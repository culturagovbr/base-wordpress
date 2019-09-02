function updateSchedule(timestamp, location, track, scroll) {
    jQuery.ajax({
        type: "POST",
        dataType: "json",
        url: ajaxurl,
        data: {
            'action': 'get_schedule',
            'data-timestamp': timestamp,
            'data-location': location,
            'data-track': track
        },
        success: function(data) {

            if (data.sessions && data.sessions.length > 0) {
                var cur_time = 0;
                var cur_date = 0;
                var html = '';
                var closing_tags = '';
                jQuery.each(data.sessions, function(index, session) {
                    var concurrent = '';
                    var speakers = '';
                    var tracks = '';
                    var color = (session.color != '' ? ' style="color:' + session.color + '"' : '');
                    if (cur_date != session.date) {
                        if (cur_date != 0) {
                            if (closing_tags != '')
                                html += closing_tags;
                            closing_tags = '';
                        }
                        html += '<div class="section-content"> \
                                    <div class="followWrap"> \
                                        <div class="day-floating"> \
                                            <section class="fullwidth small-section schedule-heading no-margin"> \
                                                <div class="container"> \
                                                    <div class="aside stickem"> \
                                                        <div class="sticky-content"> \
                                                            <span><strong>' + session.date + '</strong></span> \
                                                            <ul class="page-content-nav pull-right"> \
                                                                <li class="dropdown"> \
                                                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-filter"></i></a> \
                                                                    <ul class="dropdown-menu" role="menu"> \
                                                                        <div class="container"> \
                                                                            ' + jQuery('#schedule_filters').html() + ' \
                                                                        </div> \
                                                                    </ul> \
                                                                </li> \
                                                            </ul> \
                                                        </div> \
                                                    </div> \
                                                </div> \
                                            </section> \
                                        </div> \
                                    </div> \
                                    <div class="container">';
                        cur_date = session.date;
                        closing_tags = '</div> \
                                </div>';
                    }
                    if (session.speakers)
                        jQuery.each(session.speakers, function(index, speaker) {
                            featured = speaker.featured ? ' featured' : '';
                            speakers += '<a href="' + speaker.url + '" class="img-text' + featured + '"><div class="tint">' + speaker.post_image + '</div></a>';
                        });
                    if (session.tracks)
                        jQuery.each(session.tracks, function(index, track) {
                            color = (track.color != '' ? ' style="background-color: ' + track.color + ';"' : '');
                            tracks += '<span class="label' + (track.color == '' ? ' bg-gold' : '') + '"' + color + '>' + track.name + '</span>';
                        });
                    html += '<div class="schedule-single"> \
                                <div class="col-sm-7"> \
                                    <div class="date"> \
                                        <span class="time"><i class="fa fa-clock-o"></i>' + session.time + ' - ' + session.end_time + '</span> \
                                        <span class="map"><i class="fa fa-map-marker"></i>' + session.location + '</span> \
                                    </div> \
                                    <h3 class="title"><a href="' + session.url + '">' + session.post_title + '</a></h3> \
                                    <div class="content">' + session.post_excerpt + '</div> \
                                    ' + tracks + ' \
                                </div> \
                                <div class="col-sm-5 images' + (session.speakers.length > 2 ? ' many-images' : '') + '"> \
                                    ' + speakers + ' \
                                    <a href="' + session.url + '" class="c-dark button pull-right">' + data.strings['more_info'] + ' \
                                        <span class="sessions-icon fa-stack"> \
                                            <i class="fa fa-circle-thin fa-stack-2x"></i> \
                                            <i class="fa fa-plus fa-stack-1x"></i> \
                                        </span> \
                                    </a> \
                                </div> \
                            </div>';
                    if(index == data.sessions.length - 1)
                        html += '<div class="end-div"></div>';
                });
                jQuery('section.schedule').html(html);
                loadStickyTitles();
            }
            if(scroll === true)
                jQuery(document).scrollTop(jQuery('section.content').offset().top);
        }
    });
}

jQuery(document).ready(function($) {

    jQuery(document).on('click', '.schedule a[data-timestamp], .schedule a[data-location], .schedule a[data-track]', function(e) {
        e.preventDefault();
        updateSchedule(jQuery(this).attr('data-timestamp'), jQuery(this).attr('data-location'), jQuery(this).attr('data-track'), true);
    });
    updateSchedule(null, null, null, false);
});
