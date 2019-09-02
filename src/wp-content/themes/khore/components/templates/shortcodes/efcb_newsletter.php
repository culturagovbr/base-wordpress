<!-- contact -->
<section class="contact dark" data-section="contact" <?php echo $args['styles']['section']; ?>>
    <!-- contact__wrap -->
    <div class="contact__wrap">
        <div class="container">
            <!-- contact__subscribe -->
            <form action="<?php echo $args['mailchimp_action_url']; ?>" method="POST" class="contact__subscribe row">
                <h2 class="site__title" <?php echo $args['styles']['title']; ?>>
                    <?php echo stripslashes($args['title']); ?>
                    <span <?php echo $args['styles']['subtitle']; ?>><?php echo stripslashes($args['subtitle']); ?></span>
                </h2>
                <!-- /contact__subtitle -->
                <div class="col-md-12">
                    <fieldset>
                        <input type="EMAIL" name="EMAIL" placeholder="<?php echo $args['textbox_text']; ?>" required>
                        <button type="submit" <?php echo $args['styles']['button']; ?>><?php echo $args['button_text']; ?></button>
                    </fieldset>
                </div>
            </form>
            <!-- /contact__subscribe -->
        </div>
    </div>
    <!-- /contact__wrap -->
</section>
<!-- /contact -->