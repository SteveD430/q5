<?php
/**
 * Q5 back compat functionality
 *
 * Prevents Q5 from running on WordPress versions prior to 4.7,
 * since this theme is not meant to be backward compatible beyond that and
 * relies on many newer functions and markup changes introduced in 4.7.
 *
 * @package WordPress
 * @subpackage Q5
 * @since Q5 1.0.0
 */

/**
 * Prevent switching to Q5 on old versions of WordPress.
 *
 * Switches to the default theme.
 *
 * @since Q5 1.0.0
 */
function q5_switch_theme() {
	switch_theme( WP_DEFAULT_THEME );
	unset( $_GET['activated'] );
	add_action( 'admin_notices', 'q5_upgrade_notice' );
}
add_action( 'after_switch_theme', 'q5_switch_theme' );

/**
 * Adds a message for unsuccessful theme switch.
 *
 * Prints an update nag after an unsuccessful attempt to switch to
 * Q5 on WordPress versions prior to 4.7.
 *
 * @since Q5 1.0.0
 *
 * @global string $wp_version WordPress version.
 */
function q5_upgrade_notice() {
	$message = sprintf( __( 'Twenty Nineteen requires at least WordPress version 4.7. You are running version %s. Please upgrade and try again.', 'q5' ), $GLOBALS['wp_version'] );
	printf( '<div class="error"><p>%s</p></div>', $message );
}

/**
 * Prevents the Customizer from being loaded on WordPress versions prior to 4.7.
 *
 * @since Q5 1.0.0
 *
 * @global string $wp_version WordPress version.
 */
function q5_customize() {
	wp_die(
		sprintf(
			__( 'Q5 requires at least WordPress version 4.7. You are running version %s. Please upgrade and try again.', 'q5' ),
			$GLOBALS['wp_version']
		),
		'',
		array(
			'back_link' => true,
		)
	);
}
add_action( 'load-customize.php', 'q5_customize' );

/**
 * Prevents the Theme Preview from being loaded on WordPress versions prior to 4.7.
 *
 * @since Q5 1.0.0
 *
 * @global string $wp_version WordPress version.
 */
function q5_preview() {
	if ( isset( $_GET['preview'] ) ) {
		wp_die( sprintf( __( 'Q5 requires at least WordPress version 4.7. You are running version %s. Please upgrade and try again.', 'q5' ), $GLOBALS['wp_version'] ) );
	}
}
add_action( 'template_redirect', 'q5_preview' );
