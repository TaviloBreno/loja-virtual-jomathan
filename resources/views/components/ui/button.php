<?php
/**
 * Componente Button
 * 
 * Props:
 * - type: 'primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'
 * - size: 'xs', 'sm', 'md', 'lg', 'xl'
 * - variant: 'solid', 'outline', 'ghost', 'link'
 * - disabled: boolean
 * - loading: boolean
 * - href: string (para links)
 * - onclick: string
 * - class: string (classes adicionais)
 * - icon: string (classe do ícone)
 * - iconPosition: 'left', 'right'
 * - fullWidth: boolean
 * - rounded: boolean
 * - shadow: boolean
 * - animate: boolean
 */

$type = $type ?? 'primary';
$size = $size ?? 'md';
$variant = $variant ?? 'solid';
$disabled = $disabled ?? false;
$loading = $loading ?? false;
$href = $href ?? null;
$onclick = $onclick ?? null;
$class = $class ?? '';
$icon = $icon ?? null;
$iconPosition = $iconPosition ?? 'left';
$fullWidth = $fullWidth ?? false;
$rounded = $rounded ?? false;
$shadow = $shadow ?? false;
$animate = $animate ?? true;
$text = $text ?? slot('default', 'Button');
$id = $id ?? null;
$name = $name ?? null;
$value = $value ?? null;
$formaction = $formaction ?? null;
$formmethod = $formmethod ?? null;
$target = $target ?? null;

// Classes base
$baseClasses = [
    'inline-flex',
    'items-center',
    'justify-center',
    'font-medium',
    'focus:outline-none',
    'focus:ring-2',
    'focus:ring-offset-2',
    'transition-all',
    'duration-200'
];

// Classes de tamanho
$sizeClasses = [
    'xs' => 'px-2.5 py-1.5 text-xs',
    'sm' => 'px-3 py-2 text-sm',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-4 py-2 text-base',
    'xl' => 'px-6 py-3 text-base'
];

// Classes de tipo e variante
$typeVariantClasses = [
    'primary' => [
        'solid' => 'bg-primary-600 hover:bg-primary-700 text-white focus:ring-primary-500',
        'outline' => 'border border-primary-600 text-primary-600 hover:bg-primary-50 focus:ring-primary-500',
        'ghost' => 'text-primary-600 hover:bg-primary-50 focus:ring-primary-500',
        'link' => 'text-primary-600 hover:text-primary-700 underline-offset-4 hover:underline focus:ring-primary-500'
    ],
    'secondary' => [
        'solid' => 'bg-secondary-600 hover:bg-secondary-700 text-white focus:ring-secondary-500',
        'outline' => 'border border-secondary-300 text-secondary-700 hover:bg-secondary-50 focus:ring-secondary-500',
        'ghost' => 'text-secondary-600 hover:bg-secondary-50 focus:ring-secondary-500',
        'link' => 'text-secondary-600 hover:text-secondary-700 underline-offset-4 hover:underline focus:ring-secondary-500'
    ],
    'success' => [
        'solid' => 'bg-green-600 hover:bg-green-700 text-white focus:ring-green-500',
        'outline' => 'border border-green-600 text-green-600 hover:bg-green-50 focus:ring-green-500',
        'ghost' => 'text-green-600 hover:bg-green-50 focus:ring-green-500',
        'link' => 'text-green-600 hover:text-green-700 underline-offset-4 hover:underline focus:ring-green-500'
    ],
    'danger' => [
        'solid' => 'bg-red-600 hover:bg-red-700 text-white focus:ring-red-500',
        'outline' => 'border border-red-600 text-red-600 hover:bg-red-50 focus:ring-red-500',
        'ghost' => 'text-red-600 hover:bg-red-50 focus:ring-red-500',
        'link' => 'text-red-600 hover:text-red-700 underline-offset-4 hover:underline focus:ring-red-500'
    ],
    'warning' => [
        'solid' => 'bg-yellow-600 hover:bg-yellow-700 text-white focus:ring-yellow-500',
        'outline' => 'border border-yellow-600 text-yellow-600 hover:bg-yellow-50 focus:ring-yellow-500',
        'ghost' => 'text-yellow-600 hover:bg-yellow-50 focus:ring-yellow-500',
        'link' => 'text-yellow-600 hover:text-yellow-700 underline-offset-4 hover:underline focus:ring-yellow-500'
    ],
    'info' => [
        'solid' => 'bg-blue-600 hover:bg-blue-700 text-white focus:ring-blue-500',
        'outline' => 'border border-blue-600 text-blue-600 hover:bg-blue-50 focus:ring-blue-500',
        'ghost' => 'text-blue-600 hover:bg-blue-50 focus:ring-blue-500',
        'link' => 'text-blue-600 hover:text-blue-700 underline-offset-4 hover:underline focus:ring-blue-500'
    ]
];

// Montar classes
$classes = array_merge($baseClasses, [
    $sizeClasses[$size],
    $typeVariantClasses[$type][$variant] ?? $typeVariantClasses['primary']['solid']
]);

// Classes condicionais
if ($fullWidth) {
    $classes[] = 'w-full';
}

if ($rounded) {
    $classes[] = 'rounded-full';
} else {
    $classes[] = 'rounded-lg';
}

if ($shadow) {
    $classes[] = 'shadow-md hover:shadow-lg';
}

if ($animate) {
    $classes[] = 'transform hover:scale-105 active:scale-95';
}

if ($disabled || $loading) {
    $classes[] = 'opacity-50 cursor-not-allowed';
    $classes = array_filter($classes, function($class) {
        return !str_contains($class, 'hover:') && !str_contains($class, 'active:');
    });
}

// Adicionar classes customizadas
if ($class) {
    $classes[] = $class;
}

$classString = implode(' ', $classes);

// Atributos
$attributes = [];

if ($id) $attributes[] = "id=\"$id\"";
if ($name) $attributes[] = "name=\"$name\"";
if ($value) $attributes[] = "value=\"$value\"";
if ($onclick) $attributes[] = "onclick=\"$onclick\"";
if ($formaction) $attributes[] = "formaction=\"$formaction\"";
if ($formmethod) $attributes[] = "formmethod=\"$formmethod\"";
if ($target) $attributes[] = "target=\"$target\"";
if ($disabled) $attributes[] = 'disabled';

$attributeString = implode(' ', $attributes);

// Renderizar
if ($href): ?>
    <a href="<?= htmlspecialchars($href) ?>" 
       class="<?= $classString ?>" 
       <?= $attributeString ?>
       <?= $disabled ? 'aria-disabled="true"' : '' ?>>
        
        <?php if ($loading): ?>
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        <?php elseif ($icon && $iconPosition === 'left'): ?>
            <i class="<?= $icon ?> <?= $text ? 'mr-2' : '' ?>"></i>
        <?php endif; ?>
        
        <?= htmlspecialchars($text) ?>
        
        <?php if ($icon && $iconPosition === 'right'): ?>
            <i class="<?= $icon ?> <?= $text ? 'ml-2' : '' ?>"></i>
        <?php endif; ?>
    </a>
<?php else: ?>
    <button type="<?= $formaction ? 'submit' : 'button' ?>" 
            class="<?= $classString ?>" 
            <?= $attributeString ?>>
        
        <?php if ($loading): ?>
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        <?php elseif ($icon && $iconPosition === 'left'): ?>
            <i class="<?= $icon ?> <?= $text ? 'mr-2' : '' ?>"></i>
        <?php endif; ?>
        
        <?= htmlspecialchars($text) ?>
        
        <?php if ($icon && $iconPosition === 'right'): ?>
            <i class="<?= $icon ?> <?= $text ? 'ml-2' : '' ?>"></i>
        <?php endif; ?>
    </button>
<?php endif; ?>