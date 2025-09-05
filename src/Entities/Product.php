<?php

namespace Entities;

/**
 * Entidade Produto - NeonShop
 * 
 * Representa um produto no sistema de e-commerce.
 * Contém todas as propriedades e métodos relacionados ao produto.
 */
class Product {
    
    private int $id;
    private string $name;
    private string $description;
    private float $price;
    private ?float $salePrice;
    private string $category;
    private string $imageUrl;
    private array $gallery;
    private int $stockQuantity;
    private float $weight;
    private ?string $dimensions;
    private array $tags;
    private bool $featured;
    private bool $active;
    private int $salesCount;
    private int $viewsCount;
    private string $createdAt;
    private string $updatedAt;
    
    /**
     * Construtor da entidade Product
     * 
     * @param array $data Dados do produto
     */
    public function __construct(array $data = []) {
        $this->id = $data['id'] ?? 0;
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
        $this->createdAt = $data['created_at'] ?? date('Y-m-d H:i:s');
        $this->updatedAt = $data['updated_at'] ?? date('Y-m-d H:i:s');
    }
    
    // ========== GETTERS ==========
    
    public function getId(): int {
        return $this->id;
    }
    
    public function getName(): string {
        return $this->name;
    }
    
    public function getDescription(): string {
        return $this->description;
    }
    
    public function getPrice(): float {
        return $this->price;
    }
    
    public function getSalePrice(): ?float {
        return $this->salePrice;
    }
    
    public function getCategory(): string {
        return $this->category;
    }
    
    public function getImageUrl(): string {
        return $this->imageUrl;
    }
    
    public function getGallery(): array {
        return $this->gallery;
    }
    
    public function getStockQuantity(): int {
        return $this->stockQuantity;
    }
    
    public function getWeight(): float {
        return $this->weight;
    }
    
    public function getDimensions(): ?string {
        return $this->dimensions;
    }
    
    public function getTags(): array {
        return $this->tags;
    }
    
    public function isFeatured(): bool {
        return $this->featured;
    }
    
    public function isActive(): bool {
        return $this->active;
    }
    
    public function getSalesCount(): int {
        return $this->salesCount;
    }
    
    public function getViewsCount(): int {
        return $this->viewsCount;
    }
    
    public function getCreatedAt(): string {
        return $this->createdAt;
    }
    
    public function getUpdatedAt(): string {
        return $this->updatedAt;
    }
    
    // ========== SETTERS ==========
    
    public function setId(int $id): void {
        $this->id = $id;
    }
    
    public function setName(string $name): void {
        $this->name = $name;
        $this->updateTimestamp();
    }
    
    public function setDescription(string $description): void {
        $this->description = $description;
        $this->updateTimestamp();
    }
    
    public function setPrice(float $price): void {
        $this->price = $price;
        $this->updateTimestamp();
    }
    
    public function setSalePrice(?float $salePrice): void {
        $this->salePrice = $salePrice;
        $this->updateTimestamp();
    }
    
    public function setCategory(string $category): void {
        $this->category = $category;
        $this->updateTimestamp();
    }
    
    public function setImageUrl(string $imageUrl): void {
        $this->imageUrl = $imageUrl;
        $this->updateTimestamp();
    }
    
    public function setGallery(array $gallery): void {
        $this->gallery = $gallery;
        $this->updateTimestamp();
    }
    
    public function setStockQuantity(int $stockQuantity): void {
        $this->stockQuantity = $stockQuantity;
        $this->updateTimestamp();
    }
    
    public function setWeight(float $weight): void {
        $this->weight = $weight;
        $this->updateTimestamp();
    }
    
    public function setDimensions(?string $dimensions): void {
        $this->dimensions = $dimensions;
        $this->updateTimestamp();
    }
    
    public function setTags(array $tags): void {
        $this->tags = $tags;
        $this->updateTimestamp();
    }
    
    public function setFeatured(bool $featured): void {
        $this->featured = $featured;
        $this->updateTimestamp();
    }
    
    public function setActive(bool $active): void {
        $this->active = $active;
        $this->updateTimestamp();
    }
    
    public function setSalesCount(int $salesCount): void {
        $this->salesCount = $salesCount;
        $this->updateTimestamp();
    }
    
    public function setViewsCount(int $viewsCount): void {
        $this->viewsCount = $viewsCount;
        $this->updateTimestamp();
    }
    
    // ========== MÉTODOS DE NEGÓCIO ==========
    
    /**
     * Verifica se o produto está em promoção
     * 
     * @return bool
     */
    public function isOnSale(): bool {
        return $this->salePrice !== null && 
               $this->salePrice > 0 && 
               $this->salePrice < $this->price;
    }
    
    /**
     * Obtém o preço efetivo (promocional ou normal)
     * 
     * @return float
     */
    public function getEffectivePrice(): float {
        return $this->isOnSale() ? $this->salePrice : $this->price;
    }
    
    /**
     * Calcula o percentual de desconto
     * 
     * @return float
     */
    public function getDiscountPercentage(): float {
        if (!$this->isOnSale()) {
            return 0;
        }
        
        return (($this->price - $this->salePrice) / $this->price) * 100;
    }
    
    /**
     * Calcula o valor do desconto
     * 
     * @return float
     */
    public function getDiscountAmount(): float {
        if (!$this->isOnSale()) {
            return 0;
        }
        
        return $this->price - $this->salePrice;
    }
    
    /**
     * Verifica se o produto está disponível
     * 
     * @return bool
     */
    public function isAvailable(): bool {
        return $this->active && $this->stockQuantity > 0;
    }
    
    /**
     * Verifica se o produto está com estoque baixo
     * 
     * @param int $threshold Limite considerado baixo (padrão: 5)
     * @return bool
     */
    public function isLowStock(int $threshold = 5): bool {
        return $this->stockQuantity > 0 && $this->stockQuantity <= $threshold;
    }
    
    /**
     * Verifica se o produto está fora de estoque
     * 
     * @return bool
     */
    public function isOutOfStock(): bool {
        return $this->stockQuantity <= 0;
    }
    
    /**
     * Incrementa o contador de visualizações
     */
    public function incrementViews(): void {
        $this->viewsCount++;
        $this->updateTimestamp();
    }
    
    /**
     * Incrementa o contador de vendas
     * 
     * @param int $quantity Quantidade vendida
     */
    public function incrementSales(int $quantity = 1): void {
        $this->salesCount += $quantity;
        $this->updateTimestamp();
    }
    
    /**
     * Decrementa o estoque
     * 
     * @param int $quantity Quantidade a decrementar
     * @return bool Sucesso da operação
     */
    public function decrementStock(int $quantity): bool {
        if ($this->stockQuantity < $quantity) {
            return false;
        }
        
        $this->stockQuantity -= $quantity;
        $this->updateTimestamp();
        
        return true;
    }
    
    /**
     * Incrementa o estoque
     * 
     * @param int $quantity Quantidade a incrementar
     */
    public function incrementStock(int $quantity): void {
        $this->stockQuantity += $quantity;
        $this->updateTimestamp();
    }
    
    /**
     * Adiciona uma tag ao produto
     * 
     * @param string $tag Tag a ser adicionada
     */
    public function addTag(string $tag): void {
        $tag = trim($tag);
        if (!empty($tag) && !in_array($tag, $this->tags)) {
            $this->tags[] = $tag;
            $this->updateTimestamp();
        }
    }
    
    /**
     * Remove uma tag do produto
     * 
     * @param string $tag Tag a ser removida
     */
    public function removeTag(string $tag): void {
        $index = array_search($tag, $this->tags);
        if ($index !== false) {
            unset($this->tags[$index]);
            $this->tags = array_values($this->tags); // Reindexar array
            $this->updateTimestamp();
        }
    }
    
    /**
     * Adiciona uma imagem à galeria
     * 
     * @param string $imageUrl URL da imagem
     */
    public function addGalleryImage(string $imageUrl): void {
        if (!empty($imageUrl) && !in_array($imageUrl, $this->gallery)) {
            $this->gallery[] = $imageUrl;
            $this->updateTimestamp();
        }
    }
    
    /**
     * Remove uma imagem da galeria
     * 
     * @param string $imageUrl URL da imagem
     */
    public function removeGalleryImage(string $imageUrl): void {
        $index = array_search($imageUrl, $this->gallery);
        if ($index !== false) {
            unset($this->gallery[$index]);
            $this->gallery = array_values($this->gallery); // Reindexar array
            $this->updateTimestamp();
        }
    }
    
    /**
     * Gera slug do produto
     * 
     * @return string
     */
    public function getSlug(): string {
        $slug = strtolower($this->name);
        $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
        $slug = preg_replace('/[\s-]+/', '-', $slug);
        return trim($slug, '-') . '-' . $this->id;
    }
    
    /**
     * Formata o preço para exibição
     * 
     * @param float|null $price Preço (se null, usa o preço efetivo)
     * @return string
     */
    public function formatPrice(?float $price = null): string {
        $price = $price ?? $this->getEffectivePrice();
        return 'R$ ' . number_format($price, 2, ',', '.');
    }
    
    /**
     * Converte a entidade para array
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
            'updated_at' => $this->updatedAt,
            
            // Campos calculados
            'is_on_sale' => $this->isOnSale(),
            'effective_price' => $this->getEffectivePrice(),
            'discount_percentage' => $this->getDiscountPercentage(),
            'discount_amount' => $this->getDiscountAmount(),
            'is_available' => $this->isAvailable(),
            'is_low_stock' => $this->isLowStock(),
            'is_out_of_stock' => $this->isOutOfStock(),
            'slug' => $this->getSlug(),
            'formatted_price' => $this->formatPrice($this->price),
            'formatted_sale_price' => $this->salePrice ? $this->formatPrice($this->salePrice) : null,
            'formatted_effective_price' => $this->formatPrice()
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
     * @param array $data Dados do produto
     * @return self
     */
    public static function fromArray(array $data): self {
        return new self($data);
    }
    
    /**
     * Valida os dados do produto
     * 
     * @return array Lista de erros (vazio se válido)
     */
    public function validate(): array {
        $errors = [];
        
        if (empty($this->name) || strlen(trim($this->name)) < 2) {
            $errors[] = 'Nome do produto é obrigatório e deve ter pelo menos 2 caracteres';
        }
        
        if ($this->price <= 0) {
            $errors[] = 'Preço deve ser maior que zero';
        }
        
        if (empty($this->category)) {
            $errors[] = 'Categoria é obrigatória';
        }
        
        if ($this->stockQuantity < 0) {
            $errors[] = 'Quantidade em estoque não pode ser negativa';
        }
        
        if ($this->salePrice !== null && $this->salePrice < 0) {
            $errors[] = 'Preço promocional não pode ser negativo';
        }
        
        if ($this->salePrice !== null && $this->salePrice >= $this->price) {
            $errors[] = 'Preço promocional deve ser menor que o preço normal';
        }
        
        if ($this->weight < 0) {
            $errors[] = 'Peso não pode ser negativo';
        }
        
        return $errors;
    }
    
    /**
     * Verifica se o produto é válido
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