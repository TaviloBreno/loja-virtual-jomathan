<?php

/**
 * Rotas de API - NeonShop E-commerce
 * 
 * Este arquivo define todas as rotas de API da aplicação para requisições Ajax/JSON
 * Todas as rotas retornam dados em formato JSON
 */

use App\Infrastructure\Http\Router;

// Obtém a instância do router
/** @var Router $router */

// ============================================================================
// API DE BUSCA E FILTROS
// ============================================================================

// Busca geral de produtos
$router->get('/api/buscar', 'ApiController@search');

// Filtros de produtos
$router->get('/api/produtos/filtros', 'ApiController@productFilters');
$router->get('/api/produtos/ordenacao', 'ApiController@sortOptions');
$router->get('/api/produtos/faixa-preco', 'ApiController@priceRange');

// Categorias e marcas
$router->get('/api/categorias', 'ApiController@categories');
$router->get('/api/categorias/{id}/subcategorias', 'ApiController@subcategories');
$router->get('/api/marcas', 'ApiController@brands');
$router->get('/api/marcas/populares', 'ApiController@popularBrands');

// ============================================================================
// API DE PRODUTOS
// ============================================================================

// Informações do produto
$router->get('/api/produto/{id}', 'ApiController@product');
$router->get('/api/produto/{id}/detalhes', 'ApiController@productDetails');
$router->get('/api/produto/{id}/especificacoes', 'ApiController@productSpecs');
$router->get('/api/produto/{id}/imagens', 'ApiController@productImages');

// Estoque e disponibilidade
$router->get('/api/produto/{id}/estoque', 'ApiController@productStock');
$router->get('/api/produto/{id}/disponibilidade', 'ApiController@productAvailability');
$router->post('/api/produto/{id}/notificar-estoque', 'ApiController@notifyStock');

// Variações do produto
$router->get('/api/produto/{id}/variacoes', 'ApiController@productVariations');
$router->get('/api/produto/{id}/variacao/{variationId}', 'ApiController@productVariation');

// Avaliações e comentários
$router->get('/api/produto/{id}/avaliacoes', 'ApiController@productReviews');
$router->post('/api/produto/{id}/avaliar', 'ApiController@addReview');
$router->get('/api/produto/{id}/avaliacoes/estatisticas', 'ApiController@reviewStats');
$router->post('/api/avaliacao/{id}/util', 'ApiController@markReviewHelpful');

// Produtos relacionados
$router->get('/api/produto/{id}/relacionados', 'ApiController@relatedProducts');
$router->get('/api/produto/{id}/similares', 'ApiController@similarProducts');
$router->get('/api/produto/{id}/acessorios', 'ApiController@productAccessories');

// ============================================================================
// API DE CEP E FRETE
// ============================================================================

// Consulta de CEP
$router->get('/api/cep/{cep}', 'ApiController@addressByCep');
$router->post('/api/cep/validar', 'ApiController@validateCep');

// Cálculo de frete
$router->post('/api/frete/calcular', 'ApiController@calculateShipping');
$router->get('/api/frete/opcoes', 'ApiController@shippingOptions');
$router->post('/api/frete/prazo', 'ApiController@shippingDeadline');
$router->get('/api/frete/transportadoras', 'ApiController@shippingCarriers');

// Rastreamento
$router->get('/api/rastreamento/{codigo}', 'ApiController@trackOrder');
$router->post('/api/rastreamento/webhook', 'ApiController@trackingWebhook');

// ============================================================================
// API DE CARRINHO
// ============================================================================

// Conteúdo do carrinho
$router->get('/api/carrinho', 'ApiController@cartContents');
$router->get('/api/carrinho/resumo', 'ApiController@cartSummary');
$router->get('/api/carrinho/contador', 'ApiController@cartCount');

// Manipulação do carrinho
$router->post('/api/carrinho/adicionar', 'ApiController@addToCart');
$router->put('/api/carrinho/atualizar', 'ApiController@updateCart');
$router->delete('/api/carrinho/remover/{id}', 'ApiController@removeFromCart');
$router->delete('/api/carrinho/limpar', 'ApiController@clearCart');

// Validações do carrinho
$router->post('/api/carrinho/validar', 'ApiController@validateCart');
$router->get('/api/carrinho/disponibilidade', 'ApiController@checkCartAvailability');

// Carrinho abandonado
$router->post('/api/carrinho/salvar', 'ApiController@saveCart');
$router->get('/api/carrinho/recuperar', 'ApiController@recoverCart');

// ============================================================================
// API DE CUPONS E PROMOÇÕES
// ============================================================================

// Validação de cupons
$router->post('/api/cupom/validar', 'ApiController@validateCoupon');
$router->post('/api/cupom/aplicar', 'ApiController@applyCoupon');
$router->delete('/api/cupom/remover', 'ApiController@removeCoupon');

// Cupons disponíveis
$router->get('/api/cupons/disponiveis', 'ApiController@availableCoupons');
$router->get('/api/cupons/categoria/{id}', 'ApiController@categoryCoupons');
$router->get('/api/cupons/produto/{id}', 'ApiController@productCoupons');

// Promoções
$router->get('/api/promocoes/ativas', 'ApiController@activePromotions');
$router->get('/api/promocoes/categoria/{id}', 'ApiController@categoryPromotions');
$router->get('/api/promocoes/flash', 'ApiController@flashSales');

// ============================================================================
// API DE PAGAMENTO
// ============================================================================

// Processamento de pagamento
$router->post('/api/pagamento/processar', 'ApiController@processPayment');
$router->get('/api/pagamento/status/{id}', 'ApiController@paymentStatus');
$router->post('/api/pagamento/confirmar', 'ApiController@confirmPayment');

// Métodos de pagamento
$router->get('/api/pagamento/metodos', 'ApiController@paymentMethods');
$router->get('/api/pagamento/cartao/bandeiras', 'ApiController@cardBrands');
$router->post('/api/pagamento/cartao/validar', 'ApiController@validateCard');

// PIX
$router->post('/api/pagamento/pix/gerar', 'ApiController@generatePix');
$router->get('/api/pagamento/pix/{id}/qrcode', 'ApiController@pixQrCode');
$router->get('/api/pagamento/pix/{id}/status', 'ApiController@pixStatus');

// Boleto
$router->post('/api/pagamento/boleto/gerar', 'ApiController@generateBoleto');
$router->get('/api/pagamento/boleto/{id}', 'ApiController@boletoDetails');
$router->get('/api/pagamento/boleto/{id}/pdf', 'ApiController@boletoPdf');

// Webhooks de pagamento
$router->post('/api/pagamento/webhook/mercadopago', 'ApiController@mercadoPagoWebhook');
$router->post('/api/pagamento/webhook/pagseguro', 'ApiController@pagSeguroWebhook');
$router->post('/api/pagamento/webhook/paypal', 'ApiController@paypalWebhook');

// ============================================================================
// API DE USUÁRIO
// ============================================================================

// Perfil do usuário
$router->get('/api/usuario/perfil', 'ApiController@userProfile');
$router->put('/api/usuario/perfil', 'ApiController@updateProfile');
$router->post('/api/usuario/avatar', 'ApiController@uploadAvatar');

// Pedidos do usuário
$router->get('/api/usuario/pedidos', 'ApiController@userOrders');
$router->get('/api/usuario/pedido/{id}', 'ApiController@userOrderDetails');
$router->post('/api/usuario/pedido/{id}/cancelar', 'ApiController@cancelOrder');
$router->post('/api/usuario/pedido/{id}/avaliar', 'ApiController@rateOrder');

// Favoritos
$router->get('/api/usuario/favoritos', 'ApiController@userFavorites');
$router->post('/api/usuario/favoritos/adicionar', 'ApiController@addFavorite');
$router->delete('/api/usuario/favoritos/remover/{id}', 'ApiController@removeFavorite');
$router->get('/api/usuario/favoritos/verificar/{id}', 'ApiController@checkFavorite');

// Endereços
$router->get('/api/usuario/enderecos', 'ApiController@userAddresses');
$router->post('/api/usuario/endereco', 'ApiController@addUserAddress');
$router->put('/api/usuario/endereco/{id}', 'ApiController@updateUserAddress');
$router->delete('/api/usuario/endereco/{id}', 'ApiController@deleteUserAddress');
$router->post('/api/usuario/endereco/{id}/principal', 'ApiController@setMainAddress');

// Lista de desejos
$router->get('/api/usuario/lista-desejos', 'ApiController@wishlist');
$router->post('/api/usuario/lista-desejos/adicionar', 'ApiController@addToWishlist');
$router->delete('/api/usuario/lista-desejos/remover/{id}', 'ApiController@removeFromWishlist');

// ============================================================================
// API DE NOTIFICAÇÕES
// ============================================================================

// Notificações do usuário
$router->get('/api/notificacoes', 'ApiController@notifications');
$router->get('/api/notificacoes/nao-lidas', 'ApiController@unreadNotifications');
$router->post('/api/notificacoes/marcar-lida/{id}', 'ApiController@markNotificationRead');
$router->post('/api/notificacoes/marcar-todas-lidas', 'ApiController@markAllNotificationsRead');
$router->delete('/api/notificacoes/{id}', 'ApiController@deleteNotification');

// Preferências de notificação
$router->get('/api/usuario/notificacoes/preferencias', 'ApiController@notificationPreferences');
$router->put('/api/usuario/notificacoes/preferencias', 'ApiController@updateNotificationPreferences');

// ============================================================================
// API DE NEWSLETTER E MARKETING
// ============================================================================

// Newsletter
$router->post('/api/newsletter/inscrever', 'ApiController@subscribeNewsletter');
$router->post('/api/newsletter/desinscrever', 'ApiController@unsubscribeNewsletter');
$router->get('/api/newsletter/status/{email}', 'ApiController@newsletterStatus');

// Campanhas de marketing
$router->get('/api/campanhas/ativas', 'ApiController@activeCampaigns');
$router->post('/api/campanha/{id}/participar', 'ApiController@joinCampaign');

// ============================================================================
// API DE SUPORTE E ATENDIMENTO
// ============================================================================

// Chat de suporte
$router->get('/api/suporte/chat/status', 'ApiController@chatStatus');
$router->post('/api/suporte/chat/iniciar', 'ApiController@startChat');
$router->post('/api/suporte/chat/mensagem', 'ApiController@sendChatMessage');
$router->get('/api/suporte/chat/{id}/historico', 'ApiController@chatHistory');

// Tickets de suporte
$router->get('/api/suporte/tickets', 'ApiController@supportTickets');
$router->post('/api/suporte/ticket', 'ApiController@createSupportTicket');
$router->get('/api/suporte/ticket/{id}', 'ApiController@supportTicketDetails');
$router->post('/api/suporte/ticket/{id}/resposta', 'ApiController@replyToTicket');

// FAQ
$router->get('/api/faq/categorias', 'ApiController@faqCategories');
$router->get('/api/faq/categoria/{id}', 'ApiController@faqByCategory');
$router->get('/api/faq/buscar', 'ApiController@searchFaq');
$router->get('/api/faq/populares', 'ApiController@popularFaq');

// ============================================================================
// API DE ANALYTICS E TRACKING
// ============================================================================

// Eventos de tracking
$router->post('/api/tracking/evento', 'ApiController@trackEvent');
$router->post('/api/tracking/visualizacao-produto', 'ApiController@trackProductView');
$router->post('/api/tracking/adicionar-carrinho', 'ApiController@trackAddToCart');
$router->post('/api/tracking/iniciar-checkout', 'ApiController@trackCheckoutStart');
$router->post('/api/tracking/compra', 'ApiController@trackPurchase');

// Recomendações
$router->get('/api/recomendacoes/produtos', 'ApiController@recommendedProducts');
$router->get('/api/recomendacoes/usuario', 'ApiController@personalizedRecommendations');
$router->get('/api/recomendacoes/categoria/{id}', 'ApiController@categoryRecommendations');

// ============================================================================
// API DE SISTEMA
// ============================================================================

// Status do sistema
$router->get('/api/sistema/status', 'ApiController@systemStatus');
$router->get('/api/sistema/configuracoes', 'ApiController@systemConfig');
$router->get('/api/sistema/manutencao', 'ApiController@maintenanceStatus');

// Upload de arquivos
$router->post('/api/upload/imagem', 'ApiController@uploadImage');
$router->post('/api/upload/documento', 'ApiController@uploadDocument');
$router->delete('/api/upload/{id}', 'ApiController@deleteUpload');

// Cache
$router->post('/api/cache/limpar', 'ApiController@clearCache');
$router->get('/api/cache/status', 'ApiController@cacheStatus');

// ============================================================================
// MIDDLEWARE DE RESPOSTA JSON
// ============================================================================

/**
 * Middleware para garantir que todas as respostas da API sejam JSON
 */
$router->addMiddleware('/api/*', function($request, $response, $next) {
    // Definir cabeçalho de resposta JSON
    $response->setHeader('Content-Type', 'application/json; charset=utf-8');
    $response->setHeader('Access-Control-Allow-Origin', '*');
    $response->setHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
    $response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization, X-Requested-With');
    
    // Tratar requisições OPTIONS (CORS preflight)
    if ($request->getMethod() === 'OPTIONS') {
        return $response->json(['status' => 'ok'], 200);
    }
    
    try {
        return $next($request, $response);
    } catch (Exception $e) {
        // Log do erro
        error_log('API Error: ' . $e->getMessage());
        
        // Retornar erro em formato JSON
        return $response->json([
            'error' => true,
            'message' => $e->getMessage(),
            'code' => $e->getCode() ?: 500
        ], $e->getCode() ?: 500);
    }
});

/**
 * Middleware de rate limiting para APIs
 */
$router->addMiddleware('/api/*', function($request, $response, $next) {
    $ip = $request->getClientIp();
    $key = 'rate_limit_' . $ip;
    
    // Verificar rate limit (100 requisições por minuto)
    $requests = $_SESSION[$key] ?? [];
    $now = time();
    
    // Remover requisições antigas (mais de 1 minuto)
    $requests = array_filter($requests, function($timestamp) use ($now) {
        return ($now - $timestamp) < 60;
    });
    
    // Verificar se excedeu o limite
    if (count($requests) >= 100) {
        return $response->json([
            'error' => true,
            'message' => 'Rate limit exceeded. Try again later.',
            'code' => 429
        ], 429);
    }
    
    // Adicionar requisição atual
    $requests[] = $now;
    $_SESSION[$key] = $requests;
    
    return $next($request, $response);
});

/**
 * Funções auxiliares para API
 */

/**
 * Retorna resposta de sucesso padronizada
 */
function api_success($data = null, $message = 'Success', $code = 200) {
    return [
        'success' => true,
        'message' => $message,
        'data' => $data,
        'timestamp' => date('c')
    ];
}

/**
 * Retorna resposta de erro padronizada
 */
function api_error($message = 'Error', $code = 400, $details = null) {
    return [
        'success' => false,
        'error' => true,
        'message' => $message,
        'code' => $code,
        'details' => $details,
        'timestamp' => date('c')
    ];
}

/**
 * Valida dados de entrada da API
 */
function validate_api_input($data, $rules) {
    $errors = [];
    
    foreach ($rules as $field => $rule) {
        $value = $data[$field] ?? null;
        
        if (strpos($rule, 'required') !== false && empty($value)) {
            $errors[$field] = "O campo {$field} é obrigatório";
            continue;
        }
        
        if (strpos($rule, 'email') !== false && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $errors[$field] = "O campo {$field} deve ser um email válido";
        }
        
        if (strpos($rule, 'numeric') !== false && !is_numeric($value)) {
            $errors[$field] = "O campo {$field} deve ser numérico";
        }
        
        if (preg_match('/min:(\d+)/', $rule, $matches) && strlen($value) < $matches[1]) {
            $errors[$field] = "O campo {$field} deve ter pelo menos {$matches[1]} caracteres";
        }
        
        if (preg_match('/max:(\d+)/', $rule, $matches) && strlen($value) > $matches[1]) {
            $errors[$field] = "O campo {$field} deve ter no máximo {$matches[1]} caracteres";
        }
    }
    
    return empty($errors) ? true : $errors;
}

/**
 * Pagina resultados da API
 */
function paginate_api_results($data, $page = 1, $perPage = 20) {
    $total = count($data);
    $totalPages = ceil($total / $perPage);
    $offset = ($page - 1) * $perPage;
    $items = array_slice($data, $offset, $perPage);
    
    return [
        'data' => $items,
        'pagination' => [
            'current_page' => (int) $page,
            'per_page' => (int) $perPage,
            'total' => $total,
            'total_pages' => $totalPages,
            'has_next' => $page < $totalPages,
            'has_prev' => $page > 1
        ]
    ];
}