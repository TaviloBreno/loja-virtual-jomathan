<?php
// Configurações da página
$pageTitle = 'Editar Usuário';
$pageDescription = 'Editar informações do usuário';
$breadcrumbs = [
    ['label' => 'Dashboard', 'url' => '/'],
    ['label' => 'Usuários', 'url' => '/users'],
    ['label' => 'Editar Usuário', 'url' => '/users/' . $user->id . '/edit', 'active' => true]
];

// Dados padrão se não fornecidos
$user = $user ?? new stdClass();
$statusOptions = $statusOptions ?? [];

// Incluir layout
include __DIR__ . '/../../layouts/app.php';
?>

<!-- Conteúdo específico da página -->
<div class="user-edit-page" x-data="userEditForm()">
    <!-- Header da página -->
    <div class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                        <?= htmlspecialchars($pageTitle) ?>
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Editando: <?= htmlspecialchars($user->name ?? 'Usuário') ?>
                    </p>
                </div>
                <div class="mt-4 sm:mt-0 flex space-x-3">
                    <a href="/users/<?= $user->id ?? '' ?>" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Visualizar
                    </a>
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

    <!-- Formulário -->
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg">
            <form method="POST" action="/users/<?= $user->id ?? '' ?>" @submit="validateForm" class="space-y-6 p-6">
                <input type="hidden" name="_method" value="PUT">
                
                <!-- Informações do Usuário -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0 h-16 w-16">
                            <div class="h-16 w-16 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                <span class="text-xl font-medium text-gray-700 dark:text-gray-300">
                                    <?= strtoupper(substr($user->name ?? 'U', 0, 2)) ?>
                                </span>
                            </div>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">
                                <?= htmlspecialchars($user->name ?? 'Usuário') ?>
                            </h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                ID: <?= $user->id ?? 'N/A' ?> • 
                                Criado em: <?= isset($user->created_at) ? date('d/m/Y H:i', strtotime($user->created_at)) : 'N/A' ?>
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
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informações Básicas -->
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                        Informações Básicas
                    </h3>
                    
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <!-- Nome -->
                        <div class="sm:col-span-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Nome Completo *
                            </label>
                            <div class="mt-1 relative">
                                <input type="text" 
                                       id="name" 
                                       name="name" 
                                       x-model="form.name"
                                       @blur="validateField('name')"
                                       required
                                       value="<?= htmlspecialchars($user->name ?? '') ?>"
                                       class="block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                       :class="{'border-red-300 dark:border-red-600': errors.name}"
                                       placeholder="Digite o nome completo">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none" x-show="errors.name">
                                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <p x-show="errors.name" x-text="errors.name" class="mt-2 text-sm text-red-600 dark:text-red-400"></p>
                        </div>

                        <!-- E-mail -->
                        <div class="sm:col-span-2">
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                E-mail *
                            </label>
                            <div class="mt-1 relative">
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       x-model="form.email"
                                       @blur="validateField('email')"
                                       required
                                       value="<?= htmlspecialchars($user->email ?? '') ?>"
                                       class="block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm"
                                       :class="{'border-red-300 dark:border-red-600': errors.email}"
                                       placeholder="usuario@exemplo.com">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none" x-show="errors.email">
                                    <svg class="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <p x-show="errors.email" x-text="errors.email" class="mt-2 text-sm text-red-600 dark:text-red-400"></p>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Status
                            </label>
                            <select id="status" 
                                    name="status" 
                                    x-model="form.status"
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm">
                                <?php foreach ($statusOptions as $value => $label): ?>
                                    <option value="<?= htmlspecialchars($value) ?>" <?= ($user->status ?? '') === $value ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($label) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Última atividade -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Última Atividade
                            </label>
                            <div class="mt-1 text-sm text-gray-900 dark:text-white bg-gray-50 dark:bg-gray-700 rounded-md px-3 py-2">
                                <?= isset($user->updated_at) ? date('d/m/Y H:i', strtotime($user->updated_at)) : 'Nunca' ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alterar Senha -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Alterar Senha
                        </h3>
                        <button type="button" 
                                @click="showPasswordSection = !showPasswordSection"
                                class="text-sm text-blue-600 dark:text-blue-400 hover:text-blue-500 dark:hover:text-blue-300">
                            <span x-text="showPasswordSection ? 'Cancelar alteração' : 'Alterar senha'"></span>
                        </button>
                    </div>
                    
                    <div x-show="showPasswordSection" x-transition class="space-y-4">
                        <div class="bg-yellow-50 dark:bg-yellow-900 border border-yellow-200 dark:border-yellow-700 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                                        Atenção
                                    </h3>
                                    <div class="mt-2 text-sm text-yellow-700 dark:text-yellow-300">
                                        <p>Deixe os campos de senha em branco se não quiser alterá-la.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Nova Senha -->
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Nova Senha
                                </label>
                                <div class="mt-1 relative">
                                    <input :type="showPassword ? 'text' : 'password'" 
                                           id="password" 
                                           name="password" 
                                           x-model="form.password"
                                           @blur="validateField('password')"
                                           class="block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm pr-10"
                                           :class="{'border-red-300 dark:border-red-600': errors.password}"
                                           placeholder="Deixe em branco para manter atual">
                                    <button type="button" 
                                            @click="showPassword = !showPassword"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <svg x-show="!showPassword" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <svg x-show="showPassword" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                        </svg>
                                    </button>
                                </div>
                                <p x-show="errors.password" x-text="errors.password" class="mt-2 text-sm text-red-600 dark:text-red-400"></p>
                                <div class="mt-2" x-show="form.password">
                                    <div class="flex items-center space-x-2 text-xs">
                                        <div class="flex-1 bg-gray-200 dark:bg-gray-600 rounded-full h-1">
                                            <div class="h-1 rounded-full transition-all duration-300" 
                                                 :class="{
                                                     'bg-red-500 w-1/4': passwordStrength === 'weak',
                                                     'bg-yellow-500 w-2/4': passwordStrength === 'medium',
                                                     'bg-green-500 w-3/4': passwordStrength === 'strong',
                                                     'bg-green-600 w-full': passwordStrength === 'very-strong'
                                                 }"></div>
                                        </div>
                                        <span class="text-gray-500 dark:text-gray-400" x-text="passwordStrengthLabel"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Confirmar Nova Senha -->
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Confirmar Nova Senha
                                </label>
                                <div class="mt-1 relative">
                                    <input :type="showPasswordConfirmation ? 'text' : 'password'" 
                                           id="password_confirmation" 
                                           name="password_confirmation" 
                                           x-model="form.password_confirmation"
                                           @blur="validateField('password_confirmation')"
                                           class="block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white sm:text-sm pr-10"
                                           :class="{'border-red-300 dark:border-red-600': errors.password_confirmation}"
                                           placeholder="Repita a nova senha">
                                    <button type="button" 
                                            @click="showPasswordConfirmation = !showPasswordConfirmation"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <svg x-show="!showPasswordConfirmation" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <svg x-show="showPasswordConfirmation" class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                                        </svg>
                                    </button>
                                </div>
                                <p x-show="errors.password_confirmation" x-text="errors.password_confirmation" class="mt-2 text-sm text-red-600 dark:text-red-400"></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Configurações Adicionais -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">
                        Configurações Adicionais
                    </h3>
                    
                    <div class="space-y-4">
                        <!-- Verificar E-mail -->
                        <?php if (!isset($user->email_verified_at) || !$user->email_verified_at): ?>
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="verify_email" 
                                           name="verify_email" 
                                           type="checkbox" 
                                           x-model="form.verify_email"
                                           class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="verify_email" class="font-medium text-gray-700 dark:text-gray-300">
                                        Marcar e-mail como verificado
                                    </label>
                                    <p class="text-gray-500 dark:text-gray-400">
                                        O e-mail será marcado como verificado sem envio de confirmação.
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <!-- Notificar alterações -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="notify_changes" 
                                       name="notify_changes" 
                                       type="checkbox" 
                                       x-model="form.notify_changes"
                                       checked
                                       class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="notify_changes" class="font-medium text-gray-700 dark:text-gray-300">
                                    Notificar usuário sobre alterações
                                </label>
                                <p class="text-gray-500 dark:text-gray-400">
                                    Envia um e-mail informando sobre as alterações realizadas.
                                </p>
                            </div>
                        </div>

                        <!-- Forçar logout -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input id="force_logout" 
                                       name="force_logout" 
                                       type="checkbox" 
                                       x-model="form.force_logout"
                                       class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700">
                            </div>
                            <div class="ml-3 text-sm">
                                <label for="force_logout" class="font-medium text-gray-700 dark:text-gray-300">
                                    Forçar logout em todas as sessões
                                </label>
                                <p class="text-gray-500 dark:text-gray-400">
                                    O usuário será desconectado de todas as sessões ativas.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botões de Ação -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <div class="flex justify-end space-x-3">
                        <a href="/users" 
                           class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Cancelar
                        </a>
                        
                        <button type="button" 
                                @click="resetForm"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                            Resetar
                        </button>
                        
                        <button type="submit" 
                                :disabled="!isFormValid || isSubmitting"
                                :class="{
                                    'opacity-50 cursor-not-allowed': !isFormValid || isSubmitting,
                                    'hover:bg-blue-700': isFormValid && !isSubmitting
                                }"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg x-show="isSubmitting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <svg x-show="!isSubmitting" class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span x-text="isSubmitting ? 'Salvando...' : 'Salvar Alterações'"></span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts específicos da página -->
<script>
function userEditForm() {
    return {
        form: {
            name: '<?= htmlspecialchars($user->name ?? '', ENT_QUOTES) ?>',
            email: '<?= htmlspecialchars($user->email ?? '', ENT_QUOTES) ?>',
            password: '',
            password_confirmation: '',
            status: '<?= htmlspecialchars($user->status ?? 'active', ENT_QUOTES) ?>',
            verify_email: false,
            notify_changes: true,
            force_logout: false
        },
        
        originalForm: {
            name: '<?= htmlspecialchars($user->name ?? '', ENT_QUOTES) ?>',
            email: '<?= htmlspecialchars($user->email ?? '', ENT_QUOTES) ?>',
            status: '<?= htmlspecialchars($user->status ?? 'active', ENT_QUOTES) ?>'
        },
        
        errors: {},
        
        showPassword: false,
        showPasswordConfirmation: false,
        showPasswordSection: false,
        isSubmitting: false,
        
        get passwordStrength() {
            const password = this.form.password;
            if (!password) return '';
            
            let score = 0;
            
            // Comprimento
            if (password.length >= 8) score++;
            if (password.length >= 12) score++;
            
            // Complexidade
            if (/[a-z]/.test(password)) score++;
            if (/[A-Z]/.test(password)) score++;
            if (/[0-9]/.test(password)) score++;
            if (/[^A-Za-z0-9]/.test(password)) score++;
            
            if (score <= 2) return 'weak';
            if (score <= 4) return 'medium';
            if (score <= 5) return 'strong';
            return 'very-strong';
        },
        
        get passwordStrengthLabel() {
            switch (this.passwordStrength) {
                case 'weak': return 'Fraca';
                case 'medium': return 'Média';
                case 'strong': return 'Forte';
                case 'very-strong': return 'Muito Forte';
                default: return '';
            }
        },
        
        get isFormValid() {
            const hasBasicFields = this.form.name.trim() && this.form.email.trim();
            const hasPasswordError = this.form.password && (this.form.password.length < 8 || this.form.password !== this.form.password_confirmation);
            const hasErrors = Object.keys(this.errors).length > 0;
            
            return hasBasicFields && !hasPasswordError && !hasErrors;
        },
        
        validateField(field) {
            this.errors[field] = '';
            
            switch (field) {
                case 'name':
                    if (!this.form.name.trim()) {
                        this.errors.name = 'Nome é obrigatório';
                    } else if (this.form.name.trim().length < 2) {
                        this.errors.name = 'Nome deve ter pelo menos 2 caracteres';
                    }
                    break;
                    
                case 'email':
                    if (!this.form.email.trim()) {
                        this.errors.email = 'E-mail é obrigatório';
                    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.form.email)) {
                        this.errors.email = 'E-mail deve ter um formato válido';
                    }
                    break;
                    
                case 'password':
                    if (this.form.password && this.form.password.length < 8) {
                        this.errors.password = 'Senha deve ter pelo menos 8 caracteres';
                    }
                    
                    // Revalidar confirmação se já foi preenchida
                    if (this.form.password_confirmation) {
                        this.validateField('password_confirmation');
                    }
                    break;
                    
                case 'password_confirmation':
                    if (this.form.password && this.form.password !== this.form.password_confirmation) {
                        this.errors.password_confirmation = 'Senhas não coincidem';
                    }
                    break;
            }
        },
        
        validateForm(event) {
            // Validar todos os campos
            this.validateField('name');
            this.validateField('email');
            
            if (this.form.password) {
                this.validateField('password');
                this.validateField('password_confirmation');
            }
            
            // Se houver erros, impedir envio
            if (!this.isFormValid) {
                event.preventDefault();
                return false;
            }
            
            this.isSubmitting = true;
            return true;
        },
        
        resetForm() {
            this.form.name = this.originalForm.name;
            this.form.email = this.originalForm.email;
            this.form.status = this.originalForm.status;
            this.form.password = '';
            this.form.password_confirmation = '';
            this.form.verify_email = false;
            this.form.notify_changes = true;
            this.form.force_logout = false;
            this.errors = {};
            this.showPassword = false;
            this.showPasswordConfirmation = false;
            this.showPasswordSection = false;
        }
    }
}
</script>

<!-- Estilos específicos da página -->
<style>
.user-edit-page {
    min-height: calc(100vh - 4rem);
}

.user-edit-page input:focus,
.user-edit-page select:focus,
.user-edit-page textarea:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.user-edit-page .password-strength-indicator {
    transition: all 0.3s ease;
}

@media (max-width: 640px) {
    .user-edit-page .form-grid {
        grid-template-columns: 1fr;
    }
}
</style>