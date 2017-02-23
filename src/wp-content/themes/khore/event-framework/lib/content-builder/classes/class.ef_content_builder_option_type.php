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

if (!class_exists('EF_Content_Builder_Option_Type')) {

    final class EF_Content_Builder_Option_Type {

        const TEXT = 'text';
        const TEXTAREA = 'textarea';
        const CHECKBOX = 'checkbox';
        const COLOR = 'color';
        const SELECT = 'select';
        const IMAGE = 'image';
        const DATETIME = 'datetime';
        const FONT = 'font';
        const WYSIWYG = 'wysiwyg';
        const HIDDEN = 'hidden';

    }

}