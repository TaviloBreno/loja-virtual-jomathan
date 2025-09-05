<?php
// Simular mensagens flash (em um sistema real, viriam da sessão)
$flashMessages = $_SESSION['flash_messages'] ?? [];

// Limpar mensagens após exibir
if (isset($_SESSION['flash_messages'])) {
    unset($_SESSION['flash_messages']);
}
?>

<?php if (!empty($flashMessages)): ?>
    <div id="flash-messages" class="mb-6 space-y-3" x-data="{ messages: <?= json_encode($flashMessages) ?> }">
        <template x-for="(message, index) in messages" :key="index">
            <div x-show="message.visible !== false" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95 translate-y-2"
                 x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 transform scale-95 -translate-y-2"
                 class="flash-message rounded-lg p-4 shadow-md border-l-4 animate-slide-up"
                 :class="{
                     'bg-green-50 border-green-400 text-green-800': message.type === 'success',
                     'bg-red-50 border-red-400 text-red-800': message.type === 'error',
                     'bg-yellow-50 border-yellow-400 text-yellow-800': message.type === 'warning',
                     'bg-blue-50 border-blue-400 text-blue-800': message.type === 'info'
                 }"
                 x-init="setTimeout(() => { message.visible = false }, message.duration || 5000)">
                
                <div class="flex items-start">
                    <!-- Ícone -->
                    <div class="flex-shrink-0 mr-3">
                        <template x-if="message.type === 'success'">
                            <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-green-600 text-sm"></i>
                            </div>
                        </template>
                        
                        <template x-if="message.type === 'error'">
                            <div class="w-6 h-6 bg-red-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-times text-red-600 text-sm"></i>
                            </div>
                        </template>
                        
                        <template x-if="message.type === 'warning'">
                            <div class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-exclamation-triangle text-yellow-600 text-sm"></i>
                            </div>
                        </template>
                        
                        <template x-if="message.type === 'info'">
                            <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-info text-blue-600 text-sm"></i>
                            </div>
                        </template>
                    </div>
                    
                    <!-- Conteúdo -->
                    <div class="flex-1">
                        <!-- Título -->
                        <template x-if="message.title">
                            <h4 class="font-medium mb-1" x-text="message.title"></h4>
                        </template>
                        
                        <!-- Mensagem -->
                        <p class="text-sm" x-html="message.message"></p>
                        
                        <!-- Detalhes adicionais -->
                        <template x-if="message.details">
                            <div class="mt-2 text-xs opacity-75">
                                <details>
                                    <summary class="cursor-pointer hover:underline">Ver detalhes</summary>
                                    <div class="mt-1 p-2 bg-white bg-opacity-50 rounded text-xs font-mono" x-text="message.details"></div>
                                </details>
                            </div>
                        </template>
                        
                        <!-- Ações -->
                        <template x-if="message.actions && message.actions.length > 0">
                            <div class="mt-3 flex space-x-2">
                                <template x-for="action in message.actions" :key="action.label">
                                    <button @click="action.callback && action.callback()" 
                                            class="text-xs px-3 py-1 rounded font-medium transition-colors duration-200"
                                            :class="{
                                                'bg-green-200 hover:bg-green-300 text-green-800': message.type === 'success',
                                                'bg-red-200 hover:bg-red-300 text-red-800': message.type === 'error',
                                                'bg-yellow-200 hover:bg-yellow-300 text-yellow-800': message.type === 'warning',
                                                'bg-blue-200 hover:bg-blue-300 text-blue-800': message.type === 'info'
                                            }"
                                            x-text="action.label">
                                    </button>
                                </template>
                            </div>
                        </template>
                    </div>
                    
                    <!-- Botão Fechar -->
                    <div class="flex-shrink-0 ml-3">
                        <button @click="message.visible = false" 
                                class="inline-flex rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-200"
                                :class="{
                                    'text-green-500 hover:bg-green-100 focus:ring-green-600': message.type === 'success',
                                    'text-red-500 hover:bg-red-100 focus:ring-red-600': message.type === 'error',
                                    'text-yellow-500 hover:bg-yellow-100 focus:ring-yellow-600': message.type === 'warning',
                                    'text-blue-500 hover:bg-blue-100 focus:ring-blue-600': message.type === 'info'
                                }">
                            <i class="fas fa-times text-sm"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Barra de progresso (para mensagens com duração) -->
                <template x-if="message.showProgress !== false && (message.duration || 5000) > 0">
                    <div class="mt-2 w-full bg-white bg-opacity-30 rounded-full h-1 overflow-hidden">
                        <div class="h-full transition-all ease-linear"
                             :class="{
                                 'bg-green-400': message.type === 'success',
                                 'bg-red-400': message.type === 'error',
                                 'bg-yellow-400': message.type === 'warning',
                                 'bg-blue-400': message.type === 'info'
                             }"
                             x-init="
                                 const duration = message.duration || 5000;
                                 $el.style.width = '100%';
                                 setTimeout(() => {
                                     $el.style.transitionDuration = duration + 'ms';
                                     $el.style.width = '0%';
                                 }, 100);
                             ">
                        </div>
                    </div>
                </template>
            </div>
        </template>
    </div>
<?php endif; ?>

<!-- Mensagens de exemplo para demonstração (remover em produção) -->
<?php if (empty($flashMessages) && (isset($_GET['demo']) || isset($showDemo))): ?>
    <div class="mb-6 space-y-3">
        <!-- Sucesso -->
        <div class="flash-message bg-green-50 border-l-4 border-green-400 text-green-800 rounded-lg p-4 shadow-md animate-slide-up">
            <div class="flex items-start">
                <div class="flex-shrink-0 mr-3">
                    <div class="w-6 h-6 bg-green-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-check text-green-600 text-sm"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <h4 class="font-medium mb-1">Operação realizada com sucesso!</h4>
                    <p class="text-sm">O usuário foi cadastrado e um e-mail de confirmação foi enviado.</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="flex-shrink-0 ml-3 text-green-500 hover:bg-green-100 rounded-md p-1.5">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>
        
        <!-- Aviso -->
        <div class="flash-message bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 rounded-lg p-4 shadow-md animate-slide-up">
            <div class="flex items-start">
                <div class="flex-shrink-0 mr-3">
                    <div class="w-6 h-6 bg-yellow-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-yellow-600 text-sm"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <h4 class="font-medium mb-1">Atenção</h4>
                    <p class="text-sm">Alguns campos não foram preenchidos corretamente. Verifique os dados informados.</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="flex-shrink-0 ml-3 text-yellow-500 hover:bg-yellow-100 rounded-md p-1.5">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>
        
        <!-- Informação -->
        <div class="flash-message bg-blue-50 border-l-4 border-blue-400 text-blue-800 rounded-lg p-4 shadow-md animate-slide-up">
            <div class="flex items-start">
                <div class="flex-shrink-0 mr-3">
                    <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center">
                        <i class="fas fa-info text-blue-600 text-sm"></i>
                    </div>
                </div>
                <div class="flex-1">
                    <h4 class="font-medium mb-1">Informação</h4>
                    <p class="text-sm">O sistema será atualizado hoje às 22h. Pode haver indisponibilidade temporária.</p>
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="flex-shrink-0 ml-3 text-blue-500 hover:bg-blue-100 rounded-md p-1.5">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
    // Função global para adicionar mensagens flash via JavaScript
    window.addFlashMessage = function(type, message, options = {}) {
        const container = document.getElementById('flash-messages') || createFlashContainer();
        
        const messageData = {
            type: type,
            message: message,
            title: options.title || null,
            details: options.details || null,
            duration: options.duration || 5000,
            showProgress: options.showProgress !== false,
            actions: options.actions || [],
            visible: true
        };
        
        const messageElement = createMessageElement(messageData);
        container.appendChild(messageElement);
        
        // Auto-remove
        if (messageData.duration > 0) {
            setTimeout(() => {
                messageElement.remove();
            }, messageData.duration);
        }
        
        return messageElement;
    };
    
    function createFlashContainer() {
        const container = document.createElement('div');
        container.id = 'flash-messages';
        container.className = 'mb-6 space-y-3';
        
        const main = document.querySelector('main');
        if (main) {
            main.insertBefore(container, main.firstChild);
        } else {
            document.body.insertBefore(container, document.body.firstChild);
        }
        
        return container;
    }
    
    function createMessageElement(messageData) {
        const div = document.createElement('div');
        
        const colors = {
            success: 'bg-green-50 border-green-400 text-green-800',
            error: 'bg-red-50 border-red-400 text-red-800',
            warning: 'bg-yellow-50 border-yellow-400 text-yellow-800',
            info: 'bg-blue-50 border-blue-400 text-blue-800'
        };
        
        const icons = {
            success: 'fas fa-check text-green-600',
            error: 'fas fa-times text-red-600',
            warning: 'fas fa-exclamation-triangle text-yellow-600',
            info: 'fas fa-info text-blue-600'
        };
        
        div.className = `flash-message ${colors[messageData.type]} rounded-lg p-4 shadow-md border-l-4 animate-slide-up`;
        
        div.innerHTML = `
            <div class="flex items-start">
                <div class="flex-shrink-0 mr-3">
                    <div class="w-6 h-6 bg-${messageData.type === 'success' ? 'green' : messageData.type === 'error' ? 'red' : messageData.type === 'warning' ? 'yellow' : 'blue'}-100 rounded-full flex items-center justify-center">
                        <i class="${icons[messageData.type]} text-sm"></i>
                    </div>
                </div>
                <div class="flex-1">
                    ${messageData.title ? `<h4 class="font-medium mb-1">${messageData.title}</h4>` : ''}
                    <p class="text-sm">${messageData.message}</p>
                    ${messageData.details ? `
                        <div class="mt-2 text-xs opacity-75">
                            <details>
                                <summary class="cursor-pointer hover:underline">Ver detalhes</summary>
                                <div class="mt-1 p-2 bg-white bg-opacity-50 rounded text-xs font-mono">${messageData.details}</div>
                            </details>
                        </div>
                    ` : ''}
                </div>
                <button onclick="this.parentElement.parentElement.remove()" class="flex-shrink-0 ml-3 text-${messageData.type === 'success' ? 'green' : messageData.type === 'error' ? 'red' : messageData.type === 'warning' ? 'yellow' : 'blue'}-500 hover:bg-${messageData.type === 'success' ? 'green' : messageData.type === 'error' ? 'red' : messageData.type === 'warning' ? 'yellow' : 'blue'}-100 rounded-md p-1.5">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        `;
        
        return div;
    }
    
    // Atalhos para tipos específicos
    window.showSuccess = (message, options) => addFlashMessage('success', message, options);
    window.showError = (message, options) => addFlashMessage('error', message, options);
    window.showWarning = (message, options) => addFlashMessage('warning', message, options);
    window.showInfo = (message, options) => addFlashMessage('info', message, options);
</script>