<?php

namespace App\Domain\Entities;

use App\Infrastructure\Database\DatabaseManager;
use PDO;
use DateTime;

/**
 * BaseEntity - Classe base para todas as entidades
 * Implementa padrão Active Record com Repository
 */
abstract class BaseEntity
{
    protected array $attributes = [];
    protected array $original = [];
    protected bool $exists = false;
    protected array $fillable = [];
    protected array $guarded = ['id'];
    protected array $casts = [];
    protected array $dates = ['created_at', 'updated_at'];
    
    // Propriedades que devem ser definidas nas classes filhas
    protected string $table;
    protected string $primaryKey = 'id';
    protected bool $timestamps = true;

    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    /**
     * Preenche a entidade com dados
     */
    public function fill(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            if ($this->isFillable($key)) {
                $this->setAttribute($key, $value);
            }
        }

        return $this;
    }

    /**
     * Verifica se um atributo é preenchível
     */
    protected function isFillable(string $key): bool
    {
        if (in_array($key, $this->guarded)) {
            return false;
        }

        if (empty($this->fillable)) {
            return true;
        }

        return in_array($key, $this->fillable);
    }

    /**
     * Define um atributo
     */
    public function setAttribute(string $key, $value): void
    {
        // Aplica cast se definido
        if (isset($this->casts[$key])) {
            $value = $this->castAttribute($key, $value);
        }

        $this->attributes[$key] = $value;
    }

    /**
     * Obtém um atributo
     */
    public function getAttribute(string $key)
    {
        if (!array_key_exists($key, $this->attributes)) {
            return null;
        }

        $value = $this->attributes[$key];

        // Aplica cast se definido
        if (isset($this->casts[$key])) {
            return $this->castAttribute($key, $value);
        }

        return $value;
    }

    /**
     * Aplica cast a um atributo
     */
    protected function castAttribute(string $key, $value)
    {
        $cast = $this->casts[$key];

        switch ($cast) {
            case 'int':
            case 'integer':
                return (int) $value;
            case 'float':
            case 'double':
                return (float) $value;
            case 'string':
                return (string) $value;
            case 'bool':
            case 'boolean':
                return (bool) $value;
            case 'array':
                return is_string($value) ? json_decode($value, true) : $value;
            case 'json':
                return is_string($value) ? json_decode($value, true) : $value;
            case 'datetime':
                return $value instanceof DateTime ? $value : new DateTime($value);
            default:
                return $value;
        }
    }

    /**
     * Salva a entidade no banco de dados
     */
    public function save(): bool
    {
        if ($this->exists) {
            return $this->update();
        }

        return $this->insert();
    }

    /**
     * Insere nova entidade
     */
    protected function insert(): bool
    {
        if ($this->timestamps) {
            $now = date('Y-m-d H:i:s');
            $this->setAttribute('created_at', $now);
            $this->setAttribute('updated_at', $now);
        }

        $attributes = $this->getAttributesForSave();
        $columns = array_keys($attributes);
        $placeholders = array_map(fn($col) => ":{$col}", $columns);

        $sql = "INSERT INTO {$this->table} (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
        
        $pdo = DatabaseManager::getConnection();
        $stmt = $pdo->prepare($sql);
        
        $result = $stmt->execute($attributes);
        
        if ($result) {
            $this->setAttribute($this->primaryKey, $pdo->lastInsertId());
            $this->exists = true;
            $this->syncOriginal();
        }

        return $result;
    }

    /**
     * Atualiza entidade existente
     */
    protected function update(): bool
    {
        if ($this->timestamps) {
            $this->setAttribute('updated_at', date('Y-m-d H:i:s'));
        }

        $attributes = $this->getAttributesForSave();
        $sets = array_map(fn($col) => "{$col} = :{$col}", array_keys($attributes));

        $sql = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE {$this->primaryKey} = :id";
        
        $attributes['id'] = $this->getAttribute($this->primaryKey);
        
        $pdo = DatabaseManager::getConnection();
        $stmt = $pdo->prepare($sql);
        
        $result = $stmt->execute($attributes);
        
        if ($result) {
            $this->syncOriginal();
        }

        return $result;
    }

    /**
     * Deleta a entidade
     */
    public function delete(): bool
    {
        if (!$this->exists) {
            return false;
        }

        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        
        $pdo = DatabaseManager::getConnection();
        $stmt = $pdo->prepare($sql);
        
        $result = $stmt->execute(['id' => $this->getAttribute($this->primaryKey)]);
        
        if ($result) {
            $this->exists = false;
        }

        return $result;
    }

    /**
     * Busca por ID
     */
    public static function find($id): ?static
    {
        $instance = new static();
        
        $sql = "SELECT * FROM {$instance->table} WHERE {$instance->primaryKey} = :id LIMIT 1";
        
        $pdo = DatabaseManager::getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);
        
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$data) {
            return null;
        }

        return $instance->newFromBuilder($data);
    }

    /**
     * Busca todos os registros
     */
    public static function all(): array
    {
        $instance = new static();
        
        $sql = "SELECT * FROM {$instance->table}";
        
        $pdo = DatabaseManager::getConnection();
        $stmt = $pdo->query($sql);
        
        $results = [];
        while ($data = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[] = $instance->newFromBuilder($data);
        }

        return $results;
    }

    /**
     * Cria nova instância a partir de dados do banco
     */
    public function newFromBuilder(array $attributes): static
    {
        $instance = new static();
        $instance->attributes = $attributes;
        $instance->exists = true;
        $instance->syncOriginal();
        
        return $instance;
    }
    
    /**
     * Conta registros
     */
    public static function count(): int
    {
        $instance = new static();
        
        $sql = "SELECT COUNT(*) as count FROM {$instance->table}";
        
        $pdo = DatabaseManager::getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return (int) $result['count'];
    }
    
    /**
     * Adiciona condição WHERE
     */
    public static function where(string $column, string $operator, $value = null): QueryBuilder
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $instance = new static();
        $builder = new QueryBuilder($instance->table, static::class);
        
        return $builder->where($column, $operator, $value);
    }
    
    /**
     * Ordena resultados
     */
    public static function orderBy(string $column, string $direction = 'asc'): QueryBuilder
    {
        $instance = new static();
        $builder = new QueryBuilder($instance->table, static::class);
        
        return $builder->orderBy($column, $direction);
    }
    


    /**
     * Sincroniza atributos originais
     */
    protected function syncOriginal(): void
    {
        $this->original = $this->attributes;
    }

    /**
     * Obtém atributos para salvar
     */
    protected function getAttributesForSave(): array
    {
        $attributes = $this->attributes;
        
        // Remove a chave primária se for auto-incremento
        if (!$this->exists) {
            unset($attributes[$this->primaryKey]);
        }

        return $attributes;
    }

    /**
     * Converte para array
     */
    public function toArray(): array
    {
        return $this->attributes;
    }

    /**
     * Converte para JSON
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }

    /**
     * Magic methods
     */
    public function __get(string $key)
    {
        return $this->getAttribute($key);
    }

    public function __set(string $key, $value): void
    {
        $this->setAttribute($key, $value);
    }

    public function __isset(string $key): bool
    {
        return array_key_exists($key, $this->attributes);
    }
}