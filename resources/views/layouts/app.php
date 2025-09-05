<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title ?? 'NeonShop - E-commerce Futurista' ?></title>
    
    <!-- Tailwind CSS Compilado com Paleta Neon Futurista -->
    <link rel="stylesheet" href="/assets/css/app.css">
    
    <!-- Fallback CDN caso o arquivo não carregue -->
    <script>
        // Verificar se o CSS foi carregado
        setTimeout(() => {
            const testEl = document.createElement('div');
            testEl.className = 'bg-primary-500';
            document.body.appendChild(testEl);
            const styles = getComputedStyle(testEl);
            if (!styles.backgroundColor || styles.backgroundColor === 'rgba(0, 0, 0, 0)') {
                // Carregar CDN como fallback
                const link = document.createElement('script');
                link.src = 'https://cdn.tailwindcss.com';
                document.head.appendChild(link);
            }
            document.body.removeChild(testEl);
        }, 100);
    </script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Custom Styles Neon Futurista -->
    <style>
        [x-cloak] { display: none !important; }
        
        body {
            background: linear-gradient(135deg, #000000 0%, #1a1a1a 50%, #000000 100%);
            min-height: 100vh;
        }
        
        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .neon-gradient {
            background: linear-gradient(135deg, #7C3AED 0%, #39FF14 100%);
        }
        
        .electric-gradient {
            background: linear-gradient(135deg, #7C3AED 0%, #2E2E2E 100%);
        }
        
        .card-shadow {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        /* Particle Effect */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 1;
        }
        
        .particle {
            position: absolute;
            width: 2px;
            height: 2px;
            background: rgba(59, 130, 246, 0.6);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0; }
            50% { transform: translateY(-100px) rotate(180deg); opacity: 1; }
        }
    </style>
    
    <?php if (isset($additionalHead)): ?>
        <?= $additionalHead ?>
    <?php endif; ?>
</head>
<body class="font-sans antialiased" x-data="{ sidebarOpen: false, darkMode: false }" :class="{ 'dark': darkMode }">
    
    <!-- Particles Background -->
    <div class="particles" id="particles"></div>
    
    <!-- Loading Spinner -->
    <div id="loading" class="fixed inset-0 bg-gray-900 z-50 flex items-center justify-center" x-show="false" x-cloak>
        <div class="text-center">
            <div class="w-16 h-16 border-4 border-primary-500 border-t-transparent rounded-full animate-spin mx-auto mb-4"></div>
            <p class="text-white text-lg">Carregando...</p>
        </div>
    </div>
    
    <!-- Header -->
    <?php if ($show_header ?? true): ?>
        <?php include __DIR__ . '/../../app/Presentation/Components/header.php'; ?>
    <?php endif; ?>
    
    <!-- Main Content -->
    <main class="min-h-screen relative z-10 <?= isset($containerClass) ? $containerClass : '' ?>">
        
        <!-- Flash Messages -->
        <?php if (isset($flash_messages) && !empty($flash_messages)): ?>
            <div class="container mx-auto px-4 pt-4">
                <?php foreach ($flash_messages as $type => $message): ?>
                    <div class="alert alert-<?= $type ?> mb-4 p-4 rounded-lg">
                        <?= htmlspecialchars($message) ?>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <!-- Breadcrumbs -->
        <?php if (isset($breadcrumbs) && !empty($breadcrumbs)): ?>
            <div class="container mx-auto px-4 py-2">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <?php foreach ($breadcrumbs as $index => $crumb): ?>
                            <li class="inline-flex items-center">
                                <?php if ($index > 0): ?>
                                    <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                <?php endif; ?>
                                <?php if (isset($crumb['url'])): ?>
                                    <a href="<?= htmlspecialchars($crumb['url']) ?>" class="text-gray-400 hover:text-primary-400 transition-colors">
                                        <?= htmlspecialchars($crumb['title']) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="text-gray-300"><?= htmlspecialchars($crumb['title']) ?></span>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ol>
                </nav>
            </div>
        <?php endif; ?>
        
        <!-- Page Header -->
        <?php if (isset($pageHeader)): ?>
            <div class="container mx-auto px-4 mb-8">
                <?= $pageHeader ?>
            </div>
        <?php endif; ?>
        
        <!-- Content -->
        <div class="animate-fade-in">
            <?= $content ?? '' ?>
        </div>
        
    </main>
    
    <!-- Footer -->
    <?php if ($show_footer ?? true): ?>
        <?php include __DIR__ . '/../../app/Presentation/Components/footer.php'; ?>
    <?php endif; ?>
    
    <!-- Toast Notifications -->
    <?php include __DIR__ . '/../../app/Presentation/Components/toast.php'; ?>
    
    <!-- JavaScript -->
    <script src="/assets/js/app.js"></script>
    
    <!-- Scripts -->
    <script>
        // Global JavaScript utilities
        window.App = {
            // Show loading spinner
            showLoading() {
                document.getElementById('loading').style.display = 'flex';
            },
            
            // Hide loading spinner
            hideLoading() {
                document.getElementById('loading').style.display = 'none';
            },
            
            // AJAX helper
            async request(url, options = {}) {
                const defaultOptions = {
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                };
                
                const response = await fetch(url, { ...defaultOptions, ...options });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                return response.json();
            },
            
            // Confirm dialog
            confirm(message, callback) {
                if (window.confirm(message)) {
                    callback();
                }
            },
            
            // Format currency
            formatCurrency(value) {
                return new Intl.NumberFormat('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                }).format(value);
            },
            
            // Format date
            formatDate(date) {
                return new Intl.DateTimeFormat('pt-BR').format(new Date(date));
            }
        };
        
        // Auto-hide loading on page load
        document.addEventListener('DOMContentLoaded', function() {
            App.hideLoading();
        });
        
        // Create particles
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            if (!particlesContainer) return;
            
            for (let i = 0; i < 50; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 6 + 's';
                particle.style.animationDuration = (Math.random() * 3 + 3) + 's';
                particlesContainer.appendChild(particle);
            }
        }
        
        // Initialize particles
        createParticles();
        
        // Global error handler for better UX
        window.addEventListener('error', function(e) {
            console.error('Erro capturado:', e.error);
            if (window.showError) {
                window.showError('Ocorreu um erro inesperado. Tente novamente.');
            }
        });
        
        // Global unhandled promise rejection handler
        window.addEventListener('unhandledrejection', function(e) {
            console.error('Promise rejeitada:', e.reason);
            if (window.showError) {
                window.showError('Erro de conexão. Verifique sua internet.');
            }
        });
        
        // Handle AJAX form submissions
        document.addEventListener('submit', function(e) {
            if (e.target.classList.contains('ajax-form')) {
                e.preventDefault();
                
                const form = e.target;
                const formData = new FormData(form);
                const url = form.action || window.location.href;
                const method = form.method || 'POST';
                
                App.showLoading();
                
                fetch(url, {
                    method: method,
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    App.hideLoading();
                    
                    if (data.success) {
                        if (window.showSuccess) {
                            window.showSuccess(data.message || 'Operação realizada com sucesso!');
                        }
                        
                        if (data.redirect) {
                            setTimeout(() => {
                                window.location.href = data.redirect;
                            }, 1000);
                        }
                    } else {
                        if (window.showError) {
                            window.showError(data.message || 'Erro ao processar solicitação');
                        }
                    }
                })
                .catch(error => {
                    App.hideLoading();
                    if (window.showError) {
                        window.showError('Erro de conexão');
                    }
                    console.error('Error:', error);
                });
            }
        });
    </script>
    
    <?php if (isset($additionalScripts)): ?>
        <?= $additionalScripts ?>
    <?php endif; ?>
    
</body>
</html>