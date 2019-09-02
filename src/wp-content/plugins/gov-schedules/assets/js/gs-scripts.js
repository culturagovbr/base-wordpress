(function ($) {
	$(document).ready(function () {
		gs.init();
	});

	var gs = {
		init: function () {
			this.createAgendaCalendar();
			this.createDaypickerHandle();
			this.toggleActiveAgenda();
			this.agendaArchive();
		},

		createAgendaCalendar: function () {
			$('#datepicker').datepicker({
				dayNamesMin: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab'],
				dateFormat : 'yy-mm-dd',
				onSelect: function(date) {
					var eventCat = $('.agenda-cats a.active').data('event-cat');
					gs.getEvents( date, eventCat );
				},
			});

			$('.monthpicker').on('click', function (e) {
				e.preventDefault();
				$('.monthpicker').datepicker('show');
			})
		},

		createDaypickerHandle: function () {
			$(document).on('click', '.daypicker li a', function (e) {
				e.preventDefault();
				var date = $(this).data('day'),
					eventCat = $('.agenda-cats a.active').data('event-cat');

				gs.getEvents( date, eventCat );
			})
		},

		getEvents: function (d, c) {
			var agenda = $('.gs-agenda-container');

			$.ajax( {
				url: oscar_minc_vars.ajaxurl,
				type: 'POST',
				data: {
					action: 'gs_get_week_events',
					date: d,
					event_category: c,
				},
				beforeSend: function(){
					agenda.addClass('loading');
				},
				success: function( res ) {
					if( res.success ){
						agenda.removeClass('loading');
						agenda.find('ul').html(res.data.weeks);
						agenda.find('.monthpicker .month-name').text(res.data.month);

						if( res.data.events.length ){
							agenda.find('.events').html(res.data.events);
						} else {
							agenda.find('.events').html('<div class="event-item empty"><span class="location">Sem compromissos oficiais nesta data.</span></div>');
						}
					}
				},
				error: function( jqXHR, textStatus, errorThrown ) {
					console.log( jqXHR, textStatus, errorThrown );
				},
			} );
		},

		toggleActiveAgenda: function () {
			$('.agenda-cats a').on('click', function (e) {
				e.preventDefault();
				$('.agenda-cats a').removeClass('active');
				$(this).addClass('active');

				var date = $('.daypicker li.selected a').data('day');
				gs.getEvents( date, $(this).data('event-cat') );
			})
		},

		agendaArchive: function () {
			if( $('#archive-datepicker').length ){
				var tempSelectedDate = '';

				$( '#archive-datepicker' ).datepicker({
					dateFormat : 'yy-mm-dd',
					numberOfMonths: 3,
					showCurrentAtPos: 1,
					onSelect: function(date, inst) {
						$(this).datepicker( 'option', 'showCurrentAtPos', 1 );
						inst.drawMonth +=1;

						var eventCat = $('.agenda-cats a.active').data('event-cat');
						gs.getEvents( date, 'agenda-cultural' );
					}
				});

				$(document).on('click', '.daypicker li a', function (e) {
					$( '#archive-datepicker' ).datepicker('setDate', $(this).data('day') );
				});
			}
		}
	};
})(jQuery);
