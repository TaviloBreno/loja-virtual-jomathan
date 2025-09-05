<?php

namespace Controllers;

use Core\Request;
use Core\Response;

/**
 * Controlador do Carrinho - NeonShop
 * 
 * Responsável por gerenciar todas as ações relacionadas ao carrinho:
 * - Adicionar/remover produtos
 * - Atualizar quantidades
 * - Aplicar cupons de desconto
 * - Calcular frete
 * - Processar checkout
 */
class CartController extends BaseController {
    
    /**
     * Adiciona produto ao carrinho
     * 
     * @return Response
     */
    public function addToCart(): Response {
        $productId = (int)$this->request->getBodyParam('product_id');
        $quantity = (int)$this->request->getBodyParam('quantity', 1);
        $variation = $this->request->getBodyParam('variation', '');
        
        // Validar dados
        if (!$this->validate([
            'product_id' => $productId,
            'quantity' => $quantity
        ], [
            'product_id' => 'required|numeric',
            'quantity' => 'required|numeric'
        ])) {
            return $this->error('Dados inválidos', 400, $this->errors);
        }
        
        // Verificar se produto existe
        $product = $this->getProductById($productId);
        if (!$product) {
            return $this->error('Produto não encontrado', 404);
        }
        
        // Verificar estoque
        if (!$this->checkStock($productId, $quantity)) {
            return $this->error('Quantidade indisponível em estoque', 400);
        }
        
        // Inicializar carrinho se não existir
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        // Chave única para o item (produto + variação)
        $itemKey = $productId . ($variation ? '_' . $variation : '');
        
        // Adicionar ou atualizar item no carrinho
        if (isset($_SESSION['cart'][$itemKey])) {
            $_SESSION['cart'][$itemKey]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$itemKey] = [
                'product_id' => $productId,
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'quantity' => $quantity,
                'variation' => $variation,
                'added_at' => time()
            ];
        }
        
        // Log da ação
        $this->logAction('add_to_cart', [
            'product_id' => $productId,
            'quantity' => $quantity,
            'variation' => $variation
        ]);
        
        // Calcular totais do carrinho
        $cartTotals = $this->calculateCartTotals();
        
        return $this->success([
            'message' => 'Produto adicionado ao carrinho',
            'cart_count' => $this->getCartCount(),
            'cart_totals' => $cartTotals
        ]);
    }
    
    /**
     * Remove produto do carrinho
     * 
     * @return Response
     */
    public function removeFromCart(): Response {
        $itemKey = $this->request->getBodyParam('item_key');
        
        if (!$itemKey) {
            return $this->error('Chave do item é obrigatória', 400);
        }
        
        if (!isset($_SESSION['cart'][$itemKey])) {
            return $this->error('Item não encontrado no carrinho', 404);
        }
        
        // Remover item
        $removedItem = $_SESSION['cart'][$itemKey];
        unset($_SESSION['cart'][$itemKey]);
        
        // Log da ação
        $this->logAction('remove_from_cart', [
            'item_key' => $itemKey,
            'product_id' => $removedItem['product_id']
        ]);
        
        // Calcular totais do carrinho
        $cartTotals = $this->calculateCartTotals();
        
        return $this->success([
            'message' => 'Produto removido do carrinho',
            'cart_count' => $this->getCartCount(),
            'cart_totals' => $cartTotals
        ]);
    }
    
    /**
     * Atualiza quantidade de um item no carrinho
     * 
     * @return Response
     */
    public function updateQuantity(): Response {
        $itemKey = $this->request->getBodyParam('item_key');
        $quantity = (int)$this->request->getBodyParam('quantity');
        
        if (!$itemKey || $quantity < 1) {
            return $this->error('Dados inválidos', 400);
        }
        
        if (!isset($_SESSION['cart'][$itemKey])) {
            return $this->error('Item não encontrado no carrinho', 404);
        }
        
        $item = $_SESSION['cart'][$itemKey];
        
        // Verificar estoque
        if (!$this->checkStock($item['product_id'], $quantity)) {
            return $this->error('Quantidade indisponível em estoque', 400);
        }
        
        // Atualizar quantidade
        $_SESSION['cart'][$itemKey]['quantity'] = $quantity;
        
        // Log da ação
        $this->logAction('update_cart_quantity', [
            'item_key' => $itemKey,
            'product_id' => $item['product_id'],
            'new_quantity' => $quantity
        ]);
        
        // Calcular totais do carrinho
        $cartTotals = $this->calculateCartTotals();
        
        return $this->success([
            'message' => 'Quantidade atualizada',
            'cart_count' => $this->getCartCount(),
            'cart_totals' => $cartTotals
        ]);
    }
    
    /**
     * Limpa todo o carrinho
     * 
     * @return Response
     */
    public function clearCart(): Response {
        $_SESSION['cart'] = [];
        
        // Log da ação
        $this->logAction('clear_cart');
        
        return $this->success([
            'message' => 'Carrinho limpo com sucesso',
            'cart_count' => 0,
            'cart_totals' => $this->calculateCartTotals()
        ]);
    }
    
    /**
     * Aplica cupom de desconto
     * 
     * @return Response
     */
    public function applyCoupon(): Response {
        $couponCode = trim($this->request->getBodyParam('coupon_code', ''));
        
        if (empty($couponCode)) {
            return $this->error('Código do cupom é obrigatório', 400);
        }
        
        // Verificar se cupom é válido
        $coupon = $this->validateCoupon($couponCode);
        if (!$coupon) {
            return $this->error('Cupom inválido ou expirado', 400);
        }
        
        // Verificar se carrinho atende aos requisitos do cupom
        $cartTotals = $this->calculateCartTotals();
        if ($cartTotals['subtotal'] < $coupon['min_amount']) {
            return $this->error(
                "Valor mínimo para este cupom é {$this->formatPrice($coupon['min_amount'])}",
                400
            );
        }
        
        // Aplicar cupom
        $_SESSION['applied_coupon'] = $coupon;
        
        // Log da ação
        $this->logAction('apply_coupon', [
            'coupon_code' => $couponCode,
            'discount_type' => $coupon['type'],
            'discount_value' => $coupon['value']
        ]);
        
        // Recalcular totais com desconto
        $cartTotals = $this->calculateCartTotals();
        
        return $this->success([
            'message' => 'Cupom aplicado com sucesso',
            'coupon' => $coupon,
            'cart_totals' => $cartTotals
        ]);
    }
    
    /**
     * Remove cupom aplicado
     * 
     * @return Response
     */
    public function removeCoupon(): Response {
        unset($_SESSION['applied_coupon']);
        
        // Log da ação
        $this->logAction('remove_coupon');
        
        // Recalcular totais sem desconto
        $cartTotals = $this->calculateCartTotals();
        
        return $this->success([
            'message' => 'Cupom removido',
            'cart_totals' => $cartTotals
        ]);
    }
    
    /**
     * Calcula frete baseado no CEP
     * 
     * @return Response
     */
    public function calculateShipping(): Response {
        $zipCode = $this->request->getBodyParam('zip_code');
        
        if (!$zipCode) {
            return $this->error('CEP é obrigatório', 400);
        }
        
        // Validar formato do CEP
        $zipCode = preg_replace('/[^0-9]/', '', $zipCode);
        if (strlen($zipCode) !== 8) {
            return $this->error('CEP inválido', 400);
        }
        
        // Simular cálculo de frete
        $shippingOptions = $this->getShippingOptions($zipCode);
        
        // Salvar CEP na sessão
        $_SESSION['shipping_zip_code'] = $zipCode;
        
        return $this->success([
            'shipping_options' => $shippingOptions,
            'zip_code' => $zipCode
        ]);
    }
    
    /**
     * Processa o checkout
     * 
     * @return Response
     */
    public function processCheckout(): Response {
        // Verificar se há itens no carrinho
        $cart = $_SESSION['cart'] ?? [];
        if (empty($cart)) {
            return $this->error('Carrinho vazio', 400);
        }
        
        // Validar dados do cliente
        $customerData = [
            'name' => $this->request->getBodyParam('name'),
            'email' => $this->request->getBodyParam('email'),
            'phone' => $this->request->getBodyParam('phone'),
            'document' => $this->request->getBodyParam('document')
        ];
        
        if (!$this->validate($customerData, [
            'name' => 'required|min:2',
            'email' => 'required|email',
            'phone' => 'required|min:10',
            'document' => 'required|min:11'
        ])) {
            return $this->error('Dados do cliente inválidos', 400, $this->errors);
        }
        
        // Validar endereço de entrega
        $addressData = [
            'zip_code' => $this->request->getBodyParam('zip_code'),
            'street' => $this->request->getBodyParam('street'),
            'number' => $this->request->getBodyParam('number'),
            'neighborhood' => $this->request->getBodyParam('neighborhood'),
            'city' => $this->request->getBodyParam('city'),
            'state' => $this->request->getBodyParam('state')
        ];
        
        if (!$this->validate($addressData, [
            'zip_code' => 'required|min:8',
            'street' => 'required|min:5',
            'number' => 'required',
            'neighborhood' => 'required',
            'city' => 'required',
            'state' => 'required'
        ])) {
            return $this->error('Dados do endereço inválidos', 400, $this->errors);
        }
        
        // Validar forma de pagamento
        $paymentMethod = $this->request->getBodyParam('payment_method');
        $paymentData = $this->request->getBodyParam('payment_data', []);
        
        if (!$this->validatePaymentMethod($paymentMethod, $paymentData)) {
            return $this->error('Dados de pagamento inválidos', 400, $this->errors);
        }
        
        // Gerar pedido
        $order = $this->createOrder($customerData, $addressData, $paymentMethod, $paymentData);
        
        // Limpar carrinho e cupom
        $_SESSION['cart'] = [];
        unset($_SESSION['applied_coupon']);
        unset($_SESSION['shipping_zip_code']);
        
        // Log da ação
        $this->logAction('checkout_completed', [
            'order_id' => $order['id'],
            'total' => $order['total'],
            'payment_method' => $paymentMethod
        ]);
        
        return $this->success([
            'message' => 'Pedido realizado com sucesso',
            'order' => $order,
            'redirect' => '/order-success?id=' . $order['id']
        ]);
    }
    
    /**
     * Obtém dados do carrinho via AJAX
     * 
     * @return Response
     */
    public function getCartData(): Response {
        $cart = $_SESSION['cart'] ?? [];
        $cartTotals = $this->calculateCartTotals();
        
        return $this->success([
            'cart_items' => array_values($cart),
            'cart_count' => $this->getCartCount(),
            'cart_totals' => $cartTotals,
            'applied_coupon' => $_SESSION['applied_coupon'] ?? null
        ]);
    }
    
    // ========== MÉTODOS PRIVADOS ==========
    
    /**
     * Obtém produto por ID
     * 
     * @param int $id ID do produto
     * @return array|null
     */
    private function getProductById(int $id): ?array {
        // Simulação - em produção, buscar do banco de dados
        $products = [
            1 => ['id' => 1, 'name' => 'Smartphone Neon X1', 'price' => 1299.99, 'image' => '/assets/images/products/smartphone-x1.jpg'],
            2 => ['id' => 2, 'name' => 'Headset Gamer RGB Pro', 'price' => 299.99, 'image' => '/assets/images/products/headset-rgb.jpg'],
            3 => ['id' => 3, 'name' => 'Smartwatch Neon Fit', 'price' => 599.99, 'image' => '/assets/images/products/smartwatch-fit.jpg']
        ];
        
        return $products[$id] ?? null;
    }
    
    /**
     * Verifica estoque do produto
     * 
     * @param int $productId ID do produto
     * @param int $quantity Quantidade desejada
     * @return bool
     */
    private function checkStock(int $productId, int $quantity): bool {
        // Simulação - em produção, verificar estoque real
        $stock = [
            1 => 50,
            2 => 30,
            3 => 25
        ];
        
        return ($stock[$productId] ?? 0) >= $quantity;
    }
    
    /**
     * Calcula totais do carrinho
     * 
     * @return array
     */
    private function calculateCartTotals(): array {
        $cart = $_SESSION['cart'] ?? [];
        $subtotal = 0;
        
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        // Aplicar desconto do cupom
        $discount = 0;
        $coupon = $_SESSION['applied_coupon'] ?? null;
        if ($coupon) {
            if ($coupon['type'] === 'percentage') {
                $discount = $subtotal * ($coupon['value'] / 100);
            } else {
                $discount = $coupon['value'];
            }
            
            // Limitar desconto ao subtotal
            $discount = min($discount, $subtotal);
        }
        
        // Calcular frete
        $shipping = $this->calculateShippingCost($subtotal - $discount);
        
        $total = $subtotal - $discount + $shipping;
        
        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'shipping' => $shipping,
            'total' => $total,
            'free_shipping' => $subtotal >= 200
        ];
    }
    
    /**
     * Obtém quantidade total de itens no carrinho
     * 
     * @return int
     */
    private function getCartCount(): int {
        $cart = $_SESSION['cart'] ?? [];
        return array_sum(array_column($cart, 'quantity'));
    }
    
    /**
     * Valida cupom de desconto
     * 
     * @param string $code Código do cupom
     * @return array|null
     */
    private function validateCoupon(string $code): ?array {
        // Simulação de cupons válidos
        $coupons = [
            'NEON10' => [
                'code' => 'NEON10',
                'type' => 'percentage',
                'value' => 10,
                'min_amount' => 100,
                'expires_at' => '2024-12-31'
            ],
            'FRETE20' => [
                'code' => 'FRETE20',
                'type' => 'fixed',
                'value' => 20,
                'min_amount' => 150,
                'expires_at' => '2024-12-31'
            ]
        ];
        
        $coupon = $coupons[strtoupper($code)] ?? null;
        
        if ($coupon && strtotime($coupon['expires_at']) >= time()) {
            return $coupon;
        }
        
        return null;
    }
    
    /**
     * Obtém opções de frete
     * 
     * @param string $zipCode CEP de destino
     * @return array
     */
    private function getShippingOptions(string $zipCode): array {
        // Simulação baseada no CEP
        $region = $this->getRegionByZipCode($zipCode);
        
        $options = [
            'standard' => [
                'name' => 'Entrega Padrão',
                'price' => $region === 'capital' ? 15.90 : 25.90,
                'days' => $region === 'capital' ? '3-5 dias úteis' : '5-8 dias úteis'
            ],
            'express' => [
                'name' => 'Entrega Expressa',
                'price' => $region === 'capital' ? 29.90 : 45.90,
                'days' => $region === 'capital' ? '1-2 dias úteis' : '2-4 dias úteis'
            ]
        ];
        
        return $options;
    }
    
    /**
     * Determina região baseada no CEP
     * 
     * @param string $zipCode CEP
     * @return string
     */
    private function getRegionByZipCode(string $zipCode): string {
        // Simulação simples - CEPs de capitais
        $capitalRanges = ['01000', '20000', '30000', '40000', '50000', '60000', '70000', '80000', '90000'];
        
        foreach ($capitalRanges as $range) {
            if (substr($zipCode, 0, 2) === substr($range, 0, 2)) {
                return 'capital';
            }
        }
        
        return 'interior';
    }
    
    /**
     * Calcula custo do frete
     * 
     * @param float $orderValue Valor do pedido
     * @return float
     */
    private function calculateShippingCost(float $orderValue): float {
        // Frete grátis acima de R$ 200
        if ($orderValue >= 200) {
            return 0;
        }
        
        // Usar frete padrão se não houver CEP selecionado
        $zipCode = $_SESSION['shipping_zip_code'] ?? null;
        if (!$zipCode) {
            return 15.90;
        }
        
        $options = $this->getShippingOptions($zipCode);
        return $options['standard']['price'];
    }
    
    /**
     * Valida método de pagamento
     * 
     * @param string $method Método de pagamento
     * @param array $data Dados do pagamento
     * @return bool
     */
    private function validatePaymentMethod(string $method, array $data): bool {
        switch ($method) {
            case 'credit_card':
                return $this->validate($data, [
                    'card_number' => 'required|min:16',
                    'card_name' => 'required|min:2',
                    'card_expiry' => 'required',
                    'card_cvv' => 'required|min:3'
                ]);
                
            case 'debit_card':
                return $this->validate($data, [
                    'card_number' => 'required|min:16',
                    'card_name' => 'required|min:2',
                    'card_expiry' => 'required',
                    'card_cvv' => 'required|min:3'
                ]);
                
            case 'pix':
            case 'boleto':
                return true; // Não requer dados adicionais
                
            default:
                return false;
        }
    }
    
    /**
     * Cria um novo pedido
     * 
     * @param array $customer Dados do cliente
     * @param array $address Dados do endereço
     * @param string $paymentMethod Método de pagamento
     * @param array $paymentData Dados do pagamento
     * @return array
     */
    private function createOrder(array $customer, array $address, string $paymentMethod, array $paymentData): array {
        $cart = $_SESSION['cart'] ?? [];
        $totals = $this->calculateCartTotals();
        
        $order = [
            'id' => 'NE' . date('Ymd') . rand(1000, 9999),
            'customer' => $customer,
            'address' => $address,
            'items' => array_values($cart),
            'payment_method' => $paymentMethod,
            'totals' => $totals,
            'total' => $totals['total'],
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // Em produção, salvar no banco de dados
        // Por enquanto, salvar em arquivo para demonstração
        $this->saveOrder($order);
        
        return $order;
    }
    
    /**
     * Salva pedido em arquivo (simulação)
     * 
     * @param array $order Dados do pedido
     */
    private function saveOrder(array $order): void {
        $ordersFile = __DIR__ . '/../../storage/orders.json';
        $ordersDir = dirname($ordersFile);
        
        if (!is_dir($ordersDir)) {
            mkdir($ordersDir, 0755, true);
        }
        
        $orders = [];
        if (file_exists($ordersFile)) {
            $orders = json_decode(file_get_contents($ordersFile), true) ?? [];
        }
        
        $orders[] = $order;
        file_put_contents($ordersFile, json_encode($orders, JSON_PRETTY_PRINT));
    }
}