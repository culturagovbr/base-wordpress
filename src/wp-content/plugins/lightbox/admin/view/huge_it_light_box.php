<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$hugeit_lightbox_values      = $this->model->lightbox_get_option();
$hugeit_resp_lightbox_values = $this->model->lightbox_get_resp_option();
$hugeit_default_lightbox_values      = $this->model->default_options();
$hugeit_resp_default_lightbox_values = $this->model->default_resp_options();
require_once 'free_banner.php';
?>
<div id="post-body-heading" class="post-body-line">
	<h3>General Options</h3>
	<a onclick="document.getElementById('adminForm').submit()" class="save-lightbox-options button-primary"><?php _e('Save','lightbox');?></a>
</div>
<form
	action="<?php echo wp_nonce_url( 'admin.php?page=huge_it_light_box&hugeit_task=save', 'save_settings', 'hugeit_lightbox_save_settings_nonce' ) ?>"
	method="post" id="adminForm" name="adminForm">
	<ul id="lightbox_type">
		<li class="<?php if ( esc_html($hugeit_lightbox_values['hugeit_lightbox_type']) == 'new_type' ) {
			echo "active";
		} ?>">
			<label for="new_type"><?php _e('New Type','lightbox');?></label>
			<input type="checkbox" name="params[hugeit_lightbox_type]"
			       id="new_type" <?php if ( esc_html($hugeit_lightbox_values['hugeit_lightbox_type']) == 'new_type' ) {
				echo 'checked';
			} ?>
			       value="new_type">
		</li>
		<li class="<?php if ( esc_html($hugeit_lightbox_values['hugeit_lightbox_type']) == 'old_type' ) {
			echo "active";
		} ?>">
			<label for="old_type"><?php _e('Old Type','lightbox');?></label>
			<input type="checkbox" name="params[hugeit_lightbox_type]"
			       id="old_type" <?php if ( esc_html($hugeit_lightbox_values['hugeit_lightbox_type']) == 'old_type' ) {
				echo 'checked';
			} ?>
			       value="old_type">
		</li>
	</ul>
	<div id="new-lightbox-options-list"
	     class="unique-type-options-wrapper <?php if ( esc_html($hugeit_lightbox_values['hugeit_lightbox_type']) == 'new_type' ) {
		     echo "active";
	     } ?>">
		<div class="options-block">
			<h3><?php _e('General Options','lightbox');?></h3>
			<div>
				<label for="hugeit_lightbox_lightboxView"><?php _e('Lightbox style','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Choose the style of your popup','lightbox');?></p>
						</div>
					</div>
				</label>
				<select id="hugeit_lightbox_lightboxView" name="params[hugeit_lightbox_lightboxView]">
					<option <?php selected( 'view1', esc_html($hugeit_resp_lightbox_values['hugeit_lightbox_lightboxView']) ); ?>
						value="view1">1
					</option>
					<option <?php selected( 'view2', esc_html($hugeit_resp_lightbox_values['hugeit_lightbox_lightboxView']) ); ?>
						value="view2">2
					</option>
					<option <?php selected( 'view3', esc_html($hugeit_resp_lightbox_values['hugeit_lightbox_lightboxView']) ); ?>
						value="view3">3
					</option>
					<option <?php selected( 'view4', esc_html($hugeit_resp_lightbox_values['hugeit_lightbox_lightboxView']) ); ?>
						value="view4">4
					</option>
					<option <?php selected( 'view5', esc_html($hugeit_resp_lightbox_values['hugeit_lightbox_lightboxView']) ); ?>
						value="view5">5
					</option>
					<option <?php selected( 'view6', esc_html($hugeit_resp_lightbox_values['hugeit_lightbox_lightboxView']) ); ?>
						value="view6">6
					</option>
					<option <?php selected( 'view7', esc_html($hugeit_resp_lightbox_values['hugeit_lightbox_lightboxView']) ); ?>
						value="view7">7
					</option>
				</select>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_speed_new"><?php _e('Lightbox open speed','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set lightbox opening speed','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="number" name="params[hugeit_lightbox_speed_new]" id="hugeit_lightbox_speed_new"
				       value="<?php echo esc_html($hugeit_resp_lightbox_values['hugeit_lightbox_speed_new']); ?>"
				       class="text">
				<span>ms</span>
			</div>
			<div>
				<label for="hugeit_lightbox_overlayClose_new"><?php _e('Overlay close','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Check to enable close by Esc key.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false" name="params[hugeit_lightbox_overlayClose_new]"/>
				<input type="checkbox"
				       id="hugeit_lightbox_overlayClose_new" <?php if ( esc_html($hugeit_resp_lightbox_values['hugeit_lightbox_overlayClose_new']) == 'true' ) {
					echo 'checked="checked"';
				} ?> name="params[hugeit_lightbox_overlayClose_new]" value="true"/>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_rightclick_protection"><?php _e('Right Click protection','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Check to enable right click protection.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false" name="params[hugeit_lightbox_rightclick_protection]"/>
				<input type="checkbox"
					   id="hugeit_lightbox_rightclick_protection" <?php if (esc_html($hugeit_resp_lightbox_values['hugeit_lightbox_rightclick_protection']) == 'true') {
					echo 'checked="checked"';
				} ?> name="params[hugeit_lightbox_rightclick_protection]" value="true"/>
			</div>
			<div>
				<label for="hugeit_lightbox_style"><?php _e('Loop content','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Check to enable repeating images after one cycle.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false" name="params[hugeit_lightbox_loop_new]"/>
				<input type="checkbox"
				       id="hugeit_lightbox_loop_new" <?php if ( esc_html($hugeit_resp_lightbox_values['hugeit_lightbox_loop_new']) == 'true' ) {
					echo 'checked="checked"';
				} ?> name="params[hugeit_lightbox_loop_new]" value="true"/>
			</div>
			<div <?php if(esc_html($hugeit_resp_lightbox_values['hugeit_lightbox_lightboxView']) == 'view7'){echo 'style="display:none;"';} ?>>
				<label for="hugeit_lightbox_fullwidth_effect"><?php _e('Full Width Effect','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Check to activate full width effect.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false" name="params[hugeit_lightbox_fullwidth_effect]"/>
				<input type="checkbox"
					   id="hugeit_lightbox_fullwidth_effect" <?php if (esc_html($hugeit_resp_lightbox_values['hugeit_lightbox_fullwidth_effect']) == 'true') {
					echo 'checked="checked"';
				} ?> name="params[hugeit_lightbox_fullwidth_effect]" value="true"/>
			</div>
			<div <?php if(esc_html($hugeit_resp_lightbox_values['hugeit_lightbox_lightboxView']) != 'view7'){echo 'style="display:none;"';} ?>>
				<label for="hugeit_lightbox_view_info"><?php _e('Show info','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Check to activate show info button.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false" name="params[hugeit_lightbox_view_info]"/>
				<input type="checkbox"
					   id="hugeit_lightbox_view_info" <?php if (esc_html($hugeit_resp_lightbox_values['hugeit_lightbox_view_info']) == 'true') {
					echo 'checked="checked"';
				} ?> name="params[hugeit_lightbox_view_info]" value="true"/>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_thumbs"><?php _e('Thumbnail','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Check to activate thumbnails','lightbox');?>.</p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false" name="params[hugeit_lightbox_thumbs]"/>
				<input type="checkbox"
					   id="hugeit_lightbox_thumbs" <?php if (esc_html($hugeit_resp_lightbox_values['hugeit_lightbox_thumbs']) == 'true') {
					echo 'checked="checked"';
				} ?> name="params[hugeit_lightbox_thumbs]" value="true"/>
			</div>
			<div>
				<label for="hugeit_lightbox_showTitle"><?php _e('Show Title','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Check to display title.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false" name="params[hugeit_lightbox_showTitle]"/>
				<input type="checkbox"
					   id="hugeit_lightbox_showTitle" <?php if (esc_html($hugeit_resp_lightbox_values['hugeit_lightbox_showTitle']) == 'true') {
					echo 'checked="checked"';
				} ?> name="params[hugeit_lightbox_showTitle]" value="true"/>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_showDesc"><?php _e('Show Description','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Check to display description.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false" name="params[hugeit_lightbox_showDesc]"/>
				<input type="checkbox"
					   id="hugeit_lightbox_showDesc" <?php if (esc_html($hugeit_resp_lightbox_values['hugeit_lightbox_showDesc']) == 'true') {
					echo 'checked="checked"';
				} ?> name="params[hugeit_lightbox_showDesc]" value="true"/>
			</div>
			<div>
				<label for="hugeit_lightbox_showBorder"><?php _e('Show Border','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Check to display border.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false" name="params[hugeit_lightbox_showBorder]"/>
				<input type="checkbox"
					   id="hugeit_lightbox_showBorder" <?php if (esc_html($hugeit_resp_lightbox_values['hugeit_lightbox_showBorder']) == 'true') {
					echo 'checked="checked"';
				} ?> name="params[hugeit_lightbox_showBorder]" value="true"/>
			</div>
			<div class="has-background image_frame" <?php if(esc_html($hugeit_resp_lightbox_values['hugeit_lightbox_lightboxView']) == 'view7'){echo 'style="display:none;"';} ?>>
				<label for="hugeit_lightbox_imageframe"><?php _e('Image frame','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Choose the changing frame of the images.','lightbox');?></p>
						</div>
					</div>
				</label>
				<select id="hugeit_lightbox_imageframe" name="params[hugeit_lightbox_imageframe]">
					<option <?php selected('frame_0',(string)$hugeit_resp_lightbox_values['hugeit_lightbox_imageframe']); ?> value="frame_0">None</option>
					<option <?php selected('frame_1',(string)$hugeit_resp_lightbox_values['hugeit_lightbox_imageframe']); ?> value="frame_1">Frame 1</option>
					<option <?php selected('frame_2',(string)$hugeit_resp_lightbox_values['hugeit_lightbox_imageframe']); ?> value="frame_2">Frame 2</option>
					<option <?php selected('frame_8',(string)$hugeit_resp_lightbox_values['hugeit_lightbox_imageframe']); ?> value="frame_8">Frame 3</option>
				</select>
				<div id="view-image_frame">
					<span class="view-style-eye"><?php _e( 'Preview', 'hugeit_lightbox' ); ?></span>
					<ul>
						<li data-id="frame_0" <?php if((string)$hugeit_resp_lightbox_values['hugeit_lightbox_imageframe'] === 'frame_0') echo "class='active'"; ?>><img src="<?php echo plugins_url('../../images/image_frames/none.png', __FILE__); ?>"></li>
						<li data-id="frame_1" <?php if((string)$hugeit_resp_lightbox_values['hugeit_lightbox_imageframe'] === 'frame_1') echo "class='active'"; ?>><img src="<?php echo plugins_url('../../images/image_frames/frame_1.png', __FILE__); ?>"></li>
						<li data-id="frame_2" <?php if((string)$hugeit_resp_lightbox_values['hugeit_lightbox_imageframe'] === 'frame_2') echo "class='active'"; ?>><img src="<?php echo plugins_url('../../images/image_frames/frame_2.png', __FILE__); ?>"></li>
						<li data-id="frame_8" <?php if((string)$hugeit_resp_lightbox_values['hugeit_lightbox_imageframe'] === 'frame_8') echo "class='active'"; ?>><img src="<?php echo plugins_url('../../images/image_frames/frame_8.png', __FILE__); ?>"></li>
					</ul>
				</div>
			</div>
			<div>
				<label for="hugeit_lightbox_fullscreen_effect"><?php _e('Full Screen Effect','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Check to activate full screen effect.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false" name="params[hugeit_lightbox_fullscreen_effect]"/>
				<input type="checkbox"
					   id="hugeit_lightbox_fullscreen_effect" <?php if (esc_html($hugeit_resp_lightbox_values['hugeit_lightbox_fullscreen_effect']) == 'true') {
					echo 'checked="checked"';
				} ?> name="params[hugeit_lightbox_fullscreen_effect]" value="true"/>
			</div>
			<div class="has-background" <?php if(esc_html($hugeit_resp_lightbox_values['hugeit_lightbox_lightboxView']) == 'view7'){echo 'style="display:none;"';} ?>>
				<label for="lightbox_open_close_effect"><?php _e('Lightbox open/close effect','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Lightbox open/close effect.','lightbox');?></p>
						</div>
					</div>
				</label>
				<select id="lightbox_open_close_effect" name="params[lightbox_open_close_effect]">
					<option <?php if (esc_html($hugeit_resp_lightbox_values['lightbox_open_close_effect']) == '0') {echo 'selected="selected"';} ?> value="0"><?php _e('None','lightbox');?></option>
					<option <?php if (esc_html($hugeit_resp_lightbox_values['lightbox_open_close_effect']) == '1') {echo 'selected="selected"';} ?> value="1"><?php _e('UnFold','lightbox');?></option>
					<option <?php if (esc_html($hugeit_resp_lightbox_values['lightbox_open_close_effect']) == '2') {echo 'selected="selected"';} ?> value="2"><?php _e('UnFold R','lightbox');?></option>
					<option <?php if (esc_html($hugeit_resp_lightbox_values['lightbox_open_close_effect']) == '3') {echo 'selected="selected"';} ?> value="3"><?php _e('BlowUp','lightbox');?></option>
					<option <?php if (esc_html($hugeit_resp_lightbox_values['lightbox_open_close_effect']) == '4') {echo 'selected="selected"';} ?> value="4"><?php _e('BlowUp R','lightbox');?></option>
					<option <?php if (esc_html($hugeit_resp_lightbox_values['lightbox_open_close_effect']) == '5') {echo 'selected="selected"';} ?> value="5"><?php _e('RoadRunner','lightbox');?></option>
					<option <?php if (esc_html($hugeit_resp_lightbox_values['lightbox_open_close_effect']) == '6') {echo 'selected="selected"';} ?> value="6"><?php _e('RoadRunner R','lightbox');?></option>
					<option <?php if (esc_html($hugeit_resp_lightbox_values['lightbox_open_close_effect']) == '7') {echo 'selected="selected"';} ?> value="7"><?php _e('Runner','lightbox');?></option>
					<option <?php if (esc_html($hugeit_resp_lightbox_values['lightbox_open_close_effect']) == '8') {echo 'selected="selected"';} ?> value="8"><?php _e('Runner R','lightbox');?></option>
					<option <?php if (esc_html($hugeit_resp_lightbox_values['lightbox_open_close_effect']) == '9') {echo 'selected="selected"';} ?> value="9"><?php _e('Rotate','lightbox');?></option>
					<option <?php if (esc_html($hugeit_resp_lightbox_values['lightbox_open_close_effect']) == '10') {echo 'selected="selected"';} ?> value="10"><?php _e('Rotate R','lightbox');?></option>
				</select>
			</div>
		</div>
		<div class="options-block hugeit-lightbox-pro-option">
			<h3>Dimensions<img src="<?php echo plugins_url( '../../images/pro-icon.png', __FILE__ ) ?>"
							   class="hugeit_lightbox_pro_logo"></h3>
			<div class="has-background">
				<label for="hugeit_lightbox_width_new"><?php _e('Lightbox Width','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set the width of the popup in percentages.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="number"
				       value="<?php echo esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_width_new']); ?>"
				       class="text">
				<span>%</span>
			</div>
			<div>
				<label for="hugeit_lightbox_height_new"><?php _e('Lightbox Height','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set the height of the popup in percentages.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="number"
				       value="<?php echo esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_height_new']); ?>"
				       class="text">
				<span>%</span>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_videoMaxWidth"><?php _e('Lightbox Video maximum width','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set the maximum width of the popup in pixels, the height will be fixed automatically.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="number"
				       value="<?php echo esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_videoMaxWidth']); ?>"
				       class="text">
				<span>px</span>
			</div>
		</div>
		<div class="options-block hugeit-lightbox-pro-option">
			<h3>Slideshow<img src="<?php echo plugins_url( '../../images/pro-icon.png', __FILE__ ) ?>"
							  class="hugeit_lightbox_pro_logo"></h3>
			<div class="has-background">
				<label for="hugeit_lightbox_slideshow_new"><?php _e('Slideshow','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set the width of popup','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false" name="params[hugeit_lightbox_slideshow_new]"/>
				<input type="checkbox"
				       id="hugeit_lightbox_slideshow_new" <?php if ( esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_slideshow_new']) == 'true' ) {
					echo 'checked="checked"';
				} ?> name="params[hugeit_lightbox_slideshow_new]" value="true"/>
			</div>
			<div>
				<label for="hugeit_lightbox_slideshow_auto_new"><?php _e('Slideshow auto start','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set the width of popup','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false" name="params[hugeit_lightbox_slideshow_auto_new]"/>
				<input type="checkbox"
				       id="hugeit_lightbox_slideshow_auto_new" <?php if ( esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_slideshow_auto_new']) == 'true' ) {
					echo 'checked="checked"';
				} ?> name="params[hugeit_lightbox_slideshow_auto_new]" value="true"/>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_slideshow_speed_new"><?php _e('lideshow interval','lightbox');?>S
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set the height of popup','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="number" name="params[hugeit_lightbox_slideshow_speed_new]"
				       id="hugeit_lightbox_slideshow_speed_new"
				       value="<?php echo esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_slideshow_speed_new']); ?>"
				       class="text">
				<span>ms</span>
			</div>
		</div>
		<div class="options-block hugeit-lightbox-pro-option">
			<h3><?php _e('Advanced Options','lightbox');?><img src="<?php echo plugins_url( '../../images/pro-icon.png', __FILE__ ) ?>"
									 class="hugeit_lightbox_pro_logo"></h3>
			<div class="has-background">
				<label for="hugeit_lightbox_bordersize"><?php _e('Border Size','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Border size','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="number" id="hugeit_lightbox_bordersize" value="2" class="text">
				<span>px</span>
			</div>
			<div>
				<label for="hugeit_lightbox_bordercolor"><?php _e('Border Color','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Border Color','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="text" class="color" id="hugeit_lightbox_bordercolor"
					   value="#000"
					   size="10"/>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_borderradius"><?php _e('Border radius','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Border radius','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="number"
					   id="hugeit_lightbox_borderradius"
					   value="5"
					   class="text">
				<span>px</span>
			</div>
			<div>
				<label for="hugeit_lightbox_borderopacity"><?php _e('Border Opacity','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Border Opacity','lightbox');?></p>
						</div>
					</div>
				</label>
				<div class="slider-container">
					<input id="hugeit_lightbox_borderopacity" data-slider-highlight="true"
						   data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text" data-slider="true"
						   value="100"/>
						<span>100
							%</span>
				</div>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_style">EscKey close
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Choose the style of your popup</p>
						</div>
					</div>
				</label>
				<input type="checkbox"
				       id="hugeit_lightbox_escKey_new"/>
			</div>
			<div>
				<label for="hugeit_lightbox_keyPress_new"><?php _e('Keyboard navigation','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Choose the style of your popup','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="checkbox"
				       id="hugeit_lightbox_keyPress_new"/>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_arrows"><?php _e('Show Arrows','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Choose the style of your popup','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="checkbox"
				       id="hugeit_lightbox_arrows" checked/>
			</div>
			<div>
				<label for="hugeit_lightbox_mouseWheel"><?php _e('Mouse Wheel Navigaion','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Choose the style of your popup','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="checkbox"
				       id="hugeit_lightbox_mouseWheel" />
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_download"><?php _e('Show Download Button','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Choose the style of your popup','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="checkbox"
				       id="hugeit_lightbox_download" />
			</div>
			<div>
				<label for="hugeit_lightbox_showCounter"><?php _e('Show Counter','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Choose the style of your popup','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="checkbox"
				       id="hugeit_lightbox_showCounter" />
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_sequence_info"><?php _e('Sequence Info text','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Choose the style of your popup','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="text"
				       style="width: 13%"
				       value="<?php echo esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_sequence_info']); ?>"
				       class="text">
				X <input type="text"
				         style="width: 13%"
				         value="<?php echo esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_sequenceInfo']); ?>"
				         class="text">
				XX
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_slideAnimationType"><?php _e('Transition type','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Choose the style of your popup','lightbox');?></p>
						</div>
					</div>
				</label>
				<select id="hugeit_lightbox_slideAnimationType" >
					<option <?php selected( 'effect_1', esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_slideAnimationType']) ); ?>
						value="effect_1">Effect 1
					</option>
					<option <?php selected( 'effect_2', esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_slideAnimationType']) ); ?>
						value="effect_2">Effect 2
					</option>
					<option <?php selected( 'effect_3', esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_slideAnimationType']) ); ?>
						value="effect_3">Effect 3
					</option>
					<option <?php selected( 'effect_4', esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_slideAnimationType']) ); ?>
						value="effect_4">Effect 4
					</option>
					<option <?php selected( 'effect_5', esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_slideAnimationType']) ); ?>
						value="effect_5">Effect 5
					</option>
					<option <?php selected( 'effect_6', esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_slideAnimationType']) ); ?>
						value="effect_6">Effect 6
					</option>
					<option <?php selected( 'effect_7', esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_slideAnimationType']) ); ?>
						value="effect_7">Effect 7
					</option>
					<option <?php selected( 'effect_8', esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_slideAnimationType']) ); ?>
						value="effect_8">Effect 8
					</option>
					<option <?php selected( 'effect_9', esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_slideAnimationType']) ); ?>
						value="effect_9">Effect 9
					</option>
				</select>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_title_pos"><?php _e('Title position','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Title position','lightbox');?></p>
						</div>
					</div>
				</label>
				<select id="hugeit_lightbox_title_pos">
					<option value="left"><?php _e('Left','lightbox');?></option>
					<option value="center"><?php _e('Center','lightbox');?></option>
					<option value="right"><?php _e('Right','lightbox');?></option>
				</select>
			</div>
			<div>
				<label for="hugeit_lightbox_imageborder"><?php _e('Show Image Border','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Show image border.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false"/>
				<input type="checkbox"
					   id="hugeit_lightbox_imageborder" value="true"/>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_imagebordersize"><?php _e('Image Border Size','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set the size of the image border.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="number" id="hugeit_lightbox_imagebordersize"
					   value="2" class="text">
				<span>px</span>
			</div>
			<div>
				<label for="hugeit_lightbox_imagebordercolor"><?php _e('Image Border Color','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set the color of the image border.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="text" class="color" id="hugeit_lightbox_imagebordercolor"
					   value="#C9C9C9"
					   size="10"/>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_imageborderradius"><?php _e('Image Border Radius','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set the radius of the image border in pixels.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="number" id="hugeit_lightbox_imageborderradius"
					   value="5" class="text">
				<span>px</span>
			</div>
			<div>
				<label for="hugeit_lightbox_imageborderopacity"><?php _e('Image Border Opacity','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set the opacity of the image border','lightbox');?></p>
						</div>
					</div>
				</label>
				<div class="slider-container">
					<input id="hugeit_lightbox_imageborderopacity" data-slider-highlight="true"
						   data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text" data-slider="true"
						   value="100"/>
					<span>100%</span>
				</div>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_imageshadow"><?php _e('Show Image Shadow','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Show image shadow.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false" />
				<input type="checkbox"
					   id="hugeit_lightbox_imageshadow" value="true"/>
			</div>
			<div>
				<label for="hugeit_lightbox_imageshadowh"><?php _e('Image Horizontal Shadow','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('The position of the horizontal shadow. Negative values are allowed.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="number" id="hugeit_lightbox_imageshadowh"
					   value="5" class="text">
				<span>px</span>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_imageshadowv"><?php _e('Image Vertical Shadow','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('The position of the vertical shadow. Negative values are allowed.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="number" id="hugeit_lightbox_imageshadowv"
					   value="5" class="text">
				<span>px</span>
			</div>
			<div>
				<label for="hugeit_lightbox_imageshadow_blur"><?php _e('Image Shadow Blur','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('The blur distance.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="number" id="hugeit_lightbox_imageshadow_blur"
					   value="10" class="text">
				<span>px</span>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_imageshadow_spread"><?php _e('Image Shadow Spread','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('The size of shadow. Negative values are allowed.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="number" id="hugeit_lightbox_imageshadow_spread"
					   value="3" class="text">
				<span>px</span>
			</div>
			<div>
				<label for="hugeit_lightbox_imageshadow_color"><?php _e('Image Shadow Color','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('The color of the shadow.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="text" class="color" id="hugeit_lightbox_imageshadow_color"
					   value="#C9C9C9"
					   size="10"/>
			</div>
			<div class="has-background">
				<label for="lightbox_arrow_hover_effect"><?php _e('Arrows hover effect','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Arrows hover effect. It works in view1, view4, view5 and view6.','lightbox');?></p>
						</div>
					</div>
				</label>
				<select id="lightbox_arrow_hover_effect" name="params[hugeit_lightbox_arrows_hover_effect]">
					<option <?php if (esc_html($hugeit_resp_lightbox_values['hugeit_lightbox_arrows_hover_effect']) == '0') {
						echo 'selected="selected"';
					} ?> value="0"><?php _e('None','lightbox');?>
					</option>
					<option <?php if (esc_html($hugeit_resp_lightbox_values['hugeit_lightbox_arrows_hover_effect']) == '1') {
						echo 'selected="selected"';
					} ?> value="1"><?php _e('1','lightbox');?>
					</option>
					<option <?php if (esc_html($hugeit_resp_lightbox_values['hugeit_lightbox_arrows_hover_effect']) == '2') {
						echo 'selected="selected"';
					} ?> value="2"><?php _e('2','lightbox');?>
					</option>
					<option <?php if (esc_html($hugeit_resp_lightbox_values['hugeit_lightbox_arrows_hover_effect']) == '3') {
						echo 'selected="selected"';
					} ?> value="3"><?php _e('3','lightbox');?>
					</option>
					<option <?php if (esc_html($hugeit_resp_lightbox_values['hugeit_lightbox_arrows_hover_effect']) == '4') {
						echo 'selected="selected"';
					} ?> value="4"><?php _e('4','lightbox');?>
					</option>
					<option <?php if (esc_html($hugeit_resp_lightbox_values['hugeit_lightbox_arrows_hover_effect']) == '5') {
						echo 'selected="selected"';
					} ?> value="5"><?php _e('5','lightbox');?>
					</option>
				</select>
			</div>
		</div>
		<div class="options-block hugeit-lightbox-pro-option" style="margin-top: -920px;">
			<h3>Lightbox Watermark styles<img src="<?php echo plugins_url( '../../images/pro-icon.png', __FILE__ ) ?>"
											  class="hugeit_lightbox_pro_logo"></h3>
			<div class="has-background">
				<label for="hugeit_lightbox_watermark"><?php _e('Watermark','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set the width of popup','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="checkbox"
				       id="hugeit_lightbox_watermark"  />
			</div>
			<div>
				<label for="hugeit_lightbox_watermark_text"><?php _e('Watermark Text','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Choose the style of your popup','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="text"  id="hugeit_lightbox_watermark_text"
				       value="<?php echo esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_text']); ?>"
				       class="text">
			</div>
			<div>
				<label for="hugeit_lightbox_watermark_textColor"><?php _e('Watermark Text Color','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Choose the style of your popup','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="text" class="color" id="hugeit_lightbox_watermark_textColor"
				       value="#<?php echo esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_textColor']); ?>"
				       size="10"/>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_watermark_textFontSize"><?php _e('Watermark Text Font Size','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Choose the style of your popup','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="number"
				       id="hugeit_lightbox_watermark_textFontSize"
				       value="<?php echo esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_textFontSize']); ?>"
				       class="text">
				<span>px</span>
			</div>
			<div>
				<label for="hugeit_lightbox_watermark_containerBackground"><?php _e('Watermark Background Color','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Choose the style of your popup','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="text" class="color" id="hugeit_lightbox_watermark_containerBackground"
				       value="#<?php echo esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_containerBackground']); ?>"
				       size="10"/>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_watermark_containerOpacity"><?php _e('Watermark Background Opacity','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Choose the style of your popup','lightbox');?></p>
						</div>
					</div>
				</label>
				<div class="slider-container">
					<input id="hugeit_lightbox_watermark_containerOpacity" data-slider-highlight="true"
					       data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text" data-slider="true"
					       value="<?php echo esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_containerOpacity']); ?>"/>
						<span><?php echo esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_containerOpacity']); ?>
							%</span>
				</div>
			</div>
			<div>
				<label for="hugeit_lightbox_watermark_containerWidth"><?php _e('Watermark Width','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Choose the style of your popup','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="number"
				       id="hugeit_lightbox_watermark_containerWidth"
				       value="<?php echo esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_containerWidth']); ?>"
				       class="text">
				<span>px</span>
			</div>
			<div class="has-background has-height">
				<label for="hugeit_lightbox_watermark_containerWidth"><?php _e('Watermark Position','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Choose the style of your popup','lightbox');?></p>
						</div>
					</div>
				</label>
				<div>
					<table class="bws_position_table">
						<tbody>
						<tr>
							<td><input type="radio" value="1" id="watermark_top-left"
									<?php if ( esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_position_new']) == '1' ) {
									echo 'checked="checked"';
								} ?> /></td>
							<td><input type="radio" value="2" id="watermark_top-center"
							            <?php if ( esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_position_new']) == '2' ) {
									echo 'checked="checked"';
								} ?> /></td>
							<td><input type="radio" value="3" id="watermark_top-right"
									<?php if ( esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_position_new']) == '3' ) {
									echo 'checked="checked"';
								} ?> /></td>
						</tr>
						<tr>
							<td><input type="radio" value="4" id="watermark_middle-left"
									<?php if ( esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_position_new']) == '4' ) {
									echo 'checked="checked"';
								} ?> /></td>
							<td><input type="radio" value="5" id="watermark_middle-center"
									<?php if ( esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_position_new']) == '5' ) {
									echo 'checked="checked"';
								} ?> /></td>
							<td><input type="radio" value="6" id="watermark_middle-right"
									<?php if ( esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_position_new']) == '6' ) {
									echo 'checked="checked"';
								} ?> /></td>
						</tr>
						<tr>
							<td><input type="radio" value="7" id="watermark_bottom-left"
									<?php if ( esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_position_new']) == '7' ) {
									echo 'checked="checked"';
								} ?> /></td>
							<td><input type="radio" value="8" id="watermark_bottom-center"
									<?php if ( esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_position_new']) == '8' ) {
									echo 'checked="checked"';
								} ?> /></td>
							<td><input type="radio" value="9" id="watermark_bottom-right"
									<?php if ( esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_position_new']) == '9' ) {
									echo 'checked="checked"';
								} ?> /></td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div>
				<label for="hugeit_lightbox_watermark_margin"><?php _e('Watermark Margin','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Choose the style of your popup','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="number"
				       id="hugeit_lightbox_watermark_margin"
				       value="<?php echo esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_margin']); ?>"
				       class="text">
				<span>px</span>
			</div>
			<div class="has-background" style="display: none">
				<label for="hugeit_lightbox_watermark_opacity"><?php _e('Watermark Text Opacity','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Choose the style of your popup','lightbox');?></p>
						</div>
					</div>
				</label>
				<div class="slider-container">
					<input id="hugeit_lightbox_watermark_opacity" data-slider-highlight="true"
					       data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text" data-slider="true"
					       value="<?php echo esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_opacity']); ?>"/>
					<span><?php echo esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_opacity']); ?>%</span>
				</div>
			</div>
			<div class="has-background" style="height:auto;">
				<label for="watermark_image_btn"><?php _e('Select Watermark Image','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set the image of Lightbox watermark.','lightbox');?></p>
						</div>
					</div>
				</label>
				<img src="<?php echo esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_img_src_new']); ?>"
				     id="watermark_image_new" style="width:120px;height:auto;">
				<input type="button" class="button wp-media-buttons-icon"
				       style="margin-left: 63%;width: auto;display: inline-block;" id="watermark_image_btn_new"
				       value="Change Image">
				<input type="hidden" id="img_watermark_hidden_new" value="<?php echo esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_img_src_new']); ?>">
			</div>
		</div>
		<div class="options-block hugeit-lightbox-pro-option" style="margin-top: -260px;">
			<h3><?php _e('Zoom Options','lightbox');?><img src="<?php echo plugins_url( '../../images/pro-icon.png', __FILE__ ) ?>"
														   class="hugeit_lightbox_pro_logo"></h3>
			<div class="has-background">
				<label for="hugeit_lightbox_zoom"><?php _e('Zoom Buttons','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Check to activate zoom buttons.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false" />
				<input type="checkbox"
					   id="hugeit_lightbox_zoom" value="true"/>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_zoomtype"><?php _e('Zoom Type','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Zoom Type','lightbox');?></p>
						</div>
					</div>
				</label>
				<select id="hugeit_lightbox_zoomtype">
					<option value="0" selected ><?php _e('Full Lightbox','lightbox');?></option>
				</select>
			</div>
			<div>
				<label for="hugeit_lightbox_zoomsize"><?php _e('Zoom Size','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Zoom Size','lightbox');?></p>
						</div>
					</div>
				</label>
				<div class="slider-container">
					<input id="hugeit_lightbox_zoomsize" data-slider-highlight="true"
						   data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text" data-slider="true"
						   value="100"/>
					<span>100%</span>
				</div>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_zoomlogo"><?php _e('Zoom Logo','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Zoom Logo','lightbox');?></p>
						</div>
					</div>
				</label>
				<select id="hugeit_lightbox_zoomlogo">
					<option value="0" selected ><?php _e('None','lightbox');?></option>
					<option value="1"><?php _e('Magnifying Glass','lightbox');?></option>
					<option value="2"><?php _e('Hand','lightbox');?></option>
				</select>
			</div>
		</div>
		<div class="options-block hugeit-lightbox-pro-option">
			<h3>Social Share Buttons<img src="<?php echo plugins_url( '../../images/pro-icon.png', __FILE__ ) ?>"
										 class="hugeit_lightbox_pro_logo"></h3>
			<div class="has-background">
				<label for="hugeit_lightbox_socialSharing"><?php _e('Social Share Buttons','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set the width of popup','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="checkbox"  id="hugeit_lightbox_socialSharing"  />
			</div>
			<div class="social-buttons-list">
				<label><?php _e('Social Share Buttons List','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Choose the style of your popup','lightbox');?></p>
						</div>
					</div>
				</label>
				<div>
					<table>
						<tr>
							<td>
								<label for="hugeit_lightbox_facebookButton"><?php _e('Facebook','lightbox');?>
									<input type="checkbox"
									       id="hugeit_lightbox_facebookButton" <?php if ( esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_facebookButton']) == 'true' ) {
										echo 'checked="checked"';
									} ?>  value="true"/></label>
							</td>
							<td>
								<label for="hugeit_lightbox_twitterButton"><?php _e('Twitter','lightbox');?>
									<input type="checkbox"
									       id="hugeit_lightbox_twitterButton" <?php if ( esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_twitterButton']) == 'true' ) {
										echo 'checked="checked"';
									} ?>  value="true"/></label>
							</td>
							<td>
								<label for="hugeit_lightbox_googleplusButton"><?php _e('Google Plus','lightbox');?>
									<input type="checkbox"
									       id="hugeit_lightbox_googleplusButton" <?php if ( esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_googleplusButton']) == 'true' ) {
										echo 'checked="checked"';
									} ?>  value="true"/></label>
							</td>
							<td>
								<label for="hugeit_lightbox_pinterestButton"><?php _e('Pinterest','lightbox');?>
									<input type="checkbox"
									       id="hugeit_lightbox_pinterestButton" <?php if ( esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_pinterestButton']) == 'true' ) {
										echo 'checked="checked"';
									} ?>  value="true"/></label>
							</td>
						</tr>
						<tr>
							<td>
								<label for="hugeit_lightbox_linkedinButton"><?php _e('Linkedin','lightbox');?>
									<input type="checkbox"
									       id="hugeit_lightbox_linkedinButton" <?php if ( esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_linkedinButton']) == 'true' ) {
										echo 'checked="checked"';
									} ?>  value="true"/></label>
							</td>
							<td>
								<label for="hugeit_lightbox_tumblrButton"><?php _e('Tumblr','lightbox');?>
									<input type="checkbox"
									       id="hugeit_lightbox_tumblrButton" <?php if ( esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_tumblrButton']) == 'true' ) {
										echo 'checked="checked"';
									} ?>  value="true"/></label>
							</td>
							<td>
								<label for="hugeit_lightbox_redditButton"><?php _e('Reddit','lightbox');?>
									<input type="checkbox"
									       id="hugeit_lightbox_redditButton" <?php if ( esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_redditButton']) == 'true' ) {
										echo 'checked="checked"';
									} ?>  value="true"/></label>
							</td>
							<td>
								<label for="hugeit_lightbox_bufferButton"><?php _e('Buffer','lightbox');?>
									<input type="checkbox"
									       id="hugeit_lightbox_bufferButton" <?php if ( esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_bufferButton']) == 'true' ) {
										echo 'checked="checked"';
									} ?>  value="true"/></label>
							</td>
						</tr>
						<tr>
							<td>
								<label for="hugeit_lightbox_vkButton"><?php _e('VKontakte','lightbox');?>
									<input type="checkbox"
									       id="hugeit_lightbox_vkButton" <?php if ( esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_vkButton']) == 'true' ) {
										echo 'checked="checked"';
									} ?>  value="true"/></label>
							</td>
							<td>
								<label for="hugeit_lightbox_yummlyButton"><?php _e('Yummly','lightbox');?>
									<input type="checkbox"
									       id="hugeit_lightbox_yummlyButton" <?php if ( esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_yummlyButton']) == 'true' ) {
										echo 'checked="checked"';
									} ?>  value="true"/></label>
							</td>
							<td>
								<label for="hugeit_lightbox_diggButton"><?php _e('Digg','lightbox');?>
									<input type="checkbox"
									       id="hugeit_lightbox_diggButton" <?php if ( esc_html($hugeit_resp_default_lightbox_values['hugeit_lightbox_diggButton']) == 'true' ) {
										echo 'checked="checked"';
									} ?>  value="true"/></label>
							</td>
							<td>

							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
		<div class="options-block hugeit-lightbox-pro-option">
			<h3><?php _e('Thumbnails','lightbox');?><img src="<?php echo plugins_url( '../../images/pro-icon.png', __FILE__ ) ?>"
														 class="hugeit_lightbox_pro_logo"></h3>
			<div>
				<label for="hugeit_lightbox_thumbs_width"><?php _e('Thumbnails Width','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set the size of the thumbnails width in pixels.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="number" id="hugeit_lightbox_thumbs_width"
					   value="100" class="text">
				<span>px</span>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_thumbs_height"><?php _e('Thumbnails height','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set the size of the thumbnails height in pixels (Max height - 200px).','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="number" id="hugeit_lightbox_thumbs_height"
					   value="100" max="200" class="text">
				<span>px</span>
			</div>
			<div>
				<label for="hugeit_lightbox_thumbs_margin"><?php _e('Thumbnails Margin','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set the size of the thumbnails margin in pixels.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="number" id="hugeit_lightbox_thumbs_margin"
					   value="5" class="text">
				<span>px</span>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_thumbs_position"><?php _e('Thumbnails position','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Thumbnails position (Bottom, Top, Left, Right)','lightbox');?></p>
						</div>
					</div>
				</label>
				<select id="hugeit_lightbox_thumbs_position" >
					<option selected value="0"><?php _e('Bottom','lightbox');?></option>
				</select>
			</div>
			<div>
				<label for="hugeit_lightbox_thumbs_overlay_color"><?php _e('Thumbnails Overlay Color','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Passive Thumbnails Overlay Color.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="text" class="color" id="hugeit_lightbox_thumbs_overlay_color"
					   value="#000"
					   size="10"/>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_thumbs_overlay_opacity"><?php _e('Thumbnails Overlay Opacity','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Passive Thumbnails Overlay Opacity','lightbox');?></p>
						</div>
					</div>
				</label>
				<div class="slider-container">
					<input id="hugeit_lightbox_thumbs_overlay_opacity" data-slider-highlight="true"
						   data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text" data-slider="true"
						   value="50"/>
					<span>50%</span>
				</div>
			</div>
		</div>
		<div class="options-block hugeit-lightbox-pro-option" style="margin-top: -40px;">
			<h3>Statistics</h3>
			<div id="lightbox_stat_type">
				<input type="button" class="button_stat active" value="<?php _e('Past 24 Hours','lightbox');?>">
				<input type="button" class="button_stat" value="<?php _e('Past Month','lightbox');?>">
				<input type="button" class="button_stat" value="<?php _e('Past Year','lightbox');?>">
			</div>
			<div class="lightbox_statistics">
				<div class="wrapper past_hours active">
					<canvas id='c1' data-id="label1"></canvas>
					<div id="label1">text</div>
				</div>
			</div>
		</div>
	</div>
	<div id="lightbox-options-list"
	     class="unique-type-options-wrapper <?php if ( esc_html($hugeit_lightbox_values['hugeit_lightbox_type']) == 'old_type' ) {
		     echo "active";
	     } ?>">
		<div class="options-block">
			<h3>Main Features</h3>
			<div class="has-background">
				<label for="hugeit_lightbox_style"><?php _e('Lightbox style','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Choose the style of your popup','lightbox');?></p>
						</div>
					</div>
				</label>
				<select id="hugeit_lightbox_style" name="params[hugeit_lightbox_style]">
					<option <?php if ( esc_html($hugeit_lightbox_values['hugeit_lightbox_style']) == '1' ) {
						echo 'selected="selected"';
					} ?> value="1">1
					</option>
					<option <?php if ( esc_html($hugeit_lightbox_values['hugeit_lightbox_style']) == '2' ) {
						echo 'selected="selected"';
					} ?> value="2">2
					</option>
					<option <?php if ( esc_html($hugeit_lightbox_values['hugeit_lightbox_style']) == '3' ) {
						echo 'selected="selected"';
					} ?> value="3">3
					</option>
					<option <?php if ( esc_html($hugeit_lightbox_values['hugeit_lightbox_style']) == '4' ) {
						echo 'selected="selected"';
					} ?> value="4">4
					</option>
					<option <?php if ( esc_html($hugeit_lightbox_values['hugeit_lightbox_style']) == '5' ) {
						echo 'selected="selected"';
					} ?> value="5">5
					</option>
				</select>
				<div id="view-style-block">
					<span class="view-style-eye"><?php _e( 'Preview', 'hugeit_lightbox' ); ?></span>
					<ul>
						<li data-id="1" class="active"><img
								src="<?php echo plugins_url( '../../images/view1.jpg', __FILE__ ); ?>"></li>
						<li data-id="2"><img src="<?php echo plugins_url( '../../images/view2.jpg', __FILE__ ); ?>">
						</li>
						<li data-id="3"><img src="<?php echo plugins_url( '../../images/view3.jpg', __FILE__ ); ?>">
						</li>
						<li data-id="4"><img src="<?php echo plugins_url( '../../images/view4.jpg', __FILE__ ); ?>">
						</li>
						<li data-id="5"><img src="<?php echo plugins_url( '../../images/view5.jpg', __FILE__ ); ?>">
						</li>
					</ul>
				</div>

			</div>
			<div>
				<label for="hugeit_lightbox_transition"><?php _e('Transition type','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set the way of opening the popup.','lightbox');?></p>
						</div>
					</div>
				</label>
				<select id="hugeit_lightbox_transition" name="params[hugeit_lightbox_transition]">
					<option <?php if ( esc_html($hugeit_lightbox_values['hugeit_lightbox_transition']) == 'elastic' ) {
						echo 'selected="selected"';
					} ?> value="elastic">Elastic
					</option>
					<option <?php if ( esc_html($hugeit_lightbox_values['hugeit_lightbox_transition']) == 'fade' ) {
						echo 'selected="selected"';
					} ?> value="fade">Fade
					</option>
					<option <?php if ( esc_html($hugeit_lightbox_values['hugeit_lightbox_transition']) == 'none' ) {
						echo 'selected="selected"';
					} ?> value="none">none
					</option>
				</select>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_speed"><?php _e('Opening speed','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set the speed of opening the popup in milliseconds..','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="number" name="params[hugeit_lightbox_speed]" id="hugeit_lightbox_speed"
				       value="<?php echo esc_attr( $hugeit_lightbox_values['hugeit_lightbox_speed'] ); ?>" class="text">
				<span>ms</span>
			</div>
			<div>
				<label for="hugeit_lightbox_fadeout"><?php _e('Closing speed','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set the speed of closing the popup in milliseconds.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="number" name="params[hugeit_lightbox_fadeout]" id="hugeit_lightbox_fadeout"
				       value="<?php echo esc_attr( $hugeit_lightbox_values['hugeit_lightbox_fadeout'] ); ?>" class="text">
				<span>ms</span>
			</div>
		</div>
		<div class="options-block hugeit-lightbox-pro-option">
			<h3>Additional Options<img src="<?php echo plugins_url( '../../images/pro-icon.png', __FILE__ ) ?>"
			                           class="hugeit_lightbox_pro_logo"></h3>
			<div class="has-background hugeit-lightbox-pro-option">
				<label for="hugeit_lightbox_opacity"><?php _e('Overlay transparency','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Change the level of popup background transparency.','lightbox');?></p>
						</div>
					</div>
				</label>
				<div class="slider-container">
					<input id="hugeit_lightbox_opacity" data-slider-highlight="true"
					       data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text" data-slider="true"
					       value="20" disabled="disabled"/>
					<span>20%</span>
				</div>
			</div>
			<div class="hugeit-lightbox-pro-option">
				<label for="hugeit_lightbox_open"><?php _e('Auto open','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Choose for automatically opening the firs content after reloading.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false"/>
				<input type="checkbox" id="hugeit_lightbox_open" value="true" disabled="disabled"/>
			</div>
			<div class="has-background hugeit-lightbox-pro-option">
				<label for="hugeit_lightbox_overlayclose">Overlay close
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Choose to close the content by clicking on the overlay.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false"/>
				<input type="checkbox" id="hugeit_lightbox_overlayclose" value="true" checked="checked"
				       disabled="disabled"/>
			</div>
			<div class="hugeit-lightbox-pro-option">
				<label for="hugeit_lightbox_esckey"><?php _e('EscKey close','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Choose to close the content with esc button.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false"/>
				<input type="checkbox" id="hugeit_lightbox_esckey" value="true" disabled="disabled"/>
			</div>
			<div class="has-background hugeit-lightbox-pro-option">
				<label for="hugeit_lightbox_arrowkey"><?php _e('Keyboard navigation','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set to change the images with left and right buttons.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false"/>
				<input type="checkbox" id="hugeit_lightbox_arrowkey" value="true" disabled="disabled"/>
			</div>
			<div class="hugeit-lightbox-pro-option">
				<label for="hugeit_lightbox_loop"><?php _e('Loop content','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span><?php _e('Loop content. If �true� give the ability to move from the last
							image to the first image while navigation..','lightbox');?>
						</div>
					</div>
				</label>
				<input type="hidden" value="false"/>
				<input type="checkbox" id="hugeit_lightbox_loop" value="true" checked="checked" disabled="disabled"/>
			</div>
			<div class="has-background hugeit-lightbox-pro-option">
				<label for="hugeit_lightbox_closebutton"><?php _e('Show close button','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Choose whether to display close button.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false"/>
				<input type="checkbox" id="hugeit_lightbox_closebutton" value="true" checked="checked"
				       disabled="disabled"/>
			</div>
		</div>
		<div class="options-block hugeit-lightbox-pro-option" style="margin-top: -130px;">
			<h3>Dimensions<img src="<?php echo plugins_url( '../../images/pro-icon.png', __FILE__ ) ?>"
			                   class="hugeit_lightbox_pro_logo"></h3>

			<div class="has-background">
				<label for="hugeit_lightbox_size_fix"><?php _e('Popup size fix','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Choose to fix the popup width and high.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false"/>
				<input type="checkbox" id="hugeit_lightbox_size_fix" value="true" disabled="disabled"/>
			</div>

			<div class="fixed-size">
				<label for="hugeit_lightbox_width"><?php _e('Popup width','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Change the width of content.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="number" id="hugeit_lightbox_width" value="500" class="text" disabled="disabled"/>
				<span>px</span>
			</div>

			<div class="has-background fixed-size">
				<label for="hugeit_lightbox_height"><?php _e('Popup height','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Change the high of content.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="number" id="hugeit_lightbox_height" value="500" class="text" disabled="disabled"/>
				<span>px</span>
			</div>

			<div class="not-fixed-size">
				<label for="hugeit_lightbox_maxwidth"><?php _e('Popup maxWidth','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set unfix content max width.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="number" id="hugeit_lightbox_maxwidth" value="768" class="text" disabled="disabled"/>
				<span>px</span>
			</div>

			<div class="has-background not-fixed-size">
				<label for="hugeit_lightbox_maxheight"><?php _e('Popup maxHeight','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set unfix max hight.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="number" id="hugeit_lightbox_maxheight" value="500" class="text" disabled="disabled"/>
				<span>px</span>
			</div>

			<div>
				<label for="hugeit_lightbox_initialwidth"><?php _e('Popup initial width','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set the initial size of opening.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="number" id="hugeit_lightbox_initialwidth" value="300" class="text" disabled="disabled"/>
				<span>px</span>
			</div>

			<div class="has-background">
				<label for="hugeit_lightbox_initialheight"><?php _e('Popup initial height','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set the initial high of opening.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="number" id="hugeit_lightbox_initialheight" value="100" class="text" disabled="disabled"/>
				<span>px</span>
			</div>
		</div>
		<div class="options-block hugeit-lightbox-pro-option">
			<h3>Slideshow<img src="<?php echo plugins_url( '../../images/pro-icon.png', __FILE__ ) ?>"
			                  class="hugeit_lightbox_pro_logo"></h3>
			<div class="has-background">
				<label for="hugeit_lightbox_slideshow"><?php _e('Slideshow','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Select to enable slideshow.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false"/>
				<input type="checkbox" id="hugeit_lightbox_slideshow" value="true" checked="checked" disabled="disabled"/>
			</div>
			<div>
				<label for="hugeit_lightbox_slideshowspeed"><?php _e('Slideshow interval','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set the time between each slide.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="number" id="hugeit_lightbox_slideshowspeed" value="2500" class="text" disabled="disabled"/>
				<span>ms</span>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_slideshowauto"><?php _e('Slideshow auto start','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('If �true� it works automatically.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false"/>
				<input type="checkbox" id="hugeit_lightbox_slideshowauto" value="true" checked="checked"
				       disabled="disabled"/>
			</div>
			<div>
				<label for="hugeit_lightbox_slideshowstart"><?php _e('Slideshow start button text','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set the text on start button.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="text" id="hugeit_lightbox_slideshowstart" value="start slideshow" class="text"
				       disabled="disabled"/>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_slideshowstop"><?php _e('Slideshow stop button text','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set the text of stop button.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="text" id="hugeit_lightbox_slideshowstop" value="stop slideshow" class="text"
				       disabled="disabled"/>
			</div>
		</div>
		<div class="options-block hugeit-lightbox-pro-option" style="margin-top: -130px;">
			<h3>Positioning<img src="<?php echo plugins_url( '../../images/pro-icon.png', __FILE__ ) ?>"
			                    class="hugeit_lightbox_pro_logo"></h3>

			<div class="has-background">
				<label for="hugeit_lightbox_fixed"><?php _e('Fixed position','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('If �true� the popup does not change it�s position while scrolling up or down.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false"/>
				<input type="checkbox" id="hugeit_lightbox_fixed" checked="checked" value="true" disabled="disabled"/>
			</div>
			<div class="has-height">
				<label for=""><?php _e('Popup position','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set the position of popup.','lightbox');?></p>
						</div>
					</div>
				</label>
				<div>
					<table class="bws_position_table">
						<tbody>
						<tr>
							<td><input type="radio" value="1" id="slideshow_title_top-left" disabled="disabled"/>
							</td>
							<td><input type="radio" value="2" id="slideshow_title_top-center" disabled="disabled"/>
							</td>
							<td><input type="radio" value="3" id="slideshow_title_top-right" disabled="disabled"/>
							</td>
						</tr>
						<tr>
							<td><input type="radio" value="4" id="slideshow_title_middle-left" disabled="disabled"/>
							</td>
							<td><input type="radio" value="5" id="slideshow_title_middle-center" checked="checked"
							           disabled="disabled"/></td>
							<td><input type="radio" value="6" id="slideshow_title_middle-right"
							           disabled="disabled"/></td>
						</tr>
						<tr>
							<td><input type="radio" value="7" id="slideshow_title_bottom-left" disabled="disabled"/>
							</td>
							<td><input type="radio" value="8" id="slideshow_title_bottom-center"
							           disabled="disabled"/></td>
							<td><input type="radio" value="9" id="slideshow_title_bottom-right"
							           disabled="disabled"/></td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="options-block hugeit-lightbox-pro-option">
			<h3><?php _e('Lightbox Watermark styles','lightbox');?><img
					src="<?php echo plugins_url( '../../images/pro-icon.png', __FILE__ ) ?>"
					class="hugeit_lightbox_pro_logo"></h3>
			<div class="has-background">
				<label for="watermarket_image"><?php _e('Show Watermark Image','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Enable watermark on lightbox','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false"/>
				<input type="checkbox" id="watermarket_image" value="true" disabled="disabled"/>
			</div>
			<div class="has-height">
				<label for=""><?php _e('Lightbox Watermark position','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set the position of lightbox watermark.','lightbox');?></p>
						</div>
					</div>
				</label>
				<table class="bws_position_table">
					<tbody>
					<tr>
						<td><input type="radio" value="1" id="lightbox_watermark_position-left"
						           disabled="disabled"/></td>
						<td><input type="radio" value="2" id="lightbox_watermark_position-center"
						           disabled="disabled"/></td>
						<td><input type="radio" value="3" id="lightbox_watermark_position-right" checked="checked"
						           disabled="disabled"/></td>
					</tr>
					<tr>
						<td><input type="radio" value="4" id="lightbox_watermark_position-left"
						           disabled="disabled"/></td>
						<td style="visibility: hidden;"><input type="radio" value="4"
						                                       id="lightbox_watermark_position-left"
						                                       disabled="disabled"/></td>
						<td><input type="radio" value="6" id="lightbox_watermark_position-right"
						           disabled="disabled"/></td>
					</tr>
					<tr>
						<td><input type="radio" value="7" id="lightbox_watermark_position-left"
						           disabled="disabled"/></td>
						<td><input type="radio" value="8" id="lightbox_watermark_position-center"
						           disabled="disabled"/></td>
						<td><input type="radio" value="9" id="lightbox_watermark_position-right"
						           disabled="disabled"/></td>
					</tr>
					</tbody>
				</table>
			</div>

			<div class="has-background">
				<label for="watermark_width"><?php _e('Lightbox Watermark width','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set the widtht of Lightbox watermark.','lightbox');?></p>
						</div>
					</div>
				</label>
				<input type="number" id="watermark_width" value="30" class="text" disabled="disabled"/>
				<span>px</span>
			</div>
			<div>
				<label for="watermark_transparency"><?php _e('Lightbox Watermark transparency','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set the transparency of Lightbox Watermark.','lightbox');?></p>
						</div>
					</div>
				</label>
				<div class="slider-container">
					<input id="watermark_transparency" data-slider-highlight="true"
					       data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text" data-slider="true"
					       value="100" disabled="disabled"/>
					<span>100%</span>
				</div>
			</div>
			<div class="has-background" style="height:auto;">
				<label for="watermark_image_btn"><?php _e('Select Watermark Image','lightbox');?>
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p><?php _e('Set the image of Lightbox watermark.','lightbox');?></p>
						</div>
					</div>
				</label>
				<img src="<?php echo esc_html($hugeit_default_lightbox_values['hugeit_lightbox_watermark_img_src']); ?>" id="watermark_image"
				     style="width:120px;height:auto;">
				<input type="button" class="button wp-media-buttons-icon"
				       style="margin-left: 63%;width: auto;display: inline-block;" id="watermark_image_btn"
				       value="Change Image" disabled="disabled"/>
				<input type="hidden" id="img_watermark_hidden"
				       value="<?php echo esc_html($hugeit_default_lightbox_values['hugeit_lightbox_watermark_img_src']); ?>">
			</div>
		</div>
	</div>
</form>
<div id="post-body-heading" class="post-body-line">
	<a onclick="document.getElementById('adminForm').submit()" class="save-lightbox-options button-primary"><?php _e('Save','lightbox');?></a>
</div>
