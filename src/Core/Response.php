<?php

namespace Core;

/**
 * Classe Response - NeonShop
 * 
 * Gerencia respostas HTTP, incluindo headers, cookies, status codes,
 * redirecionamentos e diferentes tipos de conteúdo.
 */
class Response {
    
    private int $statusCode = 200;
    private array $headers = [];
    private array $cookies = [];
    private string $content = '';
    private bool $sent = false;
    
    /**
     * Códigos de status HTTP
     */
    public const HTTP_OK = 200;
    public const HTTP_CREATED = 201;
    public const HTTP_NO_CONTENT = 204;
    public const HTTP_MOVED_PERMANENTLY = 301;
    public const HTTP_FOUND = 302;
    public const HTTP_NOT_MODIFIED = 304;
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_UNAUTHORIZED = 401;
    public const HTTP_FORBIDDEN = 403;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_METHOD_NOT_ALLOWED = 405;
    public const HTTP_UNPROCESSABLE_ENTITY = 422;
    public const HTTP_INTERNAL_SERVER_ERROR = 500;
    public const HTTP_SERVICE_UNAVAILABLE = 503;
    
    /**
     * Define o código de status
     * 
     * @param int $code Código de status
     * @return self
     */
    public function status(int $code): self {
        $this->statusCode = $code;
        return $this;
    }
    
    /**
     * Obtém o código de status
     * 
     * @return int
     */
    public function getStatusCode(): int {
        return $this->statusCode;
    }
    
    /**
     * Define um header
     * 
     * @param string $name Nome do header
     * @param string $value Valor do header
     * @return self
     */
    public function header(string $name, string $value): self {
        $this->headers[$name] = $value;
        return $this;
    }
    
    /**
     * Define múltiplos headers
     * 
     * @param array $headers Array de headers
     * @return self
     */
    public function headers(array $headers): self {
        foreach ($headers as $name => $value) {
            $this->header($name, $value);
        }
        return $this;
    }
    
    /**
     * Obtém um header
     * 
     * @param string $name Nome do header
     * @return string|null
     */
    public function getHeader(string $name): ?string {
        return $this->headers[$name] ?? null;
    }
    
    /**
     * Obtém todos os headers
     * 
     * @return array
     */
    public function getHeaders(): array {
        return $this->headers;
    }
    
    /**
     * Define um cookie
     * 
     * @param string $name Nome do cookie
     * @param string $value Valor do cookie
     * @param int $expires Tempo de expiração
     * @param string $path Caminho
     * @param string $domain Domínio
     * @param bool $secure HTTPS apenas
     * @param bool $httpOnly HTTP apenas
     * @param string $sameSite SameSite policy
     * @return self
     */
    public function cookie(
        string $name,
        string $value,
        int $expires = 0,
        string $path = '/',
        string $domain = '',
        bool $secure = false,
        bool $httpOnly = true,
        string $sameSite = 'Lax'
    ): self {
        $this->cookies[] = [
            'name' => $name,
            'value' => $value,
            'expires' => $expires,
            'path' => $path,
            'domain' => $domain,
            'secure' => $secure,
            'httponly' => $httpOnly,
            'samesite' => $sameSite
        ];
        return $this;
    }
    
    /**
     * Remove um cookie
     * 
     * @param string $name Nome do cookie
     * @param string $path Caminho
     * @param string $domain Domínio
     * @return self
     */
    public function removeCookie(string $name, string $path = '/', string $domain = ''): self {
        return $this->cookie($name, '', time() - 3600, $path, $domain);
    }
    
    /**
     * Define o conteúdo da resposta
     * 
     * @param string $content Conteúdo
     * @return self
     */
    public function content(string $content): self {
        $this->content = $content;
        return $this;
    }
    
    /**
     * Obtém o conteúdo
     * 
     * @return string
     */
    public function getContent(): string {
        return $this->content;
    }
    
    /**
     * Resposta JSON
     * 
     * @param mixed $data Dados para JSON
     * @param int $status Código de status
     * @param array $headers Headers adicionais
     * @return self
     */
    public function json($data, int $status = 200, array $headers = []): self {
        $this->status($status)
             ->header('Content-Type', 'application/json; charset=utf-8')
             ->headers($headers)
             ->content(json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        
        return $this;
    }
    
    /**
     * Resposta HTML
     * 
     * @param string $html Conteúdo HTML
     * @param int $status Código de status
     * @param array $headers Headers adicionais
     * @return self
     */
    public function html(string $html, int $status = 200, array $headers = []): self {
        $this->status($status)
             ->header('Content-Type', 'text/html; charset=utf-8')
             ->headers($headers)
             ->content($html);
        
        return $this;
    }
    
    /**
     * Resposta de texto
     * 
     * @param string $text Conteúdo de texto
     * @param int $status Código de status
     * @param array $headers Headers adicionais
     * @return self
     */
    public function text(string $text, int $status = 200, array $headers = []): self {
        $this->status($status)
             ->header('Content-Type', 'text/plain; charset=utf-8')
             ->headers($headers)
             ->content($text);
        
        return $this;
    }
    
    /**
     * Redirecionamento
     * 
     * @param string $url URL de destino
     * @param int $status Código de status (301 ou 302)
     * @return self
     */
    public function redirect(string $url, int $status = 302): self {
        $this->status($status)
             ->header('Location', $url)
             ->content('');
        
        return $this;
    }
    
    /**
     * Redirecionamento permanente
     * 
     * @param string $url URL de destino
     * @return self
     */
    public function redirectPermanent(string $url): self {
        return $this->redirect($url, 301);
    }
    
    /**
     * Redirecionamento de volta
     * 
     * @param string $fallback URL de fallback
     * @return self
     */
    public function back(string $fallback = '/'): self {
        $referer = $_SERVER['HTTP_REFERER'] ?? $fallback;
        return $this->redirect($referer);
    }
    
    /**
     * Resposta de erro
     * 
     * @param string $message Mensagem de erro
     * @param int $status Código de status
     * @param array $details Detalhes adicionais
     * @return self
     */
    public function error(string $message, int $status = 400, array $details = []): self {
        $data = [
            'error' => true,
            'message' => $message,
            'status' => $status
        ];
        
        if (!empty($details)) {
            $data['details'] = $details;
        }
        
        return $this->json($data, $status);
    }
    
    /**
     * Resposta de sucesso
     * 
     * @param mixed $data Dados de resposta
     * @param string $message Mensagem de sucesso
     * @param int $status Código de status
     * @return self
     */
    public function success($data = null, string $message = 'Sucesso', int $status = 200): self {
        $response = [
            'success' => true,
            'message' => $message,
            'status' => $status
        ];
        
        if ($data !== null) {
            $response['data'] = $data;
        }
        
        return $this->json($response, $status);
    }
    
    /**
     * Resposta de validação
     * 
     * @param array $errors Erros de validação
     * @param string $message Mensagem
     * @return self
     */
    public function validation(array $errors, string $message = 'Dados inválidos'): self {
        return $this->json([
            'error' => true,
            'message' => $message,
            'errors' => $errors,
            'status' => 422
        ], 422);
    }
    
    /**
     * Resposta não encontrado
     * 
     * @param string $message Mensagem
     * @return self
     */
    public function notFound(string $message = 'Recurso não encontrado'): self {
        return $this->error($message, 404);
    }
    
    /**
     * Resposta não autorizado
     * 
     * @param string $message Mensagem
     * @return self
     */
    public function unauthorized(string $message = 'Não autorizado'): self {
        return $this->error($message, 401);
    }
    
    /**
     * Resposta proibido
     * 
     * @param string $message Mensagem
     * @return self
     */
    public function forbidden(string $message = 'Acesso negado'): self {
        return $this->error($message, 403);
    }
    
    /**
     * Resposta de erro interno
     * 
     * @param string $message Mensagem
     * @return self
     */
    public function serverError(string $message = 'Erro interno do servidor'): self {
        return $this->error($message, 500);
    }
    
    /**
     * Download de arquivo
     * 
     * @param string $filePath Caminho do arquivo
     * @param string|null $fileName Nome do arquivo para download
     * @param array $headers Headers adicionais
     * @return self
     */
    public function download(string $filePath, ?string $fileName = null, array $headers = []): self {
        if (!file_exists($filePath)) {
            return $this->notFound('Arquivo não encontrado');
        }
        
        $fileName = $fileName ?: basename($filePath);
        $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';
        $fileSize = filesize($filePath);
        
        $this->status(200)
             ->header('Content-Type', $mimeType)
             ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"')
             ->header('Content-Length', (string)$fileSize)
             ->header('Cache-Control', 'no-cache, must-revalidate')
             ->header('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT')
             ->headers($headers);
        
        $this->content = file_get_contents($filePath);
        
        return $this;
    }
    
    /**
     * Exibição inline de arquivo
     * 
     * @param string $filePath Caminho do arquivo
     * @param string|null $fileName Nome do arquivo
     * @param array $headers Headers adicionais
     * @return self
     */
    public function file(string $filePath, ?string $fileName = null, array $headers = []): self {
        if (!file_exists($filePath)) {
            return $this->notFound('Arquivo não encontrado');
        }
        
        $fileName = $fileName ?: basename($filePath);
        $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';
        $fileSize = filesize($filePath);
        
        $this->status(200)
             ->header('Content-Type', $mimeType)
             ->header('Content-Disposition', 'inline; filename="' . $fileName . '"')
             ->header('Content-Length', (string)$fileSize)
             ->headers($headers);
        
        $this->content = file_get_contents($filePath);
        
        return $this;
    }
    
    /**
     * Resposta de cache
     * 
     * @param int $seconds Segundos para cache
     * @return self
     */
    public function cache(int $seconds): self {
        $this->header('Cache-Control', 'public, max-age=' . $seconds)
             ->header('Expires', gmdate('D, d M Y H:i:s', time() + $seconds) . ' GMT');
        
        return $this;
    }
    
    /**
     * Desabilita cache
     * 
     * @return self
     */
    public function noCache(): self {
        $this->header('Cache-Control', 'no-cache, no-store, must-revalidate')
             ->header('Pragma', 'no-cache')
             ->header('Expires', '0');
        
        return $this;
    }
    
    /**
     * Define CORS headers
     * 
     * @param string $origin Origem permitida
     * @param array $methods Métodos permitidos
     * @param array $headers Headers permitidos
     * @param int $maxAge Tempo de cache do preflight
     * @return self
     */
    public function cors(
        string $origin = '*',
        array $methods = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
        array $headers = ['Content-Type', 'Authorization', 'X-Requested-With'],
        int $maxAge = 86400
    ): self {
        $this->header('Access-Control-Allow-Origin', $origin)
             ->header('Access-Control-Allow-Methods', implode(', ', $methods))
             ->header('Access-Control-Allow-Headers', implode(', ', $headers))
             ->header('Access-Control-Max-Age', (string)$maxAge);
        
        return $this;
    }
    
    /**
     * Envia a resposta
     * 
     * @return void
     */
    public function send(): void {
        if ($this->sent) {
            return;
        }
        
        // Define o código de status
        http_response_code($this->statusCode);
        
        // Envia os headers
        foreach ($this->headers as $name => $value) {
            header($name . ': ' . $value);
        }
        
        // Envia os cookies
        foreach ($this->cookies as $cookie) {
            setcookie(
                $cookie['name'],
                $cookie['value'],
                [
                    'expires' => $cookie['expires'],
                    'path' => $cookie['path'],
                    'domain' => $cookie['domain'],
                    'secure' => $cookie['secure'],
                    'httponly' => $cookie['httponly'],
                    'samesite' => $cookie['samesite']
                ]
            );
        }
        
        // Envia o conteúdo
        echo $this->content;
        
        $this->sent = true;
    }
    
    /**
     * Verifica se a resposta foi enviada
     * 
     * @return bool
     */
    public function isSent(): bool {
        return $this->sent;
    }
    
    /**
     * Obtém o nome do status HTTP
     * 
     * @param int $code Código de status
     * @return string
     */
    public static function getStatusText(int $code): string {
        $statusTexts = [
            200 => 'OK',
            201 => 'Created',
            204 => 'No Content',
            301 => 'Moved Permanently',
            302 => 'Found',
            304 => 'Not Modified',
            400 => 'Bad Request',
            401 => 'Unauthorized',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            422 => 'Unprocessable Entity',
            500 => 'Internal Server Error',
            503 => 'Service Unavailable'
        ];
        
        return $statusTexts[$code] ?? 'Unknown Status';
    }
    
    /**
     * Converte para string
     * 
     * @return string
     */
    public function __toString(): string {
        return $this->content;
    }
    
    /**
     * Cria uma nova instância de Response
     * 
     * @return self
     */
    public static function make(): self {
        return new self();
    }
}