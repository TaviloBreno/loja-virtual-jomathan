<?php

namespace Controllers;

use Core\Request;
use Core\Response;

/**
 * Controlador Home - NeonShop
 * 
 * Responsável pelas páginas principais do e-commerce:
 * - Página inicial
 * - Listagem de produtos
 * - Página de produto individual
 * - Busca de produtos
 */
class HomeController extends BaseController {
    
    /**
     * Página inicial do e-commerce
     * 
     * @return Response
     */
    public function index(): Response {
        // Dados simulados para a página inicial
        $featuredProducts = $this->getFeaturedProducts();
        $newProducts = $this->getNewProducts();
        $categories = $this->getCategories();
        $banners = $this->getBanners();
        
        $data = [
            'page_title' => 'Início - Sua loja online com estilo futurista',
            'page_description' => 'Descubra produtos incríveis com tecnologia de ponta e design futurista. Ofertas especiais e lançamentos exclusivos.',
            'featured_products' => $featuredProducts,
            'new_products' => $newProducts,
            'categories' => $categories,
            'banners' => $banners,
            'flash_message' => $this->getFlashMessage()
        ];
        
        return $this->view('pages/home', $data);
    }
    
    /**
     * Listagem de produtos com filtros
     * 
     * @return Response
     */
    public function products(): Response {
        // Parâmetros de busca e filtros
        $search = $this->request->getQueryParam('search', '');
        $category = $this->request->getQueryParam('category', '');
        $minPrice = (float)$this->request->getQueryParam('min_price', 0);
        $maxPrice = (float)$this->request->getQueryParam('max_price', 0);
        $sortBy = $this->request->getQueryParam('sort', 'name');
        $page = (int)$this->request->getQueryParam('page', 1);
        
        // Obter produtos com filtros aplicados
        $allProducts = $this->getAllProducts();
        $filteredProducts = $this->filterProducts($allProducts, [
            'search' => $search,
            'category' => $category,
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
            'sort' => $sortBy
        ]);
        
        // Paginação
        $paginatedData = $this->paginate($filteredProducts, 12, $page);
        
        $data = [
            'page_title' => 'Produtos - NeonShop',
            'page_description' => 'Explore nossa coleção completa de produtos com tecnologia avançada e design futurista.',
            'products' => $paginatedData['items'],
            'pagination' => $paginatedData['pagination'],
            'categories' => $this->getCategories(),
            'filters' => [
                'search' => $search,
                'category' => $category,
                'min_price' => $minPrice,
                'max_price' => $maxPrice,
                'sort' => $sortBy
            ],
            'total_products' => count($filteredProducts)
        ];
        
        return $this->view('pages/products', $data);
    }
    
    /**
     * Página de produto individual
     * 
     * @param int $id ID do produto
     * @return Response
     */
    public function product(int $id): Response {
        $product = $this->getProductById($id);
        
        if (!$product) {
            return $this->error404('Produto não encontrado');
        }
        
        // Produtos relacionados
        $relatedProducts = $this->getRelatedProducts($product['category'], $id);
        
        // Avaliações do produto
        $reviews = $this->getProductReviews($id);
        
        $data = [
            'page_title' => $product['name'] . ' - NeonShop',
            'page_description' => $product['description'],
            'product' => $product,
            'related_products' => $relatedProducts,
            'reviews' => $reviews,
            'flash_message' => $this->getFlashMessage()
        ];
        
        return $this->view('pages/product', $data);
    }
    
    /**
     * Busca de produtos via AJAX
     * 
     * @return Response
     */
    public function searchProducts(): Response {
        $query = $this->request->getQueryParam('q', '');
        $limit = (int)$this->request->getQueryParam('limit', 10);
        
        if (strlen($query) < 2) {
            return $this->json([
                'products' => [],
                'message' => 'Digite pelo menos 2 caracteres para buscar'
            ]);
        }
        
        $products = $this->searchProductsByQuery($query, $limit);
        
        return $this->json([
            'products' => $products,
            'total' => count($products)
        ]);
    }
    
    /**
     * Página de carrinho de compras
     * 
     * @return Response
     */
    public function cart(): Response {
        $cart = $_SESSION['cart'] ?? [];
        $cartTotal = $this->calculateCartTotal($cart);
        
        $data = [
            'page_title' => 'Carrinho de Compras - NeonShop',
            'page_description' => 'Revise seus itens e finalize sua compra com segurança.',
            'cart_items' => $cart,
            'cart_total' => $cartTotal,
            'flash_message' => $this->getFlashMessage()
        ];
        
        return $this->view('pages/cart', $data);
    }
    
    /**
     * Página de checkout
     * 
     * @return Response
     */
    public function checkout(): Response {
        $cart = $_SESSION['cart'] ?? [];
        
        if (empty($cart)) {
            $this->setFlashMessage('Seu carrinho está vazio', 'warning');
            return $this->redirect('/cart');
        }
        
        $cartTotal = $this->calculateCartTotal($cart);
        
        $data = [
            'page_title' => 'Finalizar Compra - NeonShop',
            'page_description' => 'Complete seus dados e finalize sua compra com segurança.',
            'cart_items' => $cart,
            'cart_total' => $cartTotal,
            'flash_message' => $this->getFlashMessage()
        ];
        
        return $this->view('pages/checkout', $data);
    }
    
    /**
     * Página de contato
     * 
     * @return Response
     */
    public function contact(): Response {
        $data = [
            'page_title' => 'Contato - NeonShop',
            'page_description' => 'Entre em contato conosco. Estamos aqui para ajudar!',
            'flash_message' => $this->getFlashMessage()
        ];
        
        return $this->view('pages/contact', $data);
    }
    
    /**
     * Página de política de privacidade
     * 
     * @return Response
     */
    public function privacy(): Response {
        $data = [
            'page_title' => 'Política de Privacidade - NeonShop',
            'page_description' => 'Conheça nossa política de privacidade e proteção de dados.',
            'flash_message' => $this->getFlashMessage()
        ];
        
        return $this->view('pages/privacy', $data);
    }
    
    /**
     * Página de trocas e devoluções
     * 
     * @return Response
     */
    public function returns(): Response {
        $data = [
            'page_title' => 'Trocas e Devoluções - NeonShop',
            'page_description' => 'Saiba como solicitar trocas e devoluções de forma simples e rápida.',
            'flash_message' => $this->getFlashMessage()
        ];
        
        return $this->view('pages/returns', $data);
    }
    
    // ========== MÉTODOS PRIVADOS PARA DADOS SIMULADOS ==========
    
    /**
     * Obtém produtos em destaque
     * 
     * @return array
     */
    private function getFeaturedProducts(): array {
        return [
            [
                'id' => 1,
                'name' => 'Smartphone Neon X1',
                'price' => 1299.99,
                'original_price' => 1599.99,
                'image' => '/assets/images/products/smartphone-x1.jpg',
                'rating' => 4.8,
                'reviews_count' => 156,
                'category' => 'smartphones',
                'featured' => true
            ],
            [
                'id' => 2,
                'name' => 'Headset Gamer RGB Pro',
                'price' => 299.99,
                'original_price' => 399.99,
                'image' => '/assets/images/products/headset-rgb.jpg',
                'rating' => 4.6,
                'reviews_count' => 89,
                'category' => 'gaming',
                'featured' => true
            ],
            [
                'id' => 3,
                'name' => 'Smartwatch Neon Fit',
                'price' => 599.99,
                'original_price' => 799.99,
                'image' => '/assets/images/products/smartwatch-fit.jpg',
                'rating' => 4.7,
                'reviews_count' => 203,
                'category' => 'wearables',
                'featured' => true
            ]
        ];
    }
    
    /**
     * Obtém produtos novos
     * 
     * @return array
     */
    private function getNewProducts(): array {
        return [
            [
                'id' => 4,
                'name' => 'Tablet Neon Pro 12"',
                'price' => 899.99,
                'image' => '/assets/images/products/tablet-pro.jpg',
                'rating' => 4.5,
                'reviews_count' => 67,
                'category' => 'tablets',
                'is_new' => true
            ],
            [
                'id' => 5,
                'name' => 'Câmera Action 4K',
                'price' => 449.99,
                'image' => '/assets/images/products/camera-action.jpg',
                'rating' => 4.4,
                'reviews_count' => 34,
                'category' => 'cameras',
                'is_new' => true
            ],
            [
                'id' => 6,
                'name' => 'Speaker Bluetooth Neon',
                'price' => 199.99,
                'image' => '/assets/images/products/speaker-bluetooth.jpg',
                'rating' => 4.3,
                'reviews_count' => 78,
                'category' => 'audio',
                'is_new' => true
            ]
        ];
    }
    
    /**
     * Obtém todas as categorias
     * 
     * @return array
     */
    private function getCategories(): array {
        return [
            ['id' => 'smartphones', 'name' => 'Smartphones', 'count' => 24],
            ['id' => 'tablets', 'name' => 'Tablets', 'count' => 12],
            ['id' => 'laptops', 'name' => 'Laptops', 'count' => 18],
            ['id' => 'gaming', 'name' => 'Gaming', 'count' => 31],
            ['id' => 'audio', 'name' => 'Áudio', 'count' => 22],
            ['id' => 'wearables', 'name' => 'Wearables', 'count' => 15],
            ['id' => 'cameras', 'name' => 'Câmeras', 'count' => 9],
            ['id' => 'accessories', 'name' => 'Acessórios', 'count' => 45]
        ];
    }
    
    /**
     * Obtém banners da página inicial
     * 
     * @return array
     */
    private function getBanners(): array {
        return [
            [
                'id' => 1,
                'title' => 'Mega Promoção Neon',
                'subtitle' => 'Até 50% OFF em produtos selecionados',
                'image' => '/assets/images/banners/promo-neon.jpg',
                'link' => '/products?category=smartphones',
                'active' => true
            ],
            [
                'id' => 2,
                'title' => 'Lançamento Exclusivo',
                'subtitle' => 'Novos produtos com tecnologia de ponta',
                'image' => '/assets/images/banners/lancamento.jpg',
                'link' => '/products?sort=newest',
                'active' => true
            ]
        ];
    }
    
    /**
     * Obtém todos os produtos
     * 
     * @return array
     */
    private function getAllProducts(): array {
        // Combinar produtos em destaque e novos com outros produtos
        $featured = $this->getFeaturedProducts();
        $new = $this->getNewProducts();
        
        $otherProducts = [
            [
                'id' => 7,
                'name' => 'Laptop Gamer Neon RTX',
                'price' => 3299.99,
                'image' => '/assets/images/products/laptop-gamer.jpg',
                'rating' => 4.9,
                'reviews_count' => 145,
                'category' => 'laptops',
                'description' => 'Laptop gamer com placa RTX e processador de última geração'
            ],
            [
                'id' => 8,
                'name' => 'Mouse Gamer RGB',
                'price' => 89.99,
                'image' => '/assets/images/products/mouse-gamer.jpg',
                'rating' => 4.2,
                'reviews_count' => 234,
                'category' => 'gaming',
                'description' => 'Mouse gamer com iluminação RGB e alta precisão'
            ],
            [
                'id' => 9,
                'name' => 'Teclado Mecânico RGB',
                'price' => 199.99,
                'image' => '/assets/images/products/teclado-mecanico.jpg',
                'rating' => 4.6,
                'reviews_count' => 167,
                'category' => 'gaming',
                'description' => 'Teclado mecânico com switches blue e RGB customizável'
            ],
            [
                'id' => 10,
                'name' => 'Carregador Wireless Neon',
                'price' => 79.99,
                'image' => '/assets/images/products/carregador-wireless.jpg',
                'rating' => 4.1,
                'reviews_count' => 92,
                'category' => 'accessories',
                'description' => 'Carregador sem fio com design futurista e LED'
            ]
        ];
        
        return array_merge($featured, $new, $otherProducts);
    }
    
    /**
     * Filtra produtos baseado nos critérios
     * 
     * @param array $products Lista de produtos
     * @param array $filters Filtros aplicados
     * @return array
     */
    private function filterProducts(array $products, array $filters): array {
        $filtered = $products;
        
        // Filtro por busca
        if (!empty($filters['search'])) {
            $search = strtolower($filters['search']);
            $filtered = array_filter($filtered, function($product) use ($search) {
                return strpos(strtolower($product['name']), $search) !== false ||
                       strpos(strtolower($product['description'] ?? ''), $search) !== false;
            });
        }
        
        // Filtro por categoria
        if (!empty($filters['category'])) {
            $filtered = array_filter($filtered, function($product) use ($filters) {
                return $product['category'] === $filters['category'];
            });
        }
        
        // Filtro por preço mínimo
        if ($filters['min_price'] > 0) {
            $filtered = array_filter($filtered, function($product) use ($filters) {
                return $product['price'] >= $filters['min_price'];
            });
        }
        
        // Filtro por preço máximo
        if ($filters['max_price'] > 0) {
            $filtered = array_filter($filtered, function($product) use ($filters) {
                return $product['price'] <= $filters['max_price'];
            });
        }
        
        // Ordenação
        switch ($filters['sort']) {
            case 'price_asc':
                usort($filtered, fn($a, $b) => $a['price'] <=> $b['price']);
                break;
            case 'price_desc':
                usort($filtered, fn($a, $b) => $b['price'] <=> $a['price']);
                break;
            case 'rating':
                usort($filtered, fn($a, $b) => $b['rating'] <=> $a['rating']);
                break;
            case 'newest':
                usort($filtered, fn($a, $b) => $b['id'] <=> $a['id']);
                break;
            default: // name
                usort($filtered, fn($a, $b) => strcmp($a['name'], $b['name']));
        }
        
        return array_values($filtered);
    }
    
    /**
     * Obtém produto por ID
     * 
     * @param int $id ID do produto
     * @return array|null
     */
    private function getProductById(int $id): ?array {
        $products = $this->getAllProducts();
        
        foreach ($products as $product) {
            if ($product['id'] === $id) {
                // Adicionar informações extras para página do produto
                $product['gallery'] = [
                    $product['image'],
                    str_replace('.jpg', '-2.jpg', $product['image']),
                    str_replace('.jpg', '-3.jpg', $product['image'])
                ];
                $product['specifications'] = $this->getProductSpecifications($id);
                $product['shipping'] = $this->getShippingInfo();
                return $product;
            }
        }
        
        return null;
    }
    
    /**
     * Obtém produtos relacionados
     * 
     * @param string $category Categoria do produto
     * @param int $excludeId ID do produto a excluir
     * @return array
     */
    private function getRelatedProducts(string $category, int $excludeId): array {
        $products = $this->getAllProducts();
        
        $related = array_filter($products, function($product) use ($category, $excludeId) {
            return $product['category'] === $category && $product['id'] !== $excludeId;
        });
        
        return array_slice(array_values($related), 0, 4);
    }
    
    /**
     * Obtém avaliações do produto
     * 
     * @param int $productId ID do produto
     * @return array
     */
    private function getProductReviews(int $productId): array {
        return [
            [
                'id' => 1,
                'user_name' => 'João Silva',
                'rating' => 5,
                'comment' => 'Produto excelente! Superou minhas expectativas.',
                'date' => '2024-01-15'
            ],
            [
                'id' => 2,
                'user_name' => 'Maria Santos',
                'rating' => 4,
                'comment' => 'Muito bom, recomendo. Entrega rápida.',
                'date' => '2024-01-10'
            ]
        ];
    }
    
    /**
     * Busca produtos por query
     * 
     * @param string $query Termo de busca
     * @param int $limit Limite de resultados
     * @return array
     */
    private function searchProductsByQuery(string $query, int $limit): array {
        $products = $this->getAllProducts();
        $query = strtolower($query);
        
        $results = array_filter($products, function($product) use ($query) {
            return strpos(strtolower($product['name']), $query) !== false;
        });
        
        return array_slice(array_values($results), 0, $limit);
    }
    
    /**
     * Calcula total do carrinho
     * 
     * @param array $cart Itens do carrinho
     * @return array
     */
    private function calculateCartTotal(array $cart): array {
        $subtotal = 0;
        
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        $shipping = $subtotal > 200 ? 0 : 15.90;
        $total = $subtotal + $shipping;
        
        return [
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'total' => $total,
            'free_shipping' => $subtotal > 200
        ];
    }
    
    /**
     * Obtém especificações do produto
     * 
     * @param int $productId ID do produto
     * @return array
     */
    private function getProductSpecifications(int $productId): array {
        // Especificações simuladas baseadas no ID
        $specs = [
            1 => [ // Smartphone
                'Tela' => '6.7" AMOLED 120Hz',
                'Processador' => 'Snapdragon 8 Gen 2',
                'RAM' => '12GB',
                'Armazenamento' => '256GB',
                'Câmera' => '108MP + 12MP + 12MP',
                'Bateria' => '5000mAh'
            ],
            2 => [ // Headset
                'Tipo' => 'Over-ear',
                'Conectividade' => 'USB-A + 3.5mm',
                'Drivers' => '50mm',
                'Frequência' => '20Hz - 20kHz',
                'Microfone' => 'Removível com cancelamento de ruído',
                'Iluminação' => 'RGB 16.7 milhões de cores'
            ]
        ];
        
        return $specs[$productId] ?? [
            'Marca' => 'NeonShop',
            'Garantia' => '12 meses',
            'Origem' => 'Nacional'
        ];
    }
    
    /**
     * Obtém informações de frete
     * 
     * @return array
     */
    private function getShippingInfo(): array {
        return [
            'free_shipping_min' => 200.00,
            'standard_price' => 15.90,
            'express_price' => 29.90,
            'standard_days' => '5-7 dias úteis',
            'express_days' => '2-3 dias úteis'
        ];
    }
}