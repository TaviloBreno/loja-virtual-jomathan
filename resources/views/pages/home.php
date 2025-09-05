<?php
// Configurações da página
$title = 'Início - Sistema PHP';
$currentRoute = '/';
$breadcrumbs = [];
$showDemo = true; // Para mostrar mensagens flash de demonstração

// Dados de exemplo para o dashboard
$stats = [
    [
        'title' => 'Total de Usuários',
        'value' => '1,234',
        'change' => '+12%',
        'changeType' => 'positive',
        'icon' => 'fas fa-users',
        'color' => 'blue'
    ],
    [
        'title' => 'Vendas do Mês',
        'value' => 'R$ 45.678',
        'change' => '+8%',
        'changeType' => 'positive',
        'icon' => 'fas fa-chart-line',
        'color' => 'green'
    ],
    [
        'title' => 'Pedidos Pendentes',
        'value' => '23',
        'change' => '-5%',
        'changeType' => 'negative',
        'icon' => 'fas fa-clock',
        'color' => 'yellow'
    ],
    [
        'title' => 'Taxa de Conversão',
        'value' => '3.2%',
        'change' => '+0.5%',
        'changeType' => 'positive',
        'icon' => 'fas fa-percentage',
        'color' => 'purple'
    ]
];

$recentActivities = [
    [
        'user' => 'João Silva',
        'action' => 'criou um novo usuário',
        'target' => 'Maria Santos',
        'time' => '5 minutos atrás',
        'icon' => 'fas fa-user-plus',
        'color' => 'green'
    ],
    [
        'user' => 'Ana Costa',
        'action' => 'atualizou o relatório',
        'target' => 'Vendas Q4',
        'time' => '15 minutos atrás',
        'icon' => 'fas fa-edit',
        'color' => 'blue'
    ],
    [
        'user' => 'Carlos Lima',
        'action' => 'fez backup do sistema',
        'target' => '',
        'time' => '1 hora atrás',
        'icon' => 'fas fa-database',
        'color' => 'purple'
    ],
    [
        'user' => 'Sistema',
        'action' => 'executou manutenção automática',
        'target' => '',
        'time' => '2 horas atrás',
        'icon' => 'fas fa-cog',
        'color' => 'gray'
    ]
];

$quickActions = [
    [
        'title' => 'Novo Usuário',
        'description' => 'Cadastrar um novo usuário no sistema',
        'url' => '/users/create',
        'icon' => 'fas fa-user-plus',
        'color' => 'blue'
    ],
    [
        'title' => 'Relatório',
        'description' => 'Gerar relatório de vendas',
        'url' => '/reports/sales',
        'icon' => 'fas fa-chart-bar',
        'color' => 'green'
    ],
    [
        'title' => 'Backup',
        'description' => 'Fazer backup do banco de dados',
        'url' => '/backup',
        'icon' => 'fas fa-download',
        'color' => 'purple'
    ],
    [
        'title' => 'Configurações',
        'description' => 'Ajustar configurações do sistema',
        'url' => '/settings',
        'icon' => 'fas fa-cog',
        'color' => 'gray'
    ]
];

// Conteúdo da página
ob_start();
?>

<!-- Hero Section -->
<div class="bg-gradient-to-r from-primary-600 to-primary-800 rounded-xl shadow-lg p-8 mb-8 text-white">
    <div class="flex flex-col md:flex-row items-center justify-between">
        <div class="mb-6 md:mb-0">
            <h1 class="text-3xl md:text-4xl font-bold mb-2">Bem-vindo ao Sistema PHP!</h1>
            <p class="text-primary-100 text-lg">Gerencie seus dados de forma eficiente e moderna</p>
        </div>
        <div class="flex-shrink-0">
            <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                <i class="fas fa-rocket text-4xl text-white"></i>
            </div>
        </div>
    </div>
</div>

<!-- Estatísticas -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <?php foreach ($stats as $stat): ?>
        <div class="bg-white rounded-lg shadow-sm border border-secondary-200 p-6 hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-secondary-600 mb-1"><?= htmlspecialchars($stat['title']) ?></p>
                    <p class="text-2xl font-bold text-secondary-900"><?= htmlspecialchars($stat['value']) ?></p>
                    <div class="flex items-center mt-2">
                        <span class="text-sm font-medium <?= $stat['changeType'] === 'positive' ? 'text-green-600' : 'text-red-600' ?>">
                            <i class="fas fa-arrow-<?= $stat['changeType'] === 'positive' ? 'up' : 'down' ?> mr-1"></i>
                            <?= htmlspecialchars($stat['change']) ?>
                        </span>
                        <span class="text-sm text-secondary-500 ml-2">vs mês anterior</span>
                    </div>
                </div>
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-<?= $stat['color'] ?>-100 rounded-lg flex items-center justify-center">
                        <i class="<?= $stat['icon'] ?> text-<?= $stat['color'] ?>-600 text-xl"></i>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Conteúdo Principal -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Ações Rápidas -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-sm border border-secondary-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-secondary-900">Ações Rápidas</h2>
                <i class="fas fa-bolt text-primary-600"></i>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <?php foreach ($quickActions as $action): ?>
                    <a href="<?= htmlspecialchars($action['url']) ?>" 
                       class="group p-4 border border-secondary-200 rounded-lg hover:border-<?= $action['color'] ?>-300 hover:shadow-md transition-all duration-200">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-<?= $action['color'] ?>-100 group-hover:bg-<?= $action['color'] ?>-200 rounded-lg flex items-center justify-center transition-colors duration-200">
                                    <i class="<?= $action['icon'] ?> text-<?= $action['color'] ?>-600"></i>
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-medium text-secondary-900 group-hover:text-<?= $action['color'] ?>-700 transition-colors duration-200">
                                    <?= htmlspecialchars($action['title']) ?>
                                </h3>
                                <p class="text-sm text-secondary-500 mt-1">
                                    <?= htmlspecialchars($action['description']) ?>
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="fas fa-chevron-right text-secondary-400 group-hover:text-<?= $action['color'] ?>-600 transition-colors duration-200"></i>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Gráfico de Exemplo -->
        <div class="bg-white rounded-lg shadow-sm border border-secondary-200 p-6 mt-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-secondary-900">Vendas dos Últimos 7 Dias</h2>
                <div class="flex space-x-2">
                    <button class="text-sm text-secondary-500 hover:text-primary-600 transition-colors">7 dias</button>
                    <button class="text-sm text-primary-600 font-medium">30 dias</button>
                    <button class="text-sm text-secondary-500 hover:text-primary-600 transition-colors">90 dias</button>
                </div>
            </div>
            
            <!-- Placeholder para gráfico -->
            <div class="h-64 bg-secondary-50 rounded-lg flex items-center justify-center">
                <div class="text-center">
                    <i class="fas fa-chart-line text-4xl text-secondary-300 mb-4"></i>
                    <p class="text-secondary-500">Gráfico será implementado aqui</p>
                    <p class="text-sm text-secondary-400 mt-1">Chart.js, D3.js ou similar</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Atividades Recentes -->
    <div>
        <div class="bg-white rounded-lg shadow-sm border border-secondary-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-secondary-900">Atividades Recentes</h2>
                <a href="/activities" class="text-sm text-primary-600 hover:text-primary-700 transition-colors">Ver todas</a>
            </div>
            
            <div class="space-y-4">
                <?php foreach ($recentActivities as $activity): ?>
                    <div class="flex items-start space-x-3">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-<?= $activity['color'] ?>-100 rounded-full flex items-center justify-center">
                                <i class="<?= $activity['icon'] ?> text-<?= $activity['color'] ?>-600 text-xs"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-secondary-900">
                                <span class="font-medium"><?= htmlspecialchars($activity['user']) ?></span>
                                <?= htmlspecialchars($activity['action']) ?>
                                <?php if ($activity['target']): ?>
                                    <span class="font-medium"><?= htmlspecialchars($activity['target']) ?></span>
                                <?php endif; ?>
                            </p>
                            <p class="text-xs text-secondary-500 mt-1"><?= htmlspecialchars($activity['time']) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Status do Sistema -->
        <div class="bg-white rounded-lg shadow-sm border border-secondary-200 p-6 mt-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-secondary-900">Status do Sistema</h2>
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-sm text-green-600 font-medium">Online</span>
                </div>
            </div>
            
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-secondary-600">Servidor Web</span>
                    <span class="text-sm font-medium text-green-600">✓ Funcionando</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-secondary-600">Banco de Dados</span>
                    <span class="text-sm font-medium text-green-600">✓ Conectado</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-secondary-600">Cache</span>
                    <span class="text-sm font-medium text-green-600">✓ Ativo</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-secondary-600">Último Backup</span>
                    <span class="text-sm font-medium text-secondary-900">Hoje, 03:00</span>
                </div>
            </div>
        </div>
        
        <!-- Links Úteis -->
        <div class="bg-white rounded-lg shadow-sm border border-secondary-200 p-6 mt-6">
            <h2 class="text-lg font-semibold text-secondary-900 mb-4">Links Úteis</h2>
            
            <div class="space-y-2">
                <a href="/docs" class="flex items-center space-x-2 text-sm text-secondary-600 hover:text-primary-600 transition-colors">
                    <i class="fas fa-book text-xs"></i>
                    <span>Documentação</span>
                </a>
                <a href="/help" class="flex items-center space-x-2 text-sm text-secondary-600 hover:text-primary-600 transition-colors">
                    <i class="fas fa-question-circle text-xs"></i>
                    <span>Central de Ajuda</span>
                </a>
                <a href="/contact" class="flex items-center space-x-2 text-sm text-secondary-600 hover:text-primary-600 transition-colors">
                    <i class="fas fa-envelope text-xs"></i>
                    <span>Suporte</span>
                </a>
                <a href="/changelog" class="flex items-center space-x-2 text-sm text-secondary-600 hover:text-primary-600 transition-colors">
                    <i class="fas fa-history text-xs"></i>
                    <span>Novidades</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Scripts específicos da página -->
<script>
    // Atualizar estatísticas em tempo real (simulação)
    function updateStats() {
        // Simular atualizações das estatísticas
        console.log('Atualizando estatísticas...');
    }
    
    // Atualizar a cada 30 segundos
    setInterval(updateStats, 30000);
    
    // Animações de entrada
    document.addEventListener('DOMContentLoaded', function() {
        // Animar cards de estatísticas
        const statCards = document.querySelectorAll('.grid > div');
        statCards.forEach((card, index) => {
            setTimeout(() => {
                card.classList.add('animate-fade-in');
            }, index * 100);
        });
    });
</script>

<?php
$content = ob_get_clean();

// Usar o sistema de layout do ViewRenderer
$this->extends('layouts/app');
$this->section('content');
echo $content;
$this->endSection();
?>