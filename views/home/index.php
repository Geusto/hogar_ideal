<?php 
$title = 'Dashboard - Hogar Ideal';
ob_start(); 
?>

<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-800 mb-4">Bienvenido a Hogar Ideal</h1>
        <p class="text-xl text-gray-600">Sistema de Gestión Inmobiliaria</p>
    </div>

    <!-- Estadísticas rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-12">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-home text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Propiedades</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo $totalPropiedades ?? 0; ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-users text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Clientes</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo $totalClientes ?? 0; ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-user-tie text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Agentes</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo $totalAgentes ?? 0; ?></p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-orange-100 text-orange-600">
                    <i class="fas fa-chart-line text-2xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Ventas</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo $totalVentas ?? 0; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
        <a href="<?= prettyUrl('propiedad', 'index') ?>" 
            class="bg-blue-500 hover:bg-blue-600 text-white p-8 rounded-lg font-semibold transition-colors text-center">
            <i class="fas fa-home text-4xl mb-4"></i>
            <div class="text-xl mb-2">Gestionar Propiedades</div>
            <div class="text-sm opacity-90">Crear, editar y administrar propiedades</div>
        </a>
        
        <a href="<?= prettyUrl('cliente', 'viewCliente') ?>" 
            class="bg-green-500 hover:bg-green-600 text-white p-8 rounded-lg font-semibold transition-colors text-center">
            <i class="fas fa-users text-4xl mb-4"></i>
            <div class="text-xl mb-2">Gestionar Clientes</div>
            <div class="text-sm opacity-90">Administrar información de clientes</div>
        </a>

        <a href="<?= prettyUrl('agente', 'index') ?>" 
            class="bg-indigo-500 hover:bg-indigo-600 text-white p-8 rounded-lg font-semibold transition-colors text-center">
            <i class="fas fa-users text-4xl mb-4"></i>
            <div class="text-xl mb-2">Gestionar Agentes</div>
            <div class="text-sm opacity-90">Administrar información de agentes</div>
        </a>

        <a href="<?= prettyUrl('venta', 'index') ?>" 
            class="bg-yellow-500 hover:bg-yellow-600 text-white p-8 rounded-lg font-semibold transition-colors text-center">
            <i class="fas fa-users text-4xl mb-4"></i>
            <div class="text-xl mb-2">Gestionar Ventas</div>
            <div class="text-sm opacity-90">Administrar información de ventas</div>
        </a>
    </div>
</div>

<?php 
$content = ob_get_clean();
include 'views/layouts/main.php';
?> 