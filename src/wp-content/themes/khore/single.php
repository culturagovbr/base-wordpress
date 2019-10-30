<?php
get_header();

global $wp_query;
while (have_posts()) :
    the_post();
    ?>
    <div class="site__content dark">
        <div class="page page_<?php echo $post->post_name; ?> dark page_loaded page_active single" data-id="<?php echo $post->ID; ?>">
            <?php get_template_part('post-content'); ?>
        </div>
    </div>
    <?php
endwhile;

get_footer();
