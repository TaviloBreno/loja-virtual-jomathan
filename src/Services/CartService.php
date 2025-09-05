<?php

namespace Services;

use Repositories\ProductRepository;
use Entities\Cart;
use DTOs\CartDTO;

/**
 * Serviço de Carrinho - NeonShop
 * 
 * Responsável pela lógica de negócio do carrinho de compras:
 * - Gerenciamento de itens do carrinho
 * - Cálculos de preços e descontos
 * - Validação de produtos e quantidades
 * - Persistência em sessão
 */
class CartService {
    
    private ProductRepository $productRepository;
    private array $cart;
    
    public function __construct(ProductRepository $productRepository) {
        $this->productRepository = $productRepository;
        $this->loadCart();
    }
    
    /**
     * Adiciona produto ao carrinho
     * 
     * @param int $productId ID do produto
     * @param int $quantity Quantidade
     * @param array $options Opções (cor, tamanho, etc.)
     * @return CartDTO
     * @throws \InvalidArgumentException
     */
    public function addProduct(int $productId, int $quantity = 1, array $options = []): CartDTO {
        if ($productId <= 0 || $quantity <= 0) {
            throw new \InvalidArgumentException('ID do produto e quantidade devem ser positivos');
        }
        
        // Buscar produto
        $product = $this->productRepository->findById($productId);
        if (!$product) {
            throw new \InvalidArgumentException('Produto não encontrado');
        }
        
        // Verificar disponibilidade
        if (!$product['active']) {
            throw new \InvalidArgumentException('Produto não está disponível');
        }
        
        if ($product['stock_quantity'] < $quantity) {
            throw new \InvalidArgumentException('Quantidade insuficiente em estoque');
        }
        
        // Gerar chave única do item (produto + opções)
        $itemKey = $this->generateItemKey($productId, $options);
        
        // Verificar se item já existe no carrinho
        if (isset($this->cart['items'][$itemKey])) {
            $newQuantity = $this->cart['items'][$itemKey]['quantity'] + $quantity;
            
            // Verificar limite de estoque
            if ($newQuantity > $product['stock_quantity']) {
                throw new \InvalidArgumentException('Quantidade total excede o estoque disponível');
            }
            
            $this->cart['items'][$itemKey]['quantity'] = $newQuantity;
        } else {
            // Adicionar novo item
            $this->cart['items'][$itemKey] = [
                'product_id' => $productId,
                'product_name' => $product['name'],
                'product_image' => $product['image_url'],
                'unit_price' => $product['price'],
                'quantity' => $quantity,
                'options' => $options,
                'added_at' => date('Y-m-d H:i:s')
            ];
        }
        
        // Recalcular totais
        $this->recalculateCart();
        
        // Salvar carrinho
        $this->saveCart();
        
        return $this->getCartDTO();
    }
    
    /**
     * Remove produto do carrinho
     * 
     * @param string $itemKey Chave do item
     * @return CartDTO
     */
    public function removeProduct(string $itemKey): CartDTO {
        if (isset($this->cart['items'][$itemKey])) {
            unset($this->cart['items'][$itemKey]);
            
            // Recalcular totais
            $this->recalculateCart();
            
            // Salvar carrinho
            $this->saveCart();
        }
        
        return $this->getCartDTO();
    }
    
    /**
     * Atualiza quantidade de um item
     * 
     * @param string $itemKey Chave do item
     * @param int $quantity Nova quantidade
     * @return CartDTO
     * @throws \InvalidArgumentException
     */
    public function updateQuantity(string $itemKey, int $quantity): CartDTO {
        if (!isset($this->cart['items'][$itemKey])) {
            throw new \InvalidArgumentException('Item não encontrado no carrinho');
        }
        
        if ($quantity <= 0) {
            return $this->removeProduct($itemKey);
        }
        
        $item = $this->cart['items'][$itemKey];
        
        // Verificar disponibilidade
        $product = $this->productRepository->findById($item['product_id']);
        if (!$product) {
            throw new \InvalidArgumentException('Produto não encontrado');
        }
        
        if ($quantity > $product['stock_quantity']) {
            throw new \InvalidArgumentException('Quantidade excede o estoque disponível');
        }
        
        // Atualizar quantidade
        $this->cart['items'][$itemKey]['quantity'] = $quantity;
        
        // Recalcular totais
        $this->recalculateCart();
        
        // Salvar carrinho
        $this->saveCart();
        
        return $this->getCartDTO();
    }
    
    /**
     * Aplica cupom de desconto
     * 
     * @param string $couponCode Código do cupom
     * @return CartDTO
     * @throws \InvalidArgumentException
     */
    public function applyCoupon(string $couponCode): CartDTO {
        $coupon = $this->validateCoupon($couponCode);
        
        if (!$coupon['valid']) {
            throw new \InvalidArgumentException($coupon['message']);
        }
        
        // Aplicar cupom
        $this->cart['coupon'] = [
            'code' => $couponCode,
            'type' => $coupon['type'],
            'value' => $coupon['value'],
            'applied_at' => date('Y-m-d H:i:s')
        ];
        
        // Recalcular totais
        $this->recalculateCart();
        
        // Salvar carrinho
        $this->saveCart();
        
        return $this->getCartDTO();
    }
    
    /**
     * Remove cupom do carrinho
     * 
     * @return CartDTO
     */
    public function removeCoupon(): CartDTO {
        unset($this->cart['coupon']);
        
        // Recalcular totais
        $this->recalculateCart();
        
        // Salvar carrinho
        $this->saveCart();
        
        return $this->getCartDTO();
    }
    
    /**
     * Calcula frete para CEP
     * 
     * @param string $cep CEP de destino
     * @return array
     */
    public function calculateShipping(string $cep): array {
        // Validar CEP
        $cleanCep = preg_replace('/[^0-9]/', '', $cep);
        if (strlen($cleanCep) !== 8) {
            throw new \InvalidArgumentException('CEP inválido');
        }
        
        // Calcular peso e dimensões totais
        $totalWeight = $this->calculateTotalWeight();
        $totalValue = $this->cart['subtotal'] ?? 0;
        
        // Determinar região
        $region = $this->getRegionByCep($cleanCep);
        
        // Calcular opções de frete
        $shippingOptions = $this->calculateShippingOptions($region, $totalWeight, $totalValue);
        
        // Salvar dados de frete no carrinho
        $this->cart['shipping'] = [
            'cep' => $cleanCep,
            'region' => $region,
            'options' => $shippingOptions,
            'calculated_at' => date('Y-m-d H:i:s')
        ];
        
        $this->saveCart();
        
        return $shippingOptions;
    }
    
    /**
     * Seleciona opção de frete
     * 
     * @param string $option Opção selecionada (standard/express)
     * @return CartDTO
     */
    public function selectShippingOption(string $option): CartDTO {
        if (!isset($this->cart['shipping']['options'][$option])) {
            throw new \InvalidArgumentException('Opção de frete inválida');
        }
        
        $this->cart['shipping']['selected'] = $option;
        $this->cart['shipping']['selected_at'] = date('Y-m-d H:i:s');
        
        // Recalcular totais
        $this->recalculateCart();
        
        // Salvar carrinho
        $this->saveCart();
        
        return $this->getCartDTO();
    }
    
    /**
     * Obtém carrinho atual
     * 
     * @return CartDTO
     */
    public function getCart(): CartDTO {
        return $this->getCartDTO();
    }
    
    /**
     * Limpa carrinho
     * 
     * @return CartDTO
     */
    public function clearCart(): CartDTO {
        $this->cart = $this->getEmptyCart();
        $this->saveCart();
        
        return $this->getCartDTO();
    }
    
    /**
     * Conta total de itens no carrinho
     * 
     * @return int
     */
    public function getItemCount(): int {
        return array_sum(array_column($this->cart['items'] ?? [], 'quantity'));
    }
    
    /**
     * Verifica se carrinho está vazio
     * 
     * @return bool
     */
    public function isEmpty(): bool {
        return empty($this->cart['items']);
    }
    
    /**
     * Valida itens do carrinho
     * 
     * @return array Erros encontrados
     */
    public function validateCart(): array {
        $errors = [];
        
        if ($this->isEmpty()) {
            $errors[] = 'Carrinho está vazio';
            return $errors;
        }
        
        foreach ($this->cart['items'] as $itemKey => $item) {
            // Verificar se produto ainda existe
            $product = $this->productRepository->findById($item['product_id']);
            
            if (!$product) {
                $errors[] = 'Produto "' . $item['product_name'] . '" não está mais disponível';
                continue;
            }
            
            // Verificar se produto está ativo
            if (!$product['active']) {
                $errors[] = 'Produto "' . $product['name'] . '" não está mais disponível';
                continue;
            }
            
            // Verificar estoque
            if ($item['quantity'] > $product['stock_quantity']) {
                $errors[] = 'Produto "' . $product['name'] . '" tem apenas ' . 
                           $product['stock_quantity'] . ' unidades em estoque';
            }
            
            // Verificar se preço mudou
            if (abs($item['unit_price'] - $product['price']) > 0.01) {
                $errors[] = 'Preço do produto "' . $product['name'] . '" foi alterado';
            }
        }
        
        return $errors;
    }
    
    /**
     * Sincroniza carrinho com dados atuais dos produtos
     * 
     * @return CartDTO
     */
    public function syncCart(): CartDTO {
        $updated = false;
        
        foreach ($this->cart['items'] as $itemKey => $item) {
            $product = $this->productRepository->findById($item['product_id']);
            
            if (!$product || !$product['active']) {
                // Remover produto inativo
                unset($this->cart['items'][$itemKey]);
                $updated = true;
                continue;
            }
            
            // Atualizar dados do produto
            if ($this->cart['items'][$itemKey]['product_name'] !== $product['name'] ||
                $this->cart['items'][$itemKey]['unit_price'] !== $product['price'] ||
                $this->cart['items'][$itemKey]['product_image'] !== $product['image_url']) {
                
                $this->cart['items'][$itemKey]['product_name'] = $product['name'];
                $this->cart['items'][$itemKey]['unit_price'] = $product['price'];
                $this->cart['items'][$itemKey]['product_image'] = $product['image_url'];
                $updated = true;
            }
            
            // Ajustar quantidade se exceder estoque
            if ($item['quantity'] > $product['stock_quantity']) {
                $this->cart['items'][$itemKey]['quantity'] = $product['stock_quantity'];
                $updated = true;
            }
        }
        
        if ($updated) {
            $this->recalculateCart();
            $this->saveCart();
        }
        
        return $this->getCartDTO();
    }
    
    // ========== MÉTODOS PRIVADOS ==========
    
    /**
     * Carrega carrinho da sessão
     */
    private function loadCart(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $this->cart = $_SESSION['cart'] ?? $this->getEmptyCart();
    }
    
    /**
     * Salva carrinho na sessão
     */
    private function saveCart(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION['cart'] = $this->cart;
    }
    
    /**
     * Retorna estrutura de carrinho vazio
     * 
     * @return array
     */
    private function getEmptyCart(): array {
        return [
            'items' => [],
            'subtotal' => 0,
            'discount_amount' => 0,
            'shipping_cost' => 0,
            'total' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Gera chave única para item do carrinho
     * 
     * @param int $productId ID do produto
     * @param array $options Opções do produto
     * @return string
     */
    private function generateItemKey(int $productId, array $options = []): string {
        ksort($options); // Ordenar opções para consistência
        return $productId . '_' . md5(json_encode($options));
    }
    
    /**
     * Recalcula totais do carrinho
     */
    private function recalculateCart(): void {
        // Calcular subtotal
        $subtotal = 0;
        foreach ($this->cart['items'] as $item) {
            $subtotal += $item['unit_price'] * $item['quantity'];
        }
        
        $this->cart['subtotal'] = $subtotal;
        
        // Calcular desconto
        $discountAmount = 0;
        if (isset($this->cart['coupon'])) {
            $discountAmount = $this->calculateCouponDiscount($this->cart['coupon'], $subtotal);
        }
        
        $this->cart['discount_amount'] = $discountAmount;
        
        // Calcular frete
        $shippingCost = 0;
        if (isset($this->cart['shipping']['selected'])) {
            $selectedOption = $this->cart['shipping']['selected'];
            $shippingCost = $this->cart['shipping']['options'][$selectedOption]['price'] ?? 0;
        }
        
        $this->cart['shipping_cost'] = $shippingCost;
        
        // Calcular total
        $this->cart['total'] = $subtotal - $discountAmount + $shippingCost;
        
        // Atualizar timestamp
        $this->cart['updated_at'] = date('Y-m-d H:i:s');
    }
    
    /**
     * Converte carrinho para DTO
     * 
     * @return CartDTO
     */
    private function getCartDTO(): CartDTO {
        // Processar itens
        $processedItems = [];
        foreach ($this->cart['items'] as $itemKey => $item) {
            $processedItems[$itemKey] = array_merge($item, [
                'item_key' => $itemKey,
                'total_price' => $item['unit_price'] * $item['quantity'],
                'formatted_unit_price' => $this->formatPrice($item['unit_price']),
                'formatted_total_price' => $this->formatPrice($item['unit_price'] * $item['quantity'])
            ]);
        }
        
        // Processar dados do carrinho
        $cartData = array_merge($this->cart, [
            'items' => $processedItems,
            'item_count' => $this->getItemCount(),
            'is_empty' => $this->isEmpty(),
            'formatted_subtotal' => $this->formatPrice($this->cart['subtotal']),
            'formatted_discount_amount' => $this->formatPrice($this->cart['discount_amount']),
            'formatted_shipping_cost' => $this->formatPrice($this->cart['shipping_cost']),
            'formatted_total' => $this->formatPrice($this->cart['total'])
        ]);
        
        return new CartDTO($cartData);
    }
    
    /**
     * Valida cupom de desconto
     * 
     * @param string $code Código do cupom
     * @return array
     */
    private function validateCoupon(string $code): array {
        // Dados simulados de cupons
        $coupons = [
            'NEON10' => [
                'code' => 'NEON10',
                'type' => 'percentage',
                'value' => 10,
                'min_amount' => 100,
                'max_discount' => 50,
                'active' => true
            ],
            'FRETE20' => [
                'code' => 'FRETE20',
                'type' => 'fixed',
                'value' => 20,
                'min_amount' => 150,
                'max_discount' => 20,
                'active' => true
            ],
            'WELCOME15' => [
                'code' => 'WELCOME15',
                'type' => 'percentage',
                'value' => 15,
                'min_amount' => 200,
                'max_discount' => 75,
                'active' => true
            ]
        ];
        
        $coupon = $coupons[strtoupper($code)] ?? null;
        
        if (!$coupon) {
            return [
                'valid' => false,
                'message' => 'Cupom inválido ou expirado'
            ];
        }
        
        if (!$coupon['active']) {
            return [
                'valid' => false,
                'message' => 'Cupom não está mais ativo'
            ];
        }
        
        // Verificar valor mínimo
        if ($this->cart['subtotal'] < $coupon['min_amount']) {
            return [
                'valid' => false,
                'message' => 'Valor mínimo não atingido: R$ ' . number_format($coupon['min_amount'], 2, ',', '.')
            ];
        }
        
        return array_merge($coupon, ['valid' => true]);
    }
    
    /**
     * Calcula desconto do cupom
     * 
     * @param array $coupon Dados do cupom
     * @param float $amount Valor base
     * @return float
     */
    private function calculateCouponDiscount(array $coupon, float $amount): float {
        if ($coupon['type'] === 'percentage') {
            $discount = $amount * ($coupon['value'] / 100);
            return min($discount, $coupon['max_discount'] ?? $discount);
        } else {
            return min($coupon['value'], $amount);
        }
    }
    
    /**
     * Calcula peso total do carrinho
     * 
     * @return float
     */
    private function calculateTotalWeight(): float {
        $totalWeight = 0;
        
        foreach ($this->cart['items'] as $item) {
            $product = $this->productRepository->findById($item['product_id']);
            $weight = $product['weight'] ?? 0.5; // Peso padrão 500g
            $totalWeight += $weight * $item['quantity'];
        }
        
        return $totalWeight;
    }
    
    /**
     * Determina região por CEP
     * 
     * @param string $cep CEP
     * @return string
     */
    private function getRegionByCep(string $cep): string {
        $capitalRanges = ['01', '20', '30', '40', '50', '60', '70', '80', '90'];
        $prefix = substr($cep, 0, 2);
        
        return in_array($prefix, $capitalRanges) ? 'capital' : 'interior';
    }
    
    /**
     * Calcula opções de frete
     * 
     * @param string $region Região
     * @param float $weight Peso total
     * @param float $value Valor total
     * @return array
     */
    private function calculateShippingOptions(string $region, float $weight, float $value): array {
        $basePrice = $region === 'capital' ? 15.90 : 25.90;
        $weightMultiplier = max(1, ceil($weight / 1)); // A cada 1kg
        
        $standardPrice = $basePrice * $weightMultiplier;
        $expressPrice = $standardPrice * 1.8;
        
        // Frete grátis acima de R$ 150
        if ($value >= 150) {
            $standardPrice = 0;
        }
        
        return [
            'standard' => [
                'name' => 'Entrega Padrão',
                'price' => $standardPrice,
                'formatted_price' => $this->formatPrice($standardPrice),
                'days' => $region === 'capital' ? '3-5 dias úteis' : '5-8 dias úteis',
                'free' => $standardPrice === 0
            ],
            'express' => [
                'name' => 'Entrega Expressa',
                'price' => $expressPrice,
                'formatted_price' => $this->formatPrice($expressPrice),
                'days' => $region === 'capital' ? '1-2 dias úteis' : '2-4 dias úteis',
                'free' => false
            ]
        ];
    }
    
    /**
     * Formata preço
     * 
     * @param float $price Preço
     * @return string
     */
    private function formatPrice(float $price): string {
        return 'R$ ' . number_format($price, 2, ',', '.');
    }
}