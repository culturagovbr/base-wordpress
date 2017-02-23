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

if (!class_exists('EF_Content_Builder_Section')) {

    abstract class EF_Content_Builder_Section {

        protected $ID;
        protected $clientID;
        protected $name;
        protected $cssClass;
        protected $iconCssClass;
        protected $shortTemplate;
        protected $longTemplate;
        protected $shortcode;
        protected $options;
        protected $hasEntities;
        protected $entityType;
        protected $description;
        protected $shortView = 'section-short';
        protected $longView = 'section-long';
        protected $isMediaSelector = false;

        public function __construct() {
            $this->shortTemplate = sprintf(EF_Content_Builder_Views::getViewContent($this->shortView), $this->clientID, $this->cssClass, $this->ID, $this->iconCssClass, $this->name);
            $this->longTemplate = sprintf(EF_Content_Builder_Views::getViewContent($this->longView), $this->cssClass, $this->ID, $this->entityType, $this->iconCssClass, $this->name, $this->description);

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
        }

        public function serialize() {
            return get_object_vars($this);
        }

    }

}