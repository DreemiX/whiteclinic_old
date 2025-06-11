<?php

/**
 * The class contain functions for footer sections.
 */
class Clienticabuilder_Footer {

	/**
	 * Clienticabuilder_Footer constructor.
	 */
	public function __construct() {
		add_action( 'wp_footer', array( $this, 'enqueue_scripts' ) );
	}

	public static function set_footer_sidebar_layout() {
		echo '	</div><!-- mainsidebar -->';
		if ( 2 === Clienticabuilder_Core::$page_options['sidebar_layout'] || 4 === Clienticabuilder_Core::$page_options['sidebar_layout'] ) {
			echo '<div class="col-lg-3 col-md-12 col-sm-12 widget-area right_sb animated slideInRight">';
			echo '<div>';
			try{
				dynamic_sidebar( Clienticabuilder_Core::$page_options['right_sidebar_widgets'] );
			} catch (Exception $e) {
				esc_html_e( 'Check widgets settings in this sidebar', 'dentalmed' );
			}
			echo '</div>';
			echo '</div>';

		}
		if ( Clienticabuilder_Core::$page_options['sidebar_layout'] !== 1 ) {
			echo '</div><!-- sbcenter -->';
		}
	}

	public static function footer_close_boxed_layout() {
		if ( 2 === Clienticabuilder_Core::$page_options[ Clienticabuilder_Core::$page_options['page-layout'] ] ) {
			echo '</div></div></div>';
		}
	}

	/**
	 * Footer
	 */
	public static function footer() {
		try {
			self::set_footer_widget();
		} catch ( Exception $e ) {
			Clienticabuilder_Theme_Demo::the_footer();
		}
	}

	private static function set_footer_widget() {
		if ( class_exists( 'custom_post_widget' ) ) {
			if ( - 1 !== Clienticabuilder_Core::$page_options['footer_widget'] ) {
				the_widget(
					'custom_post_widget',
					array(
						'custom_post_id'        => Clienticabuilder_Core::$page_options['footer_widget'],
						'apply_content_filters' => true,
					),
					array(
						'before_widget' => '<div class="footer-widget %s">',
					)
				);
			}
		} else {
			throw new Exception( esc_html__( 'There is no correct widget.', 'dentalmed' ) );
		}
	}

	public function enqueue_scripts() {
		$this->add_google_map();
		if ( ! empty ( Clienticabuilder_Core::$page_options['footer_js'] ) ) {
			wp_register_script( 'clienticabuilder_footer_js', false );
			wp_enqueue_script( 'clienticabuilder_footer_js' );
			wp_add_inline_script( 'clienticabuilder_footer_js', Clienticabuilder_Core::$page_options['footer_js'] );
		}
	}
	
	private static function add_google_map() {
		$api = Clienticabuilder_Config::google_api();
		if ( '' !== $api ) {
			wp_register_script( 'clienticabuilder-google-maps-api', 'https://maps.googleapis.com/maps/api/js?key=' . $api . '', array(), null, true );
			wp_enqueue_script( 'clienticabuilder-google-maps-api' );
		}
	}
}

?>
