<?php

namespace Blink;
defined('ABSPATH') or die;


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
            array(self::class, 'render'),
            "data:image/svg+xml;base64,PHN2ZyB2aWV3Qm94PSItMTAgLTIuNSA0MCA0MCIgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIj4KICAgICAgICAgICAgPGcgaWQ9IlN5bWJvbHMiIHN0cm9rZT0ibm9uZSIgc3Ryb2tlLXdpZHRoPSIxIiBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPgogICAgICAgICAgICAgICAgPGcgaWQ9IndhbGxldCIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTI0LjAwMDAwMCwgLTExLjAwMDAwMCkiIGZpbGw9IiNGRkZGRkYiIGZpbGwtcnVsZT0ibm9uemVybyI+CiAgICAgICAgICAgICAgICAgICAgPHBvbHlnb24gaWQ9IlBhdGgiIHBvaW50cz0iMjkuNiA0NS43NDk5OTEgNDQgMjUuMzQ5OTg3IDM0LjQgMjUuMzQ5OTg3IDM4LjggMTEuNzQ5ODk5IDI0IDMxLjc0OTk5MSAzMy42IDMxLjc0OTk5MSI+PC9wb2x5Z29uPgogICAgICAgICAgICAgICAgPC9nPgogICAgICAgICAgICA8L2c+CiAgICAgICAgPC9zdmc+"
        );

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
    }

    /**
     * Settings page render
     */
    static function render()
    {
        ?>
        <div class="wrap">
            <?php
            include(plugin_dir_path(__FILE__) . 'views/BlinkLogo.php');
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
}

add_action('admin_menu', array('Blink\SettingsPage', 'addToMenu'));
