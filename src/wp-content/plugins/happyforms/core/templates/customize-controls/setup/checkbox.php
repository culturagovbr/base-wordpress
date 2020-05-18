<div class="customize-control customize-control-checkbox <% if ( <?php echo $control['field']; ?> ) { %>checked<% } %>" id="customize-control-<?php echo $control['field']; ?>">
	<div class="customize-inside-control-row" data-pointer-target>
		<input type="checkbox" id="<?php echo $control['field']; ?>" value="1" <% if ( <?php echo $control['field']; ?> ) { %>checked="checked"<% } %> data-attribute="<?php echo $control['field']; ?>" />
		<label for="<?php echo $control['field']; ?>"><?php echo $control['label']; ?> <?php if ( isset( $control['tooltip'] ) ) : ?><i class="dashicons dashicons-editor-help" aria-hidden="true" data-pointer><span><?php echo $control['tooltip']; ?></span></i><?php endif; ?></label>
	</div>
</div>
