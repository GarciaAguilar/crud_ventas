-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-07-2025 a las 23:08:23
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `crud_ventas`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`id_cliente`, `nombre`, `direccion`, `telefono`, `email`, `fecha_registro`, `estado`) VALUES
(1, 'Ana Torres', 'Calle Primavera 45', '555-1111', 'ana.torres@email.com', '2025-07-11 15:00:20', 1),
(2, 'Pedro Sánchez', 'Av. Central 789', '555-2222', 'pedro.sanchez@email.com', '2025-07-11 15:00:20', 1),
(3, 'María López', 'Boulevard Norte 321', '555-3333', 'maria.lopez@email.com', '2025-07-11 15:00:20', 1),
(4, 'Carlos Ruiz', 'Callejón del Arte 56', '555-4444', 'carlos.ruiz@email.com', '2025-07-11 15:00:20', 1),
(5, 'Lucía Mendoza', 'Paseo de la Reforma 1001', '555-5555', 'lucia.mendoza@email.com', '2025-07-11 15:00:20', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalles_venta`
--

CREATE TABLE `detalles_venta` (
  `id_detalle` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `detalles_venta`
--

INSERT INTO `detalles_venta` (`id_detalle`, `id_venta`, `id_producto`, `cantidad`, `precio_unitario`, `subtotal`, `created_at`) VALUES
(1, 1, 1, 1, 18999.00, 18999.00, '2025-07-11 15:00:54'),
(2, 1, 3, 1, 1299.00, 1299.00, '2025-07-11 15:00:54'),
(3, 2, 2, 2, 7599.00, 15198.00, '2025-07-11 15:00:54'),
(4, 3, 5, 3, 1499.00, 4497.00, '2025-07-11 15:00:54'),
(5, 4, 1, 1, 18999.00, 18999.00, '2025-07-11 15:00:54'),
(6, 5, 4, 5, 699.00, 3495.00, '2025-07-11 15:00:54');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `facturacion`
--

CREATE TABLE `facturacion` (
  `id_factura` int(11) NOT NULL,
  `id_venta` int(11) NOT NULL,
  `numero_factura` varchar(20) NOT NULL,
  `fecha_emision` timestamp NOT NULL DEFAULT current_timestamp(),
  `subtotal` decimal(10,2) NOT NULL,
  `impuestos` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `facturacion`
--

INSERT INTO `facturacion` (`id_factura`, `id_venta`, `numero_factura`, `fecha_emision`, `subtotal`, `impuestos`, `total`, `estado`) VALUES
(1, 2, 'FAC-001-2025', '2025-07-11 15:01:12', 15198.00, 2431.68, 17629.68, 2),
(2, 3, 'FAC-002-2025', '2025-07-11 15:01:12', 4497.00, 719.52, 5216.52, 2),
(3, 1, 'FAC-003-2025', '2025-07-11 15:01:12', 20298.00, 3247.68, 23545.68, 2),
(4, 5, 'FAC-004-2025', '2025-07-11 15:01:12', 3495.00, 559.20, 4054.20, 2),
(5, 4, 'FAC-005-2025', '2025-07-11 15:01:12', 18999.00, 3039.84, 22038.84, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventario`
--

CREATE TABLE `inventario` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) NOT NULL,
  `costo` decimal(10,2) DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `categoria` varchar(50) DEFAULT NULL,
  `proveedor` varchar(100) DEFAULT NULL,
  `fecha_ingreso` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `inventario`
--

INSERT INTO `inventario` (`id_producto`, `nombre`, `descripcion`, `precio`, `costo`, `stock`, `categoria`, `proveedor`, `fecha_ingreso`, `estado`) VALUES
(1, 'Laptop Elite', 'Laptop i7 16GB RAM 512GB SSD', 18999.00, 14500.00, 5, 'Tecnología', 'TecnoSuministros', '2025-07-11 21:03:56', 1),
(2, 'Monitor Curvo 32\"', 'Monitor QHD 144Hz', 7599.00, 5800.00, 8, 'Tecnología', 'VisualTech', '2025-07-11 15:00:32', 1),
(3, 'Teclado Mecánico Pro', 'Teclado gaming switches azules', 1299.00, 850.00, 15, 'Periféricos', 'GamingGear', '2025-07-11 15:00:32', 1),
(4, 'Mouse Inalámbrico', 'DPI ajustable hasta 16000', 699.00, 450.00, 0, 'Periféricos', 'TechAcc', '2025-07-11 17:55:31', 1),
(5, 'Paquete Oficina', 'Mouse + Teclado + Alfombrilla', 1499.00, 950.00, 10, 'Combos', 'OfficePro', '2025-07-11 15:00:32', 1),
(7, 'Laptop hp victus', '8gb ram 256 ssd', 1100.00, 900.00, 10, 'Laptops', 'Zona digital', '2025-07-11 21:04:51', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_venta` int(11) NOT NULL,
  `id_cliente` int(11) NOT NULL,
  `fecha_venta` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado` tinyint(1) DEFAULT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `impuestos` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total` decimal(10,2) NOT NULL DEFAULT 0.00,
  `monto_recibido` decimal(10,2) DEFAULT NULL,
  `cambio` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id_venta`, `id_cliente`, `fecha_venta`, `estado`, `subtotal`, `impuestos`, `total`, `monto_recibido`, `cambio`) VALUES
(1, 1, '2025-07-11 15:00:44', 2, 20298.00, 3247.68, 23545.68, 24000.00, 454.32),
(2, 2, '2025-07-11 15:00:44', 2, 15198.00, 2431.68, 17629.68, 18000.00, 370.32),
(3, 3, '2025-07-11 15:00:44', 2, 4497.00, 719.52, 5216.52, 5500.00, 283.48),
(4, 4, '2025-07-11 15:00:44', 2, 18999.00, 3039.84, 22038.84, 22038.84, 0.00),
(5, 5, '2025-07-11 15:00:44', 2, 3495.00, 559.20, 4054.20, 4100.00, 45.80);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`);

--
-- Indices de la tabla `detalles_venta`
--
ALTER TABLE `detalles_venta`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_venta` (`id_venta`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `facturacion`
--
ALTER TABLE `facturacion`
  ADD PRIMARY KEY (`id_factura`),
  ADD UNIQUE KEY `numero_factura` (`numero_factura`),
  ADD KEY `id_venta` (`id_venta`);

--
-- Indices de la tabla `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`id_producto`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id_venta`),
  ADD KEY `id_cliente` (`id_cliente`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `detalles_venta`
--
ALTER TABLE `detalles_venta`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `facturacion`
--
ALTER TABLE `facturacion`
  MODIFY `id_factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `inventario`
--
ALTER TABLE `inventario`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `detalles_venta`
--
ALTER TABLE `detalles_venta`
  ADD CONSTRAINT `detalles_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`),
  ADD CONSTRAINT `detalles_venta_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `inventario` (`id_producto`);

--
-- Filtros para la tabla `facturacion`
--
ALTER TABLE `facturacion`
  ADD CONSTRAINT `facturacion_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`);

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
