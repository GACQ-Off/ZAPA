CREATE DATABASE IF NOT EXISTS `zapateriak`;

USE `zapateriak`;

SET foreign_key_checks = 0;

DROP TABLE IF EXISTS `apartado`;

CREATE TABLE `apartado` (
  `factura` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` int(11) NOT NULL,
  `vendedor` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `abonado` float NOT NULL,
  `impuesto` int(11) NOT NULL,
  `cliente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `vendedor` (`vendedor`,`cliente`),
  KEY `cliente` (`cliente`),
  CONSTRAINT `apartado_ibfk_1` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`),
  CONSTRAINT `apartado_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `apartado_producto`;

CREATE TABLE `apartado_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`,`producto`),
  KEY `producto` (`producto`),
  CONSTRAINT `apartado_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `apartado` (`factura`),
  CONSTRAINT `apartado_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `categorias` VALUES (7,"Ropa","2024-10-20 21:17:02"),
(9,"Calzados","2024-10-20 22:02:09"),
(10,"ACCESORIOS","2024-10-23 01:54:40"),
(12,"COMPONENTES","2024-10-30 01:38:07");


DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `apellido` text COLLATE utf8_spanish_ci NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `clientes` VALUES (12393100,"Ricardos","Bastidas","ricardomartin@gmail.com",04247712112,"Rincon 3"),
(12400500,"Ismael","Martinez","ismale@gmail.com",04127421201,"Tu casa"),
(259103825,"Vanessa","Teran","vane1q@gmail.com",2108974,"La loma"),
(31744101,"Jhoan","Mejia","mejiajhoan2021@gmail.com",04247079098,"Bisnaca"),
(31982314,"Esbeidy","Gil","esbeydui@gmail.com","Miticun",10987235),
(7689758,"Julio","Guanda","vane1q@gmail.com",04247079089,"Miticun"),
(908712984,"Julio","Carrasquero","vane@gmail.com",125123,"Miticun");


DROP TABLE IF EXISTS `colores`;

CREATE TABLE `colores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `colores` VALUES (1,"negro","2024-10-21 05:52:13"),
(10,"blanco","2024-10-21 08:17:13"),
(11,"verde","2024-10-21 08:17:24"),
(12,"beige","2012-11-01 00:08:15"),
(13,"TUTIFRUTTY","2024-10-26 20:34:51");


DROP TABLE IF EXISTS `empleado`;

CREATE TABLE `empleado` (
  `cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `foto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empleado` VALUES (12393320,0432141325,"Francisco","Arias",""),
(12847231,9012754,"Ricardo","Yepez Bastidas",""),
(1290481,0432141325,"DOUGLAS JOSUE","DEL VALLE",""),
(23194809,9102375089,"Isaac","Bastidas",""),
(3174101,172089720,"Jota","Mejia",""),
(31744101,04247079098,"Jhoan","Mejia",""),
(40123456,0409879,"Francisco","Martinez","vistas/img/usuarios//661.jpg");


DROP TABLE IF EXISTS `empresa`;

CREATE TABLE `empresa` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(80) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio_dolar` float NOT NULL,
  `impuesto` float NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empresa` VALUES ("j-1234087","Centro de Bocono","Kamyl Styles y Algo Mas de Susana Alchariti",1385132,"44.05",16);


DROP TABLE IF EXISTS `marcas`;

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marca` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `marcas` VALUES (10,"adidas","2024-10-21 08:18:24"),
(11,"pocholin","2024-10-21 08:18:31"),
(13,"rs21","2024-10-21 08:18:44"),
(14,"Aero","2012-11-01 00:07:27"),
(15,"generica","2012-11-01 00:07:35"),
(16,"acadia","2012-11-01 00:07:46"),
(17,"sifrina","2012-11-01 00:07:59"),
(18,"NIKE","2024-10-25 18:02:27");


DROP TABLE IF EXISTS `productos`;

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
  `precio_venta` float NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `id_tipo` (`id_tipo`),
  KEY `id_categoria` (`id_categoria`,`id_color`,`id_marca`),
  KEY `id_color` (`id_color`),
  KEY `id_marca` (`id_marca`),
  KEY `rif_proveedor` (`rif_proveedor`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_color`) REFERENCES `colores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_4` FOREIGN KEY (`id_tipo`) REFERENCES `tipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_5` FOREIGN KEY (`rif_proveedor`) REFERENCES `proveedores` (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `productos` VALUES (7,5,1,11,122319847,1010,"MANILLA","vistas/img/productos/default/anonymous.png",11,2,"2.8"),
(10,5,1,16,2151351235,1212,"FORZA XL","vistas/img/productos/1212/907.jpg",6,2,"2.8"),
(7,5,1,11,122319847,1234,"camisa","vistas/img/productos/default/anonymous.png",6,5,7),
(7,5,1,11,122319847,1235,"bota","vistas/img/productos/default/anonymous.png",5,15,21),
(10,5,1,11,122319847,12874,"TOLTO","vistas/img/productos/default/anonymous.png",6,12,"16.8"),
(10,5,1,11,122319847,2000,"RELOG","vistas/img/productos/default/anonymous.png",14,2,"2.8"),
(9,5,1,11,122319847,2141,"LKjdakl","vistas/img/productos/2141/351.jpg",9,12,"16.8"),
(9,5,1,11,122319847,214123,1523,"vistas/img/productos/default/anonymous.png",305,12,"16.8"),
(10,5,10,11,122319847,"214u1","BOTAS","vistas/img/productos/default/anonymous.png",3,12,"16.8"),
(10,8,13,18,2151351235,21512356,"COLLARES AESTHETIC","vistas/img/productos/default/anonymous.png",9,2,1);


DROP TABLE IF EXISTS `proveedores`;

CREATE TABLE `proveedores` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cedula_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `correo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `proveedores` VALUES (122319847,"NIKE SPORT","Vanesa","Yepez","Caracas",128948912,0921087,"vane@gmail.com"),
(2151351235,"TOTAL PANDA","Yanderson","Bastidas","Sotchi",1298412,291501235,"yan@gmail.com");


DROP TABLE IF EXISTS `tipos`;

CREATE TABLE `tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `tipos` VALUES (5,"caballero","2012-11-01 00:04:32"),
(6,"niño","2012-11-01 00:04:45"),
(7,"niña","2012-11-01 00:04:52"),
(8,"dama","2012-11-01 00:06:55");


DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `perfil` text COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL,
  PRIMARY KEY (`usuario`),
  KEY `cedula` (`cedula`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `usuarios` VALUES (23194809,"admin","$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG","Administrador",1),
(23194809,"francoarias81","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Administrador",1),
(40123456,"gsus","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Vendedor",1),
(31744101,"jotadevs","$2a$07$asxx54ahjppf45sd87a5auw6I7JoP8iFx7dMSn3sb4CatTnVVTCzi","Vendedor",1),
(12847231,"ricki","$2a$07$asxx54ahjppf45sd87a5auuA/wno05nzDP.bGv2s5tKCJKhZVQQgC","Administrador",1);


DROP TABLE IF EXISTS `venta_producto`;

CREATE TABLE `venta_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`),
  KEY `producto` (`producto`),
  CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `ventas` (`factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `venta_producto` VALUES (38,10001,1,1212),
(39,10002,1,1212),
(40,10002,2,1010),
(41,10002,2,21512356),
(42,10003,2,2000),
(43,10004,1,12874),
(44,10005,1,21512356),
(45,10005,1,"214u1"),
(46,10006,1,21512356),
(47,10006,1,"214u1"),
(48,10006,1,214123),
(49,10006,1,2141),
(50,10006,1,2000),
(51,10006,1,12874),
(52,10006,1,1235),
(53,10006,1,1234),
(54,10006,1,1212),
(55,10006,1,1010),
(56,10007,1,"214u1"),
(57,10008,1,214123),
(58,10009,1,214123),
(59,10010,1,2141),
(60,10011,1,1010),
(61,10012,1,1235),
(62,10012,1,12874),
(63,10012,1,2000),
(64,10013,1,2000),
(65,10014,1,12874),
(66,10015,1,1235),
(67,10016,1,21512356),
(68,10016,1,"214u1"),
(69,10017,1,2141),
(70,10017,1,214123),
(71,10017,1,"214u1"),
(72,10017,1,21512356),
(73,10018,1,21512356),
(74,10018,1,"214u1"),
(75,10018,1,214123),
(76,10019,1,21512356),
(77,10019,1,"214u1"),
(78,10019,1,2141),
(79,10019,1,2000),
(80,10020,1,21512356),
(81,10020,1,"214u1"),
(82,10020,1,214123),
(83,10021,1,214123),
(84,10021,1,2141),
(85,10022,1,21512356),
(86,10023,1,"214u1"),
(87,10024,1,21512356),
(88,10024,1,21512356),
(89,10025,1,21512356),
(90,10025,1,"214u1"),
(91,10025,1,214123),
(92,10025,1,2141),
(93,10026,1,"214u1"),
(94,10026,1,21512356),
(95,10027,1,1212),
(96,10028,1,21512356),
(97,10029,1,21512356),
(98,10030,1,1010),
(99,10030,1,1212),
(100,10030,1,1234),
(101,10031,1,21512356),
(102,10031,1,"214u1"),
(103,10032,1,21512356),
(104,10032,1,"214u1"),
(105,10032,1,214123),
(106,10032,1,2141),
(107,10033,1,21512356),
(108,10033,1,214123),
(109,10034,1,1212),
(110,10034,1,1235);


DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `factura` int(11) NOT NULL AUTO_INCREMENT,
  `rif_empresa` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `vendedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `total` float NOT NULL,
  `pago` float NOT NULL,
  `impuesto` int(11) NOT NULL DEFAULT '16',
  `metodo_pago` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cliente` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `rif_empresa` (`rif_empresa`),
  KEY `cliente` (`cliente`),
  KEY `vendedor` (`vendedor`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`rif_empresa`) REFERENCES `empresa` (`rif`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10035 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `ventas` VALUES (10001,"j-1234087",23194809,"120.18",100,16,"Transferencia","2024-11-02 03:16:58",12393100),
(10002,"j-1234087",23194809,"446.37",500,16,"Targeta","2024-11-02 03:19:36",12393100),
(10003,"j-1234087",23194809,"240.35",185,16,"Pago movil","2024-11-02 03:20:15",12400500),
(10004,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:19:32",12393100),
(10005,"j-1234087",23194809,"763.98","763.98",16,"Efectivo","2024-11-06 04:20:20",12393100),
(10006,"j-1234087",23194809,4,4,16,"Efectivo","2024-11-06 04:21:59",12400500),
(10007,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:22:38",12393100),
(10008,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:23:16",12393100),
(10009,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:24:39",12393100),
(10010,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:25:03",12400500),
(10011,"j-1234087",23194809,"120.18","120.18",16,"Efectivo","2024-11-06 04:25:28",12393100),
(10012,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:26:06",12400500),
(10013,"j-1234087",23194809,"120.18","120.18",16,"Efectivo","2024-11-06 04:26:39",12393100),
(10014,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:27:11",12400500),
(10015,"j-1234087",23194809,"901.32","901.32",16,"Targeta","2024-11-06 04:27:43",12393100),
(10016,"j-1234087",23194809,"763.98","763.98",16,"Efectivo","2024-11-06 04:28:22",12393100),
(10017,"j-1234087",23194809,2,2,16,"Efectivo","2024-11-06 04:30:15",12393100),
(10018,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:31:06",12393100),
(10019,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:31:54",12393100),
(10020,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:34:03",12393100),
(10021,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:34:37",12400500),
(10022,"j-1234087",23194809,"42.92","42.92",16,"Efectivo","2024-11-06 04:35:40",12400500),
(10023,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:37:53",908712984),
(10024,"j-1234087",23194809,"85.84","85.84",16,"Efectivo","2024-11-06 04:38:41",31982314),
(10025,"j-1234087",23194809,2,2,16,"Efectivo","2024-11-06 04:39:43",12393100),
(10026,"j-1234087",23194809,"763.98","763.98",16,"Efectivo","2024-11-06 04:41:06",12393100),
(10027,"j-1234087",23194809,"120.18","120.18",16,"Efectivo","2024-11-06 05:06:36",12393100),
(10028,"j-1234087",23194809,"42.92","42.92",16,"Efectivo","2024-11-06 05:50:08",12400500),
(10029,"j-1234087",23194809,"47.56","47.56",16,"Efectivo","2024-11-06 07:10:00",12393100),
(10030,"j-1234087",23194809,"599.26","599.26",16,"Efectivo","2024-11-06 07:13:11",12400500),
(10031,"j-1234087",23194809,"846.57","846.57",16,"Targeta","2012-11-01 00:05:57",12393100),
(10032,"j-1234087",23194809,2,2,16,"Pago movil","2012-11-01 00:07:12",12393100),
(10033,"j-1234087",40123456,"846.57","846.57",16,"Efectivo","2012-11-01 00:11:48",259103825),
(10034,"j-1234087",23194809,1,1,16,"Transferencia","2012-11-01 00:44:43",259103825);


SET foreign_key_checks = 1;
CREATE DATABASE IF NOT EXISTS `zapateriak`;

USE `zapateriak`;

SET foreign_key_checks = 0;

DROP TABLE IF EXISTS `apartado`;

CREATE TABLE `apartado` (
  `factura` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` int(11) NOT NULL,
  `vendedor` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `abonado` float NOT NULL,
  `impuesto` int(11) NOT NULL,
  `cliente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `vendedor` (`vendedor`,`cliente`),
  KEY `cliente` (`cliente`),
  CONSTRAINT `apartado_ibfk_1` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`),
  CONSTRAINT `apartado_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `apartado_producto`;

CREATE TABLE `apartado_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`,`producto`),
  KEY `producto` (`producto`),
  CONSTRAINT `apartado_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `apartado` (`factura`),
  CONSTRAINT `apartado_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `categorias` VALUES (7,"Ropa","2024-10-20 21:17:02"),
(9,"Calzados","2024-10-20 22:02:09"),
(10,"ACCESORIOS","2024-10-23 01:54:40"),
(12,"COMPONENTES","2024-10-30 01:38:07");


DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `apellido` text COLLATE utf8_spanish_ci NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `clientes` VALUES (12393100,"Ricardos","Bastidas","ricardomartin@gmail.com",04247712112,"Rincon 3"),
(12400500,"Ismael","Martinez","ismale@gmail.com",04127421201,"Tu casa"),
(259103825,"Vanessa","Teran","vane1q@gmail.com",2108974,"La loma"),
(31744101,"Jhoan","Mejia","mejiajhoan2021@gmail.com",04247079098,"Bisnaca"),
(31982314,"Esbeidy","Gil","esbeydui@gmail.com","Miticun",10987235),
(7689758,"Julio","Guanda","vane1q@gmail.com",04247079089,"Miticun"),
(908712984,"Julio","Carrasquero","vane@gmail.com",125123,"Miticun");


DROP TABLE IF EXISTS `colores`;

CREATE TABLE `colores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `colores` VALUES (1,"negro","2024-10-21 05:52:13"),
(10,"blanco","2024-10-21 08:17:13"),
(11,"verde","2024-10-21 08:17:24"),
(12,"beige","2012-11-01 00:08:15"),
(13,"TUTIFRUTTY","2024-10-26 20:34:51");


DROP TABLE IF EXISTS `empleado`;

CREATE TABLE `empleado` (
  `cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `foto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empleado` VALUES (12393320,0432141325,"Francisco","Arias",""),
(12847231,9012754,"Ricardo","Yepez Bastidas",""),
(1290481,0432141325,"DOUGLAS JOSUE","DEL VALLE",""),
(23194809,9102375089,"Isaac","Bastidas",""),
(3174101,172089720,"Jota","Mejia",""),
(31744101,04247079098,"Jhoan","Mejia",""),
(40123456,0409879,"Francisco","Martinez","vistas/img/usuarios//661.jpg");


DROP TABLE IF EXISTS `empresa`;

CREATE TABLE `empresa` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(80) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio_dolar` float NOT NULL,
  `impuesto` float NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empresa` VALUES ("j-1234087","Centro de Bocono","Kamyl Styles y Algo Mas de Susana Alchariti",1385132,"44.05",16);


DROP TABLE IF EXISTS `marcas`;

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marca` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `marcas` VALUES (10,"adidas","2024-10-21 08:18:24"),
(11,"pocholin","2024-10-21 08:18:31"),
(13,"rs21","2024-10-21 08:18:44"),
(14,"Aero","2012-11-01 00:07:27"),
(15,"generica","2012-11-01 00:07:35"),
(16,"acadia","2012-11-01 00:07:46"),
(17,"sifrina","2012-11-01 00:07:59"),
(18,"NIKE","2024-10-25 18:02:27");


DROP TABLE IF EXISTS `productos`;

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
  `precio_venta` float NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `id_tipo` (`id_tipo`),
  KEY `id_categoria` (`id_categoria`,`id_color`,`id_marca`),
  KEY `id_color` (`id_color`),
  KEY `id_marca` (`id_marca`),
  KEY `rif_proveedor` (`rif_proveedor`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_color`) REFERENCES `colores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_4` FOREIGN KEY (`id_tipo`) REFERENCES `tipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_5` FOREIGN KEY (`rif_proveedor`) REFERENCES `proveedores` (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `productos` VALUES (7,5,1,11,122319847,1010,"MANILLA","vistas/img/productos/default/anonymous.png",11,2,"2.8"),
(10,5,1,16,2151351235,1212,"FORZA XL","vistas/img/productos/1212/907.jpg",6,2,"2.8"),
(7,5,1,11,122319847,1234,"camisa","vistas/img/productos/default/anonymous.png",6,5,7),
(7,5,1,11,122319847,1235,"bota","vistas/img/productos/default/anonymous.png",5,15,21),
(10,5,1,11,122319847,12874,"TOLTO","vistas/img/productos/default/anonymous.png",6,12,"16.8"),
(10,5,1,11,122319847,2000,"RELOG","vistas/img/productos/default/anonymous.png",14,2,"2.8"),
(9,5,1,11,122319847,2141,"LKjdakl","vistas/img/productos/2141/351.jpg",9,12,"16.8"),
(9,5,1,11,122319847,214123,1523,"vistas/img/productos/default/anonymous.png",305,12,"16.8"),
(10,5,10,11,122319847,"214u1","BOTAS","vistas/img/productos/default/anonymous.png",3,12,"16.8"),
(10,8,13,18,2151351235,21512356,"COLLARES AESTHETIC","vistas/img/productos/default/anonymous.png",9,2,1);


DROP TABLE IF EXISTS `proveedores`;

CREATE TABLE `proveedores` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cedula_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `correo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `proveedores` VALUES (122319847,"NIKE SPORT","Vanesa","Yepez","Caracas",128948912,0921087,"vane@gmail.com"),
(2151351235,"TOTAL PANDA","Yanderson","Bastidas","Sotchi",1298412,291501235,"yan@gmail.com");


DROP TABLE IF EXISTS `tipos`;

CREATE TABLE `tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `tipos` VALUES (5,"caballero","2012-11-01 00:04:32"),
(6,"niño","2012-11-01 00:04:45"),
(7,"niña","2012-11-01 00:04:52"),
(8,"dama","2012-11-01 00:06:55");


DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `perfil` text COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL,
  PRIMARY KEY (`usuario`),
  KEY `cedula` (`cedula`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `usuarios` VALUES (23194809,"admin","$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG","Administrador",1),
(23194809,"francoarias81","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Administrador",1),
(40123456,"gsus","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Vendedor",1),
(31744101,"jotadevs","$2a$07$asxx54ahjppf45sd87a5auw6I7JoP8iFx7dMSn3sb4CatTnVVTCzi","Vendedor",1),
(12847231,"ricki","$2a$07$asxx54ahjppf45sd87a5auuA/wno05nzDP.bGv2s5tKCJKhZVQQgC","Administrador",1);


DROP TABLE IF EXISTS `venta_producto`;

CREATE TABLE `venta_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`),
  KEY `producto` (`producto`),
  CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `ventas` (`factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `venta_producto` VALUES (38,10001,1,1212),
(39,10002,1,1212),
(40,10002,2,1010),
(41,10002,2,21512356),
(42,10003,2,2000),
(43,10004,1,12874),
(44,10005,1,21512356),
(45,10005,1,"214u1"),
(46,10006,1,21512356),
(47,10006,1,"214u1"),
(48,10006,1,214123),
(49,10006,1,2141),
(50,10006,1,2000),
(51,10006,1,12874),
(52,10006,1,1235),
(53,10006,1,1234),
(54,10006,1,1212),
(55,10006,1,1010),
(56,10007,1,"214u1"),
(57,10008,1,214123),
(58,10009,1,214123),
(59,10010,1,2141),
(60,10011,1,1010),
(61,10012,1,1235),
(62,10012,1,12874),
(63,10012,1,2000),
(64,10013,1,2000),
(65,10014,1,12874),
(66,10015,1,1235),
(67,10016,1,21512356),
(68,10016,1,"214u1"),
(69,10017,1,2141),
(70,10017,1,214123),
(71,10017,1,"214u1"),
(72,10017,1,21512356),
(73,10018,1,21512356),
(74,10018,1,"214u1"),
(75,10018,1,214123),
(76,10019,1,21512356),
(77,10019,1,"214u1"),
(78,10019,1,2141),
(79,10019,1,2000),
(80,10020,1,21512356),
(81,10020,1,"214u1"),
(82,10020,1,214123),
(83,10021,1,214123),
(84,10021,1,2141),
(85,10022,1,21512356),
(86,10023,1,"214u1"),
(87,10024,1,21512356),
(88,10024,1,21512356),
(89,10025,1,21512356),
(90,10025,1,"214u1"),
(91,10025,1,214123),
(92,10025,1,2141),
(93,10026,1,"214u1"),
(94,10026,1,21512356),
(95,10027,1,1212),
(96,10028,1,21512356),
(97,10029,1,21512356),
(98,10030,1,1010),
(99,10030,1,1212),
(100,10030,1,1234),
(101,10031,1,21512356),
(102,10031,1,"214u1"),
(103,10032,1,21512356),
(104,10032,1,"214u1"),
(105,10032,1,214123),
(106,10032,1,2141),
(107,10033,1,21512356),
(108,10033,1,214123),
(109,10034,1,1212),
(110,10034,1,1235);


DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `factura` int(11) NOT NULL AUTO_INCREMENT,
  `rif_empresa` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `vendedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `total` float NOT NULL,
  `pago` float NOT NULL,
  `impuesto` int(11) NOT NULL DEFAULT '16',
  `metodo_pago` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cliente` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `rif_empresa` (`rif_empresa`),
  KEY `cliente` (`cliente`),
  KEY `vendedor` (`vendedor`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`rif_empresa`) REFERENCES `empresa` (`rif`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10035 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `ventas` VALUES (10001,"j-1234087",23194809,"120.18",100,16,"Transferencia","2024-11-02 03:16:58",12393100),
(10002,"j-1234087",23194809,"446.37",500,16,"Targeta","2024-11-02 03:19:36",12393100),
(10003,"j-1234087",23194809,"240.35",185,16,"Pago movil","2024-11-02 03:20:15",12400500),
(10004,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:19:32",12393100),
(10005,"j-1234087",23194809,"763.98","763.98",16,"Efectivo","2024-11-06 04:20:20",12393100),
(10006,"j-1234087",23194809,4,4,16,"Efectivo","2024-11-06 04:21:59",12400500),
(10007,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:22:38",12393100),
(10008,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:23:16",12393100),
(10009,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:24:39",12393100),
(10010,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:25:03",12400500),
(10011,"j-1234087",23194809,"120.18","120.18",16,"Efectivo","2024-11-06 04:25:28",12393100),
(10012,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:26:06",12400500),
(10013,"j-1234087",23194809,"120.18","120.18",16,"Efectivo","2024-11-06 04:26:39",12393100),
(10014,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:27:11",12400500),
(10015,"j-1234087",23194809,"901.32","901.32",16,"Targeta","2024-11-06 04:27:43",12393100),
(10016,"j-1234087",23194809,"763.98","763.98",16,"Efectivo","2024-11-06 04:28:22",12393100),
(10017,"j-1234087",23194809,2,2,16,"Efectivo","2024-11-06 04:30:15",12393100),
(10018,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:31:06",12393100),
(10019,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:31:54",12393100),
(10020,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:34:03",12393100),
(10021,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:34:37",12400500),
(10022,"j-1234087",23194809,"42.92","42.92",16,"Efectivo","2024-11-06 04:35:40",12400500),
(10023,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:37:53",908712984),
(10024,"j-1234087",23194809,"85.84","85.84",16,"Efectivo","2024-11-06 04:38:41",31982314),
(10025,"j-1234087",23194809,2,2,16,"Efectivo","2024-11-06 04:39:43",12393100),
(10026,"j-1234087",23194809,"763.98","763.98",16,"Efectivo","2024-11-06 04:41:06",12393100),
(10027,"j-1234087",23194809,"120.18","120.18",16,"Efectivo","2024-11-06 05:06:36",12393100),
(10028,"j-1234087",23194809,"42.92","42.92",16,"Efectivo","2024-11-06 05:50:08",12400500),
(10029,"j-1234087",23194809,"47.56","47.56",16,"Efectivo","2024-11-06 07:10:00",12393100),
(10030,"j-1234087",23194809,"599.26","599.26",16,"Efectivo","2024-11-06 07:13:11",12400500),
(10031,"j-1234087",23194809,"846.57","846.57",16,"Targeta","2012-11-01 00:05:57",12393100),
(10032,"j-1234087",23194809,2,2,16,"Pago movil","2012-11-01 00:07:12",12393100),
(10033,"j-1234087",40123456,"846.57","846.57",16,"Efectivo","2012-11-01 00:11:48",259103825),
(10034,"j-1234087",23194809,1,1,16,"Transferencia","2012-11-01 00:44:43",259103825);


SET foreign_key_checks = 1;
CREATE DATABASE IF NOT EXISTS `zapateriak`;

USE `zapateriak`;

SET foreign_key_checks = 0;

DROP TABLE IF EXISTS `apartado`;

CREATE TABLE `apartado` (
  `factura` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` int(11) NOT NULL,
  `vendedor` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `abonado` float NOT NULL,
  `impuesto` int(11) NOT NULL,
  `cliente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `vendedor` (`vendedor`,`cliente`),
  KEY `cliente` (`cliente`),
  CONSTRAINT `apartado_ibfk_1` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`),
  CONSTRAINT `apartado_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `apartado_producto`;

CREATE TABLE `apartado_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`,`producto`),
  KEY `producto` (`producto`),
  CONSTRAINT `apartado_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `apartado` (`factura`),
  CONSTRAINT `apartado_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `categorias` VALUES (7,"Ropa","2024-10-20 21:17:02"),
(9,"Calzados","2024-10-20 22:02:09"),
(10,"ACCESORIOS","2024-10-23 01:54:40"),
(12,"COMPONENTES","2024-10-30 01:38:07");


DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `apellido` text COLLATE utf8_spanish_ci NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `clientes` VALUES (12393100,"Ricardos","Bastidas","ricardomartin@gmail.com",04247712112,"Rincon 3"),
(12400500,"Ismael","Martinez","ismale@gmail.com",04127421201,"Tu casa"),
(259103825,"Vanessa","Teran","vane1q@gmail.com",2108974,"La loma"),
(31744101,"Jhoan","Mejia","mejiajhoan2021@gmail.com",04247079098,"Bisnaca"),
(31982314,"Esbeidy","Gil","esbeydui@gmail.com","Miticun",10987235),
(7689758,"Julio","Guanda","vane1q@gmail.com",04247079089,"Miticun"),
(908712984,"Julio","Carrasquero","vane@gmail.com",125123,"Miticun");


DROP TABLE IF EXISTS `colores`;

CREATE TABLE `colores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `colores` VALUES (1,"negro","2024-10-21 05:52:13"),
(10,"blanco","2024-10-21 08:17:13"),
(11,"verde","2024-10-21 08:17:24"),
(12,"beige","2012-11-01 00:08:15"),
(13,"TUTIFRUTTY","2024-10-26 20:34:51");


DROP TABLE IF EXISTS `empleado`;

CREATE TABLE `empleado` (
  `cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `foto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empleado` VALUES (12393320,0432141325,"Francisco","Arias",""),
(12847231,9012754,"Ricardo","Yepez Bastidas",""),
(1290481,0432141325,"DOUGLAS JOSUE","DEL VALLE",""),
(23194809,9102375089,"Isaac","Bastidas",""),
(3174101,172089720,"Jota","Mejia",""),
(31744101,04247079098,"Jhoan","Mejia",""),
(40123456,0409879,"Francisco","Martinez","vistas/img/usuarios//661.jpg");


DROP TABLE IF EXISTS `empresa`;

CREATE TABLE `empresa` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(80) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio_dolar` float NOT NULL,
  `impuesto` float NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empresa` VALUES ("j-1234087","Centro de Bocono","Kamyl Styles y Algo Mas de Susana Alchariti",1385132,"44.05",16);


DROP TABLE IF EXISTS `marcas`;

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marca` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `marcas` VALUES (10,"adidas","2024-10-21 08:18:24"),
(11,"pocholin","2024-10-21 08:18:31"),
(13,"rs21","2024-10-21 08:18:44"),
(14,"Aero","2012-11-01 00:07:27"),
(15,"generica","2012-11-01 00:07:35"),
(16,"acadia","2012-11-01 00:07:46"),
(17,"sifrina","2012-11-01 00:07:59"),
(18,"NIKE","2024-10-25 18:02:27");


DROP TABLE IF EXISTS `productos`;

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
  `precio_venta` float NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `id_tipo` (`id_tipo`),
  KEY `id_categoria` (`id_categoria`,`id_color`,`id_marca`),
  KEY `id_color` (`id_color`),
  KEY `id_marca` (`id_marca`),
  KEY `rif_proveedor` (`rif_proveedor`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_color`) REFERENCES `colores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_4` FOREIGN KEY (`id_tipo`) REFERENCES `tipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_5` FOREIGN KEY (`rif_proveedor`) REFERENCES `proveedores` (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `productos` VALUES (7,5,1,11,122319847,1010,"MANILLA","vistas/img/productos/default/anonymous.png",11,2,"2.8"),
(10,5,1,16,2151351235,1212,"FORZA XL","vistas/img/productos/1212/907.jpg",6,2,"2.8"),
(7,5,1,11,122319847,1234,"camisa","vistas/img/productos/default/anonymous.png",6,5,7),
(7,5,1,11,122319847,1235,"bota","vistas/img/productos/default/anonymous.png",5,15,21),
(10,5,1,11,122319847,12874,"TOLTO","vistas/img/productos/default/anonymous.png",6,12,"16.8"),
(10,5,1,11,122319847,2000,"RELOG","vistas/img/productos/default/anonymous.png",14,2,"2.8"),
(9,5,1,11,122319847,2141,"LKjdakl","vistas/img/productos/2141/351.jpg",9,12,"16.8"),
(9,5,1,11,122319847,214123,1523,"vistas/img/productos/default/anonymous.png",305,12,"16.8"),
(10,5,10,11,122319847,"214u1","BOTAS","vistas/img/productos/default/anonymous.png",3,12,"16.8"),
(10,8,13,18,2151351235,21512356,"COLLARES AESTHETIC","vistas/img/productos/default/anonymous.png",9,2,1);


DROP TABLE IF EXISTS `proveedores`;

CREATE TABLE `proveedores` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cedula_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `correo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `proveedores` VALUES (122319847,"NIKE SPORT","Vanesa","Yepez","Caracas",128948912,0921087,"vane@gmail.com"),
(2151351235,"TOTAL PANDA","Yanderson","Bastidas","Sotchi",1298412,291501235,"yan@gmail.com");


DROP TABLE IF EXISTS `tipos`;

CREATE TABLE `tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `tipos` VALUES (5,"caballero","2012-11-01 00:04:32"),
(6,"niño","2012-11-01 00:04:45"),
(7,"niña","2012-11-01 00:04:52"),
(8,"dama","2012-11-01 00:06:55");


DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `perfil` text COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL,
  PRIMARY KEY (`usuario`),
  KEY `cedula` (`cedula`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `usuarios` VALUES (23194809,"admin","$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG","Administrador",1),
(23194809,"francoarias81","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Administrador",1),
(40123456,"gsus","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Vendedor",1),
(31744101,"jotadevs","$2a$07$asxx54ahjppf45sd87a5auw6I7JoP8iFx7dMSn3sb4CatTnVVTCzi","Vendedor",1),
(12847231,"ricki","$2a$07$asxx54ahjppf45sd87a5auuA/wno05nzDP.bGv2s5tKCJKhZVQQgC","Administrador",1);


DROP TABLE IF EXISTS `venta_producto`;

CREATE TABLE `venta_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`),
  KEY `producto` (`producto`),
  CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `ventas` (`factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `venta_producto` VALUES (38,10001,1,1212),
(39,10002,1,1212),
(40,10002,2,1010),
(41,10002,2,21512356),
(42,10003,2,2000),
(43,10004,1,12874),
(44,10005,1,21512356),
(45,10005,1,"214u1"),
(46,10006,1,21512356),
(47,10006,1,"214u1"),
(48,10006,1,214123),
(49,10006,1,2141),
(50,10006,1,2000),
(51,10006,1,12874),
(52,10006,1,1235),
(53,10006,1,1234),
(54,10006,1,1212),
(55,10006,1,1010),
(56,10007,1,"214u1"),
(57,10008,1,214123),
(58,10009,1,214123),
(59,10010,1,2141),
(60,10011,1,1010),
(61,10012,1,1235),
(62,10012,1,12874),
(63,10012,1,2000),
(64,10013,1,2000),
(65,10014,1,12874),
(66,10015,1,1235),
(67,10016,1,21512356),
(68,10016,1,"214u1"),
(69,10017,1,2141),
(70,10017,1,214123),
(71,10017,1,"214u1"),
(72,10017,1,21512356),
(73,10018,1,21512356),
(74,10018,1,"214u1"),
(75,10018,1,214123),
(76,10019,1,21512356),
(77,10019,1,"214u1"),
(78,10019,1,2141),
(79,10019,1,2000),
(80,10020,1,21512356),
(81,10020,1,"214u1"),
(82,10020,1,214123),
(83,10021,1,214123),
(84,10021,1,2141),
(85,10022,1,21512356),
(86,10023,1,"214u1"),
(87,10024,1,21512356),
(88,10024,1,21512356),
(89,10025,1,21512356),
(90,10025,1,"214u1"),
(91,10025,1,214123),
(92,10025,1,2141),
(93,10026,1,"214u1"),
(94,10026,1,21512356),
(95,10027,1,1212),
(96,10028,1,21512356),
(97,10029,1,21512356),
(98,10030,1,1010),
(99,10030,1,1212),
(100,10030,1,1234),
(101,10031,1,21512356),
(102,10031,1,"214u1"),
(103,10032,1,21512356),
(104,10032,1,"214u1"),
(105,10032,1,214123),
(106,10032,1,2141),
(107,10033,1,21512356),
(108,10033,1,214123),
(109,10034,1,1212),
(110,10034,1,1235);


DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `factura` int(11) NOT NULL AUTO_INCREMENT,
  `rif_empresa` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `vendedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `total` float NOT NULL,
  `pago` float NOT NULL,
  `impuesto` int(11) NOT NULL DEFAULT '16',
  `metodo_pago` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cliente` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `rif_empresa` (`rif_empresa`),
  KEY `cliente` (`cliente`),
  KEY `vendedor` (`vendedor`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`rif_empresa`) REFERENCES `empresa` (`rif`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10035 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `ventas` VALUES (10001,"j-1234087",23194809,"120.18",100,16,"Transferencia","2024-11-02 03:16:58",12393100),
(10002,"j-1234087",23194809,"446.37",500,16,"Targeta","2024-11-02 03:19:36",12393100),
(10003,"j-1234087",23194809,"240.35",185,16,"Pago movil","2024-11-02 03:20:15",12400500),
(10004,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:19:32",12393100),
(10005,"j-1234087",23194809,"763.98","763.98",16,"Efectivo","2024-11-06 04:20:20",12393100),
(10006,"j-1234087",23194809,4,4,16,"Efectivo","2024-11-06 04:21:59",12400500),
(10007,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:22:38",12393100),
(10008,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:23:16",12393100),
(10009,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:24:39",12393100),
(10010,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:25:03",12400500),
(10011,"j-1234087",23194809,"120.18","120.18",16,"Efectivo","2024-11-06 04:25:28",12393100),
(10012,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:26:06",12400500),
(10013,"j-1234087",23194809,"120.18","120.18",16,"Efectivo","2024-11-06 04:26:39",12393100),
(10014,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:27:11",12400500),
(10015,"j-1234087",23194809,"901.32","901.32",16,"Targeta","2024-11-06 04:27:43",12393100),
(10016,"j-1234087",23194809,"763.98","763.98",16,"Efectivo","2024-11-06 04:28:22",12393100),
(10017,"j-1234087",23194809,2,2,16,"Efectivo","2024-11-06 04:30:15",12393100),
(10018,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:31:06",12393100),
(10019,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:31:54",12393100),
(10020,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:34:03",12393100),
(10021,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:34:37",12400500),
(10022,"j-1234087",23194809,"42.92","42.92",16,"Efectivo","2024-11-06 04:35:40",12400500),
(10023,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:37:53",908712984),
(10024,"j-1234087",23194809,"85.84","85.84",16,"Efectivo","2024-11-06 04:38:41",31982314),
(10025,"j-1234087",23194809,2,2,16,"Efectivo","2024-11-06 04:39:43",12393100),
(10026,"j-1234087",23194809,"763.98","763.98",16,"Efectivo","2024-11-06 04:41:06",12393100),
(10027,"j-1234087",23194809,"120.18","120.18",16,"Efectivo","2024-11-06 05:06:36",12393100),
(10028,"j-1234087",23194809,"42.92","42.92",16,"Efectivo","2024-11-06 05:50:08",12400500),
(10029,"j-1234087",23194809,"47.56","47.56",16,"Efectivo","2024-11-06 07:10:00",12393100),
(10030,"j-1234087",23194809,"599.26","599.26",16,"Efectivo","2024-11-06 07:13:11",12400500),
(10031,"j-1234087",23194809,"846.57","846.57",16,"Targeta","2012-11-01 00:05:57",12393100),
(10032,"j-1234087",23194809,2,2,16,"Pago movil","2012-11-01 00:07:12",12393100),
(10033,"j-1234087",40123456,"846.57","846.57",16,"Efectivo","2012-11-01 00:11:48",259103825),
(10034,"j-1234087",23194809,1,1,16,"Transferencia","2012-11-01 00:44:43",259103825);


SET foreign_key_checks = 1;
CREATE DATABASE IF NOT EXISTS `zapateriak`;

USE `zapateriak`;

SET foreign_key_checks = 0;

DROP TABLE IF EXISTS `apartado`;

CREATE TABLE `apartado` (
  `factura` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` int(11) NOT NULL,
  `vendedor` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `abonado` float NOT NULL,
  `impuesto` int(11) NOT NULL,
  `cliente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `vendedor` (`vendedor`,`cliente`),
  KEY `cliente` (`cliente`),
  CONSTRAINT `apartado_ibfk_1` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`),
  CONSTRAINT `apartado_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `apartado_producto`;

CREATE TABLE `apartado_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`,`producto`),
  KEY `producto` (`producto`),
  CONSTRAINT `apartado_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `apartado` (`factura`),
  CONSTRAINT `apartado_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `categorias` VALUES (7,"Ropa","2024-10-20 21:17:02"),
(9,"Calzados","2024-10-20 22:02:09"),
(10,"ACCESORIOS","2024-10-23 01:54:40"),
(12,"COMPONENTES","2024-10-30 01:38:07");


DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `apellido` text COLLATE utf8_spanish_ci NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `clientes` VALUES (12393100,"Ricardos","Bastidas","ricardomartin@gmail.com",04247712112,"Rincon 3"),
(12400500,"Ismael","Martinez","ismale@gmail.com",04127421201,"Tu casa"),
(259103825,"Vanessa","Teran","vane1q@gmail.com",2108974,"La loma"),
(31744101,"Jhoan","Mejia","mejiajhoan2021@gmail.com",04247079098,"Bisnaca"),
(31982314,"Esbeidy","Gil","esbeydui@gmail.com","Miticun",10987235),
(7689758,"Julio","Guanda","vane1q@gmail.com",04247079089,"Miticun"),
(908712984,"Julio","Carrasquero","vane@gmail.com",125123,"Miticun");


DROP TABLE IF EXISTS `colores`;

CREATE TABLE `colores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `colores` VALUES (1,"negro","2024-10-21 05:52:13"),
(10,"blanco","2024-10-21 08:17:13"),
(11,"verde","2024-10-21 08:17:24"),
(12,"beige","2012-11-01 00:08:15"),
(13,"TUTIFRUTTY","2024-10-26 20:34:51");


DROP TABLE IF EXISTS `empleado`;

CREATE TABLE `empleado` (
  `cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `foto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empleado` VALUES (12393320,0432141325,"Francisco","Arias",""),
(12847231,9012754,"Ricardo","Yepez Bastidas",""),
(1290481,0432141325,"DOUGLAS JOSUE","DEL VALLE",""),
(23194809,9102375089,"Isaac","Bastidas",""),
(3174101,172089720,"Jota","Mejia",""),
(31744101,04247079098,"Jhoan","Mejia",""),
(40123456,0409879,"Francisco","Martinez","vistas/img/usuarios//661.jpg");


DROP TABLE IF EXISTS `empresa`;

CREATE TABLE `empresa` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(80) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio_dolar` float NOT NULL,
  `impuesto` float NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empresa` VALUES ("j-1234087","Centro de Bocono","Kamyl Styles y Algo Mas de Susana Alchariti",1385132,"44.05",16);


DROP TABLE IF EXISTS `marcas`;

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marca` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `marcas` VALUES (10,"adidas","2024-10-21 08:18:24"),
(11,"pocholin","2024-10-21 08:18:31"),
(13,"rs21","2024-10-21 08:18:44"),
(14,"Aero","2012-11-01 00:07:27"),
(15,"generica","2012-11-01 00:07:35"),
(16,"acadia","2012-11-01 00:07:46"),
(17,"sifrina","2012-11-01 00:07:59"),
(18,"NIKE","2024-10-25 18:02:27");


DROP TABLE IF EXISTS `productos`;

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
  `precio_venta` float NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `id_tipo` (`id_tipo`),
  KEY `id_categoria` (`id_categoria`,`id_color`,`id_marca`),
  KEY `id_color` (`id_color`),
  KEY `id_marca` (`id_marca`),
  KEY `rif_proveedor` (`rif_proveedor`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_color`) REFERENCES `colores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_4` FOREIGN KEY (`id_tipo`) REFERENCES `tipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_5` FOREIGN KEY (`rif_proveedor`) REFERENCES `proveedores` (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `productos` VALUES (7,5,1,11,122319847,1010,"MANILLA","vistas/img/productos/default/anonymous.png",11,2,"2.8"),
(10,5,1,16,2151351235,1212,"FORZA XL","vistas/img/productos/1212/907.jpg",6,2,"2.8"),
(7,5,1,11,122319847,1234,"camisa","vistas/img/productos/default/anonymous.png",6,5,7),
(7,5,1,11,122319847,1235,"bota","vistas/img/productos/default/anonymous.png",5,15,21),
(10,5,1,11,122319847,12874,"TOLTO","vistas/img/productos/default/anonymous.png",6,12,"16.8"),
(10,5,1,11,122319847,2000,"RELOG","vistas/img/productos/default/anonymous.png",14,2,"2.8"),
(9,5,1,11,122319847,2141,"LKjdakl","vistas/img/productos/2141/351.jpg",9,12,"16.8"),
(9,5,1,11,122319847,214123,1523,"vistas/img/productos/default/anonymous.png",305,12,"16.8"),
(10,5,10,11,122319847,"214u1","BOTAS","vistas/img/productos/default/anonymous.png",3,12,"16.8"),
(10,8,13,18,2151351235,21512356,"COLLARES AESTHETIC","vistas/img/productos/default/anonymous.png",9,2,1);


DROP TABLE IF EXISTS `proveedores`;

CREATE TABLE `proveedores` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cedula_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `correo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `proveedores` VALUES (122319847,"NIKE SPORT","Vanesa","Yepez","Caracas",128948912,0921087,"vane@gmail.com"),
(2151351235,"TOTAL PANDA","Yanderson","Bastidas","Sotchi",1298412,291501235,"yan@gmail.com");


DROP TABLE IF EXISTS `tipos`;

CREATE TABLE `tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `tipos` VALUES (5,"caballero","2012-11-01 00:04:32"),
(6,"niño","2012-11-01 00:04:45"),
(7,"niña","2012-11-01 00:04:52"),
(8,"dama","2012-11-01 00:06:55");


DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `perfil` text COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL,
  PRIMARY KEY (`usuario`),
  KEY `cedula` (`cedula`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `usuarios` VALUES (23194809,"admin","$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG","Administrador",1),
(23194809,"francoarias81","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Administrador",1),
(40123456,"gsus","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Vendedor",1),
(31744101,"jotadevs","$2a$07$asxx54ahjppf45sd87a5auw6I7JoP8iFx7dMSn3sb4CatTnVVTCzi","Vendedor",1),
(12847231,"ricki","$2a$07$asxx54ahjppf45sd87a5auuA/wno05nzDP.bGv2s5tKCJKhZVQQgC","Administrador",1);


DROP TABLE IF EXISTS `venta_producto`;

CREATE TABLE `venta_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`),
  KEY `producto` (`producto`),
  CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `ventas` (`factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `venta_producto` VALUES (38,10001,1,1212),
(39,10002,1,1212),
(40,10002,2,1010),
(41,10002,2,21512356),
(42,10003,2,2000),
(43,10004,1,12874),
(44,10005,1,21512356),
(45,10005,1,"214u1"),
(46,10006,1,21512356),
(47,10006,1,"214u1"),
(48,10006,1,214123),
(49,10006,1,2141),
(50,10006,1,2000),
(51,10006,1,12874),
(52,10006,1,1235),
(53,10006,1,1234),
(54,10006,1,1212),
(55,10006,1,1010),
(56,10007,1,"214u1"),
(57,10008,1,214123),
(58,10009,1,214123),
(59,10010,1,2141),
(60,10011,1,1010),
(61,10012,1,1235),
(62,10012,1,12874),
(63,10012,1,2000),
(64,10013,1,2000),
(65,10014,1,12874),
(66,10015,1,1235),
(67,10016,1,21512356),
(68,10016,1,"214u1"),
(69,10017,1,2141),
(70,10017,1,214123),
(71,10017,1,"214u1"),
(72,10017,1,21512356),
(73,10018,1,21512356),
(74,10018,1,"214u1"),
(75,10018,1,214123),
(76,10019,1,21512356),
(77,10019,1,"214u1"),
(78,10019,1,2141),
(79,10019,1,2000),
(80,10020,1,21512356),
(81,10020,1,"214u1"),
(82,10020,1,214123),
(83,10021,1,214123),
(84,10021,1,2141),
(85,10022,1,21512356),
(86,10023,1,"214u1"),
(87,10024,1,21512356),
(88,10024,1,21512356),
(89,10025,1,21512356),
(90,10025,1,"214u1"),
(91,10025,1,214123),
(92,10025,1,2141),
(93,10026,1,"214u1"),
(94,10026,1,21512356),
(95,10027,1,1212),
(96,10028,1,21512356),
(97,10029,1,21512356),
(98,10030,1,1010),
(99,10030,1,1212),
(100,10030,1,1234),
(101,10031,1,21512356),
(102,10031,1,"214u1"),
(103,10032,1,21512356),
(104,10032,1,"214u1"),
(105,10032,1,214123),
(106,10032,1,2141),
(107,10033,1,21512356),
(108,10033,1,214123),
(109,10034,1,1212),
(110,10034,1,1235);


DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `factura` int(11) NOT NULL AUTO_INCREMENT,
  `rif_empresa` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `vendedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `total` float NOT NULL,
  `pago` float NOT NULL,
  `impuesto` int(11) NOT NULL DEFAULT '16',
  `metodo_pago` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cliente` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `rif_empresa` (`rif_empresa`),
  KEY `cliente` (`cliente`),
  KEY `vendedor` (`vendedor`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`rif_empresa`) REFERENCES `empresa` (`rif`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10035 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `ventas` VALUES (10001,"j-1234087",23194809,"120.18",100,16,"Transferencia","2024-11-02 03:16:58",12393100),
(10002,"j-1234087",23194809,"446.37",500,16,"Targeta","2024-11-02 03:19:36",12393100),
(10003,"j-1234087",23194809,"240.35",185,16,"Pago movil","2024-11-02 03:20:15",12400500),
(10004,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:19:32",12393100),
(10005,"j-1234087",23194809,"763.98","763.98",16,"Efectivo","2024-11-06 04:20:20",12393100),
(10006,"j-1234087",23194809,4,4,16,"Efectivo","2024-11-06 04:21:59",12400500),
(10007,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:22:38",12393100),
(10008,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:23:16",12393100),
(10009,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:24:39",12393100),
(10010,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:25:03",12400500),
(10011,"j-1234087",23194809,"120.18","120.18",16,"Efectivo","2024-11-06 04:25:28",12393100),
(10012,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:26:06",12400500),
(10013,"j-1234087",23194809,"120.18","120.18",16,"Efectivo","2024-11-06 04:26:39",12393100),
(10014,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:27:11",12400500),
(10015,"j-1234087",23194809,"901.32","901.32",16,"Targeta","2024-11-06 04:27:43",12393100),
(10016,"j-1234087",23194809,"763.98","763.98",16,"Efectivo","2024-11-06 04:28:22",12393100),
(10017,"j-1234087",23194809,2,2,16,"Efectivo","2024-11-06 04:30:15",12393100),
(10018,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:31:06",12393100),
(10019,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:31:54",12393100),
(10020,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:34:03",12393100),
(10021,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:34:37",12400500),
(10022,"j-1234087",23194809,"42.92","42.92",16,"Efectivo","2024-11-06 04:35:40",12400500),
(10023,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:37:53",908712984),
(10024,"j-1234087",23194809,"85.84","85.84",16,"Efectivo","2024-11-06 04:38:41",31982314),
(10025,"j-1234087",23194809,2,2,16,"Efectivo","2024-11-06 04:39:43",12393100),
(10026,"j-1234087",23194809,"763.98","763.98",16,"Efectivo","2024-11-06 04:41:06",12393100),
(10027,"j-1234087",23194809,"120.18","120.18",16,"Efectivo","2024-11-06 05:06:36",12393100),
(10028,"j-1234087",23194809,"42.92","42.92",16,"Efectivo","2024-11-06 05:50:08",12400500),
(10029,"j-1234087",23194809,"47.56","47.56",16,"Efectivo","2024-11-06 07:10:00",12393100),
(10030,"j-1234087",23194809,"599.26","599.26",16,"Efectivo","2024-11-06 07:13:11",12400500),
(10031,"j-1234087",23194809,"846.57","846.57",16,"Targeta","2012-11-01 00:05:57",12393100),
(10032,"j-1234087",23194809,2,2,16,"Pago movil","2012-11-01 00:07:12",12393100),
(10033,"j-1234087",40123456,"846.57","846.57",16,"Efectivo","2012-11-01 00:11:48",259103825),
(10034,"j-1234087",23194809,1,1,16,"Transferencia","2012-11-01 00:44:43",259103825);


SET foreign_key_checks = 1;
CREATE DATABASE IF NOT EXISTS `zapateriak`;

USE `zapateriak`;

SET foreign_key_checks = 0;

DROP TABLE IF EXISTS `apartado`;

CREATE TABLE `apartado` (
  `factura` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` int(11) NOT NULL,
  `vendedor` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `abonado` float NOT NULL,
  `impuesto` int(11) NOT NULL,
  `cliente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `vendedor` (`vendedor`,`cliente`),
  KEY `cliente` (`cliente`),
  CONSTRAINT `apartado_ibfk_1` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`),
  CONSTRAINT `apartado_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `apartado_producto`;

CREATE TABLE `apartado_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`,`producto`),
  KEY `producto` (`producto`),
  CONSTRAINT `apartado_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `apartado` (`factura`),
  CONSTRAINT `apartado_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `categorias` VALUES (7,"Ropa","2024-10-20 21:17:02"),
(9,"Calzados","2024-10-20 22:02:09"),
(10,"ACCESORIOS","2024-10-23 01:54:40"),
(12,"COMPONENTES","2024-10-30 01:38:07");


DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `apellido` text COLLATE utf8_spanish_ci NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `clientes` VALUES (12393100,"Ricardos","Bastidas","ricardomartin@gmail.com",04247712112,"Rincon 3"),
(12400500,"Ismael","Martinez","ismale@gmail.com",04127421201,"Tu casa"),
(259103825,"Vanessa","Teran","vane1q@gmail.com",2108974,"La loma"),
(31744101,"Jhoan","Mejia","mejiajhoan2021@gmail.com",04247079098,"Bisnaca"),
(31982314,"Esbeidy","Gil","esbeydui@gmail.com","Miticun",10987235),
(7689758,"Julio","Guanda","vane1q@gmail.com",04247079089,"Miticun"),
(908712984,"Julio","Carrasquero","vane@gmail.com",125123,"Miticun");


DROP TABLE IF EXISTS `colores`;

CREATE TABLE `colores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `colores` VALUES (1,"negro","2024-10-21 05:52:13"),
(10,"blanco","2024-10-21 08:17:13"),
(11,"verde","2024-10-21 08:17:24"),
(12,"beige","2012-11-01 00:08:15"),
(13,"TUTIFRUTTY","2024-10-26 20:34:51");


DROP TABLE IF EXISTS `empleado`;

CREATE TABLE `empleado` (
  `cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `foto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empleado` VALUES (12393320,0432141325,"Francisco","Arias",""),
(12847231,9012754,"Ricardo","Yepez Bastidas",""),
(1290481,0432141325,"DOUGLAS JOSUE","DEL VALLE",""),
(23194809,9102375089,"Isaac","Bastidas",""),
(3174101,172089720,"Jota","Mejia",""),
(31744101,04247079098,"Jhoan","Mejia",""),
(40123456,0409879,"Francisco","Martinez","vistas/img/usuarios//661.jpg");


DROP TABLE IF EXISTS `empresa`;

CREATE TABLE `empresa` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(80) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio_dolar` float NOT NULL,
  `impuesto` float NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empresa` VALUES ("j-1234087","Centro de Bocono","Kamyl Styles y Algo Mas de Susana Alchariti",1385132,"44.05",16);


DROP TABLE IF EXISTS `marcas`;

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marca` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `marcas` VALUES (10,"adidas","2024-10-21 08:18:24"),
(11,"pocholin","2024-10-21 08:18:31"),
(13,"rs21","2024-10-21 08:18:44"),
(14,"Aero","2012-11-01 00:07:27"),
(15,"generica","2012-11-01 00:07:35"),
(16,"acadia","2012-11-01 00:07:46"),
(17,"sifrina","2012-11-01 00:07:59"),
(18,"NIKE","2024-10-25 18:02:27");


DROP TABLE IF EXISTS `productos`;

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
  `precio_venta` float NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `id_tipo` (`id_tipo`),
  KEY `id_categoria` (`id_categoria`,`id_color`,`id_marca`),
  KEY `id_color` (`id_color`),
  KEY `id_marca` (`id_marca`),
  KEY `rif_proveedor` (`rif_proveedor`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_color`) REFERENCES `colores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_4` FOREIGN KEY (`id_tipo`) REFERENCES `tipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_5` FOREIGN KEY (`rif_proveedor`) REFERENCES `proveedores` (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `productos` VALUES (7,5,1,11,122319847,1010,"MANILLA","vistas/img/productos/default/anonymous.png",11,2,"2.8"),
(10,5,1,16,2151351235,1212,"FORZA XL","vistas/img/productos/1212/907.jpg",6,2,"2.8"),
(7,5,1,11,122319847,1234,"camisa","vistas/img/productos/default/anonymous.png",6,5,7),
(7,5,1,11,122319847,1235,"bota","vistas/img/productos/default/anonymous.png",5,15,21),
(10,5,1,11,122319847,12874,"TOLTO","vistas/img/productos/default/anonymous.png",6,12,"16.8"),
(10,5,1,11,122319847,2000,"RELOG","vistas/img/productos/default/anonymous.png",14,2,"2.8"),
(9,5,1,11,122319847,2141,"LKjdakl","vistas/img/productos/2141/351.jpg",9,12,"16.8"),
(9,5,1,11,122319847,214123,1523,"vistas/img/productos/default/anonymous.png",305,12,"16.8"),
(10,5,10,11,122319847,"214u1","BOTAS","vistas/img/productos/default/anonymous.png",3,12,"16.8"),
(10,8,13,18,2151351235,21512356,"COLLARES AESTHETIC","vistas/img/productos/default/anonymous.png",9,2,1);


DROP TABLE IF EXISTS `proveedores`;

CREATE TABLE `proveedores` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cedula_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `correo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `proveedores` VALUES (122319847,"NIKE SPORT","Vanesa","Yepez","Caracas",128948912,0921087,"vane@gmail.com"),
(2151351235,"TOTAL PANDA","Yanderson","Bastidas","Sotchi",1298412,291501235,"yan@gmail.com");


DROP TABLE IF EXISTS `tipos`;

CREATE TABLE `tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `tipos` VALUES (5,"caballero","2012-11-01 00:04:32"),
(6,"niño","2012-11-01 00:04:45"),
(7,"niña","2012-11-01 00:04:52"),
(8,"dama","2012-11-01 00:06:55");


DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `perfil` text COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL,
  PRIMARY KEY (`usuario`),
  KEY `cedula` (`cedula`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `usuarios` VALUES (23194809,"admin","$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG","Administrador",1),
(23194809,"francoarias81","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Administrador",1),
(40123456,"gsus","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Vendedor",1),
(31744101,"jotadevs","$2a$07$asxx54ahjppf45sd87a5auw6I7JoP8iFx7dMSn3sb4CatTnVVTCzi","Vendedor",1),
(12847231,"ricki","$2a$07$asxx54ahjppf45sd87a5auuA/wno05nzDP.bGv2s5tKCJKhZVQQgC","Administrador",1);


DROP TABLE IF EXISTS `venta_producto`;

CREATE TABLE `venta_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`),
  KEY `producto` (`producto`),
  CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `ventas` (`factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `venta_producto` VALUES (38,10001,1,1212),
(39,10002,1,1212),
(40,10002,2,1010),
(41,10002,2,21512356),
(42,10003,2,2000),
(43,10004,1,12874),
(44,10005,1,21512356),
(45,10005,1,"214u1"),
(46,10006,1,21512356),
(47,10006,1,"214u1"),
(48,10006,1,214123),
(49,10006,1,2141),
(50,10006,1,2000),
(51,10006,1,12874),
(52,10006,1,1235),
(53,10006,1,1234),
(54,10006,1,1212),
(55,10006,1,1010),
(56,10007,1,"214u1"),
(57,10008,1,214123),
(58,10009,1,214123),
(59,10010,1,2141),
(60,10011,1,1010),
(61,10012,1,1235),
(62,10012,1,12874),
(63,10012,1,2000),
(64,10013,1,2000),
(65,10014,1,12874),
(66,10015,1,1235),
(67,10016,1,21512356),
(68,10016,1,"214u1"),
(69,10017,1,2141),
(70,10017,1,214123),
(71,10017,1,"214u1"),
(72,10017,1,21512356),
(73,10018,1,21512356),
(74,10018,1,"214u1"),
(75,10018,1,214123),
(76,10019,1,21512356),
(77,10019,1,"214u1"),
(78,10019,1,2141),
(79,10019,1,2000),
(80,10020,1,21512356),
(81,10020,1,"214u1"),
(82,10020,1,214123),
(83,10021,1,214123),
(84,10021,1,2141),
(85,10022,1,21512356),
(86,10023,1,"214u1"),
(87,10024,1,21512356),
(88,10024,1,21512356),
(89,10025,1,21512356),
(90,10025,1,"214u1"),
(91,10025,1,214123),
(92,10025,1,2141),
(93,10026,1,"214u1"),
(94,10026,1,21512356),
(95,10027,1,1212),
(96,10028,1,21512356),
(97,10029,1,21512356),
(98,10030,1,1010),
(99,10030,1,1212),
(100,10030,1,1234),
(101,10031,1,21512356),
(102,10031,1,"214u1"),
(103,10032,1,21512356),
(104,10032,1,"214u1"),
(105,10032,1,214123),
(106,10032,1,2141),
(107,10033,1,21512356),
(108,10033,1,214123),
(109,10034,1,1212),
(110,10034,1,1235);


DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `factura` int(11) NOT NULL AUTO_INCREMENT,
  `rif_empresa` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `vendedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `total` float NOT NULL,
  `pago` float NOT NULL,
  `impuesto` int(11) NOT NULL DEFAULT '16',
  `metodo_pago` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cliente` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `rif_empresa` (`rif_empresa`),
  KEY `cliente` (`cliente`),
  KEY `vendedor` (`vendedor`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`rif_empresa`) REFERENCES `empresa` (`rif`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10035 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `ventas` VALUES (10001,"j-1234087",23194809,"120.18",100,16,"Transferencia","2024-11-02 03:16:58",12393100),
(10002,"j-1234087",23194809,"446.37",500,16,"Targeta","2024-11-02 03:19:36",12393100),
(10003,"j-1234087",23194809,"240.35",185,16,"Pago movil","2024-11-02 03:20:15",12400500),
(10004,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:19:32",12393100),
(10005,"j-1234087",23194809,"763.98","763.98",16,"Efectivo","2024-11-06 04:20:20",12393100),
(10006,"j-1234087",23194809,4,4,16,"Efectivo","2024-11-06 04:21:59",12400500),
(10007,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:22:38",12393100),
(10008,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:23:16",12393100),
(10009,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:24:39",12393100),
(10010,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:25:03",12400500),
(10011,"j-1234087",23194809,"120.18","120.18",16,"Efectivo","2024-11-06 04:25:28",12393100),
(10012,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:26:06",12400500),
(10013,"j-1234087",23194809,"120.18","120.18",16,"Efectivo","2024-11-06 04:26:39",12393100),
(10014,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:27:11",12400500),
(10015,"j-1234087",23194809,"901.32","901.32",16,"Targeta","2024-11-06 04:27:43",12393100),
(10016,"j-1234087",23194809,"763.98","763.98",16,"Efectivo","2024-11-06 04:28:22",12393100),
(10017,"j-1234087",23194809,2,2,16,"Efectivo","2024-11-06 04:30:15",12393100),
(10018,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:31:06",12393100),
(10019,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:31:54",12393100),
(10020,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:34:03",12393100),
(10021,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:34:37",12400500),
(10022,"j-1234087",23194809,"42.92","42.92",16,"Efectivo","2024-11-06 04:35:40",12400500),
(10023,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:37:53",908712984),
(10024,"j-1234087",23194809,"85.84","85.84",16,"Efectivo","2024-11-06 04:38:41",31982314),
(10025,"j-1234087",23194809,2,2,16,"Efectivo","2024-11-06 04:39:43",12393100),
(10026,"j-1234087",23194809,"763.98","763.98",16,"Efectivo","2024-11-06 04:41:06",12393100),
(10027,"j-1234087",23194809,"120.18","120.18",16,"Efectivo","2024-11-06 05:06:36",12393100),
(10028,"j-1234087",23194809,"42.92","42.92",16,"Efectivo","2024-11-06 05:50:08",12400500),
(10029,"j-1234087",23194809,"47.56","47.56",16,"Efectivo","2024-11-06 07:10:00",12393100),
(10030,"j-1234087",23194809,"599.26","599.26",16,"Efectivo","2024-11-06 07:13:11",12400500),
(10031,"j-1234087",23194809,"846.57","846.57",16,"Targeta","2012-11-01 00:05:57",12393100),
(10032,"j-1234087",23194809,2,2,16,"Pago movil","2012-11-01 00:07:12",12393100),
(10033,"j-1234087",40123456,"846.57","846.57",16,"Efectivo","2012-11-01 00:11:48",259103825),
(10034,"j-1234087",23194809,1,1,16,"Transferencia","2012-11-01 00:44:43",259103825);


SET foreign_key_checks = 1;
CREATE DATABASE IF NOT EXISTS `zapateriak`;

USE `zapateriak`;

SET foreign_key_checks = 0;

DROP TABLE IF EXISTS `apartado`;

CREATE TABLE `apartado` (
  `factura` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` int(11) NOT NULL,
  `vendedor` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `abonado` float NOT NULL,
  `impuesto` int(11) NOT NULL,
  `cliente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `vendedor` (`vendedor`,`cliente`),
  KEY `cliente` (`cliente`),
  CONSTRAINT `apartado_ibfk_1` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`),
  CONSTRAINT `apartado_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `apartado_producto`;

CREATE TABLE `apartado_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`,`producto`),
  KEY `producto` (`producto`),
  CONSTRAINT `apartado_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `apartado` (`factura`),
  CONSTRAINT `apartado_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `categorias` VALUES (7,"Ropa","2024-10-20 21:17:02"),
(9,"Calzados","2024-10-20 22:02:09"),
(10,"ACCESORIOS","2024-10-23 01:54:40"),
(12,"COMPONENTES","2024-10-30 01:38:07");


DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `apellido` text COLLATE utf8_spanish_ci NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `clientes` VALUES (12393100,"Ricardos","Bastidas","ricardomartin@gmail.com",4247712112,"Rincon 3"),
(12400500,"Ismael","Martinez","ismale@gmail.com",4127421201,"Tu casa"),
(259103825,"Vanessa","Teran","vane1q@gmail.com",2108974,"La loma"),
(31744101,"Jhoan","Mejia","mejiajhoan2021@gmail.com",4247079098,"Bisnaca"),
(31982314,"Esbeidy","Gil","esbeydui@gmail.com","Miticun",10987235),
(7689758,"Julio","Guanda","vane1q@gmail.com",4247079089,"Miticun"),
(908712984,"Julio","Carrasquero","vane@gmail.com",125123,"Miticun");


DROP TABLE IF EXISTS `colores`;

CREATE TABLE `colores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `colores` VALUES (1,"negro","2024-10-21 05:52:13"),
(10,"blanco","2024-10-21 08:17:13"),
(11,"verde","2024-10-21 08:17:24"),
(12,"beige","2012-11-01 00:08:15"),
(13,"TUTIFRUTTY","2024-10-26 20:34:51");


DROP TABLE IF EXISTS `empleado`;

CREATE TABLE `empleado` (
  `cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `foto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empleado` VALUES (12393320,432141325,"Francisco","Arias",""),
(12847231,9012754,"Ricardo","Yepez Bastidas",""),
(1290481,432141325,"DOUGLAS JOSUE","DEL VALLE",""),
(23194809,9102375089,"Isaac","Bastidas",""),
(3174101,172089720,"Jota","Mejia",""),
(31744101,4247079098,"Jhoan","Mejia",""),
(40123456,409879,"Francisco","Martinez","vistas/img/usuarios//661.jpg");


DROP TABLE IF EXISTS `empresa`;

CREATE TABLE `empresa` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(80) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio_dolar` float NOT NULL,
  `impuesto` float NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empresa` VALUES ("j-1234087","Centro de Bocono","Kamyl Styles y Algo Mas de Susana Alchariti",1385132,"44.05",16);


DROP TABLE IF EXISTS `marcas`;

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marca` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `marcas` VALUES (10,"adidas","2024-10-21 08:18:24"),
(11,"pocholin","2024-10-21 08:18:31"),
(13,"rs21","2024-10-21 08:18:44"),
(14,"Aero","2012-11-01 00:07:27"),
(15,"generica","2012-11-01 00:07:35"),
(16,"acadia","2012-11-01 00:07:46"),
(17,"sifrina","2012-11-01 00:07:59"),
(18,"NIKE","2024-10-25 18:02:27");


DROP TABLE IF EXISTS `productos`;

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
  `precio_venta` float NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `id_tipo` (`id_tipo`),
  KEY `id_categoria` (`id_categoria`,`id_color`,`id_marca`),
  KEY `id_color` (`id_color`),
  KEY `id_marca` (`id_marca`),
  KEY `rif_proveedor` (`rif_proveedor`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_color`) REFERENCES `colores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_4` FOREIGN KEY (`id_tipo`) REFERENCES `tipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_5` FOREIGN KEY (`rif_proveedor`) REFERENCES `proveedores` (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `productos` VALUES (7,5,1,11,122319847,1010,"MANILLA","vistas/img/productos/default/anonymous.png",11,2,"2.8"),
(10,5,1,16,2151351235,1212,"FORZA XL","vistas/img/productos/1212/907.jpg",6,2,"2.8"),
(7,5,1,11,122319847,1234,"camisa","vistas/img/productos/default/anonymous.png",6,5,7),
(7,5,1,11,122319847,1235,"bota","vistas/img/productos/default/anonymous.png",5,15,21),
(10,5,1,11,122319847,12874,"TOLTO","vistas/img/productos/default/anonymous.png",6,12,"16.8"),
(10,5,1,11,122319847,2000,"RELOG","vistas/img/productos/default/anonymous.png",14,2,"2.8"),
(9,5,1,11,122319847,2141,"LKjdakl","vistas/img/productos/2141/351.jpg",9,12,"16.8"),
(9,5,1,11,122319847,214123,1523,"vistas/img/productos/default/anonymous.png",305,12,"16.8"),
(10,5,10,11,122319847,"214u1","BOTAS","vistas/img/productos/default/anonymous.png",3,12,"16.8"),
(10,8,13,18,2151351235,21512356,"COLLARES AESTHETIC","vistas/img/productos/default/anonymous.png",9,2,1);


DROP TABLE IF EXISTS `proveedores`;

CREATE TABLE `proveedores` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cedula_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `correo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `proveedores` VALUES (122319847,"NIKE SPORT","Vanesa","Yepez","Caracas",128948912,921087,"vane@gmail.com"),
(2151351235,"TOTAL PANDA","Yanderson","Bastidas","Sotchi",1298412,291501235,"yan@gmail.com");


DROP TABLE IF EXISTS `tipos`;

CREATE TABLE `tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `tipos` VALUES (5,"caballero","2012-11-01 00:04:32"),
(6,"niño","2012-11-01 00:04:45"),
(7,"niña","2012-11-01 00:04:52"),
(8,"dama","2012-11-01 00:06:55");


DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `perfil` text COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL,
  PRIMARY KEY (`usuario`),
  KEY `cedula` (`cedula`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `usuarios` VALUES (23194809,"admin","$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG","Administrador",1),
(23194809,"francoarias81","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Administrador",1),
(40123456,"gsus","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Vendedor",1),
(31744101,"jotadevs","$2a$07$asxx54ahjppf45sd87a5auw6I7JoP8iFx7dMSn3sb4CatTnVVTCzi","Vendedor",1),
(12847231,"ricki","$2a$07$asxx54ahjppf45sd87a5auuA/wno05nzDP.bGv2s5tKCJKhZVQQgC","Administrador",1);


DROP TABLE IF EXISTS `venta_producto`;

CREATE TABLE `venta_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`),
  KEY `producto` (`producto`),
  CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `ventas` (`factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `venta_producto` VALUES (38,10001,1,1212),
(39,10002,1,1212),
(40,10002,2,1010),
(41,10002,2,21512356),
(42,10003,2,2000),
(43,10004,1,12874),
(44,10005,1,21512356),
(45,10005,1,"214u1"),
(46,10006,1,21512356),
(47,10006,1,"214u1"),
(48,10006,1,214123),
(49,10006,1,2141),
(50,10006,1,2000),
(51,10006,1,12874),
(52,10006,1,1235),
(53,10006,1,1234),
(54,10006,1,1212),
(55,10006,1,1010),
(56,10007,1,"214u1"),
(57,10008,1,214123),
(58,10009,1,214123),
(59,10010,1,2141),
(60,10011,1,1010),
(61,10012,1,1235),
(62,10012,1,12874),
(63,10012,1,2000),
(64,10013,1,2000),
(65,10014,1,12874),
(66,10015,1,1235),
(67,10016,1,21512356),
(68,10016,1,"214u1"),
(69,10017,1,2141),
(70,10017,1,214123),
(71,10017,1,"214u1"),
(72,10017,1,21512356),
(73,10018,1,21512356),
(74,10018,1,"214u1"),
(75,10018,1,214123),
(76,10019,1,21512356),
(77,10019,1,"214u1"),
(78,10019,1,2141),
(79,10019,1,2000),
(80,10020,1,21512356),
(81,10020,1,"214u1"),
(82,10020,1,214123),
(83,10021,1,214123),
(84,10021,1,2141),
(85,10022,1,21512356),
(86,10023,1,"214u1"),
(87,10024,1,21512356),
(88,10024,1,21512356),
(89,10025,1,21512356),
(90,10025,1,"214u1"),
(91,10025,1,214123),
(92,10025,1,2141),
(93,10026,1,"214u1"),
(94,10026,1,21512356),
(95,10027,1,1212),
(96,10028,1,21512356),
(97,10029,1,21512356),
(98,10030,1,1010),
(99,10030,1,1212),
(100,10030,1,1234),
(101,10031,1,21512356),
(102,10031,1,"214u1"),
(103,10032,1,21512356),
(104,10032,1,"214u1"),
(105,10032,1,214123),
(106,10032,1,2141),
(107,10033,1,21512356),
(108,10033,1,214123),
(109,10034,1,1212),
(110,10034,1,1235);


DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `factura` int(11) NOT NULL AUTO_INCREMENT,
  `rif_empresa` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `vendedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `total` float NOT NULL,
  `pago` float NOT NULL,
  `impuesto` int(11) NOT NULL DEFAULT '16',
  `metodo_pago` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cliente` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `rif_empresa` (`rif_empresa`),
  KEY `cliente` (`cliente`),
  KEY `vendedor` (`vendedor`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`rif_empresa`) REFERENCES `empresa` (`rif`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10035 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `ventas` VALUES (10001,"j-1234087",23194809,"120.18",100,16,"Transferencia","2024-11-02 03:16:58",12393100),
(10002,"j-1234087",23194809,"446.37",500,16,"Targeta","2024-11-02 03:19:36",12393100),
(10003,"j-1234087",23194809,"240.35",185,16,"Pago movil","2024-11-02 03:20:15",12400500),
(10004,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:19:32",12393100),
(10005,"j-1234087",23194809,"763.98","763.98",16,"Efectivo","2024-11-06 04:20:20",12393100),
(10006,"j-1234087",23194809,4,4,16,"Efectivo","2024-11-06 04:21:59",12400500),
(10007,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:22:38",12393100),
(10008,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:23:16",12393100),
(10009,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:24:39",12393100),
(10010,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:25:03",12400500),
(10011,"j-1234087",23194809,"120.18","120.18",16,"Efectivo","2024-11-06 04:25:28",12393100),
(10012,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:26:06",12400500),
(10013,"j-1234087",23194809,"120.18","120.18",16,"Efectivo","2024-11-06 04:26:39",12393100),
(10014,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:27:11",12400500),
(10015,"j-1234087",23194809,"901.32","901.32",16,"Targeta","2024-11-06 04:27:43",12393100),
(10016,"j-1234087",23194809,"763.98","763.98",16,"Efectivo","2024-11-06 04:28:22",12393100),
(10017,"j-1234087",23194809,2,2,16,"Efectivo","2024-11-06 04:30:15",12393100),
(10018,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:31:06",12393100),
(10019,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:31:54",12393100),
(10020,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:34:03",12393100),
(10021,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:34:37",12400500),
(10022,"j-1234087",23194809,"42.92","42.92",16,"Efectivo","2024-11-06 04:35:40",12400500),
(10023,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:37:53",908712984),
(10024,"j-1234087",23194809,"85.84","85.84",16,"Efectivo","2024-11-06 04:38:41",31982314),
(10025,"j-1234087",23194809,2,2,16,"Efectivo","2024-11-06 04:39:43",12393100),
(10026,"j-1234087",23194809,"763.98","763.98",16,"Efectivo","2024-11-06 04:41:06",12393100),
(10027,"j-1234087",23194809,"120.18","120.18",16,"Efectivo","2024-11-06 05:06:36",12393100),
(10028,"j-1234087",23194809,"42.92","42.92",16,"Efectivo","2024-11-06 05:50:08",12400500),
(10029,"j-1234087",23194809,"47.56","47.56",16,"Efectivo","2024-11-06 07:10:00",12393100),
(10030,"j-1234087",23194809,"599.26","599.26",16,"Efectivo","2024-11-06 07:13:11",12400500),
(10031,"j-1234087",23194809,"846.57","846.57",16,"Targeta","2012-11-01 00:05:57",12393100),
(10032,"j-1234087",23194809,2,2,16,"Pago movil","2012-11-01 00:07:12",12393100),
(10033,"j-1234087",40123456,"846.57","846.57",16,"Efectivo","2012-11-01 00:11:48",259103825),
(10034,"j-1234087",23194809,1,1,16,"Transferencia","2012-11-01 00:44:43",259103825);


SET foreign_key_checks = 1;
CREATE DATABASE IF NOT EXISTS `zapateriak`;

USE `zapateriak`;

SET foreign_key_checks = 0;

DROP TABLE IF EXISTS `apartado`;

CREATE TABLE `apartado` (
  `factura` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` int(11) NOT NULL,
  `vendedor` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `abonado` float NOT NULL,
  `impuesto` int(11) NOT NULL,
  `cliente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `vendedor` (`vendedor`,`cliente`),
  KEY `cliente` (`cliente`),
  CONSTRAINT `apartado_ibfk_1` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`),
  CONSTRAINT `apartado_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `apartado_producto`;

CREATE TABLE `apartado_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`,`producto`),
  KEY `producto` (`producto`),
  CONSTRAINT `apartado_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `apartado` (`factura`),
  CONSTRAINT `apartado_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `categorias` VALUES (7,"Ropa","2024-10-20 21:17:02"),
(9,"Calzados","2024-10-20 22:02:09"),
(10,"ACCESORIOS","2024-10-23 01:54:40"),
(12,"COMPONENTES","2024-10-30 01:38:07");


DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `apellido` text COLLATE utf8_spanish_ci NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `clientes` VALUES (12393100,"Ricardos","Bastidas","ricardomartin@gmail.com",4247712112,"Rincon 3"),
(12400500,"Ismael","Martinez","ismale@gmail.com",4127421201,"Tu casa"),
(259103825,"Vanessa","Teran","vane1q@gmail.com",2108974,"La loma"),
(31744101,"Jhoan","Mejia","mejiajhoan2021@gmail.com",4247079098,"Bisnaca"),
(31982314,"Esbeidy","Gil","esbeydui@gmail.com","Miticun",10987235),
(7689758,"Julio","Guanda","vane1q@gmail.com",4247079089,"Miticun"),
(908712984,"Julio","Carrasquero","vane@gmail.com",125123,"Miticun");


DROP TABLE IF EXISTS `colores`;

CREATE TABLE `colores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `colores` VALUES (1,"negro","2024-10-21 05:52:13"),
(10,"blanco","2024-10-21 08:17:13"),
(11,"verde","2024-10-21 08:17:24"),
(12,"beige","2012-11-01 00:08:15"),
(13,"TUTIFRUTTY","2024-10-26 20:34:51");


DROP TABLE IF EXISTS `empleado`;

CREATE TABLE `empleado` (
  `cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `foto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empleado` VALUES (12393320,432141325,"Francisco","Arias",""),
(12847231,9012754,"Ricardo","Yepez Bastidas",""),
(1290481,432141325,"DOUGLAS JOSUE","DEL VALLE",""),
(23194809,9102375089,"Isaac","Bastidas",""),
(3174101,172089720,"Jota","Mejia",""),
(31744101,4247079098,"Jhoan","Mejia",""),
(40123456,409879,"Francisco","Martinez","vistas/img/usuarios//661.jpg");


DROP TABLE IF EXISTS `empresa`;

CREATE TABLE `empresa` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(80) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio_dolar` float NOT NULL,
  `impuesto` float NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empresa` VALUES ("j-1234087","Centro de Bocono","Kamyl Styles y Algo Mas de Susana Alchariti",1385132,"44.05",16);


DROP TABLE IF EXISTS `marcas`;

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marca` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `marcas` VALUES (10,"adidas","2024-10-21 08:18:24"),
(11,"pocholin","2024-10-21 08:18:31"),
(13,"rs21","2024-10-21 08:18:44"),
(14,"Aero","2012-11-01 00:07:27"),
(15,"generica","2012-11-01 00:07:35"),
(16,"acadia","2012-11-01 00:07:46"),
(17,"sifrina","2012-11-01 00:07:59"),
(18,"NIKE","2024-10-25 18:02:27");


DROP TABLE IF EXISTS `productos`;

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
  `precio_venta` float NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `id_tipo` (`id_tipo`),
  KEY `id_categoria` (`id_categoria`,`id_color`,`id_marca`),
  KEY `id_color` (`id_color`),
  KEY `id_marca` (`id_marca`),
  KEY `rif_proveedor` (`rif_proveedor`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_color`) REFERENCES `colores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_4` FOREIGN KEY (`id_tipo`) REFERENCES `tipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_5` FOREIGN KEY (`rif_proveedor`) REFERENCES `proveedores` (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `productos` VALUES (7,5,1,11,122319847,1010,"MANILLA","vistas/img/productos/default/anonymous.png",10,2,"2.8"),
(10,5,1,16,2151351235,1212,"FORZA XL","vistas/img/productos/1212/907.jpg",6,2,"2.8"),
(7,5,1,11,122319847,1234,"camisa","vistas/img/productos/default/anonymous.png",6,5,7),
(7,5,1,11,122319847,1235,"bota","vistas/img/productos/default/anonymous.png",5,15,21),
(10,5,1,11,122319847,12874,"TOLTO","vistas/img/productos/default/anonymous.png",6,12,"16.8"),
(10,5,1,11,122319847,2000,"RELOG","vistas/img/productos/default/anonymous.png",14,2,"2.8"),
(9,5,1,11,122319847,2141,"LKjdakl","vistas/img/productos/2141/351.jpg",7,12,"16.8"),
(9,5,1,11,122319847,214123,1523,"vistas/img/productos/default/anonymous.png",304,12,"16.8"),
(10,5,10,11,122319847,"214u1","BOTAS","vistas/img/productos/default/anonymous.png",1,12,"16.8"),
(10,8,13,18,2151351235,21512356,"COLLARES AESTHETIC","vistas/img/productos/default/anonymous.png",7,2,1);


DROP TABLE IF EXISTS `proveedores`;

CREATE TABLE `proveedores` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cedula_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `correo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `proveedores` VALUES (122319847,"NIKE SPORT","Vanesa","Yepez","Caracas",128948912,921087,"vane@gmail.com"),
(2151351235,"TOTAL PANDA","Yanderson","Bastidas","Sotchi",1298412,291501235,"yan@gmail.com");


DROP TABLE IF EXISTS `tipos`;

CREATE TABLE `tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `tipos` VALUES (5,"caballero","2012-11-01 00:04:32"),
(6,"niño","2012-11-01 00:04:45"),
(7,"niña","2012-11-01 00:04:52"),
(8,"dama","2012-11-01 00:06:55");


DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `perfil` text COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL,
  PRIMARY KEY (`usuario`),
  KEY `cedula` (`cedula`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `usuarios` VALUES (23194809,"admin","$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG","Administrador",1),
(23194809,"francoarias81","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Administrador",1),
(40123456,"gsus","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Vendedor",1),
(31744101,"jotadevs","$2a$07$asxx54ahjppf45sd87a5auw6I7JoP8iFx7dMSn3sb4CatTnVVTCzi","Vendedor",1),
(12847231,"ricki","$2a$07$asxx54ahjppf45sd87a5auuA/wno05nzDP.bGv2s5tKCJKhZVQQgC","Administrador",1);


DROP TABLE IF EXISTS `venta_producto`;

CREATE TABLE `venta_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`),
  KEY `producto` (`producto`),
  CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `ventas` (`factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `venta_producto` VALUES (38,10001,1,1212),
(39,10002,1,1212),
(40,10002,2,1010),
(41,10002,2,21512356),
(42,10003,2,2000),
(43,10004,1,12874),
(44,10005,1,21512356),
(45,10005,1,"214u1"),
(46,10006,1,21512356),
(47,10006,1,"214u1"),
(48,10006,1,214123),
(49,10006,1,2141),
(50,10006,1,2000),
(51,10006,1,12874),
(52,10006,1,1235),
(53,10006,1,1234),
(54,10006,1,1212),
(55,10006,1,1010),
(56,10007,1,"214u1"),
(57,10008,1,214123),
(58,10009,1,214123),
(59,10010,1,2141),
(60,10011,1,1010),
(61,10012,1,1235),
(62,10012,1,12874),
(63,10012,1,2000),
(64,10013,1,2000),
(65,10014,1,12874),
(66,10015,1,1235),
(67,10016,1,21512356),
(68,10016,1,"214u1"),
(69,10017,1,2141),
(70,10017,1,214123),
(71,10017,1,"214u1"),
(72,10017,1,21512356),
(73,10018,1,21512356),
(74,10018,1,"214u1"),
(75,10018,1,214123),
(76,10019,1,21512356),
(77,10019,1,"214u1"),
(78,10019,1,2141),
(79,10019,1,2000),
(80,10020,1,21512356),
(81,10020,1,"214u1"),
(82,10020,1,214123),
(83,10021,1,214123),
(84,10021,1,2141),
(85,10022,1,21512356),
(86,10023,1,"214u1"),
(87,10024,1,21512356),
(88,10024,1,21512356),
(89,10025,1,21512356),
(90,10025,1,"214u1"),
(91,10025,1,214123),
(92,10025,1,2141),
(93,10026,1,"214u1"),
(94,10026,1,21512356),
(95,10027,1,1212),
(96,10028,1,21512356),
(97,10029,1,21512356),
(98,10030,1,1010),
(99,10030,1,1212),
(100,10030,1,1234),
(101,10031,1,21512356),
(102,10031,1,"214u1"),
(103,10032,1,21512356),
(104,10032,1,"214u1"),
(105,10032,1,214123),
(106,10032,1,2141),
(107,10033,1,21512356),
(108,10033,1,214123),
(109,10034,1,1212),
(110,10034,1,1235),
(111,10035,1,21512356),
(112,10035,1,"214u1"),
(113,10035,1,2141),
(114,10035,1,1010),
(115,10036,1,21512356),
(116,10036,1,"214u1"),
(117,10036,1,214123),
(118,10036,1,2141);


DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `factura` int(11) NOT NULL AUTO_INCREMENT,
  `rif_empresa` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `vendedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `total` float NOT NULL,
  `pago` float NOT NULL,
  `impuesto` int(11) NOT NULL DEFAULT '16',
  `metodo_pago` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cliente` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `rif_empresa` (`rif_empresa`),
  KEY `cliente` (`cliente`),
  KEY `vendedor` (`vendedor`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`rif_empresa`) REFERENCES `empresa` (`rif`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10037 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `ventas` VALUES (10001,"j-1234087",23194809,"120.18",100,16,"Transferencia","2024-11-02 03:16:58",12393100),
(10002,"j-1234087",23194809,"446.37",500,16,"Targeta","2024-11-02 03:19:36",12393100),
(10003,"j-1234087",23194809,"240.35",185,16,"Pago movil","2024-11-02 03:20:15",12400500),
(10004,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:19:32",12393100),
(10005,"j-1234087",23194809,"763.98","763.98",16,"Efectivo","2024-11-06 04:20:20",12393100),
(10006,"j-1234087",23194809,4,4,16,"Efectivo","2024-11-06 04:21:59",12400500),
(10007,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:22:38",12393100),
(10008,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:23:16",12393100),
(10009,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:24:39",12393100),
(10010,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:25:03",12400500),
(10011,"j-1234087",23194809,"120.18","120.18",16,"Efectivo","2024-11-06 04:25:28",12393100),
(10012,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:26:06",12400500),
(10013,"j-1234087",23194809,"120.18","120.18",16,"Efectivo","2024-11-06 04:26:39",12393100),
(10014,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:27:11",12400500),
(10015,"j-1234087",23194809,"901.32","901.32",16,"Targeta","2024-11-06 04:27:43",12393100),
(10016,"j-1234087",23194809,"763.98","763.98",16,"Efectivo","2024-11-06 04:28:22",12393100),
(10017,"j-1234087",23194809,2,2,16,"Efectivo","2024-11-06 04:30:15",12393100),
(10018,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:31:06",12393100),
(10019,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:31:54",12393100),
(10020,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:34:03",12393100),
(10021,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:34:37",12400500),
(10022,"j-1234087",23194809,"42.92","42.92",16,"Efectivo","2024-11-06 04:35:40",12400500),
(10023,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:37:53",908712984),
(10024,"j-1234087",23194809,"85.84","85.84",16,"Efectivo","2024-11-06 04:38:41",31982314),
(10025,"j-1234087",23194809,2,2,16,"Efectivo","2024-11-06 04:39:43",12393100),
(10026,"j-1234087",23194809,"763.98","763.98",16,"Efectivo","2024-11-06 04:41:06",12393100),
(10027,"j-1234087",23194809,"120.18","120.18",16,"Efectivo","2024-11-06 05:06:36",12393100),
(10028,"j-1234087",23194809,"42.92","42.92",16,"Efectivo","2024-11-06 05:50:08",12400500),
(10029,"j-1234087",23194809,"47.56","47.56",16,"Efectivo","2024-11-06 07:10:00",12393100),
(10030,"j-1234087",23194809,"599.26","599.26",16,"Efectivo","2024-11-06 07:13:11",12400500),
(10031,"j-1234087",23194809,"846.57","846.57",16,"Targeta","2012-11-01 00:05:57",12393100),
(10032,"j-1234087",23194809,2,2,16,"Pago movil","2012-11-01 00:07:12",12393100),
(10033,"j-1234087",40123456,"846.57","846.57",16,"Efectivo","2012-11-01 00:11:48",259103825),
(10034,"j-1234087",23194809,1,1,16,"Transferencia","2012-11-01 00:44:43",259103825),
(10035,"j-1234087",23194809,1,0,16,"Efectivo","2024-11-07 04:26:38",12393100),
(10036,"j-1234087",23194809,2,0,16,"Efectivo","2024-11-07 04:28:23",12393100);


SET foreign_key_checks = 1;
CREATE DATABASE IF NOT EXISTS `zapateriak`;

USE `zapateriak`;

SET foreign_key_checks = 0;

DROP TABLE IF EXISTS `apartado`;

CREATE TABLE `apartado` (
  `factura` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` int(11) NOT NULL,
  `vendedor` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `abonado` float NOT NULL,
  `impuesto` int(11) NOT NULL,
  `cliente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `vendedor` (`vendedor`,`cliente`),
  KEY `cliente` (`cliente`),
  CONSTRAINT `apartado_ibfk_1` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`),
  CONSTRAINT `apartado_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `apartado_producto`;

CREATE TABLE `apartado_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`,`producto`),
  KEY `producto` (`producto`),
  CONSTRAINT `apartado_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `apartado` (`factura`),
  CONSTRAINT `apartado_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `categorias` VALUES (7,"Ropa","2024-10-20 21:17:02"),
(9,"Calzados","2024-10-20 22:02:09"),
(10,"ACCESORIOS","2024-10-23 01:54:40"),
(12,"COMPONENTES","2024-10-30 01:38:07");


DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `apellido` text COLLATE utf8_spanish_ci NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `clientes` VALUES (12400500,"Ismael","Martinez","ismale@gmail.com",4127421201,"Tu casa"),
(259103825,"Vanessa","Teran","vane1q@gmail.com",2108974,"La loma"),
(31744101,"Jhoan","Mejia","mejiajhoan2021@gmail.com",4247079098,"Bisnaca"),
(31982314,"Esbeidy","Gil","esbeydui@gmail.com","Miticun",10987235),
(7689758,"Julio","Guanda","vane1q@gmail.com",4247079089,"Miticun"),
(908712984,"Julio","Carrasquero","vane@gmail.com",125123,"Miticun");


DROP TABLE IF EXISTS `colores`;

CREATE TABLE `colores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `colores` VALUES (1,"negro","2024-10-21 05:52:13"),
(10,"blanco","2024-10-21 08:17:13"),
(11,"verde","2024-10-21 08:17:24"),
(12,"beige","2012-11-01 00:08:15"),
(13,"TUTIFRUTTY","2024-10-26 20:34:51");


DROP TABLE IF EXISTS `empleado`;

CREATE TABLE `empleado` (
  `cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `foto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empleado` VALUES (12393320,432141325,"Francisco","Arias",""),
(12847231,9012754,"Ricardo","Yepez Bastidas",""),
(1290481,432141325,"DOUGLAS JOSUE","DEL VALLE",""),
(23194809,9102375089,"Isaac","Bastidas",""),
(3174101,172089720,"Jota","Mejia",""),
(31744101,4247079098,"Jhoan","Mejia",""),
(40123456,409879,"Francisco","Martinez","vistas/img/usuarios//661.jpg");


DROP TABLE IF EXISTS `empresa`;

CREATE TABLE `empresa` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio_dolar` float NOT NULL,
  `impuesto` float NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empresa` VALUES ("j-1234087","Centro de Bocono","Kamil Styles",1385132,37,16);


DROP TABLE IF EXISTS `marcas`;

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marca` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `marcas` VALUES (10,"adidas","2024-10-21 08:18:24"),
(11,"pocholin","2024-10-21 08:18:31"),
(13,"rs21","2024-10-21 08:18:44"),
(14,"Aero","2012-11-01 00:07:27"),
(15,"generica","2012-11-01 00:07:35"),
(16,"acadia","2012-11-01 00:07:46"),
(17,"sifrina","2012-11-01 00:07:59"),
(18,"NIKE","2024-10-25 18:02:27");


DROP TABLE IF EXISTS `productos`;

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
  `precio_venta` float NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `id_tipo` (`id_tipo`),
  KEY `id_categoria` (`id_categoria`,`id_color`,`id_marca`),
  KEY `id_color` (`id_color`),
  KEY `id_marca` (`id_marca`),
  KEY `rif_proveedor` (`rif_proveedor`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_color`) REFERENCES `colores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_4` FOREIGN KEY (`id_tipo`) REFERENCES `tipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_5` FOREIGN KEY (`rif_proveedor`) REFERENCES `proveedores` (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `productos` VALUES (7,5,1,11,122319847,1010,"MANILLA","vistas/img/productos/default/anonymous.png",14,2,"2.8"),
(10,5,1,16,2151351235,1212,"FORZA XL","vistas/img/productos/1212/907.jpg",10,2,"2.8"),
(7,5,1,11,122319847,1234,"camisa","vistas/img/productos/default/anonymous.png",8,5,7),
(7,5,1,11,122319847,1235,"bota","vistas/img/productos/default/anonymous.png",9,15,21),
(10,5,1,11,122319847,12874,"TOLTO","vistas/img/productos/default/anonymous.png",10,12,"16.8"),
(10,5,1,11,122319847,2000,"RELOG","vistas/img/productos/default/anonymous.png",17,2,"2.8"),
(9,5,1,11,122319847,2141,"LKjdakl","vistas/img/productos/2141/351.jpg",13,12,"16.8"),
(9,5,1,11,122319847,214123,1523,"vistas/img/productos/default/anonymous.png",307,12,"16.8"),
(10,5,10,11,122319847,"214u1","BOTAS","vistas/img/productos/default/anonymous.png",9,12,"16.8"),
(10,8,13,18,2151351235,21512356,"COLLARES AESTHETIC","vistas/img/productos/default/anonymous.png",18,"2.55","3.57");


DROP TABLE IF EXISTS `proveedores`;

CREATE TABLE `proveedores` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cedula_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `correo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `proveedores` VALUES (122319847,"NIKE SPORT","Vanesa","Yepez","Caracas",128948912,921087,"vane@gmail.com"),
(2151351235,"TOTAL PANDA","Yanderson","Bastidas","Sotchi",1298412,291501235,"yan@gmail.com");


DROP TABLE IF EXISTS `tipos`;

CREATE TABLE `tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `tipos` VALUES (5,"caballero","2012-11-01 00:04:32"),
(6,"niño","2012-11-01 00:04:45"),
(7,"niña","2012-11-01 00:04:52"),
(8,"dama","2012-11-01 00:06:55");


DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `perfil` text COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL,
  PRIMARY KEY (`usuario`),
  KEY `cedula` (`cedula`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `usuarios` VALUES (23194809,"admin","$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG","Administrador",1),
(23194809,"francoarias81","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Administrador",1),
(40123456,"gsus","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Vendedor",0),
(31744101,"jotadevs","$2a$07$asxx54ahjppf45sd87a5auw6I7JoP8iFx7dMSn3sb4CatTnVVTCzi","Vendedor",1),
(12847231,"ricki","$2a$07$asxx54ahjppf45sd87a5auuA/wno05nzDP.bGv2s5tKCJKhZVQQgC","Administrador",1);


DROP TABLE IF EXISTS `venta_producto`;

CREATE TABLE `venta_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`),
  KEY `producto` (`producto`),
  CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `ventas` (`factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `venta_producto` VALUES (42,10003,2,2000),
(49,10004,1,21512356),
(50,10004,1,"214u1"),
(51,10004,1,214123),
(52,10004,1,2141),
(53,10005,1,214123),
(54,10006,1,21512356),
(55,10006,1,"214u1"),
(56,10006,1,214123),
(57,10007,1,21512356),
(58,10007,1,"214u1"),
(59,10007,1,214123),
(60,10007,1,2141),
(61,10007,1,2000),
(62,10008,1,21512356),
(63,10008,1,"214u1"),
(64,10008,1,214123),
(65,10008,1,2141),
(66,10009,1,21512356),
(67,10010,1,21512356),
(68,10010,1,"214u1"),
(69,10010,1,214123);


DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `factura` int(11) NOT NULL AUTO_INCREMENT,
  `rif_empresa` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `vendedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `total` float NOT NULL,
  `pago` float NOT NULL,
  `impuesto` float NOT NULL DEFAULT 16,
  `metodo_pago` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cliente` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `rif_empresa` (`rif_empresa`),
  KEY `cliente` (`cliente`),
  KEY `vendedor` (`vendedor`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`rif_empresa`) REFERENCES `empresa` (`rif`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10013 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `ventas` VALUES (10003,"j-1234087",23194809,"240.35",185,16,"Pago movil","2024-11-02 03:20:15",12400500),
(10004,"j-1234087",23194809,"2206.09",1,304,"Efectivo","2024-11-09 18:14:52",12400500),
(10005,"j-1234087",23194809,"721.056","621.6",99,"Efectivo","2024-11-09 18:15:31",12400500),
(10006,"j-1234087",23194809,"1485.03",1,204,"Efectivo","2024-11-09 18:16:26",12400500),
(10007,"j-1234087",23194809,"2326.26","2005.4",320,"Efectivo","2024-11-09 18:18:23",12400500),
(10008,"j-1234087",23194809,"2316.39","1996.89",319,"TJ-445656","2024-11-09 18:21:22",12400500),
(10009,"j-1234087",23194809,"153.224","132.09","21.1344","TJ-1234","2024-11-09 18:22:50",259103825),
(10010,"j-1234087",23194809,"1595.34","1375.29","220.05","Efectivo","2024-11-09 18:36:25",31744101);


SET foreign_key_checks = 1;
CREATE DATABASE IF NOT EXISTS `zapateriak`;

USE `zapateriak`;

SET foreign_key_checks = 0;

DROP TABLE IF EXISTS `apartado`;

CREATE TABLE `apartado` (
  `factura` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` int(11) NOT NULL,
  `vendedor` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `abonado` float NOT NULL,
  `impuesto` int(11) NOT NULL,
  `cliente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `vendedor` (`vendedor`,`cliente`),
  KEY `cliente` (`cliente`),
  CONSTRAINT `apartado_ibfk_1` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`),
  CONSTRAINT `apartado_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `apartado_producto`;

CREATE TABLE `apartado_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`,`producto`),
  KEY `producto` (`producto`),
  CONSTRAINT `apartado_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `apartado` (`factura`),
  CONSTRAINT `apartado_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `categorias` VALUES (7,"Ropa","2024-10-20 21:17:02"),
(9,"Calzados","2024-10-20 22:02:09"),
(10,"ACCESORIOS","2024-10-23 01:54:40"),
(12,"COMPONENTES","2024-10-30 01:38:07");


DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `apellido` text COLLATE utf8_spanish_ci NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `clientes` VALUES (12400500,"Ismael","Martinez","ismale@gmail.com",4127421201,"Tu casa"),
(259103825,"Vanessa","Teran","vane1q@gmail.com",2108974,"La loma"),
(31744101,"Jhoan","Mejia","mejiajhoan2021@gmail.com",4247079098,"Bisnaca"),
(31982314,"Esbeidy","Gil","esbeydui@gmail.com","Miticun",10987235),
(7689758,"Julio","Guanda","vane1q@gmail.com",4247079089,"Miticun"),
(908712984,"Julio","Carrasquero","vane@gmail.com",125123,"Miticun");


DROP TABLE IF EXISTS `colores`;

CREATE TABLE `colores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `colores` VALUES (1,"negro","2024-10-21 05:52:13"),
(10,"blanco","2024-10-21 08:17:13"),
(11,"verde","2024-10-21 08:17:24"),
(12,"beige","2012-11-01 00:08:15"),
(13,"TUTIFRUTTY","2024-10-26 20:34:51");


DROP TABLE IF EXISTS `empleado`;

CREATE TABLE `empleado` (
  `cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `foto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empleado` VALUES (12393320,432141325,"Francisco","Arias",""),
(12847231,9012754,"Ricardo","Yepez Bastidas",""),
(1290481,432141325,"DOUGLAS JOSUE","DEL VALLE",""),
(23194809,9102375089,"Isaac","Bastidas",""),
(3174101,172089720,"Jota","Mejia",""),
(31744101,4247079098,"Jhoan","Mejia",""),
(40123456,409879,"Francisco","Martinez","vistas/img/usuarios//661.jpg");


DROP TABLE IF EXISTS `empresa`;

CREATE TABLE `empresa` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio_dolar` float NOT NULL,
  `impuesto` float NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empresa` VALUES ("j-1234087","Centro de Bocono","Kamil Styles",1385132,37,16);


DROP TABLE IF EXISTS `marcas`;

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marca` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `marcas` VALUES (10,"adidas","2024-10-21 08:18:24"),
(11,"pocholin","2024-10-21 08:18:31"),
(13,"rs21","2024-10-21 08:18:44"),
(14,"Aero","2012-11-01 00:07:27"),
(15,"generica","2012-11-01 00:07:35"),
(16,"acadia","2012-11-01 00:07:46"),
(17,"sifrina","2012-11-01 00:07:59"),
(18,"NIKE","2024-10-25 18:02:27");


DROP TABLE IF EXISTS `productos`;

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
  `precio_venta` float NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `id_tipo` (`id_tipo`),
  KEY `id_categoria` (`id_categoria`,`id_color`,`id_marca`),
  KEY `id_color` (`id_color`),
  KEY `id_marca` (`id_marca`),
  KEY `rif_proveedor` (`rif_proveedor`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_color`) REFERENCES `colores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_4` FOREIGN KEY (`id_tipo`) REFERENCES `tipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_5` FOREIGN KEY (`rif_proveedor`) REFERENCES `proveedores` (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `productos` VALUES (7,5,1,11,122319847,1010,"MANILLA","vistas/img/productos/default/anonymous.png",14,2,"2.8"),
(10,5,1,16,2151351235,1212,"FORZA XL","vistas/img/productos/1212/907.jpg",10,2,"2.8"),
(7,5,1,11,122319847,1234,"camisa","vistas/img/productos/default/anonymous.png",8,5,7),
(7,5,1,11,122319847,1235,"bota","vistas/img/productos/default/anonymous.png",9,15,21),
(10,5,1,11,122319847,12874,"TOLTO","vistas/img/productos/default/anonymous.png",10,12,"16.8"),
(10,5,1,11,122319847,2000,"RELOG","vistas/img/productos/default/anonymous.png",17,2,"2.8"),
(9,5,1,11,122319847,2141,"LKjdakl","vistas/img/productos/2141/351.jpg",13,12,"16.8"),
(9,5,1,11,122319847,214123,1523,"vistas/img/productos/default/anonymous.png",307,12,"16.8"),
(10,5,10,11,122319847,"214u1","BOTAS","vistas/img/productos/default/anonymous.png",9,12,"16.8"),
(10,8,13,18,2151351235,21512356,"COLLARES AESTHETIC","vistas/img/productos/default/anonymous.png",18,"2.55","3.57");


DROP TABLE IF EXISTS `proveedores`;

CREATE TABLE `proveedores` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cedula_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `correo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `proveedores` VALUES (122319847,"NIKE SPORT","Vanesa","Yepez","Caracas",128948912,921087,"vane@gmail.com"),
(2151351235,"TOTAL PANDA","Yanderson","Bastidas","Sotchi",1298412,291501235,"yan@gmail.com");


DROP TABLE IF EXISTS `tipos`;

CREATE TABLE `tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `tipos` VALUES (5,"caballero","2012-11-01 00:04:32"),
(6,"niño","2012-11-01 00:04:45"),
(7,"niña","2012-11-01 00:04:52"),
(8,"dama","2012-11-01 00:06:55");


DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `perfil` text COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL,
  PRIMARY KEY (`usuario`),
  KEY `cedula` (`cedula`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `usuarios` VALUES (23194809,"admin","$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG","Administrador",1),
(23194809,"francoarias81","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Administrador",1),
(40123456,"gsus","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Vendedor",0),
(31744101,"jotadevs","$2a$07$asxx54ahjppf45sd87a5auw6I7JoP8iFx7dMSn3sb4CatTnVVTCzi","Vendedor",1),
(12847231,"ricki","$2a$07$asxx54ahjppf45sd87a5auuA/wno05nzDP.bGv2s5tKCJKhZVQQgC","Administrador",1);


DROP TABLE IF EXISTS `venta_producto`;

CREATE TABLE `venta_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`),
  KEY `producto` (`producto`),
  CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `ventas` (`factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `venta_producto` VALUES (42,10003,2,2000),
(49,10004,1,21512356),
(50,10004,1,"214u1"),
(51,10004,1,214123),
(52,10004,1,2141),
(53,10005,1,214123),
(54,10006,1,21512356),
(55,10006,1,"214u1"),
(56,10006,1,214123),
(57,10007,1,21512356),
(58,10007,1,"214u1"),
(59,10007,1,214123),
(60,10007,1,2141),
(61,10007,1,2000),
(62,10008,1,21512356),
(63,10008,1,"214u1"),
(64,10008,1,214123),
(65,10008,1,2141),
(66,10009,1,21512356),
(67,10010,1,21512356),
(68,10010,1,"214u1"),
(69,10010,1,214123);


DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `factura` int(11) NOT NULL AUTO_INCREMENT,
  `rif_empresa` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `vendedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `total` float NOT NULL,
  `pago` float NOT NULL,
  `impuesto` float NOT NULL DEFAULT 16,
  `metodo_pago` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cliente` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `rif_empresa` (`rif_empresa`),
  KEY `cliente` (`cliente`),
  KEY `vendedor` (`vendedor`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`rif_empresa`) REFERENCES `empresa` (`rif`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10013 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `ventas` VALUES (10003,"j-1234087",23194809,"240.35",185,16,"Pago movil","2024-11-02 03:20:15",12400500),
(10004,"j-1234087",23194809,"2206.09",1,304,"Efectivo","2024-11-09 18:14:52",12400500),
(10005,"j-1234087",23194809,"721.056","621.6",99,"Efectivo","2024-11-09 18:15:31",12400500),
(10006,"j-1234087",23194809,"1485.03",1,204,"Efectivo","2024-11-09 18:16:26",12400500),
(10007,"j-1234087",23194809,"2326.26","2005.4",320,"Efectivo","2024-11-09 18:18:23",12400500),
(10008,"j-1234087",23194809,"2316.39","1996.89",319,"TJ-445656","2024-11-09 18:21:22",12400500),
(10009,"j-1234087",23194809,"153.224","132.09","21.1344","TJ-1234","2024-11-09 18:22:50",259103825),
(10010,"j-1234087",23194809,"1595.34","1375.29","220.05","Efectivo","2024-11-09 18:36:25",31744101);


SET foreign_key_checks = 1;
CREATE DATABASE IF NOT EXISTS `zapateriak`;

USE `zapateriak`;

SET foreign_key_checks = 0;

DROP TABLE IF EXISTS `apartado`;

CREATE TABLE `apartado` (
  `factura` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` int(11) NOT NULL,
  `vendedor` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `abonado` float NOT NULL,
  `impuesto` int(11) NOT NULL,
  `cliente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `vendedor` (`vendedor`,`cliente`),
  KEY `cliente` (`cliente`),
  CONSTRAINT `apartado_ibfk_1` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`),
  CONSTRAINT `apartado_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `apartado_producto`;

CREATE TABLE `apartado_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`,`producto`),
  KEY `producto` (`producto`),
  CONSTRAINT `apartado_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `apartado` (`factura`),
  CONSTRAINT `apartado_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `categorias` VALUES (7,"Ropa","2024-10-20 21:17:02"),
(9,"Calzados","2024-10-20 22:02:09"),
(10,"ACCESORIOS","2024-10-23 01:54:40"),
(12,"COMPONENTES","2024-10-30 01:38:07");


DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `apellido` text COLLATE utf8_spanish_ci NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `clientes` VALUES (12400500,"Ismael","Martinez","ismale@gmail.com",4127421201,"Tu casa"),
(259103825,"Vanessa","Teran","vane1q@gmail.com",2108974,"La loma"),
(31744101,"Jhoan","Mejia","mejiajhoan2021@gmail.com",4247079098,"Bisnaca"),
(31982314,"Esbeidy","Gil","esbeydui@gmail.com","Miticun",10987235),
(7689758,"Julio","Guanda","vane1q@gmail.com",4247079089,"Miticun"),
(908712984,"Julio","Carrasquero","vane@gmail.com",125123,"Miticun");


DROP TABLE IF EXISTS `colores`;

CREATE TABLE `colores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `colores` VALUES (1,"negro","2024-10-21 05:52:13"),
(10,"blanco","2024-10-21 08:17:13"),
(11,"verde","2024-10-21 08:17:24"),
(12,"beige","2012-11-01 00:08:15"),
(13,"TUTIFRUTTY","2024-10-26 20:34:51");


DROP TABLE IF EXISTS `empleado`;

CREATE TABLE `empleado` (
  `cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `foto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empleado` VALUES (12393320,432141325,"Francisco","Arias",""),
(12847231,9012754,"Ricardo","Yepez Bastidas",""),
(1290481,432141325,"DOUGLAS JOSUE","DEL VALLE",""),
(23194809,9102375089,"Isaac","Bastidas",""),
(3174101,172089720,"Jota","Mejia",""),
(31744101,4247079098,"Jhoan","Mejia",""),
(40123456,409879,"Francisco","Martinez","vistas/img/usuarios//661.jpg");


DROP TABLE IF EXISTS `empresa`;

CREATE TABLE `empresa` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio_dolar` float NOT NULL,
  `impuesto` float NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empresa` VALUES ("j-1234087","Centro de Bocono","Kamil Styles",1385132,37,16);


DROP TABLE IF EXISTS `marcas`;

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marca` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `marcas` VALUES (10,"adidas","2024-10-21 08:18:24"),
(11,"pocholin","2024-10-21 08:18:31"),
(13,"rs21","2024-10-21 08:18:44"),
(14,"Aero","2012-11-01 00:07:27"),
(15,"generica","2012-11-01 00:07:35"),
(16,"acadia","2012-11-01 00:07:46"),
(17,"sifrina","2012-11-01 00:07:59"),
(18,"NIKE","2024-10-25 18:02:27");


DROP TABLE IF EXISTS `productos`;

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
  `precio_venta` float NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `id_tipo` (`id_tipo`),
  KEY `id_categoria` (`id_categoria`,`id_color`,`id_marca`),
  KEY `id_color` (`id_color`),
  KEY `id_marca` (`id_marca`),
  KEY `rif_proveedor` (`rif_proveedor`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_color`) REFERENCES `colores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_4` FOREIGN KEY (`id_tipo`) REFERENCES `tipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_5` FOREIGN KEY (`rif_proveedor`) REFERENCES `proveedores` (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `productos` VALUES (7,5,1,11,122319847,1010,"MANILLA","vistas/img/productos/default/anonymous.png",14,2,"2.8"),
(10,5,1,16,2151351235,1212,"FORZA XL","vistas/img/productos/1212/907.jpg",10,2,"2.8"),
(7,5,1,11,122319847,1234,"camisa","vistas/img/productos/default/anonymous.png",8,5,7),
(7,5,1,11,122319847,1235,"bota","vistas/img/productos/default/anonymous.png",9,15,21),
(10,5,1,11,122319847,12874,"TOLTO","vistas/img/productos/default/anonymous.png",10,12,"16.8"),
(10,5,1,11,122319847,2000,"RELOG","vistas/img/productos/default/anonymous.png",17,2,"2.8"),
(9,5,1,11,122319847,2141,"LKjdakl","vistas/img/productos/2141/351.jpg",13,12,"16.8"),
(9,5,1,11,122319847,214123,1523,"vistas/img/productos/default/anonymous.png",307,12,"16.8"),
(10,5,10,11,122319847,"214u1","BOTAS","vistas/img/productos/default/anonymous.png",9,12,"16.8"),
(10,8,13,18,2151351235,21512356,"COLLARES AESTHETIC","vistas/img/productos/default/anonymous.png",18,"2.55","3.57");


DROP TABLE IF EXISTS `proveedores`;

CREATE TABLE `proveedores` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cedula_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `correo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `proveedores` VALUES (122319847,"NIKE SPORT","Vanesa","Yepez","Caracas",128948912,921087,"vane@gmail.com"),
(2151351235,"TOTAL PANDA","Yanderson","Bastidas","Sotchi",1298412,291501235,"yan@gmail.com");


DROP TABLE IF EXISTS `tipos`;

CREATE TABLE `tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `tipos` VALUES (5,"caballero","2012-11-01 00:04:32"),
(6,"niño","2012-11-01 00:04:45"),
(7,"niña","2012-11-01 00:04:52"),
(8,"dama","2012-11-01 00:06:55");


DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `perfil` text COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL,
  PRIMARY KEY (`usuario`),
  KEY `cedula` (`cedula`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `usuarios` VALUES (23194809,"admin","$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG","Administrador",1),
(23194809,"francoarias81","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Administrador",1),
(40123456,"gsus","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Vendedor",0),
(31744101,"jotadevs","$2a$07$asxx54ahjppf45sd87a5auw6I7JoP8iFx7dMSn3sb4CatTnVVTCzi","Vendedor",1),
(12847231,"ricki","$2a$07$asxx54ahjppf45sd87a5auuA/wno05nzDP.bGv2s5tKCJKhZVQQgC","Administrador",1);


DROP TABLE IF EXISTS `venta_producto`;

CREATE TABLE `venta_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`),
  KEY `producto` (`producto`),
  CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `ventas` (`factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `venta_producto` VALUES (42,10003,2,2000),
(49,10004,1,21512356),
(50,10004,1,"214u1"),
(51,10004,1,214123),
(52,10004,1,2141),
(53,10005,1,214123),
(54,10006,1,21512356),
(55,10006,1,"214u1"),
(56,10006,1,214123),
(57,10007,1,21512356),
(58,10007,1,"214u1"),
(59,10007,1,214123),
(60,10007,1,2141),
(61,10007,1,2000),
(62,10008,1,21512356),
(63,10008,1,"214u1"),
(64,10008,1,214123),
(65,10008,1,2141),
(66,10009,1,21512356),
(67,10010,1,21512356),
(68,10010,1,"214u1"),
(69,10010,1,214123);


DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `factura` int(11) NOT NULL AUTO_INCREMENT,
  `rif_empresa` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `vendedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `total` float NOT NULL,
  `pago` float NOT NULL,
  `impuesto` float NOT NULL DEFAULT 16,
  `metodo_pago` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cliente` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `rif_empresa` (`rif_empresa`),
  KEY `cliente` (`cliente`),
  KEY `vendedor` (`vendedor`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`rif_empresa`) REFERENCES `empresa` (`rif`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10013 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `ventas` VALUES (10003,"j-1234087",23194809,"240.35",185,16,"Pago movil","2024-11-02 03:20:15",12400500),
(10004,"j-1234087",23194809,"2206.09",1,304,"Efectivo","2024-11-09 18:14:52",12400500),
(10005,"j-1234087",23194809,"721.056","621.6",99,"Efectivo","2024-11-09 18:15:31",12400500),
(10006,"j-1234087",23194809,"1485.03",1,204,"Efectivo","2024-11-09 18:16:26",12400500),
(10007,"j-1234087",23194809,"2326.26","2005.4",320,"Efectivo","2024-11-09 18:18:23",12400500),
(10008,"j-1234087",23194809,"2316.39","1996.89",319,"TJ-445656","2024-11-09 18:21:22",12400500),
(10009,"j-1234087",23194809,"153.224","132.09","21.1344","TJ-1234","2024-11-09 18:22:50",259103825),
(10010,"j-1234087",23194809,"1595.34","1375.29","220.05","Efectivo","2024-11-09 18:36:25",31744101);


SET foreign_key_checks = 1;
CREATE DATABASE IF NOT EXISTS `zapateriak`;

USE `zapateriak`;

SET foreign_key_checks = 0;

DROP TABLE IF EXISTS `apartado`;

CREATE TABLE `apartado` (
  `factura` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` int(11) NOT NULL,
  `vendedor` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `abonado` float NOT NULL,
  `impuesto` int(11) NOT NULL,
  `cliente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `vendedor` (`vendedor`,`cliente`),
  KEY `cliente` (`cliente`),
  CONSTRAINT `apartado_ibfk_1` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`),
  CONSTRAINT `apartado_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `apartado_producto`;

CREATE TABLE `apartado_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`,`producto`),
  KEY `producto` (`producto`),
  CONSTRAINT `apartado_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `apartado` (`factura`),
  CONSTRAINT `apartado_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `categorias` VALUES (7,"Ropa","2024-10-20 21:17:02"),
(9,"Calzados","2024-10-20 22:02:09"),
(10,"ACCESORIOS","2024-10-23 01:54:40"),
(12,"COMPONENTES","2024-10-30 01:38:07");


DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `apellido` text COLLATE utf8_spanish_ci NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `clientes` VALUES (12400500,"Ismael","Martinez","ismale@gmail.com",4127421201,"Tu casa"),
(259103825,"Vanessa","Teran","vane1q@gmail.com",2108974,"La loma"),
(31744101,"Jhoan","Mejia","mejiajhoan2021@gmail.com",4247079098,"Bisnaca"),
(31982314,"Esbeidy","Gil","esbeydui@gmail.com","Miticun",10987235),
(7689758,"Julio","Guanda","vane1q@gmail.com",4247079089,"Miticun"),
(908712984,"Julio","Carrasquero","vane@gmail.com",125123,"Miticun");


DROP TABLE IF EXISTS `colores`;

CREATE TABLE `colores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `colores` VALUES (1,"negro","2024-10-21 05:52:13"),
(10,"blanco","2024-10-21 08:17:13"),
(11,"verde","2024-10-21 08:17:24"),
(12,"beige","2012-11-01 00:08:15"),
(13,"TUTIFRUTTY","2024-10-26 20:34:51");


DROP TABLE IF EXISTS `empleado`;

CREATE TABLE `empleado` (
  `cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `foto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empleado` VALUES (12393320,432141325,"Francisco","Arias",""),
(12847231,9012754,"Ricardo","Yepez Bastidas",""),
(1290481,432141325,"DOUGLAS JOSUE","DEL VALLE",""),
(23194809,9102375089,"Isaac","Bastidas",""),
(3174101,172089720,"Jota","Mejia",""),
(31744101,4247079098,"Jhoan","Mejia",""),
(40123456,409879,"Francisco","Martinez","vistas/img/usuarios//661.jpg");


DROP TABLE IF EXISTS `empresa`;

CREATE TABLE `empresa` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio_dolar` float NOT NULL,
  `impuesto` float NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empresa` VALUES ("j-1234087","Centro de Bocono","Kamil Styles",1385132,37,16);


DROP TABLE IF EXISTS `marcas`;

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marca` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `marcas` VALUES (10,"adidas","2024-10-21 08:18:24"),
(11,"pocholin","2024-10-21 08:18:31"),
(13,"rs21","2024-10-21 08:18:44"),
(14,"Aero","2012-11-01 00:07:27"),
(15,"generica","2012-11-01 00:07:35"),
(16,"acadia","2012-11-01 00:07:46"),
(17,"sifrina","2012-11-01 00:07:59"),
(18,"NIKE","2024-10-25 18:02:27");


DROP TABLE IF EXISTS `productos`;

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
  `precio_venta` float NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `id_tipo` (`id_tipo`),
  KEY `id_categoria` (`id_categoria`,`id_color`,`id_marca`),
  KEY `id_color` (`id_color`),
  KEY `id_marca` (`id_marca`),
  KEY `rif_proveedor` (`rif_proveedor`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_color`) REFERENCES `colores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_4` FOREIGN KEY (`id_tipo`) REFERENCES `tipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_5` FOREIGN KEY (`rif_proveedor`) REFERENCES `proveedores` (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `productos` VALUES (7,5,1,11,122319847,1010,"MANILLA","vistas/img/productos/default/anonymous.png",14,2,"2.8"),
(10,5,1,16,2151351235,1212,"FORZA XL","vistas/img/productos/1212/907.jpg",10,2,"2.8"),
(7,5,1,11,122319847,1234,"camisa","vistas/img/productos/default/anonymous.png",8,5,7),
(7,5,1,11,122319847,1235,"bota","vistas/img/productos/default/anonymous.png",9,15,21),
(10,5,1,11,122319847,12874,"TOLTO","vistas/img/productos/default/anonymous.png",10,12,"16.8"),
(10,5,1,11,122319847,2000,"RELOG","vistas/img/productos/default/anonymous.png",17,2,"2.8"),
(9,5,1,11,122319847,2141,"LKjdakl","vistas/img/productos/2141/351.jpg",13,12,"16.8"),
(9,5,1,11,122319847,214123,1523,"vistas/img/productos/default/anonymous.png",307,12,"16.8"),
(10,5,10,11,122319847,"214u1","BOTAS","vistas/img/productos/default/anonymous.png",9,12,"16.8"),
(10,8,13,18,2151351235,21512356,"COLLARES AESTHETIC","vistas/img/productos/default/anonymous.png",18,"2.55","3.57");


DROP TABLE IF EXISTS `proveedores`;

CREATE TABLE `proveedores` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cedula_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `correo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `proveedores` VALUES (122319847,"NIKE SPORT","Vanesa","Yepez","Caracas",128948912,921087,"vane@gmail.com"),
(2151351235,"TOTAL PANDA","Yanderson","Bastidas","Sotchi",1298412,291501235,"yan@gmail.com");


DROP TABLE IF EXISTS `tipos`;

CREATE TABLE `tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `tipos` VALUES (5,"caballero","2012-11-01 00:04:32"),
(6,"niño","2012-11-01 00:04:45"),
(7,"niña","2012-11-01 00:04:52"),
(8,"dama","2012-11-01 00:06:55");


DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `perfil` text COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL,
  PRIMARY KEY (`usuario`),
  KEY `cedula` (`cedula`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `usuarios` VALUES (23194809,"admin","$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG","Administrador",1),
(23194809,"francoarias81","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Administrador",1),
(40123456,"gsus","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Vendedor",0),
(31744101,"jotadevs","$2a$07$asxx54ahjppf45sd87a5auw6I7JoP8iFx7dMSn3sb4CatTnVVTCzi","Vendedor",1),
(12847231,"ricki","$2a$07$asxx54ahjppf45sd87a5auuA/wno05nzDP.bGv2s5tKCJKhZVQQgC","Administrador",1);


DROP TABLE IF EXISTS `venta_producto`;

CREATE TABLE `venta_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`),
  KEY `producto` (`producto`),
  CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `ventas` (`factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `venta_producto` VALUES (42,10003,2,2000),
(49,10004,1,21512356),
(50,10004,1,"214u1"),
(51,10004,1,214123),
(52,10004,1,2141),
(53,10005,1,214123),
(54,10006,1,21512356),
(55,10006,1,"214u1"),
(56,10006,1,214123),
(57,10007,1,21512356),
(58,10007,1,"214u1"),
(59,10007,1,214123),
(60,10007,1,2141),
(61,10007,1,2000),
(62,10008,1,21512356),
(63,10008,1,"214u1"),
(64,10008,1,214123),
(65,10008,1,2141),
(66,10009,1,21512356),
(67,10010,1,21512356),
(68,10010,1,"214u1"),
(69,10010,1,214123);


DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `factura` int(11) NOT NULL AUTO_INCREMENT,
  `rif_empresa` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `vendedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `total` float NOT NULL,
  `pago` float NOT NULL,
  `impuesto` float NOT NULL DEFAULT 16,
  `metodo_pago` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cliente` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `rif_empresa` (`rif_empresa`),
  KEY `cliente` (`cliente`),
  KEY `vendedor` (`vendedor`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`rif_empresa`) REFERENCES `empresa` (`rif`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10013 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `ventas` VALUES (10003,"j-1234087",23194809,"240.35",185,16,"Pago movil","2024-11-02 03:20:15",12400500),
(10004,"j-1234087",23194809,"2206.09",1,304,"Efectivo","2024-11-09 18:14:52",12400500),
(10005,"j-1234087",23194809,"721.056","621.6",99,"Efectivo","2024-11-09 18:15:31",12400500),
(10006,"j-1234087",23194809,"1485.03",1,204,"Efectivo","2024-11-09 18:16:26",12400500),
(10007,"j-1234087",23194809,"2326.26","2005.4",320,"Efectivo","2024-11-09 18:18:23",12400500),
(10008,"j-1234087",23194809,"2316.39","1996.89",319,"TJ-445656","2024-11-09 18:21:22",12400500),
(10009,"j-1234087",23194809,"153.224","132.09","21.1344","TJ-1234","2024-11-09 18:22:50",259103825),
(10010,"j-1234087",23194809,"1595.34","1375.29","220.05","Efectivo","2024-11-09 18:36:25",31744101);


SET foreign_key_checks = 1;
CREATE DATABASE IF NOT EXISTS `zapateriak`;

USE `zapateriak`;

SET foreign_key_checks = 0;

DROP TABLE IF EXISTS `apartado`;

CREATE TABLE `apartado` (
  `factura` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` int(11) NOT NULL,
  `vendedor` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `abonado` float NOT NULL,
  `impuesto` int(11) NOT NULL,
  `cliente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `vendedor` (`vendedor`,`cliente`),
  KEY `cliente` (`cliente`),
  CONSTRAINT `apartado_ibfk_1` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`),
  CONSTRAINT `apartado_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `apartado_producto`;

CREATE TABLE `apartado_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`,`producto`),
  KEY `producto` (`producto`),
  CONSTRAINT `apartado_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `apartado` (`factura`),
  CONSTRAINT `apartado_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `categorias` VALUES (7,"Ropa","2024-10-20 21:17:02"),
(9,"Calzados","2024-10-20 22:02:09"),
(10,"ACCESORIOS","2024-10-23 01:54:40"),
(12,"COMPONENTES","2024-10-30 01:38:07");


DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `apellido` text COLLATE utf8_spanish_ci NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `clientes` VALUES (12400500,"Ismael","Martinez","ismale@gmail.com",4127421201,"Tu casa"),
(259103825,"Vanessa","Teran","vane1q@gmail.com",2108974,"La loma"),
(31744101,"Jhoan","Mejia","mejiajhoan2021@gmail.com",4247079098,"Bisnaca"),
(31982314,"Esbeidy","Gil","esbeydui@gmail.com","Miticun",10987235),
(7689758,"Julio","Guanda","vane1q@gmail.com",4247079089,"Miticun"),
(908712984,"Julio","Carrasquero","vane@gmail.com",125123,"Miticun");


DROP TABLE IF EXISTS `colores`;

CREATE TABLE `colores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `colores` VALUES (1,"negro","2024-10-21 05:52:13"),
(10,"blanco","2024-10-21 08:17:13"),
(11,"verde","2024-10-21 08:17:24"),
(12,"beige","2012-11-01 00:08:15"),
(13,"TUTIFRUTTY","2024-10-26 20:34:51");


DROP TABLE IF EXISTS `empleado`;

CREATE TABLE `empleado` (
  `cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `foto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empleado` VALUES (12393320,432141325,"Francisco","Arias",""),
(12847231,9012754,"Ricardo","Yepez Bastidas",""),
(1290481,432141325,"DOUGLAS JOSUE","DEL VALLE",""),
(23194809,9102375089,"Isaac","Bastidas",""),
(3174101,172089720,"Jota","Mejia",""),
(31744101,4247079098,"Jhoan","Mejia",""),
(40123456,409879,"Francisco","Martinez","vistas/img/usuarios//661.jpg");


DROP TABLE IF EXISTS `empresa`;

CREATE TABLE `empresa` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio_dolar` float NOT NULL,
  `impuesto` float NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empresa` VALUES ("j-1234087","Centro de Bocono","Kamil Styles",1385132,37,16);


DROP TABLE IF EXISTS `marcas`;

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marca` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `marcas` VALUES (10,"adidas","2024-10-21 08:18:24"),
(11,"pocholin","2024-10-21 08:18:31"),
(13,"rs21","2024-10-21 08:18:44"),
(14,"Aero","2012-11-01 00:07:27"),
(15,"generica","2012-11-01 00:07:35"),
(16,"acadia","2012-11-01 00:07:46"),
(17,"sifrina","2012-11-01 00:07:59"),
(18,"NIKE","2024-10-25 18:02:27");


DROP TABLE IF EXISTS `productos`;

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
  `precio_venta` float NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `id_tipo` (`id_tipo`),
  KEY `id_categoria` (`id_categoria`,`id_color`,`id_marca`),
  KEY `id_color` (`id_color`),
  KEY `id_marca` (`id_marca`),
  KEY `rif_proveedor` (`rif_proveedor`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_color`) REFERENCES `colores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_4` FOREIGN KEY (`id_tipo`) REFERENCES `tipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_5` FOREIGN KEY (`rif_proveedor`) REFERENCES `proveedores` (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `productos` VALUES (7,5,1,11,122319847,1010,"MANILLA","vistas/img/productos/default/anonymous.png",14,2,"2.8"),
(10,5,1,16,2151351235,1212,"FORZA XL","vistas/img/productos/1212/907.jpg",10,2,"2.8"),
(7,5,1,11,122319847,1234,"camisa","vistas/img/productos/default/anonymous.png",8,5,7),
(7,5,1,11,122319847,1235,"bota","vistas/img/productos/default/anonymous.png",9,15,21),
(10,5,1,11,122319847,12874,"TOLTO","vistas/img/productos/default/anonymous.png",10,12,"16.8"),
(10,5,1,11,122319847,2000,"RELOG","vistas/img/productos/default/anonymous.png",17,2,"2.8"),
(9,5,1,11,122319847,2141,"LKjdakl","vistas/img/productos/2141/351.jpg",13,12,"16.8"),
(9,5,1,11,122319847,214123,1523,"vistas/img/productos/default/anonymous.png",307,12,"16.8"),
(10,5,10,11,122319847,"214u1","BOTAS","vistas/img/productos/default/anonymous.png",9,12,"16.8"),
(10,8,13,18,2151351235,21512356,"COLLARES AESTHETIC","vistas/img/productos/default/anonymous.png",18,"2.55","3.57");


DROP TABLE IF EXISTS `proveedores`;

CREATE TABLE `proveedores` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cedula_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `correo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `proveedores` VALUES (122319847,"NIKE SPORT","Vanesa","Yepez","Caracas",128948912,921087,"vane@gmail.com"),
(2151351235,"TOTAL PANDA","Yanderson","Bastidas","Sotchi",1298412,291501235,"yan@gmail.com");


DROP TABLE IF EXISTS `tipos`;

CREATE TABLE `tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `tipos` VALUES (5,"caballero","2012-11-01 00:04:32"),
(6,"niño","2012-11-01 00:04:45"),
(7,"niña","2012-11-01 00:04:52"),
(8,"dama","2012-11-01 00:06:55");


DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `perfil` text COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL,
  PRIMARY KEY (`usuario`),
  KEY `cedula` (`cedula`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `usuarios` VALUES (23194809,"admin","$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG","Administrador",1),
(23194809,"francoarias81","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Administrador",1),
(40123456,"gsus","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Vendedor",0),
(31744101,"jotadevs","$2a$07$asxx54ahjppf45sd87a5auw6I7JoP8iFx7dMSn3sb4CatTnVVTCzi","Vendedor",1),
(12847231,"ricki","$2a$07$asxx54ahjppf45sd87a5auuA/wno05nzDP.bGv2s5tKCJKhZVQQgC","Administrador",1);


DROP TABLE IF EXISTS `venta_producto`;

CREATE TABLE `venta_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`),
  KEY `producto` (`producto`),
  CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `ventas` (`factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `venta_producto` VALUES (42,10003,2,2000),
(49,10004,1,21512356),
(50,10004,1,"214u1"),
(51,10004,1,214123),
(52,10004,1,2141),
(53,10005,1,214123),
(54,10006,1,21512356),
(55,10006,1,"214u1"),
(56,10006,1,214123),
(57,10007,1,21512356),
(58,10007,1,"214u1"),
(59,10007,1,214123),
(60,10007,1,2141),
(61,10007,1,2000),
(62,10008,1,21512356),
(63,10008,1,"214u1"),
(64,10008,1,214123),
(65,10008,1,2141),
(66,10009,1,21512356),
(67,10010,1,21512356),
(68,10010,1,"214u1"),
(69,10010,1,214123);


DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `factura` int(11) NOT NULL AUTO_INCREMENT,
  `rif_empresa` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `vendedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `total` float NOT NULL,
  `pago` float NOT NULL,
  `impuesto` float NOT NULL DEFAULT 16,
  `metodo_pago` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cliente` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `rif_empresa` (`rif_empresa`),
  KEY `cliente` (`cliente`),
  KEY `vendedor` (`vendedor`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`rif_empresa`) REFERENCES `empresa` (`rif`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10013 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `ventas` VALUES (10003,"j-1234087",23194809,"240.35",185,16,"Pago movil","2024-11-02 03:20:15",12400500),
(10004,"j-1234087",23194809,"2206.09",1,304,"Efectivo","2024-11-09 18:14:52",12400500),
(10005,"j-1234087",23194809,"721.056","621.6",99,"Efectivo","2024-11-09 18:15:31",12400500),
(10006,"j-1234087",23194809,"1485.03",1,204,"Efectivo","2024-11-09 18:16:26",12400500),
(10007,"j-1234087",23194809,"2326.26","2005.4",320,"Efectivo","2024-11-09 18:18:23",12400500),
(10008,"j-1234087",23194809,"2316.39","1996.89",319,"TJ-445656","2024-11-09 18:21:22",12400500),
(10009,"j-1234087",23194809,"153.224","132.09","21.1344","TJ-1234","2024-11-09 18:22:50",259103825),
(10010,"j-1234087",23194809,"1595.34","1375.29","220.05","Efectivo","2024-11-09 18:36:25",31744101);


SET foreign_key_checks = 1;
CREATE DATABASE IF NOT EXISTS `zapateriak`;

USE `zapateriak`;

SET foreign_key_checks = 0;

DROP TABLE IF EXISTS `apartado`;

CREATE TABLE `apartado` (
  `factura` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` int(11) NOT NULL,
  `vendedor` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `abonado` float NOT NULL,
  `impuesto` int(11) NOT NULL,
  `cliente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `vendedor` (`vendedor`,`cliente`),
  KEY `cliente` (`cliente`),
  CONSTRAINT `apartado_ibfk_1` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`),
  CONSTRAINT `apartado_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `apartado_producto`;

CREATE TABLE `apartado_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`,`producto`),
  KEY `producto` (`producto`),
  CONSTRAINT `apartado_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `apartado` (`factura`),
  CONSTRAINT `apartado_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `categorias` VALUES (7,"Ropa","2024-10-20 21:17:02"),
(9,"Calzados","2024-10-20 22:02:09"),
(10,"ACCESORIOS","2024-10-23 01:54:40"),
(12,"COMPONENTES","2024-10-30 01:38:07");


DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `apellido` text COLLATE utf8_spanish_ci NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `clientes` VALUES (12400500,"Ismael","Martinez","ismale@gmail.com",4127421201,"Tu casa"),
(259103825,"Vanessa","Teran","vane1q@gmail.com",2108974,"La loma"),
(31744101,"Jhoan","Mejia","mejiajhoan2021@gmail.com",4247079098,"Bisnaca"),
(31982314,"Esbeidy","Gil","esbeydui@gmail.com","Miticun",10987235),
(7689758,"Julio","Guanda","vane1q@gmail.com",4247079089,"Miticun"),
(908712984,"Julio","Carrasquero","vane@gmail.com",125123,"Miticun");


DROP TABLE IF EXISTS `colores`;

CREATE TABLE `colores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `colores` VALUES (1,"negro","2024-10-21 05:52:13"),
(10,"blanco","2024-10-21 08:17:13"),
(11,"verde","2024-10-21 08:17:24"),
(12,"beige","2012-11-01 00:08:15"),
(13,"TUTIFRUTTY","2024-10-26 20:34:51");


DROP TABLE IF EXISTS `empleado`;

CREATE TABLE `empleado` (
  `cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `foto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empleado` VALUES (12393320,432141325,"Francisco","Arias",""),
(12847231,9012754,"Ricardo","Yepez Bastidas",""),
(1290481,432141325,"DOUGLAS JOSUE","DEL VALLE",""),
(23194809,9102375089,"Isaac","Bastidas",""),
(3174101,172089720,"Jota","Mejia",""),
(31744101,4247079098,"Jhoan","Mejia",""),
(40123456,409879,"Francisco","Martinez","vistas/img/usuarios//661.jpg");


DROP TABLE IF EXISTS `empresa`;

CREATE TABLE `empresa` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio_dolar` float NOT NULL,
  `impuesto` float NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empresa` VALUES ("j-1234087","Centro de Bocono","Kamil Styles",1385132,37,16);


DROP TABLE IF EXISTS `marcas`;

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marca` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `marcas` VALUES (10,"adidas","2024-10-21 08:18:24"),
(11,"pocholin","2024-10-21 08:18:31"),
(13,"rs21","2024-10-21 08:18:44"),
(14,"Aero","2012-11-01 00:07:27"),
(15,"generica","2012-11-01 00:07:35"),
(16,"acadia","2012-11-01 00:07:46"),
(17,"sifrina","2012-11-01 00:07:59"),
(18,"NIKE","2024-10-25 18:02:27");


DROP TABLE IF EXISTS `productos`;

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
  `precio_venta` float NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `id_tipo` (`id_tipo`),
  KEY `id_categoria` (`id_categoria`,`id_color`,`id_marca`),
  KEY `id_color` (`id_color`),
  KEY `id_marca` (`id_marca`),
  KEY `rif_proveedor` (`rif_proveedor`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_color`) REFERENCES `colores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_4` FOREIGN KEY (`id_tipo`) REFERENCES `tipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_5` FOREIGN KEY (`rif_proveedor`) REFERENCES `proveedores` (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `productos` VALUES (7,5,1,11,122319847,1010,"MANILLA","vistas/img/productos/default/anonymous.png",14,2,"2.8"),
(10,5,1,16,2151351235,1212,"FORZA XL","vistas/img/productos/1212/907.jpg",10,2,"2.8"),
(7,5,1,11,122319847,1234,"camisa","vistas/img/productos/default/anonymous.png",8,5,7),
(7,5,1,11,122319847,1235,"bota","vistas/img/productos/default/anonymous.png",9,15,21),
(10,5,1,11,122319847,12874,"TOLTO","vistas/img/productos/default/anonymous.png",10,12,"16.8"),
(10,5,1,11,122319847,2000,"RELOG","vistas/img/productos/default/anonymous.png",17,2,"2.8"),
(9,5,1,11,122319847,2141,"LKjdakl","vistas/img/productos/2141/351.jpg",13,12,"16.8"),
(9,5,1,11,122319847,214123,1523,"vistas/img/productos/default/anonymous.png",307,12,"16.8"),
(10,5,10,11,122319847,"214u1","BOTAS","vistas/img/productos/default/anonymous.png",9,12,"16.8"),
(10,8,13,18,2151351235,21512356,"COLLARES AESTHETIC","vistas/img/productos/default/anonymous.png",18,"2.55","3.57");


DROP TABLE IF EXISTS `proveedores`;

CREATE TABLE `proveedores` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cedula_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `correo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `proveedores` VALUES (122319847,"NIKE SPORT","Vanesa","Yepez","Caracas",128948912,921087,"vane@gmail.com"),
(2151351235,"TOTAL PANDA","Yanderson","Bastidas","Sotchi",1298412,291501235,"yan@gmail.com");


DROP TABLE IF EXISTS `tipos`;

CREATE TABLE `tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `tipos` VALUES (5,"caballero","2012-11-01 00:04:32"),
(6,"niño","2012-11-01 00:04:45"),
(7,"niña","2012-11-01 00:04:52"),
(8,"dama","2012-11-01 00:06:55");


DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `perfil` text COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL,
  PRIMARY KEY (`usuario`),
  KEY `cedula` (`cedula`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `usuarios` VALUES (23194809,"admin","$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG","Administrador",1),
(23194809,"francoarias81","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Administrador",1),
(40123456,"gsus","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Vendedor",0),
(31744101,"jotadevs","$2a$07$asxx54ahjppf45sd87a5auw6I7JoP8iFx7dMSn3sb4CatTnVVTCzi","Vendedor",1),
(12847231,"ricki","$2a$07$asxx54ahjppf45sd87a5auuA/wno05nzDP.bGv2s5tKCJKhZVQQgC","Administrador",1);


DROP TABLE IF EXISTS `venta_producto`;

CREATE TABLE `venta_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`),
  KEY `producto` (`producto`),
  CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `ventas` (`factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `venta_producto` VALUES (42,10003,2,2000),
(49,10004,1,21512356),
(50,10004,1,"214u1"),
(51,10004,1,214123),
(52,10004,1,2141),
(53,10005,1,214123),
(54,10006,1,21512356),
(55,10006,1,"214u1"),
(56,10006,1,214123),
(57,10007,1,21512356),
(58,10007,1,"214u1"),
(59,10007,1,214123),
(60,10007,1,2141),
(61,10007,1,2000),
(62,10008,1,21512356),
(63,10008,1,"214u1"),
(64,10008,1,214123),
(65,10008,1,2141),
(66,10009,1,21512356),
(67,10010,1,21512356),
(68,10010,1,"214u1"),
(69,10010,1,214123);


DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `factura` int(11) NOT NULL AUTO_INCREMENT,
  `rif_empresa` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `vendedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `total` float NOT NULL,
  `pago` float NOT NULL,
  `impuesto` float NOT NULL DEFAULT 16,
  `metodo_pago` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cliente` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `rif_empresa` (`rif_empresa`),
  KEY `cliente` (`cliente`),
  KEY `vendedor` (`vendedor`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`rif_empresa`) REFERENCES `empresa` (`rif`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10013 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `ventas` VALUES (10003,"j-1234087",23194809,"240.35",185,16,"Pago movil","2024-11-02 03:20:15",12400500),
(10004,"j-1234087",23194809,"2206.09",1,304,"Efectivo","2024-11-09 18:14:52",12400500),
(10005,"j-1234087",23194809,"721.056","621.6",99,"Efectivo","2024-11-09 18:15:31",12400500),
(10006,"j-1234087",23194809,"1485.03",1,204,"Efectivo","2024-11-09 18:16:26",12400500),
(10007,"j-1234087",23194809,"2326.26","2005.4",320,"Efectivo","2024-11-09 18:18:23",12400500),
(10008,"j-1234087",23194809,"2316.39","1996.89",319,"TJ-445656","2024-11-09 18:21:22",12400500),
(10009,"j-1234087",23194809,"153.224","132.09","21.1344","TJ-1234","2024-11-09 18:22:50",259103825),
(10010,"j-1234087",23194809,"1595.34","1375.29","220.05","Efectivo","2024-11-09 18:36:25",31744101);


SET foreign_key_checks = 1;
CREATE DATABASE IF NOT EXISTS `zapateriak`;

USE `zapateriak`;

SET foreign_key_checks = 0;

DROP TABLE IF EXISTS `apartado`;

CREATE TABLE `apartado` (
  `factura` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` int(11) NOT NULL,
  `vendedor` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `abonado` float NOT NULL,
  `impuesto` int(11) NOT NULL,
  `cliente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `vendedor` (`vendedor`,`cliente`),
  KEY `cliente` (`cliente`),
  CONSTRAINT `apartado_ibfk_1` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`),
  CONSTRAINT `apartado_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `apartado_producto`;

CREATE TABLE `apartado_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`,`producto`),
  KEY `producto` (`producto`),
  CONSTRAINT `apartado_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `apartado` (`factura`),
  CONSTRAINT `apartado_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `categorias` VALUES (7,"Ropa","2024-10-20 21:17:02"),
(9,"Calzados","2024-10-20 22:02:09"),
(10,"ACCESORIOS","2024-10-23 01:54:40"),
(12,"COMPONENTES","2024-10-30 01:38:07");


DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `apellido` text COLLATE utf8_spanish_ci NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `clientes` VALUES (12400500,"Ismael","Martinez","ismale@gmail.com",4127421201,"Tu casa"),
(259103825,"Vanessa","Teran","vane1q@gmail.com",2108974,"La loma"),
(31744101,"Jhoan","Mejia","mejiajhoan2021@gmail.com",4247079098,"Bisnaca"),
(31982314,"Esbeidy","Gil","esbeydui@gmail.com","Miticun",10987235),
(7689758,"Julio","Guanda","vane1q@gmail.com",4247079089,"Miticun"),
(908712984,"Julio","Carrasquero","vane@gmail.com",125123,"Miticun");


DROP TABLE IF EXISTS `colores`;

CREATE TABLE `colores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `colores` VALUES (1,"negro","2024-10-21 05:52:13"),
(10,"blanco","2024-10-21 08:17:13"),
(11,"verde","2024-10-21 08:17:24"),
(12,"beige","2012-11-01 00:08:15"),
(13,"TUTIFRUTTY","2024-10-26 20:34:51");


DROP TABLE IF EXISTS `empleado`;

CREATE TABLE `empleado` (
  `cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `foto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empleado` VALUES (12393320,432141325,"Francisco","Arias",""),
(12847231,9012754,"Ricardo","Yepez Bastidas",""),
(1290481,432141325,"DOUGLAS JOSUE","DEL VALLE",""),
(23194809,9102375089,"Isaac","Bastidas",""),
(3174101,172089720,"Jota","Mejia",""),
(31744101,4247079098,"Jhoan","Mejia",""),
(40123456,409879,"Francisco","Martinez","vistas/img/usuarios//661.jpg");


DROP TABLE IF EXISTS `empresa`;

CREATE TABLE `empresa` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio_dolar` float NOT NULL,
  `impuesto` float NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empresa` VALUES ("j-1234087","Centro de Bocono","Kamil Styles",1385132,37,16);


DROP TABLE IF EXISTS `marcas`;

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marca` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `marcas` VALUES (10,"adidas","2024-10-21 08:18:24"),
(11,"pocholin","2024-10-21 08:18:31"),
(13,"rs21","2024-10-21 08:18:44"),
(14,"Aero","2012-11-01 00:07:27"),
(15,"generica","2012-11-01 00:07:35"),
(16,"acadia","2012-11-01 00:07:46"),
(17,"sifrina","2012-11-01 00:07:59"),
(18,"NIKE","2024-10-25 18:02:27");


DROP TABLE IF EXISTS `productos`;

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
  `precio_venta` float NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `id_tipo` (`id_tipo`),
  KEY `id_categoria` (`id_categoria`,`id_color`,`id_marca`),
  KEY `id_color` (`id_color`),
  KEY `id_marca` (`id_marca`),
  KEY `rif_proveedor` (`rif_proveedor`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_color`) REFERENCES `colores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_4` FOREIGN KEY (`id_tipo`) REFERENCES `tipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_5` FOREIGN KEY (`rif_proveedor`) REFERENCES `proveedores` (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `productos` VALUES (7,5,1,11,122319847,1010,"MANILLA","vistas/img/productos/default/anonymous.png",14,2,"2.8"),
(10,5,1,16,2151351235,1212,"FORZA XL","vistas/img/productos/1212/907.jpg",10,2,"2.8"),
(7,5,1,11,122319847,1234,"camisa","vistas/img/productos/default/anonymous.png",8,5,7),
(7,5,1,11,122319847,1235,"bota","vistas/img/productos/default/anonymous.png",9,15,21),
(10,5,1,11,122319847,12874,"TOLTO","vistas/img/productos/default/anonymous.png",10,12,"16.8"),
(10,5,1,11,122319847,2000,"RELOG","vistas/img/productos/default/anonymous.png",17,2,"2.8"),
(9,5,1,11,122319847,2141,"LKjdakl","vistas/img/productos/2141/351.jpg",13,12,"16.8"),
(9,5,1,11,122319847,214123,1523,"vistas/img/productos/default/anonymous.png",307,12,"16.8"),
(10,5,10,11,122319847,"214u1","BOTAS","vistas/img/productos/default/anonymous.png",9,12,"16.8"),
(10,8,13,18,2151351235,21512356,"COLLARES AESTHETIC","vistas/img/productos/default/anonymous.png",18,"2.55","3.57");


DROP TABLE IF EXISTS `proveedores`;

CREATE TABLE `proveedores` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cedula_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `correo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `proveedores` VALUES (122319847,"NIKE SPORT","Vanesa","Yepez","Caracas",128948912,921087,"vane@gmail.com"),
(2151351235,"TOTAL PANDA","Yanderson","Bastidas","Sotchi",1298412,291501235,"yan@gmail.com");


DROP TABLE IF EXISTS `tipos`;

CREATE TABLE `tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `tipos` VALUES (5,"caballero","2012-11-01 00:04:32"),
(6,"niño","2012-11-01 00:04:45"),
(7,"niña","2012-11-01 00:04:52"),
(8,"dama","2012-11-01 00:06:55");


DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `perfil` text COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL,
  PRIMARY KEY (`usuario`),
  KEY `cedula` (`cedula`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `usuarios` VALUES (23194809,"admin","$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG","Administrador",1),
(23194809,"francoarias81","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Administrador",1),
(40123456,"gsus","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Vendedor",0),
(31744101,"jotadevs","$2a$07$asxx54ahjppf45sd87a5auw6I7JoP8iFx7dMSn3sb4CatTnVVTCzi","Vendedor",1),
(12847231,"ricki","$2a$07$asxx54ahjppf45sd87a5auuA/wno05nzDP.bGv2s5tKCJKhZVQQgC","Administrador",1);


DROP TABLE IF EXISTS `venta_producto`;

CREATE TABLE `venta_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`),
  KEY `producto` (`producto`),
  CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `ventas` (`factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `venta_producto` VALUES (42,10003,2,2000),
(49,10004,1,21512356),
(50,10004,1,"214u1"),
(51,10004,1,214123),
(52,10004,1,2141),
(53,10005,1,214123),
(54,10006,1,21512356),
(55,10006,1,"214u1"),
(56,10006,1,214123),
(57,10007,1,21512356),
(58,10007,1,"214u1"),
(59,10007,1,214123),
(60,10007,1,2141),
(61,10007,1,2000),
(62,10008,1,21512356),
(63,10008,1,"214u1"),
(64,10008,1,214123),
(65,10008,1,2141),
(66,10009,1,21512356),
(67,10010,1,21512356),
(68,10010,1,"214u1"),
(69,10010,1,214123);


DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `factura` int(11) NOT NULL AUTO_INCREMENT,
  `rif_empresa` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `vendedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `total` float NOT NULL,
  `pago` float NOT NULL,
  `impuesto` float NOT NULL DEFAULT 16,
  `metodo_pago` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cliente` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `rif_empresa` (`rif_empresa`),
  KEY `cliente` (`cliente`),
  KEY `vendedor` (`vendedor`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`rif_empresa`) REFERENCES `empresa` (`rif`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10013 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `ventas` VALUES (10003,"j-1234087",23194809,"240.35",185,16,"Pago movil","2024-11-02 03:20:15",12400500),
(10004,"j-1234087",23194809,"2206.09",1,304,"Efectivo","2024-11-09 18:14:52",12400500),
(10005,"j-1234087",23194809,"721.056","621.6",99,"Efectivo","2024-11-09 18:15:31",12400500),
(10006,"j-1234087",23194809,"1485.03",1,204,"Efectivo","2024-11-09 18:16:26",12400500),
(10007,"j-1234087",23194809,"2326.26","2005.4",320,"Efectivo","2024-11-09 18:18:23",12400500),
(10008,"j-1234087",23194809,"2316.39","1996.89",319,"TJ-445656","2024-11-09 18:21:22",12400500),
(10009,"j-1234087",23194809,"153.224","132.09","21.1344","TJ-1234","2024-11-09 18:22:50",259103825),
(10010,"j-1234087",23194809,"1595.34","1375.29","220.05","Efectivo","2024-11-09 18:36:25",31744101);


SET foreign_key_checks = 1;
CREATE DATABASE IF NOT EXISTS `zapateriak`;

USE `zapateriak`;

SET foreign_key_checks = 0;

DROP TABLE IF EXISTS `apartado`;

CREATE TABLE `apartado` (
  `factura` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` int(11) NOT NULL,
  `vendedor` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `abonado` float NOT NULL,
  `impuesto` int(11) NOT NULL,
  `cliente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `vendedor` (`vendedor`,`cliente`),
  KEY `cliente` (`cliente`),
  CONSTRAINT `apartado_ibfk_1` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`),
  CONSTRAINT `apartado_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `apartado_producto`;

CREATE TABLE `apartado_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`,`producto`),
  KEY `producto` (`producto`),
  CONSTRAINT `apartado_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `apartado` (`factura`),
  CONSTRAINT `apartado_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `categorias` VALUES (7,"Ropa","2024-10-20 21:17:02"),
(9,"Calzados","2024-10-20 22:02:09"),
(10,"ACCESORIOS","2024-10-23 01:54:40"),
(12,"COMPONENTES","2024-10-30 01:38:07");


DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `apellido` text COLLATE utf8_spanish_ci NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `clientes` VALUES (12400500,"Ismael","Martinez","ismale@gmail.com",4127421201,"Tu casa"),
(259103825,"Vanessa","Teran","vane1q@gmail.com",2108974,"La loma"),
(31744101,"Jhoan","Mejia","mejiajhoan2021@gmail.com",4247079098,"Bisnaca"),
(31982314,"Esbeidy","Gil","esbeydui@gmail.com","Miticun",10987235),
(7689758,"Julio","Guanda","vane1q@gmail.com",4247079089,"Miticun"),
(908712984,"Julio","Carrasquero","vane@gmail.com",125123,"Miticun");


DROP TABLE IF EXISTS `colores`;

CREATE TABLE `colores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `colores` VALUES (1,"negro","2024-10-21 05:52:13"),
(10,"blanco","2024-10-21 08:17:13"),
(11,"verde","2024-10-21 08:17:24"),
(12,"beige","2012-11-01 00:08:15"),
(13,"TUTIFRUTTY","2024-10-26 20:34:51");


DROP TABLE IF EXISTS `empleado`;

CREATE TABLE `empleado` (
  `cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `foto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empleado` VALUES (12393320,432141325,"Francisco","Arias",""),
(12847231,9012754,"Ricardo","Yepez Bastidas",""),
(1290481,432141325,"DOUGLAS JOSUE","DEL VALLE",""),
(23194809,9102375089,"Isaac","Bastidas",""),
(3174101,172089720,"Jota","Mejia",""),
(31744101,4247079098,"Jhoan","Mejia",""),
(40123456,409879,"Francisco","Martinez","vistas/img/usuarios//661.jpg");


DROP TABLE IF EXISTS `empresa`;

CREATE TABLE `empresa` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio_dolar` float NOT NULL,
  `impuesto` float NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empresa` VALUES ("j-1234087","Centro de Bocono","Kamil Styles",1385132,37,16);


DROP TABLE IF EXISTS `marcas`;

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marca` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `marcas` VALUES (10,"adidas","2024-10-21 08:18:24"),
(11,"pocholin","2024-10-21 08:18:31"),
(13,"rs21","2024-10-21 08:18:44"),
(14,"Aero","2012-11-01 00:07:27"),
(15,"generica","2012-11-01 00:07:35"),
(16,"acadia","2012-11-01 00:07:46"),
(17,"sifrina","2012-11-01 00:07:59"),
(18,"NIKE","2024-10-25 18:02:27");


DROP TABLE IF EXISTS `productos`;

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
  `precio_venta` float NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `id_tipo` (`id_tipo`),
  KEY `id_categoria` (`id_categoria`,`id_color`,`id_marca`),
  KEY `id_color` (`id_color`),
  KEY `id_marca` (`id_marca`),
  KEY `rif_proveedor` (`rif_proveedor`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_color`) REFERENCES `colores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_4` FOREIGN KEY (`id_tipo`) REFERENCES `tipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_5` FOREIGN KEY (`rif_proveedor`) REFERENCES `proveedores` (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `productos` VALUES (7,5,1,11,122319847,1010,"MANILLA","vistas/img/productos/default/anonymous.png",14,2,"2.8"),
(10,5,1,16,2151351235,1212,"FORZA XL","vistas/img/productos/1212/907.jpg",10,2,"2.8"),
(7,5,1,11,122319847,1234,"camisa","vistas/img/productos/default/anonymous.png",8,5,7),
(7,5,1,11,122319847,1235,"bota","vistas/img/productos/default/anonymous.png",9,15,21),
(10,5,1,11,122319847,12874,"TOLTO","vistas/img/productos/default/anonymous.png",10,12,"16.8"),
(10,5,1,11,122319847,2000,"RELOG","vistas/img/productos/default/anonymous.png",17,2,"2.8"),
(9,5,1,11,122319847,2141,"LKjdakl","vistas/img/productos/2141/351.jpg",13,12,"16.8"),
(9,5,1,11,122319847,214123,1523,"vistas/img/productos/default/anonymous.png",307,12,"16.8"),
(10,5,10,11,122319847,"214u1","BOTAS","vistas/img/productos/default/anonymous.png",9,12,"16.8"),
(10,8,13,18,2151351235,21512356,"COLLARES AESTHETIC","vistas/img/productos/default/anonymous.png",18,"2.55","3.57");


DROP TABLE IF EXISTS `proveedores`;

CREATE TABLE `proveedores` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cedula_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `correo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `proveedores` VALUES (122319847,"NIKE SPORT","Vanesa","Yepez","Caracas",128948912,921087,"vane@gmail.com"),
(2151351235,"TOTAL PANDA","Yanderson","Bastidas","Sotchi",1298412,291501235,"yan@gmail.com");


DROP TABLE IF EXISTS `tipos`;

CREATE TABLE `tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `tipos` VALUES (5,"caballero","2012-11-01 00:04:32"),
(6,"niño","2012-11-01 00:04:45"),
(7,"niña","2012-11-01 00:04:52"),
(8,"dama","2012-11-01 00:06:55");


DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `perfil` text COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL,
  PRIMARY KEY (`usuario`),
  KEY `cedula` (`cedula`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `usuarios` VALUES (23194809,"admin","$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG","Administrador",1),
(23194809,"francoarias81","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Administrador",1),
(40123456,"gsus","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Vendedor",0),
(31744101,"jotadevs","$2a$07$asxx54ahjppf45sd87a5auw6I7JoP8iFx7dMSn3sb4CatTnVVTCzi","Vendedor",1),
(12847231,"ricki","$2a$07$asxx54ahjppf45sd87a5auuA/wno05nzDP.bGv2s5tKCJKhZVQQgC","Administrador",1);


DROP TABLE IF EXISTS `venta_producto`;

CREATE TABLE `venta_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`),
  KEY `producto` (`producto`),
  CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `ventas` (`factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `venta_producto` VALUES (42,10003,2,2000),
(49,10004,1,21512356),
(50,10004,1,"214u1"),
(51,10004,1,214123),
(52,10004,1,2141),
(53,10005,1,214123),
(54,10006,1,21512356),
(55,10006,1,"214u1"),
(56,10006,1,214123),
(57,10007,1,21512356),
(58,10007,1,"214u1"),
(59,10007,1,214123),
(60,10007,1,2141),
(61,10007,1,2000),
(62,10008,1,21512356),
(63,10008,1,"214u1"),
(64,10008,1,214123),
(65,10008,1,2141),
(66,10009,1,21512356),
(67,10010,1,21512356),
(68,10010,1,"214u1"),
(69,10010,1,214123);


DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `factura` int(11) NOT NULL AUTO_INCREMENT,
  `rif_empresa` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `vendedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `total` float NOT NULL,
  `pago` float NOT NULL,
  `impuesto` float NOT NULL DEFAULT 16,
  `metodo_pago` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `cliente` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `rif_empresa` (`rif_empresa`),
  KEY `cliente` (`cliente`),
  KEY `vendedor` (`vendedor`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`rif_empresa`) REFERENCES `empresa` (`rif`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10013 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `ventas` VALUES (10003,"j-1234087",23194809,"240.35",185,16,"Pago movil","2024-11-02 03:20:15",12400500),
(10004,"j-1234087",23194809,"2206.09",1,304,"Efectivo","2024-11-09 18:14:52",12400500),
(10005,"j-1234087",23194809,"721.056","621.6",99,"Efectivo","2024-11-09 18:15:31",12400500),
(10006,"j-1234087",23194809,"1485.03",1,204,"Efectivo","2024-11-09 18:16:26",12400500),
(10007,"j-1234087",23194809,"2326.26","2005.4",320,"Efectivo","2024-11-09 18:18:23",12400500),
(10008,"j-1234087",23194809,"2316.39","1996.89",319,"TJ-445656","2024-11-09 18:21:22",12400500),
(10009,"j-1234087",23194809,"153.224","132.09","21.1344","TJ-1234","2024-11-09 18:22:50",259103825),
(10010,"j-1234087",23194809,"1595.34","1375.29","220.05","Efectivo","2024-11-09 18:36:25",31744101);


SET foreign_key_checks = 1;
CREATE DATABASE IF NOT EXISTS `zapateriak`;

USE `zapateriak`;

SET foreign_key_checks = 0;

DROP TABLE IF EXISTS `apartado`;

CREATE TABLE `apartado` (
  `factura` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` int(11) NOT NULL,
  `vendedor` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `abonado` float NOT NULL,
  `impuesto` int(11) NOT NULL,
  `cliente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `vendedor` (`vendedor`,`cliente`),
  KEY `cliente` (`cliente`),
  CONSTRAINT `apartado_ibfk_1` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`),
  CONSTRAINT `apartado_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `apartado_producto`;

CREATE TABLE `apartado_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`,`producto`),
  KEY `producto` (`producto`),
  CONSTRAINT `apartado_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `apartado` (`factura`),
  CONSTRAINT `apartado_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `categorias` VALUES (7,"Ropa","2024-10-20 21:17:02"),
(9,"Calzados","2024-10-20 22:02:09"),
(10,"ACCESORIOS","2024-10-23 01:54:40"),
(12,"COMPONENTES","2024-10-30 01:38:07");


DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `apellido` text COLLATE utf8_spanish_ci NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `clientes` VALUES (12400500,"Ismael","Martinez","ismale@gmail.com",4127421201,"Tu casa"),
(259103825,"Vanessa","Teran","vane1q@gmail.com",2108974,"La loma"),
(30124678,"jose angel","cabeza arias","jose@gmail.com",04120630245,"av sucre"),
(31744101,"Jhoan","Mejia","mejiajhoan2021@gmail.com",4247079098,"Bisnaca"),
(31982314,"Esbeidy","Gil","esbeydui@gmail.com","Miticun",10987235),
(7689758,"Julio","Guanda","vane1q@gmail.com",4247079089,"Miticun"),
(908712984,"Julio","Carrasquero","vane@gmail.com",125123,"Miticun");


DROP TABLE IF EXISTS `colores`;

CREATE TABLE `colores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `colores` VALUES (1,"negro","2024-10-21 05:52:13"),
(10,"blanco","2024-10-21 08:17:13"),
(11,"verde","2024-10-21 08:17:24"),
(12,"beige","2012-11-01 00:08:15"),
(13,"TUTIFRUTTY","2024-10-26 20:34:51");


DROP TABLE IF EXISTS `empleado`;

CREATE TABLE `empleado` (
  `cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `foto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empleado` VALUES (1000000,121212,"super","usuario","bocono",""),
(121312353,2151235,"Joan","Bastidas","Caracas",""),
(12393320,432141325,"Francisco","Arias","",""),
(12847231,9012754,"Ricardo","Yepez Bastidas","",""),
(1290481,432141325,"DOUGLAS JOSUE","DEL VALLE","",""),
(23194809,9102375089,"Isaac","Bastidas","",""),
(3174101,172089720,"Jota","Mejia","",""),
(31744100,04247714244,"Jhoan","Mejia","Bisnaca",""),
(31744101,4247079098,"Jhoan","Mejia","El carmen",""),
(40123456,409879,"Francisco","Martinez","Rincon 3","vistas/img/usuarios//661.jpg");


DROP TABLE IF EXISTS `empresa`;

CREATE TABLE `empresa` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio_dolar` float NOT NULL,
  `impuesto` float NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empresa` VALUES ("j-1234087","Centro de Bocono","Kamil Styles",1385132,37,16);


DROP TABLE IF EXISTS `marcas`;

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marca` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `marcas` VALUES (10,"adidas","2024-10-21 08:18:24"),
(11,"pocholin","2024-10-21 08:18:31"),
(13,"rs21","2024-10-21 08:18:44"),
(14,"Aero","2012-11-01 00:07:27"),
(15,"generica","2012-11-01 00:07:35"),
(16,"acadia","2012-11-01 00:07:46"),
(17,"sifrina","2012-11-01 00:07:59"),
(18,"NIKE","2024-10-25 18:02:27");


DROP TABLE IF EXISTS `productos`;

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
  `precio_venta` float NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `id_tipo` (`id_tipo`),
  KEY `id_categoria` (`id_categoria`,`id_color`,`id_marca`),
  KEY `id_color` (`id_color`),
  KEY `id_marca` (`id_marca`),
  KEY `rif_proveedor` (`rif_proveedor`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_color`) REFERENCES `colores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_4` FOREIGN KEY (`id_tipo`) REFERENCES `tipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_5` FOREIGN KEY (`rif_proveedor`) REFERENCES `proveedores` (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `productos` VALUES (7,5,1,11,122319847,1010,"MANILLA","vistas/img/productos/default/anonymous.png",14,2,"2.8"),
(10,5,1,16,2151351235,1212,"FORZA XL","vistas/img/productos/1212/907.jpg",10,2,"2.8"),
(7,5,1,11,122319847,1234,"camisa","vistas/img/productos/default/anonymous.png",8,5,7),
(7,5,1,11,122319847,1235,"bota","vistas/img/productos/default/anonymous.png",9,15,21),
(10,5,1,11,122319847,12874,"TOLTO","vistas/img/productos/default/anonymous.png",10,12,"16.8"),
(10,5,1,11,122319847,2000,"RELOG","vistas/img/productos/default/anonymous.png",16,2,"2.8"),
(9,5,1,11,122319847,2141,"LKjdakl","vistas/img/productos/2141/351.jpg",12,12,"16.8"),
(9,5,1,11,122319847,214123,1523,"vistas/img/productos/default/anonymous.png",299,12,"16.8"),
(10,5,10,11,122319847,"214u1","BOTAS","vistas/img/productos/default/anonymous.png",2,12,"16.8"),
(10,8,13,18,2151351235,21512356,"COLLARES AESTHETIC","vistas/img/productos/default/anonymous.png",9,2,"2.6");


DROP TABLE IF EXISTS `proveedores`;

CREATE TABLE `proveedores` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cedula_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `correo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `proveedores` VALUES (122319847,"NIKE SPORT","Vanesa","Yepez","Caracas",128948912,921087,"vane@gmail.com"),
(2151351235,"TOTAL PANDA","Yanderson","Bastidas","Sotchi",1298412,291501235,"yan@gmail.com");


DROP TABLE IF EXISTS `tipos`;

CREATE TABLE `tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `tipos` VALUES (5,"caballero","2012-11-01 00:04:32"),
(6,"niño","2012-11-01 00:04:45"),
(7,"niña","2012-11-01 00:04:52"),
(8,"dama","2012-11-01 00:06:55");


DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `perfil` text COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL,
  PRIMARY KEY (`usuario`),
  KEY `cedula` (`cedula`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `usuarios` VALUES (1000000,"admin","$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG","Administrador",1),
(1290481,"fesal79","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Vendedor",1),
(12393320,"francoarias81","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Administrador",1),
(40123456,"gsus","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Vendedor",1),
(23194809,"isa","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Vendedor",1),
(12847231,"ricki","$2a$07$asxx54ahjppf45sd87a5auuA/wno05nzDP.bGv2s5tKCJKhZVQQgC","Administrador",1);


DROP TABLE IF EXISTS `venta_producto`;

CREATE TABLE `venta_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`),
  KEY `producto` (`producto`),
  CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `ventas` (`factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=96 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `venta_producto` VALUES (70,10001,1,21512356),
(71,10002,1,21512356),
(72,10002,1,"214u1"),
(73,10002,1,214123),
(77,10003,1,21512356),
(78,10003,1,"214u1"),
(79,10003,1,214123),
(80,10004,1,21512356),
(81,10004,1,"214u1"),
(82,10004,1,214123),
(83,10005,1,21512356),
(84,10006,1,214123),
(85,10007,1,2141),
(86,10008,1,"214u1"),
(87,10009,1,214123),
(88,10010,1,21512356),
(89,10011,1,2000),
(90,10012,1,21512356),
(91,10012,1,"214u1"),
(92,10012,1,214123),
(93,10013,1,21512356),
(94,10013,1,"214u1"),
(95,10013,1,214123);


DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `factura` int(11) NOT NULL AUTO_INCREMENT,
  `rif_empresa` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `vendedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `total` float NOT NULL,
  `pago` float NOT NULL,
  `impuesto` float NOT NULL DEFAULT '16',
  `metodo_pago` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cliente` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `rif_empresa` (`rif_empresa`),
  KEY `cliente` (`cliente`),
  KEY `vendedor` (`vendedor`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`rif_empresa`) REFERENCES `empresa` (`rif`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10014 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `ventas` VALUES (10001,"j-1234087",23194809,"153.224","132.09","21.13","Tarjeta-1234","2024-11-10 11:38:33",12400500),
(10002,"j-1234087",23194809,"1595.34","1375.29","220.05","Tarjeta-1234","2024-11-10 11:43:24",30124678),
(10003,"j-1234087",23194809,"1595.34","1375.29","220.05","Efectivo","2024-11-10 12:05:21",12400500),
(10004,"j-1234087",23194809,"1595.34","1375.29","220.05","Tarjeta-1234","2024-11-10 12:32:44",908712984),
(10005,"j-1234087",23194809,"111.592","96.2","15.39","Efectivo","2024-11-10 14:03:46",12400500),
(10006,"j-1234087",23194809,"721.056","621.6","99.46","Tarjeta-1","2024-11-10 14:04:07",12400500),
(10007,"j-1234087",23194809,"721.056","621.6","99.46","Tarjeta-2","2024-11-10 14:04:28",259103825),
(10008,"j-1234087",23194809,"721.056","621.6","99.46","Tarjeta-2","2024-11-10 14:04:45",259103825),
(10009,"j-1234087",23194809,"721.056","621.6","99.46","Efectivo","2024-11-10 14:05:05",259103825),
(10010,"j-1234087",23194809,"111.592","96.2","15.39","Efectivo","2024-11-10 14:05:34",12400500),
(10011,"j-1234087",23194809,"120.176","103.6","16.58","Tarjeta-2","2024-11-10 14:05:51",31744101),
(10012,"j-1234087",23194809,"1553.7","1339.4","214.3","Tarjeta-1234","2024-11-10 14:09:23",31744101),
(10013,"j-1234087",23194809,"1553.7","1339.4","214.3","Tarjeta-1234","2024-11-10 14:10:30",259103825);


SET foreign_key_checks = 1;
CREATE DATABASE IF NOT EXISTS `zapateriak`;

USE `zapateriak`;

SET foreign_key_checks = 0;

DROP TABLE IF EXISTS `apartado`;

CREATE TABLE `apartado` (
  `factura` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` int(11) NOT NULL,
  `vendedor` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `abonado` float NOT NULL,
  `impuesto` int(11) NOT NULL,
  `cliente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `vendedor` (`vendedor`,`cliente`),
  KEY `cliente` (`cliente`),
  CONSTRAINT `apartado_ibfk_1` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`),
  CONSTRAINT `apartado_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `apartado_producto`;

CREATE TABLE `apartado_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`,`producto`),
  KEY `producto` (`producto`),
  CONSTRAINT `apartado_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `apartado` (`factura`),
  CONSTRAINT `apartado_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `categorias` VALUES (7,"Ropa","2024-10-20 21:17:02"),
(9,"Calzados","2024-10-20 22:02:09"),
(10,"ACCESORIOS","2024-10-23 01:54:40"),
(12,"COMPONENTES","2024-10-30 01:38:07");


DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `apellido` text COLLATE utf8_spanish_ci NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `clientes` VALUES (12400500,"Ismael","Martinez","noposee@gmail.com",04127421201,"Tu casa"),
(259103825,"Vanessa","Teran","vane1q@gmail.com",2108974,"La loma"),
(30124678,"jose angel","cabeza arias","jose@gmail.com",04120630245,"av sucre"),
(31744101,"Jhoan","Mejia","mejiajhoan2021@gmail.com",4247079098,"Bisnaca"),
(31982314,"Esbeidy","Gil","esbeydui@gmail.com","Miticun",10987235),
(7689758,"Julio","Guanda","vane1q@gmail.com",4247079089,"Miticun");


DROP TABLE IF EXISTS `colores`;

CREATE TABLE `colores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `colores` VALUES (1,"negro","2024-10-21 05:52:13"),
(10,"blanco","2024-10-21 08:17:13"),
(11,"verde","2024-10-21 08:17:24"),
(12,"beige","2012-11-01 00:08:15"),
(13,"TUTIFRUTTY","2024-10-26 20:34:51");


DROP TABLE IF EXISTS `empleado`;

CREATE TABLE `empleado` (
  `cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `foto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empleado` VALUES (1000000,121212,"super","usuario","bocono","");


DROP TABLE IF EXISTS `empresa`;

CREATE TABLE `empresa` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(80) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio_dolar` float NOT NULL,
  `impuesto` float NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empresa` VALUES ("J-221234087","Centro de Bocono","Kamyl Styl y Algo mas de Susana Al chariti",04241321222,96,16);


DROP TABLE IF EXISTS `marcas`;

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marca` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `marcas` VALUES (10,"adidas","2024-10-21 08:18:24"),
(11,"pocholin","2024-10-21 08:18:31"),
(13,"rs21","2024-10-21 08:18:44"),
(14,"Aero","2012-11-01 00:07:27"),
(15,"generica","2012-11-01 00:07:35"),
(16,"acadia","2012-11-01 00:07:46"),
(17,"sifrina","2012-11-01 00:07:59"),
(18,"NIKE","2024-10-25 18:02:27"),
(19,"hjk","2025-05-31 16:35:59");


DROP TABLE IF EXISTS `productos`;

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
  `precio_venta` float NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `id_tipo` (`id_tipo`),
  KEY `id_categoria` (`id_categoria`,`id_color`,`id_marca`),
  KEY `id_color` (`id_color`),
  KEY `id_marca` (`id_marca`),
  KEY `rif_proveedor` (`rif_proveedor`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_color`) REFERENCES `colores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_4` FOREIGN KEY (`id_tipo`) REFERENCES `tipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_5` FOREIGN KEY (`rif_proveedor`) REFERENCES `proveedores` (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `productos` VALUES (7,5,1,11,122319847,1010,"MANILLA","vistas/img/productos/default/anonymous.png",14,2,"2.8"),
(10,5,1,16,2151351235,1212,"FORZA XL","vistas/img/productos/1212/907.jpg",10,2,"2.8"),
(7,5,1,11,122319847,1234,"camisa","vistas/img/productos/default/anonymous.png",8,5,7),
(7,5,1,11,122319847,1235,"bota","vistas/img/productos/default/anonymous.png",9,15,21),
(10,5,1,11,122319847,12874,"TOLTO","vistas/img/productos/default/anonymous.png",10,12,"16.8"),
(10,5,1,11,122319847,2000,"RELOG","vistas/img/productos/default/anonymous.png",15,2,"2.8"),
(9,5,1,11,122319847,2141,"LKjdakl","vistas/img/productos/2141/351.jpg",10,12,"16.8"),
(9,5,1,11,122319847,214123,1523,"vistas/img/productos/default/anonymous.png",296,12,"16.8"),
(10,5,10,11,122319847,"214u1","BOTAS","vistas/img/productos/default/anonymous.png",0,12,"16.8"),
(10,8,13,18,2151351235,21512356,"COLLARES","vistas/img/productos/default/anonymous.png",4,2,"2.6");


DROP TABLE IF EXISTS `proveedores`;

CREATE TABLE `proveedores` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cedula_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `correo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `proveedores` VALUES (122319847,"NIKE SPORT","Vanesa","Yepez","Caracas",128948912,921087,"vane@gmail.com"),
(2151351235,"TOTAL PANDA","Yanderson","Bastidas","Sotchi",1298412,291501235,"yan@gmail.com");


DROP TABLE IF EXISTS `tipos`;

CREATE TABLE `tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `tipos` VALUES (5,"caballero","2012-11-01 00:04:32"),
(6,"niño","2012-11-01 00:04:45"),
(7,"niña","2012-11-01 00:04:52"),
(8,"dama","2012-11-01 00:06:55");


DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `perfil` text COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL,
  PRIMARY KEY (`usuario`),
  KEY `cedula` (`cedula`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `usuarios` VALUES (1000000,"admin","$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG","Administrador",1);


DROP TABLE IF EXISTS `venta_producto`;

CREATE TABLE `venta_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`),
  KEY `producto` (`producto`),
  CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `ventas` (`factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `factura` int(11) NOT NULL AUTO_INCREMENT,
  `rif_empresa` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `vendedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `total` float NOT NULL,
  `pago` float NOT NULL,
  `impuesto` float NOT NULL DEFAULT '16',
  `metodo_pago` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cliente` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `rif_empresa` (`rif_empresa`),
  KEY `cliente` (`cliente`),
  KEY `vendedor` (`vendedor`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`rif_empresa`) REFERENCES `empresa` (`rif`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;



SET foreign_key_checks = 1;
CREATE DATABASE IF NOT EXISTS `zapateriak`;

USE `zapateriak`;

SET foreign_key_checks = 0;

DROP TABLE IF EXISTS `apartado`;

CREATE TABLE `apartado` (
  `factura` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` int(11) NOT NULL,
  `vendedor` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `abonado` float NOT NULL,
  `impuesto` int(11) NOT NULL,
  `cliente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `vendedor` (`vendedor`,`cliente`),
  KEY `cliente` (`cliente`),
  CONSTRAINT `apartado_ibfk_1` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`),
  CONSTRAINT `apartado_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `apartado_producto`;

CREATE TABLE `apartado_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`,`producto`),
  KEY `producto` (`producto`),
  CONSTRAINT `apartado_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `apartado` (`factura`),
  CONSTRAINT `apartado_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `categorias` VALUES (7,"Ropa","2024-10-20 21:17:02"),
(9,"Calzados","2024-10-20 22:02:09"),
(10,"ACCESORIOS","2024-10-23 01:54:40"),
(12,"COMPONENTES","2024-10-30 01:38:07");


DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `apellido` text COLLATE utf8_spanish_ci NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;



DROP TABLE IF EXISTS `colores`;

CREATE TABLE `colores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;



DROP TABLE IF EXISTS `empleado`;

CREATE TABLE `empleado` (
  `cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `foto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empleado` VALUES (12393320,432141325,"Francisco","Arias",""),
(12847231,9012754,"Ricardo","Yepez Bastidas",""),
(1290481,432141325,"DOUGLAS JOSUE","DEL VALLE",""),
(23194809,9102375089,"Isaac","Bastidas",""),
(3174101,172089720,"Jota","Mejia",""),
(31744101,4247079098,"Jhoan","Mejia",""),
(40123456,409879,"Francisco","Martinez","vistas/img/usuarios//661.jpg");


DROP TABLE IF EXISTS `empresa`;

CREATE TABLE `empresa` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(80) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio_dolar` float NOT NULL,
  `impuesto` float NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empresa` VALUES ("j-1234087","Centro de Bocono","Kamyl Styles y Algo Mas de Susana Alchariti",1385132,"44.05",16);


DROP TABLE IF EXISTS `marcas`;

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marca` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `marcas` VALUES (10,"adidas","2024-10-21 08:18:24"),
(11,"pocholin","2024-10-21 08:18:31"),
(13,"rs21","2024-10-21 08:18:44"),
(14,"Aero","2012-11-01 00:07:27"),
(15,"generica","2012-11-01 00:07:35"),
(16,"acadia","2012-11-01 00:07:46"),
(17,"sifrina","2012-11-01 00:07:59"),
(18,"NIKE","2024-10-25 18:02:27");


DROP TABLE IF EXISTS `productos`;

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
  `precio_venta` float NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `id_tipo` (`id_tipo`),
  KEY `id_categoria` (`id_categoria`,`id_color`,`id_marca`),
  KEY `id_color` (`id_color`),
  KEY `id_marca` (`id_marca`),
  KEY `rif_proveedor` (`rif_proveedor`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_color`) REFERENCES `colores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_4` FOREIGN KEY (`id_tipo`) REFERENCES `tipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_5` FOREIGN KEY (`rif_proveedor`) REFERENCES `proveedores` (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `productos` VALUES (7,5,1,11,122319847,1010,"MANILLA","vistas/img/productos/default/anonymous.png",11,2,"2.8"),
(10,5,1,16,2151351235,1212,"FORZA XL","vistas/img/productos/1212/907.jpg",6,2,"2.8"),
(7,5,1,11,122319847,1234,"camisa","vistas/img/productos/default/anonymous.png",6,5,7),
(7,5,1,11,122319847,1235,"bota","vistas/img/productos/default/anonymous.png",5,15,21),
(10,5,1,11,122319847,12874,"TOLTO","vistas/img/productos/default/anonymous.png",6,12,"16.8"),
(10,5,1,11,122319847,2000,"RELOG","vistas/img/productos/default/anonymous.png",14,2,"2.8"),
(9,5,1,11,122319847,2141,"LKjdakl","vistas/img/productos/2141/351.jpg",9,12,"16.8"),
(9,5,1,11,122319847,214123,1523,"vistas/img/productos/default/anonymous.png",305,12,"16.8"),
(10,5,10,11,122319847,"214u1","BOTAS","vistas/img/productos/default/anonymous.png",3,12,"16.8"),
(10,8,13,18,2151351235,21512356,"COLLARES AESTHETIC","vistas/img/productos/default/anonymous.png",9,2,1);


DROP TABLE IF EXISTS `proveedores`;

CREATE TABLE `proveedores` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cedula_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `correo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `proveedores` VALUES (122319847,"NIKE SPORT","Vanesa","Yepez","Caracas",128948912,921087,"vane@gmail.com"),
(2151351235,"TOTAL PANDA","Yanderson","Bastidas","Sotchi",1298412,291501235,"yan@gmail.com");


DROP TABLE IF EXISTS `tipos`;

CREATE TABLE `tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `tipos` VALUES (5,"caballero","2012-11-01 00:04:32"),
(6,"niño","2012-11-01 00:04:45"),
(7,"niña","2012-11-01 00:04:52"),
(8,"dama","2012-11-01 00:06:55");


DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `perfil` text COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL,
  PRIMARY KEY (`usuario`),
  KEY `cedula` (`cedula`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `usuarios` VALUES (23194809,"admin","$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG","Administrador",1),
(23194809,"francoarias81","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Administrador",1),
(40123456,"gsus","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Vendedor",1),
(31744101,"jotadevs","$2a$07$asxx54ahjppf45sd87a5auw6I7JoP8iFx7dMSn3sb4CatTnVVTCzi","Vendedor",1),
(12847231,"ricki","$2a$07$asxx54ahjppf45sd87a5auuA/wno05nzDP.bGv2s5tKCJKhZVQQgC","Administrador",1);


DROP TABLE IF EXISTS `venta_producto`;

CREATE TABLE `venta_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`),
  KEY `producto` (`producto`),
  CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `ventas` (`factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `venta_producto` VALUES (38,10001,1,1212),
(39,10002,1,1212),
(40,10002,2,1010),
(41,10002,2,21512356),
(42,10003,2,2000),
(43,10004,1,12874),
(44,10005,1,21512356),
(45,10005,1,"214u1"),
(46,10006,1,21512356),
(47,10006,1,"214u1"),
(48,10006,1,214123),
(49,10006,1,2141),
(50,10006,1,2000),
(51,10006,1,12874),
(52,10006,1,1235),
(53,10006,1,1234),
(54,10006,1,1212),
(55,10006,1,1010),
(56,10007,1,"214u1"),
(57,10008,1,214123),
(58,10009,1,214123),
(59,10010,1,2141),
(60,10011,1,1010),
(61,10012,1,1235),
(62,10012,1,12874),
(63,10012,1,2000),
(64,10013,1,2000),
(65,10014,1,12874),
(66,10015,1,1235),
(67,10016,1,21512356),
(68,10016,1,"214u1"),
(69,10017,1,2141),
(70,10017,1,214123),
(71,10017,1,"214u1"),
(72,10017,1,21512356),
(73,10018,1,21512356),
(74,10018,1,"214u1"),
(75,10018,1,214123),
(76,10019,1,21512356),
(77,10019,1,"214u1"),
(78,10019,1,2141),
(79,10019,1,2000),
(80,10020,1,21512356),
(81,10020,1,"214u1"),
(82,10020,1,214123),
(83,10021,1,214123),
(84,10021,1,2141),
(85,10022,1,21512356),
(86,10023,1,"214u1"),
(87,10024,1,21512356),
(88,10024,1,21512356),
(89,10025,1,21512356),
(90,10025,1,"214u1"),
(91,10025,1,214123),
(92,10025,1,2141),
(93,10026,1,"214u1"),
(94,10026,1,21512356),
(95,10027,1,1212),
(96,10028,1,21512356),
(97,10029,1,21512356),
(98,10030,1,1010),
(99,10030,1,1212),
(100,10030,1,1234),
(101,10031,1,21512356),
(102,10031,1,"214u1"),
(103,10032,1,21512356),
(104,10032,1,"214u1"),
(105,10032,1,214123),
(106,10032,1,2141),
(107,10033,1,21512356),
(108,10033,1,214123),
(109,10034,1,1212),
(110,10034,1,1235);


DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `factura` int(11) NOT NULL AUTO_INCREMENT,
  `rif_empresa` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `vendedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `total` float NOT NULL,
  `pago` float NOT NULL,
  `impuesto` int(11) NOT NULL DEFAULT '16',
  `metodo_pago` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cliente` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `rif_empresa` (`rif_empresa`),
  KEY `cliente` (`cliente`),
  KEY `vendedor` (`vendedor`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`rif_empresa`) REFERENCES `empresa` (`rif`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10035 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `ventas` VALUES (10001,"j-1234087",23194809,"120.18",100,16,"Transferencia","2024-11-02 03:16:58",12393100),
(10002,"j-1234087",23194809,"446.37",500,16,"Targeta","2024-11-02 03:19:36",12393100),
(10003,"j-1234087",23194809,"240.35",185,16,"Pago movil","2024-11-02 03:20:15",12400500),
(10004,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:19:32",12393100),
(10005,"j-1234087",23194809,"763.98","763.98",16,"Efectivo","2024-11-06 04:20:20",12393100),
(10006,"j-1234087",23194809,4,4,16,"Efectivo","2024-11-06 04:21:59",12400500),
(10007,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:22:38",12393100),
(10008,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:23:16",12393100),
(10009,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:24:39",12393100),
(10010,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:25:03",12400500),
(10011,"j-1234087",23194809,"120.18","120.18",16,"Efectivo","2024-11-06 04:25:28",12393100),
(10012,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:26:06",12400500),
(10013,"j-1234087",23194809,"120.18","120.18",16,"Efectivo","2024-11-06 04:26:39",12393100),
(10014,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:27:11",12400500),
(10015,"j-1234087",23194809,"901.32","901.32",16,"Targeta","2024-11-06 04:27:43",12393100),
(10016,"j-1234087",23194809,"763.98","763.98",16,"Efectivo","2024-11-06 04:28:22",12393100),
(10017,"j-1234087",23194809,2,2,16,"Efectivo","2024-11-06 04:30:15",12393100),
(10018,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:31:06",12393100),
(10019,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:31:54",12393100),
(10020,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:34:03",12393100),
(10021,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:34:37",12400500),
(10022,"j-1234087",23194809,"42.92","42.92",16,"Efectivo","2024-11-06 04:35:40",12400500),
(10023,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:37:53",908712984),
(10024,"j-1234087",23194809,"85.84","85.84",16,"Efectivo","2024-11-06 04:38:41",31982314),
(10025,"j-1234087",23194809,2,2,16,"Efectivo","2024-11-06 04:39:43",12393100),
(10026,"j-1234087",23194809,"763.98","763.98",16,"Efectivo","2024-11-06 04:41:06",12393100),
(10027,"j-1234087",23194809,"120.18","120.18",16,"Efectivo","2024-11-06 05:06:36",12393100),
(10028,"j-1234087",23194809,"42.92","42.92",16,"Efectivo","2024-11-06 05:50:08",12400500),
(10029,"j-1234087",23194809,"47.56","47.56",16,"Efectivo","2024-11-06 07:10:00",12393100),
(10030,"j-1234087",23194809,"599.26","599.26",16,"Efectivo","2024-11-06 07:13:11",12400500),
(10031,"j-1234087",23194809,"846.57","846.57",16,"Targeta","2012-11-01 00:05:57",12393100),
(10032,"j-1234087",23194809,2,2,16,"Pago movil","2012-11-01 00:07:12",12393100),
(10033,"j-1234087",40123456,"846.57","846.57",16,"Efectivo","2012-11-01 00:11:48",259103825),
(10034,"j-1234087",23194809,1,1,16,"Transferencia","2012-11-01 00:44:43",259103825);


SET foreign_key_checks = 1;
CREATE DATABASE IF NOT EXISTS `zapateriak`;

USE `zapateriak`;

SET foreign_key_checks = 0;

DROP TABLE IF EXISTS `apartado`;

CREATE TABLE `apartado` (
  `factura` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` int(11) NOT NULL,
  `vendedor` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `abonado` float NOT NULL,
  `impuesto` int(11) NOT NULL,
  `cliente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `vendedor` (`vendedor`,`cliente`),
  KEY `cliente` (`cliente`),
  CONSTRAINT `apartado_ibfk_1` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`),
  CONSTRAINT `apartado_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `apartado_producto`;

CREATE TABLE `apartado_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`,`producto`),
  KEY `producto` (`producto`),
  CONSTRAINT `apartado_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `apartado` (`factura`),
  CONSTRAINT `apartado_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `categorias` VALUES (7,"Ropa","2024-10-20 21:17:02"),
(9,"Calzados","2024-10-20 22:02:09"),
(10,"ACCESORIOS","2024-10-23 01:54:40"),
(12,"COMPONENTES","2024-10-30 01:38:07");


DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `apellido` text COLLATE utf8_spanish_ci NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `clientes` VALUES ("V-12129999","francis","arias","francoarias81@gmail.com","0414-1212121","av- sucre 1-11"),
("V-14600272","javier","valdez","francoarias81@gmail.com","0272-1212121","av- sucre 1-10");


DROP TABLE IF EXISTS `colores`;

CREATE TABLE `colores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `colores` VALUES (1,"negro","2024-10-21 05:52:13"),
(10,"blanco","2024-10-21 08:17:13"),
(11,"verde","2024-10-21 08:17:24"),
(12,"beige","2012-11-01 00:08:15"),
(13,"TUTIFRUTTY","2024-10-26 20:34:51");


DROP TABLE IF EXISTS `empleado`;

CREATE TABLE `empleado` (
  `cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `foto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empleado` VALUES (1000000,121212,"super","usuario","bocono",""),
("V-14600272","0414-1074586","franco","ARIAS","av-sucre 1-11","");


DROP TABLE IF EXISTS `empresa`;

CREATE TABLE `empresa` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(80) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio_dolar` float NOT NULL,
  `impuesto` float NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empresa` VALUES ("j-1234087","Centro de Bocono","Kamyl Styles y Algo mas de Susana Al chariti",1385132,37,16);


DROP TABLE IF EXISTS `marcas`;

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marca` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `marcas` VALUES (10,"adidas","2024-10-21 08:18:24"),
(11,"pocholin","2024-10-21 08:18:31"),
(13,"rs21","2024-10-21 08:18:44"),
(14,"Aero","2012-11-01 00:07:27"),
(15,"generica","2012-11-01 00:07:35"),
(16,"acadia","2012-11-01 00:07:46"),
(17,"sifrina","2012-11-01 00:07:59");


DROP TABLE IF EXISTS `productos`;

CREATE TABLE `productos` (
  `id_categoria` int(11) NOT NULL,
  `id_tipo` int(11) NOT NULL,
  `id_color` int(11) DEFAULT NULL,
  `id_marca` int(11) NOT NULL,
  `rif_proveedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `codigo` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `imagen` text COLLATE utf8_spanish_ci NOT NULL,
  `stock` int(11) NOT NULL,
  `precio_compra` float NOT NULL,
  `precio_venta` float NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `id_tipo` (`id_tipo`),
  KEY `id_categoria` (`id_categoria`,`id_color`,`id_marca`),
  KEY `id_color` (`id_color`),
  KEY `id_marca` (`id_marca`),
  KEY `rif_proveedor` (`rif_proveedor`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_color`) REFERENCES `colores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_4` FOREIGN KEY (`id_tipo`) REFERENCES `tipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_5` FOREIGN KEY (`rif_proveedor`) REFERENCES `proveedores` (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `productos` VALUES (7,5,NULL,10,"J-121111111",1233344,"zarcills","vistas/img/productos/default/anonymous.png",12,12,"15.6"),
(7,5,NULL,10,"J-121111111","12333443-e","zarcills","vistas/img/productos/default/anonymous.png",12,33,"42.9"),
(7,5,NULL,10,"J-121111111",2323233,"cHEMISE","vistas/img/productos/default/anonymous.png",12,12,"15.6");


DROP TABLE IF EXISTS `proveedores`;

CREATE TABLE `proveedores` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cedula_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `correo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `tipos`;

CREATE TABLE `tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `tipos` VALUES (5,"caballero","2012-11-01 00:04:32"),
(6,"niño","2012-11-01 00:04:45"),
(7,"niña","2012-11-01 00:04:52"),
(8,"dama","2012-11-01 00:06:55");


DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `perfil` text COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL,
  PRIMARY KEY (`usuario`),
  KEY `cedula` (`cedula`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `usuarios` VALUES (1000000,"admin","$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG","Administrador",1),
("V-14600272","FRANCO","$2a$07$asxx54ahjppf45sd87a5auMKLpgpjghYypFQHTok48.zFSGAgdTrm","Vendedor",1);


DROP TABLE IF EXISTS `venta_producto`;

CREATE TABLE `venta_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`),
  KEY `producto` (`producto`),
  CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `ventas` (`factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `factura` int(11) NOT NULL AUTO_INCREMENT,
  `rif_empresa` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `vendedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `total` float NOT NULL,
  `pago` float NOT NULL,
  `impuesto` float NOT NULL DEFAULT '16',
  `metodo_pago` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cliente` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `rif_empresa` (`rif_empresa`),
  KEY `cliente` (`cliente`),
  KEY `vendedor` (`vendedor`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`rif_empresa`) REFERENCES `empresa` (`rif`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10020 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;



SET foreign_key_checks = 1;
CREATE DATABASE IF NOT EXISTS `zapateriak`;

USE `zapateriak`;

SET foreign_key_checks = 0;

DROP TABLE IF EXISTS `apartado`;

CREATE TABLE `apartado` (
  `factura` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` int(11) NOT NULL,
  `vendedor` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `abonado` float NOT NULL,
  `impuesto` int(11) NOT NULL,
  `cliente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `vendedor` (`vendedor`,`cliente`),
  KEY `cliente` (`cliente`),
  CONSTRAINT `apartado_ibfk_1` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`),
  CONSTRAINT `apartado_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `apartado_producto`;

CREATE TABLE `apartado_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`,`producto`),
  KEY `producto` (`producto`),
  CONSTRAINT `apartado_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `apartado` (`factura`),
  CONSTRAINT `apartado_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `categorias` VALUES (7,"Ropa","2024-10-20 21:17:02"),
(9,"Calzados","2024-10-20 22:02:09"),
(10,"ACCESORIOS","2024-10-23 01:54:40"),
(12,"COMPONENTES","2024-10-30 01:38:07");


DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `apellido` text COLLATE utf8_spanish_ci NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `clientes` VALUES ("V-12129999","francis","arias","francoarias81@gmail.com","0414-1212121","av- sucre 1-11"),
("V-14600272","javier","valdez","francoarias81@gmail.com","0272-1212121","av- sucre 1-10");


DROP TABLE IF EXISTS `colores`;

CREATE TABLE `colores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `colores` VALUES (1,"negro","2024-10-21 05:52:13"),
(10,"blanco","2024-10-21 08:17:13"),
(11,"verde","2024-10-21 08:17:24"),
(12,"beige","2012-11-01 00:08:15"),
(13,"TUTIFRUTTY","2024-10-26 20:34:51");


DROP TABLE IF EXISTS `empleado`;

CREATE TABLE `empleado` (
  `cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `foto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empleado` VALUES (1000000,121212,"super","usuario","bocono",""),
("V-14600272","0414-1074586","franco","ARIAS","av-sucre 1-11","");


DROP TABLE IF EXISTS `empresa`;

CREATE TABLE `empresa` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(80) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio_dolar` float NOT NULL,
  `impuesto` float NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empresa` VALUES ("j-1234087","Centro de Bocono","Kamyl Styles y Algo mas de Susana Al chariti",1385132,37,16);


DROP TABLE IF EXISTS `marcas`;

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marca` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `marcas` VALUES (10,"adidas","2024-10-21 08:18:24"),
(11,"pocholin","2024-10-21 08:18:31"),
(13,"rs21","2024-10-21 08:18:44"),
(14,"Aero","2012-11-01 00:07:27"),
(15,"generica","2012-11-01 00:07:35"),
(16,"acadia","2012-11-01 00:07:46"),
(17,"sifrina","2012-11-01 00:07:59");


DROP TABLE IF EXISTS `productos`;

CREATE TABLE `productos` (
  `id_categoria` int(11) NOT NULL,
  `id_tipo` int(11) NOT NULL,
  `id_color` int(11) DEFAULT NULL,
  `id_marca` int(11) NOT NULL,
  `rif_proveedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `codigo` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `imagen` text COLLATE utf8_spanish_ci NOT NULL,
  `stock` int(11) NOT NULL,
  `precio_compra` float NOT NULL,
  `precio_venta` float NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `id_tipo` (`id_tipo`),
  KEY `id_categoria` (`id_categoria`,`id_color`,`id_marca`),
  KEY `id_color` (`id_color`),
  KEY `id_marca` (`id_marca`),
  KEY `rif_proveedor` (`rif_proveedor`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_color`) REFERENCES `colores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_4` FOREIGN KEY (`id_tipo`) REFERENCES `tipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_5` FOREIGN KEY (`rif_proveedor`) REFERENCES `proveedores` (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `productos` VALUES (7,5,NULL,10,"J-121111111",1233344,"zarcills","vistas/img/productos/default/anonymous.png",12,12,"15.6"),
(7,5,NULL,10,"J-121111111","12333443-e","zarcills","vistas/img/productos/default/anonymous.png",12,33,"42.9"),
(7,5,NULL,10,"J-121111111",2323233,"cHEMISE","vistas/img/productos/default/anonymous.png",12,12,"15.6");


DROP TABLE IF EXISTS `proveedores`;

CREATE TABLE `proveedores` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cedula_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `correo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `tipos`;

CREATE TABLE `tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `tipos` VALUES (5,"caballero","2012-11-01 00:04:32"),
(6,"niño","2012-11-01 00:04:45"),
(7,"niña","2012-11-01 00:04:52"),
(8,"dama","2012-11-01 00:06:55");


DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `perfil` text COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL,
  PRIMARY KEY (`usuario`),
  KEY `cedula` (`cedula`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `usuarios` VALUES (1000000,"admin","$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG","Administrador",1),
("V-14600272","FRANCO","$2a$07$asxx54ahjppf45sd87a5auMKLpgpjghYypFQHTok48.zFSGAgdTrm","Vendedor",1);


DROP TABLE IF EXISTS `venta_producto`;

CREATE TABLE `venta_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`),
  KEY `producto` (`producto`),
  CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `ventas` (`factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=108 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `factura` int(11) NOT NULL AUTO_INCREMENT,
  `rif_empresa` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `vendedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `total` float NOT NULL,
  `pago` float NOT NULL,
  `impuesto` float NOT NULL DEFAULT '16',
  `metodo_pago` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cliente` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `rif_empresa` (`rif_empresa`),
  KEY `cliente` (`cliente`),
  KEY `vendedor` (`vendedor`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`rif_empresa`) REFERENCES `empresa` (`rif`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10020 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;



SET foreign_key_checks = 1;
CREATE DATABASE IF NOT EXISTS `zapateriak`;

USE `zapateriak`;

SET foreign_key_checks = 0;

DROP TABLE IF EXISTS `apartado`;

CREATE TABLE `apartado` (
  `factura` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` int(11) NOT NULL,
  `vendedor` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `abonado` float NOT NULL,
  `impuesto` int(11) NOT NULL,
  `cliente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `vendedor` (`vendedor`,`cliente`),
  KEY `cliente` (`cliente`),
  CONSTRAINT `apartado_ibfk_1` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`),
  CONSTRAINT `apartado_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `apartado_producto`;

CREATE TABLE `apartado_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`,`producto`),
  KEY `producto` (`producto`),
  CONSTRAINT `apartado_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `apartado` (`factura`),
  CONSTRAINT `apartado_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `categorias` VALUES (7,"Ropa","2024-10-20 21:17:02"),
(9,"Calzados","2024-10-20 22:02:09"),
(10,"ACCESORIOS","2024-10-23 01:54:40"),
(12,"COMPONENTES","2024-10-30 01:38:07");


DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `apellido` text COLLATE utf8_spanish_ci NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `clientes` VALUES (12393100,"Ricardos","Bastidas","ricardomartin@gmail.com",4247712112,"Rincon 3"),
(12400500,"Ismael","Martinez","ismale@gmail.com",4127421201,"Tu casa"),
(259103825,"Vanessa","Teran","vane1q@gmail.com",2108974,"La loma"),
(31744101,"Jhoan","Mejia","mejiajhoan2021@gmail.com",4247079098,"Bisnaca"),
(31982314,"Esbeidy","Gil","esbeydui@gmail.com","Miticun",10987235),
(7689758,"Julio","Guanda","vane1q@gmail.com",4247079089,"Miticun"),
(908712984,"Julio","Carrasquero","vane@gmail.com",125123,"Miticun");


DROP TABLE IF EXISTS `colores`;

CREATE TABLE `colores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `colores` VALUES (1,"negro","2024-10-21 05:52:13"),
(10,"blanco","2024-10-21 08:17:13"),
(11,"verde","2024-10-21 08:17:24"),
(12,"beige","2012-11-01 00:08:15"),
(13,"TUTIFRUTTY","2024-10-26 20:34:51");


DROP TABLE IF EXISTS `empleado`;

CREATE TABLE `empleado` (
  `cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `foto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empleado` VALUES (12393320,432141325,"Francisco","Arias",""),
(12847231,9012754,"Ricardo","Yepez Bastidas",""),
(1290481,432141325,"DOUGLAS JOSUE","DEL VALLE",""),
(23194809,9102375089,"Isaac","Bastidas",""),
(3174101,172089720,"Jota","Mejia",""),
(31744101,4247079098,"Jhoan","Mejia",""),
(40123456,409879,"Francisco","Martinez","vistas/img/usuarios//661.jpg");


DROP TABLE IF EXISTS `empresa`;

CREATE TABLE `empresa` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(80) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio_dolar` float NOT NULL,
  `impuesto` float NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empresa` VALUES ("j-1234087","Centro de Bocono","Kamyl Styles y Algo Mas de Susana Alchariti",1385132,"44.05",16);


DROP TABLE IF EXISTS `event_log`;

CREATE TABLE `event_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `event_type` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `description` text COLLATE utf8_spanish2_ci NOT NULL,
  `employee_cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `affected_table` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `affected_row_id` varchar(100) COLLATE utf8_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_cedula` (`employee_cedula`),
  CONSTRAINT `event_log_ibfk_1` FOREIGN KEY (`employee_cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=308 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `event_log` VALUES (1,"2025-07-02 13:04:42","Creación Cliente Fallida","Intento de crear cliente con datos inválidos (nombre: francisco, cédula: V-12600272).",1000000,"clientes","V-12600272"),
(2,"2025-07-02 13:05:07","Creación Cliente Fallida","Intento de crear cliente con datos inválidos (nombre: deee, cédula: V-14600272).",1000000,"clientes","V-14600272"),
(3,"2025-07-02 13:05:56","Cliente Eliminado","Cliente \'Ismael Martinez\' (Cédula: 12400500) eliminado.",1000000,"clientes",12400500),
(4,"2025-07-02 14:49:58","Creación Cliente Fallida","Intento de crear cliente con datos inválidos (nombre: francisco, cédula: V-14600273).",1000000,"clientes","V-14600273"),
(5,"2025-07-02 15:05:52","Cliente Eliminado","Cliente \'Esbeidy Gil\' (Cédula: 31982314) eliminado.",1000000,"clientes",31982314),
(6,"2025-07-02 15:07:27","Cliente Eliminado","Cliente \'Julio Guanda\' (Cédula: 7689758) eliminado.",1000000,"clientes",7689758),
(7,"2025-07-02 15:13:58","Cliente Editado","Datos del cliente \'qwqwqwqw qwqwqqw\' (Cédula: ) modificados.",1000000,"clientes",""),
(8,"2025-07-02 15:15:43","Creación Cliente Fallida","Intento de crear cliente con datos inválidos (nombre: francis, cédula: V-14600271).",1000000,"clientes","V-14600271"),
(9,"2025-07-02 15:32:41","Cliente Editado","Datos del cliente \'Ismael sdsdsd\' (Cédula: ) modificados.",1000000,"clientes",""),
(10,"2025-07-02 15:33:22","Cliente Editado","Datos del cliente \'Ismael Mejia\' (Cédula: ) modificados.",1000000,"clientes",""),
(11,"2025-07-02 15:51:59","Cliente Editado","Datos del cliente \'Ismael sdsdsd\' (Cédula: ) modificados.",1000000,"clientes",""),
(12,"2025-07-02 18:35:56","Creación Cliente Fallida","Intento de crear cliente con datos inválidos (nombre: qwqw, cédula: V-12222222).",1000000,"clientes","V-12222222"),
(13,"2025-07-02 20:10:48","Cliente Creado","Nuevo cliente \'lola mento\' (Cédula: V-14600276) registrado.",1000000,"clientes","V-14600276"),
(14,"2025-07-03 03:50:57","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(15,"2025-07-03 05:04:20","Cliente Editado","Datos del cliente \'Ismael Mejia\' (Cédula: ) modificados.",1000000,"clientes",""),
(16,"2025-07-03 06:33:12","Cliente Editado","Datos del cliente \'qwq qwqw\' (Cédula: ) modificados.",1000000,"clientes",""),
(17,"2025-07-03 06:34:07","Cliente Creado","Nuevo cliente \'lola veinte\' (Cédula: V-12124441) registrado.",1000000,"clientes","V-12124441"),
(18,"2025-07-03 06:35:10","Cliente Editado","Datos del cliente \'Ismael cabeza arias\' (Cédula: ) modificados.",1000000,"clientes",""),
(19,"2025-07-03 06:37:29","Cliente Creado","Nuevo cliente \'francisco perdomo\' (Cédula: V-14600274) registrado.",1000000,"clientes","V-14600274"),
(20,"2025-07-03 07:01:40","Cliente Editado","Datos del cliente \'Vanessita Teran\' (Cédula: 259103825) modificados.",1000000,"clientes",259103825),
(21,"2025-07-03 07:27:13","Empleado Eliminado","Empleado \'Francisco Arias\' (Cédula: 12393320) eliminado con éxito.",1000000,"empleado",12393320),
(22,"2025-07-03 07:27:48","Empleado Creado","Nuevo empleado \'jose err\' (Cédula: E-14600272) registrado con éxito.",1000000,"empleado","E-14600272"),
(23,"2025-07-03 07:32:10","Edición Empleado Fallida","Intento de editar empleado con datos inválidos (Nombre: Jhoan, Cédula: 31744101). Validación de campos fallida.",1000000,"empleado",31744101),
(24,"2025-07-03 07:32:24","Edición Empleado Fallida","Intento de editar empleado con datos inválidos (Nombre: Jhoan, Cédula: 31744101). Validación de campos fallida.",1000000,"empleado",31744101),
(25,"2025-07-03 07:32:45","Edición Empleado Fallida","Intento de editar empleado con datos inválidos (Nombre: Isaac, Cédula: 23194809). Validación de campos fallida.",1000000,"empleado",23194809),
(26,"2025-07-03 09:16:57","Configuración Editada","Configuración de la empresa \'Kamyl Styles y Algo mas de Susana Al chariti\' (RIF: V-221234087) modificada.",1000000,"empresa","V-221234087"),
(27,"2025-07-03 09:22:04","Categoría Editada","Categoría \'Ropa\' (ID: 7) modificada a \'Ropas\'.",1000000,"categorias",7),
(28,"2025-07-03 09:35:55","Tipo Editado","Tipo \'caballeros\' (ID: 5) modificado a \'caballero\'.",1000000,"tipos",5),
(29,"2025-07-03 09:37:32","Marca Editada","Marca \'adidas\' (ID: 10) modificada a \'adidass\'.",1000000,"marcas",10),
(30,"2025-07-03 09:52:35","Proveedor Editado","Proveedor (RIF: J-999999990) editado. Empresa: \'rs22\' a \'rs222\'. Representante: \'erer arias\' a \'erer arias\'. Teléfono: \'0272-1200000\' a \'0424-1111111\'.",1000000,"proveedores","J-999999990"),
(31,"2025-07-03 14:40:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(32,"2025-07-03 14:40:59","Login Fallido","Contraseña incorrecta para el usuario: \'admin\'.",1000000,"usuarios","admin"),
(33,"2025-07-03 14:41:05","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(34,"2025-07-03 14:41:30","Usuario Editado","Usuario \'isa\' (Cédula: ) editado con perfil \'Vendedor\'.",1000000,"usuarios",NULL),
(35,"2025-07-03 15:09:35","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(36,"2025-07-03 15:11:21","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(37,"2025-07-03 15:15:17","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(39,"2025-07-03 15:15:25","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(40,"2025-07-03 15:15:42","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(43,"2025-07-03 15:15:59","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(45,"2025-07-03 15:16:06","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(47,"2025-07-03 15:16:15","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(51,"2025-07-03 15:17:02","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(55,"2025-07-03 15:17:41","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(57,"2025-07-03 15:18:21","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(58,"2025-07-03 15:18:28","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(64,"2025-07-03 15:18:46","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(66,"2025-07-03 15:18:50","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(69,"2025-07-03 15:19:00","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(71,"2025-07-03 15:19:17","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(72,"2025-07-03 15:19:39","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(73,"2025-07-03 15:20:59","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(74,"2025-07-03 15:22:13","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(76,"2025-07-03 15:22:21","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(79,"2025-07-03 15:27:58","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(82,"2025-07-03 15:28:11","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(85,"2025-07-03 15:28:24","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(87,"2025-07-03 15:28:30","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(89,"2025-07-03 15:29:15","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(90,"2025-07-03 15:30:35","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(91,"2025-07-03 15:31:05","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(92,"2025-07-03 15:31:21","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(94,"2025-07-03 15:31:27","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(95,"2025-07-03 15:32:01","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(96,"2025-07-03 15:34:57","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(98,"2025-07-03 15:35:06","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(100,"2025-07-03 15:35:14","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(103,"2025-07-03 15:35:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(104,"2025-07-03 15:38:28","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(105,"2025-07-03 15:39:12","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(107,"2025-07-03 15:39:50","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(110,"2025-07-03 15:43:43","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(112,"2025-07-03 15:43:54","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(113,"2025-07-03 15:44:11","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(114,"2025-07-03 15:44:11","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(116,"2025-07-03 15:44:23","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(122,"2025-07-03 15:52:56","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(123,"2025-07-03 15:53:12","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(124,"2025-07-03 15:54:26","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(125,"2025-07-03 15:54:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(126,"2025-07-03 15:55:27","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(127,"2025-07-03 15:55:37","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(128,"2025-07-03 15:56:12","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(129,"2025-07-03 16:08:05","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(130,"2025-07-03 16:08:15","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(131,"2025-07-03 16:08:20","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(132,"2025-07-03 16:08:40","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(133,"2025-07-03 16:11:19","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(134,"2025-07-03 16:13:49","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(135,"2025-07-03 16:14:08","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(136,"2025-07-03 16:15:06","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(137,"2025-07-03 16:24:19","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(138,"2025-07-03 16:24:28","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(139,"2025-07-03 16:25:19","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(140,"2025-07-03 16:26:14","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(141,"2025-07-03 16:29:39","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(142,"2025-07-03 16:29:48","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(143,"2025-07-03 16:29:55","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(144,"2025-07-03 16:34:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(145,"2025-07-03 16:43:17","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(146,"2025-07-03 16:54:13","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(147,"2025-07-03 17:34:35","Usuario Editado","Usuario \'isa\' (Cédula: ) editado con perfil \'Vendedor\'.",1000000,"usuarios",NULL),
(148,"2025-07-03 17:34:47","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(149,"2025-07-03 17:34:59","Login Fallido","Contraseña incorrecta para el usuario: \'isa\'.",23194809,"usuarios","isa"),
(150,"2025-07-03 17:35:09","Login Exitoso","El empleado \'isa\' (Vendedor) ha iniciado sesión.",23194809,"usuarios","isa"),
(151,"2025-07-03 17:35:42","Cliente Editado","Datos del cliente \'Vanessita Teran\' (Cédula: 259103825) modificados.",23194809,"clientes",259103825),
(152,"2025-07-03 17:36:11","Cliente Editado","Datos del cliente \'Julio Carrasquero\' (Cédula: 908712984) modificados.",23194809,"clientes",908712984),
(153,"2025-07-03 17:37:13","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(154,"2025-07-04 04:05:27","Producto Eliminado","Producto \'cHEMISEssss\' (Código: 121342) eliminado.",1000000,"productos",121342),
(155,"2025-07-04 04:46:45","Producto Editado","Datos del producto \'cHEMISEssss\' (Código: 121342) modificados.",1000000,"productos",121342),
(156,"2025-07-04 04:47:02","Producto Editado","Datos del producto \'cHEMISEssss\' (Código: 121342) modificados.",1000000,"productos",121342),
(157,"2025-07-04 05:08:07","Producto Editado","Datos generales del producto \'pantalon\' (Código: 12333443-e) modificados.",1000000,"productos","12333443-e"),
(158,"2025-07-04 05:08:23","Producto Editado","Datos generales del producto \'pantalon\' (Código: 12333443-e) modificados.",1000000,"productos","12333443-e"),
(159,"2025-07-04 05:12:33","Producto Editado","Datos generales del producto \'cHEMISEssss\' (Código: 121342) modificados.",1000000,"productos",121342),
(160,"2025-07-04 05:18:01","Creación Producto Fallida","Intento de crear producto con datos inválidos (Descripción: zarcills, Código: 12333443-e4).",1000000,"productos","12333443-e4"),
(161,"2025-07-04 05:23:12","Creación Producto Fallida","Intento de crear producto con datos inválidos (Descripción: cHEMISEssss, Código: 12333443-e3).",1000000,"productos","12333443-e3"),
(162,"2025-07-04 05:24:29","Creación Producto Fallida","Intento de crear producto con datos inválidos (Descripción: cHEMISEssss, Código: 12333443-e3).",1000000,"productos","12333443-e3"),
(163,"2025-07-04 05:35:48","Producto Eliminado","Producto \'pantalon\' (Código: 12333443-e) eliminado.",1000000,"productos","12333443-e"),
(164,"2025-07-04 05:36:16","Producto Editado","Datos del producto \'cHEMISEssss\' (Código: 121342) modificados.",1000000,"productos",121342),
(165,"2025-07-04 06:32:55","Producto Creado","Producto \'cHEMISE\' (Código: 12333443-eo) creado.",1000000,"productos","12333443-eo"),
(166,"2025-07-04 06:55:16","Stock Modificado","Stock del producto (Código: 12333443-eo) actualizado a 2.",1000000,"productos","12333443-eo"),
(167,"2025-07-04 06:55:22","Stock Modificado","Stock del producto (Código: 121342) actualizado a 2.",1000000,"productos",121342),
(168,"2025-07-04 06:57:09","Producto Editado","Producto \'cHEMISEssss\' (Código: 121342) editado.",1000000,"productos",121342),
(169,"2025-07-04 06:57:20","Producto Editado","Producto \'cHEMISE\' (Código: 12333443-eo) editado.",1000000,"productos","12333443-eo"),
(170,"2025-07-04 06:57:25","Producto Eliminado","Producto \'N/A\' (Código: 12333443-eo) eliminado.",1000000,"productos","12333443-eo"),
(171,"2025-07-04 06:57:58","Producto Creado","Producto \'cHEMISE\' (Código: 12333443-eeee) creado.",1000000,"productos","12333443-eeee"),
(172,"2025-07-04 06:57:58","Producto Eliminado","Producto \'N/A\' (Código: 12333443-eo) eliminado.",1000000,"productos","12333443-eo"),
(173,"2025-07-04 06:58:53","Stock Modificado","Stock del producto (Código: 121342) actualizado a 2.",1000000,"productos",121342),
(174,"2025-07-04 06:59:26","Producto Creado","Producto \'cHEMISEssss\' (Código: 12333443-e) creado.",1000000,"productos","12333443-e"),
(175,"2025-07-04 07:00:40","Creación Producto Fallida","Intento de crear producto repetido (Código: 12333443-e).",1000000,"productos","12333443-e"),
(176,"2025-07-04 07:03:06","Creación Producto Fallida","Intento de crear producto repetido (Código: 12333443-e).",1000000,"productos","12333443-e"),
(177,"2025-07-04 07:03:11","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(178,"2025-07-04 07:03:19","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(179,"2025-07-04 07:03:26","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(180,"2025-07-04 07:03:34","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(181,"2025-07-04 07:06:05","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(182,"2025-07-04 07:06:21","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(183,"2025-07-04 07:06:45","Producto Editado","Producto \'cHEMISEssss\' (Código: 121342) editado.",1000000,"productos",121342),
(184,"2025-07-04 07:08:24","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(185,"2025-07-04 07:08:48","Cliente Editado","Datos del cliente \'Vanessita Teran\' (Cédula: 259103825) modificados.",1000000,"clientes",259103825),
(186,"2025-07-04 07:09:03","Producto Editado","Producto \'cHEMISEssss\' (Código: 12333443-e) editado.",1000000,"productos","12333443-e"),
(187,"2025-07-04 07:09:49","Producto Creado","Producto \'rtrtr\' (Código: 2342342424) creado.",1000000,"productos",2342342424),
(188,"2025-07-04 07:12:22","Producto Editado","Producto \'cHEMISEssss\' (Código: 121342) editado.",1000000,"productos",121342),
(189,"2025-07-04 07:12:34","Stock Modificado","Stock del producto (Código: 22ee2) actualizado a 2.",1000000,"productos","22ee2"),
(190,"2025-07-04 07:15:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(191,"2025-07-04 07:17:23","Stock Modificado","Stock del producto (Código: 2342342424) actualizado a 2.",1000000,"productos",2342342424),
(192,"2025-07-04 07:17:39","Stock Modificado","Stock del producto (Código: 2342342424) actualizado a 2.",1000000,"productos",2342342424),
(193,"2025-07-04 07:19:37","Stock Modificado","Stock del producto (Código: 2342342424) actualizado a 2.",1000000,"productos",2342342424),
(194,"2025-07-04 07:20:17","Proveedor Editado","Proveedor (RIF: J-999999990) editado. Empresa: \'rs222\' a \'rs222\'. Representante: \'erer arias\' a \'erer arias\'. Teléfono: \'0424-1111111\' a \'0426-1233333\'.",1000000,"proveedores","J-999999990"),
(195,"2025-07-04 07:22:49","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(196,"2025-07-04 07:46:57","Producto Creado","Producto \'eeeee\' (Código: 12122) registrado.",1000000,"productos",12122),
(197,"2025-07-04 14:01:21","Stock Actualizado","Stock del producto (Código: 12122) actualizado a 2 unidades.",1000000,"productos",12122),
(198,"2025-07-04 14:02:00","Producto Editado","Producto \'rtrtr\' (Código: 2342342424) editado.",1000000,"productos",2342342424),
(199,"2025-07-04 14:02:44","Producto Creado","Producto \'zarcills\' (Código: 1212-erer-4) creado.",1000000,"productos","1212-erer-4"),
(200,"2025-07-04 14:05:09","Producto Eliminado","Producto (Código: 12122) eliminado.",1000000,"productos",12122),
(206,"2025-07-04 15:12:36","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(207,"2025-07-04 15:12:37","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(208,"2025-07-04 15:24:03","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(209,"2025-07-04 15:24:28","Cliente Editado","Datos del cliente \'Vanessitas Teran\' (Cédula: 259103825) modificados.",1000000,"clientes",259103825),
(210,"2025-07-04 15:24:40","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(211,"2025-07-04 15:24:42","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(212,"2025-07-04 15:25:18","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(213,"2025-07-04 15:25:21","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(214,"2025-07-04 15:25:39","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(215,"2025-07-04 15:25:47","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(216,"2025-07-04 15:32:17","Usuario Creado","Nuevo usuario \'pedro13\' (Cédula: 3174101) con perfil \'Administrador\' fue creado.",1000000,"usuarios",3174101),
(217,"2025-07-04 15:33:44","Usuario Eliminado","Usuario \'3174101\' (Cédula: N/A) eliminado.",1000000,"usuarios","N/A"),
(218,"2025-07-04 15:40:56","Usuario Editado","Usuario \'isa\' (Cédula: ) editado con perfil \'Vendedor\'.",1000000,"usuarios",NULL),
(219,"2025-07-04 15:41:39","Usuario Editado","Usuario \'ricki\' (Cédula: ) editado con perfil \'Vendedor\'.",1000000,"usuarios",NULL),
(220,"2025-07-04 15:47:37","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(221,"2025-07-04 15:47:42","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(222,"2025-07-04 15:47:53","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(223,"2025-07-04 15:47:55","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(224,"2025-07-04 15:52:45","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(225,"2025-07-04 15:52:48","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(226,"2025-07-04 15:57:10","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(227,"2025-07-04 15:57:14","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(228,"2025-07-04 15:57:51","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(229,"2025-07-04 15:57:53","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(230,"2025-07-04 15:59:02","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(231,"2025-07-04 15:59:04","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(232,"2025-07-04 16:00:40","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(233,"2025-07-04 16:00:42","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(234,"2025-07-04 16:08:31","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(235,"2025-07-04 16:08:40","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(236,"2025-07-04 16:09:23","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(237,"2025-07-04 16:12:35","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(238,"2025-07-04 16:12:36","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(239,"2025-07-04 16:13:19","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(240,"2025-07-04 16:13:20","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(241,"2025-07-04 16:13:28","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(242,"2025-07-04 16:25:20","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(243,"2025-07-04 16:25:22","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(244,"2025-07-04 16:28:53","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(245,"2025-07-04 16:28:55","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(246,"2025-07-04 16:29:14","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(247,"2025-07-04 16:29:16","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(248,"2025-07-04 16:29:33","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(249,"2025-07-04 16:30:12","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(250,"2025-07-04 16:33:31","Edición Empleado Fallida","Intento de editar empleado con datos inválidos (Nombre: Jhoan, Cédula: 31744101). Validación de campos fallida.",1000000,"empleado",31744101),
(251,"2025-07-04 16:33:51","Edición Empleado Fallida","Intento de editar empleado con datos inválidos (Nombre: Jhoan, Cédula: 31744101). Validación de campos fallida.",1000000,"empleado",31744101),
(252,"2025-07-04 16:34:15","Cliente Eliminado","Cliente \'Jhoan Mejia\' (Cédula: 31744101) eliminado.",1000000,"clientes",31744101),
(253,"2025-07-05 17:04:34","Generación de Reporte PDF","Se generó un reporte Diario de Ventas en formato PDF. Total: 4.508,92 Bs.",1000000,"ventas","N/A"),
(254,"2025-07-05 17:04:35","Generación de Reporte PDF","Se generó un reporte Diario de Ventas en formato PDF. Total: 4.508,92 Bs.",1000000,"ventas","N/A"),
(255,"2025-07-05 20:20:43","Generación de Reporte PDF","Se generó un reporte Mensual de Ventas en formato PDF. Total: 9.213,88 Bs. Período: 07/2025",1000000,"ventas","N/A"),
(256,"2025-07-05 20:20:44","Generación de Reporte PDF","Se generó un reporte Mensual de Ventas en formato PDF. Total: 9.213,88 Bs. Período: 07/2025",1000000,"ventas","N/A"),
(257,"2025-07-05 20:23:05","Generación de Reporte PDF","Se generó un reporte Mensual de Ventas en formato PDF. Total: 18.576,45 Bs.",1000000,"ventas","N/A"),
(258,"2025-07-05 20:23:06","Generación de Reporte PDF","Se generó un reporte Mensual de Ventas en formato PDF. Total: 18.576,45 Bs.",1000000,"ventas","N/A"),
(259,"2025-07-05 20:24:44","Generación de Reporte PDF","Se generó un reporte Total de Ventas en formato PDF. Total: 18.576,45 Bs.",1000000,"ventas","N/A"),
(260,"2025-07-05 20:24:45","Generación de Reporte PDF","Se generó un reporte Total de Ventas en formato PDF. Total: 18.576,45 Bs.",1000000,"ventas","N/A"),
(261,"2025-07-05 20:25:30","Proveedor Editado","Proveedor (RIF: J-999999990) editado. Empresa: \'rs222\' a \'rs22\'. Representante: \'erer arias\' a \'erer arias\'. Teléfono: \'0426-1233333\' a \'0416-1222138\'.",1000000,"proveedores","J-999999990"),
(262,"2025-07-05 20:48:13","Producto Editado","Producto \'cHEMISEssss\' (Código: 12333443-e) editado.",1000000,"productos","12333443-e"),
(263,"2025-07-05 21:01:01","Producto Editado","Producto \'rtrtr\' (Código: 2342342424) editado.",1000000,"productos",2342342424),
(264,"2025-07-05 21:44:06","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(265,"2025-07-05 21:45:49","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(266,"2025-07-05 21:45:50","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(267,"2025-07-05 21:47:53","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(268,"2025-07-05 21:47:55","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(269,"2025-07-05 21:48:36","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(270,"2025-07-05 21:48:37","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(271,"2025-07-05 21:49:09","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al chariti\' (RIF: V-221234087) modificada.",1000000,"empresa","V-221234087"),
(272,"2025-07-05 21:49:32","Generación de Reporte PDF","Se generó un reporte Total de Ventas en formato PDF. Total: 77.780,45 Bs.",1000000,"ventas","N/A"),
(273,"2025-07-05 21:49:32","Generación de Reporte PDF","Se generó un reporte Total de Ventas en formato PDF. Total: 77.780,45 Bs.",1000000,"ventas","N/A"),
(274,"2025-07-05 21:49:44","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(275,"2025-07-05 21:50:03","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(276,"2025-07-05 21:52:57","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(277,"2025-07-05 21:53:00","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(278,"2025-07-05 21:58:39","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(279,"2025-07-05 21:58:41","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(280,"2025-07-05 22:21:45","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(281,"2025-07-05 22:22:16","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(282,"2025-07-05 22:22:16","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(283,"2025-07-05 22:22:31","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(284,"2025-07-05 22:22:33","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(285,"2025-07-05 22:23:20","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(286,"2025-07-05 22:24:45","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(287,"2025-07-05 22:24:47","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(288,"2025-07-05 22:25:48","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(289,"2025-07-05 22:31:27","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(290,"2025-07-05 22:31:39","Cliente Eliminado","Cliente \'Vanessitas Teran\' (Cédula: 259103825) eliminado.",1000000,"clientes",259103825),
(291,"2025-07-05 22:32:13","Cliente Editado","Datos del cliente \'francisco perdomo\' (Cédula: V-14600272) modificados.",1000000,"clientes","V-14600272"),
(292,"2025-07-05 22:35:58","Creación Cliente Fallida","Intento de crear cliente con cédula \'V-14600272\' fallido por ser repetida.",1000000,"clientes","V-14600272"),
(293,"2025-07-05 23:16:09","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada.",1000000,"empresa","V-221234083"),
(294,"2025-07-05 23:46:24","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada.",1000000,"empresa","V-221234083"),
(295,"2025-07-06 00:23:37","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada.",1000000,"empresa","V-221234083"),
(296,"2025-07-06 00:27:23","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 133,0000 -> 132,0000 USD.",1000000,"empresa","V-221234083"),
(297,"2025-07-06 00:30:27","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 132,00 -> 130,0000 USD.",1000000,"empresa","V-221234083"),
(298,"2025-07-06 00:31:11","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 130,00 -> 110,00 USD.",1000000,"empresa","V-221234083"),
(299,"2025-07-06 00:32:14","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 110,00 cambio a 115,00 USD.",1000000,"empresa","V-221234083"),
(300,"2025-07-06 00:32:53","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Otros datos de configuración modificados.",1000000,"empresa","V-221234083"),
(301,"2025-07-06 00:33:39","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 115,00 cambio a 116,00 USD.",1000000,"empresa","V-221234083"),
(302,"2025-07-06 00:34:08","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 116,00 cambio a 115,00 .",1000000,"empresa","V-221234083"),
(303,"2025-07-06 01:06:20","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 115,00 cambio a 114,00 .",1000000,"empresa","V-221234083"),
(304,"2025-07-06 01:18:59","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: Anterior 114,0000 USD, Nuevo 113,0000 USD.",1000000,"empresa","V-221234083"),
(305,"2025-07-06 01:34:15","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: Anterior 113,0000 USD, Nuevo 114,0000 USD.",1000000,"empresa","V-221234083"),
(306,"2025-07-06 01:39:42","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(307,"2025-07-06 01:39:43","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A");


DROP TABLE IF EXISTS `historial_dolar`;

CREATE TABLE `historial_dolar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `precio_dolar` decimal(10,4) NOT NULL,
  `precio_anterior` decimal(10,4) DEFAULT NULL,
  `fecha_cambio` datetime NOT NULL,
  `estado_cambio` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO `historial_dolar` VALUES (1,"113.0000",NULL,"2025-07-06 07:48:58",NULL),
(2,"114.0000","113.0000","2025-07-06 08:04:14","Subió");


DROP TABLE IF EXISTS `marcas`;

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marca` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `marcas` VALUES (10,"adidas","2024-10-21 08:18:24"),
(11,"pocholin","2024-10-21 08:18:31"),
(13,"rs21","2024-10-21 08:18:44"),
(14,"Aero","2012-11-01 00:07:27"),
(15,"generica","2012-11-01 00:07:35"),
(16,"acadia","2012-11-01 00:07:46"),
(17,"sifrina","2012-11-01 00:07:59"),
(18,"NIKE","2024-10-25 18:02:27");


DROP TABLE IF EXISTS `productos`;

;



DROP TABLE IF EXISTS `proveedores`;

CREATE TABLE `proveedores` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cedula_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `correo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `proveedores` VALUES 

DROP TABLE IF EXISTS `tipos`;

CREATE TABLE `tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `tipos` VALUES (5,"caballero","2025-07-03 09:35:55"),
(6,"niño","2012-11-01 00:04:45"),
(7,"niña","2012-11-01 00:04:52"),
(8,"dama","2012-11-01 00:06:55");


DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `perfil` text COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL,
  PRIMARY KEY (`usuario`),
  KEY `cedula` (`cedula`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `usuarios` VALUES (1000000,"admin","$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG","Administrador",1),
(23194809,"isa","$2a$07$asxx54ahjppf45sd87a5au4N7hmqIxxE/MQZ8CZlhGUldNNx6IO6a","Vendedor",1),
(12847231,"ricki","$2a$07$asxx54ahjppf45sd87a5auMKLpgpjghYypFQHTok48.zFSGAgdTrm","Vendedor",1);


DROP TABLE IF EXISTS `venta_producto`;

CREATE TABLE `venta_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`),
  KEY `producto` (`producto`),
  CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `ventas` (`factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `venta_producto` VALUES (1,10021,1,"12333443-e"),
(2,10022,2,2342342424),
(3,10022,3,"22ee2"),
(4,10022,5,"12333443-e"),
(5,10023,1,2342342424),
(6,10023,1,"22ee2"),
(7,10023,1,"12333443-e"),
(8,10023,1,121342),
(9,10023,1,"1212-erer-4");


DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `factura` int(11) NOT NULL AUTO_INCREMENT,
  `rif_empresa` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `vendedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `total` float NOT NULL,
  `pago` float NOT NULL,
  `impuesto` float NOT NULL DEFAULT '16',
  `metodo_pago` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cliente` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `rif_empresa` (`rif_empresa`),
  KEY `cliente` (`cliente`),
  KEY `vendedor` (`vendedor`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`rif_empresa`) REFERENCES `empresa` (`rif`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10024 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `ventas` VALUES (10002,"V-221234083",23194809,"1595.34","1375.29","220.05","Tarjeta-1234","2024-11-10 11:43:24",30124678),
(10004,"V-221234083",23194809,"1595.34","1375.29","220.05","Tarjeta-1234","2024-11-10 12:32:44",908712984),
(10015,"V-221234083",1000000,"111.592","96.2","15.39","Efectivo","2024-11-10 18:26:17",30124678),
(10019,"V-221234083",1000000,"669.552","577.2","92.35","Divisas-12","2025-06-14 16:41:49","V-14600272"),
(10021,"V-221234083",1000000,"4508.92",3887,"621.92","Divisas-30","2025-07-05 16:59:43","V-12124441"),
(10022,"V-221234083",1000000,"43324.8",37349,5,"Pago Movil-1232112","2025-07-05 20:26:18",908712984),
(10023,"V-221234083",1000000,"15879.2",13689,2,"Transferencia-676775443","2025-07-05 20:51:51","V-14600274");


SET foreign_key_checks = 1;
CREATE DATABASE IF NOT EXISTS `zapateriak`;

USE `zapateriak`;

SET foreign_key_checks = 0;

DROP TABLE IF EXISTS `apartado`;

CREATE TABLE `apartado` (
  `factura` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` int(11) NOT NULL,
  `vendedor` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `abonado` float NOT NULL,
  `impuesto` int(11) NOT NULL,
  `cliente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `vendedor` (`vendedor`,`cliente`),
  KEY `cliente` (`cliente`),
  CONSTRAINT `apartado_ibfk_1` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`),
  CONSTRAINT `apartado_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `apartado_producto`;

CREATE TABLE `apartado_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`,`producto`),
  KEY `producto` (`producto`),
  CONSTRAINT `apartado_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `apartado` (`factura`),
  CONSTRAINT `apartado_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `categorias` VALUES (7,"Ropa","2024-10-20 21:17:02"),
(9,"Calzados","2024-10-20 22:02:09"),
(10,"ACCESORIOS","2024-10-23 01:54:40"),
(12,"COMPONENTES","2024-10-30 01:38:07");


DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `apellido` text COLLATE utf8_spanish_ci NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `clientes` VALUES (12393100,"Ricardos","Bastidas","ricardomartin@gmail.com",4247712112,"Rincon 3"),
(12400500,"Ismael","Martinez","ismale@gmail.com",4127421201,"Tu casa"),
(259103825,"Vanessa","Teran","vane1q@gmail.com",2108974,"La loma"),
(31744101,"Jhoan","Mejia","mejiajhoan2021@gmail.com",4247079098,"Bisnaca"),
(31982314,"Esbeidy","Gil","esbeydui@gmail.com","Miticun",10987235),
(7689758,"Julio","Guanda","vane1q@gmail.com",4247079089,"Miticun"),
(908712984,"Julio","Carrasquero","vane@gmail.com",125123,"Miticun");


DROP TABLE IF EXISTS `colores`;

CREATE TABLE `colores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `colores` VALUES (1,"negro","2024-10-21 05:52:13"),
(10,"blanco","2024-10-21 08:17:13"),
(11,"verde","2024-10-21 08:17:24"),
(12,"beige","2012-11-01 00:08:15"),
(13,"TUTIFRUTTY","2024-10-26 20:34:51");


DROP TABLE IF EXISTS `empleado`;

CREATE TABLE `empleado` (
  `cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `foto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empleado` VALUES 

DROP TABLE IF EXISTS `empresa`;

CREATE TABLE `empresa` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(80) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio_dolar` float NOT NULL,
  `impuesto` float NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empresa` VALUES ("j-1234087","Centro de Bocono","Kamyl Styles y Algo Mas de Susana Alchariti",1385132,"44.05",16);


DROP TABLE IF EXISTS `event_log`;

CREATE TABLE `event_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `event_type` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `description` text COLLATE utf8_spanish2_ci NOT NULL,
  `employee_cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `affected_table` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `affected_row_id` varchar(100) COLLATE utf8_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_cedula` (`employee_cedula`),
  CONSTRAINT `event_log_ibfk_1` FOREIGN KEY (`employee_cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=308 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `event_log` VALUES (1,"2025-07-02 13:04:42","Creación Cliente Fallida","Intento de crear cliente con datos inválidos (nombre: francisco, cédula: V-12600272).",1000000,"clientes","V-12600272"),
(2,"2025-07-02 13:05:07","Creación Cliente Fallida","Intento de crear cliente con datos inválidos (nombre: deee, cédula: V-14600272).",1000000,"clientes","V-14600272"),
(3,"2025-07-02 13:05:56","Cliente Eliminado","Cliente \'Ismael Martinez\' (Cédula: 12400500) eliminado.",1000000,"clientes",12400500),
(4,"2025-07-02 14:49:58","Creación Cliente Fallida","Intento de crear cliente con datos inválidos (nombre: francisco, cédula: V-14600273).",1000000,"clientes","V-14600273"),
(5,"2025-07-02 15:05:52","Cliente Eliminado","Cliente \'Esbeidy Gil\' (Cédula: 31982314) eliminado.",1000000,"clientes",31982314),
(6,"2025-07-02 15:07:27","Cliente Eliminado","Cliente \'Julio Guanda\' (Cédula: 7689758) eliminado.",1000000,"clientes",7689758),
(7,"2025-07-02 15:13:58","Cliente Editado","Datos del cliente \'qwqwqwqw qwqwqqw\' (Cédula: ) modificados.",1000000,"clientes",""),
(8,"2025-07-02 15:15:43","Creación Cliente Fallida","Intento de crear cliente con datos inválidos (nombre: francis, cédula: V-14600271).",1000000,"clientes","V-14600271"),
(9,"2025-07-02 15:32:41","Cliente Editado","Datos del cliente \'Ismael sdsdsd\' (Cédula: ) modificados.",1000000,"clientes",""),
(10,"2025-07-02 15:33:22","Cliente Editado","Datos del cliente \'Ismael Mejia\' (Cédula: ) modificados.",1000000,"clientes",""),
(11,"2025-07-02 15:51:59","Cliente Editado","Datos del cliente \'Ismael sdsdsd\' (Cédula: ) modificados.",1000000,"clientes",""),
(12,"2025-07-02 18:35:56","Creación Cliente Fallida","Intento de crear cliente con datos inválidos (nombre: qwqw, cédula: V-12222222).",1000000,"clientes","V-12222222"),
(13,"2025-07-02 20:10:48","Cliente Creado","Nuevo cliente \'lola mento\' (Cédula: V-14600276) registrado.",1000000,"clientes","V-14600276"),
(14,"2025-07-03 03:50:57","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(15,"2025-07-03 05:04:20","Cliente Editado","Datos del cliente \'Ismael Mejia\' (Cédula: ) modificados.",1000000,"clientes",""),
(16,"2025-07-03 06:33:12","Cliente Editado","Datos del cliente \'qwq qwqw\' (Cédula: ) modificados.",1000000,"clientes",""),
(17,"2025-07-03 06:34:07","Cliente Creado","Nuevo cliente \'lola veinte\' (Cédula: V-12124441) registrado.",1000000,"clientes","V-12124441"),
(18,"2025-07-03 06:35:10","Cliente Editado","Datos del cliente \'Ismael cabeza arias\' (Cédula: ) modificados.",1000000,"clientes",""),
(19,"2025-07-03 06:37:29","Cliente Creado","Nuevo cliente \'francisco perdomo\' (Cédula: V-14600274) registrado.",1000000,"clientes","V-14600274"),
(20,"2025-07-03 07:01:40","Cliente Editado","Datos del cliente \'Vanessita Teran\' (Cédula: 259103825) modificados.",1000000,"clientes",259103825),
(21,"2025-07-03 07:27:13","Empleado Eliminado","Empleado \'Francisco Arias\' (Cédula: 12393320) eliminado con éxito.",1000000,"empleado",12393320),
(22,"2025-07-03 07:27:48","Empleado Creado","Nuevo empleado \'jose err\' (Cédula: E-14600272) registrado con éxito.",1000000,"empleado","E-14600272"),
(23,"2025-07-03 07:32:10","Edición Empleado Fallida","Intento de editar empleado con datos inválidos (Nombre: Jhoan, Cédula: 31744101). Validación de campos fallida.",1000000,"empleado",31744101),
(24,"2025-07-03 07:32:24","Edición Empleado Fallida","Intento de editar empleado con datos inválidos (Nombre: Jhoan, Cédula: 31744101). Validación de campos fallida.",1000000,"empleado",31744101),
(25,"2025-07-03 07:32:45","Edición Empleado Fallida","Intento de editar empleado con datos inválidos (Nombre: Isaac, Cédula: 23194809). Validación de campos fallida.",1000000,"empleado",23194809),
(26,"2025-07-03 09:16:57","Configuración Editada","Configuración de la empresa \'Kamyl Styles y Algo mas de Susana Al chariti\' (RIF: V-221234087) modificada.",1000000,"empresa","V-221234087"),
(27,"2025-07-03 09:22:04","Categoría Editada","Categoría \'Ropa\' (ID: 7) modificada a \'Ropas\'.",1000000,"categorias",7),
(28,"2025-07-03 09:35:55","Tipo Editado","Tipo \'caballeros\' (ID: 5) modificado a \'caballero\'.",1000000,"tipos",5),
(29,"2025-07-03 09:37:32","Marca Editada","Marca \'adidas\' (ID: 10) modificada a \'adidass\'.",1000000,"marcas",10),
(30,"2025-07-03 09:52:35","Proveedor Editado","Proveedor (RIF: J-999999990) editado. Empresa: \'rs22\' a \'rs222\'. Representante: \'erer arias\' a \'erer arias\'. Teléfono: \'0272-1200000\' a \'0424-1111111\'.",1000000,"proveedores","J-999999990"),
(31,"2025-07-03 14:40:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(32,"2025-07-03 14:40:59","Login Fallido","Contraseña incorrecta para el usuario: \'admin\'.",1000000,"usuarios","admin"),
(33,"2025-07-03 14:41:05","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(34,"2025-07-03 14:41:30","Usuario Editado","Usuario \'isa\' (Cédula: ) editado con perfil \'Vendedor\'.",1000000,"usuarios",NULL),
(35,"2025-07-03 15:09:35","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(36,"2025-07-03 15:11:21","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(37,"2025-07-03 15:15:17","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(39,"2025-07-03 15:15:25","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(40,"2025-07-03 15:15:42","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(43,"2025-07-03 15:15:59","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(45,"2025-07-03 15:16:06","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(47,"2025-07-03 15:16:15","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(51,"2025-07-03 15:17:02","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(55,"2025-07-03 15:17:41","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(57,"2025-07-03 15:18:21","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(58,"2025-07-03 15:18:28","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(64,"2025-07-03 15:18:46","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(66,"2025-07-03 15:18:50","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(69,"2025-07-03 15:19:00","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(71,"2025-07-03 15:19:17","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(72,"2025-07-03 15:19:39","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(73,"2025-07-03 15:20:59","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(74,"2025-07-03 15:22:13","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(76,"2025-07-03 15:22:21","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(79,"2025-07-03 15:27:58","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(82,"2025-07-03 15:28:11","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(85,"2025-07-03 15:28:24","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(87,"2025-07-03 15:28:30","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(89,"2025-07-03 15:29:15","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(90,"2025-07-03 15:30:35","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(91,"2025-07-03 15:31:05","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(92,"2025-07-03 15:31:21","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(94,"2025-07-03 15:31:27","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(95,"2025-07-03 15:32:01","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(96,"2025-07-03 15:34:57","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(98,"2025-07-03 15:35:06","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(100,"2025-07-03 15:35:14","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(103,"2025-07-03 15:35:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(104,"2025-07-03 15:38:28","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(105,"2025-07-03 15:39:12","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(107,"2025-07-03 15:39:50","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(110,"2025-07-03 15:43:43","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(112,"2025-07-03 15:43:54","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(113,"2025-07-03 15:44:11","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(114,"2025-07-03 15:44:11","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(116,"2025-07-03 15:44:23","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(122,"2025-07-03 15:52:56","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(123,"2025-07-03 15:53:12","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(124,"2025-07-03 15:54:26","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(125,"2025-07-03 15:54:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(126,"2025-07-03 15:55:27","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(127,"2025-07-03 15:55:37","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(128,"2025-07-03 15:56:12","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(129,"2025-07-03 16:08:05","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(130,"2025-07-03 16:08:15","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(131,"2025-07-03 16:08:20","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(132,"2025-07-03 16:08:40","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(133,"2025-07-03 16:11:19","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(134,"2025-07-03 16:13:49","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(135,"2025-07-03 16:14:08","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(136,"2025-07-03 16:15:06","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(137,"2025-07-03 16:24:19","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(138,"2025-07-03 16:24:28","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(139,"2025-07-03 16:25:19","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(140,"2025-07-03 16:26:14","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(141,"2025-07-03 16:29:39","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(142,"2025-07-03 16:29:48","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(143,"2025-07-03 16:29:55","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(144,"2025-07-03 16:34:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(145,"2025-07-03 16:43:17","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(146,"2025-07-03 16:54:13","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(147,"2025-07-03 17:34:35","Usuario Editado","Usuario \'isa\' (Cédula: ) editado con perfil \'Vendedor\'.",1000000,"usuarios",NULL),
(148,"2025-07-03 17:34:47","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(149,"2025-07-03 17:34:59","Login Fallido","Contraseña incorrecta para el usuario: \'isa\'.",23194809,"usuarios","isa"),
(150,"2025-07-03 17:35:09","Login Exitoso","El empleado \'isa\' (Vendedor) ha iniciado sesión.",23194809,"usuarios","isa"),
(151,"2025-07-03 17:35:42","Cliente Editado","Datos del cliente \'Vanessita Teran\' (Cédula: 259103825) modificados.",23194809,"clientes",259103825),
(152,"2025-07-03 17:36:11","Cliente Editado","Datos del cliente \'Julio Carrasquero\' (Cédula: 908712984) modificados.",23194809,"clientes",908712984),
(153,"2025-07-03 17:37:13","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(154,"2025-07-04 04:05:27","Producto Eliminado","Producto \'cHEMISEssss\' (Código: 121342) eliminado.",1000000,"productos",121342),
(155,"2025-07-04 04:46:45","Producto Editado","Datos del producto \'cHEMISEssss\' (Código: 121342) modificados.",1000000,"productos",121342),
(156,"2025-07-04 04:47:02","Producto Editado","Datos del producto \'cHEMISEssss\' (Código: 121342) modificados.",1000000,"productos",121342),
(157,"2025-07-04 05:08:07","Producto Editado","Datos generales del producto \'pantalon\' (Código: 12333443-e) modificados.",1000000,"productos","12333443-e"),
(158,"2025-07-04 05:08:23","Producto Editado","Datos generales del producto \'pantalon\' (Código: 12333443-e) modificados.",1000000,"productos","12333443-e"),
(159,"2025-07-04 05:12:33","Producto Editado","Datos generales del producto \'cHEMISEssss\' (Código: 121342) modificados.",1000000,"productos",121342),
(160,"2025-07-04 05:18:01","Creación Producto Fallida","Intento de crear producto con datos inválidos (Descripción: zarcills, Código: 12333443-e4).",1000000,"productos","12333443-e4"),
(161,"2025-07-04 05:23:12","Creación Producto Fallida","Intento de crear producto con datos inválidos (Descripción: cHEMISEssss, Código: 12333443-e3).",1000000,"productos","12333443-e3"),
(162,"2025-07-04 05:24:29","Creación Producto Fallida","Intento de crear producto con datos inválidos (Descripción: cHEMISEssss, Código: 12333443-e3).",1000000,"productos","12333443-e3"),
(163,"2025-07-04 05:35:48","Producto Eliminado","Producto \'pantalon\' (Código: 12333443-e) eliminado.",1000000,"productos","12333443-e"),
(164,"2025-07-04 05:36:16","Producto Editado","Datos del producto \'cHEMISEssss\' (Código: 121342) modificados.",1000000,"productos",121342),
(165,"2025-07-04 06:32:55","Producto Creado","Producto \'cHEMISE\' (Código: 12333443-eo) creado.",1000000,"productos","12333443-eo"),
(166,"2025-07-04 06:55:16","Stock Modificado","Stock del producto (Código: 12333443-eo) actualizado a 2.",1000000,"productos","12333443-eo"),
(167,"2025-07-04 06:55:22","Stock Modificado","Stock del producto (Código: 121342) actualizado a 2.",1000000,"productos",121342),
(168,"2025-07-04 06:57:09","Producto Editado","Producto \'cHEMISEssss\' (Código: 121342) editado.",1000000,"productos",121342),
(169,"2025-07-04 06:57:20","Producto Editado","Producto \'cHEMISE\' (Código: 12333443-eo) editado.",1000000,"productos","12333443-eo"),
(170,"2025-07-04 06:57:25","Producto Eliminado","Producto \'N/A\' (Código: 12333443-eo) eliminado.",1000000,"productos","12333443-eo"),
(171,"2025-07-04 06:57:58","Producto Creado","Producto \'cHEMISE\' (Código: 12333443-eeee) creado.",1000000,"productos","12333443-eeee"),
(172,"2025-07-04 06:57:58","Producto Eliminado","Producto \'N/A\' (Código: 12333443-eo) eliminado.",1000000,"productos","12333443-eo"),
(173,"2025-07-04 06:58:53","Stock Modificado","Stock del producto (Código: 121342) actualizado a 2.",1000000,"productos",121342),
(174,"2025-07-04 06:59:26","Producto Creado","Producto \'cHEMISEssss\' (Código: 12333443-e) creado.",1000000,"productos","12333443-e"),
(175,"2025-07-04 07:00:40","Creación Producto Fallida","Intento de crear producto repetido (Código: 12333443-e).",1000000,"productos","12333443-e"),
(176,"2025-07-04 07:03:06","Creación Producto Fallida","Intento de crear producto repetido (Código: 12333443-e).",1000000,"productos","12333443-e"),
(177,"2025-07-04 07:03:11","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(178,"2025-07-04 07:03:19","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(179,"2025-07-04 07:03:26","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(180,"2025-07-04 07:03:34","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(181,"2025-07-04 07:06:05","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(182,"2025-07-04 07:06:21","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(183,"2025-07-04 07:06:45","Producto Editado","Producto \'cHEMISEssss\' (Código: 121342) editado.",1000000,"productos",121342),
(184,"2025-07-04 07:08:24","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(185,"2025-07-04 07:08:48","Cliente Editado","Datos del cliente \'Vanessita Teran\' (Cédula: 259103825) modificados.",1000000,"clientes",259103825),
(186,"2025-07-04 07:09:03","Producto Editado","Producto \'cHEMISEssss\' (Código: 12333443-e) editado.",1000000,"productos","12333443-e"),
(187,"2025-07-04 07:09:49","Producto Creado","Producto \'rtrtr\' (Código: 2342342424) creado.",1000000,"productos",2342342424),
(188,"2025-07-04 07:12:22","Producto Editado","Producto \'cHEMISEssss\' (Código: 121342) editado.",1000000,"productos",121342),
(189,"2025-07-04 07:12:34","Stock Modificado","Stock del producto (Código: 22ee2) actualizado a 2.",1000000,"productos","22ee2"),
(190,"2025-07-04 07:15:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(191,"2025-07-04 07:17:23","Stock Modificado","Stock del producto (Código: 2342342424) actualizado a 2.",1000000,"productos",2342342424),
(192,"2025-07-04 07:17:39","Stock Modificado","Stock del producto (Código: 2342342424) actualizado a 2.",1000000,"productos",2342342424),
(193,"2025-07-04 07:19:37","Stock Modificado","Stock del producto (Código: 2342342424) actualizado a 2.",1000000,"productos",2342342424),
(194,"2025-07-04 07:20:17","Proveedor Editado","Proveedor (RIF: J-999999990) editado. Empresa: \'rs222\' a \'rs222\'. Representante: \'erer arias\' a \'erer arias\'. Teléfono: \'0424-1111111\' a \'0426-1233333\'.",1000000,"proveedores","J-999999990"),
(195,"2025-07-04 07:22:49","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(196,"2025-07-04 07:46:57","Producto Creado","Producto \'eeeee\' (Código: 12122) registrado.",1000000,"productos",12122),
(197,"2025-07-04 14:01:21","Stock Actualizado","Stock del producto (Código: 12122) actualizado a 2 unidades.",1000000,"productos",12122),
(198,"2025-07-04 14:02:00","Producto Editado","Producto \'rtrtr\' (Código: 2342342424) editado.",1000000,"productos",2342342424),
(199,"2025-07-04 14:02:44","Producto Creado","Producto \'zarcills\' (Código: 1212-erer-4) creado.",1000000,"productos","1212-erer-4"),
(200,"2025-07-04 14:05:09","Producto Eliminado","Producto (Código: 12122) eliminado.",1000000,"productos",12122),
(206,"2025-07-04 15:12:36","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(207,"2025-07-04 15:12:37","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(208,"2025-07-04 15:24:03","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(209,"2025-07-04 15:24:28","Cliente Editado","Datos del cliente \'Vanessitas Teran\' (Cédula: 259103825) modificados.",1000000,"clientes",259103825),
(210,"2025-07-04 15:24:40","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(211,"2025-07-04 15:24:42","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(212,"2025-07-04 15:25:18","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(213,"2025-07-04 15:25:21","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(214,"2025-07-04 15:25:39","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(215,"2025-07-04 15:25:47","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(216,"2025-07-04 15:32:17","Usuario Creado","Nuevo usuario \'pedro13\' (Cédula: 3174101) con perfil \'Administrador\' fue creado.",1000000,"usuarios",3174101),
(217,"2025-07-04 15:33:44","Usuario Eliminado","Usuario \'3174101\' (Cédula: N/A) eliminado.",1000000,"usuarios","N/A"),
(218,"2025-07-04 15:40:56","Usuario Editado","Usuario \'isa\' (Cédula: ) editado con perfil \'Vendedor\'.",1000000,"usuarios",NULL),
(219,"2025-07-04 15:41:39","Usuario Editado","Usuario \'ricki\' (Cédula: ) editado con perfil \'Vendedor\'.",1000000,"usuarios",NULL),
(220,"2025-07-04 15:47:37","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(221,"2025-07-04 15:47:42","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(222,"2025-07-04 15:47:53","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(223,"2025-07-04 15:47:55","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(224,"2025-07-04 15:52:45","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(225,"2025-07-04 15:52:48","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(226,"2025-07-04 15:57:10","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(227,"2025-07-04 15:57:14","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(228,"2025-07-04 15:57:51","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(229,"2025-07-04 15:57:53","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(230,"2025-07-04 15:59:02","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(231,"2025-07-04 15:59:04","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(232,"2025-07-04 16:00:40","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(233,"2025-07-04 16:00:42","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(234,"2025-07-04 16:08:31","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(235,"2025-07-04 16:08:40","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(236,"2025-07-04 16:09:23","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(237,"2025-07-04 16:12:35","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(238,"2025-07-04 16:12:36","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(239,"2025-07-04 16:13:19","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(240,"2025-07-04 16:13:20","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(241,"2025-07-04 16:13:28","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(242,"2025-07-04 16:25:20","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(243,"2025-07-04 16:25:22","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(244,"2025-07-04 16:28:53","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(245,"2025-07-04 16:28:55","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(246,"2025-07-04 16:29:14","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(247,"2025-07-04 16:29:16","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(248,"2025-07-04 16:29:33","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(249,"2025-07-04 16:30:12","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(250,"2025-07-04 16:33:31","Edición Empleado Fallida","Intento de editar empleado con datos inválidos (Nombre: Jhoan, Cédula: 31744101). Validación de campos fallida.",1000000,"empleado",31744101),
(251,"2025-07-04 16:33:51","Edición Empleado Fallida","Intento de editar empleado con datos inválidos (Nombre: Jhoan, Cédula: 31744101). Validación de campos fallida.",1000000,"empleado",31744101),
(252,"2025-07-04 16:34:15","Cliente Eliminado","Cliente \'Jhoan Mejia\' (Cédula: 31744101) eliminado.",1000000,"clientes",31744101),
(253,"2025-07-05 17:04:34","Generación de Reporte PDF","Se generó un reporte Diario de Ventas en formato PDF. Total: 4.508,92 Bs.",1000000,"ventas","N/A"),
(254,"2025-07-05 17:04:35","Generación de Reporte PDF","Se generó un reporte Diario de Ventas en formato PDF. Total: 4.508,92 Bs.",1000000,"ventas","N/A"),
(255,"2025-07-05 20:20:43","Generación de Reporte PDF","Se generó un reporte Mensual de Ventas en formato PDF. Total: 9.213,88 Bs. Período: 07/2025",1000000,"ventas","N/A"),
(256,"2025-07-05 20:20:44","Generación de Reporte PDF","Se generó un reporte Mensual de Ventas en formato PDF. Total: 9.213,88 Bs. Período: 07/2025",1000000,"ventas","N/A"),
(257,"2025-07-05 20:23:05","Generación de Reporte PDF","Se generó un reporte Mensual de Ventas en formato PDF. Total: 18.576,45 Bs.",1000000,"ventas","N/A"),
(258,"2025-07-05 20:23:06","Generación de Reporte PDF","Se generó un reporte Mensual de Ventas en formato PDF. Total: 18.576,45 Bs.",1000000,"ventas","N/A"),
(259,"2025-07-05 20:24:44","Generación de Reporte PDF","Se generó un reporte Total de Ventas en formato PDF. Total: 18.576,45 Bs.",1000000,"ventas","N/A"),
(260,"2025-07-05 20:24:45","Generación de Reporte PDF","Se generó un reporte Total de Ventas en formato PDF. Total: 18.576,45 Bs.",1000000,"ventas","N/A"),
(261,"2025-07-05 20:25:30","Proveedor Editado","Proveedor (RIF: J-999999990) editado. Empresa: \'rs222\' a \'rs22\'. Representante: \'erer arias\' a \'erer arias\'. Teléfono: \'0426-1233333\' a \'0416-1222138\'.",1000000,"proveedores","J-999999990"),
(262,"2025-07-05 20:48:13","Producto Editado","Producto \'cHEMISEssss\' (Código: 12333443-e) editado.",1000000,"productos","12333443-e"),
(263,"2025-07-05 21:01:01","Producto Editado","Producto \'rtrtr\' (Código: 2342342424) editado.",1000000,"productos",2342342424),
(264,"2025-07-05 21:44:06","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(265,"2025-07-05 21:45:49","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(266,"2025-07-05 21:45:50","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(267,"2025-07-05 21:47:53","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(268,"2025-07-05 21:47:55","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(269,"2025-07-05 21:48:36","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(270,"2025-07-05 21:48:37","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(271,"2025-07-05 21:49:09","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al chariti\' (RIF: V-221234087) modificada.",1000000,"empresa","V-221234087"),
(272,"2025-07-05 21:49:32","Generación de Reporte PDF","Se generó un reporte Total de Ventas en formato PDF. Total: 77.780,45 Bs.",1000000,"ventas","N/A"),
(273,"2025-07-05 21:49:32","Generación de Reporte PDF","Se generó un reporte Total de Ventas en formato PDF. Total: 77.780,45 Bs.",1000000,"ventas","N/A"),
(274,"2025-07-05 21:49:44","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(275,"2025-07-05 21:50:03","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(276,"2025-07-05 21:52:57","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(277,"2025-07-05 21:53:00","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(278,"2025-07-05 21:58:39","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(279,"2025-07-05 21:58:41","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(280,"2025-07-05 22:21:45","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(281,"2025-07-05 22:22:16","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(282,"2025-07-05 22:22:16","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(283,"2025-07-05 22:22:31","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(284,"2025-07-05 22:22:33","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(285,"2025-07-05 22:23:20","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(286,"2025-07-05 22:24:45","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(287,"2025-07-05 22:24:47","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(288,"2025-07-05 22:25:48","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(289,"2025-07-05 22:31:27","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(290,"2025-07-05 22:31:39","Cliente Eliminado","Cliente \'Vanessitas Teran\' (Cédula: 259103825) eliminado.",1000000,"clientes",259103825),
(291,"2025-07-05 22:32:13","Cliente Editado","Datos del cliente \'francisco perdomo\' (Cédula: V-14600272) modificados.",1000000,"clientes","V-14600272"),
(292,"2025-07-05 22:35:58","Creación Cliente Fallida","Intento de crear cliente con cédula \'V-14600272\' fallido por ser repetida.",1000000,"clientes","V-14600272"),
(293,"2025-07-05 23:16:09","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada.",1000000,"empresa","V-221234083"),
(294,"2025-07-05 23:46:24","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada.",1000000,"empresa","V-221234083"),
(295,"2025-07-06 00:23:37","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada.",1000000,"empresa","V-221234083"),
(296,"2025-07-06 00:27:23","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 133,0000 -> 132,0000 USD.",1000000,"empresa","V-221234083"),
(297,"2025-07-06 00:30:27","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 132,00 -> 130,0000 USD.",1000000,"empresa","V-221234083"),
(298,"2025-07-06 00:31:11","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 130,00 -> 110,00 USD.",1000000,"empresa","V-221234083"),
(299,"2025-07-06 00:32:14","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 110,00 cambio a 115,00 USD.",1000000,"empresa","V-221234083"),
(300,"2025-07-06 00:32:53","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Otros datos de configuración modificados.",1000000,"empresa","V-221234083"),
(301,"2025-07-06 00:33:39","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 115,00 cambio a 116,00 USD.",1000000,"empresa","V-221234083"),
(302,"2025-07-06 00:34:08","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 116,00 cambio a 115,00 .",1000000,"empresa","V-221234083"),
(303,"2025-07-06 01:06:20","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 115,00 cambio a 114,00 .",1000000,"empresa","V-221234083"),
(304,"2025-07-06 01:18:59","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: Anterior 114,0000 USD, Nuevo 113,0000 USD.",1000000,"empresa","V-221234083"),
(305,"2025-07-06 01:34:15","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: Anterior 113,0000 USD, Nuevo 114,0000 USD.",1000000,"empresa","V-221234083"),
(306,"2025-07-06 01:39:42","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(307,"2025-07-06 01:39:43","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A");


DROP TABLE IF EXISTS `historial_dolar`;

CREATE TABLE `historial_dolar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `precio_dolar` decimal(10,4) NOT NULL,
  `precio_anterior` decimal(10,4) DEFAULT NULL,
  `fecha_cambio` datetime NOT NULL,
  `estado_cambio` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO `historial_dolar` VALUES (1,"113.0000",NULL,"2025-07-06 07:48:58",NULL),
(2,"114.0000","113.0000","2025-07-06 08:04:14","Subió");


DROP TABLE IF EXISTS `marcas`;

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marca` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `marcas` VALUES (10,"adidas","2024-10-21 08:18:24"),
(11,"pocholin","2024-10-21 08:18:31"),
(13,"rs21","2024-10-21 08:18:44"),
(14,"Aero","2012-11-01 00:07:27"),
(15,"generica","2012-11-01 00:07:35"),
(16,"acadia","2012-11-01 00:07:46"),
(17,"sifrina","2012-11-01 00:07:59"),
(18,"NIKE","2024-10-25 18:02:27");


DROP TABLE IF EXISTS `productos`;

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
  `precio_venta` float NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `id_tipo` (`id_tipo`),
  KEY `id_categoria` (`id_categoria`,`id_color`,`id_marca`),
  KEY `id_color` (`id_color`),
  KEY `id_marca` (`id_marca`),
  KEY `rif_proveedor` (`rif_proveedor`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_color`) REFERENCES `colores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_4` FOREIGN KEY (`id_tipo`) REFERENCES `tipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_5` FOREIGN KEY (`rif_proveedor`) REFERENCES `proveedores` (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `productos` VALUES (7,5,1,11,122319847,1010,"MANILLA","vistas/img/productos/default/anonymous.png",11,2,"2.8"),
(10,5,1,16,2151351235,1212,"FORZA XL","vistas/img/productos/1212/907.jpg",6,2,"2.8"),
(7,5,1,11,122319847,1234,"camisa","vistas/img/productos/default/anonymous.png",6,5,7),
(7,5,1,11,122319847,1235,"bota","vistas/img/productos/default/anonymous.png",5,15,21),
(10,5,1,11,122319847,12874,"TOLTO","vistas/img/productos/default/anonymous.png",6,12,"16.8"),
(10,5,1,11,122319847,2000,"RELOG","vistas/img/productos/default/anonymous.png",14,2,"2.8"),
(9,5,1,11,122319847,2141,"LKjdakl","vistas/img/productos/2141/351.jpg",9,12,"16.8"),
(9,5,1,11,122319847,214123,1523,"vistas/img/productos/default/anonymous.png",305,12,"16.8"),
(10,5,10,11,122319847,"214u1","BOTAS","vistas/img/productos/default/anonymous.png",3,12,"16.8"),
(10,8,13,18,2151351235,21512356,"COLLARES AESTHETIC","vistas/img/productos/default/anonymous.png",9,2,1);


DROP TABLE IF EXISTS `proveedores`;

CREATE TABLE `proveedores` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cedula_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `correo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `proveedores` VALUES (122319847,"NIKE SPORT","Vanesa","Yepez","Caracas",128948912,921087,"vane@gmail.com"),
(2151351235,"TOTAL PANDA","Yanderson","Bastidas","Sotchi",1298412,291501235,"yan@gmail.com");


DROP TABLE IF EXISTS `tipos`;

CREATE TABLE `tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `tipos` VALUES (5,"caballero","2012-11-01 00:04:32"),
(6,"niño","2012-11-01 00:04:45"),
(7,"niña","2012-11-01 00:04:52"),
(8,"dama","2012-11-01 00:06:55");


DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `perfil` text COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL,
  PRIMARY KEY (`usuario`),
  KEY `cedula` (`cedula`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `usuarios` VALUES (23194809,"admin","$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG","Administrador",1),
(23194809,"francoarias81","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Administrador",1),
(40123456,"gsus","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Vendedor",1),
(31744101,"jotadevs","$2a$07$asxx54ahjppf45sd87a5auw6I7JoP8iFx7dMSn3sb4CatTnVVTCzi","Vendedor",1),
(12847231,"ricki","$2a$07$asxx54ahjppf45sd87a5auuA/wno05nzDP.bGv2s5tKCJKhZVQQgC","Administrador",1);


DROP TABLE IF EXISTS `venta_producto`;

CREATE TABLE `venta_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`),
  KEY `producto` (`producto`),
  CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `ventas` (`factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=111 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `venta_producto` VALUES (38,10001,1,1212),
(39,10002,1,1212),
(40,10002,2,1010),
(41,10002,2,21512356),
(42,10003,2,2000),
(43,10004,1,12874),
(44,10005,1,21512356),
(45,10005,1,"214u1"),
(46,10006,1,21512356),
(47,10006,1,"214u1"),
(48,10006,1,214123),
(49,10006,1,2141),
(50,10006,1,2000),
(51,10006,1,12874),
(52,10006,1,1235),
(53,10006,1,1234),
(54,10006,1,1212),
(55,10006,1,1010),
(56,10007,1,"214u1"),
(57,10008,1,214123),
(58,10009,1,214123),
(59,10010,1,2141),
(60,10011,1,1010),
(61,10012,1,1235),
(62,10012,1,12874),
(63,10012,1,2000),
(64,10013,1,2000),
(65,10014,1,12874),
(66,10015,1,1235),
(67,10016,1,21512356),
(68,10016,1,"214u1"),
(69,10017,1,2141),
(70,10017,1,214123),
(71,10017,1,"214u1"),
(72,10017,1,21512356),
(73,10018,1,21512356),
(74,10018,1,"214u1"),
(75,10018,1,214123),
(76,10019,1,21512356),
(77,10019,1,"214u1"),
(78,10019,1,2141),
(79,10019,1,2000),
(80,10020,1,21512356),
(81,10020,1,"214u1"),
(82,10020,1,214123),
(83,10021,1,214123),
(84,10021,1,2141),
(85,10022,1,21512356),
(86,10023,1,"214u1"),
(87,10024,1,21512356),
(88,10024,1,21512356),
(89,10025,1,21512356),
(90,10025,1,"214u1"),
(91,10025,1,214123),
(92,10025,1,2141),
(93,10026,1,"214u1"),
(94,10026,1,21512356),
(95,10027,1,1212),
(96,10028,1,21512356),
(97,10029,1,21512356),
(98,10030,1,1010),
(99,10030,1,1212),
(100,10030,1,1234),
(101,10031,1,21512356),
(102,10031,1,"214u1"),
(103,10032,1,21512356),
(104,10032,1,"214u1"),
(105,10032,1,214123),
(106,10032,1,2141),
(107,10033,1,21512356),
(108,10033,1,214123),
(109,10034,1,1212),
(110,10034,1,1235);


DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `factura` int(11) NOT NULL AUTO_INCREMENT,
  `rif_empresa` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `vendedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `total` float NOT NULL,
  `pago` float NOT NULL,
  `impuesto` int(11) NOT NULL DEFAULT '16',
  `metodo_pago` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cliente` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `rif_empresa` (`rif_empresa`),
  KEY `cliente` (`cliente`),
  KEY `vendedor` (`vendedor`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`rif_empresa`) REFERENCES `empresa` (`rif`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10035 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `ventas` VALUES (10001,"j-1234087",23194809,"120.18",100,16,"Transferencia","2024-11-02 03:16:58",12393100),
(10002,"j-1234087",23194809,"446.37",500,16,"Targeta","2024-11-02 03:19:36",12393100),
(10003,"j-1234087",23194809,"240.35",185,16,"Pago movil","2024-11-02 03:20:15",12400500),
(10004,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:19:32",12393100),
(10005,"j-1234087",23194809,"763.98","763.98",16,"Efectivo","2024-11-06 04:20:20",12393100),
(10006,"j-1234087",23194809,4,4,16,"Efectivo","2024-11-06 04:21:59",12400500),
(10007,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:22:38",12393100),
(10008,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:23:16",12393100),
(10009,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:24:39",12393100),
(10010,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:25:03",12400500),
(10011,"j-1234087",23194809,"120.18","120.18",16,"Efectivo","2024-11-06 04:25:28",12393100),
(10012,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:26:06",12400500),
(10013,"j-1234087",23194809,"120.18","120.18",16,"Efectivo","2024-11-06 04:26:39",12393100),
(10014,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:27:11",12400500),
(10015,"j-1234087",23194809,"901.32","901.32",16,"Targeta","2024-11-06 04:27:43",12393100),
(10016,"j-1234087",23194809,"763.98","763.98",16,"Efectivo","2024-11-06 04:28:22",12393100),
(10017,"j-1234087",23194809,2,2,16,"Efectivo","2024-11-06 04:30:15",12393100),
(10018,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:31:06",12393100),
(10019,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:31:54",12393100),
(10020,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:34:03",12393100),
(10021,"j-1234087",23194809,1,1,16,"Efectivo","2024-11-06 04:34:37",12400500),
(10022,"j-1234087",23194809,"42.92","42.92",16,"Efectivo","2024-11-06 04:35:40",12400500),
(10023,"j-1234087",23194809,"721.06","721.06",16,"Efectivo","2024-11-06 04:37:53",908712984),
(10024,"j-1234087",23194809,"85.84","85.84",16,"Efectivo","2024-11-06 04:38:41",31982314),
(10025,"j-1234087",23194809,2,2,16,"Efectivo","2024-11-06 04:39:43",12393100),
(10026,"j-1234087",23194809,"763.98","763.98",16,"Efectivo","2024-11-06 04:41:06",12393100),
(10027,"j-1234087",23194809,"120.18","120.18",16,"Efectivo","2024-11-06 05:06:36",12393100),
(10028,"j-1234087",23194809,"42.92","42.92",16,"Efectivo","2024-11-06 05:50:08",12400500),
(10029,"j-1234087",23194809,"47.56","47.56",16,"Efectivo","2024-11-06 07:10:00",12393100),
(10030,"j-1234087",23194809,"599.26","599.26",16,"Efectivo","2024-11-06 07:13:11",12400500),
(10031,"j-1234087",23194809,"846.57","846.57",16,"Targeta","2012-11-01 00:05:57",12393100),
(10032,"j-1234087",23194809,2,2,16,"Pago movil","2012-11-01 00:07:12",12393100),
(10033,"j-1234087",40123456,"846.57","846.57",16,"Efectivo","2012-11-01 00:11:48",259103825),
(10034,"j-1234087",23194809,1,1,16,"Transferencia","2012-11-01 00:44:43",259103825);


SET foreign_key_checks = 1;
CREATE DATABASE IF NOT EXISTS `zapateriak`;

USE `zapateriak`;

SET foreign_key_checks = 0;

DROP TABLE IF EXISTS `apartado`;

CREATE TABLE `apartado` (
  `factura` int(11) NOT NULL,
  `fecha_inicio` date NOT NULL,
  `fecha_fin` int(11) NOT NULL,
  `vendedor` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `abonado` float NOT NULL,
  `impuesto` int(11) NOT NULL,
  `cliente` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `vendedor` (`vendedor`,`cliente`),
  KEY `cliente` (`cliente`),
  CONSTRAINT `apartado_ibfk_1` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`),
  CONSTRAINT `apartado_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `apartado_producto`;

CREATE TABLE `apartado_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`,`producto`),
  KEY `producto` (`producto`),
  CONSTRAINT `apartado_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `apartado` (`factura`),
  CONSTRAINT `apartado_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;



DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `categorias` VALUES (7,"Ropa","2024-10-20 21:17:02"),
(9,"Calzados","2024-10-20 22:02:09"),
(10,"ACCESORIOS","2024-10-23 01:54:40"),
(12,"COMPONENTES","2024-10-30 01:38:07");


DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `apellido` text COLLATE utf8_spanish_ci NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `clientes` VALUES (12400500,"Ismael","Martinez","ismale@gmail.com",4127421201,"Tu casa"),
(259103825,"Vanessa","Teran","vane1q@gmail.com",2108974,"La loma"),
(31744101,"Jhoan","Mejia","mejiajhoan2021@gmail.com",4247079098,"Bisnaca"),
(31982314,"Esbeidy","Gil","esbeydui@gmail.com","Miticun",10987235),
(7689758,"Julio","Guanda","vane1q@gmail.com",4247079089,"Miticun"),
(908712984,"Julio","Carrasquero","vane@gmail.com",125123,"Miticun");


DROP TABLE IF EXISTS `colores`;

CREATE TABLE `colores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `colores` VALUES (1,"negro","2024-10-21 05:52:13"),
(10,"blanco","2024-10-21 08:17:13"),
(11,"verde","2024-10-21 08:17:24"),
(12,"beige","2012-11-01 00:08:15"),
(13,"TUTIFRUTTY","2024-10-26 20:34:51");


DROP TABLE IF EXISTS `empleado`;

CREATE TABLE `empleado` (
  `cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `foto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empleado` VALUES (12393320,432141325,"Francisco","Arias",""),
(12847231,9012754,"Ricardo","Yepez Bastidas",""),
(1290481,432141325,"DOUGLAS JOSUE","DEL VALLE",""),
(23194809,9102375089,"Isaac","Bastidas",""),
(3174101,172089720,"Jota","Mejia",""),
(31744101,4247079098,"Jhoan","Mejia",""),
(40123456,409879,"Francisco","Martinez","vistas/img/usuarios//661.jpg");


DROP TABLE IF EXISTS `empresa`;

CREATE TABLE `empresa` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio_dolar` float NOT NULL,
  `impuesto` float NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empresa` VALUES ("j-1234087","Centro de Bocono","Kamil Styles",1385132,37,16);


DROP TABLE IF EXISTS `event_log`;

CREATE TABLE `event_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `event_type` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `description` text COLLATE utf8_spanish2_ci NOT NULL,
  `employee_cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `affected_table` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `affected_row_id` varchar(100) COLLATE utf8_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_cedula` (`employee_cedula`),
  CONSTRAINT `event_log_ibfk_1` FOREIGN KEY (`employee_cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=308 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `event_log` VALUES (1,"2025-07-02 13:04:42","Creación Cliente Fallida","Intento de crear cliente con datos inválidos (nombre: francisco, cédula: V-12600272).",1000000,"clientes","V-12600272"),
(2,"2025-07-02 13:05:07","Creación Cliente Fallida","Intento de crear cliente con datos inválidos (nombre: deee, cédula: V-14600272).",1000000,"clientes","V-14600272"),
(3,"2025-07-02 13:05:56","Cliente Eliminado","Cliente \'Ismael Martinez\' (Cédula: 12400500) eliminado.",1000000,"clientes",12400500),
(4,"2025-07-02 14:49:58","Creación Cliente Fallida","Intento de crear cliente con datos inválidos (nombre: francisco, cédula: V-14600273).",1000000,"clientes","V-14600273"),
(5,"2025-07-02 15:05:52","Cliente Eliminado","Cliente \'Esbeidy Gil\' (Cédula: 31982314) eliminado.",1000000,"clientes",31982314),
(6,"2025-07-02 15:07:27","Cliente Eliminado","Cliente \'Julio Guanda\' (Cédula: 7689758) eliminado.",1000000,"clientes",7689758),
(7,"2025-07-02 15:13:58","Cliente Editado","Datos del cliente \'qwqwqwqw qwqwqqw\' (Cédula: ) modificados.",1000000,"clientes",""),
(8,"2025-07-02 15:15:43","Creación Cliente Fallida","Intento de crear cliente con datos inválidos (nombre: francis, cédula: V-14600271).",1000000,"clientes","V-14600271"),
(9,"2025-07-02 15:32:41","Cliente Editado","Datos del cliente \'Ismael sdsdsd\' (Cédula: ) modificados.",1000000,"clientes",""),
(10,"2025-07-02 15:33:22","Cliente Editado","Datos del cliente \'Ismael Mejia\' (Cédula: ) modificados.",1000000,"clientes",""),
(11,"2025-07-02 15:51:59","Cliente Editado","Datos del cliente \'Ismael sdsdsd\' (Cédula: ) modificados.",1000000,"clientes",""),
(12,"2025-07-02 18:35:56","Creación Cliente Fallida","Intento de crear cliente con datos inválidos (nombre: qwqw, cédula: V-12222222).",1000000,"clientes","V-12222222"),
(13,"2025-07-02 20:10:48","Cliente Creado","Nuevo cliente \'lola mento\' (Cédula: V-14600276) registrado.",1000000,"clientes","V-14600276"),
(14,"2025-07-03 03:50:57","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(15,"2025-07-03 05:04:20","Cliente Editado","Datos del cliente \'Ismael Mejia\' (Cédula: ) modificados.",1000000,"clientes",""),
(16,"2025-07-03 06:33:12","Cliente Editado","Datos del cliente \'qwq qwqw\' (Cédula: ) modificados.",1000000,"clientes",""),
(17,"2025-07-03 06:34:07","Cliente Creado","Nuevo cliente \'lola veinte\' (Cédula: V-12124441) registrado.",1000000,"clientes","V-12124441"),
(18,"2025-07-03 06:35:10","Cliente Editado","Datos del cliente \'Ismael cabeza arias\' (Cédula: ) modificados.",1000000,"clientes",""),
(19,"2025-07-03 06:37:29","Cliente Creado","Nuevo cliente \'francisco perdomo\' (Cédula: V-14600274) registrado.",1000000,"clientes","V-14600274"),
(20,"2025-07-03 07:01:40","Cliente Editado","Datos del cliente \'Vanessita Teran\' (Cédula: 259103825) modificados.",1000000,"clientes",259103825),
(21,"2025-07-03 07:27:13","Empleado Eliminado","Empleado \'Francisco Arias\' (Cédula: 12393320) eliminado con éxito.",1000000,"empleado",12393320),
(22,"2025-07-03 07:27:48","Empleado Creado","Nuevo empleado \'jose err\' (Cédula: E-14600272) registrado con éxito.",1000000,"empleado","E-14600272"),
(23,"2025-07-03 07:32:10","Edición Empleado Fallida","Intento de editar empleado con datos inválidos (Nombre: Jhoan, Cédula: 31744101). Validación de campos fallida.",1000000,"empleado",31744101),
(24,"2025-07-03 07:32:24","Edición Empleado Fallida","Intento de editar empleado con datos inválidos (Nombre: Jhoan, Cédula: 31744101). Validación de campos fallida.",1000000,"empleado",31744101),
(25,"2025-07-03 07:32:45","Edición Empleado Fallida","Intento de editar empleado con datos inválidos (Nombre: Isaac, Cédula: 23194809). Validación de campos fallida.",1000000,"empleado",23194809),
(26,"2025-07-03 09:16:57","Configuración Editada","Configuración de la empresa \'Kamyl Styles y Algo mas de Susana Al chariti\' (RIF: V-221234087) modificada.",1000000,"empresa","V-221234087"),
(27,"2025-07-03 09:22:04","Categoría Editada","Categoría \'Ropa\' (ID: 7) modificada a \'Ropas\'.",1000000,"categorias",7),
(28,"2025-07-03 09:35:55","Tipo Editado","Tipo \'caballeros\' (ID: 5) modificado a \'caballero\'.",1000000,"tipos",5),
(29,"2025-07-03 09:37:32","Marca Editada","Marca \'adidas\' (ID: 10) modificada a \'adidass\'.",1000000,"marcas",10),
(30,"2025-07-03 09:52:35","Proveedor Editado","Proveedor (RIF: J-999999990) editado. Empresa: \'rs22\' a \'rs222\'. Representante: \'erer arias\' a \'erer arias\'. Teléfono: \'0272-1200000\' a \'0424-1111111\'.",1000000,"proveedores","J-999999990"),
(31,"2025-07-03 14:40:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(32,"2025-07-03 14:40:59","Login Fallido","Contraseña incorrecta para el usuario: \'admin\'.",1000000,"usuarios","admin"),
(33,"2025-07-03 14:41:05","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(34,"2025-07-03 14:41:30","Usuario Editado","Usuario \'isa\' (Cédula: ) editado con perfil \'Vendedor\'.",1000000,"usuarios",NULL),
(35,"2025-07-03 15:09:35","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(36,"2025-07-03 15:11:21","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(37,"2025-07-03 15:15:17","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(39,"2025-07-03 15:15:25","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(40,"2025-07-03 15:15:42","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(43,"2025-07-03 15:15:59","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(45,"2025-07-03 15:16:06","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(47,"2025-07-03 15:16:15","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(51,"2025-07-03 15:17:02","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(55,"2025-07-03 15:17:41","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(57,"2025-07-03 15:18:21","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(58,"2025-07-03 15:18:28","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(64,"2025-07-03 15:18:46","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(66,"2025-07-03 15:18:50","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(69,"2025-07-03 15:19:00","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(71,"2025-07-03 15:19:17","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(72,"2025-07-03 15:19:39","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(73,"2025-07-03 15:20:59","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(74,"2025-07-03 15:22:13","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(76,"2025-07-03 15:22:21","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(79,"2025-07-03 15:27:58","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(82,"2025-07-03 15:28:11","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(85,"2025-07-03 15:28:24","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(87,"2025-07-03 15:28:30","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(89,"2025-07-03 15:29:15","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(90,"2025-07-03 15:30:35","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(91,"2025-07-03 15:31:05","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(92,"2025-07-03 15:31:21","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(94,"2025-07-03 15:31:27","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(95,"2025-07-03 15:32:01","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(96,"2025-07-03 15:34:57","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(98,"2025-07-03 15:35:06","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(100,"2025-07-03 15:35:14","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(103,"2025-07-03 15:35:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(104,"2025-07-03 15:38:28","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(105,"2025-07-03 15:39:12","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(107,"2025-07-03 15:39:50","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(110,"2025-07-03 15:43:43","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(112,"2025-07-03 15:43:54","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(113,"2025-07-03 15:44:11","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(114,"2025-07-03 15:44:11","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(116,"2025-07-03 15:44:23","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(122,"2025-07-03 15:52:56","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(123,"2025-07-03 15:53:12","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(124,"2025-07-03 15:54:26","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(125,"2025-07-03 15:54:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(126,"2025-07-03 15:55:27","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(127,"2025-07-03 15:55:37","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(128,"2025-07-03 15:56:12","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(129,"2025-07-03 16:08:05","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(130,"2025-07-03 16:08:15","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(131,"2025-07-03 16:08:20","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(132,"2025-07-03 16:08:40","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(133,"2025-07-03 16:11:19","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(134,"2025-07-03 16:13:49","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(135,"2025-07-03 16:14:08","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(136,"2025-07-03 16:15:06","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(137,"2025-07-03 16:24:19","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(138,"2025-07-03 16:24:28","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(139,"2025-07-03 16:25:19","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(140,"2025-07-03 16:26:14","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(141,"2025-07-03 16:29:39","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(142,"2025-07-03 16:29:48","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(143,"2025-07-03 16:29:55","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(144,"2025-07-03 16:34:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(145,"2025-07-03 16:43:17","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(146,"2025-07-03 16:54:13","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(147,"2025-07-03 17:34:35","Usuario Editado","Usuario \'isa\' (Cédula: ) editado con perfil \'Vendedor\'.",1000000,"usuarios",NULL),
(148,"2025-07-03 17:34:47","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(149,"2025-07-03 17:34:59","Login Fallido","Contraseña incorrecta para el usuario: \'isa\'.",23194809,"usuarios","isa"),
(150,"2025-07-03 17:35:09","Login Exitoso","El empleado \'isa\' (Vendedor) ha iniciado sesión.",23194809,"usuarios","isa"),
(151,"2025-07-03 17:35:42","Cliente Editado","Datos del cliente \'Vanessita Teran\' (Cédula: 259103825) modificados.",23194809,"clientes",259103825),
(152,"2025-07-03 17:36:11","Cliente Editado","Datos del cliente \'Julio Carrasquero\' (Cédula: 908712984) modificados.",23194809,"clientes",908712984),
(153,"2025-07-03 17:37:13","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(154,"2025-07-04 04:05:27","Producto Eliminado","Producto \'cHEMISEssss\' (Código: 121342) eliminado.",1000000,"productos",121342),
(155,"2025-07-04 04:46:45","Producto Editado","Datos del producto \'cHEMISEssss\' (Código: 121342) modificados.",1000000,"productos",121342),
(156,"2025-07-04 04:47:02","Producto Editado","Datos del producto \'cHEMISEssss\' (Código: 121342) modificados.",1000000,"productos",121342),
(157,"2025-07-04 05:08:07","Producto Editado","Datos generales del producto \'pantalon\' (Código: 12333443-e) modificados.",1000000,"productos","12333443-e"),
(158,"2025-07-04 05:08:23","Producto Editado","Datos generales del producto \'pantalon\' (Código: 12333443-e) modificados.",1000000,"productos","12333443-e"),
(159,"2025-07-04 05:12:33","Producto Editado","Datos generales del producto \'cHEMISEssss\' (Código: 121342) modificados.",1000000,"productos",121342),
(160,"2025-07-04 05:18:01","Creación Producto Fallida","Intento de crear producto con datos inválidos (Descripción: zarcills, Código: 12333443-e4).",1000000,"productos","12333443-e4"),
(161,"2025-07-04 05:23:12","Creación Producto Fallida","Intento de crear producto con datos inválidos (Descripción: cHEMISEssss, Código: 12333443-e3).",1000000,"productos","12333443-e3"),
(162,"2025-07-04 05:24:29","Creación Producto Fallida","Intento de crear producto con datos inválidos (Descripción: cHEMISEssss, Código: 12333443-e3).",1000000,"productos","12333443-e3"),
(163,"2025-07-04 05:35:48","Producto Eliminado","Producto \'pantalon\' (Código: 12333443-e) eliminado.",1000000,"productos","12333443-e"),
(164,"2025-07-04 05:36:16","Producto Editado","Datos del producto \'cHEMISEssss\' (Código: 121342) modificados.",1000000,"productos",121342),
(165,"2025-07-04 06:32:55","Producto Creado","Producto \'cHEMISE\' (Código: 12333443-eo) creado.",1000000,"productos","12333443-eo"),
(166,"2025-07-04 06:55:16","Stock Modificado","Stock del producto (Código: 12333443-eo) actualizado a 2.",1000000,"productos","12333443-eo"),
(167,"2025-07-04 06:55:22","Stock Modificado","Stock del producto (Código: 121342) actualizado a 2.",1000000,"productos",121342),
(168,"2025-07-04 06:57:09","Producto Editado","Producto \'cHEMISEssss\' (Código: 121342) editado.",1000000,"productos",121342),
(169,"2025-07-04 06:57:20","Producto Editado","Producto \'cHEMISE\' (Código: 12333443-eo) editado.",1000000,"productos","12333443-eo"),
(170,"2025-07-04 06:57:25","Producto Eliminado","Producto \'N/A\' (Código: 12333443-eo) eliminado.",1000000,"productos","12333443-eo"),
(171,"2025-07-04 06:57:58","Producto Creado","Producto \'cHEMISE\' (Código: 12333443-eeee) creado.",1000000,"productos","12333443-eeee"),
(172,"2025-07-04 06:57:58","Producto Eliminado","Producto \'N/A\' (Código: 12333443-eo) eliminado.",1000000,"productos","12333443-eo"),
(173,"2025-07-04 06:58:53","Stock Modificado","Stock del producto (Código: 121342) actualizado a 2.",1000000,"productos",121342),
(174,"2025-07-04 06:59:26","Producto Creado","Producto \'cHEMISEssss\' (Código: 12333443-e) creado.",1000000,"productos","12333443-e"),
(175,"2025-07-04 07:00:40","Creación Producto Fallida","Intento de crear producto repetido (Código: 12333443-e).",1000000,"productos","12333443-e"),
(176,"2025-07-04 07:03:06","Creación Producto Fallida","Intento de crear producto repetido (Código: 12333443-e).",1000000,"productos","12333443-e"),
(177,"2025-07-04 07:03:11","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(178,"2025-07-04 07:03:19","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(179,"2025-07-04 07:03:26","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(180,"2025-07-04 07:03:34","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(181,"2025-07-04 07:06:05","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(182,"2025-07-04 07:06:21","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(183,"2025-07-04 07:06:45","Producto Editado","Producto \'cHEMISEssss\' (Código: 121342) editado.",1000000,"productos",121342),
(184,"2025-07-04 07:08:24","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(185,"2025-07-04 07:08:48","Cliente Editado","Datos del cliente \'Vanessita Teran\' (Cédula: 259103825) modificados.",1000000,"clientes",259103825),
(186,"2025-07-04 07:09:03","Producto Editado","Producto \'cHEMISEssss\' (Código: 12333443-e) editado.",1000000,"productos","12333443-e"),
(187,"2025-07-04 07:09:49","Producto Creado","Producto \'rtrtr\' (Código: 2342342424) creado.",1000000,"productos",2342342424),
(188,"2025-07-04 07:12:22","Producto Editado","Producto \'cHEMISEssss\' (Código: 121342) editado.",1000000,"productos",121342),
(189,"2025-07-04 07:12:34","Stock Modificado","Stock del producto (Código: 22ee2) actualizado a 2.",1000000,"productos","22ee2"),
(190,"2025-07-04 07:15:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(191,"2025-07-04 07:17:23","Stock Modificado","Stock del producto (Código: 2342342424) actualizado a 2.",1000000,"productos",2342342424),
(192,"2025-07-04 07:17:39","Stock Modificado","Stock del producto (Código: 2342342424) actualizado a 2.",1000000,"productos",2342342424),
(193,"2025-07-04 07:19:37","Stock Modificado","Stock del producto (Código: 2342342424) actualizado a 2.",1000000,"productos",2342342424),
(194,"2025-07-04 07:20:17","Proveedor Editado","Proveedor (RIF: J-999999990) editado. Empresa: \'rs222\' a \'rs222\'. Representante: \'erer arias\' a \'erer arias\'. Teléfono: \'0424-1111111\' a \'0426-1233333\'.",1000000,"proveedores","J-999999990"),
(195,"2025-07-04 07:22:49","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(196,"2025-07-04 07:46:57","Producto Creado","Producto \'eeeee\' (Código: 12122) registrado.",1000000,"productos",12122),
(197,"2025-07-04 14:01:21","Stock Actualizado","Stock del producto (Código: 12122) actualizado a 2 unidades.",1000000,"productos",12122),
(198,"2025-07-04 14:02:00","Producto Editado","Producto \'rtrtr\' (Código: 2342342424) editado.",1000000,"productos",2342342424),
(199,"2025-07-04 14:02:44","Producto Creado","Producto \'zarcills\' (Código: 1212-erer-4) creado.",1000000,"productos","1212-erer-4"),
(200,"2025-07-04 14:05:09","Producto Eliminado","Producto (Código: 12122) eliminado.",1000000,"productos",12122),
(206,"2025-07-04 15:12:36","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(207,"2025-07-04 15:12:37","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(208,"2025-07-04 15:24:03","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(209,"2025-07-04 15:24:28","Cliente Editado","Datos del cliente \'Vanessitas Teran\' (Cédula: 259103825) modificados.",1000000,"clientes",259103825),
(210,"2025-07-04 15:24:40","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(211,"2025-07-04 15:24:42","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(212,"2025-07-04 15:25:18","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(213,"2025-07-04 15:25:21","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(214,"2025-07-04 15:25:39","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(215,"2025-07-04 15:25:47","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(216,"2025-07-04 15:32:17","Usuario Creado","Nuevo usuario \'pedro13\' (Cédula: 3174101) con perfil \'Administrador\' fue creado.",1000000,"usuarios",3174101),
(217,"2025-07-04 15:33:44","Usuario Eliminado","Usuario \'3174101\' (Cédula: N/A) eliminado.",1000000,"usuarios","N/A"),
(218,"2025-07-04 15:40:56","Usuario Editado","Usuario \'isa\' (Cédula: ) editado con perfil \'Vendedor\'.",1000000,"usuarios",NULL),
(219,"2025-07-04 15:41:39","Usuario Editado","Usuario \'ricki\' (Cédula: ) editado con perfil \'Vendedor\'.",1000000,"usuarios",NULL),
(220,"2025-07-04 15:47:37","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(221,"2025-07-04 15:47:42","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(222,"2025-07-04 15:47:53","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(223,"2025-07-04 15:47:55","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(224,"2025-07-04 15:52:45","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(225,"2025-07-04 15:52:48","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(226,"2025-07-04 15:57:10","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(227,"2025-07-04 15:57:14","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(228,"2025-07-04 15:57:51","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(229,"2025-07-04 15:57:53","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(230,"2025-07-04 15:59:02","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(231,"2025-07-04 15:59:04","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(232,"2025-07-04 16:00:40","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(233,"2025-07-04 16:00:42","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(234,"2025-07-04 16:08:31","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(235,"2025-07-04 16:08:40","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(236,"2025-07-04 16:09:23","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(237,"2025-07-04 16:12:35","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(238,"2025-07-04 16:12:36","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(239,"2025-07-04 16:13:19","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(240,"2025-07-04 16:13:20","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(241,"2025-07-04 16:13:28","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(242,"2025-07-04 16:25:20","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(243,"2025-07-04 16:25:22","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(244,"2025-07-04 16:28:53","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(245,"2025-07-04 16:28:55","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(246,"2025-07-04 16:29:14","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(247,"2025-07-04 16:29:16","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(248,"2025-07-04 16:29:33","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(249,"2025-07-04 16:30:12","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(250,"2025-07-04 16:33:31","Edición Empleado Fallida","Intento de editar empleado con datos inválidos (Nombre: Jhoan, Cédula: 31744101). Validación de campos fallida.",1000000,"empleado",31744101),
(251,"2025-07-04 16:33:51","Edición Empleado Fallida","Intento de editar empleado con datos inválidos (Nombre: Jhoan, Cédula: 31744101). Validación de campos fallida.",1000000,"empleado",31744101),
(252,"2025-07-04 16:34:15","Cliente Eliminado","Cliente \'Jhoan Mejia\' (Cédula: 31744101) eliminado.",1000000,"clientes",31744101),
(253,"2025-07-05 17:04:34","Generación de Reporte PDF","Se generó un reporte Diario de Ventas en formato PDF. Total: 4.508,92 Bs.",1000000,"ventas","N/A"),
(254,"2025-07-05 17:04:35","Generación de Reporte PDF","Se generó un reporte Diario de Ventas en formato PDF. Total: 4.508,92 Bs.",1000000,"ventas","N/A"),
(255,"2025-07-05 20:20:43","Generación de Reporte PDF","Se generó un reporte Mensual de Ventas en formato PDF. Total: 9.213,88 Bs. Período: 07/2025",1000000,"ventas","N/A"),
(256,"2025-07-05 20:20:44","Generación de Reporte PDF","Se generó un reporte Mensual de Ventas en formato PDF. Total: 9.213,88 Bs. Período: 07/2025",1000000,"ventas","N/A"),
(257,"2025-07-05 20:23:05","Generación de Reporte PDF","Se generó un reporte Mensual de Ventas en formato PDF. Total: 18.576,45 Bs.",1000000,"ventas","N/A"),
(258,"2025-07-05 20:23:06","Generación de Reporte PDF","Se generó un reporte Mensual de Ventas en formato PDF. Total: 18.576,45 Bs.",1000000,"ventas","N/A"),
(259,"2025-07-05 20:24:44","Generación de Reporte PDF","Se generó un reporte Total de Ventas en formato PDF. Total: 18.576,45 Bs.",1000000,"ventas","N/A"),
(260,"2025-07-05 20:24:45","Generación de Reporte PDF","Se generó un reporte Total de Ventas en formato PDF. Total: 18.576,45 Bs.",1000000,"ventas","N/A"),
(261,"2025-07-05 20:25:30","Proveedor Editado","Proveedor (RIF: J-999999990) editado. Empresa: \'rs222\' a \'rs22\'. Representante: \'erer arias\' a \'erer arias\'. Teléfono: \'0426-1233333\' a \'0416-1222138\'.",1000000,"proveedores","J-999999990"),
(262,"2025-07-05 20:48:13","Producto Editado","Producto \'cHEMISEssss\' (Código: 12333443-e) editado.",1000000,"productos","12333443-e"),
(263,"2025-07-05 21:01:01","Producto Editado","Producto \'rtrtr\' (Código: 2342342424) editado.",1000000,"productos",2342342424),
(264,"2025-07-05 21:44:06","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(265,"2025-07-05 21:45:49","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(266,"2025-07-05 21:45:50","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(267,"2025-07-05 21:47:53","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(268,"2025-07-05 21:47:55","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(269,"2025-07-05 21:48:36","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(270,"2025-07-05 21:48:37","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(271,"2025-07-05 21:49:09","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al chariti\' (RIF: V-221234087) modificada.",1000000,"empresa","V-221234087"),
(272,"2025-07-05 21:49:32","Generación de Reporte PDF","Se generó un reporte Total de Ventas en formato PDF. Total: 77.780,45 Bs.",1000000,"ventas","N/A"),
(273,"2025-07-05 21:49:32","Generación de Reporte PDF","Se generó un reporte Total de Ventas en formato PDF. Total: 77.780,45 Bs.",1000000,"ventas","N/A"),
(274,"2025-07-05 21:49:44","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(275,"2025-07-05 21:50:03","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(276,"2025-07-05 21:52:57","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(277,"2025-07-05 21:53:00","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(278,"2025-07-05 21:58:39","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(279,"2025-07-05 21:58:41","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(280,"2025-07-05 22:21:45","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(281,"2025-07-05 22:22:16","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(282,"2025-07-05 22:22:16","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(283,"2025-07-05 22:22:31","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(284,"2025-07-05 22:22:33","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(285,"2025-07-05 22:23:20","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(286,"2025-07-05 22:24:45","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(287,"2025-07-05 22:24:47","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(288,"2025-07-05 22:25:48","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(289,"2025-07-05 22:31:27","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(290,"2025-07-05 22:31:39","Cliente Eliminado","Cliente \'Vanessitas Teran\' (Cédula: 259103825) eliminado.",1000000,"clientes",259103825),
(291,"2025-07-05 22:32:13","Cliente Editado","Datos del cliente \'francisco perdomo\' (Cédula: V-14600272) modificados.",1000000,"clientes","V-14600272"),
(292,"2025-07-05 22:35:58","Creación Cliente Fallida","Intento de crear cliente con cédula \'V-14600272\' fallido por ser repetida.",1000000,"clientes","V-14600272"),
(293,"2025-07-05 23:16:09","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada.",1000000,"empresa","V-221234083"),
(294,"2025-07-05 23:46:24","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada.",1000000,"empresa","V-221234083"),
(295,"2025-07-06 00:23:37","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada.",1000000,"empresa","V-221234083"),
(296,"2025-07-06 00:27:23","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 133,0000 -> 132,0000 USD.",1000000,"empresa","V-221234083"),
(297,"2025-07-06 00:30:27","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 132,00 -> 130,0000 USD.",1000000,"empresa","V-221234083"),
(298,"2025-07-06 00:31:11","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 130,00 -> 110,00 USD.",1000000,"empresa","V-221234083"),
(299,"2025-07-06 00:32:14","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 110,00 cambio a 115,00 USD.",1000000,"empresa","V-221234083"),
(300,"2025-07-06 00:32:53","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Otros datos de configuración modificados.",1000000,"empresa","V-221234083"),
(301,"2025-07-06 00:33:39","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 115,00 cambio a 116,00 USD.",1000000,"empresa","V-221234083"),
(302,"2025-07-06 00:34:08","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 116,00 cambio a 115,00 .",1000000,"empresa","V-221234083"),
(303,"2025-07-06 01:06:20","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 115,00 cambio a 114,00 .",1000000,"empresa","V-221234083"),
(304,"2025-07-06 01:18:59","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: Anterior 114,0000 USD, Nuevo 113,0000 USD.",1000000,"empresa","V-221234083"),
(305,"2025-07-06 01:34:15","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: Anterior 113,0000 USD, Nuevo 114,0000 USD.",1000000,"empresa","V-221234083"),
(306,"2025-07-06 01:39:42","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(307,"2025-07-06 01:39:43","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A");


DROP TABLE IF EXISTS `historial_dolar`;

CREATE TABLE `historial_dolar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `precio_dolar` decimal(10,4) NOT NULL,
  `precio_anterior` decimal(10,4) DEFAULT NULL,
  `fecha_cambio` datetime NOT NULL,
  `estado_cambio` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO `historial_dolar` VALUES (1,"113.0000",NULL,"2025-07-06 07:48:58",NULL),
(2,"114.0000","113.0000","2025-07-06 08:04:14","Subió");


DROP TABLE IF EXISTS `marcas`;

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marca` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `marcas` VALUES (10,"adidas","2024-10-21 08:18:24"),
(11,"pocholin","2024-10-21 08:18:31"),
(13,"rs21","2024-10-21 08:18:44"),
(14,"Aero","2012-11-01 00:07:27"),
(15,"generica","2012-11-01 00:07:35"),
(16,"acadia","2012-11-01 00:07:46"),
(17,"sifrina","2012-11-01 00:07:59"),
(18,"NIKE","2024-10-25 18:02:27");


DROP TABLE IF EXISTS `productos`;

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
  `precio_venta` float NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `id_tipo` (`id_tipo`),
  KEY `id_categoria` (`id_categoria`,`id_color`,`id_marca`),
  KEY `id_color` (`id_color`),
  KEY `id_marca` (`id_marca`),
  KEY `rif_proveedor` (`rif_proveedor`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_color`) REFERENCES `colores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_4` FOREIGN KEY (`id_tipo`) REFERENCES `tipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_5` FOREIGN KEY (`rif_proveedor`) REFERENCES `proveedores` (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `productos` VALUES (7,5,1,11,122319847,1010,"MANILLA","vistas/img/productos/default/anonymous.png",14,2,"2.8"),
(10,5,1,16,2151351235,1212,"FORZA XL","vistas/img/productos/1212/907.jpg",10,2,"2.8"),
(7,5,1,11,122319847,1234,"camisa","vistas/img/productos/default/anonymous.png",8,5,7),
(7,5,1,11,122319847,1235,"bota","vistas/img/productos/default/anonymous.png",9,15,21),
(10,5,1,11,122319847,12874,"TOLTO","vistas/img/productos/default/anonymous.png",10,12,"16.8"),
(10,5,1,11,122319847,2000,"RELOG","vistas/img/productos/default/anonymous.png",17,2,"2.8"),
(9,5,1,11,122319847,2141,"LKjdakl","vistas/img/productos/2141/351.jpg",13,12,"16.8"),
(9,5,1,11,122319847,214123,1523,"vistas/img/productos/default/anonymous.png",307,12,"16.8"),
(10,5,10,11,122319847,"214u1","BOTAS","vistas/img/productos/default/anonymous.png",9,12,"16.8"),
(10,8,13,18,2151351235,21512356,"COLLARES AESTHETIC","vistas/img/productos/default/anonymous.png",18,"2.55","3.57");


DROP TABLE IF EXISTS `proveedores`;

CREATE TABLE `proveedores` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cedula_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `correo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `proveedores` VALUES (122319847,"NIKE SPORT","Vanesa","Yepez","Caracas",128948912,921087,"vane@gmail.com"),
(2151351235,"TOTAL PANDA","Yanderson","Bastidas","Sotchi",1298412,291501235,"yan@gmail.com");


DROP TABLE IF EXISTS `tipos`;

CREATE TABLE `tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `tipos` VALUES (5,"caballero","2012-11-01 00:04:32"),
(6,"niño","2012-11-01 00:04:45"),
(7,"niña","2012-11-01 00:04:52"),
(8,"dama","2012-11-01 00:06:55");


DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `perfil` text COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL,
  PRIMARY KEY (`usuario`),
  KEY `cedula` (`cedula`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `usuarios` VALUES (23194809,"admin","$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG","Administrador",1),
(23194809,"francoarias81","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Administrador",1),
(40123456,"gsus","$2a$07$asxx54ahjppf45sd87a5auJRR6foEJ7ynpjisKtbiKJbvJsoQ8VPS","Vendedor",0),
(31744101,"jotadevs","$2a$07$asxx54ahjppf45sd87a5auw6I7JoP8iFx7dMSn3sb4CatTnVVTCzi","Vendedor",1),
(12847231,"ricki","$2a$07$asxx54ahjppf45sd87a5auuA/wno05nzDP.bGv2s5tKCJKhZVQQgC","Administrador",1);


DROP TABLE IF EXISTS `venta_producto`;

CREATE TABLE `venta_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`),
  KEY `producto` (`producto`),
  CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `ventas` (`factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `venta_producto` VALUES (42,10003,2,2000),
(49,10004,1,21512356),
(50,10004,1,"214u1"),
(51,10004,1,214123),
(52,10004,1,2141),
(53,10005,1,214123),
(54,10006,1,21512356),
(55,10006,1,"214u1"),
(56,10006,1,214123),
(57,10007,1,21512356),
(58,10007,1,"214u1"),
(59,10007,1,214123),
(60,10007,1,2141),
(61,10007,1,2000),
(62,10008,1,21512356),
(63,10008,1,"214u1"),
(64,10008,1,214123),
(65,10008,1,2141),
(66,10009,1,21512356),
(67,10010,1,21512356),
(68,10010,1,"214u1"),
(69,10010,1,214123);


DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `factura` int(11) NOT NULL AUTO_INCREMENT,
  `rif_empresa` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `vendedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `total` float NOT NULL,
  `pago` float NOT NULL,
  `impuesto` float NOT NULL DEFAULT '16',
  `metodo_pago` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cliente` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `rif_empresa` (`rif_empresa`),
  KEY `cliente` (`cliente`),
  KEY `vendedor` (`vendedor`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`rif_empresa`) REFERENCES `empresa` (`rif`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10013 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `ventas` VALUES (10003,"j-1234087",23194809,"240.35",185,16,"Pago movil","2024-11-02 03:20:15",12400500),
(10004,"j-1234087",23194809,"2206.09",1,304,"Efectivo","2024-11-09 18:14:52",12400500),
(10005,"j-1234087",23194809,"721.056","621.6",99,"Efectivo","2024-11-09 18:15:31",12400500),
(10006,"j-1234087",23194809,"1485.03",1,204,"Efectivo","2024-11-09 18:16:26",12400500),
(10007,"j-1234087",23194809,"2326.26","2005.4",320,"Efectivo","2024-11-09 18:18:23",12400500),
(10008,"j-1234087",23194809,"2316.39","1996.89",319,"TJ-445656","2024-11-09 18:21:22",12400500),
(10009,"j-1234087",23194809,"153.224","132.09","21.1344","TJ-1234","2024-11-09 18:22:50",259103825),
(10010,"j-1234087",23194809,"1595.34","1375.29","220.05","Efectivo","2024-11-09 18:36:25",31744101);


SET foreign_key_checks = 1;
CREATE DATABASE IF NOT EXISTS `zapateriak`;

USE `zapateriak`;

SET foreign_key_checks = 0;

DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `categorias` VALUES (7,"Ropas","2025-07-03 09:22:04"),
(9,"Calzados","2024-10-20 22:02:09"),
(10,"ACCESORIOS","2024-10-23 01:54:40"),
(12,"COMPONENTES","2024-10-30 01:38:07");


DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `apellido` text COLLATE utf8_spanish_ci NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `clientes` VALUES (30124678,"jose angel","cabeza arias","jose@gmail.com",04120630245,"av sucre"),
(908712984,"Julio","Carrasquero","vane@gmail.com","0412-5555555","Miticun"),
("V-12124441","lola","veinte","francoarias81@gmail.com","0412-1222222","qwqwqwqw"),
("V-14600272","francisco","perdomo","francoarias81@gmail.com","0426-5555555","av- sucre 1-11"),
("V-14600274","francisco","perdomo","francoarias81@gmail.com","0416-1234556","Caracas"),
("V-14600276","lola","mento","francoarias81@gmail.com","0412-1232323","valera");


DROP TABLE IF EXISTS `colores`;

CREATE TABLE `colores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `colores` VALUES (1,"negro","2024-10-21 05:52:13"),
(10,"blanco","2024-10-21 08:17:13"),
(11,"verde","2024-10-21 08:17:24"),
(12,"beige","2012-11-01 00:08:15"),
(13,"TUTIFRUTTY","2024-10-26 20:34:51");


DROP TABLE IF EXISTS `empleado`;

CREATE TABLE `empleado` (
  `cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `foto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empleado` VALUES (1000000,121212,"super","usuario","bocono",""),
(12847231,9012754,"Ricardo","Yepez Bastidas","",""),
(23194809,9102375089,"Isaac","Bastidas","",""),
(3174101,172089720,"Jota","Mejia","",""),
(31744101,4247079098,"Jhoan","Mejia","El carmen",""),
("E-14600272","0272-1234455","jose","err","22e",""),
("V-14600278","0272-1232323","jose","ppe","av-sucre 1-11","");


DROP TABLE IF EXISTS `empresa`;

CREATE TABLE `empresa` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(80) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio_dolar` float NOT NULL,
  `impuesto` float NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empresa` VALUES ("V-221234083","Centro de Boconos","Kamyl Styl y Algo mas de Susana Al charitis",02721321223,114,16);


DROP TABLE IF EXISTS `event_log`;

CREATE TABLE `event_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `event_type` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `description` text COLLATE utf8_spanish2_ci NOT NULL,
  `employee_cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `affected_table` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `affected_row_id` varchar(100) COLLATE utf8_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_cedula` (`employee_cedula`),
  CONSTRAINT `event_log_ibfk_1` FOREIGN KEY (`employee_cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=308 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `event_log` VALUES (1,"2025-07-02 13:04:42","Creación Cliente Fallida","Intento de crear cliente con datos inválidos (nombre: francisco, cédula: V-12600272).",1000000,"clientes","V-12600272"),
(2,"2025-07-02 13:05:07","Creación Cliente Fallida","Intento de crear cliente con datos inválidos (nombre: deee, cédula: V-14600272).",1000000,"clientes","V-14600272"),
(3,"2025-07-02 13:05:56","Cliente Eliminado","Cliente \'Ismael Martinez\' (Cédula: 12400500) eliminado.",1000000,"clientes",12400500),
(4,"2025-07-02 14:49:58","Creación Cliente Fallida","Intento de crear cliente con datos inválidos (nombre: francisco, cédula: V-14600273).",1000000,"clientes","V-14600273"),
(5,"2025-07-02 15:05:52","Cliente Eliminado","Cliente \'Esbeidy Gil\' (Cédula: 31982314) eliminado.",1000000,"clientes",31982314),
(6,"2025-07-02 15:07:27","Cliente Eliminado","Cliente \'Julio Guanda\' (Cédula: 7689758) eliminado.",1000000,"clientes",7689758),
(7,"2025-07-02 15:13:58","Cliente Editado","Datos del cliente \'qwqwqwqw qwqwqqw\' (Cédula: ) modificados.",1000000,"clientes",""),
(8,"2025-07-02 15:15:43","Creación Cliente Fallida","Intento de crear cliente con datos inválidos (nombre: francis, cédula: V-14600271).",1000000,"clientes","V-14600271"),
(9,"2025-07-02 15:32:41","Cliente Editado","Datos del cliente \'Ismael sdsdsd\' (Cédula: ) modificados.",1000000,"clientes",""),
(10,"2025-07-02 15:33:22","Cliente Editado","Datos del cliente \'Ismael Mejia\' (Cédula: ) modificados.",1000000,"clientes",""),
(11,"2025-07-02 15:51:59","Cliente Editado","Datos del cliente \'Ismael sdsdsd\' (Cédula: ) modificados.",1000000,"clientes",""),
(12,"2025-07-02 18:35:56","Creación Cliente Fallida","Intento de crear cliente con datos inválidos (nombre: qwqw, cédula: V-12222222).",1000000,"clientes","V-12222222"),
(13,"2025-07-02 20:10:48","Cliente Creado","Nuevo cliente \'lola mento\' (Cédula: V-14600276) registrado.",1000000,"clientes","V-14600276"),
(14,"2025-07-03 03:50:57","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(15,"2025-07-03 05:04:20","Cliente Editado","Datos del cliente \'Ismael Mejia\' (Cédula: ) modificados.",1000000,"clientes",""),
(16,"2025-07-03 06:33:12","Cliente Editado","Datos del cliente \'qwq qwqw\' (Cédula: ) modificados.",1000000,"clientes",""),
(17,"2025-07-03 06:34:07","Cliente Creado","Nuevo cliente \'lola veinte\' (Cédula: V-12124441) registrado.",1000000,"clientes","V-12124441"),
(18,"2025-07-03 06:35:10","Cliente Editado","Datos del cliente \'Ismael cabeza arias\' (Cédula: ) modificados.",1000000,"clientes",""),
(19,"2025-07-03 06:37:29","Cliente Creado","Nuevo cliente \'francisco perdomo\' (Cédula: V-14600274) registrado.",1000000,"clientes","V-14600274"),
(20,"2025-07-03 07:01:40","Cliente Editado","Datos del cliente \'Vanessita Teran\' (Cédula: 259103825) modificados.",1000000,"clientes",259103825),
(21,"2025-07-03 07:27:13","Empleado Eliminado","Empleado \'Francisco Arias\' (Cédula: 12393320) eliminado con éxito.",1000000,"empleado",12393320),
(22,"2025-07-03 07:27:48","Empleado Creado","Nuevo empleado \'jose err\' (Cédula: E-14600272) registrado con éxito.",1000000,"empleado","E-14600272"),
(23,"2025-07-03 07:32:10","Edición Empleado Fallida","Intento de editar empleado con datos inválidos (Nombre: Jhoan, Cédula: 31744101). Validación de campos fallida.",1000000,"empleado",31744101),
(24,"2025-07-03 07:32:24","Edición Empleado Fallida","Intento de editar empleado con datos inválidos (Nombre: Jhoan, Cédula: 31744101). Validación de campos fallida.",1000000,"empleado",31744101),
(25,"2025-07-03 07:32:45","Edición Empleado Fallida","Intento de editar empleado con datos inválidos (Nombre: Isaac, Cédula: 23194809). Validación de campos fallida.",1000000,"empleado",23194809),
(26,"2025-07-03 09:16:57","Configuración Editada","Configuración de la empresa \'Kamyl Styles y Algo mas de Susana Al chariti\' (RIF: V-221234087) modificada.",1000000,"empresa","V-221234087"),
(27,"2025-07-03 09:22:04","Categoría Editada","Categoría \'Ropa\' (ID: 7) modificada a \'Ropas\'.",1000000,"categorias",7),
(28,"2025-07-03 09:35:55","Tipo Editado","Tipo \'caballeros\' (ID: 5) modificado a \'caballero\'.",1000000,"tipos",5),
(29,"2025-07-03 09:37:32","Marca Editada","Marca \'adidas\' (ID: 10) modificada a \'adidass\'.",1000000,"marcas",10),
(30,"2025-07-03 09:52:35","Proveedor Editado","Proveedor (RIF: J-999999990) editado. Empresa: \'rs22\' a \'rs222\'. Representante: \'erer arias\' a \'erer arias\'. Teléfono: \'0272-1200000\' a \'0424-1111111\'.",1000000,"proveedores","J-999999990"),
(31,"2025-07-03 14:40:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(32,"2025-07-03 14:40:59","Login Fallido","Contraseña incorrecta para el usuario: \'admin\'.",1000000,"usuarios","admin"),
(33,"2025-07-03 14:41:05","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(34,"2025-07-03 14:41:30","Usuario Editado","Usuario \'isa\' (Cédula: ) editado con perfil \'Vendedor\'.",1000000,"usuarios",NULL),
(35,"2025-07-03 15:09:35","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(36,"2025-07-03 15:11:21","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(37,"2025-07-03 15:15:17","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(39,"2025-07-03 15:15:25","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(40,"2025-07-03 15:15:42","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(43,"2025-07-03 15:15:59","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(45,"2025-07-03 15:16:06","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(47,"2025-07-03 15:16:15","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(51,"2025-07-03 15:17:02","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(55,"2025-07-03 15:17:41","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(57,"2025-07-03 15:18:21","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(58,"2025-07-03 15:18:28","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(64,"2025-07-03 15:18:46","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(66,"2025-07-03 15:18:50","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(69,"2025-07-03 15:19:00","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(71,"2025-07-03 15:19:17","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(72,"2025-07-03 15:19:39","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(73,"2025-07-03 15:20:59","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(74,"2025-07-03 15:22:13","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(76,"2025-07-03 15:22:21","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(79,"2025-07-03 15:27:58","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(82,"2025-07-03 15:28:11","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(85,"2025-07-03 15:28:24","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(87,"2025-07-03 15:28:30","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(89,"2025-07-03 15:29:15","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(90,"2025-07-03 15:30:35","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(91,"2025-07-03 15:31:05","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(92,"2025-07-03 15:31:21","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(94,"2025-07-03 15:31:27","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(95,"2025-07-03 15:32:01","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(96,"2025-07-03 15:34:57","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(98,"2025-07-03 15:35:06","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(100,"2025-07-03 15:35:14","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(103,"2025-07-03 15:35:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(104,"2025-07-03 15:38:28","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(105,"2025-07-03 15:39:12","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(107,"2025-07-03 15:39:50","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(110,"2025-07-03 15:43:43","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(112,"2025-07-03 15:43:54","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(113,"2025-07-03 15:44:11","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(114,"2025-07-03 15:44:11","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(116,"2025-07-03 15:44:23","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(122,"2025-07-03 15:52:56","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(123,"2025-07-03 15:53:12","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(124,"2025-07-03 15:54:26","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(125,"2025-07-03 15:54:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(126,"2025-07-03 15:55:27","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(127,"2025-07-03 15:55:37","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(128,"2025-07-03 15:56:12","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(129,"2025-07-03 16:08:05","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(130,"2025-07-03 16:08:15","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(131,"2025-07-03 16:08:20","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(132,"2025-07-03 16:08:40","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(133,"2025-07-03 16:11:19","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(134,"2025-07-03 16:13:49","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(135,"2025-07-03 16:14:08","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(136,"2025-07-03 16:15:06","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(137,"2025-07-03 16:24:19","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(138,"2025-07-03 16:24:28","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(139,"2025-07-03 16:25:19","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(140,"2025-07-03 16:26:14","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(141,"2025-07-03 16:29:39","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(142,"2025-07-03 16:29:48","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(143,"2025-07-03 16:29:55","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(144,"2025-07-03 16:34:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(145,"2025-07-03 16:43:17","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(146,"2025-07-03 16:54:13","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(147,"2025-07-03 17:34:35","Usuario Editado","Usuario \'isa\' (Cédula: ) editado con perfil \'Vendedor\'.",1000000,"usuarios",NULL),
(148,"2025-07-03 17:34:47","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(149,"2025-07-03 17:34:59","Login Fallido","Contraseña incorrecta para el usuario: \'isa\'.",23194809,"usuarios","isa"),
(150,"2025-07-03 17:35:09","Login Exitoso","El empleado \'isa\' (Vendedor) ha iniciado sesión.",23194809,"usuarios","isa"),
(151,"2025-07-03 17:35:42","Cliente Editado","Datos del cliente \'Vanessita Teran\' (Cédula: 259103825) modificados.",23194809,"clientes",259103825),
(152,"2025-07-03 17:36:11","Cliente Editado","Datos del cliente \'Julio Carrasquero\' (Cédula: 908712984) modificados.",23194809,"clientes",908712984),
(153,"2025-07-03 17:37:13","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(154,"2025-07-04 04:05:27","Producto Eliminado","Producto \'cHEMISEssss\' (Código: 121342) eliminado.",1000000,"productos",121342),
(155,"2025-07-04 04:46:45","Producto Editado","Datos del producto \'cHEMISEssss\' (Código: 121342) modificados.",1000000,"productos",121342),
(156,"2025-07-04 04:47:02","Producto Editado","Datos del producto \'cHEMISEssss\' (Código: 121342) modificados.",1000000,"productos",121342),
(157,"2025-07-04 05:08:07","Producto Editado","Datos generales del producto \'pantalon\' (Código: 12333443-e) modificados.",1000000,"productos","12333443-e"),
(158,"2025-07-04 05:08:23","Producto Editado","Datos generales del producto \'pantalon\' (Código: 12333443-e) modificados.",1000000,"productos","12333443-e"),
(159,"2025-07-04 05:12:33","Producto Editado","Datos generales del producto \'cHEMISEssss\' (Código: 121342) modificados.",1000000,"productos",121342),
(160,"2025-07-04 05:18:01","Creación Producto Fallida","Intento de crear producto con datos inválidos (Descripción: zarcills, Código: 12333443-e4).",1000000,"productos","12333443-e4"),
(161,"2025-07-04 05:23:12","Creación Producto Fallida","Intento de crear producto con datos inválidos (Descripción: cHEMISEssss, Código: 12333443-e3).",1000000,"productos","12333443-e3"),
(162,"2025-07-04 05:24:29","Creación Producto Fallida","Intento de crear producto con datos inválidos (Descripción: cHEMISEssss, Código: 12333443-e3).",1000000,"productos","12333443-e3"),
(163,"2025-07-04 05:35:48","Producto Eliminado","Producto \'pantalon\' (Código: 12333443-e) eliminado.",1000000,"productos","12333443-e"),
(164,"2025-07-04 05:36:16","Producto Editado","Datos del producto \'cHEMISEssss\' (Código: 121342) modificados.",1000000,"productos",121342),
(165,"2025-07-04 06:32:55","Producto Creado","Producto \'cHEMISE\' (Código: 12333443-eo) creado.",1000000,"productos","12333443-eo"),
(166,"2025-07-04 06:55:16","Stock Modificado","Stock del producto (Código: 12333443-eo) actualizado a 2.",1000000,"productos","12333443-eo"),
(167,"2025-07-04 06:55:22","Stock Modificado","Stock del producto (Código: 121342) actualizado a 2.",1000000,"productos",121342),
(168,"2025-07-04 06:57:09","Producto Editado","Producto \'cHEMISEssss\' (Código: 121342) editado.",1000000,"productos",121342),
(169,"2025-07-04 06:57:20","Producto Editado","Producto \'cHEMISE\' (Código: 12333443-eo) editado.",1000000,"productos","12333443-eo"),
(170,"2025-07-04 06:57:25","Producto Eliminado","Producto \'N/A\' (Código: 12333443-eo) eliminado.",1000000,"productos","12333443-eo"),
(171,"2025-07-04 06:57:58","Producto Creado","Producto \'cHEMISE\' (Código: 12333443-eeee) creado.",1000000,"productos","12333443-eeee"),
(172,"2025-07-04 06:57:58","Producto Eliminado","Producto \'N/A\' (Código: 12333443-eo) eliminado.",1000000,"productos","12333443-eo"),
(173,"2025-07-04 06:58:53","Stock Modificado","Stock del producto (Código: 121342) actualizado a 2.",1000000,"productos",121342),
(174,"2025-07-04 06:59:26","Producto Creado","Producto \'cHEMISEssss\' (Código: 12333443-e) creado.",1000000,"productos","12333443-e"),
(175,"2025-07-04 07:00:40","Creación Producto Fallida","Intento de crear producto repetido (Código: 12333443-e).",1000000,"productos","12333443-e"),
(176,"2025-07-04 07:03:06","Creación Producto Fallida","Intento de crear producto repetido (Código: 12333443-e).",1000000,"productos","12333443-e"),
(177,"2025-07-04 07:03:11","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(178,"2025-07-04 07:03:19","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(179,"2025-07-04 07:03:26","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(180,"2025-07-04 07:03:34","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(181,"2025-07-04 07:06:05","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(182,"2025-07-04 07:06:21","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(183,"2025-07-04 07:06:45","Producto Editado","Producto \'cHEMISEssss\' (Código: 121342) editado.",1000000,"productos",121342),
(184,"2025-07-04 07:08:24","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(185,"2025-07-04 07:08:48","Cliente Editado","Datos del cliente \'Vanessita Teran\' (Cédula: 259103825) modificados.",1000000,"clientes",259103825),
(186,"2025-07-04 07:09:03","Producto Editado","Producto \'cHEMISEssss\' (Código: 12333443-e) editado.",1000000,"productos","12333443-e"),
(187,"2025-07-04 07:09:49","Producto Creado","Producto \'rtrtr\' (Código: 2342342424) creado.",1000000,"productos",2342342424),
(188,"2025-07-04 07:12:22","Producto Editado","Producto \'cHEMISEssss\' (Código: 121342) editado.",1000000,"productos",121342),
(189,"2025-07-04 07:12:34","Stock Modificado","Stock del producto (Código: 22ee2) actualizado a 2.",1000000,"productos","22ee2"),
(190,"2025-07-04 07:15:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(191,"2025-07-04 07:17:23","Stock Modificado","Stock del producto (Código: 2342342424) actualizado a 2.",1000000,"productos",2342342424),
(192,"2025-07-04 07:17:39","Stock Modificado","Stock del producto (Código: 2342342424) actualizado a 2.",1000000,"productos",2342342424),
(193,"2025-07-04 07:19:37","Stock Modificado","Stock del producto (Código: 2342342424) actualizado a 2.",1000000,"productos",2342342424),
(194,"2025-07-04 07:20:17","Proveedor Editado","Proveedor (RIF: J-999999990) editado. Empresa: \'rs222\' a \'rs222\'. Representante: \'erer arias\' a \'erer arias\'. Teléfono: \'0424-1111111\' a \'0426-1233333\'.",1000000,"proveedores","J-999999990"),
(195,"2025-07-04 07:22:49","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(196,"2025-07-04 07:46:57","Producto Creado","Producto \'eeeee\' (Código: 12122) registrado.",1000000,"productos",12122),
(197,"2025-07-04 14:01:21","Stock Actualizado","Stock del producto (Código: 12122) actualizado a 2 unidades.",1000000,"productos",12122),
(198,"2025-07-04 14:02:00","Producto Editado","Producto \'rtrtr\' (Código: 2342342424) editado.",1000000,"productos",2342342424),
(199,"2025-07-04 14:02:44","Producto Creado","Producto \'zarcills\' (Código: 1212-erer-4) creado.",1000000,"productos","1212-erer-4"),
(200,"2025-07-04 14:05:09","Producto Eliminado","Producto (Código: 12122) eliminado.",1000000,"productos",12122),
(206,"2025-07-04 15:12:36","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(207,"2025-07-04 15:12:37","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(208,"2025-07-04 15:24:03","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(209,"2025-07-04 15:24:28","Cliente Editado","Datos del cliente \'Vanessitas Teran\' (Cédula: 259103825) modificados.",1000000,"clientes",259103825),
(210,"2025-07-04 15:24:40","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(211,"2025-07-04 15:24:42","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(212,"2025-07-04 15:25:18","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(213,"2025-07-04 15:25:21","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(214,"2025-07-04 15:25:39","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(215,"2025-07-04 15:25:47","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(216,"2025-07-04 15:32:17","Usuario Creado","Nuevo usuario \'pedro13\' (Cédula: 3174101) con perfil \'Administrador\' fue creado.",1000000,"usuarios",3174101),
(217,"2025-07-04 15:33:44","Usuario Eliminado","Usuario \'3174101\' (Cédula: N/A) eliminado.",1000000,"usuarios","N/A"),
(218,"2025-07-04 15:40:56","Usuario Editado","Usuario \'isa\' (Cédula: ) editado con perfil \'Vendedor\'.",1000000,"usuarios",NULL),
(219,"2025-07-04 15:41:39","Usuario Editado","Usuario \'ricki\' (Cédula: ) editado con perfil \'Vendedor\'.",1000000,"usuarios",NULL),
(220,"2025-07-04 15:47:37","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(221,"2025-07-04 15:47:42","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(222,"2025-07-04 15:47:53","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(223,"2025-07-04 15:47:55","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(224,"2025-07-04 15:52:45","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(225,"2025-07-04 15:52:48","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(226,"2025-07-04 15:57:10","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(227,"2025-07-04 15:57:14","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(228,"2025-07-04 15:57:51","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(229,"2025-07-04 15:57:53","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(230,"2025-07-04 15:59:02","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(231,"2025-07-04 15:59:04","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(232,"2025-07-04 16:00:40","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(233,"2025-07-04 16:00:42","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(234,"2025-07-04 16:08:31","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(235,"2025-07-04 16:08:40","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(236,"2025-07-04 16:09:23","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(237,"2025-07-04 16:12:35","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(238,"2025-07-04 16:12:36","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(239,"2025-07-04 16:13:19","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(240,"2025-07-04 16:13:20","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(241,"2025-07-04 16:13:28","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(242,"2025-07-04 16:25:20","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(243,"2025-07-04 16:25:22","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(244,"2025-07-04 16:28:53","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(245,"2025-07-04 16:28:55","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(246,"2025-07-04 16:29:14","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(247,"2025-07-04 16:29:16","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(248,"2025-07-04 16:29:33","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(249,"2025-07-04 16:30:12","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(250,"2025-07-04 16:33:31","Edición Empleado Fallida","Intento de editar empleado con datos inválidos (Nombre: Jhoan, Cédula: 31744101). Validación de campos fallida.",1000000,"empleado",31744101),
(251,"2025-07-04 16:33:51","Edición Empleado Fallida","Intento de editar empleado con datos inválidos (Nombre: Jhoan, Cédula: 31744101). Validación de campos fallida.",1000000,"empleado",31744101),
(252,"2025-07-04 16:34:15","Cliente Eliminado","Cliente \'Jhoan Mejia\' (Cédula: 31744101) eliminado.",1000000,"clientes",31744101),
(253,"2025-07-05 17:04:34","Generación de Reporte PDF","Se generó un reporte Diario de Ventas en formato PDF. Total: 4.508,92 Bs.",1000000,"ventas","N/A"),
(254,"2025-07-05 17:04:35","Generación de Reporte PDF","Se generó un reporte Diario de Ventas en formato PDF. Total: 4.508,92 Bs.",1000000,"ventas","N/A"),
(255,"2025-07-05 20:20:43","Generación de Reporte PDF","Se generó un reporte Mensual de Ventas en formato PDF. Total: 9.213,88 Bs. Período: 07/2025",1000000,"ventas","N/A"),
(256,"2025-07-05 20:20:44","Generación de Reporte PDF","Se generó un reporte Mensual de Ventas en formato PDF. Total: 9.213,88 Bs. Período: 07/2025",1000000,"ventas","N/A"),
(257,"2025-07-05 20:23:05","Generación de Reporte PDF","Se generó un reporte Mensual de Ventas en formato PDF. Total: 18.576,45 Bs.",1000000,"ventas","N/A"),
(258,"2025-07-05 20:23:06","Generación de Reporte PDF","Se generó un reporte Mensual de Ventas en formato PDF. Total: 18.576,45 Bs.",1000000,"ventas","N/A"),
(259,"2025-07-05 20:24:44","Generación de Reporte PDF","Se generó un reporte Total de Ventas en formato PDF. Total: 18.576,45 Bs.",1000000,"ventas","N/A"),
(260,"2025-07-05 20:24:45","Generación de Reporte PDF","Se generó un reporte Total de Ventas en formato PDF. Total: 18.576,45 Bs.",1000000,"ventas","N/A"),
(261,"2025-07-05 20:25:30","Proveedor Editado","Proveedor (RIF: J-999999990) editado. Empresa: \'rs222\' a \'rs22\'. Representante: \'erer arias\' a \'erer arias\'. Teléfono: \'0426-1233333\' a \'0416-1222138\'.",1000000,"proveedores","J-999999990"),
(262,"2025-07-05 20:48:13","Producto Editado","Producto \'cHEMISEssss\' (Código: 12333443-e) editado.",1000000,"productos","12333443-e"),
(263,"2025-07-05 21:01:01","Producto Editado","Producto \'rtrtr\' (Código: 2342342424) editado.",1000000,"productos",2342342424),
(264,"2025-07-05 21:44:06","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(265,"2025-07-05 21:45:49","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(266,"2025-07-05 21:45:50","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(267,"2025-07-05 21:47:53","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(268,"2025-07-05 21:47:55","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(269,"2025-07-05 21:48:36","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(270,"2025-07-05 21:48:37","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(271,"2025-07-05 21:49:09","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al chariti\' (RIF: V-221234087) modificada.",1000000,"empresa","V-221234087"),
(272,"2025-07-05 21:49:32","Generación de Reporte PDF","Se generó un reporte Total de Ventas en formato PDF. Total: 77.780,45 Bs.",1000000,"ventas","N/A"),
(273,"2025-07-05 21:49:32","Generación de Reporte PDF","Se generó un reporte Total de Ventas en formato PDF. Total: 77.780,45 Bs.",1000000,"ventas","N/A"),
(274,"2025-07-05 21:49:44","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(275,"2025-07-05 21:50:03","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(276,"2025-07-05 21:52:57","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(277,"2025-07-05 21:53:00","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(278,"2025-07-05 21:58:39","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(279,"2025-07-05 21:58:41","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(280,"2025-07-05 22:21:45","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(281,"2025-07-05 22:22:16","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(282,"2025-07-05 22:22:16","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(283,"2025-07-05 22:22:31","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(284,"2025-07-05 22:22:33","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(285,"2025-07-05 22:23:20","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(286,"2025-07-05 22:24:45","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(287,"2025-07-05 22:24:47","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(288,"2025-07-05 22:25:48","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(289,"2025-07-05 22:31:27","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(290,"2025-07-05 22:31:39","Cliente Eliminado","Cliente \'Vanessitas Teran\' (Cédula: 259103825) eliminado.",1000000,"clientes",259103825),
(291,"2025-07-05 22:32:13","Cliente Editado","Datos del cliente \'francisco perdomo\' (Cédula: V-14600272) modificados.",1000000,"clientes","V-14600272"),
(292,"2025-07-05 22:35:58","Creación Cliente Fallida","Intento de crear cliente con cédula \'V-14600272\' fallido por ser repetida.",1000000,"clientes","V-14600272"),
(293,"2025-07-05 23:16:09","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada.",1000000,"empresa","V-221234083"),
(294,"2025-07-05 23:46:24","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada.",1000000,"empresa","V-221234083"),
(295,"2025-07-06 00:23:37","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada.",1000000,"empresa","V-221234083"),
(296,"2025-07-06 00:27:23","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 133,0000 -> 132,0000 USD.",1000000,"empresa","V-221234083"),
(297,"2025-07-06 00:30:27","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 132,00 -> 130,0000 USD.",1000000,"empresa","V-221234083"),
(298,"2025-07-06 00:31:11","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 130,00 -> 110,00 USD.",1000000,"empresa","V-221234083"),
(299,"2025-07-06 00:32:14","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 110,00 cambio a 115,00 USD.",1000000,"empresa","V-221234083"),
(300,"2025-07-06 00:32:53","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Otros datos de configuración modificados.",1000000,"empresa","V-221234083"),
(301,"2025-07-06 00:33:39","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 115,00 cambio a 116,00 USD.",1000000,"empresa","V-221234083"),
(302,"2025-07-06 00:34:08","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 116,00 cambio a 115,00 .",1000000,"empresa","V-221234083"),
(303,"2025-07-06 01:06:20","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 115,00 cambio a 114,00 .",1000000,"empresa","V-221234083"),
(304,"2025-07-06 01:18:59","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: Anterior 114,0000 USD, Nuevo 113,0000 USD.",1000000,"empresa","V-221234083"),
(305,"2025-07-06 01:34:15","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: Anterior 113,0000 USD, Nuevo 114,0000 USD.",1000000,"empresa","V-221234083"),
(306,"2025-07-06 01:39:42","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(307,"2025-07-06 01:39:43","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A");


DROP TABLE IF EXISTS `historial_dolar`;

CREATE TABLE `historial_dolar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `precio_dolar` decimal(10,4) NOT NULL,
  `precio_anterior` decimal(10,4) DEFAULT NULL,
  `fecha_cambio` datetime NOT NULL,
  `estado_cambio` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO `historial_dolar` VALUES (1,"113.0000",NULL,"2025-07-06 07:48:58",NULL),
(2,"114.0000","113.0000","2025-07-06 08:04:14","Subió");


DROP TABLE IF EXISTS `marcas`;

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marca` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `marcas` VALUES (10,"adidass","2025-07-03 09:37:32"),
(11,"pocholin","2024-10-21 08:18:31"),
(13,"rs21","2024-10-21 08:18:44"),
(14,"Aero","2012-11-01 00:07:27"),
(15,"generica","2012-11-01 00:07:35"),
(16,"acadia","2012-11-01 00:07:46"),
(17,"sifrina","2012-11-01 00:07:59"),
(18,"NIKE","2024-10-25 18:02:27");


DROP TABLE IF EXISTS `productos`;

CREATE TABLE `productos` (
  `id_categoria` int(11) NOT NULL,
  `id_tipo` int(11) NOT NULL,
  `id_color` int(11) DEFAULT NULL,
  `id_marca` int(11) NOT NULL,
  `rif_proveedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `codigo` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `imagen` text COLLATE utf8_spanish_ci NOT NULL,
  `stock` int(11) DEFAULT NULL,
  `precio_compra` float NOT NULL,
  `precio_venta` float NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `id_tipo` (`id_tipo`),
  KEY `id_categoria` (`id_categoria`,`id_color`,`id_marca`),
  KEY `id_color` (`id_color`),
  KEY `id_marca` (`id_marca`),
  KEY `rif_proveedor` (`rif_proveedor`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_color`) REFERENCES `colores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_4` FOREIGN KEY (`id_tipo`) REFERENCES `tipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_5` FOREIGN KEY (`rif_proveedor`) REFERENCES `proveedores` (`rif`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `productos` VALUES (7,5,NULL,10,"J-999999990","1212-erer-4","zarcills","vistas/img/productos/default/anonymous.png",33,12,"15.6"),
(9,5,1,13,"J-999999990",121342,"cHEMISEssss","vistas/img/productos/default/anonymous.png",18,12,"15.6"),
(7,5,1,11,"J-999999990","12333443-e","cHEMISEssss","vistas/img/productos/default/anonymous.png",7,10,13),
(7,5,1,10,"J-999999990","22ee2","sdsd","vistas/img/productos/default/anonymous.png",12,12,"15.6"),
(7,5,10,10,"J-999999990",2342342424,"rtrtr","vistas/img/productos/default/anonymous.png",47,1,"1.3");


DROP TABLE IF EXISTS `proveedores`;

CREATE TABLE `proveedores` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cedula_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `correo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `proveedores` VALUES ("J-999999990","rs22","erer","arias","Caracas","V-12126666","0416-1222138","francoarias82@gmail.com");


DROP TABLE IF EXISTS `tipos`;

CREATE TABLE `tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `tipos` VALUES (5,"caballero","2025-07-03 09:35:55"),
(6,"niño","2012-11-01 00:04:45"),
(7,"niña","2012-11-01 00:04:52"),
(8,"dama","2012-11-01 00:06:55");


DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `perfil` text COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL,
  PRIMARY KEY (`usuario`),
  KEY `cedula` (`cedula`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `usuarios` VALUES (1000000,"admin","$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG","Administrador",1),
(23194809,"isa","$2a$07$asxx54ahjppf45sd87a5au4N7hmqIxxE/MQZ8CZlhGUldNNx6IO6a","Vendedor",1),
(12847231,"ricki","$2a$07$asxx54ahjppf45sd87a5auMKLpgpjghYypFQHTok48.zFSGAgdTrm","Vendedor",1);


DROP TABLE IF EXISTS `venta_producto`;

CREATE TABLE `venta_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`),
  KEY `producto` (`producto`),
  CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `ventas` (`factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `venta_producto` VALUES (1,10021,1,"12333443-e"),
(2,10022,2,2342342424),
(3,10022,3,"22ee2"),
(4,10022,5,"12333443-e"),
(5,10023,1,2342342424),
(6,10023,1,"22ee2"),
(7,10023,1,"12333443-e"),
(8,10023,1,121342),
(9,10023,1,"1212-erer-4");


DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `factura` int(11) NOT NULL AUTO_INCREMENT,
  `rif_empresa` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `vendedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `total` float NOT NULL,
  `pago` float NOT NULL,
  `impuesto` float NOT NULL DEFAULT '16',
  `metodo_pago` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cliente` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `rif_empresa` (`rif_empresa`),
  KEY `cliente` (`cliente`),
  KEY `vendedor` (`vendedor`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`rif_empresa`) REFERENCES `empresa` (`rif`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10024 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `ventas` VALUES (10002,"V-221234083",23194809,"1595.34","1375.29","220.05","Tarjeta-1234","2024-11-10 11:43:24",30124678),
(10004,"V-221234083",23194809,"1595.34","1375.29","220.05","Tarjeta-1234","2024-11-10 12:32:44",908712984),
(10015,"V-221234083",1000000,"111.592","96.2","15.39","Efectivo","2024-11-10 18:26:17",30124678),
(10019,"V-221234083",1000000,"669.552","577.2","92.35","Divisas-12","2025-06-14 16:41:49","V-14600272"),
(10021,"V-221234083",1000000,"4508.92",3887,"621.92","Divisas-30","2025-07-05 16:59:43","V-12124441"),
(10022,"V-221234083",1000000,"43324.8",37349,5,"Pago Movil-1232112","2025-07-05 20:26:18",908712984),
(10023,"V-221234083",1000000,"15879.2",13689,2,"Transferencia-676775443","2025-07-05 20:51:51","V-14600274");


SET foreign_key_checks = 1;
CREATE DATABASE IF NOT EXISTS `zapateriak`;

USE `zapateriak`;

SET foreign_key_checks = 0;

DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `categorias` VALUES (7,"Ropas","2025-07-03 09:22:04"),
(9,"Calzados","2024-10-20 22:02:09"),
(10,"ACCESORIOS","2024-10-23 01:54:40"),
(12,"COMPONENTES","2024-10-30 01:38:07");


DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `apellido` text COLLATE utf8_spanish_ci NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `clientes` VALUES (30124678,"jose angeles","cabeza arias","jose@gmail.com","0414-5555555","av sucre"),
(908712984,"Julio","Carrasquero","vane@gmail.com","0414-1212125","Miticun"),
("V-12124441","lola","veinte","francoarias81@gmail.com","0412-1222222","qwqwqwqw"),
("V-14600272","francisco","perdomo","francoarias81@gmail.com","0426-5555555","av- sucre 1-11"),
("V-14600274","francisco","perdomo","francoarias81@gmail.com","0416-1234556","Caracas"),
("V-14600276","lola","mento","francoarias81@gmail.com","0412-1232323","valera");


DROP TABLE IF EXISTS `colores`;

CREATE TABLE `colores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `colores` VALUES (1,"negro","2024-10-21 05:52:13"),
(10,"blanco","2024-10-21 08:17:13"),
(11,"verde","2024-10-21 08:17:24"),
(12,"beige","2012-11-01 00:08:15"),
(13,"TUTIFRUTTY","2024-10-26 20:34:51");


DROP TABLE IF EXISTS `empleado`;

CREATE TABLE `empleado` (
  `cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `foto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empleado` VALUES (1000000,121212,"super","usuario","bocono",""),
(12847231,9012754,"Ricardo","Yepez Bastidas","",""),
(23194809,9102375089,"Isaac","Bastidas","",""),
(3174101,172089720,"Jota","Mejia","",""),
(31744101,4247079098,"Jhoan","Mejia","El carmen",""),
("E-14600272","0272-1234455","jose","err","22e",""),
("V-14600278","0272-1232323","jose","ppe","av-sucre 1-11","");


DROP TABLE IF EXISTS `empresa`;

CREATE TABLE `empresa` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(80) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio_dolar` float NOT NULL,
  `impuesto` float NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empresa` VALUES ("V-221234083","Centro de Boconos","Kamyl Styl y Algo mas de Susana Al charitis",02721321223,114,16);


DROP TABLE IF EXISTS `event_log`;

CREATE TABLE `event_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `event_type` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `description` text COLLATE utf8_spanish2_ci NOT NULL,
  `employee_cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `affected_table` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `affected_row_id` varchar(100) COLLATE utf8_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_cedula` (`employee_cedula`),
  CONSTRAINT `event_log_ibfk_1` FOREIGN KEY (`employee_cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=389 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `event_log` VALUES (1,"2025-07-02 13:04:42","Creación Cliente Fallida","Intento de crear cliente con datos inválidos (nombre: francisco, cédula: V-12600272).",1000000,"clientes","V-12600272"),
(2,"2025-07-02 13:05:07","Creación Cliente Fallida","Intento de crear cliente con datos inválidos (nombre: deee, cédula: V-14600272).",1000000,"clientes","V-14600272"),
(3,"2025-07-02 13:05:56","Cliente Eliminado","Cliente \'Ismael Martinez\' (Cédula: 12400500) eliminado.",1000000,"clientes",12400500),
(4,"2025-07-02 14:49:58","Creación Cliente Fallida","Intento de crear cliente con datos inválidos (nombre: francisco, cédula: V-14600273).",1000000,"clientes","V-14600273"),
(5,"2025-07-02 15:05:52","Cliente Eliminado","Cliente \'Esbeidy Gil\' (Cédula: 31982314) eliminado.",1000000,"clientes",31982314),
(6,"2025-07-02 15:07:27","Cliente Eliminado","Cliente \'Julio Guanda\' (Cédula: 7689758) eliminado.",1000000,"clientes",7689758),
(7,"2025-07-02 15:13:58","Cliente Editado","Datos del cliente \'qwqwqwqw qwqwqqw\' (Cédula: ) modificados.",1000000,"clientes",""),
(8,"2025-07-02 15:15:43","Creación Cliente Fallida","Intento de crear cliente con datos inválidos (nombre: francis, cédula: V-14600271).",1000000,"clientes","V-14600271"),
(9,"2025-07-02 15:32:41","Cliente Editado","Datos del cliente \'Ismael sdsdsd\' (Cédula: ) modificados.",1000000,"clientes",""),
(10,"2025-07-02 15:33:22","Cliente Editado","Datos del cliente \'Ismael Mejia\' (Cédula: ) modificados.",1000000,"clientes",""),
(11,"2025-07-02 15:51:59","Cliente Editado","Datos del cliente \'Ismael sdsdsd\' (Cédula: ) modificados.",1000000,"clientes",""),
(12,"2025-07-02 18:35:56","Creación Cliente Fallida","Intento de crear cliente con datos inválidos (nombre: qwqw, cédula: V-12222222).",1000000,"clientes","V-12222222"),
(13,"2025-07-02 20:10:48","Cliente Creado","Nuevo cliente \'lola mento\' (Cédula: V-14600276) registrado.",1000000,"clientes","V-14600276"),
(14,"2025-07-03 03:50:57","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(15,"2025-07-03 05:04:20","Cliente Editado","Datos del cliente \'Ismael Mejia\' (Cédula: ) modificados.",1000000,"clientes",""),
(16,"2025-07-03 06:33:12","Cliente Editado","Datos del cliente \'qwq qwqw\' (Cédula: ) modificados.",1000000,"clientes",""),
(17,"2025-07-03 06:34:07","Cliente Creado","Nuevo cliente \'lola veinte\' (Cédula: V-12124441) registrado.",1000000,"clientes","V-12124441"),
(18,"2025-07-03 06:35:10","Cliente Editado","Datos del cliente \'Ismael cabeza arias\' (Cédula: ) modificados.",1000000,"clientes",""),
(19,"2025-07-03 06:37:29","Cliente Creado","Nuevo cliente \'francisco perdomo\' (Cédula: V-14600274) registrado.",1000000,"clientes","V-14600274"),
(20,"2025-07-03 07:01:40","Cliente Editado","Datos del cliente \'Vanessita Teran\' (Cédula: 259103825) modificados.",1000000,"clientes",259103825),
(21,"2025-07-03 07:27:13","Empleado Eliminado","Empleado \'Francisco Arias\' (Cédula: 12393320) eliminado con éxito.",1000000,"empleado",12393320),
(22,"2025-07-03 07:27:48","Empleado Creado","Nuevo empleado \'jose err\' (Cédula: E-14600272) registrado con éxito.",1000000,"empleado","E-14600272"),
(23,"2025-07-03 07:32:10","Edición Empleado Fallida","Intento de editar empleado con datos inválidos (Nombre: Jhoan, Cédula: 31744101). Validación de campos fallida.",1000000,"empleado",31744101),
(24,"2025-07-03 07:32:24","Edición Empleado Fallida","Intento de editar empleado con datos inválidos (Nombre: Jhoan, Cédula: 31744101). Validación de campos fallida.",1000000,"empleado",31744101),
(25,"2025-07-03 07:32:45","Edición Empleado Fallida","Intento de editar empleado con datos inválidos (Nombre: Isaac, Cédula: 23194809). Validación de campos fallida.",1000000,"empleado",23194809),
(26,"2025-07-03 09:16:57","Configuración Editada","Configuración de la empresa \'Kamyl Styles y Algo mas de Susana Al chariti\' (RIF: V-221234087) modificada.",1000000,"empresa","V-221234087"),
(27,"2025-07-03 09:22:04","Categoría Editada","Categoría \'Ropa\' (ID: 7) modificada a \'Ropas\'.",1000000,"categorias",7),
(28,"2025-07-03 09:35:55","Tipo Editado","Tipo \'caballeros\' (ID: 5) modificado a \'caballero\'.",1000000,"tipos",5),
(29,"2025-07-03 09:37:32","Marca Editada","Marca \'adidas\' (ID: 10) modificada a \'adidass\'.",1000000,"marcas",10),
(30,"2025-07-03 09:52:35","Proveedor Editado","Proveedor (RIF: J-999999990) editado. Empresa: \'rs22\' a \'rs222\'. Representante: \'erer arias\' a \'erer arias\'. Teléfono: \'0272-1200000\' a \'0424-1111111\'.",1000000,"proveedores","J-999999990"),
(31,"2025-07-03 14:40:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(32,"2025-07-03 14:40:59","Login Fallido","Contraseña incorrecta para el usuario: \'admin\'.",1000000,"usuarios","admin"),
(33,"2025-07-03 14:41:05","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(34,"2025-07-03 14:41:30","Usuario Editado","Usuario \'isa\' (Cédula: ) editado con perfil \'Vendedor\'.",1000000,"usuarios",NULL),
(35,"2025-07-03 15:09:35","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(36,"2025-07-03 15:11:21","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(37,"2025-07-03 15:15:17","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(39,"2025-07-03 15:15:25","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(40,"2025-07-03 15:15:42","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(43,"2025-07-03 15:15:59","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(45,"2025-07-03 15:16:06","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(47,"2025-07-03 15:16:15","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(51,"2025-07-03 15:17:02","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(55,"2025-07-03 15:17:41","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(57,"2025-07-03 15:18:21","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(58,"2025-07-03 15:18:28","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(64,"2025-07-03 15:18:46","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(66,"2025-07-03 15:18:50","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(69,"2025-07-03 15:19:00","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(71,"2025-07-03 15:19:17","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(72,"2025-07-03 15:19:39","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(73,"2025-07-03 15:20:59","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(74,"2025-07-03 15:22:13","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(76,"2025-07-03 15:22:21","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(79,"2025-07-03 15:27:58","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(82,"2025-07-03 15:28:11","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(85,"2025-07-03 15:28:24","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(87,"2025-07-03 15:28:30","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(89,"2025-07-03 15:29:15","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(90,"2025-07-03 15:30:35","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(91,"2025-07-03 15:31:05","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(92,"2025-07-03 15:31:21","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(94,"2025-07-03 15:31:27","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(95,"2025-07-03 15:32:01","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(96,"2025-07-03 15:34:57","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(98,"2025-07-03 15:35:06","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(100,"2025-07-03 15:35:14","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(103,"2025-07-03 15:35:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(104,"2025-07-03 15:38:28","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(105,"2025-07-03 15:39:12","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(107,"2025-07-03 15:39:50","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(110,"2025-07-03 15:43:43","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(112,"2025-07-03 15:43:54","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(113,"2025-07-03 15:44:11","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(114,"2025-07-03 15:44:11","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(116,"2025-07-03 15:44:23","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(122,"2025-07-03 15:52:56","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(123,"2025-07-03 15:53:12","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(124,"2025-07-03 15:54:26","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(125,"2025-07-03 15:54:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(126,"2025-07-03 15:55:27","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(127,"2025-07-03 15:55:37","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(128,"2025-07-03 15:56:12","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(129,"2025-07-03 16:08:05","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(130,"2025-07-03 16:08:15","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(131,"2025-07-03 16:08:20","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(132,"2025-07-03 16:08:40","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(133,"2025-07-03 16:11:19","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(134,"2025-07-03 16:13:49","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(135,"2025-07-03 16:14:08","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(136,"2025-07-03 16:15:06","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(137,"2025-07-03 16:24:19","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(138,"2025-07-03 16:24:28","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(139,"2025-07-03 16:25:19","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(140,"2025-07-03 16:26:14","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(141,"2025-07-03 16:29:39","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(142,"2025-07-03 16:29:48","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(143,"2025-07-03 16:29:55","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(144,"2025-07-03 16:34:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(145,"2025-07-03 16:43:17","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(146,"2025-07-03 16:54:13","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(147,"2025-07-03 17:34:35","Usuario Editado","Usuario \'isa\' (Cédula: ) editado con perfil \'Vendedor\'.",1000000,"usuarios",NULL),
(148,"2025-07-03 17:34:47","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(149,"2025-07-03 17:34:59","Login Fallido","Contraseña incorrecta para el usuario: \'isa\'.",23194809,"usuarios","isa"),
(150,"2025-07-03 17:35:09","Login Exitoso","El empleado \'isa\' (Vendedor) ha iniciado sesión.",23194809,"usuarios","isa"),
(151,"2025-07-03 17:35:42","Cliente Editado","Datos del cliente \'Vanessita Teran\' (Cédula: 259103825) modificados.",23194809,"clientes",259103825),
(152,"2025-07-03 17:36:11","Cliente Editado","Datos del cliente \'Julio Carrasquero\' (Cédula: 908712984) modificados.",23194809,"clientes",908712984),
(153,"2025-07-03 17:37:13","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(154,"2025-07-04 04:05:27","Producto Eliminado","Producto \'cHEMISEssss\' (Código: 121342) eliminado.",1000000,"productos",121342),
(155,"2025-07-04 04:46:45","Producto Editado","Datos del producto \'cHEMISEssss\' (Código: 121342) modificados.",1000000,"productos",121342),
(156,"2025-07-04 04:47:02","Producto Editado","Datos del producto \'cHEMISEssss\' (Código: 121342) modificados.",1000000,"productos",121342),
(157,"2025-07-04 05:08:07","Producto Editado","Datos generales del producto \'pantalon\' (Código: 12333443-e) modificados.",1000000,"productos","12333443-e"),
(158,"2025-07-04 05:08:23","Producto Editado","Datos generales del producto \'pantalon\' (Código: 12333443-e) modificados.",1000000,"productos","12333443-e"),
(159,"2025-07-04 05:12:33","Producto Editado","Datos generales del producto \'cHEMISEssss\' (Código: 121342) modificados.",1000000,"productos",121342),
(160,"2025-07-04 05:18:01","Creación Producto Fallida","Intento de crear producto con datos inválidos (Descripción: zarcills, Código: 12333443-e4).",1000000,"productos","12333443-e4"),
(161,"2025-07-04 05:23:12","Creación Producto Fallida","Intento de crear producto con datos inválidos (Descripción: cHEMISEssss, Código: 12333443-e3).",1000000,"productos","12333443-e3"),
(162,"2025-07-04 05:24:29","Creación Producto Fallida","Intento de crear producto con datos inválidos (Descripción: cHEMISEssss, Código: 12333443-e3).",1000000,"productos","12333443-e3"),
(163,"2025-07-04 05:35:48","Producto Eliminado","Producto \'pantalon\' (Código: 12333443-e) eliminado.",1000000,"productos","12333443-e"),
(164,"2025-07-04 05:36:16","Producto Editado","Datos del producto \'cHEMISEssss\' (Código: 121342) modificados.",1000000,"productos",121342),
(165,"2025-07-04 06:32:55","Producto Creado","Producto \'cHEMISE\' (Código: 12333443-eo) creado.",1000000,"productos","12333443-eo"),
(166,"2025-07-04 06:55:16","Stock Modificado","Stock del producto (Código: 12333443-eo) actualizado a 2.",1000000,"productos","12333443-eo"),
(167,"2025-07-04 06:55:22","Stock Modificado","Stock del producto (Código: 121342) actualizado a 2.",1000000,"productos",121342),
(168,"2025-07-04 06:57:09","Producto Editado","Producto \'cHEMISEssss\' (Código: 121342) editado.",1000000,"productos",121342),
(169,"2025-07-04 06:57:20","Producto Editado","Producto \'cHEMISE\' (Código: 12333443-eo) editado.",1000000,"productos","12333443-eo"),
(170,"2025-07-04 06:57:25","Producto Eliminado","Producto \'N/A\' (Código: 12333443-eo) eliminado.",1000000,"productos","12333443-eo"),
(171,"2025-07-04 06:57:58","Producto Creado","Producto \'cHEMISE\' (Código: 12333443-eeee) creado.",1000000,"productos","12333443-eeee"),
(172,"2025-07-04 06:57:58","Producto Eliminado","Producto \'N/A\' (Código: 12333443-eo) eliminado.",1000000,"productos","12333443-eo"),
(173,"2025-07-04 06:58:53","Stock Modificado","Stock del producto (Código: 121342) actualizado a 2.",1000000,"productos",121342),
(174,"2025-07-04 06:59:26","Producto Creado","Producto \'cHEMISEssss\' (Código: 12333443-e) creado.",1000000,"productos","12333443-e"),
(175,"2025-07-04 07:00:40","Creación Producto Fallida","Intento de crear producto repetido (Código: 12333443-e).",1000000,"productos","12333443-e"),
(176,"2025-07-04 07:03:06","Creación Producto Fallida","Intento de crear producto repetido (Código: 12333443-e).",1000000,"productos","12333443-e"),
(177,"2025-07-04 07:03:11","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(178,"2025-07-04 07:03:19","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(179,"2025-07-04 07:03:26","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(180,"2025-07-04 07:03:34","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(181,"2025-07-04 07:06:05","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(182,"2025-07-04 07:06:21","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(183,"2025-07-04 07:06:45","Producto Editado","Producto \'cHEMISEssss\' (Código: 121342) editado.",1000000,"productos",121342),
(184,"2025-07-04 07:08:24","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(185,"2025-07-04 07:08:48","Cliente Editado","Datos del cliente \'Vanessita Teran\' (Cédula: 259103825) modificados.",1000000,"clientes",259103825),
(186,"2025-07-04 07:09:03","Producto Editado","Producto \'cHEMISEssss\' (Código: 12333443-e) editado.",1000000,"productos","12333443-e"),
(187,"2025-07-04 07:09:49","Producto Creado","Producto \'rtrtr\' (Código: 2342342424) creado.",1000000,"productos",2342342424),
(188,"2025-07-04 07:12:22","Producto Editado","Producto \'cHEMISEssss\' (Código: 121342) editado.",1000000,"productos",121342),
(189,"2025-07-04 07:12:34","Stock Modificado","Stock del producto (Código: 22ee2) actualizado a 2.",1000000,"productos","22ee2"),
(190,"2025-07-04 07:15:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(191,"2025-07-04 07:17:23","Stock Modificado","Stock del producto (Código: 2342342424) actualizado a 2.",1000000,"productos",2342342424),
(192,"2025-07-04 07:17:39","Stock Modificado","Stock del producto (Código: 2342342424) actualizado a 2.",1000000,"productos",2342342424),
(193,"2025-07-04 07:19:37","Stock Modificado","Stock del producto (Código: 2342342424) actualizado a 2.",1000000,"productos",2342342424),
(194,"2025-07-04 07:20:17","Proveedor Editado","Proveedor (RIF: J-999999990) editado. Empresa: \'rs222\' a \'rs222\'. Representante: \'erer arias\' a \'erer arias\'. Teléfono: \'0424-1111111\' a \'0426-1233333\'.",1000000,"proveedores","J-999999990"),
(195,"2025-07-04 07:22:49","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(196,"2025-07-04 07:46:57","Producto Creado","Producto \'eeeee\' (Código: 12122) registrado.",1000000,"productos",12122),
(197,"2025-07-04 14:01:21","Stock Actualizado","Stock del producto (Código: 12122) actualizado a 2 unidades.",1000000,"productos",12122),
(198,"2025-07-04 14:02:00","Producto Editado","Producto \'rtrtr\' (Código: 2342342424) editado.",1000000,"productos",2342342424),
(199,"2025-07-04 14:02:44","Producto Creado","Producto \'zarcills\' (Código: 1212-erer-4) creado.",1000000,"productos","1212-erer-4"),
(200,"2025-07-04 14:05:09","Producto Eliminado","Producto (Código: 12122) eliminado.",1000000,"productos",12122),
(206,"2025-07-04 15:12:36","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(207,"2025-07-04 15:12:37","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(208,"2025-07-04 15:24:03","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(209,"2025-07-04 15:24:28","Cliente Editado","Datos del cliente \'Vanessitas Teran\' (Cédula: 259103825) modificados.",1000000,"clientes",259103825),
(210,"2025-07-04 15:24:40","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(211,"2025-07-04 15:24:42","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(212,"2025-07-04 15:25:18","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(213,"2025-07-04 15:25:21","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(214,"2025-07-04 15:25:39","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(215,"2025-07-04 15:25:47","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(216,"2025-07-04 15:32:17","Usuario Creado","Nuevo usuario \'pedro13\' (Cédula: 3174101) con perfil \'Administrador\' fue creado.",1000000,"usuarios",3174101),
(217,"2025-07-04 15:33:44","Usuario Eliminado","Usuario \'3174101\' (Cédula: N/A) eliminado.",1000000,"usuarios","N/A"),
(218,"2025-07-04 15:40:56","Usuario Editado","Usuario \'isa\' (Cédula: ) editado con perfil \'Vendedor\'.",1000000,"usuarios",NULL),
(219,"2025-07-04 15:41:39","Usuario Editado","Usuario \'ricki\' (Cédula: ) editado con perfil \'Vendedor\'.",1000000,"usuarios",NULL),
(220,"2025-07-04 15:47:37","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(221,"2025-07-04 15:47:42","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(222,"2025-07-04 15:47:53","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(223,"2025-07-04 15:47:55","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(224,"2025-07-04 15:52:45","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(225,"2025-07-04 15:52:48","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(226,"2025-07-04 15:57:10","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(227,"2025-07-04 15:57:14","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(228,"2025-07-04 15:57:51","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(229,"2025-07-04 15:57:53","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(230,"2025-07-04 15:59:02","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(231,"2025-07-04 15:59:04","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(232,"2025-07-04 16:00:40","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(233,"2025-07-04 16:00:42","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(234,"2025-07-04 16:08:31","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(235,"2025-07-04 16:08:40","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(236,"2025-07-04 16:09:23","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(237,"2025-07-04 16:12:35","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(238,"2025-07-04 16:12:36","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(239,"2025-07-04 16:13:19","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(240,"2025-07-04 16:13:20","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(241,"2025-07-04 16:13:28","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(242,"2025-07-04 16:25:20","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(243,"2025-07-04 16:25:22","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(244,"2025-07-04 16:28:53","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(245,"2025-07-04 16:28:55","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(246,"2025-07-04 16:29:14","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(247,"2025-07-04 16:29:16","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(248,"2025-07-04 16:29:33","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(249,"2025-07-04 16:30:12","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(250,"2025-07-04 16:33:31","Edición Empleado Fallida","Intento de editar empleado con datos inválidos (Nombre: Jhoan, Cédula: 31744101). Validación de campos fallida.",1000000,"empleado",31744101),
(251,"2025-07-04 16:33:51","Edición Empleado Fallida","Intento de editar empleado con datos inválidos (Nombre: Jhoan, Cédula: 31744101). Validación de campos fallida.",1000000,"empleado",31744101),
(252,"2025-07-04 16:34:15","Cliente Eliminado","Cliente \'Jhoan Mejia\' (Cédula: 31744101) eliminado.",1000000,"clientes",31744101),
(253,"2025-07-05 17:04:34","Generación de Reporte PDF","Se generó un reporte Diario de Ventas en formato PDF. Total: 4.508,92 Bs.",1000000,"ventas","N/A"),
(254,"2025-07-05 17:04:35","Generación de Reporte PDF","Se generó un reporte Diario de Ventas en formato PDF. Total: 4.508,92 Bs.",1000000,"ventas","N/A"),
(255,"2025-07-05 20:20:43","Generación de Reporte PDF","Se generó un reporte Mensual de Ventas en formato PDF. Total: 9.213,88 Bs. Período: 07/2025",1000000,"ventas","N/A"),
(256,"2025-07-05 20:20:44","Generación de Reporte PDF","Se generó un reporte Mensual de Ventas en formato PDF. Total: 9.213,88 Bs. Período: 07/2025",1000000,"ventas","N/A"),
(257,"2025-07-05 20:23:05","Generación de Reporte PDF","Se generó un reporte Mensual de Ventas en formato PDF. Total: 18.576,45 Bs.",1000000,"ventas","N/A"),
(258,"2025-07-05 20:23:06","Generación de Reporte PDF","Se generó un reporte Mensual de Ventas en formato PDF. Total: 18.576,45 Bs.",1000000,"ventas","N/A"),
(259,"2025-07-05 20:24:44","Generación de Reporte PDF","Se generó un reporte Total de Ventas en formato PDF. Total: 18.576,45 Bs.",1000000,"ventas","N/A"),
(260,"2025-07-05 20:24:45","Generación de Reporte PDF","Se generó un reporte Total de Ventas en formato PDF. Total: 18.576,45 Bs.",1000000,"ventas","N/A"),
(261,"2025-07-05 20:25:30","Proveedor Editado","Proveedor (RIF: J-999999990) editado. Empresa: \'rs222\' a \'rs22\'. Representante: \'erer arias\' a \'erer arias\'. Teléfono: \'0426-1233333\' a \'0416-1222138\'.",1000000,"proveedores","J-999999990"),
(262,"2025-07-05 20:48:13","Producto Editado","Producto \'cHEMISEssss\' (Código: 12333443-e) editado.",1000000,"productos","12333443-e"),
(263,"2025-07-05 21:01:01","Producto Editado","Producto \'rtrtr\' (Código: 2342342424) editado.",1000000,"productos",2342342424),
(264,"2025-07-05 21:44:06","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(265,"2025-07-05 21:45:49","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(266,"2025-07-05 21:45:50","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(267,"2025-07-05 21:47:53","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(268,"2025-07-05 21:47:55","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(269,"2025-07-05 21:48:36","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(270,"2025-07-05 21:48:37","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(271,"2025-07-05 21:49:09","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al chariti\' (RIF: V-221234087) modificada.",1000000,"empresa","V-221234087"),
(272,"2025-07-05 21:49:32","Generación de Reporte PDF","Se generó un reporte Total de Ventas en formato PDF. Total: 77.780,45 Bs.",1000000,"ventas","N/A"),
(273,"2025-07-05 21:49:32","Generación de Reporte PDF","Se generó un reporte Total de Ventas en formato PDF. Total: 77.780,45 Bs.",1000000,"ventas","N/A"),
(274,"2025-07-05 21:49:44","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(275,"2025-07-05 21:50:03","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(276,"2025-07-05 21:52:57","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(277,"2025-07-05 21:53:00","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(278,"2025-07-05 21:58:39","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(279,"2025-07-05 21:58:41","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(280,"2025-07-05 22:21:45","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(281,"2025-07-05 22:22:16","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(282,"2025-07-05 22:22:16","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(283,"2025-07-05 22:22:31","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(284,"2025-07-05 22:22:33","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(285,"2025-07-05 22:23:20","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(286,"2025-07-05 22:24:45","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(287,"2025-07-05 22:24:47","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(288,"2025-07-05 22:25:48","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(289,"2025-07-05 22:31:27","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(290,"2025-07-05 22:31:39","Cliente Eliminado","Cliente \'Vanessitas Teran\' (Cédula: 259103825) eliminado.",1000000,"clientes",259103825),
(291,"2025-07-05 22:32:13","Cliente Editado","Datos del cliente \'francisco perdomo\' (Cédula: V-14600272) modificados.",1000000,"clientes","V-14600272"),
(292,"2025-07-05 22:35:58","Creación Cliente Fallida","Intento de crear cliente con cédula \'V-14600272\' fallido por ser repetida.",1000000,"clientes","V-14600272"),
(293,"2025-07-05 23:16:09","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada.",1000000,"empresa","V-221234083"),
(294,"2025-07-05 23:46:24","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada.",1000000,"empresa","V-221234083"),
(295,"2025-07-06 00:23:37","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada.",1000000,"empresa","V-221234083"),
(296,"2025-07-06 00:27:23","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 133,0000 -> 132,0000 USD.",1000000,"empresa","V-221234083"),
(297,"2025-07-06 00:30:27","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 132,00 -> 130,0000 USD.",1000000,"empresa","V-221234083"),
(298,"2025-07-06 00:31:11","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 130,00 -> 110,00 USD.",1000000,"empresa","V-221234083"),
(299,"2025-07-06 00:32:14","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 110,00 cambio a 115,00 USD.",1000000,"empresa","V-221234083"),
(300,"2025-07-06 00:32:53","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Otros datos de configuración modificados.",1000000,"empresa","V-221234083"),
(301,"2025-07-06 00:33:39","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 115,00 cambio a 116,00 USD.",1000000,"empresa","V-221234083"),
(302,"2025-07-06 00:34:08","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 116,00 cambio a 115,00 .",1000000,"empresa","V-221234083"),
(303,"2025-07-06 01:06:20","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 115,00 cambio a 114,00 .",1000000,"empresa","V-221234083"),
(304,"2025-07-06 01:18:59","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: Anterior 114,0000 USD, Nuevo 113,0000 USD.",1000000,"empresa","V-221234083"),
(305,"2025-07-06 01:34:15","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: Anterior 113,0000 USD, Nuevo 114,0000 USD.",1000000,"empresa","V-221234083"),
(306,"2025-07-06 01:39:42","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(307,"2025-07-06 01:39:43","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(308,"2025-07-06 02:56:52","Login Fallido","Contraseña incorrecta para el usuario: \'isa\'.",23194809,"usuarios","isa"),
(309,"2025-07-06 02:57:00","Login Fallido","Contraseña incorrecta para el usuario: \'isa\'.",23194809,"usuarios","isa"),
(311,"2025-07-06 02:57:13","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(312,"2025-07-06 02:57:30","Login Fallido","Contraseña incorrecta para el usuario: \'admin\'.",1000000,"usuarios","admin"),
(316,"2025-07-06 02:58:15","Login Fallido","Contraseña incorrecta para el usuario: \'admin\'.",1000000,"usuarios","admin"),
(319,"2025-07-06 02:58:51","Login Fallido","Contraseña incorrecta para el usuario: \'ADMIN\'.",1000000,"usuarios","ADMIN"),
(320,"2025-07-06 02:59:03","Login Fallido","Contraseña incorrecta para el usuario: \'ADMIN\'.",1000000,"usuarios","ADMIN"),
(321,"2025-07-06 03:00:16","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(325,"2025-07-06 03:04:18","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(328,"2025-07-06 03:18:46","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(330,"2025-07-06 03:23:04","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(332,"2025-07-06 03:23:27","Login Fallido","Contraseña incorrecta para el usuario: \'admin\'.",1000000,"usuarios","admin"),
(333,"2025-07-06 03:23:35","Login Fallido","Contraseña incorrecta para el usuario: \'admin\'.",1000000,"usuarios","admin"),
(335,"2025-07-06 03:32:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(336,"2025-07-06 03:32:52","Login Fallido","Contraseña incorrecta para el usuario: \'ADMIN\'.",1000000,"usuarios","ADMIN"),
(337,"2025-07-06 03:33:07","Login Fallido","Contraseña incorrecta para el usuario: \'isa\'.",23194809,"usuarios","isa"),
(341,"2025-07-06 03:52:41","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(342,"2025-07-06 03:53:30","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(347,"2025-07-06 03:58:05","Login Fallido","Contraseña incorrecta para el usuario: \'admin\'.",1000000,"usuarios","admin"),
(348,"2025-07-06 03:58:24","Login Fallido","Contraseña incorrecta para el usuario: \'admin\'.",1000000,"usuarios","admin"),
(351,"2025-07-06 04:00:28","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(352,"2025-07-06 08:59:39","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(353,"2025-07-06 09:02:00","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(354,"2025-07-06 09:02:00","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(355,"2025-07-06 09:11:51","Cierre de Sesión","El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.",1000000,"usuarios","admin"),
(356,"2025-07-06 09:11:59","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(357,"2025-07-06 09:12:21","Cierre de Sesión","El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.",1000000,"usuarios","admin"),
(358,"2025-07-06 09:12:26","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(359,"2025-07-06 09:12:49","Cierre de Sesión","El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.",1000000,"usuarios","admin"),
(360,"2025-07-06 09:12:55","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(361,"2025-07-06 09:46:27","Cliente Editado","Datos del cliente \'jose angeles cabeza arias\' (Cédula: 30124678) modificados.",1000000,"clientes",30124678),
(362,"2025-07-06 10:01:30","Cierre de Sesión","El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.",1000000,"usuarios","admin"),
(363,"2025-07-06 10:03:29","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(364,"2025-07-06 10:04:06","Cierre de Sesión","El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.",1000000,"usuarios","admin"),
(365,"2025-07-06 10:10:07","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(366,"2025-07-06 10:11:41","Cierre de Sesión","El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.",1000000,"usuarios","admin"),
(367,"2025-07-06 10:11:49","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(368,"2025-07-06 10:33:38","Cierre de Sesión","El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.",1000000,"usuarios","admin"),
(369,"2025-07-06 10:33:46","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(370,"2025-07-06 10:35:27","Cliente Editado","Datos del cliente \'Julio Carrasquero\' (Cédula: 908712984) modificados.",1000000,"clientes",908712984),
(371,"2025-07-06 10:35:54","Cierre de Sesión","El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.",1000000,"usuarios","admin"),
(372,"2025-07-06 10:35:59","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(373,"2025-07-06 10:57:28","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(374,"2025-07-06 10:58:26","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(375,"2025-07-06 10:58:26","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(376,"2025-07-06 15:15:47","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(377,"2025-07-06 15:15:48","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(378,"2025-07-06 15:35:03","Generación de Reporte PDF","Se generó un reporte de Eventos del Sistema en formato PDF.",1000000,"event_log","N/A"),
(379,"2025-07-06 15:35:06","Generación de Reporte PDF","Se generó un reporte de Eventos del Sistema en formato PDF.",1000000,"event_log","N/A"),
(380,"2025-07-06 15:35:59","Generación de Reporte PDF","Se generó un reporte de Eventos del Sistema en formato PDF.",1000000,"event_log","N/A"),
(381,"2025-07-06 15:42:06","Generación de Reporte PDF","Se generó un reporte de Eventos del Sistema en formato PDF.",1000000,"event_log","N/A"),
(382,"2025-07-06 15:42:16","Generación de Reporte PDF","Se generó un reporte de Eventos del Sistema en formato PDF.",1000000,"event_log","N/A"),
(383,"2025-07-06 15:42:48","Cierre de Sesión","El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.",1000000,"usuarios","admin"),
(384,"2025-07-06 15:42:55","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(385,"2025-07-06 15:43:07","Generación de Reporte PDF","Se generó un reporte de Eventos del Sistema en formato PDF.",1000000,"event_log","N/A"),
(386,"2025-07-06 15:43:10","Generación de Reporte PDF","Se generó un reporte de Eventos del Sistema en formato PDF.",1000000,"event_log","N/A"),
(387,"2025-07-06 16:11:31","Generación de Reporte PDF","Se generó un reporte de Eventos del Sistema en formato PDF.",1000000,"event_log","N/A"),
(388,"2025-07-06 16:11:34","Generación de Reporte PDF","Se generó un reporte de Eventos del Sistema en formato PDF.",1000000,"event_log","N/A");


DROP TABLE IF EXISTS `historial_dolar`;

CREATE TABLE `historial_dolar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `precio_dolar` decimal(10,4) NOT NULL,
  `precio_anterior` decimal(10,4) DEFAULT NULL,
  `fecha_cambio` datetime NOT NULL,
  `estado_cambio` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO `historial_dolar` VALUES (1,"113.0000",NULL,"2025-07-06 07:48:58",NULL),
(2,"114.0000","113.0000","2025-07-06 08:04:14","Subió");


DROP TABLE IF EXISTS `marcas`;

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marca` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `marcas` VALUES (10,"adidass","2025-07-03 09:37:32"),
(11,"pocholin","2024-10-21 08:18:31"),
(13,"rs21","2024-10-21 08:18:44"),
(14,"Aero","2012-11-01 00:07:27"),
(15,"generica","2012-11-01 00:07:35"),
(16,"acadia","2012-11-01 00:07:46"),
(17,"sifrina","2012-11-01 00:07:59"),
(18,"NIKE","2024-10-25 18:02:27");


DROP TABLE IF EXISTS `productos`;

CREATE TABLE `productos` (
  `id_categoria` int(11) NOT NULL,
  `id_tipo` int(11) NOT NULL,
  `id_color` int(11) DEFAULT NULL,
  `id_marca` int(11) NOT NULL,
  `rif_proveedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `codigo` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `imagen` text COLLATE utf8_spanish_ci NOT NULL,
  `stock` int(11) DEFAULT NULL,
  `precio_compra` float NOT NULL,
  `precio_venta` float NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `id_tipo` (`id_tipo`),
  KEY `id_categoria` (`id_categoria`,`id_color`,`id_marca`),
  KEY `id_color` (`id_color`),
  KEY `id_marca` (`id_marca`),
  KEY `rif_proveedor` (`rif_proveedor`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_color`) REFERENCES `colores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_4` FOREIGN KEY (`id_tipo`) REFERENCES `tipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_5` FOREIGN KEY (`rif_proveedor`) REFERENCES `proveedores` (`rif`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `productos` VALUES (7,5,NULL,10,"J-999999990","1212-erer-4","zarcills","vistas/img/productos/default/anonymous.png",33,12,"15.6"),
(9,5,1,13,"J-999999990",121342,"cHEMISEssss","vistas/img/productos/default/anonymous.png",18,12,"15.6"),
(7,5,1,11,"J-999999990","12333443-e","cHEMISEssss","vistas/img/productos/default/anonymous.png",7,10,13),
(7,5,1,10,"J-999999990","22ee2","sdsd","vistas/img/productos/default/anonymous.png",12,12,"15.6"),
(7,5,10,10,"J-999999990",2342342424,"rtrtr","vistas/img/productos/default/anonymous.png",47,1,"1.3");


DROP TABLE IF EXISTS `proveedores`;

CREATE TABLE `proveedores` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cedula_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `correo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `proveedores` VALUES ("J-999999990","rs22","erer","arias","Caracas","V-12126666","0416-1222138","francoarias82@gmail.com");


DROP TABLE IF EXISTS `tipos`;

CREATE TABLE `tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `tipos` VALUES (5,"caballero","2025-07-03 09:35:55"),
(6,"niño","2012-11-01 00:04:45"),
(7,"niña","2012-11-01 00:04:52"),
(8,"dama","2012-11-01 00:06:55");


DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `perfil` text COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL,
  PRIMARY KEY (`usuario`),
  KEY `cedula` (`cedula`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `usuarios` VALUES (1000000,"admin","$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG","Administrador",1),
(23194809,"isa","$2a$07$asxx54ahjppf45sd87a5au4N7hmqIxxE/MQZ8CZlhGUldNNx6IO6a","Vendedor",1),
(12847231,"ricki","$2a$07$asxx54ahjppf45sd87a5auMKLpgpjghYypFQHTok48.zFSGAgdTrm","Vendedor",1);


DROP TABLE IF EXISTS `venta_producto`;

CREATE TABLE `venta_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`),
  KEY `producto` (`producto`),
  CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `ventas` (`factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `venta_producto` VALUES (1,10021,1,"12333443-e"),
(2,10022,2,2342342424),
(3,10022,3,"22ee2"),
(4,10022,5,"12333443-e"),
(5,10023,1,2342342424),
(6,10023,1,"22ee2"),
(7,10023,1,"12333443-e"),
(8,10023,1,121342),
(9,10023,1,"1212-erer-4");


DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `factura` int(11) NOT NULL AUTO_INCREMENT,
  `rif_empresa` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `vendedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `total` float NOT NULL,
  `pago` float NOT NULL,
  `impuesto` float NOT NULL DEFAULT '16',
  `metodo_pago` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cliente` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `rif_empresa` (`rif_empresa`),
  KEY `cliente` (`cliente`),
  KEY `vendedor` (`vendedor`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`rif_empresa`) REFERENCES `empresa` (`rif`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10024 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `ventas` VALUES (10002,"V-221234083",23194809,"1595.34","1375.29","220.05","Tarjeta-1234","2024-11-10 11:43:24",30124678),
(10004,"V-221234083",23194809,"1595.34","1375.29","220.05","Tarjeta-1234","2024-11-10 12:32:44",908712984),
(10015,"V-221234083",1000000,"111.592","96.2","15.39","Efectivo","2024-11-10 18:26:17",30124678),
(10019,"V-221234083",1000000,"669.552","577.2","92.35","Divisas-12","2025-06-14 16:41:49","V-14600272"),
(10021,"V-221234083",1000000,"4508.92",3887,"621.92","Divisas-30","2025-07-05 16:59:43","V-12124441"),
(10022,"V-221234083",1000000,"43324.8",37349,5,"Pago Movil-1232112","2025-07-05 20:26:18",908712984),
(10023,"V-221234083",1000000,"15879.2",13689,2,"Transferencia-676775443","2025-07-05 20:51:51","V-14600274");


SET foreign_key_checks = 1;
CREATE DATABASE IF NOT EXISTS `zapateriak`;

USE `zapateriak`;

SET foreign_key_checks = 0;

DROP TABLE IF EXISTS `categorias`;

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `categorias` VALUES (7,"Ropas","2025-07-03 09:22:04"),
(9,"Calzados","2024-10-20 22:02:09"),
(10,"ACCESORIOS","2024-10-23 01:54:40"),
(12,"COMPONENTES","2024-10-30 01:38:07");


DROP TABLE IF EXISTS `clientes`;

CREATE TABLE `clientes` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` text COLLATE utf8_spanish_ci NOT NULL,
  `apellido` text COLLATE utf8_spanish_ci NOT NULL,
  `email` text COLLATE utf8_spanish_ci NOT NULL,
  `telefono` text COLLATE utf8_spanish_ci NOT NULL,
  `direccion` text COLLATE utf8_spanish_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `clientes` VALUES (30124678,"jose angeles","cabeza arias","jose@gmail.com","0414-5555555","av sucre"),
(908712984,"Julio","Carrasquero","vane@gmail.com","0414-1212125","Miticun"),
("V-12124441","lola","veinte","francoarias81@gmail.com","0412-1222222","qwqwqwqw"),
("V-14600272","francisco","perdomo","francoarias81@gmail.com","0426-5555555","av- sucre 1-11"),
("V-14600274","francisco","perdomo","francoarias81@gmail.com","0416-1234556","Caracas"),
("V-14600276","lola","mento","francoarias81@gmail.com","0412-1232323","valera");


DROP TABLE IF EXISTS `colores`;

CREATE TABLE `colores` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `colores` VALUES (1,"negro","2024-10-21 05:52:13"),
(10,"blanco","2024-10-21 08:17:13"),
(11,"verde","2024-10-21 08:17:24"),
(12,"beige","2012-11-01 00:08:15"),
(13,"TUTIFRUTTY","2024-10-26 20:34:51");


DROP TABLE IF EXISTS `empleado`;

CREATE TABLE `empleado` (
  `cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `foto` varchar(200) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empleado` VALUES (1000000,121212,"super","usuario","bocono",""),
(12847231,9012754,"Ricardo","Yepez Bastidas","",""),
(23194809,9102375089,"Isaac","Bastidas","",""),
(3174101,172089720,"Jota","Mejia","",""),
(31744101,4247079098,"Jhoan","Mejia","El carmen",""),
("E-14600272","0272-1234455","jose","err","22e",""),
("V-14600278","0272-1232323","jose","ppe","av-sucre 1-11","");


DROP TABLE IF EXISTS `empresa`;

CREATE TABLE `empresa` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(80) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `precio_dolar` float NOT NULL,
  `impuesto` float NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `empresa` VALUES ("V-221234083","Centro de Boconos","Kamyl Styl y Algo mas de Susana Al charitis",02721321223,114,16);


DROP TABLE IF EXISTS `event_log`;

CREATE TABLE `event_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `event_type` varchar(50) COLLATE utf8_spanish2_ci NOT NULL,
  `description` text COLLATE utf8_spanish2_ci NOT NULL,
  `employee_cedula` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `affected_table` varchar(50) COLLATE utf8_spanish2_ci DEFAULT NULL,
  `affected_row_id` varchar(100) COLLATE utf8_spanish2_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `employee_cedula` (`employee_cedula`),
  CONSTRAINT `event_log_ibfk_1` FOREIGN KEY (`employee_cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=389 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `event_log` VALUES (1,"2025-07-02 13:04:42","Creación Cliente Fallida","Intento de crear cliente con datos inválidos (nombre: francisco, cédula: V-12600272).",1000000,"clientes","V-12600272"),
(2,"2025-07-02 13:05:07","Creación Cliente Fallida","Intento de crear cliente con datos inválidos (nombre: deee, cédula: V-14600272).",1000000,"clientes","V-14600272"),
(3,"2025-07-02 13:05:56","Cliente Eliminado","Cliente \'Ismael Martinez\' (Cédula: 12400500) eliminado.",1000000,"clientes",12400500),
(4,"2025-07-02 14:49:58","Creación Cliente Fallida","Intento de crear cliente con datos inválidos (nombre: francisco, cédula: V-14600273).",1000000,"clientes","V-14600273"),
(5,"2025-07-02 15:05:52","Cliente Eliminado","Cliente \'Esbeidy Gil\' (Cédula: 31982314) eliminado.",1000000,"clientes",31982314),
(6,"2025-07-02 15:07:27","Cliente Eliminado","Cliente \'Julio Guanda\' (Cédula: 7689758) eliminado.",1000000,"clientes",7689758),
(7,"2025-07-02 15:13:58","Cliente Editado","Datos del cliente \'qwqwqwqw qwqwqqw\' (Cédula: ) modificados.",1000000,"clientes",""),
(8,"2025-07-02 15:15:43","Creación Cliente Fallida","Intento de crear cliente con datos inválidos (nombre: francis, cédula: V-14600271).",1000000,"clientes","V-14600271"),
(9,"2025-07-02 15:32:41","Cliente Editado","Datos del cliente \'Ismael sdsdsd\' (Cédula: ) modificados.",1000000,"clientes",""),
(10,"2025-07-02 15:33:22","Cliente Editado","Datos del cliente \'Ismael Mejia\' (Cédula: ) modificados.",1000000,"clientes",""),
(11,"2025-07-02 15:51:59","Cliente Editado","Datos del cliente \'Ismael sdsdsd\' (Cédula: ) modificados.",1000000,"clientes",""),
(12,"2025-07-02 18:35:56","Creación Cliente Fallida","Intento de crear cliente con datos inválidos (nombre: qwqw, cédula: V-12222222).",1000000,"clientes","V-12222222"),
(13,"2025-07-02 20:10:48","Cliente Creado","Nuevo cliente \'lola mento\' (Cédula: V-14600276) registrado.",1000000,"clientes","V-14600276"),
(14,"2025-07-03 03:50:57","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(15,"2025-07-03 05:04:20","Cliente Editado","Datos del cliente \'Ismael Mejia\' (Cédula: ) modificados.",1000000,"clientes",""),
(16,"2025-07-03 06:33:12","Cliente Editado","Datos del cliente \'qwq qwqw\' (Cédula: ) modificados.",1000000,"clientes",""),
(17,"2025-07-03 06:34:07","Cliente Creado","Nuevo cliente \'lola veinte\' (Cédula: V-12124441) registrado.",1000000,"clientes","V-12124441"),
(18,"2025-07-03 06:35:10","Cliente Editado","Datos del cliente \'Ismael cabeza arias\' (Cédula: ) modificados.",1000000,"clientes",""),
(19,"2025-07-03 06:37:29","Cliente Creado","Nuevo cliente \'francisco perdomo\' (Cédula: V-14600274) registrado.",1000000,"clientes","V-14600274"),
(20,"2025-07-03 07:01:40","Cliente Editado","Datos del cliente \'Vanessita Teran\' (Cédula: 259103825) modificados.",1000000,"clientes",259103825),
(21,"2025-07-03 07:27:13","Empleado Eliminado","Empleado \'Francisco Arias\' (Cédula: 12393320) eliminado con éxito.",1000000,"empleado",12393320),
(22,"2025-07-03 07:27:48","Empleado Creado","Nuevo empleado \'jose err\' (Cédula: E-14600272) registrado con éxito.",1000000,"empleado","E-14600272"),
(23,"2025-07-03 07:32:10","Edición Empleado Fallida","Intento de editar empleado con datos inválidos (Nombre: Jhoan, Cédula: 31744101). Validación de campos fallida.",1000000,"empleado",31744101),
(24,"2025-07-03 07:32:24","Edición Empleado Fallida","Intento de editar empleado con datos inválidos (Nombre: Jhoan, Cédula: 31744101). Validación de campos fallida.",1000000,"empleado",31744101),
(25,"2025-07-03 07:32:45","Edición Empleado Fallida","Intento de editar empleado con datos inválidos (Nombre: Isaac, Cédula: 23194809). Validación de campos fallida.",1000000,"empleado",23194809),
(26,"2025-07-03 09:16:57","Configuración Editada","Configuración de la empresa \'Kamyl Styles y Algo mas de Susana Al chariti\' (RIF: V-221234087) modificada.",1000000,"empresa","V-221234087"),
(27,"2025-07-03 09:22:04","Categoría Editada","Categoría \'Ropa\' (ID: 7) modificada a \'Ropas\'.",1000000,"categorias",7),
(28,"2025-07-03 09:35:55","Tipo Editado","Tipo \'caballeros\' (ID: 5) modificado a \'caballero\'.",1000000,"tipos",5),
(29,"2025-07-03 09:37:32","Marca Editada","Marca \'adidas\' (ID: 10) modificada a \'adidass\'.",1000000,"marcas",10),
(30,"2025-07-03 09:52:35","Proveedor Editado","Proveedor (RIF: J-999999990) editado. Empresa: \'rs22\' a \'rs222\'. Representante: \'erer arias\' a \'erer arias\'. Teléfono: \'0272-1200000\' a \'0424-1111111\'.",1000000,"proveedores","J-999999990"),
(31,"2025-07-03 14:40:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(32,"2025-07-03 14:40:59","Login Fallido","Contraseña incorrecta para el usuario: \'admin\'.",1000000,"usuarios","admin"),
(33,"2025-07-03 14:41:05","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(34,"2025-07-03 14:41:30","Usuario Editado","Usuario \'isa\' (Cédula: ) editado con perfil \'Vendedor\'.",1000000,"usuarios",NULL),
(35,"2025-07-03 15:09:35","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(36,"2025-07-03 15:11:21","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(37,"2025-07-03 15:15:17","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(39,"2025-07-03 15:15:25","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(40,"2025-07-03 15:15:42","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(43,"2025-07-03 15:15:59","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(45,"2025-07-03 15:16:06","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(47,"2025-07-03 15:16:15","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(51,"2025-07-03 15:17:02","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(55,"2025-07-03 15:17:41","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(57,"2025-07-03 15:18:21","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(58,"2025-07-03 15:18:28","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(64,"2025-07-03 15:18:46","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(66,"2025-07-03 15:18:50","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(69,"2025-07-03 15:19:00","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(71,"2025-07-03 15:19:17","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(72,"2025-07-03 15:19:39","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(73,"2025-07-03 15:20:59","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(74,"2025-07-03 15:22:13","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(76,"2025-07-03 15:22:21","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(79,"2025-07-03 15:27:58","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(82,"2025-07-03 15:28:11","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(85,"2025-07-03 15:28:24","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(87,"2025-07-03 15:28:30","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(89,"2025-07-03 15:29:15","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(90,"2025-07-03 15:30:35","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(91,"2025-07-03 15:31:05","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(92,"2025-07-03 15:31:21","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(94,"2025-07-03 15:31:27","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(95,"2025-07-03 15:32:01","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(96,"2025-07-03 15:34:57","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(98,"2025-07-03 15:35:06","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(100,"2025-07-03 15:35:14","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(103,"2025-07-03 15:35:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(104,"2025-07-03 15:38:28","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(105,"2025-07-03 15:39:12","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(107,"2025-07-03 15:39:50","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(110,"2025-07-03 15:43:43","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(112,"2025-07-03 15:43:54","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(113,"2025-07-03 15:44:11","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(114,"2025-07-03 15:44:11","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (cierre de ventana/pestaña).",1000000,"sesiones","admin"),
(116,"2025-07-03 15:44:23","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(122,"2025-07-03 15:52:56","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(123,"2025-07-03 15:53:12","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(124,"2025-07-03 15:54:26","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(125,"2025-07-03 15:54:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(126,"2025-07-03 15:55:27","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(127,"2025-07-03 15:55:37","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(128,"2025-07-03 15:56:12","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(129,"2025-07-03 16:08:05","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(130,"2025-07-03 16:08:15","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(131,"2025-07-03 16:08:20","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(132,"2025-07-03 16:08:40","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(133,"2025-07-03 16:11:19","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(134,"2025-07-03 16:13:49","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(135,"2025-07-03 16:14:08","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(136,"2025-07-03 16:15:06","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(137,"2025-07-03 16:24:19","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(138,"2025-07-03 16:24:28","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(139,"2025-07-03 16:25:19","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(140,"2025-07-03 16:26:14","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(141,"2025-07-03 16:29:39","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(142,"2025-07-03 16:29:48","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(143,"2025-07-03 16:29:55","Sesión Cerrada","El usuario \'admin\' (Administrador) ha cerrado sesión (manual o cierre de ventana).",1000000,"sesiones","admin"),
(144,"2025-07-03 16:34:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(145,"2025-07-03 16:43:17","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(146,"2025-07-03 16:54:13","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(147,"2025-07-03 17:34:35","Usuario Editado","Usuario \'isa\' (Cédula: ) editado con perfil \'Vendedor\'.",1000000,"usuarios",NULL),
(148,"2025-07-03 17:34:47","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(149,"2025-07-03 17:34:59","Login Fallido","Contraseña incorrecta para el usuario: \'isa\'.",23194809,"usuarios","isa"),
(150,"2025-07-03 17:35:09","Login Exitoso","El empleado \'isa\' (Vendedor) ha iniciado sesión.",23194809,"usuarios","isa"),
(151,"2025-07-03 17:35:42","Cliente Editado","Datos del cliente \'Vanessita Teran\' (Cédula: 259103825) modificados.",23194809,"clientes",259103825),
(152,"2025-07-03 17:36:11","Cliente Editado","Datos del cliente \'Julio Carrasquero\' (Cédula: 908712984) modificados.",23194809,"clientes",908712984),
(153,"2025-07-03 17:37:13","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(154,"2025-07-04 04:05:27","Producto Eliminado","Producto \'cHEMISEssss\' (Código: 121342) eliminado.",1000000,"productos",121342),
(155,"2025-07-04 04:46:45","Producto Editado","Datos del producto \'cHEMISEssss\' (Código: 121342) modificados.",1000000,"productos",121342),
(156,"2025-07-04 04:47:02","Producto Editado","Datos del producto \'cHEMISEssss\' (Código: 121342) modificados.",1000000,"productos",121342),
(157,"2025-07-04 05:08:07","Producto Editado","Datos generales del producto \'pantalon\' (Código: 12333443-e) modificados.",1000000,"productos","12333443-e"),
(158,"2025-07-04 05:08:23","Producto Editado","Datos generales del producto \'pantalon\' (Código: 12333443-e) modificados.",1000000,"productos","12333443-e"),
(159,"2025-07-04 05:12:33","Producto Editado","Datos generales del producto \'cHEMISEssss\' (Código: 121342) modificados.",1000000,"productos",121342),
(160,"2025-07-04 05:18:01","Creación Producto Fallida","Intento de crear producto con datos inválidos (Descripción: zarcills, Código: 12333443-e4).",1000000,"productos","12333443-e4"),
(161,"2025-07-04 05:23:12","Creación Producto Fallida","Intento de crear producto con datos inválidos (Descripción: cHEMISEssss, Código: 12333443-e3).",1000000,"productos","12333443-e3"),
(162,"2025-07-04 05:24:29","Creación Producto Fallida","Intento de crear producto con datos inválidos (Descripción: cHEMISEssss, Código: 12333443-e3).",1000000,"productos","12333443-e3"),
(163,"2025-07-04 05:35:48","Producto Eliminado","Producto \'pantalon\' (Código: 12333443-e) eliminado.",1000000,"productos","12333443-e"),
(164,"2025-07-04 05:36:16","Producto Editado","Datos del producto \'cHEMISEssss\' (Código: 121342) modificados.",1000000,"productos",121342),
(165,"2025-07-04 06:32:55","Producto Creado","Producto \'cHEMISE\' (Código: 12333443-eo) creado.",1000000,"productos","12333443-eo"),
(166,"2025-07-04 06:55:16","Stock Modificado","Stock del producto (Código: 12333443-eo) actualizado a 2.",1000000,"productos","12333443-eo"),
(167,"2025-07-04 06:55:22","Stock Modificado","Stock del producto (Código: 121342) actualizado a 2.",1000000,"productos",121342),
(168,"2025-07-04 06:57:09","Producto Editado","Producto \'cHEMISEssss\' (Código: 121342) editado.",1000000,"productos",121342),
(169,"2025-07-04 06:57:20","Producto Editado","Producto \'cHEMISE\' (Código: 12333443-eo) editado.",1000000,"productos","12333443-eo"),
(170,"2025-07-04 06:57:25","Producto Eliminado","Producto \'N/A\' (Código: 12333443-eo) eliminado.",1000000,"productos","12333443-eo"),
(171,"2025-07-04 06:57:58","Producto Creado","Producto \'cHEMISE\' (Código: 12333443-eeee) creado.",1000000,"productos","12333443-eeee"),
(172,"2025-07-04 06:57:58","Producto Eliminado","Producto \'N/A\' (Código: 12333443-eo) eliminado.",1000000,"productos","12333443-eo"),
(173,"2025-07-04 06:58:53","Stock Modificado","Stock del producto (Código: 121342) actualizado a 2.",1000000,"productos",121342),
(174,"2025-07-04 06:59:26","Producto Creado","Producto \'cHEMISEssss\' (Código: 12333443-e) creado.",1000000,"productos","12333443-e"),
(175,"2025-07-04 07:00:40","Creación Producto Fallida","Intento de crear producto repetido (Código: 12333443-e).",1000000,"productos","12333443-e"),
(176,"2025-07-04 07:03:06","Creación Producto Fallida","Intento de crear producto repetido (Código: 12333443-e).",1000000,"productos","12333443-e"),
(177,"2025-07-04 07:03:11","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(178,"2025-07-04 07:03:19","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(179,"2025-07-04 07:03:26","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(180,"2025-07-04 07:03:34","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(181,"2025-07-04 07:06:05","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(182,"2025-07-04 07:06:21","Stock Modificado","Stock del producto (Código: 12333443-e) actualizado a 2.",1000000,"productos","12333443-e"),
(183,"2025-07-04 07:06:45","Producto Editado","Producto \'cHEMISEssss\' (Código: 121342) editado.",1000000,"productos",121342),
(184,"2025-07-04 07:08:24","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(185,"2025-07-04 07:08:48","Cliente Editado","Datos del cliente \'Vanessita Teran\' (Cédula: 259103825) modificados.",1000000,"clientes",259103825),
(186,"2025-07-04 07:09:03","Producto Editado","Producto \'cHEMISEssss\' (Código: 12333443-e) editado.",1000000,"productos","12333443-e"),
(187,"2025-07-04 07:09:49","Producto Creado","Producto \'rtrtr\' (Código: 2342342424) creado.",1000000,"productos",2342342424),
(188,"2025-07-04 07:12:22","Producto Editado","Producto \'cHEMISEssss\' (Código: 121342) editado.",1000000,"productos",121342),
(189,"2025-07-04 07:12:34","Stock Modificado","Stock del producto (Código: 22ee2) actualizado a 2.",1000000,"productos","22ee2"),
(190,"2025-07-04 07:15:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(191,"2025-07-04 07:17:23","Stock Modificado","Stock del producto (Código: 2342342424) actualizado a 2.",1000000,"productos",2342342424),
(192,"2025-07-04 07:17:39","Stock Modificado","Stock del producto (Código: 2342342424) actualizado a 2.",1000000,"productos",2342342424),
(193,"2025-07-04 07:19:37","Stock Modificado","Stock del producto (Código: 2342342424) actualizado a 2.",1000000,"productos",2342342424),
(194,"2025-07-04 07:20:17","Proveedor Editado","Proveedor (RIF: J-999999990) editado. Empresa: \'rs222\' a \'rs222\'. Representante: \'erer arias\' a \'erer arias\'. Teléfono: \'0424-1111111\' a \'0426-1233333\'.",1000000,"proveedores","J-999999990"),
(195,"2025-07-04 07:22:49","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(196,"2025-07-04 07:46:57","Producto Creado","Producto \'eeeee\' (Código: 12122) registrado.",1000000,"productos",12122),
(197,"2025-07-04 14:01:21","Stock Actualizado","Stock del producto (Código: 12122) actualizado a 2 unidades.",1000000,"productos",12122),
(198,"2025-07-04 14:02:00","Producto Editado","Producto \'rtrtr\' (Código: 2342342424) editado.",1000000,"productos",2342342424),
(199,"2025-07-04 14:02:44","Producto Creado","Producto \'zarcills\' (Código: 1212-erer-4) creado.",1000000,"productos","1212-erer-4"),
(200,"2025-07-04 14:05:09","Producto Eliminado","Producto (Código: 12122) eliminado.",1000000,"productos",12122),
(206,"2025-07-04 15:12:36","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(207,"2025-07-04 15:12:37","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(208,"2025-07-04 15:24:03","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(209,"2025-07-04 15:24:28","Cliente Editado","Datos del cliente \'Vanessitas Teran\' (Cédula: 259103825) modificados.",1000000,"clientes",259103825),
(210,"2025-07-04 15:24:40","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(211,"2025-07-04 15:24:42","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(212,"2025-07-04 15:25:18","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(213,"2025-07-04 15:25:21","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(214,"2025-07-04 15:25:39","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(215,"2025-07-04 15:25:47","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(216,"2025-07-04 15:32:17","Usuario Creado","Nuevo usuario \'pedro13\' (Cédula: 3174101) con perfil \'Administrador\' fue creado.",1000000,"usuarios",3174101),
(217,"2025-07-04 15:33:44","Usuario Eliminado","Usuario \'3174101\' (Cédula: N/A) eliminado.",1000000,"usuarios","N/A"),
(218,"2025-07-04 15:40:56","Usuario Editado","Usuario \'isa\' (Cédula: ) editado con perfil \'Vendedor\'.",1000000,"usuarios",NULL),
(219,"2025-07-04 15:41:39","Usuario Editado","Usuario \'ricki\' (Cédula: ) editado con perfil \'Vendedor\'.",1000000,"usuarios",NULL),
(220,"2025-07-04 15:47:37","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(221,"2025-07-04 15:47:42","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(222,"2025-07-04 15:47:53","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(223,"2025-07-04 15:47:55","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(224,"2025-07-04 15:52:45","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(225,"2025-07-04 15:52:48","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(226,"2025-07-04 15:57:10","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(227,"2025-07-04 15:57:14","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(228,"2025-07-04 15:57:51","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(229,"2025-07-04 15:57:53","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(230,"2025-07-04 15:59:02","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(231,"2025-07-04 15:59:04","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(232,"2025-07-04 16:00:40","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(233,"2025-07-04 16:00:42","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(234,"2025-07-04 16:08:31","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(235,"2025-07-04 16:08:40","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(236,"2025-07-04 16:09:23","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(237,"2025-07-04 16:12:35","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(238,"2025-07-04 16:12:36","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(239,"2025-07-04 16:13:19","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(240,"2025-07-04 16:13:20","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(241,"2025-07-04 16:13:28","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(242,"2025-07-04 16:25:20","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(243,"2025-07-04 16:25:22","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(244,"2025-07-04 16:28:53","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(245,"2025-07-04 16:28:55","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(246,"2025-07-04 16:29:14","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(247,"2025-07-04 16:29:16","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(248,"2025-07-04 16:29:33","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(249,"2025-07-04 16:30:12","Generación de Reporte PDF","Se generó un reporte de Inventario en formato PDF.",1000000,"productos","N/A"),
(250,"2025-07-04 16:33:31","Edición Empleado Fallida","Intento de editar empleado con datos inválidos (Nombre: Jhoan, Cédula: 31744101). Validación de campos fallida.",1000000,"empleado",31744101),
(251,"2025-07-04 16:33:51","Edición Empleado Fallida","Intento de editar empleado con datos inválidos (Nombre: Jhoan, Cédula: 31744101). Validación de campos fallida.",1000000,"empleado",31744101),
(252,"2025-07-04 16:34:15","Cliente Eliminado","Cliente \'Jhoan Mejia\' (Cédula: 31744101) eliminado.",1000000,"clientes",31744101),
(253,"2025-07-05 17:04:34","Generación de Reporte PDF","Se generó un reporte Diario de Ventas en formato PDF. Total: 4.508,92 Bs.",1000000,"ventas","N/A"),
(254,"2025-07-05 17:04:35","Generación de Reporte PDF","Se generó un reporte Diario de Ventas en formato PDF. Total: 4.508,92 Bs.",1000000,"ventas","N/A"),
(255,"2025-07-05 20:20:43","Generación de Reporte PDF","Se generó un reporte Mensual de Ventas en formato PDF. Total: 9.213,88 Bs. Período: 07/2025",1000000,"ventas","N/A"),
(256,"2025-07-05 20:20:44","Generación de Reporte PDF","Se generó un reporte Mensual de Ventas en formato PDF. Total: 9.213,88 Bs. Período: 07/2025",1000000,"ventas","N/A"),
(257,"2025-07-05 20:23:05","Generación de Reporte PDF","Se generó un reporte Mensual de Ventas en formato PDF. Total: 18.576,45 Bs.",1000000,"ventas","N/A"),
(258,"2025-07-05 20:23:06","Generación de Reporte PDF","Se generó un reporte Mensual de Ventas en formato PDF. Total: 18.576,45 Bs.",1000000,"ventas","N/A"),
(259,"2025-07-05 20:24:44","Generación de Reporte PDF","Se generó un reporte Total de Ventas en formato PDF. Total: 18.576,45 Bs.",1000000,"ventas","N/A"),
(260,"2025-07-05 20:24:45","Generación de Reporte PDF","Se generó un reporte Total de Ventas en formato PDF. Total: 18.576,45 Bs.",1000000,"ventas","N/A"),
(261,"2025-07-05 20:25:30","Proveedor Editado","Proveedor (RIF: J-999999990) editado. Empresa: \'rs222\' a \'rs22\'. Representante: \'erer arias\' a \'erer arias\'. Teléfono: \'0426-1233333\' a \'0416-1222138\'.",1000000,"proveedores","J-999999990"),
(262,"2025-07-05 20:48:13","Producto Editado","Producto \'cHEMISEssss\' (Código: 12333443-e) editado.",1000000,"productos","12333443-e"),
(263,"2025-07-05 21:01:01","Producto Editado","Producto \'rtrtr\' (Código: 2342342424) editado.",1000000,"productos",2342342424),
(264,"2025-07-05 21:44:06","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(265,"2025-07-05 21:45:49","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(266,"2025-07-05 21:45:50","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(267,"2025-07-05 21:47:53","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(268,"2025-07-05 21:47:55","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(269,"2025-07-05 21:48:36","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(270,"2025-07-05 21:48:37","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(271,"2025-07-05 21:49:09","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al chariti\' (RIF: V-221234087) modificada.",1000000,"empresa","V-221234087"),
(272,"2025-07-05 21:49:32","Generación de Reporte PDF","Se generó un reporte Total de Ventas en formato PDF. Total: 77.780,45 Bs.",1000000,"ventas","N/A"),
(273,"2025-07-05 21:49:32","Generación de Reporte PDF","Se generó un reporte Total de Ventas en formato PDF. Total: 77.780,45 Bs.",1000000,"ventas","N/A"),
(274,"2025-07-05 21:49:44","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(275,"2025-07-05 21:50:03","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(276,"2025-07-05 21:52:57","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(277,"2025-07-05 21:53:00","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(278,"2025-07-05 21:58:39","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(279,"2025-07-05 21:58:41","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(280,"2025-07-05 22:21:45","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(281,"2025-07-05 22:22:16","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(282,"2025-07-05 22:22:16","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(283,"2025-07-05 22:22:31","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(284,"2025-07-05 22:22:33","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(285,"2025-07-05 22:23:20","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(286,"2025-07-05 22:24:45","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(287,"2025-07-05 22:24:47","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(288,"2025-07-05 22:25:48","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(289,"2025-07-05 22:31:27","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(290,"2025-07-05 22:31:39","Cliente Eliminado","Cliente \'Vanessitas Teran\' (Cédula: 259103825) eliminado.",1000000,"clientes",259103825),
(291,"2025-07-05 22:32:13","Cliente Editado","Datos del cliente \'francisco perdomo\' (Cédula: V-14600272) modificados.",1000000,"clientes","V-14600272"),
(292,"2025-07-05 22:35:58","Creación Cliente Fallida","Intento de crear cliente con cédula \'V-14600272\' fallido por ser repetida.",1000000,"clientes","V-14600272"),
(293,"2025-07-05 23:16:09","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada.",1000000,"empresa","V-221234083"),
(294,"2025-07-05 23:46:24","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada.",1000000,"empresa","V-221234083"),
(295,"2025-07-06 00:23:37","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada.",1000000,"empresa","V-221234083"),
(296,"2025-07-06 00:27:23","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 133,0000 -> 132,0000 USD.",1000000,"empresa","V-221234083"),
(297,"2025-07-06 00:30:27","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 132,00 -> 130,0000 USD.",1000000,"empresa","V-221234083"),
(298,"2025-07-06 00:31:11","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 130,00 -> 110,00 USD.",1000000,"empresa","V-221234083"),
(299,"2025-07-06 00:32:14","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 110,00 cambio a 115,00 USD.",1000000,"empresa","V-221234083"),
(300,"2025-07-06 00:32:53","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Otros datos de configuración modificados.",1000000,"empresa","V-221234083"),
(301,"2025-07-06 00:33:39","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 115,00 cambio a 116,00 USD.",1000000,"empresa","V-221234083"),
(302,"2025-07-06 00:34:08","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 116,00 cambio a 115,00 .",1000000,"empresa","V-221234083"),
(303,"2025-07-06 01:06:20","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: 115,00 cambio a 114,00 .",1000000,"empresa","V-221234083"),
(304,"2025-07-06 01:18:59","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: Anterior 114,0000 USD, Nuevo 113,0000 USD.",1000000,"empresa","V-221234083"),
(305,"2025-07-06 01:34:15","Configuración Editada","Configuración de la empresa \'Kamyl Styl y Algo mas de Susana Al charitis\' (RIF: V-221234083) modificada. Detalles: Precio Dólar: Anterior 113,0000 USD, Nuevo 114,0000 USD.",1000000,"empresa","V-221234083"),
(306,"2025-07-06 01:39:42","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(307,"2025-07-06 01:39:43","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(308,"2025-07-06 02:56:52","Login Fallido","Contraseña incorrecta para el usuario: \'isa\'.",23194809,"usuarios","isa"),
(309,"2025-07-06 02:57:00","Login Fallido","Contraseña incorrecta para el usuario: \'isa\'.",23194809,"usuarios","isa"),
(311,"2025-07-06 02:57:13","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(312,"2025-07-06 02:57:30","Login Fallido","Contraseña incorrecta para el usuario: \'admin\'.",1000000,"usuarios","admin"),
(316,"2025-07-06 02:58:15","Login Fallido","Contraseña incorrecta para el usuario: \'admin\'.",1000000,"usuarios","admin"),
(319,"2025-07-06 02:58:51","Login Fallido","Contraseña incorrecta para el usuario: \'ADMIN\'.",1000000,"usuarios","ADMIN"),
(320,"2025-07-06 02:59:03","Login Fallido","Contraseña incorrecta para el usuario: \'ADMIN\'.",1000000,"usuarios","ADMIN"),
(321,"2025-07-06 03:00:16","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(325,"2025-07-06 03:04:18","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(328,"2025-07-06 03:18:46","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(330,"2025-07-06 03:23:04","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(332,"2025-07-06 03:23:27","Login Fallido","Contraseña incorrecta para el usuario: \'admin\'.",1000000,"usuarios","admin"),
(333,"2025-07-06 03:23:35","Login Fallido","Contraseña incorrecta para el usuario: \'admin\'.",1000000,"usuarios","admin"),
(335,"2025-07-06 03:32:38","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(336,"2025-07-06 03:32:52","Login Fallido","Contraseña incorrecta para el usuario: \'ADMIN\'.",1000000,"usuarios","ADMIN"),
(337,"2025-07-06 03:33:07","Login Fallido","Contraseña incorrecta para el usuario: \'isa\'.",23194809,"usuarios","isa"),
(341,"2025-07-06 03:52:41","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(342,"2025-07-06 03:53:30","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(347,"2025-07-06 03:58:05","Login Fallido","Contraseña incorrecta para el usuario: \'admin\'.",1000000,"usuarios","admin"),
(348,"2025-07-06 03:58:24","Login Fallido","Contraseña incorrecta para el usuario: \'admin\'.",1000000,"usuarios","admin"),
(351,"2025-07-06 04:00:28","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(352,"2025-07-06 08:59:39","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(353,"2025-07-06 09:02:00","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(354,"2025-07-06 09:02:00","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(355,"2025-07-06 09:11:51","Cierre de Sesión","El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.",1000000,"usuarios","admin"),
(356,"2025-07-06 09:11:59","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(357,"2025-07-06 09:12:21","Cierre de Sesión","El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.",1000000,"usuarios","admin"),
(358,"2025-07-06 09:12:26","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(359,"2025-07-06 09:12:49","Cierre de Sesión","El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.",1000000,"usuarios","admin"),
(360,"2025-07-06 09:12:55","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(361,"2025-07-06 09:46:27","Cliente Editado","Datos del cliente \'jose angeles cabeza arias\' (Cédula: 30124678) modificados.",1000000,"clientes",30124678),
(362,"2025-07-06 10:01:30","Cierre de Sesión","El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.",1000000,"usuarios","admin"),
(363,"2025-07-06 10:03:29","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(364,"2025-07-06 10:04:06","Cierre de Sesión","El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.",1000000,"usuarios","admin"),
(365,"2025-07-06 10:10:07","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(366,"2025-07-06 10:11:41","Cierre de Sesión","El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.",1000000,"usuarios","admin"),
(367,"2025-07-06 10:11:49","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(368,"2025-07-06 10:33:38","Cierre de Sesión","El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.",1000000,"usuarios","admin"),
(369,"2025-07-06 10:33:46","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(370,"2025-07-06 10:35:27","Cliente Editado","Datos del cliente \'Julio Carrasquero\' (Cédula: 908712984) modificados.",1000000,"clientes",908712984),
(371,"2025-07-06 10:35:54","Cierre de Sesión","El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.",1000000,"usuarios","admin"),
(372,"2025-07-06 10:35:59","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(373,"2025-07-06 10:57:28","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(374,"2025-07-06 10:58:26","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(375,"2025-07-06 10:58:26","Generación de Reporte PDF","Se generó un reporte de Clientes en formato PDF.",1000000,"clientes","N/A"),
(376,"2025-07-06 15:15:47","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(377,"2025-07-06 15:15:48","Generación de Reporte PDF","Se generó un reporte de Proveedores en formato PDF.",1000000,"proveedores","N/A"),
(378,"2025-07-06 15:35:03","Generación de Reporte PDF","Se generó un reporte de Eventos del Sistema en formato PDF.",1000000,"event_log","N/A"),
(379,"2025-07-06 15:35:06","Generación de Reporte PDF","Se generó un reporte de Eventos del Sistema en formato PDF.",1000000,"event_log","N/A"),
(380,"2025-07-06 15:35:59","Generación de Reporte PDF","Se generó un reporte de Eventos del Sistema en formato PDF.",1000000,"event_log","N/A"),
(381,"2025-07-06 15:42:06","Generación de Reporte PDF","Se generó un reporte de Eventos del Sistema en formato PDF.",1000000,"event_log","N/A"),
(382,"2025-07-06 15:42:16","Generación de Reporte PDF","Se generó un reporte de Eventos del Sistema en formato PDF.",1000000,"event_log","N/A"),
(383,"2025-07-06 15:42:48","Cierre de Sesión","El usuario \'admin\' (Cédula: 1000000, Perfil: Administrador) ha cerrado sesión.",1000000,"usuarios","admin"),
(384,"2025-07-06 15:42:55","Login Exitoso","El empleado \'admin\' (Administrador) ha iniciado sesión.",1000000,"usuarios","admin"),
(385,"2025-07-06 15:43:07","Generación de Reporte PDF","Se generó un reporte de Eventos del Sistema en formato PDF.",1000000,"event_log","N/A"),
(386,"2025-07-06 15:43:10","Generación de Reporte PDF","Se generó un reporte de Eventos del Sistema en formato PDF.",1000000,"event_log","N/A"),
(387,"2025-07-06 16:11:31","Generación de Reporte PDF","Se generó un reporte de Eventos del Sistema en formato PDF.",1000000,"event_log","N/A"),
(388,"2025-07-06 16:11:34","Generación de Reporte PDF","Se generó un reporte de Eventos del Sistema en formato PDF.",1000000,"event_log","N/A");


DROP TABLE IF EXISTS `historial_dolar`;

CREATE TABLE `historial_dolar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `precio_dolar` decimal(10,4) NOT NULL,
  `precio_anterior` decimal(10,4) DEFAULT NULL,
  `fecha_cambio` datetime NOT NULL,
  `estado_cambio` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

INSERT INTO `historial_dolar` VALUES (1,"113.0000",NULL,"2025-07-06 07:48:58",NULL),
(2,"114.0000","113.0000","2025-07-06 08:04:14","Subió");


DROP TABLE IF EXISTS `marcas`;

CREATE TABLE `marcas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `marca` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `marcas` VALUES (10,"adidass","2025-07-03 09:37:32"),
(11,"pocholin","2024-10-21 08:18:31"),
(13,"rs21","2024-10-21 08:18:44"),
(14,"Aero","2012-11-01 00:07:27"),
(15,"generica","2012-11-01 00:07:35"),
(16,"acadia","2012-11-01 00:07:46"),
(17,"sifrina","2012-11-01 00:07:59"),
(18,"NIKE","2024-10-25 18:02:27");


DROP TABLE IF EXISTS `productos`;

CREATE TABLE `productos` (
  `id_categoria` int(11) NOT NULL,
  `id_tipo` int(11) NOT NULL,
  `id_color` int(11) DEFAULT NULL,
  `id_marca` int(11) NOT NULL,
  `rif_proveedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `codigo` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `descripcion` text COLLATE utf8_spanish_ci NOT NULL,
  `imagen` text COLLATE utf8_spanish_ci NOT NULL,
  `stock` int(11) DEFAULT NULL,
  `precio_compra` float NOT NULL,
  `precio_venta` float NOT NULL,
  PRIMARY KEY (`codigo`),
  KEY `id_tipo` (`id_tipo`),
  KEY `id_categoria` (`id_categoria`,`id_color`,`id_marca`),
  KEY `id_color` (`id_color`),
  KEY `id_marca` (`id_marca`),
  KEY `rif_proveedor` (`rif_proveedor`),
  CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`id_color`) REFERENCES `colores` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_3` FOREIGN KEY (`id_marca`) REFERENCES `marcas` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_4` FOREIGN KEY (`id_tipo`) REFERENCES `tipos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `productos_ibfk_5` FOREIGN KEY (`rif_proveedor`) REFERENCES `proveedores` (`rif`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `productos` VALUES (7,5,NULL,10,"J-999999990","1212-erer-4","zarcills","vistas/img/productos/default/anonymous.png",33,12,"15.6"),
(9,5,1,13,"J-999999990",121342,"cHEMISEssss","vistas/img/productos/default/anonymous.png",18,12,"15.6"),
(7,5,1,11,"J-999999990","12333443-e","cHEMISEssss","vistas/img/productos/default/anonymous.png",7,10,13),
(7,5,1,10,"J-999999990","22ee2","sdsd","vistas/img/productos/default/anonymous.png",12,12,"15.6"),
(7,5,10,10,"J-999999990",2342342424,"rtrtr","vistas/img/productos/default/anonymous.png",47,1,"1.3");


DROP TABLE IF EXISTS `proveedores`;

CREATE TABLE `proveedores` (
  `rif` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `nombre_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `apellido_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `direccion` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `cedula_representante` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `telefono` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  `correo` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`rif`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `proveedores` VALUES ("J-999999990","rs22","erer","arias","Caracas","V-12126666","0416-1222138","francoarias82@gmail.com");


DROP TABLE IF EXISTS `tipos`;

CREATE TABLE `tipos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `tipo` text COLLATE utf8_spanish_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `tipos` VALUES (5,"caballero","2025-07-03 09:35:55"),
(6,"niño","2012-11-01 00:04:45"),
(7,"niña","2012-11-01 00:04:52"),
(8,"dama","2012-11-01 00:06:55");


DROP TABLE IF EXISTS `usuarios`;

CREATE TABLE `usuarios` (
  `cedula` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `usuario` varchar(30) COLLATE utf8_spanish_ci NOT NULL,
  `password` text COLLATE utf8_spanish_ci NOT NULL,
  `perfil` text COLLATE utf8_spanish_ci NOT NULL,
  `estado` int(11) NOT NULL,
  PRIMARY KEY (`usuario`),
  KEY `cedula` (`cedula`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`cedula`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `usuarios` VALUES (1000000,"admin","$2a$07$asxx54ahjppf45sd87a5auXBm1Vr2M1NV5t/zNQtGHGpS5fFirrbG","Administrador",1),
(23194809,"isa","$2a$07$asxx54ahjppf45sd87a5au4N7hmqIxxE/MQZ8CZlhGUldNNx6IO6a","Vendedor",1),
(12847231,"ricki","$2a$07$asxx54ahjppf45sd87a5auMKLpgpjghYypFQHTok48.zFSGAgdTrm","Vendedor",1);


DROP TABLE IF EXISTS `venta_producto`;

CREATE TABLE `venta_producto` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `factura` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `producto` varchar(30) COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `factura` (`factura`),
  KEY `producto` (`producto`),
  CONSTRAINT `venta_producto_ibfk_1` FOREIGN KEY (`factura`) REFERENCES `ventas` (`factura`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `venta_producto_ibfk_2` FOREIGN KEY (`producto`) REFERENCES `productos` (`codigo`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

INSERT INTO `venta_producto` VALUES (1,10021,1,"12333443-e"),
(2,10022,2,2342342424),
(3,10022,3,"22ee2"),
(4,10022,5,"12333443-e"),
(5,10023,1,2342342424),
(6,10023,1,"22ee2"),
(7,10023,1,"12333443-e"),
(8,10023,1,121342),
(9,10023,1,"1212-erer-4");


DROP TABLE IF EXISTS `ventas`;

CREATE TABLE `ventas` (
  `factura` int(11) NOT NULL AUTO_INCREMENT,
  `rif_empresa` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `vendedor` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `total` float NOT NULL,
  `pago` float NOT NULL,
  `impuesto` float NOT NULL DEFAULT '16',
  `metodo_pago` text CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cliente` varchar(30) CHARACTER SET utf8 COLLATE utf8_spanish2_ci NOT NULL,
  PRIMARY KEY (`factura`),
  KEY `rif_empresa` (`rif_empresa`),
  KEY `cliente` (`cliente`),
  KEY `vendedor` (`vendedor`),
  CONSTRAINT `ventas_ibfk_1` FOREIGN KEY (`rif_empresa`) REFERENCES `empresa` (`rif`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_2` FOREIGN KEY (`cliente`) REFERENCES `clientes` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `ventas_ibfk_3` FOREIGN KEY (`vendedor`) REFERENCES `empleado` (`cedula`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10024 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

INSERT INTO `ventas` VALUES (10002,"V-221234083",23194809,"1595.34","1375.29","220.05","Tarjeta-1234","2024-11-10 11:43:24",30124678),
(10004,"V-221234083",23194809,"1595.34","1375.29","220.05","Tarjeta-1234","2024-11-10 12:32:44",908712984),
(10015,"V-221234083",1000000,"111.592","96.2","15.39","Efectivo","2024-11-10 18:26:17",30124678),
(10019,"V-221234083",1000000,"669.552","577.2","92.35","Divisas-12","2025-06-14 16:41:49","V-14600272"),
(10021,"V-221234083",1000000,"4508.92",3887,"621.92","Divisas-30","2025-07-05 16:59:43","V-12124441"),
(10022,"V-221234083",1000000,"43324.8",37349,5,"Pago Movil-1232112","2025-07-05 20:26:18",908712984),
(10023,"V-221234083",1000000,"15879.2",13689,2,"Transferencia-676775443","2025-07-05 20:51:51","V-14600274");


SET foreign_key_checks = 1;
