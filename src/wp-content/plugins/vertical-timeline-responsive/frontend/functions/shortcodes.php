<?php


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/*
 * Prints the markup for the slider
 * @since 1.0.0
*/


// create shortcode with parameters so that the user can define what's queried - default is to list all blog posts
add_shortcode( 'vtimeline', 'vtimeline_shortcode' );
function vtimeline_shortcode( $atts ) {
    ob_start();
 
    // define attributes and their defaults
    extract( shortcode_atts( array (
        'id' => '',
    ), $atts ) );
 

    //$mypost = get_post($id);
  //var_dump(get_post_meta( $id ));

   $entries = get_post_meta( $id, $prefix . 'vtlr_group_demo', true );
   //$color1 = get_post_meta( $id, 'vtlr_settings_colorpickerone', 1 );
   //$color2 = get_post_meta( $id, 'vtlr_settings_colorpickertwo', 1 );
   //var_dump($color1); 
?>
<style type="text/css">
/*.vtlr-<?php echo $id; ?> .cbp_tmtimeline:before{
  background: <?php echo $color2; ?>;
}
.vtlr-<?php echo $id; ?> .cbp_tmtimeline > li:nth-child(odd) .cbp_tmlabel {
    background: <?php echo $color1; ?>;
}
.vtlr-<?php echo $id; ?> .cbp_tmtimeline > li:nth-child(even) .cbp_tmlabel {
    background: <?php echo $color2; ?>;
}
.vtlr-<?php echo $id; ?> .cbp_tmtimeline > li:nth-child(odd) .cbp_tmtime span:last-child {
  color: <?php echo $color1; ?>;
}
.vtlr-<?php echo $id; ?> .cd-timeline-content .cd-date {
  color: <?php echo $color2; ?>;
}
.vtlr-<?php echo $id; ?> .cd-timeline-content .cd-timeline-title {
    color: <?php echo $color2; ?>;
}
.vtlr-<?php echo $id; ?> .cbp_tmtimeline > li .cbp_tmlabel:after {
    border-right-color: <?php echo $color2; ?>;
}*/
</style>
<section id="cd-timeline" class="cd-container vtlr-<?php echo $id; ?>">
                  
<?php
foreach ( (array) $entries as $key => $entry ) {


    $title = $entry['title'] ; 
    $textarea1 = $entry['textarea1'] ;
    $dateortext = $entry['dateortext'] ;


?>
                    <div class="cd-timeline-block">
                      <div class="cd-timeline-img cd-picture">
                        <img src="img/cd-icon-picture.svg" alt="Picture">
                      </div> <!-- cd-timeline-img -->
                      <div class="cd-timeline-content">
                          <?php if(!empty($title)) {echo '<h2 class="cd-timeline-title">'. $title . '</h2>';} ?>
                          <p><?php echo $textarea1; ?></p>
                          <!-- <a href="#0" class="cd-read-more">Read more</a> -->
                          <span class="cd-date"><?php echo $dateortext; ?></span>
                      </div> <!-- cd-timeline-content -->
                        
                        
                    </div>
  

<?php // Do something with the data
 // Don't know if this is the correct method.
}
    // run the loop based on the query
    //var_dump($query);
     ?>
     
</section>
     

	
	        <?php // The Loop

/* Restore original Post Data */
wp_reset_postdata();
		?>
			  
	       




    <?php
        $myvariable = ob_get_clean();
        return $myvariable;
    }

