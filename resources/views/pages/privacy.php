<?php
// Configurações da página
$page_title = 'Política de Privacidade - NeonShop';
$current_route = '/privacidade';
$breadcrumbs = [
    ['name' => 'Home', 'url' => '/'],
    ['name' => 'Política de Privacidade', 'url' => '/privacidade']
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
                Política de <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-600">Privacidade</span>
            </h1>
            <p class="text-xl text-gray-300 max-w-3xl mx-auto mb-4">
                Sua privacidade é importante para nós. Esta política explica como coletamos, usamos e protegemos suas informações pessoais.
            </p>
            <p class="text-sm text-gray-400">
                <i class="fas fa-calendar-alt mr-2"></i>
                Última atualização: <?= $last_updated ?>
            </p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            
            <!-- Table of Contents -->
            <div class="lg:col-span-1">
                <div class="bg-gray-800 rounded-2xl p-6 card-shadow sticky top-8">
                    <h3 class="text-lg font-semibold text-white mb-4 flex items-center">
                        <i class="fas fa-list mr-2 text-cyan-400"></i>
                        Índice
                    </h3>
                    
                    <nav class="space-y-2">
                        <a href="#introducao" class="block text-gray-300 hover:text-cyan-400 transition-colors py-1 text-sm">
                            1. Introdução
                        </a>
                        <a href="#informacoes-coletadas" class="block text-gray-300 hover:text-cyan-400 transition-colors py-1 text-sm">
                            2. Informações Coletadas
                        </a>
                        <a href="#uso-informacoes" class="block text-gray-300 hover:text-cyan-400 transition-colors py-1 text-sm">
                            3. Uso das Informações
                        </a>
                        <a href="#compartilhamento" class="block text-gray-300 hover:text-cyan-400 transition-colors py-1 text-sm">
                            4. Compartilhamento
                        </a>
                        <a href="#cookies" class="block text-gray-300 hover:text-cyan-400 transition-colors py-1 text-sm">
                            5. Cookies e Tecnologias
                        </a>
                        <a href="#seguranca" class="block text-gray-300 hover:text-cyan-400 transition-colors py-1 text-sm">
                            6. Segurança
                        </a>
                        <a href="#direitos" class="block text-gray-300 hover:text-cyan-400 transition-colors py-1 text-sm">
                            7. Seus Direitos
                        </a>
                        <a href="#menores" class="block text-gray-300 hover:text-cyan-400 transition-colors py-1 text-sm">
                            8. Menores de Idade
                        </a>
                        <a href="#alteracoes" class="block text-gray-300 hover:text-cyan-400 transition-colors py-1 text-sm">
                            9. Alterações
                        </a>
                        <a href="#contato" class="block text-gray-300 hover:text-cyan-400 transition-colors py-1 text-sm">
                            10. Contato
                        </a>
                    </nav>
                </div>
            </div>
            
            <!-- Content -->
            <div class="lg:col-span-3">
                <div class="bg-gray-800 rounded-2xl p-8 card-shadow">
                    <div class="prose prose-invert max-w-none">
                        
                        <!-- 1. Introdução -->
                        <section id="introducao" class="mb-12">
                            <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                                <span class="w-8 h-8 bg-gradient-to-r from-cyan-500 to-purple-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">1</span>
                                Introdução
                            </h2>
                            
                            <div class="text-gray-300 space-y-4">
                                <p>
                                    A <strong class="text-white">NeonShop</strong> ("nós", "nosso" ou "empresa") está comprometida em proteger e respeitar sua privacidade. Esta Política de Privacidade explica como coletamos, usamos, armazenamos e compartilhamos suas informações pessoais quando você utiliza nosso site e serviços.
                                </p>
                                
                                <p>
                                    Ao utilizar nossos serviços, você concorda com a coleta e uso de informações de acordo com esta política. Se você não concordar com qualquer parte desta política, recomendamos que não utilize nossos serviços.
                                </p>
                                
                                <div class="bg-blue-900/30 border border-blue-500/30 rounded-lg p-4">
                                    <div class="flex items-start gap-3">
                                        <i class="fas fa-info-circle text-blue-400 mt-1"></i>
                                        <div>
                                            <p class="text-blue-200 font-medium mb-1">Importante</p>
                                            <p class="text-blue-300 text-sm">
                                                Esta política está em conformidade com a Lei Geral de Proteção de Dados (LGPD) e outras regulamentações aplicáveis.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        
                        <!-- 2. Informações Coletadas -->
                        <section id="informacoes-coletadas" class="mb-12">
                            <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                                <span class="w-8 h-8 bg-gradient-to-r from-cyan-500 to-purple-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">2</span>
                                Informações que Coletamos
                            </h2>
                            
                            <div class="text-gray-300 space-y-6">
                                <div>
                                    <h3 class="text-xl font-semibold text-white mb-3">2.1 Informações Fornecidas por Você</h3>
                                    <ul class="list-disc list-inside space-y-2 ml-4">
                                        <li>Dados de cadastro (nome, e-mail, telefone, CPF)</li>
                                        <li>Endereços de entrega e cobrança</li>
                                        <li>Informações de pagamento (não armazenamos dados completos do cartão)</li>
                                        <li>Histórico de compras e preferências</li>
                                        <li>Comunicações conosco (e-mails, chat, formulários)</li>
                                        <li>Avaliações e comentários sobre produtos</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-xl font-semibold text-white mb-3">2.2 Informações Coletadas Automaticamente</h3>
                                    <ul class="list-disc list-inside space-y-2 ml-4">
                                        <li>Endereço IP e localização geográfica</li>
                                        <li>Tipo de dispositivo, navegador e sistema operacional</li>
                                        <li>Páginas visitadas e tempo de navegação</li>
                                        <li>Referências de sites que o direcionaram para nós</li>
                                        <li>Cookies e tecnologias similares</li>
                                    </ul>
                                </div>
                                
                                <div>
                                    <h3 class="text-xl font-semibold text-white mb-3">2.3 Informações de Terceiros</h3>
                                    <ul class="list-disc list-inside space-y-2 ml-4">
                                        <li>Dados de redes sociais (quando você conecta sua conta)</li>
                                        <li>Informações de parceiros de marketing (com seu consentimento)</li>
                                        <li>Dados de verificação de identidade e prevenção à fraude</li>
                                    </ul>
                                </div>
                            </div>
                        </section>
                        
                        <!-- 3. Uso das Informações -->
                        <section id="uso-informacoes" class="mb-12">
                            <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                                <span class="w-8 h-8 bg-gradient-to-r from-cyan-500 to-purple-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">3</span>
                                Como Usamos suas Informações
                            </h2>
                            
                            <div class="text-gray-300 space-y-4">
                                <p>Utilizamos suas informações pessoais para os seguintes propósitos:</p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-gray-700/50 rounded-lg p-4">
                                        <h4 class="text-white font-semibold mb-2 flex items-center">
                                            <i class="fas fa-shopping-cart text-green-400 mr-2"></i>
                                            Processamento de Pedidos
                                        </h4>
                                        <ul class="text-sm space-y-1">
                                            <li>• Processar e entregar seus pedidos</li>
                                            <li>• Gerenciar pagamentos e faturas</li>
                                            <li>• Fornecer suporte ao cliente</li>
                                        </ul>
                                    </div>
                                    
                                    <div class="bg-gray-700/50 rounded-lg p-4">
                                        <h4 class="text-white font-semibold mb-2 flex items-center">
                                            <i class="fas fa-user-cog text-blue-400 mr-2"></i>
                                            Personalização
                                        </h4>
                                        <ul class="text-sm space-y-1">
                                            <li>• Personalizar sua experiência</li>
                                            <li>• Recomendar produtos relevantes</li>
                                            <li>• Melhorar nossos serviços</li>
                                        </ul>
                                    </div>
                                    
                                    <div class="bg-gray-700/50 rounded-lg p-4">
                                        <h4 class="text-white font-semibold mb-2 flex items-center">
                                            <i class="fas fa-envelope text-purple-400 mr-2"></i>
                                            Comunicação
                                        </h4>
                                        <ul class="text-sm space-y-1">
                                            <li>• Enviar atualizações de pedidos</li>
                                            <li>• Newsletter e promoções</li>
                                            <li>• Notificações importantes</li>
                                        </ul>
                                    </div>
                                    
                                    <div class="bg-gray-700/50 rounded-lg p-4">
                                        <h4 class="text-white font-semibold mb-2 flex items-center">
                                            <i class="fas fa-shield-alt text-red-400 mr-2"></i>
                                            Segurança
                                        </h4>
                                        <ul class="text-sm space-y-1">
                                            <li>• Prevenir fraudes</li>
                                            <li>• Garantir segurança da conta</li>
                                            <li>• Cumprir obrigações legais</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </section>
                        
                        <!-- 4. Compartilhamento -->
                        <section id="compartilhamento" class="mb-12">
                            <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                                <span class="w-8 h-8 bg-gradient-to-r from-cyan-500 to-purple-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">4</span>
                                Compartilhamento de Informações
                            </h2>
                            
                            <div class="text-gray-300 space-y-4">
                                <p>Não vendemos suas informações pessoais. Compartilhamos dados apenas nas seguintes situações:</p>
                                
                                <div class="space-y-4">
                                    <div class="border-l-4 border-yellow-500 pl-4">
                                        <h4 class="text-white font-semibold mb-2">Prestadores de Serviços</h4>
                                        <p class="text-sm">Compartilhamos com empresas que nos ajudam a operar nossos serviços (pagamento, entrega, marketing), sempre sob contratos de confidencialidade.</p>
                                    </div>
                                    
                                    <div class="border-l-4 border-blue-500 pl-4">
                                        <h4 class="text-white font-semibold mb-2">Obrigações Legais</h4>
                                        <p class="text-sm">Quando exigido por lei, ordem judicial ou para proteger nossos direitos e segurança.</p>
                                    </div>
                                    
                                    <div class="border-l-4 border-green-500 pl-4">
                                        <h4 class="text-white font-semibold mb-2">Transações Comerciais</h4>
                                        <p class="text-sm">Em caso de fusão, aquisição ou venda de ativos, suas informações podem ser transferidas (você será notificado).</p>
                                    </div>
                                    
                                    <div class="border-l-4 border-purple-500 pl-4">
                                        <h4 class="text-white font-semibold mb-2">Consentimento</h4>
                                        <p class="text-sm">Com seu consentimento explícito para finalidades específicas.</p>
                                    </div>
                                </div>
                            </div>
                        </section>
                        
                        <!-- 5. Cookies -->
                        <section id="cookies" class="mb-12">
                            <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                                <span class="w-8 h-8 bg-gradient-to-r from-cyan-500 to-purple-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">5</span>
                                Cookies e Tecnologias Similares
                            </h2>
                            
                            <div class="text-gray-300 space-y-4">
                                <p>Utilizamos cookies e tecnologias similares para melhorar sua experiência:</p>
                                
                                <div class="overflow-x-auto">
                                    <table class="w-full border border-gray-600 rounded-lg">
                                        <thead class="bg-gray-700">
                                            <tr>
                                                <th class="text-left p-3 text-white font-semibold">Tipo</th>
                                                <th class="text-left p-3 text-white font-semibold">Finalidade</th>
                                                <th class="text-left p-3 text-white font-semibold">Duração</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-sm">
                                            <tr class="border-t border-gray-600">
                                                <td class="p-3 font-medium text-green-400">Essenciais</td>
                                                <td class="p-3">Funcionamento básico do site</td>
                                                <td class="p-3">Sessão</td>
                                            </tr>
                                            <tr class="border-t border-gray-600">
                                                <td class="p-3 font-medium text-blue-400">Funcionais</td>
                                                <td class="p-3">Lembrar preferências e configurações</td>
                                                <td class="p-3">1 ano</td>
                                            </tr>
                                            <tr class="border-t border-gray-600">
                                                <td class="p-3 font-medium text-purple-400">Analíticos</td>
                                                <td class="p-3">Entender como você usa o site</td>
                                                <td class="p-3">2 anos</td>
                                            </tr>
                                            <tr class="border-t border-gray-600">
                                                <td class="p-3 font-medium text-yellow-400">Marketing</td>
                                                <td class="p-3">Personalizar anúncios e conteúdo</td>
                                                <td class="p-3">1 ano</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="bg-blue-900/30 border border-blue-500/30 rounded-lg p-4">
                                    <p class="text-blue-200 text-sm">
                                        <i class="fas fa-cog mr-2"></i>
                                        Você pode gerenciar suas preferências de cookies nas configurações do seu navegador ou através do nosso painel de preferências.
                                    </p>
                                </div>
                            </div>
                        </section>
                        
                        <!-- 6. Segurança -->
                        <section id="seguranca" class="mb-12">
                            <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                                <span class="w-8 h-8 bg-gradient-to-r from-cyan-500 to-purple-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">6</span>
                                Segurança das Informações
                            </h2>
                            
                            <div class="text-gray-300 space-y-4">
                                <p>Implementamos medidas técnicas e organizacionais para proteger suas informações:</p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="bg-green-900/30 border border-green-500/30 rounded-lg p-4 text-center">
                                        <i class="fas fa-lock text-3xl text-green-400 mb-3"></i>
                                        <h4 class="text-white font-semibold mb-2">Criptografia</h4>
                                        <p class="text-sm text-green-200">SSL/TLS para transmissão segura de dados</p>
                                    </div>
                                    
                                    <div class="bg-blue-900/30 border border-blue-500/30 rounded-lg p-4 text-center">
                                        <i class="fas fa-server text-3xl text-blue-400 mb-3"></i>
                                        <h4 class="text-white font-semibold mb-2">Armazenamento</h4>
                                        <p class="text-sm text-blue-200">Servidores seguros com acesso restrito</p>
                                    </div>
                                    
                                    <div class="bg-purple-900/30 border border-purple-500/30 rounded-lg p-4 text-center">
                                        <i class="fas fa-shield-alt text-3xl text-purple-400 mb-3"></i>
                                        <h4 class="text-white font-semibold mb-2">Monitoramento</h4>
                                        <p class="text-sm text-purple-200">Detecção de atividades suspeitas 24/7</p>
                                    </div>
                                </div>
                                
                                <div class="bg-yellow-900/30 border border-yellow-500/30 rounded-lg p-4">
                                    <div class="flex items-start gap-3">
                                        <i class="fas fa-exclamation-triangle text-yellow-400 mt-1"></i>
                                        <div>
                                            <p class="text-yellow-200 font-medium mb-1">Importante</p>
                                            <p class="text-yellow-300 text-sm">
                                                Embora implementemos as melhores práticas de segurança, nenhum sistema é 100% seguro. Recomendamos que você também tome precauções, como usar senhas fortes e não compartilhar suas credenciais.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        
                        <!-- 7. Seus Direitos -->
                        <section id="direitos" class="mb-12">
                            <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                                <span class="w-8 h-8 bg-gradient-to-r from-cyan-500 to-purple-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">7</span>
                                Seus Direitos
                            </h2>
                            
                            <div class="text-gray-300 space-y-4">
                                <p>De acordo com a LGPD, você tem os seguintes direitos:</p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="space-y-3">
                                        <div class="flex items-start gap-3">
                                            <i class="fas fa-eye text-cyan-400 mt-1"></i>
                                            <div>
                                                <h4 class="text-white font-semibold">Acesso</h4>
                                                <p class="text-sm">Saber quais dados temos sobre você</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start gap-3">
                                            <i class="fas fa-edit text-green-400 mt-1"></i>
                                            <div>
                                                <h4 class="text-white font-semibold">Correção</h4>
                                                <p class="text-sm">Corrigir dados incompletos ou incorretos</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start gap-3">
                                            <i class="fas fa-trash text-red-400 mt-1"></i>
                                            <div>
                                                <h4 class="text-white font-semibold">Exclusão</h4>
                                                <p class="text-sm">Solicitar a remoção de seus dados</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start gap-3">
                                            <i class="fas fa-download text-blue-400 mt-1"></i>
                                            <div>
                                                <h4 class="text-white font-semibold">Portabilidade</h4>
                                                <p class="text-sm">Receber seus dados em formato estruturado</p>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="space-y-3">
                                        <div class="flex items-start gap-3">
                                            <i class="fas fa-ban text-yellow-400 mt-1"></i>
                                            <div>
                                                <h4 class="text-white font-semibold">Oposição</h4>
                                                <p class="text-sm">Opor-se ao tratamento de seus dados</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start gap-3">
                                            <i class="fas fa-info-circle text-purple-400 mt-1"></i>
                                            <div>
                                                <h4 class="text-white font-semibold">Informação</h4>
                                                <p class="text-sm">Saber como seus dados são tratados</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start gap-3">
                                            <i class="fas fa-times-circle text-orange-400 mt-1"></i>
                                            <div>
                                                <h4 class="text-white font-semibold">Revogação</h4>
                                                <p class="text-sm">Retirar consentimento a qualquer momento</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start gap-3">
                                            <i class="fas fa-gavel text-pink-400 mt-1"></i>
                                            <div>
                                                <h4 class="text-white font-semibold">Revisão</h4>
                                                <p class="text-sm">Solicitar revisão de decisões automatizadas</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="bg-cyan-900/30 border border-cyan-500/30 rounded-lg p-4">
                                    <p class="text-cyan-200 text-sm">
                                        <i class="fas fa-envelope mr-2"></i>
                                        Para exercer seus direitos, entre em contato conosco através do e-mail 
                                        <a href="mailto:privacidade@neonshop.com.br" class="text-cyan-400 hover:text-cyan-300 underline">privacidade@neonshop.com.br</a>
                                    </p>
                                </div>
                            </div>
                        </section>
                        
                        <!-- 8. Menores de Idade -->
                        <section id="menores" class="mb-12">
                            <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                                <span class="w-8 h-8 bg-gradient-to-r from-cyan-500 to-purple-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">8</span>
                                Menores de Idade
                            </h2>
                            
                            <div class="text-gray-300 space-y-4">
                                <p>
                                    Nossos serviços são destinados a pessoas maiores de 18 anos. Não coletamos intencionalmente informações de menores de idade sem o consentimento dos pais ou responsáveis.
                                </p>
                                
                                <div class="bg-red-900/30 border border-red-500/30 rounded-lg p-4">
                                    <div class="flex items-start gap-3">
                                        <i class="fas fa-child text-red-400 mt-1"></i>
                                        <div>
                                            <p class="text-red-200 font-medium mb-1">Proteção de Menores</p>
                                            <p class="text-red-300 text-sm">
                                                Se tomarmos conhecimento de que coletamos dados de menores sem consentimento adequado, tomaremos medidas para remover essas informações o mais rápido possível.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        
                        <!-- 9. Alterações -->
                        <section id="alteracoes" class="mb-12">
                            <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                                <span class="w-8 h-8 bg-gradient-to-r from-cyan-500 to-purple-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">9</span>
                                Alterações nesta Política
                            </h2>
                            
                            <div class="text-gray-300 space-y-4">
                                <p>
                                    Podemos atualizar esta Política de Privacidade periodicamente para refletir mudanças em nossas práticas ou por outros motivos operacionais, legais ou regulamentares.
                                </p>
                                
                                <div class="bg-blue-900/30 border border-blue-500/30 rounded-lg p-4">
                                    <p class="text-blue-200 text-sm">
                                        <i class="fas fa-bell mr-2"></i>
                                        Notificaremos você sobre alterações significativas por e-mail ou através de um aviso em nosso site. Recomendamos que revise esta política regularmente.
                                    </p>
                                </div>
                            </div>
                        </section>
                        
                        <!-- 10. Contato -->
                        <section id="contato" class="mb-8">
                            <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                                <span class="w-8 h-8 bg-gradient-to-r from-cyan-500 to-purple-600 rounded-full flex items-center justify-center text-sm font-bold mr-3">10</span>
                                Entre em Contato
                            </h2>
                            
                            <div class="text-gray-300 space-y-4">
                                <p>
                                    Se você tiver dúvidas sobre esta Política de Privacidade ou sobre como tratamos suas informações pessoais, entre em contato conosco:
                                </p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-gray-700/50 rounded-lg p-4">
                                        <h4 class="text-white font-semibold mb-3 flex items-center">
                                            <i class="fas fa-user-shield text-cyan-400 mr-2"></i>
                                            Encarregado de Dados (DPO)
                                        </h4>
                                        <div class="space-y-2 text-sm">
                                            <p><i class="fas fa-envelope text-gray-400 mr-2"></i>privacidade@neonshop.com.br</p>
                                            <p><i class="fas fa-phone text-gray-400 mr-2"></i>(11) 99999-5555</p>
                                        </div>
                                    </div>
                                    
                                    <div class="bg-gray-700/50 rounded-lg p-4">
                                        <h4 class="text-white font-semibold mb-3 flex items-center">
                                            <i class="fas fa-building text-purple-400 mr-2"></i>
                                            Empresa
                                        </h4>
                                        <div class="space-y-2 text-sm">
                                            <p><i class="fas fa-map-marker-alt text-gray-400 mr-2"></i>Av. Paulista, 1000 - São Paulo/SP</p>
                                            <p><i class="fas fa-id-card text-gray-400 mr-2"></i>CNPJ: 00.000.000/0001-00</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card-shadow {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.05);
}

.prose h2 {
    scroll-margin-top: 2rem;
}

.prose h3 {
    scroll-margin-top: 2rem;
}

/* Smooth scrolling for anchor links */
html {
    scroll-behavior: smooth;
}
</style>

<script>
// Highlight current section in navigation
function updateActiveSection() {
    const sections = document.querySelectorAll('section[id]');
    const navLinks = document.querySelectorAll('nav a[href^="#"]');
    
    let currentSection = '';
    
    sections.forEach(section => {
        const rect = section.getBoundingClientRect();
        if (rect.top <= 100 && rect.bottom >= 100) {
            currentSection = section.id;
        }
    });
    
    navLinks.forEach(link => {
        link.classList.remove('text-cyan-400', 'font-semibold');
        link.classList.add('text-gray-300');
        
        if (link.getAttribute('href') === `#${currentSection}`) {
            link.classList.remove('text-gray-300');
            link.classList.add('text-cyan-400', 'font-semibold');
        }
    });
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Update active section on scroll
    window.addEventListener('scroll', updateActiveSection);
    
    // Initial update
    updateActiveSection();
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>