<?php

namespace Core;

/**
 * Sistema de Roteamento HTTP - NeonShop
 * 
 * Classe responsável por gerenciar todas as rotas da aplicação,
 * incluindo middlewares, parâmetros dinâmicos e tratamento de erros.
 */
class Router {
    private array $routes = [];
    private array $middlewares = [];
    private array $namedRoutes = [];
    private string $basePath = '';
    
    /**
     * Construtor do Router
     * 
     * @param string $basePath Caminho base da aplicação
     */
    public function __construct(string $basePath = '') {
        $this->basePath = rtrim($basePath, '/');
    }
    
    /**
     * Registra uma rota GET
     * 
     * @param string $path Caminho da rota
     * @param mixed $handler Controlador ou função
     * @param array $middlewares Middlewares da rota
     * @param string|null $name Nome da rota
     * @return self
     */
    public function get(string $path, $handler, array $middlewares = [], ?string $name = null): self {
        return $this->addRoute('GET', $path, $handler, $middlewares, $name);
    }
    
    /**
     * Registra uma rota POST
     * 
     * @param string $path Caminho da rota
     * @param mixed $handler Controlador ou função
     * @param array $middlewares Middlewares da rota
     * @param string|null $name Nome da rota
     * @return self
     */
    public function post(string $path, $handler, array $middlewares = [], ?string $name = null): self {
        return $this->addRoute('POST', $path, $handler, $middlewares, $name);
    }
    
    /**
     * Registra uma rota PUT
     * 
     * @param string $path Caminho da rota
     * @param mixed $handler Controlador ou função
     * @param array $middlewares Middlewares da rota
     * @param string|null $name Nome da rota
     * @return self
     */
    public function put(string $path, $handler, array $middlewares = [], ?string $name = null): self {
        return $this->addRoute('PUT', $path, $handler, $middlewares, $name);
    }
    
    /**
     * Registra uma rota DELETE
     * 
     * @param string $path Caminho da rota
     * @param mixed $handler Controlador ou função
     * @param array $middlewares Middlewares da rota
     * @param string|null $name Nome da rota
     * @return self
     */
    public function delete(string $path, $handler, array $middlewares = [], ?string $name = null): self {
        return $this->addRoute('DELETE', $path, $handler, $middlewares, $name);
    }
    
    /**
     * Registra uma rota PATCH
     * 
     * @param string $path Caminho da rota
     * @param mixed $handler Controlador ou função
     * @param array $middlewares Middlewares da rota
     * @param string|null $name Nome da rota
     * @return self
     */
    public function patch(string $path, $handler, array $middlewares = [], ?string $name = null): self {
        return $this->addRoute('PATCH', $path, $handler, $middlewares, $name);
    }
    
    /**
     * Registra uma rota OPTIONS
     * 
     * @param string $path Caminho da rota
     * @param mixed $handler Controlador ou função
     * @param array $middlewares Middlewares da rota
     * @param string|null $name Nome da rota
     * @return self
     */
    public function options(string $path, $handler, array $middlewares = [], ?string $name = null): self {
        return $this->addRoute('OPTIONS', $path, $handler, $middlewares, $name);
    }
    
    /**
     * Adiciona uma rota ao sistema
     * 
     * @param string $method Método HTTP
     * @param string $path Caminho da rota
     * @param mixed $handler Controlador ou função
     * @param array $middlewares Middlewares da rota
     * @param string|null $name Nome da rota
     * @return self
     */
    private function addRoute(string $method, string $path, $handler, array $middlewares = [], ?string $name = null): self {
        $path = $this->normalizePath($path);
        $pattern = $this->convertToRegex($path);
        
        $route = [
            'method' => strtoupper($method),
            'path' => $path,
            'pattern' => $pattern,
            'handler' => $handler,
            'middlewares' => $middlewares,
            'name' => $name
        ];
        
        $this->routes[] = $route;
        
        // Registrar rota nomeada
        if ($name) {
            $this->namedRoutes[$name] = $route;
        }
        
        return $this;
    }
    
    /**
     * Adiciona middleware global
     * 
     * @param string $pattern Padrão de rota
     * @param callable $middleware Middleware
     * @return self
     */
    public function addMiddleware(string $pattern, callable $middleware): self {
        $this->middlewares[] = [
            'pattern' => $this->convertToRegex($pattern),
            'middleware' => $middleware
        ];
        
        return $this;
    }
    
    /**
     * Executa o roteamento
     * 
     * @param string $method Método HTTP
     * @param string $uri URI da requisição
     * @return mixed
     * @throws \Exception
     */
    public function dispatch(string $method, string $uri) {
        $method = strtoupper($method);
        $uri = $this->normalizePath($uri);
        
        // Buscar rota correspondente
        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }
            
            $matches = [];
            if (preg_match($route['pattern'], $uri, $matches)) {
                // Extrair parâmetros da rota
                $params = $this->extractParams($route['path'], $uri, $matches);
                
                // Criar objetos de requisição e resposta
                $request = new Request($method, $uri, $params);
                $response = new Response();
                
                try {
                    // Executar middlewares globais
                    $this->executeGlobalMiddlewares($request, $response, $uri);
                    
                    // Executar middlewares da rota
                    $this->executeRouteMiddlewares($route['middlewares'], $request, $response);
                    
                    // Executar handler da rota
                    return $this->executeHandler($route['handler'], $request, $response, $params);
                    
                } catch (\Exception $e) {
                    throw $e;
                }
            }
        }
        
        // Rota não encontrada
        throw new \Exception('Route not found', 404);
    }
    
    /**
     * Normaliza o caminho da rota
     * 
     * @param string $path Caminho original
     * @return string Caminho normalizado
     */
    private function normalizePath(string $path): string {
        $path = $this->basePath . $path;
        $path = '/' . trim($path, '/');
        
        // Tratar raiz
        if ($path === '/') {
            return '/';
        }
        
        return rtrim($path, '/');
    }
    
    /**
     * Converte caminho para regex
     * 
     * @param string $path Caminho da rota
     * @return string Padrão regex
     */
    private function convertToRegex(string $path): string {
        // Escapar caracteres especiais
        $pattern = preg_quote($path, '/');
        
        // Converter parâmetros {param} para grupos de captura
        $pattern = preg_replace('/\\\{([a-zA-Z_][a-zA-Z0-9_]*)\\\}/', '([^/]+)', $pattern);
        
        // Converter wildcards * para grupos opcionais
        $pattern = str_replace('\\*', '.*', $pattern);
        
        return '/^' . $pattern . '$/i';
    }
    
    /**
     * Extrai parâmetros da rota
     * 
     * @param string $routePath Caminho da rota
     * @param string $uri URI da requisição
     * @param array $matches Matches do regex
     * @return array Parâmetros extraídos
     */
    private function extractParams(string $routePath, string $uri, array $matches): array {
        $params = [];
        
        // Extrair nomes dos parâmetros do caminho da rota
        preg_match_all('/\{([a-zA-Z_][a-zA-Z0-9_]*)\}/', $routePath, $paramNames);
        
        // Mapear valores dos parâmetros
        for ($i = 1; $i < count($matches); $i++) {
            if (isset($paramNames[1][$i - 1])) {
                $params[$paramNames[1][$i - 1]] = $matches[$i];
            }
        }
        
        return $params;
    }
    
    /**
     * Executa middlewares globais
     * 
     * @param Request $request Objeto de requisição
     * @param Response $response Objeto de resposta
     * @param string $uri URI da requisição
     */
    private function executeGlobalMiddlewares(Request $request, Response $response, string $uri): void {
        foreach ($this->middlewares as $middleware) {
            if (preg_match($middleware['pattern'], $uri)) {
                $result = call_user_func($middleware['middleware'], $request, $response, function($req, $res) {
                    return $res;
                });
                
                if ($result instanceof Response) {
                    $result->send();
                    exit;
                }
            }
        }
    }
    
    /**
     * Executa middlewares da rota
     * 
     * @param array $middlewares Lista de middlewares
     * @param Request $request Objeto de requisição
     * @param Response $response Objeto de resposta
     */
    private function executeRouteMiddlewares(array $middlewares, Request $request, Response $response): void {
        foreach ($middlewares as $middleware) {
            if (is_string($middleware) && class_exists($middleware)) {
                $middlewareInstance = new $middleware();
                $result = $middlewareInstance->handle($request, $response);
            } elseif (is_callable($middleware)) {
                $result = call_user_func($middleware, $request, $response);
            }
            
            if ($result === false) {
                throw new \Exception('Middleware blocked request', 403);
            }
        }
    }
    
    /**
     * Executa o handler da rota
     * 
     * @param mixed $handler Handler da rota
     * @param Request $request Objeto de requisição
     * @param Response $response Objeto de resposta
     * @param array $params Parâmetros da rota
     * @return mixed
     */
    private function executeHandler($handler, Request $request, Response $response, array $params) {
        // Handler é uma função anônima
        if (is_callable($handler)) {
            return call_user_func_array($handler, [$request, $response] + $params);
        }
        
        // Handler é uma string no formato "Controller@method"
        if (is_string($handler) && strpos($handler, '@') !== false) {
            [$controllerName, $method] = explode('@', $handler, 2);
            
            // Tentar diferentes namespaces
            $possibleClasses = [
                "Controllers\\{$controllerName}",
                "App\\Controllers\\{$controllerName}",
                "App\\Http\\Controllers\\{$controllerName}",
                $controllerName
            ];
            
            foreach ($possibleClasses as $className) {
                if (class_exists($className)) {
                    $controller = new $className();
                    
                    if (method_exists($controller, $method)) {
                        return call_user_func_array([$controller, $method], [$request, $response] + $params);
                    }
                    
                    throw new \Exception("Method {$method} not found in {$className}", 500);
                }
            }
            
            throw new \Exception("Controller {$controllerName} not found", 500);
        }
        
        // Handler é um array [Controller::class, 'method']
        if (is_array($handler) && count($handler) === 2) {
            [$controllerClass, $method] = $handler;
            
            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                
                if (method_exists($controller, $method)) {
                    return call_user_func_array([$controller, $method], [$request, $response] + $params);
                }
                
                throw new \Exception("Method {$method} not found in {$controllerClass}", 500);
            }
            
            throw new \Exception("Controller {$controllerClass} not found", 500);
        }
        
        throw new \Exception('Invalid route handler', 500);
    }
    
    /**
     * Gera URL para rota nomeada
     * 
     * @param string $name Nome da rota
     * @param array $params Parâmetros da rota
     * @return string URL gerada
     * @throws \Exception
     */
    public function url(string $name, array $params = []): string {
        if (!isset($this->namedRoutes[$name])) {
            throw new \Exception("Named route '{$name}' not found");
        }
        
        $route = $this->namedRoutes[$name];
        $path = $route['path'];
        
        // Substituir parâmetros na URL
        foreach ($params as $key => $value) {
            $path = str_replace('{' . $key . '}', $value, $path);
        }
        
        // Verificar se ainda há parâmetros não substituídos
        if (preg_match('/\{[^}]+\}/', $path)) {
            throw new \Exception("Missing parameters for route '{$name}'");
        }
        
        return $path;
    }
    
    /**
     * Obtém todas as rotas registradas
     * 
     * @return array Lista de rotas
     */
    public function getRoutes(): array {
        return $this->routes;
    }
    
    /**
     * Obtém rota por nome
     * 
     * @param string $name Nome da rota
     * @return array|null Dados da rota
     */
    public function getNamedRoute(string $name): ?array {
        return $this->namedRoutes[$name] ?? null;
    }
    
    /**
     * Verifica se uma rota existe
     * 
     * @param string $method Método HTTP
     * @param string $uri URI da requisição
     * @return bool
     */
    public function routeExists(string $method, string $uri): bool {
        $method = strtoupper($method);
        $uri = $this->normalizePath($uri);
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $uri)) {
                return true;
            }
        }
        
        return false;
    }
    
    /**
     * Define o caminho base da aplicação
     * 
     * @param string $basePath Caminho base
     * @return self
     */
    public function setBasePath(string $basePath): self {
        $this->basePath = rtrim($basePath, '/');
        return $this;
    }
    
    /**
     * Obtém o caminho base da aplicação
     * 
     * @return string Caminho base
     */
    public function getBasePath(): string {
        return $this->basePath;
    }
}

/**
 * Classe Request - Representa uma requisição HTTP
 */
class Request {
    private string $method;
    private string $uri;
    private array $params;
    private array $query;
    private array $body;
    private array $headers;
    private array $files;
    
    public function __construct(string $method, string $uri, array $params = []) {
        $this->method = strtoupper($method);
        $this->uri = $uri;
        $this->params = $params;
        $this->query = $_GET ?? [];
        $this->body = $_POST ?? [];
        $this->headers = $this->getAllHeaders();
        $this->files = $_FILES ?? [];
        
        // Parse JSON body for API requests
        if ($this->isJson()) {
            $jsonBody = json_decode(file_get_contents('php://input'), true);
            if ($jsonBody) {
                $this->body = array_merge($this->body, $jsonBody);
            }
        }
    }
    
    public function getMethod(): string {
        return $this->method;
    }
    
    public function getUri(): string {
        return $this->uri;
    }
    
    public function getPath(): string {
        return parse_url($this->uri, PHP_URL_PATH) ?? '/';
    }
    
    public function getParams(): array {
        return $this->params;
    }
    
    public function getParam(string $key, $default = null) {
        return $this->params[$key] ?? $default;
    }
    
    public function getQuery(): array {
        return $this->query;
    }
    
    public function getQueryParam(string $key, $default = null) {
        return $this->query[$key] ?? $default;
    }
    
    public function getBody(): array {
        return $this->body;
    }
    
    public function getBodyParam(string $key, $default = null) {
        return $this->body[$key] ?? $default;
    }
    
    public function getHeaders(): array {
        return $this->headers;
    }
    
    public function getHeader(string $key, $default = null) {
        $key = strtolower($key);
        return $this->headers[$key] ?? $default;
    }
    
    public function getFiles(): array {
        return $this->files;
    }
    
    public function getFile(string $key) {
        return $this->files[$key] ?? null;
    }
    
    public function isJson(): bool {
        return strpos($this->getHeader('content-type', ''), 'application/json') !== false;
    }
    
    public function isAjax(): bool {
        return strtolower($this->getHeader('x-requested-with', '')) === 'xmlhttprequest';
    }
    
    public function getClientIp(): string {
        $ipKeys = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (!empty($_SERVER[$key])) {
                $ip = $_SERVER[$key];
                if (strpos($ip, ',') !== false) {
                    $ip = trim(explode(',', $ip)[0]);
                }
                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }
    
    private function getAllHeaders(): array {
        $headers = [];
        
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
        } else {
            foreach ($_SERVER as $key => $value) {
                if (strpos($key, 'HTTP_') === 0) {
                    $header = str_replace('_', '-', substr($key, 5));
                    $headers[$header] = $value;
                }
            }
        }
        
        return array_change_key_case($headers, CASE_LOWER);
    }
}

/**
 * Classe Response - Representa uma resposta HTTP
 */
class Response {
    private int $statusCode = 200;
    private array $headers = [];
    private string $content = '';
    private bool $sent = false;
    
    public function setStatusCode(int $code): self {
        $this->statusCode = $code;
        return $this;
    }
    
    public function getStatusCode(): int {
        return $this->statusCode;
    }
    
    public function setHeader(string $key, string $value): self {
        $this->headers[$key] = $value;
        return $this;
    }
    
    public function getHeader(string $key): ?string {
        return $this->headers[$key] ?? null;
    }
    
    public function getHeaders(): array {
        return $this->headers;
    }
    
    public function setContent(string $content): self {
        $this->content = $content;
        return $this;
    }
    
    public function getContent(): string {
        return $this->content;
    }
    
    public function json(array $data, int $statusCode = 200): self {
        $this->setStatusCode($statusCode);
        $this->setHeader('Content-Type', 'application/json; charset=utf-8');
        $this->setContent(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        return $this;
    }
    
    public function html(string $html, int $statusCode = 200): self {
        $this->setStatusCode($statusCode);
        $this->setHeader('Content-Type', 'text/html; charset=utf-8');
        $this->setContent($html);
        return $this;
    }
    
    public function redirect(string $url, int $statusCode = 302): self {
        $this->setStatusCode($statusCode);
        $this->setHeader('Location', $url);
        return $this;
    }
    
    public function error(string $message, int $statusCode = 500): self {
        $this->setStatusCode($statusCode);
        $this->setHeader('Content-Type', 'application/json; charset=utf-8');
        $this->setContent(json_encode([
            'error' => true,
            'message' => $message,
            'code' => $statusCode
        ], JSON_UNESCAPED_UNICODE));
        return $this;
    }
    
    public function send(): void {
        if ($this->sent) {
            return;
        }
        
        // Enviar status code
        http_response_code($this->statusCode);
        
        // Enviar headers
        foreach ($this->headers as $key => $value) {
            header("{$key}: {$value}");
        }
        
        // Enviar conteúdo
        echo $this->content;
        
        $this->sent = true;
    }
    
    public function isSent(): bool {
        return $this->sent;
    }
}