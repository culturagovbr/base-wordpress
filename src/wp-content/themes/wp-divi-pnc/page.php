<?php get_header(); ?>

    <div id="main-content">
        <div class="conteudo">
            <div id="content-area" class="clearfix">
                <?php
                if (have_posts()) :
                    while (have_posts()) : the_post();
                        $post_format = et_pb_post_format(); ?>

                        <article id="post-<?php the_ID(); ?>" <?php post_class('et_pb_post'); ?>>

                            <?php
                            $thumb = '';

                            $width = (int)apply_filters('et_pb_index_blog_image_width', 1080);

                            $height = (int)apply_filters('et_pb_index_blog_image_height', 675);
                            $classtext = 'et_pb_post_main_image';
                            $titletext = get_the_title();
                            $thumbnail = get_thumbnail($width, $height, $classtext, $titletext, $titletext, false, 'Blogimage');
                            $thumb = $thumbnail["thumb"];

                            et_divi_post_format_content();

                            if (!in_array($post_format, array('link', 'audio', 'quote'))) {
                                if ('video' === $post_format && false !== ($first_video = et_get_first_video())) :
                                    printf(
                                        '<div class="et_main_video_container">
                                %1$s
                            </div>',
                                        $first_video
                                    );
                                elseif (!in_array($post_format, array('gallery')) && 'on' === et_get_option('divi_thumbnails_index', 'on') && '' !== $thumb) : ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php print_thumbnail($thumb, $thumbnail["use_timthumb"], $titletext, $width, $height); ?>
                                    </a>
                                    <?php
                                elseif ('gallery' === $post_format) :
                                    et_pb_gallery_images();
                                endif;
                            } ?>

                            <?php if (!in_array($post_format, array('link', 'audio', 'quote'))) : ?>
                                <?php if (!in_array($post_format, array('link', 'audio'))) : ?>
                                    <h2 class="entry-title" style="display: none"><a
                                                href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                <?php endif; ?>

                                <?php
                                // et_divi_post_meta();

                                the_content();
                                ?>
                            <?php endif; ?>
                            

                            <span class="social-icones">
                                <a class="tp-caption tp-layer-selectable " href="https://plus.google.com/share?url=<?php the_permalink(); ?>" target="_blank"> <i class="fa-icon-google-plus"></i> </a>
                                <a class="tp-caption tp-layer-selectable " href="whatsapp://send?text=<?php the_title(); ?>&nbsp;%C2%A0%20%E2%98%9B%20<?php the_permalink(); ?>" target="_blank"> <i class="fa fa-whatsapp"></i> </a>
                                <a class="tp-caption tp-layer-selectable " href="https://twitter.com/home?status=<?php the_permalink(); ?>" target="_blank"> <i class="fa-icon-twitter"></i> </a>
                                <a class="tp-caption tp-layer-selectable " href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" target="_blank"> <i class="fa-icon-facebook"></i> </a>
                            </span>

                            ?>
                           
                        </article> <!-- .et_pb_post -->
                        <?php
                    endwhile;

                else :
                    get_template_part('includes/no-results', 'index');
                endif;
                ?>
            </div>
        </div>
    </div>

<?php get_footer(); ?>