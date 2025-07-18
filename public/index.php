<?php
/**
 * Router MVC - Punto de entrada principal
 */

// Incluir archivos necesarios
require_once '../config/database.php';
require_once '../includes/functions.php';

// Obtener parámetros de la URL
$controller = $_GET['controller'] ?? 'home';
$action = $_GET['action'] ?? 'index';
$id = $_GET['id'] ?? null;

// Mapeo de controladores
$controllers = [
  'home' => 'HomeController',
  'cliente' => 'ClienteController'
];

// Verificar si el controlador existe
if (!isset($controllers[$controller])) {
  $controller = 'home';
}

// Incluir el controlador
$controllerFile = "../controllers/{$controllers[$controller]}.php";
if (file_exists($controllerFile)) {
  require_once $controllerFile;
  
  // Crear instancia del controlador
  $controllerClass = $controllers[$controller];
  $controllerInstance = new $controllerClass($pdo);
  
  // Verificar si el método existe
  if (method_exists($controllerInstance, $action)) {
    // Ejecutar la acción
    if ($id) {
      $controllerInstance->$action($id);
    } else {
      $controllerInstance->$action();
    }
  } else {
    // Método no encontrado
    http_response_code(404);
    include '../views/errors/404.php';
  }
} else {
  // Controlador no encontrado
  http_response_code(404);
  include '../views/errors/404.php';
}
?> 