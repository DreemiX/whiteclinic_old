<?php

if ( class_exists( 'WP_Customize_Control' ) ) {

	class Clienticabuilder_Customize_Advanced_Background_Control extends WP_Customize_Control  {

		/**
		 * Labels for upload control buttons.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    array
		 */
		public $button_labels = array();

		private $args = array();

		private $value;

		public function __construct( $manager, $id, $args = array() ) {

			parent::__construct( $manager, $id, $args );

			$this->type = 'advanced_background';

			$defaults = array(
				'description' => '',
				'class'       => '',
			);

			$this->args = wp_parse_args( $args, $defaults );

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

			$this->value = wp_parse_args( $this->value(), $defaults );

		}

		public function render_content() {
			echo '<div class="clienticabuilder-customize-control ' . esc_attr( $this->type ) . '" data-type="' . esc_attr( $this->type ) . '">';
			echo '<span class="customize-control-title">', $this->label, '</span>';
			echo '<span class="description customize-control-description">', $this->args['description'], '</span>';

			echo '<div class="' . esc_attr( $this->type ) . '-control">';
			echo '<input type="text" id="' . esc_attr( $this->id ) . '-color" class="bg-settings-input wp-color-picker color-picker ' . esc_attr( $this->id ) . '" value="' . esc_attr( Clienticabuilder_Config::get_color_val( $this->value['color'] ) ) . '" data-alpha="true"  data-setting="color" >';

			echo '<div id="' . esc_attr( $this->id ) . '-upload-links-box">';
			echo '<div class="background-image-view"><img src="' . esc_url( $this->value['image'] ) . '"></div>';
			echo '<button  type="button" class="change-image ' . esc_attr( $this->id ) . '-change-image" id="' . esc_attr( $this->id ) . '-change-image">' . esc_html__( 'Add/change', 'dentalmed' ) . '</button><br>';
			echo '<button  type="button" class="remove-image ' . esc_attr( $this->id ) . '-remove-image" id="' . esc_attr( $this->id ) . '-remove-image">' . esc_html__( 'Remove', 'dentalmed' ) . '</button>';
			echo '<input id="' . esc_attr( $this->id ) . '-image-input" class="bg-settings-input ' . esc_attr( $this->type ) . '-image-url ' . esc_attr( $this->id ) . '-image-url" type="text" value="' . esc_attr( $this->value['image'] ) . '" name="" data-setting="image" >';
			echo '</div>';


			$labels  = $this->get_field_labels();
			$choices = $this->get_background_choices();
			foreach ( $choices as $id => $chs ) {
				$label = empty( $labels[ $id ] ) ? '' : $labels[ $id ];
				echo '<div>';
				echo '<span>' . $label . '</span>';
				$this->render_select( $chs, $id, $label );
				echo '</div>';
			}
			echo '<input type="hidden" id="' . esc_attr( $this->id ) . '" class="hidden ' . esc_attr( $this->id ) . '" ' . $this->get_link() . '>';
			echo '</div>';
			echo '</div>';
		}

		private function render_select( $opts, $id, $pl = '' ) {
			echo '<select id="' . esc_attr( $this->id ) . '-' . $id . '-select" data-placeholder="' . $pl . '" class="bg-settings-input ' . esc_attr( $this->id ) . '-' . $id . '-select " data-setting="' . $id . '">';
			echo '<option></option>';

			foreach ( $opts as $k => $v ) {
				echo '<option value="' . esc_attr( $k ) . '" ' . selected( $this->value[ $id ], $k, false ) . '>' . esc_html( $v ) . '</option>';
			}

			echo '</select>';
		}

		public function enqueue() {
			parent::enqueue();
			wp_enqueue_style( 'wp-color-picker' );
				wp_enqueue_script( 'wp-color-picker-alpha', CLIENTICABUILDER_FRAMEWORK_URL . '/js/wp-color-picker-alpha.min.js', array( 'wp-color-picker' ), false, true );

			wp_localize_script(
				'clienticabuilder-customizer',
				'clienticabuilderCustomizer',
				array(
					'fields' => array(
						$this->type => array(
							'l10n' => $this->get_button_labels(),
						)
					),
				)
			);
		}

		/**
		 * Returns button labels.
		 *
		 * @since 1.0.0
		 */
		private function get_button_labels() {
			return array(
				'select'       => esc_html__( 'Select Image', 'dentalmed' ),
				'change'       => esc_html__( 'Change Image', 'dentalmed' ),
				'remove'       => esc_html__( 'Remove', 'dentalmed' ),
				'default'      => esc_html__( 'Default', 'dentalmed' ),
				'placeholder'  => esc_html__( 'No image selected', 'dentalmed' ),
				'frame_title'  => esc_html__( 'Select Image', 'dentalmed' ),
				'frame_button' => esc_html__( 'Choose Image', 'dentalmed' ),
			);
		}

		/**
		 * Returns field labels.
		 *
		 * @since 1.0.0
		 */
		private function get_field_labels() {
			return array(
				'repeat'     => esc_html__( 'Background Repeat', 'dentalmed' ),
				'size'       => esc_html__( 'Background Size', 'dentalmed' ),
				'position'   => esc_html__( 'Background Position', 'dentalmed' ),
				'attachment' => esc_html__( 'Background Attachment', 'dentalmed' )
			);
		}

		/**
		 * Returns the background choices.
		 *
		 * @since 1.0.0
		 * @return array
		 */
		private function get_background_choices() {
			return array(
				'repeat'     => array(
					'no-repeat' => esc_html__( 'No Repeat', 'dentalmed' ),
					'repeat'    => esc_html__( 'Repeat', 'dentalmed' ),
					'repeat-x'  => esc_html__( 'Repeat Horizontally', 'dentalmed' ),
					'repeat-y'  => esc_html__( 'Repeat Vertically', 'dentalmed' ),
				),
				'size'       => array(
					'auto'    => esc_html__( 'Auto', 'dentalmed' ),
					'cover'   => esc_html__( 'Cover', 'dentalmed' ),
					'contain' => esc_html__( 'Contain', 'dentalmed' ),
					'inherit' => esc_html__( 'Inherit', 'dentalmed' )
				),
				'position'   => array(
					'left top'      => esc_html__( 'Left Top', 'dentalmed' ),
					'left center'   => esc_html__( 'Left Center', 'dentalmed' ),
					'left bottom'   => esc_html__( 'Left Bottom', 'dentalmed' ),
					'right top'     => esc_html__( 'Right Top', 'dentalmed' ),
					'right center'  => esc_html__( 'Right Center', 'dentalmed' ),
					'right bottom'  => esc_html__( 'Right Bottom', 'dentalmed' ),
					'center top'    => esc_html__( 'Center Top', 'dentalmed' ),
					'center center' => esc_html__( 'Center Center', 'dentalmed' ),
					'center bottom' => esc_html__( 'Center Bottom', 'dentalmed' ),
				),
				'attachment' => array(
					'fixed'  => esc_html__( 'Fixed', 'dentalmed' ),
					'scroll' => esc_html__( 'Scroll', 'dentalmed' ),
				)
			);
		}
	}
}