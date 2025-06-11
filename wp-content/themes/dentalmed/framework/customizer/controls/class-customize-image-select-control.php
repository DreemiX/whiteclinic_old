<?php

if ( class_exists( 'WP_Customize_Control' ) ) {

	class Clienticabuilder_Customize_Image_Select_Control extends WP_Customize_Control {

		private $args = array();

		public function __construct( $manager, $id, $args = array() ) {

			parent::__construct( $manager, $id, $args );

			$this->type = 'image_select';

			$defaults = [
				'description' => '',
				'options'     => array(),
				'class'       => '',
			];

			$this->args = wp_parse_args( $args, $defaults );
		}

		public function render_content() {
			if ( is_array( $this->args['options'] ) ) {
				$value = $this->value();
				echo '<div class="clienticabuilder-customize-control ' . esc_attr( $this->type ) . '" data-type="' . esc_attr( $this->type ) . '">';
				echo '<span class="customize-control-title">', $this->label, '</span>';
				echo '<span class="description customize-control-description">', $this->args['description'], '</span>';


				echo '<div class="' . esc_attr( $this->type ) . '-control">';

				echo '<ul class="' . esc_attr( $this->type ) . '-list ">';
					foreach ( $this->args['options'] as $val => $img ) {
						$sel = $value == $val ? 'selected' : '';
						echo '<li data-value="' . $val . '" class="' . $sel . ' " ><img src="' . esc_attr( $img['img'] ) . '" alt="' .  esc_attr( $img['alt'] ) . '"></li>';
					}
				echo '</ul>';
				echo '<input type="hidden" class="text-input ' . esc_attr( $this->args['class'] ) . '" id="' . esc_attr( $this->id ) . '" ' . $this->get_link() . ' value="' . esc_attr( $value ) . '" />';

				echo '</div>';

				echo '</div>';
			}
		}
	}
}