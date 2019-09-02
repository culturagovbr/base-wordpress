<!-- speakers -->
<section class="news" <?php echo $args['styles']['section']; ?> data-section="news">
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
                if ($args['speakers'] && count($args['speakers'] > 0)) {
                    foreach ($args['speakers'] as $speaker) {
                        $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($speaker->ID), 'khore-speaker');
                        ?>
                        <div class="col-xs-12 col-md-4 col-sm-6">
                            <a class="news__item" data-id="<?php echo $speaker->ID; ?>" data-type="speaker" href="<?php echo get_permalink($speaker->ID); ?>" style="background-image:url(<?php echo $thumbnail[0]; ?>);">
                                <?php
                                $speaker_keynote = get_post_meta($speaker->ID, 'speaker_keynote', true);
                                if ($speaker_keynote == 1) {
                                    ?>
                                    <!-- star -->
                                    <div class="star">
                                        <i class="flaticon-vertical3"></i>
                                        <i class="fa fa-star-o"></i>
                                    </div>
                                    <!-- /star -->
                                <?php }; ?>
                                <div>
                                    <div class="vertical-center">
                                        <div>
                                            <div <?php echo $args['styles']['speaker_title']; ?>>
                                                <?php echo get_the_title($speaker->ID); ?>
                                                <span <?php echo $args['styles']['speaker_subtitle']; ?>><?php echo get_post_meta($speaker->ID, 'speaker_title', true); ?></span>

                                            </div>
                                        </div>
                                    </div>
                                    <?php if (!empty($args['view_text'])) { ?>
                                        <div class="news__item-btn" data-action="<?php echo get_permalink($speaker->ID); ?>" <?php echo $args['styles']['speaker_detail_button']; ?>>
                                            <?php echo stripslashes($args['view_text']); ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </a>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
        </div>
        <!-- /news__gallery -->
        <?php khore_main_pagination($args['paged'], $args['total'], array($args['styles']['speaker_pagination_active'], $args['styles']['speaker_pagination_disabled'])); ?>
    </div>
    <!-- /news__wrap -->
    <!-- news__popup -->
    <div class="news__popup dark">
        <!-- news__popup-wrap -->
        <div class="news__popup-wrap"></div>
        <!-- /news__popup-wrap -->
    </div>
    <!-- /news__popup -->
    <a href="#" class="news__popup-close"><span class="glyph-icon flaticon-thin35"></span></a>
</section>
<!-- /speakers -->
