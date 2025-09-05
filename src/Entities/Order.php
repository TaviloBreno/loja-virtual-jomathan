<?php

namespace Entities;

/**
 * Entidade Pedido - NeonShop
 * 
 * Representa um pedido no sistema de e-commerce.
 * Contém todas as propriedades e métodos relacionados ao pedido.
 */
class Order {
    
    private int $id;
    private string $orderNumber;
    private int $customerId;
    private string $customerName;
    private string $customerEmail;
    private string $customerPhone;
    private array $shippingAddress;
    private array $billingAddress;
    private array $items;
    private float $subtotal;
    private float $shippingCost;
    private float $discount;
    private float $total;
    private string $status;
    private string $paymentMethod;
    private string $paymentStatus;
    private ?string $couponCode;
    private ?string $trackingCode;
    private ?string $notes;
    private string $createdAt;
    private string $updatedAt;
    private ?string $shippedAt;
    private ?string $deliveredAt;
    
    // Status possíveis do pedido
    public const STATUS_PENDING = 'pending';
    public const STATUS_CONFIRMED = 'confirmed';
    public const STATUS_PROCESSING = 'processing';
    public const STATUS_SHIPPED = 'shipped';
    public const STATUS_DELIVERED = 'delivered';
    public const STATUS_CANCELLED = 'cancelled';
    public const STATUS_REFUNDED = 'refunded';
    
    // Status possíveis do pagamento
    public const PAYMENT_PENDING = 'pending';
    public const PAYMENT_APPROVED = 'approved';
    public const PAYMENT_REJECTED = 'rejected';
    public const PAYMENT_REFUNDED = 'refunded';
    
    /**
     * Construtor da entidade Order
     * 
     * @param array $data Dados do pedido
     */
    public function __construct(array $data = []) {
        $this->id = $data['id'] ?? 0;
        $this->orderNumber = $data['order_number'] ?? $this->generateOrderNumber();
        $this->customerId = (int)($data['customer_id'] ?? 0);
        $this->customerName = $data['customer_name'] ?? '';
        $this->customerEmail = $data['customer_email'] ?? '';
        $this->customerPhone = $data['customer_phone'] ?? '';
        $this->shippingAddress = $data['shipping_address'] ?? [];
        $this->billingAddress = $data['billing_address'] ?? [];
        $this->items = $data['items'] ?? [];
        $this->subtotal = (float)($data['subtotal'] ?? 0);
        $this->shippingCost = (float)($data['shipping_cost'] ?? 0);
        $this->discount = (float)($data['discount'] ?? 0);
        $this->total = (float)($data['total'] ?? 0);
        $this->status = $data['status'] ?? self::STATUS_PENDING;
        $this->paymentMethod = $data['payment_method'] ?? '';
        $this->paymentStatus = $data['payment_status'] ?? self::PAYMENT_PENDING;
        $this->couponCode = $data['coupon_code'] ?? null;
        $this->trackingCode = $data['tracking_code'] ?? null;
        $this->notes = $data['notes'] ?? null;
        $this->createdAt = $data['created_at'] ?? date('Y-m-d H:i:s');
        $this->updatedAt = $data['updated_at'] ?? date('Y-m-d H:i:s');
        $this->shippedAt = $data['shipped_at'] ?? null;
        $this->deliveredAt = $data['delivered_at'] ?? null;
    }
    
    // ========== GETTERS ==========
    
    public function getId(): int {
        return $this->id;
    }
    
    public function getOrderNumber(): string {
        return $this->orderNumber;
    }
    
    public function getCustomerId(): int {
        return $this->customerId;
    }
    
    public function getCustomerName(): string {
        return $this->customerName;
    }
    
    public function getCustomerEmail(): string {
        return $this->customerEmail;
    }
    
    public function getCustomerPhone(): string {
        return $this->customerPhone;
    }
    
    public function getShippingAddress(): array {
        return $this->shippingAddress;
    }
    
    public function getBillingAddress(): array {
        return $this->billingAddress;
    }
    
    public function getItems(): array {
        return $this->items;
    }
    
    public function getSubtotal(): float {
        return $this->subtotal;
    }
    
    public function getShippingCost(): float {
        return $this->shippingCost;
    }
    
    public function getDiscount(): float {
        return $this->discount;
    }
    
    public function getTotal(): float {
        return $this->total;
    }
    
    public function getStatus(): string {
        return $this->status;
    }
    
    public function getPaymentMethod(): string {
        return $this->paymentMethod;
    }
    
    public function getPaymentStatus(): string {
        return $this->paymentStatus;
    }
    
    public function getCouponCode(): ?string {
        return $this->couponCode;
    }
    
    public function getTrackingCode(): ?string {
        return $this->trackingCode;
    }
    
    public function getNotes(): ?string {
        return $this->notes;
    }
    
    public function getCreatedAt(): string {
        return $this->createdAt;
    }
    
    public function getUpdatedAt(): string {
        return $this->updatedAt;
    }
    
    public function getShippedAt(): ?string {
        return $this->shippedAt;
    }
    
    public function getDeliveredAt(): ?string {
        return $this->deliveredAt;
    }
    
    // ========== SETTERS ==========
    
    public function setId(int $id): void {
        $this->id = $id;
    }
    
    public function setOrderNumber(string $orderNumber): void {
        $this->orderNumber = $orderNumber;
        $this->updateTimestamp();
    }
    
    public function setCustomerId(int $customerId): void {
        $this->customerId = $customerId;
        $this->updateTimestamp();
    }
    
    public function setCustomerName(string $customerName): void {
        $this->customerName = $customerName;
        $this->updateTimestamp();
    }
    
    public function setCustomerEmail(string $customerEmail): void {
        $this->customerEmail = $customerEmail;
        $this->updateTimestamp();
    }
    
    public function setCustomerPhone(string $customerPhone): void {
        $this->customerPhone = $customerPhone;
        $this->updateTimestamp();
    }
    
    public function setShippingAddress(array $shippingAddress): void {
        $this->shippingAddress = $shippingAddress;
        $this->updateTimestamp();
    }
    
    public function setBillingAddress(array $billingAddress): void {
        $this->billingAddress = $billingAddress;
        $this->updateTimestamp();
    }
    
    public function setItems(array $items): void {
        $this->items = $items;
        $this->recalculateTotal();
        $this->updateTimestamp();
    }
    
    public function setSubtotal(float $subtotal): void {
        $this->subtotal = $subtotal;
        $this->updateTimestamp();
    }
    
    public function setShippingCost(float $shippingCost): void {
        $this->shippingCost = $shippingCost;
        $this->recalculateTotal();
        $this->updateTimestamp();
    }
    
    public function setDiscount(float $discount): void {
        $this->discount = $discount;
        $this->recalculateTotal();
        $this->updateTimestamp();
    }
    
    public function setTotal(float $total): void {
        $this->total = $total;
        $this->updateTimestamp();
    }
    
    public function setStatus(string $status): void {
        if ($this->isValidStatus($status)) {
            $this->status = $status;
            
            // Atualizar timestamps específicos
            if ($status === self::STATUS_SHIPPED && !$this->shippedAt) {
                $this->shippedAt = date('Y-m-d H:i:s');
            }
            
            if ($status === self::STATUS_DELIVERED && !$this->deliveredAt) {
                $this->deliveredAt = date('Y-m-d H:i:s');
            }
            
            $this->updateTimestamp();
        }
    }
    
    public function setPaymentMethod(string $paymentMethod): void {
        $this->paymentMethod = $paymentMethod;
        $this->updateTimestamp();
    }
    
    public function setPaymentStatus(string $paymentStatus): void {
        if ($this->isValidPaymentStatus($paymentStatus)) {
            $this->paymentStatus = $paymentStatus;
            $this->updateTimestamp();
        }
    }
    
    public function setCouponCode(?string $couponCode): void {
        $this->couponCode = $couponCode;
        $this->updateTimestamp();
    }
    
    public function setTrackingCode(?string $trackingCode): void {
        $this->trackingCode = $trackingCode;
        $this->updateTimestamp();
    }
    
    public function setNotes(?string $notes): void {
        $this->notes = $notes;
        $this->updateTimestamp();
    }
    
    // ========== MÉTODOS DE NEGÓCIO ==========
    
    /**
     * Adiciona um item ao pedido
     * 
     * @param array $item Dados do item
     */
    public function addItem(array $item): void {
        $this->items[] = $item;
        $this->recalculateTotal();
        $this->updateTimestamp();
    }
    
    /**
     * Remove um item do pedido
     * 
     * @param int $productId ID do produto
     */
    public function removeItem(int $productId): void {
        $this->items = array_filter($this->items, function($item) use ($productId) {
            return $item['product_id'] !== $productId;
        });
        
        $this->items = array_values($this->items); // Reindexar
        $this->recalculateTotal();
        $this->updateTimestamp();
    }
    
    /**
     * Atualiza a quantidade de um item
     * 
     * @param int $productId ID do produto
     * @param int $quantity Nova quantidade
     */
    public function updateItemQuantity(int $productId, int $quantity): void {
        foreach ($this->items as &$item) {
            if ($item['product_id'] === $productId) {
                $item['quantity'] = $quantity;
                $item['total'] = $item['price'] * $quantity;
                break;
            }
        }
        
        $this->recalculateTotal();
        $this->updateTimestamp();
    }
    
    /**
     * Recalcula o total do pedido
     */
    public function recalculateTotal(): void {
        $this->subtotal = 0;
        
        foreach ($this->items as $item) {
            $this->subtotal += $item['total'] ?? ($item['price'] * $item['quantity']);
        }
        
        $this->total = $this->subtotal + $this->shippingCost - $this->discount;
    }
    
    /**
     * Obtém a quantidade total de itens
     * 
     * @return int
     */
    public function getTotalItems(): int {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item['quantity'];
        }
        return $total;
    }
    
    /**
     * Verifica se o pedido pode ser cancelado
     * 
     * @return bool
     */
    public function canBeCancelled(): bool {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_CONFIRMED,
            self::STATUS_PROCESSING
        ]);
    }
    
    /**
     * Verifica se o pedido pode ser editado
     * 
     * @return bool
     */
    public function canBeEdited(): bool {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_CONFIRMED
        ]);
    }
    
    /**
     * Verifica se o pedido está finalizado
     * 
     * @return bool
     */
    public function isCompleted(): bool {
        return in_array($this->status, [
            self::STATUS_DELIVERED,
            self::STATUS_CANCELLED,
            self::STATUS_REFUNDED
        ]);
    }
    
    /**
     * Verifica se o pedido está em andamento
     * 
     * @return bool
     */
    public function isInProgress(): bool {
        return in_array($this->status, [
            self::STATUS_CONFIRMED,
            self::STATUS_PROCESSING,
            self::STATUS_SHIPPED
        ]);
    }
    
    /**
     * Verifica se o pagamento foi aprovado
     * 
     * @return bool
     */
    public function isPaymentApproved(): bool {
        return $this->paymentStatus === self::PAYMENT_APPROVED;
    }
    
    /**
     * Verifica se o pagamento está pendente
     * 
     * @return bool
     */
    public function isPaymentPending(): bool {
        return $this->paymentStatus === self::PAYMENT_PENDING;
    }
    
    /**
     * Obtém o endereço de entrega formatado
     * 
     * @return string
     */
    public function getFormattedShippingAddress(): string {
        if (empty($this->shippingAddress)) {
            return '';
        }
        
        $address = $this->shippingAddress;
        return sprintf(
            "%s, %s - %s, %s - %s, %s",
            $address['street'] ?? '',
            $address['number'] ?? '',
            $address['neighborhood'] ?? '',
            $address['city'] ?? '',
            $address['state'] ?? '',
            $address['zipcode'] ?? ''
        );
    }
    
    /**
     * Obtém o endereço de cobrança formatado
     * 
     * @return string
     */
    public function getFormattedBillingAddress(): string {
        if (empty($this->billingAddress)) {
            return $this->getFormattedShippingAddress();
        }
        
        $address = $this->billingAddress;
        return sprintf(
            "%s, %s - %s, %s - %s, %s",
            $address['street'] ?? '',
            $address['number'] ?? '',
            $address['neighborhood'] ?? '',
            $address['city'] ?? '',
            $address['state'] ?? '',
            $address['zipcode'] ?? ''
        );
    }
    
    /**
     * Obtém o status formatado para exibição
     * 
     * @return string
     */
    public function getFormattedStatus(): string {
        $statusMap = [
            self::STATUS_PENDING => 'Pendente',
            self::STATUS_CONFIRMED => 'Confirmado',
            self::STATUS_PROCESSING => 'Processando',
            self::STATUS_SHIPPED => 'Enviado',
            self::STATUS_DELIVERED => 'Entregue',
            self::STATUS_CANCELLED => 'Cancelado',
            self::STATUS_REFUNDED => 'Reembolsado'
        ];
        
        return $statusMap[$this->status] ?? $this->status;
    }
    
    /**
     * Obtém o status do pagamento formatado
     * 
     * @return string
     */
    public function getFormattedPaymentStatus(): string {
        $statusMap = [
            self::PAYMENT_PENDING => 'Pendente',
            self::PAYMENT_APPROVED => 'Aprovado',
            self::PAYMENT_REJECTED => 'Rejeitado',
            self::PAYMENT_REFUNDED => 'Reembolsado'
        ];
        
        return $statusMap[$this->paymentStatus] ?? $this->paymentStatus;
    }
    
    /**
     * Formata valor monetário
     * 
     * @param float $value Valor
     * @return string
     */
    public function formatCurrency(float $value): string {
        return 'R$ ' . number_format($value, 2, ',', '.');
    }
    
    /**
     * Obtém o tempo decorrido desde a criação
     * 
     * @return string
     */
    public function getTimeElapsed(): string {
        $created = new \DateTime($this->createdAt);
        $now = new \DateTime();
        $diff = $now->diff($created);
        
        if ($diff->days > 0) {
            return $diff->days . ' dia(s) atrás';
        } elseif ($diff->h > 0) {
            return $diff->h . ' hora(s) atrás';
        } elseif ($diff->i > 0) {
            return $diff->i . ' minuto(s) atrás';
        } else {
            return 'Agora mesmo';
        }
    }
    
    /**
     * Gera número do pedido
     * 
     * @return string
     */
    private function generateOrderNumber(): string {
        return 'NS' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }
    
    /**
     * Verifica se o status é válido
     * 
     * @param string $status Status
     * @return bool
     */
    private function isValidStatus(string $status): bool {
        return in_array($status, [
            self::STATUS_PENDING,
            self::STATUS_CONFIRMED,
            self::STATUS_PROCESSING,
            self::STATUS_SHIPPED,
            self::STATUS_DELIVERED,
            self::STATUS_CANCELLED,
            self::STATUS_REFUNDED
        ]);
    }
    
    /**
     * Verifica se o status do pagamento é válido
     * 
     * @param string $paymentStatus Status do pagamento
     * @return bool
     */
    private function isValidPaymentStatus(string $paymentStatus): bool {
        return in_array($paymentStatus, [
            self::PAYMENT_PENDING,
            self::PAYMENT_APPROVED,
            self::PAYMENT_REJECTED,
            self::PAYMENT_REFUNDED
        ]);
    }
    
    /**
     * Converte a entidade para array
     * 
     * @return array
     */
    public function toArray(): array {
        return [
            'id' => $this->id,
            'order_number' => $this->orderNumber,
            'customer_id' => $this->customerId,
            'customer_name' => $this->customerName,
            'customer_email' => $this->customerEmail,
            'customer_phone' => $this->customerPhone,
            'shipping_address' => $this->shippingAddress,
            'billing_address' => $this->billingAddress,
            'items' => $this->items,
            'subtotal' => $this->subtotal,
            'shipping_cost' => $this->shippingCost,
            'discount' => $this->discount,
            'total' => $this->total,
            'status' => $this->status,
            'payment_method' => $this->paymentMethod,
            'payment_status' => $this->paymentStatus,
            'coupon_code' => $this->couponCode,
            'tracking_code' => $this->trackingCode,
            'notes' => $this->notes,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'shipped_at' => $this->shippedAt,
            'delivered_at' => $this->deliveredAt,
            
            // Campos calculados
            'total_items' => $this->getTotalItems(),
            'can_be_cancelled' => $this->canBeCancelled(),
            'can_be_edited' => $this->canBeEdited(),
            'is_completed' => $this->isCompleted(),
            'is_in_progress' => $this->isInProgress(),
            'is_payment_approved' => $this->isPaymentApproved(),
            'is_payment_pending' => $this->isPaymentPending(),
            'formatted_shipping_address' => $this->getFormattedShippingAddress(),
            'formatted_billing_address' => $this->getFormattedBillingAddress(),
            'formatted_status' => $this->getFormattedStatus(),
            'formatted_payment_status' => $this->getFormattedPaymentStatus(),
            'formatted_subtotal' => $this->formatCurrency($this->subtotal),
            'formatted_shipping_cost' => $this->formatCurrency($this->shippingCost),
            'formatted_discount' => $this->formatCurrency($this->discount),
            'formatted_total' => $this->formatCurrency($this->total),
            'time_elapsed' => $this->getTimeElapsed()
        ];
    }
    
    /**
     * Converte a entidade para JSON
     * 
     * @return string
     */
    public function toJson(): string {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
    
    /**
     * Cria instância a partir de array
     * 
     * @param array $data Dados do pedido
     * @return self
     */
    public static function fromArray(array $data): self {
        return new self($data);
    }
    
    /**
     * Valida os dados do pedido
     * 
     * @return array Lista de erros (vazio se válido)
     */
    public function validate(): array {
        $errors = [];
        
        if (empty($this->customerName) || strlen(trim($this->customerName)) < 2) {
            $errors[] = 'Nome do cliente é obrigatório';
        }
        
        if (empty($this->customerEmail) || !filter_var($this->customerEmail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email do cliente é obrigatório e deve ser válido';
        }
        
        if (empty($this->items)) {
            $errors[] = 'Pedido deve conter pelo menos um item';
        }
        
        if ($this->total <= 0) {
            $errors[] = 'Total do pedido deve ser maior que zero';
        }
        
        if (!$this->isValidStatus($this->status)) {
            $errors[] = 'Status do pedido inválido';
        }
        
        if (!$this->isValidPaymentStatus($this->paymentStatus)) {
            $errors[] = 'Status do pagamento inválido';
        }
        
        if (empty($this->shippingAddress)) {
            $errors[] = 'Endereço de entrega é obrigatório';
        }
        
        return $errors;
    }
    
    /**
     * Verifica se o pedido é válido
     * 
     * @return bool
     */
    public function isValid(): bool {
        return empty($this->validate());
    }
    
    // ========== MÉTODOS PRIVADOS ==========
    
    /**
     * Atualiza o timestamp de modificação
     */
    private function updateTimestamp(): void {
        $this->updatedAt = date('Y-m-d H:i:s');
    }
}