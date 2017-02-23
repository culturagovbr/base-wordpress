<?php

class EF_Image_Field extends EF_Field_Base {

    public $type = 'image';

    public function display() {
        $defaults = array(
            'section_prefix' => 'section_prefix_',
            'class' => 'ef-section',
            'style' => '',
            'id_prefix' => 'id_prefix',
            'selector' => ''
        );

        $args = wp_parse_args($this->args, $defaults);
        extract($args);
        ?>
        <section id="<?php echo $section_prefix . $this->id ?>" class="<?php echo $class ?>" <?php echo $style; ?>>
            <label for="<?php echo $id_prefix . $this->id ?>"><?php echo $this->name ?></label>	

            <?php
            $img_display = '';
            if (empty($this->value) || $this->value == 'http://') {
                $img_display = 'style="display: none"';
            }
            ?>
            <div>
                <img id="<?php echo $id_prefix . $this->id ?>_img" class="ef-image-preview" src="<?php echo $this->value; ?>" <?php echo $img_display; ?> />
            </div>

                <input id="<?php echo $id_prefix . $this->id ?>" type="text" size="36" name="eventframework[<?php echo $this->id ?>]" value="<?php if (!empty($this->value)) {
            echo $this->value;
        } else {
            echo '';
        } ?>" class="ef-image-upload" /> 
                <input id="<?php echo $id_prefix . $this->id ?>_button" class="ef-upload-button button" type="button" value="Upload Image" />
        </section>
        <?php wp_enqueue_media(); ?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                    	$('#<?php echo $id_prefix . $this->id ?>_button').click(EF_Admin.image_upload_cb);

                $('#<?php echo $id_prefix . $this->id ?>').change(function () {

                    var imgSrc = $(this).val();
                    var img_selector = '#<?php echo $id_prefix . $this->id ?>_img';

                    $(img_selector).attr('src', imgSrc);

                    if (imgSrc.length === 0) {
                        $(img_selector).hide();
                    } else {
                        $(img_selector).show();
                    }
                });
            });
        </script>
        <?php
    }

    public function get_css_rules() {
        $css = array();

        if (!empty($this->value)) {
            $css['background-image'] = 'url("' . $this->value . '")';
        }

        return $css;
    }

}
