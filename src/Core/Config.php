<?php

namespace Core;

/**
 * Sistema de Configuração - NeonShop
 * 
 * Classe responsável por gerenciar configurações da aplicação,
 * incluindo banco de dados, cache, sessões e outras configurações.
 */
class Config {
    
    private static ?Config $instance = null;
    private array $config = [];
    private string $configPath;
    
    /**
     * Construtor privado para Singleton
     * 
     * @param string $configPath Caminho do diretório de configurações
     */
    private function __construct(string $configPath = '') {
        $this->configPath = $configPath ?: __DIR__ . '/../../config';
        $this->loadConfigurations();
    }
    
    /**
     * Obtém instância singleton
     * 
     * @param string $configPath Caminho do diretório de configurações
     * @return Config
     */
    public static function getInstance(string $configPath = ''): Config {
        if (self::$instance === null) {
            self::$instance = new self($configPath);
        }
        
        return self::$instance;
    }
    
    /**
     * Carrega todas as configurações
     */
    private function loadConfigurations(): void {
        // Configurações padrão
        $this->config = [
            'app' => [
                'name' => 'NeonShop',
                'version' => '1.0.0',
                'environment' => 'development',
                'debug' => true,
                'timezone' => 'America/Sao_Paulo',
                'locale' => 'pt_BR',
                'url' => 'http://localhost:8000',
                'key' => $this->generateAppKey()
            ],
            
            'database' => [
                'default' => 'sqlite',
                'connections' => [
                    'sqlite' => [
                        'driver' => 'sqlite',
                        'database' => __DIR__ . '/../../storage/database/neonshop.db',
                        'prefix' => ''
                    ],
                    'mysql' => [
                        'driver' => 'mysql',
                        'host' => 'localhost',
                        'port' => 3306,
                        'database' => 'neonshop',
                        'username' => 'root',
                        'password' => '',
                        'charset' => 'utf8mb4',
                        'prefix' => ''
                    ]
                ]
            ],
            
            'session' => [
                'driver' => 'file',
                'lifetime' => 120, // minutos
                'path' => __DIR__ . '/../../storage/sessions',
                'cookie' => [
                    'name' => 'neonshop_session',
                    'path' => '/',
                    'domain' => null,
                    'secure' => false,
                    'httponly' => true,
                    'samesite' => 'Lax'
                ]
            ],
            
            'cache' => [
                'default' => 'file',
                'stores' => [
                    'file' => [
                        'driver' => 'file',
                        'path' => __DIR__ . '/../../storage/cache'
                    ],
                    'array' => [
                        'driver' => 'array'
                    ]
                ]
            ],
            
            'mail' => [
                'default' => 'smtp',
                'mailers' => [
                    'smtp' => [
                        'transport' => 'smtp',
                        'host' => 'localhost',
                        'port' => 587,
                        'encryption' => 'tls',
                        'username' => null,
                        'password' => null
                    ]
                ],
                'from' => [
                    'address' => 'noreply@neonshop.com',
                    'name' => 'NeonShop'
                ]
            ],
            
            'logging' => [
                'default' => 'single',
                'channels' => [
                    'single' => [
                        'driver' => 'single',
                        'path' => __DIR__ . '/../../storage/logs/app.log',
                        'level' => 'debug'
                    ],
                    'daily' => [
                        'driver' => 'daily',
                        'path' => __DIR__ . '/../../storage/logs/app.log',
                        'level' => 'debug',
                        'days' => 14
                    ]
                ]
            ],
            
            'security' => [
                'csrf' => [
                    'enabled' => true,
                    'token_name' => '_token',
                    'header_name' => 'X-CSRF-Token'
                ],
                'rate_limit' => [
                    'enabled' => true,
                    'max_requests' => 60,
                    'time_window' => 60 // segundos
                ],
                'cors' => [
                    'enabled' => true,
                    'allowed_origins' => ['*'],
                    'allowed_methods' => ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
                    'allowed_headers' => ['Content-Type', 'Authorization', 'X-Requested-With'],
                    'max_age' => 86400
                ]
            ],
            
            'upload' => [
                'max_size' => 10485760, // 10MB
                'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx'],
                'path' => __DIR__ . '/../../public/uploads',
                'url' => '/uploads'
            ],
            
            'payment' => [
                'default' => 'simulation',
                'gateways' => [
                    'simulation' => [
                        'driver' => 'simulation',
                        'success_rate' => 0.8 // 80% de sucesso
                    ],
                    'mercadopago' => [
                        'driver' => 'mercadopago',
                        'access_token' => null,
                        'public_key' => null,
                        'sandbox' => true
                    ]
                ]
            ],
            
            'shipping' => [
                'default' => 'simulation',
                'providers' => [
                    'simulation' => [
                        'driver' => 'simulation',
                        'base_price' => 15.00,
                        'price_per_kg' => 5.00,
                        'free_shipping_min' => 100.00
                    ],
                    'correios' => [
                        'driver' => 'correios',
                        'username' => null,
                        'password' => null,
                        'services' => ['04014', '04510'] // SEDEX e PAC
                    ]
                ]
            ]
        ];
        
        // Carrega configurações de arquivos se existirem
        $this->loadConfigFiles();
        
        // Carrega variáveis de ambiente
        $this->loadEnvironmentVariables();
    }
    
    /**
     * Carrega arquivos de configuração
     */
    private function loadConfigFiles(): void {
        if (!is_dir($this->configPath)) {
            return;
        }
        
        $configFiles = glob($this->configPath . '/*.php');
        
        foreach ($configFiles as $file) {
            $key = basename($file, '.php');
            $config = require $file;
            
            if (is_array($config)) {
                $this->config[$key] = array_merge(
                    $this->config[$key] ?? [],
                    $config
                );
            }
        }
    }
    
    /**
     * Carrega variáveis de ambiente
     */
    private function loadEnvironmentVariables(): void {
        $envFile = dirname($this->configPath) . '/.env';
        
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            
            foreach ($lines as $line) {
                if (str_starts_with(trim($line), '#')) {
                    continue;
                }
                
                if (strpos($line, '=') !== false) {
                    [$key, $value] = explode('=', $line, 2);
                    $key = trim($key);
                    $value = trim($value, '"\' ');
                    
                    $_ENV[$key] = $value;
                    putenv($key . '=' . $value);
                }
            }
        }
        
        // Aplica variáveis de ambiente nas configurações
        $this->applyEnvironmentVariables();
    }
    
    /**
     * Aplica variáveis de ambiente nas configurações
     */
    private function applyEnvironmentVariables(): void {
        // App
        $this->config['app']['environment'] = $_ENV['APP_ENV'] ?? $this->config['app']['environment'];
        $this->config['app']['debug'] = filter_var($_ENV['APP_DEBUG'] ?? $this->config['app']['debug'], FILTER_VALIDATE_BOOLEAN);
        $this->config['app']['url'] = $_ENV['APP_URL'] ?? $this->config['app']['url'];
        $this->config['app']['key'] = $_ENV['APP_KEY'] ?? $this->config['app']['key'];
        
        // Database
        if (isset($_ENV['DB_CONNECTION'])) {
            $this->config['database']['default'] = $_ENV['DB_CONNECTION'];
        }
        
        $connection = $this->config['database']['default'];
        if (isset($this->config['database']['connections'][$connection])) {
            $this->config['database']['connections'][$connection]['host'] = $_ENV['DB_HOST'] ?? $this->config['database']['connections'][$connection]['host'] ?? null;
            $this->config['database']['connections'][$connection]['port'] = $_ENV['DB_PORT'] ?? $this->config['database']['connections'][$connection]['port'] ?? null;
            $this->config['database']['connections'][$connection]['database'] = $_ENV['DB_DATABASE'] ?? $this->config['database']['connections'][$connection]['database'] ?? null;
            $this->config['database']['connections'][$connection]['username'] = $_ENV['DB_USERNAME'] ?? $this->config['database']['connections'][$connection]['username'] ?? null;
            $this->config['database']['connections'][$connection]['password'] = $_ENV['DB_PASSWORD'] ?? $this->config['database']['connections'][$connection]['password'] ?? null;
        }
        
        // Mail
        $this->config['mail']['mailers']['smtp']['host'] = $_ENV['MAIL_HOST'] ?? $this->config['mail']['mailers']['smtp']['host'];
        $this->config['mail']['mailers']['smtp']['port'] = $_ENV['MAIL_PORT'] ?? $this->config['mail']['mailers']['smtp']['port'];
        $this->config['mail']['mailers']['smtp']['username'] = $_ENV['MAIL_USERNAME'] ?? $this->config['mail']['mailers']['smtp']['username'];
        $this->config['mail']['mailers']['smtp']['password'] = $_ENV['MAIL_PASSWORD'] ?? $this->config['mail']['mailers']['smtp']['password'];
    }
    
    /**
     * Obtém uma configuração
     * 
     * @param string $key Chave da configuração (usando notação de ponto)
     * @param mixed $default Valor padrão
     * @return mixed
     */
    public function get(string $key, $default = null) {
        $keys = explode('.', $key);
        $value = $this->config;
        
        foreach ($keys as $k) {
            if (!is_array($value) || !array_key_exists($k, $value)) {
                return $default;
            }
            $value = $value[$k];
        }
        
        return $value;
    }
    
    /**
     * Define uma configuração
     * 
     * @param string $key Chave da configuração
     * @param mixed $value Valor
     * @return self
     */
    public function set(string $key, $value): self {
        $keys = explode('.', $key);
        $config = &$this->config;
        
        foreach ($keys as $k) {
            if (!isset($config[$k]) || !is_array($config[$k])) {
                $config[$k] = [];
            }
            $config = &$config[$k];
        }
        
        $config = $value;
        
        return $this;
    }
    
    /**
     * Verifica se uma configuração existe
     * 
     * @param string $key Chave da configuração
     * @return bool
     */
    public function has(string $key): bool {
        $keys = explode('.', $key);
        $value = $this->config;
        
        foreach ($keys as $k) {
            if (!is_array($value) || !array_key_exists($k, $value)) {
                return false;
            }
            $value = $value[$k];
        }
        
        return true;
    }
    
    /**
     * Obtém todas as configurações
     * 
     * @return array
     */
    public function all(): array {
        return $this->config;
    }
    
    /**
     * Obtém configurações de uma seção
     * 
     * @param string $section Seção
     * @return array
     */
    public function section(string $section): array {
        return $this->config[$section] ?? [];
    }
    
    /**
     * Verifica se está em ambiente de desenvolvimento
     * 
     * @return bool
     */
    public function isDevelopment(): bool {
        return $this->get('app.environment') === 'development';
    }
    
    /**
     * Verifica se está em ambiente de produção
     * 
     * @return bool
     */
    public function isProduction(): bool {
        return $this->get('app.environment') === 'production';
    }
    
    /**
     * Verifica se debug está habilitado
     * 
     * @return bool
     */
    public function isDebug(): bool {
        return (bool) $this->get('app.debug', false);
    }
    
    /**
     * Gera chave da aplicação
     * 
     * @return string
     */
    private function generateAppKey(): string {
        return 'base64:' . base64_encode(random_bytes(32));
    }
    
    /**
     * Salva configurações em arquivo
     * 
     * @param string $section Seção
     * @param array $config Configurações
     * @return bool
     */
    public function save(string $section, array $config): bool {
        if (!is_dir($this->configPath)) {
            mkdir($this->configPath, 0755, true);
        }
        
        $filePath = $this->configPath . '/' . $section . '.php';
        
        $content = "<?php\n\nreturn " . var_export($config, true) . ";\n";
        
        return file_put_contents($filePath, $content) !== false;
    }
    
    /**
     * Cria arquivo .env de exemplo
     * 
     * @return bool
     */
    public function createEnvExample(): bool {
        $envPath = dirname($this->configPath) . '/.env.example';
        
        $content = <<<ENV
# Configurações da Aplicação
APP_NAME=NeonShop
APP_ENV=development
APP_DEBUG=true
APP_URL=http://localhost:8000
APP_KEY=

# Banco de Dados
DB_CONNECTION=sqlite
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=neonshop
DB_USERNAME=root
DB_PASSWORD=

# Email
MAIL_MAILER=smtp
MAIL_HOST=localhost
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@neonshop.com
MAIL_FROM_NAME=NeonShop

# Pagamento
MERCADOPAGO_ACCESS_TOKEN=
MERCADOPAGO_PUBLIC_KEY=
MERCADOPAGO_SANDBOX=true

# Correios
CORREIOS_USERNAME=
CORREIOS_PASSWORD=
ENV;
        
        return file_put_contents($envPath, $content) !== false;
    }
    
    /**
     * Obtém configuração de conexão do banco
     * 
     * @param string|null $connection Nome da conexão
     * @return array
     */
    public function getDatabaseConfig(?string $connection = null): array {
        $connection = $connection ?? $this->get('database.default');
        return $this->get('database.connections.' . $connection, []);
    }
    
    /**
     * Obtém URL da aplicação
     * 
     * @param string $path Caminho adicional
     * @return string
     */
    public function url(string $path = ''): string {
        $baseUrl = rtrim($this->get('app.url'), '/');
        $path = ltrim($path, '/');
        
        return $baseUrl . ($path ? '/' . $path : '');
    }
    
    /**
     * Obtém caminho de upload
     * 
     * @param string $file Nome do arquivo
     * @return string
     */
    public function uploadPath(string $file = ''): string {
        $basePath = rtrim($this->get('upload.path'), '/');
        $file = ltrim($file, '/');
        
        return $basePath . ($file ? '/' . $file : '');
    }
    
    /**
     * Obtém URL de upload
     * 
     * @param string $file Nome do arquivo
     * @return string
     */
    public function uploadUrl(string $file = ''): string {
        $baseUrl = rtrim($this->get('upload.url'), '/');
        $file = ltrim($file, '/');
        
        return $baseUrl . ($file ? '/' . $file : '');
    }
}