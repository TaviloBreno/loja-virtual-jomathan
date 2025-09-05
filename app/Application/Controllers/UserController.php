<?php

namespace App\Application\Controllers;

use App\Application\Controllers\BaseController;
use App\Infrastructure\Http\Request;
use App\Infrastructure\Http\Response;
use App\Domain\User\User;
use App\Presentation\Views\ViewRenderer;

/**
 * Controller para gerenciamento de usuários
 */
class UserController extends BaseController
{
    /**
     * Lista todos os usuários
     */
    public function index(Request $request, Response $response): void
    {
        try {
            // Parâmetros de busca e paginação
            $search = $request->input('search', '');
            $page = (int) $request->input('page', 1);
            $perPage = (int) $request->input('per_page', 10);
            $sortBy = $request->input('sort_by', 'name');
            $sortDirection = $request->input('sort_direction', 'asc');
            $status = $request->input('status', '');
            
            // Query base
            $query = User::query();
            
            // Aplicar filtros
            if ($search) {
                $query->search($search);
            }
            
            if ($status) {
                $query->where('status', $status);
            }
            
            // Aplicar ordenação
            $allowedSorts = ['name', 'email', 'status', 'created_at'];
            if (in_array($sortBy, $allowedSorts)) {
                $query->orderBy($sortBy, $sortDirection);
            }
            
            // Paginação
            $total = $query->count();
            $users = $query->limit($perPage)
                          ->offset(($page - 1) * $perPage)
                          ->get();
            
            // Calcular informações de paginação
            $totalPages = ceil($total / $perPage);
            $from = ($page - 1) * $perPage + 1;
            $to = min($page * $perPage, $total);
            
            // Estatísticas
            $stats = User::getStats();
            
            // Preparar dados para a view
            $data = [
                'users' => $users,
                'stats' => $stats,
                'pagination' => [
                    'current_page' => $page,
                    'total_pages' => $totalPages,
                    'per_page' => $perPage,
                    'total' => $total,
                    'from' => $from,
                    'to' => $to,
                    'prev_url' => $page > 1 ? $this->buildUrl($request, ['page' => $page - 1]) : null,
                    'next_url' => $page < $totalPages ? $this->buildUrl($request, ['page' => $page + 1]) : null
                ],
                'filters' => [
                    'search' => $search,
                    'status' => $status,
                    'sort_by' => $sortBy,
                    'sort_direction' => $sortDirection
                ],
                'statusOptions' => [
                    '' => 'Todos',
                    'active' => 'Ativo',
                    'inactive' => 'Inativo',
                    'pending' => 'Pendente'
                ]
            ];
            
            $content = $this->view('pages/users/index', $data);
            $response->setContent($content);
            
        } catch (\Exception $e) {
            $response->setStatusCode(500);
            $response->setContent('Erro ao carregar usuários: ' . $e->getMessage());
        }
        
        $response->send();
    }
    
    /**
     * Exibe formulário de criação
     */
    public function create(Request $request, Response $response): void
    {
        $data = [
            'user' => new User(),
            'statusOptions' => [
                'active' => 'Ativo',
                'inactive' => 'Inativo',
                'pending' => 'Pendente'
            ]
        ];
        
        $content = $this->view('pages/users/create', $data);
        $response->setContent($content);
        $response->send();
    }
    
    /**
     * Armazena um novo usuário
     */
    public function store(Request $request, Response $response): void
    {
        try {
            // Validar dados
            $data = [];
            $data['name'] = $request->input('name');
            $data['email'] = $request->input('email');
            $data['password'] = $request->input('password');
            $data['status'] = $request->input('status');
            
            // Validações adicionais
            if (empty($data['name'])) {
                throw new \InvalidArgumentException('Nome é obrigatório');
            }
            
            if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException('E-mail válido é obrigatório');
            }
            
            if (empty($data['password']) || strlen($data['password']) < 8) {
                throw new \InvalidArgumentException('Senha deve ter pelo menos 8 caracteres');
            }
            
            // Verificar se e-mail já existe
            if (User::findByEmail($data['email'])) {
                throw new \InvalidArgumentException('Este e-mail já está em uso');
            }
            
            // Definir status padrão
            if (empty($data['status'])) {
                $data['status'] = 'active';
            }
            
            // Criar usuário
            $user = User::createUser($data);
            
            // Redirecionar com sucesso
            $response->redirect('/users');
            
        } catch (\Exception $e) {
            $response->redirect('/users/create');
        }
        
        $response->send();
    }
    
    /**
     * Exibe um usuário específico
     */
    public function show(Request $request, Response $response, int $id): void
    {
        try {
            $user = User::findOrFail($id);
            
            $data = [
                'user' => $user
            ];
            
            $content = $this->view('pages/users/show', $data);
            $response->setContent($content);
            
        } catch (\Exception $e) {
            $response->redirect('/users');
        }
        
        $response->send();
    }
    
    /**
     * Exibe formulário de edição
     */
    public function edit(Request $request, Response $response, int $id): void
    {
        try {
            $user = User::findOrFail($id);
            
            $data = [
                'user' => $user,
                'statusOptions' => [
                    'active' => 'Ativo',
                    'inactive' => 'Inativo',
                    'pending' => 'Pendente'
                ]
            ];
            
            $content = $this->view('pages/users/edit', $data);
            $response->setContent($content);
            
        } catch (\Exception $e) {
            $response->redirect('/users');
        }
        
        $response->send();
    }
    
    /**
     * Atualiza um usuário
     */
    public function update(Request $request, Response $response, int $id): void
    {
        try {
            $user = User::findOrFail($id);
            
            // Validar dados
            $data = [];
            $data['name'] = $request->input('name');
            $data['email'] = $request->input('email');
            $data['password'] = $request->input('password');
            $data['status'] = $request->input('status');
            
            // Validações
            if (empty($data['name'])) {
                throw new \InvalidArgumentException('Nome é obrigatório');
            }
            
            if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException('E-mail válido é obrigatório');
            }
            
            // Verificar se e-mail já existe (exceto para o usuário atual)
            $existingUser = User::findByEmail($data['email']);
            if ($existingUser && $existingUser->id !== $user->id) {
                throw new \InvalidArgumentException('Este e-mail já está em uso');
            }
            
            // Validar senha apenas se fornecida
            if (!empty($data['password']) && strlen($data['password']) < 8) {
                throw new \InvalidArgumentException('Senha deve ter pelo menos 8 caracteres');
            }
            
            // Remover senha vazia
            if (empty($data['password'])) {
                unset($data['password']);
            }
            
            // Atualizar usuário
            $user->fill($data);
            $user->save();
            
            $response->redirect('/users');
            
        } catch (\Exception $e) {
            $response->redirect("/users/{$id}/edit");
        }
        
        $response->send();
    }
    
    /**
     * Remove um usuário
     */
    public function destroy(Request $request, Response $response, int $id): void
    {
        try {
            $user = User::findOrFail($id);
            
            // Verificar se não é o próprio usuário (se houver sistema de autenticação)
            // if ($user->id === auth()->id()) {
            //     throw new \InvalidArgumentException('Você não pode excluir sua própria conta');
            // }
            
            // Verificar se não é administrador principal
            if ($user->isAdmin()) {
                throw new \InvalidArgumentException('Não é possível excluir o administrador principal');
            }
            
            $userName = $user->name;
            $user->delete();
            
            $response->redirect('/users');
            
        } catch (\Exception $e) {
            $response->redirect('/users');
        }
        
        $response->send();
    }
    
    /**
     * Alterna o status de um usuário
     */
    public function toggleStatus(Request $request, int $id): Response
    {
        try {
            $user = User::findOrFail($id);
            
            // Alternar status
            $newStatus = $user->status === 'active' ? 'inactive' : 'active';
            $user->status = $newStatus;
            $user->save();
            
            $statusLabel = $newStatus === 'active' ? 'ativado' : 'desativado';
            
            return $this->redirect('/users')
                       ->with('success', "Usuário '{$user->name}' {$statusLabel} com sucesso!");
            
        } catch (\Exception $e) {
            return $this->redirect('/users')
                       ->with('error', $e->getMessage());
        }
    }
    
    /**
     * Marca e-mail como verificado
     */
    public function verifyEmail(Request $request, int $id): Response
    {
        try {
            $user = User::findOrFail($id);
            
            if ($user->hasVerifiedEmail()) {
                throw new \InvalidArgumentException('E-mail já está verificado');
            }
            
            $user->markEmailAsVerified();
            
            return $this->redirect('/users')
                       ->with('success', "E-mail de '{$user->name}' verificado com sucesso!");
            
        } catch (\Exception $e) {
            return $this->redirect('/users')
                       ->with('error', $e->getMessage());
        }
    }
    
    /**
     * Exporta usuários para CSV
     */
    public function export(Request $request): Response
    {
        try {
            $users = User::orderBy('name')->get();
            
            $filename = 'usuarios_' . date('Y-m-d_H-i-s') . '.csv';
            
            // Headers para download
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=' . $filename);
            header('Pragma: no-cache');
            header('Expires: 0');
            
            // Abrir output
            $output = fopen('php://output', 'w');
            
            // BOM para UTF-8
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Cabeçalhos
            fputcsv($output, [
                'ID',
                'Nome',
                'E-mail',
                'Status',
                'E-mail Verificado',
                'Criado em',
                'Atualizado em'
            ], ';');
            
            // Dados
            foreach ($users as $user) {
                fputcsv($output, [
                    $user->id,
                    $user->name,
                    $user->email,
                    $user->status_label,
                    $user->hasVerifiedEmail() ? 'Sim' : 'Não',
                    $user->created_at,
                    $user->updated_at
                ], ';');
            }
            
            fclose($output);
            exit;
            
        } catch (\Exception $e) {
            return $this->redirect('/users')
                       ->with('error', 'Erro ao exportar: ' . $e->getMessage());
        }
    }
    
    /**
     * API: Lista usuários em formato JSON
     */
    public function api(Request $request): Response
    {
        try {
            $search = $request->get('search', '');
            $limit = min((int) $request->get('limit', 10), 100);
            
            $query = User::query();
            
            if ($search) {
                $query->search($search);
            }
            
            $users = $query->limit($limit)->get();
            
            return $this->json([
                'success' => true,
                'data' => $users->toArray(),
                'count' => $users->count()
            ]);
            
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Constrói URL com parâmetros
     */
    private function buildUrl(Request $request, array $params = []): string
    {
        $currentParams = $request->all();
        $newParams = array_merge($currentParams, $params);
        
        return '/users?' . http_build_query($newParams);
    }
}