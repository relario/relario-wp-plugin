<?php
namespace Relario_PAY\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

use function Relario_PAY\Relario;

class Relario_Pay {


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {}


	/**
	 * Output shortcode content.
	 *
	 * @since 1.0.0
	 *
	 * @param  array $atts
	 * @param  string $content
	 */
	public function output( $atts, $content ) {

		$atts = shortcode_atts( array(
			'text'          => __( 'Show Support', 'relario-pay' ),
			'sms_count'     => 1,
			'min'           => 1,
			'max'           => 20,
			'product_id'     => get_option( 'relario_product_id', 1 ),
			'product_name'   => get_option( 'relario_product_name', '' ),
			'sms_text_prefix' => get_option( 'relario_sms_text_prefix' ),
			'type'          => 'fixed', // 'fixed' for a fixed smsCount, 'dynamic' to allow visitor to change the amount
			'size'          => 'normal', // 'small', 'normal' (default) or 'large'
		), $atts );

		$atts['max'] = min( $atts['max'], 20 ); // Max of 20

		$to_attribute = array(
			'data-smsCount' => $atts['sms_count'],
			'data-productId' => $atts['product_id'],
			'data-productName' => $atts['product_name'],
			'data-smsTextPrefix' => $atts['sms_text_prefix'],
		);
		$attributes = array();
		foreach ( $to_attribute as $name => $value ) {
			$attributes[] = esc_attr( $name ) . '="' . esc_attr( $value ) . '"';
		}

		$atts['attributes_html'] = implode( ' ', $attributes );

		ob_start();

			if ( ! Relario()->api->get_api_key() ) {
				echo __( 'Please enter a API key to continue.', 'relario-pay' );
			} else {
				\Relario_PAY\get_template( 'buttons/donate.php', $atts );
			}
		return ob_get_clean();
	}


}
