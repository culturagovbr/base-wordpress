<?php
if (post_password_required())
    return;
?>
<!-- comments -->
<div class="comments content container">
    <div class="row">
        <div class="col-xs-12">
            <?php
            $fields = array(
                'author' =>
                '<div class="text-field text-field_2">
                    <i class="fa fa-user"></i>
                    <input type="text" name="author" placeholder="' . __('Name *', 'khore') . '" value="' . esc_attr($commenter['comment_author']) . '" required>
                </div>',
                'email' =>
                '<div class="text-field text-field_2">
                    <i class="fa fa-envelope-o fa-fw"></i>
                    <input type="text" name="email" placeholder="' . __('Last name *', 'khore') . '" value="' . esc_attr($commenter['comment_author_email']) . '" required>
                </div>',
                'url' =>
                '<div class="text-field text-field_2">
                    <i class="fa fa-desktop"></i>
                    <input type="url" name="url" placeholder="' . __('Website *', 'khore') . '" value="' . esc_attr($commenter['comment_author_url']) . '" required>
                </div>',
            );
            $comments_args = array(
                'label_submit' => 'submit',
                'title_reply' => __('SUBMIT A COMMENT', 'khore'),
                'comment_notes_before' => '<p>' . __('Email address not published. Required fields are marked *', 'khore') . '</p>',
                'class_submit' => 'btn btn_2',
                'comment_notes_after' => '',
                'comment_field' => '<fieldset class="col-xs-12 col-md-7">
                        <div class="text-area text-area_2">
                        <i class="fa fa-comment-o"></i>
                        <textarea required name="comment" placeholder="' . __('Comment', 'khore') . '"></textarea>
                        </div>
                        </fieldset>',
                'fields' => apply_filters('comment_form_default_fields', $fields)
            );
            comment_form($comments_args);
            ?>
            <?php if (have_comments()) : ?>
                <h2 class="comments-title">
                    <?php
                    printf(__('Comments on %s', 'khore'), '<span>' . get_the_title() . '</span>');
                    ?>
                </h2>
                <?php
                wp_list_comments(array(
                    'avatar_size' => 60,
                    'callback' => 'khore_comment_callback'
                ));
                ?>
                <?php
                // Are there comments to navigate through?
                if (get_comment_pages_count() > 1 && get_option('page_comments')) :
                    ?>
                    <nav class="navigation comment-navigation" role="navigation">
                        <h1 class="screen-reader-text section-heading"><?php _e('Comment navigation', 'khore'); ?></h1>
                        <div class="nav-previous"><?php previous_comments_link(__('&larr; Older Comments', 'khore')); ?></div>
                        <div class="nav-next"><?php next_comments_link(__('Newer Comments &rarr;', 'khore')); ?></div>
                    </nav><!-- .comment-navigation -->
                <?php endif; // Check for comment navigation  ?>
                <?php if (!comments_open() && get_comments_number()) : ?>
                    <p class="no-comments"><?php _e('Comments are closed.', 'khore'); ?></p>
                <?php endif; ?>
            <?php endif; // have_comments() ?>
        </div>
    </div>
</div>
<!-- /comments -->