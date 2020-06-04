<?php
use Blink\Constants;

class Blink_Donate_Button_Widget extends WP_Widget
{
    public function __construct()
    {
        parent::__construct(
            Constants::BLINK_DONATE_BUTTON_WIDGET_ID,
            "Blink donation button",
            array( 'description' => esc_html__( 'Display a Blink button for donations', 'text_domain' ), )
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        ob_start();
        ?>
        <style>
            .blink-button {
                margin: 0;
                width: 100%;
                padding: 13px;
                border-radius: 4px;
                outline: 0;
                font-size: 16px;
                font-family: Lato, 'Segoe UI', 'Lucida Sans Unicode', 'Helvetica Neue', Helvetica, Arial, sans-serif;
                border: none;
                cursor: pointer;
                transition: .3s ease;
                -webkit-user-select: none;
                user-select: none;
                display: inline-block;
                box-shadow: 0 2px 8px 0 rgba(6, 73, 83, 0.36);
                background: #1A8289;
                text-shadow: 0 1px 2px rgba(6,73,83,.12);
                color: #FFFFFF;
            }

            .blink-button:hover {
                background: #1E727E;
                box-shadow: 0 2px 10px 0 rgba(6,73,83,.48);
            }
        </style>
        <button onclick="blinkSDK.promptDonationPopup()" class="blink-button"><?php echo $instance['blink_donation_text'] ?></button>
        <?php
        $blink_donation_button = ob_get_clean();
        echo $blink_donation_button;
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $donation_text = ! empty( $instance['blink_donation_text'] ) ? $instance['blink_donation_text'] : esc_html__( 'please donate today', 'text_domain' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'blink_donation_text' ) ); ?>"><?php esc_attr_e( 'Button text:', 'text_domain' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'blink_donation_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'blink_donation_text' ) ); ?>" type="text" value="<?php echo esc_attr( $donation_text ); ?>">
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['blink_donation_text'] = ( ! empty( $new_instance['blink_donation_text'] ) ) ? sanitize_text_field( $new_instance['blink_donation_text'] ) : 'Please donate today';

        return $instance;
    }
}

function register_blink_donation_button_widget()
{
    register_widget('Blink_Donate_Button_Widget');
}

add_action('widgets_init', 'register_blink_donation_button_widget');