<?php

//[blink-donate]
function blink_donate_shortcode( $atts, $content = null ){
    ob_start();
    ?>
    <div id='blink-donation-container'></div>
    <?php
    $blink_donation_container = ob_get_clean();
    if ($content == null) {
        return $blink_donation_container;
    }
    return do_shortcode($content) . $blink_donation_container;
}
add_shortcode( 'blink-donate', 'blink_donate_shortcode' );
