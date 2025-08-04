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

### 🔧 Funciones Helper
- **[05-functions/url-helpers.md](05-functions/url-helpers.md)** - Funciones de generación de URLs
- **[05-functions/redirect.md](05-functions/redirect.md)** - Sistema de redirecciones
- **[05-functions/mostrar-mensaje.md](05-functions/mostrar-mensaje.md)** - Sistema de notificaciones toast

### 📄 Reportes y PDF
- **[06-reportes/generacion-pdf.md](06-reportes/generacion-pdf.md)** - Generación de reportes PDF con mPDF

### 🔒 Seguridad y Configuración
- **[07-seguridad/gitignore-configuracion.md](07-seguridad/gitignore-configuracion.md)** - Configuración de seguridad y manejo de credenciales

---

## 🎯 Características Principales

### ✨ Funcionalidades Implementadas
- **Gestión completa de propiedades** (CRUD)
- **Sistema de agentes inmobiliarios**
- **Gestión de clientes** (compradores y vendedores)
- **Sistema de ventas** con comisiones
- **Programación de visitas** a propiedades
- **Interfaz moderna** con Tailwind CSS
- **URLs amigables** para mejor SEO
- **Sistema de mensajes** para feedback al usuario

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
│   └── Venta.php                 # Modelo de ventas
├── views/
│   ├── layouts/
│   │   ├── main.php              # Layout principal
│   │   └── modal_confirmacion.php # Modal de confirmación
│   ├── home/
│   │   └── index.php             # Dashboard
│   ├── propiedades/
│   │   ├── index.php             # Lista de propiedades
│   │   ├── create.php            # Formulario de creación
│   │   ├── edit.php              # Formulario de edición
│   │   └── show.php              # Vista detallada
│   ├── clientes/
│   │   ├── createCliente.php     # Formulario de creación
│   │   ├── editCliente.php       # Formulario de edición
│   │   └── viewCliente.php       # Vista de cliente
│   └── agentes/
│       ├── create.php            # Formulario de creación
│       ├── edit.php              # Formulario de edición
│       └── index.php             # Lista de agentes
├── includes/
│   └── functions.php             # Funciones auxiliares
├── uploads/                      # Archivos subidos
├── db/
│   └── hogar_ideal.sql          # Script de base de datos
├── docs/                         # Documentación del proyecto
├── index.php                     # Punto de entrada
└── .htaccess                     # Configuración de Apache
```

---

## 🔗 URLs del Sistema

### Rutas Principales
- **Dashboard**: `/` o `/home`
- **Propiedades**: `/propiedades`
- **Crear Propiedad**: `/propiedades/crear`
- **Editar Propiedad**: `/propiedades/editar/123`
- **Agentes**: `/agentes`
- **Clientes**: `/clientes`

### Ejemplos de URLs Amigables
```php
// Generar URLs
prettyUrl('propiedades', 'crear')     // → /propiedades/crear
prettyUrl('propiedades', 'editar', 123) // → /propiedades/editar/123
prettyUrl('agentes', 'ver', 5)        // → /agentes/ver/5

// Redirecciones
redirect('propiedades', 'index')       // → Redirige a /propiedades
redirect('propiedades', 'crear', null, ['mensaje' => 'Éxito'])
```

---

## 🗄️ Base de Datos

### Entidades Principales
1. **agente** - Agentes inmobiliarios
2. **cliente** - Compradores y vendedores
3. **propiedad** - Propiedades inmobiliarias
4. **venta** - Transacciones de venta
5. **visita** - Visitas programadas

### Relaciones
- Un agente puede gestionar múltiples propiedades
- Un cliente puede ser comprador y/o vendedor
- Una propiedad tiene un vendedor y un agente asignado
- Una venta involucra propiedad, comprador, vendedor y agente
- Las visitas conectan clientes, propiedades y agentes

---

## 🎨 Patrón MVC

### Modelo (Models)
- Maneja la lógica de negocio
- Comunica con la base de datos
- Valida datos internamente

### Vista (Views)
- Presenta la información al usuario
- No contiene lógica de negocio
- Usa datos pasados por el controlador

### Controlador (Controllers)
- Recibe peticiones HTTP
- Coordina entre modelo y vista
- Valida datos de entrada
- Maneja redirecciones

---

## 🔧 Funciones Helper

### Generación de URLs
```php
// URLs tradicionales
url('propiedades', 'crear')
// → index.php?controller=propiedades&action=crear

// URLs amigables
prettyUrl('propiedades', 'crear')
// → /propiedades/crear
```

### Redirecciones
```php
// Redirección simple
redirect('propiedades', 'index');

// Redirección con mensaje
redirect('propiedades', 'index', null, [
    'mensaje' => 'Propiedad creada exitosamente'
]);
```

---

## 🛡️ Seguridad

### Validación de Datos
- Todos los datos de entrada se validan
- Uso de prepared statements
- Escape de datos de salida con `htmlspecialchars()`

### URLs Seguras
- Sistema de URLs amigables oculta la estructura
- Validación de parámetros en controladores
- Manejo de errores 404

---

## 📈 Características Avanzadas

### Sistema de Mensajes
```php
// En controladores
redirect('propiedades', 'index', null, [
    'mensaje' => 'Operación exitosa'
]);

// En vistas
<?php if (isset($_GET['mensaje'])): ?>
    <div class="alert alert-success">
        <?php echo htmlspecialchars($_GET['mensaje']); ?>
    </div>
<?php endif; ?>
```

### Filtros y Búsqueda
- Filtros por tipo de propiedad
- Búsqueda en tiempo real
- Ordenamiento por precio, fecha, etc.

### Gestión de Archivos
- Subida de imágenes de propiedades
- Validación de tipos de archivo
- Almacenamiento seguro en carpeta `uploads/`

---

## 🔍 Solución de Problemas

### Problemas Comunes

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

---

## 🤝 Contribuir

### Mejoras Sugeridas
1. **Nuevas entidades**: Añadir más tipos de propiedades
2. **Reportes**: Generar reportes de ventas y comisiones
3. **API**: Crear API REST para integración
4. **Notificaciones**: Sistema de notificaciones por email
5. **Dashboard**: Más estadísticas y gráficos

### Estándares de Código
- Seguir el patrón MVC
- Usar funciones helper para URLs y redirecciones
- Validar todos los datos de entrada
- Documentar nuevas funcionalidades

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