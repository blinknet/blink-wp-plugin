<?php


namespace Blink\Crypto;
defined('ABSPATH') or die;


use Blink\BlinkCryptoException;

class Utils
{
    /**
     * Extracts the public key of the account from the private key
     *
     * @param $private_key
     *
     * @return string
     * @throws \Blink\BlinkCryptoException
     */
    public static function privateToPublicKey($private_key)
    {
        if (strlen($private_key) != 128) {
            throw new BlinkCryptoException("Private Key must have 128 hexadecimal characters");
        }
        $private_key = hex2bin($private_key);
        $public_key = substr($private_key, 32, strlen($private_key));
        return bin2hex($public_key);
    }

    public static function signMessage($message, $privateKey)
    {
        return bin2hex(sodium_crypto_sign_detached($message, hex2bin($privateKey)));
    }


    public static function generateUniqueResourceId()
    {
        return hash('sha256', uniqid());
    }

    public static function sha256($guid)
    {
        return hash('sha256', $guid);
    }
}
