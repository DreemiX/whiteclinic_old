<?php
/**
 * Plugin Name: Clientica Integrator
 * Description: Clientica Theme Integrator
 * Version:     1.1
 * Author:      Clientica
 * Author URI:  http://clientica.pw/
 * License:     GPL3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: clientica-integrator
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

if ( ! defined( 'CBI_PATH' ) ) {
	define( 'CBI_PATH', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'CBI_MOD_PATH' ) ) {
	define( 'CBI_MOD_PATH', CBI_PATH . 'modules/' );
}

class Clientica_Integrator {

	public static $keys = array();
	public static $blog_keys = array();
	public static $shop_keys = array();

	public static $instance = null;

	private $g_fonts = array();

	private $def_mods = array();
	public $act_mods = array();

	public function __construct() {

		if ( class_exists( 'Clienticabuilder_Config' ) ) {

			$this->set_keys();

			$mods = array(
				'post-types',
				'sidebars',
				'composer-widget',
//				'lazy-load',
			);

			if ( is_admin() ) {
				array_push( $mods, 'page-option' );
				add_action( 'admin_enqueue_scripts', array( $this, 'admin_style' ) );
			}

			foreach ( $mods as $mod ) {
				require 'modules/' . $mod . '.php';
			}
			unset( $mod );
		}
		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );

		add_filter( 'style_loader_tag', array( $this, 'lazy_fonts' ), 999, 3 );

		add_action( 'wp_footer', [ $this, 'wp_footer' ], 100 );

		add_action( 'user_contactmethods', array( $this, 'author_links_fields' ), 10, 1 );

		add_filter( 'upload_mimes', function ( $mime_types ) {

			if ( current_user_can( 'administrator' ) ) {
				$new_filetypes = array();

				// allow SVG file upload
				$new_filetypes['svg'] = 'image/svg+xml';
				$new_filetypes['svgz'] = 'image/svg+xml';

				$mime_types = array_merge( $mime_types, $new_filetypes );

				return $mime_types;

			} else {

				return $mime_types;

			}

		}, 1, 1 );

	}

	private function set_keys() {

		/*
         * Keys for Page Options
         */
		$tcbh = esc_html__( 'Select Header', 'clientica-integrator' );
		$tcbf = esc_html__( 'Select Footer', 'clientica-integrator' );
//		$tsht = esc_html__( 'Select Header Type', 'clientica-integrator' );
//		$tifh = esc_html__( 'Image for Header', 'clientica-integrator' );
		$tss  = esc_html__( 'Select Slider', 'clientica-integrator' );
		$tspl = esc_html__( 'Select Page Layout', 'clientica-integrator' );
		$tcol = esc_html__( 'Color', 'clientica-integrator' );
		$timg = esc_html__( 'Image', 'clientica-integrator' );
		$trep = esc_html__( 'Repeat', 'clientica-integrator' );
		$tsiz = esc_html__( 'Size', 'clientica-integrator' );
		$tatt = esc_html__( 'Attachment', 'clientica-integrator' );
		$tpos = esc_html__( 'Position', 'clientica-integrator' );
		$tssl = esc_html__( 'Select Sidebar Layout', 'clientica-integrator' );
		$twls = esc_html__( 'Widgets for Left Sidebar', 'clientica-integrator' );
		$twrs = esc_html__( 'Widgets for Right Sidebar', 'clientica-integrator' );

		self::$keys = array(
			'header_widget' => $tcbh,
			'footer_widget' => $tcbf,
//			'header_type'        => $tsht,
//			'header_image'       => $tifh,
			'pick_slider'   => $tss,
			'layout'        => $tspl,
//			'boxed_background'   => array(
//				'background-color'      => $tcol,
//				'background-image'      => $timg,
//				'background-repeat'     => $trep,
//				'background-size'       => $tsiz,
//				'background-attachment' => $tatt,
//				'background-position'   => $tpos,
//			),
//			'content_background' => array(
//				'background-color'      => $tcol,
//				'background-image'      => $timg,
//				'background-repeat'     => $trep,
//				'background-size'       => $tsiz,
//				'background-attachment' => $tatt,
//				'background-position'   => $tpos,
//			),

			'sidebar_layout'        => $tssl,
			'left_sidebar_widgets'  => $twls,
			'right_sidebar_widgets' => $twrs,
		);

		self::$blog_keys = array(
			'blog-header_widget' => $tcbh,
			'blog-footer_widget' => $tcbf,
//			'blog-header_type'        => $tsht,
//			'blog-header_image'       => $tifh,
			'blog-pick_slider'   => $tss,
			'blog-layout'        => $tspl,
//			'blog-boxed_background'   => array(
//				'background-color'      => $tcol,
//				'background-image'      => $timg,
//				'background-repeat'     => $trep,
//				'background-size'       => $tsiz,
//				'background-attachment' => $tatt,
//				'background-position'   => $tpos,
//			),
//			'blog-content_background' => array(
//				'background-color'      => $tcol,
//				'background-image'      => $timg,
//				'background-repeat'     => $trep,
//				'background-size'       => $tsiz,
//				'background-attachment' => $tatt,
//				'background-position'   => $tpos,
//			),

			'blog-sidebar_layout'        => $tssl,
			'blog-left_sidebar_widgets'  => $twls,
			'blog-right_sidebar_widgets' => $twrs,
		);

		self::$shop_keys = array(
			'shop-header_widget'         => $tcbh,
			'shop-footer_widget'         => $tcbf,
//			'shop-header_type'           => $tsht,
//			'shop-header_image'          => $tifh,
			'shop-pick_slider'           => $tss,
			'shop-layout'                => $tspl,
//			'shop-boxed_background'      => array(
//				'background-color'      => $tcol,
//				'background-image'      => $timg,
//				'background-repeat'     => $trep,
//				'background-size'       => $tsiz,
//				'background-attachment' => $tatt,
//				'background-position'   => $tpos,
//			),
//			'shop-content_background'    => array(
//				'background-color'      => $tcol,
//				'background-image'      => $timg,
//				'background-repeat'     => $trep,
//				'background-size'       => $tsiz,
//				'background-attachment' => $tatt,
//				'background-position'   => $tpos,
//			),
			'shop-sidebar_layout'        => $tssl,
			'shop-left_sidebar_widgets'  => $twls,
			'shop-right_sidebar_widgets' => $twrs,
		);
	}

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function admin_style( $hook ) {
		global $wp_version;
		wp_enqueue_style( 'cbi_admin_style', CBI_MOD_PATH . 'css/admin_style.min.css' );
		wp_enqueue_style( 'wp-color-picker' );
		if ( version_compare( $wp_version, '5.5', '>=' ) ) {
			wp_localize_script(
				'wp-color-picker',
				'wpColorPickerL10n',
				array(
					'clear'            => esc_html__( 'Clear', 'clientica-integrator' ),
					'clearAriaLabel'   => esc_html__( 'Clear color', 'clientica-integrator' ),
					'defaultString'    => esc_html__( 'Default', 'clientica-integrator' ),
					'defaultAriaLabel' => esc_html__( 'Select default color', 'clientica-integrator' ),
					'pick'             => esc_html__( 'Select Color', 'clientica-integrator' ),
					'defaultLabel'     => esc_html__( 'Color value', 'clientica-integrator' ),
				)
			);
		}
		wp_enqueue_script( 'wp-color-picker-alpha',
			CBI_MOD_PATH . 'js/wp-color-picker-alpha.js',
			array( 'wp-color-picker' ),
			false,
			true );

		if ( in_array( $hook,
			array(
				'post.php',
				'page.php',
				'post-new.php',
				'widgets.php',
			) ) ) {
			wp_enqueue_script( 'cbi_admin_js', CBI_MOD_PATH . 'js/admin-func.js', array( 'jquery', ), false, true );

//			wp_localize_script( 'cbi_admin_js', 'regular_keys', self::$keys );
		}
	}

	public function load_plugin_textdomain() {
		load_plugin_textdomain( 'clientica-integrator',
			false,
			dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/*
	 * Sliders list
	 */
//	public static function get_sliders_array() {
//		global $wpdb;
//		$arr = array();
//
//		if ( class_exists( 'LS_Sliders' ) ) {
//			$sliders = LS_Sliders::find();
//			foreach ( $sliders as $slider ) {
//				$arr[ 'lay_' . $slider['id'] ] = $slider['name'];
//			}
//		}
//
//		if ( class_exists( 'RevSliderSlider' ) ) {
//			$RsExists = count( $wpdb->get_results( "SELECT * FROM information_schema.tables WHERE table_schema = '" . $wpdb->dbname . "' AND table_name = '" . $wpdb->prefix . "revslider_sliders' LIMIT 1",
//				ARRAY_A ) );
//			if ( $RsExists > 0 ) {
//				$revSliders = $wpdb->get_results( "SELECT title, alias FROM " . $wpdb->prefix . "revslider_sliders WHERE ( type is NULL OR type = '' )",
//					ARRAY_A );
//				if ( count( $revSliders ) > 0 ) {
//					foreach ( $revSliders as $slider ) {
//						$arr[ 'rev_' . $slider['alias'] ] = $slider['title'];
//					}
//				}
//			}
//		}
//
//		if ( count( $arr ) == 0 ) {
//			$arr = array(
//				0 => esc_attr__( 'The Theme Support Layer Slider and Slider Revolution, but couldn\'t find it. Install one of the plug-ins to choose the slider to display in the header.',
//					'clientica-integrator' ),
//			);
//		}
//
//		asort( $arr );
//		$arr = array( -1 => 'none', ) + $arr;
//
//		return $arr;
//	}

//	public static function array_insert( $array, $var, $position ) {
//		$before = array_slice( $array, 0, $position );
//		$after  = array_slice( $array, $position );
//		$return = array_merge( $before, (array) $var );
//
//		return array_merge( $return, $after );
//	}


	public static function img_selector( $key, $imgs, $setting, $keys, $hidden, $echo = true ) {

		$out = '<div id="' . $key . '" class="custom_settings_box">';
		$out .= '<div class="ssc_label"><span>' . $keys[ $key ] . '</span></div>' .
		        '<ul class="custom settings">';
		foreach ( $imgs as $img ) {
			$out .= '<li class="custom_setting" ><img src=' . esc_attr( $img ) . '></li>';
		}
		$out .= '</ul>
	      <input id="' . $key . '_input" class="custom_setting_input hidden" type="text" name="' . esc_attr( $key . $hidden ) . '" value="' . esc_attr( $setting ) . '">
		  </div>';

		if ( $echo ) {
			echo $out;
		} else {
			return $out;
		}
	}

	public static function get_sidebars() {
		$sidebars = array();
		foreach ( $GLOBALS['wp_registered_sidebars'] as $sb ) {
			$sidebars[ $sb['id'] ] = $sb['name'];
		}

		return $sidebars;
	}

	public function lazy_fonts( $tag, $hand, $href ) {
		//  && strpos ( $hand , 'elementor-icons-' ) === false
//		wp-content/plugins/elementor/assets/lib/font-awesome/css/brands.min.css
//      wp-content/plugins/elementor/assets/lib/font-awesome/css/solid.min.css
//      wp-content/plugins/elementor/assets/lib/font-awesome/css/fontawesome.min.css

//		wp-includes/js/dist/, wp-includes/js/tinymce/, wp-includes/js/underscore.min.js, wp-includes/js/backbone.min.js, js/jquery/jquery.js, wp-content/plugins/elementor/assets/lib/backbone/backbone.marionette.min.js, wp-content/plugins/elementor/assets/lib/backbone/backbone.radio.min.js, wp-content/plugins/elementor/assets/js/common-modules.min.js, wp-content/plugins/elementor/assets/lib/dialog/dialog.min.js, wp-content/plugins/elementor/assets/js/common.min.js, wp-content/plugins/elementor/assets/js/app-loader.min.js, wp-content/plugins/elementor/assets/js/frontend-modules.min.j, wp-content/plugins/elementor/assets/lib/waypoints/waypoints.min.js, wp-content/plugins/elementor/assets/lib/swiper/swiper.min.js, wp-content/plugins/elementor/assets/lib/share-link/share-link.min.js, wp-content/plugins/elementor/assets/js/frontend.min.js, wp-content/plugins/elementor/assets/js/admin-bar.min.js
		if ( strpos( $hand, 'google-fonts-' ) === false ) {
			return $tag;
		} else {

//			$scr = 'window.onload = function() {
//		var link = document.createElement("link");
//		link.type = "text/css";
//		link.rel = "stylesheet";
//		link.href = "' . $href . '";
//		document.getElementsByTagName("head")[0].appendChild(link);
//   	};';
//			wp_add_inline_script('clientica-mainjs', $scr );

			$this->g_fonts[] = $href;
		}
	}

	public function wp_footer() {

		if ( ! empty( $this->g_fonts ) ) {
			$scr = 'window.onload = function() {
		var link = document.createElement("link"), fonts = ' . json_encode( $this->g_fonts ) . ', i=0;
		link.type = "text/css";
		link.rel = "stylesheet";
		for(i; i < fonts.length; i++){
			link.href = fonts[i];
			document.getElementsByTagName("head")[0].appendChild(link);
		}
   	};';
//			wp_add_inline_script('clientica-mainjs', $scr );

			echo '<script>' . $scr . '</script>';
		}
	}

	public function author_links_fields( $contactmethods ) {
		$contactmethods['twitter']   = esc_html__( 'Twitter Username', 'clientica-elementor' );
		$contactmethods['facebook']  = esc_html__( 'Facebook Username', 'clientica-elementor' );
		$contactmethods['tumblr']    = esc_html__( 'Tumblr Username', 'clientica-elementor' );
		$contactmethods['instagram'] = esc_html__( 'Instagram Username', 'clientica-elementor' );
		$contactmethods['pinterest'] = esc_html__( 'Pinterest Username', 'clientica-elementor' );

		return $contactmethods;
	}
}

add_action( 'after_setup_theme', array( 'Clientica_Integrator', 'get_instance' ), 1 );

register_activation_hook( __FILE__, function () {
	delete_transient( 'elementor_activation_redirect' );
	$cpt_support = get_option( 'elementor_cpt_support', array( 'page', 'post' ) );
	$cpt_support = array_unique( array_merge( $cpt_support, array( 'portfolio', 'composer_widget', ) ) );
	update_option( 'elementor_cpt_support', $cpt_support );
} );