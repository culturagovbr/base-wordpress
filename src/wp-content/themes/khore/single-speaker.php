<?php
get_header();

if (have_posts()) :
    while (have_posts()) :
        the_post();
        ?>
        <div class="site__content dark">
            <div class="page page_<?php echo $post->post_name; ?> dark page_loaded page_active single-speaker" data-id="<?php echo $post->ID; ?>">
                <?php get_template_part('speaker-content'); ?>
            </div>
        </div>
        <?php
    endwhile;
endif;

get_footer();
