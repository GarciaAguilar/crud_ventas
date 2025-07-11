/*
 Navicat Premium Data Transfer

 Source Server         : mariaDB
 Source Server Type    : MariaDB
 Source Server Version : 100432
 Source Host           : localhost:3306
 Source Schema         : crud_ventas

 Target Server Type    : MariaDB
 Target Server Version : 100432
 File Encoding         : 65001

 Date: 11/07/2025 16:42:39
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for clientes
-- ----------------------------
DROP TABLE IF EXISTS `clientes`;
CREATE TABLE `clientes`  (
  `id_cliente` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `direccion` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `telefono` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `fecha_registro` timestamp(0) NOT NULL DEFAULT current_timestamp,
  `estado` tinyint(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id_cliente`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of clientes
-- ----------------------------
INSERT INTO `clientes` VALUES (1, 'Ana Torres', 'Calle Primavera 45', '555-1111', 'ana.torres@email.com', '2025-07-11 09:00:20', 1);
INSERT INTO `clientes` VALUES (2, 'Pedro Sánchez', 'Av. Central 789', '555-2222', 'pedro.sanchez@email.com', '2025-07-11 09:00:20', 1);
INSERT INTO `clientes` VALUES (3, 'María López', 'Boulevard Norte 321', '555-3333', 'maria.lopez@email.com', '2025-07-11 09:00:20', 1);
INSERT INTO `clientes` VALUES (4, 'Carlos Ruiz', 'Callejón del Arte 56', '555-4444', 'carlos.ruiz@email.com', '2025-07-11 09:00:20', 1);
INSERT INTO `clientes` VALUES (5, 'Lucía Mendoza', 'Paseo de la Reforma 1001', '555-5555', 'lucia.mendoza@email.com', '2025-07-11 09:00:20', 1);

-- ----------------------------
-- Table structure for detalles_venta
-- ----------------------------
DROP TABLE IF EXISTS `detalles_venta`;
CREATE TABLE `detalles_venta`  (
  `id_detalle` int(11) NOT NULL AUTO_INCREMENT,
  `id_venta` int(11) NOT NULL,
  `id_producto` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10, 2) NOT NULL,
  `subtotal` decimal(10, 2) NOT NULL,
  `created_at` timestamp(0) NOT NULL DEFAULT current_timestamp,
  PRIMARY KEY (`id_detalle`) USING BTREE,
  INDEX `id_venta`(`id_venta`) USING BTREE,
  INDEX `id_producto`(`id_producto`) USING BTREE,
  CONSTRAINT `detalles_venta_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON DELETE RESTRICT ON UPDATE RESTRICT,
  CONSTRAINT `detalles_venta_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `inventario` (`id_producto`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of detalles_venta
-- ----------------------------
INSERT INTO `detalles_venta` VALUES (1, 1, 1, 1, 18999.00, 18999.00, '2025-07-11 09:00:54');
INSERT INTO `detalles_venta` VALUES (2, 1, 3, 1, 1299.00, 1299.00, '2025-07-11 09:00:54');
INSERT INTO `detalles_venta` VALUES (3, 2, 2, 2, 7599.00, 15198.00, '2025-07-11 09:00:54');
INSERT INTO `detalles_venta` VALUES (4, 3, 5, 3, 1499.00, 4497.00, '2025-07-11 09:00:54');
INSERT INTO `detalles_venta` VALUES (5, 4, 1, 1, 18999.00, 18999.00, '2025-07-11 09:00:54');
INSERT INTO `detalles_venta` VALUES (6, 5, 4, 5, 699.00, 3495.00, '2025-07-11 09:00:54');

-- ----------------------------
-- Table structure for facturacion
-- ----------------------------
DROP TABLE IF EXISTS `facturacion`;
CREATE TABLE `facturacion`  (
  `id_factura` int(11) NOT NULL AUTO_INCREMENT,
  `id_venta` int(11) NOT NULL,
  `numero_factura` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `fecha_emision` timestamp(0) NOT NULL DEFAULT current_timestamp,
  `subtotal` decimal(10, 2) NOT NULL,
  `impuestos` decimal(10, 2) NOT NULL,
  `total` decimal(10, 2) NOT NULL,
  `estado` tinyint(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id_factura`) USING BTREE,
  UNIQUE INDEX `numero_factura`(`numero_factura`) USING BTREE,
  INDEX `id_venta`(`id_venta`) USING BTREE,
  CONSTRAINT `facturacion_ibfk_1` FOREIGN KEY (`id_venta`) REFERENCES `ventas` (`id_venta`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of facturacion
-- ----------------------------
INSERT INTO `facturacion` VALUES (1, 2, 'FAC-001-2025', '2025-07-11 09:01:12', 15198.00, 2431.68, 17629.68, 2);
INSERT INTO `facturacion` VALUES (2, 3, 'FAC-002-2025', '2025-07-11 09:01:12', 4497.00, 719.52, 5216.52, 2);
INSERT INTO `facturacion` VALUES (3, 1, 'FAC-003-2025', '2025-07-11 09:01:12', 20298.00, 3247.68, 23545.68, 2);
INSERT INTO `facturacion` VALUES (4, 5, 'FAC-004-2025', '2025-07-11 09:01:12', 3495.00, 559.20, 4054.20, 2);
INSERT INTO `facturacion` VALUES (5, 4, 'FAC-005-2025', '2025-07-11 09:01:12', 18999.00, 3039.84, 22038.84, 2);

-- ----------------------------
-- Table structure for inventario
-- ----------------------------
DROP TABLE IF EXISTS `inventario`;
CREATE TABLE `inventario`  (
  `id_producto` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `precio` decimal(10, 2) NOT NULL,
  `costo` decimal(10, 2) NULL DEFAULT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `categoria` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `proveedor` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL,
  `fecha_ingreso` timestamp(0) NOT NULL DEFAULT current_timestamp,
  `estado` tinyint(1) NULL DEFAULT NULL,
  PRIMARY KEY (`id_producto`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 8 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of inventario
-- ----------------------------
INSERT INTO `inventario` VALUES (1, 'Laptop Elite', 'Laptop i7 16GB RAM 512GB SSD', 18999.00, 14500.00, 5, 'Tecnología', 'TecnoSuministros', '2025-07-11 15:03:56', 1);
INSERT INTO `inventario` VALUES (2, 'Monitor Curvo 32\"', 'Monitor QHD 144Hz', 7599.00, 5800.00, 8, 'Tecnología', 'VisualTech', '2025-07-11 09:00:32', 1);
INSERT INTO `inventario` VALUES (3, 'Teclado Mecánico Pro', 'Teclado gaming switches azules', 1299.00, 850.00, 15, 'Periféricos', 'GamingGear', '2025-07-11 09:00:32', 1);
INSERT INTO `inventario` VALUES (4, 'Mouse Inalámbrico', 'DPI ajustable hasta 16000', 699.00, 450.00, 0, 'Periféricos', 'TechAcc', '2025-07-11 11:55:31', 1);
INSERT INTO `inventario` VALUES (5, 'Paquete Oficina', 'Mouse + Teclado + Alfombrilla', 1499.00, 950.00, 10, 'Combos', 'OfficePro', '2025-07-11 09:00:32', 1);
INSERT INTO `inventario` VALUES (7, 'Laptop hp victus', '8gb ram 256 ssd', 1100.00, 900.00, 10, 'Laptops', 'Zona digital', '2025-07-11 15:04:51', 1);

-- ----------------------------
-- Table structure for ventas
-- ----------------------------
DROP TABLE IF EXISTS `ventas`;
CREATE TABLE `ventas`  (
  `id_venta` int(11) NOT NULL AUTO_INCREMENT,
  `id_cliente` int(11) NOT NULL,
  `fecha_venta` timestamp(0) NOT NULL DEFAULT current_timestamp,
  `estado` tinyint(1) NULL DEFAULT NULL,
  `subtotal` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `impuestos` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `total` decimal(10, 2) NOT NULL DEFAULT 0.00,
  `monto_recibido` decimal(10, 2) NULL DEFAULT NULL,
  `cambio` decimal(10, 2) NULL DEFAULT NULL,
  PRIMARY KEY (`id_venta`) USING BTREE,
  INDEX `id_cliente`(`id_cliente`) USING BTREE,
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE RESTRICT ON UPDATE RESTRICT
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ventas
-- ----------------------------
INSERT INTO `ventas` VALUES (1, 1, '2025-07-11 09:00:44', 2, 20298.00, 3247.68, 23545.68, 24000.00, 454.32);
INSERT INTO `ventas` VALUES (2, 2, '2025-07-11 09:00:44', 2, 15198.00, 2431.68, 17629.68, 18000.00, 370.32);
INSERT INTO `ventas` VALUES (3, 3, '2025-07-11 09:00:44', 2, 4497.00, 719.52, 5216.52, 5500.00, 283.48);
INSERT INTO `ventas` VALUES (4, 4, '2025-07-11 09:00:44', 2, 18999.00, 3039.84, 22038.84, 22038.84, 0.00);
INSERT INTO `ventas` VALUES (5, 5, '2025-07-11 09:00:44', 2, 3495.00, 559.20, 4054.20, 4100.00, 45.80);

SET FOREIGN_KEY_CHECKS = 1;
