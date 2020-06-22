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

    const DATABASE_OPTIONS_ENABLE_DONATE_POP_UP = '__blink_donate_enable_user_pop_up';

    const DATABASE_OPTIONS_DONATE_POP_UP_INACTIVE_SECONDS = '__blink_donation_pop_up_inactive_seconds';
    const DATABASE_OPTIONS_DONATE_POP_UP_INACTIVE_SECONDS_MULTIPLIER = '__blink_donation_pop_up_inactive_seconds_multiplier';

    const DATABASE_OPTIONS_DONATE_POP_UP_AFTER_PAGE_ENTER_SECONDS = '__blink_donation_pop_up_after_page_enter_seconds';
    const DATABASE_OPTIONS_DONATE_POP_UP_AFTER_PAGE_ENTER_SECONDS_MULTIPLIER = '__blink_donation_pop_up_after_page_enter_seconds_multiplier';

    const DATABASE_OPTIONS_DONATE_THROTTLE_SECONDS = '__blink_donation_throttle_seconds';
    const DATABASE_OPTIONS_DONATE_THROTTLE_SECONDS_MULTIPLIER = '__blink_donation_throttle_seconds_multiplier';

    const DATABASE_OPTIONS_WIDGET_POSITION_OFFSET_DESKTOP = '__blink_widget_position_offset_desktop';
    const DATABASE_OPTIONS_WIDGET_POSITION_OFFSET_MOBILE = '__blink_widget_position_offset_mobile';


    // --------------------------- Form Value Constants -------------------------------------
    const DONATIONS_AFTER_EACH_ARTICLE = '_blink_donation_after_article';
    const DONATIONS_ENABLE_POP_UP = '_blink_donation_pop_up_enabled';

    // -------------------------- Constant values --------------------------------------
    const DONATIONS_BUTTON_WIDGET_DEFAULT_TEXT = 'Donate';
    const DONATIONS_CUSTOM_MESSAGE_PLACEHOLDER = "We rely on support from readers like yourself. Even a small amount would help us improve our coverage.";

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

    const TIME_LEAPS = array('second','minute','hour','day','week');

    public static function get_time_seconds_multiplier($time_string){
        if($time_string == self::TIME_LEAPS[0]) { // seconds
            return 1;
        } elseif ($time_string == self::TIME_LEAPS[1]) { // minutes
            return 60;
        } elseif ($time_string == self::TIME_LEAPS[2]) { // hour
            return 3600;
        } elseif ($time_string == self::TIME_LEAPS[3]) { // day
            return 86400;
        } elseif ($time_string == self::TIME_LEAPS[4]) { // week
            return 604800;
        }
        return 0; // error; return zero multiplier
    }
}
