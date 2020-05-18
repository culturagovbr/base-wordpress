<li class="customize-control <?php echo esc_attr( 'happyforms-' . $control['type'] . '-control' ); ?>" data-target="<?php echo esc_attr( $field['target'] ); ?>" id="customize-control-<?php echo $control['field']; ?>">
	<div class="customize-control-content">
		<label>
			<input type="checkbox" name="<?php echo $control['field']; ?>" id="<?php echo $control['field']; ?>" value="<?php echo $field['value']; ?>" data-attribute="<?php echo $control['field']; ?>" <% if (<?php echo $control['field']; ?>) { %>checked="checked"<% } %>> <?php echo $control['label']; ?>
		</label>
	</div>
</li>
