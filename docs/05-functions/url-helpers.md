# 🔗 Funciones Helper de URL - Hogar Ideal

## 📋 Tabla de Contenidos

1. [Descripción General](#descripción-general)
2. [Función `url()`](#función-url)
3. [Función `prettyUrl()`](#función-prettyurl)
4. [Comparación de Métodos](#comparación-de-métodos)
5. [Ejemplos de Uso](#ejemplos-de-uso)
6. [Configuración del .htaccess](#configuración-del-htaccess)

---

## 🎯 Descripción General

El sistema "Hogar Ideal" implementa un **sistema híbrido de URLs** que permite tanto URLs tradicionales como URLs amigables. Esto proporciona flexibilidad y compatibilidad con diferentes configuraciones de servidor.

### 📁 Ubicación
- **Archivo**: `includes/functions.php`
- **Funciones**: `url()`, `prettyUrl()`
- **Dependencias**: `.htaccess` configurado

---

## 🔧 Función `url()`

### 📝 Definición
```php
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
```

### 🔍 Análisis Detallado

#### Parámetros:
- **`$controller`**: El nombre del controlador (ej: "propiedades", "agentes")
- **`$action`**: La acción a ejecutar (por defecto "index")
- **`$id`**: ID opcional del elemento (ej: ID de una propiedad específica)

#### Lógica de la Función:
```php
$url = "index.php?controller={$controller}&action={$action}";
```
- Construye la URL base con controlador y acción
- Ejemplo: `index.php?controller=propiedades&action=crear`

```php
if ($id !== null) {
    $url .= "&id={$id}";
}
```
- Si se proporciona un ID, lo añade como parámetro
- Ejemplo: `index.php?controller=propiedades&action=editar&id=123`

### 📊 Ejemplos de Uso

#### URLs Básicas:
```php
// URL para listar propiedades
echo url('propiedades', 'index');
// Resultado: index.php?controller=propiedades&action=index

// URL para crear nueva propiedad
echo url('propiedades', 'crear');
// Resultado: index.php?controller=propiedades&action=crear

// URL para editar propiedad con ID 123
echo url('propiedades', 'editar', 123);
// Resultado: index.php?controller=propiedades&action=editar&id=123
```

#### En Vistas:
```php
<!-- Enlace para crear nueva propiedad -->
<a href="<?php echo url('propiedades', 'crear'); ?>">Nueva Propiedad</a>

<!-- Enlace para editar propiedad específica -->
<a href="<?php echo url('propiedades', 'editar', $propiedad['id']); ?>">Editar</a>

<!-- Enlace para ver agente con ID 5 -->
<a href="<?php echo url('agentes', 'ver', 5); ?>">Ver Agente</a>
```

---

## 🌐 Función `prettyUrl()`

### 📝 Definición
```php
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
```

### 🔍 Análisis Detallado

#### Obtención de la Ruta Base:
```php
$base_path = dirname($_SERVER['SCRIPT_NAME']);
if ($base_path === '/') {
    $base_path = '';
}
```
- `$_SERVER['SCRIPT_NAME']` = `/hogar-ideal/index.php`
- `dirname()` = `/hogar-ideal`
- Si está en la raíz, lo convierte a cadena vacía

#### Construcción de la URL:
```php
$url = $controller;
```
- Empieza con el nombre del controlador
- Ejemplo: `propiedades`

```php
if ($action !== 'index') {
    $url .= "/{$action}";
}
```
- Si la acción NO es 'index', añade la acción
- Ejemplo: `propiedades/crear`

```php
if ($id !== null) {
    $url .= "/{$id}";
}
```
- Si hay ID, lo añade al final
- Ejemplo: `propiedades/editar/123`

### 📊 Ejemplos de Uso

#### URLs Amigables:
```php
// URL para listar propiedades
echo prettyUrl('propiedades', 'index');
// Resultado: /propiedades

// URL para crear nueva propiedad
echo prettyUrl('propiedades', 'crear');
// Resultado: /propiedades/crear

// URL para editar propiedad con ID 123
echo prettyUrl('propiedades', 'editar', 123);
// Resultado: /propiedades/editar/123

// URL para ver agente con ID 5
echo prettyUrl('agentes', 'ver', 5);
// Resultado: /agentes/ver/5
```

#### En Vistas:
```php
<!-- URL amigable para crear propiedad -->
<a href="<?php echo prettyUrl('propiedades', 'crear'); ?>">Nueva Propiedad</a>

<!-- URL amigable para editar propiedad -->
<a href="<?php echo prettyUrl('propiedades', 'editar', $propiedad['id']); ?>">Editar</a>

<!-- URL amigable para listar agentes -->
<a href="<?php echo prettyUrl('agentes'); ?>">Ver Agentes</a>
```

---

## ⚖️ Comparación de Métodos

### URLs Tradicionales vs Amigables

| Aspecto | `url()` | `prettyUrl()` |
|---------|---------|---------------|
| **Formato** | `index.php?controller=X&action=Y&id=Z` | `/controller/action/id` |
| **Legibilidad** | Técnica | Amigable |
| **SEO** | Básico | Excelente |
| **Memorización** | Difícil | Fácil |
| **Compatibilidad** | Universal | Requiere .htaccess |
| **Seguridad** | Expone estructura | Oculta estructura |

### Ejemplos Comparativos:

#### Crear Propiedad:
```php
// Tradicional
url('propiedades', 'crear')
// → index.php?controller=propiedades&action=crear

// Amigable
prettyUrl('propiedades', 'crear')
// → /propiedades/crear
```

#### Editar Propiedad:
```php
// Tradicional
url('propiedades', 'editar', 123)
// → index.php?controller=propiedades&action=editar&id=123

// Amigable
prettyUrl('propiedades', 'editar', 123)
// → /propiedades/editar/123
```

#### Ver Agente:
```php
// Tradicional
url('agentes', 'ver', 5)
// → index.php?controller=agentes&action=ver&id=5

// Amigable
prettyUrl('agentes', 'ver', 5)
// → /agentes/ver/5
```

---

## 🛠️ Configuración del .htaccess

### Archivo .htaccess Completo
```apache
RewriteEngine On

# Redirigir todas las peticiones a index.php
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]

# Permitir acceso a archivos estáticos
RewriteCond %{REQUEST_URI} !\.(css|js|png|jpg|jpeg|gif|ico|pdf)$
```

### 🔍 Explicación de las Reglas

#### `RewriteEngine On`
- Habilita el motor de reescritura de Apache

#### `RewriteCond %{REQUEST_FILENAME} !-f`
- **Condición**: Verifica que el archivo solicitado NO sea un archivo físico existente
- **Significado**: Solo aplica la regla si la URL no corresponde a un archivo real
- **Ejemplo**: Si alguien pide `/imagen.jpg` y ese archivo existe, NO se redirige a `index.php`

#### `RewriteCond %{REQUEST_FILENAME} !-d`
- **Condición**: Verifica que la ruta solicitada NO sea un directorio físico existente
- **Significado**: Solo aplica la regla si la URL no corresponde a una carpeta real
- **Ejemplo**: Si alguien pide `/uploads/` y esa carpeta existe, NO se redirige a `index.php`

#### `RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]`
- **Patrón**: `^(.*)$` - Captura cualquier URL completa
- **Redirección**: Envía todo a `index.php` con el parámetro `url`
- **Flags**:
  - `QSA` (Query String Append): Mantiene los parámetros GET existentes
  - `L` (Last): Es la última regla a procesar

### 🎯 Ventajas de esta Configuración

1. **Permite URLs amigables**: `/propiedades/123` en lugar de `/index.php?url=propiedades/123`
2. **Protege archivos estáticos**: Las imágenes, CSS, JS se sirven directamente
3. **Habilita el enrutador**: La aplicación puede procesar `/agentes/crear` como una ruta válida
4. **Mejora la seguridad**: Evita que los usuarios accedan directamente a archivos PHP sensibles

---

## 📊 Ejemplos de Uso Avanzados

### 1. En Controladores
```php
// PropiedadController.php
public function index() {
    $propiedades = $this->propiedadModel->getAll();
    include 'views/propiedades/index.php';
}

public function crear() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Procesar formulario
        if ($this->propiedadModel->create($_POST)) {
            redirect('propiedades', 'index', null, ['mensaje' => 'Propiedad creada']);
        }
    }
    include 'views/propiedades/create.php';
}
```

### 2. En Vistas con Navegación
```php
<!-- views/propiedades/index.php -->
<div class="flex justify-between items-center mb-6">
    <h1 class="text-3xl font-bold">Propiedades</h1>
    <a href="<?php echo prettyUrl('propiedades', 'crear'); ?>" 
       class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Nueva Propiedad
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php foreach ($propiedades as $propiedad): ?>
        <div class="bg-white rounded-lg shadow-md">
            <div class="p-4">
                <h3 class="text-xl font-semibold">
                    <?php echo htmlspecialchars($propiedad['titulo']); ?>
                </h3>
                <p class="text-2xl font-bold text-blue-600">
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
```

### 3. En Formularios
```php
<!-- views/propiedades/create.php -->
<form action="<?php echo prettyUrl('propiedades', 'crear'); ?>" method="POST" enctype="multipart/form-data">
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="titulo">
            Título
        </label>
        <input type="text" name="titulo" id="titulo" required
               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    
    <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="precio">
            Precio
        </label>
        <input type="number" name="precio" id="precio" step="0.01" required
               class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>
    
    <div class="flex items-center justify-between">
        <button type="submit" 
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
            Crear Propiedad
        </button>
        <a href="<?php echo prettyUrl('propiedades'); ?>" 
           class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
            Cancelar
        </a>
    </div>
</form>
```

---

## 🔧 Solución de Problemas

### URLs No Funcionan
1. **Verificar mod_rewrite**: Asegúrate de que esté habilitado en Apache
2. **Verificar .htaccess**: Confirma que el archivo esté presente y bien configurado
3. **Verificar permisos**: El archivo .htaccess debe ser legible por Apache
4. **Revisar logs**: Consulta los logs de error de Apache

### Errores 404
1. **Verificar index.php**: Confirma que esté en la raíz del proyecto
2. **Verificar rutas**: Asegúrate de que las rutas en los controladores sean correctas
3. **Verificar enrutador**: Confirma que el enrutador en index.php esté funcionando

### URLs Amigables No Funcionan
1. **Verificar .htaccess**: Asegúrate de que las reglas estén correctas
2. **Verificar servidor**: Confirma que Apache soporte mod_rewrite
3. **Probar URLs tradicionales**: Usa `url()` como fallback

---

## 📚 Mejores Prácticas

### 1. Uso Consistente
```php
// ✅ Correcto - Usar funciones helper
<a href="<?php echo prettyUrl('propiedades', 'crear'); ?>">Nueva Propiedad</a>

// ❌ Incorrecto - URLs hardcodeadas
<a href="index.php?controller=propiedades&action=crear">Nueva Propiedad</a>
```

### 2. Validación de Parámetros
```php
// ✅ Correcto - Validar parámetros
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
if ($id && $id > 0) {
    $propiedad = $this->propiedadModel->getById($id);
}

// ❌ Incorrecto - Sin validación
$propiedad = $this->propiedadModel->getById($_GET['id']);
```

### 3. Manejo de Errores
```php
// ✅ Correcto - Manejar errores
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

Las funciones helper de URL proporcionan:

1. **Flexibilidad**: Sistema híbrido que funciona en cualquier servidor
2. **Mantenibilidad**: URLs centralizadas y fáciles de cambiar
3. **SEO**: URLs amigables para mejor posicionamiento
4. **Seguridad**: Ocultan la estructura interna de la aplicación
5. **Consistencia**: Patrón uniforme en toda la aplicación

Para más información sobre implementaciones específicas, consulta:
- [Función redirect()](redirect.md)
- [Documentación de Entidades](04-entities/)
- [Guía MVC](02-mvc-pattern.md) 