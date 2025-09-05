<?php
/**
 * Footer Component - E-commerce Neon Futurista
 * Componente de rodapé com links, informações e newsletter
 */

$config = [
    'company_name' => $company_name ?? 'NeonShop',
    'company_description' => $company_description ?? 'Sua loja de tecnologia com o melhor da inovação e design futurista.',
    'social_links' => $social_links ?? [
        'facebook' => '#',
        'instagram' => '#',
        'twitter' => '#',
        'youtube' => '#'
    ],
    'payment_methods' => $payment_methods ?? ['visa', 'mastercard', 'pix', 'boleto'],
    'security_badges' => $security_badges ?? ['ssl', 'google_safe'],
    'show_newsletter' => $show_newsletter ?? true
];
?>

<footer class="bg-gradient-to-b from-gray-900 to-black border-t border-gray-800">
    <!-- Main Footer -->
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <!-- Company Info -->
            <div class="space-y-4">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary-500 to-secondary-500 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/25">
                        <i class="fas fa-bolt text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold bg-gradient-to-r from-primary-400 to-secondary-400 bg-clip-text text-transparent">
                        <?= htmlspecialchars($config['company_name']) ?>
                    </h3>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed">
                    <?= htmlspecialchars($config['company_description']) ?>
                </p>
                
                <!-- Social Links -->
                <div class="flex space-x-4">
                    <?php foreach ($config['social_links'] as $platform => $url): ?>
                        <a href="<?= htmlspecialchars($url) ?>" class="w-10 h-10 bg-gray-800 hover:bg-gradient-to-br hover:from-primary-500 hover:to-secondary-500 rounded-lg flex items-center justify-center text-gray-400 hover:text-white transition-all duration-300 group">
                            <i class="fab fa-<?= $platform ?> group-hover:scale-110 transition-transform"></i>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="space-y-4">
                <h4 class="text-lg font-semibold text-white mb-4">Links Rápidos</h4>
                <ul class="space-y-2">
                    <li><a href="/" class="text-gray-400 hover:text-primary-400 transition-colors text-sm">Início</a></li>
                    <li><a href="/produtos" class="text-gray-400 hover:text-primary-400 transition-colors text-sm">Produtos</a></li>
                    <li><a href="/ofertas" class="text-gray-400 hover:text-primary-400 transition-colors text-sm">Ofertas</a></li>
                    <li><a href="/lancamentos" class="text-gray-400 hover:text-primary-400 transition-colors text-sm">Lançamentos</a></li>
                    <li><a href="/marcas" class="text-gray-400 hover:text-primary-400 transition-colors text-sm">Marcas</a></li>
                    <li><a href="/blog" class="text-gray-400 hover:text-primary-400 transition-colors text-sm">Blog</a></li>
                </ul>
            </div>

            <!-- Customer Service -->
            <div class="space-y-4">
                <h4 class="text-lg font-semibold text-white mb-4">Atendimento</h4>
                <ul class="space-y-2">
                    <li><a href="/contato" class="text-gray-400 hover:text-primary-400 transition-colors text-sm">Fale Conosco</a></li>
                    <li><a href="/rastreamento" class="text-gray-400 hover:text-primary-400 transition-colors text-sm">Rastrear Pedido</a></li>
                    <li><a href="/trocas-devolucoes" class="text-gray-400 hover:text-primary-400 transition-colors text-sm">Trocas e Devoluções</a></li>
                    <li><a href="/garantia" class="text-gray-400 hover:text-primary-400 transition-colors text-sm">Garantia</a></li>
                    <li><a href="/faq" class="text-gray-400 hover:text-primary-400 transition-colors text-sm">FAQ</a></li>
                    <li><a href="/suporte" class="text-gray-400 hover:text-primary-400 transition-colors text-sm">Suporte Técnico</a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <?php if ($config['show_newsletter']): ?>
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-white mb-4">Newsletter</h4>
                    <p class="text-gray-400 text-sm mb-4">
                        Receba ofertas exclusivas e novidades em primeira mão!
                    </p>
                    <form action="/newsletter" method="POST" class="space-y-3">
                        <div class="relative">
                            <input 
                                type="email" 
                                name="email" 
                                placeholder="Seu e-mail"
                                required
                                class="w-full px-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                            >
                        </div>
                        <button 
                            type="submit"
                            class="w-full px-4 py-3 bg-gradient-to-r from-primary-500 to-secondary-500 text-white rounded-lg hover:from-primary-600 hover:to-secondary-600 transition-all duration-300 shadow-lg shadow-primary-500/25 font-medium"
                        >
                            <i class="fas fa-paper-plane mr-2"></i>
                            Inscrever-se
                        </button>
                    </form>
                    
                    <!-- Contact Info -->
                    <div class="pt-4 space-y-2">
                        <div class="flex items-center text-gray-400 text-sm">
                            <i class="fas fa-phone mr-3 text-primary-400"></i>
                            (11) 9999-9999
                        </div>
                        <div class="flex items-center text-gray-400 text-sm">
                            <i class="fas fa-envelope mr-3 text-secondary-400"></i>
                            contato@neonshop.com
                        </div>
                        <div class="flex items-center text-gray-400 text-sm">
                            <i class="fas fa-clock mr-3 text-accent-400"></i>
                            Seg-Sex: 8h às 18h
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Payment & Security -->
    <div class="border-t border-gray-800">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <!-- Payment Methods -->
                <div class="flex items-center space-x-4">
                    <span class="text-gray-400 text-sm font-medium">Formas de Pagamento:</span>
                    <div class="flex items-center space-x-3">
                        <?php foreach ($config['payment_methods'] as $method): ?>
                            <div class="w-12 h-8 bg-gray-800 rounded border border-gray-700 flex items-center justify-center">
                                <?php if ($method === 'pix'): ?>
                                    <span class="text-xs font-bold text-primary-400">PIX</span>
                                <?php elseif ($method === 'boleto'): ?>
                                    <i class="fas fa-barcode text-gray-400 text-xs"></i>
                                <?php else: ?>
                                    <i class="fab fa-cc-<?= $method ?> text-gray-400"></i>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Security Badges -->
                <div class="flex items-center space-x-4">
                    <span class="text-gray-400 text-sm font-medium">Segurança:</span>
                    <div class="flex items-center space-x-3">
                        <?php foreach ($config['security_badges'] as $badge): ?>
                            <div class="flex items-center space-x-1 px-3 py-1 bg-gray-800 rounded border border-gray-700">
                                <?php if ($badge === 'ssl'): ?>
                                    <i class="fas fa-lock text-green-400 text-xs"></i>
                                    <span class="text-xs text-gray-400">SSL</span>
                                <?php elseif ($badge === 'google_safe'): ?>
                                    <i class="fab fa-google text-blue-400 text-xs"></i>
                                    <span class="text-xs text-gray-400">Safe</span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bottom Footer -->
    <div class="border-t border-gray-800 bg-black/50">
        <div class="container mx-auto px-4 py-4">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-2 md:space-y-0">
                <div class="text-gray-400 text-sm">
                    © <?= date('Y') ?> <?= htmlspecialchars($config['company_name']) ?>. Todos os direitos reservados.
                </div>
                <div class="flex items-center space-x-6 text-sm">
                    <a href="/privacidade" class="text-gray-400 hover:text-primary-400 transition-colors">Política de Privacidade</a>
                    <a href="/termos" class="text-gray-400 hover:text-primary-400 transition-colors">Termos de Uso</a>
                    <a href="/cookies" class="text-gray-400 hover:text-primary-400 transition-colors">Cookies</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- Back to Top Button -->
<button 
    id="back-to-top"
    class="fixed bottom-6 right-6 w-12 h-12 bg-gradient-to-br from-primary-500 to-secondary-500 text-white rounded-full shadow-lg shadow-primary-500/25 opacity-0 invisible transition-all duration-300 hover:scale-110 z-50"
    onclick="scrollToTop()"
>
    <i class="fas fa-chevron-up"></i>
</button>

<style>
.footer-glow {
    box-shadow: 0 -10px 30px rgba(59, 130, 246, 0.1);
}

#back-to-top.show {
    opacity: 1;
    visibility: visible;
}
</style>

<script>
// Back to top functionality
function scrollToTop() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    });
}

// Show/hide back to top button
window.addEventListener('scroll', function() {
    const backToTop = document.getElementById('back-to-top');
    if (window.pageYOffset > 300) {
        backToTop.classList.add('show');
    } else {
        backToTop.classList.remove('show');
    }
});

// Newsletter form submission
document.addEventListener('DOMContentLoaded', function() {
    const newsletterForm = document.querySelector('form[action="/newsletter"]');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = this.querySelector('input[name="email"]').value;
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;
            
            // Show loading state
            button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Inscrevendo...';
            button.disabled = true;
            
            // Simulate API call
            setTimeout(() => {
                // Show success message
                if (window.showAlert) {
                    window.showAlert('E-mail cadastrado com sucesso! Obrigado por se inscrever.', 'success');
                }
                
                // Reset form
                this.reset();
                button.innerHTML = originalText;
                button.disabled = false;
            }, 1500);
        });
    }
});
</script>