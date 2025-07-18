<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nueva Propiedad - Hogar Ideal</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Nueva Propiedad</h1>
                <p class="text-gray-600">Agrega una nueva propiedad al sistema</p>
            </div>
            <a href="index.php?controller=propiedad&action=index" 
              class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>

        <!-- Formulario -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <form method="POST" action="index.php?controller=propiedad&action=store">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Información básica -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Información Básica</h3>
                    </div>
                    
                    <div>
                        <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Propiedad *</label>
                        <select id="tipo" name="tipo" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccionar tipo</option>
                            <option value="casa">Casa</option>
                            <option value="apartamento">Apartamento</option>
                            <option value="terreno">Terreno</option>
                            <option value="local">Local Comercial</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="estado" class="block text-sm font-medium text-gray-700 mb-2">Estado *</label>
                        <select id="estado" name="estado" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="disponible">Disponible</option>
                            <option value="vendida">Vendida</option>
                        </select>
                    </div>
                    
                    <!-- Características -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Características</h3>
                    </div>
                    
                    <div>
                        <label for="habitaciones" class="block text-sm font-medium text-gray-700 mb-2">Habitaciones</label>
                        <input type="number" id="habitaciones" name="habitaciones" min="0" value="0"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label for="banos" class="block text-sm font-medium text-gray-700 mb-2">Baños</label>
                        <input type="number" id="banos" name="banos" min="0" value="0"
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    
                    <div>
                        <label for="superficie" class="block text-sm font-medium text-gray-700 mb-2">Superficie (m²) *</label>
                        <input type="number" id="superficie" name="superficie" min="0" step="0.01" required
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="0.00">
                    </div>
                    
                    <div>
                        <label for="precio" class="block text-sm font-medium text-gray-700 mb-2">Precio ($) *</label>
                        <input type="number" id="precio" name="precio" min="0" step="0.01" required
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="0.00">
                    </div>
                    
                    <!-- Ubicación -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Ubicación</h3>
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="direccion" class="block text-sm font-medium text-gray-700 mb-2">Dirección *</label>
                        <input type="text" id="direccion" name="direccion" required
                          class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                          placeholder="Calle, número, colonia">
                    </div>
                    
                    <!-- Relaciones -->
                    <div class="md:col-span-2">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Relaciones</h3>
                    </div>
                    
                    <div>
                        <label for="id_cliente_vendedor" class="block text-sm font-medium text-gray-700 mb-2">Cliente Vendedor *</label>
                        <select id="id_cliente_vendedor" name="id_cliente_vendedor" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccionar cliente vendedor</option>
                            <?php foreach ($clientesVendedores as $cliente): ?>
                                <option value="<?php echo $cliente['id_cliente']; ?>">
                                    <?php echo htmlspecialchars($cliente['nombre_completo']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label for="id_agente" class="block text-sm font-medium text-gray-700 mb-2">Agente Responsable *</label>
                        <select id="id_agente" name="id_agente" required
                                class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Seleccionar agente</option>
                            <?php foreach ($agentes as $agente): ?>
                                <option value="<?php echo $agente['id_agente']; ?>">
                                    <?php echo htmlspecialchars($agente['nombre_completo']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <!-- Botones -->
                <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                    <a href="index.php?controller=propiedad&action=index" 
                      class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                        Cancelar
                    </a>
                    <button type="submit" 
                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                        <i class="fas fa-save mr-2"></i>Guardar Propiedad
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html> 