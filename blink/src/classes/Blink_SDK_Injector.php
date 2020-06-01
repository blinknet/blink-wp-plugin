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

        ?>
        <script>
            function initializeBlinkMerchant() {
                blinkSDK.init({clientId: "<?php echo $merchantAlias ?>"});
            }
            if (window.blinkSDK) {
                initializeBlinkMerchant();
            } else {
                window.addEventListener('blinkPaywallLoaded', initializeBlinkMerchant, false);
            }
        </script>
        <?php
    }

}

add_action('wp_head', array('Blink\SDK_Injector', 'init_merchant'));
add_action('wp_enqueue_scripts', array('Blink\SDK_Injector', 'add_sdk'));
