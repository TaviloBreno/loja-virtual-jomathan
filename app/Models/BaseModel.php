<?php

namespace App\Models;

use App\Infrastructure\Database\DatabaseManager;

abstract class BaseModel
{
    protected string $table = '';
    protected array $fillable = [];
    protected string $primaryKey = 'id';

    /**
     * Busca um registro por ID
     */
    public function find(int $id): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ? LIMIT 1";
        return DatabaseManager::fetchOne($sql, [$id]);
    }

    /**
     * Busca todos os registros
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$this->primaryKey}";
        return DatabaseManager::fetchAll($sql);
    }

    /**
     * Cria um novo registro
     */
    public function create(array $data): int
    {
        // Filtra apenas campos permitidos
        $filteredData = $this->filterFillable($data);
        
        if (empty($filteredData)) {
            throw new \InvalidArgumentException('Nenhum campo válido fornecido');
        }

        $fields = array_keys($filteredData);
        $placeholders = array_fill(0, count($fields), '?');
        
        $sql = "INSERT INTO {$this->table} (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
        
        DatabaseManager::execute($sql, array_values($filteredData));
        return DatabaseManager::lastInsertId();
    }

    /**
     * Atualiza um registro
     */
    public function update(int $id, array $data): bool
    {
        // Filtra apenas campos permitidos
        $filteredData = $this->filterFillable($data);
        
        if (empty($filteredData)) {
            return false;
        }

        $fields = array_keys($filteredData);
        $setClause = array_map(fn($field) => "{$field} = ?", $fields);
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $setClause) . " WHERE {$this->primaryKey} = ?";
        
        $params = array_values($filteredData);
        $params[] = $id;
        
        return DatabaseManager::execute($sql, $params);
    }

    /**
     * Remove um registro
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = ?";
        return DatabaseManager::execute($sql, [$id]);
    }

    /**
     * Busca registros com condições
     */
    public function where(array $conditions): array
    {
        if (empty($conditions)) {
            return $this->findAll();
        }

        $whereClause = [];
        $params = [];
        
        foreach ($conditions as $field => $value) {
            $whereClause[] = "{$field} = ?";
            $params[] = $value;
        }
        
        $sql = "SELECT * FROM {$this->table} WHERE " . implode(' AND ', $whereClause);
        return DatabaseManager::fetchAll($sql, $params);
    }

    /**
     * Busca primeiro registro com condições
     */
    public function whereFirst(array $conditions): ?array
    {
        if (empty($conditions)) {
            return null;
        }

        $whereClause = [];
        $params = [];
        
        foreach ($conditions as $field => $value) {
            $whereClause[] = "{$field} = ?";
            $params[] = $value;
        }
        
        $sql = "SELECT * FROM {$this->table} WHERE " . implode(' AND ', $whereClause) . " LIMIT 1";
        return DatabaseManager::fetchOne($sql, $params);
    }

    /**
     * Conta registros
     */
    public function count(array $conditions = []): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $params = [];
        
        if (!empty($conditions)) {
            $whereClause = [];
            foreach ($conditions as $field => $value) {
                $whereClause[] = "{$field} = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $whereClause);
        }
        
        $result = DatabaseManager::fetchOne($sql, $params);
        return (int) $result['total'];
    }

    /**
     * Verifica se um registro existe
     */
    public function exists(int $id): bool
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE {$this->primaryKey} = ?";
        $result = DatabaseManager::fetchOne($sql, [$id]);
        return (int) $result['total'] > 0;
    }

    /**
     * Paginação
     */
    public function paginate(int $limit = 10, int $offset = 0): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$this->primaryKey} LIMIT ? OFFSET ?";
        return DatabaseManager::fetchAll($sql, [$limit, $offset]);
    }

    /**
     * Filtra dados pelos campos permitidos
     */
    protected function filterFillable(array $data): array
    {
        if (empty($this->fillable)) {
            return $data;
        }
        
        return array_intersect_key($data, array_flip($this->fillable));
    }

    /**
     * Obtém o nome da tabela
     */
    public function getTable(): string
    {
        return $this->table;
    }

    /**
     * Obtém os campos permitidos
     */
    public function getFillable(): array
    {
        return $this->fillable;
    }

    /**
     * Obtém a chave primária
     */
    public function getPrimaryKey(): string
    {
        return $this->primaryKey;
    }
}