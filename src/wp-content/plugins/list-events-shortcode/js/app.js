jQuery( document ).ready(function() {
    jQuery('#date_listevents').daterangepicker({
        startDate:  moment().format('DD/MM/YYYY'),
        endDate:    moment().add(jQuery('#date_listevents').data('range'), 'days').format('DD/MM/YYYY'),
        locale: {
            format:         'DD/MM/YYYY',
            applyLabel:     'Buscar',
            cancelLabel:    'Cancelar',
            daysOfWeek: [
                "Do",
                "Seg",
                "Ter",
                "Qua",
                "Qui",
                "Sex",
                "Sab"
            ],
            monthNames: [
                "Janeiro",
                "Fevereiro",
                "Março",
                "Abril",
                "Maio",
                "Junho",
                "Julho",
                "Agosto",
                "Setembro",
                "Outubro",
                "Novembro",
                "Dezembro"
            ],
        }
    });

    getEvents();

    jQuery('#date_listevents').on('apply.daterangepicker', function(ev, picker) {
        getEvents();
    });
    jQuery('select#states_list').on('change', function() {
        getEvents();
    });
});

function getEvents(){
    jQuery('.list_spaces').html('');
    jQuery('#loading').show('fast');
    var states  =  jQuery('#states_list').val()

    var date_listevents = jQuery('#date_listevents').data('daterangepicker');

    var url = jQuery('#date_listevents').data('url')                 +
        '&@from='   + date_listevents.startDate.format('YYYY-MM-DD') +
        '&@to='     + date_listevents.endDate.format('YYYY-MM-DD');

    if (states!="") {
        url+=`&space:En_Estado=IN(${states})`;
    }
    //console.log(url);

    jQuery('#date_listevents span').html(date_listevents.startDate.format('DD/MM/YYYY') + ' - ' + date_listevents.endDate.format('DD/MM/YYYY'));

    jQuery.ajax({
        url: url,
        type: 'GET',
        data: {},
        success: function(response) {
            showEvents(response);
        }
    });
}

function showEvents(events){
    jQuery('#loading').hide('fast');
    baseurl = jQuery('#date_listevents').data('baseurl');

    html = '';
    for (var i = 0; i < events.length; i++) {
        thumb = '';
        if(typeof events[i]['@files:avatar.avatarBig'] != 'undefined')
            thumb = '<img src="' + events[i]['@files:avatar.avatarBig'].url + '" style="float: left;">';

        spaces = new Array();

        html += `<div class="row list_events_item">
                    <div class="col-md-3">${thumb}</div>
                    <div class="col-md-9">
                        <h3><a href="${baseurl}/evento/${events[i].id}" target="_blank">${events[i].name}</a></h3>
                        <p>${events[i].shortDescription}</p><br>`;

                        periods = new Array();
                        for (var y = 0; y < events[i].occurrences.length; y++) {
                            if(typeof periods[events[i].occurrences[y].space.name] == 'undefined')
                                periods[events[i].occurrences[y].space.name] = '';

                            spaces[events[i].occurrences[y].space.name] = {
                                name:events[i].occurrences[y].space.name,
                            };

                            periods[events[i].occurrences[y].space.name] +=
                                `<li>
                                    ${events[i].occurrences[y].rule.description}, ${events[i].occurrences[y].rule.price}
                                </li>`;
                        }

                        for (space in spaces) {
                            html += `<small><ul><b>${spaces[space].name}</b>${periods[spaces[space].name]}</ul></small>`;
                        }

            html += '</div></div>';
    }

    jQuery('.list_spaces').append(html);
}