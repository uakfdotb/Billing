<?php
namespace App\AdminBundle\Helper;

class Curl
{
    public static function call($targetUrl, $data = array(), $method = 'POST', $username = null, $password = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Expect:"));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        if ($username !== null && $password !== null) {
            curl_setopt($curl, CURLOPT_USERPWD, $username . ':' . $password);
        }
        if ($method == 'POST') {
            curl_setopt($curl, CURLOPT_URL, $targetUrl);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        } else {
            curl_setopt($curl, CURLOPT_URL, $targetUrl . '?' . http_build_query($data));
            curl_setopt($curl, CURLOPT_POST, false);
        }
        $result = curl_exec($curl);
        if ($result == false) {
            return curl_error($curl);
        }
        curl_close($curl);

        return $result;
    }
}