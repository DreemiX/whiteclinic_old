<?php

if ( class_exists( 'WP_Customize_Control' ) ) {

	class Clienticabuilder_Customize_Color_Rgba_Control extends WP_Customize_Control {

		private $args = array();

		private $value = array();

		public function __construct( $manager, $id, $args = array() ) {

			parent::__construct( $manager, $id, $args );

			$this->type = 'color_rgba';

			$defaults = array(
				'description' => '',
				'class'       => '',
			);

			$this->args = wp_parse_args( $args, $defaults );

			$value = array(
				'color' => '',
				'alpha' => 1,
				'rgba'  => '',
			);

			$this->value = wp_parse_args( $this->value(), $value );
		}

		public function render_content() {
			echo '<div class="clienticabuilder-customize-control ' . esc_attr( $this->type ) . '" data-type="' . esc_attr( $this->type ) . '">';
			echo '<span class="customize-control-title">', $this->label, '</span>';
			echo '<span class="description customize-control-description">', $this->args['description'], '</span>';

			echo '<div class="' . esc_attr( $this->type ) . '-control">';
			echo '<input type="text" id="' . esc_attr( $this->id ) . '" class="wp-color-picker color-picker ' . esc_attr( $this->id ) . '" ' . $this->get_link() . ' data-alpha="true" >';
			echo '</div>';
			echo '</div>';
		}

		public function enqueue() {
			wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_script( 'wp-color-picker-alpha', CLIENTICABUILDER_FRAMEWORK_URL . '/js/wp-color-picker-alpha.min.js', array( 'wp-color-picker' ), false, true );
		}
	}
}