<?php

$urlOrigem = "http://base-wp.cultura.gov.br";
$urlDestino = "http://base-wp.localhost";
$dominioOrigem = "cultura.gov.br";
$dominioDestino = "base-wp.localhost";

print "<h3>Atualizando: de '{$urlOrigem}' para '{$urlDestino}'<h3>";
print "<h4>Atualizações do blog 1</h4>";

$sqlUpdateOptions = "UPDATE wpminc_options SET option_value = '{$urlDestino}' WHERE option_name = 'siteurl';<br>";
$sqlUpdateOptions .= "UPDATE wpminc_options SET option_value = '{$urlDestino}' WHERE option_name = 'home';<br>";
$sqlUpdateSiteDomain = "UPDATE wpminc_site SET domain = '{$dominioDestino}' WHERE id = 1;<br>";

print $sqlUpdateOptions;
print "<br/>";
print $sqlUpdateSiteDomain;
print "<br/>";

$sqlUpdateSitemeta = "UPDATE wpminc_sitemeta SET meta_value = '{$urlDestino}' WHERE meta_key = 'siteurl';";
print $sqlUpdateSitemeta;
print "<br/>";

$sqlUpdateBlogPrincipal ="UPDATE wpminc_blogs SET domain = '{$dominioDestino}') WHERE blog_id = 1;";
print $sqlUpdateBlogPrincipal;
print "<br/>";


print "<hr>";


print "<h4>Atualizando sites para endereço local</h4>";


### Atualizando configurações de cada site

$idSite = 26;
$siteOrigem = "culturaviva.base-wp.cultura.gov.br";
$siteDestino = "culturaviva.base-wp.localhost";

print "<h5>Atualizando '$siteOrigem' para '$siteDestino'</h5>";

$sqlUpdateBlogs ="UPDATE wpminc_blogs SET domain = '{$siteDestino}' WHERE blog_id = {$idSite};";
print $sqlUpdateBlogs;
print "<br>";

$sqlDomainMap = "UPDATE wpminc_domain_mapping SET domain = '{$siteDestino}' WHERE domain = '{$siteOrigem}';";
print $sqlDomainMap;
print "<br>";

$sqlUpdateSiteOptions = "UPDATE wpminc_" . $idSite . "_options SET option_value = '{$siteDestino}' WHERE option_name = 'siteurl';<br>";
$sqlUpdateSiteOptions .= "UPDATE wpminc_" . $idSite . "_options SET option_value = '{$siteDestino}' WHERE option_name = 'home';<br>";
$sqlUpdateSiteOptions .= "UPDATE wpminc_" . $idSite . "_options SET option_value = '{$siteDestino}' WHERE option_name = 'fileupload_url';";

print $sqlUpdateSiteOptions;
print "<hr/>";



### Wp cli para o resto
/*
Caso seja necessário atualizar urls, você pode utilizar o wp cli.

    # Atualizando links e endereços em posts
    cd [path_to_wp_install]
    wp search-replace 'base-wp.cultura.gov.br' $ambienteDestino wp_posts
*/

print "wp search-replace '{$siteOrigem}' '{$siteDestino}' wp_26_options";