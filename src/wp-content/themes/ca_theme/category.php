<?php get_header(); ?>

    <!--::::::: CONTENT AREA :::::::-->

    <div>

        <div class="box-area">
            <div class="container">
                <div class="row">

                    <div class="col-md-12">
                        <div class="title-internal">
                            <p>
                                <?php the_category(', ') ?>
                            </p>
                        </div>
                        <!--/.title-internal-->
                    </div>

                </div>
            </div>
        </div>

        <div class="content">

            <div class="box-more">

                <!-- start:container -->
                <div class="container">
                    <!-- start:row -->
                    <div class="row">

                        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

                            <div class="col-md-4 col-sm-4 col-xs-12 ">

                                <div class="info">
                                   <a href="<?php the_permalink() ?>"> <?php the_post_thumbnail('full', array('class' => 'img-responsive')); ?></a>
                                        <div class="title">
                                            <h4>
                                <a href="<?php the_permalink() ?>">
                                  <?php the_title() ?>
                                </a>
                              </h4>
                                        </div>
                                </div>

                            </div>

                            <?php endwhile; else: ?>
                                <h2>Nada Encontrado</h2>
                                <p>Erro 404</p>
                                <?php endif; ?>

                    </div>
                    <!-- end:row -->

                   <!-- <div class="col-md-12">
                        <?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } ?>
                    </div>-->
                </div>
                <!-- end:container -->

            </div>
            <!-- end:box-more -->

        </div>
        <!-- end:content -->

    </div>
    <!-- /.internal -->

    <?php get_footer(); ?>