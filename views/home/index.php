<?php 
$title = 'Dashboard - Hogar Ideal';
ob_start(); 
?>

<div class="container mx-auto px-4 py-8">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Hogar Ideal</h1>
        <p class="text-xl text-gray-600">Panel de Control</p>
    </div>
    
    <!-- Estadísticas -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <i class="fas fa-home text-4xl text-blue-500 mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-800 mb-2"><?php echo $propiedadesDisponibles; ?></h3>
            <p class="text-gray-600">Propiedades Disponibles</p>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <i class="fas fa-users text-4xl text-green-500 mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-800 mb-2"><?php echo $totalClientes; ?></h3>
            <p class="text-gray-600">Clientes Registrados</p>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <i class="fas fa-user-tie text-4xl text-cyan-500 mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-800 mb-2"><?php echo $totalAgentes; ?></h3>
            <p class="text-gray-600">Agentes Activos</p>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6 text-center">
            <i class="fas fa-chart-line text-4xl text-yellow-500 mb-4"></i>
            <h3 class="text-2xl font-bold text-gray-800 mb-2"><?php echo $ventasMes; ?></h3>
            <p class="text-gray-600">Ventas del Mes</p>
        </div>
    </div>
    
    <!-- Acciones rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
        <a href="<?= url('propiedad', 'index') ?>" 
            class="bg-blue-500 hover:bg-blue-600 text-white p-8 rounded-lg font-semibold transition-colors text-center">
            <i class="fas fa-home text-4xl mb-4"></i>
            <div class="text-xl mb-2">Gestionar Propiedades</div>
            <div class="text-sm opacity-90">Crear, editar y administrar propiedades</div>
        </a>
        
        <a href="<?= url('cliente', 'viewCliente') ?>" 
            class="bg-green-500 hover:bg-green-600 text-white p-8 rounded-lg font-semibold transition-colors text-center">
            <i class="fas fa-users text-4xl mb-4"></i>
            <div class="text-xl mb-2">Gestionar Clientes</div>
            <div class="text-sm opacity-90">Administrar información de clientes</div>
        </a>

        <a href="<?= url('agente', 'index') ?>" 
            class="bg-indigo-500 hover:bg-indigo-600 text-white p-8 rounded-lg font-semibold transition-colors text-center">
            <i class="fas fa-users text-4xl mb-4"></i>
            <div class="text-xl mb-2">Gestionar Agentes</div>
            <div class="text-sm opacity-90">Administrar información de agentes</div>
        </a>
    </div>
</div>

<?php 
$content = ob_get_clean();
include 'views/layouts/main.php';
?> 