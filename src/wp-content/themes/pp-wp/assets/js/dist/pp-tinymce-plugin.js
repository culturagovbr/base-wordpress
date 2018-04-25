jQuery(document).ready(function($) {

    function ppOverlayWindow () {
        var overlay  = '<div class="pp-overlay-window">';
        overlay += 		'<div class="modal">';
        overlay += 			'<button type="button" class="media-modal-close"><span class="media-modal-icon"><span class="screen-reader-text">Fechar o painel</span></span></button>';
        overlay += 			'<div class="modal-content">';
        overlay +=	 			'<form class="pp-shortcode-form">';
        overlay +=	 				'<h2>Mapas da Cultura</h2>';
        overlay +=	 				'<div class="form-row">';
        overlay +=	 					'<label>URL Base</label>';
        overlay +=	 					'<input type="text" value="http://mapas.cultura.gov.br" name="pp-url">';
        overlay +=	 					'<p class="desc">Deixe em branco para usar a URL padrão: <b>http://mapas.cultura.gov.br</b></p>';
        overlay +=	 				'</div>';
        overlay +=	 				'<div class="form-row">';
        overlay +=	 					'<label>Tipo</label>';
        overlay +=	 					'<select name="pp-find">';
        overlay +=	 						'<option value="event">Eventos</option>';
        overlay +=	 						'<option value="space">Espaços</option>';
        overlay +=	 						'<option value="agent">Agentes</option>';
        overlay +=	 						'<option value="project">Projetos</option>';
        overlay +=	 					'</select>';
        overlay +=	 				'</div>';
        overlay +=	 				'<div class="form-row">';
        overlay +=	 					'<label>Limite</label>';
        overlay +=	 					'<input type="number" value="10" name="pp-limit">';
        overlay +=	 				'</div>';
        overlay +=	 				'<div class="form-row">';
        overlay +=	 					'<label>Ordenamento</label>';
        overlay +=	 					'<input type="radio" name="pp-order" checked="checked" value="asc">Ascendente';
        overlay +=	 					'<input style="margin-left: 15px;" type="radio" name="pp-order" value="desc">Decrescente';
        overlay +=	 				'</div>';
        overlay +=	 				'<div class="foot">';
        overlay +=	 					'<button type="button" class="button media-button button-primary button-large pp-button-insert-shortcode">Inserir shortcode</button>';
        overlay +=	 				'</div>';
        overlay += 				'</form>';
        overlay += 			'</div>';
        overlay += 		'</div>';
        overlay += '</div>';
        $('body').addClass('modal-open');
        $('body').append(overlay);


        $('body').on('click','.pp-overlay-window .media-modal-close', function(){
            closeMcwpOverlayWindow();
        })
    }

    function closeMcwpOverlayWindow () {
        $('.pp-overlay-window').remove();
        $('body').removeClass('modal-open');
    }

    function layoutSetter() {
        $(document).on('click', '.mce-pp-layout-selector', function(){
            // console.log('123');
        })
    }

    function replaceShortcodes( content ) {
        // Match [carousel]( content )[/carousel]
        return content.replace( /\[carousel\]([^]*)\[\/carousel\]/g, function( match ) {
            return html( 'wp-gallery', match );
        });
    }

    function html( cls, data ) {
        data = window.encodeURIComponent( data );
        return '<img src="' + tinymce.Env.transparentSrc + '" class="wp-media mceItem ' + cls + '" ' +
            'data-wp-media="' + data + '" data-mce-resize="false" data-mce-placeholder="1" alt="" />';
    }

    function restoreShortcodes( content ) {
        //match any image tag with our class and replace it with the shortcode's content and attributes
        return content.replace( /(?:<p(?: [^>]+)?>)*(<img [^>]+>)(?:<\/p>)*/g, function( match, image ) {
            var data = getAttr( image, 'data-sh-attr' );
            var con = getAttr( image, 'data-sh-content' );

            if ( data ) {
                return '[' + sh_tag + data + ']' + con + '[/'+sh_tag+']';
            }
            return match;
        });
    }

    tinymce.PluginManager.add('pp_button', function (editor) {
        // Register command for when button is clicked
        editor.addCommand('pp_insert_shortcode', function() {
            ppOverlayWindow();
            $('body').on('click','.pp-button-insert-shortcode', function(){
                var ppURL = $('input[name="pp-url"]').val(),
                    ppFind = $('select[name="pp-find"]').val(),
                    ppLimit = $('input[name="pp-limit"]').val(),
                    ppOrder = $('input[name="pp-order"]:checked').val(),
                    content = '[carousel url="'+ ppURL +'" buscar="'+ ppFind +'" limite="'+ ppLimit +'" ordem="'+ ppOrder +'"]';

                tinymce.execCommand('mceInsertContent', false, content);
                closeMcwpOverlayWindow();
            });
        });

        // Register buttons - trigger above command when clicked
        editor.addButton('pp_button', {
            title : 'Mapas da Cultura',
            // image: url + '/../img/logo-mapas-culturais-br.svg',
            // cmd : 'pp_insert_shortcode',
            onclick: function() {
                layoutSetter();
                // Open window
                editor.windowManager.open({
                    id: 'pp-overlay-window',
                    title: 'Mapas da Cultura',
                    width: 600,
                    height: 340,
                    body: [
                        {
                            type   : 'textbox',
                            name   : 'ppTitle',
                            label  : 'Título',
                            value  : '',
                            classes: 'pp-title',
                            autofocus: true
                        },
                        {
                            type   : 'container',
                            name   : '',
                            label  : ' ',
                            html   : '<p class="desc">Some randon text: <b>Foobar!</b></p>'
                        },
                        {
                            type   : 'textbox',
                            name   : 'ppSlideTitle',
                            label  : 'Título do slide',
                            value  : '',
                            classes: 'pp-slide-title'
                        },
                        {
                            type   : 'textbox',
                            name   : 'ppSlideImage',
                            label  : 'Imagem do slide',
                            value  : '',
                            classes: 'pp-slide-image'
                        },
                        {
                            type   : 'textbox',
                            name   : 'ppSlideURL',
                            label  : 'URL do slide',
                            value  : '',
                            classes: 'pp-slide-url'
                        },
                        {
                            type   : 'listbox',
                            name   : 'ppSlideURLTarget',
                            label  : 'Alvo do link',
                            values : [
                                { text: 'Abrir na mesma janela', value: '0' },
                                { text: 'Abrir em uma nova janela', value: '1' }
                            ],
                            value : '0' // Sets the default
                        },
                        /* {
                            type   : 'checkbox',
                            name   : 'ppRemoveCSS',
                            label  : 'Remover estilo CSS',
                            text   : 'Sim',
                            checked : false
                        }, */
                    ],
                    onsubmit: function(e) {
                        var ppURL = (e.data.ppUrl.length) ? 'url="'+ e.data.ppUrl +'"' : '',
                            ppFind = e.data.ppFind,
                            ppLimit = e.data.ppLimit,
                            ppOrder = e.data.ppOrder,
                            ppLayout = e.data.ppLayout,
                            ppLayoutCols = e.data.ppLayoutCols,
                            ppRemoveCSS = (e.data.ppRemoveCSS) ? 'remover-css="'+ e.data.ppRemoveCSS +'"' : '',
                            content = '[carousel \
									'+ ppURL + ' \
									buscar="'+ ppFind +'" \
									limite="'+ ppLimit +'" \
									layout="'+ ppLayout +'" \
									cols="'+ ppLayoutCols +'" \
									ordem="'+ ppOrder +'" \
									'+ ppRemoveCSS +']';

                        editor.insertContent(content);
                    }
                });
            }
        });

        editor.on( 'BeforeSetContent', function( event ) {
            event.content = replaceShortcodes( event.content );
        });
    });
});