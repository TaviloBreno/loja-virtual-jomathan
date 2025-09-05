<?php
/**
 * Componente de mensagens flash
 */
?>

<?php if (isset($_SESSION['flash_messages']) && !empty($_SESSION['flash_messages'])): ?>
    <div class="mb-6">
        <?php foreach ($_SESSION['flash_messages'] as $type => $messages): ?>
            <?php foreach ($messages as $message): ?>
                <div class="alert alert-<?= $type ?> mb-3 p-4 rounded-lg border <?= $type === 'success' ? 'bg-green-50 border-green-200 text-green-800' : ($type === 'error' ? 'bg-red-50 border-red-200 text-red-800' : 'bg-blue-50 border-blue-200 text-blue-800') ?>">
                    <div class="flex items-center">
                        <i class="fas <?= $type === 'success' ? 'fa-check-circle' : ($type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle') ?> mr-2"></i>
                        <span><?= htmlspecialchars($message) ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </div>
    <?php unset($_SESSION['flash_messages']); ?>
<?php endif; ?>