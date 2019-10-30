<!-- tickets -->
<section class="tickets" data-section="tickets" <?php echo $args['styles']['section']; ?>>
    <div class="tickets__wrap container">
        <header class="row">
            <!-- site__title -->
            <h2 class="site__title" <?php echo $args['styles']['title']; ?>>
                <?php echo stripslashes($args['title']); ?>
                <span <?php echo $args['styles']['subtitle']; ?>><?php echo stripslashes($args['subtitle']); ?></span>
            </h2>
            <!-- /site__title -->
        </header>
        <!-- tickets__content -->
        <div class="tickets__content row">
            <?php
            if ($args['tickets'] && count($args['tickets'])) {
                foreach ($args['tickets'] as $ticket) {
                    $status = get_post_meta($ticket->ID, 'ticket_status', true);
                    switch ($status) {
                        case 'onsale':
                            $status_class = 'tickets__item_on-sale';
                            break;
                        case 'featured':
                            $status_class = 'featured';
                            break;
                        case 'soldout':
                            $status_class = 'tickets__item_dis';
                            break;
                        default:
                            $status_class = '';
                    }
                    $ticket_url = ($status != 'soldout') ? get_post_meta($ticket->ID, 'ticket_button_link', true) : 'javascript:return false;';
                    ?>
                    <div class="col-md-4">
                        <!-- tickets__item -->
                        <div class="tickets__item <?php echo $status_class; ?>" <?php echo $args['styles']['ticket']; ?>>
                            <div class="vertical-center">
                                <div>
                                    <h3 <?php echo $args['styles']['ticket_title']; ?>><?php echo get_the_title($ticket->ID); ?></h3>
                                </div>
                            </div>
                            <!-- tickets__price -->
                            <div class="tickets__price" <?php echo $args['styles']['ticket_price_box']; ?>>
                                <?php echo get_post_meta($ticket->ID, 'ticket_price', true); ?>
                                <div class="ribbon"><i class="flaticon-vertical3"></i> <i class="fa fa-star-o"></i>
                                </div>
                            </div>
                            <!-- /tickets__price -->
                            <p <?php echo $args['styles']['ticket_text']; ?>><?php echo $ticket->post_content; ?></p>
                            <a href="<?php echo $ticket_url; ?>" class="tickets__buy" <?php echo $args['styles']['ticket_button']; ?>>
                                <?php echo get_post_meta($ticket->ID, 'ticket_button_text', true); ?>
                            </a>
                        </div>
                        <!-- /tickets__item -->
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        <!-- /tickets__content -->
    </div>
</section>
<!-- /tickets -->