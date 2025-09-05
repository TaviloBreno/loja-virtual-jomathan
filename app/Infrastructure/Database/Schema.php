<?php

namespace App\Infrastructure\Database;

/**
 * Schema - Builder para criação de tabelas
 * Implementa padrão Builder para construção de schemas
 */
class Schema
{
    /**
     * Cria uma nova tabela
     */
    public static function create(string $table, callable $callback): void
    {
        $blueprint = new Blueprint($table);
        $callback($blueprint);
        
        $sql = $blueprint->toSql();
        DatabaseManager::execute($sql);
    }

    /**
     * Modifica uma tabela existente
     */
    public static function table(string $table, callable $callback): void
    {
        $blueprint = new Blueprint($table, 'alter');
        $callback($blueprint);
        
        $statements = $blueprint->toAlterSql();
        foreach ($statements as $sql) {
            DatabaseManager::execute($sql);
        }
    }

    /**
     * Remove uma tabela
     */
    public static function drop(string $table): void
    {
        DatabaseManager::execute("DROP TABLE {$table}");
    }

    /**
     * Remove uma tabela se ela existir
     */
    public static function dropIfExists(string $table): void
    {
        DatabaseManager::execute("DROP TABLE IF EXISTS {$table}");
    }

    /**
     * Verifica se uma tabela existe
     */
    public static function hasTable(string $table): bool
    {
        return DatabaseManager::tableExists($table);
    }

    /**
     * Verifica se uma coluna existe
     */
    public static function hasColumn(string $table, string $column): bool
    {
        $columns = DatabaseManager::getTableColumns($table);
        
        foreach ($columns as $col) {
            if ($col['Field'] === $column) {
                return true;
            }
        }
        
        return false;
    }

    /**
     * Renomeia uma tabela
     */
    public static function rename(string $from, string $to): void
    {
        DatabaseManager::execute("RENAME TABLE {$from} TO {$to}");
    }
}

/**
 * Blueprint - Construtor de definições de tabela
 */
class Blueprint
{
    private string $table;
    private string $action;
    private array $columns = [];
    private array $indexes = [];
    private array $foreignKeys = [];
    private string $engine = 'InnoDB';
    private string $charset = 'utf8mb4';
    private string $collation = 'utf8mb4_unicode_ci';

    public function __construct(string $table, string $action = 'create')
    {
        $this->table = $table;
        $this->action = $action;
    }

    /**
     * Coluna ID auto-incremento
     */
    public function id(string $column = 'id'): Column
    {
        $col = $this->bigInteger($column)->autoIncrement();
        // Adiciona chave primária automaticamente
        $this->primary([$column]);
        return $col;
    }

    /**
     * Coluna string/varchar
     */
    public function string(string $column, int $length = 255): Column
    {
        return $this->addColumn($column, 'VARCHAR', $length);
    }

    /**
     * Coluna text
     */
    public function text(string $column): Column
    {
        return $this->addColumn($column, 'TEXT');
    }

    /**
     * Coluna longText
     */
    public function longText(string $column): Column
    {
        return $this->addColumn($column, 'LONGTEXT');
    }

    /**
     * Coluna integer
     */
    public function integer(string $column): Column
    {
        return $this->addColumn($column, 'INT');
    }

    /**
     * Coluna big integer
     */
    public function bigInteger(string $column): Column
    {
        return $this->addColumn($column, 'BIGINT');
    }

    /**
     * Coluna small integer
     */
    public function smallInteger(string $column): Column
    {
        return $this->addColumn($column, 'SMALLINT');
    }

    /**
     * Coluna tiny integer
     */
    public function tinyInteger(string $column): Column
    {
        return $this->addColumn($column, 'TINYINT');
    }

    /**
     * Coluna boolean
     */
    public function boolean(string $column): Column
    {
        return $this->tinyInteger($column)->default(0);
    }

    /**
     * Coluna decimal
     */
    public function decimal(string $column, int $precision = 8, int $scale = 2): Column
    {
        return $this->addColumn($column, 'DECIMAL', null, "{$precision},{$scale}");
    }

    /**
     * Coluna float
     */
    public function float(string $column, int $precision = 8, int $scale = 2): Column
    {
        return $this->addColumn($column, 'FLOAT', null, "{$precision},{$scale}");
    }

    /**
     * Coluna double
     */
    public function double(string $column): Column
    {
        return $this->addColumn($column, 'DOUBLE');
    }

    /**
     * Coluna date
     */
    public function date(string $column): Column
    {
        return $this->addColumn($column, 'DATE');
    }

    /**
     * Coluna datetime
     */
    public function dateTime(string $column): Column
    {
        return $this->addColumn($column, 'DATETIME');
    }

    /**
     * Coluna timestamp
     */
    public function timestamp(string $column): Column
    {
        return $this->addColumn($column, 'TIMESTAMP');
    }

    /**
     * Coluna time
     */
    public function time(string $column): Column
    {
        return $this->addColumn($column, 'TIME');
    }

    /**
     * Coluna year
     */
    public function year(string $column): Column
    {
        return $this->addColumn($column, 'YEAR');
    }

    /**
     * Coluna JSON
     */
    public function json(string $column): Column
    {
        return $this->addColumn($column, 'JSON');
    }

    /**
     * Coluna enum
     */
    public function enum(string $column, array $values): Column
    {
        $valuesList = "'" . implode("','", $values) . "'";
        return $this->addColumn($column, 'ENUM', null, $valuesList);
    }

    /**
     * Colunas de timestamp (created_at, updated_at)
     */
    public function timestamps(): void
    {
        $this->timestamp('created_at')->nullable()->default('CURRENT_TIMESTAMP');
        $this->timestamp('updated_at')->nullable()->default('CURRENT_TIMESTAMP');
    }

    /**
     * Coluna de soft delete
     */
    public function softDeletes(): Column
    {
        return $this->timestamp('deleted_at')->nullable();
    }

    /**
     * Chave estrangeira
     */
    public function foreignId(string $column): Column
    {
        return $this->bigInteger($column)->unsigned();
    }

    /**
     * Adiciona índice
     */
    public function index(array $columns, string $name = null): void
    {
        $this->indexes[] = [
            'type' => 'INDEX',
            'columns' => $columns,
            'name' => $name ?: $this->table . '_' . implode('_', $columns) . '_index'
        ];
    }

    /**
     * Adiciona índice único
     */
    public function unique(array $columns, string $name = null): void
    {
        $this->indexes[] = [
            'type' => 'UNIQUE',
            'columns' => $columns,
            'name' => $name ?: $this->table . '_' . implode('_', $columns) . '_unique'
        ];
    }

    /**
     * Adiciona chave primária
     */
    public function primary(array $columns, string $name = null): void
    {
        $this->indexes[] = [
            'type' => 'PRIMARY KEY',
            'columns' => $columns,
            'name' => $name
        ];
    }

    /**
     * Adiciona chave estrangeira
     */
    public function foreign(string $column): ForeignKeyDefinition
    {
        $foreign = new ForeignKeyDefinition($column, $this->table);
        $this->foreignKeys[] = $foreign;
        return $foreign;
    }

    /**
     * Remove coluna
     */
    public function dropColumn(string $column): void
    {
        $this->columns[] = new Column($column, 'DROP');
    }

    /**
     * Remove índice
     */
    public function dropIndex(string $name): void
    {
        $this->indexes[] = [
            'type' => 'DROP INDEX',
            'name' => $name
        ];
    }

    /**
     * Adiciona uma coluna
     */
    private function addColumn(string $name, string $type, ?int $length = null, ?string $parameters = null): Column
    {
        $column = new Column($name, $type, $length, $parameters);
        $this->columns[] = $column;
        return $column;
    }

    /**
     * Gera SQL para criação da tabela
     */
    public function toSql(): string
    {
        $sql = "CREATE TABLE {$this->table} (\n";
        
        $definitions = [];
        
        // Adiciona colunas
        foreach ($this->columns as $column) {
            $definitions[] = '  ' . $column->toSql();
        }
        
        // Adiciona índices
        foreach ($this->indexes as $index) {
            if ($index['type'] === 'PRIMARY KEY') {
                $definitions[] = '  PRIMARY KEY (' . implode(', ', $index['columns']) . ')';
            } else {
                $columnList = implode(', ', $index['columns']);
                $definitions[] = "  {$index['type']} {$index['name']} ({$columnList})";
            }
        }
        
        // Adiciona chaves estrangeiras
        foreach ($this->foreignKeys as $foreign) {
            $definitions[] = '  ' . $foreign->toSql();
        }
        
        $sql .= implode(",\n", $definitions);
        $sql .= "\n) ENGINE={$this->engine} DEFAULT CHARSET={$this->charset} COLLATE={$this->collation}";
        
        return $sql;
    }

    /**
     * Gera SQL para alteração da tabela
     */
    public function toAlterSql(): array
    {
        $statements = [];
        
        foreach ($this->columns as $column) {
            if ($column->getType() === 'DROP') {
                $statements[] = "ALTER TABLE {$this->table} DROP COLUMN {$column->getName()}";
            } else {
                $statements[] = "ALTER TABLE {$this->table} ADD COLUMN " . $column->toSql();
            }
        }
        
        foreach ($this->indexes as $index) {
            if ($index['type'] === 'DROP INDEX') {
                $statements[] = "ALTER TABLE {$this->table} DROP INDEX {$index['name']}";
            } else {
                $columnList = implode(', ', $index['columns']);
                $statements[] = "ALTER TABLE {$this->table} ADD {$index['type']} {$index['name']} ({$columnList})";
            }
        }
        
        foreach ($this->foreignKeys as $foreign) {
            $statements[] = "ALTER TABLE {$this->table} ADD " . $foreign->toSql();
        }
        
        return $statements;
    }
}

/**
 * Column - Representa uma coluna da tabela
 */
class Column
{
    private string $name;
    private string $type;
    private ?int $length;
    private ?string $parameters;
    private bool $nullable = false;
    private $default = null;
    private bool $autoIncrement = false;
    private bool $unsigned = false;
    private bool $primary = false;
    private bool $unique = false;
    private ?string $comment = null;

    public function __construct(string $name, string $type, ?int $length = null, ?string $parameters = null)
    {
        $this->name = $name;
        $this->type = $type;
        $this->length = $length;
        $this->parameters = $parameters;
    }

    public function nullable(bool $nullable = true): self
    {
        $this->nullable = $nullable;
        return $this;
    }

    public function default($value): self
    {
        $this->default = $value;
        return $this;
    }

    public function autoIncrement(): self
    {
        $this->autoIncrement = true;
        return $this;
    }

    public function unsigned(): self
    {
        $this->unsigned = true;
        return $this;
    }



    public function unique(): self
    {
        $this->unique = true;
        return $this;
    }

    public function comment(string $comment): self
    {
        $this->comment = $comment;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function toSql(): string
    {
        $sql = "{$this->name} {$this->type}";
        
        if ($this->length !== null) {
            $sql .= "({$this->length})";
        } elseif ($this->parameters !== null) {
            $sql .= "({$this->parameters})";
        }
        
        if ($this->unsigned) {
            $sql .= ' UNSIGNED';
        }
        
        if (!$this->nullable) {
            $sql .= ' NOT NULL';
        }
        
        if ($this->autoIncrement) {
            $sql .= ' AUTO_INCREMENT';
        }
        
        if ($this->default !== null) {
            if (is_string($this->default) && !in_array(strtoupper($this->default), ['CURRENT_TIMESTAMP', 'NULL'])) {
                $sql .= " DEFAULT '{$this->default}'";
            } else {
                $sql .= " DEFAULT {$this->default}";
            }
        }
        
        if ($this->comment !== null) {
            $sql .= " COMMENT '{$this->comment}'";
        }
        
        return $sql;
    }
}

/**
 * ForeignKeyDefinition - Define chaves estrangeiras
 */
class ForeignKeyDefinition
{
    private string $column;
    private string $table;
    private string $referencedTable;
    private string $referencedColumn = 'id';
    private string $onDelete = 'CASCADE';
    private string $onUpdate = 'CASCADE';
    private ?string $name = null;

    public function __construct(string $column, string $table)
    {
        $this->column = $column;
        $this->table = $table;
    }

    public function references(string $column): self
    {
        $this->referencedColumn = $column;
        return $this;
    }

    public function on(string $table): self
    {
        $this->referencedTable = $table;
        return $this;
    }

    public function onDelete(string $action): self
    {
        $this->onDelete = $action;
        return $this;
    }

    public function onUpdate(string $action): self
    {
        $this->onUpdate = $action;
        return $this;
    }

    public function name(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function toSql(): string
    {
        $constraintName = $this->name ?: $this->table . '_' . $this->column . '_foreign';
        
        return "CONSTRAINT {$constraintName} FOREIGN KEY ({$this->column}) REFERENCES {$this->referencedTable}({$this->referencedColumn}) ON DELETE {$this->onDelete} ON UPDATE {$this->onUpdate}";
    }
}