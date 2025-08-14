# Carpeta de Assets

Esta carpeta contiene archivos estáticos del proyecto como imágenes, CSS, JavaScript, etc.

## 📁 Estructura

```
assets/
├── images/           # Imágenes del sistema
│   ├── default-property.jpg    # Imagen por defecto para propiedades sin fotos
│   └── ...                    # Otras imágenes del sistema
├── css/              # Archivos CSS (si los hay)
├── js/               # Archivos JavaScript (si los hay)
└── README.md         # Este archivo
```

## 🖼️ Imágenes por Defecto

### `default-property.jpg`
- **Propósito**: Imagen que se muestra cuando una propiedad no tiene fotos
- **Ubicación**: `assets/images/default-property.jpg`
- **Recomendaciones**:
  - **Tamaño**: 400x300 píxeles o proporción 4:3
  - **Formato**: JPG, PNG o WebP
  - **Peso**: Máximo 200KB para carga rápida
  - **Contenido**: Imagen genérica de casa/propiedad

## 🔧 Cómo Agregar Imágenes

1. **Coloca tu imagen** en la carpeta `assets/images/`
2. **Nombra el archivo** como `default-property.jpg`
3. **Verifica que funcione** visitando la lista de propiedades

## 📝 Notas Importantes

- **Permisos**: Asegúrate de que la carpeta tenga permisos de lectura
- **Formato**: Usa formatos web estándar (JPG, PNG, WebP)
- **Optimización**: Comprime las imágenes para mejor rendimiento
- **Backup**: Incluye las imágenes en el control de versiones

## 🚀 Uso en el Código

La función `getDefaultPropertyImage()` en `includes/functions.php` usa esta imagen:

```php
function getDefaultPropertyImage() {
    return assetUrl('assets/images/default.png');
}
```

## 🔄 Cambiar la Imagen

Para cambiar la imagen por defecto:
1. Reemplaza `default.png` con tu nueva imagen
2. Mantén el mismo nombre de archivo
3. O modifica la función para usar un nombre diferente
