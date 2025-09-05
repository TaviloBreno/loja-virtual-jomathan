<footer class="bg-white border-t border-secondary-200 mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Footer Principal -->
        <div class="py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                
                <!-- Logo e Descrição -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-code text-white text-sm"></i>
                        </div>
                        <span class="text-xl font-bold text-secondary-900">Sistema PHP</span>
                    </div>
                    
                    <p class="text-secondary-600 text-sm mb-4 max-w-md">
                        Sistema desenvolvido com arquitetura limpa, seguindo as melhores práticas de desenvolvimento PHP moderno. 
                        Construído para ser escalável, maintível e performático.
                    </p>
                    
                    <!-- Redes Sociais -->
                    <div class="flex space-x-4">
                        <a href="#" class="social-link" aria-label="GitHub">
                            <i class="fab fa-github"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="LinkedIn">
                            <i class="fab fa-linkedin"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="social-link" aria-label="Email">
                            <i class="fas fa-envelope"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Links Rápidos -->
                <div>
                    <h3 class="text-sm font-semibold text-secondary-900 uppercase tracking-wider mb-4">Links Rápidos</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="/" class="footer-link">
                                <i class="fas fa-home mr-2 text-xs"></i>
                                Início
                            </a>
                        </li>
                        <li>
                            <a href="/users" class="footer-link">
                                <i class="fas fa-users mr-2 text-xs"></i>
                                Usuários
                            </a>
                        </li>
                        <li>
                            <a href="/reports" class="footer-link">
                                <i class="fas fa-chart-bar mr-2 text-xs"></i>
                                Relatórios
                            </a>
                        </li>
                        <li>
                            <a href="/settings" class="footer-link">
                                <i class="fas fa-cog mr-2 text-xs"></i>
                                Configurações
                            </a>
                        </li>
                    </ul>
                </div>
                
                <!-- Suporte -->
                <div>
                    <h3 class="text-sm font-semibold text-secondary-900 uppercase tracking-wider mb-4">Suporte</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="/help" class="footer-link">
                                <i class="fas fa-question-circle mr-2 text-xs"></i>
                                Central de Ajuda
                            </a>
                        </li>
                        <li>
                            <a href="/docs" class="footer-link">
                                <i class="fas fa-book mr-2 text-xs"></i>
                                Documentação
                            </a>
                        </li>
                        <li>
                            <a href="/contact" class="footer-link">
                                <i class="fas fa-envelope mr-2 text-xs"></i>
                                Contato
                            </a>
                        </li>
                        <li>
                            <a href="/status" class="footer-link">
                                <i class="fas fa-heartbeat mr-2 text-xs"></i>
                                Status do Sistema
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Separador -->
        <div class="border-t border-secondary-200"></div>
        
        <!-- Footer Inferior -->
        <div class="py-4">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-2 md:space-y-0">
                
                <!-- Copyright -->
                <div class="flex items-center space-x-4 text-sm text-secondary-500">
                    <span>&copy; <?= date('Y') ?> Sistema PHP. Todos os direitos reservados.</span>
                    <span class="hidden md:inline">•</span>
                    <span class="flex items-center">
                        <i class="fas fa-code mr-1 text-xs"></i>
                        Versão 1.0.0
                    </span>
                </div>
                
                <!-- Links Legais -->
                <div class="flex items-center space-x-4 text-sm">
                    <a href="/privacy" class="footer-link">Privacidade</a>
                    <span class="text-secondary-300">•</span>
                    <a href="/terms" class="footer-link">Termos de Uso</a>
                    <span class="text-secondary-300">•</span>
                    <a href="/cookies" class="footer-link">Cookies</a>
                </div>
            </div>
        </div>
        
        <!-- Informações do Sistema (apenas para admins) -->
        <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
            <div class="border-t border-secondary-200 py-2">
                <details class="text-xs text-secondary-400">
                    <summary class="cursor-pointer hover:text-secondary-600 transition-colors">Informações do Sistema</summary>
                    <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                        <div>
                            <span class="font-medium">PHP:</span> <?= PHP_VERSION ?>
                        </div>
                        <div>
                            <span class="font-medium">Servidor:</span> <?= $_SERVER['SERVER_SOFTWARE'] ?? 'N/A' ?>
                        </div>
                        <div>
                            <span class="font-medium">Memória:</span> <?= ini_get('memory_limit') ?>
                        </div>
                        <div>
                            <span class="font-medium">Tempo:</span> <?= date('H:i:s') ?>
                        </div>
                    </div>
                </details>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Botão Voltar ao Topo -->
    <button id="back-to-top" 
            class="fixed bottom-6 right-6 bg-primary-600 hover:bg-primary-700 text-white p-3 rounded-full shadow-lg transition-all duration-300 transform translate-y-16 opacity-0 z-40"
            onclick="scrollToTop()"
            aria-label="Voltar ao topo">
        <i class="fas fa-chevron-up"></i>
    </button>
</footer>

<style>
    .footer-link {
        @apply text-secondary-500 hover:text-primary-600 transition-colors duration-200 text-sm flex items-center;
    }
    
    .social-link {
        @apply w-10 h-10 bg-secondary-100 hover:bg-primary-600 text-secondary-600 hover:text-white rounded-full flex items-center justify-center transition-all duration-200 transform hover:scale-110;
    }
    
    /* Animação para o botão voltar ao topo */
    #back-to-top.show {
        @apply translate-y-0 opacity-100;
    }
</style>

<script>
    // Botão voltar ao topo
    const backToTopButton = document.getElementById('back-to-top');
    
    // Mostrar/ocultar botão baseado no scroll
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopButton.classList.add('show');
        } else {
            backToTopButton.classList.remove('show');
        }
    });
    
    // Função para voltar ao topo
    function scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
    
    // Status do sistema em tempo real (apenas para admins)
    <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
        function updateSystemStatus() {
            // Atualizar informações do sistema a cada 30 segundos
            const timeElement = document.querySelector('[data-system-time]');
            if (timeElement) {
                timeElement.textContent = new Date().toLocaleTimeString('pt-BR');
            }
        }
        
        // Atualizar a cada 30 segundos
        setInterval(updateSystemStatus, 30000);
    <?php endif; ?>
    
    // Lazy loading para links externos
    document.addEventListener('DOMContentLoaded', function() {
        const externalLinks = document.querySelectorAll('a[href^="http"]');
        externalLinks.forEach(link => {
            link.setAttribute('target', '_blank');
            link.setAttribute('rel', 'noopener noreferrer');
        });
    });
    
    // Analytics para links do footer (opcional)
    document.querySelectorAll('.footer-link').forEach(link => {
        link.addEventListener('click', function(e) {
            // Aqui você pode adicionar tracking de analytics
            // gtag('event', 'click', { 'event_category': 'footer', 'event_label': this.href });
        });
    });
</script>

<!-- Schema.org para SEO -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "Sistema PHP",
    "url": "<?= $_SERVER['REQUEST_SCHEME'] ?>://<?= $_SERVER['HTTP_HOST'] ?>",
    "logo": "<?= $_SERVER['REQUEST_SCHEME'] ?>://<?= $_SERVER['HTTP_HOST'] ?>/assets/logo.png",
    "description": "Sistema desenvolvido com arquitetura limpa, seguindo as melhores práticas de desenvolvimento PHP moderno.",
    "foundingDate": "<?= date('Y') ?>",
    "contactPoint": {
        "@type": "ContactPoint",
        "contactType": "customer service",
        "url": "<?= $_SERVER['REQUEST_SCHEME'] ?>://<?= $_SERVER['HTTP_HOST'] ?>/contact"
    },
    "sameAs": [
        "https://github.com/",
        "https://linkedin.com/",
        "https://twitter.com/"
    ]
}
</script>