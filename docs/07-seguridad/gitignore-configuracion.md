# 🔒 Seguridad y Configuración de Git

## 🎯 Descripción

Esta guía explica cómo configurar correctamente la seguridad del proyecto, especialmente el manejo de credenciales y archivos sensibles mediante `.gitignore`.

## 🛡️ Problema de Seguridad

### **❌ Situación Peligrosa (ANTES)**

```php
// config/database.php (archivo real con credenciales)
$host = 'localhost';
$dbname = 'hogar_ideal';
$username = 'mi_usuario_real';     // ← CREDENCIALES REALES
$password = 'mi_password_real';     // ← CREDENCIALES REALES
```

**⚠️ PROBLEMA:** Si este archivo se sube al repositorio, **TODOS** pueden ver tus credenciales reales de la base de datos.

### **✅ Solución Implementada (DESPUÉS)**

#### **1. Protección en `.gitignore`:**
```gitignore
# Configuración de base de datos (puede contener credenciales)
config/database.php
```

**✅ RESULTADO:** Git **IGNORARÁ** completamente el archivo `config/database.php`.

#### **2. Archivo de Ejemplo Seguro:**
```php
// config/database.example.php (archivo de ejemplo)
$host = 'localhost';
$dbname = 'hogar_ideal';
$username = 'tu_usuario';      // ← VALORES DE EJEMPLO
$password = 'tu_password';      // ← VALORES DE EJEMPLO
```

## 📁 Estructura de Archivos Segura

```
hogar-ideal/
├── config/
│   ├── database.example.php    ← ✅ SE SUBE (plantilla)
│   └── database.php           ← ❌ NO SE SUBE (ignorado)
├── .gitignore                 ← ✅ SE SUBE (configuración)
└── README.md                  ← ✅ SE SUBE (instrucciones)
```

## 🔧 Configuración del .gitignore

### **Archivos y Carpetas Protegidos:**

#### **🔒 Seguridad:**
```gitignore
# Dependencias de Composer
/vendor/
composer.phar

# Configuración de base de datos
config/database.php

# Archivos de configuración local
.env
.env.local
.env.production
.env.staging
```

#### **🗂️ Sistema:**
```gitignore
# Archivos subidos por usuarios
uploads/
uploads/*

# Archivos generados dinámicamente
*.pdf
*.log
logs/
cache/
tmp/
temp/
```

#### **💻 Desarrollo:**
```gitignore
# Configuración de IDE
.vscode/
.idea/
*.sublime-project
*.sublime-workspace

# Archivos de prueba
test-*.php
*_test.php
test/
tests/
```

#### **🖥️ Sistema Operativo:**
```gitignore
# Windows
Thumbs.db
ehthumbs.db
Desktop.ini
$RECYCLE.BIN/
*.lnk

# macOS
.DS_Store
.AppleDouble
.LSOverride
Icon
._*

# Linux
*~
.fuse_hidden*
.directory
.Trash-*
.nfs*
```

## 🚀 Flujo de Trabajo Seguro

### **Para Nuevos Desarrolladores:**

```bash
# 1. Clonar repositorio
git clone [URL_DEL_REPOSITORIO]
cd hogar-ideal

# 2. Instalar dependencias
composer install

# 3. Copiar archivo de configuración
cp config/database.example.php config/database.php

# 4. Editar con credenciales reales
nano config/database.php
# o
code config/database.php
```

### **Para Verificar Configuración:**

```bash
# Verificar que config/database.php NO se suba
git status
# No debe aparecer config/database.php

# Verificar que SÍ se suba el ejemplo
git add config/database.example.php
git status
# Debe aparecer config/database.example.php

# Ver archivos ignorados
git status --ignored
```

## ⚠️ Reglas de Seguridad

### **❌ NUNCA hagas esto:**
```bash
# Subir credenciales reales
git add config/database.php
git commit -m "Configuración de BD"
git push
```

### **✅ SIEMPRE haz esto:**
```bash
# Solo subir el ejemplo
git add config/database.example.php
git commit -m "Agregar plantilla de configuración"
git push
```

## 🎯 Beneficios de esta Configuración

### **🔐 Seguridad:**
- **Credenciales protegidas** - Nunca se exponen
- **Acceso controlado** - Solo desarrolladores autorizados
- **Sin riesgo de filtración** - Incluso si el repositorio es público

### **👥 Colaboración:**
- **Fácil configuración** - Instrucciones claras en README
- **Plantilla disponible** - `database.example.php` como guía
- **Sin conflictos** - Cada desarrollador tiene su propia configuración

### **🚀 Deployment:**
- **Flexibilidad** - Diferentes configuraciones por entorno
- **Automatización** - Scripts pueden copiar y configurar automáticamente
- **Escalabilidad** - Fácil de replicar en múltiples servidores

## 📋 Checklist de Seguridad

### **Antes de hacer Commit:**
- [ ] Verificar que `config/database.php` NO esté en el staging area
- [ ] Verificar que `config/database.example.php` SÍ esté actualizado
- [ ] Revisar que no haya credenciales reales en ningún archivo
- [ ] Verificar que `.gitignore` esté configurado correctamente

### **Antes de hacer Push:**
- [ ] Ejecutar `git status` y verificar archivos
- [ ] Revisar que no haya archivos sensibles
- [ ] Verificar que las dependencias estén en `.gitignore`

## 🔍 Comandos Útiles

### **Verificar Archivos Ignorados:**
```bash
# Ver todos los archivos ignorados
git status --ignored

# Ver qué archivos se ignorarían
git check-ignore *
```

### **Verificar Configuración:**
```bash
# Ver configuración de git
git config --list

# Ver configuración de .gitignore
git check-ignore -v config/database.php
```

### **Limpiar Archivos No Deseados:**
```bash
# Remover archivos del staging area
git reset HEAD config/database.php

# Limpiar archivos no trackeados
git clean -n  # Ver qué se eliminaría
git clean -f  # Eliminar archivos no trackeados
```

## 🚨 Casos de Emergencia

### **Si accidentalmente subiste credenciales:**

```bash
# 1. Cambiar credenciales inmediatamente
# 2. Remover del historial de git
git filter-branch --force --index-filter \
  "git rm --cached --ignore-unmatch config/database.php" \
  --prune-empty --tag-name-filter cat -- --all

# 3. Forzar push para limpiar el repositorio
git push origin --force --all
```

### **Si necesitas compartir configuración temporalmente:**

```bash
# Crear archivo temporal con configuración
cp config/database.php config/database.temp.php

# Agregar al .gitignore temporalmente
echo "config/database.temp.php" >> .gitignore

# Compartir archivo por método seguro (no git)
# Usar: email, mensaje privado, USB, etc.
```

## 📚 Referencias

### **Documentación Oficial:**
- [Git Documentation](https://git-scm.com/doc)
- [Git Ignore Patterns](https://git-scm.com/docs/gitignore)

### **Buenas Prácticas:**
- [GitHub Security Best Practices](https://docs.github.com/en/github/authenticating-to-github/keeping-your-account-and-data-secure)
- [OWASP Security Guidelines](https://owasp.org/www-project-top-ten/)

---

**Última actualización:** Agosto 2025  
**Versión:** 1.0  
**Autor:** Sistema Hogar Ideal 