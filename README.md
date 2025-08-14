# 🏠 Hogar Ideal - Sistema de Gestión Inmobiliaria

Un sistema completo de gestión inmobiliaria desarrollado en PHP siguiendo el patrón MVC (Model-View-Controller).

## 📋 Tabla de Contenidos

- [Características](#-características)
- [Tecnologías Utilizadas](#-tecnologías-utilizadas)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [Instalación](#-instalación)
- [Configuración](#-configuración)
- [Uso](#-uso)
- [Funcionalidades Implementadas](#-funcionalidades-implementadas)
- [Base de Datos](#-base-de-datos)
- [Patrón MVC](#-patrón-mvc)
- [Contribuir](#-contribuir)

## ✨ Características

- **Gestión completa de propiedades** (CRUD)
- **Sistema de filtros avanzados** por estado y tipo
- **Búsqueda en tiempo real** en propiedades
- **Interfaz moderna y responsive** con Tailwind CSS
- **Patrón MVC** para código organizado y mantenible
- **Validación de formularios** con feedback visual
- **Mensajes de confirmación** para acciones críticas
- **Dashboard con estadísticas** en tiempo real
## ✨ Características

- **Gestión completa de propiedades** (CRUD)
- **Sistema de filtros avanzados** por estado y tipo
- **Búsqueda en tiempo real** en propiedades
- **Interfaz moderna y responsive** con Tailwind CSS
- **Patrón MVC** para código organizado y mantenible
- **Validación de formularios** con feedback visual
- **Mensajes de confirmación** para acciones críticas
- **Dashboard con estadísticas** en tiempo real

->

## ✨ Características

- **Gestión completa de propiedades** (CRUD)
- **Sistema de filtros avanzados** por estado y tipo
- **Búsqueda en tiempo real** en propiedades
- **Interfaz moderna y responsive** con Tailwind CSS
- **Patrón MVC** para código organizado y mantenible
- **Validación de formularios** con feedback visual
- **Mensajes de confirmación** para acciones críticas
- **Dashboard con estadísticas** en tiempo real
- **Sistema de URLs híbrido** (tradicional + amigables)
- **Funciones helper** para generación de URLs y redirecciones
- **Enrutador centralizado** con validación de seguridad
## 🛠️ Tecnologías Utilizadas

- **Backend:** PHP 8.1+
- **Base de Datos:** MySQL 8.0
- **Frontend:** HTML5, CSS3, JavaScript
- **Framework CSS:** Tailwind CSS 2.2.19
- **Iconos:** Font Awesome 6.0.0
- **Patrón:** MVC (Model-View-Controller)
- **Servidor Web:** Apache con mod_rewrite
- **URLs:** Sistema híbrido (tradicional + amigables)

## 📁 Estructura del Proyecto

```
hogar ideal/
├── config/
│   └── database.php              # Configuración de base de datos
├── controllers/
│   ├── HomeController.php         # Controlador del dashboard
│   ├── PropiedadController.php    # Controlador de propiedades
│   ├── ClienteController.php      # Controlador de clientes
│   ├── AgenteController.php       # Controlador de agentes
│   └── VentaController.php        # Controlador de ventas
├── models/
│   ├── Propiedad.php             # Modelo de propiedades
│   ├── Cliente.php               # Modelo de clientes
│   ├── Agente.php                # Modelo de agentes
│   └── Venta.php                 # Modelo de ventas
├── views/
│   ├── layouts/
│   │   ├── header.php            # Header del sitio
│   │   └── footer.php            # Footer del sitio
│   ├── errors/
│   │   └── 404.php               # Página de error 404
│   ├── home/
│   │   └── index.php             # Dashboard principal
│   ├── propiedades/
│   │   ├── index.php             # Lista de propiedades
│   │   ├── create.php            # Formulario de creación
│   │   ├── edit.php              # Formulario de edición
│   │   └── show.php              # Vista detallada
│   ├── clientes/
│   │   └── index.php             # Lista de clientes
│   └── agentes/
│       └── index.php             # Lista de agentes
├── includes/
│   └── functions.php             # Funciones auxiliares y helpers
├── uploads/                      # Carpeta para archivos subidos
├── db/
│   └── hogar_ideal.sql           # Estructura de base de datos
├── .htaccess                     # Configuración de Apache (URLs amigables)
├── index.php                     # Enrutador principal MVC
└── README.md                     # Este archivo
```

## 🚀 Instalación

### Requisitos Previos

- PHP 8.1 o superior
- MySQL 8.0 o superior
- Apache con mod_rewrite habilitado
- Servidor web (Apache/Nginx) o servidor local (XAMPP, Laragon, etc.)

### Pasos de Instalación

1. **Clonar o descargar el proyecto**
   ```bash
   git clone [url-del-repositorio]
   cd hogar-ideal
   ```

2. **Configurar la base de datos**
   - Crear una base de datos llamada `hogar_ideal`
   - Importar el archivo `db/hogar_ideal.sql`

3. **Configurar la conexión**
   - Editar `config/database.php` con tus credenciales

4. **Verificar configuración de Apache**
   - Asegurar que mod_rewrite esté habilitado
   - El archivo `.htaccess` debe estar en la raíz del proyecto

5. **Acceder al sistema**
   - Navegar a `http://localhost/hogar-ideal`
   - Probar URLs amigables: `http://localhost/hogar-ideal/propiedades`

## ⚙️ Configuración

### Base de Datos

**1. Copiar archivo de configuración:**
```bash
cp config/database.example.php config/database.php
```

**2. Editar `config/database.php` con tus credenciales:**
```php
<?php
$host = 'localhost';
$dbname = 'hogar_ideal';
$username = 'tu_usuario';
$password = 'tu_contraseña';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
```

**⚠️ IMPORTANTE:** El archivo `config/database.php` está en `.gitignore` por seguridad. Nunca subas credenciales reales al repositorio.

## 📖 Uso

### 🎯 Sistema de URLs

El sistema soporta **dos tipos de URLs** para máxima flexibilidad:

#### **1. URLs con Parámetros GET (Tradicional)**
```
http://localhost/hogar-ideal/index.php?controller=propiedad&action=index
http://localhost/hogar-ideal/index.php?controller=propiedad&action=create
http://localhost/hogar-ideal/index.php?controller=propiedad&action=edit&id=5
```

#### **2. URLs Amigables (Pretty URLs)**
```
http://localhost/hogar-ideal/propiedades
http://localhost/hogar-ideal/propiedades/crear
http://localhost/hogar-ideal/propiedades/5/editar
http://localhost/hogar-ideal/clientes
http://localhost/hogar-ideal/agentes/3/editar
```

### 🔧 Funciones Helper para URLs

#### **Generación de URLs**
```php
// URL tradicional
url('propiedad', 'index')                    // index.php?controller=propiedad&action=index
url('propiedad', 'create')                   // index.php?controller=propiedad&action=create
url('propiedad', 'edit', 5)                  // index.php?controller=propiedad&action=edit&id=5

// URL amigable (requiere .htaccess)
prettyUrl('propiedad', 'index')              // propiedades
prettyUrl('propiedad', 'create')             // propiedades/crear
prettyUrl('propiedad', 'edit', 5)            // propiedades/5/editar
```

#### **Redirecciones**
```php
// Redirigir a otra página
redirect('propiedad', 'index');
redirect('propiedad', 'create');
redirect('propiedad', 'edit', 5);
```

#### **Obtención de Parámetros**
```php
// Parámetros GET
$controller = getParam('controller', 'home');
$action = getParam('action', 'index');
$id = getParam('id');

// Parámetros POST
$nombre = postParam('nombre', '');
$email = postParam('email', '');
```

### 🌐 Sistema de URLs Amigables

#### **Configuración Requerida**

**1. Archivo `.htaccess`**
```apache
RewriteEngine On

# Redirigir todas las peticiones a index.php
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]

# Permitir acceso a archivos estáticos
RewriteCond %{REQUEST_URI} !\.(css|js|png|jpg|jpeg|gif|ico|pdf)$
```

**2. Procesamiento en `index.php`**
```php
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

// Si hay URL amigable, procesarla
if (!empty($_GET['url'])) {
    [$controller, $action, $id, $status] = parseUrl($_GET['url']);
}
```

#### **Ventajas de las URLs Amigables**

✅ **Mejor SEO** - Los motores de búsqueda las prefieren  
✅ **Más profesionales** - URLs más limpias y atractivas  
✅ **Fáciles de recordar** - Más intuitivas para los usuarios  
✅ **Seguridad mejorada** - No exponen la estructura interna  
✅ **Compatibilidad** - Las URLs tradicionales siguen funcionando  

#### **Ejemplos de URLs**

| Acción | URL Amigable | URL Tradicional |
|--------|--------------|-----------------|
| Dashboard | `/` o `/home` | `index.php` |
| Listar Propiedades | `/propiedad` | `index.php?controller=propiedad&action=index` |
| Crear Propiedad | `/propiedad/create` | `index.php?controller=propiedad&action=create` |
| Editar Propiedad | `/propiedad/edit/123` | `index.php?controller=propiedad&action=edit&id=123` |
| Ver Propiedad | `/propiedad/show/123` | `index.php?controller=propiedad&action=show&id=123` |
| Listar Clientes | `/cliente/viewCliente` | `index.php?controller=cliente&action=viewCliente` |
| Crear Cliente | `/cliente/create` | `index.php?controller=cliente&action=create` |

### 🗺️ Mapeo de Controladores

El sistema utiliza un **mapeo centralizado** de controladores:

```php
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
```

### 📋 Navegación Principal

#### URLs Amigables (Recomendadas)
- **Dashboard:** `/` o `/home` - Panel principal con estadísticas
- **Propiedades:** `/propiedad` o `/propiedades` - Gestión de propiedades
- **Clientes:** `/cliente/viewCliente` - Gestión de clientes
- **Agentes:** `/agente` o `/agentes` - Gestión de agentes

#### URLs Tradicionales (Compatibilidad)
- **Dashboard:** `index.php` - Panel principal con estadísticas
- **Propiedades:** `index.php?controller=propiedad&action=index` - Gestión de propiedades
- **Clientes:** `index.php?controller=cliente&action=index` - Gestión de clientes

#### Ejemplos de Acceso
```bash
# URLs Amigables (Recomendadas)
http://localhost/hogar-ideal/
http://localhost/hogar-ideal/propiedad
http://localhost/hogar-ideal/propiedad/create
http://localhost/hogar-ideal/propiedad/edit/123
http://localhost/hogar-ideal/cliente/viewCliente
http://localhost/hogar-ideal/agente

# URLs Tradicionales (Compatibilidad)
http://localhost/hogar-ideal/index.php?controller=propiedad&action=index
http://localhost/hogar-ideal/index.php?controller=propiedad&action=create
http://localhost/hogar-ideal/index.php?controller=propiedad&action=edit&id=123
```

### 🏠 Gestión de Propiedades

#### Listar Propiedades
- **URL amigable:** `/propiedad` o `/propiedades`
- **URL tradicional:** `index.php?controller=propiedad&action=index`
- **Funcionalidades:**
  - Vista de todas las propiedades
  - Filtros por estado y tipo
  - Búsqueda en tiempo real
  - Acciones rápidas (ver, editar, eliminar)

#### Crear Propiedad
- **URL amigable:** `/propiedad/create`
- **URL tradicional:** `index.php?controller=propiedad&action=create`
- **Campos requeridos:**
  - Tipo de propiedad (casa, apartamento, terreno, local)
  - Dirección
  - Superficie (m²)
  - Precio
  - Estado (disponible, vendida)
  - Cliente vendedor
  - Agente responsable

#### Editar Propiedad
- **URL amigable:** `/propiedad/edit/[ID]`
- **URL tradicional:** `index.php?controller=propiedad&action=edit&id=[ID]`
- **Funcionalidades:**
  - Formulario pre-cargado con datos actuales
  - Validación de campos
  - Actualización en tiempo real

#### Ver Detalles
- **URL amigable:** `/propiedad/show/[ID]`
- **URL tradicional:** `index.php?controller=propiedad&action=show&id=[ID]`
- **Información mostrada:**
  - Características completas
  - Información de relaciones
  - Precio y estado
  - Acciones disponibles

#### Eliminar Propiedad
- **URL:** `index.php?controller=propiedad&action=delete&id=[ID]`
- **Confirmación:** Requiere confirmación antes de eliminar

## 🎯 Funcionalidades Implementadas

### ✅ Sistema de Layouts
- **Layout principal:** `views/layouts/main.php`
- **Output buffering:** Sistema para evitar duplicación de HTML
- **Navegación consistente:** Header y footer unificados
- **Responsive design:** Compatible con móviles y desktop

### ✅ Sistema de Galería de Fotos
- **Galería múltiple:** Reemplaza el sistema anterior de una sola foto de portada
- **Subida múltiple:** Drag & drop para múltiples archivos simultáneos
- **Gestión de portada:** Cambio dinámico de foto de portada
- **Validación avanzada:** Tipos permitidos (jpg, jpeg, png, gif, webp)
- **Carpeta uploads:** Almacenamiento seguro con nombres únicos
- **Interfaz modal:** Gestión centralizada de fotos por propiedad
- **Responsive:** Adaptación automática a diferentes dispositivos

### ✅ Funciones Auxiliares
- **Validación de datos:** Limpieza y sanitización de entrada
- **Mensajes de feedback:** Sistema de alertas con Tailwind CSS
- **Formateo de precios:** Función para mostrar precios formateados
- **Validación de email y teléfono:** Funciones de validación

### ✅ Configuración de Seguridad
- **Archivo .htaccess:** Protección de archivos sensibles
- **Prepared statements:** Prevención de SQL injection
- **Validación de entrada:** Sanitización de datos de usuario
- **Escape de salida:** Prevención de XSS

### ✅ Sistema de URLs y Enrutamiento

#### **Enrutador Principal (`index.php`)**
- **Punto de entrada único:** Todas las peticiones pasan por aquí
- **Mapeo dinámico:** Conecta URLs con controladores
- **Validación de seguridad:** Verifica controladores y métodos permitidos
- **Manejo de errores:** Páginas 404 personalizadas
- **Flexibilidad:** Soporta URLs tradicionales y amigables

#### **Configuración de URLs Amigables (`.htaccess`)**
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```
- **Redirección automática:** Todas las URLs van a `index.php`
- **Protección de archivos:** Permite acceso solo a archivos necesarios
- **URLs limpias:** Elimina parámetros GET de la URL visible

#### **Funciones Helper (`includes/functions.php`)**
- **`url()`:** Genera URLs tradicionales con parámetros
- **`prettyUrl()`:** Genera URLs amigables
- **`redirect()`:** Redirecciones con una sola función
- **`getParam()` / `postParam()`:** Obtención segura de parámetros
- **`isPost()` / `isGet()`:** Verificación del método HTTP
- **`e()`:** Escape HTML para prevenir XSS
- **`formatPrice()` / `formatDate()`:** Formateo de datos

#### **Ventajas del Sistema de URLs**
- **SEO mejorado:** URLs descriptivas y amigables
- **Mantenibilidad:** Cambios centralizados en funciones helper
- **Seguridad:** Validación y sanitización automática
- **Experiencia de usuario:** URLs fáciles de recordar
- **Flexibilidad:** Soporte para ambos tipos de URLs

### ✅ Refactorización MVC Completa

#### **Patrón MVC Mejorado**
- **Modelos completos:** Propiedad, Cliente, Agente, Venta
- **Controladores limpios:** Sin lógica de base de datos directa
- **Separación de responsabilidades:** Lógica de negocio en modelos
- **Reutilización de código:** Métodos compartidos entre controladores

#### **Modelos Implementados**
- **Propiedad:** CRUD completo + filtros + búsqueda + estadísticas
- **Cliente:** CRUD completo + conteos por tipo + búsqueda
- **Agente:** CRUD completo + conteos por especialidad + búsqueda
- **Venta:** CRUD completo + estadísticas temporales + relaciones

#### **HomeController Refactorizado**
- **Uso de modelos:** Todas las estadísticas vienen de modelos
- **Código limpio:** Sin consultas SQL directas
- **Manejo de errores:** Try-catch con valores por defecto
- **Patrón MVC correcto:** Controlador → Modelo → Vista

#### **Mejoras Técnicas Implementadas**
- **Conexión global:** Uso de `global $pdo` en constructores
- **Prepared statements:** Protección contra SQL injection
- **Manejo de errores:** Try-catch en todos los controladores
- **Métodos específicos:** `countByEstado()`, `countByMesActual()`, etc.
- **Relaciones optimizadas:** JOINs en consultas complejas
- **Validación de datos:** Sanitización en modelos

### ✅ CRUD Completo de Propiedades

#### **Crear (Create)**
- Formulario completo con validación
- Campos: tipo, dirección, habitaciones, baños, superficie, precio, estado
- Relaciones: cliente vendedor, agente responsable
- Validación de campos requeridos

#### **Leer (Read)**
- Lista paginada de propiedades
- Vista detallada individual
- Información de relaciones (cliente, agente)
- Estadísticas en tiempo real

#### **Actualizar (Update)**
- Formulario de edición pre-cargado
- Validación de datos
- Actualización de relaciones
- Feedback de éxito/error

#### **Eliminar (Delete)**
- Confirmación antes de eliminar
- Eliminación segura con validación
- Redirección con mensaje de éxito

### ✅ Sistema de Filtros Avanzado

#### **Filtros por Estado**
- Disponible
- Vendida

#### **Filtros por Tipo**
- Casa
- Apartamento
- Terreno
- Local Comercial

#### **Características de Filtros**
- Filtros combinables (estado + tipo)
- Estado persistente (mantiene selección)
- Botón para limpiar filtros
- Búsqueda integrada

### ✅ Búsqueda Inteligente

#### **Campos de Búsqueda**
- Dirección de la propiedad
- Nombre del cliente vendedor
- Nombre del agente responsable

#### **Funcionalidades**
- Búsqueda en tiempo real
- Resultados instantáneos
- Mantiene texto de búsqueda
- Mensaje cuando no hay resultados

### ✅ Interfaz de Usuario

#### **Diseño Responsive**
- Compatible con móviles y desktop
- Grid system adaptativo
- Componentes flexibles

#### **Feedback Visual**
- Mensajes de éxito/error
- Confirmaciones para acciones críticas
- Estados de carga
- Indicadores visuales

#### **Navegación Intuitiva**
- Breadcrumbs
- Botones de acción claros
- Enlaces de navegación
- Dashboard central

## 📨 Sistema de mensajes de feedback

- El sistema utiliza la función `mostrarMensaje` (en `includes/functions.php`) para mostrar mensajes de éxito, error o advertencia en cualquier vista.
- Los mensajes se muestran en la parte inferior derecha y desaparecen automáticamente.
- Se usa en formularios de creación, edición y en cualquier acción que requiera feedback visual para el usuario.

## 🗄️ Base de Datos

### Estructura de Tablas

#### **Tabla: propiedad**
```sql
CREATE TABLE `propiedad` (
  `id_propiedad` int NOT NULL AUTO_INCREMENT,
  `tipo` enum('casa','apartamento','terreno','local') NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `habitaciones` int DEFAULT NULL,
  `banos` int DEFAULT NULL,
  `superficie` decimal(10,2) NOT NULL,
  `precio` decimal(12,2) NOT NULL,
  `estado` enum('disponible','vendida') DEFAULT 'disponible',
  `id_cliente_vendedor` int NOT NULL,
  `id_agente` int NOT NULL,
  PRIMARY KEY (`id_propiedad`)
);
```

#### **Tabla: tipo_documento**
```sql
CREATE TABLE `tipo_documento` (
  `idTipoDocumento` int NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) NOT NULL,
  PRIMARY KEY (`idTipoDocumento`)
);
```
- Relacionada con las tablas `agente` y `cliente` mediante claves foráneas.

#### **Tabla: agente**
```sql
CREATE TABLE `agente` (
  `id_agente` int NOT NULL AUTO_INCREMENT,
  `nombre_completo` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `zona_asignada` varchar(50) NOT NULL,
  `tipo_documento` int DEFAULT NULL,
  `documento` varchar(50) NOT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `imagen_perfil` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_agente`),
  FOREIGN KEY (`tipo_documento`) REFERENCES `tipo_documento`(`idTipoDocumento`)
);
```
- **Validación:** No se permite que dos agentes tengan la misma combinación de `documento` y `tipo_documento`.

#### **Tabla: cliente**
```sql
CREATE TABLE `cliente` (
  `id_cliente` int NOT NULL AUTO_INCREMENT,
  `nombre_completo` varchar(100) NOT NULL,
  `documento` varchar(50) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `tipo` enum('Comprador','Vendedor','Ambos') NOT NULL,
  `idTipoDocumento` int DEFAULT NULL,
  `statusC` int NOT NULL,
  PRIMARY KEY (`id_cliente`),
  FOREIGN KEY (`idTipoDocumento`) REFERENCES `tipo_documento`(`idTipoDocumento`)
);
```

### Relaciones
- **agente.tipo_documento** → **tipo_documento.idTipoDocumento**
- **cliente.idTipoDocumento** → **tipo_documento.idTipoDocumento**

### Formularios
- En los formularios de creación y edición de agentes y clientes, se utiliza un `<select>` para elegir el tipo de documento, mostrando las descripciones de la tabla `tipo_documento`.

### Validaciones
- El sistema valida que no se repita la combinación de documento y tipo de documento en agentes.
- Se valida la unicidad de email y teléfono como antes.

## 🏗️ Patrón MVC

### **Model (Modelo)**
- `models/Propiedad.php` - Lógica de negocio para propiedades
- `models/Cliente.php` - Lógica de negocio para clientes

#### **Funciones del Modelo Propiedad:**
- `getAll()` - Obtener todas las propiedades
- `getById($id)` - Obtener propiedad específica
- `create($data)` - Crear nueva propiedad
- `update($id, $data)` - Actualizar propiedad
- `delete($id)` - Eliminar propiedad
- `getByEstado($estado)` - Filtrar por estado
- `getByTipo($tipo)` - Filtrar por tipo
- `search($query)` - Buscar propiedades
- `getAgentes()` - Obtener lista de agentes
- `getClientesVendedores()` - Obtener clientes vendedores

### **View (Vista)**
- `views/home/index.php` - Dashboard principal
- `views/propiedades/index.php` - Lista de propiedades
- `views/propiedades/create.php` - Formulario de creación
- `views/propiedades/edit.php` - Formulario de edición
- `views/propiedades/show.php` - Vista detallada

#### **Características de las Vistas:**
- Separación clara de lógica y presentación
- Reciben datos del controlador (no lógica de negocio)
- Reutilización de componentes
- Validación del lado cliente
- Feedback visual inmediato
- HTML completo y autónomo

### **Controller (Controlador)**
- `controllers/PropiedadController.php` - Controlador de propiedades
- `controllers/HomeController.php` - Controlador del dashboard

#### **Métodos del Controlador Propiedad:**
- `index()` - Listar propiedades con filtros
- `create()` - Mostrar formulario de creación
- `store()` - Guardar nueva propiedad
- `edit($id)` - Mostrar formulario de edición
- `update($id)` - Actualizar propiedad
- `delete($id)` - Eliminar propiedad
- `show($id)` - Mostrar detalles
- `search()` - Buscar propiedades

#### **Métodos del Controlador Home:**
- `index()` - Dashboard principal con estadísticas

### **Enrutador**
- `index.php` - Enrutador principal del sistema
- Manejo de rutas dinámicas
- Control de errores 404
- Redirección automática

### **Mejores Prácticas MVC Implementadas**

#### **Separación de Responsabilidades**
- **Controladores:** Solo lógica de negocio, sin HTML directo
- **Vistas:** Solo presentación, reciben datos del controlador
- **Modelos:** Solo acceso a datos y lógica de base de datos

#### **Flujo de Datos Correcto**
```
Controlador → Obtiene datos del Modelo → Pasa datos a la Vista → Renderiza HTML
```

#### **Ejemplo de Implementación Correcta**
```php
// HomeController.php (Controlador)
public function index() {
    // Lógica de negocio - obtener estadísticas
    $propiedadesDisponibles = $this->obtenerEstadisticas();
    
    // Pasar datos a la vista (NO HTML directo)
    include 'views/home/index.php';
}

// views/home/index.php (Vista)
<h3><?php echo $propiedadesDisponibles; ?></h3>
```

## 🔧 URLs del Sistema

### **Dashboard y Navegación**
- `index.php` - Dashboard principal
- `index.php?controller=home&action=index` - Dashboard (explícito)

### **Gestión de Propiedades**
- `index.php?controller=propiedad&action=index` - Listar propiedades
- `index.php?controller=propiedad&action=create` - Crear propiedad
- `index.php?controller=propiedad&action=edit&id=1` - Editar propiedad
- `index.php?controller=propiedad&action=show&id=1` - Ver propiedad
- `index.php?controller=propiedad&action=delete&id=1` - Eliminar propiedad

### **Filtros y Búsqueda**
- `index.php?controller=propiedad&action=index&estado=disponible` - Filtrar por estado
- `index.php?controller=propiedad&action=index&tipo=casa` - Filtrar por tipo
- `index.php?controller=propiedad&action=search&q=texto` - Buscar propiedades

## 🎨 Características de Diseño

### **Tailwind CSS**
- Framework CSS utility-first
- Componentes predefinidos
- Responsive design
- Temas personalizables

### **Font Awesome**
- Iconos vectoriales
- Escalables sin pérdida de calidad
- Amplia biblioteca de iconos
- Fácil personalización

### **JavaScript**
- Funciones de filtrado
- Confirmaciones de eliminación
- Validación del lado cliente
- Interacciones dinámicas

## 🚀 Próximas Funcionalidades

### **Pendientes de Implementar**
- [ ] Gestión completa de clientes (CRUD)
- [ ] Gestión de agentes (CRUD)
- [ ] Sistema de ventas
- [ ] Gestión de visitas
- [ ] Reportes y estadísticas avanzadas
- [ ] Sistema de autenticación
- [ ] Subida de imágenes de propiedades
- [ ] Notificaciones por email
- [ ] API REST para integración

### **Mejoras Técnicas**
- [x] Separación correcta MVC (controladores sin HTML)
- [x] Estructura de vistas organizada
- [x] Sistema de filtros persistente
- [ ] Validación más robusta
- [ ] Paginación de resultados
- [ ] Caché de consultas
- [ ] Logs de actividad
- [ ] Backup automático
- [ ] Tests unitarios

## 📚 Documentación Completa

El proyecto incluye documentación detallada organizada en la carpeta `docs/`:

### 🚀 Guías de Inicio
- **[docs/01-getting-started.md](docs/01-getting-started.md)** - Guía de instalación y configuración
- **[docs/02-mvc-pattern.md](docs/02-mvc-pattern.md)** - Explicación del patrón MVC
- **[docs/03-database-schema.md](docs/03-database-schema.md)** - Esquema completo de base de datos

### 🏗️ Entidades del Sistema
- **[docs/04-entities/agente.md](docs/04-entities/agente.md)** - Documentación de la entidad Agente
- **[docs/04-entities/propiedad.md](docs/04-entities/propiedad.md)** - Documentación de la entidad Propiedad *(próximamente)*
- **[docs/04-entities/cliente.md](docs/04-entities/cliente.md)** - Documentación de la entidad Cliente *(próximamente)*
- **[docs/04-entities/venta.md](docs/04-entities/venta.md)** - Documentación de la entidad Venta *(próximamente)*

### 🔧 Funciones Helper
- **[docs/05-functions/url-helpers.md](docs/05-functions/url-helpers.md)** - Funciones de generación de URLs
- **[docs/05-functions/redirect.md](docs/05-functions/redirect.md)** - Sistema de redirecciones
- **[docs/05-functions/mostrar-mensaje.md](docs/05-functions/mostrar-mensaje.md)** - Sistema de notificaciones toast

### 📄 Reportes y PDF
- **[docs/06-reportes/generacion-pdf.md](docs/06-reportes/generacion-pdf.md)** - Generación de reportes PDF con mPDF

### 🔒 Seguridad y Configuración
- **[docs/07-seguridad/gitignore-configuracion.md](docs/07-seguridad/gitignore-configuracion.md)** - Configuración de seguridad y manejo de credenciales

### 📖 Documentación Principal
- **[docs/README.md](docs/README.md)** - Índice completo de toda la documentación
- **[docs/10-galeria-fotos.md](docs/10-galeria-fotos.md)** - Sistema de galería de fotos para propiedades

---

## 🤝 Contribuir

### **Cómo Contribuir**
1. Fork el proyecto
2. Crear una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abrir un Pull Request

### **Estándares de Código**
- Seguir el patrón MVC
- Usar funciones helper para URLs y redirecciones
- Validar todos los datos de entrada
- Documentar nuevas funcionalidades
- Mantener consistencia en el nombramiento
- Escribir código limpio y legible

---

## 👤 Gestión de imágenes de perfil de agentes

- Puedes subir una imagen de perfil al crear o editar un agente.
- Si subes una nueva imagen al editar, la anterior se elimina automáticamente del servidor.
- Al eliminar un agente, su imagen de perfil también se elimina del servidor.
- Desde el formulario de edición puedes marcar la opción "Eliminar imagen actual" para borrar la imagen de perfil del agente sin necesidad de subir una nueva.
- Las imágenes se almacenan en la carpeta `uploads/`. 

## ✅ Validaciones en el flujo de agentes

- **Email y teléfono únicos:** El sistema valida que no se repita el email ni el teléfono de un agente, tanto al crear como al editar.
- **Formato de teléfono:** Solo se aceptan números y el símbolo + (validación en el frontend con pattern y en el backend con preg_match).
- **Persistencia de datos tras error:** Si ocurre un error de validación al crear un agente, el formulario mantiene los datos ingresados y solo muestra el mensaje de error.
- **Feedback visual:** Todos los mensajes de error, éxito o advertencia se muestran con la función `mostrarMensaje`.
- **Validación doble:** Se valida tanto en el frontend (HTML) como en el backend (PHP) para máxima robustez.

---

**Hogar Ideal** - Sistema de Gestión Inmobiliaria © 2024 