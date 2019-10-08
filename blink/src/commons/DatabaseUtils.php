<?php


namespace Blink\Commons;

use Blink\Constants;
use Blink\Crypto\Utils;
use Blink\Payment;

defined('ABSPATH') or die;


class DatabaseUtils
{
    /** Creates or updates the payment information associated to each post and stores it the {prefix}_postmeta database scheme.
     * @param int         $post_id           -> Post database ID
     * @param float       $price             -> Price of the content
     * @param string      $currency_iso_code -> Iso code of the currency ( e.g 'USD' )
     * @param string      $private_key       -> Private key of the merchant
     * @param string      $post_title        -> Post title that will show up in the user purchase history tab
     * @param string      $post_url          -> Post url that the user will see in the purchase history tab
     * @param string|null $resource_id       -> Unique identifier for the article, if not specified a UUID is generated
     *
     * @return payment information as JSON
     *
     */
    public static function createOrUpdatePaymentInformation($post_id, $price, $currency_iso_code, $private_key, $post_title, $post_url, $resource_id = null)
    {
        if ($resource_id == null) {
            $resource_id = Utils::generateUniqueResourceId();
        }
        $payment = new Payment(
            $price,
            $resource_id,
            $resource_id,
            $currency_iso_code,
            $private_key,
            $post_title,
            $post_url,
        );
        $paymentInfo = $payment->getPaymentInfo();
        update_post_meta(
            $post_id,
            Constants::ARTICLE_RESOURCE_PAYMENT_INFO_METADATA_KEY,
            $paymentInfo
        );
        return $paymentInfo;
    }
}
