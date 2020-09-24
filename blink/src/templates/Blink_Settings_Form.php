<form method="post" action="options.php" id="blink-plugin-settings-form-id">
    <?php settings_fields(Blink\Constants::DATABASE_OPTIONS_SETTINGS_GROUP); ?>
    <table class="form-table">
        <tr valign="top">
            <th scope="row">Blink Client ID:</th>
            <td><input
                       name="<?php echo Blink\Constants::DATABASE_OPTIONS_MERCHANT_ALIAS; ?>"
                       value="<?php echo esc_attr(get_option(Blink\Constants::DATABASE_OPTIONS_MERCHANT_ALIAS)); ?>"/>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Blink environment:</th>
            <td>
                <select name="<?php echo Blink\Constants::DATABASE_OPTIONS_RUNNING_ENVIRONMENT; ?>">
                    <?php
                    foreach (Blink\Constants::ENVIRONMENTS as $env) { ?>
                        <option
                        value="<?php echo $env ?>"
                        <?php
                        if($env == esc_attr(get_option(Blink\Constants::DATABASE_OPTIONS_RUNNING_ENVIRONMENT))) {
                        ?>
                            selected
                        <?php } ?>
                        >
                        <?php echo $env ?>
                        </option>
                    <?php } ?>
                </select>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Show donate after article:</th>
            <td><input type="checkbox"
                        name="<?php echo Blink\Constants::DATABASE_OPTIONS_DONATE_AFTER_CONTENT; ?>"
                        value="<?php echo Blink\Constants::DONATIONS_AFTER_EACH_ARTICLE; ?>"
                    <?php
                    if(esc_attr(get_option(Blink\Constants::DATABASE_OPTIONS_DONATE_AFTER_CONTENT)) == Blink\Constants::DONATIONS_AFTER_EACH_ARTICLE) {
                    ?>
                        checked
                    <?php } ?>
                />
            </td>
        </tr>
    </table>
    <h3 style="margin-top: 40px;">
        Manage revenue, content wording and pricing settings for this site in your
            <a
                target="_blank"
                href="<?php echo Blink\Constants::get_dashboard_url()?>"
                style=" background-color: #e2e2e2; border-radius: 5px; padding: 4px; text-align: center; color:#1B7E8C ">
                Blink publisher dashboard</a>.
    </h3>
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
    <?php submit_button(); ?>
</form>
