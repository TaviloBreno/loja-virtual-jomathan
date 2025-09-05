<?php
// Configurações da página
$page_title = 'Carrinho de Compras - NeonShop';
$current_route = '/carrinho';
$breadcrumbs = [
    ['name' => 'Home', 'url' => '/'],
    ['name' => 'Carrinho', 'url' => '/carrinho']
];

// Dados do carrinho (simulados)
$cart_items = [
    [
        'id' => 1,
        'product_id' => 1,
        'name' => 'iPhone 15 Pro Max',
        'brand' => 'Apple',
        'sku' => 'IPH15PM-256-TIT',
        'price' => 8999.99,
        'original_price' => 9999.99,
        'quantity' => 1,
        'storage' => '256GB',
        'color' => 'Titânio Natural',
        'image' => '/images/products/iphone-15-pro-1.jpg',
        'in_stock' => true,
        'max_quantity' => 15
    ],
    [
        'id' => 2,
        'product_id' => 3,
        'name' => 'AirPods Pro 2',
        'brand' => 'Apple',
        'sku' => 'APP2-WHT',
        'price' => 1899.99,
        'original_price' => 2199.99,
        'quantity' => 2,
        'storage' => null,
        'color' => 'Branco',
        'image' => '/images/products/airpods-pro.jpg',
        'in_stock' => true,
        'max_quantity' => 25
    ],
    [
        'id' => 3,
        'product_id' => 5,
        'name' => 'iPhone 15 Case',
        'brand' => 'Apple',
        'sku' => 'IPC15-BLK',
        'price' => 399.99,
        'original_price' => 0,
        'quantity' => 1,
        'storage' => null,
        'color' => 'Preto',
        'image' => '/images/products/iphone-case.jpg',
        'in_stock' => true,
        'max_quantity' => 50
    ]
];

// Cálculos do carrinho
$subtotal = 0;
$total_discount = 0;
foreach ($cart_items as $item) {
    $item_total = $item['price'] * $item['quantity'];
    $subtotal += $item_total;
    
    if ($item['original_price'] > $item['price']) {
        $item_discount = ($item['original_price'] - $item['price']) * $item['quantity'];
        $total_discount += $item_discount;
    }
}

// Cupons disponíveis
$available_coupons = [
    [
        'code' => 'BEMVINDO10',
        'description' => '10% de desconto para novos clientes',
        'discount_type' => 'percentage',
        'discount_value' => 10,
        'min_value' => 500.00,
        'valid' => true
    ],
    [
        'code' => 'FRETE20',
        'description' => 'R$ 20 de desconto no frete',
        'discount_type' => 'fixed',
        'discount_value' => 20.00,
        'min_value' => 200.00,
        'valid' => true
    ],
    [
        'code' => 'APPLE15',
        'description' => '15% de desconto em produtos Apple',
        'discount_type' => 'percentage',
        'discount_value' => 15,
        'min_value' => 1000.00,
        'valid' => true
    ]
];

// Opções de frete
$shipping_options = [
    [
        'id' => 'pac',
        'name' => 'PAC',
        'description' => 'Correios - Entrega econômica',
        'price' => 25.90,
        'days' => '8-12 dias úteis',
        'selected' => true
    ],
    [
        'id' => 'sedex',
        'name' => 'SEDEX',
        'description' => 'Correios - Entrega rápida',
        'price' => 35.90,
        'days' => '3-5 dias úteis',
        'selected' => false
    ],
    [
        'id' => 'express',
        'name' => 'Expressa',
        'description' => 'Entrega expressa',
        'price' => 45.90,
        'days' => '1-2 dias úteis',
        'selected' => false
    ]
];

$selected_shipping = array_filter($shipping_options, fn($option) => $option['selected'])[0] ?? $shipping_options[0];
$shipping_cost = $selected_shipping['price'];

// Totais
$coupon_discount = 0;
$total = $subtotal + $shipping_cost - $coupon_discount;

// Conteúdo da página
ob_start();
?>

<div class="bg-gray-900 min-h-screen py-8">
    <div class="container mx-auto px-4">
        
        <?php if (empty($cart_items)): ?>
            <!-- Empty Cart -->
            <div class="text-center py-16">
                <div class="mb-8">
                    <i class="fas fa-shopping-cart text-8xl text-gray-600"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-4">Seu carrinho está vazio</h1>
                <p class="text-gray-400 mb-8">Adicione produtos ao seu carrinho para continuar comprando.</p>
                <a href="/produtos" class="btn-primary px-8 py-3 rounded-xl font-semibold hover:scale-105 transition-all duration-300">
                    <i class="fas fa-shopping-bag mr-2"></i>
                    Continuar Comprando
                </a>
            </div>
        <?php else: ?>
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Cart Header -->
                    <div class="bg-gray-800 rounded-2xl p-6 card-shadow">
                        <div class="flex items-center justify-between">
                            <h1 class="text-2xl font-bold text-white">
                                Carrinho de Compras
                                <span class="text-lg text-gray-400 font-normal ml-2">
                                    (<?= count($cart_items) ?> <?= count($cart_items) === 1 ? 'item' : 'itens' ?>)
                                </span>
                            </h1>
                            
                            <button 
                                onclick="clearCart()"
                                class="text-red-400 hover:text-red-300 transition-colors"
                                title="Limpar carrinho"
                            >
                                <i class="fas fa-trash-alt"></i>
                                Limpar
                            </button>
                        </div>
                    </div>
                    
                    <!-- Cart Items List -->
                    <div class="space-y-4">
                        <?php foreach ($cart_items as $item): ?>
                            <div class="bg-gray-800 rounded-2xl p-6 card-shadow" data-item-id="<?= $item['id'] ?>">
                                <div class="flex flex-col md:flex-row gap-6">
                                    
                                    <!-- Product Image -->
                                    <div class="w-full md:w-32 h-32 bg-gradient-to-br from-gray-700 to-gray-800 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-cyan-500 rounded-lg opacity-20"></div>
                                        <div class="absolute">
                                            <i class="fas fa-mobile-alt text-3xl text-gray-400"></i>
                                        </div>
                                    </div>
                                    
                                    <!-- Product Info -->
                                    <div class="flex-1 space-y-4">
                                        
                                        <!-- Product Details -->
                                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4">
                                            <div class="flex-1">
                                                <h3 class="text-lg font-semibold text-white mb-1">
                                                    <?= htmlspecialchars($item['name']) ?>
                                                </h3>
                                                
                                                <div class="flex items-center gap-2 text-sm text-gray-400 mb-2">
                                                    <span><?= htmlspecialchars($item['brand']) ?></span>
                                                    <span>•</span>
                                                    <span>SKU: <?= htmlspecialchars($item['sku']) ?></span>
                                                </div>
                                                
                                                <!-- Variations -->
                                                <div class="flex flex-wrap gap-3 text-sm">
                                                    <?php if ($item['storage']): ?>
                                                        <span class="bg-gray-700 text-gray-300 px-3 py-1 rounded-full">
                                                            <?= htmlspecialchars($item['storage']) ?>
                                                        </span>
                                                    <?php endif; ?>
                                                    <span class="bg-gray-700 text-gray-300 px-3 py-1 rounded-full">
                                                        <?= htmlspecialchars($item['color']) ?>
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <!-- Remove Button -->
                                            <button 
                                                onclick="removeItem(<?= $item['id'] ?>)"
                                                class="text-red-400 hover:text-red-300 transition-colors p-2"
                                                title="Remover item"
                                            >
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                        
                                        <!-- Price and Quantity -->
                                        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                            
                                            <!-- Price -->
                                            <div class="flex items-center gap-3">
                                                <span class="text-xl font-bold text-cyan-400">
                                                    R$ <?= number_format($item['price'], 2, ',', '.') ?>
                                                </span>
                                                <?php if ($item['original_price'] > $item['price']): ?>
                                                    <span class="text-sm text-gray-500 line-through">
                                                        R$ <?= number_format($item['original_price'], 2, ',', '.') ?>
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            
                                            <!-- Quantity Controls -->
                                            <div class="flex items-center gap-4">
                                                <div class="flex items-center bg-gray-700 rounded-lg">
                                                    <button 
                                                        onclick="updateQuantity(<?= $item['id'] ?>, -1)"
                                                        class="px-3 py-2 text-gray-400 hover:text-white transition-colors"
                                                        <?= $item['quantity'] <= 1 ? 'disabled' : '' ?>
                                                    >
                                                        <i class="fas fa-minus"></i>
                                                    </button>
                                                    
                                                    <input 
                                                        type="number" 
                                                        value="<?= $item['quantity'] ?>" 
                                                        min="1" 
                                                        max="<?= $item['max_quantity'] ?>"
                                                        class="w-16 py-2 bg-transparent text-center text-white focus:outline-none"
                                                        onchange="setQuantity(<?= $item['id'] ?>, this.value)"
                                                    >
                                                    
                                                    <button 
                                                        onclick="updateQuantity(<?= $item['id'] ?>, 1)"
                                                        class="px-3 py-2 text-gray-400 hover:text-white transition-colors"
                                                        <?= $item['quantity'] >= $item['max_quantity'] ? 'disabled' : '' ?>
                                                    >
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </div>
                                                
                                                <!-- Item Total -->
                                                <div class="text-right">
                                                    <div class="text-lg font-semibold text-white">
                                                        R$ <?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?>
                                                    </div>
                                                    <?php if ($item['original_price'] > $item['price']): ?>
                                                        <div class="text-sm text-green-400">
                                                            Economia: R$ <?= number_format(($item['original_price'] - $item['price']) * $item['quantity'], 2, ',', '.') ?>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <!-- Stock Status -->
                                        <div class="flex items-center gap-2">
                                            <?php if ($item['in_stock']): ?>
                                                <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                                                <span class="text-sm text-green-400">Em estoque</span>
                                            <?php else: ?>
                                                <div class="w-2 h-2 bg-red-400 rounded-full"></div>
                                                <span class="text-sm text-red-400">Fora de estoque</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Continue Shopping -->
                    <div class="text-center pt-6">
                        <a href="/produtos" class="inline-flex items-center text-cyan-400 hover:text-cyan-300 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Continuar Comprando
                        </a>
                    </div>
                </div>
                
                <!-- Cart Summary -->
                <div class="space-y-6">
                    
                    <!-- Coupon Section -->
                    <div class="bg-gray-800 rounded-2xl p-6 card-shadow">
                        <h3 class="text-lg font-semibold text-white mb-4">
                            <i class="fas fa-tag mr-2 text-yellow-400"></i>
                            Cupom de Desconto
                        </h3>
                        
                        <div class="space-y-4">
                            <!-- Coupon Input -->
                            <div class="flex gap-2">
                                <input 
                                    type="text" 
                                    id="coupon-input"
                                    placeholder="Digite o código do cupom"
                                    class="flex-1 px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent"
                                    onkeypress="if(event.key==='Enter') applyCoupon()"
                                >
                                <button 
                                    onclick="applyCoupon()"
                                    class="px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-black font-medium rounded-lg transition-colors"
                                >
                                    Aplicar
                                </button>
                            </div>
                            
                            <!-- Applied Coupon -->
                            <div id="applied-coupon" class="hidden bg-green-500 bg-opacity-20 border border-green-500 rounded-lg p-3">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center text-green-400">
                                        <i class="fas fa-check-circle mr-2"></i>
                                        <span id="coupon-name"></span>
                                    </div>
                                    <button onclick="removeCoupon()" class="text-green-400 hover:text-green-300">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                <div class="text-sm text-green-300 mt-1" id="coupon-description"></div>
                            </div>
                            
                            <!-- Available Coupons -->
                            <div class="space-y-2">
                                <div class="text-sm text-gray-400 mb-2">Cupons disponíveis:</div>
                                <?php foreach ($available_coupons as $coupon): ?>
                                    <button 
                                        onclick="applyCouponCode('<?= $coupon['code'] ?>')"
                                        class="w-full text-left p-3 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors group"
                                    >
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <div class="text-yellow-400 font-medium"><?= $coupon['code'] ?></div>
                                                <div class="text-sm text-gray-300"><?= htmlspecialchars($coupon['description']) ?></div>
                                                <div class="text-xs text-gray-400">Mínimo: R$ <?= number_format($coupon['min_value'], 2, ',', '.') ?></div>
                                            </div>
                                            <i class="fas fa-plus text-gray-400 group-hover:text-white"></i>
                                        </div>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Shipping Section -->
                    <div class="bg-gray-800 rounded-2xl p-6 card-shadow">
                        <h3 class="text-lg font-semibold text-white mb-4">
                            <i class="fas fa-truck mr-2 text-cyan-400"></i>
                            Frete e Entrega
                        </h3>
                        
                        <!-- CEP Input -->
                        <div class="mb-4">
                            <div class="flex gap-2">
                                <input 
                                    type="text" 
                                    id="shipping-cep"
                                    placeholder="Digite seu CEP"
                                    maxlength="9"
                                    class="flex-1 px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent"
                                    oninput="formatCEP(this)"
                                    value="01310-100"
                                >
                                <button 
                                    onclick="calculateShipping()"
                                    class="px-6 py-3 bg-cyan-500 hover:bg-cyan-600 text-white font-medium rounded-lg transition-colors"
                                >
                                    Calcular
                                </button>
                            </div>
                        </div>
                        
                        <!-- Shipping Options -->
                        <div class="space-y-3">
                            <?php foreach ($shipping_options as $option): ?>
                                <label class="flex items-center p-3 bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-600 transition-colors <?= $option['selected'] ? 'ring-2 ring-cyan-400' : '' ?>">
                                    <input 
                                        type="radio" 
                                        name="shipping" 
                                        value="<?= $option['id'] ?>"
                                        class="sr-only"
                                        <?= $option['selected'] ? 'checked' : '' ?>
                                        onchange="selectShipping('<?= $option['id'] ?>', <?= $option['price'] ?>)"
                                    >
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-white font-medium"><?= $option['name'] ?></span>
                                            <span class="text-cyan-400 font-semibold">
                                                R$ <?= number_format($option['price'], 2, ',', '.') ?>
                                            </span>
                                        </div>
                                        <div class="text-sm text-gray-400"><?= htmlspecialchars($option['description']) ?></div>
                                        <div class="text-sm text-gray-300"><?= $option['days'] ?></div>
                                    </div>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <!-- Order Summary -->
                    <div class="bg-gray-800 rounded-2xl p-6 card-shadow">
                        <h3 class="text-lg font-semibold text-white mb-4">
                            <i class="fas fa-receipt mr-2 text-green-400"></i>
                            Resumo do Pedido
                        </h3>
                        
                        <div class="space-y-3">
                            <!-- Subtotal -->
                            <div class="flex justify-between text-gray-300">
                                <span>Subtotal (<?= count($cart_items) ?> itens)</span>
                                <span id="subtotal-value">R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
                            </div>
                            
                            <!-- Discount -->
                            <?php if ($total_discount > 0): ?>
                                <div class="flex justify-between text-green-400">
                                    <span>Desconto nos produtos</span>
                                    <span>-R$ <?= number_format($total_discount, 2, ',', '.') ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Coupon Discount -->
                            <div id="coupon-discount-row" class="hidden flex justify-between text-green-400">
                                <span>Desconto do cupom</span>
                                <span id="coupon-discount-value">-R$ 0,00</span>
                            </div>
                            
                            <!-- Shipping -->
                            <div class="flex justify-between text-gray-300">
                                <span>Frete</span>
                                <span id="shipping-value">R$ <?= number_format($shipping_cost, 2, ',', '.') ?></span>
                            </div>
                            
                            <hr class="border-gray-600">
                            
                            <!-- Total -->
                            <div class="flex justify-between text-xl font-bold text-white">
                                <span>Total</span>
                                <span id="total-value" class="text-cyan-400">R$ <?= number_format($total, 2, ',', '.') ?></span>
                            </div>
                            
                            <!-- Payment Info -->
                            <div class="text-sm text-gray-400 space-y-1">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-credit-card text-cyan-400"></i>
                                    <span>12x de R$ <?= number_format($total / 12, 2, ',', '.') ?> sem juros</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-barcode text-green-400"></i>
                                    <span>5% de desconto no PIX: R$ <?= number_format($total * 0.95, 2, ',', '.') ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Checkout Button -->
                        <button 
                            onclick="proceedToCheckout()"
                            class="w-full mt-6 btn-primary py-4 text-lg font-semibold rounded-xl hover:scale-105 transition-all duration-300"
                        >
                            <i class="fas fa-lock mr-2"></i>
                            Finalizar Compra
                        </button>
                        
                        <!-- Security Info -->
                        <div class="flex items-center justify-center gap-4 mt-4 text-sm text-gray-400">
                            <div class="flex items-center gap-1">
                                <i class="fas fa-shield-alt text-green-400"></i>
                                <span>Compra Segura</span>
                            </div>
                            <div class="flex items-center gap-1">
                                <i class="fas fa-undo text-blue-400"></i>
                                <span>7 dias para trocar</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.btn-primary {
    @apply bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-medium transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2;
}

.card-shadow {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.05);
}

input[type="radio"]:checked + div {
    @apply ring-2 ring-cyan-400;
}

.quantity-input::-webkit-outer-spin-button,
.quantity-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.quantity-input[type=number] {
    -moz-appearance: textfield;
}
</style>

<script>
let cartData = {
    items: <?= json_encode($cart_items) ?>,
    subtotal: <?= $subtotal ?>,
    shipping: <?= $shipping_cost ?>,
    couponDiscount: 0,
    total: <?= $total ?>
};

let availableCoupons = <?= json_encode($available_coupons) ?>;
let appliedCoupon = null;

function updateQuantity(itemId, delta) {
    const item = cartData.items.find(i => i.id === itemId);
    if (!item) return;
    
    const newQuantity = item.quantity + delta;
    if (newQuantity >= 1 && newQuantity <= item.max_quantity) {
        item.quantity = newQuantity;
        updateCartDisplay();
        
        // Update quantity input
        const input = document.querySelector(`[data-item-id="${itemId}"] input[type="number"]`);
        if (input) input.value = newQuantity;
    }
}

function setQuantity(itemId, quantity) {
    const item = cartData.items.find(i => i.id === itemId);
    if (!item) return;
    
    const newQuantity = Math.max(1, Math.min(parseInt(quantity) || 1, item.max_quantity));
    item.quantity = newQuantity;
    updateCartDisplay();
    
    // Update input value to ensure it's valid
    const input = document.querySelector(`[data-item-id="${itemId}"] input[type="number"]`);
    if (input) input.value = newQuantity;
}

function removeItem(itemId) {
    if (confirm('Tem certeza que deseja remover este item do carrinho?')) {
        cartData.items = cartData.items.filter(i => i.id !== itemId);
        
        // Remove item from DOM
        const itemElement = document.querySelector(`[data-item-id="${itemId}"]`);
        if (itemElement) {
            itemElement.remove();
        }
        
        updateCartDisplay();
        
        if (cartData.items.length === 0) {
            location.reload(); // Reload to show empty cart
        }
        
        if (window.showSuccess) {
            window.showSuccess('Item removido do carrinho!');
        }
    }
}

function clearCart() {
    if (confirm('Tem certeza que deseja limpar todo o carrinho?')) {
        cartData.items = [];
        location.reload();
    }
}

function updateCartDisplay() {
    // Recalculate subtotal
    cartData.subtotal = cartData.items.reduce((sum, item) => {
        return sum + (item.price * item.quantity);
    }, 0);
    
    // Recalculate total
    cartData.total = cartData.subtotal + cartData.shipping - cartData.couponDiscount;
    
    // Update display
    document.getElementById('subtotal-value').textContent = 
        'R$ ' + cartData.subtotal.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
    
    document.getElementById('total-value').textContent = 
        'R$ ' + cartData.total.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
}

function formatCEP(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.length > 5) {
        value = value.substring(0, 5) + '-' + value.substring(5, 8);
    }
    input.value = value;
}

function calculateShipping() {
    const cep = document.getElementById('shipping-cep').value;
    
    if (cep.length !== 9) {
        if (window.showError) {
            window.showError('Por favor, digite um CEP válido.');
        }
        return;
    }
    
    if (window.showSuccess) {
        window.showSuccess('Frete calculado com sucesso!');
    }
}

function selectShipping(shippingId, price) {
    cartData.shipping = price;
    cartData.total = cartData.subtotal + cartData.shipping - cartData.couponDiscount;
    
    // Update display
    document.getElementById('shipping-value').textContent = 
        'R$ ' + price.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
    
    document.getElementById('total-value').textContent = 
        'R$ ' + cartData.total.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
    
    // Update radio selection visual
    document.querySelectorAll('label').forEach(label => {
        const radio = label.querySelector('input[type="radio"]');
        if (radio && radio.value === shippingId) {
            label.classList.add('ring-2', 'ring-cyan-400');
        } else {
            label.classList.remove('ring-2', 'ring-cyan-400');
        }
    });
}

function applyCouponCode(code) {
    document.getElementById('coupon-input').value = code;
    applyCoupon();
}

function applyCoupon() {
    const code = document.getElementById('coupon-input').value.trim().toUpperCase();
    
    if (!code) {
        if (window.showError) {
            window.showError('Digite um código de cupom.');
        }
        return;
    }
    
    const coupon = availableCoupons.find(c => c.code === code && c.valid);
    
    if (!coupon) {
        if (window.showError) {
            window.showError('Cupom inválido ou expirado.');
        }
        return;
    }
    
    if (cartData.subtotal < coupon.min_value) {
        if (window.showError) {
            window.showError(`Valor mínimo para este cupom: R$ ${coupon.min_value.toLocaleString('pt-BR', { minimumFractionDigits: 2 })}`);
        }
        return;
    }
    
    // Calculate discount
    let discount = 0;
    if (coupon.discount_type === 'percentage') {
        discount = cartData.subtotal * (coupon.discount_value / 100);
    } else {
        discount = coupon.discount_value;
    }
    
    // Apply coupon
    appliedCoupon = coupon;
    cartData.couponDiscount = discount;
    cartData.total = cartData.subtotal + cartData.shipping - cartData.couponDiscount;
    
    // Update display
    document.getElementById('coupon-name').textContent = coupon.code;
    document.getElementById('coupon-description').textContent = coupon.description;
    document.getElementById('applied-coupon').classList.remove('hidden');
    document.getElementById('coupon-discount-row').classList.remove('hidden');
    document.getElementById('coupon-discount-value').textContent = 
        '-R$ ' + discount.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
    document.getElementById('total-value').textContent = 
        'R$ ' + cartData.total.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
    
    // Clear input
    document.getElementById('coupon-input').value = '';
    
    if (window.showSuccess) {
        window.showSuccess('Cupom aplicado com sucesso!');
    }
}

function removeCoupon() {
    appliedCoupon = null;
    cartData.couponDiscount = 0;
    cartData.total = cartData.subtotal + cartData.shipping - cartData.couponDiscount;
    
    // Update display
    document.getElementById('applied-coupon').classList.add('hidden');
    document.getElementById('coupon-discount-row').classList.add('hidden');
    document.getElementById('total-value').textContent = 
        'R$ ' + cartData.total.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
    
    if (window.showInfo) {
        window.showInfo('Cupom removido.');
    }
}

function proceedToCheckout() {
    // Validate cart
    if (cartData.items.length === 0) {
        if (window.showError) {
            window.showError('Seu carrinho está vazio.');
        }
        return;
    }
    
    // Check stock
    const outOfStock = cartData.items.filter(item => !item.in_stock);
    if (outOfStock.length > 0) {
        if (window.showError) {
            window.showError('Alguns itens estão fora de estoque. Remova-os para continuar.');
        }
        return;
    }
    
    // Proceed to checkout
    window.location.href = '/checkout';
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    // Set initial shipping selection
    const selectedShipping = document.querySelector('input[name="shipping"]:checked');
    if (selectedShipping) {
        const label = selectedShipping.closest('label');
        label.classList.add('ring-2', 'ring-cyan-400');
    }
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>