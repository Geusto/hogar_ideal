-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 22, 2025 at 11:23 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hogar_ideal`
--

-- --------------------------------------------------------

--
-- Table structure for table `agente`
--

CREATE TABLE `agente` (
  `id_agente` int NOT NULL,
  `nombre_completo` varchar(100) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `zona_asignada` varchar(50) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `activo` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `agente`
--

INSERT INTO `agente` (`id_agente`, `nombre_completo`, `telefono`, `email`, `zona_asignada`, `activo`) VALUES
(1, 'María García', '098111111', 'maria.garcia@hogarideal.com', 'Punta del Este', 1),
(2, 'Juan López', '098222222', 'juan.lopez@hogarideal.com', 'Montevideo Centro', 0),
(3, 'Pedro Martínez', '098333333', 'pedro.martinez@hogarideal.com', 'Carrasco', 1);

-- --------------------------------------------------------

--
-- Table structure for table `cliente`
--

CREATE TABLE `cliente` (
  `id_cliente` int NOT NULL,
  `nombre_completo` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `direccion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `telefono` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `tipo` enum('Comprador','Vendedor','Ambos') CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `cliente`
--

INSERT INTO `cliente` (`id_cliente`, `nombre_completo`, `direccion`, `telefono`, `email`, `tipo`) VALUES
(1, 'Carlos Méndez', 'Av. Brasil 1234, Apt. 101', '099111111', 'carlos@email.com', 'Vendedor'),
(2, 'Ana Rodríguez', 'Calle Colonia 567, Edificio Sol', '099222222', 'ana@email.com', 'Comprador'),
(3, 'Luisa Fernández', 'Bvar. Artigas 901, Casa 5', '099333333', 'luisa@email.com', 'Ambos');

-- --------------------------------------------------------

--
-- Table structure for table `propiedad`
--

CREATE TABLE `propiedad` (
  `id_propiedad` int NOT NULL,
  `tipo` enum('casa','apartamento','terreno','local') COLLATE utf8mb4_spanish2_ci NOT NULL,
  `direccion` varchar(200) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `habitaciones` int DEFAULT NULL,
  `banos` int DEFAULT NULL,
  `superficie` decimal(10,2) NOT NULL,
  `precio` decimal(12,2) NOT NULL,
  `portada` varchar(255) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `estado` enum('disponible','vendida') COLLATE utf8mb4_spanish2_ci DEFAULT 'disponible',
  `id_cliente_vendedor` int NOT NULL,
  `id_agente` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `propiedad`
--

INSERT INTO `propiedad` (`id_propiedad`, `tipo`, `direccion`, `habitaciones`, `banos`, `superficie`, `precio`, `portada`, `estado`, `id_cliente_vendedor`, `id_agente`) VALUES
(1, 'casa', 'Calle Los Pinos 123', 4, 3, '180.50', '350000.00', NULL, 'vendida', 1, 1),
(2, 'apartamento', 'Av. 18 de Julio 567', 2, 1, '75.25', '120000.00', NULL, 'disponible', 3, 2),
(3, 'terreno', 'Av. Bolivia 890', 2, 1, '300.00', '200000.00', NULL, 'disponible', 3, 3),
(4, 'casa', 'calle 45 #1a-24', 2, 2, '200.00', '89000000.00', NULL, 'vendida', 1, 1),
(5, 'apartamento', 'Edificio trin Apto 12a', 2, 2, '100.00', '450000.00', 'uploads/6879b718c5263_cat.png', 'disponible', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `venta`
--

CREATE TABLE `venta` (
  `id_venta` int NOT NULL,
  `fecha_venta` date NOT NULL,
  `precio_final` decimal(12,2) NOT NULL,
  `comision` decimal(10,2) NOT NULL,
  `metodo_pago` enum('contado','credito') COLLATE utf8mb4_spanish2_ci NOT NULL,
  `id_propiedad` int NOT NULL,
  `id_cliente_comprador` int NOT NULL,
  `id_cliente_vendedor` int NOT NULL,
  `id_agente` int NOT NULL
) ;

--
-- Dumping data for table `venta`
--

INSERT INTO `venta` (`id_venta`, `fecha_venta`, `precio_final`, `comision`, `metodo_pago`, `id_propiedad`, `id_cliente_comprador`, `id_cliente_vendedor`, `id_agente`) VALUES
(1, '2024-06-20', '340000.00', '10200.00', 'contado', 1, 2, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `visita`
--

CREATE TABLE `visita` (
  `id_visita` int NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `estado` enum('programada','realizada','cancelada') COLLATE utf8mb4_spanish2_ci DEFAULT 'programada',
  `comentarios` text COLLATE utf8mb4_spanish2_ci,
  `id_cliente` int NOT NULL,
  `id_propiedad` int NOT NULL,
  `id_agente` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `visita`
--

INSERT INTO `visita` (`id_visita`, `fecha_hora`, `estado`, `comentarios`, `id_cliente`, `id_propiedad`, `id_agente`) VALUES
(1, '2024-06-20 15:30:00', 'programada', NULL, 2, 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `agente`
--
ALTER TABLE `agente`
  ADD PRIMARY KEY (`id_agente`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`id_cliente`) USING BTREE,
  ADD UNIQUE KEY `Email` (`email`) USING BTREE;

--
-- Indexes for table `propiedad`
--
ALTER TABLE `propiedad`
  ADD PRIMARY KEY (`id_propiedad`),
  ADD KEY `id_cliente_vendedor` (`id_cliente_vendedor`),
  ADD KEY `id_agente` (`id_agente`);

--
-- Indexes for table `venta`
--
ALTER TABLE `venta`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_propiedad` (`id_propiedad`),
  ADD KEY `id_cliente_comprador` (`id_cliente_comprador`),
  ADD KEY `id_cliente_vendedor` (`id_cliente_vendedor`),
  ADD KEY `id_agente` (`id_agente`);

--
-- Indexes for table `visita`
--
ALTER TABLE `visita`
  ADD PRIMARY KEY (`id_visita`),
  ADD KEY `id_cliente` (`id_cliente`),
  ADD KEY `id_propiedad` (`id_propiedad`),
  ADD KEY `id_agente` (`id_agente`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `agente`
--
ALTER TABLE `agente`
  MODIFY `id_agente` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cliente`
--
ALTER TABLE `cliente`
  MODIFY `id_cliente` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `propiedad`
--
ALTER TABLE `propiedad`
  MODIFY `id_propiedad` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `venta`
--
ALTER TABLE `venta`
  MODIFY `id_venta` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `visita`
--
ALTER TABLE `visita`
  MODIFY `id_visita` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `propiedad`
--
ALTER TABLE `propiedad`
  ADD CONSTRAINT `propiedad_ibfk_1` FOREIGN KEY (`id_cliente_vendedor`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `propiedad_ibfk_2` FOREIGN KEY (`id_agente`) REFERENCES `agente` (`id_agente`);

--
-- Constraints for table `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`id_propiedad`) REFERENCES `propiedad` (`id_propiedad`),
  ADD CONSTRAINT `venta_ibfk_2` FOREIGN KEY (`id_cliente_comprador`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `venta_ibfk_3` FOREIGN KEY (`id_cliente_vendedor`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `venta_ibfk_4` FOREIGN KEY (`id_agente`) REFERENCES `agente` (`id_agente`);

--
-- Constraints for table `visita`
--
ALTER TABLE `visita`
  ADD CONSTRAINT `visita_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `visita_ibfk_2` FOREIGN KEY (`id_propiedad`) REFERENCES `propiedad` (`id_propiedad`),
  ADD CONSTRAINT `visita_ibfk_3` FOREIGN KEY (`id_agente`) REFERENCES `agente` (`id_agente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
