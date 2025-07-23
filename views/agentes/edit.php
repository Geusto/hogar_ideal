<?php 
$title = 'Editar Agente - Hogar Ideal';
ob_start(); 
?>
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Editar Agente</h1>
            <p class="text-gray-600">Modifica la información del agente</p>
        </div>
        <a href="<?= url('agente', 'index') ?>" 
        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Volver
        </a>
    </div>

    <?php
    if (isset($_GET['msg'])) {
        echo mostrarMensaje($_GET['msg'], $_GET['tipo'] ?? 'exito');
    }
    ?>

    <!-- Formulario -->
    <div class="bg-white rounded-lg shadow-md p-8">
        <form method="POST" action="<?= url('agente', 'update', $agente['id_agente']) ?>" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Información básica -->
                <div class="md:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Información Básica</h3>
                </div>

                <div>
                    <label for="nombre_completo" class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo *</label>
                    <input type="text" id="nombre_completo" name="nombre_completo" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?= $agente['nombre_completo'] ?>">
                </div>
                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">Teléfono *</label>
                    <input type="text" id="telefono" name="telefono" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?= $agente['telefono'] ?>">
                </div>  
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                    <input type="email" id="email" name="email" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?= $agente['email'] ?>">
                </div>  
                <div>
                    <label for="zona_asignada" class="block text-sm font-medium text-gray-700 mb-2">Zona Asignada *</label>
                    <input type="text" id="zona_asignada" name="zona_asignada" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?= $agente['zona_asignada'] ?>">
                </div>
                <div>
                    <label for="activo" class="block text-sm font-medium text-gray-700 mb-2">Estado *</label>
                    <select name="activo" id="activo" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="1" <?= $agente['activo'] == 1 ? 'selected' : '' ?>>Activo</option>
                        <option value="0" <?= $agente['activo'] == 0 ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </div>

                <!-- Imagen de perfil -->
                <div class="md:col-span-2 mt-8 mb-4">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Imagen de perfil</h3>
                    <?php if (!empty($agente['imagen_perfil'])): ?>
                        <div class="mb-2">
                            <img src="<?php echo htmlspecialchars($agente['imagen_perfil']); ?>" alt="Portada actual" style="max-width: 200px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                            <div class="text-xs text-gray-500">Portada actual</div>
                        </div>
                        <label class="flex items-center mb-2">
                            <input type="checkbox" name="eliminar_imagen" value="1" class="mr-2">
                            <span class="text-sm text-red-600">Eliminar imagen actual</span>
                        </label>
                    <?php endif; ?>
                    <input type="file" id="imagen_perfil" name="imagen_perfil" accept="image/*" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <div class="text-xs text-gray-500 mt-1">Si seleccionas una nueva imagen, reemplazará la actual.</div>
                </div>
            </div>
            
            <!-- Botones -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="<?= url('agente', 'index') ?>" 
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                    Cancelar
                </a>
                <button type="submit" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors" id="btn-guardar-agente" disabled>
                    <i class="fas fa-save mr-2"></i>Actualizar Agente
                </button>
            </div>
        </form>
    <div>
</div>

<script>
// Guardar los valores originales al cargar
const form = document.querySelector('form');
const original = {
    nombre_completo: form.nombre_completo.value,
    telefono: form.telefono.value,
    email: form.email.value,
    zona_asignada: form.zona_asignada.value,
    activo: form.activo.value,
    eliminar_imagen: form.eliminar_imagen ? form.eliminar_imagen.checked : false,
};
let originalImagen = null;
const fileInput = document.getElementById('imagen_perfil');
if (fileInput) {
    originalImagen = fileInput.value;
}

function hayCambios() {
    if (form.nombre_completo.value !== original.nombre_completo) return true;
    if (form.telefono.value !== original.telefono) return true;
    if (form.email.value !== original.email) return true;
    if (form.zona_asignada.value !== original.zona_asignada) return true;
    if (form.activo.value !== original.activo) return true;
    if (form.eliminar_imagen && form.eliminar_imagen.checked !== original.eliminar_imagen) return true;
    if (fileInput && fileInput.value !== originalImagen && fileInput.value !== '') return true;
    return false;
}

function toggleBotonGuardar() {
    document.getElementById('btn-guardar-agente').disabled = !hayCambios();
}

form.addEventListener('input', toggleBotonGuardar);
if (fileInput) fileInput.addEventListener('change', toggleBotonGuardar);
if (form.eliminar_imagen) form.eliminar_imagen.addEventListener('change', toggleBotonGuardar);
</script>

<?php 
$content = ob_get_clean();
include 'views/layouts/main.php';
?> 