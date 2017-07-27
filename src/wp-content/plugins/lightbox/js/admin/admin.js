(function($){
	function Chart(element, options){
		this.el = element.id;
		this.label = '#' + element.dataset.id;

		this.data_ = $.extend({}, this.constructor.data_, options);

		this.init();

		return this;
	}

	Chart.data_ = {};

	Chart.prototype.init = function(){
		var label = document.querySelector(this.label);
		var c = document.getElementById(this.el);

		var ctx = c.getContext("2d");
		var cw = c.width = jQuery('.options-block').width();
		var ch = c.height = 350;
		var cx = cw / 2,
			cy = ch / 2;
		var rad = Math.PI / 180;
		var frames = 0;

		ctx.lineWidth = 1;
		ctx.strokeStyle = "#999";
		ctx.fillStyle = "#ccc";
		ctx.font = "14px monospace";

		var grd = ctx.createLinearGradient(0, 0, 0, cy);
		grd.addColorStop(0, "hsla(167,72%,60%,1)");
		grd.addColorStop(1, "hsla(167,72%,60%,0)");

		oData = this.data_;

		var valuesRy = [];
		var propsRy = [];
		for (var prop in oData) {

			valuesRy.push(oData[prop]);
			propsRy.push(prop);
		}


		var vData = 4;
		var hData = valuesRy.length;
		var offset = 50.5;
		var chartHeight = ch - 2 * offset;
		var chartWidth = cw - 2 * offset;
		var t = 1 / 7;
		var speed = 2;

		var A = {
			x: offset,
			y: offset
		};
		var B = {
			x: offset,
			y: offset + chartHeight
		};
		var C = {
			x: offset + chartWidth,
			y: offset + chartHeight
		};

		ctx.beginPath();
		ctx.moveTo(A.x, A.y);
		ctx.lineTo(B.x, B.y);
		ctx.lineTo(C.x, C.y);
		ctx.stroke();

		var aStep = (chartHeight - 50) / (vData);

		var Max = Math.ceil(arrayMax(valuesRy) / 10) * 10;
		var Min = Math.floor(arrayMin(valuesRy) / 10) * 10;
		var aStepValue = (Max - Min) / (vData);
		var verticalUnit = aStep / aStepValue;

		var a = [];
		ctx.textAlign = "right";
		ctx.textBaseline = "middle";
		for (var i = 0; i <= vData; i++) {

			if (i == 0) {
				a[i] = {
					x: A.x,
					y: A.y + 25,
					val: Max
				}
			} else {
				a[i] = {};
				a[i].x = a[i - 1].x;
				a[i].y = a[i - 1].y + aStep;
				a[i].val = a[i - 1].val - aStepValue;
			}
			drawCoords(a[i], 3, 0);
		}

		var b = [];
		ctx.textAlign = "center";
		ctx.textBaseline = "hanging";
		var bStep = chartWidth / (hData + 1);

		for (var i = 0; i < hData; i++) {
			if (i == 0) {
				b[i] = {
					x: B.x + bStep,
					y: B.y,
					val: propsRy[0]
				};
			} else {
				b[i] = {};
				b[i].x = b[i - 1].x + bStep;
				b[i].y = b[i - 1].y;
				b[i].val = propsRy[i]
			}
			drawCoords(b[i], 0, 3)
		}

		function drawCoords(o, offX, offY) {
			ctx.beginPath();
			ctx.moveTo(o.x - offX, o.y - offY);
			ctx.lineTo(o.x + offX, o.y + offY);
			ctx.stroke();

			ctx.fillText(o.val, o.x - 2 * offX, o.y + 2 * offY);
		}

		var oDots = [];
		var oFlat = [];
		var i = 0;

		for (var prop in oData) {
			oDots[i] = {};
			oFlat[i] = {};

			oDots[i].x = b[i].x;
			oFlat[i].x = b[i].x;

			oDots[i].y = b[i].y - oData[prop] * verticalUnit - 25;
			oFlat[i].y = b[i].y - 25;

			oDots[i].val = oData[b[i].val];

			i++
		}

		function animateChart() {
			requestId = window.requestAnimationFrame(animateChart);
			frames += speed;
			ctx.clearRect(60, 0, cw, ch - 60);

			for (var i = 0; i < oFlat.length; i++) {
				if (oFlat[i].y > oDots[i].y) {
					oFlat[i].y -= speed;
				}
			}
			drawCurve(oFlat);
			for (var i = 0; i < oFlat.length; i++) {
				ctx.fillText(oDots[i].val, oFlat[i].x, oFlat[i].y - 25);
				ctx.beginPath();
				ctx.arc(oFlat[i].x, oFlat[i].y, 3, 0, 2 * Math.PI);
				ctx.fill();
			}

			if (frames >= Max * verticalUnit) {
				window.cancelAnimationFrame(requestId);

			}
		}
		requestId = window.requestAnimationFrame(animateChart);

		c.addEventListener("mousemove", function(e) {
			label.innerHTML = "";
			label.style.display = "none";
			this.style.cursor = "default";

			var m = oMousePos(this, e);
			for (var i = 0; i < oDots.length; i++) {

				output(m, i);
			}

		}, false);

		function output(m, i) {
			ctx.beginPath();
			ctx.arc(oDots[i].x, oDots[i].y, 20, 0, 2 * Math.PI);
			if (ctx.isPointInPath(m.x, m.y)) {
				label.style.display = "block";
				label.style.top = (m.y + 10) + "px";
				label.style.left = (m.x + 10) + "px";
				label.innerHTML = "<strong>" + propsRy[i] + "</strong>: " + valuesRy[i];
				c.style.cursor = "pointer";
			}
		}

		function controlPoints(p) {
			var pc = [];
			for (var i = 1; i < p.length - 1; i++) {
				var dx = p[i - 1].x - p[i + 1].x;
				var dy = p[i - 1].y - p[i + 1].y;
				var x1 = p[i].x - dx * t;
				var y1 = p[i].y - dy * t;
				var o1 = {
					x: x1,
					y: y1
				};

				var x2 = p[i].x + dx * t;
				var y2 = p[i].y + dy * t;
				var o2 = {
					x: x2,
					y: y2
				};

				pc[i] = [];
				pc[i].push(o1);
				pc[i].push(o2);
			}
			return pc;
		}

		function drawCurve(p) {

			var pc = controlPoints(p);

			ctx.beginPath();
			ctx.lineTo(p[0].x, p[0].y);
			ctx.quadraticCurveTo(pc[1][1].x, pc[1][1].y, p[1].x, p[1].y);

			if (p.length > 2) {
				for (var i = 1; i < p.length - 2; i++) {
					ctx.bezierCurveTo(pc[i][0].x, pc[i][0].y, pc[i + 1][1].x, pc[i + 1][1].y, p[i + 1].x, p[i + 1].y);
				}
				var n = p.length - 1;
				ctx.quadraticCurveTo(pc[n - 1][0].x, pc[n - 1][0].y, p[n].x, p[n].y);
			}

			ctx.stroke();
			ctx.save();
			ctx.fillStyle = grd;
			ctx.fill();
			ctx.restore();
		}

		function arrayMax(array) {
			return Math.max.apply(Math, array);
		}

		function arrayMin(array) {
			return Math.min.apply(Math, array);
		}

		function oMousePos(canvas, evt) {
			var ClientRect = canvas.getBoundingClientRect();
			return {
				x: Math.round(evt.clientX - ClientRect.left),
				y: Math.round(evt.clientY - ClientRect.top)
			}
		}
	};

	$.fn['RWDChart'] = function(options){
		return this.each(function () {
			if (!$.data(this, 'RWDChart')) {
				$.data(this, 'RWDChart', new Chart(this, options));
			}
		});
	};
})(jQuery);

jQuery(window).load(function(){

	var $obj1 = {
		'1': 50,
		'2': 10,
		'3': 40,
		'4': 45,
		'5': 70,
		'6': 100,
		'7': 90,
		'8': 130,
		'9': 120,
		'10': 160,
		'11': 100,
		'12': 50,
		'13': 80,
		'14': 70,
		'15': 75,
		'16': 110,
		'17': 140,
		'18': 105,
		'19': 70,
		'20': 50,
		'21': 90,
		'22': 70,
		'23': 80,
		'24': 70
	};

	jQuery('#c1').RWDChart($obj1);
});

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

	jQuery('#hugeit_lightbox_lightboxView').change(function(){
		switch(jQuery(this).val()){
			case 'view7':
				jQuery('#lightbox_open_close_effect').parent().css('display', 'none');
				jQuery('#hugeit_lightbox_fullwidth_effect').parent().css('display', 'none');
				jQuery('#hugeit_lightbox_imageframe').parent().css('display', 'none');
				jQuery('#hugeit_lightbox_view_info').parent().css('display', 'block');
				break;
			default:
				jQuery('#lightbox_open_close_effect').parent().css('display', 'block');
				jQuery('#hugeit_lightbox_fullwidth_effect').parent().css('display', 'block');
				jQuery('#hugeit_lightbox_imageframe').parent().css('display', 'block');
				jQuery('#hugeit_lightbox_view_info').parent().css('display', 'none');
				break;
		}
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
