<?php 
// define('WP_USE_THEMES', false);
// require( dirname( __FILE__ ) . '/../../../../wp-blog-header.php' ); 

// $options = get_option( 'minc_simpletheme_options' );

header("Content-type: text/css; charset: UTF-8"); 
?>

<!--<style id="minc-simpletheme-custom-css">-->
/**
 * Tema personalizado
 */
body.tema-custom{
	background-color: red !important;
}
body.tema-custom #header{
    background: <?php echo $options['color_schema']; ?>;
    background: -moz-linear-gradient(top, <?php echo $options['color_schema']; ?> 0%, <?php echo $options['color_schema']; ?>;
    background: -webkit-gradient(left top, left bottom, color-stop(0%, <?php echo $options['color_schema']; ?>), color-stop(100%, rgba(236,237,241,1)));
    background: -webkit-linear-gradient(top, <?php echo $options['color_schema']; ?> 0%, <?php echo $options['color_schema']; ?>;
    background: -o-linear-gradient(top, <?php echo $options['color_schema']; ?> 0%, <?php echo $options['color_schema']; ?>;
    background: -ms-linear-gradient(top, <?php echo $options['color_schema']; ?> 0%, <?php echo $options['color_schema']; ?>;
    background: linear-gradient(to bottom, <?php echo $options['color_schema']; ?> 0%, <?php echo $options['color_schema']; ?>;
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#f0f2f1', endColorstr='#ecedf1', GradientType=0 );
}

body.tema-custom #accessibility a,
body.tema-custom #portal-siteactions a{
    color: #2C66CE !important;
}

body.tema-custom #portal-title,
body.tema-custom #portal-description,
body.tema-custom #accessibility a,
body.tema-custom #portal-siteactions a,
body.tema-custom #header .menu li a{
    color: #2C66CE;
}

body.tema-custom #header .menu-menu-principal-container {
    background-color: <?php echo $options['color_schema_2']; ?> !important;
}

body.tema-custom #accessibility span {
    color: #fff;
    background-color: #2C66CE;
}

body.tema-custom #footer-brasil {
    background-color: #d5d5d5  !important;;
}
<!--</style>-->