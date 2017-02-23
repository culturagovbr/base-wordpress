<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Widgets File
 *
 * Handles all widget functionalities of plugin
 *
 * @package Event Framework
 * @since 1.0.0
 */

$multievent_sidebar_args = array(
						'name'          => __( 'Multievent Page', 'dxef' ),
						'id'            => 'multievent-page-sidebar',
						'description'   => __('Widgets in this area will be shown on the right hand side of multievent page.', 'dxef'),
					    'class'         => 'multievent-page-sidebar',
						'before_title'  => '',
						'after_title'   => ''
					);
register_sidebar( $multievent_sidebar_args );