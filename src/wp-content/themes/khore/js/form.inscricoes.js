(function($) {
    $(document).ready(function(e) {
	// validacao
	
	var fieldsDefinition = [
	    {'name': 'nome_completo', 'required': true, 'type': 'text'},
	    {'name': 'email', 'required': true, 'type': 'text'},
	    {'name': 'telefone', 'required': true, 'type': 'text'},
	    {'name': 'coletivo_entidade', 'required': true, 'type': 'text'},
	    {'name': 'cidade', 'required': true, 'type': 'text'},
	    {'name': 'uf', 'required': true, 'type': 'text'},
	    {'name': 'pais', 'required': true, 'type': 'text'},
	    {'name': 'necessidade_especial', 'required': false, 'type': 'text'},
	    {'name': 'receber_informacoes', 'required': true, 'type': 'text'}
	];
	
	var validaForm = function(fieldsDefinition) {
	    var unfilled = [];
	    
	    for (var i = 0; i < fieldsDefinition.length; i++) {
		var item = fieldsDefinition[i],
		    el = '.' + item.name;
		
		if (item.required) {
		    if ($(el).val() === '') {
			$(el).addClass('required');
			
			$('.error_messages span').html('Preencha os campos obrigatórios!<br/><br/>');
			unfilled.push(item);
		    } else {
			if ($(el).hasClass('required')) {
			    $(el).removeClass('required');
			}
		    }
		} 
	    }
	    
	    return unfilled;
	}
	// envio de formulario
	$('.enviar').click(function(e) {
	    e.preventDefault();
	    
	    var errors = validaForm(fieldsDefinition);
	    if (errors.length == 0) {
		document.getElementById('inscricoes').submit();
	    } else {
		window.location.href = '#topo';
	    }
	});
    });
})(jQuery);
