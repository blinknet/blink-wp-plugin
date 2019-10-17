<?php


namespace Blink;


use Blink\Commons\DatabaseUtils;

class PaymentInfoGenerator
{
    public static function generatePaymentInfo()
    {
        global $post;

        if(empty($post)){
            return;
        }

        if(is_admin()){
            return;
        }
        // Try to get the previously generated payment info
        $paymentInfo = get_post_meta($post->ID, Constants::ARTICLE_RESOURCE_PAYMENT_INFO_METADATA_KEY, true);

        if (empty($paymentInfo)) {
            $privateKey = get_option(Constants::DATABASE_OPTIONS_MERCHANT_PRIVATE_KEY);
            if(empty($privateKey)){
                return;
            }

            $currencyIsoCode = get_option(Constants::DATABASE_OPTIONS_DEFAULT_CURRENCY_ISO_CODE);
            $defaultArticlePrice = get_option(Constants::DATABASE_OPTIONS_DEFAULT_ARTICLE_PRICE);

            DatabaseUtils::createOrUpdatePaymentInformation(
                $post->ID,
                $defaultArticlePrice,
                $currencyIsoCode,
                $privateKey,
                get_the_title(),
                get_permalink($post->ID),
                Crypto\Utils::sha256($post->guid),
                );

            update_post_meta(
                $post->ID,
                Constants::ARTICLE_PAYMENT_HAS_DEFAULT_PRICE,
                "true",
            );

        } else {
            $hasDefaultPrice = get_post_meta($post->ID, Constants::ARTICLE_PAYMENT_HAS_DEFAULT_PRICE, true);

            if($hasDefaultPrice != "true") {
                return;
            }

            $paymentInfoAsArray = json_decode($paymentInfo, true);
            $defaultArticlePrice = get_option(Constants::DATABASE_OPTIONS_DEFAULT_ARTICLE_PRICE);
            $defaultArticlePriceAsInt = (int)($defaultArticlePrice * Constants::USD_CURRENCY_MULTIPLIER);


            if( $defaultArticlePriceAsInt != $paymentInfoAsArray['amount']){

                $currencyIsoCode = get_option(Constants::DATABASE_OPTIONS_DEFAULT_CURRENCY_ISO_CODE);
                $privateKey = get_option(Constants::DATABASE_OPTIONS_MERCHANT_PRIVATE_KEY);

                DatabaseUtils::createOrUpdatePaymentInformation(
                    $post->ID,
                    $defaultArticlePrice,
                    $currencyIsoCode,
                    $privateKey,
                    get_the_title(),
                    get_permalink($post->ID),
                    Crypto\Utils::sha256($post->guid),
                );
            }
        }
    }
}
add_action('wp', array('Blink\PaymentInfoGenerator', 'generatePaymentInfo'));
