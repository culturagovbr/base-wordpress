<?php
/**
 * Funções para os relatórios
 * Fernão Lopes - fernao@riseup.net
 * Ministério da Cultura
 *
 */


function get_inscritos() {
    global $wpdb;

    $sql = "SELECT * FROM wpmc_inscricoes ORDER BY ID desc";
    $results = $wpdb->get_results($sql);
    
    return $results;
}


function get_total_inscritos() {
    global $wpdb;
    $sql = "SELECT COUNT(ID) FROM wpmc_inscricoes";
    $result = $wpdb->get_var($sql);
    return $result;
}
