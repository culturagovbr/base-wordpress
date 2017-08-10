=== Subscribe to Category ===
Contributors: dansod
Tags: subscribe to post, subscribe to category, subscribe to news, subscribe
Requires at least: 3.9
Tested up to: 4.5.3
Stable tag: 1.9.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Subscribe to posts within a certain category or categories.

== Description ==
This plugin lets a user subscribe and unsubscribe to posts within a certain category or categories. 
Subscribers will recieve an e-mail with a link to the actual post. E-mails to subscribers are sent once every hour with WP Cron.

The HTML form is prepared for Bootstrap framework.

Subscribers are saved as a custom post type with a possibillity to export(excel). Unsubscription needs to be confirmed by the subscriber.

The following settings and features are available for the administrator in current version:

*   Change e-mail sender from default
*   Change the title/subject for e-mails
*   Turn CSS on/off
*   Export subscribers to Excel with a possibillity to filter by categories
*   Run the cron job manually so it will fire immediately
*   Theres a note when next scheduled event for sending e-mails to subscribers is running.
*   Options for leave no trace - deletes post meta and subscribers created by this plugin.
*   Option for re-send a post on update that has already been sent.
*   Implementation by widget or short code.


= What Translations are included? =
* Dutch
* English
* French
* German
* Italian
* Japanese
* Lithuanian
* Norwegian
* Russian
* Spanish
* Swedish

____
Have you translated this plugin to another language? Please send me your files to info@dcweb.nu and I will add them to the plugin.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `subscribe-to-category` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress Admin
3. Save your settings 'Settings > Subscribe'.
4. Create a page and add shortcode [stc-subscribe] or use the STC Widget to display stc form subscription.

= Shortcode Attributes =
'category_in' - Use this attribute if you only want one or several categories to be available for subscription. Value to be entered is the name of the category.
'category_id_in' - The difference to above is to use the category ID instead of category name.

'category_not_in' - Use this attribute if you want to exclude categories to be available for subscription. Value to be entered is the name of the category.
'category_id_not_in' - The difference to above is to use the category ID instead of category name.

For the above attributes you can use a comma sign to separate multiple categories, like [stc-subscribe category_in="news, article"].

= Filter and hooks =
Following filters and hooks can be used for customizing the email message.

`<?php
// FILTERS
// Parameters: $value
add_filter( 'stc_message_length_sum_of_words', 'stc_message_length_sum_of_words', 10, 1 ); //set return value to a negative number to show the full content


// Parameters: $value, $post_id, $subscriber_post_id
add_filter( 'stc_message_title_html', 'my_stc_message_title_html', 10, 3 );
add_filter( 'stc_message_link_to_post_html', 'my_stc_message_link_to_post_html', 10, 3 );
add_filter( 'stc_message_unsubscribe_html', 'my_stc_message_unsubscribe_html', 10, 3 );

// HOOKS
// Parameters: $post_id, $subscriber_post_id  
add_action( 'stc_before_message', 'my_stc_before_message', 10, 2 );
add_action( 'stc_before_message_title', 'my_stc_before_message_title', 10, 2 );
add_action( 'stc_after_message_title', 'my_stc_after_message_title', 10, 2 );
add_action( 'stc_before_message_content', 'my_stc_before_message_content', 10, 2 );
add_action( 'stc_after_message_content', 'my_stc_after_message_content', 10, 2 );
add_action( 'stc_after_message', 'my_stc_after_message', 10, 2 );

// Parameters: $subscriber_post_id, $categories, bool $all_categories
add_action( 'stc_after_update_subscriber', 'my_stc_after_update_subscriber', 10, 3 );
add_action( 'stc_after_insert_subscriber', 'my_stc_after_insert_subscriber', 10, 3 );

// Parameters: $subscriber_post_id, 
add_action( 'stc_before_unsubscribe', 'my_stc_before_unsubscribe', 10, 1 ); // runs before deleting a subscriber from database


/**
 * Example for adding featured image to STC email
 */
function my_stc_after_message_title( $post_id ){
	echo get_the_post_thumbnail( $post_id, 'thumbnail' );
}
add_action( 'stc_after_message_title', 'my_stc_after_message_title', 10, 2 );

?>`


= Optionally but recommended =
As Wordpress Cron is depending on that you have visits on your website you should set up a cron job on your server to hit http://yourdomain.com/wp-cron.php at a regular interval to make sure that WP Cron is running as expected. In current version of Subscribe to Category the WP Cron is running once every hour, that might be an option that is changeable in future versions. 
Therefore a suggested interval for your server cron could be once every 5 minutes. 

== Screenshots ==

1. Settings page.
2. With Bootstrap framework.
3. Without Bootstrap framework, override and add your own css.
4. When resend post is enabled in settings there is a new option available when editing a post.

== Changelog ==
= 1.9.0 =
* Email address preset for logged in users.
* Japanese, Dutch, German and Norwegian language added.

= 1.8.1 =
* Added pot file to be used for translation.
* Bugfix - changed textdomain to string instead of constant.

= 1.7.0 =
* Added some new hooks: stc_after_update_subscriber, stc_after_insert_subscriber, stc_before_unsubscribe.

= 1.6.0 =
* Added a Widget for subscription form.
* Don't show category list if only one is available (thanks to davefx).
* Extended short code attributes with an option to use category id instead of category name (thanks to Stingray_454).

= 1.3 =
* Added hooks and filters to make the plugin extensible.
* Added Lithuanian language.

= 1.2.1 =
* Fixed some undefined variables that might have caused some errors for some environments.
* Renamed language files for russian language to correct syntax.
* Added Italian language.

= 1.2.0 =
* Possibillity to re-send a post on update that has already been sent. This option needs to be activated in the settings for the plugin.
* Attribute 'category_in' added to shortcode to show only entered categories in the subscribe form. Multiple categories are separated by a comma sign.
* Attribute 'category_not_in' added to shortcode to exclude categories in the subscribe form. Multiple categories are separated by a comma sign. 


= 1.1.0 =
* Added php sleep() function to prevent sending all e-mails in the same scope. 
* Using Ajax when send is manually triggered in back-end.

= 1.0.0 =
* First release