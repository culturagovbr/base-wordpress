<?php
$single_session_fields = EF_Query_Manager::get_single_session_fields();
extract($single_session_fields);
?>
<!-- schedule__popup-layout -->
<div class="schedule__popup-layout">
    <!-- content -->
    <article class="content container">
        <!-- content__wrap -->
        <div class="content__wrap row">
            <!-- content__layout -->
            <div class="content__layout col-xs-12">
                <h1>
                    <?php the_title(); ?>
                    <span><time><?php echo(!empty($date) ? date_i18n(get_option('date_format'), $date) : ''); ?></time></span>
                </h1>
                <!-- spacer -->
                <div class="spacer">
                    <i class="fa fa-bookmark-o"></i>
                </div>
                <!-- spacer -->
                <!-- tags -->
                <div class="tags">
                    <?php foreach ($tracks as $track) { ?>
                        <div><a href="#"><?php echo $track->name; ?></a></div>
                    <?php } ?>
                </div>
                <!-- /tags -->
                <!-- schedule__faces -->
                <div class="schedule__faces">
                    <!-- /schedule__faces-item -->
                    <?php
                    if (!empty($speakers_list)) {
                        foreach ($speakers_list as $speaker_id) {
                            $thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($speaker_id), 'khore-speaker');
                            $thumbnail = $thumbnail[0];
                            ?>
                            <!-- schedule__faces-item -->
                            <a href="<?php echo get_permalink($speaker_id); ?>"
                               class="schedule__faces-item">
                                   <?php if ($thumbnail !== null) { ?>
                                    <img src="<?php echo $thumbnail; ?>" alt="">
                                <?php }; ?>
                                <?php if (get_post_meta($speaker_id, 'speaker_keynote', true) == 1) { ?>
                                    <div class="ribbon">
                                        <i class="flaticon-vertical3"></i>
                                        <i class="fa fa-star-o"></i>
                                    </div>
                                <?php } ?>
                                <span><?php echo get_the_title($speaker_id); ?></span>
                            </a>
                            <!-- /schedule__faces-item -->
                            <?php
                        }
                    }
                    ?>
                </div>
                <!-- /schedule__faces -->
                <?php the_content(); ?>
                <?php if (!empty($registration_code)) { ?>
                    <!-- schedule__registration -->
                    <section class="schedule__registration">
                        <h2><?php echo $registration_title; ?></h2>

                        <div>
                            <?php echo $registration_code; ?>
                        </div>
                        <p>
                            <?php echo get_post_meta(get_the_ID(), 'session_registration_text', true); ?>
                        </p>
                    </section>
                    <!-- /schedule__registration -->
                <?php } ?>
            </div>
            <!-- content__layout -->
        </div>
        <!-- /content__wrap -->
    </article>
    <!-- /content -->
</div>
<!-- /schedule__popup-layout -->