# Galería de Fotos para Propiedades

## Descripción General

Este documento describe la implementación de un sistema de galería de fotos para las propiedades, que reemplaza el sistema anterior de una sola foto de portada.

## Arquitectura de la Base de Datos

### Tabla `fotos_propiedad`

```sql
CREATE TABLE `fotos_propiedad` (
  `id_foto` int NOT NULL AUTO_INCREMENT,
  `id_propiedad` int NOT NULL,
  `nombre_archivo` varchar(255) NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `orden` int DEFAULT 0,
  `es_portada` tinyint(1) DEFAULT 0,
  `fecha_creacion` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_foto`),
  KEY `id_propiedad` (`id_propiedad`),
  CONSTRAINT `fotos_propiedad_ibfk_1` FOREIGN KEY (`id_propiedad`) REFERENCES `propiedad` (`id_propiedad`) ON DELETE CASCADE
);

-- Índices para optimización
CREATE INDEX idx_propiedad_orden ON fotos_propiedad(id_propiedad, orden);
CREATE INDEX idx_es_portada ON fotos_propiedad(es_portada);
```

### Campos Explicados

- **`id_foto`**: Identificador único de la foto (AUTO_INCREMENT)
- **`id_propiedad`**: Referencia a la propiedad (Foreign Key con CASCADE)
- **`nombre_archivo`**: Nombre del archivo físico en el servidor
- **`descripcion`**: Descripción opcional de la foto
- **`orden`**: Orden de visualización de las fotos
- **`es_portada`**: Indica si la foto es la portada (0=no, 1=sí)
- **`fecha_creacion`**: Timestamp de cuando se subió la foto

## Estructura de Archivos

### Modelo: `models/FotoPropiedad.php`

```php
class FotoPropiedad {
    // Métodos principales:
    - getByPropiedad($idPropiedad)     // Obtener todas las fotos de una propiedad
    - getPortada($idPropiedad)         // Obtener la foto de portada
    - create($data)                    // Crear nueva foto
    - update($idFoto, $data)           // Actualizar foto existente
    - delete($idFoto)                  // Eliminar foto
    - setPortada($idFoto, $idPropiedad) // Establecer foto como portada
    - quitarPortada($idFoto, $idPropiedad) // Quitar estado de portada
    - reordenar($idPropiedad, $orden)  // Reordenar fotos
}
```

### Controlador: `controllers/PropiedadController.php`

```php
// Nuevos métodos agregados:
- uploadFotos($idPropiedad)            // Subir múltiples fotos
- deleteFoto($idFoto)                  // Eliminar foto específica
- setPortada($idFoto)                  // Establecer foto como portada
- quitarPortada($idFoto)               // Quitar estado de portada
```

### Vista: `views/propiedades/edit.php`

- **Modal de gestión de fotos**: Interfaz para administrar fotos
- **Galería de fotos**: Visualización de todas las fotos con indicadores
- **Acciones por foto**: Botones para cambiar portada, quitar portada y eliminar
- **Subida de fotos**: Formulario con drag & drop para nuevas fotos

## Funcionalidades Implementadas

### 1. Subida de Fotos Múltiples

- **Drag & Drop**: Soporte para arrastrar y soltar archivos
- **Selección múltiple**: Permite seleccionar varias fotos a la vez
- **Validación**: Verifica tipo de archivo, tamaño y formato
- **Nombres únicos**: Genera nombres únicos para evitar conflictos
- **Portada automática**: La primera foto subida se convierte en portada

### 2. Gestión de Portada

- **Indicador visual**: Estrella azul para hacer portada, amarilla para quitar
- **Una sola portada**: Solo una foto puede ser portada por propiedad
- **Cambio dinámico**: Cambio inmediato sin recargar la página
- **Validación**: Previene múltiples portadas

### 3. Eliminación de Fotos

- **Confirmación**: Diálogo de confirmación antes de eliminar
- **Limpieza automática**: Elimina archivo físico y registro de BD
- **Integridad**: Mantiene consistencia entre archivos y base de datos

### 4. Visualización

- **Galería responsive**: Grid adaptativo para diferentes tamaños de pantalla
- **Thumbnails**: Miniaturas optimizadas para carga rápida
- **Indicadores**: Marcadores claros para foto de portada
- **Descripciones**: Muestra descripciones de fotos cuando están disponibles

## URLs y Enrutamiento

### Sistema de URLs Amigables

El sistema utiliza URLs amigables con el siguiente patrón:

```
/propiedad/accion/id
```

### Ejemplos de URLs

- **Subir fotos**: `/propiedad/uploadFotos/5`
- **Cambiar portada**: `/propiedad/setPortada/12`
- **Quitar portada**: `/propiedad/quitarPortada/12`
- **Eliminar foto**: `/propiedad/deleteFoto/12`

### Configuración .htaccess

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]
```

## Consideraciones Técnicas

### Seguridad

- **Validación de archivos**: Solo permite tipos de imagen seguros
- **Sanitización**: Nombres de archivo seguros
- **Límites de tamaño**: Prevención de archivos demasiado grandes
- **Autenticación**: Verificación de permisos antes de operaciones

### Rendimiento

- **Índices de BD**: Optimización de consultas frecuentes
- **Thumbnails**: Generación de miniaturas para carga rápida
- **Lazy loading**: Carga diferida de imágenes
- **Compresión**: Optimización de archivos de imagen

### Mantenimiento

- **Cascade delete**: Eliminación automática de fotos al eliminar propiedad
- **Logs de operaciones**: Registro de acciones para auditoría
- **Backup automático**: Respaldo de archivos y base de datos

## Uso del Sistema

### Para Agentes/Administradores

1. **Editar Propiedad** → Botón "Gestionar Fotos"
2. **Subir Fotos**: Arrastrar archivos o usar selector
3. **Gestionar Portada**: Hacer clic en estrella azul/amarilla
4. **Eliminar Fotos**: Usar botón de papelera
5. **Guardar Cambios**: Los cambios se aplican inmediatamente

### Para Visitantes

1. **Ver Propiedad** → Galería de fotos visible
2. **Foto de Portada**: Primera imagen destacada
3. **Navegación**: Scroll horizontal por todas las fotos
4. **Responsive**: Adaptación automática a dispositivos móviles

## Troubleshooting

### Problemas Comunes

1. **Fotos no se suben**
   - Verificar permisos de carpeta `uploads/`
   - Comprobar límites de `upload_max_filesize` en PHP
   - Verificar tipos de archivo permitidos

2. **Error al cambiar portada**
   - Verificar que la foto existe en la base de datos
   - Comprobar permisos de usuario
   - Revisar logs de errores de PHP

3. **Problemas de visualización**
   - Verificar que las rutas de archivos son correctas
   - Comprobar permisos de lectura de archivos
   - Verificar configuración de servidor web

### Logs y Debug

- **Logs de PHP**: `C:/laragon/tmp/php_errors.log`
- **Logs del servidor**: Logs de Apache/Nginx
- **Console del navegador**: Errores JavaScript y AJAX

## Próximas Mejoras

### Funcionalidades Planificadas

1. **Ordenamiento**: Drag & drop para reordenar fotos
2. **Edición**: Recorte y ajustes básicos de imagen
3. **Categorías**: Etiquetado de fotos por tipo (exterior, interior, etc.)
4. **Zoom**: Vista ampliada de fotos
5. **Slideshow**: Presentación automática de fotos

### Optimizaciones Técnicas

1. **CDN**: Distribución de contenido para mejor rendimiento
2. **Compresión**: Optimización automática de imágenes
3. **Cache**: Sistema de caché para fotos frecuentemente accedidas
4. **API**: Endpoints REST para integración con aplicaciones móviles

## Conclusión

La implementación de la galería de fotos proporciona una experiencia de usuario significativamente mejorada, permitiendo a los agentes mostrar múltiples aspectos de cada propiedad y a los visitantes obtener una visión más completa antes de tomar decisiones.

El sistema está diseñado para ser escalable, mantenible y seguro, siguiendo las mejores prácticas de desarrollo web moderno.
