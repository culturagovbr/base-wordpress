<?php get_header(); ?>

    <div id="main-content">
        <div id="content-area" class="clearfix">
            <?php
            if (have_posts()) : ?>

                <header class="page-header">
                    <h1 class="page-title"><?php printf( esc_html__( 'Resultado da busca por: %s', 'alerta-social' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
                </header><!-- .page-header -->

                <?php
                /* Start the Loop */
                while ( have_posts() ) : the_post(); ?>

                    <article id="post-<?php the_ID(); ?>" <?php post_class('et_pb_post'); ?>>

                        <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                        <p><?php the_excerpt(); ?></p>

                    </article>

                <?php endwhile;

                the_posts_navigation();


            else :
                get_template_part('includes/no-results', 'index');
            endif;
            ?>
        </div>
    </div>

<?php get_footer(); ?>