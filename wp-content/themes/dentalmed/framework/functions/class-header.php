<?php
/*
The file contain functions for headers sections.
*/

class Clienticabuilder_Header {

	public function __construct() {
		add_action( 'wp_head', array( $this, 'pingback_header' ) );
	}

	// Page preloader
	public function preloader_styles() {
		if ( Clienticabuilder_Core::$page_options['pageloader'] ) {
			$color = Clienticabuilder_Config::get_color_val( Clienticabuilder_Core::$page_options['pgl_color_bgr'] );
			if ( empty( $color ) ) {
				$style = '';
			} else {
				$style = 'background-color:' . $color . ';';
			}
			/* Pageloader*/
			wp_add_inline_style(
				'clienticabuilder-ownstyles',
				'.loaderbgr {position:fixed; top:0;left:0;right:0;bottom:0; z-index:999999;width: 100%;height: 100%;' . $style . '}
                .circle-chart__background {stroke: rgba(200, 200, 200, 0.3)}
                .circle-chart {position:absolute; top:calc(50% - 40px); left:calc(50% - 40px); }
                .circle-chart__circle {transform-origin: center;animation: ani 1.5s linear infinite reverse; }
                @keyframes ani {
                    0% {transform: rotate(0deg);}
                    100% {transform: rotate(360deg);}
                }'
			);
		}
	}

	// header layout
	public static function header_layout() {
		try {
			self::set_header_widget();
		} catch ( Exception $e ) {
			Clienticabuilder_Core::get_adapter()->the_header();
			self::the_header();
		}
	}

	private static function set_header_widget() {

		// Switcher between classic title and built-in blog header
		if ( class_exists( 'custom_post_widget' ) ) {
			if ( Clienticabuilder_Core::$page_options['header_widget'] !== - 1 ) {
				$position = get_metadata( 'post', Clienticabuilder_Core::$page_options['header_widget'],
					'header_block_type',
					true );
				if ( 'slmm-below' === $position ) {
					self::set_customized_slider();
				}

				?>
                <div class="clientica_menuline <?php echo ( empty( $position ) ) ? 'slmm-above' : $position; ?> default">
					<?php
					self::header_widget();
					?>
                </div>
				<?php

				if ( $position !== 'slmm-below' ) {
					self::set_customized_slider();
				}
			}

		} else {
			throw new Exception( esc_html__( 'There is no correct widget.', 'dentalmed' ) );
		}
	}

	private static function header_widget() {
		the_widget( 'custom_post_widget',
			array(
				'custom_post_id'        => Clienticabuilder_Core::$page_options['header_widget'],
				'apply_content_filters' => true,
			),
			array( 'before_widget' => '<div class="header_widget %s">' ) );
	}

	public static function set_header_sidebar_layout() {
		if ( Clienticabuilder_Core::$page_options['sidebar_layout'] !== 1 ) {
			echo '<div class="sbcenter">';
		}
		if ( 2 === Clienticabuilder_Core::$page_options['sidebar_layout'] || 3 === Clienticabuilder_Core::$page_options['sidebar_layout'] ) {
			echo '<div class="col-lg-3 col-md-12 col-sm-12 widget-area left_sb animated slideInLeft">';
			echo '<div>';
			try {
				dynamic_sidebar( Clienticabuilder_Core::$page_options['left_sidebar_widgets'] );
			} catch ( Exception $e ) {
				esc_html_e( 'Check widgets settings in this sidebar', 'dentalmed' );
			}
			echo '</div>';
			echo '</div>';
		}
		if ( 1 === Clienticabuilder_Core::$page_options['sidebar_layout'] ) {
			echo '<div class="main">';
		} elseif ( 2 === Clienticabuilder_Core::$page_options['sidebar_layout'] ) {
			echo '<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 cont-box-area main  blogsidebarspage">';
		} elseif ( 3 === Clienticabuilder_Core::$page_options['sidebar_layout'] ) {
			echo '<div class="col-lg-9 col-md-12 col-sm-12 col-xs-12 cont-box-area main blogsidebarpage lsb">';
		} elseif ( 4 === Clienticabuilder_Core::$page_options['sidebar_layout'] ) {
			echo '<div class="col-lg-9 col-md-12 col-sm-12 col-xs-12 cont-box-area main blogsidebarpage rsb">';
		}

	}

	// Pageloader
	public static function pageloader() {
		if ( Clienticabuilder_Core::$page_options['pageloader'] ) {
			$color = Clienticabuilder_Config::get_color_val( Clienticabuilder_Core::$page_options['pgl_color'] );
			echo '<div class="loaderbgr">
                <svg class="circle-chart" viewbox="0 0 80 80" width="80" height="80" xmlns="http://www.w3.org/2000/svg">
                  <circle class="circle-chart__background" stroke-width="5" fill="none" cx="40" cy="40" r="30" />
                  <circle class="circle-chart__circle" stroke="' . esc_attr( $color ) . '" stroke-width="5" stroke-dasharray="34,234" stroke-linecap="round" fill="none" cx="40" cy="40" r="30" />
                </svg>
            </div>';
		}
	}

	// boxed layout
	public static function set_boxed_background() {
		$add_class = get_body_class();

		if ( 2 === Clienticabuilder_Core::$page_options['layout'] ) {

			if ( ! empty( Clienticabuilder_Core::$page_options['boxed_background']['color'] ) ) {
				$box_bg_color = 'background-color : ' . Clienticabuilder_Config::get_color_val( Clienticabuilder_Core::$page_options['boxed_background']['color'] ) . ';';
			} else {
				$box_bg_color = '';
			}

			if ( ! empty( Clienticabuilder_Core::$page_options['boxed_background']['image'] ) ) {
				if ( ! empty( Clienticabuilder_Core::$page_options['boxed_background']['image'] ) ) {
					$src_box_bg = Clienticabuilder_Core::$page_options['boxed_background']['image'];
				} else {
					$src_box_bg = '';
				}
				if ( $src_box_bg !== 'none' && $src_box_bg !== '' ) {
					$box_bg_image      = 'background-image : url("' . $src_box_bg . '");';
					$box_bg_repeat     = 'background-repeat : ' . ( 'default' === Clienticabuilder_Core::$page_options['boxed_background']['repeat'] ? Clienticabuilder_Core::$page_options['boxed_background']['repeat'] : Clienticabuilder_Core::$page_options['boxed_background']['repeat'] ) . ';';
					$box_bg_size       = 'background-size : ' . ( 'default' === Clienticabuilder_Core::$page_options['boxed_background']['size'] ? Clienticabuilder_Core::$page_options['boxed_background']['size'] : Clienticabuilder_Core::$page_options['boxed_background']['size'] ) . ';';
					$box_bg_attachment = 'background-attachment : ' . ( 'default' === Clienticabuilder_Core::$page_options['boxed_background']['attachment'] ? Clienticabuilder_Core::$page_options['boxed_background']['attachment'] : Clienticabuilder_Core::$page_options['boxed_background']['attachment'] ) . ';';
					$box_bg_position   = 'background-position : ' . ( 'default' === Clienticabuilder_Core::$page_options['boxed_background']['position'] ? Clienticabuilder_Core::$page_options['boxed_background']['position'] : Clienticabuilder_Core::$page_options['boxed_background']['position'] ) . ';';
				} elseif ( 'none' === $src_box_bg ) {
					$box_bg_image  = 'background-image : none;';
					$box_bg_repeat = $box_bg_size = $box_bg_attachment = $box_bg_position = '';
				} else {
					$box_bg_image = $box_bg_repeat = $box_bg_size = $box_bg_attachment = $box_bg_position = '';
				}

			} else {
				$box_bg_image = $box_bg_repeat = $box_bg_size = $box_bg_attachment = $box_bg_position = '';
			}
			$mainbgr = '.' . $add_class[0] . '.' . $add_class[1] . ' .mainbgr{' . $box_bg_color . $box_bg_image . $box_bg_repeat . $box_bg_size . $box_bg_attachment . $box_bg_position . '}';

		} else {
			$mainbgr = '';
		}

		if ( ! empty( Clienticabuilder_Core::$page_options['content_background']['color'] ) ) {
			$content_bg_color = 'background-color : ' . Clienticabuilder_Config::get_color_val( Clienticabuilder_Config::get_color_val( Clienticabuilder_Core::$page_options['content_background']['color'] ) ) . ';';
		} else {
			$content_bg_color = '';
		}
		if ( ! empty( Clienticabuilder_Core::$page_options['content_background']['image'] ) ) {
			$src_content_bg = Clienticabuilder_Core::$page_options['content_background']['image'];
		} else {
			$src_content_bg = '';
		}
		if ( $src_content_bg !== 'none' && $src_content_bg !== '' ) {

			$content_bg_image      = 'background-image : url("' . $src_content_bg . '");';
			$content_bg_repeat     = 'background-repeat : ' . Clienticabuilder_Core::$page_options['content_background']['repeat'] . ';';
			$content_bg_size       = 'background-size : ' . Clienticabuilder_Core::$page_options['content_background']['size'] . ';';
			$content_bg_attachment = 'background-attachment : ' . Clienticabuilder_Core::$page_options['content_background']['attachment'] . ';';
			$content_bg_position   = 'background-position : ' . Clienticabuilder_Core::$page_options['content_background']['position'] . ';';

		} elseif ( 'none' === $src_content_bg ) {
			$content_bg_image  = 'background-image : none;';
			$content_bg_repeat = $content_bg_size = $content_bg_attachment = $content_bg_position = '';
		} else {
			$content_bg_image = $content_bg_repeat = $content_bg_size = $content_bg_attachment = $content_bg_position = '';
		}
		echo '<style>',
		$mainbgr,
		'.', $add_class[0],
		'.', $add_class[1],
		' main {',
		$content_bg_color,
		$content_bg_image,
		$content_bg_repeat,
		$content_bg_size,
		$content_bg_attachment,
		$content_bg_position,
		'}
        </style>';

	}

	public static function set_boxed_layout() {
		if ( 2 === Clienticabuilder_Core::$page_options['layout'] ) {
			echo '<div class="mainbgr"><div class="container"><div class="row">';
		}
	}

	public static function set_customized_slider() {
		if ( - 1 !== Clienticabuilder_Core::$page_options['pick_slider'] ) {
			echo '<div class="headerslider">';

			if ( ! Clienticabuilder_Core::$page_options['is_active_slider_plugins'] ) {

				echo '';

			} else {
				if ( ! Clienticabuilder_Helpers::is_search() ) {
					Clienticabuilder_Helpers::get_customized_slider( Clienticabuilder_Core::$page_options['pick_slider'] );
				}
			}
			echo '</div>';
		}
	}

	// Single post heading
	public static function single_post_heading() {
		if ( isset( Clienticabuilder_Core::$page_options['blog-custom-heading'] ) ) {
			if ( 1 === Clienticabuilder_Core::$page_options['blog-custom-heading'] ) {
				echo '<header class="single-heading">';
				Clienticabuilder_Layout::entry_header();
				echo '<div class="entry-meta">';
				Clienticabuilder_Blog::entry_meta_header();
				edit_post_link( esc_html__( 'Edit', 'dentalmed' ),
					'<span class="edit-link"><i></i> ',
					'</span>' );
				echo '</div>
                    </header>';
			} elseif ( 3 === Clienticabuilder_Core::$page_options['blog-custom-heading'] ) {
				Clienticabuilder_Layout::entry_header();
			}
		} else {
			Clienticabuilder_Layout::entry_header();
		}
	}

	// Add a pingback
	public function pingback_header() {
		if ( Clienticabuilder_Helpers::is_singular() && pings_open() ) {
			echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
		}
	}

	// Add a pingback
	private static function the_header() {
		if ( Clienticabuilder_Helpers::is_home() ) {
			echo '<header class="custblog">
                                <h1>', get_bloginfo( 'name' ), '</h1>
                                <div class="archive-meta">' . get_bloginfo( 'description' ) . '</div>
                            </header>';
		} elseif ( Clienticabuilder_Helpers::is_singular() ) {
			echo '<header class="custblog">';
			Clienticabuilder_Blog::cat_list();
			echo '<h1 class="entry-title">', get_the_title(), '</h1>
            <div class="entry-meta">';
			Clienticabuilder_Blog::entry_meta_header();
			edit_post_link( esc_html__( 'Edit', 'dentalmed' ),
				'<span class="edit-link"><i></i> ',
				'</span>' );
			echo '</div>
        </header>';
		} elseif ( Clienticabuilder_Helpers::is_author() ) {
			$post_id        = get_queried_object_id();
			$post_author_id = get_post_field( 'post_author', $post_id );
			$anick          = get_the_author_meta( 'nickname', $post_author_id );
			echo '<header class="custblog">';
			echo '<h1 class="archive-title">' . $anick . '</h1>';
			echo '<div class="archive-meta">' . get_the_author_meta( 'description' ) . '</div>';
			echo '</header>';
		} elseif ( Clienticabuilder_Helpers::is_category() ) {
			echo '<header class="custblog">';
			echo '<h1 class="archive-title">' . single_cat_title( '', 0 ) . '</h1>';
			if ( category_description() ) {
				echo '<div class="archive-meta">' . category_description() . '</div>';
			}
			echo '</header>';
		} else if ( is_tag() ) {
			echo '<header class="custblog">';
			echo '<h1 class="archive-title">' . single_cat_title( '', 0 ) . '</h1>';
			if ( tag_description() ) {
				echo '<div class="archive-meta">' . tag_description() . '</div>';
			}
			echo '</header>';
		} else if ( Clienticabuilder_Helpers::is_search() ) {
			echo '<header class="custblog">';
			echo '<h1 class="archive-title">';
			printf( esc_html__( 'Search Results for: %s', 'dentalmed' ), get_search_query() );
			echo '</h1>';
			echo '</header>';
		} elseif ( is_date() ) {
			echo '<header class="custblog">';
			echo '<h1 class="archive-title">';
			printf( esc_html__( 'Archive: %s', 'dentalmed' ), get_the_date() );
			echo '</h1>';
			echo '</header>';
		} else if ( is_404() ) {
			echo '<header class="custblog">
                     <h1>', esc_html__( '404', 'dentalmed' ), '</h1>
                  </header>
             ';
		} else if ( 'shop' == Clienticabuilder_Core::$page_options['page_type'] ) {
			if ( is_shop() ) {
				echo '<header class="custblog"><h1>', esc_html__( 'Shop', 'dentalmed' ), '</h1></header>';
			} else {
				if ( is_archive() ) {
					if ( is_woocommerce() ) {
						echo '<header class="custblog"><h1>' . get_the_archive_title() . '</h1></header>';
					}
				}
			}
		}
	}

	public function enqueue_scripts() {
		if ( ! empty ( Clienticabuilder_Core::$page_options['head_js'] ) ) {
			wp_register_script( 'clienticabuilder_head_js', false );
			wp_enqueue_script( 'clienticabuilder_head_js' );
			wp_add_inline_script( 'clienticabuilder_head_js', Clienticabuilder_Core::$page_options['head_js'] );
		}
	}
}

?>
