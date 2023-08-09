-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-08-2023 a las 14:27:28
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `dni_database`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `dni` varchar(20) NOT NULL,
  `password` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `admins`
--

INSERT INTO `admins` (`id`, `dni`, `password`) VALUES
(1, '12345678', '1234'),
(2, '12345678', '1234'),
(3, '12345678', '1234');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `escrutinio_final`
--

CREATE TABLE `escrutinio_final` (
  `id` int(11) NOT NULL,
  `lista` varchar(50) NOT NULL,
  `presidencia` int(11) NOT NULL,
  `secretarias` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `escrutinio_final`
--

INSERT INTO `escrutinio_final` (`id`, `lista`, `presidencia`, `secretarias`) VALUES
(1, 'lista a', 0, 0),
(2, 'lista b', 0, 0),
(3, 'EN BLANCO', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `padron_electoral_general___hoja_1`
--

CREATE TABLE `padron_electoral_general___hoja_1` (
  `curso` varchar(11) DEFAULT NULL,
  `dni` int(8) NOT NULL,
  `apellido_nombre` varchar(38) DEFAULT NULL,
  `count` int(11) DEFAULT 0,
  `boleta_count` int(1) DEFAULT 0,
  `habilitado` varchar(2) NOT NULL DEFAULT 'no',
  `hora` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Volcado de datos para la tabla `padron_electoral_general___hoja_1`
--

INSERT INTO `padron_electoral_general___hoja_1` (`curso`, `dni`, `apellido_nombre`, `count`, `boleta_count`, `habilitado`, `hora`) VALUES
('PRIMER AÑO', 12345678, 'FULANO RANDOM', 0, 0, 'no', '0000-00-00 00:00:00');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `escrutinio_final`
--
ALTER TABLE `escrutinio_final`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `padron_electoral_general___hoja_1`
--
ALTER TABLE `padron_electoral_general___hoja_1`
  ADD UNIQUE KEY `dni` (`dni`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `escrutinio_final`
--
ALTER TABLE `escrutinio_final`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `padron_electoral_general___hoja_1`
--
ALTER TABLE `padron_electoral_general___hoja_1`
  MODIFY `dni` int(8) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=94876804;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
