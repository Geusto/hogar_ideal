# 🚀 Guía de Inicio - Hogar Ideal

## 📋 Tabla de Contenidos

1. [Requisitos del Sistema](#requisitos-del-sistema)
2. [Instalación](#instalación)
3. [Configuración](#configuración)
4. [Estructura del Proyecto](#estructura-del-proyecto)
5. [Primeros Pasos](#primeros-pasos)

---

## 🖥️ Requisitos del Sistema

### Software Necesario:
- **PHP**: 8.1 o superior
- **MySQL**: 8.0 o superior
- **Apache**: Con mod_rewrite habilitado
- **Composer**: Para gestión de dependencias (opcional)

### Extensiones PHP Requeridas:
- `pdo_mysql`
- `mbstring`
- `fileinfo`
- `gd` (para procesamiento de imágenes)

---

## ⚙️ Instalación

### Paso 1: Clonar el Repositorio
```bash
git clone [URL_DEL_REPOSITORIO]
cd hogar-ideal
```

### Paso 2: Configurar el Servidor Web
Asegúrate de que el directorio raíz del proyecto esté configurado como el directorio web de tu servidor Apache.

### Paso 3: Configurar Base de Datos
1. Crear una base de datos MySQL llamada `hogar_ideal`
2. Importar el archivo `db/hogar_ideal.sql`

```bash
mysql -u root -p
CREATE DATABASE hogar_ideal;
USE hogar_ideal;
SOURCE db/hogar_ideal.sql;
```

---

## 🔧 Configuración

### Archivo de Configuración de Base de Datos
Edita el archivo `config/database.php`:

```php
<?php
// Configuración de base de datos
$host = 'localhost';
$dbname = 'hogar_ideal';
$username = 'tu_usuario';
$password = 'tu_password';
$charset = 'utf8mb4';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=$charset", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Error de conexión: " . $e->getMessage());
}
?>
```

### Configuración del .htaccess
El archivo `.htaccess` ya está configurado para:
- Redirigir todas las peticiones a `index.php`
- Permitir URLs amigables
- Proteger archivos sensibles

```apache
RewriteEngine On

# Redirigir todas las peticiones a index.php
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?url=$1 [QSA,L]

# Permitir acceso a archivos estáticos
RewriteCond %{REQUEST_URI} !\.(css|js|png|jpg|jpeg|gif|ico|pdf)$
```

---

## 📁 Estructura del Proyecto

```
hogar ideal/
├── config/
│   └── database.php              # Configuración de base de datos
├── controllers/
│   ├── HomeController.php         # Controlador del dashboard
│   ├── PropiedadController.php    # Controlador de propiedades
│   ├── ClienteController.php      # Controlador de clientes
│   ├── AgenteController.php       # Controlador de agentes
│   └── VentaController.php        # Controlador de ventas
├── models/
│   ├── Propiedad.php             # Modelo de propiedades
│   ├── Cliente.php               # Modelo de clientes
│   ├── Agente.php                # Modelo de agentes
│   └── Venta.php                 # Modelo de ventas
├── views/
│   ├── layouts/
│   │   ├── main.php              # Layout principal
│   │   └── modal_confirmacion.php # Modal de confirmación
│   ├── errors/
│   │   └── 404.php               # Página de error 404
│   ├── home/
│   │   └── index.php             # Dashboard principal
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
│   └── functions.php             # Funciones auxiliares y helpers
├── uploads/                      # Directorio para archivos subidos
├── public/                       # Archivos públicos
├── db/
│   └── hogar_ideal.sql          # Script de base de datos
├── docs/                         # Documentación del proyecto
├── index.php                     # Punto de entrada principal
└── .htaccess                     # Configuración de Apache
```

---

## 🎯 Primeros Pasos

### 1. Verificar la Instalación
Accede a tu navegador y visita:
```
http://localhost/hogar-ideal/
```

Deberías ver el dashboard principal con estadísticas.

### 2. Probar las Funcionalidades
- **Propiedades**: Navega a `/propiedades` para ver la lista
- **Agentes**: Navega a `/agentes` para gestionar agentes
- **Clientes**: Navega a `/clientes` para gestionar clientes

### 3. Crear Datos de Prueba
El sistema incluye datos de ejemplo, pero puedes:
- Crear nuevas propiedades
- Añadir agentes
- Registrar clientes

### 4. Verificar URLs Amigables
Prueba estas URLs para verificar que funcionan:
- `/propiedades/crear`
- `/agentes/editar/1`
- `/clientes/ver/1`

---

## 🔍 Solución de Problemas Comunes

### Error de Conexión a Base de Datos
- Verifica las credenciales en `config/database.php`
- Asegúrate de que MySQL esté ejecutándose
- Confirma que la base de datos existe

### URLs No Funcionan
- Verifica que mod_rewrite esté habilitado en Apache
- Confirma que el archivo `.htaccess` esté presente
- Revisa los logs de error de Apache

### Imágenes No Se Muestran
- Verifica permisos en la carpeta `uploads/`
- Confirma que las rutas de las imágenes sean correctas

### Errores 404
- Verifica que el archivo `index.php` esté en la raíz
- Confirma que las rutas en los controladores sean correctas

---

## 📚 Próximos Pasos

Una vez que tengas el sistema funcionando:

1. **Lee la documentación**: Revisa los archivos en la carpeta `docs/`
2. **Explora el código**: Familiarízate con la estructura MVC
3. **Personaliza**: Adapta el sistema a tus necesidades
4. **Contribuye**: Mejora el código y documentación

Para más información, consulta:
- [Guía MVC](02-mvc-pattern.md)
- [Esquema de Base de Datos](03-database-schema.md)
- [Documentación de Entidades](04-entities/) 