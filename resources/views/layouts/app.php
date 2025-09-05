<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= $title ?? 'Sistema PHP' ?></title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Configuração do Tailwind -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        secondary: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    },
                    fontFamily: {
                        'sans': ['Inter', 'system-ui', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.3s ease-out',
                        'bounce-in': 'bounceIn 0.6s ease-out',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(10px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        bounceIn: {
                            '0%': { transform: 'scale(0.3)', opacity: '0' },
                            '50%': { transform: 'scale(1.05)' },
                            '70%': { transform: 'scale(0.9)' },
                            '100%': { transform: 'scale(1)', opacity: '1' },
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Custom Styles -->
    <style>
        [x-cloak] { display: none !important; }
        
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .card-shadow {
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .btn-primary {
            @apply bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2;
        }
        
        .btn-secondary {
            @apply bg-secondary-100 hover:bg-secondary-200 text-secondary-800 font-medium py-2 px-4 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-secondary-500 focus:ring-offset-2;
        }
        
        .input-field {
            @apply w-full px-3 py-2 border border-secondary-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all duration-200;
        }
    </style>
    
    <?php if (isset($additionalHead)): ?>
        <?= $additionalHead ?>
    <?php endif; ?>
</head>
<body class="bg-secondary-50 font-sans antialiased" x-data="{ sidebarOpen: false, darkMode: false }" :class="{ 'dark': darkMode }">
    
    <!-- Loading Spinner -->
    <div id="loading" class="fixed inset-0 bg-white z-50 flex items-center justify-center" x-show="false" x-cloak>
        <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary-600"></div>
    </div>
    
    <!-- Navigation -->
    <?php if (!isset($hideNavigation) || !$hideNavigation): ?>
        <?= $this->component('navigation') ?>
    <?php endif; ?>
    
    <!-- Sidebar Overlay -->
    <div 
        x-show="sidebarOpen" 
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
        @click="sidebarOpen = false"
        x-cloak
    ></div>
    
    <!-- Main Content -->
    <main class="<?= isset($containerClass) ? $containerClass : 'container mx-auto px-4 py-8' ?>">
        
        <!-- Flash Messages -->
        <?= $this->component('flash-messages') ?>
        
        <!-- Breadcrumbs -->
        <?php if (isset($breadcrumbs) && !empty($breadcrumbs)): ?>
            <?= $this->component('breadcrumbs', ['items' => $breadcrumbs]) ?>
        <?php endif; ?>
        
        <!-- Page Header -->
        <?php if (isset($pageHeader)): ?>
            <div class="mb-8">
                <?= $pageHeader ?>
            </div>
        <?php endif; ?>
        
        <!-- Content -->
        <div class="animate-fade-in">
            <?= $this->yield('content') ?>
        </div>
        
    </main>
    
    <!-- Footer -->
    <?php if (!isset($hideFooter) || !$hideFooter): ?>
        <?= $this->component('footer') ?>
    <?php endif; ?>
    
    <!-- Modals Container -->
    <div id="modals-container"></div>
    
    <!-- Toast Notifications -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>
    
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
            
            // Show toast notification
            showToast(message, type = 'info', duration = 5000) {
                const toast = document.createElement('div');
                const colors = {
                    success: 'bg-green-500',
                    error: 'bg-red-500',
                    warning: 'bg-yellow-500',
                    info: 'bg-blue-500'
                };
                
                toast.className = `${colors[type]} text-white px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full opacity-0`;
                toast.innerHTML = `
                    <div class="flex items-center justify-between">
                        <span>${message}</span>
                        <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                `;
                
                document.getElementById('toast-container').appendChild(toast);
                
                // Animate in
                setTimeout(() => {
                    toast.classList.remove('translate-x-full', 'opacity-0');
                }, 100);
                
                // Auto remove
                setTimeout(() => {
                    toast.classList.add('translate-x-full', 'opacity-0');
                    setTimeout(() => toast.remove(), 300);
                }, duration);
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
                        App.showToast(data.message || 'Operação realizada com sucesso!', 'success');
                        
                        if (data.redirect) {
                            setTimeout(() => {
                                window.location.href = data.redirect;
                            }, 1000);
                        }
                    } else {
                        App.showToast(data.message || 'Erro ao processar solicitação', 'error');
                    }
                })
                .catch(error => {
                    App.hideLoading();
                    App.showToast('Erro de conexão', 'error');
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