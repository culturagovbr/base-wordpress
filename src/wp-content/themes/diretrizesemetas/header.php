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
<div id="header" role="banner">
    <div class="clearfix">
        <ul id="accessibility">
            <li>
                <a accesskey="1" href="#content-wrap" id="link-conteudo">
                    Ir para o conteúdo
                    <span>1</span>
                </a>
            </li>
            <li>
                <a accesskey="2" href="#site-footer" id="link-rodape">
                    Ir para o rodapé
                    <span>2</span>
                </a>
            </li>
            <li>
                <a accesskey="3" href="#main-form" id="link-formulario">
                    Ir para o formulário
                    <span>3</span>
                </a>
            </li>
        </ul>

        <ul id="portal-siteactions">
            <!--<li>
                <a href="<?php echo home_url(); ?>/acessibilidade">Acessibilidade</a>
            </li>-->
            <li>
                <a href="#" id="toggle-high-contrast" class="alto_contraste">Alto Contraste</a>
            </li>
        </ul>
		
		<div id="logo-header-tema">
            <h1 id="portal-title">
                <a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>">
                    <?php bloginfo('name'); ?>
                </a>
            </h1>
            <p id="portal-description">
                <?php bloginfo('description'); ?>
            </p>
        </div>

        

    </div>
    <?php
        if( has_nav_menu('primary-menu') ){
            wp_nav_menu( array( 'theme_location' => 'primary-menu' ) );
        }
    ?>
</div>