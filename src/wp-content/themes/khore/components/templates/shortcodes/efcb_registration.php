<!-- registration -->
<section class="registration" id="registration" data-section="registration" <?php echo $args['styles']['section']; ?>>
    <!-- vertical-center -->
    <div class="vertical-center">
        <div>
            <div>
                <h2 class="site__title" <?php echo $args['styles']['title']; ?>>
                    <?php echo stripslashes($args['title']); ?>
                    <span <?php echo $args['styles']['subtitle']; ?>><?php echo stripslashes($args['subtitle']); ?></span>
                </h2>
                <!-- /sponsors__subtitle -->
                <!-- registration__wrap -->
                <div class="registration__wrap">
                    <?php echo stripslashes(do_shortcode($args['embed_code'])); ?>
                </div>
                <!-- /registration__wrap -->
                <p <?php echo $args['styles']['text']; ?>><?php echo stripslashes($args['text']); ?></p>
            </div>
        </div>
    </div>
    <!-- /vertical-center -->
</section>
<!-- /registration -->