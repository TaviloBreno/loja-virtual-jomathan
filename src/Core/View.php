<?php

namespace Core;

/**
 * Sistema de Renderização de Views - NeonShop
 * 
 * Classe responsável por renderizar templates HTML com dados dinâmicos,
 * gerenciar layouts, incluir componentes e processar assets.
 */
class View {
    
    private string $viewsPath;
    private string $layoutsPath;
    private string $componentsPath;
    private array $data = [];
    private ?string $layout = null;
    private array $sections = [];
    private string $currentSection = '';
    private array $assets = [
        'css' => [],
        'js' => []
    ];
    
    /**
     * Construtor da classe View
     * 
     * @param string $basePath Caminho base das views
     */
    public function __construct(string $basePath = '') {
        $this->viewsPath = $basePath ?: __DIR__ . '/../../views';
        $this->layoutsPath = $this->viewsPath . '/layouts';
        $this->componentsPath = $this->viewsPath . '/components';
    }
    
    /**
     * Renderiza uma view
     * 
     * @param string $view Nome da view
     * @param array $data Dados para a view
     * @param string|null $layout Layout a ser usado
     * @return string HTML renderizado
     */
    public function render(string $view, array $data = [], ?string $layout = null): string {
        $this->data = array_merge($this->data, $data);
        
        if ($layout) {
            $this->layout = $layout;
        }
        
        $viewPath = $this->getViewPath($view);
        
        if (!file_exists($viewPath)) {
            throw new \Exception("View não encontrada: {$view}");
        }
        
        // Renderiza o conteúdo da view
        $content = $this->renderFile($viewPath, $this->data);
        
        // Se há layout, renderiza com layout
        if ($this->layout) {
            $layoutPath = $this->getLayoutPath($this->layout);
            
            if (!file_exists($layoutPath)) {
                throw new \Exception("Layout não encontrado: {$this->layout}");
            }
            
            $this->sections['content'] = $content;
            $content = $this->renderFile($layoutPath, array_merge($this->data, $this->sections));
        }
        
        return $content;
    }
    
    /**
     * Renderiza uma view como JSON
     * 
     * @param array $data Dados para JSON
     * @param int $status Código de status HTTP
     * @return string JSON
     */
    public function json(array $data, int $status = 200): string {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        return json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
    
    /**
     * Define dados globais para todas as views
     * 
     * @param array $data Dados globais
     * @return self
     */
    public function with(array $data): self {
        $this->data = array_merge($this->data, $data);
        return $this;
    }
    
    /**
     * Define o layout padrão
     * 
     * @param string $layout Nome do layout
     * @return self
     */
    public function setLayout(string $layout): self {
        $this->layout = $layout;
        return $this;
    }
    
    /**
     * Inicia uma seção
     * 
     * @param string $name Nome da seção
     */
    public function section(string $name): void {
        $this->currentSection = $name;
        ob_start();
    }
    
    /**
     * Finaliza uma seção
     */
    public function endSection(): void {
        if ($this->currentSection) {
            $this->sections[$this->currentSection] = ob_get_clean();
            $this->currentSection = '';
        }
    }
    
    /**
     * Exibe o conteúdo de uma seção
     * 
     * @param string $name Nome da seção
     * @param string $default Conteúdo padrão
     * @return string
     */
    public function yield(string $name, string $default = ''): string {
        return $this->sections[$name] ?? $default;
    }
    
    /**
     * Inclui um componente
     * 
     * @param string $component Nome do componente
     * @param array $data Dados para o componente
     * @return string HTML do componente
     */
    public function component(string $component, array $data = []): string {
        $componentPath = $this->getComponentPath($component);
        
        if (!file_exists($componentPath)) {
            throw new \Exception("Componente não encontrado: {$component}");
        }
        
        return $this->renderFile($componentPath, array_merge($this->data, $data));
    }
    
    /**
     * Inclui uma view parcial
     * 
     * @param string $partial Nome da view parcial
     * @param array $data Dados para a parcial
     * @return string HTML da parcial
     */
    public function partial(string $partial, array $data = []): string {
        $partialPath = $this->getViewPath($partial);
        
        if (!file_exists($partialPath)) {
            throw new \Exception("Parcial não encontrada: {$partial}");
        }
        
        return $this->renderFile($partialPath, array_merge($this->data, $data));
    }
    
    /**
     * Adiciona CSS
     * 
     * @param string $css Caminho do CSS
     * @return self
     */
    public function addCss(string $css): self {
        if (!in_array($css, $this->assets['css'])) {
            $this->assets['css'][] = $css;
        }
        return $this;
    }
    
    /**
     * Adiciona JavaScript
     * 
     * @param string $js Caminho do JavaScript
     * @return self
     */
    public function addJs(string $js): self {
        if (!in_array($js, $this->assets['js'])) {
            $this->assets['js'][] = $js;
        }
        return $this;
    }
    
    /**
     * Renderiza tags CSS
     * 
     * @return string Tags CSS
     */
    public function renderCss(): string {
        $html = '';
        foreach ($this->assets['css'] as $css) {
            $html .= "<link rel=\"stylesheet\" href=\"{$css}\">\n";
        }
        return $html;
    }
    
    /**
     * Renderiza tags JavaScript
     * 
     * @return string Tags JavaScript
     */
    public function renderJs(): string {
        $html = '';
        foreach ($this->assets['js'] as $js) {
            $html .= "<script src=\"{$js}\"></script>\n";
        }
        return $html;
    }
    
    /**
     * Escapa HTML
     * 
     * @param string $string String para escapar
     * @return string String escapada
     */
    public function escape(string $string): string {
        return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
    }
    
    /**
     * Formata preço
     * 
     * @param float $price Preço
     * @return string Preço formatado
     */
    public function formatPrice(float $price): string {
        return 'R$ ' . number_format($price, 2, ',', '.');
    }
    
    /**
     * Formata data
     * 
     * @param string $date Data
     * @param string $format Formato
     * @return string Data formatada
     */
    public function formatDate(string $date, string $format = 'd/m/Y'): string {
        return date($format, strtotime($date));
    }
    
    /**
     * Gera URL
     * 
     * @param string $path Caminho
     * @param array $params Parâmetros
     * @return string URL
     */
    public function url(string $path = '', array $params = []): string {
        $baseUrl = $this->getBaseUrl();
        $url = rtrim($baseUrl, '/') . '/' . ltrim($path, '/');
        
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        return $url;
    }
    
    /**
     * Gera URL de asset
     * 
     * @param string $asset Caminho do asset
     * @return string URL do asset
     */
    public function asset(string $asset): string {
        return $this->url('assets/' . ltrim($asset, '/'));
    }
    
    /**
     * Verifica se é a rota atual
     * 
     * @param string $route Rota
     * @return bool
     */
    public function isCurrentRoute(string $route): bool {
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return $currentPath === $route || str_starts_with($currentPath, $route . '/');
    }
    
    /**
     * Renderiza um arquivo
     * 
     * @param string $file Caminho do arquivo
     * @param array $data Dados
     * @return string Conteúdo renderizado
     */
    private function renderFile(string $file, array $data): string {
        // Extrai variáveis para o escopo da view
        extract($data);
        
        // Disponibiliza a instância da view
        $view = $this;
        
        ob_start();
        include $file;
        return ob_get_clean();
    }
    
    /**
     * Obtém o caminho completo de uma view
     * 
     * @param string $view Nome da view
     * @return string Caminho completo
     */
    private function getViewPath(string $view): string {
        $view = str_replace('.', '/', $view);
        return $this->viewsPath . '/' . $view . '.php';
    }
    
    /**
     * Obtém o caminho completo de um layout
     * 
     * @param string $layout Nome do layout
     * @return string Caminho completo
     */
    private function getLayoutPath(string $layout): string {
        return $this->layoutsPath . '/' . $layout . '.php';
    }
    
    /**
     * Obtém o caminho completo de um componente
     * 
     * @param string $component Nome do componente
     * @return string Caminho completo
     */
    private function getComponentPath(string $component): string {
        return $this->componentsPath . '/' . $component . '.php';
    }
    
    /**
     * Obtém a URL base
     * 
     * @return string URL base
     */
    private function getBaseUrl(): string {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $path = dirname($_SERVER['SCRIPT_NAME']);
        
        return $protocol . '://' . $host . $path;
    }
}

/**
 * Classe para gerenciar cache de views
 */
class ViewCache {
    
    private string $cachePath;
    private int $cacheTime;
    
    /**
     * Construtor do ViewCache
     * 
     * @param string $cachePath Caminho do cache
     * @param int $cacheTime Tempo de cache em segundos
     */
    public function __construct(string $cachePath, int $cacheTime = 3600) {
        $this->cachePath = $cachePath;
        $this->cacheTime = $cacheTime;
        
        if (!is_dir($this->cachePath)) {
            mkdir($this->cachePath, 0755, true);
        }
    }
    
    /**
     * Obtém conteúdo do cache
     * 
     * @param string $key Chave do cache
     * @return string|null Conteúdo ou null se não existe/expirado
     */
    public function get(string $key): ?string {
        $file = $this->getCacheFile($key);
        
        if (!file_exists($file)) {
            return null;
        }
        
        if (time() - filemtime($file) > $this->cacheTime) {
            unlink($file);
            return null;
        }
        
        return file_get_contents($file);
    }
    
    /**
     * Armazena conteúdo no cache
     * 
     * @param string $key Chave do cache
     * @param string $content Conteúdo
     */
    public function put(string $key, string $content): void {
        $file = $this->getCacheFile($key);
        file_put_contents($file, $content, LOCK_EX);
    }
    
    /**
     * Remove item do cache
     * 
     * @param string $key Chave do cache
     */
    public function forget(string $key): void {
        $file = $this->getCacheFile($key);
        if (file_exists($file)) {
            unlink($file);
        }
    }
    
    /**
     * Limpa todo o cache
     */
    public function clear(): void {
        $files = glob($this->cachePath . '/*.cache');
        foreach ($files as $file) {
            unlink($file);
        }
    }
    
    /**
     * Obtém o caminho do arquivo de cache
     * 
     * @param string $key Chave do cache
     * @return string Caminho do arquivo
     */
    private function getCacheFile(string $key): string {
        return $this->cachePath . '/' . md5($key) . '.cache';
    }
}

/**
 * Classe para compilar templates
 */
class TemplateCompiler {
    
    /**
     * Compila template com sintaxe simplificada
     * 
     * @param string $template Conteúdo do template
     * @return string Template compilado
     */
    public static function compile(string $template): string {
        // {{ $variable }} -> <?= $view->escape($variable) ?>
        $template = preg_replace('/\{\{\s*(.+?)\s*\}\}/', '<?= $view->escape($1) ?>', $template);
        
        // {!! $variable !!} -> <?= $1 ?>
        $template = preg_replace('/\{!!\s*(.+?)\s*!!\}/', '<?= $1 ?>', $template);
        
        // @if($condition) -> <?php if($condition): ?>
        $template = preg_replace('/@if\s*\((.+?)\)/', '<?php if($1): ?>', $template);
        
        // @elseif($condition) -> <?php elseif($condition): ?>
        $template = preg_replace('/@elseif\s*\((.+?)\)/', '<?php elseif($1): ?>', $template);
        
        // @else -> <?php else: ?>
        $template = str_replace('@else', '<?php else: ?>', $template);
        
        // @endif -> <?php endif; ?>
        $template = str_replace('@endif', '<?php endif; ?>', $template);
        
        // @foreach($items as $item) -> <?php foreach($items as $item): ?>
        $template = preg_replace('/@foreach\s*\((.+?)\)/', '<?php foreach($1): ?>', $template);
        
        // @endforeach -> <?php endforeach; ?>
        $template = str_replace('@endforeach', '<?php endforeach; ?>', $template);
        
        // @for($i = 0; $i < 10; $i++) -> <?php for($i = 0; $i < 10; $i++): ?>
        $template = preg_replace('/@for\s*\((.+?)\)/', '<?php for($1): ?>', $template);
        
        // @endfor -> <?php endfor; ?>
        $template = str_replace('@endfor', '<?php endfor; ?>', $template);
        
        // @while($condition) -> <?php while($condition): ?>
        $template = preg_replace('/@while\s*\((.+?)\)/', '<?php while($1): ?>', $template);
        
        // @endwhile -> <?php endwhile; ?>
        $template = str_replace('@endwhile', '<?php endwhile; ?>', $template);
        
        // @include('view') -> <?= $view->partial('view') ?>
        $template = preg_replace('/@include\s*\(\s*[\'\"](.+?)[\'\"]\s*\)/', '<?= $view->partial("$1") ?>', $template);
        
        // @component('component', $data) -> <?= $view->component('component', $data) ?>
        $template = preg_replace('/@component\s*\(\s*[\'\"](.+?)[\'\"]\s*,\s*(.+?)\s*\)/', '<?= $view->component("$1", $2) ?>', $template);
        
        // @component('component') -> <?= $view->component('component') ?>
        $template = preg_replace('/@component\s*\(\s*[\'\"](.+?)[\'\"]\s*\)/', '<?= $view->component("$1") ?>', $template);
        
        return $template;
    }
}