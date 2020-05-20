<?php
use Blink\Constants;

class Blink_Donation_Widget extends WP_Widget {
    public function __construct() {
        parent::__construct(
            Constants::BLINK_WIDGET_ID,
            "Blink donation widget"
        );
    }

    /**
     * Front-end display of widget.
     * @see WP_Widget::widget()
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        ob_start();
        ?>
        <div id='blink-donation-container'></div>
        <?php
        $blink_donation_container = ob_get_clean();
        echo $blink_donation_container;
        echo $args['after_widget'];
    }
}

function register_blink_donation_widget()
{
    register_widget('Blink_Donation_Widget');
}

add_action('widgets_init', 'register_blink_donation_widget');