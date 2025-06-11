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
class Clienticabuilder_Elementor_Adapter extends Clienticabuilder_Base_Adapter {

	private $plugin = null;

	protected function __construct() {
		$this->plugin = \Elementor\Plugin::$instance;

		add_filter( 'elementor/documents/ajax_save/return_data', array( $this, 'save_styles' ), 10, 2 );
	}

	public function enqueue_font( $font ) {
		$this->plugin->frontend->enqueue_font( $font );
	}

	public function print_fonts_links() {
		$this->plugin->frontend->print_fonts_links();
	}

	public function save_styles( $return_data, $document ) {
		if ( ! ( $document instanceof Elementor\Core\Kits\Documents\Kit ) ) {
			return $return_data;
		}

		$data = $document->get_data();

		if ( empty( $data['settings'] ) ) {
			return $return_data;
		}

		$data = $data['settings'];

		$styles = array(
			'typography-body' => $this->set_typography_styles( $data, 'body' ),
			'h1-typography'   => $this->set_typography_header_styles( $data, 'h1' ),
			'h2-typography'   => $this->set_typography_header_styles( $data, 'h2' ),
			'h3-typography'   => $this->set_typography_header_styles( $data, 'h3' ),
		);

		$this->change_style( $styles );

		return $return_data;
	}

	private function set_typography_header_styles( $data, $key ) {
		$styles = $this->set_typography_styles( $data, $key );

		$styles['line-height']    = empty( $data[ $key . '_typography_line_height' ] ) ? $this->set_typography_font_line_height_globals( $data, $key ) : $data[ $key . '_typography_line_height' ]['size'] . $data[ $key . '_typography_line_height' ]['unit'];
		$styles['text-transform'] = empty( $data[ $key . '_typography_text_transform' ] ) ? $this->set_typography_font_text_transform_globals( $data, $key ) : $data[ $key . '_typography_text_transform' ];

		return $styles;
	}

	private function set_typography_styles( $data, $key ) {
		if ( ! empty( $data[ $key . '_typography_font_family' ] ) ) {
			return $this->set_typography_custom( $data, $key );
		} else {
			return $this->set_typography_globals( $data, $key );
		}

	}

	private function set_typography_custom( $data, $key ) {

		$styles['font-family'] = $data[ $key . '_typography_font_family' ];

		$styles['font-weight'] = empty( $data[ $key . '_typography_font_weight' ] ) ? $this->set_typography_font_weight_globals( $data, $key ) : $data[ $key . '_typography_font_weight' ];

		$styles['font-size'] = empty( $data[ $key . '_typography_font_size' ] ) ? $this->set_typography_font_size_globals( $data, $key ) : $data[ $key . '_typography_font_size' ]['size'] . $data[ $key . '_typography_font_size' ]['unit'];

		$styles['color'] = empty( $data[ $key . '_color' ] ) ? $this->set_typography_font_body_color_globals( $data, $key ) : $data[ $key . '_color' ];

		return $styles;

	}

	private function set_typography_globals( $data, $key ) {
		$styles['font-family'] = $this->set_typography_font_family_globals( $data, $key );
		$styles['font-weight'] = $this->set_typography_font_weight_globals( $data, $key );
		$styles['font-size']   = $this->set_typography_font_size_globals( $data, $key );
		$styles['color']       = $this->set_typography_font_body_color_globals( $data, $key );

		return $styles;
	}

	private function set_typography_font_family_globals( $data, $key ) {
		if ( $id = $this->get_global_setting_id( $data, $key . '_typography_typography' ) ) {
			foreach ( $data['system_typography'] as $system ) {
				if ( $system['_id'] == $id && ! empty( $system['typography_font_family'] ) ) {
					return $system['typography_font_family'];
				}
			}
		}

		return Clienticabuilder_Theme_Demo::DEMO_TYPOGRAPHY['typography_font_family'];
	}

	private function set_typography_font_weight_globals( $data, $key ) {
		if ( $id = $this->get_global_setting_id( $data, $key . '_typography_typography' ) ) {
			foreach ( $data['system_typography'] as $system ) {
				if ( $system['_id'] == $id && ! empty( $system['typography_font_weight'] ) ) {
					return $system['typography_font_weight'];
				}
			}
		}

		return Clienticabuilder_Theme_Demo::DEMO_TYPOGRAPHY[ $key . '_typography_font_weight' ];
	}

	private function set_typography_font_size_globals( $data, $key ) {
		if ( $id = $this->get_global_setting_id( $data, $key . '_typography_typography' ) ) {
			foreach ( $data['system_typography'] as $system ) {
				if ( $system['_id'] == $id && ! empty( $system['typography_font_size'] ) ) {
					return $system['typography_font_size']['size'] . $system['typography_font_size']['unit'];
				}
			}
		}

		return Clienticabuilder_Theme_Demo::DEMO_TYPOGRAPHY[ $key . '_font_size' ];
	}

	private function set_typography_font_body_color_globals( $data, $key ) {
		if ( $id = $this->get_global_setting_id( $data, $key . '_color' ) ) {
			foreach ( $data['system_colors'] as $system ) {
				if ( $system['_id'] == $id && ! empty( $system['color'] ) ) {
					return $system['color'];
				}
			}
		}

		return Clienticabuilder_Theme_Demo::DEMO_TYPOGRAPHY[ $key . '_color' ];
	}

	private function set_typography_font_line_height_globals( $data, $key ) {
		if ( $id = $this->get_global_setting_id( $data, $key . '_typography_typography' ) ) {
			foreach ( $data['system_typography'] as $system ) {
				if ( $system['_id'] == $id && ! empty( $system['typography_line_height'] ) ) {
					return $system['typography_line_height']['size'] . $system['typography_line_height']['unit'];
				}
			}
		}

		return Clienticabuilder_Theme_Demo::DEMO_TYPOGRAPHY[ $key . '_line_height' ];
	}

	private function set_typography_font_text_transform_globals( $data, $key ) {
		if ( $id = $this->get_global_setting_id( $data, $key . '_typography_typography' ) ) {
			foreach ( $data['system_typography'] as $system ) {
				if ( $system['_id'] == $id && ! empty( $system['typography_text_transform'] ) ) {
					return $system['typography_text_transform'];
				}
			}
		}

		return Clienticabuilder_Theme_Demo::DEMO_TYPOGRAPHY[ $key . '_text_transforn' ];
	}

	private function get_global_setting_id( $data, $set ) {
		if ( ! empty( $data['__globals__'][ $set ] ) && false !== strpos( $data['__globals__'][ $set ], '?' ) ) {
			$command_parts = explode( '?', $data['__globals__'][ $set ] );
			$query_string  = $command_parts[1];
			parse_str( $query_string, $temp );

			return $temp['id'];
		} else {
			return false;
		}
	}

}

