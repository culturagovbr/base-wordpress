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
<header id="site-header" class="logged-user">
    <div class="container">
        <div class="row">
            <div class="col-sm-6">
                <h1>
                    <a href="<?php echo home_url(); ?>">
                        <span class="sr-only"><?php bloginfo('name'); ?></span>
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-oscar.png">
                    </a>
                </h1>
            </div>
            <div class="col-sm-6 text-right">
                <img class="statue" src="<?php echo get_template_directory_uri(); ?>/assets/images/oscar-site-images_05.png">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/logo-90-premio2018.png">
            </div>
        </div>
    </div>
    <div class="logged-user-menu">
        <div class="container">
            <div class="row">
                <ul class="nav nav-pills text-right">
                    <li role="presentation"><a href="<?php echo home_url(); ?>">Inscrição</a></li>
                    <li role="presentation"><a href="<?php echo home_url('/contato'); ?>">Fale conosco</a></li>
                    <?php if( is_user_logged_in() ): ?>
                    <li role="presentation"><a href="<?php echo home_url('/enviar-video'); ?>">Enviar vídeo</a></li>
                    <li role="presentation"><a href="<?php echo wp_logout_url( home_url('/login') ); ?>">Sair</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</header>