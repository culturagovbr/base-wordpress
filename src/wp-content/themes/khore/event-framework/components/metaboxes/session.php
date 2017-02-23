<?php
add_action('add_meta_boxes', 'ef_session_metabox');

function ef_session_metabox() {
    global $ef_speaker_label_plural;

    add_meta_box('metabox-session', __('Session Details', 'dxef'), 'ef_metabox_session', 'session', 'normal', 'high');
    add_meta_box('metabox-session-speakers', $ef_speaker_label_plural, 'ef_metabox_session_speakers', 'session', 'normal', 'high');
}

function ef_metabox_session($post) {
    $session_date = get_post_meta($post->ID, 'session_date', true);
    $session_time = get_post_meta($post->ID, 'session_time', true);
    $session_end_time = get_post_meta($post->ID, 'session_end_time', true);
    $session_home = get_post_meta($post->ID, 'session_home', true);
    $session_registration_code = get_post_meta($post->ID, 'session_registration_code', true);
    $session_registration_title = get_post_meta($post->ID, 'session_registration_title', true);
    $session_registration_text = get_post_meta($post->ID, 'session_registration_text', true);
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function () {
            jQuery('#session_date_str').datepicker({
                changeMonth: true,
                changeYear: true,
                dateFormat: 'mm/dd/yy',
                altFormat: 'yy-mm-dd',
                altField: '#session_date'
            });
        });
    </script>
    <p>
        <label for="session_home"><?php _e('Show in home page', 'dxef'); ?></label>
        <input type="checkbox" id="session_home" name="session_home" value="1" <?php if ($session_home == 1) echo 'checked="checked"'; ?> />
    </p>   
    <p>
        <label for="session_date"><?php _e('Date', 'dxef'); ?></label>
        <input type="text" id="session_date_str" name="session_date" value="<?php echo!empty($session_date) ? date('m/d/Y', $session_date) : ''; ?>" />
    </p>   
    <p>
        <label for="session_time"><?php _e('Start Time', 'dxef'); ?></label>
        <input type="text" id="session_time" name="session_time" value="<?php echo $session_time; ?>" />
        <span><?php _e('Format hh:mm', 'dxef'); ?></span>
    <p>
        <label for="session_end_time"><?php _e('End Time', 'dxef'); ?></label>
        <input type="text" id="session_end_time" name="session_end_time" value="<?php echo $session_end_time; ?>" />
        <span><?php _e('Format hh:mm', 'dxef'); ?></span>
    </p>
    <p class="update-nag" style="margin-top: 0;">
        <?php _e('Please enter the time in 24 hours and four digits format. 9am should be inputted as 09:00 <strong>not</strong> as 9:00 - notice the lack of a zero.', 'dxef'); ?>
    </p>
    <p>
        <label for="session_registration_code"><?php _e('Registration Embed Code:', 'dxef'); ?></label><br/>
        <textarea id="session_registration_code" name="session_registration_code" cols="50" rows="5"><?php echo $session_registration_code; ?></textarea>
    </p>
    <p>
        <label for="session_registration_title"><?php _e('Registration Title:', 'dxef'); ?></label><br/>
        <input type="text" id="session_registration_title" name="session_registration_title" value="<?php echo $session_registration_title; ?>" />
    </p>
    <p>
        <label for="session_registration_text"><?php _e('Registration Text:', 'dxef'); ?></label><br/>
        <textarea id="session_registration_text" name="session_registration_text" cols="50" rows="5"><?php echo $session_registration_text; ?></textarea>
    </p>
    <?php
}

function ef_metabox_session_speakers($post) {
    global $ef_speaker_label_plural;

    $speakers = get_posts(array(
        'post_type' => 'speaker',
        'post_status' => 'publish',
        'suppress_filters' => false,
        'posts_per_page' => -1,
        'orderby' => 'title',
        'order' => 'ASC'
    ));

    $session_speakers_list = get_post_meta($post->ID, 'session_speakers_list', true);

    $meta_query = '';
    if (empty($session_speakers_list)) {
        $meta_query = array('key' => 'session_speakers_list');
    }

    $selected_speakers_query = new WP_Query(array(
        'post_type' => 'speaker',
        'post__in' => $session_speakers_list,
        'meta_query' => array($meta_query),
        'posts_per_page' => -1
    ));

    $ignored_speakers_query = new WP_Query(array(
        'post_type' => 'speaker',
        'post_status' => 'publish',
        'post__not_in' => $session_speakers_list,
        'orderby' => 'title',
        'order' => 'ASC',
        'posts_per_page' => -1
    ));
    ?>

    <script>
        jQuery(function () {
            jQuery("#selected_speakers, #ignored_speakers").sortable({
                connectWith: ".sortable1",
                update: function (event, ui) {
                    jQuery('#session_speakers_list').val(jQuery("#selected_speakers").sortable('toArray', {
                        attribute: "data-id"
                    }));
                }
            }).disableSelection();
        });
    </script>

    <div class="sortable-container">
        <p><?php echo sprintf(__('Select and order %s to show in this section', 'dxef'), $ef_speaker_label_plural); ?></p>
        <ul id="selected_speakers" class="sortable destination sortable1">
            <?php
            if (!empty($session_speakers_list[0])) {
                foreach ($session_speakers_list as $key => $speaker) {
                    ?>
                    <li class="ui-state-default" data-id="<?php echo $speaker; ?>"><?php echo get_the_title($speaker); ?></li>
                    <?php
                }
            }
            ?>
        </ul>

        <ul id="ignored_speakers" class="sortable source sortable1">
            <?php
            while ($ignored_speakers_query->have_posts()) :
                $ignored_speakers_query->the_post();
                ?>
                <li class="ui-state-default" data-id="<?php the_ID(); ?>"><?php the_title(); ?></li>
                <?php
            endwhile;
            wp_reset_query();
            wp_reset_postdata();
            ?>
        </ul>
        <input type="hidden" id="session_speakers_list" name="session_speakers_list[]" value="<?php
               if (!empty($session_speakers_list)) {
                   echo implode(',', $session_speakers_list);
               }
               ?>" />
    </div>
    <?php
}

add_action('save_post', 'ef_session_save_post');

function ef_session_save_post($id) {
    if (isset($_POST['post_type']) && $_POST['post_type'] === 'session') {
        if (isset($_POST['session_home']))
            update_post_meta($id, 'session_home', $_POST['session_home']);
        else
            delete_post_meta($id, 'session_home');

        if (isset($_POST['session_date']))
            update_post_meta($id, 'session_date', strtotime($_POST['session_date']));

        if (isset($_POST['session_time']))
            update_post_meta($id, 'session_time', $_POST['session_time']);

        if (isset($_POST['session_end_time']))
            update_post_meta($id, 'session_end_time', $_POST['session_end_time']);

        if (isset($_POST['session_registration_code']))
            update_post_meta($id, 'session_registration_code', $_POST['session_registration_code']);
        else
            delete_post_meta($id, 'session_registration_code');

        if (isset($_POST['session_registration_title']))
            update_post_meta($id, 'session_registration_title', $_POST['session_registration_title']);
        else
            delete_post_meta($id, 'session_registration_title');

        if (isset($_POST['session_registration_text']))
            update_post_meta($id, 'session_registration_text', $_POST['session_registration_text']);
        else
            delete_post_meta($id, 'session_registration_text');

        // AJAX Speakers Order
        if (isset($_POST['session_speakers_list'])) {
            $session_speakers = $_POST['session_speakers_list'];
            if (!empty($session_speakers[0])) {
                $session_speakers = explode(',', $session_speakers[0]);
                update_post_meta($id, 'session_speakers_list', $session_speakers);
            } else {
                delete_post_meta($id, 'session_speakers_list');
            }
        }
    }
}
