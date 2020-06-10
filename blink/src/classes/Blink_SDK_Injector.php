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
        $inactiveSeconds = null;
        $dbInactiveSecondsValue = get_option(Constants::DATABASE_OPTIONS_DONATE_POP_UP_INACTIVE_SECONDS);
        $dbInactiveSecondsMultiplierValue = get_option(Constants::DATABASE_OPTIONS_DONATE_POP_UP_INACTIVE_SECONDS_MULTIPLIER);
        if(!empty($dbInactiveSecondsValue) &&
           !empty($dbInactiveSecondsMultiplierValue) &&
            intval($dbInactiveSecondsValue) > 0
        ) {
            $inactiveSeconds = intval($dbInactiveSecondsValue) * Constants::get_time_seconds_multiplier($dbInactiveSecondsMultiplierValue);
        }

        $throttleSeconds = null;
        $dbThrottleSecondsValue = get_option(Constants::DATABASE_OPTIONS_DONATE_THROTTLE_SECONDS);
        $dbThrottleSecondsMultiplier = get_option(Constants::DATABASE_OPTIONS_DONATE_THROTTLE_SECONDS_MULTIPLIER);
        if(!empty($dbThrottleSecondsValue) &&
            !empty($dbThrottleSecondsMultiplier) &&
            intval($dbThrottleSecondsValue) > 0
        ){
            $throttleSeconds = intval($dbThrottleSecondsValue) * Constants::get_time_seconds_multiplier($dbThrottleSecondsMultiplier);
        }
        ?>
        <script>
            function initializeBlinkMerchant() {
                blinkSDK.init({
                    clientId: "<?php echo $merchantAlias ?>",
                    donateModal : {
                        <?php if(!empty(get_option(Constants::DATABASE_OPTIONS_DONATE_MESSAGE))) { ?>
                        message : "<?php echo sanitize_text_field(get_option(Constants::DATABASE_OPTIONS_DONATE_MESSAGE))?>",
                        <?php } ?>
                        <?php if($inactiveSeconds != null) { ?>
                        inactiveSeconds : <?php echo $inactiveSeconds?>,
                        <?php } ?>
                        <?php if($throttleSeconds != null) { ?>
                        throttleSeconds : <?php echo $throttleSeconds?>,
                        <?php } ?>
                    }
                });
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
