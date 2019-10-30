<?php
get_header();
?>
<div class="site__content light">
    <div class="page page-archive light page_loaded page_active">
        <div class="page__scroll">
            <div>
                <?php echo do_shortcode('[efcb-section-fullnews title="' . get_the_archive_title() . '"][/efcb-section-fullnews]'); ?>
            </div>
        </div>
    </div>
</div>
<?php
get_footer();
