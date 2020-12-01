<?php


namespace Blink;


class Blink_Donate_Bottom_Content
{
    static function add_donation_after_content($content)
    {
        if(is_singular() && in_the_loop() && is_main_query() ){
            $show_donate_after_article = get_option(Constants::DATABASE_OPTIONS_DONATE_AFTER_CONTENT);
            if ($show_donate_after_article == Constants::DONATIONS_AFTER_EACH_ARTICLE) {
                ob_start();
                ?>
                <div id='blink-donation-container'></div>
                <?php
                $blink_donate = ob_get_clean();
                return $content . $blink_donate;
            }
        }
        return $content;
    }
}

add_filter('the_content', array('Blink\Blink_Donate_Bottom_Content', 'add_donation_after_content'));