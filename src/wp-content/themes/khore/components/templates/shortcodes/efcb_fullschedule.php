<?php
global $khore_footer_scripts;
$khore_footer_scripts[] = "section.schedule .schedule__face1{background-color:{$args['atts']['session_background_color']}!important;}";
$khore_footer_scripts[] = "section.schedule .schedule__face2{background-color:{$args['atts']['session_background_color']}!important;}";
$khore_footer_scripts[] = "section.schedule .schedule__card-title{color:{$args['atts']['day_bar_font_color']}!important;background-color:{$args['atts']['day_bar_background_color']}!important;font-size:{$args['atts']['day_bar_font_size']}!important;}";
$khore_footer_scripts[] = "section.schedule .vertical-center > div > div{color:{$args['atts']['session_title_font_color']}!important;font-size:{$args['atts']['session_title_font_size']}!important;}";
$khore_footer_scripts[] = "section.schedule .schedule__face1 time{color:{$args['atts']['session_time_font_color']}!important;}";
$khore_footer_scripts[] = "section.schedule .schedule__info p{color:{$args['atts']['session_location_font_color']}!important;font-size:{$args['atts']['session_location_font_size']}!important;}";
$khore_footer_scripts[] = "section.schedule .schedule__info a{color:{$args['atts']['session_button_font_color']}!important;font-size:{$args['atts']['session_button_font_size']}!important;}";
?>
<!-- schedule -->
<section class="schedule" data-section="schedule" <?php echo $args['styles']['section']; ?>>
    <!-- schedule__wrap -->
    <div class="schedule__wraper">
        <header>
            <div class="schedule__title-wrap">
                <!-- schedule__title -->
                <h2 class="site__title" <?php echo $args['styles']['title']; ?>>
                    <?php echo stripslashes($args['title']); ?>
                    <span <?php echo $args['styles']['subtitle']; ?>><?php echo stripslashes($args['subtitle']); ?></span>
                </h2>
                <!-- /schedule__subtitle -->
		  <div class="search-form">
		    <form role="search" action="<?php echo site_url('/'); ?>" method="get" id="searchform">     
		      <input type="text" value="<?php print get_search_query(); ?>" name="s" id="s" />
		      <button id="searchsubmit" class="btn"><?php _e('Search', 'khore'); ?></button>
		    </form>
		  </div>

            </div>
            <!-- schedule__filter -->
            <div class="schedule__filter container">
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <?php if (!empty($args['tracks'])) { ?>
                            <select name="schedule_tracks" class="schedule_tracks">
                                <option value="0" selected><?php _e('Track', 'khore'); ?></option>
                                <?php foreach ($args['tracks'] as $session_track) { ?>
                                    <option value="<?php echo $session_track->term_id; ?>"><?php echo $session_track->name; ?></option>
                                <?php } ?>
                            </select>
                        <?php } ?>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <?php if (!empty($args['locations'])) { ?>
                            <select name="session_locations" class="session_locations">
                                <option value="0" selected><?php _e('Location', 'khore'); ?></option>

                                <?php foreach ($args['locations'] as $session_location) { ?>
                                    <option
                                        value="<?php echo $session_location->term_id; ?>"><?php echo $session_location->name; ?></option>
                                    <?php } ?>
                            </select>
                        <?php } ?>
                    </div>
                    <div class="col-md-4 col-sm-12">
                        <?php if (!empty($args['dates'])) { ?>
                            <select name="session_dates" class="session_dates">
                                <option value="0" selected><?php _e('Days', 'khore'); ?></option>
                                <?php foreach ($args['dates'] as $session_date) { ?>
                                    <option
                                        value="<?php echo $session_date->meta_value; ?>"><?php echo date_i18n(get_option('date_format'), $session_date->meta_value); ?></option>
                                    <?php } ?>
                            </select>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <!-- /schedule__filter -->
        </header>
        <!-- schedule__scroll -->
        <div class="schedule__scroll">
            <div class="schedule_update_content">
            </div>
        </div>
        <!-- /schedule__scroll -->
        <!-- schedule__popup -->
        <div class="schedule__popup dark" data-action="">
            <div class="schedule__popup-wrap">
            </div>
        </div>
        <a href="#" class="schedule__popup-close">
            <span class="glyph-icon flaticon-thin35"></span>
        </a>
    </div>
    <!-- /schedule__wrap -->
</section>
<!-- /schedule -->
<?php
if (defined('DOING_AJAX')) {
    if (isset($khore_footer_scripts)) {
        echo '<style type="text/css">';
        foreach ($khore_footer_scripts as $script) {
            echo $script;
        }
        echo '</style>';
    }
}
