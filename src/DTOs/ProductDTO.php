<?php

namespace DTOs;

/**
 * DTO para Produto - NeonShop
 * 
 * Data Transfer Object para transferência de dados de produtos
 * entre diferentes camadas da aplicação.
 */
class ProductDTO {
    
    public ?int $id;
    public string $name;
    public string $description;
    public float $price;
    public ?float $salePrice;
    public string $category;
    public string $imageUrl;
    public array $gallery;
    public int $stockQuantity;
    public float $weight;
    public ?string $dimensions;
    public array $tags;
    public bool $featured;
    public bool $active;
    public int $salesCount;
    public int $viewsCount;
    public ?string $createdAt;
    public ?string $updatedAt;
    
    /**
     * Construtor do ProductDTO
     * 
     * @param array $data Dados do produto
     */
    public function __construct(array $data = []) {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->price = (float)($data['price'] ?? 0);
        $this->salePrice = isset($data['sale_price']) ? (float)$data['sale_price'] : null;
        $this->category = $data['category'] ?? '';
        $this->imageUrl = $data['image_url'] ?? '';
        $this->gallery = $data['gallery'] ?? [];
        $this->stockQuantity = (int)($data['stock_quantity'] ?? 0);
        $this->weight = (float)($data['weight'] ?? 0);
        $this->dimensions = $data['dimensions'] ?? null;
        $this->tags = $data['tags'] ?? [];
        $this->featured = (bool)($data['featured'] ?? false);
        $this->active = (bool)($data['active'] ?? true);
        $this->salesCount = (int)($data['sales_count'] ?? 0);
        $this->viewsCount = (int)($data['views_count'] ?? 0);
        $this->createdAt = $data['created_at'] ?? null;
        $this->updatedAt = $data['updated_at'] ?? null;
    }
    
    /**
     * Cria DTO a partir de array
     * 
     * @param array $data Dados do produto
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
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'sale_price' => $this->salePrice,
            'category' => $this->category,
            'image_url' => $this->imageUrl,
            'gallery' => $this->gallery,
            'stock_quantity' => $this->stockQuantity,
            'weight' => $this->weight,
            'dimensions' => $this->dimensions,
            'tags' => $this->tags,
            'featured' => $this->featured,
            'active' => $this->active,
            'sales_count' => $this->salesCount,
            'views_count' => $this->viewsCount,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt
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
        
        if (empty(trim($this->name))) {
            $errors['name'] = 'Nome do produto é obrigatório';
        }
        
        if ($this->price <= 0) {
            $errors['price'] = 'Preço deve ser maior que zero';
        }
        
        if (empty(trim($this->category))) {
            $errors['category'] = 'Categoria é obrigatória';
        }
        
        if ($this->stockQuantity < 0) {
            $errors['stock_quantity'] = 'Quantidade em estoque não pode ser negativa';
        }
        
        if ($this->salePrice !== null && $this->salePrice < 0) {
            $errors['sale_price'] = 'Preço promocional não pode ser negativo';
        }
        
        if ($this->salePrice !== null && $this->salePrice >= $this->price) {
            $errors['sale_price'] = 'Preço promocional deve ser menor que o preço normal';
        }
        
        if ($this->weight < 0) {
            $errors['weight'] = 'Peso não pode ser negativo';
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
 * DTO para filtros de busca de produtos
 */
class ProductFilterDTO {
    
    public ?string $search;
    public ?string $category;
    public ?float $minPrice;
    public ?float $maxPrice;
    public ?bool $featured;
    public ?bool $onSale;
    public ?bool $inStock;
    public array $tags;
    public string $sortBy;
    public string $sortOrder;
    public int $page;
    public int $limit;
    
    /**
     * Construtor do ProductFilterDTO
     * 
     * @param array $data Dados dos filtros
     */
    public function __construct(array $data = []) {
        $this->search = $data['search'] ?? null;
        $this->category = $data['category'] ?? null;
        $this->minPrice = isset($data['min_price']) ? (float)$data['min_price'] : null;
        $this->maxPrice = isset($data['max_price']) ? (float)$data['max_price'] : null;
        $this->featured = isset($data['featured']) ? (bool)$data['featured'] : null;
        $this->onSale = isset($data['on_sale']) ? (bool)$data['on_sale'] : null;
        $this->inStock = isset($data['in_stock']) ? (bool)$data['in_stock'] : null;
        $this->tags = $data['tags'] ?? [];
        $this->sortBy = $data['sort_by'] ?? 'created_at';
        $this->sortOrder = $data['sort_order'] ?? 'desc';
        $this->page = (int)($data['page'] ?? 1);
        $this->limit = (int)($data['limit'] ?? 12);
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
            'category' => $this->category,
            'min_price' => $this->minPrice,
            'max_price' => $this->maxPrice,
            'featured' => $this->featured,
            'on_sale' => $this->onSale,
            'in_stock' => $this->inStock,
            'tags' => $this->tags,
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
        
        if ($this->minPrice !== null && $this->minPrice < 0) {
            $errors['min_price'] = 'Preço mínimo não pode ser negativo';
        }
        
        if ($this->maxPrice !== null && $this->maxPrice < 0) {
            $errors['max_price'] = 'Preço máximo não pode ser negativo';
        }
        
        if ($this->minPrice !== null && $this->maxPrice !== null && $this->minPrice > $this->maxPrice) {
            $errors['price_range'] = 'Preço mínimo não pode ser maior que o máximo';
        }
        
        $validSortFields = ['name', 'price', 'created_at', 'sales_count', 'views_count'];
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
}

/**
 * DTO para resposta paginada de produtos
 */
class ProductListResponseDTO {
    
    public array $products;
    public int $total;
    public int $page;
    public int $limit;
    public int $totalPages;
    public bool $hasNext;
    public bool $hasPrevious;
    public array $filters;
    
    /**
     * Construtor do ProductListResponseDTO
     * 
     * @param array $products Lista de produtos
     * @param int $total Total de produtos
     * @param ProductFilterDTO $filters Filtros aplicados
     */
    public function __construct(array $products, int $total, ProductFilterDTO $filters) {
        $this->products = $products;
        $this->total = $total;
        $this->page = $filters->page;
        $this->limit = $filters->limit;
        $this->totalPages = (int)ceil($total / $filters->limit);
        $this->hasNext = $this->page < $this->totalPages;
        $this->hasPrevious = $this->page > 1;
        $this->filters = $filters->toArray();
    }
    
    /**
     * Converte DTO para array
     * 
     * @return array
     */
    public function toArray(): array {
        return [
            'products' => $this->products,
            'pagination' => [
                'total' => $this->total,
                'page' => $this->page,
                'limit' => $this->limit,
                'total_pages' => $this->totalPages,
                'has_next' => $this->hasNext,
                'has_previous' => $this->hasPrevious
            ],
            'filters' => $this->filters
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