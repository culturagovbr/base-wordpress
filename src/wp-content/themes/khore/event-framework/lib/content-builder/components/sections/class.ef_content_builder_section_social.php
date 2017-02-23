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

if (!class_exists('EF_Content_Builder_Section_Social')) {

    class EF_Content_Builder_Section_Social extends EF_Content_Builder_Section {

        function __construct() {
            $this->ID = 'social';
            $this->clientID = 'efcb-section-social';
            $this->name = __('Social', 'dxef');
            $this->cssClass = 'ef-cb-section';
            $this->iconCssClass = 'fa fa-user-plus';
            $this->hasEntities = true;
            $this->entityType = false;
            $this->longView = 'section-social-long';
            $this->options = array(
                array(
                    'ID' => 'background_color',
                    'name' => __('Background color:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::COLOR,
                    'nested' => false
                ),
                array(
                    'ID' => 'icon_font_color',
                    'name' => __('Icon color:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::COLOR,
                    'nested' => false
                ),
                array(
                    'ID' => 'icon_hover_font_color',
                    'name' => __('Icon hover color:', 'dxef'),
                    'desc' => '',
                    'type' => EF_Content_Builder_Option_Type::COLOR,
                    'nested' => false
                ),
                array(
                    'ID' => 'icon_font_size',
                    'name' => __('Icon size:', 'dxef'),
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
            
            // replaces parent construct call
            
            $this->shortTemplate = sprintf(EF_Content_Builder_Views::getViewContent($this->shortView), $this->clientID, $this->cssClass, $this->ID, $this->iconCssClass, $this->name);

            $content = '';
            $this->shortcode = "[efcb-section-$this->ID";
            foreach ($this->options as $option) {
                if ($option['nested'] === false) {
                    $this->shortcode .= sprintf(' %s=""', $option['ID']);
                } else {
                    $content .= sprintf('[%s][/%s]', $option['ID'], $option['ID']);
                }
            }
            if ($this->hasEntities) {
                $this->shortcode .= ' entities=""';
            }
            $this->shortcode .= "]{$content}[/efcb-section-$this->ID]";
            // --------------------------------
            
            $themeOptions = EF_Event_Options::get_theme_options();
            $socialItems = array(
                'facebook' => array(
                    'url' => isset($themeOptions['ef_facebook']) ? $themeOptions['ef_facebook'] : '',
                    'icon' => 'facebook',
                    'option' => 'ef_facebook',
                    'title' => 'Facebook'
                ),
                'twitter' => array(
                    'url' => isset($themeOptions['ef_twitter']) ? $themeOptions['ef_twitter'] : '',
                    'icon' => 'twitter',
                    'option' => 'ef_twitter',
                    'title' => 'Twitter'
                ),
                'rss' => array(
                    'url' => isset($themeOptions['ef_rss']) ? $themeOptions['ef_rss'] : '',
                    'icon' => 'rss',
                    'option' => 'ef_rss',
                    'title' => 'Rss'
                ),
                'email' => array(
                    'url' => isset($themeOptions['ef_email']) ? $themeOptions['ef_email'] : '',
                    'icon' => 'envelope',
                    'option' => 'ef_email',
                    'title' => 'Email'
                ),
                'google_plus' => array(
                    'url' => isset($themeOptions['ef_google_plus']) ? $themeOptions['ef_google_plus'] : '',
                    'icon' => 'google-plus',
                    'option' => 'ef_google_plus',
                    'title' => 'Google +'
                ),
                'fickr' => array(
                    'url' => isset($themeOptions['ef_flickr']) ? $themeOptions['ef_flickr'] : '',
                    'icon' => 'flickr',
                    'option' => 'ef_flickr',
                    'title' => 'Flickr'
                ),
                'instagram' => array(
                    'url' => isset($themeOptions['ef_instagram']) ? $themeOptions['ef_instagram'] : '',
                    'icon' => 'instagram',
                    'option' => 'ef_instagram',
                    'title' => 'Instagram'
                ),
                'pinterest' => array(
                    'url' => isset($themeOptions['ef_pinterest']) ? $themeOptions['ef_pinterest'] : '',
                    'icon' => 'pinterest',
                    'option' => 'ef_pinterest',
                    'title' => 'Pinterest'
                ),
                'linkedin' => array(
                    'url' => isset($themeOptions['ef_linkedin']) ? $themeOptions['ef_linkedin'] : '',
                    'icon' => 'linkedin',
                    'option' => 'ef_linkedin',
                    'title' => 'Linkedin'
                ),
                'add_this' => array(
                    'url' => isset($themeOptions['ef_add_this_pubid']) ? $themeOptions['ef_add_this_pubid'] : '',
                    'icon' => 'user-plus',
                    'option' => 'ef_add_this_pubid',
                    'title' => 'AddThis'
                ),
                'youtube' => array(
                    'url' => isset($themeOptions['ef_youtube']) ? $themeOptions['ef_youtube'] : '',
                    'icon' => 'youtube',
                    'option' => 'ef_youtube',
                    'title' => 'Youtube'
                ),
                'skype' => array(
                    'url' => isset($themeOptions['ef_skype']) ? $themeOptions['ef_skype'] : '',
                    'icon' => 'skype',
                    'option' => 'ef_skype',
                    'title' => 'Skype'
                ),
                'vimeo' => array(
                    'url' => isset($themeOptions['ef_vimeo']) ? $themeOptions['ef_vimeo'] : '',
                    'icon' => 'vimeo-square',
                    'option' => 'ef_vimeo',
                    'title' => 'Vimeo'
                ),
            );
            $socialItemsList = '';
            foreach($socialItems as $socialItem){
                if(!empty($socialItem['url'])){
                    $socialItemsList .= sprintf(EF_Content_Builder_Views::getViewContent('entity-short'), 'social', $socialItem['option'], 'social', '', "<i class=\"fa fa-{$socialItem['icon']}\"></i><span>{$socialItem['title']}</span>");
                }
            }
            
            $this->longTemplate = sprintf(EF_Content_Builder_Views::getViewContent($this->longView), $this->cssClass, $this->ID, $this->entityType, $this->iconCssClass, $this->name, $this->description, $socialItemsList);
        }

    }

}