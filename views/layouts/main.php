<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Hogar Ideal'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-100">
    <!-- Header/Navegación -->
    <nav class="bg-white shadow-md">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center">
                    <a href="index.php" class="text-2xl font-bold text-blue-600">
                        <i class="fas fa-home mr-2"></i>Hogar Ideal
                    </a>
                </div>
                <div class="hidden md:flex space-x-6">
                    <a href="<?= url('home', 'index') ?>" 
                        class="text-gray-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-tachometer-alt mr-1"></i>Dashboard
                    </a>
                    <a href="<?= url('propiedad', 'index') ?>" 
                        class="text-gray-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-home mr-1"></i>Propiedades
                    </a>
                    <a href="<?= url('cliente', 'viewCliente') ?>" 
                        class="text-gray-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-users mr-1"></i>Clientes
                    </a>
                    <a href="<?= url('agente', 'index') ?>" 
                        class="text-gray-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-user-tie mr-1"></i>Agentes
                    </a>
                    <a href="<?= url('venta', 'index') ?>" 
                    class="text-gray-700 hover:text-blue-600 transition-colors">
                        <i class="fas fa-chart-line mr-1"></i>Ventas
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <main>
        <?php echo $content; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <p class="text-gray-300">&copy; <?php echo date('Y'); ?> Hogar Ideal. Sistema de Gestión Inmobiliaria.</p>
                <p class="text-gray-400 text-sm mt-2">Desarrollado con PHP y MVC</p>
            </div>
        </div>
    </footer>
</body>
</html> 