<?php
get_header();
?>
<div class="site__content light">
    <div class="page page-category light page_loaded page_active">
        <div class="page__scroll">
            <div>
                <?php echo do_shortcode('[efcb-section-fullnews title="' . single_cat_title('', false) . '" categories="' . $cat . '"][/efcb-section-fullnews]'); ?>
            </div>
        </div>
    </div>
</div>
<?php
get_footer();
