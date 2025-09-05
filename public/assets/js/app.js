/**
 * App JavaScript - Neon Futurista Theme
 * Funcionalidades principais da aplicação
 */

// Inicialização quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Neon Futurista Theme loaded!');
    
    // Inicializar componentes
    initializeComponents();
    
    // Adicionar efeitos visuais
    addVisualEffects();
});

/**
 * Inicializa todos os componentes da página
 */
function initializeComponents() {
    // Inicializar modais
    initializeModals();
    
    // Inicializar alerts
    initializeAlerts();
    
    // Inicializar badges removíveis
    initializeBadges();
}

/**
 * Inicializa funcionalidades dos modais
 */
function initializeModals() {
    // Fechar modal ao clicar no backdrop
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-backdrop')) {
            const modal = e.target.closest('.modal');
            if (modal) {
                closeModal(modal.id);
            }
        }
    });
    
    // Fechar modal com ESC
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const openModal = document.querySelector('.modal:not(.hidden)');
            if (openModal) {
                closeModal(openModal.id);
            }
        }
    });
}

/**
 * Inicializa funcionalidades dos alerts
 */
function initializeAlerts() {
    // Auto-dismiss alerts após 5 segundos
    const alerts = document.querySelectorAll('.alert[data-auto-dismiss="true"]');
    alerts.forEach(alert => {
        setTimeout(() => {
            dismissAlert(alert.id);
        }, 5000);
    });
}

/**
 * Inicializa badges removíveis
 */
function initializeBadges() {
    const removableBadges = document.querySelectorAll('.badge .remove-btn');
    removableBadges.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const badge = this.closest('.badge');
            if (badge) {
                badge.style.transform = 'scale(0)';
                badge.style.opacity = '0';
                setTimeout(() => {
                    badge.remove();
                }, 200);
            }
        });
    });
}

/**
 * Adiciona efeitos visuais especiais
 */
function addVisualEffects() {
    // Efeito de partículas no background
    createParticleEffect();
    
    // Efeito de hover nos cards
    addCardHoverEffects();
    
    // Efeito de glow nos botões
    addButtonGlowEffects();
}

/**
 * Cria efeito de partículas no background
 */
function createParticleEffect() {
    const canvas = document.createElement('canvas');
    canvas.id = 'particle-canvas';
    canvas.style.position = 'fixed';
    canvas.style.top = '0';
    canvas.style.left = '0';
    canvas.style.width = '100%';
    canvas.style.height = '100%';
    canvas.style.pointerEvents = 'none';
    canvas.style.zIndex = '-1';
    canvas.style.opacity = '0.3';
    
    document.body.appendChild(canvas);
    
    const ctx = canvas.getContext('2d');
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
    
    const particles = [];
    const particleCount = 50;
    
    // Criar partículas
    for (let i = 0; i < particleCount; i++) {
        particles.push({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height,
            vx: (Math.random() - 0.5) * 0.5,
            vy: (Math.random() - 0.5) * 0.5,
            size: Math.random() * 2 + 1,
            color: `hsl(${Math.random() * 60 + 180}, 100%, 70%)`
        });
    }
    
    // Animar partículas
    function animateParticles() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        
        particles.forEach(particle => {
            particle.x += particle.vx;
            particle.y += particle.vy;
            
            // Reposicionar se sair da tela
            if (particle.x < 0) particle.x = canvas.width;
            if (particle.x > canvas.width) particle.x = 0;
            if (particle.y < 0) particle.y = canvas.height;
            if (particle.y > canvas.height) particle.y = 0;
            
            // Desenhar partícula
            ctx.beginPath();
            ctx.arc(particle.x, particle.y, particle.size, 0, Math.PI * 2);
            ctx.fillStyle = particle.color;
            ctx.shadowBlur = 10;
            ctx.shadowColor = particle.color;
            ctx.fill();
        });
        
        requestAnimationFrame(animateParticles);
    }
    
    animateParticles();
    
    // Redimensionar canvas
    window.addEventListener('resize', () => {
        canvas.width = window.innerWidth;
        canvas.height = window.innerHeight;
    });
}

/**
 * Adiciona efeitos de hover nos cards
 */
function addCardHoverEffects() {
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
}

/**
 * Adiciona efeitos de glow nos botões
 */
function addButtonGlowEffects() {
    const buttons = document.querySelectorAll('.btn');
    buttons.forEach(button => {
        button.addEventListener('mouseenter', function() {
            this.style.boxShadow = '0 0 20px rgba(59, 130, 246, 0.6)';
        });
        
        button.addEventListener('mouseleave', function() {
            this.style.boxShadow = '';
        });
    });
}

// Funções globais para componentes
window.openModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
};

window.closeModal = function(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
};

window.dismissAlert = function(alertId) {
    const alert = document.getElementById(alertId);
    if (alert) {
        alert.style.opacity = '0';
        alert.style.transform = 'translateX(100%)';
        setTimeout(() => {
            alert.remove();
        }, 300);
    }
};

window.showAlert = function(message, type = 'info', autoClose = true) {
    const alertId = 'alert-' + Date.now();
    const alertHtml = `
        <div id="${alertId}" class="alert alert-${type} mb-4" data-auto-dismiss="${autoClose}">
            <div class="alert-content">
                <span class="alert-message">${message}</span>
                <button onclick="dismissAlert('${alertId}')" class="alert-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    `;
    
    const container = document.querySelector('.alerts-container') || document.body;
    container.insertAdjacentHTML('afterbegin', alertHtml);
    
    if (autoClose) {
        setTimeout(() => {
            dismissAlert(alertId);
        }, 5000);
    }
};

console.log('✨ Neon Futurista JavaScript loaded successfully!');