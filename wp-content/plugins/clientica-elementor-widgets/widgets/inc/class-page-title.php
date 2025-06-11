<?php

namespace Clientica_Elementor_Widgets\Widgets\Inc;

use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Clientica_Elementor_Widgets;

class Page_Title extends Widget_Base {

	public function get_name() {
		return 'abew_page_title';
	}

	public function get_title() {
		return esc_html__( 'Page Title', 'cew' );
	}

	public function get_icon() {
		return 'fa fa-header';
	}

	public function get_categories() {
		return array( Clientica_Elementor_Widgets::WIDGET_CATEGORY_NAME );
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'styles',
			array(
				'label' => __( 'Styles', 'cew' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

//		$this->add_group_control(
//			Group_Control_Background::get_type(),
//			[
//				'name'      => 'abew-breadcrumbs',
//				'types'     => [ 'classic', 'gradient' ],
//				'selector'  => '{{WRAPPER}} .abew-breadcrumbs',
//				'separator' => 'after',
//			]
//		);


		$this->add_responsive_control(
			'text_align',
			[
				'label'        => esc_html__( 'Text Alignment', 'cew' ),
				'type'         => Controls_Manager::CHOOSE,
				'prefix_class' => 'elementor%s-align-',
				'default'      => '',
				'options'      => [
					'left'   => [
						'title' => esc_html__( 'Left', 'cew' ),
						'icon'  => 'fas fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'cew' ),
						'icon'  => 'fas fa-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'cew' ),
						'icon'  => 'fas fa-align-right',
					],
				],
				'selector'     => '{{WRAPPER}}',
			]
		);


		$this->add_responsive_control(
			'wrapper_opacity',
			[
				'label'     => esc_html__( 'Opacity (%)', 'cew' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 1,
				],
				'range'     => [
					'px' => [
						'max'  => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'color',
			[
				'label'     => __( 'Color', 'cew' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} h1' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography',
				'label'    => esc_html__( 'Typography', 'cew' ),
				//'scheme'   => Schemes\Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} h1',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'     => 'wrapper_shadow',
				'selector' => '{{WRAPPER}}',
			]
		);

		$this->add_responsive_control(
			'wrapper_z_index',
			[
				'label'       => esc_html__( 'Z-Index', 'cew' ),
				'type'        => Controls_Manager::TEXT,
				'description' => __( 'If you need mouse activity, you can fix z-index.', 'cew' ),
				'default'     => '',
				'dynamic'     => [ 'active' => true ],
				'selectors'   => [
					'{{WRAPPER}}' => 'z-index: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'styles_hover',
			array(
				'label' => __( 'Styles', 'cew' ) . ' ' . esc_html__( 'Hover', 'cew' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'typography_color_hover',
			[
				'label'     => esc_html__( 'Typography', 'cew' ) . ' ' . esc_html__( 'Color', 'cew' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}:hover h1' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography_hover',
				'label'    => esc_html__( 'Typography', 'cew' ),
				//'scheme'   => Schemes\Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}}:hover h1',
			]
		);

		$this->add_responsive_control(
			'wrapper_opacity_hover',
			[
				'label'     => esc_html__( 'Opacity (%)', 'cew' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 1,
				],
				'range'     => [
					'px' => [
						'max'  => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}}:hover' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$atts = $this->get_settings_for_display();
		extract( shortcode_atts( array(
			'el_class' => '',
		), $atts ) );

		$output = '';
		extract( $atts );

		$wrp_classes = apply_filters( 'kc-el-class', $atts );
		$output      .= '<div  ' . $this->get_render_attribute_string( 'content-wrapper' ) . '><h1>';

		$output .= $this->get_document_title();

		$output .= '</h1></div>';

		echo $output;
	}

	protected function _content_template() {
	}

	/**
	 * Returns document title for the current page.
	 *
	 * @since a1.0.1
	 *
	 * @global int $page  Page number of a single post.
	 * @global int $paged Page number of a list of posts.
	 *
	 * @return string Tag with the document title.
	 */
	private function get_document_title() {
		$title = '';

		// If it's a 404 page, use a "Page not found" title.
		if ( is_404() ) {
			$title = esc_html__( 'Page not found', 'cew' );

			// If it's a search, use a dynamic search results title.
		} elseif ( is_search() ) {
			/* translators: %s: Search query. */
			$title = sprintf( esc_html__( 'Search Results for', 'cew' ) . '  &#8220;%s&#8221;', get_search_query() );

			// If on the front page, use the site title.
		} elseif ( is_front_page() ) {
			$title = get_bloginfo( 'name', 'display' );

			// If on a post type archive, use the post type archive title.
		} elseif ( is_post_type_archive() ) {
			if ( function_exists( 'is_woocommerce' ) && ( is_woocommerce() || is_shop() ) ) {
				$title = woocommerce_page_title(false);
			} else {
				$title = post_type_archive_title( '', false );
			}

			// If on a taxonomy archive, use the term title.
		} elseif ( is_tax() ) {
			$title = single_term_title( '', false );

			/*
			* If we're on the blog page that is not the homepage
			* or a single post of any post type, use the post title.
			*/
		} elseif ( is_home() || is_singular() ) {
			$title = single_post_title( '', false );

			// If on a category or tag archive, use the term title.
		} elseif ( is_category() || is_tag() ) {
			$title = single_term_title( '', false );

			// If on an author archive, use the author's display name.
		} elseif ( is_author() && get_queried_object() ) {
			$author         = get_queried_object();
			$title = $author->display_name;

			// If it's a date archive, use the date as the title.
		} elseif ( is_year() ) {
			$title = get_the_date( _x( 'Y', 'yearly archives date format', 'cew' ) );

		} elseif ( is_month() ) {
			$title = get_the_date( _x( 'F Y', 'monthly archives date format', 'cew' ) );

		} elseif ( is_day() ) {
			$title = get_the_date();
		}

		$title = wptexturize( $title );
		$title = convert_chars( $title );
		$title = esc_html( $title );

		return $title;
	}

}

