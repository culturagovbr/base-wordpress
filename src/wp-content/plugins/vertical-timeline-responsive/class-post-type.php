<?php


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Plugin public class
 **/
if ( ! class_exists( 'VTLR_POST_TYPE' ) ) {

	class VTLR_POST_TYPE {

		public function __construct() {
			//
		}
		
		/*
		 * Initialize the class and start calling our hooks and filters
		 * @since 1.0.0
		 */
		public function init() {
			add_action ( 'init', array ( $this, 'register_post_type' ) );
			add_action ( 'init', array ( $this, 'create_vtlr_taxonomies' ) );
			add_action ( 'wp_enqueue_scripts', array ( $this, 'enqueue_scripts' ) );
		}
		
		/*
		 * Register the post types and taxonomies
		 * @since 1.0.0
		 */
		public function register_post_type() {
			// Slider post type
			
			
			
			
			// Slide post type
			$labels = array (
				'name'					=> _x( 'V Timeline', 'post type general name', 'vtlr' ),
				'singular_name'			=> _x( 'V Timeline', 'post type singular name', 'vtlr' ),
				'menu_name'				=> _x( 'V Timeline', 'admin menu', 'vtlr' ),
				'name_admin_bar'		=> _x( 'V Timeline', 'add new on admin bar', 'vtlr' ),
				'add_new'				=> _x( 'Add New', 'Timeline', 'vtlr' ),
				'add_new_item'			=> __( 'Add New Timeline', 'vtlr' ),
				'new_item'				=> __( 'New Timeline', 'vtlr' ),
				'edit_item'				=> __( 'Edit Timeline', 'vtlr' ),
				'view_item'				=> __( 'View Timeline', 'vtlr' ),
				'all_items'				=> __( 'All Timelines', 'vtlr' ),
				'search_items'			=> __( 'Search Timeline', 'vtlr' ),
				'parent_item_colon'		=> __( 'Parent Timeline:', 'vtlr' ),
				'not_found'				=> __( 'No timelines found.', 'vtlr' ),
				'not_found_in_trash'	=> __( 'No timeline found in Trash.', 'vtlr' )
			);
			$args = array(
				'labels'				=> $labels,
				'description'			=> __( 'Description.', 'vtlr' ),
				'public'				=> true,
				'publicly_queryable'	=> false,
				'show_ui'				=> true,
				'show_in_menu'			=> true,
				'query_var'				=> true,
				'rewrite'				=> array( 'slug' => 'vtlr' ),
				'capability_type'		=> 'post',
				'menu_icon'				=> 'dashicons-format-image',
				'has_archive'			=> true,
				'hierarchical'			=> false,
				'menu_position'			=> 35,
				'supports'				=> array ( 'title' )
			);
			register_post_type( 'vtlr', $args );

		}
		
	// hook into the init action and call create_book_taxonomies when it fires


// create two taxonomies, genres and writers for the post type "book"
public function create_vtlr_taxonomies() {
	
	// $labels = array(
	// 	'name'              => _x( 'Slide Categories', 'taxonomy general name', 'clfsfs' ),
	// 	'singular_name'     => _x( 'Slide Category', 'taxonomy singular name', 'clfsfs' ),
	// 	'search_items'      => __( 'Search Categories', 'clfsfs' ),
	// 	'all_items'         => __( 'All Categories', 'clfsfs' ),
	// 	'parent_item'       => __( 'Parent Category', 'clfsfs' ),
	// 	'parent_item_colon' => __( 'Parent Category:', 'clfsfs' ),
	// 	'edit_item'         => __( 'Edit Category', 'clfsfs' ),
	// 	'update_item'       => __( 'Update Category', 'clfsfs' ),
	// 	'add_new_item'      => __( 'Add New Category', 'clfsfs' ),
	// 	'new_item_name'     => __( 'New Category Name', 'clfsfs' ),
	// 	'menu_name'         => __( 'Category', 'clfsfs' ),
	// );

	// $args = array(
	// 	'hierarchical'      => true,
	// 	'labels'            => $labels,
	// 	'show_ui'           => true,
	// 	'show_admin_column' => true,
	// 	'query_var'         => true,
	// 	'rewrite'           => array( 'slug' => 'fsfscat' ),
	// );

	// register_taxonomy( 'fsfscat', 'slide', $args );

	

	
}
		/*
		 * Enqueue styles and scripts
		 * @since 1.0.0
		 */
		public function enqueue_scripts() {
			
			wp_enqueue_style ( 'vtlr-style', VTIMERES_PLUGIN_URL . 'frontend/assets/css/style.css' );
			wp_enqueue_style ( 'dashicons' );
			//wp_enqueue_script ( 'vtlr-script',  VTIMERES_PLUGIN_URL . 'frontend/assets/js/ffw.js', array ( 'jquery' ), '1.0.0', true );
			//wp_enqueue_script ( 'vtlr-init-script',  SCHSLIDE_PLUGIN_URL . 'frontend/assets/js/slider-init.js', array ( 'jquery' ), '1.0.0', true );
			
		}
		
		/*
		 * Register the widgets
		 * @since 1.0.0
		 */
		public function widgets_init() {
			
		}

	}
	
}