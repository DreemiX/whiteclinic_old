<?php

if ( class_exists( 'WP_Customize_Control' ) ) {

	class Clienticabuilder_Customize_Color_Rgba_Setting extends WP_Customize_Setting {

		private $value = array();

		public function __construct( $manager, $id, $args = array() ) {

			parent::__construct( $manager, $id, $args );

			$value = array(
				'color' => '',
				'alpha' => 1,
				'rgba'  => '',
			);

			$this->value = wp_parse_args( $this->value(), $value );

			$this->default = Clienticabuilder_Config::get_color_val( $this->value );

		}

		public function enqueue() {
			wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_script( 'wp-color-picker-alpha', CLIENTICABUILDER_FRAMEWORK_URL . '/js/wp-color-picker-alpha.min.js', array( 'wp-color-picker' ), false, true );
		}
	}
}