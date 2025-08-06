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
      <!-- Header -->
      <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Ventas</h1>
            <p class="text-gray-600">Gestiona todas las ventas del sistema</p>
        </div>
        <div class="flex gap-4">
        <a href="<?= prettyUrl('home', 'index') ?>" 
            class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Volver
        </a>
                <a href="<?= prettyUrl('venta', 'create') ?>" 
        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
            <i class="fas fa-plus mr-2"></i>Nueva Venta
        </a>
        </div>
    </div>
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
          <td class="px-6 py-4 whitespace-nowrap text-gray-800 font-semibold">$<?php echo number_format($venta['precio_final'], 2, ',', '.'); ?></td>
          <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?php echo $venta['cliente_vendedor']; ?></td>
          <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?php echo $venta['cliente_comprador']; ?></td>
          <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?php echo $venta['agente_nombre']; ?></td>
          <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?php echo $venta['propiedad_direccion']; ?></td>
          <td class="px-6 py-4 whitespace-nowrap text-right">
            <div class="flex space-x-2 justify-end">
              <a href="<?= prettyUrl('venta', 'comprobantePDFView', $venta['id_venta']) ?>" 
                class="text-blue-500 hover:text-blue-700" 
                title="Ver comprobante en navegador" 
                target="_blank">
                  <i class="fas fa-eye"></i>
              </a>
              <a href="<?= prettyUrl('venta', 'comprobantePDF', $venta['id_venta']) ?>" 
                class="text-green-500 hover:text-green-700" 
                title="Descargar comprobante PDF">
                  <i class="fas fa-download"></i>
              </a>
            </div>
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