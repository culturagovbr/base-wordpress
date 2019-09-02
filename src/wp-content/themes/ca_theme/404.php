<?php get_header(); ?>

    <!--::::::: CONTENT AREA :::::::-->

    <div>

        <div class="box-area">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="title-internal">
                            <p>
                                Erro 404
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

                    <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

                        <h1><?php the_title(); ?></h1>

                        <p>
                            <?php the_content(); ?>
                        </p>

                        <?php endwhile; else: ?>
                            <h1>Pedimos desculpas. Esse conteúdo não foi encontrado.</h1>
                            <p>Voltar para <a href="<?php echo esc_url( home_url( '/' ) ); ?>">Página Inicial</a>.</p>
                            <?php endif; ?>

                </div>
            </div>

        </div>
        <!-- /container -->

        <?php get_footer(); ?>