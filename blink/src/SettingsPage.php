<?php

namespace Blink;
defined('ABSPATH') or die;

use Blink\Crypto\Utils;

class SettingsPage
{

    // Blink Setting page constants
    private const pageTitle = "Blink Settings";
    private const menuTitle = "Blink Settings";
    private const pagePermissions = "administrator";
    private const pageUUID = "blink_pay_settings"; // unique identifier

    /**
     * Adds Blink Settings page to the sidebar of admin dashboard.
     */
    static function addToMenu()
    {
        //create new top-level menu
        add_menu_page(
            self::pageTitle,
            self::menuTitle,
            self::pagePermissions,
            self::pageUUID,
            array(self::class, 'render'));

        // Add settings form fields in database
        add_action('admin_init', array(self::class, 'addOptionsToDatabase'));
    }

    /**
     * Adds the default article price and currency iso code
     */
    static function addOptionsToDatabase()
    {
        // add options to database
        register_setting(
            Constants::DATABASE_OPTIONS_SETTINGS_GROUP,
            Constants::DATABASE_OPTIONS_DEFAULT_ARTICLE_PRICE
        );
        register_setting(
            Constants::DATABASE_OPTIONS_SETTINGS_GROUP,
            Constants::DATABASE_OPTIONS_DEFAULT_CURRENCY_ISO_CODE
        );
    }

    /**
     * Settings page render
     */
    static function render()
    {
        ?>
        <div class="wrap">
            <h1>Blink Settings</h1>
            <?php
            $privateKey = get_option(Constants::DATABASE_OPTIONS_MERCHANT_PRIVATE_KEY);
            if (empty($privateKey)) {
                include(plugin_dir_path(__FILE__) . 'views/SecretsForm.php');
            } else {
                include(plugin_dir_path(__FILE__) . 'views/GeneralSettingsForm.php');
            }
            ?>
        </div>
        <?php
    }

    static function setupSecretsHandler()
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
        $email = sanitize_text_field($_POST[Constants::CONFIGURE_MERCHANT_EMAIL_FIELD]);
        $password = sanitize_text_field($_POST[Constants::CONFIGURE_MERCHANT_PASSWORD_FIELD]);

        if (empty($email) || empty($password)) {
            exit;
        }

        $loginToken = Api::login($email, $password);
        if ($loginToken === FALSE) {
            return;
        }

        // Generate random key pair ed25519 signing pair
        $merchant_key_pair = sodium_crypto_sign_keypair();
        $merchant_private_key = bin2hex(sodium_crypto_sign_secretkey($merchant_key_pair));
        $merchant_public_key = bin2hex(sodium_crypto_sign_publickey($merchant_key_pair));
        $merchant_id = Api::postPublicKey(
            $merchant_public_key,
            Utils::signMessage($merchant_public_key, $merchant_private_key),
            $loginToken
        );
        if ($merchant_id === FALSE) {
            //TODO(Mike) Send admin notification
            return;
        }
        update_option(Constants::DATABASE_OPTIONS_MERCHANT_PRIVATE_KEY, $merchant_private_key);
        update_option(Constants::DATABASE_OPTIONS_MERCHANT_PUBLIC_KEY, $merchant_public_key);
        update_option(Constants::DATABASE_OPTIONS_MERCHANT_ID, $merchant_id);

        $default_article_price = get_option(Constants::DATABASE_OPTIONS_DEFAULT_ARTICLE_PRICE);
        if (empty($default_article_price)) {
            update_option(Constants::DATABASE_OPTIONS_DEFAULT_ARTICLE_PRICE, Constants::DEFAULT_USD_CONTENT_PRICE);
        }
        $default_currency = get_option(Constants::DATABASE_OPTIONS_DEFAULT_CURRENCY_ISO_CODE);
        if (empty($default_currency)) {
            update_option(Constants::DATABASE_OPTIONS_DEFAULT_CURRENCY_ISO_CODE, 'usd');
        }
        // return to the caller page
        exit;
    }
}

add_action('admin_menu', array('Blink\SettingsPage', 'addToMenu'));
add_action('admin_post_login_and_setup_secrets', array('Blink\SettingsPage', 'setupSecretsHandler'));
