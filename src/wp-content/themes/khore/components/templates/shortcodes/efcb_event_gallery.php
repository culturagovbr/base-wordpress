<?php $ef_options = EF_Event_Options::get_theme_options(); ?>

<!-- index -->
<div class="index">

    <!-- index__wrap -->
    <div class="index__wrap">

        <!-- video-card -->
        <div class="video-card">
            <div>
                <div>

                    <!-- video-card__title -->
                    <h2 class="video-card__title">
                        <?php
                        if (!empty($ef_options['ef_header_logo'])) {
                            ?>
                            <img src="<?php echo $ef_options['ef_header_logo']; ?>" width="300" height="101"
                                 alt="<?php bloginfo('name'); ?>"/>
                                 <?php
                             }
                             ?>
                        <span> <?php
                            if (!empty($ef_options['ef_herotitle'])) {
                                echo stripslashes($ef_options['ef_herotitle']);
                            }
                            ?></span>
                    </h2>
                    <!-- /video-card__title -->

                    <p><?php
                        if (!empty($ef_options['ef_herosubtitle'])) {
                            echo stripslashes($ef_options['ef_herosubtitle']);
                        }
                        ?></p>

                    <!-- video-card__place -->
                    <div class="video-card__place">
                        <?php
                        if (!empty($ef_options['ef_datetimeplace'])) {
                            echo stripslashes($ef_options['ef_datetimeplace']);
                        }
                        ?>
                    </div>
                    <!-- /video-card__place -->

                    <?php if (isset($ef_options['ef_header_video']) && $ef_options['ef_header_video'] != '') { ?>
                        <!-- video-card__lnk -->
                        <div>
                            <a class="video-card__lnk" href="#"
                               data-video="<?php echo stripslashes($ef_options['ef_header_video']); ?>">
                                <i class="fa fa-play fa-5x"></i>
                            </a>
                        </div>
                        <!-- /video-card__lnk -->
                    <?php };
                    ?>

                    <?php if (isset($ef_options['ef_show_register_btn'])) { ?>
                        <!-- video-card__reg -->
                        <a href="<?php echo stripslashes($ef_options['ef_registerbtnurl']); ?>"
                           title="Register"
                           class="video-card__reg"><?php echo stripslashes($ef_options['ef_registerbtntext']); ?></a>
                        <!-- /video-card__reg -->
                    <?php }; ?>
                </div>
            </div>
        </div>
        <!-- /video-card -->

        <!-- slider -->
        <div class="slider">

            <!-- slider__wrap -->
            <div class="slider__wrap">

                <?php
                if (!empty($ef_options['ef_header_gallery'])) {
                    $i = 0;
                    foreach (explode(',', $ef_options['ef_header_gallery']) as $id) {
                        ?>
                        <div class="slider__item"
                             style="background-image: url(<?php echo wp_get_attachment_url($id); ?>);"></div>
                        <?php
                    }
                }
                ?>

            </div>
            <!-- /slider__wrap -->

        </div>
        <!-- /slider -->

    </div>
    <!-- /index__wrap -->

</div>
<!-- /index -->