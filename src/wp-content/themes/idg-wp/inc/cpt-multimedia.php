<?php
/**
 * Custom post type = multimedia
 *
 */
function multimedia_custom_type() {
	$labels = array(
		'name'           		=> 'Multimídia',
		'singular_name'     	=> 'Multimídia',
		/*'add_new'         		=> _x('Criar nova galeria', 'como chegamos la'),
		'add_new_item'       	=> __('Criar nova galeria'),
		'edit_item'       		=> __('Editar galeria'),
		'new_item'         		=> __('Nova'),
		'all_items'       		=> __('Todos as galerias'),
		'view_item'       		=> __('Visualizar galeria'),
		'search_items'      	=> __('Pesquisar por galeria'),*/
		'not_found'       		=> __('Nada encontrado'),
		'not_found_in_trash'   	=> __('Nada encontrado na lixeira'),
		'parent_item_colon'   	=> '',
		'menu_name'       		=> 'Multimídia'
	);

	$args = array(
		'labels'         		=> $labels,
		'public'         		=> true,
		'publicly_queryable'  	=> true,
		'show_ui'        		=> true,
		'show_in_menu'       	=> true,
		'show_in_nav_menus'   	=> true,
		'query_var'       		=> true,
		'capability_type'     	=> 'post',
		'has_archive'       	=> true,
		'hierarchical'       	=> false,
		'menu_position'     	=>  _( 5 ),
		'menu_icon'				=> 'dashicons-format-gallery',
		'supports'				=> array( 'title', 'thumbnail', 'excerpt' )
	);
	register_post_type( 'multimedia', $args );
	// flush_rewrite_rules( false );
}
add_action( 'init', 'multimedia_custom_type' );
