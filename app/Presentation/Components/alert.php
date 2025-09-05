<?php
/**
 * Componente Alert - Paleta Neon Futurista
 * 
 * Variantes disponíveis:
 * - success: Verde de sucesso
 * - error: Vermelho de erro
 * - warning: Amarelo de aviso
 * - info: Azul informativo
 * - neon: Verde neon com efeito especial
 * - primary: Roxo elétrico
 */

$variant = $variant ?? 'info';
$title = $title ?? '';
$message = $message ?? $slot ?? '';
$dismissible = $dismissible ?? false;
$icon = $icon ?? null;
$actions = $actions ?? '';
$class = $class ?? '';
$id = $id ?? 'alert-' . uniqid();

// Ícones padrão por variante
$defaultIcons = [
    'success' => 'fas fa-check-circle',
    'error' => 'fas fa-exclamation-circle',
    'warning' => 'fas fa-exclamation-triangle',
    'info' => 'fas fa-info-circle',
    'neon' => 'fas fa-bolt',
    'primary' => 'fas fa-star'
];

$alertIcon = $icon ?? ($defaultIcons[$variant] ?? $defaultIcons['info']);

// Classes base
$baseClasses = 'relative rounded-xl border backdrop-blur-sm transition-all duration-300';

// Variantes de cor
$variants = [
    'success' => 'bg-green-500/10 border-green-500/30 text-green-300',
    'error' => 'bg-red-500/10 border-red-500/30 text-red-300',
    'warning' => 'bg-yellow-500/10 border-yellow-500/30 text-yellow-300',
    'info' => 'bg-blue-500/10 border-blue-500/30 text-blue-300',
    'neon' => 'bg-neon-500/10 border-neon-500/30 text-neon-300 shadow-neon-alert',
    'primary' => 'bg-primary-500/10 border-primary-500/30 text-primary-300 shadow-primary-alert'
];

// Classes de ícone por variante
$iconVariants = [
    'success' => 'text-green-400',
    'error' => 'text-red-400',
    'warning' => 'text-yellow-400',
    'info' => 'text-blue-400',
    'neon' => 'text-neon-400',
    'primary' => 'text-primary-400'
];

// Classes finais
$alertClasses = implode(' ', [
    $baseClasses,
    $variants[$variant] ?? $variants['info'],
    'p-4',
    $class
]);

$iconClasses = $iconVariants[$variant] ?? $iconVariants['info'];
?>

<div 
    id="<?= htmlspecialchars($id) ?>"
    class="<?= $alertClasses ?>"
    x-data="{ show: true }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform scale-95"
    x-transition:enter-end="opacity-100 transform scale-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform scale-100"
    x-transition:leave-end="opacity-0 transform scale-95"
>
    <div class="flex items-start">
        <!-- Ícone -->
        <div class="flex-shrink-0">
            <i class="<?= htmlspecialchars($alertIcon) ?> <?= $iconClasses ?> text-xl"></i>
        </div>
        
        <!-- Conteúdo -->
        <div class="ml-3 flex-1">
            <?php if ($title): ?>
                <h3 class="text-sm font-semibold mb-1">
                    <?= htmlspecialchars($title) ?>
                </h3>
            <?php endif; ?>
            
            <?php if ($message): ?>
                <div class="text-sm opacity-90">
                    <?= $message ?>
                </div>
            <?php endif; ?>
            
            <?php if ($actions): ?>
                <div class="mt-3">
                    <?= $actions ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Botão de fechar -->
        <?php if ($dismissible): ?>
            <div class="ml-4 flex-shrink-0">
                <button 
                    @click="show = false"
                    class="inline-flex text-gray-400 hover:text-white transition-colors duration-200 p-1 hover:bg-white/10 rounded-lg"
                    aria-label="Fechar alerta"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Efeitos de glow para alerts */
.shadow-neon-alert {
    box-shadow: 0 0 20px rgba(57, 255, 20, 0.1);
}

.shadow-primary-alert {
    box-shadow: 0 0 20px rgba(124, 58, 237, 0.1);
}

/* Animação de entrada para alerts */
@keyframes alert-slide-in {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

.alert-slide-in {
    animation: alert-slide-in 0.3s ease-out;
}
</style>

<script>
// Função para mostrar alert programaticamente
function showAlert(type, title, message, duration = 5000) {
    const alertId = 'alert-' + Date.now();
    const alertHTML = `
        <div id="${alertId}" class="fixed top-4 right-4 z-50 max-w-sm w-full">
            <!-- Alert content aqui -->
        </div>
    `;
    
    document.body.insertAdjacentHTML('beforeend', alertHTML);
    
    // Auto-remover após duration
    if (duration > 0) {
        setTimeout(() => {
            const alert = document.getElementById(alertId);
            if (alert) {
                alert.remove();
            }
        }, duration);
    }
}

// Função para fechar alert
function closeAlert(alertId) {
    const alert = document.getElementById(alertId);
    if (alert && alert.__x) {
        alert.__x.$data.show = false;
    }
}
</script>

<?php
/**
 * Exemplos de uso:
 * 
 * <!-- Alert simples -->
 * <?php 
 * $variant = 'success';
 * $message = 'Operação realizada com sucesso!';
 * include 'alert.php'; 
 * ?>
 * 
 * <!-- Alert com título -->
 * <?php 
 * $variant = 'error';
 * $title = 'Erro de validação';
 * $message = 'Verifique os campos obrigatórios.';
 * $dismissible = true;
 * include 'alert.php'; 
 * ?>
 * 
 * <!-- Alert neon com ações -->
 * <?php 
 * $variant = 'neon';
 * $title = 'Novidade!';
 * $message = 'Nova funcionalidade disponível.';
 * $actions = '<button class="text-neon-400 hover:text-neon-300 font-semibold">Ver mais</button>';
 * $dismissible = true;
 * include 'alert.php'; 
 * ?>
 */
?>