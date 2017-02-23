<?php
$tierClass = '';
switch ($args['type']) {
    case 'large':
        $tierClass = 'sponsors__content_gold';
        break;
    case 'medium':
        $tierClass = 'sponsors__content_silver';
        break;
    case 'small':
        $tierClass = 'sponsors__content_bronze';
        break;
}
?>
<!-- sponsors -->
<section class="sponsors" data-section="sponsors">
    <!-- sponsors__wrap -->
    <div class="sponsors__wrap">
        <div class="container">
            <!-- sponsors__content -->
            <div class="sponsors__content  <?php echo $tierClass; ?> row">

                <div class="col-xs-12">
                    <div class="container">
                        <div class="row" <?php echo $args['styles']['section']; ?>>
                            <!-- sponsors__info -->
                            <div class="sponsors__info col-md-4" <?php echo $args['styles']['tier_description']; ?>>
                                <!-- sponsors___content-title -->
                                <div class="sponsors___content-title">
                                    <h3 <?php echo $args['styles']['tier']; ?>><?php echo $args['title']; ?></h3>
                                </div>
                                <!-- /sponsors___content-title -->
                                <?php echo $args['description']; ?>
                            </div>
                            <!-- /sponsors__info -->
                            <!-- sponsors__photo -->
                            <div class="sponsors__photo col-md-8">
                                <?php
                                if (!empty($args['sponsors'])) {
                                    foreach ($args['sponsors'] as $sponsor) {
                                        $link = get_post_meta($sponsor->ID, 'sponsor_link', true);
                                        $image_array = wp_get_attachment_image_src(get_post_thumbnail_id($sponsor->ID), 'full');
                                        ?>
                                        <!-- sponsors__item -->
                                        <div class="sponsors__item">
                                            <!-- sponsors__face1 -->
                                            <div class="sponsors__face1" style="background-image:url('<?php echo $image_array[0]; ?>')"></div>
                                            <!-- /sponsors__face1 -->
                                            <!-- sponsors__face2 -->
                                            <div class="sponsors__face2" <?php echo $args['styles']['sponsor']; ?>>
                                                <h4 <?php echo $args['styles']['sponsor_title']; ?>><?php echo $sponsor->post_title; ?></h4>
                                                <p <?php echo $args['styles']['sponsor_description']; ?>><?php echo $sponsor->post_content; ?></p>
                                                <?php if (!empty($link)) { ?>
                                                    <a href="<?php echo $link ?>"  target="_blank" <?php echo $args['styles']['sponsor_detail_button']; ?>>
                                                        <i class="fa fa-link"></i>
                                                        <span><?php echo $args['view_button_text']; ?></span>
                                                    </a>
                                                <?php } ?>
                                            </div>
                                            <!-- /sponsors__face2 -->
                                        </div>
                                        <!-- /sponsors__item -->
                                        <?php
                                    }
                                }
                                ?>
                            </div>
                            <!-- /sponsors__photo -->
                        </div>
                    </div>
                </div>
            </div>
            <!-- /sponsors__content -->
            <?php /*
              }
              } */
            ?>
        </div>
    </div>
    <!-- /sponsors__wrap -->
</section>
<!-- /sponsors -->