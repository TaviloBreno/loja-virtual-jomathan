<?php
// Configurações da página
$title = 'StyleHub - Moda & Estilo';
$show_header = true;
$show_footer = true;

// Dados simulados para demonstração
$featured_products = [
    [
        'id' => 1,
        'name' => 'Vestido Floral Elegante',
        'price' => 189.99,
        'original_price' => 249.99,
        'image' => 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=400&h=500&fit=crop',
        'rating' => 4.8,
        'reviews' => 156,
        'badge' => 'Destaque',
        'discount' => 24
    ],
    [
        'id' => 2,
        'name' => 'Blazer Feminino Premium',
        'price' => 299.99,
        'original_price' => 399.99,
        'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=400&h=500&fit=crop',
        'rating' => 4.9,
        'reviews' => 89,
        'badge' => 'Mais Vendido',
        'discount' => 25
    ],
    [
        'id' => 3,
        'name' => 'Calça Jeans Skinny',
        'price' => 129.99,
        'original_price' => 179.99,
        'image' => 'https://images.unsplash.com/photo-1541099649105-f69ad21f3246?w=400&h=500&fit=crop',
        'rating' => 4.7,
        'reviews' => 234,
        'badge' => 'Oferta',
        'discount' => 28
    ],
    [
        'id' => 4,
        'name' => 'Blusa Cropped Moderna',
        'price' => 89.99,
        'original_price' => 119.99,
        'image' => 'https://images.unsplash.com/photo-1515886657613-9f3515b0c78f?w=400&h=500&fit=crop',
        'rating' => 4.6,
        'reviews' => 178,
        'badge' => 'Novo',
        'discount' => 25
    ]
];

$new_products = [
    [
        'id' => 5,
        'name' => 'Saia Midi Plissada',
        'price' => 149.99,
        'image' => 'https://images.unsplash.com/photo-1583496661160-fb5886a13d24?w=400&h=500&fit=crop',
        'rating' => 4.5,
        'reviews' => 67
    ],
    [
        'id' => 6,
        'name' => 'Casaco de Inverno',
        'price' => 399.99,
        'image' => 'https://images.unsplash.com/photo-1551698618-1dfe5d97d256?w=400&h=500&fit=crop',
        'rating' => 4.8,
        'reviews' => 45
    ],
    [
        'id' => 7,
        'name' => 'Tênis Casual Branco',
        'price' => 199.99,
        'image' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=400&h=500&fit=crop',
        'rating' => 4.9,
        'reviews' => 123
    ],
    [
        'id' => 8,
        'name' => 'Bolsa Transversal',
        'price' => 159.99,
        'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=400&h=500&fit=crop',
        'rating' => 4.7,
        'reviews' => 89
    ]
];

$recentActivities = [
    [
        'user' => 'João Silva',
        'action' => 'fez um pedido de',
        'target' => 'Smartphone Galaxy Neon',
        'time' => 'há 2 minutos',
        'icon' => 'fas fa-shopping-cart',
        'color' => 'green'
    ],
    [
        'user' => 'Maria Santos',
        'action' => 'avaliou o produto',
        'target' => 'Notebook Gaming RGB',
        'time' => 'há 5 minutos',
        'icon' => 'fas fa-star',
        'color' => 'yellow'
    ],
    [
        'user' => 'Pedro Costa',
        'action' => 'adicionou ao carrinho',
        'target' => 'Headset Wireless Pro',
        'time' => 'há 8 minutos',
        'icon' => 'fas fa-cart-plus',
        'color' => 'blue'
    ],
    [
        'user' => 'Ana Oliveira',
        'action' => 'visualizou',
        'target' => 'Smartwatch Elite',
        'time' => 'há 12 minutos',
        'icon' => 'fas fa-eye',
        'color' => 'purple'
    ]
];

$categories = [
    [
        'id' => 1,
        'name' => 'Vestidos',
        'icon' => 'fas fa-tshirt',
        'image' => 'https://images.unsplash.com/photo-1566479179817-c0ae8e0d4b4e?w=300&h=200&fit=crop',
        'products_count' => 156,
        'color' => 'from-pink-500 to-rose-600'
    ],
    [
        'id' => 2,
        'name' => 'Blusas',
        'icon' => 'fas fa-user-tie',
        'image' => 'https://images.unsplash.com/photo-1581044777550-4cfa60707c03?w=300&h=200&fit=crop',
        'products_count' => 89,
        'color' => 'from-purple-500 to-indigo-600'
    ],
    [
        'id' => 3,
        'name' => 'Calças',
        'icon' => 'fas fa-cut',
        'image' => 'https://images.unsplash.com/photo-1594633312681-425c7b97ccd1?w=300&h=200&fit=crop',
        'products_count' => 234,
        'color' => 'from-blue-500 to-cyan-600'
    ],
    [
        'id' => 4,
        'name' => 'Acessórios',
        'icon' => 'fas fa-gem',
        'image' => 'https://images.unsplash.com/photo-1515562141207-7a88fb7ce338?w=300&h=200&fit=crop',
        'products_count' => 78,
        'color' => 'from-amber-500 to-orange-600'
    ],
    [
        'id' => 5,
        'name' => 'Sapatos',
        'icon' => 'fas fa-shoe-prints',
        'image' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=300&h=200&fit=crop',
        'products_count' => 145,
        'color' => 'from-red-500 to-pink-600'
    ],
    [
        'id' => 6,
        'name' => 'Bolsas',
        'icon' => 'fas fa-shopping-bag',
        'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=300&h=200&fit=crop',
        'products_count' => 203,
        'color' => 'from-emerald-500 to-teal-600'
    ]
];

ob_start();
?>

<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <!-- Background Gradient -->
    <div class="absolute inset-0 bg-gradient-to-br from-rose-100 via-pink-50 to-purple-100"></div>
    
    <!-- Animated Background Elements -->
    <div class="absolute inset-0">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-rose-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse"></div>
        <div class="absolute top-3/4 right-1/4 w-64 h-64 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse animation-delay-2000"></div>
        <div class="absolute bottom-1/4 left-1/2 w-64 h-64 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-20 animate-pulse animation-delay-4000"></div>
    </div>
    
    <div class="relative z-10 container mx-auto px-4 text-center">
        <h1 class="text-6xl md:text-8xl font-bold text-gray-800 mb-6 animate-fade-in">
            <span class="bg-gradient-to-r from-rose-600 via-pink-600 to-purple-600 bg-clip-text text-transparent">
                Style
            </span>
            <span class="text-gray-800">Hub</span>
        </h1>
        
        <p class="text-xl md:text-2xl text-gray-600 mb-8 max-w-3xl mx-auto animate-fade-in animation-delay-500">
            Descubra as últimas tendências da moda feminina com peças exclusivas e elegantes para todos os momentos.
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in animation-delay-1000">
            <a href="#featured" class="bg-gradient-to-r from-rose-500 to-pink-600 hover:from-rose-600 hover:to-pink-700 text-white px-8 py-4 text-lg font-semibold rounded-full hover:scale-105 transition-all duration-300">
                <i class="fas fa-shopping-bag mr-2"></i>
                Explorar Coleção
            </a>
            <a href="#categories" class="border-2 border-rose-300 hover:border-rose-500 text-rose-600 hover:text-rose-700 px-8 py-4 text-lg font-semibold rounded-full hover:scale-105 transition-all duration-300">
                <i class="fas fa-th-large mr-2"></i>
                Ver Categorias
            </a>
        </div>
        
        <!-- Stats -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mt-20 animate-fade-in animation-delay-1500">
            <div class="text-center">
                <div class="text-3xl font-bold text-rose-500 mb-2">5K+</div>
                <div class="text-gray-600">Peças</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-pink-500 mb-2">25K+</div>
                <div class="text-gray-600">Clientes</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-purple-500 mb-2">98%</div>
                <div class="text-gray-600">Satisfação</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-rose-400 mb-2">24/7</div>
                <div class="text-gray-600">Atendimento</div>
            </div>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
        <i class="fas fa-chevron-down text-gray-600 text-2xl opacity-60"></i>
    </div>
</section>
<!-- Featured Products Section -->
<section id="featured" class="py-32 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-20">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">
                Peças em <span class="bg-gradient-to-r from-rose-500 to-pink-600 bg-clip-text text-transparent">Destaque</span>
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Descubra nossa seleção especial de peças com as melhores ofertas e os looks mais modernos da temporada
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($featured_products as $product): ?>
                <div class="group relative bg-gray-800 rounded-2xl overflow-hidden hover:transform hover:scale-105 transition-all duration-300 card-shadow">
                    <!-- Product Badge -->
                    <div class="absolute top-4 left-4 z-10">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full <?= $product['badge'] === 'Destaque' ? 'bg-purple-500 text-white' : ($product['badge'] === 'Mais Vendido' ? 'bg-green-500 text-white' : ($product['badge'] === 'Oferta' ? 'bg-red-500 text-white' : 'bg-cyan-500 text-white')) ?>">
                            <?= htmlspecialchars($product['badge']) ?>
                        </span>
                    </div>
                    
                    <!-- Discount Badge -->
                    <?php if ($product['discount']): ?>
                        <div class="absolute top-4 right-4 z-10">
                            <span class="px-2 py-1 text-xs font-bold bg-red-500 text-white rounded-full">
                                -<?= $product['discount'] ?>%
                            </span>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Product Image -->
                    <div class="relative h-64 bg-gradient-to-br from-gray-700 to-gray-800 flex items-center justify-center">
                        <div class="w-32 h-32 bg-gradient-to-br from-purple-500 to-cyan-500 rounded-lg opacity-20"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i class="fas fa-mobile-alt text-6xl text-gray-400"></i>
                        </div>
                    </div>
                    
                    <!-- Product Info -->
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-white mb-2 group-hover:text-cyan-400 transition-colors">
                            <?= htmlspecialchars($product['name']) ?>
                        </h3>
                        
                        <!-- Rating -->
                        <div class="flex items-center mb-3">
                            <div class="flex text-yellow-400 mr-2">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?= $i <= floor($product['rating']) ? '' : 'opacity-30' ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <span class="text-sm text-gray-400">
                                <?= $product['rating'] ?> (<?= $product['reviews'] ?> avaliações)
                            </span>
                        </div>
                        
                        <!-- Price -->
                        <div class="mb-4">
                            <div class="flex items-center gap-2">
                                <span class="text-2xl font-bold text-cyan-400">
                                    R$ <?= number_format($product['price'], 2, ',', '.') ?>
                                </span>
                                <?php if ($product['original_price'] > $product['price']): ?>
                                    <span class="text-sm text-gray-500 line-through">
                                        R$ <?= number_format($product['original_price'], 2, ',', '.') ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex gap-2">
                            <button class="flex-1 btn-primary py-2 text-sm font-medium rounded-lg hover:bg-purple-600 transition-colors">
                                <i class="fas fa-shopping-cart mr-1"></i>
                                Comprar
                            </button>
                            <button class="p-2 text-gray-400 hover:text-red-400 transition-colors">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-12">
            <a href="/produtos" class="btn-secondary px-8 py-3 font-semibold rounded-full hover:scale-105 transition-all duration-300">
                Ver Todos os Produtos
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section id="categories" class="py-32 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-20">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">
                Explore por <span class="bg-gradient-to-r from-purple-500 to-pink-600 bg-clip-text text-transparent">Categoria</span>
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Encontre exatamente o que você procura navegando por nossas categorias de moda feminina
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($categories as $category): ?>
                <a href="/categoria/<?= $category['id'] ?>" class="group relative bg-gradient-to-br <?= $category['color'] ?> rounded-2xl p-8 hover:transform hover:scale-105 transition-all duration-300 card-shadow overflow-hidden">
                    <!-- Background Pattern -->
                    <div class="absolute inset-0 opacity-10">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-white rounded-full transform translate-x-16 -translate-y-16"></div>
                        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white rounded-full transform -translate-x-12 translate-y-12"></div>
                    </div>
                    
                    <div class="relative z-10">
                        <div class="text-4xl text-white mb-4">
                            <i class="<?= $category['icon'] ?>"></i>
                        </div>
                        
                        <h3 class="text-2xl font-bold text-white mb-2 group-hover:text-gray-100 transition-colors">
                            <?= htmlspecialchars($category['name']) ?>
                        </h3>
                        
                        <p class="text-white/80 mb-4">
                            <?= $category['products_count'] ?> produtos disponíveis
                        </p>
                        
                        <div class="flex items-center text-white/90 group-hover:text-white transition-colors">
                            <span class="font-medium">Explorar categoria</span>
                            <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- New Products Section -->
<section class="py-32 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-20">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">
                Novos <span class="bg-gradient-to-r from-amber-500 to-rose-500 bg-clip-text text-transparent">Lançamentos</span>
            </h2>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                Seja a primeira a descobrir as peças mais recentes e as tendências que estão chegando
            </p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <?php foreach ($new_products as $product): ?>
                <div class="group relative bg-gray-800 rounded-2xl overflow-hidden hover:transform hover:scale-105 transition-all duration-300 card-shadow">
                    <!-- New Badge -->
                    <div class="absolute top-4 left-4 z-10">
                        <span class="px-3 py-1 text-xs font-semibold bg-green-500 text-white rounded-full">
                            Novo
                        </span>
                    </div>
                    
                    <!-- Product Image -->
                    <div class="relative h-64 bg-gradient-to-br from-gray-700 to-gray-800 flex items-center justify-center">
                        <div class="w-32 h-32 bg-gradient-to-br from-green-500 to-yellow-500 rounded-lg opacity-20"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i class="fas fa-cube text-6xl text-gray-400"></i>
                        </div>
                    </div>
                    
                    <!-- Product Info -->
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-white mb-2 group-hover:text-green-400 transition-colors">
                            <?= htmlspecialchars($product['name']) ?>
                        </h3>
                        
                        <!-- Rating -->
                        <div class="flex items-center mb-3">
                            <div class="flex text-yellow-400 mr-2">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?= $i <= floor($product['rating']) ? '' : 'opacity-30' ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <span class="text-sm text-gray-400">
                                <?= $product['rating'] ?> (<?= $product['reviews'] ?> avaliações)
                            </span>
                        </div>
                        
                        <!-- Price -->
                        <div class="mb-4">
                            <span class="text-2xl font-bold text-green-400">
                                R$ <?= number_format($product['price'], 2, ',', '.') ?>
                            </span>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex gap-2">
                            <button class="flex-1 btn-primary py-2 text-sm font-medium rounded-lg hover:bg-green-600 transition-colors">
                                <i class="fas fa-shopping-cart mr-1"></i>
                                Comprar
                            </button>
                            <button class="p-2 text-gray-400 hover:text-red-400 transition-colors">
                                <i class="fas fa-heart"></i>
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-12">
            <a href="/novidades" class="btn-secondary px-8 py-3 font-semibold rounded-full hover:scale-105 transition-all duration-300">
                Ver Todos os Lançamentos
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="py-32 bg-gradient-to-r from-rose-100 via-pink-50 to-purple-100">
    <div class="container mx-auto px-4 text-center">
        <div class="max-w-3xl mx-auto">
            <h2 class="text-4xl md:text-5xl font-bold text-gray-800 mb-6">
                Fique por <span class="bg-gradient-to-r from-rose-500 to-pink-600 bg-clip-text text-transparent">Dentro</span>
            </h2>
            <p class="text-xl text-gray-600 mb-8">
                Receba as últimas tendências, ofertas exclusivas e lançamentos de moda diretamente no seu e-mail
            </p>
            
            <form class="flex flex-col sm:flex-row gap-4 max-w-md mx-auto" onsubmit="subscribeNewsletter(event)">
                <input 
                    type="email" 
                    placeholder="Seu melhor e-mail" 
                    class="flex-1 px-6 py-4 rounded-full bg-white border border-rose-200 text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-rose-400 focus:border-rose-400"
                    required
                >
                <button 
                    type="submit" 
                    class="bg-gradient-to-r from-rose-500 to-pink-600 hover:from-rose-600 hover:to-pink-700 px-8 py-4 rounded-full font-semibold text-white transition-all duration-300 transform hover:scale-105"
                >
                    <i class="fas fa-heart mr-2"></i>
                    Inscrever-se
                </button>
            </form>
            
            <p class="text-sm text-gray-500 mt-4">
                Não enviamos spam. Você pode cancelar a qualquer momento.
            </p>
        </div>
    </div>
</section>

<style>
/* Custom Animations */
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.8s ease-out forwards;
}

.animation-delay-500 {
    animation-delay: 0.5s;
    opacity: 0;
}

.animation-delay-1000 {
    animation-delay: 1s;
    opacity: 0;
}

.animation-delay-1500 {
    animation-delay: 1.5s;
    opacity: 0;
}

.animation-delay-2000 {
    animation-delay: 2s;
}

.animation-delay-4000 {
    animation-delay: 4s;
}

.btn-primary {
    @apply bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-medium transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2;
}

.btn-secondary {
    @apply bg-white/10 hover:bg-white/20 text-white font-medium border border-white/20 hover:border-white/30 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-white/50 backdrop-blur-sm;
}
</style>

<script>
function subscribeNewsletter(event) {
    event.preventDefault();
    const email = event.target.querySelector('input[type="email"]').value;
    
    // Simular inscrição
    if (window.showSuccess) {
        window.showSuccess('Inscrição realizada com sucesso! Bem-vindo à NeonShop.');
    }
    
    event.target.reset();
}

// Smooth scroll para links internos
document.addEventListener('DOMContentLoaded', function() {
    const links = document.querySelectorAll('a[href^="#"]');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            const targetSection = document.querySelector(targetId);
            
            if (targetSection) {
                targetSection.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>