<?php
global $khore_footer_scripts;
$khore_footer_scripts[] = "#{$args['element_id']} a:hover{color:{$args['additional_styles']['icon']['color']}!important;}";
?>
<!-- social -->
<div id="<?php echo $args['element_id']; ?>" class="row" <?php echo $args['styles']['section']; ?>>
    <div class="social col-xs-12">
        <?php if (!empty($args['items'])) { ?>

            <?php if (in_array('ef_linkedin', $args['items'])) { ?>
                <a href="<?php echo esc_url($args['ef_options']['ef_linkedin'], $args['esc_url_protocols']); ?>"
                   target="_blank" title="LinkedIn" <?php echo $args['styles']['icon']; ?>><i class='fa fa-linkedin'></i></a>
               <?php } ?>
               <?php if (in_array('ef_twitter', $args['items'])) { ?>
                <a href="<?php echo esc_url($args['ef_options']['ef_twitter'], $args['esc_url_protocols']); ?>"
                   target="_blank" title="Twitter" <?php echo $args['styles']['icon']; ?>><i class='fa fa-twitter'></i></a>
               <?php } ?>
               <?php if (in_array('ef_facebook', $args['items'])) { ?>
                <a href="<?php echo esc_url($args['ef_options']['ef_facebook'], $args['esc_url_protocols']); ?>"
                   target="_blank" title="Facebook" <?php echo $args['styles']['icon']; ?>><i class='fa fa-facebook'></i></a>
               <?php } ?>
               <?php if (in_array('ef_instagram', $args['items'])) { ?>
                <a href="<?php echo esc_url($args['ef_options']['ef_instagram'], $args['esc_url_protocols']); ?>"
                   target="_blank" title="Instagram" <?php echo $args['styles']['icon']; ?>><i class='fa fa-instagram'></i></a>
               <?php } ?>
               <?php if (in_array('ef_youtube', $args['items'])) { ?>
                <a href="<?php echo esc_url($args['ef_options']['ef_youtube'], $args['esc_url_protocols']); ?>"
                   target="_blank" title="Youtube" <?php echo $args['styles']['icon']; ?>><i class='fa fa-youtube'></i></a>
               <?php } ?>
               <?php if (in_array('ef_pinterest', $args['items'])) { ?>
                <a href="<?php echo esc_url($args['ef_options']['ef_pinterest'], $args['esc_url_protocols']); ?>"
                   target="_blank" title="Pinterest" <?php echo $args['styles']['icon']; ?>><i class='fa fa-pinterest'></i></a>
               <?php } ?>
               <?php if (in_array('ef_google_plus', $args['items'])) { ?>
                <a href="<?php echo esc_url($args['ef_options']['ef_google_plus'], $args['esc_url_protocols']); ?>"
                   target="_blank" title="Google+" <?php echo $args['styles']['icon']; ?>><i class='fa fa-google-plus'></i></a>
               <?php } ?>
               <?php if (in_array('ef_email', $args['items'])) { ?>
                <a href="mailto:<?php echo esc_url($args['ef_options']['ef_email'], $args['esc_url_protocols']); ?>"
                   title="Email" <?php echo $args['styles']['icon']; ?>><i class='fa fa-envelope'></i></a>
               <?php } ?>
               <?php if (in_array('ef_vimeo', $args['items'])) { ?>
                <a href="<?php echo esc_url($args['ef_options']['ef_vimeo'], $args['esc_url_protocols']); ?>"
                   target="_blank" title="Vimeo" <?php echo $args['styles']['icon']; ?>><i class='fa fa-vimeo-square'></i></a>
               <?php } ?>
               <?php if (in_array('ef_flickr', $args['items'])) { ?>
                <a href="<?php echo esc_url($args['ef_options']['ef_flickr'], $args['esc_url_protocols']); ?>"
                   target="_blank" title="Flickr" <?php echo $args['styles']['icon']; ?>><i class='fa fa-flickr'></i></a>
               <?php } ?>
               <?php if (in_array('ef_skype', $args['items'])) { ?>
                <a href="<?php echo esc_url($args['ef_options']['ef_skype'], $args['esc_url_protocols']); ?>"
                   target="_blank" title="Skype" <?php echo $args['styles']['icon']; ?>><i class='fa fa-skype'></i></a>
               <?php } ?>
               <?php if (in_array('ef_rss', $args['items'])) { ?>
                <a href="<?php echo esc_url($args['ef_options']['ef_rss'], $args['esc_url_protocols']); ?>"
                   target="_blank" title="RSS" <?php echo $args['styles']['icon']; ?>><i class='fa fa-rss'></i></a>
               <?php } ?>
               <?php if (in_array('ef_add_this_pubid', $args['items'])) { ?>
                <a href="<?php echo esc_url($args['ef_options']['ef_add_this_pubid'], $args['esc_url_protocols']); ?>"
                   target="_blank" title="AddThis" <?php echo $args['styles']['icon']; ?>><i class='fa fa-user-plus'></i></a>
                <?php } ?>

        <?php } ?>
    </div>
</div>
<!-- /social -->