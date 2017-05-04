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
	<a onclick="document.getElementById('adminForm').submit()" class="save-lightbox-options button-primary">Save</a>
</div>
<form
	action="<?php echo wp_nonce_url( 'admin.php?page=huge_it_light_box&hugeit_task=save', 'save_settings', 'hugeit_lightbox_save_settings_nonce' ) ?>"
	method="post" id="adminForm" name="adminForm">
	<ul id="lightbox_type">
		<li class="<?php if ( $hugeit_lightbox_values['hugeit_lightbox_type'] == 'new_type' ) {
			echo "active";
		} ?>">
			<label for="new_type">New Type</label>
			<input type="checkbox" name="params[hugeit_lightbox_type]"
			       id="new_type" <?php if ( $hugeit_lightbox_values['hugeit_lightbox_type'] == 'new_type' ) {
				echo 'checked';
			} ?>
			       value="new_type">
		</li>
		<li class="<?php if ( $hugeit_lightbox_values['hugeit_lightbox_type'] == 'old_type' ) {
			echo "active";
		} ?>">
			<label for="old_type">Old Type</label>
			<input type="checkbox" name="params[hugeit_lightbox_type]"
			       id="old_type" <?php if ( $hugeit_lightbox_values['hugeit_lightbox_type'] == 'old_type' ) {
				echo 'checked';
			} ?>
			       value="old_type">
		</li>
	</ul>
	<div id="new-lightbox-options-list"
	     class="unique-type-options-wrapper <?php if ( $hugeit_lightbox_values['hugeit_lightbox_type'] == 'new_type' ) {
		     echo "active";
	     } ?>">
		<div class="options-block">
			<h3>General Options</h3>
			<div class="has-background">
				<label for="hugeit_lightbox_lightboxView">Lightbox style
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Choose the style of your popup</p>
						</div>
					</div>
				</label>
				<select id="hugeit_lightbox_lightboxView" name="params[hugeit_lightbox_lightboxView]">
					<option <?php selected( 'view1', $hugeit_resp_lightbox_values['hugeit_lightbox_lightboxView'] ); ?>
						value="view1">1
					</option>
					<option <?php selected( 'view2', $hugeit_resp_lightbox_values['hugeit_lightbox_lightboxView'] ); ?>
						value="view2">2
					</option>
					<option <?php selected( 'view3', $hugeit_resp_lightbox_values['hugeit_lightbox_lightboxView'] ); ?>
						value="view3">3
					</option>
					<option <?php selected( 'view4', $hugeit_resp_lightbox_values['hugeit_lightbox_lightboxView'] ); ?>
						value="view4">4
					</option>
					<option <?php selected( 'view5', $hugeit_resp_lightbox_values['hugeit_lightbox_lightboxView'] ); ?>
						value="view5">5
					</option>
				</select>
			</div>
			<div>
				<label for="hugeit_lightbox_speed_new">Lightbox open speed
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Set lightbox opening speed</p>
						</div>
					</div>
				</label>
				<input type="number" name="params[hugeit_lightbox_speed_new]" id="hugeit_lightbox_speed_new"
				       value="<?php echo $hugeit_resp_lightbox_values['hugeit_lightbox_speed_new']; ?>"
				       class="text">
				<span>ms</span>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_overlayClose_new">Overlay close
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Check to enable close by Esc key.</p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false" name="params[hugeit_lightbox_overlayClose_new]"/>
				<input type="checkbox"
				       id="hugeit_lightbox_overlayClose_new" <?php if ( $hugeit_resp_lightbox_values['hugeit_lightbox_overlayClose_new'] == 'true' ) {
					echo 'checked="checked"';
				} ?> name="params[hugeit_lightbox_overlayClose_new]" value="true"/>
			</div>
			<div>
				<label for="hugeit_lightbox_style">Loop content
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Check to enable repeating images after one cycle.</p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false" name="params[hugeit_lightbox_loop_new]"/>
				<input type="checkbox"
				       id="hugeit_lightbox_loop_new" <?php if ( $hugeit_resp_lightbox_values['hugeit_lightbox_loop_new'] == 'true' ) {
					echo 'checked="checked"';
				} ?> name="params[hugeit_lightbox_loop_new]" value="true"/>
			</div>
			<div>
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
					   id="hugeit_lightbox_fullwidth_effect" <?php if ($hugeit_resp_lightbox_values['hugeit_lightbox_fullwidth_effect'] == 'true') {
					echo 'checked="checked"';
				} ?> name="params[hugeit_lightbox_fullwidth_effect]" value="true"/>
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
					   id="hugeit_lightbox_thumbs" <?php if ($hugeit_resp_lightbox_values['hugeit_lightbox_thumbs'] == 'true') {
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
					   id="hugeit_lightbox_showTitle" <?php if ($hugeit_resp_lightbox_values['hugeit_lightbox_showTitle'] == 'true') {
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
					   id="hugeit_lightbox_showDesc" <?php if ($hugeit_resp_lightbox_values['hugeit_lightbox_showDesc'] == 'true') {
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
					   id="hugeit_lightbox_showBorder" <?php if ($hugeit_resp_lightbox_values['hugeit_lightbox_showBorder'] == 'true') {
					echo 'checked="checked"';
				} ?> name="params[hugeit_lightbox_showBorder]" value="true"/>
			</div>
			<div class="has-background image_frame">
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
		</div>
		<div class="options-block hugeit-lightbox-pro-option">
			<h3>Dimensions<img src="<?php echo plugins_url( '../../images/pro-icon.png', __FILE__ ) ?>"
							   class="hugeit_lightbox_pro_logo"></h3>
			<div class="has-background">
				<label for="hugeit_lightbox_width_new">Lightbox Width
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Set the width of the popup in percentages.</p>
						</div>
					</div>
				</label>
				<input type="number"
				       value="<?php echo $hugeit_resp_default_lightbox_values['hugeit_lightbox_width_new']; ?>"
				       class="text">
				<span>%</span>
			</div>
			<div>
				<label for="hugeit_lightbox_height_new">Lightbox Height
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Set the height of the popup in percentages.</p>
						</div>
					</div>
				</label>
				<input type="number"
				       value="<?php echo $hugeit_resp_default_lightbox_values['hugeit_lightbox_height_new']; ?>"
				       class="text">
				<span>%</span>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_videoMaxWidth">Lightbox Video maximum width
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Set the maximum width of the popup in pixels, the height will be fixed automatically.</p>
						</div>
					</div>
				</label>
				<input type="number"
				       value="<?php echo $hugeit_resp_default_lightbox_values['hugeit_lightbox_videoMaxWidth']; ?>"
				       class="text">
				<span>px</span>
			</div>
		</div>
		<div class="options-block hugeit-lightbox-pro-option">
			<h3>Slideshow<img src="<?php echo plugins_url( '../../images/pro-icon.png', __FILE__ ) ?>"
							  class="hugeit_lightbox_pro_logo"></h3>
			<div class="has-background">
				<label for="hugeit_lightbox_slideshow_new">Slideshow
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Set the width of popup</p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false" name="params[hugeit_lightbox_slideshow_new]"/>
				<input type="checkbox"
				       id="hugeit_lightbox_slideshow_new" <?php if ( $hugeit_resp_default_lightbox_values['hugeit_lightbox_slideshow_new'] == 'true' ) {
					echo 'checked="checked"';
				} ?> name="params[hugeit_lightbox_slideshow_new]" value="true"/>
			</div>
			<div>
				<label for="hugeit_lightbox_slideshow_auto_new">Slideshow auto start
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Set the width of popup</p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false" name="params[hugeit_lightbox_slideshow_auto_new]"/>
				<input type="checkbox"
				       id="hugeit_lightbox_slideshow_auto_new" <?php if ( $hugeit_resp_default_lightbox_values['hugeit_lightbox_slideshow_auto_new'] == 'true' ) {
					echo 'checked="checked"';
				} ?> name="params[hugeit_lightbox_slideshow_auto_new]" value="true"/>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_slideshow_speed_new">Slideshow interval
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Set the height of popup</p>
						</div>
					</div>
				</label>
				<input type="number" name="params[hugeit_lightbox_slideshow_speed_new]"
				       id="hugeit_lightbox_slideshow_speed_new"
				       value="<?php echo $hugeit_resp_default_lightbox_values['hugeit_lightbox_slideshow_speed_new']; ?>"
				       class="text">
				<span>ms</span>
			</div>
		</div>
		<div class="options-block hugeit-lightbox-pro-option">
			<h3>Advanced Options<img src="<?php echo plugins_url( '../../images/pro-icon.png', __FILE__ ) ?>"
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
				<label for="hugeit_lightbox_keyPress_new">Keyboard navigation
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Choose the style of your popup</p>
						</div>
					</div>
				</label>
				<input type="checkbox"
				       id="hugeit_lightbox_keyPress_new"/>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_arrows">Show Arrows
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Choose the style of your popup</p>
						</div>
					</div>
				</label>
				<input type="checkbox"
				       id="hugeit_lightbox_arrows" checked/>
			</div>
			<div>
				<label for="hugeit_lightbox_mouseWheel">Mouse Wheel Navigaion
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Choose the style of your popup</p>
						</div>
					</div>
				</label>
				<input type="checkbox"
				       id="hugeit_lightbox_mouseWheel" />
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_download">Show Download Button
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Choose the style of your popup</p>
						</div>
					</div>
				</label>
				<input type="checkbox"
				       id="hugeit_lightbox_download" />
			</div>
			<div>
				<label for="hugeit_lightbox_showCounter">Show Counter
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Choose the style of your popup</p>
						</div>
					</div>
				</label>
				<input type="checkbox"
				       id="hugeit_lightbox_showCounter" />
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_sequence_info">Sequence Info text
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Choose the style of your popup</p>
						</div>
					</div>
				</label>
				<input type="text"
				       style="width: 13%"
				       value="<?php echo $hugeit_resp_default_lightbox_values['hugeit_lightbox_sequence_info']; ?>"
				       class="text">
				X <input type="text"
				         style="width: 13%"
				         value="<?php echo $hugeit_resp_default_lightbox_values['hugeit_lightbox_sequenceInfo']; ?>"
				         class="text">
				XX
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_slideAnimationType">Transition type
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Choose the style of your popup</p>
						</div>
					</div>
				</label>
				<select id="hugeit_lightbox_slideAnimationType" >
					<option <?php selected( 'effect_1', $hugeit_resp_default_lightbox_values['hugeit_lightbox_slideAnimationType'] ); ?>
						value="effect_1">Effect 1
					</option>
					<option <?php selected( 'effect_2', $hugeit_resp_default_lightbox_values['hugeit_lightbox_slideAnimationType'] ); ?>
						value="effect_2">Effect 2
					</option>
					<option <?php selected( 'effect_3', $hugeit_resp_default_lightbox_values['hugeit_lightbox_slideAnimationType'] ); ?>
						value="effect_3">Effect 3
					</option>
					<option <?php selected( 'effect_4', $hugeit_resp_default_lightbox_values['hugeit_lightbox_slideAnimationType'] ); ?>
						value="effect_4">Effect 4
					</option>
					<option <?php selected( 'effect_5', $hugeit_resp_default_lightbox_values['hugeit_lightbox_slideAnimationType'] ); ?>
						value="effect_5">Effect 5
					</option>
					<option <?php selected( 'effect_6', $hugeit_resp_default_lightbox_values['hugeit_lightbox_slideAnimationType'] ); ?>
						value="effect_6">Effect 6
					</option>
					<option <?php selected( 'effect_7', $hugeit_resp_default_lightbox_values['hugeit_lightbox_slideAnimationType'] ); ?>
						value="effect_7">Effect 7
					</option>
					<option <?php selected( 'effect_8', $hugeit_resp_default_lightbox_values['hugeit_lightbox_slideAnimationType'] ); ?>
						value="effect_8">Effect 8
					</option>
					<option <?php selected( 'effect_9', $hugeit_resp_default_lightbox_values['hugeit_lightbox_slideAnimationType'] ); ?>
						value="effect_9">Effect 9
					</option>
				</select>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_title_pos">Title position
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Title position</p>
						</div>
					</div>
				</label>
				<select id="hugeit_lightbox_title_pos">
					<option value="left">Left</option>
					<option value="center">Center</option>
					<option value="right">Right</option>
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
		</div>
		<div class="options-block hugeit-lightbox-pro-option" style="margin-top: -1010px;">
			<h3>Lightbox Watermark styles<img src="<?php echo plugins_url( '../../images/pro-icon.png', __FILE__ ) ?>"
											  class="hugeit_lightbox_pro_logo"></h3>
			<div class="has-background">
				<label for="hugeit_lightbox_watermark">Watermark
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Set the width of popup</p>
						</div>
					</div>
				</label>
				<input type="checkbox"
				       id="hugeit_lightbox_watermark"  />
			</div>
			<div>
				<label for="hugeit_lightbox_watermark_text">Watermark Text
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Choose the style of your popup</p>
						</div>
					</div>
				</label>
				<input type="text"  id="hugeit_lightbox_watermark_text"
				       value="<?php echo $hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_text']; ?>"
				       class="text">
			</div>
			<div>
				<label for="hugeit_lightbox_watermark_textColor">Watermark Text Color
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Choose the style of your popup</p>
						</div>
					</div>
				</label>
				<input type="text" class="color" id="hugeit_lightbox_watermark_textColor"
				       value="#<?php echo $hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_textColor']; ?>"
				       size="10"/>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_watermark_textFontSize">Watermark Text Font Size
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Choose the style of your popup</p>
						</div>
					</div>
				</label>
				<input type="number"
				       id="hugeit_lightbox_watermark_textFontSize"
				       value="<?php echo $hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_textFontSize']; ?>"
				       class="text">
				<span>px</span>
			</div>
			<div>
				<label for="hugeit_lightbox_watermark_containerBackground">Watermark Background Color
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Choose the style of your popup</p>
						</div>
					</div>
				</label>
				<input type="text" class="color" id="hugeit_lightbox_watermark_containerBackground"
				       value="#<?php echo $hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_containerBackground']; ?>"
				       size="10"/>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_watermark_containerOpacity">Watermark Background Opacity
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Choose the style of your popup</p>
						</div>
					</div>
				</label>
				<div class="slider-container">
					<input id="hugeit_lightbox_watermark_containerOpacity" data-slider-highlight="true"
					       data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text" data-slider="true"
					       value="<?php echo $hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_containerOpacity']; ?>"/>
						<span><?php echo $hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_containerOpacity']; ?>
							%</span>
				</div>
			</div>
			<div>
				<label for="hugeit_lightbox_watermark_containerWidth">Watermark Width
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Choose the style of your popup</p>
						</div>
					</div>
				</label>
				<input type="number"
				       id="hugeit_lightbox_watermark_containerWidth"
				       value="<?php echo $hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_containerWidth']; ?>"
				       class="text">
				<span>px</span>
			</div>
			<div class="has-background has-height">
				<label for="hugeit_lightbox_watermark_containerWidth">Watermark Position
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Choose the style of your popup</p>
						</div>
					</div>
				</label>
				<div>
					<table class="bws_position_table">
						<tbody>
						<tr>
							<td><input type="radio" value="1" id="watermark_top-left"
									<?php if ( $hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_position_new'] == '1' ) {
									echo 'checked="checked"';
								} ?> /></td>
							<td><input type="radio" value="2" id="watermark_top-center"
							            <?php if ( $hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_position_new'] == '2' ) {
									echo 'checked="checked"';
								} ?> /></td>
							<td><input type="radio" value="3" id="watermark_top-right"
									<?php if ( $hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_position_new'] == '3' ) {
									echo 'checked="checked"';
								} ?> /></td>
						</tr>
						<tr>
							<td><input type="radio" value="4" id="watermark_middle-left"
									<?php if ( $hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_position_new'] == '4' ) {
									echo 'checked="checked"';
								} ?> /></td>
							<td><input type="radio" value="5" id="watermark_middle-center"
									<?php if ( $hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_position_new'] == '5' ) {
									echo 'checked="checked"';
								} ?> /></td>
							<td><input type="radio" value="6" id="watermark_middle-right"
									<?php if ( $hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_position_new'] == '6' ) {
									echo 'checked="checked"';
								} ?> /></td>
						</tr>
						<tr>
							<td><input type="radio" value="7" id="watermark_bottom-left"
									<?php if ( $hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_position_new'] == '7' ) {
									echo 'checked="checked"';
								} ?> /></td>
							<td><input type="radio" value="8" id="watermark_bottom-center"
									<?php if ( $hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_position_new'] == '8' ) {
									echo 'checked="checked"';
								} ?> /></td>
							<td><input type="radio" value="9" id="watermark_bottom-right"
									<?php if ( $hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_position_new'] == '9' ) {
									echo 'checked="checked"';
								} ?> /></td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div>
				<label for="hugeit_lightbox_watermark_margin">Watermark Margin
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Choose the style of your popup</p>
						</div>
					</div>
				</label>
				<input type="number"
				       id="hugeit_lightbox_watermark_margin"
				       value="<?php echo $hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_margin']; ?>"
				       class="text">
				<span>px</span>
			</div>
			<div class="has-background" style="display: none">
				<label for="hugeit_lightbox_watermark_opacity">Watermark Text Opacity
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Choose the style of your popup</p>
						</div>
					</div>
				</label>
				<div class="slider-container">
					<input id="hugeit_lightbox_watermark_opacity" data-slider-highlight="true"
					       data-slider-values="0,10,20,30,40,50,60,70,80,90,100" type="text" data-slider="true"
					       value="<?php echo $hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_opacity']; ?>"/>
					<span><?php echo $hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_opacity']; ?>%</span>
				</div>
			</div>
			<div class="has-background" style="height:auto;">
				<label for="watermark_image_btn">Select Watermark Image
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Set the image of Lightbox watermark.</p>
						</div>
					</div>
				</label>
				<img src="<?php echo $hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_img_src_new']; ?>"
				     id="watermark_image_new" style="width:120px;height:auto;">
				<input type="button" class="button wp-media-buttons-icon"
				       style="margin-left: 63%;width: auto;display: inline-block;" id="watermark_image_btn_new"
				       value="Change Image">
				<input type="hidden" id="img_watermark_hidden_new" value="<?php echo $hugeit_resp_default_lightbox_values['hugeit_lightbox_watermark_img_src_new']; ?>">
			</div>
		</div>
		<div class="options-block hugeit-lightbox-pro-option" style="margin-top: -345px;">
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
		<div class="options-block hugeit-lightbox-pro-option" style="margin-top: -85px;">
			<h3>Social Share Buttons<img src="<?php echo plugins_url( '../../images/pro-icon.png', __FILE__ ) ?>"
										 class="hugeit_lightbox_pro_logo"></h3>
			<div class="has-background">
				<label for="hugeit_lightbox_socialSharing">Social Share Buttons
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Set the width of popup</p>
						</div>
					</div>
				</label>
				<input type="checkbox"  id="hugeit_lightbox_socialSharing"  />
			</div>
			<div class="social-buttons-list">
				<label>Social Share Buttons List
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Choose the style of your popup</p>
						</div>
					</div>
				</label>
				<div>
					<table>
						<tr>
							<td>
								<label for="hugeit_lightbox_facebookButton">Facebook
									<input type="checkbox"
									       id="hugeit_lightbox_facebookButton" <?php if ( $hugeit_resp_default_lightbox_values['hugeit_lightbox_facebookButton'] == 'true' ) {
										echo 'checked="checked"';
									} ?>  value="true"/></label>
							</td>
							<td>
								<label for="hugeit_lightbox_twitterButton">Twitter
									<input type="checkbox"
									       id="hugeit_lightbox_twitterButton" <?php if ( $hugeit_resp_default_lightbox_values['hugeit_lightbox_twitterButton'] == 'true' ) {
										echo 'checked="checked"';
									} ?>  value="true"/></label>
							</td>
							<td>
								<label for="hugeit_lightbox_googleplusButton">Google Plus
									<input type="checkbox"
									       id="hugeit_lightbox_googleplusButton" <?php if ( $hugeit_resp_default_lightbox_values['hugeit_lightbox_googleplusButton'] == 'true' ) {
										echo 'checked="checked"';
									} ?>  value="true"/></label>
							</td>
							<td>
								<label for="hugeit_lightbox_pinterestButton">Pinterest
									<input type="checkbox"
									       id="hugeit_lightbox_pinterestButton" <?php if ( $hugeit_resp_default_lightbox_values['hugeit_lightbox_pinterestButton'] == 'true' ) {
										echo 'checked="checked"';
									} ?>  value="true"/></label>
							</td>
						</tr>
						<tr>
							<td>
								<label for="hugeit_lightbox_linkedinButton">Linkedin
									<input type="checkbox"
									       id="hugeit_lightbox_linkedinButton" <?php if ( $hugeit_resp_default_lightbox_values['hugeit_lightbox_linkedinButton'] == 'true' ) {
										echo 'checked="checked"';
									} ?>  value="true"/></label>
							</td>
							<td>
								<label for="hugeit_lightbox_tumblrButton">Tumblr
									<input type="checkbox"
									       id="hugeit_lightbox_tumblrButton" <?php if ( $hugeit_resp_default_lightbox_values['hugeit_lightbox_tumblrButton'] == 'true' ) {
										echo 'checked="checked"';
									} ?>  value="true"/></label>
							</td>
							<td>
								<label for="hugeit_lightbox_redditButton">Reddit
									<input type="checkbox"
									       id="hugeit_lightbox_redditButton" <?php if ( $hugeit_resp_default_lightbox_values['hugeit_lightbox_redditButton'] == 'true' ) {
										echo 'checked="checked"';
									} ?>  value="true"/></label>
							</td>
							<td>
								<label for="hugeit_lightbox_bufferButton">Buffer
									<input type="checkbox"
									       id="hugeit_lightbox_bufferButton" <?php if ( $hugeit_resp_default_lightbox_values['hugeit_lightbox_bufferButton'] == 'true' ) {
										echo 'checked="checked"';
									} ?>  value="true"/></label>
							</td>
						</tr>
						<tr>
							<td>
								<label for="hugeit_lightbox_vkButton">Vkontakte
									<input type="checkbox"
									       id="hugeit_lightbox_vkButton" <?php if ( $hugeit_resp_default_lightbox_values['hugeit_lightbox_vkButton'] == 'true' ) {
										echo 'checked="checked"';
									} ?>  value="true"/></label>
							</td>
							<td>
								<label for="hugeit_lightbox_yummlyButton">Yumly
									<input type="checkbox"
									       id="hugeit_lightbox_yummlyButton" <?php if ( $hugeit_resp_default_lightbox_values['hugeit_lightbox_yummlyButton'] == 'true' ) {
										echo 'checked="checked"';
									} ?>  value="true"/></label>
							</td>
							<td>
								<label for="hugeit_lightbox_diggButton">Digg
									<input type="checkbox"
									       id="hugeit_lightbox_diggButton" <?php if ( $hugeit_resp_default_lightbox_values['hugeit_lightbox_diggButton'] == 'true' ) {
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

	</div>
	<div id="lightbox-options-list"
	     class="unique-type-options-wrapper <?php if ( $hugeit_lightbox_values['hugeit_lightbox_type'] == 'old_type' ) {
		     echo "active";
	     } ?>">
		<div class="options-block">
			<h3>Main Features</h3>
			<div class="has-background">
				<label for="hugeit_lightbox_style">Lightbox style
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Choose the style of your popup</p>
						</div>
					</div>
				</label>
				<select id="hugeit_lightbox_style" name="params[hugeit_lightbox_style]">
					<option <?php if ( $hugeit_lightbox_values['hugeit_lightbox_style'] == '1' ) {
						echo 'selected="selected"';
					} ?> value="1">1
					</option>
					<option <?php if ( $hugeit_lightbox_values['hugeit_lightbox_style'] == '2' ) {
						echo 'selected="selected"';
					} ?> value="2">2
					</option>
					<option <?php if ( $hugeit_lightbox_values['hugeit_lightbox_style'] == '3' ) {
						echo 'selected="selected"';
					} ?> value="3">3
					</option>
					<option <?php if ( $hugeit_lightbox_values['hugeit_lightbox_style'] == '4' ) {
						echo 'selected="selected"';
					} ?> value="4">4
					</option>
					<option <?php if ( $hugeit_lightbox_values['hugeit_lightbox_style'] == '5' ) {
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
				<label for="hugeit_lightbox_transition">Transition type
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Set the way of opening the popup.</p>
						</div>
					</div>
				</label>
				<select id="hugeit_lightbox_transition" name="params[hugeit_lightbox_transition]">
					<option <?php if ( $hugeit_lightbox_values['hugeit_lightbox_transition'] == 'elastic' ) {
						echo 'selected="selected"';
					} ?> value="elastic">Elastic
					</option>
					<option <?php if ( $hugeit_lightbox_values['hugeit_lightbox_transition'] == 'fade' ) {
						echo 'selected="selected"';
					} ?> value="fade">Fade
					</option>
					<option <?php if ( $hugeit_lightbox_values['hugeit_lightbox_transition'] == 'none' ) {
						echo 'selected="selected"';
					} ?> value="none">none
					</option>
				</select>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_speed">Opening speed
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Set the speed of opening the popup in milliseconds..</p>
						</div>
					</div>
				</label>
				<input type="number" name="params[hugeit_lightbox_speed]" id="hugeit_lightbox_speed"
				       value="<?php echo esc_attr( $hugeit_lightbox_values['hugeit_lightbox_speed'] ); ?>" class="text">
				<span>ms</span>
			</div>
			<div>
				<label for="hugeit_lightbox_fadeout">Closing speed
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Set the speed of closing the popup in milliseconds.</p>
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
				<label for="hugeit_lightbox_opacity">Overlay transparency
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Change the level of popup background transparency.</p>
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
				<label for="hugeit_lightbox_open">Auto open
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Choose for automatically opening the firs content after reloading.</p>
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
							<p>Choose to close the content by clicking on the overlay.</p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false"/>
				<input type="checkbox" id="hugeit_lightbox_overlayclose" value="true" checked="checked"
				       disabled="disabled"/>
			</div>
			<div class="hugeit-lightbox-pro-option">
				<label for="hugeit_lightbox_esckey">EscKey close
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Choose to close the content with esc button.</p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false"/>
				<input type="checkbox" id="hugeit_lightbox_esckey" value="true" disabled="disabled"/>
			</div>
			<div class="has-background hugeit-lightbox-pro-option">
				<label for="hugeit_lightbox_arrowkey">Keyboard navigation
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Set to change the images with left and right buttons.</p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false"/>
				<input type="checkbox" id="hugeit_lightbox_arrowkey" value="true" disabled="disabled"/>
			</div>
			<div class="hugeit-lightbox-pro-option">
				<label for="hugeit_lightbox_loop">Loop content
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>Loop content. If �true� give the ability to move from the last
							image to the first image while navigation..</p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false"/>
				<input type="checkbox" id="hugeit_lightbox_loop" value="true" checked="checked" disabled="disabled"/>
			</div>
			<div class="has-background hugeit-lightbox-pro-option">
				<label for="hugeit_lightbox_closebutton">Show close button
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Choose whether to display close button.</p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false"/>
				<input type="checkbox" id="hugeit_lightbox_closebutton" value="true" checked="checked"
				       disabled="disabled"/>
			</div>
		</div>
		<div class="options-block hugeit-lightbox-pro-option">
			<h3>Dimensions<img src="<?php echo plugins_url( '../../images/pro-icon.png', __FILE__ ) ?>"
			                   class="hugeit_lightbox_pro_logo"></h3>

			<div class="has-background">
				<label for="hugeit_lightbox_size_fix">Popup size fix
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Choose to fix the popup width and high.</p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false"/>
				<input type="checkbox" id="hugeit_lightbox_size_fix" value="true" disabled="disabled"/>
			</div>

			<div class="fixed-size">
				<label for="hugeit_lightbox_width">Popup width
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Change the width of content.</p>
						</div>
					</div>
				</label>
				<input type="number" id="hugeit_lightbox_width" value="500" class="text" disabled="disabled"/>
				<span>px</span>
			</div>

			<div class="has-background fixed-size">
				<label for="hugeit_lightbox_height">Popup height
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Change the high of content.</p>
						</div>
					</div>
				</label>
				<input type="number" id="hugeit_lightbox_height" value="500" class="text" disabled="disabled"/>
				<span>px</span>
			</div>

			<div class="not-fixed-size">
				<label for="hugeit_lightbox_maxwidth">Popup maxWidth
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Set unfix content max width.</p>
						</div>
					</div>
				</label>
				<input type="number" id="hugeit_lightbox_maxwidth" value="768" class="text" disabled="disabled"/>
				<span>px</span>
			</div>

			<div class="has-background not-fixed-size">
				<label for="hugeit_lightbox_maxheight">Popup maxHeight
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Set unfix max hight.</p>
						</div>
					</div>
				</label>
				<input type="number" id="hugeit_lightbox_maxheight" value="500" class="text" disabled="disabled"/>
				<span>px</span>
			</div>

			<div>
				<label for="hugeit_lightbox_initialwidth">Popup initial width
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Set the initial size of opening.</p>
						</div>
					</div>
				</label>
				<input type="number" id="hugeit_lightbox_initialwidth" value="300" class="text" disabled="disabled"/>
				<span>px</span>
			</div>

			<div class="has-background">
				<label for="hugeit_lightbox_initialheight">Popup initial height
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Set the initial high of opening.</p>
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
				<label for="hugeit_lightbox_slideshow">Slideshow
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Select to enable slideshow.</p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false"/>
				<input type="checkbox" id="hugeit_lightbox_slideshow" value="true" checked="checked" disabled="disabled"/>
			</div>
			<div>
				<label for="hugeit_lightbox_slideshowspeed">Slideshow interval
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Set the time between each slide.</p>
						</div>
					</div>
				</label>
				<input type="number" id="hugeit_lightbox_slideshowspeed" value="2500" class="text" disabled="disabled"/>
				<span>ms</span>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_slideshowauto">Slideshow auto start
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>If �true� it works automatically.</p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false"/>
				<input type="checkbox" id="hugeit_lightbox_slideshowauto" value="true" checked="checked"
				       disabled="disabled"/>
			</div>
			<div>
				<label for="hugeit_lightbox_slideshowstart">Slideshow start button text
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Set the text on start button.</p>
						</div>
					</div>
				</label>
				<input type="text" id="hugeit_lightbox_slideshowstart" value="start slideshow" class="text"
				       disabled="disabled"/>
			</div>
			<div class="has-background">
				<label for="hugeit_lightbox_slideshowstop">Slideshow stop button text
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Set the text of stop button.</p>
						</div>
					</div>
				</label>
				<input type="text" id="hugeit_lightbox_slideshowstop" value="stop slideshow" class="text"
				       disabled="disabled"/>
			</div>
		</div>
		<div class="options-block hugeit-lightbox-pro-option" style="margin-top:0px;">
			<h3>Positioning<img src="<?php echo plugins_url( '../../images/pro-icon.png', __FILE__ ) ?>"
			                    class="hugeit_lightbox_pro_logo"></h3>

			<div class="has-background">
				<label for="hugeit_lightbox_fixed">Fixed position
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>If �true� the popup does not change it�s position while scrolling up or down.</p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false"/>
				<input type="checkbox" id="hugeit_lightbox_fixed" checked="checked" value="true" disabled="disabled"/>
			</div>
			<div class="has-height">
				<label for="">Popup position
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Set the position of popup.</p>
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
			<h3>Lightbox Watermark styles<img
					src="<?php echo plugins_url( '../../images/pro-icon.png', __FILE__ ) ?>"
					class="hugeit_lightbox_pro_logo"></h3>
			<div class="has-background">
				<label for="watermarket_image">Show Watermark Image
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Enable watermark on lightbox</p>
						</div>
					</div>
				</label>
				<input type="hidden" value="false"/>
				<input type="checkbox" id="watermarket_image" value="true" disabled="disabled"/>
			</div>
			<div class="has-height">
				<label for="">Lightbox Watermark position
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Set the position of lightbox watermark.</p>
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
				<label for="watermark_width">Lightbox Watermark width
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Set the widtht of Lightbox watermark.</p>
						</div>
					</div>
				</label>
				<input type="number" id="watermark_width" value="30" class="text" disabled="disabled"/>
				<span>px</span>
			</div>
			<div>
				<label for="watermark_transparency">Lightbox Watermark transparency
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Set the transparency of Lightbox Watermark.</p>
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
				<label for="watermark_image_btn">Select Watermark Image
					<div class="help">?
						<div class="help-block">
							<span class="pnt"></span>
							<p>Set the image of Lightbox watermark.</p>
						</div>
					</div>
				</label>
				<img src="<?php echo $hugeit_default_lightbox_values['hugeit_lightbox_watermark_img_src']; ?>" id="watermark_image"
				     style="width:120px;height:auto;">
				<input type="button" class="button wp-media-buttons-icon"
				       style="margin-left: 63%;width: auto;display: inline-block;" id="watermark_image_btn"
				       value="Change Image" disabled="disabled"/>
				<input type="hidden" id="img_watermark_hidden"
				       value="<?php echo $hugeit_default_lightbox_values['hugeit_lightbox_watermark_img_src']; ?>">
			</div>
		</div>
	</div>
</form>
<div id="post-body-heading" class="post-body-line">
	<a onclick="document.getElementById('adminForm').submit()" class="save-lightbox-options button-primary">Save</a>
</div>
