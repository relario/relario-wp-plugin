<?php
/**
 * Plugin Name: 	relario PAY Donation & Paywall Plugin
 * Plugin URI:
 * Description:		Accept donations, tips, and integrate a Paywall via an SMS payment gateway. 10X Conversion with no banks or cards - just a phone.
 * Version: 		1.0.3
 * Author:			relario PAY
 * Author URI:		https://relario.com
 * Text Domain: 	relario-pay
 */


/**
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


if ( ! function_exists( 'add_filter' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

/**
 * Display PHP 7.0 required notice.
 *
 * Display a notice when the required PHP version is not met.
 *
 * @since 1.0.0
 */
function relario_php_version_notices() {
	?><div class='updated'>
		<p><?php echo sprintf( __( 'Relario PAY requires PHP 7 or higher and your current PHP version is %s. Please (contact your host to) update your PHP version.', 'relario-pay' ), PHP_VERSION ); ?></p>
	</div><?php
}

if ( version_compare( PHP_VERSION, '7', 'lt' ) ) {
	add_action( 'admin_notices', 'so_php_version_notices' );
	return;
}


define( 'RELARIO PAY_FILE', __FILE__ );
require 'relario-pay.php';


register_activation_hook( __FILE__, function () {
	// Set transient for activation notice
	set_transient( 'relario_activation_notice', 1, 30 ); // 30 seconds
} );
