<?php
function oscar_video_upload_form( $atts ){
    $upload_video_form = '
    <form id="oscar-video-form" method="post" action="'. get_the_permalink() .'">
        <div class="form-group text-center video-drag-area dropzone">
            <input type="file" id="oscar-video" name="oscar-video" class="inputfile">
            <label id="oscar-video-btn" for="oscar-video"><span class="glyphicon glyphicon-cloud-upload" aria-hidden="true"></span> Selecione seu vídeo</label>
            <p id="oscar-video-name" class="help-block"></p>
        </div>
        <div id="upload-status" class="form-group hidden">
            <div class="progress">
                <div class="progress-bar progress-bar-success progress-bar-striped myprogress" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                    <span class="sr-only">40% Complete (success)</span>
                </div>
            </div>

            <div class="panel panel-default msg"></div>
        </div>
        <div class="text-right">
            <button id="oscar-video-upload-btn" type="submit" class="btn btn-default" disabled>Enviar</button>
        </div>
    </form>
    ';

    return $upload_video_form;
}
add_shortcode( 'oscar-upload-video', 'oscar_video_upload_form' );