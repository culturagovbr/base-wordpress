<?php

/*  Copyright 2015  Showthemes Content Builder  (email : info@showthemes.com)
  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

if (!class_exists('EF_Content_Builder_Section_Facebook')) {

    class EF_Content_Builder_Section_Facebook extends EF_Content_Builder_Section {

        function __construct() {
            $this->ID = 'facebook';
            $this->clientID = 'efcb-section-facebook';
            $this->name = __('Facebook', 'dxef');
            $this->cssClass = 'ef-cb-section';
            $this->iconCssClass = 'fa fa-facebook-official';
            $this->hasEntities = false;
            $this->entityType = false;
            $this->options = array(
                array(
                    'ID' => 'title',
                    'name' => __('Title', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::TEXT,
                    'nested' => false
                ),
                array(
                    'ID' => 'subtitle',
                    'name' => __('Subtitle', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::TEXT,
                    'nested' => false
                ),
                array(
                    'ID' => 'event_id',
                    'name' => __('Event ID:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::TEXT,
                    'nested' => false
                ),
                array(
                    'ID' => 'event_link',
                    'name' => __('Link to event on Facebook', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::TEXT,
                    'nested' => false
                ),
                array(
                    'ID' => 'event_link_text',
                    'name' => __('Link text:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::TEXT,
                    'nested' => false
                ),
                array(
                    'ID' => 'background_color',
                    'name' => __('Background color:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::COLOR,
                    'nested' => false
                ),
                array(
                    'ID' => 'title_font_color',
                    'name' => __('Title color', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::COLOR,
                    'nested' => false
                ),
                array(
                    'ID' => 'icon_font_color',
                    'name' => __('Icons color', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::COLOR,
                    'nested' => false
                ),
                array(
                    'ID' => 'box_font_color',
                    'name' => __('Boxes text color', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::COLOR,
                    'nested' => false
                ),
                array(
                    'ID' => 'box_background_color',
                    'name' => __('Boxes background color', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::COLOR,
                    'nested' => false
                ),
                array(
                    'ID' => 'count_font_color',
                    'name' => __('Count text color', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::COLOR,
                    'nested' => false
                ),
                array(
                    'ID' => 'count_background_color',
                    'name' => __('Count background color', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::COLOR,
                    'nested' => false
                ),
                array(
                    'ID' => 'label_font_color',
                    'name' => __('Label text color', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::COLOR,
                    'nested' => false
                ),
                array(
                    'ID' => 'button_font_color',
                    'name' => __('Button text color', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::COLOR,
                    'nested' => false
                ),
                array(
                    'ID' => 'title_font_size',
                    'name' => __('Title text size', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::TEXT,
                    'nested' => false
                ),
                array(
                    'ID' => 'icon_font_size',
                    'name' => __('Icon size', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::TEXT,
                    'nested' => false
                ),
                array(
                    'ID' => 'count_font_size',
                    'name' => __('Count text size', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::TEXT,
                    'nested' => false
                ),
                array(
                    'ID' => 'label_font_size',
                    'name' => __('Label text size', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::TEXT,
                    'nested' => false
                ),
                array(
                    'ID' => 'button_font_size',
                    'name' => __('Button text size', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::TEXT,
                    'nested' => false
                ),
                array(
                    'ID' => 'margin_top',
                    'name' => __('Margin top:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::TEXT,
                    'nested' => false
                ),
                array(
                    'ID' => 'margin_bottom',
                    'name' => __('Margin bottom:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::TEXT,
                    'nested' => false
                ),
            );
            
            parent::__construct();
        }

    }

}