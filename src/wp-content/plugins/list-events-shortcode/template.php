<div class="content">
	<div class="row">
		<div class="col-md-12">
			<h3><?= $atts['title']; ?></h3>
			<div id="date_listevents" data-range="<?= $dataRange; ?>" data-baseurl="<?= $atts['url']; ?>" data-url="<?= $url; ?>" class="pull-right" style="">
			    <i class="glyphicon glyphicon-calendar fa fa-calendar"></i>&nbsp;
			    <span></span> <b class="caret"></b>
		    </div>
		</div>
	</div>

	<div id="loading" class="spinner" style="display:none;"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>

	<div class="list_spaces"></div>
</div>
