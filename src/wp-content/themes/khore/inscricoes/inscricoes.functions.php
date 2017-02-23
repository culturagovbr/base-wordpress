<?php
// funçoes para inscricoes

/*
global $wpdb;

$charset_collate = $wpdb->get_charset_collate();

$sql = "CREATE TABLE $table_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
  name tinytext NOT NULL,
  text text NOT NULL,
  url varchar(55) DEFAULT '' NOT NULL,
  UNIQUE KEY id (id)
) $charset_collate;";

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );
*/


function add_inscricao($data) {
    global $wpdb;
    
    $wpdb->insert('wpmc_inscricoes', $data);
    
    return $wpdb->insert_id;    
}

function send_mail_user($user_email, $subject) {
$lang = $GLOBALS['q_config']['language'];
putenv("LC_ALL=$lang");
setlocale(LC_ALL, $lang);

    $mail_from = "contato.emergencias@cultura.gov.br";
    $from = sprintf("%s <%s>", $mail_from, $mail_from);
    $to = $user_email;
    
    include('modelo.mail.inscricao.php');
    $message = ob_get_contents();
    ob_end_clean();
    
    $header = "From: $mail_from\r\n";
    $header .= "Content-Type: text/html\r\n";
    
    wp_mail( $to, $subject, $message, $header );
}
