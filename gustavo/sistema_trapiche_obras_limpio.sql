-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-10-2025 a las 21:31:51
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistema_trapiche_obras`
--
CREATE DATABASE IF NOT EXISTS `sistema_trapiche_obras` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `sistema_trapiche_obras`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `area`
--

DROP TABLE IF EXISTS `area`;
CREATE TABLE `area` (
`id_area` int(11) NOT NULL COMMENT 'Identificativo de Área',
`nombre_area` varchar(32) NOT NULL COMMENT 'Nombre de Área',
`img_area` varchar(125) DEFAULT NULL COMMENT 'Ruta de Imagen de Área',
`status_area` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Status Sistema de Área',
`usuario_ci` varchar(9) NOT NULL COMMENT 'Cédula de Usuario Registrante'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autor`
--

DROP TABLE IF EXISTS `autor`;
CREATE TABLE `autor` (
`ci_autor` varchar(9) NOT NULL COMMENT 'Cédula de Autor',
`nombres_autor` varchar(32) NOT NULL COMMENT '(1er y 2do) Nombres de Autor',
`apellidos_autor` varchar(32) NOT NULL COMMENT '(1er y 2do) Apellidos de Autor',
`seudonimos_autor` varchar(65) DEFAULT NULL COMMENT 'Seudónimos de Autor',
`f_nacimiento_autor` date DEFAULT NULL COMMENT 'F.Nacimiento de Autor',
`f_fallecimiento_autor` date DEFAULT NULL COMMENT 'F.Fallecimiento de Autor',
`telefono_autor` varchar(13) DEFAULT NULL COMMENT 'N.Telefónico de Autor',
`mail_autor` varchar(65) DEFAULT NULL COMMENT 'E-Mail de Autor',
`domicilio_autor` varchar(85) DEFAULT NULL COMMENT 'D.Domicilio de Autor',
`status_autor` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Status Sistema de Autor',
`usuario_ci` varchar(9) NOT NULL COMMENT 'Cédula de Usuario Registrante'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `autor_obra`
--

DROP TABLE IF EXISTS `autor_obra`;
CREATE TABLE `autor_obra` (
`id_autor_obra` int(11) NOT NULL COMMENT 'Identificativo de Autoría',
`obra_cod` varchar(25) NOT NULL COMMENT 'Código de Obra de Arte',
`autor_ci` varchar(9) NOT NULL COMMENT 'Cédula de Autor',
`f_elaboracion_obra` date DEFAULT NULL COMMENT 'F.Creación de Obra de Arte'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categoria`
--

DROP TABLE IF EXISTS `categoria`;
CREATE TABLE `categoria` (
`id_categoria` int(11) NOT NULL COMMENT 'Identificativo de Categoría',
`nombre_categoria` varchar(32) NOT NULL COMMENT 'Nombre de Categoría',
`descripcion_categoria` varchar(255) DEFAULT NULL COMMENT 'Texto Descriptivo de Categoría',
`status_categoria` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Status Sistema de Categoría',
`usuario_ci` varchar(9) NOT NULL COMMENT 'Cédula de Usuario Registrante'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coleccion`
--

DROP TABLE IF EXISTS `coleccion`;
CREATE TABLE `coleccion` (
`cod_coleccion` varchar(25) NOT NULL COMMENT 'Código de Colección',
`titulo_coleccion` varchar(65) NOT NULL COMMENT 'Título de Colección',
`f_creacion_coleccion` date DEFAULT (CURRENT_DATE()) COMMENT 'F.Creación de Colección',
`naturaleza_coleccion` varchar(11) NOT NULL DEFAULT 'Permanente' COMMENT 'Naturaleza de Colección',
`estado_coleccion` varchar(12) NOT NULL DEFAULT 'Disponible' COMMENT 'Estado de Colección',
`descripcion_coleccion` varchar(255) DEFAULT NULL COMMENT 'Texto Descriptivo de Colección',
`status_coleccion` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Status Sistema de Colección',
`usuario_ci` varchar(9) NOT NULL COMMENT 'Cédula de Usuario Registrante'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantenimiento_obra`
--

DROP TABLE IF EXISTS `mantenimiento_obra`;
CREATE TABLE `mantenimiento_obra` (
`id_mantenimiento_obra` int(11) NOT NULL COMMENT 'Identificativo de Listado de Mantenimiento',
`mantenimiento_id` int(11) NOT NULL COMMENT 'Identificativo de Mantenimiento',
`obra_cod` varchar(25) NOT NULL COMMENT 'Código de Obra de Arte'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mantenimiento`
--

DROP TABLE IF EXISTS `mantenimiento`;
CREATE TABLE `mantenimiento` (
`id_mantenimiento` int(11) NOT NULL COMMENT 'Identificativo de Solicitud de Mantenimiento',
`f_escritura_mantenimiento` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'F.Escritura de Solicitud de Mantenimiento',
`resumen_mantenimiento` varchar(85) NOT NULL COMMENT 'Texto Resumen (Título) Solicitud de Mantenimiento',
`descripcion_mantenimiento` TEXT(2550) NOT NULL COMMENT 'Texto descriptivo (Sustento) de Solicitud de Mantenimiento',
`estado_mantenimiento` varchar(11) NOT NULL DEFAULT 'Pendiente' COMMENT 'Estado de Solicitud de Mantenimiento',
`f_actualizacion_mantenimiento` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'F.Actualización de Solicitud de Mantenimiento',
`img_mantenimiento` varchar(125) DEFAULT NULL COMMENT 'Rutas de Imágenes de Solicitud de Mantenimiento',
`status_mantenimiento` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Status Sistema de Solicitud de Mantenimiento',
`usuario_ci` varchar(9) NOT NULL COMMENT 'Cédula de Usuario Solicitante',
`restaurador_ci` varchar(9) NOT NULL COMMENT 'Cédula del Restaurador Asociado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `material`
--

DROP TABLE IF EXISTS `material`;
CREATE TABLE `material` (
`id_material` int(11) NOT NULL COMMENT 'Identificativo de Material',
`nombre_material` varchar(32) NOT NULL COMMENT 'Nombre de Material',
`status_material` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Status Sistema de Material',
`usuario_ci` varchar(9) NOT NULL COMMENT 'Cédula de Usuario Registrante'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `obra`
--

DROP TABLE IF EXISTS `obra`;
CREATE TABLE `obra` (
`cod_obra` varchar(25) NOT NULL COMMENT 'Código de Obra de Arte',
`titulo_obra` varchar(65) NOT NULL COMMENT 'Título de Obra de Arte',
`alto_obra` int(11) DEFAULT NULL COMMENT 'Altura de Obra de Arte',
`ancho_obra` int(11) DEFAULT NULL COMMENT 'Anchura de Obra de Arte',
`profundidad_obra` int(11) DEFAULT NULL COMMENT 'Profundidad de Obra de Arte',
`procedencia_obra` varchar(13) NOT NULL COMMENT 'Procedencia de Obra de Arte',
`f_ingreso_obra` date DEFAULT NULL COMMENT 'F.Ingreso de Obra de Arte',
`valor_obra` decimal(10,2) DEFAULT NULL COMMENT 'Valor Estimado ($) de Obra de Arte',
`f_tasacion_obra` date DEFAULT NULL COMMENT 'F.Tasación de Obra de Arte',
`estado_obra` varchar(12) NOT NULL DEFAULT 'Bueno' COMMENT 'E.Conservación de la Obra',
`descripcion_obra` varchar(255) DEFAULT NULL COMMENT 'Texto Descriptivo de Obra de Arte',
`img_obra` varchar(125) DEFAULT NULL COMMENT 'Ruta de Imagen de Obra de Arte',
`status_obra` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Status Sistema de Obra de Arte',
`coleccion_cod` varchar(25) NOT NULL COMMENT 'Código de Colección Asociada',
`categoria_id` int(11) NOT NULL COMMENT 'Identificativo de Categoría Asociada',
`material_id` int(11) NOT NULL COMMENT 'Identificativo de Material Empleado',
`tecnica_id` int(11) NOT NULL COMMENT 'Identificativo de Técnica Empleada',
`area_id` int(11) NOT NULL COMMENT 'Identificativo de Área Asociada',
`usuario_ci` varchar(9) NOT NULL COMMENT 'Cédula de Usuario Registrante'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `restaurador`
--

DROP TABLE IF EXISTS `restaurador`;
CREATE TABLE `restaurador` (
`ci_restaurador` varchar(9) NOT NULL COMMENT 'Cédula de Restaurador',
`nombres_restaurador` varchar(32) NOT NULL COMMENT '(1er y 2do) Nombres de Restaurador',
`apellidos_restaurador` varchar(32) NOT NULL COMMENT '(1er y 2do) Apellidos de Restaurador',
`telefono_restaurador` varchar(13) DEFAULT NULL COMMENT 'N.Telefónico de Restaurador',
`mail_restaurador` varchar(65) DEFAULT NULL COMMENT 'E-mail de Restaurador',
`domicilio_restaurador` varchar(85) DEFAULT NULL COMMENT 'D.Domicilio de Restaurador',
`status_restaurador` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Status Sistema de Restaurador',
`usuario_ci` varchar(9) NOT NULL COMMENT 'Cédula de Usuario Registrante'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

DROP TABLE IF EXISTS `rol`;
CREATE TABLE `rol` (
`id_rol` tinyint(1) NOT NULL,
`descripcion_rol` varchar(22) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `descripcion_rol`) VALUES
(1, 'Usuario'),
(2, 'Administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tecnica`
--

DROP TABLE IF EXISTS `tecnica`;
CREATE TABLE `tecnica` (
`id_tecnica` int(11) NOT NULL COMMENT 'Identificativo de Técnica',
`nombre_tecnica` varchar(32) NOT NULL COMMENT 'Nombre de Técnica',
`status_tecnica` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Status Sistema de Técnica',
`usuario_ci` varchar(9) NOT NULL COMMENT 'Cédula de Usuario Registrante'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `trapiche`
--

DROP TABLE IF EXISTS `trapiche`;
CREATE TABLE `trapiche` (
`rif_trapiche` varchar(11) NOT NULL COMMENT 'Registro Único de Información Fiscal de Trapiche',
`nombre_trapiche` varchar(45) NOT NULL COMMENT 'Nombre de Trapiche',
`ci_director_a_trapiche` varchar(9) NOT NULL COMMENT 'Cédula de Director(a) de Trapiche',
`nombres_director_a_trapiche` varchar(32) NOT NULL COMMENT 'Nombres de Director(a) de Trapiche',
`apellidos_director_a_trapiche` varchar(32) NOT NULL COMMENT 'Apellidos de Director(a) de Trapiche',
`ubicacion_trapiche` varchar(75) NOT NULL COMMENT '(D.)Ubicación de Trapiche',
`telefono_trapiche` varchar(25) NOT NULL COMMENT 'N.Telefónicos de Trapiche',
`mail_trapiche` varchar(65) NOT NULL COMMENT 'E-Mail de Trapiche',
`logo_trapiche` varchar(125) NOT NULL COMMENT 'Ruta de Logotipo de Trapiche',
`f_fundacion_trapiche` date NOT NULL COMMENT 'F.Fundación de Trapiche',
`usuario_ci` varchar(9) NOT NULL COMMENT 'Cédula de Usuario Registrante'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
`ci_usuario` varchar(9) NOT NULL COMMENT 'Cédula de Usuario',
`nombres_usuario` varchar(32) NOT NULL COMMENT '(1er y 2do) Nombres de Usuario',
`apellidos_usuario` varchar(32) NOT NULL COMMENT '(1er y 2do) Apellidos de Usuario',
`f_nacimiento_usuario` date DEFAULT NULL COMMENT 'F.Nacimiento de Usuario',
`telefono_usuario` varchar(13) DEFAULT NULL COMMENT 'N.Telefónico de Usuario',
`mail_usuario` varchar(65) DEFAULT NULL COMMENT 'E-Mail de Usuario',
`domicilio_usuario` varchar(85) DEFAULT NULL COMMENT 'D.Domicilio de Usuario',
`img_usuario` varchar(125) DEFAULT NULL COMMENT 'Ruta de Imagen de Usuario',
`nombre_usuario` varchar(32) NOT NULL COMMENT 'Nombre Sistema de Usuario',
`pass_usuario` varchar(32) NOT NULL COMMENT 'Contraseña de Usuario',
`status_usuario` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Status Sistema de Usuario',
`rol_id` tinyint(1) NOT NULL COMMENT 'Identificativo de Rol Asociado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`ci_usuario`, `nombres_usuario`, `apellidos_usuario`, `f_nacimiento_usuario`, `telefono_usuario`, `mail_usuario`, `domicilio_usuario`, `img_usuario`, `nombre_usuario`, `pass_usuario`, `status_usuario`, `rol_id`) VALUES
('1109', '229s', '11', NULL, NULL, NULL, NULL, NULL, 'aas', '112s', 0, 1),
('13932122', 'Katherine', 'Villamizar', NULL, NULL, NULL, NULL, NULL, 'KAT1', '2200', 0, 1),
('19190190', 'Gustavo José', 'Celada Berrios', '1970-01-01', '', '', '', '68e7bb2aef61b1760017194.jpg', 'GUS20', '11223344', 1, 1),
('191919', 'Manuel', 'Quintero Rojas', NULL, NULL, NULL, NULL, '68e6255cce03e1759913308.jpg', 'MAN1', '19191919', 1, 1),
('19339560', 'Sergio', 'Villegas', NULL, NULL, NULL, NULL, NULL, 'SE1', '3311', 0, 1),
('2299099', 'Miguel Angel', 'Quintero Vargas', '2003-12-12', '', '', 'Mosquey, Municipio Boconó. Cerca del Aeropuerto', NULL, 'MIG01', 'Palabras', 1, 1),
('30108009', 'Gustavo Adolfo', 'Celada', '2003-09-11', '02726520383', 'gustavo@gmail.com', 'Parroquia Boconó. La Milla. Estado Trujillo', '68e957d846f871760122840.jpeg', 'GUS1', '123456789', 1, 2),
('32290147', 'Angel', 'Arraiz', '2006-04-07', '04247015056', 'angel@gmail.com', 'Boconó', NULL, 'ANG01', '12342000', 1, 2),
('5632440', 'Abigail', 'Soler Vargas', '0000-00-00', '', '', '', NULL, 'AB01', '11223344', 1, 1),
('77', 'Tt', 'Tttttttt', NULL, NULL, NULL, NULL, NULL, 'tttttt', 'gggggggggggggggggg', 0, 1),
('9009112', 'David', 'Quevedo Requena', '0000-00-00', '02726520909', 'david@outlook.es', 'Las Mesitas, Municipio Boconó.', NULL, 'DAV1', '33003000', 1, 2),
('kkkkk', 'Www3', '22222', NULL, NULL, NULL, NULL, NULL, 's', 's', 0, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `area`
--
ALTER TABLE `area`
ADD PRIMARY KEY (`id_area`),
ADD KEY `usuario_ci` (`usuario_ci`);

--
-- Indices de la tabla `autor`
--
ALTER TABLE `autor`
ADD PRIMARY KEY (`ci_autor`),
ADD KEY `usuario_ci` (`usuario_ci`);

--
-- Indices de la tabla `autor_obra`
--
ALTER TABLE `autor_obra`
ADD PRIMARY KEY (`id_autor_obra`),
ADD KEY `autor_ci` (`autor_ci`),
ADD KEY `obra_cod` (`obra_cod`);

--
-- Indices de la tabla `categoria`
--
ALTER TABLE `categoria`
ADD PRIMARY KEY (`id_categoria`),
ADD KEY `usuario_ci` (`usuario_ci`);

--
-- Indices de la tabla `coleccion`
--
ALTER TABLE `coleccion`
ADD PRIMARY KEY (`cod_coleccion`),
ADD KEY `usuario_ci` (`usuario_ci`);

--
-- Indices de la tabla `mantenimiento_obra`
--
ALTER TABLE `mantenimiento_obra`
ADD PRIMARY KEY (`id_mantenimiento_obra`),
ADD KEY `mantenimiento_id` (`mantenimiento_id`),
ADD KEY `obra_cod` (`obra_cod`);

--
-- Indices de la tabla `mantenimiento`
--
ALTER TABLE `mantenimiento`
ADD PRIMARY KEY (`id_mantenimiento`),
ADD KEY `restaurador_ci` (`restaurador_ci`),
ADD KEY `usuario_ci` (`usuario_ci`);

--
-- Indices de la tabla `material`
--
ALTER TABLE `material`
ADD PRIMARY KEY (`id_material`),
ADD KEY `usuario_ci` (`usuario_ci`);

--
-- Indices de la tabla `obra`
--
ALTER TABLE `obra`
ADD PRIMARY KEY (`cod_obra`),
ADD KEY `usuario_ci` (`usuario_ci`),
ADD KEY `area_id` (`area_id`),
ADD KEY `coleccion_cod` (`coleccion_cod`),
ADD KEY `material_id` (`material_id`),
ADD KEY `tecnica_id` (`tecnica_id`),
ADD KEY `categoria_id` (`categoria_id`);

--
-- Indices de la tabla `restaurador`
--
ALTER TABLE `restaurador`
ADD PRIMARY KEY (`ci_restaurador`),
ADD KEY `usuario_ci` (`usuario_ci`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `tecnica`
--
ALTER TABLE `tecnica`
ADD PRIMARY KEY (`id_tecnica`),
ADD KEY `usuario_ci` (`usuario_ci`);

--
-- Indices de la tabla `trapiche`
--
ALTER TABLE `trapiche`
ADD PRIMARY KEY (`rif_trapiche`),
ADD KEY `usuario_ci` (`usuario_ci`),
ADD KEY `usuario_ci_2` (`usuario_ci`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
ADD PRIMARY KEY (`ci_usuario`),
ADD KEY `rol_id` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `area`
--
ALTER TABLE `area`
MODIFY `id_area` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificativo de Área';

--
-- AUTO_INCREMENT de la tabla `autor_obra`
--
ALTER TABLE `autor_obra`
MODIFY `id_autor_obra` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificativo de Autoría';

--
-- AUTO_INCREMENT de la tabla `categoria`
--
ALTER TABLE `categoria`
MODIFY `id_categoria` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificativo de Categoría';

--
-- AUTO_INCREMENT de la tabla `mantenimiento_obra`
--
ALTER TABLE `mantenimiento_obra`
MODIFY `id_mantenimiento_obra` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificativo de Listado de Mantenimiento';

--
-- AUTO_INCREMENT de la tabla `mantenimiento`
--
ALTER TABLE `mantenimiento`
MODIFY `id_mantenimiento` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificativo de Solicitud de Mantenimiento';

--
-- AUTO_INCREMENT de la tabla `material`
--
ALTER TABLE `material`
MODIFY `id_material` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificativo de Material';

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
MODIFY `id_rol` tinyint(1) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `tecnica`
--
ALTER TABLE `tecnica`
MODIFY `id_tecnica` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Identificativo de Técnica';

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `area`
--
ALTER TABLE `area`
ADD CONSTRAINT `area_ibfk_1` FOREIGN KEY (`usuario_ci`) REFERENCES `usuario` (`ci_usuario`);

--
-- Filtros para la tabla `autor`
--
ALTER TABLE `autor`
ADD CONSTRAINT `autor_ibfk_1` FOREIGN KEY (`usuario_ci`) REFERENCES `usuario` (`ci_usuario`);

--
-- Filtros para la tabla `autor_obra`
--
ALTER TABLE `autor_obra`
ADD CONSTRAINT `autor_obra_ibfk_1` FOREIGN KEY (`autor_ci`) REFERENCES `autor` (`ci_autor`),
ADD CONSTRAINT `autor_obra_ibfk_2` FOREIGN KEY (`obra_cod`) REFERENCES `obra` (`cod_obra`);

--
-- Filtros para la tabla `categoria`
--
ALTER TABLE `categoria`
ADD CONSTRAINT `categoria_ibfk_1` FOREIGN KEY (`usuario_ci`) REFERENCES `usuario` (`ci_usuario`);

--
-- Filtros para la tabla `coleccion`
--
ALTER TABLE `coleccion`
ADD CONSTRAINT `coleccion_ibfk_1` FOREIGN KEY (`usuario_ci`) REFERENCES `usuario` (`ci_usuario`);

--
-- Filtros para la tabla `mantenimiento_obra`
--
ALTER TABLE `mantenimiento_obra`
ADD CONSTRAINT `mantenimiento_obra_ibfk_1` FOREIGN KEY (`mantenimiento_id`) REFERENCES `mantenimiento` (`id_mantenimiento`),
ADD CONSTRAINT `mantenimiento_obra_ibfk_2` FOREIGN KEY (`obra_cod`) REFERENCES `obra` (`cod_obra`);

--
-- Filtros para la tabla `mantenimiento`
--
ALTER TABLE `mantenimiento`
ADD CONSTRAINT `mantenimiento_ibfk_1` FOREIGN KEY (`usuario_ci`) REFERENCES `usuario` (`ci_usuario`),
ADD CONSTRAINT `mantenimiento_ibfk_2` FOREIGN KEY (`restaurador_ci`) REFERENCES `restaurador` (`ci_restaurador`);

--
-- Filtros para la tabla `material`
--
ALTER TABLE `material`
ADD CONSTRAINT `material_ibfk_1` FOREIGN KEY (`usuario_ci`) REFERENCES `usuario` (`ci_usuario`);

--
-- Filtros para la tabla `obra`
--
ALTER TABLE `obra`
ADD CONSTRAINT `obra_ibfk_1` FOREIGN KEY (`coleccion_cod`) REFERENCES `coleccion` (`cod_coleccion`),
ADD CONSTRAINT `obra_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id_categoria`),
ADD CONSTRAINT `obra_ibfk_3` FOREIGN KEY (`material_id`) REFERENCES `material` (`id_material`),
ADD CONSTRAINT `obra_ibfk_4` FOREIGN KEY (`tecnica_id`) REFERENCES `tecnica` (`id_tecnica`),
ADD CONSTRAINT `obra_ibfk_5` FOREIGN KEY (`area_id`) REFERENCES `area` (`id_area`),
ADD CONSTRAINT `obra_ibfk_6` FOREIGN KEY (`usuario_ci`) REFERENCES `usuario` (`ci_usuario`);

--
-- Filtros para la tabla `restaurador`
--
ALTER TABLE `restaurador`
ADD CONSTRAINT `restaurador_ibfk_1` FOREIGN KEY (`usuario_ci`) REFERENCES `usuario` (`ci_usuario`);

--
-- Filtros para la tabla `tecnica`
--
ALTER TABLE `tecnica`
ADD CONSTRAINT `tecnica_ibfk_1` FOREIGN KEY (`usuario_ci`) REFERENCES `usuario` (`ci_usuario`);

--
-- Filtros para la tabla `trapiche`
--
ALTER TABLE `trapiche`
ADD CONSTRAINT `trapiche_ibfk_1` FOREIGN KEY (`usuario_ci`) REFERENCES `usuario` (`ci_usuario`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `rol` (`id_rol`);
COMMIT;