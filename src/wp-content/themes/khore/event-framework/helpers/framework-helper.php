<?php

/**
 * Helper class for Framework Specific settings
 * 
 * @author metodiew
 *
 */
class EF_Framework_Helper {

    private static $default_framework_name = 'Event Framework';

    /**
     * 
     */
    public static function get_framework_name($name = false) {

        return self::$default_framework_name;
    }

    /**
     * Return current activated theme name
     */
    public static function get_theme_name() {

        $theme_name = wp_get_theme();

        return $theme_name['Name'];
    }

    /**
     * 
     */
    public static function get_widget_name() {
        $widget_name = wp_get_theme();

        return $widget_name['Name'];
    }

    public static function get_framework_logo_src() {
        $img_path = '';

        return apply_filters('ef_theme_options_logo', $img_path);
    }

    /*
      Function to get content between two given tags.
      Author : portrave.showthemes2
     */

    public static function get_text_between_two_tags($string, $start, $end) {

        $string = " " . $string;
        $ini    = strpos($string, $start);
        if ($ini == 0)
            return "";
        $ini += strlen($start);
        $len    = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    public static function get_styles_from_shortcode_attributes($style_groups, $atts) {
        $ret = array();
        foreach ($style_groups as $style_group => $styles) {
            $style = '';
            foreach ($styles as $property => $att_name) {
                if (!empty($atts[$att_name])) {
                    $style .= "$property: $atts[$att_name];"; //!important
                }
            }
            if (!empty($style)) {
                $ret[$style_group] = "style=\"$style\"";
            } else {
                $ret[$style_group] = '';
            }
        }
        return $ret;
    }

    public static function get_video_thumbnail($url, $sizes) {
        $ret = '';
        try {
            if (!empty($url)) {
                $image_url = parse_url($url);

                if (!empty($image_url['host'])) {
                    if ($image_url['host'] == 'www.youtube.com' || $image_url['host'] == 'youtube.com') {
                        $query_params = explode('&', $image_url['query']);
                        if (!empty($query_params)) {
                            foreach ($query_params as $query_param) {
                                $parts = explode('=', $query_param);
                                if ($parts[0] == 'v') {
                                    $ret = "http://img.youtube.com/vi/" . $parts[1] . "/{$sizes['youtube']}.jpg";
                                    break;
                                }
                            }
                        }
                    } else if ($image_url['host'] == 'www.vimeo.com' || $image_url['host'] == 'vimeo.com') {
                        $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/" . substr($image_url['path'], 1) . ".php"));
                        $ret  = $hash[0][$sizes['vimeo']];
                    }
                }
            }
        } catch (Exception $e) {
            
        }

        return $ret;
    }

}
