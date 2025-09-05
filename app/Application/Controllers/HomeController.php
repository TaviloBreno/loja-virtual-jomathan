<?php

namespace App\Application\Controllers;

use App\Infrastructure\Http\Request;
use App\Infrastructure\Http\Response;
use App\Domain\User\User;

/**
 * Controller para a página inicial
 */
class HomeController extends BaseController
{
    /**
     * Exibe a página inicial
     */
    public function index(Request $request, Response $response): void
    {
        try {
            // Estatísticas básicas para o dashboard
            $stats = [
                'total_users' => User::count(),
                'active_users' => User::where('status', 'active')->count(),
                'pending_users' => User::where('status', 'pending')->count(),
                'recent_users' => User::orderBy('created_at', 'desc')->limit(5)->get()
            ];
            
            $data = [
                'stats' => $stats,
                'page_title' => 'Dashboard',
                'current_route' => '/'
            ];
            
            $content = $this->view('pages/home', $data);
            $response->setContent($content);
            
        } catch (\Exception $e) {
            $response->setStatusCode(500);
            $response->setContent('Erro ao carregar dashboard: ' . $e->getMessage());
        }
        
        $response->send();
    }
}