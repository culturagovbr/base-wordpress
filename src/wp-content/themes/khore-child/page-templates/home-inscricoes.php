<?php
/**
 * Template Name: Inscricoes
 *
 */

$required = [];
$error_message = "";
$inscricao_id = null;

if (!empty($_POST)) {
    $f = [];
    $errors = [];
    
    foreach ($_POST as $key => $value) {
        $f[$key] = $value;
        if (!filter_var($value, FILTER_SANITIZE_STRING)) {
            $errors[] = $key;
        }
    }
    if (empty($errors)) {
        // insercao
        $inscricao_id = add_inscricao($f);
    } else {
        foreach ($errors as $key) {
            $required[$key] = 'required';
        }
        $error_message = "Preencha os campos obrigatórios!<br><br>";

    }   
}

$uf_list = ['AC', 'AL', 'AM', 'AP', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MG', 'MS', 'MT', 'PA', 'PB', 'PE', 'PI', 'PR', 'RJ', 'RN', 'RO', 'RR', 'SC', 'SE', 'SP', 'TO'];

get_header();


?>
<style>
.form-inscricoes {
  padding: 30px;
 }
.form-field {
    margin-bottom: 20px;
 }
.form-input {
  width: 400px;
 }
.form-radio {
  width: 50px;
 }
.form-button {
    font-size: 20px;
    font-weight: bold;
  width: 100px;
  color: #fff;
    background-color: #4889f2;
 }
.form-field label {
    font-weight: normal;
    font-size: 18px;
 }
.form-field small {
  color: #666;
 }

.required {
  border: 2px solid #f00;
 }
.error_messages span {
  color: #f00;
 }

</style>
<?php
if (have_posts()) :
    while (have_posts()) :
        the_post();
        global $post;
        $page_class = is_front_page() ? 'page_index front-page' : "page_$post->post_name";
        ?>
        <div class="site__content light">
            <div class="page <?php echo $page_class; ?> light page_loaded page_active" data-id="<?php echo $post->ID; ?>">
                <div class="page__scroll">
                                    <a name='topo'></a>

                    <div class="form-inscricoes">
<?php if ($inscricao_id != null) : ?>
            <div>
            <h3>Inscrição realizada com sucesso! Anote os dados da sua inscrição: </span>
            </div>
            <div>
               <span>
                  <strong>Código da inscrição:</strong> <?php echo $inscricao_id; ?>
               </span>
            </div>
            <div>
               <span>
                  <strong>Nome completo:</strong> <?php echo $f['nome_completo'] ?>
               </span>
            </div>
            <div>
               <span>
                  <strong>Email:</strong> <?php echo $f['email'] ?>
               </span>
            </div>
            <div>
               <span>
                  <strong>Telefone:</strong> <?php echo $f['telefone'] ?>
               </span>
            </div>
            <div>
               <span>
                  <strong>Coletivo / Entidade / Organização:</strong> <?php echo $f['coletivo_entidade'] ?>
               </span>
            </div>
            <div>
               <span>
                  <strong>Cidade:</strong> <?php echo $f['cidade'] ?>
               </span>
            </div>
            <div>
               <span>
                  <strong>UF:</strong> <?php echo $f['uf'] ?>
               </span>
            </div>
            <div>
               <span>
                  <strong>País:</strong> <?php echo $f['pais'] ?>
               </span>
            </div>
            <div>
               <span>
                  <strong>Possui alguma necessidade especial? </strong> <?php echo $f['necessidade_especial'] ?>
               </span>
            </div>
            <div>
               <span>
                  <strong>Gostaria de receber informações do Ministério da Cultura? </strong> <?php echo $f['receber_informacoes'] ?>
               </span>
            </div>
            
<?php else: ?>
                        <?php the_content(); ?>
                        <div class="error_messages">
                           <span><?php echo $error_message; ?></span>
                        </div>

                        <h3>Ficha de inscrição</h3>
                        <form method="POST" id="inscricoes" name="inscricoes">
                        <div class="form-field">
                            <label for="nome_completo">Nome completo</label><br/>
                            <input class="form-input nome_completo <?php echo $required['nome_completo']; ?>" type="text" name="nome_completo" value="<?php echo isset($f['nome_completo'])?$f['nome_completo']:'';?>" />
                        </div>
                        <div class="form-field">
                            <label for="email">E-mail</label><br/>
                            <input class="form-input email <?php echo $required['email']; ?>" type="text" name="email" value="<?php echo isset($f['email'])?$f['email']:'';?>" />
                        </div>
                        <div class="form-field">                        
                            <label for="telefone">Telefone</label><br/>
                            <small><span>Com DDD e operadora</span></small><br/>
                            <input class="form-input telefone <?php echo $required['telefone']; ?>" type="text" name="telefone" value="<?php echo isset($f['telefone'])?$f['telefone']:'';?>" />
                        </div>
                        <div class="form-field">                        
                            <label for="coletivo_entidade">Coletivo / Entidade / Organização</label><br/>
                            <input class="form-input coletivo_entidade <?php echo $required['coletivo_entidade']; ?>" type="text" name="coletivo_entidade" value="<?php echo isset($f['coletivo_entidade'])?$f['coletivo_entidade']:'';?>" />
                        </div>
                        <div class="form-field">
                            <label for="cidade">Cidade</label><br/>
                            <input class="form-input cidade <?php echo $required['cidade']; ?>" type="text" name="cidade" value="<?php echo isset($f['cidade'])?$f['cidade']:'';?>" />
                        </div>
                        <div class="form-field">
                            <label for="uf">UF</label><br/>
                            <select class="uf form-select  <?php echo $required['UF']; ?>" type="text" name="uf">
                               <option value="">Selecione</option>
                               <?php foreach ($uf_list as $uf): ?>
                               <option value="<?php echo $uf; ?>" <?php echo isset($f['uf']) ? 'selected' : ''; ?>><?php echo $uf; ?></option>            
                               <?php endforeach; ?>
                               </select>
                            </div>
           
                         <div class="form-field">
                            <label for="pais">País</label><br/>
                            <input type="text" class="form-input pais  <?php echo $required['pais']; ?>" name="pais" value="<?php echo isset($f['pais'])?$f['pais']:'';?>" />
                        </div>
                        <div class="form-field">
                            <label for="necessidade_especial">Possui alguma necessidade especial?</label><br/>
                            <input class="form-input necessidade_especial <?php echo $required['necessidade_especial']; ?>" type="text" name="necessidade_especial" value="<?php echo isset($f['necessidade_especial'])?$f['necessidade_especial']:'';?>" />
                        </div>
                        <div class="form-field">
                            <label for="receber_informacoes">Gostaria de receber informações do Ministério da Cultura?</label><br/>
                            <input class="form-radio receber_informacoes" type="radio" name="receber_informacoes" value="<?php echo isset($f['receber_informacoes'])?$f['receber_informacoes']:'sim';?>" /> Sim <br/>
                            <input class="form-radio receber_informacoes" type="radio" name="receber_informacoes" value="<?php echo isset($f['receber_informacoes'])?$f['receber_informacoes']:'não';?>" /> Não       
                        </div>

                        <div class="form-field">
                            <input type="submit" class="form-button enviar" value="Enviar" name="enviar">
                        </div>
            
                        </form>
<?php endif; ?>
                   </div>
                </div>
            </div>
        </div>
        <?php
    endwhile;
endif;
?>
<?php
get_footer();
