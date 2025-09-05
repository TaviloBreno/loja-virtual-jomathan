<?php

/**
 * Arquivo de rotas da aplicação
 * Define todas as rotas web disponíveis
 */

use App\Infrastructure\Http\Router;

// Obtém a instância do router (passada pelo index.php)
/** @var Router $router */

// Rota inicial
$router->get('/', 'HomeController@index');

// Rota de demonstração dos componentes Neon Futurista
$router->get('/demo/components', function() {
    include __DIR__ . '/../resources/views/pages/demo/components.php';
});

// Rotas de exemplo para usuários
$router->get('/users', 'UserController@index');
$router->get('/users/create', 'UserController@create');
$router->post('/users', 'UserController@store');
$router->get('/users/{id}', 'UserController@show');
$router->get('/users/{id}/edit', 'UserController@edit');
$router->put('/users/{id}', 'UserController@update');
$router->post('/users/{id}/delete', 'UserController@destroy');
$router->delete('/users/{id}', 'UserController@destroy');

// Rotas API
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