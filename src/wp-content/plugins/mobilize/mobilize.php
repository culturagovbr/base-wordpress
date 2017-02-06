<?php
/*
Plugin Name: Mobilize
Plugin URI: https://getbutterfly.com/wordpress-plugins-free/
Description: Mobilize adds a swipe menu to any site, when the width is lower than 720.
Version: 2.5.2
Author: Ciprian Popescu
Author URI: https://getbutterfly.com/
License: GPL3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
Text Domain: mobilize

Mobilize
Copyright (C) 2014, 2015, 2016 Ciprian Popescu (getbutterfly@gmail.com)

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if(!defined('ABSPATH')) exit; // Exit if accessed directly

register_activation_hook(__FILE__, 'mobilize_install');

function mobilize_install() {
    add_option('mobilize_flavour', '#333333', '', 'no');
    add_option('mobilize_text_flavour', '#ffffff', '', 'no');
    add_option('mobilize_button_flavour', '#116699', '', 'no');
    add_option('mobilize_font_size', 14, '', 'no');
    add_option('mobilize_icon_size', 20, '', 'no');
    add_option('mobilize_line_height', 24, '', 'no');

    add_option('mobilize_menubar_minwidth', 480, '', 'no');
    add_option('mobilize_menubar_maxwidth', 320, '', 'no');

    add_option('mobilize_show_site_logo', 0, '', 'no');
    add_option('mobilize_logo_width', 90, '', 'no');
}

register_nav_menus(array(
    'mobilize' => __('Mobilize Navigation', 'mobilize'),
));
register_sidebar(array(
	'name'          => __('Mobilize Widget (above menu)', 'mobilize'),
	'id'            => 'mobilize-widget-0',
	'description'   => __('This is the Mobilize widget. It will fit columns, text, icons.', 'mobilize'),
	'before_widget' => '<div class="mobilize-widget">',
	'after_widget'  => '</div>',
	'before_title'  => '<h5>',
	'after_title'   => '</h5>',
));
register_sidebar(array(
	'name'          => __('Mobilize Widget (below menu)', 'mobilize'),
	'id'            => 'mobilize-widget-1',
	'description'   => __('This is the Mobilize widget. It will fit columns, text, icons.', 'mobilize'),
	'before_widget' => '<div class="mobilize-widget">',
	'after_widget'  => '</div>',
	'before_title'  => '<h5>',
	'after_title'   => '</h5>',
));

add_action('admin_menu', 'mobilize_menu');

function mobilize_menu() {
    add_options_page(__('Mobilize', 'mobilize'), __('Mobilize', 'mobilize'), 'manage_options', 'mobilize_options', 'mobilize_options');
}

function mobilize_options() {
    ?>
	<div class="wrap">
		<h2>Mobilize</h2>

        <div id="gb-ad">
            <h3 class="gb-handle"><span class="dashicons dashicons-heart"></span> <?php _e('Thank you for using this plugin!', 'mobilize'); ?></h3>
            <div class="inside">
                <img src="<?php echo plugins_url('img/gb-logo-white-512.png', __FILE__); ?>" alt="getButterfly">
                <h4>This plugin is brought to you by <a href="https://getbutterfly.com/" target="_blank">getButterfly</a>, a WordPress themes &amp; plugins freelance agency.</h4>
                <hr>
                <p>If you enjoy this plugin, why not try our other plugins, <a href="https://getbutterfly.com/wordpress-plugins/imagepress/" target="_blank">ImagePress</a> or <a href="https://getbutterfly.com/wordpress-plugins/lighthouse/" target="_blank">Lighthouse</a>.<br>We would love for you to try out other products!</p>
            </div>
        </div>

        <h2><span class="dashicons dashicons-admin-generic"></span> <?php _e('Usage and Requirements', 'mobilize'); ?></h2>
        <p>Create a new menu and assign it to the "Mobilize" section. If you want to duplicate an existing menu, just assign it to the "Mobilize" section (tick the "Mobilize" checkbox).</p>
        <p>Your theme needs to have the <code>wp_footer()</code> function in your <code>footer.php</code> template. For best results, it is recommended, but not mandatory, to have the <code>&lt;?php wp_footer(); ?&gt;</code> line right before the closing <code>&lt;/body&gt;</code> tag.</p>

        <form method="post">
            <?php
            if(isset($_POST['mmsubmit'])) {
                update_option('mobilize_font_size', floatval($_POST['mobilize_font_size']));
                update_option('mobilize_icon_size', floatval($_POST['mobilize_icon_size']));
                update_option('mobilize_line_height', floatval($_POST['mobilize_line_height']));
                update_option('mobilize_flavour', sanitize_text_field($_POST['mobilize_flavour']));
                update_option('mobilize_text_flavour', sanitize_text_field($_POST['mobilize_text_flavour']));
                update_option('mobilize_button_flavour', sanitize_text_field($_POST['mobilize_button_flavour']));
                update_option('mobilize_menubar_minwidth', intval($_POST['mobilize_menubar_minwidth']));
                update_option('mobilize_menubar_maxwidth', intval($_POST['mobilize_menubar_maxwidth']));
                update_option('mobilize_show_site_logo', intval($_POST['mobilize_show_site_logo']));
                update_option('mobilize_logo_width', intval($_POST['mobilize_logo_width']));

                echo '<div id="setting-error-settings_updated" class="updated settings-error"><p><strong>' . __('Settings saved.', 'mobilize') . '</strong></p></div>';
            }
            ?>

            <hr>
            <h2><span class="dashicons dashicons-admin-generic"></span> <?php _e('General Options', 'mobilize'); ?></h2>
            <p>
                <input type="number" name="mobilize_icon_size" id="mobilize_icon_size" min="8" max="72" value="<?php echo get_option('mobilize_icon_size'); ?>"> <label for="mobilize_icon_size">Menu icon size (px) (default is <b>20</b>)</label>
            </p>

            <p>
                <input type="number" name="mobilize_font_size" id="mobilize_font_size" min="8" max="72" value="<?php echo get_option('mobilize_font_size'); ?>"> <label for="mobilize_font_size">Menu font size (px) (default is <b>14</b>)</label>
                <br>
                <input type="number" name="mobilize_line_height" id="mobilize_line_height" min="8" max="72" value="<?php echo get_option('mobilize_line_height'); ?>"> <label for="mobilize_line_height">Menu line height (px) (default is <b>24</b>)</label>
            </p>

            <hr>
            <h2><span class="dashicons dashicons-admin-generic"></span> <?php _e('Content Options', 'mobilize'); ?></h2>
            <p>
                <input type="checkbox" name="mobilize_show_site_logo" value="1" <?php if(get_option('mobilize_show_site_logo') == 1) echo 'checked'; ?>> <label>Show custom site logo (needs theme support)</label>
            </p>

            <p>
                <input type="number" name="mobilize_logo_width" id="mobilize_logo_width" min="16" max="720" value="<?php echo get_option('mobilize_logo_width'); ?>"> <label for="mobilize_logo_width">Custom logo width (px)</label>
            </p>

            <p>
				<label for="mobilize_flavour"><b>Mobilize</b> background colour</label>
				<br><input type="text" name="mobilize_flavour" class="mobilize_colorPicker" data-default-color="#333333" value="<?php echo get_option('mobilize_flavour'); ?>">
				<br><small>This is the colour of the menu container</small>
			</p>
			<p>
				<label for="mobilize_text_flavour"><b>Mobilize</b> text colour</label>
				<br><input type="text" name="mobilize_text_flavour" class="mobilize_colorPicker" data-default-color="#ffffff" value="<?php echo get_option('mobilize_text_flavour'); ?>">
				<br><small>This is the colour of the menu text</small>
			</p>
			<p>
				<label for="mobilize_button_flavour"><b>Mobilize</b> button hover colour</label>
				<br><input type="text" name="mobilize_button_flavour" class="mobilize_colorPicker" data-default-color="#116699" value="<?php echo get_option('mobilize_button_flavour'); ?>">
				<br><small>This is the colour of the menu button (when hover)</small>
			</p>

            <hr>
            <h2><span class="dashicons dashicons-admin-generic"></span> <?php _e('Menu Button Appearance', 'mobilize'); ?></h2>
            <p>
                <input type="number" name="mobilize_menubar_minwidth" id="mobilize_menubar_minwidth" min="0" max="9999" value="<?php echo get_option('mobilize_menubar_minwidth'); ?>"> <label for="mobilize_menubar_minwidth">Minimum width for menu button (px) (default is <b>480</b>)</label>
                <br>
                <input type="number" name="mobilize_menubar_maxwidth" id="mobilize_menubar_maxwidth" min="0" max="9999" value="<?php echo get_option('mobilize_menubar_maxwidth'); ?>"> <label for="mobilize_menubar_maxwidth">Maximum width for menu button (px) (default is <b>320</b>)</label>
                <br><small>Combine widths such as 720 with 480 or 480 with 320.</small>
                <br><small>The former is recommended for tablets, while the latter is recommended for mobile devices. Select 480 with 320 as default.</small>
            </p>

            <hr>
			<p>
				<input name="mmsubmit" type="submit" class="button-primary" value="Save Changes">
			</p>
        </form>

        <div class="postbox">
            <div class="inside">
                <p>For support, feature requests and bug reporting, please visit the <a href="https://getbutterfly.com/" rel="external">official website</a>.</p>
                <p>&copy;<?php echo date('Y'); ?> <a href="https://getbutterfly.com/" rel="external"><strong>getButterfly</strong>.com</a> &middot; <small>Code wrangling since 2005</small></p>
            </div>
        </div>
	</div>
    <?php
}

function mobilize_enqueue_color_picker() {
	wp_enqueue_style('wp-color-picker');
    wp_enqueue_style('gbad', plugins_url('css/gbad.css', __FILE__));
	wp_enqueue_script('mobilize_color_picker', plugins_url('js/mobilize-functions.js', __FILE__ ), array('wp-color-picker'), false, true);
}

// Frontend actions and filters
add_action('wp_enqueue_scripts', 'mobilize_custom_css');
add_action('wp_footer', 'mobilize');
add_filter('body_class', function($classes) {
    return array_merge($classes, array('mobilize'));
});
//

add_action('admin_enqueue_scripts', 'mobilize_enqueue_color_picker');

function mobilize_custom_css() {
    wp_enqueue_style('fa', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.3/css/font-awesome.min.css');
    wp_enqueue_style('mobilize-css', plugins_url('css/jquery.mmenu.css', __FILE__));

    $mobilize_flavour = get_option('mobilize_flavour');
    $mobilize_text_flavour = get_option('mobilize_text_flavour');
    $mobilize_button_flavour = get_option('mobilize_button_flavour');
    $mobilize_font_size = get_option('mobilize_font_size');
    $mobilize_icon_size = get_option('mobilize_icon_size');
    $mobilize_line_height = get_option('mobilize_line_height');

    $mobilize_menubar_minwidth = get_option('mobilize_menubar_minwidth');
    $mobilize_menubar_maxwidth = get_option('mobilize_menubar_maxwidth');

    $mobilize_logo_width = get_option('mobilize_logo_width');

    $css = '#navid > ul,
    #navid #toggle,
    .mobilize-container { background-color: ' . $mobilize_flavour . '; }
    #navid ul li a { color: ' . $mobilize_text_flavour . '; font-size: ' . $mobilize_font_size . 'px; line-height: ' . $mobilize_line_height . 'px; }
    #navid #toggle { color: ' . $mobilize_text_flavour . '; }
    #navid #toggle:hover { background-color: ' . $mobilize_button_flavour . '; }
    #navid #toggle .fa { font-size: ' . $mobilize_icon_size . 'px; }
    #navid .custom-logo { width: ' . $mobilize_logo_width . 'px; height: auto; }

    .mobilize-widget,
    .mobilize-menu-logo,
    .custom-logo-link {
        background-color: ' . $mobilize_flavour . ';
        color: ' . $mobilize_text_flavour . ' !important;
    }
    .mobilize-widget {
        font-size: ' . $mobilize_font_size . 'px;
    }
    .mobilize-widget * {
        color: ' . $mobilize_text_flavour . ' !important;
    }

    @media screen and (min-width: ' . $mobilize_menubar_minwidth . 'px) {
        #navid #toggle { display: none; }
    }
    @media screen and (max-width: ' . $mobilize_menubar_maxwidth . 'px) {
        body.mobilize { padding-top: 48px; }
        #navid #toggle { display: block; }
    }';

    wp_add_inline_style('mobilize-css', $css);
}

function get_mobilize_sidebar($index) {
    $sidebar_contents = '';
    ob_start();
    dynamic_sidebar($index);
    $sidebar_contents = ob_get_clean();

    return $sidebar_contents;
}

function mobilize() {
    $display .= '<input type="checkbox" id="nav-toggle">
    <div id="navid">
        <label for="nav-toggle" id="toggle"><i class="fa fa-bars"></i></label>
        <div class="mobilize-spacer-48"></div>
        <div class="mobilize-container">';
            $mobilize_show_site_logo = get_option('mobilize_show_site_logo');
            if((int) $mobilize_show_site_logo === 1) {
                $display .= get_custom_logo();
                if(!has_custom_logo()) {
                    $display .= '<a href="' . esc_url(home_url()) . '" class="mobilize-menu-logo">' . get_bloginfo('name') . '</a>';
                }
            }

            if(is_active_sidebar('mobilize-widget-0')) {
                $display .= get_mobilize_sidebar('mobilize-widget-0');
            }

            $display .= wp_nav_menu(array('theme_location' => 'mobilize', 'menu_class' => '', 'container' => false, 'echo' => false));

            if(is_active_sidebar('mobilize-widget-1')) {
                $display .= get_mobilize_sidebar('mobilize-widget-1');
            }
            $display .= '</div>
        </div>';

    $display .= '<!-- END MOBILIZE -->';

    echo $display;
}