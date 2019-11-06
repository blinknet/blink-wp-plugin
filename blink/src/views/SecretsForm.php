<form action="<?php echo esc_url(admin_url('admin-post.php')); ?>" method="post" id="nds_add_user_meta_form">
    <input type="hidden" name="action" value="login_and_setup_secrets">
    <?php wp_nonce_field(
        Blink\Constants::CONFIGURE_MERCHANT_POST_HANDLER,
        Blink\Constants::CONFIGURE_MERCHANT_POST_NONCE
    ); ?>
    <table class="form-table">
        <tr valign="top">
            <th scope="row">Email</th>
            <td><input type="email"
                       name="<?php echo Blink\Constants::CONFIGURE_MERCHANT_EMAIL_FIELD; ?>"
                       style="width: 40%;"
                />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Password</th>
            <td>
                <input type="password"
                       name="<?php echo Blink\Constants::CONFIGURE_MERCHANT_PASSWORD_FIELD; ?>"
                />
            </td>
        </tr>
        <tr valign="top">
            <th scope="row">Environment</th>
            <td>
                <select name="<?php echo Blink\Constants::CONFIGURE_MERCHANT_ENVIRONMENT_FIELD; ?>">
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
    <input type="submit" name="submit" value="submit" class="button button-primary">
</form>
