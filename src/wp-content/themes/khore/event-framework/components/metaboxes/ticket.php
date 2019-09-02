<?php
add_action('add_meta_boxes', 'ef_ticket_metabox');

function ef_ticket_metabox() {
    add_meta_box('metabox-ticket', __('Ticket Details', 'dxef'), 'ef_metabox_ticket', 'ticket', 'normal', 'high');
}

function ef_metabox_ticket($post) {
    $ticket_price = get_post_meta($post->ID, 'ticket_price', true);
    $ticket_button_text = get_post_meta($post->ID, 'ticket_button_text', true);
    $ticket_button_link = get_post_meta($post->ID, 'ticket_button_link', true);
    $ticket_status = get_post_meta($post->ID, 'ticket_status', true);
    ?>
    <p>
        <label for="ticket_price"><?php _e('Price', 'dxef'); ?></label>
        <input type="text" class="widefat" id="ticket_price" name="ticket_price" value="<?php echo $ticket_price; ?>" />
    </p>
    <p>
        <label for="ticket_button_text"><?php _e('Button Text', 'dxef'); ?></label>
        <input type="text" class="widefat" id="ticket_button_text" name="ticket_button_text" value="<?php echo $ticket_button_text; ?>" />
    </p>
    <p>
        <label for="ticket_button_link"><?php _e('Button Link', 'dxef'); ?></label>
        <input type="text" class="widefat" id="ticket_button_link" name="ticket_button_link" value="<?php echo $ticket_button_link; ?>" />
    </p>
    <p>
        <label for="ticket_status"><?php _e('Status', 'dxef'); ?></label>
        <select class="widefat" id="ticket_status" name="ticket_status">
            <option value="onsale"<?php if ($ticket_status == 'onsale') echo ' selected="selected"'; ?>>On Sale</option>
            <option value="featured"<?php if ($ticket_status == 'featured') echo ' selected="selected"'; ?>>Featured</option>
            <option value="soldout"<?php if ($ticket_status == 'soldout') echo ' selected="selected"'; ?>>Sold Out</option>
        </select>
    </p>
    <?php
}

add_action('save_post', 'ef_ticket_save_post');

function ef_ticket_save_post($id) {
    if (isset($_POST['post_type']) && $_POST['post_type'] === 'ticket') {
        if (isset($_POST['ticket_price']))
            update_post_meta($id, 'ticket_price', $_POST['ticket_price']);
        if (isset($_POST['ticket_button_text']))
            update_post_meta($id, 'ticket_button_text', $_POST['ticket_button_text']);
        if (isset($_POST['ticket_button_link']))
            update_post_meta($id, 'ticket_button_link', $_POST['ticket_button_link']);
        if (isset($_POST['ticket_status']))
            update_post_meta($id, 'ticket_status', $_POST['ticket_status']);
    }
}
