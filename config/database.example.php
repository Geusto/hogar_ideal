<?php
/**
 * Configuración de Base de Datos - Ejemplo
 * 
 * Copia este archivo como 'database.php' y configura tus credenciales
 * 
 * IMPORTANTE: Nunca subas el archivo database.php real al repositorio
 * ya que contiene credenciales sensibles.
 */

// Configuración de conexión a la base de datos
$host = 'localhost';           // Servidor de base de datos
$dbname = 'hogar_ideal';       // Nombre de la base de datos
$username = 'tu_usuario';      // Usuario de la base de datos
$password = 'tu_password';     // Contraseña de la base de datos

// Configuración adicional
$charset = 'utf8mb4';         // Charset de la base de datos
$port = 3306;                 // Puerto de MySQL (por defecto 3306)

// Opciones de PDO
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
];

// DSN (Data Source Name)
$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset;port=$port";

try {
    // Crear conexión PDO
    $pdo = new PDO($dsn, $username, $password, $options);
    
    // Opcional: Verificar conexión
    // echo "Conexión exitosa a la base de datos";
    
} catch (PDOException $e) {
    // En producción, no mostrar errores de base de datos al usuario
    die('Error de conexión a la base de datos. Contacta al administrador.');
    
    // Para desarrollo, puedes usar esto:
    // die('Error de conexión: ' . $e->getMessage());
}
?> 