<?php

namespace Core;

/**
 * Classe Request - NeonShop
 * 
 * Gerencia dados da requisição HTTP, incluindo parâmetros GET/POST,
 * headers, cookies, arquivos enviados e validação de dados.
 */
class Request {
    
    private array $get;
    private array $post;
    private array $server;
    private array $cookies;
    private array $files;
    private array $headers;
    private ?string $body;
    private array $json;
    private string $method;
    private string $uri;
    private string $path;
    private array $segments;
    private array $query;
    
    /**
     * Construtor da classe Request
     */
    public function __construct() {
        $this->get = $_GET ?? [];
        $this->post = $_POST ?? [];
        $this->server = $_SERVER ?? [];
        $this->cookies = $_COOKIE ?? [];
        $this->files = $_FILES ?? [];
        $this->headers = $this->parseHeaders();
        $this->body = file_get_contents('php://input');
        $this->json = $this->parseJson();
        $this->method = strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
        $this->uri = $this->server['REQUEST_URI'] ?? '/';
        $this->path = parse_url($this->uri, PHP_URL_PATH) ?? '/';
        $this->segments = array_filter(explode('/', trim($this->path, '/')));
        $this->query = $this->parseQuery();
    }
    
    /**
     * Obtém o método HTTP
     * 
     * @return string
     */
    public function getMethod(): string {
        return $this->method;
    }
    
    /**
     * Obtém a URI completa
     * 
     * @return string
     */
    public function getUri(): string {
        return $this->uri;
    }
    
    /**
     * Obtém o caminho da URI
     * 
     * @return string
     */
    public function getPath(): string {
        return $this->path;
    }
    
    /**
     * Obtém os segmentos do caminho
     * 
     * @return array
     */
    public function getSegments(): array {
        return $this->segments;
    }
    
    /**
     * Obtém um segmento específico
     * 
     * @param int $index Índice do segmento
     * @param string|null $default Valor padrão
     * @return string|null
     */
    public function segment(int $index, ?string $default = null): ?string {
        return $this->segments[$index] ?? $default;
    }
    
    /**
     * Obtém parâmetro GET
     * 
     * @param string|null $key Chave do parâmetro
     * @param mixed $default Valor padrão
     * @return mixed
     */
    public function get(?string $key = null, $default = null) {
        if ($key === null) {
            return $this->get;
        }
        return $this->get[$key] ?? $default;
    }
    
    /**
     * Obtém parâmetro POST
     * 
     * @param string|null $key Chave do parâmetro
     * @param mixed $default Valor padrão
     * @return mixed
     */
    public function post(?string $key = null, $default = null) {
        if ($key === null) {
            return $this->post;
        }
        return $this->post[$key] ?? $default;
    }
    
    /**
     * Obtém parâmetro de qualquer método
     * 
     * @param string $key Chave do parâmetro
     * @param mixed $default Valor padrão
     * @return mixed
     */
    public function input(string $key, $default = null) {
        return $this->post[$key] ?? $this->get[$key] ?? $this->json[$key] ?? $default;
    }
    
    /**
     * Obtém todos os dados de entrada
     * 
     * @return array
     */
    public function all(): array {
        return array_merge($this->get, $this->post, $this->json);
    }
    
    /**
     * Obtém apenas os campos especificados
     * 
     * @param array $keys Chaves dos campos
     * @return array
     */
    public function only(array $keys): array {
        $data = $this->all();
        return array_intersect_key($data, array_flip($keys));
    }
    
    /**
     * Obtém todos exceto os campos especificados
     * 
     * @param array $keys Chaves dos campos a excluir
     * @return array
     */
    public function except(array $keys): array {
        $data = $this->all();
        return array_diff_key($data, array_flip($keys));
    }
    
    /**
     * Verifica se tem um parâmetro
     * 
     * @param string $key Chave do parâmetro
     * @return bool
     */
    public function has(string $key): bool {
        return isset($this->get[$key]) || isset($this->post[$key]) || isset($this->json[$key]);
    }
    
    /**
     * Verifica se tem vários parâmetros
     * 
     * @param array $keys Chaves dos parâmetros
     * @return bool
     */
    public function hasAll(array $keys): bool {
        foreach ($keys as $key) {
            if (!$this->has($key)) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * Verifica se tem pelo menos um dos parâmetros
     * 
     * @param array $keys Chaves dos parâmetros
     * @return bool
     */
    public function hasAny(array $keys): bool {
        foreach ($keys as $key) {
            if ($this->has($key)) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Obtém dados JSON
     * 
     * @param string|null $key Chave específica
     * @param mixed $default Valor padrão
     * @return mixed
     */
    public function json(?string $key = null, $default = null) {
        if ($key === null) {
            return $this->json;
        }
        return $this->json[$key] ?? $default;
    }
    
    /**
     * Obtém o corpo da requisição
     * 
     * @return string
     */
    public function getBody(): string {
        return $this->body ?? '';
    }
    
    /**
     * Obtém header
     * 
     * @param string $key Nome do header
     * @param string|null $default Valor padrão
     * @return string|null
     */
    public function header(string $key, ?string $default = null): ?string {
        $key = strtolower($key);
        return $this->headers[$key] ?? $default;
    }
    
    /**
     * Obtém todos os headers
     * 
     * @return array
     */
    public function headers(): array {
        return $this->headers;
    }
    
    /**
     * Obtém cookie
     * 
     * @param string $key Nome do cookie
     * @param string|null $default Valor padrão
     * @return string|null
     */
    public function cookie(string $key, ?string $default = null): ?string {
        return $this->cookies[$key] ?? $default;
    }
    
    /**
     * Obtém arquivo enviado
     * 
     * @param string $key Nome do campo do arquivo
     * @return array|null
     */
    public function file(string $key): ?array {
        return $this->files[$key] ?? null;
    }
    
    /**
     * Obtém todos os arquivos
     * 
     * @return array
     */
    public function files(): array {
        return $this->files;
    }
    
    /**
     * Verifica se tem arquivo
     * 
     * @param string $key Nome do campo
     * @return bool
     */
    public function hasFile(string $key): bool {
        return isset($this->files[$key]) && $this->files[$key]['error'] === UPLOAD_ERR_OK;
    }
    
    /**
     * Obtém IP do cliente
     * 
     * @return string
     */
    public function ip(): string {
        $keys = ['HTTP_X_FORWARDED_FOR', 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'REMOTE_ADDR'];
        
        foreach ($keys as $key) {
            if (!empty($this->server[$key])) {
                $ips = explode(',', $this->server[$key]);
                return trim($ips[0]);
            }
        }
        
        return $this->server['REMOTE_ADDR'] ?? '127.0.0.1';
    }
    
    /**
     * Obtém User Agent
     * 
     * @return string
     */
    public function userAgent(): string {
        return $this->server['HTTP_USER_AGENT'] ?? '';
    }
    
    /**
     * Verifica se é requisição AJAX
     * 
     * @return bool
     */
    public function isAjax(): bool {
        return strtolower($this->header('X-Requested-With', '')) === 'xmlhttprequest';
    }
    
    /**
     * Verifica se é requisição JSON
     * 
     * @return bool
     */
    public function isJson(): bool {
        return str_contains($this->header('Content-Type', ''), 'application/json');
    }
    
    /**
     * Verifica se é HTTPS
     * 
     * @return bool
     */
    public function isSecure(): bool {
        return (!empty($this->server['HTTPS']) && $this->server['HTTPS'] !== 'off') ||
               (!empty($this->server['SERVER_PORT']) && $this->server['SERVER_PORT'] == 443) ||
               (!empty($this->server['HTTP_X_FORWARDED_PROTO']) && $this->server['HTTP_X_FORWARDED_PROTO'] === 'https');
    }
    
    /**
     * Verifica método HTTP
     * 
     * @param string $method Método
     * @return bool
     */
    public function isMethod(string $method): bool {
        return $this->method === strtoupper($method);
    }
    
    /**
     * Verifica se é GET
     * 
     * @return bool
     */
    public function isGet(): bool {
        return $this->isMethod('GET');
    }
    
    /**
     * Verifica se é POST
     * 
     * @return bool
     */
    public function isPost(): bool {
        return $this->isMethod('POST');
    }
    
    /**
     * Verifica se é PUT
     * 
     * @return bool
     */
    public function isPut(): bool {
        return $this->isMethod('PUT');
    }
    
    /**
     * Verifica se é DELETE
     * 
     * @return bool
     */
    public function isDelete(): bool {
        return $this->isMethod('DELETE');
    }
    
    /**
     * Valida dados da requisição
     * 
     * @param array $rules Regras de validação
     * @return array Erros de validação
     */
    public function validate(array $rules): array {
        $errors = [];
        $data = $this->all();
        
        foreach ($rules as $field => $rule) {
            $fieldRules = is_string($rule) ? explode('|', $rule) : $rule;
            $value = $data[$field] ?? null;
            
            foreach ($fieldRules as $singleRule) {
                $error = $this->validateField($field, $value, $singleRule);
                if ($error) {
                    $errors[$field] = $error;
                    break;
                }
            }
        }
        
        return $errors;
    }
    
    /**
     * Valida um campo específico
     * 
     * @param string $field Nome do campo
     * @param mixed $value Valor do campo
     * @param string $rule Regra de validação
     * @return string|null Erro ou null se válido
     */
    private function validateField(string $field, $value, string $rule): ?string {
        if ($rule === 'required' && empty($value)) {
            return "O campo {$field} é obrigatório";
        }
        
        if (str_starts_with($rule, 'min:')) {
            $min = (int)substr($rule, 4);
            if (strlen($value) < $min) {
                return "O campo {$field} deve ter pelo menos {$min} caracteres";
            }
        }
        
        if (str_starts_with($rule, 'max:')) {
            $max = (int)substr($rule, 4);
            if (strlen($value) > $max) {
                return "O campo {$field} deve ter no máximo {$max} caracteres";
            }
        }
        
        if ($rule === 'email' && !empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return "O campo {$field} deve ser um email válido";
        }
        
        if ($rule === 'numeric' && !empty($value) && !is_numeric($value)) {
            return "O campo {$field} deve ser numérico";
        }
        
        if ($rule === 'integer' && !empty($value) && !filter_var($value, FILTER_VALIDATE_INT)) {
            return "O campo {$field} deve ser um número inteiro";
        }
        
        return null;
    }
    
    /**
     * Analisa os headers da requisição
     * 
     * @return array
     */
    private function parseHeaders(): array {
        $headers = [];
        
        foreach ($this->server as $key => $value) {
            if (str_starts_with($key, 'HTTP_')) {
                $header = strtolower(str_replace('_', '-', substr($key, 5)));
                $headers[$header] = $value;
            }
        }
        
        // Headers especiais
        if (isset($this->server['CONTENT_TYPE'])) {
            $headers['content-type'] = $this->server['CONTENT_TYPE'];
        }
        
        if (isset($this->server['CONTENT_LENGTH'])) {
            $headers['content-length'] = $this->server['CONTENT_LENGTH'];
        }
        
        return $headers;
    }
    
    /**
     * Analisa dados JSON
     * 
     * @return array
     */
    private function parseJson(): array {
        if ($this->isJson() && !empty($this->body)) {
            $decoded = json_decode($this->body, true);
            return is_array($decoded) ? $decoded : [];
        }
        
        return [];
    }
    
    /**
     * Analisa query string
     * 
     * @return array
     */
    private function parseQuery(): array {
        $queryString = parse_url($this->uri, PHP_URL_QUERY);
        if (!$queryString) {
            return [];
        }
        
        parse_str($queryString, $query);
        return $query;
    }
    
    /**
     * Obtém dados do servidor
     * 
     * @param string|null $key Chave específica
     * @param mixed $default Valor padrão
     * @return mixed
     */
    public function server(?string $key = null, $default = null) {
        if ($key === null) {
            return $this->server;
        }
        return $this->server[$key] ?? $default;
    }
    
    /**
     * Obtém a URL completa
     * 
     * @return string
     */
    public function fullUrl(): string {
        $protocol = $this->isSecure() ? 'https' : 'http';
        $host = $this->server['HTTP_HOST'] ?? 'localhost';
        return $protocol . '://' . $host . $this->uri;
    }
    
    /**
     * Obtém a URL base
     * 
     * @return string
     */
    public function baseUrl(): string {
        $protocol = $this->isSecure() ? 'https' : 'http';
        $host = $this->server['HTTP_HOST'] ?? 'localhost';
        $path = dirname($this->server['SCRIPT_NAME'] ?? '');
        return $protocol . '://' . $host . $path;
    }
}