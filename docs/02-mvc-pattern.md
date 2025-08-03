# 🏗️ Patrón MVC - Hogar Ideal

## 📋 Tabla de Contenidos

1. [¿Qué es MVC?](#qué-es-mvc)
2. [Componentes del MVC](#componentes-del-mvc)
3. [Implementación en el Proyecto](#implementación-en-el-proyecto)
4. [Flujo de Datos](#flujo-de-datos)
5. [Ventajas del Patrón](#ventajas-del-patrón)
6. [Buenas Prácticas](#buenas-prácticas)

---

## 🎯 ¿Qué es MVC?

**MVC** (Model-View-Controller) es un patrón de arquitectura de software que separa la lógica de una aplicación en tres componentes principales:

- **Modelo (Model)**: Maneja los datos y la lógica de negocio
- **Vista (View)**: Presenta la información al usuario
- **Controlador (Controller)**: Coordina entre el modelo y la vista

### 🎨 Analogía Simple
Imagina un restaurante:
- **Modelo** = La cocina (datos y lógica)
- **Vista** = El comedor (interfaz del usuario)
- **Controlador** = El mesero (coordina pedidos y respuestas)

---

## 🧩 Componentes del MVC

### 📊 Modelo (Model)
**Responsabilidad**: Maneja los datos y la lógica de negocio

```php
// Ejemplo: models/Propiedad.php
class Propiedad {
    private $pdo;
    
    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }
    
    public function getAll() {
        $sql = "SELECT * FROM propiedades ORDER BY fecha_creacion DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $sql = "SELECT * FROM propiedades WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function create($datos) {
        $sql = "INSERT INTO propiedades (titulo, descripcion, precio) VALUES (?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$datos['titulo'], $datos['descripcion'], $datos['precio']]);
    }
}
```

**Características del Modelo:**
- ✅ No conoce nada sobre la interfaz de usuario
- ✅ Contiene toda la lógica de negocio
- ✅ Maneja la comunicación con la base de datos
- ✅ Valida datos internamente
- ✅ Es reutilizable

### 🎮 Controlador (Controller)
**Responsabilidad**: Recibe peticiones, procesa datos y decide qué vista mostrar

```php
// Ejemplo: controllers/PropiedadController.php
class PropiedadController {
    private $propiedadModel;
    
    public function __construct() {
        $this->propiedadModel = new Propiedad();
    }
    
    public function index() {
        // Obtener datos del modelo
        $propiedades = $this->propiedadModel->getAll();
        
        // Pasar datos a la vista
        include 'views/propiedades/index.php';
    }
    
    public function crear() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar datos
            $datos = $this->validarDatos($_POST);
            
            if ($datos) {
                // Guardar en el modelo
                if ($this->propiedadModel->create($datos)) {
                    redirect('propiedades', 'index', null, ['mensaje' => 'Propiedad creada']);
                } else {
                    redirect('propiedades', 'crear', null, ['error' => 'Error al crear']);
                }
            }
        }
        
        // Mostrar formulario
        include 'views/propiedades/create.php';
    }
    
    private function validarDatos($datos) {
        $errores = [];
        
        if (empty($datos['titulo'])) {
            $errores[] = 'El título es obligatorio';
        }
        
        if (empty($datos['precio']) || !is_numeric($datos['precio'])) {
            $errores[] = 'El precio debe ser un número válido';
        }
        
        return empty($errores) ? $datos : false;
    }
}
```

**Características del Controlador:**
- ✅ Recibe peticiones HTTP
- ✅ Valida datos de entrada
- ✅ Coordina con el modelo
- ✅ Decide qué vista mostrar
- ✅ Maneja redirecciones

### 🖼️ Vista (View)
**Responsabilidad**: Presenta la información al usuario de forma atractiva

```php
<!-- Ejemplo: views/propiedades/index.php -->
<?php include 'views/layouts/main.php'; ?>

<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Propiedades</h1>
        <a href="<?php echo prettyUrl('propiedades', 'crear'); ?>" 
           class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Nueva Propiedad
        </a>
    </div>
    
    <!-- Mostrar mensajes -->
    <?php if (isset($_GET['mensaje'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            <?php echo htmlspecialchars($_GET['mensaje']); ?>
        </div>
    <?php endif; ?>
    
    <!-- Lista de propiedades -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($propiedades as $propiedad): ?>
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <img src="<?php echo $propiedad['imagen']; ?>" 
                     alt="<?php echo htmlspecialchars($propiedad['titulo']); ?>"
                     class="w-full h-48 object-cover">
                <div class="p-4">
                    <h3 class="text-xl font-semibold text-gray-800">
                        <?php echo htmlspecialchars($propiedad['titulo']); ?>
                    </h3>
                    <p class="text-gray-600 mt-2">
                        <?php echo htmlspecialchars($propiedad['descripcion']); ?>
                    </p>
                    <p class="text-2xl font-bold text-blue-600 mt-2">
                        $<?php echo number_format($propiedad['precio']); ?>
                    </p>
                    <div class="mt-4 flex space-x-2">
                        <a href="<?php echo prettyUrl('propiedades', 'editar', $propiedad['id']); ?>" 
                           class="bg-yellow-500 hover:bg-yellow-700 text-white px-3 py-1 rounded text-sm">
                            Editar
                        </a>
                        <a href="<?php echo prettyUrl('propiedades', 'ver', $propiedad['id']); ?>" 
                           class="bg-green-500 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                            Ver
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'views/layouts/footer.php'; ?>
```

**Características de la Vista:**
- ✅ Solo se preocupa por la presentación
- ✅ No contiene lógica de negocio
- ✅ Es reutilizable y modular
- ✅ Usa datos pasados por el controlador
- ✅ Maneja la seguridad de salida (htmlspecialchars)

---

## 🔄 Implementación en el Proyecto

### Estructura de Archivos
```
controllers/
├── HomeController.php      # Dashboard principal
├── PropiedadController.php # Gestión de propiedades
├── ClienteController.php   # Gestión de clientes
├── AgenteController.php    # Gestión de agentes
└── VentaController.php     # Gestión de ventas

models/
├── Propiedad.php          # Lógica de propiedades
├── Cliente.php            # Lógica de clientes
├── Agente.php             # Lógica de agentes
└── Venta.php              # Lógica de ventas

views/
├── layouts/
│   ├── main.php           # Layout principal
│   └── modal_confirmacion.php
├── home/
│   └── index.php          # Dashboard
├── propiedades/
│   ├── index.php          # Lista de propiedades
│   ├── create.php         # Formulario de creación
│   ├── edit.php           # Formulario de edición
│   └── show.php           # Vista detallada
└── [otros módulos...]
```

### Enrutador Principal
```php
// index.php - Punto de entrada
$url = $_GET['url'] ?? 'home/index';
$urlParts = explode('/', $url);

$controller = ucfirst($urlParts[0]) . 'Controller';
$action = $urlParts[1] ?? 'index';
$id = $urlParts[2] ?? null;

// Cargar controlador
if (file_exists("controllers/{$controller}.php")) {
    require_once "controllers/{$controller}.php";
    $controllerInstance = new $controller();
    
    if (method_exists($controllerInstance, $action)) {
        $controllerInstance->$action($id);
    } else {
        // Error 404
        include 'views/errors/404.php';
    }
} else {
    // Error 404
    include 'views/errors/404.php';
}
```

---

## 🔄 Flujo de Datos

### 1. Petición del Usuario
```
Usuario hace clic en "Ver Propiedades"
↓
URL: /propiedades
↓
index.php recibe la petición
```

### 2. Procesamiento del Controlador
```php
// PropiedadController::index()
public function index() {
    // 1. Obtener datos del modelo
    $propiedades = $this->propiedadModel->getAll();
    
    // 2. Procesar datos si es necesario
    foreach ($propiedades as &$propiedad) {
        $propiedad['precio_formateado'] = number_format($propiedad['precio']);
    }
    
    // 3. Incluir la vista con los datos
    include 'views/propiedades/index.php';
}
```

### 3. Respuesta de la Vista
```php
<!-- La vista recibe $propiedades y los muestra -->
<?php foreach ($propiedades as $propiedad): ?>
    <div class="propiedad">
        <h3><?php echo htmlspecialchars($propiedad['titulo']); ?></h3>
        <p>$<?php echo $propiedad['precio_formateado']; ?></p>
    </div>
<?php endforeach; ?>
```

### 4. Resultado Final
El usuario ve una página HTML con la lista de propiedades formateada.

---

## ✅ Ventajas del Patrón MVC

### 🎯 Separación de Responsabilidades
- **Modelo**: Solo datos y lógica de negocio
- **Vista**: Solo presentación
- **Controlador**: Solo coordinación

### 🔄 Reutilización de Código
```php
// El mismo modelo se usa en diferentes controladores
$propiedadModel = new Propiedad();
$propiedades = $propiedadModel->getAll();

// Se puede usar en:
// - PropiedadController::index()
// - HomeController::dashboard()
// - VentaController::crear()
```

### 🧪 Facilidad de Testing
```php
// Puedes probar cada componente por separado
class PropiedadTest {
    public function testGetAll() {
        $model = new Propiedad();
        $result = $model->getAll();
        $this->assertIsArray($result);
    }
}
```

### 👥 Trabajo en Equipo
- **Desarrollador A**: Trabaja en modelos
- **Desarrollador B**: Trabaja en vistas
- **Desarrollador C**: Trabaja en controladores

### 🔧 Mantenibilidad
- Cambios en la lógica → Solo afecta al modelo
- Cambios en la interfaz → Solo afecta a las vistas
- Cambios en el flujo → Solo afecta a los controladores

---

## 📋 Buenas Prácticas

### 1. Nomenclatura Consistente
```php
// Controladores: PascalCase + "Controller"
class PropiedadController {}
class ClienteController {}

// Modelos: PascalCase (singular)
class Propiedad {}
class Cliente {}

// Vistas: camelCase o kebab-case
views/propiedades/index.php
views/propiedades/create.php
```

### 2. Validación en el Controlador
```php
public function crear() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $datos = $this->validarDatos($_POST);
        if ($datos) {
            // Procesar datos válidos
        } else {
            // Mostrar errores
        }
    }
    // Mostrar formulario
}
```

### 3. Escape de Datos en Vistas
```php
// ✅ Correcto
echo htmlspecialchars($propiedad['titulo']);

// ❌ Incorrecto
echo $propiedad['titulo'];
```

### 4. Uso de Funciones Helper
```php
// En lugar de escribir URLs manualmente
<a href="index.php?controller=propiedades&action=crear">

// Usar funciones helper
<a href="<?php echo prettyUrl('propiedades', 'crear'); ?>">
```

### 5. Manejo de Errores
```php
try {
    $resultado = $this->modelo->operacion();
    if ($resultado) {
        redirect('controlador', 'accion', null, ['mensaje' => 'Éxito']);
    }
} catch (Exception $e) {
    redirect('controlador', 'accion', null, ['error' => $e->getMessage()]);
}
```

---

## 🎓 Conclusión

El patrón MVC proporciona:

1. **Organización clara** del código
2. **Mantenibilidad** a largo plazo
3. **Escalabilidad** para proyectos grandes
4. **Reutilización** de componentes
5. **Testing** más fácil
6. **Trabajo en equipo** eficiente

En "Hogar Ideal", este patrón permite que el sistema sea:
- Fácil de entender para nuevos desarrolladores
- Sencillo de mantener y actualizar
- Escalable para nuevas funcionalidades
- Robusto y bien estructurado

Para más información sobre implementaciones específicas, consulta:
- [Documentación de Entidades](04-entities/)
- [Funciones Helper](05-functions/)
- [Esquema de Base de Datos](03-database-schema.md) 