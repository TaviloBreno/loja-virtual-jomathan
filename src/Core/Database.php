<?php

namespace Core;

/**
 * Sistema de Banco de Dados - NeonShop
 * 
 * Classe responsável por gerenciar conexões com banco de dados,
 * executar queries e fornecer uma interface simples para operações CRUD.
 */
class Database {
    
    private static ?Database $instance = null;
    private ?\PDO $connection = null;
    private array $config;
    private array $queryLog = [];
    private bool $logQueries = false;
    
    /**
     * Construtor privado para Singleton
     * 
     * @param array $config Configurações do banco
     */
    private function __construct(array $config = []) {
        $this->config = array_merge([
            'driver' => 'sqlite',
            'host' => 'localhost',
            'port' => 3306,
            'database' => __DIR__ . '/../../storage/database/neonshop.db',
            'username' => '',
            'password' => '',
            'charset' => 'utf8mb4',
            'options' => [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::ATTR_EMULATE_PREPARES => false
            ]
        ], $config);
    }
    
    /**
     * Obtém instância singleton
     * 
     * @param array $config Configurações do banco
     * @return Database
     */
    public static function getInstance(array $config = []): Database {
        if (self::$instance === null) {
            self::$instance = new self($config);
        }
        
        return self::$instance;
    }
    
    /**
     * Obtém conexão PDO
     * 
     * @return \PDO
     * @throws \Exception
     */
    public function getConnection(): \PDO {
        if ($this->connection === null) {
            $this->connect();
        }
        
        return $this->connection;
    }
    
    /**
     * Conecta ao banco de dados
     * 
     * @throws \Exception
     */
    private function connect(): void {
        try {
            $dsn = $this->buildDsn();
            
            $this->connection = new \PDO(
                $dsn,
                $this->config['username'],
                $this->config['password'],
                $this->config['options']
            );
            
            // Configurações específicas do SQLite
            if ($this->config['driver'] === 'sqlite') {
                $this->connection->exec('PRAGMA foreign_keys = ON');
                $this->connection->exec('PRAGMA journal_mode = WAL');
            }
            
        } catch (\PDOException $e) {
            throw new \Exception('Erro na conexão com o banco de dados: ' . $e->getMessage());
        }
    }
    
    /**
     * Constrói DSN da conexão
     * 
     * @return string
     */
    private function buildDsn(): string {
        switch ($this->config['driver']) {
            case 'sqlite':
                // Cria diretório se não existir
                $dbDir = dirname($this->config['database']);
                if (!is_dir($dbDir)) {
                    mkdir($dbDir, 0755, true);
                }
                return 'sqlite:' . $this->config['database'];
                
            case 'mysql':
                return sprintf(
                    'mysql:host=%s;port=%d;dbname=%s;charset=%s',
                    $this->config['host'],
                    $this->config['port'],
                    $this->config['database'],
                    $this->config['charset']
                );
                
            case 'pgsql':
                return sprintf(
                    'pgsql:host=%s;port=%d;dbname=%s',
                    $this->config['host'],
                    $this->config['port'],
                    $this->config['database']
                );
                
            default:
                throw new \Exception('Driver de banco não suportado: ' . $this->config['driver']);
        }
    }
    
    /**
     * Executa uma query
     * 
     * @param string $sql SQL da query
     * @param array $params Parâmetros
     * @return \PDOStatement
     * @throws \Exception
     */
    public function query(string $sql, array $params = []): \PDOStatement {
        try {
            $startTime = microtime(true);
            
            $stmt = $this->getConnection()->prepare($sql);
            $stmt->execute($params);
            
            $endTime = microtime(true);
            
            // Log da query se habilitado
            if ($this->logQueries) {
                $this->queryLog[] = [
                    'sql' => $sql,
                    'params' => $params,
                    'time' => round(($endTime - $startTime) * 1000, 2),
                    'timestamp' => date('Y-m-d H:i:s')
                ];
            }
            
            return $stmt;
            
        } catch (\PDOException $e) {
            throw new \Exception('Erro na execução da query: ' . $e->getMessage() . ' | SQL: ' . $sql);
        }
    }
    
    /**
     * Busca todos os registros
     * 
     * @param string $sql SQL da query
     * @param array $params Parâmetros
     * @return array
     */
    public function fetchAll(string $sql, array $params = []): array {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }
    
    /**
     * Busca um registro
     * 
     * @param string $sql SQL da query
     * @param array $params Parâmetros
     * @return array|null
     */
    public function fetch(string $sql, array $params = []): ?array {
        $stmt = $this->query($sql, $params);
        $result = $stmt->fetch();
        return $result ?: null;
    }
    
    /**
     * Busca um valor específico
     * 
     * @param string $sql SQL da query
     * @param array $params Parâmetros
     * @return mixed
     */
    public function fetchColumn(string $sql, array $params = []) {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchColumn();
    }
    
    /**
     * Insere um registro
     * 
     * @param string $table Nome da tabela
     * @param array $data Dados para inserir
     * @return int ID do registro inserido
     */
    public function insert(string $table, array $data): int {
        $columns = array_keys($data);
        $placeholders = array_map(fn($col) => ':' . $col, $columns);
        
        $sql = sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $table,
            implode(', ', $columns),
            implode(', ', $placeholders)
        );
        
        $this->query($sql, $data);
        
        return (int) $this->getConnection()->lastInsertId();
    }
    
    /**
     * Atualiza registros
     * 
     * @param string $table Nome da tabela
     * @param array $data Dados para atualizar
     * @param array $where Condições WHERE
     * @return int Número de registros afetados
     */
    public function update(string $table, array $data, array $where): int {
        $setParts = array_map(fn($col) => $col . ' = :' . $col, array_keys($data));
        $whereParts = array_map(fn($col) => $col . ' = :where_' . $col, array_keys($where));
        
        $sql = sprintf(
            'UPDATE %s SET %s WHERE %s',
            $table,
            implode(', ', $setParts),
            implode(' AND ', $whereParts)
        );
        
        // Combina parâmetros de dados e where
        $params = array_merge(
            $data,
            array_combine(
                array_map(fn($key) => 'where_' . $key, array_keys($where)),
                array_values($where)
            )
        );
        
        $stmt = $this->query($sql, $params);
        
        return $stmt->rowCount();
    }
    
    /**
     * Deleta registros
     * 
     * @param string $table Nome da tabela
     * @param array $where Condições WHERE
     * @return int Número de registros deletados
     */
    public function delete(string $table, array $where): int {
        $whereParts = array_map(fn($col) => $col . ' = :' . $col, array_keys($where));
        
        $sql = sprintf(
            'DELETE FROM %s WHERE %s',
            $table,
            implode(' AND ', $whereParts)
        );
        
        $stmt = $this->query($sql, $where);
        
        return $stmt->rowCount();
    }
    
    /**
     * Inicia uma transação
     * 
     * @return bool
     */
    public function beginTransaction(): bool {
        return $this->getConnection()->beginTransaction();
    }
    
    /**
     * Confirma uma transação
     * 
     * @return bool
     */
    public function commit(): bool {
        return $this->getConnection()->commit();
    }
    
    /**
     * Desfaz uma transação
     * 
     * @return bool
     */
    public function rollback(): bool {
        return $this->getConnection()->rollback();
    }
    
    /**
     * Executa uma função dentro de uma transação
     * 
     * @param callable $callback Função a executar
     * @return mixed Resultado da função
     * @throws \Exception
     */
    public function transaction(callable $callback) {
        $this->beginTransaction();
        
        try {
            $result = $callback($this);
            $this->commit();
            return $result;
        } catch (\Exception $e) {
            $this->rollback();
            throw $e;
        }
    }
    
    /**
     * Verifica se uma tabela existe
     * 
     * @param string $table Nome da tabela
     * @return bool
     */
    public function tableExists(string $table): bool {
        if ($this->config['driver'] === 'sqlite') {
            $sql = "SELECT name FROM sqlite_master WHERE type='table' AND name = ?";
            return (bool) $this->fetchColumn($sql, [$table]);
        }
        
        // Para MySQL/PostgreSQL
        $sql = "SELECT COUNT(*) FROM information_schema.tables WHERE table_name = ?";
        return (bool) $this->fetchColumn($sql, [$table]);
    }
    
    /**
     * Executa um arquivo SQL
     * 
     * @param string $filePath Caminho do arquivo
     * @return bool
     * @throws \Exception
     */
    public function executeSqlFile(string $filePath): bool {
        if (!file_exists($filePath)) {
            throw new \Exception('Arquivo SQL não encontrado: ' . $filePath);
        }
        
        $sql = file_get_contents($filePath);
        
        // Remove comentários e divide em statements
        $statements = array_filter(
            array_map('trim', explode(';', $sql)),
            fn($stmt) => !empty($stmt) && !str_starts_with($stmt, '--')
        );
        
        $this->beginTransaction();
        
        try {
            foreach ($statements as $statement) {
                if (!empty($statement)) {
                    $this->query($statement);
                }
            }
            
            $this->commit();
            return true;
            
        } catch (\Exception $e) {
            $this->rollback();
            throw new \Exception('Erro ao executar arquivo SQL: ' . $e->getMessage());
        }
    }
    
    /**
     * Habilita/desabilita log de queries
     * 
     * @param bool $enable
     * @return self
     */
    public function enableQueryLog(bool $enable = true): self {
        $this->logQueries = $enable;
        return $this;
    }
    
    /**
     * Obtém log de queries
     * 
     * @return array
     */
    public function getQueryLog(): array {
        return $this->queryLog;
    }
    
    /**
     * Limpa log de queries
     * 
     * @return self
     */
    public function clearQueryLog(): self {
        $this->queryLog = [];
        return $this;
    }
    
    /**
     * Obtém estatísticas do banco
     * 
     * @return array
     */
    public function getStats(): array {
        return [
            'driver' => $this->config['driver'],
            'queries_executed' => count($this->queryLog),
            'total_time' => array_sum(array_column($this->queryLog, 'time')),
            'average_time' => count($this->queryLog) > 0 
                ? round(array_sum(array_column($this->queryLog, 'time')) / count($this->queryLog), 2)
                : 0
        ];
    }
    
    /**
     * Fecha a conexão
     */
    public function close(): void {
        $this->connection = null;
    }
    
    /**
     * Destrutor
     */
    public function __destruct() {
        $this->close();
    }
}

/**
 * Query Builder simples
 */
class QueryBuilder {
    
    private Database $db;
    private string $table = '';
    private array $select = ['*'];
    private array $where = [];
    private array $joins = [];
    private array $orderBy = [];
    private array $groupBy = [];
    private ?int $limit = null;
    private ?int $offset = null;
    private array $params = [];
    
    /**
     * Construtor
     * 
     * @param Database $db Instância do banco
     */
    public function __construct(Database $db) {
        $this->db = $db;
    }
    
    /**
     * Define a tabela
     * 
     * @param string $table Nome da tabela
     * @return self
     */
    public function table(string $table): self {
        $this->table = $table;
        return $this;
    }
    
    /**
     * Define campos SELECT
     * 
     * @param array|string $columns Colunas
     * @return self
     */
    public function select($columns = ['*']): self {
        $this->select = is_array($columns) ? $columns : [$columns];
        return $this;
    }
    
    /**
     * Adiciona condição WHERE
     * 
     * @param string $column Coluna
     * @param mixed $operator Operador ou valor
     * @param mixed $value Valor (opcional)
     * @return self
     */
    public function where(string $column, $operator, $value = null): self {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        
        $paramKey = 'param_' . count($this->params);
        $this->where[] = $column . ' ' . $operator . ' :' . $paramKey;
        $this->params[$paramKey] = $value;
        
        return $this;
    }
    
    /**
     * Adiciona JOIN
     * 
     * @param string $table Tabela
     * @param string $first Primeira coluna
     * @param string $operator Operador
     * @param string $second Segunda coluna
     * @param string $type Tipo de JOIN
     * @return self
     */
    public function join(string $table, string $first, string $operator, string $second, string $type = 'INNER'): self {
        $this->joins[] = $type . ' JOIN ' . $table . ' ON ' . $first . ' ' . $operator . ' ' . $second;
        return $this;
    }
    
    /**
     * Adiciona ORDER BY
     * 
     * @param string $column Coluna
     * @param string $direction Direção
     * @return self
     */
    public function orderBy(string $column, string $direction = 'ASC'): self {
        $this->orderBy[] = $column . ' ' . strtoupper($direction);
        return $this;
    }
    
    /**
     * Define LIMIT
     * 
     * @param int $limit Limite
     * @param int|null $offset Offset
     * @return self
     */
    public function limit(int $limit, ?int $offset = null): self {
        $this->limit = $limit;
        if ($offset !== null) {
            $this->offset = $offset;
        }
        return $this;
    }
    
    /**
     * Constrói a query SQL
     * 
     * @return string
     */
    public function toSql(): string {
        $sql = 'SELECT ' . implode(', ', $this->select) . ' FROM ' . $this->table;
        
        if (!empty($this->joins)) {
            $sql .= ' ' . implode(' ', $this->joins);
        }
        
        if (!empty($this->where)) {
            $sql .= ' WHERE ' . implode(' AND ', $this->where);
        }
        
        if (!empty($this->groupBy)) {
            $sql .= ' GROUP BY ' . implode(', ', $this->groupBy);
        }
        
        if (!empty($this->orderBy)) {
            $sql .= ' ORDER BY ' . implode(', ', $this->orderBy);
        }
        
        if ($this->limit !== null) {
            $sql .= ' LIMIT ' . $this->limit;
            
            if ($this->offset !== null) {
                $sql .= ' OFFSET ' . $this->offset;
            }
        }
        
        return $sql;
    }
    
    /**
     * Executa a query e retorna todos os resultados
     * 
     * @return array
     */
    public function get(): array {
        return $this->db->fetchAll($this->toSql(), $this->params);
    }
    
    /**
     * Executa a query e retorna o primeiro resultado
     * 
     * @return array|null
     */
    public function first(): ?array {
        $this->limit(1);
        return $this->db->fetch($this->toSql(), $this->params);
    }
    
    /**
     * Conta registros
     * 
     * @return int
     */
    public function count(): int {
        $originalSelect = $this->select;
        $this->select = ['COUNT(*) as count'];
        
        $result = $this->db->fetch($this->toSql(), $this->params);
        
        $this->select = $originalSelect;
        
        return (int) ($result['count'] ?? 0);
    }
}