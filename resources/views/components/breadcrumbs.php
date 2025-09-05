<?php
// Breadcrumbs component
// Uso: <?= $this->component('breadcrumbs', ['items' => $breadcrumbs]) ?>
// Formato dos items: [['label' => 'Home', 'url' => '/'], ['label' => 'Usuários', 'url' => '/users'], ['label' => 'Novo']]

$items = $items ?? [];

if (empty($items)) {
    return;
}
?>

<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3 bg-white rounded-lg shadow-sm border border-secondary-200 px-4 py-2">
        
        <!-- Home sempre presente -->
        <li class="inline-flex items-center">
            <a href="/" class="breadcrumb-link group">
                <i class="fas fa-home text-secondary-400 group-hover:text-primary-600 mr-2 text-sm"></i>
                <span class="text-sm font-medium text-secondary-500 group-hover:text-primary-600 transition-colors duration-200">Início</span>
            </a>
        </li>
        
        <?php foreach ($items as $index => $item): ?>
            <li>
                <div class="flex items-center">
                    <!-- Separador -->
                    <i class="fas fa-chevron-right text-secondary-300 mx-2 text-xs"></i>
                    
                    <?php if ($index === count($items) - 1): ?>
                        <!-- Último item (atual) - sem link -->
                        <span class="text-sm font-medium text-secondary-900 flex items-center">
                            <?php if (isset($item['icon'])): ?>
                                <i class="<?= htmlspecialchars($item['icon']) ?> mr-2 text-primary-600 text-sm"></i>
                            <?php endif; ?>
                            <?= htmlspecialchars($item['label']) ?>
                        </span>
                    <?php else: ?>
                        <!-- Item com link -->
                        <a href="<?= htmlspecialchars($item['url']) ?>" class="breadcrumb-link group flex items-center">
                            <?php if (isset($item['icon'])): ?>
                                <i class="<?= htmlspecialchars($item['icon']) ?> mr-2 text-secondary-400 group-hover:text-primary-600 text-sm"></i>
                            <?php endif; ?>
                            <span class="text-sm font-medium text-secondary-500 group-hover:text-primary-600 transition-colors duration-200">
                                <?= htmlspecialchars($item['label']) ?>
                            </span>
                        </a>
                    <?php endif; ?>
                </div>
            </li>
        <?php endforeach; ?>
    </ol>
</nav>

<!-- Breadcrumbs com dropdown para mobile -->
<nav class="md:hidden mb-6" x-data="{ open: false }">
    <div class="bg-white rounded-lg shadow-sm border border-secondary-200">
        <!-- Botão para abrir dropdown -->
        <button @click="open = !open" class="w-full px-4 py-3 flex items-center justify-between text-left focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-inset">
            <div class="flex items-center">
                <i class="fas fa-map-marker-alt text-primary-600 mr-2 text-sm"></i>
                <span class="text-sm font-medium text-secondary-900">
                    <?= htmlspecialchars(end($items)['label']) ?>
                </span>
            </div>
            <i class="fas fa-chevron-down text-secondary-400 text-xs transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
        </button>
        
        <!-- Dropdown com todos os breadcrumbs -->
        <div x-show="open" 
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             class="border-t border-secondary-200 py-2"
             x-cloak>
            
            <!-- Home -->
            <a href="/" class="mobile-breadcrumb-link" @click="open = false">
                <i class="fas fa-home text-secondary-400 mr-3 text-sm"></i>
                <span>Início</span>
            </a>
            
            <?php foreach ($items as $index => $item): ?>
                <?php if (isset($item['url']) && $index < count($items) - 1): ?>
                    <a href="<?= htmlspecialchars($item['url']) ?>" class="mobile-breadcrumb-link" @click="open = false">
                        <?php if (isset($item['icon'])): ?>
                            <i class="<?= htmlspecialchars($item['icon']) ?> text-secondary-400 mr-3 text-sm"></i>
                        <?php else: ?>
                            <div class="w-4 mr-3"></div>
                        <?php endif; ?>
                        <span><?= htmlspecialchars($item['label']) ?></span>
                    </a>
                <?php else: ?>
                    <div class="mobile-breadcrumb-current">
                        <?php if (isset($item['icon'])): ?>
                            <i class="<?= htmlspecialchars($item['icon']) ?> text-primary-600 mr-3 text-sm"></i>
                        <?php else: ?>
                            <div class="w-4 mr-3"></div>
                        <?php endif; ?>
                        <span><?= htmlspecialchars($item['label']) ?></span>
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
    </div>
</nav>

<!-- Breadcrumbs estruturados para SEO -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "BreadcrumbList",
    "itemListElement": [
        {
            "@type": "ListItem",
            "position": 1,
            "name": "Início",
            "item": "<?= $_SERVER['REQUEST_SCHEME'] ?>://<?= $_SERVER['HTTP_HOST'] ?>/"
        }
        <?php foreach ($items as $index => $item): ?>
            <?php if (isset($item['url'])): ?>
                ,{
                    "@type": "ListItem",
                    "position": <?= $index + 2 ?>,
                    "name": "<?= htmlspecialchars($item['label']) ?>",
                    "item": "<?= $_SERVER['REQUEST_SCHEME'] ?>://<?= $_SERVER['HTTP_HOST'] ?><?= htmlspecialchars($item['url']) ?>"
                }
            <?php else: ?>
                ,{
                    "@type": "ListItem",
                    "position": <?= $index + 2 ?>,
                    "name": "<?= htmlspecialchars($item['label']) ?>"
                }
            <?php endif; ?>
        <?php endforeach; ?>
    ]
}
</script>

<style>
    .breadcrumb-link {
        @apply hover:bg-secondary-50 rounded px-2 py-1 -mx-2 -my-1 transition-colors duration-200;
    }
    
    .mobile-breadcrumb-link {
        @apply block px-4 py-2 text-sm text-secondary-600 hover:bg-secondary-50 hover:text-secondary-900 transition-colors duration-200 flex items-center;
    }
    
    .mobile-breadcrumb-current {
        @apply px-4 py-2 text-sm text-secondary-900 font-medium bg-secondary-50 flex items-center;
    }
</style>

<?php
// Função helper para gerar breadcrumbs automaticamente baseado na URL
function generateBreadcrumbs($currentPath, $customLabels = []) {
    $breadcrumbs = [];
    $pathParts = array_filter(explode('/', trim($currentPath, '/')));
    $currentUrl = '';
    
    foreach ($pathParts as $index => $part) {
        $currentUrl .= '/' . $part;
        
        // Usar label customizado se disponível
        if (isset($customLabels[$currentUrl])) {
            $label = $customLabels[$currentUrl]['label'];
            $icon = $customLabels[$currentUrl]['icon'] ?? null;
        } else {
            // Gerar label automaticamente
            $label = ucfirst(str_replace(['-', '_'], ' ', $part));
            $icon = null;
            
            // Ícones padrão para algumas rotas
            $defaultIcons = [
                'users' => 'fas fa-users',
                'reports' => 'fas fa-chart-bar',
                'settings' => 'fas fa-cog',
                'profile' => 'fas fa-user',
                'create' => 'fas fa-plus',
                'edit' => 'fas fa-edit',
                'view' => 'fas fa-eye'
            ];
            
            if (isset($defaultIcons[$part])) {
                $icon = $defaultIcons[$part];
            }
        }
        
        $breadcrumb = ['label' => $label];
        
        if ($icon) {
            $breadcrumb['icon'] = $icon;
        }
        
        // Adicionar URL apenas se não for o último item
        if ($index < count($pathParts) - 1) {
            $breadcrumb['url'] = $currentUrl;
        }
        
        $breadcrumbs[] = $breadcrumb;
    }
    
    return $breadcrumbs;
}

// Exemplo de uso da função helper:
// $breadcrumbs = generateBreadcrumbs('/users/create', [
//     '/users' => ['label' => 'Usuários', 'icon' => 'fas fa-users'],
//     '/users/create' => ['label' => 'Novo Usuário', 'icon' => 'fas fa-plus']
// ]);
?>