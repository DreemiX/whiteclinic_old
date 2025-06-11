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
class Clienticabuilder_Base_Adapter {

	private static $instance = null;

	protected function __construct() {
	}

	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'dentalmed' ), '1.0.0' );
	}

	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'dentalmed' ), '1.0.0' );
	}

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new static();
		}

		return self::$instance;
	}

	public function the_header() {
		Clienticabuilder_Theme_Demo::the_header();
	}

	/* Generate CSS by Template */
	protected function change_style( $opts ) {
		Clienticabuilder_Helpers::generate_styles_file( '/framework/css/theme.css', '/framework/css/src/style.css', $opts );
	}

	public function enqueue_scripts() {
		if ( empty( Clienticabuilder_Core::$page_options['demo_imported'] ) ) {
			wp_enqueue_style( 'clienticabuilder-save-fonts',
				Clienticabuilder_Theme_Demo::DEMO_FONT_SRC,
				array(),
				'1.0.0' );
			wp_enqueue_style( 'clienticabuilder-save-style',
				get_template_directory_uri() . '/css/save.css' );
		}
	}

	public function header_scripts() {
		if ( empty( Clienticabuilder_Core::$page_options['demo_imported'] ) ) {
			// Responsive menu
			$data = 'jQuery(document).ready(function(e){(e("#myTopnav>.icon").on("click",function(o){o.stopPropagation(),o.preventDefault();var n=document.getElementById("myTopnav");"topnav"===n.className?(n.className+=" responsive",jQuery(document).click(function(o){!jQuery(o.target).is("#myTopnav>.icon")||jQuery("#myTopnav").hasClass("responsive")&&e("#myTopnav").trigger("click")})):n.className="topnav"}),document.querySelectorAll(".menu-item-has-children > a").forEach(e=>{e.addEventListener("click",function(o){let n=e.parentNode.querySelector("ul");null!==n&&(o.preventDefault(),o.stopPropagation(),n.classList.contains("slmm-dropdown-show")?n.classList.remove("slmm-dropdown-show"):n.classList.add("slmm-dropdown-show"))},!1)}))});';
			wp_add_inline_script( 'clienticabuilder-mainjs', $data );
		}
	}

	public function enqueue_font( $font ) {
	}

}

