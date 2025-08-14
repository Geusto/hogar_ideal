<?php
// Incluir archivos necesarios
require_once 'config/database.php';
require_once 'includes/functions.php';

// Función para procesar URLs amigables
function parseUrl($url) {
    $url = trim($url, '/');
    $parts = explode('/', $url);
    
    $controller = $parts[0] ?? 'home';
    $action = $parts[1] ?? 'index';
    $id = $parts[2] ?? null;
    $status = $parts[3] ?? null;
    
    return [$controller, $action, $id, $status];
}

// Obtener parámetros de la URL
$url = $_GET['url'] ?? '';
$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;
$status = $_GET['status'] ?? null;




// Si hay URL amigable, procesarla
if (!empty($url)) {
    [$controller, $action, $id, $status] = parseUrl($url);
}

// Mapeo de controladores
$controllers = [
    'home' => 'HomeController',
    'propiedad' => 'PropiedadController',
    'propiedades' => 'PropiedadController', // Alias para URLs amigables
    'cliente' => 'ClienteController',
    'clientes' => 'ClienteController', // Alias para URLs amigables
    'agente' => 'AgenteController',
    'agentes' => 'AgenteController', // Alias para URLs amigables
    'venta' => 'VentaController',
    'ventas' => 'VentaController' // Alias para URLs amigables
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
        // Si la acción es changeStatus, pasar ambos parámetros
        if ($action === 'changeStatus') {
            $controllerInstance->$action($id, $status);
        } else if ($id !== null) {
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
