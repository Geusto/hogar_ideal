# 🔄 Función redirect() - Hogar Ideal

## 📋 Tabla de Contenidos

1. [Descripción General](#descripción-general)
2. [Definición y Parámetros](#definición-y-parámetros)
3. [Análisis Detallado](#análisis-detallado)
4. [Ejemplos de Uso](#ejemplos-de-uso)
5. [Casos de Uso Típicos](#casos-de-uso-típicos)
6. [Mejores Prácticas](#mejores-prácticas)

---

## 🎯 Descripción General

La función `redirect()` es un **helper esencial** que maneja las redirecciones en el sistema "Hogar Ideal". Utiliza URLs amigables y puede incluir parámetros adicionales para pasar mensajes o datos entre páginas.

### 📁 Ubicación
- **Archivo**: `includes/functions.php`
- **Dependencias**: `prettyUrl()`, `http_build_query()`
- **Uso**: En controladores para redirecciones

---

## 🔧 Definición y Parámetros

### 📝 Función Completa
```php
/**
 * Redirige a una URL específica
 * @param string $controller Nombre del controlador
 * @param string $action Nombre de la acción
 * @param int|null $id ID del elemento (opcional)
 * @param array $params Parámetros adicionales (opcional)
 */
function redirect($controller, $action = 'index', $id = null, $params = []) {
    $url = prettyUrl($controller, $action, $id);
    // Si hay parámetros adicionales, agrégalos como query string
    if (!empty($params) && is_array($params)) {
        $query = http_build_query($params);
        $url .= (strpos($url, '?') === false ? '?' : '&') . $query;
    }
    header("Location: {$url}");
    exit;
}
```

### 📋 Parámetros

| Parámetro | Tipo | Descripción | Requerido | Valor por Defecto |
|-----------|------|-------------|-----------|-------------------|
| `$controller` | string | Controlador destino | ✅ Sí | - |
| `$action` | string | Acción destino | ❌ No | 'index' |
| `$id` | int/null | ID opcional del elemento | ❌ No | null |
| `$params` | array | Parámetros adicionales | ❌ No | [] |

---

## 🔍 Análisis Detallado

### Paso 1: Generar URL Base
```php
$url = prettyUrl($controller, $action, $id);
```
- Usa la función `prettyUrl()` para generar la URL amigable
- Ejemplo: `prettyUrl('propiedades', 'crear')` → `/propiedades/crear`

### Paso 2: Procesar Parámetros Adicionales
```php
if (!empty($params) && is_array($params)) {
    $query = http_build_query($params);
    $url .= (strpos($url, '?') === false ? '?' : '&') . $query;
}
```

#### Verificación de Parámetros:
- `!empty($params)`: Verifica que el array no esté vacío
- `is_array($params)`: Confirma que sea un array válido

#### Conversión a Query String:
- `http_build_query($params)`: Convierte el array en cadena de consulta
- Ejemplo: `['mensaje' => 'exito', 'tipo' => 'crear']` → `mensaje=exito&tipo=crear`

#### Construcción de URL:
- **Lógica**: Si la URL NO tiene `?`, añade `?`, sino añade `&`
- **Ejemplos**:
  - `/propiedades/crear` + parámetros → `/propiedades/crear?mensaje=exito`
  - `/propiedades/crear?pagina=1` + parámetros → `/propiedades/crear?pagina=1&mensaje=exito`

### Paso 3: Ejecutar Redirección
```php
header("Location: {$url}");
exit;
```
- `header("Location: ...")`: Envía el header HTTP de redirección
- `exit`: Termina la ejecución del script inmediatamente

---

## 📊 Ejemplos de Uso

### 1. Redirección Simple
```php
// Redirigir a la lista de propiedades
redirect('propiedades', 'index');
// → Redirige a /propiedades
```

### 2. Redirección con ID
```php
// Redirigir a editar una propiedad específica
redirect('propiedades', 'editar', 123);
// → Redirige a /propiedades/editar/123
```

### 3. Redirección con Mensaje de Éxito
```php
// Después de crear una propiedad exitosamente
redirect('propiedades', 'index', null, [
    'mensaje' => 'Propiedad creada exitosamente'
]);
// → Redirige a /propiedades?mensaje=Propiedad%20creada%20exitosamente
```

### 4. Redirección con Múltiples Parámetros
```php
// Redirección con mensaje y tipo de operación
redirect('propiedades', 'crear', null, [
    'mensaje' => 'Datos guardados',
    'tipo' => 'nueva_propiedad',
    'timestamp' => time()
]);
// → Redirige a /propiedades/crear?mensaje=Datos%20guardados&tipo=nueva_propiedad&timestamp=1234567890
```

### 5. Redirección con Error
```php
// Cuando hay un error en la validación
redirect('propiedades', 'crear', null, [
    'error' => 'Datos inválidos',
    'datos' => json_encode($datos_anteriores)
]);
// → Redirige a /propiedades/crear?error=Datos%20inválidos&datos=%7B%22titulo%22%3A%22%22%7D
```

---

## 🎯 Casos de Uso Típicos

### 1. Después de Crear un Registro
```php
// En PropiedadController.php
public function crear() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $datos = $this->validarDatos($_POST);
        
        if ($datos) {
            if ($this->propiedadModel->create($datos)) {
                redirect('propiedades', 'index', null, [
                    'mensaje' => 'Propiedad creada correctamente'
                ]);
            } else {
                redirect('propiedades', 'crear', null, [
                    'error' => 'Error al crear la propiedad'
                ]);
            }
        } else {
            redirect('propiedades', 'crear', null, [
                'error' => 'Datos inválidos',
                'datos' => json_encode($_POST)
            ]);
        }
    }
    
    include 'views/propiedades/create.php';
}
```

### 2. Después de Actualizar
```php
// En PropiedadController.php
public function editar($id) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $datos = $this->validarDatos($_POST);
        
        if ($datos) {
            if ($this->propiedadModel->update($id, $datos)) {
                redirect('propiedades', 'editar', $id, [
                    'mensaje' => 'Propiedad actualizada correctamente'
                ]);
            } else {
                redirect('propiedades', 'editar', $id, [
                    'error' => 'Error al actualizar la propiedad'
                ]);
            }
        }
    }
    
    $propiedad = $this->propiedadModel->getById($id);
    include 'views/propiedades/edit.php';
}
```

### 3. Después de Eliminar
```php
// En PropiedadController.php
public function eliminar($id) {
    if ($this->propiedadModel->delete($id)) {
        redirect('propiedades', 'index', null, [
            'mensaje' => 'Propiedad eliminada correctamente',
            'tipo' => 'eliminacion'
        ]);
    } else {
        redirect('propiedades', 'index', null, [
            'error' => 'Error al eliminar la propiedad'
        ]);
    }
}
```

### 4. Redirección con Errores de Validación
```php
private function validarDatos($datos) {
    $errores = [];
    
    if (empty($datos['titulo'])) {
        $errores[] = 'El título es obligatorio';
    }
    
    if (empty($datos['precio']) || !is_numeric($datos['precio'])) {
        $errores[] = 'El precio debe ser un número válido';
    }
    
    if (empty($datos['direccion'])) {
        $errores[] = 'La dirección es obligatoria';
    }
    
    if (!empty($errores)) {
        redirect('propiedades', 'crear', null, [
            'error' => implode(', ', $errores),
            'datos' => json_encode($datos)
        ]);
        return false;
    }
    
    return $datos;
}
```

### 5. Redirección con Confirmación
```php
// En AgenteController.php
public function cambiarEstado($id) {
    $agente = $this->agenteModel->getById($id);
    $nuevoEstado = $agente['activo'] ? 0 : 1;
    
    if ($this->agenteModel->updateEstado($id, $nuevoEstado)) {
        $mensaje = $nuevoEstado ? 'Agente activado' : 'Agente desactivado';
        redirect('agentes', 'index', null, [
            'mensaje' => $mensaje,
            'tipo' => 'estado'
        ]);
    } else {
        redirect('agentes', 'index', null, [
            'error' => 'Error al cambiar el estado del agente'
        ]);
    }
}
```

---

## 🔧 Mejores Prácticas

### 1. Siempre Usar `exit` Después de `redirect()`
```php
// ✅ Correcto
redirect('propiedades', 'index');
// El exit está dentro de la función redirect()

// ❌ Incorrecto - Nunca hacer esto
redirect('propiedades', 'index');
echo "Este código nunca se ejecutará";
```

### 2. Validar Datos Antes de Redirigir
```php
// ✅ Correcto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = $this->validarDatos($_POST);
    if ($datos) {
        // Procesar datos válidos
        redirect('propiedades', 'index', null, ['mensaje' => 'Éxito']);
    }
    // Si no son válidos, redirect() ya se ejecutó en validarDatos()
}

// ❌ Incorrecto
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Procesar sin validar
    redirect('propiedades', 'index');
}
```

### 3. Usar Parámetros Específicos
```php
// ✅ Correcto - Parámetros específicos
redirect('propiedades', 'index', null, [
    'mensaje' => 'Propiedad creada',
    'tipo' => 'creacion'
]);

// ❌ Incorrecto - Parámetros genéricos
redirect('propiedades', 'index', null, [
    'data' => 'some_value'
]);
```

### 4. Manejar Errores con Try-Catch
```php
// ✅ Correcto
try {
    $resultado = $this->modelo->operacion();
    if ($resultado) {
        redirect('controlador', 'accion', null, ['mensaje' => 'Éxito']);
    } else {
        redirect('controlador', 'accion', null, ['error' => 'Error en la operación']);
    }
} catch (Exception $e) {
    redirect('controlador', 'accion', null, ['error' => $e->getMessage()]);
}

// ❌ Incorrecto
$resultado = $this->modelo->operacion();
redirect('controlador', 'accion');
```

### 5. Usar Constantes para Mensajes
```php
// ✅ Correcto - Constantes definidas
class PropiedadController {
    const MSG_CREATED = 'Propiedad creada correctamente';
    const MSG_UPDATED = 'Propiedad actualizada correctamente';
    const MSG_DELETED = 'Propiedad eliminada correctamente';
    
    public function crear() {
        // ... lógica ...
        redirect('propiedades', 'index', null, [
            'mensaje' => self::MSG_CREATED
        ]);
    }
}

// ❌ Incorrecto - Strings hardcodeados
redirect('propiedades', 'index', null, [
    'mensaje' => 'Propiedad creada correctamente'
]);
```

---

## 🔍 Solución de Problemas

### Redirección No Funciona
1. **Verificar headers**: Asegúrate de que no se haya enviado output antes
2. **Verificar prettyUrl()**: Confirma que la función genere URLs válidas
3. **Verificar permisos**: El servidor debe poder enviar headers

### Headers Already Sent
```php
// ❌ Problema - Output antes de redirect
echo "Algún texto";
redirect('propiedades', 'index');

// ✅ Solución - Sin output antes de redirect
redirect('propiedades', 'index');
```

### URLs Incorrectas
```php
// ❌ Problema - Parámetros incorrectos
redirect('propiedades', 'editar', 'abc'); // ID debe ser numérico

// ✅ Solución - Parámetros correctos
redirect('propiedades', 'editar', 123);
```

---

## 📊 Comparación con Otras Alternativas

### vs `header()` Directo
```php
// ❌ Sin función helper
header("Location: /propiedades/crear?mensaje=exito");
exit;

// ✅ Con función helper
redirect('propiedades', 'crear', null, ['mensaje' => 'exito']);
```

### vs JavaScript
```php
// ❌ JavaScript (menos confiable)
echo "<script>window.location.href = '/propiedades';</script>";

// ✅ PHP redirect (más confiable)
redirect('propiedades', 'index');
```

---

## 🎓 Conclusión

La función `redirect()` proporciona:

1. **Consistencia**: Todas las redirecciones usan el mismo patrón
2. **URLs amigables**: Usa `prettyUrl()` internamente
3. **Parámetros flexibles**: Puede incluir mensajes, errores, etc.
4. **Seguridad**: `exit` asegura que no se ejecute código después
5. **Mantenibilidad**: Centraliza la lógica de redirección

### Ventajas Principales:
- **Simplicidad**: Una sola función para todas las redirecciones
- **Flexibilidad**: Soporte para parámetros adicionales
- **Seguridad**: Terminación automática del script
- **Consistencia**: URLs amigables en toda la aplicación

Para más información sobre implementaciones específicas, consulta:
- [Funciones Helper de URL](url-helpers.md)
- [Documentación de Entidades](04-entities/)
- [Guía MVC](02-mvc-pattern.md) 