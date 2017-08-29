<?php if ('on' == et_get_option('divi_back_to_top', 'false')) : ?>

    <span class="et_pb_scroll_top et-pb-icon"></span>

<?php endif;

if (!is_page_template('page-template-blank.php')) : ?>

    <footer id="main-footer">
        <?php get_sidebar('footer'); ?>


        <?php
        if (has_nav_menu('footer-menu')) : ?>

            <div id="et-footer-nav">
                <div class="container">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'footer-menu',
                        'depth' => '1',
                        'menu_class' => 'bottom-nav',
                        'container' => '',
                        'fallback_cb' => '',
                    ));
                    ?>
                </div>
            </div> <!-- #et-footer-nav -->

        <?php endif; ?>

        <div id="footer-bottom">
            <div class="container clearfix">
                <?php
                if (false !== et_get_option('show_footer_social_icons', true)) {
                    get_template_part('includes/social_icons', 'footer');
                }
                ?>

                <p id="footer-info"><?php printf(et_get_safe_localization(__('Designed by %1$s | Powered by %2$s', 'Divi')), '<a href="http://www.elegantthemes.com" title="Premium WordPress Themes">Elegant Themes</a>', '<a href="http://www.wordpress.org">WordPress</a>'); ?></p>
            </div>
        </div>
    </footer>
    </div> <!-- /#et-main-area -->

<?php endif; // ! is_page_template( 'page-template-blank.php' ) ?>

</div> <!-- #page-container -->

<?php wp_footer(); ?>

<!-- Piwik -->
<script type="text/javascript">
    jQuery('.markdown-body a').on('click', function () {
        $(this).unbind('click').click();
    })
</script>
<script type="text/javascript">
    var _paq = _paq || [];
    _paq.push(['trackPageView']);
    _paq.push(['enableLinkTracking']);
    (function () {
        var u = "//analise.cultura.gov.br/";
        _paq.push(['setTrackerUrl', u + 'piwik.php']);
        _paq.push(['setSiteId', 27]);
        var d = document, g = d.createElement('script'), s = d.getElementsByTagName('script')[0];
        g.type = 'text/javascript';
        g.async = true;
        g.defer = true;
        g.src = u + 'piwik.js';
        s.parentNode.insertBefore(g, s);
    })();
</script>
<noscript><p><img src="//analise.cultura.gov.br/piwik.php?idsite=27" style="border:0;" alt=""/></p></noscript>
<!-- End Piwik Code -->

</body>
</html>