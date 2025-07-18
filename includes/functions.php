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
  $bgColor = 'bg-blue-100';
  $textColor = 'text-blue-800';
  $borderColor = 'border-blue-200';
  
  switch($tipo) {
    case 'exito':
      $bgColor = 'bg-green-100';
      $textColor = 'text-green-800';
      $borderColor = 'border-green-200';
      break;
    case 'error':
      $bgColor = 'bg-red-100';
      $textColor = 'text-red-800';
      $borderColor = 'border-red-200';
      break;
    case 'advertencia':
      $bgColor = 'bg-yellow-100';
      $textColor = 'text-yellow-800';
      $borderColor = 'border-yellow-200';
      break;
  }
  
  return "<div class='$bgColor $textColor $borderColor border px-4 py-3 rounded relative mb-4' role='alert'>
    <span class='block sm:inline'>$mensaje</span>
            <button type='button' class='absolute top-0 bottom-0 right-0 px-4 py-3' onclick='this.parentElement.remove()'>
                  <i class='fas fa-times'></i>
              </button>
          </div>";
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
?> 