<?php
if (!get_option('inscricoes-db-update-1')) {
    update_option('inscricoes-db-update-1', 1);
    
    require(get_template_directory() . '/inscricoes/db.update.inscricoes.php');
    
    global $wpdb;
    $wpdb->query($sql);
}

if (!get_option('inscricoes-db-update-2')) {
    update_option('inscricoes-db-update-2', 1);
    
    require(get_template_directory() . '/inscricoes/db.alter.inscricoes.php');
    
    global $wpdb;
    $wpdb->query($sql);
}

