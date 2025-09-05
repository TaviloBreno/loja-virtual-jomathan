<?php
/**
 * Página de Demonstração dos Componentes UI
 */

// Configurações da página
$pageTitle = 'Demonstração de Componentes';
$pageDescription = 'Exemplos de uso dos componentes UI disponíveis';
$breadcrumbs = [
    ['label' => 'Home', 'url' => '/'],
    ['label' => 'Componentes', 'url' => '/components']
];

// Dados de exemplo para a tabela
$tableData = [
    ['id' => 1, 'nome' => 'João Silva', 'email' => 'joao@email.com', 'status' => 'Ativo', 'created_at' => '2024-01-15'],
    ['id' => 2, 'nome' => 'Maria Santos', 'email' => 'maria@email.com', 'status' => 'Inativo', 'created_at' => '2024-01-14'],
    ['id' => 3, 'nome' => 'Pedro Costa', 'email' => 'pedro@email.com', 'status' => 'Ativo', 'created_at' => '2024-01-13'],
    ['id' => 4, 'nome' => 'Ana Oliveira', 'email' => 'ana@email.com', 'status' => 'Pendente', 'created_at' => '2024-01-12'],
    ['id' => 5, 'nome' => 'Carlos Lima', 'email' => 'carlos@email.com', 'status' => 'Ativo', 'created_at' => '2024-01-11']
];

// Colunas da tabela
$tableColumns = [
    ['key' => 'id', 'label' => 'ID', 'width' => '80px', 'align' => 'center'],
    ['key' => 'nome', 'label' => 'Nome', 'sortable' => true],
    ['key' => 'email', 'label' => 'E-mail', 'sortable' => true],
    [
        'key' => 'status',
        'label' => 'Status',
        'align' => 'center',
        'component' => function($value, $row) {
            $colors = [
                'Ativo' => 'bg-green-100 text-green-800',
                'Inativo' => 'bg-red-100 text-red-800',
                'Pendente' => 'bg-yellow-100 text-yellow-800'
            ];
            $color = $colors[$value] ?? 'bg-gray-100 text-gray-800';
            return "<span class='inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium $color'>$value</span>";
        }
    ],
    ['key' => 'created_at', 'label' => 'Criado em', 'sortable' => true]
];

// Ações da tabela
$tableActions = [
    [
        'text' => 'Editar',
        'class' => 'text-primary-600 hover:text-primary-900',
        'href' => function($row) { return "/users/{$row['id']}/edit"; }
    ],
    [
        'text' => 'Excluir',
        'class' => 'text-red-600 hover:text-red-900 ml-4',
        'onclick' => function($row) { return "deleteUser({$row['id']})"; }
    ]
];

// Incluir layout
include __DIR__ . '/../layouts/app.php';
?>

<!-- Conteúdo específico da página -->
<div class="space-y-8">
    
    <!-- Seção de Botões -->
    <section class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Componente Button</h2>
        
        <div class="space-y-6">
            <!-- Tipos de botões -->
            <div>
                <h3 class="text-sm font-medium text-gray-700 mb-3">Tipos</h3>
                <div class="flex flex-wrap gap-3">
                    <?= component('ui/button', ['type' => 'primary', 'text' => 'Primary']) ?>
                    <?= component('ui/button', ['type' => 'secondary', 'text' => 'Secondary']) ?>
                    <?= component('ui/button', ['type' => 'success', 'text' => 'Success']) ?>
                    <?= component('ui/button', ['type' => 'danger', 'text' => 'Danger']) ?>
                    <?= component('ui/button', ['type' => 'warning', 'text' => 'Warning']) ?>
                    <?= component('ui/button', ['type' => 'info', 'text' => 'Info']) ?>
                </div>
            </div>
            
            <!-- Variantes -->
            <div>
                <h3 class="text-sm font-medium text-gray-700 mb-3">Variantes</h3>
                <div class="flex flex-wrap gap-3">
                    <?= component('ui/button', ['variant' => 'solid', 'text' => 'Solid']) ?>
                    <?= component('ui/button', ['variant' => 'outline', 'text' => 'Outline']) ?>
                    <?= component('ui/button', ['variant' => 'ghost', 'text' => 'Ghost']) ?>
                    <?= component('ui/button', ['variant' => 'link', 'text' => 'Link']) ?>
                </div>
            </div>
            
            <!-- Tamanhos -->
            <div>
                <h3 class="text-sm font-medium text-gray-700 mb-3">Tamanhos</h3>
                <div class="flex flex-wrap items-center gap-3">
                    <?= component('ui/button', ['size' => 'xs', 'text' => 'Extra Small']) ?>
                    <?= component('ui/button', ['size' => 'sm', 'text' => 'Small']) ?>
                    <?= component('ui/button', ['size' => 'md', 'text' => 'Medium']) ?>
                    <?= component('ui/button', ['size' => 'lg', 'text' => 'Large']) ?>
                    <?= component('ui/button', ['size' => 'xl', 'text' => 'Extra Large']) ?>
                </div>
            </div>
            
            <!-- Com ícones -->
            <div>
                <h3 class="text-sm font-medium text-gray-700 mb-3">Com Ícones</h3>
                <div class="flex flex-wrap gap-3">
                    <?= component('ui/button', ['icon' => 'fas fa-plus', 'text' => 'Adicionar']) ?>
                    <?= component('ui/button', ['icon' => 'fas fa-download', 'iconPosition' => 'right', 'text' => 'Download']) ?>
                    <?= component('ui/button', ['icon' => 'fas fa-heart', 'variant' => 'outline', 'type' => 'danger']) ?>
                    <?= component('ui/button', ['loading' => true, 'text' => 'Carregando...']) ?>
                </div>
            </div>
            
            <!-- Estados -->
            <div>
                <h3 class="text-sm font-medium text-gray-700 mb-3">Estados</h3>
                <div class="flex flex-wrap gap-3">
                    <?= component('ui/button', ['text' => 'Normal']) ?>
                    <?= component('ui/button', ['disabled' => true, 'text' => 'Desabilitado']) ?>
                    <?= component('ui/button', ['loading' => true, 'text' => 'Carregando']) ?>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Seção de Inputs -->
    <section class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Componente Input</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Input básico -->
            <div>
                <?= component('ui/input', [
                    'label' => 'Nome completo',
                    'name' => 'name',
                    'placeholder' => 'Digite seu nome',
                    'required' => true
                ]) ?>
            </div>
            
            <!-- Input com ícone -->
            <div>
                <?= component('ui/input', [
                    'type' => 'email',
                    'label' => 'E-mail',
                    'name' => 'email',
                    'placeholder' => 'seu@email.com',
                    'icon' => 'fas fa-envelope',
                    'help' => 'Usaremos este e-mail para contato'
                ]) ?>
            </div>
            
            <!-- Input com erro -->
            <div>
                <?= component('ui/input', [
                    'type' => 'password',
                    'label' => 'Senha',
                    'name' => 'password',
                    'icon' => 'fas fa-lock',
                    'error' => 'A senha deve ter pelo menos 8 caracteres'
                ]) ?>
            </div>
            
            <!-- Input desabilitado -->
            <div>
                <?= component('ui/input', [
                    'label' => 'Campo desabilitado',
                    'name' => 'disabled',
                    'value' => 'Valor fixo',
                    'disabled' => true
                ]) ?>
            </div>
            
            <!-- Variantes -->
            <div>
                <?= component('ui/input', [
                    'label' => 'Input preenchido',
                    'name' => 'filled',
                    'variant' => 'filled',
                    'placeholder' => 'Variante filled'
                ]) ?>
            </div>
            
            <div>
                <?= component('ui/input', [
                    'label' => 'Input sublinhado',
                    'name' => 'underlined',
                    'variant' => 'underlined',
                    'placeholder' => 'Variante underlined'
                ]) ?>
            </div>
        </div>
    </section>
    
    <!-- Seção de Cards -->
    <section class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Componente Card</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Card básico -->
            <?= component('ui/card', [
                'title' => 'Card Básico',
                'subtitle' => 'Exemplo de card simples',
                'content' => 'Este é um exemplo de card básico com título, subtítulo e conteúdo. Perfeito para exibir informações de forma organizada.'
            ]) ?>
            
            <!-- Card com imagem -->
            <?= component('ui/card', [
                'title' => 'Card com Imagem',
                'image' => 'https://via.placeholder.com/400x200',
                'imageAlt' => 'Imagem de exemplo',
                'badge' => 'Novo',
                'badgeType' => 'success',
                'content' => 'Card com imagem no topo e badge de destaque.',
                'actions' => [
                    ['text' => 'Ver mais', 'href' => '#'],
                    ['text' => 'Compartilhar', 'onclick' => 'alert("Compartilhar")']
                ]
            ]) ?>
            
            <!-- Card clicável -->
            <?= component('ui/card', [
                'title' => 'Card Clicável',
                'subtitle' => 'Com efeito hover',
                'content' => 'Este card é clicável e possui efeito hover.',
                'href' => '#',
                'hover' => true,
                'shadow' => 'lg'
            ]) ?>
            
            <!-- Card com variantes -->
            <?= component('ui/card', [
                'title' => 'Card Elevado',
                'variant' => 'elevated',
                'content' => 'Card com variante elevada para destaque.',
                'padding' => 'lg'
            ]) ?>
            
            <?= component('ui/card', [
                'title' => 'Card Preenchido',
                'variant' => 'filled',
                'content' => 'Card com fundo preenchido.',
                'rounded' => 'xl'
            ]) ?>
            
            <?= component('ui/card', [
                'title' => 'Card Compacto',
                'content' => 'Card com padding reduzido.',
                'padding' => 'sm',
                'size' => 'sm'
            ]) ?>
        </div>
    </section>
    
    <!-- Seção de Tabela -->
    <section class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Componente Table</h2>
        
        <?= component('ui/table', [
            'columns' => $tableColumns,
            'data' => $tableData,
            'striped' => true,
            'hover' => true,
            'sortable' => true,
            'searchable' => true,
            'selection' => true,
            'actions' => $tableActions,
            'pagination' => [
                'current_page' => 1,
                'total_pages' => 3,
                'from' => 1,
                'to' => 5,
                'total' => 15,
                'prev_url' => '#',
                'next_url' => '#'
            ]
        ]) ?>
    </section>
    
    <!-- Seção de Modal -->
    <section class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">Componente Modal</h2>
        
        <div class="flex flex-wrap gap-3 mb-6">
            <?= component('ui/button', [
                'text' => 'Modal Básico',
                'onclick' => "openModal('modal-basic')"
            ]) ?>
            
            <?= component('ui/button', [
                'text' => 'Modal Grande',
                'variant' => 'outline',
                'onclick' => "openModal('modal-large')"
            ]) ?>
            
            <?= component('ui/button', [
                'text' => 'Modal de Confirmação',
                'type' => 'danger',
                'onclick' => "openModal('modal-confirm')"
            ]) ?>
        </div>
        
        <!-- Modal básico -->
        <?= component('ui/modal', [
            'id' => 'modal-basic',
            'title' => 'Modal Básico',
            'content' => '<p>Este é um exemplo de modal básico. Você pode colocar qualquer conteúdo aqui.</p><p class="mt-4">O modal pode ser fechado clicando no X, pressionando ESC ou clicando fora dele.</p>',
            'showFooter' => true,
            'actions' => [
                ['text' => 'Fechar', 'role' => 'close']
            ]
        ]) ?>
        
        <!-- Modal grande -->
        <?= component('ui/modal', [
            'id' => 'modal-large',
            'title' => 'Modal Grande',
            'size' => 'xl',
            'content' => '<div class="space-y-4"><p>Este é um modal maior, ideal para formulários ou conteúdo mais extenso.</p>' .
                        component('ui/input', ['label' => 'Nome', 'name' => 'modal_name']) .
                        component('ui/input', ['label' => 'E-mail', 'name' => 'modal_email', 'type' => 'email']) .
                        '<p>Você pode incluir qualquer componente dentro do modal.</p></div>',
            'showFooter' => true,
            'actions' => [
                ['text' => 'Cancelar', 'role' => 'cancel'],
                ['text' => 'Salvar', 'class' => 'px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700', 'role' => 'confirm']
            ]
        ]) ?>
        
        <!-- Modal de confirmação -->
        <?= component('ui/modal', [
            'id' => 'modal-confirm',
            'title' => 'Confirmar Ação',
            'size' => 'sm',
            'content' => '<div class="text-center"><div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4"><svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" /></svg></div><p>Tem certeza que deseja excluir este item? Esta ação não pode ser desfeita.</p></div>',
            'showFooter' => true,
            'actions' => [
                ['text' => 'Cancelar', 'role' => 'cancel'],
                ['text' => 'Excluir', 'class' => 'px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700', 'onclick' => 'alert("Item excluído!")', 'role' => 'close']
            ]
        ]) ?>
    </section>
    
</div>

<!-- Scripts específicos da página -->
<script>
function deleteUser(id) {
    if (confirm('Tem certeza que deseja excluir este usuário?')) {
        alert('Usuário ' + id + ' excluído!');
    }
}

// Exemplo de uso dos componentes via JavaScript
document.addEventListener('DOMContentLoaded', function() {
    console.log('Página de demonstração carregada!');
    
    // Você pode adicionar mais interações aqui
});
</script>

<style>
/* Estilos específicos da página */
.demo-section {
    @apply bg-white rounded-lg shadow-sm border border-gray-200 p-6;
}

.demo-section h2 {
    @apply text-xl font-semibold text-gray-900 mb-6;
}

.demo-section h3 {
    @apply text-sm font-medium text-gray-700 mb-3;
}
</style>