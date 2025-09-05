<?php
/**
 * Componente de rodapé
 */
?>

<footer class="bg-white border-t border-gray-200 mt-12">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Sobre -->
            <div class="col-span-1 md:col-span-2">
                <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase mb-4">Sistema PHP</h3>
                <p class="text-gray-500 text-sm">
                    Sistema desenvolvido com PHP seguindo os princípios da Clean Architecture.
                    Moderno, eficiente e escalável.
                </p>
            </div>
            
            <!-- Links -->
            <div>
                <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase mb-4">Links</h3>
                <ul class="space-y-2">
                    <li><a href="/" class="text-gray-500 hover:text-gray-900 text-sm">Dashboard</a></li>
                    <li><a href="/users" class="text-gray-500 hover:text-gray-900 text-sm">Usuários</a></li>
                    <li><a href="/api/users" class="text-gray-500 hover:text-gray-900 text-sm">API</a></li>
                </ul>
            </div>
            
            <!-- Suporte -->
            <div>
                <h3 class="text-sm font-semibold text-gray-400 tracking-wider uppercase mb-4">Suporte</h3>
                <ul class="space-y-2">
                    <li><a href="#" class="text-gray-500 hover:text-gray-900 text-sm">Documentação</a></li>
                    <li><a href="#" class="text-gray-500 hover:text-gray-900 text-sm">Contato</a></li>
                    <li><a href="#" class="text-gray-500 hover:text-gray-900 text-sm">Ajuda</a></li>
                </ul>
            </div>
        </div>
        
        <div class="mt-8 border-t border-gray-200 pt-8">
            <p class="text-gray-400 text-sm text-center">
                © <?= date('Y') ?> Sistema PHP. Todos os direitos reservados.
            </p>
        </div>
    </div>
</footer>