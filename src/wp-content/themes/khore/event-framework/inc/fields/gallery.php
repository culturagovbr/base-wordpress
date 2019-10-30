<?php

class EF_Gallery_Field extends EF_Field_Base {

    public $type = 'gallery';

    public function display() {
        $defaults = array(
            'section_prefix' => 'section_prefix_',
            'class' => 'ef-section ef-gallery',
            'style' => '',
            'id_prefix' => 'id_prefix'
        );

        $args = wp_parse_args($this->args, $defaults);
        extract($args);
        ?>
        <section class="<?php echo $class ?>" <?php echo $style; ?>>
            <label for="<?php echo $id_prefix . $this->id ?>"><?php echo $this->name ?></label>
            <div class="sortable-container<?php echo (isset($video) && $video === true) ? ' video' : ''; ?>">
                <input id="<?php echo $id_prefix . $this->id ?>_button" class="ef-upload-button button" type="button" value="<?php _e('Upload Image', 'dxef'); ?>" style="margin-left:0;" />
                <?php if (isset($video) && $video === true) { ?>
                    <br/><br/>
                    <?php _e('or add a video url (Youtube or Vimeo)', 'dxef'); ?>
                    <br/>
                    <input id="<?php echo $id_prefix . $this->id ?>_video" value="" />
                    <input id="<?php echo $id_prefix . $this->id ?>_button_video" class="ef-upload-button button" type="button" value="<?php _e('Add Video', 'dxef'); ?>" style="margin:0;" />
                <?php } ?>
                <br/><br/>
                <ul id="<?php echo $id_prefix . $this->id ?>_sortable" class="sortable destination sortable_media" style="padding: 0;">
                    <?php
                    if (!empty($this->value)) {
                        foreach (explode(',', $this->value) as $id) {
                            ?>
                            <li class="ui-state-default" data-id="<?php echo $id; ?>" style="float: left;margin: 10px;cursor: default;">
                                <a href="#" class="remove_gallery_item">remove</a><br/>
                                <?php if (ctype_digit($id)) { ?>
                                    <img src="<?php echo wp_get_attachment_url($id); ?>" alt="" height="70"/>
                                <?php } else { ?>
                                    <img src="<?php echo khore_get_video_thumbnail($id, array('youtube' => 'default', 'vimeo' => 'thumbnail_small')); ?>" alt="" height="70"/>
                                <?php } ?>
                            </li>
                            <?php
                        }
                    }
                    ?>
                </ul>
                <input type="hidden" name="eventframework[<?php echo $this->id ?>]" value="<?php if (!empty($this->value)) echo $this->value; ?>" />
            </div>
        </section>
        <script type="text/javascript">
            jQuery(function() {
                jQuery('#<?php echo $id_prefix . $this->id ?>_button').click(function(e) {
                    var custom_uploader;
                    var that = this; 
                            e.preventDefault();
         
                            if (custom_uploader) {
                                    custom_uploader.open();
                                    return;
                            }
                            custom_uploader = wp.media.frames.file_frame = wp.media({
                                    title: 'Choose Image',
                                    button: {
                                            text: 'Choose Image'
                                    },
                                    multiple: false
                            });
                            custom_uploader.on('select', jQuery.proxy(function() {
                            attachment = custom_uploader.state().get('selection').first().toJSON();
                        jQuery(that).nextAll('.sortable_media').append('<li class="ui-state-default" data-id="' + attachment.id + '" style="float: left;margin: 10px;cursor: default;"><a href="#" class="remove_gallery_item">remove</a><br/><img src="' + attachment.url + '" alt="" height="70" /></li>');
                        khore_update_gallery('[name="eventframework[<?php echo $this->id ?>]"]', "#<?php echo $id_prefix . $this->id ?>_sortable");
                    }, that));
                    custom_uploader.open();
                });

                jQuery('#<?php echo $id_prefix . $this->id ?>_button_video').click(function(e) {
                    video_url = jQuery('#<?php echo $id_prefix . $this->id ?>_video').val();
                    container = this;
                    video_thumb = '';

                    jQuery.post(
                            ajaxurl,
                            {action: 'get_video_thumbnail', url: video_url},
                    function(data, textStatus, jqXHR) {
                        jQuery(container).nextAll('.sortable_media').append('<li class="ui-state-default" data-id="' + video_url + '" style="float: left;margin: 10px;cursor: default;"><a href="#" class="remove_gallery_item">remove</a><br/><img src="' + data + '" alt="" height="70" /></li>');
                        khore_update_gallery('[name="eventframework[<?php echo $this->id ?>]"]', "#<?php echo $id_prefix . $this->id ?>_sortable");
                        jQuery('#<?php echo $id_prefix . $this->id; ?>_video').val('');
                    },
                            'json'
                            );
                });

                jQuery("#<?php echo $id_prefix . $this->id ?>_sortable").sortable({
                    update: function(event, ui) {
                        khore_update_gallery('[name="eventframework[<?php echo $this->id ?>]"]', "#<?php echo $id_prefix . $this->id ?>_sortable");
                    }
                }).disableSelection();

                jQuery('body').on('click', '.remove_gallery_item', function(e) {
                    e.preventDefault();
                    jQuery(this).parent().remove();
                    khore_update_gallery('[name="eventframework[<?php echo $this->id ?>]"]', "#<?php echo $id_prefix . $this->id ?>_sortable");
                    return false;
                });
            });

            if (typeof khore_update_gallery != 'function')
                    function khore_update_gallery(destination, sortable) {
                        jQuery(sortable).sortable('refresh');
                        jQuery(destination).val(jQuery(sortable).sortable('toArray', {
                            attribute: "data-id"
                        }).toString());
                    }
        </script>
        <?php
    }

}
