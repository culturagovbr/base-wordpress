<!DOCTYPE html>
<!--[if IE 6]>
<html id="ie6" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 7]>
<html id="ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html id="ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 6) | !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
    <meta charset="<?php bloginfo('charset'); ?>"/>
    <?php elegant_description(); ?>
    <?php elegant_keywords(); ?>
    <?php elegant_canonical(); ?>

    <?php do_action('et_head_meta'); ?>

    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>"/>

    <?php $template_directory_uri = get_template_directory_uri(); ?>
    <!--[if lt IE 9]>
    <script src="<?php echo esc_url( $template_directory_uri . '/js/html5.js"' ); ?>" type="text/javascript"></script>
    <![endif]-->

    <script type="text/javascript">
        document.documentElement.className = 'js';
    </script>

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
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
                <a accesskey="2" href="#irparaomenu" id="link-navegacao">
                    Ir para o menu
                    <span>2</span>
                </a>
            </li>
            <li>
                <a accesskey="3" href="#portal-searchbox" id="link-buscar">
                    Ir para a busca
                    <span>3</span>
                </a>
            </li>
            <li>
                <a accesskey="4" href="#main-footer" id="link-rodape">
                    Ir para o rodap&eacute;
                    <span>4</span>
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

        <div id="social-icons">
            <ul>
                <!-- <li>
                    <a href="#!" title="Github" target="_blank" class="github">
                        <span>Github</span>
                        <svg aria-hidden="true" class="octicon octicon-mark-github" height="18" version="1.1" viewBox="0 0 16 16" width="18"><path fill-rule="evenodd" d="M8 0C3.58 0 0 3.58 0 8c0 3.54 2.29 6.53 5.47 7.59.4.07.55-.17.55-.38 0-.19-.01-.82-.01-1.49-2.01.37-2.53-.49-2.69-.94-.09-.23-.48-.94-.82-1.13-.28-.15-.68-.52-.01-.53.63-.01 1.08.58 1.23.82.72 1.21 1.87.87 2.33.66.07-.52.28-.87.51-1.07-1.78-.2-3.64-.89-3.64-3.95 0-.87.31-1.59.82-2.15-.08-.2-.36-1.02.08-2.12 0 0 .67-.21 2.2.82.64-.18 1.32-.27 2-.27.68 0 1.36.09 2 .27 1.53-1.04 2.2-.82 2.2-.82.44 1.1.16 1.92.08 2.12.51.56.82 1.27.82 2.15 0 3.07-1.87 3.75-3.65 3.95.29.25.54.73.54 1.48 0 1.07-.01 1.93-.01 2.2 0 .21.15.46.55.38A8.013 8.013 0 0 0 16 8c0-4.42-3.58-8-8-8z"></path></svg>
                    </a>
                </li> -->
            </ul>
        </div>

    </div>
    <?php
        if( has_nav_menu('primary-menu') ){
            wp_nav_menu( array( 'theme_location' => 'primary-menu' ) );
        }
    ?>
</div>

<div id="et-main-area">
