<?php

namespace App\Domain\Entities;

use App\Infrastructure\Database\DatabaseManager;
use PDO;

/**
 * QueryBuilder - Construtor de consultas SQL
 * Implementa padrão Builder para construção de queries
 */
class QueryBuilder
{
    private string $table;
    private string $modelClass;
    private array $wheres = [];
    private array $orders = [];
    private ?int $limitValue = null;
    private ?int $offsetValue = null;
    
    public function __construct(string $table, string $modelClass)
    {
        $this->table = $table;
        $this->modelClass = $modelClass;
    }
    
    /**
     * Adiciona condição WHERE
     */
    public function where(string $column, string $operator, $value): self
    {
        $this->wheres[] = [
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
            'boolean' => 'AND'
        ];
        
        return $this;
    }
    
    /**
     * Adiciona condição WHERE com OR
     */
    public function orWhere(string $column, string $operator, $value): self
    {
        $this->wheres[] = [
            'column' => $column,
            'operator' => $operator,
            'value' => $value,
            'boolean' => 'OR'
        ];
        
        return $this;
    }
    
    /**
     * Adiciona ordenação
     */
    public function orderBy(string $column, string $direction = 'asc'): self
    {
        $this->orders[] = [
            'column' => $column,
            'direction' => strtoupper($direction)
        ];
        
        return $this;
    }
    
    /**
     * Define limite de resultados
     */
    public function limit(int $limit): self
    {
        $this->limitValue = $limit;
        
        return $this;
    }
    
    /**
     * Define offset
     */
    public function offset(int $offset): self
    {
        $this->offsetValue = $offset;
        
        return $this;
    }
    
    /**
     * Conta registros
     */
    public function count(): int
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $params = [];
        
        if (!empty($this->wheres)) {
            $whereClause = $this->buildWhereClause($params);
            $sql .= " WHERE {$whereClause}";
        }
        
        $pdo = DatabaseManager::getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (int) $result['count'];
    }
    
    /**
     * Executa a query e retorna resultados
     */
    public function get(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];
        
        if (!empty($this->wheres)) {
            $whereClause = $this->buildWhereClause($params);
            $sql .= " WHERE {$whereClause}";
        }
        
        if (!empty($this->orders)) {
            $orderClause = $this->buildOrderClause();
            $sql .= " ORDER BY {$orderClause}";
        }
        
        if ($this->limitValue !== null) {
            $sql .= " LIMIT {$this->limitValue}";
        }
        
        if ($this->offsetValue !== null) {
            $sql .= " OFFSET {$this->offsetValue}";
        }
        
        $pdo = DatabaseManager::getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $modelClass = $this->modelClass;
            $model = new $modelClass();
            $results[] = $model->newFromBuilder($row);
        }
        
        return $results;
    }
    
    /**
     * Retorna o primeiro resultado
     */
    public function first(): ?object
    {
        $results = $this->limit(1)->get();
        
        return $results[0] ?? null;
    }
    
    /**
     * Constrói cláusula WHERE
     */
    private function buildWhereClause(array &$params): string
    {
        $conditions = [];
        $paramIndex = 0;
        
        foreach ($this->wheres as $index => $where) {
            $paramName = "param_{$paramIndex}";
            $condition = "{$where['column']} {$where['operator']} :{$paramName}";
            
            if ($index > 0) {
                $condition = "{$where['boolean']} {$condition}";
            }
            
            $conditions[] = $condition;
            $params[$paramName] = $where['value'];
            $paramIndex++;
        }
        
        return implode(' ', $conditions);
    }
    
    /**
     * Constrói cláusula ORDER BY
     */
    private function buildOrderClause(): string
    {
        $orders = [];
        
        foreach ($this->orders as $order) {
            $orders[] = "{$order['column']} {$order['direction']}";
        }
        
        return implode(', ', $orders);
    }
}