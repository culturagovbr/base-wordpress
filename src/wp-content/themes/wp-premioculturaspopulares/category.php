<?php get_header(); ?>

<div id="main-content">
    <div class="container">
        <div id="content-area" class="clearfix">
            <div class="wrapper-align">
                <h1 class="category-title">
                    <?php echo get_cat_name(get_query_var('cat')); ?>
                </h1>

                <?php if (have_posts()) : ?>

                    <ul class="posts-list">

                        <?php while ( have_posts() ) : the_post(); ?>

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

                                <span class="excerpt"><?php the_excerpt(); ?></span>

                            </li> <!-- .et_pb_post -->

                        <?php endwhile; ?>

                    </ul>

                <?php else : ?>

                    <p>Não há nenhum post.</p>

                <?php endif; ?>
            </div>

        </div> <!-- #content-area -->
    </div> <!-- .container -->

</div> <!-- #main-content -->

<?php get_footer(); ?>