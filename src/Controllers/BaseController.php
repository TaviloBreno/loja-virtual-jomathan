<?php

namespace Controllers;

use Core\Request;
use Core\Response;

/**
 * Controlador Base - NeonShop
 * 
 * Classe base para todos os controladores do sistema.
 * Fornece funcionalidades comuns como renderização de views,
 * validação, autenticação e tratamento de erros.
 */
abstract class BaseController {
    
    protected Request $request;
    protected Response $response;
    protected array $data = [];
    protected array $errors = [];
    
    /**
     * Construtor do controlador base
     * 
     * @param Request $request
     * @param Response $response
     */
    public function __construct(Request $request, Response $response) {
        $this->request = $request;
        $this->response = $response;
        
        // Inicializar sessão se necessário
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Dados globais disponíveis em todas as views
        $this->data = [
            'site_name' => 'NeonShop',
            'site_description' => 'Sua loja online com estilo futurista',
            'current_user' => $this->getCurrentUser(),
            'cart_count' => $this->getCartCount(),
            'csrf_token' => $this->getCsrfToken(),
            'current_url' => $this->request->getUri(),
            'base_url' => $this->getBaseUrl()
        ];
    }
    
    /**
     * Renderiza uma view com os dados fornecidos
     * 
     * @param string $view Nome da view
     * @param array $data Dados para a view
     * @param int $statusCode Código de status HTTP
     * @return Response
     */
    protected function view(string $view, array $data = [], int $statusCode = 200): Response {
        // Mesclar dados globais com dados específicos da view
        $viewData = array_merge($this->data, $data);
        
        // Caminho da view
        $viewPath = $this->getViewPath($view);
        
        if (!file_exists($viewPath)) {
            return $this->error404("View não encontrada: {$view}");
        }
        
        // Extrair variáveis para o escopo da view
        extract($viewData);
        
        // Capturar output da view
        ob_start();
        include $viewPath;
        $content = ob_get_clean();
        
        return $this->response->html($content, $statusCode);
    }
    
    /**
     * Retorna resposta JSON
     * 
     * @param array $data Dados para JSON
     * @param int $statusCode Código de status HTTP
     * @return Response
     */
    protected function json(array $data, int $statusCode = 200): Response {
        return $this->response->json($data, $statusCode);
    }
    
    /**
     * Retorna resposta de sucesso JSON
     * 
     * @param mixed $data Dados de resposta
     * @param string $message Mensagem de sucesso
     * @return Response
     */
    protected function success($data = null, string $message = 'Operação realizada com sucesso'): Response {
        $response = [
            'success' => true,
            'message' => $message
        ];
        
        if ($data !== null) {
            $response['data'] = $data;
        }
        
        return $this->json($response);
    }
    
    /**
     * Retorna resposta de erro JSON
     * 
     * @param string $message Mensagem de erro
     * @param int $statusCode Código de status HTTP
     * @param array $errors Erros específicos
     * @return Response
     */
    protected function error(string $message, int $statusCode = 400, array $errors = []): Response {
        $response = [
            'error' => true,
            'message' => $message,
            'code' => $statusCode
        ];
        
        if (!empty($errors)) {
            $response['errors'] = $errors;
        }
        
        return $this->json($response, $statusCode);
    }
    
    /**
     * Redireciona para uma URL
     * 
     * @param string $url URL de destino
     * @param int $statusCode Código de status HTTP
     * @return Response
     */
    protected function redirect(string $url, int $statusCode = 302): Response {
        return $this->response->redirect($url, $statusCode);
    }
    
    /**
     * Redireciona de volta com mensagem
     * 
     * @param string $message Mensagem
     * @param string $type Tipo da mensagem (success, error, warning, info)
     * @return Response
     */
    protected function back(string $message = '', string $type = 'info'): Response {
        if ($message) {
            $_SESSION['flash_message'] = $message;
            $_SESSION['flash_type'] = $type;
        }
        
        $referer = $this->request->getHeader('referer') ?? '/';
        return $this->redirect($referer);
    }
    
    /**
     * Valida dados de entrada
     * 
     * @param array $data Dados para validar
     * @param array $rules Regras de validação
     * @return bool
     */
    protected function validate(array $data, array $rules): bool {
        $this->errors = [];
        
        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? null;
            $fieldRules = is_string($fieldRules) ? explode('|', $fieldRules) : $fieldRules;
            
            foreach ($fieldRules as $rule) {
                if (!$this->validateField($field, $value, $rule)) {
                    break; // Para no primeiro erro do campo
                }
            }
        }
        
        return empty($this->errors);
    }
    
    /**
     * Valida um campo específico
     * 
     * @param string $field Nome do campo
     * @param mixed $value Valor do campo
     * @param string $rule Regra de validação
     * @return bool
     */
    private function validateField(string $field, $value, string $rule): bool {
        $ruleParts = explode(':', $rule);
        $ruleName = $ruleParts[0];
        $ruleParam = $ruleParts[1] ?? null;
        
        switch ($ruleName) {
            case 'required':
                if (empty($value) && $value !== '0') {
                    $this->errors[$field] = "O campo {$field} é obrigatório";
                    return false;
                }
                break;
                
            case 'email':
                if ($value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$field] = "O campo {$field} deve ser um email válido";
                    return false;
                }
                break;
                
            case 'min':
                if ($value && strlen($value) < (int)$ruleParam) {
                    $this->errors[$field] = "O campo {$field} deve ter pelo menos {$ruleParam} caracteres";
                    return false;
                }
                break;
                
            case 'max':
                if ($value && strlen($value) > (int)$ruleParam) {
                    $this->errors[$field] = "O campo {$field} deve ter no máximo {$ruleParam} caracteres";
                    return false;
                }
                break;
                
            case 'numeric':
                if ($value && !is_numeric($value)) {
                    $this->errors[$field] = "O campo {$field} deve ser numérico";
                    return false;
                }
                break;
                
            case 'url':
                if ($value && !filter_var($value, FILTER_VALIDATE_URL)) {
                    $this->errors[$field] = "O campo {$field} deve ser uma URL válida";
                    return false;
                }
                break;
                
            case 'confirmed':
                $confirmField = $field . '_confirmation';
                if ($value !== ($_POST[$confirmField] ?? null)) {
                    $this->errors[$field] = "A confirmação do campo {$field} não confere";
                    return false;
                }
                break;
        }
        
        return true;
    }
    
    /**
     * Verifica se usuário está autenticado
     * 
     * @return bool
     */
    protected function isAuthenticated(): bool {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }
    
    /**
     * Verifica se usuário é administrador
     * 
     * @return bool
     */
    protected function isAdmin(): bool {
        return $this->isAuthenticated() && 
               isset($_SESSION['user_role']) && 
               $_SESSION['user_role'] === 'admin';
    }
    
    /**
     * Requer autenticação
     * 
     * @return Response|null
     */
    protected function requireAuth(): ?Response {
        if (!$this->isAuthenticated()) {
            if ($this->request->isAjax()) {
                return $this->error('Usuário não autenticado', 401);
            }
            
            $_SESSION['redirect_after_login'] = $this->request->getUri();
            return $this->redirect('/login');
        }
        
        return null;
    }
    
    /**
     * Requer privilégios de administrador
     * 
     * @return Response|null
     */
    protected function requireAdmin(): ?Response {
        $authCheck = $this->requireAuth();
        if ($authCheck) {
            return $authCheck;
        }
        
        if (!$this->isAdmin()) {
            if ($this->request->isAjax()) {
                return $this->error('Acesso negado', 403);
            }
            
            return $this->redirect('/?error=access_denied');
        }
        
        return null;
    }
    
    /**
     * Retorna erro 404
     * 
     * @param string $message Mensagem de erro
     * @return Response
     */
    protected function error404(string $message = 'Página não encontrada'): Response {
        if ($this->request->isAjax()) {
            return $this->error($message, 404);
        }
        
        return $this->view('errors/404', ['message' => $message], 404);
    }
    
    /**
     * Retorna erro 500
     * 
     * @param string $message Mensagem de erro
     * @return Response
     */
    protected function error500(string $message = 'Erro interno do servidor'): Response {
        if ($this->request->isAjax()) {
            return $this->error($message, 500);
        }
        
        return $this->view('errors/500', ['message' => $message], 500);
    }
    
    /**
     * Obtém dados do usuário atual
     * 
     * @return array|null
     */
    private function getCurrentUser(): ?array {
        if (!$this->isAuthenticated()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'] ?? '',
            'email' => $_SESSION['user_email'] ?? '',
            'role' => $_SESSION['user_role'] ?? 'user'
        ];
    }
    
    /**
     * Obtém quantidade de itens no carrinho
     * 
     * @return int
     */
    private function getCartCount(): int {
        $cart = $_SESSION['cart'] ?? [];
        return array_sum(array_column($cart, 'quantity'));
    }
    
    /**
     * Obtém token CSRF
     * 
     * @return string
     */
    private function getCsrfToken(): string {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION['csrf_token'];
    }
    
    /**
     * Obtém URL base do site
     * 
     * @return string
     */
    private function getBaseUrl(): string {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $protocol . '://' . $host;
    }
    
    /**
     * Obtém caminho completo da view
     * 
     * @param string $view Nome da view
     * @return string
     */
    private function getViewPath(string $view): string {
        $basePath = __DIR__ . '/../../resources/views/';
        
        // Se não tem extensão, adicionar .php
        if (pathinfo($view, PATHINFO_EXTENSION) === '') {
            $view .= '.php';
        }
        
        return $basePath . $view;
    }
    
    /**
     * Sanitiza dados de entrada
     * 
     * @param mixed $data Dados para sanitizar
     * @return mixed
     */
    protected function sanitize($data) {
        if (is_array($data)) {
            return array_map([$this, 'sanitize'], $data);
        }
        
        if (is_string($data)) {
            return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
        }
        
        return $data;
    }
    
    /**
     * Registra log de ação
     * 
     * @param string $action Ação realizada
     * @param array $data Dados da ação
     */
    protected function logAction(string $action, array $data = []): void {
        $logData = [
            'timestamp' => date('Y-m-d H:i:s'),
            'user_id' => $_SESSION['user_id'] ?? null,
            'ip' => $this->request->getClientIp(),
            'action' => $action,
            'data' => $data,
            'uri' => $this->request->getUri()
        ];
        
        $logFile = __DIR__ . '/../../storage/logs/actions.log';
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        file_put_contents($logFile, json_encode($logData) . "\n", FILE_APPEND | LOCK_EX);
    }
    
    /**
     * Paginação de resultados
     * 
     * @param array $items Itens para paginar
     * @param int $perPage Itens por página
     * @param int $currentPage Página atual
     * @return array
     */
    protected function paginate(array $items, int $perPage = 12, int $currentPage = 1): array {
        $total = count($items);
        $totalPages = ceil($total / $perPage);
        $offset = ($currentPage - 1) * $perPage;
        
        $paginatedItems = array_slice($items, $offset, $perPage);
        
        return [
            'items' => $paginatedItems,
            'pagination' => [
                'current_page' => $currentPage,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => $totalPages,
                'has_prev' => $currentPage > 1,
                'has_next' => $currentPage < $totalPages,
                'prev_page' => $currentPage > 1 ? $currentPage - 1 : null,
                'next_page' => $currentPage < $totalPages ? $currentPage + 1 : null
            ]
        ];
    }
    
    /**
     * Formata preço para exibição
     * 
     * @param float $price Preço
     * @return string
     */
    protected function formatPrice(float $price): string {
        return 'R$ ' . number_format($price, 2, ',', '.');
    }
    
    /**
     * Gera slug a partir de string
     * 
     * @param string $text Texto para converter
     * @return string
     */
    protected function generateSlug(string $text): string {
        // Converter para minúsculas
        $text = strtolower($text);
        
        // Remover acentos
        $text = iconv('UTF-8', 'ASCII//TRANSLIT', $text);
        
        // Remover caracteres especiais
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        
        // Substituir espaços e múltiplos hífens por um hífen
        $text = preg_replace('/[\s-]+/', '-', $text);
        
        // Remover hífens do início e fim
        return trim($text, '-');
    }
    
    /**
     * Obtém mensagem flash da sessão
     * 
     * @return array|null
     */
    protected function getFlashMessage(): ?array {
        if (isset($_SESSION['flash_message'])) {
            $message = [
                'text' => $_SESSION['flash_message'],
                'type' => $_SESSION['flash_type'] ?? 'info'
            ];
            
            unset($_SESSION['flash_message'], $_SESSION['flash_type']);
            
            return $message;
        }
        
        return null;
    }
    
    /**
     * Define mensagem flash na sessão
     * 
     * @param string $message Mensagem
     * @param string $type Tipo da mensagem
     */
    protected function setFlashMessage(string $message, string $type = 'info'): void {
        $_SESSION['flash_message'] = $message;
        $_SESSION['flash_type'] = $type;
    }
}