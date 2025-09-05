<?php

use App\Infrastructure\Database\Seeder;
use Database\Factories\UserFactory;

/**
 * UserSeeder - Seeder para popular tabela de usuários
 * Demonstra o uso do sistema de seeders com factories
 */
class UserSeeder extends Seeder
{
    /**
     * Executa o seeder
     */
    public function run(): void
    {
        $this->info('Iniciando UserSeeder...');
        
        // Verifica se a tabela existe
        if (!$this->tableExists('users')) {
            $this->info('Tabela users não encontrada. Execute as migrações primeiro.');
            return;
        }
        
        // Limpa dados existentes (opcional)
        if ($this->count('users') > 0) {
            $this->info('Limpando dados existentes da tabela users...');
            $this->delete('users');
        }
        
        // Cria usuário administrador
        $this->info('Criando usuário administrador...');
        $adminData = UserFactory::new()->admin()->make();
        $adminId = $this->insert('users', $adminData);
        $this->info("Administrador criado com ID: {$adminId}");
        
        // Cria usuários de teste específicos
        $this->info('Criando usuários de teste...');
        $testUsers = [
            UserFactory::new()->withName('João Silva')->withEmail('joao@test.com')->verified()->make(),
            UserFactory::new()->withName('Maria Santos')->withEmail('maria@test.com')->verified()->withAvatar()->make(),
            UserFactory::new()->withName('Pedro Oliveira')->withEmail('pedro@test.com')->inactive()->make()
        ];
        
        foreach ($testUsers as $userData) {
            $userId = $this->insert('users', $userData);
            $this->info("Usuário de teste criado: {$userData['name']} (ID: {$userId})");
        }
        
        // Cria usuários aleatórios em lotes
        $this->info('Criando usuários aleatórios...');
        $batchSize = 50;
        $totalUsers = 200;
        $batches = ceil($totalUsers / $batchSize);
        
        for ($batch = 1; $batch <= $batches; $batch++) {
            $currentBatchSize = ($batch === $batches) ? ($totalUsers % $batchSize) ?: $batchSize : $batchSize;
            
            $users = [];
            for ($i = 0; $i < $currentBatchSize; $i++) {
                // Varia os tipos de usuários
                $factory = UserFactory::new();
                
                // 70% usuários ativos verificados
                if ($this->randomInt(1, 100) <= 70) {
                    $factory = $factory->verified();
                }
                
                // 20% usuários inativos
                elseif ($this->randomInt(1, 100) <= 20) {
                    $factory = $factory->inactive();
                }
                
                // 30% com avatar
                if ($this->randomInt(1, 100) <= 30) {
                    $factory = $factory->withAvatar();
                }
                
                $users[] = $factory->make();
            }
            
            $this->insertBatch('users', $users);
            $this->info("Lote {$batch}/{$batches} inserido ({$currentBatchSize} usuários)");
        }
        
        // Estatísticas finais
        $totalCount = $this->count('users');
        $activeCount = $this->count('users', ['status' => 'active']);
        $verifiedCount = $this->count('users', ['status' => 'active']);
        
        $this->info('=== Estatísticas Finais ===');
        $this->info("Total de usuários: {$totalCount}");
        $this->info("Usuários ativos: {$activeCount}");
        $this->info("Usuários verificados: {$verifiedCount}");
        
        $this->info('UserSeeder executado com sucesso!');
    }

    /**
     * Cria usuários específicos para desenvolvimento
     */
    private function createDevelopmentUsers(): void
    {
        $devUsers = [
            [
                'name' => 'Desenvolvedor',
                'email' => 'dev@localhost.com',
                'password' => password_hash('dev123', PASSWORD_DEFAULT),
                'status' => 'active',
                'email_verified_at' => $this->now(),
                'preferences' => json_encode([
                    'theme' => 'dark',
                    'language' => 'pt-BR',
                    'notifications' => true,
                    'newsletter' => false
                ]),
                'created_at' => $this->now(),
                'updated_at' => $this->now()
            ],
            [
                'name' => 'Testador',
                'email' => 'test@localhost.com',
                'password' => password_hash('test123', PASSWORD_DEFAULT),
                'status' => 'active',
                'email_verified_at' => $this->now(),
                'preferences' => json_encode([
                    'theme' => 'light',
                    'language' => 'pt-BR',
                    'notifications' => false,
                    'newsletter' => true
                ]),
                'created_at' => $this->now(),
                'updated_at' => $this->now()
            ]
        ];
        
        foreach ($devUsers as $user) {
            $this->insert('users', $user);
            $this->info("Usuário de desenvolvimento criado: {$user['name']}");
        }
    }

    /**
     * Atualiza estatísticas dos usuários
     */
    private function updateUserStats(): void
    {
        // Simula algumas atividades dos usuários
        $users = $this->find('users', ['status' => 'active'], 50);
        
        foreach ($users as $user) {
            // Atualiza data de último acesso aleatoriamente
            if ($this->randomBool()) {
                $lastLogin = $this->randomDate('-30 days');
                $this->update('users', 
                    ['updated_at' => $lastLogin], 
                    ['id' => $user['id']]
                );
            }
        }
        
        $this->info('Estatísticas dos usuários atualizadas.');
    }
}