<?php

class Oportunidades
{
	function __construct()
	{
		$this->_customs = array(
				
				
		);
		
		add_action('init', array($this, 'init'));
		add_action('init', array($this, 'rewrite_rules'));
		add_action('template_redirect', array($this, 'form'));
		add_action('wp_ajax_resetpass', array($this, 'form'));
		add_action('wp_ajax_nopriv_resetpass', array($this, 'form'));
		add_filter('query_vars', array($this, 'print_variables'));
		//add_filter('archive_template', array($this, 'archiveTemplate'));
		//add_filter('single_template', array($this, 'singleTemplate'));
		add_action( 'save_post', array( $this, 'save' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts') );
		add_action( 'wp_enqueue_scripts', array($this, 'css'));
		add_action( 'wp_enqueue_scripts', array($this, 'javascript'));
// 		add_action( 'add_meta_boxes', array($this, 'oportunidade_custom_meta') );
		add_action( 'wp_loaded', array($this, 'check_oportunidade_rewrite' ));
		
	}
	
	function init()
	{
		$this->Add_custom_Post();
	
	}
	
	function Add_custom_Post()
	{
		$labels = array
		(
				'name' => __('Oportunidade','rede-cultura-viva'),
				'singular_name' => __('Oportunidade','rede-cultura-viva'),
				'add_new' => __('Nova Oportunidade','rede-cultura-viva'),
				'add_new_item' => __('Adicionar nova Oportunidade','rede-cultura-viva'),
				'edit_item' => __('Editar Oportunidade','rede-cultura-viva'),
				'new_item' => __('Nova Oportunidade','rede-cultura-viva'),
				'view_item' => __('Ver Oportunidade','rede-cultura-viva'),
				'search_items' => __('Procurar por uma Oportunidade','rede-cultura-viva'),
				'not_found' =>  __('Oportunidade não encontrada','rede-cultura-viva'),
				'not_found_in_trash' => __('Oportunidade não encontrada na lixeira','rede-cultura-viva'),
				'parent_item_colon' => '',
				'menu_name' => __('Oportunidade','rede-cultura-viva')
	
		);
	
		$args = array
		(
				'label' => __('Oportunidade','rede-cultura-viva'),
				'labels' => $labels,
				'description' => __('Oportunidades','rede-cultura-viva'),
				'public' => true,
				'publicly_queryable' => true, // public
				//'exclude_from_search' => '', // public
				'show_ui' => true, // public
				'show_in_menu' => true,
				'menu_position' => 5,
				// 'menu_icon' => '',
				'capability_type' => array('post','posts'),
				'map_meta_cap' => true,
				'hierarchical' => false,
				'supports' => array('title', 'editor', 'author', 'excerpt', 'trackbacks','thumbnail', 'revisions', 'comments'),
				'register_meta_box_cb' => array($this, 'oportunidade_custom_meta'), // função para chamar na edição
				'taxonomies' => array(), // Taxionomias já existentes relaciondas, vamos criar e registrar na sequência
				'permalink_epmask' => 'EP_PERMALINK ',
				'has_archive' => true, // Opção de arquivamento por slug
				'rewrite' => true,
				'query_var' => true,
				'can_export' => true//, // veja abaixo
				//'show_in_nav_menus' => '', // public
				//'_builtin' => '', // Core
				//'_edit_link' => '' // Core
	
		);
	
		register_post_type("oportunidade", $args);
	}

	function oportunidade_custom_meta()
	{
		add_meta_box("oportunidade_meta", __("Detalhes da Oportunidade", 'oportunidade'), array($this, 'oportunidade_meta'), 'oportunidade', 'side', 'default');
		//add_meta_box("second_image_meta", __("Imagens da Oportunidade", 'oportunidade'), array($this, 'second_image_meta'), 'oportunidade', 'side', 'default');
	}
	
	protected $_customs = array();
	
	function getFields()
	{
		$post = array(
			'post_title' => array(
				'slug' => 'post_title',
				'title' => __('Título', 'oportunidade'),
				'tip' => '',
				'required' => true,
				'buildin' => true
			),
			'post_content' => array(
				'slug' => 'post_content',
				'title' => __('Descrição', 'oportunidade'),
				'tip' => __('Maximum 2000 characters', 'oportunidade'),
				'required' => true,
				'type' => 'wp_editor',
				//'type' => 'textarea',
				'rows' => 10,
				'buildin' => true
			),
		);
		
		return array_merge($post, $this->_customs);
	}
	
	/**
	 * Para padronizar dados da importação/formulário
	 */
	function get_custom_field($field, $custom)
	{
		$fields = $this->getFields();
		$dado = '';
		if(array_key_exists($field, $fields))
		{
			$slug = $fields[$field]['slug'];
			$custom_field = $fields[$field]['custom_field'];
			
			if(array_key_exists($slug, $custom))
			{
				$dado = array_pop($custom[$slug]);
			}
			elseif(array_key_exists($custom_field, $custom))
			{
				$dado = array_pop($custom[$custom_field]);
			}
		}
		return $dado;
	}
	
	function oportunidade_meta()
	{
		global $post;
		
		$custom = get_post_custom($post->ID);
		if(!is_array($custom)) $custom = array();
		
		$disable_edicao = '';
		
		/*if (
				!($post->post_status == 'draft' ||
				$post->post_status == 'auto-draft' ||
				$post->post_status == 'pending')
		)
		{
			$disable_edicao = 'readonly="readonly"';
		}*/
		
		wp_nonce_field( 'oportunidade_meta_inner_custom_box', 'oportunidade_meta_inner_custom_box_nonce' );
		
		foreach ($this->_customs as $key => $campo )
		{
			$slug = $campo['slug'];
			$type = array_key_exists('type', $campo) ? $campo['type'] : 'input';
			$dado = '';
			
			if(array_key_exists($slug, $custom))
			{
				$dado = array_pop($custom[$slug]);
			}
			elseif(array_key_exists($campo['custom_field'], $custom))
			{
				$dado = array_pop($custom[$campo['custom_field']]);
			}
			
			if($type == 'dropdown-meses-anos')
			{
				$_REQUEST[$campo['slug'].'-anos'] = array_key_exists("$slug-anos", $custom) ? array_pop($custom["$slug-anos"]).__(" anos", 'oportunidade') : '';
				if(array_key_exists("$slug-meses", $custom))
				{
					$_REQUEST[$campo['slug'].'-meses'] = array_pop($custom["$slug-meses"]).__(" meses", 'oportunidade');
				}
			}
			$_REQUEST[$campo['slug']] = $dado;
			$this->print_field($campo);
			
			if(array_key_exists($slug, $custom)) unset($custom[$slug]);
		}
		
		foreach ($custom as $key => $value)
		{
			$input_pos = strpos($key, '_input'); 
			if( $input_pos > 0 ) // input text
			{
				$taxonomy =  substr($key, 1, strpos($key, "_", 1) - 1);
				$taxonomy_obj = get_taxonomy($taxonomy);
				
				$term_id = substr($key, strlen($taxonomy) + 2, $input_pos - (strlen($taxonomy) + 2));
			
				$term = get_term(intval( $term_id ), $taxonomy);
				
				$dado = array_pop($value);
				
				?>
				<p>
					<label for="<?php echo $term->slug; ?>" class="<?php echo 'label_'.$term->slug; ?>"><?php echo $taxonomy_obj->labels->name." - ".str_replace('#input#', '', $term->name) ?>:</label>
					<input <?php echo $disable_edicao ?> id="<?php echo $term->slug; ?>"
						name="<?php echo $term->slug; ?>"
						class="<?php echo $term->slug; ?>"
						value="<?php echo $dado; ?>" />
				</p>
				<?php
				
			}
		}
		
		
	}
	
	function second_image_meta($post)
	{
		$stored_meta = get_post_meta( $post->ID, 'thumbnail2', true)
		?>
		<p>
		    <label for="meta-image" class="oportunidade-second-image-meta"><?php _e( 'Segunda Imagem', 'oportunidade' )?></label>
		    <input type="text" name="thumbnail2" id="meta-image" value="<?php if ( isset ( $stored_meta ) ) echo $stored_meta; ?>" />
		    <input type="button" id="meta-image-button" class="button" value="<?php _e( 'Choose or Upload an Image', 'oportunidade' )?>" />
		</p>
		<?php
		$stored_meta = get_post_meta( $post->ID, 'thumbnail3', true)
		?>
		<p>
		    <label for="meta-image" class="oportunidade-second-image-meta"><?php _e( 'Terceira Imagem', 'oportunidade' )?></label>
		    <input type="text" name="thumbnail3" id="meta-image" value="<?php if ( isset ( $stored_meta ) ) echo $stored_meta; ?>" />
		    <input type="button" id="meta-image-button" class="button" value="<?php _e( 'Choose or Upload an Image', 'oportunidade' )?>" />
		</p>
		<?php
		$stored_meta = get_post_meta( $post->ID, 'thumbnail4', true)
		?>
		<p>
		    <label for="meta-image" class="oportunidade-second-image-meta"><?php _e( 'Foto do Responsável', 'oportunidade' )?></label>
		    <input type="text" name="thumbnail4" id="meta-image" value="<?php if ( isset ( $stored_meta ) ) echo $stored_meta; ?>" />
		    <input type="button" id="meta-image-button" class="button" value="<?php _e( 'Choose or Upload an Image', 'oportunidade' )?>" />
		</p>
		<?php
		
	}
	
	const NEW_OPORTUNIDADE_PAGE = 'nova-oportunidade';
	
	function print_variables($public_query_vars) {
		$public_query_vars[] = self::NEW_OPORTUNIDADE_PAGE;
		return $public_query_vars;
	}
	
	function check_oportunidade_rewrite() {
		$rules = get_option( 'rewrite_rules' );
		$found = false;
		if(is_array($rules))
		{
			foreach ($rules as $rule)
			{
				if(strpos($rule, 'oportunidade') !== false)
				{
					$found = true;
					break;
				}
			}
			if ( ! $found ) {
				global $wp_rewrite; $wp_rewrite->flush_rules();
			}
		}
	}
	
	function rewrite_rules()
	{
		add_rewrite_rule('^'.self::NEW_OPORTUNIDADE_PAGE.'(.*)', 'index.php?'.self::NEW_OPORTUNIDADE_PAGE.'=true$matches[1]', 'top');
	}
	
	function form()
	{
		if(get_query_var(self::NEW_OPORTUNIDADE_PAGE) == true)
		{
			wp_enqueue_script('jquery-ui-datepicker-ptbr', get_template_directory_uri() . '/inc/oportunidades/js/jquery.ui.datepicker-pt-BR.js', array('jquery-ui-datepicker'));
			wp_enqueue_script('date-scripts', get_template_directory_uri() . '/inc/oportunidades/js/date_scripts.js', array( 'jquery-ui-datepicker-ptbr'));
			wp_enqueue_script('new-oportunidade', get_template_directory_uri().'/inc/oportunidades/js/new-oportunidade.js', array( 'jquery'));
			wp_localize_script( 'new-oportunidade', 'new_oportunidade',
					array(
							'error' => __( 'Os arquivos permitidos são: *.', 'oportunidade' ),
							'exts' => explode(' ', get_site_option( 'upload_filetypes', '' )),
					)
			);
			wp_enqueue_script( 'meta-box-image' );
			
			
			get_header();
			$file_path = get_stylesheet_directory() . '/new-oportunidade.php';
			if(file_exists($file_path))
			{
				include $file_path;
			}
			else
			{
				include dirname(__FILE__) . '/new-oportunidade.php';;
			}
			get_footer();
			exit();
		}
	}
	
	/**
	 * Default post information to use when populating the "Write Post" form customized for sulution.
	 *
	 * @since 2.0.0
	 *
	 * @param string $post_type A post type string, defaults to 'post'.
	 * @return WP_Post Post object containing all the default post data as attributes
	 */
	function get_default_post_to_edit( $post_type = 'oportunidade', $create_in_db = false ) {
		global $wpdb;
	
		$post_title = '';
		if ( !empty( $_REQUEST['post_title'] ) )
			$post_title = esc_html( stripslashes( $_REQUEST['post_title'] ));
	
		$post_content = '';
		if ( !empty( $_REQUEST['content'] ) )
			$post_content = esc_html( stripslashes( $_REQUEST['content'] ));
	
		$post_excerpt = '';
		if ( !empty( $_REQUEST['excerpt'] ) )
			$post_excerpt = esc_html( stripslashes( $_REQUEST['excerpt'] ));
	
		if ( $create_in_db ) {
			$post_id = wp_insert_post( array( 'post_title' => __( 'Auto Draft' ), 'post_type' => $post_type, 'post_status' => 'pending' ) );
			$post = get_post( $post_id );
			if ( current_theme_supports( 'post-formats' ) && post_type_supports( $post->post_type, 'post-formats' ) && get_option( 'default_post_format' ) )
				set_post_format( $post, get_option( 'default_post_format' ) );
		} else {
			$post = new stdClass;
			$post->ID = 0;
			$post->post_author = '';
			$post->post_date = '';
			$post->post_date_gmt = '';
			$post->post_password = '';
			$post->post_type = $post_type;
			$post->post_status = 'pending';
			$post->to_ping = '';
			$post->pinged = '';
			$post->comment_status = get_option( 'default_comment_status' );
			$post->ping_status = get_option( 'default_ping_status' );
			$post->post_pingback = get_option( 'default_pingback_flag' );
			$post->post_category = get_option( 'default_category' );
			$post->page_template = 'default';
			$post->post_parent = 0;
			$post->menu_order = 0;
			$post = new WP_Post( $post );
		}
	
		$post->post_content = apply_filters( 'default_content', $post_content, $post );
		$post->post_title   = apply_filters( 'default_title',   $post_title, $post   );
		$post->post_excerpt = apply_filters( 'default_excerpt', $post_excerpt, $post );
		$post->post_name = '';
	
		return $post;
	}
	
	/**
	 * Inclui os arquivos do tema relacionados com
	 * a listagem de oportunidades e retorna o template
	 * a ser usado.
	 *
	 * @param string $archiveTemplate
	 * @return string
	 */
	public function archiveTemplate($archiveTemplate)
	{
		global $post;
	
		if (get_post_type($post) == "oportunidade" || is_post_type_archive('oportunidade'))
		{
			if(file_exists(get_stylesheet_directory()."/archive-oportunidade.php"))
			{
				$archive_template = get_stylesheet_directory()."/archive-oportunidade.php";
			}
			else
			{
				$archiveTemplate = $this->themeFilePath('archive-oportunidade.php');
			}
		}
	
		return $archiveTemplate;
	}
	
	/**
	 * Inclui os arquivos do tema relacionados com
	 * a página de uma oportunidade e retorna o template
	 * a ser usado.
	 *
	 * @param string $singleTemplate
	 * @return string
	 */
	public function singleTemplate($singleTemplate)
	{
		global $post;
	
		if (get_post_type($post) == "oportunidade" || is_post_type_archive('oportunidade'))
		{
			if(file_exists(get_stylesheet_directory()."/single-oportunidade.php"))
			{
				$singleTemplate = get_stylesheet_directory()."/single-oportunidade.php";
			}
			else
			{
				$singleTemplate = $this->themeFilePath('single-oportunidade.php');
			}
		}
	
		return $singleTemplate;
	}
	
	/**
	 * Save the meta when the post is saved.
	 *
	 * @param int $post_id The ID of the post being saved.
	 */
	public function save( $post_id )
	{
		/*
		 * We need to verify this came from the our screen and with proper authorization,
		* because save_post can be triggered at other times.
		*/
		
		// Check if our nonce is set.
		if ( ! isset( $_POST['oportunidade_meta_inner_custom_box_nonce'] ) )
		{
			return $post_id;
		}
		
		$nonce = $_POST['oportunidade_meta_inner_custom_box_nonce'];
		
		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'oportunidade_meta_inner_custom_box' ) )
		{
			return $post_id;
		}
		
		// If this is an autosave, our form has not been submitted,
		//     so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
		{
			return $post_id;
		}
	
		// Check the user's permissions.
		if ( 'oportunidade' == $_POST['post_type'] )
		{
			if ( ! current_user_can( 'edit_oportunidade', $post_id ) )
			{
				return $post_id;
			}
		}
		else
		{
			return $post_id;
		}
	
		/* OK, its safe for us to save the data now. */
		Oportunidades::save_fields($post_id);
		
		if(array_key_exists('thumbnail2', $_POST))
		{
			update_post_meta($post_id, 'thumbnail2', $_POST['thumbnail2']); //TODO more sec
		}
		if(array_key_exists('thumbnail3', $_POST))
		{
			update_post_meta($post_id, 'thumbnail3', $_POST['thumbnail3']); //TODO more sec
		}
		if(array_key_exists('thumbnail4', $_POST))
		{
			update_post_meta($post_id, 'thumbnail4', $_POST['thumbnail4']); //TODO more sec
		}
		
		if(function_exists('mapasdevista_save_postdata'))
		{
			mapasdevista_save_postdata($post_id);
		}
		
	}
	
	/**
	 * Loads the image management javascript
	 */
	function admin_enqueue_scripts()
	{
		global $typenow;
		if( $typenow == 'oportunidade' || $typenow == 'post' )
		{
			wp_enqueue_media();
	
			// Registers and enqueues the required javascript.
			wp_register_script( 'meta-box-image', get_template_directory_uri() . '/inc/oportunidades/js/meta-box-image.js', array( 'jquery' ) );
			wp_localize_script( 'meta-box-image', 'meta_image',
				array(
					'title' => __( 'Choose or Upload an Image', 'oportunidade' ),
					'button' => __( 'Use this image', 'oportunidade' ),
				)
			);
			wp_enqueue_script( 'meta-box-image' );
		}
	}
	
	/**
	 * Função responsável por controlar as folhas de estilo do site para práticas
	 *
	 */
	public function css()
	{
		// map.css
		wp_register_style( 'oportunidade', get_template_directory_uri() . '/inc/oportunidades/css/oportunidade.css', array(), '1' );
		wp_enqueue_style( 'oportunidade' );
		
		if( function_exists('mapasdevista_enqueue_scripts') && get_query_var(self::NEW_OPORTUNIDADE_PAGE) == true )
		{
			wp_enqueue_style('mapasdevista-admin', mapasdevista_get_baseurl('template_directory') . '/admin/admin.css');
		}
		
	}
	
	public function javascript()
	{
		if( function_exists('mapasdevista_enqueue_scripts') && get_query_var(self::NEW_OPORTUNIDADE_PAGE) == true )
		{
			mapasdevista_enqueue_scripts();
			wp_enqueue_script('metabox', mapasdevista_get_baseurl() . '/admin/metabox.js', array('jquery', 'jquery-ui-resizable', 'jquery-ui-dialog') );
			$data = array('options' => get_option('mapasdevista'));
			wp_localize_script('metabox', 'mapasdevista_options', $data);
		}
	}
	
	static function print_field($field, $tax = false)
	{
		if($tax !== false)
		{
			/*
			 Oportunidades::print_field('ressonancias', array(
	 			'label' => 'Áreas de Ressonância',
	 			'outro' => true,
	 		));
			 */
			$type = $tax;
			$label = '';
			$tip = '';
			$id = '';
			$required = false;
			$required_message = '';
			
			if(is_array($tax))
			{
				$type = array_key_exists('type', $tax) ? $tax['type'] : '';
				$label = array_key_exists('label', $tax) ? $tax['label'] : '';
				$tip = array_key_exists('tip', $tax) ? $tax['tip'] : '';
				$id = array_key_exists('slug', $tax) ? $tax['id'] : '';
				$required = array_key_exists('required', $tax) ? $tax['required'] : false;
				$required_message = array_key_exists('required_message', $tax) ? $tax['required_message'] : '';
			}
			
			switch ($type)
			{
				
				case 'dropdown':
				{
					?>
					<div class="oportunidade-item oportunidade-item-dropdown <?php echo $id; ?>">
						<label for="<?php echo $id ?>" class="oportunidade-item-label">
							<div class="oportunidade-item-title"><?php echo $label;
								if($required)
								{?>
									<span class="oportunidade-item-required-asterisk">*</span><?php
								}?>
							</div>
							<div class="oportunidade-item-tip-text"><?php echo $tip; ?>
						</div>
						</label><div class="oportunidade-item-input-dropdown dropdown-<?php echo $id; ?>"><select id="<?php echo $id ?>"
							class="<?php echo $input_class ?>"
							name="<?php echo $id ?>">
								<option value="" selected="selected" ><?php echo esc_attr_x('Selecione', 'oportunidade' ); ?></option>
								<?php
									$args = array(
											'orderby' => 'name',
											'hide_empty'=> 0,
											'hierarchical' => 0,
									
									);
									$terms = get_terms($field, $args);
									
									foreach ($terms as $term)
									{
										?>
										<option value="<?php echo $term->slug; ?>" ><?php echo $term->name; ?></option>
										<?php
									}
								?>
							</select>
						</div>
						<div class="oportunidade-item-error-message"></div>
						<div class="oportunidade-item-required-message"><?php echo $required_message; ?></div>
					</div>
					<?php
									
				}break;
				case 'estadocidade':
				{
					Oportunidades::dropdownEstadoCidade($field, $tax);
				}break;
				default:
					$taxonomy = $field;
					$field = $tax;
					
					$field_default = array(
							'id' => 'taxonomy_'.$taxonomy,
							'label' => '',
							'tip' => '',
							'required_message' => '',
							'input_class' => '',
							'type' => '',
							'required' => false
					);
					
					if(!is_array($field))
					{
						$field = array();
					}
					
					$field = array_merge($field_default, $field);
					
					extract($field);
					
					?>
					<div class="oportunidade-item oportunidade-item-checkbox <?php echo $id; ?>">
						<label for="<?php echo $id ?>" class="oportunidade-item-label">
							<div class="oportunidade-item-title"><?php echo $label;
								if(array_key_exists( 'required', $field ) && $field['required'])
								{?>
									<span class="oportunidade-item-required-asterisk">*</span><?php
								}?>
							</div>
							<div class="oportunidade-item-tip-text"><?php echo $tip; ?>
						</div>
						</label> <?php Oportunidades::taxonomy_checklist($taxonomy); ?>
						<div class="oportunidade-item-error-message"></div>
						<div class="oportunidade-item-required-message"><?php echo $required_message; ?></div>
					</div>
					<?php
				break;
			}
		}
		else
		{
			$id = $field['slug'];
			$label = $field['title'];
			$tip = $field['tip'];
			$required_message = '';
			$input_class = '';
			$type = array_key_exists('type', $field) ? $field['type'] : '';
			switch ($type)
			{
				case 'wp_editor':
					?>
					<div class="oportunidade-item <?php echo $id; ?>">
							<label for="<?php echo $id ?>" class="oportunidade-item-label">
								<div class="oportunidade-item-title"><?php echo $label; ?>
								<span class="oportunidade-item-required-asterisk">*</span>
								</div>
								<div class="oportunidade-item-tip-text">
								<?php echo $tip; ?>
							</div>
							</label>
						<?php wp_editor((array_key_exists($id, $_POST) ? stripslashes($purifier->purify($_POST[$id])) : ''), $id,  array( 
					       'tinymce' => array( 
					            'content_css' => get_stylesheet_directory_uri() . '/inc/oportunidades/css/editor-styles.css' 
					    		)
							)
						); ?>
						<div class="oportunidade-item-error-message"></div>
							<div class="oportunidade-item-required-message">
							<?php echo $required_message; ?>
						</div>
					</div>
					<?php
				break;
				case 'dropdown-ano':
					?>
					<div class="oportunidade-item oportunidade-item-dropdown <?php echo $id; ?>">
						<label for="<?php echo $id ?>" class="oportunidade-item-label">
							<div class="oportunidade-item-title"><?php echo $label;
								if(array_key_exists( 'required', $field ) && $field['required'])
								{?>
									<span class="oportunidade-item-required-asterisk">*</span><?php
								}?>
							</div>
							<div class="oportunidade-item-tip-text"><?php echo $tip; ?>
						</div>
						</label><div class="oportunidade-item-input-dropdown dropdown-<?php echo $id; ?>"><select id="<?php echo $id ?>"
							class="<?php echo $input_class ?>"
							name="<?php echo $id ?>">
								<option value="" <?php echo array_key_exists($id, $_REQUEST)? '' : 'selected="selected"'; ?> ><?php echo esc_attr_x('Selecione', 'oportunidade' ); ?></option>
								<?php
									for($i = date('Y'); $i >= 1900; $i--)
									{
										?>
										<option value="<?php echo $i; ?>" <?php echo array_key_exists($id, $_REQUEST) && wp_strip_all_tags($_REQUEST[$id]) == $i ? 'selected="selected"': ''; ?> ><?php echo $i; ?></option>
										<?php
									} 
								?>
							</select>
						</div>
						<div class="oportunidade-item-error-message"></div>
						<div class="oportunidade-item-required-message"><?php echo $required_message; ?></div>
					</div>
					<?php
				break;
				case 'dropdown-meses-anos':
					?>
					<div class="oportunidade-item oportunidade-item-dropdown <?php echo $id; ?>">
						<label for="<?php echo $id ?>" class="oportunidade-item-label">
							<div class="oportunidade-item-title"><?php echo $label;
								if(array_key_exists( 'required', $field ) && $field['required'])
								{?>
									<span class="oportunidade-item-required-asterisk">*</span><?php
								}?>
							</div>
							<div class="oportunidade-item-tip-text"><?php echo $tip; ?>
						</div>
						</label><div class="oportunidade-item-input-dropdown dropdown-<?php echo $id; ?>"><select id="<?php echo "$id-meses"; ?>"
							class="<?php echo $input_class; ?>"
							name="<?php echo "$id-meses"; ?>">
								<option value="" <?php echo array_key_exists("$id-meses", $_REQUEST) ? '' : 'selected="selected"';?> ><?php echo esc_attr_x('Meses', 'oportunidade' ); ?></option>
								<?php
									for($i = 1; $i < 13; $i++)
									{
										?>
										<option value="<?php echo $i; ?>" <?php echo array_key_exists("$id-meses", $_REQUEST) && wp_strip_all_tags($_REQUEST["$id-meses"]) == $i ? 'selected="selected"': ''; ?> ><?php echo $i; ?></option>
										<?php
									} 
								?>
							</select></div>
							<div class="oportunidade-item-input-dropdown dropdown-<?php echo $id; ?>"><select id="<?php echo "$id-anos"; ?>"
							class="<?php echo $input_class; ?>"
							name="<?php echo "$id-anos"; ?>">
								<option value="" <?php echo array_key_exists("$id-anos", $_REQUEST) ? '' : 'selected="selected"';?> ><?php echo esc_attr_x('Anos', 'oportunidade' ); ?></option>
								<?php
									for($i = 1; $i < 101; $i++)
									{
										?>
										<option value="<?php echo $i; ?>" <?php echo array_key_exists("$id-anos", $_REQUEST) && wp_strip_all_tags($_REQUEST["$id-anos"]) == $i ? 'selected="selected"': ''; ?> ><?php echo $i; ?></option>
										<?php
									} 
								?>
							</select>
						</div>
						<div class="oportunidade-item-error-message"></div>
						<div class="oportunidade-item-required-message"><?php echo $required_message; ?></div>
					</div>
					<?php
				break;
				case 'dropdown-cem':
					?>
					<div class="oportunidade-item oportunidade-item-dropdown <?php echo $id; ?>">
						<label for="<?php echo $id ?>" class="oportunidade-item-label">
							<div class="oportunidade-item-title"><?php echo $label;
								if(array_key_exists( 'required', $field ) && $field['required'])
								{?>
									<span class="oportunidade-item-required-asterisk">*</span><?php
								}?>
							</div>
							<div class="oportunidade-item-tip-text"><?php echo $tip; ?>
						</div>
						</label><div class="oportunidade-item-input-dropdown dropdown-<?php echo $id; ?>"><select id="<?php echo $id ?>"
							class="<?php echo $input_class ?>"
							name="<?php echo $id ?>">
								<option value="" selected="selected" ><?php echo esc_attr_x('Selecione', 'oportunidade' ); ?></option>
								<?php
									for($i = 1; $i < 100; $i++)
									{
										$label = $i == 99 ? '99+' : $i;
										?>
										<option value="<?php echo $i; ?>" <?php echo array_key_exists($id, $_REQUEST) && wp_strip_all_tags($_REQUEST[$id]) == $i ? 'selected="selected"': ''; ?> ><?php echo $label; ?></option>
										<?php
									} 
								?>
							</select>
						</div>
						<div class="oportunidade-item-error-message"></div>
						<div class="oportunidade-item-required-message"><?php echo $required_message; ?></div>
					</div>
					<?php
				break;
				case 'radio':
					?>
					<div class="oportunidade-item oportunidade-item-radio <?php echo $id; ?>">
						<label for="<?php echo $id ?>" class="oportunidade-item-label">
							<div class="oportunidade-item-title"><?php echo $label;
								if(array_key_exists( 'required', $field ) && $field['required'])
								{?>
									<span class="oportunidade-item-required-asterisk">*</span><?php
								}?>
							</div>
							<div class="oportunidade-item-tip-text"><?php echo $tip; ?>
						</div>
						</label>
							<div class="oportunidade-item-input-radio-block"><?php
								$i = 0;
								foreach ($field['values'] as $value => $label_item)
								{
									echo '<input id="'.("$id-option-$i").'" type="radio" name="'.$id.'" value="'.$value.'" '.(array_key_exists($id, $_REQUEST) && wp_strip_all_tags($_REQUEST[$id]) == $value ? 'checked="checked"': '').' ><label for="'.("$id-option-$i").'" class="oportunidade-item-input-radio" >'.$label_item.'</label>';
									$i++;
								}
						?></div><div class="oportunidade-item-error-message"></div>
						<div class="oportunidade-item-required-message"><?php echo $required_message; ?></div>
					</div>
					<?php
				break;
				case 'checkbox':
					?>
					<div class="oportunidade-item oportunidade-item-checkbox <?php echo $id; ?>">
						<label for="<?php echo $id ?>" class="oportunidade-item-label">
							<div class="oportunidade-item-title"><?php echo $label;
								if(array_key_exists( 'required', $field ) && $field['required'])
								{?>
									<span class="oportunidade-item-required-asterisk">*</span><?php
								}?>
							</div>
							<div class="oportunidade-item-tip-text"><?php echo $tip; ?>
						</div>
						</label>
							<div class="oportunidade-item-input-checkbox-block"><?php
								$i = 0;
								$dado = array();
								if(array_key_exists($id, $_REQUEST))
								{
									if (is_array($_REQUEST[$id]))
									{
										$dado = $_REQUEST[$id];
									}
									elseif(is_string($_REQUEST[$id])) 
									{
										$dado = explode(',', $_REQUEST[$id]);
									}
								}
								foreach ($field['values'] as $value => $label_item)
								{
									echo '<input id="'.("$id-option-$i").'" type="checkbox" name="'.$id.'[]" value="'.$value.'" '.(in_array($value, $dado) ? 'checked="checked"': '').' ><label for="'.("$id-option-$i").'" class="oportunidade-item-input-checkbox" >'.$label_item.'</label>';
									$i++;
								}
						?></div><div class="oportunidade-item-error-message"></div>
						<div class="oportunidade-item-required-message"><?php echo $required_message; ?></div>
					</div>
					<?php
				break;
				case 'textarea':
				?>
				<div class="oportunidade-item oportunidade-item-textarea <?php echo $id; ?>">
					<label for="<?php echo $id ?>" class="oportunidade-item-label">
						<div class="oportunidade-item-title"><?php echo $label;
							if(array_key_exists( 'required', $field ) && $field['required'])
							{?>
								<span class="oportunidade-item-required-asterisk">*</span><?php
							}?>
						</div>
						<div class="oportunidade-item-tip-text"><?php echo $tip; ?>
						</div>
					</label>
					<textarea id="<?php echo $id ?>"
						class="oportunidade-item-input-text <?php echo $input_class ?>"
						name="<?php echo $id ?>"
						rows="<?php echo array_key_exists('rows', $field) ? $field['rows'] : 4; ?>"
						<?php echo array_key_exists('cols', $field) ? 'cols="'.$field['cols'].'"' : ''; ?>
					><?php echo array_key_exists($id, $_REQUEST) ? wp_strip_all_tags($_REQUEST[$id]) : ''; ?></textarea>
					<div class="oportunidade-item-error-message"></div>
					<div class="oportunidade-item-required-message"><?php echo $required_message; ?></div>
				</div>
				<?php
				break;
				case 'date':
				case 'number':
				default:
					?>
					<div class="oportunidade-item <?php echo $type == 'text' || $type == '' ? 'oportunidade-item-text' : 'oportunidade-item-'.$type; ?> <?php echo $id; ?>">
						<label for="<?php echo $id ?>" class="oportunidade-item-label">
							<div class="oportunidade-item-title"><?php echo $label;
								if(array_key_exists( 'required', $field ) && $field['required'])
								{?>
									<span class="oportunidade-item-required-asterisk">*</span><?php
								}?>
							</div>
							<div class="oportunidade-item-tip-text"><?php echo $tip; ?>
						</div>
						</label> <input type="<?php echo $type == 'number' ? 'number' : 'text' ?>" id="<?php echo $id ?>"
							class="oportunidade-item-input-text <?php echo $type == 'date' ? 'hasdatepicker' : ''; ?> <?php echo $input_class ?>"
							value="<?php echo array_key_exists($id, $_REQUEST) ? wp_strip_all_tags($_REQUEST[$id]) : ''; ?>"
							name="<?php echo $id ?>">
						<div class="oportunidade-item-error-message"></div>
						<div class="oportunidade-item-required-message"><?php echo $required_message; ?></div>
					</div>
					<?php
				break;
			}
		}
	}
	
	static function taxonomy_checklist($taxonomy = 'category', $parent = 0)
	{
		$args = array(
				'orderby' => 'id',
				'hide_empty'=> 0,
				'parent' => $parent,
				'hierarchical' => 0,
				'taxonomy'=>$taxonomy
	
		);
		$terms = get_terms($taxonomy, $args);
		//print_r($terms);
	
		if (!is_array($terms) || ( is_array($terms) && sizeof($terms) < 1 ) )
		{
			return;
		}
		if ($parent > 0)
		{?>
			<ul class='children'><?php
		}
		else
		{?>
			<ul class='parent'><?php
		}
		$index = 1;
		foreach ($terms as $term)
		{
			$name = $term->name;
			$input = '';
			if(strpos($name, '#input#') !== false)
			{
				$name = str_replace('#input#', '', $name);
				$value = array_key_exists($taxonomy.'_'.$term->term_id.'_input', $_REQUEST) ? $_REQUEST[$taxonomy.'_'.$term->term_id.'_input'] : ''; 
				$input = '<input type="text" class="taxonomy-checkbox-text taxonomy-'.$taxonomy.'-checkbox-text" name="'.$taxonomy.'_'.$term->term_id.'_input" id="taxonomy_'.$taxonomy.'_'.$term->slug.'_input" value="'.$value.'" />';
			}
			$checked = 
				isset($_REQUEST) &&
				array_key_exists("taxonomy_$taxonomy", $_REQUEST) &&
				((is_array($_REQUEST["taxonomy_$taxonomy"]) && array_search($term->term_id, $_REQUEST["taxonomy_$taxonomy"]) !== false ) ||
				(is_string($_REQUEST["taxonomy_$taxonomy"]) && $_REQUEST["taxonomy_$taxonomy"] == $term->term_id))
			? 'checked="checked"' : '';
			if($taxonomy == 'category' && get_query_var('cat')) // workaround for cat query var
			{
				$checked = get_query_var('cat') == $term->term_id ? 'checked="checked"' : '';
			}
			$haschildren = false;
			if($parent == 0)
			{
				$children = get_term_children($term->term_id, $taxonomy);
				$haschildren = is_array($children) && count($children) > 0 ? true : false;
			}
			?>
			<li class="taxonomy-checkbox-group-col <?php echo $taxonomy ?>-group-col <?php echo $parent == 0 ? $taxonomy.'-group-col-'.$index : ''; ?>"><?php
				if( ($parent > 0 || !$haschildren ) && $input == '')
				{?>
					<input type="checkbox" class="taxonomy-<?php echo $taxonomy ?>-checkbox" value="<?php echo $term->term_id; ?>" name="taxonomy_<?php echo $taxonomy; ?>[]" id="taxonomy_<?php echo $taxonomy; ?>_<?php echo $term->slug; ?>"
					<?php echo $checked; ?> autocomplete="off" /><?php
				}?>
				<label for="taxonomy_<?php echo $taxonomy; ?>_<?php echo $term->slug; ?>" class="taxonomy-label-group-col <?php echo empty($input) ? '' : 'taxonomy-label-group-col-input'; ?>" ><?php
					echo $name.(empty($input) ? '' : ':&nbsp;');?>
				</label><?php
				echo $input; 
				self::taxonomy_checklist($taxonomy, $term->term_id); ?>
			</li>
			<?php
			$index++;
		}
		//if ($parent > 0)
		{?>
			</ul><?php
		}
	}
	
	function roles_install($permissoes)
	{
	
		// Criação das regras
		foreach ($permissoes as $nome => $permisao)
		{
			if($permisao['Novo'] == true)
			{
				$Role = get_role($permisao['From']);
					
				if(!is_object($Role))
				{
					throw new Exception(sprintf(__('Permissão original (%s) não localizada','oportunidade'),$permisao['From']));
				}
					
				$cap = $Role->capabilities;
				add_role($nome, $permisao["nome"], $cap);
			}
	
			$Role = get_role($nome);
			if(!is_object($Role))
			{
				throw new Exception(sprintf(__('Permissão %s não localizada','oportunidade'),$nome));
			}
	
			foreach ($permisao['Caps'] as $cap)
			{
					
				$Role->add_cap($cap);
			}
		}
	
	}
	
	static function dropdownEstadoCidade($taxonomy = 'territorio', $field = array())
	{
		$field_default = array(
			'id' => 'field-'.rand(),
			'label' => '',
			'tip' => '',
			'required_message' => '',
			'input_class' => '',
			'type' => '',
			'required' => false
		);
		
		if(!is_array($field))
		{
			$field = array();
		}
		
		$field = array_merge($field_default, $field);
		
		extract($field);
		?>
		<div class="oportunidade-item oportunidade-item-dropdown">
			<label for="<?php echo $id ?>" class="oportunidade-item-label">
				<div class="oportunidade-item-title"><?php echo $label;
					if(array_key_exists( 'required', $field ) && $field['required'])
					{?>
						<span class="oportunidade-item-required-asterisk">*</span><?php
					}?>
				</div>
				<div class="oportunidade-item-tip-text"><?php echo $tip; ?>
			</div>
			</label><?php EstadosCidades::dropdown($taxonomy, 'taxonomy_'.$taxonomy.'[]', 'taxonomy_'.$taxonomy.'[]'); ?>
			<div class="oportunidade-item-error-message"></div>
			<div class="oportunidade-item-required-message"><?php echo $required_message; ?></div>
		</div>
		<?php
		
	}
	
	public static function get_new_url()
	{
		return get_bloginfo( 'url' ) . '/'.self::NEW_OPORTUNIDADE_PAGE;
	}
	
	public static function save_fields($post_ID, &$message = array(), &$notice = false)
	{
		global $Oportunidade_global;
		
		$oportunidade = $Oportunidade_global;
		
		$purifier = '';
		if(!is_admin())
		{
			$purifier = new HTMLPurifier();
		}
		
		$post = get_post($post_ID);
		
		foreach ($oportunidade->getFields() as $key => $field)
		{
			if(array_key_exists('save', $field) && $field['save'] == false ) /* do not save especial fields */
			{
				continue;
			}
				
			if( (array_key_exists('required', $field) && $field['required']) && (! array_key_exists($field['slug'], $_POST) || empty($_POST[$field['slug']]) ))
			{
				$message[] = '<span class="error-msn-pre">'.__('*O campo obrigatório').': '.'</span><div onclick="oportunidade_scroll_to_anchor(\''.$field['slug'].'\');">'.$field['title'].' '.__('não foi preenchido').'</div>';
				$notice = true;
			}
			else
			{
		
				if(array_key_exists('buildin', $field) && $field['buildin'] == true)
				{
					if(!is_admin())
					{
						if(array_key_exists('type', $field) && $field['type'] == 'wp_editor')
						{
							$post->{$field['slug']} = $purifier->purify(stripslashes($_POST[$field['slug']]));
						}
						else
						{
							$post->{$field['slug']} = wp_strip_all_tags($_POST[$field['slug']]);
						}
					}
				}
				else
				{
					if( array_key_exists($field['slug'], $_POST) )
					{
						if(array_key_exists('type', $field) && $field['type'] == 'checkbox' && is_array($_POST[$field['slug']]))
						{
							update_post_meta($post_ID, $field['slug'], implode(',', $_POST[$field['slug']]));
						}
						else
						{
							update_post_meta($post_ID, $field['slug'], $_POST[$field['slug']]);
						}
					}
					elseif(array_key_exists('type', $field) && $field['type'] == 'dropdown-meses-anos')
					{
						if( array_key_exists($field['slug'].'-anos', $_POST) )
						{
							update_post_meta($post_ID, $field['slug'].'-anos', $_POST[$field['slug'].'-anos']);
						}
						if( array_key_exists($field['slug'].'-meses', $_POST) )
						{
							update_post_meta($post_ID, $field['slug'].'-meses', $_POST[$field['slug'].'-meses']);
						}
					}
				}
			}
		}
	}
	
}

$Oportunidade_global = new Oportunidades();

?>
