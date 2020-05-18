<script type="text/template" id="happyforms-form-parts-drawer-template">
	<div id="happyforms-parts-drawer">
		<div class="happyforms-parts-drawer-header">
			<div class="happyforms-parts-drawer-header-search">
				<input type="text" placeholder="<?php _e( 'Search parts', 'happyforms' ); ?>&hellip;" id="part-search">
				<div class="happyforms-parts-drawer-header-search-icon"></div>
				<button type="button" class="happyforms-clear-search"><span class="screen-reader-text"><?php _e( 'Clear Results', 'happyforms' ); ?></span></button>
			</div>
		</div>
		<ul class="happyforms-parts-list">
			<% for (var p = 0; p < parts.length; p ++) { var part = parts[p]; %>
			<%
				var customClass = '';
				var isDummy = false;


				if ( -1 !== part.type.indexOf( 'dummy' ) ) {
					isDummy = true;
				}

				if ( isDummy ) {
					customClass = ' happyforms-parts-list-item--dummy';
				}
			%>
			<li class="happyforms-parts-list-item<%= customClass %>" data-part-type="<%= part.type %>">
				<div class="happyforms-parts-list-item-content">
					<div class="happyforms-parts-list-item-title">
						<h3><%= part.label %></h3>
						<% if ( isDummy ) { %>
							<a href="https://happyforms.me/upgrade" target="_blank"><?php _e( 'Upgrade', 'happyforms' ); ?></a>
						<% } %>
					</div>
					<div class="happyforms-parts-list-item-description"><%= part.description %></div>
				</div>
			</li>
			<% } %>
		</ul>
		<div class="happyforms-parts-drawer-not-found">
			<p><?php _e( 'No parts found.', 'happyforms' ); ?></p>
		</div>
	</div>
</script>
