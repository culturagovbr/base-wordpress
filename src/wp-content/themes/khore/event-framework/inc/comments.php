<?php
// ******************* Custom Comments ****************** //

function ef_comments( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	global $post;
	?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
        <div id="comment-<?php comment_ID(); ?>">
            <?php if ('0' == $comment->comment_approved) : ?>
                <p class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'dxef'); ?></p>
            <?php endif; ?>

            <div class="comment-content">
                <?php comment_text(); ?>
                <?php edit_comment_link(__('Edit', 'dxef'), '<p class="edit-link">', '</p>'); ?>
            </div><!-- .comment-content -->

            <div class="comment-meta comment-author vcard">
                <?php
                printf('<cite><b class="fn">%1$s</b></cite>', get_comment_author_link()
                );
                printf('<a href="%1$s"><time datetime="%2$s">%3$s</time></a>', esc_url(get_comment_link($comment->comment_ID)), get_comment_time('c'),
                        /* translators: 1: date, 2: time */ sprintf(__('%1$s at %2$s'), get_comment_date(), get_comment_time())
                );
                ?>
            </div><!-- .comment-meta -->

        </div><!-- #comment-## -->
    <?php
}