<?php
$title = 'Demo - Componentes Neon Futurista';
$pageTitle = 'Componentes da Paleta Neon Futurista';
$pageDescription = 'Demonstração dos componentes UI com a nova paleta de cores';
?>

<?php include __DIR__ . '/../../layouts/app.php'; ?>

<div class="min-h-screen bg-gradient-to-br from-dark-950 via-dark-900 to-dark-950">
    <!-- Header -->
    <div class="bg-dark-800/50 backdrop-blur-xl border-b border-dark-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="text-center">
                <h1 class="text-4xl font-bold text-white mb-4">
                    <span class="bg-gradient-to-r from-primary-400 to-neon-400 bg-clip-text text-transparent">
                        <?= htmlspecialchars($pageTitle) ?>
                    </span>
                </h1>
                <p class="text-gray-300 text-lg max-w-2xl mx-auto">
                    <?= htmlspecialchars($pageDescription) ?>
                </p>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <!-- Paleta de Cores -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-white mb-8 flex items-center">
                <i class="fas fa-palette text-neon-500 mr-3"></i>
                Paleta de Cores
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Roxo Elétrico -->
                <div class="bg-dark-800/80 rounded-2xl p-6 border border-primary-500/30">
                    <div class="w-full h-20 bg-primary-500 rounded-xl mb-4 shadow-lg"></div>
                    <h3 class="text-white font-semibold mb-2">Roxo Elétrico</h3>
                    <p class="text-gray-400 text-sm mb-2">#7C3AED</p>
                    <p class="text-gray-500 text-xs">Cor principal moderna</p>
                </div>
                
                <!-- Verde Neon -->
                <div class="bg-dark-800/80 rounded-2xl p-6 border border-neon-500/30">
                    <div class="w-full h-20 bg-neon-500 rounded-xl mb-4 shadow-neon-glow"></div>
                    <h3 class="text-white font-semibold mb-2">Verde Neon</h3>
                    <p class="text-gray-400 text-sm mb-2">#39FF14</p>
                    <p class="text-gray-500 text-xs">Destaque ousado</p>
                </div>
                
                <!-- Preto Absoluto -->
                <div class="bg-dark-800/80 rounded-2xl p-6 border border-dark-600">
                    <div class="w-full h-20 bg-dark-950 rounded-xl mb-4 border border-dark-700"></div>
                    <h3 class="text-white font-semibold mb-2">Preto Absoluto</h3>
                    <p class="text-gray-400 text-sm mb-2">#000000</p>
                    <p class="text-gray-500 text-xs">Fundo sofisticado</p>
                </div>
                
                <!-- Cinza Médio -->
                <div class="bg-dark-800/80 rounded-2xl p-6 border border-dark-600">
                    <div class="w-full h-20 bg-dark-800 rounded-xl mb-4 border border-dark-600"></div>
                    <h3 class="text-white font-semibold mb-2">Cinza Médio</h3>
                    <p class="text-gray-400 text-sm mb-2">#2E2E2E</p>
                    <p class="text-gray-500 text-xs">Equilíbrio visual</p>
                </div>
            </div>
        </section>

        <!-- Botões -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-white mb-8 flex items-center">
                <i class="fas fa-mouse-pointer text-primary-500 mr-3"></i>
                Botões
            </h2>
            
            <div class="bg-dark-800/50 rounded-2xl p-8 border border-dark-600">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <!-- Botão Primary -->
                    <div class="space-y-4">
                        <h3 class="text-white font-medium">Primary</h3>
                        <?php 
                        $variant = 'primary';
                        $text = 'Botão Primary';
                        include __DIR__ . '/../../../app/Presentation/Components/button.php';
                        ?>
                    </div>
                    
                    <!-- Botão Neon -->
                    <div class="space-y-4">
                        <h3 class="text-white font-medium">Neon</h3>
                        <?php 
                        $variant = 'neon';
                        $text = 'Botão Neon';
                        include __DIR__ . '/../../../app/Presentation/Components/button.php';
                        ?>
                    </div>
                    
                    <!-- Botão Dark -->
                    <div class="space-y-4">
                        <h3 class="text-white font-medium">Dark</h3>
                        <?php 
                        $variant = 'dark';
                        $text = 'Botão Dark';
                        include __DIR__ . '/../../../app/Presentation/Components/button.php';
                        ?>
                    </div>
                    
                    <!-- Botão Outline -->
                    <div class="space-y-4">
                        <h3 class="text-white font-medium">Outline</h3>
                        <?php 
                        $variant = 'outline';
                        $text = 'Botão Outline';
                        include __DIR__ . '/../../../app/Presentation/Components/button.php';
                        ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Cards -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-white mb-8 flex items-center">
                <i class="fas fa-layer-group text-neon-500 mr-3"></i>
                Cards
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card Default -->
                <?php 
                $variant = 'default';
                $title = 'Card Padrão';
                $content = 'Este é um card com o estilo padrão da paleta Neon Futurista.';
                include __DIR__ . '/../../../app/Presentation/Components/card.php';
                ?>
                
                <!-- Card Neon -->
                <?php 
                $variant = 'neon';
                $title = 'Card Neon';
                $content = 'Card com borda neon e efeito glow especial.';
                include __DIR__ . '/../../../app/Presentation/Components/card.php';
                ?>
                
                <!-- Card Primary -->
                <?php 
                $variant = 'primary';
                $title = 'Card Primary';
                $content = 'Card com tema roxo elétrico da cor principal.';
                include __DIR__ . '/../../../app/Presentation/Components/card.php';
                ?>
            </div>
        </section>

        <!-- Inputs -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-white mb-8 flex items-center">
                <i class="fas fa-edit text-primary-500 mr-3"></i>
                Inputs
            </h2>
            
            <div class="bg-dark-800/50 rounded-2xl p-8 border border-dark-600">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Input Primary -->
                    <div>
                        <h3 class="text-white font-medium mb-4">Input Primary</h3>
                        <?php 
                        $variant = 'primary';
                        $label = 'Nome Completo';
                        $placeholder = 'Digite seu nome';
                        $name = 'name_primary';
                        include __DIR__ . '/../../../app/Presentation/Components/input.php';
                        ?>
                    </div>
                    
                    <!-- Input Neon -->
                    <div>
                        <h3 class="text-white font-medium mb-4">Input Neon</h3>
                        <?php 
                        $variant = 'neon';
                        $label = 'E-mail';
                        $placeholder = 'Digite seu e-mail';
                        $type = 'email';
                        $name = 'email_neon';
                        include __DIR__ . '/../../../app/Presentation/Components/input.php';
                        ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- Badges -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-white mb-8 flex items-center">
                <i class="fas fa-tags text-neon-500 mr-3"></i>
                Badges
            </h2>
            
            <div class="bg-dark-800/50 rounded-2xl p-8 border border-dark-600">
                <div class="flex flex-wrap gap-4">
                    <?php 
                    $variant = 'primary';
                    $text = 'Primary';
                    include __DIR__ . '/../../../app/Presentation/Components/badge.php';
                    ?>
                    
                    <?php 
                    $variant = 'neon';
                    $text = 'Neon';
                    include __DIR__ . '/../../../app/Presentation/Components/badge.php';
                    ?>
                    
                    <?php 
                    $variant = 'success';
                    $text = 'Sucesso';
                    include __DIR__ . '/../../../app/Presentation/Components/badge.php';
                    ?>
                    
                    <?php 
                    $variant = 'warning';
                    $text = 'Aviso';
                    include __DIR__ . '/../../../app/Presentation/Components/badge.php';
                    ?>
                    
                    <?php 
                    $variant = 'danger';
                    $text = 'Erro';
                    include __DIR__ . '/../../../app/Presentation/Components/badge.php';
                    ?>
                </div>
            </div>
        </section>

        <!-- Alerts -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-white mb-8 flex items-center">
                <i class="fas fa-exclamation-triangle text-primary-500 mr-3"></i>
                Alerts
            </h2>
            
            <div class="space-y-6">
                <?php 
                $variant = 'success';
                $title = 'Sucesso!';
                $message = 'Operação realizada com sucesso.';
                $dismissible = true;
                include __DIR__ . '/../../../app/Presentation/Components/alert.php';
                ?>
                
                <?php 
                $variant = 'neon';
                $title = 'Novidade!';
                $message = 'Nova funcionalidade com efeito neon disponível.';
                $dismissible = true;
                include __DIR__ . '/../../../app/Presentation/Components/alert.php';
                ?>
                
                <?php 
                $variant = 'primary';
                $title = 'Informação';
                $message = 'Esta é uma mensagem informativa com tema roxo elétrico.';
                $dismissible = true;
                include __DIR__ . '/../../../app/Presentation/Components/alert.php';
                ?>
            </div>
        </section>

        <!-- Demonstração de Modal -->
        <section class="mb-16">
            <h2 class="text-2xl font-bold text-white mb-8 flex items-center">
                <i class="fas fa-window-maximize text-neon-500 mr-3"></i>
                Modal
            </h2>
            
            <div class="bg-dark-800/50 rounded-2xl p-8 border border-dark-600">
                <div class="flex space-x-4">
                    <button 
                        onclick="openModal('demo-modal')"
                        class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-3 rounded-xl transition-colors duration-300"
                    >
                        Abrir Modal
                    </button>
                    
                    <button 
                        onclick="openModal('neon-modal')"
                        class="bg-neon-500 hover:bg-neon-400 text-dark-950 px-6 py-3 rounded-xl transition-colors duration-300 font-bold"
                    >
                        Modal Neon
                    </button>
                </div>
            </div>
        </section>
    </div>
</div>

<!-- Modais de Demonstração -->
<?php 
$id = 'demo-modal';
$title = 'Modal de Demonstração';
$variant = 'primary';
$content = '<p class="text-gray-300">Este é um modal com tema roxo elétrico da paleta Neon Futurista.</p>';
$footer = '<button onclick="closeModal(\'demo-modal\')" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg">Fechar</button>';
include __DIR__ . '/../../../app/Presentation/Components/modal.php';
?>

<?php 
$id = 'neon-modal';
$title = 'Modal Neon';
$variant = 'neon';
$content = '<p class="text-gray-300">Modal com efeito neon e borda verde vibrante!</p>';
$footer = '<button onclick="closeModal(\'neon-modal\')" class="bg-neon-500 hover:bg-neon-400 text-dark-950 px-4 py-2 rounded-lg font-bold">Fechar</button>';
include __DIR__ . '/../../../app/Presentation/Components/modal.php';
?>

<style>
.shadow-neon-glow {
    box-shadow: 0 0 20px rgba(57, 255, 20, 0.4);
}
</style>

<?php include __DIR__ . '/../../layouts/footer.php'; ?>