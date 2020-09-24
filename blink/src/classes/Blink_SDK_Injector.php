<?php


namespace Blink;


class SDK_Injector
{
    /**
     * Add the blinkSDK javascript file to the page.
     */
    static function add_sdk()
    {
        $merchantAlias = get_option(Constants::DATABASE_OPTIONS_MERCHANT_ALIAS);
        if (empty($merchantAlias)) {
            return;
        }
        $baseURL = Constants::get_SDK_url();
        $url = $baseURL . "?clientId=" . $merchantAlias;
        wp_enqueue_script('blink_sdk_script', $url);
    }
}

add_action('wp_enqueue_scripts', array('Blink\SDK_Injector', 'add_sdk'));
