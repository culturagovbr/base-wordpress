<?php
/**
 * Plugin Name:     Markdown Parser
 * Plugin URI:      @todo
 * Description:     Adiciona a funcionalidade para converter textos no formato Markdown em código HTML (Com suporte à TOC - Table Of Contents). Baseado no Markdown Parser in PHP [http://parsedown.org/].
 * Version:         1.0.0
 * Author:          Ricardo Carvalho
 * Author URI:      @todo
 * Text Domain:     markdown-parser
 */

/**
 * Exit if accessed directly
 */
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Include some JS files
 */
function markdown_parser_scripts()
{
    wp_register_script( 'markdown-parser-scripts', plugins_url( 'assets/js/markdown-parser-scripts.js', __FILE__ ) );

    // For either a plugin or a theme, you can then enqueue the script:
    wp_enqueue_script( 'markdown-parser-scripts' );

    $mpObj = array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'post_id' => get_the_ID()
    );

    wp_localize_script( 'markdown-parser-scripts', 'mpObj', $mpObj );
}
add_action( 'admin_enqueue_scripts', 'markdown_parser_scripts' );


/**
 * Include the amazing Markdown Parser in PHP [http://parsedown.org/] *with modifications
 */
require_once plugin_dir_path( __FILE__ ) . 'inc/Parsedown.php';

/**
 * Remove automatic filters for WP
 */
add_action( 'init', 'remove_auto_filters' );
function remove_auto_filters() {
    if( is_page() ){
        remove_filter('the_content', 'wpautop');
        remove_filter('the_content_rss', 'wpautop');
        remove_filter('the_excerpt', 'wpautop');
    }
}

/**
 * Get content, parse and then output to user
 */
function parse_content_filter($content) {

    // Check if the option to parse as markdown is set
    $parse_as_markdown = get_post_meta( get_the_ID(), 'markdown-parser', true );
    if( !empty( $parse_as_markdown ) ):
        $Parsedown = new Parsedown();
        $content = $Parsedown->text($content);
        $content = '<div class="markdown-body">' . $content . '</div>';

        // Change contents of [TOC] markdown extension with headings found on content
        echo str_replace('[TOC]', $Parsedown->getHeadings(), $content);

        // Prevent the default action for content filter
        return false;
    else:
        return $content;
    endif;

}
add_filter( 'the_content', 'parse_content_filter', 1 );

/**
 * Adds a meta box to the page editing screen
 */
function markdown_parser_meta() {
    add_meta_box(
        'markdown_parser_meta',
        __( 'Markdown Parser', 'markdown_parser-textdomain' ),
        'markdown_parser_meta_callback',
        'page',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'markdown_parser_meta' );

/**
 * Outputs the content of the meta box
 */
function markdown_parser_meta_callback( $post ) {
    wp_nonce_field( basename( __FILE__ ), 'markdown_parser_nonce' );
    $markdown_parser_stored_meta = get_post_meta( $post->ID );
    $check = isset( $markdown_parser_stored_meta['markdown-parser'] ) ? esc_attr( $markdown_parser_stored_meta['markdown-parser'][0] ) : '';
    $check2 = isset( $markdown_parser_stored_meta['markdown-parser-from-url'] ) ? esc_attr( $markdown_parser_stored_meta['markdown-parser-from-url'][0] ) : '';
    $check3 = isset( $markdown_parser_stored_meta['markdown-parser-monitor'] ) ? esc_attr( $markdown_parser_stored_meta['markdown-parser-monitor'][0] ) : '';
    ?>

    <p>
        <input type="checkbox" name="markdown-parser" id="markdown-parser" <?php checked( $check, 'on' ); ?> />
        <label for="markdown-parser" class="markdown_parser-row-title"><?php _e( 'Parsear essa página como Markdown.', 'markdown_parser-textdomain' )?></label>
    </p>

    <p>
        <input type="checkbox" name="markdown-parser-from-url" id="markdown-parser-from-url" <?php checked( $check2, 'on' ); ?> />
        <label for="markdown-parser-from-url" class="markdown_parser-row-title"><?php _e( 'Selecionar arquivo externo (URL) para parsear.', 'markdown_parser-textdomain' )?></label>
    </p>

    <div id="markdown-parser-from-url-box" <?php echo ( $check2 ) ? '' : 'style="display: none"'; ?>>
        <p>
            <label for="markdown-parser-url-origin" class="markdown_parser-row-title"><?php _e( 'Insira a URL do arquivo com o texto markdown.', 'markdown_parser-textdomain' )?></label>
            <input type="text" name="markdown-parser-url-origin" id="markdown-parser-url-origin" value="<?php if ( isset ( $markdown_parser_stored_meta['markdown-parser-url-origin'] ) ) echo $markdown_parser_stored_meta['markdown-parser-url-origin'][0]; ?>" style="width: 100%;"/>
            <small style="color: #a00; font-style: italic; text-align: center; margin-top: 15px; display: block;">Atenção ao salvar essa página, seu conteúdo será substituído pelo contido na URL acima!</small>
        </p>

        <p>
            <input type="checkbox" name="markdown-parser-monitor" id="markdown-parser-monitor" <?php checked( $check3, 'on' ); ?> />
            <label for="markdown-parser-monitor" class="markdown_parser-row-title"><?php _e( 'Permitir monitoramento automático da URL acima (Isso atualizará essa página periodicamente).', 'markdown_parser-textdomain' )?></label>
        </p>
    </div>

    <?php
}

/**
 * Saves the markdown parser meta input
 */
function markdown_parser_meta_save( $post_id ) {

    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'markdown_parser_nonce' ] ) && wp_verify_nonce( $_POST[ 'markdown_parser_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }

    $chk = ( isset( $_POST['markdown-parser'] ) && $_POST['markdown-parser'] ) ? 'on' : '';
    update_post_meta( $post_id, 'markdown-parser', $chk );

    $chk2 = ( isset( $_POST['markdown-parser-from-url'] ) && $_POST['markdown-parser-from-url'] ) ? 'on' : '';
    update_post_meta( $post_id, 'markdown-parser-from-url', $chk2 );

    $chk3 = ( isset( $_POST['markdown-parser-monitor'] ) && $_POST['markdown-parser-monitor'] ) ? 'on' : '';
    update_post_meta( $post_id, 'markdown-parser-monitor', $chk3 );

    // Checks for input and sanitizes/saves if needed
    if( isset( $_POST[ 'markdown-parser-url-origin' ] ) ) {
        update_post_meta( $post_id, 'markdown-parser-url-origin', sanitize_text_field( $_POST[ 'markdown-parser-url-origin' ] ) );
    }

}
add_action( 'save_post', 'markdown_parser_meta_save' );

/**
 * A filter hook called by the wp_insert_post function prior to inserting into or updating the database.
 */
function filter_handler( $data , $postarr ) {
    $my_post_id = $postarr['ID'];

    // Check if the option to parse a markdown from URL is set
    $parse_markdown_url_origin = get_post_meta( $my_post_id, 'markdown-parser-url-origin', true );

    if( isset($_POST['markdown-parser-from-url']) ){
        $new_markdown = file_get_contents( $parse_markdown_url_origin );
        $data['post_content'] = $new_markdown;
        $postarr['post_content'] = $new_markdown;
    }
    return $data;
}
add_filter( 'wp_insert_post_data', 'filter_handler', '99', 2 );

/**
 * Updates post meta, on event of a click on markdown-parser-from-url's button
 */
function change_meta_parse_markdown_from_url() {

    $update = update_post_meta( $_REQUEST['post_id'], 'markdown-parser-from-url', $_REQUEST['prop'] );

    if( $update ){
        echo 'true';
    }else{
        echo 'false';
    }

    exit;
}
add_action('wp_ajax_change_meta_parse_markdown_from_url', 'change_meta_parse_markdown_from_url');

/**
 * Add a CRON to run once a day verifiyng alterations on markdown URL, and updating the page
 */
function parse_markdown_cron_schedule() {

    //Use wp_next_scheduled to check if the event is already scheduled
    $timestamp = wp_next_scheduled( 'parse_markdown_cron_run' );

    //If $timestamp == false schedule daily backups since it hasn't been done previously
    if( $timestamp == false ){
        wp_schedule_event( time(), '5sec', 'parse_markdown_cron_run' );
        wp_schedule_event( time(), 'twicedaily', 'parse_markdown_cron_run' );
    }

}
register_activation_hook( __FILE__, 'parse_markdown_cron_schedule' );

//Hook our function , wi_create_backup(), into the action wi_create_daily_backup
function parse_markdown_update_pages(){

    // Check if the option to parse a markdown from URL is set
    // $parse_markdown_url_origin = get_post_meta( $my_post_id, 'markdown-parser-url-origin', true );
    // $parse_markdown_monitor = get_post_meta( $my_post_id, 'markdown-parser-monitor', true );

    $args = array(
        'meta_key'   => 'markdown-parser-monitor',
        'meta_value' => 'on',
        'post_type'  => 'page',
        'posts_per_page' => -1
    );
    $query_pages = new WP_Query( $args );
    if ( $query_pages->have_posts() ):
        while ( $query_pages->have_posts() ) : $query_pages->the_post();

            $parse_markdown_from_url = get_post_meta( get_the_ID(), 'markdown-parser-from-url', true );
            $parse_markdown_url_origin = get_post_meta( get_the_ID(), 'markdown-parser-url-origin', true );

            if( isset( $parse_markdown_from_url ) && isset( $parse_markdown_url_origin ) ){
                $new_markdown = file_get_contents( $parse_markdown_url_origin );

                $update_post = array(
                    'ID'           => get_the_ID(),
                    'post_content' => $new_markdown,
                );

                // Update the post into the database
                wp_update_post( $update_post );

                update_post_meta( get_the_ID(), 'markdown-parser', 'on' );
                update_post_meta( get_the_ID(), 'markdown-parser-from-url', 'on' );
                update_post_meta( get_the_ID(), 'markdown-parser-monitor', 'on' );
            }

        endwhile;
        wp_reset_postdata();
    endif;

}
add_action( 'parse_markdown_cron_run', 'parse_markdown_update_pages' );


function parse_markdown_cron_schedule_remove(){
    wp_clear_scheduled_hook( 'parse_markdown_cron_run' );
}
register_deactivation_hook( __FILE__, 'parse_markdown_cron_schedule_remove' );


function myprefix_add_weekly_cron_schedule( $schedules ) {
    if(!isset($schedules['1min'])) {
        $schedules['1min'] = array(
            // 'interval' => 7 * 24 * 60 * 60,
            'interval' => 60,
            'display' => __('Once every 60 secs'),
        );
    }
    if(!isset($schedules['5sec'])) {
        $schedules['5sec'] = array(
            'interval' => 5,
            'display' => __('Once every 5 secs'),
        );
    }

    return $schedules;
}
add_filter( 'cron_schedules', 'myprefix_add_weekly_cron_schedule' );