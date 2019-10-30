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

if (!class_exists('EF_Content_Builder_Section_Columns4')) {

    class EF_Content_Builder_Section_Columns4 extends EF_Content_Builder_Section {

        function __construct() {
            $this->ID = 'columns-4';
            $this->clientID = 'efcb-section-columns-4';
            $this->name = __('Columns 4', 'dxef');
            $this->cssClass = 'ef-cb-section';
            $this->iconCssClass = 'fa fa-columns';
            $this->hasEntities = false;
            $this->entityType = false;
            $this->options = array(
                array(
                    'ID' => 'content1',
                    'name' => __('Content 1', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::WYSIWYG,
                    'nested' => true
                ),
                array(
                    'ID' => 'content2',
                    'name' => __('Content 2', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::WYSIWYG,
                    'nested' => true
                ),
                array(
                    'ID' => 'content3',
                    'name' => __('Content 3', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::WYSIWYG,
                    'nested' => true
                ),
                array(
                    'ID' => 'content4',
                    'name' => __('Content 4', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::WYSIWYG,
                    'nested' => true
                ),
                array(
                    'ID' => 'text_font_color',
                    'name' => __('Text color:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::COLOR,
                    'nested' => false
                ),
                array(
                    'ID' => 'text_alignment',
                    'name' => __('Text alignment:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::SELECT,
                    'values' => array(
                        'left' => 'Left',
                        'right' => 'Right',
                        'center' => 'Center',
                    ),
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
                    'ID' => 'text_font_size',
                    'name' => __('Text size:', 'dxef'),
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