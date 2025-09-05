<?php

namespace Services;

use Repositories\OrderRepository;
use Repositories\ProductRepository;
use Entities\Order;
use DTOs\OrderDTO;

/**
 * Serviço de Pedidos - NeonShop
 * 
 * Responsável pela lógica de negócio relacionada a pedidos:
 * - Criação e processamento de pedidos
 * - Cálculos de preços e fretes
 * - Validação de dados
 * - Gerenciamento de status
 */
class OrderService {
    
    private OrderRepository $orderRepository;
    private ProductRepository $productRepository;
    
    public function __construct(
        OrderRepository $orderRepository,
        ProductRepository $productRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
    }
    
    /**
     * Cria novo pedido
     * 
     * @param array $orderData Dados do pedido
     * @param array $items Itens do pedido
     * @return OrderDTO
     * @throws \InvalidArgumentException
     */
    public function createOrder(array $orderData, array $items): OrderDTO {
        // Validar dados do pedido
        $this->validateOrderData($orderData);
        
        // Validar e processar itens
        $processedItems = $this->validateAndProcessItems($items);
        
        // Calcular totais
        $totals = $this->calculateOrderTotals($processedItems, $orderData);
        
        // Preparar dados completos do pedido
        $completeOrderData = array_merge($orderData, [
            'subtotal' => $totals['subtotal'],
            'shipping_cost' => $totals['shipping_cost'],
            'discount_amount' => $totals['discount_amount'],
            'tax_amount' => $totals['tax_amount'],
            'total' => $totals['total'],
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        // Salvar pedido
        $order = $this->orderRepository->create($completeOrderData, $processedItems);
        
        // Atualizar estoque dos produtos
        $this->updateProductStock($processedItems);
        
        // Registrar no histórico
        $this->addOrderHistory($order['id'], 'created', 'Pedido criado');
        
        return new OrderDTO($order);
    }
    
    /**
     * Obtém pedido por ID
     * 
     * @param int $id ID do pedido
     * @return OrderDTO|null
     */
    public function getOrderById(int $id): ?OrderDTO {
        if ($id <= 0) {
            return null;
        }
        
        $order = $this->orderRepository->findById($id);
        
        if (!$order) {
            return null;
        }
        
        // Processar dados do pedido
        $processedOrder = $this->processOrderData($order);
        
        return new OrderDTO($processedOrder);
    }
    
    /**
     * Obtém pedidos com filtros
     * 
     * @param array $filters Filtros
     * @param int $page Página
     * @param int $limit Limite
     * @return array
     */
    public function getOrders(array $filters = [], int $page = 1, int $limit = 20): array {
        $validatedFilters = $this->validateOrderFilters($filters);
        
        $orders = $this->orderRepository->findWithFilters($validatedFilters, $page, $limit);
        
        $processedOrders = array_map(function($order) {
            return $this->processOrderData($order);
        }, $orders['items']);
        
        return [
            'items' => $processedOrders,
            'pagination' => $orders['pagination'],
            'filters_applied' => $validatedFilters
        ];
    }
    
    /**
     * Atualiza status do pedido
     * 
     * @param int $id ID do pedido
     * @param string $status Novo status
     * @param string $notes Observações
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function updateOrderStatus(int $id, string $status, string $notes = ''): bool {
        if ($id <= 0) {
            return false;
        }
        
        // Validar status
        if (!$this->isValidStatus($status)) {
            throw new \InvalidArgumentException('Status inválido: ' . $status);
        }
        
        // Verificar se pedido existe
        $order = $this->orderRepository->findById($id);
        if (!$order) {
            return false;
        }
        
        // Verificar se transição de status é válida
        if (!$this->isValidStatusTransition($order['status'], $status)) {
            throw new \InvalidArgumentException(
                'Transição de status inválida: ' . $order['status'] . ' -> ' . $status
            );
        }
        
        // Atualizar status
        $updated = $this->orderRepository->updateStatus($id, $status);
        
        if ($updated) {
            // Registrar no histórico
            $this->addOrderHistory($id, $status, $notes ?: 'Status atualizado para: ' . $status);
            
            // Executar ações específicas do status
            $this->executeStatusActions($id, $status, $order);
        }
        
        return $updated;
    }
    
    /**
     * Cancela pedido
     * 
     * @param int $id ID do pedido
     * @param string $reason Motivo do cancelamento
     * @return bool
     */
    public function cancelOrder(int $id, string $reason = ''): bool {
        $order = $this->orderRepository->findById($id);
        
        if (!$order) {
            return false;
        }
        
        // Verificar se pedido pode ser cancelado
        if (!$this->canCancelOrder($order)) {
            return false;
        }
        
        // Cancelar pedido
        $cancelled = $this->orderRepository->updateStatus($id, 'cancelled');
        
        if ($cancelled) {
            // Restaurar estoque
            $this->restoreProductStock($order['items']);
            
            // Registrar no histórico
            $this->addOrderHistory($id, 'cancelled', $reason ?: 'Pedido cancelado');
        }
        
        return $cancelled;
    }
    
    /**
     * Calcula frete para CEP
     * 
     * @param string $cep CEP de destino
     * @param array $items Itens do carrinho
     * @return array
     */
    public function calculateShipping(string $cep, array $items): array {
        // Validar CEP
        $cleanCep = preg_replace('/[^0-9]/', '', $cep);
        if (strlen($cleanCep) !== 8) {
            throw new \InvalidArgumentException('CEP inválido');
        }
        
        // Calcular peso e dimensões totais
        $totalWeight = $this->calculateTotalWeight($items);
        $totalValue = $this->calculateItemsTotal($items);
        
        // Determinar região
        $region = $this->getRegionByCep($cleanCep);
        
        // Calcular opções de frete
        $shippingOptions = $this->calculateShippingOptions($region, $totalWeight, $totalValue);
        
        return [
            'cep' => $cleanCep,
            'region' => $region,
            'options' => $shippingOptions
        ];
    }
    
    /**
     * Aplica cupom de desconto
     * 
     * @param string $code Código do cupom
     * @param array $items Itens do carrinho
     * @return array
     */
    public function applyCoupon(string $code, array $items): array {
        $coupon = $this->getCouponData($code);
        
        if (!$coupon) {
            return [
                'valid' => false,
                'message' => 'Cupom inválido ou expirado'
            ];
        }
        
        // Verificar valor mínimo
        $itemsTotal = $this->calculateItemsTotal($items);
        if ($itemsTotal < $coupon['min_amount']) {
            return [
                'valid' => false,
                'message' => 'Valor mínimo não atingido: R$ ' . number_format($coupon['min_amount'], 2, ',', '.')
            ];
        }
        
        // Calcular desconto
        $discount = $this->calculateCouponDiscount($coupon, $itemsTotal);
        
        return [
            'valid' => true,
            'coupon' => $coupon,
            'discount_amount' => $discount,
            'formatted_discount' => 'R$ ' . number_format($discount, 2, ',', '.')
        ];
    }
    
    /**
     * Obtém estatísticas de pedidos
     * 
     * @param string $period Período (day, week, month, year)
     * @return array
     */
    public function getOrderStats(string $period = 'month'): array {
        return [
            'total_orders' => $this->orderRepository->countByPeriod($period),
            'total_revenue' => $this->orderRepository->sumRevenueByPeriod($period),
            'average_order_value' => $this->orderRepository->averageOrderValueByPeriod($period),
            'orders_by_status' => $this->orderRepository->countByStatus($period),
            'top_products' => $this->orderRepository->getTopProductsByPeriod($period, 10)
        ];
    }
    
    // ========== MÉTODOS PRIVADOS ==========
    
    /**
     * Valida dados do pedido
     * 
     * @param array $data Dados do pedido
     * @throws \InvalidArgumentException
     */
    private function validateOrderData(array $data): void {
        $errors = [];
        
        // Validar dados do cliente
        if (empty($data['customer_name']) || strlen(trim($data['customer_name'])) < 2) {
            $errors[] = 'Nome do cliente é obrigatório';
        }
        
        if (empty($data['customer_email']) || !filter_var($data['customer_email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email válido é obrigatório';
        }
        
        if (empty($data['customer_phone'])) {
            $errors[] = 'Telefone é obrigatório';
        }
        
        // Validar endereço de entrega
        $requiredAddressFields = ['street', 'number', 'neighborhood', 'city', 'state', 'zip_code'];
        foreach ($requiredAddressFields as $field) {
            if (empty($data['shipping_address'][$field])) {
                $errors[] = 'Campo de endereço obrigatório: ' . $field;
            }
        }
        
        // Validar método de pagamento
        if (empty($data['payment_method'])) {
            $errors[] = 'Método de pagamento é obrigatório';
        }
        
        if (!empty($errors)) {
            throw new \InvalidArgumentException('Dados inválidos: ' . implode(', ', $errors));
        }
    }
    
    /**
     * Valida e processa itens do pedido
     * 
     * @param array $items Itens do pedido
     * @return array
     * @throws \InvalidArgumentException
     */
    private function validateAndProcessItems(array $items): array {
        if (empty($items)) {
            throw new \InvalidArgumentException('Pedido deve ter pelo menos um item');
        }
        
        $processedItems = [];
        
        foreach ($items as $item) {
            // Validar item
            if (!isset($item['product_id']) || !isset($item['quantity'])) {
                throw new \InvalidArgumentException('Item inválido: product_id e quantity são obrigatórios');
            }
            
            $productId = (int)$item['product_id'];
            $quantity = (int)$item['quantity'];
            
            if ($productId <= 0 || $quantity <= 0) {
                throw new \InvalidArgumentException('Product ID e quantidade devem ser positivos');
            }
            
            // Buscar produto
            $product = $this->productRepository->findById($productId);
            if (!$product) {
                throw new \InvalidArgumentException('Produto não encontrado: ' . $productId);
            }
            
            // Verificar disponibilidade
            if ($product['stock_quantity'] < $quantity) {
                throw new \InvalidArgumentException(
                    'Quantidade insuficiente em estoque para: ' . $product['name']
                );
            }
            
            // Processar item
            $processedItems[] = [
                'product_id' => $productId,
                'product_name' => $product['name'],
                'quantity' => $quantity,
                'unit_price' => $product['price'],
                'total_price' => $product['price'] * $quantity,
                'product_data' => $product
            ];
        }
        
        return $processedItems;
    }
    
    /**
     * Calcula totais do pedido
     * 
     * @param array $items Itens processados
     * @param array $orderData Dados do pedido
     * @return array
     */
    private function calculateOrderTotals(array $items, array $orderData): array {
        // Subtotal
        $subtotal = array_sum(array_column($items, 'total_price'));
        
        // Desconto
        $discountAmount = 0;
        if (!empty($orderData['coupon_code'])) {
            $coupon = $this->getCouponData($orderData['coupon_code']);
            if ($coupon) {
                $discountAmount = $this->calculateCouponDiscount($coupon, $subtotal);
            }
        }
        
        // Frete
        $shippingCost = $orderData['shipping_cost'] ?? 0;
        
        // Taxa (impostos)
        $taxRate = 0.18; // 18% de impostos
        $taxAmount = ($subtotal - $discountAmount) * $taxRate;
        
        // Total
        $total = $subtotal - $discountAmount + $shippingCost + $taxAmount;
        
        return [
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'shipping_cost' => $shippingCost,
            'tax_amount' => $taxAmount,
            'total' => $total
        ];
    }
    
    /**
     * Processa dados do pedido
     * 
     * @param array $order Dados do pedido
     * @return array
     */
    private function processOrderData(array $order): array {
        // Formatar valores monetários
        $order['formatted_subtotal'] = $this->formatPrice($order['subtotal']);
        $order['formatted_shipping_cost'] = $this->formatPrice($order['shipping_cost']);
        $order['formatted_discount_amount'] = $this->formatPrice($order['discount_amount']);
        $order['formatted_tax_amount'] = $this->formatPrice($order['tax_amount']);
        $order['formatted_total'] = $this->formatPrice($order['total']);
        
        // Status traduzido
        $order['status_label'] = $this->getStatusLabel($order['status']);
        
        // Processar endereço
        if (isset($order['shipping_address']) && is_string($order['shipping_address'])) {
            $order['shipping_address'] = json_decode($order['shipping_address'], true);
        }
        
        // Processar itens
        if (isset($order['items'])) {
            $order['items'] = array_map(function($item) {
                $item['formatted_unit_price'] = $this->formatPrice($item['unit_price']);
                $item['formatted_total_price'] = $this->formatPrice($item['total_price']);
                return $item;
            }, $order['items']);
        }
        
        return $order;
    }
    
    /**
     * Valida filtros de pedidos
     * 
     * @param array $filters Filtros
     * @return array
     */
    private function validateOrderFilters(array $filters): array {
        $validatedFilters = [];
        
        // Status
        if (isset($filters['status']) && $this->isValidStatus($filters['status'])) {
            $validatedFilters['status'] = $filters['status'];
        }
        
        // Período
        if (isset($filters['date_from']) && $this->isValidDate($filters['date_from'])) {
            $validatedFilters['date_from'] = $filters['date_from'];
        }
        
        if (isset($filters['date_to']) && $this->isValidDate($filters['date_to'])) {
            $validatedFilters['date_to'] = $filters['date_to'];
        }
        
        // Cliente
        if (isset($filters['customer_email']) && !empty($filters['customer_email'])) {
            $validatedFilters['customer_email'] = trim($filters['customer_email']);
        }
        
        return $validatedFilters;
    }
    
    /**
     * Verifica se status é válido
     * 
     * @param string $status Status
     * @return bool
     */
    private function isValidStatus(string $status): bool {
        $validStatuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        return in_array($status, $validStatuses);
    }
    
    /**
     * Verifica se transição de status é válida
     * 
     * @param string $currentStatus Status atual
     * @param string $newStatus Novo status
     * @return bool
     */
    private function isValidStatusTransition(string $currentStatus, string $newStatus): bool {
        $transitions = [
            'pending' => ['processing', 'cancelled'],
            'processing' => ['shipped', 'cancelled'],
            'shipped' => ['delivered'],
            'delivered' => [],
            'cancelled' => []
        ];
        
        return in_array($newStatus, $transitions[$currentStatus] ?? []);
    }
    
    /**
     * Executa ações específicas do status
     * 
     * @param int $orderId ID do pedido
     * @param string $status Status
     * @param array $order Dados do pedido
     */
    private function executeStatusActions(int $orderId, string $status, array $order): void {
        switch ($status) {
            case 'processing':
                // Enviar email de confirmação
                $this->sendOrderConfirmationEmail($order);
                break;
                
            case 'shipped':
                // Enviar email com código de rastreamento
                $this->sendShippingNotificationEmail($order);
                break;
                
            case 'delivered':
                // Enviar email de entrega
                $this->sendDeliveryConfirmationEmail($order);
                break;
        }
    }
    
    /**
     * Verifica se pedido pode ser cancelado
     * 
     * @param array $order Dados do pedido
     * @return bool
     */
    private function canCancelOrder(array $order): bool {
        $cancellableStatuses = ['pending', 'processing'];
        return in_array($order['status'], $cancellableStatuses);
    }
    
    /**
     * Atualiza estoque dos produtos
     * 
     * @param array $items Itens do pedido
     */
    private function updateProductStock(array $items): void {
        foreach ($items as $item) {
            $this->productRepository->decrementStock($item['product_id'], $item['quantity']);
        }
    }
    
    /**
     * Restaura estoque dos produtos
     * 
     * @param array $items Itens do pedido
     */
    private function restoreProductStock(array $items): void {
        foreach ($items as $item) {
            $this->productRepository->incrementStock($item['product_id'], $item['quantity']);
        }
    }
    
    /**
     * Adiciona entrada no histórico do pedido
     * 
     * @param int $orderId ID do pedido
     * @param string $status Status
     * @param string $notes Observações
     */
    private function addOrderHistory(int $orderId, string $status, string $notes): void {
        $this->orderRepository->addHistory($orderId, $status, $notes);
    }
    
    /**
     * Calcula peso total dos itens
     * 
     * @param array $items Itens
     * @return float
     */
    private function calculateTotalWeight(array $items): float {
        $totalWeight = 0;
        
        foreach ($items as $item) {
            $product = $this->productRepository->findById($item['product_id']);
            $weight = $product['weight'] ?? 0.5; // Peso padrão 500g
            $totalWeight += $weight * $item['quantity'];
        }
        
        return $totalWeight;
    }
    
    /**
     * Calcula total dos itens
     * 
     * @param array $items Itens
     * @return float
     */
    private function calculateItemsTotal(array $items): float {
        $total = 0;
        
        foreach ($items as $item) {
            $product = $this->productRepository->findById($item['product_id']);
            $total += $product['price'] * $item['quantity'];
        }
        
        return $total;
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
                'days' => $region === 'capital' ? '3-5 dias úteis' : '5-8 dias úteis',
                'free' => $standardPrice === 0
            ],
            'express' => [
                'name' => 'Entrega Expressa',
                'price' => $expressPrice,
                'days' => $region === 'capital' ? '1-2 dias úteis' : '2-4 dias úteis',
                'free' => false
            ]
        ];
    }
    
    /**
     * Obtém dados do cupom
     * 
     * @param string $code Código do cupom
     * @return array|null
     */
    private function getCouponData(string $code): ?array {
        $coupons = [
            'NEON10' => [
                'code' => 'NEON10',
                'type' => 'percentage',
                'value' => 10,
                'min_amount' => 100,
                'max_discount' => 50
            ],
            'FRETE20' => [
                'code' => 'FRETE20',
                'type' => 'fixed',
                'value' => 20,
                'min_amount' => 150,
                'max_discount' => 20
            ]
        ];
        
        return $coupons[strtoupper($code)] ?? null;
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
     * Formata preço
     * 
     * @param float $price Preço
     * @return string
     */
    private function formatPrice(float $price): string {
        return 'R$ ' . number_format($price, 2, ',', '.');
    }
    
    /**
     * Obtém label do status
     * 
     * @param string $status Status
     * @return string
     */
    private function getStatusLabel(string $status): string {
        $labels = [
            'pending' => 'Pendente',
            'processing' => 'Processando',
            'shipped' => 'Enviado',
            'delivered' => 'Entregue',
            'cancelled' => 'Cancelado'
        ];
        
        return $labels[$status] ?? $status;
    }
    
    /**
     * Verifica se data é válida
     * 
     * @param string $date Data
     * @return bool
     */
    private function isValidDate(string $date): bool {
        return (bool)strtotime($date);
    }
    
    // Métodos de email (simulação)
    private function sendOrderConfirmationEmail(array $order): void {
        // Em produção, enviar email real
        error_log('Email de confirmação enviado para pedido: ' . $order['id']);
    }
    
    private function sendShippingNotificationEmail(array $order): void {
        // Em produção, enviar email real
        error_log('Email de envio enviado para pedido: ' . $order['id']);
    }
    
    private function sendDeliveryConfirmationEmail(array $order): void {
        // Em produção, enviar email real
        error_log('Email de entrega enviado para pedido: ' . $order['id']);
    }
}