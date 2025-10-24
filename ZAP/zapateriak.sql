-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-10-2025 a las 00:56:25
-- Versión del servidor: 10.1.38-MariaDB
-- Versión de PHP: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `zapateriak`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `categoria` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `categorias`
--

INSERT INTO `categorias` (`id`, `categoria`, `fecha`) VALUES
(7, 'Ropa', '2025-07-17 23:32:12'),
(9, 'Calzado', '2025-07-15 08:54:01'),
(10, 'ACCESORIOS', '2024-10-23 06:24:40'),
(12, 'COMPONENTES', '2024-10-30 06:08:07'),
(16, 'medias', '2025-07-17 23:32:37');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `apellido` text COLLATE utf8_spanish_ci NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL,
  `prefijo_telefono` varchar(10) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
  `numero_telefono` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
  `tipo_ced` varchar(5) COLLATE utf8_spanish_ci NOT NULL DEFAULT '',
  `num_ced` varchar(15) COLLATE utf8_spanish_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`nombre`, `apellido`, `email`, `direccion`, `prefijo_telefono`, `numero_telefono`, `tipo_ced`, `num_ced`) VALUES
('jose angeles', 'cabeza arias', 'jose@gmail.com', 'av sucre', '0414', '1212125', '30124', '30124678'),
('anas', 'ruiz perdomo', 'rojasbastidasr@gmail.com', 'amazonas', '0426', '3333333', 'E', '10000000'),
('jose', 'roas', 'arrshadow@hotmail.com', 'qwqwqwqw', '0412', '0000000', 'E', '14600272'),
('peter', 'grifin', 'bastidas@gmail.com', 'valencia', '0412', '1111112', 'E', '14600999'),
('juan', 'perez', 'qwqwqw@mail.com', 'valencia', '0416', '1111111', 'V', '10000000'),
('francisco', 'perdomo', 'francoarias81@gmail.com', 'av- sucre 1-10', '0272', '1212125', 'V', '14600270'),
('francisco', 'sdsdsd', 'francoarias81@gmail.com', 'av- sucre 1-11', '0424', '1212125', 'V', '14600274'),
('francisco', 'perdomo', 'qwqwqw@mail.com', 'qwqwqwqw', '0416', '1111111', 'V', '14603333'),
('france', 'perdomo', 'france@gmail.com', 'av- sucre 1-11', '0412', '0630239', 'V', '17049561');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colores`
--

CREATE TABLE `colores` (
  `id` int(11) NOT NULL,
  `color` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `colores`
--

INSERT INTO `colores` (`id`, `color`, `fecha`) VALUES
(1, 'negro', '2024-10-21 10:22:13'),
(10, 'blanco', '2024-10-21 12:47:13'),
(11, 'verde', '2024-10-21 12:47:24'),
(12, 'beige', '2012-11-01 04:38:15'),
(13, 'TUTIFRUTTY', '2024-10-27 01:04:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `compras`
--

CREATE TABLE `compras` (
  `id_compra` int(11) NOT NULL,
  `fecha_compra` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Fecha y hora en que se registró la compra.',
  `numero_factura_proveedor` varchar(50) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Número de factura o documento que te entregó el proveedor.',
  `total_compra_bs` decimal(10,2) NOT NULL COMMENT 'Monto total de la compra en Bolívares.',
  `total_compra_usd` decimal(10,2) DEFAULT NULL COMMENT 'Monto total de la compra en Dólares (si aplica).',
  `tasa_cambio_usd` decimal(10,4) DEFAULT NULL COMMENT 'Tasa de cambio USD/BS utilizada para esta compra específica.',
  `observaciones` text COLLATE utf8_spanish_ci COMMENT 'Notas adicionales sobre la compra.',
  `tipo_rif` varchar(5) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL DEFAULT '',
  `num_rif` varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `compras`
--

INSERT INTO `compras` (`id_compra`, `fecha_compra`, `numero_factura_proveedor`, `total_compra_bs`, `total_compra_usd`, `tasa_cambio_usd`, `observaciones`, `tipo_rif`, `num_rif`) VALUES
(188, '2025-10-23 13:08:16', '1234', '48000.00', '240.00', '200.0000', 'pagado', 'C', '175109422'),
(190, '2025-10-23 13:13:06', '1234', '2000.00', '10.00', '200.0000', 'pagado', 'C', '999999991'),
(192, '2025-10-23 14:02:57', '12', '4000.00', '20.00', '200.0000', '', 'C', '175109422'),
(212, '2025-10-23 14:51:37', '123', '2400.00', '12.00', '200.0000', '', 'C', '175109422'),
(214, '2025-10-23 18:18:11', 'wewe23234', '2000.00', '10.00', '200.0000', '', 'C', '175109422'),
(215, '2025-10-23 18:23:13', '12', '200.00', '1.00', '200.0000', '', 'J', '123423232');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_compra`
--

CREATE TABLE `detalle_compra` (
  `id_detalle_compra` int(11) NOT NULL,
  `id_compra` int(11) NOT NULL COMMENT 'Clave foránea a la tabla compras.',
  `codigo_producto` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL COMMENT 'Clave foránea al código del producto.',
  `cantidad` int(11) NOT NULL DEFAULT '1' COMMENT 'Cantidad de este producto en esta línea de compra.',
  `precio_compra_unitario_bs` decimal(10,2) NOT NULL COMMENT 'Precio unitario de compra en Bolívares de este producto en esta compra (crucial para historial de costos).',
  `precio_compra_unitario_usd` decimal(10,2) DEFAULT NULL COMMENT 'Precio unitario de compra en Dólares de este producto en esta compra (si aplica).',
  `total_linea_bs` decimal(10,2) AS (`cantidad` * `precio_compra_unitario_bs`) VIRTUAL COMMENT 'Total calculado para esta línea de detalle en Bolívares.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `detalle_compra`
--

INSERT INTO `detalle_compra` (`id_detalle_compra`, `id_compra`, `codigo_producto`, `cantidad`, `precio_compra_unitario_bs`, `precio_compra_unitario_usd`) VALUES
(72, 188, '2323233-2', 20, '2400.00', '12.00'),
(73, 190, 'ard-093-as34', 1, '2000.00', '10.00'),
(74, 192, '121342-2-abc', 1, '4000.00', '20.00'),
(75, 212, '1234blu', 1, '2400.00', '12.00'),
(76, 214, '2323233-lo-p', 1, '1000.00', '5.00'),
(77, 214, '2323233-lo-p', 1, '1000.00', '5.00'),
(78, 215, '2323233-le3-32', 1, '200.00', '1.00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `foto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  `prefijo_telefono` varchar(10) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `numero_telefono` varchar(20) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `tipo_ced` varchar(5) COLLATE utf8_spanish2_ci NOT NULL,
  `num_ced` varchar(20) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`cedula`, `nombre`, `apellido`, `direccion`, `foto`, `prefijo_telefono`, `numero_telefono`, `tipo_ced`, `num_ced`) VALUES
('1000000', 'super', 'usuario', 'bocono', '', NULL, NULL, 'C', '1000000'),
('V-14600272', 'francisco', 'arias', 'av-sucre 1-10', '', '0414', '1074586', 'V', '14600272'),
('V-17049561', 'francelia', 'arias', 'av-sucre 1-10', '', '0412', '0630239', 'V', '17049561');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `direccion` varchar(80) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(80) COLLATE utf8_spanish2_ci NOT NULL,
  `precio_dolar` float NOT NULL,
  `impuesto` float NOT NULL,
  `prefijo_telefono` varchar(10) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `numero_telefono` varchar(20) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `tipo_rif` varchar(5) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '',
  `num_rif` varchar(15) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '',
  `log_eventos_activo` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`direccion`, `nombre`, `precio_dolar`, `impuesto`, `prefijo_telefono`, `numero_telefono`, `tipo_rif`, `num_rif`, `log_eventos_activo`) VALUES
('Avenida Miranda entre Calle Paez y Calle Jauregui', 'Kamyl Styl y Algo mas de Susana Al Chariti', 200, 16, '0416', '1234560', 'J', '121212121', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `event_log`
--

CREATE TABLE `event_log` (
  `id` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `event_type` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `description` text COLLATE utf8_spanish2_ci NOT NULL,
  `employee_cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `affected_table` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `affected_row_id` varchar(100) COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `event_log`
--

INSERT INTO `event_log` (`id`, `timestamp`, `event_type`, `description`, `employee_cedula`, `affected_table`, `affected_row_id`) VALUES
(636, '2025-07-11 08:00:23', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 0,00 Bs.', '1000000', 'ventas', 'N/A'),
(637, '2025-07-11 08:00:25', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 0,00 Bs.', '1000000', 'ventas', 'N/A'),
(638, '2025-07-11 09:00:59', 'Creación Cliente Fallida', 'Intento de crear cliente con datos inválidos (nombre: francisco, cédula: V-14600222).', '1000000', 'clientes', 'V-14600222'),
(639, '2025-07-11 09:07:46', 'Creación Cliente Fallida', 'Intento de crear cliente con datos inválidos (nombre: francisco, cédula: V-14600222).', '1000000', 'clientes', 'V-14600222'),
(640, '2025-07-11 09:11:37', 'Creación Cliente Fallida', 'Intento de crear cliente con datos inválidos (nombre: francisco, cédula: V-12121212).', '1000000', 'clientes', 'V-12121212'),
(641, '2025-07-11 09:16:18', 'Creación Cliente Fallida', 'Intento de crear cliente con datos inválidos (nombre: francisco, cédula: V-14603434).', '1000000', 'clientes', 'V-14603434'),
(642, '2025-07-11 09:18:18', 'Cliente Creado', 'Nuevo cliente \'francisco perdomo\' (Cédula: V-14600270) registrado.', '1000000', 'clientes', 'V-14600270'),
(643, '2025-07-11 09:27:11', 'Edición Cliente Fallida', 'Intento de editar cliente con datos inválidos (nombre: jose angeles, cédula: 30124678).', '1000000', 'clientes', '30124678'),
(644, '2025-07-11 09:27:54', 'Edición Cliente Fallida', 'Intento de editar cliente con datos inválidos (nombre: francisco, cédula: V-14600270).', '1000000', 'clientes', 'V-14600270'),
(645, '2025-07-11 09:35:53', 'Edición Cliente Fallida', 'Intento de editar cliente con datos inválidos (nombre: jose angeles, cédula: 30124678).', '1000000', 'clientes', '30124678'),
(646, '2025-07-11 09:37:30', 'Edición Cliente Fallida', 'Intento de editar cliente con datos inválidos (nombre: jose angeles, cédula: 30124678).', '1000000', 'clientes', '30124678'),
(647, '2025-07-11 09:43:58', 'Proveedor Creado', 'Nuevo proveedor \'sifrina\' (RIF: V-999994444) registrado. Representante: franco arias. Teléfono: 0272-1074586.', '1000000', 'proveedores', 'V-999994444'),
(648, '2025-07-11 09:44:38', 'Edición Proveedor Fallida', 'Intento de editar proveedor con datos inválidos. RIF: V-999994444. Nombre Empresa: sifrina. Prefijo: . Número: .', '1000000', 'proveedores', 'V-999994444'),
(649, '2025-07-11 09:44:58', 'Edición Proveedor Fallida', 'Intento de editar proveedor con datos inválidos. RIF: V-999994444. Nombre Empresa: sifrina. Prefijo: . Número: .', '1000000', 'proveedores', 'V-999994444'),
(650, '2025-07-11 09:47:36', 'Edición Proveedor Fallida', 'Intento de editar proveedor con datos inválidos. RIF: C-999999991. Nombre Empresa: franco import. Prefijo: . Número: .', '1000000', 'proveedores', 'C-999999991'),
(651, '2025-07-11 09:48:36', 'Edición Proveedor Fallida', 'Intento de editar proveedor con datos inválidos. RIF: V-999994444. Nombre Empresa: sifrina. Prefijo: . Número: .', '1000000', 'proveedores', 'V-999994444'),
(652, '2025-07-11 09:50:38', 'Edición Proveedor Fallida', 'Intento de editar proveedor con datos inválidos. RIF: V-999994444. Nombre Empresa: sifrina. Prefijo: . Número: .', '1000000', 'proveedores', 'V-999994444'),
(653, '2025-07-11 09:53:09', 'Edición Proveedor Fallida', 'Intento de editar proveedor con datos inválidos. RIF: J-999999997. Nombre Empresa: rs22. Prefijo: . Número: .', '1000000', 'proveedores', 'J-999999997'),
(654, '2025-07-11 09:54:07', 'Proveedor Creado', 'Nuevo proveedor \'pochiloin\' (RIF: G-999999993) registrado. Representante: jose artigas. Teléfono: prefijo_telefono-editarTelefono.', '1000000', 'proveedores', 'G-999999993'),
(655, '2025-07-11 09:57:45', 'Proveedor Creado', 'Nuevo proveedor \'popular x\' (RIF: V-123212121) registrado. Representante: sdsdsd sdsdsd. Teléfono: 0414-.', '1000000', 'proveedores', 'V-123212121'),
(656, '2025-07-11 09:59:36', 'Proveedor Creado', 'Nuevo proveedor \'adidas\' (RIF: J-992222222) registrado. Representante: erer sdsdsd. Teléfono: 0272-1074586.', '1000000', 'proveedores', 'J-992222222'),
(657, '2025-07-11 09:59:51', 'Edición Proveedor Fallida', 'Intento de editar proveedor con datos inválidos. RIF: J-992222222. Nombre Empresa: adidas. Prefijo: . Número: .', '1000000', 'proveedores', 'J-992222222'),
(658, '2025-07-11 10:03:36', 'Proveedor Creado', 'Nuevo proveedor \'rs22222\' (RIF: P-999999999) registrado. Representante: sdsdsd arias. Teléfono: 0412-1074586.', '1000000', 'proveedores', 'P-999999999'),
(659, '2025-07-11 10:03:44', 'Proveedor Eliminado', 'Proveedor \'pochiloin\' (RIF: G-999999993) eliminado. Teléfono: prefijo_te-editarTelefono.', '1000000', 'proveedores', 'G-999999993'),
(660, '2025-07-11 10:03:51', 'Proveedor Eliminado', 'Proveedor \'popular x\' (RIF: V-123212121) eliminado. Teléfono: 0414-N/A.', '1000000', 'proveedores', 'V-123212121'),
(661, '2025-07-11 10:04:04', 'Edición Proveedor Fallida', 'Intento de editar proveedor con datos inválidos. RIF: V-999994444. Nombre Empresa: sifrina. Prefijo: . Número: .', '1000000', 'proveedores', 'V-999994444'),
(662, '2025-07-11 10:05:17', 'Edición Proveedor Fallida', 'Intento de editar proveedor con datos inválidos. RIF: J-992222222. Nombre Empresa: adidas. Prefijo: . Número: .', '1000000', 'proveedores', 'J-992222222'),
(663, '2025-07-11 10:06:54', 'Edición Proveedor Fallida', 'Intento de editar proveedor con datos inválidos. RIF: C-999999991. Nombre Empresa: franco import. Prefijo: N/A. Número: N/A.', '1000000', 'proveedores', 'C-999999991'),
(664, '2025-07-11 10:08:01', 'Proveedor Editado', 'Proveedor (RIF: C-999999991) editado. Empresa: \'franco import\' a \'franco import\'. Representante: \'pedro valdez\' a \'pedro valdez\'. Teléfono: \'0426-1111112\' a \'0281-1111112\'.', '1000000', 'proveedores', 'C-999999991'),
(665, '2025-07-11 10:09:17', 'Proveedor Editado', 'Proveedor (RIF: C-999999991) editado. Empresa: \'franco import\' a \'franco import\'. Representante: \'pedro valdez\' a \'pedro valdez\'. Teléfono: \'0281-1111112\' a \'0251-1111112\'.', '1000000', 'proveedores', 'C-999999991'),
(666, '2025-07-11 10:19:16', 'Proveedor Editado', 'Proveedor (RIF: J-992222222) editado. Empresa: \'adidas\' a \'adidas\'. Representante: \'erer sdsdsd\' a \'erer sdsdsd\'. Teléfono: \'0272-1074586\' a \'0212-1074586\'.', '1000000', 'proveedores', 'J-992222222'),
(667, '2025-07-11 10:20:25', 'Proveedor Editado', 'Proveedor (RIF: C-999999991) editado. Empresa: \'franco import\' a \'franco import\'. Representante: \'pedro valdez\' a \'pedro valdez\'. Teléfono: \'0251-1111112\' a \'0261-1111112\'.', '1000000', 'proveedores', 'C-999999991'),
(668, '2025-07-11 10:22:22', 'Proveedor Editado', 'Proveedor (RIF: J-992222222) editado. Empresa: \'adidas\' a \'adidas\'. Representante: \'erer sdsdsd\' a \'erer sdsdsd\'. Teléfono: \'0212-1074586\' a \'0251-1074586\'.', '1000000', 'proveedores', 'J-992222222'),
(669, '2025-07-11 10:22:59', 'Edición Cliente Fallida', 'Intento de editar cliente con datos inválidos (nombre: jose angeles, cédula: 30124678).', '1000000', 'clientes', '30124678'),
(670, '2025-07-11 10:25:51', 'Proveedor Editado', 'Proveedor (RIF: J-992222222) editado. Empresa: \'adidas\' a \'adidas\'. Representante: \'erer sdsdsd\' a \'erer sdsdsd\'. Teléfono: \'0251-1074586\' a \'0251-1074586\'.', '1000000', 'proveedores', 'J-992222222'),
(671, '2025-07-11 10:27:05', 'Proveedor Editado', 'Proveedor (RIF: C-999999991) editado. Empresa: \'franco import\' a \'franco import\'. Representante: \'pedro valdez\' a \'pedro valdez\'. Teléfono: \'0261-1111112\' a \'0261-1111112\'.', '1000000', 'proveedores', 'C-999999991'),
(672, '2025-07-11 10:31:59', 'Cliente Editado', 'Datos del cliente \'francisco perdomo\' (Cédula: V-14600270) modificados.', '1000000', 'clientes', 'V-14600270'),
(673, '2025-07-11 10:32:34', 'Cliente Editado', 'Datos del cliente \'jose angeles cabeza arias\' (Cédula: 30124678) modificados.', '1000000', 'clientes', '30124678'),
(674, '2025-07-11 10:32:57', 'Cliente Editado', 'Datos del cliente \'francisco perdomo\' (Cédula: V-14600270) modificados.', '1000000', 'clientes', 'V-14600270'),
(675, '2025-07-11 10:33:26', 'Cliente Editado', 'Datos del cliente \'francisco sdsdsd\' (Cédula: V-14600274) modificados.', '1000000', 'clientes', 'V-14600274'),
(676, '2025-07-11 11:17:58', 'Cliente Editado', 'Datos del cliente \'francisco perdomo\' (Cédula: V-14600270) modificados.', '1000000', 'clientes', 'V-14600270'),
(677, '2025-07-11 11:18:09', 'Cliente Editado', 'Datos del cliente \'jose angeles cabeza arias\' (Cédula: 30124678) modificados.', '1000000', 'clientes', '30124678'),
(678, '2025-07-11 11:28:13', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: V-122233333) modificada. Detalles: Precio Dólar: Anterior 300,00 , Nuevo 110,00 .', '1000000', 'empresa', 'V-122233333'),
(679, '2025-07-11 16:43:05', 'Empleado Creado', 'Nuevo empleado \'jose perdomo\' (Cédula: V-24600272) registrado con éxito.', '1000000', 'empleado', 'V-24600272'),
(680, '2025-07-11 16:59:25', 'Empleado Creado', 'Nuevo empleado \'franco perdomo\' (Cédula: V-34600272) registrado con éxito.', '1000000', 'empleado', 'V-34600272'),
(681, '2025-07-11 17:00:54', 'Empleado Creado', 'Nuevo empleado \'maria rivas\' (Cédula: V-12190909) registrado con éxito.', '1000000', 'empleado', 'V-12190909'),
(682, '2025-07-11 17:06:24', 'Empleado Creado', 'Nuevo empleado \'jose perdomo\' (Cédula: V-66600272) registrado con éxito.', '1000000', 'empleado', 'V-66600272'),
(683, '2025-07-11 17:10:15', 'Empleado Creado', 'Nuevo empleado \'jose valdez\' (Cédula: V-66600270) registrado con éxito.', '1000000', 'empleado', 'V-66600270'),
(684, '2025-07-11 17:28:33', 'Empleado Creado', 'Nuevo empleado \'jose perdomo\' (Cédula: V-44600273) registrado con éxito.', '1000000', 'empleado', 'V-44600273'),
(685, '2025-07-11 17:33:31', 'Edición Empleado Fallida', 'Intento de editar empleado con datos inválidos (Nombre: jose, Cédula: V-66600272). Validación de campos fallida.', '1000000', 'empleado', 'V-66600272'),
(686, '2025-07-11 17:59:20', 'Empleado Eliminado', 'Empleado \'jose perdomo\' (Cédula: V-66600272) eliminado con éxito.', '1000000', 'empleado', 'V-66600272'),
(687, '2025-07-11 17:59:27', 'Empleado Eliminado', 'Empleado \'Jota Mejia\' (Cédula: 3174101) eliminado con éxito.', '1000000', 'empleado', '3174101'),
(688, '2025-07-11 17:59:35', 'Empleado Eliminado', 'Empleado \'Ricardo Yepez Bastidas\' (Cédula: 12847231) eliminado con éxito.', '1000000', 'empleado', '12847231'),
(689, '2025-07-11 17:59:45', 'Empleado Eliminado', 'Empleado \'Isaac Bastidas\' (Cédula: 23194809) eliminado con éxito.', '1000000', 'empleado', '23194809'),
(690, '2025-07-11 18:05:21', 'Empleado Creado', 'Nuevo empleado \'jose sdsdsd\' (Cédula: V-14600272) registrado con éxito.', '1000000', 'empleado', 'V-14600272'),
(691, '2025-07-11 18:05:48', 'Empleado Eliminado', 'Empleado \'Jhoan Mejia\' (Cédula: 31744101) eliminado con éxito.', '1000000', 'empleado', '31744101'),
(692, '2025-07-11 18:06:19', 'Empleado Creado', 'Nuevo empleado \'pedro arias\' (Cédula: V-14600273) registrado con éxito.', '1000000', 'empleado', 'V-14600273'),
(693, '2025-07-11 18:06:42', 'Empleado Creado', 'Nuevo empleado \'jose ppe\' (Cédula: E-14600272) registrado con éxito.', '1000000', 'empleado', 'E-14600272'),
(694, '2025-07-11 18:06:48', 'Empleado Eliminado', 'Empleado \'jose sdsdsd\' (Cédula: V-14600272) eliminado con éxito.', '1000000', 'empleado', 'V-14600272'),
(695, '2025-07-11 18:08:26', 'Categoría Editada', 'Categoría \'Ropas\' (ID: 7) modificada a \'Ropa\'.', '1000000', 'categorias', '7'),
(696, '2025-07-11 18:58:12', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: V-122233333) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'V-122233333'),
(697, '2025-07-11 19:07:47', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: V-222222222) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'V-222222222'),
(698, '2025-07-11 19:09:00', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J-222222222) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J-222222222'),
(699, '2025-07-11 19:09:11', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J-222222222) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J-222222222'),
(700, '2025-07-11 19:21:01', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J-222222222) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J-222222222'),
(701, '2025-07-11 19:28:44', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J-222222222) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J-222222222'),
(702, '2025-07-11 19:33:17', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J-222222222) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J-222222222'),
(703, '2025-07-11 19:33:28', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J-111111111) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J-111111111'),
(704, '2025-07-11 19:55:18', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J-111111111) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J-111111111'),
(705, '2025-07-11 19:57:46', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J-111111111) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J-111111111'),
(706, '2025-07-11 20:08:59', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J-111111111) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J-111111111'),
(707, '2025-07-11 20:09:06', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J-111111111) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J-111111111'),
(708, '2025-07-11 20:16:31', 'Empleado Creado', 'Nuevo empleado \'francisco arias\' (Cédula: V-14600272) registrado con éxito.', '1000000', 'empleado', 'V-14600272'),
(709, '2025-07-11 20:16:57', 'Usuario Creado', 'Nuevo usuario \'FRANCO\' (Cédula: V-14600272) con perfil \'Administrador\' fue creado.', '1000000', 'usuarios', 'V-14600272'),
(710, '2025-07-11 21:44:43', 'Proveedor Creado', 'Nuevo proveedor \'respuestas eventuales\' (RIF: G-999999999) registrado. Representante: maria  bolivar. Teléfono: 0272-1074586.', '1000000', 'proveedores', 'G-999999999'),
(711, '2025-07-11 21:52:47', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: G-111111111) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'G-111111111'),
(712, '2025-07-11 21:59:44', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: E-111111111) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'E-111111111'),
(713, '2025-07-11 22:00:02', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J-222222222) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J-222222222'),
(714, '2025-07-11 22:00:21', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J-111111111) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J-111111111'),
(715, '2025-07-11 22:01:30', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: P-888888888) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'P-888888888'),
(716, '2025-07-11 22:02:37', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: V-222222222) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'V-222222222'),
(717, '2025-07-11 22:03:42', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J-111111111) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J-111111111'),
(718, '2025-07-11 22:04:20', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J-222222222) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J-222222222'),
(719, '2025-07-11 22:08:07', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J'),
(720, '2025-07-11 22:08:54', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J'),
(721, '2025-07-11 22:11:10', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: ) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', NULL),
(722, '2025-07-11 22:15:27', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: ) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', NULL),
(723, '2025-07-11 22:29:00', 'Proveedor Editado', 'Proveedor (RIF: J-992222222) editado. Empresa: \'adidas\' a \'adidas\'. Representante: \'erer sdsdsd\' a \'erer sdsdsd\'. Teléfono: \'0251-1074586\' a \'0251-1074586\'.', '1000000', 'proveedores', 'J-992222222'),
(724, '2025-07-11 22:29:45', 'Proveedor Editado', 'Proveedor (RIF: V-999994444) editado. Empresa: \'sifrina\' a \'sifrina\'. Representante: \'franco arias\' a \'franco arias\'. Teléfono: \'0272-1074586\' a \'0272-1074586\'.', '1000000', 'proveedores', 'V-999994444'),
(725, '2025-07-11 22:57:11', 'Proveedor Editado', 'Proveedor (RIF: G-999999999) editado. Empresa: \'respuestas eventuales\' a \'respuestas eventuales\'. Representante: \'maria  bolivar\' a \'maria  bolivar\'. Teléfono: \'0272-1074586\' a \'0272-1074586\'.', '1000000', 'proveedores', 'G-999999999'),
(726, '2025-07-11 22:57:33', 'Proveedor Editado', 'Proveedor (RIF: C-999999991) editado. Empresa: \'franco import\' a \'franco import\'. Representante: \'pedro valdez\' a \'pedro valdez\'. Teléfono: \'0261-1111112\' a \'0261-1111112\'.', '1000000', 'proveedores', 'C-999999991'),
(727, '2025-07-11 23:17:05', 'Proveedor Editado', 'Proveedor (RIF: J-992222222) editado. Empresa: \'adidas\' a \'adidas\'. Representante: \'erer sdsdsd\' a \'erer sdsdsd\'. Teléfono: \'0251-1074586\' a \'0251-1074586\'.', '1000000', 'proveedores', 'J-992222222'),
(728, '2025-07-11 23:19:15', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: ) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', NULL),
(729, '2025-07-11 23:20:49', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: ) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', NULL),
(730, '2025-07-11 23:21:20', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: ) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', NULL),
(731, '2025-07-11 23:43:39', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: ) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', NULL),
(732, '2025-07-12 00:19:18', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: E121211111) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'E121211111'),
(733, '2025-07-12 00:26:12', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J123456789) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J123456789'),
(734, '2025-07-12 00:26:20', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J988888888) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J988888888'),
(735, '2025-07-12 00:26:44', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J123456789) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J123456789'),
(736, '2025-07-12 00:34:55', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J123456780) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J123456780'),
(737, '2025-07-12 00:35:48', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J123456789) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J123456789'),
(738, '2025-07-12 00:36:28', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J123456789) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J123456789'),
(739, '2025-07-12 00:57:54', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J123456783) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J123456783'),
(740, '2025-07-12 00:58:04', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: P123456783) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'P123456783'),
(741, '2025-07-12 00:58:11', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: P222222222) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'P222222222'),
(742, '2025-07-12 02:00:31', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J123456789) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J123456789'),
(743, '2025-07-12 03:07:58', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J123456111) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J123456111'),
(744, '2025-07-12 09:53:04', 'Generación de Reporte PDF', 'Se generó un reporte de Inventario en formato PDF.', '1000000', 'productos', 'N/A'),
(745, '2025-07-12 09:53:21', 'Generación de Reporte PDF', 'Se generó un reporte de Clientes en formato PDF.', '1000000', 'clientes', 'N/A'),
(746, '2025-07-12 09:53:51', 'Generación de Reporte PDF', 'Se generó un reporte de Proveedores en formato PDF.', '1000000', 'proveedores', 'N/A'),
(747, '2025-07-12 09:53:51', 'Generación de Reporte PDF', 'Se generó un reporte de Proveedores en formato PDF.', '1000000', 'proveedores', 'N/A'),
(748, '2025-07-12 09:54:08', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 0,00 Bs.', '1000000', 'ventas', 'N/A'),
(749, '2025-07-12 09:54:08', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 0,00 Bs.', '1000000', 'ventas', 'N/A'),
(750, '2025-07-12 09:54:50', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 26.735,33 Bs. Rango: Del 01/07/2025 al 31/07/2025', '1000000', 'ventas', 'N/A'),
(751, '2025-07-12 09:54:51', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 26.735,33 Bs. Rango: Del 01/07/2025 al 31/07/2025', '1000000', 'ventas', 'N/A'),
(752, '2025-07-12 10:13:07', 'Venta Creada', 'Nueva venta registrada. Factura: 10043, Cliente: 30124678, Total: 829.4.', '1000000', 'ventas', '10043'),
(753, '2025-07-12 10:14:57', 'Proveedor Editado', 'Proveedor (RIF: J-999999997) editado. Empresa: \'rs22\' a \'rs22\'. Representante: \'pedro arias\' a \'pedro arias\'. Teléfono: \'0272-1074586\' a \'0272-1074586\'.', '1000000', 'proveedores', 'J-999999997'),
(754, '2025-07-12 10:15:09', 'Proveedor Editado', 'Proveedor (RIF: P-999999999) editado. Empresa: \'rs22222\' a \'rs22222\'. Representante: \'sdsdsd arias\' a \'sdsdsd arias\'. Teléfono: \'0412-1074586\' a \'0412-1074586\'.', '1000000', 'proveedores', 'P-999999999'),
(755, '2025-07-12 10:15:21', 'Proveedor Editado', 'Proveedor (RIF: V-999994444) editado. Empresa: \'sifrina\' a \'sifrina\'. Representante: \'franco arias\' a \'franco arias\'. Teléfono: \'0272-1074586\' a \'0272-1074586\'.', '1000000', 'proveedores', 'V-999994444'),
(756, '2025-07-12 10:17:00', 'Producto Creado', 'Producto \'cHEMISE\' (Código: 121342-2) creado.', '1000000', 'productos', '121342-2'),
(757, '2025-07-12 10:17:23', 'Stock Actualizado', 'Stock del producto (Código: 121342) actualizado a 2 unidades.', '1000000', 'productos', '121342'),
(758, '2025-07-12 11:30:32', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(759, '2025-07-12 11:31:17', 'Generación de Reporte PDF', 'Se generó un reporte de Proveedores en formato PDF.', '1000000', 'proveedores', 'N/A'),
(760, '2025-07-12 11:31:20', 'Generación de Reporte PDF', 'Se generó un reporte de Proveedores en formato PDF.', '1000000', 'proveedores', 'N/A'),
(761, '2025-07-12 11:39:02', 'Producto Creado', 'Producto \'pantalon\' (Código: 2323233-2) creado.', '1000000', 'productos', '2323233-2'),
(762, '2025-07-12 11:40:18', 'Venta Creada', 'Nueva venta registrada. Factura: 10044, Cliente: 30124678, Total: 4478.76.', '1000000', 'ventas', '10044'),
(763, '2025-07-12 12:51:55', 'Proveedor Creado', 'Nuevo proveedor \'inversores bastidas\' (RIF: C-175109422) registrado. Representante: franco ana . Teléfono: 0272-8888888.', '1000000', 'proveedores', 'C-175109422'),
(764, '2025-07-12 15:06:46', 'Edición Proveedor Fallida', 'Intento de editar proveedor con datos inválidos. RIF: N/A-N/A. Nombre Empresa: franco import. Prefijo: 0261. Número: 1111112.', '1000000', 'proveedores', 'N/A'),
(765, '2025-07-12 15:07:27', 'Proveedor Editado', 'Proveedor (RIF: N/A-N/A) editado a (RIF: -). Empresa: \'N/A\' a \'franco import\'. Representante: \'N/A N/A\' a \'pedro valdez\'. Teléfono: \'N/A-N/A\' a \'0261-1111112\'.', '1000000', 'proveedores', '-'),
(766, '2025-07-12 15:07:42', 'Proveedor Editado', 'Proveedor (RIF: N/A-N/A) editado a (RIF: -). Empresa: \'N/A\' a \'inversores bastidas\'. Representante: \'N/A N/A\' a \'franco ana \'. Teléfono: \'N/A-N/A\' a \'0272-8888888\'.', '1000000', 'proveedores', '-'),
(767, '2025-07-12 15:08:46', 'Proveedor Editado', 'Proveedor (RIF: N/A-N/A) editado a (RIF: -). Empresa: \'N/A\' a \'franco import\'. Representante: \'N/A N/A\' a \'pedro valdezzzzzzzzz\'. Teléfono: \'N/A-N/A\' a \'0261-1111112\'.', '1000000', 'proveedores', '-'),
(768, '2025-07-12 15:10:20', 'Proveedor Eliminado', 'Proveedor \'sifrina\' (RIF: V-999994444) eliminado. Teléfono: 0272-1074586.', '1000000', 'proveedores', 'V-999994444'),
(769, '2025-07-12 15:13:43', 'Proveedor Editado', 'Proveedor (RIF: N/A-N/A) editado a (RIF: -). Empresa: \'N/A\' a \'rs22\'. Representante: \'N/A N/A\' a \'sdsdsd arias\'. Teléfono: \'N/A-N/A\' a \'0412-1074586\'.', '1000000', 'proveedores', '-'),
(770, '2025-07-12 15:13:58', 'Proveedor Editado', 'Proveedor (RIF: N/A-N/A) editado a (RIF: -). Empresa: \'N/A\' a \'inversores \'. Representante: \'N/A N/A\' a \'franco ana \'. Teléfono: \'N/A-N/A\' a \'0272-8888888\'.', '1000000', 'proveedores', '-'),
(771, '2025-07-12 15:24:08', 'Proveedor Creado', 'Nuevo proveedor \'invversiones perez\' (RIF: P-121212121) registrado. Representante: franco arias. Teléfono: 0272-5861111.', '1000000', 'proveedores', 'P-121212121'),
(772, '2025-07-12 15:24:27', 'Proveedor Editado', 'Proveedor (RIF: N/A-N/A) editado a (RIF: -). Empresa: \'N/A\' a \'invversiones perez\'. Representante: \'N/A N/A\' a \'franco arias\'. Teléfono: \'N/A-N/A\' a \'0272-5861112\'.', '1000000', 'proveedores', '-'),
(773, '2025-07-12 15:28:06', 'Edición Proveedor Fallida', 'Intento de editar proveedor con datos inválidos. RIF: N/A-N/A. Nombre Empresa: franco import. Prefijo: 0426. Número: 1074586.', '1000000', 'proveedores', 'N/A'),
(774, '2025-07-12 15:29:00', 'Proveedor Editado', 'Proveedor (RIF: N/A-N/A) editado a (RIF: -). Empresa: \'N/A\' a \'franco import\'. Representante: \'N/A N/A\' a \'pedro valdez\'. Teléfono: \'N/A-N/A\' a \'0261-1111112\'.', '1000000', 'proveedores', '-'),
(775, '2025-07-12 15:30:16', 'Proveedor Editado', 'Proveedor (RIF: N/A-N/A) editado a (RIF: -). Empresa: \'N/A\' a \'inversores bastidas\'. Representante: \'N/A N/A\' a \'franco ana \'. Teléfono: \'N/A-N/A\' a \'0272-8888888\'.', '1000000', 'proveedores', '-'),
(776, '2025-07-12 15:34:43', 'Proveedor Editado', 'Proveedor (RIF: N/A-N/A) editado a (RIF: -). Empresa: \'N/A\' a \'inversores bastidas\'. Representante: \'N/A N/A\' a \'franco ana \'. Teléfono: \'N/A-N/A\' a \'0272-8888888\'.', '1000000', 'proveedores', '-'),
(777, '2025-07-12 15:36:54', 'Proveedor Editado', 'Proveedor (RIF: N/A-N/A) editado a (RIF: -). Empresa: \'N/A\' a \'adidas\'. Representante: \'N/A N/A\' a \'erer sdsdsd\'. Teléfono: \'N/A-N/A\' a \'0251-1111111\'.', '1000000', 'proveedores', '-'),
(778, '2025-07-12 15:38:28', 'Proveedor Editado', 'Proveedor (RIF: N/A-N/A) editado a (RIF: -). Empresa: \'N/A\' a \'inversores bastidas\'. Representante: \'N/A N/A\' a \'franco ana \'. Teléfono: \'N/A-N/A\' a \'0272-8888888\'.', '1000000', 'proveedores', '-'),
(779, '2025-07-12 15:38:48', 'Proveedor Editado', 'Proveedor (RIF: N/A-N/A) editado a (RIF: -). Empresa: \'N/A\' a \'inversores \'. Representante: \'N/A N/A\' a \'franco ana \'. Teléfono: \'N/A-N/A\' a \'0272-8888888\'.', '1000000', 'proveedores', '-'),
(780, '2025-07-12 15:44:51', 'Proveedor Editado', 'Proveedor (RIF: N/A-N/A) editado a (RIF: -). Empresa: \'N/A\' a \'franco\'. Representante: \'N/A N/A\' a \'pedro valdez\'. Teléfono: \'N/A-N/A\' a \'0261-1111112\'.', '1000000', 'proveedores', '-'),
(781, '2025-07-12 15:56:46', 'Proveedor Editado', 'Proveedor (RIF: N/A-N/A) editado a (RIF: C-175109422). Empresa: \'N/A\' a \'\'. Representante: \'N/A N/A\' a \'inversores bastidas \'. Teléfono: \'N/A-N/A\' a \'0272-8888888\'.', '1000000', 'proveedores', 'C-175109422'),
(782, '2025-07-12 15:58:30', 'Proveedor Editado', 'Proveedor (RIF: N/A-N/A) editado a (RIF: C-175109422). Empresa: \'N/A\' a \'\'. Representante: \'N/A N/A\' a \'inversores velez \'. Teléfono: \'N/A-N/A\' a \'0272-8888888\'.', '1000000', 'proveedores', 'C-175109422'),
(783, '2025-07-12 15:59:28', 'Proveedor Editado', 'Proveedor (RIF: N/A-N/A) editado a (RIF: C-175109422). Empresa: \'N/A\' a \'\'. Representante: \'N/A N/A\' a \'inversores velez \'. Teléfono: \'N/A-N/A\' a \'0272-8888888\'.', '1000000', 'proveedores', 'C-175109422'),
(784, '2025-07-12 16:08:02', 'Proveedor Eliminado', 'Proveedor \'rs22222\' (RIF: P-999999999) eliminado. Teléfono: 0412-1074586.', '1000000', 'proveedores', 'P-999999999'),
(785, '2025-07-12 16:14:53', 'Proveedor Editado', 'Proveedor (RIF: N/A-N/A) editado a (RIF: C-175109422). Empresa: \'N/A\' a \'\'. Representante: \'N/A N/A\' a \'inversores velez \'. Teléfono: \'N/A-N/A\' a \'0272-8888888\'.', '1000000', 'proveedores', 'C-175109422'),
(786, '2025-07-12 16:15:33', 'Proveedor Editado', 'Proveedor (RIF: N/A-N/A) editado a (RIF: C-175109422). Empresa: \'N/A\' a \'\'. Representante: \'N/A N/A\' a \'inversores velez \'. Teléfono: \'N/A-N/A\' a \'0272-8888888\'.', '1000000', 'proveedores', 'C-175109422'),
(787, '2025-07-12 16:15:46', 'Proveedor Editado', 'Proveedor (RIF: N/A-N/A) editado a (RIF: G-999999999). Empresa: \'N/A\' a \'\'. Representante: \'N/A N/A\' a \'respuestas prontas \'. Teléfono: \'N/A-N/A\' a \'0272-1074586\'.', '1000000', 'proveedores', 'G-999999999'),
(788, '2025-07-12 16:34:51', 'Proveedor Editado', 'Proveedor (RIF: N/A-N/A) editado a (RIF: J-992222222). Empresa: \'N/A\' a \'\'. Representante: \'N/A N/A\' a \'adidas \'. Teléfono: \'N/A-N/A\' a \'0251-1074586\'.', '1000000', 'proveedores', 'J-992222222'),
(789, '2025-07-12 16:35:17', 'Generación de Reporte PDF', 'Se generó un reporte de Proveedores en formato PDF.', '1000000', 'proveedores', 'N/A'),
(790, '2025-07-12 16:35:19', 'Generación de Reporte PDF', 'Se generó un reporte de Proveedores en formato PDF.', '1000000', 'proveedores', 'N/A'),
(791, '2025-07-12 18:48:16', 'Creación Venta Fallida', 'Intento de crear venta fallido: lista de productos vacía. Factura: 10045.', '1000000', 'ventas', '10045'),
(792, '2025-07-12 18:48:28', 'Venta Creada', 'Nueva venta registrada. Factura: 10045, Cliente: 30124678, Total: 1990.56.', '1000000', 'ventas', '10045'),
(793, '2025-07-12 19:02:32', 'Venta Creada', 'Nueva venta registrada. Factura: 10046, Cliente: 30124678, Total: 1990.56.', '1000000', 'ventas', '10046'),
(794, '2025-07-12 21:18:57', 'Cliente Creado', 'Nuevo cliente \'jose roa\' (Cédula: E-14600272) registrado.', '1000000', 'clientes', 'E-14600272'),
(795, '2025-07-12 21:40:24', 'Edición Cliente Fallida', 'Intento de editar cliente con datos inválidos (nombre: jose, cédula: N/A).', '1000000', 'clientes', 'N/A'),
(796, '2025-07-12 21:58:21', 'Proveedor Creado', 'Nuevo proveedor \'importadora lazaro\' (RIF: J-175109410) registrado. Representante: alberto ramirez. Teléfono: 0272-5861111.', '1000000', 'proveedores', 'J-175109410'),
(797, '2025-07-12 21:58:33', 'Proveedor Editado', 'Proveedor (RIF: N/A-N/A) editado a (RIF: J-175109410). Empresa: \'N/A\' a \'\'. Representante: \'N/A N/A\' a \'importadora lazaros \'. Teléfono: \'N/A-N/A\' a \'0272-5861111\'.', '1000000', 'proveedores', 'J-175109410'),
(798, '2025-07-12 21:58:51', 'Proveedor Editado', 'Proveedor (RIF: N/A-N/A) editado a (RIF: J-175109410). Empresa: \'N/A\' a \'\'. Representante: \'N/A N/A\' a \'importadora lazaros2 \'. Teléfono: \'N/A-N/A\' a \'0272-5861111\'.', '1000000', 'proveedores', 'J-175109410'),
(799, '2025-07-12 21:59:24', 'Proveedor Editado', 'Proveedor (RIF: N/A-N/A) editado a (RIF: J-175109410). Empresa: \'N/A\' a \'\'. Representante: \'N/A N/A\' a \'importadora lazaros2 \'. Teléfono: \'N/A-N/A\' a \'0272-5862222\'.', '1000000', 'proveedores', 'J-175109410'),
(800, '2025-07-12 22:00:10', 'Creación Cliente Fallida', 'Intento de crear cliente con datos inválidos (nombre: francisco, cédula: N/A).', '1000000', 'clientes', 'N/A'),
(801, '2025-07-12 23:14:55', 'Venta Creada', 'Nueva venta registrada. Factura: 10047, Cliente: , Total: 1990.56.', '1000000', 'ventas', '10047'),
(802, '2025-07-12 23:29:58', 'Venta Creada', 'Nueva venta registrada. Factura: 10048, Cliente: , Total: 3649.36.', '1000000', 'ventas', '10048'),
(803, '2025-07-12 23:43:55', 'Venta Creada', 'Nueva venta registrada. Factura: 10049, Cliente: , Total: 3649.36.', '1000000', 'ventas', '10049'),
(804, '2025-07-12 23:49:20', 'Proveedor Eliminado', 'Proveedor \'rs22\' (RIF: J-999999997) eliminado. Teléfono: 0272-1074586.', '1000000', 'proveedores', 'J-999999997'),
(806, '2025-07-13 00:17:10', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 45.313,89 Bs.', '1000000', 'ventas', 'N/A'),
(807, '2025-07-13 00:17:12', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 45.313,89 Bs.', '1000000', 'ventas', 'N/A'),
(808, '2025-07-13 00:42:23', 'Edición Cliente Fallida', 'Intento de editar cliente con datos inválidos (nombre: Ismael, cédula: N/A).', '1000000', 'clientes', 'N/A'),
(809, '2025-07-13 00:47:24', 'Generación de Reporte PDF', 'Se generó un reporte de Clientes en formato PDF.', '1000000', 'clientes', 'N/A'),
(812, '2025-07-13 01:06:17', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(813, '2025-07-13 01:38:07', 'Generación de Reporte PDF', 'Se generó un reporte de Clientes en formato PDF.', '1000000', 'clientes', 'N/A'),
(814, '2025-07-13 01:38:28', 'Generación de Reporte PDF', 'Se generó un reporte de Proveedores en formato PDF.', '1000000', 'proveedores', 'N/A'),
(815, '2025-07-13 01:39:21', 'Cierre de Sesión', 'El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.', '1000000', 'usuarios', 'admin'),
(816, '2025-07-13 01:39:26', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(818, '2025-07-13 01:40:37', 'Generación de Reporte PDF', 'Se generó un reporte de Inventario en formato PDF.', '1000000', 'productos', 'N/A'),
(819, '2025-07-13 01:41:05', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 45.313,89 Bs.', '1000000', 'ventas', 'N/A'),
(820, '2025-07-13 01:41:06', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 45.313,89 Bs.', '1000000', 'ventas', 'N/A'),
(821, '2025-07-13 01:41:27', 'Generación de Reporte PDF', 'Se generó un reporte Mensual de Ventas en formato PDF. Total: 45.313,89 Bs. Período: 07/2025', '1000000', 'ventas', 'N/A'),
(822, '2025-07-13 01:41:28', 'Generación de Reporte PDF', 'Se generó un reporte Mensual de Ventas en formato PDF. Total: 45.313,89 Bs. Período: 07/2025', '1000000', 'ventas', 'N/A'),
(823, '2025-07-13 01:41:56', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 45.313,89 Bs. Rango: Del 01/07/2025 al 31/07/2025', '1000000', 'ventas', 'N/A'),
(824, '2025-07-13 01:41:57', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 45.313,89 Bs. Rango: Del 01/07/2025 al 31/07/2025', '1000000', 'ventas', 'N/A'),
(825, '2025-07-13 01:46:06', 'Proveedor Eliminado', 'Proveedor \'invversiones perez\' (RIF: P-121212121) eliminado. Teléfono: 0272-5861111.', '1000000', 'proveedores', 'P-121212121'),
(826, '2025-07-13 02:56:01', 'Generación de Reporte PDF', 'Se generó un reporte de Clientes en formato PDF.', '1000000', 'clientes', 'N/A'),
(827, '2025-07-13 02:56:16', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 45.313,89 Bs.', '1000000', 'ventas', 'N/A'),
(828, '2025-07-13 02:56:17', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 45.313,89 Bs.', '1000000', 'ventas', 'N/A'),
(829, '2025-07-13 02:59:39', 'Edición Cliente Fallida', 'Intento de editar cliente con datos inválidos (nombre: Ismael, cédula: N/A).', '1000000', 'clientes', 'N/A'),
(830, '2025-07-13 03:05:16', 'Venta Creada', 'Nueva venta registrada. Factura: 10050, Cliente: , Total: 3649.36.', '1000000', 'ventas', '10050'),
(831, '2025-07-13 10:40:28', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 0,00 Bs.', '1000000', 'ventas', 'N/A'),
(832, '2025-07-13 10:40:28', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 0,00 Bs.', '1000000', 'ventas', 'N/A'),
(833, '2025-07-13 10:40:39', 'Generación de Reporte PDF', 'Se generó un reporte Mensual de Ventas en formato PDF. Total: 48.963,25 Bs. Período: 07/2025', '1000000', 'ventas', 'N/A'),
(834, '2025-07-13 10:40:40', 'Generación de Reporte PDF', 'Se generó un reporte Mensual de Ventas en formato PDF. Total: 48.963,25 Bs. Período: 07/2025', '1000000', 'ventas', 'N/A'),
(835, '2025-07-13 10:45:39', 'Generación de Reporte PDF', 'Se generó un reporte Mensual de Ventas en formato PDF. Total: 48.963,25 Bs. Período: 07/2025', '1000000', 'ventas', 'N/A'),
(836, '2025-07-13 10:45:41', 'Generación de Reporte PDF', 'Se generó un reporte Mensual de Ventas en formato PDF. Total: 48.963,25 Bs. Período: 07/2025', '1000000', 'ventas', 'N/A'),
(838, '2025-07-13 11:15:20', 'Generación de Reporte PDF', 'Se generó un reporte de Clientes en formato PDF.', '1000000', 'clientes', 'N/A'),
(839, '2025-07-13 11:21:26', 'Generaci?n de Reporte PDF', 'Se gener? un reporte de Clientes en formato PDF.', '1000000', 'clientes', 'N/A'),
(840, '2025-07-13 11:25:08', 'Generaci?n de Reporte PDF', 'Se gener? un reporte de Clientes en formato PDF.', '1000000', 'clientes', 'N/A'),
(841, '2025-07-13 11:25:11', 'Generaci?n de Reporte PDF', 'Se gener? un reporte de Clientes en formato PDF.', '1000000', 'clientes', 'N/A'),
(842, '2025-07-13 11:25:22', 'Generaci?n de Reporte PDF', 'Se gener? un reporte de Clientes en formato PDF.', '1000000', 'clientes', 'N/A'),
(843, '2025-07-13 11:26:21', 'Generación de Reporte PDF', 'Se generó un reporte de Clientes en formato PDF.', '1000000', 'clientes', 'N/A'),
(844, '2025-07-13 11:26:23', 'Generación de Reporte PDF', 'Se generó un reporte de Clientes en formato PDF.', '1000000', 'clientes', 'N/A'),
(845, '2025-07-13 11:27:02', 'Generación de Reporte PDF', 'Se generó un reporte de Clientes en formato PDF.', '1000000', 'clientes', 'N/A'),
(846, '2025-07-13 12:21:47', 'Creación Cliente Fallida', 'Intento de crear cliente con datos inválidos (nombre: yolimar, cédula: N/A).', '1000000', 'clientes', 'N/A'),
(847, '2025-07-13 12:22:11', 'Creación Cliente Fallida', 'Intento de crear cliente con datos inválidos (nombre: francisco, cédula: N/A).', '1000000', 'clientes', 'N/A'),
(848, '2025-07-13 12:27:11', 'Creación Cliente Fallida', 'Intento de crear cliente con datos inválidos (nombre: yolimar, cédula: N/A).', '1000000', 'clientes', 'N/A'),
(849, '2025-07-13 12:31:42', 'Generación de Reporte PDF', 'Se generó un reporte de Clientes en formato PDF.', '1000000', 'clientes', 'N/A'),
(850, '2025-07-13 12:31:44', 'Generación de Reporte PDF', 'Se generó un reporte de Clientes en formato PDF.', '1000000', 'clientes', 'N/A'),
(851, '2025-07-13 12:32:57', 'Creación Cliente Fallida', 'Intento de crear cliente con datos inválidos (nombre: yolimar, cédula: N/A).', '1000000', 'clientes', 'N/A'),
(852, '2025-07-13 12:34:24', 'Creación Cliente Fallida', 'Intento de crear cliente con datos inválidos (nombre: francisco, cédula: N/A).', '1000000', 'clientes', 'N/A'),
(853, '2025-07-13 12:34:42', 'Creación Cliente Fallida', 'Intento de crear cliente con datos inválidos (nombre: francisco, cédula: N/A).', '1000000', 'clientes', 'N/A'),
(854, '2025-07-13 12:41:34', 'Creación Cliente Fallida', 'Intento de crear cliente con datos inválidos (nombre: francisco, cédula: N/A).', '1000000', 'clientes', 'N/A'),
(855, '2025-07-13 12:42:00', 'Cliente Creado', 'Nuevo cliente \'francisco perdomo\' (Cédula: V-14603333) registrado.', '1000000', 'clientes', 'V-14603333'),
(856, '2025-07-13 13:05:35', 'Proveedor Editado', 'Proveedor (RIF: N/A-N/A) editado a (RIF: C-175109422). Empresa: \'N/A\' a \'\'. Representante: \'N/A N/A\' a \'inversores velezz \'. Teléfono: \'N/A-N/A\' a \'0272-8888888\'.', '1000000', 'proveedores', 'C-175109422'),
(857, '2025-07-13 13:31:26', 'Generación de Reporte PDF', 'Se generó un reporte Mensual de Ventas en formato PDF. Total: 48.963,25 Bs. Período: 07/2025', '1000000', 'ventas', 'N/A'),
(858, '2025-07-13 13:31:27', 'Generación de Reporte PDF', 'Se generó un reporte Mensual de Ventas en formato PDF. Total: 48.963,25 Bs. Período: 07/2025', '1000000', 'ventas', 'N/A'),
(859, '2025-07-13 13:31:44', 'Generación de Reporte PDF', 'Se generó un reporte Total de Ventas en formato PDF. Total: 48.963,25 Bs.', '1000000', 'ventas', 'N/A'),
(860, '2025-07-13 13:31:46', 'Generación de Reporte PDF', 'Se generó un reporte Total de Ventas en formato PDF. Total: 48.963,25 Bs.', '1000000', 'ventas', 'N/A'),
(861, '2025-07-13 13:32:04', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 0,00 Bs.', '1000000', 'ventas', 'N/A'),
(862, '2025-07-13 13:32:06', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 0,00 Bs.', '1000000', 'ventas', 'N/A'),
(863, '2025-07-13 13:35:20', 'Generación de Reporte PDF', 'Se generó un reporte Total de Ventas en formato PDF. Total: 48.963,25 Bs.', '1000000', 'ventas', 'N/A'),
(864, '2025-07-13 13:35:22', 'Generación de Reporte PDF', 'Se generó un reporte Total de Ventas en formato PDF. Total: 48.963,25 Bs.', '1000000', 'ventas', 'N/A'),
(865, '2025-07-13 13:35:35', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 48.963,25 Bs. Rango: Del 01/07/2025 al 31/07/2025', '1000000', 'ventas', 'N/A'),
(866, '2025-07-13 13:35:36', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 48.963,25 Bs. Rango: Del 01/07/2025 al 31/07/2025', '1000000', 'ventas', 'N/A'),
(867, '2025-07-13 13:37:01', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 48.963,25 Bs. Rango: Del 01/07/2025 al 31/07/2025', '1000000', 'ventas', 'N/A'),
(868, '2025-07-13 13:37:04', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 48.963,25 Bs. Rango: Del 01/07/2025 al 31/07/2025', '1000000', 'ventas', 'N/A'),
(869, '2025-07-13 13:37:28', 'Generación de Reporte PDF', 'Se generó un reporte Mensual de Ventas en formato PDF. Total: 48.963,25 Bs. Período: 07/2025', '1000000', 'ventas', 'N/A'),
(870, '2025-07-13 13:37:29', 'Generación de Reporte PDF', 'Se generó un reporte Mensual de Ventas en formato PDF. Total: 48.963,25 Bs. Período: 07/2025', '1000000', 'ventas', 'N/A'),
(871, '2025-07-13 13:39:57', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 48.963,25 Bs. Rango: Del 01/07/2025 al 31/07/2025', '1000000', 'ventas', 'N/A'),
(872, '2025-07-13 13:40:07', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 0,00 Bs. Rango: Del 12/07/2025 al 12/07/2025', '1000000', 'ventas', 'N/A'),
(873, '2025-07-13 13:40:08', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 0,00 Bs. Rango: Del 12/07/2025 al 12/07/2025', '1000000', 'ventas', 'N/A'),
(874, '2025-07-13 13:41:15', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 48.963,25 Bs. Rango: Del 12/07/2025 al 13/07/2025', '1000000', 'ventas', 'N/A'),
(875, '2025-07-13 13:41:16', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 48.963,25 Bs. Rango: Del 12/07/2025 al 13/07/2025', '1000000', 'ventas', 'N/A'),
(876, '2025-07-13 13:45:36', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 292,55 Bs. Rango: Del 08/07/2025 al 12/07/2025', '1000000', 'ventas', 'N/A'),
(877, '2025-07-13 13:45:37', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 292,55 Bs. Rango: Del 08/07/2025 al 12/07/2025', '1000000', 'ventas', 'N/A'),
(878, '2025-07-13 14:04:28', 'Cliente Creado', 'Nuevo cliente \'peter grifin\' (Cédula: E-14600999) registrado.', '1000000', 'clientes', 'E-14600999'),
(879, '2025-07-13 14:52:13', 'Cliente Editado', 'Datos del cliente \'Ismael ana \' (Cédula: -) modificados.', '1000000', 'clientes', '-'),
(880, '2025-07-13 14:52:33', 'Cliente Editado', 'Datos del cliente \'Ismael sdsdsd\' (Cédula: -) modificados.', '1000000', 'clientes', '-'),
(881, '2025-07-13 15:03:46', 'Cliente Editado', 'Datos del cliente \'Ismael valdez\' (Cédula: -) modificados.', '1000000', 'clientes', '-'),
(882, '2025-07-13 15:05:52', 'Cliente Editado', 'Datos del cliente \'Ismael valdez\' (Cédula: -) modificados.', '1000000', 'clientes', '-'),
(883, '2025-07-13 15:10:23', 'Cliente Editado', 'Datos del cliente \'Ismael ana \' (Cédula: -) modificados.', '1000000', 'clientes', '-'),
(884, '2025-07-13 15:17:33', 'Cliente Editado', 'Datos del cliente \'Ismael ana \' (Cédula: -) modificados.', '1000000', 'clientes', '-'),
(885, '2025-07-13 15:39:07', 'Cliente Editado', 'Datos del cliente \'Ismael cabeza arias\' (Cédula: -) modificados.', '1000000', 'clientes', '-'),
(886, '2025-07-13 15:39:35', 'Cliente Editado', 'Datos del cliente \'Ismael ana \' (Cédula: -) modificados.', '1000000', 'clientes', '-'),
(887, '2025-07-13 15:43:31', 'Cliente Editado', 'Datos del cliente \'Ismael ana \' (Cédula: -) modificados.', '1000000', 'clientes', '-'),
(888, '2025-07-13 15:50:41', 'Cliente Editado', 'Datos del cliente \'Ismael arias\' (Cédula: -) modificados.', '1000000', 'clientes', '-'),
(889, '2025-07-13 15:51:04', 'Cliente Editado', 'Datos del cliente \'Ismael cabeza arias\' (Cédula: -) modificados.', '1000000', 'clientes', '-'),
(890, '2025-07-13 15:52:45', 'Cliente Creado', 'Nuevo cliente \'juan perez\' (Cédula: V-10000000) registrado.', '1000000', 'clientes', 'V-10000000'),
(891, '2025-07-13 15:53:37', 'Cliente Editado', 'Datos del cliente \'Ismael ana \' (Cédula: -) modificados.', '1000000', 'clientes', '-'),
(892, '2025-07-13 15:53:56', 'Cliente Editado', 'Datos del cliente \'Ismael sdsdsd\' (Cédula: -) modificados.', '1000000', 'clientes', '-'),
(893, '2025-07-13 15:54:52', 'Cliente Creado', 'Nuevo cliente \'ana ruiz\' (Cédula: E-10000000) registrado.', '1000000', 'clientes', 'E-10000000'),
(894, '2025-07-13 15:55:15', 'Cliente Editado', 'Datos del cliente \'Ismael cabeza arias\' (Cédula: -) modificados.', '1000000', 'clientes', '-'),
(895, '2025-07-13 16:10:53', 'Cliente Eliminado', 'Cliente \'N/A N/A\' (Cédula: undefined) eliminado.', '1000000', 'clientes', 'undefined'),
(896, '2025-07-13 16:15:16', 'Cliente Eliminado', 'Cliente \'N/A N/A\' (Cédula: undefined) eliminado.', '1000000', 'clientes', 'undefined'),
(897, '2025-07-13 16:20:39', 'Cliente Eliminado', 'Cliente \'N/A N/A\' (Cédula: undefined) eliminado.', '1000000', 'clientes', 'undefined');
INSERT INTO `event_log` (`id`, `timestamp`, `event_type`, `description`, `employee_cedula`, `affected_table`, `affected_row_id`) VALUES
(898, '2025-07-13 16:23:45', 'Cliente Eliminado', 'Cliente \'N/A N/A\' (Cédula: undefined) eliminado.', '1000000', 'clientes', 'undefined'),
(899, '2025-07-13 20:11:01', 'Venta Creada', 'Nueva venta registrada. Factura: 10053, Cliente: , Total: 9621.04.', '1000000', 'ventas', '10053'),
(902, '2025-07-13 20:30:54', 'Generación de Reporte PDF', 'Se generó un reporte de Eventos del Sistema en formato PDF.', '1000000', 'event_log', 'N/A'),
(903, '2025-07-13 20:30:55', 'Generación de Reporte PDF', 'Se generó un reporte de Eventos del Sistema en formato PDF.', '1000000', 'event_log', 'N/A'),
(904, '2025-07-13 21:01:49', 'Venta Creada', 'Nueva venta registrada. Factura: 10054, Cliente: , Total: 1990.56.', '1000000', 'ventas', '10054'),
(905, '2025-07-13 21:12:26', 'Creación Venta Fallida', 'Intento de crear venta fallido: lista de productos vacía. Factura: 10055.', '1000000', 'ventas', '10055'),
(906, '2025-07-13 21:20:18', 'Venta Creada', 'Nueva venta registrada. Factura: 10055, Cliente: , Total: 1658.8.', '1000000', 'ventas', '10055'),
(907, '2025-07-13 21:23:09', 'Creación Venta Fallida', 'Intento de crear venta fallido: lista de productos vacía. Factura: 10056.', '1000000', 'ventas', '10056'),
(908, '2025-07-13 21:49:14', 'Categoría Editada', 'Categoría \'Ropa\' (ID: 7) modificada a \'Ropas\'.', '1000000', 'categorias', '7'),
(909, '2025-07-13 21:58:59', 'Creación Venta Fallida', 'Intento de crear venta fallido: lista de productos vacía. Factura: 10056.', '1000000', 'ventas', '10056'),
(910, '2025-07-14 00:12:48', 'Producto Editado', 'Producto \'pantalon\' (Código: 2323233-2) editado.', '1000000', 'productos', '2323233-2'),
(911, '2025-07-14 00:14:48', 'Producto Editado', 'Producto \'cHEMISE\' (Código: 121342-2) editado.', '1000000', 'productos', '121342-2'),
(912, '2025-07-14 00:32:17', 'Producto Creado', 'Producto \'bota alta\' (Código: 121342-2-abc) creado.', '1000000', 'productos', '121342-2-abc'),
(913, '2025-07-14 00:34:05', 'Producto Creado', 'Producto \'zapatos colores\' (Código: ard-093-as34) creado.', '1000000', 'productos', 'ard-093-as34'),
(914, '2025-07-14 00:34:40', 'Generación de Reporte PDF', 'Se generó un reporte de Eventos del Sistema en formato PDF.', '1000000', 'event_log', 'N/A'),
(915, '2025-07-14 00:34:44', 'Generación de Reporte PDF', 'Se generó un reporte de Eventos del Sistema en formato PDF.', '1000000', 'event_log', 'N/A'),
(916, '2025-07-14 00:35:08', 'Generación de Reporte PDF', 'Se generó un reporte de Inventario en formato PDF.', '1000000', 'productos', 'N/A'),
(917, '2025-07-14 00:39:45', 'Generación de Reporte PDF', 'Se generó un reporte de Inventario en formato PDF.', '1000000', 'productos', 'N/A'),
(918, '2025-07-14 00:39:46', 'Generación de Reporte PDF', 'Se generó un reporte de Inventario en formato PDF.', '1000000', 'productos', 'N/A'),
(919, '2025-07-14 00:43:15', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 18.910,32 Bs.', '1000000', 'ventas', 'N/A'),
(920, '2025-07-14 00:43:15', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 18.910,32 Bs.', '1000000', 'ventas', 'N/A'),
(921, '2025-07-14 00:44:24', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 18.910,32 Bs.', '1000000', 'ventas', 'N/A'),
(922, '2025-07-14 00:44:26', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 18.910,32 Bs.', '1000000', 'ventas', 'N/A'),
(923, '2025-07-14 00:44:39', 'Generación de Reporte PDF', 'Se generó un reporte de Clientes en formato PDF.', '1000000', 'clientes', 'N/A'),
(924, '2025-07-14 00:44:39', 'Generación de Reporte PDF', 'Se generó un reporte de Clientes en formato PDF.', '1000000', 'clientes', 'N/A'),
(925, '2025-07-14 00:44:49', 'Generación de Reporte PDF', 'Se generó un reporte de Proveedores en formato PDF.', '1000000', 'proveedores', 'N/A'),
(926, '2025-07-14 00:44:50', 'Generación de Reporte PDF', 'Se generó un reporte de Proveedores en formato PDF.', '1000000', 'proveedores', 'N/A'),
(927, '2025-07-14 01:06:33', 'Generación de Reporte PDF', 'Se generó un reporte de Inventario en formato PDF.', '1000000', 'productos', 'N/A'),
(928, '2025-07-14 01:06:35', 'Generación de Reporte PDF', 'Se generó un reporte de Inventario en formato PDF.', '1000000', 'productos', 'N/A'),
(929, '2025-07-14 01:07:06', 'Generación de Reporte PDF', 'Se generó un reporte de Inventario en formato PDF.', '1000000', 'productos', 'N/A'),
(930, '2025-07-14 01:07:07', 'Generación de Reporte PDF', 'Se generó un reporte de Inventario en formato PDF.', '1000000', 'productos', 'N/A'),
(931, '2025-07-14 01:09:20', 'Generación de Reporte PDF', 'Se generó un reporte de Proveedores en formato PDF.', '1000000', 'proveedores', 'N/A'),
(932, '2025-07-14 01:09:22', 'Generación de Reporte PDF', 'Se generó un reporte de Proveedores en formato PDF.', '1000000', 'proveedores', 'N/A'),
(933, '2025-07-14 01:09:50', 'Generación de Reporte PDF', 'Se generó un reporte de Clientes en formato PDF.', '1000000', 'clientes', 'N/A'),
(934, '2025-07-14 01:09:51', 'Generación de Reporte PDF', 'Se generó un reporte de Clientes en formato PDF.', '1000000', 'clientes', 'N/A'),
(935, '2025-07-14 01:10:24', 'Generación de Reporte PDF', 'Se generó un reporte Total de Ventas en formato PDF. Total: 67.873,57 Bs.', '1000000', 'ventas', 'N/A'),
(936, '2025-07-14 01:10:25', 'Generación de Reporte PDF', 'Se generó un reporte Total de Ventas en formato PDF. Total: 67.873,57 Bs.', '1000000', 'ventas', 'N/A'),
(937, '2025-07-14 01:10:56', 'Generación de Reporte PDF', 'Se generó un reporte Mensual de Ventas en formato PDF. Total: 67.873,57 Bs. Período: 07/2025', '1000000', 'ventas', 'N/A'),
(938, '2025-07-14 01:10:57', 'Generación de Reporte PDF', 'Se generó un reporte Mensual de Ventas en formato PDF. Total: 67.873,57 Bs. Período: 07/2025', '1000000', 'ventas', 'N/A'),
(939, '2025-07-14 01:12:15', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 67.873,57 Bs. Rango: Del 01/07/2025 al 31/07/2025', '1000000', 'ventas', 'N/A'),
(940, '2025-07-14 01:12:16', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 67.873,57 Bs. Rango: Del 01/07/2025 al 31/07/2025', '1000000', 'ventas', 'N/A'),
(941, '2025-07-14 01:15:16', 'Generación de Reporte PDF', 'Se generó un reporte Mensual de Ventas en formato PDF. Total: 67.873,57 Bs. Período: 07/2025', '1000000', 'ventas', 'N/A'),
(942, '2025-07-14 01:15:17', 'Generación de Reporte PDF', 'Se generó un reporte Mensual de Ventas en formato PDF. Total: 67.873,57 Bs. Período: 07/2025', '1000000', 'ventas', 'N/A'),
(943, '2025-07-14 01:16:59', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 67.873,57 Bs. Rango: Del 01/07/2025 al 31/07/2025', '1000000', 'ventas', 'N/A'),
(944, '2025-07-14 01:17:01', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 67.873,57 Bs. Rango: Del 01/07/2025 al 31/07/2025', '1000000', 'ventas', 'N/A'),
(945, '2025-07-14 01:18:15', 'Generación de Reporte PDF', 'Se generó un reporte Mensual de Ventas en formato PDF. Total: 67.873,57 Bs. Período: 07/2025', '1000000', 'ventas', 'N/A'),
(946, '2025-07-14 01:18:17', 'Generación de Reporte PDF', 'Se generó un reporte Mensual de Ventas en formato PDF. Total: 67.873,57 Bs. Período: 07/2025', '1000000', 'ventas', 'N/A'),
(947, '2025-07-14 01:18:26', 'Generación de Reporte PDF', 'Se generó un reporte Total de Ventas en formato PDF. Total: 67.873,57 Bs.', '1000000', 'ventas', 'N/A'),
(948, '2025-07-14 01:18:28', 'Generación de Reporte PDF', 'Se generó un reporte Total de Ventas en formato PDF. Total: 67.873,57 Bs.', '1000000', 'ventas', 'N/A'),
(949, '2025-07-14 01:19:02', 'Generación de Reporte PDF', 'Se generó un reporte Total de Ventas en formato PDF. Total: 67.873,57 Bs.', '1000000', 'ventas', 'N/A'),
(950, '2025-07-14 01:19:03', 'Generación de Reporte PDF', 'Se generó un reporte Total de Ventas en formato PDF. Total: 67.873,57 Bs.', '1000000', 'ventas', 'N/A'),
(951, '2025-07-14 01:22:20', 'Generación de Reporte PDF', 'Se generó un reporte Total de Ventas en formato PDF. Total: 67.873,57 Bs.', '1000000', 'ventas', 'N/A'),
(952, '2025-07-14 01:22:23', 'Generación de Reporte PDF', 'Se generó un reporte Total de Ventas en formato PDF. Total: 67.873,57 Bs.', '1000000', 'ventas', 'N/A'),
(953, '2025-07-14 12:59:30', 'Cierre de Sesión', 'El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.', '1000000', 'usuarios', 'admin'),
(954, '2025-07-15 08:16:05', 'Categoría Creada', 'Nueva categoría \'hola\' registrada.', '1000000', 'categorias', 'hola'),
(955, '2025-07-15 08:16:13', 'Categoría Editada', 'Categoría \'hola\' (ID: 13) modificada a \'holas\'.', '1000000', 'categorias', '13'),
(956, '2025-07-15 08:16:22', 'Categoría Editada', 'Categoría \'holas\' (ID: 13) modificada a \'Holas\'.', '1000000', 'categorias', '13'),
(957, '2025-07-15 08:16:28', 'Categoría Eliminada', 'Categoría \'Holas\' (ID: 13) eliminada.', '1000000', 'categorias', '13'),
(958, '2025-07-15 08:25:49', 'Categoría Creada', 'Nueva categoría \'hola\' registrada.', '1000000', 'categorias', 'hola'),
(959, '2025-07-15 08:25:54', 'Categoría Eliminada', 'Categoría \'hola\' (ID: 14) eliminada.', '1000000', 'categorias', '14'),
(960, '2025-07-15 08:37:58', 'Categoría Creada', 'Nueva categoría \'hola\' registrada.', '1000000', 'categorias', 'hola'),
(961, '2025-07-15 08:48:26', 'Eliminación Categoría Restringida', 'Intento de eliminar categoría \'Calzados\' (ID: 9) fallido: está relacionada con otros registros.', '1000000', 'categorias', '9'),
(962, '2025-07-15 08:54:01', 'Categoría Editada', 'Categoría \'Calzados\' (ID: 9) modificada a \'Calzado\'.', '1000000', 'categorias', '9'),
(963, '2025-07-15 09:23:54', 'Edición Cliente Fallida', 'Intento de editar cliente con datos inválidos (nombre: Ismael, cédula: ).', '1000000', 'clientes', ''),
(964, '2025-07-15 09:44:48', 'Edición Cliente Fallida', 'Intento de editar cliente con datos inválidos (nombre: Ismael, cédula: ).', '1000000', 'clientes', ''),
(965, '2025-07-15 09:48:02', 'Cliente Editado', 'Datos del cliente \'Ismael cabeza arias\' (Cédula: -) modificados.', '1000000', 'clientes', '-'),
(966, '2025-07-15 10:20:16', 'Cliente Editado', 'Datos del cliente \'Ismael sdsdsd\' (Cédula: -) modificados.', '1000000', 'clientes', '-'),
(967, '2025-07-15 10:20:55', 'Cliente Editado', 'Datos del cliente \'Ismael ana \' (Cédula: -) modificados.', '1000000', 'clientes', '-'),
(968, '2025-07-15 10:23:42', 'Edición Cliente Fallida', 'Intento de editar cliente con datos inválidos (nombre: Ismael, cédula: ).', '1000000', 'clientes', ''),
(969, '2025-07-15 10:24:16', 'Edición Cliente Fallida', 'Intento de editar cliente con datos inválidos (nombre: Ismael, cédula: ).', '1000000', 'clientes', ''),
(970, '2025-07-15 10:24:56', 'Edición Cliente Fallida', 'Intento de editar cliente con datos inválidos (nombre: Ismael, cédula: ).', '1000000', 'clientes', ''),
(971, '2025-07-15 10:25:31', 'Edición Cliente Fallida', 'Intento de editar cliente con datos inválidos (nombre: francisco, cédula: ).', '1000000', 'clientes', ''),
(972, '2025-07-15 10:26:03', 'Edición Cliente Fallida', 'Intento de editar cliente con datos inválidos (nombre: francisco, cédula: ).', '1000000', 'clientes', ''),
(973, '2025-07-15 10:26:54', 'Edición Cliente Fallida', 'Intento de editar cliente con datos inválidos (nombre: Ismael, cédula: ).', '1000000', 'clientes', ''),
(974, '2025-07-15 10:37:55', 'Cliente Editado', 'Datos del cliente \'anas ruiz\' (Cédula: E-10000000) modificados.', '1000000', 'clientes', 'E-10000000'),
(975, '2025-07-15 10:38:18', 'Cliente Editado', 'Datos del cliente \'anas ruizx\' (Cédula: E-10000000) modificados.', '1000000', 'clientes', 'E-10000000'),
(976, '2025-07-15 10:39:32', 'Cliente Editado', 'Datos del cliente \'anas ruizx\' (Cédula: E-10000000) modificados.', '1000000', 'clientes', 'E-10000000'),
(977, '2025-07-15 10:45:18', 'Edición Cliente Fallida', 'Intento de editar cliente con datos inválidos (nombre: anas, cédula: ).', '1000000', 'clientes', ''),
(978, '2025-07-15 10:46:23', 'Edición Cliente Fallida', 'Intento de editar cliente con datos inválidos (nombre: anas, cédula: ).', '1000000', 'clientes', ''),
(979, '2025-07-15 10:48:02', 'Edición Cliente Fallida', 'Intento de editar cliente con datos inválidos (nombre: jose angeles, cédula: ).', '1000000', 'clientes', ''),
(980, '2025-07-15 10:48:31', 'Edición Cliente Fallida', 'Intento de editar cliente con datos inválidos (nombre: jose angeles, cédula: ).', '1000000', 'clientes', ''),
(981, '2025-07-15 10:51:57', 'Edición Cliente Fallida', 'Intento de editar cliente con datos inválidos (nombre: jose angeles, cédula: ).', '1000000', 'clientes', ''),
(982, '2025-07-15 11:01:27', 'Edición Cliente Fallida', 'Intento de editar cliente con datos inválidos (nombre: anas, cédula: ).', '1000000', 'clientes', ''),
(983, '2025-07-15 11:02:21', 'Cliente Editado', 'Datos del cliente \'anas ruizxx\' (Cédula: E-10000000) modificados.', '1000000', 'clientes', 'E-10000000'),
(984, '2025-07-15 11:04:28', 'Cliente Editado', 'Datos del cliente \'anas ruiz\' (Cédula: E-10000000) modificados.', '1000000', 'clientes', 'E-10000000'),
(985, '2025-07-15 11:09:07', 'Edición Cliente Fallida', 'Intento de editar cliente con datos inválidos (nombre: anas, cédula: ).', '1000000', 'clientes', ''),
(986, '2025-07-15 11:09:33', 'Edición Cliente Fallida', 'Intento de editar cliente con datos inválidos (nombre: anas, cédula: ).', '1000000', 'clientes', ''),
(987, '2025-07-15 11:09:46', 'Edición Cliente Fallida', 'Intento de editar cliente con datos inválidos (nombre: jose, cédula: ).', '1000000', 'clientes', ''),
(988, '2025-07-15 11:10:23', 'Cliente Editado', 'Datos del cliente \'anas ruiz\' (Cédula: E-10000000) modificados.', '1000000', 'clientes', 'E-10000000'),
(989, '2025-07-15 11:11:25', 'Edición Cliente Fallida', 'Intento de editar cliente con datos inválidos (nombre: anas, cédula: ).', '1000000', 'clientes', ''),
(990, '2025-07-15 11:12:13', 'Edición Cliente Fallida', 'Intento de editar cliente con datos inválidos (nombre: anas, cédula: ).', '1000000', 'clientes', ''),
(991, '2025-07-15 11:12:47', 'Cliente Editado', 'Datos del cliente \'anas ruiz\' (Cédula: E-10000000) modificados.', '1000000', 'clientes', 'E-10000000'),
(992, '2025-07-15 11:13:20', 'Edición Cliente Fallida', 'Intento de editar cliente con datos inválidos (nombre: jose, cédula: ).', '1000000', 'clientes', ''),
(993, '2025-07-15 11:13:47', 'Edición Cliente Fallida', 'Intento de editar cliente con datos inválidos (nombre: jose, cédula: ).', '1000000', 'clientes', ''),
(994, '2025-07-15 11:14:16', 'Edición Cliente Fallida', 'Intento de editar cliente con datos inválidos (nombre: jose, cédula: ).', '1000000', 'clientes', ''),
(995, '2025-07-15 11:16:09', 'Cliente Editado', 'Datos del cliente \'anas ruiz perdomo\' (Cédula: E-10000000) modificados.', '1000000', 'clientes', 'E-10000000'),
(996, '2025-07-15 12:06:07', 'Cliente Editado', 'Datos del cliente \'anas ruiz perdomo\' (Cédula: E-10000000) modificados.', '1000000', 'clientes', 'E-10000000'),
(997, '2025-07-15 12:06:18', 'Cliente Editado', 'Datos del cliente \'jose roa\' (Cédula: E-14600272) modificados.', '1000000', 'clientes', 'E-14600272'),
(998, '2025-07-15 12:28:42', 'Proveedor Eliminado', 'Proveedor \'importadora lazaros2\' (RIF: J-175109410) eliminado. Teléfono: 0272-5862222.', '1000000', 'proveedores', 'J-175109410'),
(999, '2025-07-15 12:35:12', 'Cliente Creado', 'Nuevo cliente \'joel bptista\' (Cédula: E-17600272) registrado.', '1000000', 'clientes', 'E-17600272'),
(1000, '2025-07-15 12:52:41', 'Cliente Creado', 'Nuevo cliente \'yolimar err\' (Cédula: E-44444444) registrado.', '1000000', 'clientes', 'E-44444444'),
(1001, '2025-07-15 13:43:37', 'Cierre de Sesión', 'El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.', '1000000', 'usuarios', 'admin'),
(1002, '2012-11-01 04:33:28', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1003, '2012-11-01 04:37:58', 'Generación de Reporte PDF', 'Se generó un reporte de Inventario en formato PDF.', '1000000', 'productos', 'N/A'),
(1004, '2012-11-01 04:37:59', 'Generación de Reporte PDF', 'Se generó un reporte de Inventario en formato PDF.', '1000000', 'productos', 'N/A'),
(1005, '2012-11-01 04:41:20', 'Generación de Reporte PDF', 'Se generó un reporte de Proveedores en formato PDF.', '1000000', 'proveedores', 'N/A'),
(1006, '2012-11-01 04:41:20', 'Generación de Reporte PDF', 'Se generó un reporte de Proveedores en formato PDF.', '1000000', 'proveedores', 'N/A'),
(1007, '2025-07-16 10:34:23', 'Login Fallido', 'Contraseña incorrecta para el usuario: \'ADMIN\'.', '1000000', 'usuarios', 'ADMIN'),
(1008, '2025-07-16 10:34:30', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1009, '2025-07-16 10:53:17', 'Cierre de Sesión', 'El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.', '1000000', 'usuarios', 'admin'),
(1010, '2025-07-16 10:53:22', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1011, '2025-07-16 11:04:26', 'Cierre de Sesión', 'El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.', '1000000', 'usuarios', 'admin'),
(1012, '2025-07-16 11:07:18', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1013, '2025-07-17 14:02:08', 'Tipo Editado', 'Tipo \'N/A\' (ID: ) modificado a \'caballeros\'.', '1000000', 'tipos', ''),
(1014, '2025-07-17 14:02:24', 'Tipo Editado', 'Tipo \'niño\' (ID: 6) modificado a \'niños\'.', '1000000', 'tipos', '6'),
(1015, '2025-07-17 14:03:05', 'Tipo Editado', 'Tipo \'N/A\' (ID: ) modificado a \'niñas\'.', '1000000', 'tipos', ''),
(1016, '2025-07-17 14:11:46', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 67.873,57 Bs. Rango: Del 01/07/2025 al 31/07/2025', '1000000', 'ventas', 'N/A'),
(1017, '2025-07-17 14:11:47', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 67.873,57 Bs. Rango: Del 01/07/2025 al 31/07/2025', '1000000', 'ventas', 'N/A'),
(1018, '2025-07-17 14:11:51', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 67.873,57 Bs. Rango: Del 01/07/2025 al 31/07/2025', '1000000', 'ventas', 'N/A'),
(1019, '2025-07-17 17:41:51', 'Cierre de Sesión', 'El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.', '1000000', 'usuarios', 'admin'),
(1020, '2025-07-17 17:42:56', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1021, '2025-07-17 17:46:23', 'Cierre de Sesión', 'El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.', '1000000', 'usuarios', 'admin'),
(1022, '2025-07-17 17:48:13', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1023, '2025-07-17 17:48:46', 'Cierre de Sesión', 'El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.', '1000000', 'usuarios', 'admin'),
(1024, '2025-07-17 17:49:36', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1025, '2025-07-17 19:25:23', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 67.873,57 Bs. Rango: Del 01/07/2025 al 31/07/2025', '1000000', 'ventas', 'N/A'),
(1026, '2025-07-17 22:18:33', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J123456111) modificada. Detalles: Precio Dólar: Anterior 110,00 , Nuevo 120,00 .', '1000000', 'empresa', 'J123456111'),
(1027, '2025-07-17 22:20:13', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J123456111) modificada. Detalles: Precio Dólar: Anterior 120,00 , Nuevo 121,00 .', '1000000', 'empresa', 'J123456111'),
(1028, '2025-07-17 22:20:24', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J123456789) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J123456789'),
(1029, '2025-07-17 22:20:33', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J123456789) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J123456789'),
(1030, '2025-07-17 22:32:20', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J123456789) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J123456789'),
(1031, '2025-07-17 22:49:34', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J123456789) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J123456789'),
(1032, '2025-07-17 22:53:17', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J123456789) modificada. Detalles: Precio Dólar: Anterior 121,00 , Nuevo 1.214,00 .', '1000000', 'empresa', 'J123456789'),
(1033, '2025-07-17 22:53:37', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J123456789) modificada. Detalles: Precio Dólar: Anterior 1.214,00 , Nuevo 127,00 .', '1000000', 'empresa', 'J123456789'),
(1034, '2025-07-17 22:55:22', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J123456789) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J123456789'),
(1035, '2025-07-17 23:00:27', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J123456788) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J123456788'),
(1036, '2025-07-17 23:01:05', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J123456788) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J123456788'),
(1037, '2025-07-17 23:01:18', 'Edición Configuración Fallida', 'Intento de editar configuración con datos inválidos (tipo_rif: J, num_rif: 123456788, nombre: Kamyl Styl y Algo mas de Susana Al Chariti_).', '1000000', 'empresa', 'J123456788'),
(1038, '2025-07-17 23:05:32', 'Marca Eliminada', 'Marca \'pocholin\' (ID: 11) eliminada.', '1000000', 'marcas', '11'),
(1039, '2025-07-17 23:07:24', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 0,00 Bs.', '1000000', 'ventas', 'N/A'),
(1040, '2025-07-17 23:07:25', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 0,00 Bs.', '1000000', 'ventas', 'N/A'),
(1041, '2025-07-17 23:08:39', 'Cierre de Sesión', 'El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.', '1000000', 'usuarios', 'admin'),
(1042, '2025-07-17 23:22:57', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1043, '2025-07-17 23:23:38', 'Cierre de Sesión', 'El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.', '1000000', 'usuarios', 'admin'),
(1044, '2025-07-17 23:28:43', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1045, '2025-07-17 23:31:00', 'Empleado Creado', 'Nuevo empleado \'francelia arias\' (Cédula: V-17049561) registrado con éxito.', '1000000', 'empleado', 'V-17049561'),
(1046, '2025-07-17 23:32:12', 'Categoría Editada', 'Categoría \'Ropas\' (ID: 7) modificada a \'Ropa\'.', '1000000', 'categorias', '7'),
(1047, '2025-07-17 23:32:37', 'Categoría Creada', 'Nueva categoría \'medias\' registrada.', '1000000', 'categorias', 'medias'),
(1048, '2025-07-17 23:38:48', 'Generación de Reporte PDF', 'Se generó un reporte de Inventario en formato PDF.', '1000000', 'productos', 'N/A'),
(1049, '2025-07-17 23:38:49', 'Generación de Reporte PDF', 'Se generó un reporte de Inventario en formato PDF.', '1000000', 'productos', 'N/A'),
(1050, '2025-07-17 23:40:53', 'Cliente Creado', 'Nuevo cliente \'france perdomo\' (Cédula: V-17049561) registrado.', '1000000', 'clientes', 'V-17049561'),
(1051, '2025-07-17 23:42:47', 'Venta Creada', 'Nueva venta registrada. Factura: 10056, Cliente: , Total: 5745.48.', '1000000', 'ventas', '10056'),
(1052, '2025-07-17 23:44:27', 'Venta Creada', 'Nueva venta registrada. Factura: 10057, Cliente: , Total: 42516.552.', '1000000', 'ventas', '10057'),
(1053, '2025-07-17 23:44:53', 'Generación de Reporte PDF', 'Se generó un reporte de Inventario en formato PDF.', '1000000', 'productos', 'N/A'),
(1054, '2025-07-17 23:44:53', 'Generación de Reporte PDF', 'Se generó un reporte de Inventario en formato PDF.', '1000000', 'productos', 'N/A'),
(1055, '2025-07-17 23:45:26', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 48.262,08 Bs.', '1000000', 'ventas', 'N/A'),
(1056, '2025-07-17 23:45:27', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 48.262,08 Bs.', '1000000', 'ventas', 'N/A'),
(1057, '2025-07-17 23:46:15', 'Generación de Reporte PDF', 'Se generó un reporte Mensual de Ventas en formato PDF. Total: 116.135,65 Bs. Período: 07/2025', '1000000', 'ventas', 'N/A'),
(1058, '2025-07-17 23:46:16', 'Generación de Reporte PDF', 'Se generó un reporte Mensual de Ventas en formato PDF. Total: 116.135,65 Bs. Período: 07/2025', '1000000', 'ventas', 'N/A'),
(1059, '2025-07-17 23:48:52', 'Venta Creada', 'Nueva venta registrada. Factura: 10058, Cliente: , Total: 2298.192.', '1000000', 'ventas', '10058'),
(1060, '2025-07-17 23:58:28', 'Venta Creada', 'Nueva venta registrada. Factura: 10059, Cliente: , Total: 13208.', '1000000', 'ventas', '10059'),
(1061, '2025-07-17 23:59:07', 'Venta Creada', 'Nueva venta registrada. Factura: 10060, Cliente: , Total: 3830.32.', '1000000', 'ventas', '10060'),
(1062, '2025-07-18 00:01:02', 'Venta Creada', 'Nueva venta registrada. Factura: 10061, Cliente: , Total: 2298.192.', '1000000', 'ventas', '10061'),
(1063, '2025-07-18 00:03:21', 'Venta Creada', 'Nueva venta registrada. Factura: 10062, Cliente: , Total: 2298.192.', '1000000', 'ventas', '10062'),
(1064, '2025-07-18 00:11:38', 'Venta Creada', 'Nueva venta registrada. Factura: 10063, Cliente: E-14600999, Total: 2298.192.', '1000000', 'ventas', '10063'),
(1065, '2025-07-18 00:15:42', 'Venta Creada', 'Nueva venta registrada. Factura: 10064, Cliente: , Total: 2298.192.', '1000000', 'ventas', '10064'),
(1066, '2025-07-18 00:17:37', 'Venta Creada', 'Nueva venta registrada. Factura: 10065, Total: 2298.192.', '1000000', 'ventas', '10065'),
(1067, '2025-07-18 00:23:10', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 79.089,54 Bs.', '1000000', 'ventas', 'N/A'),
(1068, '2025-07-18 00:23:10', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 79.089,54 Bs.', '1000000', 'ventas', 'N/A'),
(1069, '2025-07-18 00:24:40', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 146.963,11 Bs. Rango: Del 01/07/2025 al 31/07/2025', '1000000', 'ventas', 'N/A'),
(1070, '2025-07-18 00:24:41', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 146.963,11 Bs. Rango: Del 01/07/2025 al 31/07/2025', '1000000', 'ventas', 'N/A'),
(1071, '2025-07-18 00:25:07', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 146.963,11 Bs. Rango: Del 01/07/2025 al 31/07/2025', '1000000', 'ventas', 'N/A'),
(1072, '2025-07-18 00:25:10', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 146.963,11 Bs. Rango: Del 01/07/2025 al 31/07/2025', '1000000', 'ventas', 'N/A'),
(1073, '2025-07-18 00:25:56', 'Generación de Reporte PDF', 'Se generó un reporte Mensual de Ventas en formato PDF. Total: 146.963,11 Bs. Período: 07/2025', '1000000', 'ventas', 'N/A'),
(1074, '2025-07-18 00:25:57', 'Generación de Reporte PDF', 'Se generó un reporte Mensual de Ventas en formato PDF. Total: 146.963,11 Bs. Período: 07/2025', '1000000', 'ventas', 'N/A'),
(1075, '2025-07-18 00:29:47', 'Venta Creada', 'Nueva venta registrada. Factura: 10066, Cliente: , Total: 2298.192.', '1000000', 'ventas', '10066'),
(1076, '2025-07-18 00:32:50', 'Venta Creada', 'Nueva venta registrada. Factura: 10067, Cliente: jose roa, Total: 2298.192.', '1000000', 'ventas', '10067'),
(1077, '2025-07-18 00:33:06', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 83.685,92 Bs.', '1000000', 'ventas', 'N/A'),
(1078, '2025-07-18 00:33:41', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 83.685,92 Bs.', '1000000', 'ventas', 'N/A'),
(1079, '2025-07-18 00:33:48', 'Generación de Reporte PDF', 'Se generó un reporte Mensual de Ventas en formato PDF. Total: 151.559,49 Bs. Período: 07/2025', '1000000', 'ventas', 'N/A'),
(1080, '2025-07-18 00:33:49', 'Generación de Reporte PDF', 'Se generó un reporte Mensual de Ventas en formato PDF. Total: 151.559,49 Bs. Período: 07/2025', '1000000', 'ventas', 'N/A'),
(1081, '2025-07-18 00:34:25', 'Generación de Reporte PDF', 'Se generó un reporte Total de Ventas en formato PDF. Total: 151.559,49 Bs.', '1000000', 'ventas', 'N/A'),
(1082, '2025-07-18 00:34:26', 'Generación de Reporte PDF', 'Se generó un reporte Total de Ventas en formato PDF. Total: 151.559,49 Bs.', '1000000', 'ventas', 'N/A'),
(1083, '2025-07-18 00:35:08', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 67.581,02 Bs. Rango: Del 11/07/2025 al 17/07/2025', '1000000', 'ventas', 'N/A'),
(1084, '2025-07-18 00:35:09', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 67.581,02 Bs. Rango: Del 11/07/2025 al 17/07/2025', '1000000', 'ventas', 'N/A'),
(1085, '2025-07-18 00:36:05', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 111,59 Bs. Rango: Del 09/07/2025 al 10/07/2025', '1000000', 'ventas', 'N/A'),
(1086, '2025-07-18 00:36:05', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 111,59 Bs. Rango: Del 09/07/2025 al 10/07/2025', '1000000', 'ventas', 'N/A'),
(1087, '2025-07-18 00:36:47', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 151.559,49 Bs. Rango: Del 01/07/2025 al 31/07/2025', '1000000', 'ventas', 'N/A'),
(1088, '2025-07-18 00:36:49', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 151.559,49 Bs. Rango: Del 01/07/2025 al 31/07/2025', '1000000', 'ventas', 'N/A'),
(1089, '2025-07-18 00:41:01', 'Generación de Reporte PDF', 'Se generó un reporte Total de Ventas en formato PDF. Total: 151.559,49 Bs.', '1000000', 'ventas', 'N/A'),
(1090, '2025-07-18 00:41:04', 'Generación de Reporte PDF', 'Se generó un reporte Total de Ventas en formato PDF. Total: 151.559,49 Bs.', '1000000', 'ventas', 'N/A'),
(1091, '2025-07-18 00:42:36', 'Generación de Reporte PDF', 'Se generó un reporte Total de Ventas en formato PDF. Total: 151.559,49 Bs.', '1000000', 'ventas', 'N/A'),
(1092, '2025-07-18 00:42:37', 'Generación de Reporte PDF', 'Se generó un reporte Total de Ventas en formato PDF. Total: 151.559,49 Bs.', '1000000', 'ventas', 'N/A'),
(1093, '2025-07-18 00:45:47', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 83.685,92 Bs.', '1000000', 'ventas', 'N/A'),
(1094, '2025-07-18 00:45:48', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 83.685,92 Bs.', '1000000', 'ventas', 'N/A'),
(1095, '2025-07-18 00:47:51', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 83.685,92 Bs.', '1000000', 'ventas', 'N/A'),
(1096, '2025-07-18 00:47:53', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 83.685,92 Bs.', '1000000', 'ventas', 'N/A'),
(1097, '2025-07-18 00:49:25', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 151.559,49 Bs. Rango: Del 01/07/2025 al 31/07/2025', '1000000', 'ventas', 'N/A'),
(1098, '2025-07-18 00:49:27', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 151.559,49 Bs. Rango: Del 01/07/2025 al 31/07/2025', '1000000', 'ventas', 'N/A'),
(1099, '2025-07-18 00:51:35', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 83.685,92 Bs.', '1000000', 'ventas', 'N/A'),
(1100, '2025-07-18 00:51:35', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 83.685,92 Bs.', '1000000', 'ventas', 'N/A'),
(1101, '2025-07-18 00:53:51', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 83.685,92 Bs.', '1000000', 'ventas', 'N/A'),
(1102, '2025-07-18 00:53:53', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 83.685,92 Bs.', '1000000', 'ventas', 'N/A'),
(1103, '2025-07-18 00:55:50', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 0,00 Bs. Rango: Del 01/06/2025 al 30/06/2025', '1000000', 'ventas', 'N/A'),
(1104, '2025-07-18 00:55:51', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 0,00 Bs. Rango: Del 01/06/2025 al 30/06/2025', '1000000', 'ventas', 'N/A'),
(1105, '2025-07-18 01:01:21', 'Creación Venta Fallida', 'Intento de crear venta fallido: lista de productos vacía. Factura: 10068.', '1000000', 'ventas', '10068'),
(1106, '2025-07-18 01:28:52', 'Usuario Editado', 'Usuario \'FRANCO\' (Cédula: ) editado con perfil \'Vendedor\'.', '1000000', 'usuarios', NULL),
(1107, '2025-07-18 01:28:59', 'Cierre de Sesión', 'El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.', '1000000', 'usuarios', 'admin'),
(1108, '2025-07-18 01:29:15', 'Login Fallido', 'Contraseña incorrecta para el usuario: \'franco\'.', 'V-14600272', 'usuarios', 'franco'),
(1109, '2025-07-18 01:29:31', 'Login Exitoso', 'El empleado \'FRANCO\' (Vendedor) ha iniciado sesión.', 'V-14600272', 'usuarios', 'FRANCO'),
(1110, '2025-07-18 01:46:55', 'Cierre de Sesión', 'El usuario \'FRANCO\' (Cédula: V-14600272, Perfil: Vendedor) ha cerrado sesión.', 'V-14600272', 'usuarios', 'FRANCO'),
(1111, '2025-07-18 01:47:01', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1112, '2025-07-18 01:49:27', 'Cierre de Sesión', 'El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.', '1000000', 'usuarios', 'admin'),
(1113, '2025-07-18 05:23:49', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1114, '2025-07-18 05:34:00', 'Proveedor Creado', 'Nuevo proveedor \'emprendedores 35\' (RIF: J-120912091) registrado. Representante: pedro perez. Teléfono: 0412-1111111.', '1000000', 'proveedores', 'J-120912091'),
(1115, '2025-07-18 05:34:09', 'Proveedor Eliminado', 'Proveedor \'emprendedores 35\' (RIF: J-120912091) eliminado. Teléfono: 0412-1111111.', '1000000', 'proveedores', 'J-120912091'),
(1116, '2025-07-18 05:37:44', 'Producto Creado', 'Producto \'zapato escolar\' (Código: 12333443-32-qwe) creado.', '1000000', 'productos', '12333443-32-qwe'),
(1117, '2025-07-18 05:47:51', 'Proveedor Eliminado', 'Proveedor \'adidas\' (RIF: J-992222222) eliminado. Teléfono: 0251-1074586.', '1000000', 'proveedores', 'J-992222222'),
(1118, '2025-07-18 05:48:43', 'Proveedor Eliminado', 'Proveedor \'respuestas prontas\' (RIF: G-999999999) eliminado. Teléfono: 0272-1074586.', '1000000', 'proveedores', 'G-999999999'),
(1119, '2025-07-18 06:00:28', 'Producto Editado', 'Producto \'zapatos colores\' (Código: ard-093-as34) editado.', '1000000', 'productos', 'ard-093-as34'),
(1120, '2025-07-18 06:21:48', 'Producto Creado', 'Producto \'zarcills\' (Código: 2323233-lo-p) creado.', '1000000', 'productos', '2323233-lo-p'),
(1121, '2025-07-18 06:22:19', 'Producto Editado', 'Producto \'zarcills\' (Código: 2323233-lo-p) editado.', '1000000', 'productos', '2323233-lo-p'),
(1122, '2025-07-18 06:22:30', 'Producto Editado', 'Producto \'zapato escolar\' (Código: 12333443-32-qwe) editado.', '1000000', 'productos', '12333443-32-qwe'),
(1123, '2025-07-18 06:24:00', 'Producto Editado', 'Producto \'zapatos colores\' (Código: ard-093-as34) editado.', '1000000', 'productos', 'ard-093-as34'),
(1124, '2025-07-18 06:24:21', 'Producto Editado', 'Producto \'zapatos colores\' (Código: ard-093-as34) editado.', '1000000', 'productos', 'ard-093-as34'),
(1125, '2025-07-18 06:28:18', 'Proveedor Creado', 'Nuevo proveedor \'inversiones rivas\' (RIF: J-123423232) registrado. Representante: ana rivas. Teléfono: 0424-5861111.', '1000000', 'proveedores', 'J-123423232'),
(1126, '2025-07-18 06:29:26', 'Producto Creado', 'Producto \'mono deportivo\' (Código: 2323233-le3-3213) creado.', '1000000', 'productos', '2323233-le3-3213'),
(1127, '2025-07-18 06:29:53', 'Producto Editado', 'Producto \'mono deportivos\' (Código: 2323233-le3-3213) editado.', '1000000', 'productos', '2323233-le3-3213'),
(1128, '2025-07-18 08:09:41', 'Venta Creada', 'Nueva venta registrada. Factura: 10068, Cliente: jose angeles cabeza arias, Total: 1915.16.', '1000000', 'ventas', '10068'),
(1129, '2025-07-18 08:10:00', 'Venta Creada', 'Nueva venta registrada. Factura: 10069, Cliente: anas ruiz perdomo, Total: 1031.24.', '1000000', 'ventas', '10069'),
(1130, '2025-07-18 08:10:28', 'Venta Creada', 'Nueva venta registrada. Factura: 10070, Cliente: jose roa, Total: 1915.16.', '1000000', 'ventas', '10070'),
(1131, '2025-07-18 08:10:50', 'Creación Venta Fallida', 'Intento de crear venta fallido: lista de productos vacía. Factura: 10071.', '1000000', 'ventas', '10071'),
(1132, '2025-07-18 08:11:14', 'Categoría Eliminada', 'Categoría \'hola\' (ID: 15) eliminada.', '1000000', 'categorias', '15'),
(1133, '2025-07-18 08:13:09', 'Eliminación Categoría Restringida', 'Intento de eliminar categoría \'Ropa\' (ID: 7) fallido: está relacionada con otros registros.', '1000000', 'categorias', '7'),
(1134, '2025-07-18 11:27:33', 'Generación de Reporte PDF', 'Se generó un reporte de Eventos del Sistema en formato PDF.', '1000000', 'event_log', 'N/A'),
(1135, '2025-07-18 11:27:35', 'Generación de Reporte PDF', 'Se generó un reporte de Eventos del Sistema en formato PDF.', '1000000', 'event_log', 'N/A'),
(1136, '2025-07-18 11:29:37', 'Generación de Reporte PDF', 'Se generó un reporte de Eventos del Sistema en formato PDF.', '1000000', 'event_log', 'N/A'),
(1137, '2025-07-18 11:29:40', 'Generación de Reporte PDF', 'Se generó un reporte de Eventos del Sistema en formato PDF.', '1000000', 'event_log', 'N/A'),
(1138, '2025-07-18 11:30:58', 'Cierre de Sesión', 'El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.', '1000000', 'usuarios', 'admin'),
(1139, '2025-07-18 14:58:30', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1140, '2025-07-18 15:00:04', 'Generación de Reporte PDF', 'Se generó un reporte de Proveedores en formato PDF.', '1000000', 'proveedores', 'N/A'),
(1141, '2025-07-18 15:00:05', 'Generación de Reporte PDF', 'Se generó un reporte de Proveedores en formato PDF.', '1000000', 'proveedores', 'N/A'),
(1142, '2025-07-18 15:00:21', 'Generación de Reporte PDF', 'Se generó un reporte de Clientes en formato PDF.', '1000000', 'clientes', 'N/A'),
(1143, '2025-07-18 15:00:21', 'Generación de Reporte PDF', 'Se generó un reporte de Clientes en formato PDF.', '1000000', 'clientes', 'N/A'),
(1144, '2025-07-18 15:06:23', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 156.421,05 Bs. Rango: Del 09/07/2025 al 19/07/2025', '1000000', 'ventas', 'N/A'),
(1145, '2025-07-18 15:06:24', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 156.421,05 Bs. Rango: Del 09/07/2025 al 19/07/2025', '1000000', 'ventas', 'N/A'),
(1146, '2025-07-18 15:12:16', 'Eliminación Categoría Restringida', 'Intento de eliminar categoría \'Ropa\' (ID: 7) fallido: está relacionada con otros registros.', '1000000', 'categorias', '7'),
(1147, '2025-07-20 01:27:59', 'Creación Venta Fallida', 'Intento de crear venta fallido: lista de productos vacía. Factura: 10071.', '1000000', 'ventas', '10071'),
(1148, '2025-07-20 01:49:48', 'Venta Creada', 'Nueva venta registrada. Factura: 10071, Cliente: jose angeles cabeza arias, Total: 1915.16.', '1000000', 'ventas', '10071'),
(1149, '2025-07-20 01:53:46', 'Creación Venta Fallida', 'Intento de crear venta fallido: lista de productos vacía. Factura: 10071.', '1000000', 'ventas', '10071'),
(1150, '2025-07-20 22:36:40', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1151, '2025-07-20 23:40:57', 'Producto Editado', 'Producto \'zapatos colores\' (Código: ard-093-as34) editado.', '1000000', 'productos', 'ard-093-as34'),
(1152, '2025-07-20 23:41:07', 'Producto Editado', 'Producto \'zapatos colores\' (Código: ard-093-as34) editado.', '1000000', 'productos', 'ard-093-as34'),
(1153, '2025-07-20 23:42:12', 'Producto Creado', 'Producto \'pantalon\' (Código: 23245-43434-eret) creado.', '1000000', 'productos', '23245-43434-eret'),
(1154, '2025-10-14 05:13:54', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1155, '2025-10-14 05:57:20', 'Producto Creado', 'Producto \'shors\' (Código: 2323233-aqwe) creado.', '1000000', 'productos', '2323233-aqwe'),
(1156, '2025-10-14 06:05:59', 'Producto Editado', 'Producto \'pantalon\' (Código: 23245-43434-eret) editado.', '1000000', 'productos', '23245-43434-eret'),
(1157, '2025-10-14 06:06:16', 'Producto Editado', 'Producto \'shors\' (Código: 2323233-aqwe) editado.', '1000000', 'productos', '2323233-aqwe'),
(1158, '2025-10-14 06:09:47', 'Producto Creado', 'Producto \'pantalon\' (Código: 12333443-er) creado.', '1000000', 'productos', '12333443-er'),
(1159, '2025-10-14 06:17:30', 'Producto Creado', 'Producto \'blusa roja\' (Código: 12333443-23) creado.', '1000000', 'productos', '12333443-23'),
(1160, '2025-10-14 06:25:58', 'Cierre de Sesión', 'El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.', '1000000', 'usuarios', 'admin'),
(1161, '2025-10-14 11:17:09', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1162, '2025-10-14 11:42:52', 'Producto Editado', 'Producto \'zapatos colores\' (Código: ard-093-as34) editado.', '1000000', 'productos', 'ard-093-as34'),
(1163, '2025-10-14 11:50:05', 'Producto Creado', 'Producto \'zarcills\' (Código: 2323233-le3-32) creado.', '1000000', 'productos', '2323233-le3-32'),
(1164, '2025-10-14 11:50:41', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J123456788) modificada. Detalles: Precio Dólar: Anterior 127,00 , Nuevo 192,00 .', '1000000', 'empresa', 'J123456788'),
(1165, '2025-10-14 11:52:00', 'Generación de Reporte PDF', 'Se generó un reporte de Inventario en formato PDF.', '1000000', 'productos', 'N/A'),
(1166, '2025-10-14 11:52:01', 'Generación de Reporte PDF', 'Se generó un reporte de Inventario en formato PDF.', '1000000', 'productos', 'N/A'),
(1167, '2025-10-14 12:41:58', 'Cierre de Sesión', 'El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.', '1000000', 'usuarios', 'admin'),
(1168, '2025-10-15 18:35:37', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1169, '2025-10-15 18:52:39', 'Producto Creado', 'Producto \'jeasn\' (Código: 121342-p) creado.', '1000000', 'productos', '121342-p'),
(1170, '2025-10-15 18:54:30', 'Generación de Reporte PDF', 'Se generó un reporte de Eventos del Sistema en formato PDF.', '1000000', 'event_log', 'N/A'),
(1171, '2025-10-15 18:54:32', 'Generación de Reporte PDF', 'Se generó un reporte de Eventos del Sistema en formato PDF.', '1000000', 'event_log', 'N/A'),
(1172, '2025-10-15 19:26:14', 'Producto Editado', 'Producto \'bota alta\' (Código: 121342-2-abc) editado.', '1000000', 'productos', '121342-2-abc'),
(1173, '2025-10-16 12:55:55', 'Cierre de Sesión', 'El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.', '1000000', 'usuarios', 'admin'),
(1174, '2025-10-16 12:56:00', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1175, '2025-10-16 13:32:06', 'Producto Creado', 'Producto \'blusa roja\' (Código: 1234blu) creado.', '1000000', 'productos', '1234blu'),
(1176, '2025-10-16 13:38:48', 'Producto Editado', 'Producto \'pantalon\' (Código: 23245-43434-eret) editado.', '1000000', 'productos', '23245-43434-eret'),
(1177, '2025-10-16 17:27:18', 'Producto Editado', 'Producto \'zapatos colores\' (Código: ard-093-as34) editado.', '1000000', 'productos', 'ard-093-as34'),
(1178, '2025-10-16 18:00:37', 'Producto Creado', 'Producto \'deportivos\' (Código: 2323233-q) creado.', '1000000', 'productos', '2323233-q'),
(1179, '2025-10-16 18:01:51', 'Producto Editado', 'Producto \'deportivos\' (Código: 2323233-q) editado.', '1000000', 'productos', '2323233-q'),
(1180, '2025-10-16 20:52:34', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J123456788) modificada. Detalles: Precio Dólar: Anterior 192,00 , Nuevo 199,00 .', '1000000', 'empresa', 'J123456788'),
(1181, '2025-10-17 09:41:05', 'Cierre de Sesión', 'El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.', '1000000', 'usuarios', 'admin'),
(1182, '2025-10-17 09:41:11', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1183, '2025-10-17 11:26:15', 'Creación Venta Fallida', 'Intento de crear venta fallido: lista de productos vacía. Factura: 10072.', '1000000', 'ventas', '10072'),
(1184, '2025-10-17 11:34:56', 'Cierre de Sesión', 'El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.', '1000000', 'usuarios', 'admin'),
(1185, '2025-10-17 11:35:02', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1186, '2025-10-17 15:13:15', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1187, '2025-10-17 15:14:29', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1188, '2025-10-17 15:15:15', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1189, '2025-10-18 21:26:18', 'Generación de Reporte PDF', 'Se generó un reporte de Inventario en formato PDF.', '1000000', 'productos', 'N/A'),
(1190, '2025-10-18 22:11:47', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 0,00 Bs. Rango: Del 01/10/2025 al 31/10/2025', '1000000', 'ventas', 'N/A'),
(1191, '2025-10-18 22:11:48', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 0,00 Bs. Rango: Del 01/10/2025 al 31/10/2025', '1000000', 'ventas', 'N/A'),
(1192, '2025-10-18 22:31:30', 'Generación de Reporte PDF', 'Se generó un reporte Mensual de Ventas en formato PDF. Total: 0,00 Bs. Período: 10/2025', '1000000', 'ventas', 'N/A'),
(1193, '2025-10-18 22:31:31', 'Generación de Reporte PDF', 'Se generó un reporte Mensual de Ventas en formato PDF. Total: 0,00 Bs. Período: 10/2025', '1000000', 'ventas', 'N/A'),
(1194, '2025-10-19 01:11:11', 'Cierre de Sesión', 'El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.', '1000000', 'usuarios', 'admin'),
(1195, '2025-10-19 01:11:28', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1196, '2025-10-19 09:13:42', 'Cierre de Sesión', 'El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.', '1000000', 'usuarios', 'admin'),
(1197, '2025-10-19 09:13:49', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1198, '2025-10-19 09:16:19', 'Empleado Creado', 'Nuevo empleado \'pedro perdomo\' (Cédula: V-14600277) registrado con éxito.', '1000000', 'empleado', 'V-14600277'),
(1199, '2025-10-19 09:16:46', 'Edición Empleado Fallida', 'Intento de editar empleado con datos inválidos (Nombre: pablo, Cédula: V-14600277). Validación de campos fallida.', '1000000', 'empleado', 'V-14600277'),
(1200, '2025-10-19 09:16:59', 'Empleado Eliminado', 'Empleado \'pedro perdomo\' (Cédula: V-14600277) eliminado con éxito.', '1000000', 'empleado', 'V-14600277'),
(1201, '2025-10-19 09:17:16', 'Edición Empleado Fallida', 'Intento de editar empleado con datos inválidos (Nombre: pedro, Cédula: V-14600273). Validación de campos fallida.', '1000000', 'empleado', 'V-14600273'),
(1202, '2025-10-19 09:18:15', 'Usuario Creado', 'Nuevo usuario \'france\' (Cédula: V-17049561) con perfil \'Administrador\' fue creado.', '1000000', 'usuarios', 'V-17049561'),
(1203, '2025-10-19 09:18:36', 'Usuario Editado', 'Usuario \'france\' (Cédula: ) editado con perfil \'Administrador\'.', '1000000', 'usuarios', NULL),
(1204, '2025-10-19 09:18:44', 'Cierre de Sesión', 'El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.', '1000000', 'usuarios', 'admin');
INSERT INTO `event_log` (`id`, `timestamp`, `event_type`, `description`, `employee_cedula`, `affected_table`, `affected_row_id`) VALUES
(1205, '2025-10-19 09:18:52', 'Login Exitoso', 'El empleado \'france\' (Administrador) ha iniciado sesión.', 'V-17049561', 'usuarios', 'france'),
(1206, '2025-10-19 09:19:52', 'Cierre de Sesión', 'El usuario \'france\' (Cédula: V-17049561, Perfil: Administrador) ha cerrado sesión.', 'V-17049561', 'usuarios', 'france'),
(1207, '2025-10-19 09:22:07', 'Login Exitoso', 'El empleado \'france\' (Administrador) ha iniciado sesión.', 'V-17049561', 'usuarios', 'france'),
(1208, '2025-10-19 09:24:01', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1209, '2025-10-19 09:27:21', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1210, '2025-10-19 09:47:23', 'Usuario Eliminado', 'Usuario \'V-17049561\' (Cédula: N/A) eliminado.', '1000000', 'usuarios', 'N/A'),
(1211, '2025-10-19 09:49:41', 'Usuario Creado', 'Nuevo usuario \'FRANCO\' (Cédula: V-14600273) con perfil \'Administrador\' fue creado.', '1000000', 'usuarios', 'V-14600273'),
(1212, '2025-10-19 09:50:33', 'Usuario Creado', 'Nuevo usuario \'jose13\' (Cédula: V-17049561) con perfil \'Administrador\' fue creado.', '1000000', 'usuarios', 'V-17049561'),
(1213, '2025-10-19 09:50:41', 'Usuario Eliminado', 'Usuario \'V-14600273\' (Cédula: N/A) eliminado.', '1000000', 'usuarios', 'N/A'),
(1214, '2025-10-19 09:58:25', 'Cierre de Sesión', 'El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.', '1000000', 'usuarios', 'admin'),
(1215, '2025-10-19 09:58:35', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1216, '2025-10-20 18:53:27', 'Usuario Creado', 'Nuevo usuario \'pedro13\' (Cédula: E-14600272) con perfil \'Administrador\' fue creado.', '1000000', 'usuarios', 'E-14600272'),
(1217, '2025-10-20 18:53:51', 'Creación Usuario Fallida', 'Intento de crear usuario \'pedro13\' con cédula \'E-14600272\' fallido por ser repetido.', '1000000', 'usuarios', 'E-14600272'),
(1218, '2025-10-20 18:54:15', 'Usuario Creado', 'Nuevo usuario \'FRANCO\' (Cédula: V-14600272) con perfil \'Administrador\' fue creado.', '1000000', 'usuarios', 'V-14600272'),
(1219, '2025-10-20 18:54:28', 'Usuario Editado', 'Usuario \'FRANCO\' (Cédula: ) editado con perfil \'Vendedor\'.', '1000000', 'usuarios', NULL),
(1220, '2025-10-20 18:54:38', 'Usuario Eliminado', 'Usuario \'V-14600272\' (Cédula: N/A) eliminado.', '1000000', 'usuarios', 'N/A'),
(1221, '2025-10-20 18:55:16', 'Usuario Creado', 'Nuevo usuario \'jose13\' (Cédula: E-14600272) con perfil \'Administrador\' fue creado.', '1000000', 'usuarios', 'E-14600272'),
(1222, '2025-10-20 18:55:35', 'Usuario Creado', 'Nuevo usuario \'FRANCO\' (Cédula: V-14600272) con perfil \'Administrador\' fue creado.', '1000000', 'usuarios', 'V-14600272'),
(1223, '2025-10-20 18:55:41', 'Usuario Eliminado', 'Usuario \'V-14600272\' (Cédula: N/A) eliminado.', '1000000', 'usuarios', 'N/A'),
(1224, '2025-10-20 18:55:56', 'Creación Usuario Fallida', 'Intento de crear usuario \'12122\' con cédula \'E-14600272\' fallido por ser repetido.', '1000000', 'usuarios', 'E-14600272'),
(1225, '2025-10-20 18:56:12', 'Creación Usuario Fallida', 'Intento de crear usuario con datos inválidos (Usuario: pedro13, Cédula: N/A).', '1000000', 'usuarios', 'N/A'),
(1226, '2025-10-20 18:56:32', 'Usuario Creado', 'Nuevo usuario \'pedro13\' (Cédula: V-17049561) con perfil \'Administrador\' fue creado.', '1000000', 'usuarios', 'V-17049561'),
(1227, '2025-10-20 18:57:07', 'Usuario Creado', 'Nuevo usuario \'1234\' (Cédula: V-14600272) con perfil \'Administrador\' fue creado.', '1000000', 'usuarios', 'V-14600272'),
(1228, '2025-10-20 18:57:19', 'Usuario Eliminado', 'Usuario \'E-14600272\' (Cédula: N/A) eliminado.', '1000000', 'usuarios', 'N/A'),
(1229, '2025-10-20 18:57:31', 'Usuario Eliminado', 'Usuario \'V-14600272\' (Cédula: N/A) eliminado.', '1000000', 'usuarios', 'N/A'),
(1230, '2025-10-20 18:57:37', 'Usuario Eliminado', 'Usuario \'V-17049561\' (Cédula: N/A) eliminado.', '1000000', 'usuarios', 'N/A'),
(1231, '2025-10-20 18:59:31', 'Edición Empleado Fallida', 'Intento de editar empleado con datos inválidos (Nombre: francelia, Cédula: V-17049561). Validación de campos fallida.', '1000000', 'empleado', 'V-17049561'),
(1232, '2025-10-20 18:59:46', 'Empleado Eliminado', 'Empleado \'pedro arias\' (Cédula: V-14600273) eliminado con éxito.', '1000000', 'empleado', 'V-14600273'),
(1233, '2025-10-20 19:01:13', 'Creación Empleado Fallida', 'Intento de crear empleado con cédula \'V-14600272\' fallido: Cédula ya existe.', '1000000', 'empleado', 'V-14600272'),
(1234, '2025-10-20 19:01:24', 'Empleado Eliminado', 'Empleado \'jose ppe\' (Cédula: E-14600272) eliminado con éxito.', '1000000', 'empleado', 'E-14600272'),
(1235, '2025-10-20 20:26:50', 'Empleado Creado', 'Nuevo empleado \'jose err\' (Cédula: V-14600273) registrado con éxito.', '1000000', 'empleado', 'V-14600273'),
(1236, '2025-10-20 20:26:56', 'Eliminación Empleado Fallida', 'Fallo de clave foránea al intentar eliminar empleado (Cédula: V-17049561).', '1000000', 'empleado', 'V-17049561'),
(1237, '2025-10-20 20:27:18', 'Empleado Eliminado', 'Empleado \'jose err\' (Cédula: V-14600273) eliminado con éxito.', '1000000', 'empleado', 'V-14600273'),
(1238, '2025-10-20 20:28:25', 'Eliminación Empleado Fallida', 'Fallo de clave foránea al intentar eliminar empleado (Cédula: V-14600272).', '1000000', 'empleado', 'V-14600272'),
(1239, '2025-10-20 20:28:37', 'Eliminación Empleado Fallida', 'Fallo de clave foránea al intentar eliminar empleado (Cédula: V-14600272).', '1000000', 'empleado', 'V-14600272'),
(1240, '2025-10-20 20:28:53', 'Eliminación Empleado Fallida', 'Fallo de clave foránea al intentar eliminar empleado (Cédula: V-14600272).', '1000000', 'empleado', 'V-14600272'),
(1241, '2025-10-20 20:29:41', 'Eliminación Empleado Fallida', 'Fallo de clave foránea al intentar eliminar empleado (Cédula: V-14600272).', '1000000', 'empleado', 'V-14600272'),
(1242, '2025-10-20 20:31:39', 'Eliminación Empleado Fallida', 'Fallo de clave foránea al intentar eliminar empleado (Cédula: V-14600272).', '1000000', 'empleado', 'V-14600272'),
(1243, '2025-10-20 23:40:20', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J123456788) modificada. Detalles: Precio Dólar: Anterior 199,00 , Nuevo 200,00 .', '1000000', 'empresa', 'J123456788'),
(1244, '2025-10-21 00:02:56', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 0,00 Bs. Rango: Del 01/10/2025 al 31/10/2025', '1000000', 'ventas', 'N/A'),
(1245, '2025-10-21 00:02:58', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 0,00 Bs. Rango: Del 01/10/2025 al 31/10/2025', '1000000', 'ventas', 'N/A'),
(1246, '2025-10-21 01:57:43', 'Generación de Reporte PDF', 'Se generó un reporte de Proveedores en formato PDF.', '1000000', 'proveedores', 'N/A'),
(1247, '2025-10-21 01:57:43', 'Generación de Reporte PDF', 'Se generó un reporte de Proveedores en formato PDF.', '1000000', 'proveedores', 'N/A'),
(1248, '2025-10-21 01:58:41', 'Generación de Reporte PDF', 'Se generó un reporte de Proveedores en formato PDF.', '1000000', 'proveedores', 'N/A'),
(1249, '2025-10-21 02:01:55', 'Generación de Reporte PDF', 'Se generó un reporte Total de Ventas en formato PDF. Total: 158.336,21 Bs.', '1000000', 'ventas', 'N/A'),
(1250, '2025-10-21 02:01:57', 'Generación de Reporte PDF', 'Se generó un reporte Total de Ventas en formato PDF. Total: 158.336,21 Bs.', '1000000', 'ventas', 'N/A'),
(1251, '2025-10-21 02:02:05', 'Generación de Reporte PDF', 'Se generó un reporte Total de Ventas en formato PDF. Total: 158.336,21 Bs.', '1000000', 'ventas', 'N/A'),
(1252, '2025-10-21 02:02:06', 'Generación de Reporte PDF', 'Se generó un reporte Total de Ventas en formato PDF. Total: 158.336,21 Bs.', '1000000', 'ventas', 'N/A'),
(1253, '2025-10-21 02:02:13', 'Generación de Reporte PDF', 'Se generó un reporte Total de Ventas en formato PDF. Total: 158.336,21 Bs.', '1000000', 'ventas', 'N/A'),
(1254, '2025-10-21 02:05:17', 'Generación de Reporte PDF', 'Se generó un reporte Total de Ventas en formato PDF. Total: 158.336,21 Bs.', '1000000', 'ventas', 'N/A'),
(1255, '2025-10-21 02:05:18', 'Generación de Reporte PDF', 'Se generó un reporte Total de Ventas en formato PDF. Total: 158.336,21 Bs.', '1000000', 'ventas', 'N/A'),
(1256, '2025-10-21 02:18:50', 'Generación de Reporte PDF', 'Se generó un reporte Total de Compras en formato PDF. Total: 0,00 Bs.', '1000000', 'compras', 'N/A'),
(1257, '2025-10-21 02:18:52', 'Generación de Reporte PDF', 'Se generó un reporte Total de Compras en formato PDF. Total: 0,00 Bs.', '1000000', 'compras', 'N/A'),
(1258, '2025-10-21 02:26:51', 'Generación de Reporte PDF', 'Se generó un reporte Total de Compras en formato PDF. Total Bs: 0,00 Bs. Total $: 0,00 $.', '1000000', 'compras', 'N/A'),
(1259, '2025-10-21 02:26:53', 'Generación de Reporte PDF', 'Se generó un reporte Total de Compras en formato PDF. Total Bs: 0,00 Bs. Total $: 0,00 $.', '1000000', 'compras', 'N/A'),
(1260, '2025-10-21 02:33:35', 'Generación de Reporte PDF', 'Se generó un reporte Total de Compras en formato PDF. Total Bs: 19.502,00 Bs. Total $: 98,00 $.', '1000000', 'compras', 'N/A'),
(1261, '2025-10-21 02:33:37', 'Generación de Reporte PDF', 'Se generó un reporte Total de Compras en formato PDF. Total Bs: 19.502,00 Bs. Total $: 98,00 $.', '1000000', 'compras', 'N/A'),
(1262, '2025-10-21 02:52:20', 'Generación de Reporte PDF', 'Se generó un reporte Total de Compras en formato PDF. Total Bs: 19.502,00 Bs. Total $: 98,00 $.', '1000000', 'compras', 'N/A'),
(1263, '2025-10-21 02:52:22', 'Generación de Reporte PDF', 'Se generó un reporte Total de Compras en formato PDF. Total Bs: 19.502,00 Bs. Total $: 98,00 $.', '1000000', 'compras', 'N/A'),
(1264, '2025-10-21 03:19:37', 'Generación de Reporte PDF', 'Se generó un reporte Total de Compras en formato PDF. Total Bs: 24.502,00 Bs. Total $: 123,00 $.', '1000000', 'compras', 'N/A'),
(1265, '2025-10-21 03:19:40', 'Generación de Reporte PDF', 'Se generó un reporte Total de Compras en formato PDF. Total Bs: 24.502,00 Bs. Total $: 123,00 $.', '1000000', 'compras', 'N/A'),
(1266, '2025-10-21 03:56:12', 'Producto Creado', 'Producto \'pantalon gris\' (Código: 2323233-sd) creado.', '1000000', 'productos', '2323233-sd'),
(1267, '2025-10-21 07:42:12', 'Venta Creada', 'Nueva venta registrada. Factura: 10072, Cliente: anas ruiz perdomo, Total: 4524.', '1000000', 'ventas', '10072'),
(1268, '2025-10-21 10:22:20', 'Venta Creada', 'Nueva venta registrada. Factura: 10073, Cliente: anas ruiz perdomo, Total: 3016.', '1000000', 'ventas', '10073'),
(1269, '2025-10-21 11:06:04', 'Venta Creada', 'Nueva venta registrada. Factura: 10074, Cliente: anas ruiz perdomo, Total: 4524.', '1000000', 'ventas', '10074'),
(1270, '2025-10-21 11:06:50', 'Venta Creada', 'Nueva venta registrada. Factura: 10075, Cliente: jose roa, Total: 3900.', '1000000', 'ventas', '10075'),
(1271, '2025-10-21 11:10:07', 'Venta Creada', 'Nueva venta registrada. Factura: 10076, Cliente: peter grifin, Total: 1624.', '1000000', 'ventas', '10076'),
(1272, '2025-10-21 11:26:32', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 17.588,00 Bs. Rango: Del 01/10/2025 al 31/10/2025', '1000000', 'ventas', 'N/A'),
(1273, '2025-10-21 11:26:34', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 17.588,00 Bs. Rango: Del 01/10/2025 al 31/10/2025', '1000000', 'ventas', 'N/A'),
(1274, '2025-10-21 11:27:02', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 0,00 Bs. Rango: Del 20/10/2025 al 20/10/2025', '1000000', 'ventas', 'N/A'),
(1275, '2025-10-21 11:27:03', 'Generación de Reporte PDF', 'Se generó un reporte de Ventas por Rango de Fecha en formato PDF. Total: 0,00 Bs. Rango: Del 20/10/2025 al 20/10/2025', '1000000', 'ventas', 'N/A'),
(1276, '2025-10-21 12:28:50', 'Generación de Reporte PDF', 'Se generó un reporte Total de Compras en formato PDF. Total Bs: 0,00 Bs. Total $: 0,00 $.', '1000000', 'compras', 'N/A'),
(1277, '2025-10-21 12:28:51', 'Generación de Reporte PDF', 'Se generó un reporte Total de Compras en formato PDF. Total Bs: 0,00 Bs. Total $: 0,00 $.', '1000000', 'compras', 'N/A'),
(1278, '2025-10-21 12:29:39', 'Generación de Reporte PDF', 'Se generó un reporte Total de Compras en formato PDF. Total Bs: 1.000,00 Bs. Total $: 5,00 $.', '1000000', 'compras', 'N/A'),
(1279, '2025-10-21 12:34:31', 'Cierre de Sesión', 'El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.', '1000000', 'usuarios', 'admin'),
(1280, '2025-10-21 12:34:40', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1281, '2025-10-21 12:35:49', 'Cierre de Sesión', 'El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.', '1000000', 'usuarios', 'admin'),
(1282, '2025-10-21 12:35:54', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1283, '2025-10-21 12:36:11', 'Cierre de Sesión', 'El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.', '1000000', 'usuarios', 'admin'),
(1284, '2025-10-21 12:36:18', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1285, '2025-10-21 12:37:25', 'Cierre de Sesión', 'El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.', '1000000', 'usuarios', 'admin'),
(1286, '2025-10-21 12:37:52', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1287, '2025-10-21 12:39:13', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1288, '2025-10-21 12:39:53', 'Cierre de Sesión', 'El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.', '1000000', 'usuarios', 'admin'),
(1289, '2012-11-01 04:35:50', 'Login Fallido', 'Contraseña incorrecta para el usuario: \'admin\'.', '1000000', 'usuarios', 'admin'),
(1290, '2012-11-01 04:35:57', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1291, '2012-11-01 04:37:00', 'Cierre de Sesión', 'El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.', '1000000', 'usuarios', 'admin'),
(1292, '2012-11-01 04:42:30', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1293, '2012-11-01 04:49:43', 'Cierre de Sesión', 'El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.', '1000000', 'usuarios', 'admin'),
(1294, '2012-11-01 04:50:09', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1295, '2012-11-01 04:59:27', 'Venta Creada', 'Nueva venta registrada. Factura: 10077, Cliente: anas ruiz perdomo, Total: 8143.2.', '1000000', 'ventas', '10077'),
(1296, '2025-10-23 04:05:55', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1297, '2025-10-23 07:45:17', 'Generación de Reporte PDF', 'Se generó un reporte Total de Ventas en formato PDF. Total: 184.067,41 Bs.', '1000000', 'ventas', 'N/A'),
(1298, '2025-10-23 07:45:19', 'Generación de Reporte PDF', 'Se generó un reporte Total de Ventas en formato PDF. Total: 184.067,41 Bs.', '1000000', 'ventas', 'N/A'),
(1299, '2025-10-23 07:45:38', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 0,00 Bs.', '1000000', 'ventas', 'N/A'),
(1300, '2025-10-23 07:45:39', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 0,00 Bs.', '1000000', 'ventas', 'N/A'),
(1301, '2025-10-23 08:18:31', 'Venta Creada', 'Nueva venta registrada. Factura: 10078, Cliente: jose roa, Total: 4524.', '1000000', 'ventas', '10078'),
(1302, '2025-10-23 15:13:46', 'Login Exitoso', 'El empleado \'admin\' (Administrador) ha iniciado sesión.', '1000000', 'usuarios', 'admin'),
(1303, '2025-10-23 15:14:05', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J123456789) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J123456789'),
(1304, '2025-10-23 15:19:45', 'Cliente Editado', 'Datos del cliente \'jose roas\' (Cédula: E-14600272) modificados.', '1000000', 'clientes', 'E-14600272'),
(1305, '2025-10-23 15:22:52', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J123456789) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J123456789'),
(1306, '2025-10-23 15:23:09', 'Generación de Reporte PDF', 'Se generó un reporte de Eventos del Sistema en formato PDF.', '1000000', 'event_log', 'N/A'),
(1307, '2025-10-23 15:23:09', 'Generación de Reporte PDF', 'Se generó un reporte de Eventos del Sistema en formato PDF.', '1000000', 'event_log', 'N/A'),
(1308, '2025-10-23 15:23:11', 'Generación de Reporte PDF', 'Se generó un reporte de Eventos del Sistema en formato PDF.', '1000000', 'event_log', 'N/A'),
(1309, '2025-10-23 15:23:44', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 4.524,00 Bs.', '1000000', 'ventas', 'N/A'),
(1310, '2025-10-23 15:23:45', 'Generación de Reporte PDF', 'Se generó un reporte Diario de Ventas en formato PDF. Total: 4.524,00 Bs.', '1000000', 'ventas', 'N/A'),
(1311, '2025-10-23 15:24:02', 'Generación de Reporte PDF', 'Se generó un reporte Total de Ventas en formato PDF. Total: 188.591,41 Bs.', '1000000', 'ventas', 'N/A'),
(1312, '2025-10-23 15:24:04', 'Generación de Reporte PDF', 'Se generó un reporte Total de Ventas en formato PDF. Total: 188.591,41 Bs.', '1000000', 'ventas', 'N/A'),
(1313, '2025-10-23 15:28:50', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J123456789) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J123456789'),
(1314, '2025-10-23 15:45:43', 'Configuración Editada', 'Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al Chariti\' (RIF: J121212121) modificada. Otros datos de configuración modificados.', '1000000', 'empresa', 'J121212121'),
(1315, '2025-10-23 15:51:28', 'Generación de Reporte PDF', 'Se generó un reporte Total de Ventas en formato PDF. Total: 188.591,41 Bs.', '1000000', 'ventas', 'N/A'),
(1316, '2025-10-23 15:51:30', 'Generación de Reporte PDF', 'Se generó un reporte Total de Ventas en formato PDF. Total: 188.591,41 Bs.', '1000000', 'ventas', 'N/A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_dolar`
--

CREATE TABLE `historial_dolar` (
  `id` int(11) NOT NULL,
  `precio_dolar` decimal(10,4) NOT NULL,
  `precio_anterior` decimal(10,4) DEFAULT NULL,
  `fecha_cambio` datetime NOT NULL,
  `estado_cambio` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `historial_dolar`
--

INSERT INTO `historial_dolar` (`id`, `precio_dolar`, `precio_anterior`, `fecha_cambio`, `estado_cambio`) VALUES
(1, '113.0000', NULL, '2025-07-06 07:48:58', NULL),
(2, '114.0000', '113.0000', '2025-07-06 08:04:14', 'Subió'),
(3, '120.0000', '114.0000', '2025-07-07 01:22:24', 'Subió'),
(4, '140.0000', '120.0000', '2025-07-07 03:53:58', 'Subió'),
(5, '200.0000', '140.0000', '2025-07-07 04:03:25', 'Subió'),
(6, '117.0000', '200.0000', '2025-07-07 05:09:01', 'Bajó'),
(7, '200.0000', '117.0000', '2025-07-07 23:08:46', 'Subió'),
(8, '201.0000', '200.0000', '2025-07-08 06:41:41', 'Subió'),
(9, '107.0000', '201.0000', '2025-07-08 07:07:04', 'Bajó'),
(10, '300.0000', '107.0000', '2025-07-10 00:20:28', 'Subió'),
(11, '110.0000', '300.0000', '2025-07-11 13:28:13', 'Bajó'),
(12, '120.0000', '110.0000', '2025-07-18 00:18:33', 'Subió'),
(13, '121.0000', '120.0000', '2025-07-18 00:20:13', 'Subió'),
(14, '1214.0000', '121.0000', '2025-07-18 00:53:17', 'Subió'),
(15, '127.0000', '1214.0000', '2025-07-18 00:53:37', 'Bajó'),
(16, '192.0000', '127.0000', '2025-10-14 13:50:41', 'Subió'),
(17, '199.0000', '192.0000', '2025-10-16 22:52:34', 'Subió'),
(18, '200.0000', '199.0000', '2025-10-21 01:40:20', 'Subió');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `marcas`
--

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL,
  `marca` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `marcas`
--

INSERT INTO `marcas` (`id`, `marca`, `fecha`) VALUES
(10, 'adidass', '2025-07-03 14:07:32'),
(13, 'rs21', '2024-10-21 12:48:44'),
(14, 'Aero', '2012-11-01 04:37:27'),
(15, 'generica', '2012-11-01 04:37:35'),
(16, 'acadia', '2012-11-01 04:37:46'),
(17, 'sifrina', '2012-11-01 04:37:59'),
(18, 'NIKE', '2024-10-25 22:32:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodos_pago`
--

CREATE TABLE `metodos_pago` (
  `id` int(11) NOT NULL,
  `nombre_metodo` varchar(50) COLLATE utf8_spanish_ci NOT NULL COMMENT 'Nombre descriptivo del método de pago (Ej. Efectivo, Tarjeta, Pago Movil, Divisas)',
  `requiere_detalle` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 = No requiere referencia adicional (Ej. Efectivo), 1 = Sí requiere (Ej. número de tarjeta, código de transacción)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `metodos_pago`
--

INSERT INTO `metodos_pago` (`id`, `nombre_metodo`, `requiere_detalle`) VALUES
(1, 'Efectivo', 0),
(2, 'Tarjeta', 1),
(3, 'Pago Movil', 1),
(4, 'Divisas', 0),
(5, 'Transferencia', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos_venta`
--

CREATE TABLE `pagos_venta` (
  `id` int(11) NOT NULL,
  `factura_id` int(11) NOT NULL COMMENT 'Clave foránea a la tabla ventas (columna factura)',
  `metodo_pago_id` int(11) NOT NULL COMMENT 'Clave foránea a la tabla metodos_pago',
  `monto_pagado_bs` decimal(10,2) NOT NULL COMMENT 'Monto pagado en la moneda base (Bolívares) con este método',
  `referencia_metodo` varchar(100) COLLATE utf8_spanish_ci DEFAULT NULL COMMENT 'Referencia o código de transacción (Ej. últimos 4 dígitos tarjeta, código Pago Movil)',
  `monto_divisas` decimal(10,2) DEFAULT NULL COMMENT 'Cantidad de divisas pagadas (Ej. 40.00 USD). NULL si no es pago en divisas',
  `tasa_cambio_divisas` decimal(10,4) DEFAULT NULL COMMENT 'Tasa de cambio aplicada al momento del pago con divisas (Ej. 107.5000). NULL si no es pago en divisas'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_categoria` int(11) NOT NULL,
  `id_tipo` int(11) NOT NULL,
  `id_color` int(11) DEFAULT NULL,
  `id_marca` int(11) NOT NULL,
  `codigo` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `imagen` text COLLATE utf8_spanish_ci NOT NULL,
  `stock` int(11) DEFAULT NULL,
  `precio_compra` float NOT NULL,
  `precio_venta` float NOT NULL,
  `tipo_rif_proveedor` varchar(5) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL,
  `num_rif_proveedor` varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish2_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_categoria`, `id_tipo`, `id_color`, `id_marca`, `codigo`, `descripcion`, `imagen`, `stock`, `precio_compra`, `precio_venta`, `tipo_rif_proveedor`, `num_rif_proveedor`) VALUES
(7, 5, 10, 10, '121342-2', 'cHEMISE', 'vistas/img/productos/121342-2/359.jpg', 27, 10, 13, 'C', '999999991'),
(9, 5, NULL, 15, '121342-2-abc', 'bota alta', 'vistas/img/productos/121342-2-abc/431.jpg', 11, 20, 26, 'J', '123423232'),
(7, 5, NULL, 10, '121342-p', 'jeasn', 'vistas/img/productos/default/anonymous.png', 12, 12, 15.6, 'C', '175109422'),
(7, 5, NULL, 10, '12333443-23', 'blusa roja', 'vistas/img/productos/default/anonymous.png', 12, 12, 15.6, NULL, NULL),
(9, 6, NULL, 10, '12333443-32-qwe', 'zapato escolar', 'vistas/img/productos/default/anonymous.png', 45, 20, 26, NULL, NULL),
(7, 6, NULL, 10, '12333443-er', 'pantalon', 'vistas/img/productos/default/anonymous.png', 12, 12, 15.6, NULL, NULL),
(7, 7, NULL, 14, '1234blu', 'blusa roja', 'vistas/img/productos/default/anonymous.png', 4, 12, 15.6, 'C', '175109422'),
(7, 5, 1, 10, '2323233-2', 'pantalon', 'vistas/img/productos/2323233-2/520.jpg', 43, 12, 15.6, NULL, NULL),
(7, 5, NULL, 13, '2323233-aqwe', 'shors', 'vistas/img/productos/default/anonymous.png', 12, 12, 15.6, NULL, NULL),
(7, 5, NULL, 13, '2323233-le3-32', 'zarcills', 'vistas/img/productos/default/anonymous.png', 33, 1, 1.3, NULL, NULL),
(7, 5, NULL, 15, '2323233-le3-3213', 'mono deportivos', 'vistas/img/productos/default/anonymous.png', 20, 10, 13, NULL, NULL),
(9, 6, NULL, 10, '2323233-lo-p', 'zarcills', 'vistas/img/productos/default/anonymous.png', 13, 5, 7, NULL, NULL),
(9, 5, NULL, 10, '2323233-q', 'deportivos', 'vistas/img/productos/default/anonymous.png', 13, 13, 16.9, 'C', '175109422'),
(7, 5, NULL, 14, '2323233-sd', 'pantalon gris', 'vistas/img/productos/default/anonymous.png', 9, 12, 15.6, 'C', '175109422'),
(7, 5, NULL, 14, '23245-43434-eret', 'pantalon', 'vistas/img/productos/default/anonymous.png', 7, 15, 19.5, 'C', '175109422'),
(7, 5, NULL, 15, 'ard-093-as34', 'zapatos colores', 'vistas/img/productos/ard-093-as34/569.jpg', 19, 10, 13, 'J', '123423232');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `correo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `prefijo_telefono` varchar(10) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `numero_telefono` varchar(20) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `tipo_ced` varchar(5) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '',
  `num_ced` varchar(20) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '',
  `tipo_rif` varchar(5) COLLATE utf8_spanish2_ci NOT NULL DEFAULT '',
  `num_rif` varchar(15) COLLATE utf8_spanish2_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`nombre`, `nombre_representante`, `apellido_representante`, `direccion`, `correo`, `prefijo_telefono`, `numero_telefono`, `tipo_ced`, `num_ced`, `tipo_rif`, `num_rif`) VALUES
('inversores velezz', 'franco', 'valenzuela', 'Caracasss', 'bastidas@gmail.com', '0272', '8888888', 'E', '14500222', 'C', '175109422'),
('franco import', 'pedro', 'valdez', 'av- sucre 1-10', 'francoarias81@gmail.com', '0261', '1111112', 'E', '14500222', 'C', '999999991'),
('inversiones rivas', 'ana', 'rivas', 'valencia', 'bastidas@gmail.com', '0424', '5861111', 'V', '14500222', 'J', '123423232');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos`
--

CREATE TABLE `tipos` (
  `id` int(11) NOT NULL,
  `tipo` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `tipos`
--

INSERT INTO `tipos` (`id`, `tipo`, `fecha`) VALUES
(5, 'caballero', '2025-07-03 14:05:55'),
(6, 'niños', '2025-07-17 14:02:24'),
(7, 'niña', '2012-11-01 04:34:52'),
(8, 'dama', '2012-11-01 04:36:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `perfil` text COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL,
  `tipo_ced` varchar(5) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL DEFAULT '',
  `num_ced` varchar(20) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`cedula`, `usuario`, `password`, `perfil`, `estado`, `tipo_ced`, `num_ced`) VALUES
('1000000', 'admin', '$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG', 'Administrador', 1, 'C', '1000000');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `factura` int(11) NOT NULL,
  `tipo_rif_empresa` varchar(5) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `num_rif_empresa` varchar(15) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `vendedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `total` float NOT NULL,
  `pago` float NOT NULL,
  `impuesto` float NOT NULL DEFAULT '16',
  `metodo_pago` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tipo_ced_cliente` varchar(5) COLLATE utf8_spanish_ci DEFAULT NULL,
  `num_ced_cliente` varchar(15) COLLATE utf8_spanish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`factura`, `tipo_rif_empresa`, `num_rif_empresa`, `vendedor`, `total`, `pago`, `impuesto`, `metodo_pago`, `fecha`, `tipo_ced_cliente`, `num_ced_cliente`) VALUES
(10015, 'J', '121212121', '1000000', 111.592, 96.2, 15.39, 'Efectivo', '2025-07-09 20:47:13', '30124', '30124678'),
(10024, 'J', '121212121', '1000000', 180.96, 156, 24.96, 'Divisas-2', '2025-07-10 20:47:13', '30124', '30124678'),
(10026, 'J', '121212121', '1000000', 2171.52, 1872, 299.52, 'Tarjeta-1234', '2025-07-12 20:47:13', '30124', '30124678'),
(10027, 'J', '121212121', '1000000', 2171.52, 1872, 299.52, 'Tarjeta-123', '2025-07-12 20:47:13', '30124', '30124678'),
(10028, 'J', '121212121', '1000000', 2171.52, 1872, 299.52, 'Divisas-20', '2025-07-12 20:47:13', '30124', '30124678'),
(10030, 'J', '121212121', '1000000', 4343.04, 3744, 599.04, 'Divisas-20', '2025-07-12 20:47:13', '30124', '30124678'),
(10031, 'J', '121212121', '1000000', 2171.52, 1872, 299.52, 'Tarjeta-123', '2025-07-12 20:47:13', '30124', '30124678'),
(10034, 'J', '121212121', '1000000', 301.6, 260, 0, 'Divisas-2', '2025-07-12 20:47:13', '30124', '30124678'),
(10038, 'J', '121212121', '1000000', 4410.9, 3802.5, 0, 'Pago Movil-1234', '2025-07-12 20:47:13', '30124', '30124678'),
(10039, 'J', '121212121', '1000000', 301.6, 260, 41.6, 'Divisas-2', '2025-07-12 20:47:13', '30124', '30124678'),
(10040, 'J', '121212121', '1000000', 806.78, 695.5, 111.28, 'Divisas-7', '2025-07-12 20:47:13', '30124', '30124678'),
(10041, 'J', '121212121', '1000000', 806.78, 695.5, 111.28, 'Divisas-7', '2025-07-12 20:47:13', 'V', '14600274'),
(10042, 'J', '121212121', '1000000', 6786, 5850, 936, 'Divisas-20', '2025-07-12 20:47:13', '30124', '30124678'),
(10043, 'J', '121212121', '1000000', 829.4, 715, 114.4, 'Tarjeta-1234', '2025-07-12 20:47:13', '30124', '30124678'),
(10044, 'J', '121212121', '1000000', 4478.76, 3861, 617.76, 'Divisas-31', '2025-07-12 20:47:13', '30124', '30124678'),
(10045, 'J', '121212121', '1000000', 1990.56, 1716, 274.56, '', '2025-07-12 20:47:13', '30124', '30124678'),
(10046, 'J', '121212121', '1000000', 1990.56, 1716, 274.56, 'Efectivo', '2025-07-12 20:47:13', '30124', '30124678'),
(10047, 'J', '121212121', '1000000', 1990.56, 1716, 274.56, 'Divisas-16', '2025-07-12 23:14:54', 'E', '14600272'),
(10048, 'J', '121212121', '1000000', 3649.36, 3146, 503.36, 'Tarjeta-1234', '2025-07-12 23:29:57', 'E', '14600272'),
(10049, 'J', '121212121', '1000000', 3649.36, 3146, 503.36, 'Tarjeta-1234', '2025-07-12 23:43:55', 'V', '14600270'),
(10050, 'J', '121212121', '1000000', 3649.36, 3146, 503.36, 'Tarjeta-12345', '2025-07-13 03:05:16', 'V', '14600270'),
(10051, 'J', '121212121', '1000000', 1990.56, 1716, 274.56, 'Tarjeta-1234', '2025-07-13 20:03:27', 'E', '10000000'),
(10052, 'J', '121212121', '1000000', 3649.36, 3146, 503.36, 'Divisas-40', '2025-07-13 20:06:00', 'E', '14600272'),
(10053, 'J', '121212121', '1000000', 9621.04, 8294, 1327.04, 'Tarjeta-1234', '2025-07-13 20:11:00', 'E', '14600999'),
(10054, 'J', '121212121', '1000000', 1990.56, 1716, 274.56, 'Divisas-15', '2025-07-13 21:01:48', 'V', '10000000'),
(10055, 'J', '121212121', '1000000', 1658.8, 1430, 228.8, 'Tarjeta-4321', '2025-07-13 21:20:18', 'V', '14600270'),
(10056, 'J', '121212121', '1000000', 5745.48, 4953, 792.48, 'Divisas-20', '2025-07-17 23:42:47', 'V', '17049561'),
(10057, 'J', '121212121', '1000000', 42516.6, 36652.2, 5864.35, 'Pago Movil-5678', '2025-07-17 23:44:26', 'V', '17049561'),
(10058, 'J', '121212121', '1000000', 2298.19, 1981.2, 316.99, 'Transferencia-123456788', '2025-07-17 23:48:52', 'E', '14600272'),
(10059, 'J', '121212121', '1000000', 13208, 13208, 0, 'Tarjeta-4321', '2025-07-17 23:58:28', 'E', '14600999'),
(10060, 'J', '121212121', '1000000', 3830.32, 3302, 528.32, 'Efectivo', '2025-07-17 23:59:06', 'V', '14600270'),
(10061, 'J', '121212121', '1000000', 2298.19, 1981.2, 316.99, 'Pago Movil-12321223', '2025-07-18 00:01:02', 'V', '14600270'),
(10062, 'J', '121212121', '1000000', 2298.19, 1981.2, 316.99, 'Efectivo', '2025-07-18 00:03:21', 'E', '14600272'),
(10063, 'J', '121212121', '1000000', 2298.19, 1981.2, 316.99, 'Tarjeta-2314', '2025-07-18 00:11:37', 'E', '14600999'),
(10064, 'J', '121212121', '1000000', 2298.19, 1981.2, 316.99, 'Divisas-12', '2025-07-18 00:15:41', 'E', '10000000'),
(10065, 'J', '121212121', '1000000', 2298.19, 1981.2, 316.99, 'Divisas-16', '2025-07-18 00:17:37', 'E', '14600999'),
(10066, 'J', '121212121', '1000000', 2298.19, 1981.2, 316.99, 'Divisas-12', '2025-07-18 00:29:46', 'E', '14600999'),
(10067, 'J', '121212121', '1000000', 2298.19, 1981.2, 316.99, 'Efectivo', '2025-07-18 00:32:50', 'E', '14600272'),
(10068, 'J', '121212121', '1000000', 1915.16, 1651, 264.16, 'Pago Movil-12', '2025-07-18 08:09:40', '30124', '30124678'),
(10069, 'J', '121212121', '1000000', 1031.24, 889, 142.24, 'Pago Movil-123', '2025-07-18 08:09:59', 'E', '10000000'),
(10070, 'J', '121212121', '1000000', 1915.16, 1651, 264.16, 'Tarjeta-3214', '2025-07-18 08:10:27', 'E', '14600272'),
(10071, 'J', '121212121', '1000000', 1915.16, 1651, 264.16, 'Transferencia-1234', '2025-07-20 01:49:48', '30124', '30124678'),
(10072, 'J', '121212121', '1000000', 4524, 3900, 624, '[{\"metodo\":\"Pago Movil\"', '2025-10-21 12:25:19', 'E', '10000000'),
(10073, 'J', '121212121', '1000000', 3016, 2600, 416, 'Tarjeta-1234', '2025-10-21 10:22:19', 'E', '10000000'),
(10074, 'J', '121212121', '1000000', 4524, 3900, 624, '', '2025-10-21 11:06:03', 'E', '10000000'),
(10075, 'J', '121212121', '1000000', 3900, 3900, 0, 'Tarjeta-1234', '2025-10-21 11:06:50', 'E', '14600272'),
(10076, 'J', '121212121', '1000000', 1624, 1400, 224, 'Divisas-7', '2025-10-21 11:10:07', 'E', '14600999'),
(10077, 'J', '121212121', '1000000', 8143.2, 7020, 1123.2, 'Tarjeta-12345', '2012-11-01 04:59:27', 'E', '10000000'),
(10078, 'J', '121212121', '1000000', 4524, 3900, 624, '', '2025-10-23 08:18:31', 'E', '14600272');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_producto`
--

CREATE TABLE `venta_producto` (
  `id` int(11) NOT NULL,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio_total_en_linea` decimal(10,2) AS (`precio_unitario` * `cantidad`) VIRTUAL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `venta_producto`
--

INSERT INTO `venta_producto` (`id`, `factura`, `cantidad`, `precio_unitario`, `producto`) VALUES
(5, 10044, 1, '1716.00', '2323233-2'),
(6, 10044, 1, '1430.00', '121342-2'),
(8, 10045, 1, '1716.00', '2323233-2'),
(9, 10046, 1, '1716.00', '2323233-2'),
(10, 10047, 1, '1716.00', '2323233-2'),
(11, 10048, 1, '1716.00', '2323233-2'),
(12, 10048, 1, '1430.00', '121342-2'),
(13, 10049, 1, '1716.00', '2323233-2'),
(14, 10049, 1, '1430.00', '121342-2'),
(15, 10050, 1, '1716.00', '2323233-2'),
(16, 10050, 1, '1430.00', '121342-2'),
(17, 10051, 1, '1716.00', '2323233-2'),
(18, 10052, 1, '1716.00', '2323233-2'),
(19, 10052, 1, '1430.00', '121342-2'),
(20, 10053, 4, '1716.00', '2323233-2'),
(21, 10053, 1, '1430.00', '121342-2'),
(22, 10054, 1, '1716.00', '2323233-2'),
(23, 10055, 1, '1430.00', '121342-2'),
(24, 10056, 3, '1651.00', 'ard-093-as34'),
(25, 10057, 1, '1651.00', 'ard-093-as34'),
(26, 10057, 1, '1981.20', '2323233-2'),
(27, 10057, 10, '3302.00', '121342-2-abc'),
(28, 10058, 1, '1981.20', '2323233-2'),
(29, 10059, 4, '3302.00', '121342-2-abc'),
(30, 10060, 1, '1651.00', '121342-2'),
(31, 10060, 1, '1651.00', 'ard-093-as34'),
(32, 10061, 1, '1981.20', '2323233-2'),
(33, 10062, 1, '1981.20', '2323233-2'),
(34, 10063, 1, '1981.20', '2323233-2'),
(35, 10064, 1, '1981.20', '2323233-2'),
(36, 10065, 1, '1981.20', '2323233-2'),
(37, 10066, 1, '1981.20', '2323233-2'),
(38, 10067, 1, '1981.20', '2323233-2'),
(39, 10068, 1, '1651.00', 'ard-093-as34'),
(40, 10069, 1, '889.00', '2323233-lo-p'),
(41, 10070, 1, '1651.00', '2323233-le3-3213'),
(42, 10071, 1, '1651.00', 'ard-093-as34'),
(43, 10072, 1, '3900.00', '23245-43434-eret'),
(44, 10073, 1, '2600.00', 'ard-093-as34'),
(45, 10074, 1, '3900.00', '23245-43434-eret'),
(46, 10075, 1, '3900.00', '23245-43434-eret'),
(47, 10076, 1, '1400.00', '2323233-lo-p'),
(48, 10077, 1, '3900.00', '23245-43434-eret'),
(49, 10077, 1, '3120.00', '2323233-sd'),
(50, 10078, 1, '3900.00', '23245-43434-eret');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`tipo_ced`,`num_ced`);

--
-- Indices de la tabla `colores`
--
ALTER TABLE `colores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `compras`
--
ALTER TABLE `compras`
  ADD PRIMARY KEY (`id_compra`),
  ADD UNIQUE KEY `uk_factura_proveedor` (`numero_factura_proveedor`,`tipo_rif`,`num_rif`),
  ADD KEY `fk_proveedor_rif` (`tipo_rif`,`num_rif`);

--
-- Indices de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD PRIMARY KEY (`id_detalle_compra`),
  ADD KEY `fk_detalle_compra_compra` (`id_compra`),
  ADD KEY `fk_detalle_compra_producto` (`codigo_producto`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`cedula`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`tipo_rif`,`num_rif`);

--
-- Indices de la tabla `event_log`
--
ALTER TABLE `event_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_cedula` (`employee_cedula`);

--
-- Indices de la tabla `historial_dolar`
--
ALTER TABLE `historial_dolar`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre_metodo` (`nombre_metodo`);

--
-- Indices de la tabla `pagos_venta`
--
ALTER TABLE `pagos_venta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pagos_venta_factura` (`factura_id`),
  ADD KEY `fk_pagos_venta_metodo_pago` (`metodo_pago_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `id_tipo` (`id_tipo`),
  ADD KEY `id_categoria` (`id_categoria`,`id_color`,`id_marca`),
  ADD KEY `id_color` (`id_color`),
  ADD KEY `id_marca` (`id_marca`),
  ADD KEY `fk_productos_proveedor` (`tipo_rif_proveedor`,`num_rif_proveedor`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`tipo_rif`,`num_rif`);

--
-- Indices de la tabla `tipos`
--
ALTER TABLE `tipos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`cedula`),
  ADD UNIQUE KEY `usuario_unique` (`usuario`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`factura`),
  ADD KEY `vendedor` (`vendedor`),
  ADD KEY `fk_ventas_empresa_compuesta` (`tipo_rif_empresa`,`num_rif_empresa`),
  ADD KEY `fk_ventas_cliente_compuesta` (`tipo_ced_cliente`,`num_ced_cliente`);

--
-- Indices de la tabla `venta_producto`
--
ALTER TABLE `venta_producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `factura` (`factura`),
  ADD KEY `producto` (`producto`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `colores`
--
ALTER TABLE `colores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `compras`
--
ALTER TABLE `compras`
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=216;

--
-- AUTO_INCREMENT de la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  MODIFY `id_detalle_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- AUTO_INCREMENT de la tabla `event_log`
--
ALTER TABLE `event_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1317;

--
-- AUTO_INCREMENT de la tabla `historial_dolar`
--
ALTER TABLE `historial_dolar`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `marcas`
--
ALTER TABLE `marcas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `metodos_pago`
--
ALTER TABLE `metodos_pago`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `pagos_venta`
--
ALTER TABLE `pagos_venta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `tipos`
--
ALTER TABLE `tipos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10079;

--
-- AUTO_INCREMENT de la tabla `venta_producto`
--
ALTER TABLE `venta_producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `compras`
--
ALTER TABLE `compras`
  ADD CONSTRAINT `fk_proveedor_rif` FOREIGN KEY (`tipo_rif`,`num_rif`) REFERENCES `proveedores` (`tipo_rif`, `num_rif`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_compra`
--
ALTER TABLE `detalle_compra`
  ADD CONSTRAINT `fk_detalle_compra_compra` FOREIGN KEY (`id_compra`) REFERENCES `compras` (`id_compra`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_detalle_compra_producto` FOREIGN KEY (`codigo_producto`) REFERENCES `productos` (`codigo`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `event_log`
--
ALTER TABLE `event_log`
  ADD CONSTRAINT `event_log_ibfk_1` FOREIGN KEY (`employee_cedula`) REFERENCES `empleado` (`cedula`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `pagos_venta`
--
ALTER TABLE `pagos_venta`
  ADD CONSTRAINT `fk_pagos_venta_factura` FOREIGN KEY (`factura_id`) REFERENCES `ventas` (`factura`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pagos_venta_metodo_pago` FOREIGN KEY (`metodo_pago_id`) REFERENCES `metodos_pago` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_productos_proveedor` FOREIGN KEY (`tipo_rif_proveedor`,`num_rif_proveedor`) REFERENCES `proveedores` (`tipo_rif`, `num_rif`) ON UPDATE CASCADE,
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_color`) REFERENCES `colores` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `productos_ibfk_4` FOREIGN KEY (`id_tipo`) REFERENCES `tipos` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `fk_usuarios_empleado_cedula` FOREIGN KEY (`cedula`) REFERENCES `empleado` (`cedula`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `fk_ventas_cliente_compuesta` FOREIGN KEY (`tipo_ced_cliente`,`num_ced_cliente`) REFERENCES `clientes` (`tipo_ced`, `num_ced`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_ventas_empresa_compuesta` FOREIGN KEY (`tipo_rif_empresa`,`num_rif_empresa`) REFERENCES `empresa` (`tipo_rif`, `num_rif`) ON UPDATE CASCADE,
  ADD CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `venta_producto`
--
ALTER TABLE `venta_producto`
  ADD CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `ventas` (`factura`) ON UPDATE CASCADE,
  ADD CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
