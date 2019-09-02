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

if (!class_exists('EF_Content_Builder_Section_FullExhibitors')) {

    class EF_Content_Builder_Section_FullExhibitors extends EF_Content_Builder_Section {

        function __construct() {
            $this->ID = 'fullexhibitors';
            $this->clientID = 'efcb-section-fullexhibitors';
            $this->name = __('Exhibitors', 'dxef');
            $this->cssClass = 'ef-cb-section';
            $this->iconCssClass = 'fa fa-male';
            $this->hasEntities = true;
            $this->entityType = 'exhibitors';
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
                    'ID' => 'view_text',
                    'name' => __('\'View profile\' Text:', 'dxef'),
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
                    'name' => __('Title text color:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::COLOR,
                    'nested' => false
                ),
                array(
                    'ID' => 'subtitle_font_color',
                    'name' => __('Subtitle text color:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::COLOR,
                    'nested' => false
                ),
                array(
                    'ID' => 'exhibitor_title_font_color',
                    'name' => __('Exhibitor title text color:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::COLOR,
                    'nested' => false
                ),
                array(
                    'ID' => 'exhibitor_subtitle_font_color',
                    'name' => __('Exhibitor subtitle text color:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::COLOR,
                    'nested' => false
                ),
                array(
                    'ID' => 'exhibitor_detail_button_font_color',
                    'name' => __('Exhibitor detail button text color:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::COLOR,
                    'nested' => false
                ),
                array(
                    'ID' => 'exhibitor_pagination_active_font_color',
                    'name' => __('Exhibitor pagination active text color:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::COLOR,
                    'nested' => false
                ),
                array(
                    'ID' => 'exhibitor_pagination_disabled_font_color',
                    'name' => __('Exhibitor pagination disabled text color:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::COLOR,
                    'nested' => false
                ),
                array(
                    'ID' => 'title_font_size',
                    'name' => __('Title text size:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::TEXT,
                    'nested' => false
                ),
                array(
                    'ID' => 'subtitle_font_size',
                    'name' => __('Subtitle size:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::TEXT,
                    'nested' => false
                ),
                array(
                    'ID' => 'exhibitor_title_font_size',
                    'name' => __('Exhibitor title size:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::TEXT,
                    'nested' => false
                ),
                
                array(
                    'ID' => 'exhibitor_subtitle_font_size',
                    'name' => __('Exhibitor subtitle size:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::TEXT,
                    'nested' => false
                ),
                array(
                    'ID' => 'exhibitor_detail_button_font_size',
                    'name' => __('Exhibitor detail button size:', 'dxef'),
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