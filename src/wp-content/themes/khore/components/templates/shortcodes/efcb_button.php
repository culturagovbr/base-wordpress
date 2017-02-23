<?php
global $khore_footer_scripts;
$khore_footer_scripts[] = "#{$args['element_id']}:hover{color:{$args['additional_styles']['section']['color']}!important;background-color:{$args['additional_styles']['section']['background-color']}!important;}";
?>
<div class="buttons row clearfix">
    <button id="<?php echo $args['element_id']; ?>" class="btn" href="#" <?php echo $args['styles']['section']; ?> onclick="window.location.href = '<?php echo $args['url']; ?>'"><?php echo $args['title']; ?></button>
</div>
