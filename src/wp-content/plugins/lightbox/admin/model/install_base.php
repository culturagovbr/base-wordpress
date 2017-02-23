<?php

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

global $wpdb;

$model = new Hugeit_Lightbox_Model();
$table_name = $wpdb->prefix . "hugeit_lightbox";
if (!get_option('hugeit_lightbox_title')) {
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name) {
        $query = "SELECT name, value FROM " . $table_name . " WHERE name='light_box_style' || name='light_box_transition' || name='light_box_speed' || name='light_box_fadeout' || name='light_box_title'";
        $lightbox_params = $wpdb->get_results($query);
        foreach ($lightbox_params as $lightbox_param) {
            $new_param = str_replace('light_box_', 'hugeit_lightbox_', $lightbox_param->name);
            update_option($new_param, $lightbox_param->value);
            update_option('hugeit_lightbox_type', 'old_type');
        }
    } else {
        $lightbox_options = $model->general_options();
        foreach ($lightbox_options as $name => $value) {
            update_option($name, $value);
            update_option('hugeit_lightbox_type', 'new_type');
        }
    }
}

$resp_lightbox_options = $model->general_resp_options();
if (!get_option('hugeit_lightbox_loop_new')) {
    foreach ($resp_lightbox_options as $name => $value) {
        update_option($name, $value);
    }
}

if( !get_option( 'hugeit_lightbox_fullwidth_effect' ) ) {
    update_option('hugeit_lightbox_fullwidth_effect', 'false');
}