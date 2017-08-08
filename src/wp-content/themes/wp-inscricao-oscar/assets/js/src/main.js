(function($) {
    $(document).ready(function() {
        app.init();
        app.utils();
    });

    var app = {
        init: function() {
            app.oscarMainForms();
        },

        utils: function() {
            $('.checkbox input[type="checkbox"], div[data-type=true_false] .acf-input input[type=checkbox]').on('change', function(e) {
                if ($(this).is(':checked')) {
                    $('.acf-form-submit input[type=submit]').removeAttr('disabled');
                    $(this).parent().parent().addClass('selected');
                } else {
                    $('.acf-form-submit input[type=submit]').attr('disabled', 'disabled');
                    $(this).parent().parent().removeClass('selected');
                }
            });
        },

        oscarMainForms: function() {
            if ($('#acf_inscricoes-oscar-2018').length) {
            	if( $('div[data-name="declaracao"] input[type="checkbox"]').is(':checked') ){
            		$('div[data-name="declaracao"] input[type="checkbox"]').parent().parent().addClass('selected');
            	}else{
            		$('.acf-form-submit input[type=submit]').attr('disabled', 'disabled');
            	}

                $('#oscar-add-cast').on('click', function(e) {
                    e.preventDefault();
                    var castName = $('#nome-elenco');
                    var castNationality = $('#nacionalidade-elenco');
                    var delimiter = $('#elenco').val() === '' ? '' : ' | ';

                    if (castName.val().length && castNationality.val().length) {
                        $('#elenco').val($('#elenco').val() + delimiter + castName.val() + ' (' + castNationality.val() + ')');

                        castName.val('');
                        castNationality.val('');

                        var castVal = $('#elenco').val();
                        var res = castVal.split(' | ');

                        $('#cast-holder ul').html('');
                        $(res).each(function(i, val) {
                            var item = '<li class="list-group-item"><span>' + val + '</span><a href="!#"><span class="glyphicon glyphicon-remove-sign" aria-hidden="true"></span></a></li>';
                            $('#cast-holder ul').append(item);
                        });
                    } else {
                        console.log('Campo vazio');
                    }
                })

                $('body').on('click', '#cast-holder .list-group .list-group-item a', function(e) {
                    e.preventDefault();
                    var castVal = $('#elenco').val();
                    var arr = castVal.split(' | ');
                    arr.splice(arr.indexOf($(this).parent().find('span').text()), 1);

                    $('#elenco').val('');
                    for (var i = 0, l = arr.length; i < l; i++) {
                        var delimiter = $('#elenco').val() === '' ? '' : ' | ';
                        $('#elenco').val($('#elenco').val() + delimiter + arr[i]);
                    }

                    $(this).parent().remove();
                });

                $('div[data-name="data_estreia"] input').mask('00/00/0000');

                var maskBehavior = function(val) {
                        return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
                    },
                    options = {
                        onKeyPress: function(val, e, field, options) {
                            field.mask(maskBehavior.apply({}, arguments), options);
                        }
                    };

                $('div[data-name="empresa_telefone"] input, div[data-name="empresa_fax"] input').mask(maskBehavior, options);
                $.each( $('div[data-name="anexos"] table > tbody > tr.acf-row'), function( i, rowEl ) {
                  	// console.log( rowEl );
                  	$(this).find('.file-wrap .file-info strong[data-name="title"]').addClass('xxx');
                  	if( $(this).find('.file-wrap .file-info strong[data-name="title"]').text() !== '' ){
                  		$(this).find('.file-wrap').show();
                  		$(this).find('.hide-if-value').hide();
                  	}

                });
            }

            if ($('#oscar-video-form').length) {
                $(document).on('change', '#oscar-video', function(e) {
                    if ($(this)[0].files[0]) {
                        $('#oscar-video-name').text($(this)[0].files[0].name);
                        $('#oscar-video-upload-btn').removeAttr('disabled');
                        $('#oscar-video-form .video-drag-area').addClass('ready-to-upload');
                    } else {
                        $('#oscar-video-name').text('');
                        $('#oscar-video-upload-btn').attr('disabled', 'disabled');
                        $('#oscar-video-form .video-drag-area').removeClass('ready-to-upload');
                    }
                });

                $("#oscar-video-form").on('submit', function(e) {
                    e.preventDefault();
                    $('#oscar-video-form .myprogress').css('width', '0');
                    $('#oscar-video-form .msg').text('');
                    // var filename = $('#filename').val();
                    var filename = 'Foobar';
                    var oscarVideo = $('#oscar-video').val();
                    if (oscarVideo == '') {
                        alert('Por favor, selecione um arquivo para upload.');
                        return;
                    }
                    var formData = new FormData();
                    formData.append('oscarVideo', $('#oscar-video')[0].files[0]);
                    // formData.append('filename', filename);
                    formData.append('action', 'upload_oscar_video');
                    // $('#btn').attr('disabled', 'disabled');
                    $('#oscar-video-form .msg').text('Upload em progresso, por favor, aguarde...');
                    $.ajax({
                        url: oscarJS.ajaxurl,
                        data: formData,
                        processData: false,
                        contentType: false,
                        type: 'POST',
                        beforeSend: function() {
                            $('#upload-status').removeClass('hidden');
                        },
                        // this part is progress bar
                        xhr: function() {
                            var xhr = new window.XMLHttpRequest();
                            xhr.upload.addEventListener("progress", function(evt) {
                                if (evt.lengthComputable) {
                                    var percentComplete = evt.loaded / evt.total;
                                    percentComplete = parseInt(percentComplete * 100);
                                    $('#oscar-video-form .myprogress').text(percentComplete + '%');
                                    $('#oscar-video-form .myprogress').css('width', percentComplete + '%');
                                }
                            }, false);
                            return xhr;
                        },
                        success: function(data) {
                            $('#oscar-video-form .msg').addClass('success');
                            $('#oscar-video-form .msg').html(data);
                        }
                    });
                    // return false;
                });
            }

            if ($('#oscar-register-form').length) {
           		$('input[name="cnpj"]').mask('00.000.000/0000-00', {reverse: true}); 	
            }

        }
    };
})(jQuery);