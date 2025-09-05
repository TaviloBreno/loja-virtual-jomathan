<?php

namespace App\Core\View;

/**
 * Sistema de renderização de componentes
 * Permite criar e reutilizar componentes UI de forma eficiente
 */
class ComponentRenderer
{
    private static $instance = null;
    private $componentsPath;
    private $cache = [];
    private $slots = [];
    private $currentComponent = null;
    
    private function __construct()
    {
        $this->componentsPath = dirname(__DIR__, 3) . '/resources/views/components/';
    }
    
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        return self::$instance;
    }
    
    /**
     * Renderiza um componente
     */
    public function render(string $component, array $data = [], array $slots = []): string
    {
        $componentPath = $this->resolveComponentPath($component);
        
        if (!file_exists($componentPath)) {
            throw new \Exception("Component '{$component}' not found at {$componentPath}");
        }
        
        // Salvar contexto atual
        $previousComponent = $this->currentComponent;
        $previousSlots = $this->slots;
        
        // Configurar novo contexto
        $this->currentComponent = $component;
        $this->slots = $slots;
        
        // Extrair variáveis para o escopo do componente
        extract($data, EXTR_SKIP);
        
        // Capturar saída
        ob_start();
        include $componentPath;
        $output = ob_get_clean();
        
        // Restaurar contexto anterior
        $this->currentComponent = $previousComponent;
        $this->slots = $previousSlots;
        
        return $output;
    }
    
    /**
     * Renderiza um slot
     */
    public function slot(string $name, string $default = ''): string
    {
        return $this->slots[$name] ?? $default;
    }
    
    /**
     * Verifica se um slot existe
     */
    public function hasSlot(string $name): bool
    {
        return isset($this->slots[$name]);
    }
    
    /**
     * Resolve o caminho do componente
     */
    private function resolveComponentPath(string $component): string
    {
        // Converter dot notation para path
        $path = str_replace('.', '/', $component);
        
        return $this->componentsPath . $path . '.php';
    }
    
    /**
     * Cria um componente inline
     */
    public function inline(string $template, array $data = []): string
    {
        extract($data, EXTR_SKIP);
        
        ob_start();
        eval('?>' . $template);
        return ob_get_clean();
    }
    
    /**
     * Renderiza uma lista de componentes
     */
    public function each(string $component, array $items, string $itemKey = 'item', array $additionalData = []): string
    {
        $output = '';
        
        foreach ($items as $index => $item) {
            $data = array_merge($additionalData, [
                $itemKey => $item,
                'index' => $index,
                'first' => $index === 0,
                'last' => $index === count($items) - 1
            ]);
            
            $output .= $this->render($component, $data);
        }
        
        return $output;
    }
    
    /**
     * Renderiza componente condicionalmente
     */
    public function when(bool $condition, string $component, array $data = [], array $slots = []): string
    {
        return $condition ? $this->render($component, $data, $slots) : '';
    }
    
    /**
     * Renderiza um dos componentes baseado na condição
     */
    public function choose(bool $condition, string $trueComponent, string $falseComponent, array $data = [], array $slots = []): string
    {
        $component = $condition ? $trueComponent : $falseComponent;
        return $this->render($component, $data, $slots);
    }
    
    /**
     * Cache de componentes
     */
    public function cached(string $key, callable $callback, int $ttl = 3600): string
    {
        $cacheKey = 'component_' . md5($key);
        
        if (isset($this->cache[$cacheKey])) {
            $cached = $this->cache[$cacheKey];
            if ($cached['expires'] > time()) {
                return $cached['content'];
            }
        }
        
        $content = $callback();
        
        $this->cache[$cacheKey] = [
            'content' => $content,
            'expires' => time() + $ttl
        ];
        
        return $content;
    }
    
    /**
     * Limpa o cache de componentes
     */
    public function clearCache(): void
    {
        $this->cache = [];
    }
    
    /**
     * Registra helpers globais
     */
    public function registerHelpers(): void
    {
        if (!function_exists('component')) {
            function component(string $name, array $data = [], array $slots = []): string {
                return ComponentRenderer::getInstance()->render($name, $data, $slots);
            }
        }
        
        if (!function_exists('slot')) {
            function slot(string $name, string $default = ''): string {
                return ComponentRenderer::getInstance()->slot($name, $default);
            }
        }
        
        if (!function_exists('hasSlot')) {
            function hasSlot(string $name): bool {
                return ComponentRenderer::getInstance()->hasSlot($name);
            }
        }
    }
    
    /**
     * Cria um componente com layout
     */
    public function layout(string $layout, array $data = [], callable $content = null): string
    {
        if ($content) {
            ob_start();
            $content();
            $contentHtml = ob_get_clean();
            $data['content'] = $contentHtml;
        }
        
        return $this->render($layout, $data);
    }
    
    /**
     * Renderiza componente com fallback
     */
    public function fallback(array $components, array $data = [], array $slots = []): string
    {
        foreach ($components as $component) {
            $componentPath = $this->resolveComponentPath($component);
            if (file_exists($componentPath)) {
                return $this->render($component, $data, $slots);
            }
        }
        
        return '';
    }
    
    /**
     * Renderiza componente com tratamento de erro
     */
    public function safe(string $component, array $data = [], array $slots = [], string $fallback = ''): string
    {
        try {
            return $this->render($component, $data, $slots);
        } catch (\Exception $e) {
            // Log do erro
            error_log("Component render error: {$e->getMessage()}");
            return $fallback;
        }
    }
    
    /**
     * Cria um wrapper para componentes
     */
    public function wrap(string $wrapper, string $component, array $wrapperData = [], array $componentData = []): string
    {
        $componentHtml = $this->render($component, $componentData);
        $wrapperData['content'] = $componentHtml;
        
        return $this->render($wrapper, $wrapperData);
    }
    
    /**
     * Renderiza múltiplos componentes
     */
    public function multiple(array $components): string
    {
        $output = '';
        
        foreach ($components as $componentConfig) {
            if (is_string($componentConfig)) {
                $output .= $this->render($componentConfig);
            } elseif (is_array($componentConfig)) {
                $component = $componentConfig['component'] ?? $componentConfig[0];
                $data = $componentConfig['data'] ?? $componentConfig[1] ?? [];
                $slots = $componentConfig['slots'] ?? $componentConfig[2] ?? [];
                
                $output .= $this->render($component, $data, $slots);
            }
        }
        
        return $output;
    }
    
    /**
     * Cria um componente com contexto compartilhado
     */
    public function withContext(array $context, callable $callback): string
    {
        $previousContext = $this->getContext();
        $this->setContext(array_merge($previousContext, $context));
        
        ob_start();
        $callback();
        $output = ob_get_clean();
        
        $this->setContext($previousContext);
        
        return $output;
    }
    
    /**
     * Obtém o contexto atual
     */
    private function getContext(): array
    {
        return $GLOBALS['component_context'] ?? [];
    }
    
    /**
     * Define o contexto
     */
    private function setContext(array $context): void
    {
        $GLOBALS['component_context'] = $context;
    }
    
    /**
     * Obtém uma variável do contexto
     */
    public function context(string $key, $default = null)
    {
        $context = $this->getContext();
        return $context[$key] ?? $default;
    }
    
    /**
     * Renderiza componente com profiling
     */
    public function profile(string $component, array $data = [], array $slots = []): array
    {
        $start = microtime(true);
        $memoryBefore = memory_get_usage();
        
        $output = $this->render($component, $data, $slots);
        
        $end = microtime(true);
        $memoryAfter = memory_get_usage();
        
        return [
            'output' => $output,
            'time' => ($end - $start) * 1000, // em millisegundos
            'memory' => $memoryAfter - $memoryBefore,
            'component' => $component
        ];
    }
    
    /**
     * Lista todos os componentes disponíveis
     */
    public function listComponents(): array
    {
        $components = [];
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($this->componentsPath)
        );
        
        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $relativePath = str_replace($this->componentsPath, '', $file->getPathname());
                $componentName = str_replace(['/', '.php'], ['.', ''], $relativePath);
                $components[] = $componentName;
            }
        }
        
        return $components;
    }
}