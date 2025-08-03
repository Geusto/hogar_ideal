<?php 
$title = 'Ventas - Hogar Ideal';
ob_start(); 
?>

<?php
if (isset($_GET['msg'])) {
  echo mostrarMensaje($_GET['msg'], $_GET['tipo'] ?? 'exito');
}
?>




<!-- Lista de ventas -->
<div class="container mx-auto px-4 py-8">
  <h1 class="text-3xl font-bold text-gray-800">Ventas</h1>
  <p class="text-gray-600 mb-8">Gestiona todas las ventas del sistema</p>
  <table class="min-w-full bg-white rounded-lg shadow-md">
    <thead>
      <tr>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha de Venta</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Precio de Venta</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente vendedor</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente Comprador</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agente</th>
        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Propiedad</th>
        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($ventas as $venta): ?>
        <tr class="border-b hover:bg-gray-50">
          <td class="px-6 py-4 ">
            <?php echo $venta['fecha_venta']; ?>
          </td>
          <td class="px-6 py-4 whitespace-nowrap text-gray-800 font-semibold"><?php echo $venta['precio_final']; ?></td>
          <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?php echo $venta['cliente_vendedor']; ?></td>
          <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?php echo $venta['cliente_comprador']; ?></td>
          <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?php echo $venta['agente_nombre']; ?></td>
          <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?php echo $venta['propiedad_direccion']; ?></td>
          <td class="px-6 py-4 whitespace-nowrap text-right">
          <!-- <a href="<?= prettyUrl('venta', 'edit', $venta['id_venta']) ?>" class="text-blue-500 hover:text-blue-700 mr-3" title="Editar">
            <i class="fas fa-edit"></i>
          </a>
            <a href="#" class="text-red-500 hover:text-red-700 btn-eliminar-venta" data-url="<?= prettyUrl('venta', 'delete', $venta['id_venta']) ?>" title="Eliminar" data-nombre="<?= htmlspecialchars(ucfirst($venta['nombre_completo'])) ?>">
                <i class="fas fa-trash-alt"></i>
            </a> -->
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>


<?php
// Incluir el modal de confirmación reutilizable
$modal_id = 'modal-eliminar-venta';
$mensaje = '¿Estás seguro de que quieres eliminar esta venta? Esta acción no se puede deshacer.';
$texto_confirmar = 'Eliminar';
$texto_cancelar = 'Cancelar';
$url_accion = '';
include 'views/layouts/modal_confirmacion.php';
?>

<?php 
$content = ob_get_clean();
include 'views/layouts/main.php';
?>