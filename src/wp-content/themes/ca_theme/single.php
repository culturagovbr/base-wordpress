<?php

$post = $wp_query->post;

if (in_category('conversas')) {
include (TEMPLATEPATH.'/single-conversas.php');
return;
}


if (in_category('noticias')) {
include (TEMPLATEPATH.'/single-noticias.php');
return;
}

/* se não for nenhuma das categorias acima */
else { ?>
<? include (TEMPLATEPATH.'/single-default.php'); ?>

<?php } ?>