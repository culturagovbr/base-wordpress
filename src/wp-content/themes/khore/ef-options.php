<?php

global $ef_panel_manager;
$theme_options = $ef_panel_manager->get_panel('theme_options');

/*
 * Generate Theme Options Tabs
 */

$tab_general_site_options = new EF_Options_Tab();
$tab_menu_options = new EF_Options_Tab();
$tab_homepage_options = new EF_Options_Tab();
$tab_misc = new EF_Options_Tab();
$tab_social_connecting = new EF_Options_Tab();
$tab_contacts = new EF_Options_Tab();
$tab_api = new EF_Options_Tab();

/*
 * General Site Options Fields
 */
$font = new EF_Select_Field(
        'ef_font', __('General Font', 'khore'), '', array('options' => array(
        '' => __('Default', 'khore'),
        'Open Sans' => __('Open Sans', 'khore'),
        'Droid Sans' => __('Droid Sans', 'khore'),
        'PT Sans' => __('PT Sans', 'khore'),
        'Lato' => __('Lato', 'khore'),
        'Oswald' => __('Oswald', 'khore'),
        'Droid Serif' => __('Droid Serif', 'khore'),
        'Roboto' => __('Roboto', 'khore'),
        'Lora' => __('Lora', 'khore'),
        'Libre Baskerville' => __('Libre Baskerville', 'khore'),
        'Josefin Slab' => __('Josefin Slab', 'khore'),
        'Arvo' => __('Arvo', 'khore'),
        'Ubuntu' => __('Ubuntu', 'khore'),
        'Raleway' => __('Raleway', 'khore'),
        'Source Sans Pro' => __('Source Sans Pro', 'khore'),
        'Lobster' => __('Lobster', 'khore'),
        'PT Serif' => __('PT Serif', 'khore'),
        'Old Standard TT' => __('Old Standard TT', 'khore'),
        'Volkorn' => __('Volkorn', 'khore'),
        'Gravitas One' => __('Gravitas One', 'khore'),
        'Merriweather' => __('Merriweather', 'khore'),
    ))
);
$primary_color = new EF_Color_Field('ef_primary_color', __('Primary Color', 'khore'), '', array('default' => ''));
$secondary_color = new EF_Color_Field('ef_secondary_color', __('Secondary Color', 'khore'), '', array('default' => ''));
$tertiary_color = new EF_Color_Field('ef_tertiary_color', __('Tertiary Color', 'khore'), '', array('default' => ''));
$primary_background_color = new EF_Color_Field('ef_primary_background_color', __('Primary Background Color', 'khore'), '', array('default' => ''));
$secondary_background_color = new EF_Color_Field('ef_secondary_background_color', __('Secondary Background Color', 'khore'), '', array('default' => ''));
$speaker_label_singular = new EF_Text_Field('ef_speaker_label_singular', __('Singular Performer Label', 'khore'));
$speaker_label_plural = new EF_Text_Field('ef_speaker_label_plural', __('Plural Performer Label', 'khore'));

/*
 * Menu Options Fields
 */
$menu_status = new EF_Select_Field(
        'ef_menu_status', __('Default Menu Behaviour', 'khore'), '', array('options' => array(
        '0' => 'Closed',
        '1' => 'Expanded',
        )));
$menu_style = new EF_Select_Field(
        'ef_menu_style', __('Menu Style', 'khore'), '', array('options' => array(
        'hamburger' => __('Hamburger', 'khore'),
        'text' => __('Text', 'khore'),
        )));
$logo = new EF_Image_Field('ef_logo', __('Menu Logo (Recommended size: 70x70)', 'khore'));
$countdown_datatime = new EF_Datapicker_Text_Field('ef_countdown', __('Countdown Date/Time', 'khore'));
$event_register_btn = new EF_Checkbox_Field('ef_show_register_btn', __('Show register button', 'khore'));
$event_registerbtn_text = new EF_Text_Field('ef_registerbtntext', __('Register button text', 'khore'));
$event_registerbtn_url = new EF_Text_Field('ef_registerbtnurl', __('Register button url', 'khore'));
$register_button_position = new EF_Select_Field(
        'ef_register_button_position', __('Register button position', 'khore'), '', array('options' => array(
        'top' => __('Top', 'khore'),
        'bottom' => __('Bottom', 'khore'),
    ))
);
$menu_item_font_size = new EF_Text_Field('ef_menu_item_font_size', __('Menu Item Font Size', 'khore'), __('Example: 11px', 'khore'));
$register_button_color = new EF_Color_Field('ef_registerbutton_color', __('Register Button Color', 'khore'), '', array('default' => ''));
$register_button_font_size = new EF_Text_Field('ef_registerbutton_font_size', __('Register Button Font Size', 'khore'), __('Example: 11px', 'khore'));
$menu_background_color = new EF_Color_Field('ef_menu_background_color', __('Menu Background Color', 'khore'), '', array('default' => ''));
$menu_font_color = new EF_Color_Field('ef_menu_font_color', __('Menu Font Color', 'khore'), '', array('default' => ''));
$menu_item_background_hover_color = new EF_Color_Field('ef_menu_item_background_hover_color', __('Menu Item Background Hover Color', 'khore'), '', array('default' => ''));
$menu_item_font_hover_color = new EF_Color_Field('ef_menu_item_font_hover_color', __('Menu Item Font Hover Color', 'khore'), '', array('default' => ''));

/*
 * Homepage Options Fields
 */
$event_title = new EF_Text_Field('ef_herotitle', __('Event Title', 'khore'));
$event_title_color = new EF_Color_Field('ef_herotitle_color', __('Event Title Color', 'khore'), '', array('default' => ''));
$event_title_font_size = new EF_Text_Field('ef_herotitle_font_size', __('Event Title Font Size', 'khore'), __('Example: 11px', 'khore'));
$event_subtitle = new EF_Text_Field('ef_herosubtitle', __('Event Subtitle', 'khore'));
$event_subtitle_color = new EF_Color_Field('ef_herosubtitle_color', __('Event Subtitle Color', 'khore'), '', array('default' => ''));
$event_subtitle_font_size = new EF_Text_Field('ef_herosubtitle_font_size', __('Event Subtitle Font Size', 'khore'), __('Example: 11px', 'khore'));
$event_date_time_place = new EF_Text_Field('ef_datetimeplace', __('Date/Time and place', 'khore'));
$event_date_time_place_color = new EF_Color_Field('ef_datetimeplace_color', __('Date/Time and Place Color', 'khore'), '', array('default' => ''));
$event_date_time_place_font_size = new EF_Text_Field('ef_datetimeplace_font_size', __('Date/Time and Place Font Size', 'khore'), __('Example: 11px', 'khore'));
$header_logo = new EF_Image_Field('ef_header_logo', __('Home Page Logo (Recommended size: 300x101)', 'khore'));
$header_logo_remove_size = new EF_Checkbox_Field('ef_logo_remove_size', __('Remove logo home page optimized size', 'khore'));
$header_gallery = new EF_Gallery_Field('ef_header_gallery', __('Header Gallery', 'khore'), __('Upload more than one picture to show slider', 'khore'), array('style' => 'style="width:100%;"'));
$header_video = new EF_Text_Field('ef_header_video', __('Header Video Url', 'khore'));
$video_button_color = new EF_Color_Field('ef_videobutton_color', __('Video Button Color', 'khore'), '', array('default' => ''));
$home_event_register_btn = new EF_Checkbox_Field('ef_home_show_register_btn', __('Show register button', 'khore'));
$home_event_registerbtn_text = new EF_Text_Field('ef_home_registerbtntext', __('Register button text', 'khore'));
$home_event_registerbtn_url = new EF_Text_Field('ef_home_registerbtnurl', __('Register button url', 'khore'));
$home_register_button_color = new EF_Color_Field('ef_home_registerbutton_color', __('Register Button Color', 'khore'), '', array('default' => ''));
$home_register_button_font_size = new EF_Text_Field('ef_home_registerbutton_font_size', __('Register Button Font Size', 'khore'), __('Example: 11px', 'khore'));

// Social and Connecting
$social_facebook = new EF_Text_Field('ef_facebook', __('Facebook URL', 'khore'));
$social_twitter = new EF_Text_Field('ef_twitter', __('Twitter URL', 'khore'));
$social_rss = new EF_Checkbox_Field('ef_rss', __('Show RSS?', 'khore'));
$social_email = new EF_Text_Field('ef_email', __('Email Address', 'khore'));
$social_google_plus = new EF_Text_Field('ef_google_plus', __('Google+ URL', 'khore'));
$social_flickr = new EF_Text_Field('ef_flickr', __('Flickr URL', 'khore'));
$social_instagram = new EF_Text_Field('ef_instagram', __('Instagram URL', 'khore'));
$social_pinterest = new EF_Text_Field('ef_pinterest', __('Pinterest URL', 'khore'));
$social_linkedin = new EF_Text_Field('ef_linkedin', __('LinkedIn URL', 'khore'));
$social_add_this = new EF_Text_Field('ef_add_this_pubid', __('AddThis PubID', 'khore'));
$social_youtube = new EF_Text_Field('ef_youtube', __('Youtube URL', 'khore'));
$social_skype = new EF_Text_Field('ef_skype', __('Skype User', 'khore'));
$social_vimeo = new EF_Text_Field('ef_vimeo', __('Vimeo URL', 'khore'));

// Misc Fields
$misc_importer = new EF_Importer_Field('misc-importer', __('Demo Data', 'khore'), __('Import test data. Success message will follow.', 'khore'));

// Add fields to General Site Options
$tab_general_site_options->add_field('ef_title_font', new EF_Separator_Field(__('Font', 'khore'), '', ''));
$tab_general_site_options->add_field('ef_font', $font);
$tab_general_site_options->add_field('ef_title_colors', new EF_Separator_Field(__('Colors', 'khore'), '', ''));
$tab_general_site_options->add_field('ef_primary_color', $primary_color);
$tab_general_site_options->add_field('ef_secondary_color', $secondary_color);
$tab_general_site_options->add_field('ef_tertiary_color', $tertiary_color);
$tab_general_site_options->add_field('ef_primary_background_color', $primary_background_color);
$tab_general_site_options->add_field('ef_secondary_background_color', $secondary_background_color);
$tab_general_site_options->add_field('ef_title_performers', new EF_Separator_Field(__('Performers', 'khore'), '', __('How would you like to call your performers? (Default: Speakers)', 'khore')));
$tab_general_site_options->add_field('ef_speaker_label_singular', $speaker_label_singular);
$tab_general_site_options->add_field('ef_speaker_label_plural', $speaker_label_plural);

// Add fields to Menu Options
$tab_menu_options->add_field('ef_menu_status', $menu_status);
$tab_menu_options->add_field('ef_menu_style', $menu_style);
$tab_menu_options->add_field('ef_logo', $logo);
$tab_menu_options->add_field('ef_countdown', $countdown_datatime);
$tab_menu_options->add_field('ef_show_register_btn', $event_register_btn);
$tab_menu_options->add_field('ef_registerbtntext', $event_registerbtn_text);
$tab_menu_options->add_field('ef_registerbtnurl', $event_registerbtn_url);
$tab_menu_options->add_field('ef_register_button_position', $register_button_position);
$tab_menu_options->add_field('ef_registerbutton_color', $register_button_color);
$tab_menu_options->add_field('ef_registerbutton_font_size', $register_button_font_size);
$tab_menu_options->add_field('ef_menu_background_color', $menu_background_color);
$tab_menu_options->add_field('ef_menu_font_color', $menu_font_color);
$tab_menu_options->add_field('ef_menu_item_background_hover_color', $menu_item_background_hover_color);
$tab_menu_options->add_field('ef_menu_item_font_hover_color', $menu_item_font_hover_color);
$tab_menu_options->add_field('ef_menu_item_font_size', $menu_item_font_size);

// Add fields to Homepage Options
$tab_homepage_options->add_field('ef_title_hometitle', new EF_Separator_Field(__('Title Settings', 'khore'), '', ''));
$tab_homepage_options->add_field('ef_herotitle', $event_title);
$tab_homepage_options->add_field('ef_herotitle_color', $event_title_color);
$tab_homepage_options->add_field('ef_herotitle_font_size', $event_title_font_size);
$tab_homepage_options->add_field('ef_title_homesubtitle', new EF_Separator_Field(__('Subtitle Settings', 'khore'), '', ''));
$tab_homepage_options->add_field('ef_herosubtitle', $event_subtitle);
$tab_homepage_options->add_field('ef_herosubtitle_color', $event_subtitle_color);
$tab_homepage_options->add_field('ef_herosubtitle_font_size', $event_subtitle_font_size);
$tab_homepage_options->add_field('ef_title_datetime', new EF_Separator_Field(__('Date/Time and Place Settings', 'khore'), '', ''));
$tab_homepage_options->add_field('ef_datetimeplace', $event_date_time_place);
$tab_homepage_options->add_field('ef_datetimeplace_color', $event_date_time_place_color);
$tab_homepage_options->add_field('ef_datetimeplace_font_size', $event_date_time_place_font_size);
$tab_homepage_options->add_field('ef_title_logo', new EF_Separator_Field(__('Logo Settings', 'khore'), '', ''));
$tab_homepage_options->add_field('ef_header_logo', $header_logo);
$tab_homepage_options->add_field('ef_logo_remove_size', $header_logo_remove_size);
$tab_homepage_options->add_field('ef_title_slider', new EF_Separator_Field(__('Slider Settings', 'khore'), '', ''));
$tab_homepage_options->add_field('ef_header_gallery', $header_gallery);
$tab_homepage_options->add_field('ef_title_video', new EF_Separator_Field(__('Video Settings', 'khore'), '', ''));
$tab_homepage_options->add_field('ef_header_video', $header_video);
$tab_homepage_options->add_field('ef_videobutton_color', $video_button_color);
$tab_homepage_options->add_field('ef_title_register', new EF_Separator_Field(__('Register Button Settings', 'khore'), '', ''));
$tab_homepage_options->add_field('ef_home_show_register_btn', $home_event_register_btn);
$tab_homepage_options->add_field('ef_home_registerbtntext', $home_event_registerbtn_text);
$tab_homepage_options->add_field('ef_home_registerbtnurl', $home_event_registerbtn_url);
$tab_homepage_options->add_field('ef_home_registerbutton_color', $home_register_button_color);
$tab_homepage_options->add_field('ef_home_registerbutton_font_size', $home_register_button_font_size);

// Add fields to Misc tab
$tab_misc->add_field('misc_importer', $misc_importer);

// Add fields to Social and Connecting tab
$tab_social_connecting->add_field('ef_linkedin', $social_linkedin);
$tab_social_connecting->add_field('ef_twitter', $social_twitter);
$tab_social_connecting->add_field('ef_facebook', $social_facebook);
$tab_social_connecting->add_field('ef_instagram', $social_instagram);
$tab_social_connecting->add_field('ef_youtube', $social_youtube);
$tab_social_connecting->add_field('ef_pinterest', $social_pinterest);
$tab_social_connecting->add_field('ef_google_plus', $social_google_plus);
$tab_social_connecting->add_field('ef_email', $social_email);
$tab_social_connecting->add_field('ef_vimeo', $social_vimeo);
$tab_social_connecting->add_field('ef_flickr', $social_flickr);
$tab_social_connecting->add_field('ef_skype', $social_skype);
$tab_social_connecting->add_field('ef_rss', $social_rss);
$tab_social_connecting->add_field('ef_add_this_pubid', $social_add_this);

/*
 * APIs Fields
 */

//for twitter
$twitter_title = new EF_Separator_Field(__('Twitter', 'khore'), '', '');
$twitter_access_token = new ef_Text_Field('efcb_twitter_access_token', __('Twitter Access Token', 'khore'));
$twitter_access_token_secret = new ef_Text_Field('efcb_twitter_access_token_secret', __('Twitter Access Token Secret', 'khore'));
$twitter_consumer_key = new ef_Text_Field('efcb_twitter_consumer_key', __('Twitter Consumer Key', 'khore'));
$twitter_consumer_secret = new ef_Text_Field('efcb_twitter_consumer_secret', __('Twitter Consumer Secret', 'khore'));

//for instagram
$instagram_title = new EF_Separator_Field(__('Instagram', 'khore'), '', '');
$instagram_client_id = new ef_Text_Field('efcb_instagram_client_id', __('Instagram Client ID', 'khore'));
$instagram_client_secret = new ef_Text_Field('efcb_instagram_client_secret', __('Instagram Client Secret', 'khore'));

//for facebook
$facebook_title = new EF_Separator_Field(__('Facebook', 'khore'), '', '');
$facebook_rsvp_app_id = new EF_Text_Field('efcb_facebook_rsvp_app_id', __('Facebook App ID', 'khore'));
$facebook_rsvp_secret = new EF_Text_Field('efcb_facebook_rsvp_secret', __('Facebook Secret Key', 'khore'));

// Add fields to APIs
$tab_api->add_field('efcb_twitter_title', $twitter_title);
$tab_api->add_field('efcb_twitter_access_token', $twitter_access_token);
$tab_api->add_field('efcb_twitter_access_token_secret', $twitter_access_token_secret);
$tab_api->add_field('efcb_twitter_consumer_key', $twitter_consumer_key);
$tab_api->add_field('efcb_twitter_consumer_secret', $twitter_consumer_secret);

$tab_api->add_field('efcb_instagram_title', $instagram_title);
$tab_api->add_field('efcb_instagram_client_id', $instagram_client_id);
$tab_api->add_field('efcb_instagram_client_secret', $instagram_client_secret);

$tab_api->add_field('efcb_facebook_title', $facebook_title);
$tab_api->add_field('efcb_facebook_rsvp_app_id', $facebook_rsvp_app_id);
$tab_api->add_field('efcb_facebook_rsvp_secret', $facebook_rsvp_secret);

/*
 * Contact
 */
$contacts_title = new EF_Separator_Field(__('Contacts', 'khore'), '', '');
$contacts_recaptcha_public_key = new EF_Text_Field('efcb_contacts_recaptcha_public_key', __('Recaptcha Public Key', 'khore'));
$contacts_recaptcha_private_key = new EF_Text_Field('efcb_contacts_recaptcha_private_key', __('Recaptcha Private Key', 'khore'));
$contacts_email = new EF_Text_Field('efcb_contacts_email', __('Recipient Email', 'khore'));

$tab_contacts->add_field('efcb_contacts_recaptcha_public_key', $contacts_recaptcha_public_key);
$tab_contacts->add_field('efcb_contacts_recaptcha_private_key', $contacts_recaptcha_private_key);
$tab_contacts->add_field('efcb_contacts_email', $contacts_email);

/*
 * Add All Main Tabs
 */
$theme_options->add_tab(__('General Site Settings', 'khore'), $tab_general_site_options);
$theme_options->add_tab(__('Menu Settings', 'khore'), $tab_menu_options);
$theme_options->add_tab(__('Homepage Settings', 'khore'), $tab_homepage_options);
$theme_options->add_tab(__('Social Networks', 'khore'), $tab_social_connecting);
$theme_options->add_tab(__('Social API', 'khore'), $tab_api);
$theme_options->add_tab(__('Contact', 'khore'), $tab_contacts);
$theme_options->add_tab(__('Tools', 'khore'), $tab_misc);
