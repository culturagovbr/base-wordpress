<?php
/**
 * Script para importar dados de planilhas para tabela de inscrições / Emergências
 * Fernão Lopes - fernao@riseup.net
 * Ministério da Cultura
 *
 */

function get_connection_data() {
    return [
        'servername' => '192.168.16.2',
        'username' => 'usr_emergencias',
        'password' => '2uN3Fd80ajTGU8X',
        'dbname' => 'dbemergencias'
    ];
}

function start_conn() {
    $connection_data = get_connection_data();
    
    $conn = new mysqli($connection_data['servername'], $connection_data['username'], $connection_data['password'], $connection_data['dbname']);
    
    if ($conn->connect_error) {
    die('erro de conexão: ' . $conn->connect_error);
    } else {
        return $conn;
    }
}

function insert_row($row) {
    $connection_data = get_connection_data();  
    
    $sql = "INSERT INTO wpmc_inscricoes (nome_completo, email, coletivo_entidade, cidade, uf, pais, necessidade_especial, receber_informacoes, telefone, data_inscricao)  VALUES (\"" . implode('","', $row) . "\")";
    
    $conn = start_conn();
    if ($conn->query($sql)) {
        return true;
    } else {
        echo $sql . "<br>";
        echo $conn->error;
    }
}

function uf_hack($row) {
    $return_row = [];
    
    $cidade_uf = $row[3];
    $cidade = '';
    $uf = [''];
    
    $pattern_slash = '/^(.*)\/([a-zA-Z]{2})$/';
    $pattern_white = '/^(.*)\s([a-zA-Z]{2})$/';    
    if (preg_match($pattern_slash, $cidade_uf, $matches) || preg_match($pattern_white, $cidade_uf, $matches)) {
        $cidade = $matches[1];
        $uf = [$matches[2]];
    }
    
    $cidade = str_replace('/', '', ($cidade != '') ? $cidade : $cidade_uf);
    $return_row = array_merge(array_slice($row, 0, 3), [$cidade], $uf, array_slice($row, 4));
    
    return $return_row;    
}

function filter_data($row) {
    $pattern = array(
        '/\"/',    // "
        '/\//',    // /
        "/´/",     // ´
        "/'/"      // '
    );
    
    $row = preg_replace($pattern, '', $row);
    return $row;
}

function fix_date($row) {
    $date_arr = explode(' ', $row[9]);
    $date = $date_arr[0];
    $time = $date_arr[1];
    
    $row[9] = substr($date, -4) . '-' . substr($date, 0, 2) . '-' . substr($date, 2, 2) . ' ' . $time;
    
    return $row;
}

function handle_encoding($row) {
    $rows = [];
    foreach ($row as $field) {
        $field = addslashes($field);
        $rows[] =  utf8_decode($field);
    }
    return $rows;
}

    
// importa arquivos do csv
$fo = fopen('inscricoes-emergencias.csv', 'r');
$header = [];
$content = [];

while($read_data = fgetcsv($fo)) {
    if (empty($header)) {
        $header = $read_data;
    } else {
        $read_data = filter_data($read_data);
        $data[] = $read_data;
        
        $read_data = uf_hack($read_data);
        $read_data = fix_date($read_data);
        $read_data = handle_encoding($read_data);
        insert_row($read_data);        
    }
}
