<?php
/**
 * Toast Component - Sistema de Notificações Neon Futurista
 * Componente para exibir mensagens de feedback ao usuário
 */

$config = [
    'position' => $position ?? 'top-right', // top-right, top-left, bottom-right, bottom-left, top-center, bottom-center
    'auto_dismiss' => $auto_dismiss ?? true,
    'dismiss_time' => $dismiss_time ?? 5000, // milliseconds
    'max_toasts' => $max_toasts ?? 5,
    'show_progress' => $show_progress ?? true
];

$position_classes = [
    'top-right' => 'top-4 right-4',
    'top-left' => 'top-4 left-4',
    'bottom-right' => 'bottom-4 right-4',
    'bottom-left' => 'bottom-4 left-4',
    'top-center' => 'top-4 left-1/2 transform -translate-x-1/2',
    'bottom-center' => 'bottom-4 left-1/2 transform -translate-x-1/2'
];
?>

<!-- Toast Container -->
<div 
    id="toast-container" 
    class="fixed <?= $position_classes[$config['position']] ?> z-50 space-y-3 pointer-events-none"
    data-position="<?= $config['position'] ?>"
    data-auto-dismiss="<?= $config['auto_dismiss'] ? 'true' : 'false' ?>"
    data-dismiss-time="<?= $config['dismiss_time'] ?>"
    data-max-toasts="<?= $config['max_toasts'] ?>"
    data-show-progress="<?= $config['show_progress'] ? 'true' : 'false' ?>"
>
    <!-- Toasts will be dynamically inserted here -->
</div>

<!-- Toast Template (Hidden) -->
<template id="toast-template">
    <div class="toast-item bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl pointer-events-auto transform transition-all duration-300 ease-out opacity-0 translate-y-2 scale-95 max-w-sm w-full overflow-hidden">
        <!-- Toast Header -->
        <div class="flex items-start p-4">
            <!-- Icon -->
            <div class="toast-icon flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center mr-3">
                <i class="toast-icon-element text-white"></i>
            </div>
            
            <!-- Content -->
            <div class="flex-1 min-w-0">
                <div class="toast-title font-semibold text-gray-900 dark:text-white text-sm mb-1"></div>
                <div class="toast-message text-gray-600 dark:text-gray-300 text-sm leading-relaxed"></div>
            </div>
            
            <!-- Close Button -->
            <button 
                type="button" 
                class="toast-close flex-shrink-0 ml-3 w-6 h-6 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 flex items-center justify-center text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                onclick="dismissToast(this)"
            >
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
        
        <!-- Progress Bar -->
        <div class="toast-progress-container h-1 bg-gray-100 dark:bg-gray-700">
            <div class="toast-progress h-full transition-all duration-100 ease-linear"></div>
        </div>
    </div>
</template>

<style>
/* Toast Animations */
.toast-item.show {
    opacity: 1;
    transform: translateY(0) scale(1);
}

.toast-item.hide {
    opacity: 0;
    transform: translateY(-100%) scale(0.95);
}

/* Toast Types */
.toast-success .toast-icon {
    background: linear-gradient(135deg, #10b981, #059669);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.toast-error .toast-icon {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
}

.toast-warning .toast-icon {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

.toast-info .toast-icon {
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.toast-loading .toast-icon {
    background: linear-gradient(135deg, #8b5cf6, #7c3aed);
    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
}

/* Progress Bar Colors */
.toast-success .toast-progress {
    background: linear-gradient(90deg, #10b981, #059669);
}

.toast-error .toast-progress {
    background: linear-gradient(90deg, #ef4444, #dc2626);
}

.toast-warning .toast-progress {
    background: linear-gradient(90deg, #f59e0b, #d97706);
}

.toast-info .toast-progress {
    background: linear-gradient(90deg, #3b82f6, #2563eb);
}

.toast-loading .toast-progress {
    background: linear-gradient(90deg, #8b5cf6, #7c3aed);
}

/* Loading Animation */
.toast-loading .toast-icon-element {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Dark Mode Adjustments */
@media (prefers-color-scheme: dark) {
    .toast-item {
        background: #1f2937;
        border-color: #374151;
    }
}
</style>

<script>
// Toast System
class ToastSystem {
    constructor() {
        this.container = document.getElementById('toast-container');
        this.template = document.getElementById('toast-template');
        this.toasts = new Map();
        this.config = {
            position: this.container.dataset.position,
            autoDismiss: this.container.dataset.autoDismiss === 'true',
            dismissTime: parseInt(this.container.dataset.dismissTime),
            maxToasts: parseInt(this.container.dataset.maxToasts),
            showProgress: this.container.dataset.showProgress === 'true'
        };
        
        this.init();
    }
    
    init() {
        // Make toast functions globally available
        window.showToast = this.show.bind(this);
        window.dismissToast = this.dismiss.bind(this);
        window.clearAllToasts = this.clearAll.bind(this);
        
        // Convenience methods
        window.showSuccess = (message, title = 'Sucesso!') => this.show(message, 'success', title);
        window.showError = (message, title = 'Erro!') => this.show(message, 'error', title);
        window.showWarning = (message, title = 'Atenção!') => this.show(message, 'warning', title);
        window.showInfo = (message, title = 'Informação') => this.show(message, 'info', title);
        window.showLoading = (message, title = 'Carregando...') => this.show(message, 'loading', title);
    }
    
    show(message, type = 'info', title = '', options = {}) {
        const id = 'toast-' + Date.now() + '-' + Math.random().toString(36).substr(2, 9);
        
        // Remove oldest toast if at max capacity
        if (this.toasts.size >= this.config.maxToasts) {
            const oldestId = this.toasts.keys().next().value;
            this.dismiss(oldestId);
        }
        
        // Create toast element
        const toastElement = this.createToastElement(id, message, type, title, options);
        
        // Add to container
        this.container.appendChild(toastElement);
        
        // Store reference
        this.toasts.set(id, {
            element: toastElement,
            type: type,
            timer: null,
            progressInterval: null
        });
        
        // Animate in
        requestAnimationFrame(() => {
            toastElement.classList.add('show');
        });
        
        // Auto dismiss
        if (this.config.autoDismiss && type !== 'loading') {
            this.startAutoDismiss(id);
        }
        
        return id;
    }
    
    createToastElement(id, message, type, title, options) {
        const template = this.template.content.cloneNode(true);
        const toastElement = template.querySelector('.toast-item');
        
        // Set ID and type
        toastElement.id = id;
        toastElement.classList.add(`toast-${type}`);
        
        // Set icon
        const iconElement = toastElement.querySelector('.toast-icon-element');
        const icons = {
            success: 'fas fa-check',
            error: 'fas fa-times',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info-circle',
            loading: 'fas fa-spinner'
        };
        iconElement.className = `toast-icon-element text-white ${icons[type] || icons.info}`;
        
        // Set title and message
        const titleElement = toastElement.querySelector('.toast-title');
        const messageElement = toastElement.querySelector('.toast-message');
        
        if (title) {
            titleElement.textContent = title;
        } else {
            titleElement.style.display = 'none';
        }
        
        messageElement.textContent = message;
        
        // Hide progress bar if disabled
        if (!this.config.showProgress) {
            toastElement.querySelector('.toast-progress-container').style.display = 'none';
        }
        
        return toastElement;
    }
    
    startAutoDismiss(id) {
        const toast = this.toasts.get(id);
        if (!toast) return;
        
        const progressBar = toast.element.querySelector('.toast-progress');
        let progress = 100;
        const interval = 50; // Update every 50ms
        const decrement = (interval / this.config.dismissTime) * 100;
        
        // Update progress bar
        if (this.config.showProgress && progressBar) {
            progressBar.style.width = '100%';
            
            toast.progressInterval = setInterval(() => {
                progress -= decrement;
                progressBar.style.width = Math.max(0, progress) + '%';
            }, interval);
        }
        
        // Set dismiss timer
        toast.timer = setTimeout(() => {
            this.dismiss(id);
        }, this.config.dismissTime);
    }
    
    dismiss(idOrElement) {
        let id, element;
        
        if (typeof idOrElement === 'string') {
            id = idOrElement;
            const toast = this.toasts.get(id);
            if (!toast) return;
            element = toast.element;
        } else {
            element = idOrElement.closest('.toast-item');
            id = element.id;
        }
        
        const toast = this.toasts.get(id);
        if (!toast) return;
        
        // Clear timers
        if (toast.timer) clearTimeout(toast.timer);
        if (toast.progressInterval) clearInterval(toast.progressInterval);
        
        // Animate out
        element.classList.remove('show');
        element.classList.add('hide');
        
        // Remove after animation
        setTimeout(() => {
            if (element.parentNode) {
                element.parentNode.removeChild(element);
            }
            this.toasts.delete(id);
        }, 300);
    }
    
    clearAll() {
        const toastIds = Array.from(this.toasts.keys());
        toastIds.forEach(id => this.dismiss(id));
    }
    
    updateToast(id, message, type, title) {
        const toast = this.toasts.get(id);
        if (!toast) return;
        
        const element = toast.element;
        
        // Update type class
        element.className = element.className.replace(/toast-\w+/g, '');
        element.classList.add(`toast-${type}`);
        
        // Update icon
        const iconElement = element.querySelector('.toast-icon-element');
        const icons = {
            success: 'fas fa-check',
            error: 'fas fa-times',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info-circle',
            loading: 'fas fa-spinner'
        };
        iconElement.className = `toast-icon-element text-white ${icons[type] || icons.info}`;
        
        // Update content
        if (title) {
            element.querySelector('.toast-title').textContent = title;
            element.querySelector('.toast-title').style.display = 'block';
        }
        element.querySelector('.toast-message').textContent = message;
        
        // Update toast reference
        toast.type = type;
        
        // Restart auto dismiss if needed
        if (toast.timer) clearTimeout(toast.timer);
        if (toast.progressInterval) clearInterval(toast.progressInterval);
        
        if (this.config.autoDismiss && type !== 'loading') {
            this.startAutoDismiss(id);
        }
    }
}

// Initialize toast system when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('toast-container')) {
        window.toastSystem = new ToastSystem();
    }
});

// Global helper function for alerts (backward compatibility)
window.showAlert = function(message, type = 'info', title = '') {
    if (window.showToast) {
        return window.showToast(message, type, title);
    }
};
</script>