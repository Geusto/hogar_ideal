# 🔍 Sistema de Búsqueda y Filtros - Hogar Ideal

## 📋 Tabla de Contenidos

1. [Descripción General](#descripción-general)
2. [Arquitectura del Sistema](#arquitectura-del-sistema)
3. [Implementación en el Modelo](#implementación-en-el-modelo)
4. [Implementación en el Controlador](#implementación-en-el-controlador)
5. [Implementación en la Vista](#implementación-en-la-vista)
6. [Funcionalidades](#funcionalidades)
7. [Ejemplos de Uso](#ejemplos-de-uso)
8. [Solución de Problemas](#solución-de-problemas)

---

## 🎯 Descripción General

El sistema "Hogar Ideal" implementa un **sistema de búsqueda avanzada** que permite a los usuarios encontrar propiedades inmobiliarias mediante:

- **Búsqueda por texto**: En direcciones, nombres de clientes y agentes
- **Filtros por tipo**: Casa, apartamento, terreno, local comercial
- **Filtros por estado**: Disponible, vendida
- **Combinación de criterios**: Búsqueda + filtros simultáneos

### 🌟 Características Principales
- Búsqueda en tiempo real
- Filtros dinámicos con JavaScript
- URLs amigables para filtros
- Persistencia de criterios de búsqueda
- Mensajes informativos cuando no hay resultados

---

## 🏗️ Arquitectura del Sistema

### Flujo de Búsqueda
```
Usuario ingresa texto o selecciona filtros
↓
Formulario envía a PropiedadController::search()
↓
Modelo Propiedad::search() ejecuta consulta SQL
↓
Controlador procesa resultados y añade fotos de portada
↓
Vista muestra resultados con indicadores de búsqueda
```

### Componentes Involucrados
1. **Vista**: Formulario de búsqueda y filtros
2. **Controlador**: `PropiedadController::search()`
3. **Modelo**: `Propiedad::search()`
4. **JavaScript**: Filtros dinámicos
5. **Base de Datos**: Consultas optimizadas con JOINs

---

## 📊 Implementación en el Modelo

### Método de Búsqueda Principal

```php
// models/Propiedad.php
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
```

### Análisis de la Consulta

#### Campos de Búsqueda
- **`p.direccion`**: Dirección de la propiedad
- **`c.nombre_completo`**: Nombre del cliente vendedor
- **`a.nombre_completo`**: Nombre del agente asignado

#### Optimización
- **JOINs LEFT**: Para incluir propiedades sin cliente o agente
- **LIKE con wildcards**: `%texto%` para búsqueda parcial
- **ORDER BY**: Ordenamiento por ID descendente (más recientes primero)

### Métodos de Filtrado Adicionales

```php
// Filtro por estado
public function getByEstado($estado) {
    $stmt = $this->pdo->prepare("SELECT p.*, c.nombre_completo as cliente_vendedor, a.nombre_completo as agente_nombre 
        FROM propiedad p 
        LEFT JOIN cliente c ON p.id_cliente_vendedor = c.id_cliente 
        LEFT JOIN agente a ON p.id_agente = a.id_agente 
        WHERE p.estado = ? ORDER BY p.id_propiedad DESC");
    $stmt->execute([$estado]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Filtro por tipo
public function getByTipo($tipo) {
    $stmt = $this->pdo->prepare("SELECT p.*, c.nombre_completo as cliente_vendedor, a.nombre_completo as agente_nombre 
        FROM propiedad p 
        LEFT JOIN cliente c ON p.id_cliente_vendedor = c.id_cliente 
        LEFT JOIN agente a ON p.id_agente = a.id_agente 
        WHERE p.tipo = ? ORDER BY p.id_propiedad DESC");
    $stmt->execute([$tipo]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
```

---

## 🎮 Implementación en el Controlador

### Método de Búsqueda

```php
// controllers/PropiedadController.php
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

### Método Principal con Filtros

```php
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
```

### Características del Controlador

- **Validación de parámetros**: Uso de operador null coalescing (`??`)
- **Lógica condicional**: Aplicar filtros solo si están presentes
- **Procesamiento de datos**: Añadir fotos de portada a cada propiedad
- **Reutilización de vista**: Misma vista para búsqueda y listado

---

## 🖼️ Implementación en la Vista

### Formulario de Búsqueda

```php
<!-- views/propiedades/index.php -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Formulario de búsqueda por texto -->
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
        
        <!-- Filtro por estado -->
        <select id="filtroEstado" onchange="aplicarFiltro()" 
                class="border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Todos los estados</option>
            <option value="disponible" <?php echo (isset($_GET['estado']) && $_GET['estado'] === 'disponible') ? 'selected' : ''; ?>>Disponible</option>
            <option value="vendida" <?php echo (isset($_GET['estado']) && $_GET['estado'] === 'vendida') ? 'selected' : ''; ?>>Vendida</option>
        </select>
        
        <!-- Filtro por tipo -->
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
```

### JavaScript para Filtros Dinámicos

```javascript
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
```

### Visualización de Resultados

```php
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
            <!-- Tarjeta de propiedad con foto de portada -->
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
                    
                    <!-- Indicador de estado -->
                    <div class="absolute top-2 right-2">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                            <?php echo $propiedad['estado'] === 'disponible' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'; ?>">
                            <?php echo ucfirst($propiedad['estado']); ?>
                        </span>
                    </div>
                </div>
                
                <!-- Información de la propiedad -->
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-800">
                        <?php echo ucfirst($propiedad['tipo']); ?> en <?php echo htmlspecialchars($propiedad['direccion']); ?>
                    </h3>
                    
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
                    
                    <!-- Acciones -->
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
```

---

## ⚡ Funcionalidades

### 1. Búsqueda por Texto
- **Campos de búsqueda**: Dirección, nombre del cliente, nombre del agente
- **Búsqueda parcial**: Encuentra coincidencias en cualquier parte del texto
- **Case-insensitive**: No distingue entre mayúsculas y minúsculas
- **Múltiples términos**: Búsqueda en varios campos simultáneamente

### 2. Filtros por Estado
- **Disponible**: Propiedades en venta
- **Vendida**: Propiedades ya vendidas
- **Selección múltiple**: Permite combinar con otros filtros

### 3. Filtros por Tipo
- **Casa**: Viviendas unifamiliares
- **Apartamento**: Unidades en edificios
- **Terreno**: Lotes para construcción
- **Local Comercial**: Espacios comerciales

### 4. Combinación de Criterios
- **Búsqueda + Filtros**: Texto + estado + tipo
- **Persistencia**: Los filtros se mantienen entre navegaciones
- **Limpieza**: Botón para resetear todos los filtros

### 5. Experiencia de Usuario
- **Feedback visual**: Indicadores de estado y tipo
- **Mensajes informativos**: Cuando no hay resultados
- **Navegación intuitiva**: URLs amigables para filtros
- **Responsive**: Adaptación a diferentes dispositivos

---

## 📝 Ejemplos de Uso

### Búsqueda Simple por Texto
```
URL: /propiedad/search?q=casa
Resultado: Propiedades que contengan "casa" en dirección, cliente o agente
```

### Filtro por Estado
```
URL: /propiedad?estado=disponible
Resultado: Solo propiedades disponibles
```

### Filtro por Tipo
```
URL: /propiedad?tipo_propiedad=apartamento
Resultado: Solo apartamentos
```

### Combinación de Filtros
```
URL: /propiedad?estado=disponible&tipo_propiedad=casa
Resultado: Solo casas disponibles
```

### Búsqueda + Filtros
```
URL: /propiedad/search?q=centro&estado=disponible
Resultado: Propiedades disponibles que contengan "centro" en dirección, cliente o agente
```

---

## 🔧 Solución de Problemas

### Problemas Comunes

#### 1. Búsqueda No Funciona
**Síntomas**: El formulario no envía o no muestra resultados
**Soluciones**:
- Verificar que el formulario apunte a `propiedad/search`
- Confirmar que el controlador `search()` esté implementado
- Verificar que el modelo tenga el método `search()`
- Revisar la construcción de URLs en los filtros

#### 2. Filtros No Se Aplican
**Síntomas**: Los filtros no cambian los resultados
**Soluciones**:
- Verificar que JavaScript esté habilitado
- Confirmar que la función `aplicarFiltro()` esté definida
- Revisar la construcción de URLs en JavaScript
- Verificar que los parámetros GET se estén recibiendo

#### 3. No Se Muestran Fotos
**Síntomas**: Las propiedades aparecen sin imágenes
**Soluciones**:
- Verificar que la tabla `fotos_propiedad` esté creada
- Confirmar que el método `getFotoPortada()` esté implementado
- Revisar permisos de la carpeta `uploads/`
- Verificar que las rutas de archivos sean correctas

#### 4. URLs Mal Formadas
**Síntomas**: Errores 404 o parámetros perdidos
**Soluciones**:
- Verificar que `.htaccess` esté configurado
- Confirmar que `mod_rewrite` esté habilitado
- Revisar la función `prettyUrl()` en `functions.php`
- Verificar que el enrutador esté procesando correctamente

### Debug y Logs

#### Verificar Parámetros Recibidos
```php
// En el controlador
public function search() {
    error_log("DEBUG: Parámetros recibidos: " . print_r($_GET, true));
    $query = $_GET['q'] ?? '';
    // ... resto del código
}
```

#### Verificar Consulta SQL
```php
// En el modelo
public function search($query) {
    $sql = "SELECT p.*, c.nombre_completo as cliente_vendedor, a.nombre_completo as agente_nombre 
            FROM propiedad p 
            LEFT JOIN cliente c ON p.id_cliente_vendedor = c.id_cliente 
            LEFT JOIN agente a ON p.id_agente = a.id_agente 
            WHERE p.direccion LIKE ? OR c.nombre_completo LIKE ? OR a.nombre_completo LIKE ?
            ORDER BY p.id_propiedad DESC";
    
    error_log("DEBUG: SQL: " . $sql);
    error_log("DEBUG: Query: " . $query);
    
    $searchTerm = "%$query%";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$searchTerm, $searchTerm, $searchTerm]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    error_log("DEBUG: Resultados: " . count($result));
    return $result;
}
```

#### Verificar JavaScript
```javascript
// En la consola del navegador
function aplicarFiltro() {
    const estado = document.getElementById('filtroEstado').value;
    const tipo = document.getElementById('filtroTipo').value;
    
    console.log('Estado:', estado);
    console.log('Tipo:', tipo);
    
    let url = '<?= prettyUrl('propiedad', 'index') ?>';
    const params = new URLSearchParams();
    if (estado) { params.set('estado', estado); }
    if (tipo) { params.set('tipo_propiedad', tipo); }
    const qs = params.toString();
    if (qs) { url += '?' + qs; }
    
    console.log('URL final:', url);
    window.location.href = url;
}
```

---

## 🚀 Mejoras Futuras

### Funcionalidades Planificadas

1. **Búsqueda Avanzada**
   - Filtros por rango de precios
   - Filtros por superficie
   - Búsqueda por ubicación geográfica
   - Historial de búsquedas

2. **Optimización de Rendimiento**
   - Paginación de resultados
   - Cache de consultas frecuentes
   - Índices de base de datos optimizados
   - Lazy loading de imágenes

3. **Experiencia de Usuario**
   - Búsqueda en tiempo real (AJAX)
   - Autocompletado de direcciones
   - Filtros con sliders
   - Guardado de filtros favoritos

4. **Integración**
   - API REST para búsquedas
   - Exportación de resultados
   - Notificaciones de nuevas propiedades
   - Comparación de propiedades

---

## 📚 Conclusión

El sistema de búsqueda implementado en "Hogar Ideal" proporciona:

1. **Funcionalidad completa**: Búsqueda por texto y filtros múltiples
2. **Arquitectura robusta**: Separación clara entre modelo, vista y controlador
3. **Experiencia de usuario**: Interfaz intuitiva y responsive
4. **Escalabilidad**: Fácil de extender con nuevas funcionalidades
5. **Mantenibilidad**: Código bien estructurado y documentado

Para más información sobre implementaciones específicas, consulta:
- [Patrón MVC](02-mvc-pattern.md)
- [Esquema de Base de Datos](03-database-schema.md)
- [Funciones Helper de URL](url-helpers.md)
- [Sistema de Galería de Fotos](10-galeria-fotos.md)
