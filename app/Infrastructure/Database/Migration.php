<?php

namespace App\Infrastructure\Database;

/**
 * Migration - Classe base para migrações
 * Define interface comum para todas as migrações
 */
abstract class Migration
{
    /**
     * Executa a migração
     */
    abstract public function up(): void;

    /**
     * Desfaz a migração
     */
    abstract public function down(): void;

    /**
     * Executa SQL bruto
     */
    protected function sql(string $query, array $params = []): void
    {
        DatabaseManager::execute($query, $params);
    }

    /**
     * Executa múltiplas queries SQL
     */
    protected function multiSql(string $queries): void
    {
        DatabaseManager::multiQuery($queries);
    }

    /**
     * Verifica se uma tabela existe
     */
    protected function tableExists(string $table): bool
    {
        return DatabaseManager::tableExists($table);
    }

    /**
     * Verifica se uma coluna existe
     */
    protected function columnExists(string $table, string $column): bool
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
     * Adiciona uma coluna se ela não existir
     */
    protected function addColumnIfNotExists(string $table, string $column, string $definition): void
    {
        if (!$this->columnExists($table, $column)) {
            $this->sql("ALTER TABLE {$table} ADD COLUMN {$column} {$definition}");
        }
    }

    /**
     * Remove uma coluna se ela existir
     */
    protected function dropColumnIfExists(string $table, string $column): void
    {
        if ($this->columnExists($table, $column)) {
            $this->sql("ALTER TABLE {$table} DROP COLUMN {$column}");
        }
    }

    /**
     * Cria um índice
     */
    protected function createIndex(string $table, array $columns, string $name = null, string $type = ''): void
    {
        $indexName = $name ?: $table . '_' . implode('_', $columns) . '_index';
        $columnList = implode(', ', $columns);
        
        $sql = "CREATE {$type} INDEX {$indexName} ON {$table} ({$columnList})";
        $this->sql($sql);
    }

    /**
     * Remove um índice
     */
    protected function dropIndex(string $table, string $indexName): void
    {
        $this->sql("DROP INDEX {$indexName} ON {$table}");
    }

    /**
     * Adiciona chave estrangeira
     */
    protected function addForeignKey(
        string $table,
        string $column,
        string $referencedTable,
        string $referencedColumn = 'id',
        string $onDelete = 'CASCADE',
        string $onUpdate = 'CASCADE'
    ): void {
        $constraintName = $table . '_' . $column . '_foreign';
        
        $sql = "ALTER TABLE {$table} 
                ADD CONSTRAINT {$constraintName} 
                FOREIGN KEY ({$column}) 
                REFERENCES {$referencedTable}({$referencedColumn}) 
                ON DELETE {$onDelete} 
                ON UPDATE {$onUpdate}";
        
        $this->sql($sql);
    }

    /**
     * Remove chave estrangeira
     */
    protected function dropForeignKey(string $table, string $constraintName): void
    {
        $this->sql("ALTER TABLE {$table} DROP FOREIGN KEY {$constraintName}");
    }

    /**
     * Renomeia uma tabela
     */
    protected function renameTable(string $from, string $to): void
    {
        $this->sql("RENAME TABLE {$from} TO {$to}");
    }

    /**
     * Renomeia uma coluna
     */
    protected function renameColumn(string $table, string $from, string $to, string $definition): void
    {
        $this->sql("ALTER TABLE {$table} CHANGE {$from} {$to} {$definition}");
    }

    /**
     * Modifica uma coluna
     */
    protected function modifyColumn(string $table, string $column, string $definition): void
    {
        $this->sql("ALTER TABLE {$table} MODIFY {$column} {$definition}");
    }

    /**
     * Trunca uma tabela
     */
    protected function truncate(string $table): void
    {
        $this->sql("TRUNCATE TABLE {$table}");
    }

    /**
     * Insere dados
     */
    protected function insert(string $table, array $data): void
    {
        if (empty($data)) {
            return;
        }

        // Se é um array de arrays, insere múltiplos registros
        if (is_array($data[0] ?? null)) {
            foreach ($data as $row) {
                $this->insertSingle($table, $row);
            }
        } else {
            $this->insertSingle($table, $data);
        }
    }

    /**
     * Insere um único registro
     */
    private function insertSingle(string $table, array $data): void
    {
        $columns = array_keys($data);
        $placeholders = array_map(fn($col) => ":{$col}", $columns);
        
        $sql = "INSERT INTO {$table} (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
        $this->sql($sql, $data);
    }

    /**
     * Atualiza dados
     */
    protected function update(string $table, array $data, array $where): void
    {
        $sets = array_map(fn($col) => "{$col} = :{$col}", array_keys($data));
        $wheres = array_map(fn($col) => "{$col} = :where_{$col}", array_keys($where));
        
        $sql = "UPDATE {$table} SET " . implode(', ', $sets) . " WHERE " . implode(' AND ', $wheres);
        
        // Prepara parâmetros
        $params = $data;
        foreach ($where as $key => $value) {
            $params["where_{$key}"] = $value;
        }
        
        $this->sql($sql, $params);
    }

    /**
     * Deleta dados
     */
    protected function delete(string $table, array $where): void
    {
        $wheres = array_map(fn($col) => "{$col} = :{$col}", array_keys($where));
        
        $sql = "DELETE FROM {$table} WHERE " . implode(' AND ', $wheres);
        $this->sql($sql, $where);
    }
}