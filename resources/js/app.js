import Alpine from 'alpinejs';

// Inicializar Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Componentes Alpine.js para o sistema
document.addEventListener('alpine:init', () => {
    // Componente para navegação
    Alpine.data('navigation', () => ({
        mobileMenuOpen: false,
        
        toggleMobileMenu() {
            this.mobileMenuOpen = !this.mobileMenuOpen;
        },
        
        closeMobileMenu() {
            this.mobileMenuOpen = false;
        }
    }));
    
    // Componente para modais
    Alpine.data('modal', () => ({
        open: false,
        
        show() {
            this.open = true;
            document.body.style.overflow = 'hidden';
        },
        
        hide() {
            this.open = false;
            document.body.style.overflow = 'auto';
        },
        
        toggle() {
            this.open ? this.hide() : this.show();
        }
    }));
    
    // Componente para notificações/alerts
    Alpine.data('notification', () => ({
        notifications: [],
        
        add(message, type = 'info', duration = 5000) {
            const id = Date.now();
            const notification = { id, message, type };
            
            this.notifications.push(notification);
            
            if (duration > 0) {
                setTimeout(() => {
                    this.remove(id);
                }, duration);
            }
            
            return id;
        },
        
        remove(id) {
            this.notifications = this.notifications.filter(n => n.id !== id);
        },
        
        success(message, duration = 5000) {
            return this.add(message, 'success', duration);
        },
        
        error(message, duration = 7000) {
            return this.add(message, 'error', duration);
        },
        
        warning(message, duration = 6000) {
            return this.add(message, 'warning', duration);
        },
        
        info(message, duration = 5000) {
            return this.add(message, 'info', duration);
        }
    }));
    
    // Componente para tema escuro/claro
    Alpine.data('theme', () => ({
        dark: localStorage.getItem('theme') === 'dark' || 
              (!localStorage.getItem('theme') && window.matchMedia('(prefers-color-scheme: dark)').matches),
        
        init() {
            this.updateTheme();
        },
        
        toggle() {
            this.dark = !this.dark;
            this.updateTheme();
        },
        
        updateTheme() {
            if (this.dark) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            }
        }
    }));
    
    // Componente para tabelas com funcionalidades
    Alpine.data('dataTable', (data = []) => ({
        items: data,
        filteredItems: [],
        search: '',
        sortBy: '',
        sortDirection: 'asc',
        currentPage: 1,
        itemsPerPage: 10,
        
        init() {
            this.filteredItems = [...this.items];
            this.$watch('search', () => this.filterItems());
        },
        
        filterItems() {
            if (!this.search) {
                this.filteredItems = [...this.items];
            } else {
                this.filteredItems = this.items.filter(item => 
                    Object.values(item).some(value => 
                        String(value).toLowerCase().includes(this.search.toLowerCase())
                    )
                );
            }
            this.currentPage = 1;
        },
        
        sortItems(field) {
            if (this.sortBy === field) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortBy = field;
                this.sortDirection = 'asc';
            }
            
            this.filteredItems.sort((a, b) => {
                const aVal = a[field];
                const bVal = b[field];
                
                if (this.sortDirection === 'asc') {
                    return aVal > bVal ? 1 : -1;
                } else {
                    return aVal < bVal ? 1 : -1;
                }
            });
        },
        
        get paginatedItems() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            const end = start + this.itemsPerPage;
            return this.filteredItems.slice(start, end);
        },
        
        get totalPages() {
            return Math.ceil(this.filteredItems.length / this.itemsPerPage);
        },
        
        nextPage() {
            if (this.currentPage < this.totalPages) {
                this.currentPage++;
            }
        },
        
        prevPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
            }
        },
        
        goToPage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
            }
        }
    }));
});

// Utilitários globais
window.utils = {
    // Formatação de datas
    formatDate(date, format = 'dd/mm/yyyy') {
        const d = new Date(date);
        const day = String(d.getDate()).padStart(2, '0');
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const year = d.getFullYear();
        
        return format
            .replace('dd', day)
            .replace('mm', month)
            .replace('yyyy', year);
    },
    
    // Formatação de moeda
    formatCurrency(value, currency = 'BRL') {
        return new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: currency
        }).format(value);
    },
    
    // Debounce para otimizar buscas
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },
    
    // Copiar texto para clipboard
    async copyToClipboard(text) {
        try {
            await navigator.clipboard.writeText(text);
            return true;
        } catch (err) {
            console.error('Erro ao copiar texto:', err);
            return false;
        }
    }
};

// Efeitos visuais e animações
document.addEventListener('DOMContentLoaded', () => {
    // Animação de fade-in para elementos
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-fade-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observar elementos com classe 'fade-in-on-scroll'
    document.querySelectorAll('.fade-in-on-scroll').forEach(el => {
        observer.observe(el);
    });
    
    // Efeito de ripple nos botões
    document.addEventListener('click', (e) => {
        if (e.target.matches('.btn, .btn *')) {
            const button = e.target.closest('.btn');
            if (button) {
                const ripple = document.createElement('span');
                const rect = button.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;
                
                ripple.style.cssText = `
                    position: absolute;
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                    background: rgba(255, 255, 255, 0.3);
                    border-radius: 50%;
                    transform: scale(0);
                    animation: ripple 0.6s linear;
                    pointer-events: none;
                `;
                
                button.style.position = 'relative';
                button.style.overflow = 'hidden';
                button.appendChild(ripple);
                
                setTimeout(() => {
                    ripple.remove();
                }, 600);
            }
        }
    });
});

// CSS para animação de ripple
const style = document.createElement('style');
style.textContent = `
    @keyframes ripple {
        to {
            transform: scale(4);
            opacity: 0;
        }
    }
    
    .animate-fade-in {
        animation: fadeIn 0.6s ease-out forwards;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
`;
document.head.appendChild(style);