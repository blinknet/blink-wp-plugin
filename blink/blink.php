<?php
/*
Plugin Name: Blink
Description: The future of online news. Blink provides single log-in across publications, seamless payments for articles and one-click subscriptions. Explore and support the best journalism quality journalism.
Author: Blink Ledger Systems Inc.
Requires PHP: 7.3
Author URI: https://blink.net
Version: 1.0.0
*/



defined('ABSPATH') or die;

function includePluginFiles()
{

    $BLINK_PLUGIN_FOLDER_PATH = plugin_dir_path(__FILE__);
    if ( !defined( 'BLINK_PLUGIN_ROOT_URL' ) )
        define( 'BLINK_PLUGIN_ROOT_URL', plugin_dir_url(__FILE__) );
    include($BLINK_PLUGIN_FOLDER_PATH . 'src/SettingsPage.php');
    include($BLINK_PLUGIN_FOLDER_PATH . 'src/SettingsPageSecretsHandler.php');

    // Add custom meta box on add/edit posts page
    include($BLINK_PLUGIN_FOLDER_PATH . 'src/ArticleConfigurationDropdown.php');

    // Import constants
    require_once($BLINK_PLUGIN_FOLDER_PATH . 'src/commons/Constants.php');
    require_once($BLINK_PLUGIN_FOLDER_PATH . 'src/commons/DatabaseUtils.php');

    // Import api calls
    include($BLINK_PLUGIN_FOLDER_PATH . 'src/commons/Api.php');

    // Import crypto files
    include($BLINK_PLUGIN_FOLDER_PATH . 'src/crypto/BlinkCryptoException.php');
    include($BLINK_PLUGIN_FOLDER_PATH . 'src/crypto/Payment.php');
    include($BLINK_PLUGIN_FOLDER_PATH . 'src/crypto/PaymentInfo.php');
    include($BLINK_PLUGIN_FOLDER_PATH . 'src/crypto/PostComment.php');
    include($BLINK_PLUGIN_FOLDER_PATH . 'src/crypto/Utils.php');

    // Import payment generator
    include($BLINK_PLUGIN_FOLDER_PATH . 'src/PaymentInfoGenerator.php');

    //TODO | Uncomment to show example integration
//        include($BLINK_PLUGIN_FOLDER_PATH . 'src/example/PhpIntegration.php');
}

add_action( 'plugins_loaded', 'includePluginFiles');
