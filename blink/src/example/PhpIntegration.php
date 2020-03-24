<?php

use Blink\Constants;

/**
 * Add the blinkSDK javascript file to the page.
 */
function addBlinkWidgetToPage()
{
    wp_enqueue_script(
        'blink_pay_wall_script',
        Constants::getPaywallUrl()
    );
}

/**
 * Append somewhere in the content a div where with the id `blink-container`
 * where the paywall will be inserted by the blinkSDK.
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

/**
 * Fetch the clientId and the current payment information for the post from the database.
 * Then add them to the integration javascript file.
 * In this examples the variables are nested in the `integration` object.
 */
function addJavascriptIntegrationToPage()
{
    global $post;

    if(is_admin()|| empty($post)){
        return;
    }
    // Add the CUSTOM content management functions
    wp_enqueue_script(Constants::JS_FILE_HANDLE, BLINK_PLUGIN_ROOT_URL . 'src/example/JsIntegration.js');

    // Check if a signed payment for the post being accessed exists
    $paymentInfo = get_post_meta($post->ID, Constants::ARTICLE_RESOURCE_PAYMENT_INFO_METADATA_KEY, true);
    // Add the merchant ID inside the javascript file, need to call blinkSDK.init()
    $merchantId = get_option(Constants::DATABASE_OPTIONS_MERCHANT_ID);

    // Using the just create payment information, add it to the custom content management file
    wp_localize_script(Constants::JS_FILE_HANDLE, 'integration',
        array(
            'paymentInfo' => json_decode($paymentInfo),
            'clientId' => (int)$merchantId
        )
    );
}

// Wordpress hooks
add_action('wp_enqueue_scripts', 'addBlinkWidgetToPage');
add_filter('the_content', 'addBlinkContainerToContent');
add_action('wp_enqueue_scripts', 'addJavascriptIntegrationToPage');
