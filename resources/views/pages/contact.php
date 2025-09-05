<?php
// Configurações da página
$page_title = 'Contato - NeonShop';
$current_route = '/contato';
$breadcrumbs = [
    ['name' => 'Home', 'url' => '/'],
    ['name' => 'Contato', 'url' => '/contato']
];

// Informações de contato
$contact_info = [
    'phone' => '(11) 99999-9999',
    'whatsapp' => '5511999999999',
    'email' => 'contato@neonshop.com.br',
    'address' => 'Av. Paulista, 1000 - Bela Vista, São Paulo - SP, 01310-100',
    'hours' => [
        'Segunda a Sexta' => '08:00 às 18:00',
        'Sábado' => '09:00 às 15:00',
        'Domingo' => 'Fechado'
    ]
];

// Departamentos
$departments = [
    [
        'name' => 'Vendas',
        'email' => 'vendas@neonshop.com.br',
        'phone' => '(11) 99999-1111',
        'icon' => 'fas fa-shopping-cart',
        'description' => 'Dúvidas sobre produtos, preços e disponibilidade'
    ],
    [
        'name' => 'Suporte Técnico',
        'email' => 'suporte@neonshop.com.br',
        'phone' => '(11) 99999-2222',
        'icon' => 'fas fa-tools',
        'description' => 'Problemas técnicos, configurações e instalações'
    ],
    [
        'name' => 'Trocas e Devoluções',
        'email' => 'trocas@neonshop.com.br',
        'phone' => '(11) 99999-3333',
        'icon' => 'fas fa-undo',
        'description' => 'Solicitações de troca, devolução e garantia'
    ],
    [
        'name' => 'Financeiro',
        'email' => 'financeiro@neonshop.com.br',
        'phone' => '(11) 99999-4444',
        'icon' => 'fas fa-credit-card',
        'description' => 'Questões sobre pagamentos, faturas e parcelamentos'
    ]
];

// Conteúdo da página
ob_start();
?>

<div class="bg-gray-900 min-h-screen py-8">
    <div class="container mx-auto px-4">
        
        <!-- Page Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Entre em <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-600">Contato</span>
            </h1>
            <p class="text-xl text-gray-300 max-w-2xl mx-auto">
                Estamos aqui para ajudar! Entre em contato conosco através dos canais abaixo.
            </p>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            
            <!-- Contact Form -->
            <div class="lg:col-span-2">
                <div class="bg-gray-800 rounded-2xl p-8 card-shadow">
                    <h2 class="text-2xl font-semibold text-white mb-6 flex items-center">
                        <i class="fas fa-envelope mr-3 text-cyan-400"></i>
                        Envie sua Mensagem
                    </h2>
                    
                    <form id="contact-form" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Nome Completo *</label>
                                <input 
                                    type="text" 
                                    name="name"
                                    required
                                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition-all"
                                    placeholder="Seu nome completo"
                                >
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">E-mail *</label>
                                <input 
                                    type="email" 
                                    name="email"
                                    required
                                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition-all"
                                    placeholder="seu@email.com"
                                >
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Telefone</label>
                                <input 
                                    type="tel" 
                                    name="phone"
                                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition-all"
                                    placeholder="(11) 99999-9999"
                                    oninput="formatPhone(this)"
                                >
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">Departamento</label>
                                <select 
                                    name="department"
                                    class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition-all"
                                >
                                    <option value="">Selecione um departamento</option>
                                    <?php foreach ($departments as $dept): ?>
                                        <option value="<?= strtolower($dept['name']) ?>"><?= $dept['name'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Assunto *</label>
                            <input 
                                type="text" 
                                name="subject"
                                required
                                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition-all"
                                placeholder="Assunto da sua mensagem"
                            >
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-2">Mensagem *</label>
                            <textarea 
                                name="message"
                                required
                                rows="6"
                                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-cyan-400 focus:border-transparent transition-all resize-none"
                                placeholder="Descreva sua dúvida, sugestão ou problema..."
                            ></textarea>
                        </div>
                        
                        <div class="flex items-start gap-3">
                            <input 
                                type="checkbox" 
                                name="newsletter" 
                                id="newsletter"
                                class="mt-1 w-4 h-4 text-cyan-600 bg-gray-700 border-gray-600 rounded focus:ring-cyan-500 focus:ring-2"
                            >
                            <label for="newsletter" class="text-sm text-gray-300">
                                Desejo receber novidades e promoções por e-mail
                            </label>
                        </div>
                        
                        <button 
                            type="submit"
                            class="w-full btn-primary py-4 text-lg font-semibold rounded-xl hover:scale-105 transition-all duration-300"
                        >
                            <i class="fas fa-paper-plane mr-2"></i>
                            Enviar Mensagem
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Contact Info Sidebar -->
            <div class="space-y-6">
                
                <!-- Quick Contact -->
                <div class="bg-gray-800 rounded-2xl p-6 card-shadow">
                    <h3 class="text-xl font-semibold text-white mb-6 flex items-center">
                        <i class="fas fa-phone mr-3 text-green-400"></i>
                        Contato Rápido
                    </h3>
                    
                    <div class="space-y-4">
                        <a 
                            href="tel:<?= $contact_info['phone'] ?>"
                            class="flex items-center gap-3 p-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition-colors group"
                        >
                            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-phone text-white"></i>
                            </div>
                            <div>
                                <div class="text-white font-medium">Telefone</div>
                                <div class="text-sm text-gray-400"><?= $contact_info['phone'] ?></div>
                            </div>
                        </a>
                        
                        <a 
                            href="https://wa.me/<?= $contact_info['whatsapp'] ?>"
                            target="_blank"
                            class="flex items-center gap-3 p-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition-colors group"
                        >
                            <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fab fa-whatsapp text-white"></i>
                            </div>
                            <div>
                                <div class="text-white font-medium">WhatsApp</div>
                                <div class="text-sm text-gray-400">Chat online</div>
                            </div>
                        </a>
                        
                        <a 
                            href="mailto:<?= $contact_info['email'] ?>"
                            class="flex items-center gap-3 p-3 bg-gray-700 rounded-lg hover:bg-gray-600 transition-colors group"
                        >
                            <div class="w-10 h-10 bg-blue-500 rounded-full flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fas fa-envelope text-white"></i>
                            </div>
                            <div>
                                <div class="text-white font-medium">E-mail</div>
                                <div class="text-sm text-gray-400"><?= $contact_info['email'] ?></div>
                            </div>
                        </a>
                    </div>
                </div>
                
                <!-- Business Hours -->
                <div class="bg-gray-800 rounded-2xl p-6 card-shadow">
                    <h3 class="text-xl font-semibold text-white mb-6 flex items-center">
                        <i class="fas fa-clock mr-3 text-yellow-400"></i>
                        Horário de Atendimento
                    </h3>
                    
                    <div class="space-y-3">
                        <?php foreach ($contact_info['hours'] as $day => $hours): ?>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-300"><?= $day ?></span>
                                <span class="text-white font-medium"><?= $hours ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- Address -->
                <div class="bg-gray-800 rounded-2xl p-6 card-shadow">
                    <h3 class="text-xl font-semibold text-white mb-6 flex items-center">
                        <i class="fas fa-map-marker-alt mr-3 text-red-400"></i>
                        Endereço
                    </h3>
                    
                    <div class="space-y-4">
                        <p class="text-gray-300"><?= $contact_info['address'] ?></p>
                        
                        <a 
                            href="https://maps.google.com/?q=<?= urlencode($contact_info['address']) ?>"
                            target="_blank"
                            class="inline-flex items-center gap-2 text-cyan-400 hover:text-cyan-300 transition-colors"
                        >
                            <i class="fas fa-external-link-alt"></i>
                            Ver no Google Maps
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Departments Section -->
        <div class="mt-16">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-white mb-4">
                    Nossos <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-600">Departamentos</span>
                </h2>
                <p class="text-gray-300 max-w-2xl mx-auto">
                    Cada departamento tem especialistas prontos para atender suas necessidades específicas.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <?php foreach ($departments as $dept): ?>
                    <div class="bg-gray-800 rounded-2xl p-6 card-shadow hover:scale-105 transition-all duration-300 group">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-cyan-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                                <i class="<?= $dept['icon'] ?> text-2xl text-white"></i>
                            </div>
                            
                            <h3 class="text-xl font-semibold text-white mb-2"><?= $dept['name'] ?></h3>
                            <p class="text-sm text-gray-400 mb-4"><?= $dept['description'] ?></p>
                            
                            <div class="space-y-2">
                                <a 
                                    href="mailto:<?= $dept['email'] ?>"
                                    class="block text-cyan-400 hover:text-cyan-300 text-sm transition-colors"
                                >
                                    <i class="fas fa-envelope mr-1"></i>
                                    <?= $dept['email'] ?>
                                </a>
                                
                                <a 
                                    href="tel:<?= $dept['phone'] ?>"
                                    class="block text-green-400 hover:text-green-300 text-sm transition-colors"
                                >
                                    <i class="fas fa-phone mr-1"></i>
                                    <?= $dept['phone'] ?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- FAQ Section -->
        <div class="mt-16">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-white mb-4">
                    Perguntas <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-purple-600">Frequentes</span>
                </h2>
                <p class="text-gray-300 max-w-2xl mx-auto">
                    Encontre respostas rápidas para as dúvidas mais comuns.
                </p>
            </div>
            
            <div class="max-w-4xl mx-auto">
                <div class="space-y-4">
                    <div class="faq-item bg-gray-800 rounded-2xl overflow-hidden card-shadow">
                        <button class="faq-question w-full text-left p-6 flex items-center justify-between hover:bg-gray-700 transition-colors">
                            <span class="text-white font-medium">Qual o prazo de entrega?</span>
                            <i class="fas fa-chevron-down text-cyan-400 transition-transform"></i>
                        </button>
                        <div class="faq-answer hidden p-6 pt-0 text-gray-300">
                            <p>O prazo de entrega varia conforme sua localização e o método de envio escolhido. Geralmente, entregas para capitais levam de 1 a 3 dias úteis, enquanto para interior pode levar de 3 a 7 dias úteis.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item bg-gray-800 rounded-2xl overflow-hidden card-shadow">
                        <button class="faq-question w-full text-left p-6 flex items-center justify-between hover:bg-gray-700 transition-colors">
                            <span class="text-white font-medium">Como posso rastrear meu pedido?</span>
                            <i class="fas fa-chevron-down text-cyan-400 transition-transform"></i>
                        </button>
                        <div class="faq-answer hidden p-6 pt-0 text-gray-300">
                            <p>Após a confirmação do pagamento, você receberá um código de rastreamento por e-mail. Você também pode acompanhar o status do seu pedido na área "Meus Pedidos" em sua conta.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item bg-gray-800 rounded-2xl overflow-hidden card-shadow">
                        <button class="faq-question w-full text-left p-6 flex items-center justify-between hover:bg-gray-700 transition-colors">
                            <span class="text-white font-medium">Posso trocar ou devolver um produto?</span>
                            <i class="fas fa-chevron-down text-cyan-400 transition-transform"></i>
                        </button>
                        <div class="faq-answer hidden p-6 pt-0 text-gray-300">
                            <p>Sim! Você tem até 7 dias corridos após o recebimento para solicitar troca ou devolução. O produto deve estar em perfeitas condições, com embalagem original e todos os acessórios.</p>
                        </div>
                    </div>
                    
                    <div class="faq-item bg-gray-800 rounded-2xl overflow-hidden card-shadow">
                        <button class="faq-question w-full text-left p-6 flex items-center justify-between hover:bg-gray-700 transition-colors">
                            <span class="text-white font-medium">Quais formas de pagamento vocês aceitam?</span>
                            <i class="fas fa-chevron-down text-cyan-400 transition-transform"></i>
                        </button>
                        <div class="faq-answer hidden p-6 pt-0 text-gray-300">
                            <p>Aceitamos cartões de crédito (até 12x), cartão de débito, PIX (com 5% de desconto) e boleto bancário (com 3% de desconto). Todos os pagamentos são processados com segurança.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn-primary {
    @apply bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-medium transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2;
}

.card-shadow {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.05);
}

.faq-item.active .faq-question i {
    transform: rotate(180deg);
}

.faq-item.active .faq-answer {
    display: block;
}
</style>

<script>
function formatPhone(input) {
    let value = input.value.replace(/\D/g, '');
    if (value.length <= 11) {
        value = value.replace(/(\d{2})(\d)/, '($1) $2');
        value = value.replace(/(\d{5})(\d)/, '$1-$2');
    }
    input.value = value;
}

function validateContactForm() {
    const form = document.getElementById('contact-form');
    const formData = new FormData(form);
    
    // Required fields validation
    const requiredFields = ['name', 'email', 'subject', 'message'];
    
    for (const field of requiredFields) {
        if (!formData.get(field) || !formData.get(field).trim()) {
            if (window.showError) {
                window.showError(`Por favor, preencha o campo ${field === 'name' ? 'nome' : field === 'email' ? 'e-mail' : field === 'subject' ? 'assunto' : 'mensagem'}.`);
            }
            return false;
        }
    }
    
    // Email validation
    const email = formData.get('email');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        if (window.showError) {
            window.showError('Por favor, insira um e-mail válido.');
        }
        return false;
    }
    
    return true;
}

function submitContactForm() {
    if (!validateContactForm()) return;
    
    const form = document.getElementById('contact-form');
    const submitButton = form.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    // Show loading state
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Enviando...';
    submitButton.disabled = true;
    
    // Simulate form submission
    setTimeout(() => {
        if (window.showSuccess) {
            window.showSuccess('Mensagem enviada com sucesso! Entraremos em contato em breve.');
        }
        
        // Reset form
        form.reset();
        
        // Restore button
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    }, 2000);
}

function toggleFAQ(item) {
    const isActive = item.classList.contains('active');
    
    // Close all FAQ items
    document.querySelectorAll('.faq-item').forEach(faq => {
        faq.classList.remove('active');
    });
    
    // Open clicked item if it wasn't active
    if (!isActive) {
        item.classList.add('active');
    }
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    // Contact form submission
    document.getElementById('contact-form').addEventListener('submit', function(e) {
        e.preventDefault();
        submitContactForm();
    });
    
    // FAQ toggle
    document.querySelectorAll('.faq-question').forEach(question => {
        question.addEventListener('click', function() {
            toggleFAQ(this.closest('.faq-item'));
        });
    });
});
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>