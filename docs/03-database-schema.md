# 🗄️ Esquema de Base de Datos - Hogar Ideal

## 📋 Tabla de Contenidos

1. [Descripción General](#descripción-general)
2. [Entidades Principales](#entidades-principales)
3. [Relaciones](#relaciones)
4. [Índices y Restricciones](#índices-y-restricciones)
5. [Datos de Ejemplo](#datos-de-ejemplo)
6. [Consultas Comunes](#consultas-comunes)

---

## 🎯 Descripción General

La base de datos de "Hogar Ideal" está diseñada para gestionar un sistema inmobiliario completo, incluyendo propiedades, agentes, clientes, ventas y visitas. Utiliza MySQL 8.0 con codificación UTF-8 y collation español.

### 📊 Características Técnicas
- **Motor**: InnoDB
- **Codificación**: utf8mb4
- **Collation**: utf8mb4_spanish2_ci
- **Integridad Referencial**: Completa con FOREIGN KEYs
- **Auto-increment**: En todas las claves primarias

---

## 🏗️ Entidades Principales

### 👤 Tabla: `agente`

**Descripción**: Almacena información de los agentes inmobiliarios del sistema.

```sql
CREATE TABLE `agente` (
  `id_agente` int NOT NULL AUTO_INCREMENT,
  `nombre_completo` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `zona_asignada` varchar(50) NOT NULL,
  `activo` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id_agente`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
```

#### 📋 Campos de la Tabla

| Campo | Tipo | Descripción | Restricciones |
|-------|------|-------------|---------------|
| `id_agente` | INT | Identificador único del agente | AUTO_INCREMENT, PRIMARY KEY |
| `nombre_completo` | VARCHAR(100) | Nombre completo del agente | NOT NULL |
| `telefono` | VARCHAR(20) | Número de teléfono | NOT NULL |
| `email` | VARCHAR(100) | Correo electrónico | UNIQUE |
| `zona_asignada` | VARCHAR(50) | Zona geográfica asignada | NOT NULL |
| `activo` | TINYINT(1) | Estado activo/inactivo | DEFAULT 1 |

#### 📊 Datos de Ejemplo
```sql
INSERT INTO `agente` VALUES
(1, 'María García', '098111111', 'maria.garcia@hogarideal.com', 'Punta del Este', 1),
(2, 'Juan López', '098222222', 'juan.lopez@hogarideal.com', 'Montevideo Centro', 0),
(3, 'Pedro Martínez', '098333333', 'pedro.martinez@hogarideal.com', 'Carrasco', 1);
```

---

### 👥 Tabla: `cliente`

**Descripción**: Gestiona información de clientes (compradores y vendedores).

```sql
CREATE TABLE `cliente` (
  `id_cliente` int NOT NULL AUTO_INCREMENT,
  `nombre_completo` varchar(100) NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `tipo` enum('Comprador','Vendedor','Ambos') NOT NULL,
  PRIMARY KEY (`id_cliente`),
  UNIQUE KEY `Email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
```

#### 📋 Campos de la Tabla

| Campo | Tipo | Descripción | Restricciones |
|-------|------|-------------|---------------|
| `id_cliente` | INT | Identificador único del cliente | AUTO_INCREMENT, PRIMARY KEY |
| `nombre_completo` | VARCHAR(100) | Nombre completo del cliente | NOT NULL |
| `direccion` | VARCHAR(200) | Dirección del cliente | NOT NULL |
| `telefono` | VARCHAR(20) | Número de teléfono | NOT NULL |
| `email` | VARCHAR(100) | Correo electrónico | UNIQUE |
| `tipo` | ENUM | Tipo de cliente | 'Comprador', 'Vendedor', 'Ambos' |

#### 📊 Datos de Ejemplo
```sql
INSERT INTO `cliente` VALUES
(1, 'Carlos Méndez', 'Av. Brasil 1234, Apt. 101', '099111111', 'carlos@email.com', 'Vendedor'),
(2, 'Ana Rodríguez', 'Calle Colonia 567, Edificio Sol', '099222222', 'ana@email.com', 'Comprador'),
(3, 'Luisa Fernández', 'Bvar. Artigas 901, Casa 5', '099333333', 'luisa@email.com', 'Ambos');
```

---

### 🏠 Tabla: `propiedad`

**Descripción**: Almacena información detallada de las propiedades inmobiliarias.

```sql
CREATE TABLE `propiedad` (
  `id_propiedad` int NOT NULL AUTO_INCREMENT,
  `tipo` enum('casa','apartamento','terreno','local') NOT NULL,
  `direccion` varchar(200) NOT NULL,
  `habitaciones` int DEFAULT NULL,
  `banos` int DEFAULT NULL,
  `superficie` decimal(10,2) NOT NULL,
  `precio` decimal(12,2) NOT NULL,
  `portada` varchar(255) DEFAULT NULL,
  `estado` enum('disponible','vendida') DEFAULT 'disponible',
  `id_cliente_vendedor` int NOT NULL,
  `id_agente` int NOT NULL,
  PRIMARY KEY (`id_propiedad`),
  KEY `id_cliente_vendedor` (`id_cliente_vendedor`),
  KEY `id_agente` (`id_agente`),
  CONSTRAINT `propiedad_ibfk_1` FOREIGN KEY (`id_cliente_vendedor`) REFERENCES `cliente` (`id_cliente`),
  CONSTRAINT `propiedad_ibfk_2` FOREIGN KEY (`id_agente`) REFERENCES `agente` (`id_agente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
```

#### 📋 Campos de la Tabla

| Campo | Tipo | Descripción | Restricciones |
|-------|------|-------------|---------------|
| `id_propiedad` | INT | Identificador único de la propiedad | AUTO_INCREMENT, PRIMARY KEY |
| `tipo` | ENUM | Tipo de propiedad | 'casa', 'apartamento', 'terreno', 'local' |
| `direccion` | VARCHAR(200) | Dirección de la propiedad | NOT NULL |
| `habitaciones` | INT | Número de habitaciones | NULL |
| `banos` | INT | Número de baños | NULL |
| `superficie` | DECIMAL(10,2) | Superficie en m² | NOT NULL |
| `precio` | DECIMAL(12,2) | Precio de la propiedad | NOT NULL |
| `portada` | VARCHAR(255) | Ruta de la imagen de portada | NULL |
| `estado` | ENUM | Estado de la propiedad | 'disponible', 'vendida' |
| `id_cliente_vendedor` | INT | ID del cliente vendedor | FOREIGN KEY |
| `id_agente` | INT | ID del agente asignado | FOREIGN KEY |

#### 📊 Datos de Ejemplo
```sql
INSERT INTO `propiedad` VALUES
(1, 'casa', 'Calle Los Pinos 123', 4, 3, '180.50', '350000.00', NULL, 'vendida', 1, 1),
(2, 'apartamento', 'Av. 18 de Julio 567', 2, 1, '75.25', '120000.00', NULL, 'disponible', 3, 2),
(3, 'terreno', 'Av. Bolivia 890', 2, 1, '300.00', '200000.00', NULL, 'disponible', 3, 3),
(4, 'casa', 'calle 45 #1a-24', 2, 2, '200.00', '89000000.00', NULL, 'vendida', 1, 1),
(5, 'apartamento', 'Edificio trin Apto 12a', 2, 2, '100.00', '450000.00', 'uploads/6879b718c5263_cat.png', 'disponible', 3, 1);
```

---

### 💰 Tabla: `venta`

**Descripción**: Registra las transacciones de venta de propiedades.

```sql
CREATE TABLE `venta` (
  `id_venta` int NOT NULL AUTO_INCREMENT,
  `fecha_venta` date NOT NULL,
  `precio_final` decimal(12,2) NOT NULL,
  `comision` decimal(10,2) NOT NULL,
  `metodo_pago` enum('contado','credito') NOT NULL,
  `id_propiedad` int NOT NULL,
  `id_cliente_comprador` int NOT NULL,
  `id_cliente_vendedor` int NOT NULL,
  `id_agente` int NOT NULL,
  PRIMARY KEY (`id_venta`),
  KEY `id_propiedad` (`id_propiedad`),
  KEY `id_cliente_comprador` (`id_cliente_comprador`),
  KEY `id_cliente_vendedor` (`id_cliente_vendedor`),
  KEY `id_agente` (`id_agente`),
  CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`id_propiedad`) REFERENCES `propiedad` (`id_propiedad`),
  CONSTRAINT `venta_ibfk_2` FOREIGN KEY (`id_cliente_comprador`) REFERENCES `cliente` (`id_cliente`),
  CONSTRAINT `venta_ibfk_3` FOREIGN KEY (`id_cliente_vendedor`) REFERENCES `cliente` (`id_cliente`),
  CONSTRAINT `venta_ibfk_4` FOREIGN KEY (`id_agente`) REFERENCES `agente` (`id_agente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
```

#### 📋 Campos de la Tabla

| Campo | Tipo | Descripción | Restricciones |
|-------|------|-------------|---------------|
| `id_venta` | INT | Identificador único de la venta | AUTO_INCREMENT, PRIMARY KEY |
| `fecha_venta` | DATE | Fecha de la venta | NOT NULL |
| `precio_final` | DECIMAL(12,2) | Precio final de la venta | NOT NULL |
| `comision` | DECIMAL(10,2) | Comisión del agente | NOT NULL |
| `metodo_pago` | ENUM | Método de pago | 'contado', 'credito' |
| `id_propiedad` | INT | ID de la propiedad vendida | FOREIGN KEY |
| `id_cliente_comprador` | INT | ID del cliente comprador | FOREIGN KEY |
| `id_cliente_vendedor` | INT | ID del cliente vendedor | FOREIGN KEY |
| `id_agente` | INT | ID del agente que realizó la venta | FOREIGN KEY |

#### 📊 Datos de Ejemplo
```sql
INSERT INTO `venta` VALUES
(1, '2024-06-20', '340000.00', '10200.00', 'contado', 1, 2, 1, 1);
```

---

### 📅 Tabla: `visita`

**Descripción**: Gestiona las visitas programadas a las propiedades.

```sql
CREATE TABLE `visita` (
  `id_visita` int NOT NULL AUTO_INCREMENT,
  `fecha_hora` datetime NOT NULL,
  `estado` enum('programada','realizada','cancelada') DEFAULT 'programada',
  `comentarios` text,
  `id_cliente` int NOT NULL,
  `id_propiedad` int NOT NULL,
  `id_agente` int NOT NULL,
  PRIMARY KEY (`id_visita`),
  KEY `id_cliente` (`id_cliente`),
  KEY `id_propiedad` (`id_propiedad`),
  KEY `id_agente` (`id_agente`),
  CONSTRAINT `visita_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  CONSTRAINT `visita_ibfk_2` FOREIGN KEY (`id_propiedad`) REFERENCES `propiedad` (`id_propiedad`),
  CONSTRAINT `visita_ibfk_3` FOREIGN KEY (`id_agente`) REFERENCES `agente` (`id_agente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;
```

#### 📋 Campos de la Tabla

| Campo | Tipo | Descripción | Restricciones |
|-------|------|-------------|---------------|
| `id_visita` | INT | Identificador único de la visita | AUTO_INCREMENT, PRIMARY KEY |
| `fecha_hora` | DATETIME | Fecha y hora de la visita | NOT NULL |
| `estado` | ENUM | Estado de la visita | 'programada', 'realizada', 'cancelada' |
| `comentarios` | TEXT | Comentarios sobre la visita | NULL |
| `id_cliente` | INT | ID del cliente que visita | FOREIGN KEY |
| `id_propiedad` | INT | ID de la propiedad a visitar | FOREIGN KEY |
| `id_agente` | INT | ID del agente que acompaña | FOREIGN KEY |

#### 📊 Datos de Ejemplo
```sql
INSERT INTO `visita` VALUES
(1, '2024-06-20 15:30:00', 'programada', NULL, 2, 1, 1);
```

---

## 🔗 Relaciones

### Diagrama de Relaciones
```
agente (1) ←→ (N) propiedad
cliente (1) ←→ (N) propiedad (como vendedor)
cliente (1) ←→ (N) venta (como comprador)
cliente (1) ←→ (N) venta (como vendedor)
agente (1) ←→ (N) venta
propiedad (1) ←→ (N) venta
cliente (1) ←→ (N) visita
propiedad (1) ←→ (N) visita
agente (1) ←→ (N) visita
```

### Relaciones Específicas

#### 1. Agente → Propiedad
- Un agente puede gestionar múltiples propiedades
- Una propiedad tiene un solo agente asignado
- **Clave foránea**: `propiedad.id_agente → agente.id_agente`

#### 2. Cliente → Propiedad (Vendedor)
- Un cliente puede vender múltiples propiedades
- Una propiedad tiene un solo vendedor
- **Clave foránea**: `propiedad.id_cliente_vendedor → cliente.id_cliente`

#### 3. Venta (Relaciones Múltiples)
- **Propiedad**: Una propiedad puede tener una venta
- **Cliente Comprador**: Un cliente puede comprar múltiples propiedades
- **Cliente Vendedor**: Un cliente puede vender múltiples propiedades
- **Agente**: Un agente puede realizar múltiples ventas

#### 4. Visita (Relaciones Múltiples)
- **Cliente**: Un cliente puede hacer múltiples visitas
- **Propiedad**: Una propiedad puede tener múltiples visitas
- **Agente**: Un agente puede acompañar múltiples visitas

---

## 🔍 Índices y Restricciones

### Claves Primarias
```sql
-- Todas las tablas tienen AUTO_INCREMENT en su PK
agente.id_agente
cliente.id_cliente
propiedad.id_propiedad
venta.id_venta
visita.id_visita
```

### Claves Únicas
```sql
-- Emails únicos
agente.email
cliente.email
```

### Claves Foráneas
```sql
-- Propiedad
propiedad.id_cliente_vendedor → cliente.id_cliente
propiedad.id_agente → agente.id_agente

-- Venta
venta.id_propiedad → propiedad.id_propiedad
venta.id_cliente_comprador → cliente.id_cliente
venta.id_cliente_vendedor → cliente.id_cliente
venta.id_agente → agente.id_agente

-- Visita
visita.id_cliente → cliente.id_cliente
visita.id_propiedad → propiedad.id_propiedad
visita.id_agente → agente.id_agente
```

### Índices Secundarios
```sql
-- Índices en claves foráneas para mejorar rendimiento
propiedad.id_cliente_vendedor
propiedad.id_agente
venta.id_propiedad
venta.id_cliente_comprador
venta.id_cliente_vendedor
venta.id_agente
visita.id_cliente
visita.id_propiedad
visita.id_agente
```

---

## 📊 Consultas Comunes

### 1. Propiedades Disponibles con Información del Agente
```sql
SELECT 
    p.*,
    a.nombre_completo as agente_nombre,
    c.nombre_completo as vendedor_nombre
FROM propiedad p
JOIN agente a ON p.id_agente = a.id_agente
JOIN cliente c ON p.id_cliente_vendedor = c.id_cliente
WHERE p.estado = 'disponible'
ORDER BY p.precio ASC;
```

### 2. Ventas por Agente con Comisiones
```sql
SELECT 
    a.nombre_completo as agente,
    COUNT(v.id_venta) as total_ventas,
    SUM(v.precio_final) as total_ventas_monto,
    SUM(v.comision) as total_comisiones
FROM agente a
LEFT JOIN venta v ON a.id_agente = v.id_agente
WHERE a.activo = 1
GROUP BY a.id_agente, a.nombre_completo
ORDER BY total_comisiones DESC;
```

### 3. Propiedades por Tipo y Estado
```sql
SELECT 
    tipo,
    estado,
    COUNT(*) as cantidad,
    AVG(precio) as precio_promedio,
    MIN(precio) as precio_minimo,
    MAX(precio) as precio_maximo
FROM propiedad
GROUP BY tipo, estado
ORDER BY tipo, estado;
```

### 4. Visitas Programadas para Hoy
```sql
SELECT 
    v.fecha_hora,
    c.nombre_completo as cliente,
    p.direccion as propiedad,
    a.nombre_completo as agente
FROM visita vi
JOIN cliente c ON vi.id_cliente = c.id_cliente
JOIN propiedad p ON vi.id_propiedad = p.id_propiedad
JOIN agente a ON vi.id_agente = a.id_agente
WHERE DATE(vi.fecha_hora) = CURDATE()
AND vi.estado = 'programada'
ORDER BY vi.fecha_hora;
```

### 5. Top 5 Propiedades Más Caras
```sql
SELECT 
    p.tipo,
    p.direccion,
    p.precio,
    a.nombre_completo as agente,
    c.nombre_completo as vendedor
FROM propiedad p
JOIN agente a ON p.id_agente = a.id_agente
JOIN cliente c ON p.id_cliente_vendedor = c.id_cliente
WHERE p.estado = 'disponible'
ORDER BY p.precio DESC
LIMIT 5;
```

---

## 🛠️ Mantenimiento de la Base de Datos

### Respaldos
```bash
# Respaldar la base de datos
mysqldump -u usuario -p hogar_ideal > backup_hogar_ideal.sql

# Restaurar la base de datos
mysql -u usuario -p hogar_ideal < backup_hogar_ideal.sql
```

### Optimización
```sql
-- Analizar tablas para optimizar índices
ANALYZE TABLE agente, cliente, propiedad, venta, visita;

-- Optimizar tablas
OPTIMIZE TABLE agente, cliente, propiedad, venta, visita;
```

### Monitoreo
```sql
-- Verificar tamaño de las tablas
SELECT 
    table_name,
    ROUND(((data_length + index_length) / 1024 / 1024), 2) AS 'Size (MB)'
FROM information_schema.tables
WHERE table_schema = 'hogar_ideal'
ORDER BY (data_length + index_length) DESC;

-- Verificar fragmentación
SHOW TABLE STATUS FROM hogar_ideal;
```

---

## 📈 Consideraciones de Rendimiento

### 1. Índices Recomendados
- Todos los campos de búsqueda frecuente
- Campos de ordenamiento (precio, fecha)
- Campos de filtrado (estado, tipo)

### 2. Particionamiento
Para bases de datos grandes, considerar:
- Particionar por fecha en tabla `venta`
- Particionar por zona en tabla `agente`

### 3. Optimización de Consultas
- Usar LIMIT en consultas de listado
- Implementar paginación
- Cachear consultas frecuentes

---

## 🔒 Seguridad

### 1. Permisos de Usuario
```sql
-- Crear usuario con permisos limitados
CREATE USER 'hogar_ideal_user'@'localhost' IDENTIFIED BY 'password_seguro';
GRANT SELECT, INSERT, UPDATE, DELETE ON hogar_ideal.* TO 'hogar_ideal_user'@'localhost';
FLUSH PRIVILEGES;
```

### 2. Validación de Datos
- Validar todos los datos de entrada
- Usar prepared statements
- Escapar datos de salida

### 3. Auditoría
```sql
-- Crear tabla de auditoría (opcional)
CREATE TABLE auditoria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    tabla VARCHAR(50),
    accion ENUM('INSERT', 'UPDATE', 'DELETE'),
    registro_id INT,
    usuario VARCHAR(50),
    fecha TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## 📚 Próximos Pasos

Para implementar mejoras en la base de datos:

1. **Índices adicionales** según patrones de uso
2. **Procedimientos almacenados** para operaciones complejas
3. **Triggers** para auditoría automática
4. **Vistas** para consultas frecuentes
5. **Replicación** para alta disponibilidad

Para más información sobre implementaciones específicas, consulta:
- [Documentación de Entidades](04-entities/)
- [Funciones Helper](05-functions/)
- [Guía MVC](02-mvc-pattern.md) 