<?php

/**
 * Class Oscar_Minc_Shortcodes
 *
 */
class SNC_Oficinas_Visualizar_Inscricao_Shortcode
{
    public function __construct()
    {
        if (!is_admin()) {
            add_shortcode('snc_view_subscription', array($this, 'snc_minc_view_subscription')); // Registro de usuário
        }
    }

    /**
     * Authentication form
     *
     * @param $atts
     * @return string
     */
    public function snc_minc_view_subscription($atts)
    {

        ob_start();


        $other_page = 12;

        $post = $this->is_user_registered_in_workshop();

        $updated = $_GET['updated'];

        $current_user = wp_get_current_user();
        $name = $current_user->display_name;
        $birthday = get_user_meta( $current_user->ID, '_user_birthday', true );
        $schooling = get_user_meta( $current_user->ID, '_user_schooling', true );
        $gender = get_user_meta( $current_user->ID, '_user_gender', true );
        $cpf = get_user_meta( $current_user->ID, '_user_cpf', true );
        $rg = get_user_meta( $current_user->ID, '_user_rg', true );
        $address = get_user_meta( $current_user->ID, '_user_address', true );
        $state = get_user_meta( $current_user->ID, '_user_state', true );
        $county = get_user_meta( $current_user->ID, '_user_county', true );
        $neighborhood = get_user_meta( $current_user->ID, '_user_neighborhood', true );
        $number = get_user_meta( $current_user->ID, '_user_number', true );
        $complement = get_user_meta( $current_user->ID, '_user_complement', true );
        $zipcode = get_user_meta( $current_user->ID, '_user_zipcode', true );
        $phone = get_user_meta( $current_user->ID, '_user_phone', true );
        $celphone = get_user_meta( $current_user->ID, '_user_celphone', true );
        $email = $current_user->user_email;
        $institutional_email = get_user_meta( $current_user->ID, '_user_institutional-email', true );
        $webpage = get_user_meta( $current_user->ID, '_user_webpage', true );
        $socials = get_user_meta( $current_user->ID, '_user_socials', true );


        ?>
        <?php if ($updated) : ?>
            <div id="message" class="updated"><p>Seu cadastro foi realizado com sucesso!</p></div>
        <?php endif; ?>
        <h3>Dados do usuário</h3>
        <p><b>Nome completo:</b> <?php echo $name; ?></p>
        <p><b>Data de nascimento:</b> <?php echo $birthday; ?></p>
        <p><b>Escolaridade:</b> <?php echo $schooling; ?></p>
        <p><b>Gênero:</b> <?php echo $gender; ?></p>
        <p><b>CPF:</b> <?php echo $cpf; ?></p>
        <p><b>RG:</b> <?php echo $rg; ?></p>
        <p><b>Endereço:</b> <?php echo $address; ?></p>
        <p><b>UF:</b> <?php echo $state; ?></p>
        <p><b>Município:</b> <?php echo $county; ?></p>
        <p><b>Bairro:</b> <?php echo $neighborhood; ?></p>
        <p><b>Número:</b> <?php echo $number; ?></p>
        <p><b>Complemento:</b> <?php echo $complement; ?></p>
        <p><b>DDD / Telefone:</b> <?php echo $phone; ?></p>
        <p><b>DDD / Celular:</b> <?php echo $celphone; ?></p>
        <p><b>E-mail pessoal:</b> <?php echo $email; ?></p>
        <p><b>E-mail institucional:</b> <?php echo $institutional_email; ?></p>
        <p><b>Página da internet:</b> <?php echo $webpage; ?></p>
        <p><b>Indique outras ferramentas de comunicação utilizadas:</b> <?php echo $socials; ?></p>

        <h3>Inscrição</h3>
        <p><b>Perfil:</b> <?php the_field('informe_o_seu_perfil', $post->ID); ?></p>
        <p><b>Cargo:</b> <?php the_field('cargo', $post->ID); ?></p>
        <p><b>Orgão:</b> <?php the_field('orgao', $post->ID); ?></p>
        <p><b>Tipo:</b> <?php the_field('tipo', $post->ID); ?></p>
        <p><b>Entidade/Coletivo que representa:</b> <?php the_field('entidadecoletivo_que_representa', $post->ID); ?></p>
        <p><b>Localidade:</b> <?php the_field('localidade', $post->ID); ?></p>
        <p><b>Função:</b> <?php the_field('funcao', $post->ID); ?></p>
        <p><b>Indique a Unidade da Federação onde você participará da oficina:</b> <?php the_field('indique_a_unidade_da_federacao_onde_voce_participara_da_oficina', $post->ID); ?></p>
        <p>
            <b>Numere por ordem de prioridade até 5 áreas de interesse:</b>
            <?php the_field('numere_por_ordem_de_prioridade_ate_5_areas_de_interesse', $post->ID); ?>
        </p>


        <h3>Perfil Gestor de Cultura</h3>
        <p>
            <b>O ente federado já institucionalizou (criou as leis ou normativos) o sistema de cultura local:</b>
            <?php the_field('o_ente_federado_ja_institucionalizou_criou_as_leis_ou_normativos_o_sistema_de_cultura_local_se_sim_marque_os_componentes_ja_institucionalizados', $post->ID); ?>
        </p>
        <p>
            <b>Outros:</b>
            <?php the_field('outros', $post->ID); ?>
        </p>
        <p>
            <b>O ente federado possui lei de Cultura Viva local?:</b>
            <?php the_field('o_ente_federado_possui_lei_de_cultura_viva_local', $post->ID); ?>
        </p>
        <p>
            <b>Qual?:</b>
            <?php the_field('qual', $post->ID); ?>
        </p>


        <p>
            <b>Você já participou de alguma ação de capacitação na área da cultura ofertado pelo Governo Federal?:</b>
            <?php the_field('voce_ja_participou_de_alguma_acao_de_capacitacao_na_area_da_cultura_ofertado_pelo_governo_federal', $post->ID); ?>
        </p>
        <p>
            <b>O seu ente federado oferta capacitação na área da cultura?:</b>
            <?php the_field('o_seu_ente_federado_oferta_capacitacao_na_area_da_cultura', $post->ID); ?>
        </p>
        <p>
            <b>Você já participou de algum curso?</b>
            <?php the_field('voce_ja_participou_de_algum_curso', $post->ID); ?>
        </p>
        <p>
            <b>Seu ente federado possui rede de pontos de cultura?</b>
            <?php the_field('seu_ente_federado_possui_rede_de_pontos_de_cultura', $post->ID); ?>
        </p>
        <p>
            <b>Quantos Pontos e Pontões de Cultura fazem parte da sua Rede?</b>
            <?php the_field('quantos_pontos_e_pontoes_de_cultura_fazem_parte_da_sua_rede', $post->ID); ?>
        </p>
        <p>
            <b>Tem interesse em implementar a Rede de Pontos de Cultura em sua região?</b>
            <?php the_field('tem_interesse_em_implementar_a_rede_de_pontos_de_cultura_em_sua_regiao', $post->ID); ?>
        </p>
        <p>
            <b>O seu ente federado possui conselho de cultura em funcionamento?</b>
            <?php the_field('o_seu_ente_federado_possui_conselho_de_cultura_em_funcionamento', $post->ID); ?>
        </p>
        <p>
            <b>Qual a duração do mandato?</b>
            <?php the_field('qual_a_duracao_do_mandato', $post->ID); ?>
        </p>
        <p>
            <b>O ente federado realizou conferência de cultura?</b>
            <?php the_field('o_ente_federado_realizou_conferencia_de_cultura', $post->ID); ?>
        </p>
        <p>
            <b>Quando ocorreu a última edição?</b>
            <?php the_field('quando_ocorreu_a_ultima_edicao', $post->ID); ?>
        </p>
        <p>
            <b>Qual o temário da última edição?</b>
            <?php the_field('qual_o_temario_da_ultima_edicao', $post->ID); ?>
        </p>

        <h3>Perfil Ponteiro de Cultura</h3>

        <p>
            <b>Possui certificação emitida pela Plataforma da Rede Cultura Viva?</b>
            <?php the_field('possui_certificacao_emitida_pela_plataforma_da_rede_cultura_viva', $post->ID); ?>
        </p>

        <p>
            <b>Possui certificação</b>
            <?php the_field('possui_certificacao', $post->ID); ?>
        </p>

        <p>
            <b>Participou de ação de capacitação para entidades e coletivos culturais pelo estado/município?</b>
            <?php the_field('participou_de_acao_de_capacitacao_para_entidades_e_coletivos_culturais_pelo_estadomunicipio', $post->ID); ?>
        </p>

        <h3>Perfil Sociedade Civil</h3>
        <p>
            <b>Qual a sua área de atuação?</b>
            <?php the_field('qual_a_sua_area_de_atuacao_ex:_danca_teatro_musica_cultura_popular_cinema_artesanato_', $post->ID); ?>
        </p>

        <?php return ob_get_clean();
    }

    private function is_user_registered_in_workshop()
    {
        if ( is_user_logged_in() ) {
            $current_user = get_current_user_id();
            $post = get_posts([
                'author'        =>  $current_user,
                'orderby'       =>  'post_date',
                'order'         =>  'ASC',
                'post_type'     => 'inscricao-oficina',
                'posts_per_page' => 1
            ]);

            return current($post);
        }

        return false;
    }

}