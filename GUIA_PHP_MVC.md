# Guía Completa de PHP y MVC

## 📚 Índice
1. [Conceptos Básicos de PHP](#conceptos-básicos-de-php)
2. [Patrón MVC](#patrón-mvc)
3. [Herramientas Necesarias](#herramientas-necesarias)
4. [Buenas Prácticas](#buenas-prácticas)
5. [Ejemplos Prácticos](#ejemplos-prácticos)
6. [Recursos Adicionales](#recursos-adicionales)

---

## 🐘 Conceptos Básicos de PHP

### Variables y Tipos de Datos
```php
// Variables
$nombre = "Juan";
$edad = 25;
$precio = 19.99;
$activo = true;

// Arrays
$colores = ["rojo", "verde", "azul"];
$persona = [
  "nombre" => "María",
  "edad" => 30
];

// Constantes
define("PI", 3.1416);
const VERSION = "1.0";
```

### Estructuras de Control
```php
// Condicionales
if ($edad >= 18) {
  echo "Eres mayor de edad";
} elseif ($edad >= 16) {
  echo "Eres adolescente";
} else {
  echo "Eres menor de edad";
}

// Switch
switch ($dia) {
  case "lunes":
    echo "Inicio de semana";
    break;
  case "viernes":
    echo "¡Fin de semana!";
    break;
  default:
    echo "Día normal";
}

// Bucles
for ($i = 0; $i < 5; $i++) {
  echo $i . "<br>";
}

foreach ($colores as $color) {
  echo $color . "<br>";
}

while ($contador < 10) {
  echo $contador;
  $contador++;
}
```

### Funciones
```php
// Función básica
function saludar($nombre) {
    return "Hola, " . $nombre;
}

// Función con valor por defecto
function mostrarInfo($nombre, $edad = 18) {
  return "Nombre: $nombre, Edad: $edad";
}

// Función anónima (closure)
$multiplicar = function($a, $b) {
  return $a * $b;
};
```

### Clases y Objetos
```php
class Usuario {
    // Propiedades
    private $nombre;
    private $email;
    
    // Constructor
    public function __construct($nombre, $email) {
      $this->nombre = $nombre;
      $this->email = $email;
    }
    
    // Métodos
    public function getNombre() {
      return $this->nombre;
    }
    
    public function setNombre($nombre) {
      $this->nombre = $nombre;
    }
}

// Crear instancia
$usuario = new Usuario("Juan", "juan@email.com");
```

### Manejo de Errores
```php
// Try-catch
try {
  $resultado = 10 / 0;
} catch (Exception $e) {
  echo "Error: " . $e->getMessage();
}

// Verificar si existe
if (isset($variable)) {
  echo "La variable existe";
}

// Operador de fusión null (??)
$nombre = $_GET['nombre'] ?? 'Anónimo';
```

---

## 🏗️ Patrón MVC

### ¿Qué es MVC?
**MVC** (Model-View-Controller) es un patrón arquitectónico que separa una aplicación en tres componentes principales:

### 1. Modelo (Model)
- **Responsabilidad**: Lógica de negocio y acceso a datos
- **Ubicación**: `models/`
- **Ejemplo**:
```php
class Propiedad {
  private $pdo;
  
  public function __construct() {
    global $pdo;
    $this->pdo = $pdo;
  }
  
  public function getAll() {
    $stmt = $this->pdo->query("SELECT * FROM propiedades");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
  
  public function create($data) {
    $sql = "INSERT INTO propiedades (titulo, precio) VALUES (?, ?)";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([$data['titulo'], $data['precio']]);
  }
}
```

### 2. Vista (View)
- **Responsabilidad**: Presentación de datos al usuario
- **Ubicación**: `views/`
- **Ejemplo**:
```php
<!-- views/propiedades/index.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Propiedades</title>
</head>
<body>
  <h1>Lista de Propiedades</h1>
  <?php foreach ($propiedades as $propiedad): ?>
    <div>
      <h3><?php echo $propiedad['titulo']; ?></h3>
      <p>Precio: $<?php echo $propiedad['precio']; ?></p>
    </div>
  <?php endforeach; ?>
</body>
</html>
```

### 3. Controlador (Controller)
- **Responsabilidad**: Recibe peticiones y coordina Modelo y Vista
- **Ubicación**: `controllers/`
- **Ejemplo**:
```php
class PropiedadController {
  private $propiedadModel;
    
  public function __construct() {
      $this->propiedadModel = new Propiedad();
  }
    
  public function index() {
    $propiedades = $this->propiedadModel->getAll();
    include 'views/propiedades/index.php';
  }
    
  public function create() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $data = [
        'titulo' => $_POST['titulo'],
        'precio' => $_POST['precio']
      ];
            
      if ($this->propiedadModel->create($data)) {
        header('Location: index.php?controller=propiedad&action=index');
      }
    }
    include 'views/propiedades/create.php';
  }
}
```

### Flujo MVC
```
1. Usuario hace petición → index.php
2. index.php identifica controlador y acción
3. Controlador recibe petición
4. Controlador pide datos al Modelo
5. Modelo devuelve datos
6. Controlador pasa datos a la Vista
7. Vista renderiza HTML
8. Usuario ve resultado
```

---

## 🛠️ Herramientas Necesarias

### 1. Servidor Web Local
- **XAMPP** (Windows, Linux, macOS)
- **Laragon** (Windows)
- **MAMP** (macOS, Windows)
- **WAMP** (Windows)

### 2. Editor de Código
- **Visual Studio Code** (Recomendado)
- **Sublime Text**
- **PHPStorm** (Pago, muy completo)
- **Notepad++**

### 3. Extensiones Recomendadas para VS Code
- PHP IntelliSense
- PHP Debug
- PHP Extension Pack
- Auto Rename Tag
- Bracket Pair Colorizer
- Live Server

### 4. Herramientas de Desarrollo
- **Composer** (Gestor de dependencias)
- **Git** (Control de versiones)
- **phpMyAdmin** (Gestión de base de datos)

### 5. Configuración Básica
```php
// config/database.php
<?php
$host = 'localhost';
$dbname = 'tu_base_de_datos';
$username = 'root';
$password = '';

try {
  $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
  die("Error de conexión: " . $e->getMessage());
}
?>
```

---

## 🚨 Sistema de Mensajes de Alerta (Toasts)

### ¿Cómo funciona?
- El controlador redirige tras una acción (crear, editar, eliminar) usando la función `redirect`, pasando los parámetros `msg` (mensaje) y `tipo` (tipo de alerta: exito, error, advertencia, info).
- En la vista, se llama a la función `mostrarMensaje` para mostrar el mensaje como un toast animado en la parte inferior derecha.
- El parámetro `tipo` es **solo para el mensaje de alerta**. Para filtros de búsqueda, usa `tipo_propiedad`.

### Ejemplo en el controlador:
```php
redirect('propiedad', 'index', null, [
  'msg' => 'Propiedad actualizada exitosamente.',
  'tipo' => 'exito'
]);
```

### Ejemplo en la vista:
```php
if (isset($_GET['msg'])) {
    echo mostrarMensaje($_GET['msg'], $_GET['tipo'] ?? 'info');
}
```

### Parámetros de filtro
- Para filtrar por tipo de propiedad, usa `tipo_propiedad` en la URL y en los formularios:
```php
<select name="tipo_propiedad"> ... </select>
```
- Así evitas conflicto con el parámetro `tipo` de los mensajes.

### Ventajas
- Mensajes visuales modernos y consistentes (toast con icono FontAwesome y color).
- Código centralizado y fácil de mantener.
- Sin conflictos entre filtros y mensajes.

---

## ✅ Buenas Prácticas

### 1. Nomenclatura
```php
// Clases: PascalCase
class PropiedadController {}

// Métodos y variables: camelCase
public function obtenerPropiedades() {}
$nombreUsuario = "Juan";

// Constantes: UPPER_CASE
define("MAX_USUARIOS", 100);

// Archivos: snake_case
mi_archivo.php
mi_clase.php
```

### 2. Estructura de Archivos
```
proyecto/
├── config/
│   └── database.php
├── controllers/
│   ├── HomeController.php
│   └── PropiedadController.php
├── models/
│   ├── Propiedad.php
│   └── Usuario.php
├── views/
│   ├── home/
│   │   └── index.php
│   └── propiedades/
│       ├── index.php
│       └── create.php
├── includes/
│   └── functions.php
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
└── index.php
```

### 3. Seguridad
```php
// Validar entrada de datos
$nombre = filter_input(INPUT_POST, 'nombre', FILTER_SANITIZE_STRING);

// Usar prepared statements
$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id]);

// Escapar salida
echo htmlspecialchars($dato);

// Verificar permisos
if (!isset($_SESSION['usuario_id'])) {
  header('Location: login.php');
  exit;
}
```

### 4. Manejo de Errores
```php
// Configurar reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Logs personalizados
function logError($mensaje) {
  error_log(date('Y-m-d H:i:s') . " - " . $mensaje . "\n", 3, "logs/error.log");
}
```

### 5. Sintaxis Alternativa en Vistas
Cuando mezclas HTML y PHP en archivos de vista, es recomendable usar la **sintaxis alternativa** para mayor legibilidad:

```php
<!-- Sintaxis alternativa (RECOMENDADA para vistas) -->
<?php foreach ($items as $item): ?>
    <div class="item">
        <h3><?php echo htmlspecialchars($item['nombre']); ?></h3>
        <p><?php echo htmlspecialchars($item['descripcion']); ?></p>
    </div>
<?php endforeach; ?>

<!-- Sintaxis tradicional (con llaves) -->
<?php foreach ($items as $item) { ?>
    <div class="item">
        <h3><?php echo htmlspecialchars($item['nombre']); ?></h3>
        <p><?php echo htmlspecialchars($item['descripcion']); ?></p>
    </div>
<?php } ?>
```

**¿Por qué usar sintaxis alternativa?**
- **Mejor legibilidad**: Es más fácil distinguir HTML de PHP
- **Menos errores**: No hay confusión con las llaves `{}`
- **Estándar en vistas**: Es la forma recomendada para archivos que mezclan HTML y PHP

**Otras estructuras con sintaxis alternativa:**
```php
// IF
<?php if ($condicion): ?>
    <div>Contenido</div>
<?php endif; ?>

// FOR
<?php for ($i = 0; $i < 10; $i++): ?>
    <div>Item <?php echo $i; ?></div>
<?php endfor; ?>

// WHILE
<?php while ($condicion): ?>
    <div>Contenido</div>
<?php endwhile; ?>
```

---

## 📝 Ejemplos Prácticos

### 1. CRUD Básico
```php
// Modelo
class Usuario {
  private $pdo;
    
  public function __construct() {
    global $pdo;
    $this->pdo = $pdo;
  }
    
  public function getAll() {
    $stmt = $this->pdo->query("SELECT * FROM usuarios");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
    
  public function getById($id) {
    $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
  }
    
  public function create($data) {
    $sql = "INSERT INTO usuarios (nombre, email) VALUES (?, ?)";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([$data['nombre'], $data['email']]);
  }
    
  public function update($id, $data) {
    $sql = "UPDATE usuarios SET nombre = ?, email = ? WHERE id = ?";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([$data['nombre'], $data['email'], $id]);
  }
    
  public function delete($id) {
    if ($this->tieneVentasAsociadas($id)) {
        return 'venta'; // No eliminar, tiene ventas asociadas
    }
    $stmt = $this->pdo->prepare("DELETE FROM propiedad WHERE id_propiedad = ?");
    $stmt->execute([$id]);
    return 'ok';
  }
}
```

### 2. Controlador Completo
```php
class UsuarioController {
  private $usuarioModel;
    
  public function __construct() {
    $this->usuarioModel = new Usuario();
  }
    
  // Listar usuarios
  public function index() {
    $usuarios = $this->usuarioModel->getAll();
    include 'views/usuarios/index.php';
  }
    
  // Mostrar formulario de creación
  public function create() {
    include 'views/usuarios/create.php';
  }
    
  // Guardar nuevo usuario
  public function store() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $data = [
        'nombre' => $_POST['nombre'] ?? '',
        'email' => $_POST['email'] ?? ''
      ];
        
      if ($this->usuarioModel->create($data)) {
        header('Location: index.php?controller=usuario&action=index&success=1');
      } else {
        header('Location: index.php?controller=usuario&action=create&error=1');
      }
      exit;
    }
  }
    
  // Mostrar formulario de edición
  public function edit($id) {
    $usuario = $this->usuarioModel->getById($id);
    if (!$usuario) {
      header('Location: index.php?controller=usuario&action=index&error=not_found');
      exit;
    }
    include 'views/usuarios/edit.php';
  }
    
  // Actualizar usuario
  public function update($id) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $data = [
        'nombre' => $_POST['nombre'] ?? '',
        'email' => $_POST['email'] ?? ''
      ];
        
      if ($this->usuarioModel->update($id, $data)) {
        header('Location: index.php?controller=usuario&action=index&success=2');
      } else {
        header('Location: index.php?controller=usuario&action=edit&id=' . $id . '&error=1');
      }
      exit;
    }
  }
    
  // Eliminar usuario
  public function delete($id) {
    if ($this->usuarioModel->delete($id)) {
      header('Location: index.php?controller=usuario&action=index&success=3');
    } else {
      header('Location: index.php?controller=usuario&action=index&error=delete_failed');
    }
    exit;
  }
}
```

### 3. Vista con Bootstrap
```php
<!-- views/usuarios/index.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Usuarios</h1>
            <a href="index.php?controller=usuario&action=create" class="btn btn-primary">
                Nuevo Usuario
            </a>
        </div>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php
                switch($_GET['success']) {
                    case '1': echo 'Usuario creado exitosamente'; break;
                    case '2': echo 'Usuario actualizado exitosamente'; break;
                    case '3': echo 'Usuario eliminado exitosamente'; break;
                }
                ?>
            </div>
        <?php endif; ?>
        
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><?php echo $usuario['id']; ?></td>
                        <td><?php echo htmlspecialchars($usuario['nombre']); ?></td>
                        <td><?php echo htmlspecialchars($usuario['email']); ?></td>
                        <td>
                            <a href="index.php?controller=usuario&action=edit&id=<?php echo $usuario['id']; ?>" 
                               class="btn btn-sm btn-warning">Editar</a>
                            <a href="index.php?controller=usuario&action=delete&id=<?php echo $usuario['id']; ?>" 
                               class="btn btn-sm btn-danger" 
                               onclick="return confirm('¿Estás seguro?')">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

---

## 📚 Recursos Adicionales

### Documentación Oficial
- [PHP.net](https://www.php.net/manual/es/)
- [PDO](https://www.php.net/manual/es/book.pdo.php)
- [MySQL](https://dev.mysql.com/doc/)

### Tutoriales Recomendados
- [W3Schools PHP](https://www.w3schools.com/php/)
- [PHP Tutorial](https://www.phptutorial.net/)
- [Laracasts PHP](https://laracasts.com/series/php-for-beginners)

### Herramientas de Desarrollo
- [Composer](https://getcomposer.org/)
- [Git](https://git-scm.com/)
- [VS Code](https://code.visualstudio.com/)

### Frameworks para el Futuro
- [Laravel](https://laravel.com/)
- [Symfony](https://symfony.com/)
- [CodeIgniter](https://codeigniter.com/)

---

## 🎯 Consejos Finales

1. **Practica mucho**: Crea proyectos pequeños para afianzar conceptos
2. **Lee código de otros**: GitHub es tu amigo
3. **Mantén tu código organizado**: Usa comentarios y nombres descriptivos
4. **Aprende Git**: Es fundamental para cualquier proyecto
5. **Sé paciente**: PHP tiene una curva de aprendizaje, pero es muy gratificante
6. **Únete a comunidades**: Stack Overflow, Reddit, Discord

---

*¡Recuerda que la práctica es la mejor manera de aprender! 🚀* 

---

## 🔗 Lógica de Negocio: Relaciones y Eliminación de Entidades

### Eliminación de Propiedades
- **No se permite eliminar una propiedad si tiene ventas asociadas.**
- Esto protege el historial de transacciones y evita la pérdida de información importante.
- Antes de eliminar una propiedad, el sistema ejecuta la consulta:

```sql
SELECT COUNT(*) as total FROM venta WHERE id_propiedad = ?
```
- Si el resultado es mayor que 0, la propiedad no se elimina y se muestra un mensaje de error al usuario.
- Si no tiene ventas asociadas, la propiedad se elimina normalmente.

### Ejemplo de lógica en el modelo:
```php
public function delete($id) {
    if ($this->tieneVentasAsociadas($id)) {
        return 'venta'; // No eliminar, tiene ventas asociadas
    }
    $stmt = $this->pdo->prepare("DELETE FROM propiedad WHERE id_propiedad = ?");
    $stmt->execute([$id]);
    return 'ok';
}
```

### Eliminación de Agentes
- **No se permite eliminar un agente si tiene propiedades asignadas.**
- Si el agente tiene propiedades, se marca como inactivo (`activo = 0`) en vez de eliminarlo físicamente.
- Esto protege la integridad de las relaciones y permite mantener la trazabilidad de las operaciones.
- Solo se elimina físicamente un agente si no tiene ninguna propiedad asignada.

#### Ejemplo de lógica en el modelo:
```php
public function eliminarOInactivar($id) {
    // Verificar si el agente tiene propiedades asociadas
    $stmt = $this->pdo->prepare("SELECT COUNT(*) as total FROM propiedad WHERE id_agente = ?");
    $stmt->execute([$id]);
    $total = $stmt->fetch()['total'];
    if ($total > 0) {
        // Marcar como inactivo
        $stmt = $this->pdo->prepare("UPDATE agente SET activo = 0 WHERE id_agente = ?");
        $stmt->execute([$id]);
        return 'inactivado';
    } else {
        // Eliminar físicamente
        $stmt = $this->pdo->prepare("DELETE FROM agente WHERE id_agente = ?");
        $stmt->execute([$id]);
        return 'eliminado';
    }
}
```

#### Razón de negocio
- Así se evita perder la relación entre propiedades y agentes históricos.
- Permite reactivar agentes en el futuro si es necesario.

--- 