<?php

if ( class_exists( 'WP_Customize_Control' ) ) {

	class Clienticabuilder_Customize_Advanced_Background_Setting extends WP_Customize_Setting {

		private $value = array();

		public function __construct( $manager, $id, $args = array() ) {

			$args['sanitize_callback']    = array( $this, 'sanitize_callback' );
			$args['sanitize_js_callback'] = array( $this, 'sanitize_js_callback' );

			parent::__construct( $manager, $id, $args );

			$defaults = array(
				'color'      => array(
					'color' => '',
					'alpha' => 1,
					'rgba'  => '',
				),
				'repeat'     => '',
				'attachment' => '',
				'position'   => '',
				'image'      => '',
				'clip'       => '',
				'origin'     => '',
				'size'       => '',
			);

			$this->value   = wp_parse_args( $this->value(), $defaults );
			$this->default = $this->value;

		}

		public function sanitize_callback( $value ) {
			if ( ! empty( $value['image'] ) ) {
				$value['image'] = esc_url_raw( $value['image'] );
			}
			return json_decode( $value, true );
		}

		public function sanitize_js_callback( $value, $el ) {
			return json_encode( $value, true );
		}
	}
}