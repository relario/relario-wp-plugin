<?php
namespace Relario_PAY\Admin;

use function Relario_PAY\Relario;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Enqueue scripts.
 *
 * Enqueue script as javascript and style sheets.
 *
 * @since 1.0.0
 */
function admin_enqueue_scripts() {

	wp_register_style( 'relario-pay', plugins_url( 'assets/admin/css/relario.css', Relario()->file ), array(), Relario()->version );
	wp_register_script( 'relario-pay', plugins_url( 'assets/admin/js/relario.js', Relario()->file ), array(), Relario()->version, true );

	// Currently not used
//	wp_enqueue_style( 'relario-pay' );
//	wp_enqueue_script( 'relario-pay' );

}
add_action( 'admin_enqueue_scripts', 'Relario_PAY\Admin\admin_enqueue_scripts' );
