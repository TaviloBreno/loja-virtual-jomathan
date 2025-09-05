<?php

/**
 * Front Controller - Ponto de entrada da aplicação
 * Segue o padrão Single Entry Point
 */

require_once __DIR__ . '/../vendor/autoload.php';

use App\Infrastructure\Http\Router;
use App\Infrastructure\Http\Request;
use App\Infrastructure\Http\Response;
use App\Infrastructure\Database\DatabaseManager;
use Dotenv\Dotenv;

try {
    // Carrega variáveis de ambiente
    $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
    $dotenv->load();

    // Inicializa o banco de dados
    DatabaseManager::initialize();

    // Cria instâncias de Request e Response
    $request = Request::createFromGlobals();
    $response = new Response();

    // Carrega as rotas
    $router = new Router();
    require_once __DIR__ . '/../routes/web.php';

    // Processa a requisição
    $router->dispatch($request, $response);

} catch (Exception $e) {
    // Log do erro
    error_log($e->getMessage());
    
    // Resposta de erro para o usuário
    http_response_code(500);
    
    if ($_ENV['APP_DEBUG'] ?? false) {
        echo '<h1>Erro 500 - Internal Server Error</h1>';
        echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    } else {
        echo '<h1>Erro 500 - Internal Server Error</h1>';
        echo '<p>Algo deu errado. Tente novamente mais tarde.</p>';
    }
}