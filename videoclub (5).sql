-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-08-2025 a las 03:30:11
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
-- Base de datos: `videoclub`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `deuda`
--

CREATE TABLE `deuda` (
  `Id_Deuda` int(100) NOT NULL,
  `Id_Persona` int(100) NOT NULL,
  `Id_Peliculas` int(100) NOT NULL,
  `Id_Renta` int(100) NOT NULL,
  `Id_EstadoRenta` int(100) NOT NULL,
  `InicioDeuda` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `deuda`
--

INSERT INTO `deuda` (`Id_Deuda`, `Id_Persona`, `Id_Peliculas`, `Id_Renta`, `Id_EstadoRenta`, `InicioDeuda`) VALUES
(1, 1, 9, 1, 2, '2025-05-20'),
(2, 1, 4, 2, 2, '2025-05-20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `distribuidora`
--

CREATE TABLE `distribuidora` (
  `Id_Distribuidora` int(100) NOT NULL,
  `Distribuidora` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `distribuidora`
--

INSERT INTO `distribuidora` (`Id_Distribuidora`, `Distribuidora`) VALUES
(1, 'Warner Bros.'),
(2, 'Paramount Pictures'),
(3, '20th Century Fox'),
(4, 'Universal Pictures'),
(5, 'Columbia Pictures'),
(6, 'Walt Disney Studios'),
(7, 'Miramax'),
(8, 'New Line Cinema'),
(9, 'Lionsgate'),
(10, 'TriStar Pictures'),
(11, 'Focus Features'),
(12, 'StudioCanal'),
(13, 'Metro-Goldwyn-Mayer (MGM)'),
(14, 'Searchlight Pictures'),
(15, 'CJ Entertainment');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estadorenta`
--

CREATE TABLE `estadorenta` (
  `Id_EstadoRenta` int(100) NOT NULL,
  `EstadoRenta` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `estadorenta`
--

INSERT INTO `estadorenta` (`Id_EstadoRenta`, `EstadoRenta`) VALUES
(1, 'Sin Renta Activa'),
(2, 'Renta Activa'),
(3, 'Deuda Activa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu`
--

CREATE TABLE `menu` (
  `Id_Menu` int(11) NOT NULL,
  `Nombre_Menu` varchar(150) NOT NULL,
  `Orden_Menu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `menu`
--

INSERT INTO `menu` (`Id_Menu`, `Nombre_Menu`, `Orden_Menu`) VALUES
(1, 'Permisos', 1),
(2, 'Ingresos', 2),
(3, 'Peliculas', 3),
(4, 'Rentas', 4),
(5, 'Clientes', 5),
(6, 'Staff', 6),
(7, 'inventario', 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `peliculas`
--

CREATE TABLE `peliculas` (
  `Id_Peliculas` int(100) NOT NULL,
  `Id_Distribuidora` int(100) NOT NULL,
  `Titulo` varchar(100) NOT NULL,
  `Director` varchar(100) NOT NULL,
  `AñoLanzamiento` year(4) NOT NULL,
  `Stock` int(100) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `peliculas`
--

INSERT INTO `peliculas` (`Id_Peliculas`, `Id_Distribuidora`, `Titulo`, `Director`, `AñoLanzamiento`, `Stock`) VALUES
(1, 4, 'The Shawshank Redemption', 'Frank Darabont', '1994', 5),
(2, 1, 'The Godfather', 'Francis Ford Coppola', '1972', 3),
(3, 14, 'The Dark Knight', 'Christopher Nolan', '2008', 7),
(4, 6, 'Pulp Fiction', 'Quentin Tarantino', '1994', 4),
(5, 13, 'Forrest Gump', 'Robert Zemeckis', '1994', 6),
(6, 9, 'Inception', 'Christopher Nolan', '2010', 8),
(7, 7, 'Fight Club', 'David Fincher', '1999', 2),
(8, 14, 'The Matrix', 'Lana Wachowski y Lilly Wachowski', '1999', 5),
(9, 6, 'Goodfellas', 'Martin Scorsese', '1990', 3),
(10, 8, 'The Lord of the Rings: The Fellowship of the Ring', 'Peter Jackson', '2001', 4),
(11, 1, 'Star Wars: Episode V - The Empire Strikes Back', 'Irvin Kershner', '1980', 6),
(12, 15, 'The Silence of the Lambs', 'Jonathan Demme', '1991', 4),
(13, 10, 'Se7en', 'David Fincher', '1995', 5),
(14, 12, 'Interstellar', 'Christopher Nolan', '2014', 7),
(15, 8, 'The Green Mile', 'Frank Darabont', '1999', 3),
(16, 3, 'Gladiator', 'Ridley Scott', '2000', 8),
(17, 7, 'Saving Private Ryan', 'Steven Spielberg', '1998', 5),
(18, 12, 'The Prestige', 'Christopher Nolan', '2006', 6),
(19, 12, 'The Departed', 'Martin Scorsese', '2006', 4),
(20, 12, 'Whiplash', 'Damien Chazelle', '2014', 7),
(21, 12, 'The Lion King', 'Roger Allers y Rob Minkoff', '1994', 9),
(22, 8, 'Django Unchained', 'Quentin Tarantino', '2012', 5),
(23, 10, 'The Social Network', 'David Fincher', '2010', 6),
(24, 13, 'Parasite', 'Bong Joon Ho', '2019', 8),
(25, 5, 'Joker', 'Todd Phillips', '2019', 7),
(26, 10, 'The Truman Show', 'Peter Weir', '1998', 4),
(27, 8, 'A Beautiful Mind', 'Ron Howard', '2001', 5),
(28, 5, 'Titanic', 'James Cameron', '1997', 6),
(29, 14, 'The Pianist', 'Roman Polanski', '2002', 3),
(30, 11, 'Amélie', 'Jean-Pierre Jeunet', '2001', 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

CREATE TABLE `permisos` (
  `id_permisos` int(11) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `id_menu` int(11) NOT NULL,
  `id_submenu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id_permisos`, `id_rol`, `id_menu`, `id_submenu`) VALUES
(11, 4, 4, 4),
(12, 4, 4, 5),
(13, 1, 2, 3),
(15, 1, 4, 4),
(16, 1, 4, 5),
(17, 5, 4, 4),
(18, 5, 4, 5),
(20, 1, 7, 9),
(21, 2, 7, 8),
(22, 2, 7, 9),
(23, 1, 1, 1),
(25, 1, 5, 6),
(28, 3, 7, 8),
(31, 3, 2, 3),
(32, 2, 5, 6),
(33, 2, 4, 4),
(34, 2, 4, 5),
(35, 2, 2, 3),
(36, 2, 6, 7),
(37, 4, 7, 8),
(38, 4, 7, 9),
(39, 4, 6, 7),
(40, 4, 5, 6),
(41, 1, 3, 10),
(42, 3, 3, 10),
(43, 5, 3, 10),
(44, 2, 3, 10),
(45, 4, 3, 10),
(46, 1, 1, 2),
(47, 1, 6, 7),
(48, 1, 7, 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

CREATE TABLE `persona` (
  `Id_Persona` int(100) NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  `Apellido` varchar(100) NOT NULL,
  `Identificacion` varchar(12) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `Clave` varchar(100) NOT NULL,
  `Id_Rol` int(100) NOT NULL,
  `Id_Validacion` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`Id_Persona`, `Nombre`, `Apellido`, `Identificacion`, `Email`, `Clave`, `Id_Rol`, `Id_Validacion`) VALUES
(1, 'Cristian Herney ', 'Pascuas Ramirez', '1061712230', 'herneycristian@gmail.com', '1234', 1, 1),
(2, 'ASASASAS', 'AVAVASVAV', '1234565432', 'maximuselverde@gmail.com', 'Q34353', 2, 0),
(3, 'Angelica', 'Mendez', '1061790198', 'mendezangelica1803@gmail.com', 'O2vqRF2n', 3, 1),
(4, 'Wilmer', 'Peña', '76313911', 'wilpe88@gmail.com', '1234', 4, 1),
(5, 'ASASAS', 'AAA', '6554646', 'ASDFWER@gmail.com', '1234', 5, 1),
(7, 'aDSASD', 'DFADFSSDAFD', '3554543525', 'SDSEFDSFS@KSDHVBSDHKFB', '34234234', 4, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `renta`
--

CREATE TABLE `renta` (
  `Id_Renta` int(100) NOT NULL,
  `Id_Peliculas` int(100) NOT NULL,
  `Id_Persona` int(100) NOT NULL,
  `Id_EstadoRenta` int(100) NOT NULL,
  `InicioRenta` date NOT NULL,
  `FinRenta` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `renta`
--

INSERT INTO `renta` (`Id_Renta`, `Id_Peliculas`, `Id_Persona`, `Id_EstadoRenta`, `InicioRenta`, `FinRenta`) VALUES
(1, 9, 1, 2, '2025-05-20', '2025-05-31'),
(2, 4, 1, 2, '2025-05-20', '2025-05-31');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rol`
--

CREATE TABLE `rol` (
  `id_rol` int(100) NOT NULL,
  `rol_nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `rol`
--

INSERT INTO `rol` (`id_rol`, `rol_nombre`) VALUES
(1, 'Administrador'),
(2, 'Gerente'),
(3, 'Cajero'),
(4, 'Organizador'),
(5, 'Cliente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `submenu`
--

CREATE TABLE `submenu` (
  `Id_SubMenu` int(11) NOT NULL,
  `Nombre_SubMenu` varchar(150) NOT NULL,
  `nombre_archivo` varchar(150) NOT NULL,
  `Orden_SubMenu` int(11) NOT NULL,
  `Id_Menu` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci;

--
-- Volcado de datos para la tabla `submenu`
--

INSERT INTO `submenu` (`Id_SubMenu`, `Nombre_SubMenu`, `nombre_archivo`, `Orden_SubMenu`, `Id_Menu`) VALUES
(1, 'Roles', 'roles.php', 1, 1),
(2, 'Validacion', 'validacion.php', 2, 1),
(3, 'Ver Ingresos', '', 1, 2),
(4, 'Ver Rentas', '', 1, 4),
(5, 'Ver Deudas', '', 2, 4),
(6, 'Administrar Clientes', '', 1, 5),
(7, 'Administrar Staff', '', 1, 6),
(8, 'Ver Inventario', '', 1, 7),
(9, 'Agregar Inventario', '', 2, 7),
(10, 'Buscar Peliculas', '', 1, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `validacion`
--

CREATE TABLE `validacion` (
  `Id_Validacion` tinyint(1) NOT NULL,
  `nombre_validacion` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_spanish2_ci;

--
-- Volcado de datos para la tabla `validacion`
--

INSERT INTO `validacion` (`Id_Validacion`, `nombre_validacion`) VALUES
(0, 'Sin Permiso'),
(1, 'Permitido');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `deuda`
--
ALTER TABLE `deuda`
  ADD PRIMARY KEY (`Id_Deuda`),
  ADD KEY `Id_Persona` (`Id_Persona`),
  ADD KEY `Id_EstadoRenta` (`Id_EstadoRenta`),
  ADD KEY `Id_Peliculas` (`Id_Peliculas`),
  ADD KEY `Id_Renta` (`Id_Renta`);

--
-- Indices de la tabla `distribuidora`
--
ALTER TABLE `distribuidora`
  ADD PRIMARY KEY (`Id_Distribuidora`);

--
-- Indices de la tabla `estadorenta`
--
ALTER TABLE `estadorenta`
  ADD PRIMARY KEY (`Id_EstadoRenta`);

--
-- Indices de la tabla `menu`
--
ALTER TABLE `menu`
  ADD PRIMARY KEY (`Id_Menu`);

--
-- Indices de la tabla `peliculas`
--
ALTER TABLE `peliculas`
  ADD PRIMARY KEY (`Id_Peliculas`),
  ADD KEY `Id_Distribuidora` (`Id_Distribuidora`);

--
-- Indices de la tabla `permisos`
--
ALTER TABLE `permisos`
  ADD PRIMARY KEY (`id_permisos`),
  ADD KEY `id_rol` (`id_rol`),
  ADD KEY `id_menu` (`id_menu`),
  ADD KEY `id-submenu` (`id_submenu`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`Id_Persona`),
  ADD KEY `Id_Rol` (`Id_Rol`),
  ADD KEY `Id_Validacion` (`Id_Validacion`);

--
-- Indices de la tabla `renta`
--
ALTER TABLE `renta`
  ADD PRIMARY KEY (`Id_Renta`),
  ADD KEY `Id_Peliculas` (`Id_Peliculas`),
  ADD KEY `Id_EstadoRenta` (`Id_EstadoRenta`),
  ADD KEY `Id_Persona` (`Id_Persona`);

--
-- Indices de la tabla `rol`
--
ALTER TABLE `rol`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `submenu`
--
ALTER TABLE `submenu`
  ADD PRIMARY KEY (`Id_SubMenu`),
  ADD KEY `Id_Menu` (`Id_Menu`);

--
-- Indices de la tabla `validacion`
--
ALTER TABLE `validacion`
  ADD PRIMARY KEY (`Id_Validacion`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `deuda`
--
ALTER TABLE `deuda`
  MODIFY `Id_Deuda` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `distribuidora`
--
ALTER TABLE `distribuidora`
  MODIFY `Id_Distribuidora` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `estadorenta`
--
ALTER TABLE `estadorenta`
  MODIFY `Id_EstadoRenta` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `menu`
--
ALTER TABLE `menu`
  MODIFY `Id_Menu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `peliculas`
--
ALTER TABLE `peliculas`
  MODIFY `Id_Peliculas` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `permisos`
--
ALTER TABLE `permisos`
  MODIFY `id_permisos` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `Id_Persona` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `renta`
--
ALTER TABLE `renta`
  MODIFY `Id_Renta` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `rol`
--
ALTER TABLE `rol`
  MODIFY `id_rol` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `submenu`
--
ALTER TABLE `submenu`
  MODIFY `Id_SubMenu` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `deuda`
--
ALTER TABLE `deuda`
  ADD CONSTRAINT `deuda_ibfk_1` FOREIGN KEY (`Id_Persona`) REFERENCES `persona` (`Id_Persona`),
  ADD CONSTRAINT `deuda_ibfk_2` FOREIGN KEY (`Id_EstadoRenta`) REFERENCES `estadorenta` (`Id_EstadoRenta`),
  ADD CONSTRAINT `deuda_ibfk_3` FOREIGN KEY (`Id_Peliculas`) REFERENCES `peliculas` (`Id_Peliculas`),
  ADD CONSTRAINT `deuda_ibfk_4` FOREIGN KEY (`Id_Renta`) REFERENCES `renta` (`Id_Renta`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
