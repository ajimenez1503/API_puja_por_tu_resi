-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 04-03-2017 a las 13:46:18
-- Versión del servidor: 5.7.17-0ubuntu0.16.04.1
-- Versión de PHP: 7.0.15-0ubuntu0.16.04.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `API_puja_por_tu_resi`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `agreement`
--

CREATE TABLE `agreement` (
  `id` int(11) NOT NULL,
  `student_username` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `price` double NOT NULL,
  `date_start_school` datetime NOT NULL,
  `date_end_school` datetime NOT NULL,
  `file_agreement` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `file_agreement_signed` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_signed` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `agreement`
--

INSERT INTO `agreement` (`id`, `student_username`, `room_id`, `price`, `date_start_school`, `date_end_school`, `file_agreement`, `file_agreement_signed`, `date_signed`) VALUES
(16, '12345678A', 16, 1800, '2017-09-01 00:00:00', '2018-06-30 00:00:00', 'fada024a97f6cf710f7fa7d345b4c4a5.pdf', '77087d519fdd34cf123b91655e2e9633.pdf', '2017-02-02 17:33:08'),
(17, '87654321A', 17, 800, '2017-08-26 00:00:00', '2018-07-26 00:00:00', 'cf7dd4a104cf8a6c01471d7542699f82.pdf', '0f7f4e3faf9a2ac186a0a159d8b53f34.pdf', '2017-02-09 19:23:45');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bank`
--

CREATE TABLE `bank` (
  `id` int(11) NOT NULL,
  `college_username` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `iban` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `bic` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `account_holder` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `activate` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `bank`
--

INSERT INTO `bank` (`id`, `college_username`, `iban`, `bic`, `account_holder`, `activate`) VALUES
(16, 'B18756676', 'ES7620770024003102575766', 'DABAIE2D', 'esg', 1),
(17, 'B18756676', 'ES7620770024003102575766', 'DABAIE2D', 'test', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bid`
--

CREATE TABLE `bid` (
  `id` int(11) NOT NULL,
  `student_username` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `point` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `bid`
--

INSERT INTO `bid` (`id`, `student_username`, `room_id`, `point`) VALUES
(2, '12345678A', 27, 33);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colleges`
--

CREATE TABLE `colleges` (
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
  `wifi` tinyint(1) NOT NULL,
  `elevator` tinyint(1) NOT NULL,
  `canteen` tinyint(1) NOT NULL,
  `hours24` tinyint(1) NOT NULL,
  `laundry` tinyint(1) NOT NULL,
  `gym` tinyint(1) NOT NULL,
  `study_room` tinyint(1) NOT NULL,
  `heating` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `colleges`
--

INSERT INTO `colleges` (`username`, `password`, `email`, `is_active`, `company_name`, `address`, `latitude`, `longitude`, `telephone`, `url`, `wifi`, `elevator`, `canteen`, `hours24`, `laundry`, `gym`, `study_room`, `heating`) VALUES
('B18756676', '$2y$13$tVR0M8lYWAfbKwJY5uLMuOepGiefq3PEy8uKFozbyfZefwtv6ow22', 'ab@gmai.com', 1, 'antonio college', 'Calle Obispo Hurtado, 21, 18002 Granada, España', 37.173724, -3.606465500000013, '444444448', 'http://127.0.0.1:8000/ProfileCollege/updateProfile/', 0, 1, 1, 0, 0, 1, 1, 0),
('college11', '$2y$13$CmOjG7rctQxhEqURfXm.9eEtwXRfjD8.9Iwz0ASntPy/GK9d2i9iu', 'email@gmail.com', 1, 'gdsga', 'address', 0, 0, '666666666', 'https://symfony.com/', 0, 0, 0, 0, 0, 0, 0, 0),
('college12', '$2y$13$WmHW2RCZ.l6GkkSEUIlguOVNRas6wKv2uSi44KEgp96X4ngRblCT.', 'email@gmail.com', 1, 'gdsga', 'address', 0, 0, '666666666', 'https://symfony.com/', 0, 0, 0, 0, 0, 0, 0, 0),
('college13', '$2y$13$gu9KvLTlqDoSkAk625sSTeZAuXciBkKXV1w5CEspAaxJAMz1Lhbnu', 'email@gmail.com', 1, 'gdsga', 'address', 0, 0, '666666666', 'https://symfony.com/', 1, 0, 1, 0, 0, 0, 1, 0),
('F95161196', '$2y$13$GGhMVySkCPzhCCQKw/FBweYCRCG8thhSVzKseF96jH2U0Co38Offm', 'jm.94.antonio@gmail.com', 1, 'ISABEL JIMÉNEZ', 'Calle Estación, 1, 50500 Valverde, Zaragoza, España', 41.9786272, -1.8613904000000048, '123456789', 'https://github.com/softwarejimenez/web_puja_por_tu_resi', 1, 0, 0, 0, 0, 0, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `incidence`
--

CREATE TABLE `incidence` (
  `id` int(11) NOT NULL,
  `student_username` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `file_name` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `incidence`
--

INSERT INTO `incidence` (`id`, `student_username`, `status`, `description`, `file_name`, `date`) VALUES
(1, '12345678A', 'IN PROGRESS', 'la mesa esta rota ', '1aa2c0c121556de82865a9898058937a.jpg', '2017-01-10 18:34:45'),
(2, '12345678A', 'DONE', 'la puerta no se cierra. Ayuda ', 'c4d35eccf17df135b22c07d183a47e21.jpg', '2017-01-10 18:40:13'),
(3, '87654321A', 'IN PROGRESS', 'test', '010e00f6e19b777011c88403147b1e53.jpg', '2017-02-15 20:19:28'),
(4, '87654321A', 'OPEN', 'test', 'ba96d281e518b61853012ef9050c20fc.jpg', '2017-02-15 20:19:28'),
(5, '87654321A', 'OPEN', 'sdgsadgdas', '4e8c207f9e7c1f4a7b0b4a56ff0b6172.jpg', '2017-02-15 20:36:11'),
(6, '12345678A', 'DONE', 'holaaaa test ', '297677123c02644aac0de9cbb1b54328.jpg', '2017-03-01 20:02:52');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `student_username` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `college_username` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `read_by_student` tinyint(1) NOT NULL,
  `read_by_college` tinyint(1) NOT NULL,
  `message` varchar(5000) COLLATE utf8_unicode_ci NOT NULL,
  `file_attached` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date` datetime NOT NULL,
  `sender_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `message`
--

INSERT INTO `message` (`id`, `student_username`, `college_username`, `read_by_student`, `read_by_college`, `message`, `file_attached`, `date`, `sender_type`) VALUES
(7, '12345678A', 'B18756676', 1, 1, 'test', 'c5248b92f71bf9c4c4bf57ce4ac23c33.pdf', '2017-02-03 17:56:46', 'ROLE_STUDENT'),
(8, '12345678A', 'B18756676', 1, 1, 'test', '5eda11c58a4253090424adf579c53727.pdf', '2017-02-03 17:58:04', 'ROLE_STUDENT'),
(9, '12345678A', 'B18756676', 1, 1, 'test', '8de6ebb97a18d313abf14b67c1e51077.pdf', '2017-02-03 18:12:51', 'ROLE_COLLEGE'),
(10, '12345678A', 'B18756676', 1, 1, 'holaaaaaa', NULL, '2017-02-04 13:35:42', 'ROLE_STUDENT'),
(11, '12345678A', 'B18756676', 1, 1, 'undefined', 'b9ace7fa97e6ae3c284c702069fedf9c.pdf', '2017-02-04 19:16:34', 'ROLE_COLLEGE'),
(12, '12345678A', 'B18756676', 1, 1, 'undefined', NULL, '2017-02-04 19:19:27', 'ROLE_COLLEGE'),
(13, '12345678A', 'B18756676', 1, 1, 'sdgsadgsadgsd', NULL, '2017-02-04 19:20:32', 'ROLE_COLLEGE'),
(14, '12345678A', 'B18756676', 1, 1, 'holaaa test ', NULL, '2017-02-05 11:48:04', 'ROLE_COLLEGE'),
(15, '12345678A', 'B18756676', 1, 1, 'hjhk', NULL, '2017-02-05 11:51:44', 'ROLE_COLLEGE'),
(16, '87654321A', 'B18756676', 1, 1, 'holajefe ', NULL, '2017-02-09 19:33:08', 'ROLE_STUDENT'),
(17, '12345678A', 'B18756676', 1, 1, 'safasdfdsafsa', NULL, '2017-02-10 17:58:45', 'ROLE_STUDENT'),
(18, '12345678A', 'B18756676', 1, 1, 'agadgas', NULL, '2017-02-10 18:02:39', 'ROLE_STUDENT'),
(19, '12345678A', 'B18756676', 1, 1, 'hola como estas?', NULL, '2017-02-22 12:27:28', 'ROLE_COLLEGE'),
(20, '87654321A', 'B18756676', 1, 1, 'hola como estas?', NULL, '2017-02-22 12:27:29', 'ROLE_COLLEGE'),
(21, '12345678A', 'B18756676', 1, 1, 'holaaa test 1', NULL, '2017-02-22 16:34:44', 'ROLE_COLLEGE'),
(22, '87654321A', 'B18756676', 1, 1, 'holaaa test 1', NULL, '2017-02-22 16:34:44', 'ROLE_COLLEGE'),
(23, '12345678A', 'B18756676', 1, 1, 'test2', 'f0f4d8995b19db5c9579c397a0e01327.jpg', '2017-02-22 16:35:25', 'ROLE_COLLEGE'),
(24, '87654321A', 'B18756676', 1, 1, 'test2', '92f14bee872058e29c4ada6045767aab.jpg', '2017-02-22 16:35:25', 'ROLE_COLLEGE'),
(25, '12345678A', 'B18756676', 1, 1, 'holaa%20asdgsad%20%3Csdg%3E%20ssd%20%3C/sdgs%3E', NULL, '2017-03-01 18:57:36', 'ROLE_COLLEGE'),
(26, '12345678A', 'B18756676', 1, 1, 'hola%20que%20tal%20estas%20%3F.%20%3Cscript%3Efsdsd%3C/script%3E', NULL, '2017-03-01 18:58:09', 'ROLE_COLLEGE'),
(27, '12345678A', 'B18756676', 1, 1, 'hola%20que%20tal%20estas%20%3F.%20%3Cscript%3Efsdsd%3C/script%3E', NULL, '2017-03-01 19:00:11', 'ROLE_COLLEGE'),
(28, '12345678A', 'B18756676', 1, 1, 'sadg dsg', NULL, '2017-03-01 19:00:53', 'ROLE_COLLEGE'),
(29, '12345678A', 'B18756676', 1, 1, 'hola que tal estas ?. &lt;script&gt;fsdsd&lt;/script&gt;', NULL, '2017-03-01 19:01:04', 'ROLE_COLLEGE'),
(30, '12345678A', 'B18756676', 1, 1, 'saga', NULL, '2017-03-01 19:04:33', 'ROLE_COLLEGE'),
(31, '12345678A', 'B18756676', 1, 1, 'hola que tal estas ?. &lt;script&gt;fsdsd&lt;/script&gt;', NULL, '2017-03-01 19:04:42', 'ROLE_COLLEGE'),
(32, '12345678A', 'B18756676', 1, 0, 'dbsadb', NULL, '2017-03-01 19:06:09', 'ROLE_STUDENT'),
(33, '12345678A', 'B18756676', 1, 0, 'sdgsdagasd', NULL, '2017-03-01 19:07:14', 'ROLE_STUDENT');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rent`
--

CREATE TABLE `rent` (
  `id` int(11) NOT NULL,
  `student_username` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status_paid` tinyint(1) NOT NULL,
  `price` double NOT NULL,
  `date` datetime NOT NULL,
  `file_receipt` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_paid` datetime DEFAULT NULL,
  `id_transaction` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `rent`
--

INSERT INTO `rent` (`id`, `student_username`, `status_paid`, `price`, `date`, `file_receipt`, `date_paid`, `id_transaction`) VALUES
(69, '87654321A', 1, 800, '2017-08-26 00:00:00', '292c55780838459d42f093ca9767cf6a.pdf', '2017-02-09 20:10:44', NULL),
(70, '87654321A', 1, 800, '2017-09-26 00:00:00', '2734b62087d6207c7425469cec8ec298.pdf', '2017-03-04 13:44:21', '3785'),
(71, '87654321A', 0, 800, '2017-10-26 00:00:00', NULL, NULL, NULL),
(72, '87654321A', 0, 800, '2017-11-26 00:00:00', NULL, NULL, NULL),
(73, '87654321A', 0, 800, '2017-12-26 00:00:00', NULL, NULL, NULL),
(74, '87654321A', 0, 800, '2018-01-26 00:00:00', NULL, NULL, NULL),
(75, '87654321A', 0, 800, '2018-02-26 00:00:00', NULL, NULL, NULL),
(76, '87654321A', 0, 800, '2018-03-26 00:00:00', NULL, NULL, NULL),
(77, '87654321A', 0, 800, '2018-04-26 00:00:00', NULL, NULL, NULL),
(78, '87654321A', 0, 800, '2018-05-26 00:00:00', NULL, NULL, NULL),
(79, '87654321A', 0, 800, '2018-06-26 00:00:00', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `responsiblePerson`
--

CREATE TABLE `responsiblePerson` (
  `dni` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `college_username` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `job_position` varchar(30) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `responsiblePerson`
--

INSERT INTO `responsiblePerson` (`dni`, `college_username`, `email`, `name`, `job_position`) VALUES
('11111111M', 'B18756676', 'jm.94.antonio@gmail.com', 'test', 'test'),
('12123456A', 'B18756676', 'jm.94.antonio@gmail.com', 'sdfasf', 'seee'),
('12345678U', 'B18756676', 'jm.94.antonio@gmail.com', 'aasafa', 'asdfasf'),
('123456w78B', 'B18756676', 'jm@gsdgsdsgamial.com', 'juan', 'nuevo'),
('12412412', 'B18756676', 'jm@gsdgsdsgamial.com', 'juan', 'nuevo'),
('87654321E', 'B18756676', 'jm.94.antonio@gmail.com', 'hollaaa', 'holaaa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `room`
--

CREATE TABLE `room` (
  `id` int(11) NOT NULL,
  `college_username` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `date_start_school` datetime NOT NULL,
  `date_end_school` datetime NOT NULL,
  `date_start_bid` datetime NOT NULL,
  `date_end_bid` datetime NOT NULL,
  `floor` int(11) NOT NULL,
  `size` int(11) NOT NULL,
  `picture1` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `picture2` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `picture3` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `tv` tinyint(1) NOT NULL,
  `bath` tinyint(1) NOT NULL,
  `desk` tinyint(1) NOT NULL,
  `wardrove` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `room`
--

INSERT INTO `room` (`id`, `college_username`, `name`, `price`, `date_start_school`, `date_end_school`, `date_start_bid`, `date_end_bid`, `floor`, `size`, `picture1`, `picture2`, `picture3`, `tv`, `bath`, `desk`, `wardrove`) VALUES
(16, 'B18756676', 'room5', 1800, '2017-09-01 00:00:00', '2018-06-30 00:00:00', '2017-01-18 00:00:00', '2017-02-02 00:00:00', 1, 20, '35cf01580edfdde1e494f95678836fd2.jpg', '3f1ee2b7772ac8710cd2e34391466a1b.jpg', 'b4b9a7c026ee06f336ff4a0a866d03b5.jpg', 1, 1, 0, 0),
(17, 'B18756676', 'room6', 800, '2017-08-26 00:00:00', '2018-07-26 00:00:00', '2017-02-09 00:00:00', '2017-02-09 00:00:00', 1, 20, 'a6caa9570ce36f6960d91b036fc89c28.jpg', '89d73aaae334992c9ead57acc34ec75e.jpg', 'd69a1fdcc4a557b7f7e9f0906be37fd8.jpg', 1, 1, 0, 0),
(18, 'B18756676', '7room', 500, '2017-08-26 00:00:00', '2018-07-26 00:00:00', '2017-01-18 00:00:00', '2017-02-15 00:00:00', 1, 20, '32257b38bea73960739c16fc8bbd132a.jpg', '45c872837ae1133117315ba459b6a887.jpg', '71e3ca6d50fd67fbe2d154201c287130.jpg', 1, 1, 0, 0),
(19, 'B18756676', 'r8', 500, '2017-08-26 00:00:00', '2018-07-26 00:00:00', '2017-01-18 00:00:00', '2017-02-15 00:00:00', 1, 20, '92f21f32d96f64cc431b2b4066fd3d13.jpg', 'c5a920cf23f292afd072f86997943897.jpg', '0ba62d6ec07456ef0fc8586af446027c.jpg', 1, 1, 0, 0),
(21, 'B18756676', 'room10', 500, '2017-08-26 00:00:00', '2018-07-26 00:00:00', '2017-01-18 00:00:00', '2017-02-15 00:00:00', 1, 20, '88821bd681b347d38738b8cf1b23d256.jpg', 'fb01924d0c22f6bb947651874804dc61.jpg', '131c94b66466b357c5cdda7e86575fdd.jpg', 1, 1, 0, 0),
(22, 'B18756676', 'room18', 500, '2017-08-26 00:00:00', '2018-07-26 00:00:00', '2017-01-18 00:00:00', '2017-02-15 00:00:00', 1, 20, 'f0fa3a5c5738e3591cb8823d4c9b71f3.jpg', '5d7607fd220b8ed6583cdd3b2f00e332.jpg', 'e6b99a1ddb2b95def9e50fb416799579.jpg', 1, 1, 0, 0),
(23, 'B18756676', 'room11', 500, '2017-08-26 00:00:00', '2018-07-26 00:00:00', '2017-01-18 00:00:00', '2017-02-15 00:00:00', 1, 20, 'c707128c80c13e3d373f187a477f7b56.jpg', '', '6015cf47e1cb6993511423d544e00a9e.jpg', 1, 1, 0, 0),
(24, 'B18756676', 'room1', 1600, '2017-08-26 00:00:00', '2018-07-26 00:00:00', '2017-01-18 00:00:00', '2017-02-15 00:00:00', 1, 20, '58c72081eb6f858be0f0b12e9a59e3a4.jpg', '6e3574b33436ca67af3cbda9fbe01593.jpg', 'e2ffb26cde795312c015f2274452db83.jpg', 1, 1, 1, 0),
(25, 'B18756676', 'room33', 50, '2017-08-26 00:00:00', '2018-07-26 00:00:00', '2017-01-18 00:00:00', '2017-02-15 00:00:00', 1, 20, '4af5653fb57d641dfcce40218fdce5a9.jpg', '40b7574e8f09d7d90fd496c47948a504.jpg', '49cd8064b939bf328fe1a22540772dd0.jpg', 1, 1, 1, 0),
(26, 'B18756676', 'room145', 500, '2017-08-26 00:00:00', '2018-07-26 00:00:00', '2017-01-18 00:00:00', '2017-02-15 00:00:00', 1, 20, '83286d1c2495564847abc2351ebcb2f0.jpg', '61ec83a540bf844c0155ef27bd7627ff.jpg', '9a3b54a8080fca7c9c35b2174210d46b.jpg', 1, 1, 1, 0),
(27, 'B18756676', 'room1', 500, '2017-08-26 00:00:00', '2018-07-26 00:00:00', '2017-01-18 00:00:00', '2017-02-15 00:00:00', 1, 20, 'de9a724964786ee80293224cb1646b21.jpg', '9b64c65ad869402f2d1f05ee3d493b0f.jpg', '6f697af442926ebeaa4f193a155bde99.jpg', 1, 1, 1, 0),
(28, 'B18756676', 'room155', 500, '2017-08-26 00:00:00', '2018-07-26 00:00:00', '2017-02-01 00:00:00', '2017-02-01 00:00:00', 1, 20, '3f8ee6adc50a0d6c03a74682e1509705.jpg', '6c28fa3fc8a375a63e557bc9ff9df3d8.jpg', 'db24c02f77ea9066019894a0cfada8f2.jpg', 1, 1, 1, 0),
(29, 'B18756676', 'room[number]', 34, '2017-02-02 00:00:00', '2017-02-05 00:00:00', '2017-01-23 00:00:00', '2017-01-31 00:00:00', 1, 15, 'ed924939bff5f0920eddf3351b253c0d.jpg', 'ae59195fd335a0e3eed2b16836bedaed.jpg', 'ebf9c8bf8dbaa0e75b42f1e43c630690.jpg', 0, 1, 1, 0),
(30, 'B18756676', 'room[number]', 34, '2017-02-02 00:00:00', '2017-02-05 00:00:00', '2017-01-23 00:00:00', '2017-01-31 00:00:00', 1, 15, '746b387a43f61dc0f821484dc6ac58ee.jpg', '5dab58fa6b7a409057d91a1b4e94c7f2.jpg', 'ec2e3feb10149454d6f6ced0b33c2953.jpg', 0, 1, 1, 0),
(31, 'B18756676', 'room[number]', 34, '2017-02-02 00:00:00', '2017-02-05 00:00:00', '2017-01-23 00:00:00', '2017-01-31 00:00:00', 1, 15, 'cf34b09009a156c95e57eb76715c2ff4.jpg', '7816c1bde2197f9ed99b86a107f42b8b.jpg', 'd3426b97be84916b864bffad7cdd11c7.jpg', 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `students`
--

CREATE TABLE `students` (
  `username` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  `creation_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `students`
--

INSERT INTO `students` (`username`, `password`, `email`, `name`, `is_active`, `creation_date`) VALUES
('12345678A', '$2y$13$xqavJniqKVrgXgISBRXt.Ow1ztY8UCgdBymCeRep40r4MdvheAIqe', 'antoniou@gmail.com', 'antonio', 1, '2017-01-10 18:31:32'),
('87654321A', '$2y$13$kHfdnRWdRaJVk0eP/Fc32.u61SPNR88/5O/33nOHrdqNd4bppXnVO', 'jm@sgs.com', 'antonio', 1, '2017-02-09 19:17:57');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `agreement`
--
ALTER TABLE `agreement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_2E655A244BD54AEC` (`student_username`),
  ADD KEY `IDX_2E655A2454177093` (`room_id`);

--
-- Indices de la tabla `bank`
--
ALTER TABLE `bank`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D860BF7A16A289D1` (`college_username`);

--
-- Indices de la tabla `bid`
--
ALTER TABLE `bid`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_4AF2B3F34BD54AEC` (`student_username`),
  ADD KEY `IDX_4AF2B3F354177093` (`room_id`);

--
-- Indices de la tabla `colleges`
--
ALTER TABLE `colleges`
  ADD PRIMARY KEY (`username`);

--
-- Indices de la tabla `incidence`
--
ALTER TABLE `incidence`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_170604174BD54AEC` (`student_username`);

--
-- Indices de la tabla `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B6BD307F4BD54AEC` (`student_username`),
  ADD KEY `IDX_B6BD307F16A289D1` (`college_username`);

--
-- Indices de la tabla `rent`
--
ALTER TABLE `rent`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_2784DCC4BD54AEC` (`student_username`);

--
-- Indices de la tabla `responsiblePerson`
--
ALTER TABLE `responsiblePerson`
  ADD PRIMARY KEY (`dni`),
  ADD KEY `IDX_CB0E5D4A16A289D1` (`college_username`);

--
-- Indices de la tabla `room`
--
ALTER TABLE `room`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_729F519B16A289D1` (`college_username`);

--
-- Indices de la tabla `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `agreement`
--
ALTER TABLE `agreement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT de la tabla `bank`
--
ALTER TABLE `bank`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT de la tabla `bid`
--
ALTER TABLE `bid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT de la tabla `incidence`
--
ALTER TABLE `incidence`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT de la tabla `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;
--
-- AUTO_INCREMENT de la tabla `rent`
--
ALTER TABLE `rent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;
--
-- AUTO_INCREMENT de la tabla `room`
--
ALTER TABLE `room`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;
--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `agreement`
--
ALTER TABLE `agreement`
  ADD CONSTRAINT `FK_2E655A244BD54AEC` FOREIGN KEY (`student_username`) REFERENCES `students` (`username`),
  ADD CONSTRAINT `FK_2E655A2454177093` FOREIGN KEY (`room_id`) REFERENCES `room` (`id`);

--
-- Filtros para la tabla `bank`
--
ALTER TABLE `bank`
  ADD CONSTRAINT `FK_D860BF7A16A289D1` FOREIGN KEY (`college_username`) REFERENCES `colleges` (`username`);

--
-- Filtros para la tabla `bid`
--
ALTER TABLE `bid`
  ADD CONSTRAINT `FK_4AF2B3F34BD54AEC` FOREIGN KEY (`student_username`) REFERENCES `students` (`username`),
  ADD CONSTRAINT `FK_4AF2B3F354177093` FOREIGN KEY (`room_id`) REFERENCES `room` (`id`);

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

--
-- Filtros para la tabla `responsiblePerson`
--
ALTER TABLE `responsiblePerson`
  ADD CONSTRAINT `FK_CB0E5D4A16A289D1` FOREIGN KEY (`college_username`) REFERENCES `colleges` (`username`);

--
-- Filtros para la tabla `room`
--
ALTER TABLE `room`
  ADD CONSTRAINT `FK_729F519B16A289D1` FOREIGN KEY (`college_username`) REFERENCES `colleges` (`username`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
