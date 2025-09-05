<?php
/**
 * Componente Button - Paleta Neon Futurista
 * 
 * Variantes disponíveis:
 * - primary: Roxo elétrico (#7C3AED)
 * - neon: Verde neon (#39FF14) 
 * - dark: Cinza médio (#2E2E2E)
 * - outline: Contorno roxo elétrico
 */

$variant = $variant ?? 'primary';
$size = $size ?? 'md';
$type = $type ?? 'button';
$href = $href ?? null;
$class = $class ?? '';
$disabled = $disabled ?? false;
$icon = $icon ?? null;
$iconPosition = $iconPosition ?? 'left';
$loading = $loading ?? false;
$text = $text ?? $slot ?? 'Button';

// Classes base
$baseClasses = 'inline-flex items-center justify-center font-semibold transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-4 disabled:opacity-50 disabled:cursor-not-allowed disabled:transform-none';

// Variantes de cor
$variants = [
    'primary' => 'bg-primary-500 text-white hover:bg-primary-600 focus:ring-primary-300 shadow-electric',
    'neon' => 'bg-neon-500 text-dark-950 hover:bg-neon-400 focus:ring-neon-300 shadow-neon animate-glow font-bold',
    'dark' => 'bg-dark-800 text-white border border-dark-600 hover:bg-dark-700 focus:ring-dark-500',
    'outline' => 'border-2 border-primary-500 text-primary-500 hover:bg-primary-500 hover:text-white focus:ring-primary-300 bg-transparent',
    'ghost' => 'text-primary-500 hover:bg-primary-500/10 focus:ring-primary-300 bg-transparent',
    'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-300',
    'success' => 'bg-neon-600 text-white hover:bg-neon-700 focus:ring-neon-300',
    'warning' => 'bg-yellow-600 text-white hover:bg-yellow-700 focus:ring-yellow-300'
];

// Tamanhos
$sizes = [
    'xs' => 'px-3 py-1.5 text-xs rounded-lg',
    'sm' => 'px-4 py-2 text-sm rounded-lg', 
    'md' => 'px-6 py-3 text-base rounded-xl',
    'lg' => 'px-8 py-4 text-lg rounded-xl',
    'xl' => 'px-10 py-5 text-xl rounded-2xl'
];

// Montar classes finais
$classes = implode(' ', [
    $baseClasses,
    $variants[$variant] ?? $variants['primary'],
    $sizes[$size] ?? $sizes['md'],
    $class
]);

// Atributos adicionais
$attributes = [];
if ($disabled) {
    $attributes[] = 'disabled';
}
if ($loading) {
    $attributes[] = 'data-loading="true"';
}

$attributesString = implode(' ', $attributes);
?>

<?php if ($href && !$disabled): ?>
    <a href="<?= htmlspecialchars($href) ?>" class="<?= $classes ?>" <?= $attributesString ?>>
<?php else: ?>
    <button type="<?= htmlspecialchars($type) ?>" class="<?= $classes ?>" <?= $attributesString ?>>
<?php endif; ?>

    <?php if ($loading): ?>
        <!-- Loading spinner -->
        <svg class="animate-spin -ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Carregando...
    <?php else: ?>
        <?php if ($icon && $iconPosition === 'left'): ?>
            <i class="<?= htmlspecialchars($icon) ?> mr-2"></i>
        <?php endif; ?>
        
        <?= htmlspecialchars($text) ?>
        
        <?php if ($icon && $iconPosition === 'right'): ?>
            <i class="<?= htmlspecialchars($icon) ?> ml-2"></i>
        <?php endif; ?>
    <?php endif; ?>

<?php if ($href && !$disabled): ?>
    </a>
<?php else: ?>
    </button>
<?php endif; ?>

<style>
/* Animação glow para botão neon */
@keyframes glow {
    0%, 100% {
        box-shadow: 0 0 5px rgba(57, 255, 20, 0.5);
    }
    50% {
        box-shadow: 0 0 20px rgba(57, 255, 20, 0.8), 0 0 30px rgba(57, 255, 20, 0.6);
    }
}

.animate-glow {
    animation: glow 2s ease-in-out infinite alternate;
}

.shadow-electric {
    box-shadow: 0 0 20px rgba(124, 58, 237, 0.3);
}

.shadow-neon {
    box-shadow: 0 0 20px rgba(57, 255, 20, 0.3);
}
</style>