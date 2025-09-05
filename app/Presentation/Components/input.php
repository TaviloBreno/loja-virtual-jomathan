<?php
/**
 * Componente Input - Paleta Neon Futurista
 * 
 * Tipos disponíveis: text, email, password, number, tel, url, search
 * Variantes: primary (roxo), neon (verde), dark (cinza)
 */

$type = $type ?? 'text';
$name = $name ?? '';
$id = $id ?? $name;
$value = $value ?? '';
$placeholder = $placeholder ?? '';
$label = $label ?? '';
$required = $required ?? false;
$disabled = $disabled ?? false;
$readonly = $readonly ?? false;
$variant = $variant ?? 'primary';
$size = $size ?? 'md';
$error = $error ?? '';
$help = $help ?? '';
$icon = $icon ?? null;
$iconPosition = $iconPosition ?? 'left';
$class = $class ?? '';

// Classes base
$baseClasses = 'w-full transition-all duration-300 focus:outline-none border backdrop-blur-sm';

// Variantes de cor
$variants = [
    'primary' => 'bg-dark-800/80 border-dark-600 text-white placeholder-gray-400 focus:ring-2 focus:ring-primary-500 focus:border-primary-500',
    'neon' => 'bg-dark-800/80 border-dark-600 text-white placeholder-gray-400 focus:ring-2 focus:ring-neon-500 focus:border-neon-500',
    'dark' => 'bg-dark-900/90 border-dark-700 text-white placeholder-gray-500 focus:ring-2 focus:ring-dark-500 focus:border-dark-500'
];

// Tamanhos
$sizes = [
    'sm' => 'px-3 py-2 text-sm rounded-lg',
    'md' => 'px-4 py-3 text-base rounded-xl', 
    'lg' => 'px-5 py-4 text-lg rounded-xl'
];

// Estados de erro
$errorClasses = $error ? 'border-red-500 focus:ring-red-500 focus:border-red-500' : '';

// Classes finais
$inputClasses = implode(' ', array_filter([
    $baseClasses,
    $variants[$variant] ?? $variants['primary'],
    $sizes[$size] ?? $sizes['md'],
    $errorClasses,
    $disabled ? 'opacity-50 cursor-not-allowed' : '',
    $readonly ? 'bg-dark-700/50' : '',
    $icon ? ($iconPosition === 'left' ? 'pl-12' : 'pr-12') : '',
    $class
]));

// Atributos
$attributes = [];
if ($required) $attributes[] = 'required';
if ($disabled) $attributes[] = 'disabled';
if ($readonly) $attributes[] = 'readonly';

$attributesString = implode(' ', $attributes);
?>

<div class="space-y-2">
    <?php if ($label): ?>
        <label for="<?= htmlspecialchars($id) ?>" class="block text-sm font-semibold text-white">
            <?= htmlspecialchars($label) ?>
            <?php if ($required): ?>
                <span class="text-neon-500 ml-1">*</span>
            <?php endif; ?>
        </label>
    <?php endif; ?>
    
    <div class="relative">
        <?php if ($icon && $iconPosition === 'left'): ?>
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="<?= htmlspecialchars($icon) ?> text-gray-400"></i>
            </div>
        <?php endif; ?>
        
        <input 
            type="<?= htmlspecialchars($type) ?>"
            name="<?= htmlspecialchars($name) ?>"
            id="<?= htmlspecialchars($id) ?>"
            value="<?= htmlspecialchars($value) ?>"
            placeholder="<?= htmlspecialchars($placeholder) ?>"
            class="<?= $inputClasses ?>"
            <?= $attributesString ?>
        >
        
        <?php if ($icon && $iconPosition === 'right'): ?>
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <i class="<?= htmlspecialchars($icon) ?> text-gray-400"></i>
            </div>
        <?php endif; ?>
    </div>
    
    <?php if ($error): ?>
        <p class="text-sm text-red-400 flex items-center">
            <i class="fas fa-exclamation-circle mr-1"></i>
            <?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>
    
    <?php if ($help && !$error): ?>
        <p class="text-sm text-gray-400">
            <?= htmlspecialchars($help) ?>
        </p>
    <?php endif; ?>
</div>

<style>
/* Efeitos especiais para inputs */
.input-glow:focus {
    box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.1), 0 0 20px rgba(124, 58, 237, 0.2);
}

.input-neon-glow:focus {
    box-shadow: 0 0 0 3px rgba(57, 255, 20, 0.1), 0 0 20px rgba(57, 255, 20, 0.2);
}
</style>