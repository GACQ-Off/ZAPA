-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-09-2025 a las 14:32:00
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
-- Base de datos: `vertex`
--
CREATE DATABASE IF NOT EXISTS `vertex` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci;
USE `vertex`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja`
--

CREATE TABLE `caja` (
  `id_caja` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_usuario_cierre` int(11) DEFAULT NULL,
  `fecha_apertura` datetime NOT NULL,
  `fecha_cierre` datetime DEFAULT NULL,
  `monto_inicial` decimal(16,2) NOT NULL,
  `monto_bs` decimal(16,2) NOT NULL,
  `monto_final_calculado` decimal(16,2) DEFAULT NULL,
  `monto_final_real` decimal(16,2) DEFAULT NULL,
  `diferencia` decimal(16,2) DEFAULT NULL,
  `estado` enum('Abierta','Cerrada') NOT NULL DEFAULT 'Abierta',
  `monto_final_calculado_usd` decimal(10,2) DEFAULT NULL,
  `monto_final_real_usd` decimal(10,2) DEFAULT NULL,
  `diferencia_usd` decimal(10,2) DEFAULT NULL,
  `monto_final_calculado_bs` decimal(10,2) DEFAULT NULL,
  `monto_final_real_bs` decimal(10,2) DEFAULT NULL,
  `diferencia_bs` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `caja`
--

INSERT INTO `caja` (`id_caja`, `id_usuario`, `id_usuario_cierre`, `fecha_apertura`, `fecha_cierre`, `monto_inicial`, `monto_bs`, `monto_final_calculado`, `monto_final_real`, `diferencia`, `estado`, `monto_final_calculado_usd`, `monto_final_real_usd`, `diferencia_usd`, `monto_final_calculado_bs`, `monto_final_real_bs`, `diferencia_bs`) VALUES
(1, 4, NULL, '2025-07-01 22:39:42', '2025-07-01 23:26:31', 50.00, 0.00, 50.00, 30.00, -20.00, 'Cerrada', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 4, NULL, '2025-07-01 23:31:30', '2025-07-02 08:47:09', 20.00, 0.00, 26.00, 26.00, 0.00, 'Cerrada', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 4, NULL, '2025-07-02 10:32:08', '2025-07-02 10:35:28', 10.00, 0.00, 16.00, 16.00, 0.00, 'Cerrada', NULL, NULL, NULL, NULL, NULL, NULL),
(4, 4, NULL, '2025-07-07 20:22:02', '2025-07-08 12:47:00', 10.00, 0.00, 10.00, 10.00, 0.00, 'Cerrada', NULL, NULL, NULL, NULL, NULL, NULL),
(5, 4, NULL, '2025-07-08 12:47:09', '2025-07-08 12:49:39', 5.00, 0.00, 5.00, 5.00, 0.00, 'Cerrada', NULL, NULL, NULL, NULL, NULL, NULL),
(6, 4, NULL, '2025-07-08 12:49:47', '2025-07-08 13:56:47', 8.00, 0.00, NULL, NULL, NULL, 'Cerrada', 28.00, 28.00, 0.00, 0.00, 0.00, 0.00),
(7, 4, NULL, '2025-07-08 15:01:51', '2025-07-08 15:30:52', 17.00, 550.00, NULL, NULL, NULL, 'Cerrada', 27.00, 27.00, 0.00, 2050.00, 2050.00, 0.00),
(8, 4, NULL, '2025-07-08 21:29:24', '2025-07-09 08:05:07', 5.00, 220.00, NULL, NULL, NULL, 'Cerrada', 5.00, 5.00, 0.00, 220.00, 220.00, 0.00),
(9, 5, NULL, '2025-07-09 09:14:24', '2025-07-09 09:18:14', 12.00, 220.00, NULL, NULL, NULL, 'Cerrada', 22.00, 22.00, 0.00, 1220.00, 1220.00, 0.00),
(11, 4, NULL, '2025-07-09 13:09:08', '2025-08-04 13:34:51', 10.00, 40.00, NULL, NULL, NULL, 'Cerrada', 10.00, 10.00, 0.00, 40.00, 40.00, 0.00),
(12, 2, NULL, '2025-07-22 10:47:32', '2025-07-22 11:03:40', 1000.00, 1000.00, NULL, NULL, NULL, 'Cerrada', 99999999.99, 99999999.99, 0.00, 1000.00, 1000.00, 0.00),
(13, 2, NULL, '2025-08-21 16:48:11', '2025-08-21 16:48:33', 10.00, 10.00, NULL, NULL, NULL, 'Cerrada', 10.00, 10.00, 0.00, 10.00, 10.00, 0.00),
(14, 2, NULL, '2025-09-01 20:39:49', '2025-09-03 14:37:54', 5.00, 100.00, NULL, NULL, NULL, 'Cerrada', 9.50, 9.50, 0.00, 100.00, 100.00, 0.00),
(15, 1, NULL, '2025-09-19 19:51:41', '2025-09-19 21:50:32', 5.00, 500.00, NULL, NULL, NULL, 'Cerrada', 5.00, 5.00, 0.00, 500.00, 500.00, 0.00),
(16, 2, NULL, '2025-09-20 23:24:32', '2025-09-22 09:10:49', 5.00, 300.00, NULL, NULL, NULL, 'Cerrada', 10.00, 10.00, 0.00, 300.00, 300.00, 0.00),
(17, 1, NULL, '2025-09-22 09:20:58', NULL, 5.00, 500.00, NULL, NULL, NULL, 'Abierta', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cargo`
--

CREATE TABLE `cargo` (
  `id_cargo` int(11) NOT NULL,
  `nom_cargo` varchar(40) NOT NULL,
  `estado_cargo` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `cargo`
--

INSERT INTO `cargo` (`id_cargo`, `nom_cargo`, `estado_cargo`) VALUES
(5, 'Cajeros', '1'),
(6, 'Producción', '1'),
(7, 'Cocina', '1'),
(8, 'Limpieza', '1'),
(9, 'Delivery', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

CREATE TABLE `categoria` (
  `id_categoria` int(11) NOT NULL,
  `nombre_categoria` varchar(40) NOT NULL,
  `estado_categoria` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `categoria`
--

INSERT INTO `categoria` (`id_categoria`, `nombre_categoria`, `estado_categoria`) VALUES
(8, 'Bebidas', '1'),
(9, 'Helados', '1'),
(10, 'Tortas', '1'),
(11, 'Pasapalos', '1'),
(12, 'Golosinas', '1'),
(13, 'Lacteos', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria_gasto`
--

CREATE TABLE `categoria_gasto` (
  `id_categoria_gasto` int(11) NOT NULL,
  `nombre_categoria_gasto` varchar(40) NOT NULL,
  `estado_categoria_gasto` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `categoria_gasto`
--

INSERT INTO `categoria_gasto` (`id_categoria_gasto`, `nombre_categoria_gasto`, `estado_categoria_gasto`) VALUES
(3, 'Internet', '1'),
(4, 'Mercado', '1'),
(5, 'Insumos De Limpieza', '1'),
(6, 'Publicidad', '1'),
(7, 'Comida', '1'),
(8, 'Mecanica', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente_generico`
--

CREATE TABLE `cliente_generico` (
  `id_cliente_generico` int(11) NOT NULL,
  `cedula` varchar(14) NOT NULL,
  `nombre` varchar(40) NOT NULL,
  `apellido_cliente_generico` varchar(40) DEFAULT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `cliente_generico`
--

INSERT INTO `cliente_generico` (`id_cliente_generico`, `cedula`, `nombre`, `apellido_cliente_generico`, `id_usuario`) VALUES
(4, '10254895', 'Maria', 'Diaz', 4),
(5, '31744007', 'Keilyn', 'Desantiago', 4),
(6, '30392231', 'ivan', 'Martinez', 4),
(7, '30107693', 'Damaris', 'Delgado', 1),
(8, '53762382', 'yusbely', 'Perez', 4),
(9, '23779672', 'Janny', 'de Angulo', 2),
(10, '30123456', 'Victor', 'Collantes', 1),
(11, '28532168', 'Edinson', 'Petaquero', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente_mayor`
--

CREATE TABLE `cliente_mayor` (
  `id_cliente_mayor` int(11) NOT NULL,
  `cedula_identidad` varchar(20) NOT NULL,
  `nombre` varchar(80) NOT NULL,
  `apellido` varchar(40) DEFAULT NULL,
  `telefono` varchar(20) NOT NULL,
  `correo` varchar(40) NOT NULL,
  `direccion` varchar(255) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `cliente_mayor`
--

INSERT INTO `cliente_mayor` (`id_cliente_mayor`, `cedula_identidad`, `nombre`, `apellido`, `telefono`, `correo`, `direccion`, `id_usuario`) VALUES
(2, 'R-285610759', 'Sabores Bocono', NULL, '04121667280', 'jesus11022003@gmail.com', 'los pantanos', 4),
(3, 'R-301076937', 'Las julianas', NULL, '04143268968', 'damaris@gmail.com', 'el hato', 4),
(4, '30602646', 'pedro', 'gonzalez', '0412534783', 'pedro@gmail.com', 'la viravira', 4);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras_credito`
--

CREATE TABLE `compras_credito` (
  `id_compra_credito` int(11) NOT NULL,
  `RIF_proveedor` varchar(15) NOT NULL,
  `fecha_compra` date NOT NULL,
  `monto_total_credito` decimal(12,2) NOT NULL,
  `monto_abonado` decimal(12,2) NOT NULL DEFAULT 0.00,
  `saldo_pendiente` decimal(12,2) DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `estado_credito` varchar(20) NOT NULL DEFAULT 'Pendiente',
  `id_usuario_registro` int(11) NOT NULL,
  `fecha_registro_compra` timestamp NOT NULL DEFAULT current_timestamp(),
  `notas_compra` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `compras_credito`
--

INSERT INTO `compras_credito` (`id_compra_credito`, `RIF_proveedor`, `fecha_compra`, `monto_total_credito`, `monto_abonado`, `saldo_pendiente`, `fecha_vencimiento`, `estado_credito`, `id_usuario_registro`, `fecha_registro_compra`, `notas_compra`) VALUES
(2, 'R-285610759', '2025-05-21', 320.00, 177.78, NULL, '2025-05-25', 'Pagado Parcialmente', 1, '2025-05-21 02:17:20', 'hola'),
(3, 'R-285610759', '2025-05-21', 80.00, 0.00, NULL, '2025-05-30', 'Pendiente', 1, '2025-05-21 02:20:51', 'he'),
(4, 'R-410360461', '2025-05-21', 100.00, 100.00, NULL, '2025-05-30', 'Pagado Totalmente', 1, '2025-05-21 14:57:04', 'panes muy buenos'),
(5, 'R-410360461', '2025-05-21', 45.00, 14.63, NULL, '2025-06-06', 'Pagado Parcialmente', 1, '2025-05-21 16:46:24', ''),
(6, 'R-285610759', '2025-05-30', 25.00, 25.00, NULL, '2025-06-15', 'Pagado Totalmente', 1, '2025-05-30 19:12:54', 'pagar para junio'),
(7, 'R-285610759', '2025-08-12', 150.00, 0.00, NULL, '2025-08-31', 'Pendiente', 1, '2025-08-12 21:52:50', ''),
(8, 'R-301076935', '2025-08-21', 1.36, 0.90, 0.46, '2025-09-12', 'Pagado Parcialmente', 1, '2025-08-21 21:27:49', ''),
(9, 'R-3080540', '2025-09-23', 60.00, 0.00, 60.00, '2025-10-30', 'Pendiente', 1, '2025-09-23 17:18:24', ''),
(10, 'R-3080540', '2025-09-23', 40.00, 40.00, 0.00, '2025-11-04', 'Pagado Totalmente', 1, '2025-09-23 17:47:14', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `creditos_venta`
--

CREATE TABLE `creditos_venta` (
  `id_credito_venta` int(11) NOT NULL,
  `id_ventas` int(11) NOT NULL,
  `monto_total_credito` decimal(16,2) NOT NULL,
  `monto_abonado` decimal(16,2) NOT NULL DEFAULT 0.00,
  `saldo_pendiente` decimal(16,2) DEFAULT NULL,
  `fecha_credito` date NOT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `estado_credito` varchar(25) NOT NULL DEFAULT 'Pendiente',
  `notas_credito` text DEFAULT NULL,
  `id_usuario_registro` int(11) NOT NULL,
  `fecha_registro_credito` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `creditos_venta`
--

INSERT INTO `creditos_venta` (`id_credito_venta`, `id_ventas`, `monto_total_credito`, `monto_abonado`, `saldo_pendiente`, `fecha_credito`, `fecha_vencimiento`, `estado_credito`, `notas_credito`, `id_usuario_registro`, `fecha_registro_credito`) VALUES
(4, 7, 23.20, 3.00, NULL, '2025-06-18', '2025-06-30', 'Pagado Parcialmente', NULL, 4, '2025-06-18 10:15:51'),
(5, 11, 73.44, 0.00, NULL, '2025-06-18', '2025-06-30', 'Pendiente', NULL, 4, '2025-06-18 19:04:12'),
(6, 15, 324.00, 45.58, NULL, '2025-07-01', '2025-07-30', 'Pagado Parcialmente', NULL, 4, '2025-07-01 15:49:21'),
(7, 28, 1.30, 0.00, NULL, '2025-07-09', '2025-08-01', 'Pendiente', NULL, 5, '2025-07-09 13:19:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deducciones`
--

CREATE TABLE `deducciones` (
  `id_deduccion` int(11) NOT NULL,
  `nombre_deduccion` varchar(40) NOT NULL,
  `valor_deduccion` decimal(10,2) NOT NULL,
  `id_nomina` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `deducciones`
--

INSERT INTO `deducciones` (`id_deduccion`, `nombre_deduccion`, `valor_deduccion`, `id_nomina`) VALUES
(1, 'SSO', 4.50, NULL),
(2, 'FAOV', 1.00, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_venta`
--

CREATE TABLE `detalle_venta` (
  `id_detalle_venta` int(11) NOT NULL,
  `id_ventas` int(11) NOT NULL,
  `id_pro` int(11) NOT NULL,
  `cantidad_vendida` decimal(10,2) NOT NULL,
  `precio_unitario_venta_sin_iva` decimal(12,2) NOT NULL,
  `subtotal_linea_sin_iva` decimal(14,2) NOT NULL,
  `id_iva_aplicado` int(11) NOT NULL,
  `porcentaje_iva_aplicado` decimal(5,2) NOT NULL,
  `monto_iva_linea` decimal(12,2) NOT NULL,
  `total_linea_con_iva` decimal(14,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `detalle_venta`
--

INSERT INTO `detalle_venta` (`id_detalle_venta`, `id_ventas`, `id_pro`, `cantidad_vendida`, `precio_unitario_venta_sin_iva`, `subtotal_linea_sin_iva`, `id_iva_aplicado`, `porcentaje_iva_aplicado`, `monto_iva_linea`, `total_linea_con_iva`) VALUES
(7, 5, 39, 1.00, 0.20, 0.20, 1, 16.00, 0.03, 0.23),
(8, 6, 25, 50.00, 1.70, 85.00, 2, 8.00, 6.80, 91.80),
(9, 7, 30, 20.00, 1.00, 20.00, 1, 16.00, 3.20, 23.20),
(10, 8, 30, 1.00, 1.00, 1.00, 1, 16.00, 0.16, 1.16),
(11, 9, 25, 4.00, 1.70, 6.80, 2, 8.00, 0.54, 7.34),
(12, 10, 25, 3.00, 1.70, 5.10, 2, 8.00, 0.41, 5.51),
(13, 11, 25, 40.00, 1.70, 68.00, 2, 8.00, 5.44, 73.44),
(14, 12, 25, 7.00, 1.70, 11.90, 2, 8.00, 0.95, 12.85),
(15, 13, 27, 1.00, 1.10, 1.10, 1, 16.00, 0.18, 1.28),
(16, 14, 25, 1.00, 1.70, 1.70, 2, 8.00, 0.14, 1.84),
(17, 15, 29, 200.00, 1.50, 300.00, 2, 8.00, 24.00, 324.00),
(18, 16, 25, 1.00, 1.70, 1.70, 2, 8.00, 0.14, 1.84),
(19, 17, 30, 1.00, 1.00, 1.00, 1, 16.00, 0.16, 1.16),
(20, 18, 30, 1.00, 1.00, 1.00, 1, 16.00, 0.16, 1.16),
(21, 18, 25, 1.00, 1.70, 1.70, 2, 8.00, 0.14, 1.84),
(22, 19, 25, 1.00, 1.70, 1.70, 2, 8.00, 0.14, 1.84),
(23, 19, 29, 1.00, 1.07, 1.07, 2, 8.00, 0.09, 1.16),
(24, 19, 26, 3.00, 1.50, 4.50, 3, 0.00, 0.00, 4.50),
(25, 20, 25, 1.00, 1.70, 1.70, 2, 8.00, 0.14, 1.84),
(26, 20, 30, 1.00, 1.00, 1.00, 1, 16.00, 0.16, 1.16),
(27, 21, 27, 5.00, 1.10, 5.50, 1, 16.00, 0.88, 6.38),
(28, 21, 29, 1.00, 1.07, 1.07, 2, 8.00, 0.09, 1.16),
(29, 22, 27, 5.00, 1.10, 5.50, 1, 16.00, 0.88, 6.38),
(30, 22, 25, 20.00, 1.70, 34.00, 2, 8.00, 2.72, 36.72),
(31, 23, 26, 13.00, 1.50, 19.50, 3, 0.00, 0.00, 19.50),
(32, 23, 25, 6.00, 1.70, 10.20, 2, 8.00, 0.82, 11.02),
(33, 24, 25, 1.00, 1.70, 1.70, 2, 8.00, 0.14, 1.84),
(34, 25, 28, 12.00, 0.20, 2.40, 2, 8.00, 0.19, 2.59),
(35, 25, 34, 2.00, 0.80, 1.60, 2, 8.00, 0.13, 1.73),
(36, 26, 24, 1.00, 1.20, 1.20, 2, 8.00, 0.10, 1.30),
(37, 27, 29, 5.00, 1.07, 5.35, 2, 8.00, 0.43, 5.78),
(38, 27, 24, 10.00, 1.20, 12.00, 2, 8.00, 0.96, 12.96),
(39, 27, 41, 10.00, 0.60, 6.00, 1, 16.00, 0.96, 6.96),
(40, 28, 24, 1.00, 1.20, 1.20, 2, 8.00, 0.10, 1.30),
(43, 30, 25, 600.00, 1.70, 1020.00, 2, 8.00, 81.60, 1101.60),
(53, 37, 25, 7.00, 1.70, 11.90, 2, 8.00, 0.95, 12.85),
(54, 38, 26, 3.00, 1.50, 4.50, 3, 0.00, 0.00, 4.50),
(55, 39, 27, 3.00, 1.10, 3.30, 1, 16.00, 0.53, 3.83),
(56, 40, 25, 4.00, 1.70, 6.80, 2, 8.00, 0.54, 7.34),
(57, 41, 48, 5.00, 1.71, 8.55, 1, 16.00, 1.37, 9.92),
(58, 42, 52, 10.00, 0.65, 6.50, 1, 16.00, 1.04, 7.54),
(59, 43, 48, 5.00, 1.71, 8.55, 1, 16.00, 1.37, 9.92);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `cedula_emple` varchar(14) NOT NULL,
  `nombre_emp` varchar(40) NOT NULL,
  `apellido_emp` varchar(40) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `telefono_emple` varchar(20) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_nomina` int(11) DEFAULT NULL,
  `id_cargo` int(11) NOT NULL,
  `estado_empleado` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`cedula_emple`, `nombre_emp`, `apellido_emp`, `fecha_nacimiento`, `telefono_emple`, `id_usuario`, `id_nomina`, `id_cargo`, `estado_empleado`) VALUES
('28561075', 'Jesus ', 'Collantes', '2003-02-11', '4121667280', 1, NULL, 7, '1'),
('29864394', 'Maria', 'Diaz', '1991-06-12', '4127748484', 1, NULL, 7, '1'),
('30107693', 'Damaris Valentina', 'Delgado', '2003-06-26', '4143268968', 1, NULL, 5, '1'),
('3032231', 'Ivan', 'Martínez', '2004-08-30', '4247453347', 1, NULL, 7, '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `RIF_empresa` varchar(15) NOT NULL,
  `cedula_representante` varchar(14) NOT NULL,
  `nombre_representante` varchar(40) NOT NULL,
  `apellido_representante` varchar(40) NOT NULL,
  `telefono_representante` varchar(20) NOT NULL,
  `nombre_empresa` varchar(40) NOT NULL,
  `logo_empresa` longblob DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `direccion_empresa` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`RIF_empresa`, `cedula_representante`, `nombre_representante`, `apellido_representante`, `telefono_representante`, `nombre_empresa`, `logo_empresa`, `id_usuario`, `direccion_empresa`) VALUES
('RIF-J3009495', '30392231', 'Iván', 'Martínez', '04247453347', 'Yucream', 0x89504e470d0a1a0a0000000d49484452000001f4000001f40806000000cbd6df8a000000017352474200aece1ce90000000467414d410000b18f0bfc6105000000097048597300000ec100000ec101b8916bed0000ffa549444154785eec9d076014451b86dfe4eed21bbdf78e74a44a115044ca0f225d1029a222022a2022581011458a028a8260434510040bbdf7de7b87501348af77b9fcdf37b39b2bb940800029f3247bdb67776767e79d6faa5b32018542a1502814591a776dae5028140a85220ba3045da15028148a6c8012748542a15028b2014ad0150a8542a1c80628415728140a85221ba0045da15028148a6c8012748542a15028b2014ad0150a8542a1c80628415728140a85221ba0045da15028148a6c8012748542a15028b2014ad0150a8542a1c80628415728140a85221ba0045da15028148a6c8012748542a15028b2014ad0150a8542a1c80628415728140a85221ba0045da15028148a6c8012748542a15028b2014ad0150a8542a1c80628415728140a85221ba0045da15028148a6c8012748542a15028b2014ad0150a8542a1c80628415728140a85221ba0045da15028148a6c8012748542a15028b2014ad0150a8542a1c80628415728140a85221ba0045da15028148a6c8012748542a15028b2014ad0150a8542a1c80628415728140a85221ba0045da15028148a6c8012748542a15028b2014ad0150a8542a1c80628415728140a85221ba0045da15028148a6c805b32a12d2b148a2c829b9b9bb6f47050d1844291f95182ae5064021eb6403f485494a2503c1a94a02b140f81ec24d8f7838a6e148a0787127485228351e27df7a86848a1b87f94a02b14f78812ee078f8a9e148af4a3045da148279945c01fc5279b939f5da1c82a28415728d2e0618b5876f91495bf29148f0625e80a85c6831622f5a9293f56281e244ad015399607252eea93ba7bd4bb5028ee1f25e88a1c45460b87fa7c1e1cea5d29147787127445b627a384417d2a8f968c1478f52e15d91125e88a6c494644feead3c8fca8f7ac50d85082aec836dc6fe4ae3e85ac8f0a038a9c8c127445964645e08ab450614391d35082aec872dc4f44ad827bce45851b45764709ba22cb70af11b20ae20a67545852644794a02b32352ae2553c68541853641794a02b3225f712c9aaa0acb85f54b853646594a02b320dca52526416545854644594a02b1e39ca2a52646654f854641594a02b1e192aa2546435ee36ccaaf0aa78982841573c7454a4a8c8eaa830acc88c2841573c345424a8c86ea830adc84c2841573c7054a4a7c809dc4d3857615cf1205082ae7860a8084e911351e15ef1a85082aec8705484a650a8ef40f1f05182aec8305404a650a4467d178a87851274c57da3222c85e2cea8ef44f1a05182aeb82fd21b49a960a65048d437a37850284157dc132a525228ee0ff50d29321a25e88abb4245420a45c6a2be294546a1045d912e54a4a3503c58d437a6b85fdcb5b94291262aa251281e3ce9fd7ed2fb3d2a721eca4257a489127285e2d1a0be3dc5bda0045de192f444282ae828140f16f51d2aee0625e80a075404a250643ed477a9480faa0c5d91828a34148acc497abebbf47cbf8aec8db2d0154ac8158a2c84fa5e1569a12cf41c8e8a1c148aac457abe4765ade74c94859e435142ae50647dd477acb04759e8391015092814d903fe4eeff4ad2a6b3de7a02cf41c8412728522fba2be6f85b2d07308ea635728b237e9f97e95b59ebd51167a0ee04e1fb10a020a45f6427df3391325e8d91865952b143917f5fde73c94a06753540a5da150302a2ec839a832f46c88fa80150a85ce9dbef7f458f28aac8112f46c8612738542e14c7a445d097bd64765b9671394902b148af4a0e28aec8bb2d0b301ea03552814e9e54ef181b2d4b32e4ad0b3384acc150ac5dda2443d7ba2043d0ba3c45ca150dc2b1c3fdc2e8e50a29ef550829e45b9ddc776a70f55a150287494a8671f94a06741ee24e60a854271372851cf1e2841cf6228315728140f823b89ba12f6cc8f12f42cc29d3e2825e60a85e27eb9533ca2443d73a3043d0b70a78f4889b942a1c82894a8675d94a0677294982b148a878d12f5ac89ea292e13a3c45c91161673221213e291101f0f736202ccb49e9494046b92054996245aa639adebdbdcdd0d301868321a694e93d100779ed336a3d1040f4f4f9abcc464f2f0d4aea250dc3e1e527150e642097a26457d443993c8f05b38bc77278e1d3e8093c78fe2dcd9b3b8187c1937c323111d1b8f98b84444c59bb5a31f1cfede26f87979c0cfc70b79820250ac6811942e5d0ae52b5646e52a3550bb611398bc7cb4a315d91d151f650d94a06742d4c793fd3976700f766d5e8f03fbf6e0e8b163387bf132ae85462092043bab10e4eb81c2798350aa781154ae5409356a3d8eba8d9aa1ec6335b42314d909152f657e94a06732d447937de0f77564ff6eecdab20e07f6eec1b1e3c771e64230826f8423c162d58eca7ef87a1a502c7f2e9429592c45e8eb376981d215ab7200d78e52644554fc94b951829e89501f4bd627262a12cb16ff8e352bfec38edd24e2e7ae22de9ca4edcd783c8ceef0a2c9d34493981b1cd6bd689d8f49a40404df07272412ccb42ce68eeb7ccc83c2dfcb882a658aa27eddc7f154abb678aa5d277878fb6a7b155909154f655e94a06712d4479235b9167c01ff2cfc15ebd6acc2eefd8770e6ca4d2459efff7de5f3f7407e7f4f1408f4445e3f0f5af72471b6136d5a66b1ce6858f4e349e075b1e7e590a804844627e27a44026ed07248d4fd170bf0fd57285e00756b5547f3a79f41ab0e5d1194af90b65791d951f155e644097a26407d1c598b53470ee2d71f6662f5ea35d87bf40c6213efcd02cfe56342fe004f21de2cd8f9792ed6337f2d7316f9904816f844b94cf31bb41e167b6f15f67253a2a5ce6365d1b2654b3cff421f94a8504ddba3c8aca8782bf3a104fd11a33e8acc4f544418fefcf97bfcfbf75fd8b2fb00aede8ad6f6dc1da5f3f9a04c3e5f94ce4f132d3f080bfb51c316feb990589c0989c1991b31384bcb778b3b7d12a50a04a251dd5a68dbfe39b4ebd6079e3e7eda5e456642c55f990b25e88f10f531646e4e1e3e80695f7c8225ffadc4a590086d6bfae0ac7116edd29a8097a529a7729a855d88bb14f8bba910c8e25ea5647e3cdfbe0d5e1e3c12854a95d7f628320b2a1ecb3c28417f44a88f2073c265e2df4cfe140b172fc1b1f3d790de3711e06594024ec2cd5678d1dcdeda9e7bc7f9da698598b4eed1f97887e3d2e978daa1f4de090e8b1302af5bf091f1166dcfede1ca7d752b1547b7ae5dd0eb95371190b7a0b647f1a851f159e64009fa234005feccc7d2f93f61fa9793b17ee7219893ee6c4172453529de321b9dcbbeef95b48436d96ec54d9756bb99d84b3ff6c7e9381fef7c9cab60e62a58a6b8a373fbd57b82cbe059e04f93b8f39c2be0dd093f4f239ad7ab82feaf0e42bb6e7d5ddfbce2a1a2e2b5478f12f4878c0af49907b6c6a77c3206bf2e5c82e0d0486d6bda70a5b5da2583c4742f15d71cdeaeb6e22cc6f641c07e8f1e6a38f80891a57f3dbcf08c97f473e53176c7127c1dde2f2be0cb6557c8e029cfd1dd71461e23d1ef452e670c2cf07bce8763cf850851d1ee4e14cae58b0e6d9ec69bef8e45b9ca55b5ad8a47818adf1e2d4ad01f222ab0670efef8f15b7c33fd2b6cde7b0c963b34310bf436a2560929e2c5ee321b3dc5656dc19585ac6fe1f7efb0cd6e99438d105731b9897265868fe7dbd7cfd54e91c7d1dc9d0ed40e15fbac7470a17235e01d900b890971880cbd86b0eb9760b558c4393a6251b8a19d2f966dc8636df721af270fd2366518976ec50971df7b211c1171b7cf9ae76b735bf75ebd7ae2d537df857f4080dca178a8a878eed1a104fd21a102f9a3258944ebeb2f3ec6f419dfe0647088b6d5353e1e06542f162044bc5c81bbab5d9df22669411770fdf5f24c2edb8937cde5767934af7bfa05a05099aa2850b202f2142e893c454ac16034e1d0fac538b8ea0f11963838f1392ce885cad744cb7eefd171a5c5a02dd7ce1ec1df5f8d407cc44d711c4b1d1f1b50b0385e9ef237dcdced6ad7d3f6d8a870c4d0b13111b710131e8ae8b010318fa279d4cdeb24fc57111172995304c23d7652bf0716753d91f1a0445de7d4f56821ee072e45deb1a960c15c7e18d0b717867f30017efe4ad81f362abe7b3428417f08a8c0fde8b815721de3470fc38fbfff89d0c8386d6b6a4c063754292245bc6ad17b1300f126e987a5597fad291634efb2dbc7939bd18842a51f43fe92158518e72b56067969ee9fa7803cd98984d8284c7da93e89a7b49cd98d24fae9fbc562e42fe158fb7bfbd2b9d8f0cb1770d714968fadd1b23b5af67f4f3be2eeb05a9310c7c21fcec24f13097ef8f5609cdcb112b72e9d12b9010f43d4750e05470a713f7c3912e6247ab834f0f534a273bb96f878e234142d595adbaa7818a878efe1a304fd01a302f5a381073ff970e4db58ba7ab3681b9d16950af90911af4622ce3db0dd89b4de18bf65de27c49b16acf4c362ce735ecf5db40c59dc959097e6f98a9545ee422590bb484931ace9ddf0fdb0e7117af1b8104ebe161719bc39770b7c02726947480e6e588a7fa68d8481c31ffdf33dd47f6e009eec31443b22634826abfd8f4f06e0d2e1ed3090a28bcb69098ef4c07ec6a4f77867b847bb839ab81fbb9a76ff009c607bfa89da18f3f104d1afbce2e1a0e2bf878b12f407880acc0f9ff05ba1183eb02f7e5ef4afe8a3dc151cb9d72f931b4f55ce87201f93b6356d52de142d485bdb115dbe74eb9b45dc3b202f6ab7ed8de295eb202f59de26cffb6fc6c60841bf705cdc852ee843676f807feefcf2008d63db57e3cfcf070b9165cb998fcf5faa323a8f9c91ead8fb65f5dc09d8b7ec1761a51bdc65b84f2be4a7f85e6a6f648fb49fdd35e1b166ac3916826da76fa569b5f3bbeffc6c134c9c3e07854b288bfd61a0e2c1878712f407485a01597979c6131f178b0fde1e886f7ef8155171aebb1fe5ce5e1a95cb8de695f2c1cfcba86d754dca1ba2055dc4f5d7c6337d995f31bf659ef336decc7db9377bf11d3cdea69738e67e89bc7903372e9ec489edab7060cd0291e5cef075787a63e62ae42a50546cd339b967037e1ff7aa10303e9eef8b131a49c9ee28f6581d04e62b222ac7f906e486b77f107c682a58a21c72177474273d2c9af4164eed5801a310741673fa7311f48597093f124b297ea823fd52fcc87539bb6ba2e32d58773c149b4fdd147dd1bb82eb49f4e9d20e9f7c390b81b9f36a5b150f0a25ea0f0725e80f0815801f0e4949164cfa6824267ef9759a65e43c9c6793f279d0b4425e7853449e16296f8516ec458797f89dd996e9fdba1b50aa7a43845fbf88f0ab17b4f72d8fe1726d16f4ba6d5f6427ee8aeb174fe3fcb17d24e0a771e3c2295c397d0809b191422c8d64fe7216ba3b59c1e23a24e66c89be3efd5fe42bea686d9e39b00df33eec4be790c84aa54c3927d5443b78deeee55178f2f9be9a0b1273620276af5e0cbfc0dcf00da404008b7f804c007045bda85b37f0cd1bad916c8ebbada04b7fe35f4e58c8656d55206e917ef834fbe5fb212e31091b4fdec48613a18849705dec12e4e381375eee85319fcf80c923f3f7a19f95492b4e54f161c6a104fd01a0c4fce1f0ebac6918f9de9834bb650df036a259c5bc78a25c1e619da7857823da6b6121e757c4abbae8f0a49789735978b527dba352c35608ca5f04491633fef8e415041fdd2904888f65812cdfa0153abe3549b8e90c57300bbb7e99acea22a9cad0d7fe310bffcdf95c08a32ec69c95ad8b258b394b1ddfa785c49c3bc17975ca5f2858aaa2e682e4c2d13d983baa273c8cda791c26b57b63f116d63a19afdc948db3ed2d562bbabf3315359bb6d15c90dcbc76099ff46e26b3d35964c91921da3ca71fbe4791d8a01b4bc972e73fbb4f802e9be297ec87a25e819b3b9a761f8aca8d5a5382251a5bff9c8913db968be20151e98fe61921ea0c0f0bbb85ac75b6dad36afa56248f1fc6bdff2e5e7ae35dbeb0b65591d128517fb02841cf6094983f7836affe17afbff60a0e9ebeac6d71248faf092d2ae713427e3bc4dba01f1647b14833161c9bf848110f2c500c559ab443f93acd50b0746571ac3de70f6ec3ef1ff7d7b2b6e5b95c84fbdc9b931090af3009560c59dea770fdd2195c397702174f92d51d9f80d13fae45de42c535572447b6afc14f635f4b114a16cf9489054e0b5e7c0d3389308b55ffcfff40d1f28ea3935d3e7508b3867716091929b672bb7c2ef96cba85ce826e66773efd1965aad593076a9c3fba1733deee9aea1ef859d94d7d9bbe9ff7d90b315dcac13f3911c17ef3fc8869c23fedf969540f5c3f7d30cd844146c0c2bee668086ec6b82e96a95aaa00a67e350dcddb76d6b6283212153f3e5894a067202ab03e588e1ed88d375feb8755db0f0a8170a64080a7a8e856b7b4638d6f5788d3e94717605dbc85f54873efc0bca8d2b81d2ad47f0a45ca55839bae882eb092953ea15b752968f4c76eea96afb08285dbda75681b5f8785b4dfc7b351a94e53cd15c98de0b398f24a2bd16fb92eea36ebd826707c3e5be72ce82f7df20b4a3ef6b8dca171edfc09cc1cda5eb8e341eac8e7923302be3e8747dd5a674b9fdd19fcf57fc85fac8c3c48e3d0969598377e90288b37901fb0d0ea16b43ee77be37b64f7f9f9b57ff65e711df1dce25a1039016ddf98806a4dff27dcb7e7e0fa25f86fc6a894840cbb6d9f38c848769d0bc3aa2321b8eea2273abe6e8bc72be3ab6fe7a0524dc7048ee2fe51f1e48323ed584a7157a840fae0b87e2518ddda3643f55a75b1725b6a31e70a4e5dea14c6a8b6e5ef4acc7591d1ad5493b73faab7ec8e6e1fccc51bdfad458bdec350b4428ddb8a39e36630c2971200ec2687026efbcd02c7c2c44268a2398baa58d6859aa6d0cbe7a40376e429588c8e310a41d3b3b26d963a8ba8bc863e3149e6d47d9f1b4d1eda927c5e711e4d2cc2ec8e708f976912d6364d7e41a97334b89319be57937eff622e27797f7727e62d5e1ae952cc196ed2c7ef82131a9ce87890d429954b84170e371c7eece17b5ebdeb28aad769805eed9ec48dcb17b43d8a8ce076f1e1ede251c59d5182fe8051627e7f2cfe6536aa3f5611f3ff5d2f44d799fa65726174bbf277cc5ed7112ed08fb0a269ce6253b8521d741c3e1d6fccde8856fd47a36495ba2452e96b1f6e494cc0a60533111d1e2ac58c2691154d0bba18b388eb2228855d6ee78a6fce7045b342652a09778475caee6973164c219a0eb8c16c4e6d65b2a03b073d71aeee0ecf85bb729bd1c303de7e81da9136b8b73821fc74bfba80cbc4856da27fe1a6f66ff363cd7f7531afd7be3feadda6a220d7d46741e7f3f4c480742c7de887dfcd691c6e38fc34a070e40c5738fce59f0da855fd31fcf5d337da564546a044fdc1a0043d03482b002a31bf7742ae06a3dd9375f07caf97713d3c46db6aa3682e2fbcf54c1974af5754f4067637b098eba2e1e99f5b94e796abd34c88697ab9753d189b16cfc1b437da62fd6f5f095193c22bad5d21769a252e449de62ce6ba75cefb6f5c38a1b9e648c19215c49c4315072d31f19feb6046167aeaf2608366a13b0741e184e68eee1ecf72172c2eeedd99a85bd7c5f388e7a31f91fdafe51488e715f7452bf25f0a29fdb090db8b798da7bba279cf37796f9a70f3392fff5ce2bcf47c397c8c98e8474efc5eed276dbb765c5a70f8e946e188c313872b672edf8c41c79706e2b9276b91b57e5edbaab85ff81d29321625e8f78912f38c67d6d4092857b60cfed9b03b5544ccd9a39deb14c6f067cba1441e1f6debdd23ad73a070f91af0f4495f7fedf1b1d1d8b3760966bdd7079ff76d8ee5733e43c4f54b42e4381c704860a113fa46132f3b58b2fa32ede4f9d5d347444f6bce704f72a91e9c1d4f85bc26f7dfee8cd194fe26587ca980bc85e48a13dc973bddaa783e31a76dba88ebcf29b769b74c3f2cc7c27f45718615951ab545ab97478b73ee44c1529584084b77a45b3c77466ce363e860793d3dc7c57e92dbc5b728ffc594161c9e385c71f872950dffd7867da854b1227efa729cdca0b86fd28a27d3135614a951827e1fa84097b15c3a7f1a4fd4288f016fbe8b88d8d42255bfb4cc5e6f94ceec759750fc21e277fae1c8fedab9e3da0ed7703bec23dbd762de676fe3c36ef530ff8b6138b37f8b106491fd4c6180854ecf16676c82272769e1eac7d1c4162e2fc08a9b575397cfe62d522a457cd2a31bae05dd56869e16c21f848790a0a7d17f7c44c855a1d674fb84f68cbcceab7688dbd4dc63ab5c560ab4a244f546f8dfa04fe879d35784c103d1f07d59d925e1a823bc494cb453177151eece5392ace0c7132fa76ce7fba163ed85fd7670f8e270c6c539cedc8a4ec04b43c7e099ba15117cf6f66147913e94a8671c4ad0ef91db05b6b402a8226d7848d3ea55aa60eb8153da161b8583b4ecf5fa779fbd7e276e5dbb84ebe75d677d6f58fc233ee8de10df7f3000fbd72f45b2c59c625df35c08362ddbc45c9f4bc14b99b4059e0b81e7e3b47da12eb270f3162929c44c0f46627e9b2095e4a20cdde46997759ce6b97c11fe4f867f9ed4ddc17238be75e5bcb85b79c7ae11ce6beee8e2c9f51d0a97af854ec3a7de5551068f2ea70baff6e308efa2fd7c085f87059b05bcfc136df0cc6be3d065cc6c3cffee3768def73d94a8d158dc872eecf49f22ec2e5c7680c31917e770b8e3f0670f9fbb72f749d47bbc36fefc7eaadca878202851bf3b94a067304accef8e84f838746bd30cdd5e7a1561318ec2c4d99e9d1e2f8c775adf5ff6fa9d38b87999b6e408472671d111291636c72d1e5e3ea8d9bc033abd3d116fce5a83273a0e10e272278590022e6591dd61039ddd0f093e2bf6db93a7500951b39e652795b3ec8006bbc3932b0b9dd1cbd16f87b875faf10b4addfd696c6498b803711d5ae7b933e2fee8878fd3059305365fc94ae8faded7c2bf5cf1dfcfd39048efde99dc058b0937c5241cb721b7d31f2d0821a789878e7df1d3f9f8dfe0cf50ad590794ac5a1f656a3646ed56ddd19984fdb5af57a17cc367a5b0d37d494167b7d9a53bc3e18ec31f87436f93637479252c165d07bc85fecf3547625cea7a1e8af473bb7853897afa51827e0fa415c09498df1d3b37af43c5928531ffbff5a922d77a5af67ae3f2aeb3d7f978e7295db0386993b68a3d2b17827b6f73a6469367c5bb36984ca8d6f859bcf0ee5718f5cb16747a93c4a3693b04e62b8c662f0c45f1c7ea68d79762912676c186dd6551bf7131758e048b39776023dca249ce6c02e41cfcb8a6bd2b4c7662eaeab6ecefd5d5802d5cc3ddfe52bc6c6fab8bd3e987ef4c88398b264d416465771ff32dbc7c5d0f43bbece7e9f867ee64845c495ddc90bb5071e196fe2dd9fb8198d3242c6e5ac853bca210ed4265abf05e97f03bea307422dabf35056e1e5e74ae6ea5b35b363fbd131c0e47b7ab20c2a53d7c2fdfffb50eb52b95c4812dabb4ad8a7b41c59ff78f12f4bb448979c6f0dee0fe68f4640b9cbf1eae6d91b0553eb07929f448237b9d7d5978354d2242d6263db2e75de9435adc9c651e1b1e8ab3077768db6d04e4ce8797c7cec687bf6d2731ff12551bb582a7b7afb6d746cd965d53444227adfb10d7d48210cfb8031857f0f8e6ec465a65c9f6a465a17b388df096225f34d3dd147e4693eb36e8a1622eee57bb6707f85cfa6301d63bd2f1092a801eefcf866fa0eb84d8b25f48cc7f984c6eba21c44571831c6446de294ff6a45c8b26ee33a0d388afd25da1b1528396e8356e1e4c3efe0ea2ce1771be4e5af0803e1c2e397c3a579a3b7c21148d9f6a8dcf460c40b28bc4a1227da4158fa615ef2a1c51827e17a84075ff444584a149edca183fed7bd1ced79e1279bc45f6668582a9236911f772e4cb11b188d86d913b6f171134ff89f99d61eb5864a3f3327d058736b9ce76af54b729bcfd5c5b9a3a427cb5ebca896f4a2ebb464b4cd0cfb5b347457ff0ce704d773da1e2ca25fba09896a03b94a36ba47649deb77f503eb96a47142574f81ef952769713f0397ca6f07b7a092ce61e7eb9d0fdfdef1098468d7973423cbc4880cbd7a82fd6af5f4a5ddcc0e5eddcc10cbbcb17d1af23fec524df7db5661d455ffa7743819215d071d897c2aa16e186b6c9a7bf3b387cca6220c7045354bc05ef4e9c85ae4fd5415c5498b65571b78870ef0215ffde1925e819405a0150e1c8fe9d5bf058b992d8b4f798b6c5060fa2f2d633656f3b3eb92e206c61896c579e285160abf8c4ef42be8fb4de8814271931f0af684e4611c5fef54b9098e07ab4b63b71fdfc492d71c1f727ef51dc81fc4f752f1c2ff13d8839fdb8aa18272b87a53ed71ef12cf4e3aa521ce34ad0d3c23728b7b664837b89e36bc82bd910cfa43da7fe1e0c9e3ee8f6de4cd9e42e0df87e9a757c094326fd8af1f3b791b037d0f63892bf78b954fec6fec9ebeccf7c3d1e1cc715c7766dc0967f7e15fde5bba24495ba2857ef29910849f96eed2f944e389c7278e5706b0f3bb560dd3e34a85e0147776d941b158a878412f4749256ea508979faf861c61768d0a8292e85446a5b245cd1a87f9312e850cbb555c7b00feb02222c738a8cf396ac8c7643bec0c0efd6a3df947f1054b8b4d82e45559e94e69ba157a98b29cfb90959b2251147b7add10e481f9137af63c5dc89f86bda28ed1ed95ad5ee9197c5bdc81be1fd1c828490f35cbb07ce2908bd92ba0b58aee9ae239c6007784a811d6037dcc46865ae48190e541e4a93bcb6339c6dedaaf21a3f1fdf9f7eaf3c0907b4fb90cf4893bb115d47cd44e1db94653b1398b7004a55aea9ad39925b345d937e279e5b43dfc6fe1a98bfb0b6d546f099a39839aa2f167cf53e260f7a0e2b7ef94adbe348c5fa2d53de97bdfbf702875b0ebfce15e60e9c0b41e3664f63d16cd723ee296e8ff86e5ca0acf4dba3043d1d2831bf77922c16f4e9d40a7d070d47bcd9b16c917be5e2accbaa456f9fa5ed20202498c5ab3d819e1fff8c8a64a5f906e5456eb2669b741f2a2c7529a87c8af8491396363ddb5dce81831bfed6f6a64d4c641876ad5a84efdfef8f4f7b37c1a645b3456ded64baa8b056294cc8fbb0252ef47bb1bf1d0e515260dd1072f18cdc6807570ee35aea6905310e92a2cf789adfbc74c66507357191b7c4f3f1b5d2c60d7e81aefbbf0fbf7159847d19fc6daef0f388e7a21f7ee68e6f4f46f1cab5b5bdf70ff75a27fd8db17980be8de7ae3ace09bd2cc7a5673fe1deec5692a0bb2aa7e76c7d76c31ea7d5bb82c32f8763e75ee66ec524a2dbabc3316640275812e3b5ad8af4a244fdee5182ae78605cb9781e352a94c00f7fae481561362a975bf4ca95cbf7ce4dab181199d30f8b648da73bc3a85b9f1ae5eb364791ca8f4b3115c771242da43415f6d1012fb3e871bbf293bbd72322f49adce1c4919debf1c3b8c1f8b07b43cc9ffc0e1dbb91ce25d7c901be065707285db309dabf3919237e3f84be53fe864fee8242f0f47b11d0f11c1fe91397dfbbeadc863b6261cb998fb187576532403b9f7eae9e3e88c55f8dc495d38745bbfae09307f1df77e310792358889bb31bf6f0bef0eb9770e9c47e6d8b84fde1c2c1ede2fe44bb792737f89978aadcb82d2ad46da16d75e4dcd1bd18dfef692cfb69aac8024f6f02388f36a4ac7ce7b6c99eb0ebc1da928d2a0d5a88f279d14f004ddc97fe8a1f273b24762e1edb8b65df8ebdad9fdc0b1c8e393c73b8b687eb898c9bf5275ad57f4cf8b3226350a2ee1a357cea1d50d6f9bd71f4c05e3cf3543304873a66b1f3709e2fd42f8a1ac5530f049216ecd52cd22c8e1c413ed57714eab47e41db6be3ea9923f87e3889bdd67fba5e3eceeae7fc16f9edf13bd4cbe37928d2048b15cf90db4fb44f3d80c8dfdf4fc49af9df0acb4f7797a7c2652aa1768b8ea8dcf0e95495c1f6aef80dabbe1f27ee453f9e6fc4f9ba5c996cf0cc950e35e8599ce78ce822fa80d7fb7e177f7c3eedd7dde09c00bd0e819e23c0c7703182fd402af42fc232cd5c3e3b2748cad67e12252851c489891dfffe8ce89b57841bce7e29cf95d72d5bef193c4f16ba3357cf9dc08c113d90101d29aec970ae438d27dba172bde6285ec171fc767b38ab7f72bf27e16994a3bbc95ef5e4b3f2fbe7fbaddfa13f5af67e5b6cb727212e060ba6be8bc39b978b67662ad47912656b3444e8a53338b0e64f7a16b2f0b567d29f8b1f2da3d87f3102f3b6078b2169ed79ac781e2c5dbe06a52b55d7b628d2838a83d38f12f4dba002d2bdb169ed4ab469db1651718eb5b7b9c7adbe8d8a235f40fafb1967d8bb6d826e85c93708af4e5d02bf5ca96b662f99f61e0eaf5f2c844046d8362173c6d9dd044b3272172d87c1d3976a47d8b874ea30260fea202cf93c050ae3f1a73b8a666cfa402aaee07ed0a7bfd23c4590e95f8629bbebb22872472c05cb56154dabae9d3f89d8c85b387f701b5dcb4ac293fa3938f47118d4ef9f0d50ceea17db681f1fc3c78a6e69d9c2e6395f57fe3b9cafdf03cf5312047c3e5f93ce118911bbeb337cae7e1e0b6c9777bf41f9c76de3badfbc7a113387f74074d80d71ae388b7ed86d792e1054b018aa376d8bba2d9f475ecd22d7e1633eedfe3892cd7152d0e95cbe343f277727cbd74c7233e0e5cf7e43d17255b5b31c59f3eb34acfd6dba384fcf6110ef8026fb04caedc2c7fd10129980591b2fa41a6f3d8fbf17fe5a381f8d5aba1e4256e11a3dec39c36145614309fa6d5081e8eef971d60c0c183838957552ae802f06342d292cf4bb85bddb26bc527c6b3ed31ded5e7d5f3bc246e4ad1b98fe5a4bb825998575cb1137bf46579136bf457e97429cc8dd4421ea56bc3679118a947d4c1e64c7bf7327a3d2e38d50eab1c745e72fe9e1f3171e07ccf1e23e5244912eccd2cbc2966261d37d70393cdf131fc262636f453a3f837eefd26f6ccb3afaf174aacb73f947bf07e107daf9326ccbf3444280eed93e41204ee5e3e93cf93eac70f7f046c7b7be204bb819ae5f388579e35f47d8d50be21c71eff234715d3e87afa5b74e78b2f32b68d377983cc08ed9237be0dac9fd2231c4eeb01b1c06f4c407bf2fef5cf9f1ca67f344ef72aed8b0e05bacf9798a780699b8d1fc532ccbe7a27f07bfc948382ccdda701ea7ae3bf622e7653260fae44fd16fd0706d8b223d886fc70532cc2a98f4c54a39101578ee9ef7de7e037d060c4a25e6d58b0560508bd2f724e63a32e2e54858ce77fdf7ab100f670272e747c3e75e96a241138b80e036af4d0a9f9c38b23fb465b9b6c791367dde42e9aa75d32de6d7ce1d232d8fd5d6ecd08296104d728a855b4ca4f89c0811e3a5eb563d4dfcbcae2487b7f13e4e2808a1e273f489d769bb38977fec106bbc9dfee8302974748c7e0ff6d7d6454f1e6f83b7f1b97c5c52621ce68f1f880f3b54c4d783db21f2fa2531542cbf6f7e16311ebcb6ccdbc498f062ddcde5e0344cbe62655212713c2f54a1265af47917dd467f87f28f3713c7700ec8acf75e42d8f5cb62dd99a6945868daf535f99c749fc24fe8c6ef47cc3918394f69e149cfc8e19ec3bf3d5c39f4e5374660e82b2faaf84491a1a42f66ca612831bf7b5eeffb02c64f9e9e2a82ab5b2a087d1b97d0d6ee11ed75d80b2f47caff7d3f41ee70a261fb97e013985758bff44fef8d235efe4b0d47e7c279fad1056aff9ac5b02659c4fe7bc19c1087edffcec3bcb1af48d1a06df6414aae4b4191cfa28ba926a8daba14543e5efc88f374c4326f6377f88f96f9587de275b99d16f4e3ed10ebf423fd534b1068135f5b2e6bee686ee8c82bcaedb684804c8808c1e6b26f4dc0451d00cd4d3db192b29de6378353d7f06772172a21de1d8b79931e6f89560d8fb7ee8932351ba1d3f029a24b57beadb06b97307b745f51eeee8ae63d06a35435d9918d7c16f9cc62593c45fae0b0233e7f0e4bb4a04f725dccd284c33f7f07f6f0f15f7ef733fa766d273728ee485af1af73f8ccc9284157dc373d3bb5c3d7737fd5d66c3c55391f5e68e03a3bf46eb18988263eb47076ff669cdcbb591e608787b7afe8635dcfd2665110718116f18a899779bbf8d3366a117c7478084e1fd82e96d30bf705cfe72c9af63ec6f76c8865df7d8cb888503bf1488d1063f13cf2996c42ca229bb698ebf03631f17ef943ffe4189dc8b9087232c86db42f593822eae60bb455fa97d76327c435c59cb7f236fed58ed5e729c7d0443f52b0a5652fc49a97699bfe2ce2d9685fcab298e431372f9f71d917bd6f402ef17ef81dd668d151db2ae1160e755a7515cb7c9f37af9cc3cc777b8b1afecef0fdd76bdb4b5b93f72de6e221c4ff6d710e2b1c966442439fb43024e65a3072017f074f3f96bacec70f0bfe45e7d6cdc4f98a3ba3fce9f6a8327427d24aed296f724d8f0eadf0db9215da9a0dee70c3b9172d262d5f4c5fc42a23532e7fe5f26e2ebfcd55a42cde98b68484c5b16f6d16d8efdeee8c5b978e0b819159aeda4e427f9dec1ebb2bb2e76985cbe8b97cb66ab30ee8fca6eb1c007bae5d3885839b978b015e2243af3a08982e5cfab5a5406a276a88dbd0ee454a8244171cb3c10b71f04464a23b6ec55a7023220e372362101d9780d8f8449a1210131b8fc8681759fb7720d0df173ede9ef0f1e2c9037eb49c37c80ff903bd91cbc7884093153e6e09305a1c7bd0d3ef92fdcdde1f75f819f9311dc453839f91455a2f7f67ffeef7d9ef2856c1b1e6f7e2af4661ff9a45c28da1dfad49d5cdebcd2b1730f995966299df1bbfbf82a52a61e8b4c5a9c202b7a79ffeead35aae807c3f7a42e5768847a21fbe677e4e3d61c8db4b567b02e70f6ed19e534b7c89e9f6eeae3b1e8abff65ed5d66cb46c541bffaedb0ea331638707ce8ea8383a6d94a0dba102caddd1b56d0bfcf1ef5a6dcd46ef278aa15689d4598c7ae4e80a5dc06e1719f26be04855548ad205c1928cff0d1a87c75b76d28eb271f6c036ccfbb0afc8fe657115912d5d80dd116244c7480bcb0da56a342241a88c9dcb7e43744418920d1e18fefd5af8e74a9d288989b885032ce2ab168936df2cd642b4c971160bdd6abd9bf25aaeb51dedee8f5bf16e080e8bc3d92b3771e2fc1521d68f1a5f1f2f542c5504a50be546915cdec8e3950c3f6b14451e4969be53fd7dca6589388a7eecdf21279e0a57a82172544c1e5e6284b763db57e1c0dabf847ff17bea347c32aa366a2ddcb067c1e477b06fed62f13e657d0960cc2f9b1198a780768424ead60d4cedd734a5dc3e3d82aedf2b3f1bbbab17df14ad5c079d46ce103debc5848762f9771fe1ecee75e97ecfccde0be1f8714beadc84276a56c49aadfbe0e995fe2e7b732aecc7aec8e971b512743b5420493f9d5a35c69f2b526777bfdcb404aa1471ac04c4bea7fb21cfe4ba58951160ca5caef0ba2b74773862652bdd6cb5229104dd2b200fde9cb9dce5c85bbf8d7f1d67f6ac4b1158769caf2dadad6494abfb149af77a5b94d932a7f66cc42f6307088168fcfc003ca3b575e66ce1e37b3661efda2538b66d35399224459c268ec84927c43a2f73e4ced7e2f0449be4b3f1ba70499268f04688d90be76fc6e1d0996b387e2e58dc4f56819fa752e9a2a852ba204ae5219137c5c323c9d192b77f5e1d8777483f2ce87ac5379ed897d8bf74785bf9ba2dd063d40c6d8b8db8e848fc3a61a82876713318f1dceb1fa25eab2eda5e1b97b94dfff0cee91674f116e8c75ecc39f191af4445f4f8e07b78fb3b26567f19d30b574fecd3c2033fb7e333b8e2f0e548ccda90ba422077c4b471e701f807a4bf9f869c888aab5da3045d4305907442fef1dcd30df0d71ac7e146b932d46bcd4aa2743ec7e14585ef71a448e771e4c8fec95eea4b56145bc467f76ea48d49c2ffd3123f7bf85c3df26731e02c5bce7e6fda75105af418a41d6523f4f239cc18d43ac55a6677f93ef295288f16bd86a34ccd27e48176cc7def459c3fbc535cab6ced26e2a6ce1dde8584d818717f52ac65b9b71471699dc9493e836ead096821ce1080ebf1ee387d2d1a074e5d42f03539346946c3d9e87ebedef0f3a189e6bedede88898b43740c4db1721e11e5d88c2aa32856282faa972b86b205fc51c0cb02ef24b2e2b57d3ae4a576e18126ce7ed796d9bf19dddfecc5b4ff84df50bc92ebbedfaf9c3d26867fe5160eae58f9e324ecf86b76fa059daeeb2ce601058aa3d7d81f5d8e1b7f7cfb6afc3569884c348af0402ecbffdb7236240633d79d17cddbec79bc52496cde73049edea9fbd757d85071766a94a0132a60a49f179f6b899fff5aa5ad497c3d0d18d8ac148ae6761c4e92d123472e37e548dbc32f086d067e4242293b2239ba6519964e1d9622b8ba10a615e1f21bd1130532cb56eb68c4dd84b7be5d89c0bc05e58176706df81d4b7f106e7265abe62f0c41cda73bd1f51ccb5a7556ccf90cdbc5f1f2fe193d24e8e2cdfba43068ebbc8fd7692ecea3799c211017a2817d67ae63efd1b348624fb8074a152b8472258ba02c4de54a164585d2c5902bd03f45b4f5b99767fabad165e213121d449ee76111513871f6124e9d0fc6e9f39771f25c30ce07bbee0af74e18c854ad55b9346a95298812fec9f0b546a57826ffeaef9045535f967bc9ff68d2c781d7adf88002c5f0da944562f8d5bb81bb88fd7a487bad931a59a74158d1da3b7386ef81ef87ef4b17731ee3fdc54f7e4e73b8d6738776e0d70ffb88e678dc6490c3485aee3b73253c1ed3d79c454c82e338074d6a55c0eaed076132a5ff9de64454dced88127442058af431a07b7bccfaddb117351e4672508b52c8e79fbaf7373d72646fe4883909eee8f5c93c142eebd8bbd7da5fa69005358b225a19d9b240dececa61f7c85519d9d3243a9b212ba75af38e787ec878ed281b3111619834a025ea3cd309cdbabe062fdfb4078339b57713164f1e86c438695d6ab7219e45ae6b224e6b621ffd7004cef07aa2d10fa7230dd874e8028e9f4ddddf785af8787ba161edc78460b378b368972e5e1815cb38f6a2f628387ee622ce5ebc22e6a72f5c21a1bf846d7b8f22362efde5fb95cb1443e36a2551dacf02cf24393a9cfe7dc9f729e7f6f07e9168e3774ceff7b1266dd1e9cdcfd24c8839c31523e78ee98b4b4776caa6742cb8f4b2d2125c790f7609099a8cdefe7869fcafc85bb4b43cc8058736fd87255386c144d7b85382c11521510924eae7101eebd8b3e29375aa60f5b67d94485015e5d242c5dd8ee478415701227dbcf152674cff71a1b626f1f7326268cb32c8ebe7da8ad023488a174599770112f217c7ff2677dac111ef6fe35ec5c5835b4484685f16a9fd3b20de0cfdb0c5cf11af1074b27e39eb7de094c5285ca6b238ce1e2e6ff5f64b5bc8d9925b3bef4b1cd9f48f88f475eb9bef410f22fa7d88c85abb2f9e9b0dbe381b6dc296a39770e8a4eb8e525cd1b86e35b468580b4f3d510b4f3ceeba0bd3cccce65d87b066eb5eacdeb2472ca797aae54ba0d163c550d2cf0c4f4b2cbd4ad7df1a7f8242d0e9fd8a9c185a2e59b5019e7be363e42a50543b2a6dfe9af101f6ae9c2f2c67bdd5819e25aebf531dbe030eab7c4d91f8e4406bf2c20b1fce41d1f2b7ef7b7ddbdf3f61f59c4fefaad29d3361318998b4e20ca2e21dfb3f68ddb836feddb85b5b53b842c5e13694a0abc0704786bdfa22267dfbb3b626e15eb0de24312f1494768d5cf641f647ce69e60892ad9dfe931623c045b6784cf84d7c3fa20b626e5d4b695a44ff69468cce6e0b41272bae58e53ae8ffa9e3bdde8ed8a8706c5a34075b97cc811b252c52227eba2e1701b8ba3e0719be7eb85b6eec381f8e353b8fc06271cc3275458dca65d182c49b45bc69bdeac22acf2ec4c4c661e3ce8342e0d76cd98bfd474f6b7bd2c66834a045dd2aa85f2a0041c9e18efe4c1e2c7361f4ac6fabac3341cb6e06136ab7ec8aaa8d9e4189cab55259ece137ae88bedc0fac5d2cdea7c86abf8d75ae8725fee47531e731debbbdf76d4aa734b763e54f93b17df12c518fe45e059db91a1e8f292bcfa42a53efdeb6197efd3b756b12850d57f1784e8cc373b4a0bb0a044c0ef692547cf8f6abf868f2b7da9a8da14f974629a70a70cee811a5bde85668f02c3a0d9b240f70826b23ff30aa070cc924ac2caa22624c1d0133e20dd18fcd4a9765e96ca5bff0de4c54aa27bb074d8b24732276adfc136bc82a8f8f0ea36ba5b6e26826af6f77758bc10ba7a23db162d7499cbf7c43db9a36f56a5442b776cdd1e37f2d903fafeb71c7b32357ae87e28f7fd7e37712a21dfb8f695bd3a66491fc78a64e7994f74f803129dee1fdca77cc6188445d135c21bab4dfcde8813c854a8aba1306a311b7ae5e4068f019f11ec5240436eda21cbe0e8751fa97eed202271cb8691af74d9f1efefcf25d1c59ffd77d0b3a73f6460cbe5c7d565bb3d1bf4b6bcc9affafb6a67046c5e51225e84ee460ef48c5afb3a6e1c557878888ce1e574dd35cc167b17ff2e9b6ce6092f14cfff7d0a06d4f799013fb56ff897fbf190313458a1c31da8bba03e4265b70c26d8e8869d2453d77913218cc9dcda451f6786ce73a2c9bf3396e5d3e9b720dfb491773fbc8ff4a0c703024196b779f4042a26359a7336c897725abaa6787a751b450eadec1721a1782afe137127616f703c75c77f5aae3e96142f3da1550bd803b0afab0d04ab195c26e7bd76cb5d3ab4ed92fd0de15bf37c7772ab789d7c97379b48398b3fb7af67e9b81e352f54e773b7e78bf1f2e1dda2a045dcf5dba5741670e054762f6c6d44537533e1a81a1ef7fa6ad299c51a24e7e400f9b739ed60ef5f26fcfa655ffa0659b0e6220097b7ad42f8a7aa5d367690a9fd4224b8e84f5ca4d9c6ddaefd35f4576a92bf42150758b392532d6a24816727e4de48c785f6c55e9113d4f2cea6d068c46c376b62e3f191e9af4bf399fe1ccbecd32a22747c540263c17d7a1885f9bf3b562e9d94fde4ac6d633613879e1f6b5bdb9f21a5be23d3b3c8532255cd786560027cf5e4a1177ae64773b2a942888066572a1426e3751c4c3e189859cdfbb6eb97338e0ede247bc37166f29e0f64d0a45f8e1391f4be8e7e8ee700e0067eb37e93e148d9e1f208e492f5fbede1a9157cfa594d50b41d76fe636dc6ef7b633b7f0fb0ec74167d8fa9fffc34c3cd7f3eeee2fa7a0e274f2839c28e8eac5df9e9347f6a37ebdfa088b71ec63bb75b50278a68aebb6be69c15eca022cb34c752bda0a0fbfdc787dea2297cdccb813971fc7f4c6f5330753ba6de588995f9b74cf3162e7a1549b767b03d72f9cc4af9f0c445c4c140c1edee8f3f15c14af58435478dbb07016762dff5d2b1bd7b3d66564af5b54f6d7381f63c2d23dc1387f2544de940bb839195be2ddda3543f54a65b5ad8af472e0d86912f67598ffcf3a9cbb94ba3b549d5245f2a35dad2228e19b288492df3b7fab1c0e8428cb2581adf581fccef99d32698bb90c972ce675dbf5c153bdd31ed274c18c8fd1f9f531da9a8dd1ffab20121ca2d99a0857f21eece130c5d86f177724ff5db2f2f00dfc7bd071d0193f2f23d62cff17759bca6e6f158ee4f4b85d09ba1d4ad081d0eb5750ab6a255c0a89d4b648ea9255fe0259e7770bfba8887c6941469c325b93b3df0b96a98a973ffd1926cfd495c32243af61d6b04e488cbe45e24bd134477ce2bdd922e1647723dabc3616d59e6c2f4f222e1cd985b9eff512d7e3a978a55ab874629f58e1f359c4a5352e235e8ef0ed7300ae25f961f1ae0b3871ee8ae6626ab85c7cd4eb2fe07f4fa5ee9446716ffcb3661b3e99f10bb6ef3baa6d494d859285d1a14e091434468977cbf04c5f66e47bb45f961bf46de250fad113997a85bb2acd3aa22d85255743e3722b8c795f8c4478e80dbcf1f98fda56493c251ec7757f1c9e14b0b896bb1ea6ecafe87c7fbc8f8f91cb3c970bfa19f62cdc75199b4eddd2d62405827cb063e72e942897ba3587c2e69ff6e494b83d75e8cde628314f9b44b28c5b34aa9b4acc793ce77b1173867d9bfd9cbd5d3647931397915f3d7d107f7dfda13cd009ae09dff1ed4914d9caac5016709120a0c4003761f20ac88b5e1ffde820e64c89c7eaa054d5faf21a06bac6a97d629e32ac2747bc5c01ceee5ef8dee28dfe987f340113fed896a698376f5813ab7f9984ed8bbf56629ec1b46dd100db16cdc09a7993843fbb82fbb6ff6cc1362c3c6e4182314024c4e8f5d13bb44dbc2eb7f37ba515f9ef008b39270ad942e70466f9facfa0ddebe3d21ce77ed18cb1d8b57211f217493d0c705458a8106c2ecf4f09a362e20a7cb24e875c4f46feb2d550e4b1fa29c7718282cfbd5ddcd3a94e11d428eed80decf5f0583cfd646344dc4a3bf748e1485af17e7623c759e83939f57627bab46e8a05cb366a6b92b2f97df1c653a93bd5b8938fa58a4439e2a23fdd2ae27ed8754bfdd9feefe189ffbda81de9c8d62573b1fa87cf456280dde4eb162855199d477ce9b2e7aeb8e808cc1ede0591372e89c85d4f4cf0d95afc9ee216af27bb7b60cf2d0fcc5fb52bcda667ed5a34c4fb835fc4e3d52a685b140f9add074fe0a3af7e1496bb2bb8c95bb7a7eba0661e330cd6446dab0d7ebfaee07028cbcce554b44a7d747df76b3124ab2b96ce9a80f50b678b70d7a6cf303cd5ed55b943e3dc913df86e44779150e4f2730e53854a57469e22a590af7879e42b5606f9699eaba06d18e1e8f0502c9bf93eceefdd4861d19600e19b4eebbeb937b953d71dbbec7da6fe6358beedb0b6a6b027a7c6f3394ad0d34aa5294107267d381cc33efa425b931409f2121dc7b075ab93e253b4c0029d1642329dbd9b0e97e59652d8f55ae96cbdf4fde42794ae5a573bd0913fbe780b4736fd2722cbca0d5ba1c3e0f162b42b6738fb73fe843770e9e84e6981d309229ed4e7da71bccc6b57acb9f0e3ea83b871335c6c7786cbc759c82b972ba96d513c6c0e1d3f2bb2e2b99cdd1505f2e6c28b2dc8f235dc4a15dc9ce1d0cadf3a7fee1ce68c5e7ee83f65a9cbfed999e53f4fc59a5f67087729b8a2dbdb9fe3f1a79e13fb742e1cdf8fe33bd7a350a9f22858a21c8977396dcfede17a22dc5d2cd713e15ca294c4a7b6df196e9bfed5aa33080e73ec9deffdc17df0d19773b435854e4e8deb73bca02b3107d6aff81bcfb4ed203a66d1c9e3e781b79f29035f4f5bd32fe153f4c342cedec6ebd2fbc48f864d3cedbd3b45e0e9507b0b896bbc73cd774fffdc1838e54f04e52b244fb0830745f9faad4ea8d6a4359a751b48564deaae3fb942dcfc09431071edbc5ddb63c7b24a86ef23dadd1bcb8e4662cbbee3da5647b88cfc872f46668a6e57159283c7cee0b53153b1758f6b8bb44e95b278be667e31204c5ac8f02a13931cf60a94ab8ede9fcc933b9d58fbc7b76250170e3f0c1d2eea7b94a9564f6ec8008e6d5d8ea553de16e1d5564ca0ed74414c820593579c4168b42d47c283cefdf3a7efd0b6477f6d8b422727c6f73946d0736a8aed4e845c0d46a50ae57133ca36f4254712235a9773e89f5df812fdb09873e4c6d9e6ec75852ad480975f10ae9c3c80d848b292c89bf59ac60cfbbb1e29dabf02dd0d51bea865bd17285d050326fce2b2925c7c6c749a03731cdcb40c8bbf7c17c99604515e6edfdc4dcfca64ac6e46ecbd69c2efabf7b8cc5e2f5c202f26befb0a7ab47f4adba2c86cfcba6435868d9f89ab376e6a5b6c70367c8f96755133573cdc931dbb5065380ceb822ecab829dcbd3aed3fe429ec9803b365e94ff8f7bb71a26846841f3a919b460e9db91cf98a94d28eba7ff6aefc032b677d94529fe34e82ce844426e0f365a7e87be1a791e4f6f5c081830751b4b42a12b22727c6f9395ad073ba985b2952abf75869ec3eeed88945cf064551a794635b73f629f62ff632b66e4cbe01e832ea5b14d2065a49b626e1ea99a362d8d1b3fb36e2d2b1dd4247d9db9d9b12f176dd3d692dd9cad32b376a83ee2326b39377c49a64c1aa5fbec2a685df4a11e7724c8a1885988b08d276bd50b75c98bbe6689ac3960e1fd00d1f0c7911be3ea9478c53642e7874b8b15ffd8489dffdae6d71a468c1bce8d3a232f22687695b248e614e36a1cc5bbc02ba8e9a81a07c85c531fbd6fe858553de494910729815619e7edef9692b7c03738be3ee9790e0b3f8f1bd9e30c7448844687a059dd979360cf3b63b0efc53b36c216c3f780a1edeb7efbd31a791d3e2fd1c21e83931a5961e5c8d9e56b378205e6a943aab99bd4a2fff66abfaa9bea351bb55776d6f6ab872dab983db718104fed4ae3588090bb1092ccfb5e3c8a91437f51aeccfbe9c7625391dee83fdb7cfdfc2b903724017dd2a771073fa4ba6f9d19800ccf9679bcbe14bb94ff5599f0e43b952f7568b5ff1e83871f622068e998ab55bf7695b6cf0f0adfddb354465df080abc721bcff89be7b0cce18d459dc39cd1db0f359a774462421c76af989fd2d19008a7347118e5e3fa8cff05252ad7968edd07dcd7fc9cf77a2126f48a68eac6615617f4940f83b05b4cc58f5b2e62ef057a363b7ab5698c9ffe71acd49ad3c969717f8e15f49c2ee6ff2d9c87b65d7a8ac84d27afbf07de79b69c432538860fd1234269d950e4f6f902142c9dbe76b07c6ec8c55364bdef20eb7d33ce1fdc4a1bad22c2926e4b511711ac16d1befcd96f2899464f72dce3db2fe30622f246b010728e0c53224511317264e8068bc1134b8fc560e39ed47d899728520053c6bc8ee79e69ac6d51645516afd884373f9e810b971d3b61619ad5ad823615bc61ba4dfff0149c29388abd22aee0e8421774860e1159f4c52ad7c54be31cdba17327465c31eed2c9c3080fb986d0ab9750a3696b3468d5493bc29198885b983daa376e0593356d27e63c31fa3539fc8a0f442ca786ebbb70d67b4894ad3c9dcf9d37e333747f6d84b645c1e4a4f83fdb0b7a4e4ba1a5876bc11750edb14a0889b4959b73b6dfb0566551303075f935fb14fb17c7797a9fec1d877f894af59f9607dc2509b1d1b878743759d7db707aef06845dbb9822eac25aa76b7805e4c6a0a98b525592dbbffe1f2c9e361ac9967869916b82ee6055d15f84311033fe3b9caa06bb97a70746bcd20def0e7c412c2b52a37f1b697d3b4c7a8e7998c4272462fc8c79f878da4fda161b5c137e60ab2a08b286a74a9cf29cc39c7c1a0e3bf299347d1588704f82ce09d90265aa886226a387174eeddb8a4ba78f08f7f818ae13d2aad7103cfbe260ed4c4738dccf1edd17d74e1f4869e6a6875de98df2a2bc2cef83e77285d79de1d1d926ad382dee4b27afbf270e1c3888c2a5ca6b5b1439490372a4a067f347be234d6b57c4c6bd27b43549d7ba45d0b0acebf241f62d3dd262b1e5b2c7b2759e42d7915fc903ee93d0cbe770fed00e9cdebf99047e1312131244e4c839003c142a57864b329bb1e2c749a25d3a4782328b5def6a53b6e5d523c0e3f18198b5746baa2c76ce565ff2dd38542a9bba83909c0abf57fb6f84fd2c262e812cc024dc8a8cc58db01824982dc26fb9ce05b71ef0f1f240e13cfe08f0f316cb5c7721b308fbb1d317d07ec0689c3ae758c6cc59f0afb47f02e5bd288147e198259ca3010ed3cef181bda0f31e0e8b29e30550f8e75c2499f894fb78cefb9a74ea8ffff57f479ee884393101733f1c808b87b60bff1262ae5f8726dfa0bca8debc93a8587a70ed4270cff5f6613a2defdd7afa16e6ef74ecf3bd69f5d258b7ef149de3baa39c9c484ed1816c2de8694532d9f891efc894b123f1d6078e2336552d1a80fe4dd21639f62df63311715184c6163a67f90d9eb912790a656cd32e6e9f7b74db4a2c9a329222490b8a56a8810a8f3f89c35b9621e4c2494dc06d13477a1c29f2ab4e367860e5b9242cdf724073cd468ff62df0ddf8b755a5370d7e9ffaf7c1421d12168d9d472fe0e8f9abd87ff23262e3e311111d8744ab1bbd733a8642811b4d42bce91c5f2f03027c7d50be4441542a9e1f250ae546cdf245e1ef63cbe1b1bfc6c384c7667f69f86758f8df066d8b8dd68d6ba04509030c4989f434321e708e0e9c6f59176e167539d7d6b56dfc4d3cdeaa2b3a0e1aebf279b9ebd89f3f1984933bd7a6e42ae9e196c953ac2cba8cfc1a4105643d8eddffcdc3da1f3e15fb398cb393ec6e6a9725df6fbc8083c18ebd3b7e3cb40f464f99a3ad29728a16e43841cfc68f7b470eeeda8a068d9b2236c1d6a427b7af09efb42e072f53eab6dd3ac2c7381223bf63eb842d7416f4faedfba1559fd48359981364e717ae9a9fa5171e716ddf9a3f53ac139ef44890cbc9857543735ee763e24dbe98b5fe02ce5c741ce483b3d5bffc601006746fa76d51305131f1b81c1a8e1d872f60ebe1b3387b3994443d0a4916b3f06c035977ee6ce191ff1a8c248006032d4a41734b4ea2398501527a6b32093c59edc9c95614cd9f1b8d6a94438db28551bd6c11e40df28587c9986664faa0f9eeb7bf31e4a3e9223bde9e72250a5302b6383c9362b4c0ed84fdedd27edd9ad7b3e853ac759af87ba8f66407741cf20985c7d4df5032f9d3c2af468b6181f5daec3ce994aff714fe37683c3c9d9a64ceffe4155c3cb0458675f11ae477e00a1e11f1b3ff4ee1568c6d585f1f0f03b6ae5b81ea0d5b685b1439410fb2ada0e79414597a494ab2a0225953a72f3bb6df1df16c5914c97567ab95bd8d055d8fc458d0dd3cfd3062ee7a783a3595b19813f1e18bcd51a86479547abc11ca55ab8722652aba8cf0d262f9f7e3b1eb9f9f4584c6af52cf7e4c11756d9d23ba30f75c98ba740f22a21cbbc6e4d1d0388bbd6ac5d45dd7e6546e45c660db91f358bdfb388e9dbd811b37a3e8e59a91683653646085b78f0f0ae4cf8fc2850aa170c1022854a820720505212820009e9e9ef0210b9c2b7325992db81a7203474f9cc6997317107cf51aae5ebf8168b28e7dbd3c51ba705e542f53106d9ad442d5d2858455fa28e09ee6380bde7934b7407f5f0cf95f6de4716adae68c882de887459d82bd66a16b824e9679b9fa2dd165f8e434c3f6d26f3fc1f6bf7f12cfcfd639875b4e30703cd4e2c5e168d0be8fcbb8eadcc16d98ff71ff944a737a784f8be0b0384c5c765a5b93542f950fbb8e5da084b5ca9562728226e42841cfa962ce0c7fa527bef8ceb157ac8eb50ba16985bcdadaed619f63ffe3626931e884458a7adb811fa16eabaef2203bd62c988d4533c78be532551ec7eb13e6a612fedbf1f3077d71f1f07669d1689119bf531673465fbf9c940b93176ea6c8d5b1bcbc53eba698f3d908f8fba5ee2236a7c1ef8dfd6ae381d398bd740b0e9c0e16d9e8eec96473279961325a51b26469d4ad5317f5ebd446d9d2a550204f20f93d0f5dcbe6214d29df0ecdd9f369964c96ba9bbb11164b226e4544e1d8a9f3d8be671fb6edde8bb367ce20213616f9f2e7459b4635d1ebe96a289a2f4858f6c2cd874854742c7abef90996aedeaa6d9170b9fad04e8d50dc706751673f642fd073a9b82e49995a4fa2d388a9309a5c57ae5cf7c7b758f5d3a494ba1ee2b1c90def805ce8f8d62494aede401ee882e3db5761f117431ddaa87342400bfe2ed9703c148bf63a265cdeeadd01937e58acad29b2bb2e644b4177f5d2986cf8a8e982c737af56a3b6e80f5aa752213fbcda2cfdbd5eb1cfe9919a2ddb3d19b98b96c5e0e97fcb83ec888eb885d15d1b227fb1d218fcc52ff073ea9083fb5dff67ce172851a9062ad47a020176fd69875dbb84e9035b8a885058352242e3f72af7b355ceff07a3fc31e76fc7489a99f6e1600ceaedd8e7764e8505e8c8992bf861f90eacda7d0c49dc431ebd3f5f0f0fe4cf971fb56a5543f3467551b3ca6308f0f7977eecae0954aaef885e3e0700b2e4c53e7dd9dda4add37252222263e371f0e8716cdcba9d04fe00ae5c0f85b7c91d2fb56e88d60d2ba3501e7fcdbd87cbb41f1761f087d3b4351bfdda354435ffdb7719cb3ff63954452ad541f7d1dfa639a8cbce150bb0f8abf764e5375d908942a51f43e7775c0f2ca473f1d81e2c9a3c1c7161d7ef4ad0996fd79fc7d12bb667e171da776c5c8dea0d9a695b7236d95d1b94a0e700ea572d831d87cf6a6b642150e43aba5d05f879d9fa694f0f22fea63fb64e84a85302218184fda5b17351b64643ed281bcb7e9e867a2d3b227701c7c88bb3e4bffff0559cdcb35144543c15ab500d8fd56f010f129abd2b1720f2c645876c4a7ea529ef952cc675970d58b27eaf5cd7606b7cc58f9fa341adc7b42d39170eebec5f8bd71fc0b77f6f42700859a1f4fedcc8422e55ac38dab56a8546f56ba06cb18230f906d11977928abbc71a1f816bd7ae63fb91b358b96e230e1d3a828279fcd0e399066855b7027cbd4d29f7f9b0d8b2fb109eed335258edf6746c5e1b8d0b59e0ce89122738d6e0fba4202f059dc2fca0efd6a739a8cb814dcbf0db84210ed9e54c95266df1bf411fc3e4e1ba6e095f63ebd29fb072ee0431bcb01efe7537d2e34dd1f1168cfbfb04e2ccb6e7e05ee4f69cb8449f4dfa8bbcb233aec25b76d1861c23e8d9f031d3c5cc2fc6e2b5e11f686b922e750ae3897279b4b5f4e310b191a8ebb5dd2bd46f891eefa6af091b9fffc79477b177f5228aa4c8b8a31f7e5d7af7b01c79394ff42fde29bf55ee8f7dc9c944acdf75443aa891377720d6fc3209d52a95d1b6e45cd88fb9affa1f97edc4d43fd60bad4e764b82b7d188b64f3f8dbebdbaa3785e5fc0e44d1eeedac2cc3838d4b82129211a1b77eec70fbf2fc2defdfbd1ea891a18d9f369e4f2f37ce859f0fb8e9cc233bd4720c4a98f82e6f5aaa05d592389ba633fff7ab8e7521d16f462551ba0fb985972a71327f76dc19cd17d1cc22e97afb7eaff1eeab6eea11d959a84b8182cfcf23d1cdbb24cb451371959cc6dcd3239f4bb88d65cb2f9d44d2cd8e538a6ff476fbe8cf7277fa7ade56cd24a4066078dc876829e9d5fd6dd72edf22554ae500e613109da16a0709097a8d57eafb0373a578ee369d89cf52e474a7366d5bc6958fbeb3418382b91de154758fcc6846073e447132fcbc8508b107907fd27bb1bf1e3ee70ec3f7e4e3aa6c1bdbe6df87d2a4a142da86dc9d9b0987fbd7813e6adda83b8d87891687aac52798c7e7b08ca972a0683e9de5b1fdc13495cfb9a028e9b3b622242b176eb6efcbd6a23e2a2c2d0b9794d34af595658eb697dbb0f82f3c1d7d0acfb9b626e4fcd4aa5d1ab56100cc9b61ae31c73e882cef5473c7c83d077e24204e6750cefe78feec537c3bac96c767a167e1cbf5cf9d06de4572856b1a676546a422f9fc72f9f0e41c885e352cced2c73f17d70f067f7b4e3d303d77abf126e1b6a95474ddcb175131eab555fdb92b37115d694a06742b2eb8bba179e6dfc38966fdea3ad497848d4e279525714bb9d0fd9fba87de4c6a2ce65e95c36ff44c70164f5bc250f4a837debffc6822f78b848c7feab6584c5d7d196e9587e8fb45bdbe706b3c11bdf6fb982e3671d3b0c79ac7c49acfb750af2e5e16c630533ebafad98f5cf1624989310171383a1afbe8cbe3dbbc0cbeb210bb98ef8fe28c098e30013578c4c465ce44dacdab21bffae5a87bc9e56f4ffdf1328553897085baebee107c18dd0303cd56b98a8096f4fe532c5d0b761219892644f8aaec27c50e132e8f6eeb49491daae9c398659a37a2131364a865bfa2bf1586d741e3ec5a17e883327f66c12d9f39684186191eb65e6a2a8497c1fd2adbbf5920b3763c550abf6d4a9581c3b8f390ec4945351829e05482b22c8468f986e962ffe1dcf76741c3ca551b9dce85cc7b13c3bc5676881cbc79de1c844fb178823e847b7d2f5ca71ee5eae9bb0e99c3eb81d73deeb431155724af9a0a845cd6e0bc7e515789997f457c9efd4e2ee81e9eb83c99a72ecab9bcbcab9cc5cd56497700df25d472fa0ffa7bf898e605814de1af42a7a77eda81d7167d80d1dfd7bca7081b55a007723cd9310197603bfffbb16ff2d5f83016d1f478bc7cbc3c364c8f86ba60197a53fdbe71d6cd9ed38ce7aa9a20530e8c9a2305ab9031a0af214de29b88b302feb905861f20944a3e75fa67dc08605df223e3a2225dc7247336d078c81c168921b9ce0ce66d6cd9f89d5bf7c29be05b6cc752117095d9ec82de7efef6e58b0eb32369fbaa5ad49664d1e8bfe6f8ed1d6722ed9552bb2bda0e744314f4a4a4285e2f971e68aed63f6f534888a70dce1848ef019fa6121676fe275ddbbd82bd937752fb58f58e4715ab63b456edc973467bbb77bfd63d479a6b338de9ed0cb17f0d5e00eb026c68a36cc324b919b44c9ec74e7d726ae251790ec6ec28f7bc2b0ff986336fbb34fd6c37f7327686b0ae656440c3efcfe3facdb7f9afcd880ee9d9fc7b0d7b89df39dcaa8e5fbe7ef87dfabf377a47f43192fb2ec2e5d33311ae7ce9dc596bd47604a0cc313150aa25841d92a22adef37a3efa55dff51f867cd366d4dc2d9ef2fd60e829bd52c6e9513b13221cbc26e15733d61abdf250bf8ffe83ba8d9bc83b6253571d191f8fd8b6138b57b8314731671fd9ba0c74ac966e70f40fedf13b18949a2825c4c82ad4e40e1dcbe387de10abcfd02b42d3997eca8170fb736ca0324e3239baccbd811831cc49ce950b3502a31e7c06b8ba0684ec2ec1d940f75daf747f1aa0de536dac7615c4459fc6f17de4519375b122ccc34dff6cfcf2e3f086f3f7fd17b9c3c962d0fed1c9ed3c4efce7e1291184dc96e06fc7138269598f7e9fcac127317fcb97e3f361d3ccbe61f2a572c8fd77a77257fbcdd27ce424eb6bcd32be377683fa5bc978c86cbd6e95edd4cbe285da11a7a3eff3f346ad11687227d71f8e22d519bdc3e5cd84fcee8f77aaffc3d7b3cfa7679565b93ec3b76160b8fc48ab6f67a9894619645d736b88a186885e6790b97c02b9316de56ccaf5d3885196f75c1e93d1b44c2d683cfe57273728f663631173fe2ff9ee1efbd3d7df7f65cb9158391037b6b6b0a675c85adac44b6b1d0d3fac8731a376f5c43c912c5111d6fabd4532aaf0f86b694b5bf751f9111a0147231a79f3a6d7be3894eafc2cb57a6de974c791b27b7ad9011194734b4cdde9bf5f3ed9bb0f5f9f84794a99ebae2cda2afdec3c1b5dcfda5bb88c4d822e1885144d0da31f6908c60e9192bd6ee70cc0a7dfed92658f8f547da9a4227f846385e1af733aedd8a84bfaf0f267e3c1a8dead6d1f6da23df370b39f9bed822de818befe781c359ef7c0f9cbaa379b22511c1e74f61c3de63f869fe22942a14887c013e42382d4949b098c9d214e1c50d41fe3e28962f08450b0421d0cf07f973f98b8162b82f790eabf74a97d73fc282ffd66b6b920ecdeba069c1048e2cd9f784ff5190a739257869e2dee3f81be8c423103668a99d959a839b9661c19491da48813241605f97846f9be7da6bb141d77289dd71b77be2a9abcee05c88ad999e97c91d07766f43f96a75b52d3997eca61b4ad0b3199d9f6984852bb7686b92516dcba340806c9ec43ec2fec25e232c705ac857aa325abdfc010a97ad228ed1b976f628e68ee8946255b30f4b6f66016077a45bec0667bd8b266c0d9e418f915ff2410e5c3a7900df8fe8064fa3adbc505ae7da014eecb8e58b5f976fd7d6244fd6af8175bf4dd1d6143a5cee3d6bc916cc58b85194cdf6ecd10dc306bc00a3a7537d0621e2b22c58be433931fc1e1f94a8dfc96deeadeecce19dd8bc732f6e4426c00c2372e52b20ba9cf5f53291f019f8ae1117978098d858d1dceccad5ebb87eed1ac2426f203131160549d00be6cf8bba8f95448db24551ae58fe34c3d69d68f1c25b58bb759fb626e9d1aa3eeae6967dbfb3a8331cfe652e9614f4015397225fb1b2629f3dd6240b56fc34151b177ea789b8cdba97df81147257f7cbd7480bfd783a937ff8df25d723e231fedf53da9aa459edf258bbdb71c4c59c4876d38d6c21e8694516d9e0d1ee0aee11aae1934f6b11b6a459c5bce8504b66bbf166f613f6163d222a50b61a7a7ef4439a3d5e4de9d704b1e1a122e229fe581d9478ac2e0e6ffa1b11d72e890884ddd4cb1185a89395fef6acd5c85db09838df9ed9ef7447c8193916f4ed04fd744210a6fdb9495b9354af54065bff9c0e1fef4754533b93c2627e2d3492acf39f70ed66248a14288099d327a344a17cf461b8c86e27abd8ca1240fbecc59cc9684177fefe78ddcd8d275bd14fe8c5e3f86bd54694285d16f5ebd48597a7a7b83736da79d01711c6c4793c348c5811e19bcbb0b979de951b21d8b5670f96af5885ddfb0fc360f240808f371a5429897eed9f40d9c2796034de5d872a3c5a5be32e43447b757bde78be31ca786a6dd7e91e58d8c5bd703d12ba9f273abf8ea65d5f97fb35b8c7c45f270cc5f9435a37c6ba98d3f3f033eacddbe869c5f112e96f4ede970af6134e0cc8f3e5babd2bf6fcb5f72ad61d0fd5d6248b7ffe161d7a0ed0d67226698579e7b09b5570f1c5670fb2ea0bb91f06f4eded20e6015e46b4aa2a9bcc88cdf4c3de22049826934f003abcf9459a62ce98bc7c8558fbe72f864eefcca0486b20fa4f5e8a167ddf83875fa0885875bfe66f8323985d2b178a75672a3cde545b4a9b1bc885198b1d73184a172f8c35f32629314f83f3576f223c328652e756d4a95d1dc573933fa55576eecea39f4931e7f7a68b655a11dbdd62ffdde96ecaec7d7d7c6fba2fabaca475ead8419cbe128eeeddbae3e966cde0efe90e939b9522255976ce2395e9ee897bd5d769622bd7dbd303658a1541d70effc394099f60c2d80f50af764d0a93c0f2ed47f1d2c73f61c48c2558b7f73442c3a34565d1f4c043ecaefa7922ca9428ac6d9170b8e4f0299e4af32e9ef163b2946e5bfc3d2e1eb3f55e78edfc094c1ff2bc10733d978bb45c3f95bd44cbb297096cae39cff506f48aa6a2284b2c5b656b126dcedb020b9716c7cb84b9f012e9479ad3ce703c10e0edd833e4fb63c620c9ec38125d4ec33ebc6607b2bc859e564494dd5ed49d58fbef223cd5f679870ffaa5278aa16609d93e9bb7b39fb0e0eb1645c7615fa262fda7c57e67389b70fd1f33b1feb7e922c2ea31fa1b54a8e3d81f74424c1476fcf313b62c9e25c631672fe748c8e4ed8f1173d6c3d3c731cb77c9b45138ba6189a80ce4ca428f720fc4270b77212eded6114efebcb9b07bc94c142b9c765bde9c0c5ba9df2cdc801ffedd26723ede1bfe06da3ddb4a08f7ede0b0204453fb4eee57d075f774785d6ea3650a6bbcc7cd40f79464a6f06741544c1ca539dc10e8cff5354891b83ff8bb40bf6f1dbe0e2754436f4590a57e100b97fc83ddfbf651b877833f0974a562f9d0a16975b46b5c9d8e763c372d2e5ebe8eba1d5ec3f550dbe02dde5e9e18dba33e3ce26fd13d68896321aa528061f040ad67ba52d8f7c7e6c573440f707c6f1cd6e544f74aeef0b3f39ce15c05ff3c05e11b98073e01b968ca2de6defe41f0f60b82975f002d07c2db97e7bc2d8012e15e08b9780a7f7ff50e6e5e3a29130b9cabc17fbac34eecbd108e1fb75cd2d62473a78ec34b43ded3d67226d94943b2a5a067f147ba279cfb6b2f9acb0bc39fb5f508671ff970c493a74445f49fb8d0a5ff45845ec3bc0943117c7cafd85fbe7613f4fed07557974c44e8556c5af81d762fff5df83d5b1dadfa8e44a30e7d44647ee5ec511cdfb106dbfefc36554520fdf2dc71cc67ff9e400845c83a41017ed8bce02b3c563efd83c8e43458d0a7fcba1abfaed885007f5f7c3f652cca5662d1ba33fa77925684965ed81d76c3febb63ab5cb84bdbf4fd9c6bc0f3444afc19dc9245f6b34878a4959b9006f6d7b1476cb79ae196948878ab11f316ff839fe6cdc7cdb030188cdca56b32da35a981373a3f897c817248d13b3dfbe113e7d0b0d32087bedff3e50ec43b6d2ac06889a3a481b490ed859d275ea799a05cadc62852ae2a82f215263126612671f622c167d166b1f6f0baf77e146e5e398f5943daa47c53c29be927ada79ab4fc342ede921de630158be6c691f3d7e1ce89ad1c8cab70905638cbcc2841cf06b8b2cefb352e8e6ac502c5326f673fe10846ef83fd7f433e47d5266dc57e7b6e5c3a8b6f867717dd7252fc40feeb8ea15fff8b7cc5eedc47faf5f327f1e7d411b87eeeb8b866198ac86e9c3b8198f01ba2ac506f6b2bcb12c96dcd9ab0ba19f0fdce301c3e65ebc5cacbd3031be77f893ad52b6a5b14ae309b2df8e29795f863f51e14295c10f3664e41609ef4e5667098b893a0dd2bc95c835daf49af09368709d96ffbfd5d5334b523f729f22237c92d31e88874d38db3eb49d0791bd73e3f75f62ca6cf9d8fad3b7723d19c48b76244cd0a25f052eb7aa85fb918bc3dee2c649b771dc2d3bd86213ec1963d5db57c09f4a9930b6e741f1cdde802ae8bb998d3e3576fde011d067faa9df560f87a604bc4845eb963cb11e6e0a5087cbfe9a2b626f9f6f3f7316078ce6e39925d74e4ee92c6998c0715196535468d1cee20e6f9fc3d52c4dc1e29ea722afff893da561b5c43fac771af2336528a39478f4fb47f295d62ce1428591e3d46cda048db20ce3fbf6f33e22242c82ab7d5eae5edd26d429baf3c9fec20e6cccf934729314f17e4a7a216386b9811a6dbd487700587097dca4844c5379a84e06ac8efd5b67eaf884a73061ee2955c678b5c3cbd2d1700462ff20c0f32fe3d50a15c794c1c3508aff7eb85dc818130279ab1e74430467eb314137f5d8f1be131c2cddb3d7fa33a55f1e3a491da9ae4d0c90b584dba28c4932e2bb3bc6db94f3ce7b44bc92a0fb669586c5438c26f5ea7640cbf436de36de078213fc50ff67c3271aa286253389215f5254b0bba2b323a62caecb075bed32eab9d79b66a016d4983bc84bd8527b622f21429034f1f3f6da78d53fbb622f4d2194d78dde013108466dd5ed3f6a60f1ee7b95283a76504c716b996c52e2c73da26dab373bcab45ecc7e202b16ccb01b1acf372b7b6e8d4face15e8144c322c16ab102f6121de45c42cc5c8366528fc8ee99db345fea0224637166e21ecf6b0bcf3c5c50d500c6784977f6ef4edda0193c60c41f54a65c99312111b9f80df56efc1dbd396e0caad5861cddf8e2e6d9aa15fd7d6da9ae4bf4dfb719cc22f3f1f87770ada227c8b70ae4d6975fd9a51ec59f107ac165b9f13e9a19553fc7031241293c73a2658721ad94537b29da0e7349cadf3dcbe26d42ee97aa0123e8e3329fdf3ba1e95ecc0867fb54889268a8c1ab5ef2dcafcec3977742fe68e1b8cb01b8ec333da93bf783921d7a4e576d68ab45cd87d21e6f41f6d08c4774bb6ca93342a952d81ef3e7d5b5b53dc090359e7f9827c4954ac888f8f42626266a9b5cc2180df3305260a4b3c16b7a8e19e16c95691432416ef22727533f098ea8e0992540908ce2d307ae2f17a8df0c3979fa2e773cfc0906c11e5f87b4f06a3d7473fe2f7b5fb101d67ab8ce98ae91f0d41e5727230161d0ebf61eef4bdf1a3d275397ceb35da79992baedd0bec07d1e1377123f89cb625355c8b7eedbc2f359f767ae6dbc0f103c713f67cfded6c51df4591b5c9b282fea052fd598975cb97a6b2ce5b56b94df929c5931c571ac89a7345f88dcb2991117baf7fae7cda1e09377159f8d5181cd8f81fc6f76b89b50bbfd7f63862f2e46653f21de956b92833e76d6207979b9b306bcd498ac46d9108374b5b3aeb136d4d911ed84feb5729010fb724c4c444e262b063f9e8c38685888599bbfa4d8c8b21eb9144d25ea05389352531e978de9a96e0b39bb71379ae317ee7440079141de3e5eb8f916fbd85a9e346939f716d360bae864660cafc8d98b1681b3ba61d9f1aaed7b1e4bb710ecd2739fcce5c7e1849eef29be2f04d5792619d7ef6ae988f4bc7652735496449dfba7609a70f6cc7ae558b104582edccda3fe760ec4b4f6148ab8a78e7f93a387fdc31f74a87bfc5c55fbd47cf649589649a789e5e9ce38973d723f0fd97eadb7326abe94c96157457dcf9a3ce5e7c34fa5d1111ea703bd30665e4a016ced81fc7e56eae08cc5728256260515ffdd31738ba75052c5a5bd54d7ffd88900b2785c59d94188f937b1c3b7fd189890c131f02bba5bb27d77941fc63f9590b2e5cb9214fd0f8f18b91285bd2713438c59d29923f0f0ae4cd2506e3d8bce7209010a9ed79307085344b6c24ae73b3a9bf16e2f5b786e37fdd5f42a3561d50b7456b9adaa151db6e68fabf9e68d7ed65da3f02b367cd4478c865f9f21973bc1023dec0d6bb9853f8e06fd83912755e77863f7b4e0ca484b1b460b1e65af5e43f4f3ed1000b7ef80685f305c2002b12cc16fcb26227be5cb80351f15cd1cd755cc2e173eec411da9a849bb5ada4f06c7f65be0dfe8612e32231f7dd1ef8a07d457cf05c154cecd702b3de7d11f327bd8355bfced08eb651fbc9b6e44fd74436fa63f59aa1ee53aefb85dfb0e87b5c3d7d38a5784cbf362fdddeb7241c4f043ab54b9f3ee31b6d2967921df423cbd67277f5e1668717925e4e1f3d880a55aa8b72539de76b1742930a79b5351bec2d5c114eefc9cdcde48d51bf3b8e93ce1cd8f00f164c7a5b66916bfecbeefb92a5ceddc21edbb1565824dc4c879ba6751a3c0ef59fed2a8eb3e7c7f7fbe2d291edb2cf76724b5ae7b688e69c3917a62ed8a8ad4906f66a8f1963876a6b8abb81c3fdbc157bf0f9cfcb50a64c71fcfedd0c783a37851281208954c65db386593c792e77e9a425885c69eaf2f99358b761030e9e3c8f0b976fe0e295eb302726223e2e4eb48bf6f4f182afb7073c3d3c613279525871437c7c3c62a2a310171b833cb9fd51a24841bc39f01554ab5e93f495ee850218cf197e8edb0a721ac8ef9ecfbd0bfbc4122f9ee9faad28fcaf6b4fc471a57c3713ddbb01ad1b56c15bdd9a23b77fda150c5f1b3d0533e72dd5d624433b3741495398b81fbea594666cb4c219512935e0c5ba6c3e3a74fa52142e5d497341b26ed15cfcfbc3548c99b31281799deac31057cf9dc0d7439f83d12d191e46aea7a2f7bce8f89ddd898d2742f1e79eabda1a9d4bd3c6154bd0a8e5ffe4861c88abf0979574254b0a7a5a1f7d167c947ba667fba7306fe91a6d4d0e8ffa2159013c3ca933ec2d42d02902e1fed6791a343d755334ce129cfadab388bc112c2208f665f6513e9fcbdee974e10e474685ca56c5c089bf894e31ec898f89c2849ef56172a7c886231aa7a63471463f8c5db017b176e595d52a96c18165b3b535c5bdc0bd87f57e7f2e8e5d08c6f0c103c5d0a9f6df097f1bbccebda55d0a0e16b5bd0b16cc0f7f7f7ffd009aac52701213101b13812b572ee3f0b193d8b6ef304e9f0fc6956bd71117cfb935328c799a8c2851ac28aa57ad8846f5eb2357be7cc8151480a2f9f3d3bbe6a66549309b937021f82a366cdf83fdfbf7e1e8e1c3b8793304eddbb5c5fba3de81b7b76c0fce214c4a4adae8cf909158cd7138177c0d9d7af643a29b07a5770cf0a0c7ebd7e9690c6c5b938ee0fb724d8dd6fd71e0d8196d8d7b98f3c27b9d6ac2c71c6dfb5ee887bd96459cef5fdfc6df224f25aad4c3804f7fd25c90708ed889bd5b8485ee0ceffbe6adce08bd78427cebf6fd3a88bfbbf01e8e073e5a721cd176c3abb678bc1256ef3aaaade53cb2bab6641b41cf828f71cf845cbf2a46548b4db0d568fe5f8d826851d9b1cc5b87bd4644d41481882e242dc968d6eb6d3479bebf76840dae68f3d387fd45ffed4288691bfbacee064f7e790ae2b52ffe40409ed4d6c39ed58bf0f7f451dab8e714e190237a993cf3dbe1046c3f7852ae107ebede38bc7c0e4a14755d514f917e761cb980d1d317c14cef6ae6575fa05c99d2a2d29cfe6dc877205fc4858b17b17ce52a5cbf719d5e2e5bd209888e8b47c8ad708447c5203e369ad6e3104302ceedbefd7c7cc4602941814122ec55aa500115cb9541853225c4c867eea28318725bbb168516b9cc56336f27714fb224e2e2850bf877e57aac58bb0146a309435e1f80c64f3414f7298fd702cac382732d9212b061c73e0c7d6f1c857577d1590fa78bdfe9db1e9d9a54143d1bbae23c2504aa3ddbcfa1d39906352aa06b654ae4d2a3b0a8f323b1888b356dd9f62dcaefb1eb88a9a8d6d871e8d6b4583d6f1a36cc9f2e12cb2616745dcce916395ebc5bdf5b7d34047fefbfa6adc9e2b1833b2931f178436d4bce232beb8b12f42cc8e03e5d31ed873fb435c0dbe48e8f3a54248b89cb2253c33ec3fe93128950cadc3b77410cfd66b9cb7edcb972dcfccf86e2cae943428c198e9cb86954f5e61d4542205781a262bb3d7c8d6983da21fcca19693d68918d2ee8979272e18bf98e59ed5c09aedd533937f2c84812b91c78d96e4c9bb7024d1ad7c307a3462057a0bffc5ec4e4183e92cc0908b97a11274e9dc6d59b51a2bff3c89878ee9a05ee560b3c3c8cb870f5060e1e39c4b61f5eecd6152d9a34446040a0e8efdce4a67d737a56b7082af423b2f65d84452e33a763b90cfeea8533f8f18fa5d8b26337dab779167d5eec0aa349b7d61f32e658241bbd30f1cbaff1e3ef0bc57df018053c24ebbb7ddba245cd92f454ae730796acda820e03466b6b92615d9ba0a8214c7c78fcdde87014c5829ef22df244dfa2aff816ffbb638f71c1a70e63e6db9d60a26fcac4a316baa7ce01bb5b12cc49f8e0afe38833737d0649b7671be1b7ff5cd78fc90928417f88b8f26c268b3dc63d9398108f027902111e636b9ec4032fa46a7b6e07fb8c8c4464399eb4d2ad78a6df2834fcdf8bf22027f8f8937b36e2e6e573629ddbad57a8f324fc825297d1eb1cdeb2027f7c3e44b3ceb5b6e7c2407313b580c7ff731237c3a3b4a381173a3c855fa6e4ec7ea433127e675cc1ece3b9ab4968b6a25be7f618f6c6007a0124aedc7b9a290dc1e06fc76a96135bda4934e76335f1bd70e3167efe6d3eb66dde0a5f6f4f3cf3540b346bf2044a93a52ecabf85506b022ebe4f728fddd4853e2de83ac78f1fc3e8719fe3c59e3df0bf679acb4e611e059604fa2692d0adff209c3c7d0ec9f43c26a301e54b17c5f461dd90df3fedf6e43d868cc36f76c55f7982fcc590c5466ba24dceb505167816753ddb9d053d81bec7e63d86a079b781f220177056fbf4211d1076f9ac43563b279645a25bfedf13cb0e5dc7f243b60aaa7e9e465cb97a15feb9d2fed6b33b5955d4b385a067b147b82f667e3116af0dff405ba378973eecb1cf55828f876beb9c11bec39108f9937d569fc1cb1faf7ff91782f23b8e2a752f84dfb88219439f43525ca414732dbb5dc435f4b3f2924174c4a1c3835c9cddf82b0ae6735d2b5f71ef58ac6ef8feef6d98f7f726346a5817ef0c1e80402e1eb993c0f277c4c22f9a35da7d679658127a4f84865ec7aaf59bb1e0af7f111e760b258a1547fd3ab5d1a86e35547aac3aa50548f4d80df18d72a84bfdadba223224185fce9889964f3f8d7a0d1ad3b5ee709f2970a0e6dc803b77df9a2ec8ad6b572ee1e561efe3c2a5abe43a275692d1b95543bcdbb399a817e28aab376ea2ec933d111b17af6d019e6bfe389e2ce8d8ae5d9c4d3f2cea9cb896a3abc97a2d49ee26bc3d7339721570ddca63c58f53b0e5cf6fa565ae7d5bf793d56e0fb78e787ff131112fe87c326c00464dfc565bcb7964559d49ef97a3c8247c37cb7190947aa573dd56cc193d68ca8f5fc6971c1924c64662f67bbd45dbd8fb819bc1fd3a613012632264163b4fdab538aa89760f74107366dcdb7d95983f200c6e56bcd4ba0e3e1cd41d67cf5fc284a9df88ac756e73adc98a6bd8d2761673c648d63a59fe7929e1d7bd4b17cc9ff30dc68c1882909b61983ef757f47b6722baf41d847d3bb7911324b0ecce5d484c40bea2786fd42824c4c723328c2c45717e7ae0c0cc390a9408e1ebde2ff48c058b14c79837fa5282d38bc23137853360e9da5dd87488bf11d7cf54287f1e7c34f4256d4db278ed6e441a1c3b7812676b4e88efd0ce3dae4fc05df8ba82b3da372e9c29be2d21e474327f63f27bbe3f316738fe706eeefad36f0b58c1b435455641097a16e2e8feddd87f32585b93d425414f17da57cfd97362a0148a1038a51f79e312be1d4111ffa19df280bb24ecc6657cf7ce0bb876e6b0c8066477f5ae2f05345bbccfd63486a950ba18deeadf455b5364341cc9737d8ae6358ae2fb0ffaa0683e7f8cffe22b6cdfb10b89f1719ab0a74688713ae4c1e4e58366cd9ec25fbf7c87977b3c0f6f4f238e9db98817defc18f55b3e8f9ddbb7483110c2ccf33b0b83bb870f9a347f1ac9dc956b9a829e863b7c8eab32fb7bc1cd1d75ea354093c71f9369047237c19c88ef16aec2e5305be53767860de82ac6edb767a953b8bf1dadfbbd8340173d3872073d0b268f1042cedf97482c53ac4d337a53777e57e9c5391e3979390cdbd7fcadad291857567b66234b097a56cd06c928c67f30d2214acbe36b42893cb7af48a3c33e2752f3b4207b6ed345dd0df111a1f87ed48b58f3eb0cc444dc9227dc01f6f71dcbe7e3ab41ed70ebf2195151471f492dc57aa0bf6bc9b9b1e788ad690f33e773c78e39140f0e1ff744bcd2ae2e5e7dbe09422f1cc58f3fcc45f025b6d649ad2cb66134c5e8687799756df4f6c3e041afe18f6f3ec12b3dda215fee2044c4c693c5fe095e7c65208e1cdc27bf4f0e0c3ce7ebddcefa26310df0d3c6d077f95da7fefe1f04dc9decbb6ff443fe206f2425994551c2a94b57b162c70991459e16df8d77ecb278d7e1d3b84ae13f2d7497780097c75b76d2d61c59f5cb57a2dc3cadc47246f948b1dcdec8e3c7b93312beb7c99fe5dc9ee3b2aaae64a932f49c2ce85c192e77a03f62ec9aaab5a956e0f65dbd3a217c8a7ef4323cbd620e97abebe5791c99556ff63f3cfe7427142c591e5e7683b87053a61b174fe3c49e4d38b0e16f845dbd906239b098f3b29eddce918e95b64d5d7b0517af86682e005ddb36c3efd3ded7d6140f03fe46f8dba1d78ca8f8244424f9c0d3db5b08b07bfc4d8484c5c0cd2f1ff216e7d1edee552292111315892f67cfc36f7ffd47612b4934abaa51a114460e1d888a152b6bc711c95a4e405a65fabc9f131886bb1b392ea359fcebf718fbf56f30bb79d0f39851b55c717c37ea25047af0fdbba6cbeb1f61c17febb535a044e1fc18daac30dcc533b32fc9f7a197a1bb997cd0ff8b85c85da884d86fcfb923bb317be40ba289dafd7420935e561fb981bf0f5cd7d664e5b8cb572e232077fae398ec4456d41b25e85984296347e2ad0f3ed3d624633b5444a0cfdd8de6247c8b7e649b723967417798781f0b3ecd731528863c858b2334f8bce8ba538f4c746b418e71ae590e5a64a3d7bae591a8662ede2caecba88a709909d9cdead683677139de079d9f7f4e7e5fa9462f4b276c796b027d33e43adefffc4bacdbbe8fc20909973909a58a16c6876ff5439dba4fd061b491ae2d02e29d2aea3d42ccb111e8f9ea5b38723658dca797c980775f7e1ecf37284db74f62ccfee584ab0a72af3ed70895bc23c4323fb64c50cbcaa975dbf743f39e6f897df624c6c762fae0e71071fd8243ad76feee8494cbff0c253cd62c9ab0d933f1ddd7316cfc746d2d679115f526f37e4d4eb8f2dc9cc4f7737fd4962465f3fbdeb59833c217e94794a5d3dbe70842b7b2b94c9d230f6911b8c393a6989b9771f1e036c4deba2ad6f5ede23851e3569eef2ce6c9ee46fcb65a0e4aa1f3e1d0de4acc1f22b7c223b179df0958dd64563a4746172e5fc3b92b214826eb37363e1141f90ae0f90eede0c695e152caa159756e93359e1662e8d664e42137674c1c8fe96387234fa0bf0813dc4deccb2326a06de717b06df33a793c8715cd72cd8c987c02d1b2491d8a24932948bb0991fe6bdd2ec45a78e438ba77177005b9316ff4d2d624bfafd98f2437dbb72a445dd3858810d7a316aefc6932c249ccf99bb4cf6a7f5062ce04517c52ae8056e4a1b160e19fb69bcd61b812efccae435946d05d9153acf373a78ee1f8455b561853a794eb2152d3830892225290222c455db61d4f1175126b39a7c9a44d2942aeb533a76375ab811653c49cdd3f12e98388a818be928023ba11af74d7d6140f1afe36566fd9838fa7ff8628378aa4c9c28c8a89c5db9f7c8da9bfaec2df7b2fe1cf9d5750a85819f1ee8480f3c86889fccef8258a8dec103b7767528ee7993ca75993c65832670a5a36ae23ba15b690e05fb81e86fe23c6a377ff5790102dadd6ccccb3cd9b206f5000f9083d939b01078f9dc1914b3c4a1a8772d78c7cad07f2e5b17d9fe191d1381a65eb34477c26da7464f37fd830df71909613bb3760e7bfbf887a29f2db920966a1257c9e3cec8150a79463e5b803e76e88d1e11459832c2de839853933268b2c3a1d16d39a25ee5dd019112970e440b184bdb52ec55d13766de2ebd996a588eb65e67cbc889cb49886ddb59275be609d6333b5775e5562fe30d013b93c2f5ca8088e1e3d8ed9f39620382c01c97e854413b19dbbf7e3bb1f1762cbae43786bec0c6cdbb41e560b578a232bd2a8955b0b0b5d0410b92e94fa0e18e87c1118e4395c1f23304f614cf9742cbefdec3de4cb1520b6bb1b8cd873fc029ee9da17674f1e8335916bdeb3a59e8e6b3c640a95ac804ae54ac14cf7c809591e6c66d3fed36471dfbe02e148a7f0be70fd0169a5ebde490bdc6c8d3e2d6cfc7d3a668fec8103eb968ad10d174d7a4b5632e54433cdf9fba4191d2dbeb2074a8d6201e27bd749b05831f7db69da9a22b39365cad0b36279464651ab4231ecb36bae56ab44207a3f515c5bbb7f842f6a5ec9e57bbab7eabecbebbaf7eb6fc1b64e0bf23f8543d10198bd748bb606e4cd1d888b5be68b3274c5fda387fbd4df841b92441a9d852219b1ee7e183cfa732cfceb5f3c51af36860c198cbcf90a60fe1f7fe2d2d510d4af5f1f5bb6edc1b9e0cb28983f375a34a98f86b5aba05a8ddaf0f0f625a39b448bafc5e2cee5dee22df33a2f3a5ffb0e582d8808bb85495fcfc1c2ffd690d81bc9b564789b0c7867505f74ead8d175a7369980e5cb9763d8871360101de75851ab4a057c3aa8130afaa76d0fc5c527a048fdce088bb0f58cd8ff7f4fa08a5fa4787fecadb6fa2a3c67a7e9871e5d0ab84c2c53fa39659df73d0c9ff969cb45ecb960cb3da95a221f0e9ebb7ef7ef3c1b9056167b66d59eb443642622278b3967b71f3c7d595b9338678bdd2fecbbc28b69d22d76397164c296bb9ccb49eee3e3e424cfd7311bbc5259e7c35eeeaac4fc3e700eebbadff3c8695c4e7ee57a28fe5eb315af7f3015b5dbf6c3f8b97fc392b72c0cb90ae09d7787a35ba70ed8bfff20667e3313a5cb9641b76e9d50b2702e7479ae15befe723cde193a1026b2cca7cf9d8f9e6f7d82ba6d7b62dca4af70f0c05e58cc66baa0164de8567b0a77f10d52e220304f7e8c1d3312b3277f082339c3cf156b4ec6075fcc44b7be03c52870f450bc433b297350a7760df8fbf88afbe27bbe7e2314a1b76e3fe63c87f7612f3bf6b5c056babb072594f88fbf25166c5a3068b95ddc2a40e480b1654e932ee67cacf836a5330f9c3a4e6dd28f5e0ac5a11db69afb3989aca63359c242e7c8cb99ace6d1f7ca98a12f63dc97b6a145fd3c0df8e479bb26400f10573e7ca748c5d93a0f0af0c3a5ad7f8851d514770f8b7664742cae85dcc2151692f0689cbc7015fe018148763322815e52ee7c79e1ed1d08778307f9b30faa3c5611c58b1524a3388e74d4138971665cba72458c2256a67429787878d0bba50481c54c29fa64184c265cbb1e82dd874e62d99acd3878ec04c22263e81bb3c2dbc384364f3640c7679f42a5c7aac08b2c77113252be499e734849afdcd0b1f4edc6444763c1dfcbf0c58cb9ac6c945648820f85ed217dbba3cbf3cfc1c364a4942459eb7a62e211926c49c4df2bd6e2dd8f3f23bff384b7a709e387bd8ca6e5036cdee0021e85ad4883ce0ea3b1e956baf006fea3391be6f6f119bb29126d4ecb0f93d18b8e212aded6447658bf4e98387b81b696b3c84afaa3043d93e39cdddeac625e74a855485bcb5c58dd0c18fbcf698445446b5b808fde7c09ef0feeadad29ee84d56a257d9322169f60c6bcbf5661d1f28d24e451285eaa242a54288f264f3e89c7aad4809f9f8fb0eab8e75f37217cb2299a1081e4249acbec5cf1fdd0bbe14fc68dad6c5a1533feacc4a7259b60b1c8874527e0d8a90b58be710756afdf84889b6130993ce874037c282150287f10bab76b86b6cf3e0bef807c742a59f05c76aec39fa58bef35157c1eddd3a6addb31f8bd4f20fb45b08aa282d2450b60cab8f750b26449127b7eb80cea05ee9e21abfcfa75b4e8f0028c1e5ea295c7db03baa373bd22c2235dc54f3a6326cdc1b8e93f6b6b40ae403fbcdfb6ac783fec572cea8c7374265e19bf1cf9ffd0f96bef55ac3b1eaaad01158be6c6b18bb49e9e779bcd50829ec1e45441e7ecf672151f73a81037e2d9b228922b735abbe7ccb93075816d7854b6cab9ec9c87f054dc1e3d3c2791b245c7c661ebdea3f8fecf95c853a0382a57ac88b66ddba048f14242c0dddc939190c0bdbc5961223135d09f8cf8e5772244e21e3f0fbe0f91a0f0f0c6be4327b060f1726cdab21d513131a20cd96c7583992cfd205f4f746bd3087d5fe8262ada890bb2f0f273f0f72a9e87975d58d87aaf747c0c09e2f98b17309444fdf485cbe278ae93e5493f53c68d42c306f569934cac3c32e83ed95f1a3cf31ca26213e141b73ea47747bcd8ac1cdd9596604a839b611128fe44378776e96f777b12c5dd6fda5e51ca821d9a938feaa92f87c5e1f365a7b5355961f6e8ee4d285ff3096d4bce212be98f8baf2d7371bb8f25bbf3d76f3f3a88798100cf4c2be6c9f49efed9714a5b93bc41919e12f33bc39103376d5aba7a2b66fcfa0f7e5cbe0331de85f0f9d469f8eaab497863c820942a531cee6e5612fc44d2400b8ca2e73096702d7cd04c64e1eaebfcd9d0a4cdd23531fcbd894e87e26350a362097c30ec157cf5c558bcd4bd138a1729042392c4a863b1098998bd682ddaf4791333a64fc5e5d38744d6b480233bfe6e59cc392bc01921e6b49d27b2c04b162f81453f7d8bbe5ddb891c040b6d8e355bf1da888f307adc4444848723392981523b36517cb8d0fb890843b52a9545b937d7c84fa0e74f0f7972056260cff6da9a64e9f653e27b49f17bf62ae749dbf7a8e078a660a0adde0bc7437ffcf4bdb696b37025de99559732bda0bb22b3a68e329a65cb96694b921ac503b5a5cc474872104e5fb40d46c19582deead7595b53b8420fc75bf71c46f7a11fe3a77f37a15ccd7ae8fe621f3cd7f17fc89fdb9f34cf4cc72520c91c070b0ba61b4936098a5574e2c2b01b729299e68e131f9fde89b3e853dce1f88aaee36148428df24530b0cff398f6d9fb18fc4a6f942d551c56732219da8988888cc537f357a2fb900f317af47b88be4561804fe667634b3cad888fc59eb3d385a8bb8b5c813707bd8611035f841b9dc709130b454f4b57ae43a7975ec3d90bdc5b9b094878346dd78d0613a2a3a32951954c8929034c26bb62863b30e2956eda92e4d4f9cbe27bc9ec542fe618dffcb7d236e6bb22739225053d27906cb562d721c76e189d7b71ca4cec3c1ba62d49fa767956345753a486859ca7b394009af6f312cc5db60d9d5ee885efe77e8fd66ddb2177a01f2cb191f0f5f110d9bb2c7006b28abd3c0c30d217cbe5cc5c1b9aad590e272cc21935b1a8eb936dbb95ae6b45917cdee8ddb515a64d184de2fb0a1e2b5f96049f049bee213c320e4b36ee47d34efdf0c5848f70fddc21d273b2aaf97416e124332db8402bfbe704801b255e7a75ef863fbe9b8832c50a896c5e2bedbf7a330cbd5f7f1bfb0f1d043c03b84f5679eec382eecddbc71b57af5c161509f9a1bc3c3dc88fd248ac38c19dcc0cece568a5ef3a1fae2d655e9ce39bfda782111dce9dea28322b4ad03329cb97fe81f018c76cbd72056c03a56426120d3e58b7fba8b626e9d3a995b6a4b087853c21d18c3f976dc4e08f67c0b340294c9a3c197dfbf4858fb7272cf1b16405926eb1602473ab72ab3625c39dce75e74a6ee48e7bb23b8c6e06123dae30c6d9b7fc2967ecc415ed64653b9ef3750c48b624a1703e3ff4eefc343eff7018de78a50faa542a070fb25859df2c162b7efe772b7a0cfe10efbef71e4eee5947774ee7b2a8df4e88f9397830167ac6ca15ca61f1cfdf61509f2ee4a0fc06c2a36231fca389d8bc752b1de721720f1e1ad60491c51e1111c6990994c832203040ff16d327eafdbab4d69624eb761d45027d37999992797d1c3a9989335bb1789eadc58d22f3c15fab2213b278fe6fda92a47c26b6ce4f44184593289d8a658aa376d50ada9a4287c59c2bbdfdb468057ef96f0bbabed807ddbb7581370904f79626845ac49fb22c3c39252e6533d70eb1431cad4d29073e70d82a15b9e4f453a2506ef4e8d802df4c7c1fefbe3510e54a978239218e8c713342c263f0dff663e8fdee140c7b77144243aef378ab42b06f0b0bbbd10b0683012ff7ec86e99fbe0fb7248bc892bf1a720b43477f8239bf72f32957eedcc1ed7bc5dd03bebebef00b0c823b255c8c5e3e2850b0a0f08b741ae9a855a53c2a972ba9ad51dac66cc149fa6e32339c0bc4a26ecfbf4b97684b8acc588e9ea905dd9587e9e58ed99d759b6c6db999720533a775ce957b56ec3aa1ad495e52d6792a38dc265224fec7bfeb71362c09737ffe0dddbbf70097c4ba9360b9717335ce42a73f2bf9691209581259c7c21e6701278b5c4e24780e93fc84f9abe04fe37e279938709ca470c9897ef83f0577b29403bdddf15ccbfaf866d28718f1e6eba84616bb17f71c43cf141d6f11c2dee6e577f1f58ca988ba79595ad7f262aee15c019a781cf2271bd5c3f7533e86893671f9757ca205e3a77e8d6fe6fc22ea12a4b8437e67159dd23c00cbdddd88e3274fe25658045dc38ac2850aa3a0bf56b1ef2ee8f5dcd3da9264e5ae93f498f2fd6556ca3be50a6ed879e0f6ef2e9b925574277387a61c4ac8d5609cb9ec5856e5fc61651622dc0371e9aaadbd2ae31c71e5743832884f48c4f41f1761c19a9de8f5622f7818695b7c94a860c56dbcddb85b30164ff14b13c51f6e24e43cc92d3c2301a11d49246442fa85c0b2a87005391232dae726d639f2d1e70439cd9dc4304204a5fc6bd3dd21ee4628babc275de45948f3057a8a77ffd947c3317cc8aba851b10c3ce87e385912151787190b56a2e38061d8be69351dce59e977b8071252ee7eb66edd06f8f9abf1f0f79243bb7afbf861eefc4598fdcbeff45cf43c5c564feeb038ca04d0dd3f579a88f6e2c958fccf0a24d1bbb02426a12a3d571ebfbbb7ae7b7670fc2e2e5e0d41a431637b7dcc689ccbd1af85c7e2c81edb90c88acc8512f44cc89ff3e63844733c288473d65766e1f055c7a6442d9ea885c205f26a6b0a16739efe5db71dfbce5ec6f773be45f97225e065b2c0d32d11eeeec9701762ae0b2559a664791b6872e7b26731b168b29893901b2c3027c793059f043389b899fe92dd1349dc4920699e608e85d9124b726ea544821b1248ec2c749e25d982c48458f1c5bb717280dde2c9ddd61b98a6d1e942eab89de5ae0929bb5d387f203ab56b86efbefa042306f543f17c81b0d2b5b94ec0959b61e8316c023e9ef0996ce62602faed459d2f56b95a2dfcf5fd5414c8e5438f6015cde63e9f3e1b23c74e1003a5b00b9c1c12fecd7f34cf10c81d2b25867e5db8041e260ff8787be0e94675e00d4e44dc1d450be543f38635b535c98160db8884999152f97c1dcad199954bffd49614990d25e8999035ab566a4b9272f93367f93947991bf639b63d7fb1634b6d49c1b0d86ddd7304ab761fc7d88f3f466040009248c878c431a3a7a7102b67e949264b9bffa40fdbf6b25cb2e56ea2f338eb57545a139dca7890f5c8d6a987e82b9ccbaa936124612757dc4ca4492612744a1e78fad2b5296120b2f0b548daf9e262c3bd4c365858b9599ba72119cfb76b811993c6e1f5de3d50345f2e4a9758e0ed6dc2c2d53b3060e870845ce2e21aba1761b1df9e7cc54a63d10fdfa05cd17ca278c2cbcb0bffac588bd93fcd13fec1b912ee5c4ca1d5fcd773251ce0a67477c9860debe91665ab82d6cf3c8d4a4502efc91da6d7738edfc7a603a79d7c2ff351d6297770fdfa0dda9222b3a1043d13b2ff885373b54c5a7e1e6108c28d9bb6e637dc94e7f9564db4350573ecf4798cfc62363a76ea8682058bc04242c7bdbb315ce62bc547489a03ceeb1229c47c0a4f274e9cc2b9739768b3172510288cd0dc68f4a1c99fbe6c0f4445c563e3e61d18396a0cc68e1b4fd67b9228974ee9d4244549ee5f52d848e749475aed3427b74b9200bfdaa70b3e7f7f385eecd41eb9fd7d91688ec3d68327d06dc88758b6f40f3a901226dc798c2b114ec10d81790a61deac696851af3a92c9ffdc4d9ef876ee6f98f8d53774aa4c5c88ce5f341fd47348c432173770f9fc5d906849c2e84fa709bfaa50b60c7afcaf05fcac1ce6efcdcf3a3ddb547c273ad742c210e791475bcb9c381b147b8f9e729d58523c7232ada0eb11823d19968d968989898ac0856bb7b43549666d7fee9cdddea97553f8faa84158180eab3ca0cadbe366a077df97d0a4494324c4478347d6b2927567494ca08f8fac3e213e5c22ce16b736d1326f93931d14fcf90b48b6bae1e6cd08bcfde630b47cba1d5e7d6d307efb7d01fe5af20ffef8e34f7c3e712206bcf21a9a367b1acf77ea82a57fff075fdf40d1198ab0d0a59c8b6f4c7e67ecaafd35ef7d4a79062e22d0d679cebddc55a9501cc35f7b0133277d82e68d1a886dd76edec4db13e7a247bf57456247dc0b0bfb6df0f4cd85c99f7f8a0f86f4119509d956fefdaf65f8f39fe5b026c6d2a319e8e958c8f968f123107ec71bd3198f8487ddc4c0b747213a3616050be4c16b7d7ba07c105d2d1db90969c1dd21776cd5585b931cbe7ef7d9f70f93f24e06c5e55b31387960a7b6967370a53fae74ea519269fb72cfa982bee08799e8d2e7356d0df036b96342e7c7b4b5cc03bf89f12b2e3958e82b7f9a88a71b3faeade55c389c72f89df5dbdf58b46e07fe5abc90d649be29488be272b66eb4a09c6c67313a0479dacfb9e2420f195ae053783599042b3121095bb66cc78c19df62e5ea7548a244029fce03a9f8fafa2057ee5ca23676d3668dd0b3570f1429581426ae799e68166207034d74437ccd64915d4df0f5e45286211e53fce833b6a08da28f854ddbf760f18a0d387cf40425646350a17471bcd3af23ea376f47a2a90ddb2adad9bb26998ed9b17d2b868d9d4cee51e292fcf5dd81bdd0e3f9f670f30a1016bcc88de0ec729af37bd1130dc9dcd35e320f6043fbe8a558c9124f4888c78d6bc13876e820b61f3a8e7fd66e2701f643ed5ab5d0b3635bd42ce10b631c5700d51ee81e59be61279e7de91d6d0d28982f17463e5d34c3fd3e2319f1c7112488c4a064ea87c330e48389da5ace21b3eb9212f44cc6cb5ddb60f61fff696bdcfd6200fa362ea1ad651e620c0118f5b3ad699daf8f17a28f3876559b9349349bf1c157bfe089164fa175bb360009060bba6c6aa51dc490388bb271b12cb648e81829e8bad8ca79126db7ba9960213d3a7ffe12667df71d59e07f93a5fe34aa56ad8212258b215fbefcc89d27084141b9e1e3e309a3a8fdee8e643ac9ddcd088341d8af5c2f5edc8ad1ee5b73f1d96508f69fae58e667a38b8586c760e5a63df8e5f785b872e53addaf17c6bed91f2d5b93a8730622d73267b8729c2b282173ecc87ef41f3e16913109c84fcf3df28d97f1549327e066f2823931011191d1888c8ec2cd9b3771e5ea559aae23263e0e31313130c7c78bed376f85d33171888d8f16f51bf2e4ce8bca952ba071bddaa853ae3082dc63e066e11c29bb07b90ffcabb446740c0fb02319dfeb09f826dd7e8cf547c9ec8d177028d8767fcf35af83456b729e95ae04fd1ec9a9825eb35c51ec3f7d595b033a3d5e188dcb67be32b663718198b9d8d67ca55d8b86583afb136d4d71f0d8197cf9fb6a4c9b31152683953f341253166e2d5c6ba2c6b5d8f5326df1a36fa7b9d8ae37331315e568dd6012eda1e31280d75f1d84f51b37e0fdf74660c8b091a2abd4646b3c1d45d63c9d1717c79dd558e0e5ed85643359a031b1f0f2f113e24eb629250cf81a52d0c5f5ef041da41f27be44f17397b00376e7f15319dc3d101c1a8daf66cec1fa4ddb440df6b1437aa363e71e743c9dc0df3d5bebbcec0a7ad653c70fe1a5b7c620960cfb42797363fae71f238f9f0742c8af22a2a2488ce310e0e986402f8318cfdc4056b9d96c459cd982f0a83812d778c4b3056a342077803f0ae5f2419017252212a3ef5804702fb4e93b12ffaddba1ad01af3ed70895bc1f4d3ff5e961e38950fcb9c7365643e98281387335f3775f9bd164765db2e5f7291e394949169cbc744d5b9364d61ee20e9cbda12d499e6a544b5b52f0b8de7f2edf88675ab586a78781b458b3aeb9e393dba1c70b297316325ed1b33add904496be8f5f107ef9e927ecdabd0bfdfabe84177abe0058d8da8c104dd37844366e9bee61728391ae9d1c1581a4e848d1e39a9944dd9a4002956485bbbb81848d2be8a52194a9e0e3ec27098fcec677296fdb955bfa03d15e7e246d99e1a484d5624691dcbe18f3d6cb78ff9d3750a7fa63f86ce63c0c1e3a1891b7aecb6c773e29ad8a58b4bf5cc5aa58f1fb5c71ee85cb573166fc24619517f34d44ed026ea855c01d65032cc8e79100dfe468785b2211e0168d021ef1a890c70db58b7be389d27e7882e695822c084a26718dbbf940c49c79ea89dada92c4f97bca6c38773b7df5661422436c86872273a0043d1371f1f409b24e1c23fd02815eda52e6c1ea6ec49ea3e7b43549f3064ad0756262e3116d31e0c9e64f9208b991707ad06422ddf1a0552e13668192736ea246aae134f168633c088885c4328e2ce978d1299c992d4872ebc8e163f8e5d7dff1d1c71fe3dd31ef224fbe5c30582d30ba935b64549add92c4202286b01858ce5d43d4e960c49ebd8c8433971077f63c224f9f45dc954bb046de84c1920037ab3b12c85c4fe24a73466efe46d7a1fb4a72b3925b9a88bad26982c53c81121016da4f062f2cf4bc89b49094944c8fc8cf47098a64aeb6c6937c267e46ba9a98c4329dcb63abf99245dceac93a98f2f1dbe8d3ab3bf61dbf88d65dfbe2c00ebd99143d5f5acdc5c80d1f5f7fe4f23152a466c5b3cd9ba0b0310a9e0924ca8951741e5f5b92c6a310fc2ef4e9c1e2dc1e7defb173e2bbcaac140a728c87b85ff79307776b6b391b5756fba322530a7a4ecd6edfb466b9b62429963b73d6180fb5fa8b32629da0003f54a9504a5bcbd97038ddb2e730899c014141419a367078265b94b38d69ce59e972e27d0c5bb8f662a28575da2fb68bef818596130246ac5ab5067e7e7e68dcb8318918b7396701a67f2b5738e36b242331260eb12161b046c4c023d10a2fd24e6f126d2f125a135bea64bd264646c01a1b23cfa36b5845b6be848b08e4829c891d629bfdc4026ec58dd0689c3f1f8c9b5c1440d772337ad2a16ea27c9a145fabc92f8f97c8f3f56796891a092f06fafba24fd76731f1e3f750ad4a15bc36ea33cc9dfd3519e8fc9cec295a22c31e4a20dd0abb85ad7b0ea257e7f678a1451518cc99b74cba7aa5b2c81d14a0ad410cd8c3df5566a68893a86fddb45e5bca3964761d52167a2662e78eadda9224bfbfadbd6a66e2dc2dc7663bcf3e594f5b52f02035c74e5f42d3e6cd4439ad14411b2c6db6c951e87889cbc9495e6989cfe5fddc898c07b9cbe5e71e08be148c9f7ffa119d3a3d87b2e54a23c99c0836cc25c9309ac93a0f2561bd7203c9d131f0a644847752120c09f1488a8f8581ac65a335091e24ea965b6188ba74194924ee464a14b8d1bd73253ca9af7c17f26eece1c7d1759d675c916df4a8e1e8d9f34574e9dc1dbd7bf6c2844f3fc3c123c791ec6e223d272b9d0bebb56792aeca73f5927be91712a1d774011e18a46ef572f8f8bda178b57f1ffcbc682d3ef9f84358e2c8da7621ea89f46cedbaf6c61375ebe085671bc22ddab1e82a33f2d4138eb95acedf5566237f80a7b6243970e080b6a4c82c2841cf441c3a7c445b92387f409985e3171dcbfb5a3454d9ed0c979d87454663ff890b78bc4e2dd21cd9444a9fc418e30e13b7d3a699ae6629d226c58f872cb5588c24705e7037fae2af3f1763f8b0b7458dec679e6989b8d870789ab88c9e0ee789488a8d43e28d9b304627c03d361e21a7cfe2e2a12338b5ef002e9e3a81f8a808182c667891687a992d3090851e1f1e06376ece46c2cf96b9105a718b2cee2c9cfa051c27ae7c57ac48417cf9e55798f4f9043cf3740b21c63366cc44b76e3df0c39c9f70991216dc939d7c262e66d09f915d603fe155728f26fb31d8e5515604f999d0e3b9e6f8e2d38f702a380c5f4eff06c9dc852da72a52cab793316dc6372852bc24fa756b87223e9c7bc4ee646eb89b647b9cbfabcc86737c74ece4196d49915950829e893875ceb19249814c28e8561299a36782b5358973c494d1b048d84f99152e2a4a2461f4f2f6416040206d213114e6334d76629532d9af6a7067679cb32cf42a2919e624371ca788f3eda16fe1e371e370f0d061b46dd312450ae74562420cb85734316297f892dd9014170f1359e99c116f8e894674641892cd744f261302fd02e0e5e92d3ab7e1766f463adecb480986441246ba282521e84fb3a2b92b599a4473377e0e12769ecb1ec26c37cd59f5febe7ea85bb71e5e1ff43abefc6a2a09fc64942e551ad3bffe163ffcf0336e854790f324cff4703c891aeb742581ee9478af52d47912d7a23ff17c30a346a502f874ecbbc895af30de1dfd3e4e1fda41e903fa3e9293107cf12cf61f3b838f87bf8ab2fef1f46c8e1d1e65569c13c2c7ce5e16df5766c5393e3a7bf986161e14990525e89984c8b09bb8161eadad4932a3851ee9ee2f460ed3295ea4004a162da8ad3d1842c322f1cf9aed58b8620b82af878bb1a433232ce8b72263e0ed1b001f5f5f9224bd9e816e95ba9a7445e365eee4c4082b097912093957809b3871127af6ea8d33e7cfe213b252172c9c8749533e85bf9f97e8754c6fa72e9ba02523312a1ac6f8047890b5ede7e783e2e5cba270b1229400284ca26ec4cd6b5711131eae65d3d3b9dca90c25422c740ed7bc4b4a7287c56a104dba7897e873269933dec5056825f564a0e73691f00606f8a2088587e73b75c0dc9f7e44e7aeddb0e4ef7ff1ed37b3e06ee4b06c2001e0737441e749c7e61e4fb21e0d5d97ee93fb67e7def582028c68f36c33142a5a0adb0f9d8239fa26fe5af02b468cf904fd7b3e878a81095a5bf1ac4199124550305f6e6d0d88a377c0df5766c5393eba11198fcb678e696b8acc40a66c87fe682bc5915d10f7f0db83ae5bf617fa0e7c535b937cd0be82b69479d811e2816f176fd2d68097bbb5c5779fbeadad652c09947058b87c337efd77038a142f8683078ee0f2e5abf864f410bcd0ba11c903d792ce5cfcbb7e37fede7c0c53a77f496be130b979d24746e159fe3b208334f7f0266c513a809e889499db8c5b49c4ae5d0b41e3a6cd90277f5efcb3f44f142c940f89f131484c8c23b10e80c18322584a3358298163764f8281c43df1d265986e84c1c44de40c64b1c7c6e1c6b90ba21395e88478326a4d2854bc28f2162e8444f23f7772233ad1089f32a561ca955bd4b4e6b6ee6ec96658931311151389004fc7ca50cedf273f9f1c50467c3de259cc66b6b18df864dc782cffe71f6cddbe05461ed49c7b672371961dc0d1195c8b9eabf08be717ff0e083f724b82d99240cf1b80b36783f1e4934fe1879f7e429e3c79f1c65befe2fd77dfc233d50bc22d3e4c9e9485e8f7cee798f387ad43a637ba3443cda0586d2d73914089bc09ff390ec6f4dd171fe1e9e75fd4d61e1e1c46c103113d021ead3edd1e25e82e48d8fb3392421d03ae42d267ca6afcb17c9bb606fcf6d518746bd75c5bcb5896aedb8970b740d4ad5f1f850a16c2b5eb57b175eb36cc9fbf105ddb34459fff35a580c126e4a387c32787db797fadc39eb3b730e18b4fc8da8d8487fb9d059d966862ab952d513e9007503162f4c877b17eeb668c1afd0e9e79a6052c89d130990ca24ff664b6e279b01550c4e6ee8e441246aeec167ffe123c42c2e0c1f743bbcc94280abd788d8e7313b9063ca4aaa7bf1fe0e381044e10194d749f9e3870f51a0e9c3f8f9b11d1f41c06942a550cc58b1741d9f2a550386f5ef2666e5e269f20b5a093888b2205abc829b0d2f326096bdf88bd7b0f6040bf7e98fee5545151109448704fa6440c3f2ee710f079c9f2d9d316742bb96fa1c48e01e72f5c46db765dd0b5734704e42e8422c58aa353d3caf0880fa183334758b81b7e5db21a2f0cb575c8d4a55543cc7db385b6a6480baffaafc23da0b0b6f670c9cc822e3e2b85239ed53ac3cd23738e70f6a83970e292b624a9532de37311f8e3387ce21c361f38830ecf7541c102f9453fe4450b17c60bddbba34ba7e7f1d95773703321f395379a2d6678fb78d147ef2af7802302e7c8c06e9b542f21c2b1d1d158b0702159a38dd1b85143c4c64489b2f2f8b8581273b67293c90097ed96398211238c7144c365ce460389b9dc67f2f64681c20590bf682178506220213e5e1ccb02ef4e560ea50970f5e64d8cfef8138c1af3213e9d3011133e9b882143dfc64b7d5ec6d429d3e878230c3471f9b773c425749c12245c814dcc3561e76b2426c6a374e9d2f0f5f3c3fe030760a0c4831bdd332743d88ae7527b167fc76848f70f9e24dc5c4d3459a36b172b5214cd9b35c6bfcb56a272a57268df28eb8a39f37855c7efe7c0898bda92222d4ce55a3e3231675c89b72b917f14643a41cf14a91fa3173c6b74d556143ab189569cba70455b93703960466349b2e2b399bfa2d953ade0e3ed0e3f2f2359a51678799160c647a3458ba79037777ef41c3c0ac1a191243499273237b3959c144361d64d88600a9af0690b7690b472e533b14ccfc159d08664c49318065fbb8a4a95aa921f04c2df37109e261ff87805c0ddca59e2dc810a4d740d96531399c6620b59e1b1c949b092a89be39390181e81d81b9771ede4519c3f750c313111488c8b83e8143ece0c1309ecb1cbc1387dfe023c8c1ee412975bbb91f09b71e3ca0d7c3763363ab4ef82b5ebb7202ed18c444ab0701101ebb61b9be39a75cdf7c022ccdf2abf0f8b3901064a6f454787d2bb73c7895367b06bd71e5cb97e1396641359f21e483073d73226ed5c1ddd8f6cfe24122c6e0618e83a1e1e5e78f1859e78eaa9a7902fc81bdee65b7468d61473a67ce962da9284bfaf18a7cea51436dc739786a954236d4de18cb2d0d3c03da8044c659a696b0ae648b0a378962fe51819651427cf052322d11d8d9f6848a2140b0359961c5079842c4f0f230ae5cf835ebd7a62d3e65df87bc32e9241292499810ae5caa076adea24b424442c7ac202bd032ce29a88f12f9f919444c269e0266bf27cab85ad54b2cab9ec503c2afb88eeb63c8f940f261f5f58bc7d10419a90e4e98bb8640f5c4b3422dc2310317e7971d962c0ee4bd7b1ebfc65042758119ae4060b59ce3ebe3e62b431beae957b552391e41acc66b3192b56adc54bfd06e0df7f96c1d3cb0749220cd024555dcb6ee78e69383960a0db30e0c0fe43e8d4a93bba747e01a7cf9cc5cad5abf0faa08168d3a60d060f198a0d1b37b10bd2eae77bbf2dda7392fb56babfea356ba1608102628cf7284a786475ca94b0599bfc7d1dbd1ca5ad291ca004ad57f52eda8ac2154ad06f030bba7b50716d4d71e8fc4d6d4952be74516d29e360615ebf7d3faa54ad061f7f7f18b9fb501610a958222b97a3f7e6cd9a22779e00acdcb8031171dc9e5b17b747837efd7a351f43c7766d614e8825f1614b4b93ab3bdd9ebe9f9e9ffd80cbc9b9a7b99898285a26db9b87f81442ea287fa2329a26aa6c301bbcbc9044566c3c2506dc73e785d9271017e292b164db1e7cf4edf7183cfe73bcf6c1580cfa701c464d9a824f677c8dc9d366e0ca8d10b2c013c9af9348d413c97a4e8085968d46a39842426ee2a79fe62132324664d5cb7406df0f63bb271668eefa75ee9c1fb177cf7e94295316bebe7e78fef90e1839f21d346c581f0bfef803a3c7bc8f8307f7c368f2d49e2b7db0dff8907b5151b7b07bf7415c4fa0048e2c90cfb238278c4f5dc9bc3ddc3d4a849893a82bd24609fa1df0acd14d64c12b8083671c7bdf2a5732e3053d29c98a909b61a858a912c5d36ced91054942c391362fbb8972582b4a905533e7fb5908be7a035b0e1ea733efa4980f071662d6762e4b36709eb3103bce4310f6a8e3c496b9b06e099ac9a48ac4872ced8a152b62cfae5d08bf758b755e08ab4d3a9d20ff49a2f32d9c00ca9317a748a0477d36112d5f7a09033ffb04ffeedd8d3c15caa36daf9e78e18dd751ffd9a71149d7de77e614ea3e511f53bf9c84df7ffd190b16fe8659dfcdc4c8e16fa20d1de317e82fdac67b797a61d59a0dd8bc790b89395d839ba0d183725d76be73710bf4c7da1c12128e1dbb0f60d2a4c9183a74183c3d7dd0baf5b378ae6307ccf8fa2b2cfcf37721e24386bc8df367cf53228da321cd4f9c26e96f84c8daa7088b2e65b5c4e089860de8dac9d8b89587f0cc1ceffe5e2957d2b1d8eae4251e735d618fa9642391ddaeb83d4ad0ef00578ef3acd6495bcbd95cbfe99815e81c11650416b26a63e3e291276f5e8ac4ad22026721133d987136b025910480a4cbdd2afa32af57f7714cfd6e1ee2456f648f161e94e5a6d944126480d19d4bb4ed485389ed2081640bd74889164fb2b47bf4e88eaddbb661e3a64de0814e2c660b927820743bfde21c0bce1a3790454e07e1f8a933e8dba73f7abdfa3a361f3a88be6fbc861fe6fd8cf94b1761fa77d33172cc3b18f6ce5bf86ce278fcfccb5c2c5abc089f4c9880177bf74687e7daa37dfbb6e8f942578c1efd0e6692b08f1dfb0125a280d88478142b5e8cde4b01baa8094924b071f164d1b3754c932807e7657a4e4f4f13fc7c7db064c9624cf8743c0203fdf0f8e3b590981803b3259eacf47a9830612c097f08fe58f0279da6277c6e87e69b74191e95b040813cc2dd5dbb77d3b6ac2ee88e09e3131732778f710f1b77ff8230956fa9ad296e8712f47460c85b1ec662aabff20b571d2d87075186ce7da147c7c6c2c3a409b488aced278ef8d9fa053c3c8c78bac5533872f414fedbb48f42b35d25b487885e7e3fe3e745387cee0a892c254084c6f07679df7aa5b1d4932346ae9dae6dae52b50a6edebc892d9b37d3766f127bce0ee76c7cdb79b20cda0dd1d15148888bc5cc6fbec3ce3dbbd1f8c9c618f7d938f47ba53fead4ad867cb97dc9bfdc85bf2599e36120ebdccfc70b850be615e5e7469301b13161b42f8e125166180d6e24ca9ee8dcf579f4eedd8b9ed14242dc00458b15171ddf983cfde0ed27ad772edb4fa6dbe2b9d1c380dcb902f05cfb3658b3760df6eedb8d8e64990790a52f6ad7bb73ef6f5654af5e0db56ad6c0de3dfbc4c024b2ae405a1347533ce71977ba93080f2f6f7a1e0f84454467f96c58e7a22be7ef2c4763f080678d1eda8ae24e28414f271e95dac0cd37bfb69633397fd9d1727810163ae72d73a52b314217674993f529e550cb9016d9d49c0d6b415c54a4a8eddca37b17bc376e0ad6ed3a9222ae0f03be164fdcf46addcec358b06a2baa55ab41624e9f15dd228f5d2ed1ef3ead89a139b9e5ceeed1792cdc8bff5c8c22458aa0418306b02672e54093c8d217e9040de9b23b3c0c269108eaf94237ac5cf137e6ccf9164d9e6c425f38d74a4f20ffe476e4dcdc2d898e2555375b1116128e916f8fc44b7dfae1c4c9e3f0f2f1a66393e8362c3090f07233b7007f5f8c78f72df47fb90fb66cdb8a162d9e46f3e64fe3d5575ec57fffac222b9dfcc06a20a3dd93ee8bee8552327c9dc1435fc3860dabb07acdbf78f3adc188a3c40657b64b4ea6fb21410f08e48a8d2fe2d8f1e3b8762d941f5d3c8df32413420ccdc5221d48a2cecfcc83df9c397b0e91f15ab67c16c5d94277fece72329e559f879b7790b6a6b8134ad0ef02af9add1f9915f8a8e171da23a262b4350977fb9ad1b090fb787991487024cd3138c7f47aa4aec3fbac301ab99315a3c82ee6eceee9bf2c4594f9e1046916f2e06b37b1efd4355c4bf2c7f693a174ef26f8085164a1b792c89952dd79dad8abb41b42c932ff65de2f68d3a6359a367b92120d32f120b11dcbdbb818de9d8b2568bd768d9aa846963dd733b05862c97f64a54291332eb2edcd94d0b0d2766fecdb731073e6fe8c7ffe5d8693a74ec2c3d35324268c5ca62d4c6e4e589891376f6e0c1ef23ade1834101d3a74807f4010962f5b8511234662ed9a75746fee64b5cb4498b81fba210b59d1952b9747b9f2a5e8c23c3e3a2514e88f732e58f4adb4bf68d122888d8d4344e4dd550233d1b326c447233e3e1ed7afdf20bfcada166da96285b425097f67517199b37be38789b1482d18f257d2d614e94109fa5de0e693872cf5b6da5ace22d229827950e39f7345321ef0e3565838afb10408819341958586cb5bc94a05674d5b4918e250bd7a15bcf3cedbd8ba6d2766cd5f41db1f4cb0d605f5e2e5ebf8e6f7ffb0ef4a3c8a56af87dcc54a92b85f46c95225e1e9e92fb3c5b9e2181be8763a4d1bed260db15fbb5f12446ec7cef9e2ebd66e20b10a45cf9e2fc0cfc7879ed34c47717b6c7e6ef613791a2fb27ff14869469af388ad06ba4ff6254f03893c89b7c18ddbadd3fd2426c2d3db8b6d705868dab67d0792cd74ac193871e23409b317cc945092774857f3f4462c1dc75dc0962b5b126fbf3d1813bff804ff2d5b840d1b57e1f1ba75d1f7e50118f5d1078888a1c41e5fdf6882073dbb2f59ecee16334c64b57b9047f87b1ae149efc5c8cddaac9cf87043eea05c9478704342028f9a46574d95edaec3098404e155c9f41c6e066f12f16884dd8ac6cd6bb770fc54307942d64e68572a5b425b92dc8cd64792cb99b8f9e48647c536da9a22bd3c98982f1b9353538d61b18eed7d0be5cba32d65343cc29815d7ae5dd3c4ce3e62e7e04a52c5e224260b8c261284d848f4edf312c67ef8213efbf25bacd87a884ecbf8a01d1d97805ffe5e87ed1723d1a84d17b46cd30e814101741fb2a997bb812dd404d2352e2be6b6f374efacb8290849d2260ddacd962b37394b22cb96f7982d8958b77e2ddab47e16654a168587c14a93bb1804852e40c7701e803c4f73422484c475c531b4cd4abe4593b80b5ae77ed9388b3a2991ef8f453a0971f1b164915b50dc2f08cb16ff8dcb57cfc3e0e143163ddfb95158f65ca6efe7e50db339013171a1888dbf81f88470e42b1088e95f4f43d76e5d3165f234fcb660811834273682ad6d77245342c248890f777a3031800cbd2bd13f9c5827d70d1ee27e39a785fd4fdc74aa49c2f76af03090bf2489c9cddd173bb6ef25f7dc29a1511effac580fee8bc5968391f52894df36480b131291b305dd8bcbcdb9cf05c55d91a9045d7ee08e64c68fd4b36a47b879e5ac729db8448e746df8fb796b4b190737658a25d18c359b51ba3497cf5322420f124e4183573968b0356cf0a00f9fceedd0a13ddab5698db153bfc7c9e09b19127674374e9dbf8cc973fe8477a15268f96c6b54285f0696c418fa80d82af6228132213e9eac48add737ce52e67ed3c54dde11ed189a71f7aeb7426fe2ecd97368d2b4094c9e3ec20d7b57d87d7d7249aa1df26cfee5fa099c7dcf6bfc6cdc6aa0b89f3f6e9eb9801993bec4257ace64d162804497f61bc8aa8f8d4d8487c9574c9e34717ff35c13df6a4d44f71e5d51b65c192c59bc145131f1888be3d1cef41b2021e72e70459d07427b084e6b9913e2902b4f2ebcd8ab270a172a401bd37c1ac24d243c4c1e1e94c0a0eb92df6ed8b01e356a54c3eb035fc5ce7d0770f66ab8cbf823abe0efeb58b12f3ace36a2614e43d457f2cbdcf5955cc52d9921fc290bfd5e3078c293cbd3731071098e59ee7e3e192be8425c688a31bbe1dd0fdec7b3cf364772529c260d528084a9a92f8b5f164fb6066525aea0403fcc98f195182ab4eb8077b0e7d805d6c27b86ef876bddef3c780abfafda8d2e7d06a07dfb8e24de2c5009f0f6f164c393dbda912815c5d9d367288141969556964c76b0cc45a625f1a73da3dc67b3b325fa39c0b973e7101e760b2d5af0a0372c8ab49526b6ac455fe97693becd6172709b275b44c3718e6ec9fbfaf808ab3f0f09646112cac953bfc6dad59b91e0ee09b3d636dc8bdcb35a8d3874f01896fcb50cebd76f41d8ad0812ee38787b7ba0668daa98317d06ce9e3947f77d097eb9f3901fd073f0f906ba36bd17e90784e6177c6f4956330203fc30e6c3d12856a4107b99d8e76ae25ddcf5ab393141b83d77ee1c1c3d760c2ff7ef873efd7a23302810bfffbd0e89d6ac1b9df150b8f644c7e7cc327443de72aa45d17da004fd1e71f72f94a3da4646c53966b9fbfb655c532116326e7e75e596057ec5aaa248b1a288890e43b2952335a7c89d04465ff6f4f4a663642529ae04c6162337897afffd3164fde545b7d74663ffe9ab2424f716cc4348b8be98351fc743cd78fbddd12859a238894a0cbc3cb9b6b9812c450b4dc948884b40a1428571e5ca35b2accf0bd121d51759d6428d74b172295a3ada32f9454868a810dd62458b921b5c33dcf99cbb991ce14e5cd86df646ffc0005869dd878437bfbf9f2883e7815ff84d2790c25ae87e930d266cdeb805afbefa3a59d32fe3f9e75fc0a44953e859c95f096e3ac86dccb947bfd09010787806c0ac35a513a3ae093f704c5ef0af9797172c8971b09a13c8fa666bd43911623f51d242bc4303ae5dbb8acf3eff02cd9e6c8c96ad5ac0e4e94189bf96f875c1222cd978801e306b66d33a5be861d159675cf78c42f6f9d1595b53dc0b4ad0ef839cd47b5144ac6316a0730474bf84445a1058aa16bcfd8284d5e8e3ed2dcaa15321e37789d90a1397b3599344bb6a0f2357bab2a27cb9d2983d7b261a356e88d7477d8e5d47cf6927a41faef836f5c7252854be263a74ec222b9bd18f483cd07ece02a6140409bb8944cc8344ad368c460ffcf2f3af88888a455c2c09315760a3fb9595e4649f67429bd905fbe7b083b3dcb98917efe651ca58cc33322b8f7334d83a3790dfe6cb9b4f58d2f189b1f0a02b06d2fea3fbf622990761a144494c62a268233e73e62c1c3878989ec382f8b878ccf9fe670c1c3818274e9c10f76632b891407be0d0e123888a8e82d1d307f1e62424982de40794f02177b98e8095de117751cbc2ed9664a6f32871c165eb6ccddf064eecb993505f387f1e5f4efd12f1f1b1183c6410bcbc3d613147a16d87b6c89b2f1fde193b11e7ae7365caac87f3f7742b324e5bca398801b154af9cf78512f4fb24a7f42fcc5db2da939159eee1648d447be4868f1f7dcc3c5eb6c18362711e988483a7bb98a74c22c8ca89073ee1e259b63645fb69b266dd8cdc24ca82e2c58a61eedcd96844965ca7fe2330e2f3ef712b2a5e08e49de0f2f2bfb69f408f97df40cfdebd48c4b922165d99c48b7bac63756261e4df244b3c59890654285f16035eee872fbf9a21fa31f7f6cb8db8f82458e81eb9a3198b8547202377b847373a4f3c033f5f4a7e346d6571f490239e7133306e96e56e648b531e9bf2fc7c1ecf69e2757b3f493db1fb76cf4c9b0c24a0b2931729eef18916e4f5f042d30285b0f5afbf30f1c3d188b811024f931f62639370e6ec6998b9f317f26b2b0fe6624ec6e6cdbbf0de7b1fe0d6cd5b94d0b1a276cd6a983c65329a346e8ad9737ea0e73652987197e3a2d379dc4d2cdf8b2c541012cf4f2926dd3f9c9f435fe7c15e12121231e8f537317fc15f94c0f81a55aa5682d91c0b83d18a92c50be3bd31231018e48fcfbef91951e4ef590d3f5f47218b4d70cc11cbee98ca3417036229ee0ffe6214f70389794e1801886b9edb935195e2b822dca5b058e42dcc15a3b8f2950196b844245bb836bb4dec5c234542c252c1a26e81075792b324222e2116035f7f051d3b77c292d55b31e1bb3f1019cb15d7d216f513e782f1fdd24d68fc545b942e5b0ef1d161247ce45c02379bb26bd245a22ac708b72029515a535dbb7543b9f215306fdeef58b962b510603e9adb7e733b71dac00f2c8e6552dd056d48b29851b06001516b3d383898363a7da2740c9fc74ed99f6fbf9c1a9b3f72e28791c51c56125cee75cf03be30a2429e02284c098e3f4990c78e19831f66ff8833a72ec23fc05f1ccf0900b6aff99e7cbd7db167f7019c3d771146a3bbe83c863b9f49a4679d3e63260e1f3e0e2b897954040fe6c289187a6efde2025ee689fd436eb7dda5239cd0e15af3dc2d7089e2c5d1a041432426c4c348163ef79ec7efa069d3261836ec4d6cdeb90ffb4f3b0ef19b1570b6d079a8da9c020f80652af3a4b6a6b81f94a0670039618c5e3345a8f66454967b584414ae869be1e545ee255b441325cec6e65ed1b8591a4f1ce173f3ec240b5752a388dd4aebac684e0ac0a38089c89f84c39d2c5d0f9311b9c86a9b30613c962ffb1ba72e5e439fe19f63d7c193da19e432bba3713d340c8b37ecc7d011a3f058a5f2a43509f0f5f7055756f7f2b6efec468a1a8fa666f42091e3fb4c4a4485b2a5b160fe6f6850bf1e7abdd00b7d7af7c39f0b1691e0798a73b84ffa1411e3ebf23fcdc52dd07d5b69436c6c3c2a54aa4e221a888debd68b7db2563a1f4e73218a3cb11b363194e8f7c69333721b5bca2c8c668b05515151e2fade747fa6787ad6f838d4a5c444fdfc79b1fbafbf31f6cd61e8d1ba352e1c3d0e1f51639fee902e99684e14d9ef3191d1d8b57d2bb84399b2a54b61cc7ba3b066f50a54af5e15c3de1e46099c1e68dda63d366ed842579643c1f2f5edb3dff9bed86eb7a1df3f5f4f6c108bdee41f050a16124de3f89e4d5c47819bbbd13132572309ddbaf644fdfa75f1fec499387df9967672d620a38bb0b20c462f390096224350829e4198cab514830864572cce167a064540dc4ccdcdcd44d6168b1e091e8983d58d453c9ee2ea0498c9fa650bd74c629098e446960b5995b43791ac32316ca8a845cd962325004829d82a6689e05e5db81b553f6f6f18921345b6ece79f7f06abc907afbc3f0d1fce988fe3e7ae203e2151d4643f7af23cbe9ebf02751a3642be5cbe947a88851bd7584fa2c4819b8155550cb822c546c24246ff220162e2e27e6b224a14298871633f42e78eedb169eb364c9c340d71095c618f9487bf36eefa949e40cf6ee6214acd2cd87c0d1828f140563ff9b52fddf7c23ffea4fba3e7271535d339667a662b251e4892e5e4c6569ccc5eb6dd950b58186962214d266bda4ceef1d8e5c1572ec348e2ee4d0f612477bde9fe4c24eaa5bc4c6855a628fe57a6002ab927211ff9412e7783286767dfe7666b3c508c392101972f06d35639188d81dc2d903717faf77e01e7ce9cc19a35ebb177df217cf1f9547a37b2ef00f6060b09395f5f64a9737f025a34c4b7e980f65052f82d080c08423c8517f206ae3c2f2af1714e49328509ee93cfcd1a8bae9d3a88ee60474e9885584bd689de9c2b999acd59afd8e05ee081afb8329c2263c83a213e0b200611e0f2df6c0877e8618f73339b7b856b415fbc7491ac6f8aa52982d6637551e6ca65bc3c5c2af9e9ca956bf0fefb1f61fcf80958b870b1a8ac657f472ce2a20f750769e30c622eb7e6013dcc2856b42066ccf812df7ef70d62923df0de941f3166ca0f98f0cdaff8e19f2de84616350f40c239fd06b2483dbd3c498434a7d222e5722cfcbc6a45a09f37264c9c88375e7b15c197afe0bf652b849825c4c5d3bdb080b1a33c5945ed70b6f4b98c9ab3f0e3e2e3f1d9f84f70eedc791c397c882cde35e229580939ebdfe17ec4b2f3067db2834e97a248903b7ccbdcfd6b7458387cc8516e76ee411b7d49b8fd28dd668c4d803f25424ab9f9a05ebea2a853a122f207049028d33d18dce1e941091823093859c99caba0f9be48e058fedfde59c047717c71fc97d3b8010182047777772b2e058abb9542a154a0ff164a4b81424b911628a5a5408b4bb14291e2eeeeae0112423c39c97fdeec5e72178178ee2ef3e5b3dccede666f6f6f777ef366debca78b845fa1c22898df97f748508fc9c5f39771fdca4df6fd1c79ab42cd1a2f34f38f02dfd017329d1a9d5762df41ca97aee2f70a390d4a3d25fc500c07f65ba9f87000f94e54ae5c09efb46c8efd474ee0e0a96bec6ddba8e2e2fba49043a1bda32a588b27be12a41f42d0d3114a2240c904ec910463e8e924e84e5a0db66eddcac58b44411207b2b29925c90cd018072d162dfa1ddd7bf4c1818387b08309fb88111fb2bff9072a0db31979bdcffe8e1a00f4b7f4cad7097a9324879d3bb3e62913983b13dbb2a54b61e6f7b3d0b95b0f2cdff02f769fbc8ab19f8e47e1c27efcef234243f959d071625fe32d26e2d624483fd45a35fb5e2af4eedd13d5ab54c317fffb02af5ebd86a38b07efb2e67f4f6a46962a3b3bfa1eb4504cf459dfff88652b5663c4309a22d609dbb66e4628ef1aa746869a9f8bf4a9e60b91d8765a883871642d1b66cd2ab8a5fbe2f153f87a7b43cb1a151a26e65a26f2ce46055c156a38ea1ce014110367b6848584e35548289a346e04171767f69b18a061dfd181fd76b5ead581034f696bfa2c079efa368f6f5ed66830b0468e024f030270e6dc25f61de4d0afec7795449cfd675273f677d4d4e0ddf2f2c25b560c3a06dd0f01818170d46a59c380ae9de96fe8458168f2718831c0d3cb03a3467d80aa952b62fd3f7b10162f7e82b592f079325d4ffb84125d694ab5964b82f442087a3a436161293cacbd41538ccc89eff59e5abc3cdd91cbd315abd7ac953c7b59e54c5de8246e861835b6fdf32fb3a87f43fb76ed3067ce1cfcf4d33c3469d204572e31eb8bdfbe741e968bd49d4d553d95d92b5981d43d6f3448b1cc8d3a3c78701b9b366f628750a06bf777e195c39b7d2659c1cc327772e27f6da016051d8354583eb669a1e3f32a978b8e2c4a4c9c48a2c952d43a6ae0e3930b7dfaf6c50b26683bb6efe056b881edcfcf9009331f4766ffc80b9ee6b33f7dfa141bffde827a756ba371b3a6e83ba00ffaf51f00770f4fa8d41a2e90316c3fa9b2972b7c2a92dac73b47de88a157fa0cf306085b272f777d7414c242c3e0edeac1845c053d13da50f65e24bb4efaa86866411b6054c640a775e0f1dd552e2e18347810264cf8944fd1a3b8f5efb46c86b265cbf08f97903e87aea15a453e1074ddd936f6be819d1fdd337a3d3b33d9c2a6dda5f3b33c77b6b7bc98ca3188d685232828082eae2e5052fc011a3f67db49e869171e418e5d1f72902b54a820cf257ffdf63dbc0cb58d886bf1a7ee45dbb3854e0dc66c16982bb310829e015002174a2e60cf8486a7cf3c59725c7bbf4f079c38760ce3c68ec5a54b57111d6d80dee880274f0330edbb59d03abb62c6ccef50aa4c69e4c8918359ee3a383bbbb0bfe66af686c504130d56a40c68341e4f823d7bf61cdcbc798b351266a0678faecc0a34f2ae643e479b35289832704f73125cc98a4c6c318789273b3e8de6b33f63c21b05474715da776c83512347e0c7d9b3b188354c0caca1a233d0dc6f762e24eeec3094cb3c5a67c086f59bf854b5ef674ee7bd0555aa5640891225f0f8f1133ece4f9aed4073f363ad57d312ffbccc17336857127376a0a8c8280407bd66dfd919e10a273c640d909bae6a3c73d6f069838eec3c69a03aca598147af02e199370faad6aa8961438760cd9a35d8b07103162c9c8f2245299b9a74781334ef9e22c99198532389cec2cb9b4225d3b0042d6c8d6dd7b37214fb3a06fef771e74cef91f7bd919da78e1d8bfc0feedf7f882b972fa370413f68a97780fd0d1d9ffed10950f73e65dea3e692b3b3235ab66ec51a6e31b8e7ff9a0e6ef5848485cb6b1234dbc35ed194a1fa31a37241646f84a06704cce2e2c905ec08f22a3627bd049d2aefcaa58b60fa84f7b16bd77e74effa1e7e9ebb00e74e9dc3b973e771f3d61df4ecdd8b0bd1a25f7f41cb162d78d84fb2d2a9fb581a377fd32255f83497997d1477383b74e0201f87ffe083a1e8d6ad3b9c9cb46c17668d728b5c92154963c80224014de4b8f4b9d2a1b9b8102426d25f4b9625e5fdd6681c30e17fe3912f7f7e66dd7e89f51b36b31db53cf84c6434796b6bd927501778243baf4368dab831ca942dcf8e1503271737fcbd713d3e1a3396c777272b9db299c57eaf18fa4dcccec96ce1962bbd32e1e7ed11827f314902695a5f38b3c21f06bec4a997cfb0f3c12d1c7ef110b7a3c399c02b10454e594605b3d2558866c248a9559594418d7da4b7a7137c737bc28d5db7d8634b47e5d72c2c2c028181343f9d32e7d1f8b0230ae7cfc39d0c1d98d053d63707764cfa3d98c1ce4e8bfe969daffc5bd26c05de7861bf999a7da68a353af6ed3b8070768dca962d053579c4c91f47d06fc4e303b093a17801d413e3eeee065737373c79e62fef65dd84b26b668e96354eec11659ef250f956964b82f4869e20410640c90528c980bd40737ecd09094d1f41272b8b44bd4ac902f86de678d4ad591d1b366cc4679f7d8e1933be874eafe379c1070d1ec82dc312a58ae3938fc7a254e99230f26e49b39a5d863486160eb76499ddcc5f95d8b57b0fbe99329d3b4fb5608d83681de57897ac469a4fcd158aab14dbc00486ec4b93602784de90de8cdb87e4c974060618f4e14c8881b16347e19dd62d316dda0c2c5bb682899d336ba368111e1e0e8da313b3401fe3eefd07e8dca90b3f029f63cd0490baa9cf9cbd80d367cef2e97ad1ccb2963e2cc9934a04f96ad09fb06b4dce644aad066e9eee78f23a00118e809f5f21786adcf1d03f1097438310e8ed81a74c5003c3f488208bdea0679fafe782cece8c5d49f26d67d78d1d4f1275f93bb3737b1dc4fe9e59ff643593235da15c39e0cdde8b7ef410514f9e22f2f133185f8740c5d49c3551e4bfb784cf7967c736b0df38f87510b66dfd0735ab5743bdfaf578b77a5c08e084559881de57a8798f4b7048a8bcd5ba896fa15368617b83125a69cb76944b828c40087a0642490628d9803da026b13323bd2c7482449d04ac49cd7298f3e570acf9652a96cc9f811ad52a216f3e5fb478a705060d1980a5cb7ec3aa556b3178f02038318b5112b6448815087a5fb262698cfa99ff0b4cff6e364e33eb7fdc471fc3c7c707c6181db37a1d987092973dfd8d492858413e4eece11225ee5d3a1d2ee63cc398945235c641c704280af5ead5c1c25f16a141c32698f0f997dc5aff6fdf6138b0ef7dedea152cfde37726622ad4ad570f86a848764e0a2666113c884af162c5b072c52afe5164b5ca9f66b6bce51c79634642c7be27f51fb8787a216ffe7cd03b18f16eab16f869f4c798336c0c3a366d89232121f8fbd5236c78741787ef3ec02b763e01c1c1080aa15e02fa7c23b7b0292daa823ccff991194c80a9dbfbf6cdab08782959c624eaefb66c865c74ee01afa06242af7c1582f0272f10e91f006514e56b8f570db1e390273bfd264a27279cbb701e478f9dc0e80f47a35295aaec3ab14f8c15745a379d81691b7d43031d061ab56dcc3a098967a16bd875b33778422b72ec14641842d033184a36600ff32ce33bc5c5ef224c2bdc798ae1e6ac41811c4e28ea65c4b08ef550ad7259bcf75e577468d71a258a1581878b826965246f6048569a09fa7bd342b057764c0a5443ddb71aad130eed3f8c57012fd1bf5f2f54ac58966982018e0a0dd75ffa765ca0d8ceb44873c5c9f12aee88d25a5c29ae4c7fcd16de35cfcaf4ca169a4e45d3d214ec73a83b5e4bddefe3c7a177afeed8bd73073efff4530c19380ca3468ec1f66d3bd0a57307b8b83981c6f3a90b99a6b1e5f5f545fd068d70f4f8293c7f11c03e468e8d4e4e61f471f4e562319d8fd447409096c74e5993bbdfa943de9159e8a54bb1c6267bff9f3d7b708b89707e6727d42f550a85bc73e26950087c72e541997c7e70621f15c404fdf6e3a7ecdc34a0e02e7a124c6609c728c8f14e07033be7683ab2da15d7aedd84136b4879b2cfa8ca1a230d6ad6e2d7d468a0f8f6ec7b310157b06344040641171ac6ad789a92463e0834e35e6f8c64af0656f73be1f1dda7d8faf70e444744c2d3dd831f83849ecf84e0c31e7426d1928321bb360623f5aaa8f91cf9b08808b87b50847aeb276197bb7d59e89a92ad78422b41c64255822023e19190bacb05db8512939813bf8b30bda1eed61285f2a15c917c0878ee0f471a3f66caab6615b746a1808a44d75ccf63ad33d3461209668d32c172506a71e7ce03ccfb6901467e3002df4efb061a8d1194c8c5c1e00025133a72a7a2ae649242692c9cbaf3cdc592303fbe09a907206ea13259576c61a2ae60274002a651c6c045eb005f1f0f4cf9fa0bac5bf3273e19331245fd8af26ef69fe6cdc1b0610359a3228a59a05203803ecad3c31beff5ecc5be8b03366fddce0ea9461439c8b12f6f8c89665fcefc1c4de7677e8e6c5dbe500afe3d154cee9860b0f3e9dba707faf4ed89ebcc6afef7e249f6b9e1f073d1a26bf9ca70d3c7a057fd4698d26f203a54a989d7af823075ea4c5c3a7b955d53f63d350a26a424bed1ecb78a828ebdea6294b8cac47cc7d67f316ed040ac99f5037e9bf2356a9628c52e27f584b02bcace97e2df6bd8f7d350377e70100cd14c90d9791ae4460aef326796dce52b373168f048ecdabd1f152b54c6ff3e9fc01a3e343381f661d2afa786056b242868a1a04306e8f51a8484eab079cb36f61a8692256ca3872c81535c3c9f155b862269aafceac82541464235902083a1a403947cc096f16096b339e9d9e59e1864b13b396a50b1b81fcfb51d1343e3b14c6ec900e582155f6c1342e3b0d4454d9ee15b376f665650380f45eaece2c444c57c3a133fa8fc6a5a9789574c88d90ea6aedfd8c398de8bdbc7c88487329395295902bdfbf4c2e4e9d3307adc38346fd6943b8fc5ed490d0bb63f13bd12c58bf3a96cfbf6eec5aba040ded821cfef189eec25f18a3fe1279b208167d7d16840e12245f0e5c489a85ab52a1ebe78013d3b3735bb56be3973203c3c1afe4f1ec0939d5399a285b893d6e9438731fee34f70e9e2253c7ef490cf12e0d3d0d88fa2d13822863534b66cde84a70f1fa04ef5ca285fbc30f278b9f379ea1454203a2c0cd1e433c08e65242f7826c8b4cda08be219d4689e7d4c8c8659d70a44473b60ceacd9f8efc07f68ddaa25be9f310da7cf9cc38a556bf867d2f43dada33377120c0f8b647faf67c77566d7d61d7bf7fe87895f4e64d7b8180ae6b08d90aaf105dddb3d7de23c6435d43b991d725d580b42d033094a3e4049086c153727cbb1aff4ee724f0c9aaf5caa50012612dbf0fa7500136826ceec1f75f38259b1d2fc658255f034ee2a2f9268911517832866fd6dddbc113fce998bee5dbb205fde9c880c0b859393336f1670c5e3162c5fa12d0c7a2c686165f36326bad03998adf363498e62d211a9839b90fea739deb446f9c0a9fb581f110ca32e9c093dfb1b26a84c1ef97e1cfed97ad6f88842dfbebd71fad429ccff693ed45a77763826e4cc5a8f2137716967cb859d001f92e0afec857f3c3b1b39aa1a6d20cbd83b474eb4edd819af98b006454670a1cfe7e5095776f888e04026ba9128e8e58182eecea859ab064f52d3bd7b5f3469da0e63c64cc0d3a72f791858f667b872e932162dfc05658a1541913cb91013160c5574049edcbec9c53b2c2010414f9e01d16c7fd620a4fc624a8a67c0bebb8a59a4060aedcb742d20c8806fbe9a8aa3c78fe3e7b93331e283c12857a93cfaf5eb85cb97afe2f4c99370506999f0b3638646b2df5881808008d6f07b84efbf9f89af277f031757370c1dd00b6e6a69d8c4da89ef64ea459907ed00de3bc91a5a82cc41087a26c29310d868be5f8a7c664e4677b91364a5e7cae985670f1f61cfeefd4cdc944ccc291eb8344f59d2c83841a57fd45d4beb94854bcfacb8bbf71e63f237d3b973dde0a103403d991a266a345e4e22475dbd74040af422057d5122920905a5fd64aa117b6c12d7d8f5642da6f3a2478cac683a5966c9b213a0b16785d2813b98a9d54cf0293e3b2d92ce32e8efa4ce7f723223c16fd8a81e6ad5aa85050b7fc5a99347989e33616656316bb330e2ae41c22521d450a2e86b34e79ea93bb372358861c7536ba568ed9eec33f33baa9097898a313c10be1eee28e09d1395cb94c3aa75ebb076cd2afcf9e732cc9af713f2fae6e77f4fdee82f9ef9e3f6ddfb2857bc18dcb514df3e06af9895af64963bb5bd0c340eeeec82a8970178f9e8316bcc44f22b438bd6c9138f1efbe3b34fbf40c78e1d71e8f031fc3c7f1efa0fea85dc79bdd975a0e97f9fa14183faf8fe8739ece750e1757018268cff125dbbbec7872ddab7efc81a7f5b3172c430acf9e367d42c9157ea1db001e2f778b9b2eb6feba80bd7172951331921e89908753f5132025bc44943d56e1c9961a193a07b3251e9dbb9258fb416ad372238389489ad246192c84a4249f008644cf025197380a3930bce9d3b879bb7eea265cba6c89d3b17546c571207924b123c29b90b5b630247f1ea8dccea5533d1a1cfa070b726673d496953ba10f44a563a3d6a34042079f453f218a6ec6cbbd4b0e0f2cd1ddca4f332fd1d0f54c37657314bbb41c3bacc5ad7e19f7fb6f3f768e298e9b3a4f34c6c91e0978a5ea921c357d8bbf4374a0df77cd7284980553cf08eb7ab0b7ab56d8986d5ab7367482ddbcf45ab456040007c72e64685b26551b57225942e52849d17bb6e91913c529b963508087248a3b38b8a0843c8ab20b8b306815e17cda7dca9d51a9edb3c8a0918ef715138f006d4c58b67f1d5c4afb0e4f7df111e128131633f44fdfa7578573c7528188d3af8e6cdcbb3a99d397d16af0283d87771c0f1e3a771e3da0de4f6c9cb73b17ff5d9180cecd418a572b0afa6a7296bf217b772e237905d9d6c3b2784c23d1fd4c59bcb25416621043d93a16404aa8235e592ede0e56cd9e5fee47980bc160f731d89d3933431a2573b383b4461d992654c78b5d0eb15cc326562c6a770993e90899f42726d8b610b8d3187844463f192e568d7be15060e1a0c4ad0c2bbe949dda89e37abeba95b5dede88853278e63ef9ebda01ce152301ae9d809628ccb4b6c39f63ccc17126ef6ca3fcef4816ccd64edb373a17869cc8c647b4b8e70f457125257393990515a5912fceeddba61eab42958fcdb72ecda7310fa681a5290e6e2c73f0ff3f3a58f331dd7f46acea307f7919789ae329af2b6031ad690e950ad0e4ae4f08103fbd91d1cc85b5f8d086e45b2abcb449e1a0334e54ec5d4d6d9d5556a4c511703dfc381378ee852bb7979c125670e18d8be8eec33d4cece888888828b97370c2a1522d9ef347bce7cb468d28ea7d25dbd6639f6eefb071d3bb464821d0d17276752737e0de81ad6a9539ffdf62afcbb7d0f72e5ce8f86f5eba362a58a58fefb3c2c9e3a0acd2af942a37bc5cfc39678e26ff93ce572a7ec83360a7b76b4954468d7ac40087a16a029d5862727b025dc9d2cbb002f5dbf2bafc5c3646412f4ca2dc7b441d3b806777b07ebd6acc6acef67332b3d92893a933fa9bf3916cad866cac4e5a074c481837bf1f8e1530c1a3c18799975a7d747b3d3617f230b3afdb5b9b6d358f08a15abf1d75fabd887aad9fe24b209cfdfb43f2d047f351512203d623c621bbdf285849b642f6ea1b16ee92066a2ce8a94869ccf49678d110db3827bf5ea83f2e5cbe08f3ffe80ce68e041694cf023980e130fda643a6eecf13906dcb97503beee1e504447b3fdd8e7b3efeda6637b314b39925d334a974a43dd94e18e7a16a4d97974de14ead674be146f5deaded638b173526ba175f3428e7c05f8d080a3bb27f2f815628d262dc29845efe4e181687650157b3d7be12a6b8c694169675bbed304eeeeac71a68a628d2afa4da901c43e8e5a07ecfbe6cf9f177ef9f3e3ec99f36cbb0295ab54c3cbc05778f58cdd8f9141fcfc6c912b37efc96b1239dc6c57d029419583a3bb5c12642642d0b3084dc996f29a6de0ac55c2c38de2a7c7719789a505e6aa6142aa8de542eaa958ba307ab56f845973e762e68c99088f648242412a9885481f4b1f430e5f6425720f70e8b074e972f8152a88ca15abe2c50b7ff63e5308b61fdf3f76210b96599d0a255e3c7f8967cf9ea378b1e26c1b75dd93d4d25e9690f69a1613b42fdb126f216827d3baf9abf971cdf735873e84e91813580ad042c2eee8a4c198b16370e6f469acfc6b25c2c399052f5bc6a6ebcc0df558cc8f69b64efbd0fe4c24035f3c87b7a323e81b53da581e3c8659d18e5a47a8d58ed0b13fa32962a6da822754a163b1eb4673c87524e46c9b49d02956be913bed49330c486275e4dcc73e8b7c2072f9fac2c98bac762502a3a2f0e0d1231429528489b52fed091777477668ca534f53f8287c2cfb8de93765a74be754b16225dcbc7903af035ea15cd972080b0be1f3e46d95dbf71fcb6b12f49cb9c56b40db0a0a8ffc3c4195206b10829e45e86eee92d76c8742f92c7b15aedf7928af7175905e1283d4361de8deae31e6ff380d7b0f1c44d777dfc59a751b70ede65d1899351ea3d4b245c30c6b47e899186ffc7b2b8e1e3d8a4103fac2d3d30d2e2e6e4c109d11a372458cda8d59906e70606585ca912d2eec3d57f83f7f8d27cf5e220fb300a91b5ca92641270b942d7cde3b2dec4468917b224c5f995bd916c865b22cf962fa437ae498d8f179eab468d8c21a26b4f019e224abb48f74441adba7ae6dfa200ad6678c0e47a3860dd0a163077c32610266ccfc1ea1e1d14c7f75dc82e66151d9f5e60b7d2c3b86c5e79b4e98afb203b2c642c8eb10685d3d11c2945bcbae9d521dcd768f60efe9a1363ae3ecf3c7b8ffe8219c9d553038d120014588a3736430518e513ad3aeb87cf102df54b46429449143a1460b070dfb7e0ed1d0505b8b9d1bf5926872e7c26bf6659c58c369c7dec3dcf1ef938f87c32b871bfb8e74ee0e7c51b3c605a54aa500402ad64050d2393381278ff727ac8116ae0b4701bf82d0b286dcb367cff8675b27893c18669be29e2389f8cf992d617cfd0886e757e59220b3919f4a4166127d7d3b8c21d65c01258e5fde9cf29a446c454495132da419298467e68a8ce28e526f823b93b1bbf5c1fdbbdc223b72f408c67ef409060d19883e7d7ba34fbf7ee8d7bf1f7f1d3c6408e6ce9b8357af42f0d75fcb3068d0000c1e3c180306f447df7e7dd942fbf5657fd71ffdfaf6435fb6f4ead50b93bffe0677efde81964488c99694e39bc6b925eb907f3fde38315fcc496a7b62982e5a628b197c93a9a780891b2b2b9938f7ecf91eb34ecb60c58a55ace1720c5a17573e44c0ad75d339721127d81f99564daf9c18663d33cb9a892c75a353a63703b3cc499d955a15a298828633413d7bf33a5e8685c13b8717ab30486c0d6c910e4411ebe873a3750eb872f51af2e6f442d11225d8df3b2292de63c7a594375106238c2a35744c7c43743ab8172d8a20669dffb57215ea37a8832a55abb053270740668d933362ec7590fb3ed876ba57a8f7c5c3c383bd1a594324146aad9695dd10181a69dd33482cae3b831a8432f1053dfe73666b445d5c8f9848dbed31b165d873293f995682e4ad6b89959d629a30bcbc81a8337fca25db62d28ad3f87ee93f720918d1bb03e67f33867e205662bf5bc29f4edac6de7e1c108c1b8f5ff1ca9aa731d5ebb877f3eb9030665d32ab8d5969ee6e4e7c5a57b48ed2661a51bc503e942fe40393837d78a40e9b0e5cc0d153e710ca443d4a17cd2c6b350c7a23efcea5f3a02edae8e828ee49ae6216224dcda2f4a42408d46dcdad52bac7d8be349e4eddf094ad8bbe8286ed7feed215346bde0c53a67cc31a0e81f07475e2e3f2a6af16ab912698252a7f7bf9bf7898f64fecbdb7c0ef7b8a4b4b076196333f0b26d84cd7d88b0a77efddc7cf3fcdc7e143473173c65454a95685cf46d0a8d97edc639e1a2384e9c3d977e7eb52991f9e7d678a44573020049fb5690b3505ae61121ce6a8815eadc1c99bf73161d94af8eba23073de4c0c193614fac8301e598f7a2ef4742d1d9c71e2f8490c1d361cfdfaf4c4e811c3a08c8c407450008cecb7080d0ee759d55cdc5cd935572042adc69163c73177fe2f50b9b963c91fbfc2af603ee82242d9ef41bf0b355ce47395c70fb8377c243534dcb171e3260c1c380cc58a1461f78c2b6edfb905370f77942b5514eff7ea003f5f1f9e392f9a3518e8fbf3e979b1d016b91cfbc2fef1efc10ad205e35323a53e92b8bfa5fb567ae52f099036b3ffe5f79d9db42851385fdc1f98ce838a7c555a19febf59f865c516dac0f9a8cf3bf8a67735b9649b9097bb63ad6172c93eb146ad12829e89c4448722e2d05c6601316bc2065971e81e867cb35c2e014dea54c69e153fd20fc44a52e5f426f44a6769de746c0547ebd26f4e1628af40e93d5699c7b06d14ff5b15c32c46f3df5f1e9b8d7d6542cd0f44fbf0bf95d7f981e93d5a6715310943ecbd65fa60695753c2133d13ad7fff3b841f96acc586bfff6602cfc4918632a9ab9dce4f3eaee928d27148d0e56d716fa419fa24ea76261b5a42ba263cbfb8917d10fb6e9451ecd9d3a7e83760089e3d7e827efdfb62e407efc351450d1bc96b9e4e4a9ade66824e523a517aae145a27cc5db0007f4d9a86010d1aa046e992c8e993032f0dd1b878fb0e7e5bbf05975e316b8b89d386bfd7a04eedea5052185776080ae31aa354b3337345fffe0311c01a034b962c86879b3334e4951e110623b3f67f9cb5809f43f73ebd71e7ce4d2cfc6d3176ec3980c2458be09745bfa06aa50aecbbead977d3f173265f8118eedcc63e84ba0ee87459d9605442a971c382858b58836b3a3ab56fcfaebe018ecc4ad7b1571ae7cf9f2f3f9f2617cd043d3438941dd3c8ae056bccd1a1f8cf478d1cb9c05eb898b3cd0af6997c1a1dbdcfdee20d44ba6de81fff43fa13e9956fe5ebd410900e45622fbdc6edeb9bcb03433a3790762062ef3f19e91068dc632cf61d3b276d63ccfbac270636292a976c1775e10650176f2697ec0f21e8c9c09e053df2c46218831ec825dbe3e49d20341a314f2e01f9f2e4c4a3a36bd85ac2df2c79c4ff5d4dc7316d4fc971dff437f41e6d7fd37dc4848fdd6794aeb4cf2733d0adef2074ecd00ed191c1bc5d4096bc5117cd2c7f5689f3fb513a66dc5c70f67f4a4ef72d48b73c1dd024e8ecb3d807d03f6a1491056bd0319163e5ddff1d40f7eebdb9b5fac5ff3e63a23e1c46034d77d371873a85429ad34ccf168f46c75b5512e4bcf6e0c90b4c1cfd110eeede032f8d068e4e4e08629675706414fffb08472d8a97298e157f2e855f3e1f1e479f46230c6c79f0e819a64f9b831d3b76e1c7d93fa0f3bb1d1045615c1d62f8dc790a38f3e1a8cff0e7da0df062421b1c1ece03c574ebd60dddbb7547d12245114363f5dc4f8006000cecdc7572e43803545a17762d8cec98616c9b23546a47bc3ff223dcb8761d2b7e9d0d5743205d19a6fb7c7e1d1764dec85350438b1a74742de52e7cba9ca6df8e5e637f47f6165ba77f7c9dd64c7f23fd101ca9b3806fe5afa677a43211f777742cca7fe0482d42b36324866fcd77f1d46c1ae8be05a350bd88a75cb26d1c6b0cb2cbe032d6aa5342d03309dd9dfdd0ddda23976c93b02803f2749ccec72f4d445cfd97c7264f16a402666262ad9cbdfe085fce598e112347a0458b463cd88a521e4f27a1e2a2c1fbded936726ce355b8544c2ff8d1d97f46079a672e7f1c877d16290b959970d1d04470a40ec3868ec09edd7b7970984e9ddaa367afaea854a134b4cc7a5539b0df87448ec6bed9f7e05f851f8089323b96015a048786e2cae52b3879fe1c6e3d78807c3973c3c7cd8b879abdffe2093e1a371a1f8ff9101a872872e7e3c7a3c6cc8ddbf731e59be968dbae1ddab47b873be569b5340c42b9e049d4d4e8c91a1b91d13abcdbad0b8a96288ab2654ac0dbdb870936e56763df88b50c480229ca1f9fa8c0ad7126e66a0d5ebc08c0c9132751a3766db8b97ae0f9cb40346dda0223870fc1873d5b42a1b38d7ce709907f83d0d048b8956bcd3711d49be0bf69029c355263c4d6a1605a4e753f60e6ba7d857fb5569db28fbbc6ca21cf4f5b1773c245ab44713f9a5a14c73556a173c88c7ddbfd6cd610b0662a972a88e2f973e18b2f26e1e89193d0685d59454b016da85b951e19faae24aaf450cb0f76c2e73b5d30efe08f85571c6ca1ebe960848bab231a366a809c3973a276edbaf87bd3360c1f3e1a6bd66c4404137b07959a8f23534e78cba331c1652d0595520f6767356ad5adcd1a31ef63f294c9f870dc47e8d6e33d78f9e48452ad40c9e2c5a026b1a53f936b0df23d28e25708bf2cfe858bb99659a361a12178f4f02113720d9c5c3cf09859fff759b92d7bbfff80bea853b306dc5d9da08b0e6595228d9503af828270ecc8318447464a0e71f2bd44af572f5dc6bcb9f371f1c255689ddc71e6cc05bc0e0e41ad6a95a03092bb9db593c8ef475f9aff860eecf9b1ecb1a3e7cb5ec49ca061c6c8f3d48b27c80cece7ceb156f491883ab74a2ed83e154b1690d7244e5cb826ad70ab555a4d92b709beb5c0becbf861dd50a35259346ed21233bf9b89bbf71fb2368b86598ecc9674e033b6d98e6663d3b1e29e1e8b25920c93156bea0990849cbae3696abdc11885faf5eb225f015f74e9da9559ea7bd0b861137c31693adab5e984a9df4cc1c54b97a0a0b8f04c48a88785c6a8694a1e4d4273880981b32a1a6a5d301c2343919309694c783022a25fb3af68404e2f2f14cc9f0fc6a808a9ddc68ea167c24b19cf14bc87828ea5c7ee3d3bd1bc5913346ddc0abd7bf66516fd780ce83f0c41af0250bd7a0576ca3a76c5a2c0da85d0288c30eaa2d8a536f04c782d5bb767af5bf8775530cb9c3e88acfc42458ab0b374c0a3c72fb06ad51a4c9a34191d3b7640edca65d8c746d1d5b072e49bdef4b3929853434cee6539697a7e64e23f5ff68031f00ef4f78fc825414622043d8389bab0163151f63385a374a1dcf29ac4dea371ce3c126f526d53ad66fde4cee18ed95f8cc0e03edd3177decf18347020766cdf8980c0102eeab156ba8c69fc357d8995f0789018d0765ad8271bf42856a410f2f8f8e0f1bd9b289f5b8f791387e3e7995f2238341cf37e59822eeff6c4cc193fe0caf53b78e81f88083dab68c9a1cf41cdbe0f7ba579f00e1a7644151cf44638abd5f0747343d9b22551b67449e4c9ed03854acb1a040ae8d8f727af75035d0326500e6cdb95cb37f1d5575371f5fa1378797be3c2a5abd8ba6333fc9f3f44b45e87cb172fe3e183476c7f4a91aae40d020a18c3649d3be1393a69f1e265002b01911111ecf29257bf025a67179e198fa6e67d3b652a222323f0d1a81140f013ba10d68ff9cf4756392da69f8e11fff9295b38afbc665f445fdf016348bc40548274478ca16720fa8727107d75ab5cb20f0e5f0f408bd1f3e512e0ede98e80b39be492fd1114128e43e76fe2285bf61c3acebeaf17a67ff3158a172dc2c78a29f527d5cefcae4d78eba61272ae9257df04db27c6418f281ed2568b91ef7f88e2f9f363c290b6fc99d131617ee0ff0a571f0660fff1f3d8b0692bdb4f01777777142b5a18f998d5ededed051f1f5f3e654f9ac207848787e2d5ab40bc0e0ec6d3274f51a8703e942b5701050a148497973b544a151fe37efad41f57ae5ce5c9528e1f3f817bf7ee232c3c12633efc00fdfbf6812ef831b3c41d70ecc469fcb17a13744cf85bbdd31ceddbb743be7c79e0e1e1ce3ed795279b193268283e1bff29c68c19c3c7e1c3c2c2f0987df6ee5d3bf1ebaf4bf088adb769fd0e3ab76f83b6754b43a30b91ae818de355b11d8282e3fc0076ce7d1f754be6904bf68583a3279cea8e0228c2a38d239ce29289bd087a4ce873441c5bc8cc20c9a9c95e888836a240d7ef7930181357772f45a9a2f172bdd3cfc87f37f9f7345bb525a87b9ae70f6727ff2a5a898e03c6e2c71f7f40952a5560888a947ccdd8f7e25f2d1dbf1fbf5cecbfd84e00b61efff0b40fed14a98b66975a81912346a342c962f8a88f145638eedc194a0dfe397619a3c67f8d172f02116a967f9b9e39fe8cb1851f53dec6a1ed6c955e346a059c9d9df9310d063dc29925ada3b8b00c8a23a070600d02d660c8eb9b1b4b17ce42bd125efc3d22325a8f4d072fe097e56b71f4d819e4cf9f0bc58b9742f1124571e7d67decdaf51f5ab56c05df7cbebc3171e9d265dcba7387a7ba2d57a11cc68d1b85cead9a02af1f3273cf461de1e241f910cab71a289728acad068fd77f0247769ded15659ef2d056e82a976c176bd529fbbd73b21226e291e756d89d98134e1a05130dcb69287b8f9e95d7cce0f7b67cd3d3cd9ff0feb70948bce841a5e948eeca28f4ebd202aeeeae5cd0b8d0c9df2bbd1f657e58f9b2f1c5b422237d1e3b2fb64dcdaceb67cffce1fffc39dc3ddcf83b8449cc7945638846b34a853076503714c8efcbde736002a2e642ecece8c8b39ab950f7b6a3139cb44e6c1b2b3bb9b2326d7764fbaa7825161212c653d852838004dc740c3a91689d9e5d97183c7f1e80f98b97e1dccd2788d61bf8e7d3f4adee4dabe28fef3ec3f80ffaa142997208791d8c3dbb0fe0daf59b4ce0f3e3eab5ab3878e8106eddbe8ddcb97dd0fbbd6e9831f56b4c9bf829dad62a03bcb86637624eec3d66f9dc542c55c8aec59c303cbb08fd9344ea0b41ba202cf40c20faca26e81f9d964bf6c7f8a5c7316fc54eb904746ddd086b7e9e2497ec17ba0f0f9dbb8982156aa3408102acc14673c44df3c465d1cd244c8f849eadc4285458bb662dbe9f311b33befe1c2d2a17e4e79af0598a4144941e379e8560da4fcbb08359c56161e14c98c9158da2e5d1d4b1183eb64daf4a0adc437f1523354ce933e99064c79325ae522aa1d7ebe59e0066a1b3fd691f6a2c5092977cf9726268efce18d9b32d8f60673a2712fd3066b147181488326aa023273d760634754da371647fab82c6c10017a58e350428d80b0df8db5fe3b8f3f089d8f8ef41b9048cead902d3fbd95e6ae514a350c3a9cefb7070b6dda10561a12793c42e4a6217cf5aa1c404f62ce644fdf296ddeb7b8e9c91d7ec1bba0f5d9cb44c7698be70f96122c4848deeceacb943a9ab9cace6702c5db60c513a03fc0ae697de49f4997160d6b71a15fdbc99a5fc317e9e39198d1bd683b393a3f40598b053701a0a8822854b7560624dbd13d2e750995e49fcc922a7697c5485d067f1c6008fb54ee24cf3cf1578f2d41f1b761c827f18db8dfe5a3e27ea3870775223b7ab1205dd0d28ea1983629e46fe5ac03902beda10e4d484c38909ba0305c8b1433127761e3c29af49c47faeec16a30e916757ca05417a22badcd3114a48408909ec9dfaa57341ab89736c090c0ac6e51b77e5927de3eaa4415424c5085773a7332e72ec31325fa4c72a33166a502871f3d66d5cba7413a54a16464ed7e4391c392af478b76179ccfee27dfc3c63223efd7028dab76d857cf9f3f2e0336a0d75b1b36f4391f1b80ed3774d6ae1d2cff7a732ed1ead8b62e2ceb653149b784e50b6d440cf28ce5ebec91d084d68d8f344cf55762126ec39a2afc5e585b02512bb7fadc13a27a85610a41351e758abd3f0e6ac61f680bb930ab52a96904b1209a7afd92734d1eabfddbbf1e4094dc191ba9163a155b6d0268b457a37dda171fd686615fff6fb52b8ba3963f8d021f0764966d43e0605822953c8073d5ad6c497c3ba60c9f48fb165e98f1839ac2fef32274b9abae3e91b502596a81073d167fba9d8feec6ff8df2949dc8132658b63f0c05ec8e39efc73ca2e98c76e276ab3e7899eabec84fec1319eac4a907e08414f2728bfb931f8b15cb27f9ad72829af49c477f0b14748bc3ddd5c70f4f061ac5cc11a6f3cc08c247424dc714822282d712fec0fd271610faf5289bbb7ef60ff818398f2cd14f69b9483833ece7b3dd9b093a7dce3ae4c770be570c4771ff5c5f793c6a154a9a2cc7264963a4dcda33ce524dcb10b09376bd230d5777474828b8b0bdcd8b571f770858787172a562a8f5933a7a277db467074b0ff466e4af92f9e2369fce729bb1075611d8f2627481facce298eb0e62e8dc4a0484891a7fe904bd9838b0f43506bf06cb904b8ba38e1c5e9bff9d41b7b46a7d3e3d7d5db31f7f70df86cc278b46edb8459c7ae4c629975cac48d22b72146cf4a143d8d3ae02947378d33d35f27bcaf530b3d0f0eecc3ce9dbb881ebd06e287efbf43ebaa7e7048a7d8e6349c70fb6920139ef3d8b1f708ee3e7c8a9714f88502c2a814707375439e3c7950a6547154619638c5237062a24e8e742e2ecec8e3ed8a42793ca036da4234b7cc252c3c0239ab744464545c43e7dcb28f513cb7935cca5e283c0bc2b1c660b964fd58b33e09414f2bba7084534a54f69add28d57f211e3e7d219780bf66ff0f3d3bd86fba4413d10660f24fabf0cbd255e8d6bd1d1a35698c86f5eb83868bbd73e660b2ad87212a84ddc74ab6cd99ddd092e35c7a428f0339a1451834e8d7af3f9e3d7e80a5f3a6a0446e17798ff481b2cf451b1d100d2d6e3f09c013ff17cc2277842f13f34279bda13246b2258a7d3f7642ecb9e5cf2e7f56adf479b502fe58b703033ef94e2e0185f2e7c6e5df86caa5ec89ba6813b634924bd68d35eb93e8724f233cf140361473a255ddf2f29ac4f28dbbe435fb46c3acf08f0674c49821bd70e1ec457c3b653aba76e9898fc77d8ee057afa1678a4fc95c1c1c32764c94e6993b6962d0be7d1b9cbd70057f6dda051dd2370a174d4173523bc0431d8d2a7e6e685ba3089a96cf8b323e0a381b82a089898c1d67e7b9c6f9543ec9514e9038cb36c44df9249ad72a2baf655f74b7ffe349ac04694358e8694077ef307437fe954bd98ff8616009ff931be093332e4298fd1283687d0c9ebc08c2f3101d02c3a2e1ea9903d56bd5640f95112aa549e4d8bd4c966b3a5baca6c7c1e0a044c0eb5074ecd005af0202b17cde34d428999b3f2f893d4782ace589ff4be4ab6519296de7bc0f50d72caa5e76c541eb2ea55a55d13095f5222cf474c0da2a274a34909dc59ca8532207f2fa78cb2589ec62a5b33b121a9582773bd728911b2d2be547cda239582b8fe66153e43429b9484688b93914f7dcd9c511ad5ab5e06945771e3826bf23b04696aeb7ac3372b3c66f9d1296cf507685925851322b41eab14a41b7466bdc024314a2446004d22ab46d50492e49fcbe76bbbc967dd0eb0d387cfa12264c9d8741438661c2f80908090ba7195dec66a6fb991aa3e9bcf0062e4d2d5342abd5a27af5eac89b37379c9c24c72a619d5b27bfaeda26af49b46f5499fd8a565edf6522869737a17f785c2e591fd6de7b2cc6d05341d4c50d88890c924bd99b76b58acb6b12576edec385abb7e592fd430f735474342edc7d062fdf42a859ab068a172b0c25b9bbf3e75c125e9adec623ae5199045e5ecca3b0a564a17ff4ca5390b20645a9e22599b5978bc764175827a72e5ce7b305cce9dab08cbc2630117d751b4f6e25483942d05388fef1191ede5520d1a49c0ffcf2f9c82589651b2d9d7eec196ab13b320bb947877730b26f170ce9d40803dbd58513289a5cdc3e0e5cb41964b14b2fb1efa7069351c0c7ca8d317caa985aa5c6ad3b0f317ff9dfd8b4eb30aedd7e20ed24b00ae23bc315f4f5411d31769e283cb99541279704c945087a0a88090fb0bbfce669857aa0fab6a92d9724b2cf38ba04c52df7d2e8e189d770442482035f62d3c68d088f88028f6ceec0fe57b0c5c1c02e9881d9d5a648f0e6481677f217099a174ef1d6035fbc42f0eb603c7b19889113e7a0e3d02f50ba593f78576883d6fd3fc5d77397e2bf6c1273df5a591eafa1dbaf6d6db35f52604e4c7820a2af590e4f08de8e10f414c0130ad869a288b4d0ab5171a82985a6ccf397aff0ef01cbc413d9852dbb8fa2d3b089f8f6c75f11121a069dc1009d510703656553d0942e798931b285ff49ea90c7f214ac31a154ab71e5da55bc6682deaa6e59ec9cd90f6bbf7a0f9ff5a88fe2f9bdb17dff494cfaf10f34ed350e6599c8c777cc12643c5b761f4150705cd01f954a89de8d2dc3270b2c11bda129c72aa7ad99b02607041ad7b166678daca6c7f41dd8bc374ec43bb6a8878dbf7c2397b2071b771cc2e2f57b51b97279b46bdd14552a964594838a59e78056ad8091a6b3b17b5a49f3d36328a49c820fa51389dcea6fc4f41850fad480a010b46fd309d19151f87dcafbc86db09ccf1b1c1e858317ee61c3c12bd879f216df963fb737c60ded813103dfe56541c6d261c8ffb09989ba89f64d6a60e5672de5922049941a38d5f9000e4e9ef286ac232947536b925021e8c9801208449df9532e091263db99a7e83661b15c92b8b8e377942b59582ed93ffe01c150bae680a7931aaa982844e97458b9f3183c72fba25ddb7728601c620c3a28f9b436bab795a91774f93feac03f7cec14da3241efd1a53da60e6e086dd863e939610737b257dad76888814e6fc0bd674158faef59fcb5fb3c4223a251b4401e2cf8761c9ad7af46471464009489b05ccb81724962cdb4c1685325af5c12bc09855b1e38d67e5f2e651dd664602685e8727f0b3c25ea8575724990142d2be541c9c2522e6e13d316fc25af650f72e770474ead0e2a6338628c7a9e6675ff81233871fc141c945a26ae0eac5220cbdc1c2eb7f26b0a16eab2677f6734ea78ea4daa6cdcdcdce0c4d6554a05d42a25d46a05b41a251cd9e2eca482879b16158ae5c6f4a1cd71f1f75118dfb3011efbbf448bbe9fa0f7d86ff9508920fd993adff23928c19e137a5e04c9c318f20cba1bd9c7d1362d08417f0b3c25aa3e2e6fb12071540a077cd0ad815c9258b1690f6edfcf3e19e8cc218175777546c94205a05269d91615b3ce8d71deeedc1dca24e4443cc17eeb42906b1de0e5e5c943b43e79f204bae8376736232343c304dec7db195ff46d8893bf0c479b5a25f0d7dfbb51b2691f1ec94c907ed0fd4fcf8139a3bb35e4cf8b20f9e8ee1de249b0046f4608fa1be0f185b3514ad4b4d2a35e617879b8c92589ef16ae92d7b22331f060a2eec0c4dc813d6a0a057b4dd06d47657a0c53b3d06c743a2e59fd0e3cc08dd1a4f5c9804ea588af17d64cee8ecd537ba17ca15c40685cb21d41da99bec03200956fee1c78af5e21b9244809d9396f4672a15ac16a496c7c22b1718c8cc018749f09fa3eb924480e4e1a053ee8de442e49fcba6a6bb6b6fa7278baf1716c42c1bbdb2521e661e4f8ad9cb6fbd9c18152b64a330ca299754ed9d15243d3aa45b063661f68c31fe2d0de9d581e6fceb420e53c7d1e80c5ab2da75ebddfb5117f4e04a980893917f52c20b37427ad883b2b31f491883ab75a2e08924f0c86bf53066ecc2a3567c62fd9d74a0f0d0f477454246b9cd278b7496c49cce33756a9c248c1c22b18395e9c3c2eafd568e0ec4cddfba9c749abc2d2f53bd177dc342cf87393bc55901aa6cd5f21af49b8ba3861405331552d2d50b7bbeeee21b994b558a33fb910f444a0040131d1717346056f476ac03ac0d35985313d9bf36d2616addc8a1701d93354ae46ad465070305b8be1d3d6629890f36a80ff973a6b9ac32b13a94231c6e87870194767479e933dad7cd2a32e727938e3fd2f6763f3eec3f256414a7819f81af3966e904b1223ba36e1cf87206de86eee8431f8895c129863d5d3d688c4ba3a32f69459851bf15a5ecf7a1edeb98926cd5b426f3638daaf6e0114ca695d31bb9d9cb43cae38111da3c4a825c72d02694c78bf17a67e32582e651fce5db9854f672dc7baf5ab98054ddde3fca1930d74bab725eb3aa53d7aa64780ee8b3b0f1ea1468dfa68d5a42e7effb41314a19695ddee53b771e6e61314cced8926558ac0c7d3457e27694e5f7f8206a37fe3ebc737ce478d4aa5f9ba20798cff6e11be5b18377eeee1e6829f06d684c62161232e3c2222f6f7b406eebd0cc7d2c30fe59274976ef8eb7754aad358da60053828d5acb5fcf6fb38bdc87c1d4a1d3629e884959f76ba52a564019cbd11172ca466112ff4ac653945ccdab818ea8ec59be3ac3bea867f7c746d82ee787bc7ff45203a7d3005eb37ae8587bb2b28a09e03537305f776a7456a04a556d0a30d065cbe76038d1ab542e3ba35b062e27b4cd02547ce4317efa3f794f5781114c6cb84b79b13967fd1058d2abd3d3ec0860357d0e7dbf5c893cb1b37fe5b9eed7ebbd4420dd90275ba21342c42de020c6e5f17e55da9a7c6faf9ebd8239cb8133785b1681e4fdc7a1298f29bd48eb01541b7fa2ef7ec24dc49d1e69d56f29ac4b907afa14ba5f3536651c62d029e4cc04c84848663f2dca57229fb40e94cf5515178fae4995c29982dac4ce24e0bbbd353b55056b78080577c7c5ea952f1adc41d5601b7fc7819540e0afc30f01d6cfcbc17a6f46e0e57470dba7cb90a57eebddd9bbd738332f8a0534d3c638d9209337f95b70adec657b3ffb01073b2cecbbadb867736d52b54bf98d3a679237eaf66576c45cc0931866e030c1c31864b808928bd11e71f5a776b5f19a3c37b4d2d73a5fff0eb1a5cbc96bde692babb3af1f4aa172f5e824aa5e6f1601c78d857f68b9295ce75394ea053ba5065f3e4c9631898a5ae30bb4bbe59b60f5ab51253fbb640e9023ef07071428b2a25b068d4bb888cd6e3dbe5fbe53ddfcc57031a235f4e77fcbcec6f9efe53f0662e5dbf8b394bd6cb2589ee4d2a416123392048cca359fd6282eea821233f940a02ab4708ba0d50b864599429641959eaa4599798b552d239147ebe96a955077e36435ecb1e1c3b7b8587febc71fd060cb19eee544dc6896facb8c76e4fde42ed009a12f7f8d1137edc685d34db4cefb18af9d63314c8e981dc9eaed0a8947c3e3c59f3b93c5c5081dd4b072fdce7fbbd0d27ad1a7346bfc3d787fd6f167f15244dff4fa6cb6b1205f3e6426957db71b03d79d7d279b5443e2f94abc92c74814d60b3829ed4d8babd3270405f794de2dab35084445a77ab5f1163408f8696ce5464e52d5e957dd2227e3afd17aeb1278e1dc39d9b77a0d1500858a6c4dc3a9792a84a89542d17d336d3fbf1173a281d2632da88b367cfb3631951bb761da8c8eb8ea1635656509814e1d08d59e7e684464623a747f2c7c3dfa95902edea94c4994b37b0e7b048c19a14bfacd882d3176fc825899e8dcab0e7c0ba87c74cbc0ed7e13aab57cce9d2beb5bc26b0056c42d0c5383a30f2d3afe0eaa8964b12e68e2bd64a5e8740542f574c2e497c326d219fd663efd0f0c2c11317d08609a2a72a025bb66c85838aa62d314926bd2661e696396da1fb3c6ea10da6d7a4163a4668c86bdcbbf7107a430c6a55ad084487b03781da650be0657038b69cb81a2bf2c4a12bf770e759209a544959d29c09bda4b0be245a828404bc7acd3ddbcda95ab628bfff6d8593f72cad738dd201a3c74f964bd9135b331c4597bb8da0757442fba675e492c489bbb6914ca37d65cbac52e4054c96abbdb370c566fe3aa05565d429950fab57ae86ff537f28546adefdae8f31303d27ebcd5ca5e97f69cebac148ef3bb07dc11723552eac41c073acb3c541e58c1bd7aee3fefd07a85aa50a4a14c8859828c9c21adbb50e9cb56a2cd87e0233d7efc76126e44b769dc287bf6ce6d6f984de9671f7df46c5627950ab4c7eacfd675fb6688ca5948fa6ccb798a649bc5bbd80bc661bc437101a572b85dc058bca2581096b363085a0db10e3267c49f57d2ccf5e47e161609c37adb5e26e0842fb8655e492c492b5db71e4f425b9649f2cdff02fcaf8e542d592f9d0bc5a51a88d11f8e8e3cff13a240c514cac151a050cd0c3a8304a0b13f7684314a2f551d01b7550b2f729df7994c18818a50a81af83f18a2d600d02a34281474fef63d69cb9c89b372ffe5ebb0639a36e31e59792b39429940b9ba6f644b9c23e5875e03c463321ff69eb119e88e5c4c261c8e19ef22968233ad6e0afbfadf987bf0a248e9eb98c65f142e576685c15ced10172c9faa17ac43f384a2e498c1c35465e13d80a362de8d96d1cbd4adda6285f38b75c92c8c86e7727776ff855aa8fb28dbba06ca3cef0ab580f5a1777f9dd94d1b080038f6b6ecec04fedd7416ec7fe1308098b44bb3aa578d9d34583c9fd9be2fac5d33875f21477508bd6e9d83dcc1e41793c9dcc7307289998b375b69de2b2d33dae5439c0c81a008b172fc6d2a5cba060e21e15a5c7ba751b71e6cc79346ed0001ec1e7617c1d17ab80a853ae208e33f1beb5620cf6fcd81f4f377cca13b1e4f68e9b4e9812de6d58963704566fdd2b6f1110033ef94e5e93a0fbbc4921db8a0817bf1e29ece38636ef59e67017583f3623e8621c5d6260bf3ef29ac4d1db81088f36c8a5f4c1d923071af4fe14fde6ec40cdd193e1d763000af51c883a63a660c8827da8fdde877072f392f74e1e2a633406b4ac2c9724aedf7988cf672e964bf6c5faed07f86b8bea71fe03e50be5c0d4418df1e38cef70feec394444448172a44b8e6e0a1efd6ac7bfbb30e6c3b1b87fff317b8f3d9e142a96ddfb74fbefd973009b366d879efddeebd76dc20f3ffc0c95ca09237ab686e2e515f953129237871b6a9529007797b7c779df75f2168e5fb16c1898d3a852219cbd7c138141b6112425a3f96cfa2ffc3e3687dfe751b693158cea8fe3f104bd4f97b6bce1989d49cc60b4761d125dee36c6fb9f4d8637b3f64ce80c31d8772dfdb299e5f22b859ed3d64151b7061a9ffb00658ff746c5538350812d658ff546eb736390b7595bbcf7ed6a78f9a6ccb1aa80f2156a572a299724a6cdffcb2e3da7f71c3e095f26a425f2e794b7008e8e2ad428911b95f26af0e5975fe3c0fe43acd274649504b3c069909c3d8ec78f1dc7f61dfbf1f021135526e87a839e59ea64c993b51e0315ab64838242b064c932f61a8c9fe7fc8842ea67b15ded6981c4bcc7376bf1f3c6e3f2968434a828a5fedc75e8347fcdceec3d7a3641e2a15a154af0fb3c3514a9da1855daf4cb74213d70fd258f6d61c259a3c487ff9b229704b68410741b43ede88cde9d2da792d00319a94bbb959ea7580574fc623166faaf45dd93c37124e81a022343111e15c1971791aff16fc059543cda179b224ea3f317bfc1338f9ffcd7c9a363792f383b595a8aef8dfe9a4723b3175e0787e2eea3e7a85ac257de22e1a851f1602fef77ae85faa57cb063f366fcf0fd8f3876ec2c1e3f0b6416bb8e4f43d368b57076f1647fa186c241cd445c83f0303dc2c3a3f0f4e9330c193a1cc1c12158bee47734f18b8621c8d2424c2dbf6c3905a542814f7ad493b724a44145e9f7defadf51fe9a5da190be5d477e259724e8beee54c95b2ea59c46fd3f47bd9ee3d072a4e55cf68c248ad51bf10d82e635cbc23b5f11b924b0256c4ad013ebeec86ee3e8c4ffa6cee2ad6813113a230edd489b038e52a546c3bee3b1fdd5097c73fb4fd640d02387c25d72af8e5dd88e6c098c0ac5904bdfe1aae139da8c9b0ba53aaec7e06d381ac230b46d4db924415ed3f12b475be6dcd55bfcb57c91b860404a25857995eed51cae1a8c695f1effeb5416776fdfc58891a3d1a64d7b8c18f101b3cef7c0d5d5058f9f3cc3a933e7b06be75e2c5fbe129f7cf209ae5ebd81dbb7ef40a3d6e2b7b9d3d022ef73c43cbfcc3becd3839fc7b6c5919f87b0f3b6f4d330a744819cdc4bfefcd5dbf296ec4997f72721e095e5b003ddd78efab8b8f929a160f9da70f596aefb936b99d7fb71e8266b48b2fac30465ebfbe2eb697229fb62abba222c741bc4277f61b46950552e49ec65ad6cf3908d29a57cf3f71091db0bbdce4ee6ce585f14ea837375fe809a0b393b2e5fd88ef212a9d7a3f7998950e7cc858215eaf2632497c2ea576859a7a25c923874f2228f816d0ff8bf94ba5c4b32f133a151593e6a147427875687167e91d0b10ab55685e2b871f9129efb3fc74b66fd7d3fe3077c30f2437cfac9044c9e34059b376d47d1c245f0f5c4cfb166fe649453dd802222f178eccbff3d874e5facc4e78b765924663167e3c12bb878c75f2e49e4f67245d17c6fb7300be6f688fd8ed991493f2ec1e1539633345ad4aec0efebd452ac86947238c668c0a5bd1b485150b5dd40f49fbd1d03e6fc8b0a2d7af0f7d39b3d572defa1fae50ba35a23114c26316cc18fcbeab3adc5c7161d153282338776a17a831630cbaa8a2e55f3a241c9381149099dbff81d5bd477f0fe85d9ec82ea70b3c57a1471cd8fefaefd81cfaf51620ef9bad3b5965795ac3d78b0fe02785ebd8f5d0bff276d4c267a85063376dc49200c07d7cc45bdeae5e5926df2e7c65de8f3d1546cffae2fcac9b312dc5cd450abe27a55ccf9e7d80d542bed07274f1f9cbaf114517066ed273dcf71aea24034ec9ed76ad4a859b1145c62985548deecac4190180b379dc4b8f93be41250288f274efe321ccef18212e5eb329359630ed8fd637f8b8647727877e22a6c3f7e133177b39fb7fbe1531751afeb68b924913ba7173e6b5998e72f482d2d467c8b52f5da7141ffa95f35341d3c09651a7694df9538f8e74c9cddbe5c2ea59d833702b0ee545caa5d7aacb7af5a8496dd87481bb231b6aa337661a167c76ef72af59aa376594ba7b4dd57de9e412b29dc73e5c57ffea798d2b29b962d51061d0f6e12111d01e8d836d342d166a9de628b4167c0ea07bbe05bcad27b3d3990d7fbf056e5a05058de82d4f56eeb814be8ba113ecce2354163d349d1ba5609f87868e16a0c42e312ae6855528536a51dd1aeac13de29a9c63b2554685228062e419781a007498a39f1f3dfc7e1e9e28403df0dc780e6d570ef5910f69e4d9810e78ff19d101812817f8e5a862a4d0e3e5e999787da9aa068705d464c924b1274ff8e60f7715ac49c787849724474502851afe747b1627ee7f45e44044bfe25b5bb7e00b5d6328c6f5ad879f9b9bc2651a680379abf3b402e096c119b13f4ec688d27c5c79f8d3719cb9cd7117a3e8d2d3528358eb8ff9ab5d6a9dbdea0c4a607fbb830adb8c3ac3d8b6e77f32506575e5e8393472e1efd2ca57833011bd8b6b65c9220e7387292b3659c1d1df9ab9326ce5b594183936f81374ca931c0acb44417de5078f3fd4ff3c429c2dc9587cf99604b53a79c1d13fa3834af5e0ce77e7b1f63bb59461f4c0e3e9ea99bc76eeb741d3939418f12ddbf5eec3e4e2b570f6dc18bfbd7f87ae577a4a9a9212f9f62ebac0f716cfd025e56313177f2c8c1d7d3ca71564f04b3fac29c31238765fba96a842df702db85859e5de9d87b28aa14b7f4a4de79c9b2d59d5c628ccc027460ad7f7ac6d932fde2329c7c7105075bfe8e728e05b945ee051754752d0687687673cb56baa7ca13465d149f5a951acab986a069cd72724982a6b17d30698e5cb23d28ff3561e099d59227e69650e591d8f276bee8d3102111d118fed3066c3a7605958be745e3ca894f2f2c9e3f75e2406167b31b743fd23435731ad728cbefdf748109c6fea5d3d98b74cf104e6e9e28d3a8132a34ebc6cb61412f10fc3ce91801296147bc7a8202c9f41d9db2613381f56137829e1dbbdd89899326ca6b1281613a9cb99f728b2132340835bccab0da9a0987ce8890c870f4def72542a32370b8cd127c54bc0b7eaff53f9c68bb1c2bea7e0d959e5d6fb66f1bdf7a087c7a9fe29fa40af2d06e534cc3d34c9a43f9b7672eb29ce36b2bb8bb49615529e3199172414f3dcdaa15c595a5a3f0ddf016f86342671cfa69b0fccedb09650d81a3971fe2f0c507b8f72c6907afe030cb10a1f60edd87743f9a53206f4eb42fa1e1f76f6ac857ba1ade9db414a3fe3c87210bf7f331f4b057cfb173fee7d04549e19cc9226f366432721428cecb97fe5bc75fd30ad50f544f98337ad820689cb2e7508a39b6ae2336e71467c296bb45d29b1aa5fd70f2da03b904e4f5d0627c9b12722979b41afd3d1efabaa3d97672f86142c42fa503a8cadadefa2734cc57955d7305efe27f1cea8f827fb585bbc60dd7baadc19d1d6b706a63da92ad842addf1d5aae3d0e92cbb0197cffa1cbd3b491ec0b6c28d3b0f51b2695fec9cd90fa5fd7ca05229e06e160c28399cbbf9141ab58ac764cf285e874562c7f15bd879f2268e5c7e8407fe091b8205737ba254c19ce8d6b81c7a34959c15fb4fdb80b5fb2e670ba7389383a3396af6bb4c7eaf265c0ca98b96e795b710ba4dfe334118e5889057f87bda30f6e8c5a0ee7b6350b05c2d3ea61efe3a00570f6ec6e1953fca7ba68d69db6ef03c1026f279bbe0feb30028d56f8f2468efd8baae0841b703b6af5b8e365dfb5ad80a831bf8a17cfee4c75dcf55a8343a4e5c82565b3fc081a717e4ad1224e365bdfc50274f05b8a91cb1f6ee3edc0ff3c7d872ddf05dbd8fb0724237043d4efbbce47b7a2ffcb8460a996acef63fbe43ab865262105bc1a170632c9bd0058d2b17814ae90077d7e4559673d71fc3cc9587b8c31ae1eeacc5fc8fdaa253fd32bc9c1e04064760e6aa43fcb34c34ab5715f9f3e442d182beb1b30c5ebd0ec1ce83a7781099474f5f2097a72bfab4a8808317eeb306e463bb17748ac7ff4effcfe4521c63bbd5472155eac7cd9b0dfb06651a74e0eb0f2f1de3a29dbf4c755ebe7d720fb6cd1ecbd749f073f995c4e36b67b8f77b7a70e951307e3d705f2e49cc9f3a01232658365ab22b42d0b388a4ba46b2aba8c7b7d20b7a3b615c2bcb3ce46fc2c533177afeb009e5ffec8adb6134bec6ae63824b69bae646742cdc006b5acfc0894dbfe2f446cb3cd069e162a83b166f3e2c97241cb51aec5f351b352a9596b7583f24e853063643bf56957950190f59d047feb815f79925fcbf3e0d79ce727326fcb20b73371c43ad9205d0bc5231bc0a8dc0b653d771d7ff15968cefc4ade4b4f2d7aef3f874e14e048546a2529962183fa227da35ad0d6727c9912f294e9ebf86cfbe5b143b8edc96fdcd96c5f62b0227ce5d4543662547465986d41dd2b13eca39a7cd09aedfac6df0c85d002fee5dc5caff75e78e683dbe5dcdbbd6c9116ec9872d7990a7b28d3ae1e8ba9f71f69f65f25fa69d99db6fe2d1ab48b90494cce78dab0f9ef3464576c71e34c566c7d0b3ab7027c5e46fa6c4ca2df1203002e71e247ffa174d93b91dfc04fec12f50cfbb145ae5a9016d8c0aa6696cb4381862e018a3c4c7157a627ddb1f70efc2119cfe3b7d93ab94770d4e1074862ad596fd3ec5f53b710d165be0fceda7fc95e6939b781e148697afc3a1d35b5a5cd71fbee4625ebf6c214c7caf096a972a8877eb55c08a4fde4339bfdcf860f65644465b0e47dc607ff3e845f27e63fadb41df6dc4d0ef37938b04e67e350a67b7fd8aee6d1bbf55cc89ea154be1bf15b3b0faa789f8e17f23ec5acce93ea3fb2dbe98776e5a03e55dd29e94461f2d092a093959e146839e59e1527438ad8b1bf296a8840acdbbf331f462d59bf1ede901d507e6624e8cffe42321e66fc0d674c6662d744274bb5b42c1494e9b59e9ee4e2a7cd9ae6482286589418165dc0a974058743872bbe664958c01775e3fc6f9e7d77135e01e540e0a54c85502e5721783af732e1c5f330f1776aee495517a637450e2af73213875d9b21bdf37774e9cd9f20b72e74a7dbcecccc2bd7c1bf8b83b62dfec41bcecedf166d1fcfd9f331835671bbe1ff00eca14f481a79b33b46a692ae0da8317307ddd3e6cf8e63db4ac2139481135862d849e35b2ce2c1e216f499a1e5fafc5e6c3d750c0d707fffc3e1de54aa62cb14e76e189ff4b54ef3082bf9a53b56c51f4abe28e9874b8dfabb51b883accfa279ede3c8fb55ff541fdde9ff0e96ae4c91e11fc0a390b9660759911ebbf1e8027372cbdeb53034591fc66f3750447c69d7fc5c2b970ee4eea66c5d823f6a02776376d2da96e93ecc04f0b2cbbbe699ee9f68b96e13d1343a1d6c2c5cb0701d7cee1c6a6a558f6513bacfabc3b820fff879a7a0f8c28d818830b3444b908159effb70d2b3feb8273db97678898131416b567652f542c2965f63241956cb33e1f2324d4fa5353d6ae5c06b79f04e2698034adc9dc4a4f0c5388567288d2302137893941495d88f8c169ba3729cf2dfbaf96bc792cfbf35f7773312f5e383f6b102d12629e0441c1a168d2f3a304625eb15461f462f7637a883971e69f6578725dca30a87194664428642b9986be48cc89cb7b37a48b9813ff5cf0b7107362daf419f29ac05e74c3a62d744258e996746bdd106be55cdc263e6f531cb9df60216a9cdd60d0eb6090bb02ad0583428b85879ee2c6bdc7f21689f2a58a60f7f2efe193336539d93393394bd663ccd73fe1fbe1adb8f0ba3aab995027ddb5b9f3e42d1e7fbd71f922f8a677733839c639d1d19cf293371fe1f68a31c893c34dde2a5179d07c94f2cb859513bbca5b2ca1dce64dc62ee1e1494ffcbd0005f3259d78253bf32220082dfa7e827357a4c43a264a14f2c5fb0df3c341273929a607d495eeed5b0445ab37c1a53d6b1112f08c8f99576cd953de03dcb3fdcf4f3b223234ed5113fd83a33075ab6544c0a6d54a63f7c9a473e86737ec4547ecce42cfee2cf86335dce2c5ed5e79c25210e3131d1e6275624e288d51185a2f1fab54f3c95b242e5ebb839a9ddec7bd47cfe42dd647db26b5f8ebd663d7f9ab81a2edbd817ae5fd78dcf5bd17efe087bfc9cb3d9c2f33d7efe7623eaa73ad04624eec9b3b3049312786cfdac25f37fd3a255962be6cc3bfe8326222da0c1c8f4fa62ec4e6dd960e8af6c87d761f55ef303c51311f5a3f5fba8a398d57779bb40c5dbef80d61af5e703127c25e5bf60a1c59353b5dc49c5879cc32188da35a81c57fad954b027bc22e2d74223b5be953277c88ff4d9f2b97247ad5ca8f1a45acd7a27d133a85137eda7b0ff71e5b8ef791854e963a59ecd648e31e63b1efd8391cfd7928fc98589b3cdd93e2f2bde7e8f9f55adc7a6c19bef7834e3579a09894b2ecdf7318c104fdcb517df1f5476f8fd16d3adff834a8511edbff98912ce7395be3f28dbb68dcf3236ea19be3e7eb83514dfda036a46f43d72da72f06cc894b9e73ebf84eecf9ed6b1ed4a5cfcc4d50691cf1e8ca496cf856f2bd482b27eebcc25ff1047d749f8e98b36ca35c12d89386d8bca013a2db3d21c5f3e764c2109723dd45abc417ed4a5ae4514f6fc8faa8d2a61fca37edca733bbfbc7f9d4fbbb97ffe90bc47ea89523ae3d7030f71f37e5c7628c2cdd519db974c47dd6ad697a16dfdf60378f7fd4918d2a61a26f66b0c2f776d929587397b4edfc6a5bbcff9d839256e29e0e321bf93321a8cfe0da7af3fc1eb0b5be12e87a34d0a8a8446e14d07b5ae8aaffa37e1d3da2edff3c7ec754771ecca430cebd90e0bbffd48dedb3e387ae632f7668fef9351dccf17431a1480d690bebe1a25ebb446833e9fc2c9ddd2a993c2b9febbe073fefcf895af8d73ffae884dc89216c2a30d98b2e53ac2a2e26654e4f572c6ed074fe1e49afc1815f68e3de987dd0a3a919d45fd9f757fa14dd7de7249a26e316f74ab61d97d9d5e78e72b8a16c3a7c0a74859794b1c27ff5e84a36b7f924ba9474f63ea079f24107562fd82c9e8dcaa815cb21e8a37ee8d5bf71ee3e4c2e13cd7b836031b54e65cbdff02d5862ec480aeefe0f7199fca5b93a661f7313870e23c6e2c1f0327ad94ead551a340a4ce807a1ffcca7b0d82ce6f8187bb7d2466d9b4eb303a0efd422ec541623ebcbe2f54c6f40d6f4b53d406cedd0967cf9c30e8a2717c19aadb00003b16494441549359e6798a9587671e3ffe3e3998eef9f52b1e112ebd5873e2310edfb26c18fc31772afa8d9a209704f6a61d7631869e9d853b295abfdb0badea55954b12f470df0f487f0f71df9255d0fdebbf62c59ce6d906bf881bb7afde610872174d7b5014aa64a9b2a54a373e94d672d14a69bcd89a98324eea3a9db27c3fa298c59459504c768282c6bc8dd7c1a15ccc9b542ec2c5dcc5492de56f572be1e6acc1d076d5f87e7f3311b40716feb53943c5bc66e7e1a8f5ee48b924e196230f1773e2dcbf7f6127b3c877fc1417858e04bf20b3ced38b07ec398f2fe675ca1516629e0c6c594f84539c1db364d54678c51bb75d75fccd0e7229852aaa366366412d4fbf7976eb027e1fd51c7f8c7907d78ffcc3b7b16630abac529ea633314ca21e7f4a1b31ecf359f87aee52b9641d50e0966a154a62d3e1ab3879edd15ba7afa517d4d54e542b5f92bfbe89fd4ccc8946950af330b5f17b114c11ea566fb5fd50af937e5c82115f248c895ebe845fba88398972cd2eefa346a761a8d0fc3d792b101d19d790a658eefc99a8203d1334c3e4eed9fd38944eb1da8995f19e73ad4a815f97ad944b027bc5ae053d39e395f64c9e7c053075d2e77249e24950240ede881b5b4f2bf57a7d1c3b26487366374e1d12eb9dfbfcce65fe4a1859a5955e50a5dbbf9a179ad74a386e3ee9c73fd075e4575635577de1142936f7ff7edb8d8878738153c2bc0dc790ffdd99183cc332f3577ca8bb7dfb899b7caa1a0592791b01afa4e8673e5e2e4ccc13e6c3cee5e98226558a702bde56090d8b40a7615fb2065fc230aa4d6a96c3801a39d32ce644744418a2c28211f2f2091e5c38820a2d7ac03b5f113e266e7a1e8a546d8cd17f9e479d6e9408098862cfcb96ef47f16c6be9013ddff49c9b33b25f5794a95c532e09087bd407bb1174d1ed9e38c33f9e889ae52cbdc0b79c7b86d034088b09f2d82d56bd295fa74a6cdbac31b1a91f9ddcbc2ce6d5debf2075d792e34f81b269af5814317ab4615f6b6887ba091ecc75ffec47c5d68371f6f24d794bd6529559c91f0ee8828b77fcf1cdb27da9b2d2c7fdbc03e37fd90505fbae5d1a269ea8e559400886ccdcc4c7cefd034331edd321f23b6fa6418d0afcf5d7ada7f0fc55285f8f0f6d57ab128abd2d70feea2d946f35107fefb474cea4fb6670fbba685fd4014aa36598d7d440c199aab61d807fe74fc09a497de05bba2a1af59bc033ab1565cfc981e533a033b3d439acde3ab5e577b99076e8b9a6e7db9c42b93df1dd82f48b076fcfd8ba8ed885539c097b7370482fae5f3a8b8a95ab214acecf4d94f175c3b04609bbad5342b11acdd1fac31ff8fad5039bb0eb972ff9ba5bcebc68f3e1acd831f59bc7fec5f6799fc0c3273f9a0c9a8802e56af14a8ce6daa607cfe185799b4f213811ab7cfe376330a2b794d92aaba9d9f17d9c387f15cb3fef82ce49887252fc77e60ef69dbb878fbad581a76bc2e963bb4fddc67b5faf4544940e437bb4e50d8832c5e37edf6367afe0c7dfd6c2ffe52bbcd3a8263e1bde437e47c2e4e54e50bad46aa5f2a166e9fcc897d30d8bb69cc60e66f1f7eddc024b7fb0ad31d8b97facc78793133a64babb3ae3c30ed5903326e9bcef2985f29bfb96a8cc9dde688c9c62b2576a2539a65218d723abe7e2c6d11da8d8e23de42e528e5bed17ff5bc733aea5170bf7dec5d5a7718d32a5c2013b37af4393369de52d02c25eb5c2ae049d48ec87caee824e7c32ac37be5ff4975c92685f290f9a96497dbe6d4af9d8f97fbff1f5d7fe0fb1e3e7f13cdd63ed6ea3b8854e8406fa63f5a45e28d7b80baab4e90fb5d6896f7f7ef70a567d1137c698562255ae587ce0016ec68b2a47bcdbba217effee533ec52d2ba114a455db0dc5f38020ac9ddc9d4f494b0f68fe7aa30f7f878bb333fe5d361395cbc6c57ba7cf1c3c7e26fe3d7052de22f1cbd48f98f0b7934b12dbf71dc70f8bd7f0b9d9cf5e580a1d1d73273b764eefd44da1cb6cc8d1afd7d86fb1edbf846259cccf17ef372b066544fa893941f3c8bd7ca5b0bae4b5be77c9b77cfa668d4e4359bd24758692173b79b36744d8e43d575e60733cebbc7fc76658b271975c1298b0579dc816824e0851675679a13cb87adf32b6fb472d8bc22f47ea848ebacffbccd8084f72f2498488905738f9f7af28d7e45d3e8e6882449eac79ffdb97f878e2b5c35be90792df4d3d312a2df63e72c0a6ff2cc58b285c202f9fda662e76590145b9abdf6d34a2a2a270feb7f7913f9573cc4d90455e67e4623c0e08c5d1f53f5b04d9213f828aad07e1eec3676857bb24c674ad83fc39ddd174dc1284441a70e7c00a787b263e1f392c3c8209fb3d9cbc709d7bbbc7177f6be6f4c5ebe8347c221e3e493826ddb66135b42aaa8221528a9d9f9eb4fb781e0a576e289718ec9e3ef8d7f7087df51ccd867e1ddb98a5b80c9b66bccfd7d30b9abd32eb5fcb6446b93d9d71ebde23b87ad86640a98cc45e055d78b96723d66eda0a67ade538e8ef071ff00014a921c668c0f6799f223234617e6872903bbe7e01eaf71a6721e6d4fdbe62c2bb3c1a56ab91d3d162c4b7e83461113d61f21ea9c7411f85e6f9f5f8b45f5b289596b7f6dd874f51a5ed50fcb4346b236491e06efd6d1a1ad5aa0867e737077b490edfaf3eccd3a8d2d042fc8879ff308b9bc49c02c5cc1fdb1ea5fd72214f0e574c1fd61caf43c2b06efb7e79cf84b8383bf1fcf323fb76b429319fb5780daab51f9e40cce97ef8a8f73b68e567c81031275e3db92baf49cf06ddd39445cd9b59ede42c6a727aa3699e1aa7f49bcf4fcfef6f07eecba538fef8ed5721e6899094d1670fd89da00b4b3c69ca56ac86299f8f934b1241e13a2c3f22cd594e0d2fee5fc3dac9fdf8dc5affdb17b9606ff96134d6b16d34f79cac780efb5d762ef81f1f4b8f0c0be6625ea8b21408264fd1f250aa347c3dad18f47ae4d3ddc7ec0fdf459e44d2ac8efa6a2e0fa29295b9d5eb552f8fed4bbf47fe8a8db0f7e213cc5a7318c161a9f3b0fe79e309d4ae52167d3bb794b7c4e122876aa54c6e4e8e2a1e7a56a552f094ab445474facd3cc86ae8f7acfbee288cfb7681bc250ef2f69fc3ee073fe3237e7f6414818fe22ce4037fce8ced56a7696c14256ef5c45e3ca0ccb6d963111d91b8f3616a5876f8015e47587eafc1dd5aa355e738a754c19bb117ddb0bb2e774274bbbf9946d5ca62ff69cb4c4b1d2ae74193d2a91f4f4f8c1cf98be1dd897f40eb2275eb92d5fecf9c71a8d7e32394aad7966fd3474560e3b4a13c2f747aa3f6c885bf8e3fc5e13357e52d964c78bf17268eee0b476dfa342652c3d0093fe0d7555b91dbdb153f8e6c850ef54acbefbc9dedc76fb0ebbb1a8ba68dc390f7a4eb191f12b923a72fa15d9d92f8796c5bee615f63f82f086102707bff5fc8eb9343de1358b16937028282d1a555039e7bde16088f88e45315bfff75b5bcc592c6352ba05b959c887efd42de923e90f39b9159e1347c440b91cbaf147a4c5dc3d7b7fd38865be8ad467e07a55ababf4e6cfc05c7d6fdccd7d38bffaebec0a6b396e3e6650be5c6f95b8fa054dae6ac848cc4deb5c12e059db0d73192f4e0f9b3272857aa185ebcb6cc229596f1f4a420516f376e2edc7df2f3325926a6ee46b260b6cdfe0877cfece3e58cc041a1c24d9d37e6afdb0b8321cecbdf0465209b3b69143a34af2b6fc95c68acfae3a90b79f432821ce5be1ddc14250abc5d50690adcf4bf0ee2e28edf93cc714e82d763f437d8bcfb086f34d07341d3db7e9afc21ef4e37d1b4d747f8ef88947b9b52b7ee5d390b352ba5cc133fb359bb6d1f3efa763e77fc8b0f75b10fedd400659c5ea7bb035a8b1153631ba4041d9f622f50ca53532e73937817add604ad3e2051d7726f766abca617898d9bbb3ba971fae471142b5b59de2230c7de75215b8da127d53acb6ef8e4f1c592c58bd8f59037c8a4653c3d29021eddc2daaffbe3c53dc94a8e1d3b640fd1ee4593a050283064c13e8cfeeb42ec327cf111946ddc45da2f8dc418f528a67c8e49bdeaa350fe84e9431f3cf6e761405bf5fb344bd2b1d258f58229639980fec8ade27f8edd40e5c10bf87cf27bcfdeec85fd32489aa6475dca494119d236fdfa2d267dd88fcf4da7441d33260cb310f3555bfee3623eb075152cf8a81d2222a3120dc0622ddcbeff983740ba7d30395131cf973b0726f6ac8f529a800cf1262f50b686bc2641615b9d3d72c48a3991a380e47c79fbd47fd8fae358dc3b7b802760492f121b37a7e779de8caf8598274176a8ffed56d08535fe66dabcdb1b43bab7974b12691d4f4f0a72063ab7c372cadc91b5f370edd016ee151c3ffb14897e912a66dec2e980873e101f36ccc30389683596f9e2099ad655ba59bf2c0b1ddba856255cddf5074f754a73a457ecbe80b2fd7ec280e91bb0eda894533d3e6a95f4f846ebde3e16fed598fe88b9bb17c117b7e193a196d3054d63e99493bd6fcb4ac89bc30d8141217c9bb53171d612146bd43bb637c11cfa5d8774ac8f8f9b1584a721edd9ca9262e7c22f2cc6c0297701856e0d7c7c9bd53b463ebffce6b1b814a9e4d5bef9fb0f101e6499f33c2d24366edebb4d03f4fd60bc5c1224077bd309bbed7227926a9109b197301a8da859b6084e5db36ce9a7f7787a89daefa0e5fb53631de42eee5e83bd4ba6f0759acb5ebbdb68e43173a08b0e0fc1ea49bdb9d730c5bb8e3118f0f0f271fe5e7a10a572c1a68b41387cf69abcc5120a97fa2913bdc1efb5c992f1759a6ef6f3f2bff99cf0978152185d0a26d3a15e29542e9e1745f27af1fcea6bf75ee6495f8e6f9ccf3dd2530b7d46d916fd111c12cabbfa2fdc7e86d9133fe0c169ac81c8a868fcb2620b662e5a85c7cf1217c5ba954ba153c59c50474bd72ba3a131f4b6e3e6c0d1d593979f5c3f83cd333f485767b7a4486cdcbc54c15c387fed2ecfab2e484876d102bb1674c2dec74cd2ca8ba78f50ba6409048464dc78fa7bdfac8c8d1a475d90dc61c80c4a6461ca4e45d37d28aef5c32b27d078e09728d3a0038fbcb5eacb1e087898bea15c1fc77861e9ae8b3c7a5a625057f6b821ddf07eef0ebc6b3c2bd8b2fb0836ee3c84f53b0e30c14d7cbad5e59d4b2ca2c2a506ff178158b1790fb3f6f5a85fbd3cea544d7b76bcb442fe053f2dfb1bb37e5b8be749fc463ede1ee8dfa222f229d237484c72a06ef58e9f2de0215f89970f6ee0efef86a7ab251e9fc4c6cdbd5d343879f2148a944e98db4020915d74205b0a3a21443d8e7dff6e41cbb61d116d161ad6cd5185714cd4bd5865915628d4eb3ba366e2e9cd73dccb9d04da44f15a2db9d3902992d6a1153fe0cea9bd78e7c3efb9d7b0096a04506320bd3128343813a0c6aa5d27a1d727ee3f40c157c60cec82d1fd3a67693ef0fdc7cfe1d6bd277cacffeea3a778e21fc0c577f2d801f21ef60145799bbd643de6b0e5d5ebc4bbfe552a25ba37af8e6a39a2a13066ddf43bca9cd671fc429ed780a088897f4f1f86d7cf1ff1727af22a2c1a3f30310f31cbc3a0513a60fdb24568db73b0bc45109feca401762fe884b0d2dfce0f5f7d828f277f2f972472ba6ab8a5ee122f184d7ae153b80cdefd7209547204ad6b87b6e2d6895d3caa96a3ab14418d9c9ac85bf8d4e6df78e28bc2951be0f88685e9da054f4428ddb0e5526092ddf084bb9b0b3ee8db11e306774b32c29a20f504060563e6a2d57cb8e14dd9f26a572c810e1572c0c990f1dddbc981721774f874416c0025ba87a9e19a9e8445e9b965fe32d43289cc9723fbe0eb9f44e29537919deaff6c2be88410754bbab6ac87753ba5ac6826f27b39624c8ba250c78bbc96565c3c73a1fb372b78ac6b8262bb5ffa6f1d9a0cfc927e30be2d98593994b98ae6a8976ed001cd877dc3b73fbd710e6b27f7e5ebe9cd0b074fac3f7a0f57ef24ed1c48e3ea83bbb7c12743bbf3696f82b441330d48c817afdec6c7cb93a2749102e85cab307c90f9ddeb6f831c3b3b7ffe2bef86a7ac6ae776fc29bf937628a9d2dc5db7f1e895654ad496b5ca62fb910bec71c9569395524476abfbb385a013c24a7f3be4a15bbd74219cbe6e2966a5f3ba6278e3c4e739a796f69fce47a18af5f87a48c033ac99d41b9dffb7987761121407fecf4f3af257b27c2805a569cadbfe65d371fedf157c3d238881039ec57862dde19bb8f5e0a9bc35711ad6acc8b390756bd318ae2e5933ce6e8b507ef2d55bf762f9c69dd87ffccd41858a16cc8bae754b208fc32bf6cb58ef334b4e9de4e4999ed9d3889ff6dcc14d7f4bff89a279bd70e5f683b869a08244c96ef57eb616744288ba252ffd9fa06af93278f0c2d25bb85a214ff4a953402ea59db61fcde1895968cacf866f07e3d9ad0b5ce05b8f990595460a594adbb6fd389689ffcfb1e3e9774eefc5d6591ff2f5cce09ede0b3b4edd7da3c54e503096f6cdea70716fddb896bc55101fca7e4622be69d7e1375ae34499a205d0b26a611452599f459e59d034d253f72c7325f87838e1c4c953f02b6edd817fb29aec58e7671b412784959e3c287f7aeddab5f12ad432be78b332b9d0ae521eb99436281c66f50e43f858f8e3aba7e4ade0f1dfdb8e9d1deb391c1d11163b1527c8ff01567fd9135161c1bc9c99042b3d70e27e18761ebbf4d618e83e39bdd0b37d53f4e9d41c55caa54f8a545be6d485eb5cc457318b3c296f75133497bc45ad72a8eee7020f43e64c414b2fc85a76cfe5cbbdddd3832de79e61f715cbc039ae8e2afcb7733baad76f266f11244576acefb3bda01342d413b2ffdf2d68d5ae132275969edfef56f345fd1271f1bf33021a5f6ffbd16c26ee71d3700cba28ac99d4872783c94af44a0d6e8438e19fe3d7f0f0e9dba727f9e5cb8d66f5aaa259ddaa68ce5e7378d9463ef1b4f0222008bb0f9f9697337c8cfc6d14c89b13ad6b964209b708a80c6fb6dcad11ea41a2f4a9d4f8fc6bfcbb0879f9447e27751cbc118075a72c8fa1563a60edd245e8d04b78b4bf8dec5ad7672b412784959e7cfe5a3407fd468c85c168797dfad72b88ca0533569848ccbb7f1d175d8e02d150401a6b81aec82b074f1cbb1b8c3d272e2539e52d3e154b178d15f806352af0d0acb60ec58ba771702ee087cee0c235cb79d2494153cf9ad6288b5a853de11543e3e35908ab172ab6e88122551a41171591a2619d82e56bf369999484e8e5fdebd83075308fed9e5ace3e788d3f0e25cc0638f79bf118f5c534b9247813d9b59e17822e23443d71a6ff6f0c264c9d2397e2f8b0591114f1c998a85464e5f4f8760d3c724b63f6949275e7c2ff7187b937756716adde0cb5ba8c80874f7e3cba761a97f76ec0ed93bbe577330ebdd29159ed5a1cbefc10976e26cc4bfd26c8a98ec49d44be5665db19133d7ae672ac801f3891b24c79e58afba15eb90228ee1ac5ac714bcfedac80867f5a7ff8030f434c5000a3bfc6272f4a1e4541a4d917740c1a3adaf2c3283e4c945aee3c0fc39cdd77e4521c940ef5d7d5dbe492e04d64e73a3edb093a21443d654c1a33185fcff94d2e4968550a8c6d5114793dd3dfc2348f1c17f4ec3e567dd9938fab9307314d6d232f77f3e034444ebf92e8c91a016469994373d829f3556611a974c58d2007ec3f7ff7ad1ef2894111df8a17cac796fc285e381f8af949eb148e36b3a1aef25bf71fe3e63d5a1ee1e6dd47acfc04576ede93f7483e34e5ac7e053f1473d743ab4fbde0650474afd13d47332a68f644c0a3dbc85db80c8f56f8268ff56aed07a176b751ec9653f046e78e9fc7f34887a9e56950247edc799b4f5333a763931ad8b8277de32ed833d9b9175608ba1942d09366448fb658b8cad24248cf6872e6903877f8743e1c5ddcb1ee9b01bc821d3067079cdca4ac6264a56f9ff7098ff56e8202d10c98bb136aad139f06e79623ce796fc3b783f0e8ca49b994798429dd703f448133b79fe1e4c55bf2d6d443625fb4a02f4a172b885cde9ef0f674e3016e72b0c5cb83d6dd9295c7fc89ff4b9e7c85a2b051fe730ae842e5178141b872eb3eeeb086486a443b3ed5cb1743e5a2b951c4c3014e3aeb7570a3e046bea5aaf258089458c54f9e4e491c593d8707358a4fe301ff43f966dda9d2c0a92dbff3fdd2426251e08856752a62eb815322b77932c9ee757bb6147442887acae9dfb129966eb20cbfeae9acc6fb8d0b21b747c68e057be62ec843c49a62c253128c4ddf8de041674cd018a6671e3ff8dfbe880a2d7aa051bf097cfbf5c3db78809aac44afd0e0a5c105579f85e1e4957b78ec1f20bf93fed0143a93d87bb8b92028383456c029356a4641694bab97298452795c904b190695d13a9ddb28dd69e3815ff0ee729a3551b84a43d47dcf32bf80091a0b5f34acbe5ca2ee792dda8c99854295eaf328867b977c8bcb7bd7cbefa68e97215198b7e72ecf76684eddf285b1eff435a8d4999f20c8161175ba10f40408414f1a0a3cd3b5690dacdf7b5ade22e1a456604493c2e996cc2529a822aed76b1c2ab5e8c9cbdbe67c84db27f7f0758242c9b61b3717f7cf1fc6931b67790859e2ee997dd8f2c368be6e2d442b9df052e788bb0111b874e719aede796453f71e3d3fa50ae747f9a2795028871372a923a1315826f8b146e81ef12d59050dfa7ccacba6a883d4f8a3c622e5efa7b1f092755a734b3dfc750016bfdf98ef4bbd3f9d3eff15798a55802e321c3b177c9ee6fc020f02c2b160ef3d9edfdc9cda650a62f7b10b7076b3ff5911e985a8d3b3b1a013e2064839347dac53e36ad872f892bc4582a6d40c6ee0875279dde42d190705a471cfe90b95a313f74c26ef771a27270b9eacaef81c5ef923af78ebb3c640e12a8df8f8bbffddcbbc3170f61feb88836d74502254e1868048073c7e15813b4f0371edee63848567bdd3988bb3234a16f24511df1cc8efe5841c8e31703586401193faf1e2acc034561e1f4a087466db523e6cd378c0172850ae666c5ad43d8b27c75ae014a6b8e7b4757c9c9cbce0cd7b8752c3f567a1f875ff3de80c96f54dc5c2b971ecc2b5d87310bc1d51974b646b412712bb1184a0bf199ad6d3b949756c3d7259de1247bfba0550c52f732aa277272de579a98985836b732ff77a3dc6f2b8da8441afe391e5f6fd31155d27fe014f39acac39199148233dd1291d110e2d82a315080cd7e3f9eb08bc080a857f60305e04be4e17c127c1cee5ed81dc6cc9e5e9c22391793babe0ae31c21951505b81277a72a1e98e45ab37c549d6c0a3fbd4042550e9ffe33f3c3c2b0972e0e33b3cee3a41fbad18df056a47672ed80439c8ed5f3a1d378e6ee76513def98af2fdd33acf3ca9a969159898ef3b76065e3e52f63641f210f5b8841074d1b24b1564e5766956139b0e9c93b7c4d1a59a2f1a6470f019e2bd29ab78172afbb1b0f883a63c0f3559ef145696d83ef763dc3cbe13b5bb7e80ea1d87f26dd4c3404e7539fd4a41a952f36d6bbfea93666b2bab30b2ef1e13e30003cd8c4fc93dcbee7b251cd84b0c14493c03b60009357585934053ccff1e53d7f2df9532f25dd9ff371f1fbf7974078ad56cc11dd9886db3c7f2de99867dc7a3624b69f88622166e9c3a845bf161af9ee3167b9f1ce43282433703b0f664c20641b592f9b1e7c819b87be792b7089283a8c3e3c8f6697a92fad193ba49041234ef76c37f2779f77b7cd69f7a82cd67533e652ba5dc3dbb5f5a61bf55fb71f3509c55da143ad6847bae7cfc95c649c92a23315ff17937ac9ed88b5bed26285fbbad42624c89f0340a0768d84ab217b63f7bb169312fdfb42bfacfde8ece9f2fe6ce6a212f9fe2b5bf64f5567ea70fba7db58c8f8d37eaff39db12f79c2b98951e9f02656ba268b5267c9ae3c53d6b334ccc379f7b96a89837a85814074e5f11629e4284985b22f2ee09520d39a96dd873025d9ad796b7c4b1e7ea4b2c3d9cb04b313d39fbcf729e62952087a677467fcfe3c31324e08fe418f1f7ce1fc28a095d9905363476aa9b2e326e2e34354e04b687b3672e56a12ba0757183b3470ede157ee7d45efe1e052732e503a0fb94b69b6217b4787f1aefdd3159e7942028f8c563043c4cfbd4c237b1e4d003ec89179b9d685da73cf69cb80c27f63d0482b490edbbdc4d88965edae8deba11d66c972d66334ae571c5a0067ed0a832a6ede896d317ed3f9e173b1e4ad094367266a2601f04792553854fd018aa6fc9ca2855af6decbcf64d33dec77d26fae41455b3f308e42d510939f217c5ebe78fb9c7f3e57d1bf81c6541d643bfa5ff9dcbbcc146bf65d536fd78c38d44f9d5d37b787fc9092ef226ce6d5f8e037fcee4eb651b754693415ff2bf334151dd168f6c02bdd9787b7aa33318f1ebfefbdc092e3edd5ad4c2ca7f0ef246872065883a3b2142d0cd103748daf874445fcc5cb85c2ec5e197c389e75377d624ecea4c2ff297a9c1859a2ac60bbb57f3f1f44aeff4e115bec9524b0c5398cf8215eaa0d990c9dc93393e34dff8cf4f3bf1a87582ac83c2b316abd11c67b6fd81432b66c95b81f69ffccce785d3b6bcc52b703f0a7a66e95ea0d8ea2b3eef2aef09f67e4554ef340c4eacf146627ee2ef4516d9fed29b886803e6ff77170f021336180675698e5fd76cb7686008928fa8af132204dd0c7183a49d5f7e9c8ad19f7e89e878e12b73bb6b31b269617838498e68190dcd236e3972ba5c4a9c47574e60e782fff1a8729dbff83dd6498eac3f729c23ab9f4481acb7c5239b72cb5f9075f49eb1917b9953fadc7fe67ecc1a6bfd71f5e066eef4e8ee939f6f5ff5650ffebcd2f4b4320d3af0bf3bb7e34f3ec3e1c691edb876680bdf961950a0989f99983f0fb60ce6a35438e0ab71c3f1c58cf9f216414a117575e208418f87b851d2ceaecd6bd1b97b2f84465a46bea250b134adad786e57794bc651a87203b4fff827b92411191a847be70e22f8c5131e0a96049d7258f7f876756c22188addbd7bd144297c6ccebcbc9b9652b6d23cf6867d3e43f966ddf83d42efdf3d7b004756cdb6981e25c838ca34eac47b51cca1df81c2aeb67c5fca424671050efef53d6fa4f59eb9897bc09b881ff52d23b9e91fcac7ccc3a22ce7eabb6a5558f2cb5cbcdb6f84bc459052441d9d34c2292e1e49dd1449dd448284346fdf15470e1f42be1c964e3e14a7faa73d77b1edfc33794bc6718f892d79b2d37c621314a88302cf681c9df1fcae3487be7a87c1b1624e90c77bb7afffe20d02f29a26af679ae254abcbfbdc898a2c76ea22250f7a0a6a43c16c04a9837a40c83b3db950e3ca1cb2c8298e0085f6a5a113821a5c148780847ed7c22ff8d83a41510ea9319719d0fd4df7797c31cfe5ee84fffedd2ac45c9061080b3d11440b307d78f1f4119a37ac8df337254f74730ae574c6c0fa0533bc0b9ee2bbd3dc629ae264ee784453deb67c3f8a2774314fe46282bad7977fdc0161419257f29085fbb9131d4d7dbb7e643b8ad76a196bfdad99d41bcf6e5de0eb82e441f9c3e91a5e3bb49587508d0f5d6b1a0ba75e12bab6f72f1c41cfa96b2c9c1f293a2035b808f31e998bbb57f318eb04fdb6b90a95c66bff873cac6b46f23a4287df0f3ec0bd97e1f296384ae4cb819d7b0fc0afb8eda4c8b54644ddfc6684859e08c24a4f1f72e5cd8f73d71fa067fba6f29638a8d29bbeed26ae3d0d91b7640c64c5ed5f3a0d2b3fefc63dd94dbc7ef6804f57338939596f3ff7afc6bde309ea8a2f51bb155f274c73975f3eb8c9bbe48faf5fc0cb8469ee3b59f06dc6cee6f1beebf7fa983726041234c79b02fe500a5cc21423a058f5a67c8a9939a5ebb747bf595bd174c8577c2c9c9cde7a4e5b8b832b7ec0c9bf17c5f6ba946dd4891e4abe4e3d3214bf9f28d3b0239c3da5ac7364a953b4c08c1673ba8fe97e4e4ccc5bd4ae80cb771e09314f2342ccdf8e10f42410a29e4eb0ebf5d7a6dd583c7b1a1cd596debc94908212536cca84203454a1d3f4345a4e6efa158756fec843c3b21f9abfefe42685ab359f9f4ed3d8a8fbb6d9b06f624587c4bbe7d4b5bcabde04597f94818b228f9170519092caadfb72272e9a6625009a0c9ec42dee6aeda5eb660a0aa4d23a31516fc6d7099a86d8b0df04689ca5e11af27b2072e42fc61a5f797174ed4fb8bc7703df46b3173a7eb69087742dd7e45d1c5b2b59eba1af5ec4ce39cf0ce8fe4d2cc18a4ae1806fc78fc6bf47ce43a5c9d86c84020121045d90290cfa703cf6efd989823e09b347fd77f525bedf718be784ce68c84a3fba661e9f8a46627ef7dc01be9d62808ffce3145a7d30839789e7f7aea2f5e8efb9b7b4f9d422cad76eb2bec92a8c311a791c79821a09a66e7a17cf5cdcca14b06b7947f259200b9d44fcdae16db18da9a235e204bd4c83f6b18da77f7f1e8f35137bc7ee67ea4d39bb7d79ac955eb07c6deee14eddf7e4e4485100a937867a66321aba5fe9bea5fb373e39dc1cb165ed9ff87c5adaf2a40b2484759e3c84a0bf0161a5a72f35ea37c1a51b77d1a04a49794b1c0f0323f0dd3f3771f971c657c4e61c5e393bd6a1ca1cf276a76032f94a4ba16d4920282b17c58637f1e0c211ac9bdc0fb998c09ba0eefddf47b5e063c3045996a6eedfecccfd8b47f92bf9311465963a45f87b2afb1d142c572bd6398ea6a511347590a61ef698ba86cf22b8b26f23ceef5cc9dfa3b0ac749dc96b9d2c78fa4d4c63e9940b3f33a617d27d4af72bddb7f1295b280f2e5cba8a569da5487482b421c43cf908414f2542d453879b8717f69fbe86f12307c85be288d019b168ff7d6c389db64c562921f0f16d3e77f9bfdfbec6ed93bbf94263e49bbfff009eb90bca7b817b52538a4d4af84239b2098db334fd8ee6ac9bc857aa2abc7d0b5b8c0b6be5ee63b2ea69ce344d931bf9c7490c5b7408bda6afe70170320aaaf28c50c31893f64864d4addd75d2328cfef33c3ff70e9fcee7dde86f82862da85173fbc42e790b789e71c294cb9ec4bc641d29ed6da01c9a977a44a827e4f0ea3958f9bfeedcaaa76bc593f1306e1cddc1a7a02d1ad680ff2614af3fb3a05c05749fd2fd1a9f9eed9ae2d2dda7f02d9830b39f20e5887a3665082ff764205a8819c3b1037bd0b37b57dc7d1637b5cc848fbb163d6be643e15c960e53990979b10f5f7c848b0bc5fa3ebc6a361c992837e8f31977a8a3c600459023defb66258f271f9fe8f0102c1c529747a0230134f7d23641c7fe634cc23ceea985c43bd4500cc1c61a88d29784d2e80aadde0067e373b828cf43e974927db997ecbe96ff2019341ef8259f2990180b07d7b1b08a7d4b56e199cf8c7a1d1af41d0f833e1abf8e68845ed3d6f1ef4f163695694862e0bc9dfcfa9a661d50f7799f191bb9c89395fee2de35b8e5f28d0dd3fbf777c379cf485670f74518561c7f9c20500c91d3dd090b7f9a8b2e7de27c2b046947d4bd2943087a32113756c6101519817e5d5a61cd3f07ccf261c551bdb0273a56ce0b57c7ac89754d2937cb37eb2e972c39be6141acb73bcd65a7a027a62e7a13570f6cc2fe65df5904afa139d194ee93048d2c589a66b56fe974d4ec3c1c2598a54ace5f4aa50a378efd8b7d7f7ccbbb9693438cd1013abd0f82c29b213aaa3c9c9801e9cc845c63304a0b2babd845563818119d632fa23c4ec0a87c7baef36aed06a2ce7b63f83a89ecb3db97783e70bf0a75f970c5ba6fa4de16ea4e6ff7f13cbe3d3e1ba70de5e159290b1ab1eeebfe7872fd0c3aff6f310fdb4bf3c569cc9c12a4f896ae8aa683265af82de822c371e9bf75dc9991ce2133098dd463e399a738754f72d033876a8536f52b61f9c69df0cc2132a5a527a2ce4d3942d05380b8c1328e3f17cec6c8719f21383ca1639c935a81b695f2a05ef18ccfb11e1fb2c4c9222fd7b8739cc0b0dffbcac1cdd8fdcb97dcebfdd5937b9285caee8f46cc22add0a207df8db691054f5dd534fd8a20e7b0ddbf4eb2e8a627aa32d1ac2b8ba639b4ff5a267e34fffd4dd02da88bca8788a086508617878b430c9c148096351ed4aca9a4628b32c6813df0ec3475ec3f26fe21398fe3b5f741c428933e367d7ff222278f72b2ac374c1d92a8cf0151bbdba8d86c77f1a1e9660f2f9fe0024e9cdaf23b8fb247d7a609136f13414fef61d9c7ed79c388a69f9165fee0e2513ee4611e2428b338702300ff9c7f9668f77a0e572d7e9c3e197d467e266f11a417a2ae4d1d42d05340523719212e63da7970f7167a756e8343e72cc5ce447e2f47bc57333f0a78c785f3cc2c48d00a576e00cf3c05f9f82d39cc5154b94e131671b12581f6ca5b2876ba154153acceffbb0203e6ecb098934eddf0c7362ce499c04cf4fa6e031f6b2628fa191dcbd43dbfe7d7af78c6b73761d47942ffaa2ed4e165e0eda545fe0a3991bb94379cbd9ca050302167fbc430510a7f1482a0b32f1072291046d6767a9e773f023d8f535b2451c8daeef099d40b414e81e44740e3d824c4e40c48e71cccac756ab8d01837ef5267c24bdee6b90a96e00955a821440950d64f1988210bf6f1a980d438787af33c4e3361a799051440862027371a17cf6ac8d96dc5b147781294780f46cb1aa5b074dd3fc85da0b0bc45909e08414f1dc2292e05889b29632958b8180e9ebd8e453f4e83a74bc290a08f5e45f269426b4f3e46a42e73bb5dc35e3d97ba7c57cc8a4da55a4876eea2f15e9af6661273ea12a6286634dfbd68f5a616624ed07e0d7a7fc2bbb24d981ce7e873b6cffb94075131e199d74f5e4b02a3028af082700e2d829c1a032ab42f84c2557320e4d00d3cfce5201ecddf8fc73fef83ffd2a3d0c444a3cc275591a398339c108502cf6a436b4c3895d00439a399087a26e5b7afd4aa371774facef43e79a6d398b9c91190bacdc98b9de2df47b1c60b413d1964edd33682449d1cea8ad76e852d3f8ce28d1b8ac1be64744bfe7e564173c9579f78ccefb3c4c4dc9b59e57ffcf835761cbb22c43c8310629e7a84a0a790a46eaa3759ef82943164cc78dcbc730fed1a56e396657c0edd0cc4942d3770f26ee677c19a43f3a1c902a7b9e834c61b1ae8cfacf7ed58f3555f691a15bb57cca7b451b7f3bff327f018f144e536fdb8f54aa248e95e09ea09e8f7c3168b242461f27b496270863632273cf4d1f0c9ab812a24142f369c45f4b97b7071718057217778b0c5516580a3a70aaff7dd8067594f382aa2a1657f932bb4b47ca08444c81efd0485502528c31959d694c5cc0459e6260f759a6b4ea15d29610ac5cf2728b00a75a3d335a0eb44f070ac0f6ef2de0dca594e8955b232d1cdf1db81f876cb751cb915286f89831eef0e0d2ae1eab56be837e64b698320dd11629e3644977b2a11375ee6b0f1cfc5183a72345e06275ed117cee98c1eb5f2f3f4acd688f9b832753953d773972f7e8f759efb65683db41d3b3b81339d09f2a45f3bb9df1b03a5a8227ce0f1ac1edca35c90bb7a3e78167047e8be6b70cce7851ccd4a43c3ae8d834a89f0eb2f10fcdf5538b838c1a35539f8ff7c1c864805c23c2371a6e09f89369e88beb3b6f2297cd468a10609855225a8ebfdbd29abf8faf1f5f3f91879c7f10b938c8a46be03349fdcda78f63a12ab8e3fc6dd44c2b612f972b8e0a75933d1b1af48aa9291bcc92812f56af210829e4ac4cd97798485856178cf0ef873b3346f39319a96ce8956e57343a3b2ae4ea7c2551aa1ddb8b97c3d3a228c59a7cf6283a75038da733bfe42d3c1937899c6951f31c12f52a521efc627b6cefa30564093c229340f7cfd6bc31506e4ac5908aebeee083f7809b97a3442f495c708d9760e314605f2fd3c08010bf64217ae8357976a78fed301e82314d0abb4385266398c318947eaa328786d3efc816e7a5ea6a03baf9edee3e3eb26cf7deaa9d8bf6c3a0f7b4b330328ff38f9165cfa6f3dc25ebfc48bbb5771ffc261beafb54039fbffb9e08fbdd792ee01e9d3b62116afde62e11b21487f447d9a3e882ef754226eb2ccc3c5c505cb37edc6859347d0b45a4993ae58b0e7ea4b7cbdf93a765e7e9ee9e3eb6fe2ee997db10e6d34c66c1273ea5a3eb07c063c7cf2f332b16fe9341e2465e3f4e1f216c929ed6da8544ca05d03e0e9f11caeae4170740e85ab6720d4da70681c43e1e4fc0a8e9a60f6b0eba092170726feca18b6ee10c5cad16fac0828d80e79a59b2067c00acddf8b1573ca86768c59e8c4c3cbc7b997fadaaffae097610db8c89fdab4d8aac43c22da807f2f3dc7e44dd7121573babf1a562c82d387f660d9967d42ccb31051cfa60c21e86920a99bed4dad4d41ea295fad36769fbc861d6b97a24cc18453d828dffab6f3fe98b8f11a4f98111ca193dfc95ac84b7dede4be5c18293429c593ff7bfa306ee98604c425a6a1086a649953b439134acddb871294ea283879fac3ddf3251c9d82a0750e83a3db2ba6aea7a12de1058ffe4de039b8090c0feec121f029d40a3d1c0c4cd08d3a2eea31ce1130c4e8e5a325ce91d573f83c718ac866eafe7f71ef2a0eaffc11eba70c4a3024401eecfa2c1c0f4f0cba1ffe3ef31493febec62df3d078f9ca89d2f93db1f1f7d9d877f616aad46d226f15642462f832fd105deee980b821331fcaa6f5ebcc2ff1cdf7f3f0e455d2c251aba8179a97f5414e578dbcc5baa0c03203e7eeb4f02637c71480e54db8408d722a7714d2384053b828d445cb2086fd4dccb3475095aa06871c0511635022f2c06544df0882b2567d38e4ca83e09527d87574c0c3c201b8e49635d1d73283172151d87df9058edd49da8932afa7233e1eda0b1f7c35c7226caf2063117567fa22043d1d10e33f590779874f9f300af3ffdc8080d0a4b3b5552ce08e164cd8f367c11cf6b7410e71345fdb14ded4c4e9ad4bb805fc3654ecf62be1a445557705341e6e50d6e8c8a7b2192fee055ebf6662ae813142094398238c9e45e0d8b62342561e43c4f187d0b3c6c0b1b26710e890f1296c339b478111bc6bfdc2a3a41d0a3d9c5418d4a525be98f1139ffb2fc83c44bd99fe08414f27c4cd99b504bd788a496387e1f7f5db79a8cea4289edb05cdcae442a9bcd6352eaa717245a556bd78ac78a3d18807978e263f6639bbbf72392a50c347817c1e3150e42fc75a0915d91b5a8059e6d49b1ec32cf19828150c916c3dd880d04d1711fd548587bea1389ff31c0c4cdaed85ab4f43b8457eeb795c6efbf8386b94e8d1ba01be9dbd08b9fda4803e82cc43d497198310f47444dca4598fffa3fbf87ccc30acd8bc0b918984eb34e1ebe988e66573a18a9f344fdad651b25baf704e152af9a9e0e3ad618a9583e9b937bbefd440a411316106185f1ba1f78f86ee7e04a29f69f158e388f3de7710a2485eac786be7ccfd20ec62429e54743742cd2e54a7a6b53063de22f895600d1f419690545d29eac9b421043d9d11a26e1d3cbc771b9f7e30187fef3cf846af77b2d42a17f4e04960b232b35b7aa0563920bf8f232a97f3465e1f2d1c0c3130461b1013ae474c4834620274303e07a20335b86970c66587400423043149dfb2560f65403b793708671fbce651dede04052a9a3977014a56487ccebf207310629e710841cf00c40d6b3d04bef0c7b42f3fc11f2bd725199cc60439ce91b0d728e2056f17eb74a27b1b14b7ddd3438b4285bc51a8883772796aa1d445c1181a85a8973a3c7e1889dbfe91781a118ef024e69d5b3b8161d13871e7154ede0bc24bd6507913ce5a15bab66d8eaf67cc46c12225e4ad82ac42d48d198b10f40c42dcb8d68541afc7821fbec1bc9f16e0c6a317f2d6a4299ccb19d50b79f22e792766c5db163150a99450ab157074d642abd1c0a83722322c02515106e88d46186cec3ea4b9e3d4a54e227ef745e211ddccc9e3e58a21037ae3d3afbe83ab9b652c7d41d6207a2f331e21e81984b879ad97ed1b5761e6d429d87beab2bce5cd90877cf5c25e289f5f08436673f151308fd97ffe61d29eeae654285e00a3478fc2a00f3e91b708ac01511f660e42d0331071135b37cf1eddc7eca913f1d7dabff1e8e5db05c345abe4163b75cbfbe57096b70ad29b7b2fc3718a89f89907af119648f097f8b83aaad1be45434c983c0de52a89f1716b43d483998710f40c46dcccb6c1b6b57f62eeec1fb0f7f805e80c497bc79ba0f9cbc573bba2586e17fe6aad816b6c81972151b8e91fc6a799ddf40fc5eb88e44da12b5f343f060f1a88e11f7d0e8dd63a93f3647744fd97b90841cf04c44d6d3bbcf47f8a79332663dd864db872ef99bcf5ed783aabf91c772ef23e2ec821043e492800d02d26dc37b9808721283cf9217a7d3c5df04ed386183b61222a56ad296f155823a2decb7c84a06712e2e6b63d6e5c3a8f9f7ef8167f6fdb89872f523657dbdb85045e12f712795cb9e0675748b06f3c0b8db5c003c35216633f7f4e37b46ad200fd868c44bd66efc85b05d68ca8efb20621e89988b8c96d97237bffc52f737fc07f878ee1d1cb10796bf2218bdd64c1176722ef61c702ff9a049c09f72d667d9315fea690bc4951c2d70bcd1ad645bfa12351a3512b79abc01610f55cd621043d931137bbed73f3f205ac5cba08bb76edc2992bb7df1ad02431c8c12e878b0639dd345cec69a171787ab58539f034179c84fa255be895af87b057b63d398e6cf1f166dfbb5a996268dea219baf61e04bf9215e47704b684a8dfb21621e85980b8e9ed8b2d6b9661d3fa353874f424ae3f7c2e6f4d1bb964a13789bcf9ab569df1f3e2a3748658b18efffae22dc15c928356a54099427950bf4e4d747cf73d346ed74d7e4760ab887a2deb11829e45889bdf3e090b09c6ce4d6bb0ebdf6d387ee234aedc7df2c6d0b3a9c549ad809a89a246c916f64a31cae9952f6c9bf45edcb668bd515a0c31d0f157b9cc161ddb26bdc7d6d96bc41b62e0a716374715ca16c9875a35aaa179ab3668d6be9b48536a4788facc3a10829e858887207bb06bf35aecdabe05e7ce9dc7edfb8ff0e8451017507b85e2e317f0f142d142f951ad6a15b468d301759bb797df15d81ba21eb31e84a06731e261c89e5c3a7b12a70eefc3b9b3a771f5ea55dc7ff4144f5fbe467084edc457f774d1c037a7270a17cc87d2a54aa152d56aa851b7118a97ab22ef21b07744fd655d0841b702c4432130111c14888ba78fe3daa5f3b871ed0aeedebdcbc4fe31028382111a1e893026f82191299bf6951adc9cd47075d4c0d5d911393cdd5120bf2f8a142982e2254aa14c85caa856a721d48e225a5e7646d45bd68710742b413c1c8294a0d745233a2a12911111d0454741afd7c16030c068d0c3a037b075f6cacaa66d0a85124a255b542af6ca1695120a7a65db542a358fb4a6d13af245ad1151d7046fe64df51521eaacac4108ba1521445d2010583b42ccad1785fc2ab002def420bced21120804828ce66d468710f3ac4508ba9521445d20105823a207d1fa11826e850851170804d6841073db4008ba9522445d2010580342cc6d0721e856ccdb445d08bb4020c8488498db1642d0ad9cb73d3442d40502417af336834188b9752204dd0610a22e1008328bb7d52742ccad1721e836821075814090d10831b76d84a0db10f430bde98112a22e1008528b1073db4708ba0d22445d2010a4276faa37de664808ac0721e8368a10758140901ebc4dcc05b68388e56ee3886e328140901a44dd617f080bddc679db4327ac758140101f21e6f68910743b4088ba4020482e42cced1721e8764272445d08bb4090bd11626edf8831743b443cb40281c01c5127640f84856e87bcede11496ba40907d10629e7d1016ba1d931ce1163fbf40609f88e73ffb21043d1b205ae80241f6423cf3d913d1e59e0d78dbc34b0f7f725af30281c0fa11629e7d11829e4d48ce432c445d20b05d92d33017626edf882ef76c8878e80502fb423cd30242087a362539d6b8b8350402eb463cc7027384a0677344852010d81ee2b91524861843cfe624e7a14f4ee5211008320721e682a41016ba80935cd116b78b409035886754f03684a00b2c10ad7f81c0fa10cfa52039084117248aa8400482ac473c87829420045d9024a28b4f20c81ac4b327480d42d0056f45542e0241e621ac72416a115eee82b792dcca23b9c22f10081242cf8f1073415a1016ba2045086b5d20485fc43325482f84a00b5285a8840482b4219e21417a23045d902644a52410a48ce43e3384786e04294108ba20cd880a4a20783be23911643442d005e986a8b004828488e7429059084117a43ba2021308c47320c87c84a00b320c51a109b223e2be17641542d005198ea8e004d901719f0bb21a21e8824c2325151e216e4d81b523ee69813521045d90e9884a5060eb887b58608d0841176419a25214d81ae29e15583342d005594e4a2b4942dcb682cc42dc9f025b4108bac06a484dc549885b5890de887b51608b0841175825c22a12640542c805b68c10748155232a58414623ee3181bd20045d6013a4b6d225c42d2e888fb89f04f688107481cd212a63416a10f78dc0de11822eb069d2524913e2f6b75fc4bd21c86e084117d80569adbc09f128d83ee23e10646784a00bec8ef4a8d409f168583fe2b71608e210822eb06bd2abc227c4a392f588df5320481a21e8826c437a8a8109f1f8641ce2f712085286107441b625230483108f54ca11bf8540907684a00b043219252a26c4a396f1d79810d759905d11822e10244266088f39f6f4188a6b2710640d42d005826492d942951459f1c866e7ef2e10d80a42d0058254622d2267cf88ea4920483e42d005827446087dea10559140903684a00b04998010f93844952310640c42d005022bc09e045f54290241d620045d20b031325bfc45152110d80642d00502814020b00314f2ab4020100804021b4608ba4020100804768010748140201008ec0021e80281402010d80142d00502814020b00384a00b04028140600708411708040281c00e10822e10080402811d20045d20100804023b4008ba4020100804768010748140201008ec0021e80281402010d80142d00502814020b00384a00b04028140600708411708040281c00e10822e10080402811d20045d20100804023b4008ba4020100804768010748140201008ec0021e80281402010d80142d00502814020b00384a00b04028140600708411708040281c00e10822e10080402811d20045d20100804023b4008ba4020100804768010748140201008ec0021e80281402010d80142d00502814020b00384a00b04028140600708411708040281c00e10822e10080402811d20045d20100804029b07f83fc8d47890977f12770000000049454e44ae426082, 1, 'Calle San José Local NRO S/N URB PADRE FERRARO SECTOR SEGUNDA SABANA VALLE VERDE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fondo`
--

CREATE TABLE `fondo` (
  `id_fondo` int(11) NOT NULL,
  `fondo` decimal(20,8) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `fondo`
--

INSERT INTO `fondo` (`id_fondo`, `fondo`, `id_usuario`) VALUES
(1, 543240074.16621730, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gastos`
--

CREATE TABLE `gastos` (
  `id_gastos` int(11) NOT NULL,
  `monto_gasto` decimal(12,2) NOT NULL,
  `fecha_gasto` date NOT NULL,
  `id_fondo` int(11) DEFAULT NULL,
  `id_categoria_gasto` int(11) NOT NULL,
  `descripcion_gasto` varchar(100) NOT NULL,
  `estado_gasto` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `gastos`
--

INSERT INTO `gastos` (`id_gastos`, `monto_gasto`, `fecha_gasto`, `id_fondo`, `id_categoria_gasto`, `descripcion_gasto`, `estado_gasto`) VALUES
(8, 20.00, '2025-05-09', NULL, 3, 'Pago de Internet', '1'),
(9, 200.00, '2025-05-20', NULL, 4, 'Comida Semanal', '1'),
(10, 2.00, '2025-05-30', NULL, 4, 'transporte', '1'),
(12, 45.00, '2025-07-09', NULL, 4, 'dddf', '1'),
(13, 44.00, '2025-07-09', NULL, 6, 'n;', '1'),
(14, 30.00, '2025-07-09', NULL, 8, 'daño de auto', '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `iva`
--

CREATE TABLE `iva` (
  `id_iva` int(11) NOT NULL,
  `nombre_iva` varchar(40) NOT NULL,
  `valor_iva` decimal(5,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `iva`
--

INSERT INTO `iva` (`id_iva`, `nombre_iva`, `valor_iva`) VALUES
(1, 'General', 16.00),
(2, 'Reducido', 8.00),
(3, 'Exento', 0.00);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nomina`
--

CREATE TABLE `nomina` (
  `id_nomina` int(11) NOT NULL,
  `pago_neto` decimal(15,4) NOT NULL,
  `fecha_nom` date NOT NULL,
  `id_tasa_dolar` int(11) NOT NULL,
  `id_fondo` int(11) NOT NULL,
  `cedula_emple` varchar(14) NOT NULL,
  `horas_extra` decimal(10,4) NOT NULL,
  `dias_feriados` decimal(10,4) NOT NULL,
  `bono_ali` decimal(15,4) NOT NULL,
  `bono_produc` decimal(15,4) NOT NULL,
  `SSO_total` decimal(15,6) NOT NULL,
  `FAOV_total` decimal(15,6) NOT NULL,
  `sueldo_deduc` decimal(15,4) NOT NULL,
  `total_deduc` decimal(15,4) NOT NULL,
  `sueldo_neto` decimal(15,4) NOT NULL,
  `pago_total` decimal(15,4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `nomina`
--

INSERT INTO `nomina` (`id_nomina`, `pago_neto`, `fecha_nom`, `id_tasa_dolar`, `id_fondo`, `cedula_emple`, `horas_extra`, `dias_feriados`, `bono_ali`, `bono_produc`, `SSO_total`, `FAOV_total`, `sueldo_deduc`, `total_deduc`, `sueldo_neto`, `pago_total`) VALUES
(60, 2171.2500, '2025-05-20', 19, 1, '28561075', 0.0000, 434.2500, 1737.0000, 1302.7500, 10.856250, 21.712500, 2138.6813, 32.5688, 5645.2500, 5612.6813),
(61, 22.9029, '2025-05-20', 19, 1, '28561075', 0.0000, 4.5806, 18.3223, 13.7417, 0.114514, 0.229029, 22.5594, 0.3435, 59.5475, 59.2040),
(62, 130.0000, '2025-05-20', 19, 1, '28561075', 2.0000, 0.0000, 40.0000, 30.0000, 0.650000, 1.300000, 128.0500, 1.9500, 202.0000, 200.0500),
(63, 130.0000, '2025-05-20', 19, 1, '28561075', 0.0000, 0.0000, 40.0000, 30.0000, 0.650000, 1.300000, 128.0500, 1.9500, 200.0000, 198.0500),
(64, 130.0000, '2025-05-20', 19, 1, '30107693', 5.0000, 2.0000, 40.0000, 20.0000, 0.650000, 1.300000, 128.0500, 1.9500, 197.0000, 195.0500),
(65, 200.0000, '2025-05-20', 19, 1, '28561075', 20.0000, 50.0000, 5.0000, 10.0000, 1.000000, 2.000000, 197.0000, 3.0000, 285.0000, 282.0000),
(66, 20.0000, '2025-05-20', 19, 1, '30107693', 5.0000, 10.0000, 40.0000, 10.0000, 0.100000, 0.200000, 19.7000, 0.3000, 85.0000, 84.7000),
(67, 10.0000, '2025-05-20', 19, 1, '30107693', 4.0000, 10.0000, 20.0000, 48.0000, 0.050000, 0.100000, 9.8500, 0.1500, 92.0000, 91.8500),
(68, 20.0000, '2025-05-20', 19, 1, '28561075', 0.3571, 4.2857, 40.0000, 10.0000, 0.100000, 0.200000, 19.7000, 0.3000, 74.6429, 74.3429),
(69, 30.0000, '2025-05-21', 19, 1, '28561075', 2.6786, 12.8571, 40.0000, 10.0000, 0.150000, 0.300000, 29.5500, 0.4500, 95.5357, 95.0857),
(70, 100.0000, '2025-05-21', 19, 1, '28561075', 8.9286, 42.8571, 40.0000, 20.0000, 0.015822, 0.003516, 99.9807, 0.0193, 211.7857, 211.7664),
(71, 20.0000, '2025-05-21', 19, 1, '30107693', 0.7143, 4.2857, 40.0000, 10.0000, 0.015822, 0.003516, 19.9807, 0.0193, 75.0000, 74.9807),
(72, 45.0000, '2025-05-30', 19, 1, '30107693', 1.6071, 19.2857, 40.0000, 10.0000, 0.015822, 0.003516, 44.9807, 0.0193, 115.8929, 115.8735),
(73, 12.0000, '2025-06-24', 24, 1, '28561075', 0.4286, 2.5714, 12.0000, 10.0000, 0.014683, 0.003263, 11.9821, 0.0179, 37.0000, 36.9821),
(74, 30.0000, '2025-07-01', 24, 1, '30107693', 2.1429, 12.8571, 10.0000, 10.0000, 0.014683, 0.003263, 29.9821, 0.0179, 65.0000, 64.9821),
(75, 30.0000, '2025-07-01', 26, 1, '28561075', 1.6071, 0.0000, 10.0000, 10.0000, 0.013889, 0.003086, 29.9830, 0.0170, 51.6071, 51.5902),
(76, 50.0000, '2025-07-09', 26, 1, '28561075', 2.6786, 10.7143, 40.0000, 20.0000, 0.013889, 0.003086, 49.9830, 0.0170, 123.3929, 123.3759),
(77, 10.0000, '2025-07-09', 26, 1, '28561075', 0.1786, 2.1429, 120.0000, 120.0000, 0.013889, 0.003086, 9.9830, 0.0170, 252.3214, 252.3045),
(78, 20.0000, '2025-07-09', 26, 1, '28561075', 0.7143, 4.2857, 40.0000, 10.0000, 0.013889, 0.003086, 19.9830, 0.0170, 75.0000, 74.9830),
(79, 50.0000, '2025-08-06', 33, 1, '28561075', 3.5714, 21.4286, 40.0000, 10.0000, 0.011697, 0.002599, 49.9857, 0.0143, 125.0000, 124.9857);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_compras_credito`
--

CREATE TABLE `pagos_compras_credito` (
  `id_pago_compra_credito` int(11) NOT NULL,
  `id_compra_credito` int(11) NOT NULL,
  `fecha_pago` date NOT NULL,
  `monto_pago` decimal(12,2) NOT NULL,
  `id_fondo_origen` int(11) NOT NULL,
  `id_tipo_pago` int(11) DEFAULT NULL,
  `monto_moneda_pago` decimal(12,2) DEFAULT NULL,
  `codigo_moneda_pago` varchar(3) DEFAULT NULL,
  `tasa_cambio_aplicada` decimal(18,6) DEFAULT NULL,
  `referencia_pago` varchar(100) DEFAULT NULL,
  `id_usuario_registro_pago` int(11) NOT NULL,
  `fecha_registro_pago` timestamp NOT NULL DEFAULT current_timestamp(),
  `notas_pago` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `pagos_compras_credito`
--

INSERT INTO `pagos_compras_credito` (`id_pago_compra_credito`, `id_compra_credito`, `fecha_pago`, `monto_pago`, `id_fondo_origen`, `id_tipo_pago`, `monto_moneda_pago`, `codigo_moneda_pago`, `tasa_cambio_aplicada`, `referencia_pago`, `id_usuario_registro_pago`, `fecha_registro_pago`, `notas_pago`) VALUES
(1, 2, '2025-05-21', 150.00, 1, NULL, NULL, NULL, NULL, '0', 1, '2025-05-21 02:41:02', 'mitad'),
(2, 4, '2025-05-21', 50.00, 1, NULL, NULL, NULL, NULL, '0', 1, '2025-05-21 14:59:26', 'se cancelo la mitad de la deuda'),
(3, 4, '2025-05-21', 31.64, 1, 3, 3000.00, 'BS', 94.802400, '5050', 1, '2025-05-21 16:07:26', 'pgo lo que tenia'),
(4, 4, '2025-05-21', 18.36, 1, 1, 1741.00, 'BS', 94.802400, '0', 1, '2025-05-21 16:15:58', ''),
(5, 6, '2025-05-30', 0.02, 1, 2, 2.37, 'BS', 94.802400, '8744', 1, '2025-05-30 19:17:44', 'pagado'),
(6, 6, '2025-05-30', 24.98, 1, 6, 24.98, 'USD', 1.000000, '0', 1, '2025-05-30 19:18:35', ''),
(8, 5, '2025-06-03', 10.00, 1, 6, NULL, NULL, NULL, '0', 1, '2025-06-03 16:12:04', ''),
(13, 5, '2025-07-09', 4.63, 1, 1, 500.00, 'BS', 108.000000, '0', 1, '2025-07-09 03:57:07', ''),
(14, 2, '2025-07-09', 27.78, 1, 5, 3000.00, 'BS', 108.000000, '0', 1, '2025-07-09 05:24:10', ''),
(16, 8, '2025-09-24', 0.90, 1, 2, NULL, NULL, NULL, '8383', 1, '2025-09-24 00:12:00', ''),
(17, 10, '2025-09-24', 20.00, 1, 4, 2000.00, '0', NULL, '', 1, '2025-09-24 00:31:54', ''),
(18, 10, '2025-09-24', 12.01, 1, 4, 2000.00, '0', NULL, '', 1, '2025-09-24 00:32:29', ''),
(19, 10, '2025-09-24', 5.00, 1, 6, 5.00, '0', NULL, '', 1, '2025-09-24 00:33:08', ''),
(20, 10, '2025-09-24', 1.80, 1, 4, 300.00, '0', 166.583400, '', 1, '2025-09-24 00:50:11', ''),
(21, 10, '2025-09-24', 0.90, 1, 2, 150.00, 'BS', 166.583400, '', 1, '2025-09-24 00:55:55', ''),
(22, 10, '2025-09-24', 0.29, 1, 5, 48.00, 'BS', 166.583400, '', 1, '2025-09-24 00:58:30', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_venta`
--

CREATE TABLE `pagos_venta` (
  `id_pago_venta` int(11) NOT NULL,
  `id_ventas` int(11) NOT NULL,
  `id_tipo_pago` int(11) NOT NULL,
  `monto_pagado_moneda_principal` decimal(14,2) NOT NULL,
  `monto_transaccion` decimal(14,2) DEFAULT NULL,
  `codigo_moneda_transaccion` varchar(3) DEFAULT NULL,
  `id_tasa_dolar_aplicada` int(11) DEFAULT NULL,
  `referencia_pago` varchar(100) DEFAULT NULL,
  `fecha_pago` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_usuario_registro` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `pagos_venta`
--

INSERT INTO `pagos_venta` (`id_pago_venta`, `id_ventas`, `id_tipo_pago`, `monto_pagado_moneda_principal`, `monto_transaccion`, `codigo_moneda_transaccion`, `id_tasa_dolar_aplicada`, `referencia_pago`, `fecha_pago`, `id_usuario_registro`) VALUES
(4, 5, 6, 0.23, 0.23, 'USD', NULL, NULL, '2025-06-18 10:11:46', 4),
(5, 6, 6, 91.80, 91.80, 'USD', NULL, NULL, '2025-06-18 10:14:07', 4),
(6, 8, 5, 0.16, 16.00, 'BS', 24, NULL, '2025-06-18 11:09:25', 4),
(7, 8, 6, 1.00, 1.00, 'USD', NULL, NULL, '2025-06-18 11:09:25', 4),
(8, 9, 5, 5.34, 546.00, 'BS', 24, NULL, '2025-06-18 18:26:49', 4),
(9, 9, 6, 2.00, 2.00, 'USD', NULL, NULL, '2025-06-18 18:26:49', 4),
(10, 10, 5, 3.92, 400.00, 'BS', 24, NULL, '2025-06-18 19:01:30', 4),
(11, 10, 6, 2.00, 2.00, 'USD', NULL, NULL, '2025-06-18 19:01:30', 4),
(12, 12, 5, 0.85, 87.00, 'BS', 24, NULL, '2025-06-25 14:40:50', 1),
(13, 12, 6, 12.00, 12.00, 'USD', NULL, NULL, '2025-06-25 14:40:50', 1),
(14, 7, 6, 3.00, 3.00, 'USD', NULL, '5050', '2025-06-25 14:52:09', 1),
(15, 13, 2, 1.28, 130.76, 'BS', 24, NULL, '2025-07-01 15:45:25', 4),
(16, 14, 4, 0.84, 85.81, 'BS', 24, NULL, '2025-07-01 15:47:12', 4),
(17, 14, 6, 1.00, 1.00, 'USD', NULL, NULL, '2025-07-01 15:47:12', 4),
(18, 15, 4, 19.58, 2000.00, 'BS', 24, '', '2025-07-01 15:50:14', 4),
(19, 15, 6, 20.00, 20.00, 'USD', NULL, '', '2025-07-01 15:50:58', 4),
(20, 16, 6, 1.84, 1.84, 'USD', NULL, NULL, '2025-07-02 02:22:04', 4),
(21, 17, 5, 1.17, 126.00, 'BS', 26, NULL, '2025-07-02 03:05:04', 4),
(22, 18, 6, 1.00, 108.00, 'BS', 26, NULL, '2025-07-02 12:44:30', 4),
(23, 18, 4, 2.00, 2.00, 'USD', NULL, NULL, '2025-07-02 12:44:30', 4),
(24, 19, 5, 1.85, 200.00, 'BS', 26, NULL, '2025-07-02 12:46:05', 4),
(25, 19, 4, 0.65, 70.00, 'BS', 26, NULL, '2025-07-02 12:46:05', 4),
(26, 19, 6, 5.00, 5.00, 'USD', NULL, NULL, '2025-07-02 12:46:05', 4),
(27, 20, 4, 1.07, 116.00, 'BS', 26, NULL, '2025-07-02 14:33:18', 4),
(28, 20, 5, 0.93, 100.00, 'BS', 26, NULL, '2025-07-02 14:33:18', 4),
(29, 20, 6, 1.00, 1.00, 'USD', NULL, NULL, '2025-07-02 14:33:18', 4),
(30, 21, 3, 2.55, 275.00, 'BS', 26, NULL, '2025-07-02 14:34:12', 4),
(31, 21, 6, 5.00, 5.00, 'USD', NULL, NULL, '2025-07-02 14:34:12', 4),
(32, 22, 6, 20.00, 20.00, 'USD', NULL, NULL, '2025-07-08 17:56:00', 4),
(33, 22, 2, 18.52, 2000.00, 'BS', 26, NULL, '2025-07-08 17:56:00', 4),
(34, 22, 4, 4.58, 495.00, 'BS', 26, NULL, '2025-07-08 17:56:00', 4),
(35, 23, 6, 10.00, 10.00, 'USD', NULL, NULL, '2025-07-08 19:05:49', 4),
(36, 23, 1, 13.89, 1500.00, 'BS', 26, NULL, '2025-07-08 19:05:49', 4),
(37, 23, 4, 6.63, 716.16, 'BS', 26, NULL, '2025-07-08 19:05:49', 4),
(38, 24, 1, 1.85, 200.00, 'BS', 26, NULL, '2025-07-08 19:38:52', 1),
(39, 25, 1, 2.96, 320.00, 'BS', 26, NULL, '2025-07-09 03:47:14', 1),
(40, 25, 6, 1.36, 1.36, 'USD', NULL, NULL, '2025-07-09 03:47:14', 1),
(41, 15, 6, 6.00, 6.00, 'USD', NULL, '', '2025-07-09 03:48:10', 1),
(42, 26, 1, 1.30, 139.97, 'BS', 26, NULL, '2025-07-09 12:57:40', 1),
(43, 27, 1, 8.86, 1000.00, 'BS', 27, NULL, '2025-07-09 13:17:12', 5),
(44, 27, 6, 10.00, 10.00, 'USD', NULL, NULL, '2025-07-09 13:17:12', 5),
(45, 27, 4, 6.20, 700.00, 'BS', 27, NULL, '2025-07-09 13:17:12', 5),
(46, 27, 2, 0.64, 72.21, 'BS', 27, NULL, '2025-07-09 13:17:12', 5),
(49, 30, 4, 1101.60, 141270.18, 'BS', 33, NULL, '2025-08-05 23:49:38', 1),
(51, 37, 4, 0.01, 1.00, 'BS', 35, NULL, '2025-09-03 16:25:51', 2),
(52, 38, 6, 4.50, 4.50, 'USD', NULL, NULL, '2025-09-03 16:30:21', 2),
(53, 39, 4, 3.83, 558.00, 'BS', 35, NULL, '2025-09-03 16:43:47', 2),
(54, 40, 4, 0.01, 1.00, 'BS', 35, NULL, '2025-09-03 18:35:28', 2),
(55, 41, 4, 0.01, 1.00, 'BS', 35, NULL, '2025-09-20 00:01:52', 1),
(56, 42, 6, 5.00, 5.00, 'USD', NULL, NULL, '2025-09-21 03:27:15', 2),
(57, 42, 5, 2.54, 423.00, 'BS', 36, NULL, '2025-09-21 03:27:15', 2),
(58, 43, 4, 0.01, 1.00, 'BS', 36, NULL, '2025-09-22 13:00:50', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `perdida`
--

CREATE TABLE `perdida` (
  `id_perdida` int(11) NOT NULL,
  `cant` decimal(10,2) NOT NULL,
  `precio_perdida` decimal(12,2) NOT NULL,
  `fecha_perdida` date NOT NULL,
  `id_pro` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `perdida`
--

INSERT INTO `perdida` (`id_perdida`, `cant`, `precio_perdida`, `fecha_perdida`, `id_pro`) VALUES
(6, 20.00, 16.00, '2025-05-13', 25),
(7, 50.00, 25.00, '2025-05-13', 24),
(8, 10.00, 5.00, '2025-05-13', 24),
(9, 10.00, 8.00, '2025-05-20', 27),
(10, 25.00, 12.50, '2025-05-30', 30),
(11, 2.00, 1.60, '2025-06-24', 27),
(12, 2.00, 1.20, '2025-07-09', 34),
(13, 3.00, 2.10, '2025-07-09', 25),
(14, 2.00, 1.40, '2025-09-03', 25),
(15, 5.00, 5.00, '2025-09-24', 49);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id_pro` int(11) NOT NULL,
  `nombre_producto` varchar(40) NOT NULL,
  `cantidad` decimal(10,2) NOT NULL,
  `precio` decimal(12,3) NOT NULL,
  `costo` decimal(11,3) NOT NULL,
  `codigo` varchar(30) NOT NULL,
  `codigo_barras` varchar(50) DEFAULT NULL,
  `descrip_prod` varchar(100) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  `id_fondo` int(11) DEFAULT NULL,
  `estado_producto` varchar(10) NOT NULL,
  `id_iva` int(11) NOT NULL,
  `id_tipo_cuenta` int(11) NOT NULL,
  `ganancia` decimal(10,0) NOT NULL,
  `materia_prima` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id_pro`, `nombre_producto`, `cantidad`, `precio`, `costo`, `codigo`, `codigo_barras`, `descrip_prod`, `id_categoria`, `id_fondo`, `estado_producto`, `id_iva`, `id_tipo_cuenta`, `ganancia`, `materia_prima`) VALUES
(24, 'Papas', 298.00, 0.875, 0.700, 'P-A', '', 'Papas sabor ajo', 12, NULL, '1', 2, 2, 25, 0),
(25, 'Barquillas', 99999994.00, 1.700, 0.000, 'B-1', NULL, 'Barquillas Pequeñas', 9, NULL, '1', 2, 1, 0, 0),
(26, 'Barquillones', 99999999.00, 1.500, 0.000, 'B-2', NULL, 'Barquillas Grandes', 9, NULL, '1', 3, 1, 0, 0),
(27, 'Cestas', 99999997.00, 1.100, 0.000, 'C-1', NULL, 'Cestas de dos porciones', 9, NULL, '1', 1, 1, 0, 0),
(28, 'Copa de Helados', 99999999.00, 0.200, 0.000, 'C-H', NULL, 'Copas con dos porciones de helado', 9, NULL, '1', 2, 1, 0, 0),
(29, 'Tinitas', 99999999.00, 1.070, 0.000, 'T-1', NULL, 'Tinitas sabores surtidos', 9, NULL, '1', 2, 1, 0, 0),
(30, 'Banana split', 99999999.00, 1.000, 0.000, 'B-3', NULL, 'Banana split', 9, NULL, '1', 1, 1, 0, 0),
(31, 'Tortas frías', 0.00, 0.800, 0.000, 'T-1', NULL, 'Tortas Frias', 10, NULL, '1', 2, 2, 0, 0),
(32, 'Tortas caseras', 0.00, 0.800, 0.000, 'T-C', NULL, 'Tortas caseras', 10, NULL, '1', 2, 2, 0, 0),
(33, 'Yogures', 0.00, 0.800, 0.000, 'Y-1', NULL, 'Yogures surtidos', 9, NULL, '1', 2, 2, 0, 0),
(34, 'Masa facil', 296.00, 0.800, 0.000, 'M-F', NULL, 'Masa facil', 11, NULL, '1', 2, 2, 0, 0),
(35, 'Bandeja de tequeños', 200.00, 0.800, 0.000, 'B-T', NULL, 'Bandeja de tequeños', 11, NULL, '1', 2, 2, 0, 0),
(36, 'Refrescos', 50.00, 0.900, 0.000, 'R-1', NULL, 'Refrescos de sabores surtidos', 8, NULL, '1', 2, 2, 0, 0),
(37, 'Café expreso', 97.00, 0.700, 0.000, 'C-1', NULL, 'Café expreso', 8, NULL, '1', 2, 2, 0, 0),
(39, 'Pasta seca', 99999999.00, 0.200, 0.000, 'a-p', NULL, 'Pasta seca de 20 unidades', 11, NULL, '1', 1, 1, 0, 0),
(40, 'Jamon', 0.00, 0.340, 0.000, 'Jamon', NULL, 'Jamon de Pavo', 11, NULL, '1', 2, 2, 0, 0),
(41, 'Galletas', 99999999.00, 0.600, 0.000, 'll', NULL, 'Galletas cubiertas de chocolate', 11, NULL, '1', 1, 1, 0, 0),
(42, 'Mani', 1000.00, 0.300, 0.300, 'M-2', NULL, 'Bolsa de mani granulado', 12, NULL, '1', 1, 2, 0, 0),
(43, 'cuaderno', 241.00, 0.979, 0.890, 'N-2', NULL, 'cuaderno de ivan', 13, NULL, '1', 1, 2, 10, 0),
(44, 'jojoto', 136.00, 2.520, 1.680, 'JJ-1', NULL, 'Jojotos salteados', 11, NULL, '1', 2, 2, 50, 0),
(45, 'almendras', 0.00, 0.952, 0.680, 'A-MM', NULL, 'almendras dulces', 12, NULL, '1', 1, 2, 40, 0),
(46, 'pepito', 0.00, 0.650, 0.500, 'P-34', NULL, 'pepito de colores', 12, NULL, '1', 1, 2, 30, 0),
(47, 'Perras', 4.00, 0.394, 0.340, 'pe-e', NULL, 'perra de ivan', 13, NULL, '1', 1, 2, 16, 0),
(48, 'cheestris', 837.00, 1.710, 0.900, 'ch8', NULL, 'wdw', 12, NULL, '1', 1, 2, 90, 0),
(49, 'leche', 95.00, 1.000, 1.000, 'L-Pyy', NULL, 'leche descremada', 13, NULL, '1', 1, 2, 0, 0),
(50, 'isolante', 0.00, 1.000, 1.000, 'II-30', '6878597000628', 'suplemento', 8, NULL, '1', 1, 2, 0, 0),
(51, 'mantecado', 0.00, 5.000, 5.000, 'M-A45', 'BC1758037165210251', 'pote de mantecado de 1kg', 9, NULL, '1', 1, 2, 0, 1),
(52, 'edinson', 490.00, 0.650, 0.500, 'E-son', 'BC1758424765807848', 'edinsones', 13, NULL, '1', 1, 2, 30, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto_proveedor`
--

CREATE TABLE `producto_proveedor` (
  `id_producto_proveedor` int(11) NOT NULL,
  `RIF` varchar(15) NOT NULL,
  `id_pro` int(11) NOT NULL,
  `costo_compra` decimal(12,3) NOT NULL,
  `cantidad_compra` decimal(10,2) NOT NULL,
  `fecha` date NOT NULL,
  `id_compra_credito_fk` int(11) DEFAULT NULL,
  `id_tipo_pago_contado` int(11) DEFAULT NULL,
  `monto_moneda_pago_contado` decimal(12,2) DEFAULT NULL,
  `codigo_moneda_pago_contado` varchar(3) DEFAULT NULL,
  `tasa_cambio_aplicada_contado` decimal(18,6) DEFAULT NULL,
  `referencia_pago_contado` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `producto_proveedor`
--

INSERT INTO `producto_proveedor` (`id_producto_proveedor`, `RIF`, `id_pro`, `costo_compra`, `cantidad_compra`, `fecha`, `id_compra_credito_fk`, `id_tipo_pago_contado`, `monto_moneda_pago_contado`, `codigo_moneda_pago_contado`, `tasa_cambio_aplicada_contado`, `referencia_pago_contado`) VALUES
(20, 'R-301076935', 25, 0.800, 50.00, '2025-05-13', NULL, NULL, NULL, NULL, NULL, NULL),
(21, 'R-301076935', 24, 0.500, 1.00, '2025-05-13', NULL, NULL, NULL, NULL, NULL, NULL),
(22, 'R-301076935', 24, 0.600, 1.00, '2025-05-13', NULL, NULL, NULL, NULL, NULL, NULL),
(24, 'R-301076935', 24, 0.800, 0.00, '2025-05-13', NULL, NULL, NULL, NULL, NULL, NULL),
(25, 'R-87654321', 26, 0.800, 200.00, '2025-05-20', NULL, NULL, NULL, NULL, NULL, NULL),
(26, 'R-285610759', 27, 0.800, 0.00, '2025-05-20', NULL, NULL, NULL, NULL, NULL, NULL),
(27, 'R-285610759', 26, 0.800, 400.00, '2025-05-21', 2, NULL, NULL, NULL, NULL, NULL),
(28, 'R-285610759', 26, 0.800, 100.00, '2025-05-21', 3, NULL, NULL, NULL, NULL, NULL),
(29, 'R-12345678', 25, 0.500, 200.00, '2025-05-21', 4, NULL, NULL, NULL, NULL, NULL),
(30, 'R-12345678', 25, 0.700, 50.00, '2025-05-21', NULL, 2, 3318.00, 'BS', 94.802400, '49494'),
(31, 'R-12345678', 26, 0.200, 150.00, '2025-05-21', NULL, 4, 2844.00, 'BS', 94.802400, ''),
(32, 'R-12345678', 26, 0.300, 150.00, '2025-05-21', 5, NULL, NULL, NULL, NULL, NULL),
(33, 'R-285610759', 28, 1.000, 15.00, '2025-05-22', NULL, 6, 15.00, 'USD', 1.000000, '1234567'),
(34, 'R-285610759', 29, 1.200, 100.00, '2025-05-30', NULL, 6, 120.00, 'USD', 1.000000, 'pago exitoso'),
(35, 'R-285610759', 30, 0.500, 50.00, '2025-05-30', 6, NULL, NULL, NULL, NULL, NULL),
(36, 'R-50528502-5', 37, 0.500, 100.00, '2025-06-24', NULL, 6, 50.00, 'USD', 1.000000, 'pagp'),
(37, 'R-301076935', 36, 0.800, 50.00, '2025-06-24', NULL, 5, 4086.00, 'BS', 102.157000, '49494'),
(38, 'R-50528502-5', 34, 0.600, 300.00, '2025-07-09', NULL, 5, 19440.00, 'BS', 108.000000, ''),
(39, 'R-50528502-57', 35, 0.800, 200.00, '2025-07-09', NULL, 2, 17280.00, 'BS', 108.000000, '453'),
(40, 'R-77676768', 24, 0.900, 300.00, '2025-07-09', NULL, 6, 270.00, 'USD', 1.000000, ''),
(41, 'R-3080540', 24, 1.500, 10.00, '2025-07-09', NULL, 6, 15.00, 'USD', 1.000000, ''),
(42, 'R-3080540', 42, 0.870, 200.00, '2025-08-12', NULL, 4, 22313.92, 'BS', 128.240900, '546'),
(43, 'R-3080540', 43, 1.680, 58.00, '2025-08-12', NULL, 6, 97.44, 'USD', 1.000000, '565'),
(44, 'R-3080540', 43, 5.000, 60.00, '2025-08-12', NULL, 6, 300.00, 'USD', 1.000000, ''),
(45, 'R-50528502-57', 43, 1.600, 60.00, '2025-08-12', NULL, 4, 12311.13, 'BS', 128.240900, '323'),
(46, 'R-50528502-57', 44, 2.750, 50.00, '2025-08-12', NULL, 6, 137.50, 'USD', 1.000000, ''),
(47, 'R-50528502-57', 44, 2.750, 1.00, '2025-08-12', NULL, 6, 2.75, 'USD', 1.000000, ''),
(48, 'R-50528502-57', 43, 1.250, 1.00, '2025-08-12', NULL, 6, 1.25, 'USD', 1.000000, ''),
(49, 'R-3080540', 44, 0.900, 50.00, '2025-08-12', NULL, 6, 45.00, 'USD', 1.000000, ''),
(50, 'R-50528502-57', 43, 0.998, 50.00, '2025-08-12', NULL, 6, 49.90, 'USD', 1.000000, ''),
(51, 'R-3080540', 44, 1.250, 5.00, '2025-08-12', NULL, 6, 6.25, 'USD', 1.000000, ''),
(52, 'R-285610759', 42, 0.250, 300.00, '2025-08-12', NULL, 4, 9618.07, 'BS', 128.240900, ''),
(53, 'R-285610759', 42, 0.300, 500.00, '2025-08-12', 7, NULL, NULL, NULL, NULL, NULL),
(54, 'R-285610759', 43, 0.890, 12.00, '2025-08-12', NULL, 4, 7832.95, 'BS', 128.240900, ''),
(55, 'R-285610759', 44, 1.680, 30.00, '2025-08-12', NULL, 4, 7832.95, 'BS', 128.240900, ''),
(56, 'R-301076935', 47, 0.340, 4.00, '2025-08-21', 8, NULL, NULL, NULL, NULL, NULL),
(57, 'R-3080540', 48, 0.900, 800.00, '2025-09-15', NULL, 6, 720.00, 'USD', 1.000000, ''),
(58, 'R-3080540', 48, 0.900, 7.00, '2025-09-16', NULL, 6, 6.30, 'USD', 1.000000, ''),
(59, 'R-3080540', 48, 0.900, 18.00, '2025-09-16', NULL, 6, 16.20, 'USD', 1.000000, ''),
(60, 'R-3080540', 48, 0.900, 22.00, '2025-09-16', NULL, 6, 19.80, 'USD', 1.000000, ''),
(61, 'R-50528502-57', 52, 0.500, 500.00, '2025-09-20', NULL, 4, 36436.33, 'BS', 145.745300, ''),
(62, 'R-3080540', 49, 1.000, 60.00, '2025-09-23', 9, NULL, NULL, NULL, NULL, NULL),
(63, 'R-3080540', 49, 1.000, 40.00, '2025-09-23', 10, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedor`
--

CREATE TABLE `proveedor` (
  `RIF` varchar(15) NOT NULL,
  `nombre_provedor` varchar(40) NOT NULL,
  `telefono_pro` varchar(20) NOT NULL,
  `correo_pro` varchar(40) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `estado_proveedor` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `proveedor`
--

INSERT INTO `proveedor` (`RIF`, `nombre_provedor`, `telefono_pro`, `correo_pro`, `id_usuario`, `estado_proveedor`) VALUES
('R-285610759', 'COMERCIALIZADORA LA PASTORA', '51748954943', 'lapastora@gmail.com', NULL, '1'),
('R-301076935', 'DISCOLA ANDINA', '4146789054', 'discola@gmail.com', NULL, '1'),
('R-3080540', 'ivan', '04247453347', 'ivantru04@gmail.com', NULL, '1'),
('R-312287640', 'LA CASA DE LA REPEPOSTERIA', '4146789054', 'lacasadelareposteria@gmail.com', NULL, '1'),
('R-410360461', 'COMERCIALIZADORA 4D CA', '4146789054', 'COMERCIALIZADORA@gmail.com', NULL, '1'),
('R-50528502-57', 'ARASIMPOR. CA', '4127654321', 'ARASIMPOR@gmail.com', NULL, '1'),
('R-7537367363', 'CONFITERIA LA GUACAMAYA CA', '4146789054', 'laguacamaya@gmail.com', NULL, '1'),
('R-77676768', 'PAPAS JHONS', '4145678938', 'papasjhon@gmail.com', NULL, '1'),
('RIF-J301234567', 'LACTEOS LOS ANDES', '4247453347', 'lacteoslosandes@gmail.com', NULL, '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tasa_dolar`
--

CREATE TABLE `tasa_dolar` (
  `id_tasa_dolar` int(11) NOT NULL,
  `tasa_dolar` decimal(12,4) NOT NULL,
  `fecha_dolar` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `tasa_dolar`
--

INSERT INTO `tasa_dolar` (`id_tasa_dolar`, `tasa_dolar`, `fecha_dolar`, `id_usuario`) VALUES
(19, 94.8024, '2025-05-20 00:05:09', 1),
(20, 96.0000, '2025-05-31 01:44:53', 1),
(21, 96.8500, '2025-05-31 01:45:14', 1),
(22, 99.0916, '2025-06-08 23:38:22', 1),
(23, 99.6983, '2025-06-11 02:33:50', 1),
(24, 102.1570, '2025-06-16 11:40:30', 1),
(25, 107.0000, '2025-07-01 21:52:33', 1),
(26, 108.0000, '2025-07-01 21:52:44', 1),
(27, 112.8285, '2025-07-09 13:11:14', 1),
(28, 20.0000, '2025-07-22 21:24:27', 1),
(29, 20.0000, '2025-07-22 21:24:40', 1),
(30, 119.0000, '2025-07-22 21:24:59', 1),
(31, 122.1700, '2025-07-28 21:44:51', 1),
(32, 126.2802, '2025-08-04 18:00:54', 1),
(33, 128.2409, '2025-08-05 23:47:14', 1),
(34, 139.4016, '2025-08-20 23:59:24', 1),
(35, 145.7453, '2025-08-27 23:15:29', 1),
(36, 166.5834, '2025-09-21 03:21:36', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_cuenta`
--

CREATE TABLE `tipo_cuenta` (
  `id_tipo_cuenta` int(11) NOT NULL,
  `nom_cuenta` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `tipo_cuenta`
--

INSERT INTO `tipo_cuenta` (`id_tipo_cuenta`, `nom_cuenta`) VALUES
(1, 'Stock Siempre en Existencia'),
(2, 'Stock Contable');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_pago`
--

CREATE TABLE `tipo_pago` (
  `id_tipo_pago` int(11) NOT NULL,
  `tipo_pago` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `tipo_pago`
--

INSERT INTO `tipo_pago` (`id_tipo_pago`, `tipo_pago`) VALUES
(1, 'Efectivo (BS)'),
(2, 'Pago Móvil (BS)'),
(3, 'Transferencia (BS)'),
(4, 'Punto de Venta (BS)'),
(5, 'Biopago (BS)'),
(6, 'Efectivo (USD)'),
(10, 'Otros');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_usuario`
--

CREATE TABLE `tipo_usuario` (
  `id_tipo_usuario` int(11) NOT NULL,
  `tipo_usuario` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `tipo_usuario`
--

INSERT INTO `tipo_usuario` (`id_tipo_usuario`, `tipo_usuario`) VALUES
(1, 'Gerente'),
(2, 'Cajero');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(40) NOT NULL,
  `clave` varchar(255) NOT NULL,
  `id_tipo_usuario` int(11) NOT NULL,
  `estado_usuario` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre_usuario`, `clave`, `id_tipo_usuario`, `estado_usuario`) VALUES
(1, 'ivanj', 'm5uplaM=', 1, '1'),
(2, 'Damaris', 'pHaUoZY=', 2, '1'),
(4, 'Keilyn', 'n32YnaGvpYM=', 2, '1'),
(5, 'Hector', '12345678', 2, '1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `id_ventas` int(11) NOT NULL,
  `fecha_venta` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_fondo` int(11) NOT NULL,
  `id_cliente_generico` int(11) DEFAULT NULL,
  `id_cliente_mayor` int(11) DEFAULT NULL,
  `subtotal_venta` decimal(16,2) NOT NULL DEFAULT 0.00,
  `total_iva_venta` decimal(16,2) NOT NULL DEFAULT 0.00,
  `total_neto_venta` decimal(16,2) NOT NULL DEFAULT 0.00,
  `estado_venta` varchar(20) NOT NULL DEFAULT 'Completada',
  `id_usuario_registro` int(11) NOT NULL,
  `id_caja` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`id_ventas`, `fecha_venta`, `id_fondo`, `id_cliente_generico`, `id_cliente_mayor`, `subtotal_venta`, `total_iva_venta`, `total_neto_venta`, `estado_venta`, `id_usuario_registro`, `id_caja`) VALUES
(5, '2025-06-18 10:11:46', 1, 4, NULL, 0.20, 0.03, 0.23, 'Completada', 4, NULL),
(6, '2025-06-18 10:14:07', 1, NULL, 2, 85.00, 6.80, 91.80, 'Completada', 4, NULL),
(7, '2025-06-18 10:15:51', 1, NULL, 3, 20.00, 3.20, 23.20, 'Credito Pendiente', 4, NULL),
(8, '2025-06-18 11:09:25', 1, 4, NULL, 1.00, 0.16, 1.16, 'Completada', 4, NULL),
(9, '2025-06-18 18:26:49', 1, 5, NULL, 6.80, 0.54, 7.34, 'Completada', 4, NULL),
(10, '2025-06-18 19:01:30', 1, 6, NULL, 5.10, 0.41, 5.51, 'Completada', 4, NULL),
(11, '2025-06-18 19:04:12', 1, NULL, 4, 68.00, 5.44, 73.44, 'Credito Pendiente', 4, NULL),
(12, '2025-06-25 14:40:50', 1, 7, NULL, 11.90, 0.95, 12.85, 'Completada', 1, NULL),
(13, '2025-07-01 15:45:25', 1, 7, NULL, 1.10, 0.18, 1.28, 'Completada', 4, NULL),
(14, '2025-07-01 15:47:12', 1, 8, NULL, 1.70, 0.14, 1.84, 'Completada', 4, NULL),
(15, '2025-07-01 15:49:21', 1, NULL, 4, 300.00, 24.00, 324.00, 'Credito Pendiente', 4, NULL),
(16, '2025-07-02 02:22:04', 1, 6, NULL, 1.70, 0.14, 1.84, 'Completada', 4, NULL),
(17, '2025-07-02 03:05:04', 1, 4, NULL, 1.00, 0.16, 1.16, 'Completada', 4, 1),
(18, '2025-07-02 12:44:30', 1, 8, NULL, 2.70, 0.30, 3.00, 'Completada', 4, 2),
(19, '2025-07-02 12:46:05', 1, 7, NULL, 7.27, 0.22, 7.49, 'Completada', 4, 2),
(20, '2025-07-02 14:33:18', 1, 4, NULL, 2.70, 0.30, 3.00, 'Completada', 4, 3),
(21, '2025-07-02 14:34:12', 1, 8, NULL, 6.57, 0.97, 7.54, 'Completada', 4, 3),
(22, '2025-07-08 17:56:00', 1, 6, NULL, 39.50, 3.60, 43.10, 'Completada', 4, 6),
(23, '2025-07-08 19:05:49', 1, 7, NULL, 29.70, 0.82, 30.52, 'Completada', 4, 7),
(24, '2025-07-08 19:38:52', 1, 7, NULL, 1.70, 0.14, 1.84, 'Completada', 1, NULL),
(25, '2025-07-09 03:47:14', 1, 5, NULL, 4.00, 0.32, 4.32, 'Completada', 1, NULL),
(26, '2025-07-09 12:57:40', 1, 7, NULL, 1.20, 0.10, 1.30, 'Completada', 1, NULL),
(27, '2025-07-09 13:17:12', 1, 5, NULL, 23.35, 2.35, 25.70, 'Completada', 5, 9),
(28, '2025-07-09 13:19:34', 1, NULL, 4, 1.20, 0.10, 1.30, 'Credito Pendiente', 5, NULL),
(30, '2025-08-05 23:49:38', 1, 7, NULL, 1020.00, 81.60, 1101.60, 'Completada', 1, NULL),
(37, '2025-09-03 16:25:51', 1, 7, NULL, 11.90, 0.95, 12.85, 'Pago Pendiente', 2, 14),
(38, '2025-09-03 16:30:21', 1, 7, NULL, 4.50, 0.00, 4.50, 'Completada', 2, 14),
(39, '2025-09-03 16:43:47', 1, 6, NULL, 3.30, 0.53, 3.83, 'Completada', 2, 14),
(40, '2025-09-03 18:35:28', 1, 6, NULL, 6.80, 0.54, 7.34, 'Pago Pendiente', 2, 14),
(41, '2025-09-20 00:01:52', 1, 10, NULL, 8.55, 1.37, 9.92, 'Pago Pendiente', 1, 15),
(42, '2025-09-21 03:27:15', 1, 11, NULL, 6.50, 1.04, 7.54, 'Completada', 2, 16),
(43, '2025-09-22 13:00:50', 1, 6, NULL, 8.55, 1.37, 9.92, 'Pago Pendiente', 1, 16);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `caja`
--
ALTER TABLE `caja`
  ADD PRIMARY KEY (`id_caja`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `cargo`
--
ALTER TABLE `cargo`
  ADD PRIMARY KEY (`id_cargo`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id_categoria`);

--
-- Indices de la tabla `categoria_gasto`
--
ALTER TABLE `categoria_gasto`
  ADD PRIMARY KEY (`id_categoria_gasto`);

--
-- Indices de la tabla `cliente_generico`
--
ALTER TABLE `cliente_generico`
  ADD PRIMARY KEY (`id_cliente_generico`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `cliente_mayor`
--
ALTER TABLE `cliente_mayor`
  ADD PRIMARY KEY (`id_cliente_mayor`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `compras_credito`
--
ALTER TABLE `compras_credito`
  ADD PRIMARY KEY (`id_compra_credito`),
  ADD KEY `RIF_proveedor` (`RIF_proveedor`),
  ADD KEY `id_usuario_registro` (`id_usuario_registro`);

--
-- Indices de la tabla `creditos_venta`
--
ALTER TABLE `creditos_venta`
  ADD PRIMARY KEY (`id_credito_venta`),
  ADD UNIQUE KEY `uk_id_ventas_credito` (`id_ventas`) COMMENT 'Asegura un solo registro de crédito por venta',
  ADD KEY `idx_id_usuario_registro_credito` (`id_usuario_registro`);

--
-- Indices de la tabla `deducciones`
--
ALTER TABLE `deducciones`
  ADD PRIMARY KEY (`id_deduccion`),
  ADD KEY `id_nomina` (`id_nomina`);

--
-- Indices de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD PRIMARY KEY (`id_detalle_venta`),
  ADD KEY `idx_id_ventas_detalle` (`id_ventas`),
  ADD KEY `idx_id_pro_detalle` (`id_pro`),
  ADD KEY `idx_id_iva_aplicado_detalle` (`id_iva_aplicado`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`cedula_emple`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_nomina` (`id_nomina`,`id_cargo`),
  ADD KEY `id_cargo` (`id_cargo`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`RIF_empresa`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `fondo`
--
ALTER TABLE `fondo`
  ADD PRIMARY KEY (`id_fondo`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `gastos`
--
ALTER TABLE `gastos`
  ADD PRIMARY KEY (`id_gastos`),
  ADD KEY `id_fondo` (`id_fondo`,`id_categoria_gasto`),
  ADD KEY `id_categoria_gasto` (`id_categoria_gasto`);

--
-- Indices de la tabla `iva`
--
ALTER TABLE `iva`
  ADD PRIMARY KEY (`id_iva`);

--
-- Indices de la tabla `nomina`
--
ALTER TABLE `nomina`
  ADD PRIMARY KEY (`id_nomina`),
  ADD KEY `id_tasa_dolar` (`id_tasa_dolar`,`id_fondo`),
  ADD KEY `id_fondo` (`id_fondo`),
  ADD KEY `cedula_emple` (`cedula_emple`);

--
-- Indices de la tabla `pagos_compras_credito`
--
ALTER TABLE `pagos_compras_credito`
  ADD PRIMARY KEY (`id_pago_compra_credito`),
  ADD KEY `id_compra_credito` (`id_compra_credito`),
  ADD KEY `id_fondo_origen` (`id_fondo_origen`),
  ADD KEY `id_usuario_registro_pago` (`id_usuario_registro_pago`),
  ADD KEY `idx_id_tipo_pago` (`id_tipo_pago`);

--
-- Indices de la tabla `pagos_venta`
--
ALTER TABLE `pagos_venta`
  ADD PRIMARY KEY (`id_pago_venta`),
  ADD KEY `idx_id_ventas_pago` (`id_ventas`),
  ADD KEY `idx_id_tipo_pago_pago` (`id_tipo_pago`),
  ADD KEY `idx_id_tasa_dolar_aplicada_pago` (`id_tasa_dolar_aplicada`),
  ADD KEY `idx_id_usuario_registro_pago` (`id_usuario_registro`);

--
-- Indices de la tabla `perdida`
--
ALTER TABLE `perdida`
  ADD PRIMARY KEY (`id_perdida`),
  ADD KEY `id_pro` (`id_pro`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id_pro`),
  ADD UNIQUE KEY `uk_codigo_barras` (`codigo_barras`),
  ADD KEY `id_tasa_dolar` (`id_categoria`,`id_fondo`),
  ADD KEY `id_categoria` (`id_categoria`),
  ADD KEY `id_fondo` (`id_fondo`),
  ADD KEY `id_iva` (`id_iva`,`id_tipo_cuenta`),
  ADD KEY `id_tipo_cuenta` (`id_tipo_cuenta`);

--
-- Indices de la tabla `producto_proveedor`
--
ALTER TABLE `producto_proveedor`
  ADD PRIMARY KEY (`id_producto_proveedor`),
  ADD KEY `RIF` (`RIF`,`id_pro`),
  ADD KEY `id_pro` (`id_pro`),
  ADD KEY `producto_proveedor_ibfk_credito` (`id_compra_credito_fk`),
  ADD KEY `fk_prod_prov_tipo_pago_contado` (`id_tipo_pago_contado`);

--
-- Indices de la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD PRIMARY KEY (`RIF`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `tasa_dolar`
--
ALTER TABLE `tasa_dolar`
  ADD PRIMARY KEY (`id_tasa_dolar`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `tipo_cuenta`
--
ALTER TABLE `tipo_cuenta`
  ADD PRIMARY KEY (`id_tipo_cuenta`);

--
-- Indices de la tabla `tipo_pago`
--
ALTER TABLE `tipo_pago`
  ADD PRIMARY KEY (`id_tipo_pago`);

--
-- Indices de la tabla `tipo_usuario`
--
ALTER TABLE `tipo_usuario`
  ADD PRIMARY KEY (`id_tipo_usuario`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `id_tipo_usuario` (`id_tipo_usuario`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`id_ventas`),
  ADD KEY `id_cajero` (`id_fondo`),
  ADD KEY `id_fondo` (`id_fondo`),
  ADD KEY `ventas_ibfk_cliente_generico` (`id_cliente_generico`),
  ADD KEY `ventas_ibfk_cliente_mayor` (`id_cliente_mayor`),
  ADD KEY `ventas_ibfk_usuario` (`id_usuario_registro`),
  ADD KEY `ventas_ibfk_caja` (`id_caja`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `caja`
--
ALTER TABLE `caja`
  MODIFY `id_caja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `cargo`
--
ALTER TABLE `cargo`
  MODIFY `id_cargo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `categoria_gasto`
--
ALTER TABLE `categoria_gasto`
  MODIFY `id_categoria_gasto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `cliente_generico`
--
ALTER TABLE `cliente_generico`
  MODIFY `id_cliente_generico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `cliente_mayor`
--
ALTER TABLE `cliente_mayor`
  MODIFY `id_cliente_mayor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `compras_credito`
--
ALTER TABLE `compras_credito`
  MODIFY `id_compra_credito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `creditos_venta`
--
ALTER TABLE `creditos_venta`
  MODIFY `id_credito_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `deducciones`
--
ALTER TABLE `deducciones`
  MODIFY `id_deduccion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  MODIFY `id_detalle_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

--
-- AUTO_INCREMENT de la tabla `fondo`
--
ALTER TABLE `fondo`
  MODIFY `id_fondo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `gastos`
--
ALTER TABLE `gastos`
  MODIFY `id_gastos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `iva`
--
ALTER TABLE `iva`
  MODIFY `id_iva` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `nomina`
--
ALTER TABLE `nomina`
  MODIFY `id_nomina` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT de la tabla `pagos_compras_credito`
--
ALTER TABLE `pagos_compras_credito`
  MODIFY `id_pago_compra_credito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `pagos_venta`
--
ALTER TABLE `pagos_venta`
  MODIFY `id_pago_venta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT de la tabla `perdida`
--
ALTER TABLE `perdida`
  MODIFY `id_perdida` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id_pro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT de la tabla `producto_proveedor`
--
ALTER TABLE `producto_proveedor`
  MODIFY `id_producto_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT de la tabla `tasa_dolar`
--
ALTER TABLE `tasa_dolar`
  MODIFY `id_tasa_dolar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `tipo_cuenta`
--
ALTER TABLE `tipo_cuenta`
  MODIFY `id_tipo_cuenta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tipo_pago`
--
ALTER TABLE `tipo_pago`
  MODIFY `id_tipo_pago` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `tipo_usuario`
--
ALTER TABLE `tipo_usuario`
  MODIFY `id_tipo_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `id_ventas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `caja`
--
ALTER TABLE `caja`
  ADD CONSTRAINT `caja_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cliente_generico`
--
ALTER TABLE `cliente_generico`
  ADD CONSTRAINT `cliente_generico_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `cliente_mayor`
--
ALTER TABLE `cliente_mayor`
  ADD CONSTRAINT `cliente_mayor_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `compras_credito`
--
ALTER TABLE `compras_credito`
  ADD CONSTRAINT `compras_credito_ibfk_1` FOREIGN KEY (`RIF_proveedor`) REFERENCES `proveedor` (`RIF`) ON UPDATE CASCADE,
  ADD CONSTRAINT `compras_credito_ibfk_2` FOREIGN KEY (`id_usuario_registro`) REFERENCES `usuario` (`id_usuario`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `creditos_venta`
--
ALTER TABLE `creditos_venta`
  ADD CONSTRAINT `fk_creditos_venta_usuario` FOREIGN KEY (`id_usuario_registro`) REFERENCES `usuario` (`id_usuario`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_creditos_venta_ventas` FOREIGN KEY (`id_ventas`) REFERENCES `ventas` (`id_ventas`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `deducciones`
--
ALTER TABLE `deducciones`
  ADD CONSTRAINT `deducciones_ibfk_1` FOREIGN KEY (`id_nomina`) REFERENCES `nomina` (`id_nomina`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_venta`
--
ALTER TABLE `detalle_venta`
  ADD CONSTRAINT `detalle_venta_ibfk_iva` FOREIGN KEY (`id_iva_aplicado`) REFERENCES `iva` (`id_iva`) ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_venta_ibfk_producto` FOREIGN KEY (`id_pro`) REFERENCES `producto` (`id_pro`) ON UPDATE CASCADE,
  ADD CONSTRAINT `detalle_venta_ibfk_ventas` FOREIGN KEY (`id_ventas`) REFERENCES `ventas` (`id_ventas`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD CONSTRAINT `empleado_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `empleado_ibfk_2` FOREIGN KEY (`id_cargo`) REFERENCES `cargo` (`id_cargo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `empleado_ibfk_3` FOREIGN KEY (`id_nomina`) REFERENCES `nomina` (`id_nomina`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD CONSTRAINT `empresa_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `fondo`
--
ALTER TABLE `fondo`
  ADD CONSTRAINT `fondo_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `gastos`
--
ALTER TABLE `gastos`
  ADD CONSTRAINT `gastos_ibfk_1` FOREIGN KEY (`id_fondo`) REFERENCES `fondo` (`id_fondo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `gastos_ibfk_2` FOREIGN KEY (`id_categoria_gasto`) REFERENCES `categoria_gasto` (`id_categoria_gasto`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `nomina`
--
ALTER TABLE `nomina`
  ADD CONSTRAINT `nomina_ibfk_1` FOREIGN KEY (`id_fondo`) REFERENCES `fondo` (`id_fondo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `nomina_ibfk_2` FOREIGN KEY (`id_tasa_dolar`) REFERENCES `tasa_dolar` (`id_tasa_dolar`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `pagos_compras_credito`
--
ALTER TABLE `pagos_compras_credito`
  ADD CONSTRAINT `fk_pagos_compras_credito_tipo_pago` FOREIGN KEY (`id_tipo_pago`) REFERENCES `tipo_pago` (`id_tipo_pago`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pagos_compras_credito_ibfk_1` FOREIGN KEY (`id_compra_credito`) REFERENCES `compras_credito` (`id_compra_credito`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pagos_compras_credito_ibfk_2` FOREIGN KEY (`id_fondo_origen`) REFERENCES `fondo` (`id_fondo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pagos_compras_credito_ibfk_3` FOREIGN KEY (`id_usuario_registro_pago`) REFERENCES `usuario` (`id_usuario`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `pagos_venta`
--
ALTER TABLE `pagos_venta`
  ADD CONSTRAINT `pagos_venta_ibfk_tasa_dolar` FOREIGN KEY (`id_tasa_dolar_aplicada`) REFERENCES `tasa_dolar` (`id_tasa_dolar`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pagos_venta_ibfk_tipo_pago` FOREIGN KEY (`id_tipo_pago`) REFERENCES `tipo_pago` (`id_tipo_pago`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pagos_venta_ibfk_usuario_pago` FOREIGN KEY (`id_usuario_registro`) REFERENCES `usuario` (`id_usuario`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pagos_venta_ibfk_ventas` FOREIGN KEY (`id_ventas`) REFERENCES `ventas` (`id_ventas`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `perdida`
--
ALTER TABLE `perdida`
  ADD CONSTRAINT `perdida_ibfk_1` FOREIGN KEY (`id_pro`) REFERENCES `producto` (`id_pro`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categoria` (`id_categoria`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `producto_ibfk_2` FOREIGN KEY (`id_fondo`) REFERENCES `fondo` (`id_fondo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `producto_ibfk_3` FOREIGN KEY (`id_iva`) REFERENCES `iva` (`id_iva`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `producto_ibfk_4` FOREIGN KEY (`id_tipo_cuenta`) REFERENCES `tipo_cuenta` (`id_tipo_cuenta`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `producto_proveedor`
--
ALTER TABLE `producto_proveedor`
  ADD CONSTRAINT `fk_prod_prov_tipo_pago_contado` FOREIGN KEY (`id_tipo_pago_contado`) REFERENCES `tipo_pago` (`id_tipo_pago`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `producto_proveedor_ibfk_1` FOREIGN KEY (`id_pro`) REFERENCES `producto` (`id_pro`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `producto_proveedor_ibfk_credito` FOREIGN KEY (`id_compra_credito_fk`) REFERENCES `compras_credito` (`id_compra_credito`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Filtros para la tabla `proveedor`
--
ALTER TABLE `proveedor`
  ADD CONSTRAINT `proveedor_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tasa_dolar`
--
ALTER TABLE `tasa_dolar`
  ADD CONSTRAINT `tasa_dolar_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`id_tipo_usuario`) REFERENCES `tipo_usuario` (`id_tipo_usuario`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`id_fondo`) REFERENCES `fondo` (`id_fondo`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ventas_ibfk_caja` FOREIGN KEY (`id_caja`) REFERENCES `caja` (`id_caja`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `ventas_ibfk_cliente_generico` FOREIGN KEY (`id_cliente_generico`) REFERENCES `cliente_generico` (`id_cliente_generico`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `ventas_ibfk_cliente_mayor` FOREIGN KEY (`id_cliente_mayor`) REFERENCES `cliente_mayor` (`id_cliente_mayor`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `ventas_ibfk_usuario` FOREIGN KEY (`id_usuario_registro`) REFERENCES `usuario` (`id_usuario`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
