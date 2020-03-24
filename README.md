# Publisher prerequisites
After creating a merchant account, the publisher will have a merchant id and one or more public keys. They will be available in the publisher’s dashboard.

The merchant id will be required to configure the SDK (see publisherDomainId in setOptions bellow) and the keys will be used to set up payment information for all articles.

For each article, the publisher will provide the following information:
* `resourceId` (str) &rightarrow; unique identifier for an article
* `offerId` (str)  &rightarrow; unique identifier for an offer, can be the same as resourceId
* `currencyIsoCode` (str) &rightarrow; payment currency (ex: usd)
* `amount` (int) &rightarrow; article price 
    * to avoid precision errors we work with big integers, so the price will be multiplied by 10^6
* `paymentInfoSignature` (hex str) &rightarrow; proof that the publisher actually created this payment info
    * a signature over a json object with resourceId, offerId, currencyIsoCode and amount 
    * keys are ordered alphabetically
* `merchantPublicKey` (hex str) &rightarrow; the public key that will be used to verify the signature

On an article page, the publisher must add an empty `<div>` element having the id **`blink-container`**.
The Blink SDK will insert an `<iframe>` in this div, containing the paywall. 

**Note**: If the publisher installs the Blink Wordpress plugin, all these steps are done automatically.

# Blink SDK API
The Blink SDK is a javascript file that provides integration with the Blink wallet.

When loaded, it will set a “blinkSDK” property on the window object.
## SDK Methods
* [init](#initoptions)
* [isInitialized](#isinitialized)
* [requestPayment](#requestPayment)
* [isAuthenticated](#isAuthenticated)
* [onAuthenticationChange](#onAuthenticationChange)
* [isSubscribed](#isSubscribed)
* [onSubscriptionChange](#onSubscriptionChange)
* [getSubscription](#getSubscription)
* [promptSubscriptionPopup](#promptSubscriptionPopup)
* [promptDonationPopup](#promptdonationpopup)

### The SDK object has the following API:
<a name="initoptions"></a> 
### &rightarrow; init(options)
Configure the SDK, should be the first function called.

**Parameters**:
```javascript
options (Object)
└── clientId (int)
 ```
**Returns**: `void`

**Example**
```javascript
blinkSDK.init({clientId: 1});
```

<a name="isinitialized"></a>
### &rightarrow; isInitialized()
Checks if the SDK has been initialized.

**Parameters**: `none`

**Returns**: `bool`

<a name="requestPayment"></a>
### &rightarrow; requestPayment(request , callback, errorCallback)
Request payment for an article, should be called after the page loads.

**Parameters**:
```javascript
├── request (Object)
│   ├── offerId (str)
│   ├── currencyIsoCode (str)
│   ├── amount (int)
│   ├── merchantPublicKey (hex str)
│   ├── paymentInfoSignature (hex str)
│   └── comment (str, optional)
│
├── callback(response) (Function)
│   ├── Receives a response object which contains a payment object. 
│   └── The callback should be used to do the content management.
│
└── errorCallback(response) (Function | Optional)
    ├── receives a response object which contains an error object.
    └── The callback should be used to do additional error handling.
 ```
**Returns**: `void`

**Example**
```javascript
blinkSDK.requestPayment(paymentInfo, successCallback, errorCallback);
```

<a name="isAuthenticated"></a>
### &rightarrow; isAuthenticated()
Checks if the user is authenticated.

**Parameters**: `none`

**Returns**: `bool`

**Example**:
```javascript
let userStatus = blinkSDK.isAuthenticated()
```

<a name="onAuthenticationChange"></a>
### &rightarrow; onAuthenticationChange(callback: ({authenticated}) => void)
Register a callback for changes in authentication status.

**Parameters**:
```javascript
callback (Function)
└── receives an object with authenticated property set to true or false.
 ```
 
**Returns**: `void`

**Example**:
```javascript
blinkSDK.onAuthenticationChange(({authenticated}) => {
     if (!authenticated) {
       // user is not authenticated
       // logic here
     } else {
       // user is authenticated 
       // logic here
     }
})
```

<a name="isSubscribed"></a>
### &rightarrow; isSubscribed()
Checks if the user is subscribed.

**Parameters**: `none`

**Returns**: `bool`

**Example**:
```javascript
let isUserSubscribed = blinkSDK.isSubscribed()
```

<a name="onSubscriptionChange"></a>
### &rightarrow; onSubscriptionChange(callback: (subscription) => void)
Register a callback for changes in a user's valid subscription.
**Parameters**:
```javascript
callback (Function)
└── subscription (Object | null)
    ├── id                 (str)
    ├── deliveryAddressId  (str | null)
    ├── blinkSignature     (hex, str)
    ├── userId             (str)
    ├── amount             (int)
    ├── createdAt          (iso date str)
    ├── canceledAt         (iso date str | null)
    ├── nextPaymentAttempt (iso date str | null)
    ├── currencyCode       (str)
    └── offerId            (str)
 ```
**Returns**: `void`

<a name="getSubscription"></a>
### &rightarrow;  getSubscription()
Get a user's valid subscription if the user is authenticated and has a valid subscription.
**Parameters**: `none`

**Returns**: 

    subscription (Object | null)
    ├── id                 (str)
    ├── deliveryAddressId  (str | null)
    ├── blinkSignature     (hex, str)
    ├── userId             (str)
    ├── amount             (int)
    ├── createdAt          (iso date str)
    ├── canceledAt         (iso date str | null)
    ├── nextPaymentAttempt (iso date str | null)
    ├── currencyCode       (str | null)
    └── offerId            (str)

<a name="promptSubscriptionPopup"></a>
### &rightarrow; promptSubscriptionPopup()
Prompt the subscription page in the wallet iframe. 

Should be called when the user clicks on “Subscribe” on the publisher’s website.

**Parameters**: `none`

**Returns**: `void`

<a name="promptDonationPopup"></a>
### &rightarrow; promptDonationPopup()
Prompt the donation page in the wallet iframe.

Should be called when the user clicks on “Donate” on the publisher’s website.

**Parameters**: `none`

**Returns**: `void`


# URLS:
* Front: https://qa.blink.net
* API: https://api.qa.blink.net
* Demo website: https://qa.blinktimes.com/
* Second demo website: https://qa.blinkstreetjournal.com

# Mock data
Credit card: a series of 42 ( eg. `4242424242` ) until you fill the credit card info.

# [Integration Example](./blink/src/example)
