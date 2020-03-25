<?php

use Blink\Api;
use Blink\Constants;
use Blink\Crypto\Utils;


function setupSecretsHandler()
{
    // set location header to return to previous page on submit
    header("Location: {$_SERVER['HTTP_REFERER']}");

    // check if the post request is coming from admin page
    if (!isset($_POST[Constants::CONFIGURE_MERCHANT_POST_NONCE]) ||
        !wp_verify_nonce(
            $_POST[Constants::CONFIGURE_MERCHANT_POST_NONCE],
            Constants::CONFIGURE_MERCHANT_POST_HANDLER
        )
    ) {
        return;
    }

    // check if email and password exist in the form submit request
    if (!isset($_REQUEST[Constants::CONFIGURE_MERCHANT_EMAIL_FIELD]) ||
        !isset($_REQUEST[Constants::CONFIGURE_MERCHANT_PASSWORD_FIELD])) {
        return;
    }
    $email = sanitize_email($_POST[Constants::CONFIGURE_MERCHANT_EMAIL_FIELD]);
    $password = sanitize_text_field($_POST[Constants::CONFIGURE_MERCHANT_PASSWORD_FIELD]);
    $environment = sanitize_text_field($_POST[Constants::CONFIGURE_MERCHANT_ENVIRONMENT_FIELD]);

    if (empty($email) || empty($password) || empty($environment)) {
        exit;
    }

    if (!in_array($environment, Constants::ENVIRONMENTS)){
        exit;
    }
    update_option(Constants::DATABASE_OPTIONS_RUNNING_ENVIRONMENT, strtolower($environment));

    $loginToken = Api::login($email, $password);
    if ($loginToken === FALSE) {
        return;
    }

    $merchantProfileResponse = Api::getMerchantProfile($loginToken);
    if ($merchantProfileResponse === FALSE) {
        return;
    }

    // Generate random key pair ed25519 signing pair
    $merchant_key_pair = sodium_crypto_sign_keypair();
    $merchant_private_key = bin2hex(sodium_crypto_sign_secretkey($merchant_key_pair));
    $merchant_public_key = bin2hex(sodium_crypto_sign_publickey($merchant_key_pair));
    $postPublicKeyResponse = Api::postPublicKey(
        $merchant_public_key,
        Utils::signMessage($merchant_public_key, $merchant_private_key),
        $loginToken
    );
    if ($postPublicKeyResponse === FALSE) {
        //TODO(Mike) Send admin notification
        return;
    }

    update_option(Constants::DATABASE_OPTIONS_MERCHANT_PRIVATE_KEY, $merchant_private_key);
    update_option(Constants::DATABASE_OPTIONS_MERCHANT_PUBLIC_KEY, $merchant_public_key);
    update_option(Constants::DATABASE_OPTIONS_MERCHANT_ID, $merchantProfileResponse->id);

    $default_article_price = get_option(Constants::DATABASE_OPTIONS_DEFAULT_ARTICLE_PRICE);
    if (empty($default_article_price)) {
        update_option(Constants::DATABASE_OPTIONS_DEFAULT_ARTICLE_PRICE, Constants::DEFAULT_CONTENT_PRICE);
    }
    $default_currency = get_option(Constants::DATABASE_OPTIONS_DEFAULT_CURRENCY_ISO_CODE);
    if (empty($default_currency)) {
        update_option(Constants::DATABASE_OPTIONS_DEFAULT_CURRENCY_ISO_CODE, $merchantProfileResponse->currencyIsoCode);
    }
    // return to the caller page
    exit;
}

add_action('admin_post_login_and_setup_secrets', 'setupSecretsHandler');
