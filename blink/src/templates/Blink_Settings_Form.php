<?php
if(!empty(esc_attr(get_option(Blink\Constants::DATABASE_OPTIONS_RUNNING_ENVIRONMENT)))){ ?>
<h3 style="margin-top: 40px;">
    Configured plugin running on the <a
            target="_blank"
            href="<?php echo Blink\Constants::get_website_url()?>"
            style=" background-color: #e2e2e2; border-radius: 5px; padding: 4px; text-align: center; color:#1B7E8C ">
        <?php echo esc_attr(get_option(Blink\Constants::DATABASE_OPTIONS_RUNNING_ENVIRONMENT))?></a>
    environment using the
    <span style=" background-color: #e2e2e2; border-radius: 5px; padding: 4px; text-align: center; color:#1B7E8C ">
        <?php echo esc_attr(get_option(Blink\Constants::DATABASE_OPTIONS_MERCHANT_ALIAS))?></span> merchant.
</h3>
<?php } ?>
<form method="post" action="options.php">
    <?php settings_fields(Blink\Constants::DATABASE_OPTIONS_SETTINGS_GROUP); ?>
    <table class="form-table">
        <tr valign="top">
            <th scope="row">Merchant Alias</th>
            <td><input
                       name="<?php echo Blink\Constants::DATABASE_OPTIONS_MERCHANT_ALIAS; ?>"
                       value="<?php echo esc_attr(get_option(Blink\Constants::DATABASE_OPTIONS_MERCHANT_ALIAS)); ?>"/>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Environment</th>
            <td>
                <select name="<?php echo Blink\Constants::DATABASE_OPTIONS_RUNNING_ENVIRONMENT; ?>">
                    <?php
                    foreach (Blink\Constants::ENVIRONMENTS as $env) { ?>
                        <option value="<?php echo $env ?>" <?php
                        ?>><?php echo $env ?>
                        </option>
                    <?php } ?>
                </select>
            </td>
        </tr>
    </table>
    <?php submit_button(); ?>
</form>
