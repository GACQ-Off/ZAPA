-- phpMyAdmin SQL Dump
-- version 4.8.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-11-2024 a las 10:00:33
-- Versión del servidor: 10.1.36-MariaDB
-- Versión de PHP: 7.0.32

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
-- Estructura de tabla para la tabla `apartado`
--

CREATE TABLE `apartado` (
  `factura` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` int(11) NOT NULL,
  `vendedor` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `abonado` float NOT NULL,
  `impuesto` int(11) NOT NULL,
  `cliente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `apartado_producto`
--

CREATE TABLE `apartado_producto` (
  `id` int(11) NOT NULL,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

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
(7, 'Ropa', '2024-10-21 01:47:02'),
(9, 'Calzados', '2024-10-21 02:32:09'),
(10, 'ACCESORIOS', '2024-10-23 06:24:40'),
(12, 'COMPONENTES', '2024-10-30 06:08:07');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

CREATE TABLE `clientes` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `apellido` text COLLATE utf8_spanish_ci NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `clientes`
--

INSERT INTO `clientes` (`cedula`, `nombre`, `apellido`, `email`, `telefono`, `direccion`) VALUES
('12400500', 'Ismael', 'Martinez', 'ismale@gmail.com', '4127421201', 'Tu casa'),
('259103825', 'Vanessa', 'Teran', 'vane1q@gmail.com', '2108974', 'La loma'),
('30124678', 'jose angel', 'cabeza arias', 'jose@gmail.com', '04120630245', 'av sucre'),
('31744101', 'Jhoan', 'Mejia', 'mejiajhoan2021@gmail.com', '4247079098', 'Bisnaca'),
('31982314', 'Esbeidy', 'Gil', 'esbeydui@gmail.com', 'Miticun', '10987235'),
('7689758', 'Julio', 'Guanda', 'vane1q@gmail.com', '4247079089', 'Miticun'),
('908712984', 'Julio', 'Carrasquero', 'vane@gmail.com', '125123', 'Miticun');

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
-- Estructura de tabla para la tabla `empleado`
--

CREATE TABLE `empleado` (
  `cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `foto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `empleado`
--

INSERT INTO `empleado` (`cedula`, `telefono`, `nombre`, `apellido`, `direccion`, `foto`) VALUES
('1000000', '121212', 'super', 'usuario', 'bocono', ''),
('121312353', '2151235', 'Joan', 'Bastidas', 'Caracas', ''),
('12393320', '432141325', 'Francisco', 'Arias', '', ''),
('12847231', '9012754', 'Ricardo', 'Yepez Bastidas', '', ''),
('1290481', '432141325', 'DOUGLAS JOSUE', 'DEL VALLE', '', ''),
('23194809', '9102375089', 'Isaac', 'Bastidas', '', ''),
('3174101', '172089720', 'Jota', 'Mejia', '', ''),
('31744100', '04247714244', 'Jhoan', 'Mejia', 'Bisnaca', ''),
('31744101', '4247079098', 'Jhoan', 'Mejia', 'El carmen', ''),
('40123456', '409879', 'Francisco', 'Martinez', 'Rincon 3', 'vistas/img/usuarios//661.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(80) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio_dolar` float NOT NULL,
  `impuesto` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`rif`, `direccion`, `nombre`, `telefono`, `precio_dolar`, `impuesto`) VALUES
('j-1234087', 'Centro de Bocono', 'Kamyl Styles y Algo mas de Susana Al chariti', '1385132', 37, 16);

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
(10, 'adidas', '2024-10-21 12:48:24'),
(11, 'pocholin', '2024-10-21 12:48:31'),
(13, 'rs21', '2024-10-21 12:48:44'),
(14, 'Aero', '2012-11-01 04:37:27'),
(15, 'generica', '2012-11-01 04:37:35'),
(16, 'acadia', '2012-11-01 04:37:46'),
(17, 'sifrina', '2012-11-01 04:37:59'),
(18, 'NIKE', '2024-10-25 22:32:27');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id_categoria` int(11) NOT NULL,
  `id_tipo` int(11) NOT NULL,
  `id_color` int(11) NOT NULL,
  `id_marca` int(11) NOT NULL,
  `rif_proveedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `codigo` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `imagen` text COLLATE utf8_spanish_ci NOT NULL,
  `stock` int(11) NOT NULL,
  `precio_compra` float NOT NULL,
  `precio_venta` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id_categoria`, `id_tipo`, `id_color`, `id_marca`, `rif_proveedor`, `codigo`, `descripcion`, `imagen`, `stock`, `precio_compra`, `precio_venta`) VALUES
(7, 5, 1, 11, '122319847', '1010', 'MANILLA', 'vistas/img/productos/default/anonymous.png', 14, 2, 2.8),
(10, 5, 1, 16, '2151351235', '1212', 'FORZA XL', 'vistas/img/productos/1212/907.jpg', 10, 2, 2.8),
(7, 5, 1, 11, '122319847', '1234', 'camisa', 'vistas/img/productos/default/anonymous.png', 8, 5, 7),
(7, 5, 1, 11, '122319847', '1235', 'bota', 'vistas/img/productos/default/anonymous.png', 9, 15, 21),
(10, 5, 1, 11, '122319847', '12874', 'TOLTO', 'vistas/img/productos/default/anonymous.png', 10, 12, 16.8),
(10, 5, 1, 11, '122319847', '2000', 'RELOG', 'vistas/img/productos/default/anonymous.png', 15, 2, 2.8),
(9, 5, 1, 11, '122319847', '2141', 'LKjdakl', 'vistas/img/productos/2141/351.jpg', 10, 12, 16.8),
(9, 5, 1, 11, '122319847', '214123', '1523', 'vistas/img/productos/default/anonymous.png', 297, 12, 16.8),
(10, 5, 10, 11, '122319847', '214u1', 'BOTAS', 'vistas/img/productos/default/anonymous.png', 0, 12, 16.8),
(10, 8, 13, 18, '2151351235', '21512356', 'COLLARES', 'vistas/img/productos/default/anonymous.png', 6, 2, 2.6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cedula_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `correo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`rif`, `nombre`, `nombre_representante`, `apellido_representante`, `direccion`, `cedula_representante`, `telefono`, `correo`) VALUES
('122319847', 'NIKE SPORT', 'Vanesa', 'Yepez', 'Caracas', '128948912', '921087', 'vane@gmail.com'),
('2151351235', 'TOTAL PANDA', 'Yanderson', 'Bastidas', 'Sotchi', '1298412', '291501235', 'yan@gmail.com');

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
(5, 'caballero', '2012-11-01 04:34:32'),
(6, 'niño', '2012-11-01 04:34:45'),
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
  `estado` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`cedula`, `usuario`, `password`, `perfil`, `estado`) VALUES
('1000000', 'admin', '$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG', 'Administrador', 1),
('1290481', 'fesal79', '$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS', 'Vendedor', 1),
('12393320', 'francoarias81', '$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS', 'Administrador', 1),
('40123456', 'gsus', '$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS', 'Vendedor', 1),
('23194809', 'isa', '$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS', 'Vendedor', 1),
('12847231', 'ricki', '$2a$07$asxx54ahjppf45sd87a5auuA/wno05nzDP.bGv2s5tKCJKhZVQQgC', 'Administrador', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ventas`
--

CREATE TABLE `ventas` (
  `factura` int(11) NOT NULL,
  `rif_empresa` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `vendedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `total` float NOT NULL,
  `pago` float NOT NULL,
  `impuesto` float NOT NULL DEFAULT '16',
  `metodo_pago` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cliente` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `ventas`
--

INSERT INTO `ventas` (`factura`, `rif_empresa`, `vendedor`, `total`, `pago`, `impuesto`, `metodo_pago`, `fecha`, `cliente`) VALUES
(10001, 'j-1234087', '23194809', 153.224, 132.09, 21.13, 'Tarjeta-1234', '2024-11-10 16:08:33', '12400500'),
(10002, 'j-1234087', '23194809', 1595.34, 1375.29, 220.05, 'Tarjeta-1234', '2024-11-10 16:13:24', '30124678'),
(10003, 'j-1234087', '23194809', 1595.34, 1375.29, 220.05, 'Efectivo', '2024-11-10 16:35:21', '12400500'),
(10004, 'j-1234087', '23194809', 1595.34, 1375.29, 220.05, 'Tarjeta-1234', '2024-11-10 17:02:44', '908712984'),
(10005, 'j-1234087', '23194809', 111.592, 96.2, 15.39, 'Efectivo', '2024-11-10 18:33:46', '12400500'),
(10006, 'j-1234087', '23194809', 721.056, 621.6, 99.46, 'Tarjeta-1', '2024-11-10 18:34:07', '12400500'),
(10007, 'j-1234087', '23194809', 721.056, 621.6, 99.46, 'Tarjeta-2', '2024-11-10 18:34:28', '259103825'),
(10008, 'j-1234087', '23194809', 721.056, 621.6, 99.46, 'Tarjeta-2', '2024-11-10 18:34:45', '259103825'),
(10009, 'j-1234087', '23194809', 721.056, 621.6, 99.46, 'Efectivo', '2024-11-10 18:35:05', '259103825'),
(10010, 'j-1234087', '23194809', 111.592, 96.2, 15.39, 'Efectivo', '2024-11-10 18:35:34', '12400500'),
(10011, 'j-1234087', '23194809', 120.176, 103.6, 16.58, 'Tarjeta-2', '2024-11-10 18:35:51', '31744101'),
(10012, 'j-1234087', '23194809', 1553.7, 1339.4, 214.3, 'Tarjeta-1234', '2024-11-10 18:39:23', '31744101'),
(10013, 'j-1234087', '23194809', 1553.7, 1339.4, 214.3, 'Tarjeta-1234', '2024-11-10 18:40:30', '259103825'),
(10014, 'j-1234087', '1000000', 721.056, 621.6, 99.46, 'Tarjeta-1234', '2024-11-10 20:12:20', '12400500'),
(10015, 'j-1234087', '1000000', 111.592, 96.2, 15.39, 'Efectivo', '2024-11-10 22:56:17', '30124678'),
(10016, 'j-1234087', '1000000', 832.648, 717.8, 114.85, 'Efectivo', '2024-11-11 06:46:06', '12400500'),
(10017, 'j-1234087', '1000000', 1673.88, 1443, 230.88, 'Tarjeta-1234', '2024-11-11 08:20:21', '259103825'),
(10018, 'j-1234087', '1000000', 1442.11, 1243.2, 198.91, 'Tarjeta-1234', '2024-11-11 08:55:53', '12400500');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_producto`
--

CREATE TABLE `venta_producto` (
  `id` int(11) NOT NULL,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `venta_producto`
--

INSERT INTO `venta_producto` (`id`, `factura`, `cantidad`, `producto`) VALUES
(70, 10001, 1, '21512356'),
(71, 10002, 1, '21512356'),
(72, 10002, 1, '214u1'),
(73, 10002, 1, '214123'),
(77, 10003, 1, '21512356'),
(78, 10003, 1, '214u1'),
(79, 10003, 1, '214123'),
(80, 10004, 1, '21512356'),
(81, 10004, 1, '214u1'),
(82, 10004, 1, '214123'),
(83, 10005, 1, '21512356'),
(84, 10006, 1, '214123'),
(85, 10007, 1, '2141'),
(86, 10008, 1, '214u1'),
(87, 10009, 1, '214123'),
(88, 10010, 1, '21512356'),
(89, 10011, 1, '2000'),
(90, 10012, 1, '21512356'),
(91, 10012, 1, '214u1'),
(92, 10012, 1, '214123'),
(93, 10013, 1, '21512356'),
(94, 10013, 1, '214u1'),
(95, 10013, 1, '214123'),
(96, 10014, 1, '214u1'),
(97, 10015, 1, '21512356'),
(98, 10016, 1, '21512356'),
(99, 10016, 1, '214u1'),
(100, 10017, 1, '21512356'),
(101, 10017, 1, '214123'),
(102, 10017, 1, '2141'),
(103, 10017, 1, '2000'),
(104, 10018, 1, '214123'),
(105, 10018, 1, '2141');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `apartado`
--
ALTER TABLE `apartado`
  ADD PRIMARY KEY (`factura`),
  ADD KEY `vendedor` (`vendedor`,`cliente`),
  ADD KEY `cliente` (`cliente`);

--
-- Indices de la tabla `apartado_producto`
--
ALTER TABLE `apartado_producto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `factura` (`factura`,`producto`),
  ADD KEY `producto` (`producto`);

--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`cedula`);

--
-- Indices de la tabla `colores`
--
ALTER TABLE `colores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `empleado`
--
ALTER TABLE `empleado`
  ADD PRIMARY KEY (`cedula`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`rif`);

--
-- Indices de la tabla `marcas`
--
ALTER TABLE `marcas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `id_tipo` (`id_tipo`),
  ADD KEY `id_categoria` (`id_categoria`,`id_color`,`id_marca`),
  ADD KEY `id_color` (`id_color`),
  ADD KEY `id_marca` (`id_marca`),
  ADD KEY `rif_proveedor` (`rif_proveedor`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`rif`);

--
-- Indices de la tabla `tipos`
--
ALTER TABLE `tipos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`usuario`),
  ADD KEY `cedula` (`cedula`);

--
-- Indices de la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD PRIMARY KEY (`factura`),
  ADD KEY `rif_empresa` (`rif_empresa`),
  ADD KEY `cliente` (`cliente`),
  ADD KEY `vendedor` (`vendedor`);

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
-- AUTO_INCREMENT de la tabla `apartado_producto`
--
ALTER TABLE `apartado_producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `colores`
--
ALTER TABLE `colores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `marcas`
--
ALTER TABLE `marcas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `tipos`
--
ALTER TABLE `tipos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `ventas`
--
ALTER TABLE `ventas`
  MODIFY `factura` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10019;

--
-- AUTO_INCREMENT de la tabla `venta_producto`
--
ALTER TABLE `venta_producto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `apartado`
--
ALTER TABLE `apartado`
  ADD CONSTRAINT `apartado_ibfk_1` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`),
  ADD CONSTRAINT `apartado_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`);

--
-- Filtros para la tabla `apartado_producto`
--
ALTER TABLE `apartado_producto`
  ADD CONSTRAINT `apartado_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `apartado` (`factura`),
  ADD CONSTRAINT `apartado_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`);

--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_color`) REFERENCES `colores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `productos_ibfk_4` FOREIGN KEY (`id_tipo`) REFERENCES `tipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `productos_ibfk_5` FOREIGN KEY (`rif_proveedor`) REFERENCES `proveedores` (`rif`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ventas`
--
ALTER TABLE `ventas`
  ADD CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`rif_empresa`) REFERENCES `empresa` (`rif`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `venta_producto`
--
ALTER TABLE `venta_producto`
  ADD CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `ventas` (`factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
