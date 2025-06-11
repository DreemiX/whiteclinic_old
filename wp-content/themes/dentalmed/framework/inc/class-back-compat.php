<?php
/**
 * Clientica back compat functionality
 *
 * Prevents Clientica from running on WordPress versions prior to 4.1,
 * since this theme is not meant to be backward compatible beyond that and
 * relies on many newer functions and markup changes introduced in 4.1.
 *
 * @package WordPress
 * @since Clientica 1.0
 */

/**
 * Prevent switching to Clientica on old versions of WordPress.
 *
 * Switches to the default theme.
 *
 * @since Clientica 1.8
 */

function switch_theme() {
		switch_theme( WP_DEFAULT_THEME, WP_DEFAULT_THEME );
		unset( $_GET['activated'] );
		add_action( 'admin_notices', array( 'Clienticabuilder_Back_Compat', 'upgrade_notice' ) );
	}

	/**
	 * Add message for unsuccessful theme switch.
	 *
	 * Prints an update nag after an unsuccessful attempt to switch to
	 * Clientica on WordPress versions prior to 4.1.
	 *
	 * @since Clientica 1.0
	 */
 function upgrade_notice() {
		$message = sprintf( esc_html__( 'The Clientica requires at least WordPress version 4.1. You are running version %s. Please upgrade and try again.', 'dentalmed' ), $GLOBALS['wp_version'] );
		printf( '<div class="error"><p>%s</p></div>', $message );
	}

	/**
	 * Prevent the Customizer from being loaded on WordPress versions prior to 4.1.
	 *
	 * @since Clientica 1.0
	 */
function customize() {
		wp_die( sprintf( esc_html__( 'The Clientica requires at least WordPress version 4.1. You are running version %s. Please upgrade and try again.', 'dentalmed' ), $GLOBALS['wp_version'] ), '', array(
			'back_link' => true,
		) );
	}

	/**
	 * Prevent the Theme Preview from being loaded on WordPress versions prior to 4.1.
	 *
	 * @since Clientica 1.0
	 */
function preview() {
		if ( isset( $_GET['preview'] ) ) {
			wp_die( sprintf( esc_html__( 'The Clientica requires at least WordPress version 4.1. You are running version %s. Please upgrade and try again.', 'dentalmed' ), $GLOBALS['wp_version'] ) );
		}
	}


if ( ! function_exists( 'wp_body_open' ) ) {
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
}
if ( ! ( function_exists( 'has_site_icon' ) && has_site_icon() ) ) {
	echo '<link rel="icon" type="image/png" href="http://example.com/myicon.png">';
}