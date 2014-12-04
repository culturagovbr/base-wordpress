<?php
/*
Plugin Name: Anti-spam
Plugin URI: http://wordpress.org/plugins/anti-spam/
Description: No spam in comments. No captcha.
Version: 3.1
Author: webvitaly
Author URI: http://web-profile.com.ua/wordpress/plugins/
License: GPLv3
*/

$antispam_send_spam_comment_to_admin = false; // if true, than rejected spam comments will be sent to admin email

$antispam_allow_trackbacks = false; // if true, than trackbacks will be allowed
// trackbacks almost not used by users, but mostly used by spammers; pingbacks are always enabled
// more about the difference between trackback and pingback - http://web-profile.com.ua/web/trackback-vs-pingback/


$antispam_settings = array(
	'send_spam_comment_to_admin' => $antispam_send_spam_comment_to_admin,
	'allow_trackbacks' => $antispam_allow_trackbacks,
	'version' => '3.1',
	'admin_email' => get_option('admin_email'),
	'max_spam_points' => 3, // if more - it is spam
	'max_links_number' => 2, // if more - +1 spam point
	'max_comment_length' => 2000 // if more - +1 spam point
);


if ( ! function_exists('antispam_enqueue_script')):
	function antispam_enqueue_script() {
		global $antispam_settings;
		if (is_singular() && comments_open()) { // load script only for pages with comments form
			wp_enqueue_script('anti-spam-script', plugins_url('/js/anti-spam-3.0.js', __FILE__), array('jquery'), $antispam_settings['version'], true);
		}
	}
	add_action('wp_enqueue_scripts', 'antispam_enqueue_script');
endif; // end of antispam_enqueue_script()


if ( ! function_exists('antispam_form_part')):
	function antispam_form_part() {
		global $antispam_settings;
		if ( ! is_user_logged_in() ) { // add anti-spam fields only for not logged in users
			echo '<p class="antispam-group antispam-group-q" style="clear: both;">
					<strong>Current ye@r</strong> <span class="required">*</span>
					<input type="hidden" name="antspm-a" class="antispam-control antispam-control-a" value="'.date('Y').'" />
					<input type="text" name="antspm-q" class="antispam-control antispam-control-q" value="'.$antispam_settings['version'].'" />
				</p>'; // question (hidden with js) [required="required"]

			echo '<p class="antispam-group antispam-group-e" style="display: none;">
					<strong>Leave this field empty</strong>
					<input type="text" name="antspm-e-email-url-website" class="antispam-control antispam-control-e" value="" />
				</p>'; // empty field (hidden with css)
		}
	}
	add_action('comment_form', 'antispam_form_part'); // add anti-spam input to the comment form
endif; // end of antispam_form_part()


if ( ! function_exists('antispam_check_comment')):
	function antispam_check_comment($commentdata) {
		global $antispam_settings;
		$rn = "\r\n"; // .chr(13).chr(10)

		extract($commentdata);

		$antispam_pre_error_message = '<p><strong><a href="javascript:window.history.back()">Go back</a></strong> and try again.</p>';
		$antispam_error_message = '';

		if ($antispam_settings['send_spam_comment_to_admin']) { // if sending email to admin is enabled
			$post = get_post($comment->comment_post_ID);
			$antispam_message_spam_info  = 'Spam for post: "'.$post->post_title.'"' . $rn;
			$antispam_message_spam_info .= get_permalink($comment->comment_post_ID) . $rn.$rn;

			$antispam_message_spam_info .= 'IP: ' . $_SERVER['REMOTE_ADDR'] . $rn;
			$antispam_message_spam_info .= 'User agent: ' . $_SERVER['HTTP_USER_AGENT'] . $rn;
			$antispam_message_spam_info .= 'Referer: ' . $_SERVER['HTTP_REFERER'] . $rn.$rn;

			$antispam_message_spam_info .= 'Comment data:'.$rn; // lets see what comment data spammers try to submit
			foreach ($commentdata as $key => $value) {
				$antispam_message_spam_info .= '$commentdata['.$key. '] = '.$value.$rn;
			}
			$antispam_message_spam_info .= $rn.$rn;

			$antispam_message_spam_info .= 'Post vars:'.$rn; // lets see what post vars spammers try to submit
			foreach ($_POST as $key => $value) {
				$antispam_message_spam_info .= '$_POST['.$key. '] = '.$value.$rn;
			}
			$antispam_message_spam_info .= $rn.$rn;

			$antispam_message_spam_info .= 'Cookie vars:'.$rn; // lets see what cookie vars spammers try to submit
			foreach ($_COOKIE as $key => $value) {
				$antispam_message_spam_info .= '$_COOKIE['.$key. '] = '.$value.$rn;
			}
			$antispam_message_spam_info .= $rn.$rn;

			$antispam_message_append = '-----------------------------'.$rn;
			$antispam_message_append .= 'This is spam comment rejected by Anti-spam plugin - wordpress.org/plugins/anti-spam/' . $rn;
			$antispam_message_append .= 'You may edit "anti-spam.php" file and disable this notification.' . $rn;
			$antispam_message_append .= 'You should find "$antispam_send_spam_comment_to_admin" and make it equal to "false".' . $rn;
		}

		if ( ! is_user_logged_in() && $comment_type != 'pingback' && $comment_type != 'trackback') { // logged in user is not a spammer
			$spam_flag = false;

			if (trim($_POST['antspm-q']) != date('Y')) { // year-answer is wrong - it is spam
				$spam_flag = true;
				if (empty($_POST['antspm-q'])) { // empty answer - it is spam
					$antispam_error_message .= 'Error: empty answer. ['.$_POST['antspm-q'].']<br> '.$rn;
				} else {
					$antispam_error_message .= 'Error: answer is wrong. ['.$_POST['antspm-q'].']<br> '.$rn;
				}
			}

			if ( ! empty($_POST['antspm-e-email-url-website'])) { // trap field is not empty - it is spam
				$spam_flag = true;
				$antispam_error_message .= 'Error: field should be empty. ['.$_POST['antspm-e-email-url-website'].']<br> '.$rn;
			}

			// if comment passed general checks lets add extra check
			if (empty($_COOKIE)) { // probably spam
				$spam_points += 1;
				$antispam_error_message .= 'Info: COOKIE array is empty. +1 spam point.<br> '.$rn;
			}

			if ( ! empty($commentdata['comment_author_url'])) { // probably spam
				$spam_points += 1;
				$antispam_error_message .= 'Info: URL field is not empty. +1 spam point.<br> '.$rn;
			}

			$links_count = substr_count($commentdata['comment_content'], 'http');
			if ($links_count > $antispam_settings['max_links_number']) { // probably spam
				$spam_points += 1;
				$antispam_error_message .= 'Info: comment contains too many links ['.$links_count.' links; max = '.$antispam_settings['max_links_number'].']. +1 spam point.<br> '.$rn;
			}

			if (strpos($commentdata['comment_content'], '</') !== false) { // probably spam
				$spam_points += 1;
				$antispam_error_message .= 'Info: comment contains html. +1 spam point.<br> '.$rn;
			}

			$comment_length = strlen($commentdata['comment_content']);
			if ($comment_length > $antispam_settings['max_comment_length']) { // probably spam
				$spam_points += 1;
				$antispam_error_message .= 'Info: comment is too long ['.$comment_length.' chars; max = '.$antispam_settings['max_comment_length'].']. +1 spam point.<br> '.$rn;
			}

			if (strpos($commentdata['comment_content'], 'rel="nofollow"') !== false) { // probably spam
				$spam_points += 1;
				$antispam_error_message .= 'Info: comment contains rel="nofollow" code. +1 spam point.<br> '.$rn;
			}

			if (strpos($commentdata['comment_content'], '[/url]') !== false) { // probably spam
				$spam_points += 1;
				$antispam_error_message .= 'Info: comment contains [/url] code. +1 spam point.<br> '.$rn;
			}

			if ($spam_points > 0) {
				$antispam_error_message .= 'Total spam points = '.$spam_points.' [max = '.$antispam_settings['max_spam_points'].']<br> '.$rn;
			}

			if ($spam_flag || $spam_points > $antispam_settings['max_spam_points']) { // it is spam
				$antispam_error_message .= '<strong>Comment was blocked because it is spam.</strong><br> ';
				if ($antispam_settings['send_spam_comment_to_admin']) { // if sending email to admin is enabled
					$antispam_subject = 'Spam comment on site ['.get_bloginfo('name').']'; // email subject
					$antispam_message = '';
					$antispam_message .= $antispam_error_message . $rn.$rn;
					$antispam_message .= $antispam_message_spam_info; // spam comment, post, cookie and other data
					$antispam_message .= $antispam_message_append;
					@wp_mail($antispam_settings['admin_email'], $antispam_subject, $antispam_message); // send spam comment to admin email
				}
				wp_die( $antispam_pre_error_message . $antispam_error_message ); // die - do not send comment and show errors
			}
		}

		if ( ! $antispam_settings['allow_trackbacks']) { // if trackbacks are blocked (pingbacks are alowed)
			if ($comment_type == 'trackback') { // if trackbacks ( || $comment_type == 'pingback')
				$antispam_error_message .= 'Error: trackbacks are disabled.<br> ';
				if ($antispam_settings['send_spam_comment_to_admin']) { // if sending email to admin is enabled
					$antispam_subject = 'Spam trackback on site ['.get_bloginfo('name').']'; // email subject
					$antispam_message = '';
					$antispam_message .= $antispam_error_message . $rn.$rn;
					$antispam_message .= $antispam_message_spam_info; // spam comment, post, cookie and other data
					$antispam_message .= $antispam_message_append;
					@wp_mail($antispam_settings['admin_email'], $antispam_subject, $antispam_message); // send trackback comment to admin email
				}
				wp_die($antispam_pre_error_message . $antispam_error_message); // die - do not send trackback
			}
		}

		return $commentdata; // if comment does not looks like spam
	}

	if ( ! is_admin()) {
		add_filter('preprocess_comment', 'antispam_check_comment', 1);
	}
endif; // end of antispam_check_comment()


if ( ! function_exists('antispam_plugin_meta')):
	function antispam_plugin_meta($links, $file) { // add some links to plugin meta row
		if (strpos($file, 'anti-spam.php') !== false) {
			$links = array_merge($links, array('<a href="http://web-profile.com.ua/wordpress/plugins/anti-spam/" title="Plugin page">Anti-spam</a>'));
			$links = array_merge($links, array('<a href="http://web-profile.com.ua/donate/" title="Support the development">Donate</a>'));
			$links = array_merge($links, array('<a href="http://codecanyon.net/item/antispam-pro/6491169?ref=webvitaly" title="Upgrade to Pro">Anti-spam Pro</a>'));
		}
		return $links;
	}
	add_filter('plugin_row_meta', 'antispam_plugin_meta', 10, 2);
endif; // end of antispam_plugin_meta()