<?php
// Configurações da página
$page_title = 'Trocas e Devoluções - NeonShop';
$current_route = '/trocas-devolucoes';
$breadcrumbs = [
    ['name' => 'Home', 'url' => '/'],
    ['name' => 'Trocas e Devoluções', 'url' => '/trocas-devolucoes']
];

// Data da última atualização
$last_updated = '15 de Janeiro de 2024';

// Conteúdo da página
ob_start();
?>

<div class="bg-gray-900 min-h-screen py-8">
    <div class="container mx-auto px-4">
        
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Trocas e <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-600">Devoluções</span>
            </h1>
            <p class="text-xl text-gray-300 max-w-3xl mx-auto mb-4">
                Sua satisfação é nossa prioridade. Conheça nossa política de trocas e devoluções para comprar com tranquilidade.
            </p>
            <p class="text-sm text-gray-400">
                <i class="fas fa-calendar-alt mr-2"></i>
                Última atualização: <?= $last_updated ?>
            </p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Quick Actions -->
            <div class="lg:col-span-1">
                <div class="bg-gray-800 rounded-2xl p-6 card-shadow sticky top-8">
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                        <i class="fas fa-rocket mr-2 text-cyan-400"></i>
                        Ações Rápidas
                    </h3>
                    
                    <div class="space-y-3">
                        <a href="#solicitar-troca" class="block bg-gradient-to-r from-cyan-600 to-purple-600 text-white px-4 py-3 rounded-lg text-center font-medium hover:from-cyan-700 hover:to-purple-700 transition-all">
                            <i class="fas fa-exchange-alt mr-2"></i>
                            Solicitar Troca
                        </a>
                        
                        <a href="#solicitar-devolucao" class="block bg-gray-700 text-white px-4 py-3 rounded-lg text-center font-medium hover:bg-gray-600 transition-colors">
                            <i class="fas fa-undo mr-2"></i>
                            Solicitar Devolução
                        </a>
                        
                        <a href="#rastrear-solicitacao" class="block bg-gray-700 text-white px-4 py-3 rounded-lg text-center font-medium hover:bg-gray-600 transition-colors">
                            <i class="fas fa-search mr-2"></i>
                            Rastrear Solicitação
                        </a>
                    </div>
                    
                    <hr class="border-gray-600 my-6">
                    
                    <h4 class="text-white font-semibold mb-3">Índice</h4>
                    <nav class="space-y-2">
                        <a href="#prazos" class="block text-gray-300 hover:text-cyan-400 transition-colors py-1 text-sm">
                            Prazos
                        </a>
                        <a href="#condicoes" class="block text-gray-300 hover:text-cyan-400 transition-colors py-1 text-sm">
                            Condições
                        </a>
                        <a href="#processo" class="block text-gray-300 hover:text-cyan-400 transition-colors py-1 text-sm">
                            Como Solicitar
                        </a>
                        <a href="#custos" class="block text-gray-300 hover:text-cyan-400 transition-colors py-1 text-sm">
                            Custos
                        </a>
                        <a href="#reembolso" class="block text-gray-300 hover:text-cyan-400 transition-colors py-1 text-sm">
                            Reembolso
                        </a>
                        <a href="#faq" class="block text-gray-300 hover:text-cyan-400 transition-colors py-1 text-sm">
                            FAQ
                        </a>
                    </nav>
                </div>
            </div>
            
            <!-- Content -->
            <div class="lg:col-span-3">
                
                <!-- Resumo Rápido -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-green-900/30 border border-green-500/30 rounded-2xl p-6 text-center">
                        <div class="w-16 h-16 bg-green-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-calendar-check text-2xl text-green-400"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">30 Dias</h3>
                        <p class="text-green-200 text-sm">Prazo para trocas e devoluções</p>
                    </div>
                    
                    <div class="bg-blue-900/30 border border-blue-500/30 rounded-2xl p-6 text-center">
                        <div class="w-16 h-16 bg-blue-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-shipping-fast text-2xl text-blue-400"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Frete Grátis</h3>
                        <p class="text-blue-200 text-sm">Para defeitos de fabricação</p>
                    </div>
                    
                    <div class="bg-purple-900/30 border border-purple-500/30 rounded-2xl p-6 text-center">
                        <div class="w-16 h-16 bg-purple-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-money-bill-wave text-2xl text-purple-400"></i>
                        </div>
                        <h3 class="text-xl font-bold text-white mb-2">Reembolso</h3>
                        <p class="text-purple-200 text-sm">Em até 7 dias úteis</p>
                    </div>
                </div>
                
                <!-- Prazos -->
                <section id="prazos" class="bg-gray-800 rounded-2xl p-8 card-shadow mb-8">
                    <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                        <i class="fas fa-clock text-cyan-400 mr-3"></i>
                        Prazos para Trocas e Devoluções
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-700/50 rounded-lg p-6">
                            <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
                                <i class="fas fa-exchange-alt text-green-400 mr-2"></i>
                                Trocas
                            </h3>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-300">Produtos em geral</span>
                                    <span class="text-green-400 font-semibold">30 dias</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-300">Eletrônicos</span>
                                    <span class="text-green-400 font-semibold">30 dias</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-300">Roupas e calçados</span>
                                    <span class="text-green-400 font-semibold">30 dias</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-300">Produtos personalizados</span>
                                    <span class="text-red-400 font-semibold">Não aceito</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-700/50 rounded-lg p-6">
                            <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
                                <i class="fas fa-undo text-blue-400 mr-2"></i>
                                Devoluções
                            </h3>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-300">Arrependimento</span>
                                    <span class="text-blue-400 font-semibold">7 dias</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-300">Defeito de fabricação</span>
                                    <span class="text-blue-400 font-semibold">30 dias</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-300">Produto diferente</span>
                                    <span class="text-blue-400 font-semibold">30 dias</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-300">Garantia legal</span>
                                    <span class="text-blue-400 font-semibold">90 dias</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-yellow-900/30 border border-yellow-500/30 rounded-lg p-4 mt-6">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-info-circle text-yellow-400 mt-1"></i>
                            <div>
                                <p class="text-yellow-200 font-medium mb-1">Importante</p>
                                <p class="text-yellow-300 text-sm">
                                    Os prazos são contados a partir da data de recebimento do produto. Para compras online, você tem direito ao arrependimento em até 7 dias corridos, conforme o Código de Defesa do Consumidor.
                                </p>
                            </div>
                        </div>
                    </div>
                </section>
                
                <!-- Condições -->
                <section id="condicoes" class="bg-gray-800 rounded-2xl p-8 card-shadow mb-8">
                    <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                        <i class="fas fa-clipboard-check text-purple-400 mr-3"></i>
                        Condições para Trocas e Devoluções
                    </h2>
                    
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
                                <i class="fas fa-check-circle text-green-400 mr-2"></i>
                                Produtos Aceitos
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-check text-green-400 text-sm"></i>
                                        <span class="text-gray-300 text-sm">Produto em perfeito estado</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-check text-green-400 text-sm"></i>
                                        <span class="text-gray-300 text-sm">Embalagem original preservada</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-check text-green-400 text-sm"></i>
                                        <span class="text-gray-300 text-sm">Etiquetas e lacres intactos</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-check text-green-400 text-sm"></i>
                                        <span class="text-gray-300 text-sm">Acessórios inclusos</span>
                                    </div>
                                </div>
                                
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-check text-green-400 text-sm"></i>
                                        <span class="text-gray-300 text-sm">Manual e documentos</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-check text-green-400 text-sm"></i>
                                        <span class="text-gray-300 text-sm">Nota fiscal</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-check text-green-400 text-sm"></i>
                                        <span class="text-gray-300 text-sm">Dentro do prazo</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-check text-green-400 text-sm"></i>
                                        <span class="text-gray-300 text-sm">Sem sinais de uso</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
                                <i class="fas fa-times-circle text-red-400 mr-2"></i>
                                Produtos NÃO Aceitos
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-times text-red-400 text-sm"></i>
                                        <span class="text-gray-300 text-sm">Produtos personalizados</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-times text-red-400 text-sm"></i>
                                        <span class="text-gray-300 text-sm">Itens de higiene pessoal</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-times text-red-400 text-sm"></i>
                                        <span class="text-gray-300 text-sm">Produtos perecíveis</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-times text-red-400 text-sm"></i>
                                        <span class="text-gray-300 text-sm">Software/licenças ativadas</span>
                                    </div>
                                </div>
                                
                                <div class="space-y-2">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-times text-red-400 text-sm"></i>
                                        <span class="text-gray-300 text-sm">Produtos danificados pelo uso</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-times text-red-400 text-sm"></i>
                                        <span class="text-gray-300 text-sm">Itens sem embalagem original</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-times text-red-400 text-sm"></i>
                                        <span class="text-gray-300 text-sm">Produtos fora do prazo</span>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-times text-red-400 text-sm"></i>
                                        <span class="text-gray-300 text-sm">Itens em promoção especial*</span>
                                    </div>
                                </div>
                            </div>
                            
                            <p class="text-gray-400 text-xs mt-3">* Algumas promoções podem ter condições específicas</p>
                        </div>
                    </div>
                </section>
                
                <!-- Processo -->
                <section id="processo" class="bg-gray-800 rounded-2xl p-8 card-shadow mb-8">
                    <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                        <i class="fas fa-list-ol text-cyan-400 mr-3"></i>
                        Como Solicitar Troca ou Devolução
                    </h2>
                    
                    <div class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Troca -->
                            <div>
                                <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
                                    <i class="fas fa-exchange-alt text-green-400 mr-2"></i>
                                    Solicitar Troca
                                </h3>
                                
                                <div class="space-y-4">
                                    <div class="flex gap-4">
                                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">1</div>
                                        <div>
                                            <h4 class="text-white font-medium mb-1">Acesse sua conta</h4>
                                            <p class="text-gray-300 text-sm">Entre na sua conta e vá em "Meus Pedidos"</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-4">
                                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">2</div>
                                        <div>
                                            <h4 class="text-white font-medium mb-1">Selecione o produto</h4>
                                            <p class="text-gray-300 text-sm">Encontre o pedido e clique em "Solicitar Troca"</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-4">
                                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">3</div>
                                        <div>
                                            <h4 class="text-white font-medium mb-1">Preencha o formulário</h4>
                                            <p class="text-gray-300 text-sm">Informe o motivo e escolha o novo produto</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-4">
                                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">4</div>
                                        <div>
                                            <h4 class="text-white font-medium mb-1">Aguarde aprovação</h4>
                                            <p class="text-gray-300 text-sm">Analisaremos sua solicitação em até 24h</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-4">
                                        <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">5</div>
                                        <div>
                                            <h4 class="text-white font-medium mb-1">Envie o produto</h4>
                                            <p class="text-gray-300 text-sm">Use a etiqueta de postagem que enviaremos</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Devolução -->
                            <div>
                                <h3 class="text-xl font-semibold text-white mb-4 flex items-center">
                                    <i class="fas fa-undo text-blue-400 mr-2"></i>
                                    Solicitar Devolução
                                </h3>
                                
                                <div class="space-y-4">
                                    <div class="flex gap-4">
                                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">1</div>
                                        <div>
                                            <h4 class="text-white font-medium mb-1">Acesse sua conta</h4>
                                            <p class="text-gray-300 text-sm">Entre na sua conta e vá em "Meus Pedidos"</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-4">
                                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">2</div>
                                        <div>
                                            <h4 class="text-white font-medium mb-1">Selecione o produto</h4>
                                            <p class="text-gray-300 text-sm">Encontre o pedido e clique em "Solicitar Devolução"</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-4">
                                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">3</div>
                                        <div>
                                            <h4 class="text-white font-medium mb-1">Informe o motivo</h4>
                                            <p class="text-gray-300 text-sm">Descreva o problema ou motivo da devolução</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-4">
                                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">4</div>
                                        <div>
                                            <h4 class="text-white font-medium mb-1">Aguarde aprovação</h4>
                                            <p class="text-gray-300 text-sm">Analisaremos sua solicitação em até 24h</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex gap-4">
                                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0">5</div>
                                        <div>
                                            <h4 class="text-white font-medium mb-1">Receba o reembolso</h4>
                                            <p class="text-gray-300 text-sm">Após recebermos o produto, processamos o reembolso</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <!-- Formulários de Solicitação -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                    <!-- Solicitar Troca -->
                    <section id="solicitar-troca" class="bg-gray-800 rounded-2xl p-6 card-shadow">
                        <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                            <i class="fas fa-exchange-alt text-green-400 mr-2"></i>
                            Solicitar Troca
                        </h3>
                        
                        <form class="space-y-4">
                            <div>
                                <label class="block text-gray-300 text-sm font-medium mb-2">Número do Pedido</label>
                                <input type="text" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-cyan-400 focus:outline-none" placeholder="Ex: #12345">
                            </div>
                            
                            <div>
                                <label class="block text-gray-300 text-sm font-medium mb-2">Produto</label>
                                <select class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-cyan-400 focus:outline-none">
                                    <option>Selecione o produto</option>
                                    <option>Smartphone Galaxy S23</option>
                                    <option>Notebook Dell Inspiron</option>
                                    <option>Fone Bluetooth Sony</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-gray-300 text-sm font-medium mb-2">Motivo da Troca</label>
                                <select class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-cyan-400 focus:outline-none">
                                    <option>Selecione o motivo</option>
                                    <option>Tamanho incorreto</option>
                                    <option>Cor diferente</option>
                                    <option>Defeito de fabricação</option>
                                    <option>Produto diferente do anunciado</option>
                                    <option>Mudei de ideia</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-gray-300 text-sm font-medium mb-2">Observações</label>
                                <textarea class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-cyan-400 focus:outline-none h-20" placeholder="Descreva detalhes sobre a troca..."></textarea>
                            </div>
                            
                            <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-green-700 text-white py-3 rounded-lg font-medium hover:from-green-700 hover:to-green-800 transition-all">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Enviar Solicitação
                            </button>
                        </form>
                    </section>
                    
                    <!-- Solicitar Devolução -->
                    <section id="solicitar-devolucao" class="bg-gray-800 rounded-2xl p-6 card-shadow">
                        <h3 class="text-xl font-bold text-white mb-4 flex items-center">
                            <i class="fas fa-undo text-blue-400 mr-2"></i>
                            Solicitar Devolução
                        </h3>
                        
                        <form class="space-y-4">
                            <div>
                                <label class="block text-gray-300 text-sm font-medium mb-2">Número do Pedido</label>
                                <input type="text" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-cyan-400 focus:outline-none" placeholder="Ex: #12345">
                            </div>
                            
                            <div>
                                <label class="block text-gray-300 text-sm font-medium mb-2">Produto</label>
                                <select class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-cyan-400 focus:outline-none">
                                    <option>Selecione o produto</option>
                                    <option>Smartphone Galaxy S23</option>
                                    <option>Notebook Dell Inspiron</option>
                                    <option>Fone Bluetooth Sony</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-gray-300 text-sm font-medium mb-2">Motivo da Devolução</label>
                                <select class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-cyan-400 focus:outline-none">
                                    <option>Selecione o motivo</option>
                                    <option>Arrependimento da compra</option>
                                    <option>Defeito de fabricação</option>
                                    <option>Produto danificado na entrega</option>
                                    <option>Produto diferente do pedido</option>
                                    <option>Não funcionou como esperado</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-gray-300 text-sm font-medium mb-2">Descrição do Problema</label>
                                <textarea class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-cyan-400 focus:outline-none h-20" placeholder="Descreva o problema em detalhes..."></textarea>
                            </div>
                            
                            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-3 rounded-lg font-medium hover:from-blue-700 hover:to-blue-800 transition-all">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Enviar Solicitação
                            </button>
                        </form>
                    </section>
                </div>
                
                <!-- Rastrear Solicitação -->
                <section id="rastrear-solicitacao" class="bg-gray-800 rounded-2xl p-8 card-shadow mb-8">
                    <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                        <i class="fas fa-search text-purple-400 mr-3"></i>
                        Rastrear Solicitação
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <form class="space-y-4">
                                <div>
                                    <label class="block text-gray-300 text-sm font-medium mb-2">Número da Solicitação</label>
                                    <input type="text" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-cyan-400 focus:outline-none" placeholder="Ex: TRC-12345 ou DEV-67890">
                                </div>
                                
                                <div>
                                    <label class="block text-gray-300 text-sm font-medium mb-2">E-mail do Pedido</label>
                                    <input type="email" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-4 py-2 text-white focus:border-cyan-400 focus:outline-none" placeholder="seu@email.com">
                                </div>
                                
                                <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-purple-700 text-white py-3 rounded-lg font-medium hover:from-purple-700 hover:to-purple-800 transition-all">
                                    <i class="fas fa-search mr-2"></i>
                                    Rastrear Solicitação
                                </button>
                            </form>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold text-white mb-4">Status das Solicitações</h3>
                            
                            <div class="space-y-3">
                                <div class="flex items-center gap-3 p-3 bg-yellow-900/30 border border-yellow-500/30 rounded-lg">
                                    <i class="fas fa-clock text-yellow-400"></i>
                                    <div>
                                        <p class="text-white font-medium">Aguardando Análise</p>
                                        <p class="text-yellow-200 text-sm">Sua solicitação está sendo analisada</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-3 p-3 bg-blue-900/30 border border-blue-500/30 rounded-lg">
                                    <i class="fas fa-check text-blue-400"></i>
                                    <div>
                                        <p class="text-white font-medium">Aprovada</p>
                                        <p class="text-blue-200 text-sm">Solicitação aprovada, aguardando produto</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-3 p-3 bg-purple-900/30 border border-purple-500/30 rounded-lg">
                                    <i class="fas fa-shipping-fast text-purple-400"></i>
                                    <div>
                                        <p class="text-white font-medium">Em Trânsito</p>
                                        <p class="text-purple-200 text-sm">Produto a caminho do nosso centro</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center gap-3 p-3 bg-green-900/30 border border-green-500/30 rounded-lg">
                                    <i class="fas fa-check-double text-green-400"></i>
                                    <div>
                                        <p class="text-white font-medium">Finalizada</p>
                                        <p class="text-green-200 text-sm">Troca/devolução processada com sucesso</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <!-- Custos -->
                <section id="custos" class="bg-gray-800 rounded-2xl p-8 card-shadow mb-8">
                    <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                        <i class="fas fa-dollar-sign text-yellow-400 mr-3"></i>
                        Custos de Frete
                    </h2>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full border border-gray-600 rounded-lg">
                            <thead class="bg-gray-700">
                                <tr>
                                    <th class="text-left p-4 text-white font-semibold">Situação</th>
                                    <th class="text-left p-4 text-white font-semibold">Frete de Ida</th>
                                    <th class="text-left p-4 text-white font-semibold">Frete de Volta</th>
                                    <th class="text-left p-4 text-white font-semibold">Observação</th>
                                </tr>
                            </thead>
                            <tbody class="text-sm">
                                <tr class="border-t border-gray-600">
                                    <td class="p-4 font-medium text-green-400">Defeito de Fabricação</td>
                                    <td class="p-4 text-green-400">Grátis</td>
                                    <td class="p-4 text-green-400">Grátis</td>
                                    <td class="p-4 text-gray-300">Por nossa conta</td>
                                </tr>
                                <tr class="border-t border-gray-600">
                                    <td class="p-4 font-medium text-blue-400">Produto Diferente</td>
                                    <td class="p-4 text-blue-400">Grátis</td>
                                    <td class="p-4 text-blue-400">Grátis</td>
                                    <td class="p-4 text-gray-300">Erro nosso</td>
                                </tr>
                                <tr class="border-t border-gray-600">
                                    <td class="p-4 font-medium text-yellow-400">Arrependimento</td>
                                    <td class="p-4 text-yellow-400">Por sua conta</td>
                                    <td class="p-4 text-yellow-400">Por sua conta</td>
                                    <td class="p-4 text-gray-300">Direito do consumidor</td>
                                </tr>
                                <tr class="border-t border-gray-600">
                                    <td class="p-4 font-medium text-purple-400">Troca de Tamanho</td>
                                    <td class="p-4 text-purple-400">Por sua conta</td>
                                    <td class="p-4 text-purple-400">Grátis</td>
                                    <td class="p-4 text-gray-300">Primeira troca grátis</td>
                                </tr>
                                <tr class="border-t border-gray-600">
                                    <td class="p-4 font-medium text-red-400">Dano por Mau Uso</td>
                                    <td class="p-4 text-red-400">Por sua conta</td>
                                    <td class="p-4 text-red-400">Por sua conta</td>
                                    <td class="p-4 text-gray-300">Não coberto pela garantia</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
                
                <!-- Reembolso -->
                <section id="reembolso" class="bg-gray-800 rounded-2xl p-8 card-shadow mb-8">
                    <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                        <i class="fas fa-money-bill-wave text-green-400 mr-3"></i>
                        Política de Reembolso
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h3 class="text-xl font-semibold text-white mb-4">Prazos de Reembolso</h3>
                            
                            <div class="space-y-3">
                                <div class="flex justify-between items-center p-3 bg-gray-700/50 rounded-lg">
                                    <span class="text-gray-300">Cartão de Crédito</span>
                                    <span class="text-green-400 font-semibold">5-7 dias úteis</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-gray-700/50 rounded-lg">
                                    <span class="text-gray-300">Cartão de Débito</span>
                                    <span class="text-green-400 font-semibold">5-7 dias úteis</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-gray-700/50 rounded-lg">
                                    <span class="text-gray-300">PIX</span>
                                    <span class="text-green-400 font-semibold">1-2 dias úteis</span>
                                </div>
                                <div class="flex justify-between items-center p-3 bg-gray-700/50 rounded-lg">
                                    <span class="text-gray-300">Boleto</span>
                                    <span class="text-green-400 font-semibold">5-10 dias úteis</span>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-xl font-semibold text-white mb-4">Formas de Reembolso</h3>
                            
                            <div class="space-y-4">
                                <div class="p-4 bg-blue-900/30 border border-blue-500/30 rounded-lg">
                                    <h4 class="text-white font-semibold mb-2 flex items-center">
                                        <i class="fas fa-credit-card text-blue-400 mr-2"></i>
                                        Mesmo Meio de Pagamento
                                    </h4>
                                    <p class="text-blue-200 text-sm">O reembolso será feito no mesmo cartão ou conta utilizada na compra.</p>
                                </div>
                                
                                <div class="p-4 bg-green-900/30 border border-green-500/30 rounded-lg">
                                    <h4 class="text-white font-semibold mb-2 flex items-center">
                                        <i class="fas fa-university text-green-400 mr-2"></i>
                                        Transferência Bancária
                                    </h4>
                                    <p class="text-green-200 text-sm">Em casos especiais, podemos fazer transferência para sua conta corrente.</p>
                                </div>
                                
                                <div class="p-4 bg-purple-900/30 border border-purple-500/30 rounded-lg">
                                    <h4 class="text-white font-semibold mb-2 flex items-center">
                                        <i class="fas fa-gift text-purple-400 mr-2"></i>
                                        Crédito na Loja
                                    </h4>
                                    <p class="text-purple-200 text-sm">Opção de receber crédito para usar em futuras compras (com bônus de 5%).</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <!-- FAQ -->
                <section id="faq" class="bg-gray-800 rounded-2xl p-8 card-shadow">
                    <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                        <i class="fas fa-question-circle text-cyan-400 mr-3"></i>
                        Perguntas Frequentes
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="faq-item border border-gray-600 rounded-lg">
                            <button class="faq-question w-full text-left p-4 flex justify-between items-center hover:bg-gray-700/50 transition-colors">
                                <span class="text-white font-medium">Posso trocar um produto por outro de valor diferente?</span>
                                <i class="fas fa-chevron-down text-gray-400 transform transition-transform"></i>
                            </button>
                            <div class="faq-answer hidden p-4 border-t border-gray-600 bg-gray-700/30">
                                <p class="text-gray-300 text-sm">Sim! Se o novo produto for mais caro, você paga a diferença. Se for mais barato, devolvemos a diferença ou geramos crédito na loja.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item border border-gray-600 rounded-lg">
                            <button class="faq-question w-full text-left p-4 flex justify-between items-center hover:bg-gray-700/50 transition-colors">
                                <span class="text-white font-medium">Como funciona a garantia dos produtos?</span>
                                <i class="fas fa-chevron-down text-gray-400 transform transition-transform"></i>
                            </button>
                            <div class="faq-answer hidden p-4 border-t border-gray-600 bg-gray-700/30">
                                <p class="text-gray-300 text-sm">Todos os produtos têm garantia legal de 90 dias. Eletrônicos têm garantia adicional do fabricante (geralmente 12 meses). Defeitos de fabricação são cobertos integralmente.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item border border-gray-600 rounded-lg">
                            <button class="faq-question w-full text-left p-4 flex justify-between items-center hover:bg-gray-700/50 transition-colors">
                                <span class="text-white font-medium">Posso cancelar uma solicitação de troca/devolução?</span>
                                <i class="fas fa-chevron-down text-gray-400 transform transition-transform"></i>
                            </button>
                            <div class="faq-answer hidden p-4 border-t border-gray-600 bg-gray-700/30">
                                <p class="text-gray-300 text-sm">Sim, você pode cancelar enquanto a solicitação estiver com status "Aguardando Análise". Após aprovação e envio do produto, não é possível cancelar.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item border border-gray-600 rounded-lg">
                            <button class="faq-question w-full text-left p-4 flex justify-between items-center hover:bg-gray-700/50 transition-colors">
                                <span class="text-white font-medium">O que acontece se o produto chegar danificado no retorno?</span>
                                <i class="fas fa-chevron-down text-gray-400 transform transition-transform"></i>
                            </button>
                            <div class="faq-answer hidden p-4 border-t border-gray-600 bg-gray-700/30">
                                <p class="text-gray-300 text-sm">Recomendamos embalar bem o produto. Se chegar danificado por problemas no transporte, a transportadora é responsável. Por isso, sempre guarde o comprovante de postagem.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item border border-gray-600 rounded-lg">
                            <button class="faq-question w-full text-left p-4 flex justify-between items-center hover:bg-gray-700/50 transition-colors">
                                <span class="text-white font-medium">Produtos em promoção podem ser trocados?</span>
                                <i class="fas fa-chevron-down text-gray-400 transform transition-transform"></i>
                            </button>
                            <div class="faq-answer hidden p-4 border-t border-gray-600 bg-gray-700/30">
                                <p class="text-gray-300 text-sm">Sim, produtos em promoção seguem a mesma política. Porém, algumas promoções especiais (liquidação, outlet) podem ter condições diferenciadas, sempre informadas na página do produto.</p>
                            </div>
                        </div>
                        
                        <div class="faq-item border border-gray-600 rounded-lg">
                            <button class="faq-question w-full text-left p-4 flex justify-between items-center hover:bg-gray-700/50 transition-colors">
                                <span class="text-white font-medium">Quanto tempo demora para processar uma troca?</span>
                                <i class="fas fa-chevron-down text-gray-400 transform transition-transform"></i>
                            </button>
                            <div class="faq-answer hidden p-4 border-t border-gray-600 bg-gray-700/30">
                                <p class="text-gray-300 text-sm">Após recebermos o produto, analisamos em até 2 dias úteis. Se aprovado, o novo produto é enviado em até 1 dia útil. O prazo total depende do frete.</p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>

<style>
.card-shadow {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.05);
}

.faq-question:hover .fa-chevron-down {
    color: #06b6d4;
}

.faq-item.active .fa-chevron-down {
    transform: rotate(180deg);
    color: #06b6d4;
}

/* Smooth scrolling for anchor links */
html {
    scroll-behavior: smooth;
}

/* Form focus states */
input:focus, select:focus, textarea:focus {
    box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.1);
}
</style>

<script>
// FAQ Toggle
document.addEventListener('DOMContentLoaded', function() {
    const faqItems = document.querySelectorAll('.faq-item');
    
    faqItems.forEach(item => {
        const question = item.querySelector('.faq-question');
        const answer = item.querySelector('.faq-answer');
        
        question.addEventListener('click', () => {
            const isActive = item.classList.contains('active');
            
            // Close all other FAQ items
            faqItems.forEach(otherItem => {
                otherItem.classList.remove('active');
                otherItem.querySelector('.faq-answer').classList.add('hidden');
            });
            
            // Toggle current item
            if (!isActive) {
                item.classList.add('active');
                answer.classList.remove('hidden');
            }
        });
    });
    
    // Form submissions
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Simulate form submission
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enviando...';
            submitBtn.disabled = true;
            
            setTimeout(() => {
                submitBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Enviado com Sucesso!';
                submitBtn.classList.remove('from-green-600', 'to-green-700', 'from-blue-600', 'to-blue-700', 'from-purple-600', 'to-purple-700');
                submitBtn.classList.add('from-green-500', 'to-green-600');
                
                setTimeout(() => {
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('from-green-500', 'to-green-600');
                    
                    if (form.closest('#solicitar-troca')) {
                        submitBtn.classList.add('from-green-600', 'to-green-700');
                    } else if (form.closest('#solicitar-devolucao')) {
                        submitBtn.classList.add('from-blue-600', 'to-blue-700');
                    } else {
                        submitBtn.classList.add('from-purple-600', 'to-purple-700');
                    }
                    
                    form.reset();
                }, 2000);
            }, 1500);
        });
    });
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>