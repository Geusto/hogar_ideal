<?php 
$title = ucfirst($propiedad['tipo']) . ' en ' . htmlspecialchars($propiedad['direccion']) . ' - Hogar Ideal';
ob_start(); 
?>

<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800"><?php echo ucfirst($propiedad['tipo']); ?> en <?php echo htmlspecialchars($propiedad['direccion']); ?></h1>
            <p class="text-gray-600">Detalles de la propiedad</p>
        </div>
        <div class="flex space-x-3">
            <a href="<?= prettyUrl('propiedad', 'edit', $propiedad['id_propiedad']) ?>" 
            class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg font-semibold transition-colors">
                <i class="fas fa-edit mr-2"></i>Editar
            </a>
            <a href="<?= prettyUrl('propiedad', 'index') ?>" 
            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-semibold transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
        </div>
    </div>

    <!-- Estado de la propiedad -->
    <div class="mb-6">
        <span class="px-4 py-2 text-sm font-semibold rounded-full 
            <?php echo $propiedad['estado'] === 'disponible' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
            <?php echo ucfirst($propiedad['estado']); ?>
        </span>
    </div>

    <!-- Galería de Fotos -->
    <div class="mb-8">
        <?php if (!empty($fotos)): ?>
            <!-- Foto principal (portada) -->
            <?php if ($fotoPortada): ?>
                <div class="mb-6">
                    <img src="<?php echo assetUrl($fotoPortada['nombre_archivo']); ?>" 
                        alt="<?php echo htmlspecialchars($fotoPortada['descripcion'] ?: 'Foto de portada'); ?>" 
                        class="w-full max-w-4xl mx-auto rounded-lg shadow-lg" 
                        style="max-height: 500px; object-fit: cover;">
                </div>
            <?php endif; ?>
            
            <!-- Galería de miniaturas -->
            <?php if (count($fotos) > 1): ?>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    <?php foreach ($fotos as $foto): ?>
                        <div class="relative group cursor-pointer">
                            <img src="<?php echo assetUrl($foto['nombre_archivo']); ?>" 
                                alt="<?php echo htmlspecialchars($foto['descripcion'] ?: 'Foto de la propiedad'); ?>" 
                                class="w-full h-24 object-cover rounded-lg shadow-md transition-transform duration-200 group-hover:scale-105">
                            
                            <?php if ($foto['es_portada']): ?>
                                <div class="absolute top-2 left-2 bg-blue-500 text-white text-xs px-2 py-1 rounded-full">
                                    Portada
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($foto['descripcion'])): ?>
                                <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-2 rounded-b-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                                    <?php echo htmlspecialchars($foto['descripcion']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <!-- Sin fotos -->
            <div class="bg-gray-100 rounded-lg p-8 text-center">
                <i class="fas fa-images text-4xl text-gray-400 mb-4"></i>
                <p class="text-gray-600">Esta propiedad aún no tiene fotos</p>
                <a href="<?= prettyUrl('propiedad', 'edit', $propiedad['id_propiedad']) ?>" 
                class="inline-block mt-4 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold transition-colors">
                <i class="fas fa-plus mr-2"></i>Agregar Fotos
                </a>
            </div>
        <?php endif; ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Información principal -->
        <div class="lg:col-span-2">
            <!-- Ubicación -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Ubicación</h2>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <i class="fas fa-map-marker-alt text-red-500 mr-3 text-xl"></i>
                        <div>
                            <div class="font-semibold text-gray-800">Dirección</div>
                            <div class="text-gray-600"><?php echo htmlspecialchars($propiedad['direccion']); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Características -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Características</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <i class="fas fa-home text-2xl text-blue-500 mb-2"></i>
                        <div class="text-lg font-semibold text-gray-800"><?php echo ucfirst($propiedad['tipo']); ?></div>
                        <div class="text-sm text-gray-600">Tipo</div>
                    </div>
                    <?php if ($propiedad['habitaciones']): ?>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <i class="fas fa-bed text-2xl text-blue-500 mb-2"></i>
                        <div class="text-lg font-semibold text-gray-800"><?php echo $propiedad['habitaciones']; ?></div>
                        <div class="text-sm text-gray-600">Habitaciones</div>
                    </div>
                    <?php endif; ?>
                    <?php if ($propiedad['banos']): ?>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <i class="fas fa-bath text-2xl text-blue-500 mb-2"></i>
                        <div class="text-lg font-semibold text-gray-800"><?php echo $propiedad['banos']; ?></div>
                        <div class="text-sm text-gray-600">Baños</div>
                    </div>
                    <?php endif; ?>
                    <div class="text-center p-4 bg-gray-50 rounded-lg">
                        <i class="fas fa-ruler-combined text-2xl text-blue-500 mb-2"></i>
                        <div class="text-lg font-semibold text-gray-800"><?php echo $propiedad['superficie']; ?> m²</div>
                        <div class="text-sm text-gray-600">Superficie</div>
                    </div>
                </div>
            </div>

            <!-- Información de relaciones -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Información de Relaciones</h2>
                <div class="space-y-4">
                    <div class="flex items-center">
                        <i class="fas fa-user text-green-500 mr-3 text-xl"></i>
                        <div>
                            <div class="font-semibold text-gray-800">Cliente Vendedor</div>
                            <div class="text-gray-600"><?php echo htmlspecialchars($propiedad['cliente_vendedor'] ?? 'N/A'); ?></div>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-user-tie text-blue-500 mr-3 text-xl"></i>
                        <div>
                            <div class="font-semibold text-gray-800">Agente Responsable</div>
                            <div class="text-gray-600"><?php echo htmlspecialchars($propiedad['agente_nombre'] ?? 'N/A'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Precio -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Precio</h3>
                <div class="text-3xl font-bold text-blue-600 mb-2">
                    $<?php echo number_format($propiedad['precio'], 0, ',', '.'); ?>
                </div>
                <p class="text-gray-600 text-sm">Precio de venta</p>
            </div>

            <!-- Información del sistema -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Información del Sistema</h3>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">ID:</span>
                        <span class="font-semibold"><?php echo $propiedad['id_propiedad']; ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Cliente ID:</span>
                        <span class="font-semibold"><?php echo $propiedad['id_cliente_vendedor']; ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Agente ID:</span>
                        <span class="font-semibold"><?php echo $propiedad['id_agente']; ?></span>
                    </div>
                </div>
            </div>

            <!-- Acciones -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Acciones</h3>
                <div class="space-y-3">
                    <a href="<?= prettyUrl('propiedad', 'edit', $propiedad['id_propiedad']) ?>" 
                    class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold transition-colors text-center block">
                        <i class="fas fa-edit mr-2"></i>Editar Propiedad
                    </a>
                    <a href="<?= prettyUrl('propiedad', 'delete', $propiedad['id_propiedad']) ?>" 
                    class="w-full bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg font-semibold transition-colors text-center block"
                    onclick="return confirm('¿Estás seguro de que quieres eliminar esta propiedad?')">
                        <i class="fas fa-trash mr-2"></i>Eliminar Propiedad
                    </a>
                    <a href="<?= prettyUrl('propiedad', 'index') ?>" 
                    class="w-full bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-semibold transition-colors text-center block">
                        <i class="fas fa-list mr-2"></i>Ver Todas
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean();
include 'views/layouts/main.php';
?> 