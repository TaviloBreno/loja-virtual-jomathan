<?php

namespace Core;

/**
 * Sistema de Middlewares - NeonShop
 * 
 * Classe responsável por gerenciar middlewares de autenticação,
 * CSRF, rate limiting e outras validações de segurança.
 */
class Middleware {
    
    /**
     * Middleware de autenticação
     * Verifica se o usuário está logado
     * 
     * @param Request $request
     * @param Response $response
     * @return bool|Response
     */
    public static function auth(Request $request, Response $response) {
        // Iniciar sessão se não estiver iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Verificar se usuário está logado
        if (!isset($_SESSION['user_id']) || empty($_SESSION['user_id'])) {
            // Se for requisição Ajax, retornar JSON
            if ($request->isAjax() || strpos($request->getPath(), '/api/') === 0) {
                return $response->json([
                    'error' => true,
                    'message' => 'Usuário não autenticado',
                    'code' => 401,
                    'redirect' => '/login'
                ], 401);
            }
            
            // Salvar URL de destino para redirecionamento após login
            $_SESSION['redirect_after_login'] = $request->getUri();
            
            // Redirecionar para login
            return $response->redirect('/login', 302);
        }
        
        // Verificar se a sessão não expirou (24 horas)
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > 86400) {
            session_destroy();
            
            if ($request->isAjax() || strpos($request->getPath(), '/api/') === 0) {
                return $response->json([
                    'error' => true,
                    'message' => 'Sessão expirada',
                    'code' => 401,
                    'redirect' => '/login'
                ], 401);
            }
            
            return $response->redirect('/login?expired=1', 302);
        }
        
        // Atualizar timestamp da última atividade
        $_SESSION['last_activity'] = time();
        
        return true;
    }
    
    /**
     * Middleware de administrador
     * Verifica se o usuário é administrador
     * 
     * @param Request $request
     * @param Response $response
     * @return bool|Response
     */
    public static function admin(Request $request, Response $response) {
        // Primeiro verificar autenticação
        $authResult = self::auth($request, $response);
        if ($authResult !== true) {
            return $authResult;
        }
        
        // Verificar se é administrador
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
            if ($request->isAjax() || strpos($request->getPath(), '/api/') === 0) {
                return $response->json([
                    'error' => true,
                    'message' => 'Acesso negado. Privilégios de administrador necessários.',
                    'code' => 403
                ], 403);
            }
            
            return $response->redirect('/?error=access_denied', 302);
        }
        
        return true;
    }
    
    /**
     * Middleware de proteção CSRF
     * Verifica token CSRF em requisições POST/PUT/DELETE
     * 
     * @param Request $request
     * @param Response $response
     * @return bool|Response
     */
    public static function csrf(Request $request, Response $response) {
        // Iniciar sessão se não estiver iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Gerar token CSRF se não existir
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        // Verificar apenas em métodos que modificam dados
        $methodsToCheck = ['POST', 'PUT', 'DELETE', 'PATCH'];
        if (!in_array($request->getMethod(), $methodsToCheck)) {
            return true;
        }
        
        // Obter token da requisição
        $token = $request->getBodyParam('_token') ?? 
                $request->getHeader('x-csrf-token') ?? 
                $request->getHeader('x-xsrf-token');
        
        // Verificar se token é válido
        if (!$token || !hash_equals($_SESSION['csrf_token'], $token)) {
            if ($request->isAjax() || strpos($request->getPath(), '/api/') === 0) {
                return $response->json([
                    'error' => true,
                    'message' => 'Token CSRF inválido ou ausente',
                    'code' => 419
                ], 419);
            }
            
            return $response->redirect('/?error=csrf_token_mismatch', 302);
        }
        
        return true;
    }
    
    /**
     * Middleware de rate limiting
     * Limita número de requisições por IP
     * 
     * @param Request $request
     * @param Response $response
     * @param int $maxRequests Máximo de requisições por minuto
     * @return bool|Response
     */
    public static function rateLimit(Request $request, Response $response, int $maxRequests = 60) {
        // Iniciar sessão se não estiver iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $ip = $request->getClientIp();
        $key = 'rate_limit_' . $ip;
        
        // Obter histórico de requisições
        $requests = $_SESSION[$key] ?? [];
        $now = time();
        
        // Remover requisições antigas (mais de 1 minuto)
        $requests = array_filter($requests, function($timestamp) use ($now) {
            return ($now - $timestamp) < 60;
        });
        
        // Verificar se excedeu o limite
        if (count($requests) >= $maxRequests) {
            if ($request->isAjax() || strpos($request->getPath(), '/api/') === 0) {
                return $response->json([
                    'error' => true,
                    'message' => 'Muitas requisições. Tente novamente em alguns minutos.',
                    'code' => 429,
                    'retry_after' => 60
                ], 429);
            }
            
            return $response->redirect('/?error=rate_limit_exceeded', 302);
        }
        
        // Adicionar requisição atual
        $requests[] = $now;
        $_SESSION[$key] = $requests;
        
        return true;
    }
    
    /**
     * Middleware de validação de entrada
     * Sanitiza e valida dados de entrada
     * 
     * @param Request $request
     * @param Response $response
     * @return bool|Response
     */
    public static function sanitizeInput(Request $request, Response $response) {
        // Sanitizar dados GET
        foreach ($_GET as $key => $value) {
            $_GET[$key] = self::sanitizeValue($value);
        }
        
        // Sanitizar dados POST
        foreach ($_POST as $key => $value) {
            $_POST[$key] = self::sanitizeValue($value);
        }
        
        return true;
    }
    
    /**
     * Middleware de segurança de cabeçalhos
     * Adiciona cabeçalhos de segurança
     * 
     * @param Request $request
     * @param Response $response
     * @return bool
     */
    public static function securityHeaders(Request $request, Response $response) {
        // Cabeçalhos de segurança
        $response->setHeader('X-Content-Type-Options', 'nosniff');
        $response->setHeader('X-Frame-Options', 'DENY');
        $response->setHeader('X-XSS-Protection', '1; mode=block');
        $response->setHeader('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->setHeader('Content-Security-Policy', "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval'; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' https:;");
        
        // HSTS apenas em HTTPS
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $response->setHeader('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
        }
        
        return true;
    }
    
    /**
     * Middleware de CORS
     * Configura cabeçalhos CORS para APIs
     * 
     * @param Request $request
     * @param Response $response
     * @return bool|Response
     */
    public static function cors(Request $request, Response $response) {
        // Domínios permitidos (configurar conforme necessário)
        $allowedOrigins = [
            'http://localhost:3000',
            'http://localhost:8000',
            'https://neonshop.com'
        ];
        
        $origin = $request->getHeader('origin');
        
        if ($origin && in_array($origin, $allowedOrigins)) {
            $response->setHeader('Access-Control-Allow-Origin', $origin);
        } else {
            $response->setHeader('Access-Control-Allow-Origin', '*');
        }
        
        $response->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
        $response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With, X-CSRF-Token');
        $response->setHeader('Access-Control-Allow-Credentials', 'true');
        $response->setHeader('Access-Control-Max-Age', '86400');
        
        // Tratar requisições OPTIONS (preflight)
        if ($request->getMethod() === 'OPTIONS') {
            return $response->json(['status' => 'ok'], 200);
        }
        
        return true;
    }
    
    /**
     * Middleware de log de requisições
     * Registra todas as requisições para auditoria
     * 
     * @param Request $request
     * @param Response $response
     * @return bool
     */
    public static function logRequests(Request $request, Response $response) {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'ip' => $request->getClientIp(),
            'method' => $request->getMethod(),
            'uri' => $request->getUri(),
            'user_agent' => $request->getHeader('user-agent'),
            'user_id' => $_SESSION['user_id'] ?? null
        ];
        
        // Log em arquivo (em produção, usar sistema de log mais robusto)
        $logFile = __DIR__ . '/../../storage/logs/requests.log';
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        file_put_contents($logFile, json_encode($logData) . "\n", FILE_APPEND | LOCK_EX);
        
        return true;
    }
    
    /**
     * Middleware de manutenção
     * Bloqueia acesso durante manutenção
     * 
     * @param Request $request
     * @param Response $response
     * @return bool|Response
     */
    public static function maintenance(Request $request, Response $response) {
        $maintenanceFile = __DIR__ . '/../../storage/maintenance.flag';
        
        if (file_exists($maintenanceFile)) {
            // Permitir acesso para administradores
            if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
                return true;
            }
            
            // Permitir acesso a IPs específicos (opcional)
            $allowedIps = ['127.0.0.1', '::1'];
            if (in_array($request->getClientIp(), $allowedIps)) {
                return true;
            }
            
            if ($request->isAjax() || strpos($request->getPath(), '/api/') === 0) {
                return $response->json([
                    'error' => true,
                    'message' => 'Sistema em manutenção. Tente novamente em alguns minutos.',
                    'code' => 503
                ], 503);
            }
            
            // Retornar página de manutenção
            $maintenanceHtml = file_get_contents(__DIR__ . '/../../resources/views/errors/maintenance.php');
            return $response->html($maintenanceHtml, 503);
        }
        
        return true;
    }
    
    /**
     * Middleware de validação de API
     * Valida formato e conteúdo de requisições API
     * 
     * @param Request $request
     * @param Response $response
     * @return bool|Response
     */
    public static function validateApi(Request $request, Response $response) {
        // Verificar se é requisição para API
        if (strpos($request->getPath(), '/api/') !== 0) {
            return true;
        }
        
        // Verificar Content-Type para requisições POST/PUT
        $methodsWithBody = ['POST', 'PUT', 'PATCH'];
        if (in_array($request->getMethod(), $methodsWithBody)) {
            $contentType = $request->getHeader('content-type');
            
            if (!$contentType || (
                strpos($contentType, 'application/json') === false &&
                strpos($contentType, 'application/x-www-form-urlencoded') === false &&
                strpos($contentType, 'multipart/form-data') === false
            )) {
                return $response->json([
                    'error' => true,
                    'message' => 'Content-Type inválido. Use application/json ou application/x-www-form-urlencoded',
                    'code' => 400
                ], 400);
            }
        }
        
        return true;
    }
    
    /**
     * Sanitiza um valor removendo caracteres perigosos
     * 
     * @param mixed $value Valor a ser sanitizado
     * @return mixed Valor sanitizado
     */
    private static function sanitizeValue($value) {
        if (is_array($value)) {
            return array_map([self::class, 'sanitizeValue'], $value);
        }
        
        if (is_string($value)) {
            // Remover tags HTML perigosas
            $value = strip_tags($value);
            
            // Escapar caracteres especiais
            $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            
            // Remover caracteres de controle
            $value = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $value);
        }
        
        return $value;
    }
    
    /**
     * Verifica se um IP está em uma lista de IPs bloqueados
     * 
     * @param string $ip IP a verificar
     * @return bool
     */
    private static function isBlockedIp(string $ip): bool {
        $blockedIps = [
            // Adicionar IPs bloqueados aqui
        ];
        
        return in_array($ip, $blockedIps);
    }
    
    /**
     * Middleware personalizado para bloquear IPs
     * 
     * @param Request $request
     * @param Response $response
     * @return bool|Response
     */
    public static function blockIps(Request $request, Response $response) {
        $ip = $request->getClientIp();
        
        if (self::isBlockedIp($ip)) {
            if ($request->isAjax() || strpos($request->getPath(), '/api/') === 0) {
                return $response->json([
                    'error' => true,
                    'message' => 'Acesso negado',
                    'code' => 403
                ], 403);
            }
            
            return $response->redirect('/', 302);
        }
        
        return true;
    }
}

/**
 * Interface para middlewares personalizados
 */
interface MiddlewareInterface {
    /**
     * Processa a requisição através do middleware
     * 
     * @param Request $request
     * @param Response $response
     * @return bool|Response
     */
    public function handle(Request $request, Response $response);
}

/**
 * Classe base para middlewares personalizados
 */
abstract class BaseMiddleware implements MiddlewareInterface {
    /**
     * Executa o middleware
     * 
     * @param Request $request
     * @param Response $response
     * @return bool|Response
     */
    abstract public function handle(Request $request, Response $response);
    
    /**
     * Verifica se a requisição deve ser processada pelo middleware
     * 
     * @param Request $request
     * @return bool
     */
    protected function shouldProcess(Request $request): bool {
        return true;
    }
    
    /**
     * Registra um erro de middleware
     * 
     * @param string $message
     * @param Request $request
     */
    protected function logError(string $message, Request $request): void {
        error_log(sprintf(
            '[Middleware Error] %s - IP: %s, URI: %s, Method: %s',
            $message,
            $request->getClientIp(),
            $request->getUri(),
            $request->getMethod()
        ));
    }
}

/**
 * Funções auxiliares para middlewares
 */

/**
 * Gera token CSRF
 * 
 * @return string
 */
function csrf_token(): string {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * Gera campo hidden com token CSRF
 * 
 * @return string
 */
function csrf_field(): string {
    return '<input type="hidden" name="_token" value="' . csrf_token() . '">';
}

/**
 * Verifica se usuário está autenticado
 * 
 * @return bool
 */
function is_authenticated(): bool {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

/**
 * Verifica se usuário é administrador
 * 
 * @return bool
 */
function is_admin(): bool {
    if (!is_authenticated()) {
        return false;
    }
    
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

/**
 * Obtém ID do usuário atual
 * 
 * @return int|null
 */
function current_user_id(): ?int {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    return $_SESSION['user_id'] ?? null;
}

/**
 * Obtém dados do usuário atual
 * 
 * @return array|null
 */
function current_user(): ?array {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    
    return [
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['user_name'] ?? '',
        'email' => $_SESSION['user_email'] ?? '',
        'role' => $_SESSION['user_role'] ?? 'user'
    ];
}