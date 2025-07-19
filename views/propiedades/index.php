<?php 
$title = 'Propiedades - Hogar Ideal';
ob_start(); 
?>

<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Propiedades</h1>
            <p class="text-gray-600">Gestiona todas las propiedades del sistema</p>
        </div>
        <div class="flex gap-4">
        <a href="index.php?controller=home&action=index" 
        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Volver
        </a>
        <a href="index.php?controller=propiedad&action=create" 
        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
            <i class="fas fa-plus mr-2"></i>Nueva Propiedad
        </a>
        </div>
    </div>

    <!-- Mensajes de éxito/error -->
    <?php if (isset($_GET['success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            <?php
            $success = $_GET['success'];
            switch($success) {
                case '1': echo 'Propiedad creada exitosamente.'; break;
                case '2': echo 'Propiedad actualizada exitosamente.'; break;
                case '3': echo 'Propiedad eliminada exitosamente.'; break;
            }
            ?>
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
            <?php
            $error = $_GET['error'];
            switch($error) {
                case 'not_found': echo 'Propiedad no encontrada.'; break;
                case 'delete_failed': echo 'Error al eliminar la propiedad.'; break;
                default: echo 'Ha ocurrido un error.'; break;
            }
            ?>
        </div>
    <?php endif; ?>

    <!-- Filtros y búsqueda -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <form method="GET" class="md:col-span-2">
                <input type="hidden" name="controller" value="propiedad">
                <input type="hidden" name="action" value="search">
                <div class="flex">
                    <input type="text" name="q" placeholder="Buscar propiedades..." 
                        value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>"
                        class="flex-1 border border-gray-300 rounded-l-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-r-lg hover:bg-blue-600">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
            
            <select id="filtroEstado" onchange="aplicarFiltro()" 
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Todos los estados</option>
                <option value="disponible" <?php echo (isset($_GET['estado']) && $_GET['estado'] === 'disponible') ? 'selected' : ''; ?>>Disponible</option>
                <option value="vendida" <?php echo (isset($_GET['estado']) && $_GET['estado'] === 'vendida') ? 'selected' : ''; ?>>Vendida</option>
            </select>
            
            <select id="filtroTipo" onchange="aplicarFiltro()" 
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Todos los tipos</option>
                <option value="casa" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] === 'casa') ? 'selected' : ''; ?>>Casa</option>
                <option value="apartamento" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] === 'apartamento') ? 'selected' : ''; ?>>Apartamento</option>
                <option value="terreno" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] === 'terreno') ? 'selected' : ''; ?>>Terreno</option>
                <option value="local" <?php echo (isset($_GET['tipo']) && $_GET['tipo'] === 'local') ? 'selected' : ''; ?>>Local Comercial</option>
            </select>
        </div>
        
        <!-- Botón para limpiar filtros -->
        <?php if (isset($_GET['estado']) || isset($_GET['tipo']) || isset($_GET['q'])): ?>
            <div class="mt-4">
                <a href="index.php?controller=propiedad&action=index" 
                class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                    <i class="fas fa-times mr-1"></i>Limpiar filtros
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Lista de propiedades -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (empty($propiedades)): ?>
            <div class="col-span-full text-center py-12">
                <i class="fas fa-home text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No hay propiedades</h3>
                <p class="text-gray-500">
                    <?php if (isset($_GET['q']) || isset($_GET['estado']) || isset($_GET['tipo'])): ?>
                        No se encontraron propiedades con los filtros aplicados
                    <?php else: ?>
                        Comienza agregando tu primera propiedad
                    <?php endif; ?>
                </p>
            </div>
        <?php else: ?>
            <?php foreach ($propiedades as $propiedad): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <?php if (!empty($propiedad['portada'])): ?>
                        <img src="<?php echo htmlspecialchars($propiedad['portada']); ?>" alt="Portada" style="width:100%; max-height:180px; object-fit:cover;">
                    <?php endif; ?>
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-xl font-semibold text-gray-800"><?php echo ucfirst($propiedad['tipo']); ?> en <?php echo htmlspecialchars($propiedad['direccion']); ?></h3>
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                <?php echo $propiedad['estado'] === 'disponible' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                <?php echo ucfirst($propiedad['estado']); ?>
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4 text-sm text-gray-600">
                            <?php if ($propiedad['habitaciones']): ?>
                                <div><i class="fas fa-bed mr-2"></i><?php echo $propiedad['habitaciones']; ?> hab.</div>
                            <?php endif; ?>
                            <?php if ($propiedad['banos']): ?>
                                <div><i class="fas fa-bath mr-2"></i><?php echo $propiedad['banos']; ?> baños</div>
                            <?php endif; ?>
                            <div><i class="fas fa-ruler-combined mr-2"></i><?php echo $propiedad['superficie']; ?> m²</div>
                            <div><i class="fas fa-user mr-2"></i><?php echo htmlspecialchars($propiedad['cliente_vendedor'] ?? 'N/A'); ?></div>
                        </div>
                        
                        <div class="text-2xl font-bold text-blue-600 mb-4">
                            $<?php echo number_format($propiedad['precio'], 0, ',', '.'); ?>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <div class="flex space-x-2">
                                <a href="index.php?controller=propiedad&action=show&id=<?php echo $propiedad['id_propiedad']; ?>" 
                                   class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="index.php?controller=propiedad&action=edit&id=<?php echo $propiedad['id_propiedad']; ?>" 
                                   class="text-green-500 hover:text-green-700">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="index.php?controller=propiedad&action=delete&id=<?php echo $propiedad['id_propiedad']; ?>" 
                                   class="text-red-500 hover:text-red-700"
                                   onclick="return confirm('¿Estás seguro de que quieres eliminar esta propiedad?')">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                            <span class="text-sm text-gray-500">Agente: <?php echo htmlspecialchars($propiedad['agente_nombre'] ?? 'N/A'); ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
    function aplicarFiltro() {
        const estado = document.getElementById('filtroEstado').value;
        const tipo = document.getElementById('filtroTipo').value;
        
        let url = 'index.php?controller=propiedad&action=index';
        
        if (estado) {
            url += '&estado=' + estado;
        }
        
        if (tipo) {
            url += '&tipo=' + tipo;
        }
        
        window.location.href = url;
    }
</script>

<?php 
$content = ob_get_clean();
include 'views/layouts/main.php';
?> 