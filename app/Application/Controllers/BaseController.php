<?php

namespace App\Application\Controllers;

use App\Infrastructure\Http\Request;
use App\Infrastructure\Http\Response;
use App\Presentation\Views\ViewRenderer;

/**
 * BaseController - Classe base para todos os controllers
 * Implementa funcionalidades comuns seguindo DRY e SOLID
 */
abstract class BaseController
{
    protected ViewRenderer $viewRenderer;
    protected Request $request;
    protected Response $response;

    public function __construct()
    {
        $this->viewRenderer = new ViewRenderer();
    }

    /**
     * Renderiza uma view
     */
    protected function view(string $template, array $data = []): string
    {
        return $this->viewRenderer->render($template, $data);
    }

    /**
     * Retorna resposta JSON
     */
    protected function json(array $data, int $statusCode = 200): Response
    {
        return (new Response())->json($data, $statusCode);
    }

    /**
     * Retorna resposta de sucesso JSON
     */
    protected function successJson(string $message = 'Success', array $data = [], int $statusCode = 200): Response
    {
        return (new Response())->successJson($message, $data, $statusCode);
    }

    /**
     * Retorna resposta de erro JSON
     */
    protected function errorJson(string $message, int $statusCode = 400, array $errors = []): Response
    {
        return (new Response())->errorJson($message, $statusCode, $errors);
    }

    /**
     * Redireciona para uma URL
     */
    protected function redirect(string $url, int $statusCode = 302): Response
    {
        return (new Response())->redirect($url, $statusCode);
    }

    /**
     * Valida dados de entrada
     */
    protected function validate(Request $request, array $rules): array
    {
        $errors = [];
        $data = [];

        foreach ($rules as $field => $rule) {
            $value = $request->input($field);
            $fieldRules = explode('|', $rule);

            foreach ($fieldRules as $fieldRule) {
                if ($fieldRule === 'required' && empty($value)) {
                    $errors[$field][] = "O campo {$field} é obrigatório";
                    continue;
                }

                if (strpos($fieldRule, 'min:') === 0 && !empty($value)) {
                    $min = (int) substr($fieldRule, 4);
                    if (strlen($value) < $min) {
                        $errors[$field][] = "O campo {$field} deve ter pelo menos {$min} caracteres";
                    }
                }

                if (strpos($fieldRule, 'max:') === 0 && !empty($value)) {
                    $max = (int) substr($fieldRule, 4);
                    if (strlen($value) > $max) {
                        $errors[$field][] = "O campo {$field} deve ter no máximo {$max} caracteres";
                    }
                }

                if ($fieldRule === 'email' && !empty($value)) {
                    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                        $errors[$field][] = "O campo {$field} deve ser um email válido";
                    }
                }

                if ($fieldRule === 'numeric' && !empty($value)) {
                    if (!is_numeric($value)) {
                        $errors[$field][] = "O campo {$field} deve ser numérico";
                    }
                }
            }

            if (empty($errors[$field])) {
                $data[$field] = $value;
            }
        }

        if (!empty($errors)) {
            throw new \InvalidArgumentException('Dados de validação inválidos', 422);
        }

        return $data;
    }

    /**
     * Obtém dados paginados
     */
    protected function getPaginationData(Request $request): array
    {
        $page = max(1, (int) $request->input('page', 1));
        $perPage = min(100, max(1, (int) $request->input('per_page', 15)));
        $offset = ($page - 1) * $perPage;

        return [
            'page' => $page,
            'per_page' => $perPage,
            'offset' => $offset
        ];
    }

    /**
     * Formata resposta paginada
     */
    protected function paginatedResponse(array $items, int $total, int $page, int $perPage): array
    {
        $totalPages = ceil($total / $perPage);

        return [
            'data' => $items,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => $totalPages,
                'has_next' => $page < $totalPages,
                'has_prev' => $page > 1
            ]
        ];
    }

    /**
     * Sanitiza dados de entrada
     */
    protected function sanitize(string $input): string
    {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }

    /**
     * Verifica se a requisição é AJAX
     */
    protected function isAjax(Request $request): bool
    {
        return $request->isAjax();
    }

    /**
     * Verifica se a requisição quer JSON
     */
    protected function wantsJson(Request $request): bool
    {
        return $request->wantsJson();
    }
}