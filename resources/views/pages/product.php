<?php
// Configurações da página
$product_id = $_GET['id'] ?? 1;
$page_title = 'iPhone 15 Pro Max - NeonShop';
$current_route = '/produto/' . $product_id;
$breadcrumbs = [
    ['name' => 'Home', 'url' => '/'],
    ['name' => 'Produtos', 'url' => '/produtos'],
    ['name' => 'iPhone 15 Pro Max', 'url' => '/produto/' . $product_id]
];

// Dados do produto
$product = [
    'id' => 1,
    'name' => 'iPhone 15 Pro Max',
    'brand' => 'Apple',
    'model' => 'A3108',
    'sku' => 'IPH15PM-256-TIT',
    'price' => 8999.99,
    'original_price' => 9999.99,
    'discount' => 10,
    'rating' => 4.8,
    'reviews' => 245,
    'category' => 'Smartphones',
    'in_stock' => true,
    'stock_quantity' => 15,
    'description' => 'O iPhone 15 Pro Max representa o ápice da inovação da Apple, combinando design premium em titânio com o poderoso chip A17 Pro. Experimente fotografia profissional com o sistema de câmeras mais avançado já criado pela Apple.',
    'features' => [
        'Chip A17 Pro com GPU de 6 núcleos',
        'Sistema de câmeras Pro com teleobjetiva 5x',
        'Tela Super Retina XDR de 6,7 polegadas',
        'Design em titânio aeroespacial',
        'Conector USB-C com Thunderbolt 3',
        'Bateria com até 29 horas de reprodução de vídeo',
        'Resistente à água IP68',
        'Face ID avançado'
    ],
    'specifications' => [
        'Tela' => '6,7" Super Retina XDR OLED',
        'Resolução' => '2796 x 1290 pixels (460 ppi)',
        'Processador' => 'Apple A17 Pro (3nm)',
        'RAM' => '8GB',
        'Câmera Principal' => '48MP f/1.78',
        'Câmera Ultra Wide' => '12MP f/2.2',
        'Câmera Teleobjetiva' => '12MP f/2.8 (5x zoom)',
        'Câmera Frontal' => '12MP f/1.9',
        'Bateria' => '4441 mAh',
        'Sistema Operacional' => 'iOS 17',
        'Conectividade' => '5G, Wi-Fi 6E, Bluetooth 5.3',
        'Dimensões' => '159.9 x 76.7 x 8.25 mm',
        'Peso' => '221g'
    ],
    'images' => [
        '/images/products/iphone-15-pro-1.jpg',
        '/images/products/iphone-15-pro-2.jpg',
        '/images/products/iphone-15-pro-3.jpg',
        '/images/products/iphone-15-pro-4.jpg',
        '/images/products/iphone-15-pro-5.jpg'
    ],
    'variations' => [
        'storage' => [
            ['id' => '256gb', 'name' => '256GB', 'price' => 8999.99, 'stock' => 15],
            ['id' => '512gb', 'name' => '512GB', 'price' => 10499.99, 'stock' => 8],
            ['id' => '1tb', 'name' => '1TB', 'price' => 12999.99, 'stock' => 3]
        ],
        'color' => [
            ['id' => 'titanium', 'name' => 'Titânio Natural', 'hex' => '#8E8E93', 'stock' => 15],
            ['id' => 'blue', 'name' => 'Titânio Azul', 'hex' => '#395B7A', 'stock' => 12],
            ['id' => 'white', 'name' => 'Titânio Branco', 'hex' => '#F7F7F7', 'stock' => 10],
            ['id' => 'black', 'name' => 'Titânio Preto', 'hex' => '#1C1C1E', 'stock' => 18]
        ]
    ]
];

// Produtos relacionados
$related_products = [
    [
        'id' => 2,
        'name' => 'iPhone 15 Pro',
        'price' => 7499.99,
        'original_price' => 7999.99,
        'rating' => 4.7,
        'reviews' => 189,
        'image' => '/images/products/iphone-15-pro.jpg'
    ],
    [
        'id' => 3,
        'name' => 'AirPods Pro 2',
        'price' => 1899.99,
        'original_price' => 2199.99,
        'rating' => 4.8,
        'reviews' => 312,
        'image' => '/images/products/airpods-pro.jpg'
    ],
    [
        'id' => 4,
        'name' => 'MagSafe Charger',
        'price' => 299.99,
        'original_price' => 0,
        'rating' => 4.5,
        'reviews' => 156,
        'image' => '/images/products/magsafe.jpg'
    ],
    [
        'id' => 5,
        'name' => 'iPhone 15 Case',
        'price' => 399.99,
        'original_price' => 0,
        'rating' => 4.6,
        'reviews' => 89,
        'image' => '/images/products/iphone-case.jpg'
    ]
];

// Avaliações
$reviews = [
    [
        'id' => 1,
        'user' => 'João Silva',
        'rating' => 5,
        'date' => '2024-01-15',
        'title' => 'Excelente produto!',
        'comment' => 'O iPhone 15 Pro Max superou todas as minhas expectativas. A qualidade da câmera é impressionante e a bateria dura o dia todo.',
        'verified' => true
    ],
    [
        'id' => 2,
        'user' => 'Maria Santos',
        'rating' => 4,
        'date' => '2024-01-10',
        'title' => 'Muito bom, mas caro',
        'comment' => 'Produto de excelente qualidade, mas o preço é bem salgado. Vale a pena para quem pode investir.',
        'verified' => true
    ],
    [
        'id' => 3,
        'user' => 'Pedro Costa',
        'rating' => 5,
        'date' => '2024-01-08',
        'title' => 'Melhor iPhone já feito',
        'comment' => 'A tela é linda, o desempenho é incrível e o design em titânio é premium. Recomendo!',
        'verified' => false
    ]
];

// Conteúdo da página
ob_start();
?>

<div class="bg-gray-900 min-h-screen py-8">
    <div class="container mx-auto px-4">
        
        <!-- Product Main Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
            
            <!-- Product Gallery -->
            <div class="space-y-4">
                <!-- Main Image -->
                <div class="relative bg-gray-800 rounded-2xl overflow-hidden card-shadow">
                    <div id="main-image" class="aspect-square bg-gradient-to-br from-gray-700 to-gray-800 flex items-center justify-center">
                        <div class="w-64 h-64 bg-gradient-to-br from-purple-500 to-cyan-500 rounded-lg opacity-20"></div>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i class="fas fa-mobile-alt text-8xl text-gray-400"></i>
                        </div>
                    </div>
                    
                    <!-- Zoom Button -->
                    <button 
                        onclick="openImageModal()"
                        class="absolute top-4 right-4 bg-black bg-opacity-50 text-white p-2 rounded-full hover:bg-opacity-70 transition-all"
                    >
                        <i class="fas fa-search-plus"></i>
                    </button>
                </div>
                
                <!-- Thumbnail Gallery -->
                <div class="grid grid-cols-5 gap-2">
                    <?php foreach ($product['images'] as $index => $image): ?>
                        <button 
                            onclick="changeMainImage(<?= $index ?>)"
                            class="thumbnail aspect-square bg-gray-800 rounded-lg overflow-hidden hover:ring-2 hover:ring-cyan-400 transition-all <?= $index === 0 ? 'ring-2 ring-cyan-400' : '' ?>"
                            data-index="<?= $index ?>"
                        >
                            <div class="w-full h-full bg-gradient-to-br from-gray-700 to-gray-800 flex items-center justify-center">
                                <i class="fas fa-mobile-alt text-2xl text-gray-400"></i>
                            </div>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- Product Info -->
            <div class="space-y-6">
                <!-- Product Header -->
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="text-sm text-cyan-400 font-medium"><?= htmlspecialchars($product['brand']) ?></span>
                        <span class="text-sm text-gray-500">•</span>
                        <span class="text-sm text-gray-400">SKU: <?= htmlspecialchars($product['sku']) ?></span>
                    </div>
                    
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-4">
                        <?= htmlspecialchars($product['name']) ?>
                    </h1>
                    
                    <!-- Rating -->
                    <div class="flex items-center gap-4 mb-4">
                        <div class="flex items-center">
                            <div class="flex text-yellow-400 mr-2">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="fas fa-star <?= $i <= floor($product['rating']) ? '' : 'opacity-30' ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <span class="text-white font-medium"><?= $product['rating'] ?></span>
                        </div>
                        <span class="text-gray-400">(<?= $product['reviews'] ?> avaliações)</span>
                    </div>
                </div>
                
                <!-- Price -->
                <div class="bg-gray-800 rounded-2xl p-6 card-shadow">
                    <div class="flex items-center gap-4 mb-4">
                        <span id="current-price" class="text-4xl font-bold text-cyan-400">
                            R$ <?= number_format($product['price'], 2, ',', '.') ?>
                        </span>
                        <?php if ($product['original_price'] > $product['price']): ?>
                            <div class="text-right">
                                <span class="text-lg text-gray-500 line-through block">
                                    R$ <?= number_format($product['original_price'], 2, ',', '.') ?>
                                </span>
                                <span class="text-sm bg-red-500 text-white px-2 py-1 rounded-full">
                                    -<?= $product['discount'] ?>%
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Stock Status -->
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                        <span class="text-green-400 font-medium">
                            Em estoque (<?= $product['stock_quantity'] ?> unidades)
                        </span>
                    </div>
                    
                    <!-- Payment Options -->
                    <div class="space-y-2 text-sm text-gray-300">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-credit-card text-cyan-400"></i>
                            <span>12x de R$ <?= number_format($product['price'] / 12, 2, ',', '.') ?> sem juros</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-barcode text-green-400"></i>
                            <span>5% de desconto no PIX: R$ <?= number_format($product['price'] * 0.95, 2, ',', '.') ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Product Variations -->
                <div class="space-y-6">
                    <!-- Storage Options -->
                    <div>
                        <h3 class="text-lg font-semibold text-white mb-3">Armazenamento</h3>
                        <div class="grid grid-cols-3 gap-3">
                            <?php foreach ($product['variations']['storage'] as $index => $storage): ?>
                                <button 
                                    onclick="selectStorage('<?= $storage['id'] ?>', <?= $storage['price'] ?>, <?= $storage['stock'] ?>)"
                                    class="storage-option p-3 border-2 border-gray-600 rounded-lg text-center hover:border-cyan-400 transition-colors <?= $index === 0 ? 'border-cyan-400 bg-cyan-400 bg-opacity-10' : '' ?>"
                                    data-storage="<?= $storage['id'] ?>"
                                >
                                    <div class="text-white font-medium"><?= $storage['name'] ?></div>
                                    <div class="text-sm text-gray-400">+R$ <?= number_format($storage['price'] - $product['price'], 2, ',', '.') ?></div>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Color Options -->
                    <div>
                        <h3 class="text-lg font-semibold text-white mb-3">Cor</h3>
                        <div class="flex gap-3">
                            <?php foreach ($product['variations']['color'] as $index => $color): ?>
                                <button 
                                    onclick="selectColor('<?= $color['id'] ?>', <?= $color['stock'] ?>)"
                                    class="color-option group relative w-12 h-12 rounded-full border-2 border-gray-600 hover:border-white transition-colors <?= $index === 0 ? 'border-white' : '' ?>"
                                    style="background-color: <?= $color['hex'] ?>"
                                    data-color="<?= $color['id'] ?>"
                                    title="<?= htmlspecialchars($color['name']) ?>"
                                >
                                    <div class="absolute -bottom-8 left-1/2 transform -translate-x-1/2 text-xs text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                        <?= htmlspecialchars($color['name']) ?>
                                    </div>
                                </button>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <!-- Quantity and Actions -->
                <div class="space-y-4">
                    <!-- Quantity Selector -->
                    <div class="flex items-center gap-4">
                        <label class="text-white font-medium">Quantidade:</label>
                        <div class="flex items-center bg-gray-800 rounded-lg">
                            <button 
                                onclick="changeQuantity(-1)"
                                class="px-4 py-2 text-gray-400 hover:text-white transition-colors"
                            >
                                <i class="fas fa-minus"></i>
                            </button>
                            <input 
                                type="number" 
                                id="quantity" 
                                value="1" 
                                min="1" 
                                max="<?= $product['stock_quantity'] ?>"
                                class="w-16 py-2 bg-transparent text-center text-white focus:outline-none"
                                onchange="validateQuantity()"
                            >
                            <button 
                                onclick="changeQuantity(1)"
                                class="px-4 py-2 text-gray-400 hover:text-white transition-colors"
                            >
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="space-y-3">
                        <button 
                            onclick="addToCart()"
                            class="w-full btn-primary py-4 text-lg font-semibold rounded-xl hover:scale-105 transition-all duration-300"
                        >
                            <i class="fas fa-shopping-cart mr-2"></i>
                            Adicionar ao Carrinho
                        </button>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <button 
                                onclick="buyNow()"
                                class="btn-secondary py-3 font-semibold rounded-xl hover:scale-105 transition-all duration-300"
                            >
                                <i class="fas fa-bolt mr-2"></i>
                                Comprar Agora
                            </button>
                            
                            <button 
                                onclick="toggleWishlist()"
                                class="bg-gray-800 hover:bg-gray-700 text-white py-3 font-semibold rounded-xl transition-colors"
                            >
                                <i class="fas fa-heart mr-2"></i>
                                Favoritar
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Shipping Calculator -->
                <div class="bg-gray-800 rounded-2xl p-6 card-shadow">
                    <h3 class="text-lg font-semibold text-white mb-4">
                        <i class="fas fa-truck mr-2 text-cyan-400"></i>
                        Calcular Frete
                    </h3>
                    
                    <div class="flex gap-3">
                        <input 
                            type="text" 
                            id="cep-input"
                            placeholder="Digite seu CEP"
                            maxlength="9"
                            class="flex-1 px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent"
                            oninput="formatCEP(this)"
                        >
                        <button 
                            onclick="calculateShipping()"
                            class="px-6 py-3 bg-cyan-500 hover:bg-cyan-600 text-white font-medium rounded-lg transition-colors"
                        >
                            Calcular
                        </button>
                    </div>
                    
                    <div id="shipping-results" class="hidden mt-4 space-y-3">
                        <!-- Shipping options will be populated here -->
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Product Details Tabs -->
        <div class="bg-gray-800 rounded-2xl card-shadow mb-16">
            <!-- Tab Navigation -->
            <div class="flex border-b border-gray-700">
                <button 
                    onclick="switchTab('description')"
                    class="tab-button px-6 py-4 font-medium transition-colors border-b-2 border-cyan-400 text-cyan-400"
                    data-tab="description"
                >
                    Descrição
                </button>
                <button 
                    onclick="switchTab('specifications')"
                    class="tab-button px-6 py-4 font-medium transition-colors border-b-2 border-transparent text-gray-400 hover:text-white"
                    data-tab="specifications"
                >
                    Especificações
                </button>
                <button 
                    onclick="switchTab('reviews')"
                    class="tab-button px-6 py-4 font-medium transition-colors border-b-2 border-transparent text-gray-400 hover:text-white"
                    data-tab="reviews"
                >
                    Avaliações (<?= count($reviews) ?>)
                </button>
            </div>
            
            <!-- Tab Content -->
            <div class="p-6">
                <!-- Description Tab -->
                <div id="description-tab" class="tab-content">
                    <div class="prose prose-invert max-w-none">
                        <p class="text-gray-300 text-lg leading-relaxed mb-6">
                            <?= htmlspecialchars($product['description']) ?>
                        </p>
                        
                        <h3 class="text-xl font-semibold text-white mb-4">Principais Características:</h3>
                        <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            <?php foreach ($product['features'] as $feature): ?>
                                <li class="flex items-center text-gray-300">
                                    <i class="fas fa-check text-green-400 mr-3"></i>
                                    <?= htmlspecialchars($feature) ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                
                <!-- Specifications Tab -->
                <div id="specifications-tab" class="tab-content hidden">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <?php foreach ($product['specifications'] as $spec => $value): ?>
                            <div class="flex justify-between items-center py-3 border-b border-gray-700">
                                <span class="text-gray-400 font-medium"><?= htmlspecialchars($spec) ?>:</span>
                                <span class="text-white"><?= htmlspecialchars($value) ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Reviews Tab -->
                <div id="reviews-tab" class="tab-content hidden">
                    <!-- Reviews Summary -->
                    <div class="bg-gray-700 rounded-xl p-6 mb-6">
                        <div class="flex items-center gap-8">
                            <div class="text-center">
                                <div class="text-4xl font-bold text-white mb-2"><?= $product['rating'] ?></div>
                                <div class="flex text-yellow-400 mb-2">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?= $i <= floor($product['rating']) ? '' : 'opacity-30' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <div class="text-sm text-gray-400"><?= $product['reviews'] ?> avaliações</div>
                            </div>
                            
                            <div class="flex-1">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <?php $percentage = rand(10, 90); ?>
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="text-sm text-gray-400 w-8"><?= $i ?> ★</span>
                                        <div class="flex-1 bg-gray-600 rounded-full h-2">
                                            <div class="bg-yellow-400 h-2 rounded-full" style="width: <?= $percentage ?>%"></div>
                                        </div>
                                        <span class="text-sm text-gray-400 w-12"><?= $percentage ?>%</span>
                                    </div>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Individual Reviews -->
                    <div class="space-y-6">
                        <?php foreach ($reviews as $review): ?>
                            <div class="bg-gray-700 rounded-xl p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-cyan-500 rounded-full flex items-center justify-center text-white font-semibold">
                                            <?= strtoupper(substr($review['user'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-white font-medium"><?= htmlspecialchars($review['user']) ?></span>
                                                <?php if ($review['verified']): ?>
                                                    <span class="text-xs bg-green-500 text-white px-2 py-1 rounded-full">Compra Verificada</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="flex items-center gap-2 mt-1">
                                                <div class="flex text-yellow-400">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <i class="fas fa-star <?= $i <= $review['rating'] ? '' : 'opacity-30' ?>"></i>
                                                    <?php endfor; ?>
                                                </div>
                                                <span class="text-sm text-gray-400"><?= date('d/m/Y', strtotime($review['date'])) ?></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <h4 class="text-white font-semibold mb-2"><?= htmlspecialchars($review['title']) ?></h4>
                                <p class="text-gray-300"><?= htmlspecialchars($review['comment']) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Products -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-white mb-8 text-center">
                Produtos <span class="bg-gradient-to-r from-purple-400 to-cyan-400 bg-clip-text text-transparent">Relacionados</span>
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($related_products as $related): ?>
                    <div class="group bg-gray-800 rounded-2xl overflow-hidden hover:transform hover:scale-105 transition-all duration-300 card-shadow">
                        <!-- Product Image -->
                        <div class="relative h-48 bg-gradient-to-br from-gray-700 to-gray-800 flex items-center justify-center">
                            <div class="w-24 h-24 bg-gradient-to-br from-purple-500 to-cyan-500 rounded-lg opacity-20"></div>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <i class="fas fa-mobile-alt text-4xl text-gray-400"></i>
                            </div>
                        </div>
                        
                        <!-- Product Info -->
                        <div class="p-4">
                            <h3 class="text-lg font-semibold text-white mb-2 group-hover:text-cyan-400 transition-colors">
                                <?= htmlspecialchars($related['name']) ?>
                            </h3>
                            
                            <!-- Rating -->
                            <div class="flex items-center mb-3">
                                <div class="flex text-yellow-400 mr-2">
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <i class="fas fa-star <?= $i <= floor($related['rating']) ? '' : 'opacity-30' ?>"></i>
                                    <?php endfor; ?>
                                </div>
                                <span class="text-sm text-gray-400">
                                    <?= $related['rating'] ?> (<?= $related['reviews'] ?>)
                                </span>
                            </div>
                            
                            <!-- Price -->
                            <div class="mb-4">
                                <div class="flex items-center gap-2">
                                    <span class="text-xl font-bold text-cyan-400">
                                        R$ <?= number_format($related['price'], 2, ',', '.') ?>
                                    </span>
                                    <?php if ($related['original_price'] > $related['price']): ?>
                                        <span class="text-sm text-gray-500 line-through">
                                            R$ <?= number_format($related['original_price'], 2, ',', '.') ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Action -->
                            <button 
                                onclick="viewProduct(<?= $related['id'] ?>)"
                                class="w-full btn-primary py-2 text-sm font-medium rounded-lg hover:bg-purple-600 transition-colors"
                            >
                                Ver Produto
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div id="image-modal" class="fixed inset-0 bg-black bg-opacity-90 z-50 hidden flex items-center justify-center p-4">
    <div class="relative max-w-4xl max-h-full">
        <button 
            onclick="closeImageModal()"
            class="absolute top-4 right-4 text-white text-2xl hover:text-gray-300 transition-colors z-10"
        >
            <i class="fas fa-times"></i>
        </button>
        
        <div id="modal-image" class="w-full h-full bg-gray-800 rounded-lg flex items-center justify-center">
            <i class="fas fa-mobile-alt text-8xl text-gray-400"></i>
        </div>
    </div>
</div>

<style>
.btn-primary {
    @apply bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-medium transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2;
}

.btn-secondary {
    @apply bg-white/10 hover:bg-white/20 text-white font-medium border border-white/20 hover:border-white/30 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-white/50 backdrop-blur-sm;
}

.card-shadow {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.05);
}

.storage-option.active {
    @apply border-cyan-400 bg-cyan-400 bg-opacity-10;
}

.color-option.active {
    @apply border-white;
}

.tab-button.active {
    @apply border-cyan-400 text-cyan-400;
}
</style>

<script>
let currentStorage = '256gb';
let currentColor = 'titanium';
let currentImageIndex = 0;
let basePrice = <?= $product['price'] ?>;

function changeMainImage(index) {
    currentImageIndex = index;
    
    // Update active thumbnail
    document.querySelectorAll('.thumbnail').forEach((thumb, i) => {
        if (i === index) {
            thumb.classList.add('ring-2', 'ring-cyan-400');
        } else {
            thumb.classList.remove('ring-2', 'ring-cyan-400');
        }
    });
}

function selectStorage(storageId, price, stock) {
    currentStorage = storageId;
    
    // Update active storage option
    document.querySelectorAll('.storage-option').forEach(option => {
        if (option.dataset.storage === storageId) {
            option.classList.add('active');
        } else {
            option.classList.remove('active');
        }
    });
    
    // Update price
    document.getElementById('current-price').textContent = 
        'R$ ' + price.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
    
    // Update max quantity
    const quantityInput = document.getElementById('quantity');
    quantityInput.max = stock;
    if (parseInt(quantityInput.value) > stock) {
        quantityInput.value = stock;
    }
}

function selectColor(colorId, stock) {
    currentColor = colorId;
    
    // Update active color option
    document.querySelectorAll('.color-option').forEach(option => {
        if (option.dataset.color === colorId) {
            option.classList.add('active');
        } else {
            option.classList.remove('active');
        }
    });
}

function changeQuantity(delta) {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    const newValue = currentValue + delta;
    const maxValue = parseInt(quantityInput.max);
    
    if (newValue >= 1 && newValue <= maxValue) {
        quantityInput.value = newValue;
    }
}

function validateQuantity() {
    const quantityInput = document.getElementById('quantity');
    const value = parseInt(quantityInput.value);
    const maxValue = parseInt(quantityInput.max);
    
    if (value < 1) {
        quantityInput.value = 1;
    } else if (value > maxValue) {
        quantityInput.value = maxValue;
    }
}

function switchTab(tabName) {
    // Update tab buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        if (button.dataset.tab === tabName) {
            button.classList.add('active', 'border-cyan-400', 'text-cyan-400');
            button.classList.remove('border-transparent', 'text-gray-400');
        } else {
            button.classList.remove('active', 'border-cyan-400', 'text-cyan-400');
            button.classList.add('border-transparent', 'text-gray-400');
        }
    });
    
    // Update tab content
    document.querySelectorAll('.tab-content').forEach(content => {
        if (content.id === tabName + '-tab') {
            content.classList.remove('hidden');
        } else {
            content.classList.add('hidden');
        }
    });
}

function formatCEP(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.length > 5) {
        value = value.substring(0, 5) + '-' + value.substring(5, 8);
    }
    input.value = value;
}

function calculateShipping() {
    const cep = document.getElementById('cep-input').value;
    const resultsDiv = document.getElementById('shipping-results');
    
    if (cep.length !== 9) {
        if (window.showError) {
            window.showError('Por favor, digite um CEP válido.');
        }
        return;
    }
    
    // Simulate shipping calculation
    const shippingOptions = [
        { name: 'PAC', price: 15.90, days: '8-12 dias úteis' },
        { name: 'SEDEX', price: 25.90, days: '3-5 dias úteis' },
        { name: 'Expressa', price: 35.90, days: '1-2 dias úteis' }
    ];
    
    resultsDiv.innerHTML = '';
    shippingOptions.forEach(option => {
        const optionDiv = document.createElement('div');
        optionDiv.className = 'flex justify-between items-center p-3 bg-gray-700 rounded-lg';
        optionDiv.innerHTML = `
            <div>
                <div class="text-white font-medium">${option.name}</div>
                <div class="text-sm text-gray-400">${option.days}</div>
            </div>
            <div class="text-cyan-400 font-semibold">
                R$ ${option.price.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}
            </div>
        `;
        resultsDiv.appendChild(optionDiv);
    });
    
    resultsDiv.classList.remove('hidden');
}

function openImageModal() {
    document.getElementById('image-modal').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    document.getElementById('image-modal').classList.add('hidden');
    document.body.style.overflow = 'auto';
}

function addToCart() {
    const quantity = document.getElementById('quantity').value;
    
    if (window.showSuccess) {
        window.showSuccess(`${quantity} produto(s) adicionado(s) ao carrinho!`);
    }
    
    console.log('Adicionando ao carrinho:', {
        productId: <?= $product['id'] ?>,
        storage: currentStorage,
        color: currentColor,
        quantity: parseInt(quantity)
    });
}

function buyNow() {
    addToCart();
    window.location.href = '/carrinho';
}

function toggleWishlist() {
    if (window.showInfo) {
        window.showInfo('Produto adicionado aos favoritos!');
    }
}

function viewProduct(productId) {
    window.location.href = `/produto/${productId}`;
}

// Close modal on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>