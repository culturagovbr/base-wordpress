<!-- contact -->
<section class="contact dark" data-section="contact" <?php echo $args['styles']['section']; ?>>
    <!-- contact__wrap -->
    <div class="contact__wrap">
        <div class="container">
            <header class="row">
                <h2 class="site__title" <?php echo $args['styles']['title']; ?>>
                    <?php echo stripslashes($args['title']); ?>
                    <span <?php echo $args['styles']['subtitle']; ?>><?php echo stripslashes($args['subtitle']); ?></span>
                </h2>
            </header>
            <!-- contact__feedback -->
            <form class="contact__feedback row">
                <fieldset>
                    <div class="text-field text-field_2">
                        <i class="fa fa-user"></i>
                        <input type="text" name="contactName" placeholder="<?php _e('Full Name *', 'khore'); ?>" <?php echo $args['styles']['field']; ?> required />
                    </div>
                    <div class="text-field text-field_2">
                        <i class="fa fa-envelope-o"></i>
                        <input type="text" name="email" placeholder="<?php _e('Email Address', 'khore'); ?>" <?php echo $args['styles']['field']; ?> required />
                    </div>
                </fieldset>
                <div class="text-area text-area_2">
                    <i class="fa fa-comment-o"></i>
                    <textarea placeholder="<?php _e('Message (Maximum 500 characters)', 'khore'); ?>" name="comments" <?php echo $args['styles']['field']; ?>
                              required></textarea>
                </div>
                <div class="col-sm-6 col-xs-12 captcha">
                    <?php if (!empty($args['ef_options']['efcb_contacts_recaptcha_public_key']) && !empty($args['ef_options']['efcb_contacts_recaptcha_private_key'])) { ?>
                        <div id="recaptcha_widget" >
                            <div id="recaptcha_image" class='captcha-image pull-left'></div>
                            <div><a href="javascript:Recaptcha.reload()" class="refresh pull-right"><?php _e('Refresh Captcha Code', 'khore'); ?> <i class='fa fa-refresh'></i></a></div>
                        </div>
                        <noscript>
                            <iframe src="http://www.google.com/recaptcha/api/noscript?k=<?php echo $args['ef_options']['efcb_contacts_recaptcha_public_key']; ?>" height="300" width="500"></iframe><br>
                            <textarea name="recaptcha_challenge_field" rows="3" cols="40"></textarea>
                            <input type="hidden" name="recaptcha_response_field" value="manual_challenge">
                        </noscript>
                    <?php } ?>
                </div>
                <div class="col-sm-6 col-xs-12 contact__feedback-submit">
                    <input type="hidden" name="action" value="send_contact_email" />
                    <button type="submit" <?php echo $args['styles']['send_button']; ?>><?php echo !empty($args['sendtext']) ? $args['sendtext'] : __('Send', 'khore'); ?></button>
                </div>
            </form>
            <!-- /contact__feedback -->
        </div>
    </div>
    <!-- /contact__wrap -->
</section>
<!-- /contact -->