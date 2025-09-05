<?php

namespace Repositories;

use Entities\Product;
use DTOs\ProductDTO;

/**
 * Repositório de Produtos - NeonShop
 * 
 * Responsável pelo acesso aos dados dos produtos:
 * - Operações CRUD (Create, Read, Update, Delete)
 * - Consultas com filtros e ordenação
 * - Gerenciamento de estoque
 * - Busca e paginação
 */
class ProductRepository {
    
    private array $products;
    private string $dataFile;
    
    public function __construct() {
        $this->dataFile = __DIR__ . '/../../data/products.json';
        $this->loadProducts();
    }
    
    /**
     * Busca produto por ID
     * 
     * @param int $id ID do produto
     * @return array|null
     */
    public function findById(int $id): ?array {
        return $this->products[$id] ?? null;
    }
    
    /**
     * Busca produtos com filtros
     * 
     * @param array $filters Filtros de busca
     * @param int $page Página atual
     * @param int $limit Itens por página
     * @param string $orderBy Campo de ordenação
     * @param string $orderDirection Direção da ordenação (ASC/DESC)
     * @return array
     */
    public function findWithFilters(
        array $filters = [],
        int $page = 1,
        int $limit = 12,
        string $orderBy = 'name',
        string $orderDirection = 'ASC'
    ): array {
        $filteredProducts = $this->applyFilters($this->products, $filters);
        $sortedProducts = $this->sortProducts($filteredProducts, $orderBy, $orderDirection);
        
        // Paginação
        $total = count($sortedProducts);
        $offset = ($page - 1) * $limit;
        $paginatedProducts = array_slice($sortedProducts, $offset, $limit, true);
        
        return [
            'items' => array_values($paginatedProducts),
            'pagination' => [
                'current_page' => $page,
                'per_page' => $limit,
                'total' => $total,
                'total_pages' => ceil($total / $limit),
                'has_previous' => $page > 1,
                'has_next' => $page < ceil($total / $limit)
            ],
            'filters_applied' => $filters
        ];
    }
    
    /**
     * Busca produtos por termo
     * 
     * @param string $term Termo de busca
     * @param int $limit Limite de resultados
     * @return array
     */
    public function search(string $term, int $limit = 20): array {
        $term = strtolower(trim($term));
        
        if (empty($term)) {
            return [];
        }
        
        $results = [];
        
        foreach ($this->products as $product) {
            if (!$product['active']) {
                continue;
            }
            
            $score = 0;
            
            // Busca no nome (peso maior)
            if (stripos($product['name'], $term) !== false) {
                $score += 10;
                if (stripos($product['name'], $term) === 0) {
                    $score += 5; // Bonus se começar com o termo
                }
            }
            
            // Busca na descrição
            if (stripos($product['description'], $term) !== false) {
                $score += 3;
            }
            
            // Busca nas tags
            if (isset($product['tags'])) {
                foreach ($product['tags'] as $tag) {
                    if (stripos($tag, $term) !== false) {
                        $score += 2;
                    }
                }
            }
            
            // Busca na categoria
            if (stripos($product['category'], $term) !== false) {
                $score += 1;
            }
            
            if ($score > 0) {
                $results[] = array_merge($product, ['search_score' => $score]);
            }
        }
        
        // Ordenar por relevância
        usort($results, function($a, $b) {
            return $b['search_score'] <=> $a['search_score'];
        });
        
        return array_slice($results, 0, $limit);
    }
    
    /**
     * Obtém produtos por categoria
     * 
     * @param string $category Categoria
     * @param int $limit Limite de resultados
     * @return array
     */
    public function findByCategory(string $category, int $limit = 12): array {
        $categoryProducts = array_filter($this->products, function($product) use ($category) {
            return $product['active'] && 
                   strcasecmp($product['category'], $category) === 0;
        });
        
        // Ordenar por popularidade/vendas
        usort($categoryProducts, function($a, $b) {
            return ($b['sales_count'] ?? 0) <=> ($a['sales_count'] ?? 0);
        });
        
        return array_slice($categoryProducts, 0, $limit);
    }
    
    /**
     * Obtém produtos em destaque
     * 
     * @param int $limit Limite de resultados
     * @return array
     */
    public function getFeatured(int $limit = 8): array {
        $featuredProducts = array_filter($this->products, function($product) {
            return $product['active'] && ($product['featured'] ?? false);
        });
        
        // Ordenar por data de criação (mais recentes primeiro)
        usort($featuredProducts, function($a, $b) {
            return strtotime($b['created_at'] ?? '2024-01-01') <=> 
                   strtotime($a['created_at'] ?? '2024-01-01');
        });
        
        return array_slice($featuredProducts, 0, $limit);
    }
    
    /**
     * Obtém produtos mais vendidos
     * 
     * @param int $limit Limite de resultados
     * @return array
     */
    public function getBestSellers(int $limit = 8): array {
        $activeProducts = array_filter($this->products, function($product) {
            return $product['active'];
        });
        
        // Ordenar por vendas
        usort($activeProducts, function($a, $b) {
            return ($b['sales_count'] ?? 0) <=> ($a['sales_count'] ?? 0);
        });
        
        return array_slice($activeProducts, 0, $limit);
    }
    
    /**
     * Obtém produtos em promoção
     * 
     * @param int $limit Limite de resultados
     * @return array
     */
    public function getOnSale(int $limit = 8): array {
        $saleProducts = array_filter($this->products, function($product) {
            return $product['active'] && 
                   isset($product['sale_price']) && 
                   $product['sale_price'] > 0 && 
                   $product['sale_price'] < $product['price'];
        });
        
        // Ordenar por desconto (maior desconto primeiro)
        usort($saleProducts, function($a, $b) {
            $discountA = (($a['price'] - $a['sale_price']) / $a['price']) * 100;
            $discountB = (($b['price'] - $b['sale_price']) / $b['price']) * 100;
            return $discountB <=> $discountA;
        });
        
        return array_slice($saleProducts, 0, $limit);
    }
    
    /**
     * Obtém produtos relacionados
     * 
     * @param int $productId ID do produto
     * @param int $limit Limite de resultados
     * @return array
     */
    public function getRelated(int $productId, int $limit = 4): array {
        $product = $this->findById($productId);
        
        if (!$product) {
            return [];
        }
        
        // Buscar produtos da mesma categoria, excluindo o produto atual
        $relatedProducts = array_filter($this->products, function($p) use ($product, $productId) {
            return $p['active'] && 
                   $p['id'] !== $productId && 
                   $p['category'] === $product['category'];
        });
        
        // Ordenar por popularidade
        usort($relatedProducts, function($a, $b) {
            return ($b['sales_count'] ?? 0) <=> ($a['sales_count'] ?? 0);
        });
        
        return array_slice($relatedProducts, 0, $limit);
    }
    
    /**
     * Cria novo produto
     * 
     * @param array $data Dados do produto
     * @return array
     * @throws \InvalidArgumentException
     */
    public function create(array $data): array {
        $this->validateProductData($data);
        
        // Gerar novo ID
        $newId = $this->getNextId();
        
        // Preparar dados do produto
        $product = array_merge($data, [
            'id' => $newId,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'active' => $data['active'] ?? true,
            'sales_count' => 0,
            'views_count' => 0
        ]);
        
        // Adicionar produto
        $this->products[$newId] = $product;
        
        // Salvar dados
        $this->saveProducts();
        
        return $product;
    }
    
    /**
     * Atualiza produto
     * 
     * @param int $id ID do produto
     * @param array $data Dados para atualização
     * @return bool
     * @throws \InvalidArgumentException
     */
    public function update(int $id, array $data): bool {
        if (!isset($this->products[$id])) {
            return false;
        }
        
        $this->validateProductData($data, false);
        
        // Atualizar dados
        $this->products[$id] = array_merge($this->products[$id], $data, [
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        // Salvar dados
        $this->saveProducts();
        
        return true;
    }
    
    /**
     * Remove produto
     * 
     * @param int $id ID do produto
     * @return bool
     */
    public function delete(int $id): bool {
        if (!isset($this->products[$id])) {
            return false;
        }
        
        unset($this->products[$id]);
        
        // Salvar dados
        $this->saveProducts();
        
        return true;
    }
    
    /**
     * Decrementa estoque do produto
     * 
     * @param int $id ID do produto
     * @param int $quantity Quantidade a decrementar
     * @return bool
     */
    public function decrementStock(int $id, int $quantity): bool {
        if (!isset($this->products[$id])) {
            return false;
        }
        
        $currentStock = $this->products[$id]['stock_quantity'] ?? 0;
        
        if ($currentStock < $quantity) {
            return false;
        }
        
        $this->products[$id]['stock_quantity'] = $currentStock - $quantity;
        $this->products[$id]['updated_at'] = date('Y-m-d H:i:s');
        
        // Incrementar contador de vendas
        $this->products[$id]['sales_count'] = ($this->products[$id]['sales_count'] ?? 0) + $quantity;
        
        $this->saveProducts();
        
        return true;
    }
    
    /**
     * Incrementa estoque do produto
     * 
     * @param int $id ID do produto
     * @param int $quantity Quantidade a incrementar
     * @return bool
     */
    public function incrementStock(int $id, int $quantity): bool {
        if (!isset($this->products[$id])) {
            return false;
        }
        
        $this->products[$id]['stock_quantity'] = ($this->products[$id]['stock_quantity'] ?? 0) + $quantity;
        $this->products[$id]['updated_at'] = date('Y-m-d H:i:s');
        
        $this->saveProducts();
        
        return true;
    }
    
    /**
     * Incrementa contador de visualizações
     * 
     * @param int $id ID do produto
     * @return bool
     */
    public function incrementViews(int $id): bool {
        if (!isset($this->products[$id])) {
            return false;
        }
        
        $this->products[$id]['views_count'] = ($this->products[$id]['views_count'] ?? 0) + 1;
        $this->saveProducts();
        
        return true;
    }
    
    /**
     * Obtém todas as categorias
     * 
     * @return array
     */
    public function getCategories(): array {
        $categories = [];
        
        foreach ($this->products as $product) {
            if ($product['active'] && !empty($product['category'])) {
                $category = $product['category'];
                
                if (!isset($categories[$category])) {
                    $categories[$category] = [
                        'name' => $category,
                        'count' => 0,
                        'slug' => $this->generateSlug($category)
                    ];
                }
                
                $categories[$category]['count']++;
            }
        }
        
        // Ordenar por nome
        ksort($categories);
        
        return array_values($categories);
    }
    
    /**
     * Obtém estatísticas dos produtos
     * 
     * @return array
     */
    public function getStats(): array {
        $total = count($this->products);
        $active = count(array_filter($this->products, fn($p) => $p['active']));
        $inactive = $total - $active;
        $outOfStock = count(array_filter($this->products, fn($p) => ($p['stock_quantity'] ?? 0) === 0));
        $lowStock = count(array_filter($this->products, fn($p) => ($p['stock_quantity'] ?? 0) > 0 && ($p['stock_quantity'] ?? 0) <= 5));
        
        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $inactive,
            'out_of_stock' => $outOfStock,
            'low_stock' => $lowStock,
            'categories' => count($this->getCategories())
        ];
    }
    
    // ========== MÉTODOS PRIVADOS ==========
    
    /**
     * Carrega produtos do arquivo JSON
     */
    private function loadProducts(): void {
        if (file_exists($this->dataFile)) {
            $data = json_decode(file_get_contents($this->dataFile), true);
            $this->products = $data ?: [];
        } else {
            $this->products = $this->getDefaultProducts();
            $this->saveProducts();
        }
    }
    
    /**
     * Salva produtos no arquivo JSON
     */
    private function saveProducts(): void {
        $dir = dirname($this->dataFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        file_put_contents($this->dataFile, json_encode($this->products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
    
    /**
     * Aplica filtros aos produtos
     * 
     * @param array $products Produtos
     * @param array $filters Filtros
     * @return array
     */
    private function applyFilters(array $products, array $filters): array {
        $filtered = $products;
        
        // Filtrar apenas produtos ativos por padrão
        $filtered = array_filter($filtered, fn($p) => $p['active']);
        
        // Filtro por categoria
        if (!empty($filters['category'])) {
            $filtered = array_filter($filtered, function($p) use ($filters) {
                return strcasecmp($p['category'], $filters['category']) === 0;
            });
        }
        
        // Filtro por faixa de preço
        if (!empty($filters['min_price'])) {
            $minPrice = (float)$filters['min_price'];
            $filtered = array_filter($filtered, function($p) use ($minPrice) {
                $price = $p['sale_price'] ?? $p['price'];
                return $price >= $minPrice;
            });
        }
        
        if (!empty($filters['max_price'])) {
            $maxPrice = (float)$filters['max_price'];
            $filtered = array_filter($filtered, function($p) use ($maxPrice) {
                $price = $p['sale_price'] ?? $p['price'];
                return $price <= $maxPrice;
            });
        }
        
        // Filtro por disponibilidade
        if (isset($filters['in_stock']) && $filters['in_stock']) {
            $filtered = array_filter($filtered, fn($p) => ($p['stock_quantity'] ?? 0) > 0);
        }
        
        // Filtro por promoção
        if (isset($filters['on_sale']) && $filters['on_sale']) {
            $filtered = array_filter($filtered, function($p) {
                return isset($p['sale_price']) && 
                       $p['sale_price'] > 0 && 
                       $p['sale_price'] < $p['price'];
            });
        }
        
        // Filtro por termo de busca
        if (!empty($filters['search'])) {
            $term = strtolower($filters['search']);
            $filtered = array_filter($filtered, function($p) use ($term) {
                return stripos($p['name'], $term) !== false ||
                       stripos($p['description'], $term) !== false ||
                       stripos($p['category'], $term) !== false;
            });
        }
        
        return $filtered;
    }
    
    /**
     * Ordena produtos
     * 
     * @param array $products Produtos
     * @param string $orderBy Campo de ordenação
     * @param string $direction Direção (ASC/DESC)
     * @return array
     */
    private function sortProducts(array $products, string $orderBy, string $direction): array {
        $direction = strtoupper($direction);
        
        usort($products, function($a, $b) use ($orderBy, $direction) {
            $valueA = $this->getSortValue($a, $orderBy);
            $valueB = $this->getSortValue($b, $orderBy);
            
            $comparison = $valueA <=> $valueB;
            
            return $direction === 'DESC' ? -$comparison : $comparison;
        });
        
        return $products;
    }
    
    /**
     * Obtém valor para ordenação
     * 
     * @param array $product Produto
     * @param string $field Campo
     * @return mixed
     */
    private function getSortValue(array $product, string $field) {
        switch ($field) {
            case 'price':
                return $product['sale_price'] ?? $product['price'];
            case 'name':
                return strtolower($product['name']);
            case 'created_at':
                return strtotime($product['created_at'] ?? '2024-01-01');
            case 'sales':
                return $product['sales_count'] ?? 0;
            case 'stock':
                return $product['stock_quantity'] ?? 0;
            default:
                return $product[$field] ?? '';
        }
    }
    
    /**
     * Valida dados do produto
     * 
     * @param array $data Dados do produto
     * @param bool $isCreate Se é criação (true) ou atualização (false)
     * @throws \InvalidArgumentException
     */
    private function validateProductData(array $data, bool $isCreate = true): void {
        $errors = [];
        
        if ($isCreate || isset($data['name'])) {
            if (empty($data['name']) || strlen(trim($data['name'])) < 2) {
                $errors[] = 'Nome do produto é obrigatório e deve ter pelo menos 2 caracteres';
            }
        }
        
        if ($isCreate || isset($data['price'])) {
            if (!isset($data['price']) || $data['price'] <= 0) {
                $errors[] = 'Preço deve ser maior que zero';
            }
        }
        
        if ($isCreate || isset($data['category'])) {
            if (empty($data['category'])) {
                $errors[] = 'Categoria é obrigatória';
            }
        }
        
        if (isset($data['stock_quantity']) && $data['stock_quantity'] < 0) {
            $errors[] = 'Quantidade em estoque não pode ser negativa';
        }
        
        if (isset($data['sale_price']) && $data['sale_price'] < 0) {
            $errors[] = 'Preço promocional não pode ser negativo';
        }
        
        if (!empty($errors)) {
            throw new \InvalidArgumentException('Dados inválidos: ' . implode(', ', $errors));
        }
    }
    
    /**
     * Obtém próximo ID disponível
     * 
     * @return int
     */
    private function getNextId(): int {
        if (empty($this->products)) {
            return 1;
        }
        
        return max(array_keys($this->products)) + 1;
    }
    
    /**
     * Gera slug a partir do texto
     * 
     * @param string $text Texto
     * @return string
     */
    private function generateSlug(string $text): string {
        $text = strtolower($text);
        $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
        $text = preg_replace('/[\s-]+/', '-', $text);
        return trim($text, '-');
    }
    
    /**
     * Obtém produtos padrão para inicialização
     * 
     * @return array
     */
    private function getDefaultProducts(): array {
        return [
            1 => [
                'id' => 1,
                'name' => 'Smartphone Galaxy Pro',
                'description' => 'Smartphone premium com tela AMOLED de 6.7", câmera tripla de 108MP e bateria de 5000mAh.',
                'price' => 2499.99,
                'sale_price' => 2199.99,
                'category' => 'Smartphones',
                'image_url' => '/assets/images/products/smartphone-galaxy-pro.jpg',
                'gallery' => [
                    '/assets/images/products/smartphone-galaxy-pro-1.jpg',
                    '/assets/images/products/smartphone-galaxy-pro-2.jpg',
                    '/assets/images/products/smartphone-galaxy-pro-3.jpg'
                ],
                'stock_quantity' => 25,
                'weight' => 0.195,
                'dimensions' => '164.2 x 75.6 x 8.8 mm',
                'tags' => ['smartphone', 'android', 'premium', 'camera'],
                'featured' => true,
                'active' => true,
                'sales_count' => 156,
                'views_count' => 2341,
                'created_at' => '2024-01-15 10:30:00',
                'updated_at' => '2024-01-20 14:22:00'
            ],
            2 => [
                'id' => 2,
                'name' => 'Notebook UltraBook Pro',
                'description' => 'Notebook ultrafino com processador Intel i7, 16GB RAM, SSD 512GB e tela 14" Full HD.',
                'price' => 3999.99,
                'category' => 'Notebooks',
                'image_url' => '/assets/images/products/notebook-ultrabook-pro.jpg',
                'gallery' => [
                    '/assets/images/products/notebook-ultrabook-pro-1.jpg',
                    '/assets/images/products/notebook-ultrabook-pro-2.jpg'
                ],
                'stock_quantity' => 12,
                'weight' => 1.4,
                'dimensions' => '321 x 214 x 15.9 mm',
                'tags' => ['notebook', 'intel', 'ultrabook', 'ssd'],
                'featured' => true,
                'active' => true,
                'sales_count' => 89,
                'views_count' => 1876,
                'created_at' => '2024-01-10 09:15:00',
                'updated_at' => '2024-01-18 16:45:00'
            ],
            3 => [
                'id' => 3,
                'name' => 'Fone Bluetooth Premium',
                'description' => 'Fone de ouvido sem fio com cancelamento de ruído ativo e bateria de 30 horas.',
                'price' => 599.99,
                'sale_price' => 449.99,
                'category' => 'Áudio',
                'image_url' => '/assets/images/products/fone-bluetooth-premium.jpg',
                'stock_quantity' => 45,
                'weight' => 0.25,
                'tags' => ['fone', 'bluetooth', 'wireless', 'premium'],
                'featured' => false,
                'active' => true,
                'sales_count' => 234,
                'views_count' => 3421,
                'created_at' => '2024-01-08 14:20:00',
                'updated_at' => '2024-01-19 11:30:00'
            ]
        ];
    }
}