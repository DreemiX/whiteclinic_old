<?php

if ( class_exists( 'WP_Customize_Control' ) ) {

	class Clienticabuilder_Customize_Info_Control extends WP_Customize_Control {

		private $args = array();

		public function __construct( $manager, $id, $args = array() ) {

			parent::__construct( $manager, $id, $args );

			$this->type = 'info';

			$defaults = [
				'description'  => '',
				'class'  => '',
			];

			$this->args = wp_parse_args( $args, $defaults );
		}

		public function render_content(){
			echo '<div class="clienticabuilder-customize-control ' . esc_attr( $this->type ) . '" data-type="' . esc_attr( $this->type ) . '">';
			echo '<span class="customize-control-title">' , $this->label , '</span>';
			echo '<span class="description customize-control-description">' , $this->args['description'] , '</span>';
			echo '</div>';
		}
	}
}