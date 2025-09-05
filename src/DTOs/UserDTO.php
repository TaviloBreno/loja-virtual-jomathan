<?php

namespace DTOs;

/**
 * DTO para Usuário - NeonShop
 * 
 * Data Transfer Object para transferência de dados de usuários
 * entre diferentes camadas da aplicação.
 */
class UserDTO {
    
    public ?int $id;
    public string $name;
    public string $email;
    public ?string $password;
    public ?string $phone;
    public ?string $cpf;
    public ?string $birthDate;
    public string $role;
    public bool $active;
    public bool $emailVerified;
    public array $addresses;
    public array $preferences;
    public ?string $avatar;
    public ?string $createdAt;
    public ?string $updatedAt;
    public ?string $lastLoginAt;
    
    /**
     * Construtor do UserDTO
     * 
     * @param array $data Dados do usuário
     */
    public function __construct(array $data = []) {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? null;
        $this->phone = $data['phone'] ?? null;
        $this->cpf = $data['cpf'] ?? null;
        $this->birthDate = $data['birth_date'] ?? null;
        $this->role = $data['role'] ?? 'customer';
        $this->active = (bool)($data['active'] ?? true);
        $this->emailVerified = (bool)($data['email_verified'] ?? false);
        $this->addresses = $data['addresses'] ?? [];
        $this->preferences = $data['preferences'] ?? [];
        $this->avatar = $data['avatar'] ?? null;
        $this->createdAt = $data['created_at'] ?? null;
        $this->updatedAt = $data['updated_at'] ?? null;
        $this->lastLoginAt = $data['last_login_at'] ?? null;
    }
    
    /**
     * Cria DTO a partir de array
     * 
     * @param array $data Dados do usuário
     * @return self
     */
    public static function fromArray(array $data): self {
        return new self($data);
    }
    
    /**
     * Converte DTO para array
     * 
     * @param bool $includePassword Incluir senha no array
     * @return array
     */
    public function toArray(bool $includePassword = false): array {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'cpf' => $this->cpf,
            'birth_date' => $this->birthDate,
            'role' => $this->role,
            'active' => $this->active,
            'email_verified' => $this->emailVerified,
            'addresses' => $this->addresses,
            'preferences' => $this->preferences,
            'avatar' => $this->avatar,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt,
            'last_login_at' => $this->lastLoginAt
        ];
        
        if ($includePassword && $this->password) {
            $data['password'] = $this->password;
        }
        
        return $data;
    }
    
    /**
     * Converte DTO para JSON
     * 
     * @param bool $includePassword Incluir senha no JSON
     * @return string
     */
    public function toJson(bool $includePassword = false): string {
        return json_encode($this->toArray($includePassword), JSON_UNESCAPED_UNICODE);
    }
    
    /**
     * Valida os dados do DTO
     * 
     * @return array Lista de erros
     */
    public function validate(): array {
        $errors = [];
        
        if (empty(trim($this->name))) {
            $errors['name'] = 'Nome é obrigatório';
        } elseif (strlen(trim($this->name)) < 2) {
            $errors['name'] = 'Nome deve ter pelo menos 2 caracteres';
        }
        
        if (empty($this->email)) {
            $errors['email'] = 'Email é obrigatório';
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email deve ser válido';
        }
        
        if ($this->cpf && !$this->isValidCpf($this->cpf)) {
            $errors['cpf'] = 'CPF inválido';
        }
        
        if ($this->birthDate && !$this->isValidDate($this->birthDate)) {
            $errors['birth_date'] = 'Data de nascimento inválida';
        }
        
        if (!in_array($this->role, ['customer', 'admin', 'manager'])) {
            $errors['role'] = 'Role inválido';
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
    
    /**
     * Valida CPF
     * 
     * @param string $cpf CPF
     * @return bool
     */
    private function isValidCpf(string $cpf): bool {
        $cpf = preg_replace('/\D/', '', $cpf);
        
        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }
        
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        
        return true;
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
 * DTO para criação de usuário
 */
class CreateUserDTO {
    
    public string $name;
    public string $email;
    public string $password;
    public ?string $phone;
    public ?string $cpf;
    public ?string $birthDate;
    public string $role;
    public array $addresses;
    public array $preferences;
    
    /**
     * Construtor do CreateUserDTO
     * 
     * @param array $data Dados para criação do usuário
     */
    public function __construct(array $data = []) {
        $this->name = $data['name'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->phone = $data['phone'] ?? null;
        $this->cpf = $data['cpf'] ?? null;
        $this->birthDate = $data['birth_date'] ?? null;
        $this->role = $data['role'] ?? 'customer';
        $this->addresses = $data['addresses'] ?? [];
        $this->preferences = $data['preferences'] ?? [];
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
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'phone' => $this->phone,
            'cpf' => $this->cpf,
            'birth_date' => $this->birthDate,
            'role' => $this->role,
            'addresses' => $this->addresses,
            'preferences' => $this->preferences
        ];
    }
    
    /**
     * Valida os dados para criação
     * 
     * @return array Lista de erros
     */
    public function validate(): array {
        $errors = [];
        
        if (empty(trim($this->name))) {
            $errors['name'] = 'Nome é obrigatório';
        } elseif (strlen(trim($this->name)) < 2) {
            $errors['name'] = 'Nome deve ter pelo menos 2 caracteres';
        }
        
        if (empty($this->email)) {
            $errors['email'] = 'Email é obrigatório';
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email deve ser válido';
        }
        
        if (empty($this->password)) {
            $errors['password'] = 'Senha é obrigatória';
        } elseif (strlen($this->password) < 6) {
            $errors['password'] = 'Senha deve ter pelo menos 6 caracteres';
        }
        
        if ($this->cpf && !$this->isValidCpf($this->cpf)) {
            $errors['cpf'] = 'CPF inválido';
        }
        
        if ($this->birthDate && !$this->isValidDate($this->birthDate)) {
            $errors['birth_date'] = 'Data de nascimento inválida';
        }
        
        if (!in_array($this->role, ['customer', 'admin', 'manager'])) {
            $errors['role'] = 'Role inválido';
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
    
    /**
     * Valida CPF
     * 
     * @param string $cpf CPF
     * @return bool
     */
    private function isValidCpf(string $cpf): bool {
        $cpf = preg_replace('/\D/', '', $cpf);
        
        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }
        
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        
        return true;
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
 * DTO para login de usuário
 */
class LoginDTO {
    
    public string $email;
    public string $password;
    public bool $rememberMe;
    
    /**
     * Construtor do LoginDTO
     * 
     * @param array $data Dados de login
     */
    public function __construct(array $data = []) {
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->rememberMe = (bool)($data['remember_me'] ?? false);
    }
    
    /**
     * Cria DTO a partir de array
     * 
     * @param array $data Dados de login
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
            'email' => $this->email,
            'password' => $this->password,
            'remember_me' => $this->rememberMe
        ];
    }
    
    /**
     * Valida os dados de login
     * 
     * @return array Lista de erros
     */
    public function validate(): array {
        $errors = [];
        
        if (empty($this->email)) {
            $errors['email'] = 'Email é obrigatório';
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email deve ser válido';
        }
        
        if (empty($this->password)) {
            $errors['password'] = 'Senha é obrigatória';
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

/**
 * DTO para filtros de busca de usuários
 */
class UserFilterDTO {
    
    public ?string $search;
    public ?string $role;
    public ?bool $active;
    public ?bool $emailVerified;
    public ?string $dateFrom;
    public ?string $dateTo;
    public string $sortBy;
    public string $sortOrder;
    public int $page;
    public int $limit;
    
    /**
     * Construtor do UserFilterDTO
     * 
     * @param array $data Dados dos filtros
     */
    public function __construct(array $data = []) {
        $this->search = $data['search'] ?? null;
        $this->role = $data['role'] ?? null;
        $this->active = isset($data['active']) ? (bool)$data['active'] : null;
        $this->emailVerified = isset($data['email_verified']) ? (bool)$data['email_verified'] : null;
        $this->dateFrom = $data['date_from'] ?? null;
        $this->dateTo = $data['date_to'] ?? null;
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
            'role' => $this->role,
            'active' => $this->active,
            'email_verified' => $this->emailVerified,
            'date_from' => $this->dateFrom,
            'date_to' => $this->dateTo,
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
        
        if ($this->dateFrom && !$this->isValidDate($this->dateFrom)) {
            $errors['date_from'] = 'Data inicial inválida';
        }
        
        if ($this->dateTo && !$this->isValidDate($this->dateTo)) {
            $errors['date_to'] = 'Data final inválida';
        }
        
        if ($this->dateFrom && $this->dateTo && $this->dateFrom > $this->dateTo) {
            $errors['date_range'] = 'Data inicial não pode ser maior que a final';
        }
        
        if ($this->role && !in_array($this->role, ['customer', 'admin', 'manager'])) {
            $errors['role'] = 'Role inválido';
        }
        
        $validSortFields = ['name', 'email', 'created_at', 'last_login_at', 'role'];
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
 * DTO para resposta paginada de usuários
 */
class UserListResponseDTO {
    
    public array $users;
    public int $total;
    public int $page;
    public int $limit;
    public int $totalPages;
    public bool $hasNext;
    public bool $hasPrevious;
    public array $filters;
    public array $summary;
    
    /**
     * Construtor do UserListResponseDTO
     * 
     * @param array $users Lista de usuários
     * @param int $total Total de usuários
     * @param UserFilterDTO $filters Filtros aplicados
     * @param array $summary Resumo dos usuários
     */
    public function __construct(array $users, int $total, UserFilterDTO $filters, array $summary = []) {
        $this->users = $users;
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
            'users' => $this->users,
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
 * DTO para atualização de usuário
 */
class UpdateUserDTO {
    
    public ?string $name;
    public ?string $email;
    public ?string $phone;
    public ?string $cpf;
    public ?string $birthDate;
    public ?string $role;
    public ?bool $active;
    public ?bool $emailVerified;
    public ?array $addresses;
    public ?array $preferences;
    public ?string $avatar;
    
    /**
     * Construtor do UpdateUserDTO
     * 
     * @param array $data Dados para atualização
     */
    public function __construct(array $data = []) {
        $this->name = $data['name'] ?? null;
        $this->email = $data['email'] ?? null;
        $this->phone = $data['phone'] ?? null;
        $this->cpf = $data['cpf'] ?? null;
        $this->birthDate = $data['birth_date'] ?? null;
        $this->role = $data['role'] ?? null;
        $this->active = isset($data['active']) ? (bool)$data['active'] : null;
        $this->emailVerified = isset($data['email_verified']) ? (bool)$data['email_verified'] : null;
        $this->addresses = $data['addresses'] ?? null;
        $this->preferences = $data['preferences'] ?? null;
        $this->avatar = $data['avatar'] ?? null;
    }
    
    /**
     * Cria DTO a partir de array
     * 
     * @param array $data Dados para atualização
     * @return self
     */
    public static function fromArray(array $data): self {
        return new self($data);
    }
    
    /**
     * Converte DTO para array (apenas campos não nulos)
     * 
     * @return array
     */
    public function toArray(): array {
        $data = [];
        
        if ($this->name !== null) $data['name'] = $this->name;
        if ($this->email !== null) $data['email'] = $this->email;
        if ($this->phone !== null) $data['phone'] = $this->phone;
        if ($this->cpf !== null) $data['cpf'] = $this->cpf;
        if ($this->birthDate !== null) $data['birth_date'] = $this->birthDate;
        if ($this->role !== null) $data['role'] = $this->role;
        if ($this->active !== null) $data['active'] = $this->active;
        if ($this->emailVerified !== null) $data['email_verified'] = $this->emailVerified;
        if ($this->addresses !== null) $data['addresses'] = $this->addresses;
        if ($this->preferences !== null) $data['preferences'] = $this->preferences;
        if ($this->avatar !== null) $data['avatar'] = $this->avatar;
        
        return $data;
    }
    
    /**
     * Verifica se há dados para atualizar
     * 
     * @return bool
     */
    public function hasDataToUpdate(): bool {
        return !empty($this->toArray());
    }
    
    /**
     * Valida os dados para atualização
     * 
     * @return array Lista de erros
     */
    public function validate(): array {
        $errors = [];
        
        if ($this->name !== null && (empty(trim($this->name)) || strlen(trim($this->name)) < 2)) {
            $errors['name'] = 'Nome deve ter pelo menos 2 caracteres';
        }
        
        if ($this->email !== null && (empty($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL))) {
            $errors['email'] = 'Email deve ser válido';
        }
        
        if ($this->cpf !== null && !empty($this->cpf) && !$this->isValidCpf($this->cpf)) {
            $errors['cpf'] = 'CPF inválido';
        }
        
        if ($this->birthDate !== null && !empty($this->birthDate) && !$this->isValidDate($this->birthDate)) {
            $errors['birth_date'] = 'Data de nascimento inválida';
        }
        
        if ($this->role !== null && !in_array($this->role, ['customer', 'admin', 'manager'])) {
            $errors['role'] = 'Role inválido';
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
    
    /**
     * Valida CPF
     * 
     * @param string $cpf CPF
     * @return bool
     */
    private function isValidCpf(string $cpf): bool {
        $cpf = preg_replace('/\D/', '', $cpf);
        
        if (strlen($cpf) !== 11 || preg_match('/^(\d)\1{10}$/', $cpf)) {
            return false;
        }
        
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        
        return true;
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