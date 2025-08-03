# 💬 Función mostrarMensaje() - Hogar Ideal

## 📋 Tabla de Contenidos

1. [Descripción General](#descripción-general)
2. [Definición y Parámetros](#definición-y-parámetros)
3. [Análisis Detallado](#análisis-detallado)
4. [Tipos de Mensajes](#tipos-de-mensajes)
5. [Ejemplos de Uso](#ejemplos-de-uso)
6. [Características del Toast](#características-del-toast)
7. [Mejores Prácticas](#mejores-prácticas)

---

## 🎯 Descripción General

La función `mostrarMensaje()` es un **helper esencial** que genera notificaciones tipo "toast" para mostrar feedback al usuario. Crea mensajes visuales atractivos con diferentes estilos según el tipo de mensaje (éxito, error, advertencia, info).

### 📁 Ubicación
- **Archivo**: `includes/functions.php`
- **Dependencias**: Font Awesome (iconos), Tailwind CSS (estilos)
- **Uso**: En controladores y vistas para mostrar feedback

---

## 🔧 Definición y Parámetros

### 📝 Función Completa
```php
/**
 * Muestra un mensaje tipo toast al usuario
 * @param string $mensaje El mensaje a mostrar
 * @param string $tipo El tipo de mensaje (exito, error, advertencia, info)
 * @return string HTML del toast
 */
function mostrarMensaje($mensaje, $tipo = 'info') {
    $icon = '';
    $bgColor = 'bg-blue-100';
    $textColor = 'text-blue-800';
    $borderColor = 'border-blue-500';

    switch($tipo) {
        case 'exito':
            $icon = '<i class="fas fa-check-circle mr-2 text-green-500"></i>';
            $bgColor = 'bg-green-100';
            $textColor = 'text-green-700';
            $borderColor = 'border-green-500';
            break;
        case 'error':
            $icon = '<i class="fas fa-times-circle mr-2 text-red-500"></i>';
            $bgColor = 'bg-red-100';
            $textColor = 'text-red-700';
            $borderColor = 'border-red-500';
            break;
        case 'advertencia':
            $icon = '<i class="fas fa-exclamation-triangle mr-2 text-yellow-500"></i>';
            $bgColor = 'bg-yellow-100';
            $textColor = 'text-yellow-700';
            $borderColor = 'border-yellow-500';
            break;
        default:
            $icon = '<i class="fas fa-info-circle mr-2 text-blue-500"></i>';
            $bgColor = 'bg-blue-100';
            $textColor = 'text-blue-700';
            $borderColor = 'border-blue-500';
            break;
    }

    return "
    <div id='toast-msg' class='fixed bottom-6 right-6 z-50 min-w-[250px] max-w-xs $bgColor $borderColor border-l-4 $textColor p-4 mb-4 shadow-lg flex items-center animate-slide-in rounded-lg' role='alert'>
        $icon
        <span class='font-semibold flex-1'>" . htmlspecialchars($mensaje) . "</span>
        <button onclick=\"document.getElementById('toast-msg').style.display='none'\" class='ml-4 text-xl font-bold'>&times;</button>
    </div>
    <style>
        @keyframes slide-in {
            from { opacity: 0; transform: translateX(100px);}
            to { opacity: 1; transform: translateX(0);}
        }
        .animate-slide-in { animation: slide-in 0.5s; }
    </style>
    <script>
        setTimeout(() => {
            const toast = document.getElementById('toast-msg');
            if(toast) toast.style.display = 'none';
        }, 4000);
    </script>
    ";
}
```

### 📋 Parámetros

| Parámetro | Tipo | Descripción | Requerido | Valor por Defecto |
|-----------|------|-------------|-----------|-------------------|
| `$mensaje` | string | El texto del mensaje a mostrar | ✅ Sí | - |
| `$tipo` | string | Tipo de mensaje | ❌ No | 'info' |

---

## 🔍 Análisis Detallado

### Paso 1: Inicialización de Variables
```php
$icon = '';
$bgColor = 'bg-blue-100';
$textColor = 'text-blue-800';
$borderColor = 'border-blue-500';
```
- Se inicializan las variables con valores por defecto (info)
- `$icon`: Icono de Font Awesome
- `$bgColor`: Color de fondo de Tailwind CSS
- `$textColor`: Color del texto
- `$borderColor`: Color del borde izquierdo

### Paso 2: Configuración por Tipo
```php
switch($tipo) {
    case 'exito':
        $icon = '<i class="fas fa-check-circle mr-2 text-green-500"></i>';
        $bgColor = 'bg-green-100';
        $textColor = 'text-green-700';
        $borderColor = 'border-green-500';
        break;
    // ... otros casos
}
```
- **Éxito**: Verde con icono de check
- **Error**: Rojo con icono de X
- **Advertencia**: Amarillo con icono de triángulo
- **Info**: Azul con icono de información

### Paso 3: Generación del HTML
```php
return "
<div id='toast-msg' class='fixed bottom-6 right-6 z-50 min-w-[250px] max-w-xs $bgColor $borderColor border-l-4 $textColor p-4 mb-4 shadow-lg flex items-center animate-slide-in rounded-lg' role='alert'>
    $icon
    <span class='font-semibold flex-1'>" . htmlspecialchars($mensaje) . "</span>
    <button onclick=\"document.getElementById('toast-msg').style.display='none'\" class='ml-4 text-xl font-bold'>&times;</button>
</div>
";
```

#### Clases CSS Utilizadas:
- `fixed bottom-6 right-6`: Posición fija en la esquina inferior derecha
- `z-50`: Alto z-index para estar por encima de otros elementos
- `min-w-[250px] max-w-xs`: Ancho mínimo y máximo
- `shadow-lg`: Sombra pronunciada
- `animate-slide-in`: Animación de entrada

### Paso 4: CSS y JavaScript
```php
<style>
    @keyframes slide-in {
        from { opacity: 0; transform: translateX(100px);}
        to { opacity: 1; transform: translateX(0);}
    }
    .animate-slide-in { animation: slide-in 0.5s; }
</style>
<script>
    setTimeout(() => {
        const toast = document.getElementById('toast-msg');
        if(toast) toast.style.display = 'none';
    }, 4000);
</script>
```

#### Características:
- **Animación**: Desliza desde la derecha
- **Auto-ocultar**: Desaparece después de 4 segundos
- **Botón de cerrar**: Permite cerrar manualmente

---

## 🎨 Tipos de Mensajes

### 1. Mensaje de Éxito
```php
echo mostrarMensaje('Propiedad creada exitosamente', 'exito');
```
**Resultado**: Toast verde con icono de check ✓

### 2. Mensaje de Error
```php
echo mostrarMensaje('Error al guardar los datos', 'error');
```
**Resultado**: Toast rojo con icono de X ✗

### 3. Mensaje de Advertencia
```php
echo mostrarMensaje('Algunos campos están incompletos', 'advertencia');
```
**Resultado**: Toast amarillo con icono de triángulo ⚠

### 4. Mensaje Informativo
```php
echo mostrarMensaje('Cargando datos...', 'info');
// o simplemente
echo mostrarMensaje('Cargando datos...');
```
**Resultado**: Toast azul con icono de información ℹ

---

## 📊 Ejemplos de Uso

### 1. En Controladores (Después de Operaciones)
```php
// PropiedadController.php
public function crear() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($this->propiedadModel->create($_POST)) {
            echo mostrarMensaje('Propiedad creada exitosamente', 'exito');
        } else {
            echo mostrarMensaje('Error al crear la propiedad', 'error');
        }
    }
    include 'views/propiedades/create.php';
}
```

### 2. En Vistas (Para Validaciones)
```php
<!-- views/propiedades/create.php -->
<?php if (isset($_GET['error'])): ?>
    <?php echo mostrarMensaje($_GET['error'], 'error'); ?>
<?php endif; ?>

<?php if (isset($_GET['mensaje'])): ?>
    <?php echo mostrarMensaje($_GET['mensaje'], 'exito'); ?>
<?php endif; ?>
```

### 3. En JavaScript (Para Interacciones)
```php
// En una vista con JavaScript
<script>
function eliminarPropiedad(id) {
    if (confirm('¿Estás seguro de eliminar esta propiedad?')) {
        fetch(`/propiedades/eliminar/${id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar mensaje de éxito
                    document.body.insertAdjacentHTML('beforeend', 
                        '<?php echo mostrarMensaje("Propiedad eliminada", "exito"); ?>'
                    );
                } else {
                    // Mostrar mensaje de error
                    document.body.insertAdjacentHTML('beforeend', 
                        '<?php echo mostrarMensaje("Error al eliminar", "error"); ?>'
                    );
                }
            });
    }
}
</script>
```

### 4. En Formularios (Validación en Tiempo Real)
```php
<!-- views/agentes/create.php -->
<form id="agenteForm" onsubmit="return validarFormulario()">
    <!-- campos del formulario -->
</form>

<script>
function validarFormulario() {
    const email = document.getElementById('email').value;
    const telefono = document.getElementById('telefono').value;
    
    if (!email || !telefono) {
        document.body.insertAdjacentHTML('beforeend', 
            '<?php echo mostrarMensaje("Todos los campos son obligatorios", "advertencia"); ?>'
        );
        return false;
    }
    
    return true;
}
</script>
```

---

## 🎯 Características del Toast

### 📍 Posicionamiento
- **Posición**: Esquina inferior derecha
- **Z-index**: 50 (por encima de otros elementos)
- **Responsive**: Se adapta a diferentes tamaños de pantalla

### 🎨 Estilos Visuales
- **Fondo**: Colores suaves según el tipo
- **Borde**: Borde izquierdo de color distintivo
- **Sombra**: Sombra pronunciada para destacar
- **Iconos**: Font Awesome con colores específicos

### ⚡ Interactividad
- **Animación**: Desliza desde la derecha
- **Auto-ocultar**: Desaparece automáticamente en 4 segundos
- **Botón cerrar**: Permite cerrar manualmente
- **Accesibilidad**: Atributo `role="alert"`

### 🔒 Seguridad
- **Escape HTML**: `htmlspecialchars()` para prevenir XSS
- **Sanitización**: El mensaje se escapa antes de mostrar

---

## 📊 Comparación de Tipos

| Tipo | Color | Icono | Uso Típico |
|------|-------|-------|------------|
| **éxito** | Verde | ✓ | Operaciones exitosas |
| **error** | Rojo | ✗ | Errores y fallos |
| **advertencia** | Amarillo | ⚠ | Validaciones y advertencias |
| **info** | Azul | ℹ | Información general |

---

## 🔧 Mejores Prácticas

### 1. Usar Tipos Apropiados
```php
// ✅ Correcto - Tipo específico
echo mostrarMensaje('Datos guardados correctamente', 'exito');
echo mostrarMensaje('Campo obligatorio faltante', 'error');
echo mostrarMensaje('Verificando datos...', 'info');

// ❌ Incorrecto - Tipo genérico para todo
echo mostrarMensaje('Operación completada'); // Siempre azul
```

### 2. Mensajes Claros y Concisos
```php
// ✅ Correcto - Mensaje claro
echo mostrarMensaje('Propiedad creada exitosamente', 'exito');

// ❌ Incorrecto - Mensaje vago
echo mostrarMensaje('OK', 'exito');
```

### 3. Combinar con Redirecciones
```php
// ✅ Correcto - Mensaje en la página de destino
if ($this->propiedadModel->create($datos)) {
    redirect('propiedades', 'index', null, [
        'mensaje' => 'Propiedad creada exitosamente'
    ]);
}

// En la vista de destino
<?php if (isset($_GET['mensaje'])): ?>
    <?php echo mostrarMensaje($_GET['mensaje'], 'exito'); ?>
<?php endif; ?>
```

### 4. Manejar Múltiples Mensajes
```php
// ✅ Correcto - Mostrar múltiples mensajes
$mensajes = [];
if (isset($_GET['error'])) $mensajes[] = ['texto' => $_GET['error'], 'tipo' => 'error'];
if (isset($_GET['mensaje'])) $mensajes[] = ['texto' => $_GET['mensaje'], 'tipo' => 'exito'];

foreach ($mensajes as $msg) {
    echo mostrarMensaje($msg['texto'], $msg['tipo']);
}
```

### 5. Personalizar Tiempo de Auto-ocultar
```php
// ✅ Correcto - Modificar el tiempo según el tipo
function mostrarMensajePersonalizado($mensaje, $tipo = 'info', $tiempo = 4000) {
    $toast = mostrarMensaje($mensaje, $tipo);
    return str_replace('4000', $tiempo, $toast);
}

// Mensajes de error más largos
echo mostrarMensajePersonalizado('Error complejo', 'error', 8000);
```

---

## 🔍 Solución de Problemas

### Toast No Se Muestra
1. **Verificar Font Awesome**: Asegúrate de que esté incluido
2. **Verificar Tailwind CSS**: Confirma que esté cargado
3. **Verificar JavaScript**: Revisa la consola del navegador

### Toast Se Superpone
```php
// ✅ Solución - Usar z-index más alto
<div style="z-index: 9999;">
    <?php echo mostrarMensaje('Mensaje importante', 'error'); ?>
</div>
```

### Múltiples Toasts
```php
// ✅ Solución - Limpiar toasts anteriores
<script>
function mostrarToast(mensaje, tipo) {
    // Eliminar toasts existentes
    const toasts = document.querySelectorAll('#toast-msg');
    toasts.forEach(toast => toast.remove());
    
    // Mostrar nuevo toast
    document.body.insertAdjacentHTML('beforeend', 
        '<?php echo mostrarMensaje("' + mensaje + '", "' + tipo + '"); ?>'
    );
}
</script>
```

---

## 📊 Comparación con Otras Alternativas

### vs Alert() de JavaScript
```php
// ❌ JavaScript básico
alert('Propiedad creada');

// ✅ Toast personalizado
echo mostrarMensaje('Propiedad creada exitosamente', 'exito');
```

### vs Bootstrap Toast
```php
// ❌ Bootstrap (requiere librería adicional)
<div class="toast" role="alert">
    <div class="toast-header">
        <strong class="me-auto">Notificación</strong>
        <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
    </div>
    <div class="toast-body">Mensaje aquí</div>
</div>

// ✅ Función helper personalizada
echo mostrarMensaje('Mensaje aquí', 'exito');
```

---

## 🎓 Conclusión

La función `mostrarMensaje()` proporciona:

1. **Consistencia visual**: Todos los mensajes tienen el mismo estilo
2. **Feedback inmediato**: Notificaciones claras al usuario
3. **Flexibilidad**: Diferentes tipos según el contexto
4. **Accesibilidad**: Atributos ARIA apropiados
5. **Auto-gestión**: Se oculta automáticamente

### Ventajas Principales:
- **Simplicidad**: Una función para todos los mensajes
- **Personalización**: Diferentes estilos según el tipo
- **Responsive**: Se adapta a cualquier dispositivo
- **Seguridad**: Escape automático de HTML
- **UX mejorada**: Animaciones y auto-ocultar

Para más información sobre implementaciones específicas, consulta:
- [Funciones Helper de URL](url-helpers.md)
- [Sistema de Redirecciones](redirect.md)
- [Documentación de Entidades](04-entities/) 