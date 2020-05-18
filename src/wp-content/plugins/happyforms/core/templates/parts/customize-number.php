<script type="text/template" id="happyforms-customize-number-template">
	<?php include( happyforms_get_core_folder() . '/templates/customize-form-part-header.php' ); ?>
	<p>
		<label for="<%= instance.id %>_title"><?php _e( 'Label', 'happyforms' ); ?></label>
		<input type="text" id="<%= instance.id %>_title" class="widefat title" value="<%= instance.label %>" data-bind="label" />
	</p>
	<p>
		<label for="<%= instance.id %>_label_placement"><?php _e( 'Label display', 'happyforms' ); ?></label>
		<select id="<%= instance.id %>_label_placement" data-bind="label_placement">
			<option value="above"<%= (instance.label_placement == 'above') ? ' selected' : '' %>><?php _e( 'Above', 'happyforms' ); ?></option>
			<option value="below"<%= (instance.label_placement == 'below') ? ' selected' : '' %>><?php _e( 'Below', 'happyforms' ); ?></option>
			<option value="left"<%= (instance.label_placement == 'left') ? ' selected' : '' %>><?php _e( 'Left', 'happyforms' ); ?></option>
			<option value="inside"<%= (instance.label_placement == 'inside') ? ' selected' : '' %>><?php _e( 'Inside', 'happyforms' ); ?></option>
			<option value="hidden"<%= (instance.label_placement == 'hidden') ? ' selected' : '' %>><?php _e( 'Hidden', 'happyforms' ); ?></option>
		</select>
	</p>
	<p>
		<label for="<%= instance.id %>_description"><?php _e( 'Description', 'happyforms' ); ?></label>
		<textarea id="<%= instance.id %>_description" data-bind="description"><%= instance.description %></textarea>
	</p>
	<p class="happyforms-description-options" style="display: <%= (instance.description != '') ? 'block' : 'none' %>">
		<label for="<%= instance.id %>_description_mode"><?php _e( 'Description appearance', 'happyforms' ); ?></label>
		<select id="<%= instance.id %>_description_mode" data-bind="description_mode">
			<option value=""><?php _e( 'Standard', 'happyforms' ); ?></option>
			<option value="focus-reveal"<%= (instance.description_mode == 'focus-reveal') ? ' selected' : '' %>><?php _e( 'Reveal on focus', 'happyforms' ); ?></option>
			<option value="tooltip"<%= (instance.description_mode == 'tooltip' || instance.tooltip_description ) ? ' selected' : '' %>><?php _e( 'Tooltip', 'happyforms' ); ?></option>
		</select>
	</p>
	<p class="happyforms-placeholder-option" style="display: <%= ( 'as_placeholder' !== instance.label_placement ) ? 'block' : 'none' %>">
		<label for="<%= instance.id %>_placeholder"><?php _e( 'Placeholder', 'happyforms' ); ?></label>
		<input type="text" id="<%= instance.id %>_placeholder" class="widefat title" value="<%= instance.placeholder %>" data-bind="placeholder" />
	</p>

	<?php do_action( 'happyforms_part_customize_number_before_options' ); ?>

	<div class="min-max-wrapper">
		<p>
			<label for="<%= instance.id %>_min_value"><?php _e( 'Min number', 'happyforms' ); ?></label>
			<input type="number" id="<%= instance.id %>_min_value" class="widefat title" value="<%= instance.min_value %>" data-bind="min_value" />
		</p>
		<p>
			<label for="<%= instance.id %>_max_value"><?php _e( 'Max number', 'happyforms' ); ?></label>
			<input type="number" id="<%= instance.id %>_max_value" class="widefat title" value="<%= instance.max_value %>" data-bind="max_value" />
		</p>
	</div>
	<p>
		<label>
			<input type="checkbox" class="checkbox" value="1" <% if ( instance.required ) { %>checked="checked"<% } %> data-bind="required" /> <?php _e( 'This is required', 'happyforms' ); ?>
		</label>
	</p>

	<?php do_action( 'happyforms_part_customize_number_after_options' ); ?>

	<div class="happyforms-part-advanced-settings-wrap">
		<?php do_action( 'happyforms_part_customize_number_before_advanced_options' ); ?>

		<p>
			<label>
				<input type="checkbox" name="masked" class="checkbox" value="1" <% if ( instance.masked ) { %>checked="checked"<% } %> data-bind="masked" /> <?php _e( 'Use number separators', 'happyforms' ); ?>
			</label>
		</p>
		<div class="happyforms-nested-settings mask-wrapper number-options number-options--numeric" data-trigger="masked" style="display: <%= (instance.masked == 1) ? 'flex' : 'none' %>">
			<p>
				<label for="<%= instance.id %>_mask_numeric_thousands_delimiter"><?php _e( 'Grouping', 'happyforms' ); ?></label>
				<input type="text" id="<%= instance.id %>_mask_numeric_thousands_delimiter" class="widefat title" value="<%= instance.mask_numeric_thousands_delimiter %>" data-bind="mask_numeric_thousands_delimiter" />
			</p>
			<p>
				<label for="<%= instance.id %>_mask_numeric_decimal_mark"><?php _e( 'Decimal', 'happyforms' ); ?></label>
				<input type="text" id="<%= instance.id %>_mask_numeric_decimal_mark" class="widefat title" value="<%= instance.mask_numeric_decimal_mark %>" data-bind="mask_numeric_decimal_mark" />
			</p>
		</div>
		<p>
			<label>
				<input type="checkbox" class="checkbox confirmation-checkbox" value="1" <% if ( instance.confirmation_field ) { %>checked="checked"<% } %> data-bind="confirmation_field" /> <?php _e( 'Require confirmation', 'happyforms' ); ?>
			</label>
		</p>
		<div class="happyforms-nested-settings" data-trigger="confirmation_field" style="display: <%= (instance.confirmation_field == 1) ? 'block' : 'none' %>">
			<p>
				<label for="<%= instance.id %>_confirmation_field_label"><?php _e( '\'Confirmation\' label', 'happyforms' ); ?></label>
				<input type="text" id="<%= instance.id %>_confirmation_field_label" class="widefat title" value="<%= instance.confirmation_field_label %>" data-bind="confirmation_field_label" />
			</p>
			<p>
				<label for="<%= instance.id %>_confirmation_field_placeholder"><?php _e( '\'Confirmation\' placeholder', 'happyforms' ); ?></label>
				<input type="text" id="<%= instance.id %>_confirmation_field_placeholder" class="widefat title" value="<%= instance.confirmation_field_placeholder %>" data-bind="confirmation_field_placeholder" />
			</p>
		</div>
		<p>
			<label for="<%= instance.id %>_mask_numeric_prefix"><?php _e( 'Prefix', 'happyforms' ); ?></label>
			<input type="text" id="<%= instance.id %>_mask_numeric_prefix" class="widefat title" value="<%= instance.mask_numeric_prefix %>" data-bind="mask_numeric_prefix" maxlength="50" />
		</p>
		<p>
			<label for="<%= instance.id %>_mask_numeric_suffix"><?php _e( 'Suffix', 'happyforms' ); ?></label>
			<input type="text" id="<%= instance.id %>_mask_numeric_suffix" class="widefat title" value="<%= instance.mask_numeric_suffix %>" data-bind="mask_numeric_suffix" maxlength="50" />
		</p>

		<?php happyforms_customize_part_width_control(); ?>

		<p>
			<label for="<%= instance.id %>_css_class"><?php _e( 'CSS classes', 'happyforms' ); ?></label>
			<input type="text" id="<%= instance.id %>_css_class" class="widefat title" value="<%= instance.css_class %>" data-bind="css_class" />
		</p>

		<?php do_action( 'happyforms_part_customize_number_after_advanced_options' ); ?>
	</div>

	<div class="happyforms-part-logic-wrap">
		<div class="happyforms-logic-view">
			<?php happyforms_customize_part_logic(); ?>
		</div>
	</div>

	<?php happyforms_customize_part_footer(); ?>
</script>
