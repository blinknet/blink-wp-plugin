<?php

namespace Blink;
defined('ABSPATH') or die;


class SettingsPage
{

    // Blink Setting page constants
    const pageTitle = "Blink Settings";
    const menuTitle = "Blink Settings";
    const pagePermissions = "administrator";
    const pageUUID = "blink_pay_settings"; // unique identifier

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
     * Register the merchant alias and environment to the option db.
     */
    static function addOptionsToDatabase()
    {
        register_setting(
            Constants::DATABASE_OPTIONS_SETTINGS_GROUP,
            Constants::DATABASE_OPTIONS_MERCHANT_ALIAS
        );
        register_setting(
            Constants::DATABASE_OPTIONS_SETTINGS_GROUP,
            Constants::DATABASE_OPTIONS_RUNNING_ENVIRONMENT
        );
        register_setting(
            Constants::DATABASE_OPTIONS_SETTINGS_GROUP,
            Constants::DATABASE_OPTIONS_DONATE_AFTER_CONTENT
        );
        register_setting(
            Constants::DATABASE_OPTIONS_SETTINGS_GROUP,
            Constants::DATABASE_OPTIONS_DONATE_MESSAGE
        );

        //---- Options for donation pop up

        // enable or disable user pop-up
        register_setting(
            Constants::DATABASE_OPTIONS_SETTINGS_GROUP,
            Constants::DATABASE_OPTIONS_ENABLE_DONATE_POP_UP
        );

        // Active time pop-up
        register_setting(
            Constants::DATABASE_OPTIONS_SETTINGS_GROUP,
            Constants::DATABASE_OPTIONS_DONATE_POP_UP_AFTER_PAGE_ENTER_SECONDS
        );

        register_setting(
            Constants::DATABASE_OPTIONS_SETTINGS_GROUP,
            Constants::DATABASE_OPTIONS_DONATE_POP_UP_AFTER_PAGE_ENTER_SECONDS_MULTIPLIER
        );

        // Inactive time donation pop-up
        register_setting(
            Constants::DATABASE_OPTIONS_SETTINGS_GROUP,
            Constants::DATABASE_OPTIONS_DONATE_POP_UP_INACTIVE_SECONDS
        );
        register_setting(
            Constants::DATABASE_OPTIONS_SETTINGS_GROUP,
            Constants::DATABASE_OPTIONS_DONATE_POP_UP_INACTIVE_SECONDS_MULTIPLIER
        );

        // Throttle settings
        register_setting(
            Constants::DATABASE_OPTIONS_SETTINGS_GROUP,
            Constants::DATABASE_OPTIONS_DONATE_THROTTLE_SECONDS
        );
        register_setting(
            Constants::DATABASE_OPTIONS_SETTINGS_GROUP,
            Constants::DATABASE_OPTIONS_DONATE_THROTTLE_SECONDS_MULTIPLIER
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
            include(BLINK_PLUGIN_ROOT_DIR . 'src/assets/Blink_Logo.php');
            include(BLINK_PLUGIN_ROOT_DIR . 'src/templates/Blink_Settings_Form.php');
            ?>
        </div>
        <?php
    }
}

add_action('admin_menu', array('Blink\SettingsPage', 'addToMenu'));
