<script type="text/template" id="happyforms-form-item-template">
	<li class="customize-control">
		<div class="happyforms-widget">
			<div class="happyforms-widget-top happyforms-part-widget-top">
				<div class="happyforms-part-widget-title-action">
					<button type="button" class="happyforms-widget-action">
						<span class="screen-reader-text"><%= post_title %></span>
						<span class="toggle-indicator"></span>
					</button>
				</div>
				<div class="happyforms-widget-title">
					<h3><%= post_title %></h3>
				</div>
			</div>
			<div class="happyforms-widget-content">
				<ul class="form-actions">
					<li>
						<a href="#" data-href="form/<%= ID %>/build" class="form-action-link form-action-build-link"><?php _e( 'Add Part', 'happyforms' ); ?></a>
					</li>
					<li>
						<a href="#" data-href="form/<%= ID %>" class="form-action-link form-action-setup-link"><?php _e( 'Setup', 'happyforms' ); ?></a>
					</li>
					<li>
						<a href="#" data-href="form/<%= ID %>/style" class="form-action-link form-action-style-link"><?php _e( 'Style', 'happyforms' ); ?></a>
					</li>
					<li>
						<a href="#" data-href="form/<%= ID %>" class="form-action-link form-action-duplicate-link"><?php _e( 'Duplicate', 'happyforms' ); ?></a>
					</li>
				</ul>
			</div>
			<div class="happyforms-widget-footer">
				<div class="happyforms-widget-actions">
					<a href="#" data-href="form/<%= ID %>/remove" class="happyforms-form-remove"><?php _e( 'Delete', 'happyforms' ); ?></a> |
					<a href="#" data-href="form/<%= ID %>" class="happyforms-form-preview"><?php _e( 'Preview', 'happyforms' ); ?></a>
				</div>
			</div>
		</div>
	</li>
</script>
