-- Script para crear la tabla fotos_propiedad
-- Ejecutar este script en tu base de datos MySQL

USE hogar_ideal;

-- Crear la tabla fotos_propiedad
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Crear índices para mejorar el rendimiento
CREATE INDEX idx_propiedad_orden ON fotos_propiedad(id_propiedad, orden);
CREATE INDEX idx_es_portada ON fotos_propiedad(es_portada);

-- Comentarios sobre la estructura
-- id_foto: Identificador único de cada foto
-- id_propiedad: ID de la propiedad a la que pertenece la foto
-- nombre_archivo: Ruta/nombre del archivo de imagen
-- descripcion: Descripción opcional de la foto
-- orden: Orden de visualización de las fotos
-- es_portada: Indica si la foto es la principal (1) o no (0)
-- fecha_creacion: Fecha y hora de creación del registro

-- Verificar que la tabla se creó correctamente
DESCRIBE fotos_propiedad;
