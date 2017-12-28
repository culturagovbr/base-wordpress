SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Table structure for table `wpminc_site`
--

DROP TABLE IF EXISTS `wpminc_site`;
CREATE TABLE `wpminc_site` (
  `id` bigint(20) NOT NULL,
  `domain` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `path` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wpminc_site`
--

INSERT INTO `wpminc_site` (`id`, `domain`, `path`) VALUES
(1, 'base-wp.cultura.gov.br', '/');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wpminc_site`
--
ALTER TABLE `wpminc_site`
  ADD PRIMARY KEY (`id`),
  ADD KEY `domain` (`domain`(140),`path`(51));

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wpminc_site`
--
ALTER TABLE `wpminc_site`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;


--
-- Table structure for table `wpminc_sitemeta`
--
DROP TABLE IF EXISTS `wpminc_sitemeta`;
CREATE TABLE `wpminc_sitemeta` (
  `meta_id` bigint(20) NOT NULL,
  `site_id` bigint(20) NOT NULL DEFAULT '0',
  `meta_key` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `meta_value` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wpminc_sitemeta`
--

INSERT INTO `wpminc_sitemeta` (`meta_id`, `site_id`, `meta_key`, `meta_value`) VALUES
(1, 1, 'site_name', 'Fazenda Wordpress MinC '),
(2, 1, 'siteurl', 'http://base-wp.cultura.gov.br/'),
(3, 1, 'dm_cname', 'base-wp.cultura.gov.br');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wpminc_sitemeta`
--
ALTER TABLE `wpminc_sitemeta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `meta_key` (`meta_key`(191)),
  ADD KEY `site_id` (`site_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wpminc_sitemeta`
--
ALTER TABLE `wpminc_sitemeta`
  MODIFY `meta_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;



--
-- Table structure for table `wpminc_options`
--
DROP TABLE IF EXISTS `wpminc_options`;
CREATE TABLE `wpminc_options` (
  `option_id` bigint(20) UNSIGNED NOT NULL,
  `option_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `option_value` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `autoload` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wpminc_options`
--

INSERT INTO `wpminc_options` (`option_id`, `option_name`, `option_value`, `autoload`) VALUES
(1, 'siteurl', 'http://base-wp.cultura.gov.br', 'yes'),
(2, 'home', 'http://base-wp.cultura.gov.br', 'yes'),
(3, 'blogname', 'Plataforma WordPress do MinC - teste', 'yes'),
(4, 'blogdescription', 'Teste', 'yes');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wpminc_options`
--
ALTER TABLE `wpminc_options`
  ADD PRIMARY KEY (`option_id`),
  ADD UNIQUE KEY `option_name` (`option_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wpminc_options`
--
ALTER TABLE `wpminc_options`
  MODIFY `option_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;


--
-- Table structure for table `wpminc_blogs`
--
DROP TABLE IF EXISTS `wpminc_blogs`;
CREATE TABLE `wpminc_blogs` (
  `blog_id` bigint(20) NOT NULL,
  `site_id` bigint(20) NOT NULL DEFAULT '0',
  `domain` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `path` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `public` tinyint(2) NOT NULL DEFAULT '1',
  `archived` tinyint(2) NOT NULL DEFAULT '0',
  `mature` tinyint(2) NOT NULL DEFAULT '0',
  `spam` tinyint(2) NOT NULL DEFAULT '0',
  `deleted` tinyint(2) NOT NULL DEFAULT '0',
  `lang_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wpminc_blogs`
--

INSERT INTO `wpminc_blogs` (`blog_id`, `site_id`, `domain`, `path`, `registered`, `last_updated`, `public`, `archived`, `mature`, `spam`, `deleted`, `lang_id`) VALUES
(1, 1, 'base-wp.cultura.gov.br', '/', '2016-07-13 15:09:26', '2017-02-17 13:44:53', 1, 0, 0, 0, 0, 0),
(2, 1, 'teste1.base-wp.cultura.gov.br', '/', '2016-07-14 18:32:00', '2016-12-27 18:33:23', 1, 0, 0, 0, 0, 0),
(3, 1, 'teste2.base-wp.cultura.gov.br', '/', '2016-07-14 21:19:40', '2016-07-14 21:19:41', 1, 0, 0, 0, 0, 0),
(4, 1, 'teste3.base-wp.cultura.gov.br', '/', '2016-07-19 17:30:39', '2017-02-24 15:23:12', 1, 0, 0, 0, 0, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wpminc_blogs`
--
ALTER TABLE `wpminc_blogs`
  ADD PRIMARY KEY (`blog_id`),
  ADD KEY `domain` (`domain`(50),`path`(5)),
  ADD KEY `lang_id` (`lang_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wpminc_blogs`
--
ALTER TABLE `wpminc_blogs`
  MODIFY `blog_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;


--
-- Table structure for table `wpminc_domain_mapping`
--
DROP TABLE IF EXISTS `wpminc_domain_mapping`;
CREATE TABLE `wpminc_domain_mapping` (
  `id` bigint(20) NOT NULL,
  `blog_id` bigint(20) NOT NULL,
  `domain` varchar(255) NOT NULL,
  `active` tinyint(4) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wpminc_domain_mapping`
--

INSERT INTO `wpminc_domain_mapping` (`id`, `blog_id`, `domain`, `active`) VALUES
(1, 2, 'teste1.cultura.gov.br', 0),
(2, 3, 'teste2.cultura.gov.br', 1),
(3, 4, 'teste3.cultura.gov.br', 1);
--
-- Indexes for dumped tables
--

--
-- Indexes for table `wpminc_domain_mapping`
--
ALTER TABLE `wpminc_domain_mapping`
  ADD PRIMARY KEY (`id`),
  ADD KEY `blog_id` (`blog_id`,`domain`,`active`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wpminc_domain_mapping`
--
ALTER TABLE `wpminc_domain_mapping`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;


--
-- Table structure for table `wpminc_2_options`
--
DROP TABLE IF EXISTS `wpminc_2_options`;
CREATE TABLE `wpminc_2_options` (
  `option_id` bigint(20) UNSIGNED NOT NULL,
  `option_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `option_value` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `autoload` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wpminc_2_options`
--

INSERT INTO `wpminc_2_options` (`option_id`, `option_name`, `option_value`, `autoload`) VALUES
(1, 'siteurl', 'http://teste1.base-wp.cultura.gov.br', 'yes'),
(2, 'home', 'http://teste1.base-wp.cultura.gov.br', 'yes'),
(3, 'blogname', 'Teste 1', 'yes'),
(4, 'blogdescription', 'Teste 1 blog é um teste', 'yes'),
(5, 'users_can_register', '0', 'yes'),
(6, 'admin_email', 'teste1@cultura.gov.br', 'yes');

-- Indexes for dumped tables
--

--
-- Indexes for table `wpminc_2_options`
--
ALTER TABLE `wpminc_2_options`
  ADD PRIMARY KEY (`option_id`),
  ADD UNIQUE KEY `option_name` (`option_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wpminc_2_options`
--
ALTER TABLE `wpminc_2_options`
  MODIFY `option_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;



--
-- Table structure for table `wpminc_3_options`
--
DROP TABLE IF EXISTS `wpminc_3_options`;
CREATE TABLE `wpminc_3_options` (
  `option_id` bigint(20) UNSIGNED NOT NULL,
  `option_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `option_value` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `autoload` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wpminc_3_options`
--

INSERT INTO `wpminc_3_options` (`option_id`, `option_name`, `option_value`, `autoload`) VALUES
(1, 'siteurl', 'http://teste2.base-wp.cultura.gov.br', 'yes'),
(2, 'home', 'http://teste2.base-wp.cultura.gov.br', 'yes'),
(3, 'blogname', 'Teste 2', 'yes'),
(4, 'blogdescription', 'Teste 2 blog é um teste', 'yes'),
(5, 'users_can_register', '0', 'yes'),
(6, 'admin_email', 'teste2@cultura.gov.br', 'yes');

-- Indexes for dumped tables
--

--
-- Indexes for table `wpminc_3_options`
--
ALTER TABLE `wpminc_3_options`
  ADD PRIMARY KEY (`option_id`),
  ADD UNIQUE KEY `option_name` (`option_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wpminc_3_options`
--
ALTER TABLE `wpminc_3_options`
  MODIFY `option_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;


--
-- Table structure for table `wpminc_4_options`
--
DROP TABLE IF EXISTS `wpminc_4_options`;
CREATE TABLE `wpminc_4_options` (
  `option_id` bigint(20) UNSIGNED NOT NULL,
  `option_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `option_value` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `autoload` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wpminc_4_options`
--

INSERT INTO `wpminc_4_options` (`option_id`, `option_name`, `option_value`, `autoload`) VALUES
(1, 'siteurl', 'http://teste3.base-wp.cultura.gov.br', 'yes'),
(2, 'home', 'http://teste3.base-wp.cultura.gov.br', 'yes'),
(3, 'blogname', 'Teste 3', 'yes'),
(4, 'blogdescription', 'Teste 3 blog é um teste', 'yes'),
(5, 'users_can_register', '0', 'yes'),
(6, 'admin_email', 'teste2@cultura.gov.br', 'yes');

-- Indexes for dumped tables
--

--
-- Indexes for table `wpminc_4_options`
--
ALTER TABLE `wpminc_4_options`
  ADD PRIMARY KEY (`option_id`),
  ADD UNIQUE KEY `option_name` (`option_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wpminc_4_options`
--
ALTER TABLE `wpminc_4_options`
  MODIFY `option_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;



-- --------------------------------------------------------

--
-- Table structure for table `wpminc_2_posts`
--
DROP TABLE IF EXISTS `wpminc_2_posts`;
CREATE TABLE `wpminc_2_posts` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `post_author` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_excerpt` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `post_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `post_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `to_ping` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `pinged` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_parent` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `guid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `menu_order` int(11) NOT NULL DEFAULT '0',
  `post_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_count` bigint(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wpminc_2_posts`
--

INSERT INTO `wpminc_2_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1, 1, '2016-07-14 21:19:41', '2016-07-14 21:19:41', 'Bem-vindo ao <a href=\"http://teste1.cultura.gov.br/\">BaseWP MinC sites</a>. Esse é o seu primeiro post. Edite-o ou exclua-o, e então comece a escrever!', 'Olá, mundo!', '', 'publish', 'open', 'open', '', 'ola-mundo', '', '', '2016-07-14 21:19:41', '2016-07-14 21:19:41', '', 0, 'http://artenarua.base-wp.cultura.gov.br/?p=1', 0, 'post', '', 1),
(2, 1, '2016-07-14 21:19:41', '2016-07-14 21:19:41', 'Esta é uma página de exemplo. É diferente de um post porque ela ficará em um local e será exibida na navegação do seu site (na maioria dos temas). A maioria das pessoas começa com uma página de introdução aos potenciais visitantes do site. Ela pode ser assim:\n\n<blockquote>Olá! Eu sou um bike courrier de dia, ator amador à noite e este é meu blog. Eu moro em São Paulo, tenho um cachorro chamado Tonico e eu gosto de caipirinhas. (E de ser pego pela chuva.)</blockquote>\n\nou assim:\n\n<blockquote>A XYZ foi fundada em 1971 e desde então vem proporcionando produtos de qualidade a seus clientes. Localizada em Valinhos, XYZ emprega mais de 2.000 pessoas e faz várias contribuições para a comunidade local.</blockquote>\nComo um novo usuário do WordPress, você deve ir até o <a href=\"http://teste1.base-wp.cultura.gov.br/wp-admin/\">seu painel</a> para excluir essa página e criar novas páginas com seu próprio conteúdo. Divirta-se!', 'Página de Exemplo', '', 'publish', 'closed', 'open', '', 'pagina-exemplo', '', '', '2016-07-14 21:19:41', '2016-07-14 21:19:41', '', 0, 'http://teste1.base-wp.cultura.gov.br/?page_id=2', 0, 'page', '', 0),
(3, 1, '2016-07-14 21:20:25', '0000-00-00 00:00:00', '', 'Rascunho automático', '', 'auto-draft', 'open', 'open', '', '', '', '', '2016-07-14 21:20:25', '0000-00-00 00:00:00', '', 0, 'http://teste1.base-wp.cultura.gov.br/?p=3', 0, 'post', '', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wpminc_2_posts`
--
ALTER TABLE `wpminc_2_posts`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `post_name` (`post_name`(191)),
  ADD KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  ADD KEY `post_parent` (`post_parent`),
  ADD KEY `post_author` (`post_author`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wpminc_2_posts`
--
ALTER TABLE `wpminc_2_posts`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;



--
-- Table structure for table `wpminc_3_posts`
--
DROP TABLE IF EXISTS `wpminc_3_posts`;
CREATE TABLE `wpminc_3_posts` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `post_author` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_excerpt` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `post_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `post_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `to_ping` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `pinged` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_parent` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `guid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `menu_order` int(11) NOT NULL DEFAULT '0',
  `post_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_count` bigint(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wpminc_3_posts`
--

INSERT INTO `wpminc_3_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1, 1, '2016-07-14 21:19:41', '2016-07-14 21:19:41', 'Bem-vindo ao <a href=\"http://teste2.cultura.gov.br/\">BaseWP MinC sites</a>. Esse é o seu primeiro post. Edite-o ou exclua-o, e então comece a escrever!', 'Olá, mundo!', '', 'publish', 'open', 'open', '', 'ola-mundo', '', '', '2016-07-14 21:19:41', '2016-07-14 21:19:41', '', 0, 'http://artenarua.base-wp.cultura.gov.br/?p=1', 0, 'post', '', 1),
(2, 1, '2016-07-14 21:19:41', '2016-07-14 21:19:41', 'Esta é uma página de exemplo. É diferente de um post porque ela ficará em um local e será exibida na navegação do seu site (na maioria dos temas). A maioria das pessoas começa com uma página de introdução aos potenciais visitantes do site. Ela pode ser assim:\n\n<blockquote>Olá! Eu sou um bike courrier de dia, ator amador à noite e este é meu blog. Eu moro em São Paulo, tenho um cachorro chamado Tonico e eu gosto de caipirinhas. (E de ser pego pela chuva.)</blockquote>\n\nou assim:\n\n<blockquote>A XYZ foi fundada em 1971 e desde então vem proporcionando produtos de qualidade a seus clientes. Localizada em Valinhos, XYZ emprega mais de 2.000 pessoas e faz várias contribuições para a comunidade local.</blockquote>\nComo um novo usuário do WordPress, você deve ir até o <a href=\"http://teste2.base-wp.cultura.gov.br/wp-admin/\">seu painel</a> para excluir essa página e criar novas páginas com seu próprio conteúdo. Divirta-se!', 'Página de Exemplo', '', 'publish', 'closed', 'open', '', 'pagina-exemplo', '', '', '2016-07-14 21:19:41', '2016-07-14 21:19:41', '', 0, 'http://teste2.base-wp.cultura.gov.br/?page_id=2', 0, 'page', '', 0),
(3, 1, '2016-07-14 21:20:25', '0000-00-00 00:00:00', '', 'Rascunho automático', '', 'auto-draft', 'open', 'open', '', '', '', '', '2016-07-14 21:20:25', '0000-00-00 00:00:00', '', 0, 'http://teste2.base-wp.cultura.gov.br/?p=3', 0, 'post', '', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wpminc_3_posts`
--
ALTER TABLE `wpminc_3_posts`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `post_name` (`post_name`(191)),
  ADD KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  ADD KEY `post_parent` (`post_parent`),
  ADD KEY `post_author` (`post_author`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wpminc_3_posts`
--
ALTER TABLE `wpminc_3_posts`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;




--
-- Table structure for table `wpminc_4_posts`
--
DROP TABLE IF EXISTS `wpminc_4_posts`;
CREATE TABLE `wpminc_4_posts` (
  `ID` bigint(20) UNSIGNED NOT NULL,
  `post_author` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_title` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_excerpt` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `post_password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `post_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `to_ping` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `pinged` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `post_parent` bigint(20) UNSIGNED NOT NULL DEFAULT '0',
  `guid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `menu_order` int(11) NOT NULL DEFAULT '0',
  `post_type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `comment_count` bigint(20) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wpminc_4_posts`
--

INSERT INTO `wpminc_4_posts` (`ID`, `post_author`, `post_date`, `post_date_gmt`, `post_content`, `post_title`, `post_excerpt`, `post_status`, `comment_status`, `ping_status`, `post_password`, `post_name`, `to_ping`, `pinged`, `post_modified`, `post_modified_gmt`, `post_content_filtered`, `post_parent`, `guid`, `menu_order`, `post_type`, `post_mime_type`, `comment_count`) VALUES
(1, 1, '2016-07-14 21:19:41', '2016-07-14 21:19:41', 'Bem-vindo ao <a href=\"http://teste3.cultura.gov.br/\">BaseWP MinC sites</a>. Esse é o seu primeiro post. Edite-o ou exclua-o, e então comece a escrever!', 'Olá, mundo!', '', 'publish', 'open', 'open', '', 'ola-mundo', '', '', '2016-07-14 21:19:41', '2016-07-14 21:19:41', '', 0, 'http://artenarua.base-wp.cultura.gov.br/?p=1', 0, 'post', '', 1),
(2, 1, '2016-07-14 21:19:41', '2016-07-14 21:19:41', 'Esta é uma página de exemplo. É diferente de um post porque ela ficará em um local e será exibida na navegação do seu site (na maioria dos temas). A maioria das pessoas começa com uma página de introdução aos potenciais visitantes do site. Ela pode ser assim:\n\n<blockquote>Olá! Eu sou um bike courrier de dia, ator amador à noite e este é meu blog. Eu moro em São Paulo, tenho um cachorro chamado Tonico e eu gosto de caipirinhas. (E de ser pego pela chuva.)</blockquote>\n\nou assim:\n\n<blockquote>A XYZ foi fundada em 1971 e desde então vem proporcionando produtos de qualidade a seus clientes. Localizada em Valinhos, XYZ emprega mais de 2.000 pessoas e faz várias contribuições para a comunidade local.</blockquote>\nComo um novo usuário do WordPress, você deve ir até o <a href=\"http://teste3.base-wp.cultura.gov.br/wp-admin/\">seu painel</a> para excluir essa página e criar novas páginas com seu próprio conteúdo. Divirta-se!', 'Página de Exemplo', '', 'publish', 'closed', 'open', '', 'pagina-exemplo', '', '', '2016-07-14 21:19:41', '2016-07-14 21:19:41', '', 0, 'http://teste3.base-wp.cultura.gov.br/?page_id=2', 0, 'page', '', 0),
(3, 1, '2016-07-14 21:20:25', '0000-00-00 00:00:00', '', 'Rascunho automático', '', 'auto-draft', 'open', 'open', '', '', '', '', '2016-07-14 21:20:25', '0000-00-00 00:00:00', '', 0, 'http://teste3.base-wp.cultura.gov.br/?p=3', 0, 'post', '', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wpminc_4_posts`
--
ALTER TABLE `wpminc_4_posts`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `post_name` (`post_name`(191)),
  ADD KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  ADD KEY `post_parent` (`post_parent`),
  ADD KEY `post_author` (`post_author`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wpminc_4_posts`
--
ALTER TABLE `wpminc_4_posts`
  MODIFY `ID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;




