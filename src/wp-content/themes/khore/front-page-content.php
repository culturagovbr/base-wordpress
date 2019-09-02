<!--slider -->
<div class="swiper-container">
    <?php echo get_home_page_slider(); ?>
    <div class="swiper-pagination"></div>
    <div class="swiper-button swiper-button-prev glyphicon glyphicon-chevron-left"></div>
    <div class="swiper-button swiper-button-next glyphicon glyphicon-chevron-right"></div>
</div>
<!-- /slider -->
<script type="text/javascript">
    jQuery('.swiper-container').parent('div').addClass('index');
</script>