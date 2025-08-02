<?php 
$title = 'Clientes - Hogar Ideal';
ob_start(); 

$tiposDoc = [];
foreach ($tiposDocumento as $tipo) {
    $tiposDoc[$tipo['idTipoDocumento']] = $tipo['descripcion'];
}
?>


<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Clientes</h1>
            <p class="text-gray-600">Gestiona todos los Clientes del sistema</p>
        </div>
        <div class="flex gap-4">
        <a href="<?= prettyUrl('home', 'index') ?>" 
            class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Volver
        </a>
        <a href="<?= prettyUrl('cliente', 'create') ?>" 
          class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
            <i class="fas fa-plus mr-2"></i>Nuevo Cliente
        </a>
        </div>
    </div>

    <?php
    if (isset($_GET['msg'])) {
      echo mostrarMensaje($_GET['msg'], $_GET['tipo'] ?? 'exito');
    }
    ?>

    <div class="flex justify-between items-center mb-8">

    <!-- Filtro de búsqueda -->
    <form method="GET" action="?url=cliente/viewCliente" class="mb-6 flex flex-wrap items-center gap-4">
        <input type="text" name="buscar" placeholder="Buscar cliente..." 
            value="<?= $_GET['buscar'] ?? '' ?>"
            class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 w-64">

        <select name="tipo" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Todos los tipos</option>
            <option value="Comprador" <?= ($_GET['tipo'] ?? '') == 'Comprador' ? 'selected' : '' ?>>Comprador</option>
            <option value="Vendedor" <?= ($_GET['tipo'] ?? '') == 'Vendedor' ? 'selected' : '' ?>>Vendedor</option>
            <option value="Ambos" <?= ($_GET['tipo'] ?? '') == 'Ambos' ? 'selected' : '' ?>>Ambos</option>
        </select>

        <select name="estado" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Todos los estados</option>
            <option value="1" <?= ($_GET['estado'] ?? '') == '1' ? 'selected' : '' ?>>Activo</option>
            <option value="0" <?= ($_GET['estado'] ?? '') == '0' ? 'selected' : '' ?>>Inactivo</option>
        </select>

        <select name="tipoDocumento" class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Todos los tipos de documento</option>
            <?php foreach ($tiposDoc as $id => $descripcion): ?>
                <option value="<?= $id ?>" <?= ($_GET['tipoDocumento'] ?? '') == $id ? 'selected' : '' ?>>
                    <?= $descripcion ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold transition-colors">
            <i class="fas fa-search mr-2"></i>Filtrar
        </button>
    </form>
    </div>


    <!-- Lista de clientes -->
    <div class="overflow-x-auto">
      <table class="min-w-full bg-white rounded-lg shadow-md">
        <thead>
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre Completo</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Documento</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Direccion</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Telefono</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo de Documento</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Estado</th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($clientes as $cliente): ?>
            <tr class="border-b hover:bg-gray-50">
              
              <td class="px-6 py-4 whitespace-nowrap text-gray-800 font-semibold"><?php echo ucfirst($cliente['nombre_completo']); ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?php echo $cliente['documento']; ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?php echo $cliente['direccion']; ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?php echo $cliente['telefono']; ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?php echo $cliente['email']; ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?php echo $cliente['tipo']; ?></td>
              <<td class="px-6 py-4 whitespace-nowrap text-gray-600">
                  <?php echo isset($tiposDoc[$cliente['idTipoDocumento']]) ? $tiposDoc[$cliente['idTipoDocumento']] : 'Sin documento'; ?>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-gray-600"><?php echo $cliente['statusC'] == 1 ? 'Activo' : 'Inactivo'; ?></td>
              <td class="px-6 py-4 whitespace-nowrap text-right">
                <a href="<?= prettyUrl('cliente', 'edit', $cliente['id_cliente']) ?>" class="text-blue-500 hover:text-blue-700 mr-3" title="Editar">
                  <i class="fas fa-edit"></i>
                </a>
                <a href="<?= prettyUrl('cliente', 'changeStatus', $cliente['id_cliente']) ?>?status=<?= ($cliente['statusC'] == 1 ? 0 : 1) ?>"
                  class="text-red-500 hover:text-red-700"
                  title="Cambiar Estado">
                  <i class="fas fa-exchange-alt mr-2"></i>
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