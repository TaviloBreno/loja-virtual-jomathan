<?php

namespace App\Infrastructure\Http;

/**
 * Request - Encapsula dados da requisição HTTP
 * Implementa padrão Value Object
 */
class Request
{
    private string $method;
    private string $path;
    private array $query;
    private array $post;
    private array $files;
    private array $server;
    private array $headers;
    private ?string $body;

    public function __construct(
        string $method,
        string $path,
        array $query = [],
        array $post = [],
        array $files = [],
        array $server = [],
        array $headers = [],
        ?string $body = null
    ) {
        $this->method = strtoupper($method);
        $this->path = $path;
        $this->query = $query;
        $this->post = $post;
        $this->files = $files;
        $this->server = $server;
        $this->headers = $headers;
        $this->body = $body;
    }

    /**
     * Cria uma instância a partir das variáveis globais do PHP
     */
    public static function createFromGlobals(): self
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $path = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $query = $_GET ?? [];
        $post = $_POST ?? [];
        $files = $_FILES ?? [];
        $server = $_SERVER ?? [];
        
        // Extrai headers
        $headers = [];
        foreach ($server as $key => $value) {
            if (strpos($key, 'HTTP_') === 0) {
                $headerName = str_replace('_', '-', substr($key, 5));
                $headers[strtolower($headerName)] = $value;
            }
        }

        // Lê o corpo da requisição
        $body = null;
        if (in_array($method, ['POST', 'PUT', 'PATCH', 'DELETE'])) {
            $body = file_get_contents('php://input');
        }

        return new self($method, $path, $query, $post, $files, $server, $headers, $body);
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getQuery(?string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->query;
        }
        
        return $this->query[$key] ?? $default;
    }

    public function getPost(?string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->post;
        }
        
        return $this->post[$key] ?? $default;
    }

    public function getFiles(?string $key = null)
    {
        if ($key === null) {
            return $this->files;
        }
        
        return $this->files[$key] ?? null;
    }

    public function getServer(?string $key = null, $default = null)
    {
        if ($key === null) {
            return $this->server;
        }
        
        return $this->server[$key] ?? $default;
    }

    public function getHeader(string $name, $default = null)
    {
        $name = strtolower($name);
        return $this->headers[$name] ?? $default;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * Obtém dados JSON do corpo da requisição
     */
    public function getJson(): ?array
    {
        if ($this->body === null) {
            return null;
        }

        $data = json_decode($this->body, true);
        return json_last_error() === JSON_ERROR_NONE ? $data : null;
    }

    /**
     * Verifica se a requisição é AJAX
     */
    public function isAjax(): bool
    {
        return $this->getHeader('x-requested-with') === 'XMLHttpRequest';
    }

    /**
     * Verifica se a requisição aceita JSON
     */
    public function wantsJson(): bool
    {
        $accept = $this->getHeader('accept', '');
        return strpos($accept, 'application/json') !== false;
    }

    /**
     * Obtém um input (POST ou GET)
     */
    public function input(string $key, $default = null)
    {
        return $this->post[$key] ?? $this->query[$key] ?? $default;
    }

    /**
     * Verifica se um input existe
     */
    public function has(string $key): bool
    {
        return isset($this->post[$key]) || isset($this->query[$key]);
    }
}