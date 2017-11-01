<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="apple-touch-icon" sizes="57x57" href="<?php bloginfo('template_directory'); ?>/images/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="<?php bloginfo('template_directory'); ?>/images/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="<?php bloginfo('template_directory'); ?>/images/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="<?php bloginfo('template_directory'); ?>/images/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="<?php bloginfo('template_directory'); ?>/images/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="<?php bloginfo('template_directory'); ?>/images/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="<?php bloginfo('template_directory'); ?>/images/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="<?php bloginfo('template_directory'); ?>/images/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php bloginfo('template_directory'); ?>/images/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="images/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?php bloginfo('template_directory'); ?>/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="<?php bloginfo('template_directory'); ?>/images/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?php bloginfo('template_directory'); ?>/images/favicon-16x16.png">
    <link rel="manifest" href="/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <title>Ambiente virtual de participação de adolescentes</title>
    <!-- Bootstrap core CSS -->
    <link href="<?php bloginfo('template_directory'); ?>/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="<?php bloginfo('template_directory'); ?>/style.css" rel="stylesheet">
    <link href="<?php bloginfo('template_directory'); ?>/css/font-awesome.min.css" rel="stylesheet">
    <!-- Font family -->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,400,300,700' rel='stylesheet' type='text/css'>
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,300i,400,400i,500,500i,700,700i" rel="stylesheet">
    <!--<link href="https://fonts.googleapis.com/css?family=Oswald:300,400,500" rel="stylesheet">-->

    <link rel="icon" type="image/x-icon" href="<?php bloginfo('template_directory'); ?>/images/favicon.ico" />

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
<?php wp_head(); ?>
</head>

<!-- :: Condicionais para header :: -->

<?php if (is_home()) { ?>
    <!-- HOME  -->

    <body data-spy="scroll" data-target="nav" class="home">

                    <div class="backdrop"></div>
                    <div class="fab child" data-subitem="1" data-toggle="tooltip" data-placement="left" title="Perguntas frequentes"><span><a href="<?php echo get_site_url(); ?>/perguntas-frequentes" role="presentation">?</a></span></div>
                    <div class="fab child" data-subitem="2" data-toggle="tooltip" data-placement="left" title="Problemas ou Sugestões"><span><a href="<?php echo get_site_url(); ?>/problemas-sugestoes" role="presentation"><i class="fa fa-comment" aria-hidden="true"></i></a></span></div>
                    <div class="fab" id="masterfab"><span>+</span></div>



<?php } elseif (is_page('perguntas-frequentes')) { ?>

    <body data-spy="scroll" data-target="nav" class="internal">

                    <div class="backdrop"></div>
                    <div class="fab child" data-subitem="1" data-toggle="tooltip" data-placement="left" title="Página inicial"><span><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><i class="fa fa-home" aria-hidden="true"></i></a></span></div>
                    <div class="fab child" data-subitem="2" data-toggle="tooltip" data-placement="left" title="Problemas ou Sugestões"><span><a href="<?php echo get_site_url(); ?>/problemas-sugestoes" role="presentation"><i class="fa fa-comment" aria-hidden="true"></i></a></span></div>
                    <div class="fab" id="masterfab"><span>+</span></div>



<?php } elseif (is_page('problemas-sugestoes')) { ?>

    <body data-spy="scroll" data-target="nav" class="internal">

                    <div class="backdrop"></div>
                    <div class="fab child" data-subitem="1" data-toggle="tooltip" data-placement="left" title="Página inicial"><span><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><i class="fa fa-home" aria-hidden="true"></i></a></span></div>
                    <div class="fab child" data-subitem="2" data-toggle="tooltip" data-placement="left" title="Perguntas frequentes"><span><a href="<?php echo get_site_url(); ?>/perguntas-frequentes" role="presentation">?</a></span></div>
                    <div class="fab" id="masterfab"><span>+</span></div>



    <?php } else { ?>

    <!-- INTERNAL  -->

    <body data-spy="scroll" data-target="nav" class="internal">

                    <div class="backdrop"></div>
                    <div class="fab child" data-subitem="1" data-toggle="tooltip" data-placement="left" title="Página inicial"><span><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><i class="fa fa-home" aria-hidden="true"></i></a></span></div>
                    <div class="fab child" data-subitem="2" data-toggle="tooltip" data-placement="left" title="Perguntas frequentes"><span><a href="<?php echo get_site_url(); ?>/perguntas-frequentes" role="presentation">?</a></span></div>
                    <div class="fab child" data-subitem="3" data-toggle="tooltip" data-placement="left" title="Problemas ou Sugestões"><span><a href="<?php echo get_site_url(); ?>/problemas-sugestoes" role="presentation"><i class="fa fa-comment" aria-hidden="true"></i></a></span></div>
                    <div class="fab" id="masterfab"><span>+</span></div>

<?php } ?>



                    <header>

                        <nav class="navbar navbar-default">
                            <div class="container">
                                <!-- Brand and toggle get grouped for better mobile display -->
                                <div class="navbar-header">
                                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                        <span class="sr-only">Toggle navigation</span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </button>
                                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo">Ambiente virtual de <span>participação <br />de adolescentes</span></a>
                                </div>

                                <!-- Collect the nav links, forms, and other content for toggling -->
                                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                                    <ul class="nav navbar-nav navbar-right">
                                        <li><a href="<?php echo get_site_url(); ?>/sobre-nos" role="presentation">Sobre nós</a></li>
                                        <li><a href="<?php echo get_site_url(); ?>/category/conversas/" role="presentation">Conversas</a></li>
                                        <li><a href="<?php echo get_site_url(); ?>/perguntas-frequentes" role="presentation">Perguntas frequentes</a></li>
                                    </ul>
                                </div>
                                <!-- /.navbar-collapse -->
                            </div>
                            <!-- /.container-fluid -->
                        </nav>


                    </header>
