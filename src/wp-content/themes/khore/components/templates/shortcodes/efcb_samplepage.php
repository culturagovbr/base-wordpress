<!-- samplepage -->
<section class="samplepage" data-section="samplepage" <?php echo $args['styles']['section']; ?>>
    <div class="container">
        <?php if (!empty($args['hero_image'])) { ?>
            <img src="<?php echo $args['hero_image']; ?>" width="1085" height="480" class="hero" />
        <?php } ?>
        <?php if (!empty($args['title'])) { ?>
            <!-- site__title -->
            <h2 class="site__title" <?php echo $args['styles']['title']; ?>>
                <?php echo stripslashes($args['title']); ?>
                <?php if (!empty($args['subtitle'])) { ?>
                    <span <?php echo $args['styles']['subtitle']; ?>><?php echo stripslashes($args['subtitle']); ?></span>
                <?php } ?>
            </h2>
            <!-- /site__title -->
        <?php } ?>
        <div class="row content">
            <?php echo do_shortcode($args['content']); ?>
        </div>
    </div>
</section>
<!-- /samplepage -->