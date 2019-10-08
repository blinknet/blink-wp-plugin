<?php

namespace Blink;
defined('ABSPATH') or die;


use SodiumException;

class PaymentInfo
{
    public $amount;
    public $currencyIsoCode;
    public $offerId;
    public $resourceId;

    public function __construct($amount, $currencyIsoCode, $offerId, $resourceId)
    {
        $this->amount = $amount;
        $this->currencyIsoCode = $currencyIsoCode;
        $this->offerId = $offerId;
        $this->resourceId = $resourceId;
    }

    /** Returns the signature of the payment info
     * @param $private_key
     * @return string
     * @throws BlinkCryptoException
     */
    public function getSignature($private_key)
    {
        $message = json_encode($this);
        try {
            $signature = sodium_crypto_sign_detached($message, hex2bin($private_key));
        } catch (SodiumException $exception) {
            throw new BlinkCryptoException($exception->getMessage());
        }
        return bin2hex($signature);
    }
}