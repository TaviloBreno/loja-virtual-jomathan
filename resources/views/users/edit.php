<?php $title = 'Editar Usuário'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-800">Editar Usuário</h1>
            <a href="/users/<?= $user['id'] ?>" class="text-gray-600 hover:text-gray-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </a>
        </div>

        <?php if (isset($errors) && !empty($errors)): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul class="list-disc list-inside">
                    <?php foreach ($errors as $error): ?>
                        <li><?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form method="POST" action="/users/<?= $user['id'] ?>">
            <input type="hidden" name="_method" value="PUT">
            
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                    Nome *
                </label>
                <input 
                    type="text" 
                    id="name" 
                    name="name" 
                    value="<?= htmlspecialchars($old['name'] ?? $user['name']) ?>"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?= isset($errors['name']) ? 'border-red-500' : '' ?>"
                    required
                >
                <?php if (isset($errors['name'])): ?>
                    <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($errors['name']) ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email *
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="<?= htmlspecialchars($old['email'] ?? $user['email']) ?>"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?= isset($errors['email']) ? 'border-red-500' : '' ?>"
                    required
                >
                <?php if (isset($errors['email'])): ?>
                    <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($errors['email']) ?></p>
                <?php endif; ?>
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Nova Senha
                </label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 <?= isset($errors['password']) ? 'border-red-500' : '' ?>"
                >
                <?php if (isset($errors['password'])): ?>
                    <p class="text-red-500 text-xs mt-1"><?= htmlspecialchars($errors['password']) ?></p>
                <?php endif; ?>
                <p class="text-gray-500 text-xs mt-1">Deixe em branco para manter a senha atual. Mínimo de 6 caracteres se informada.</p>
            </div>

            <div class="mb-4">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                    Status
                </label>
                <select 
                    id="status" 
                    name="status" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option value="active" <?= ($old['status'] ?? $user['status']) === 'active' ? 'selected' : '' ?>>Ativo</option>
                    <option value="inactive" <?= ($old['status'] ?? $user['status']) === 'inactive' ? 'selected' : '' ?>>Inativo</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="flex items-center">
                    <input 
                        type="checkbox" 
                        name="email_verified" 
                        value="1" 
                        <?= $user['email_verified_at'] ? 'checked' : '' ?>
                        class="mr-2 rounded"
                    >
                    <span class="text-sm text-gray-700">Email verificado</span>
                </label>
            </div>

            <div class="mb-6">
                <label for="preferences" class="block text-sm font-medium text-gray-700 mb-2">
                    Observações
                </label>
                <textarea 
                    id="preferences" 
                    name="preferences" 
                    rows="3"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Informações adicionais sobre o usuário..."
                ><?php 
                    if (isset($old['preferences'])) {
                        echo htmlspecialchars($old['preferences']);
                    } else {
                        $preferences = json_decode($user['preferences'] ?? '', true);
                        if (is_array($preferences)) {
                            echo htmlspecialchars(json_encode($preferences, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
                        } else {
                            echo htmlspecialchars($user['preferences'] ?? '');
                        }
                    }
                ?></textarea>
            </div>

            <div class="flex justify-between">
                <a href="/users/<?= $user['id'] ?>" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Atualizar Usuário
                </button>
            </div>
        </form>

        <div class="mt-6 pt-4 border-t border-gray-200">
            <div class="text-sm text-gray-600">
                <p><strong>Criado em:</strong> <?= date('d/m/Y H:i', strtotime($user['created_at'])) ?></p>
                <p><strong>Última atualização:</strong> <?= date('d/m/Y H:i', strtotime($user['updated_at'])) ?></p>
            </div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>