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

if (!class_exists('EF_Content_Builder_Section_FullTickets')) {

    class EF_Content_Builder_Section_FullTickets extends EF_Content_Builder_Section {

        function __construct() {
            $this->ID = 'fulltickets';
            $this->clientID = 'efcb-section-fulltickets';
            $this->name = __('All Tickets', 'dxef');
            $this->cssClass = 'ef-cb-section';
            $this->iconCssClass = 'fa fa-ticket';
            $this->hasEntities = true;
            $this->entityType = 'ticket';
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
                    'ID' => 'ticket_background_color',
                    'name' => __('Ticket background color:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::COLOR,
                    'nested' => false
                ),
                array(
                    'ID' => 'ticket_title_font_color',
                    'name' => __('Ticket title text color:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::COLOR,
                    'nested' => false
                ),
                array(
                    'ID' => 'ticket_pricebox_background_color',
                    'name' => __('Ticket price box background color:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::COLOR,
                    'nested' => false
                ),
                array(
                    'ID' => 'ticket_price_font_color',
                    'name' => __('Ticket price text color:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::COLOR,
                    'nested' => false
                ),
                array(
                    'ID' => 'ticket_text_font_color',
                    'name' => __('Ticket text text color:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::COLOR,
                    'nested' => false
                ),
                array(
                    'ID' => 'ticket_button_background_color',
                    'name' => __('Ticket button background color:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::COLOR,
                    'nested' => false
                ),
                array(
                    'ID' => 'ticket_button_font_color',
                    'name' => __('Ticket button text color:', 'dxef'),
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
                    'name' => __('Subtitle text size:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::TEXT,
                    'nested' => false
                ),
                array(
                    'ID' => 'ticket_title_font_size',
                    'name' => __('Subtitle text size:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::TEXT,
                    'nested' => false
                ),
                array(
                    'ID' => 'ticket_price_font_size',
                    'name' => __('Ticket price text size:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::TEXT,
                    'nested' => false
                ),
                array(
                    'ID' => 'ticket_text_font_size',
                    'name' => __('Ticket text text size:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::TEXT,
                    'nested' => false
                ),
                array(
                    'ID' => 'ticket_button_font_size',
                    'name' => __('Ticket button text size:', 'dxef'),
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