<?php
/*
Plugin Name: Rede Livre - Widget Busca Avançada
Plugin URI: http://apolinariopassos.com.br/dev/
Description: Um plugin que cria uma busca avançada por categorias, taxonomias e intervalo de datas
Version: 0.1
Author: Apolinário
Author URI: http://apolinariopassos.com.br/
License: GPL3
*/
?>
<?php
class busca_avancada extends WP_Widget{
	//Constructor
	function busca_avancada(){
		parent::WP_Widget(false, $name = __('Busca Avançada') );
	}

	//Widget Form Creation
	/*function form($instance) {
		// Check values
		if( $instance) {
		     $title = esc_attr($instance['title']);
		     $text = esc_attr($instance['text']);
		     $textarea = esc_textarea($instance['textarea']);
		} else {
		     $title = '';
		     $text = '';
		     $textarea = '';
		}
		?>

		<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title', 'wp_widget_plugin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>

		<p>
		<label for="<?php echo $this->get_field_id('text'); ?>"><?php _e('Text:', 'wp_widget_plugin'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>" type="text" value="<?php echo $text; ?>" />
		</p>

		<p>
		<label for="<?php echo $this->get_field_id('textarea'); ?>"><?php _e('Textarea:', 'wp_widget_plugin'); ?></label>
		<textarea class="widefat" id="<?php echo $this->get_field_id('textarea'); ?>" name="<?php echo $this->get_field_name('textarea'); ?>"><?php echo $textarea; ?></textarea>
		</p>
	<?php }
	//End of Form Creation


	//Widget Update
	function update($new_instance, $old_instance) {
	    $instance = $old_instance;
	    // Fields
	    $instance['title'] = strip_tags($new_instance['title']);
	    $instance['text'] = strip_tags($new_instance['text']);
	    $instance['textarea'] = strip_tags($new_instance['textarea']);
	    return $instance;
	}
	//End of Update
	*/
	//Widget Display
	function widget($args, $instance) {

		extract( $args );
		// these are the widget options
		if(isset($instance['title']))
			$title = apply_filters('widget_title', $instance['title']);
		if(isset($instance['text']))
			$text = $instance['text'];
		if(isset($instance['textarea']))
			$textarea = $instance['textarea'];
		echo '<div class="widget">';
		echo '<h2>Busca Avançada</h2>';
		// Display the widget
		echo '<div class="widget-busca-avancada wp_widget_plugin_box"><form>';
		if(isset($_GET['s']))
			echo '<input class="busca-texto" type="text" name="s" placeholder="digite aqui a sua busca" value="'.$_GET['s'].'">';
		else
			echo '<input class="busca-texto" type="text" name="s" placeholder="digite aqui a sua busca">';
		echo '<div class="filtro-data">	';
			echo '<h4>Filtrar por período</h4>';
			if(isset($_GET['date-from']))
				echo '<input type="text" class="from" name="date-from" placeholder="data inicial" value="'.$_GET['date-from'].'">';
			else
				echo '<input type="text" class="from" name="date-from" placeholder="data inicial">';
			if(isset($_GET['date-to']))
				echo '<input type="text" class="to" name="date-to" placeholder="data final" value="'.$_GET['date-to'].'">';
			else
				echo '<input type="text" class="to" name="date-to" placeholder="data final">';
		echo '</div>';
		echo '<div class="clear"></div>';
		echo '<div class="filtro-categorias">';
			echo '<div id="busca-avancada-categorias">';
				$taxonomies = get_taxonomies();
				foreach($taxonomies as $taxonomy):
					$taxonomy_list = get_terms($taxonomy);
					if(!empty($taxonomy_list)):
						$the_tax = get_taxonomy($taxonomy);
						echo '<h3>'.$the_tax->labels->name.'</h3>';
						echo '<div>'; 
							foreach($taxonomy_list as $single_taxonomy):
								if($single_taxonomy->parent != 0){
									if(is_category($single_taxonomy->slug) || is_tag($single_taxonomy->slug) || is_tax($taxonomy,$single_taxonomy->slug) || (isset($_GET['selected-'.$taxonomy]) && in_array($single_taxonomy->slug,$_GET['selected-'.$taxonomy])))
										echo '<div class="tag-listed child"><input type="checkbox" checked name="selected-'.$taxonomy.'[]" id="'.$single_taxonomy->slug.'" value="'.$single_taxonomy->slug.'"><label for="'.$single_taxonomy->slug.'">'.$single_taxonomy->name.'</label></div>';
									else
										echo '<div class="tag-listed child"><input type="checkbox" name="selected-'.$taxonomy.'[]" id="'.$single_taxonomy->slug.'" value="'.$single_taxonomy->slug.'"><label for="'.$single_taxonomy->slug.'">'.$single_taxonomy->name.'</label></div>';
								}else{
									if(is_category($single_taxonomy->slug) || is_tag($single_taxonomy->slug) || is_tax($taxonomy,$single_taxonomy->slug) || (isset($_GET['selected-'.$taxonomy]) && in_array($single_taxonomy->slug,$_GET['selected-'.$taxonomy])))
										echo '<div class="tag-listed"><input type="checkbox" checked name="selected-'.$taxonomy.'[]" id="'.$single_taxonomy->slug.'" value="'.$single_taxonomy->slug.'"><label for="'.$single_taxonomy->slug.'">'.$single_taxonomy->name.'</label></div>';
									else
										echo '<div class="tag-listed"><input type="checkbox" name="selected-'.$taxonomy.'[]" id="'.$single_taxonomy->slug.'" value="'.$single_taxonomy->slug.'"><label for="'.$single_taxonomy->slug.'">'.$single_taxonomy->name.'</label></div>';
								}
							endforeach;
						echo '</div>';
					endif;
				endforeach;
			echo '</div>';
		echo '</div>';
		echo '<input type="submit" value="Pesquisar">';
	    echo '</form><div class="clear"></div></div>';
	    echo '</div>';
	    echo $after_widget;
	}
	//End of Display
}

	//add_filter( 'template_include', 'get_search_template' );
function SearchFilter($query) {
    // If 's' request variable is set but empty
    if (isset($_GET['s']) && empty($_GET['s']) && $query->is_main_query()){
        $query->is_search = true;
        $query->is_home = false;
    }
    return $query;
}
if(!is_admin())
	add_filter('pre_get_posts','SearchFilter');

function apply_search_filters($search_query) {	
	if(is_search()):
		if(isset($_GET['date-from']) && $_GET['date-from'] != ''):
			$date_from = $_GET['date-from'];
			$search_query .= " AND (wp_posts.post_date >= '".$date_from." 00:00:00')"; 
		endif;
		if(isset($_GET['date-to']) && $_GET['date-to'] != ''):
			$date_to = $_GET['date-to'];
			$search_query .= " AND (wp_posts.post_date <= '".$date_to." 23:59:59') ";
		endif;
		return $search_query;
	else:
		return $search_query;
	endif;

}
if(!is_admin())
	add_filter( 'posts_where', 'apply_search_filters');

function exclude_taxonomies($query){
	if($query->is_search() && $query->is_main_query()):
		$taxonomies = get_taxonomies();
				foreach($taxonomies as $taxonomy):
					$taxonomy_list = get_terms($taxonomy);
					if(!empty($taxonomy_list)):
						$taxonomy_query = 'selected-'.$taxonomy;
						if(isset($_GET[$taxonomy_query]) && $_GET[$taxonomy_query] != ''):
								$array_taxonomies = $_GET[$taxonomy_query];
								$len = count($array_taxonomies);
								$i=0;
								$lista_taxonomias = '';
								foreach($array_taxonomies as $taxonomy_item):
									if($i == $len-1) 
										$lista_taxonomias .= $taxonomy_item;
									else
										$lista_taxonomias .= $taxonomy_item.',';
									$i++;
								endforeach;
								if($taxonomy == 'category')
									$query->set('category_name',$lista_taxonomias);
								else if($taxonomy == 'post_tag')
									$query->set('tag',$lista_taxonomias);
								else
									$query->set($taxonomy,$lista_taxonomias);
						endif;
					endif;
				endforeach;
	endif;
}
if(!is_admin())
	add_action( 'pre_get_posts', 'exclude_taxonomies' );

	function enqueue_scripts(){
		wp_enqueue_style('jquery-ui-1.10.3-custom',plugins_url() . '/BuscaAvancada/css/jquery-ui-1.10.3.custom.min.css',false,false,false);
		wp_enqueue_style('theme-style',plugins_url() . '/BuscaAvancada/css/style.css',false,false,false);
		wp_enqueue_script('jquery');
		wp_enqueue_script('jquery-ui-core');  
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_script('jquery-ui-accordion');
		wp_enqueue_script('scripts-busca-avancada',plugins_url() . '/BuscaAvancada/js/scripts.js',array('jquery','jquery-ui-core','jquery-ui-datepicker'),false,false);
	}
	if(!is_admin())
	add_action( 'wp_enqueue_scripts', 'enqueue_scripts' );

//Register Widget
function register_busca_avancada(){
	register_widget('busca_avancada');
}
add_action('widgets_init','register_busca_avancada');
?>