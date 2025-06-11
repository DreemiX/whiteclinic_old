<?php

if ( class_exists( 'WP_Customize_Control' ) ) {

	class Clienticabuilder_Customize_Switch_Setting extends WP_Customize_Setting {

		public function __construct( $manager, $id, $args = array() ) {

			$args['sanitize_callback']    = array( $this, 'sanitize_callback' );

			parent::__construct( $manager, $id, $args );
		}

		public function sanitize_callback( $value ) {
			return 'true' == $value;
		}
	}
}