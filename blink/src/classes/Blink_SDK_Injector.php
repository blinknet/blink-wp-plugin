<?php


namespace Blink;


class SDK_Injector
{
    /**
     * Add the blinkSDK javascript file to the page.
     */
    static function add_SDK()
    {
        $merchantAlias = get_option(Constants::DATABASE_OPTIONS_MERCHANT_ALIAS);
        if(empty($merchantAlias)){
            return;
        }
        wp_enqueue_script('blink_sdk_script', Constants::get_SDK_url());
    }


    static function init_merchant()
    {
        global $post;

        if (is_admin() || empty($post)) {
            return;
        }
        $merchantAlias = get_option(Constants::DATABASE_OPTIONS_MERCHANT_ALIAS);
        if(empty($merchantAlias)){
            return;
        }

        // Add the js needed to init blink sdk
        wp_enqueue_script(Constants::JS_FILE_HANDLE, plugins_url('js/blink-plugin.js', dirname(__FILE__)));

        // Add the merchant alias inside the javascript file
        wp_localize_script(Constants::JS_FILE_HANDLE, 'blinkIntegration',
            array(
                'clientId' => $merchantAlias,
            )
        );
    }

}

add_action('wp_enqueue_scripts', array('Blink\SDK_Injector', 'add_sdk'));
add_action('wp_enqueue_scripts', array('Blink\SDK_Injector', 'init_merchant'));