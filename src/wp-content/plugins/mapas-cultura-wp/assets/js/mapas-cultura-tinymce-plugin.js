jQuery(document).ready(function($) {

	function mcwpOverlayWindow () {
		var overlay  = '<div class="mcwp-overlay-window">';
			overlay += 		'<div class="modal">';
			overlay += 			'<button type="button" class="media-modal-close"><span class="media-modal-icon"><span class="screen-reader-text">Fechar o painel</span></span></button>';
			overlay += 			'<div class="modal-content">';
			overlay +=	 			'<form class="mcwp-shortcode-form">';
			overlay +=	 				'<h2>Mapas da Cultura</h2>';
			overlay +=	 				'<div class="form-row">';
			overlay +=	 					'<label>URL Base</label>';
			overlay +=	 					'<input type="text" value="http://mapas.cultura.gov.br" name="mcwp-url">';
			overlay +=	 					'<p class="desc">Deixe em branco para usar a URL padrão: <b>http://mapas.cultura.gov.br</b></p>';
			overlay +=	 				'</div>';
			overlay +=	 				'<div class="form-row">';
			overlay +=	 					'<label>Tipo</label>';
			overlay +=	 					'<select name="mcwp-find">';
			overlay +=	 						'<option value="event">Eventos</option>';
			overlay +=	 						'<option value="space">Espaços</option>';
			overlay +=	 						'<option value="agent">Agentes</option>';
			overlay +=	 						'<option value="project">Projetos</option>';
			overlay +=	 					'</select>';
			overlay +=	 				'</div>';
			overlay +=	 				'<div class="form-row">';
			overlay +=	 					'<label>Limite</label>';
			overlay +=	 					'<input type="number" value="10" name="mcwp-limit">';
			overlay +=	 				'</div>';
			overlay +=	 				'<div class="form-row">';
			overlay +=	 					'<label>Ordenamento</label>';
			overlay +=	 					'<input type="radio" name="mcwp-order" checked="checked" value="asc">Ascendente';
			overlay +=	 					'<input style="margin-left: 15px;" type="radio" name="mcwp-order" value="desc">Decrescente';
			overlay +=	 				'</div>';
			overlay +=	 				'<div class="foot">';
			overlay +=	 					'<button type="button" class="button media-button button-primary button-large mcwp-button-insert-shortcode">Inserir shortcode</button>';
			overlay +=	 				'</div>';
			overlay += 				'</form>';
			overlay += 			'</div>';
			overlay += 		'</div>';
			overlay += '</div>';
		$('body').addClass('modal-open');
		$('body').append(overlay);


		$('body').on('click','.mcwp-overlay-window .media-modal-close', function(){
			closeMcwpOverlayWindow();
		})
	}

	function closeMcwpOverlayWindow () {
		$('.mcwp-overlay-window').remove();
		$('body').removeClass('modal-open');
	}

	function layoutSetter() {
		$(document).on('click', '.mce-mcwp-layout-selector', function(){
			// console.log('123');
		})
	}

    tinymce.create('tinymce.plugins.mcwp_plugin', {
        init : function(ed, url) {
                // Register command for when button is clicked
                ed.addCommand('mcwp_insert_shortcode', function() {
                	mcwpOverlayWindow();
                	$('body').on('click','.mcwp-button-insert-shortcode', function(){
                		var mcwpURL = $('input[name="mcwp-url"]').val(),
                			mcwpFind = $('select[name="mcwp-find"]').val(),
                			mcwpLimit = $('input[name="mcwp-limit"]').val(),
                			mcwpOrder = $('input[name="mcwp-order"]:checked').val(),
                			content = '[mapas-cultura-wp url="'+ mcwpURL +'" buscar="'+ mcwpFind +'" limite="'+ mcwpLimit +'" ordem="'+ mcwpOrder +'"]';

                		tinymce.execCommand('mceInsertContent', false, content);
                		closeMcwpOverlayWindow();
                	});
                });

            // Register buttons - trigger above command when clicked
            ed.addButton('mcwp_button', {
            	title : 'Mapas da Cultura', 
            	image: url + '/../img/logo-mapas-culturais-br.svg',
            	// cmd : 'mcwp_insert_shortcode', 
            	onclick: function() {
            		layoutSetter();
					// Open window
					ed.windowManager.open({
						id: 'mcwp-overlay-window',
						title: 'Mapas da Cultura',
						width: 600,
						height: 340,
						body: [
							{
								type   : 'combobox',
								name   : 'mcwpUrl',
								label  : 'URL Base',
								tooltip: 'Deixe em branco para usar a URL padrão: http://mapas.cultura.gov.br',
								values : [
									{ text: 'http://spcultura.prefeitura.sp.gov.br', value: 'http://spcultura.prefeitura.sp.gov.br' },
									{ text: 'http://estadodacultura.sp.gov.br', value: 'http://estadodacultura.sp.gov.br' },
									{ text: 'http://jpcultura.joaopessoa.pb.gov.br', value: 'http://jpcultura.joaopessoa.pb.gov.br' },
									{ text: 'http://cultura.sobral.ce.gov.br', value: 'http://cultura.sobral.ce.gov.br' },
									{ text: 'http://mapa.cultura.ce.gov.br', value: 'http://mapa.cultura.ce.gov.br' },
									{ text: 'http://blumenaumaiscultura.com.br', value: 'http://blumenaumaiscultura.com.br' },
									{ text: 'http://mapa.cultura.rs.gov.br', value: 'http://mapa.cultura.rs.gov.br' },
									{ text: 'http://culturaz.santoandre.sp.gov.br', value: 'http://culturaz.santoandre.sp.gov.br' },
									{ text: 'http://mapa.cultura.to.gov.br', value: 'http://mapa.cultura.to.gov.br' },
									{ text: 'https://mapas.cultura.mt.gov.br', value: 'https://mapas.cultura.mt.gov.br' },
									{ text: 'http://mapaculturalbh.pbh.gov.br', value: 'http://mapaculturalbh.pbh.gov.br' },
									{ text: 'http://lugaresdacultura.org.br', value: 'http://lugaresdacultura.org.br' },
									{ text: 'http://mapas.cultura.gov.br', value: 'http://mapas.cultura.gov.br' },
									{ text: 'http://culturaviva.gov.br', value: 'http://culturaviva.gov.br' },
									{ text: 'http://bibliotecas.cultura.gov.br', value: 'http://bibliotecas.cultura.gov.br' },
									{ text: 'http://museus.cultura.gov.br', value: 'http://museus.cultura.gov.br' },
									{ text: 'https://mapas.cultura.mt.gov.br', value: 'https://mapas.cultura.mt.gov.br' }
								],
								autofocus: true
							},
							{
								type   : 'container',
								name   : '',
								label  : ' ',
								html   : '<p class="desc">Deixe em branco para usar a URL padrão: <b>http://mapas.cultura.gov.br</b></p>'
							},
							{
								type   : 'listbox',
								name   : 'mcwpFind',
								label  : 'Tipo',
								values : [
									{ text: 'Eventos', value: 'event' },
									{ text: 'Espaços', value: 'space' },
									{ text: 'Agentes', value: 'agent' },
									// { text: 'Projetos', value: 'project' }
								],
								value : 'event' // Sets the default
							},
							{
								type   : 'textbox',
								name   : 'mcwpLimit',
								label  : 'Limite',
								value  : '10',
								classes: 'mcwp-limit',
							},
							{
								type   : 'listbox',
								name   : 'mcwpOrder',
								label  : 'Ordenamento',
								values : [
									{ text: 'Ascendente', value: 'asc' },
									{ text: 'Decrescente', value: 'desc' }
								],
								value : 'asc'
							},
							{
								type   : 'listbox',
								name   : 'mcwpLayout',
								label  : 'Layout',
								values : [
									{ text: 'Colunas', value: 'cols' },
									{ text: 'Carrosel', value: 'carousel' },
								],
								classes: 'mcwp-layout-selector'
							},
							{
								type   : 'textbox',
								name   : 'mcwpLayoutCols',
								label  : 'Colunas',
								value  : '3',
								classes: 'mcwp-layout-cols',
							},
							/* {
								type   : 'checkbox',
								name   : 'mcwpRemoveCSS',
								label  : 'Remover estilo CSS',
								text   : 'Sim',
								checked : false
							}, */
						],
						onsubmit: function(e) {
							console.log(e.data.mcwpRemoveCSS);
							var mcwpURL = (e.data.mcwpUrl.length) ? 'url="'+ e.data.mcwpUrl +'"' : '',
								mcwpFind = e.data.mcwpFind,
								mcwpLimit = e.data.mcwpLimit,
								mcwpOrder = e.data.mcwpOrder,
								mcwpLayout = e.data.mcwpLayout,
								mcwpLayoutCols = e.data.mcwpLayoutCols,
								mcwpRemoveCSS = (e.data.mcwpRemoveCSS) ? 'remover-css="'+ e.data.mcwpRemoveCSS +'"' : '',
								content = '[mapas-cultura-wp \
									'+ mcwpURL + ' \
									buscar="'+ mcwpFind +'" \
									limite="'+ mcwpLimit +'" \
									layout="'+ mcwpLayout +'" \
									cols="'+ mcwpLayoutCols +'" \
									ordem="'+ mcwpOrder +'" \
									'+ mcwpRemoveCSS +']';

							ed.insertContent(content);
						}
	        	  	});
        	  	}
            });
        },   
    });

    // Register our TinyMCE plugin
    tinymce.PluginManager.add('mcwp_button', tinymce.plugins.mcwp_plugin);
});