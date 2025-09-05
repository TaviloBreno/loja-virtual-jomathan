<?php

namespace App\Infrastructure\Http;

use Exception;

/**
 * Router - Gerencia o roteamento da aplicação
 * Implementa padrão Strategy para diferentes tipos de rotas
 */
class Router
{
    private array $routes = [];
    private array $middlewares = [];

    /**
     * Registra uma rota GET
     */
    public function get(string $path, $handler, array $middlewares = []): void
    {
        $this->addRoute('GET', $path, $handler, $middlewares);
    }

    /**
     * Registra uma rota POST
     */
    public function post(string $path, $handler, array $middlewares = []): void
    {
        $this->addRoute('POST', $path, $handler, $middlewares);
    }

    /**
     * Registra uma rota PUT
     */
    public function put(string $path, $handler, array $middlewares = []): void
    {
        $this->addRoute('PUT', $path, $handler, $middlewares);
    }

    /**
     * Registra uma rota DELETE
     */
    public function delete(string $path, $handler, array $middlewares = []): void
    {
        $this->addRoute('DELETE', $path, $handler, $middlewares);
    }

    /**
     * Adiciona uma rota ao sistema
     */
    private function addRoute(string $method, string $path, $handler, array $middlewares = []): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $this->normalizePath($path),
            'handler' => $handler,
            'middlewares' => $middlewares,
            'pattern' => $this->createPattern($path)
        ];
    }

    /**
     * Processa a requisição e executa o handler apropriado
     */
    public function dispatch(Request $request, Response $response): void
    {
        $method = $request->getMethod();
        $path = $this->normalizePath($request->getPath());

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $path, $matches)) {
                // Remove o primeiro elemento (match completo)
                array_shift($matches);
                
                // Executa middlewares
                foreach ($route['middlewares'] as $middleware) {
                    $middlewareInstance = new $middleware();
                    $middlewareInstance->handle($request, $response);
                }

                // Executa o handler
                $this->executeHandler($route['handler'], $request, $response, $matches);
                return;
            }
        }

        // Rota não encontrada
        $response->setStatusCode(404);
        $response->setContent('<h1>404 - Página não encontrada</h1>');
        $response->send();
    }

    /**
     * Executa o handler da rota
     */
    private function executeHandler($handler, Request $request, Response $response, array $params = []): void
    {
        if (is_string($handler)) {
            // Handler no formato "Controller@method"
            [$controllerClass, $method] = explode('@', $handler);
            $controllerClass = "App\\Application\\Controllers\\" . $controllerClass;
            
            if (!class_exists($controllerClass)) {
                throw new Exception("Controller {$controllerClass} não encontrado");
            }

            $controller = new $controllerClass();
            
            if (!method_exists($controller, $method)) {
                throw new Exception("Método {$method} não encontrado no controller {$controllerClass}");
            }

            $result = $controller->$method($request, $response, ...$params);
        } elseif (is_callable($handler)) {
            // Handler é uma função anônima
            $result = $handler($request, $response, ...$params);
        } else {
            throw new Exception('Handler inválido');
        }

        // Se o resultado for uma string, define como conteúdo da resposta
        if (is_string($result)) {
            $response->setContent($result);
        }

        $response->send();
    }

    /**
     * Normaliza o caminho da rota
     */
    private function normalizePath(string $path): string
    {
        $path = trim($path, '/');
        return $path === '' ? '/' : '/' . $path;
    }

    /**
     * Cria padrão regex para a rota
     */
    private function createPattern(string $path): string
    {
        $path = $this->normalizePath($path);
        
        // Substitui parâmetros {id} por grupos de captura
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $path);
        
        return '#^' . $pattern . '$#';
    }
}