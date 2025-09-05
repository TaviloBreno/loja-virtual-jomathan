<?php $title = 'Página Inicial'; ?>
<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="container mx-auto px-4 py-8">
    <!-- Hero Section -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Sistema de Gerenciamento</h1>
        <p class="text-xl text-gray-600 mb-8">Framework PHP com Clean Architecture</p>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg inline-block">
            <span class="font-semibold">✓ Sistema funcionando!</span> Todas as funcionalidades estão operacionais.
        </div>
    </div>

    <!-- Features Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
        <!-- CRUD de Usuários -->
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center mb-4">
                <div class="bg-blue-100 p-3 rounded-full mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Gerenciar Usuários</h3>
            </div>
            <p class="text-gray-600 mb-4">Sistema completo de CRUD para gerenciamento de usuários com validação e paginação.</p>
            <a href="/users" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-block">
                Acessar Usuários
            </a>
        </div>

        <!-- API REST -->
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center mb-4">
                <div class="bg-green-100 p-3 rounded-full mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">API REST</h3>
            </div>
            <p class="text-gray-600 mb-4">Endpoints RESTful para integração com aplicações externas e SPAs.</p>
            <a href="/api/users" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-block">
                Testar API
            </a>
        </div>

        <!-- Arquitetura -->
        <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center mb-4">
                <div class="bg-purple-100 p-3 rounded-full mr-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800">Clean Architecture</h3>
            </div>
            <p class="text-gray-600 mb-4">Estrutura organizada seguindo princípios de Clean Architecture e SOLID.</p>
            <a href="/test" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded inline-block">
                Testar Sistema
            </a>
        </div>
    </div>

    <!-- Statistics -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Estatísticas do Sistema</h2>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="text-center">
                <div class="text-3xl font-bold text-blue-600 mb-2">204</div>
                <div class="text-gray-600">Usuários Cadastrados</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-green-600 mb-2">144</div>
                <div class="text-gray-600">Usuários Ativos</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-yellow-600 mb-2">144</div>
                <div class="text-gray-600">Emails Verificados</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-bold text-purple-600 mb-2">100%</div>
                <div class="text-gray-600">Sistema Operacional</div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-gray-50 rounded-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Ações Rápidas</h2>
        <div class="flex flex-wrap gap-4">
            <a href="/users/create" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                + Novo Usuário
            </a>
            <a href="/users" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                📋 Listar Usuários
            </a>
            <a href="/api/users" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                🔗 API Usuários
            </a>
            <a href="/test" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded">
                🧪 Testar Sistema
            </a>
        </div>
    </div>

    <!-- Technology Stack -->
    <div class="mt-12 text-center">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Tecnologias Utilizadas</h2>
        <div class="flex flex-wrap justify-center gap-4">
            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">PHP 8+</span>
            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">MySQL</span>
            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-semibold">Clean Architecture</span>
            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">Tailwind CSS</span>
            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold">PSR-4</span>
            <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm font-semibold">Composer</span>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../layouts/footer.php'; ?>