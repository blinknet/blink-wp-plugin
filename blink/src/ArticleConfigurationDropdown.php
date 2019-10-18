<?php

namespace Blink;
defined('ABSPATH') or die;

use Blink\Commons\DatabaseUtils;
use Blink\Crypto\Utils;

class ArticleConfigurationDropdown
{
    public static function AddBlinkContentMetaDataBox($post_type, $post)
    {
        add_meta_box(
            Constants::ARTICLE_META_BOX_ID,
            __(Constants::ARTICLE_META_BOX_TITLE),
            array(self::class, 'RenderContentMetadataBox'),
            'post',
            'side',
            'high'
        );
    }

    // Dropdown render
    public static function RenderContentMetadataBox($post)
    {
        wp_nonce_field(basename(__FILE__), Constants::ARTICLE_POST_NONCE);


        $paymentInfo = json_decode(get_post_meta(
            $post->ID,
            Constants::ARTICLE_RESOURCE_PAYMENT_INFO_METADATA_KEY,
            true
        ), true);

        if (empty($paymentInfo)) {
            $private_key = get_option(Constants::DATABASE_OPTIONS_MERCHANT_PRIVATE_KEY);
            if (empty($private_key)) {
                ?>
                <div class='inside'>
                    <h3>Login with the merchant account in the settings page.</h3>
                </div>
                <?php
                return;
            }
            $current_price = get_option(Constants::DATABASE_OPTIONS_DEFAULT_ARTICLE_PRICE);

            $currency_iso_code = get_option(Constants::DATABASE_OPTIONS_DEFAULT_CURRENCY_ISO_CODE);
            $paymentInfo = DatabaseUtils::createOrUpdatePaymentInformation(
                $post->ID,
                $current_price,
                $currency_iso_code,
                $private_key,
                get_the_title(),
                get_permalink($post->ID),
                Crypto\Utils::sha256($post->guid),
            );
            // used in the page render
            $current_resource_id = json_decode($paymentInfo)->resourceId;
            $defaultArticlePrice = $current_price;

            $hasDefaultPrice = "true";
            update_post_meta(
                $post->ID,
                Constants::ARTICLE_PAYMENT_HAS_DEFAULT_PRICE,
                $hasDefaultPrice,
            );

        } else {
            $need_update = FALSE;
            $hasDefaultPrice = get_post_meta($post->ID, Constants::ARTICLE_PAYMENT_HAS_DEFAULT_PRICE, true);
            $defaultArticlePrice = get_option(Constants::DATABASE_OPTIONS_DEFAULT_ARTICLE_PRICE);
            if($hasDefaultPrice == "true") {
                $defaultArticlePriceAsInt = (int)($defaultArticlePrice * Constants::USD_CURRENCY_MULTIPLIER);
                if ($defaultArticlePriceAsInt != $paymentInfo['amount']) {
                    $currencyIsoCode = get_option(Constants::DATABASE_OPTIONS_DEFAULT_CURRENCY_ISO_CODE);
                    $privateKey = get_option(Constants::DATABASE_OPTIONS_MERCHANT_PRIVATE_KEY);
                    $paymentInfo = DatabaseUtils::createOrUpdatePaymentInformation(
                        $post->ID,
                        $defaultArticlePrice,
                        $currencyIsoCode,
                        $privateKey,
                        get_the_title(),
                        get_permalink($post->ID),
                        Crypto\Utils::sha256($post->guid),
                        );
                    $need_update = TRUE;
                }
            }

            if($need_update === FALSE ){
                $current_resource_id = $paymentInfo['resourceId'];
                $current_price = $paymentInfo['amount'] / (Constants::USD_CURRENCY_MULTIPLIER);
            } else {
                $current_resource_id = json_decode($paymentInfo)->resourceId;
                $current_price = json_decode($paymentInfo)->amount / (Constants::USD_CURRENCY_MULTIPLIER);;
            }
        }
        // Render
        include(plugin_dir_path(__FILE__) . 'views/ArticleConfigurationDropdownUI.php');
    }

    public static function ProcessContentMetadataHandler($post_id)
    {

        // check nonce || https://wordpress.org/support/article/glossary/#nonce
        if (!isset($_POST[Constants::ARTICLE_POST_NONCE]) || !wp_verify_nonce($_POST[Constants::ARTICLE_POST_NONCE], basename(__FILE__))) {
            return;
        }

        // return if auto save
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // return if current user does not have permissions.
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }

        $private_key = get_option(Constants::DATABASE_OPTIONS_MERCHANT_PRIVATE_KEY);
        if (empty($private_key)) {
            return;
        }
        $currency_iso_code = get_option(Constants::DATABASE_OPTIONS_DEFAULT_CURRENCY_ISO_CODE);

        $paymentInfo = json_decode(get_post_meta(
            $post_id,
            Constants::ARTICLE_RESOURCE_PAYMENT_INFO_METADATA_KEY,
            true
        ), true);

        $articleShouldUseDefaultPrice = sanitize_text_field($_POST[Constants::ARTICLE_USE_DEFAULT_PRICE]);
        $articlePrice = get_option(Constants::DATABASE_OPTIONS_DEFAULT_ARTICLE_PRICE);

        if(empty($articleShouldUseDefaultPrice)) {
            $articlePrice = sanitize_text_field($_POST[Constants::ARTICLE_PRICE_INPUT_NAME]);
            update_post_meta(
                $post_id,
                Constants::ARTICLE_PAYMENT_HAS_DEFAULT_PRICE,
                "false",
            );
        } else {
            update_post_meta(
                $post_id,
                Constants::ARTICLE_PAYMENT_HAS_DEFAULT_PRICE,
                "true",
            );
        }

        DatabaseUtils::createOrUpdatePaymentInformation(
            $post_id,
            $articlePrice,
            $currency_iso_code,
            $private_key,
            get_the_title(),
            get_permalink($post_id),
            $paymentInfo['resourceId'],
            );

    }
}

add_action('save_post', array('Blink\ArticleConfigurationDropdown', 'ProcessContentMetadataHandler'), 10, 2);
add_action('add_meta_boxes', array('Blink\ArticleConfigurationDropdown', 'AddBlinkContentMetaDataBox'), 10, 2);
