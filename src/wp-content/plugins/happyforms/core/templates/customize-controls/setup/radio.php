<div class="customize-control customize-control-radio" id="customize-control-<?php echo $control['field']; ?>">
	<?php foreach ( $control['options'] as $option => $label ) : ?>
	<div class="customize-inside-control-row" data-pointer-target>
		<input type="radio" name="<?php echo $control['field']; ?>" id="<?php echo $control['field']; ?>-<?php echo $option; ?>" value="<?php echo $option; ?>" <% if ( '<?php echo $option; ?>' === <?php echo $control['field']; ?> ) { %>checked="checked"<% } %> data-attribute="<?php echo $control['field']; ?>" />
		<label for="<?php echo $control['field']; ?>-<?php echo $option; ?>"><?php echo $label; ?></label>
	</div>
	<?php endforeach; ?>
</div>