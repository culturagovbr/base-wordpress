<?php
$ef_options               = EF_Event_Options::get_theme_options();
$custom_logo_url          = !empty($ef_options['ef_logo']) ? $ef_options['ef_logo'] : '';
$menu_status              = isset($ef_options['ef_menu_status']) ? $ef_options['ef_menu_status'] : 1;
$menu_style               = isset($ef_options['ef_menu_style']) ? $ef_options['ef_menu_style'] : 'hamburger';
$register_button_position = isset($ef_options['ef_register_button_position']) ? $ef_options['ef_register_button_position'] : 'bottom';
$menu_class               = $menu_status == 1 ? 'site_opened' : '';
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>"/>
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="format-detection" content="telephone=no">
        <meta name="format-detection" content="address=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title><?php wp_title('&laquo;', true, 'right'); ?> <?php bloginfo('name'); ?></title>
        <link rel="icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico" type="image/x-icon"/>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="<?php echo get_template_directory_uri(); ?>/js/vendor/html5shiv.js"></script>
        <![endif]-->
        <!--[if IE]>
        <link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/ie.css"/><![endif]-->
        <?php wp_head(); ?>
        <script type="text/javascript">
            var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
            var recaptchaPublicKey = '<?php echo!empty($ef_options['efcb_contacts_recaptcha_public_key']) ? $ef_options['efcb_contacts_recaptcha_public_key'] : ''; ?>';
        </script>
    </head>
    <body>
        <!-- site -->
        <div class="site <?php echo $menu_class; ?>">
            <input type="hidden" name="base_url" id="base_url" value=" <?php echo get_site_url(); ?>/">
            <!-- site__header -->
            <header class="site__header">
                <!-- site__header-btn -->
                <button class="site__header-btn">
                    <?php if ($menu_style == 'hamburger') { ?>
                        <i class="fa fa-bars"></i>
                    <?php } else { ?>
                        <span><?php _e('Menu', 'khore'); ?></span>
                    <?php } ?>
                </button>
                <!-- /site__header-btn -->
                <!-- site__header-wrap -->
                <div class="site__header-wrap" id="site__header-wrap">
                    <div>
                        <?php if (!empty($custom_logo_url)) { ?>
                            <!-- logo -->
                            <div class="logo">
                                <?php
                                $frontpage_ID = get_option('page_on_front');
                                $post         = get_post($frontpage_ID);
                                $home_url     = $post->post_name;
                                ?>
                                <a href="<?php $home_url; ?>" data-page="<?php echo $frontpage_ID; ?>" class="page-lnk <?php
                                if (is_front_page()) {
                                    echo 'active';
                                }
                                ?>" data-url="" data-title="<?php echo get_the_title($frontpage_ID); ?>">
                                    <img src="<?php echo $custom_logo_url; ?>" width="70" height="70" alt="Khore">
                                </a>
                            </div>
                            <!-- /logo -->
                        <?php } ?>
                        <!-- menu -->
                        <?php if (!empty($ef_options['ef_show_register_btn']) && $register_button_position == 'top') { ?>
                            <!-- reg-btn -->
                            <a class="reg-btn" href="<?php echo $ef_options['ef_registerbtnurl']; ?>">
                                <i class="fa fa-ticket"></i>
                                <span><?php echo $ef_options['ef_registerbtntext']; ?></span>
                            </a>
                            <!-- /reg-btn -->
                        <?php } ?>
                        <nav class="menu">
                            <div class="menu-lang">
				<i class="fa "></i>         
                          	<a class="menu__item" id="pb"><span>pt</span></a>
                          	<a class="menu__item" id="es"><span>es</span></a>
                          	<a class="menu__item" id="en"><span>en</span></a>
                            </div>

                            <?php
                            global $wp;
                            $currentUrl = home_url(add_query_arg(array(), $wp->request));
                            if (substr($currentUrl, -1) == '/') {
                                $currentUrl = substr($currentUrl, 0, -1);
                            }
                            $locations = get_nav_menu_locations();
                            if (isset($locations) && isset($locations['primary'])) {
                                $menu = wp_get_nav_menu_items($locations['primary'], array(
                                    'orderby'                => 'menu_order',
                                    'post_type'              => 'nav_menu_item',
                                    'post_status'            => 'publish',
                                    'output'                 => ARRAY_A,
                                    'output_key'             => 'menu_order',
                                    'update_post_term_cache' => false));
                            } else {
                                $menu = array();
                            }
                            $json = '';

                            if (!empty($menu)) {
                                foreach ($menu as $key => $item) {
                                    $itemPermalink = get_permalink($item->object_id);
                                    if (substr($itemPermalink, -1) == '/') {
                                        $itemPermalink = substr($itemPermalink, 0, -1);
                                    }
                                    $active = $currentUrl == $itemPermalink ? 'active' : '';
                                    if ($item->menu_item_parent) {
                                        continue;
                                    }
                                    $classes = '';
                                    foreach ($item->classes as $class) {
                                        $classes .= ' ' . $class;
                                    }
                                    $sub_item       = '<i class="fa fa-plus"></i><i class="fa fa-minus"></i><div>';
                                    $is_parent_item = false;
                                    foreach ($menu as $k => $class_sub) {
                                        if ($class_sub->menu_item_parent && $class_sub->menu_item_parent == $item->ID) {

                                            if ($class_sub->object != 'custom') {
                                                $itemPermalink = get_permalink($class_sub->object_id);
                                                if (substr($itemPermalink, -1) == '/') {
                                                    $itemPermalink = substr($itemPermalink, 0, -1);
                                                }
                                                $active         = $currentUrl == $itemPermalink ? 'active' : '';
                                                $is_parent_item = true;
                                                $sub_url        = explode(home_url(), $class_sub->url);
                                                $sub_url        = str_replace('/', '', $sub_url[1]);
                                                $link_class     = 'page-lnk';
                                            } else {
                                                $sub_url    = $class_sub->url;
                                                $link_class = '';
                                                $active     = '';
                                            }
                                            $sub_item .= '<a href="' . $sub_url . '" data-page="' . $class_sub->object_id . '" data-url="' . $sub_url . '" class="' . $link_class . ' ' . $active . '" data-title="' . get_the_title($class_sub->object_id) . '">
                                            <i class="fa ' . $class_sub->custom_icon . '"></i><span>' . $class_sub->title . '</span>
                                        </a>';

                                            unset($class_sub);
                                        }
                                    }
                                    $sub_item .= '</div>';

                                    $postItem = get_post($item->object_id);

                                    if ($item->object != 'custom' && !has_shortcode($postItem->post_content, 'efcb-section-map')) {
                                        $url        = $postItem->post_name;
                                        $url_data   = explode(home_url(), $item->url);
                                        $url_data   = str_replace('/', '', $url_data[1]);
                                        $link_class = 'page-lnk';
                                    } else {
                                        $url        = $url_data   = $item->url;
                                        $link_class = '';
                                        $active     = '';
                                    }
                                    if ($is_parent_item) {
                                        $json .= '<div class="menu__item">
                                        <a href="' . $url . '" data-page="' . $item->object_id . '" data-url="' . $url . '" class="' . $link_class . ' ' . $active . '" data-title="' . get_the_title($item->object_id) . '">
                                        <i class="fa ' . $item->custom_icon . '"></i><span>' . $item->title . '</span></a>
                                        ' . $sub_item . '</div>';
                                    } else {
                                        $json .= '<a href="' . $url . '" data-page="' . $item->object_id . '" class="menu__item ' . $link_class . ' ' . $active . '" data-url="' . $url_data . '" data-title="' . get_the_title($item->object_id) . '">
                                        <i class="fa ' . $item->custom_icon . '"></i><span>' . $item->title . '</span>
                                    </a>';
                                    };
                                }
                            }
                            echo $json;
                            ?>
                        </nav>
                        <!-- /menu -->
                        <!-- countdown -->
                        <?php
                        echo do_shortcode('[efcb-section-event_timer]');
                        ?>
                        <!-- /countdown -->
                        <?php
                        if (!empty($ef_options['ef_show_register_btn']) && $register_button_position == 'bottom') {
                            $button_font_size    = !empty($ef_options['ef_registerbutton_font_size']) ? "font-size: {$ef_options['ef_registerbutton_font_size']};" : '';
                            $button_border_color = !empty($ef_options['ef_registerbutton_color']) ? "border-color: {$ef_options['ef_registerbutton_color']};" : '';
                            $button_text_color   = !empty($ef_options['ef_registerbutton_color']) ? "style=\"color: {$ef_options['ef_registerbutton_color']};\"" : '';
                            $button_style        = '';

                            if (!empty($button_font_size) || !empty($button_border_color)) {
                                $button_style = "style=\"$button_font_size $button_border_color\"";
                            }
                            ?>
                            <!-- reg-btn -->
                            <a class="reg-btn" href="<?php echo $ef_options['ef_registerbtnurl']; ?>" <?php echo $button_style; ?>>
                                <i class="fa fa-ticket"></i>
                                <span <?php echo $button_text_color; ?>><?php echo $ef_options['ef_registerbtntext']; ?></span>
                            </a>
                            <!-- /reg-btn -->
                        <?php } ?>
                    </div>
                </div>
                <!-- /site__header-wrap -->
                <!-- site__header-arrow -->
                <a href="#" class="site__header-arrow"></a>
                <!-- /site__header-arrow -->
            </header>
            <!-- /site__header -->
