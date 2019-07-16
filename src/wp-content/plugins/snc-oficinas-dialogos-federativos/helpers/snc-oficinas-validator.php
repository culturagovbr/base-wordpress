<?php

class SNC_Oficinas_Validator
{
    public $fields_rules = array(
        'register' => array(
            'fullname' => array('not_empty'),
            'birthday' => array('not_empty', 'is_a_valid_date'),
            'schooling' => array('not_empty'),
            'gender' => array('not_empty'),
            'cpf' => array('not_empty', 'is_a_valid_cpf', 'user_cpf_does_not_exist'),
            'rg' => array('not_empty'),
            'address' => array('not_empty'),
            'number' => array('not_empty'),
            'state' => array('not_empty'),
            'county' => array('not_empty'),
            'neighborhood' => array('not_empty'),
            'zipcode' => array('not_empty', 'is_a_valid_cep'),
            'phone' => array('not_empty'),
            'celphone' => array('not_empty'),
            'email' => array('not_empty', 'is_valid_email', 'is_email_does_not_exist'),
            'password' => array('not_empty', 'password_length_more_than_5'),
        ),
        'update' => array(
            'fullname' => array('not_empty'),
            'birthday' => array('not_empty', 'is_a_valid_date'),
            'schooling' => array('not_empty'),
            'gender' => array('not_empty'),
            'rg' => array('not_empty'),
            'address' => array('not_empty'),
            'number' => array('not_empty'),
            'state' => array('not_empty'),
            'county' => array('not_empty'),
            'neighborhood' => array('not_empty'),
            'zipcode' => array('not_empty', 'is_a_valid_cep'),
            'phone' => array('not_empty'),
            'celphone' => array('not_empty'),
            'email' => array('not_empty', 'is_valid_email', 'is_email_doest_not_exist_update'),
        ),
    );

    /**
     * Return 'true' if field is valid, an error message if field is invalid
     * or 'null' if field is not recognized
     *
     * @param String $s the step
     * @param String $f the field
     * @param String $v the values ...
     */
    function validate_field($s, $f, $v)
    {
        $args_v = array_slice(func_get_args(), 2);

        if (isset($this->fields_rules[$s]) && isset($this->fields_rules[$s][$f])) {
            foreach ($this->fields_rules[$s][$f] as $function) {
                $result = call_user_func_array(array($this, $function), $args_v);

                if ($result !== true) {
                    return $result;
                }
            }
            return true;
        }
        return null;
    }

    /** @return true if field is require and false otherwise */
    function is_required_field($s, $f)
    {
        return isset($this->fields_rules[$s])
            && isset($this->fields_rules[$s][$f])
            && in_array('not_empty', ($this->fields_rules[$s][$f]));
    }

    /** Return true if parameter is not empty or a message otherwise */
    static function not_empty($v)
    {
        if (!isset($v) || empty($v)) {
            return __('Este item não pode ser vazio');
        }
        return true;
    }

    /** Return true if supplied email is valid or give an error message otherwise */
    static function is_valid_email($e)
    {
        if (is_email($e)) {
            return true;
        }
        return __('O e-mail não tem um formato válido');
    }

    /** Return true if supplied email is valid or give an error message otherwise */
    static function is_email_does_not_exist($e)
    {
        if (email_exists($e)) {
            return __('Já existe um usuário com o e-mail informado');
        }
        return true;
    }

    static function is_email_doest_not_exist_update($e)
    {
        $id_user_email = email_exists($e);
        if (!is_user_logged_in() || $id_user_email != get_current_user_id()) {
            return __('Já existe um usuário com o e-mail informadooo');
        }

        return true;
    }


    /** Return true if supplied cpf is valid or give an error message otherwise */
    static function is_a_valid_cpf($cpf)
    {
        $error = __("O CPF fornecido é inválido.");
        $cpf = preg_replace('/[^0-9]/', '', $cpf);

        if (strlen($cpf) != 11 || preg_match('/^([0-9])\1+$/', $cpf)) {
            return $error;
        }

        // 9 primeiros digitos do cpf
        $digit = substr($cpf, 0, 9);

        // calculo dos 2 digitos verificadores
        for ($j = 10; $j <= 11; $j++) {
            $sum = 0;
            for ($i = 0; $i < $j - 1; $i++) {
                $sum += ($j - $i) * ((int)$digit[$i]);
            }

            $summod11 = $sum % 11;
            $digit[$j - 1] = $summod11 < 2 ? 0 : 11 - $summod11;
        }

        if ($digit[9] == ((int)$cpf[9]) && $digit[10] == ((int)$cpf[10])) {
            return true;
        } else {
            return $error;
        }
    }

    static function user_cpf_does_not_exist($c)
    {
        global $wpdb;

        $result = $wpdb->get_var($wpdb->prepare("SELECT count(1) FROM {$wpdb->usermeta} WHERE"
            . " meta_key='cpf' and meta_value='%s';", $c));

        if ($result > 0) {
            return __('Já existe um usuário cadastrado com este CPF. <a href="' . wp_lostpassword_url() . '">Recuperar senha?</a>');
        }
        return $result == 0; // $result provavelmente é String
    }

    static function is_a_valid_cep($c)
    {
        if (preg_match('/^\d\d\d\d\d-\d\d\d$/', $c)) {
            return true;
        }
        return __('O CEP fornecido é invalido');
    }

    static function is_a_valid_phone($p)
    {
        if (empty($p) || preg_match('/^\(\d\d\) \d{6,9}$/', $p)) {
            return true;
        }
        return __('O número do telefone é invalido');
    }

    static function is_a_valid_date($d)
    {
        $format = "d/m/Y";

        $dateTime = DateTime::createFromFormat($format, $d);

        $errors = DateTime::getLastErrors();
        if (!empty($errors['warning_count']) || !empty($errors['error_count']) || strlen($d) < 10) {
            return __('Formato de data inválido. Por favor apague e tente novamente.');
        }
        return true;
    }

    static function password_length_more_than_5($v) {
        if(strlen(utf8_decode($v)) < 5) { // php não sabe contar utf8
            return __('A senha deve conter mais que 5 caracteres.');
        }
        return true;
    }

}
