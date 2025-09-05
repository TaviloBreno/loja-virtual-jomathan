<?php

/**
 * Sistema de Roteamento HTTP - NeonShop E-commerce
 * 
 * Este arquivo define todas as rotas da aplicação, incluindo:
 * - Rotas GET para páginas públicas
 * - Rotas POST para ações (login, registro, carrinho, checkout)
 * - Rotas de API (JSON) para requisições Ajax
 * - Middlewares de autenticação, admin e CSRF
 */

use App\Infrastructure\Http\Router;

// Obtém a instância do router (passada pelo index.php)
/** @var Router $router */

// ============================================================================
// ROTAS PÚBLICAS (GET) - Páginas do E-commerce
// ============================================================================

// Página inicial
$router->get('/', 'HomeController@index');
$router->get('/home', 'HomeController@index');

// Produtos
$router->get('/produtos', 'ProductController@index');
$router->get('/produto/{id}', 'ProductController@show');
$router->get('/categoria/{slug}', 'ProductController@category');
$router->get('/buscar', 'ProductController@search');

// Carrinho e Checkout
$router->get('/carrinho', 'CartController@index');
$router->get('/checkout', 'CheckoutController@index');
$router->get('/checkout/sucesso', 'CheckoutController@success');

// Autenticação
$router->get('/login', 'AuthController@loginForm');
$router->get('/registro', 'AuthController@registerForm');
$router->get('/logout', 'AuthController@logout');
$router->get('/esqueci-senha', 'AuthController@forgotPasswordForm');
$router->get('/redefinir-senha/{token}', 'AuthController@resetPasswordForm');

// Páginas institucionais
$router->get('/contato', 'PageController@contact');
$router->get('/privacidade', 'PageController@privacy');
$router->get('/trocas-devolucoes', 'PageController@returns');
$router->get('/termos-uso', 'PageController@terms');
$router->get('/sobre', 'PageController@about');

// Área do usuário
$router->get('/minha-conta', 'AuthController@account');
$router->get('/meus-pedidos', 'AuthController@orders');
$router->get('/pedido/{id}', 'AuthController@orderDetails');
$router->get('/favoritos', 'AuthController@favorites');

// ============================================================================
// ROTAS DE AÇÃO (POST) - Processamento de formulários
// ============================================================================

// Autenticação
$router->post('/login', 'AuthController@login');
$router->post('/registro', 'AuthController@register');
$router->post('/esqueci-senha', 'AuthController@forgotPassword');
$router->post('/redefinir-senha', 'AuthController@resetPassword');

// Carrinho
$router->post('/carrinho/adicionar', 'CartController@add');
$router->post('/carrinho/atualizar', 'CartController@update');
$router->post('/carrinho/remover', 'CartController@remove');
$router->post('/carrinho/limpar', 'CartController@clear');
$router->post('/carrinho/cupom', 'CartController@applyCoupon');

// Checkout
$router->post('/checkout/processar', 'CheckoutController@process');
$router->post('/checkout/pagamento', 'CheckoutController@payment');

// Conta do usuário
$router->post('/minha-conta/atualizar', 'AuthController@updateAccount');
$router->post('/minha-conta/senha', 'AuthController@changePassword');
$router->post('/favoritos/adicionar', 'AuthController@addFavorite');
$router->post('/favoritos/remover', 'AuthController@removeFavorite');

// Contato
$router->post('/contato/enviar', 'PageController@sendContact');

// ============================================================================
// ROTAS DE API (JSON) - Para requisições Ajax
// ============================================================================

// Busca e filtros
$router->get('/api/buscar', 'ApiController@search');
$router->get('/api/produtos/filtros', 'ApiController@productFilters');
$router->get('/api/categorias', 'ApiController@categories');
$router->get('/api/marcas', 'ApiController@brands');

// Produto
$router->get('/api/produto/{id}', 'ApiController@product');
$router->get('/api/produto/{id}/estoque', 'ApiController@productStock');
$router->get('/api/produto/{id}/avaliacoes', 'ApiController@productReviews');
$router->post('/api/produto/{id}/avaliar', 'ApiController@addReview');

// CEP e Frete
$router->get('/api/cep/{cep}', 'ApiController@addressByCep');
$router->post('/api/frete/calcular', 'ApiController@calculateShipping');
$router->get('/api/frete/opcoes', 'ApiController@shippingOptions');

// Carrinho (Ajax)
$router->get('/api/carrinho', 'ApiController@cartContents');
$router->post('/api/carrinho/adicionar', 'ApiController@addToCart');
$router->put('/api/carrinho/atualizar', 'ApiController@updateCart');
$router->delete('/api/carrinho/remover/{id}', 'ApiController@removeFromCart');

// Cupons
$router->post('/api/cupom/validar', 'ApiController@validateCoupon');
$router->get('/api/cupons/disponiveis', 'ApiController@availableCoupons');

// Pagamento
$router->post('/api/pagamento/processar', 'ApiController@processPayment');
$router->get('/api/pagamento/status/{id}', 'ApiController@paymentStatus');
$router->post('/api/pagamento/webhook', 'ApiController@paymentWebhook');

// Usuário
$router->get('/api/usuario/pedidos', 'ApiController@userOrders');
$router->get('/api/usuario/favoritos', 'ApiController@userFavorites');
$router->get('/api/usuario/endereco', 'ApiController@userAddresses');
$router->post('/api/usuario/endereco', 'ApiController@addUserAddress');

// Notificações
$router->get('/api/notificacoes', 'ApiController@notifications');
$router->post('/api/notificacoes/marcar-lida/{id}', 'ApiController@markNotificationRead');

// ============================================================================
// ROTAS ADMINISTRATIVAS
// ============================================================================

// Dashboard Admin
$router->get('/admin', 'AdminController@dashboard');
$router->get('/admin/dashboard', 'AdminController@dashboard');

// Produtos Admin
$router->get('/admin/produtos', 'AdminController@products');
$router->get('/admin/produtos/criar', 'AdminController@createProduct');
$router->post('/admin/produtos/criar', 'AdminController@storeProduct');
$router->get('/admin/produtos/{id}/editar', 'AdminController@editProduct');
$router->put('/admin/produtos/{id}', 'AdminController@updateProduct');
$router->delete('/admin/produtos/{id}', 'AdminController@deleteProduct');

// Pedidos Admin
$router->get('/admin/pedidos', 'AdminController@orders');
$router->get('/admin/pedidos/{id}', 'AdminController@orderDetails');
$router->put('/admin/pedidos/{id}/status', 'AdminController@updateOrderStatus');

// Usuários Admin
$router->get('/admin/usuarios', 'AdminController@users');
$router->get('/admin/usuarios/{id}', 'AdminController@userDetails');

// ============================================================================
// ROTAS DE DEMONSTRAÇÃO E DESENVOLVIMENTO
// ============================================================================

// Rota de demonstração dos componentes Neon Futurista
$router->get('/demo/components', function() {
    include __DIR__ . '/../resources/views/pages/demo/components.php';
});

// Rotas de exemplo para usuários (manter para compatibilidade)
$router->get('/users', 'UserController@index');
$router->get('/users/create', 'UserController@create');
$router->post('/users', 'UserController@store');
$router->get('/users/{id}', 'UserController@show');
$router->get('/users/{id}/edit', 'UserController@edit');
$router->put('/users/{id}', 'UserController@update');
$router->post('/users/{id}/delete', 'UserController@destroy');
$router->delete('/users/{id}', 'UserController@destroy');

// Rotas API de usuários (manter para compatibilidade)
$router->get('/api/users', 'Api\UserController@index');
$router->post('/api/users', 'Api\UserController@store');
$router->get('/api/users/{id}', 'Api\UserController@show');
$router->put('/api/users/{id}', 'Api\UserController@update');
$router->delete('/api/users/{id}', 'Api\UserController@destroy');

// Rota de teste
$router->get('/test', function($request, $response) {
    return $response->json([
        'message' => 'Sistema funcionando!',
        'timestamp' => date('Y-m-d H:i:s'),
        'method' => $request->getMethod(),
        'path' => $request->getPath()
    ]);
});

// Rota para servir assets estáticos (CSS, JS, imagens)
$router->get('/assets/{file}', function($request, $response, $file) {
    $filePath = __DIR__ . '/../public/assets/' . $file;
    
    if (!file_exists($filePath)) {
        return $response->error('Arquivo não encontrado', 404);
    }
    
    $mimeType = mime_content_type($filePath);
    $response->setHeader('Content-Type', $mimeType);
    $response->setContent(file_get_contents($filePath));
    
    return $response;
});