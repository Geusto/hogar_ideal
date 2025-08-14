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
      $icon = '<i class="fas fa-check-circle mr-2 text-green-500"></i>';
      $bgColor = 'bg-green-100';
      $textColor = 'text-green-700';
      $borderColor = 'border-green-500';
      break;
    case 'error':
      $icon = '<i class="fas fa-times-circle mr-2 text-red-500"></i>';
      $bgColor = 'bg-red-100';
      $textColor = 'text-red-700';
      $borderColor = 'border-red-500';
      break;
    case 'advertencia':
      $icon = '<i class="fas fa-exclamation-triangle mr-2 text-yellow-500"></i>';
      $bgColor = 'bg-yellow-100';
      $textColor = 'text-yellow-700';
      $borderColor = 'border-yellow-500';
      break;
    default:
      $icon = '<i class="fas fa-info-circle mr-2 text-blue-500"></i>';
      $bgColor = 'bg-blue-100';
      $textColor = 'text-blue-700';
      $borderColor = 'border-blue-500';
      break;
  }

  return "
  <div id='toast-msg' class='fixed bottom-6 right-6 z-50 min-w-[250px] max-w-xs $bgColor $borderColor border-l-4 $textColor p-4 mb-4 shadow-lg flex items-center animate-slide-in rounded-lg' role='alert'>
    $icon
    <span class='font-semibold flex-1'>" . htmlspecialchars($mensaje) . "</span>
    <button onclick=\"document.getElementById('toast-msg').style.display='none'\" class='ml-4 text-xl font-bold'>&times;</button>
  </div>
  <style>
    @keyframes slide-in {
      from { opacity: 0; transform: translateX(100px);}
      to { opacity: 1; transform: translateX(0);}
    }
    .animate-slide-in { animation: slide-in 0.5s; }
  </style>
  <script>
    setTimeout(() => {
      const toast = document.getElementById('toast-msg');
      if(toast) toast.style.display = 'none';
    }, 4000);
  </script>
  ";
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
    // Obtener la ruta base del proyecto
    $base_path = dirname($_SERVER['SCRIPT_NAME']);
    if ($base_path === '/') {
        $base_path = '';
    }
    
    $url = $controller;
    if ($action !== 'index') {
        $url .= "/{$action}";
    }
    if ($id !== null) {
        $url .= "/{$id}";
    }
    
    return $base_path . '/' . $url;
}

/**
 * Genera una URL absoluta para archivos estáticos
 * @param string $file_path Ruta del archivo (ej: uploads/imagen.jpg)
 * @return string URL absoluta del archivo
 */
function assetUrl($file_path) {
    // Obtener la ruta base del proyecto
    $base_path = dirname($_SERVER['SCRIPT_NAME']);
    if ($base_path === '/') {
        $base_path = '';
    }
    
    // Limpiar la ruta del archivo
    $file_path = ltrim($file_path, '/');
    
    return $base_path . '/' . $file_path;
}

/**
 * Redirige a una URL específica
 * @param string $controller Nombre del controlador
 * @param string $action Nombre de la acción
 * @param int|null $id ID del elemento (opcional)
 */
function redirect($controller, $action = 'index', $id = null, $params = []) {
    $url = prettyUrl($controller, $action, $id);
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

/**
 * Obtiene la URL de la imagen por defecto para propiedades sin fotos
 * @return string URL de la imagen por defecto
 */
function getDefaultPropertyImage() {
    // Imagen local por defecto
    return assetUrl('assets/images/default.png');
    
    // Alternativa: usar servicio externo (descomenta si prefieres)
    // return 'https://picsum.photos/400/300?random=1.webp';
}
?> 