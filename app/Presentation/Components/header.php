<?php
/**
 * Header Component - E-commerce Neon Futurista
 * Componente de cabeçalho com navbar, busca e carrinho
 */

$config = [
    'logo' => $logo ?? 'NeonShop',
    'search_placeholder' => $search_placeholder ?? 'Buscar produtos...',
    'cart_count' => $cart_count ?? 0,
    'user_name' => $user_name ?? null,
    'categories' => $categories ?? [],
    'show_categories' => $show_categories ?? true,
    'sticky' => $sticky ?? true
];

$stickyClass = $config['sticky'] ? 'sticky top-0 z-50' : '';
?>

<header class="<?= $stickyClass ?> bg-gradient-to-r from-gray-900 via-purple-900 to-gray-900 backdrop-blur-md border-b border-primary-500/20 shadow-lg shadow-primary-500/10">
    <!-- Top Bar -->
    <div class="bg-gradient-to-r from-primary-600 to-secondary-600 text-white py-2">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center text-sm">
                <div class="flex items-center space-x-4">
                    <span class="flex items-center">
                        <i class="fas fa-shipping-fast mr-2"></i>
                        Frete grátis acima de R$ 199
                    </span>
                    <span class="flex items-center">
                        <i class="fas fa-shield-alt mr-2"></i>
                        Compra 100% segura
                    </span>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="/contato" class="hover:text-secondary-200 transition-colors">
                        <i class="fas fa-phone mr-1"></i>
                        (11) 9999-9999
                    </a>
                    <a href="/rastreamento" class="hover:text-secondary-200 transition-colors">
                        <i class="fas fa-search mr-1"></i>
                        Rastrear pedido
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <div class="container mx-auto px-4 py-4">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="/" class="flex items-center space-x-3 group">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/25 group-hover:shadow-primary-500/40 transition-all duration-300">
                        <i class="fas fa-bolt text-white text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-primary-400 to-secondary-400 bg-clip-text text-transparent">
                            <?= htmlspecialchars($config['logo']) ?>
                        </h1>
                        <p class="text-xs text-gray-400">E-commerce Futurista</p>
                    </div>
                </a>
            </div>

            <!-- Search Bar -->
            <div class="flex-1 max-w-2xl mx-8">
                <form action="/produtos" method="GET" class="relative group">
                    <div class="relative">
                        <input 
                            type="text" 
                            name="q" 
                            placeholder="<?= htmlspecialchars($config['search_placeholder']) ?>"
                            value="<?= htmlspecialchars($_GET['q'] ?? '') ?>"
                            class="w-full px-6 py-3 pl-12 pr-16 bg-gray-800/50 border border-gray-700 rounded-xl text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent backdrop-blur-sm transition-all duration-300 group-hover:bg-gray-800/70"
                        >
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i class="fas fa-search text-gray-400 group-focus-within:text-primary-400 transition-colors"></i>
                        </div>
                        <button 
                            type="submit"
                            class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-primary-400 transition-colors"
                        >
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </div>
                    
                    <!-- Search Suggestions (Hidden by default) -->
                    <div class="absolute top-full left-0 right-0 mt-2 bg-gray-800/95 backdrop-blur-md border border-gray-700 rounded-xl shadow-xl shadow-black/20 hidden" id="search-suggestions">
                        <div class="p-4">
                            <div class="text-sm text-gray-400 mb-2">Sugestões populares:</div>
                            <div class="space-y-2">
                                <a href="/produtos?q=smartphone" class="block px-3 py-2 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-lg transition-colors">
                                    <i class="fas fa-mobile-alt mr-2 text-primary-400"></i>
                                    Smartphones
                                </a>
                                <a href="/produtos?q=notebook" class="block px-3 py-2 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-lg transition-colors">
                                    <i class="fas fa-laptop mr-2 text-secondary-400"></i>
                                    Notebooks
                                </a>
                                <a href="/produtos?q=headphone" class="block px-3 py-2 text-gray-300 hover:text-white hover:bg-gray-700/50 rounded-lg transition-colors">
                                    <i class="fas fa-headphones mr-2 text-accent-400"></i>
                                    Headphones
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- User Actions -->
            <div class="flex items-center space-x-4">
                <!-- Wishlist -->
                <a href="/favoritos" class="relative p-3 text-gray-400 hover:text-primary-400 transition-colors group">
                    <i class="fas fa-heart text-xl"></i>
                    <span class="absolute -top-1 -right-1 bg-secondary-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">3</span>
                </a>

                <!-- Cart -->
                <a href="/carrinho" class="relative p-3 text-gray-400 hover:text-primary-400 transition-colors group">
                    <i class="fas fa-shopping-cart text-xl"></i>
                    <?php if ($config['cart_count'] > 0): ?>
                        <span class="absolute -top-1 -right-1 bg-primary-500 text-white text-xs rounded-full w-6 h-6 flex items-center justify-center font-bold animate-pulse">
                            <?= $config['cart_count'] ?>
                        </span>
                    <?php endif; ?>
                </a>

                <!-- User Menu -->
                <div class="relative" x-data="{ open: false }">
                    <?php if ($config['user_name']): ?>
                        <button @click="open = !open" class="flex items-center space-x-2 p-2 text-gray-400 hover:text-white transition-colors">
                            <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <span class="hidden md:block"><?= htmlspecialchars($config['user_name']) ?></span>
                            <i class="fas fa-chevron-down text-xs" :class="{ 'rotate-180': open }"></i>
                        </button>
                        
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-gray-800/95 backdrop-blur-md border border-gray-700 rounded-xl shadow-xl shadow-black/20">
                            <div class="py-2">
                                <a href="/perfil" class="block px-4 py-2 text-gray-300 hover:text-white hover:bg-gray-700/50 transition-colors">
                                    <i class="fas fa-user mr-2"></i>
                                    Meu Perfil
                                </a>
                                <a href="/pedidos" class="block px-4 py-2 text-gray-300 hover:text-white hover:bg-gray-700/50 transition-colors">
                                    <i class="fas fa-box mr-2"></i>
                                    Meus Pedidos
                                </a>
                                <a href="/favoritos" class="block px-4 py-2 text-gray-300 hover:text-white hover:bg-gray-700/50 transition-colors">
                                    <i class="fas fa-heart mr-2"></i>
                                    Favoritos
                                </a>
                                <hr class="my-2 border-gray-700">
                                <a href="/logout" class="block px-4 py-2 text-red-400 hover:text-red-300 hover:bg-gray-700/50 transition-colors">
                                    <i class="fas fa-sign-out-alt mr-2"></i>
                                    Sair
                                </a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="flex items-center space-x-2">
                            <a href="/login" class="px-4 py-2 text-gray-400 hover:text-white transition-colors">
                                Entrar
                            </a>
                            <a href="/registro" class="px-4 py-2 bg-gradient-to-r from-primary-500 to-secondary-500 text-white rounded-lg hover:from-primary-600 hover:to-secondary-600 transition-all duration-300 shadow-lg shadow-primary-500/25">
                                Cadastrar
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Navigation -->
    <?php if ($config['show_categories'] && !empty($config['categories'])): ?>
        <div class="border-t border-gray-700/50">
            <div class="container mx-auto px-4">
                <nav class="flex items-center space-x-8 py-3 overflow-x-auto">
                    <a href="/produtos" class="whitespace-nowrap text-gray-400 hover:text-primary-400 transition-colors font-medium">
                        <i class="fas fa-th-large mr-2"></i>
                        Todas as Categorias
                    </a>
                    <?php foreach ($config['categories'] as $category): ?>
                        <a href="/produtos?categoria=<?= urlencode($category['slug']) ?>" class="whitespace-nowrap text-gray-400 hover:text-primary-400 transition-colors">
                            <?php if (isset($category['icon'])): ?>
                                <i class="<?= $category['icon'] ?> mr-2"></i>
                            <?php endif; ?>
                            <?= htmlspecialchars($category['name']) ?>
                        </a>
                    <?php endforeach; ?>
                </nav>
            </div>
        </div>
    <?php endif; ?>
</header>

<style>
.header-glow {
    box-shadow: 0 0 30px rgba(59, 130, 246, 0.1);
}

@media (max-width: 768px) {
    .container {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .flex-1.max-w-2xl.mx-8 {
        margin-left: 1rem;
        margin-right: 1rem;
        max-width: none;
    }
}
</style>

<script>
// Search suggestions functionality
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.querySelector('input[name="q"]');
    const suggestions = document.getElementById('search-suggestions');
    
    if (searchInput && suggestions) {
        searchInput.addEventListener('focus', function() {
            if (this.value.length === 0) {
                suggestions.classList.remove('hidden');
            }
        });
        
        searchInput.addEventListener('blur', function() {
            setTimeout(() => {
                suggestions.classList.add('hidden');
            }, 200);
        });
        
        searchInput.addEventListener('input', function() {
            if (this.value.length > 0) {
                suggestions.classList.add('hidden');
            } else {
                suggestions.classList.remove('hidden');
            }
        });
    }
});
</script>