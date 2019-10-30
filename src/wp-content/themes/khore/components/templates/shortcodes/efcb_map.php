<?php
$marks = array();
if (!empty($args['pois_grouped'])) {
    foreach ($args['pois_grouped'] as $poi_group) {
        $marks = array_merge($marks, $poi_group);
    }
}
?>
<!-- location -->
<script type="text/javascript">
    var poi_gmap_zoom = <?php echo (!empty($args['zoom'])) ? $args['zoom'] : 13; ?>;
</script>
<section class="location light" data-section="location" <?php echo $args['styles']['section']; ?>>
    <!-- location__wrap -->
    <div class="location__wrap">
        <!-- location__titles -->
        <div class="location__titles">
            <ul>
                <?php for ($i = 0; $i < count($args['groups']); $i++) {
                    ?>
                    <li class="vertical-center" data-group="<?php echo $i; ?>" <?php echo $args['styles']['group']; ?>>
                        <div>
                            <span><?php echo $args['groups'][$i]; ?></span>
                        </div>
                    </li>
                <?php } ?>
            </ul>
        </div>
        <!-- /location__titles -->
        <!-- location__control -->
        <select class="light" id="location_select" name="select_name" <?php echo $args['styles']['location']; ?>>
            <option value="0" selected><?php _e('EXPLORE', 'khore'); ?></option>
            <?php
            for ($i = 0; $i < count($args['pois_grouped']); $i++) {
                for ($j = 0; $j < count($args['pois_grouped'][$i]); $j++) {
                    ?>
                    <option value="<?php echo $args['pois_grouped'][$i][$j]['ID']; ?>" data-group="<?php echo $i; ?>" data-lat="<?php echo $args['pois_grouped'][$i][$j]['poi_latitude']; ?>" data-lng="<?php echo $args['pois_grouped'][$i][$j]['poi_longitude']; ?>"><?php echo $args['pois_grouped'][$i][$j]['poi_title']; ?></option>
                    <?php
                }
            }
            ?>
        </select>
        <!-- /location__control -->
        <!-- location__map -->
        <div class="location__map" data-map='{"marks":<?php echo json_encode($marks, JSON_HEX_APOS); ?>}' >
        </div>
        <!-- /location__map -->
    </div>
    <!-- /location__wrap -->
</section>
<!-- /location -->