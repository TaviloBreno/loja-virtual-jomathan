<?php

namespace Services;

use Repositories\ProductRepository;
use Entities\Product;
use DTOs\ProductDTO;

/**
 * Serviço de Produtos - NeonShop
 * 
 * Responsável pela lógica de negócio relacionada a produtos:
 * - Validação de dados
 * - Regras de negócio
 * - Processamento de informações
 * - Integração com repositórios
 */
class ProductService {
    
    private ProductRepository $productRepository;
    
    public function __construct(ProductRepository $productRepository) {
        $this->productRepository = $productRepository;
    }
    
    /**
     * Obtém todos os produtos com filtros
     * 
     * @param array $filters Filtros de busca
     * @param int $page Página atual
     * @param int $limit Itens por página
     * @return array
     */
    public function getProducts(array $filters = [], int $page = 1, int $limit = 12): array {
        // Validar filtros
        $validatedFilters = $this->validateFilters($filters);
        
        // Buscar produtos no repositório
        $products = $this->productRepository->findWithFilters($validatedFilters, $page, $limit);
        
        // Processar dados dos produtos
        $processedProducts = array_map(function($product) {
            return $this->processProductData($product);
        }, $products['items']);
        
        return [
            'items' => $processedProducts,
            'pagination' => $products['pagination'],
            'filters_applied' => $validatedFilters
        ];
    }
    
    /**
     * Obtém produto por ID
     * 
     * @param int $id ID do produto
     * @return ProductDTO|null
     */
    public function getProductById(int $id): ?ProductDTO {
        if ($id <= 0) {
            return null;
        }
        
        $product = $this->productRepository->findById($id);
        
        if (!$product) {
            return null;
        }
        
        // Processar dados do produto
        $processedProduct = $this->processProductData($product);
        
        // Adicionar informações extras
        $processedProduct['related_products'] = $this->getRelatedProducts($product['category'], $id, 4);
        $processedProduct['reviews'] = $this->getProductReviews($id);
        $processedProduct['availability'] = $this->checkProductAvailability($product);
        
        return new ProductDTO($processedProduct);
    }
    
    /**
     * Busca produtos por termo
     * 
     * @param string $query Termo de busca
     * @param int $limit Limite de resultados
     * @return array
     */
    public function searchProducts(string $query, int $limit = 10): array {
        if (strlen(trim($query)) < 2) {
            return [];
        }
        
        $cleanQuery = $this->sanitizeSearchQuery($query);
        $products = $this->productRepository->search($cleanQuery, $limit);
        
        return array_map(function($product) {
            return $this->processProductData($product);
        }, $products);
    }
    
    /**
     * Obtém produtos em destaque
     * 
     * @param int $limit Limite de produtos
     * @return array
     */
    public function getFeaturedProducts(int $limit = 8): array {
        $products = $this->productRepository->findFeatured($limit);
        
        return array_map(function($product) {
            return $this->processProductData($product);
        }, $products);
    }
    
    /**
     * Obtém produtos mais vendidos
     * 
     * @param int $limit Limite de produtos
     * @return array
     */
    public function getBestSellers(int $limit = 8): array {
        $products = $this->productRepository->findBestSellers($limit);
        
        return array_map(function($product) {
            return $this->processProductData($product);
        }, $products);
    }
    
    /**
     * Obtém produtos em promoção
     * 
     * @param int $limit Limite de produtos
     * @return array
     */
    public function getPromotionalProducts(int $limit = 8): array {
        $products = $this->productRepository->findPromotional($limit);
        
        return array_map(function($product) {
            return $this->processProductData($product);
        }, $products);
    }
    
    /**
     * Obtém produtos por categoria
     * 
     * @param string $category Categoria
     * @param int $page Página
     * @param int $limit Limite
     * @return array
     */
    public function getProductsByCategory(string $category, int $page = 1, int $limit = 12): array {
        if (empty($category)) {
            return ['items' => [], 'pagination' => []];
        }
        
        $products = $this->productRepository->findByCategory($category, $page, $limit);
        
        $processedProducts = array_map(function($product) {
            return $this->processProductData($product);
        }, $products['items']);
        
        return [
            'items' => $processedProducts,
            'pagination' => $products['pagination']
        ];
    }
    
    /**
     * Cria novo produto
     * 
     * @param array $data Dados do produto
     * @return ProductDTO
     * @throws \InvalidArgumentException
     */
    public function createProduct(array $data): ProductDTO {
        // Validar dados
        $validatedData = $this->validateProductData($data);
        
        // Processar dados antes de salvar
        $processedData = $this->preprocessProductData($validatedData);
        
        // Salvar no repositório
        $product = $this->productRepository->create($processedData);
        
        return new ProductDTO($this->processProductData($product));
    }
    
    /**
     * Atualiza produto existente
     * 
     * @param int $id ID do produto
     * @param array $data Dados do produto
     * @return ProductDTO|null
     * @throws \InvalidArgumentException
     */
    public function updateProduct(int $id, array $data): ?ProductDTO {
        if ($id <= 0) {
            return null;
        }
        
        // Verificar se produto existe
        $existingProduct = $this->productRepository->findById($id);
        if (!$existingProduct) {
            return null;
        }
        
        // Validar dados
        $validatedData = $this->validateProductData($data, false);
        
        // Processar dados antes de salvar
        $processedData = $this->preprocessProductData($validatedData);
        
        // Atualizar no repositório
        $product = $this->productRepository->update($id, $processedData);
        
        return new ProductDTO($this->processProductData($product));
    }
    
    /**
     * Remove produto
     * 
     * @param int $id ID do produto
     * @return bool
     */
    public function deleteProduct(int $id): bool {
        if ($id <= 0) {
            return false;
        }
        
        // Verificar se produto pode ser removido
        if (!$this->canDeleteProduct($id)) {
            return false;
        }
        
        return $this->productRepository->delete($id);
    }
    
    /**
     * Verifica disponibilidade do produto
     * 
     * @param int $productId ID do produto
     * @param int $quantity Quantidade desejada
     * @return array
     */
    public function checkAvailability(int $productId, int $quantity = 1): array {
        $product = $this->productRepository->findById($productId);
        
        if (!$product) {
            return [
                'available' => false,
                'reason' => 'Produto não encontrado'
            ];
        }
        
        return $this->checkProductAvailability($product, $quantity);
    }
    
    /**
     * Obtém estatísticas de produtos
     * 
     * @return array
     */
    public function getProductStats(): array {
        return [
            'total_products' => $this->productRepository->count(),
            'active_products' => $this->productRepository->countByStatus('active'),
            'featured_products' => $this->productRepository->countFeatured(),
            'out_of_stock' => $this->productRepository->countOutOfStock(),
            'low_stock' => $this->productRepository->countLowStock(10),
            'categories_count' => $this->productRepository->countCategories()
        ];
    }
    
    // ========== MÉTODOS PRIVADOS ==========
    
    /**
     * Valida filtros de busca
     * 
     * @param array $filters Filtros
     * @return array
     */
    private function validateFilters(array $filters): array {
        $validatedFilters = [];
        
        // Validar categoria
        if (isset($filters['category']) && !empty($filters['category'])) {
            $validatedFilters['category'] = $this->sanitizeString($filters['category']);
        }
        
        // Validar faixa de preço
        if (isset($filters['min_price']) && is_numeric($filters['min_price'])) {
            $validatedFilters['min_price'] = max(0, (float)$filters['min_price']);
        }
        
        if (isset($filters['max_price']) && is_numeric($filters['max_price'])) {
            $validatedFilters['max_price'] = max(0, (float)$filters['max_price']);
        }
        
        // Validar ordenação
        $validSortOptions = ['name', 'price', 'created_at', 'popularity'];
        if (isset($filters['sort']) && in_array($filters['sort'], $validSortOptions)) {
            $validatedFilters['sort'] = $filters['sort'];
        }
        
        // Validar direção da ordenação
        $validDirections = ['asc', 'desc'];
        if (isset($filters['direction']) && in_array($filters['direction'], $validDirections)) {
            $validatedFilters['direction'] = $filters['direction'];
        }
        
        // Validar disponibilidade
        if (isset($filters['in_stock'])) {
            $validatedFilters['in_stock'] = (bool)$filters['in_stock'];
        }
        
        return $validatedFilters;
    }
    
    /**
     * Processa dados do produto
     * 
     * @param array $product Dados do produto
     * @return array
     */
    private function processProductData(array $product): array {
        // Calcular desconto
        if (isset($product['original_price']) && $product['original_price'] > $product['price']) {
            $product['discount_percentage'] = round(
                (($product['original_price'] - $product['price']) / $product['original_price']) * 100
            );
            $product['has_discount'] = true;
        } else {
            $product['discount_percentage'] = 0;
            $product['has_discount'] = false;
        }
        
        // Formatar preços
        $product['formatted_price'] = $this->formatPrice($product['price']);
        if (isset($product['original_price'])) {
            $product['formatted_original_price'] = $this->formatPrice($product['original_price']);
        }
        
        // Processar imagens
        if (isset($product['images']) && is_string($product['images'])) {
            $product['images'] = json_decode($product['images'], true) ?: [];
        }
        
        // Garantir que sempre há uma imagem
        if (empty($product['images'])) {
            $product['images'] = ['/assets/images/products/placeholder.jpg'];
        }
        
        $product['main_image'] = $product['images'][0];
        
        // Processar especificações
        if (isset($product['specifications']) && is_string($product['specifications'])) {
            $product['specifications'] = json_decode($product['specifications'], true) ?: [];
        }
        
        // Status de estoque
        $product['stock_status'] = $this->getStockStatus($product['stock_quantity'] ?? 0);
        
        // URL amigável
        $product['url'] = '/produto/' . $this->generateSlug($product['name']) . '-' . $product['id'];
        
        return $product;
    }
    
    /**
     * Valida dados do produto
     * 
     * @param array $data Dados do produto
     * @param bool $isCreate Se é criação (true) ou atualização (false)
     * @return array
     * @throws \InvalidArgumentException
     */
    private function validateProductData(array $data, bool $isCreate = true): array {
        $errors = [];
        
        // Validar nome
        if ($isCreate || isset($data['name'])) {
            if (empty($data['name']) || strlen(trim($data['name'])) < 3) {
                $errors[] = 'Nome deve ter pelo menos 3 caracteres';
            } elseif (strlen($data['name']) > 255) {
                $errors[] = 'Nome deve ter no máximo 255 caracteres';
            }
        }
        
        // Validar descrição
        if ($isCreate || isset($data['description'])) {
            if (empty($data['description']) || strlen(trim($data['description'])) < 10) {
                $errors[] = 'Descrição deve ter pelo menos 10 caracteres';
            }
        }
        
        // Validar preço
        if ($isCreate || isset($data['price'])) {
            if (!isset($data['price']) || !is_numeric($data['price']) || $data['price'] <= 0) {
                $errors[] = 'Preço deve ser um valor positivo';
            }
        }
        
        // Validar preço original
        if (isset($data['original_price'])) {
            if (!is_numeric($data['original_price']) || $data['original_price'] < 0) {
                $errors[] = 'Preço original deve ser um valor não negativo';
            } elseif (isset($data['price']) && $data['original_price'] < $data['price']) {
                $errors[] = 'Preço original deve ser maior ou igual ao preço atual';
            }
        }
        
        // Validar categoria
        if ($isCreate || isset($data['category'])) {
            if (empty($data['category'])) {
                $errors[] = 'Categoria é obrigatória';
            }
        }
        
        // Validar quantidade em estoque
        if ($isCreate || isset($data['stock_quantity'])) {
            if (!isset($data['stock_quantity']) || !is_numeric($data['stock_quantity']) || $data['stock_quantity'] < 0) {
                $errors[] = 'Quantidade em estoque deve ser um valor não negativo';
            }
        }
        
        if (!empty($errors)) {
            throw new \InvalidArgumentException('Dados inválidos: ' . implode(', ', $errors));
        }
        
        return $data;
    }
    
    /**
     * Pré-processa dados do produto antes de salvar
     * 
     * @param array $data Dados do produto
     * @return array
     */
    private function preprocessProductData(array $data): array {
        // Limpar e formatar nome
        if (isset($data['name'])) {
            $data['name'] = trim($data['name']);
        }
        
        // Limpar descrição
        if (isset($data['description'])) {
            $data['description'] = trim($data['description']);
        }
        
        // Gerar slug
        if (isset($data['name'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }
        
        // Processar especificações
        if (isset($data['specifications']) && is_array($data['specifications'])) {
            $data['specifications'] = json_encode($data['specifications']);
        }
        
        // Processar imagens
        if (isset($data['images']) && is_array($data['images'])) {
            $data['images'] = json_encode($data['images']);
        }
        
        // Definir timestamps
        $now = date('Y-m-d H:i:s');
        if (!isset($data['created_at'])) {
            $data['created_at'] = $now;
        }
        $data['updated_at'] = $now;
        
        return $data;
    }
    
    /**
     * Sanitiza termo de busca
     * 
     * @param string $query Termo de busca
     * @return string
     */
    private function sanitizeSearchQuery(string $query): string {
        return trim(preg_replace('/[^\w\s\-áéíóúàèìòùâêîôûãõç]/ui', '', $query));
    }
    
    /**
     * Sanitiza string
     * 
     * @param string $string String
     * @return string
     */
    private function sanitizeString(string $string): string {
        return trim(strip_tags($string));
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
     * Obtém status do estoque
     * 
     * @param int $quantity Quantidade
     * @return string
     */
    private function getStockStatus(int $quantity): string {
        if ($quantity <= 0) {
            return 'out_of_stock';
        } elseif ($quantity <= 10) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }
    
    /**
     * Gera slug a partir do nome
     * 
     * @param string $name Nome
     * @return string
     */
    private function generateSlug(string $name): string {
        $slug = strtolower($name);
        $slug = preg_replace('/[áàâãä]/u', 'a', $slug);
        $slug = preg_replace('/[éèêë]/u', 'e', $slug);
        $slug = preg_replace('/[íìîï]/u', 'i', $slug);
        $slug = preg_replace('/[óòôõö]/u', 'o', $slug);
        $slug = preg_replace('/[úùûü]/u', 'u', $slug);
        $slug = preg_replace('/[ç]/u', 'c', $slug);
        $slug = preg_replace('/[^a-z0-9\s\-]/', '', $slug);
        $slug = preg_replace('/[\s\-]+/', '-', $slug);
        return trim($slug, '-');
    }
    
    /**
     * Obtém produtos relacionados
     * 
     * @param string $category Categoria
     * @param int $excludeId ID para excluir
     * @param int $limit Limite
     * @return array
     */
    private function getRelatedProducts(string $category, int $excludeId, int $limit): array {
        $products = $this->productRepository->findByCategory($category, 1, $limit + 5);
        
        $related = array_filter($products['items'], function($product) use ($excludeId) {
            return $product['id'] !== $excludeId;
        });
        
        $related = array_slice($related, 0, $limit);
        
        return array_map(function($product) {
            return $this->processProductData($product);
        }, $related);
    }
    
    /**
     * Obtém avaliações do produto
     * 
     * @param int $productId ID do produto
     * @return array
     */
    private function getProductReviews(int $productId): array {
        // Em produção, buscar do repositório de reviews
        return [
            'average_rating' => 4.5,
            'total_reviews' => 156,
            'recent_reviews' => [
                [
                    'id' => 1,
                    'rating' => 5,
                    'comment' => 'Produto excelente!',
                    'author' => 'João S.',
                    'date' => '2024-01-15'
                ]
            ]
        ];
    }
    
    /**
     * Verifica disponibilidade do produto
     * 
     * @param array $product Dados do produto
     * @param int $quantity Quantidade desejada
     * @return array
     */
    private function checkProductAvailability(array $product, int $quantity = 1): array {
        if (!isset($product['status']) || $product['status'] !== 'active') {
            return [
                'available' => false,
                'reason' => 'Produto não disponível'
            ];
        }
        
        $stockQuantity = $product['stock_quantity'] ?? 0;
        
        if ($stockQuantity <= 0) {
            return [
                'available' => false,
                'reason' => 'Produto fora de estoque'
            ];
        }
        
        if ($stockQuantity < $quantity) {
            return [
                'available' => false,
                'reason' => 'Quantidade insuficiente em estoque',
                'available_quantity' => $stockQuantity
            ];
        }
        
        return [
            'available' => true,
            'stock_quantity' => $stockQuantity,
            'estimated_delivery' => $this->calculateEstimatedDelivery()
        ];
    }
    
    /**
     * Calcula prazo estimado de entrega
     * 
     * @return string
     */
    private function calculateEstimatedDelivery(): string {
        // Lógica simples - em produção seria mais complexa
        return '3-5 dias úteis';
    }
    
    /**
     * Verifica se produto pode ser removido
     * 
     * @param int $id ID do produto
     * @return bool
     */
    private function canDeleteProduct(int $id): bool {
        // Verificar se produto tem pedidos associados
        // Em produção, consultar repositório de pedidos
        return true;
    }
}