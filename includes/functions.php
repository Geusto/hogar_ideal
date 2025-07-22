<?php
/**
 * Funciones útiles para la aplicación Hogar Ideal
 * Este archivo contiene funciones que se usarán en toda la aplicación
 */

/**
 * Función para limpiar y validar datos de entrada
 * Previene ataques XSS y otros problemas de seguridad
 */
function limpiarDatos($datos) {
  $datos = trim($datos);                    // Elimina espacios al inicio y final
  $datos = stripslashes($datos);            // Elimina barras invertidas
  $datos = htmlspecialchars($datos);        // Convierte caracteres especiales en entidades HTML
return $datos;
}

/**
 * Función para mostrar mensajes de éxito o error con Tailwind
 */
function mostrarMensaje($mensaje, $tipo = 'info') {
  $icon = '';
  $bgColor = 'bg-blue-100';
  $textColor = 'text-blue-800';
  $borderColor = 'border-blue-500';

  switch($tipo) {
    case 'exito':
      $icon = '<svg class="w-6 h-6 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';
      $bgColor = 'bg-green-100';
      $textColor = 'text-green-700';
      $borderColor = 'border-green-500';
      break;
    case 'error':
      $icon = '<svg class="w-6 h-6 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>';
      $bgColor = 'bg-red-100';
      $textColor = 'text-red-700';
      $borderColor = 'border-red-500';
      break;
    case 'advertencia':
      $icon = '<svg class="w-6 h-6 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01" /></svg>';
      $bgColor = 'bg-yellow-100';
      $textColor = 'text-yellow-700';
      $borderColor = 'border-yellow-500';
      break;
    default:
      $icon = '<svg class="w-6 h-6 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01" /></svg>';
      $bgColor = 'bg-blue-100';
      $textColor = 'text-blue-700';
      $borderColor = 'border-blue-500';
      break;
  }
  return "<div class='flex items-center $bgColor $borderColor border-l-4 $textColor p-4 mb-4 shadow-md' role='alert' id='alert-msg'>$icon<span class='font-semibold'>" . htmlspecialchars($mensaje) . "</span></div>
  <script>setTimeout(() => { const alert = document.getElementById('alert-msg'); if(alert) alert.style.display = 'none'; }, 4000);</script>";
}

/**
 * Función para formatear precios
 */
function formatearPrecio($precio) {
  return '$' . number_format($precio, 0, ',', '.');
}

/**
 * Función para obtener el tipo de propiedad en texto
 */
function obtenerTipoPropiedad($tipo) {
  $tipos = [
    'casa' => 'Casa',
    'apartamento' => 'Apartamento',
    'terreno' => 'Terreno',
    'local' => 'Local Comercial'
  ];
  
  return isset($tipos[$tipo]) ? $tipos[$tipo] : $tipo;
}

/**
 * Función para obtener el estado de la propiedad en texto
 */
function obtenerEstadoPropiedad($estado) {
  $estados = [
    'disponible' => 'Disponible',
    'vendida' => 'Vendida'
  ];
  
  return isset($estados[$estado]) ? $estados[$estado] : $estado;
}

/**
 * Función para obtener el tipo de cliente en texto
 */
function obtenerTipoCliente($tipo) {
  $tipos = [
    'Comprador' => 'Comprador',
    'Vendedor' => 'Vendedor',
    'Ambos' => 'Comprador y Vendedor'
  ];
  
  return isset($tipos[$tipo]) ? $tipos[$tipo] : $tipo;
}

/**
 * Función para obtener el estado de visita en texto
 */
function obtenerEstadoVisita($estado) {
  $estados = [
    'programada' => 'Programada',
    'realizada' => 'Realizada',
    'cancelada' => 'Cancelada'
  ];
  
  return isset($estados[$estado]) ? $estados[$estado] : $estado;
}

/**
 * Función para validar email
 */
function validarEmail($email) {
  return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Función para validar teléfono (formato básico)
 */
function validarTelefono($telefono) {
  // Eliminar espacios y caracteres especiales
  $telefono = preg_replace('/[^0-9]/', '', $telefono);
  return strlen($telefono) >= 7 && strlen($telefono) <= 15;
}

// Funciones helper para el sistema

/**
 * Genera una URL para el sistema
 * @param string $controller Nombre del controlador
 * @param string $action Nombre de la acción
 * @param int|null $id ID del elemento (opcional)
 * @return string URL generada
 */
function url($controller, $action = 'index', $id = null) {
    $url = "index.php?controller={$controller}&action={$action}";
    if ($id !== null) {
        $url .= "&id={$id}";
    }
    return $url;
}

/**
 * Genera una URL amigable (requiere .htaccess configurado)
 * @param string $controller Nombre del controlador
 * @param string $action Nombre de la acción
 * @param int|null $id ID del elemento (opcional)
 * @return string URL amigable generada
 */
function prettyUrl($controller, $action = 'index', $id = null) {
    $url = $controller;
    if ($action !== 'index') {
        $url .= "/{$action}";
    }
    if ($id !== null) {
        $url .= "/{$id}";
    }
    return $url;
}

/**
 * Redirige a una URL específica
 * @param string $controller Nombre del controlador
 * @param string $action Nombre de la acción
 * @param int|null $id ID del elemento (opcional)
 */
function redirect($controller, $action = 'index', $id = null, $params = []) {
    $url = url($controller, $action, $id);
    // Si hay parámetros adicionales, agrégalos como query string
    if (!empty($params) && is_array($params)) {
        $query = http_build_query($params);
        $url .= (strpos($url, '?') === false ? '?' : '&') . $query;
    }
    header("Location: {$url}");
    exit;
}

/**
 * Obtiene el valor de un parámetro GET con valor por defecto
 * @param string $key Clave del parámetro
 * @param mixed $default Valor por defecto
 * @return mixed Valor del parámetro
 */
function getParam($key, $default = '') {
    return $_GET[$key] ?? $default;
}

/**
 * Obtiene el valor de un parámetro POST con valor por defecto
 * @param string $key Clave del parámetro
 * @param mixed $default Valor por defecto
 * @return mixed Valor del parámetro
 */
function postParam($key, $default = '') {
    return $_POST[$key] ?? $default;
}

/**
 * Verifica si la petición es POST
 * @return bool True si es POST
 */
function isPost() {
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/**
 * Verifica si la petición es GET
 * @return bool True si es GET
 */
function isGet() {
    return $_SERVER['REQUEST_METHOD'] === 'GET';
}

/**
 * Escapa HTML para prevenir XSS
 * @param string $text Texto a escapar
 * @return string Texto escapado
 */
function e($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

/**
 * Formatea un precio con formato de moneda
 * @param float $price Precio a formatear
 * @return string Precio formateado
 */
function formatPrice($price) {
    return '$' . number_format($price, 2, ',', '.');
}

/**
 * Formatea una fecha
 * @param string $date Fecha a formatear
 * @param string $format Formato deseado
 * @return string Fecha formateada
 */
function formatDate($date, $format = 'd/m/Y') {
    return date($format, strtotime($date));
}
?> 