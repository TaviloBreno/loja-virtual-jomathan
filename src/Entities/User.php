<?php

namespace Entities;

/**
 * Entidade Usuário - NeonShop
 * 
 * Representa um usuário no sistema de e-commerce.
 * Contém todas as propriedades e métodos relacionados ao usuário.
 */
class User {
    
    private int $id;
    private string $name;
    private string $email;
    private string $password;
    private ?string $phone;
    private ?string $cpf;
    private ?string $birthDate;
    private string $role;
    private bool $active;
    private bool $emailVerified;
    private ?string $emailVerificationToken;
    private ?string $passwordResetToken;
    private ?string $passwordResetExpires;
    private array $addresses;
    private array $preferences;
    private ?string $avatar;
    private string $createdAt;
    private string $updatedAt;
    private ?string $lastLoginAt;
    
    // Roles possíveis
    public const ROLE_CUSTOMER = 'customer';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_MANAGER = 'manager';
    
    /**
     * Construtor da entidade User
     * 
     * @param array $data Dados do usuário
     */
    public function __construct(array $data = []) {
        $this->id = $data['id'] ?? 0;
        $this->name = $data['name'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->phone = $data['phone'] ?? null;
        $this->cpf = $data['cpf'] ?? null;
        $this->birthDate = $data['birth_date'] ?? null;
        $this->role = $data['role'] ?? self::ROLE_CUSTOMER;
        $this->active = (bool)($data['active'] ?? true);
        $this->emailVerified = (bool)($data['email_verified'] ?? false);
        $this->emailVerificationToken = $data['email_verification_token'] ?? null;
        $this->passwordResetToken = $data['password_reset_token'] ?? null;
        $this->passwordResetExpires = $data['password_reset_expires'] ?? null;
        $this->addresses = $data['addresses'] ?? [];
        $this->preferences = $data['preferences'] ?? $this->getDefaultPreferences();
        $this->avatar = $data['avatar'] ?? null;
        $this->createdAt = $data['created_at'] ?? date('Y-m-d H:i:s');
        $this->updatedAt = $data['updated_at'] ?? date('Y-m-d H:i:s');
        $this->lastLoginAt = $data['last_login_at'] ?? null;
    }
    
    // ========== GETTERS ==========
    
    public function getId(): int {
        return $this->id;
    }
    
    public function getName(): string {
        return $this->name;
    }
    
    public function getEmail(): string {
        return $this->email;
    }
    
    public function getPassword(): string {
        return $this->password;
    }
    
    public function getPhone(): ?string {
        return $this->phone;
    }
    
    public function getCpf(): ?string {
        return $this->cpf;
    }
    
    public function getBirthDate(): ?string {
        return $this->birthDate;
    }
    
    public function getRole(): string {
        return $this->role;
    }
    
    public function isActive(): bool {
        return $this->active;
    }
    
    public function isEmailVerified(): bool {
        return $this->emailVerified;
    }
    
    public function getEmailVerificationToken(): ?string {
        return $this->emailVerificationToken;
    }
    
    public function getPasswordResetToken(): ?string {
        return $this->passwordResetToken;
    }
    
    public function getPasswordResetExpires(): ?string {
        return $this->passwordResetExpires;
    }
    
    public function getAddresses(): array {
        return $this->addresses;
    }
    
    public function getPreferences(): array {
        return $this->preferences;
    }
    
    public function getAvatar(): ?string {
        return $this->avatar;
    }
    
    public function getCreatedAt(): string {
        return $this->createdAt;
    }
    
    public function getUpdatedAt(): string {
        return $this->updatedAt;
    }
    
    public function getLastLoginAt(): ?string {
        return $this->lastLoginAt;
    }
    
    // ========== SETTERS ==========
    
    public function setId(int $id): void {
        $this->id = $id;
    }
    
    public function setName(string $name): void {
        $this->name = $name;
        $this->updateTimestamp();
    }
    
    public function setEmail(string $email): void {
        $this->email = $email;
        $this->emailVerified = false; // Reset verification when email changes
        $this->updateTimestamp();
    }
    
    public function setPassword(string $password): void {
        $this->password = $this->hashPassword($password);
        $this->updateTimestamp();
    }
    
    public function setPhone(?string $phone): void {
        $this->phone = $phone;
        $this->updateTimestamp();
    }
    
    public function setCpf(?string $cpf): void {
        $this->cpf = $cpf;
        $this->updateTimestamp();
    }
    
    public function setBirthDate(?string $birthDate): void {
        $this->birthDate = $birthDate;
        $this->updateTimestamp();
    }
    
    public function setRole(string $role): void {
        if ($this->isValidRole($role)) {
            $this->role = $role;
            $this->updateTimestamp();
        }
    }
    
    public function setActive(bool $active): void {
        $this->active = $active;
        $this->updateTimestamp();
    }
    
    public function setEmailVerified(bool $emailVerified): void {
        $this->emailVerified = $emailVerified;
        if ($emailVerified) {
            $this->emailVerificationToken = null;
        }
        $this->updateTimestamp();
    }
    
    public function setEmailVerificationToken(?string $token): void {
        $this->emailVerificationToken = $token;
        $this->updateTimestamp();
    }
    
    public function setPasswordResetToken(?string $token): void {
        $this->passwordResetToken = $token;
        $this->updateTimestamp();
    }
    
    public function setPasswordResetExpires(?string $expires): void {
        $this->passwordResetExpires = $expires;
        $this->updateTimestamp();
    }
    
    public function setAddresses(array $addresses): void {
        $this->addresses = $addresses;
        $this->updateTimestamp();
    }
    
    public function setPreferences(array $preferences): void {
        $this->preferences = array_merge($this->getDefaultPreferences(), $preferences);
        $this->updateTimestamp();
    }
    
    public function setAvatar(?string $avatar): void {
        $this->avatar = $avatar;
        $this->updateTimestamp();
    }
    
    public function setLastLoginAt(?string $lastLoginAt): void {
        $this->lastLoginAt = $lastLoginAt;
        $this->updateTimestamp();
    }
    
    // ========== MÉTODOS DE NEGÓCIO ==========
    
    /**
     * Verifica se a senha está correta
     * 
     * @param string $password Senha a verificar
     * @return bool
     */
    public function verifyPassword(string $password): bool {
        return password_verify($password, $this->password);
    }
    
    /**
     * Gera hash da senha
     * 
     * @param string $password Senha em texto plano
     * @return string
     */
    private function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    /**
     * Verifica se o usuário é administrador
     * 
     * @return bool
     */
    public function isAdmin(): bool {
        return $this->role === self::ROLE_ADMIN;
    }
    
    /**
     * Verifica se o usuário é gerente
     * 
     * @return bool
     */
    public function isManager(): bool {
        return $this->role === self::ROLE_MANAGER;
    }
    
    /**
     * Verifica se o usuário é cliente
     * 
     * @return bool
     */
    public function isCustomer(): bool {
        return $this->role === self::ROLE_CUSTOMER;
    }
    
    /**
     * Verifica se o usuário tem permissões administrativas
     * 
     * @return bool
     */
    public function hasAdminPermissions(): bool {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_MANAGER]);
    }
    
    /**
     * Adiciona um endereço
     * 
     * @param array $address Dados do endereço
     */
    public function addAddress(array $address): void {
        $address['id'] = uniqid();
        $address['created_at'] = date('Y-m-d H:i:s');
        $this->addresses[] = $address;
        $this->updateTimestamp();
    }
    
    /**
     * Remove um endereço
     * 
     * @param string $addressId ID do endereço
     */
    public function removeAddress(string $addressId): void {
        $this->addresses = array_filter($this->addresses, function($address) use ($addressId) {
            return $address['id'] !== $addressId;
        });
        
        $this->addresses = array_values($this->addresses); // Reindexar
        $this->updateTimestamp();
    }
    
    /**
     * Atualiza um endereço
     * 
     * @param string $addressId ID do endereço
     * @param array $data Novos dados
     */
    public function updateAddress(string $addressId, array $data): void {
        foreach ($this->addresses as &$address) {
            if ($address['id'] === $addressId) {
                $address = array_merge($address, $data);
                $address['updated_at'] = date('Y-m-d H:i:s');
                break;
            }
        }
        
        $this->updateTimestamp();
    }
    
    /**
     * Obtém o endereço principal
     * 
     * @return array|null
     */
    public function getPrimaryAddress(): ?array {
        foreach ($this->addresses as $address) {
            if ($address['is_primary'] ?? false) {
                return $address;
            }
        }
        
        return $this->addresses[0] ?? null;
    }
    
    /**
     * Define um endereço como principal
     * 
     * @param string $addressId ID do endereço
     */
    public function setPrimaryAddress(string $addressId): void {
        foreach ($this->addresses as &$address) {
            $address['is_primary'] = ($address['id'] === $addressId);
        }
        
        $this->updateTimestamp();
    }
    
    /**
     * Atualiza uma preferência
     * 
     * @param string $key Chave da preferência
     * @param mixed $value Valor da preferência
     */
    public function setPreference(string $key, $value): void {
        $this->preferences[$key] = $value;
        $this->updateTimestamp();
    }
    
    /**
     * Obtém uma preferência
     * 
     * @param string $key Chave da preferência
     * @param mixed $default Valor padrão
     * @return mixed
     */
    public function getPreference(string $key, $default = null) {
        return $this->preferences[$key] ?? $default;
    }
    
    /**
     * Gera token de verificação de email
     * 
     * @return string
     */
    public function generateEmailVerificationToken(): string {
        $this->emailVerificationToken = bin2hex(random_bytes(32));
        $this->updateTimestamp();
        return $this->emailVerificationToken;
    }
    
    /**
     * Gera token de reset de senha
     * 
     * @param int $expiresInHours Horas para expirar (padrão: 1)
     * @return string
     */
    public function generatePasswordResetToken(int $expiresInHours = 1): string {
        $this->passwordResetToken = bin2hex(random_bytes(32));
        $this->passwordResetExpires = date('Y-m-d H:i:s', strtotime("+{$expiresInHours} hours"));
        $this->updateTimestamp();
        return $this->passwordResetToken;
    }
    
    /**
     * Verifica se o token de reset de senha é válido
     * 
     * @param string $token Token a verificar
     * @return bool
     */
    public function isValidPasswordResetToken(string $token): bool {
        if ($this->passwordResetToken !== $token) {
            return false;
        }
        
        if (!$this->passwordResetExpires) {
            return false;
        }
        
        return strtotime($this->passwordResetExpires) > time();
    }
    
    /**
     * Limpa tokens de reset de senha
     */
    public function clearPasswordResetTokens(): void {
        $this->passwordResetToken = null;
        $this->passwordResetExpires = null;
        $this->updateTimestamp();
    }
    
    /**
     * Registra login
     */
    public function recordLogin(): void {
        $this->lastLoginAt = date('Y-m-d H:i:s');
        $this->updateTimestamp();
    }
    
    /**
     * Obtém a idade do usuário
     * 
     * @return int|null
     */
    public function getAge(): ?int {
        if (!$this->birthDate) {
            return null;
        }
        
        $birthDate = new \DateTime($this->birthDate);
        $today = new \DateTime();
        
        return $today->diff($birthDate)->y;
    }
    
    /**
     * Formata o CPF
     * 
     * @return string|null
     */
    public function getFormattedCpf(): ?string {
        if (!$this->cpf) {
            return null;
        }
        
        $cpf = preg_replace('/\D/', '', $this->cpf);
        return preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $cpf);
    }
    
    /**
     * Formata o telefone
     * 
     * @return string|null
     */
    public function getFormattedPhone(): ?string {
        if (!$this->phone) {
            return null;
        }
        
        $phone = preg_replace('/\D/', '', $this->phone);
        
        if (strlen($phone) === 11) {
            return preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $phone);
        } elseif (strlen($phone) === 10) {
            return preg_replace('/(\d{2})(\d{4})(\d{4})/', '($1) $2-$3', $phone);
        }
        
        return $this->phone;
    }
    
    /**
     * Obtém as iniciais do nome
     * 
     * @return string
     */
    public function getInitials(): string {
        $names = explode(' ', trim($this->name));
        $initials = '';
        
        foreach ($names as $name) {
            if (!empty($name)) {
                $initials .= strtoupper($name[0]);
            }
        }
        
        return substr($initials, 0, 2);
    }
    
    /**
     * Obtém o primeiro nome
     * 
     * @return string
     */
    public function getFirstName(): string {
        $names = explode(' ', trim($this->name));
        return $names[0] ?? '';
    }
    
    /**
     * Obtém o último nome
     * 
     * @return string
     */
    public function getLastName(): string {
        $names = explode(' ', trim($this->name));
        return end($names) ?? '';
    }
    
    /**
     * Obtém o avatar ou gera um padrão
     * 
     * @return string
     */
    public function getAvatarUrl(): string {
        if ($this->avatar) {
            return $this->avatar;
        }
        
        // Gerar avatar padrão baseado nas iniciais
        $initials = $this->getInitials();
        $colors = ['#FF6B6B', '#4ECDC4', '#45B7D1', '#96CEB4', '#FFEAA7', '#DDA0DD', '#98D8C8'];
        $color = $colors[array_sum(str_split(ord($this->name[0] ?? 'A'))) % count($colors)];
        
        return "https://ui-avatars.com/api/?name={$initials}&background={$color}&color=fff&size=200";
    }
    
    /**
     * Obtém as preferências padrão
     * 
     * @return array
     */
    private function getDefaultPreferences(): array {
        return [
            'newsletter' => true,
            'email_notifications' => true,
            'sms_notifications' => false,
            'theme' => 'light',
            'language' => 'pt-BR',
            'currency' => 'BRL'
        ];
    }
    
    /**
     * Verifica se o role é válido
     * 
     * @param string $role Role
     * @return bool
     */
    private function isValidRole(string $role): bool {
        return in_array($role, [
            self::ROLE_CUSTOMER,
            self::ROLE_ADMIN,
            self::ROLE_MANAGER
        ]);
    }
    
    /**
     * Converte a entidade para array
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
            'last_login_at' => $this->lastLoginAt,
            
            // Campos calculados
            'is_admin' => $this->isAdmin(),
            'is_manager' => $this->isManager(),
            'is_customer' => $this->isCustomer(),
            'has_admin_permissions' => $this->hasAdminPermissions(),
            'age' => $this->getAge(),
            'formatted_cpf' => $this->getFormattedCpf(),
            'formatted_phone' => $this->getFormattedPhone(),
            'initials' => $this->getInitials(),
            'first_name' => $this->getFirstName(),
            'last_name' => $this->getLastName(),
            'avatar_url' => $this->getAvatarUrl(),
            'primary_address' => $this->getPrimaryAddress()
        ];
        
        if ($includePassword) {
            $data['password'] = $this->password;
            $data['email_verification_token'] = $this->emailVerificationToken;
            $data['password_reset_token'] = $this->passwordResetToken;
            $data['password_reset_expires'] = $this->passwordResetExpires;
        }
        
        return $data;
    }
    
    /**
     * Converte a entidade para JSON
     * 
     * @param bool $includePassword Incluir senha no JSON
     * @return string
     */
    public function toJson(bool $includePassword = false): string {
        return json_encode($this->toArray($includePassword), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    }
    
    /**
     * Cria instância a partir de array
     * 
     * @param array $data Dados do usuário
     * @return self
     */
    public static function fromArray(array $data): self {
        return new self($data);
    }
    
    /**
     * Valida os dados do usuário
     * 
     * @return array Lista de erros (vazio se válido)
     */
    public function validate(): array {
        $errors = [];
        
        if (empty($this->name) || strlen(trim($this->name)) < 2) {
            $errors[] = 'Nome é obrigatório e deve ter pelo menos 2 caracteres';
        }
        
        if (empty($this->email) || !filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email é obrigatório e deve ser válido';
        }
        
        if (empty($this->password)) {
            $errors[] = 'Senha é obrigatória';
        }
        
        if (!$this->isValidRole($this->role)) {
            $errors[] = 'Role inválido';
        }
        
        if ($this->cpf && !$this->isValidCpf($this->cpf)) {
            $errors[] = 'CPF inválido';
        }
        
        if ($this->birthDate && !$this->isValidDate($this->birthDate)) {
            $errors[] = 'Data de nascimento inválida';
        }
        
        return $errors;
    }
    
    /**
     * Verifica se o usuário é válido
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
    
    // ========== MÉTODOS PRIVADOS ==========
    
    /**
     * Atualiza o timestamp de modificação
     */
    private function updateTimestamp(): void {
        $this->updatedAt = date('Y-m-d H:i:s');
    }
}