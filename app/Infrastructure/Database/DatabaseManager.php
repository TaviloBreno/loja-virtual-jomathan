<?php

namespace App\Infrastructure\Database;

use PDO;
use PDOException;
use Exception;

/**
 * DatabaseManager - Gerencia conexões com banco de dados
 * Implementa padrão Singleton para conexão única
 */
class DatabaseManager
{
    private static ?PDO $connection = null;
    private static array $config = [];

    /**
     * Inicializa o gerenciador de banco de dados
     */
    public static function initialize(): void
    {
        self::$config = [
            'driver' => $_ENV['DB_CONNECTION'] ?? 'mysql',
            'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
            'port' => $_ENV['DB_PORT'] ?? '3306',
            'database' => $_ENV['DB_DATABASE'] ?? '',
            'username' => $_ENV['DB_USERNAME'] ?? 'root',
            'password' => $_ENV['DB_PASSWORD'] ?? '',
            'charset' => 'utf8mb4',
            'options' => [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci'
            ]
        ];
    }

    /**
     * Obtém a conexão com o banco de dados
     */
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            self::connect();
        }

        return self::$connection;
    }

    /**
     * Estabelece conexão com o banco de dados
     */
    private static function connect(): void
    {
        try {
            $dsn = self::buildDsn();
            
            self::$connection = new PDO(
                $dsn,
                self::$config['username'],
                self::$config['password'],
                self::$config['options']
            );
            
        } catch (PDOException $e) {
            // Tenta criar o banco se não existir
            if (strpos($e->getMessage(), 'Unknown database') !== false) {
                self::createDatabaseIfNotExists();
                // Tenta conectar novamente
                self::$connection = new PDO(
                    self::buildDsn(),
                    self::$config['username'],
                    self::$config['password'],
                    self::$config['options']
                );
            } else {
                throw new Exception('Erro ao conectar com o banco de dados: ' . $e->getMessage());
            }
        }
    }

    /**
     * Cria o banco de dados se não existir
     */
    private static function createDatabaseIfNotExists(): void
    {
        $driver = self::$config['driver'];
        $host = self::$config['host'];
        $port = self::$config['port'];
        $database = self::$config['database'];
        
        // Conecta sem especificar o banco
        $dsn = "{$driver}:host={$host};port={$port};charset=utf8mb4";
        
        $tempConnection = new PDO(
            $dsn,
            self::$config['username'],
            self::$config['password'],
            self::$config['options']
        );
        
        // Cria o banco de dados
        $tempConnection->exec("CREATE DATABASE IF NOT EXISTS `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    }

    /**
     * Constrói a string DSN
     */
    private static function buildDsn(): string
    {
        $driver = self::$config['driver'];
        $host = self::$config['host'];
        $port = self::$config['port'];
        $database = self::$config['database'];
        $charset = self::$config['charset'];

        return "{$driver}:host={$host};port={$port};dbname={$database};charset={$charset}";
    }

    /**
     * Executa uma query e retorna o resultado
     */
    public static function query(string $sql, array $params = []): \PDOStatement
    {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        
        return $stmt;
    }

    /**
     * Executa uma query e retorna todos os resultados
     */
    public static function fetchAll(string $sql, array $params = []): array
    {
        $stmt = self::query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * Executa uma query e retorna o primeiro resultado
     */
    public static function fetchOne(string $sql, array $params = []): ?array
    {
        $stmt = self::query($sql, $params);
        $result = $stmt->fetch();
        
        return $result ?: null;
    }

    /**
     * Executa uma query e retorna o número de linhas afetadas
     */
    public static function execute(string $sql, array $params = []): int
    {
        $stmt = self::query($sql, $params);
        return $stmt->rowCount();
    }

    /**
     * Obtém o último ID inserido
     */
    public static function lastInsertId(): string
    {
        return self::getConnection()->lastInsertId();
    }

    /**
     * Inicia uma transação
     */
    public static function beginTransaction(): bool
    {
        return self::getConnection()->beginTransaction();
    }

    /**
     * Confirma uma transação
     */
    public static function commit(): bool
    {
        return self::getConnection()->commit();
    }

    /**
     * Desfaz uma transação
     */
    public static function rollback(): bool
    {
        return self::getConnection()->rollback();
    }

    /**
     * Executa código dentro de uma transação
     */
    public static function transaction(callable $callback)
    {
        self::beginTransaction();
        
        try {
            $result = $callback();
            self::commit();
            return $result;
        } catch (Exception $e) {
            self::rollback();
            throw $e;
        }
    }

    /**
     * Verifica se uma tabela existe
     */
    public static function tableExists(string $table): bool
    {
        $sql = "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = ? AND table_name = ?";
        $result = self::fetchOne($sql, [self::$config['database'], $table]);
        
        return $result && $result['COUNT(*)'] > 0;
    }

    /**
     * Obtém informações sobre as colunas de uma tabela
     */
    public static function getTableColumns(string $table): array
    {
        $sql = "DESCRIBE {$table}";
        return self::fetchAll($sql);
    }

    /**
     * Executa múltiplas queries (útil para migrações)
     */
    public static function multiQuery(string $sql): bool
    {
        $pdo = self::getConnection();
        
        // Divide as queries por ponto e vírgula
        $queries = array_filter(array_map('trim', explode(';', $sql)));
        
        foreach ($queries as $query) {
            if (!empty($query)) {
                $pdo->exec($query);
            }
        }
        
        return true;
    }

    /**
     * Fecha a conexão
     */
    public static function disconnect(): void
    {
        self::$connection = null;
    }

    /**
     * Obtém informações sobre a conexão
     */
    public static function getConnectionInfo(): array
    {
        $pdo = self::getConnection();
        
        return [
            'driver' => $pdo->getAttribute(PDO::ATTR_DRIVER_NAME),
            'version' => $pdo->getAttribute(PDO::ATTR_SERVER_VERSION),
            'connection_status' => $pdo->getAttribute(PDO::ATTR_CONNECTION_STATUS)
        ];
    }

    /**
     * Testa a conexão com o banco de dados
     */
    public static function testConnection(): bool
    {
        try {
            $pdo = self::getConnection();
            $pdo->query('SELECT 1');
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}