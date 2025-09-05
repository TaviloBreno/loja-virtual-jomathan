<?php

namespace Controllers;

use Core\Request;
use Core\Response;

/**
 * Controlador de API - NeonShop
 * 
 * Responsável por fornecer endpoints de API REST para:
 * - Busca de produtos
 * - Consulta de CEP
 * - Dados de produtos
 * - Informações do sistema
 * - Analytics e métricas
 */
class ApiController extends BaseController {
    
    /**
     * Busca produtos via API
     * 
     * @return Response
     */
    public function searchProducts(): Response {
        $query = $this->request->getQueryParam('q', '');
        $category = $this->request->getQueryParam('category', '');
        $limit = (int)$this->request->getQueryParam('limit', 10);
        $page = (int)$this->request->getQueryParam('page', 1);
        
        if (strlen($query) < 2 && empty($category)) {
            return $this->error('Query deve ter pelo menos 2 caracteres ou categoria deve ser especificada', 400);
        }
        
        $products = $this->getProductsForApi($query, $category, $limit, $page);
        
        return $this->success([
            'products' => $products['items'],
            'pagination' => $products['pagination'],
            'filters' => [
                'query' => $query,
                'category' => $category
            ]
        ]);
    }
    
    /**
     * Obtém detalhes de um produto
     * 
     * @param int $id ID do produto
     * @return Response
     */
    public function getProduct(int $id): Response {
        $product = $this->getProductById($id);
        
        if (!$product) {
            return $this->error('Produto não encontrado', 404);
        }
        
        // Adicionar informações extras para API
        $product['related_products'] = $this->getRelatedProducts($product['category'], $id, 4);
        $product['reviews'] = $this->getProductReviews($id);
        $product['availability'] = $this->getProductAvailability($id);
        
        return $this->success($product);
    }
    
    /**
     * Consulta CEP via API
     * 
     * @return Response
     */
    public function consultCep(): Response {
        $cep = $this->request->getQueryParam('cep');
        
        if (!$cep) {
            return $this->error('CEP é obrigatório', 400);
        }
        
        // Limpar CEP
        $cep = preg_replace('/[^0-9]/', '', $cep);
        
        if (strlen($cep) !== 8) {
            return $this->error('CEP deve ter 8 dígitos', 400);
        }
        
        // Consultar CEP (simulação)
        $addressData = $this->getCepData($cep);
        
        if (!$addressData) {
            return $this->error('CEP não encontrado', 404);
        }
        
        // Calcular frete para o CEP
        $shippingOptions = $this->calculateShippingForCep($cep);
        
        return $this->success([
            'cep' => $cep,
            'address' => $addressData,
            'shipping_options' => $shippingOptions
        ]);
    }
    
    /**
     * Obtém categorias disponíveis
     * 
     * @return Response
     */
    public function getCategories(): Response {
        $categories = $this->getAllCategories();
        
        return $this->success([
            'categories' => $categories,
            'total' => count($categories)
        ]);
    }
    
    /**
     * Valida cupom de desconto
     * 
     * @return Response
     */
    public function validateCoupon(): Response {
        $code = $this->request->getQueryParam('code');
        
        if (!$code) {
            return $this->error('Código do cupom é obrigatório', 400);
        }
        
        $coupon = $this->getCouponData($code);
        
        if (!$coupon) {
            return $this->error('Cupom inválido ou expirado', 404);
        }
        
        return $this->success([
            'coupon' => $coupon,
            'valid' => true
        ]);
    }
    
    /**
     * Processa newsletter
     * 
     * @return Response
     */
    public function subscribeNewsletter(): Response {
        $email = $this->request->getBodyParam('email');
        $name = $this->request->getBodyParam('name', '');
        
        if (!$this->validate(['email' => $email], ['email' => 'required|email'])) {
            return $this->error('Email inválido', 400, $this->errors);
        }
        
        // Verificar se email já está cadastrado
        if ($this->isEmailSubscribed($email)) {
            return $this->error('Email já cadastrado na newsletter', 409);
        }
        
        // Cadastrar na newsletter
        $subscription = $this->subscribeEmail($email, $name);
        
        // Log da ação
        $this->logAction('newsletter_subscription', [
            'email' => $email,
            'name' => $name
        ]);
        
        return $this->success([
            'message' => 'Email cadastrado com sucesso na newsletter',
            'subscription' => $subscription
        ]);
    }
    
    /**
     * Processa contato
     * 
     * @return Response
     */
    public function submitContact(): Response {
        $data = [
            'name' => $this->request->getBodyParam('name'),
            'email' => $this->request->getBodyParam('email'),
            'phone' => $this->request->getBodyParam('phone'),
            'subject' => $this->request->getBodyParam('subject'),
            'message' => $this->request->getBodyParam('message')
        ];
        
        if (!$this->validate($data, [
            'name' => 'required|min:2',
            'email' => 'required|email',
            'subject' => 'required|min:5',
            'message' => 'required|min:10'
        ])) {
            return $this->error('Dados inválidos', 400, $this->errors);
        }
        
        // Salvar mensagem de contato
        $contact = $this->saveContactMessage($data);
        
        // Log da ação
        $this->logAction('contact_submitted', [
            'email' => $data['email'],
            'subject' => $data['subject']
        ]);
        
        return $this->success([
            'message' => 'Mensagem enviada com sucesso. Retornaremos em breve!',
            'contact_id' => $contact['id']
        ]);
    }
    
    /**
     * Obtém estatísticas do sistema
     * 
     * @return Response
     */
    public function getStats(): Response {
        // Verificar se é administrador
        $adminCheck = $this->requireAdmin();
        if ($adminCheck) {
            return $adminCheck;
        }
        
        $stats = [
            'products' => [
                'total' => $this->getTotalProducts(),
                'categories' => count($this->getAllCategories()),
                'featured' => $this->getFeaturedProductsCount()
            ],
            'orders' => [
                'total' => $this->getTotalOrders(),
                'today' => $this->getTodayOrders(),
                'pending' => $this->getPendingOrders()
            ],
            'users' => [
                'newsletter' => $this->getNewsletterSubscribers(),
                'contacts' => $this->getTotalContacts()
            ],
            'revenue' => [
                'total' => $this->getTotalRevenue(),
                'monthly' => $this->getMonthlyRevenue(),
                'average_order' => $this->getAverageOrderValue()
            ]
        ];
        
        return $this->success($stats);
    }
    
    /**
     * Obtém produtos mais vendidos
     * 
     * @return Response
     */
    public function getBestSellers(): Response {
        $limit = (int)$this->request->getQueryParam('limit', 10);
        
        $bestSellers = $this->getBestSellingProducts($limit);
        
        return $this->success([
            'products' => $bestSellers,
            'total' => count($bestSellers)
        ]);
    }
    
    /**
     * Obtém produtos em promoção
     * 
     * @return Response
     */
    public function getPromotions(): Response {
        $promotions = $this->getPromotionalProducts();
        
        return $this->success([
            'promotions' => $promotions,
            'total' => count($promotions)
        ]);
    }
    
    /**
     * Registra evento de analytics
     * 
     * @return Response
     */
    public function trackEvent(): Response {
        $event = $this->request->getBodyParam('event');
        $data = $this->request->getBodyParam('data', []);
        
        if (!$event) {
            return $this->error('Evento é obrigatório', 400);
        }
        
        // Registrar evento
        $this->recordAnalyticsEvent($event, $data);
        
        return $this->success([
            'message' => 'Evento registrado com sucesso'
        ]);
    }
    
    /**
     * Obtém informações do sistema
     * 
     * @return Response
     */
    public function getSystemInfo(): Response {
        $info = [
            'name' => 'NeonShop API',
            'version' => '1.0.0',
            'environment' => 'development',
            'timestamp' => time(),
            'timezone' => date_default_timezone_get(),
            'php_version' => PHP_VERSION,
            'memory_usage' => memory_get_usage(true),
            'uptime' => $this->getSystemUptime()
        ];
        
        return $this->success($info);
    }
    
    // ========== MÉTODOS PRIVADOS ==========
    
    /**
     * Obtém produtos para API com filtros
     * 
     * @param string $query Termo de busca
     * @param string $category Categoria
     * @param int $limit Limite por página
     * @param int $page Página atual
     * @return array
     */
    private function getProductsForApi(string $query, string $category, int $limit, int $page): array {
        $allProducts = $this->getAllProductsData();
        
        // Filtrar por busca
        if (!empty($query)) {
            $query = strtolower($query);
            $allProducts = array_filter($allProducts, function($product) use ($query) {
                return strpos(strtolower($product['name']), $query) !== false ||
                       strpos(strtolower($product['description'] ?? ''), $query) !== false;
            });
        }
        
        // Filtrar por categoria
        if (!empty($category)) {
            $allProducts = array_filter($allProducts, function($product) use ($category) {
                return $product['category'] === $category;
            });
        }
        
        // Paginação
        $total = count($allProducts);
        $offset = ($page - 1) * $limit;
        $products = array_slice(array_values($allProducts), $offset, $limit);
        
        return [
            'items' => $products,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $limit,
                'total' => $total,
                'total_pages' => ceil($total / $limit)
            ]
        ];
    }
    
    /**
     * Obtém dados de um produto por ID
     * 
     * @param int $id ID do produto
     * @return array|null
     */
    private function getProductById(int $id): ?array {
        $products = $this->getAllProductsData();
        
        foreach ($products as $product) {
            if ($product['id'] === $id) {
                return $product;
            }
        }
        
        return null;
    }
    
    /**
     * Obtém todos os produtos (simulação)
     * 
     * @return array
     */
    private function getAllProductsData(): array {
        return [
            [
                'id' => 1,
                'name' => 'Smartphone Neon X1',
                'description' => 'Smartphone com tecnologia de ponta e design futurista',
                'price' => 1299.99,
                'original_price' => 1599.99,
                'category' => 'smartphones',
                'image' => '/assets/images/products/smartphone-x1.jpg',
                'rating' => 4.8,
                'reviews_count' => 156,
                'in_stock' => true,
                'stock_quantity' => 50
            ],
            [
                'id' => 2,
                'name' => 'Headset Gamer RGB Pro',
                'description' => 'Headset gamer com som surround e iluminação RGB',
                'price' => 299.99,
                'original_price' => 399.99,
                'category' => 'gaming',
                'image' => '/assets/images/products/headset-rgb.jpg',
                'rating' => 4.6,
                'reviews_count' => 89,
                'in_stock' => true,
                'stock_quantity' => 30
            ],
            [
                'id' => 3,
                'name' => 'Smartwatch Neon Fit',
                'description' => 'Smartwatch com monitoramento de saúde e GPS',
                'price' => 599.99,
                'original_price' => 799.99,
                'category' => 'wearables',
                'image' => '/assets/images/products/smartwatch-fit.jpg',
                'rating' => 4.7,
                'reviews_count' => 203,
                'in_stock' => true,
                'stock_quantity' => 25
            ]
        ];
    }
    
    /**
     * Obtém dados de CEP (simulação)
     * 
     * @param string $cep CEP para consulta
     * @return array|null
     */
    private function getCepData(string $cep): ?array {
        // Simulação de dados de CEP
        $cepData = [
            '01310100' => [
                'cep' => '01310-100',
                'street' => 'Avenida Paulista',
                'neighborhood' => 'Bela Vista',
                'city' => 'São Paulo',
                'state' => 'SP',
                'region' => 'Sudeste'
            ],
            '20040020' => [
                'cep' => '20040-020',
                'street' => 'Rua da Assembleia',
                'neighborhood' => 'Centro',
                'city' => 'Rio de Janeiro',
                'state' => 'RJ',
                'region' => 'Sudeste'
            ]
        ];
        
        return $cepData[$cep] ?? [
            'cep' => substr($cep, 0, 5) . '-' . substr($cep, 5),
            'street' => 'Rua Exemplo',
            'neighborhood' => 'Centro',
            'city' => 'Cidade Exemplo',
            'state' => 'SP',
            'region' => 'Sudeste'
        ];
    }
    
    /**
     * Calcula frete para CEP
     * 
     * @param string $cep CEP de destino
     * @return array
     */
    private function calculateShippingForCep(string $cep): array {
        $region = $this->getRegionByCep($cep);
        
        return [
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
     * Obtém todas as categorias
     * 
     * @return array
     */
    private function getAllCategories(): array {
        return [
            ['id' => 'smartphones', 'name' => 'Smartphones', 'slug' => 'smartphones'],
            ['id' => 'tablets', 'name' => 'Tablets', 'slug' => 'tablets'],
            ['id' => 'laptops', 'name' => 'Laptops', 'slug' => 'laptops'],
            ['id' => 'gaming', 'name' => 'Gaming', 'slug' => 'gaming'],
            ['id' => 'audio', 'name' => 'Áudio', 'slug' => 'audio'],
            ['id' => 'wearables', 'name' => 'Wearables', 'slug' => 'wearables']
        ];
    }
    
    /**
     * Obtém dados de cupom
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
                'description' => '10% de desconto em compras acima de R$ 100'
            ],
            'FRETE20' => [
                'code' => 'FRETE20',
                'type' => 'fixed',
                'value' => 20,
                'min_amount' => 150,
                'description' => 'R$ 20 de desconto em compras acima de R$ 150'
            ]
        ];
        
        return $coupons[strtoupper($code)] ?? null;
    }
    
    /**
     * Verifica se email já está inscrito
     * 
     * @param string $email Email para verificar
     * @return bool
     */
    private function isEmailSubscribed(string $email): bool {
        // Simulação - em produção, verificar no banco
        return false;
    }
    
    /**
     * Inscreve email na newsletter
     * 
     * @param string $email Email
     * @param string $name Nome
     * @return array
     */
    private function subscribeEmail(string $email, string $name): array {
        return [
            'id' => uniqid(),
            'email' => $email,
            'name' => $name,
            'subscribed_at' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Salva mensagem de contato
     * 
     * @param array $data Dados da mensagem
     * @return array
     */
    private function saveContactMessage(array $data): array {
        $contact = array_merge($data, [
            'id' => uniqid(),
            'created_at' => date('Y-m-d H:i:s'),
            'status' => 'pending'
        ]);
        
        // Em produção, salvar no banco
        return $contact;
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
        $products = $this->getAllProductsData();
        
        $related = array_filter($products, function($product) use ($category, $excludeId) {
            return $product['category'] === $category && $product['id'] !== $excludeId;
        });
        
        return array_slice(array_values($related), 0, $limit);
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
                'rating' => 5,
                'comment' => 'Produto excelente!',
                'author' => 'João S.',
                'date' => '2024-01-15'
            ]
        ];
    }
    
    /**
     * Obtém disponibilidade do produto
     * 
     * @param int $productId ID do produto
     * @return array
     */
    private function getProductAvailability(int $productId): array {
        return [
            'in_stock' => true,
            'quantity' => 50,
            'estimated_delivery' => '3-5 dias úteis'
        ];
    }
    
    /**
     * Registra evento de analytics
     * 
     * @param string $event Nome do evento
     * @param array $data Dados do evento
     */
    private function recordAnalyticsEvent(string $event, array $data): void {
        $eventData = [
            'event' => $event,
            'data' => $data,
            'timestamp' => time(),
            'ip' => $this->request->getClientIp(),
            'user_agent' => $this->request->getHeader('user-agent')
        ];
        
        // Em produção, salvar em sistema de analytics
        error_log('Analytics Event: ' . json_encode($eventData));
    }
    
    /**
     * Obtém uptime do sistema
     * 
     * @return string
     */
    private function getSystemUptime(): string {
        return '24h 30m'; // Simulação
    }
    
    // Métodos simulados para estatísticas
    private function getTotalProducts(): int { return 150; }
    private function getFeaturedProductsCount(): int { return 12; }
    private function getTotalOrders(): int { return 1250; }
    private function getTodayOrders(): int { return 15; }
    private function getPendingOrders(): int { return 8; }
    private function getNewsletterSubscribers(): int { return 2340; }
    private function getTotalContacts(): int { return 89; }
    private function getTotalRevenue(): float { return 125000.50; }
    private function getMonthlyRevenue(): float { return 15000.75; }
    private function getAverageOrderValue(): float { return 299.99; }
    
    private function getBestSellingProducts(int $limit): array {
        return array_slice($this->getAllProductsData(), 0, $limit);
    }
    
    private function getPromotionalProducts(): array {
        return array_filter($this->getAllProductsData(), function($product) {
            return isset($product['original_price']) && $product['original_price'] > $product['price'];
        });
    }
}