<?php get_header(); ?>

    <div class="box-intro soft-scroll">
        <div class="container">
            <div class="row">

                    <?php
                       $the_query = new WP_Query(array(
                        'category_name' => 'painel-home',
                        'posts_per_page' => 1
                        ));
                       while ( $the_query->have_posts() ) :
                       $the_query->the_post();
                    ?>

                <div class="col-md-12">
                    <div class="txt-intro">
                        <?php the_field('frase_painel'); ?>
                    </div>
                    <!--/.txt-intro-->
                </div>



                <div class="col-md-4 col-xs-12">
                    <div class="number-intro">
                        <a href="<?php the_field('link_1_painel'); ?>"><span><i class="fa <?php the_field('icone_1_painel'); ?>" aria-hidden="true"></i></span></a>
                    </div>
                    <div class="info-intro">
                        <h2><a href="<?php the_field('link_1_painel'); ?>"><?php the_field('titulo_1_painel'); ?></a></h2>
                        <p>
                            <a href="<?php the_field('link_1_painel'); ?>">
                                <?php the_field('chamada_1_painel'); ?>
                            </a>
                        </p>
                        <p class="btn-home-intro"><a href="#" data-toggle="modal" data-target="#modal-intro-1">+ Mais detalhes</a></p>
                    </div>
                </div>

                <div class="col-md-4 col-xs-12">
                    <div class="number-intro">
                        <a href="<?php the_field('link_2_painel'); ?>"><span><i class="fa <?php the_field('icone_2_painel'); ?>" aria-hidden="true"></i></span></a>
                    </div>
                    <div class="info-intro">
                        <h2><a href="<?php the_field('link_2_painel'); ?>"><?php the_field('titulo_2_painel'); ?></a></h2>
                        <p>
                            <a href="<?php the_field('link_2_painel'); ?>">
                                <?php the_field('chamada_2_painel'); ?>
                            </a>
                        </p>
                        <p class="btn-home-intro"><a href="#" data-toggle="modal" data-target="#modal-intro-2">+ Mais detalhes</a></p>
                    </div>
                </div>

                <div class="col-md-4 col-xs-12">
                    <div class="number-intro">
                        <a href="<?php the_field('link_3_painel'); ?>"><span><i class="fa <?php the_field('icone_3_painel'); ?>" aria-hidden="true"></i></span></a>
                    </div>
                    <div class="info-intro">
                        <h2><a href="<?php the_field('link_3_painel'); ?>"><?php the_field('titulo_3_painel'); ?></a></h2>
                        <p>
                            <a href="<?php the_field('link_3_painel'); ?>">
                                <?php the_field('chamada_3_painel'); ?>
                            </a>
                        </p>
                        <p class="btn-home-intro"><a href="#" data-toggle="modal" data-target="#modal-intro-3">+ Mais detalhes</a></p>
                    </div>
                </div>




                <!-- Modal 1 -->
                <div class="modal fade" id="modal-intro-1" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

                  <div class="modal-dialog" role="document">

                    <div class="modal-content">

                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel"><?php the_field('titulo_1_painel'); ?></h4>
                      </div>

                      <div class="modal-body">

                        <?php the_field('texto_1_painel'); ?>

                      </div> <!-- /.modal-body -->

                  </div> <!-- /.modal-content -->

                </div> <!-- modal-dialog -->

              </div> <!-- /.modal 1 -->


                <!-- Modal 2 -->
                <div class="modal fade" id="modal-intro-2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

                  <div class="modal-dialog" role="document">

                    <div class="modal-content">

                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel"><?php the_field('titulo_2_painel'); ?></h4>
                      </div>

                      <div class="modal-body">

                        <?php the_field('texto_2_painel'); ?>

                      </div> <!-- /.modal-body -->

                  </div> <!-- /.modal-content -->

                </div> <!-- modal-dialog -->

              </div> <!-- /.modal 2 -->



            <!-- Modal 3 -->
              <div class="modal fade" id="modal-intro-3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">

                  <div class="modal-dialog" role="document">

                    <div class="modal-content">

                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="myModalLabel"><?php the_field('titulo_3_painel'); ?></h4>
                      </div>

                      <div class="modal-body">

                        <?php the_field('texto_3_painel'); ?>

                      </div> <!-- /.modal-body -->

                  </div> <!-- /.modal-content -->

                </div> <!-- modal-dialog -->

              </div> <!-- /.modal 3 -->

                <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>

            </div>
        </div>

    </div>

    <div class="featured-box">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="title" id="topoconversa">Acontecendo agora</h3>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">

                    <?php
                       $the_query = new WP_Query(array(
                        'category_name' => 'acontecendo-agora',
                        'posts_per_page' => 1
                        ));
                       while ( $the_query->have_posts() ) :
                       $the_query->the_post();
                    ?>

                        <div class="featured-content">
                            <div class="carousel slide" data-ride="carousel" id="quote-carousel">
                                <!-- Bottom Carousel Indicators -->
                                <ol class="carousel-indicators">
                                    <li data-target="#quote-carousel" data-slide-to="0" class="active"></li>
                                    <!--
                                    <li data-target="#quote-carousel" data-slide-to="1"></li>
                                    <li data-target="#quote-carousel" data-slide-to="2"></li>
                                    <li data-target="#quote-carousel" data-slide-to="3"></li>
                                    <li data-target="#quote-carousel" data-slide-to="4"></li>
                                    <li data-target="#quote-carousel" data-slide-to="5"></li>
                                    <li data-target="#quote-carousel" data-slide-to="6"></li>
                                    -->
                                </ol>

                                <!-- Carousel Slides / Quotes -->
                                <div class="carousel-inner">

                                    <!-- Quote 1 -->
                                    <div class="item active">
                                        <blockquote>
                                            <div class="row">
                                                <div class="col-sm-12 col-xs-12">
                                                    <img src="<?php the_field('imagem_slide_1'); ?>" class="img-responsive">
                                                </div>
                                                <div class="col-sm-12 col-xs-12 text-center">
                                                    <p>
                                                        <?php the_field('frase_slide_1'); ?>
                                                    </p>
                                                    <span class="<?php the_field('fonte_da_informacao_slide_1'); ?>">
                                                        <a href="<?php the_field('link_slide_1'); ?>" role="presentation" target="_blank">
                                                        <?php the_field('link_slide_1'); ?></a>
                                                    </span>
                                                </div>
                                            </div>
                                        </blockquote>
                                    </div>

                                    <!-- Quote 2 -->
                                    <!--
                                    <div class="item">
                                        <blockquote>
                                            <div class="row">
                                                <div class="col-sm-12 col-xs-12">
                                                    <img src="<?php the_field('imagem_slide_2'); ?>" class="img-responsive">
                                                </div>
                                                <div class="col-sm-12 col-xs-12 text-center">
                                                    <p>
                                                        <?php the_field('frase_slide_2'); ?>
                                                    </p>
                                                    <span class="<?php the_field('fonte_da_informacao_slide_2'); ?>">
                                                        <a href="<?php the_field('link_slide_2'); ?>" role="presentation" target="_blank">
                                                            <?php the_field('link_slide_2'); ?>
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>
                                        </blockquote>
                                    </div>
                                    -->

                                    <!-- Quote 3 -->
                                    <!--
                                    <div class="item">
                                        <blockquote>
                                            <div class="row">
                                                <div class="col-sm-12 col-xs-12">
                                                    <img src="<?php the_field('imagem_slide_3'); ?>" class="img-responsive">
                                                </div>
                                                <div class="col-sm-12 col-xs-12 text-center">
                                                    <p>
                                                        <?php the_field('frase_slide_3'); ?>
                                                    </p>
                                                    <span class="<?php the_field('fonte_da_informacao_slide_3'); ?>">
                                                        <a href="<?php the_field('link_slide_3'); ?>" role="presentation" target="_blank">
                                                            <?php the_field('link_slide_3'); ?>
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>
                                        </blockquote>
                                    </div>
                                    -->

                                    <!-- Quote 4 -->
                                    <!--
                                    <div class="item">
                                        <blockquote>
                                            <div class="row">
                                                <div class="col-sm-12 col-xs-12">
                                                    <img src="<?php the_field('imagem_slide_4'); ?>" class="img-responsive">
                                                </div>
                                                <div class="col-sm-12 col-xs-12 text-center">
                                                    <p>
                                                        <?php the_field('frase_slide_4'); ?>
                                                    </p>
                                                    <span class="<?php the_field('fonte_da_informacao_slide_4'); ?>">
                                                        <a href="<?php the_field('link_slide_4'); ?>" role="presentation" target="_blank">
                                                            <?php the_field('link_slide_4'); ?>
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>
                                        </blockquote>
                                    </div>
                                    -->

                                    <!-- Quote 5 -->
                                    <!--
                                    <div class="item">
                                        <blockquote>
                                            <div class="row">
                                                <div class="col-sm-12 col-xs-12">
                                                    <img src="<?php the_field('imagem_slide_5'); ?>" class="img-responsive">
                                                </div>
                                                <div class="col-sm-12 col-xs-12 text-center">
                                                    <p>
                                                        <?php the_field('frase_slide_5'); ?>
                                                    </p>
                                                    <span class="<?php the_field('fonte_da_informacao_slide_5'); ?>">
                                                        <a href="<?php the_field('link_slide_5'); ?>" role="presentation" target="_blank">
                                                            <?php the_field('link_slide_5'); ?>
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>
                                        </blockquote>
                                    </div>
                                    -->

                                     <!-- Quote 6 -->
                                     <!--
                                    <div class="item">
                                        <blockquote>
                                            <div class="row">
                                                <div class="col-sm-12 col-xs-12">
                                                    <img src="<?php the_field('imagem_slide_6'); ?>" class="img-responsive">
                                                </div>
                                                <div class="col-sm-12 col-xs-12 text-center">
                                                    <p>
                                                        <?php the_field('frase_slide_6'); ?>
                                                    </p>
                                                    <span class="<?php the_field('fonte_da_informacao_slide_6'); ?>">
                                                        <a href="<?php the_field('link_slide_6'); ?>" role="presentation" target="_blank">
                                                        <?php the_field('link_slide_6'); ?></a>
                                                    </span>
                                                </div>
                                            </div>
                                        </blockquote>
                                    </div>
                                    -->

                                    <!-- Quote 7 -->
                                    <!--
                                    <div class="item">
                                        <blockquote>
                                            <div class="row">
                                                <div class="col-sm-12 col-xs-12">
                                                    <img src="<?php the_field('imagem_slide_7'); ?>" class="img-responsive">
                                                </div>
                                                <div class="col-sm-12 col-xs-12 text-center">
                                                    <p>
                                                        <?php the_field('frase_slide_7'); ?>
                                                    </p>
                                                    <span class="<?php the_field('fonte_da_informacao_slide_7'); ?>">
                                                        <a href="<?php the_field('link_slide_7'); ?>" role="presentation" target="_blank">
                                                            <?php the_field('link_slide_7'); ?>
                                                        </a>
                                                    </span>
                                                </div>
                                            </div>
                                        </blockquote>
                                    </div>
                                    -->

                                </div>

                                <!-- Carousel Buttons Next/Prev -->
                                <!--<a data-slide="prev" href="#quote-carousel" class="left carousel-control"><i class="fa fa-chevron-left"></i></a>
                                <a data-slide="next" href="#quote-carousel" class="right carousel-control"><i class="fa fa-chevron-right"></i></a>-->
                            </div>

                            <!-- Conversa section -->
                            <section class="section conversa" id="conversa">

                                <div class="row">
                                    <div class="col-md-12 board">
                                        <div class="board-inner">
                                            <ul class="nav nav-tabs" id="myTab">
                                                <div class="liner"></div>
                                                <li class="active">
                                                    <a href="#escuta" aria-controls="escuta" role="tab" data-toggle="tab" title="Opine">
                                                        <span class="round-tabs one">
                              <span>1</span>
                                                        <h3 class="head text-center">Opine</h3>
                                                        </span>
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="#dialogo" aria-controls="dialogo" role="tab" data-toggle="tab" title="Diálogo">
                                                        <span class="round-tabs two">
                                                       <span>2</span>
                                                        <h3 class="head text-center">Diálogo</h3>
                                                        </span>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a href="#resposta" aria-controls="resposta" role="tab" data-toggle="tab" title="Resposta">
                                                        <span class="round-tabs three">
                        <span>3</span>
                                                        <h3 class="head text-center">Resposta</h3>
                                                        </span>
                                                    </a>
                                                </li>

                                            </ul>
                                        </div>

                                        <div class="tab-content">
                                            <div class="tab-pane fade in active" id="escuta">
                                                <?php the_field('campo_opine'); ?>

                                            </div>
                                            <div class="tab-pane fade" id="dialogo">
                                                <?php the_field('campo_dialogo'); ?>

                                            </div>
                                            <div class="tab-pane fade" id="resposta">
                                                <?php the_field('campo_resposta'); ?>

                                            </div>

                                            <div class="clearfix"></div>
                                        </div>

                                    </div>
                                </div>


                            </section>
                            <!-- /conversa -->

                            <span class="share-item"><span>Divulgue!</span> <?php echo do_shortcode('[Sassy_Social_Share style=""]') ?></span>

                        </div>

                </div>
                <!-- /.content-featured-->

                 <?php
                    endwhile;

                    ?>

            </div>
            <!-- /.row-->
        </div>
        <!-- /.container-->

    </div>
    <!-- /.featured-box -->


                <?php $orig_post = $post;
                global $post;
                $categories = get_the_category($post->ID);
                if ($categories) {
                $category_ids = array();
                foreach($categories as $individual_category) $category_ids[] = $individual_category->term_id;
                $args=array(
                'category__in' => $category_ids,
                'post__not_in' => array($post->ID),
                'posts_per_page'=> 4, // Number of related posts that will be shown.
                'caller_get_posts'=>1
                );
                $my_query = new wp_query( $args );
                if( $my_query->have_posts() ) {
                echo '
                    <div class="box-more">

                        <!-- start:container -->
                        <div class="container">

                        <!-- start:row -->
                          <div class="row">

                            <div class="col-md-12">
                                <h3 class="title">Outras conversas</h3>
                            </div>';
                while( $my_query->have_posts() ) {
                $my_query->the_post();?>

                        <div class="col-md-3 col-sm-3 col-xs-12 ">

                            <div class="info">
                                <a href="<?php the_permalink() ?>"><?php the_post_thumbnail('full', array('class' => 'img-responsive')); ?></a>
                                    <div class="title">
                                        <h4>
                                <a href="<?php the_permalink() ?>">
                                  <?php the_title() ?>
                                </a>
                              </h4>
                                    </div>
                            </div>


                         </div>

                            <?
                }
                echo '</div>
                    <!-- end:row -->
                     <div class="row">
                        <div class="col-md-12">
                            <a href="' . get_site_url() . '/category/conversas"><i class="fa fa-plus-circle" aria-hidden="true"></i> Mais conversas</a>
                        </div>
                    </div>
                </div>
                <!-- end:container -->
            </div>
            <!-- end:box-more -->
                ';
                }
                }
                $post = $orig_post;
                wp_reset_query(); ?>


    <?php get_footer(); ?>
