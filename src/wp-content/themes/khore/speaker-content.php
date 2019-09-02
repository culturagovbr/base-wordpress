<?php
$speaker_id = get_the_ID();
add_filter('posts_fields', array('EF_Speakers_Helper', 'ef_speaker_sessions_posts_fields'));
add_filter('posts_orderby', array('EF_Speakers_Helper', 'ef_speaker_sessions_posts_orderby'));

$sessions_loop = EF_Session_Helper::get_sessions_loop();

remove_filter('posts_fields', array('EF_Speakers_Helper', 'ef_speaker_sessions_posts_fields'));
remove_filter('posts_orderby', array('EF_Speakers_Helper', 'ef_speaker_sessions_posts_orderby'));
?>
<!-- news__layout -->
<div class="news__layout">
    <!-- content -->
    <article class="content container">
        <!-- content__wrap -->
        <div class="content__wrap row">
            <!-- content__layout -->
            <div class="content__layout col-xs-12">
                <h1>
                    <?php the_title(); ?>
                    <span><?php echo get_post_meta($speaker_id, 'speaker_title', true); ?></span>
                </h1>
            </div>
            <!-- /content__layout -->
            <div class="col-xs-12">
                <div class="container">
                    <div class="row">
                        <div class="col-xs-12 col-sm-4">
                            <?php the_post_thumbnail('khore-speaker', array('title' => get_the_title(), 'alt' => get_the_title())); ?>
                        </div>
                        <!-- content__layout -->
                        <div class="content__layout col-xs-12 col-sm-8">
                            <?php the_content(); ?>
                        </div>
                        <!-- /content__layout -->
                    </div>
                </div>
            </div>
            <!-- content__layout -->
            <div class="content__layout col-xs-12">
                <?php
                if (get_post_meta($speaker_id, 'speaker_text', true)) {
                    echo stripcslashes(get_post_meta($speaker_id, 'speaker_text', true));
                }
                ?>
            </div>
            <!-- /content__layout -->
            <div class="col-xs-12">
                <!-- news__session -->
                <div class="news__session container">
                    <div class="row">
                        <div class="col-xs-12">
                            <h2><?php _e("MY SESSIONS", 'khore'); ?></h2>
                        </div>
                        <?php
                        if ($sessions_loop->have_posts()):
                            while ($sessions_loop->have_posts()):
                                $sessions_loop->the_post();
                                $session_speakers = get_post_meta(get_the_ID(), 'session_speakers_list', true);
                                if ($session_speakers && is_array($session_speakers) && in_array($speaker_id, $session_speakers)) {
                                    $date = get_post_meta(get_the_ID(), 'session_date', true);
                                    $locations = wp_get_post_terms(get_the_ID(), 'session-location', array('fields' => 'all'));
                                    $time = get_post_meta(get_the_ID(), 'session_time', true);
                                    $end_time = get_post_meta(get_the_ID(), 'session_end_time', true);
                                    if (!empty($time)) {
                                        $time_parts = explode(':', $time);
                                        if (count($time_parts) == 2)
                                            $time = date(get_option("time_format"), mktime($time_parts[0], $time_parts[1], 0));
                                    }
                                    if (!empty($end_time)) {
                                        $time_parts = explode(':', $end_time);
                                        if (count($time_parts) == 2)
                                            $end_time = date(get_option("time_format"), mktime($time_parts[0], $time_parts[1], 0));
                                    }
                                    $tracks = wp_get_post_terms(get_the_ID(), 'session-track', array('fields' => 'ids', 'count' => 1));
                                    if ($tracks && count($tracks) > 0)
                                        $color = EF_Taxonomy_Helper::ef_get_term_meta('session-track-metas', $tracks[0], 'session_track_color');
                                    else
                                        $color = '';
                                    ?>
                                    <div class="col-xs-12 col-lg-6">

                                        <article class="news__session-item">
                                            <header>
                                                <time><?php echo $time; ?>
                                                    - <?php echo $end_time; ?></time>
                                                <h3 <?php if (!empty($color)) echo(" style='color:$color;'"); ?>><?php echo!empty($locations) ? $locations[0]->name : ''; ?></h3>
                                            </header>
                                            <p>
                                                <?php the_title(); ?>
                                            </p>
                                            <!-- tags -->
                                            <div class="tags tags__small">

                                                <i class="fa fa-bookmark-o"></i>
                                                <?php
                                                if ($tracks && count($tracks) > 0) {
                                                    foreach ($tracks as $track) {
                                                        $color = EF_Taxonomy_Helper::ef_get_term_meta('session-track-metas', $track, 'session_track_color');
                                                        $term = get_term_by('id', $track, 'session-track');
                                                        ?>
                                                        <div>
                                                            <a href="#"><?php echo $term->name; ?></a>
                                                        </div>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </div>
                                            <!-- /tags -->
                                            <!-- btn -->
                                            <a href="<?php the_permalink(); ?>"
                                               class="btn btn_2"><?php _e('MORE INFO', 'khore'); ?></a>
                                            <!-- /btn -->
                                        </article>
                                    </div>
                                    <?php
                                }
                            endwhile;
                            wp_reset_postdata();
                        endif;
                        ?>
                    </div>
                </div>
                <!-- /news__session -->
            </div>
        </div>
        <!-- /content__wrap -->
    </article>
    <!-- /content -->
</div>
<!-- /news__layout -->
<a href="#" class="news__popup-close"><span class="glyph-icon flaticon-thin35"></span></a>