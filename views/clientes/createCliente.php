<?php 
$title = 'Nuevo Cliente - Hogar Ideal';
ob_start(); 
?>

<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Nuevo Cliente</h1>
            <p class="text-gray-600">Agrega un nuevo cliente al sistema</p>
        </div>
        <a href="<?= url('clientes', 'viewCliente') ?>" 
            class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Volver
        </a>
    </div>

    <!-- Formulario -->
    <div class="bg-white rounded-lg shadow-md p-8">
        <form method="POST" action="<?= url('cliente', 'store') ?>" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Información básica -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Información Básica</h3>
                </div>
                
                <div>
                    <label for="nombre_completo" class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo *</label>
                    <input type="text" id="nombre_completo" name="nombre_completo" required
                      class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                      placeholder="Nombre y Apellido">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" id="email" name="email" required
                      class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                      placeholder="Correo Electrónico">
                </div>
                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                    <input type="tel" id="telefono" name="telefono"
                      class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                      placeholder="Número de Teléfono">
                </div>

                 <!-- Características -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Características</h3>
                </div>
                <div>
                    <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Cliente *</label>
                    <select id="tipo" name="tipo" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="cliente">Cliente</option>
                        <option value="vendedor">Vendedor</option>
                        <option value="ambos">Ambos</option>
                    </select>
                </div>
                
                <!-- Botones -->
                <div class="flex justify-end space-x-4 mt-8 pt-6 border t border-gray-200">
                    <a href="<?= url('clientes', 'viewCliente') ?>" 
                      class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                        <i class="fas fa-save mr-2"></i>Guardar Cliente
                    </button>
                </div>
            </div>
                
                <div>
                    <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">Estado *</label>
                    <select id="estado" name="estado" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="disponible">Disponible</option>
                        <option value="vendida">Vendida</option>
                    </select>
                </div>
                
               
                
                
                
        </form>
    </div>
</div>

<?php 
$content = ob_get_clean();
include 'views/layouts/main.php';
?> 