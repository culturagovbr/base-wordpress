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
<body <?php body_class( ( get_minc_option('theme_color') ) ? 'tema-' . get_minc_option('theme_color') : ''  ); echo ( get_minc_option('theme_color') === 'custom' ) ? 'data-primary-color-palette="'. get_minc_option('color_schema') .'" data-secondary-color-palette="'. get_minc_option('color_schema_2') .'"' : ''; ?>>
<div id="header" role="banner">
    <div class="clearfix" <?php echo ( get_minc_option('header_bg') ) ? 'style="background: url('. get_minc_option('header_bg') . ') right bottom no-repeat;"' : ''; ?>>
        <ul id="accessibility">
            <li>
                <a accesskey="1" href="#div-conteudo" id="link-conteudo">
                    Ir para o conteúdo
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
                    Ir para o rodapé
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
            <h1 id="portal-title">
                <a href="<?php echo home_url(); ?>" title="<?php bloginfo('name'); ?>">
                    <?php bloginfo('name'); ?>
                </a>
            </h1>
            <p id="portal-description">
                <?php bloginfo('description'); ?>
            </p>
        </div>

        <?php if( get_minc_option('show_searchbar') ): ?>
        <div id="portal-searchbox" role="search">
            <form role="search" method="get" class="" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <?php
                    printf( '<input type="search" class="et-search-field"  accesskey="3" placeholder="%1$s" value="%2$s" name="s" title="%3$s" /> <input type="submit" value="Buscar" class="searchButton">',
                        esc_attr__( 'Search &hellip;', 'Divi' ),
                        get_search_query(),
                        esc_attr__( 'Buscar:', 'Divi' )
                    );
                ?>
            </form>
        </div>
        <?php endif; ?>

        <?php if( get_minc_option('show_social_links') ): ?>
        <div id="social-icons">
            <ul>
                <?php foreach (get_minc_option('social_links') as $key => $li) {
                    echo $li;
                }?>
            </ul>
        </div>
        <?php endif; ?>

    </div>
    <?php
        if( has_nav_menu('primary-menu') ){
            wp_nav_menu( array( 'theme_location' => 'primary-menu' ) );
        }
    ?>
</div>

<div id="et-main-area">
