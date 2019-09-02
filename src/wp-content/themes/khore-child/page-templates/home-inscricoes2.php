<?php
 /**
 * Template Name: Inscricoes2
 *
 */

$lang = $GLOBALS['q_config']['language'];
$file_lang = get_template_directory() .'/../khore-child/includes/paises-' . $lang . '.php';
include($file_lang);

$required = [];
$error_message = "";
$inscricao_id = null;

$fieldsDefinition = [];
$fieldsDefinition['nome_completo'] = ['required'=> true, 'type'=> 'text'];
$fieldsDefinition['data_inscricao'] = ['required'=> false, 'type'=> 'text'];
$fieldsDefinition['email'] = ['required'=> true, 'type'=> 'text'];
$fieldsDefinition['telefone'] = ['required'=> true, 'type'=> 'text'];
$fieldsDefinition['coletivo_entidade'] = ['required'=> true, 'type'=> 'text'];
$fieldsDefinition['cidade'] = ['required'=> true, 'type'=> 'text'];
$fieldsDefinition['uf'] = ['required'=> true, 'type'=> 'text'];
$fieldsDefinition['pais'] = ['required'=> true, 'type'=> 'text'];
$fieldsDefinition['necessidade_especial'] = ['required'=> false, 'type'=> 'text'];
$fieldsDefinition['receber_informacoes'] = ['required'=> false, 'type'=> 'text'];

if (!empty($_POST)) {
    $f = [];
    $errors = [];
    
    foreach ($fieldsDefinition as $key => $value) {
        $f[$key] = str_replace("\'", "", str_replace("\"", "", htmlspecialchars($_POST[$key])));
        
        // verifica preenchimento obrigatório
	//        if ($value['required']) {
        // sanitiza entradas
    }
    if (empty($errors)) {
        // insercao
        $f['data_inscricao'] = date("Y-m-d h:i:s");

        $inscricao_id = add_inscricao($f);
        $sub = [];
        $sub['en'] = 'Emergências: Application completed!';
	$sub['es'] = 'Emergências: Inscripción realizada con éxito!';
        $sub['pb'] = 'Emergências: Inscrição realizada com sucesso!';
        send_mail_user($f['email'], $sub[$lang]);
    } else {
        $error_message = __('Fill in all required fields! <br><br>', 'khore-child');
    }   
}

$uf_list = ['Outro/Otro/Other', 'AC', 'AL', 'AM', 'AP', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MG', 'MS', 'MT', 'PA', 'PB', 'PE', 'PI', 'PR', 'RJ', 'RN', 'RO', 'RR', 'RS', 'SC', 'SE', 'SP', 'TO'];

get_header();

if (have_posts()) :
    while (have_posts()) :
        the_post();
        global $post;
        $page_class = is_front_page() ? 'page_index front-page' : "page_$post->post_name";
        ?>
<style>
header, #wpadminbar {
  display: none;
}
.page__scroll {
  background-color: #fff;
}

</style>
<script type='text/javascript'>
    var self = this;

    $(document).on('ready', function() {
        window.parent.scrollTo(0, 0);
    });

</script>
        <div class="site__content light">
            <div class="page <?php echo $page_class; ?> light page_loaded page_active" data-id="<?php echo $post->ID; ?>">
                <div class="page__scroll">
                                    <a name='topo'></a>

		<div class="mainContent">
		
				<div class="content">

				<article class="topcontent">	
<?php if ($inscricao_id != null) : ?>
            <div id="inscricao-realizada">
            <div>
            <h3><?php _e('Subscription succeded!', 'khore-child'); ?></h3>
            <h4><?php _e('Take note of your subscription data', 'khore-child'); ?>:</h4>
            </div>
            <div>
               <span>
                  <strong><?php _e('Subscription code', 'khore-child'); ?>:</strong> <?php echo $inscricao_id; ?>
               </span>
            </div>
            <div>
               <span>
                  <strong><?php _e('Full name', 'khore-child'); ?>:</strong> <?php echo $f['nome_completo'] ?>
               </span>
            </div>
            <div>
               <span>
                  <strong><?php _e('E-mail', 'khore-child'); ?>:</strong> <?php echo $f['email'] ?>
               </span>
            </div>
            <div>
               <span>
                  <strong><?php _e('Phone', 'khore-child'); ?>:</strong> <?php echo $f['telefone'] ?>
               </span>
            </div>
            <div>
               <span>
                  <strong><?php _e('Collective / Entity / Organization', 'khore-child'); ?>:</strong> <?php echo $f['coletivo_entidade'] ?>
               </span>
            </div>
            <div>
               <span>
                  <strong><?php _e('City', 'khore-child'); ?>:</strong> <?php echo $f['cidade'] ?>
               </span>
            </div>
            <div>
               <span>
                  <strong><?php _e('State', 'khore-child'); ?>:</strong> <?php echo $f['uf'] ?>
               </span>
            </div>
            <div>
               <span>
                  <strong><?php _e('Country', 'khore-child'); ?>:</strong> <?php echo $f['pais'] ?>
               </span>
            </div>
            <div>
               <span>
                  <strong><?php _e('Do you have any special needs?', 'khore-child'); ?>? </strong> <?php echo $f['necessidade_especial'] ?>
               </span>
            </div>
            <div>
               <span>
                  <strong><?php _e('Would you like to receive informations from Brazil Culture Ministry?', 'khore-child'); ?>? </strong> <?php echo $f['receber_informacoes'] ?>
               </span>
            </div>
</div>
            
<?php else: ?>
                        <?php the_content(); ?>
                        <div class="error_messages">
                           <span><?php echo $error_message; ?></span>
                        </div>

                    <div class="form-inscricoes">


		<div class="mainContent">
		
				<div class="content">

				<article class="topcontent">	

					<!-- Application Form -->
                        <form method="POST" id="inscricoes" name="inscricoes">
		
					<!-- Application Form Header -->
                    <h3><?php _e('Subscription', 'khore-child'); ?>  </h3>
					<!-- End Application Form Header -->
		
		
					<!-- Personal Infomation -->
            <h4> <i class="icon-user"></i><?php _e('User data', 'khore-child'); ?> </h4>
				
					<label> 
                            <input class="form-input nome_completo <?php echo $required['nome_completo']; ?>" type="text" name="nome_completo" value="<?php echo isset($f['nome_completo'])?$f['nome_completo']:'';?>" placeholder="<?php _e('Full name', 'khore-child'); ?>" required /></label>
		
					<label> <input type="email" placeholder="<?php _e('E-mail', 'khore-child'); ?>"  required class="form-input email <?php echo $required['email']; ?>" name="email" value="<?php echo isset($f['email'])?$f['email']:'';?>" />
 </label>
                    
                    <label> <input placeholder="<?php _e('Phone number (with international and local codes)', 'khore-child'); ?>" required class="form-input telefone <?php echo $required['telefone']; ?>" type="text" name="telefone" value="<?php echo isset($f['telefone'])?$f['telefone']:'';?>"> </label>

					<label> <input class="form-input coletivo_entidade <?php echo $required['coletivo_entidade']; ?>" type="text" name="coletivo_entidade" value="<?php echo isset($f['coletivo_entidade'])?$f['coletivo_entidade']:'';?>" placeholder="<?php _e('Collective / Entity / Organization', 'khore-child'); ?>" required> </label>
                    
                    <label> <input class="form-input cidade <?php echo $required['cidade']; ?>" type="text" name="cidade" value="<?php echo isset($f['cidade'])?$f['cidade']:'';?>" placeholder="<?php _e('City', 'khore-child'); ?>" required > </label>
                    
                    <label> 
                            <select class="uf form-select  <?php echo $required['UF']; ?>" type="text" name="uf">
                               <option value=""><?php _e('Fill in the state (if applicable)', 'khore-child'); ?></option>
                               <?php foreach ($uf_list as $uf): ?>
                               <option value="<?php echo $uf; ?>" <?php echo isset($f['uf']) ? 'selected' : ''; ?>><?php echo $uf; ?></option>            
                               <?php endforeach; ?>
                               </select>
                               </label>
                               
                     <label>
                            <select class="pais form-select  <?php echo $required['pais']; ?>" type="text" name="pais" required>
<?php foreach ($paises as $pais): ?>
         <option value="<?php echo $pais; ?>"><?php echo $pais; ?></option>
<?php endforeach; ?>
                            </select>
                     
                     <h4> <i class="icon-user"></i><?php _e('Do you have any special needs?', 'khore-child'); ?></h4>
                     <label> <input class="form-input necessidade_especial <?php echo $required['necessidade_especial']; ?>" type="text" name="necessidade_especial" value="<?php echo isset($f['necessidade_especial'])?$f['necessidade_especial']:'';?>" > </label>    
                     
                     <h4> <i class="icon-user"></i><?php _e('Would you like to receive informations from Brazil Culture Ministry?', 'khore-child'); ?> </h4>
<label class="radio"><input class="form-radio receber_informacoes receber_informacoes_sim" type="radio" name="receber_informacoes" value="<?php echo isset($f['receber_informacoes'])?$f['receber_informacoes']:'sim';?>" name="cultura" checked><?php _e('Yes', 'khore-child'); ?></label>
				
					<label class="radio"><input class="form-radio receber_informacoes" type="radio" name="receber_informacoes" value="<?php echo isset($f['receber_informacoes'])?$f['receber_informacoes']:'não';?>"><?php _e('No', 'khore-child'); ?></label>
					<!-- End Personal Infomation -->
				
				
					
					<!-- End Comments & Messages -->
				
					<!-- Submit Button -->
                        <button type="submit" class="form-button enviar" name="enviar"><i class="icon-envelope"></i><?php _e('Submit', 'khore-child'); ?></button>
					<!-- End Submit Button-->
			
				</form>
				<!-- End Application Form  -->
            
                        </form>
                    </div>
<?php endif; ?>
	
				</article>
				
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
