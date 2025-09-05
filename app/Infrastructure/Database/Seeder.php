<?php

namespace App\Infrastructure\Database;

/**
 * Seeder - Classe base para seeders
 * Implementa padrão Template Method para população de dados
 */
abstract class Seeder
{
    protected array $data = [];
    protected int $batchSize = 100;

    public function __construct()
    {
        // Inicializa a conexão com o banco
        DatabaseManager::initialize();
    }

    /**
     * Método principal para executar o seeder
     */
    abstract public function run(): void;

    /**
     * Insere dados em lote para melhor performance
     */
    protected function insertBatch(string $table, array $data): void
    {
        if (empty($data)) {
            return;
        }

        $chunks = array_chunk($data, $this->batchSize);
        
        foreach ($chunks as $chunk) {
            $this->insertChunk($table, $chunk);
        }
    }

    /**
     * Insere um chunk de dados
     */
    private function insertChunk(string $table, array $chunk): void
    {
        if (empty($chunk)) {
            return;
        }

        $columns = array_keys($chunk[0]);
        $placeholders = '(' . implode(',', array_fill(0, count($columns), '?')) . ')';
        $values = implode(',', array_fill(0, count($chunk), $placeholders));
        
        $sql = "INSERT INTO {$table} (" . implode(',', $columns) . ") VALUES {$values}";
        
        $params = [];
        foreach ($chunk as $row) {
            foreach ($columns as $column) {
                $params[] = $row[$column] ?? null;
            }
        }
        
        DatabaseManager::execute($sql, $params);
    }

    /**
     * Insere um único registro
     */
    protected function insert(string $table, array $data): int
    {
        $columns = array_keys($data);
        $placeholders = implode(',', array_fill(0, count($columns), '?'));
        
        $sql = "INSERT INTO {$table} (" . implode(',', $columns) . ") VALUES ({$placeholders})";
        
        DatabaseManager::execute($sql, array_values($data));
        
        return (int) DatabaseManager::getConnection()->lastInsertId();
    }

    /**
     * Atualiza registros
     */
    protected function update(string $table, array $data, array $where): int
    {
        $setParts = [];
        $params = [];
        
        foreach ($data as $column => $value) {
            $setParts[] = "{$column} = ?";
            $params[] = $value;
        }
        
        $whereParts = [];
        foreach ($where as $column => $value) {
            $whereParts[] = "{$column} = ?";
            $params[] = $value;
        }
        
        $sql = "UPDATE {$table} SET " . implode(', ', $setParts) . " WHERE " . implode(' AND ', $whereParts);
        
        return DatabaseManager::execute($sql, $params);
    }

    /**
     * Remove todos os registros da tabela
     */
    protected function truncate(string $table): void
    {
        DatabaseManager::execute("TRUNCATE TABLE {$table}");
    }

    /**
     * Remove registros com condição
     */
    protected function delete(string $table, array $where = []): int
    {
        if (empty($where)) {
            DatabaseManager::execute("DELETE FROM {$table}");
            return 0;
        }
        
        $whereParts = [];
        $params = [];
        
        foreach ($where as $column => $value) {
            $whereParts[] = "{$column} = ?";
            $params[] = $value;
        }
        
        $sql = "DELETE FROM {$table} WHERE " . implode(' AND ', $whereParts);
        
        return DatabaseManager::execute($sql, $params);
    }

    /**
     * Verifica se a tabela existe
     */
    protected function tableExists(string $table): bool
    {
        return DatabaseManager::tableExists($table);
    }

    /**
     * Conta registros na tabela
     */
    protected function count(string $table, array $where = []): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$table}";
        $params = [];
        
        if (!empty($where)) {
            $whereParts = [];
            foreach ($where as $column => $value) {
                $whereParts[] = "{$column} = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $whereParts);
        }
        
        $result = DatabaseManager::fetchOne($sql, $params);
        return (int) $result['total'];
    }

    /**
     * Busca registros
     */
    protected function find(string $table, array $where = [], int $limit = null): array
    {
        $sql = "SELECT * FROM {$table}";
        $params = [];
        
        if (!empty($where)) {
            $whereParts = [];
            foreach ($where as $column => $value) {
                $whereParts[] = "{$column} = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $whereParts);
        }
        
        if ($limit !== null) {
            $sql .= " LIMIT {$limit}";
        }
        
        return DatabaseManager::query($sql, $params);
    }

    /**
     * Gera dados fake usando Factory
     */
    protected function factory(string $class, int $count = 1): array
    {
        $factoryClass = "Database\\Factories\\{$class}Factory";
        
        if (!class_exists($factoryClass)) {
            throw new \Exception("Factory {$factoryClass} não encontrada");
        }
        
        $factory = new $factoryClass();
        $data = [];
        
        for ($i = 0; $i < $count; $i++) {
            $data[] = $factory->definition();
        }
        
        return $count === 1 ? $data[0] : $data;
    }

    /**
     * Executa outro seeder
     */
    protected function call(string $seederClass): void
    {
        if (!class_exists($seederClass)) {
            throw new \Exception("Seeder {$seederClass} não encontrado");
        }
        
        $seeder = new $seederClass();
        $seeder->run();
    }

    /**
     * Exibe mensagem de progresso
     */
    protected function info(string $message): void
    {
        echo "[" . date('Y-m-d H:i:s') . "] {$message}\n";
    }

    /**
     * Gera timestamp atual
     */
    protected function now(): string
    {
        return date('Y-m-d H:i:s');
    }

    /**
     * Gera data aleatória
     */
    protected function randomDate(string $start = '-1 year', string $end = 'now'): string
    {
        $startTimestamp = strtotime($start);
        $endTimestamp = strtotime($end);
        
        $randomTimestamp = mt_rand($startTimestamp, $endTimestamp);
        
        return date('Y-m-d H:i:s', $randomTimestamp);
    }

    /**
     * Gera string aleatória
     */
    protected function randomString(int $length = 10): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $string = '';
        
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }
        
        return $string;
    }

    /**
     * Escolhe elemento aleatório do array
     */
    protected function randomChoice(array $choices)
    {
        return $choices[array_rand($choices)];
    }

    /**
     * Gera número aleatório
     */
    protected function randomInt(int $min = 1, int $max = 100): int
    {
        return mt_rand($min, $max);
    }

    /**
     * Gera boolean aleatório
     */
    protected function randomBool(): bool
    {
        return (bool) mt_rand(0, 1);
    }

    /**
     * Gera email aleatório
     */
    protected function randomEmail(): string
    {
        $domains = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 'example.com'];
        $username = strtolower($this->randomString(8));
        $domain = $this->randomChoice($domains);
        
        return "{$username}@{$domain}";
    }

    /**
     * Define o tamanho do lote
     */
    protected function setBatchSize(int $size): void
    {
        $this->batchSize = $size;
    }
}