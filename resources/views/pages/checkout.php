<?php
// Configurações da página
$page_title = 'Finalizar Compra - NeonShop';
$current_route = '/checkout';
$breadcrumbs = [
    ['name' => 'Home', 'url' => '/'],
    ['name' => 'Carrinho', 'url' => '/carrinho'],
    ['name' => 'Checkout', 'url' => '/checkout']
];

// Dados do carrinho (simulados - normalmente viriam da sessão)
$cart_items = [
    [
        'id' => 1,
        'name' => 'iPhone 15 Pro Max',
        'price' => 8999.99,
        'quantity' => 1,
        'storage' => '256GB',
        'color' => 'Titânio Natural'
    ],
    [
        'id' => 2,
        'name' => 'AirPods Pro 2',
        'price' => 1899.99,
        'quantity' => 2,
        'color' => 'Branco'
    ]
];

$subtotal = 12799.97;
$shipping_cost = 25.90;
$coupon_discount = 0;
$total = $subtotal + $shipping_cost - $coupon_discount;

// Estados brasileiros
$states = [
    'AC' => 'Acre', 'AL' => 'Alagoas', 'AP' => 'Amapá', 'AM' => 'Amazonas',
    'BA' => 'Bahia', 'CE' => 'Ceará', 'DF' => 'Distrito Federal', 'ES' => 'Espírito Santo',
    'GO' => 'Goiás', 'MA' => 'Maranhão', 'MT' => 'Mato Grosso', 'MS' => 'Mato Grosso do Sul',
    'MG' => 'Minas Gerais', 'PA' => 'Pará', 'PB' => 'Paraíba', 'PR' => 'Paraná',
    'PE' => 'Pernambuco', 'PI' => 'Piauí', 'RJ' => 'Rio de Janeiro', 'RN' => 'Rio Grande do Norte',
    'RS' => 'Rio Grande do Sul', 'RO' => 'Rondônia', 'RR' => 'Roraima', 'SC' => 'Santa Catarina',
    'SP' => 'São Paulo', 'SE' => 'Sergipe', 'TO' => 'Tocantins'
];

// Métodos de pagamento
$payment_methods = [
    'credit_card' => [
        'name' => 'Cartão de Crédito',
        'icon' => 'fas fa-credit-card',
        'description' => 'Até 12x sem juros',
        'installments' => true
    ],
    'debit_card' => [
        'name' => 'Cartão de Débito',
        'icon' => 'fas fa-credit-card',
        'description' => 'À vista com desconto',
        'discount' => 2
    ],
    'pix' => [
        'name' => 'PIX',
        'icon' => 'fas fa-qrcode',
        'description' => '5% de desconto',
        'discount' => 5
    ],
    'boleto' => [
        'name' => 'Boleto Bancário',
        'icon' => 'fas fa-barcode',
        'description' => '3% de desconto',
        'discount' => 3
    ]
];

// Conteúdo da página
ob_start();
?>

<div class="bg-gray-900 min-h-screen py-8">
    <div class="container mx-auto px-4">
        
        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-center space-x-4 md:space-x-8">
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                        <i class="fas fa-check"></i>
                    </div>
                    <span class="ml-2 text-green-400 font-medium hidden md:block">Carrinho</span>
                </div>
                
                <div class="w-8 md:w-16 h-0.5 bg-cyan-400"></div>
                
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-cyan-500 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                        2
                    </div>
                    <span class="ml-2 text-cyan-400 font-medium hidden md:block">Checkout</span>
                </div>
                
                <div class="w-8 md:w-16 h-0.5 bg-gray-600"></div>
                
                <div class="flex items-center">
                    <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center text-gray-400 text-sm font-semibold">
                        3
                    </div>
                    <span class="ml-2 text-gray-400 font-medium hidden md:block">Confirmação</span>
                </div>
            </div>
        </div>
        
        <form id="checkout-form" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Checkout Form -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Customer Information -->
                <div class="bg-gray-800 rounded-2xl p-6 card-shadow">
                    <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
                        <i class="fas fa-user mr-3 text-cyan-400"></i>
                        Dados Pessoais
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Nome Completo *</label>
                            <input 
                                type="text" 
                                name="full_name"
                                required
                                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent"
                                placeholder="Digite seu nome completo"
                            >
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">CPF *</label>
                            <input 
                                type="text" 
                                name="cpf"
                                required
                                maxlength="14"
                                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent"
                                placeholder="000.000.000-00"
                                oninput="formatCPF(this)"
                            >
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">E-mail *</label>
                            <input 
                                type="email" 
                                name="email"
                                required
                                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent"
                                placeholder="seu@email.com"
                            >
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Telefone *</label>
                            <input 
                                type="tel" 
                                name="phone"
                                required
                                maxlength="15"
                                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent"
                                placeholder="(11) 99999-9999"
                                oninput="formatPhone(this)"
                            >
                        </div>
                        
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-300 mb-2">Data de Nascimento</label>
                            <input 
                                type="date" 
                                name="birth_date"
                                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent"
                            >
                        </div>
                    </div>
                </div>
                
                <!-- Delivery Address -->
                <div class="bg-gray-800 rounded-2xl p-6 card-shadow">
                    <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
                        <i class="fas fa-map-marker-alt mr-3 text-cyan-400"></i>
                        Endereço de Entrega
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">CEP *</label>
                                <div class="relative">
                                    <input 
                                        type="text" 
                                        name="cep"
                                        id="address-cep"
                                        required
                                        maxlength="9"
                                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent"
                                        placeholder="00000-000"
                                        oninput="formatCEP(this)"
                                        onblur="searchCEP()"
                                    >
                                    <div id="cep-loading" class="hidden absolute right-3 top-1/2 transform -translate-y-1/2">
                                        <i class="fas fa-spinner fa-spin text-cyan-400"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-300 mb-2">Endereço *</label>
                                <input 
                                    type="text" 
                                    name="address"
                                    id="address-street"
                                    required
                                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent"
                                    placeholder="Rua, Avenida, etc."
                                >
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Número *</label>
                                <input 
                                    type="text" 
                                    name="number"
                                    required
                                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent"
                                    placeholder="123"
                                >
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-300 mb-2">Complemento</label>
                                <input 
                                    type="text" 
                                    name="complement"
                                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent"
                                    placeholder="Apto, Bloco, etc."
                                >
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Bairro *</label>
                                <input 
                                    type="text" 
                                    name="neighborhood"
                                    id="address-neighborhood"
                                    required
                                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent"
                                    placeholder="Bairro"
                                >
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Cidade *</label>
                                <input 
                                    type="text" 
                                    name="city"
                                    id="address-city"
                                    required
                                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent"
                                    placeholder="Cidade"
                                >
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Estado *</label>
                                <select 
                                    name="state"
                                    id="address-state"
                                    required
                                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent"
                                >
                                    <option value="">Selecione o estado</option>
                                    <?php foreach ($states as $code => $name): ?>
                                        <option value="<?= $code ?>"><?= $name ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Method -->
                <div class="bg-gray-800 rounded-2xl p-6 card-shadow">
                    <h2 class="text-xl font-semibold text-white mb-6 flex items-center">
                        <i class="fas fa-credit-card mr-3 text-cyan-400"></i>
                        Forma de Pagamento
                    </h2>
                    
                    <div class="space-y-4">
                        <?php foreach ($payment_methods as $method_id => $method): ?>
                            <label class="payment-method flex items-center p-4 bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-600 transition-colors" data-method="<?= $method_id ?>">
                                <input 
                                    type="radio" 
                                    name="payment_method" 
                                    value="<?= $method_id ?>"
                                    class="sr-only"
                                    onchange="selectPaymentMethod('<?= $method_id ?>')"
                                    <?= $method_id === 'credit_card' ? 'checked' : '' ?>
                                >
                                <div class="flex items-center flex-1">
                                    <i class="<?= $method['icon'] ?> text-2xl text-cyan-400 mr-4"></i>
                                    <div class="flex-1">
                                        <div class="text-white font-medium"><?= $method['name'] ?></div>
                                        <div class="text-sm text-gray-400"><?= $method['description'] ?></div>
                                        <?php if (isset($method['discount'])): ?>
                                            <div class="text-sm text-green-400">
                                                Desconto de <?= $method['discount'] ?>%: 
                                                R$ <?= number_format($total * (1 - $method['discount'] / 100), 2, ',', '.') ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="payment-check w-5 h-5 border-2 border-gray-500 rounded-full flex items-center justify-center">
                                    <div class="w-3 h-3 bg-cyan-400 rounded-full opacity-0 transition-opacity"></div>
                                </div>
                            </label>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Credit Card Form -->
                    <div id="credit-card-form" class="mt-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-300 mb-2">Número do Cartão *</label>
                                <input 
                                    type="text" 
                                    name="card_number"
                                    maxlength="19"
                                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent"
                                    placeholder="0000 0000 0000 0000"
                                    oninput="formatCardNumber(this)"
                                >
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Nome no Cartão *</label>
                                <input 
                                    type="text" 
                                    name="card_name"
                                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent"
                                    placeholder="Nome como no cartão"
                                >
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">Validade *</label>
                                    <input 
                                        type="text" 
                                        name="card_expiry"
                                        maxlength="5"
                                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent"
                                        placeholder="MM/AA"
                                        oninput="formatCardExpiry(this)"
                                    >
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-300 mb-2">CVV *</label>
                                    <input 
                                        type="text" 
                                        name="card_cvv"
                                        maxlength="4"
                                        class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent"
                                        placeholder="000"
                                        oninput="this.value = this.value.replace(/\D/g, '')"
                                    >
                                </div>
                            </div>
                        </div>
                        
                        <!-- Installments -->
                        <div id="installments-section">
                            <label class="block text-sm font-medium text-gray-300 mb-2">Parcelamento</label>
                            <select 
                                name="installments"
                                id="installments-select"
                                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent"
                            >
                                <option value="1">1x de R$ <?= number_format($total, 2, ',', '.') ?> (à vista)</option>
                                <?php for ($i = 2; $i <= 12; $i++): ?>
                                    <option value="<?= $i ?>">
                                        <?= $i ?>x de R$ <?= number_format($total / $i, 2, ',', '.') ?> 
                                        <?= $i <= 6 ? 'sem juros' : 'com juros' ?>
                                    </option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    
                    <!-- PIX Form -->
                    <div id="pix-form" class="hidden mt-6">
                        <div class="bg-gray-700 rounded-lg p-4 text-center">
                            <i class="fas fa-qrcode text-4xl text-cyan-400 mb-3"></i>
                            <p class="text-white mb-2">Após confirmar o pedido, você receberá o código PIX para pagamento.</p>
                            <p class="text-sm text-gray-400">O pagamento deve ser realizado em até 30 minutos.</p>
                        </div>
                    </div>
                    
                    <!-- Boleto Form -->
                    <div id="boleto-form" class="hidden mt-6">
                        <div class="bg-gray-700 rounded-lg p-4 text-center">
                            <i class="fas fa-barcode text-4xl text-cyan-400 mb-3"></i>
                            <p class="text-white mb-2">Após confirmar o pedido, você receberá o boleto para pagamento.</p>
                            <p class="text-sm text-gray-400">Prazo de vencimento: 3 dias úteis.</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Order Summary -->
            <div class="space-y-6">
                
                <!-- Order Items -->
                <div class="bg-gray-800 rounded-2xl p-6 card-shadow">
                    <h3 class="text-lg font-semibold text-white mb-4">
                        <i class="fas fa-shopping-bag mr-2 text-cyan-400"></i>
                        Resumo do Pedido
                    </h3>
                    
                    <div class="space-y-4">
                        <?php foreach ($cart_items as $item): ?>
                            <div class="flex items-center gap-3 pb-3 border-b border-gray-700 last:border-b-0 last:pb-0">
                                <div class="w-16 h-16 bg-gradient-to-br from-gray-700 to-gray-800 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <i class="fas fa-mobile-alt text-xl text-gray-400"></i>
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <h4 class="text-white font-medium truncate"><?= htmlspecialchars($item['name']) ?></h4>
                                    <div class="text-sm text-gray-400">
                                        <?php if (isset($item['storage'])): ?>
                                            <?= htmlspecialchars($item['storage']) ?> • 
                                        <?php endif; ?>
                                        <?= htmlspecialchars($item['color']) ?>
                                    </div>
                                    <div class="text-sm text-gray-400">Qtd: <?= $item['quantity'] ?></div>
                                </div>
                                
                                <div class="text-right">
                                    <div class="text-cyan-400 font-semibold">
                                        R$ <?= number_format($item['price'] * $item['quantity'], 2, ',', '.') ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Price Summary -->
                <div class="bg-gray-800 rounded-2xl p-6 card-shadow">
                    <h3 class="text-lg font-semibold text-white mb-4">
                        <i class="fas fa-calculator mr-2 text-green-400"></i>
                        Valores
                    </h3>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between text-gray-300">
                            <span>Subtotal</span>
                            <span>R$ <?= number_format($subtotal, 2, ',', '.') ?></span>
                        </div>
                        
                        <div class="flex justify-between text-gray-300">
                            <span>Frete</span>
                            <span>R$ <?= number_format($shipping_cost, 2, ',', '.') ?></span>
                        </div>
                        
                        <div id="payment-discount" class="hidden flex justify-between text-green-400">
                            <span>Desconto no pagamento</span>
                            <span id="discount-value">-R$ 0,00</span>
                        </div>
                        
                        <hr class="border-gray-600">
                        
                        <div class="flex justify-between text-xl font-bold text-white">
                            <span>Total</span>
                            <span id="final-total" class="text-cyan-400">R$ <?= number_format($total, 2, ',', '.') ?></span>
                        </div>
                    </div>
                </div>
                
                <!-- Security Info -->
                <div class="bg-gray-800 rounded-2xl p-6 card-shadow">
                    <h3 class="text-lg font-semibold text-white mb-4">
                        <i class="fas fa-shield-alt mr-2 text-green-400"></i>
                        Segurança
                    </h3>
                    
                    <div class="space-y-3 text-sm text-gray-300">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-lock text-green-400"></i>
                            <span>Dados protegidos com SSL</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-shield-alt text-blue-400"></i>
                            <span>Compra 100% segura</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-undo text-yellow-400"></i>
                            <span>7 dias para trocar ou devolver</span>
                        </div>
                    </div>
                </div>
                
                <!-- Terms and Submit -->
                <div class="space-y-4">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input 
                            type="checkbox" 
                            name="terms" 
                            required
                            class="mt-1 w-4 h-4 text-cyan-600 bg-gray-700 border-gray-600 rounded focus:ring-cyan-500 focus:ring-2"
                        >
                        <span class="text-sm text-gray-300">
                            Eu li e aceito os 
                            <a href="/termos" class="text-cyan-400 hover:text-cyan-300 underline">Termos de Uso</a> 
                            e a 
                            <a href="/privacidade" class="text-cyan-400 hover:text-cyan-300 underline">Política de Privacidade</a>
                        </span>
                    </label>
                    
                    <button 
                        type="submit"
                        class="w-full btn-primary py-4 text-lg font-semibold rounded-xl hover:scale-105 transition-all duration-300"
                    >
                        <i class="fas fa-lock mr-2"></i>
                        Finalizar Compra
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.btn-primary {
    @apply bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-medium transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2;
}

.card-shadow {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.05);
}

.payment-method input:checked + div .payment-check {
    @apply border-cyan-400;
}

.payment-method input:checked + div .payment-check > div {
    @apply opacity-100;
}

.payment-method:hover {
    @apply ring-2 ring-cyan-400 ring-opacity-50;
}

.payment-method input:checked {
    @apply ring-2 ring-cyan-400;
}
</style>

<script>
let currentPaymentMethod = 'credit_card';
let baseTotal = <?= $total ?>;

function formatCPF(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.length <= 11) {
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d)/, '$1.$2');
        value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
    }
    input.value = value;
}

function formatPhone(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.length <= 11) {
        value = value.replace(/(\d{2})(\d)/, '($1) $2');
        value = value.replace(/(\d{5})(\d)/, '$1-$2');
    }
    input.value = value;
}

function formatCEP(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.length > 5) {
        value = value.substring(0, 5) + '-' + value.substring(5, 8);
    }
    input.value = value;
}

function formatCardNumber(input) {
    let value = input.value.replace(/\D/g, '');
    value = value.replace(/(\d{4})(?=\d)/g, '$1 ');
    input.value = value;
}

function formatCardExpiry(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.length >= 2) {
        value = value.substring(0, 2) + '/' + value.substring(2, 4);
    }
    input.value = value;
}

function searchCEP() {
    const cep = document.getElementById('address-cep').value.replace(/\D/g, '');
    
    if (cep.length !== 8) return;
    
    const loading = document.getElementById('cep-loading');
    loading.classList.remove('hidden');
    
    // Simulate API call
    setTimeout(() => {
        // Mock data
        const mockData = {
            logradouro: 'Avenida Paulista',
            bairro: 'Bela Vista',
            localidade: 'São Paulo',
            uf: 'SP'
        };
        
        document.getElementById('address-street').value = mockData.logradouro;
        document.getElementById('address-neighborhood').value = mockData.bairro;
        document.getElementById('address-city').value = mockData.localidade;
        document.getElementById('address-state').value = mockData.uf;
        
        loading.classList.add('hidden');
        
        if (window.showSuccess) {
            window.showSuccess('Endereço encontrado!');
        }
    }, 1500);
}

function selectPaymentMethod(method) {
    currentPaymentMethod = method;
    
    // Update visual selection
    document.querySelectorAll('.payment-method').forEach(el => {
        el.classList.remove('ring-2', 'ring-cyan-400');
    });
    
    const selectedMethod = document.querySelector(`[data-method="${method}"]`);
    selectedMethod.classList.add('ring-2', 'ring-cyan-400');
    
    // Show/hide payment forms
    document.getElementById('credit-card-form').classList.add('hidden');
    document.getElementById('pix-form').classList.add('hidden');
    document.getElementById('boleto-form').classList.add('hidden');
    
    if (method === 'credit_card' || method === 'debit_card') {
        document.getElementById('credit-card-form').classList.remove('hidden');
        
        // Show/hide installments for credit card
        const installmentsSection = document.getElementById('installments-section');
        if (method === 'credit_card') {
            installmentsSection.classList.remove('hidden');
        } else {
            installmentsSection.classList.add('hidden');
        }
    } else if (method === 'pix') {
        document.getElementById('pix-form').classList.remove('hidden');
    } else if (method === 'boleto') {
        document.getElementById('boleto-form').classList.remove('hidden');
    }
    
    // Update total with discount
    updateTotal();
}

function updateTotal() {
    const paymentMethods = {
        'credit_card': { discount: 0 },
        'debit_card': { discount: 2 },
        'pix': { discount: 5 },
        'boleto': { discount: 3 }
    };
    
    const method = paymentMethods[currentPaymentMethod];
    const discount = baseTotal * (method.discount / 100);
    const finalTotal = baseTotal - discount;
    
    // Update discount display
    const discountRow = document.getElementById('payment-discount');
    const discountValue = document.getElementById('discount-value');
    const finalTotalElement = document.getElementById('final-total');
    
    if (discount > 0) {
        discountRow.classList.remove('hidden');
        discountValue.textContent = '-R$ ' + discount.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
    } else {
        discountRow.classList.add('hidden');
    }
    
    finalTotalElement.textContent = 'R$ ' + finalTotal.toLocaleString('pt-BR', { minimumFractionDigits: 2 });
}

function validateForm() {
    const form = document.getElementById('checkout-form');
    const formData = new FormData(form);
    
    // Basic validation
    const requiredFields = [
        'full_name', 'cpf', 'email', 'phone',
        'cep', 'address', 'number', 'neighborhood', 'city', 'state'
    ];
    
    for (const field of requiredFields) {
        if (!formData.get(field)) {
            if (window.showError) {
                window.showError(`Por favor, preencha o campo ${field.replace('_', ' ')}.`);
            }
            return false;
        }
    }
    
    // Payment method specific validation
    if (currentPaymentMethod === 'credit_card' || currentPaymentMethod === 'debit_card') {
        const cardFields = ['card_number', 'card_name', 'card_expiry', 'card_cvv'];
        for (const field of cardFields) {
            if (!formData.get(field)) {
                if (window.showError) {
                    window.showError('Por favor, preencha todos os dados do cartão.');
                }
                return false;
            }
        }
    }
    
    // Terms acceptance
    if (!formData.get('terms')) {
        if (window.showError) {
            window.showError('Você deve aceitar os termos de uso.');
        }
        return false;
    }
    
    return true;
}

function processOrder() {
    if (!validateForm()) return;
    
    // Show loading
    const submitButton = document.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processando...';
    submitButton.disabled = true;
    
    // Simulate order processing
    setTimeout(() => {
        if (window.showSuccess) {
            window.showSuccess('Pedido realizado com sucesso!');
        }
        
        // Redirect to success page
        setTimeout(() => {
            window.location.href = '/pedido/confirmacao/123456';
        }, 2000);
    }, 3000);
}

// Form submission
document.getElementById('checkout-form').addEventListener('submit', function(e) {
    e.preventDefault();
    processOrder();
});

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    selectPaymentMethod('credit_card');
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>