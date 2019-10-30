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

if (!class_exists('EF_Content_Builder_Section_Image')) {

    class EF_Content_Builder_Section_Picture extends EF_Content_Builder_Section {

        function __construct() {
            $this->ID = 'picture';
            $this->clientID = 'efcb-section-picture';
            $this->name = __('Image', 'dxef');
            $this->cssClass = 'ef-cb-section';
            $this->iconCssClass = 'fa fa-file-image-o';
            $this->hasEntities = false;
            $this->entityType = false;
            $this->options = array(
                array(
                    'ID' => 'url',
                    'name' => __('Picture', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::IMAGE,
                    'nested' => false
                ),
                array(
                    'ID' => 'alt',
                    'name' => __('Alt', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::TEXT,
                    'nested' => false
                ),
                array(
                    'ID' => 'width',
                    'name' => __('Width', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::TEXT,
                    'nested' => false
                ),
                array(
                    'ID' => 'height',
                    'name' => __('Height', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::TEXT,
                    'nested' => false
                ),
                array(
                    'ID' => 'alignment',
                    'name' => __('Image alignment:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::SELECT,
                    'values' => array(
                        'none' => 'None',
                        'left' => 'Left',
                        'right' => 'Right',
                    ),
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