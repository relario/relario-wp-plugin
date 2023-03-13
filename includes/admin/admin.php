<?php
namespace Relario_PAY\Admin;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Admin {

	public $settings = null;


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {}


	/**
	 * Initialize admin parts.
	 *
	 * @since 1.0.0
	 */
	public function init() {
		// Include files
		$this->includes();

		// Settings
		$this->settings = new \Relario_PAY\Admin\Settings();

		// Notices
		add_action( 'admin_notices', array( $this, 'notices' ) );

	}


	/**
	 * Include files.
	 *
	 * Include/require plugin files/classes.
	 *
	 * @since 1.0.0
	 */
	public function includes() {

		require_once plugin_dir_path( \Relario_PAY\Relario()->file ) . 'includes/admin/admin-functions.php';
		require_once plugin_dir_path( \Relario_PAY\Relario()->file ) . 'includes/admin/settings.php';

	}


	/**
	 * Activation notice.
	 *
	 * Show a notice on the plugins page when first activating the plugin.
	 */
	function notices() {
		global $pagenow;

		if ( $pagenow == 'plugins.php' && get_transient( 'relario_activation_notice' ) ) {
			?><div class="updated notice is-dismissible">
				<p><?php
					echo sprintf( __( 'Thank you for using Relario PAY. To get started, %sgo to the settings page%s to enter your API key.', 'relario-pay' ),
						'<a href="' . esc_url( admin_url( 'options-general.php?page=relario-pay' ) ) . '">', '</a>'
					);
				?></p>
			</div><?php

			delete_transient( 'relario_activation_notice' );
		}
	}
}
