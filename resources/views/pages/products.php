<?php
// Configurações da página
$page_title = 'Produtos - NeonShop';
$current_route = '/produtos';
$breadcrumbs = [
    ['name' => 'Home', 'url' => '/'],
    ['name' => 'Produtos', 'url' => '/produtos']
];

// Dados de exemplo para produtos
$products = [
    [
        'id' => 1,
        'name' => 'iPhone 15 Pro Max',
        'price' => 8999.99,
        'original_price' => 9999.99,
        'rating' => 4.8,
        'reviews' => 245,
        'category' => 'Smartphones',
        'brand' => 'Apple',
        'discount' => 10,
        'badge' => 'Mais Vendido',
        'in_stock' => true,
        'image' => '/images/products/iphone-15-pro.jpg'
    ],
    [
        'id' => 2,
        'name' => 'Samsung Galaxy S24 Ultra',
        'price' => 7499.99,
        'original_price' => 7999.99,
        'rating' => 4.7,
        'reviews' => 189,
        'category' => 'Smartphones',
        'brand' => 'Samsung',
        'discount' => 6,
        'badge' => 'Destaque',
        'in_stock' => true,
        'image' => '/images/products/galaxy-s24.jpg'
    ],
    [
        'id' => 3,
        'name' => 'MacBook Pro M3',
        'price' => 12999.99,
        'original_price' => 0,
        'rating' => 4.9,
        'reviews' => 156,
        'category' => 'Notebooks',
        'brand' => 'Apple',
        'discount' => 0,
        'badge' => 'Novo',
        'in_stock' => true,
        'image' => '/images/products/macbook-pro-m3.jpg'
    ],
    [
        'id' => 4,
        'name' => 'Dell XPS 13',
        'price' => 6999.99,
        'original_price' => 7499.99,
        'rating' => 4.6,
        'reviews' => 98,
        'category' => 'Notebooks',
        'brand' => 'Dell',
        'discount' => 7,
        'badge' => 'Oferta',
        'in_stock' => true,
        'image' => '/images/products/dell-xps-13.jpg'
    ],
    [
        'id' => 5,
        'name' => 'iPad Pro 12.9"',
        'price' => 5999.99,
        'original_price' => 6499.99,
        'rating' => 4.8,
        'reviews' => 134,
        'category' => 'Tablets',
        'brand' => 'Apple',
        'discount' => 8,
        'badge' => 'Destaque',
        'in_stock' => true,
        'image' => '/images/products/ipad-pro.jpg'
    ],
    [
        'id' => 6,
        'name' => 'AirPods Pro 2',
        'price' => 1899.99,
        'original_price' => 2199.99,
        'rating' => 4.7,
        'reviews' => 312,
        'category' => 'Acessórios',
        'brand' => 'Apple',
        'discount' => 14,
        'badge' => 'Oferta',
        'in_stock' => true,
        'image' => '/images/products/airpods-pro.jpg'
    ],
    [
        'id' => 7,
        'name' => 'Sony WH-1000XM5',
        'price' => 1599.99,
        'original_price' => 1799.99,
        'rating' => 4.6,
        'reviews' => 87,
        'category' => 'Acessórios',
        'brand' => 'Sony',
        'discount' => 11,
        'badge' => 'Mais Vendido',
        'in_stock' => false,
        'image' => '/images/products/sony-wh1000xm5.jpg'
    ],
    [
        'id' => 8,
        'name' => 'Nintendo Switch OLED',
        'price' => 2299.99,
        'original_price' => 0,
        'rating' => 4.8,
        'reviews' => 203,
        'category' => 'Games',
        'brand' => 'Nintendo',
        'discount' => 0,
        'badge' => 'Novo',
        'in_stock' => true,
        'image' => '/images/products/nintendo-switch.jpg'
    ]
];

// Filtros disponíveis
$categories = [
    ['id' => 'smartphones', 'name' => 'Smartphones', 'count' => 2],
    ['id' => 'notebooks', 'name' => 'Notebooks', 'count' => 2],
    ['id' => 'tablets', 'name' => 'Tablets', 'count' => 1],
    ['id' => 'acessorios', 'name' => 'Acessórios', 'count' => 2],
    ['id' => 'games', 'name' => 'Games', 'count' => 1]
];

$brands = [
    ['id' => 'apple', 'name' => 'Apple', 'count' => 4],
    ['id' => 'samsung', 'name' => 'Samsung', 'count' => 1],
    ['id' => 'dell', 'name' => 'Dell', 'count' => 1],
    ['id' => 'sony', 'name' => 'Sony', 'count' => 1],
    ['id' => 'nintendo', 'name' => 'Nintendo', 'count' => 1]
];

$price_ranges = [
    ['id' => '0-1000', 'name' => 'Até R$ 1.000', 'min' => 0, 'max' => 1000],
    ['id' => '1000-3000', 'name' => 'R$ 1.000 - R$ 3.000', 'min' => 1000, 'max' => 3000],
    ['id' => '3000-5000', 'name' => 'R$ 3.000 - R$ 5.000', 'min' => 3000, 'max' => 5000],
    ['id' => '5000-8000', 'name' => 'R$ 5.000 - R$ 8.000', 'min' => 5000, 'max' => 8000],
    ['id' => '8000+', 'name' => 'Acima de R$ 8.000', 'min' => 8000, 'max' => 999999]
];

// Paginação
$current_page = 1;
$per_page = 12;
$total_products = count($products);
$total_pages = ceil($total_products / $per_page);

// Conteúdo da página
ob_start();
?>

<!-- Page Header -->
<div class="bg-gradient-to-r from-gray-900 via-purple-900 to-gray-900 py-16">
    <div class="container mx-auto px-4">
        <div class="text-center">
            <h1 class="text-4xl md:text-6xl font-bold text-white mb-4">
                Nossos <span class="bg-gradient-to-r from-purple-400 to-cyan-400 bg-clip-text text-transparent">Produtos</span>
            </h1>
            <p class="text-xl text-gray-300 max-w-2xl mx-auto">
                Descubra nossa seleção completa de produtos tecnológicos com as melhores ofertas
            </p>
        </div>
    </div>
</div>

<!-- Filters and Products -->
<div class="bg-gray-900 min-h-screen py-12">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            
            <!-- Sidebar Filters -->
            <div class="lg:w-1/4">
                <div class="bg-gray-800 rounded-2xl p-6 card-shadow sticky top-4">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-white">Filtros</h2>
                        <button onclick="clearFilters()" class="text-sm text-cyan-400 hover:text-cyan-300 transition-colors">
                            Limpar
                        </button>
                    </div>
                    
                    <!-- Search Filter -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-300 mb-2">Buscar Produto</label>
                        <input 
                            type="text" 
                            id="search-filter"
                            placeholder="Digite o nome do produto..."
                            class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent"
                            oninput="filterProducts()"
                        >
                    </div>
                    
                    <!-- Category Filter -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-white mb-3">Categorias</h3>
                        <div class="space-y-2">
                            <?php foreach ($categories as $category): ?>
                                <label class="flex items-center cursor-pointer group">
                                    <input 
                                        type="checkbox" 
                                        name="category" 
                                        value="<?= $category['id'] ?>"
                                        class="sr-only"
                                        onchange="filterProducts()"
                                    >
                                    <div class="w-4 h-4 border-2 border-gray-500 rounded mr-3 flex items-center justify-center group-hover:border-cyan-400 transition-colors">
                                        <div class="w-2 h-2 bg-cyan-400 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    </div>
                                    <span class="text-gray-300 group-hover:text-white transition-colors flex-1">
                                        <?= htmlspecialchars($category['name']) ?>
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        (<?= $category['count'] ?>)
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Brand Filter -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-white mb-3">Marcas</h3>
                        <div class="space-y-2">
                            <?php foreach ($brands as $brand): ?>
                                <label class="flex items-center cursor-pointer group">
                                    <input 
                                        type="checkbox" 
                                        name="brand" 
                                        value="<?= $brand['id'] ?>"
                                        class="sr-only"
                                        onchange="filterProducts()"
                                    >
                                    <div class="w-4 h-4 border-2 border-gray-500 rounded mr-3 flex items-center justify-center group-hover:border-purple-400 transition-colors">
                                        <div class="w-2 h-2 bg-purple-400 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    </div>
                                    <span class="text-gray-300 group-hover:text-white transition-colors flex-1">
                                        <?= htmlspecialchars($brand['name']) ?>
                                    </span>
                                    <span class="text-sm text-gray-500">
                                        (<?= $brand['count'] ?>)
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Price Range Filter -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-white mb-3">Faixa de Preço</h3>
                        <div class="space-y-2">
                            <?php foreach ($price_ranges as $range): ?>
                                <label class="flex items-center cursor-pointer group">
                                    <input 
                                        type="radio" 
                                        name="price_range" 
                                        value="<?= $range['id'] ?>"
                                        data-min="<?= $range['min'] ?>"
                                        data-max="<?= $range['max'] ?>"
                                        class="sr-only"
                                        onchange="filterProducts()"
                                    >
                                    <div class="w-4 h-4 border-2 border-gray-500 rounded-full mr-3 flex items-center justify-center group-hover:border-green-400 transition-colors">
                                        <div class="w-2 h-2 bg-green-400 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    </div>
                                    <span class="text-gray-300 group-hover:text-white transition-colors">
                                        <?= htmlspecialchars($range['name']) ?>
                                    </span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Stock Filter -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-white mb-3">Disponibilidade</h3>
                        <label class="flex items-center cursor-pointer group">
                            <input 
                                type="checkbox" 
                                id="in-stock-only"
                                class="sr-only"
                                onchange="filterProducts()"
                            >
                            <div class="w-4 h-4 border-2 border-gray-500 rounded mr-3 flex items-center justify-center group-hover:border-yellow-400 transition-colors">
                                <div class="w-2 h-2 bg-yellow-400 rounded-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            </div>
                            <span class="text-gray-300 group-hover:text-white transition-colors">
                                Apenas em estoque
                            </span>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Products Grid -->
            <div class="lg:w-3/4">
                <!-- Toolbar -->
                <div class="bg-gray-800 rounded-2xl p-4 mb-6 card-shadow">
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <span class="text-gray-300">
                                <span id="products-count"><?= count($products) ?></span> produtos encontrados
                            </span>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <!-- View Toggle -->
                            <div class="flex bg-gray-700 rounded-lg p-1">
                                <button 
                                    onclick="setView('grid')" 
                                    id="grid-view-btn"
                                    class="px-3 py-1 rounded text-sm font-medium transition-colors bg-cyan-500 text-white"
                                >
                                    <i class="fas fa-th-large"></i>
                                </button>
                                <button 
                                    onclick="setView('list')" 
                                    id="list-view-btn"
                                    class="px-3 py-1 rounded text-sm font-medium transition-colors text-gray-300 hover:text-white"
                                >
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>
                            
                            <!-- Sort Options -->
                            <select 
                                id="sort-select"
                                onchange="sortProducts()"
                                class="bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent"
                            >
                                <option value="name-asc">Nome A-Z</option>
                                <option value="name-desc">Nome Z-A</option>
                                <option value="price-asc">Menor Preço</option>
                                <option value="price-desc">Maior Preço</option>
                                <option value="rating-desc">Melhor Avaliação</option>
                                <option value="newest">Mais Recentes</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <!-- Products Grid -->
                <div id="products-grid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    <?php foreach ($products as $product): ?>
                        <div class="product-card group relative bg-gray-800 rounded-2xl overflow-hidden hover:transform hover:scale-105 transition-all duration-300 card-shadow"
                             data-name="<?= strtolower($product['name']) ?>"
                             data-category="<?= strtolower($product['category']) ?>"
                             data-brand="<?= strtolower($product['brand']) ?>"
                             data-price="<?= $product['price'] ?>"
                             data-rating="<?= $product['rating'] ?>"
                             data-in-stock="<?= $product['in_stock'] ? 'true' : 'false' ?>">
                            
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
                            
                            <!-- Stock Status -->
                            <?php if (!$product['in_stock']): ?>
                                <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center z-20">
                                    <span class="bg-red-500 text-white px-4 py-2 rounded-full font-semibold">
                                        Fora de Estoque
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
                                
                                <!-- Category and Brand -->
                                <p class="text-sm text-gray-400 mb-3">
                                    <?= htmlspecialchars($product['category']) ?> • <?= htmlspecialchars($product['brand']) ?>
                                </p>
                                
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
                                    <button 
                                        onclick="addToCart(<?= $product['id'] ?>)"
                                        class="flex-1 btn-primary py-2 text-sm font-medium rounded-lg transition-colors <?= $product['in_stock'] ? 'hover:bg-purple-600' : 'opacity-50 cursor-not-allowed' ?>"
                                        <?= $product['in_stock'] ? '' : 'disabled' ?>
                                    >
                                        <i class="fas fa-shopping-cart mr-1"></i>
                                        <?= $product['in_stock'] ? 'Comprar' : 'Indisponível' ?>
                                    </button>
                                    <button 
                                        onclick="toggleWishlist(<?= $product['id'] ?>)"
                                        class="p-2 text-gray-400 hover:text-red-400 transition-colors"
                                    >
                                        <i class="fas fa-heart"></i>
                                    </button>
                                    <button 
                                        onclick="viewProduct(<?= $product['id'] ?>)"
                                        class="p-2 text-gray-400 hover:text-cyan-400 transition-colors"
                                    >
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- No Results Message -->
                <div id="no-results" class="hidden text-center py-16">
                    <div class="text-6xl text-gray-600 mb-4">
                        <i class="fas fa-search"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Nenhum produto encontrado</h3>
                    <p class="text-gray-400 mb-6">Tente ajustar os filtros ou buscar por outros termos</p>
                    <button onclick="clearFilters()" class="btn-primary px-6 py-3 rounded-lg">
                        Limpar Filtros
                    </button>
                </div>
                
                <!-- Pagination -->
                <div id="pagination" class="flex justify-center items-center gap-2">
                    <button class="px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <button class="px-4 py-2 rounded-lg transition-colors <?= $i === $current_page ? 'bg-cyan-500 text-white' : 'bg-gray-700 text-gray-300 hover:bg-gray-600' ?>">
                            <?= $i ?>
                        </button>
                    <?php endfor; ?>
                    
                    <button class="px-4 py-2 bg-gray-700 text-gray-300 rounded-lg hover:bg-gray-600 transition-colors">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn-primary {
    @apply bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-medium transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2;
}

.card-shadow {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.05);
}

/* Custom checkbox/radio styles */
input[type="checkbox"]:checked + div,
input[type="radio"]:checked + div {
    @apply border-cyan-400;
}

input[type="checkbox"]:checked + div > div,
input[type="radio"]:checked + div > div {
    @apply opacity-100;
}

/* List view styles */
.list-view .product-card {
    @apply flex flex-row;
}

.list-view .product-card .relative {
    @apply w-48 h-32 flex-shrink-0;
}

.list-view .product-card .p-6 {
    @apply flex-1 flex flex-col justify-between;
}
</style>

<script>
let currentView = 'grid';
let allProducts = <?= json_encode($products) ?>;
let filteredProducts = [...allProducts];

function setView(view) {
    currentView = view;
    const gridBtn = document.getElementById('grid-view-btn');
    const listBtn = document.getElementById('list-view-btn');
    const productsGrid = document.getElementById('products-grid');
    
    if (view === 'grid') {
        gridBtn.className = 'px-3 py-1 rounded text-sm font-medium transition-colors bg-cyan-500 text-white';
        listBtn.className = 'px-3 py-1 rounded text-sm font-medium transition-colors text-gray-300 hover:text-white';
        productsGrid.className = 'grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8';
        productsGrid.classList.remove('list-view');
    } else {
        listBtn.className = 'px-3 py-1 rounded text-sm font-medium transition-colors bg-cyan-500 text-white';
        gridBtn.className = 'px-3 py-1 rounded text-sm font-medium transition-colors text-gray-300 hover:text-white';
        productsGrid.className = 'space-y-4 mb-8 list-view';
        productsGrid.classList.add('list-view');
    }
}

function filterProducts() {
    const searchTerm = document.getElementById('search-filter').value.toLowerCase();
    const selectedCategories = Array.from(document.querySelectorAll('input[name="category"]:checked')).map(cb => cb.value);
    const selectedBrands = Array.from(document.querySelectorAll('input[name="brand"]:checked')).map(cb => cb.value);
    const selectedPriceRange = document.querySelector('input[name="price_range"]:checked');
    const inStockOnly = document.getElementById('in-stock-only').checked;
    
    const productCards = document.querySelectorAll('.product-card');
    let visibleCount = 0;
    
    productCards.forEach(card => {
        const name = card.dataset.name;
        const category = card.dataset.category;
        const brand = card.dataset.brand;
        const price = parseFloat(card.dataset.price);
        const inStock = card.dataset.inStock === 'true';
        
        let show = true;
        
        // Search filter
        if (searchTerm && !name.includes(searchTerm)) {
            show = false;
        }
        
        // Category filter
        if (selectedCategories.length > 0 && !selectedCategories.includes(category)) {
            show = false;
        }
        
        // Brand filter
        if (selectedBrands.length > 0 && !selectedBrands.includes(brand)) {
            show = false;
        }
        
        // Price range filter
        if (selectedPriceRange) {
            const minPrice = parseFloat(selectedPriceRange.dataset.min);
            const maxPrice = parseFloat(selectedPriceRange.dataset.max);
            if (price < minPrice || (maxPrice < 999999 && price > maxPrice)) {
                show = false;
            }
        }
        
        // Stock filter
        if (inStockOnly && !inStock) {
            show = false;
        }
        
        if (show) {
            card.style.display = 'block';
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });
    
    // Update products count
    document.getElementById('products-count').textContent = visibleCount;
    
    // Show/hide no results message
    const noResults = document.getElementById('no-results');
    const pagination = document.getElementById('pagination');
    
    if (visibleCount === 0) {
        noResults.classList.remove('hidden');
        pagination.style.display = 'none';
    } else {
        noResults.classList.add('hidden');
        pagination.style.display = 'flex';
    }
}

function sortProducts() {
    const sortValue = document.getElementById('sort-select').value;
    const productsGrid = document.getElementById('products-grid');
    const productCards = Array.from(document.querySelectorAll('.product-card'));
    
    productCards.sort((a, b) => {
        switch (sortValue) {
            case 'name-asc':
                return a.dataset.name.localeCompare(b.dataset.name);
            case 'name-desc':
                return b.dataset.name.localeCompare(a.dataset.name);
            case 'price-asc':
                return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
            case 'price-desc':
                return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
            case 'rating-desc':
                return parseFloat(b.dataset.rating) - parseFloat(a.dataset.rating);
            case 'newest':
                // Simulate newest by reversing order
                return Math.random() - 0.5;
            default:
                return 0;
        }
    });
    
    // Re-append sorted cards
    productCards.forEach(card => {
        productsGrid.appendChild(card);
    });
}

function clearFilters() {
    // Clear search
    document.getElementById('search-filter').value = '';
    
    // Clear checkboxes and radios
    document.querySelectorAll('input[type="checkbox"], input[type="radio"]').forEach(input => {
        input.checked = false;
    });
    
    // Reset sort
    document.getElementById('sort-select').value = 'name-asc';
    
    // Re-filter products
    filterProducts();
    sortProducts();
}

function addToCart(productId) {
    if (window.showSuccess) {
        window.showSuccess('Produto adicionado ao carrinho!');
    }
    console.log('Adicionando produto ao carrinho:', productId);
}

function toggleWishlist(productId) {
    if (window.showInfo) {
        window.showInfo('Produto adicionado aos favoritos!');
    }
    console.log('Alternando favorito:', productId);
}

function viewProduct(productId) {
    window.location.href = `/produto/${productId}`;
}

// Initialize filters on page load
document.addEventListener('DOMContentLoaded', function() {
    filterProducts();
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>