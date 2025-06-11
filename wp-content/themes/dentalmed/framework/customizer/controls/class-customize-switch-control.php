<?php

if ( class_exists( 'WP_Customize_Control' ) ) {

	class Clienticabuilder_Customize_Switch_Control extends WP_Customize_Control {

		private $args = array();

		public function __construct( $manager, $id, $args = array() ) {

			parent::__construct( $manager, $id, $args );

			$this->type = 'switch';

			$defaults = [
				'on'  => esc_html__( 'On', 'dentalmed' ),
				'off'  => esc_html__( 'Off', 'dentalmed' ),
				'description'  => '',
				'class'  => '',
			];

			$this->args = wp_parse_args( $args, $defaults );
		}

		public function render_content(){
			$enabled  = '';
			$disabled = '';
			$value = $this->value();
			// Get selected.
			if ( $value ) {
				$enabled = 'selected';
			} else {
				$disabled = 'selected';
			}
			echo '<div class="clienticabuilder-customize-control ' . esc_attr( $this->type ) . '" data-type="' . esc_attr( $this->type ) . '">';
			echo '<span class="customize-control-title">' , $this->label , '</span>';
			echo '<span class="description customize-control-description">' , $this->args['description'] , '</span>';

			echo '<div class="' . esc_attr( $this->type ) . '-control">';
			echo '<label class="enable ' . esc_attr( $enabled ) . '" data-id="' . esc_attr( $this->id ) . '" data-status="true"><span>' . esc_html( $this->args['on'] ) . '</span></label>';
			echo '<label class="disable ' . esc_attr( $disabled ) . '" data-id="' . esc_attr( $this->id ) . '" data-status="false"><span>' . esc_html( $this->args['off'] ) . '</span></label>';
			echo '<input type="hidden" class="checkbox checkbox-input ' . esc_attr( $this->args['class'] ) . '" id="' . esc_attr( $this->id ) . '" ' . $this->get_link() . ' />';
			echo '</div>';
			echo '</div>';
		}
	}
}