<?php
// Configurações da página
$pageTitle = 'Visualizar Usuário';
$pageDescription = 'Detalhes do usuário';
$breadcrumbs = [
    ['label' => 'Dashboard', 'url' => '/'],
    ['label' => 'Usuários', 'url' => '/users'],
    ['label' => 'Visualizar Usuário', 'url' => '/users/' . $user->id, 'active' => true]
];

// Dados padrão se não fornecidos
$user = $user ?? new stdClass();
$userStats = $userStats ?? [];
$recentActivities = $recentActivities ?? [];

// Incluir layout
include __DIR__ . '/../../layouts/app.php';
?>

<!-- Conteúdo específico da página -->
<div class="user-show-page" x-data="userShowPage()">
    <!-- Header da página -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0 h-16 w-16">
                        <div class="h-16 w-16 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center shadow-lg">
                            <span class="text-xl font-bold text-white">
                                <?= strtoupper(substr($user->name ?? 'U', 0, 2)) ?>
                            </span>
                        </div>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                            <?= htmlspecialchars($user->name ?? 'Usuário') ?>
                        </h1>
                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                            <?= htmlspecialchars($user->email ?? 'email@exemplo.com') ?>
                        </p>
                        <div class="mt-2 flex items-center space-x-2">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                <?php 
                                switch($user->status ?? 'inactive') {
                                    case 'active':
                                        echo 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100';
                                        break;
                                    case 'inactive':
                                        echo 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100';
                                        break;
                                    case 'pending':
                                        echo 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100';
                                        break;
                                    default:
                                        echo 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100';
                                }
                                ?>">
                                <?= ucfirst($user->status ?? 'Inativo') ?>
                            </span>
                            <?php if (isset($user->email_verified_at) && $user->email_verified_at): ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">
                                    <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    E-mail Verificado
                                </span>
                            <?php else: ?>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100">
                                    <svg class="mr-1 h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    E-mail Não Verificado
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="mt-4 sm:mt-0 flex space-x-3">
                    <a href="/users/<?= $user->id ?? '' ?>/edit" 
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Editar
                    </a>
                    <button @click="toggleStatus" 
                            :disabled="isUpdating"
                            :class="{
                                'bg-green-600 hover:bg-green-700': user.status === 'inactive',
                                'bg-red-600 hover:bg-red-700': user.status === 'active',
                                'opacity-50 cursor-not-allowed': isUpdating
                            }"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2">
                        <svg x-show="isUpdating" class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span x-text="user.status === 'active' ? 'Desativar' : 'Ativar'"></span>
                    </button>
                    <a href="/users" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Voltar para Lista
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Conteúdo Principal -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Coluna Principal -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Informações Básicas -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Informações Básicas
                        </h3>
                    </div>
                    <div class="px-6 py-4">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Nome Completo</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                    <?= htmlspecialchars($user->name ?? 'N/A') ?>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">E-mail</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                    <a href="mailto:<?= htmlspecialchars($user->email ?? '') ?>" 
                                       class="text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300">
                                        <?= htmlspecialchars($user->email ?? 'N/A') ?>
                                    </a>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                <dd class="mt-1">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        <?php 
                                        switch($user->status ?? 'inactive') {
                                            case 'active':
                                                echo 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100';
                                                break;
                                            case 'inactive':
                                                echo 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100';
                                                break;
                                            case 'pending':
                                                echo 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100';
                                                break;
                                            default:
                                                echo 'bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-100';
                                        }
                                        ?>">
                                        <?= ucfirst($user->status ?? 'Inativo') ?>
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">ID do Usuário</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">
                                    #<?= $user->id ?? 'N/A' ?>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Data de Cadastro</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                    <?= isset($user->created_at) ? date('d/m/Y H:i', strtotime($user->created_at)) : 'N/A' ?>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Última Atualização</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                    <?= isset($user->updated_at) ? date('d/m/Y H:i', strtotime($user->updated_at)) : 'N/A' ?>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">E-mail Verificado</dt>
                                <dd class="mt-1">
                                    <?php if (isset($user->email_verified_at) && $user->email_verified_at): ?>
                                        <span class="inline-flex items-center text-sm text-green-600 dark:text-green-400">
                                            <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Sim, em <?= date('d/m/Y H:i', strtotime($user->email_verified_at)) ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center text-sm text-red-600 dark:text-red-400">
                                            <svg class="mr-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                            </svg>
                                            Não verificado
                                        </span>
                                    <?php endif; ?>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Último Login</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                    <?= isset($user->last_login_at) ? date('d/m/Y H:i', strtotime($user->last_login_at)) : 'Nunca' ?>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Atividades Recentes -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Atividades Recentes
                        </h3>
                    </div>
                    <div class="px-6 py-4">
                        <?php if (!empty($recentActivities)): ?>
                            <div class="flow-root">
                                <ul class="-mb-8">
                                    <?php foreach ($recentActivities as $index => $activity): ?>
                                        <li>
                                            <div class="relative pb-8">
                                                <?php if ($index < count($recentActivities) - 1): ?>
                                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-600" aria-hidden="true"></span>
                                                <?php endif; ?>
                                                <div class="relative flex space-x-3">
                                                    <div>
                                                        <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white dark:ring-gray-800
                                                            <?php 
                                                            switch($activity['type'] ?? 'info') {
                                                                case 'login':
                                                                    echo 'bg-green-500';
                                                                    break;
                                                                case 'update':
                                                                    echo 'bg-blue-500';
                                                                    break;
                                                                case 'error':
                                                                    echo 'bg-red-500';
                                                                    break;
                                                                default:
                                                                    echo 'bg-gray-500';
                                                            }
                                                            ?>">
                                                            <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                                <?php 
                                                                switch($activity['type'] ?? 'info') {
                                                                    case 'login':
                                                                        echo '<path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>';
                                                                        break;
                                                                    case 'update':
                                                                        echo '<path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"></path>';
                                                                        break;
                                                                    case 'error':
                                                                        echo '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>';
                                                                        break;
                                                                    default:
                                                                        echo '<path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>';
                                                                }
                                                                ?>
                                                            </svg>
                                                        </span>
                                                    </div>
                                                    <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                                        <div>
                                                            <p class="text-sm text-gray-900 dark:text-white">
                                                                <?= htmlspecialchars($activity['description'] ?? 'Atividade') ?>
                                                            </p>
                                                            <?php if (!empty($activity['details'])): ?>
                                                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                                                    <?= htmlspecialchars($activity['details']) ?>
                                                                </p>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="text-right text-sm whitespace-nowrap text-gray-500 dark:text-gray-400">
                                                            <time datetime="<?= $activity['created_at'] ?? '' ?>">
                                                                <?= isset($activity['created_at']) ? date('d/m H:i', strtotime($activity['created_at'])) : 'N/A' ?>
                                                            </time>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">Nenhuma atividade</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    Este usuário ainda não possui atividades registradas.
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Estatísticas -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Estatísticas
                        </h3>
                    </div>
                    <div class="px-6 py-4">
                        <dl class="space-y-4">
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Total de Logins</dt>
                                <dd class="text-sm text-gray-900 dark:text-white font-semibold">
                                    <?= $userStats['total_logins'] ?? '0' ?>
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Sessões Ativas</dt>
                                <dd class="text-sm text-gray-900 dark:text-white font-semibold">
                                    <?= $userStats['active_sessions'] ?? '0' ?>
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Ações Realizadas</dt>
                                <dd class="text-sm text-gray-900 dark:text-white font-semibold">
                                    <?= $userStats['total_actions'] ?? '0' ?>
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Tempo Online</dt>
                                <dd class="text-sm text-gray-900 dark:text-white font-semibold">
                                    <?= $userStats['time_online'] ?? '0h' ?>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Ações Rápidas -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Ações Rápidas
                        </h3>
                    </div>
                    <div class="px-6 py-4 space-y-3">
                        <button @click="sendPasswordReset" 
                                :disabled="isUpdating"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m0 0a2 2 0 012 2m-2-2a2 2 0 00-2 2m2-2V5a2 2 0 00-2-2H9a2 2 0 00-2 2v2m2-2a2 2 0 012 2m0 0V9a2 2 0 012-2m-2 2a2 2 0 00-2-2m2 2h2m-6 4v6m-2-6h4m-2 0h.01"></path>
                            </svg>
                            Redefinir Senha
                        </button>
                        
                        <?php if (!isset($user->email_verified_at) || !$user->email_verified_at): ?>
                            <button @click="verifyEmail" 
                                    :disabled="isUpdating"
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Verificar E-mail
                            </button>
                        <?php endif; ?>
                        
                        <button @click="logoutAllSessions" 
                                :disabled="isUpdating"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Desconectar Sessões
                        </button>
                        
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-3">
                            <button @click="confirmDelete" 
                                    class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 dark:border-red-600 rounded-md shadow-sm text-sm font-medium text-red-700 dark:text-red-400 bg-white dark:bg-gray-700 hover:bg-red-50 dark:hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Excluir Usuário
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Informações do Sistema -->
                <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Informações do Sistema
                        </h3>
                    </div>
                    <div class="px-6 py-4">
                        <dl class="space-y-3">
                            <div>
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">IP do Último Login</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white font-mono">
                                    <?= $user->last_login_ip ?? 'N/A' ?>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">User Agent</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white break-all">
                                    <?= htmlspecialchars($user->last_user_agent ?? 'N/A') ?>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">Timezone</dt>
                                <dd class="mt-1 text-sm text-gray-900 dark:text-white">
                                    <?= $user->timezone ?? 'UTC' ?>
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div x-show="showDeleteModal" 
         x-transition:enter="ease-out duration-300" 
         x-transition:enter-start="opacity-0" 
         x-transition:enter-end="opacity-100" 
         x-transition:leave="ease-in duration-200" 
         x-transition:leave-start="opacity-100" 
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity z-50" 
         style="display: none;">
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div x-show="showDeleteModal" 
                     x-transition:enter="ease-out duration-300" 
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave="ease-in duration-200" 
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" 
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
                    <div class="sm:flex sm:items-start">
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"></path>
                            </svg>
                        </div>
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-gray-900 dark:text-white">
                                Confirmar Exclusão
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Tem certeza que deseja excluir o usuário <strong><?= htmlspecialchars($user->name ?? '') ?></strong>? 
                                    Esta ação não pode ser desfeita e todos os dados relacionados serão permanentemente removidos.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
                        <button @click="deleteUser" 
                                :disabled="isDeleting"
                                :class="{'opacity-50 cursor-not-allowed': isDeleting}"
                                class="inline-flex w-full justify-center rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto">
                            <svg x-show="isDeleting" class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="isDeleting ? 'Excluindo...' : 'Excluir'"></span>
                        </button>
                        <button @click="showDeleteModal = false" 
                                type="button" 
                                class="mt-3 inline-flex w-full justify-center rounded-md bg-white dark:bg-gray-700 px-3 py-2 text-sm font-semibold text-gray-900 dark:text-gray-300 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 sm:mt-0 sm:w-auto">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts específicos da página -->
<script>
function userShowPage() {
    return {
        user: {
            id: <?= json_encode($user->id ?? null) ?>,
            name: <?= json_encode($user->name ?? '') ?>,
            email: <?= json_encode($user->email ?? '') ?>,
            status: <?= json_encode($user->status ?? 'inactive') ?>
        },
        
        isUpdating: false,
        isDeleting: false,
        showDeleteModal: false,
        
        async toggleStatus() {
            if (this.isUpdating) return;
            
            this.isUpdating = true;
            
            try {
                const response = await fetch(`/users/${this.user.id}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.user.status = data.status;
                    this.showNotification('Status atualizado com sucesso!', 'success');
                } else {
                    this.showNotification(data.message || 'Erro ao atualizar status', 'error');
                }
            } catch (error) {
                console.error('Erro:', error);
                this.showNotification('Erro ao atualizar status', 'error');
            } finally {
                this.isUpdating = false;
            }
        },
        
        async sendPasswordReset() {
            if (this.isUpdating) return;
            
            this.isUpdating = true;
            
            try {
                const response = await fetch(`/users/${this.user.id}/password-reset`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showNotification('E-mail de redefinição de senha enviado!', 'success');
                } else {
                    this.showNotification(data.message || 'Erro ao enviar e-mail', 'error');
                }
            } catch (error) {
                console.error('Erro:', error);
                this.showNotification('Erro ao enviar e-mail', 'error');
            } finally {
                this.isUpdating = false;
            }
        },
        
        async verifyEmail() {
            if (this.isUpdating) return;
            
            this.isUpdating = true;
            
            try {
                const response = await fetch(`/users/${this.user.id}/verify-email`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showNotification('E-mail verificado com sucesso!', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    this.showNotification(data.message || 'Erro ao verificar e-mail', 'error');
                }
            } catch (error) {
                console.error('Erro:', error);
                this.showNotification('Erro ao verificar e-mail', 'error');
            } finally {
                this.isUpdating = false;
            }
        },
        
        async logoutAllSessions() {
            if (this.isUpdating) return;
            
            this.isUpdating = true;
            
            try {
                const response = await fetch(`/users/${this.user.id}/logout-sessions`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showNotification('Todas as sessões foram desconectadas!', 'success');
                } else {
                    this.showNotification(data.message || 'Erro ao desconectar sessões', 'error');
                }
            } catch (error) {
                console.error('Erro:', error);
                this.showNotification('Erro ao desconectar sessões', 'error');
            } finally {
                this.isUpdating = false;
            }
        },
        
        confirmDelete() {
            this.showDeleteModal = true;
        },
        
        async deleteUser() {
            if (this.isDeleting) return;
            
            this.isDeleting = true;
            
            try {
                const response = await fetch(`/users/${this.user.id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.showNotification('Usuário excluído com sucesso!', 'success');
                    setTimeout(() => {
                        window.location.href = '/users';
                    }, 1500);
                } else {
                    this.showNotification(data.message || 'Erro ao excluir usuário', 'error');
                    this.showDeleteModal = false;
                }
            } catch (error) {
                console.error('Erro:', error);
                this.showNotification('Erro ao excluir usuário', 'error');
                this.showDeleteModal = false;
            } finally {
                this.isDeleting = false;
            }
        },
        
        showNotification(message, type = 'info') {
            // Implementar sistema de notificações
            console.log(`${type.toUpperCase()}: ${message}`);
            
            // Exemplo simples com alert (substituir por toast/notification)
            if (type === 'error') {
                alert(`Erro: ${message}`);
            } else {
                alert(message);
            }
        }
    }
}
</script>

<!-- Estilos específicos da página -->
<style>
.user-show-page {
    min-height: calc(100vh - 4rem);
}

.user-show-page .activity-timeline {
    position: relative;
}

.user-show-page .activity-timeline::before {
    content: '';
    position: absolute;
    left: 1rem;
    top: 0;
    bottom: 0;
    width: 2px;
    background: linear-gradient(to bottom, #e5e7eb, transparent);
}

@media (max-width: 1024px) {
    .user-show-page .grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 640px) {
    .user-show-page .user-header {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .user-show-page .user-actions {
        width: 100%;
        justify-content: stretch;
    }
    
    .user-show-page .user-actions > * {
        flex: 1;
    }
}
</style>