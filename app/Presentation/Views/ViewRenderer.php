<?php

namespace App\Presentation\Views;

use Exception;

/**
 * ViewRenderer - Renderizador de views com sistema de templates
 * Implementa padrão Template Method e Strategy
 */
class ViewRenderer
{
    private string $viewsPath;
    private string $componentsPath;
    private array $globalData = [];
    private array $sections = [];
    private string $currentSection = '';
    private string $layout = '';

    public function __construct()
    {
        $this->viewsPath = __DIR__ . '/../../../resources/views';
        $this->componentsPath = __DIR__ . '/../Components';
    }

    /**
     * Renderiza uma view
     */
    public function render(string $template, array $data = []): string
    {
        $templatePath = $this->getTemplatePath($template);
        
        if (!file_exists($templatePath)) {
            throw new Exception("Template {$template} não encontrado em {$templatePath}");
        }

        // Mescla dados globais com dados locais
        $data = array_merge($this->globalData, $data);
        
        // Extrai variáveis para o escopo da view
        extract($data, EXTR_SKIP);
        
        // Inicia buffer de saída
        ob_start();
        
        // Inclui o template
        include $templatePath;
        
        $content = ob_get_clean();
        
        // Se há um layout definido, renderiza dentro dele
        if (!empty($this->layout)) {
            $layoutPath = $this->getTemplatePath($this->layout);
            
            if (file_exists($layoutPath)) {
                $this->sections['content'] = $content;
                extract($data, EXTR_SKIP);
                
                ob_start();
                include $layoutPath;
                $content = ob_get_clean();
            }
            
            // Reset layout
            $this->layout = '';
            $this->sections = [];
        }
        
        return $content;
    }

    /**
     * Define o layout a ser usado
     */
    public function extends(string $layout): void
    {
        $this->layout = $layout;
    }

    /**
     * Inicia uma seção
     */
    public function section(string $name): void
    {
        $this->currentSection = $name;
        ob_start();
    }

    /**
     * Finaliza uma seção
     */
    public function endSection(): void
    {
        if (empty($this->currentSection)) {
            throw new Exception('Nenhuma seção foi iniciada');
        }
        
        $this->sections[$this->currentSection] = ob_get_clean();
        $this->currentSection = '';
    }

    /**
     * Exibe o conteúdo de uma seção
     */
    public function yield(string $name, string $default = ''): string
    {
        return $this->sections[$name] ?? $default;
    }

    /**
     * Inclui um componente
     */
    public function component(string $component, array $data = []): string
    {
        $componentPath = $this->componentsPath . '/' . str_replace('.', '/', $component) . '.php';
        
        if (!file_exists($componentPath)) {
            throw new Exception("Componente {$component} não encontrado em {$componentPath}");
        }

        // Mescla dados globais com dados do componente
        $data = array_merge($this->globalData, $data);
        
        extract($data, EXTR_SKIP);
        
        ob_start();
        include $componentPath;
        
        return ob_get_clean();
    }

    /**
     * Inclui uma partial
     */
    public function partial(string $partial, array $data = []): string
    {
        return $this->render($partial, $data);
    }

    /**
     * Escapa HTML
     */
    public function escape(string $string): string
    {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Alias para escape
     */
    public function e(string $string): string
    {
        return $this->escape($string);
    }

    /**
     * Gera URL
     */
    public function url(string $path = ''): string
    {
        $baseUrl = $_ENV['APP_URL'] ?? 'http://localhost:8000';
        return rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
    }

    /**
     * Gera URL para asset
     */
    public function asset(string $path): string
    {
        return $this->url('assets/' . ltrim($path, '/'));
    }

    /**
     * Formata data
     */
    public function formatDate(string $date, string $format = 'd/m/Y H:i'): string
    {
        return date($format, strtotime($date));
    }

    /**
     * Formata moeda
     */
    public function formatCurrency(float $value, string $currency = 'BRL'): string
    {
        return 'R$ ' . number_format($value, 2, ',', '.');
    }

    /**
     * Trunca texto
     */
    public function truncate(string $text, int $length = 100, string $suffix = '...'): string
    {
        if (strlen($text) <= $length) {
            return $text;
        }
        
        return substr($text, 0, $length) . $suffix;
    }

    /**
     * Define dados globais
     */
    public function share(array $data): void
    {
        $this->globalData = array_merge($this->globalData, $data);
    }

    /**
     * Obtém o caminho completo do template
     */
    private function getTemplatePath(string $template): string
    {
        // Converte notação de ponto para caminho
        $template = str_replace('.', '/', $template);
        
        // Adiciona extensão se não tiver
        if (!str_ends_with($template, '.php')) {
            $template .= '.php';
        }
        
        return $this->viewsPath . '/' . $template;
    }

    /**
     * Verifica se um template existe
     */
    public function exists(string $template): bool
    {
        return file_exists($this->getTemplatePath($template));
    }

    /**
     * Renderiza JSON
     */
    public function json(array $data): string
    {
        header('Content-Type: application/json');
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }

    /**
     * Inclui CSS inline
     */
    public function css(string $path): string
    {
        $fullPath = __DIR__ . '/../../../public/assets/css/' . $path;
        
        if (file_exists($fullPath)) {
            return '<style>' . file_get_contents($fullPath) . '</style>';
        }
        
        return "<!-- CSS file not found: {$path} -->";
    }

    /**
     * Inclui JS inline
     */
    public function js(string $path): string
    {
        $fullPath = __DIR__ . '/../../../public/assets/js/' . $path;
        
        if (file_exists($fullPath)) {
            return '<script>' . file_get_contents($fullPath) . '</script>';
        }
        
        return "<!-- JS file not found: {$path} -->";
    }
}