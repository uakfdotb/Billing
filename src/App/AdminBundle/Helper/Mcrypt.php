<?php
namespace App\AdminBundle\Helper;

class Mcrypt
{
    public $container;

    public function __construct($container)
    {
        $this->container = $container;
    }

    public function encrypt($plaintext)
    {
        $key = $this->container->getParameter('secret');
        $iv  = $this->container->getParameter('iv');

        $plaintext = utf8_encode($plaintext);

        $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $plaintext, MCRYPT_MODE_CBC, $iv);
        return base64_encode($ciphertext);
    }

    public function decrypt($ciphertext)
    {
        $ciphertext = base64_decode($ciphertext);
        $key        = $this->container->getParameter('secret');
        $iv         = $this->container->getParameter('iv');

        $plaintext = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext, MCRYPT_MODE_CBC, $iv);
        return rtrim(utf8_decode($plaintext), "\0");
    }
}
