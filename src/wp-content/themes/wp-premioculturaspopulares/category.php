<?php get_header(); ?>

    <div id="main-content">
        <div class="container">
            <div id="content-area" class="clearfix">
                <h1 class="page-title text-center">
                    <?php echo get_cat_name(get_query_var('cat')); ?>
                </h1>

                <?php $paged = (get_query_var('paged')) ? get_query_var('paged') : 1; ?>

                <?php if (have_posts()) : ?>
                    <ul id="posts-list">

                        <?php while (have_posts()) : ?>
                            <?php the_post(); ?>

                            <li id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                                <div class="categories">
                                    <?php
                                        $categories = get_the_category();
                                        $i = 1;
                                        $exc = array('destaque', 'destaquinho-1', 'destaquinho-2', 'destaquinho-3');

                                        foreach($categories as $cd){
                                            if (!in_array($cd->slug, $exc)) {
                                                echo ($i==1) ? '' : ', ';
                                                echo '<a href="'. get_category_link( $cd->term_id ) .'">' . $cd->cat_name . '</a>';
                                                $i++;
                                            }
                                        }
                                    ?>
                                </div>

                                <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
                                <p><?php echo idg_excerpt(); ?></p>

                                <?php if (get_the_tags()) : ?>
                                    <div class="tags-list">
                                        <?php the_tags('<span>tags:</span>', ''); ?>
                                    </div>
                                <?php endif; ?>

                                <span class="details">
                          por Ascom
                                    última modificação em <?php the_modified_date('d/m/Y'); ?> <?php the_modified_time('H'); ?>
                                    h<?php the_modified_time('i'); ?>
                        </span>
                            </li>

                        <?php endwhile; ?>

                        <?php if ( function_exists('wp_bootstrap_pagination') ){
                            wp_bootstrap_pagination();
                        }; ?>


                    </ul>

                <?php else : ?>

                    <?php get_template_part('template-parts/content', 'none'); ?>

                <?php endif; ?>
            </div>
        </div>
    </div>

<?php get_footer(); ?>