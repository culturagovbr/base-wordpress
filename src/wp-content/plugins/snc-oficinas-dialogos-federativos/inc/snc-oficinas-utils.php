<?php

class SNC_Oficinas_Utils
{
    static function generate_token()
    {
        return md5(uniqid(rand(), true));
    }

}
