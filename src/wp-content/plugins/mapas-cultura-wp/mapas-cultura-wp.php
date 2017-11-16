<?php
/**
 * Plugin Name:       Mapas da Cultura WP
 * Plugin URI:        https://github.com/culturagovbr/
 * Description:       Plugin para criação de interface listando agentes, espaços e eventos, obtidos através da API do <a target="_blank" href="https://github.com/culturagovbr/mapasculturais">Mapas Culturais</a>
 * Version:           1.0.0
 * Author:            Ricardo Carvalho
 * Author URI:        https://github.com/culturagovbr/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mapas-cultura-wp
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if( ! class_exists('MapasCulturaWP') ) :

	class MapasCulturaWP {

		public function __construct() {

			load_plugin_textdomain( 'mapas-cultura-wp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

			add_action( 'wp_enqueue_scripts', array( $this, 'register_mcwp_styles' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'register_mcwp_scripts' ) );

			add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_mcwp_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_mcwp_scripts' ) );

			// add_filter( 'the_content', array( $this, 'append_post_notification' ) );
			add_action( 'init', array( $this, 'register_mcwp_tinymce_button' ) );
			add_shortcode( 'mapas-cultura-wp', array( $this, 'mcwp_shortcodes' ) );

			setlocale(LC_ALL, "pt_BR", "pt_BR.iso-8859-1", "pt_BR.utf-8", "portuguese");
			date_default_timezone_set('America/Sao_Paulo');

		}

		// Register our public styles
		public function register_mcwp_styles() {
			// if($this->has_shortcode('mapas-cultura-wp')) {
				wp_register_style( 'mcwp-styles', plugins_url( 'mapas-cultura-wp/assets/css/mapas-cultura-wp-main.css' ) );
				wp_enqueue_style( 'mcwp-styles' );
			// }
		}

		// Register our public scripts
		public function register_mcwp_scripts() {

			wp_register_script( 'mcwp-cycle2', plugins_url( 'mapas-cultura-wp/assets/js/jquery.cycle2.js' ) );
			wp_enqueue_script( 'mcwp-cycle2' );

			wp_register_script( 'mcwp-scripts', plugins_url( 'mapas-cultura-wp/assets/js/mapas-cultura-wp-main.js' ) );
			wp_enqueue_script( 'mcwp-scripts' );

		}

		// Register our admin styles
		public function register_admin_mcwp_styles() {

			wp_register_style( 'mcwp-admin-styles', plugins_url( 'mapas-cultura-wp/assets/css/mapas-cultura-wp-admin-main.css' ) );
			wp_enqueue_style( 'mcwp-admin-styles' );

		}

		// Register our admin scripts
		public function register_admin_mcwp_scripts() {

			wp_register_script( 'mcwp-jquery-mask', plugins_url( 'mapas-cultura-wp/assets/js/jquery.mask.min.js' ) );
			wp_register_script( 'mcwp-admin-scripts', plugins_url( 'mapas-cultura-wp/assets/js/mapas-cultura-wp-admin-main.js' ) );
			wp_enqueue_script( 'mcwp-jquery-mask' );
			wp_enqueue_script( 'mcwp-admin-scripts' );

		}

		// Register our button on TinyMCE 
		public function register_mcwp_tinymce_button() {

			//Abort early if the user will never see TinyMCE
			if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') && get_user_option('rich_editing') == 'true')
				return;

	      	//Add a callback to regiser our tinymce plugin   
			add_filter('mce_external_plugins', array( $this, 'mcwp_register_tinymce_plugin' ) ); 

	      	// Add a callback to add our button to the TinyMCE toolbar
			add_filter('mce_buttons', array( $this, 'mcwp_add_tinymce_button' ));

		}

		//This callback registers our plug-in
		function mcwp_register_tinymce_plugin($plugin_array) {
			$plugin_array['mcwp_button'] = plugins_url( 'mapas-cultura-wp/assets/js/mapas-cultura-tinymce-plugin.js' );
			return $plugin_array;
		}

		//This callback adds our button to the toolbar
		function mcwp_add_tinymce_button($buttons) {
            //Add the button ID to the $button array
			$buttons[] = "mcwp_button";
			return $buttons;
		}

		// Set a words limit for long texts
		public function mcwp_limit_desc_text($text, $limit) {
	    	if (str_word_count($text, 0) > $limit) {
	    		$words = str_word_count($text, 2);
	    		$pos = array_keys($words);
	    		$text = substr($text, 0, $pos[$limit]) . '[...]';
	    	}
	    	return $text;
	    }

	    // check the current post for the existence of a short code
	    public function has_shortcode($shortcode = '') {
	        $post_to_check = get_post(get_the_ID());
	        // false because we have to search through the post content first
	        $found = false;
	        // if no short code was provided, return false
	        if (!$shortcode) {
	            return $found;
	        }
	        // check the post content for the short code
	        if ( stripos($post_to_check->post_content, '[' . $shortcode) !== false ) {
	            // we have found the short code
	            $found = true;
	        }
	         
	        // return our final results
	        return $found;
	    }

		public function mcwp_shortcodes($atts) {
			$atts = shortcode_atts( array(
				'url' => 'http://mapas.cultura.gov.br',
				'buscar' => 'space',
				'limite' => '10',
				'layout' => 'cols',
				'cols' => '3',
				'ordem' => 'asc',
				'remover-css' => false
			), $atts );

			$now = date('Y-m-d');
			$last_month = date( 'Y-m-d', mktime (0, 0, 0, date('m')-1, date('d'),  date('Y')) );
			$next_month = date( 'Y-m-d', mktime (0, 0, 0, date('m')+1, date('d'),  date('Y')) );

			switch ($atts['buscar']) {
				case 'space':
					$search_type = 'space';
					// $url = "$atts[url]/api/space/find/?&@select=id,singleUrl,name,subTitle,type,shortDescription,terms,project.name,project.singleUrl,num_sniic,endereco,acessibilidade&@files=(avatar.avatarMedium):url&@page=1&@limit=$atts[limite]&@order=name%20ASC";
					$url = "$atts[url]/api/space/find/?&@select=id,singleUrl,name,subTitle,type,shortDescription,terms,project.name,project.singleUrl,endereco,acessibilidade&@files=(avatar.avatarMedium,header):url&@page=1&@limit=$atts[limite]&@order=name%20ASC";
					break;
				case 'event':
					$search_type = 'event';
					// $url = "$atts[url]/api/agent/find/?&@select=id,singleUrl,name,subTitle,type,shortDescription,terms,project.name,project.singleUrl&@files=(avatar.avatarSmall,avatar.avatarMedium,header):url&@page=4&@limit=$atts[limite]&@order=name%20DESC";
					$url = "$atts[url]/api/event/findByLocation/?&@from=$now&@to=$next_month&@select=id,singleUrl,name,subTitle,type,shortDescription,terms,project.name,project.singleUrl,num_sniic,classificacaoEtaria,project.name,project.singleUrl,occurrences.{*,space.{*}}&@files=(avatar.avatarMedium,header):url&@page=1&@limit=$atts[limite]&@order=name%20ASC";
					break;
				case 'agent':
					$search_type = 'agent';
					$url = "$atts[url]/api/agent/find/?&@select=id,singleUrl,name,subTitle,type,shortDescription,terms,project.name,project.singleUrl,num_sniic&@files=(avatar.avatarBig):url&@page=1&@limit=$atts[limite]&@order=id%20DESC";
					break;
				case 'project':
					$search_type = 'project';
					$url = "$atts[url]/api/project/find/?&@select=id,singleUrl,name,subTitle,type,shortDescription,terms,project.name,project.singleUrl,num_sniic,registrationFrom,registrationTo&@files=(avatar.avatarMedium):url&@page=1&@limit=$atts[limite]&@order=name%20ASC";
					break;
			}
			
			$ch = curl_init();
		    curl_setopt($ch, CURLOPT_URL, $url);
		    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		    $result = curl_exec($ch);
		    if(curl_errno($ch)) {
		        echo 'Curl error: ' . curl_error($ch);
		    }
		    curl_close($ch);

		    return $this->render_data($search_type, $result, $atts['layout'], $atts['cols'], $atts['remover-css']);
		}

		/**
		 * [render_data description]
		 * @param  [String] $type     [description]
		 * @param  [JSON] $data_obj  [description]
		 * @return [Void]           [description]
		 */
		public function render_data($type, $data_obj, $layout, $cols, $remove_css){
			$mcwp_content = '';
			if( $type === 'event'  ) {
				$mcwp_content .= '<div class="box-events '. $layout .' cols-'. $cols .'">';
				foreach ( json_decode($data_obj) as $key => $value ) {
					$mcwp_content .= '<div class="mcwp-item">';
					$mcwp_content .= '<div class="mcwp-figure">';
					$mcwp_content .= '<img src="'. $value->{"@files:header"}->url .'">';
					$mcwp_content .= '<div class="mcwp-date">';
					$mcwp_content .= '<span><img src="'. plugins_url( 'assets/img/clock-circular-outline.png', __FILE__ ) .'">'. strftime('%B %d, %Y', strtotime( $value->occurrences['0']->rule->startsOn )) .'</span>';
					$mcwp_content .= '</div>';
					$mcwp_content .= '</div>';
					$mcwp_content .= '<div class="mcwp-desc">';
					$mcwp_content .= '<h2>'. $value->name .'</h2>';
					$mcwp_content .= '<h3>Linguagens</h3>';
					$mcwp_content .= '<p>'. implode (', ', $value->terms->linguagem) .'</p>';
					$mcwp_content .= '<h3>Local</h3>';
					$mcwp_content .= '<p>'. $value->occurrences['0']->space->name .'</p>';
					$mcwp_content .= '</div>';
					$mcwp_content .= '<div class="mcwp-meta">';
					$mcwp_content .= '<span class="age-rating">'. $value->classificacaoEtaria .'</span>';
					$mcwp_content .= '<a href="'. $value->singleUrl .'" class="more-info" target="_blank">Mais informações</a>';
					$mcwp_content .= '</div>';
					$mcwp_content .= '</div>';
				}
				$mcwp_content .= '<div class="mcwp-carousel-pager"></div>';
				$mcwp_content .= '</div>';

			} else if( $type === 'agent'  ){

				$mcwp_content .= '<div class="box-agents '. $layout .' cols-'. $cols .'">';
				foreach ( json_decode($data_obj) as $key => $value ) {
					$mcwp_content .= '<div class="mcwp-item">';
					$mcwp_content .= '<div class="img-holder">';
					if ( ( $value->{'@files:avatar.avatarBig'}->url ) ){
						$mcwp_content .= '<img src="'. $value->{'@files:avatar.avatarBig'}->url .'">';
					} else {
						$mcwp_content .= '<img src="'. plugins_url( 'assets/img/users.png', __FILE__ ) .'">';;
					}
					$mcwp_content .= '</div>';
					$mcwp_content .= '<div class="mcwp-desc">';
					$mcwp_content .= '<h2>'. $value->name .'</h2>';
					$mcwp_content .= '<a href="'. $value->singleUrl .'" class="more-info" target="_blank">Abrir perfil</a>';
					$mcwp_content .= '</div>';
					$mcwp_content .= '</div>';
				}
				$mcwp_content .= '</div>';

			} else if( $type === 'space'  ){

				$mcwp_content .= '<div class="box-spaces '. $layout .' cols-'. $cols .'">';
				foreach ( json_decode($data_obj) as $key => $value ) {
					$mcwp_content .= '<div class="mcwp-item">';
					$mcwp_content .= '<h2><a href="'. $value->singleUrl .'" class="more-info" target="_blank">'. $value->name .'</a></h2>';
					$mcwp_content .= '<div class="mcwp-desc">';
					if ( ( $value->{'@files:header'}->url ) ){
						$mcwp_content .= '<img src="'. $value->{'@files:header'}->url .'">';
						$mcwp_content .= '<p>'. $value->shortDescription .'</p>';
					} else {
						$mcwp_content .= '<p class="no-img">'. $value->shortDescription .'</p>';
					}
					$mcwp_content .= '</div>';
					$mcwp_content .= '<div class="mcwp-meta">';
					if( $value->type->name ){
						$mcwp_content .= '<p><b>Tipo:</b> '. $value->type->name .'</p>';
					}
					if( !empty( $value->terms->area ) ){
						$mcwp_content .= '<p><b>Área de atuação:</b> '.  implode (', ', $value->terms->area) .'</p>';
					}
					if( !empty( $value->terms->tag ) ){
						$mcwp_content .= '<p><b>Tags:</b> '. implode (', ', $value->terms->tag) .'</p>';
					}
					if( $value->endereco ){
						$mcwp_content .= '<p><b>Endereço:</b> '. $value->endereco .'</p>';
					}
					if( $value->acessibilidade ){
						$mcwp_content .= '<p><b>Acessibilidade:</b> '. $value->acessibilidade .'</p>';
					}
					$mcwp_content .= '</div>';
					$mcwp_content .= '</div>';
				}
				$mcwp_content .= '</div>';

			}
			return $mcwp_content;
		}

	}

	// Initialize our plugin
	$mcwp = new MapasCulturaWP();

endif; // For class_exists