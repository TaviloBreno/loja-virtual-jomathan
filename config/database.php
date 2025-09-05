<?php

/**
 * Configurações do banco de dados
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Conexão padrão do banco de dados
    |--------------------------------------------------------------------------
    |
    | Esta opção controla a conexão padrão do banco de dados que é usada por
    | todas as operações de banco de dados, a menos que uma conexão diferente
    | seja especificada explicitamente.
    |
    */
    'default' => env('DB_CONNECTION', 'mysql'),

    /*
    |--------------------------------------------------------------------------
    | Conexões do banco de dados
    |--------------------------------------------------------------------------
    |
    | Aqui estão cada uma das conexões de banco de dados configuradas para
    | sua aplicação. Você pode usar várias conexões ao mesmo tempo usando
    | a biblioteca de banco de dados.
    |
    */
    'connections' => [
        'mysql' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '3306'),
            'database' => env('DB_DATABASE', 'php_mvc_clean'),
            'username' => env('DB_USERNAME', 'root'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
            'options' => [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ],
        ],

        'sqlite' => [
            'driver' => 'sqlite',
            'database' => env('DB_DATABASE', database_path('database.sqlite')),
            'prefix' => '',
            'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'host' => env('DB_HOST', '127.0.0.1'),
            'port' => env('DB_PORT', '5432'),
            'database' => env('DB_DATABASE', 'php_mvc_clean'),
            'username' => env('DB_USERNAME', 'postgres'),
            'password' => env('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tabela de migrações
    |--------------------------------------------------------------------------
    |
    | Esta tabela mantém o controle de todas as migrações que já foram
    | executadas para sua aplicação. Usando essas informações, podemos
    | determinar quais migrações ainda não foram executadas.
    |
    */
    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Pool de conexões
    |--------------------------------------------------------------------------
    |
    | Configurações para pool de conexões (se implementado)
    |
    */
    'pool' => [
        'min_connections' => 1,
        'max_connections' => 10,
        'timeout' => 30,
    ],
];

/**
 * Função helper para obter variáveis de ambiente
 */
if (!function_exists('env')) {
    function env(string $key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }
}

/**
 * Função helper para obter caminho do banco de dados
 */
if (!function_exists('database_path')) {
    function database_path(string $path = ''): string
    {
        return __DIR__ . '/../database/' . ltrim($path, '/');
    }
}