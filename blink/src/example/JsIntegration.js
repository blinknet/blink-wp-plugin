var blink_plugin = { status: 'enabled' };

function showBlinkPaywall() {
    let paywall = document.getElementById('blink-paywall-placeholder');
    paywall.style.display = "flex"
}

function hideBlinkPaywall() {
    let paywall = document.getElementById('blink-paywall-placeholder');
    paywall.style.display = "none"
}

function emptyPaywallPlaceHolder() {
    let paywall = document.getElementById('blink-paywall-placeholder');
    paywall.innerHTML = "";
}

function addPaywallIframeContainer() {
    let paywall = document.getElementById('blink-paywall-placeholder');
    if(paywall.innerHTML === "") {
        let container = document.createElement('div');
        container.setAttribute('id', 'blink-paywall-iframe-container');
        container.setAttribute('style', 'margin-top: 0;align-items:center;justify-content:center;flex-direction: column')
        paywall.appendChild(container)
    }
}

function requestContentPayment(response) {
    hideBlinkPaywall();
    if (response.error) {
        console.warn("Encountered an error during the payment");
        createErrorIframe(response.error);
        //    show be show error iframe
    } else {
        console.warn("You just paid for your article");
    }
}

function payForContent() {
    if(blink_plugin.status === 'disabled') {
        return
    }
    blinkSDK.requestPayment(post_metadata.paymentInfo, requestContentPayment)
}

function initializeWidget() {
    if(blink_plugin.status === 'disabled') {
        return
    }
    blinkSDK.setOptions({enablePopup: true, publisherDomainId: publisher.meta.publisher_id});
    blinkSDK.onAuthenticationChange(({authenticated}) => {
        if (!authenticated) {
            showBlinkPaywall();
            payForContent();
            addPaywallIframeContainer();
        } else {
            emptyPaywallPlaceHolder();
        }
    })
}

function createErrorIframe(error) {
    let errorIframeDiv = document.getElementsByClassName("blink-error-iframe-div")[0];
    // clear maybe existing child nodes
    errorIframeDiv.innerHTML = '';

    let errorIframeSrc = error.link + "&paymentDetails=" + JSON.stringify(post_metadata.paymentInfo);
    let errorIframe = document.createElement('iframe');

    errorIframe.style.width = '100%';
    errorIframe.style.height = '250px';
    errorIframe.style.zIndex = '1024';
    errorIframe.style.border = '0';
    errorIframe.style.boxShadow = '0 8px 16px -8px rgba(0, 0, 0, .3), 0px 13px 27px -5px rgba(50, 50, 93, .25)';
    errorIframe.style.borderRadius = '18px';

    errorIframe.setAttribute("src", errorIframeSrc);
    errorIframe.setAttribute('class', 'error-iframe');

    errorIframeDiv.appendChild(errorIframe);
    blinkSDK.onExternalManualConfirmation(clearErrorIframe);
    errorIframeDiv.style.display = "block";

}


function clearErrorIframe() {
    let errorIframeDiv = document.getElementsByClassName("blink-error-iframe-div")[0];
    errorIframeDiv.style.display = "none";
}
