<?php get_header(); ?>

    <div id="main-content">
        <div id="content-area" class="clearfix">
            <?php
            if (have_posts()) : ?>
                <header class="page-header">
                    <h1 class="page-title"><?php the_title(); ?></h1>
                    <?php et_divi_post_meta(); ?>
                </header>

                <?php while (have_posts()) : the_post(); ?>

                    <article id="post-<?php the_ID(); ?>" <?php post_class('et_pb_post'); ?>>

                        <?php
                        the_content();
                        if ( ( comments_open() || get_comments_number() ) && 'on' == et_get_option( 'divi_show_postcomments', 'on' ) && ! $et_pb_has_comments_module ) {
                            comments_template( '', true );
                        }
                        ?>

                    </article>
                    <?php
                endwhile;
            endif;
            ?>
        </div>
    </div>

<?php get_footer(); ?>