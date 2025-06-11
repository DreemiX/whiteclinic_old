<?php

use Clientica_Elementor_Widgets\Widgets\Inc;

/*
Plugin Name: Clientica Elementor Widgets
Plugin URI: http://clientica.pw/clientica-widgets
Description: SecretLab Widgets For Elsmentor
Author: SecretLab
Author URI: http://clientica.pw/
Version: 1.0
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'is_plugin_active' ) ) {
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

define( 'ABEW_FILE', __FILE__ );
define( 'ABEW_PATH', dirname( ABEW_FILE ) );
define( 'ABEW_WIDGETS_PATH', ABEW_PATH . '/widgets' );

class Clientica_Elementor_Widgets {

	/**
	 * Plugin Name
	 *
	 * @since 1.0.0
	 * @var string Plugin Name
	 */
	const PLUGIN_NAME = 'Clientica Elementor Widgets';

	/**
	 * Widget Category Name
	 *
	 * @since 1.0.0
	 * @var string Widget Category Name
	 */
	const WIDGET_CATEGORY_NAME = 'clientica-elementor-widgets';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '7.0';

	private static $instance = null;

	private $error = '';

	private $widgets = array(
		Inc\Breadcrumbs::class,
		Inc\Page_Title::class,
//		'Breadcrumbs',
//		'class-page-title'
	);

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	protected function __construct() {

		try {
			if(class_exists('\WooCommerce')){
				$this->widgets[] = Inc\Cart_Icon::class;
			}

			$this->init();
		} catch ( \Exception $e ) {
			$this->error = $e->getMessage();
			add_action( 'admin_notices', array( $this, 'admin_notice' ) );
			return;
		}
		// Load the translation.
		add_action( 'init', array( $this, 'i18n' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'front_styles' ) );
		add_action( 'elementor/elements/categories_registered', array( $this, 'add_elementor_widget_categories' ), 0 );
	}

	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'cew' ), '1.0.0' );
	}

	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'cew' ), '1.0.0' );
	}

	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new static();
		}

		return self::$instance;
	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function i18n() {
		load_plugin_textdomain( 'cew', false, dirname( plugin_basename( ABEW_FILE ) ) . '/languages/' );
	}

	function front_styles() {
		wp_enqueue_style( 'abew-styles', plugin_dir_url( __FILE__ ) . 'css/style.min.css' );
	}

	function add_elementor_widget_categories( $elements_manager ) {
		$elements_manager->add_category(
			self::WIDGET_CATEGORY_NAME,
			[
				'title' => __( 'Clientica', 'cew' ),
				'icon' => 'fa fa-fire',
			]
		);
	}

	/**
	 * Initialize the plugin
	 *
	 * Validates that Elementor is already loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed include the plugin class.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function init() {

		// Check if Elementor installed and activated.
		if ( ! did_action( 'elementor/loaded' ) ) {
			throw new \Exception( sprintf(
				wp_kses(
					'<div class="error notice-error  is-dismissible"><p><strong>"%1$s"</strong> requires <strong>"%2$s"</strong> to be installed and activated.</p></div>',
					array(
						'div' => array(
							'class'  => array(),
							'p'      => array(),
							'strong' => array(),
						),
					)
				),
				self::PLUGIN_NAME,
				'Elementor'
			) );
		}

		// Check for required Elementor version.
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			throw new \Exception( sprintf(
				wp_kses(
					'<div class="error notice-error  is-dismissible"><p><strong>"%1$s"</strong> requires <strong>"%2$s"</strong> version %3$s or greater.</p></div>',
					array(
						'div' => array(
							'class'  => array(),
							'p'      => array(),
							'strong' => array(),
						),
					)
				),
				self::PLUGIN_NAME,
				'Elementor',
				self::MINIMUM_ELEMENTOR_VERSION
			) );
		}

		// Check for required PHP version.
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			throw new \Exception( sprintf(
				wp_kses(
					'<div class="error notice-error  is-dismissible"><p><strong>"%1$s"</strong> requires <strong>"%2$s"</strong> version %3$s or greater.</p></div>',
					array(
						'div' => array(
							'class'  => array(),
							'p'      => array(),
							'strong' => array(),
						),
					)
				),
				self::PLUGIN_NAME,
				'Elementor',
				self::MINIMUM_ELEMENTOR_VERSION
			) );
		}

//		$this->init_widgets();
		// Register widgets
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );
	}

//	private function init_widgets() {
//		// Widgets autoloader.
//		require ABEW_WIDGETS_PATH . '/autoloader.php';
//
//		foreach ( $this->widgets as $widget ) {
//			$f = ABEW_WIDGETS_PATH . '/' . $widget;
//			if( file_exists( $f ) ){
//				require $f;
//			}
//		}
//
//	}

	public function register_widgets() {
		// Widgets autoloader.
		require ABEW_WIDGETS_PATH . '/autoloader.php';

		foreach ( $this->widgets as $widget ) {
			\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new $widget() );
//			$f = ABEW_WIDGETS_PATH . '/' . $widget;
//			if( file_exists( $f ) ){
//				require $f;
//			}
		}

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice() {
		deactivate_plugins( plugin_basename( ABEW_FILE ) );
		echo $this->error;
	}

//	public function adapt_controls( $controls, $widget ){
//
//		if ( $widget instanceof \Elementor\Widget_Base ){
//			foreach( $controls as $sect => $cts ) {
//				if( empty( $cts['tab'] ) ){
//					$widget->start_controls_section(
//						strtolower( $sect ),
//						array(
//							'label' => $sect,
//						)
//					);
//				} else {
//					$widget->start_controls_section(
//						strtolower( $sect ),
//						array(
//							'label' => $sect,
//							'tab' => $cts['tab'],
//						)
//					);
//				}
//
//				foreach ( $cts as $id => $ct ) {
//					$widget->add_control(
//						$id,
//						$ct
//					);
//				}
//
//				$widget->end_controls_section();
//			}
//		}
//
//	}

}

/**
 * Get images sizes info
 *
 * @global $_wp_additional_image_sizes
 * @uses   get_intermediate_image_sizes()
 *
 * @param  boolean [$unset_disabled = true] Delete images with 0 height and width?
 *
 * @return array
 */
//function abew_get_image_sizes( $unset_disabled = true ) {
//	$wais = &$GLOBALS['_wp_additional_image_sizes'];
//
//	$sizes = array( 'full' => 'full', 'custom_size' => 'Custom size' );
//
//	foreach ( get_intermediate_image_sizes() as $_size ) {
//		if ( in_array( $_size, array( 'thumbnail', 'medium', 'medium_large', 'large' ) ) ) {
//			if ( $unset_disabled && 0 == get_option( "{$_size}_size_w" ) && 0 == get_option( "{$_size}_size_h" ) ) {
//				continue;
//			}
//			$sizes[ $_size ] = $_size . '(' . get_option( "{$_size}_size_w" ) . 'x' . get_option( "{$_size}_size_h" ) . ')';
//		} elseif ( isset( $wais[ $_size ] ) ) {
//			if ( $unset_disabled && 0 == $wais[ $_size ]['width'] && 0 == $wais[ $_size ]['height'] ) {
//				continue;
//			}
//			$sizes[ $_size ] = $_size . '(' . $wais[ $_size ]['width'] . 'x' . $wais[ $_size ]['height'] . ')';
//		}
//	}
//
//	return $sizes;
//}

add_action( 'plugins_loaded', array( 'Clientica_Elementor_Widgets', 'get_instance' ) );
//Clientica_Elementor_Widgets::get_instance();

//function abew_get_img_sizes_array_from_string( $c_size ) {
//	$sizes = explode( 'x', str_replace( ' ', '', $c_size ), 2 );
//	if ( count( $sizes ) > 1 && is_numeric( $sizes[0] ) && is_numeric( $sizes[1] ) ) {
//		return $sizes;
//	} else {
//		return $c_size;
//	}
//}
//
//function abew_process_svg( $svg ) {
//	$svg_url = wp_get_attachment_url( $svg );
//
//	return abew_generate_svg( $svg_url );
//}
//
//function abew_generate_svg( $svg_url ) {
//	$svg_file = wp_remote_get( esc_url_raw( $svg_url ), array('sslverify' => false) ); //  array('sslverify' => false) ???????????????
//	$svg_file = wp_remote_retrieve_body( $svg_file );
//	if ( false !== $pos = strpos( $svg_file, '<svg' ) ) {
//		return substr( preg_replace('~\sid="[^"]+"~', '', $svg_file), $pos );
//	}
//
//	return '';
//
//}
//
//function abew_process_media( $media, $size = 'full', $atts = array() ) {
//	$m_data = image_downsize( $media, $size );
//	if ( $m_url = $m_data[0] ) {
//		if ( false === strpos( $m_url, '.svg' ) ) {
//			$d_atts = array(
//				'alt' => "",
//			);
//			$atts   = wp_parse_args( $atts, $d_atts );
//			$att    = '';
//			foreach ( $atts as $n => $v ) {
//				$att .= " $n=" . '"' . $v . '"';
//			}
//
//			return '<img src="' . $m_url . '"' . $att . ' />';
//		} else {
//			$d_atts = array();
//			$atts   = wp_parse_args( $atts, $d_atts );
//			$att    = '';
//			foreach ( $atts as $n => $v ) {
//				$att .= " $n=" . '"' . $v . '"';
//			}
//
//			return str_replace( '<svg', '<svg ' . $att . ' ', abew_generate_svg( $m_url ) );
//		}
//	}
//
//	return '';
//}
//
//add_action( 'wp_ajax_abew_get_svg', 'abew_get_svg' );
//
//function abew_get_svg() {
//	$svg = '';
//	if ( ! empty( $_GET['id'] ) ) {
//		$img_s = empty( $_GET['img_s'] ) ? '' : $_GET['img_s'];
//		$anim  = null;
//		if ( ! empty( $_GET['anim'] ) ) {
//			$anim = array( 'class' => $_GET['anim'] );
//		}
//		$svg = abew_process_media( esc_attr( $_GET['id'] ), $img_s, $anim );
//	}
//
//	echo $svg;
//	wp_die();
//}