<?php get_header(); ?>

    <div class="box-area">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="title-internal">
                        <p><a href="<?php echo get_site_url(); ?>/category/noticias"><span>«</span> Notícias</a></p>
                    </div>
                    <!--/.title-internal-->
                </div>

            </div>
        </div>
    </div>

    <div class="content">
        <div class="featured-box">
            <div class="container">

                <div class="row">
                    <div class="col-md-12">

                        <?php
                            while (have_posts()) {
                              the_post();
                            ?>

                            <h1><?php the_title() ?></h1>

                            <div class="row">
                                <div class="col-md-12">

                                    <?php the_content() ?>

                                </div>
                                <!-- /.col-md-12-->

                            </div>
                            <!-- /.row-->
                    </div>
                    <!-- /.container-->

                    <?php } ?>

                </div>
                <!-- /.row-->
            </div>
            <!-- /.container-->

        </div>
        <!-- /.featured-box -->

        <div class="box-more">

            <!-- start:container -->
            <div class="container">
                <!-- start:row -->

                <div class="row">

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
                echo '<div class="col-md-12">
                        <h3 class="title">Outras notícias</h3>
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
                echo '';
                }
                }
                $post = $orig_post;
                wp_reset_query(); ?>

                        </div>
                        <!-- end:row -->

                </div>
                <!-- end:container -->

            </div>
            <!-- end:box-more -->

        </div>
        <!--/.content-->

        <?php get_footer(); ?>