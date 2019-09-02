<?php

class Geocoder {

    static private $url = "http://maps.google.com/maps/api/geocode/json?sensor=false&address=";

    static public function getLocation($address) {
        $url = self::$url . urlencode($address);

        $resp_json = self::curl_file_get_contents($url);
        $resp = json_decode($resp_json, true);

        if ($resp['status'] == 'OK') {
            return $resp['results'][0]['geometry']['location'];
        } else {
            return false;
        }
    }

    static private function curl_file_get_contents($URL) {
        $c = curl_init();
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_URL, $URL);
        $contents = curl_exec($c);
        curl_close($c);

        if ($contents)
            return $contents;
        else
            return FALSE;
    }

}