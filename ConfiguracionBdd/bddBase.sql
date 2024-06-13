-- phpMyAdmin SQL Dump
-- version 5.2.1deb1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 11-06-2024 a las 21:36:44
-- Versión del servidor: 10.11.6-MariaDB-0+deb12u1
-- Versión de PHP: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sesamo`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alertas`
--

CREATE TABLE IF NOT EXISTS   `alertas` (
  `codigo` int(11) NOT NULL,
  `descripcion` varchar(200) DEFAULT NULL,
  `observaciones` varchar(400) DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `nhc` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alertas`
--

INSERT INTO `alertas` (`codigo`, `descripcion`, `observaciones`, `fecha`, `nhc`) VALUES
(1, 'Alergia Ibuprofeno', '', '2024-06-06', 1),
(3, 'alergia polen 2', '', '2024-06-06', 3),
(4, 'Alergia cacahuetes', 'No más cacahuetes', '2024-06-07', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alta`
--

CREATE TABLE IF NOT EXISTS   `alta` (
  `id_alta` int(11) NOT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `motivo` varchar(300) DEFAULT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `nhc` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alta`
--

INSERT INTO `alta` (`id_alta`, `fecha`, `hora`, `motivo`, `tipo`, `nhc`) VALUES
(1, '2024-06-06', '18:52:34', 'Domicilio (fin de cuidados)', 'Hospitalización', 2),
(2, '2024-06-08', '01:16:06', 'Fuga', 'Urgencias', 2),
(3, '2024-06-09', '16:26:20', 'Alta Voluntaria', 'Hospitalización', 16),
(4, '2024-06-09', '16:26:48', 'Exitus por gripe H1N1', 'Hospitalización', 16),
(5, '2024-06-09', '16:28:04', 'Alta Voluntaria', 'Hospitalización', 5),
(6, '2024-06-09', '19:54:56', 'Exitus', 'Hospitalización', 2),
(7, '2024-06-10', '12:53:13', 'Alta Voluntaria', 'Hospitalización', 15),
(8, '2024-06-10', '16:10:47', 'Domicilio (fin de cuidados)', 'Hospitalización', 1),
(9, '2024-06-10', '16:18:48', 'Alta Voluntaria', 'Hospitalización', 2),
(10, '2024-06-10', '16:31:04', 'Alta Voluntaria', 'Hospitalización', 24),
(11, '2024-06-10', '16:32:15', 'Alta Voluntaria', 'Hospitalización', 3),
(12, '2024-06-10', '16:32:21', 'Exitus', 'Hospitalización', 4),
(13, '2024-06-10', '16:32:32', 'Domicilio (fin de cuidados)', 'Hospitalización', 21),
(14, '2024-06-10', '16:32:39', 'Translado a otro centro sanitario', 'Hospitalización', 22),
(15, '2024-06-10', '16:33:35', 'Alta Voluntaria', 'Hospitalización', 19),
(16, '2024-06-10', '16:38:00', 'Alta Voluntaria', 'Hospitalización', 1),
(17, '2024-06-10', '16:46:37', 'Alta Voluntaria', 'Hospitalización', 1),
(18, '2024-06-10', '16:46:59', 'Exitus', 'Hospitalización', 1),
(19, '2024-06-10', '16:49:57', 'Exitus', 'Hospitalización', 1),
(20, '2024-06-10', '16:51:14', 'Domicilio (fin de cuidados)', 'Hospitalización', 1),
(21, '2024-06-10', '16:57:48', 'Alta Voluntaria', 'Urgencias', 4),
(22, '2024-06-10', '16:58:55', 'Alta Voluntaria', 'Hospitalización', 3),
(23, '2024-06-10', '16:59:17', 'Exitus', 'Urgencias', 4),
(24, '2024-06-10', '17:05:23', 'Alta Voluntaria', 'Hospitalización', 4),
(25, '2024-06-10', '17:07:01', 'Domicilio (fin de cuidados)', 'Hospitalización', 3),
(26, '2024-06-10', '17:44:28', 'Alta Voluntaria', 'Hospitalización', 1),
(27, '2024-06-10', '17:52:51', 'Domicilio (fin de cuidados)', 'Hospitalización', 1),
(28, '2024-06-10', '17:53:31', 'Exitus', 'Urgencias', 2),
(29, '2024-06-10', '17:54:48', 'Domicilio (fin de cuidados)', 'Hospitalización', 9),
(30, '2024-06-10', '17:54:57', 'Fuga', 'Urgencias', 10),
(31, '2024-06-10', '17:55:05', 'Translado a otro centro sanitario', 'Hospitalización', 8),
(32, '2024-06-10', '18:02:53', 'Alta Voluntaria', 'Hospitalización', 26),
(33, '2024-06-11', '16:43:37', 'Domicilio (fin de cuidados)', 'Hospitalización', 4),
(34, '2024-06-11', '19:33:28', 'Domicilio (fin de cuidados)', 'Hospitalización', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ambitos`
--

CREATE TABLE IF NOT EXISTS   `ambitos` (
  `id_ambito` int(11) NOT NULL,
  `nombre` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ambitos`
--

INSERT INTO `ambitos` (`id_ambito`, `nombre`) VALUES
(1, 'Hospitalización'),
(2, 'Urgencias'),
(3, 'Consultas');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `areaSalud`
--

CREATE TABLE IF NOT EXISTS   `areaSalud` (
  `id_area_salud` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `areaSalud`
--

INSERT INTO `areaSalud` (`id_area_salud`, `nombre`) VALUES
(1, 'Albacete'),
(2, 'Almansa (Albacete)'),
(3, 'Hellín (Albacete)'),
(4, 'Villarrobledo (Albacete)'),
(5, 'Alcázar de San Juan (La Mancha Centro)'),
(6, 'Tomelloso (La Mancha Centro)'),
(7, 'Ciudad Real'),
(8, 'Manzanares (Ciudad Real)'),
(9, 'Valdepeñas (Ciudad Real)'),
(10, 'Cuenca'),
(11, 'Guadalajara'),
(12, 'Talavera'),
(13, 'Toledo'),
(14, 'Puertollano');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `camas`
--

CREATE TABLE IF NOT EXISTS   `camas` (
  `id_cama` int(11) NOT NULL,
  `id_planta` int(11) DEFAULT NULL,
  `id_habitacion` int(11) DEFAULT NULL,
  `letra` varchar(1) DEFAULT NULL,
  `bloqueada` char(1) DEFAULT NULL,
  `estado` varchar(50) DEFAULT NULL,
  `id_centro` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `camas`
--

INSERT INTO `camas` (`id_cama`, `id_planta`, `id_habitacion`, `letra`, `bloqueada`, `estado`, `id_centro`) VALUES
(1, 1, 1, 'A', 'N', 'Ocupada', 10),
(2, 1, 1, 'B', 'N', 'Ocupada', 10),
(3, 1, 2, 'A', 'N', 'Ocupada', 10),
(4, 1, 2, 'B', 'S', 'Disponible', 10),
(5, 1, 3, 'A', 'S', 'Disponible', 10),
(6, 1, 3, 'B', 'N', 'Disponible', 10),
(7, 1, 4, 'A', 'N', 'Ocupada', 10),
(8, 1, 4, 'B', 'N', 'Disponible', 10),
(9, 1, 5, 'A', 'N', 'Disponible', 10),
(10, 1, 5, 'B', 'N', 'Disponible', 10),
(11, 1, 6, 'A', 'N', 'Disponible', 10),
(12, 1, 6, 'B', 'N', 'Disponible', 10),
(13, 1, 7, 'A', 'N', 'Disponible', 10),
(14, 1, 7, 'B', 'N', 'Disponible', 10),
(15, 1, 8, 'A', 'N', 'Disponible', 10),
(16, 1, 8, 'B', 'N', 'Disponible', 10),
(17, 1, 9, 'A', 'N', 'Disponible', 10),
(18, 1, 9, 'B', 'N', 'Disponible', 10),
(19, 1, 10, 'A', 'S', 'Disponible', 10),
(20, 1, 10, 'B', 'N', 'Disponible', 10),
(21, 2, 1, 'A', 'N', 'Disponible', 10),
(22, 2, 1, 'B', 'N', 'Disponible', 10),
(23, 2, 2, 'A', 'N', 'Disponible', 10),
(24, 2, 2, 'B', 'N', 'Disponible', 10),
(25, 2, 3, 'A', 'N', 'Disponible', 10),
(26, 2, 3, 'B', 'N', 'Disponible', 10),
(27, 2, 4, 'A', 'N', 'Disponible', 10),
(28, 2, 4, 'B', 'N', 'Disponible', 10),
(29, 2, 5, 'A', 'N', 'Disponible', 10),
(30, 2, 5, 'B', 'N', 'Disponible', 10),
(31, 2, 6, 'A', 'N', 'Disponible', 10),
(32, 2, 6, 'B', 'N', 'Disponible', 10),
(33, 2, 7, 'A', 'N', 'Disponible', 10),
(34, 2, 7, 'B', 'N', 'Disponible', 10),
(35, 2, 8, 'A', 'N', 'Disponible', 10),
(36, 2, 8, 'B', 'N', 'Disponible', 10),
(37, 2, 9, 'A', 'N', 'Disponible', 10),
(38, 2, 9, 'B', 'N', 'Disponible', 10),
(39, 2, 10, 'A', 'N', 'Disponible', 10),
(40, 2, 10, 'B', 'N', 'Disponible', 10),
(41, 3, 1, 'A', 'N', 'Disponible', 10),
(42, 3, 1, 'B', 'S', 'Disponible', 10),
(43, 3, 2, 'A', 'N', 'Disponible', 10),
(44, 3, 2, 'B', 'N', 'Disponible', 10),
(45, 3, 3, 'A', 'N', 'Disponible', 10),
(46, 3, 3, 'B', 'N', 'Disponible', 10),
(47, 3, 4, 'A', 'N', 'Disponible', 10),
(48, 3, 4, 'B', 'N', 'Disponible', 10),
(49, 3, 5, 'A', 'N', 'Disponible', 10),
(50, 3, 5, 'B', 'N', 'Disponible', 10),
(51, 3, 6, 'A', 'N', 'Disponible', 10),
(52, 3, 6, 'B', 'N', 'Disponible', 10),
(53, 3, 7, 'A', 'N', 'Disponible', 10),
(54, 3, 7, 'B', 'N', 'Disponible', 10),
(55, 3, 8, 'A', 'N', 'Disponible', 10),
(56, 3, 8, 'B', 'N', 'Disponible', 10),
(57, 3, 9, 'A', 'N', 'Disponible', 10),
(58, 3, 9, 'B', 'N', 'Disponible', 10),
(59, 3, 10, 'A', 'N', 'Disponible', 10),
(60, 3, 10, 'B', 'N', 'Disponible', 10),
(61, 4, 1, 'A', 'N', 'Disponible', 10),
(62, 4, 1, 'B', 'N', 'Disponible', 10),
(63, 4, 2, 'A', 'N', 'Disponible', 10),
(64, 4, 2, 'B', 'N', 'Disponible', 10),
(65, 4, 3, 'A', 'N', 'Disponible', 10),
(66, 4, 3, 'B', 'N', 'Disponible', 10),
(67, 4, 4, 'A', 'N', 'Disponible', 10),
(68, 4, 4, 'B', 'N', 'Disponible', 10),
(69, 4, 5, 'A', 'N', 'Disponible', 10),
(70, 4, 5, 'B', 'N', 'Disponible', 10),
(71, 4, 6, 'A', 'N', 'Disponible', 10),
(72, 4, 6, 'B', 'N', 'Disponible', 10),
(73, 4, 7, 'A', 'N', 'Disponible', 10),
(74, 4, 7, 'B', 'N', 'Disponible', 10),
(75, 4, 8, 'A', 'N', 'Disponible', 10),
(76, 4, 8, 'B', 'N', 'Disponible', 10),
(77, 4, 9, 'A', 'N', 'Disponible', 10),
(78, 4, 9, 'B', 'N', 'Disponible', 10),
(79, 4, 10, 'A', 'N', 'Disponible', 10),
(80, 4, 10, 'B', 'N', 'Disponible', 10),
(81, 1, 1, 'A', 'N', 'Disponible', 1),
(82, 1, 1, 'B', 'N', 'Disponible', 1),
(83, 2, 1, 'A', 'N', 'Disponible', 1),
(84, 2, 1, 'B', 'N', 'Disponible', 1),
(85, 1, 1, 'A', 'N', 'Disponible', 2),
(86, 1, 1, 'B', 'N', 'Disponible', 2),
(87, 2, 1, 'A', 'N', 'Disponible', 2),
(88, 2, 1, 'B', 'N', 'Disponible', 2),
(89, 1, 1, 'A', 'N', 'Disponible', 3),
(90, 1, 1, 'B', 'N', 'Disponible', 3),
(91, 2, 1, 'A', 'N', 'Disponible', 3),
(92, 2, 1, 'B', 'N', 'Disponible', 3),
(93, 1, 1, 'A', 'N', 'Disponible', 4),
(94, 1, 1, 'B', 'N', 'Disponible', 4),
(95, 2, 1, 'A', 'N', 'Disponible', 4),
(96, 2, 1, 'B', 'N', 'Disponible', 4),
(97, 1, 1, 'A', 'N', 'Disponible', 5),
(98, 1, 1, 'B', 'N', 'Disponible', 5),
(99, 2, 1, 'A', 'N', 'Disponible', 5),
(100, 2, 1, 'B', 'N', 'Disponible', 5),
(101, 1, 1, 'A', 'N', 'Disponible', 6),
(102, 1, 1, 'B', 'N', 'Disponible', 6),
(103, 2, 1, 'A', 'N', 'Disponible', 6),
(104, 2, 1, 'B', 'N', 'Disponible', 6),
(105, 1, 1, 'A', 'N', 'Disponible', 7),
(106, 1, 1, 'B', 'N', 'Disponible', 7),
(107, 2, 1, 'A', 'N', 'Disponible', 7),
(108, 2, 1, 'B', 'N', 'Disponible', 7),
(109, 1, 1, 'A', 'N', 'Disponible', 8),
(110, 1, 1, 'B', 'N', 'Disponible', 8),
(111, 2, 1, 'A', 'N', 'Disponible', 8),
(112, 2, 1, 'B', 'N', 'Disponible', 8),
(113, 1, 1, 'A', 'N', 'Disponible', 9),
(114, 1, 1, 'B', 'N', 'Disponible', 9),
(115, 2, 1, 'A', 'N', 'Disponible', 9),
(116, 2, 1, 'B', 'N', 'Disponible', 9),
(121, 1, 1, 'A', 'N', 'Disponible', 11),
(122, 1, 1, 'B', 'N', 'Disponible', 11),
(123, 2, 1, 'A', 'N', 'Disponible', 11),
(124, 2, 1, 'B', 'N', 'Disponible', 11),
(125, 1, 1, 'A', 'N', 'Disponible', 12),
(126, 1, 1, 'B', 'N', 'Disponible', 12),
(127, 2, 1, 'A', 'N', 'Disponible', 12),
(128, 2, 1, 'B', 'N', 'Disponible', 12),
(129, 1, 1, 'A', 'N', 'Disponible', 13),
(130, 1, 1, 'B', 'N', 'Disponible', 13),
(131, 2, 1, 'A', 'N', 'Disponible', 13),
(132, 2, 1, 'B', 'N', 'Disponible', 13),
(133, 1, 1, 'A', 'N', 'Disponible', 14),
(134, 1, 1, 'B', 'N', 'Disponible', 14),
(135, 2, 1, 'A', 'N', 'Disponible', 14),
(136, 2, 1, 'B', 'N', 'Disponible', 14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `centros`
--

CREATE TABLE IF NOT EXISTS   `centros` (
  `id_centro` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `id_area_salud` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `centros`
--

INSERT INTO `centros` (`id_centro`, `nombre`, `id_area_salud`) VALUES
(1, 'Complejo Hospitalario Universitario de Albacete', 1),
(2, 'Hospital general de Almansa', 2),
(3, 'Hospital de Hellín', 3),
(4, 'Hospital General de Villarrobledo', 4),
(5, 'Hospital General La Mancha Centro', 5),
(6, 'Hospital General de Tomelloso', 6),
(7, 'Hospital General Universitario de Ciudad Real', 7),
(8, 'Hospital Virgen de Altagracia', 8),
(9, 'Hospital Gutiérrez Ortega', 9),
(10, 'Hospital Virgen de la Luz', 10),
(11, 'Hospital General Universitario de Guadalajara', 11),
(12, 'Hospital Nuestra Señora del Prado', 12),
(13, 'Hospital Universitario de Toledo', 13),
(14, 'Hospital de Santa Bárbara', 14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comunidadautonoma`
--

CREATE TABLE IF NOT EXISTS   `comunidadautonoma` (
  `id_cautonoma` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `comunidadautonoma`
--

INSERT INTO `comunidadautonoma` (`id_cautonoma`, `nombre`) VALUES
(0, ' '),
(1, 'Andalucía'),
(2, 'Aragón'),
(3, 'Asturias'),
(4, 'Cantabria'),
(5, 'Castilla-La Mancha'),
(6, 'Castilla y León'),
(7, 'Cataluña'),
(8, 'Ceuta'),
(9, 'Extremadura'),
(10, 'Galicia'),
(11, 'Islas Baleares'),
(12, 'Islas Canarias'),
(13, 'La Rioja'),
(14, 'Madrid'),
(15, 'Melilla'),
(16, 'Murcia'),
(17, 'Navarra'),
(18, 'Comunidad Valenciana'),
(19, 'País Vasco');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hoja_prescripcion`
--

CREATE TABLE IF NOT EXISTS   `hoja_prescripcion` (
  `id_hoja` int(11) NOT NULL,
  `especialidad` varchar(255) DEFAULT NULL,
  `principio_activo` varchar(255) DEFAULT NULL,
  `dosis` varchar(50) DEFAULT NULL,
  `via_administracion` varchar(50) DEFAULT NULL,
  `frecuencia` varchar(50) DEFAULT NULL,
  `fecha_fin_tratamiento` date DEFAULT NULL,
  `nhc` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `hoja_prescripcion`
--

INSERT INTO `hoja_prescripcion` (`id_hoja`, `especialidad`, `principio_activo`, `dosis`, `via_administracion`, `frecuencia`, `fecha_fin_tratamiento`, `nhc`) VALUES
(1, 'Cardiología', 'Aspirina', '100 mg', 'Oral', 'Diaria', '2024-12-31', 1),
(2, 'Neurología', 'Ibuprofeno', '200 mg', 'Oral', 'Cada 8 horas', '2024-11-30', 2),
(3, 'Dermatología', 'Clotrimazol', '1%', 'Tópica', 'Diaria', '2024-10-31', 3),
(4, 'Pediatría', 'Paracetamol', '250 mg', 'Oral', 'Cada 6 horas', '2024-09-30', 4),
(5, 'Gastroenterología', 'Omeprazol', '20 mg', 'Oral', 'Diaria', '2024-08-31', 5),
(6, 'Neumología', 'Salbutamol', '100 mcg', 'Inhalación', 'Cada 4 horas', '2024-07-31', 6),
(7, 'Reumatología', 'Metotrexato', '10 mg', 'Oral', 'Semanal', '2024-06-30', 7),
(8, 'Oncología', 'Cisplatino', '50 mg/m2', 'Intravenosa', 'Cada 3 semanas', '2024-05-31', 8),
(9, 'Psiquiatría', 'Sertralina', '50 mg', 'Oral', 'Diaria', '2024-04-30', 9),
(10, 'Endocrinología', 'Levotiroxina', '75 mcg', 'Oral', 'Diaria', '2024-03-31', 10),
(11, 'Cardiología', 'Aspirina', '100 mg', 'Oral', 'Diaria', '2024-12-31', 1),
(12, 'Neurología', 'Ibuprofeno', '200 mg', 'Oral', 'Cada 8 horas', '2024-11-30', 1),
(13, 'Endocrinología', 'Metformina', '500 mg', 'Oral', 'Diaria', '2024-10-15', 1),
(14, 'Cardiología', 'Aspirina', '100 mg', 'Oral', 'Diaria', '2024-12-31', 1),
(15, 'Neurología', 'Ibuprofeno', '200 mg', 'Oral', 'Cada 8 horas', '2024-11-30', 1),
(16, 'Endocrinología', 'Metformina', '500 mg', 'Oral', 'Diaria', '2024-10-15', 1),
(17, 'Gastroenterología', 'Omeprazol', '20 mg', 'Oral', 'Antes del desayuno', '2024-09-20', 1),
(18, 'Dermatología', 'Corticosteroide', '10 mg', 'Tópico', 'Cada 12 horas', '2024-08-25', 1),
(19, 'Neumología', 'Paracetamol', '1', 'Oral', 'Cada 8 horas', '2036-05-05', 4),
(20, 'Farmacia', 'actimel', '1', 'Oral', '8', '2024-07-12', 19);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingresos`
--

CREATE TABLE IF NOT EXISTS   `ingresos` (
  `id_ingreso` int(11) NOT NULL,
  `estado_nhc` varchar(20) NOT NULL,
  `id_ambito` int(11) NOT NULL,
  `Fecha` date DEFAULT NULL,
  `Hora` time DEFAULT NULL,
  `id_cama` int(11) DEFAULT NULL,
  `nhc` int(11) NOT NULL,
  `ingresado` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ingresos`
--

INSERT INTO `ingresos` (`id_ingreso`, `estado_nhc`, `id_ambito`, `Fecha`, `Hora`, `id_cama`, `nhc`, `ingresado`) VALUES
(1, 'Provisional', 1, '2024-06-06', '18:37:56', 1, 1, 0),
(2, 'Provisional', 1, '2024-06-06', '18:38:08', 2, 2, 0),
(3, 'Provisional', 2, '2024-06-06', '18:38:16', 3, 3, 0),
(4, 'Provisional', 3, '2024-06-06', '18:38:24', 4, 4, 0),
(5, 'Provisional', 1, '2024-06-06', '20:31:56', 16, 2, 0),
(6, 'Provisional', 1, '2024-06-07', '18:40:05', 2, 5, 0),
(7, 'Provisional', 1, '2024-06-08', '18:19:05', 6, 15, 0),
(8, 'Provisional', 1, '2024-06-08', '19:36:08', 7, 2, 0),
(9, 'Provisional', 2, '2024-06-09', '16:20:35', 9, 16, 0),
(10, 'Provisional', 2, '2024-06-09', '16:26:14', 9, 16, 0),
(11, 'Provisional', 1, '2024-06-09', '19:45:02', 41, 19, 0),
(12, 'Provisional', 3, '2024-06-09', '20:23:33', 14, 21, 0),
(13, 'Provisional', 3, '2024-06-09', '20:24:10', 15, 22, 0),
(14, 'Provisional', 1, '2024-06-10', '12:50:24', 7, 23, 0),
(15, 'Provisional', 1, '2024-06-10', '13:52:38', 6, 24, 0),
(16, 'Provisional', 1, '2024-06-10', '16:33:42', 1, 1, 0),
(17, 'Provisional', 1, '2024-06-10', '16:40:58', 1, 1, 0),
(18, 'Provisional', 1, '2024-06-10', '16:45:24', 2, 2, 0),
(19, 'Provisional', 1, '2024-06-10', '16:46:16', 3, 3, 0),
(20, 'Provisional', 1, '2024-06-10', '16:46:25', 4, 4, 0),
(21, 'Provisional', 1, '2024-06-10', '16:46:44', 7, 1, 0),
(22, 'Provisional', 1, '2024-06-10', '16:47:07', 1, 1, 0),
(23, 'Provisional', 1, '2024-06-10', '16:49:55', 1, 1, 0),
(24, 'Provisional', 1, '2024-06-10', '16:58:23', 4, 4, 0),
(25, 'Provisional', 1, '2024-06-10', '17:01:01', 1, 1, 0),
(26, 'Provisional', 1, '2024-06-10', '17:01:33', 2, 2, 0),
(27, 'Provisional', 1, '2024-06-10', '17:01:45', 3, 3, 0),
(28, 'Provisional', 2, '2024-06-10', '17:01:56', 4, 4, 0),
(29, 'Provisional', 1, '2024-06-10', '17:19:21', 2, 2, 0),
(30, 'Provisional', 1, '2024-06-10', '17:23:47', 2, 10, 0),
(31, 'Provisional', 2, '2024-06-10', '17:44:25', 1, 2, 0),
(32, 'Provisional', 1, '2024-06-10', '17:52:26', 1, 1, 0),
(33, 'Provisional', 1, '2024-06-10', '17:53:08', 1, 2, 0),
(34, 'Provisional', 1, '2024-06-10', '17:53:39', 2, 10, 0),
(35, 'Provisional', 1, '2024-06-10', '17:53:47', 3, 9, 0),
(36, 'Provisional', 3, '2024-06-10', '17:53:56', 1, 8, 0),
(37, 'Provisional', 1, '2024-06-10', '17:55:40', 2, 2, 1),
(38, 'Provisional', 1, '2024-06-10', '17:55:56', 3, 3, 1),
(39, 'Provisional', 2, '2024-06-10', '17:57:52', 1, 1, 1),
(40, 'Provisional', 1, '2024-06-10', '18:01:36', 130, 26, 0),
(41, 'Provisional', 1, '2024-06-10', '18:43:25', 4, 4, 0),
(42, 'Provisional', 1, '2024-06-11', '01:53:31', 6, 5, 0),
(43, 'Provisional', 2, '2024-06-11', '11:09:59', 7, 10, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notas_enfermeria`
--

CREATE TABLE IF NOT EXISTS   `notas_enfermeria` (
  `id_notas` int(11) NOT NULL,
  `fecha_toma` date DEFAULT NULL,
  `hora_toma` time DEFAULT NULL,
  `temperatura` decimal(5,2) DEFAULT NULL,
  `tension_arterial_sistolica` int(11) DEFAULT NULL,
  `tension_arterial_diastolica` int(11) DEFAULT NULL,
  `frecuencia_cardiaca` int(11) DEFAULT NULL,
  `frecuencia_respiratoria` int(11) DEFAULT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `talla` decimal(5,2) DEFAULT NULL,
  `indice_masa_corporal` decimal(5,2) DEFAULT NULL,
  `glucemia_capilar` decimal(5,2) DEFAULT NULL,
  `ingesta_alimentos` varchar(100) DEFAULT NULL,
  `tipo_deposicion` varchar(100) DEFAULT NULL,
  `nauseas` varchar(100) DEFAULT NULL,
  `prurito` varchar(100) DEFAULT NULL,
  `observaciones` varchar(100) DEFAULT NULL,
  `balance_hidrico_entrada_alimentos` int(11) DEFAULT NULL,
  `balance_hidrico_entrada_liquidos` int(11) DEFAULT NULL,
  `balance_hidrico_fluidoterapia` int(11) DEFAULT NULL,
  `balance_hidrico_hemoderivados` int(11) DEFAULT NULL,
  `balance_hidrico_otros_entrada` int(11) DEFAULT NULL,
  `balance_hidrico_diuresis` int(11) DEFAULT NULL,
  `balance_hidrico_vomitos` int(11) DEFAULT NULL,
  `balance_hidrico_heces` int(11) DEFAULT NULL,
  `balance_hidrico_sonda_nasogastrica` int(11) DEFAULT NULL,
  `balance_hidrico_drenajes` int(11) DEFAULT NULL,
  `balance_hidrico_otras_perdidas` int(11) DEFAULT NULL,
  `total_balance_hidrico` int(11) DEFAULT NULL,
  `nhc` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `notas_enfermeria`
--

INSERT INTO `notas_enfermeria` (`id_notas`, `fecha_toma`, `hora_toma`, `temperatura`, `tension_arterial_sistolica`, `tension_arterial_diastolica`, `frecuencia_cardiaca`, `frecuencia_respiratoria`, `peso`, `talla`, `indice_masa_corporal`, `glucemia_capilar`, `ingesta_alimentos`, `tipo_deposicion`, `nauseas`, `prurito`, `observaciones`, `balance_hidrico_entrada_alimentos`, `balance_hidrico_entrada_liquidos`, `balance_hidrico_fluidoterapia`, `balance_hidrico_hemoderivados`, `balance_hidrico_otros_entrada`, `balance_hidrico_diuresis`, `balance_hidrico_vomitos`, `balance_hidrico_heces`, `balance_hidrico_sonda_nasogastrica`, `balance_hidrico_drenajes`, `balance_hidrico_otras_perdidas`, `total_balance_hidrico`, `nhc`) VALUES
(1, '2024-06-01', '08:00:00', 36.50, 120, 80, 75, 18, 70.50, 175.00, 23.00, 90.00, 'Desayuno completo', 'Normal', 'No', 'No', 'Paciente estable', 500, 1000, 200, 0, 0, 1500, 0, 0, 0, 0, 0, 1500, 1),
(2, '2024-06-02', '09:00:00', 37.00, 118, 78, 70, 19, 68.00, 172.00, 22.50, 85.00, 'Almuerzo ligero', 'Normal', 'No', 'No', 'Paciente en buenas condiciones', 600, 1200, 250, 0, 0, 1600, 0, 0, 0, 0, 0, 1600, 2),
(3, '2024-06-03', '10:00:00', 36.80, 115, 75, 72, 20, 75.00, 178.00, 23.70, 95.00, 'Cena', 'Normal', 'No', 'No', 'Paciente descansando', 550, 1100, 300, 0, 0, 1400, 0, 0, 0, 0, 0, 1400, 3),
(4, '2024-06-04', '11:00:00', 36.90, 117, 77, 74, 18, 72.50, 173.00, 24.20, 88.00, 'Desayuno ligero', 'Normal', 'No', 'No', 'Paciente sin quejas', 600, 1150, 220, 0, 0, 1550, 0, 0, 0, 0, 0, 1550, 4),
(5, '2024-06-05', '12:00:00', 37.20, 122, 82, 78, 17, 80.00, 180.00, 24.70, 100.00, 'Almuerzo completo', 'Normal', 'No', 'No', 'Paciente estable', 700, 1300, 240, 0, 0, 1700, 0, 0, 0, 0, 0, 1700, 5),
(6, '2024-06-06', '13:00:00', 37.50, 125, 85, 80, 16, 85.00, 182.00, 25.60, 105.00, 'Desayuno', 'Normal', 'No', 'No', 'Paciente sin quejas', 750, 1350, 260, 0, 0, 1750, 0, 0, 0, 0, 0, 1750, 6),
(7, '2024-06-01', '08:00:00', 36.50, 120, 80, 75, 18, 70.50, 175.00, 23.00, 90.00, 'Desayuno completo', 'Normal', 'No', 'No', 'Paciente estable', 500, 1000, 200, 0, 0, 1500, 0, 0, 0, 0, 0, 1500, 1),
(8, '2024-06-02', '09:00:00', 37.00, 118, 78, 70, 19, 68.00, 172.00, 22.50, 85.00, 'Almuerzo ligero', 'Normal', 'No', 'No', 'Paciente en buenas condiciones', 600, 1200, 250, 0, 0, 1600, 0, 0, 0, 0, 0, 1600, 2),
(9, '2024-06-03', '10:00:00', 36.80, 115, 75, 72, 20, 75.00, 178.00, 23.70, 95.00, 'Cena', 'Normal', 'No', 'No', 'Paciente descansando', 550, 1100, 300, 0, 0, 1400, 0, 0, 0, 0, 0, 1400, 3),
(10, '2024-06-04', '11:00:00', 36.90, 117, 77, 74, 18, 72.50, 173.00, 24.20, 88.00, 'Desayuno ligero', 'Normal', 'No', 'No', 'Paciente sin quejas', 600, 1150, 220, 0, 0, 1550, 0, 0, 0, 0, 0, 1550, 4),
(11, '2024-06-05', '12:00:00', 37.20, 122, 82, 78, 17, 80.00, 180.00, 24.70, 100.00, 'Almuerzo completo', 'Normal', 'No', 'No', 'Paciente estable', 700, 1300, 240, 0, 0, 1700, 0, 0, 0, 0, 0, 1700, 5),
(12, '2024-06-06', '13:00:00', 37.50, 125, 85, 80, 16, 85.00, 182.00, 25.60, 105.00, 'Desayuno', 'Normal', 'No', 'No', 'Paciente sin quejas', 750, 1350, 260, 0, 0, 1750, 0, 0, 0, 0, 0, 1750, 6),
(13, '2024-06-07', '03:09:47', 25.00, 25, 25, 50, 50, 66.00, 170.00, 22.84, 60.00, 'Desayuno', 'Normal', 'No', 'No', 'Ninguna', 2, 5, 6, 2, 5, 2, 1, 1, 1, 1, 1, 13, 1),
(14, '2024-06-07', '03:13:36', 25.00, 25, 25, 25, 25, 50.00, 160.00, 19.53, 25.00, 'no', 'normal', 'No', 'No', 'no', 5, 555, 55, 55, 55, 55, 5, 5, 5, 5, 5, 645, 3),
(15, '2024-06-09', '19:47:49', 36.00, 120, 70, 71, 2, 70.00, 156.00, 28.76, 10.00, 'demasiado', 'bien', 'No', 'Sí', 'esta mayor la pobre', 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, 10, -10, 19);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
--

CREATE TABLE IF NOT EXISTS   `pacientes` (
  `nhc` int(11) NOT NULL,
  `nif` varchar(20) NOT NULL,
  `numeroSS` bigint(20) DEFAULT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `apellido1` varchar(100) DEFAULT NULL,
  `apellido2` varchar(100) DEFAULT NULL,
  `sexo` varchar(50) DEFAULT NULL,
  `fechaNacimiento` date DEFAULT NULL,
  `telefono1` int(11) DEFAULT NULL,
  `telefono2` int(11) DEFAULT 0,
  `movil` int(11) DEFAULT 0,
  `estadoCivil` varchar(20) DEFAULT NULL,
  `estudios` varchar(100) DEFAULT NULL,
  `fallecido` varchar(3) DEFAULT NULL,
  `nacionalidad` varchar(20) DEFAULT NULL,
  `cAutonoma` varchar(100) DEFAULT NULL,
  `provincia` varchar(100) DEFAULT NULL,
  `poblacion` varchar(100) DEFAULT NULL,
  `cp` int(11) DEFAULT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `titular` varchar(100) DEFAULT NULL,
  `regimen` varchar(100) DEFAULT NULL,
  `tis` varchar(20) DEFAULT NULL,
  `medico` varchar(100) DEFAULT NULL,
  `cap` varchar(100) DEFAULT NULL,
  `zona` varchar(100) DEFAULT NULL,
  `area` varchar(100) DEFAULT NULL,
  `nacimiento` varchar(20) DEFAULT NULL,
  `cAutonomaNacimiento` varchar(100) DEFAULT NULL,
  `provinciaNacimiento` varchar(100) DEFAULT NULL,
  `poblacionNacimiento` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pacientes`
--

INSERT INTO `pacientes` (`nhc`, `nif`, `numeroSS`, `nombre`, `apellido1`, `apellido2`, `sexo`, `fechaNacimiento`, `telefono1`, `telefono2`, `movil`, `estadoCivil`, `estudios`, `fallecido`, `nacionalidad`, `cAutonoma`, `provincia`, `poblacion`, `cp`, `direccion`, `titular`, `regimen`, `tis`, `medico`, `cap`, `zona`, `area`, `nacimiento`, `cAutonomaNacimiento`, `provinciaNacimiento`, `poblacionNacimiento`) VALUES
(1, '88888881A', 1600000081, 'Manuel', 'Hernandez', 'Lopez', 'Hombre', '1982-06-15', 987654321, 5849994, 612345678, 'Casado', 'Abogado', 'No', 'Nacional', 'Castilla-La Mancha', 'Cuenca', '', 16006, 'Calle del Sol 15', 'Manuel Hernandez Sr.', 'Seguridad Social', 'TIS006', 'Dr. Ruiz', 'Cuenca 1 (Cuenca)', 'Cuenca 1 ', 'Cuenca', 'Nacional', 'Castilla-La Mancha', 'Cuenca', 'Cuenca'),
(2, '88888882B', 1600000082, 'Marta', 'Fernandez', 'Gomez', 'Mujer', '1990-03-22', 998877665, 932112345, 609876543, 'Soltera', 'Economista', 'No', 'Nacional', 'Castilla-La Mancha', 'Cuenca', 'Cuenca', 16007, 'Avenida de la Libertad 25', 'Marta Fernandez Sr.', 'Seguridad Social', 'TIS007', 'Dra. Perez', 'Cuenca 1 (Cuenca)', 'Cuenca 1 ', 'Cuenca', 'Nacional', 'Castilla-La Mancha', 'Cuenca', 'Cuenca'),
(3, '88888883C', 1600000083, 'Antonio', 'Lopez', 'Martinez', 'Hombre', '1985-08-09', 987654123, NULL, 698765432, 'Divorciado', 'Ingeniero', 'No', 'Nacional', 'Castilla-La Mancha', 'Cuenca', 'Cuenca', 16008, 'Calle de la Luna 5', 'Antonio Lopez Sr.', 'Seguridad Social', 'TIS008', 'Dr. Gonzalez', 'Cuenca 1 (Cuenca)', 'Cuenca 1 ', 'Cuenca', 'Nacional', 'Castilla-La Mancha', 'Cuenca', 'Cuenca'),
(4, '88888884D', 1600000084, 'Sara', 'Rodriguez', 'Sanchez', 'Mujer', '1994-11-12', 934567123, 923456789, 609876321, 'Viuda', 'Profesora', 'No', 'Nacional', 'Castilla-La Mancha', 'Cuenca', 'Cuenca', 16009, 'Plaza del Ayuntamiento 1', 'Sara Rodriguez Sr.', 'Seguridad Social', 'TIS009', 'Dra. Martinez', 'Cuenca 1 (Cuenca)', 'Cuenca 1 ', 'Cuenca', 'Nacional', 'Castilla-La Mancha', 'Cuenca', 'Cuenca'),
(5, '88888885E', 1600000085, 'Pedro', 'Garcia', 'Rodriguez', 'Hombre', '1997-05-23', 945678123, NULL, 612345321, 'Casado', 'Mecánico', 'No', 'Nacional', 'Castilla-La Mancha', 'Cuenca', 'Cuenca', 16010, 'Avenida de la Paz 45', 'Pedro Garcia Sr.', 'Seguridad Social', 'TIS010', 'Dr. Fernandez', 'Cuenca 1 (Cuenca)', 'Cuenca 1', 'Cuenca', 'Nacional', 'Castilla-La Mancha', 'Cuenca', 'Cuenca'),
(6, '99999991A', 1600000091, 'Alberto', 'Sanchez', 'Ramirez', 'Hombre', '1981-02-15', 912345001, NULL, 612345001, 'Casado', 'Ingeniero', 'No', 'Nacional', 'Castilla-La Mancha', 'Cuenca', 'Cuenca', 16011, 'Calle Mayor 1', 'Alberto Sanchez Sr.', 'Seguridad Social', 'TIS011', 'Dr. Ruiz', 'Cuenca 1 (Cuenca)', 'Cuenca 1', 'Cuenca', 'Nacional', 'Castilla-La Mancha', 'Cuenca', 'Cuenca'),
(7, '99999992B', 1600000092, 'Beatriz', 'Hernandez', 'Garcia', 'Mujer', '1990-07-18', 923456001, 934567001, 622345001, 'Soltera', 'Médica', 'No', 'Nacional', 'Castilla-La Mancha', 'Cuenca', 'Cuenca', 16012, 'Avenida Central 2', 'Beatriz Hernandez Sr.', 'Seguridad Social', 'TIS012', 'Dra. Perez', 'Cuenca 1 (Cuenca)', 'Cuenca 1', 'Cuenca', 'Nacional', 'Castilla-La Mancha', 'Cuenca', 'Cuenca'),
(8, '99999993C', 1600000093, 'Carmen', 'Lopez', 'Fernandez', 'Mujer', '1985-09-20', 934567001, NULL, 632345001, 'Viuda', 'Profesora', 'No', 'Nacional', 'Castilla-La Mancha', 'Cuenca', 'Cuenca', 16013, 'Plaza de la Constitución 3', 'Carmen Lopez Sr.', 'Seguridad Social', 'TIS013', 'Dra. Gonzalez', 'Cuenca 1 (Cuenca)', 'Cuenca 1', 'Cuenca', 'Nacional', 'Castilla-La Mancha', 'Cuenca', 'Cuenca'),
(9, '99999994D', 1600000094, 'David', 'Martinez', 'Santos', 'Hombre', '1993-03-30', 945678001, 956789001, 642345001, 'Casado', 'Abogado', 'No', 'Nacional', 'Castilla-La Mancha', 'Cuenca', 'Cuenca', 16014, 'Calle del Carmen 4', 'David Martinez Sr.', 'Seguridad Social', 'TIS014', 'Dr. Fernandez', 'Cuenca 1 (Cuenca)', 'Cuenca 1', 'Cuenca', 'Nacional', 'Castilla-La Mancha', 'Cuenca', 'Cuenca'),
(10, '99999995E', 1600000095, 'Elena', 'Garcia', 'Lopez', 'Mujer', '1987-11-05', 956789001, NULL, 652345001, 'Soltera', 'Arquitecta', 'No', 'Nacional', 'Castilla-La Mancha', 'Cuenca', 'Cuenca', 16015, 'Avenida de los Pinos 5', 'Elena Garcia Sr.', 'Seguridad Social', 'TIS015', 'Dra. Martinez', 'Cuenca 1 (Cuenca)', 'Cuenca 1', 'Cuenca', 'Nacional', 'Castilla-La Mancha', 'Cuenca', 'Cuenca'),
(11, '99999996F', 1600000096, 'Juan', 'Perez', 'Gomez', 'Hombre', '1975-04-21', 912345678, NULL, 612345678, 'Casado', 'Ingeniero', 'No', 'Nacional', 'Castilla-La Mancha', 'Toledo', 'Toledo', 45001, 'Calle Mayor 1', 'Juan Perez Sr.', 'Seguridad Social', 'TIS016', 'Dr. Ruiz', 'Toledo 2-Palomarejos (Toledo)', 'Toledo 2-Palomarejos', 'Toledo', 'Nacional', 'Castilla-La Mancha', 'Toledo', 'Toledo'),
(12, '99999997G', 1600000097, 'Maria', 'Lopez', 'Hernandez', 'Mujer', '1980-08-15', 923456789, 934567890, 622345678, 'Soltera', 'Doctora', 'No', 'Nacional', 'Castilla-La Mancha', 'Toledo', 'Toledo', 45002, 'Avenida Central 2', 'Maria Lopez Sr.', 'Seguridad Social', 'TIS017', 'Dra. Perez', 'Toledo 2-Palomarejos (Toledo)', 'Toledo 2-Palomarejos', 'Toledo', 'Nacional', 'Castilla-La Mancha', 'Toledo', 'Toledo'),
(13, '99999998H', 1600000098, 'Carlos', 'Martinez', 'Rodriguez', 'Hombre', '1990-12-01', 934567891, NULL, 632345678, 'Divorciado', 'Abogado', 'No', 'Nacional', 'Castilla-La Mancha', 'Toledo', 'Toledo', 45003, 'Plaza de la Constitución 3', 'Carlos Martinez Sr.', 'Seguridad Social', 'TIS018', 'Dr. Gonzalez', 'Toledo 2-Palomarejos (Toledo)', 'Toledo 2-Palomarejos', 'Toledo', 'Nacional', 'Castilla-La Mancha', 'Toledo', 'Toledo'),
(14, '99999999I', 1600000099, 'Laura', 'Garcia', 'Fernandez', 'Mujer', '1985-05-10', 945678912, 956789012, 642345678, 'Casada', 'Arquitecta', 'No', 'Nacional', 'Castilla-La Mancha', 'Toledo', 'Toledo', 45004, 'Calle del Carmen 4', 'Laura Garcia Sr.', 'Seguridad Social', 'TIS019', 'Dra. Fernandez', 'Toledo 2-Palomarejos (Toledo)', 'Toledo 2-Palomarejos', 'Toledo', 'Nacional', 'Castilla-La Mancha', 'Toledo', 'Toledo'),
(15, '99999900J', 1600000100, 'Ana', 'Hernandez', 'Sanchez', 'Mujer', '1992-07-25', 956789123, NULL, 652345678, 'Viuda', 'Maestra', 'No', 'Nacional', 'Castilla-La Mancha', 'Toledo', 'Toledo', 45005, 'Avenida de los Pinos 5', 'Ana Hernandez Sr.', 'Seguridad Social', 'TIS020', 'Dra. Martinez', 'Toledo 2-Palomarejos (Toledo)', 'Toledo 2-Palomarejos', 'Toledo', 'Nacional', 'Castilla-La Mancha', 'Toledo', 'Toledo'),
(16, '04646995y', 1600002, 'jhoel', 'Narvaez', 'Valarezo', 'Hombre', '2000-03-11', 658998798, 0, 0, 'Soltero/a', 'CFGS', 'No', 'Nacional', 'Castilla-La Mancha', 'Cuenca', '', 16002, NULL, NULL, 'Seguridad Social', '160000000', NULL, NULL, 'Cuenca 1', '10', 'Nacional', 'Castilla-La Mancha', 'Toledo', NULL),
(17, '04646996y', 16000005, 'Jorge', 'Perez', 'Otero', 'Hombre', '2002-01-04', 645996875, 0, 0, 'Soltero/a', '', 'No', 'Nacional', 'Castilla-La Mancha', 'Toledo', '', 16002, '', '', 'Seguridad Social', 'RG100000001', 'Pedro Rojas Peña', '', 'Cuenca 1', '10', 'Nacional', 'Castilla-La Mancha', 'Cuenca', ''),
(18, '04548995y', 16000002, 'Jeronimo', 'Paladines', 'Cabrera', 'Hombre', '1996-01-04', 659888725, 0, 0, 'Soltero/a', '', 'No', 'Nacional', 'Castilla-La Mancha', 'Cuenca', '', 16001, '', '', 'Seguridad Social', '55965', '', '', 'Cuenca 1', '10', 'Nacional', 'Castilla-La Mancha', 'Toledo', ''),
(19, '04628996Q', 1610090456, 'Jennifer', 'Madrigal', 'Pérez', 'Mujer', '1992-09-09', 613681185, 0, 0, 'Casado/a', '', 'Si', 'Nacional', 'Castilla-La Mancha', 'Cuenca', 'El Picazo', 16004, 'Subida Cerro Molina nº4 3ºD', '', '', 'hola193', '', '', 'Cuenca 2', '10', 'Nacional', 'Castilla-La Mancha', 'Cuenca', ''),
(21, '25656998T', 161009045, 'Maria', 'Gomez', 'Perez', 'Hombre', '2000-03-11', 265448935, 0, 0, 'Soltero/a', '', 'Si', 'Nacional', '5', 'Cuenca', '', 16002, '', '', 'Seguridad Social', 'R00001', '', '', 'Cuenca 1', '10', 'Nacional', '5', 'Cuenca', ''),
(22, '04718996N', 167008145666, 'Lucinio', 'Bautista', 'Navarro', 'Hombre', '1964-02-22', 617845297, 0, 0, 'Casado/a', '', 'No', 'Nacional', 'Castilla-La Mancha', 'Cuenca', 'Motilla del palancar', 16200, 'calle valencia', '', 'Seguridad Social', 'jajas12', '', '', 'Cuenca 1', '10', 'Nacional', 'Castilla-La Mancha', 'Cuenca', ''),
(23, '04646558l', 1600001, 'Juan', 'Lopez', 'Perez', 'Hombre', '2000-03-11', 365889565, 0, 0, 'Soltero/a', '', 'Si', 'Nacional', 'Castilla-La Mancha', 'Toledo', '', 16002, '', '', 'Seguridad Social', '26552', '', '', 'Añover de Tajo', '13', 'Nacional', 'Castilla-La Mancha', 'Toledo', ''),
(24, '06218996N', 161478045666, 'Romana', 'Martinez', 'Gonzalez', 'Mujer', '1932-03-22', 698745817, NULL, NULL, 'Casado/a', NULL, 'No', 'Nacional', 'Castilla-La Mancha', 'Cuenca', 'Cuenca', 16004, 'Calle tintes', NULL, NULL, 'gfg44', NULL, NULL, 'Cuenca 1', '10', 'Nacional', 'Castilla-La Mancha', 'Cuenca', NULL),
(25, '4337572T', 222222222222222222, 'paco', 'pquez', '(RENAVE, 2024)aquin', 'Hombre', '2024-06-24', 364672931, 0, 0, 'Soltero/a', '', 'No', 'Nacional', 'Andalucía', 'Granada', '', 44999, '', '', '', '222222222222222', '', '', 'Balazote', '1', 'Nacional', 'Cantabria', 'Cantabria', ''),
(26, '22222222T', 2, 'a', 's', 'p', 'Hombre', '2024-06-10', 222222222, 0, 0, 'Casado/a', '', 'No', 'Nacional', 'Aragón', 'Huesca', '', 22222, '', '', '', '2', '', '', 'Alcadozo', '1', 'Nacional', 'Andalucía', 'Almería', ''),
(27, '0466558t', 16000215, 'Alberto', 'perez', 'Gómez', 'Hombre', '2000-11-03', 254998625, 0, 0, 'Soltero/a', '', 'No', 'Nacional', 'Castilla-La Mancha', 'Toledo', '', 45150, '', '', 'Mutuas Laborales', '1564', '', 'Cuenca 1 (Cuenca)', 'Cuenca 1', '10', 'Nacional', 'Castilla-La Mancha', 'Toledo', 'Cuenca');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `patologia`
--

CREATE TABLE IF NOT EXISTS   `patologia` (
  `id_patologia` int(11) NOT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_diagnostico` date DEFAULT NULL,
  `sintomas` varchar(255) DEFAULT NULL,
  `diagnostico` varchar(255) DEFAULT NULL,
  `especialidad` varchar(50) DEFAULT NULL,
  `codificacion` varchar(50) DEFAULT NULL,
  `nhc` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `patologia`
--

INSERT INTO `patologia` (`id_patologia`, `fecha_inicio`, `fecha_diagnostico`, `sintomas`, `diagnostico`, `especialidad`, `codificacion`, `nhc`) VALUES
(1, '2023-01-01', '2023-01-10', 'Dolor de cabeza, mareos', 'Migraña crónica', 'Neurología', 'G43', 1),
(2, '2023-02-01', '2023-02-10', 'Dificultad para respirar, tos', 'Asma', 'Neumología', 'J45', 2),
(3, '2023-03-01', '2023-03-10', 'Dolor en el pecho, fatiga', 'Enfermedad coronaria', 'Cardiología', 'I25', 3),
(4, '2023-04-01', '2023-04-10', 'Dolor abdominal, diarrea', 'Gastroenteritis', 'Gastroenterología', 'K52', 4),
(5, '2023-05-01', '2023-05-10', 'Dolor articular, inflamación', 'Artritis reumatoide', 'Reumatología', 'M06', 5),
(6, '2023-06-01', '2023-06-10', 'Pérdida de memoria, confusión', 'Demencia', 'Neurología', 'F03', 6),
(7, '2023-07-01', '2023-07-10', 'Erupción cutánea, picazón', 'Dermatitis atópica', 'Dermatología', 'L20', 7),
(8, '2023-08-01', '2023-08-10', 'Náuseas, vómitos', 'Gastritis', 'Gastroenterología', 'K29', 8),
(9, '2023-09-01', '2023-09-10', 'Dolor lumbar, rigidez', 'Lumbalgia', 'Reumatología', 'M54', 9),
(10, '2023-10-01', '2023-10-10', 'Ansiedad, insomnio', 'Trastorno de ansiedad', 'Psiquiatría', 'F41', 10),
(11, '2023-11-01', '2023-11-10', 'Fiebre, tos, dolor de garganta', 'Gripe', 'Medicina General', 'J10', 10),
(12, '2023-12-01', '2023-12-10', 'Dolor de espalda, debilidad en las piernas', 'Hernia discal', 'Neurocirugía', 'M51', 10),
(13, '2023-01-01', '2023-01-10', 'Dolor de cabeza, mareos', 'Migraña crónica', 'Neurología', 'G43', 1),
(14, '2022-05-15', '2022-05-20', 'Dificultad para respirar, fatiga', 'Asma', 'Neumología', 'J45', 1),
(15, '2021-11-10', '2021-11-15', 'Dolor en el pecho, presión alta', 'Hipertensión', 'Cardiología', 'I10', 1),
(16, '2020-08-05', '2020-08-10', 'Dolor abdominal, náuseas', 'Gastritis', 'Gastroenterología', 'K29', 1),
(17, '2019-03-22', '2019-03-25', 'Dolor en las articulaciones, hinchazón', 'Artritis reumatoide', 'Reumatología', 'M05', 1),
(18, '2022-02-10', '2022-02-15', 'Pérdida de peso, sed excesiva', 'Diabetes tipo 2', 'Endocrinología', 'E11', 1),
(19, '2023-04-01', '2023-04-05', 'Fiebre, tos persistente', 'Bronquitis aguda', 'Neumología', 'J20', 1),
(20, '2021-07-20', '2021-07-25', 'Erupción cutánea, picazón', 'Dermatitis atópica', 'Dermatología', 'L20', 1),
(21, '2019-12-01', '2019-12-05', 'Dolor lumbar, rigidez', 'Lumbalgia', 'Traumatología', 'M54', 1),
(22, '2020-06-10', '2020-06-15', 'Visión borrosa, dolor ocular', 'Glaucoma', 'Oftalmología', 'H40', 1),
(23, '2024-02-15', '2024-02-20', 'Fatiga, dolor muscular', 'Síndrome de fatiga crónica', 'Reumatología', 'R53', 1),
(24, '2024-06-05', '2024-06-07', 'Dolor', 'Enfermo', 'Medicina', 'Sí', 5),
(25, '2023-02-04', '2024-06-07', 'Dolor en el pecho', 'Asma', 'Medicina', 'Sí', 3),
(26, '2023-04-05', '2024-06-08', 'Dolor en la pierna derecha', 'Rotura', 'Cirugía', 'Sí', 3),
(27, '2023-04-05', '2024-06-09', 'Nervios', 'Ansiedad severa', 'Medicina', 'Sí', 1),
(28, '2024-06-09', '2024-06-09', 'dolor estomacal', 'gatroenteritis', 'Enfermería', 'Sí', 19),
(29, '2023-06-05', '2024-06-10', 'lll', 'lll', 'Medicina', 'Sí', 4),
(30, '2023-04-05', '2024-06-10', 'Dolor', 'Gastronteritis', 'Medicina', 'Sí', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `provincia`
--

CREATE TABLE IF NOT EXISTS   `provincia` (
  `id_provincia` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `id_cautonoma` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `provincia`
--

INSERT INTO `provincia` (`id_provincia`, `nombre`, `id_cautonoma`) VALUES
(1, 'Almería', 1),
(2, 'Cádiz', 1),
(3, 'Córdoba', 1),
(4, 'Granada', 1),
(5, 'Huelva', 1),
(6, 'Jaén', 1),
(7, 'Málaga', 1),
(8, 'Sevilla', 1),
(9, 'Huesca', 2),
(10, 'Teruel', 2),
(11, 'Zaragoza', 2),
(12, 'Asturias', 3),
(13, 'Cantabria', 4),
(14, 'Albacete', 5),
(15, 'Ciudad Real', 5),
(16, 'Cuenca', 5),
(17, 'Guadalajara', 5),
(18, 'Toledo', 5),
(19, 'Ávila', 6),
(20, 'Burgos', 6),
(21, 'León', 6),
(22, 'Palencia', 6),
(23, 'Salamanca', 6),
(24, 'Segovia', 6),
(25, 'Soria', 6),
(26, 'Valladolid', 6),
(27, 'Zamora', 6),
(28, 'Barcelona', 7),
(29, 'Girona', 7),
(30, 'Lleida', 7),
(31, 'Tarragona', 7),
(32, 'Ceuta', 8),
(33, 'Badajoz', 9),
(34, 'Cáceres', 9),
(35, 'A Coruña', 10),
(36, 'Lugo', 10),
(37, 'Ourense', 10),
(38, 'Pontevedra', 10),
(39, 'Illes Balears', 11),
(40, 'Las Palmas', 12),
(41, 'Santa Cruz de Tenerife', 12),
(42, 'La Rioja', 13),
(43, 'Madrid', 14),
(44, 'Melilla', 15),
(45, 'Murcia', 16),
(46, 'Navarra', 17),
(47, 'Alicante', 18),
(48, 'Castellón', 18),
(49, 'Valencia', 18),
(50, 'Álava', 19),
(51, 'Gipuzkoa', 19),
(52, 'Bizkaia', 19);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recuperarContrasena`
--

CREATE TABLE IF NOT EXISTS   `recuperarContrasena` (
  `id_usuario` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expiracion` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `recuperarContrasena`
--

INSERT INTO `recuperarContrasena` (`id_usuario`, `token`, `expiracion`) VALUES
(1, 'PYqJQQQM', '2024-06-10 13:18:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE IF NOT EXISTS   `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre` varchar(300) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre`) VALUES
(1, 'Profesor'),
(2, 'TCAE'),
(3, 'Administrativo'),
(4, 'Auxiliar de Farmacia y Parafarmacia'),
(5, 'Técnico en Imagen para el Diagnóstico'),
(6, 'Técnico en Laboratorio Clínico y Biomédico'),
(7, 'Administrador');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles_ambitos`
--

CREATE TABLE IF NOT EXISTS   `roles_ambitos` (
  `ID_ROL` int(11) NOT NULL,
  `ID_AMBITO` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles_ambitos`
--

INSERT INTO `roles_ambitos` (`ID_ROL`, `ID_AMBITO`) VALUES
(1, 1),
(2, 1),
(3, 1),
(4, 1),
(5, 1),
(6, 1),
(1, 2),
(2, 2),
(3, 2),
(4, 2),
(5, 2),
(6, 2),
(1, 3),
(2, 3),
(3, 3),
(4, 3),
(5, 3),
(6, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS   `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `userName` varchar(100) NOT NULL,
  `apellidos` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `id_rol` int(11) NOT NULL,
  `password` varchar(255) NOT NULL,
  `id_centro` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre`, `userName`, `apellidos`, `email`, `id_rol`, `password`, `id_centro`) VALUES
(1, 'Admin', 'admin', 'Admin', 'sesamosytes@gmail.com', 7, '$2y$10$k3RZ4rMhxv.I5l5cXGLJYOg/ygQ3TdxTvriclAcUrjv5XCvPqE5Jm', 10),
(2, 'Usuario', 'usuario', 'Usuario', 'jhax311@gmail.com', 3, '$2y$10$GM6eTM1NmlitsrIO.6skQuqeKoBf9DPDDd.BYoOBTadO6IJ3Ce1m2', 10),
(3, 'user', 'user1', 'user', 'user1@gmail.com', 3, '$2y$10$r4lPoyXH.9ogiVR3kpQ45.cK4W/eJKIATIZNWMkbRVC8rmS8Y1v8.', 10),
(4, 'user4', 'user4', 'user 4', 'user4@gmail.com', 3, '$2y$10$sb17K4tnEvpIRXuJ0TeLC.XeZ/F/Tk/kbw/9.pPe5beqaxsmbXs9a', 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_token`
--

CREATE TABLE IF NOT EXISTS   `usuarios_token` (
  `TokenId` int(11) NOT NULL,
  `UsuarioId` int(11) DEFAULT NULL,
  `Token` varchar(45) DEFAULT NULL,
  `Estado` varchar(45) DEFAULT NULL,
  `Fecha` datetime DEFAULT NULL,
  `id_rol` int(2) NOT NULL DEFAULT 3
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios_token`
--

INSERT INTO `usuarios_token` (`TokenId`, `UsuarioId`, `Token`, `Estado`, `Fecha`, `id_rol`) VALUES
(1, 2, '2d641a31267d5dca690f0f5588115908', '1', '2024-06-11 16:42:00', 3),
(2, 1, '34620b313b99dae3bfa449f2d21e9a75', '1', '2024-06-11 16:47:00', 7);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `visitas`
--

CREATE TABLE IF NOT EXISTS   `visitas` (
  `id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `pagina` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `visitas`
--

INSERT INTO `visitas` (`id`, `fecha`, `pagina`) VALUES
(70, '2024-05-10', 'alertas'),
(71, '2024-05-11', 'alertas'),
(73, '2024-06-09', 'alertas'),
(240, '2024-06-10', 'camas'),
(338, '2024-06-11', 'centros'),
(339, '2024-06-11', 'centros'),
(340, '2024-06-11', 'roles'),
(341, '2024-06-11', 'centros'),
(342, '2024-06-11', 'roles'),
(343, '2024-06-11', 'roles'),
(344, '2024-06-11', 'centros'),
(345, '2024-06-11', 'roles'),
(346, '2024-06-11', 'centros'),
(347, '2024-06-11', 'centros'),
(348, '2024-06-11', 'roles'),
(349, '2024-06-11', 'roles'),
(350, '2024-06-11', 'centros'),
(351, '2024-06-11', 'roles'),
(352, '2024-06-11', 'centros'),
(353, '2024-06-11', 'centros'),
(354, '2024-06-11', 'roles'),
(355, '2024-06-11', 'roles'),
(356, '2024-06-11', 'centros'),
(357, '2024-06-11', 'roles'),
(358, '2024-06-11', 'centros'),
(359, '2024-06-11', 'roles'),
(360, '2024-06-11', 'centros'),
(361, '2024-06-11', 'roles'),
(362, '2024-06-11', 'centros'),
(363, '2024-06-11', 'roles'),
(364, '2024-06-11', 'centros'),
(365, '2024-06-11', 'roles'),
(366, '2024-06-11', 'centros'),
(367, '2024-06-11', 'roles'),
(368, '2024-06-11', 'centros'),
(369, '2024-06-11', 'roles'),
(370, '2024-06-11', 'centros'),
(371, '2024-06-11', 'centros'),
(372, '2024-06-11', 'roles'),
(373, '2024-06-11', 'centros'),
(374, '2024-06-11', 'roles'),
(375, '2024-06-11', 'centros'),
(376, '2024-06-11', 'roles'),
(377, '2024-06-11', 'roles'),
(378, '2024-06-11', 'centros'),
(379, '2024-06-11', 'roles'),
(380, '2024-06-11', 'centros'),
(381, '2024-06-11', 'roles'),
(382, '2024-06-11', 'centros'),
(383, '2024-06-11', 'roles'),
(384, '2024-06-11', 'centros');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `zonaBasicaSalud`
--

CREATE TABLE IF NOT EXISTS   `zonaBasicaSalud` (
  `id_zona_basica_salud` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `cap` varchar(100) DEFAULT NULL,
  `id_area_salud` int(11) DEFAULT NULL,
  `id_ambito` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `zonaBasicaSalud`
--

INSERT INTO `zonaBasicaSalud` (`id_zona_basica_salud`, `nombre`, `cap`, `id_area_salud`, `id_ambito`) VALUES
(1, 'Alcadozo', 'Alcadozo (Albacete)', 1, NULL),
(2, 'Alcaraz', 'Alcaraz (Albacete)', 1, NULL),
(3, 'Balazote', 'Balazote (Albacete)', 1, NULL),
(4, 'Bogarra', 'Bogarra (Albacete)', 1, NULL),
(5, 'Casas de Juan Núñez', 'Casas de Juan Núñez (Albacete)', 1, NULL),
(6, 'Casas Ibáñez', 'Casas Ibáñez (Albacete)', 1, NULL),
(7, 'Casasimarro', 'Casasimarro (Cuenca)', 1, NULL),
(8, 'Chinchilla de Montearagón', 'Chinchilla de Montearagón (Albacete)', 1, NULL),
(9, 'Iniesta', 'Iniesta (Cuenca)', 1, NULL),
(10, 'La Roda', 'La Roda (Albacete)', 1, NULL),
(11, 'Madrigueras', 'Madrigueras (Albacete)', 1, NULL),
(12, 'Quintanar del Rey', 'Quintanar del Rey (Cuenca)', 1, NULL),
(13, 'Tarazona de la Mancha', 'Tarazona de la Mancha (Albacete)', 1, NULL),
(14, 'Villamalea', 'Villamalea (Albacete)', 1, NULL),
(15, 'Zona 1-Hospital', 'Zona 1-Hospital (Albacete)', 1, NULL),
(16, 'Zona 2-Municipal', 'Zona 2-Municipal (Albacete)', 1, NULL),
(17, 'Zona 3-Villacerrada', 'Zona 3-Villacerrada (Albacete)', 1, NULL),
(18, 'Zona 4-Residencia', 'Zona 4-Residencia (Albacete)', 1, NULL),
(19, 'Zona 5', 'Zona 5 (Albacete)', 1, NULL),
(20, 'Zona V-B', 'Zona V-B (Albacete)', 1, NULL),
(21, 'Zona 6', 'Zona 6 (Albacete)', 1, NULL),
(22, 'Zona 7-Feria', 'Zona 7-Feria (Albacete)', 1, NULL),
(23, 'Zona 8', 'Zona 8 (Albacete)', 1, NULL),
(24, 'Almansa', 'Almansa (Albacete)', 2, NULL),
(25, 'Bonete', 'Bonete (Albacete)', 2, NULL),
(26, 'Caudete', 'Caudete (Albacete)', 2, NULL),
(27, 'Elche de la Sierra', 'Elche de la Sierra (Albacete)', 3, NULL),
(28, 'Hellín 1', 'Hellín 1 (Albacete)', 3, NULL),
(29, 'Hellín 2', 'Hellín 2 (Albacete)', 3, NULL),
(30, 'Nerpio', 'Nerpio (Albacete)', 3, NULL),
(31, 'Ontur', 'Ontur (Albacete)', 3, NULL),
(32, 'Riopar', 'Riopar (Albacete)', 3, NULL),
(33, 'Socovos', 'Socovos (Albacete)', 3, NULL),
(34, 'Tobarra', 'Tobarra (Albacete)', 3, NULL),
(35, 'Yeste', 'Yeste (Albacete)', 3, NULL),
(36, 'El Bonillo', 'El Bonillo (Albacete)', 4, NULL),
(37, 'Ossa de Montiel', 'Ossa de Montiel (Albacete)', 4, NULL),
(38, 'Villarrobledo', 'Villarrobledo (Albacete)', 4, NULL),
(39, 'Munera', 'Munera (Albacete)', 4, NULL),
(40, 'Las Pedroñeras', ' ', 4, NULL),
(41, 'San Clemente', ' ', 4, NULL),
(42, 'Sisante', ' ', 4, NULL),
(43, 'Alcázar de San Juan 1', 'Alcázar de San Juan 1 (Ciudad Real)', 5, NULL),
(44, 'Alcázar de San Juan 2', 'Alcázar de San Juan 2 (Ciudad Real)', 5, NULL),
(45, 'Campo de Criptana', 'Campo de Criptana (Ciudad Real)', 5, NULL),
(46, 'Herencia', 'Herencia (Ciudad Real)', 5, NULL),
(47, 'Madridejos', 'Madridejos (Ciudad Real)', 5, NULL),
(48, 'Mota del Cuervo', 'Mota del Cuervo (Ciudad Real)', 5, NULL),
(49, 'Quintanar de la Orden', 'Quintanar de la Orden (Ciudad Real)', 5, NULL),
(50, 'Villacañas', 'Villacañas (Ciudad Real)', 5, NULL),
(51, 'Villafranca de los Caballeros', 'Villafranca de los Caballeros (Ciudad Real)', 5, NULL),
(52, 'Villarta de San Juan', 'Villarta de San Juan (Ciudad Real)', 5, NULL),
(53, 'Argamasilla de Alba', 'Argamasilla de Alba (Ciudad Real)', 6, NULL),
(54, 'Pedro Muñoz', 'Pedro Muñoz (Ciudad Real)', 6, NULL),
(55, 'Socuéllamos', 'Socuéllamos (Ciudad Real)', 6, NULL),
(56, 'Tomelloso 1', 'Tomelloso 1 (Ciudad Real)', 6, NULL),
(57, 'Tomelloso 2', 'Tomelloso 2 (Ciudad Real)', 6, NULL),
(58, 'Abenójar', 'Abenójar (Ciudad Real)', 7, NULL),
(59, 'Agudo', 'Agudo (Ciudad Real)', 7, NULL),
(60, 'Alcoba de los Montes', 'Alcoba de los Montes (Ciudad Real)', 7, NULL),
(61, 'Almagro', 'Almagro (Ciudad Real)', 7, NULL),
(62, 'Bolaños', 'Bolaños (Ciudad Real)', 7, NULL),
(63, 'Calzada de Calatrava', 'Calzada de Calatrava (Ciudad Real)', 7, NULL),
(64, 'Carrión de Calatrava', 'Carrión de Calatrava (Ciudad Real)', 7, NULL),
(65, 'Ciudad Real 1', 'Ciudad Real 1 (Ciudad Real)', 7, NULL),
(66, 'Ciudad Real 2', 'Ciudad Real 2 (Ciudad Real)', 7, NULL),
(67, 'Ciudad Real 3', 'Ciudad Real 3 (Ciudad Real)', 7, NULL),
(68, 'Corral de Calatrava', 'Corral de Calatrava (Ciudad Real)', 7, NULL),
(69, 'Daimiel 1', 'Daimiel 1 (Ciudad Real)', 7, NULL),
(70, 'Daimiel II Cedt', 'Daimiel II Cedt (Ciudad Real)', 7, NULL),
(71, 'Malagón', 'Malagón (Ciudad Real)', 7, NULL),
(72, 'Miguelturra', 'Miguelturra (Ciudad Real)', 7, NULL),
(73, 'Piedrabuena', 'Piedrabuena (Ciudad Real)', 7, NULL),
(74, 'Porzuna', 'Porzuna (Ciudad Real)', 7, NULL),
(75, 'Retuerta del Bullaque', 'Retuerta del Bullaque (Ciudad Real)', 7, NULL),
(76, 'Villarrubia de los Ojos', 'Villarrubia de los Ojos (Ciudad Real)', 7, NULL),
(77, 'La Solana', 'La Solana (Ciudad Real)', 8, NULL),
(78, 'Manzanares I', 'Manzanares I (Ciudad Real)', 8, NULL),
(79, 'Manzanares II', 'Manzanares II (Ciudad Real)', 8, NULL),
(80, 'Albaladejo', 'Albaladejo (Ciudad Real)', 9, NULL),
(81, 'Moral de Calatrava', 'Moral de Calatrava (Ciudad Real)', 9, NULL),
(82, 'Santa Cruz de Mudela', 'Santa Cruz de Mudela (Ciudad Real)', 9, NULL),
(83, 'Torre de Juan Abad', 'Torre de Juan Abad (Ciudad Real)', 9, NULL),
(84, 'Valdepeñas I', 'Valdepeñas I (Ciudad Real)', 9, NULL),
(85, 'Valdepeñas II', 'Valdepeñas II (Ciudad Real)', 9, NULL),
(86, 'Villahermosa', 'Villahermosa (Ciudad Real)', 9, NULL),
(87, 'Villanueva de los Infantes', 'Villanueva de los Infantes (Ciudad Real)', 9, NULL),
(88, 'Belmonte', 'Belmonte (Cuenca)', 10, NULL),
(89, 'Beteta', 'Beteta (Cuenca)', 10, NULL),
(90, 'Campillo de Altobuey', 'Campillo de Altobuey (Cuenca)', 10, NULL),
(91, 'Cañaveras', 'Cañaveras (Cuenca)', 10, NULL),
(92, 'Cañete', 'Cañete (Cuenca)', 10, NULL),
(93, 'Carboneras de Guadazaón', 'Carboneras de Guadazaón (Cuenca)', 10, NULL),
(94, 'Cardenete', 'Cardenete (Cuenca)', 10, NULL),
(95, 'Carrascosa del Campo', 'Carrascosa del Campo (Cuenca)', 10, NULL),
(96, 'Cuenca 1', 'Cuenca 1 (Cuenca)', 10, NULL),
(97, 'Cuenca 2', 'Cuenca 2 (Cuenca)', 10, NULL),
(98, 'Cuenca 3', 'Cuenca 3 (Cuenca)', 10, NULL),
(99, 'Honrubia', 'Honrubia (Cuenca)', 10, NULL),
(100, 'Horcajo de Santiago', 'Horcajo de Santiago (Cuenca)', 10, NULL),
(101, 'Huete', 'Huete (Cuenca)', 10, NULL),
(102, 'Landete', 'Landete (Cuenca)', 10, NULL),
(103, 'Minglanilla', 'Minglanilla (Cuenca)', 10, NULL),
(104, 'Mira', 'Mira (Cuenca)', 10, NULL),
(105, 'Montalbo', 'Montalbo (Cuenca)', 10, NULL),
(106, 'Motilla del Palancar', 'Motilla del Palancar (Cuenca)', 10, NULL),
(107, 'Priego', 'Priego (Cuenca)', 10, NULL),
(108, 'San Lorenzo de la Parrilla', 'San Lorenzo de la Parrilla (Cuenca)', 10, NULL),
(109, 'Talayuelas', 'Talayuelas (Cuenca)', 10, NULL),
(110, 'Tarancón', 'Tarancón (Cuenca)', 10, NULL),
(111, 'Torrejoncillo del Rey', 'Torrejoncillo del Rey (Cuenca)', 10, NULL),
(112, 'Tragacete', 'Tragacete (Cuenca)', 10, NULL),
(113, 'Valverde del Júcar', 'Valverde del Júcar (Cuenca)', 10, NULL),
(114, 'Villalba de la Sierra', 'Villalba de la Sierra (Cuenca)', 10, NULL),
(115, 'Villalba del Rey', 'Villalba del Rey (Cuenca)', 10, NULL),
(116, 'Villamayor de Santiago', 'Villamayor de Santiago (Cuenca)', 10, NULL),
(117, 'Villares del Saz', 'Villares del Saz (Cuenca)', 10, NULL),
(118, 'Villas de la Ventosa', 'Villas de la Ventosa (Cuenca)', 10, NULL),
(119, 'Alcolea del Pinar', 'Alcolea del Pinar (Guadalajara)', 11, NULL),
(120, 'Atienza', 'Atienza (Guadalajara)', 11, NULL),
(121, 'Azuqueca de Henares', 'Azuqueca de Henares (Guadalajara)', 11, NULL),
(122, 'Brihuega', 'Brihuega (Guadalajara)', 11, NULL),
(123, 'Cabanillas del Campo', 'Cabanillas del Campo (Guadalajara)', 11, NULL),
(124, 'Campiña', 'Campiña (Guadalajara)', 11, NULL),
(125, 'Checa zona especial', 'Checa zona especial (Guadalajara)', 11, NULL),
(126, 'Cifuentes', 'Cifuentes (Guadalajara)', 11, NULL),
(127, 'Cogolludo', 'Cogolludo (Guadalajara)', 11, NULL),
(128, 'El Casar de Talamanca', 'El Casar de Talamanca (Guadalajara)', 11, NULL),
(129, 'El Pobo de Dueñas', 'El Pobo de Dueñas (Guadalajara)', 11, NULL),
(130, 'Galve de Sorbe', 'Galve de Sorbe (Guadalajara)', 11, NULL),
(131, 'Guadalajara 1-Sur', 'Guadalajara 1-Sur (Guadalajara)', 11, NULL),
(132, 'Guadalajara 2-Balconcill', 'Guadalajara 2-Balconcill (Guadalajara)', 11, NULL),
(133, 'Guadalajara 3-Alamin', 'Guadalajara 3-Alamin (Guadalajara)', 11, NULL),
(134, 'Guadalajara 4-Cervantes', 'Guadalajara 4-Cervantes (Guadalajara)', 11, NULL),
(135, 'Guadalajara 5-Manantiales', 'Guadalajara 5-Manantiales (Guadalajara)', 11, NULL),
(136, 'Guadalajara-Periférica', 'Guadalajara-Periférica (Guadalajara)', 11, NULL),
(137, 'Hiendelaencina', 'Hiendelaencina (Guadalajara)', 11, NULL),
(138, 'Horche', 'Horche (Guadalajara)', 11, NULL),
(139, 'Jadraque', 'Jadraque (Guadalajara)', 11, NULL),
(140, 'Maranchón', 'Maranchón (Guadalajara)', 11, NULL),
(141, 'Molina de Aragón', 'Molina de Aragón (Guadalajara)', 11, NULL),
(142, 'Mondéjar', 'Mondéjar (Guadalajara)', 11, NULL),
(143, 'Pastrana', 'Pastrana (Guadalajara)', 11, NULL),
(144, 'Sacedón', 'Sacedón (Guadalajara)', 11, NULL),
(145, 'Sigüenza', 'Sigüenza (Guadalajara)', 11, NULL),
(146, 'Villanueva de Alcorón', 'Villanueva de Alcorón (Guadalajara)', 11, NULL),
(147, 'Yunquera de Henares', 'Yunquera de Henares (Guadalajara)', 11, NULL),
(148, 'Aldeanueva de San Bartolomé', 'Aldeanueva de San Bartolomé (Toledo)', 12, NULL),
(149, 'Belvis de la Jara', 'Belvis de la Jara (Toledo)', 12, NULL),
(150, 'Cebolla', 'Cebolla (Toledo)', 12, NULL),
(151, 'La Nava de Ricomalillo', 'La Nava de Ricomalillo (Toledo)', 12, NULL),
(152, 'La Pueblanueva', 'La Pueblanueva (Toledo)', 12, NULL),
(153, 'Los Navalmorales', 'Los Navalmorales (Toledo)', 12, NULL),
(154, 'Navamorcuende', 'Navamorcuende (Toledo)', 12, NULL),
(155, 'Oropesa', 'Oropesa (Toledo)', 12, NULL),
(156, 'Puente del Arzobispo', 'Puente del Arzobispo (Toledo)', 12, NULL),
(157, 'Santa Olalla', 'Santa Olalla (Toledo)', 12, NULL),
(158, 'Sierra de San Vicente', 'Sierra de San Vicente (Toledo)', 12, NULL),
(159, 'Talavera 1 Centro', 'Talavera 1 Centro (Toledo)', 12, NULL),
(160, 'Talavera 2 Estación', 'Talavera 2 Estación (Toledo)', 12, NULL),
(161, 'Talavera 3 La Solana', 'Talavera 3 La Solana (Toledo)', 12, NULL),
(162, 'Talavera 4 La Algodonera', 'Talavera 4 La Algodonera (Toledo)', 12, NULL),
(163, 'Talavera 5 Río Tajo', 'Talavera 5 Río Tajo (Toledo)', 12, NULL),
(164, 'Velada', 'Velada (Toledo)', 12, NULL),
(165, 'Añover de Tajo', 'Añover de Tajo (Toledo)', 13, NULL),
(166, 'Bargas', 'Bargas (Toledo)', 13, NULL),
(167, 'Camarena', 'Camarena (Toledo)', 13, NULL),
(168, 'Consuegra', 'Consuegra (Toledo)', 13, NULL),
(169, 'Corral de Almaguer', 'Corral de Almaguer (Toledo)', 13, NULL),
(170, 'Escalona', 'Escalona (Toledo)', 13, NULL),
(171, 'Esquivias', 'Esquivias (Toledo)', 13, NULL),
(172, 'Fuensalida', 'Fuensalida (Toledo)', 13, NULL),
(173, 'Illescas', 'Illescas (Toledo)', 13, NULL),
(174, 'La Puebla de Montalbán', 'La Puebla de Montalbán (Toledo)', 13, NULL),
(175, 'Los Yébenes', 'Los Yébenes (Toledo)', 13, NULL),
(176, 'Menasalbas', 'Menasalbas (Toledo)', 13, NULL),
(177, 'Mora', 'Mora (Toledo)', 13, NULL),
(178, 'Navahermosa Noblejas', 'Navahermosa Noblejas (Toledo)', 13, NULL),
(179, 'Ocaña', 'Ocaña (Toledo)', 13, NULL),
(180, 'Polán', 'Polán (Toledo)', 13, NULL),
(181, 'Santa Cruz de la Zarza', 'Santa Cruz de la Zarza (Toledo)', 13, NULL),
(182, 'Seseña', 'Seseña (Toledo)', 13, NULL),
(183, 'Sonseca', 'Sonseca (Toledo)', 13, NULL),
(184, 'Tembleque', 'Tembleque (Toledo)', 13, NULL),
(185, 'Toledo 1-Sillería', 'Toledo 1-Sillería (Toledo)', 13, NULL),
(186, 'Toledo 2-Palomarejos', 'Toledo 2-Palomarejos (Toledo)', 13, NULL),
(187, 'Toledo 3-Benquerencia', 'Toledo 3-Benquerencia (Toledo)', 13, NULL),
(188, 'Toledo 4-Santa Bárbara', 'Toledo 4-Santa Bárbara (Toledo)', 13, NULL),
(189, 'Toledo 5-Buenavista', 'Toledo 5-Buenavista (Toledo)', 13, NULL),
(190, 'Torrijos', 'Torrijos (Toledo)', 13, NULL),
(191, 'Valmojado', 'Valmojado (Toledo)', 13, NULL),
(192, 'Villaluenga de la Sagra', 'Villaluenga de la Sagra (Toledo)', 13, NULL),
(193, 'Yepes', 'Yepes (Toledo)', 13, NULL),
(194, 'Almadén', 'Almadén (Ciudad Real)', 14, NULL),
(195, 'Argamasilla de Calatrava', 'Argamasilla de Calatrava (Ciudad Real)', 14, NULL),
(196, 'Almodóvar del Campo', 'Almodóvar del Campo (Ciudad Real)', 14, NULL),
(197, 'Fuencaliente', 'Fuencaliente (Ciudad Real)', 14, NULL),
(198, 'Puertollano 1 – Barataria', 'Puertollano 1 – Barataria (Ciudad Real)', 14, NULL),
(199, 'Puertollano 2', 'Puertollano 2 (Ciudad Real)', 14, NULL),
(200, 'Puertollano 3 – Carlos Mestre', 'Puertollano 3 – Carlos Mestre (Ciudad Real)', 14, NULL),
(201, 'Puertollano 4', 'Puertollano 4 (Ciudad Real)', 14, NULL),
(202, 'Solana del Pino', 'Solana del Pino (Ciudad Real)', 14, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alertas`
--
ALTER TABLE `alertas`
  ADD PRIMARY KEY (`codigo`),
  ADD KEY `alertas_ibfk_1` (`nhc`);

--
-- Indices de la tabla `alta`
--
ALTER TABLE `alta`
  ADD PRIMARY KEY (`id_alta`),
  ADD KEY `altas_ibfk_1` (`nhc`);

--
-- Indices de la tabla `ambitos`
--
ALTER TABLE `ambitos`
  ADD PRIMARY KEY (`id_ambito`);

--
-- Indices de la tabla `areaSalud`
--
ALTER TABLE `areaSalud`
  ADD PRIMARY KEY (`id_area_salud`);

--
-- Indices de la tabla `camas`
--
ALTER TABLE `camas`
  ADD PRIMARY KEY (`id_cama`),
  ADD KEY `camas_ibfk_1` (`id_centro`);

--
-- Indices de la tabla `centros`
--
ALTER TABLE `centros`
  ADD PRIMARY KEY (`id_centro`),
  ADD KEY `centros_ibfk_1` (`id_area_salud`);

--
-- Indices de la tabla `comunidadautonoma`
--
ALTER TABLE `comunidadautonoma`
  ADD PRIMARY KEY (`id_cautonoma`);

--
-- Indices de la tabla `hoja_prescripcion`
--
ALTER TABLE `hoja_prescripcion`
  ADD PRIMARY KEY (`id_hoja`),
  ADD KEY `patologia_ibfk_1` (`nhc`);

--
-- Indices de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  ADD PRIMARY KEY (`id_ingreso`),
  ADD KEY `ingresos_ibfk_1` (`id_cama`),
  ADD KEY `ingresos_ibfk_2` (`nhc`);

--
-- Indices de la tabla `notas_enfermeria`
--
ALTER TABLE `notas_enfermeria`
  ADD PRIMARY KEY (`id_notas`),
  ADD KEY `notas_enfermeria_ibfk_1` (`nhc`);

--
-- Indices de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`nhc`),
  ADD UNIQUE KEY `NumeroSS` (`numeroSS`) USING BTREE,
  ADD UNIQUE KEY `tis` (`tis`) USING BTREE;

--
-- Indices de la tabla `patologia`
--
ALTER TABLE `patologia`
  ADD PRIMARY KEY (`id_patologia`),
  ADD KEY `fk_patologia_nhc` (`nhc`);

--
-- Indices de la tabla `provincia`
--
ALTER TABLE `provincia`
  ADD PRIMARY KEY (`id_provincia`),
  ADD KEY `provincia_ibfk_1` (`id_cautonoma`);

--
-- Indices de la tabla `recuperarContrasena`
--
ALTER TABLE `recuperarContrasena`
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `roles_ambitos`
--
ALTER TABLE `roles_ambitos`
  ADD KEY `roles_ambitos_ibfk_1` (`ID_ROL`),
  ADD KEY `roles_ambitos_ibfk_2` (`ID_AMBITO`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `userName` (`userName`),
  ADD KEY `usuarios_ibfk_2` (`id_rol`),
  ADD KEY `usuarios_ibfk_3` (`id_centro`);

--
-- Indices de la tabla `usuarios_token`
--
ALTER TABLE `usuarios_token`
  ADD PRIMARY KEY (`TokenId`),
  ADD KEY `UsuarioId` (`UsuarioId`);

--
-- Indices de la tabla `visitas`
--
ALTER TABLE `visitas`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `zonaBasicaSalud`
--
ALTER TABLE `zonaBasicaSalud`
  ADD PRIMARY KEY (`id_zona_basica_salud`),
  ADD KEY `zonaBasicaSalud_ibfk_1` (`id_area_salud`),
  ADD KEY `zonaBasicaSalud_ibfk_2` (`id_ambito`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `alertas`
--
ALTER TABLE `alertas`
  MODIFY `codigo` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `alta`
--
ALTER TABLE `alta`
  MODIFY `id_alta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT de la tabla `hoja_prescripcion`
--
ALTER TABLE `hoja_prescripcion`
  MODIFY `id_hoja` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `ingresos`
--
ALTER TABLE `ingresos`
  MODIFY `id_ingreso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `notas_enfermeria`
--
ALTER TABLE `notas_enfermeria`
  MODIFY `id_notas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `nhc` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `patologia`
--
ALTER TABLE `patologia`
  MODIFY `id_patologia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuarios_token`
--
ALTER TABLE `usuarios_token`
  MODIFY `TokenId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `visitas`
--
ALTER TABLE `visitas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=385;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alertas`
--
ALTER TABLE `alertas`
  ADD CONSTRAINT `alertas_ibfk_1` FOREIGN KEY (`nhc`) REFERENCES `pacientes` (`nhc`);

--
-- Filtros para la tabla `alta`
--
ALTER TABLE `alta`
  ADD CONSTRAINT `altas_ibfk_1` FOREIGN KEY (`nhc`) REFERENCES `pacientes` (`nhc`);

--
-- Filtros para la tabla `camas`
--
ALTER TABLE `camas`
  ADD CONSTRAINT `camas_ibfk_1` FOREIGN KEY (`id_centro`) REFERENCES `centros` (`id_centro`);

--
-- Filtros para la tabla `centros`
--
ALTER TABLE `centros`
  ADD CONSTRAINT `centros_ibfk_1` FOREIGN KEY (`id_area_salud`) REFERENCES `areaSalud` (`id_area_salud`);

--
-- Filtros para la tabla `hoja_prescripcion`
--
ALTER TABLE `hoja_prescripcion`
  ADD CONSTRAINT `patologia_ibfk_1` FOREIGN KEY (`nhc`) REFERENCES `pacientes` (`nhc`);

--
-- Filtros para la tabla `ingresos`
--
ALTER TABLE `ingresos`
  ADD CONSTRAINT `ingresos_ibfk_1` FOREIGN KEY (`id_cama`) REFERENCES `camas` (`id_cama`),
  ADD CONSTRAINT `ingresos_ibfk_2` FOREIGN KEY (`nhc`) REFERENCES `pacientes` (`nhc`);

--
-- Filtros para la tabla `notas_enfermeria`
--
ALTER TABLE `notas_enfermeria`
  ADD CONSTRAINT `notas_enfermeria_ibfk_1` FOREIGN KEY (`nhc`) REFERENCES `pacientes` (`nhc`);

--
-- Filtros para la tabla `patologia`
--
ALTER TABLE `patologia`
  ADD CONSTRAINT `fk_patologia_nhc` FOREIGN KEY (`nhc`) REFERENCES `pacientes` (`nhc`);

--
-- Filtros para la tabla `provincia`
--
ALTER TABLE `provincia`
  ADD CONSTRAINT `provincia_ibfk_1` FOREIGN KEY (`id_cautonoma`) REFERENCES `comunidadautonoma` (`id_cautonoma`);

--
-- Filtros para la tabla `recuperarContrasena`
--
ALTER TABLE `recuperarContrasena`
  ADD CONSTRAINT `recuperarContrasena_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `roles_ambitos`
--
ALTER TABLE `roles_ambitos`
  ADD CONSTRAINT `roles_ambitos_ibfk_1` FOREIGN KEY (`ID_ROL`) REFERENCES `roles` (`id_rol`),
  ADD CONSTRAINT `roles_ambitos_ibfk_2` FOREIGN KEY (`ID_AMBITO`) REFERENCES `ambitos` (`id_ambito`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`id_rol`) REFERENCES `roles` (`id_rol`),
  ADD CONSTRAINT `usuarios_ibfk_3` FOREIGN KEY (`id_centro`) REFERENCES `centros` (`id_centro`);

--
-- Filtros para la tabla `usuarios_token`
--
ALTER TABLE `usuarios_token`
  ADD CONSTRAINT `usuarios_token_ibfk_1` FOREIGN KEY (`UsuarioId`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `zonaBasicaSalud`
--
ALTER TABLE `zonaBasicaSalud`
  ADD CONSTRAINT `zonaBasicaSalud_ibfk_1` FOREIGN KEY (`id_area_salud`) REFERENCES `areaSalud` (`id_area_salud`),
  ADD CONSTRAINT `zonaBasicaSalud_ibfk_2` FOREIGN KEY (`id_ambito`) REFERENCES `ambitos` (`id_ambito`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
