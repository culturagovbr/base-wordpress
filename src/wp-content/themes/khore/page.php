<?php
get_header();
?>
<?php
if (have_posts()) :
    while (have_posts()) :
        the_post();
        global $post;
        $page_class = is_front_page() ? 'page_index front-page' : "page_$post->post_name";
        ?>
        <div class="site__content light">
            <div class="page <?php echo $page_class; ?> light page_loaded page_active" data-id="<?php echo $post->ID; ?>">
                <div class="page__scroll">
                    <div>
                        <?php the_content(); ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    endwhile;
endif;
?>
<?php
get_footer();
