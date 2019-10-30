<?php
// Template Name: Main page
get_header();

if (have_posts()) :
    while (have_posts()) :
        the_post();
        ?>
        <!-- site__content -->
        <div class="site__content light">
            <!-- page -->
            <div class="page page_index light page_loaded page_active front-page" data-id="<?php echo $post->ID; ?>">
                <!-- page__scroll -->
                <div class="page__scroll">
                    <div class="index">
                        <?php //get_template_part('front-page-content'); ?>
                        <?php the_content(); ?>
                    </div>
                </div>
                <!-- /page__scroll -->
            </div>
            <!-- /page -->
        </div>
        <!-- /site__content -->
        <?php
    endwhile;
endif;

get_footer();

?>
<script type="text/javascript">
(function($) {
    $(document).ready(function(e) {    
        $('.page__scroll > div:first-child').css('overflow-y', 'scroll');
    });
})(jQuery);
</script>
<?php
