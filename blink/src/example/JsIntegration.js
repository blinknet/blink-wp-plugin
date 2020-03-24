/**
 * The following variables are required for this demo integration.
 * Required variables:
 *      -> integration.paymentInfo   | type : Object
 *      -> integration.clientId  | type : int
 *
 * The blinkSDK `requestPayment` method requires the payment information as an argument.
 * The `clientId` is needed in the `init` function of the SDK to load merchant assets. ( e.g name, logo )
 */

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
    //TODO(developer)
    // The rest of the JS content management should be placed here
}

/**
 *  Request a payment from the SDK with a success callback function.
 */
function requestPayment() {
    blinkSDK.requestPayment(integration.paymentInfo, successCallback);
}

/**
 * Initialize the Blink widget
 */
function initializeWidget() {
    blinkSDK.init({clientId: integration.clientId});

    //TODO(mike) @deprecated
    // blinkSDK.setOptions({publisherDomainId:  parseInt(integration.clientId)});

    // request a payment again if a users logs in the same tab with an another account
    blinkSDK.onAuthenticationChange(({authenticated}) => {
        if (!authenticated) {
            requestPayment();
        }
    })
}

function main() {
    /**
     * Initialize the Blink widget or add the initialize function to an event listener.
     * The event `blinkPaywallLoaded` will be dispatched when the blinkSDK file will be loaded in the browser.
     */
    if (window.blinkSDK) {
        initializeWidget()
    } else {
        window.addEventListener('blinkPaywallLoaded', initializeWidget, false);
    }

    /**
     * Request a payment if the blinkSDK exists and isInitialized.
     * If any of the previous conditions are not met the payment request will be added to an event listener.
     * The event `blinkPaywallReadyForInit` will be dispatched when widget iframe is loaded.
     */
    if (window.blinkSDK && window.blinkSDK.isReadyForInit()) {
    //TODO(mike) @deprecated
    // if (window.blinkSDK && window.blinkSDK.isInitialized()) {
        requestPayment()
    } else {
        window.addEventListener('blinkPaywallReadyForInit', requestPayment, false);
    }
}

main()