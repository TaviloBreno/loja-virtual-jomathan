<nav class="bg-white shadow-lg border-b border-secondary-200" x-data="{ mobileMenuOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            
            <!-- Logo e Menu Principal -->
            <div class="flex">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="/" class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-code text-white text-sm"></i>
                        </div>
                        <span class="text-xl font-bold text-secondary-900">Sistema PHP</span>
                    </a>
                </div>
                
                <!-- Menu Desktop -->
                <div class="hidden md:ml-6 md:flex md:space-x-8">
                    <a href="/" class="nav-link <?= $currentRoute === '/' ? 'active' : '' ?>">
                        <i class="fas fa-home mr-2"></i>
                        Início
                    </a>
                    
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="nav-link flex items-center">
                            <i class="fas fa-users mr-2"></i>
                            Usuários
                            <i class="fas fa-chevron-down ml-1 text-xs" :class="{ 'rotate-180': open }"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             @click.away="open = false"
                             class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                             x-cloak>
                            <a href="/users" class="dropdown-link">
                                <i class="fas fa-list mr-2"></i>
                                Listar Usuários
                            </a>
                            <a href="/users/create" class="dropdown-link">
                                <i class="fas fa-plus mr-2"></i>
                                Novo Usuário
                            </a>
                            <div class="border-t border-secondary-100 my-1"></div>
                            <a href="/users/import" class="dropdown-link">
                                <i class="fas fa-upload mr-2"></i>
                                Importar
                            </a>
                        </div>
                    </div>
                    
                    <a href="/reports" class="nav-link <?= $currentRoute === '/reports' ? 'active' : '' ?>">
                        <i class="fas fa-chart-bar mr-2"></i>
                        Relatórios
                    </a>
                    
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="nav-link flex items-center">
                            <i class="fas fa-cog mr-2"></i>
                            Sistema
                            <i class="fas fa-chevron-down ml-1 text-xs" :class="{ 'rotate-180': open }"></i>
                        </button>
                        
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             @click.away="open = false"
                             class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                             x-cloak>
                            <a href="/settings" class="dropdown-link">
                                <i class="fas fa-sliders-h mr-2"></i>
                                Configurações
                            </a>
                            <a href="/logs" class="dropdown-link">
                                <i class="fas fa-file-alt mr-2"></i>
                                Logs do Sistema
                            </a>
                            <div class="border-t border-secondary-100 my-1"></div>
                            <a href="/backup" class="dropdown-link">
                                <i class="fas fa-database mr-2"></i>
                                Backup
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Menu Direito -->
            <div class="hidden md:flex md:items-center md:space-x-4">
                
                <!-- Dark Mode Toggle -->
                <button @click="darkMode = !darkMode" 
                        class="p-2 text-secondary-500 hover:text-secondary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 rounded-lg transition-colors">
                    <i class="fas fa-moon" x-show="!darkMode"></i>
                    <i class="fas fa-sun" x-show="darkMode" x-cloak></i>
                </button>
                
                <!-- Notifications -->
                <div class="relative" x-data="{ open: false, count: 3 }">
                    <button @click="open = !open" class="relative p-2 text-secondary-500 hover:text-secondary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 rounded-lg transition-colors">
                        <i class="fas fa-bell"></i>
                        <span x-show="count > 0" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center" x-text="count"></span>
                    </button>
                    
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         @click.away="open = false"
                         class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-1 z-50"
                         x-cloak>
                        <div class="px-4 py-2 border-b border-secondary-100">
                            <h3 class="text-sm font-medium text-secondary-900">Notificações</h3>
                        </div>
                        
                        <div class="max-h-64 overflow-y-auto">
                            <div class="px-4 py-3 hover:bg-secondary-50 cursor-pointer">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm text-secondary-900">Novo usuário cadastrado</p>
                                        <p class="text-xs text-secondary-500 mt-1">Há 5 minutos</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="px-4 py-3 hover:bg-secondary-50 cursor-pointer">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm text-secondary-900">Backup realizado com sucesso</p>
                                        <p class="text-xs text-secondary-500 mt-1">Há 1 hora</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="px-4 py-3 hover:bg-secondary-50 cursor-pointer">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div class="w-2 h-2 bg-yellow-500 rounded-full mt-2"></div>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <p class="text-sm text-secondary-900">Atualização disponível</p>
                                        <p class="text-xs text-secondary-500 mt-1">Há 2 horas</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-t border-secondary-100 px-4 py-2">
                            <a href="/notifications" class="text-sm text-primary-600 hover:text-primary-700">Ver todas</a>
                        </div>
                    </div>
                </div>
                
                <!-- User Menu -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-secondary-100 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-colors">
                        <img class="h-8 w-8 rounded-full object-cover" src="https://ui-avatars.com/api/?name=Admin&background=3b82f6&color=fff" alt="Avatar">
                        <span class="text-sm font-medium text-secondary-700">Admin</span>
                        <i class="fas fa-chevron-down text-xs text-secondary-500" :class="{ 'rotate-180': open }"></i>
                    </button>
                    
                    <div x-show="open" 
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="opacity-100 scale-100"
                         x-transition:leave-end="opacity-0 scale-95"
                         @click.away="open = false"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50"
                         x-cloak>
                        <a href="/profile" class="dropdown-link">
                            <i class="fas fa-user mr-2"></i>
                            Meu Perfil
                        </a>
                        <a href="/profile/settings" class="dropdown-link">
                            <i class="fas fa-cog mr-2"></i>
                            Configurações
                        </a>
                        <div class="border-t border-secondary-100 my-1"></div>
                        <a href="/logout" class="dropdown-link text-red-600 hover:text-red-700 hover:bg-red-50">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Sair
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Mobile Menu Button -->
            <div class="md:hidden flex items-center">
                <button @click="mobileMenuOpen = !mobileMenuOpen" 
                        class="p-2 text-secondary-500 hover:text-secondary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 rounded-lg">
                    <i class="fas fa-bars" x-show="!mobileMenuOpen"></i>
                    <i class="fas fa-times" x-show="mobileMenuOpen" x-cloak></i>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Mobile Menu -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="md:hidden bg-white border-t border-secondary-200"
         x-cloak>
        <div class="px-2 pt-2 pb-3 space-y-1">
            <a href="/" class="mobile-nav-link <?= $currentRoute === '/' ? 'active' : '' ?>">
                <i class="fas fa-home mr-3"></i>
                Início
            </a>
            
            <div x-data="{ open: false }">
                <button @click="open = !open" class="mobile-nav-link w-full flex items-center justify-between">
                    <span>
                        <i class="fas fa-users mr-3"></i>
                        Usuários
                    </span>
                    <i class="fas fa-chevron-down text-xs" :class="{ 'rotate-180': open }"></i>
                </button>
                
                <div x-show="open" class="pl-6 space-y-1" x-cloak>
                    <a href="/users" class="mobile-nav-link">
                        <i class="fas fa-list mr-3"></i>
                        Listar Usuários
                    </a>
                    <a href="/users/create" class="mobile-nav-link">
                        <i class="fas fa-plus mr-3"></i>
                        Novo Usuário
                    </a>
                </div>
            </div>
            
            <a href="/reports" class="mobile-nav-link <?= $currentRoute === '/reports' ? 'active' : '' ?>">
                <i class="fas fa-chart-bar mr-3"></i>
                Relatórios
            </a>
            
            <div class="border-t border-secondary-200 pt-3 mt-3">
                <a href="/profile" class="mobile-nav-link">
                    <i class="fas fa-user mr-3"></i>
                    Meu Perfil
                </a>
                <a href="/settings" class="mobile-nav-link">
                    <i class="fas fa-cog mr-3"></i>
                    Configurações
                </a>
                <a href="/logout" class="mobile-nav-link text-red-600">
                    <i class="fas fa-sign-out-alt mr-3"></i>
                    Sair
                </a>
            </div>
        </div>
    </div>
</nav>

<style>
    .nav-link {
        @apply inline-flex items-center px-1 pt-1 text-sm font-medium text-secondary-500 hover:text-secondary-700 hover:border-secondary-300 focus:outline-none focus:text-secondary-700 focus:border-secondary-300 transition-colors duration-200 border-b-2 border-transparent;
    }
    
    .nav-link.active {
        @apply text-primary-600 border-primary-600;
    }
    
    .dropdown-link {
        @apply block px-4 py-2 text-sm text-secondary-700 hover:bg-secondary-100 hover:text-secondary-900 transition-colors duration-200;
    }
    
    .mobile-nav-link {
        @apply block px-3 py-2 text-base font-medium text-secondary-500 hover:text-secondary-700 hover:bg-secondary-100 rounded-md transition-colors duration-200;
    }
    
    .mobile-nav-link.active {
        @apply text-primary-600 bg-primary-50;
    }
</style>