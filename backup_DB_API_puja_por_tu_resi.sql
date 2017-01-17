-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 17-01-2017 a las 16:32:20
-- Versión del servidor: 5.5.44-0ubuntu0.14.04.1
-- Versión de PHP: 5.5.9-1ubuntu4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `API_puja_por_tu_resi`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colleges`
--

CREATE TABLE IF NOT EXISTS `colleges` (
  `username` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `company_name` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `latitude` double NOT NULL,
  `longitude` double NOT NULL,
  `telephone` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `url` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `colleges`
--

INSERT INTO `colleges` (`username`, `password`, `email`, `is_active`, `company_name`, `address`, `latitude`, `longitude`, `telephone`, `url`) VALUES
('B18756676', '$2y$13$tVR0M8lYWAfbKwJY5uLMuOepGiefq3PEy8uKFozbyfZefwtv6ow22', 'jm.94.antonio@gmail.com', 1, 'antonio', 'Calle de la Estacion, 1, 04850 Cantoria, Almería, España', 37.35106999999999, -2.192099999999982, '888888888', 'https://www.google.es/?gws_rd=ssl');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `incidence`
--

CREATE TABLE IF NOT EXISTS `incidence` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_username` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `file_name` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_170604174BD54AEC` (`student_username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `incidence`
--

INSERT INTO `incidence` (`id`, `student_username`, `status`, `description`, `file_name`, `date`) VALUES
(1, '12345678A', 'IN PROGRESS', 'la mesa esta rota ', '1aa2c0c121556de82865a9898058937a.jpg', '2017-01-10 18:34:45'),
(2, '12345678A', 'OPEN', 'la puerta no se cierra. Ayuda ', 'c4d35eccf17df135b22c07d183a47e21.jpg', '2017-01-10 18:40:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `message`
--

CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_username` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `college_username` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `read_by_student` tinyint(1) NOT NULL,
  `read_by_college` tinyint(1) NOT NULL,
  `message` varchar(5000) COLLATE utf8_unicode_ci NOT NULL,
  `file_attached` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` datetime NOT NULL,
  `sender_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_B6BD307F4BD54AEC` (`student_username`),
  KEY `IDX_B6BD307F16A289D1` (`college_username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `message`
--

INSERT INTO `message` (`id`, `student_username`, `college_username`, `read_by_student`, `read_by_college`, `message`, `file_attached`, `date`, `sender_type`) VALUES
(1, '12345678A', NULL, 1, 0, 'hola que tal estas antonio?', NULL, '2017-01-10 18:35:06', 'ROLE_STUDENT'),
(2, '12345678A', NULL, 1, 0, 'te escribo de nuevo para preguntar por la familia? ', 'ce074d239ca6644c8b78bc42728b3e98.jpg', '2017-01-10 18:35:38', 'ROLE_STUDENT'),
(3, '12345678A', NULL, 1, 0, 'holaaaaaaa, yo muy bien y tu?', NULL, '2017-01-10 18:58:12', 'ROLE_COLLEGE'),
(4, '12345678A', NULL, 1, 0, 'muy bien también. Gracias. ', NULL, '2017-01-10 19:25:57', 'ROLE_STUDENT');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rent`
--

CREATE TABLE IF NOT EXISTS `rent` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `student_username` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status_paid` tinyint(1) NOT NULL,
  `price` double NOT NULL,
  `date` datetime NOT NULL,
  `file_receipt` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_paid` datetime DEFAULT NULL,
  `card_holder` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `card_number` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_2784DCC4BD54AEC` (`student_username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `rent`
--

INSERT INTO `rent` (`id`, `student_username`, `status_paid`, `price`, `date`, `file_receipt`, `date_paid`, `card_holder`, `card_number`) VALUES
(2, '12345678A', 1, 100, '2017-01-10 18:38:07', '81acf277523dcde69abaa61e60cfa993.pdf', '2017-01-10 19:26:33', 'juan', '4886171554372581'),
(3, '12345678A', 1, 100, '2017-01-10 18:38:08', '16461ec3bd01a0d1db5d6dbf63d1f19c.pdf', '2017-01-10 18:59:47', 'antonio', '4009487096031929'),
(4, '12345678A', 0, 100, '2017-01-12 20:49:33', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `students`
--

CREATE TABLE IF NOT EXISTS `students` (
  `username` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `creation_date` datetime NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `students`
--

INSERT INTO `students` (`username`, `password`, `email`, `name`, `is_active`, `creation_date`) VALUES
('12345678A', '$2y$13$xqavJniqKVrgXgISBRXt.Ow1ztY8UCgdBymCeRep40r4MdvheAIqe', 'ant1onio@gmail.com', 'antonio', 1, '2017-01-10 18:31:32');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `incidence`
--
ALTER TABLE `incidence`
  ADD CONSTRAINT `FK_170604174BD54AEC` FOREIGN KEY (`student_username`) REFERENCES `students` (`username`);

--
-- Filtros para la tabla `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `FK_B6BD307F16A289D1` FOREIGN KEY (`college_username`) REFERENCES `colleges` (`username`),
  ADD CONSTRAINT `FK_B6BD307F4BD54AEC` FOREIGN KEY (`student_username`) REFERENCES `students` (`username`);

--
-- Filtros para la tabla `rent`
--
ALTER TABLE `rent`
  ADD CONSTRAINT `FK_2784DCC4BD54AEC` FOREIGN KEY (`student_username`) REFERENCES `students` (`username`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
