/**
 * The plugin status indicates if SDK methods can be safely called and all required variables are inserted
 * using the wp_localize_script() hook.
 * Required variables:
 *      -> post_metadata.paymentInfo   | type : Object
 *      -> publisher.meta.publisher_id | type : int
 *
 * Status meaning:
 *      -> enabled  : The Blink wordpress plugin is enabled and configured correctly
 *      -> disabled : The plugin is enabled but the private key is missing from the database
 *                    ( e.g the publisher is not logged in the plugin settings page )
 */
var blink_plugin = {status: 'enabled'};

/**
 * Example implementation of a success callback.
 *
 * blinkSDK will call the success callback whenever a payment is successfully performed.
 *
 * In the case of any errors while performing the payment (e.g. "Insufficient Funds")
 * they are handled by the SDK within the error iframe.
 *
 * Additionally the blinkSDK CAN call an error callback if provided to the call of requestPayment.
 */
function successCallback(response) {
    // Find the paywall in the page
    let paywall = document.getElementById('blink-container');
    // Remove the paywall iframe or the error iframe inside the container
    paywall.innerHTML = "";
    //TODO
    // The rest of the JS content management should be placed here
}

/**
 *  Request a payment from the SDK with a success callback function.
 */
function payForContent() {
    if (blink_plugin.status === 'disabled') {
        return;
    }
    blinkSDK.requestPayment(post_metadata.paymentInfo, successCallback);
}

/**
 * Initialize the Blink widget
 */
function initializeWidget() {
    if (blink_plugin.status === 'disabled') {
        return
    }
    blinkSDK.setOptions({publisherDomainId: publisher.meta.publisher_id});
    // request a payment again if a users logs in the same tab with an another account
    blinkSDK.onAuthenticationChange(({authenticated}) => {
        if (!authenticated) {
            payForContent();
        }
    })
}