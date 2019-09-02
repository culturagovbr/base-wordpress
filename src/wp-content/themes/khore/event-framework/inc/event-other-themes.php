<?php
// Get Theme Name 
$theme_name = strtolower(EF_Framework_Helper::get_theme_name());
?>
<div class="wrap">
    <div class="custom-wrapper">
        <div class="ef-theme-otherthemes">
            <div class="logo">
                <img src="https://dl.dropboxusercontent.com/u/478761/themeads/logo.png" alt="Showthemes" height="61" width="378" />
            </div>
            <h3 class="description">The most advanced WordPress themes for Event Professionals</h3>
            <ul class="themes">
                <li class="vertoh" data-theme="vertoh">
                    <div class="image-container">
                        <a href="http://www.showthemes.com/2015-event-wordpress-theme-vertoh?utm_source=InThemeAd&utm_medium=InThemeAd&utm_campaign=InThemeAd" target="_blank">
                            <img width="370" height="176" src="https://dl.dropboxusercontent.com/u/478761/themeads/vertoh.png" alt="Vertoh" />
                        </a>
                    </div>
                    <div class="theme-details active">
                        <span class="theme-name">Vertoh</span>
                        <a class="button" target="_blank" href="http://www.showthemes.com/2015-event-wordpress-theme-vertoh?utm_source=InThemeAd&utm_medium=InThemeAd&utm_campaign=InThemeAd">Buy Now</a>
                    </div>
                </li>
                <li class="tyler" data-theme="tyler">
                    <div class="image-container">
                        <a href="http://www.showthemes.com/new-event-wordpress-theme-tyler?utm_source=InThemeAd&utm_medium=InThemeAd&utm_campaign=InThemeAd" target="_blank">
                            <img width="370" height="176" src="https://dl.dropboxusercontent.com/u/478761/themeads/tyler.png" alt="Tyler" />
                        </a>
                    </div>
                    <div class="theme-details active">
                        <span class="theme-name">Tyler</span>
                        <a class="button" target="_blank" href="http://www.showthemes.com/new-event-wordpress-theme-tyler?utm_source=InThemeAd&utm_medium=InThemeAd&utm_campaign=InThemeAd">Buy Now</a>
                    </div>
                </li>
                <li class="fudge" data-theme="fudge">
                    <div class="image-container">
                        <a href="http://www.showthemes.com/conference-wordpress-theme-fudge?utm_source=InThemeAd&utm_medium=InThemeAd&utm_campaign=InThemeAd" target="_blank">
                            <img width="370" height="176" src="https://dl.dropboxusercontent.com/u/478761/themeads/fudge.png" alt="Fudge" />
                        </a>
                    </div>
                    <div class="theme-details active">
                        <span class="theme-name">Fudge</span>
                        <a class="button" target="_blank" href="http://www.showthemes.com/conference-wordpress-theme-fudge?utm_source=InThemeAd&utm_medium=InThemeAd&utm_campaign=InThemeAd">Buy Now</a>
                    </div>
                </li>
                <li class="januas" data-theme="januas">
                    <div class="image-container">
                        <a href="http://www.showthemes.com/multiple-event-wordpress-theme-januas?utm_source=InThemeAd&utm_medium=InThemeAd&utm_campaign=InThemeAd" target="_blank">
                            <img width="370" height="176" src="https://dl.dropboxusercontent.com/u/478761/themeads/januas.png" alt="Januas" />
                        </a>
                    </div>
                    <div class="theme-details active">
                        <span class="theme-name">Januas</span>
                        <a class="button" target="_blank" href="http://www.showthemes.com/multiple-event-wordpress-theme-januas?utm_source=InThemeAd&utm_medium=InThemeAd&utm_campaign=InThemeAd">Buy Now</a>
                    </div>
                </li>
                <li class="emtheme" data-theme="emtheme">
                    <div class="image-container">
                        <a href="http://www.showthemes.com/wordpress-event-manager-theme?utm_source=InThemeAd&utm_medium=InThemeAd&utm_campaign=InThemeAd" target="_blank">
                            <img width="370" height="176" src="https://dl.dropboxusercontent.com/u/478761/themeads/emtheme.png" alt="Event Manager Theme" />
                        </a>
                    </div>
                    <div class="theme-details active">
                        <span class="theme-name">Event Manager Theme</span>
                        <a class="button" target="_blank" href="http://www.showthemes.com/wordpress-event-manager-theme?utm_source=InThemeAd&utm_medium=InThemeAd&utm_campaign=InThemeAd">Buy Now</a>
                    </div>
                </li>
            </ul>
        </div><!-- end of .ef-theme-otherthemes -->
    </div><!-- end of .custom-wrapper -->
</div><!-- end of #wrap -->
<script type="text/javascript">
    jQuery(function(){
       jQuery('.ef-theme-otherthemes .themes li[data-theme=<?php echo $theme_name; ?>]').hide();
    });
</script>