jQuery(document).ready(function ($) {
	
//*** Função Menu collapse responsivo*** 
$("#et_mobile_nav_menu").on("click", function(){
	console.log("teste menu");
    $("#top-menu-nav").toggleClass("menu-collapse");
});

/*Leitor de rss script home*/
$('#divRss').FeedEk({
		FeedUrl:'http://www.cultura.gov.br/rss-backup/-/asset_publisher/PBe5d9MJmlrW/rss?p_p_cacheability=cacheLevelPage',
		MaxCount: 5,
		DateFormat: 'DD MMMM YYYY',
		DateFormatLang: 'pt'
	});

 /*Leitor de rss pagina noticias*/
    $('#rss-read').FeedEk({
		FeedUrl:'http://www.cultura.gov.br/rss-backup/-/asset_publisher/PBe5d9MJmlrW/rss?p_p_cacheability=cacheLevelPage',
		MaxCount: 20,
		DateFormat: 'DD MMMM YYYY',
		DateFormatLang: 'pt'
	});

});
