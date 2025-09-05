<?php

namespace App\Infrastructure\Database;

/**
 * SeederManager - Gerenciador de seeders
 * Implementa padrão Command para execução de seeders
 */
class SeederManager
{
    private array $seeders = [];
    private string $seedersPath;

    public function __construct(string $seedersPath = null)
    {
        $this->seedersPath = $seedersPath ?: __DIR__ . '/../../../database/seeders';
    }

    /**
     * Registra um seeder
     */
    public function register(string $seederClass): void
    {
        $this->seeders[] = $seederClass;
    }

    /**
     * Executa todos os seeders registrados
     */
    public function run(): void
    {
        $this->info('Iniciando execução dos seeders...');
        
        if (empty($this->seeders)) {
            $this->loadSeeders();
        }
        
        foreach ($this->seeders as $seederClass) {
            $this->runSeeder($seederClass);
        }
        
        $this->info('Todos os seeders foram executados com sucesso!');
    }

    /**
     * Executa um seeder específico
     */
    public function runSeeder(string $seederClass): void
    {
        try {
            $this->info("Executando seeder: {$seederClass}");
            
            // Carrega o arquivo do seeder se não estiver carregado
            $this->loadSeederFile($seederClass);
            
            if (!class_exists($seederClass)) {
                throw new \Exception("Seeder {$seederClass} não encontrado");
            }
            
            $seeder = new $seederClass();
            
            if (!$seeder instanceof Seeder) {
                throw new \Exception("Seeder {$seederClass} deve estender a classe Seeder");
            }
            
            $startTime = microtime(true);
            $seeder->run();
            $endTime = microtime(true);
            
            $executionTime = round(($endTime - $startTime) * 1000, 2);
            $this->info("Seeder {$seederClass} executado em {$executionTime}ms");
            
        } catch (\Exception $e) {
            $this->error("Erro ao executar seeder {$seederClass}: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Carrega todos os seeders do diretório
     */
    private function loadSeeders(): void
    {
        if (!is_dir($this->seedersPath)) {
            $this->info('Diretório de seeders não encontrado: ' . $this->seedersPath);
            return;
        }
        
        $files = glob($this->seedersPath . '/*.php');
        
        foreach ($files as $file) {
            $className = $this->getClassNameFromFile($file);
            if ($className) {
                $this->register($className);
            }
        }
        
        // Ordena os seeders por nome para execução consistente
        sort($this->seeders);
    }

    /**
     * Carrega o arquivo do seeder
     */
    private function loadSeederFile(string $seederClass): void
    {
        $filename = $seederClass . '.php';
        $filepath = $this->seedersPath . '/' . $filename;
        
        if (file_exists($filepath)) {
            require_once $filepath;
        }
    }

    /**
     * Extrai o nome da classe do arquivo
     */
    private function getClassNameFromFile(string $file): ?string
    {
        $content = file_get_contents($file);
        
        // Procura por declaração de classe
        if (preg_match('/class\s+([a-zA-Z_][a-zA-Z0-9_]*)\s+extends\s+Seeder/', $content, $matches)) {
            return $matches[1];
        }
        
        return null;
    }

    /**
     * Cria um novo seeder
     */
    public function createSeeder(string $name): string
    {
        $className = $this->formatClassName($name);
        $filename = $className . '.php';
        $filepath = $this->seedersPath . '/' . $filename;
        
        if (file_exists($filepath)) {
            throw new \Exception("Seeder {$filename} já existe");
        }
        
        $this->ensureSeederDirectory();
        
        $template = $this->getSeederTemplate($className);
        file_put_contents($filepath, $template);
        
        return $filename;
    }

    /**
     * Formata o nome da classe
     */
    private function formatClassName(string $name): string
    {
        // Remove caracteres especiais e converte para PascalCase
        $name = preg_replace('/[^a-zA-Z0-9_]/', '_', $name);
        $parts = explode('_', $name);
        $parts = array_map('ucfirst', $parts);
        $className = implode('', $parts);
        
        // Adiciona sufixo se não existir
        if (!str_ends_with($className, 'Seeder')) {
            $className .= 'Seeder';
        }
        
        return $className;
    }

    /**
     * Garante que o diretório de seeders existe
     */
    private function ensureSeederDirectory(): void
    {
        if (!is_dir($this->seedersPath)) {
            mkdir($this->seedersPath, 0755, true);
        }
    }

    /**
     * Template para novos seeders
     */
    private function getSeederTemplate(string $className): string
    {
        return <<<PHP
<?php

use App\Infrastructure\Database\Seeder;

/**
 * {$className}
 * Seeder para popular dados na base
 */
class {$className} extends Seeder
{
    /**
     * Executa o seeder
     */
    public function run(): void
    {
        \$this->info('Executando {$className}...');
        
        // TODO: Implementar lógica do seeder
        
        // Exemplo de inserção de dados:
        // \$data = [
        //     ['name' => 'Exemplo 1', 'email' => 'exemplo1@test.com'],
        //     ['name' => 'Exemplo 2', 'email' => 'exemplo2@test.com'],
        // ];
        // 
        // \$this->insertBatch('tabela', \$data);
        
        \$this->info('{$className} executado com sucesso!');
    }
}
PHP;
    }

    /**
     * Lista todos os seeders disponíveis
     */
    public function listSeeders(): array
    {
        $this->loadSeeders();
        return $this->seeders;
    }

    /**
     * Verifica se um seeder existe
     */
    public function seederExists(string $seederClass): bool
    {
        return class_exists($seederClass) && is_subclass_of($seederClass, Seeder::class);
    }

    /**
     * Executa seeders específicos
     */
    public function runSpecific(array $seederClasses): void
    {
        $this->info('Executando seeders específicos...');
        
        foreach ($seederClasses as $seederClass) {
            if (!$this->seederExists($seederClass)) {
                $this->error("Seeder {$seederClass} não encontrado ou inválido");
                continue;
            }
            
            $this->runSeeder($seederClass);
        }
        
        $this->info('Seeders específicos executados!');
    }

    /**
     * Executa seeders com transação
     */
    public function runWithTransaction(): void
    {
        try {
            DatabaseManager::beginTransaction();
            
            $this->run();
            
            DatabaseManager::commit();
            $this->info('Todos os seeders executados com sucesso (com transação)!');
            
        } catch (\Exception $e) {
            DatabaseManager::rollback();
            $this->error('Erro durante execução dos seeders. Transação revertida.');
            throw $e;
        }
    }

    /**
     * Limpa dados antes de executar seeders
     */
    public function fresh(array $tables = []): void
    {
        $this->info('Limpando dados antes de executar seeders...');
        
        if (empty($tables)) {
            // Se não especificado, limpa todas as tabelas (exceto migrações)
            $tables = $this->getAllTables();
            $tables = array_filter($tables, function($table) {
                return $table !== 'migrations';
            });
        }
        
        // Desabilita verificação de chaves estrangeiras temporariamente
        DatabaseManager::execute('SET FOREIGN_KEY_CHECKS = 0');
        
        foreach ($tables as $table) {
            try {
                DatabaseManager::execute("TRUNCATE TABLE {$table}");
                $this->info("Tabela {$table} limpa");
            } catch (\Exception $e) {
                $this->error("Erro ao limpar tabela {$table}: " . $e->getMessage());
            }
        }
        
        // Reabilita verificação de chaves estrangeiras
        DatabaseManager::execute('SET FOREIGN_KEY_CHECKS = 1');
        
        $this->run();
    }

    /**
     * Obtém todas as tabelas do banco
     */
    private function getAllTables(): array
    {
        $result = DatabaseManager::query('SHOW TABLES');
        $tables = [];
        
        foreach ($result as $row) {
            $tables[] = array_values($row)[0];
        }
        
        return $tables;
    }

    /**
     * Exibe mensagem informativa
     */
    private function info(string $message): void
    {
        echo "[" . date('Y-m-d H:i:s') . "] INFO: {$message}\n";
    }

    /**
     * Exibe mensagem de erro
     */
    private function error(string $message): void
    {
        echo "[" . date('Y-m-d H:i:s') . "] ERROR: {$message}\n";
    }
}