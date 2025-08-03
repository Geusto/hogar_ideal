# 📋 DOCUMENTACIÓN COMPLETA: ENTIDAD AGENTE

## 🎯 ÍNDICE
1. [Descripción General](#descripción-general)
2. [Estructura de Base de Datos](#estructura-de-base-de-datos)
3. [Modelo (Agente.php)](#modelo-agentephp)
4. [Controlador (AgenteController.php)](#controlador-agentephp)
5. [Vistas](#vistas)
6. [Relaciones con Otras Entidades](#relaciones-con-otras-entidades)
7. [Funcionalidades Especiales](#funcionalidades-especiales)
8. [Validaciones y Seguridad](#validaciones-y-seguridad)
9. [Flujo de Datos](#flujo-de-datos)
10. [Ejemplos de Uso](#ejemplos-de-uso)

---

## 📖 DESCRIPCIÓN GENERAL

La entidad **Agente** representa a los agentes inmobiliarios del sistema "Hogar Ideal". Es una de las entidades principales que maneja toda la información relacionada con los agentes que gestionan propiedades y ventas.

### 🎯 Propósito
- Gestionar información personal y profesional de agentes inmobiliarios
- Asignar agentes a zonas geográficas específicas
- Controlar el estado activo/inactivo de los agentes
- Manejar imágenes de perfil de los agentes
- Validar datos únicos (email, teléfono, documento)

---

## 🗄️ ESTRUCTURA DE BASE DE DATOS

### Tabla Principal: `agente`

```sql
CREATE TABLE `agente` (
  `id_agente` int NOT NULL AUTO_INCREMENT,
  `nombre_completo` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `zona_asignada` varchar(50) NOT NULL,
  `tipo_documento` int DEFAULT NULL,
  `documento` varchar(20) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `imagen_perfil` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_agente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
```

### 📊 Campos de la Tabla

| Campo | Tipo | Descripción | Restricciones |
|-------|------|-------------|---------------|
| `id_agente` | INT | Identificador único | AUTO_INCREMENT, PRIMARY KEY |
| `nombre_completo` | VARCHAR(100) | Nombre completo del agente | NOT NULL |
| `telefono` | VARCHAR(20) | Número de teléfono | NOT NULL |
| `email` | VARCHAR(100) | Correo electrónico | UNIQUE |
| `zona_asignada` | VARCHAR(50) | Zona geográfica asignada | NOT NULL |
| `tipo_documento` | INT | ID del tipo de documento | FOREIGN KEY |
| `documento` | VARCHAR(20) | Número de documento | UNIQUE con tipo_documento |
| `activo` | TINYINT(1) | Estado activo/inactivo | DEFAULT 1 |
| `imagen_perfil` | VARCHAR(255) | Ruta de la imagen de perfil | NULL |

### 🔗 Tabla Relacionada: `tipo_documento`

```sql
CREATE TABLE `tipo_documento` (
  `idTipoDocumento` int NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) NOT NULL,
  PRIMARY KEY (`idTipoDocumento`)
);
```

---

## 🏗️ MODELO (Agente.php)

### 📁 Ubicación: `models/Agente.php`

### 🔧 Constructor
```php
public function __construct() {
    global $pdo;
    $this->pdo = $pdo;
}
```

### 📋 Métodos CRUD Principales

#### 1. **getAll()** - Obtener todos los agentes
```php
public function getAll() {
    $sql = "SELECT a.*, td.descripcion AS tipo_documento_nombre
            FROM agente a
            LEFT JOIN tipo_documento td ON a.tipo_documento = td.idTipoDocumento
            ORDER BY a.nombre_completo";
    $stmt = $this->pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
```

**Explicación:**
- Realiza un LEFT JOIN con la tabla `tipo_documento`
- Obtiene la descripción del tipo de documento
- Ordena por nombre completo
- Retorna un array asociativo con todos los agentes

#### 2. **getById($id)** - Obtener agente por ID
```php
public function getById($id) {
    $stmt = $this->pdo->prepare("SELECT * FROM agente WHERE id_agente = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
```

**Explicación:**
- Usa prepared statements para prevenir SQL injection
- Busca por el ID único del agente
- Retorna un solo registro o false si no existe

#### 3. **create($data)** - Crear nuevo agente
```php
public function create($data) {
    $sql = "INSERT INTO agente (nombre_completo, email, telefono, zona_asignada, 
            tipo_documento, documento, activo, imagen_perfil) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
        $data['nombre_completo'],
        $data['email'],
        $data['telefono'],
        $data['zona_asignada'],
        $data['tipo_documento'],
        $data['documento'],
        $data['activo'],
        $data['imagen_perfil']
    ]);
}
```

**Explicación:**
- Usa prepared statements para seguridad
- Inserta todos los campos del agente
- Retorna true/false según el éxito de la operación

#### 4. **update($id, $data)** - Actualizar agente
```php
public function update($id, $data) {
    $sql = "UPDATE agente SET nombre_completo = ?, email = ?, telefono = ?, 
            zona_asignada = ?, tipo_documento = ?, documento = ?, activo = ?, 
            imagen_perfil = ? WHERE id_agente = ?";
    $stmt = $this->pdo->prepare($sql);
    return $stmt->execute([
        $data['nombre_completo'],
        $data['email'],
        $data['telefono'],
        $data['zona_asignada'],
        $data['tipo_documento'],
        $data['documento'],
        $data['activo'],
        $data['imagen_perfil'],
        $id
    ]);
}
```

### 🔍 Métodos de Validación

#### 1. **emailEnUso($email, $id = null)**
```php
public function emailEnUso($email, $id = null) {
    $sql = "SELECT COUNT(*) as total FROM agente WHERE email = ?";
    $params = [$email];
    if ($id !== null) {
        $sql .= " AND id_agente != ?";
        $params[] = $id;
    }
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch()['total'] > 0;
}
```

**Explicación:**
- Verifica si el email ya está en uso
- El parámetro `$id` permite excluir el registro actual en edición
- Retorna true si el email está en uso, false si está disponible

#### 2. **documentoEnUso($documento, $tipo_documento, $id = null)**
```php
public function documentoEnUso($documento, $tipo_documento, $id = null) {
    $sql = "SELECT COUNT(*) as total FROM agente WHERE documento = ? AND tipo_documento = ?";
    $params = [$documento, $tipo_documento];
    if ($id !== null) {
        $sql .= " AND id_agente != ?";
        $params[] = $id;
    }
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetch()['total'] > 0;
}
```

### 🗑️ Métodos de Eliminación

#### **eliminarOInactivar($id)**
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

**Explicación:**
- Verifica si el agente tiene propiedades asociadas
- Si tiene propiedades: lo marca como inactivo (soft delete)
- Si no tiene propiedades: lo elimina físicamente (hard delete)
- Retorna el tipo de acción realizada

### 📊 Métodos de Consulta

#### 1. **count()** - Contar total de agentes
#### 2. **countByZonaAsignada($zona)** - Contar agentes por zona
#### 3. **search($query)** - Buscar agentes
#### 4. **getTiposDocumento()** - Obtener tipos de documento

---

## 🎮 CONTROLADOR (AgenteController.php)

### 📁 Ubicación: `controllers/AgenteController.php`

### 🔧 Constructor
```php
public function __construct() {
    $this->agenteModel = new Agente();
}
```

### 📋 Métodos del Controlador

#### 1. **index()** - Mostrar lista de agentes
```php
public function index(){
    $agentes = $this->agenteModel->getAll();
    include 'views/agentes/index.php';
}
```

#### 2. **create()** - Mostrar formulario de creación
```php
public function create() {
    $tipos_documento = $this->agenteModel->getTiposDocumento();
    include 'views/agentes/create.php';
}
```

#### 3. **store()** - Guardar nuevo agente
```php
public function store() {
    $data = [
        'nombre_completo' => $_POST['nombre_completo'] ?? '',
        'telefono' => $_POST['telefono'] ?? '',
        'email' => $_POST['email'] ?? '',
        'zona_asignada' => $_POST['zona_asignada'] ?? '',
        'activo' => $_POST['activo'] ?? '1',
    ];

    // Validaciones
    if (!preg_match('/^[0-9+]{8,15}$/', $data['telefono'])) {
        $msg = 'El teléfono debe contener solo números y el símbolo +.';
        $tipo = 'error';
        $agente = $data;
        include 'views/agentes/create.php';
        return;
    }

    if ($this->agenteModel->emailEnUso($data['email'])) {
        $msg = 'El email ya está en uso.';
        $tipo = 'error';
        $agente = $data;
        include 'views/agentes/create.php';
        return;
    }

    // Procesar imagen
    if (isset($_FILES['imagen_perfil']) && $_FILES['imagen_perfil']['error'] === UPLOAD_ERR_OK) {
        $nombreArchivo = uniqid() . '_' . basename($_FILES['imagen_perfil']['name']);
        $rutaDestino = 'uploads/' . $nombreArchivo;
        $tipoArchivo = strtolower(pathinfo($rutaDestino, PATHINFO_EXTENSION));
        $tiposPermitidos = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        
        if (in_array($tipoArchivo, $tiposPermitidos)) {
            if (move_uploaded_file($_FILES['imagen_perfil']['tmp_name'], $rutaDestino)) {
                $data['imagen_perfil'] = $rutaDestino;
            }
        }
    }

    if ($this->agenteModel->create($data)) {
        redirect('agente', 'index', null, ['msg' => 'Agente creado correctamente.', 'tipo' => 'exito']);
    } else {
        $msg = 'No se pudo crear el agente.';
        $tipo = 'error';
        $agente = $data;
        include 'views/agentes/create.php';
    }
}
```

**Explicación del flujo:**
1. **Recopila datos** del formulario POST
2. **Valida formato** del teléfono con regex
3. **Verifica email único** usando el modelo
4. **Procesa imagen** si se subió una
5. **Crea el agente** usando el modelo
6. **Redirige** con mensaje de éxito o error

#### 4. **edit($id)** - Mostrar formulario de edición
```php
public function edit($id) {
    $agente = $this->agenteModel->getById($id);
    $tipos_documento = $this->agenteModel->getTiposDocumento();
    if (!$agente) {
        redirect('agente', 'index');
    }
    include 'views/agentes/edit.php';
}
```

#### 5. **update($id)** - Actualizar agente
```php
public function update($id) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = [
            'nombre_completo' => $_POST['nombre_completo'] ?? '',
            'telefono' => $_POST['telefono'] ?? '',
            'email' => $_POST['email'] ?? '',
            'tipo_documento' => $_POST['tipo_documento'] ?? '',
            'documento' => $_POST['documento'] ?? '',
            'zona_asignada' => $_POST['zona_asignada'] ?? '',
            'activo' => $_POST['activo'] ?? '',
        ];

        // Obtener imagen actual
        $agenteActual = $this->agenteModel->getById($id);
        $data['imagen_perfil'] = $agenteActual['imagen_perfil'] ?? null;

        // Eliminar imagen si se solicita
        if (isset($_POST['eliminar_imagen']) && $_POST['eliminar_imagen'] === '1') {
            if (!empty($agenteActual['imagen_perfil']) && file_exists($agenteActual['imagen_perfil'])) {
                unlink($agenteActual['imagen_perfil']);
            }
            $data['imagen_perfil'] = null;
        }

        // Procesar nueva imagen
        if (isset($_FILES['imagen_perfil']) && $_FILES['imagen_perfil']['error'] === UPLOAD_ERR_OK) {
            // Eliminar imagen anterior
            if (!empty($agenteActual['imagen_perfil']) && file_exists($agenteActual['imagen_perfil'])) {
                unlink($agenteActual['imagen_perfil']);
            }
            
            $nombreArchivo = uniqid() . '_' . basename($_FILES['imagen_perfil']['name']);
            $rutaDestino = 'uploads/' . $nombreArchivo;
            $tipoArchivo = strtolower(pathinfo($rutaDestino, PATHINFO_EXTENSION));
            $tiposPermitidos = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            
            if (in_array($tipoArchivo, $tiposPermitidos)) {
                if (move_uploaded_file($_FILES['imagen_perfil']['tmp_name'], $rutaDestino)) {
                    $data['imagen_perfil'] = $rutaDestino;
                }
            }
        }

        // Validaciones
        if ($this->agenteModel->emailEnUso($data['email'], $id)) {
            redirect('agente', 'edit', $id, ['msg' => 'El email ya está en uso.', 'tipo' => 'error']);
        }

        if ($this->agenteModel->documentoEnUso($data['documento'], $data['tipo_documento'], $id)) {
            redirect('agente', 'edit', $id, ['msg' => 'El documento ya está en uso.', 'tipo' => 'error']);
        }

        if ($this->agenteModel->update($id, $data)) {
            redirect('agente', 'index', null, ['msg' => 'Agente actualizado correctamente.', 'tipo' => 'exito']);
        } else {
            redirect('agente', 'edit', $id, ['msg' => 'No se pudo actualizar el agente.', 'tipo' => 'error']);
        }
    }
}
```

#### 6. **delete($id)** - Eliminar agente
```php
public function delete($id) {
    $agente = $this->agenteModel->getById($id);
    $resultado = $this->agenteModel->eliminarOInactivar($id);
    
    if ($resultado === 'eliminado') {
        // Eliminar imagen de perfil
        if (!empty($agente['imagen_perfil']) && file_exists($agente['imagen_perfil'])) {
            unlink($agente['imagen_perfil']);
        }
        redirect('agente', 'index', null, ['msg' => 'Agente eliminado correctamente.', 'tipo' => 'eliminado']);
    } else if ($resultado === 'inactivado') {
        redirect('agente', 'index', null, ['msg' => 'El agente tiene propiedades asociadas y fue marcado como inactivo.', 'tipo' => 'advertencia']);
    } else {
        redirect('agente', 'index', null, ['msg' => 'No se pudo eliminar el agente.', 'tipo' => 'error']);
    }
}
```

---

## 🎨 VISTAS

### 📁 Ubicación: `views/agentes/`

### 1. **index.php** - Lista de agentes
**Características principales:**
- Tabla responsive con Tailwind CSS
- Muestra imagen de perfil, nombre, email, teléfono, etc.
- Botones de editar y eliminar
- Modal de confirmación para eliminar
- Mensajes de estado (activo/inactivo)

**Estructura HTML:**
```html
<table class="min-w-full bg-white rounded-lg shadow-md">
    <thead>
        <tr>
            <th>Imagen</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Teléfono</th>
            <th>Tipo de Documento</th>
            <th>Documento</th>
            <th>Zona Asignada</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <!-- Iteración de agentes -->
    </tbody>
</table>
```

### 2. **create.php** - Formulario de creación
**Características:**
- Formulario con validación HTML5
- Campos requeridos marcados con *
- Select para tipos de documento
- Input para imagen de perfil
- Validación de teléfono con pattern
- Botones de guardar y cancelar

**Campos del formulario:**
- Nombre Completo (text)
- Teléfono (tel con pattern)
- Tipo de Documento (select)
- Documento (text con pattern)
- Email (email)
- Zona Asignada (text)
- Imagen de Perfil (file)

### 3. **edit.php** - Formulario de edición
**Características adicionales:**
- Muestra imagen actual si existe
- Checkbox para eliminar imagen
- Botón deshabilitado si no hay cambios
- JavaScript para detectar cambios
- Validaciones del lado del cliente

**JavaScript para detectar cambios:**
```javascript
function hayCambios() {
    if (form.nombre_completo.value !== original.nombre_completo) return true;
    if (form.telefono.value !== original.telefono) return true;
    // ... más validaciones
    return false;
}

function toggleBotonGuardar() {
    document.getElementById('btn-guardar-agente').disabled = !hayCambios();
}
```

---

## 🔗 RELACIONES CON OTRAS ENTIDADES

### 1. **Relación con Propiedad (1:N)**
```sql
-- Un agente puede tener múltiples propiedades
SELECT * FROM propiedad WHERE id_agente = ?
```

**Impacto en eliminación:**
- Si un agente tiene propiedades, se marca como inactivo
- Si no tiene propiedades, se elimina físicamente

### 2. **Relación con Venta (1:N)**
```sql
-- Un agente puede realizar múltiples ventas
SELECT * FROM venta WHERE id_agente = ?
```

### 3. **Relación con Tipo Documento (N:1)**
```sql
-- Un tipo de documento puede ser usado por múltiples agentes
SELECT a.*, td.descripcion AS tipo_documento_nombre
FROM agente a
LEFT JOIN tipo_documento td ON a.tipo_documento = td.idTipoDocumento
```

---

## ⚡ FUNCIONALIDADES ESPECIALES

### 1. **Gestión de Imágenes**
- **Subida:** Procesa archivos de imagen con validación de tipo
- **Almacenamiento:** Guarda en carpeta `uploads/` con nombre único
- **Eliminación:** Borra archivo físico al eliminar o cambiar imagen
- **Formatos permitidos:** jpg, jpeg, png, gif, webp

### 2. **Soft Delete vs Hard Delete**
- **Soft Delete:** Marca como inactivo si tiene relaciones
- **Hard Delete:** Elimina físicamente si no tiene relaciones
- **Prevención:** Evita pérdida de datos históricos

### 3. **Validaciones Únicas**
- **Email:** Debe ser único en toda la tabla
- **Documento + Tipo:** Combinación única
- **Teléfono:** Validación de formato (números y +)

---

## 🔒 VALIDACIONES Y SEGURIDAD

### 1. **Validaciones del Lado del Servidor**
```php
// Validación de teléfono
if (!preg_match('/^[0-9+]{8,15}$/', $data['telefono'])) {
    // Error: formato inválido
}

// Validación de email único
if ($this->agenteModel->emailEnUso($data['email'], $id)) {
    // Error: email ya en uso
}

// Validación de documento único
if ($this->agenteModel->documentoEnUso($data['documento'], $data['tipo_documento'], $id)) {
    // Error: documento ya en uso
}
```

### 2. **Validaciones del Lado del Cliente**
```html
<!-- Validación de teléfono -->
<input type="tel" pattern="[0-9+]{8,15}" title="Solo números y el símbolo +">

<!-- Validación de documento -->
<input type="text" pattern="[0-9]{8,15}" title="Solo números">

<!-- Validación de email -->
<input type="email" required>
```

### 3. **Prevención de SQL Injection**
- Uso de **prepared statements** en todas las consultas
- Parámetros separados de la consulta SQL
- Validación de tipos de datos

### 4. **Prevención de XSS**
- Uso de `htmlspecialchars()` en la salida
- Función `limpiarDatos()` para entrada
- Escape de caracteres especiales

### 5. **Seguridad de Archivos**
- Validación de tipos MIME
- Nombres únicos para archivos
- Verificación de extensiones permitidas
- Eliminación segura de archivos

---

## 🔄 FLUJO DE DATOS

### 1. **Creación de Agente**
```
Usuario → Formulario → Controlador → Validaciones → Modelo → Base de Datos
   ↓
Respuesta → Vista → Usuario
```

### 2. **Edición de Agente**
```
Usuario → Formulario → Controlador → Validaciones → Modelo → Base de Datos
   ↓
Respuesta → Vista → Usuario
```

### 3. **Eliminación de Agente**
```
Usuario → Botón Eliminar → Modal → Controlador → Verificar Relaciones → Modelo → Base de Datos
   ↓
Respuesta → Vista → Usuario
```

---

## 💡 EJEMPLOS DE USO

### 1. **Crear un nuevo agente**
```php
// En el controlador
$data = [
    'nombre_completo' => 'Juan Pérez',
    'email' => 'juan.perez@hogarideal.com',
    'telefono' => '098123456',
    'zona_asignada' => 'Punta del Este',
    'tipo_documento' => 1, // CI
    'documento' => '12345678',
    'activo' => 1,
    'imagen_perfil' => 'uploads/abc123_foto.jpg'
];

$agenteModel->create($data);
```

### 2. **Buscar agentes por zona**
```php
// En el modelo
public function getByZona($zona) {
    $stmt = $this->pdo->prepare("SELECT * FROM agente WHERE zona_asignada = ? AND activo = 1");
    $stmt->execute([$zona]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
```

### 3. **Validar email antes de crear**
```php
// En el controlador
if ($this->agenteModel->emailEnUso($_POST['email'])) {
    $msg = 'El email ya está en uso.';
    $tipo = 'error';
    include 'views/agentes/create.php';
    return;
}
```

---

## 🎓 CONCEPTOS PHP APRENDIDOS

### 1. **POO (Programación Orientada a Objetos)**
- **Clases:** `Agente`, `AgenteController`
- **Propiedades:** `$pdo`, `$agenteModel`
- **Métodos:** `getAll()`, `create()`, `update()`, etc.
- **Constructor:** `__construct()`

### 2. **PDO (PHP Data Objects)**
- **Conexión:** `$pdo = new PDO(...)`
- **Prepared Statements:** `$stmt = $pdo->prepare($sql)`
- **Ejecución:** `$stmt->execute($params)`
- **Fetch:** `$stmt->fetch(PDO::FETCH_ASSOC)`

### 3. **Manejo de Archivos**
- **$_FILES:** Array con información del archivo subido
- **move_uploaded_file():** Mover archivo temporal
- **file_exists():** Verificar si existe archivo
- **unlink():** Eliminar archivo

### 4. **Validaciones**
- **Regex:** `preg_match('/^[0-9+]{8,15}$/', $telefono)`
- **Validación única:** Consultas COUNT en base de datos
- **Validación HTML5:** `pattern`, `required`, `type`

### 5. **Seguridad**
- **SQL Injection:** Prepared statements
- **XSS:** `htmlspecialchars()`
- **CSRF:** Tokens (implementar)
- **Validación de archivos:** Tipos MIME y extensiones

### 6. **Patrón MVC**
- **Modelo:** Lógica de negocio y base de datos
- **Vista:** Interfaz de usuario
- **Controlador:** Coordinación entre modelo y vista

---

## 🔧 MÉTODOS NATIVOS DE PHP EXPLICADOS

### 📋 **Métodos de Arrays**

#### 1. **isset()** - Verificar si una variable está definida
```php
// En el controlador
$data = [
    'nombre_completo' => $_POST['nombre_completo'] ?? '',
    'email' => $_POST['email'] ?? '',
];
```
**Explicación:**
- `isset()` verifica si una variable existe y no es NULL
- El operador `??` (null coalescing) es una forma abreviada de `isset() ? $_POST['campo'] : ''`
- Previene errores cuando un campo del formulario no se envía

#### 2. **empty()** - Verificar si una variable está vacía
```php
// En el modelo
if (!empty($agente['imagen_perfil']) && file_exists($agente['imagen_perfil'])) {
    unlink($agente['imagen_perfil']);
}
```
**Explicación:**
- `empty()` retorna true si la variable está vacía, es NULL, false, 0, "", o un array vacío
- Es más estricto que `isset()` porque también verifica si el valor está vacío

#### 3. **array_key_exists()** - Verificar si existe una clave en un array
```php
// Verificar si existe un campo en $_POST
if (array_key_exists('eliminar_imagen', $_POST)) {
    // Procesar eliminación de imagen
}
```

### 🔍 **Métodos de Strings**

#### 1. **trim()** - Eliminar espacios al inicio y final
```php
// En includes/functions.php
function limpiarDatos($datos) {
    $datos = trim($datos);  // Elimina espacios al inicio y final
    $datos = stripslashes($datos);  // Elimina barras invertidas
    $datos = htmlspecialchars($datos);  // Convierte caracteres especiales
    return $datos;
}
```
**Explicación:**
- `trim()` elimina espacios en blanco, tabulaciones y saltos de línea al inicio y final
- Es útil para limpiar datos de entrada del usuario

#### 2. **stripslashes()** - Eliminar barras invertidas
```php
$datos = stripslashes($datos);
```
**Explicación:**
- Elimina barras invertidas (`\`) de una cadena
- Útil cuando magic_quotes_gpc está habilitado (aunque está deprecado en PHP 7+)

#### 3. **htmlspecialchars()** - Convertir caracteres especiales
```php
// En las vistas
echo htmlspecialchars($agente['nombre_completo']);
```
**Explicación:**
- Convierte caracteres especiales en entidades HTML
- Previene ataques XSS (Cross-Site Scripting)
- `<` se convierte en `&lt;`, `>` en `&gt;`, etc.

#### 4. **strtolower()** - Convertir a minúsculas
```php
// En el controlador
$tipoArchivo = strtolower(pathinfo($rutaDestino, PATHINFO_EXTENSION));
```
**Explicación:**
- Convierte todos los caracteres de una cadena a minúsculas
- Útil para normalizar extensiones de archivo

#### 5. **basename()** - Obtener el nombre del archivo
```php
$nombreArchivo = uniqid() . '_' . basename($_FILES['imagen_perfil']['name']);
```
**Explicación:**
- Extrae el nombre del archivo de una ruta completa
- `basename('/path/to/file.jpg')` retorna `file.jpg`

### 📁 **Métodos de Manejo de Archivos**

#### 1. **$_FILES** - Array superglobal para archivos subidos
```php
// Estructura de $_FILES
$_FILES['imagen_perfil'] = [
    'name' => 'foto.jpg',           // Nombre original del archivo
    'type' => 'image/jpeg',         // Tipo MIME
    'tmp_name' => '/tmp/php123',    // Ruta temporal
    'error' => 0,                   // Código de error (0 = sin errores)
    'size' => 1024000               // Tamaño en bytes
];
```

#### 2. **move_uploaded_file()** - Mover archivo subido
```php
if (move_uploaded_file($_FILES['imagen_perfil']['tmp_name'], $rutaDestino)) {
    $data['imagen_perfil'] = $rutaDestino;
}
```
**Explicación:**
- Mueve un archivo subido desde su ubicación temporal a la ubicación final
- Verifica automáticamente que el archivo fue subido por HTTP POST
- Retorna true si se movió correctamente, false en caso contrario

#### 3. **file_exists()** - Verificar si existe un archivo
```php
if (!empty($agente['imagen_perfil']) && file_exists($agente['imagen_perfil'])) {
    unlink($agente['imagen_perfil']);
}
```
**Explicación:**
- Verifica si un archivo o directorio existe
- Retorna true si existe, false si no

#### 4. **unlink()** - Eliminar un archivo
```php
unlink($agente['imagen_perfil']);
```
**Explicación:**
- Elimina un archivo del sistema de archivos
- Retorna true si se eliminó correctamente, false en caso contrario

#### 5. **pathinfo()** - Obtener información de una ruta
```php
$tipoArchivo = strtolower(pathinfo($rutaDestino, PATHINFO_EXTENSION));
```
**Explicación:**
- Extrae información de una ruta de archivo
- `PATHINFO_EXTENSION` obtiene la extensión del archivo
- `PATHINFO_FILENAME` obtiene el nombre sin extensión
- `PATHINFO_DIRNAME` obtiene el directorio

### 🔢 **Métodos de Validación y Expresiones Regulares**

#### 1. **preg_match()** - Buscar coincidencias con regex
```php
if (!preg_match('/^[0-9+]{8,15}$/', $data['telefono'])) {
    $msg = 'El teléfono debe contener solo números y el símbolo +.';
}
```
**Explicación:**
- Busca una coincidencia de una expresión regular en una cadena
- Retorna 1 si encuentra coincidencia, 0 si no, false en caso de error
- `/^[0-9+]{8,15}$/` significa:
  - `^` - Inicio de la cadena
  - `[0-9+]` - Solo números y el símbolo +
  - `{8,15}` - Entre 8 y 15 caracteres
  - `$` - Fin de la cadena

#### 2. **filter_var()** - Filtrar y validar datos
```php
// Validar email (aunque no se usa en el código actual, es una mejor práctica)
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Email inválido
}
```

### 🔄 **Métodos de Control de Flujo**

#### 1. **$_SERVER** - Array con información del servidor
```php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar formulario POST
}
```
**Explicación:**
- `$_SERVER['REQUEST_METHOD']` contiene el método HTTP usado (GET, POST, etc.)
- `$_SERVER['HTTP_HOST']` contiene el nombre del host
- `$_SERVER['REQUEST_URI']` contiene la URI solicitada

#### 2. **$_POST** - Array con datos del formulario POST
```php
$nombre = $_POST['nombre_completo'] ?? '';
```
**Explicación:**
- Contiene todos los datos enviados por método POST
- Es un array asociativo donde las claves son los nombres de los campos

#### 3. **$_GET** - Array con parámetros de la URL
```php
if (isset($_GET['msg'])) {
    echo mostrarMensaje($_GET['msg'], $_GET['tipo'] ?? 'exito');
}
```
**Explicación:**
- Contiene parámetros enviados por método GET (en la URL)
- Ejemplo: `?msg=exito&tipo=info`

### 🎲 **Métodos de Generación de Datos**

#### 1. **uniqid()** - Generar ID único
```php
$nombreArchivo = uniqid() . '_' . basename($_FILES['imagen_perfil']['name']);
```
**Explicación:**
- Genera un identificador único basado en la hora actual
- Útil para crear nombres únicos de archivos
- Retorna algo como: `64f8a1b2c3d4e`

#### 2. **time()** - Timestamp actual
```php
// Aunque no se usa en este código, es útil para timestamps
$timestamp = time(); // Retorna segundos desde Unix epoch
```

### 🔧 **Métodos de Arrays Avanzados**

#### 1. **in_array()** - Buscar valor en array
```php
$tiposPermitidos = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
if (in_array($tipoArchivo, $tiposPermitidos)) {
    // Tipo de archivo permitido
}
```
**Explicación:**
- Busca un valor en un array
- Retorna true si encuentra el valor, false si no
- Opcionalmente puede ser case-sensitive o no

#### 2. **array_merge()** - Combinar arrays
```php
// Aunque no se usa en este código, es útil
$datosCompletos = array_merge($datosBasicos, $datosAdicionales);
```

### 🛡️ **Constantes de PHP**

#### 1. **UPLOAD_ERR_OK** - Constante para error de subida
```php
if ($_FILES['imagen_perfil']['error'] === UPLOAD_ERR_OK) {
    // Archivo subido correctamente
}
```
**Explicación:**
- `UPLOAD_ERR_OK = 0` - Sin errores
- `UPLOAD_ERR_INI_SIZE = 1` - Archivo excede upload_max_filesize
- `UPLOAD_ERR_FORM_SIZE = 2` - Archivo excede MAX_FILE_SIZE
- `UPLOAD_ERR_PARTIAL = 3` - Archivo subido parcialmente
- `UPLOAD_ERR_NO_FILE = 4` - No se subió ningún archivo

### 📊 **Métodos de Base de Datos (PDO)**

#### 1. **prepare()** - Preparar consulta SQL
```php
$stmt = $this->pdo->prepare("SELECT * FROM agente WHERE id_agente = ?");
```
**Explicación:**
- Prepara una consulta SQL para ejecución
- Los `?` son placeholders que se reemplazarán con valores
- Previene SQL injection

#### 2. **execute()** - Ejecutar consulta preparada
```php
$stmt->execute([$id]);
```
**Explicación:**
- Ejecuta una consulta preparada
- Los parámetros se pasan como array
- Retorna true si se ejecutó correctamente

#### 3. **fetch()** - Obtener una fila
```php
$agente = $stmt->fetch(PDO::FETCH_ASSOC);
```
**Explicación:**
- Obtiene la siguiente fila del resultado
- `PDO::FETCH_ASSOC` retorna un array asociativo
- `PDO::FETCH_NUM` retorna un array indexado
- `PDO::FETCH_BOTH` retorna ambos

#### 4. **fetchAll()** - Obtener todas las filas
```php
$agentes = $stmt->fetchAll(PDO::FETCH_ASSOC);
```
**Explicación:**
- Obtiene todas las filas del resultado como array
- Útil cuando esperas múltiples registros

### 🎯 **Operadores Especiales de PHP**

#### 1. **Operador Null Coalescing (??)**
```php
$nombre = $_POST['nombre_completo'] ?? '';
```
**Explicación:**
- Retorna el primer operando si existe y no es NULL
- Si no, retorna el segundo operando
- Equivale a: `isset($_POST['nombre_completo']) ? $_POST['nombre_completo'] : ''`

#### 2. **Operador de Concatenación (.)**
```php
$nombreArchivo = uniqid() . '_' . basename($_FILES['imagen_perfil']['name']);
```
**Explicación:**
- Concatena (une) dos o más cadenas
- Es el operador para unir strings en PHP

#### 3. **Operador de Asignación (.=)**
```php
$sql .= " AND id_agente != ?";
```
**Explicación:**
- Concatena y asigna el resultado a la variable
- Equivale a: `$sql = $sql . " AND id_agente != ?"`

### 🔍 **Métodos de Debugging y Desarrollo**

#### 1. **var_dump()** - Mostrar información de variables
```php
// Para debugging (no usar en producción)
var_dump($agente);
```
**Explicación:**
- Muestra información detallada de una variable
- Incluye tipo, valor y estructura
- Útil para debugging

#### 2. **print_r()** - Mostrar información legible
```php
// Para debugging (no usar en producción)
print_r($agentes);
```
**Explicación:**
- Muestra información de una variable de forma legible
- Menos detallado que var_dump()

#### 3. **error_reporting()** - Configurar reporte de errores
```php
// Configurar para desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

### 📝 **Ejemplos Prácticos de Uso**

#### 1. **Validación completa de formulario**
```php
// Validar todos los campos requeridos
$camposRequeridos = ['nombre_completo', 'email', 'telefono'];
$errores = [];

foreach ($camposRequeridos as $campo) {
    if (empty($_POST[$campo] ?? '')) {
        $errores[] = "El campo $campo es requerido";
    }
}

if (!empty($errores)) {
    // Mostrar errores
    foreach ($errores as $error) {
        echo $error . "<br>";
    }
}
```

#### 2. **Procesamiento seguro de archivos**
```php
function procesarArchivo($archivo, $directorioDestino) {
    // Verificar si se subió correctamente
    if ($archivo['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    // Verificar tipo de archivo
    $tiposPermitidos = ['jpg', 'jpeg', 'png', 'gif'];
    $extension = strtolower(pathinfo($archivo['name'], PATHINFO_EXTENSION));
    
    if (!in_array($extension, $tiposPermitidos)) {
        return false;
    }
    
    // Generar nombre único
    $nombreArchivo = uniqid() . '.' . $extension;
    $rutaDestino = $directorioDestino . '/' . $nombreArchivo;
    
    // Mover archivo
    if (move_uploaded_file($archivo['tmp_name'], $rutaDestino)) {
        return $rutaDestino;
    }
    
    return false;
}
```

#### 3. **Consulta segura con múltiples condiciones**
```php
function buscarAgentes($filtros = []) {
    $sql = "SELECT * FROM agente WHERE 1=1";
    $params = [];
    
    if (!empty($filtros['zona'])) {
        $sql .= " AND zona_asignada = ?";
        $params[] = $filtros['zona'];
    }
    
    if (isset($filtros['activo'])) {
        $sql .= " AND activo = ?";
        $params[] = $filtros['activo'];
    }
    
    if (!empty($filtros['busqueda'])) {
        $sql .= " AND (nombre_completo LIKE ? OR email LIKE ?)";
        $busqueda = '%' . $filtros['busqueda'] . '%';
        $params[] = $busqueda;
        $params[] = $busqueda;
    }
    
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
```

---

## 🚀 MEJORAS FUTURAS

### 1. **Funcionalidades Adicionales**
- Búsqueda avanzada con filtros
- Exportación a PDF/Excel
- Historial de cambios
- Notificaciones por email

### 2. **Optimizaciones**
- Paginación para listas grandes
- Caché de consultas frecuentes
- Compresión de imágenes
- Validación AJAX en tiempo real

### 3. **Seguridad**
- Implementar CSRF tokens
- Logs de auditoría
- Encriptación de datos sensibles
- Rate limiting

---

## 📚 RECURSOS ADICIONALES

### Documentación PHP
- [PDO Documentation](https://www.php.net/manual/en/book.pdo.php)
- [PHP File Upload](https://www.php.net/manual/en/features.file-upload.php)
- [PHP Security](https://www.php.net/manual/en/security.php)

### Patrones de Diseño
- [MVC Pattern](https://en.wikipedia.org/wiki/Model%E2%80%93view%E2%80%93controller)
- [Repository Pattern](https://martinfowler.com/eaaCatalog/repository.html)
- [Active Record Pattern](https://en.wikipedia.org/wiki/Active_record_pattern)

---

*Esta documentación cubre todos los aspectos de la entidad Agente en el sistema Hogar Ideal. Es una guía completa para entender cómo funciona cada componente y cómo se relacionan entre sí.* 