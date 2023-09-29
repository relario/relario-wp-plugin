<?php
namespace Relario_PAY;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Relario {

	public $version = '1.0.4';

	public $file = __FILE__;

	/**
	 * @var $api Relario_Api
	 */
	public $api;

	private static  $instance;


	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

	}


	/**
	 * Instance.
	 *
	 * A global instance of the class. Used to retrieve the instance
	 * to use on other files/plugins/themes.
	 *
	 * @since 1.0.0
	 * @return  object Instance of the class.
	 */
	public static  function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Initialize plugin parts.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Load textdomain
		$this->load_textdomain();

		// Include files
		$this->includes();

		$this->api = new Relario_Api();

		// Add shortcodes
		$this->add_shortcodes();

		// Admin
		if ( is_admin() ) {
			require_once plugin_dir_path( $this->file ) . 'includes/admin/admin.php';

			$this->admin = new \Relario_PAY\Admin\Admin();
			$this->admin->init();
		}
	}


	/**
	 * Textdomain.
	 *
	 * Load the textdomain based on WP language.
	 *
	 * @since 1.0.0
	 */
	public function load_textdomain() {

		$locale = apply_filters( 'plugin_locale', get_locale(), 'relario-pay' );

		// Load textdomain
		load_textdomain( 'relario-pay', WP_LANG_DIR . '/relario-pay/relario-pay-' . $locale . '.mo' );
		load_plugin_textdomain( 'relario-pay', false, basename( dirname( __FILE__ ) ) . '/languages' );

	}


	/**
	 * Include files.
	 *
	 * @since 1.0.0
	 */
	public function includes() {
		require_once plugin_dir_path( $this->file ) . 'relario-pay.php';
		require_once plugin_dir_path( $this->file ) . 'includes/relario-api.php';
		require_once plugin_dir_path( $this->file ) . 'includes/core-functions.php';
		require_once plugin_dir_path( $this->file ) . 'includes/ajax-functions.php';
		require_once plugin_dir_path( $this->file ) . 'includes/template-functions.php';
		require_once plugin_dir_path( $this->file ) . 'includes/shortcodes/relario-pay.php';
	}


	/**
	 * Add shortcodes
	 *
	 * Add the shortcodes to WordPress with their callbacks to be initialised.
	 *
	 * @since 1.0.0
	 */
	public function add_shortcodes() {
		add_shortcode( 'relario_pay', array( new \Relario_PAY\Shortcodes\Relario_Pay(), 'output' ) );
	}


}

/**
 * The main function responsible for returning the Relario object.
 *
 * Use this function like you would a global variable, except without needing to declare the global.
 *
 * Example: <?php Relario()->method_name(); ?>
 *
 * @since 1.0.0
 *
 * @return Relario Return the singleton Relario object.
 */
function Relario() {
	return Relario::instance();
}
Relario()->init();
