<h4>General Settings</h4>
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
        <tr valign="top">
            <th scope="row">Currency</th>
            <td>
                <select name="<?php echo Blink\Constants::DATABASE_OPTIONS_DEFAULT_CURRENCY_ISO_CODE; ?>">
                    <?php
                    $selected_iso_code = get_option(Blink\Constants::DATABASE_OPTIONS_DEFAULT_CURRENCY_ISO_CODE);
                    foreach (Blink\Constants::ACCEPTED_CURRENCY_ISO_CODES as $currency_iso_code) { ?>
                        <option value="<?php echo $currency_iso_code ?>" <?php
                        if ($selected_iso_code == $currency_iso_code) {
                            echo "selected";
                        }
                        ?>><?php echo strtoupper($currency_iso_code) ?>
                        </option>
                    <?php } ?>
                </select>
            </td>
        </tr>
    </table>
    <?php submit_button(); ?>
</form>