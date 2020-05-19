<?php
/*
Plugin Name: Blink Donations
Description: Blink donation
Author: Blink Ledger Systems Inc.
Requires PHP: 7.3
Author URI: https://blink.net
Version: 1.0.0
*/



defined('ABSPATH') or die;

function blinkIncludePluginFiles()
{

    $BLINK_PLUGIN_FOLDER_PATH = plugin_dir_path(__FILE__);
    if ( !defined( 'BLINK_PLUGIN_ROOT_URL' ) )
        define( 'BLINK_PLUGIN_ROOT_URL', plugin_dir_url(__FILE__) );

    include($BLINK_PLUGIN_FOLDER_PATH . 'src/SettingsPage.php');

    // Import constants
    require_once($BLINK_PLUGIN_FOLDER_PATH . 'src/commons/Constants.php');
    require_once($BLINK_PLUGIN_FOLDER_PATH . 'src/commons/SDK_Injector.php');

    // Shortcode
    require_once($BLINK_PLUGIN_FOLDER_PATH . 'src/shortcodes/Blink_Donate_Shortcode.php');

    // Widgets
    require_once($BLINK_PLUGIN_FOLDER_PATH . 'src/widgets/Blink_Donate_Widget.php');

    /**
     * @example `src/example/PhpIntegration.php`  Demo integration in the php backend
     * @example `src/example/JsIntegration`       Demo of blinkSDK api calls and placeholder for content management
     */
//    @todo Uncomment to include example integration
//    include($BLINK_PLUGIN_FOLDER_PATH . 'src/example/PhpIntegration.php');
}

add_action( 'plugins_loaded', 'blinkIncludePluginFiles');
