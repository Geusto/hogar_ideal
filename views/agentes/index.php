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
        <a href="<?= url('home', 'index') ?>" 
            class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Volver
        </a>
        <a href="<?= url('agente', 'create') ?>" 
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
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teléfono</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Zona Asignada</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($agentes as $agente): ?>
            <tr class="border-b hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap text-gray-800 font-semibold"><?php echo ucfirst($agente['nombre_completo']); ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?php echo $agente['email']; ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?php echo $agente['telefono']; ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?php echo $agente['zona_asignada']; ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?php echo $agente['activo'] == 1 ? 'Activo' : 'Inactivo'; ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-right">
                <a href="<?= url('agente', 'edit', $agente['id_agente']) ?>" class="text-blue-500 hover:text-blue-700 mr-3" title="Editar">
                  <i class="fas fa-edit"></i>
                </a>
                <a href="<?= url('agente', 'delete', $agente['id_agente']) ?>" class="text-red-500 hover:text-red-700" title="Eliminar" onclick="return confirm('¿Estás seguro de eliminar este agente?');">
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
$content = ob_get_clean();
include 'views/layouts/main.php';
?> 