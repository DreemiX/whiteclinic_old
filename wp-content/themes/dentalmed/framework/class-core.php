<?php

define( 'CLIENTICABUILDER_FRAMEWORK_PATH', get_template_directory() . '/framework' );
define( 'CLIENTICABUILDER_FRAMEWORK_URL', get_template_directory_uri() . '/framework' );

require CLIENTICABUILDER_FRAMEWORK_PATH . '/inc/class-config.php';
require CLIENTICABUILDER_FRAMEWORK_PATH . '/functions/class-helpers.php'; // General Functions of the theme. Under the hood.
require CLIENTICABUILDER_FRAMEWORK_PATH . '/functions/class-blog.php'; // Functions and layouts for blog
require CLIENTICABUILDER_FRAMEWORK_PATH . '/functions/class-header.php'; // Functions for header section
require CLIENTICABUILDER_FRAMEWORK_PATH . '/functions/class-layout.php'; // General Functions of the theme. Under the hood.
require CLIENTICABUILDER_FRAMEWORK_PATH . '/functions/class-footer.php'; // Functions for footer section
require CLIENTICABUILDER_FRAMEWORK_PATH . '/class-theme-demo.php'; // Theme demos
require CLIENTICABUILDER_FRAMEWORK_PATH . '/inc/adapters/class-base-adapter.php';

class Clienticabuilder_Core {

	private static $instance = null;

	/**
	 * @var Clienticabuilder_Config
	 */
	public $config;

	private $installer;

	public $blog;

	public $helpers;

	public $header;

	public $layout;

	public $woocommerce;

	public $footer;

	private $plugins;

	private static $adapter;

	public static $page_options = Clienticabuilder_Theme_Demo::DEFAULT_SETTINGS;

	private static $welcome_menu_slug = 'welcome';

	public function __construct() {

		$this->set_adapter();

		$this->config  = new Clienticabuilder_Config();
		$this->helpers = new Clienticabuilder_Helpers();
		if ( class_exists( 'WooCommerce' ) ) {
			require CLIENTICABUILDER_FRAMEWORK_PATH . '/functions/class-woocommerce.php'; // Functions for woocommerce and a cart in menu
			$this->woocommerce = new Clienticabuilder_Woocommerce();
		}

		// Load all core theme classes
		add_action( 'template_redirect', array( $this, 'init' ) );

		/**
		 * Clientica only works in WordPress 4.7 or later.
		 */
		if ( version_compare( $GLOBALS['wp_version'], '4.7', '<' ) ) {
			require get_template_directory() . '/inc/class-back-compat.php';

			return;
		}

		/** Admin only actions **/
		if ( is_admin() ) {

			if ( wp_doing_ajax() ) {
				add_action( 'customize_save_after', array( $this->helpers, 'customize_save_after' ) );
			}



			$this->welcome_screen();

			delete_transient( '_wc_activation_redirect' );

			update_option( 'revslider-valid-notice', 'false' );
			add_filter( 'wpcf7_autop_or_not', '__return_false' );

			add_filter( 'yikes_easy_mailchimp_extender_use_custom_db', array( $this, 'del_red_mailchamp' ) );

			$this->set_plugins();

			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_assets' ) );

			add_action( 'after_switch_theme', array( $this, 'after_switch_theme' ) );

		} else {

			add_action( 'comment_form', array( $this, 'comment_checkbox' ) );

			add_action( 'pre_comment_on_post', array( $this, 'comment_check' ) );

			add_filter( 'wp_list_categories', array( $this, 'remove_brackets_from_counts' ), 11 );

			add_filter( 'get_archives_link', array( $this, 'remove_brackets_from_counts' ), 11 );
		}

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 4 );

		add_action( 'wp_enqueue_scripts', array( $this, 'head_actions' ), 11 );

		add_action( 'widgets_init', array( $this, 'widgets_init' ) );

		// This theme uses its own gallery styles.
		add_filter( 'use_default_gallery_style', '__return_false' );
	}

	public function init() {
		$this->helpers->set_globals();
		if ( 'blog' == Clienticabuilder_Core::$page_options['page_type'] ) {
			$this->blog = new Clienticabuilder_Blog();
		}
		$this->header = new Clienticabuilder_Header();
		$this->layout = new Clienticabuilder_Layout();
		$this->footer = new Clienticabuilder_Footer();
	}

	/**
	 * gets the instance via lazy initialization (created on first usage)
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	// CSS and JS files
	public function enqueue_scripts() {
		if ( Clienticabuilder_Helpers::is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		//css
		$uri = get_template_directory_uri();
		wp_enqueue_style( 'clienticabuilder-ownstyles', $uri . '/style.css', array(), false, 'all' );


		self::$adapter->enqueue_scripts();

		wp_enqueue_script(
			'clienticabuilder-mainjs',
			$uri . '/js/main.min.js',
			array(
				'jquery',
			),
			false,
			true
		);
	}

	// CSS and JS files
	public function head_actions() {
		self::$adapter->header_scripts();
		$this->dequeue_dashicon();

		$this->header->preloader_styles();
		$this->header->enqueue_scripts();

	}

	/**
	 * @return mixed
	 */
	public static function get_adapter() {
		return self::$adapter;
	}

	/**
	 * @param mixed $adapter
	 */
	private function set_adapter() {
		if ( class_exists( '\Elementor\Plugin' ) ) {
			require CLIENTICABUILDER_FRAMEWORK_PATH . '/inc/adapters/class-elementor-adapter.php';
			self::$adapter = Clienticabuilder_Elementor_Adapter::get_instance();
		} else {
			require CLIENTICABUILDER_FRAMEWORK_PATH . '/inc/adapters/class-standard-adapter.php';
			self::$adapter = Clienticabuilder_Standard_Adapter::get_instance();
		}
	}

	/**
	 * Register 7 widget areas.
	 */
	public function widgets_init() {
		foreach ( Clienticabuilder_Theme_Demo::get_widgets() as $widget ) {
			register_sidebar(
				$widget
			);
		}

	}

	public function del_red_mailchamp( $custom_db ) {
		update_option( 'yikes_mailchimp_activation_redirect', 'false' );

		return $custom_db;
	}

	public function save_installed() {
		if ( ! empty( $_GET['clienticabuilderfrom'] ) && 'welcome' === $_GET['clienticabuilderfrom'] ) {
			echo '<div class="updated notice my-acf-notice is-dismissible"><p>' . esc_html__( 'Clientica themes data was installed successfully', 'dentalmed' ) . '</p></div>';
		}
	}

	public function comment_checkbox() {
		echo '<p class="comment-form-ch"><input id="clienticabuilder-ch" name="clienticabuilder_ch" type="checkbox" /></p>';
	}

	public function comment_check( $comment_data ) {
		if ( isset( $_POST['clienticabuilder_ch'] ) ) {
			wp_die();
		} else {
			return $comment_data;
		}
	}

	public function installer() {
		return $this->installer;
	}

	public function blog() {
		return $this->blog;
	}

	public function helpers() {
		return $this->helpers;
	}

	public function header() {
		return $this->header;
	}

	public function layout() {
		return $this->layout;
	}

	public function woocommerce() {
		return $this->woocommerce;
	}

	public function footer() {
		return $this->footer;
	}

	public function set_plugins() {
		require CLIENTICABUILDER_FRAMEWORK_PATH . '/inc/class-plugins-list.php';
		$this->plugins = new Clienticabuilder_Plugins();
	}

	public function plugins() {
		return $this->plugins;
	}

	private function welcome_screen() {
		// Welcome Page section
		if ( class_exists( 'Clientica_Installer' ) ) {
			$this->installer = new Clientica_Installer();
			add_action( 'admin_menu', array( $this->installer, 'welcome_screen_page' ) );
			add_action( 'admin_menu', array( $this->installer, 'documentation_screen_page' ) );
		} else {
			add_action( 'admin_menu', array( $this, 'welcome_screen_page' ) );
		}
	}

	public function welcome_screen_page() {

		$uri = get_template_directory_uri();
		wp_enqueue_style( 'clienticabuilder-welcome',
			esc_url( $uri ) . '/framework/css/welcome.css',
			array(),
			'3.03' );
		
		$wel = esc_html__( 'Clientica', 'dentalmed' );
		add_theme_page(
			$wel,
			$wel,
			'read',
			self::$welcome_menu_slug,
			array( $this, 'welcome_page' ) );
	}

	public function welcome_page() {

		require CLIENTICABUILDER_FRAMEWORK_PATH . '/inc/class-welcome-page.php';

		$welcome = new Clienticabuilder_Welcome();

		$welcome->render();
	}

	public function after_switch_theme() {
		$mods = get_theme_mods();
		if ( is_array($mods) && count($mods) < 3 ) {
			wp_redirect( '/wp-admin/themes.php?page=' . self::$welcome_menu_slug );
		}
	}

	public function sliders_message() {
		$sliders = array(
			'LS_Sliders',
			'RevSliderSlider',
			'SmartSlider3',
		);
		$c       = 0;

		foreach ( $sliders as $slider ) {
			if ( class_exists( $slider ) ) {
				$c ++;
			}
		}

		if ( 1 < $c ) {
			echo '<div id="message" class="notice notice-warning is-dismissible">';
			echo '<p>' . esc_html__( 'You use more that one slider on the site, so we advise to you leave only one of them. You can deactiveta others at:',
					'dentalmed' ) . '</p>';
			echo '<a class="meta_btn" href="' . get_admin_url( null, 'plugins.php' ) . '">' . esc_html__( 'plugins', 'dentalmed' ) . '</a>';
			echo '
    </div>';
		} else {
			echo '';
		}
	}

	public function save_live_editor_revision( $addition_check, $id ) {
		wp_save_post_revision( $id );

		return $addition_check;
	}

	public function admin_enqueue_assets() {
		wp_register_style( 'clienticabuilder-admin', CLIENTICABUILDER_FRAMEWORK_URL . '/css/admin.css' );
		wp_enqueue_style( 'clienticabuilder-admin' );
	}

	private function dequeue_dashicon() {
		if ( current_user_can( 'update_core' ) ) {
			return;
		}
		wp_deregister_style( 'dashicons' );
	}

	public static function welcome_menu_slug(  ) {
		return self::$welcome_menu_slug;
	}

	public function remove_brackets_from_counts( $variable ) {
		$variable = str_replace( '(', '', $variable );
		$variable = str_replace( ')', '', $variable );

		return $variable;
	}
}
