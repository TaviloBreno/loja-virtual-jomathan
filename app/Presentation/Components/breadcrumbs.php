<?php
/**
 * Componente de breadcrumbs
 */
$items = $items ?? [];
?>

<?php if (!empty($items)): ?>
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <?php foreach ($items as $index => $item): ?>
                <li class="inline-flex items-center">
                    <?php if ($index > 0): ?>
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                        </svg>
                    <?php endif; ?>
                    
                    <?php if (isset($item['active']) && $item['active']): ?>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">
                            <?= htmlspecialchars($item['label']) ?>
                        </span>
                    <?php else: ?>
                        <a href="<?= htmlspecialchars($item['url']) ?>" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">
                            <?= htmlspecialchars($item['label']) ?>
                        </a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ol>
    </nav>
<?php endif; ?>