<?php

namespace Core;

/**
 * Aplicação Principal - NeonShop
 * 
 * Classe responsável por inicializar e gerenciar toda a aplicação,
 * incluindo roteamento, middlewares, dependências e ciclo de vida.
 */
class Application {
    
    private static ?Application $instance = null;
    private Config $config;
    private Router $router;
    private Request $request;
    private Response $response;
    private Database $database;
    private View $view;
    private MiddlewarePipeline $middlewarePipeline;
    private array $services = [];
    private array $singletons = [];
    private bool $booted = false;
    
    /**
     * Construtor
     * 
     * @param string $basePath Caminho base da aplicação
     */
    public function __construct(private string $basePath = '') {
        $this->basePath = $basePath ?: dirname(__DIR__, 2);
        $this->initialize();
    }
    
    /**
     * Obtém instância singleton
     * 
     * @param string $basePath Caminho base
     * @return Application
     */
    public static function getInstance(string $basePath = ''): Application {
        if (self::$instance === null) {
            self::$instance = new self($basePath);
        }
        
        return self::$instance;
    }
    
    /**
     * Inicializa a aplicação
     */
    private function initialize(): void {
        // Configurar timezone
        date_default_timezone_set('America/Sao_Paulo');
        
        // Configurar encoding
        mb_internal_encoding('UTF-8');
        
        // Inicializar componentes principais
        $this->config = Config::getInstance($this->basePath . '/config');
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->basePath);
        $this->middlewarePipeline = new MiddlewarePipeline();
        
        // Configurar timezone da aplicação
        if ($timezone = $this->config->get('app.timezone')) {
            date_default_timezone_set($timezone);
        }
        
        // Configurar tratamento de erros
        $this->setupErrorHandling();
        
        // Configurar sessões
        $this->setupSession();
        
        // Registrar serviços principais
        $this->registerCoreServices();
        
        // Registrar middlewares padrão
        $this->registerDefaultMiddlewares();
    }
    
    /**
     * Configura tratamento de erros
     */
    private function setupErrorHandling(): void {
        if ($this->config->isDebug()) {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        } else {
            error_reporting(0);
            ini_set('display_errors', '0');
        }
        
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
        register_shutdown_function([$this, 'handleShutdown']);
    }
    
    /**
     * Configura sessões
     */
    private function setupSession(): void {
        $sessionConfig = $this->config->section('session');
        
        // Configurar diretório de sessões
        if (isset($sessionConfig['path']) && !is_dir($sessionConfig['path'])) {
            mkdir($sessionConfig['path'], 0755, true);
        }
        
        // Configurar parâmetros da sessão
        if (isset($sessionConfig['cookie'])) {
            $cookie = $sessionConfig['cookie'];
            
            session_set_cookie_params([
                'lifetime' => ($sessionConfig['lifetime'] ?? 120) * 60,
                'path' => $cookie['path'] ?? '/',
                'domain' => $cookie['domain'] ?? '',
                'secure' => $cookie['secure'] ?? false,
                'httponly' => $cookie['httponly'] ?? true,
                'samesite' => $cookie['samesite'] ?? 'Lax'
            ]);
            
            session_name($cookie['name'] ?? 'neonshop_session');
        }
        
        if (isset($sessionConfig['path'])) {
            session_save_path($sessionConfig['path']);
        }
    }
    
    /**
     * Registra serviços principais
     */
    private function registerCoreServices(): void {
        // Database
        $this->singleton('database', function () {
            $dbConfig = $this->config->getDatabaseConfig();
            return Database::getInstance($dbConfig);
        });
        
        // View
        $this->singleton('view', function () {
            return new View($this->basePath . '/resources/views');
        });
        
        // Config
        $this->singleton('config', function () {
            return $this->config;
        });
        
        // Request
        $this->singleton('request', function () {
            return $this->request;
        });
        
        // Response
        $this->singleton('response', function () {
            return $this->response;
        });
        
        // Router
        $this->singleton('router', function () {
            return $this->router;
        });
    }
    
    /**
     * Registra middlewares padrão
     */
    private function registerDefaultMiddlewares(): void {
        $securityConfig = $this->config->section('security');
        
        // CORS
        if ($securityConfig['cors']['enabled'] ?? false) {
            $this->middlewarePipeline->add(new CorsMiddleware($securityConfig['cors']));
        }
        
        // Rate Limiting
        if ($securityConfig['rate_limit']['enabled'] ?? false) {
            $this->middlewarePipeline->add(new RateLimitMiddleware(
                $securityConfig['rate_limit']['max_requests'] ?? 60,
                $securityConfig['rate_limit']['time_window'] ?? 60
            ));
        }
        
        // Log de requisições
        if ($this->config->isDebug()) {
            $this->middlewarePipeline->add(new LogMiddleware());
        }
    }
    
    /**
     * Registra um serviço
     * 
     * @param string $name Nome do serviço
     * @param callable $factory Factory do serviço
     * @return self
     */
    public function bind(string $name, callable $factory): self {
        $this->services[$name] = $factory;
        return $this;
    }
    
    /**
     * Registra um singleton
     * 
     * @param string $name Nome do serviço
     * @param callable $factory Factory do serviço
     * @return self
     */
    public function singleton(string $name, callable $factory): self {
        $this->singletons[$name] = $factory;
        return $this;
    }
    
    /**
     * Resolve um serviço
     * 
     * @param string $name Nome do serviço
     * @return mixed
     * @throws \Exception
     */
    public function make(string $name) {
        // Verifica se é um singleton já instanciado
        if (isset($this->singletons[$name])) {
            if (is_callable($this->singletons[$name])) {
                $this->singletons[$name] = $this->singletons[$name]();
            }
            return $this->singletons[$name];
        }
        
        // Verifica se é um serviço registrado
        if (isset($this->services[$name])) {
            return $this->services[$name]();
        }
        
        throw new \Exception('Serviço não encontrado: ' . $name);
    }
    
    /**
     * Verifica se um serviço existe
     * 
     * @param string $name Nome do serviço
     * @return bool
     */
    public function has(string $name): bool {
        return isset($this->services[$name]) || isset($this->singletons[$name]);
    }
    
    /**
     * Obtém o roteador
     * 
     * @return Router
     */
    public function getRouter(): Router {
        return $this->router;
    }
    
    /**
     * Obtém a configuração
     * 
     * @return Config
     */
    public function getConfig(): Config {
        return $this->config;
    }
    
    /**
     * Obtém o banco de dados
     * 
     * @return Database
     */
    public function getDatabase(): Database {
        if (!isset($this->database)) {
            $this->database = $this->make('database');
        }
        return $this->database;
    }
    
    /**
     * Obtém o sistema de views
     * 
     * @return View
     */
    public function getView(): View {
        if (!isset($this->view)) {
            $this->view = $this->make('view');
        }
        return $this->view;
    }
    
    /**
     * Adiciona middleware
     * 
     * @param Middleware|callable $middleware Middleware
     * @return self
     */
    public function addMiddleware($middleware): self {
        $this->middlewarePipeline->add($middleware);
        return $this;
    }
    
    /**
     * Carrega rotas de um arquivo
     * 
     * @param string $file Caminho do arquivo
     * @return self
     */
    public function loadRoutes(string $file): self {
        if (file_exists($file)) {
            require $file;
        }
        return $this;
    }
    
    /**
     * Executa a aplicação
     * 
     * @return void
     */
    public function run(): void {
        try {
            // Boot da aplicação
            if (!$this->booted) {
                $this->boot();
            }
            
            // Executa pipeline de middlewares
            $this->middlewarePipeline->execute(
                $this->request,
                $this->response,
                [$this, 'handleRequest']
            );
            
        } catch (\Exception $e) {
            $this->handleException($e);
        }
    }
    
    /**
     * Boot da aplicação
     */
    private function boot(): void {
        // Carrega rotas padrão
        $this->loadRoutes($this->basePath . '/routes/web.php');
        $this->loadRoutes($this->basePath . '/routes/api.php');
        
        // Inicializa banco de dados se necessário
        $this->initializeDatabase();
        
        $this->booted = true;
    }
    
    /**
     * Inicializa banco de dados
     */
    private function initializeDatabase(): void {
        try {
            $db = $this->getDatabase();
            
            // Verifica se as tabelas existem, se não, cria
            $this->createDatabaseTables($db);
            
        } catch (\Exception $e) {
            if ($this->config->isDebug()) {
                throw $e;
            }
            // Em produção, apenas loga o erro
            error_log('Erro na inicialização do banco: ' . $e->getMessage());
        }
    }
    
    /**
     * Cria tabelas do banco de dados
     * 
     * @param Database $db Instância do banco
     */
    private function createDatabaseTables(Database $db): void {
        $schemaFile = $this->basePath . '/database/schema.sql';
        
        if (file_exists($schemaFile)) {
            $db->executeSqlFile($schemaFile);
        }
    }
    
    /**
     * Manipula a requisição
     * 
     * @param Request $request Requisição
     * @param Response $response Resposta
     * @return mixed
     */
    public function handleRequest(Request $request, Response $response) {
        try {
            // Resolve a rota
            $route = $this->router->resolve($request->getMethod(), $request->getPath());
            
            if (!$route) {
                return $response->notFound('Página não encontrada');
            }
            
            // Executa middlewares da rota
            if (!empty($route['middlewares'])) {
                $routePipeline = new MiddlewarePipeline();
                
                foreach ($route['middlewares'] as $middleware) {
                    $routePipeline->add($this->resolveMiddleware($middleware));
                }
                
                return $routePipeline->execute($request, $response, function() use ($route, $request, $response) {
                    return $this->executeRoute($route, $request, $response);
                });
            }
            
            return $this->executeRoute($route, $request, $response);
            
        } catch (\Exception $e) {
            return $this->handleRouteException($e, $response);
        }
    }
    
    /**
     * Executa uma rota
     * 
     * @param array $route Dados da rota
     * @param Request $request Requisição
     * @param Response $response Resposta
     * @return mixed
     */
    private function executeRoute(array $route, Request $request, Response $response) {
        $handler = $route['handler'];
        $params = $route['params'] ?? [];
        
        // Se for uma closure
        if (is_callable($handler)) {
            return $handler($request, $response, $params);
        }
        
        // Se for controller@method
        if (is_string($handler) && strpos($handler, '@') !== false) {
            [$controllerClass, $method] = explode('@', $handler);
            
            // Adiciona namespace se necessário
            if (!str_contains($controllerClass, '\\')) {
                $controllerClass = 'Controllers\\' . $controllerClass;
            }
            
            if (!class_exists($controllerClass)) {
                throw new \Exception('Controller não encontrado: ' . $controllerClass);
            }
            
            $controller = new $controllerClass($this);
            
            if (!method_exists($controller, $method)) {
                throw new \Exception('Método não encontrado: ' . $controllerClass . '::' . $method);
            }
            
            return $controller->$method($request, $response, $params);
        }
        
        throw new \Exception('Handler de rota inválido');
    }
    
    /**
     * Resolve um middleware
     * 
     * @param string|callable $middleware Middleware
     * @return callable
     */
    private function resolveMiddleware($middleware): callable {
        if (is_callable($middleware)) {
            return $middleware;
        }
        
        if (is_string($middleware)) {
            // Middlewares pré-definidos
            switch ($middleware) {
                case 'auth':
                    return new AuthMiddleware();
                case 'csrf':
                    return new CsrfMiddleware();
                case 'cors':
                    return new CorsMiddleware();
                default:
                    // Tenta instanciar a classe
                    if (class_exists($middleware)) {
                        return new $middleware();
                    }
            }
        }
        
        throw new \Exception('Middleware não encontrado: ' . $middleware);
    }
    
    /**
     * Manipula exceção de rota
     * 
     * @param \Exception $e Exceção
     * @param Response $response Resposta
     * @return mixed
     */
    private function handleRouteException(\Exception $e, Response $response) {
        if ($this->config->isDebug()) {
            return $response->status(500)->json([
                'error' => true,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
        }
        
        return $response->status(500)->json([
            'error' => true,
            'message' => 'Erro interno do servidor'
        ]);
    }
    
    /**
     * Manipula erros PHP
     * 
     * @param int $severity Severidade
     * @param string $message Mensagem
     * @param string $file Arquivo
     * @param int $line Linha
     * @return bool
     */
    public function handleError(int $severity, string $message, string $file, int $line): bool {
        if (!(error_reporting() & $severity)) {
            return false;
        }
        
        $this->logError('PHP Error', $message, $file, $line);
        
        if ($this->config->isDebug()) {
            echo "<b>Error:</b> $message in <b>$file</b> on line <b>$line</b><br>";
        }
        
        return true;
    }
    
    /**
     * Manipula exceções não capturadas
     * 
     * @param \Throwable $exception Exceção
     */
    public function handleException(\Throwable $exception): void {
        $this->logError('Uncaught Exception', $exception->getMessage(), $exception->getFile(), $exception->getLine());
        
        if ($this->config->isDebug()) {
            echo "<h1>Uncaught Exception</h1>";
            echo "<p><b>Message:</b> " . $exception->getMessage() . "</p>";
            echo "<p><b>File:</b> " . $exception->getFile() . "</p>";
            echo "<p><b>Line:</b> " . $exception->getLine() . "</p>";
            echo "<pre>" . $exception->getTraceAsString() . "</pre>";
        } else {
            echo "<h1>Erro interno do servidor</h1>";
            echo "<p>Ocorreu um erro inesperado. Tente novamente mais tarde.</p>";
        }
    }
    
    /**
     * Manipula shutdown da aplicação
     */
    public function handleShutdown(): void {
        $error = error_get_last();
        
        if ($error && in_array($error['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_PARSE])) {
            $this->logError('Fatal Error', $error['message'], $error['file'], $error['line']);
            
            if ($this->config->isDebug()) {
                echo "<h1>Fatal Error</h1>";
                echo "<p><b>Message:</b> " . $error['message'] . "</p>";
                echo "<p><b>File:</b> " . $error['file'] . "</p>";
                echo "<p><b>Line:</b> " . $error['line'] . "</p>";
            }
        }
    }
    
    /**
     * Registra erro no log
     * 
     * @param string $type Tipo do erro
     * @param string $message Mensagem
     * @param string $file Arquivo
     * @param int $line Linha
     */
    private function logError(string $type, string $message, string $file, int $line): void {
        $logFile = $this->basePath . '/storage/logs/error.log';
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $logEntry = sprintf(
            "[%s] %s: %s in %s on line %d\n",
            date('Y-m-d H:i:s'),
            $type,
            $message,
            $file,
            $line
        );
        
        file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Obtém caminho base
     * 
     * @return string
     */
    public function getBasePath(): string {
        return $this->basePath;
    }
    
    /**
     * Obtém caminho para um diretório
     * 
     * @param string $path Caminho relativo
     * @return string
     */
    public function path(string $path = ''): string {
        return $this->basePath . ($path ? '/' . ltrim($path, '/') : '');
    }
}