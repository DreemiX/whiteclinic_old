<?php
/**
 *  Clientica Theme Customizer
 *
 * @package Clientica
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
class Clienticabuilder_Standard_Adapter extends Clienticabuilder_Base_Adapter {

	public function print_fonts_links() {
		wp_enqueue_style( 'google-fonts-standard', Clienticabuilder_Theme_Demo::DEMO_FONT_SRC );
	}
}

