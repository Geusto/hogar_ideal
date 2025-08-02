<?php 
$title = 'Nuevo Agente - Hogar Ideal';
ob_start(); 
?>
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Nuevo Agente</h1>
            <p class="text-gray-600">Agrega un nuevo agente al sistema</p>
        </div>
        <a href="<?= prettyUrl('agente', 'index') ?>" 
        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Volver
        </a>
    </div>

    <?php
    if (isset($msg)) {
        echo mostrarMensaje($msg, $tipo ?? 'error');
    } elseif (isset($_GET['msg'])) {
        echo mostrarMensaje($_GET['msg'], $_GET['tipo'] ?? 'exito');
    }
    ?>

    <!-- Formulario -->
    <div class="bg-white rounded-lg shadow-md p-8">
        <form method="POST" action="<?= prettyUrl('agente', 'store') ?>" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Información básica -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Información Básica</h3>
                </div>

                <div>
                    <label for="nombre_completo" class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo *</label>
                    <input type="text" id="nombre_completo" name="nombre_completo" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="<?= isset($agente['nombre_completo']) ? htmlspecialchars($agente['nombre_completo']) : '' ?>">
                </div>
                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">Teléfono *</label>
                    <input type="tel" id="telefono" name="telefono" required
                        pattern="[0-9+]{8,15}"
                        title="Solo números y el símbolo +"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="<?= isset($agente['telefono']) ? htmlspecialchars($agente['telefono']) : '' ?>">
                </div>  
                <div>
                    <label for="tipo_documento" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Documento *</label>
                    <select name="tipo_documento" id="tipo_documento" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <?php foreach ($tipos_documento as $tipo): ?>
                            <option value="<?= $tipo['idTipoDocumento'] ?>"><?= $tipo['descripcion'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>  
                <div>
                    <label for="documento" class="block text-sm font-medium text-gray-700 mb-2">Documento *</label>
                    <input type="text" id="documento" name="documento" required
                        pattern="[0-9]{8,15}"
                        title="Solo números" 
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="<?= isset($agente['documento']) ? htmlspecialchars($agente['documento']) : '' ?>">
                </div>  

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" id="email" name="email" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                        value="<?= isset($agente['email']) ? htmlspecialchars($agente['email']) : '' ?>">
                </div>  
                <div>
                    <label for="zona_asignada" class="block text-sm font-medium text-gray-700 mb-2">Zona Asignada *</label>
                    <input type="text" id="zona_asignada" name="zona_asignada" required
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" value="<?= isset($agente['zona_asignada']) ? htmlspecialchars($agente['zona_asignada']) : '' ?>">
                </div>

                <!-- Imagen de perfil -->
                <div class="md:col-span-2 mt-8 mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Imagen de perfil</h3>
                    <input type="file" id="imagen_perfil" name="imagen_perfil" accept="image/*" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <div class="text-xs text-gray-500 mt-1">Si seleccionas una nueva imagen, reemplazará la actual.</div>
                </div>
            </div>
            
            <!-- Botones -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="<?= prettyUrl('agente', 'index') ?>" 
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                    Cancelar
                </a>
                <button type="submit" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                    <i class="fas fa-save mr-2"></i>Guardar Agente
                </button>
            </div>
        </form>
    <div>
</div>

<?php 
$content = ob_get_clean();
include 'views/layouts/main.php';
?> 