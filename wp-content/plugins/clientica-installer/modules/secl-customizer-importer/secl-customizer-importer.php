<?php
/**
 * Created by PhpStorm.
 * User: Alex
 * Date: 23.07.2020
 * Time: 18:15
 */

class CLIENTICA_Customizer_Importer {

	public static $instance = null;

	private $export_data = array();

	private $depth = 1024;

	private $post = null;

	private $parent_slug = 'themes.php';

	private $export_slug = 'customizer-export';

	private $import_slug = 'customizer-import';

	private $file_name = 'customizer_export';

	private $import_file_type = 'application/json';

	/**
	 * @return string
	 */
	public function get_file_name() {
		return $this->file_name;
	}

	/**
	 * @return string
	 */
	public function get_parent_slug() {
		return $this->parent_slug;
	}

	/**
	 * @return string
	 */
	public function get_export_slug() {
		return $this->export_slug;
	}

	/**
	 * @return string
	 */
	public function get_import_slug() {
		return $this->import_slug;
	}

	public static function get_instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function add_export_subpage() {

		add_theme_page(
			__( 'Customize Export', 'clientica-installer' ),
			__( 'Customize Export', 'clientica-installer' ),
			'manage_options',
			$this->export_slug,
			array( $this, 'create_export_page' ),
            2
		);

	}

	public function add_import_subpage() {

		add_theme_page(
			__( 'Customize Import', 'clientica-installer' ),
			__( 'Customize Import', 'clientica-installer' ),
			'manage_options',
			$this->import_slug,
			array( $this, 'create_import_page' ),
            2
		);

	}

	public function create_export_page() {
		?>
        <div class="wrap">
            <h2><?php echo __( 'Export', 'clientica-installer' ); ?></h2>

            <hr class="wp-header-end">

            <p><?php echo __( 'Copy data from textarea to file', 'clientica-installer' ); ?></p>

            <textarea rows="10" cols="100" style="display:block;width:100%;"><?php $this->export_text(); ?></textarea>
        </div>

		<?php

	}

	public function create_import_page() {
		try {
			if ( $this->import_from_form() ) { ?>
                <div class="notice notice-success settings-success is-dismissible">
                    <h4><?php esc_html_e( 'Import was finished successfully' ) ?></h4>
                </div>
			<?php }
		} catch ( Exception $e ) {
			error_log( __METHOD__ . ' get error: ' . $e->getMessage() );
			?>
            <div class="notice notice-error settings-error is-dismissible">
                <h4><?php esc_html_e( 'Import error, check errors log!' ) ?></h4>
            </div>
			<?php
		}
		?>
        <div class="wrap">
            <h2><?php echo __( 'Import', 'clientica-installer' ); ?></h2>

            <hr class="wp-header-end">

            <form method="POST" action="" enctype="multipart/form-data">

                <h3><?php echo __( 'Choose json import file', 'clientica-installer' ); ?></h3>

				<?php wp_nonce_field( 'secl_customize_import', 'secl_customize_import_nonce' ); ?>

                <input type="file" name="import_file">

				<?php submit_button( __( esc_html__( 'Import', 'clientica-installer' ) ) ); ?>

            </form>
        </div>

		<?php

	}

	private function import_from_form() {
		if ( empty( $_POST['secl_customize_import_nonce'] ) || empty( $_FILES['import_file'] ) || ! wp_verify_nonce( $_POST['secl_customize_import_nonce'], 'secl_customize_import' ) ) {
			return false;
		}
		$import_file = $_FILES['import_file'];
		if ( $this->is_correct_import_file_type( $import_file['type'] ) ) {
			global $wp_filesystem;
			if ( empty( $wp_filesystem ) ) {
				require_once( ABSPATH . '/wp-admin/includes/file.php' );
				WP_Filesystem();
			}
			$data = $wp_filesystem->get_contents( $import_file['tmp_name'] );

			return $this->import( $data );
		}
	}

	/**
	 * @param $data
	 *
	 * @return bool
	 * @throws Exception
	 */
	public function import( $data ) {
		if ( $array_data = json_decode( $data, true, $this->depth ) ) {

			if ( function_exists( 'ini_set' ) ) {
				ini_set( 'max_execution_time', '300' );
				ini_set( 'memory_limit', '128M' );
			}

			return $this->import_process( $array_data );
		} else {
			throw new Exception( esc_html__( 'Wrong Import File data', 'clientica-installer' ) );
		}
	}

	public function import_process( $array_data ) {
		foreach ( $array_data as $key => $data ) {
			set_theme_mod( $key, $data );
		}

		return true;
	}

	public function is_correct_import_file_type( $type ) {
		if ( $type === $this->import_file_type ) {
			return true;
		} else {
			throw new Exception( esc_html__( 'Wrong File Type', 'clientica-installer' ) );
		}
	}

	private function export_text() {
		echo json_encode( $this->get_export_data(), 0, $this->depth );
	}

	private function get_export_data() {
		if ( $theme_options = get_theme_mods() ) {
			return $theme_options;
		}
		return array();
	}

}