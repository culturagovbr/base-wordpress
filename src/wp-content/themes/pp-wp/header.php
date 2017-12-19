<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Portal_Padrão_WP
 */

?>
<!doctype html>
<html <?php language_attributes (); ?>>
<head>
    <meta charset="<?php bloginfo ('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="profile" href="http://gmpg.org/xfn/11">

	<?php wp_head (); ?>
</head>

<body <?php body_class (); ?>>
<div id="page" class="site">
    <a class="skip-link screen-reader-text sr-only"
       href="#content"><?php esc_html_e ('Skip to content', 'pp-wp'); ?></a>

    <header id="masthead" class="site-header">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">

                    <ul id="shortcut-bar">
                        <li>
                            <a accesskey="1" href="#main" id="main-link">
                                Ir para o conteúdo
                                <span>1</span>
                            </a>
                        </li>
                        <li>
                            <a accesskey="2" href="#navigation" id="navigation-link">
                                Ir para o menu
                                <span>2</span>
                            </a>
                        </li>
                        <li>
                            <a accesskey="3" href="#main-search" id="main-search-link">
                                Ir para a busca
                                <span>3</span>
                            </a>
                        </li>
                        <li class="last-item">
                            <a accesskey="4" href="#footer" id="footer-link">
                                Ir para o rodapé
                                <span>4</span>
                            </a>
                        </li>
                    </ul>

                    <span class="denomination">Denominação</span>
                    <h1 class="site-title">
                        <a href="<?php echo esc_url (home_url ('/')); ?>" rel="home">
                            <?php the_custom_logo (); ?>
                            <?php bloginfo ('name'); ?>
                        </a>
                    </h1>
                    <?php $description = get_bloginfo ('description', 'display');
                    if ($description || is_customize_preview ()) : ?>
                        <span class="site-description"><?php echo $description; /* WPCS: xss ok. */ ?></span>
                        <?php
                    endif; ?>
                </div>

                <div class="col-lg-4">

                    <ul id="accessibility">
                        <li>
                            <a href="#" title="Acessibilidade" accesskey="5">Acessibilidade</a>
                        </li>
                        <li>
                            <a href="#" title="Alto Contraste" accesskey="6">Alto Contraste</a>
                        </li>
                        <li>
                            <a href="#" title="Mapa do Site" accesskey="7">Mapa do Site</a>
                        </li>
                    </ul>

					<?php get_search_form(); ?>
                </div>
            </div>
        </div>

        <div class="service-bar-container">
            <div class="container">
                <div class="row">
                    <div class="col">
                        <nav class="navbar navbar-expand-md navbar-light">
                            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-navbar" aria-controls="main-navbar" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
							<?php
							$menu_args = array(
								'menu'              => 'service-menu',
								'theme_location'    => 'service-menu',
								'depth'             => 2,
								'container'         => 'div',
								'container_class'   => 'collapse navbar-collapse',
								'container_id'      => 'main-navbar',
								'menu_class'        => 'service-bar ml-auto nav navbar-nav',
								'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
								'walker'            => new WP_Bootstrap_Navwalker()
							);
							wp_nav_menu($menu_args); ?>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </header>
