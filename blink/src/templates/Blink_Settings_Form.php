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
        <tr valign="top">
            <th scope="row">Inactive seconds</th>
            <td>
                <input type="number" min="0" step="1"
                       style="vertical-align: bottom"
                       name="<?php echo Blink\Constants::DATABASE_OPTIONS_DONATE_POP_UP_INACTIVE_SECONDS; ?>"
                       value="<?php echo esc_attr(get_option(Blink\Constants::DATABASE_OPTIONS_DONATE_POP_UP_INACTIVE_SECONDS)); ?>"/>
                <select style="vertical-align: bottom"
                        name="<?php echo Blink\Constants::DATABASE_OPTIONS_DONATE_POP_UP_INACTIVE_SECONDS_MULTIPLIER; ?>">
                    <?php
                    foreach (array_slice(Blink\Constants::TIME_LEAPS,0,2) as $time_step) { ?>
                        <option
                                value="<?php echo $time_step ?>"
                            <?php
                            if($time_step == esc_attr(get_option(Blink\Constants::DATABASE_OPTIONS_DONATE_POP_UP_INACTIVE_SECONDS_MULTIPLIER))) {
                                ?>
                                selected
                            <?php } ?>
                        >
                            <?php echo $time_step ?>
                        </option>
                    <?php } ?>
                </select>
                <div>Show users a donation pop-up after they spend the configured amount on time inactive on the website.</div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">
                <div>Donation popup throttle</div>
            </th>
            <td>
                <input type="number" min="0" step="1"
                       style="vertical-align: bottom"
                       name="<?php echo Blink\Constants::DATABASE_OPTIONS_DONATE_THROTTLE_SECONDS; ?>"
                       value="<?php echo esc_attr(get_option(Blink\Constants::DATABASE_OPTIONS_DONATE_THROTTLE_SECONDS)); ?>"/>
                <select name="<?php echo Blink\Constants::DATABASE_OPTIONS_DONATE_THROTTLE_SECONDS_MULTIPLIER; ?>">
                    <?php
                    foreach (Blink\Constants::TIME_LEAPS as $time_step) { ?>
                        <option
                                value="<?php echo $time_step ?>"
                            <?php
                            if($time_step == esc_attr(get_option(Blink\Constants::DATABASE_OPTIONS_DONATE_THROTTLE_SECONDS_MULTIPLIER))) {
                                ?>
                                selected
                            <?php } ?>
                        >
                            <?php echo $time_step ?>
                        </option>
                    <?php } ?>
                </select>
                <div>The minimum time to wait between consecutive appearances of the donation pop-up to a user.</div>
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Custom donation message:</th>
            <td>
                <textarea
                    rows="6"
                    cols="50"
                    placeholder="<?php echo Blink\Constants::DONATIONS_CUSTOM_MESSAGE_PLACEHOLDER; ?>"
                    name="<?php echo Blink\Constants::DATABASE_OPTIONS_DONATE_MESSAGE; ?>"
                    form="blink-plugin-settings-form-id"><?php echo esc_attr(get_option(Blink\Constants::DATABASE_OPTIONS_DONATE_MESSAGE)) ?></textarea>
            </td>
        </tr>
    </table>
    <?php submit_button(); ?>
</form>
