<?php

register_post_type('ticket', array(
    'labels' => array(
        'name' => __('Tickets', 'dxef'),
        'singular_name' => __('Ticket', 'dxef'),
        'add_new' => __('Add New', 'dxef'),
        'add_new_item' => __('Add New Ticket', 'dxef'),
        'edit_item' => __('Edit Ticket', 'dxef'),
        'new_item' => __('New Ticket', 'dxef'),
        'view_item' => __('View Ticket', 'dxef'),
        'search_items' => __('Search Tickets', 'dxef'),
        'not_found' => __('No Tickets found', 'dxef'),
        'not_found_in_trash' => __('No Tickets found in trash', 'dxef'),
        'menu_name' => __('Tickets', 'dxef')
    ),
    'public' => true,
    'show_ui' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'rewrite' => true,
    'query_var' => false,
    'supports' => array(
        'title',
        'editor',
        'excerpt',
        'author',
        'page-attributes'
    )
));

/**
 * Message Filter
 *
 * Add filter to ensure the text Review, or review, 
 * is displayed when a user updates a custom post type.
 */
function khore_ticket_updated_messages($messages) {

    global $post, $post_ID;

    $messages['ticket'] = array(
        0 => '', // Unused. Messages start at index 1.
        1 => sprintf(__('Ticket updated. <a href="%s">View Ticket</a>', 'dxef'), esc_url(get_permalink($post_ID))),
        2 => __('Custom field updated.', 'dxef'),
        3 => __('Custom field deleted.', 'dxef'),
        4 => __('Ticket updated.', 'dxef'),
        /* translators: %s: date and time of the revision */
        5 => isset($_GET['revision']) ? sprintf(__('Ticket restored to revision from %s', 'dxef'), wp_post_revision_title((int) $_GET['revision'], false)) : false,
        6 => sprintf(__('Ticket published. <a href="%s">View Ticket</a>', 'dxef'), esc_url(get_permalink($post_ID))),
        7 => __('Ticket saved.', 'dxef'),
        8 => sprintf(__('Ticket submitted. <a target="_blank" href="%s">Preview Ticket</a>', 'dxef'), esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))),
        9 => sprintf(__('Ticket scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Ticket</a>', 'dxef'),
                // translators: Publish box date format, see http://php.net/date
                date_i18n(__('M j, Y @ G:i'), strtotime($post->post_date)), esc_url(get_permalink($post_ID))),
        10 => sprintf(__('Ticket draft updated. <a target="_blank" href="%s">Preview Ticket</a>', 'dxef'), esc_url(add_query_arg('preview', 'true', get_permalink($post_ID)))),
    );

    return $messages;
}

add_filter('post_updated_messages', 'khore_ticket_updated_messages');
