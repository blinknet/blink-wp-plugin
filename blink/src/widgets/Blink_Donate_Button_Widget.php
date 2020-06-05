<?php
use Blink\Constants;

class Blink_Donate_Button_Widget extends WP_Widget
{
    const _ButtonTextKey = 'blink_donation_text';
    public function __construct()
    {
        parent::__construct(
            Constants::BLINK_DONATE_BUTTON_WIDGET_ID,
            "Blink donation button",
            array( 'description' => esc_html__( 'Displays a Blink button for donations.', 'text_domain' ), )
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
                font-size: 18px;
                font-weight: bold;
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
        <button
                onclick="blinkSDK.promptDonationPopup()"
                class="blink-button"
        ><?php echo $instance[Blink_Donate_Button_Widget::_ButtonTextKey] ?></button>
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
        $donation_text = ! empty( $instance[Blink_Donate_Button_Widget::_ButtonTextKey] ) ? $instance[Blink_Donate_Button_Widget::_ButtonTextKey] : esc_html__( Constants::DONATIONS_BUTTON_WIDGET_DEFAULT_TEXT, 'text_domain' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( Blink_Donate_Button_Widget::_ButtonTextKey ) ); ?>"><?php esc_attr_e( 'Button text:', 'text_domain' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( Blink_Donate_Button_Widget::_ButtonTextKey ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( Blink_Donate_Button_Widget::_ButtonTextKey ) ); ?>" type="text" value="<?php echo esc_attr( $donation_text ); ?>">
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
        $instance[Blink_Donate_Button_Widget::_ButtonTextKey] = ( ! empty( $new_instance[Blink_Donate_Button_Widget::_ButtonTextKey] ) ) ? sanitize_text_field( $new_instance[Blink_Donate_Button_Widget::_ButtonTextKey] ) : Constants::DONATIONS_BUTTON_WIDGET_DEFAULT_TEXT;

        return $instance;
    }
}

function register_blink_donation_button_widget()
{
    register_widget('Blink_Donate_Button_Widget');
}

add_action('widgets_init', 'register_blink_donation_button_widget');

function include_button_fonts()
{
    ?>
    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@700&display=swap" rel="preload" as="style" onload="this.rel='stylesheet'">
    <?php
}
add_action('wp_head', 'include_button_fonts');
