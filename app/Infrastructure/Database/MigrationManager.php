<?php

namespace App\Infrastructure\Database;

use Exception;
use DirectoryIterator;

/**
 * MigrationManager - Gerencia migrações do banco de dados
 * Implementa padrão Command para execução de migrações
 */
class MigrationManager
{
    private string $migrationsPath;
    private string $migrationsTable = 'migrations';

    public function __construct()
    {
        $this->migrationsPath = __DIR__ . '/../../../database/migrations';
        $this->ensureMigrationsTableExists();
    }

    /**
     * Executa todas as migrações pendentes
     */
    public function migrate(): array
    {
        $executed = [];
        $migrations = $this->getPendingMigrations();

        foreach ($migrations as $migration) {
            try {
                $this->executeMigration($migration);
                $this->recordMigration($migration);
                $executed[] = $migration;
                echo "Migração executada: {$migration}\n";
            } catch (Exception $e) {
                echo "Erro ao executar migração {$migration}: " . $e->getMessage() . "\n";
                break;
            }
        }

        return $executed;
    }

    /**
     * Desfaz a última migração
     */
    public function rollback(int $steps = 1): array
    {
        $rolledBack = [];
        $migrations = $this->getExecutedMigrations($steps);

        foreach (array_reverse($migrations) as $migration) {
            try {
                $this->rollbackMigration($migration);
                $this->removeMigrationRecord($migration);
                $rolledBack[] = $migration;
                echo "Migração desfeita: {$migration}\n";
            } catch (Exception $e) {
                echo "Erro ao desfazer migração {$migration}: " . $e->getMessage() . "\n";
                break;
            }
        }

        return $rolledBack;
    }

    /**
     * Reexecuta migrações (rollback + migrate)
     */
    public function refresh(): array
    {
        $this->rollbackAll();
        return $this->migrate();
    }

    /**
     * Desfaz todas as migrações
     */
    public function rollbackAll(): array
    {
        $migrations = $this->getAllExecutedMigrations();
        $rolledBack = [];

        foreach (array_reverse($migrations) as $migration) {
            try {
                $this->rollbackMigration($migration);
                $this->removeMigrationRecord($migration);
                $rolledBack[] = $migration;
                echo "Migração desfeita: {$migration}\n";
            } catch (Exception $e) {
                echo "Erro ao desfazer migração {$migration}: " . $e->getMessage() . "\n";
                break;
            }
        }

        return $rolledBack;
    }

    /**
     * Obtém status das migrações
     */
    public function status(): array
    {
        $allMigrations = $this->getAllMigrationFiles();
        $executedMigrations = $this->getAllExecutedMigrations();
        
        $status = [];
        
        foreach ($allMigrations as $migration) {
            $status[] = [
                'migration' => $migration,
                'executed' => in_array($migration, $executedMigrations),
                'batch' => $this->getMigrationBatch($migration)
            ];
        }
        
        return $status;
    }

    /**
     * Cria uma nova migração
     */
    public function create(string $name): string
    {
        $timestamp = date('Y_m_d_His');
        $className = $this->studlyCase($name);
        $filename = "{$timestamp}_{$name}.php";
        $filepath = $this->migrationsPath . '/' . $filename;

        $stub = $this->getMigrationStub($className);
        
        if (!file_put_contents($filepath, $stub)) {
            throw new Exception("Não foi possível criar a migração: {$filename}");
        }

        return $filename;
    }

    /**
     * Obtém migrações pendentes
     */
    private function getPendingMigrations(): array
    {
        $allMigrations = $this->getAllMigrationFiles();
        $executedMigrations = $this->getAllExecutedMigrations();
        
        return array_diff($allMigrations, $executedMigrations);
    }

    /**
     * Obtém todas as migrações executadas
     */
    private function getAllExecutedMigrations(): array
    {
        $sql = "SELECT migration FROM {$this->migrationsTable} ORDER BY batch ASC, id ASC";
        $results = DatabaseManager::fetchAll($sql);
        
        return array_column($results, 'migration');
    }

    /**
     * Obtém migrações executadas (limitado por steps)
     */
    private function getExecutedMigrations(int $steps): array
    {
        $sql = "SELECT migration FROM {$this->migrationsTable} ORDER BY batch DESC, id DESC LIMIT ?";
        $results = DatabaseManager::fetchAll($sql, [$steps]);
        
        return array_column($results, 'migration');
    }

    /**
     * Obtém todos os arquivos de migração
     */
    private function getAllMigrationFiles(): array
    {
        $migrations = [];
        
        if (!is_dir($this->migrationsPath)) {
            return $migrations;
        }
        
        $iterator = new DirectoryIterator($this->migrationsPath);
        
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $migrations[] = $file->getBasename('.php');
            }
        }
        
        sort($migrations);
        return $migrations;
    }

    /**
     * Executa uma migração
     */
    private function executeMigration(string $migration): void
    {
        $migrationInstance = $this->loadMigration($migration);
        $migrationInstance->up();
    }

    /**
     * Desfaz uma migração
     */
    private function rollbackMigration(string $migration): void
    {
        $migrationInstance = $this->loadMigration($migration);
        $migrationInstance->down();
    }

    /**
     * Carrega uma instância de migração
     */
    private function loadMigration(string $migration): object
    {
        $filepath = $this->migrationsPath . '/' . $migration . '.php';
        
        if (!file_exists($filepath)) {
            throw new Exception("Arquivo de migração não encontrado: {$migration}");
        }
        
        require_once $filepath;
        
        // Extrai o nome da classe do nome do arquivo
        // Formato: 2025_01_05_080000_create_users_table
        $parts = explode('_', $migration);
        // Remove as 4 primeiras partes (ano, mês, dia, hora)
        $classParts = array_slice($parts, 4);
        
        $className = $this->studlyCase(implode('_', $classParts));
        
        if (!class_exists($className)) {
            throw new Exception("Classe de migração não encontrada: {$className}");
        }
        
        return new $className();
    }

    /**
     * Registra uma migração como executada
     */
    private function recordMigration(string $migration): void
    {
        $batch = $this->getNextBatchNumber();
        
        $sql = "INSERT INTO {$this->migrationsTable} (migration, batch) VALUES (?, ?)";
        DatabaseManager::execute($sql, [$migration, $batch]);
    }

    /**
     * Remove registro de migração
     */
    private function removeMigrationRecord(string $migration): void
    {
        $sql = "DELETE FROM {$this->migrationsTable} WHERE migration = ?";
        DatabaseManager::execute($sql, [$migration]);
    }

    /**
     * Obtém o próximo número de batch
     */
    private function getNextBatchNumber(): int
    {
        $sql = "SELECT MAX(batch) as max_batch FROM {$this->migrationsTable}";
        $result = DatabaseManager::fetchOne($sql);
        
        return ($result['max_batch'] ?? 0) + 1;
    }

    /**
     * Obtém o batch de uma migração
     */
    private function getMigrationBatch(string $migration): ?int
    {
        $sql = "SELECT batch FROM {$this->migrationsTable} WHERE migration = ?";
        $result = DatabaseManager::fetchOne($sql, [$migration]);
        
        return $result ? (int) $result['batch'] : null;
    }

    /**
     * Garante que a tabela de migrações existe
     */
    private function ensureMigrationsTableExists(): void
    {
        if (!DatabaseManager::tableExists($this->migrationsTable)) {
            $sql = "
                CREATE TABLE {$this->migrationsTable} (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    migration VARCHAR(255) NOT NULL,
                    batch INT NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ";
            
            DatabaseManager::execute($sql);
        }
    }

    /**
     * Obtém o stub da migração
     */
    private function getMigrationStub(string $className): string
    {
        return "<?php\n\nuse App\\Infrastructure\\Database\\Migration;\nuse App\\Infrastructure\\Database\\Schema;\n\nclass {$className} extends Migration\n{\n    /**\n     * Executa a migração\n     */\n    public function up(): void\n    {\n        Schema::create('table_name', function (\$table) {\n            \$table->id();\n            \$table->timestamps();\n        });\n    }\n\n    /**\n     * Desfaz a migração\n     */\n    public function down(): void\n    {\n        Schema::dropIfExists('table_name');\n    }\n}\n";
    }

    /**
     * Converte string para StudlyCase
     */
    private function studlyCase(string $string): string
    {
        return str_replace(' ', '', ucwords(str_replace(['_', '-'], ' ', $string)));
    }
}