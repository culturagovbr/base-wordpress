<!-- inst -->
<section class="inst light" data-section="inst" <?php echo $args['styles']['section']; ?>>
    <!-- inst__wrap -->
    <div class="inst__wrap">
        <!-- inst__title -->
        <h2 class="site__title" <?php echo $args['styles']['title']; ?>>
            <i class="fa fa-instagram"></i>
            <?php echo stripslashes($args['title']); ?>
            <span <?php echo $args['styles']['subtitle']; ?>><?php echo stripslashes($args['subtitle']); ?></span>
        </h2>
        <!-- /inst__title -->
        <!-- inst__content -->
        <div class="inst__content" id="instagram_update_list">
            <?php
            if ($args['photos'] && $args['photos']->data && count($args['photos']->data) > 0) {
                $i = 0;
                foreach ($args['photos']->data as $media) {
                    ?>
                    <a href="<?php echo $media->link; ?>" target="_blank">
                        <img src="<?php echo $media->images->low_resolution->url; ?>" alt="" />
                    </a>
                    <?php
                }
            }
            ?>
        </div>
        <!-- /inst__content -->
    </div>
    <!-- /inst__wrap -->
</section>
<!-- /inst -->