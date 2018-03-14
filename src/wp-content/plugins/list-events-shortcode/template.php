<div class="content list_events">
	<div class="row top">
		<div class="col-md-12">
			<h3><?= $atts['title']; ?></h3>
			<div id="date_listevents" data-range="<?= $dataRange; ?>" data-baseurl="<?= $atts['url']; ?>" data-url="<?= $url; ?>" class="pull-right" style="">
			    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
			    <span></span> <b class="caret"></b>
		    </div>			
		</div>
		<div class="col-md-12 text-right">
			<label for="states_list">UF: </label>
			<select id="states_list">
				<option value="">Todos</option>
				<option value="AC">Acre</option>
				<option value="AL">Alagoas</option>
				<option value="AM">Amazonas</option>
				<option value="AP">Amapá</option>
				<option value="BA">Bahia</option>
				<option value="CE">Ceará</option>
				<option value="DF">Distrito Federal</option>
				<option value="ES">Espírito Santo</option>
				<option value="GO">Goiás</option>
				<option value="MA">Maranhão</option>
				<option value="MG">Minas Gerais</option>
				<option value="MS">Mato Grosso do Sul</option>
				<option value="MT">Mato Grosso</option>
				<option value="PA">Pará</option>
				<option value="PB">Paraíba</option>
				<option value="PE">Pernambuco</option>
				<option value="PI">Piauí</option>
				<option value="PR">Paraná</option>
				<option value="RJ">Rio de Janeiro</option>
				<option value="RN">Rio Grande do Norte</option>
				<option value="RO">Rondônia</option>
				<option value="RR">Roraima</option>
				<option value="RS">Rio Grande do Sul</option>
				<option value="SC">Santa Catarina</option>
				<option value="SE">Sergipe</option>
				<option value="SP">São Paulo</option>
				<option value="TO">Tocantins</option>				
			</select>
		</div>
	</div>

	<div id="loading" class="spinner" style="display:none;"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>

	<div class="list_spaces"></div>
</div>
