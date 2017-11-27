<?php
/**
 * Template Name: Formulário de Importação de usuários
 *
 */
get_header(); ?>

    <div id="main-content">
        <div class="conteudo">
            <div id="content-area" class="clearfix">
                <?php
                if (have_posts()) :
                    while (have_posts()) : the_post();
                        $post_format = et_pb_post_format(); ?>

                        <article id="post-<?php the_ID(); ?>" <?php post_class('et_pb_post'); ?>>

                            <?php the_content(); ?>

                            <?php
                                if( !empty( $_POST['users-json'] ) ) {

									function pnc_save_users_to_stc($stc_title, $array_of_cats, $location) {
										// Check for user's email
										$stc_post = get_page_by_title ($stc_title, '', 'stc');
										if (null == $stc_post) {
											// Create stc post
											$stc_post = array('post_type' => 'stc', 'post_title' => $stc_title, 'post_status' => 'publish', 'post_author' => 1, 'post_category' => $array_of_cats);

											// Insert the stc post into the database
											if (wp_insert_post ($stc_post)) {
												echo 'Usuário: ' . $stc_title . ' importado com sucesso<br>';
											}
										} else {
											echo 'O usuário: ' . $stc_title . ' já está cadastrado para receber as metas<br>';
											error_log ("User already subscribing to some category!", 0);
										}

										update_post_meta ($stc_post->ID, '_stc_user_location_state', $location['uf']);
										update_post_meta ($stc_post->ID, '_stc_user_location_city', $location['municipio']);
									}


									function pnc_update_term_id( $term_id ) {
										$new_term_id = '';
										switch ($term_id) {
											case '391':
												$new_term_id = '18';
												break;
											case '392':
												$new_term_id = '635';
												break;
											case '394':
												$new_term_id = '15';
												break;
											case '396':
												$new_term_id = '325';
												break;
											case '397':
												$new_term_id = '270';
												break;
											case '398':
												$new_term_id = '17';
												break;
											case '395':
												$new_term_id = '17';
												break;
											case '393':
												$new_term_id = '564';
												break;
											case '399':
												$new_term_id = '23';
												break;
											case '400':
												$new_term_id = '21';
												break;
											case '316':
												$new_term_id = '317';
												break;
											case '579':
												$new_term_id = '485';
												break;
											case '580':
												$new_term_id = '401';
												break;
											case '840':
												$new_term_id = '635';
												break;
										}
										return $new_term_id;
									}

                                    $users_to_import_arr = [];
                                    $obj = stripslashes($_POST['users-json']);
                                    foreach ( json_decode ( $obj ) as $users_obj ){
                                        foreach ( $users_obj as $user ){
                                            if( $user->meta_key == 'temas_followed' ){
												$users_to_import_arr[$user->user_email]['cats'][] = pnc_update_term_id($user->meta_value);
											}

											if( $user->meta_key == 'estado' ){
												$users_to_import_arr[$user->user_email]['location']['uf'] = $user->meta_value;
											}

											if( $user->meta_key == 'municipio' ){
												$users_to_import_arr[$user->user_email]['location']['municipio'] = $user->meta_value;
											}
                                        }
                                    }

                                    $cats_arr = [];
                                    foreach ( $users_to_import_arr as $user => $cats ){
										pnc_save_users_to_stc($user, $cats['cats'], $cats['location']);
                                    }

								} else { ?>
                                    <form id="pnc-form-users-import" method="post">
                                        <label for="users-json">Insira o JSON abaixo</label>
                                        <textarea id="users-json" name="users-json" style="width: 100%" rows="10"></textarea><br>
                                        <a href="#" class="validate-json-link">Validar JSON</a>
                                        <input type="submit" value="Importar JSON" disabled="disabled" style="float: right">
                                    </form>
                                <?php }
                            ?>

                        </article> <!-- .et_pb_post -->
                        <?php
                    endwhile;

                else :
                    get_template_part('includes/no-results', 'index');
                endif;
                ?>
            </div>
        </div>
    </div>

<?php get_footer(); ?>