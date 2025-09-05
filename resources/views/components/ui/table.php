<?php
/**
 * Componente Table
 * 
 * Props:
 * - columns: array (definição das colunas)
 * - data: array (dados da tabela)
 * - striped: boolean (linhas alternadas)
 * - bordered: boolean (bordas)
 * - hover: boolean (efeito hover)
 * - compact: boolean (espaçamento reduzido)
 * - responsive: boolean (scroll horizontal)
 * - sortable: boolean (ordenação)
 * - searchable: boolean (busca)
 * - pagination: array (configuração de paginação)
 * - actions: array (ações por linha)
 * - selection: boolean (checkbox de seleção)
 * - loading: boolean (estado de carregamento)
 * - empty: string (mensagem quando vazio)
 * - class: string (classes adicionais)
 * - tableClass: string
 * - headerClass: string
 * - bodyClass: string
 * - footerClass: string
 */

$columns = $columns ?? [];
$data = $data ?? [];
$striped = $striped ?? true;
$bordered = $bordered ?? false;
$hover = $hover ?? true;
$compact = $compact ?? false;
$responsive = $responsive ?? true;
$sortable = $sortable ?? false;
$searchable = $searchable ?? false;
$pagination = $pagination ?? null;
$actions = $actions ?? [];
$selection = $selection ?? false;
$loading = $loading ?? false;
$empty = $empty ?? 'Nenhum dado encontrado';
$class = $class ?? '';
$tableClass = $tableClass ?? '';
$headerClass = $headerClass ?? '';
$bodyClass = $bodyClass ?? '';
$footerClass = $footerClass ?? '';
$tableId = $tableId ?? uniqid('table_');

// Classes do container
$containerClasses = [];
if ($responsive) {
    $containerClasses[] = 'overflow-x-auto';
}
if ($class) {
    $containerClasses[] = $class;
}

// Classes da tabela
$tableClasses = [
    'min-w-full',
    'divide-y',
    'divide-gray-200'
];

if ($bordered) {
    $tableClasses[] = 'border border-gray-200';
}

if ($tableClass) {
    $tableClasses[] = $tableClass;
}

$tableClassString = implode(' ', $tableClasses);
$containerClassString = implode(' ', $containerClasses);

// Processar colunas
if (empty($columns) && !empty($data)) {
    // Auto-gerar colunas baseado nos dados
    $firstRow = reset($data);
    if (is_array($firstRow)) {
        foreach (array_keys($firstRow) as $key) {
            $columns[] = [
                'key' => $key,
                'label' => ucfirst(str_replace('_', ' ', $key)),
                'sortable' => true
            ];
        }
    }
}

// Função para obter valor da célula
function getCellValue($row, $column) {
    $key = $column['key'] ?? '';
    $formatter = $column['formatter'] ?? null;
    
    if (is_array($row)) {
        $value = $row[$key] ?? '';
    } elseif (is_object($row)) {
        $value = $row->$key ?? '';
    } else {
        $value = '';
    }
    
    if ($formatter && is_callable($formatter)) {
        return $formatter($value, $row);
    }
    
    return $value;
}
?>

<div class="<?= $containerClassString ?>" id="<?= $tableId ?>-container">
    
    <?php if ($searchable): ?>
        <!-- Barra de busca -->
        <div class="mb-4">
            <div class="relative">
                <input type="text" 
                       id="<?= $tableId ?>-search"
                       placeholder="Buscar..."
                       class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-primary-500 focus:border-primary-500">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <?php if ($loading): ?>
        <!-- Estado de carregamento -->
        <div class="flex items-center justify-center py-12">
            <div class="flex items-center space-x-2">
                <svg class="animate-spin h-5 w-5 text-primary-600" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span class="text-gray-600">Carregando...</span>
            </div>
        </div>
    <?php elseif (empty($data)): ?>
        <!-- Estado vazio -->
        <div class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Sem dados</h3>
            <p class="mt-1 text-sm text-gray-500"><?= htmlspecialchars($empty) ?></p>
        </div>
    <?php else: ?>
        <!-- Tabela -->
        <table class="<?= $tableClassString ?>" id="<?= $tableId ?>">
            
            <!-- Cabeçalho -->
            <thead class="bg-gray-50 <?= $headerClass ?>">
                <tr>
                    <?php if ($selection): ?>
                        <th scope="col" class="relative px-6 py-3">
                            <input type="checkbox" 
                                   id="<?= $tableId ?>-select-all"
                                   class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                        </th>
                    <?php endif; ?>
                    
                    <?php foreach ($columns as $column): ?>
                        <?php 
                        $label = $column['label'] ?? $column['key'] ?? '';
                        $sortable = $sortable && ($column['sortable'] ?? true);
                        $align = $column['align'] ?? 'left';
                        $width = $column['width'] ?? null;
                        $headerClass_col = $column['headerClass'] ?? '';
                        
                        $thClasses = [
                            'px-6',
                            $compact ? 'py-2' : 'py-3',
                            'text-xs',
                            'font-medium',
                            'text-gray-500',
                            'uppercase',
                            'tracking-wider'
                        ];
                        
                        if ($align === 'center') {
                            $thClasses[] = 'text-center';
                        } elseif ($align === 'right') {
                            $thClasses[] = 'text-right';
                        } else {
                            $thClasses[] = 'text-left';
                        }
                        
                        if ($sortable) {
                            $thClasses[] = 'cursor-pointer hover:bg-gray-100';
                        }
                        
                        if ($headerClass_col) {
                            $thClasses[] = $headerClass_col;
                        }
                        
                        $thClassString = implode(' ', $thClasses);
                        ?>
                        
                        <th scope="col" 
                            class="<?= $thClassString ?>"
                            <?= $width ? "style=\"width: $width\"" : '' ?>
                            <?= $sortable ? "onclick=\"sortTable('$tableId', '{$column['key']}')\"" : '' ?>
                            data-sort-key="<?= $column['key'] ?? '' ?>">
                            
                            <div class="flex items-center <?= $align === 'center' ? 'justify-center' : ($align === 'right' ? 'justify-end' : 'justify-start') ?>">
                                <?= htmlspecialchars($label) ?>
                                
                                <?php if ($sortable): ?>
                                    <span class="ml-2 flex-none rounded text-gray-400">
                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                        </svg>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </th>
                    <?php endforeach; ?>
                    
                    <?php if (!empty($actions)): ?>
                        <th scope="col" class="relative px-6 py-3">
                            <span class="sr-only">Ações</span>
                        </th>
                    <?php endif; ?>
                </tr>
            </thead>
            
            <!-- Corpo -->
            <tbody class="bg-white divide-y divide-gray-200 <?= $bodyClass ?>">
                <?php foreach ($data as $index => $row): ?>
                    <tr class="<?= $striped && $index % 2 === 1 ? 'bg-gray-50' : '' ?> <?= $hover ? 'hover:bg-gray-100' : '' ?> transition-colors">
                        
                        <?php if ($selection): ?>
                            <td class="relative px-6 py-4">
                                <input type="checkbox" 
                                       name="selected[]"
                                       value="<?= htmlspecialchars($row['id'] ?? $index) ?>"
                                       class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-500">
                            </td>
                        <?php endif; ?>
                        
                        <?php foreach ($columns as $column): ?>
                            <?php 
                            $value = getCellValue($row, $column);
                            $align = $column['align'] ?? 'left';
                            $cellClass = $column['cellClass'] ?? '';
                            
                            $tdClasses = [
                                'px-6',
                                $compact ? 'py-2' : 'py-4',
                                'whitespace-nowrap',
                                'text-sm'
                            ];
                            
                            if ($align === 'center') {
                                $tdClasses[] = 'text-center';
                            } elseif ($align === 'right') {
                                $tdClasses[] = 'text-right';
                            }
                            
                            if ($cellClass) {
                                $tdClasses[] = $cellClass;
                            }
                            
                            $tdClassString = implode(' ', $tdClasses);
                            ?>
                            
                            <td class="<?= $tdClassString ?>">
                                <?php if (isset($column['component'])): ?>
                                    <?= $column['component']($value, $row, $index) ?>
                                <?php else: ?>
                                    <span class="text-gray-900"><?= htmlspecialchars($value) ?></span>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; ?>
                        
                        <?php if (!empty($actions)): ?>
                            <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                                <div class="flex items-center justify-end space-x-2">
                                    <?php foreach ($actions as $action): ?>
                                        <?php if (is_callable($action)): ?>
                                            <?= $action($row, $index) ?>
                                        <?php elseif (is_array($action)): ?>
                                            <?php 
                                            $actionText = $action['text'] ?? 'Ação';
                                            $actionClass = $action['class'] ?? 'text-primary-600 hover:text-primary-900';
                                            $actionHref = $action['href'] ?? null;
                                            $actionOnclick = $action['onclick'] ?? null;
                                            
                                            if ($actionHref && is_callable($actionHref)) {
                                                $actionHref = $actionHref($row, $index);
                                            }
                                            
                                            if ($actionOnclick && is_callable($actionOnclick)) {
                                                $actionOnclick = $actionOnclick($row, $index);
                                            }
                                            ?>
                                            
                                            <?php if ($actionHref): ?>
                                                <a href="<?= htmlspecialchars($actionHref) ?>" 
                                                   class="<?= $actionClass ?>">
                                                    <?= htmlspecialchars($actionText) ?>
                                                </a>
                                            <?php else: ?>
                                                <button type="button" 
                                                        class="<?= $actionClass ?>"
                                                        <?= $actionOnclick ? "onclick=\"$actionOnclick\"" : '' ?>>
                                                    <?= htmlspecialchars($actionText) ?>
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                        <?php endif; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <?php if ($pagination): ?>
            <!-- Paginação -->
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 <?= $footerClass ?>">
                <div class="flex-1 flex justify-between sm:hidden">
                    <?php if ($pagination['current_page'] > 1): ?>
                        <a href="<?= $pagination['prev_url'] ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Anterior
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                        <a href="<?= $pagination['next_url'] ?>" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Próximo
                        </a>
                    <?php endif; ?>
                </div>
                
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Mostrando
                            <span class="font-medium"><?= $pagination['from'] ?></span>
                            até
                            <span class="font-medium"><?= $pagination['to'] ?></span>
                            de
                            <span class="font-medium"><?= $pagination['total'] ?></span>
                            resultados
                        </p>
                    </div>
                    
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <!-- Links de paginação aqui -->
                        </nav>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script>
// Funções da tabela
if (typeof window.tableFunctions === 'undefined') {
    window.tableFunctions = true;
    
    // Ordenação
    window.sortTable = function(tableId, column) {
        const table = document.getElementById(tableId);
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        
        // Determinar direção da ordenação
        const header = table.querySelector(`th[data-sort-key="${column}"]`);
        const currentSort = header.dataset.sortDirection || 'asc';
        const newSort = currentSort === 'asc' ? 'desc' : 'asc';
        
        // Limpar indicadores de ordenação anteriores
        table.querySelectorAll('th[data-sort-key]').forEach(th => {
            th.dataset.sortDirection = '';
            th.classList.remove('bg-gray-100');
        });
        
        // Definir nova ordenação
        header.dataset.sortDirection = newSort;
        header.classList.add('bg-gray-100');
        
        // Ordenar linhas
        const columnIndex = Array.from(header.parentNode.children).indexOf(header);
        
        rows.sort((a, b) => {
            const aValue = a.children[columnIndex].textContent.trim();
            const bValue = b.children[columnIndex].textContent.trim();
            
            // Tentar converter para número
            const aNum = parseFloat(aValue);
            const bNum = parseFloat(bValue);
            
            if (!isNaN(aNum) && !isNaN(bNum)) {
                return newSort === 'asc' ? aNum - bNum : bNum - aNum;
            }
            
            // Comparação de string
            return newSort === 'asc' 
                ? aValue.localeCompare(bValue)
                : bValue.localeCompare(aValue);
        });
        
        // Reordenar no DOM
        rows.forEach(row => tbody.appendChild(row));
    };
    
    // Busca
    window.searchTable = function(tableId, searchTerm) {
        const table = document.getElementById(tableId);
        const tbody = table.querySelector('tbody');
        const rows = tbody.querySelectorAll('tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            const matches = text.includes(searchTerm.toLowerCase());
            row.style.display = matches ? '' : 'none';
        });
    };
    
    // Seleção múltipla
    window.toggleSelectAll = function(tableId) {
        const table = document.getElementById(tableId + '-container');
        const selectAll = table.querySelector('#' + tableId + '-select-all');
        const checkboxes = table.querySelectorAll('input[name="selected[]"]');
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAll.checked;
        });
    };
}

// Inicializar funcionalidades para esta tabela
document.addEventListener('DOMContentLoaded', function() {
    const tableId = '<?= $tableId ?>';
    
    // Busca
    <?php if ($searchable): ?>
    const searchInput = document.getElementById(tableId + '-search');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            searchTable(tableId, this.value);
        });
    }
    <?php endif; ?>
    
    // Seleção múltipla
    <?php if ($selection): ?>
    const selectAll = document.getElementById(tableId + '-select-all');
    if (selectAll) {
        selectAll.addEventListener('change', function() {
            toggleSelectAll(tableId);
        });
    }
    <?php endif; ?>
});
</script>