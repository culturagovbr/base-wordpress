jQuery(document).ready(function() {
	
	jQuery('#lightbox_type input').change(function () {
		jQuery('#lightbox_type input').parent().removeClass('active');
		jQuery(this).parent().addClass('active');
		if(jQuery(this).val() == 'old_type'){
			jQuery('#lightbox-options-list').addClass('active');
			jQuery('#new-lightbox-options-list').removeClass('active');
		}
		else{
			jQuery('#lightbox-options-list').removeClass('active');
			jQuery('#new-lightbox-options-list').addClass('active');
		}
		jQuery('#lightbox_type input').prop('checked',false);
		if(!jQuery(this).prop('checked')){
			jQuery(this).prop('checked',true);
		}
	});
	popupsizes(jQuery('#light_box_size_fix'));
	function popupsizes(checkbox) {
		if (checkbox.is(':checked')) {
			jQuery('.options-block .not-fixed-size').css({'display': 'none'});
			jQuery('.options-block .fixed-size').css({'display': 'block'});
		} else {
			jQuery('.options-block .fixed-size').css({'display': 'none'});
			jQuery('.options-block .not-fixed-size').css({'display': 'block'});
		}
	}

	jQuery('#light_box_size_fix').change(function() {
		popupsizes(jQuery(this));
	});

	jQuery('#arrows-type input[name="params[slider_navigation_type]"]').change(function() {
		jQuery(this).parents('ul').find('li.active').removeClass('active');
		jQuery(this).parents('li').addClass('active');
	});

	jQuery('input[data-slider="true"]').bind("slider:changed", function(event, data) {
		jQuery(this).parent().find('span').html(parseInt(data.value) + "%");
		jQuery(this).val(parseInt(data.value));
	});


	jQuery('#view-style-block ul li[data-id="' + jQuery('#light_box_style option[selected="selected"]').val() + '"]').addClass('active');

	jQuery('#light_box_style').change(function() {
		var strtr = jQuery(this).val();
		jQuery('#view-style-block ul li').removeClass('active');
		jQuery('#view-style-block ul li[data-id="' + strtr + '"]').addClass('active');
	});

	jQuery('#view-image_frame ul li[data-id="' + jQuery('#light_box_style option[selected="selected"]').val() + '"]').addClass('active');

	jQuery('#hugeit_lightbox_imageframe').change(function() {
		var $strt = jQuery(this).val();
		jQuery('#view-image_frame ul li').removeClass('active');
		jQuery('#view-image_frame ul li[data-id="' + $strt + '"]').addClass('active');
	});
	
	jQuery('.help').hover(function() {
		jQuery(this).parent().find('.help-block').removeClass('active');
		var width = jQuery(this).parent().find('.help-block').outerWidth();
		jQuery(this).parent().find('.help-block').addClass('active').css({'left': -((width / 2) - 10)});
	}, function() {
		jQuery(this).parent().find('.help-block').removeClass('active');
	});

	jQuery('.hugeit-lightbox-pro-option input, .hugeit-lightbox-pro-option select').on('focus change click', function(e) {
		e.preventDefault();
		alert('Some of Lightbox Settings are disabled in free version. If you need those functionalities, you need to buy the commercial version.');
	});

	jQuery('.close_free_banner').on('click', function() {
		jQuery(".free_version_banner").css('display', 'none');
		hgLightboxSetCookie('hgSliderFreeBannerShow', 'no', {expires: 3600});
	});
});

function hgLightboxSetCookie(name, value, options) {
	options = options || {};

	var expires = options.expires;

	if (typeof expires == "number" && expires) {
		var d = new Date();
		d.setTime(d.getTime() + expires * 1000);
		expires = options.expires = d;
	}
	if (expires && expires.toUTCString) {
		options.expires = expires.toUTCString();
	}


	if(typeof value == "object"){
		value = JSON.stringify(value);
	}
	value = encodeURIComponent(value);
	var updatedCookie = name + "=" + value;

	for (var propName in options) {
		updatedCookie += "; " + propName;
		var propValue = options[propName];
		if (propValue !== true) {
			updatedCookie += "=" + propValue;
		}
	}

	document.cookie = updatedCookie;
}
