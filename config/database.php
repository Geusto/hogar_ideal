<?php
/**
 * Configuración de la Base de Datos
 * Este archivo contiene los datos de conexión a la base de datos MySQL
 */

// Datos de conexión a la base de datos
$host = 'localhost';        // Dirección del servidor de base de datos
$dbname = 'hogar_ideal';    // Nombre de tu base de datos
$username = 'root';         // Usuario de la base de datos
$password = '';             // Contraseña (vacía por defecto en XAMPP/Laragon)

// Intentar conectar a la base de datos
try {
  // Crear conexión usando PDO (más seguro que mysqli)
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
  
  // Configurar PDO para mostrar errores (solo en desarrollo)
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  
  // Mensaje de éxito (opcional, puedes comentarlo después)
  // echo "Conexión exitosa a la base de datos";
  
} catch(PDOException $e) {
  // Si hay error, mostrar mensaje
  die("Error de conexión: " . $e->getMessage());
}
?> 