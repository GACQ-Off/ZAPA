-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 20-04-2024 a las 22:31:41
-- Versión del servidor: 10.1.10-MariaDB
-- Versión de PHP: 7.0.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `Sistema_Ventas`
--
CREATE DATABASE IF NOT EXISTS `Sistema_Ventas` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `Sistema_Ventas`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Cliente`
--

CREATE TABLE `Cliente` (
  `Cedula_Cliente` varchar(10) NOT NULL,
  `Nombres_Cliente` varchar(40) NOT NULL,
  `Apellidos_Cliente` varchar(40) NOT NULL,
  `Telefono_Cliente` varchar(12) NOT NULL,
  `Correo_Cliente` varchar(100) NOT NULL,
  `Direccion_Cliente` varchar(100) NOT NULL,
  `Fecha_Hora_Cliente` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Usuario_Cedula` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `Cliente`
--

INSERT INTO `Cliente` (`Cedula_Cliente`, `Nombres_Cliente`, `Apellidos_Cliente`, `Telefono_Cliente`, `Correo_Cliente`, `Direccion_Cliente`, `Fecha_Hora_Cliente`, `Usuario_Cedula`) VALUES
('10258418', 'Ramón', 'Hernánez', '32423423', 'ramon@gmail.com', 'La Sabanita', '2024-04-02 14:18:31', '16418498'),
('111111', 'María josefina', 'Torres Mendoza', '2726524020', 'maria@hotmail.com', 'la elba', '2024-03-12 19:15:52', '16418498'),
('12', 'José Ramón', 'Nuñez', '324325', 'j@gmail.com', 'safsfds', '2024-03-21 11:22:24', '16418498'),
('1234', 'Luby', 'Osal paz', '237595479', 'JuanAraujo@gmail.com', 'El Molino Quibor estado Lara', '2024-03-12 16:35:11', '16418498'),
('123456', 'María', 'Pérez', '4263519089', 'maria@gmail.com', 'La Sabanita', '2024-03-12 16:46:34', '4412307'),
('12345678', 'Ramón José', 'Rodríguez Martínez', '23423535', 'dsfds@gmia.com', 'ewtergfdgf', '2024-03-13 14:57:34', '18471271'),
('2222', 'Yyyy', 'sasss', '312343', 'fdsfsdf@hotmail.com', 'wewqeqweqw', '2024-03-13 14:59:11', '18471271'),
('33333', 'Luis', 'ortegano', '44234028', 'luis@hotmail.com', 'valle verde', '2024-03-12 19:16:43', '16418498'),
('4412307', 'Angela Custodia', 'Mendoza de Martínez', '4264525859', 'angela.custodia.mendoza@gmail.com', 'El Molino Quibor estado Lara', '2024-03-12 16:38:55', '16418498'),
('7386715', 'Lisandro José', 'Rodríguez Martínez', '1321312312', 'lisandro@hotmail.com', 'La Purilimpia El Molino Del Quíbor', '2024-03-23 01:26:42', '16418498');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Entrada_Producto`
--

CREATE TABLE `Entrada_Producto` (
  `Id_Entrada_Producto` int(11) NOT NULL,
  `Cod_Producto` int(11) NOT NULL,
  `Cantidad_Entrada` int(11) NOT NULL,
  `Precio` decimal(10,2) NOT NULL,
  `Fecha_Entrada_Producto` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Usuario_Cedula` varchar(10) NOT NULL DEFAULT '16418498'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Libro`
--

CREATE TABLE `Libro` (
  `Id_Libro` int(11) NOT NULL,
  `Nombre_Libro` varchar(100) NOT NULL,
  `Autor_Libro` varchar(50) NOT NULL,
  `Archivo_Libro` varchar(200) NOT NULL,
  `Codigo_Usuario` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `Libro`
--

INSERT INTO `Libro` (`Id_Libro`, `Nombre_Libro`, `Autor_Libro`, `Archivo_Libro`, `Codigo_Usuario`) VALUES
(1, 'eweqweq', '0', 'Geometria.pdf', '16418498'),
(2, 'sadsa', 'zx<zx<z', 'Leithold-7a.Ed..pdf', '16418498'),
(3, 'www', 'ggg', 'EL-PROYECTO-DE-INVESTIGACIÃ“N-6ta-Ed.-FIDIAS-G.-ARIAS.pdf', '16418498'),
(4, 'InvestigaciÃ³n de Operaciones', 'Linda Betania', 'IO 9 Ed-taha.pdf', '16418498'),
(5, 'Calculo', 'Larzon', 'Calculo_Vol.1_-_Larson_-_Hostetler.pdf', '16418498');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Producto`
--

CREATE TABLE `Producto` (
  `Codigo_Producto` int(11) NOT NULL,
  `Descripcion_Producto` varchar(100) NOT NULL,
  `Precio_Producto` decimal(10,2) NOT NULL,
  `Existencia_Producto` int(11) NOT NULL,
  `Foto_Producto` text NOT NULL,
  `Proveedor_Producto` int(11) NOT NULL,
  `Fecha_Hora_Producto` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Usuario_Cedula` varchar(10) NOT NULL,
  `Estatus_Producto` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Proveedor`
--

CREATE TABLE `Proveedor` (
  `Rif_Proveedor` varchar(15) NOT NULL,
  `Nombre_Proveedor` varchar(50) NOT NULL,
  `Cedula_Contacto` varchar(10) NOT NULL,
  `Nombres_Contacto` varchar(50) NOT NULL,
  `Apellidos_Contacto` varchar(50) NOT NULL,
  `Telefono_Proveedor` varchar(12) NOT NULL,
  `Correo_Proveedor` varchar(40) NOT NULL,
  `Direccion_Proveedor` varchar(100) NOT NULL,
  `Fecha_Hora_Proveedor` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Usuario_Cedula` varchar(10) NOT NULL,
  `Estatus_Proveedor` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `Proveedor`
--

INSERT INTO `Proveedor` (`Rif_Proveedor`, `Nombre_Proveedor`, `Cedula_Contacto`, `Nombres_Contacto`, `Apellidos_Contacto`, `Telefono_Proveedor`, `Correo_Proveedor`, `Direccion_Proveedor`, `Fecha_Hora_Proveedor`, `Usuario_Cedula`, `Estatus_Proveedor`) VALUES
('G-20005902-8', 'UPTTMBI', '16418498', 'Ismael', 'MartÃ­nez', '0426-3711445', 'ismael@gmail.com', 'La Milla', '2024-03-21 02:49:24', '16418498', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Tipo_Usuario`
--

CREATE TABLE `Tipo_Usuario` (
  `Id_Tipo_Usuario` int(11) NOT NULL,
  `Descripcion_Tipo_Usuario` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `Tipo_Usuario`
--

INSERT INTO `Tipo_Usuario` (`Id_Tipo_Usuario`, `Descripcion_Tipo_Usuario`) VALUES
(1, 'Administrador'),
(2, 'Supervisor'),
(3, 'Vendedor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuario`
--

CREATE TABLE `Usuario` (
  `Cedula_Usuario` varchar(10) NOT NULL,
  `Nombres_Usuario` varchar(40) NOT NULL,
  `Apellidos_Usuario` varchar(40) NOT NULL,
  `Telefono_Usuario` varchar(15) NOT NULL,
  `Correo_Usuario` varchar(50) NOT NULL,
  `Usuario` varchar(50) NOT NULL,
  `Clave_Usuario` varchar(100) NOT NULL,
  `Codigo_Tipo_Usuario` int(11) NOT NULL,
  `Estatus_Usuario` int(11) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `Usuario`
--

INSERT INTO `Usuario` (`Cedula_Usuario`, `Nombres_Usuario`, `Apellidos_Usuario`, `Telefono_Usuario`, `Correo_Usuario`, `Usuario`, `Clave_Usuario`, `Codigo_Tipo_Usuario`, `Estatus_Usuario`) VALUES
('123123', 'Juana Maria', 'perez', '12423423', 'juanamaria@hotmail.com', 'juana', '123456', 3, 0),
('12345678', 'Juan JosÃ©', 'PÃ©rez Mendoza Linares', '4263711445', 'juanperez90@gmail.com', 'Juan90', '1234', 1, 0),
('16418498', 'Ismael RamÃ³n', 'MartÃ­nez Mendoza', '0426-3711445', 'ismael.martinez.mendoza@gmail.com', 'Ismael', '123', 1, 1),
('18471148', 'Carla Katerine', 'MejÃ­a', '4147370012', 'carla@gmail.com', 'Carla', 'carla', 3, 1),
('18471271', 'Hector josÃ©', 'angulo', '23432355', 'hangulo@upttmbi.edu.ve', 'Hector', '12345', 3, 1),
('19849233', 'Manuel Antonio', 'MartÃ­nez Mendoza', '4263711445', 'manuelmartinez@gmail.com', 'Manuel', '1234', 3, 1),
('20405322', 'Humberto Pastor', 'MartÃ­nez Mendoza', '4263711445', 'humbertomartinez@gmail.com', 'Humberto', '1234', 3, 1),
('30130057', 'Deximar', 'Torrealba', '4263711445', 'deximar@gmail.com', 'Deximar', 'deximar', 3, 1),
('33333333', 'Carmen', 'Rodriguez', '23473295', 'carmen@gmail.com', 'Carmen', 'carmen', 2, 0),
('4412307', 'Angela Custodia', 'Mendoza de MartÃ­nez', '4264515859', 'angela.custodia.mendoza@gmail.com', 'Angela', '1234', 2, 1),
('44444444', 'JesÃºs Leandro', 'Valera', '23123124', 'jesus@gmail.com', 'Jesus', 'jesus', 2, 1),
('7386715', 'Lisandro JosÃ©', 'Valera', '27432042', 'lisandro@gmail.com', 'Lisandro', '1234', 2, 1),
('7450154', 'Rafael Jose', 'Mendoza Linarez', '221476', 'rafael@gmail.com', 'Rafael', '12345', 2, 1),
('7988566', 'Pedro Pablo', 'Mendoza', '3124723804', 'pedropablo@gmail.com', 'Pedro', 'pedro', 2, 1),
('888888888', 'Sadas', 'dsadsa', '543534', 'hh@gmail.com', 'ttt', '1234', 3, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Cliente`
--
ALTER TABLE `Cliente`
  ADD PRIMARY KEY (`Cedula_Cliente`),
  ADD KEY `Codigo_Usuario` (`Usuario_Cedula`),
  ADD KEY `Codigo_Usuario_2` (`Usuario_Cedula`),
  ADD KEY `Codigo_Usuario_3` (`Usuario_Cedula`),
  ADD KEY `Cedula_Usuario` (`Usuario_Cedula`),
  ADD KEY `Cedula_Usuario_2` (`Usuario_Cedula`),
  ADD KEY `Cedula_Usuario_3` (`Usuario_Cedula`),
  ADD KEY `Cedula_U` (`Usuario_Cedula`),
  ADD KEY `Usuario_Cedula` (`Usuario_Cedula`),
  ADD KEY `Usuario_Cedula_2` (`Usuario_Cedula`),
  ADD KEY `Usuario_Cedula_3` (`Usuario_Cedula`);

--
-- Indices de la tabla `Entrada_Producto`
--
ALTER TABLE `Entrada_Producto`
  ADD PRIMARY KEY (`Id_Entrada_Producto`),
  ADD KEY `Usuario_Cedula` (`Usuario_Cedula`);

--
-- Indices de la tabla `Libro`
--
ALTER TABLE `Libro`
  ADD PRIMARY KEY (`Id_Libro`),
  ADD KEY `Codigo_Usuario` (`Codigo_Usuario`),
  ADD KEY `Codigo_Usuario_2` (`Codigo_Usuario`);

--
-- Indices de la tabla `Producto`
--
ALTER TABLE `Producto`
  ADD PRIMARY KEY (`Codigo_Producto`),
  ADD KEY `Usuario_Cedula` (`Usuario_Cedula`),
  ADD KEY `Proveedor_Producto` (`Proveedor_Producto`),
  ADD KEY `Usuario_Cedula_2` (`Usuario_Cedula`);

--
-- Indices de la tabla `Proveedor`
--
ALTER TABLE `Proveedor`
  ADD PRIMARY KEY (`Rif_Proveedor`),
  ADD KEY `Usuario_Cedula` (`Usuario_Cedula`);

--
-- Indices de la tabla `Tipo_Usuario`
--
ALTER TABLE `Tipo_Usuario`
  ADD PRIMARY KEY (`Id_Tipo_Usuario`);

--
-- Indices de la tabla `Usuario`
--
ALTER TABLE `Usuario`
  ADD PRIMARY KEY (`Cedula_Usuario`),
  ADD KEY `Codigo_Tipo_Usuario` (`Codigo_Tipo_Usuario`),
  ADD KEY `Codigo_Tipo_Usuario_2` (`Codigo_Tipo_Usuario`),
  ADD KEY `Codigo_Tipo_Usuario_3` (`Codigo_Tipo_Usuario`),
  ADD KEY `Codigo_Tipo_Usuario_4` (`Codigo_Tipo_Usuario`),
  ADD KEY `Codigo_Tipo_Usuario_5` (`Codigo_Tipo_Usuario`),
  ADD KEY `Codigo_Tipo_Usuario_6` (`Codigo_Tipo_Usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Entrada_Producto`
--
ALTER TABLE `Entrada_Producto`
  MODIFY `Id_Entrada_Producto` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Libro`
--
ALTER TABLE `Libro`
  MODIFY `Id_Libro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT de la tabla `Producto`
--
ALTER TABLE `Producto`
  MODIFY `Codigo_Producto` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `Tipo_Usuario`
--
ALTER TABLE `Tipo_Usuario`
  MODIFY `Id_Tipo_Usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Cliente`
--
ALTER TABLE `Cliente`
  ADD CONSTRAINT `Cliente_ibfk_1` FOREIGN KEY (`Usuario_Cedula`) REFERENCES `Usuario` (`Cedula_Usuario`);

--
-- Filtros para la tabla `Libro`
--
ALTER TABLE `Libro`
  ADD CONSTRAINT `Libro_ibfk_1` FOREIGN KEY (`Codigo_Usuario`) REFERENCES `Usuario` (`Cedula_Usuario`);

--
-- Filtros para la tabla `Producto`
--
ALTER TABLE `Producto`
  ADD CONSTRAINT `Producto_ibfk_1` FOREIGN KEY (`Usuario_Cedula`) REFERENCES `Usuario` (`Cedula_Usuario`);

--
-- Filtros para la tabla `Proveedor`
--
ALTER TABLE `Proveedor`
  ADD CONSTRAINT `Proveedor_ibfk_1` FOREIGN KEY (`Usuario_Cedula`) REFERENCES `Usuario` (`Cedula_Usuario`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
