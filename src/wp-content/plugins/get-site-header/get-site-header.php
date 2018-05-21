<?php
/**
 * Plugin Name:       Get Site Header
 * Plugin URI:        https://github.com/culturagovbr/
 * Description:       Retorna o cabeçalho do site para ser usado de forma dinamica em outros locais
 * Version:           1.0.0
 * Author:            Ricardo Carvalho
 * Author URI:        https://github.com/darciro/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

if (!class_exists('GetSiteHeader')) :

    class GetSiteHeader
    {

        public function __construct()
        {
            add_action('wp_ajax_nopriv_get_header', array($this, 'get_header'));
            add_action('wp_ajax_get_header', array($this, 'get_header'));
            add_action('wp_ajax_nopriv_get_footer', array($this, 'get_footer'));
            add_action('wp_ajax_get_footer', array($this, 'get_footer'));
        }

        public function get_header()
        { ?>

            <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700,800" rel="stylesheet">
            <link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) ?>/assets/get-site-header-styles.css" type="text/css" media="all" />
            <link rel="stylesheet" href="<?php echo plugin_dir_url(__FILE__) ?>/assets/header.css" type="text/css" media="all" />

            <header id="header" class="site-header">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6 col-lg-8 cf-1">

                            <ul id="shortcut-bar" class="d-none d-md-block">
                                <li>
                                    <a accesskey="1" href="#main" id="main-link">
                                        Ir para o conteúdo
                                        <span>1</span>
                                    </a>
                                </li>
                                <li>
                                    <a accesskey="2" href="#main-navbar" id="navigation-link">
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

                            <?php $blog_denomination = get_option( 'blogdenomination' );
                            if( $blog_denomination || is_customize_preview () ): ?>
                                <span class="denomination"><?php echo $blog_denomination ?></span>
                            <?php endif; ?>
                            <h1 class="site-title">
                                <a href="<?php echo esc_url (home_url ('/')); ?>" rel="home">
                                    <?php the_custom_logo (); ?>
                                    <?php bloginfo ('name'); ?>
                                </a>
                            </h1>
                            <?php $description = get_bloginfo ('description', 'display');
                            if ($description || is_customize_preview ()) : ?>
                                <span class="site-description"><?php echo $description; ?></span>
                            <?php
                            endif; ?>
                        </div>

                        <div class="col-md-6 col-lg-4 cf-2">

                            <ul id="accessibility">
                                <li>
                                    <a href="<?php echo home_url('/acessibilidade'); ?>" title="Acessibilidade" accesskey="5">Acessibilidade</a>
                                </li>
                                <li>
                                    <a href="#" title="Alto Contraste" accesskey="6" id="high-contrast">Alto Contraste</a>
                                </li>
                                <li>
                                    <a href="<?php echo home_url('/mapa-do-site'); ?>" title="Mapa do Site" accesskey="7">Mapa do Site</a>
                                </li>
                            </ul>

                            <?php
                            $pp_theme_options_options = get_option( 'pp_theme_options_option_name' );
                            if( $pp_theme_options_options['show_search'] ){
                                get_search_form();
                            }

                            if( $pp_theme_options_options['show_social_links'] ):
                                if( !empty( $pp_theme_options_options['social_links'] ) ){ ?>

                                    <ul class="social-media-links">
                                        <?php foreach ($pp_theme_options_options['social_links'] as $social_links): ?>
                                            <li>
                                                <a href="<?php echo $social_links['url']; ?>" target="_blank" title="<?php echo $social_links['title']; ?>">
                                                    <i class="fa-ico-toggle fa <?php echo $social_links['icon']; ?>" aria-hidden="true"></i>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>

                                <?php } ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="service-bar-container">
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <nav class="navbar navbar-expand-md navbar-dark">
                                    <a class="navbar-brand invisible d-md-none" href="#">Menu de navegação</a>
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
                <?php if( get_header_image_tag() ): ?>
                    <div class="custom-header-bg">
                        <?php the_header_image_tag(); ?>
                    </div>
                <?php endif; ?>
            </header>
            <script type="text/javascript" src="<?php echo plugin_dir_url(__FILE__) ?>/assets/get-site-header-styles.js"></script>

            <?php
            die;
        }

        public function get_footer()
        { ?>

            <footer id="footer" class="site-footer">
                <div class="container site-info">
                    <div class="row">
                        <?php get_sidebar('footer'); ?>
                    </div>
                </div>
            </footer>

            <?php die;
        }

    }

    // Initialize our plugin
    $gewp = new GetSiteHeader();

endif;
