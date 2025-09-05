<?php

namespace App\Models;

use App\Infrastructure\Database\DatabaseManager;

class User extends BaseModel
{
    protected string $table = 'users';
    protected array $fillable = [
        'name',
        'email', 
        'password',
        'status',
        'email_verified_at',
        'preferences',
        'created_at',
        'updated_at'
    ];

    /**
     * Busca usuário por email
     */
    public function findByEmail(string $email): ?array
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = ? LIMIT 1";
        return DatabaseManager::fetchOne($sql, [$email]);
    }

    /**
     * Lista usuários com paginação
     */
    public function paginate(int $limit = 10, int $offset = 0): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY created_at DESC LIMIT ? OFFSET ?";
        return DatabaseManager::fetchAll($sql, [$limit, $offset]);
    }

    /**
     * Conta total de usuários
     */
    public function count(array $conditions = []): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $params = [];
        
        if (!empty($conditions)) {
            $whereClause = [];
            foreach ($conditions as $field => $value) {
                $whereClause[] = "{$field} = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $whereClause);
        }
        
        $result = DatabaseManager::fetchOne($sql, $params);
        return (int) $result['total'];
    }

    /**
     * Busca usuários ativos
     */
    public function findActive(): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE status = 'active' ORDER BY name";
        return DatabaseManager::fetchAll($sql);
    }

    /**
     * Busca usuários por status
     */
    public function findByStatus(string $status): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE status = ? ORDER BY created_at DESC";
        return DatabaseManager::fetchAll($sql, [$status]);
    }

    /**
     * Verifica se email já existe (excluindo um ID específico)
     */
    public function emailExists(string $email, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE email = ?";
        $params = [$email];
        
        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }
        
        $result = DatabaseManager::fetchOne($sql, $params);
        return (int) $result['total'] > 0;
    }

    /**
     * Atualiza último login do usuário
     */
    public function updateLastLogin(int $userId): bool
    {
        $sql = "UPDATE {$this->table} SET updated_at = ? WHERE id = ?";
        return DatabaseManager::execute($sql, [date('Y-m-d H:i:s'), $userId]);
    }

    /**
     * Marca email como verificado
     */
    public function markEmailAsVerified(int $userId): bool
    {
        $sql = "UPDATE {$this->table} SET email_verified_at = ?, updated_at = ? WHERE id = ?";
        $now = date('Y-m-d H:i:s');
        return DatabaseManager::execute($sql, [$now, $now, $userId]);
    }

    /**
     * Busca usuários criados em um período
     */
    public function findByDateRange(string $startDate, string $endDate): array
    {
        $sql = "SELECT * FROM {$this->table} WHERE created_at BETWEEN ? AND ? ORDER BY created_at DESC";
        return DatabaseManager::fetchAll($sql, [$startDate, $endDate]);
    }

    /**
     * Busca usuários por termo de pesquisa (nome ou email)
     */
    public function search(string $term, int $limit = 10, int $offset = 0): array
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE name LIKE ? OR email LIKE ? 
                ORDER BY name 
                LIMIT ? OFFSET ?";
        $searchTerm = "%{$term}%";
        return DatabaseManager::fetchAll($sql, [$searchTerm, $searchTerm, $limit, $offset]);
    }

    /**
     * Conta usuários por termo de pesquisa
     */
    public function countSearch(string $term): int
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE name LIKE ? OR email LIKE ?";
        $searchTerm = "%{$term}%";
        $result = DatabaseManager::fetchOne($sql, [$searchTerm, $searchTerm]);
        return (int) $result['total'];
    }

    /**
     * Obtém estatísticas dos usuários
     */
    public function getStats(): array
    {
        $stats = [];
        
        // Total de usuários
        $stats['total'] = $this->count();
        
        // Usuários ativos
        $stats['active'] = $this->count(['status' => 'active']);
        
        // Usuários inativos
        $stats['inactive'] = $this->count(['status' => 'inactive']);
        
        // Usuários verificados
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE email_verified_at IS NOT NULL";
        $result = DatabaseManager::fetchOne($sql);
        $stats['verified'] = (int) $result['total'];
        
        // Usuários criados hoje
        $today = date('Y-m-d');
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE DATE(created_at) = ?";
        $result = DatabaseManager::fetchOne($sql, [$today]);
        $stats['today'] = (int) $result['total'];
        
        // Usuários criados esta semana
        $weekStart = date('Y-m-d', strtotime('monday this week'));
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE created_at >= ?";
        $result = DatabaseManager::fetchOne($sql, [$weekStart]);
        $stats['this_week'] = (int) $result['total'];
        
        return $stats;
    }
}