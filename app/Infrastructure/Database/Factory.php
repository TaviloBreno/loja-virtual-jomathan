<?php

namespace App\Infrastructure\Database;

/**
 * Factory - Classe base para factories de dados fake
 * Implementa padrão Factory Method para geração de dados
 */
abstract class Factory
{
    protected array $states = [];
    protected int $count = 1;
    protected array $afterCreating = [];
    
    /**
     * Define a estrutura dos dados fake
     */
    abstract public function definition(): array;

    /**
     * Cria instância da factory
     */
    public static function new(): static
    {
        return new static();
    }

    /**
     * Define quantos registros criar
     */
    public function count(int $count): static
    {
        $this->count = $count;
        return $this;
    }

    /**
     * Aplica um estado específico
     */
    public function state(string $state, array $attributes = []): static
    {
        if (!isset($this->states[$state])) {
            throw new \Exception("Estado '{$state}' não definido na factory");
        }
        
        $this->states[$state] = array_merge($this->states[$state], $attributes);
        return $this;
    }

    /**
     * Define callback para executar após criação
     */
    public function afterCreating(callable $callback): static
    {
        $this->afterCreating[] = $callback;
        return $this;
    }

    /**
     * Gera os dados fake
     */
    public function make(): array
    {
        $results = [];
        
        for ($i = 0; $i < $this->count; $i++) {
            $data = $this->definition();
            
            // Aplica estados
            foreach ($this->states as $stateData) {
                $data = array_merge($data, $stateData);
            }
            
            $results[] = $data;
        }
        
        return $this->count === 1 ? $results[0] : $results;
    }

    /**
     * Cria e insere no banco de dados
     */
    public function create(string $table): array
    {
        $data = $this->make();
        
        if ($this->count === 1) {
            $id = $this->insertRecord($table, $data);
            $data['id'] = $id;
            
            // Executa callbacks
            foreach ($this->afterCreating as $callback) {
                $callback($data);
            }
            
            return $data;
        }
        
        $results = [];
        foreach ($data as $record) {
            $id = $this->insertRecord($table, $record);
            $record['id'] = $id;
            
            // Executa callbacks
            foreach ($this->afterCreating as $callback) {
                $callback($record);
            }
            
            $results[] = $record;
        }
        
        return $results;
    }

    /**
     * Insere um registro no banco
     */
    private function insertRecord(string $table, array $data): int
    {
        $columns = array_keys($data);
        $placeholders = implode(',', array_fill(0, count($columns), '?'));
        
        $sql = "INSERT INTO {$table} (" . implode(',', $columns) . ") VALUES ({$placeholders})";
        
        DatabaseManager::execute($sql, array_values($data));
        
        return (int) DatabaseManager::getConnection()->lastInsertId();
    }

    /**
     * Define um estado
     */
    protected function defineState(string $name, array $attributes): void
    {
        $this->states[$name] = $attributes;
    }

    // Métodos helper para geração de dados fake
    
    /**
     * Gera nome fake
     */
    protected function fakeName(): string
    {
        $firstNames = [
            'João', 'Maria', 'José', 'Ana', 'Pedro', 'Carla', 'Paulo', 'Lucia',
            'Carlos', 'Fernanda', 'Ricardo', 'Juliana', 'Roberto', 'Patricia',
            'Antonio', 'Sandra', 'Francisco', 'Cristina', 'Marcos', 'Adriana'
        ];
        
        $lastNames = [
            'Silva', 'Santos', 'Oliveira', 'Souza', 'Rodrigues', 'Ferreira',
            'Alves', 'Pereira', 'Lima', 'Gomes', 'Costa', 'Ribeiro', 'Martins',
            'Carvalho', 'Almeida', 'Lopes', 'Soares', 'Fernandes', 'Vieira', 'Barbosa'
        ];
        
        $firstName = $this->randomChoice($firstNames);
        $lastName = $this->randomChoice($lastNames);
        
        return "{$firstName} {$lastName}";
    }

    /**
     * Gera email fake
     */
    protected function fakeEmail(): string
    {
        $domains = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 'example.com'];
        $username = strtolower($this->randomString(8));
        $domain = $this->randomChoice($domains);
        
        return "{$username}@{$domain}";
    }

    /**
     * Gera telefone fake
     */
    protected function fakePhone(): string
    {
        $ddd = str_pad(mt_rand(11, 99), 2, '0', STR_PAD_LEFT);
        $number = str_pad(mt_rand(10000000, 999999999), 9, '0', STR_PAD_LEFT);
        
        return "({$ddd}) {$number}";
    }

    /**
     * Gera CPF fake
     */
    protected function fakeCpf(): string
    {
        $cpf = '';
        for ($i = 0; $i < 9; $i++) {
            $cpf .= mt_rand(0, 9);
        }
        
        // Calcula dígitos verificadores
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += intval($cpf[$i]) * (10 - $i);
        }
        $digit1 = 11 - ($sum % 11);
        if ($digit1 >= 10) $digit1 = 0;
        
        $sum = 0;
        for ($i = 0; $i < 9; $i++) {
            $sum += intval($cpf[$i]) * (11 - $i);
        }
        $sum += $digit1 * 2;
        $digit2 = 11 - ($sum % 11);
        if ($digit2 >= 10) $digit2 = 0;
        
        $cpf .= $digit1 . $digit2;
        
        return substr($cpf, 0, 3) . '.' . substr($cpf, 3, 3) . '.' . substr($cpf, 6, 3) . '-' . substr($cpf, 9, 2);
    }

    /**
     * Gera endereço fake
     */
    protected function fakeAddress(): string
    {
        $streets = [
            'Rua das Flores', 'Avenida Brasil', 'Rua da Paz', 'Avenida Paulista',
            'Rua do Comércio', 'Avenida Central', 'Rua São João', 'Avenida Atlântica'
        ];
        
        $street = $this->randomChoice($streets);
        $number = mt_rand(1, 9999);
        
        return "{$street}, {$number}";
    }

    /**
     * Gera cidade fake
     */
    protected function fakeCity(): string
    {
        $cities = [
            'São Paulo', 'Rio de Janeiro', 'Belo Horizonte', 'Salvador',
            'Brasília', 'Fortaleza', 'Curitiba', 'Recife', 'Porto Alegre',
            'Manaus', 'Belém', 'Goiânia', 'Campinas', 'São Luís'
        ];
        
        return $this->randomChoice($cities);
    }

    /**
     * Gera texto fake
     */
    protected function fakeText(int $sentences = 3): string
    {
        $words = [
            'lorem', 'ipsum', 'dolor', 'sit', 'amet', 'consectetur', 'adipiscing',
            'elit', 'sed', 'do', 'eiusmod', 'tempor', 'incididunt', 'ut', 'labore',
            'et', 'dolore', 'magna', 'aliqua', 'enim', 'ad', 'minim', 'veniam'
        ];
        
        $text = '';
        for ($i = 0; $i < $sentences; $i++) {
            $sentenceLength = mt_rand(5, 15);
            $sentence = [];
            
            for ($j = 0; $j < $sentenceLength; $j++) {
                $sentence[] = $this->randomChoice($words);
            }
            
            $text .= ucfirst(implode(' ', $sentence)) . '. ';
        }
        
        return trim($text);
    }

    /**
     * Gera data fake
     */
    protected function fakeDate(string $start = '-1 year', string $end = 'now'): string
    {
        $startTimestamp = strtotime($start);
        $endTimestamp = strtotime($end);
        
        $randomTimestamp = mt_rand($startTimestamp, $endTimestamp);
        
        return date('Y-m-d H:i:s', $randomTimestamp);
    }

    /**
     * Gera string aleatória
     */
    protected function randomString(int $length = 10): string
    {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $string = '';
        
        for ($i = 0; $i < $length; $i++) {
            $string .= $characters[mt_rand(0, strlen($characters) - 1)];
        }
        
        return $string;
    }

    /**
     * Escolhe elemento aleatório do array
     */
    protected function randomChoice(array $choices)
    {
        return $choices[array_rand($choices)];
    }

    /**
     * Gera número aleatório
     */
    protected function randomInt(int $min = 1, int $max = 100): int
    {
        return mt_rand($min, $max);
    }

    /**
     * Gera boolean aleatório
     */
    protected function randomBool(): bool
    {
        return (bool) mt_rand(0, 1);
    }

    /**
     * Gera decimal aleatório
     */
    protected function randomFloat(float $min = 0, float $max = 100, int $decimals = 2): float
    {
        $value = $min + mt_rand() / mt_getrandmax() * ($max - $min);
        return round($value, $decimals);
    }
}