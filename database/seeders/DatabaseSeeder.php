<?php

use App\Infrastructure\Database\Seeder;

/**
 * DatabaseSeeder - Seeder principal
 * Orquestra a execução de todos os seeders do sistema
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Executa todos os seeders do sistema
     */
    public function run(): void
    {
        $this->info('=== Iniciando DatabaseSeeder ===');
        $this->info('Populando banco de dados com dados de exemplo...');
        
        $startTime = microtime(true);
        
        try {
            // Executa seeders em ordem específica
            $this->runSeedersInOrder();
            
            $endTime = microtime(true);
            $executionTime = round(($endTime - $startTime), 2);
            
            $this->info('=== DatabaseSeeder Concluído ===');
            $this->info("Tempo total de execução: {$executionTime} segundos");
            $this->showFinalStatistics();
            
        } catch (Exception $e) {
            $this->info('Erro durante execução dos seeders: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Executa os seeders na ordem correta
     */
    private function runSeedersInOrder(): void
    {
        $seeders = [
            'UserSeeder' => 'Populando usuários do sistema',
            // Adicione outros seeders aqui conforme necessário
            // 'PostSeeder' => 'Populando posts do blog',
            // 'CategorySeeder' => 'Populando categorias',
            // 'CommentSeeder' => 'Populando comentários',
        ];
        
        foreach ($seeders as $seederClass => $description) {
            $this->info("\n--- {$description} ---");
            
            if (class_exists($seederClass)) {
                $this->call($seederClass);
            } else {
                $this->info("Aviso: Seeder {$seederClass} não encontrado, pulando...");
            }
        }
    }

    /**
     * Mostra estatísticas finais do banco
     */
    private function showFinalStatistics(): void
    {
        $this->info('\n=== Estatísticas do Banco de Dados ===');
        
        $tables = $this->getPopulatedTables();
        
        foreach ($tables as $table) {
            if ($this->tableExists($table)) {
                $count = $this->count($table);
                $tableName = ucfirst($table);
                $this->info("{$tableName}: {$count} registros");
            }
        }
        
        $this->info('\nBanco de dados populado com sucesso!');
        $this->showQuickStartInfo();
    }

    /**
     * Lista das tabelas que devem ser populadas
     */
    private function getPopulatedTables(): array
    {
        return [
            'users',
            // Adicione outras tabelas conforme necessário
            // 'posts',
            // 'categories',
            // 'comments',
        ];
    }

    /**
     * Mostra informações de início rápido
     */
    private function showQuickStartInfo(): void
    {
        $this->info('\n=== Informações de Acesso ===');
        $this->info('Usuários de teste criados:');
        $this->info('• Administrador: admin@example.com / password123');
        $this->info('• Desenvolvedor: dev@localhost.com / dev123');
        $this->info('• Testador: test@localhost.com / test123');
        $this->info('• João Silva: joao@test.com / password123');
        $this->info('• Maria Santos: maria@test.com / password123');
        $this->info('');
        $this->info('Para acessar o sistema, use qualquer um dos emails acima.');
        $this->info('Todos os usuários de exemplo usam a senha padrão: password123');
    }

    /**
     * Executa seeders para ambiente de desenvolvimento
     */
    public function runDevelopment(): void
    {
        $this->info('=== Modo Desenvolvimento ===');
        $this->info('Criando dados mínimos para desenvolvimento...');
        
        // Apenas dados essenciais para desenvolvimento
        $this->call('UserSeeder');
        
        $this->info('Dados de desenvolvimento criados!');
    }

    /**
     * Executa seeders para ambiente de teste
     */
    public function runTesting(): void
    {
        $this->info('=== Modo Teste ===');
        $this->info('Criando dados para testes automatizados...');
        
        // Dados específicos para testes
        $this->createTestUsers();
        
        $this->info('Dados de teste criados!');
    }

    /**
     * Cria usuários específicos para testes
     */
    private function createTestUsers(): void
    {
        $testUsers = [
            [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => password_hash('testpass', PASSWORD_DEFAULT),
                'status' => 'active',
                'email_verified_at' => $this->now(),
                'created_at' => $this->now(),
                'updated_at' => $this->now()
            ],
            [
                'name' => 'Inactive User',
                'email' => 'inactive@example.com',
                'password' => password_hash('testpass', PASSWORD_DEFAULT),
                'status' => 'inactive',
                'email_verified_at' => null,
                'created_at' => $this->now(),
                'updated_at' => $this->now()
            ]
        ];
        
        foreach ($testUsers as $user) {
            $this->insert('users', $user);
        }
        
        $this->info('Usuários de teste criados.');
    }

    /**
     * Limpa e repovooa o banco (fresh seed)
     */
    public function fresh(): void
    {
        $this->info('=== Fresh Seed ===');
        $this->info('Limpando e repopulando banco de dados...');
        
        $tables = $this->getPopulatedTables();
        
        // Desabilita verificação de chaves estrangeiras
        $this->execute('SET FOREIGN_KEY_CHECKS = 0');
        
        // Limpa todas as tabelas
        foreach ($tables as $table) {
            if ($this->tableExists($table)) {
                $this->truncate($table);
                $this->info("Tabela {$table} limpa");
            }
        }
        
        // Reabilita verificação de chaves estrangeiras
        $this->execute('SET FOREIGN_KEY_CHECKS = 1');
        
        // Executa seeders novamente
        $this->run();
    }

    /**
     * Executa comando SQL diretamente
     */
    private function execute(string $sql): void
    {
        $this->db->getConnection()->exec($sql);
    }
}