<?php
/**
 * Componente Modal
 * 
 * Props:
 * - id: string (ID único do modal)
 * - title: string
 * - size: 'xs', 'sm', 'md', 'lg', 'xl', '2xl', '3xl', 'full'
 * - position: 'center', 'top', 'bottom'
 * - backdrop: 'static', 'clickable', 'none'
 * - closable: boolean (mostra botão X)
 * - keyboard: boolean (fecha com ESC)
 * - animation: 'fade', 'slide', 'zoom', 'none'
 * - persistent: boolean (não fecha automaticamente)
 * - class: string (classes adicionais)
 * - headerClass: string
 * - bodyClass: string
 * - footerClass: string
 * - overlayClass: string
 * - showHeader: boolean
 * - showFooter: boolean
 * - actions: array (botões de ação)
 * - onOpen: string (callback JS)
 * - onClose: string (callback JS)
 * - onConfirm: string (callback JS)
 * - onCancel: string (callback JS)
 */

$id = $id ?? uniqid('modal_');
$title = $title ?? '';
$size = $size ?? 'md';
$position = $position ?? 'center';
$backdrop = $backdrop ?? 'clickable';
$closable = $closable ?? true;
$keyboard = $keyboard ?? true;
$animation = $animation ?? 'fade';
$persistent = $persistent ?? false;
$class = $class ?? '';
$headerClass = $headerClass ?? '';
$bodyClass = $bodyClass ?? '';
$footerClass = $footerClass ?? '';
$overlayClass = $overlayClass ?? '';
$showHeader = $showHeader ?? true;
$showFooter = $showFooter ?? false;
$actions = $actions ?? [];
$onOpen = $onOpen ?? null;
$onClose = $onClose ?? null;
$onConfirm = $onConfirm ?? null;
$onCancel = $onCancel ?? null;
$content = $content ?? slot('default', '');
$header = $header ?? slot('header', '');
$footer = $footer ?? slot('footer', '');

// Classes de tamanho
$sizeClasses = [
    'xs' => 'max-w-xs',
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
    '3xl' => 'max-w-3xl',
    'full' => 'max-w-full mx-4'
];

// Classes de posição
$positionClasses = [
    'center' => 'items-center justify-center',
    'top' => 'items-start justify-center pt-16',
    'bottom' => 'items-end justify-center pb-16'
];

// Classes de animação
$animationClasses = [
    'fade' => [
        'enter' => 'transition-opacity duration-300',
        'enter-from' => 'opacity-0',
        'enter-to' => 'opacity-100',
        'leave' => 'transition-opacity duration-200',
        'leave-from' => 'opacity-100',
        'leave-to' => 'opacity-0'
    ],
    'slide' => [
        'enter' => 'transition-all duration-300',
        'enter-from' => 'opacity-0 transform translate-y-4 sm:translate-y-0 sm:scale-95',
        'enter-to' => 'opacity-100 transform translate-y-0 sm:scale-100',
        'leave' => 'transition-all duration-200',
        'leave-from' => 'opacity-100 transform translate-y-0 sm:scale-100',
        'leave-to' => 'opacity-0 transform translate-y-4 sm:translate-y-0 sm:scale-95'
    ],
    'zoom' => [
        'enter' => 'transition-all duration-300',
        'enter-from' => 'opacity-0 transform scale-50',
        'enter-to' => 'opacity-100 transform scale-100',
        'leave' => 'transition-all duration-200',
        'leave-from' => 'opacity-100 transform scale-100',
        'leave-to' => 'opacity-0 transform scale-50'
    ],
    'none' => [
        'enter' => '',
        'enter-from' => '',
        'enter-to' => '',
        'leave' => '',
        'leave-from' => '',
        'leave-to' => ''
    ]
];

// Classes do modal
$modalClasses = [
    'relative',
    'bg-white',
    'rounded-lg',
    'shadow-xl',
    'w-full',
    $sizeClasses[$size]
];

if ($class) {
    $modalClasses[] = $class;
}

$modalClassString = implode(' ', $modalClasses);

// Classes do overlay
$overlayClasses = [
    'fixed',
    'inset-0',
    'bg-black',
    'bg-opacity-50',
    'transition-opacity',
    'duration-300'
];

if ($overlayClass) {
    $overlayClasses[] = $overlayClass;
}

$overlayClassString = implode(' ', $overlayClasses);
?>

<!-- Modal Overlay -->
<div id="<?= $id ?>" 
     class="fixed inset-0 z-50 overflow-y-auto hidden"
     aria-labelledby="<?= $id ?>-title" 
     role="dialog" 
     aria-modal="true"
     data-modal-id="<?= $id ?>"
     data-backdrop="<?= $backdrop ?>"
     data-keyboard="<?= $keyboard ? 'true' : 'false' ?>"
     data-persistent="<?= $persistent ? 'true' : 'false' ?>">
    
    <!-- Background overlay -->
    <div class="<?= $overlayClassString ?>" 
         <?= $backdrop === 'clickable' ? "onclick=\"closeModal('$id')\"" : '' ?>></div>
    
    <!-- Modal container -->
    <div class="relative min-h-screen flex <?= $positionClasses[$position] ?> p-4">
        
        <!-- Modal content -->
        <div class="<?= $modalClassString ?>"
             onclick="event.stopPropagation()"
             data-animation="<?= $animation ?>">
            
            <?php if ($showHeader && ($header || $title || $closable)): ?>
                <!-- Modal header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200 <?= $headerClass ?>">
                    <div class="flex-1">
                        <?php if ($header): ?>
                            <?= $header ?>
                        <?php elseif ($title): ?>
                            <h3 class="text-lg font-semibold text-gray-900" id="<?= $id ?>-title">
                                <?= htmlspecialchars($title) ?>
                            </h3>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($closable): ?>
                        <button type="button" 
                                class="ml-4 text-gray-400 hover:text-gray-600 focus:outline-none focus:text-gray-600 transition-colors"
                                onclick="closeModal('<?= $id ?>')">
                            <span class="sr-only">Fechar</span>
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <!-- Modal body -->
            <div class="p-6 <?= $bodyClass ?>">
                <?= $content ?>
            </div>
            
            <?php if ($showFooter && ($footer || !empty($actions))): ?>
                <!-- Modal footer -->
                <div class="flex items-center justify-end px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg <?= $footerClass ?>">
                    <?php if ($footer): ?>
                        <?= $footer ?>
                    <?php endif; ?>
                    
                    <?php if (!empty($actions)): ?>
                        <div class="flex space-x-3 <?= $footer ? 'ml-4' : '' ?>">
                            <?php foreach ($actions as $action): ?>
                                <?php if (is_array($action)): ?>
                                    <?php 
                                    $actionType = $action['type'] ?? 'button';
                                    $actionText = $action['text'] ?? 'Action';
                                    $actionClass = $action['class'] ?? 'px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500';
                                    $actionOnclick = $action['onclick'] ?? null;
                                    $actionRole = $action['role'] ?? null; // 'confirm', 'cancel', 'close'
                                    ?>
                                    
                                    <button type="<?= $actionType ?>" 
                                            class="<?= $actionClass ?>"
                                            <?php if ($actionOnclick): ?>
                                                onclick="<?= $actionOnclick ?>"
                                            <?php elseif ($actionRole === 'confirm' && $onConfirm): ?>
                                                onclick="<?= $onConfirm ?>; closeModal('<?= $id ?>')"
                                            <?php elseif ($actionRole === 'cancel' && $onCancel): ?>
                                                onclick="<?= $onCancel ?>; closeModal('<?= $id ?>')"
                                            <?php elseif ($actionRole === 'close'): ?>
                                                onclick="closeModal('<?= $id ?>')"
                                            <?php endif; ?>>
                                        <?= htmlspecialchars($actionText) ?>
                                    </button>
                                <?php else: ?>
                                    <?= $action ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
// Funções globais do modal (se não existirem)
if (typeof window.modalFunctions === 'undefined') {
    window.modalFunctions = true;
    
    // Abrir modal
    window.openModal = function(modalId, options = {}) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        
        const animation = modal.querySelector('[data-animation]')?.dataset.animation || 'fade';
        const modalContent = modal.querySelector('.relative.bg-white');
        
        // Callback onOpen
        <?php if ($onOpen): ?>
        if (modalId === '<?= $id ?>') {
            <?= $onOpen ?>;
        }
        <?php endif; ?>
        
        // Mostrar modal
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        
        // Aplicar animação de entrada
        if (animation !== 'none' && modalContent) {
            modalContent.classList.add('opacity-0');
            if (animation === 'slide') {
                modalContent.classList.add('transform', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            } else if (animation === 'zoom') {
                modalContent.classList.add('transform', 'scale-50');
            }
            
            setTimeout(() => {
                modalContent.classList.remove('opacity-0');
                modalContent.classList.add('opacity-100');
                
                if (animation === 'slide') {
                    modalContent.classList.remove('translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
                    modalContent.classList.add('translate-y-0', 'sm:scale-100');
                } else if (animation === 'zoom') {
                    modalContent.classList.remove('scale-50');
                    modalContent.classList.add('scale-100');
                }
            }, 10);
        }
        
        // Focus no primeiro elemento focável
        setTimeout(() => {
            const focusable = modal.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
            if (focusable) focusable.focus();
        }, 100);
    };
    
    // Fechar modal
    window.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        
        const persistent = modal.dataset.persistent === 'true';
        if (persistent) return;
        
        const animation = modal.querySelector('[data-animation]')?.dataset.animation || 'fade';
        const modalContent = modal.querySelector('.relative.bg-white');
        
        // Callback onClose
        <?php if ($onClose): ?>
        if (modalId === '<?= $id ?>') {
            <?= $onClose ?>;
        }
        <?php endif; ?>
        
        // Aplicar animação de saída
        if (animation !== 'none' && modalContent) {
            modalContent.classList.remove('opacity-100');
            modalContent.classList.add('opacity-0');
            
            if (animation === 'slide') {
                modalContent.classList.remove('translate-y-0', 'sm:scale-100');
                modalContent.classList.add('translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            } else if (animation === 'zoom') {
                modalContent.classList.remove('scale-100');
                modalContent.classList.add('scale-50');
            }
            
            setTimeout(() => {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }, 200);
        } else {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }
    };
    
    // Event listeners globais
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const openModals = document.querySelectorAll('[data-modal-id]:not(.hidden)');
            openModals.forEach(modal => {
                const keyboard = modal.dataset.keyboard === 'true';
                if (keyboard) {
                    closeModal(modal.dataset.modalId);
                }
            });
        }
    });
    
    // Fechar modal clicando no backdrop
    document.addEventListener('click', function(e) {
        if (e.target.matches('[data-backdrop="clickable"]')) {
            const modalId = e.target.closest('[data-modal-id]')?.dataset.modalId;
            if (modalId) {
                closeModal(modalId);
            }
        }
    });
}
</script>