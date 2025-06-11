<?php

class Clienticabuilder_Theme_Demo {

	const DEFAULT_DEMO = 'demo8';
	const THEME_PREFIX = '';
	const DEFAULT_COMMENT_THEMPLATE = 1; // Comment from view
	const DEFAULT_PAGE_BUILDER = 'Elementor'; // Default Page Builder
	const DEFAULT_SETTINGS = array(
		'google_api_key_opt'         => '',
		'header_widget'              => '589',
		'pick_slider'                => 0,
		'footer_widget'              => '700',
		'scroll_to_top'              => true,
		'pageloader'                 => true,
		'pgl_color_bgr'              => array(
			'color' => '#000000',
			'alpha' => 0.95,
		),
		'pgl_color'                  => array(
			'color' => '#1F2533',
			'alpha' => 1,
		),
		'single_header'              => false,
		'layout'                     => 1,
		'content_background'         => array(
			'color'      => array(
				'color' => '#ffffff',
				'alpha' => 0.95,
			),
			'image'      => '',
			'repeat'     => 'no-repeat',
			'attachment' => 'fixed',
			'position'   => 'center center',
			'size'       => 'inherit',
		),
		'sidebar_layout'             => 1,
		'left_sidebar_widgets'       => '_default_left_sidebar',
		'right_sidebar_widgets'      => '_default_right_sidebar',
		'portfolio_slug'             => 'portfolio',
		'portfolio_arch_title'       => 'Case Studies List',
		'portfolio_arch_desc'        => 'Some description text here',
		'gc1'                        => array(
			'color' => '#34C5A1',
			'alpha' => '1',
		),
		'bgrc'                       => array(
			'color' => '#ffffff',
			'alpha' => '1',
		),
		'ac1'                        => array(
			'color' => '#768188',
			'alpha' => '1',
		),
		'ac1l'                       => array(
			'color' => '#b2b9be',
			'alpha' => '1',
		),
		'blog-columns'               => 1,
		'archive_template'           => 5,
		'single_template'            => 5,
		'comment_template'           => 2,
		'blog-sidebar_layout'        => 4,
		'blog-left_sidebar_widgets'  => 'blog_default_left_sidebar',
		'blog-right_sidebar_widgets' => 'blog_default_right_sidebar',
		'blog-layout'                => 1,
		'blog-header_widget'         => '',
		'blog-footer_widget'         => '',
		'blog-pick_slider'           => 0,
		'post_header'                => false,
		'is_related_posts'           => true,
		'related_posts_title'        => 'Recent posts',
		'show_post_author'           => true,
		'show_post_category'         => true,
		'show_post_tags'             => true,
		'show_post_share'            => false,
		'show_post_date'             => true,
		'show_comments_count'        => true,
		'show_read_more'             => true,
		'read_more_text'             => 'Read more',
		'read_more_related'          => 'Read more',
		'shop-pick_slider'           => 0,
		'shop1'                      => array(
			'color' => '#1F2533',
			'alpha' => '1',
		),
		'product_columns'            => 3,
		'relates_product_products'   => 3,
		'relates_product_columns'    => 3,
		'shop-layout'                => 1,
		'shop-sidebar_layout'        => 3,
		'shop-left_sidebar_widgets'  => 'shop_default_left_sidebar',
		'shop-right_sidebar_widgets' => 'shop_default_right_sidebar',
		'shop-header_widget'         => '',
		'shop-footer_widget'         => '',
		'woocomp'                    => true,
		'wooadd1'                    => true,
		'wooadd2'                    => true,
		'woostate'                   => true,
		'woocity'                    => true,
		'woophone'                   => true,
		'woopostcode'                => true,
		'woocountry'                 => true,

	);


	const DEMO_FONT_SRC = 'https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800&display=swap';
	const DEMO_TYPOGRAPHY = array(
		'body_typography_font_family'      => 'Inter',
		'body_typography_font_weight' => '400',
		'body_font_size'              => '15px',
		'body_color'                  => '#5C5F66',
		'h1_typography_font_family'      => 'Inter',
		'h1_typography_font_weight' => '600',
		'h1_font_size'              => '60px',
		'h1_line_height'              => '70px',
		'h1_text_transforn'              => 'none',
		'h1_color'                  => '#1F2533',
		'h2_typography_font_family'      => 'Inter',
		'h2_typography_font_weight' => '600',
		'h2_font_size'              => '48px',
		'h2_line_height'              => '58px',
		'h2_text_transforn'              => 'none',
		'h2_color'                  => '#1F2533',
		'h3_typography_font_family'      => 'Inter',
		'h3_typography_font_weight' => '600',
		'h3_font_size'              => '36px',
		'h3_line_height'              => '46px',
		'h3_text_transforn'              => 'none',
		'h3_color'                  => '#1F2533',
	);


	// Allowed HTML tags for escaping of texts
	const ALLOWED_HTML = array(
		'a'      => array(
			'href'   => array(),
			'title'  => array(),
			'target' => array(),
		),
		'br'     => array(),
		'em'     => array(),
		'strong' => array(),
		'h1'     => array(),
		'h2'     => array(),
		'h3'     => array(),
		'h4'     => array(),
		'h5'     => array(),
		'h6'     => array(),
		'p'      => array(
			'style' => array(),
		),
		'b'      => array(),
		'i'      => array(),
		'u'      => array(),
		'ol'     => array(),
		'ul'     => array(),
		'li'     => array(),
		'code'   => array(),
		'del'    => array(),
	);

	public static function get_theme_name() {
		return __( 'dentalmed', 'dentalmed' );
	}

	public static function get_demos() {
		return array(
			'demo8' => esc_html__( 'DentalMed - Dentist Clinic WordPress Theme', 'dentalmed' ),
		);
	}

		public static function get_config() {
		return array(
			'args'     => array(),
			'sections' => array(
				'general'      => array(
					'title'  => esc_html__( 'General Setting', 'dentalmed' ),
					'fields' => array(
						array(
							'id'       => 'google_api_key_opt',
							'type'     => 'text',
							'label'    => esc_html__( 'Google API Key', 'dentalmed' ),
							'subtitle' => esc_html__( 'You can get a new API key at https://developers.google.com/maps/documentation/javascript/get-api-key',
								'dentalmed' ),
							'default'  => self::DEFAULT_SETTINGS['google_api_key_opt'],
						),
						array(
							'id'      => 'header_widget',
							'type'    => 'select',
							'label'   => esc_html__( 'Select Header', 'dentalmed' ),
							'choices' => Clienticabuilder_Config::get_composer_block_array( 'header' ),
							'default' => self::DEFAULT_SETTINGS['header_widget'],
						),
						array(
							'id'       => 'pick_slider',
							'type'     => 'select',
							'label'    => esc_html__( 'Select Slider', 'dentalmed' ),
							'subtitle' => esc_html__( 'Select slider for header section', 'dentalmed' ),
							'choices'  => Clienticabuilder_Config::get_sliders_array(
								esc_html__( 'The Theme Support Layer Slider, Smart Slider and Slider Revolution, but couldn\'t find it. Install one of the plug-ins to choose the slider to display in the header.',
									'dentalmed' )
							),
							'default'  => self::DEFAULT_SETTINGS['pick_slider'],
						),
						array(
							'id'      => 'footer_widget',
							'type'    => 'select',
							'label'   => esc_html__( 'Select Footer', 'dentalmed' ),
							'choices' => Clienticabuilder_Config::get_composer_block_array( 'footer' ),

							'default' => self::DEFAULT_SETTINGS['footer_widget'],
						),
						array(
							'id'      => 'scroll_to_top',
							'type'    => 'switch',
							'label'   => esc_html__( 'Display Scroll to Top Button?', 'dentalmed' ),
							'default' => self::DEFAULT_SETTINGS['scroll_to_top'],
						),
						array(
							'id'       => 'pageloader',
							'type'     => 'switch',
							'label'    => esc_html__( 'Display Page Loader', 'dentalmed' ),
							'subtitle' => esc_html__( 'Do you want to show page loader, when website is loading?',
								'dentalmed' ),
							'default'  => self::DEFAULT_SETTINGS['pageloader'],
						),
						array(
							'id'      => 'pgl_color_bgr',
							'type'    => 'color-rgba',
							'label'   => esc_html__( 'Select Background Color for Page Loader', 'dentalmed' ),
							'default' => self::DEFAULT_SETTINGS['pgl_color_bgr'],
						),
						array(
							'id'    => 'pgl_color',
							'type'  => 'color-rgba',
							'label' => esc_html__( 'Select Arrow Color for Page Loader', 'dentalmed' ),

							'default' => self::DEFAULT_SETTINGS['pgl_color'],
						),
					),
				),
				'layout'       => array(
					'title'  => esc_html__( 'Layout', 'dentalmed' ),
					'fields' => array(
						array(
							'id'          => 'single_header',
							'type'        => 'switch',
							'label'       => esc_html__( 'Display Page H1 Heading', 'dentalmed' ),
							'description' => esc_html__( 'Do you want to show H1 heading for pages? Usually we display it through drag&drop builder',
								'dentalmed' ),
							'default'     => self::DEFAULT_SETTINGS['single_header'],
						),
						array(
							'id'      => 'layout',
							'type'    => 'image-select',
							'label'   => esc_html__( 'Select page layout', 'dentalmed' ),
							'options' => self::get_layout_options(),
							'default' => self::DEFAULT_SETTINGS['layout'],
						),
						array(
							'id'          => 'sidebar_layout',
							'type'        => 'image-select',
							'label'       => esc_html__( 'Select sidebar option', 'dentalmed' ),
							'description' => esc_html__( 'Default sidebars is Left Sidebar and Right sidebar',
								'dentalmed' ),
							'options'     => self::get_sidebar_layout_options(),
							'default'     => self::DEFAULT_SETTINGS['sidebar_layout'],
						),
						array(
							'id'      => 'left_sidebar_widgets',
							'type'    => 'select',
							'title'   => esc_html__( 'Widgets for Left Sidebar', 'dentalmed' ),
							'choices' => array( '_default_left_sidebar' => esc_html__( 'Left Sidebar', 'dentalmed' ), ),
							'default' => self::DEFAULT_SETTINGS['left_sidebar_widgets'],
						),
						array(
							'id'      => 'right_sidebar_widgets',
							'type'    => 'select',
							'title'   => esc_html__( 'Widgets for Right Sidebar', 'dentalmed' ),
							'choices' => array( '_default_right_sidebar' => esc_html__( 'Right Sidebar', 'dentalmed' ), ),
							'default' => self::DEFAULT_SETTINGS['right_sidebar_widgets'],
						),
					),
				),
				'portfolio'    => array(
					'title'  => esc_html__( 'Portfolio', 'dentalmed' ),
					'fields' => array(
						array(
							'id'          => 'portfolio_slug',
							'type'        => 'text',
							'label'       => esc_html__( 'URL Slug For Portfolio Post Type', 'dentalmed' ),
							'description' => wp_kses( esc_html__( 'After you change it, go to', 'dentalmed' ) . ' ' . '<a href="' . admin_url('options-permalink.php') . '" target="_blank">/wp-admin/options-permalink.php</a>' . ' ' . esc_html__( 'and click "Save Changes" button to activate the URL slug', 'dentalmed' ),
								self::ALLOWED_HTML ),
							'default'     => self::DEFAULT_SETTINGS['portfolio_slug'],
						),
						array(
							'id'          => 'portfolio_arch_title',
							'type'        => 'text',
							'label'       => esc_html__( 'Portfolio Page H1 Heading', 'dentalmed' ),
							'description' => esc_html__( 'Heading for Portfolio Archive page', 'dentalmed' ),
							'default'     => self::DEFAULT_SETTINGS['portfolio_arch_title'],
						),
						array(
							'id'          => 'portfolio_arch_desc',
							'type'        => 'textarea',
							'label'       => esc_html__( 'Description Text Under H1 Heading', 'dentalmed' ),
							'description' => esc_html__( 'Allowed tags: a, img, br, em, strong, h1, h2, h3, h4, h5, h6, p, b, i, u, ol, ul, li, code, del',
								'dentalmed' ),
							'default'     => self::DEFAULT_SETTINGS['portfolio_arch_desc'],
						),
					),
				),
				'color_scheme' => array(
					'title'  => esc_html__( 'Color Scheme', 'dentalmed' ),
					'fields' => array(
						array(
							'id'    => 'general_colors_section',
							'type'  => 'info',
							'label' => esc_html__( 'General Colors', 'dentalmed' ),
						),
						array(
							'id'      => 'gc1',
							'type'    => 'color-rgba',
							'label'   => esc_html__( 'Major Color', 'dentalmed' ),
							'default' => self::DEFAULT_SETTINGS['gc1'],
						),
						array(
							'id'      => 'bgrc',
							'type'    => 'color-rgba',
							'label'   => esc_html__( 'Background Color', 'dentalmed' ),
							'default' => self::DEFAULT_SETTINGS['bgrc'],
						),
						array(
							'id'    => 'additional_colors_section',
							'type'  => 'info',
							'label' => esc_html__( 'Additional Colors', 'dentalmed' ),
						),
						array(
							'id'      => 'ac1',
							'type'    => 'color-rgba',
							'label'   => esc_html__( 'Gray Color', 'dentalmed' ),
							'default' => self::DEFAULT_SETTINGS['ac1'],
						),
						array(
							'id'      => 'ac1l',
							'type'    => 'color-rgba',
							'label'   => esc_html__( 'Gray Color Light', 'dentalmed' ),
							'default' => self::DEFAULT_SETTINGS['ac1l'],
						),
					),
				),
				'translate'    => array(
					'title'  => esc_html__( 'Translate', 'dentalmed' ),
					'fields' => array(
						array(
							'id'    => '404_info',
							'type'  => 'info',
							'label' => esc_html__( 'Text for 404 error page', 'dentalmed' ),
						),
						array(
							'id'      => '404_title',
							'type'    => 'text',
							'label'   => esc_html__( '404 Page Heading', 'dentalmed' ),
							'default' => esc_html__( 'Oops, something`s goes wrong!', 'dentalmed' ),
						),
						array(
							'id'      => '404_descr',
							'type'    => 'text',
							'label'   => esc_html__( '404 Page Description', 'dentalmed' ),
							'default' => esc_html__( 'It looks like nothing was found at this location. Maybe try a search?',
								'dentalmed' ),
						),

					),
				),
				'blog'         => array(
					'title'  => esc_html__( 'Blog', 'dentalmed' ),
					'fields' => array(
						array(
							'id'          => 'blog-columns',
							'type'        => 'select',
							'label'       => esc_html__( 'Blog Columns Option', 'dentalmed' ),
							'description' => esc_html__( 'If you are turn on sidebar or sidebars, you cannot use 2-3 columns layout', 'dentalmed' ),
							//Must provide key => value pairs for radio options
							'choices'     => array(
								'1' => esc_html__( '1 Column', 'dentalmed' ),
							),
							'default'     => self::DEFAULT_SETTINGS['blog-columns'],
						),
						array(
							'id'      => 'archive_template',
							'type'    => 'image-select',
							'label'   => esc_html__( 'Select archive template', 'dentalmed' ),
							'options' => self::get_archive_templates(),
							'default' => self::DEFAULT_SETTINGS['archive_template'],
						),
						array(
							'id'      => 'single_template',
							'type'    => 'image-select',
							'label'   => esc_html__( 'Select single post template', 'dentalmed' ),
							'options' => array(
								'5' => array(
									'alt' => esc_attr__( 'Post', 'dentalmed' ),
									'img' => get_template_directory_uri() . '/images/framework/post5.gif',
								),

							),
							'default' => self::DEFAULT_SETTINGS['single_template'],
						),
						array(
							'id'      => 'comment_template',
							'type'    => 'image-select',
							'label'   => esc_html__( 'Select comment template', 'dentalmed' ),
							'options' => array(
								'2' => array(
									'alt' => esc_html__( 'Post', 'dentalmed' ),
									'img' => get_template_directory_uri() . '/images/framework/comm2.gif',
								),
							),
							'default' => self::DEFAULT_SETTINGS['comment_template'],
						),

						array(
							'id'          => 'blog-sidebar_layout',
							'type'        => 'image-select',
							'label'       => esc_html__( 'Choose sidebar option for blog', 'dentalmed' ),
							'description' => esc_html__( 'The option work for POST post type', 'dentalmed' ),
							'options'     => self::get_sidebar_layout_options(),
							'default'     => self::DEFAULT_SETTINGS['blog-sidebar_layout'],
						),

						array(
							'id'      => 'blog-left_sidebar_widgets',
							'type'    => 'select',
							'label'   => esc_html__( 'Widgets for Blog Left Sidebar', 'dentalmed' ),
							'choices' => array( 'blog_default_left_sidebar' => esc_html__( 'Left Blog Sidebar', 'dentalmed' ), ),
							'default' => self::DEFAULT_SETTINGS['blog-left_sidebar_widgets'],
						),
						array(
							'id'      => 'blog-right_sidebar_widgets',
							'type'    => 'select',
							'label'   => esc_html__( 'Widgets for Blog Right Sidebar', 'dentalmed' ),
							'choices' => array( 'blog_default_right_sidebar' => esc_html__( 'Right Blog Sidebar', 'dentalmed' ), ),
							'default' => self::DEFAULT_SETTINGS['blog-right_sidebar_widgets'],
						),
						array(
							'id'          => 'blog-layout',
							'type'        => 'image-select',
							'label'       => esc_html__( 'Select page layout', 'dentalmed' ),
							'description' => esc_html__( 'The option work for Post post type', 'dentalmed' ),
							'options'     => self::get_layout_options(),
							'default'     => self::DEFAULT_SETTINGS['blog-layout'],

						),
						array(
							'id'      => 'blog-header_widget',
							'type'    => 'select',
							'label'   => esc_html__( 'Header Menu for Blog', 'dentalmed' ),
							'options' => Clienticabuilder_Config::get_composer_block_array( 'header' ),
							'default' => self::DEFAULT_SETTINGS['blog-header_widget'],
						),
						array(
							'id'      => 'blog-footer_widget',
							'type'    => 'select',
							'label'   => esc_html__( 'Footer for Blog', 'dentalmed' ),
							'options' => Clienticabuilder_Config::get_composer_block_array( 'footer' ),
							'default' => self::DEFAULT_SETTINGS['blog-footer_widget'],
						),
						array(
							'id'    => 'blog_info',
							'type'  => 'info',
							'style' => 'info',
							'label' => esc_html__( 'Blog Heading Box', 'dentalmed' ),
						),
						array(
							'id'          => 'blog-pick_slider',
							'type'        => 'select',
							'label'       => esc_html__( 'Select Slider for Blog', 'dentalmed' ),
							'description' => esc_html__( 'The option work for Post post type', 'dentalmed' ),
							'choices'     => Clienticabuilder_Config::get_sliders_array(
								esc_html__( 'The Theme Support Layer Slider, Smart Slider and Slider Revolution, but couldn\'t find it. Install one of the plug-ins to choose the slider to display in the header.',
									'dentalmed' )
							),
							'default'     => self::DEFAULT_SETTINGS['blog-pick_slider'],
						),
						array(
							'id'          => 'post_header',
							'type'        => 'switch',
							'label'       => esc_html__( 'Display Post H1 Heading', 'dentalmed' ),
							'description' => esc_html__( 'Do you want to show H1 heading for post? Usually we display it through drag&drop builder',
								'dentalmed' ),
							'default'     => self::DEFAULT_SETTINGS['post_header'],
						),
						array(
							'id'          => 'is_related_posts',
							'type'        => 'switch',
							'label'       => esc_html__( 'Related Posts for Single Post View', 'dentalmed' ),
							'description' => esc_html__( 'Press On to template choice appear', 'dentalmed' ),
							'default'     => self::DEFAULT_SETTINGS['is_related_posts'],
						),
						array(
							'id'          => 'related_posts_title',
							'type'        => 'text',
							'label'       => esc_html__( 'Related Posts Title', 'dentalmed' ),
							'description' => esc_html__( 'Set Title for Related Posts section', 'dentalmed' ),
							'default'     => 'Related Posts',
						),
						array(
							'id'      => 'show_post_author',
							'type'    => 'switch',
							'label'   => esc_html__( 'Show Post Author ', 'dentalmed' ),
							'default' => self::DEFAULT_SETTINGS['show_post_author'],
						),
						array(
							'id'      => 'show_post_category',
							'type'    => 'switch',
							'label'   => esc_html__( 'Show Post Category ', 'dentalmed' ),
							'default' => self::DEFAULT_SETTINGS['show_post_category'],
						),
						array(
							'id'      => 'show_post_tags',
							'type'    => 'switch',
							'label'   => esc_html__( 'Show Post Tags ', 'dentalmed' ),
							'default' => self::DEFAULT_SETTINGS['show_post_tags'],
						),
						array(
							'id'      => 'show_post_share',
							'type'    => 'switch',
							'label'   => esc_html__( 'Show Post Share ', 'dentalmed' ),
							'default' => self::DEFAULT_SETTINGS['show_post_share'],
						),
						array(
							'id'      => 'show_post_date',
							'type'    => 'switch',
							'label'   => esc_html__( 'Show Post Date ', 'dentalmed' ),
							'default' => self::DEFAULT_SETTINGS['show_post_date'],
						),
						array(
							'id'      => 'show_comments_count',
							'type'    => 'switch',
							'label'   => esc_html__( 'Show Post Comments count ', 'dentalmed' ),
							'default' => self::DEFAULT_SETTINGS['show_comments_count'],
						),
						array(
							'id'      => 'show_read_more',
							'type'    => 'switch',
							'label'   => esc_html__( 'Show Read More Link', 'dentalmed' ),
							'default' => self::DEFAULT_SETTINGS['show_read_more'],
						),
						array(
							'id'          => 'read_more_text',
							'type'        => 'text',
							'label'       => esc_html__( 'Read More Link Text', 'dentalmed' ),
							'description' => esc_html__( 'Set Text for Read More Link', 'dentalmed' ),
							'required'    => array( 'show_read_more', '=', true ),
							'default'     => self::DEFAULT_SETTINGS['read_more_text'],
						),
						array(
							'id'          => 'read_more_related',
							'type'        => 'text',
							'label'       => esc_html__( 'Read More Link Text On Related Posts', 'dentalmed' ),
							'description' => esc_html__( 'Set Text for Read More Link', 'dentalmed' ),
							'default'     => self::DEFAULT_SETTINGS['read_more_related'],
						),
					),
				),
				'shop'         => array(
					'title'    => esc_html__( 'Shop', 'dentalmed' ),
					'fields'   => array(
						array(
							'id'          => 'shop-pick_slider',
							'type'        => 'select',
							'label'       => esc_html__( 'Select Slider for Shop', 'dentalmed' ),
							'description' => esc_html__( 'Select slider for header section', 'dentalmed' ),
							'options'     => Clienticabuilder_Config::get_sliders_array(
								esc_html__( 'The Theme Support Layer Slider, Smart Slider and Slider Revolution, but couldn\'t find it. Install one of the plug-ins to choose the slider to display in the header.',
									'dentalmed' )
							),
							'default'     => self::DEFAULT_SETTINGS['shop-pick_slider'],
						),
						array(
							'id'      => 'shop1',
							'type'    => 'color-rgba',
							'label'   => esc_html__( 'Price Color', 'dentalmed' ),
							'default' => self::DEFAULT_SETTINGS['shop1'],
						),

						array(
							'id'          => 'product_columns',
							'type'        => 'text',
							'label'       => esc_html__( 'Columns of Products', 'dentalmed' ),
							'description' => esc_html__( 'For catalog and categories pages', 'dentalmed' ),
							'default'     => self::DEFAULT_SETTINGS['product_columns'],
						),
						array(
							'id'          => 'relates_product_products',
							'type'        => 'text',
							'label'       => esc_html__( 'Related Products to show', 'dentalmed' ),
							'description' => esc_html__( 'For product page', 'dentalmed' ),
							'default'     => self::DEFAULT_SETTINGS['relates_product_products'],
						),
						array(
							'id'          => 'relates_product_columns',
							'type'        => 'text',
							'label'       => esc_html__( 'Columns of related products', 'dentalmed' ),
							'description' => esc_html__( 'For product page', 'dentalmed' ),
							'default'     => self::DEFAULT_SETTINGS['relates_product_columns'],
						),

						array(
							'id'          => 'shop-layout',
							'type'        => 'image-select',
							'label'       => esc_html__( 'Select shop page layout', 'dentalmed' ),
							'description' => esc_html__( 'The option work for slug /shop/', 'dentalmed' ),
							'options'     => self::get_layout_options(),
							'default'     => self::DEFAULT_SETTINGS['shop-layout'],
						),
						array(
							'id'      => 'shop-sidebar_layout',
							'type'    => 'image-select',
							'label'   => esc_html__( 'Select sidebar option for shop', 'dentalmed' ),
							'options' => self::get_sidebar_layout_options(),
							'default' => self::DEFAULT_SETTINGS['shop-sidebar_layout'],
						),
						array(
							'id'      => 'shop-left_sidebar_widgets',
							'type'    => 'select',
							'label'   => esc_html__( 'Widgets for Shop Left Sidebar', 'dentalmed' ),
							'choices' => array( 'shop_default_left_sidebar' => esc_html__( 'Left Shop Sidebar', 'dentalmed' ), ),
							'default' => self::DEFAULT_SETTINGS['shop-left_sidebar_widgets'],
						),
						array(
							'id'      => 'shop-right_sidebar_widgets',
							'type'    => 'select',
							'label'   => esc_html__( 'Widgets for Shop Right Sidebar', 'dentalmed' ),
							'choices' => array( 'shop_default_right_sidebar' => esc_html__( 'Right Shop Sidebar', 'dentalmed' ), ),
							'default' => self::DEFAULT_SETTINGS['shop-right_sidebar_widgets'],
						),

						array(
							'id'      => 'shop-header_widget',
							'type'    => 'select',
							'label'   => esc_html__( 'Select Header for Shop', 'dentalmed' ),
							'choices' => Clienticabuilder_Config::get_composer_block_array( 'header' ),
							'default' => self::DEFAULT_SETTINGS['shop-header_widget'],
						),


						array(
							'id'      => 'shop-footer_widget',
							'type'    => 'select',
							'label'   => esc_html__( 'Select Footer for Shop', 'dentalmed' ),
							'choices' => Clienticabuilder_Config::get_composer_block_array( 'footer' ),
							'default' => self::DEFAULT_SETTINGS['shop-footer_widget'],
						),
						array(
							'id'      => 'woocomp',
							'type'    => 'switch',
							'label'   => esc_html__( 'Enable company field in checkout', 'dentalmed' ),
							'default' => self::DEFAULT_SETTINGS['woocomp'],
						),
						array(
							'id'      => 'wooadd1',
							'type'    => 'switch',
							'label'   => esc_html__( 'Enable address 1 field in checkout', 'dentalmed' ),
							'default' => self::DEFAULT_SETTINGS['wooadd1'],
						),
						array(
							'id'      => 'wooadd2',
							'type'    => 'switch',
							'label'   => esc_html__( 'Enable address 2 field in checkout', 'dentalmed' ),
							'default' => self::DEFAULT_SETTINGS['wooadd2'],
						),
						array(
							'id'      => 'woostate',
							'type'    => 'switch',
							'label'   => esc_html__( 'Enable state field in checkout', 'dentalmed' ),
							'default' => self::DEFAULT_SETTINGS['woostate'],
						),
						array(
							'id'      => 'woocity',
							'type'    => 'switch',
							'label'   => esc_html__( 'Enable city field in checkout', 'dentalmed' ),
							'default' => self::DEFAULT_SETTINGS['woocity'],
						),
						array(
							'id'      => 'woophone',
							'type'    => 'switch',
							'label'   => esc_html__( 'Enable phone field in checkout', 'dentalmed' ),
							'default' => self::DEFAULT_SETTINGS['woophone'],
						),
						array(
							'id'      => 'woopostcode',
							'type'    => 'switch',
							'label'   => esc_html__( 'Enable postcode field in checkout', 'dentalmed' ),
							'default' => self::DEFAULT_SETTINGS['woopostcode'],
						),
						array(
							'id'      => 'woocountry',
							'type'    => 'switch',
							'label'   => esc_html__( 'Enable country field in checkout', 'dentalmed' ),
							'default' => self::DEFAULT_SETTINGS['woocountry'],
						),

					),
					'priority' => 150,
				),

				'custom_js' => array(
					'title'    => esc_html__( 'Additional JS', 'dentalmed' ),
					'fields'   => array(
						array(
							'id'        => 'head_js',
							'type'      => 'code-editor',
							'code_type' => 'javascript',
							'label'     => esc_html__( 'Head JS', 'dentalmed' ),
						),
						array(
							'id'        => 'footer_js',
							'type'      => 'code-editor',
							'code_type' => 'javascript',
							'label'     => esc_html__( 'Footer JS', 'dentalmed' ),
						),

					),
					'priority' => 250,
				),
			),
		);
	}

	public static function get_widgets() {
		return array(
			array(
				'name'          => esc_html__( 'Left Sidebar', 'dentalmed' ),
				'id'            => '_default_left_sidebar',
				'description'   => esc_html__( 'Works through all website pages.', 'dentalmed' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			),
			array(
				'name'          => esc_html__( 'Right Sidebar', 'dentalmed' ),
				'id'            => '_default_right_sidebar',
				'description'   => esc_html__( 'Works through all website pages.', 'dentalmed' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			),
			array(
				'name'          => esc_html__( 'Left Blog Sidebar', 'dentalmed' ),
				'id'            => 'blog_default_left_sidebar',
				'description'   => esc_html__( 'Works through blog website pages.', 'dentalmed' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			),
			array(
				'name'          => esc_html__( 'Right Blog Sidebar', 'dentalmed' ),
				'id'            => 'blog_default_right_sidebar',
				'description'   => esc_html__( 'Works through blog website pages.', 'dentalmed' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			),
			array(
				'name'          => esc_html__( 'Left Shop Sidebar', 'dentalmed' ),
				'id'            => 'shop_default_left_sidebar',
				'description'   => esc_html__( 'Works through shop website pages.', 'dentalmed' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			),
			array(
				'name'          => esc_html__( 'Right Shop Sidebar', 'dentalmed' ),
				'id'            => 'shop_default_right_sidebar',
				'description'   => esc_html__( 'Works through shop website pages.', 'dentalmed' ),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget'  => '</aside>',
				'before_title'  => '<h3 class="widget-title">',
				'after_title'   => '</h3>',
			),
		);
	}

	public static function get_notices() {
		return array(
			'was_installed'   => esc_html__( 'clientica themes data was installed successfully', 'dentalmed' ),
			'sliders_message' => esc_html__( 'You use more that one slider on the site, so we advise to you leave only one of them. You can deactiveta others at:',
				'dentalmed' ),
			'sliders_plugins' => esc_html__( 'plugins', 'dentalmed' ),
		);
	}

	public static function get_welcomes() {
		return array(
			'welcome'       => esc_html__( 'clientica', 'dentalmed' ),
			'documentation' => esc_html__( 'Documentation', 'dentalmed' ),
		);
	}

	public static function get_layout_settings() {
		return array(
			'scroll_to_top' => esc_html__( 'Scroll to top', 'dentalmed' ),
		);
	}

	public static function get_archive_templates() {
		$uri = get_template_directory_uri();

		return array(

			'5' => array(
				'alt' => esc_attr__( 'Post', 'dentalmed' ),
				'img' => get_template_directory_uri() . '/images/framework/post5.gif',
			),

		);
	}

	public static function get_layout_options() {
		$uri = get_template_directory_uri();

		return array(
			'1' => array(
				'alt' => esc_attr__( 'Full width layout', 'dentalmed' ),
				'img' => $uri . '/images/framework/full.gif',
			),
			'2' => array(
				'alt' => esc_attr__( 'Boxed layout, maximum resolution - 1170 px', 'dentalmed' ),
				'img' => $uri . '/images/framework/boxed.gif',
			),
		);
	}

	public static function get_sidebar_layout_options() {
		$uri = get_template_directory_uri();

		return array(
			'1' => array(
				'alt' => esc_attr__( 'Without sidebar', 'dentalmed' ),
				'img' => $uri . '/images/framework/nosidebar.gif',
			),
			'2' => array(
				'alt' => esc_attr__( '2 sidebars', 'dentalmed' ),
				'img' => $uri . '/images/framework/2sidebars.gif',
			),
			'3' => array(
				'alt' => esc_attr__( 'Left sidebar', 'dentalmed' ),
				'img' => $uri . '/images/framework/leftsidebar.gif',
			),
			'4' => array(
				'alt' => esc_attr__( 'Right sidebar', 'dentalmed' ),
				'img' => $uri . '/images/framework/rightsidebar.gif',
			),
		);
	}


	public static function get_plugins() {
		return array(
			array(
				'name'     => esc_html__( 'Elementor', 'dentalmed' ),
				'slug'     => 'elementor',
				'required' => true,
			),
			array(
				'name'         => esc_html__( 'Caldera Forms', 'dentalmed' ),
				'slug'         => 'caldera-forms',
				'required'     => true,
			),
			array(
				'name'     => esc_html__( 'WooCommerce', 'dentalmed' ),
				'slug'     => 'woocommerce',
				'required' => false,
			),
			array(
				'name'     => esc_html__( 'Clientica Integrator', 'dentalmed' ),
				'slug'     => 'clientica-integrator',
				'source'   => 'https://git.clientica.org/clientica-integrator.zip',
				'required' => true,
			),

			array(
				'name'     => esc_html__( 'Clientica Elementor Widgets', 'dentalmed' ),
				'slug'     => 'clientica-elementor-widgets',
				'source'   => 'https://git.clientica.org/clientica-elementor-widgets.zip',
				'required' => true,
			),

			array(
				'name'     => esc_html__( 'Element Pack', 'dentalmed' ),
				'slug'     => 'bdthemes-element-pack',
				'source'   => 'https://git.clientica.org/bdthemes-element-pack.zip',
				'required' => true,
			),
			array(
				'name'         => esc_html__( 'BdThemes Portfolio', 'dentalmed' ),
				'slug'         => 'bdthemes-portfolio',
				'source'       => 'https://git.clientica.org/bdthemes-portfolio.zip',
				'external_url' => 'https://bdthemes.com/',
				'required'     => true,
			),
			array(
				'name'         => esc_html__( 'BdThemes Testimonials', 'dentalmed' ),
				'slug'         => 'bdthemes-testimonials',
				'source'       => 'https://git.clientica.org/bdthemes-testimonials.zip',
				'external_url' => 'https://bdthemes.com/',
				'required'     => true,
			),
		);
	}

	public static function get_plugins_first() {
		return array(
			array(
				'name'     => esc_html__( 'Clientica Installer', 'dentalmed' ),
				'slug'     => 'clientica-installer',
				'source'   => 'https://git.clientica.org/clientica-installer.zip',
				'required' => true,
			)
		);
	}

	public static function the_header() {
		echo '<div class="header_alt">
                        <div class="topbar">
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-3">
                                    <a href="' . esc_url( home_url( '/' ) ) . '" class="logo">' . esc_html( self::get_theme_name() ) . '</a>
                                    </div>
                                    <div class="col-md-9">
                                    <ul class="topnav" id="myTopnav">';
		wp_nav_menu( array(
			'theme_location'  => 'clienticabuilder_top_menu',
			'container'       => false,
			'container_id'    => 'myTopnav',
			'container_class' => 'topnav',
			'menu_class'      => 'topnav',
			'fallback_cb'     => '__return_empty_string',
			'depth'           => 3,
			'items_wrap'      => '%3$s',
		) );

		echo '<a href="javascript:void(0);"  class="icon">&#9776;</a>
                                    </ul></div>
                                </div>
                            </div>
                        </div></div>';
	}

	public static function the_footer() {
		echo '<div class="footer_alt"><div class="container">&copy; ', esc_html( date( 'Y' ) ), '</div></div>';
	}


	public static function get_element_pack_third_party_default_widget() {
		return array('portfolio-gallery', 'portfolio-carousel','vertical-menu',
		'svg-image', 'flip-box', 'advanced-progress-bar');
	}
}