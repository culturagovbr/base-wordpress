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

if (!class_exists('EF_Content_Builder_Views')) {

    class EF_Content_Builder_Views {
        
        public static function getViewContent($view){
            return @file_get_contents(EF_CONTENT_BUILDER_DIR . "views/$view.tpl");
        }

    }

}