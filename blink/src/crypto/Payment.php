<?php


namespace Blink;
defined('ABSPATH') or die;


use Blink\Crypto\Utils;

class Payment
{
    public $paymentInfoSignature;
    public $merchantPublicKey;
    public $resourceId;
    public $offerId;
    public $amount;
    public $currencyIsoCode;
    public $comment;
    private $private_key;

    /**
     * BlinkPayment constructor.
     *
     * @param float  $amount          -> Price of the content
     * @param string $resourceId      -> Unique identifier of the content
     * @param string $offerId         -> Unique identifier of the current offering of the content
     * @param string $currencyIsoCode -> Iso code of the currency, in which the price is set ( e.g 'USD' )
     * @param string $private_key     -> Private key of the merchant as hex string
     * @param string $post_title      -> Post title that will show up in the user purchase history tab
     * @param string $post_url        -> Post url that the user will see in the purchase history tab
     */
    public function __construct(
        $amount,
        $resourceId,
        $offerId,
        $currencyIsoCode,
        $private_key,
        $post_title,
        $post_url
    )
    {
        $this->amount = $amount * Constants::USD_CURRENCY_MULTIPLIER;
        $this->resourceId = $resourceId;
        $this->offerId = $offerId;
        $this->currencyIsoCode = $currencyIsoCode;
        $this->private_key = $private_key;
        $this->comment = new PostComment($post_title, $post_url);
    }

    public function getPaymentAsDict()
    {
        // Transform public fields of object to associative array
        // the associative array will be injected in a javascript object
        return json_decode(
            $this->getPaymentInfo(),
            true
        );
    }

    public function getPaymentInfo()
    {
        try {
            $payment_info = new PaymentInfo($this->amount, $this->currencyIsoCode, $this->offerId, $this->resourceId);
            $this->paymentInfoSignature = $payment_info->getSignature($this->private_key);
            $this->merchantPublicKey = Utils::privateToPublicKey($this->private_key);
        } catch (BlinkCryptoException $e) {
            //echo $e->getMessage();
            //TODO(Mike) maybe send admin notification when if any error occurred
        }
        return json_encode($this);
    }

    public function getUrlEncodePayment()
    {
        return base64_encode($this->getPaymentInfo());
    }


}
