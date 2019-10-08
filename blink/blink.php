<?php
/*
Plugin Name: Blink
Description: Integration with Blink. Adds the core content management features.
Author: Blink Ledger Systems Inc.
Author URI: https://blinkpass.com
Version: 0.1.0
*/



defined('ABSPATH') or die;

function includePluginFiles()
{

    $BLINK_PLUGIN_FOLDER_PATH = plugin_dir_path(__FILE__);
    if ( !defined( 'BLINK_PLUGIN_ROOT_URL' ) )
        define( 'BLINK_PLUGIN_ROOT_URL', plugin_dir_url(__FILE__) );
    include($BLINK_PLUGIN_FOLDER_PATH . 'src/SettingsPage.php');

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

    //TODO | Uncomment to show example integration
    //include($BLINK_PLUGIN_FOLDER_PATH . 'src/example/PhpIntegration.php');
}

add_action( 'plugins_loaded', 'includePluginFiles');
