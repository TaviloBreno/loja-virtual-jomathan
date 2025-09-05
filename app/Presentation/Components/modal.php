<?php
/**
 * Componente Modal - Paleta Neon Futurista
 * 
 * Variantes disponíveis:
 * - default: Modal padrão com fundo escuro
 * - neon: Modal com bordas neon e efeitos
 * - primary: Modal com tema roxo elétrico
 * - glass: Efeito glassmorphism
 */

$id = $id ?? 'modal';
$title = $title ?? '';
$variant = $variant ?? 'default';
$size = $size ?? 'md';
$closable = $closable ?? true;
$backdrop = $backdrop ?? true;
$content = $content ?? $slot ?? '';
$footer = $footer ?? '';
$class = $class ?? '';

// Tamanhos do modal
$sizes = [
    'sm' => 'max-w-md',
    'md' => 'max-w-lg', 
    'lg' => 'max-w-2xl',
    'xl' => 'max-w-4xl',
    'full' => 'max-w-7xl'
];

// Variantes
$variants = [
    'default' => 'bg-dark-800 border border-dark-600',
    'neon' => 'bg-dark-800 border-2 border-neon-500 shadow-neon-modal',
    'primary' => 'bg-dark-800 border-2 border-primary-500 shadow-primary-modal',
    'glass' => 'bg-white/10 border border-white/20 backdrop-blur-xl',
    'dark' => 'bg-dark-900 border border-dark-700'
];

$modalClasses = implode(' ', [
    'relative w-full mx-4 rounded-2xl text-white transform transition-all duration-300',
    $variants[$variant] ?? $variants['default'],
    $sizes[$size] ?? $sizes['md'],
    $class
]);
?>

<!-- Modal Backdrop -->
<div 
    id="<?= htmlspecialchars($id) ?>"
    class="fixed inset-0 z-50 hidden overflow-y-auto"
    x-data="{ open: false }"
    x-show="open"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
>
    <!-- Backdrop -->
    <?php if ($backdrop): ?>
        <div 
            class="fixed inset-0 bg-black/80 backdrop-blur-sm"
            <?php if ($closable): ?>
                @click="open = false"
            <?php endif; ?>
        ></div>
    <?php endif; ?>
    
    <!-- Modal Container -->
    <div class="flex min-h-full items-center justify-center p-4">
        <!-- Modal Content -->
        <div 
            class="<?= $modalClasses ?>"
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            @click.stop
        >
            <!-- Header -->
            <?php if ($title || $closable): ?>
                <div class="flex items-center justify-between p-6 border-b border-dark-600">
                    <?php if ($title): ?>
                        <h3 class="text-xl font-bold text-white">
                            <?= htmlspecialchars($title) ?>
                        </h3>
                    <?php endif; ?>
                    
                    <?php if ($closable): ?>
                        <button 
                            @click="open = false"
                            class="text-gray-400 hover:text-white transition-colors duration-200 p-2 hover:bg-dark-700 rounded-lg"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <!-- Body -->
            <div class="p-6">
                <?= $content ?>
            </div>
            
            <!-- Footer -->
            <?php if ($footer): ?>
                <div class="flex items-center justify-end space-x-3 p-6 border-t border-dark-600">
                    <?= $footer ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* Efeitos de glow para modais */
.shadow-neon-modal {
    box-shadow: 
        0 0 30px rgba(57, 255, 20, 0.3),
        0 20px 60px rgba(0, 0, 0, 0.5);
}

.shadow-primary-modal {
    box-shadow: 
        0 0 30px rgba(124, 58, 237, 0.3),
        0 20px 60px rgba(0, 0, 0, 0.5);
}
</style>

<script>
// Funções JavaScript para controlar o modal
function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        modal.__x.$data.open = true;
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.__x.$data.open = false;
        setTimeout(() => {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }, 200);
    }
}

// Fechar modal com ESC
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const openModals = document.querySelectorAll('[id*="modal"]:not(.hidden)');
        openModals.forEach(modal => {
            if (modal.__x && modal.__x.$data.open) {
                closeModal(modal.id);
            }
        });
    }
});
</script>