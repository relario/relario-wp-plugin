<?php
namespace Relario_PAY;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Locate template.
 *
 * Locate the called template.
 * Search Order:
 * 1. /themes/theme/relario-pay/$template_name
 * 2. /themes/theme/$template_name
 * 3. /plugins/relario-pay/templates/$template_name.
 *
 * @since 1.0.0
 *
 * @param  string $template_name Template to load.
 * @param  string $template_path Path to templates.
 * @param  string $default_path  Default path to template files.
 * @return string                Path to the template file.
 */
function locate_template( $template_name, $template_path = '', $default_path = '' ) {

	// Set variable to search in reviewer folder of theme.
	if ( ! $template_path ) :
		$template_path = 'relario-pay/';
	endif;

	// Set default plugin templates path.
	if ( ! $default_path ) :
		$default_path = plugin_dir_path( \Relario_PAY\Relario()->file ) . 'templates/'; // Path to the template folder
	endif;

	// Search template file in theme folder.
	$template = \locate_template( array(
		$template_path . $template_name,
		$template_name
	) );

	// Get plugins template file.
	if ( ! $template ) :
		$template = $default_path . $template_name;
	endif;

	return apply_filters( 'relario-pay\locate_template', $template, $template_name, $template_path, $default_path );

}


/**
 * Get template.
 *
 * Search for the template and include the file.
 *
 * @since 1.0.0
 *
 * @param string $template_name Template to load.
 * @param array  $args          Args passed for the template file.
 * @param string $template_path Path to templates.
 * @param string $default_path  Default path to template files.
 */
function get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {

	if ( is_array( $args ) && isset( $args ) ) :
		extract( $args );
	endif;

	$template_file = locate_template( $template_name, $template_path, $default_path );

	if ( ! file_exists( $template_file ) ) :
		return _doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template_file ), '1.0.0' );
	endif;

	include $template_file;

}
