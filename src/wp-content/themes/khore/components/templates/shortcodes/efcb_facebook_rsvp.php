<!-- fbook -->
<section class="fbook" data-section="fbook" <?php echo $args['styles']['section']; ?>>
    <!-- fbook__wrap -->
    <div class="fbook__wrap">
        <div class="container">
            <header class="row">
                <!-- fbook__title -->
                <h2 class="site__title" <?php echo $args['styles']['title']; ?>>
                    <?php echo stripslashes($args['title']); ?>
                    <span <?php echo $args['styles']['subtitle']; ?>><?php echo stripslashes($args['subtitle']); ?></span>
                </h2>
                <!-- /fbook__subtitle -->
            </header>
            <!-- fbook__content -->
            <div class="fbook__content row">
                <div class="col-sm-4 col-xs-12">
                    <!-- fbook__item -->
                    <div class="fbook__item" <?php echo $args['styles']['box']; ?>>
                        <i class="fa fa-thumbs-o-up" <?php echo $args['styles']['icon']; ?>></i>
                        <div <?php echo $args['styles']['count']; ?>><?php echo $args['rsvpattending']['summary']['count']; ?></div>
                        <span <?php echo $args['styles']['label']; ?>><?php _e('Yes', 'khore'); ?></span>
                    </div>
                    <!-- /fbook__item -->
                </div>
                <div class="col-sm-4 col-xs-12">
                    <!-- fbook__item -->
                    <div class="fbook__item" <?php echo $args['styles']['box']; ?>>
                        <i class="fa fa-hand-o-down" <?php echo $args['styles']['icon']; ?>></i>
                        <div <?php echo $args['styles']['count']; ?>><?php echo $args['rsvpmaybe']['summary']['count']; ?></div>
                        <span <?php echo $args['styles']['label']; ?>><?php _e('Maybe', 'khore'); ?></span>
                    </div>
                    <!-- /fbook__item -->
                </div>
                <div class="col-sm-4 col-xs-12">
                    <!-- fbook__item -->
                    <div class="fbook__item" <?php echo $args['styles']['box']; ?>>
                        <i class="fa fa-thumbs-o-down" <?php echo $args['styles']['icon']; ?>></i>
                        <div <?php echo $args['styles']['count']; ?>><?php echo $args['rsvpdeclined']['summary']['count']; ?></div>
                        <span <?php echo $args['styles']['label']; ?>><?php _e('No', 'khore'); ?></span>
                    </div>
                    <!-- /fbook__item -->
                </div>
                <div class="col-sm-12 fbook__lnk">
                    <a href="<?php echo $args['event_link']; ?>" target="_blank" <?php echo $args['styles']['button']; ?>><?php echo $args['event_link_text']; ?></a>
                </div>
            </div>
            <!-- /fbook__content -->
        </div>
    </div>
    <!-- /fbook__wrap -->
</section>
<!-- /fbook -->