<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta http-equiv="cache-control" content="no-cache, must-revalidate, post-check=0, pre-check=0" />
    <meta http-equiv="cache-control" content="max-age=0" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
    <meta http-equiv="pragma" content="no-cache" />

    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    <!-- <link rel="icon" type="image/x-icon" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon.png"/> -->
    <title><?php bloginfo('name'); ?></title>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <?php wp_head(); ?>
</head>
<body <?php body_class( get_page_theme( get_queried_object_id() ) ); ?>>
<header id="site-header" class="logged-user">
    <div class="container">
        <div class="row">
            <div class="col-lg-9 col-md-12">
                <nav class="navbar navbar-expand-md navbar-light">
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#main-navbar" aria-controls="main-navbar" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <?php 
                    $menu_args = array(
                        'menu'              => 'primary-menu',
                        'theme_location'    => 'primary-menu',
                        'depth'             => 2,
                        'container'         => 'div',
                        'container_class'   => 'collapse navbar-collapse',
                        'container_id'      => 'main-navbar',
                        'menu_class'        => 'nav navbar-nav',
                        'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
                        'walker'            => new WP_Bootstrap_Navwalker()
                    );
                    wp_nav_menu($menu_args); ?>
                </nav>
            </div>
            <div class="col-lg-3 col-md-12 nav-support">
                <?php if ( get_field('facebook', 'option') ): ?>
                    <a href="<?php the_field('facebook', 'option'); ?>" class="facebook" target="_blank">f</a>
                <?php endif; ?>
                <ul class="lang-switcher">
                    <?php pll_the_languages( array( 'display_names_as' => 'slug' ) ); ?>
                </ul>
            </div>
        </div>
    </div>
</header>