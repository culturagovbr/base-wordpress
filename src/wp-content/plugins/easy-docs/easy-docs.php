<?php
/**
 * Plugin Name:       Easy Docs
 * Plugin URI:        https://github.com/culturagovbr/
 * Description:       Adiciona um novo tipo de conteúdo denominado <strong>Documento</strong>, com suporte à categorias e shortcodes (Utilização: <strong>[easy-docs]</strong>; parâmetros disponíveis: <strong>category</strong>, <strong>items</strong>, <strong>all-items-label</strong>).
 * Version:           1.0.0
 * Author:            Ricardo Carvalho
 * Author URI:        https://github.com/culturagovbr/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

if( ! class_exists('EasyDocs') ) :

    class EasyDocs
    {

        /**
         * EasyDocs constructor.
         *
         */
        public function __construct()
        {
            setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
            date_default_timezone_set('America/Sao_Paulo');

            add_action( 'wp_enqueue_scripts', array( $this, 'register_easy_docs_styles' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'register_easy_docs_admin_styles' ) );
            add_action( 'admin_enqueue_scripts', array( $this, 'register_easy_docs_admin_scripts' ) );
            add_action( 'init', array( $this, 'cpt_docs' ) );
            add_action( 'add_meta_boxes', array( $this, 'easy_docs_add_meta_box' ) );
            add_action( 'save_post', array( $this, 'easy_docs_save_postdata' ) );

            add_filter( 'the_content', array( $this, 'add_document_to_content' ) );

            add_shortcode( 'easy-docs', array( $this, 'easy_docs_shortcode' ) );
        }

        /**
         * Register our public styles
         *
         */
        public function register_easy_docs_styles()
        {
            wp_enqueue_style( 'dashicons' );
            wp_register_style( 'easy_docs-styles', plugins_url( '/assets/css/easy-docs-styles.css', __FILE__ ) );
            wp_enqueue_style( 'easy_docs-styles' );
        }

        /**
         * Register our admin styles
         *
         */
        public function register_easy_docs_admin_styles()
        {
            global $post_type;
            if( $post_type !== 'documents' ){
                return;
            }

            wp_register_style( 'easy_docs-admin-styles', plugins_url( '/assets/css/easy-docs-admin-styles.css', __FILE__ ) );
            wp_enqueue_style( 'easy_docs-admin-styles' );
        }

        /**
         * Register our public scripts
         *
         */
        public function register_easy_docs_scripts()
        {
            wp_register_script( 'easy_docs-masonry', plugins_url( 'easy-docs/assets/masonry.pkgd.min.js' ) );
            wp_enqueue_script( 'easy_docs-masonry' );
            wp_register_script( 'easy_docs-scripts', plugins_url( 'easy-docs/assets/easy_docs-scripts.js' ) );
            wp_enqueue_script( 'easy_docs-scripts' );
        }

        /**
         * Register our admin scripts
         *
         */
        public function register_easy_docs_admin_scripts()
        {
            wp_enqueue_script('media-upload');
            wp_register_script( 'easy_docs-admin-scripts', plugins_url( '/assets/js/easy-docs-admin-scripts.js', __FILE__ ),  array( 'jquery' ) );
            wp_enqueue_script( 'easy_docs-admin-scripts' );
        }

        /**
         * Create a custom post type with custom taxonomy for our documents
         *
         */
        public function cpt_docs()
        {
            register_post_type( 'documents',
                array(
                    'labels'              => array(
                        'name'          => 'Documentos',
                        'singular_name' => 'Documento',
                    ),
                    'public' => true,
                    'has_archive' => true,
                    'publicly_queryable' => true,
                    'supports' => array( 'title', 'editor', 'excerpt' ),
                    'menu_icon' => 'dashicons-media-document'
                )
            );

            register_taxonomy(
                'document-category',
                'documents',
                array(
                    'label' => 'Tipo de documento',
                    'hierarchical' => true
                )
            );
        }

        /**
         * Add a meta box to upload our document
         *
         */
        public function easy_docs_add_meta_box()
        {
            add_meta_box(
                'easy-docs-metabox',
                'Documento',
                array( $this, 'render_meta_box_content' ),
                'documents',
                'side',
                'default'
            );
        }

        /**
         * Metabox content
         *
         * @param $post
         */
        public function render_meta_box_content( $post )
        {
            wp_nonce_field( plugin_basename( __FILE__ ), 'easy_docs_nonce' );

            $document_url = get_post_meta( $post->ID, '_document-url', true ); ?>

            <div class="document-url-wrapper <?php echo $document_url ? '' : 'no-doc'; ?>">
                <label for="document-url">Selecione o arquivo para o documento</label>
                <input type="text" id="document-url" name="document-url" value="<?php echo esc_attr($document_url) ?>"/>
                <div class="document-url-meta" data-default-icon="<?php echo home_url('/wp-includes/images/media/document.png'); ?>">
                    <div class="thumbnail default-image" >
                    <?php
                    if( $document_url ){
                        echo '<a href="#">';
                    }

                    $attachment = $this->get_attachment_id_by_url($document_url);
                    if( wp_get_attachment_image( $attachment->ID, array('150', '150') ) ){
                        echo wp_get_attachment_image( $attachment->ID, array('150', '150') );
                    } else { ?>
                        <img src="<?php echo home_url('/wp-includes/images/media/document.png'); ?>">
                    <?php }

                    if( $document_url ){
                        echo '</a>';
                    } ?>
                    </div>
                    <div class="data">
                        <span class="doc-name"><b><?php echo $attachment->post_title; ?></b></span>
                        <small class="doc-date"><?php echo strftime('%d de %B de %Y', strtotime($attachment->post_date)); ?></small>
                        <small class="doc-size"><?php echo size_format( filesize( get_attached_file( $attachment->ID ) ) ); ?></small>
                    </div>
                </div>
                <div class="document-url-footer">
                    <a href="#" id="remove-document-url">Remover documento</a>
                    <button id="upload-doc-button" class="button">Selecionar</button>
                </div>
            </div>

            <?php
        }

        /**
         * Sanitize and save our data
         *
         * @param $post_id
         */
        public function easy_docs_save_postdata( $post_id )
        {
            if ( 'documents' == $_POST['post_type'] ) {
                if ( ! current_user_can( 'edit_page', $post_id ) )
                    return;
            } else {
                if ( ! current_user_can( 'edit_post', $post_id ) )
                    return;
            }

            if ( ! isset( $_POST['easy_docs_nonce'] ) || ! wp_verify_nonce( $_POST['easy_docs_nonce'], plugin_basename( __FILE__ ) ) )
                return;

            $document_url = sanitize_text_field( $_POST['document-url'] );
            update_post_meta($post_id, '_document-url', $document_url);
        }

        /**
         * Retrieve Attachment ID from Image URL
         *
         * @link https://pippinsplugins.com/retrieve-attachment-id-from-image-url/
         * @param $image_url
         * @return mixed
         */
        public function get_attachment_id_by_url($image_url) {
            global $wpdb;
            $attachment = $wpdb->get_row("SELECT ID, post_title, post_date FROM $wpdb->posts WHERE guid='$image_url';" );
            return $attachment;
        }

        /**
         * @param $bytes
         * @param int $decimals
         * @return string
         * @link http://php.net/manual/pt_BR/function.filesize.php#106569
         */
        public function human_filesize($bytes, $decimals = 2) {
            $sz = 'BKMGTP';
            $factor = floor((strlen($bytes) - 1) / 3);
            return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$sz[$factor];
        }

        /**
         * Add a card with attachment info and a link to download
         *
         * @param $content
         * @return string
         */
        public function add_document_to_content ($content){
            $document_url = get_post_meta( get_the_ID(), '_document-url', true );
            if( !$document_url ){
                return $content;
            } else {
                $attachment = $this->get_attachment_id_by_url($document_url);
                $doc_tax = get_the_terms( get_the_ID(), 'document-category' );
                $attach_cat = $doc_tax[0]->name ? $doc_tax[0]->name : 'Anexo';

                $document_box  = '<div class="easy-document-box card">';
                $document_box .=    '<div class="card-body">';
                $document_box .=        '<h5 class="card-title">'. $attachment->post_title .'</h5>';
                $document_box .=        '<h6 class="card-subtitle mb-2 text-muted"><span class="dashicons dashicons-media-document"></span> '. $attach_cat .'</h6>';
                $document_box .=        '<p class="card-text"><small>'. strftime('%d de %B de %Y', strtotime($attachment->post_date)) .'</small></p>';
                $document_box .=        '<p class="card-text"><small>'. size_format( filesize( get_attached_file( $attachment->ID ) ) ) .'</small></p>';
                $document_box .=        '<a href="'. $document_url .'" target="_blank" class="btn btn-primary">Download do arquivo</a>';
                $document_box .=    '</div>';
                $document_box .= '</div>';
                return $content . $document_box;
            }
        }

        /**
         * Shortcode to add a list of documents to show
         *
         * @param $atts
         * @return string
         */
        public function easy_docs_shortcode ($atts){
            $atts = shortcode_atts(array(
                'category'          => '',
                'items'             => '5',
                'all-items-label'   => 'Todos os documentos',
            ), $atts);

            ob_start();
            $easy_docs_query = new WP_Query( array(
                'post_type'         => 'documents',
                'posts_per_page'    => $atts['items'],
                'tax_query'         => $atts['category'] ? array(
                    array(
                        'taxonomy'      => 'document-category',
                        'field'         => 'slug',
                        'terms'         => $atts['category'],
                    ),
                ) : ''
            ) );

            if ( $easy_docs_query->have_posts() ) {
                echo '<ul>';
                while ( $easy_docs_query->have_posts() ) {
                    $easy_docs_query->the_post();
                    echo '<li><a href="'. get_the_permalink() .'">' . get_the_title( $easy_docs_query->post->ID ) . '</a></li>';
                }
                echo '</ul>';

                $link_to_all_docs = $atts['category'] ? home_url('/document-category/' . $atts['category']) : home_url('/documents');
                echo '<a href="'. $link_to_all_docs .'" class="easy-docs-all-btn">'. $atts['all-items-label'] .' <span class="easy-docs-all-btn-plus-ico"></span></a>';

                wp_reset_postdata();
            }

            return ob_get_clean();
        }

    }

    // Initialize our plugin
    $easy_docs = new EasyDocs();

endif;
