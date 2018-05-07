(function ($) {
    $(document).ready(function () {
        app.init();
    });

    var app = {
        init: function () {
            this.initPlugin();
        },

        initPlugin: function () {
            if( $('#busca-epracas').length ){

                $('body').on('click', function () {
                    $('.epracas-list').remove();
                });

                $('body').on('click', '.busca-epracas .icone', function () {
                    $('.epracas-list').remove();
                    $('#busca-epracas').val('');
                    $('.busca-epracas .icone .fa-close').addClass('hidden');
                    $('.busca-epracas .icone .fa-search').removeClass('hidden');
                });

                $('#busca-epracas').on('keyup click', function(){
                    var busca = $('#busca-epracas').val();

                    if( busca.length ){
                        $('.busca-epracas .icone .fa-close').removeClass('hidden');
                        $('.busca-epracas .icone .fa-search').addClass('hidden');
                    } else {
                        $('.busca-epracas .icone .fa-close').addClass('hidden');
                        $('.busca-epracas .icone .fa-search').removeClass('hidden');
                    }

                    if( busca.length < 2 ){
                        $('.epracas-list').remove();
                        return false;
                    } else {

                        var url = 'https://epracas.cultura.gov.br/api/v1/pracas/?search=' + busca;
                        $.ajax({
                            url: url,
                            cache: true,
                            beforeSend: function() {
                                $('.epracas-list').remove();
                                var list  = '<ul class="epracas-list">';
                                list += '<li>Pesquisando...</li>';
                                list += '</ul>';
                                $('.modulo-epracas .busca-epracas').append(list);
                            },
                            error: function(error) {
                                console.error('Error :' + error);
                                $('.epracas-list').remove();
                                var list  = '<ul class="epracas-list">';
                                list += '<li>Houve um erro durante a busca.</li>';
                                list += '</ul>';
                                $('.modulo-epracas .busca-epracas').append(list);
                            },
                            success: function(listObj) {

                                $('.epracas-list').remove();
                                var list  = '<ul class="epracas-list">';
                                if( listObj.length ){
                                    $.each(listObj,function(i, epraca){
                                        list += '<li>';
                                        list += '<a href="https://epracas.cultura.gov.br/pracas/'+ epraca.id_pub +'" target="_blank">';
                                        list += '<h3>'+ epraca.nome +'</h3>';
                                        list += '<h4>'+ epraca.municipio +' - '+ epraca.uf +'</h4>';
                                        list += '<h4>Modelo: '+ epraca.modelo_descricao +'</h4>';
                                        list += '<h4>Situação: '+ epraca.situacao_descricao +'</h4>';
                                        list += '</a>';
                                        list += '</li>';
                                    });
                                } else {
                                    list += '<li>Nada encontrado.</li>';
                                }

                                list += '</ul>';
                                $('.modulo-epracas .busca-epracas').append(list);

                            }
                        });
                    }

                });
            }
        }
    };
})(jQuery);