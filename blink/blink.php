<?php
/*
Plugin Name: Blink
Description: Blink is a set of tools built to help publishers and creators of digital content.
Author: Blink Ledger Systems Inc.
Requires PHP: 5.6
Author URI: https://blink.net
Version: 1.0.0
*/



defined('ABSPATH') or die;

function blinkIncludePluginFiles()
{

    $BLINK_PLUGIN_FOLDER_PATH = plugin_dir_path(__FILE__);
    if ( !defined( 'BLINK_PLUGIN_ROOT_DIR' ) )
        define( 'BLINK_PLUGIN_ROOT_DIR', plugin_dir_path(__FILE__) );

    // Admin UI
    include($BLINK_PLUGIN_FOLDER_PATH . 'src/admin/Blink_Settings_Page.php');

    // Import constants
    require_once($BLINK_PLUGIN_FOLDER_PATH . 'src/commons/Constants.php');

    // Classes used in the integration
    require_once($BLINK_PLUGIN_FOLDER_PATH . 'src/classes/Blink_SDK_Injector.php');

    // Shortcode
    require_once($BLINK_PLUGIN_FOLDER_PATH . 'src/shortcodes/Blink_Donate_Shortcode.php');

    // Widgets
    require_once($BLINK_PLUGIN_FOLDER_PATH . 'src/widgets/Blink_Donate_Widget.php');

}

add_action( 'plugins_loaded', 'blinkIncludePluginFiles');
