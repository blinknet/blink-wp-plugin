<h3 style="margin-top: 40px;">
    Configured plugin running on the <span
            style=" background-color: #e2e2e2; border-radius: 5px; padding: 4px; text-align: center; color:#1B7E8C ">
        <?php echo esc_attr(get_option(Blink\Constants::DATABASE_OPTIONS_RUNNING_ENVIRONMENT))?></span> environment.
</h3>
<table class="form-table">


    <tr valign="top">
        <th scope="row">Public Key</th>
        <td>
            <div style="
            display: inline-block;
            padding: 10px;
            background-color: #e2e2e2;
            border-radius: 5px;
            ">
                <?php echo esc_attr(get_option(Blink\Constants::DATABASE_OPTIONS_MERCHANT_PUBLIC_KEY)); ?>
            </div>
        </td>
    </tr>
</table>
<table class="form-table">

    <tr valign="top">
        <th scope="row">Currency</th>
        <td>
            <div style="
            display: inline-block;
            padding: 10px;
            background-color: #e2e2e2;
            border-radius: 5px;
            ">
                <?php echo strtoupper(esc_attr(get_option(Blink\Constants::DATABASE_OPTIONS_DEFAULT_CURRENCY_ISO_CODE))); ?>
            </div>
        </td>
    </tr>
</table>

<form method="post" action="options.php">
    <?php settings_fields(Blink\Constants::DATABASE_OPTIONS_SETTINGS_GROUP); ?>
    <table class="form-table">
        <tr valign="top">
            <th scope="row">Default Article Price</th>
            <td><input type="number" min="0" step="0.01"
                       name="<?php echo Blink\Constants::DATABASE_OPTIONS_DEFAULT_ARTICLE_PRICE; ?>"
                       value="<?php echo esc_attr(get_option(Blink\Constants::DATABASE_OPTIONS_DEFAULT_ARTICLE_PRICE)); ?>"/>
            </td>
        </tr>
    </table>
    <?php submit_button(); ?>
</form>
