<?php


namespace Blink;
defined('ABSPATH') or die;


class Constants
{
    // --------------------------- UI Object IDs ----------------------------------------
    const BLINK_DONATE_WIDGET_ID = '__blink_ledger_systems_inc_donation_widget_id';
    const BLINK_DONATE_BUTTON_WIDGET_ID = '__blink_ledger_systems_inc_donation_button_widget_id';

    //--------------------------- Database Objects --------------------------------------
    // DATABASE
    // pattern: DATABASE_{TABLE}_{OBJECT}
    const DATABASE_OPTIONS_SETTINGS_GROUP = 'blink-settings-group';

    const DATABASE_OPTIONS_MERCHANT_ALIAS = '__blink_merchant_alias';
    const DATABASE_OPTIONS_RUNNING_ENVIRONMENT = '__blink_selected_running_environment';
    const DATABASE_OPTIONS_DONATE_AFTER_CONTENT = '__blink_enable_donate_after_content';
    const DATABASE_OPTIONS_DONATE_MESSAGE = '__blink_donation_custom_message';

    // --------------------------- Form Value Constants -------------------------------------
    const DONATIONS_AFTER_EACH_ARTICLE = '_blink_donation_after_article';

    // -------------------------- Constant values --------------------------------------
    const DONATIONS_BUTTON_WIDGET_DEFAULT_TEXT = 'Donate';
    const DONATIONS_CUSTOM_MESSAGE_PLACEHOLDER = "Publisher Name relies on support from readers like yourself. Even a small amount would help us improve our coverage.";

    //--------------------------- Environment Settings  --------------------------------------
    const ENVIRONMENTS = array('Live','Test');
    const TESTING_DOMAIN = 'demo.blink.net/';
    const PRODUCTION_DOMAIN = 'blink.net/';
    const PAYWALL_VERSION = '1.0/';
    const PAYWALL_FILE = 'blink-sdk.js';

    const HTTPS = 'https://';

    public static function get_website_url() {
        $selected_environment = strtolower(get_option(Constants::DATABASE_OPTIONS_RUNNING_ENVIRONMENT));
        if(!empty($selected_environment) && $selected_environment == 'live') {
            return self::HTTPS . self::PRODUCTION_DOMAIN;
        }
        return self::HTTPS . self::TESTING_DOMAIN;
    }

    /**
     * Returns the BlinkSDK javascript file url.
     * @api
     * @return string
     */
    public static function get_SDK_url() {
        $selected_environment = strtolower(get_option(Constants::DATABASE_OPTIONS_RUNNING_ENVIRONMENT));
        if(!empty($selected_environment) && $selected_environment == 'live') {
            return self::HTTPS . self::PRODUCTION_DOMAIN . self::PAYWALL_VERSION . self::PAYWALL_FILE;
        }
        return self::HTTPS . self::TESTING_DOMAIN . self::PAYWALL_VERSION . self::PAYWALL_FILE;

    }
}
