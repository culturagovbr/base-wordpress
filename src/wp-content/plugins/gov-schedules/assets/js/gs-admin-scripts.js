(function($) {
	$(document).ready(function() {
		gs.init();
	});

	var gs = {
		init: function() {
			console.log('FOOBAR HERE MODAFUCKER!');

			$.timepicker.regional['pt-BR'] = {
				timeOnlyTitle: 'Escolha o horário',
				timeText: 'Horário',
				hourText: 'Hora',
				minuteText: 'Minutos',
				secondText: 'Segundos',
				millisecText: 'Milissegundos',
				microsecText: 'Microssegundos',
				timezoneText: 'Fuso horário',
				currentText: 'Agora',
				closeText: 'Confirmar',
				timeFormat: 'HH:mm',
				timeSuffix: '',
				amNames: ['a.m.', 'AM', 'A'],
				pmNames: ['p.m.', 'PM', 'P'],
				isRTL: false
			};
			$.timepicker.setDefaults($.timepicker.regional['pt-BR']);

			var myControl=  {
				create: function(tp_inst, obj, unit, val, min, max, step){
					$('<input class="ui-timepicker-input" value="'+val+'">')
						.appendTo(obj)
						.spinner({
							min: min,
							max: max,
							step: step,
							change: function(e,ui){ // key events
								// don't call if api was used and not key press
								if(e.originalEvent !== undefined)
									tp_inst._onTimeChange();
								tp_inst._onSelectHandler();
							},
							spin: function(e,ui){ // spin events
								tp_inst.control.value(tp_inst, obj, unit, ui.value);
								tp_inst._onTimeChange();
								tp_inst._onSelectHandler();
							}
						});
					return obj;
				},
				options: function(tp_inst, obj, unit, opts, val){
					if(typeof(opts) == 'string' && val !== undefined)
						return obj.find('.ui-timepicker-input').spinner(opts, val);
					return obj.find('.ui-timepicker-input').spinner(opts);
				},
				value: function(tp_inst, obj, unit, val){
					if(val !== undefined)
						return obj.find('.ui-timepicker-input').spinner('value', val);
					return obj.find('.ui-timepicker-input').spinner('value');
				}
			};

			//$('.datepicker').datepicker({ dateFormat : 'yy-mm-dd' });
			//$('.datepicker').datetimepicker({ dateFormat : 'dd/mm/yy' });
			$('.datepicker').datetimepicker({
				controlType: myControl,
				dateFormat : 'dd/mm/yy'
			});
		}
	};
})(jQuery);