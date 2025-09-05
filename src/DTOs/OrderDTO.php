<?php

namespace DTOs;

/**
 * DTO para Pedido - NeonShop
 * 
 * Data Transfer Object para transferência de dados de pedidos
 * entre diferentes camadas da aplicação.
 */
class OrderDTO {
    
    public ?int $id;
    public ?string $orderNumber;
    public int $customerId;
    public string $customerName;
    public string $customerEmail;
    public string $customerPhone;
    public array $shippingAddress;
    public array $billingAddress;
    public array $items;
    public float $subtotal;
    public float $shippingCost;
    public float $discount;
    public float $total;
    public string $status;
    public string $paymentMethod;
    public string $paymentStatus;
    public ?string $couponCode;
    public ?string $trackingCode;
    public ?string $notes;
    public ?string $createdAt;
    public ?string $updatedAt;
    public ?string $shippedAt;
    public ?string $deliveredAt;
    
    /**
     * Construtor do OrderDTO
     * 
     * @param array $data Dados do pedido
     */
    public function __construct(array $data = []) {
        $this->id = $data['id'] ?? null;
        $this->orderNumber = $data['order_number'] ?? null;
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
        $this->status = $data['status'] ?? 'pending';
        $this->paymentMethod = $data['payment_method'] ?? '';
        $this->paymentStatus = $data['payment_status'] ?? 'pending';
        $this->couponCode = $data['coupon_code'] ?? null;
        $this->trackingCode = $data['tracking_code'] ?? null;
        $this->notes = $data['notes'] ?? null;
        $this->createdAt = $data['created_at'] ?? null;
        $this->updatedAt = $data['updated_at'] ?? null;
        $this->shippedAt = $data['shipped_at'] ?? null;
        $this->deliveredAt = $data['delivered_at'] ?? null;
    }
    
    /**
     * Cria DTO a partir de array
     * 
     * @param array $data Dados do pedido
     * @return self
     */
    public static function fromArray(array $data): self {
        return new self($data);
    }
    
    /**
     * Converte DTO para array
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
            'delivered_at' => $this->deliveredAt
        ];
    }
    
    /**
     * Converte DTO para JSON
     * 
     * @return string
     */
    public function toJson(): string {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * Valida os dados do DTO
     * 
     * @return array Lista de erros
     */
    public function validate(): array {
        $errors = [];
        
        if (empty(trim($this->customerName))) {
            $errors['customer_name'] = 'Nome do cliente é obrigatório';
        }
        
        if (empty($this->customerEmail) || !filter_var($this->customerEmail, FILTER_VALIDATE_EMAIL)) {
            $errors['customer_email'] = 'Email do cliente é obrigatório e deve ser válido';
        }
        
        if (empty($this->items)) {
            $errors['items'] = 'Pedido deve conter pelo menos um item';
        }
        
        if ($this->total <= 0) {
            $errors['total'] = 'Total do pedido deve ser maior que zero';
        }
        
        if (empty($this->shippingAddress)) {
            $errors['shipping_address'] = 'Endereço de entrega é obrigatório';
        } else {
            $requiredAddressFields = ['street', 'number', 'neighborhood', 'city', 'state', 'zipcode'];
            foreach ($requiredAddressFields as $field) {
                if (empty($this->shippingAddress[$field])) {
                    $errors["shipping_address.{$field}"] = "Campo {$field} do endereço é obrigatório";
                }
            }
        }
        
        if (empty($this->paymentMethod)) {
            $errors['payment_method'] = 'Método de pagamento é obrigatório';
        }
        
        return $errors;
    }
    
    /**
     * Verifica se o DTO é válido
     * 
     * @return bool
     */
    public function isValid(): bool {
        return empty($this->validate());
    }
}

/**
 * DTO para item do pedido
 */
class OrderItemDTO {
    
    public int $productId;
    public string $productName;
    public string $productImage;
    public float $price;
    public int $quantity;
    public float $total;
    public ?array $productVariations;
    
    /**
     * Construtor do OrderItemDTO
     * 
     * @param array $data Dados do item
     */
    public function __construct(array $data = []) {
        $this->productId = (int)($data['product_id'] ?? 0);
        $this->productName = $data['product_name'] ?? '';
        $this->productImage = $data['product_image'] ?? '';
        $this->price = (float)($data['price'] ?? 0);
        $this->quantity = (int)($data['quantity'] ?? 1);
        $this->total = (float)($data['total'] ?? ($this->price * $this->quantity));
        $this->productVariations = $data['product_variations'] ?? null;
    }
    
    /**
     * Cria DTO a partir de array
     * 
     * @param array $data Dados do item
     * @return self
     */
    public static function fromArray(array $data): self {
        return new self($data);
    }
    
    /**
     * Converte DTO para array
     * 
     * @return array
     */
    public function toArray(): array {
        return [
            'product_id' => $this->productId,
            'product_name' => $this->productName,
            'product_image' => $this->productImage,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'total' => $this->total,
            'product_variations' => $this->productVariations
        ];
    }
    
    /**
     * Valida os dados do item
     * 
     * @return array Lista de erros
     */
    public function validate(): array {
        $errors = [];
        
        if ($this->productId <= 0) {
            $errors['product_id'] = 'ID do produto é obrigatório';
        }
        
        if (empty(trim($this->productName))) {
            $errors['product_name'] = 'Nome do produto é obrigatório';
        }
        
        if ($this->price <= 0) {
            $errors['price'] = 'Preço deve ser maior que zero';
        }
        
        if ($this->quantity <= 0) {
            $errors['quantity'] = 'Quantidade deve ser maior que zero';
        }
        
        if ($this->total <= 0) {
            $errors['total'] = 'Total deve ser maior que zero';
        }
        
        return $errors;
    }
    
    /**
     * Verifica se o item é válido
     * 
     * @return bool
     */
    public function isValid(): bool {
        return empty($this->validate());
    }
}

/**
 * DTO para filtros de busca de pedidos
 */
class OrderFilterDTO {
    
    public ?string $search;
    public ?string $status;
    public ?string $paymentStatus;
    public ?int $customerId;
    public ?string $dateFrom;
    public ?string $dateTo;
    public ?float $minTotal;
    public ?float $maxTotal;
    public string $sortBy;
    public string $sortOrder;
    public int $page;
    public int $limit;
    
    /**
     * Construtor do OrderFilterDTO
     * 
     * @param array $data Dados dos filtros
     */
    public function __construct(array $data = []) {
        $this->search = $data['search'] ?? null;
        $this->status = $data['status'] ?? null;
        $this->paymentStatus = $data['payment_status'] ?? null;
        $this->customerId = isset($data['customer_id']) ? (int)$data['customer_id'] : null;
        $this->dateFrom = $data['date_from'] ?? null;
        $this->dateTo = $data['date_to'] ?? null;
        $this->minTotal = isset($data['min_total']) ? (float)$data['min_total'] : null;
        $this->maxTotal = isset($data['max_total']) ? (float)$data['max_total'] : null;
        $this->sortBy = $data['sort_by'] ?? 'created_at';
        $this->sortOrder = $data['sort_order'] ?? 'desc';
        $this->page = (int)($data['page'] ?? 1);
        $this->limit = (int)($data['limit'] ?? 20);
    }
    
    /**
     * Cria DTO a partir de array
     * 
     * @param array $data Dados dos filtros
     * @return self
     */
    public static function fromArray(array $data): self {
        return new self($data);
    }
    
    /**
     * Converte DTO para array
     * 
     * @return array
     */
    public function toArray(): array {
        return [
            'search' => $this->search,
            'status' => $this->status,
            'payment_status' => $this->paymentStatus,
            'customer_id' => $this->customerId,
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo,
            'min_total' => $this->minTotal,
            'max_total' => $this->maxTotal,
            'sort_by' => $this->sortBy,
            'sort_order' => $this->sortOrder,
            'page' => $this->page,
            'limit' => $this->limit
        ];
    }
    
    /**
     * Obtém o offset para paginação
     * 
     * @return int
     */
    public function getOffset(): int {
        return ($this->page - 1) * $this->limit;
    }
    
    /**
     * Valida os dados do filtro
     * 
     * @return array Lista de erros
     */
    public function validate(): array {
        $errors = [];
        
        if ($this->page < 1) {
            $errors['page'] = 'Página deve ser maior que zero';
        }
        
        if ($this->limit < 1 || $this->limit > 100) {
            $errors['limit'] = 'Limite deve estar entre 1 e 100';
        }
        
        if ($this->minTotal !== null && $this->minTotal < 0) {
            $errors['min_total'] = 'Total mínimo não pode ser negativo';
        }
        
        if ($this->maxTotal !== null && $this->maxTotal < 0) {
            $errors['max_total'] = 'Total máximo não pode ser negativo';
        }
        
        if ($this->minTotal !== null && $this->maxTotal !== null && $this->minTotal > $this->maxTotal) {
            $errors['total_range'] = 'Total mínimo não pode ser maior que o máximo';
        }
        
        if ($this->dateFrom && !$this->isValidDate($this->dateFrom)) {
            $errors['date_from'] = 'Data inicial inválida';
        }
        
        if ($this->dateTo && !$this->isValidDate($this->dateTo)) {
            $errors['date_to'] = 'Data final inválida';
        }
        
        if ($this->dateFrom && $this->dateTo && $this->dateFrom > $this->dateTo) {
            $errors['date_range'] = 'Data inicial não pode ser maior que a final';
        }
        
        $validSortFields = ['created_at', 'total', 'status', 'customer_name', 'order_number'];
        if (!in_array($this->sortBy, $validSortFields)) {
            $errors['sort_by'] = 'Campo de ordenação inválido';
        }
        
        if (!in_array($this->sortOrder, ['asc', 'desc'])) {
            $errors['sort_order'] = 'Ordem de classificação deve ser asc ou desc';
        }
        
        return $errors;
    }
    
    /**
     * Verifica se o filtro é válido
     * 
     * @return bool
     */
    public function isValid(): bool {
        return empty($this->validate());
    }
    
    /**
     * Valida data
     * 
     * @param string $date Data
     * @return bool
     */
    private function isValidDate(string $date): bool {
        $d = \DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
}

/**
 * DTO para resposta paginada de pedidos
 */
class OrderListResponseDTO {
    
    public array $orders;
    public int $total;
    public int $page;
    public int $limit;
    public int $totalPages;
    public bool $hasNext;
    public bool $hasPrevious;
    public array $filters;
    public array $summary;
    
    /**
     * Construtor do OrderListResponseDTO
     * 
     * @param array $orders Lista de pedidos
     * @param int $total Total de pedidos
     * @param OrderFilterDTO $filters Filtros aplicados
     * @param array $summary Resumo dos pedidos
     */
    public function __construct(array $orders, int $total, OrderFilterDTO $filters, array $summary = []) {
        $this->orders = $orders;
        $this->total = $total;
        $this->page = $filters->page;
        $this->limit = $filters->limit;
        $this->totalPages = (int)ceil($total / $filters->limit);
        $this->hasNext = $this->page < $this->totalPages;
        $this->hasPrevious = $this->page > 1;
        $this->filters = $filters->toArray();
        $this->summary = $summary;
    }
    
    /**
     * Converte DTO para array
     * 
     * @return array
     */
    public function toArray(): array {
        return [
            'orders' => $this->orders,
            'pagination' => [
                'total' => $this->total,
                'page' => $this->page,
                'limit' => $this->limit,
                'total_pages' => $this->totalPages,
                'has_next' => $this->hasNext,
                'has_previous' => $this->hasPrevious
            ],
            'filters' => $this->filters,
            'summary' => $this->summary
        ];
    }
    
    /**
     * Converte DTO para JSON
     * 
     * @return string
     */
    public function toJson(): string {
        return json_encode($this->toArray(), JSON_UNESCAPED_UNICODE);
    }
}

/**
 * DTO para criação de pedido
 */
class CreateOrderDTO {
    
    public int $customerId;
    public string $customerName;
    public string $customerEmail;
    public string $customerPhone;
    public array $shippingAddress;
    public ?array $billingAddress;
    public array $items;
    public string $paymentMethod;
    public ?string $couponCode;
    public ?string $notes;
    
    /**
     * Construtor do CreateOrderDTO
     * 
     * @param array $data Dados para criação do pedido
     */
    public function __construct(array $data = []) {
        $this->customerId = (int)($data['customer_id'] ?? 0);
        $this->customerName = $data['customer_name'] ?? '';
        $this->customerEmail = $data['customer_email'] ?? '';
        $this->customerPhone = $data['customer_phone'] ?? '';
        $this->shippingAddress = $data['shipping_address'] ?? [];
        $this->billingAddress = $data['billing_address'] ?? null;
        $this->items = $data['items'] ?? [];
        $this->paymentMethod = $data['payment_method'] ?? '';
        $this->couponCode = $data['coupon_code'] ?? null;
        $this->notes = $data['notes'] ?? null;
    }
    
    /**
     * Cria DTO a partir de array
     * 
     * @param array $data Dados para criação
     * @return self
     */
    public static function fromArray(array $data): self {
        return new self($data);
    }
    
    /**
     * Converte DTO para array
     * 
     * @return array
     */
    public function toArray(): array {
        return [
            'customer_id' => $this->customerId,
            'customer_name' => $this->customerName,
            'customer_email' => $this->customerEmail,
            'customer_phone' => $this->customerPhone,
            'shipping_address' => $this->shippingAddress,
            'billing_address' => $this->billingAddress,
            'items' => $this->items,
            'payment_method' => $this->paymentMethod,
            'coupon_code' => $this->couponCode,
            'notes' => $this->notes
        ];
    }
    
    /**
     * Valida os dados para criação
     * 
     * @return array Lista de erros
     */
    public function validate(): array {
        $errors = [];
        
        if (empty(trim($this->customerName))) {
            $errors['customer_name'] = 'Nome do cliente é obrigatório';
        }
        
        if (empty($this->customerEmail) || !filter_var($this->customerEmail, FILTER_VALIDATE_EMAIL)) {
            $errors['customer_email'] = 'Email do cliente é obrigatório e deve ser válido';
        }
        
        if (empty($this->items)) {
            $errors['items'] = 'Pedido deve conter pelo menos um item';
        } else {
            foreach ($this->items as $index => $item) {
                $itemDTO = new OrderItemDTO($item);
                $itemErrors = $itemDTO->validate();
                if (!empty($itemErrors)) {
                    $errors["items.{$index}"] = $itemErrors;
                }
            }
        }
        
        if (empty($this->shippingAddress)) {
            $errors['shipping_address'] = 'Endereço de entrega é obrigatório';
        } else {
            $requiredFields = ['street', 'number', 'neighborhood', 'city', 'state', 'zipcode'];
            foreach ($requiredFields as $field) {
                if (empty($this->shippingAddress[$field])) {
                    $errors["shipping_address.{$field}"] = "Campo {$field} é obrigatório";
                }
            }
        }
        
        if (empty($this->paymentMethod)) {
            $errors['payment_method'] = 'Método de pagamento é obrigatório';
        }
        
        return $errors;
    }
    
    /**
     * Verifica se os dados são válidos
     * 
     * @return bool
     */
    public function isValid(): bool {
        return empty($this->validate());
    }
}