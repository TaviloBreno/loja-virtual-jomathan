<?php

namespace Controllers;

use Core\Request;
use Core\Response;

/**
 * Controlador Administrativo - NeonShop
 * 
 * Responsável por funcionalidades administrativas:
 * - Gerenciamento de produtos
 * - Controle de pedidos
 * - Relatórios e estatísticas
 * - Configurações do sistema
 * - Gerenciamento de usuários
 */
class AdminController extends BaseController {
    
    /**
     * Dashboard administrativo
     * 
     * @return Response
     */
    public function dashboard(): Response {
        $adminCheck = $this->requireAdmin();
        if ($adminCheck) {
            return $adminCheck;
        }
        
        $data = [
            'page_title' => 'Dashboard Administrativo - NeonShop',
            'stats' => $this->getDashboardStats(),
            'recent_orders' => $this->getRecentOrders(10),
            'low_stock_products' => $this->getLowStockProducts(),
            'revenue_chart' => $this->getRevenueChartData(),
            'top_products' => $this->getTopSellingProducts(5)
        ];
        
        return $this->render('admin/dashboard', $data);
    }
    
    /**
     * Lista produtos para administração
     * 
     * @return Response
     */
    public function products(): Response {
        $adminCheck = $this->requireAdmin();
        if ($adminCheck) {
            return $adminCheck;
        }
        
        $page = (int)$this->request->getQueryParam('page', 1);
        $search = $this->request->getQueryParam('search', '');
        $category = $this->request->getQueryParam('category', '');
        $status = $this->request->getQueryParam('status', '');
        
        $products = $this->getProductsForAdmin($page, $search, $category, $status);
        
        $data = [
            'page_title' => 'Gerenciar Produtos - NeonShop Admin',
            'products' => $products['items'],
            'pagination' => $products['pagination'],
            'categories' => $this->getAllCategories(),
            'filters' => [
                'search' => $search,
                'category' => $category,
                'status' => $status
            ]
        ];
        
        return $this->render('admin/products', $data);
    }
    
    /**
     * Formulário para criar/editar produto
     * 
     * @param int|null $id ID do produto para edição
     * @return Response
     */
    public function productForm(?int $id = null): Response {
        $adminCheck = $this->requireAdmin();
        if ($adminCheck) {
            return $adminCheck;
        }
        
        $product = null;
        if ($id) {
            $product = $this->getProductForEdit($id);
            if (!$product) {
                return $this->redirect('/admin/products?error=product_not_found');
            }
        }
        
        $data = [
            'page_title' => $id ? 'Editar Produto - NeonShop Admin' : 'Novo Produto - NeonShop Admin',
            'product' => $product,
            'categories' => $this->getAllCategories(),
            'brands' => $this->getAllBrands(),
            'is_edit' => (bool)$id
        ];
        
        return $this->render('admin/product-form', $data);
    }
    
    /**
     * Salva produto (criar/editar)
     * 
     * @param int|null $id ID do produto para edição
     * @return Response
     */
    public function saveProduct(?int $id = null): Response {
        $adminCheck = $this->requireAdmin();
        if ($adminCheck) {
            return $adminCheck;
        }
        
        if (!$this->verifyCsrf()) {
            return $this->error('Token CSRF inválido', 403);
        }
        
        $data = [
            'name' => $this->request->getBodyParam('name'),
            'description' => $this->request->getBodyParam('description'),
            'price' => $this->request->getBodyParam('price'),
            'original_price' => $this->request->getBodyParam('original_price'),
            'category' => $this->request->getBodyParam('category'),
            'brand' => $this->request->getBodyParam('brand'),
            'stock_quantity' => $this->request->getBodyParam('stock_quantity'),
            'status' => $this->request->getBodyParam('status', 'active'),
            'featured' => $this->request->getBodyParam('featured', false),
            'specifications' => $this->request->getBodyParam('specifications', []),
            'images' => $this->request->getBodyParam('images', [])
        ];
        
        // Validação
        $rules = [
            'name' => 'required|min:3|max:255',
            'description' => 'required|min:10',
            'price' => 'required|numeric|min:0.01',
            'category' => 'required',
            'stock_quantity' => 'required|integer|min:0'
        ];
        
        if (!$this->validate($data, $rules)) {
            if ($this->request->isAjax()) {
                return $this->error('Dados inválidos', 400, $this->errors);
            }
            return $this->redirect('/admin/products/form' . ($id ? '/' . $id : '') . '?error=validation', $this->errors);
        }
        
        // Salvar produto
        try {
            if ($id) {
                $product = $this->updateProduct($id, $data);
                $message = 'Produto atualizado com sucesso!';
            } else {
                $product = $this->createProduct($data);
                $message = 'Produto criado com sucesso!';
            }
            
            // Log da ação
            $this->logAction($id ? 'product_updated' : 'product_created', [
                'product_id' => $product['id'],
                'name' => $data['name']
            ]);
            
            if ($this->request->isAjax()) {
                return $this->success([
                    'message' => $message,
                    'product' => $product
                ]);
            }
            
            return $this->redirect('/admin/products?success=' . urlencode($message));
            
        } catch (\Exception $e) {
            if ($this->request->isAjax()) {
                return $this->error('Erro ao salvar produto: ' . $e->getMessage(), 500);
            }
            return $this->redirect('/admin/products/form' . ($id ? '/' . $id : '') . '?error=' . urlencode($e->getMessage()));
        }
    }
    
    /**
     * Remove produto
     * 
     * @param int $id ID do produto
     * @return Response
     */
    public function deleteProduct(int $id): Response {
        $adminCheck = $this->requireAdmin();
        if ($adminCheck) {
            return $adminCheck;
        }
        
        if (!$this->verifyCsrf()) {
            return $this->error('Token CSRF inválido', 403);
        }
        
        try {
            $product = $this->getProductForEdit($id);
            if (!$product) {
                return $this->error('Produto não encontrado', 404);
            }
            
            $this->removeProduct($id);
            
            // Log da ação
            $this->logAction('product_deleted', [
                'product_id' => $id,
                'name' => $product['name']
            ]);
            
            if ($this->request->isAjax()) {
                return $this->success([
                    'message' => 'Produto removido com sucesso!'
                ]);
            }
            
            return $this->redirect('/admin/products?success=Produto removido com sucesso!');
            
        } catch (\Exception $e) {
            if ($this->request->isAjax()) {
                return $this->error('Erro ao remover produto: ' . $e->getMessage(), 500);
            }
            return $this->redirect('/admin/products?error=' . urlencode($e->getMessage()));
        }
    }
    
    /**
     * Lista pedidos para administração
     * 
     * @return Response
     */
    public function orders(): Response {
        $adminCheck = $this->requireAdmin();
        if ($adminCheck) {
            return $adminCheck;
        }
        
        $page = (int)$this->request->getQueryParam('page', 1);
        $status = $this->request->getQueryParam('status', '');
        $search = $this->request->getQueryParam('search', '');
        
        $orders = $this->getOrdersForAdmin($page, $status, $search);
        
        $data = [
            'page_title' => 'Gerenciar Pedidos - NeonShop Admin',
            'orders' => $orders['items'],
            'pagination' => $orders['pagination'],
            'order_statuses' => $this->getOrderStatuses(),
            'filters' => [
                'status' => $status,
                'search' => $search
            ]
        ];
        
        return $this->render('admin/orders', $data);
    }
    
    /**
     * Detalhes do pedido
     * 
     * @param int $id ID do pedido
     * @return Response
     */
    public function orderDetails(int $id): Response {
        $adminCheck = $this->requireAdmin();
        if ($adminCheck) {
            return $adminCheck;
        }
        
        $order = $this->getOrderDetails($id);
        if (!$order) {
            return $this->redirect('/admin/orders?error=Pedido não encontrado');
        }
        
        $data = [
            'page_title' => 'Pedido #' . $order['id'] . ' - NeonShop Admin',
            'order' => $order,
            'order_statuses' => $this->getOrderStatuses(),
            'timeline' => $this->getOrderTimeline($id)
        ];
        
        return $this->render('admin/order-details', $data);
    }
    
    /**
     * Atualiza status do pedido
     * 
     * @param int $id ID do pedido
     * @return Response
     */
    public function updateOrderStatus(int $id): Response {
        $adminCheck = $this->requireAdmin();
        if ($adminCheck) {
            return $adminCheck;
        }
        
        if (!$this->verifyCsrf()) {
            return $this->error('Token CSRF inválido', 403);
        }
        
        $status = $this->request->getBodyParam('status');
        $notes = $this->request->getBodyParam('notes', '');
        
        if (!$status) {
            return $this->error('Status é obrigatório', 400);
        }
        
        try {
            $order = $this->updateOrderStatusData($id, $status, $notes);
            
            // Log da ação
            $this->logAction('order_status_updated', [
                'order_id' => $id,
                'new_status' => $status,
                'notes' => $notes
            ]);
            
            if ($this->request->isAjax()) {
                return $this->success([
                    'message' => 'Status do pedido atualizado com sucesso!',
                    'order' => $order
                ]);
            }
            
            return $this->redirect('/admin/orders/' . $id . '?success=Status atualizado com sucesso!');
            
        } catch (\Exception $e) {
            if ($this->request->isAjax()) {
                return $this->error('Erro ao atualizar status: ' . $e->getMessage(), 500);
            }
            return $this->redirect('/admin/orders/' . $id . '?error=' . urlencode($e->getMessage()));
        }
    }
    
    /**
     * Relatórios administrativos
     * 
     * @return Response
     */
    public function reports(): Response {
        $adminCheck = $this->requireAdmin();
        if ($adminCheck) {
            return $adminCheck;
        }
        
        $period = $this->request->getQueryParam('period', 'month');
        $type = $this->request->getQueryParam('type', 'sales');
        
        $data = [
            'page_title' => 'Relatórios - NeonShop Admin',
            'sales_report' => $this->getSalesReport($period),
            'products_report' => $this->getProductsReport($period),
            'customers_report' => $this->getCustomersReport($period),
            'period' => $period,
            'type' => $type
        ];
        
        return $this->render('admin/reports', $data);
    }
    
    /**
     * Configurações do sistema
     * 
     * @return Response
     */
    public function settings(): Response {
        $adminCheck = $this->requireAdmin();
        if ($adminCheck) {
            return $adminCheck;
        }
        
        $data = [
            'page_title' => 'Configurações - NeonShop Admin',
            'settings' => $this->getSystemSettings(),
            'payment_methods' => $this->getPaymentMethods(),
            'shipping_methods' => $this->getShippingMethods()
        ];
        
        return $this->render('admin/settings', $data);
    }
    
    /**
     * Salva configurações
     * 
     * @return Response
     */
    public function saveSettings(): Response {
        $adminCheck = $this->requireAdmin();
        if ($adminCheck) {
            return $adminCheck;
        }
        
        if (!$this->verifyCsrf()) {
            return $this->error('Token CSRF inválido', 403);
        }
        
        $settings = $this->request->getBodyParam('settings', []);
        
        try {
            $this->updateSystemSettings($settings);
            
            // Log da ação
            $this->logAction('settings_updated', [
                'settings_count' => count($settings)
            ]);
            
            if ($this->request->isAjax()) {
                return $this->success([
                    'message' => 'Configurações salvas com sucesso!'
                ]);
            }
            
            return $this->redirect('/admin/settings?success=Configurações salvas com sucesso!');
            
        } catch (\Exception $e) {
            if ($this->request->isAjax()) {
                return $this->error('Erro ao salvar configurações: ' . $e->getMessage(), 500);
            }
            return $this->redirect('/admin/settings?error=' . urlencode($e->getMessage()));
        }
    }
    
    // ========== MÉTODOS PRIVADOS ==========
    
    /**
     * Obtém estatísticas do dashboard
     * 
     * @return array
     */
    private function getDashboardStats(): array {
        return [
            'total_products' => 150,
            'total_orders' => 1250,
            'total_customers' => 890,
            'total_revenue' => 125000.50,
            'pending_orders' => 8,
            'low_stock_items' => 5,
            'monthly_revenue' => 15000.75,
            'monthly_orders' => 120
        ];
    }
    
    /**
     * Obtém pedidos recentes
     * 
     * @param int $limit Limite de pedidos
     * @return array
     */
    private function getRecentOrders(int $limit): array {
        return [
            [
                'id' => 1001,
                'customer_name' => 'João Silva',
                'total' => 299.99,
                'status' => 'pending',
                'created_at' => '2024-01-15 14:30:00'
            ],
            [
                'id' => 1002,
                'customer_name' => 'Maria Santos',
                'total' => 599.99,
                'status' => 'processing',
                'created_at' => '2024-01-15 13:15:00'
            ]
        ];
    }
    
    /**
     * Obtém produtos com estoque baixo
     * 
     * @return array
     */
    private function getLowStockProducts(): array {
        return [
            [
                'id' => 1,
                'name' => 'Smartphone Neon X1',
                'stock_quantity' => 3,
                'min_stock' => 10
            ],
            [
                'id' => 2,
                'name' => 'Headset Gamer RGB Pro',
                'stock_quantity' => 5,
                'min_stock' => 15
            ]
        ];
    }
    
    /**
     * Obtém dados para gráfico de receita
     * 
     * @return array
     */
    private function getRevenueChartData(): array {
        return [
            'labels' => ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
            'data' => [12000, 15000, 18000, 14000, 20000, 22000]
        ];
    }
    
    /**
     * Obtém produtos mais vendidos
     * 
     * @param int $limit Limite
     * @return array
     */
    private function getTopSellingProducts(int $limit): array {
        return [
            [
                'id' => 1,
                'name' => 'Smartphone Neon X1',
                'sales_count' => 156,
                'revenue' => 202794.44
            ],
            [
                'id' => 2,
                'name' => 'Headset Gamer RGB Pro',
                'sales_count' => 89,
                'revenue' => 26699.11
            ]
        ];
    }
    
    /**
     * Obtém produtos para administração
     * 
     * @param int $page Página
     * @param string $search Busca
     * @param string $category Categoria
     * @param string $status Status
     * @return array
     */
    private function getProductsForAdmin(int $page, string $search, string $category, string $status): array {
        // Simulação de dados
        $products = [
            [
                'id' => 1,
                'name' => 'Smartphone Neon X1',
                'category' => 'smartphones',
                'price' => 1299.99,
                'stock_quantity' => 50,
                'status' => 'active',
                'created_at' => '2024-01-10'
            ],
            [
                'id' => 2,
                'name' => 'Headset Gamer RGB Pro',
                'category' => 'gaming',
                'price' => 299.99,
                'stock_quantity' => 30,
                'status' => 'active',
                'created_at' => '2024-01-12'
            ]
        ];
        
        return [
            'items' => $products,
            'pagination' => [
                'current_page' => $page,
                'per_page' => 20,
                'total' => count($products),
                'total_pages' => 1
            ]
        ];
    }
    
    /**
     * Obtém produto para edição
     * 
     * @param int $id ID do produto
     * @return array|null
     */
    private function getProductForEdit(int $id): ?array {
        // Simulação
        if ($id === 1) {
            return [
                'id' => 1,
                'name' => 'Smartphone Neon X1',
                'description' => 'Smartphone com tecnologia de ponta',
                'price' => 1299.99,
                'original_price' => 1599.99,
                'category' => 'smartphones',
                'brand' => 'Neon',
                'stock_quantity' => 50,
                'status' => 'active',
                'featured' => true,
                'specifications' => [
                    'Tela' => '6.5 polegadas',
                    'Memória' => '128GB',
                    'RAM' => '8GB'
                ],
                'images' => ['/assets/images/products/smartphone-x1.jpg']
            ];
        }
        
        return null;
    }
    
    /**
     * Obtém todas as marcas
     * 
     * @return array
     */
    private function getAllBrands(): array {
        return [
            ['id' => 'neon', 'name' => 'Neon'],
            ['id' => 'tech', 'name' => 'Tech'],
            ['id' => 'gaming', 'name' => 'Gaming Pro']
        ];
    }
    
    /**
     * Cria novo produto
     * 
     * @param array $data Dados do produto
     * @return array
     */
    private function createProduct(array $data): array {
        $product = array_merge($data, [
            'id' => rand(1000, 9999),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        // Em produção, salvar no banco
        return $product;
    }
    
    /**
     * Atualiza produto existente
     * 
     * @param int $id ID do produto
     * @param array $data Dados do produto
     * @return array
     */
    private function updateProduct(int $id, array $data): array {
        $product = array_merge($data, [
            'id' => $id,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        // Em produção, atualizar no banco
        return $product;
    }
    
    /**
     * Remove produto
     * 
     * @param int $id ID do produto
     */
    private function removeProduct(int $id): void {
        // Em produção, remover do banco
    }
    
    /**
     * Obtém pedidos para administração
     * 
     * @param int $page Página
     * @param string $status Status
     * @param string $search Busca
     * @return array
     */
    private function getOrdersForAdmin(int $page, string $status, string $search): array {
        $orders = [
            [
                'id' => 1001,
                'customer_name' => 'João Silva',
                'customer_email' => 'joao@email.com',
                'total' => 299.99,
                'status' => 'pending',
                'created_at' => '2024-01-15 14:30:00'
            ],
            [
                'id' => 1002,
                'customer_name' => 'Maria Santos',
                'customer_email' => 'maria@email.com',
                'total' => 599.99,
                'status' => 'processing',
                'created_at' => '2024-01-15 13:15:00'
            ]
        ];
        
        return [
            'items' => $orders,
            'pagination' => [
                'current_page' => $page,
                'per_page' => 20,
                'total' => count($orders),
                'total_pages' => 1
            ]
        ];
    }
    
    /**
     * Obtém status de pedidos disponíveis
     * 
     * @return array
     */
    private function getOrderStatuses(): array {
        return [
            'pending' => 'Pendente',
            'processing' => 'Processando',
            'shipped' => 'Enviado',
            'delivered' => 'Entregue',
            'cancelled' => 'Cancelado'
        ];
    }
    
    /**
     * Obtém detalhes do pedido
     * 
     * @param int $id ID do pedido
     * @return array|null
     */
    private function getOrderDetails(int $id): ?array {
        if ($id === 1001) {
            return [
                'id' => 1001,
                'customer_name' => 'João Silva',
                'customer_email' => 'joao@email.com',
                'customer_phone' => '(11) 99999-9999',
                'total' => 299.99,
                'subtotal' => 279.99,
                'shipping' => 20.00,
                'status' => 'pending',
                'created_at' => '2024-01-15 14:30:00',
                'items' => [
                    [
                        'product_name' => 'Headset Gamer RGB Pro',
                        'quantity' => 1,
                        'price' => 279.99
                    ]
                ],
                'shipping_address' => [
                    'street' => 'Rua das Flores, 123',
                    'city' => 'São Paulo',
                    'state' => 'SP',
                    'zip_code' => '01234-567'
                ]
            ];
        }
        
        return null;
    }
    
    /**
     * Obtém timeline do pedido
     * 
     * @param int $id ID do pedido
     * @return array
     */
    private function getOrderTimeline(int $id): array {
        return [
            [
                'status' => 'pending',
                'description' => 'Pedido criado',
                'timestamp' => '2024-01-15 14:30:00'
            ]
        ];
    }
    
    /**
     * Atualiza status do pedido
     * 
     * @param int $id ID do pedido
     * @param string $status Novo status
     * @param string $notes Observações
     * @return array
     */
    private function updateOrderStatusData(int $id, string $status, string $notes): array {
        // Em produção, atualizar no banco
        return [
            'id' => $id,
            'status' => $status,
            'notes' => $notes,
            'updated_at' => date('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Obtém relatório de vendas
     * 
     * @param string $period Período
     * @return array
     */
    private function getSalesReport(string $period): array {
        return [
            'total_sales' => 125000.50,
            'total_orders' => 1250,
            'average_order' => 100.00,
            'chart_data' => [
                'labels' => ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
                'data' => [12000, 15000, 18000, 14000, 20000, 22000]
            ]
        ];
    }
    
    /**
     * Obtém relatório de produtos
     * 
     * @param string $period Período
     * @return array
     */
    private function getProductsReport(string $period): array {
        return [
            'total_products' => 150,
            'active_products' => 145,
            'out_of_stock' => 5,
            'top_selling' => [
                ['name' => 'Smartphone Neon X1', 'sales' => 156],
                ['name' => 'Headset Gamer RGB Pro', 'sales' => 89]
            ]
        ];
    }
    
    /**
     * Obtém relatório de clientes
     * 
     * @param string $period Período
     * @return array
     */
    private function getCustomersReport(string $period): array {
        return [
            'total_customers' => 890,
            'new_customers' => 45,
            'returning_customers' => 234,
            'newsletter_subscribers' => 2340
        ];
    }
    
    /**
     * Obtém configurações do sistema
     * 
     * @return array
     */
    private function getSystemSettings(): array {
        return [
            'site_name' => 'NeonShop',
            'site_description' => 'Loja de tecnologia com design futurista',
            'contact_email' => 'contato@neonshop.com',
            'contact_phone' => '(11) 99999-9999',
            'free_shipping_min' => 150.00,
            'tax_rate' => 0.18,
            'currency' => 'BRL',
            'timezone' => 'America/Sao_Paulo'
        ];
    }
    
    /**
     * Obtém métodos de pagamento
     * 
     * @return array
     */
    private function getPaymentMethods(): array {
        return [
            'credit_card' => ['name' => 'Cartão de Crédito', 'enabled' => true],
            'debit_card' => ['name' => 'Cartão de Débito', 'enabled' => true],
            'pix' => ['name' => 'PIX', 'enabled' => true],
            'boleto' => ['name' => 'Boleto', 'enabled' => false]
        ];
    }
    
    /**
     * Obtém métodos de entrega
     * 
     * @return array
     */
    private function getShippingMethods(): array {
        return [
            'standard' => ['name' => 'Entrega Padrão', 'enabled' => true, 'price' => 15.90],
            'express' => ['name' => 'Entrega Expressa', 'enabled' => true, 'price' => 29.90],
            'pickup' => ['name' => 'Retirada na Loja', 'enabled' => true, 'price' => 0.00]
        ];
    }
    
    /**
     * Atualiza configurações do sistema
     * 
     * @param array $settings Configurações
     */
    private function updateSystemSettings(array $settings): void {
        // Em produção, salvar no banco ou arquivo de configuração
    }
}