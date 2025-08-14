# Manual de Git para Trabajo Colaborativo

## Índice
1. [¿Qué es Git y por qué lo necesitamos?](#qué-es-git)
2. [Configuración inicial](#configuración-inicial)
3. [Conceptos básicos](#conceptos-básicos)
4. [Flujo de trabajo diario](#flujo-de-trabajo-diario)
5. [Trabajo colaborativo](#trabajo-colaborativo)
6. [Resolución de conflictos](#resolución-de-conflictos)
7. [Buenas prácticas](#buenas-prácticas)
8. [Comandos útiles](#comandos-útiles)
9. [Ejemplos prácticos](#ejemplos-prácticos)

---

## ¿Qué es Git? {#qué-es-git}

Git es un sistema de control de versiones que permite:
- **Rastrear cambios** en tu código
- **Trabajar en equipo** sin sobrescribir el trabajo de otros
- **Volver atrás** si algo sale mal
- **Mantener un historial** de todas las modificaciones

**Imagina Git como una máquina del tiempo para tu código:**
- Cada vez que guardas cambios, creas un "punto de restauración"
- Puedes volver a cualquier momento anterior
- Múltiples personas pueden trabajar en el mismo proyecto

---

## Configuración inicial {#configuración-inicial}

### 1. Instalar Git
- **Windows**: Descarga desde [git-scm.com](https://git-scm.com)
- **macOS**: `brew install git`
- **Linux**: `sudo apt-get install git`

### 2. Configurar tu identidad
```bash
# Configurar tu nombre y email (solo la primera vez)
git config --global user.name "Tu Nombre"
git config --global user.email "tu.email@ejemplo.com"

# Verificar la configuración
git config --list
```

### 3. Configurar el repositorio remoto
```bash
# Si es la primera vez que clonas el proyecto
git clone https://github.com/tu-usuario/hogar-ideal.git

# Si ya tienes el proyecto, agregar el origen remoto
git remote add origin https://github.com/tu-usuario/hogar-ideal.git
```

---

## Conceptos básicos {#conceptos-básicos}

### Áreas de Git
```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   Working       │    │   Staging       │    │   Repository    │
│   Directory     │───▶│   Area          │───▶│   (Local)       │
│   (Archivos)    │    │   (Preparado)   │    │   (Commits)     │
└─────────────────┘    └─────────────────┘    └─────────────────┘
```

1. **Working Directory**: Donde editas archivos
2. **Staging Area**: Donde preparas archivos para commit
3. **Repository**: Donde se guardan los commits

### Estados de los archivos
- **Untracked**: Archivo nuevo, Git no lo conoce
- **Modified**: Archivo modificado pero no preparado
- **Staged**: Archivo preparado para commit
- **Committed**: Cambios guardados en el repositorio

---

## Flujo de trabajo diario {#flujo-de-trabajo-diario}

### 1. Ver el estado actual
```bash
# Ver qué archivos han cambiado
git status

# Ver cambios específicos en archivos
git diff
```

### 2. Preparar cambios para commit
```bash
# Agregar archivo específico
git add nombre-archivo.php

# Agregar todos los archivos modificados
git add .

# Agregar solo archivos PHP
git add *.php

# Ver qué está preparado
git status
```

### 3. Crear un commit
```bash
# Commit con mensaje descriptivo
git commit -m "Agregar validación de email en formulario de cliente"

# Commit con mensaje más detallado
git commit -m "feat: implementar sistema de autenticación

- Agregar login de usuarios
- Validar credenciales
- Crear sesiones seguras"
```

### 4. Subir cambios al servidor
```bash
# Subir cambios a tu rama
git push origin nombre-rama

# Subir cambios a la rama principal
git push origin main
```

---

## Trabajo colaborativo {#trabajo-colaborativo}

### 1. Obtener cambios del servidor
```bash
# Descargar cambios del servidor
git pull origin main

# O hacer fetch + merge por separado
git fetch origin
git merge origin/main
```

### 2. Trabajar con ramas
```bash
# Ver todas las ramas
git branch -a

# Crear nueva rama
git checkout -b feature/nuevo-formulario

# Cambiar a rama existente
git checkout nombre-rama

# Crear y cambiar a nueva rama (Git 2.23+)
git switch -c feature/nuevo-formulario
```

### 3. Flujo de trabajo recomendado
```
main (rama principal)
├── develop (rama de desarrollo)
├── feature/nuevo-formulario (tu rama)
├── feature/validacion-email (rama de compañero)
└── hotfix/error-critico (rama de emergencia)
```

---

## Resolución de conflictos {#resolución-de-conflictos}

### ¿Cuándo ocurren conflictos?
Los conflictos ocurren cuando:
- Dos personas modifican la misma línea del mismo archivo
- Git no puede decidir qué cambios mantener

### Ejemplo de conflicto
```php
<<<<<<< HEAD
// Tu cambio
$cliente = new Cliente($nombre, $email);
=======
// Cambio de tu compañero
$cliente = new Cliente($nombre, $email, $telefono);
>>>>>>> feature/agregar-telefono
```

### Cómo resolver conflictos
1. **Identificar conflictos**:
   ```bash
   git status
   # Archivos con conflictos aparecen como "both modified"
   ```

2. **Editar archivos conflictivos**:
   - Buscar marcadores `<<<<<<<`, `=======`, `>>>>>>>`
   - Decidir qué código mantener
   - Eliminar marcadores de conflicto

3. **Resolver el conflicto**:
   ```bash
   # Después de editar, agregar archivos resueltos
   git add archivo-resuelto.php
   
   # Completar el merge
   git commit -m "Resolver conflicto en Cliente.php"
   ```

---

## Buenas prácticas {#buenas-prácticas}

### 1. Mensajes de commit claros
```bash
# ❌ Malo
git commit -m "fix"

# ✅ Bueno
git commit -m "fix: corregir validación de email en formulario de cliente"

# ✅ Mejor aún
git commit -m "fix: corregir validación de email en formulario de cliente

- Agregar validación de formato de email
- Mostrar mensaje de error específico
- Prevenir envío de formulario inválido"
```

### 2. Hacer commits frecuentes y pequeños
```bash
# ❌ Malo: Un commit con muchos cambios
git add .
git commit -m "Implementar todo el sistema de clientes"

# ✅ Bueno: Commits específicos
git add models/Cliente.php
git commit -m "feat: crear modelo Cliente con validaciones"

git add controllers/ClienteController.php
git commit -m "feat: implementar CRUD de clientes"

git add views/clientes/
git commit -m "feat: crear vistas para gestión de clientes"
```

### 3. Usar ramas para nuevas funcionalidades
```bash
# Crear rama para nueva funcionalidad
git checkout -b feature/sistema-ventas

# Trabajar en la funcionalidad
# ... hacer cambios ...

# Fusionar con la rama principal
git checkout main
git merge feature/sistema-ventas
git branch -d feature/sistema-ventas
```

---

## Comandos útiles {#comandos-útiles}

### Ver información
```bash
# Ver historial de commits
git log --oneline

# Ver historial con gráfico
git log --graph --oneline --all

# Ver cambios en un commit específico
git show abc1234

# Ver quién modificó qué línea
git blame archivo.php
```

### Deshacer cambios
```bash
# Descartar cambios en archivo específico
git checkout -- archivo.php

# Descartar todos los cambios
git checkout -- .

# Deshacer último commit (mantener cambios)
git reset --soft HEAD~1

# Deshacer último commit (descartar cambios)
git reset --hard HEAD~1
```

### Limpiar y mantener
```bash
# Limpiar archivos no rastreados
git clean -n  # Ver qué se eliminaría
git clean -f  # Eliminar archivos

# Verificar integridad del repositorio
git fsck

# Optimizar repositorio
git gc
```

---

## Ejemplos prácticos {#ejemplos-prácticos}

### Escenario 1: Trabajando en una nueva funcionalidad

```bash
# 1. Obtener cambios más recientes
git checkout main
git pull origin main

# 2. Crear rama para tu funcionalidad
git checkout -b feature/agregar-filtros-propiedades

# 3. Hacer cambios en el código
# ... editar archivos ...

# 4. Ver qué has cambiado
git status
git diff

# 5. Preparar cambios
git add controllers/PropiedadController.php
git add views/propiedades/index.php

# 6. Crear commit
git commit -m "feat: agregar filtros de búsqueda para propiedades

- Implementar filtro por precio
- Agregar filtro por ubicación
- Crear interfaz de filtros en vista"

# 7. Subir tu rama
git push origin feature/agregar-filtros-propiedades
```

### Escenario 2: Resolviendo conflictos

```bash
# 1. Intentar hacer merge
git checkout main
git pull origin main
git checkout feature/agregar-filtros-propiedades
git merge main

# 2. Si hay conflictos, ver cuáles son
git status

# 3. Editar archivos con conflictos
# ... resolver conflictos manualmente ...

# 4. Marcar conflictos como resueltos
git add archivo-resuelto.php

# 5. Completar el merge
git commit -m "Merge main into feature/agregar-filtros-propiedades"
```

### Escenario 3: Trabajo diario típico

```bash
# Al comenzar el día
git checkout main
git pull origin main
git checkout mi-rama-de-trabajo

# Durante el trabajo
# ... hacer cambios ...
git add archivo-modificado.php
git commit -m "feat: mejorar validación de formulario"

# Al final del día
git push origin mi-rama-de-trabajo
```

---

## Comandos de emergencia

### Si algo sale muy mal
```bash
# Descartar todos los cambios locales
git reset --hard HEAD
git clean -fd

# Volver a un commit específico
git reset --hard abc1234

# Forzar push (¡CUIDADO! Solo en emergencias)
git push --force-with-lease origin rama
```

### Recuperar archivos eliminados
```bash
# Ver commits que modificaron un archivo
git log -- archivo-eliminado.php

# Recuperar archivo de un commit específico
git checkout abc1234 -- archivo-eliminado.php
```

---

## Resumen de comandos esenciales

| Comando | Descripción |
|---------|-------------|
| `git status` | Ver estado actual |
| `git add .` | Preparar todos los cambios |
| `git commit -m "mensaje"` | Crear commit |
| `git push origin rama` | Subir cambios |
| `git pull origin rama` | Obtener cambios |
| `git checkout -b nueva-rama` | Crear y cambiar a rama |
| `git merge rama` | Fusionar ramas |
| `git log --oneline` | Ver historial |

---

## Consejos finales

1. **Siempre haz `git pull` antes de empezar a trabajar**
2. **Usa ramas para cada funcionalidad nueva**
3. **Haz commits frecuentes y descriptivos**
4. **Comunícate con tu equipo sobre qué archivos estás modificando**
5. **Si tienes dudas, pregunta antes de hacer push**

---

## Recursos adicionales

- [Git Cheat Sheet](https://education.github.com/git-cheat-sheet-education.pdf)
- [GitHub Guides](https://guides.github.com/)
- [Git Book](https://git-scm.com/book/es/v2)

---

*Este manual te ayudará a trabajar de manera colaborativa en tu proyecto Hogar Ideal. Recuerda que Git es una herramienta poderosa, pero con práctica se vuelve natural.*
