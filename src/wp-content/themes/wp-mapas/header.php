<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <link rel="icon" type="image/x-icon" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon.png"/>
    <title><?php bloginfo('name'); ?></title>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

<!-- <header id="site-header" class="logged-user">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <h1>
                    <a href="<?php echo home_url(); ?>">
                        <span class="sr-only"><?php bloginfo('name'); ?></span>
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-mapas-culturais.png">
                    </a>
                </h1>

            </div>
            <div class="col-sm-6 text-right">
                <a href="http://www.cultura.gov.br/" target="_blank">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-ministerio-da-cultura.png">
                </a>
            </div>
        </div>
    </div>
</header> -->

<div id="header" role="banner">
    <div class="clearfix">
        <ul id="accessibility">
            <li>
                <a accesskey="1" href="#div-conteudo" id="link-conteudo">
                    Ir para o conte&uacute;do
                    <span>1</span>
                </a>
            </li>
            <li>
                <a accesskey="3" href="#portal-searchbox" id="link-buscar">
                    Ir para a busca
                    <span>2</span>
                </a>
            </li>
            <li>
                <a accesskey="4" href="#main-footer" id="link-rodape">
                    Ir para o rodap&eacute;
                    <span>3</span>
                </a>
            </li>
        </ul>

        <ul id="portal-siteactions">
            <li>
                <a href="<?php echo home_url(); ?>/acessibilidade">Acessibilidade</a>
            </li>
            <li>
                <a href="#" class="alto_contraste">Alto Contraste</a>
            </li>
            <li>
                <a href="<?php echo home_url(); ?>/mapa-do-site">Mapa do Site</a>
            </li>
        </ul>

        <div id="logo-header-tema">
            <a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>">
                <div id="portal-title"><?php bloginfo('name'); ?></div>
                <div id="portal-description" style="color:#FFF">
                    <?php bloginfo('description'); ?>
                </div>
            </a>
        </div>

        

    </div>
    <?php
        if( has_nav_menu('primary-menu') ){
            wp_nav_menu( array( 'theme_location' => 'primary-menu' ) );
        }
    ?>
</div>