<?php
/**
 * Multievent meta box
 *
 * Handles the html for Multievent meta box
 *
 * @package Event Framework
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

global $post;

$prefix				= MUL_META_PREFIX;
$multievent_Data	= dx_multievent_get_data();

$_assigned			= get_post_meta( $post->ID, $prefix . 'assigned', true );

?>

<table class="form-table multievent-field-table">
	<tr>
		<th>
			<label for="multievent-select"><strong><?php echo __( 'Event', 'dxef' ); ?></strong></label>
		</th>
		<td>
			<select data-placeholder="<?php echo __( 'Choose a Event...', 'dxef' ); ?>" class="chzn-select multievent-assigned" multiple name="<?php echo $prefix;?>assigned[]">
				
				<?php 
					if( !empty( $multievent_Data ) ) {
						
						foreach ( $multievent_Data as $multievent ) {
							
							$selected = '';
							if( in_array( $multievent['ID'], $_assigned ) ) {
								$selected = ' selected="selected"';
							}
							?>
							
							<option value="<?php echo $multievent['ID'];?>" <?php echo $selected;?>><?php echo $multievent['post_title'];?></option><?php 
						}
					}
				?>
			</select>
		</td>
	</tr>
</table>