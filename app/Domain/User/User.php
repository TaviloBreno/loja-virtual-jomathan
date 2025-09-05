<?php

namespace App\Domain\User;

use App\Domain\Entities\BaseEntity;
use App\Infrastructure\Database\Factory;

/**
 * Modelo User
 * 
 * Representa um usuário do sistema
 */
class User extends BaseEntity
{
    /**
     * Nome da tabela
     */
    protected string $table = 'users';
    
    /**
     * Campos que podem ser preenchidos em massa
     */
    protected array $fillable = [
        'name',
        'email',
        'password',
        'status',
        'email_verified_at',
        'remember_token'
    ];
    
    /**
     * Campos que devem ser ocultados na serialização
     */
    protected array $hidden = [
        'password',
        'remember_token'
    ];
    
    /**
     * Campos que devem ser convertidos para tipos específicos
     */
    protected array $casts = [
        'email_verified_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    /**
     * Validações do modelo
     */
    protected array $rules = [
        'name' => 'required|string|min:2|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:8',
        'status' => 'in:active,inactive,pending'
    ];
    
    /**
     * Mensagens de validação customizadas
     */
    protected array $messages = [
        'name.required' => 'O nome é obrigatório',
        'name.min' => 'O nome deve ter pelo menos 2 caracteres',
        'name.max' => 'O nome não pode ter mais de 255 caracteres',
        'email.required' => 'O e-mail é obrigatório',
        'email.email' => 'O e-mail deve ter um formato válido',
        'email.unique' => 'Este e-mail já está em uso',
        'password.required' => 'A senha é obrigatória',
        'password.min' => 'A senha deve ter pelo menos 8 caracteres',
        'status.in' => 'O status deve ser: ativo, inativo ou pendente'
    ];
    
    /**
     * Construtor
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }
    
    /**
     * Mutator para senha - criptografa automaticamente
     */
    public function setPasswordAttribute($value): void
    {
        if ($value) {
            $this->attributes['password'] = password_hash($value, PASSWORD_DEFAULT);
        }
    }
    
    /**
     * Accessor para nome - primeira letra maiúscula
     */
    public function getNameAttribute($value): string
    {
        return ucwords(strtolower($value));
    }
    
    /**
     * Accessor para status formatado
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'active' => 'Ativo',
            'inactive' => 'Inativo',
            'pending' => 'Pendente'
        ];
        
        return $labels[$this->status] ?? 'Desconhecido';
    }
    
    /**
     * Accessor para avatar (usando Gravatar)
     */
    public function getAvatarAttribute(): string
    {
        $hash = md5(strtolower(trim($this->email)));
        return "https://www.gravatar.com/avatar/{$hash}?d=identicon&s=150";
    }
    
    /**
     * Verifica se o usuário está ativo
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }
    
    /**
     * Verifica se o usuário é administrador
     */
    public function isAdmin(): bool
    {
        return $this->email === 'admin@sistema.com';
    }
    
    /**
     * Verifica se a senha está correta
     */
    public function checkPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }
    
    /**
     * Marca o e-mail como verificado
     */
    public function markEmailAsVerified(): bool
    {
        $this->email_verified_at = date('Y-m-d H:i:s');
        return $this->save();
    }
    
    /**
     * Verifica se o e-mail foi verificado
     */
    public function hasVerifiedEmail(): bool
    {
        return !is_null($this->email_verified_at);
    }
    
    /**
     * Gera um token de lembrete
     */
    public function generateRememberToken(): string
    {
        $this->remember_token = bin2hex(random_bytes(32));
        $this->save();
        return $this->remember_token;
    }
    
    /**
     * Scopes para consultas
     */
    
    /**
     * Scope para usuários ativos
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
    
    /**
     * Scope para usuários verificados
     */
    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }
    
    /**
     * Scope para busca por nome ou e-mail
     */
    public function scopeSearch($query, $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('name', 'LIKE', "%{$term}%")
              ->orWhere('email', 'LIKE', "%{$term}%");
        });
    }
    
    /**
     * Scope para ordenação por nome
     */
    public function scopeOrderByName($query, $direction = 'asc')
    {
        return $query->orderBy('name', $direction);
    }
    
    /**
     * Métodos estáticos de conveniência
     */
    
    /**
     * Encontra usuário por e-mail
     */
    public static function findByEmail(string $email): ?self
    {
        return static::where('email', $email)->first();
    }
    
    /**
     * Cria um novo usuário com dados validados
     */
    public static function createUser(array $data): self
    {
        $user = new static();
        $user->fill($data);
        
        if (!$user->validate()) {
            throw new \InvalidArgumentException('Dados inválidos: ' . implode(', ', $user->getErrors()));
        }
        
        $user->save();
        return $user;
    }
    
    /**
     * Obtém estatísticas dos usuários
     */
    public static function getStats(): array
    {
        $total = static::count();
        $active = static::active()->count();
        $verified = static::verified()->count();
        $recent = static::where('created_at', '>=', date('Y-m-d', strtotime('-30 days')))->count();
        
        return [
            'total' => $total,
            'active' => $active,
            'inactive' => $total - $active,
            'verified' => $verified,
            'unverified' => $total - $verified,
            'recent' => $recent,
            'active_percentage' => $total > 0 ? round(($active / $total) * 100, 1) : 0,
            'verified_percentage' => $total > 0 ? round(($verified / $total) * 100, 1) : 0
        ];
    }
    
    /**
     * Factory para testes e seeders
     */
    public static function factory(): UserFactory
    {
        return new UserFactory();
    }
    
    /**
     * Serialização customizada
     */
    public function toArray(): array
    {
        $data = parent::toArray();
        
        // Adicionar campos computados
        $data['status_label'] = $this->status_label;
        $data['avatar'] = $this->avatar;
        $data['is_active'] = $this->isActive();
        $data['is_admin'] = $this->isAdmin();
        $data['has_verified_email'] = $this->hasVerifiedEmail();
        
        // Remover campos sensíveis
        unset($data['password'], $data['remember_token']);
        
        return $data;
    }
    
    /**
     * Representação em string
     */
    public function __toString(): string
    {
        return $this->name . ' (' . $this->email . ')';
    }
}

/**
 * Factory específica para User
 */
class UserFactory extends Factory
{
    protected string $model = User::class;
    
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => 'password123', // Será criptografada automaticamente
            'status' => $this->faker->randomElement(['active', 'inactive', 'pending']),
            'email_verified_at' => $this->faker->optional(0.7)->dateTimeThisYear(),
            'created_at' => $this->faker->dateTimeThisYear(),
            'updated_at' => function (array $attributes) {
                return $this->faker->dateTimeBetween($attributes['created_at'], 'now');
            }
        ];
    }
    
    /**
     * Estado: usuário ativo
     */
    public function active(): self
    {
        return $this->state(function (array $attributes) {
            return ['status' => 'active'];
        });
    }
    
    /**
     * Estado: usuário inativo
     */
    public function inactive(): self
    {
        return $this->state(function (array $attributes) {
            return ['status' => 'inactive'];
        });
    }
    
    /**
     * Estado: e-mail verificado
     */
    public function verified(): self
    {
        return $this->state(function (array $attributes) {
            return ['email_verified_at' => $this->faker->dateTimeThisYear()];
        });
    }
    
    /**
     * Estado: e-mail não verificado
     */
    public function unverified(): self
    {
        return $this->state(function (array $attributes) {
            return ['email_verified_at' => null];
        });
    }
    
    /**
     * Estado: administrador
     */
    public function admin(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'name' => 'Administrador',
                'email' => 'admin@sistema.com',
                'status' => 'active',
                'email_verified_at' => date('Y-m-d H:i:s')
            ];
        });
    }
}