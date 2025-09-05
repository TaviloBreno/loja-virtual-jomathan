<?php
/**
 * Componente Card
 * 
 * Props:
 * - title: string
 * - subtitle: string
 * - image: string (URL da imagem)
 * - imageAlt: string
 * - imagePosition: 'top', 'left', 'right', 'background'
 * - variant: 'default', 'outlined', 'elevated', 'filled'
 * - size: 'sm', 'md', 'lg', 'xl'
 * - padding: 'none', 'sm', 'md', 'lg', 'xl'
 * - rounded: 'none', 'sm', 'md', 'lg', 'xl', 'full'
 * - shadow: 'none', 'sm', 'md', 'lg', 'xl'
 * - hover: boolean (efeito hover)
 * - clickable: boolean
 * - href: string (para links)
 * - target: string
 * - onclick: string
 * - class: string (classes adicionais)
 * - headerClass: string
 * - bodyClass: string
 * - footerClass: string
 * - actions: array (botões de ação)
 * - badge: string
 * - badgeType: 'primary', 'secondary', 'success', 'danger', 'warning', 'info'
 */

$title = $title ?? '';
$subtitle = $subtitle ?? '';
$image = $image ?? null;
$imageAlt = $imageAlt ?? '';
$imagePosition = $imagePosition ?? 'top';
$variant = $variant ?? 'default';
$size = $size ?? 'md';
$padding = $padding ?? 'md';
$rounded = $rounded ?? 'lg';
$shadow = $shadow ?? 'md';
$hover = $hover ?? false;
$clickable = $clickable ?? false;
$href = $href ?? null;
$target = $target ?? null;
$onclick = $onclick ?? null;
$class = $class ?? '';
$headerClass = $headerClass ?? '';
$bodyClass = $bodyClass ?? '';
$footerClass = $footerClass ?? '';
$actions = $actions ?? [];
$badge = $badge ?? null;
$badgeType = $badgeType ?? 'primary';
$content = $content ?? slot('default', '');
$header = $header ?? slot('header', '');
$footer = $footer ?? slot('footer', '');

// Classes base
$baseClasses = [
    'bg-white',
    'border',
    'transition-all',
    'duration-200'
];

// Classes de variante
$variantClasses = [
    'default' => 'border-gray-200',
    'outlined' => 'border-gray-300 border-2',
    'elevated' => 'border-transparent',
    'filled' => 'border-transparent bg-gray-50'
];

// Classes de tamanho (largura máxima)
$sizeClasses = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl'
];

// Classes de padding
$paddingClasses = [
    'none' => '',
    'sm' => 'p-3',
    'md' => 'p-4',
    'lg' => 'p-6',
    'xl' => 'p-8'
];

// Classes de arredondamento
$roundedClasses = [
    'none' => '',
    'sm' => 'rounded-sm',
    'md' => 'rounded-md',
    'lg' => 'rounded-lg',
    'xl' => 'rounded-xl',
    'full' => 'rounded-full'
];

// Classes de sombra
$shadowClasses = [
    'none' => '',
    'sm' => 'shadow-sm',
    'md' => 'shadow-md',
    'lg' => 'shadow-lg',
    'xl' => 'shadow-xl'
];

// Classes de badge
$badgeClasses = [
    'primary' => 'bg-primary-100 text-primary-800',
    'secondary' => 'bg-gray-100 text-gray-800',
    'success' => 'bg-green-100 text-green-800',
    'danger' => 'bg-red-100 text-red-800',
    'warning' => 'bg-yellow-100 text-yellow-800',
    'info' => 'bg-blue-100 text-blue-800'
];

// Montar classes
$classes = array_merge($baseClasses, [
    $variantClasses[$variant],
    $sizeClasses[$size],
    $roundedClasses[$rounded],
    $shadowClasses[$shadow]
]);

// Classes condicionais
if ($hover) {
    $classes[] = 'hover:shadow-lg hover:-translate-y-1';
}

if ($clickable || $href || $onclick) {
    $classes[] = 'cursor-pointer';
    if (!$hover) {
        $classes[] = 'hover:shadow-md';
    }
}

// Adicionar classes customizadas
if ($class) {
    $classes[] = $class;
}

$classString = implode(' ', $classes);

// Atributos
$attributes = [];
if ($onclick) $attributes[] = "onclick=\"$onclick\"";
if ($target) $attributes[] = "target=\"$target\"";

$attributeString = implode(' ', $attributes);

// Determinar se é flexível (com imagem lateral)
$isFlexLayout = $image && in_array($imagePosition, ['left', 'right']);

// Classes do container de conteúdo
$contentClasses = [];
if ($padding !== 'none' && !$image) {
    $contentClasses[] = $paddingClasses[$padding];
} elseif ($image && $imagePosition === 'top') {
    // Padding apenas no conteúdo quando imagem está no topo
    $contentClasses[] = $paddingClasses[$padding];
}

$contentClassString = implode(' ', $contentClasses);
?>

<?php if ($href): ?>
    <a href="<?= htmlspecialchars($href) ?>" class="<?= $classString ?> block" <?= $attributeString ?>>
<?php else: ?>
    <div class="<?= $classString ?>" <?= $attributeString ?>>
<?php endif; ?>

    <?php if ($image && $imagePosition === 'background'): ?>
        <div class="absolute inset-0 bg-cover bg-center <?= $roundedClasses[$rounded] ?>" 
             style="background-image: url('<?= htmlspecialchars($image) ?>');"></div>
        <div class="relative bg-black bg-opacity-40 <?= $roundedClasses[$rounded] ?> <?= $contentClassString ?>">
    <?php elseif ($isFlexLayout): ?>
        <div class="flex <?= $imagePosition === 'right' ? 'flex-row-reverse' : '' ?>">
            <div class="flex-shrink-0">
                <img src="<?= htmlspecialchars($image) ?>" 
                     alt="<?= htmlspecialchars($imageAlt) ?>"
                     class="w-24 h-24 object-cover <?= $roundedClasses[$rounded] ?>">
            </div>
            <div class="flex-1 <?= $paddingClasses[$padding] ?>">
    <?php else: ?>
        <?php if ($image && $imagePosition === 'top'): ?>
            <div class="relative">
                <img src="<?= htmlspecialchars($image) ?>" 
                     alt="<?= htmlspecialchars($imageAlt) ?>"
                     class="w-full h-48 object-cover <?= $rounded !== 'none' ? 'rounded-t-' . $rounded : '' ?>">
                
                <?php if ($badge): ?>
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $badgeClasses[$badgeType] ?>">
                            <?= htmlspecialchars($badge) ?>
                        </span>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <div class="<?= $contentClassString ?>">
    <?php endif; ?>

            <?php if ($header || $title || $subtitle): ?>
                <div class="<?= $headerClass ?>">
                    <?php if ($header): ?>
                        <?= $header ?>
                    <?php else: ?>
                        <?php if ($badge && $imagePosition !== 'top'): ?>
                            <div class="flex items-start justify-between mb-2">
                                <div class="flex-1">
                        <?php endif; ?>
                        
                        <?php if ($title): ?>
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">
                                <?= htmlspecialchars($title) ?>
                            </h3>
                        <?php endif; ?>
                        
                        <?php if ($subtitle): ?>
                            <p class="text-sm text-gray-600 mb-3">
                                <?= htmlspecialchars($subtitle) ?>
                            </p>
                        <?php endif; ?>
                        
                        <?php if ($badge && $imagePosition !== 'top'): ?>
                                </div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $badgeClasses[$badgeType] ?>">
                                    <?= htmlspecialchars($badge) ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($content): ?>
                <div class="<?= $bodyClass ?> text-gray-700">
                    <?= $content ?>
                </div>
            <?php endif; ?>

            <?php if ($footer || !empty($actions)): ?>
                <div class="<?= $footerClass ?> <?= ($content || $title || $subtitle) ? 'mt-4' : '' ?>">
                    <?php if ($footer): ?>
                        <?= $footer ?>
                    <?php endif; ?>
                    
                    <?php if (!empty($actions)): ?>
                        <div class="flex flex-wrap gap-2 <?= $footer ? 'mt-3' : '' ?>">
                            <?php foreach ($actions as $action): ?>
                                <?php if (is_array($action)): ?>
                                    <?php 
                                    $actionType = $action['type'] ?? 'button';
                                    $actionText = $action['text'] ?? 'Action';
                                    $actionClass = $action['class'] ?? 'text-primary-600 hover:text-primary-700';
                                    $actionHref = $action['href'] ?? null;
                                    $actionOnclick = $action['onclick'] ?? null;
                                    ?>
                                    
                                    <?php if ($actionHref): ?>
                                        <a href="<?= htmlspecialchars($actionHref) ?>" 
                                           class="<?= $actionClass ?> text-sm font-medium transition-colors">
                                            <?= htmlspecialchars($actionText) ?>
                                        </a>
                                    <?php else: ?>
                                        <button type="button" 
                                                class="<?= $actionClass ?> text-sm font-medium transition-colors"
                                                <?= $actionOnclick ? "onclick=\"$actionOnclick\"" : '' ?>>
                                            <?= htmlspecialchars($actionText) ?>
                                        </button>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <?= $action ?>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

    <?php if ($image && $imagePosition === 'background'): ?>
        </div>
    <?php elseif ($isFlexLayout): ?>
            </div>
        </div>
    <?php else: ?>
        </div>
    <?php endif; ?>

<?php if ($href): ?>
    </a>
<?php else: ?>
    </div>
<?php endif; ?>