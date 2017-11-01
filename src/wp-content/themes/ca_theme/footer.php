    <footer>

        <div class="logos">
            <div class="container">
                <div class="col-md-12 text-center">
                    <ul>
                        <li><a href="http://www.sdh.gov.br/sobre/participacao-social/conselho-nacional-dos-direitos-da-crianca-e-do-adolescente-conanda" target="_blank"><img src="<?php bloginfo('template_directory'); ?>/images/logo-conanda.png" class="img-responsive" /></a></li>
                        <li><a href="http://www.sdh.gov.br/noticias/2017/marco/secretaria-nacional-dos-direitos-da-crianca-e-do-adolescente-acompanha-situacao-do-sistema-socioeducativo-de-pernambuco" target="_blank"><img src="<?php bloginfo('template_directory'); ?>/images/logo-sndca.png" class="img-responsive" /></a></li>
                        <li><a href="http://www.sdh.gov.br" target="_blank"><img src="<?php bloginfo('template_directory'); ?>/images/logo-mdh.png" class="img-responsive" /></a></li>
                        <li><a href="http://www.brasil.gov.br" target="_blank"><img src="<?php bloginfo('template_directory'); ?>/images/logo-brasil.png" class="img-responsive" /></a></li>
                    </ul>
                </div>
            </div>
        </div>



        <div class="copyright">
            <div class="container">
                <div class="col-md-12">
                <span>
                    <ul class="bottom_ul text-center">
                        <li><a href="<?php echo get_site_url(); ?>/sobre-nos" role="presentation">Sobre nós</a></li>
                        <li><a href="<?php echo get_site_url(); ?>/category/conversas/" role="presentation">Conversas</a></li>
                        <li><a href="<?php echo get_site_url(); ?>/perguntas-frequentes" role="presentation">Perguntas frequentes</a></li>
                        <li><a href="<?php echo get_site_url(); ?>/termos-de-uso" role="presentation">Termos de uso</a></li>
                    </ul>
                </span>
                </div>
                <div class="col-md-12">
                    <p>Desenhado pelo Instituto Cidade Democrática e movido pelo software livre Pol.is</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Go To Top -->
    <a href="#0" class="cd-top" title="Voltar para o topo">Voltar para o topo</a>

    <script src="<?php echo get_template_directory_uri(); ?>/js/jquery.min.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/scripts.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/bootstrap.min.js"></script>
    <script src="<?php echo get_template_directory_uri(); ?>/js/touchswipe.min.js"></script>

    <script>
    $(document).ready(function() {

                    //Enable swiping...
                    $(".carousel-inner").swipe( {
                        //Generic swipe handler for all directions
                        swipeLeft:function(event, direction, distance, duration, fingerCount) {
                            $(this).parent().carousel('next');
                        },
                        swipeRight: function() {
                            $(this).parent().carousel('prev');
                        },
                        //Default is 75px, set to 0 for demo so any distance triggers swipe
                        threshold:0
                    });
                });
    </script>
<?php wp_footer(); ?>
</body>

</html>
