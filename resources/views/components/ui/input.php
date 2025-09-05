<?php
/**
 * Componente Input
 * 
 * Props:
 * - type: 'text', 'email', 'password', 'number', 'tel', 'url', 'search', 'date', 'time', 'datetime-local'
 * - name: string
 * - id: string
 * - value: string
 * - placeholder: string
 * - label: string
 * - help: string
 * - error: string
 * - required: boolean
 * - disabled: boolean
 * - readonly: boolean
 * - size: 'sm', 'md', 'lg'
 * - variant: 'default', 'filled', 'underlined'
 * - icon: string (classe do ícone)
 * - iconPosition: 'left', 'right'
 * - class: string (classes adicionais)
 * - inputClass: string (classes específicas do input)
 * - labelClass: string (classes específicas do label)
 * - min: string/number
 * - max: string/number
 * - step: string/number
 * - pattern: string
 * - autocomplete: string
 * - autofocus: boolean
 * - maxlength: number
 * - minlength: number
 */

$type = $type ?? 'text';
$name = $name ?? '';
$id = $id ?? ($name ? $name : uniqid('input_'));
$value = $value ?? '';
$placeholder = $placeholder ?? '';
$label = $label ?? '';
$help = $help ?? '';
$error = $error ?? '';
$required = $required ?? false;
$disabled = $disabled ?? false;
$readonly = $readonly ?? false;
$size = $size ?? 'md';
$variant = $variant ?? 'default';
$icon = $icon ?? null;
$iconPosition = $iconPosition ?? 'left';
$class = $class ?? '';
$inputClass = $inputClass ?? '';
$labelClass = $labelClass ?? '';
$min = $min ?? null;
$max = $max ?? null;
$step = $step ?? null;
$pattern = $pattern ?? null;
$autocomplete = $autocomplete ?? null;
$autofocus = $autofocus ?? false;
$maxlength = $maxlength ?? null;
$minlength = $minlength ?? null;

// Classes base do container
$containerClasses = ['relative'];
if ($class) {
    $containerClasses[] = $class;
}

// Classes base do input
$baseInputClasses = [
    'block',
    'w-full',
    'border',
    'rounded-lg',
    'transition-all',
    'duration-200',
    'focus:outline-none',
    'focus:ring-2',
    'focus:ring-offset-1'
];

// Classes de tamanho
$sizeClasses = [
    'sm' => 'px-3 py-2 text-sm',
    'md' => 'px-4 py-2.5 text-sm',
    'lg' => 'px-4 py-3 text-base'
];

// Classes de variante
$variantClasses = [
    'default' => [
        'normal' => 'border-gray-300 bg-white text-gray-900 placeholder-gray-500 focus:border-primary-500 focus:ring-primary-500',
        'error' => 'border-red-300 bg-red-50 text-red-900 placeholder-red-400 focus:border-red-500 focus:ring-red-500',
        'disabled' => 'border-gray-200 bg-gray-50 text-gray-500 placeholder-gray-400 cursor-not-allowed'
    ],
    'filled' => [
        'normal' => 'border-transparent bg-gray-100 text-gray-900 placeholder-gray-500 focus:border-primary-500 focus:ring-primary-500 focus:bg-white',
        'error' => 'border-transparent bg-red-100 text-red-900 placeholder-red-400 focus:border-red-500 focus:ring-red-500 focus:bg-red-50',
        'disabled' => 'border-transparent bg-gray-100 text-gray-500 placeholder-gray-400 cursor-not-allowed'
    ],
    'underlined' => [
        'normal' => 'border-0 border-b-2 border-gray-300 bg-transparent rounded-none text-gray-900 placeholder-gray-500 focus:border-primary-500 focus:ring-0 px-0',
        'error' => 'border-0 border-b-2 border-red-300 bg-transparent rounded-none text-red-900 placeholder-red-400 focus:border-red-500 focus:ring-0 px-0',
        'disabled' => 'border-0 border-b-2 border-gray-200 bg-transparent rounded-none text-gray-500 placeholder-gray-400 cursor-not-allowed px-0'
    ]
];

// Determinar estado
$state = 'normal';
if ($error) {
    $state = 'error';
} elseif ($disabled || $readonly) {
    $state = 'disabled';
}

// Montar classes do input
$inputClasses = array_merge($baseInputClasses, [
    $sizeClasses[$size],
    $variantClasses[$variant][$state]
]);

// Ajustar padding para ícones
if ($icon) {
    if ($iconPosition === 'left') {
        $inputClasses[] = $size === 'sm' ? 'pl-10' : ($size === 'lg' ? 'pl-12' : 'pl-11');
    } else {
        $inputClasses[] = $size === 'sm' ? 'pr-10' : ($size === 'lg' ? 'pr-12' : 'pr-11');
    }
}

// Adicionar classes customizadas do input
if ($inputClass) {
    $inputClasses[] = $inputClass;
}

$inputClassString = implode(' ', $inputClasses);

// Classes do label
$labelClasses = [
    'block',
    'text-sm',
    'font-medium',
    'mb-2'
];

if ($error) {
    $labelClasses[] = 'text-red-700';
} elseif ($disabled) {
    $labelClasses[] = 'text-gray-500';
} else {
    $labelClasses[] = 'text-gray-700';
}

if ($labelClass) {
    $labelClasses[] = $labelClass;
}

$labelClassString = implode(' ', $labelClasses);

// Atributos do input
$attributes = [];
$attributes[] = "type=\"$type\"";
$attributes[] = "name=\"$name\"";
$attributes[] = "id=\"$id\"";

if ($value !== '') $attributes[] = "value=\"" . htmlspecialchars($value) . "\"";
if ($placeholder) $attributes[] = "placeholder=\"" . htmlspecialchars($placeholder) . "\"";
if ($required) $attributes[] = 'required';
if ($disabled) $attributes[] = 'disabled';
if ($readonly) $attributes[] = 'readonly';
if ($min !== null) $attributes[] = "min=\"$min\"";
if ($max !== null) $attributes[] = "max=\"$max\"";
if ($step !== null) $attributes[] = "step=\"$step\"";
if ($pattern) $attributes[] = "pattern=\"$pattern\"";
if ($autocomplete) $attributes[] = "autocomplete=\"$autocomplete\"";
if ($autofocus) $attributes[] = 'autofocus';
if ($maxlength) $attributes[] = "maxlength=\"$maxlength\"";
if ($minlength) $attributes[] = "minlength=\"$minlength\"";

$attributeString = implode(' ', $attributes);

// Tamanho do ícone baseado no tamanho do input
$iconSize = $size === 'sm' ? 'w-4 h-4' : ($size === 'lg' ? 'w-6 h-6' : 'w-5 h-5');
$iconPosition_class = $iconPosition === 'left' ? 'left-3' : 'right-3';
?>

<div class="<?= implode(' ', $containerClasses) ?>">
    <?php if ($label): ?>
        <label for="<?= $id ?>" class="<?= $labelClassString ?>">
            <?= htmlspecialchars($label) ?>
            <?php if ($required): ?>
                <span class="text-red-500 ml-1">*</span>
            <?php endif; ?>
        </label>
    <?php endif; ?>
    
    <div class="relative">
        <?php if ($icon && $iconPosition === 'left'): ?>
            <div class="absolute inset-y-0 left-0 flex items-center <?= $iconPosition_class ?> pointer-events-none">
                <i class="<?= $icon ?> <?= $iconSize ?> <?= $error ? 'text-red-400' : ($disabled ? 'text-gray-400' : 'text-gray-500') ?>"></i>
            </div>
        <?php endif; ?>
        
        <input class="<?= $inputClassString ?>" <?= $attributeString ?> />
        
        <?php if ($icon && $iconPosition === 'right'): ?>
            <div class="absolute inset-y-0 right-0 flex items-center <?= $iconPosition_class ?> pointer-events-none">
                <i class="<?= $icon ?> <?= $iconSize ?> <?= $error ? 'text-red-400' : ($disabled ? 'text-gray-400' : 'text-gray-500') ?>"></i>
            </div>
        <?php endif; ?>
    </div>
    
    <?php if ($error): ?>
        <p class="mt-2 text-sm text-red-600" id="<?= $id ?>-error">
            <i class="fas fa-exclamation-circle mr-1"></i>
            <?= htmlspecialchars($error) ?>
        </p>
    <?php elseif ($help): ?>
        <p class="mt-2 text-sm text-gray-600" id="<?= $id ?>-help">
            <i class="fas fa-info-circle mr-1"></i>
            <?= htmlspecialchars($help) ?>
        </p>
    <?php endif; ?>
</div>

<script>
// Auto-resize para textareas (se necessário)
if (document.getElementById('<?= $id ?>') && document.getElementById('<?= $id ?>').tagName === 'TEXTAREA') {
    const textarea = document.getElementById('<?= $id ?>');
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });
    
    // Trigger inicial
    textarea.dispatchEvent(new Event('input'));
}

// Validação em tempo real (opcional)
if (document.getElementById('<?= $id ?>')) {
    const input = document.getElementById('<?= $id ?>');
    
    input.addEventListener('blur', function() {
        // Aqui você pode adicionar validações customizadas
        if (this.hasAttribute('required') && !this.value.trim()) {
            this.classList.add('border-red-300', 'bg-red-50');
            this.classList.remove('border-gray-300', 'bg-white');
        } else {
            this.classList.remove('border-red-300', 'bg-red-50');
            this.classList.add('border-gray-300', 'bg-white');
        }
    });
    
    input.addEventListener('input', function() {
        // Remove erro visual durante digitação
        if (this.classList.contains('border-red-300')) {
            this.classList.remove('border-red-300', 'bg-red-50');
            this.classList.add('border-gray-300', 'bg-white');
        }
    });
}
</script>