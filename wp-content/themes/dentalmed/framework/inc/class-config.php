<?php

/** Theme Options Config File */

class Clienticabuilder_Config {

	private $config;
	/**
	 * @see wp-includes/class-wp-customize-manager.php
	 * @var WP_Customize_Manager
	 */
	protected $wp_customize;

	private static $composer_block_array = array();

	private static $sliders_array = null;

	private $customize_controls = array(
		'switch'              => 'Clienticabuilder_Customize_Switch_Control',
		'color-rgba'          => 'Clienticabuilder_Customize_Color_Rgba_Control',
		'image-select'        => 'Clienticabuilder_Customize_Image_Select_Control',
		'code-editor'         => 'Clienticabuilder_Customize_Code_Editor_Control',
		'info'                => 'Clienticabuilder_Customize_Info_Control',
		'advanced-background' => 'Clienticabuilder_Customize_Advanced_Background_Control',
	);

	private $customize_settings = array(
		'switch'              => 'Clienticabuilder_Customize_Switch_Setting',
		'color-rgba'          => 'Clienticabuilder_Customize_Color_Rgba_Setting',
		'image-select'        => 'Clienticabuilder_Customize_Image_Select_Setting',
		'advanced-background' => 'Clienticabuilder_Customize_Advanced_Background_Setting',
	);

	/**
	 * Clienticabuilder_Config constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', array( $this, 'customize_register' ) );
	}

	public function customize_register( $wp_customize ) {

		$this->wp_customize = $wp_customize;
		$this->config       = Clienticabuilder_Theme_Demo::get_config();
		$this->add_customize_settings();
		$this->add_customize_controls();

		$this->register();

	}

	private function add_customize_settings() {
		foreach ( $this->customize_settings as $type => $cs ) {
			$f = CLIENTICABUILDER_FRAMEWORK_PATH . '/customizer/settings/class-customize-' . $type . '-setting.php';
			if ( file_exists( $f ) ) {
				require $f;
			}
		}
	}

	private function add_customize_controls() {
		foreach ( $this->customize_controls as $type => $cc ) {
			$f = CLIENTICABUILDER_FRAMEWORK_PATH . '/customizer/controls/class-customize-' . $type . '-control.php';
			if ( file_exists( $f ) ) {
				require $f;
			}
		}
	}

	private function register() {

		foreach ( $this->config['sections'] as $section_id => $section ) {
			if ( ! $this->wp_customize->get_section( $section_id ) ) {
				$this->wp_customize->add_section( $section_id, array(
					'title'    => $section['title'],
					'priority' => empty( $section['priority'] ) ? 0 : (int) $section['priority'],
				) );
			}

			foreach ( $section['fields'] as $field_id => $field ) {
				$def = empty( $field['default'] ) ? '' : $field['default'];
				if ( key_exists( $field['type'], $this->customize_settings ) ) {
					$setting = $this->customize_settings[ $field['type'] ];
				} else {
					$setting = WP_Customize_Setting::class;
				}

				$this->wp_customize->add_setting( new $setting( $this->wp_customize, $field['id'], array(
					'type'      => 'theme_mod',
					'default'   => $def,
					'transport' => 'refresh',
				) ) );
				$field = array_merge(
					$field,
					array(
						'section'  => $section_id,
						'settings' => $field['id'],
					)
				);

				if ( key_exists( $field['type'], $this->customize_controls ) ) {
					$control = $this->customize_controls[ $field['type'] ];
				} else {
					$control = WP_Customize_Control::class;
				}
				$this->wp_customize->add_control( new $control( $this->wp_customize, $field['id'], $field ) );

			}
		}

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_assets' ) );


	}

	public static function admin_enqueue_assets( $hook ) {

		if ( 'widgets.php' === $hook ) {

			wp_register_script( 'clienticabuilder-customizer', CLIENTICABUILDER_FRAMEWORK_URL . '/js/customizer.js', array( 'jquery' ), false, true );

			wp_enqueue_script( 'clienticabuilder-customizer' );

			wp_localize_script(
				'clienticabuilder-customizer',
				'wpColorPickerL10n',
				array(
					'clear'            => esc_html__( 'Clear', 'dentalmed' ),
					'clearAriaLabel'   => esc_html__( 'Clear color', 'dentalmed' ),
					'defaultString'    => esc_html__( 'Default', 'dentalmed' ),
					'defaultAriaLabel' => esc_html__( 'Select default color', 'dentalmed' ),
					'pick'             => esc_html__( 'Select Color', 'dentalmed' ),
					'defaultLabel'     => esc_html__( 'Color value', 'dentalmed' ),
				) );

			wp_localize_script(
				'clienticabuilder-customizer',
				'clienticabuilderCustomizer',
				array(
					'fields' => array(),
				)
			);
		}

	}

	public static function get_sidebars() {
		$sidebars = array();
		foreach ( $GLOBALS['wp_registered_sidebars'] as $sb ) {
			$sidebars[ $sb['id'] ] = $sb['name'];
		}

		return $sidebars;
	}

	public static function get_sliders_array( $text = '' ) {

		if ( null !== self::$sliders_array ) {
			return self::$sliders_array;
		}

		global $wpdb;

		$arr = array( - 1 => 'none' );

		if ( class_exists( 'LS_Sliders' ) ) {
			$sliders = LS_Sliders::find();
			foreach ( $sliders as $slider ) {
				$arr[ 'lay_' . $slider['id'] ] = $slider['name'];
			}
		}

		if ( class_exists( 'RevSliderSlider' ) ) {
			$RsExists = count( $wpdb->get_results( "SELECT * FROM information_schema.tables WHERE table_schema = '" . $wpdb->dbname . "' AND table_name = '" . $wpdb->prefix . "revslider_sliders' LIMIT 1",
				ARRAY_A ) );
			if ( $RsExists > 0 ) {
				$revSliders = $wpdb->get_results( "SELECT title, alias FROM " . $wpdb->prefix . "revslider_sliders WHERE ( type is NULL OR type = '' )",
					ARRAY_A );
				if ( count( $revSliders ) > 0 ) {
					foreach ( $revSliders as $slider ) {
						$arr[ 'rev_' . $slider['alias'] ] = $slider['title'];
					}
				}
			}
		}

		if ( class_exists( 'SmartSlider3' ) ) {
			$sliders = $wpdb->get_results( "SELECT id, title FROM " . $wpdb->prefix . "nextend2_smartslider3_sliders" );
			if ( ! empty( $sliders ) ) {
				foreach ( $sliders as $slider ) {
					$arr[ 'smart_' . $slider->id ] = $slider->title;
				}
			}
		}

		if ( count( $arr ) === 1 ) {
			$arr = array(
				0 => esc_html( $text ),
			);
		}
		self::$sliders_array = $arr;

		return $arr;
	}

	public static function get_composer_block_array( $type = 'sidebar' ) {

		if ( isset( self::$composer_block_array[ $type ] ) ) {
			return self::$composer_block_array[ $type ];
		}

		global $wpdb;

		$composer_block_array = array();
		if ( 'header' == $type || 'footer' == $type ) {
			$composer_block_array[ - 1 ] = 'None';
		}
		$composer_block_type = 'composer_block_' . $type;

		$query_arr = $wpdb->get_results( $wpdb->prepare( "SELECT pm.`post_id`, wps.`post_title` FROM $wpdb->postmeta pm INNER JOIN $wpdb->posts wps ON pm.post_id=wps.ID WHERE pm.meta_key = 'composer_block_type' AND pm.meta_value = '%s' AND wps.post_status = 'publish'",
			$composer_block_type ) );

		if ( is_array( $query_arr ) ) {
			foreach ( $query_arr as $composer_block_data ) {
				$composer_block_array[ $composer_block_data->post_id ] = $composer_block_data->post_title;
			}
		}
		self::$composer_block_array[ $type ] = $composer_block_array;

		return $composer_block_array;
	}

	/* ---> SET ARGUMENTS
	* All the possible arguments for Redux.
	* For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
	* */

	// Google Api Key
	public static function google_api() {
		return isset( Clienticabuilder_Core::$page_options['google_api_key_opt'] ) ? Clienticabuilder_Core::$page_options['google_api_key_opt'] : '';
	}

	public static function get_color_val( $color ) {
		return Clienticabuilder_Config::get_string_color( $color );
	}

	private static function get_string_color( $color ) {
		// No notices.
		$alpha = 1;
		$hex   = $rgba = '';

		// Must be an array.
		if ( is_array( $color ) ) {
			// Enum array to parse values.
			foreach ( $color as $id => $val ) {

				// Sanitize alpha.
				if ( 'alpha' === $id ) {
					$alpha = is_numeric( $val ) ? $val * 1 : 1;
				} elseif ( 'color' === $id ) {
					$hex = ! empty( $val ) ? $val : '';
				} elseif ( 'rgba' === $id ) {
					$rgba = ! empty( $val ) ? $val : '';
				}
			}

			// Only build rgba output if alpha ia less than 1.
			if ( $rgba !== '' ) {
				$color = 'rgba' . $rgba;
			} elseif ( $alpha === 1 ) {
				$color = $hex;
			} elseif ( $alpha < 1 ) {
				$color = self::hex2rgba( $hex, $alpha );
			} else {
				$color = '';
			}
		}

		return $color;

	}

	public static function hex2rgba( $hex, $alpha = '' ) {
		$hex = str_replace( '#', '', $hex );
		if ( 3 === strlen( $hex ) ) {
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}
		$rgb = $r . ',' . $g . ',' . $b;

		if ( '' === $alpha ) {
			return $rgb;
		} else {
			$alpha = floatval( $alpha );

			return 'rgba(' . $rgb . ',' . $alpha . ')';
		}
	}
}