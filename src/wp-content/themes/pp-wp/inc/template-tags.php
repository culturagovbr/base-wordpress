<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Portal_Padrão_WP
 */

if (!function_exists ('pp_wp_posted_on')) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
	 */
	function pp_wp_posted_on () {
		$updated = false;
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		$time_string_updated = false;
		if (get_the_time ('U') !== get_the_modified_time ('U')) {
			$updated = true;
			$time_string_updated = '<time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf ($time_string, esc_attr (get_the_date ('c')), esc_html (get_the_date ()), esc_attr (get_the_modified_date ('c')), esc_html (get_the_modified_date ()));
		if( $time_string_updated ){
			$time_string_updated = sprintf ($time_string_updated, esc_attr (get_the_date ('c')), esc_html (get_the_date ()), esc_attr (get_the_modified_date ('c')), esc_html (get_the_modified_date ()));
		}

		$posted_on = sprintf (esc_html_x ('Published: %s', 'post date', 'pp-wp'), $time_string);
		$updated_on = sprintf (esc_html_x ('Last modification: %s', 'post date', 'pp-wp'), $time_string_updated);

		$byline = sprintf (esc_html_x ('By: %s', 'post author', 'pp-wp'), '<span class="author vcard"><a class="url fn n" href="' . esc_url (get_author_posts_url (get_the_author_meta ('ID'))) . '">' . esc_html (get_the_author ()) . '</a></span>');

		echo '<span class="byline"> ' . $byline . '</span>';
		echo '<span class="posted-on">' . $posted_on . '</span>';
		if( $updated ){
			echo '<span class="updated-on">' . $updated_on . '</span>';
		}

	}
endif;

if (!function_exists ('pp_wp_entry_footer')) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function pp_wp_entry_footer () {
		// Hide category and tag text for pages.
		if ('post' === get_post_type ()) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list (esc_html__ (', ', 'pp-wp'));
			if ($categories_list) {
				/* translators: 1: list of categories. */
				printf ('<span class="cat-links">' . esc_html__ ('Posted in %1$s', 'pp-wp') . '</span>', $categories_list); // WPCS: XSS OK.
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list ('', esc_html_x (', ', 'list item separator', 'pp-wp'));
			if ($tags_list) {
				/* translators: 1: list of tags. */
				printf ('<span class="tags-links">' . esc_html__ ('Tagged %1$s', 'pp-wp') . '</span>', $tags_list); // WPCS: XSS OK.
			}
		}

		if (!is_single () && !post_password_required () && (comments_open () || get_comments_number ())) {
			echo '<span class="comments-link">';
			comments_popup_link (sprintf (wp_kses (/* translators: %s: post title */
				__ ('Leave a Comment<span class="screen-reader-text"> on %s</span>', 'pp-wp'), array('span' => array('class' => array(),),)), get_the_title ()));
			echo '</span>';
		}
	}
endif;
