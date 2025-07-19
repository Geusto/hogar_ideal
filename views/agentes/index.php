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
        <a href="index.php?controller=home&action=index" 
        class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Volver
        </a>
        <a href="index.php?controller=agente&action=create" 
        class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
            <i class="fas fa-plus mr-2"></i>Nuevo Agente
        </a>
        </div>
    </div>

    <!-- Lista de agentes -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <?php foreach ($agentes as $agente): ?>
          <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow p-6">
              <div class="flex items-center mb-4">
              <i class="fas fa-user mr-2"></i>
                <h3 class="text-xl font-semibold text-gray-800"><?php echo ucfirst($agente['nombre_completo']); ?></h3>
              </div>
              <div class="flex items-center mb-4">
                <i class="fas fa-envelope mr-2"></i>
                <p class="text-gray-600"><?php echo ucfirst($agente['email']); ?></p>
              </div>
              <div class="flex items-center mb-4">
                <i class="fas fa-phone mr-2"></i>
                <p class="text-gray-600"><?php echo ucfirst($agente['telefono']); ?></p>
              </div>
          </div>
      <?php endforeach; ?>
    </div>
</div>

<?php 
$content = ob_get_clean();
include 'views/layouts/main.php';
?> 