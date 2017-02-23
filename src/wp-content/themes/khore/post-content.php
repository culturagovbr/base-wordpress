<?php
global $withcomments;
$withcomments = true;
$date_format = get_option('date_format');
$categories = get_the_category();
?>
<!-- news__layout -->
<div class="news__layout">
    <!-- content -->
    <article class="content container">
        <!-- content__wrap -->
        <div class="content__wrap row">
            <!-- content__layout -->
            <div class="content__layout col-xs-12">
                <h1>
                    <?php the_title(); ?>
                    <span><time><?php the_time($date_format); ?></time></span>
                </h1>
                <!-- spacer -->
                <div class="spacer">
                    <i class="fa fa-bookmark-o"></i>
                </div>
                <!-- spacer -->
                <!-- tags -->
                <div class="tags">
                    <?php foreach ($categories as $category) { ?>
                        <div><a href="<?php echo get_category_link($category->term_id); ?>"><?php echo $category->name; ?></a></div>    
                    <?php } ?>
                </div>
                <!-- /tags -->
            </div>
            <!-- /content__layout -->
            <!-- content__layout -->
            <div class="content__layout col-xs-12">
                <?php the_content(); ?>
            </div>
            <!-- content__layout -->
            <!-- news__footer -->
            <footer class="news__footer col-xs-12">
                <!-- news__author -->
                <!--<cite class="news__author"><i class="fa fa-pencil"></i><?php the_author(); ?></cite>-->
                <!-- /news__author -->
                <!-- tags -->
                <div class="tags tags__small">
                    <i class="fa fa-bookmark-o"></i>
                    <?php foreach ($categories as $category) { ?>
                        <div><a href="<?php echo get_category_link($category->term_id); ?>"><?php echo $category->name; ?></a></div>    
                    <?php } ?>
                </div>
                <!-- /tags -->
                <!-- news__comments -->
                <!--<div class="news__comments"><i class="fa fa-comment-o"></i> <?php echo get_comments_number(); ?> COMMENTS</div>-->
                <!-- /news__comments -->
                <?php if (!empty($ef_options['ef_add_this_pubid'])) { ?>
                    <!-- news__share -->
                    <div class="news__share">
                        <a class="addthis_button no-overlay" href="http://www.addthis.com/bookmark.php?v=300&amp;pubid=<?php echo $ef_options['ef_add_this_pubid']; ?>">
                            <img src="http://s7.addthis.com/static/btn/v2/lg-share-en.gif" width="125" height="16" alt="<?php _e('Bookmark and Share', 'khore'); ?>" style="border:0" class=''>
                        </a>
                    </div>
                    <!-- /news__share -->
                <?php } ?>
            </footer>
            <!-- /news__footer -->
            <!-- comments -->
            <?php comments_template(); ?>
            <!-- /comments -->
        </div>
        <!-- /content__wrap -->
    </article>
    <!-- /content -->
</div>
<!-- /news__layout -->
