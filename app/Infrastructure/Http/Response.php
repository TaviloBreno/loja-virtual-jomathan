<?php

namespace App\Infrastructure\Http;

/**
 * Response - Gerencia respostas HTTP
 * Implementa padrão Builder para construção de respostas
 */
class Response
{
    private int $statusCode = 200;
    private array $headers = [];
    private string $content = '';
    private bool $sent = false;

    /**
     * Status codes HTTP mais comuns
     */
    private const STATUS_TEXTS = [
        200 => 'OK',
        201 => 'Created',
        204 => 'No Content',
        301 => 'Moved Permanently',
        302 => 'Found',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        422 => 'Unprocessable Entity',
        500 => 'Internal Server Error',
        503 => 'Service Unavailable'
    ];

    public function setStatusCode(int $code): self
    {
        $this->statusCode = $code;
        return $this;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function setHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;
        return $this;
    }

    public function getHeader(string $name): ?string
    {
        return $this->headers[$name] ?? null;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * Define resposta JSON
     */
    public function json(array $data, int $statusCode = 200): self
    {
        $this->setStatusCode($statusCode)
             ->setHeader('Content-Type', 'application/json')
             ->setContent(json_encode($data, JSON_UNESCAPED_UNICODE));
        
        return $this;
    }

    /**
     * Define resposta HTML
     */
    public function html(string $content, int $statusCode = 200): self
    {
        $this->setStatusCode($statusCode)
             ->setHeader('Content-Type', 'text/html; charset=utf-8')
             ->setContent($content);
        
        return $this;
    }

    /**
     * Redireciona para uma URL
     */
    public function redirect(string $url, int $statusCode = 302): self
    {
        $this->setStatusCode($statusCode)
             ->setHeader('Location', $url);
        
        return $this;
    }

    /**
     * Resposta de erro
     */
    public function error(string $message, int $statusCode = 500): self
    {
        $this->setStatusCode($statusCode)
             ->setHeader('Content-Type', 'text/html; charset=utf-8')
             ->setContent("<h1>Erro {$statusCode}</h1><p>{$message}</p>");
        
        return $this;
    }

    /**
     * Resposta de erro em JSON
     */
    public function errorJson(string $message, int $statusCode = 500, array $errors = []): self
    {
        $data = [
            'error' => true,
            'message' => $message,
            'status_code' => $statusCode
        ];

        if (!empty($errors)) {
            $data['errors'] = $errors;
        }

        return $this->json($data, $statusCode);
    }

    /**
     * Resposta de sucesso em JSON
     */
    public function successJson(string $message = 'Success', array $data = [], int $statusCode = 200): self
    {
        $response = [
            'success' => true,
            'message' => $message,
            'status_code' => $statusCode
        ];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        return $this->json($response, $statusCode);
    }

    /**
     * Envia a resposta
     */
    public function send(): void
    {
        if ($this->sent) {
            return;
        }

        // Define status code
        $statusText = self::STATUS_TEXTS[$this->statusCode] ?? 'Unknown';
        header("HTTP/1.1 {$this->statusCode} {$statusText}");

        // Define headers
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}");
        }

        // Envia conteúdo
        echo $this->content;

        $this->sent = true;
    }

    /**
     * Verifica se a resposta já foi enviada
     */
    public function isSent(): bool
    {
        return $this->sent;
    }

    /**
     * Define cookie
     */
    public function setCookie(
        string $name,
        string $value,
        int $expire = 0,
        string $path = '/',
        string $domain = '',
        bool $secure = false,
        bool $httpOnly = true
    ): self {
        setcookie($name, $value, $expire, $path, $domain, $secure, $httpOnly);
        return $this;
    }

    /**
     * Remove cookie
     */
    public function removeCookie(string $name, string $path = '/', string $domain = ''): self
    {
        setcookie($name, '', time() - 3600, $path, $domain);
        return $this;
    }
}