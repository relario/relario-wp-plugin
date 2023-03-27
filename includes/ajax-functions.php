<?php
namespace Relario_PAY;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Process Donate request.
 *
 * Process when a donate request is done. Returns the URL to redirect to.
 *
 * @since 1.0.0
 */
function donate_request() {
	if ( ! isset( $_POST['nonce'] ) ) {
		die( - 1 );
	}

	check_ajax_referer( 'relario-nonce', 'nonce' );

	try {
		$response = Relario()->api->transaction( array(
			'smsCount' => absint( $_POST['smsCount'] ),
			'productId' => sanitize_text_field( $_POST['productId'] ),
			'productName' => sanitize_text_field( $_POST['productName'] ),
			'smsTextPrefix' => sanitize_text_field( $_POST['smsTextPrefix'] ),
		) );

		// Send AJAX response
		wp_send_json( array(
			'success'          => true,
			'iosUrl'           => sanitize_url( $response['iosClickToSmsUrl'] ),
			'androidUrl'       => sanitize_url( $response['androidClickToSmsUrl'] ),
			'phoneNumbersList' => array_map( 'sanitize_text_field', $response['phoneNumbersList'] ),
			'smsBody'		   => $response['smsBody'],
			'smsCount'         => $response['smsCount'],
		) );
	} catch ( \Exception $e ) {
		wp_send_json( array(
			'success' => false,
			'error'   => $e->getMessage(),
		) );
	}
}
add_action( 'wp_ajax_relario_donate_request', '\Relario_PAY\donate_request' );
add_action( 'wp_ajax_nopriv_relario_donate_request', '\Relario_PAY\donate_request' );
