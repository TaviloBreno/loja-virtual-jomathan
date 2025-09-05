<?php

namespace Database\Factories;

use App\Infrastructure\Database\Factory;

/**
 * UserFactory - Factory para geração de dados de usuários
 * Demonstra o uso do sistema de factories
 */
class UserFactory extends Factory
{
    /**
     * Define a estrutura dos dados fake para usuários
     */
    public function definition(): array
    {
        return [
            'name' => $this->fakeName(),
            'email' => $this->fakeEmail(),
            'email_verified_at' => $this->randomBool() ? $this->fakeDate('-30 days') : null,
            'password' => password_hash('password123', PASSWORD_DEFAULT),
            'avatar' => $this->randomBool() ? 'avatar_' . $this->randomString(8) . '.jpg' : null,
            'status' => $this->randomChoice(['active', 'inactive', 'suspended']),
            'preferences' => json_encode([
                'theme' => $this->randomChoice(['light', 'dark']),
                'language' => $this->randomChoice(['pt-BR', 'en-US', 'es-ES']),
                'notifications' => $this->randomBool(),
                'newsletter' => $this->randomBool()
            ]),
            'created_at' => $this->fakeDate('-1 year'),
            'updated_at' => $this->fakeDate('-1 month')
        ];
    }

    /**
     * Estado para usuário administrador
     */
    public function admin(): static
    {
        $this->defineState('admin', [
            'name' => 'Administrador',
            'email' => 'admin@example.com',
            'status' => 'active',
            'email_verified_at' => date('Y-m-d H:i:s'),
            'preferences' => json_encode([
                'theme' => 'dark',
                'language' => 'pt-BR',
                'notifications' => true,
                'newsletter' => false
            ])
        ]);
        
        return $this->state('admin');
    }

    /**
     * Estado para usuário inativo
     */
    public function inactive(): static
    {
        $this->defineState('inactive', [
            'status' => 'inactive',
            'email_verified_at' => null
        ]);
        
        return $this->state('inactive');
    }

    /**
     * Estado para usuário verificado
     */
    public function verified(): static
    {
        $this->defineState('verified', [
            'email_verified_at' => $this->fakeDate('-7 days'),
            'status' => 'active'
        ]);
        
        return $this->state('verified');
    }

    /**
     * Estado para usuário com avatar
     */
    public function withAvatar(): static
    {
        $this->defineState('with_avatar', [
            'avatar' => 'avatar_' . $this->randomString(10) . '.jpg'
        ]);
        
        return $this->state('with_avatar');
    }

    /**
     * Gera usuário com email específico
     */
    public function withEmail(string $email): static
    {
        $this->defineState('custom_email', [
            'email' => $email
        ]);
        
        return $this->state('custom_email');
    }

    /**
     * Gera usuário com nome específico
     */
    public function withName(string $name): static
    {
        $this->defineState('custom_name', [
            'name' => $name
        ]);
        
        return $this->state('custom_name');
    }

    /**
     * Sobrescreve o método fakeName para nomes brasileiros
     */
    protected function fakeName(): string
    {
        $maleNames = [
            'João', 'José', 'Pedro', 'Paulo', 'Carlos', 'Ricardo', 'Roberto',
            'Antonio', 'Francisco', 'Marcos', 'Rafael', 'Daniel', 'Bruno',
            'Felipe', 'Lucas', 'Gabriel', 'Mateus', 'André', 'Thiago', 'Diego'
        ];
        
        $femaleNames = [
            'Maria', 'Ana', 'Carla', 'Lucia', 'Fernanda', 'Juliana', 'Patricia',
            'Sandra', 'Cristina', 'Adriana', 'Camila', 'Beatriz', 'Larissa',
            'Mariana', 'Renata', 'Vanessa', 'Priscila', 'Amanda', 'Leticia', 'Bruna'
        ];
        
        $lastNames = [
            'Silva', 'Santos', 'Oliveira', 'Souza', 'Rodrigues', 'Ferreira',
            'Alves', 'Pereira', 'Lima', 'Gomes', 'Costa', 'Ribeiro', 'Martins',
            'Carvalho', 'Almeida', 'Lopes', 'Soares', 'Fernandes', 'Vieira',
            'Barbosa', 'Rocha', 'Dias', 'Monteiro', 'Mendes', 'Freitas'
        ];
        
        $allNames = array_merge($maleNames, $femaleNames);
        $firstName = $this->randomChoice($allNames);
        $lastName = $this->randomChoice($lastNames);
        
        // Às vezes adiciona um nome do meio
        if ($this->randomBool()) {
            $middleName = $this->randomChoice($lastNames);
            return "{$firstName} {$middleName} {$lastName}";
        }
        
        return "{$firstName} {$lastName}";
    }

    /**
     * Gera email brasileiro
     */
    protected function fakeEmail(): string
    {
        $domains = [
            'gmail.com', 'hotmail.com', 'yahoo.com.br', 'outlook.com',
            'uol.com.br', 'terra.com.br', 'ig.com.br', 'bol.com.br'
        ];
        
        $username = strtolower(str_replace(' ', '.', $this->fakeName()));
        $username = preg_replace('/[^a-z0-9.]/', '', $username);
        $username = substr($username, 0, 15); // Limita o tamanho
        
        // Adiciona números se necessário
        if ($this->randomBool()) {
            $username .= $this->randomInt(1, 999);
        }
        
        $domain = $this->randomChoice($domains);
        
        return "{$username}@{$domain}";
    }
}