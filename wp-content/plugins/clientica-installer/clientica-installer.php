<?php

/*
Plugin Name: Clientica Installer
Plugin URI: https://clientica.org/
Description: Imports necessary data and settings for Clientica Theme
Author: Clientica
Version: 1.1
Author URI: https://clientica.org/
*/

define( 'CLIENTICA_INSTALLER_PATH', dirname( __FILE__ ) );
define( 'CLIENTICA_INSTALLER_DIR_URL', plugin_dir_url( __FILE__ ) );

class CLIENTICA_Setup {

//	public $theme_type = '';
	public $plugins;

	public function __construct() {

		try{

			if ( isset( $_POST['op'] ) ) {
				$method = $_POST['op'];

				$this->install_demo_archive();

				if ( 'install_plugins' === $method || 'activate_plugins' === $method ) {
					// Remove else after all theme will be updated
					if ( method_exists( 'Clienticabuilder_Theme_Demo', 'get_plugins' ) ) {
						$this->plugins = Clienticabuilder_Theme_Demo::get_plugins();
					} else {
						$this->plugins = array();
					}
					echo wp_json_encode( $this->plugins );
				}

				add_filter( 'http_request_timeout', array( &$this, 'bump_request_timeout' ) );
				set_time_limit( 0 );

				WP_Filesystem();
			} else {
				throw new Exception( __( 'Wrong request', 'clientica-installer' ) );
			}

		} catch (\Exception $e) {
			echo esc_html($e->getMessage());
			$this->abort();
		}
	}

	public function bump_request_timeout() {
		return 60;
	}

	public function install_plugin() {

		if ( filter_input( INPUT_POST, 'op' ) === 'install_plugin' ) {

			if ( isset( $_POST['i_id'] ) && $_POST['i_id'] == 0 ) {
				delete_transient( 'clientica_on_click_setup' );
			}

			$setup_opts = array( 'i_id' => 0, );

			$opts = array(
				'install_plugin',
				'activate_plugin',
				'technical_refresh',
				'import_attachments',
				'import_data',
				'import_widgets',
				'set_sliders',
				'import_caldera_forms',
				'import_sample_data',
				'i_id',
				'install_theme',
				'set_elementor_active_kit_id',
				'clear_cache',
			);

			foreach ( $opts as $opt ) {
				if ( isset( $_POST[ $opt ] ) ) {
					$setup_opts[ $opt ] = $_POST[ $opt ];
				}
			}

			set_transient( 'clientica_on_click_setup', $setup_opts, 60 * 10 );
			$tgm_install      = 1;
			$tgm_is_automatic = false;
			$msg              = esc_attr__( 'Plugins Installed', 'clientica-installer' );

		} else if ( $_POST['op'] == 'activate_plugin' ) {
			$tgm_install      = 0;
			$tgm_is_automatic = true;
			$msg              = esc_attr__( 'Plugins Activated', 'clientica-installer' );
		}

		if ( isset( $_POST ) && is_array( $_POST ) ) {
			$plugin = $_POST;
			if ( $tgm_install == 1 ) {
				$_GET['plugin']       = $plugin['slug'];
				$_POST['tgm_pass']    = 1;
				$_POST['tgm_install'] = $tgm_install;
				$tgma                 = new TGM_Plugin_Activation();
				$tgma->register( $plugin );
				$tgma->is_automatic = $tgm_is_automatic;
				$tgma->do_plugin_install();
			} else if ( $tgm_install == 0 ) {
				$_POST['tgm_pass'] = 0;
				$tgma              = new TGM_Plugin_Activation();
				$file_path         = $tgma->_get_plugin_basename_from_slug( $plugin['slug'] );
				$tgma->activate_single_plugin( $file_path, $plugin['slug'] );
			}
		} else {
			$msg = esc_attr__( 'No plugins data found', 'clientica-installer' );
		}

		echo '___<p><b>' . $msg . '</b></p>___';

	}

	public function abort() {
		delete_transient( 'clientica_on_click_setup' );
	}


	public function run() {
		$method = $_POST['op'];
		if ( $method == 'activate_plugin' ) {
			$method = 'install_plugin';
		}
		if ( method_exists( $this, $method ) ) {
			$this->$method();
		} else {
			$setup2 = new CLIENTICA_Installer();
			if ( method_exists( $setup2, $method ) ) {
				$setup2->$method();
			} else {
				$this->abort();
			}
		}
	}

	private function install_demo_archive() {
		$installer = new CLIENTICA_Installer();
		$theme_type = $installer->get_theme_type();
		$installer->upload_demo_archive( $theme_type );
		$installer->unzip_demo_data( $theme_type );
	}
}


function clientica_theme_setup() {

	$setup = new CLIENTICA_Setup();

	$setup->run();

}


add_action( 'wp_ajax_setup_theme', 'clientica_theme_setup' );


class CLIENTICA_Installer {

	private $wp_filesystem;

	private $wp_http = null;

	private $theme_type;

	private $import_url = 'https://clientica.org/import/';

	private $base_url = '';

	public function __construct() {

		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			require_once( ABSPATH . '/wp-admin/includes/file.php' );
			WP_Filesystem();
		}

		$this->wp_filesystem = $wp_filesystem;

		if ( isset( $_POST['theme_type'] ) ) {
			$this->theme_type = $_POST['theme_type'];
		} else {
			if ( class_exists( 'Clienticabuilder_Theme_Demo' ) ) {
				$this->theme_type = Clienticabuilder_Theme_Demo::DEFAULT_DEMO;
			} else {
				throw new Exception( __( 'There is no such demo', 'clientica-installer' ) );
			}
		}

		add_filter( 'wp_import_post_data_processed', array( $this, 'set_attachment_import_id' ), 10, 2 );
		add_filter( 'wp_import_post_data_raw', array( $this, 'set_correct_domain' ), 20, 1 );
		add_action( 'import_end', array( $this, 'import_end' ) );
	}

	/**
	 * @return string
	 */
	public function get_theme_type() {
		return $this->theme_type;
	}

	/**
	 * @param string $theme_type
	 */
	public function set_theme_type( $theme_type ) {
		$this->theme_type = $theme_type;
	}

	/**
	 * @return mixed
	 */
	public function get_wp_http() {
		if ( null === $this->wp_http ) {
			$this->wp_http = new WP_Http();
		}

		return $this->wp_http;
	}

	public function set_sliders() {

		global $wpdb;

		if ( class_exists( 'RevSliderSlider' ) ) {
			try {
				$this->upload_demo_archive( $this->theme_type );
				if ( ! $this->wp_filesystem->exists( get_template_directory() . '/import/' . $this->theme_type . '/sliders/rev-slider/' ) ) {
					$this->unzip_demo_data( $this->theme_type );
				}

			} catch ( Exception $e ) {
				echo $e->getMessage();

				return;
			}

			$installed_sliders = $wpdb->get_col( "SELECT title, alias FROM " . $wpdb->prefix . "revslider_sliders WHERE ( type is NULL OR type = '' )",
				1 );
			$files             = glob( get_template_directory() . '/import/' . $this->theme_type . '/sliders/rev-slider/*.zip' );
			$names             = preg_replace( '/(.+sliders\/rev-slider\/)([^\.]+)(\.zip)/', "\$2", $files );
			$result            = $msg = array();
			$i                 = 0;
			foreach ( $names as $name ) {
				if ( ! in_array( $name, $installed_sliders ) ) {
					$_FILES["import_file"]["tmp_name"] = $files[ $i ];
					if ( class_exists( 'RevSliderSliderImport' ) ) {
						$instance = new RevSliderSliderImport();
						$instance->import_slider();
					} else {
						$instance = new RevSliderSlider();
						$instance->importSliderFromPost();
					}
					$result[] = str_ireplace( '_', ' ', $name );
				} else {
					$msg[] = '<i>' . str_ireplace( '_', ' ', $name ) . '</i>';
				}
				$i ++;
			}
			if ( count( $result ) > 0 ) {
				$result = 'Revolution Sliders ' . implode( ', ', $result ) . ' are imported';
			} else {
				$result = '';
			}
			if ( count( $msg ) > 0 ) {
				$msg = '<br>NOTE: Revolution Sliders ' . implode( ', ', $msg ) . ' already exists';
			} else {
				$msg = '';
			}
			echo '___<p><b>' . $result . '</b>' . $msg . '</p>___';
		}
	}

	public function import_widgets( $echo = true ) {

		try {
			$this->upload_demo_archive( $this->theme_type );
			$file = glob( get_template_directory() . '/import/' . $this->theme_type . '/widgets.json' );
			if ( ! $this->wp_filesystem->exists( $file ) ) {
				$this->unzip_demo_data( $this->theme_type );
			}
		} catch ( Exception $e ) {
			echo $e->getMessage();

			return;
		}

		$file        = $file[0];
		$file_to_use = str_ireplace( '.json', '_to_use.json', $file );
		copy( $file, $file_to_use );
		wie_process_import_file( $file_to_use );

		if ( $echo ) {
			echo '___<p><b>' . esc_attr__( 'Widgets imported', 'clientica-installer' ) . '</b></p>___';
		}
	}

	public function import_sample_data() {
		if ( ( isset( $_POST['import_data'] ) && $_POST['import_data'] == 1 ) || $_POST['op'] == 'import_sample_data' ) {

			if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
				define( 'WP_LOAD_IMPORTERS', true );
			} // we are loading importers

			if ( ! class_exists( 'WP_Importer' ) ) { // if main importer class doesn't exist
				require ABSPATH . 'wp-admin/includes/class-wp-importer.php';
			}

			if ( ! class_exists( 'Clientica_Import' ) ) { // if WP importer doesn't exist
				require CLIENTICA_INSTALLER_PATH . '/modules/clientica-importer/clientica-importer.php';
			}

			if ( class_exists( 'Clientica_Import' ) ) {

				$_POST['imported_authors'][0] = 'admin';
				$_POST['imported_authors'][1] = 'wooteam';
				$_POST['use_map'][0]          = 0;
				$_POST['use_map'][1]          = 0;
				$_POST['user_new'][0]         = null;
				$_POST['user_new'][1]         = null;

				$importer                    = new Clientica_Import();
				$importer->fetch_attachments = false;

				try {
					$this->upload_demo_archive( $this->theme_type );
					$file = get_template_directory() . '/import/' . $this->theme_type . '/demo_data.xml';
					if ( ! $this->wp_filesystem->exists( $file ) ) {
						$this->unzip_demo_data( $this->theme_type );
					}
				} catch ( Exception $e ) {
					echo $e->getMessage();

					return;
				}

				$files = glob( get_template_directory() . '/import/' . $this->theme_type . '/demo_data.xml' );

				$past_files = glob( get_template_directory() . '/import/*inst.xml' );
				if ( count( $past_files ) > 0 ) {
					foreach ( $past_files as $pf ) {
						unlink( $pf );
					}
				}


				foreach ( $files as $file ) {
					$nfn = str_ireplace( '.xml', '_inst.xml', $file );
					if ( copy( $file, $nfn ) ) {
						$object = array(
							'post_title'     => $nfn,
							'post_content'   => $nfn,
							'post_mime_type' => '',
							'guid'           => $nfn,
							'context'        => 'import',
							'post_status'    => 'private',
						);

						$id = wp_insert_attachment( $object, $nfn );
						wp_schedule_single_event( time() + DAY_IN_SECONDS, 'importer_scheduled_cleanup', array( $id ) );

						$_POST['import_id'] = $id;
						$importer->id       = $id;

						ob_start();
						$importer->import( $nfn );
						if ( is_file( $nfn ) ) {
							unlink( $nfn );
						}
						sleep( 2 );
						ob_end_clean();
					}
				}
				echo '___<p class="green"><b>' . esc_attr__( 'Sample data imported', 'clientica-installer' ) . '</b></p>___';
			} else {
				echo '___<p class="red"><b>' . esc_attr__( 'There are problems with WP_Import classes, check if "wordpress-importer" plugin is activated',
						'clientica-installer' ) . '</b></p>___';
			}

			// Finish Import
			delete_transient( 'clientica_on_click_setup' );
		}
	}

	public function get_xml_file() {

		try {
			$this->upload_demo_archive( $this->theme_type );
			$file = glob( get_template_directory() . '/import/' . $this->theme_type . '/demo_data.xml' );
			if ( ! $this->wp_filesystem->exists( $file ) ) {
				$this->unzip_demo_data( $this->theme_type );
			}
		} catch ( Exception $e ) {
			echo $e->getMessage();

			return;
		}

		$file    = $file[0];
		$content = $this->wp_filesystem->get_contents( $file );

		ob_clean();
		echo $content;
	}

	public function get_uploaded_attachments() {
		$uploaded_attachments = get_option( 'clientica_uploaded_attachments' );
		try {
			if ( isset( $_POST['theme_type'] ) ) {
				$theme_type = $_POST['theme_type'];
			} else {
				if ( class_exists( 'Clienticabuilder_Theme_Demo' ) ) {
					$theme_type = Clienticabuilder_Theme_Demo::DEFAULT_DEMO;
				} else {
					throw new Exception( __( 'There is no such demo', 'clientica-installer' ) );
				}
			}
			$this->upload_demo_archive( $theme_type );
			$this->unzip_demo_data( $theme_type );
		} catch ( Exception $e ) {
			error_log( print_r( $e->getMessage(), true ) );
		}
		ob_clean();
		if ( $uploaded_attachments && count( $uploaded_attachments ) > 0 ) {
			echo json_encode( array( 'empty' => 'no', 'content' => array_values( $uploaded_attachments ) ) );
		} else {
			echo json_encode( array( 'empty' => 'yes', 'content' => $uploaded_attachments ) );
		}
	}

	public function attachment_upload() {
		//header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
		$parameters = array(
			'url'               => $_POST['url'],
			'post_title'        => $_POST['title'],
			'link'              => $_POST['link'],
			'pubDate'           => $_POST['pubDate'],
			'post_author'       => $_POST['creator'],
			'guid'              => $_POST['guid'],
			'import_id'         => $_POST['post_id'],
			'post_date'         => $_POST['post_date'],
			'post_date_gmt'     => $_POST['post_date_gmt'],
			'comment_status'    => $_POST['comment_status'],
			'ping_status'       => $_POST['ping_status'],
			'post_name'         => $_POST['post_name'],
			'post_status'       => $_POST['status'],
			'post_parent'       => $_POST['post_parent'],
			'menu_order'        => $_POST['menu_order'],
			'post_type'         => $_POST['post_type'],
			'post_password'     => $_POST['post_password'],
			'is_sticky'         => $_POST['is_sticky'],
			'attribute_author1' => $_POST['author1'],
			'attribute_author2' => $_POST['author2'],
		);

		$remote_url = ! empty( $parameters['attachment_url'] ) ? $parameters['attachment_url'] : $parameters['guid'];

		$result = $this->process_attachment( $parameters, $remote_url );
		ob_clean();
		echo json_encode( $result );

		if ( ! isset( $result['error'] ) && ! isset( $result['fatal'] ) ) {
			$uploaded_attachments = get_option( 'clientica_uploaded_attachments' );
			if ( ! is_array( $uploaded_attachments ) ) {
				$uploaded_attachments[] = array();
			}
			$uploaded_attachments[] = $_POST['post_id'];
			if ( is_array( $uploaded_attachments ) ) {
				update_option( 'clientica_uploaded_attachments', array_unique( $uploaded_attachments ) );
			}
		}

		die();
	}

	public function process_attachment( $post, $url ) {

		$pre_process = $this->pre_process_attachment( $post, $url );
		if ( is_wp_error( $pre_process ) ) {
			return array(
				'fatal'   => false,
				'type'    => 'error',
				'code'    => $pre_process->get_error_code(),
				'message' => $pre_process->get_error_message(),
				'text'    => sprintf( esc_attr__( '%1$s was not uploaded. (<strong>%2$s</strong>: %3$s)', 'clientica-installer' ),
					$post['post_title'],
					$pre_process->get_error_code(),
					$pre_process->get_error_message() ),
			);
		}

		// if the URL is absolute, but does not contain address, then upload it assuming base_site_url
		if ( preg_match( '|^/[\w\W]+$|', $url ) ) {
			$url = rtrim( $this->base_url, '/' ) . $url;
		}

		$upload = $this->fetch_remote_file( $url, $post );
		if ( is_wp_error( $upload ) ) {
			return array(
				'fatal'   => ( $upload->get_error_code() == 'upload_dir_error' && $upload->get_error_message() != 'Invalid file type' ? true : false ),
				'type'    => 'error',
				'code'    => $upload->get_error_code(),
				'message' => $upload->get_error_message(),
				'text'    => sprintf( esc_attr__( '%1$s could not be uploaded because of an error. (<strong>%2$s</strong>: %3$s)',
					'clientica-installer' ),
					$post['post_title'],
					$upload->get_error_code(),
					$upload->get_error_message() ),
			);
		}

		if ( $info = wp_check_filetype( $upload['file'] ) ) {
			$post['post_mime_type'] = $info['type'];
		} else {
			$upload = new WP_Error( 'attachment_processing_error', esc_attr__( 'Invalid file type', 'clientica-installer' ) );

			return array(
				'fatal'   => false,
				'type'    => 'error',
				'code'    => $upload->get_error_code(),
				'message' => $upload->get_error_message(),
				'text'    => sprintf( esc_attr__( '%1$s could not be uploaded because of an error. (<strong>%2$s</strong>: %3$s)',
					'clientica-installer' ),
					$post['post_title'],
					$upload->get_error_code(),
					$upload->get_error_message() ),
			);
		}

		$post['guid'] = $upload['url'];

		// Set author per user options.
		switch ( $post['attribute_author1'] ) {

			case 1: // Attribute to current user.
				$post['post_author'] = (int) wp_get_current_user()->ID;
				break;

			case 2: // Attribute to user in import file.
				if ( ! username_exists( $post['post_author'] ) ) {
					wp_create_user( $post['post_author'], wp_generate_password() );
				}
				$post['post_author'] = (int) username_exists( $post['post_author'] );
				break;

			case 3: // Attribute to selected user.
				$post['post_author'] = (int) $post['attribute_author2'];
				break;

		}

		// as per wp-admin/includes/upload.php
		$post_id = wp_insert_attachment( $post, $upload['file'] );
		wp_update_attachment_metadata( $post_id, wp_generate_attachment_metadata( $post_id, $upload['file'] ) );

		// remap image URL's
		$this->backfill_attachment_urls( $url, $upload['url'] );

		return array(
			'fatal' => false,
			'type'  => 'updated',
			'text'  => sprintf( esc_attr__( '%s was uploaded successfully', 'clientica-installer' ), $post['post_title'] ),
		);
	}

	public function pre_process_attachment( $post, $url ) {
		global $wpdb;

		$imported = $wpdb->get_results(
			$wpdb->prepare(
				"
					SELECT ID, post_date_gmt, guid
					FROM $wpdb->posts
					WHERE post_type = 'attachment'
						AND post_title = %s
					",
				$post['post_title']
			)
		);

		if ( $imported ) {
			foreach ( $imported as $attachment ) {
				if ( basename( $url ) == basename( $attachment->guid ) ) {
					if ( $post['post_date_gmt'] == $attachment->post_date_gmt ) {
						$WP_Http = $this->get_wp_http();
						$headers = $WP_Http->head( $url );
						if ( filesize( get_attached_file( $attachment->ID ) ) == $headers['headers']['content-length'] ) {
							return new WP_Error( 'duplicate_file_notice', esc_attr__( 'File already exists', 'clientica-installer' ) );
						}
					}
				}
			}
		}

		return false;
	}

	public function backfill_attachment_urls( $from_url, $to_url ) {
		global $wpdb;
		// remap urls in post_content
		$wpdb->query(
			$wpdb->prepare(
				"
						UPDATE {$wpdb->posts}
						SET post_content = REPLACE(post_content, %s, %s)
					",
				$from_url,
				$to_url
			)
		);
		// remap enclosure urls
		$result = $wpdb->query(
			$wpdb->prepare(
				"
						UPDATE {$wpdb->postmeta}
						SET meta_value = REPLACE(meta_value, %s, %s) WHERE meta_key='enclosure'
					",
				$from_url,
				$to_url
			)
		);
	}

	public function fetch_remote_file( $url, $post ) {
		// extract the file name and extension from the url
		$file_name = basename( $url );

		// get placeholder file in the upload dir with a unique, sanitized filename
		$upload = wp_upload_bits( $file_name, 0, '', $post['post_date'] );
		if ( $upload['error'] ) {
			return new WP_Error( 'upload_dir_error', $upload['error'] );
		}

		// fetch the remote url and write it to the placeholder file
		$remote_response = wp_safe_remote_get( $url,
			array(
				'timeout'  => 300,
				'stream'   => true,
				'filename' => $upload['file'],
				'headers'  => array(
					'Accept-Encoding' => 'identity',
				),
			) );

		$headers = wp_remote_retrieve_headers( $remote_response );
		// request failed
		if ( ! $headers ) {
			@unlink( $upload['file'] );

			return new WP_Error( 'import_file_error', __( 'Remote server did not respond', 'attachment-importer' ) );
		}

		$remote_response_code = wp_remote_retrieve_response_code( $remote_response );

		// make sure the fetch was successful
		if ( $remote_response_code != '200' ) {
			@unlink( $upload['file'] );

			return new WP_Error( 'import_file_error',
				sprintf( __( 'Remote server returned error response %1$d %2$s', 'wordpress-importer' ),
					esc_html( $remote_response_code ),
					get_status_header_desc( $remote_response_code ) ) );
		}

		$filesize = filesize( $upload['file'] );

		if ( isset( $headers['content-length'] ) && $filesize != $headers['content-length'] ) {
			@unlink( $upload['file'] );

			return new WP_Error( 'import_file_error', __( 'Remote file is incorrect size', 'attachment-importer' ) );
		}

		if ( 0 == $filesize ) {
			@unlink( $upload['file'] );

			return new WP_Error( 'import_file_error', __( 'Zero size file downloaded', 'attachment-importer' ) );
		}

		return $upload;
	}

	public function welcome_notice() {
		global $wn;

		$max = array(
			"max_execution_time"  => array(
				120,
				ini_get( "max_execution_time" ),
				" 'max_execution_time' " . esc_html__( "parameter on your hosting/server is ### seconds, 120 seconds recommended",
					"sci" ),
			),
			"memory_limit"        => array(
				184,
				intval( ini_get( "memory_limit" ) ),
				" 'memory_limit' " . esc_html__( "parameter on your hosting/server is ### Mb, 184Mb recommended",
					"sci" ),
			),
			"post_max_size"       => array(
				12,
				intval( ini_get( "post_max_size" ) ),
				" 'post_max_size' " . esc_html__( "parameter on your hosting/server is ### Mb, 12Mb recommended",
					"sci" ),
			),
			"upload_max_filesize" => array(
				12,
				intval( ini_get( "upload_max_filesize" ) ),
				" 'upload_max_filesize' " . esc_html__( "parameter on your hosting/server is ### Mb, 12Mb recommended",
					"sci" ),
			),
		);

		$init_msgs = array();
		foreach ( $max as $name => $set ) {
			if ( $set[1] > 0 && $set[1] < $set[0] ) {
				$init_msgs[] = str_ireplace( '###', $set[1], $set[2] );
			}
		}

		if ( count( $init_msgs ) > 0 ) {
			$init_msg = '<div id="message" class="notice notice-warning is-dismissible"><p>' . implode( '<br>', $init_msgs ) . '</p></div>';
		} else {
			$init_msg = '';
		}

		$wn['real_capabilities'] = $init_msg;

		$wn['recommended_capabilities'] = '<div class="col-md-4 col-sm-12">

						<h2 class="second">' . esc_html__( 'Server Requirements', 'clientica-installer' ) . '</h2>
						<div class="inform">
						<ul>
						<li>' . esc_html( 'max_execution_time 120' ) . '</li>
						<li>' . esc_html( 'memory_limit 184M' ) . '</li>
						<li>' . esc_html( 'post_max_size 12M' ) . '</li>
						<li>' . esc_html( 'upload_max_filesize 12M' ) . '</li>
						<li>' . esc_html( 'allow_url_fopen ON' ) . '</li>
						</ul>
						</div></div>';

		$wn['fail_install'] = '	<div class="col-md-4 col-sm-12">	                
						<h2 class="second">' . esc_html__( 'Fail of installation',
				'clientica-installer' ) . '</h2><div class="inform">' .
		                      esc_html__( 'If you got fail of the installation ask your hosting to check error logs',
			                      'clientica-installer' ) .
		                      '</div></div>';

	}

	public static function load_plugin_textdomain() {
		load_plugin_textdomain( 'clientica-installer', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

//	public static function correct_imported_attaches_list( $id ) {
//		$imported_attaches = get_option( 'clientica_uploaded_attachments' );
//		if ( is_array( $imported_attaches ) ) {
//			$imported_attaches = array_diff( $imported_attaches, array( $id ) );
//			update_option( 'clientica_uploaded_attachments', $imported_attaches );
//		}
//	}

	public function import_caldera_forms() {
		if ( class_exists( 'Caldera_Forms_Forms' ) ) {
			$msg = $result = array();

			try {
				$this->upload_demo_archive( $this->theme_type );
				if ( ! $this->wp_filesystem->exists( get_template_directory() . '/import/' . $this->theme_type . '/caldera2/' ) ) {
					$this->unzip_demo_data( $this->theme_type );
				}
//				$this->upload_ipmort_file( 'caldera2.zip' );
//				$this->unzip_caldera( 'caldera2' );
			} catch ( Exception $e ) {
				echo $e->getMessage();

				return;
			}
			$files = glob( get_template_directory() . '/' . 'import/' . $this->theme_type . '/caldera2/*.json' );
			if ( ! empty( $files ) ) {
				$forms = Caldera_Forms_Forms::get_forms();
				foreach ( $files as $file ) {
					$new_form = null;
					$form     = json_decode( $this->wp_filesystem->get_contents( $file ), true );
					if ( isset( $form['ID'] ) && ! array_key_exists( $form['ID'], $forms ) ) {
						$new_form = Caldera_Forms_Forms::import_form( $form );
						if ( $new_form ) {
							$result[] = $form['name'];
						}
					} else {
						$msg[] = $form['name'];
					}
				}
			} else {
				echo '___<p><b>' . esc_attr__( 'There isn\'t files for import', 'clientica-installer' ) . '</b></p>___';

				return;
			}
			if ( count( $result ) > 0 ) {
				$result = 'Caldera Forms: ' . implode( ', ', $result ) . ' were imported';
			} else {
				$result = '';
			}
			if ( count( $msg ) > 0 ) {
				$msg = '<br>NOTE: Caldera Forms: ' . implode( ', ', $msg ) . ' already exists';
			} else {
				$msg = '';
			}
			echo '___<p><b>' . $result . '</b>' . $msg . '</p>___';
			echo $msg;
		}
	}

	public static function move_attachments_to_begin( $posts ) {
		uasort( $posts, array( 'CLIENTICA_Installer', 'attachments_to_begin' ) );
		return $posts;
	}


	public static function attachments_to_begin( $a, $b ) {
		if ( $a['post_type'] == $b['post_type'] ) {
			return 0;
		}

		return ( $a['post_type'] == 'attachment' ) ? - 1 : 1;
	}

	public function upload_ipmort_file( $file ) {
		if ( file_exists( get_template_directory() . '/import/' . $this->theme_type . '/' . $file ) ) {
			return true;
		}
		$WP_Http = $this->get_wp_http();
		$url     = $this->import_url . $this->theme_type . '/' . $file;
		$headers = $WP_Http->get( $url, array( 'stream' => true, ) );
		if ( is_wp_error( $headers ) ) {
			throw new Exception(
				'___<p class="red"><b>' . sprintf(
					__( 'Remote server did not respond for file:',
						'clientica-installer' ) . ' %1$s',
					$this->theme_type . '/' . $file
				) . '</b></p>___'
			);
//			return '___<p class="red"><b>' . __( 'Remote server did not respond',
//					'clientica-installer' ) . '</b></p>___';
		}
		// make sure the fetch was successful
		if ( $headers['response']['code'] == '200' ) {
			$installer = $this->wp_filesystem->get_contents( $url );
			$this->wp_filesystem->put_contents( get_template_directory() . '/import/' . $this->theme_type . '/' . $file,
				$installer );
		} else {
			throw new Exception(
				'___<p class="red"><b>' . sprintf(
					__( 'Remote server returned error response %1$d %2$s demo: %3$s',
						'clientica-installer' ),
					esc_html( $headers['response']['code'] ),
					get_status_header_desc( $headers['response']['code'] ),
					$this->theme_type
				) . '</b></p>___'
			);
//			return '___<p class="red"><b>' . sprintf( __( 'Remote server returned error response %1$d %2$s',
//					'clientica-installer' ),
//					esc_html( $headers['response']['code'] ),
//					get_status_header_desc( $headers['response']['code'] ) ) . '</b></p>___';
		}

		return true;
	}

	public function upload_demo_archive( $file ) {
		$file = $file . '.zip';
		if ( file_exists( get_template_directory() . '/import/' . $file ) ) {
			return true;
		}
		$WP_Http = $this->get_wp_http();
		$url     = $this->import_url . $file;
		$headers = $WP_Http->get( $url, array( 'stream' => true, 'timeout' => 25 ) );
		if ( is_wp_error( $headers ) ) {
			throw new Exception(
				'___<p class="red"><b>' . sprintf(
					__( 'Remote server did not respond for file:',
						'clientica-installer' ) . ' %1$s',
					$file
				) . '</b></p>___'
			);
		}
		// make sure the fetch was successful
		if ( $headers['response']['code'] == '200' ) {
			$installer = $this->wp_filesystem->get_contents( $url );
			$this->wp_filesystem->put_contents( get_template_directory() . '/import/' . $file,
				$installer );
		} else {
			throw new Exception(
				'___<p class="red"><b>' . sprintf(
					__( 'Remote server returned error response %1$d %2$s demo: %3$s',
						'clientica-installer' ),
					esc_html( $headers['response']['code'] ),
					get_status_header_desc( $headers['response']['code'] ),
					$file
				) . '</b></p>___'
			);
		}

		return true;
	}

//	private function unzip_sliders( $zip_file ) {
//		$from     = get_template_directory() . '/import/' . $this->theme_type . '/sliders/';
//		$to       = str_replace( ABSPATH, $this->wp_filesystem->abspath(), $from ) . $zip_file;
//		$zip_file = $from . $zip_file . '.zip';
//
//		return $this->unzip( $zip_file, $to );
//	}
//
//	private function unzip_caldera( $zip_file ) {
//		$from     = get_template_directory() . '/import/' . $this->theme_type . '/';
//		$to       = str_replace( ABSPATH, $this->wp_filesystem->abspath(), $from ) . $zip_file;
//		$zip_file = $from . $zip_file . '.zip';
//
//		return $this->unzip( $zip_file, $to );
//	}

	public function unzip_demo_data( $zip_file ) {
		$from     = get_template_directory() . '/import/';
		$to       = str_replace( ABSPATH, $this->wp_filesystem->abspath(), $from );
		$zip_file = $from . $zip_file . '.zip';

		return $this->unzip( $zip_file, $to );
	}

	private function unzip( $zip_file, $to ) {
		$unzip = unzip_file( $zip_file, $to );
		if ( is_wp_error( $unzip ) ) {
			throw new Exception( $unzip->get_error_message() );
		}

		return true;
	}

//	public function prepeare_full_demo_data() {
//		try {
//			$this->upload_demo_archive( 'full' );
//			$this->unzip_demo_data( 'full' );
//		} catch ( Exception $e ) {
//			error_log( print_r( $e->getMessage(), true ) );
//		}
//	}

	public function set_attachment_import_id( $postdata, $post ) {
		if ( 'attachment' == $postdata['post_type'] ) {
			$postdata['ID'] = $post['post_id'];
		}

		return $postdata;
	}

	public function set_correct_domain( $post ) {
		$site_url = site_url();
		if ( ! empty( $post['guid'] ) ) {
			$post['guid'] = preg_replace( '~http+s?:\/\/demo[0-9]*.atiframe.com~',
				( $site_url ),
				$post['guid'] );
		}
		if ( ! empty( $post['post_content'] ) ) {
			$post['post_content'] = preg_replace( '~http+s?:\/\/demo[0-9]*.atiframe.com~',
				( $site_url ),
				$post['post_content'] );
		}
		if ( ! empty( $post['postmeta'] ) ) {
			foreach ( $post['postmeta'] as &$meta ) {
				if ( ! empty( $meta['value'] ) ) {
					$meta['value'] = preg_replace( '~http+s?:\\\/\\\/demo[0-9]*.atiframe.com~',
						addslashes( $site_url ),
						$meta['value'] );
				}
			}
			unset( $meta );
		}

		return $post;
	}

	// Update data for composer block post type
	public function import_end() {
		$this->update_composer_block_posts();
		$this->set_element_pack_third_party_default_widgets();
		$this->set_element_pack_core_default_widgets();
		$this->set_demo_imported();
	}

	// Update data for composer block post type
	private function update_composer_block_posts() {
		global $wp_rewrite;
		$str = '/%category%/%postname%/';
		$wp_rewrite->set_permalink_structure( $str );
		flush_rewrite_rules();
	}

	// Set third party defaul widgets
	private function set_element_pack_third_party_default_widgets() {
		if ( is_callable( array( 'Clienticabuilder_Theme_Demo', 'get_element_pack_third_party_default_widget' ) ) ) {
			$option_name = 'element_pack_third_party_widget';
			$options = get_option( $option_name );
			if ( ! $options ) {
				$options = array();
			}
			foreach ( Clienticabuilder_Theme_Demo::get_element_pack_third_party_default_widget() as $widget ) {
				$options[ $widget ] = 'on';
			}
			update_option( $option_name, $options );
		}
	}

	// Set core defaul widgets
	private function set_element_pack_core_default_widgets() {
		if ( is_callable( array( 'Clienticabuilder_Theme_Demo', 'get_element_pack_core_default_widget' ) ) ) {
			$option_name = 'element_pack_active_modules';
			$options = get_option( $option_name );
			if ( ! $options ) {
				$options = array();
			}
			foreach ( Clienticabuilder_Theme_Demo::get_element_pack_core_default_widget() as $widget ) {
				$options[ $widget ] = 'on';
			}
			update_option( $option_name, $options );
		}
	}

	private function set_demo_imported() {
		set_theme_mod( 'demo_imported', true );
	}

	public function set_elementor_active_kit_id() {
		$wrong_slug = 'default-kit-2';
		$slug = 'default-kit';
		$msg = esc_attr__( 'Elementor\'s site settings were set', 'clientica-installer' );
		if ( $old_post = get_page_by_path( $wrong_slug, OBJECT, 'elementor_library' ) ) {
			if( $post = get_page_by_path( $slug, OBJECT, 'elementor_library' ) ) {
				$post->post_name = 'default-kit-tmp';
				wp_update_post( $post );
				$old_post->post_name = $slug;
				wp_update_post( $old_post );
				$post->post_name = $wrong_slug;
				wp_update_post( $post );
				$msg = esc_attr__( 'Elementor\'s site settings were changed', 'clientica-installer' );
			} else {
				$old_post->post_name = $slug;
				wp_update_post( $old_post );
			}
		}
		if ( $post = get_page_by_path( $slug, OBJECT, 'elementor_library' ) ) {
			if ( class_exists( 'Elementor\Core\Kits\Manager' ) ) {
				$option = Elementor\Core\Kits\Manager::OPTION_ACTIVE;
			} else {
				$option = 'elementor_active_kit';
			}
			if ( update_option( $option, $post->ID ) ) {
				echo '___<p><b>' . $msg . '</b></p>___';
			} else {
				echo '___<p><b>' . esc_attr__( 'Saving the same data', 'clientica-installer' ) . '</b></p>___';
			}
		}
	}

	public function clear_cache() {
		if ( class_exists( '\Elementor\Plugin' ) ) {
			\Elementor\Plugin::$instance->files_manager->clear_cache();
			echo '___<p><b>' . esc_attr__( 'CSS generated', 'clientica-installer' ) . '</b></p>___';
		} else {
			echo '___<p><b>' . esc_attr__( 'CSS didn\'t generate', 'clientica-installer' ) . '</b></p>___';
		}
	}

	public function welcome_screen_page() {
		add_theme_page(
			esc_html__( 'Clientica', 'clientica-installer' ),
			esc_html__( 'Clientica', 'clientica-installer' ),
			'read',
			'welcome',
			array( $this, 'welcome_page' ) );
	}

	public function documentation_screen_page() {
		add_theme_page(
			esc_html__( 'Documentation', 'clientica-installer' ),
			esc_html__( 'Documentation', 'clientica-installer' ),
			'read',
			'welcomedocs',
			array( $this, 'welcome_docs' ) );
	}

	public function welcome_page() {

		require CLIENTICA_INSTALLER_PATH . '/pages/class-welcome-page.php';

		$welcome = new CLIENTICA_Welcome( $this );

		$welcome->render();
	}

	public function welcome_docs() {

		require CLIENTICA_INSTALLER_PATH . '/pages/class-welcome-docs.php';

		$welcome = new CLIENTICA_Docs();

		$welcome->render();

	}
}

$modules = array(
	'widget-importer-exporter',
);

if ( ! in_array( 'wordpress-importer.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	$modules[] = 'secretlab-importer';
}

foreach ( $modules as $module ) {
	$module = CLIENTICA_INSTALLER_PATH . '/modules/' . $module . '/' . $module . '.php';
	if ( file_exists( $module ) ) {
		require $module;
	}
}

add_filter( 'wie_includes', function( $inc ){
	return array(
		// Admin only.
		'admin' => array(
			WIE_INC_DIR . '/admin.php',
			WIE_INC_DIR . '/export.php',
			'secl-includes/import.php',
			WIE_INC_DIR . '/mime-types.php',
			WIE_INC_DIR . '/page.php',
			WIE_INC_DIR . '/widgets.php',
		),
	);
});

add_action( 'plugins_loaded', array( 'CLIENTICA_Installer', 'load_plugin_textdomain' ) );

function secl_plugin_activate() {
	set_transient( 'secl_installer_activation_redirect', true, MINUTE_IN_SECONDS );
}

register_activation_hook( __FILE__, 'secl_plugin_activate' );

function secl_plugin_uninstall() {
	delete_option( 'secret_lab_demo_files_imported' );
}

register_uninstall_hook( __FILE__, 'secl_plugin_uninstall' );
if ( is_admin() ) {
	add_action( 'admin_init', function () {
		if ( ! get_transient( 'secl_installer_activation_redirect' ) ) {
			return;
		}

		if ( wp_doing_ajax() ) {
			return;
		}

		delete_transient( 'secl_installer_activation_redirect' );

		if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
			return;
		}

		wp_safe_redirect( admin_url( 'themes.php?page=welcome' ) );

		exit;
	} );
}


?>
