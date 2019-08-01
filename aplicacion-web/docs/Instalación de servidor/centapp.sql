-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-03-2019 a las 21:11:09
-- Versión del servidor: 10.1.26-MariaDB
-- Versión de PHP: 7.2.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `centapp`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alumno`
--

CREATE TABLE `alumno` (
  `codigo` int(11) NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cambiopass` tinyint(1) NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `alumno`
--

INSERT INTO `alumno` (`codigo`, `password`, `cambiopass`, `email`) VALUES
(7893, '76d80224611fc919a5d54f0ff9fba446', 1, 'facundo98n@gmail.com'),
(7941, '6d2143154327a64d86a264aea225f3', 1, 'a@b.com'),
(7981, '98f6bcd4621d373cade4e832627b4f6', 1, 'a@b.com'),
(8121, '662eaa47199461d01a623884080934ab', 1, 'test@gmail.com');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anuncios`
--

CREATE TABLE `anuncios` (
  `id` int(11) NOT NULL,
  `contenido` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_publi` datetime NOT NULL,
  `fecha_ini` date NOT NULL,
  `fecha_fin` date NOT NULL,
  `visible` tinyint(1) NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `anuncios`
--

INSERT INTO `anuncios` (`id`, `contenido`, `fecha_publi`, `fecha_ini`, `fecha_fin`, `visible`, `titulo`) VALUES
(59, 'Se suspenden las actividades el día martes 26/03 en el turno vespertino', '2019-03-09 17:32:10', '2019-03-26', '2019-03-26', 1, 'Suspensión de actividades'),
(61, 'anuncio', '2019-03-10 21:50:16', '2019-03-20', '2019-03-20', 1, 'titulo'),
(62, 'a', '2019-03-10 21:51:05', '2019-03-13', '2019-03-13', 1, 'notif'),
(63, 'p', '2019-03-10 21:51:35', '2019-03-21', '2019-03-28', 1, 'n');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ausencia_docente`
--

CREATE TABLE `ausencia_docente` (
  `id` int(11) NOT NULL,
  `docente` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_ini` date NOT NULL,
  `fecha_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ausencia_docente`
--

INSERT INTO `ausencia_docente` (`id`, `docente`, `fecha_ini`, `fecha_fin`) VALUES
(6, 'Docente', '2019-03-11', '2019-03-13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `fecha_calendario`
--

CREATE TABLE `fecha_calendario` (
  `id` int(11) NOT NULL,
  `fecha_ini` date NOT NULL,
  `fecha_fin` date DEFAULT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `visible` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `fecha_calendario`
--

INSERT INTO `fecha_calendario` (`id`, `fecha_ini`, `fecha_fin`, `descripcion`, `visible`) VALUES
(7, '2018-02-14', NULL, 'Presentación de docentes y personal administrativo', 1),
(8, '2018-02-14', '2018-03-02', 'Mesas de examen - dos llamados', 1),
(9, '2018-02-26', '2018-03-16', 'Curso Introductorio a los Estudios Superiores- Enfermería', 1),
(10, '2018-03-05', '2018-03-16', 'Curso Introductorio a los Estudios Superiores- resto de carreras', 1),
(11, '2018-03-12', '2018-03-16', 'Inscripciones a cursar materias', 1),
(12, '2018-03-19', NULL, 'Comienzo de clases - Todos los cursos', 1),
(13, '2018-03-20', '2018-03-23', 'Acreditaciones Artículo 7° - Ley 24.521', 1),
(14, '2018-03-24', NULL, 'Feriado Día Nacional de la Memoria por la verdad y la Justicia', 1),
(15, '2018-03-31', NULL, 'Vencimiento entrega declaración jurada', 1),
(16, '2018-03-29', '2018-03-30', 'Feriado jueves y viernes Santo', 1),
(17, '2018-04-02', NULL, 'Feriado Día del veterano de Malvinas', 1),
(18, '2018-04-04', NULL, '30 años del Cent 35', 1),
(19, '2018-04-06', NULL, 'Vencimiento entrega de programas', 1),
(20, '2018-04-27', NULL, 'Jornada Institucional', 1),
(21, '2018-04-30', NULL, 'Día no laborable - puente', 1),
(22, '2018-05-01', NULL, 'Feriado Día del trabajador', 1),
(23, '2018-05-07', '2018-05-07', 'Mesas de examen extraordinarias sin suspensión de clases', 1),
(24, '2018-05-25', NULL, 'Feriado Revolución de Mayo', 1),
(25, '2018-05-29', NULL, 'Jornada - Oferta Académica', 1),
(26, '2018-06-01', NULL, 'Feriado Día de la Provincia', 1),
(27, '2018-06-11', NULL, 'Jornada Institucional', 1),
(28, '2018-06-18', '2018-07-06', 'Parciales (y recuperatorios de materias cuatrimestrales)', 1),
(29, '2018-07-06', NULL, 'Vencimiento plazo de entrega de planillas de notas', 1),
(30, '2018-07-09', NULL, 'Feriado Día de la Independencia', 1),
(31, '2018-06-17', NULL, 'Feriado Güemes', 1),
(32, '2018-06-20', NULL, 'Feriado Paso a la Inmortalidad del General Manuel Belgrano', 1),
(33, '2018-07-11', NULL, 'Feriado Día de Río Grande', 1),
(34, '2018-07-10', '2018-07-13', 'Inscripción a finales', 1),
(35, '2018-07-16', '2018-07-28', 'Receso invernal', 1),
(36, '2018-07-30', NULL, 'Presentación de docentes y personal administrativo', 1),
(37, '2018-07-30', NULL, 'Inicio inscripciones a mesas', 1),
(38, '2018-07-31', '2018-08-09', 'Mesas de examen', 1),
(39, '2018-08-10', NULL, 'Inscripción a cursar materias del 2° cuatrimestre', 1),
(40, '2018-08-13', NULL, 'Inicio de clases - 2° cuatrimestre', 1),
(41, '2018-08-20', NULL, 'Feriado Paso a la Inmortalidad del General San Martín', 1),
(42, '2018-08-24', NULL, 'Entrega programas 2° cuatrimestre', 1),
(43, '2018-09-10', '2018-09-14', 'Mesas de examen extraordinarias sin suspensión de clases', 1),
(44, '2018-09-11', NULL, 'Asueto Día del Maestro', 1),
(45, '2018-09-21', NULL, 'Jornada Institucional', 1),
(46, '2018-10-15', NULL, 'Feriado Día del Respeto a la Diversidad Cultural', 1),
(47, '2018-11-12', '2018-11-26', 'Semana de parciales y recuperatorios', 1),
(48, '2018-11-12', '2018-11-16', 'Semana de la educación técnica', 1),
(49, '2018-11-19', NULL, 'Día de la Soberanía Nacional', 1),
(50, '2018-11-23', NULL, 'Finalización plazo de entrega de planilllas de notas', 1),
(51, '2018-11-26', NULL, 'Inicio de inscripciones a mesas del turno diciembre', 1),
(52, '2018-11-28', '2018-12-18', 'Mesas de examen turno diciembre - dos llamados', 1),
(53, '2018-12-08', NULL, 'Feriado Inmaculada Concepción de María', 1),
(54, '2018-12-19', NULL, 'Cierre del ciclo lectivo', 1),
(55, '2017-02-07', NULL, 'Presentación de docentes y personal administrativo', 1),
(56, '2017-02-13', '2017-03-10', 'Inscripciones a primer año', 1),
(57, '2017-02-15', '2017-03-10', 'Mesas de examen', 1),
(58, '2017-02-15', '2017-03-10', 'Curso introductorio a los Estudios Superiores', 1),
(59, '2017-03-06', '2017-03-10', 'Inscripciones a cursar materias', 1),
(60, '2017-03-13', NULL, 'Comienzo de clases - Todos los cursos', 1),
(61, '2017-03-24', NULL, 'Feriado Día Nacional de la Memoria por la verdad y la justicia', 1),
(62, '2017-03-31', NULL, 'Vencimiento del plazo para entrega de declaración jurada', 1),
(63, '2017-03-31', NULL, 'Vencimiento del plazo para entrega de programas', 1),
(64, '2017-04-02', NULL, 'Feriado Día del veterano de Malvinas', 1),
(65, '2017-04-04', NULL, '29 años del Cent 35', 1),
(66, '2017-04-13', '2017-04-14', 'Feriado jueves y viernes Santo', 1),
(67, '2017-04-24', NULL, 'Jornada Institucional', 1),
(68, '2017-05-01', NULL, 'Feriado Día del trabajador', 1),
(69, '2017-05-02', '2017-05-09', 'Mesas de examen extraordinarias sin suspensión de clases', 1),
(70, '2017-05-10', '2017-05-12', 'Acreditaciones Artículo 7° -Ley 24.521', 1),
(71, '2017-05-25', NULL, 'Feriado Revolución de Mayo', 1),
(72, '2017-06-01', NULL, 'Feriado Día de la Provincia', 1),
(73, '2017-05-29', '2017-06-02', 'Convocatoria interna a docentes 2° cuatrimestre', 1),
(74, '2017-06-05', '2017-06-09', 'Convocatoria externa a docentes 2° cuatrimestre', 1),
(75, '2017-06-17', NULL, 'Feriado Güemes', 1),
(76, '2017-06-20', NULL, 'Feriado Paso a la Inmortalidad del General Manuel Belgrano', 1),
(77, '2017-06-19', '2017-06-30', 'Parciales (y recuperatorios de materias cuatrimestrales)', 1),
(78, '2017-07-03', NULL, 'Vencimiento plazo de entrega de planillas de notas', 1),
(79, '2017-07-03', NULL, 'Inicio inscripciones mesas del primer llamado de Agosto', 1),
(80, '2017-07-05', '2017-07-14', 'Mesas de examen - Primer llamado turno Agosto', 1),
(81, '2017-07-09', NULL, 'Feriado Día de la Independencia', 1),
(82, '2017-07-11', NULL, 'Feriado Día de Río Grande', 1),
(83, '2017-07-17', '2017-07-29', 'Receso invernal', 1),
(84, '2017-07-31', NULL, 'Presentación de docentes y personal administrativo', 1),
(85, '2017-07-31', NULL, 'Inicio inscripciones a mesas del segundo llamado Agosto', 1),
(86, '2017-08-02', '2017-08-11', 'Mesas de examen - Segundo llamado turno Agosto', 1),
(87, '2017-08-14', NULL, 'Inicio de clases - 2° cuatrimestre', 1),
(88, '2017-08-14', '2017-08-18', 'Recuperatorios de materias anuales', 1),
(89, '2017-08-21', NULL, 'Feriado Paso a la inmortalidad del General San Martín', 1),
(90, '2017-08-25', NULL, 'Entrega de programas y declaraciones juradas del segundo cuatrimestre', 1),
(91, '2017-09-11', NULL, 'Asueto Día del Maestro', 1),
(92, '2017-09-21', NULL, 'Día del Estudiante. Suspensión de clases para alumnos', 1),
(93, '2017-09-22', NULL, 'Muestra de trabajos de alumnos del CENT 35', 1),
(94, '2017-09-25', '2017-09-29', 'Mesas de examen extraordinarias sin suspensión de clases', 1),
(95, '2017-10-01', '2017-10-31', 'Convocatoria a docentes, interna y externa', 1),
(96, '2017-10-16', NULL, 'Feriado Día del Respeto a la Diversidad Cultural', 1),
(97, '2017-11-06', '2017-11-23', 'Semanas de parciales y recuperatorios', 1),
(98, '2017-11-13', '2017-11-17', 'Semana de la educación técnica', 1),
(99, '2017-11-20', NULL, 'Día de la Soberanía Nacional', 1),
(100, '2017-11-24', NULL, 'Finalización plazo de entrega de planillas de notas', 1),
(101, '2017-11-27', NULL, 'Inicio de inscripciones a mesas del turno diciembre', 1),
(102, '2017-11-28', NULL, 'Jornada institucional docente', 1),
(103, '2017-11-30', '2017-12-15', 'Mesas de examen turno diciembre', 1),
(104, '2017-12-08', NULL, 'Feriado inmaculada Concepción de María', 1),
(105, '2017-12-20', NULL, 'Jornada. Cierre del ciclo lectivo', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migration_versions`
--

CREATE TABLE `migration_versions` (
  `version` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `migration_versions`
--

INSERT INTO `migration_versions` (`version`) VALUES
('20181017133719'),
('20181025232611'),
('20190104023006'),
('20190109194914'),
('20190109215233'),
('20190109215624'),
('20190209202549'),
('20190306233033');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `username` varchar(127) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(127) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(127) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `user`
--

INSERT INTO `user` (`id`, `username`, `password`, `email`, `role`) VALUES
(2, 'admin', '$2y$13$KOzGLNecMjMnrx9hP5Uxm.VPh5gXFx0DZoEhlXgHU7U4nADSi/p8W', 'no-reply@overseas.media', 'ROLE_ADMIN'),
(5, 'Empleado', '$2y$13$OcYdeLoyzqeuV1bDi/77t.BdNlH7/W5rnn9qoY5krgYmhPJkvhWJC', 'empleado@gmail.com', 'ROLE_EMPLOYEE');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `alumno`
--
ALTER TABLE `alumno`
  ADD PRIMARY KEY (`codigo`);

--
-- Indices de la tabla `anuncios`
--
ALTER TABLE `anuncios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ausencia_docente`
--
ALTER TABLE `ausencia_docente`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `fecha_calendario`
--
ALTER TABLE `fecha_calendario`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migration_versions`
--
ALTER TABLE `migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indices de la tabla `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649F85E0677` (`username`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `anuncios`
--
ALTER TABLE `anuncios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=64;

--
-- AUTO_INCREMENT de la tabla `ausencia_docente`
--
ALTER TABLE `ausencia_docente`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `fecha_calendario`
--
ALTER TABLE `fecha_calendario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT de la tabla `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
