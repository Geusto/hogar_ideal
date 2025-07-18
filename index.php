<?php
// Incluir archivos necesarios
require_once 'config/database.php';
require_once 'includes/functions.php';

// Obtener parámetros de la URL
$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

// Mapeo de controladores
$controllers = [
    'home' => 'HomeController',
    'propiedad' => 'PropiedadController',
    'cliente' => 'ClienteController',
    'agente' => 'AgenteController',
    'venta' => 'VentaController'
];

// Verificar si el controlador existe
if (!isset($controllers[$controller])) {
    http_response_code(404);
    include 'views/errors/404.php';
    exit;
}

// Incluir el controlador
$controllerFile = 'controllers/' . $controllers[$controller] . '.php';
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    
    // Crear instancia del controlador
    $controllerClass = $controllers[$controller];
    $controllerInstance = new $controllerClass();
    
    // Verificar si el método existe
    if (method_exists($controllerInstance, $action)) {
        // Llamar al método con parámetros
        if ($id !== null) {
            $controllerInstance->$action($id);
        } else {
            $controllerInstance->$action();
        }
    } else {
        // Método no encontrado, mostrar error 404
        http_response_code(404);
        include 'views/errors/404.php';
    }
} else {
    // Controlador no encontrado, mostrar página de inicio
    include 'views/errors/404.php';
}
?>
