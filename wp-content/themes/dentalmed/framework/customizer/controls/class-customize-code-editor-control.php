<?php

if ( class_exists( 'WP_Customize_Control' ) ) {

	class Clienticabuilder_Customize_Code_Editor_Control extends WP_Customize_Code_Editor_Control {

		public function __construct( $manager, $id, $args = array() ) {

			parent::__construct( $manager, $id, $args );

			$this->type = 'code_editor';

		}

	}
}