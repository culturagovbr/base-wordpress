/***** Handles admin side js functionality *****/

// Search widget functionality
jQuery( document ).ready(function($) {

	// Filter widget search on input keyup
	$('.multievent-search-inp').keyup(function(event) {
		multievent_search_widget();
	});
	
	// Filter widget search on button click
	$(document).on('click', '.multievent-search-btn', function() {
		multievent_search_widget();
	});
	
	// Filter widget search on input keyup
	$('.multievent-search-area').keyup(function(event) {
		multievent_search_widget_area();
	});
	
});

function multievent_search_widget() {
	var timer;
	var timeOut = 300; // delay after last keypress to execute filter
	
	clearTimeout(timer); // if we pressed the key, it will clear the previous timer and wait again
	timer = setTimeout(function() {

	    var search_value =  jQuery('.multievent-search-inp').val().toLowerCase();
		
		jQuery('#available-widgets .widget-holder #widget-list > div').each(function(index) {
			
			var contents = jQuery(this).children('.widget-top').find('h4').text().toLowerCase();
			
			if (contents.indexOf(search_value) !== -1) {
				jQuery(this).show();
			}else{
				jQuery(this).hide();
			}
		});
		
	} , timeOut);
}

function multievent_search_widget_area() {
	var timer;
	var timeOut = 300; // delay after last keypress to execute filter
	
	clearTimeout(timer); // if we pressed the key, it will clear the previous timer and wait again
	timer = setTimeout(function() {

	    var search_value =  jQuery('.multievent-search-area').val().toLowerCase();
		
		jQuery('#widgets-right .sidebars-column-1 > div').each(function(index) {
			
			var contents = jQuery(this).children('.widgets-sortables').children('.sidebar-name').find('h3').text().toLowerCase();
			
			if (contents.indexOf(search_value) !== -1) {
				jQuery(this).show();
			}else{
				jQuery(this).hide();
			}
		});
		
		jQuery('#widgets-right .sidebars-column-2 > div').each(function(index) {
			
			var contents = jQuery(this).children('.widgets-sortables').children('.sidebar-name').find('h3').text().toLowerCase();
			
			if (contents.indexOf(search_value) !== -1) {
				jQuery(this).show();
			}else{
				jQuery(this).hide();
			}
		});
		
	} , timeOut);
}