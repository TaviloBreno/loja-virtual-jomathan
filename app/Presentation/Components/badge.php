<?php
/**
 * Componente Badge - Paleta Neon Futurista
 * 
 * Variantes disponíveis:
 * - primary: Roxo elétrico
 * - neon: Verde neon com efeito glow
 * - dark: Cinza escuro
 * - success: Verde de sucesso
 * - warning: Amarelo de aviso
 * - danger: Vermelho de erro
 * - info: Azul informativo
 */

$variant = $variant ?? 'primary';
$size = $size ?? 'md';
$text = $text ?? $slot ?? 'Badge';
$icon = $icon ?? null;
$removable = $removable ?? false;
$pulse = $pulse ?? false;
$class = $class ?? '';
$onclick = $onclick ?? '';

// Classes base
$baseClasses = 'inline-flex items-center font-semibold transition-all duration-300';

// Variantes de cor
$variants = [
    'primary' => 'bg-primary-500/20 text-primary-300 border border-primary-500/30',
    'neon' => 'bg-neon-500/20 text-neon-300 border border-neon-500/50 shadow-neon-badge',
    'dark' => 'bg-dark-700 text-gray-300 border border-dark-600',
    'success' => 'bg-green-500/20 text-green-300 border border-green-500/30',
    'warning' => 'bg-yellow-500/20 text-yellow-300 border border-yellow-500/30',
    'danger' => 'bg-red-500/20 text-red-300 border border-red-500/30',
    'info' => 'bg-blue-500/20 text-blue-300 border border-blue-500/30',
    'gradient' => 'bg-gradient-to-r from-primary-500/20 to-neon-500/20 text-white border border-primary-500/30'
];

// Tamanhos
$sizes = [
    'xs' => 'px-2 py-0.5 text-xs rounded-md',
    'sm' => 'px-2.5 py-1 text-xs rounded-lg',
    'md' => 'px-3 py-1.5 text-sm rounded-lg',
    'lg' => 'px-4 py-2 text-base rounded-xl',
    'xl' => 'px-5 py-2.5 text-lg rounded-xl'
];

// Efeito pulse
$pulseClass = $pulse ? 'animate-pulse' : '';

// Classes finais
$badgeClasses = implode(' ', array_filter([
    $baseClasses,
    $variants[$variant] ?? $variants['primary'],
    $sizes[$size] ?? $sizes['md'],
    $pulseClass,
    $class
]));
?>

<span 
    class="<?= $badgeClasses ?>"
    <?php if ($onclick): ?>
        onclick="<?= htmlspecialchars($onclick) ?>"
        role="button"
        tabindex="0"
    <?php endif; ?>
>
    <?php if ($icon): ?>
        <i class="<?= htmlspecialchars($icon) ?> <?= $text ? 'mr-1.5' : '' ?>"></i>
    <?php endif; ?>
    
    <?php if ($text): ?>
        <?= htmlspecialchars($text) ?>
    <?php endif; ?>
    
    <?php if ($removable): ?>
        <button 
            class="ml-1.5 hover:text-white transition-colors duration-200"
            onclick="this.parentElement.remove()"
            aria-label="Remover badge"
        >
            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    <?php endif; ?>
</span>

<style>
/* Efeito glow para badge neon */
.shadow-neon-badge {
    box-shadow: 0 0 10px rgba(57, 255, 20, 0.3);
}

/* Animação de pulse personalizada */
@keyframes badge-pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.7;
    }
}

.animate-badge-pulse {
    animation: badge-pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Hover effects */
.badge-hover:hover {
    transform: scale(1.05);
    cursor: pointer;
}
</style>

<?php
/**
 * Exemplo de uso:
 * 
 * <!-- Badge simples -->
 * <?php include 'badge.php'; ?>
 * 
 * <!-- Badge com ícone -->
 * <?php 
 * $variant = 'neon';
 * $icon = 'fas fa-star';
 * $text = 'Destaque';
 * include 'badge.php'; 
 * ?>
 * 
 * <!-- Badge removível -->
 * <?php 
 * $variant = 'primary';
 * $text = 'Tag';
 * $removable = true;
 * include 'badge.php'; 
 * ?>
 * 
 * <!-- Badge com pulse -->
 * <?php 
 * $variant = 'danger';
 * $text = 'Novo';
 * $pulse = true;
 * include 'badge.php'; 
 * ?>
 */
?>