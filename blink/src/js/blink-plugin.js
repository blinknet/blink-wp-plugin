/**
 * Initialize the Blink widget
 */
function initializeBlinkMerchant() {
    blinkSDK.init({clientId:  integration.clientId});
}

function main() {
    /**
     * Initialize the Blink widget or add the initialize function to an event listener.
     * The event `blinkPaywallLoaded` will be dispatched when the blinkSDK file will be loaded in the browser.
     */
    if (window.blinkSDK) {
        initializeBlinkMerchant();
    } else {
        window.addEventListener('blinkPaywallLoaded', initializeBlinkMerchant, false);
    }
}

main();