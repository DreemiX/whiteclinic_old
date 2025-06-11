<?php

namespace Clientica_Elementor_Widgets\Widgets\Inc;

use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Clientica_Elementor_Widgets;

class Breadcrumbs extends Widget_Base {

	public function get_name() {
		return 'abew_breadcrumb';
	}

	public function get_title() {
		return esc_html__( 'Breadcrumbs', 'cew' );
	}

	public function get_icon() {
		return 'fa fa-angle-right';
	}

	public function get_categories() {
		return array( Clientica_Elementor_Widgets::WIDGET_CATEGORY_NAME );
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function _register_controls() {

//		$par = array(
//			esc_html__( 'General', 'cew' ) => array(
//				'homename'      =>
//					array(
//						'label'       => esc_html__( 'Homepage Title', 'cew' ),
//						'admin_label' => true,
//						'type'        => Controls_Manager::TEXT,
//						'default'     => esc_html__( 'Home', 'cew' )
//					),
//				'divider'       =>
//					array(
//						'label'       => esc_html__( 'Divider', 'cew' ),
//						'admin_label' => true,
//						'type'        => Controls_Manager::TEXT,
//						'default'     => '/'
//					),
//				'el_class'      =>
//					array(
//						'label'       => esc_html__( 'Extra Class Name', 'cew' ),
//						'type'        => Controls_Manager::TEXT,
//						'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your CSS file.', 'cew' ),
//						'admin_label' => true,
//						'default'     => ''
//					),
//				'current_title' =>
//					array(
//						'label'        => esc_html__( 'Show the current page title?', 'cew' ),
//						'type'         => Controls_Manager::SWITCHER,
//						'label_on'     => __( 'Show', 'cew' ),
//						'label_off'    => __( 'Hide', 'cew' ),
//						'return_value' => 'yes',
//						'default'      => 'yes',
//					),
//				'home_show'     =>
//					array(
//						'label'        => esc_html__( 'Show breadcrumbs on the Home page?', 'cew' ),
//						'type'         => Controls_Manager::SWITCHER,
//						'label_on'     => __( 'Show', 'cew' ),
//						'label_off'    => __( 'Hide', 'cew' ),
//						'return_value' => 'yes',
//						'default'      => 'no',
//					),
//			),
//			esc_html__( 'Styling', 'cew' ) => array(
//				array(
//					'tab' => \Elementor\Controls_Manager::TAB_STYLE,
//				),
//			),
//		);
//
//		Clientica_Elementor_Widgets::get_instance()->adapt_controls( $par, $this );

		$this->start_controls_section(
			Controls_Manager::TAB_CONTENT,
			array(
				'label' => __( 'General', 'cew' ),
			)
		);

		$this->add_control(
			'homename',
			array(
				'label'   => esc_html__( 'Homepage Title', 'cew' ),
				'type'    => Controls_Manager::TEXT,
				'default' => esc_html__( 'Home', 'cew' ),
			)
		);

		$this->add_control(
			'divider',
			array(
				'label'   => esc_html__( 'Divider', 'cew' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '/',
			)
		);

		$this->add_control(
			'current_title',
			array(
				'label'        => esc_html__( 'Show the current page title?', 'cew' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'cew' ),
				'label_off'    => __( 'Hide', 'cew' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			)
		);

		$this->add_control(
			'home_show',
			array(
				'label'        => esc_html__( 'Show breadcrumbs on the Home page?', 'cew' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Show', 'cew' ),
				'label_off'    => __( 'Hide', 'cew' ),
				'return_value' => 'yes',
				'default'      => 'no',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'styles',
			array(
				'label' => __( 'Styles', 'cew' ),
				'tab' => Controls_Manager::TAB_STYLE,
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
					'left'    => [
						'title' => esc_html__( 'Left', 'cew' ),
						'icon'  => 'fas fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'cew' ),
						'icon'  => 'fas fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'cew' ),
						'icon'  => 'fas fa-align-right',
					],
				],
				'selector'  => '{{WRAPPER}}',
			]
		);
//		$this->add_responsive_control(
//			'float',
//			[
//				'label'        => esc_html__( 'Float', 'cew' ),
//				'type'         => Controls_Manager::CHOOSE,
//				'prefix_class' => 'elementor%s-align-',
//				'default'      => 'none',
//				'options'      => [
//					'left'    => [
//						'title' => esc_html__( 'Left', 'cew' ),
//						'icon'  => 'fas fa-align-left',
//					],
//					'none' => [
//						'title' => esc_html__( 'None', 'cew' ),
//					],
//					'right' => [
//						'title' => esc_html__( 'Right', 'cew' ),
//						'icon'  => 'fas fa-align-right',
//					],
//				],
//				'selector'  => array( '{{WRAPPER}}' => 'float: {{VALUE}}' ),
//			]
//		);


		$this->add_responsive_control(
			'wrapper_opacity',
			[
				'label'   => esc_html__( 'Opacity (%)', 'cew' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'opacity: {{SIZE}};',
				],
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
				'label'     => esc_html__( 'Z-Index', 'cew' ),
				'type'      => Controls_Manager::TEXT,
				'description'   => __( 'If you need mouse activity, you can fix z-index.', 'cew' ),
				'default'       => '',
				'dynamic'       => [ 'active' => true ],
				'selectors' => [
					'{{WRAPPER}}' => 'z-index: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'color',
			[
				'label'     => __('Color', 'cew'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography',
				'label'    => esc_html__( 'Typography', 'cew' ),
				//'scheme'   => Schemes\Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}}',
			]
		);

		$this->add_control(
			'home_color',
			[
				'label'     => esc_html__( 'Home', 'cew' ) . ' ' . esc_html__('Color', 'cew'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .abew-breadcrumb-home' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'home_typography',
				'label'    => esc_html__( 'Home', 'cew' ) . ' ' . esc_html__( 'Typography', 'cew' ),
				//'scheme'   => Schemes\Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .abew-breadcrumb-home',
			]
		);

		$this->add_control(
			'links_color',
			[
				'label'     => esc_html__( 'Links', 'cew' ) . ' ' . esc_html__('Color', 'cew'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'links_typography',
				'label'    => esc_html__( 'Links', 'cew' ) . ' ' . esc_html__( 'Typography', 'cew' ),
				//'scheme'   => Schemes\Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} a',
			]
		);

		$this->add_control(
			'page_title_color',
			[
				'label'     => esc_html__( 'Page Title', 'cew' ) . ' ' . esc_html__('Color', 'cew'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .abew-breadcrumb' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'page_title_typography',
				'label'    => esc_html__( 'Page Title', 'cew' ) . ' ' . esc_html__( 'Typography', 'cew' ),
				'selector' => '{{WRAPPER}} .abew-breadcrumb',
			]
		);

		$this->add_control(
			'divider_color',
			[
				'label'     => esc_html__( 'Divider', 'cew' ) . ' ' . esc_html__('Color', 'cew'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .divider' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'divider_typography',
				'label'    => esc_html__( 'Divider', 'cew' ) . ' ' . esc_html__( 'Typography', 'cew' ),
				//'scheme'   => Schemes\Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .divider',
			]
		);

		$this->add_responsive_control(
			'divider_margin',
			[
				'label'      => esc_html__( 'Divider', 'cew' ) . ' ' . esc_html__('Margin', 'cew'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'{{WRAPPER}} .divider' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'divider_padding',
			[
				'label'      => esc_html__( 'Divider', 'cew' ) . ' ' . esc_html__('Padding', 'cew'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', '%'],
				'selectors'  => [
					'{{WRAPPER}} .divider' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);


		$this->end_controls_section();

		$this->start_controls_section(
			'styles_hover',
			array(
				'label' => __( 'Styles', 'cew' ) . ' ' . esc_html__( 'Hover', 'cew' ),
				'tab' => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'wrapper_opacity_hover',
			[
				'label'   => esc_html__( 'Opacity (%)', 'cew' ),
				'type'    => Controls_Manager::SLIDER,
				'default' => [
					'size' => 1,
				],
				'range' => [
					'px' => [
						'max' => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}}:hover' => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_control(
			'typography_color_hover',
			[
				'label'     => esc_html__( 'Typography', 'cew' ) . ' ' . esc_html__('Color', 'cew'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'typography_hover',
				'label'    => esc_html__( 'Typography', 'cew' ),
				//'scheme'   => Schemes\Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}}:hover',
			]
		);

		$this->add_control(
			'home_color_hover',
			[
				'label'     => esc_html__( 'Home', 'cew' ) . ' ' . esc_html__('Color', 'cew'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .abew-breadcrumb-home:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'home_typography_hover',
				'label'    => esc_html__( 'Home', 'cew' ) . ' ' . esc_html__( 'Typography', 'cew' ),
				//'scheme'   => Schemes\Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .abew-breadcrumb-home:hover',
			]
		);

		$this->add_control(
			'links_typography_color_hover',
			[
				'label'     => esc_html__( 'Links', 'cew' ) . ' ' . esc_html__('Color', 'cew'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a:hover, {{WRAPPER}} a:focus, {{WRAPPER}} a:active' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'links_typography_hover',
				'label'    => esc_html__( 'Links', 'cew' ) . ' ' . esc_html__( 'Typography', 'cew' ),
				//'scheme'   => Schemes\Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} a:hover',
			]
		);

		$this->add_control(
			'page_title_color_hover',
			[
				'label'     => esc_html__( 'Page Title', 'cew' ) . ' ' . esc_html__('Color', 'cew'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .abew-breadcrumb:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'page_title_typography_hover',
				'label'    => esc_html__( 'Page Title', 'cew' ) . ' ' . esc_html__( 'Typography', 'cew' ),
				'selector' => '{{WRAPPER}} .abew-breadcrumb:hover',
			]
		);

		$this->add_control(
			'divider_color_hover',
			[
				'label'     => esc_html__( 'Divider', 'cew' ) . ' ' . esc_html__('Color', 'cew'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .divider:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'     => 'divider_typography_hover',
				'label'    => esc_html__( 'Divider', 'cew' ) . ' ' . esc_html__( 'Typography', 'cew' ),
				//'scheme'   => Schemes\Typography::TYPOGRAPHY_4,
				'selector' => '{{WRAPPER}} .divider:hover',
			]
		);


		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		extract( shortcode_atts( array(
			'homename'      => esc_html__( 'Home', 'cew' ),
			'divider'       => '/',
//			'el_class'      => '',
			'current_title' => 'yes',
			'home_show'     => 'no',
		),
			$settings ) );
//		$wrp_classes = apply_filters( 'kc-el-class', $atts );
		$this->add_render_attribute( 'content-wrapper', 'class', 'abew-breadcrumbs' );
//		$this->add_render_attribute( 'content-wrapper', 'class', $el_class );
		$divider     = ! empty( $divider ) ? '<span class="divider">' . $divider . '</span>' : '';
		$output      = '<div ' . $this->get_render_attribute_string( 'content-wrapper' ) . ' >';
		$before      = '<span class="abew-breadcrumb">';
		$after       = '</span>';
		$linkBefore  = $linkAfter = $pagedHtml = '';

		if ( function_exists( 'woocommerce_breadcrumb' ) && ( is_woocommerce() || is_shop() ) ) {
			$args = array(
				'delimiter' => $divider,
				'before'    => $before,
				'after'     => $after,
				'wrap_before' => '',
				'wrap_after'  => '',
			);
			ob_start();
			woocommerce_breadcrumb( $args );
			$output .= ob_get_clean();
		} else {
			$content      = $before;
			$category     = esc_html__( '%1$s Archive for %2$s', 'cew' );
			$search       = esc_html__( '%1$sSearch results for: %2$s', 'cew' );
			$tag          = esc_html__( '%1$sPosts tagged %2$s', 'cew' );
			$title_author = esc_html__( '%1$sView all posts by %2$s', 'cew' ); // text for an author page
			$title_404    = esc_html__( 'Error 404', 'cew' ); // text for the 404 page

			global $post, $paged, $page;
			$homeLink = home_url( '/' );
			$linkAttr = ' rel="v:url" property="v:title"';

			$link_home = $linkBefore . '<a class="abew-breadcrumb-home" ' . $linkAttr . ' href="%1$s">%2$s</a>' . wp_kses_post( $divider ) . $linkAfter;

			$link = $linkBefore . '<a' . $linkAttr . ' href="%1$s">' . '%2$s</a>' . wp_kses_post( $divider ) . $linkAfter;

			if ( is_front_page() ) {
				if ( 'yes' == $home_show ) {
					$output .= $linkBefore . '<a href="' . esc_url( $homeLink ) . '">' . $homename . '</a>' . $linkAfter;
				}
			} else {
				$output .= sprintf( $link_home, esc_url( $homeLink ), $homename ); // WPCS: XSS OK.

				if ( get_query_var( 'paged' ) ) {
					$pagedHtml .= '<span class="paged"> ';
					$pagedHtml .= '(';
//				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) {
//					$pagedHtml .= '(';
//				}
					/* translators: %s: current page number. */
					$pagedHtml .= sprintf( esc_html__( 'Page %s', 'cew' ), absint( max( $paged, $page ) ) );
//				if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) {
//					$pagedHtml .= ')';
//				}
					$pagedHtml .= ')';
					$pagedHtml .= '</span>';
				}

				if ( is_home() ) {
					$output .= $content . esc_html( get_the_title( get_option( 'page_for_posts', true ) ) ) . $after; // WPCS: XSS OK.
				} elseif ( is_category() ) {
					$thisCat = get_category( get_query_var( 'cat' ), false );
					if ( $thisCat->parent != 0 ) {
						$cats   = get_category_parents( $thisCat->parent, true, false );
						$cats   = str_replace( '<a', $linkBefore . '<a' . $linkAttr, $cats );
						$cats   = str_replace( '</a>', '</a>' . wp_kses_post( $divider ) . $linkAfter, $cats );
						$output .= $cats; // WPCS: XSS OK.
					}
					$output .= $content . sprintf( $category, '<span class="archive-text">', '</span>', $after ) . get_the_archive_title() . $pagedHtml . $after;
				} elseif ( is_search() ) {
					$output .= $content . sprintf( $search, '<span class="search-text">', '</span>' . get_search_query() ) . $pagedHtml . $after; // WPCS: XSS OK.

				} elseif ( is_day() ) {
					$output .= sprintf( $link, esc_url( get_year_link( get_the_time( __( 'Y', 'cew' ) ) ) ), esc_html( get_the_time( __( 'Y', 'cew' ) ) ) ); // WPCS: XSS OK.
					$output .= sprintf( $link, esc_url( get_month_link( get_the_time( __( 'Y', 'cew' ) ), false ) ), esc_html( get_the_time( __( 'F', 'cew' ) ) ) ); // WPCS: XSS OK.
					$output .= $content . esc_html( get_the_time( __( 'd', 'cew' ) ) ) . $pagedHtml . $after; // WPCS: XSS OK.
				} elseif ( is_month() ) {
					$output .= sprintf( $link, esc_url( get_year_link( get_the_time( __( 'Y', 'cew' ) ) ) ), esc_html( get_the_time( __( 'Y', 'cew' ) ) ) ); // WPCS: XSS OK.
					$output .= $content . esc_html( get_the_time( __( 'F', 'cew' ) ) ) . $pagedHtml . $after; // WPCS: XSS OK.
				} elseif ( is_year() ) {
					$output .= $content . esc_html( get_the_time( __( 'Y', 'cew' ) ) ) . $pagedHtml . $after; // WPCS: XSS OK.
				} elseif ( is_single() && ! is_attachment() ) {
					if ( get_post_type() != 'post' ) {
						$post_type = get_post_type_object( get_post_type() );
						$post_link = get_post_type_archive_link( $post_type->name );
						$output    .= sprintf( $link, esc_url( $post_link ), esc_html( $post_type->labels->singular_name ) ); // WPCS: XSS OK.
						$output    .= $content . esc_html( get_the_title() ) . $pagedHtml . $after; // WPCS: XSS OK
					} else {
						$cat = get_the_category();
						$cat = $cat[0];

						if ( ! empty( $cat ) ) {
							$cats   = get_category_parents( $cat, true, '' );
							$cats   = preg_replace( "#^(.+)$#", "$1", $cats );
							$cats   = str_replace( '<a', $linkBefore . '<a' . $linkAttr, $cats );
							$cats   = str_replace( '</a>', '</a>' . wp_kses_post( $divider ) . $linkAfter, $cats );
							$output .= $cats;  // WPCS: XSS OK.
						}
						$output .= $content . esc_html( get_the_title() ) . $pagedHtml . $after;  // WPCS: XSS OK.
					}
				} elseif ( ! is_single() && ! is_page() && get_post_type() != 'post' && ! is_404() ) {
					$post_type = get_post_type_object( get_post_type() );
					$output    .= isset( $post_type->labels->singular_name ) ? $content . esc_html( $post_type->labels->singular_name ) . $pagedHtml . $after : '';  // WPCS: XSS OK.
					if ( null === $post_type && $kc_action = filter_input( INPUT_POST, 'kc_action' ) ) {
						if ( 'live-editor' === $kc_action ) {
							if ( isset( $_POST['ID'] ) ) {
								$output .= $content . esc_attr( get_the_title( $_POST['ID'] ) ) . $pagedHtml . $after;
							} else {
								$output .= $content . esc_html__( 'Live Editor', 'cew' ) . $pagedHtml . $after;
							}
						}
					}
				} elseif ( is_attachment() ) {
					$parent = get_post( $post->post_parent );
					$cat    = get_the_category( $parent->ID );
					if ( isset( $cat[0] ) ) {
						$cat = $cat[0];
					}
					if ( $cat ) {
						$cats   = get_category_parents( $cat, true );
						$cats   = str_replace( '<a', $linkBefore . '<a' . $linkAttr, $cats );
						$cats   = str_replace( '</a>', '</a>' . wp_kses_post( $divider ) . $linkAfter, $cats );
						$output .= $cats; // WPCS: XSS OK.
					}
					$output .= sprintf( $link, esc_url( get_permalink( $parent ) ), esc_html( $parent->post_title ) );// WPCS: XSS OK.
					$output .= $content . esc_html( get_the_title() ) . $pagedHtml . $after;// WPCS: XSS OK.
				} elseif ( is_page() && ! $post->post_parent ) {
					$output .= $content . esc_html( get_the_title() ) . $pagedHtml . $after; // WPCS: XSS O
				} elseif ( is_page() && $post->post_parent ) {
					$parent_id   = $post->post_parent;
					$breadcrumbs = array();
					while ( $parent_id ) {
						$page_child    = get_post( $parent_id );
						$breadcrumbs[] = sprintf( $link, esc_url( get_permalink( $page_child->ID ) ), esc_html( get_the_title( $page_child->ID ) ) );
						$parent_id     = $page_child->post_parent;
					}
					$breadcrumbs = array_reverse( $breadcrumbs );
					for ( $i = 0; $i < count( $breadcrumbs ); $i ++ ) {
						$output .= $breadcrumbs[ $i ];// WPCS: XSS OK.
					}
					$output .= $content . esc_html( get_the_title() ) . $pagedHtml . $after; // WPCS: XSS OK.
				} elseif ( is_tag() ) {
					$output .= $content . sprintf( $tag, '<span class="tag-text">', '</span>' ) . get_the_archive_title() . $pagedHtml . $after; // WPCS: XSS OK
				} elseif ( is_author() ) {
					global $author;
					$userdata = get_userdata( $author );
					$output   .= $content . sprintf( $title_author, '<span class="author-text">', '</span>' . $userdata->display_name ) . $pagedHtml . $after; // WPCS: XSS
				} elseif ( is_404() ) {
					$output .= $content . $title_404 . $after; // WPCS: XSS OK.
				}
			}
		}

		$output .= '</div>';

		echo $output;
	}

	protected function _content_template() {
	}

}
