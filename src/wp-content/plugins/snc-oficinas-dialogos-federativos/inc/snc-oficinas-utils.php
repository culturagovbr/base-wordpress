<?php

if (!defined('WPINC'))
    die();

class SNC_Oficinas_Utils
{
    static function generate_token($add = null)
    {
        return md5(uniqid(rand(), true) . $add);
    }

    static function get_status_label_subscription($status)
    {
        $label = '';

        switch ($status) {
            case 'pending':
                $label = 'Confirmação Pendente';
                break;
            case 'canceled':
                $label = 'Cancelada';
                break;
            case 'waiting_list':
                $label = 'Lista de espera';
                break;
            case 'confirmed':
                $label = 'Confirmada';
                break;
            case 'waiting_presence':
                $label = 'Aguardando Presença';
                break;
            case 'waiting_questions':
                $label = 'Aguardando Questionário';
                break;
            case 'finish':
                $label = 'Finalizada';
                break;
        }
        return $label;
    }
}