<?php

use Blink\Commons\DatabaseUtils;
use Blink\Constants;

function addBlinkWidgetToPage()
{
    wp_enqueue_script(
        'blink_pay_wall_script',
        Constants::getPaywallUrl()
    );
}

function addBlinkErrorIframeContainer($content)
{
    ob_start();
    ?>
    <div style='display: none' class='blink-error-iframe-div'></div>
    <?php
    $errorIframeContainer = ob_get_clean();
    return $content . $errorIframeContainer;
}


function addBlinkContainerToContent($content)
{
    ob_start();
    ?>
    <div style="display: none" id="blink-paywall-placeholder">
        <div style='margin-top: 0;align-items:center;justify-content:center;flex-direction: column' id='blink-sdk-iframe-container'></div>
    </div>
    <?php
    $blinkContainer = ob_get_clean();
    return $content . $blinkContainer;
}

function addJavascriptIntegrationToPage()
{
    global $post;

    // Add paywall javascript logic to the page
    wp_enqueue_script(Constants::JS_FILE_HANDLE, BLINK_PLUGIN_ROOT_URL . 'src/example/JsIntegration.js');

    $paymentInfo = get_post_meta($post->ID, Constants::ARTICLE_RESOURCE_PAYMENT_INFO_METADATA_KEY, true);
    if (empty($paymentInfo)) {
        $private_key = get_option(Constants::DATABASE_OPTIONS_MERCHANT_PRIVATE_KEY);
        if (empty($private_key)) {
            wp_localize_script(Constants::JS_FILE_HANDLE, 'blink_plugin',
                array(
                    'status' => 'disabled',
                )
            );
            return;
        }
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

    // in JavaScript, object properties are accessed as ajax_object.ajax_url, ajax_object.we_value
    wp_localize_script(Constants::JS_FILE_HANDLE, 'post_metadata',
        array(
            'post_id' => $post->ID,
            'paymentInfo' => json_decode($paymentInfo),
            'encodedPaymentInfo' => base64_encode($paymentInfo)
        )
    );
    $merchantId = get_option(Constants::DATABASE_OPTIONS_MERCHANT_ID);
    $data = array(
        'meta' => array(//array nested to protect boolean and integer values
            'publisher_id' => (int)$merchantId
        ),
    );
    wp_localize_script(Constants::JS_FILE_HANDLE, 'publisher', $data);

}

function initializeMerchant()
{
    ob_start();
    ?>
    <script>
        if (window.blinkSDK) {
            initializeWidget()
        } else {
            window.addEventListener('blinkPaywallLoaded', initializeMerchant, false);
        }
    </script>
    <?php
}


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
add_filter('the_content', 'addBlinkErrorIframeContainer');
