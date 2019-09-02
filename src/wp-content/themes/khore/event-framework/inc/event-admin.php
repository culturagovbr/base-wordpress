<?php
// Get Theme Name 
$theme_name = EF_Framework_Helper::get_theme_name();
?>

<div class="wrap">
    <div class="custom-wrapper">
        <div class="ef-theme-options">

            <div id="ef-options-updated" class="updated fade">
                <p>
                    <strong><?php _e('Theme Options saved', 'dxef'); ?></strong>
                </p>
            </div>

            <header class="ef-options-header">
                <div class="ef-options-logo">
                    <img src="<?php echo EF_Framework_Helper::get_framework_logo_src(); ?>" alt="<?php _e('Event Framework Logo', 'dxef'); ?>">
                </div><!-- /ef-options-logo -->
                <div class="ef-about">
                    <p><?php echo $current_theme->display('Name'); ?> <span><?php echo $current_theme->display('Version'); ?></span></p>
                    <p>Developed by <a href="http://www.showthemes.com" target="_blank">Showthemes</a></p>
                </div><!-- /about -->
            </header><!-- /ef-options-header -->

            <nav class="el-options-links">
                <ul>
                    <li><a href="https://showthemes.zendesk.com/hc/en-us/articles/201480262" target="_blank"><?php _e('View Theme Documentation', 'dxef'); ?></a></li>
                    <li class='el-save el-button'><a id="ef-save-options" href="#"><?php _e('Save options', 'dxef'); ?></a></li>
                </ul>
            </nav><!-- /el-options-links -->

            <form id="ef-theme-options" method="POST">
                <div id="el-options-body">
                    <?php
                    global $ef_panel_manager;

                    $theme_options = $ef_panel_manager->get_panel('theme_options');

                    if (!empty($theme_options) && $theme_options instanceof EF_Options_Panel) {
                        $tabs = $theme_options->get_tabs();
                        ?>
                        <ul class="el-options-menu resp-tabs-list">
                            <?php foreach ($tabs as $tab_name => $tab) { ?>
                                <li><i class="fa <?php echo $tab_name; ?>"></i><?php echo $tab_name; ?></li>
                            <?php } ?>
                        </ul>
                        <div class="el-options-main resp-tabs-list resp-tabs-container">
                            <?php foreach ($tabs as $tab_name => $tab) { ?>
                                <div>
                                    <?php
                                    $fields = $tab->get_fields();
                                    foreach ($fields as $field_name => $field) {
                                        ?>
                                        <div class="el-option">
                                            <h3><?php // echo $field_name;  ?></h3>
                                            <div class="el-option-input">
                                                <?php $field->display(); ?>
                                            </div>
                                            <div class="el-option-description">
                                                <p><?php echo $field->description; ?></p>
                                            </div>
                                        </div><!-- end of .el-option -->
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div><!-- end of .el-options-main -->
                    <?php } ?>
                </div><!-- end of .el-options-body -->
            </form>
        </div><!-- end of .ef-theme-options -->
    </div><!-- end of .custom-wrapper -->
    <script type="text/javascript">
        jQuery(document).ready(function ($) {
            $('#el-options-body').easyResponsiveTabs({
                type: 'vertical',
                width: 'auto',
                fit: true
            });

            $('#ef-save-options').click(function () {
                $.post(
                        ajaxurl,
                        {
                            data: {theme_options: $('#ef-theme-options').serializeArray()},
                            action: 'save_theme_options'
                        },
                function (status) {
                    $('#ef-options-updated').show();
                    $('#ef-options-updated').fadeOut(8000, 'linear');
                }
                );
            });

        });
    </script>
</div><!-- end of #wrap -->