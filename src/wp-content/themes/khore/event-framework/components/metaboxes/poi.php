<?php


add_action( 'add_meta_boxes', 'ef_poi_metabox' );

function ef_poi_metabox() {
	add_meta_box('metabox-poi', __('POI Address Info', 'dxef'), 
		'ef_metabox_poi', 'poi', 'normal', 'high');
}

function ef_metabox_poi($post) {
	$poi_address = get_post_meta($post->ID, 'poi_address', true);
	$poi_manual_coordinates = get_post_meta($post->ID, 'poi_manual_coordinates', true);
	$poi_latitude = get_post_meta($post->ID, 'poi_latitude', true);
	$poi_longitude = get_post_meta($post->ID, 'poi_longitude', true);
	?>
    <p>
        <label for="poi_address"><?php _e('Address', 'dxef'); ?></label>
        <input type="text" class="widefat" id="poi_address" name="poi_address" value="<?php echo $poi_address; ?>" />
    </p>
    <p>
        <label for="poi_manual_coordinates"><?php _e('Manual coordinates', 'dxef'); ?></label>
        <input type="checkbox" id="poi_manual_coordinates" name="poi_manual_coordinates" value="1" <?php if ($poi_manual_coordinates == 1) echo 'checked="checked"'; ?> />
    </p>
    <p>
        <label for="poi_latitude"><?php _e('Latitude', 'dxef'); ?></label>
        <input type="text" class="widefat" id="poi_latitude" name="poi_latitude" value="<?php echo $poi_latitude; ?>" />
    </p>
    <p>
        <label for="poi_longitude"><?php _e('Longitude', 'dxef'); ?></label>
        <input type="text" class="widefat" id="poi_longitude" name="poi_longitude" value="<?php echo $poi_longitude; ?>" />
    </p>
    <?php
}

add_action( 'save_post', 'ef_poi_save_post' );

function ef_poi_save_post( $id ) {
	if( isset( $_POST['post_type'] ) && $_POST['post_type'] === 'poi' ) {
		if (isset($_POST['poi_address']))
			update_post_meta($id, 'poi_address', $_POST['poi_address']);
		
		if (isset($_POST['poi_manual_coordinates']))
			update_post_meta($id, 'poi_manual_coordinates', $_POST['poi_manual_coordinates']);
		else
			delete_post_meta($id, 'poi_manual_coordinates');
		
		if (isset($_POST['poi_manual_coordinates'])) {
			if (isset($_POST['poi_latitude']))
				update_post_meta($id, 'poi_latitude', $_POST['poi_latitude']);
		
			if (isset($_POST['poi_longitude']))
				update_post_meta($id, 'poi_longitude', $_POST['poi_longitude']);
		} else if (isset($_POST['poi_address'])) {
			$location = Geocoder::getLocation($_POST['poi_address']);
			if ($location !== false) {
				update_post_meta($id, 'poi_latitude', $location['lat']);
				update_post_meta($id, 'poi_longitude', $location['lng']);
			} else {
				delete_post_meta($id, 'poi_latitude');
				delete_post_meta($id, 'poi_longitude');
			}
		}

	}
}