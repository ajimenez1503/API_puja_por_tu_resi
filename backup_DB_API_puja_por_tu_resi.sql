-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 15-04-2017 a las 12:50:58
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
(17, '87654321A', 17, 800, '2017-08-26 00:00:00', '2018-07-26 00:00:00', 'cf7dd4a104cf8a6c01471d7542699f82.pdf', '0f7f4e3faf9a2ac186a0a159d8b53f34.pdf', '2017-02-09 19:23:45'),
(18, '15426661A', 19, 500, '2017-07-01 00:00:00', '2017-08-31 00:00:00', '9c7029693cbf128989f60091769e1817.pdf', 'f6b09be962b65ff1f8d8596c3e70db78.pdf', '2017-04-05 19:45:46'),
(19, 'test', 30, 100, '2017-04-14 00:00:00', '2018-08-31 00:00:00', 'file.pdf', 'file.pdf', '2017-04-14 00:00:00');

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
(16, 'B18756676', 'ES7620770024003102575766', 'DABAIE2D', 'Residencia XD', 1),
(17, 'B18756676', 'ES7620770024003102575766', 'DABAIE2D', 'Residencia XD', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `bid`
--

CREATE TABLE `bid` (
  `id` int(11) NOT NULL,
  `student_username` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `room_id` int(11) DEFAULT NULL,
  `date_start_school` datetime NOT NULL,
  `date_end_school` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `bid`
--

INSERT INTO `bid` (`id`, `student_username`, `room_id`, `date_start_school`, `date_end_school`) VALUES
(7, '15426661B', 28, '2017-04-26 00:00:00', '2017-04-27 00:00:00'),
(8, 'test', 30, '2017-04-26 00:00:00', '2017-04-27 00:00:00');

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
('B18756676', '$2y$13$tVR0M8lYWAfbKwJY5uLMuOepGiefq3PEy8uKFozbyfZefwtv6ow22', 'residenciaxd@gmai.com', 1, 'Residencia XD', 'Calle Obispo Hurtado, 12, 18002 Granada, España', 37.17401, -3.6066789999999855, '697268644', 'http://127.0.0.1:8000/ProfileCollege/updateProfile/', 1, 1, 1, 0, 0, 1, 1, 0),
('F95161196', '$2y$13$GGhMVySkCPzhCCQKw/FBweYCRCG8thhSVzKseF96jH2U0Co38Offm', 'jm.94.antonio@gmail.com', 1, 'ISABEL JIMÉNEZ', 'Calle Estación, 1, 50500 Valverde, Zaragoza, España', 41.9786272, -1.8613904000000048, '123456789', 'https://github.com/softwarejimenez/web_puja_por_tu_resi', 1, 0, 0, 0, 0, 0, 1, 0),
('test', '$2y$13$tVR0M8lYWAfbKwJY5uLMuOepGiefq3PEy8uKFozbyfZefwtv6ow22', 'new_test_email@gmail.com', 1, 'test', 'test', 0, 0, 'test', 'http://test', 1, 1, 1, 1, 1, 1, 1, 1);

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
(3, '87654321A', 'IN PROGRESS', 'El baño huele mal', '010e00f6e19b777011c88403147b1e53.jpg', '2017-02-15 20:19:28'),
(4, '87654321A', 'OPEN', 'Necesito una silla nueva. La mia no funciona.', 'ba96d281e518b61853012ef9050c20fc.jpg', '2017-02-15 20:19:28'),
(5, '87654321A', 'OPEN', 'No se ve la tv correctamente.', '4e8c207f9e7c1f4a7b0b4a56ff0b6172.jpg', '2017-02-15 20:36:11'),
(6, '12345678A', 'DONE', 'La puerta se queda entre abierta.', '297677123c02644aac0de9cbb1b54328.jpg', '2017-03-01 20:02:52'),
(7, '87654321A', 'OPEN', 'la ventana  esta rota ', '43f9d4a5e26321821889b8004afb0e79.jpg', '2017-04-13 10:00:35'),
(8, '12345678A', 'OPEN', 'algo no funciona', '4d20db5edb83207b07e85e79c3d1fefa.jpg', '2017-04-14 07:53:07'),
(9, '12345678A', 'OPEN', 'algo no funciona', 'd2a83dba597e4b83778e594c1053f13c.jpg', '2017-04-14 07:58:52'),
(10, '12345678A', 'OPEN', 'algo no funciona', '456f00c1653e979066a23460b66a909f.jpg', '2017-04-14 08:00:22'),
(28, 'test', 'IN PROGRESS', 'algo no funciona', 'bc552c4f91e00b68ec30a4be251bcf5a.png', '2017-04-14 11:09:35'),
(29, 'test', 'IN PROGRESS', 'algo no funciona', '7d61d4f3e1f7c336c4d07843432e6f84.png', '2017-04-14 12:17:31');

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
(36, '12345678A', 'B18756676', 0, 1, 'Os damos la bienvenida a nuestra residencia XD, espero que disfruteis vuestro curso academico junto a nosotros y os convirtais en unos verdaderos veteranos. \r\nUn saludo a todos. ', NULL, '2017-04-13 10:26:03', 'ROLE_COLLEGE'),
(37, '87654321A', 'B18756676', 1, 1, 'Os damos la bienvenida a nuestra residencia XD, espero que disfruteis vuestro curso academico junto a nosotros y os convirtais en unos verdaderos veteranos. \r\nUn saludo a todos. ', NULL, '2017-04-13 10:26:03', 'ROLE_COLLEGE'),
(38, '15426661A', 'B18756676', 0, 1, 'Os damos la bienvenida a nuestra residencia XD, espero que disfruteis vuestro curso academico junto a nosotros y os convirtais en unos verdaderos veteranos. \r\nUn saludo a todos. ', NULL, '2017-04-13 10:26:03', 'ROLE_COLLEGE'),
(39, '87654321A', 'B18756676', 1, 1, 'Muchas gracias por todo. Yo tambien espero estar muy feliz aqui. ', NULL, '2017-04-13 10:26:58', 'ROLE_STUDENT'),
(40, '87654321A', 'B18756676', 1, 1, 'Me gustaría preguntarle de como puedo poner la lavadora y cual es la clave del WIFI. Un saludo', NULL, '2017-04-13 10:29:07', 'ROLE_STUDENT'),
(41, '87654321A', 'B18756676', 0, 1, 'Para la lavadora, unicamente tiene que acercarte a la puerta y registrarte. Y la contraseña del wifi es "RESIDENCIAXD" Si tienes algun problema puedes pasarte por la recepcón.', NULL, '2017-04-13 10:33:52', 'ROLE_COLLEGE'),
(42, 'test', 'test', 1, 0, 'message', 'e66268ae6478c1107a77bbdafa68168e.png', '2017-04-14 09:25:40', 'ROLE_STUDENT'),
(43, 'test', 'test', 1, 0, 'message', '0e8e0e26911aa1486732a338207e7132.png', '2017-04-14 09:28:52', 'ROLE_STUDENT'),
(44, 'test', 'test', 1, 0, 'message', '7b20965e95c310b6000a5cda1d927408.png', '2017-04-14 09:29:21', 'ROLE_STUDENT'),
(45, 'test', 'test', 1, 0, 'message', 'b35e7cbf1842761bbb4f09ae738cf7de.png', '2017-04-14 09:29:43', 'ROLE_STUDENT'),
(46, 'test', 'test', 1, 0, 'message', 'a17b31656b529be2dd5fe4eaf65251d5.png', '2017-04-14 09:29:57', 'ROLE_STUDENT'),
(47, 'test', 'test', 1, 0, 'message', '808d7498539a9b8c6b273a0802d0deed.png', '2017-04-14 09:34:33', 'ROLE_STUDENT'),
(48, 'test', 'test', 1, 0, 'message', '9f5fbe8a4face5b67790d07f35a01f87.png', '2017-04-14 11:09:35', 'ROLE_STUDENT'),
(49, 'test', 'test', 1, 0, 'message', 'f4afaed15f4686b569975f8fe1151020.png', '2017-04-14 12:17:31', 'ROLE_STUDENT');

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
(71, '87654321A', 1, 800, '2017-10-26 00:00:00', '189d9fe58587b3b10b55edb7bf71c692.pdf', '2017-03-04 14:13:43', '7561'),
(72, '87654321A', 0, 800, '2017-11-26 00:00:00', NULL, NULL, NULL),
(73, '87654321A', 0, 800, '2017-12-26 00:00:00', NULL, NULL, NULL),
(74, '87654321A', 0, 800, '2018-01-26 00:00:00', NULL, NULL, NULL),
(75, '87654321A', 0, 800, '2018-02-26 00:00:00', NULL, NULL, NULL),
(76, '87654321A', 0, 800, '2018-03-26 00:00:00', NULL, NULL, NULL),
(77, '87654321A', 0, 800, '2018-04-26 00:00:00', NULL, NULL, NULL),
(78, '87654321A', 0, 800, '2018-05-26 00:00:00', NULL, NULL, NULL),
(79, '87654321A', 0, 800, '2018-06-26 00:00:00', NULL, NULL, NULL),
(80, '15426661A', 0, 500, '2017-07-01 00:00:00', NULL, NULL, NULL),
(81, '15426661A', 0, 500, '2017-08-01 00:00:00', NULL, NULL, NULL);

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
('11111111M', 'B18756676', 'jm.94.antonio@gmail.com', 'Luis', 'Cocinero'),
('12123456A', 'B18756676', 'jm.94.antonio@gmail.com', 'Marcos', 'Recepcionista'),
('12345678U', 'B18756676', 'jm.94.antonio@gmail.com', 'Carlos', 'Secretario'),
('123456w78B', 'B18756676', 'jm@gsdgsdsgamial.com', 'Pedro', 'Manager'),
('12412412', 'B18756676', 'jm@gsdgsdsgamial.com', 'Luisa', 'Limpiadora'),
('87654321E', 'B18756676', 'jm.94.antonio@gmail.com', 'David', 'Mantenimiento');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `room`
--

CREATE TABLE `room` (
  `id` int(11) NOT NULL,
  `college_username` varchar(9) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `price` double NOT NULL,
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

INSERT INTO `room` (`id`, `college_username`, `name`, `price`, `floor`, `size`, `picture1`, `picture2`, `picture3`, `tv`, `bath`, `desk`, `wardrove`) VALUES
(16, 'B18756676', 'room16', 1800, 1, 20, 'room1.jpg', 'room2.jpg', 'room3.jpg', 1, 1, 0, 0),
(17, 'B18756676', 'room17', 800, 1, 20, 'room3.jpg', 'room1.jpg', 'room2.jpg', 1, 1, 0, 0),
(18, 'B18756676', 'room18', 500, 1, 20, 'room3.jpg', 'room2.jpg', 'room1.jpg', 1, 1, 0, 0),
(19, 'B18756676', 'room19', 500, 1, 20, 'room2.jpg', 'room1.jpg', 'room3.jpg', 1, 1, 0, 0),
(21, 'B18756676', 'room21', 500, 1, 20, 'room1.jpg', 'room2.jpg', 'room2.jpg', 1, 1, 0, 0),
(22, 'B18756676', 'room22', 500, 1, 20, 'room2.jpg', 'room2.jpg', 'room3.jpg', 0, 1, 0, 1),
(23, 'B18756676', 'room23', 500, 1, 20, 'room3.jpg', 'room1.jpg', 'room2.jpg', 1, 1, 0, 0),
(24, 'B18756676', 'room24', 1600, 1, 20, 'room1.jpg', 'room2.jpg', 'room3.jpg', 1, 1, 1, 0),
(25, 'B18756676', 'room25', 50, 1, 20, 'room1.jpg', 'room2.jpg', 'room3.jpg', 1, 1, 1, 0),
(26, 'B18756676', 'room26', 500, 1, 20, 'room1.jpg', 'room2.jpg', 'room3.jpg', 1, 1, 1, 1),
(27, 'B18756676', 'room27', 500, 1, 20, 'room2.jpg', 'room1.jpg', 'room3.jpg', 1, 1, 1, 0),
(28, 'B18756676', 'room28', 500, 1, 20, 'room3.jpg', 'room2.jpg', 'room1.jpg', 1, 1, 1, 0),
(29, 'B18756676', 'room29', 34, 1, 15, 'room1.jpg', 'room3.jpg', 'room2.jpg', 0, 1, 1, 0),
(30, 'test', 'test', 100, 1, 1, 'room1.jpg', 'room3.jpg', 'room2.jpg', 1, 1, 1, 1);

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
('12345678A', '$2y$13$xqavJniqKVrgXgISBRXt.Ow1ztY8UCgdBymCeRep40r4MdvheAIqe', 'antonio@gmail.com', 'antonio', 1, '2017-01-10 18:31:32'),
('15426661A', '$2y$13$YzPREGqWagLCq8O7rAvmYOemC9hhK8UMbnRvZY/brrRwcXPcZp2pO', 'Maria@gmail.com', 'Maria', 1, '2017-04-04 13:01:15'),
('15426661B', '$2y$13$kAhOvlaKlEHzdhYqpdH.JenKvP8X3gLF9VoFTbQ4dyF506ENrNEie', 'isabel@gmail.com', 'ISABEL JIMÉNEZ', 1, '2017-04-08 17:08:43'),
('87654321A', '$2y$13$kHfdnRWdRaJVk0eP/Fc32.u61SPNR88/5O/33nOHrdqNd4bppXnVO', 'carlos@gmail.com', 'carlos', 1, '2017-02-09 19:17:57'),
('test', '$2y$13$kHfdnRWdRaJVk0eP/Fc32.u61SPNR88/5O/33nOHrdqNd4bppXnVO', 'new_test_email@gmail.com', 'test', 1, '2017-02-09 19:17:57');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
--
-- AUTO_INCREMENT de la tabla `bank`
--
ALTER TABLE `bank`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT de la tabla `bid`
--
ALTER TABLE `bid`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT de la tabla `incidence`
--
ALTER TABLE `incidence`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;
--
-- AUTO_INCREMENT de la tabla `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;
--
-- AUTO_INCREMENT de la tabla `rent`
--
ALTER TABLE `rent`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;
--
-- AUTO_INCREMENT de la tabla `room`
--
ALTER TABLE `room`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;
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
