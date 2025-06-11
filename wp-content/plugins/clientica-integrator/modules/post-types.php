<?php

//add_action( 'init', 'cbi_register_post_types_to_kc', 99 );
//add_action( 'init', 'cbi_register_post_types', 98 );
//
//function cbi_register_post_types_to_kc() {
//	global $kc;
//	if ( isset( $kc ) ) {
//		$kc->add_content_type( array(
//			'portfolio',
//			'composer_widget',
//			'product',
//			'modal_window',
//		) );
//	}
//}
//
//function cbi_register_post_types() {
//	if ( isset ( Clienticabuilder_Core::$theme_options['portfolio_slug'] ) ) {
//		$pslug = Clienticabuilder_Core::$theme_options['portfolio_slug'];
//	} else {
//		$pslug = 'portfolio';
//	}
//	register_post_type( 'portfolio',
//		array(
//			'label'               => 'Portfolio',
//			'labels'              => array(
//				'name'               => esc_html__( 'Portfolio', 'clientica-integrator' ),
//				'singular_name'      => esc_html__( 'Portfolio', 'clientica-integrator' ),
//				'add_new'            => esc_html__( 'Add New', 'clientica-integrator' ),
//				'add_new_item'       => esc_html__( 'Add New Portfolio Item', 'clientica-integrator' ),
//				'edit_item'          => esc_html__( 'Edit Portfolio Item', 'clientica-integrator' ),
//				'new_item'           => esc_html__( 'New Portfolio Item', 'clientica-integrator' ),
//				'view_item'          => esc_html__( 'View Portfolio Item', 'clientica-integrator' ),
//				'search_items'       => esc_html__( 'Search Portfolio Item', 'clientica-integrator' ),
//				'not_found'          => esc_html__( 'No portfolio found.', 'clientica-integrator' ),
//				'not_found_in_trash' => esc_html__( 'No portfolio found in Trash.', 'clientica-integrator' ),
//				'parent_item_colon'  => '',
//				'menu_name'          => esc_html__( 'Portfolio', 'clientica-integrator' ),
//			),
//			'description'         => '',
//			'public'              => true,
//			'publicly_queryable'  => true,
//			'exclude_from_search' => false,
//			'show_ui'             => true,
//			'show_in_menu'        => true,
//			'show_in_admin_bar'   => true,
//			'show_in_nav_menus'   => true,
//			'show_in_rest'        => false,
//			'rest_base'           => true,
//			'menu_position'       => 27,
//			'menu_icon'           => 'dashicons-portfolio',
//			'hierarchical'        => true,
//			'supports'            => array( 'title', 'editor', 'thumbnail', 'comments', 'revisions', 'excerpt' ),
//			// 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
//			'taxonomies'          => array( 'post_tag', 'category' ),
//			'has_archive'         => $pslug,
//			'rewrite'             => array( 'slug' => $pslug, 'with_front' => true ),
//			'query_var'           => true,
//		)
//	);
//}

class CBI_Portfolio_Post_Type {

	private $type = 'portfolio';
	private $slug = 'portfolio';
	private $category_slug = 'portfolio';
	private $name;

	public function __construct() {
		// Register the post type
		$this->name = __( 'Portfolio', 'clientica-integrator' );
		if ( isset ( Clienticabuilder_Core::$page_options['portfolio_slug'] ) ) {
			$this->slug = Clienticabuilder_Core::$page_options['portfolio_slug'];
		}

		add_action( 'init', array( $this, 'init' ), 98 );
	}

	public function init() {
		$this->register_post_types();
		$this->register_taxonomy();
		$this->register_taxonomy_tag();
	}

	private function register_taxonomy() { // Second part of taxonomy name

		$labels = array(
			'name'              => sprintf( __( '%s Categories', 'clientica-integrator' ), $this->name ),
			'menu_name'         => sprintf( __( '%s Categories', 'clientica-integrator' ), $this->name ),
			'singular_name'     => sprintf( __( '%s Category', 'clientica-integrator' ), $this->name ),
			'search_items'      => sprintf( __( 'Search %s Categories', 'clientica-integrator' ), $this->name ),
			'all_items'         => sprintf( __( 'All %s Categories', 'clientica-integrator' ), $this->name ),
			'parent_item'       => sprintf( __( 'Parent %s Category', 'clientica-integrator' ), $this->name ),
			'parent_item_colon' => sprintf( __( 'Parent %s Category:', 'clientica-integrator' ), $this->name ),
			'new_item_name'     => sprintf( __( 'New %s Category Name', 'clientica-integrator' ), $this->name ),
			'add_new_item'      => sprintf( __( 'Add New %s Category', 'clientica-integrator' ), $this->name ),
			'edit_item'         => sprintf( __( 'Edit %s Category', 'clientica-integrator' ), $this->name ),
			'update_item'       => sprintf( __( 'Update %s Category', 'clientica-integrator' ), $this->name ),
		);
		$args   = array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => $this->slug . '-' . $this->category_slug ),
		);
		register_taxonomy( $this->type . '-' . $this->category_slug, array( $this->type ), $args );
	}

	private function register_taxonomy_tag() { // Second part of taxonomy name

		$labels = array(
			'name'              => sprintf( __( '%s Tags', 'clientica-integrator' ), $this->name ),
			'menu_name'         => sprintf( __( '%s Tags', 'clientica-integrator' ), $this->name ),
			'singular_name'     => sprintf( __( '%s Tag', 'clientica-integrator' ), $this->name ),
			'popular_items'     => sprintf( __( '%s Popular Tags', 'clientica-integrator' ), $this->name ),
			'search_items'      => sprintf( __( '%s Search Tag', 'clientica-integrator' ), $this->name ),
			'all_items'         => sprintf( __( '%s All Tags', 'clientica-integrator' ), $this->name ),
			'parent_item'       => null,
			'parent_item_colon' => null,
			'new_item_name'     => sprintf( __( '%s New Tag Name', 'clientica-integrator' ), $this->name ),
			'add_new_item'      => sprintf( __( '%s Add New Tag', 'clientica-integrator' ), $this->name ),
			'edit_item'         => sprintf( __( '%s Edit Tag', 'clientica-integrator' ), $this->name ),
			'update_item'       => sprintf( __( '%s Update Tag', 'clientica-integrator' ), $this->name ),
		);
		$args   = array(
			'labels'                => $labels,
			'hierarchical'          => false,
			'update_count_callback' => '_update_post_term_count',
			'show_ui'               => true,
			'query_var'             => true,
			'rewrite'               => array( 'slug' => $this->slug . '-tag' ),
		);
		register_taxonomy( $this->type . '_tag', array( $this->type ), $args );
	}

	function register_post_types() {
		register_post_type( 'portfolio',
			array(
				'label'               => 'Portfolio',
				'labels'              => array(
					'name'               => esc_html__( 'Portfolio', 'clientica-integrator' ),
					'singular_name'      => esc_html__( 'Portfolio', 'clientica-integrator' ),
					'add_new'            => esc_html__( 'Add New', 'clientica-integrator' ),
					'add_new_item'       => esc_html__( 'Add New Portfolio Item', 'clientica-integrator' ),
					'edit_item'          => esc_html__( 'Edit Portfolio Item', 'clientica-integrator' ),
					'new_item'           => esc_html__( 'New Portfolio Item', 'clientica-integrator' ),
					'view_item'          => esc_html__( 'View Portfolio Item', 'clientica-integrator' ),
					'search_items'       => esc_html__( 'Search Portfolio Item', 'clientica-integrator' ),
					'not_found'          => esc_html__( 'No portfolio found.', 'clientica-integrator' ),
					'not_found_in_trash' => esc_html__( 'No portfolio found in Trash.', 'clientica-integrator' ),
					'parent_item_colon'  => '',
					'menu_name'          => esc_html__( 'Portfolio', 'clientica-integrator' ),
				),
				'description'         => '',
				'public'              => true,
				'publicly_queryable'  => true,
				'exclude_from_search' => false,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'show_in_rest'        => false,
				'rest_base'           => true,
				'menu_position'       => 27,
				'menu_icon'           => 'dashicons-portfolio',
				'hierarchical'        => true,
				'supports'            => array( 'title', 'editor', 'thumbnail', 'comments', 'revisions', 'excerpt' ),
				// 'title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments','revisions','page-attributes','post-formats'
				'taxonomies'          => array( 'post_tag', 'category', $this->slug . '-' . $this->category_slug ),
				'has_archive'         => $this->slug,
				'rewrite'             => array( 'slug' => $this->slug, 'with_front' => true ),
				'query_var'           => true,
			)
		);
	}
}

new CBI_Portfolio_Post_Type();