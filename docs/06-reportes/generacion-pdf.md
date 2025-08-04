# 📄 Generación de Reportes PDF - mPDF

## 🎯 Descripción

Este documento describe la implementación de generación de reportes PDF en el sistema Hogar Ideal utilizando la librería **mPDF**.

## 📦 Instalación

### **1. Instalar mPDF via Composer**
```bash
composer require mpdf/mpdf
```

### **2. Verificar Instalación**
```bash
composer show mpdf/mpdf
```

## 🏗️ Arquitectura

### **Estructura de Archivos**
```
controllers/
├── VentaController.php          # Lógica de generación de PDFs
models/
├── Venta.php                   # Datos de ventas
views/
├── ventas/
│   └── index.php              # Vista con botón de PDF
```

### **Flujo de Generación**
1. **Usuario hace clic** en botón "Generar PDF"
2. **Controlador** recibe la petición
3. **Modelo** obtiene datos de la venta
4. **mPDF** genera el documento
5. **Descarga automática** del archivo

## 🔧 Implementación

### **Controlador (VentaController.php)**

```php
public function comprobantePDF($id) {
    $venta = $this->ventaModel->getById($id);
    
    if (!$venta) {
        redirect('ventas', 'index', null, ['error' => 'Venta no encontrada']);
        return;
    }
    
    $this->generarComprobantePDF($venta);
}

private function generarComprobantePDF($venta) {
    try {
        // Crear instancia de mPDF
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 16,
            'margin_bottom' => 16,
        ]);
        
        // Configurar metadatos
        $mpdf->SetTitle('Comprobante de Venta #' . $venta['id_venta']);
        $mpdf->SetAuthor('Sistema Hogar Ideal');
        $mpdf->SetCreator('Hogar Ideal');
        
        // Generar HTML del comprobante
        $html = $this->generarHTMLComprobante($venta);
        
        // Escribir HTML en el PDF
        $mpdf->WriteHTML($html);
        
        // Generar archivo
        $filename = 'comprobante_venta_' . str_pad($venta['id_venta'], 6, '0', STR_PAD_LEFT) . '.pdf';
        $mpdf->Output($filename, 'D');
        
    } catch (Exception $e) {
        redirect('ventas', 'index', null, ['error' => 'Error al generar PDF: ' . $e->getMessage()]);
    }
}
```

### **Vista (views/ventas/index.php)**

```php
<a href="<?= prettyUrl('venta', 'comprobantePDF', $venta['id_venta']) ?>" 
   class="text-green-500 hover:text-green-700 mr-3" 
   title="Generar comprobante PDF" 
   target="_blank">
    <i class="fas fa-file-pdf"></i>
</a>
```

## 🎨 Características del PDF

### **Diseño del Comprobante**
- **Header** con logo y número de venta
- **Información de la propiedad** (dirección, tipo)
- **Partes intervinientes** (vendedor, comprador, agente)
- **Detalles financieros** (precio, comisión)
- **Total destacado** con formato especial
- **Footer** con información del sistema

### **Emojis como Imágenes**
```html
<img src="https://cdn.jsdelivr.net/gh/twitter/twemoji@latest/assets/svg/1f3e0.svg" class="emoji-img">
```

### **Estilos CSS**
```css
.emoji-img { width: 18px; height: 18px; vertical-align: middle; margin: 0 3px; }
.emoji-large { width: 25px; height: 25px; }
.info-box { background-color: #f9fafb; padding: 10px; border-radius: 5px; }
```

## 📋 Tipos de Reportes

### **1. Comprobante de Venta**
- **Ruta:** `/venta/comprobantePDF/{id}`
- **Contenido:** Detalles completos de la transacción
- **Uso:** Documento oficial para clientes

### **2. Reporte de Propiedades** *(Próximamente)*
- **Ruta:** `/propiedad/reportePDF`
- **Contenido:** Lista de propiedades con filtros
- **Uso:** Análisis de inventario

### **3. Estadísticas de Agentes** *(Próximamente)*
- **Ruta:** `/agente/estadisticasPDF`
- **Contenido:** Rendimiento y comisiones
- **Uso:** Evaluación de agentes

## 🔍 Solución de Problemas

### **Error: "Class 'Mpdf' not found"**
```bash
# Verificar instalación
composer require mpdf/mpdf

# Limpiar autoloader
composer dump-autoload
```

### **Error: "Cannot modify header information"**
- Asegurar que no hay salida antes de `$mpdf->Output()`
- Verificar que no hay espacios en blanco antes de `<?php`

### **Emojis no se muestran**
- Usar imágenes SVG desde CDN
- Verificar conexión a internet
- Considerar símbolos Unicode como alternativa

### **PDF no se descarga**
- Verificar permisos de escritura
- Comprobar configuración del navegador
- Revisar logs de error de PHP

## 🚀 Mejoras Futuras

### **Funcionalidades Planificadas**
1. **Múltiples formatos** (A4, Letter, Legal)
2. **Plantillas personalizables** por agente
3. **Firma digital** en comprobantes
4. **Envío por email** automático
5. **Almacenamiento** en servidor
6. **Historial** de documentos generados

### **Optimizaciones**
1. **Caché** de plantillas HTML
2. **Compresión** de imágenes
3. **Fuentes personalizadas** locales
4. **Watermarks** de seguridad

## 📚 Referencias

### **Documentación Oficial**
- [mPDF Documentation](https://mpdf.github.io/)
- [mPDF GitHub](https://github.com/mpdf/mpdf)

### **Recursos Adicionales**
- [Twemoji CDN](https://twemoji.twitter.com/) - Emojis SVG
- [Unicode Symbols](https://unicode-table.com/) - Símbolos alternativos

---

**Última actualización:** Agosto 2025
**Versión:** 1.0
**Autor:** Sistema Hogar Ideal 