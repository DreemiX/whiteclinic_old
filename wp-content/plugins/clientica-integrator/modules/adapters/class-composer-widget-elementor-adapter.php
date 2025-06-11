<?php

class Composer_Widget_Elementor_Adapter {

	private $instance;

	public function __construct() {
		if( class_exists( '\Elementor\Plugin' ) ){
			$this->instance = \Elementor\Plugin::$instance;
		} else {
			throw new Exception( 'Elementor' . ' ' . __( 'plugin don\'t found', 'clientica-integrator' ) );
		}
	}


	public function widget( $id ) {

		return $this->instance->frontend->get_builder_content( $id, false );

	}
}