<?php
namespace Relario_PAY;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Enqueue scripts.
 *
 * Enqueue script as javascript and style sheets.
 *
 * @since 1.0.0
 */
function enqueue_scripts() {
	$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

	wp_register_style( 'relario-pay', plugins_url( 'assets/front-end/css/relario' . $suffix . '.css', Relario()->file ), array(), Relario()->version );
	wp_register_script( 'relario-pay', plugins_url( 'assets/front-end/js/relario' . $suffix . '.js', Relario()->file ), array( 'jquery' ), Relario()->version, true );

	wp_localize_script( 'relario-pay', 'relario', array(
		'ajaxurl' => admin_url( 'admin-ajax.php' ),
		'nonce'   => wp_create_nonce( 'relario-nonce' ),
	) );

	wp_enqueue_style( 'relario-pay' ); // Currently not used - inline styles instead
	wp_enqueue_script( 'relario-pay' );

}
add_action( 'wp_enqueue_scripts', 'Relario_PAY\enqueue_scripts' );

/**
 * Logger.
 *
 * Log messages to the WooCommerce logger.
 *
 * @param mixed ...$messages Messages to log
 */
function log( ...$messages ) {
	error_log( print_r( $messages, 1 ) );
}

/**
 * Logger function (debug mode only).
 *
 * A logger function that only logs when the debug mode is enabled.
 *
 * @since 1.0.0
 *
 * @param mixed ...$messages Messages to log.
 */
function debug_mode_log( ...$messages ) {
	if ( get_option( 'relario_debug_mode', 'no' ) !== 'no' ) {
		log( ...$messages );
	}
}


/**
 * Get the user IP.
 *
 * @return array|mixed|string
 */
function get_user_ip(){
    foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
        if (array_key_exists($key, $_SERVER) === true){
            foreach (explode(',', $_SERVER[$key]) as $ip){
                $ip = trim($ip); // just to be safe

                if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                    return $ip;
                }
            }
        }
    }
}