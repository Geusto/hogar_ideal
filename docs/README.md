# 📚 Documentación Completa - Hogar Ideal

## 🏠 Sistema de Gestión Inmobiliaria

Bienvenido a la documentación completa del sistema "Hogar Ideal", una aplicación web desarrollada en PHP siguiendo el patrón MVC para la gestión inmobiliaria.

---

## 📋 Índice de Documentación

### 🚀 Guías de Inicio
- **[01-getting-started.md](01-getting-started.md)** - Guía de instalación y configuración
- **[02-mvc-pattern.md](02-mvc-pattern.md)** - Explicación del patrón MVC
- **[03-database-schema.md](03-database-schema.md)** - Esquema completo de base de datos

### 🏗️ Entidades del Sistema
- **[04-entities/agente.md](04-entities/agente.md)** - Documentación de la entidad Agente
- **[04-entities/propiedad.md](04-entities/propiedad.md)** - Documentación de la entidad Propiedad *(próximamente)*
- **[04-entities/cliente.md](04-entities/cliente.md)** - Documentación de la entidad Cliente *(próximamente)*
- **[04-entities/venta.md](04-entities/venta.md)** - Documentación de la entidad Venta *(próximamente)*
- **[04-entities/foto-propiedad.md](04-entities/foto-propiedad.md)** - Documentación del sistema de galería de fotos *(próximamente)*

### 🔧 Funciones Helper
- **[05-functions/url-helpers.md](05-functions/url-helpers.md)** - Funciones de generación de URLs
- **[05-functions/redirect.md](05-functions/redirect.md)** - Sistema de redirecciones
- **[05-functions/mostrar-mensaje.md](05-functions/mostrar-mensaje.md)** - Sistema de notificaciones toast
- **[05-functions/busqueda.md](05-functions/busqueda.md)** - Sistema de búsqueda y filtros *(próximamente)*

### 📄 Reportes y PDF
- **[06-reportes/generacion-pdf.md](06-reportes/generacion-pdf.md)** - Generación de reportes PDF con mPDF

### 🔒 Seguridad y Configuración
- **[07-seguridad/gitignore-configuracion.md](07-seguridad/gitignore-configuracion.md)** - Configuración de seguridad y manejo de credenciales

---

## 🎯 Características Principales

### ✨ Funcionalidades Implementadas
- **Gestión completa de propiedades** (CRUD) con sistema de galería de fotos múltiples
- **Sistema de agentes inmobiliarios** con zonas asignadas
- **Gestión de clientes** (compradores y vendedores)
- **Sistema de ventas** con comisiones y seguimiento
- **Programación de visitas** a propiedades
- **Sistema de búsqueda avanzada** por texto, tipo y estado
- **Interfaz moderna** con Tailwind CSS
- **URLs amigables** para mejor SEO
- **Sistema de mensajes** para feedback al usuario
- **Gestión de archivos** con validación de seguridad

### 🛠️ Tecnologías Utilizadas
- **Backend**: PHP 8.1+
- **Base de Datos**: MySQL 8.0
- **Frontend**: HTML5, CSS3, JavaScript
- **Framework CSS**: Tailwind CSS 2.2.19
- **Patrón**: MVC (Model-View-Controller)
- **Servidor**: Apache con mod_rewrite

---

## 🚀 Inicio Rápido

### 1. Instalación
```bash
# Clonar el repositorio
git clone [URL_DEL_REPOSITORIO]
cd hogar-ideal

# Configurar base de datos
mysql -u root -p
CREATE DATABASE hogar_ideal;
USE hogar_ideal;
SOURCE db/hogar_ideal.sql;
```

### 2. Configuración
Editar `config/database.php` con tus credenciales:
```php
$host = 'localhost';
$dbname = 'hogar_ideal';
$username = 'tu_usuario';
$password = 'tu_password';
```

### 3. Acceso
Visitar `http://localhost/hogar-ideal/` en tu navegador.

---

## 📁 Estructura del Proyecto

```
hogar ideal/
├── config/
│   └── database.php              # Configuración de base de datos
├── controllers/
│   ├── HomeController.php         # Dashboard principal
│   ├── PropiedadController.php    # Gestión de propiedades
│   ├── ClienteController.php      # Gestión de clientes
│   ├── AgenteController.php       # Gestión de agentes
│   └── VentaController.php        # Gestión de ventas
├── models/
│   ├── Propiedad.php             # Modelo de propiedades
│   ├── Cliente.php               # Modelo de clientes
│   ├── Agente.php                # Modelo de agentes
│   ├── Venta.php                 # Modelo de ventas
│   └── FotoPropiedad.php         # Modelo de galería de fotos
├── views/
│   ├── layouts/
│   │   ├── main.php              # Layout principal
│   │   └── modal_confirmacion.php # Modal de confirmación
│   ├── home/
│   │   └── index.php             # Dashboard
│   ├── propiedades/
│   │   ├── index.php             # Lista de propiedades con búsqueda
│   │   ├── create.php            # Formulario de creación
│   │   ├── edit.php              # Formulario de edición con gestión de fotos
│   │   └── show.php              # Vista detallada con galería
│   ├── clientes/
│   │   ├── createCliente.php     # Formulario de creación
│   │   ├── editCliente.php       # Formulario de edición
│   │   └── viewCliente.php       # Vista de cliente
│   └── agentes/
│       ├── create.php            # Formulario de creación
│       ├── edit.php              # Formulario de edición
│       └── index.php             # Lista de agentes
├── includes/
│   └── functions.php             # Funciones auxiliares y helpers
├── uploads/                      # Archivos subidos (fotos de propiedades)
├── db/
│   ├── hogar_ideal.sql          # Script de base de datos principal
│   └── crear_tabla_fotos.sql    # Script para tabla de fotos
├── docs/                         # Documentación del proyecto
├── index.php                     # Punto de entrada principal
└── .htaccess                     # Configuración de Apache para URLs amigables
```

---

## 🔗 URLs del Sistema

### Rutas Principales
- **Dashboard**: `/` o `/home`
- **Propiedades**: `/propiedad`
- **Crear Propiedad**: `/propiedad/create`
- **Editar Propiedad**: `/propiedad/edit/123`
- **Agentes**: `/agente`
- **Clientes**: `/cliente`

### Ejemplos de URLs Amigables
```php
// Generar URLs
prettyUrl('propiedad', 'create')     // → /propiedad/create
prettyUrl('propiedad', 'edit', 123)  // → /propiedad/edit/123
prettyUrl('agente', 'show', 5)       // → /agente/show/5

// Redirecciones
redirect('propiedad', 'index')       // → Redirige a /propiedad
redirect('propiedad', 'create', null, ['msg' => 'Éxito'])
```

---

## 🗄️ Base de Datos

### Entidades Principales
1. **agente** - Agentes inmobiliarios con zonas asignadas
2. **cliente** - Compradores y vendedores
3. **propiedad** - Propiedades inmobiliarias con sistema de fotos
4. **fotos_propiedad** - Galería de fotos múltiples por propiedad
5. **venta** - Transacciones de venta con comisiones
6. **visita** - Visitas programadas a propiedades

### Relaciones
- Un agente puede gestionar múltiples propiedades
- Un cliente puede ser comprador y/o vendedor
- Una propiedad tiene un vendedor y un agente asignado
- Una propiedad puede tener múltiples fotos (una como portada)
- Una venta involucra propiedad, comprador, vendedor y agente
- Las visitas conectan clientes, propiedades y agentes

---

## 🎨 Patrón MVC

### Modelo (Models)
- Maneja la lógica de negocio
- Comunica con la base de datos
- Valida datos internamente
- **Nuevo**: Sistema de gestión de fotos múltiples

### Vista (Views)
- Presenta la información al usuario
- No contiene lógica de negocio
- Usa datos pasados por el controlador
- **Nuevo**: Modal de gestión de fotos y galería responsive

### Controlador (Controllers)
- Recibe peticiones HTTP
- Coordina entre modelo y vista
- Valida datos de entrada
- Maneja redirecciones
- **Nuevo**: Sistema de búsqueda y gestión de archivos

---

## 🔧 Funciones Helper

### Generación de URLs
```php
// URLs tradicionales
url('propiedad', 'create')
// → index.php?controller=propiedad&action=create

// URLs amigables
prettyUrl('propiedad', 'create')
// → /propiedad/create
```

### Redirecciones
```php
// Redirección simple
redirect('propiedad', 'index');

// Redirección con mensaje
redirect('propiedad', 'index', null, [
    'msg' => 'Propiedad creada exitosamente'
]);
```

### Sistema de Búsqueda
```php
// Búsqueda por texto en direcciones y nombres
// Filtros por tipo y estado de propiedad
// Combinación de búsqueda y filtros
```

---

## 🛡️ Seguridad

### Validación de Datos
- Todos los datos de entrada se validan
- Uso de prepared statements
- Escape de datos de salida con `htmlspecialchars()`

### Gestión de Archivos
- Validación de tipos de archivo permitidos
- Límites de tamaño de archivo
- Nombres únicos para evitar conflictos
- Sanitización de nombres de archivo

### URLs Seguras
- Sistema de URLs amigables oculta la estructura
- Validación de parámetros en controladores
- Manejo de errores 404

---

## 📈 Características Avanzadas

### Sistema de Mensajes
```php
// En controladores
redirect('propiedad', 'index', null, [
    'msg' => 'Operación exitosa'
]);

// En vistas
<?php if (isset($_GET['msg'])): ?>
    <div class="alert alert-success">
        <?php echo htmlspecialchars($_GET['msg']); ?>
    </div>
<?php endif; ?>
```

### Búsqueda y Filtros
- **Búsqueda por texto**: En direcciones, nombres de clientes y agentes
- **Filtros por tipo**: Casa, apartamento, terreno, local comercial
- **Filtros por estado**: Disponible, vendida
- **Combinación**: Búsqueda + filtros simultáneos

### Sistema de Galería de Fotos
- **Fotos múltiples**: Hasta 10 fotos por propiedad
- **Foto de portada**: Una foto destacada por propiedad
- **Gestión visual**: Modal con drag & drop
- **Validación**: Tipos de archivo seguros y límites de tamaño

---

## 🔍 Solución de Problemas

### Problemas Comunes

#### Búsqueda No Funciona
1. Verificar que el formulario apunte a `propiedad/search`
2. Confirmar que el controlador `search()` esté implementado
3. Verificar que el modelo tenga el método `search()`
4. Revisar la construcción de URLs en los filtros

#### URLs No Funcionan
1. Verificar que mod_rewrite esté habilitado
2. Confirmar que el archivo `.htaccess` esté presente
3. Revisar los logs de error de Apache

#### Error de Conexión a Base de Datos
1. Verificar credenciales en `config/database.php`
2. Asegurar que MySQL esté ejecutándose
3. Confirmar que la base de datos existe

#### Imágenes No Se Muestran
1. Verificar permisos en la carpeta `uploads/`
2. Confirmar que las rutas sean correctas
3. Verificar que los archivos existan
4. Comprobar que la tabla `fotos_propiedad` esté creada

#### Fotos No Se Suben
1. Verificar permisos de carpeta `uploads/`
2. Comprobar límites de `upload_max_filesize` en PHP
3. Verificar tipos de archivo permitidos
4. Confirmar que el formulario tenga `enctype="multipart/form-data"`

---

## 🤝 Contribuir

### Mejoras Sugeridas
1. **Sistema de usuarios**: Autenticación y autorización
2. **Reportes avanzados**: Generación de reportes de ventas y comisiones
3. **API REST**: Para integración con aplicaciones móviles
4. **Notificaciones**: Sistema de notificaciones por email
5. **Dashboard**: Más estadísticas y gráficos
6. **Búsqueda avanzada**: Filtros por precio, superficie, ubicación

### Estándares de Código
- Seguir el patrón MVC
- Usar funciones helper para URLs y redirecciones
- Validar todos los datos de entrada
- Documentar nuevas funcionalidades
- Mantener consistencia en nombres (usar "propiedad" no "propiedades")

---

## 📞 Soporte

### Recursos Adicionales
- **PHP Documentation**: https://www.php.net/docs.php
- **MySQL Documentation**: https://dev.mysql.com/doc/
- **Tailwind CSS**: https://tailwindcss.com/docs
- **Apache mod_rewrite**: https://httpd.apache.org/docs/current/mod/mod_rewrite.html

### Contacto
Para soporte técnico o preguntas sobre el sistema, consulta la documentación específica en cada archivo o crea un issue en el repositorio.

---

## 📄 Licencia

Este proyecto está desarrollado como sistema educativo y de demostración. Puede ser usado libremente para fines de aprendizaje y desarrollo.

---

**¡Gracias por usar Hogar Ideal! 🏠✨** 