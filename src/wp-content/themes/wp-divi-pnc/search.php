<?php get_header(); ?>

    <div id="main-content">
        <div class="container">
            <div id="content-area" class="clearfix">
                <div class="search-heading">
                    <h1>Busca no portal</h1>
                    <?php get_search_form(); ?>
                    <ul>
                        <li <?php echo ( empty($_GET['cat']) ) ? 'class="active"' : ''; ?>><a href="<?php echo home_url('?s=' . get_search_query() ); ?>">Todas</a></li>
                        <li <?php echo ( $_GET['cat'] == 'metas' ) ? 'class="active"' : ''; ?>><a href="<?php echo home_url('?s=' . get_search_query() . '&cat=metas'); ?>">Metas</a></li>
                        <li <?php echo ( $_GET['cat'] == 'noticias' ) ? 'class="active"' : ''; ?>><a href="<?php echo home_url('?s=' . get_search_query() . '&cat=noticias' ); ?>">Notícias</a></li>
                    </ul>
                </div>
                <?php
                $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
                $args = array(
                    's' => get_search_query(),
                    'posts_per_page' => 20,
                    'paged' => $paged,
                    'category_name' => ( !empty($_GET['cat']) ) ? $_GET['cat'] : ''
                );
                $search = new WP_Query( $args );

                if ( $search->have_posts() ) :
                	while ( $search->have_posts() ) : $search->the_post(); ?>
                		<article id="post-<?php the_ID(); ?>" class="searched-post">
                            <?php the_title('<h2><a href="'. get_permalink() .'">', '</a></h2>'); ?>
                            <?php the_excerpt(); ?>
                        </article>
                	<?php endwhile; wp_reset_postdata();

                    if($search->max_num_pages > 1): ?>
                        <nav class="text-center">
                            <ul class="pagination">
                                <?php
                                $search_str = '/?s=' . get_search_query();
                                $search_str = ( !empty($_GET['cat']) ) ? $search_str . '&cat=' . $_GET['cat'] : $search_str;
                                if( $paged <= 1 ):?>
                                    <li class="disabled"><a href="#" aria-label="Previous"><span aria-hidden="true">«</span></a></li>
                                <?php else: ?>
                                    <li><a href="<?php echo home_url('/page/' . ($paged - 1) . $search_str); ?>" aria-label="Previous"><span aria-hidden="true">«</span></a></li>
                                <?php endif;

                                for($i = 1; $i <= $search->max_num_pages; $i++ ): ?>
                                    <li class="<?php echo ( $paged == $i) ? 'active' : ''; ?>"><a href="<?php echo home_url('/page/' . $i . $search_str) ; ?>"><?php echo $i;?></a></li>
                                <?php endfor;

                                if( $paged != $search->max_num_pages ):?>
                                    <li><a href="<?php echo home_url('/page/' . ($paged + 1) . $search_str); ?>" aria-label="Next"><span aria-hidden="true">»</span></a></li>
                                <?php else: ?>
                                    <li class="disabled"><a href="#" aria-label="Next"><span aria-hidden="true">»</span></a></li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                        <?php endif;

                else:
                	get_template_part('includes/no-results', 'index');
                endif; ?>
                
            </div>
        </div>
    </div>

<?php get_footer(); ?>