(function($) {
    $(document).ready(function(e) {
        // funcoes gerais
        
        // altera lingua
	$('.menu-lang a').click(function(el) {
            var new_lang = '/' + el.currentTarget.id,
                langs = '\/en|\/es|\/pb',
                current_lang = window.location.pathname.substring(window.location.pathname.search(langs), 3);
                current_page  = window.location.href,
                new_page = '';

            if (current_page.match(langs)) {
                // substitui
                new_page = current_page.replace(current_lang, new_lang);
            } else {
               // adiciona
                new_page = window.location.origin + new_lang + window.location.pathname ;
            }
            window.location.href = new_page;
	});
        
        // convocatorias
	//////////////////
	
	// ao atualizar iframe, joga pagina parent para o topo do iframe (exceto a primeira vez)
        localStorage.loadIframeTimes = false;
        if ($('#iframe_convocatoria').length > 0) {
           $('#iframe_convocatoria').load(function() {
               if (localStorage.loadIframeTimes == 'true') {
                   document.getElementById('topo').scrollIntoView(true); 
               } else {
                   localStorage.loadIframeTimes = true;
               }
           });
        }
    });
})(jQuery);
