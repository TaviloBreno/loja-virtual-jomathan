<?php

namespace App\Controllers;

use App\Infrastructure\Http\Request;
use App\Infrastructure\Http\Response;
use App\Models\User;
use App\Infrastructure\View\View;

class UserController extends BaseController
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Lista todos os usuários
     */
    public function index(Request $request): Response
    {
        $page = (int) ($request->get('page') ?? 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $users = $this->userModel->paginate($limit, $offset);
        $total = $this->userModel->count();
        $totalPages = ceil($total / $limit);

        return View::render('users/index', [
            'users' => $users,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'total' => $total
        ]);
    }

    /**
     * Exibe formulário de criação
     */
    public function create(): Response
    {
        return View::render('users/create');
    }

    /**
     * Armazena novo usuário
     */
    public function store(Request $request): Response
    {
        $data = $request->all();
        
        // Validação básica
        $errors = $this->validateUser($data);
        if (!empty($errors)) {
            return View::render('users/create', [
                'errors' => $errors,
                'old' => $data
            ]);
        }

        // Hash da senha
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $userId = $this->userModel->create($data);
        
        return Response::redirect('/users?success=created');
    }

    /**
     * Exibe um usuário específico
     */
    public function show(Request $request): Response
    {
        $id = $request->getRouteParam('id');
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return Response::notFound('Usuário não encontrado');
        }

        return View::render('users/show', ['user' => $user]);
    }

    /**
     * Exibe formulário de edição
     */
    public function edit(Request $request): Response
    {
        $id = $request->getRouteParam('id');
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return Response::notFound('Usuário não encontrado');
        }

        return View::render('users/edit', ['user' => $user]);
    }

    /**
     * Atualiza usuário
     */
    public function update(Request $request): Response
    {
        $id = $request->getRouteParam('id');
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return Response::notFound('Usuário não encontrado');
        }

        $data = $request->all();
        
        // Validação básica
        $errors = $this->validateUser($data, $id);
        if (!empty($errors)) {
            return View::render('users/edit', [
                'user' => $user,
                'errors' => $errors,
                'old' => $data
            ]);
        }

        // Se senha foi fornecida, fazer hash
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } else {
            unset($data['password']);
        }
        
        $data['updated_at'] = date('Y-m-d H:i:s');

        $this->userModel->update($id, $data);
        
        return Response::redirect('/users?success=updated');
    }

    /**
     * Remove usuário
     */
    public function destroy(Request $request): Response
    {
        $id = $request->getRouteParam('id');
        $user = $this->userModel->find($id);
        
        if (!$user) {
            return Response::notFound('Usuário não encontrado');
        }

        $this->userModel->delete($id);
        
        return Response::redirect('/users?success=deleted');
    }

    /**
     * Validação básica do usuário
     */
    private function validateUser(array $data, ?int $userId = null): array
    {
        $errors = [];

        if (empty($data['name'])) {
            $errors['name'] = 'Nome é obrigatório';
        }

        if (empty($data['email'])) {
            $errors['email'] = 'Email é obrigatório';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email inválido';
        } else {
            // Verificar se email já existe
            $existingUser = $this->userModel->findByEmail($data['email']);
            if ($existingUser && (!$userId || $existingUser['id'] != $userId)) {
                $errors['email'] = 'Este email já está em uso';
            }
        }

        if (!$userId && empty($data['password'])) {
            $errors['password'] = 'Senha é obrigatória';
        } elseif (!empty($data['password']) && strlen($data['password']) < 6) {
            $errors['password'] = 'Senha deve ter pelo menos 6 caracteres';
        }

        return $errors;
    }
}