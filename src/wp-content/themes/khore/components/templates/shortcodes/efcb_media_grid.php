<!-- gallery -->
<section class="gallery light" data-section="gallery" <?php echo $args['styles']['section']; ?>>
    <!-- gallery__wrap -->
    <div class="gallery__wrap">
        <!-- site__title -->
        <h2 class="site__title" <?php echo $args['styles']['title']; ?>>
            <?php echo stripslashes($args['title']); ?>
            <span <?php echo $args['styles']['subtitle']; ?>><?php echo stripslashes($args['subtitle']); ?></span>
        </h2>
        <!-- /site__title -->
        <div class="swiper-container gallery-top">
            <div class="swiper-wrapper">
                <?php
                if (count($args['medias']) > 0) {
                    foreach ($args['medias'] as $media) {
                        if (ctype_digit($media)) {
                            $thumbnail = wp_get_attachment_image_src($media, 'khore-media-thumbnail');
                            $image = wp_get_attachment_image_src($media, 'khore-media');
                            ?>
                            <div class="swiper-slide" style='background-image: url(<?php echo $image[0]; ?>)'>
                                <img src="<?php echo $image[0]; ?>" alt="">
                            </div>
                        <?php
                        } else {
                            ?>
                            <div class="swiper-slide gallery__btn-video" style='background-image: url(<?php echo khore_get_video_thumbnail($media, array('youtube' => 'hqdefault', 'vimeo' => 'thumbnail_small')); ?>)' data-video="<iframe width='560' height='315' src='<?php echo khore_get_video_embedded_url($media); ?>' frameborder='0' allowfullscreen webkitallowfullscreen mozallowfullscreen></iframe>">
                                <img src="<?php echo khore_get_video_thumbnail($media, array('youtube' => 'hqdefault', 'vimeo' => 'thumbnail_small')); ?>" alt="">
                            </div>
                        <?php
                        }
                    }
                }
                ?>
            </div>
            <!-- Add Arrows -->
            <div class="swiper-button-next swiper-button">
                <i class="fa fa-arrow-right"></i>
            </div>
            <div class="swiper-button-prev swiper-button">
                <i class="fa fa-arrow-left"></i>
            </div>
        </div>
        <div class="swiper-container gallery-thumbs">
            <div class="swiper-wrapper">
                <?php
                if (count($args['medias']) > 0) {
                    foreach ($args['medias'] as $media) {
                        if (ctype_digit($media)) {
                            $thumbnail = wp_get_attachment_image_src($media, 'khore-media-thumbnail');
                            $image = wp_get_attachment_image_src($media, 'khore-media');
                            ?>
                            <div class="swiper-slide" style='background-image: url(<?php echo $thumbnail[0]; ?>)'>
                                <img src="<?php echo $thumbnail[0]; ?>" alt="">
                                <i class="fa fa-camera"></i>
                            </div>
                        <?php
                        } else {
                            ?>
                            <div class="swiper-slide" style='background-image: url(<?php echo khore_get_video_thumbnail($media, array('youtube' => '0', 'vimeo' => 'thumbnail_large')); ?>)'>
                                <img src="<?php echo khore_get_video_thumbnail($media, array('youtube' => '0', 'vimeo' => 'thumbnail_large')); ?>" alt="">
                                <i class="fa fa-video-camera"></i>
                            </div>
                        <?php
                        }
                    }
                }
                ?>
            </div>
        </div>
        <!-- gallery__video -->
        <div class="gallery__video">
            <i class="fa fa-times"></i>
            <div></div>
        </div>
        <!-- /gallery__video -->
    </div>
    <!-- /gallery__wrap -->
</section>
<!-- /gallery -->