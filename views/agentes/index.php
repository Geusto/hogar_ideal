<?php 
$title = 'Agentes - Hogar Ideal';
ob_start(); 
?>

<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Agentes</h1>
            <p class="text-gray-600">Gestiona todos los agentes del sistema</p>
        </div>
        <div class="flex gap-4">
        <a href="<?= prettyUrl('home', 'index') ?>" 
            class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Volver
        </a>
                <a href="<?= prettyUrl('agente', 'create') ?>" 
        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
            <i class="fas fa-plus mr-2"></i>Nuevo Agente
        </a>
        </div>
    </div>

    <?php
    if (isset($_GET['msg'])) {
      echo mostrarMensaje($_GET['msg'], $_GET['tipo'] ?? 'exito');
    }
    ?>

    <!-- Lista de agentes -->
    <div class="overflow-x-auto">
      <table class="min-w-full bg-white rounded-lg shadow-md">
        <thead>
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Imagen</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo de Documento</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documento</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Zona Asignada</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($agentes as $agente): ?>
            <tr class="border-b hover:bg-gray-50">
              <td class="px-6 py-4 ">
                <?php if (!empty($agente['imagen_perfil'])): ?>
                  <img src="<?php echo assetUrl($agente['imagen_perfil']); ?>" alt="Imagen de perfil" class="object-cover w-10 h-10 rounded-full">
                <?php else: ?>
                  <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center">
                    <i class="fas fa-user text-gray-400 text-lg"></i>
                  </div>
                <?php endif; ?>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-800 font-semibold"><?php echo ucfirst($agente['nombre_completo']); ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?php echo $agente['email']; ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?php echo $agente['telefono']; ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?php echo $agente['tipo_documento_nombre']; ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?php echo $agente['documento']; ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?php echo $agente['zona_asignada']; ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?php echo $agente['activo'] == 1 ? 'Activo' : 'Inactivo'; ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-right">
                                <a href="<?= prettyUrl('agente', 'edit', $agente['id_agente']) ?>" class="text-blue-500 hover:text-blue-700 mr-3" title="Editar">
                    <i class="fas fa-edit"></i>
                </a>
                <a href="#" class="text-red-500 hover:text-red-700 btn-eliminar-agente" data-url="<?= prettyUrl('agente', 'delete', $agente['id_agente']) ?>" title="Eliminar" data-nombre="<?= htmlspecialchars(ucfirst($agente['nombre_completo'])) ?>">
                    <i class="fas fa-trash-alt"></i>
                </a>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
</div>

<?php
// Incluir el modal de confirmación reutilizable
$modal_id = 'modal-eliminar-agente';
$mensaje = '¿Estás seguro de que quieres eliminar este agente? Esta acción no se puede deshacer.';
$texto_confirmar = 'Eliminar';
$texto_cancelar = 'Cancelar';
$url_accion = '';
include 'views/layouts/modal_confirmacion.php';
?>

<script>
  let urlEliminarAgente = '';
  let modal = document.getElementById('modal-eliminar-agente');
  document.querySelectorAll('.btn-eliminar-agente').forEach(btn => {
    btn.addEventListener('click', function(e) {
      e.preventDefault();
      urlEliminarAgente = this.getAttribute('data-url');
      // Si quieres personalizar el mensaje con el nombre, puedes hacerlo aquí
      // modal.querySelector('p').textContent = `¿Estás seguro de que quieres eliminar al agente ${this.getAttribute('data-nombre')}? Esta acción no se puede deshacer.`;
      showModal('modal-eliminar-agente');
    });
  });
  document.querySelectorAll('.btn-cancelar-modal').forEach(btn => {
    btn.addEventListener('click', function() {
      let modalId = this.getAttribute('data-modal');
      hideModal(modalId);
      urlEliminarAgente = '';
    });
  });
  document.querySelectorAll('.btn-confirmar-modal').forEach(btn => {
    btn.addEventListener('click', function() {
      let url = urlEliminarAgente || this.getAttribute('data-url');
      if(url) {
        window.location.href = url;
      }
    });
  });
</script>

<?php 
$content = ob_get_clean();
include 'views/layouts/main.php';
?> 