<?php


namespace Blink;
defined('ABSPATH') or die;


class Constants
{
    //--------------------------- Article MetaBox -------------------------------------

    //New Post Page
    const ARTICLE_META_BOX_ID = 'blink_pay_article_price';
    const ARTICLE_META_BOX_TITLE = 'Article Price';
    const ARTICLE_POST_NONCE = 'blink_meta_box_nonce';

    // Price
    const ARTICLE_PRICE_INPUT_NAME = 'article_price';

    // Resource ID
    const ARTICLE_RESOURCE_ID_INPUT_NAME = 'resource_id';

    // Article payment information
    const ARTICLE_RESOURCE_PAYMENT_INFO_METADATA_KEY = "__blink_article_payment_info_json";

    //--------------------------- Settings page  -------------------------------------
    // Configure merchant
    const CONFIGURE_MERCHANT_POST_NONCE = '__blink_configure_merchant_post_nonce';
    const CONFIGURE_MERCHANT_POST_HANDLER = '__blink_configure_merchant_post_handler';
    const CONFIGURE_MERCHANT_EMAIL_FIELD = "__blink_configure_merchant_email_field";
    const CONFIGURE_MERCHANT_PASSWORD_FIELD = "__blink_configure_merchant_password_field";

    //--------------------------- Database Objects --------------------------------------
    // DATABASE
    // pattern: DATABASE_{TABLE}_{OBJECT}
    const DATABASE_OPTIONS_SETTINGS_GROUP = "blink-settings-group";
    const DATABASE_OPTIONS_MERCHANT_PRIVATE_KEY = "__blink_private_key";
    const DATABASE_OPTIONS_MERCHANT_PUBLIC_KEY = "__blink_public_key";
    const DATABASE_OPTIONS_MERCHANT_ID = "__blink_merchant_id";
    const DATABASE_OPTIONS_DEFAULT_ARTICLE_PRICE = "__blink_default_article_price";
    const DATABASE_OPTIONS_DEFAULT_CURRENCY_ISO_CODE = "__blink_default_currency_iso_code";


    // --------------------------- File Handlers -----------------------------------------
    const JS_FILE_HANDLE = "blink-javascript-handle";

    //--------------------------- General Settings  --------------------------------------
    const ACCEPTED_CURRENCY_ISO_CODES = array('usd');
    const USD_CURRENCY_MULTIPLIER = 10000 * 100;
    const DEFAULT_USD_CONTENT_PRICE = 1.5 ;

    const DOMAIN = "demopaywall.com/";
    const PAYWALL_VERSION = "1.0/";
    const PAYWALL_FILE = "blink-paywall.js";

    const HTTPS = "https://";

    public static function getPaywallUrl() : string {
        return self::HTTPS . self::DOMAIN . self::PAYWALL_VERSION . self::PAYWALL_FILE;
    }

    public static function getApiUrl() : string {
        return self::HTTPS . 'api.' . self::DOMAIN;
    }
}
