<div class="happyforms-widget happyforms-part-widget" data-part-id="<%= instance.id %>">
	<div class="happyforms-widget-top happyforms-part-widget-top">
		<div class="happyforms-part-widget-title-action">
			<button type="button" class="happyforms-widget-action">
				<span class="toggle-indicator"></span>
			</button>
		</div>
		<div class="happyforms-widget-title">
			<h3><%= settings.label %><span class="in-widget-title"<% if (!instance.label) { %> style="display: none"<% } %>>: <span><%= (instance.label) ? instance.label : '' %></span></span></h3>
		</div>
	</div>
	<div class="happyforms-widget-content">
		<div class="happyforms-widget-form">
