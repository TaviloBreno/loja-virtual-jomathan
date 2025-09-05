<?php $title = 'Detalhes do Usuário'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Detalhes do Usuário</h1>
            <div class="flex space-x-2">
                <a href="/users/<?= $user['id'] ?>/edit" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Editar
                </a>
                <a href="/users" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Voltar
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ID</label>
                    <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded"><?= htmlspecialchars($user['id']) ?></p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                    <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded"><?= htmlspecialchars($user['name']) ?></p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded"><?= htmlspecialchars($user['email']) ?></p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <span class="px-3 py-2 inline-flex text-sm leading-5 font-semibold rounded-full <?= $user['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                        <?= $user['status'] === 'active' ? 'Ativo' : 'Inativo' ?>
                    </span>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email Verificado</label>
                    <span class="px-3 py-2 inline-flex text-sm leading-5 font-semibold rounded-full <?= $user['email_verified_at'] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' ?>">
                        <?= $user['email_verified_at'] ? 'Verificado' : 'Não Verificado' ?>
                    </span>
                    <?php if ($user['email_verified_at']): ?>
                        <p class="text-xs text-gray-500 mt-1">
                            Verificado em: <?= date('d/m/Y H:i', strtotime($user['email_verified_at'])) ?>
                        </p>
                    <?php endif; ?>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Criado em</label>
                    <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded"><?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Atualizado em</label>
                    <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded"><?= date('d/m/Y H:i', strtotime($user['updated_at'])) ?></p>
                </div>
            </div>
        </div>

        <?php if (!empty($user['preferences'])): ?>
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Preferências</label>
                <div class="bg-gray-50 px-4 py-3 rounded">
                    <?php 
                    $preferences = json_decode($user['preferences'], true);
                    if (is_array($preferences) && !empty($preferences)): 
                    ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php foreach ($preferences as $key => $value): ?>
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-700"><?= ucfirst(htmlspecialchars($key)) ?>:</span>
                                    <span class="text-gray-900">
                                        <?php if (is_bool($value)): ?>
                                            <?= $value ? 'Sim' : 'Não' ?>
                                        <?php else: ?>
                                            <?= htmlspecialchars($value) ?>
                                        <?php endif; ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500 italic"><?= htmlspecialchars($user['preferences']) ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="mt-8 pt-6 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <div class="flex space-x-4">
                    <a href="/users/<?= $user['id'] ?>/edit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Editar Usuário
                    </a>
                    <form method="POST" action="/users/<?= $user['id'] ?>/delete" class="inline" onsubmit="return confirm('Tem certeza que deseja remover este usuário?')">
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Remover Usuário
                        </button>
                    </form>
                </div>
                <a href="/users" class="text-gray-600 hover:text-gray-800">
                    ← Voltar para lista
                </a>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>