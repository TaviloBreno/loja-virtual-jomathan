<?php
/**
 * Componente Card - Paleta Neon Futurista
 * 
 * Variantes disponíveis:
 * - default: Fundo escuro com borda sutil
 * - neon: Com borda neon e efeito glow
 * - primary: Com borda roxa elétrica
 * - glass: Efeito glassmorphism
 */

$variant = $variant ?? 'default';
$padding = $padding ?? 'md';
$shadow = $shadow ?? true;
$hover = $hover ?? false;
$class = $class ?? '';
$title = $title ?? '';
$subtitle = $subtitle ?? '';
$image = $image ?? '';
$imageAlt = $imageAlt ?? '';
$footer = $footer ?? '';
$content = $content ?? $slot ?? '';

// Classes base
$baseClasses = 'rounded-2xl transition-all duration-300 backdrop-blur-sm';

// Variantes
$variants = [
    'default' => 'bg-dark-800/90 border border-dark-600 text-white',
    'neon' => 'bg-dark-800/90 border-2 border-neon-500 text-white shadow-neon-glow',
    'primary' => 'bg-dark-800/90 border-2 border-primary-500 text-white shadow-primary-glow',
    'glass' => 'bg-white/5 border border-white/10 text-white backdrop-blur-xl',
    'dark' => 'bg-dark-900/95 border border-dark-700 text-white',
    'gradient' => 'bg-gradient-to-br from-primary-600/20 to-neon-600/20 border border-primary-500/30 text-white'
];

// Padding
$paddings = [
    'none' => '',
    'sm' => 'p-4',
    'md' => 'p-6',
    'lg' => 'p-8',
    'xl' => 'p-10'
];

// Shadow
$shadowClasses = $shadow ? 'shadow-2xl' : '';

// Hover effects
$hoverClasses = $hover ? 'hover:scale-105 hover:shadow-3xl cursor-pointer' : '';

// Classes finais
$cardClasses = implode(' ', array_filter([
    $baseClasses,
    $variants[$variant] ?? $variants['default'],
    $paddings[$padding] ?? $paddings['md'],
    $shadowClasses,
    $hoverClasses,
    $class
]));
?>

<div class="<?= $cardClasses ?>">
    <?php if ($image): ?>
        <div class="-m-6 mb-6 rounded-t-2xl overflow-hidden">
            <img 
                src="<?= htmlspecialchars($image) ?>" 
                alt="<?= htmlspecialchars($imageAlt) ?>"
                class="w-full h-48 object-cover transition-transform duration-300 hover:scale-110"
            >
        </div>
    <?php endif; ?>
    
    <?php if ($title || $subtitle): ?>
        <div class="mb-4">
            <?php if ($title): ?>
                <h3 class="text-xl font-bold text-white mb-2">
                    <?= htmlspecialchars($title) ?>
                </h3>
            <?php endif; ?>
            
            <?php if ($subtitle): ?>
                <p class="text-gray-400 text-sm">
                    <?= htmlspecialchars($subtitle) ?>
                </p>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
    <?php if ($content): ?>
        <div class="text-gray-300 leading-relaxed">
            <?= $content ?>
        </div>
    <?php endif; ?>
    
    <?php if ($footer): ?>
        <div class="mt-6 pt-4 border-t border-dark-600">
            <?= $footer ?>
        </div>
    <?php endif; ?>
</div>

<style>
/* Efeitos de glow para cards */
.shadow-neon-glow {
    box-shadow: 
        0 0 20px rgba(57, 255, 20, 0.2),
        0 10px 40px rgba(0, 0, 0, 0.3);
}

.shadow-primary-glow {
    box-shadow: 
        0 0 20px rgba(124, 58, 237, 0.2),
        0 10px 40px rgba(0, 0, 0, 0.3);
}

.shadow-3xl {
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
}

/* Animação de hover para cards neon */
.card-neon:hover {
    box-shadow: 
        0 0 30px rgba(57, 255, 20, 0.4),
        0 15px 50px rgba(0, 0, 0, 0.4);
}

.card-primary:hover {
    box-shadow: 
        0 0 30px rgba(124, 58, 237, 0.4),
        0 15px 50px rgba(0, 0, 0, 0.4);
}
</style>