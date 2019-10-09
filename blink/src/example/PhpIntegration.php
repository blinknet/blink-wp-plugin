<?php

use Blink\Commons\DatabaseUtils;
use Blink\Constants;

/**
 * Add the javascript widget file to the page.
 */
function addBlinkWidgetToPage()
{
    wp_enqueue_script(
        'blink_pay_wall_script',
        Constants::getPaywallUrl()
    );
}

/**
 * Append somewhere in the content a div where with the id `blink-container` where the paywall
 * will be inserted by the blinkSDK.
 */
function addBlinkContainerToContent($content)
{
    ob_start();
    ?>
        <div style='margin-top: 0;align-items:center;justify-content:center;flex-direction: column' id='blink-container'></div>
    <?php
    $blinkContainer = ob_get_clean();
    return $content . $blinkContainer;
}

function addJavascriptIntegrationToPage()
{
    global $post;

    // Add the CUSTOM content management functions
    wp_enqueue_script(Constants::JS_FILE_HANDLE, BLINK_PLUGIN_ROOT_URL . 'src/example/JsIntegration.js');

    // Check if a signed payment for the post being accessed exists
    $paymentInfo = get_post_meta($post->ID, Constants::ARTICLE_RESOURCE_PAYMENT_INFO_METADATA_KEY, true);
    if (empty($paymentInfo)) {
        // if no  payment information exist check if the private key is set
        $private_key = get_option(Constants::DATABASE_OPTIONS_MERCHANT_PRIVATE_KEY);
        if (empty($private_key)) {
            // if the private key does not exist yet in the database disable the custom content management functions
            wp_localize_script(Constants::JS_FILE_HANDLE, 'blink_plugin',
                array(
                    'status' => 'disabled',
                )
            );
            return;
        }
        // If no payment information exist for the post being accessed BUT the private key exist
        // create a payment information for that post using the default merchant price
        $currency_iso_code = get_option(Constants::DATABASE_OPTIONS_DEFAULT_CURRENCY_ISO_CODE);
        $default_article_price = get_option(Constants::DATABASE_OPTIONS_DEFAULT_ARTICLE_PRICE);
        $paymentInfo = DatabaseUtils::createOrUpdatePaymentInformation(
            $post->ID,
            $default_article_price,
            $currency_iso_code,
            $private_key,
            get_the_title(),
            get_permalink($post->ID)
        );
    }

    // Using the just create payment information, add it to the custom content management file
    wp_localize_script(Constants::JS_FILE_HANDLE, 'post_metadata',
        array(
            'post_id' => $post->ID,
            'paymentInfo' => json_decode($paymentInfo),
            'encodedPaymentInfo' => base64_encode($paymentInfo)
        )
    );
    // Add the merchant ID inside the javascript file, need to call blinkSDK.setOptions()
    $merchantId = get_option(Constants::DATABASE_OPTIONS_MERCHANT_ID);
    $data = array(
        'meta' => array(//array nested to protect boolean and integer values
            'publisher_id' => (int)$merchantId
        ),
    );
    wp_localize_script(Constants::JS_FILE_HANDLE, 'publisher', $data);

}

/**
 * Initialize the Blink widget or add the initialize function to an event listener.
 * The event `blinkPaywallLoaded` will be dispatched when the script added with @see addBlinkWidgetToPage
 * will be loaded in the browser.
 */
function initializeMerchant()
{
    ob_start();
    ?>
    <script>
        if (window.blinkSDK) {
            initializeWidget()
        } else {
            window.addEventListener('blinkPaywallLoaded', initializeWidget, false);
        }
    </script>
    <?php
}

/**
 * Add a script to the web page where we request a payment if the SDK exists and isInitialized.
 * If any of the previous conditions are not met the payment request will be added to an event listener.
 * The event `blinkPaywallInitialized` will be dispatched when widget iframe is loaded.
 */
function addAutoPayScripToContent($content)
{
    ob_start();
    ?>
    <script>
        if (window.blinkSDK && window.blinkSDK.isInitialized()) {
            payForContent()
        } else {
            window.addEventListener('blinkPaywallInitialized', payForContent, false);
        }
    </script>
    <?php
    $auto_pay = ob_get_clean();
    return $content . $auto_pay;
}


// Wordpress hooks
add_action('wp_enqueue_scripts', 'addBlinkWidgetToPage');
add_filter('the_content', 'addBlinkContainerToContent');
add_action('wp_enqueue_scripts', 'addJavascriptIntegrationToPage');
add_action('wp_head', 'initializeMerchant');
add_filter('the_content', 'addAutoPayScripToContent');
