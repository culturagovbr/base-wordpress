<?php
global $khore_footer_scripts;
$khore_footer_scripts[] = ".search-form #s, .search-form #searchsubmit{color:{$args['additional_styles']['search_form']['color']}!important;border-color:{$args['additional_styles']['search_form']['color']}!important;}";
;
?>
<!-- news -->
<section class="news" data-section="news" <?php echo $args['styles']['section']; ?>>
    <!-- news__wrap -->
    <div class="news__wrap">
        <!-- site__title -->
        <h2 class="site__title" <?php echo $args['styles']['title']; ?>>
            <?php echo stripslashes($args['title']); ?>
            <span <?php echo $args['styles']['subtitle']; ?>><?php echo stripslashes($args['subtitle']); ?></span>
        </h2>
        <!-- /site__title -->
        <!-- news__gallery -->
        <div class="news__gallery container">
            <div class="row">
                <?php
                $i = 0;
                if (!empty($args['news'])) {
                    foreach ($args['news'] as $new) {
                        $news_date = strtotime($new->post_date);
                        $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($new->ID), 'khore-news');
                        ?>
                        <div class="col-xs-12 col-md-4 col-sm-6">
                            <!-- news__item -->
                            <a class="news__item" data-id="<?php echo $new->ID; ?>" data-type="post" href="<?php echo get_permalink($new->ID); ?>" style="background-image:url(<?php echo $thumbnail[0]; ?>);">
                                <div>
                                    <div class="vertical-center">
                                        <div>
                                            <div <?php echo $args['styles']['news_title']; ?>>
                                                <?php echo get_the_title($new->ID); ?>
                                                <span <?php echo $args['styles']['news_subtitle']; ?>><?php echo date_i18n('M d, Y', $news_date); ?></span>

                                            </div>
                                        </div>
                                    </div>
                                    <?php if (!empty($args['view_button_text'])) { ?>
                                        <div class="news__item-btn" <?php echo $args['styles']['news_detail_button']; ?>><?php echo $args['view_button_text']; ?></div>
                                    <?php } ?>
                                </div>
                            </a>
                            <!-- /news__item -->
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
        <!-- /news__gallery -->
        <!-- pagination -->
        <?php khore_main_pagination($args['paged'], $args['total'], array($args['styles']['speaker_pagination_active'], $args['styles']['speaker_pagination_disabled'])); ?>
        <!-- /pagination -->
        <?php
        get_search_form();
        ?>
    </div>
    <!-- /news__wrap -->
    <!-- news__popup -->
    <div class="news__popup dark" data-action="">
        <!-- news__popup-wrap -->
        <div class="news__popup-wrap"></div>
        <!-- /news__popup-wrap -->
    </div>
    <!-- /news__popup -->
    <a href="#" class="news__popup-close"><span class="glyph-icon flaticon-thin35"></span></a>
</section>
<!-- /news -->