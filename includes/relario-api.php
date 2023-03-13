<?php
namespace Relario_PAY;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Relario_Api {

	/** @var $api_url string The Relario API URL  */
	private $api_url = 'https://payment.relario.com/api/web/';

	/**
	 * Get API key.
	 *
	 * @since 1.0.0
	 *
	 * @return string API key registered on the settings page.
	 */
	public function get_api_key() {
		return get_option( 'relario_api_key', '' );
	}


	/**
	 * API request.
	 *
	 * All API requests come through this method. Prepares the connection to Relario.
	 *
	 * @throws \Exception
	 *
	 * @since 1.0.0
	 *
	 * @param  string $endpoint Relario API endpoint to call.
	 * @param  array  $args     Arguments to pass.
	 * @return array            API body response
	 */
	public function request( $endpoint, $args ) {
		$url  = $this->api_url . $endpoint;
		$args = array(
			'body' => json_encode( $args ),
		);

		debug_mode_log( '** REQUEST **', $url, $args );

		// Add authorization
		$args['headers']['Content-Type']  = 'application/json';
		$args['headers']['Authorization'] = 'Bearer ' . sanitize_key( $this->get_api_key() );

		// Make request
		$response = wp_remote_post( $url, $args );

		$code    = wp_remote_retrieve_response_code( $response );
		$message = wp_remote_retrieve_response_message( $response );
		$body    = wp_remote_retrieve_body( $response );
		$body    = (array) json_decode( $body );

		if ( $code !== 200 ) {
			throw new \Exception( sanitize_key( $body['errorCode'] ?? $code ) . ': ' . sanitize_text_field( $body['errorMessage'] ?? '' ) );
		}

		debug_mode_log( '** RESPONSE (body) ** Code: ' . $code, $body );

		return $body;
	}


	/**
	 * Transaction request (donate).
	 *
	 * Perform the transaction request.
	 *
	 * @throws \Exception When API call fails
	 *
	 * @since 1.0.0
	 *
	 * @param  array $args List of arguments to pass to the API.
	 * @return array       API response.
	 */
	public function transaction( $args = array() ) {
		$args = wp_parse_args( $args, array(
			'paymentType'       => 'sms',
			'customerId'        => md5( get_current_user_id() . get_user_ip() ),
			'productId'         => '',
			'smsCount'          => 1,
			'customerIpAddress' => get_user_ip(), // 127.0.0.1 is NOT allowed
		) );

		$response = $this->request( 'transactions', $args );

		return $response;
	}
}
