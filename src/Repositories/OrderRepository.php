<?php

namespace Repositories;

use Entities\Order;
use DTOs\OrderDTO;

/**
 * Repositório de Pedidos - NeonShop
 * 
 * Responsável pelo acesso aos dados dos pedidos:
 * - Operações CRUD (Create, Read, Update, Delete)
 * - Consultas com filtros e ordenação
 * - Histórico de status
 * - Relatórios e estatísticas
 */
class OrderRepository {
    
    private array $orders;
    private array $orderHistory;
    private string $ordersFile;
    private string $historyFile;
    
    public function __construct() {
        $this->ordersFile = __DIR__ . '/../../data/orders.json';
        $this->historyFile = __DIR__ . '/../../data/order_history.json';
        $this->loadOrders();
        $this->loadOrderHistory();
    }
    
    /**
     * Busca pedido por ID
     * 
     * @param int $id ID do pedido
     * @return array|null
     */
    public function findById(int $id): ?array {
        $order = $this->orders[$id] ?? null;
        
        if ($order) {
            // Incluir histórico do pedido
            $order['history'] = $this->getOrderHistory($id);
        }
        
        return $order;
    }
    
    /**
     * Busca pedidos com filtros
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
        int $limit = 20,
        string $orderBy = 'created_at',
        string $orderDirection = 'DESC'
    ): array {
        $filteredOrders = $this->applyFilters($this->orders, $filters);
        $sortedOrders = $this->sortOrders($filteredOrders, $orderBy, $orderDirection);
        
        // Paginação
        $total = count($sortedOrders);
        $offset = ($page - 1) * $limit;
        $paginatedOrders = array_slice($sortedOrders, $offset, $limit, true);
        
        return [
            'items' => array_values($paginatedOrders),
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
     * Busca pedidos por cliente
     * 
     * @param string $customerEmail Email do cliente
     * @param int $limit Limite de resultados
     * @return array
     */
    public function findByCustomer(string $customerEmail, int $limit = 10): array {
        $customerOrders = array_filter($this->orders, function($order) use ($customerEmail) {
            return strcasecmp($order['customer_email'], $customerEmail) === 0;
        });
        
        // Ordenar por data (mais recentes primeiro)
        usort($customerOrders, function($a, $b) {
            return strtotime($b['created_at']) <=> strtotime($a['created_at']);
        });
        
        return array_slice($customerOrders, 0, $limit);
    }
    
    /**
     * Cria novo pedido
     * 
     * @param array $orderData Dados do pedido
     * @param array $items Itens do pedido
     * @return array
     * @throws \InvalidArgumentException
     */
    public function create(array $orderData, array $items): array {
        $this->validateOrderData($orderData);
        
        if (empty($items)) {
            throw new \InvalidArgumentException('Pedido deve ter pelo menos um item');
        }
        
        // Gerar novo ID
        $newId = $this->getNextId();
        
        // Preparar dados do pedido
        $order = array_merge($orderData, [
            'id' => $newId,
            'order_number' => $this->generateOrderNumber(),
            'items' => $items,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        // Adicionar pedido
        $this->orders[$newId] = $order;
        
        // Salvar dados
        $this->saveOrders();
        
        return $order;
    }
    
    /**
     * Atualiza pedido
     * 
     * @param int $id ID do pedido
     * @param array $data Dados para atualização
     * @return bool
     */
    public function update(int $id, array $data): bool {
        if (!isset($this->orders[$id])) {
            return false;
        }
        
        // Atualizar dados
        $this->orders[$id] = array_merge($this->orders[$id], $data, [
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        // Salvar dados
        $this->saveOrders();
        
        return true;
    }
    
    /**
     * Atualiza status do pedido
     * 
     * @param int $id ID do pedido
     * @param string $status Novo status
     * @return bool
     */
    public function updateStatus(int $id, string $status): bool {
        if (!isset($this->orders[$id])) {
            return false;
        }
        
        $this->orders[$id]['status'] = $status;
        $this->orders[$id]['updated_at'] = date('Y-m-d H:i:s');
        
        // Salvar dados
        $this->saveOrders();
        
        return true;
    }
    
    /**
     * Remove pedido
     * 
     * @param int $id ID do pedido
     * @return bool
     */
    public function delete(int $id): bool {
        if (!isset($this->orders[$id])) {
            return false;
        }
        
        unset($this->orders[$id]);
        
        // Remover histórico relacionado
        $this->orderHistory = array_filter($this->orderHistory, function($history) use ($id) {
            return $history['order_id'] !== $id;
        });
        
        // Salvar dados
        $this->saveOrders();
        $this->saveOrderHistory();
        
        return true;
    }
    
    /**
     * Adiciona entrada no histórico do pedido
     * 
     * @param int $orderId ID do pedido
     * @param string $status Status
     * @param string $notes Observações
     * @return bool
     */
    public function addHistory(int $orderId, string $status, string $notes = ''): bool {
        if (!isset($this->orders[$orderId])) {
            return false;
        }
        
        $historyEntry = [
            'id' => $this->getNextHistoryId(),
            'order_id' => $orderId,
            'status' => $status,
            'notes' => $notes,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => 'system' // Em produção, usar ID do usuário logado
        ];
        
        $this->orderHistory[] = $historyEntry;
        
        // Salvar histórico
        $this->saveOrderHistory();
        
        return true;
    }
    
    /**
     * Obtém histórico do pedido
     * 
     * @param int $orderId ID do pedido
     * @return array
     */
    public function getOrderHistory(int $orderId): array {
        $history = array_filter($this->orderHistory, function($entry) use ($orderId) {
            return $entry['order_id'] === $orderId;
        });
        
        // Ordenar por data (mais recente primeiro)
        usort($history, function($a, $b) {
            return strtotime($b['created_at']) <=> strtotime($a['created_at']);
        });
        
        return array_values($history);
    }
    
    /**
     * Conta pedidos por período
     * 
     * @param string $period Período (day, week, month, year)
     * @return int
     */
    public function countByPeriod(string $period = 'month'): int {
        $startDate = $this->getPeriodStartDate($period);
        
        return count(array_filter($this->orders, function($order) use ($startDate) {
            return strtotime($order['created_at']) >= strtotime($startDate);
        }));
    }
    
    /**
     * Soma receita por período
     * 
     * @param string $period Período (day, week, month, year)
     * @return float
     */
    public function sumRevenueByPeriod(string $period = 'month'): float {
        $startDate = $this->getPeriodStartDate($period);
        
        $orders = array_filter($this->orders, function($order) use ($startDate) {
            return strtotime($order['created_at']) >= strtotime($startDate) &&
                   in_array($order['status'], ['processing', 'shipped', 'delivered']);
        });
        
        return array_sum(array_column($orders, 'total'));
    }
    
    /**
     * Calcula valor médio do pedido por período
     * 
     * @param string $period Período (day, week, month, year)
     * @return float
     */
    public function averageOrderValueByPeriod(string $period = 'month'): float {
        $startDate = $this->getPeriodStartDate($period);
        
        $orders = array_filter($this->orders, function($order) use ($startDate) {
            return strtotime($order['created_at']) >= strtotime($startDate) &&
                   in_array($order['status'], ['processing', 'shipped', 'delivered']);
        });
        
        if (empty($orders)) {
            return 0;
        }
        
        $totalRevenue = array_sum(array_column($orders, 'total'));
        return $totalRevenue / count($orders);
    }
    
    /**
     * Conta pedidos por status em um período
     * 
     * @param string $period Período (day, week, month, year)
     * @return array
     */
    public function countByStatus(string $period = 'month'): array {
        $startDate = $this->getPeriodStartDate($period);
        
        $orders = array_filter($this->orders, function($order) use ($startDate) {
            return strtotime($order['created_at']) >= strtotime($startDate);
        });
        
        $statusCounts = [];
        foreach ($orders as $order) {
            $status = $order['status'];
            $statusCounts[$status] = ($statusCounts[$status] ?? 0) + 1;
        }
        
        return $statusCounts;
    }
    
    /**
     * Obtém produtos mais vendidos por período
     * 
     * @param string $period Período (day, week, month, year)
     * @param int $limit Limite de resultados
     * @return array
     */
    public function getTopProductsByPeriod(string $period = 'month', int $limit = 10): array {
        $startDate = $this->getPeriodStartDate($period);
        
        $orders = array_filter($this->orders, function($order) use ($startDate) {
            return strtotime($order['created_at']) >= strtotime($startDate) &&
                   in_array($order['status'], ['processing', 'shipped', 'delivered']);
        });
        
        $productSales = [];
        
        foreach ($orders as $order) {
            foreach ($order['items'] as $item) {
                $productId = $item['product_id'];
                
                if (!isset($productSales[$productId])) {
                    $productSales[$productId] = [
                        'product_id' => $productId,
                        'product_name' => $item['product_name'],
                        'quantity_sold' => 0,
                        'total_revenue' => 0
                    ];
                }
                
                $productSales[$productId]['quantity_sold'] += $item['quantity'];
                $productSales[$productId]['total_revenue'] += $item['total_price'];
            }
        }
        
        // Ordenar por quantidade vendida
        usort($productSales, function($a, $b) {
            return $b['quantity_sold'] <=> $a['quantity_sold'];
        });
        
        return array_slice($productSales, 0, $limit);
    }
    
    /**
     * Obtém estatísticas gerais dos pedidos
     * 
     * @return array
     */
    public function getStats(): array {
        $total = count($this->orders);
        $statusCounts = [];
        $totalRevenue = 0;
        
        foreach ($this->orders as $order) {
            $status = $order['status'];
            $statusCounts[$status] = ($statusCounts[$status] ?? 0) + 1;
            
            if (in_array($status, ['processing', 'shipped', 'delivered'])) {
                $totalRevenue += $order['total'];
            }
        }
        
        $averageOrderValue = $total > 0 ? $totalRevenue / $total : 0;
        
        return [
            'total_orders' => $total,
            'total_revenue' => $totalRevenue,
            'average_order_value' => $averageOrderValue,
            'orders_by_status' => $statusCounts,
            'pending_orders' => $statusCounts['pending'] ?? 0,
            'processing_orders' => $statusCounts['processing'] ?? 0,
            'shipped_orders' => $statusCounts['shipped'] ?? 0,
            'delivered_orders' => $statusCounts['delivered'] ?? 0,
            'cancelled_orders' => $statusCounts['cancelled'] ?? 0
        ];
    }
    
    // ========== MÉTODOS PRIVADOS ==========
    
    /**
     * Carrega pedidos do arquivo JSON
     */
    private function loadOrders(): void {
        if (file_exists($this->ordersFile)) {
            $data = json_decode(file_get_contents($this->ordersFile), true);
            $this->orders = $data ?: [];
        } else {
            $this->orders = [];
        }
    }
    
    /**
     * Carrega histórico de pedidos do arquivo JSON
     */
    private function loadOrderHistory(): void {
        if (file_exists($this->historyFile)) {
            $data = json_decode(file_get_contents($this->historyFile), true);
            $this->orderHistory = $data ?: [];
        } else {
            $this->orderHistory = [];
        }
    }
    
    /**
     * Salva pedidos no arquivo JSON
     */
    private function saveOrders(): void {
        $dir = dirname($this->ordersFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        file_put_contents($this->ordersFile, json_encode($this->orders, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
    
    /**
     * Salva histórico de pedidos no arquivo JSON
     */
    private function saveOrderHistory(): void {
        $dir = dirname($this->historyFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        file_put_contents($this->historyFile, json_encode($this->orderHistory, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
    
    /**
     * Aplica filtros aos pedidos
     * 
     * @param array $orders Pedidos
     * @param array $filters Filtros
     * @return array
     */
    private function applyFilters(array $orders, array $filters): array {
        $filtered = $orders;
        
        // Filtro por status
        if (!empty($filters['status'])) {
            $filtered = array_filter($filtered, function($order) use ($filters) {
                return $order['status'] === $filters['status'];
            });
        }
        
        // Filtro por período
        if (!empty($filters['date_from'])) {
            $dateFrom = $filters['date_from'];
            $filtered = array_filter($filtered, function($order) use ($dateFrom) {
                return strtotime($order['created_at']) >= strtotime($dateFrom);
            });
        }
        
        if (!empty($filters['date_to'])) {
            $dateTo = $filters['date_to'] . ' 23:59:59';
            $filtered = array_filter($filtered, function($order) use ($dateTo) {
                return strtotime($order['created_at']) <= strtotime($dateTo);
            });
        }
        
        // Filtro por cliente
        if (!empty($filters['customer_email'])) {
            $customerEmail = strtolower($filters['customer_email']);
            $filtered = array_filter($filtered, function($order) use ($customerEmail) {
                return stripos($order['customer_email'], $customerEmail) !== false;
            });
        }
        
        if (!empty($filters['customer_name'])) {
            $customerName = strtolower($filters['customer_name']);
            $filtered = array_filter($filtered, function($order) use ($customerName) {
                return stripos($order['customer_name'], $customerName) !== false;
            });
        }
        
        // Filtro por número do pedido
        if (!empty($filters['order_number'])) {
            $orderNumber = $filters['order_number'];
            $filtered = array_filter($filtered, function($order) use ($orderNumber) {
                return stripos($order['order_number'], $orderNumber) !== false;
            });
        }
        
        // Filtro por faixa de valor
        if (!empty($filters['min_total'])) {
            $minTotal = (float)$filters['min_total'];
            $filtered = array_filter($filtered, function($order) use ($minTotal) {
                return $order['total'] >= $minTotal;
            });
        }
        
        if (!empty($filters['max_total'])) {
            $maxTotal = (float)$filters['max_total'];
            $filtered = array_filter($filtered, function($order) use ($maxTotal) {
                return $order['total'] <= $maxTotal;
            });
        }
        
        return $filtered;
    }
    
    /**
     * Ordena pedidos
     * 
     * @param array $orders Pedidos
     * @param string $orderBy Campo de ordenação
     * @param string $direction Direção (ASC/DESC)
     * @return array
     */
    private function sortOrders(array $orders, string $orderBy, string $direction): array {
        $direction = strtoupper($direction);
        
        usort($orders, function($a, $b) use ($orderBy, $direction) {
            $valueA = $this->getSortValue($a, $orderBy);
            $valueB = $this->getSortValue($b, $orderBy);
            
            $comparison = $valueA <=> $valueB;
            
            return $direction === 'DESC' ? -$comparison : $comparison;
        });
        
        return $orders;
    }
    
    /**
     * Obtém valor para ordenação
     * 
     * @param array $order Pedido
     * @param string $field Campo
     * @return mixed
     */
    private function getSortValue(array $order, string $field) {
        switch ($field) {
            case 'created_at':
            case 'updated_at':
                return strtotime($order[$field]);
            case 'customer_name':
                return strtolower($order['customer_name']);
            case 'total':
                return (float)$order['total'];
            case 'status':
                return $order['status'];
            default:
                return $order[$field] ?? '';
        }
    }
    
    /**
     * Valida dados do pedido
     * 
     * @param array $data Dados do pedido
     * @throws \InvalidArgumentException
     */
    private function validateOrderData(array $data): void {
        $errors = [];
        
        $requiredFields = [
            'customer_name' => 'Nome do cliente',
            'customer_email' => 'Email do cliente',
            'customer_phone' => 'Telefone do cliente',
            'shipping_address' => 'Endereço de entrega',
            'payment_method' => 'Método de pagamento',
            'subtotal' => 'Subtotal',
            'total' => 'Total'
        ];
        
        foreach ($requiredFields as $field => $label) {
            if (!isset($data[$field]) || empty($data[$field])) {
                $errors[] = $label . ' é obrigatório';
            }
        }
        
        // Validar email
        if (isset($data['customer_email']) && !filter_var($data['customer_email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email inválido';
        }
        
        // Validar valores
        if (isset($data['subtotal']) && $data['subtotal'] < 0) {
            $errors[] = 'Subtotal não pode ser negativo';
        }
        
        if (isset($data['total']) && $data['total'] < 0) {
            $errors[] = 'Total não pode ser negativo';
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
        if (empty($this->orders)) {
            return 1;
        }
        
        return max(array_keys($this->orders)) + 1;
    }
    
    /**
     * Obtém próximo ID do histórico
     * 
     * @return int
     */
    private function getNextHistoryId(): int {
        if (empty($this->orderHistory)) {
            return 1;
        }
        
        $maxId = 0;
        foreach ($this->orderHistory as $entry) {
            if ($entry['id'] > $maxId) {
                $maxId = $entry['id'];
            }
        }
        
        return $maxId + 1;
    }
    
    /**
     * Gera número do pedido
     * 
     * @return string
     */
    private function generateOrderNumber(): string {
        $year = date('Y');
        $month = date('m');
        $sequence = str_pad($this->getNextId(), 6, '0', STR_PAD_LEFT);
        
        return "NS{$year}{$month}{$sequence}";
    }
    
    /**
     * Obtém data de início do período
     * 
     * @param string $period Período
     * @return string
     */
    private function getPeriodStartDate(string $period): string {
        switch ($period) {
            case 'day':
                return date('Y-m-d 00:00:00');
            case 'week':
                return date('Y-m-d 00:00:00', strtotime('monday this week'));
            case 'month':
                return date('Y-m-01 00:00:00');
            case 'year':
                return date('Y-01-01 00:00:00');
            default:
                return date('Y-m-01 00:00:00'); // Default para mês
        }
    }
}