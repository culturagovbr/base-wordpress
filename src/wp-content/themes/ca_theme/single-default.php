<?php get_header(); ?>

    <!--::::::: CONTENT AREA :::::::-->

    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

        <div>

            <div class="box-area">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="title-internal">
                                <p>
                                    <?php the_title() ?>
                                </p>
                            </div>
                            <!--/.title-internal-->
                        </div>

                    </div>
                </div>
            </div>

            <div class="container">
                <div class="row">
                    <div class="col-md-12">

                        <div class="content">
                            <?php the_content() ?>

                        </div>
                        <!-- /.content-->
                    </div>
                    <!-- /.col-md-12-->
                </div>
                <!-- /.row-->
            </div>
            <!-- /.container-->

        </div>
        <!-- /.internal -->

        <?php endwhile; else: ?>
            <h2>Nada Encontrado</h2>
            <p>Erro 404</p>
            <?php endif; ?>

                <?php get_footer(); ?>