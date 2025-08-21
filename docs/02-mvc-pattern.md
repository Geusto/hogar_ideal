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
        $stmt = $this->pdo->query("SELECT p.*, c.nombre_completo as cliente_vendedor, a.nombre_completo as agente_nombre 
        FROM propiedad p 
        LEFT JOIN cliente c ON p.id_cliente_vendedor = c.id_cliente 
        LEFT JOIN agente a ON p.id_agente = a.id_agente 
        ORDER BY p.id_propiedad DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getById($id) {
        $stmt = $this->pdo->prepare("SELECT p.*, c.nombre_completo as cliente_vendedor, a.nombre_completo as agente_nombre 
        FROM propiedad p 
        LEFT JOIN cliente c ON p.id_cliente_vendedor = c.id_cliente 
        LEFT JOIN agente a ON p.id_agente = a.id_agente 
        WHERE p.id_propiedad = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function search($query) {
        $sql = "SELECT p.*, c.nombre_completo as cliente_vendedor, a.nombre_completo as agente_nombre 
                FROM propiedad p 
                LEFT JOIN cliente c ON p.id_cliente_vendedor = c.id_cliente 
                LEFT JOIN agente a ON p.id_agente = a.id_agente 
                WHERE p.direccion LIKE ? OR c.nombre_completo LIKE ? OR a.nombre_completo LIKE ?
                ORDER BY p.id_propiedad DESC";
        
        $searchTerm = "%$query%";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function create($datos) {
        $sql = "INSERT INTO propiedad (tipo, direccion, habitaciones, banos, superficie, precio, estado, portada, id_cliente_vendedor, id_agente) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $datos['tipo'],
            $datos['direccion'],
            $datos['habitaciones'],
            $datos['banos'],
            $datos['superficie'],
            $datos['precio'],
            $datos['estado'],
            $datos['portada'],
            $datos['id_cliente_vendedor'],
            $datos['id_agente']
        ]);
    }
}
```

**Características del Modelo:**
- ✅ No conoce nada sobre la interfaz de usuario
- ✅ Contiene toda la lógica de negocio
- ✅ Maneja la comunicación con la base de datos
- ✅ Valida datos internamente
- ✅ Es reutilizable
- ✅ **Nuevo**: Sistema de búsqueda por texto en múltiples campos

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
        // Verificar si hay filtros aplicados
        $estado = $_GET['estado'] ?? '';
        $tipo_propiedad = $_GET['tipo_propiedad'] ?? '';
        
        if (!empty($estado)) {
            $propiedades = $this->propiedadModel->getByEstado($estado);
        } elseif (!empty($tipo_propiedad)) {
            $propiedades = $this->propiedadModel->getByTipo($tipo_propiedad);
        } else {
            $propiedades = $this->propiedadModel->getAll();
        }
        
        // Obtener fotos de portada para cada propiedad
        foreach ($propiedades as &$propiedad) {
            $fotoPortada = $this->propiedadModel->getFotoPortada($propiedad['id_propiedad']);
            $propiedad['foto_portada'] = $fotoPortada;
        }
        
        include 'views/propiedades/index.php';
    }
    
    public function search() {
        $query = $_GET['q'] ?? '';
        $propiedades = [];
        
        if (!empty($query)) {
            $propiedades = $this->propiedadModel->search($query);
        } else {
            $propiedades = $this->propiedadModel->getAll();
        }
        
        // Obtener fotos de portada para cada propiedad
        foreach ($propiedades as &$propiedad) {
            $fotoPortada = $this->propiedadModel->getFotoPortada($propiedad['id_propiedad']);
            $propiedad['foto_portada'] = $fotoPortada;
        }
        
        include 'views/propiedades/index.php';
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar datos
            $datos = $this->validarDatos($_POST);
            
            if ($datos) {
                // Guardar en el modelo
                if ($this->propiedadModel->create($datos)) {
                    redirect('propiedad', 'index', null, ['msg' => 'Propiedad creada exitosamente']);
                } else {
                    redirect('propiedad', 'create', null, ['error' => 'Error al crear la propiedad']);
                }
            }
        }
        
        // Mostrar formulario
        include 'views/propiedades/create.php';
    }
    
    private function validarDatos($datos) {
        $errores = [];
        
        if (empty($datos['direccion'])) {
            $errores[] = 'La dirección es obligatoria';
        }
        
        if (empty($datos['precio']) || !is_numeric($datos['precio'])) {
            $errores[] = 'El precio debe ser un número válido';
        }
        
        if (empty($datos['superficie']) || !is_numeric($datos['superficie'])) {
            $errores[] = 'La superficie debe ser un número válido';
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
- ✅ **Nuevo**: Sistema de búsqueda y filtros combinados
- ✅ **Nuevo**: Gestión de fotos de portada

### 🖼️ Vista (View)
**Responsabilidad**: Presenta la información al usuario de forma atractiva

```php
<!-- Ejemplo: views/propiedades/index.php -->
<?php $title = 'Propiedades - Hogar Ideal'; ob_start(); ?>

<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Propiedades</h1>
        <div class="flex gap-4">
            <a href="<?= prettyUrl('home', 'index') ?>" 
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Volver
            </a>
            <a href="<?= prettyUrl('propiedad', 'create') ?>" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                <i class="fas fa-plus mr-2"></i>Nueva Propiedad
            </a>
        </div>
    </div>

    <!-- Filtros y búsqueda -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <form method="GET" action="<?= prettyUrl('propiedad', 'search') ?>" class="md:col-span-2">
                <div class="flex">
                    <input type="text" name="q" placeholder="Buscar propiedades..." 
                           value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>"
                           class="flex-1 border border-gray-300 rounded-l-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-r-lg hover:bg-blue-600">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
            
            <select id="filtroEstado" onchange="aplicarFiltro()" 
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Todos los estados</option>
                <option value="disponible" <?php echo (isset($_GET['estado']) && $_GET['estado'] === 'disponible') ? 'selected' : ''; ?>>Disponible</option>
                <option value="vendida" <?php echo (isset($_GET['estado']) && $_GET['estado'] === 'vendida') ? 'selected' : ''; ?>>Vendida</option>
            </select>
            
            <select id="filtroTipo" name="tipo_propiedad" onchange="aplicarFiltro()" 
                    class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Todos los tipos</option>
                <option value="casa" <?php echo (isset($_GET['tipo_propiedad']) && $_GET['tipo_propiedad'] === 'casa') ? 'selected' : ''; ?>>Casa</option>
                <option value="apartamento" <?php echo (isset($_GET['tipo_propiedad']) && $_GET['tipo_propiedad'] === 'apartamento') ? 'selected' : ''; ?>>Apartamento</option>
                <option value="terreno" <?php echo (isset($_GET['tipo_propiedad']) && $_GET['tipo_propiedad'] === 'terreno') ? 'selected' : ''; ?>>Terreno</option>
                <option value="local" <?php echo (isset($_GET['tipo_propiedad']) && $_GET['tipo_propiedad'] === 'local') ? 'selected' : ''; ?>>Local Comercial</option>
            </select>
        </div>
        
        <!-- Botón para limpiar filtros -->
        <?php if (isset($_GET['estado']) || isset($_GET['tipo_propiedad']) || isset($_GET['q'])): ?>
            <div class="mt-4">
                <a href="<?= prettyUrl('propiedad', 'index') ?>" 
                   class="text-blue-500 hover:text-blue-700 text-sm font-medium">
                    <i class="fas fa-times mr-1"></i>Limpiar filtros
                </a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Lista de propiedades -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php if (empty($propiedades)): ?>
            <div class="col-span-full text-center py-12">
                <i class="fas fa-home text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">No hay propiedades</h3>
                <p class="text-gray-500">
                    <?php if (isset($_GET['q']) || isset($_GET['estado']) || isset($_GET['tipo_propiedad'])): ?>
                        No se encontraron propiedades con los filtros aplicados
                    <?php else: ?>
                        Comienza agregando tu primera propiedad
                    <?php endif; ?>
                </p>
            </div>
        <?php else: ?>
            <?php foreach ($propiedades as $propiedad): ?>
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                    <!-- Imagen de portada -->
                    <div class="relative" style="height: 180px; overflow: hidden;">
                        <?php if (!empty($propiedad['foto_portada'])): ?>
                            <img src="<?php echo assetUrl($propiedad['foto_portada']['nombre_archivo']); ?>" 
                                 alt="<?php echo htmlspecialchars($propiedad['foto_portada']['descripcion'] ?: 'Foto de portada'); ?>" 
                                 class="w-full h-full object-cover">
                        <?php else: ?>
                            <img src="<?php echo getDefaultPropertyImage(); ?>" 
                                 alt="Imagen por defecto" 
                                 class="w-full h-full object-contain">
                        <?php endif; ?>
                        
                        <!-- Indicador de estado sobre la imagen -->
                        <div class="absolute top-2 right-2">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                <?php echo $propiedad['estado'] === 'disponible' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'; ?>">
                                <?php echo ucfirst($propiedad['estado']); ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="mb-4">
                            <h3 class="text-xl font-semibold text-gray-800">
                                <?php echo ucfirst($propiedad['tipo']); ?> en <?php echo htmlspecialchars($propiedad['direccion']); ?>
                            </h3>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 mb-4 text-sm text-gray-600">
                            <?php if ($propiedad['habitaciones']): ?>
                                <div><i class="fas fa-bed mr-2"></i><?php echo $propiedad['habitaciones']; ?> hab.</div>
                            <?php endif; ?>
                            <?php if ($propiedad['banos']): ?>
                                <div><i class="fas fa-bath mr-2"></i><?php echo $propiedad['banos']; ?> baños</div>
                            <?php endif; ?>
                            <div><i class="fas fa-ruler-combined mr-2"></i><?php echo $propiedad['superficie']; ?> m²</div>
                            <div><i class="fas fa-user mr-2"></i><?php echo htmlspecialchars($propiedad['cliente_vendedor'] ?? 'N/A'); ?></div>
                        </div>
                        
                        <div class="text-2xl font-bold text-blue-600 mb-4">
                            $<?php echo number_format($propiedad['precio'], 0, ',', '.'); ?>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <div class="flex space-x-2">
                                <a href="<?= prettyUrl('propiedad', 'show', $propiedad['id_propiedad']) ?>" 
                                   class="text-blue-500 hover:text-blue-700">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="<?= prettyUrl('propiedad', 'edit', $propiedad['id_propiedad']) ?>" 
                                   class="text-green-500 hover:text-green-700">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="#" 
                                   class="text-red-500 hover:text-red-700 btn-eliminar-propiedad"
                                   data-url="<?= prettyUrl('propiedad', 'delete', $propiedad['id_propiedad']) ?>"
                                   data-direccion="<?= htmlspecialchars($propiedad['direccion']) ?>"
                                   title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                            <span class="text-sm text-gray-500">Agente: <?php echo htmlspecialchars($propiedad['agente_nombre'] ?? 'N/A'); ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
    function aplicarFiltro() {
        const estado = document.getElementById('filtroEstado').value;
        const tipo = document.getElementById('filtroTipo').value;
        
        let url = '<?= prettyUrl('propiedad', 'index') ?>';
        const params = new URLSearchParams();
        if (estado) { params.set('estado', estado); }
        if (tipo) { params.set('tipo_propiedad', tipo); }
        const qs = params.toString();
        if (qs) { url += '?' + qs; }
        window.location.href = url;
    }
</script>

<?php 
$content = ob_get_clean();
include 'views/layouts/main.php';
?>
```

**Características de la Vista:**
- ✅ Solo se preocupa por la presentación
- ✅ No contiene lógica de negocio
- ✅ Es reutilizable y modular
- ✅ Usa datos pasados por el controlador
- ✅ Maneja la seguridad de salida (htmlspecialchars)
- ✅ **Nuevo**: Sistema de búsqueda y filtros integrado
- ✅ **Nuevo**: Visualización de fotos de portada
- ✅ **Nuevo**: Filtros dinámicos con JavaScript

---

## 🔄 Implementación en el Proyecto

### Estructura de Archivos
```
controllers/
├── HomeController.php      # Dashboard principal
├── PropiedadController.php # Gestión de propiedades con búsqueda
├── ClienteController.php   # Gestión de clientes
├── AgenteController.php    # Gestión de agentes
└── VentaController.php     # Gestión de ventas

models/
├── Propiedad.php          # Lógica de propiedades con búsqueda
├── Cliente.php            # Lógica de clientes
├── Agente.php             # Lógica de agentes
├── Venta.php              # Lógica de ventas
└── FotoPropiedad.php      # Lógica de galería de fotos

views/
├── layouts/
│   ├── main.php           # Layout principal
│   └── modal_confirmacion.php
├── home/
│   └── index.php          # Dashboard
├── propiedades/
│   ├── index.php          # Lista con búsqueda y filtros
│   ├── create.php         # Formulario de creación
│   ├── edit.php           # Formulario de edición con gestión de fotos
│   └── show.php           # Vista detallada con galería
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

// Mapeo de controladores
$controllers = [
    'home' => 'HomeController',
    'propiedad' => 'PropiedadController',
    'cliente' => 'ClienteController',
    'agente' => 'AgenteController',
    'venta' => 'VentaController'
];

// Cargar controlador
if (isset($controllers[$urlParts[0]]) && file_exists("controllers/{$controllers[$urlParts[0]]}.php")) {
    require_once "controllers/{$controllers[$urlParts[0]]}.php";
    $controllerClass = $controllers[$urlParts[0]];
    $controllerInstance = new $controllerClass();
    
    if (method_exists($controllerInstance, $action)) {
        // Ejecutar la acción
        if ($id !== null) {
            $controllerInstance->$action($id);
        } else {
            $controllerInstance->$action();
        }
    } else {
        // Método no encontrado
        http_response_code(404);
        include 'views/errors/404.php';
    }
} else {
    // Controlador no encontrado
    http_response_code(404);
    include 'views/errors/404.php';
}
```

---

## 🔄 Flujo de Datos

### 1. Petición del Usuario
```
Usuario hace clic en "Ver Propiedades" o busca texto
↓
URL: /propiedad o /propiedad/search?q=texto
↓
index.php recibe la petición
```

### 2. Procesamiento del Controlador
```php
// PropiedadController::index() o PropiedadController::search()
public function index() {
    // 1. Verificar filtros aplicados
    $estado = $_GET['estado'] ?? '';
    $tipo_propiedad = $_GET['tipo_propiedad'] ?? '';
    
    // 2. Obtener datos del modelo según filtros
    if (!empty($estado)) {
        $propiedades = $this->propiedadModel->getByEstado($estado);
    } elseif (!empty($tipo_propiedad)) {
        $propiedades = $this->propiedadModel->getByTipo($tipo_propiedad);
    } else {
        $propiedades = $this->propiedadModel->getAll();
    }
    
    // 3. Procesar datos adicionales (fotos de portada)
    foreach ($propiedades as &$propiedad) {
        $fotoPortada = $this->propiedadModel->getFotoPortada($propiedad['id_propiedad']);
        $propiedad['foto_portada'] = $fotoPortada;
    }
    
    // 4. Incluir la vista con los datos
    include 'views/propiedades/index.php';
}

public function search() {
    $query = $_GET['q'] ?? '';
    $propiedades = [];
    
    if (!empty($query)) {
        $propiedades = $this->propiedadModel->search($query);
    } else {
        $propiedades = $this->propiedadModel->getAll();
    }
    
    // Obtener fotos de portada para cada propiedad
    foreach ($propiedades as &$propiedad) {
        $fotoPortada = $this->propiedadModel->getFotoPortada($propiedad['id_propiedad']);
        $propiedad['foto_portada'] = $fotoPortada;
    }
    
    include 'views/propiedades/index.php';
}
```

### 3. Respuesta de la Vista
```php
<!-- La vista recibe $propiedades y los muestra con búsqueda y filtros -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Formulario de búsqueda -->
        <form method="GET" action="<?= prettyUrl('propiedad', 'search') ?>" class="md:col-span-2">
            <div class="flex">
                <input type="text" name="q" placeholder="Buscar propiedades..." 
                       value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>"
                       class="flex-1 border border-gray-300 rounded-l-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-r-lg hover:bg-blue-600">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </form>
        
        <!-- Filtros por estado y tipo -->
        <select id="filtroEstado" onchange="aplicarFiltro()">
            <option value="">Todos los estados</option>
            <option value="disponible">Disponible</option>
            <option value="vendida">Vendida</option>
        </select>
        
        <select id="filtroTipo" onchange="aplicarFiltro()">
            <option value="">Todos los tipos</option>
            <option value="casa">Casa</option>
            <option value="apartamento">Apartamento</option>
            <option value="terreno">Terreno</option>
            <option value="local">Local Comercial</option>
        </select>
    </div>
</div>

<!-- Lista de propiedades con fotos de portada -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($propiedades as $propiedad): ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Imagen de portada -->
            <div class="relative" style="height: 180px; overflow: hidden;">
                <?php if (!empty($propiedad['foto_portada'])): ?>
                    <img src="<?php echo assetUrl($propiedad['foto_portada']['nombre_archivo']); ?>" 
                         alt="<?php echo htmlspecialchars($propiedad['foto_portada']['descripcion'] ?: 'Foto de portada'); ?>" 
                         class="w-full h-full object-cover">
                <?php else: ?>
                    <img src="<?php echo getDefaultPropertyImage(); ?>" 
                         alt="Imagen por defecto" 
                         class="w-full h-full object-contain">
                <?php endif; ?>
            </div>
            
            <div class="p-6">
                <h3 class="text-xl font-semibold text-gray-800">
                    <?php echo ucfirst($propiedad['tipo']); ?> en <?php echo htmlspecialchars($propiedad['direccion']); ?>
                </h3>
                <p class="text-2xl font-bold text-blue-600 mt-2">
                    $<?php echo number_format($propiedad['precio'], 0, ',', '.'); ?>
                </p>
            </div>
        </div>
    <?php endforeach; ?>
</div>
```

### 4. Resultado Final
El usuario ve una página HTML con:
- Formulario de búsqueda por texto
- Filtros por estado y tipo de propiedad
- Lista de propiedades con fotos de portada
- Sistema de filtros dinámicos
- Mensajes cuando no hay resultados

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
// - PropiedadController::search()
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
    
    public function testSearch() {
        $model = new Propiedad();
        $result = $model->search('casa');
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
class PropiedadController {}  // ✅ Correcto
class ClienteController {}     // ✅ Correcto

// Modelos: PascalCase (singular)
class Propiedad {}            // ✅ Correcto
class Cliente {}              // ✅ Correcto

// Vistas: camelCase o kebab-case
views/propiedades/index.php   // ✅ Correcto
views/propiedades/create.php  // ✅ Correcto
```

### 2. Validación en el Controlador
```php
public function create() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $datos = $this->validarDatos($_POST);
        if ($datos) {
            // Procesar datos válidos
            if ($this->propiedadModel->create($datos)) {
                redirect('propiedad', 'index', null, ['msg' => 'Propiedad creada']);
            }
        } else {
            // Mostrar errores
            redirect('propiedad', 'create', null, ['error' => 'Datos inválidos']);
        }
    }
    // Mostrar formulario
}
```

### 3. Escape de Datos en Vistas
```php
// ✅ Correcto
echo htmlspecialchars($propiedad['direccion']);

// ❌ Incorrecto
echo $propiedad['direccion'];
```

### 4. Uso de Funciones Helper
```php
// En lugar de escribir URLs manualmente
<a href="index.php?controller=propiedad&action=create">

// Usar funciones helper
<a href="<?php echo prettyUrl('propiedad', 'create'); ?>">
```

### 5. Manejo de Errores
```php
// ✅ Correcto - Manejar errores
try {
    $resultado = $this->modelo->operacion();
    if ($resultado) {
        redirect('propiedad', 'index', null, ['msg' => 'Éxito']);
    }
} catch (Exception $e) {
    redirect('propiedad', 'index', null, ['error' => $e->getMessage()]);
}
```

### 6. Sistema de Búsqueda
```php
// ✅ Correcto - Búsqueda con validación
public function search() {
    $query = $_GET['q'] ?? '';
    $propiedades = [];
    
    if (!empty($query)) {
        $propiedades = $this->propiedadModel->search($query);
    } else {
        $propiedades = $this->propiedadModel->getAll();
    }
    
    // Procesar datos adicionales
    foreach ($propiedades as &$propiedad) {
        $fotoPortada = $this->propiedadModel->getFotoPortada($propiedad['id_propiedad']);
        $propiedad['foto_portada'] = $fotoPortada;
    }
    
    include 'views/propiedades/index.php';
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
- **Capaz de manejar búsquedas complejas**
- **Eficiente en la gestión de archivos multimedia**

Para más información sobre implementaciones específicas, consulta:
- [Documentación de Entidades](04-entities/)
- [Funciones Helper](05-functions/)
- [Esquema de Base de Datos](03-database-schema.md)
- [Sistema de Galería de Fotos](10-galeria-fotos.md) 