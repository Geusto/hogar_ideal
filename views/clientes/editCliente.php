<?php 
$title = 'Editar Cliente - Hogar Ideal';
ob_start(); 
?>
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Editar Cliente</h1>
            <p class="text-gray-600">Modifica la información del Cliente</p>
        </div>
        <a href="<?= prettyUrl('cliente', 'viewCliente') ?>" 
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
        <form method="POST" action="<?= prettyUrl('cliente', 'update', $cliente['id_cliente']) ?>" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                

                <div>
                    <label for="nombre_completo" class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo</label>
                    <input type="text" id="nombre_completo" name="nombre_completo" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?= $cliente['nombre_completo'] ?>">
                </div>
                <div>
                    <label for="telefono" class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                    <input type="text" id="telefono" name="telefono" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?= $cliente['telefono'] ?>">
                </div>  
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="email" name="email" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?= $cliente['email'] ?>">
                </div>  
                <div>
                    <label for="direccion" class="block text-sm font-medium text-gray-700 mb-2">Direccion</label>
                    <input type="text" id="direccion" name="direccion" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?= $cliente['direccion'] ?>">
                </div>
                <div>
                    <label for="tipo" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Cliente</label>
                    <select name="tipo" id="tipo" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="Comprador" <?= $cliente['tipo'] == "Comprador" ? 'selected' : '' ?>>Comprador</option>
                        <option value="Vendedor" <?= $cliente['tipo'] == "Vendedor" ? 'selected' : '' ?>>Vendedor</option>
                        <option value="Ambos" <?= $cliente['tipo'] == "Ambos" ? 'selected' : '' ?>>Ambos</option>
                    </select>
                </div>
                <div>
                    <label for="idTipoDocumento" class="block text-sm font-medium text-gray-700 mb-2">Tipo de Documento</label>
                    <select name="idTipoDocumento" id="idTipoDocumento" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="1" <?= $cliente['idTipoDocumento'] == 1 ? 'selected' : '' ?>>Cédula de Ciudadanía (CC)</option>
                        <option value="2" <?= $cliente['idTipoDocumento'] == 2 ? 'selected' : '' ?>>Cédula de Extranjería (CE)</option>
                        <option value="3" <?= $cliente['idTipoDocumento'] == 3 ? 'selected' : '' ?>>Pasaporte (PP)</option>
                    </select>
                </div>
                <div>
                    <label for="documento" class="block text-sm font-medium text-gray-700 mb-2">Numero De Documento</label>
                    <input type="text" id="documento" name="documento" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            value="<?= $cliente['documento'] ?>">
                </div>
                <div>
                    <label for="statusC" class="block text-sm font-medium text-gray-700 mb-2">Estado</label>
                    <select name="statusC" id="statusC" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="1" <?= $cliente['statusC'] == 1 ? 'selected' : '' ?>>Activo</option>
                        <option value="0" <?= $cliente['statusC'] == 0 ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                </div>
            </div>
            
            <!-- Botones -->
            <div class="flex justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="<?= prettyUrl('cliente', 'viewCliente') ?>" 
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                    Cancelar
                </a>
                <button type="submit" 
                    class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors" id="btn-guardar-cliente" disabled>
                    <i class="fas fa-save mr-2"></i>Actualizar Cliente
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
    direccion: form.direccion.value,
    tipo: form.tipo.value,
    idTipoDocumento: form.idTipoDocumento.value,
    documento: form.documento.value,
};


function hayCambios() {
    if (form.nombre_completo.value !== original.nombre_completo) return true;
    if (form.telefono.value !== original.telefono) return true;
    if (form.email.value !== original.email) return true;
    if (form.direccion.value !== original.direccion) return true;
    if (form.tipo.value !== original.tipo) return true;
    if (form.idTipoDocumento.value !== original.idTipoDocumento) return true;
    if (form.documento.value !== original.documento) return true;
    return false;
}

function toggleBotonGuardar() {
    document.getElementById('btn-guardar-cliente').disabled = !hayCambios();
}

const campos = [
    'nombre_completo',
    'telefono',
    'email',
    'direccion',
    'tipo',
    'idTipoDocumento',
    'documento'
];

campos.forEach(campo => {
    form[campo].addEventListener('input', toggleBotonGuardar);
    form[campo].addEventListener('change', toggleBotonGuardar);
});

</script>


<?php 
$content = ob_get_clean();
include 'views/layouts/main.php';
?> 