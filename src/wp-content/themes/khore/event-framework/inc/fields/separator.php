<?php

class EF_Separator_Field extends EF_Field_Base {

    public $type = 'separator';

    public function display() {
        $defaults = array(
            'section_prefix' => 'section_prefix_',
            'class' => 'ef-section ef-separator',
            'style' => '',
            'id_prefix' => 'id_prefix'
        );

        $args = wp_parse_args($this->args, $defaults);
        extract($args);
        ?>
        <h3 class="<?php echo $class ?>" <?php echo $style; ?>>
            <?php echo esc_attr(stripslashes($this->id)); ?>
        </h3>
        <?php
    }

}
