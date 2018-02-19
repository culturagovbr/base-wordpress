jQuery( document ).ready(function() {
    jQuery('#date_listevents').daterangepicker({
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
});

function getEvents(){
    jQuery('.list_spaces').html('');
    jQuery('#loading').show('fast');

    var date_listevents = jQuery('#date_listevents').data('daterangepicker');

    var url = jQuery('#date_listevents').data('url')                 +
        '&@from='   + date_listevents.startDate.format('YYYY-MM-DD') +
        '&@to='     + date_listevents.endDate.format('YYYY-MM-DD');

    console.log(url);

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
        if(typeof events[i]['@files:avatar.avatarBig'] != 'undefined')
            thumb = '<img src="' + events[i]['@files:avatar.avatarBig'].url + '" style="float: left;">';
        else
            thumb = '';

        spaces = new Array();

        html += `<div class="row list_events_item">
                    <div class="col-md-3">${thumb}</div>
                    <div class="col-md-9">
                        <h3><a href="${baseurl}/evento/${events[i].id}" target="_blank">${events[i].name}</a></h3>
                        <p>${events[i].shortDescription}</p>`;

                        for (var y = 0; y < events[i].occurrences.length; y++) {
                            if(typeof spaces[events[i].occurrences[y].space.name] == 'undefined'){
                                spaces[events[i].occurrences[y].space.name] = '';
                                html += `<a href="${baseurl}/espaco/${events[i].occurrences[y].space.id}" target="_blank">
                                            <strong>${events[i].occurrences[y].space.name}</strong>
                                        </a><br><small><b>${events[i].occurrences[y].space.endereco}</b><ul>`;
                            }

                            spaces[events[i].occurrences[y].space.name] +=
                                `<li>
                                    ${events[i].occurrences[y].rule.description}, ${events[i].occurrences[y].rule.price}
                                </li>`;

                            if(y == events[i].occurrences.length - 1){
                                html += spaces[events[i].occurrences[y].space.name] + `</ul></small></div>`;
                            }

            }

            html += '</div>';

    }

    jQuery('.list_spaces').append(html);
}
