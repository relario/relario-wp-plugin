<?php
namespace Relario_PAY\Admin;

use function Relario_PAY\get_user_ip;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Settings {


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Add notice(s)
		add_action( 'admin_notices', array( $this, 'admin_notices' ) );

		// Register settings sections
		add_action( 'admin_init', array( $this, 'register_sections' ) );

		// Register settings
		add_action( 'admin_init', array( $this, 'register_settings' ) );

		// Add to menu
		add_action( 'admin_menu', array( $this, 'add_to_menu' ) );
	}


	/**
	 * Admin notices.
	 *
	 * @return void
	 */
	public function admin_notices() {

		// Only settings page
		if ( ! empty( $_GET['page'] ) && $_GET['page'] == 'relario-pay' && get_user_ip() == '127.0.0.1' ) {
			?><div class='error'>
				<p><?php echo __( 'We notice that you\'re currently on a local server. Beware that requests to Relario PAY are not allowed from localhost', 'relario-pay' ); ?></p>
			</div><?php
		}
	}

	/**
	 * Register setting sections.
	 *
	 * @since 1.0.0
	 */
	public function register_sections() {

		add_settings_section( 'relario_pay_settings_section', '', '__return_false', 'relario-pay_settings_page' );

	}


	/**
	 * Register settings.
	 *
	 * @since 1.0.0
	 */
	public function register_settings() {

		register_setting( 'relario_pay_settings_section', 'relario_api_key', array( $this, 'sanitize_value' ) );
		add_settings_field( 'relario_api_key', __( 'API key', 'relario-pay' ), array( $this, 'form_input_password' ), 'relario-pay_settings_page', 'relario_pay_settings_section', array(
			'label_for'     => 'relario_api_key',
			'id'            => 'relario_api_key',
			'description'   => sprintf(
				__( 'Enter the Relario Pay API key, found on your %s', 'relario-pay' ),
				'<a href="https://payment.relario.com/account/settings" target="_blank">' . __( 'Account page on Relario', 'relario-pay' ) . '</a>'
			),
			'default_value' => '',
			'placeholder'   => '',
			'title'         => __( 'API key', 'relario-pay' ),
		) );

		register_setting( 'relario_pay_settings_section', 'relario_debug_mode', array( $this, 'sanitize_checkbox' ) );
		add_settings_field( 'relario_debug_mode', __( 'Debug mode', 'relario-pay' ), array( $this, 'form_input_checkbox' ), 'relario-pay_settings_page', 'relario_pay_settings_section', array(
			'label_for'     => 'relario_debug_mode',
			'id'            => 'relario_debug_mode',
			'description'   => __( 'When enabled API requests and responses are logged', 'relario-pay' ),
			'default_value' => '',
			'title'         => 'Debug mode',
		) );


		register_setting( 'relario_pay_settings_section', 'relario_product_id', array( $this, 'sanitize_value' ) );
		add_settings_field( 'relario_product_id', __( 'ProductId', 'relario-pay' ), array( $this, 'form_input_text' ), 'relario-pay_settings_page', 'relario_pay_settings_section', array(
			'label_for'     => 'relario_product_id',
			'id'            => 'relario_product_id',
			'description'   => '',
			'default_value' => '',
			'placeholder'   => 'Required',
			'title'         => __( 'ProductId', 'relario-pay' ),
		) );

		register_setting( 'relario_pay_settings_section', 'relario_product_name', array( $this, 'sanitize_value' ) );
		add_settings_field( 'relario_product_name', __( 'Product name', 'relario-pay' ), array( $this, 'form_input_text' ), 'relario-pay_settings_page', 'relario_pay_settings_section', array(
			'label_for'     => 'relario_product_name',
			'id'            => 'relario_product_name',
			'description'   => '',
			'default_value' => '',
			'placeholder'   => '(optional)',
			'title'         => __( 'Product name', 'relario-pay' ),
		) );

		register_setting( 'relario_pay_settings_section', 'relario_sms_text_prefix', array( $this, 'sanitize_value' ) );
		add_settings_field( 'relario_sms_text_prefix', __( 'SMS text prefix', 'relario-pay' ), array( $this, 'form_input_text' ), 'relario-pay_settings_page', 'relario_pay_settings_section', array(
			'label_for'     => 'relario_sms_text_prefix',
			'id'            => 'relario_sms_text_prefix',
			'description'   => '',
			'default_value' => '',
			'placeholder'   => '(optional)',
			'title'         => __( 'SMS text prefix', 'relario-pay' ),
		) );

		add_settings_field( 'relario_shortcode_description', 'Shortcodes', array( $this, 'description' ), 'relario-pay_settings_page', 'relario_pay_settings_section', array(
			'label_for'     => 'relario_shortcode_description',
			'id'            => 'relario_shortcode_description',
			'description'   => __( 'You can use the <code>[relario_pay]</code> shortcode on any page on your site.', 'relario-pay' ) . '<br/><br/>' .
							   __( 'Additional attributes for the shortcodes are:', 'relario-pay' ) . '<br/>' .
							   __( '<code>text="Show support"</code> - Allow to set a custom button text', 'relario-pay' ) . '<br/>' .
							   __( '<code>sms_count="1"</code> - Allow to set a number of messages (values 1-20)', 'relario-pay' ) . '<br/>' .
							   __( '<code>min="5"</code> - Allow to set a minimum number (for dynamic button types) of messages (values 1-20)', 'relario-pay' ) . '<br/>' .
							   __( '<code>max="20"</code> - Allow to set a maximum number (for dynamic button types) of messages (values 1-20)', 'relario-pay' ) . '<br/>' .
							   __( '<code>type="dynamic"</code> - Allow the visitor to choose the number of messages', 'relario-pay' ) . '<br/>' .
							   __( '<code>product_id="1"</code>', 'relario-pay' ) . '<br/>' .
							   __( '<code>product_name="{Name}"</code>', 'relario-pay' ) . '<br/>' .
							   __( '<code>sms_text_prefix="{Prefix}"</code>', 'relario-pay' ) . '<br/><br/>' .
							   __( '<code>size="large"</code> - Choose between \'small\', \'normal\' or \'large\'', 'relario-pay' ) . '<br/><br/>' .
							   __( 'Full example: <code>[relario_pay text="Donate now!" sms_count="2" type="dynamic"]</code>', 'relario-pay' ),
			'default_value' => '',
			'title'         => __( '', 'relario-pay' ),
		) );

	}


	/**
	 * Add to admin menu.
	 *
	 * @since 1.0.0
	 */
	public function add_to_menu() {

		add_options_page( __( 'Relario PAY settings', 'relario-pay' ), __( 'Relario PAY', 'relario-pay' ), 'manage_options', 'relario-pay', array( $this, 'output' ) );

	}


	/**
	 * Output settings.
	 *
	 * The HTML that is outputted on the settings page.
	 *
	 * @since 1.0.0
	 */
	public function output() {

		?><div class="wrap">

			<h1><?php _e( 'Relario PAY', 'relario-pay' ); ?></h1><?php
			settings_errors( "relario-pay_settings_page" );

			?><form action="options.php" method="post"><?php
				settings_fields( "relario_pay_settings_section" ); // Nonce fields

				do_settings_sections( "relario-pay_settings_page" );
				submit_button();

			?></form>

		</div><?php

	}


	/**
	 * Text input field HTML.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $args
	 */
	public function form_input_text( $args = array() ) {

		$default_value = isset( $args['default_value'] ) ? $args['default_value'] : null;
		$value = get_option( $args['id'], $default_value );

		?><input type="text" class="regular-text" id="<?php echo esc_attr( $args['id'] ); ?>" name="<?php echo esc_attr( $args['id'] ); ?>" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr( $args['placeholder'] ); ?>" /><?php

		$this->description( $args );
	}


	/**
	 * Password field HTML.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $args
	 */
	public function form_input_password( $args = array() ) {

		$default_value = isset( $args['default_value'] ) ? $args['default_value'] : null;
		$value = get_option( $args['id'], $default_value );

		?><input type="password" class="regular-text" id="<?php echo esc_attr( $args['id'] ); ?>" name="<?php echo esc_attr( $args['id'] ); ?>" value="<?php echo esc_attr( $value ); ?>" placeholder="<?php echo esc_attr( $args['placeholder'] ); ?>" /><?php

		$this->description( $args );
	}


	/**
	 * Textarea field HTML.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $args
	 */
	public function form_input_textarea( $args = array() ) {

		$default_value = isset( $args['default_value'] ) ? $args['default_value'] : null;
		$value = get_option( $args['id'], $default_value );

		?><textarea class="large-text code" rows="10" cols="50" id="<?php echo esc_attr( $args['id'] ); ?>" name="<?php echo esc_attr( $args['id'] ); ?>" placeholder="<?php echo esc_attr( $args['placeholder'] ); ?>"><?php echo esc_textarea( $value ); ?></textarea><?php

		$this->description( $args );
	}


	/**
	 * Radio field HTML.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $args
	 */
	public function form_input_radio( $args = array() ) {

		$options       = isset( $args['options'] ) ? $args['options'] : null;
		$default_value = isset( $args['default_value'] ) ? $args['default_value'] : null;
		$value         = get_option( $args['id'], $default_value );
		$title         = isset( $args['title'] ) ? $args['title'] : '';

		if ( ! is_null( $options ) ) :

			?><fieldset><?php

				foreach ( $options as $k => $v ) :

					$key = sanitize_key( str_replace( ' ', '-', $v ) );
					?><label>
						<input type="radio" class="radio-input" id="<?php echo esc_attr( $args['id'] . '-' . $key ); ?>" name="<?php echo esc_attr( $args['id'] ); ?>" value="<?php echo esc_attr( $key ); ?>" <?php checked( in_array( $key, (array) $value ) ); ?> />
						<span class="radio-input-label"><?php echo wp_kses_post( $v ); ?></span>
					</label><br/><?php

				endforeach;

			?></fieldset><?php

		else :

			?><label>
				<input type="radio" class="" id="<?php echo esc_attr( $args['id'] ); ?>" name="<?php echo esc_attr( $args['id'] ); ?>" value="1" <?php checked( $value ); ?> />
				<span class="radio-input-label"><?php echo wp_kses_post( $title ); ?></span>
			</label><?php

		endif;

		$this->description( $args );
	}


	/**
	 * Checkbox field HTML.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $args
	 */
	public function form_input_checkbox( $args = array() ) {

		$options       = isset( $args['options'] ) ? $args['options'] : null;
		$default_value = isset( $args['default_value'] ) ? $args['default_value'] : null;
		$value         = get_option( $args['id'], $default_value );
		$title         = isset( $args['title'] ) ? $args['title'] : '';

		if ( ! is_null( $options ) ) :

			?><fieldset><?php

				foreach ( $options as $k => $v ) :

					$key = sanitize_key( str_replace( ' ', '-', $v ) );
					?><label>
						<input type="checkbox" class="checkbox-input" id="<?php echo esc_attr( $args['id'] . '-' . $key ); ?>" name="<?php echo esc_attr( $args['id'] ); ?>[]" value="<?php echo esc_attr( $key ); ?>" <?php checked( in_array( $key, (array) $value ) ); ?> />
						<span class="checkbox-input-label"><?php echo wp_kses_post( $v ); ?></span>
					</label><br/><?php

				endforeach;

			?></fieldset><?php

		else :

			?><label>
				<input type="checkbox" class="" id="<?php echo esc_attr( $args['id'] ); ?>" name="<?php echo esc_attr( $args['id'] ); ?>" value="1" <?php checked( 'yes', $value ); ?> />
				<span class="checkbox-input-label"><?php echo wp_kses_post( $title ); ?></span>
			</label><?php

		endif;

		$this->description( $args );
	}


	/**
	 * Select/dropdown field HTML.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $args
	 */
	public function form_input_select( $args = array() ) {

		$options = $args['options'];
		$default_value = isset( $args['default_value'] ) ? $args['default_value'] : null;
		$value = get_option( $args['id'], $default_value );

		?><select class="" id="<?php echo esc_attr( $args['id'] ); ?>" name="<?php echo esc_attr( $args['id'] ); ?>"><?php
			foreach ( $options as $k => $v ) :
				$key = sanitize_key( $v );
				?><option value="<?php echo esc_attr( $key ); ?>" <?php selected( $value, $key ); ?>><?php echo esc_html( $v ); ?></option><?php
			endforeach;
		?></select><?php

		$this->description( $args );
	}


	/**
	 * Output description.
	 *
	 * @param array $args List of setting arguments.
	 */
	public function description( $args = array() ) {
		if ( ! empty( $args['description'] ) ) {
			?><p class="description"><?php echo wp_kses_post( $args['description'] ); ?></p><?php
		}
	}


	/**
	 * Sanitize values.
	 *
	 * Sanitize setting values. If its an array it will auto-sanitize each key/value accordingly.
	 *
	 * @since 1.0.0
	 *
	 * @param  string|array $value Value being saved.
	 * @return  string|array Sanitized value
	 */
	public function sanitize_value( $value ) {

		if ( is_array( $value ) ) {
			foreach ( $value as $k => $v ) {
				$value[ sanitize_key( $k ) ] = $this->sanitize_value( $v );
			}
		} else {
			$value = sanitize_text_field( $value );
		}

		return $value;
	}

	/**
	 * Sanitize values.
	 *
	 * Sanitize setting values. If its an array it will auto-sanitize each key/value accordingly.
	 *
	 * @since 1.0.0
	 *
	 * @param  string|array $value Value being saved.
	 * @return  string|array Sanitized value
	 */
	public function sanitize_checkbox( $value ) {

		if ( is_array( $value ) ) {
			$value = $this->sanitize_value( $value );
		} else {
			$value = $value ? 'yes' : 'no';
		}

		return $value;
	}


}
