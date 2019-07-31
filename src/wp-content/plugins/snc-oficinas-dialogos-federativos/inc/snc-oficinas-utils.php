<?php

class SNC_Oficinas_Utils
{
    static function generate_token()
    {
        return md5(uniqid(rand(), true));
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
            case 'publish':
                $label = 'Confirmada';
                break;
        }
        return $label;
    }

}
