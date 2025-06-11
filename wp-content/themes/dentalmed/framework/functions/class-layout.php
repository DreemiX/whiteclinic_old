<?php
/*
The file contain functions for Woocommerce.
*/

class Clienticabuilder_Layout {

	public function __construct() {
		$this->content_width();
		add_filter( 'body_class', array( $this, 'body_class' ) );
		add_filter( 'wp_list_categories', array( $this, 'cat_count_span' ) );
		add_filter( 'get_archives_link', array( $this, 'archive_count_span' ) );
	}

	/**
	 * Extend the default WordPress body classes.
	 *
	 * Adds body classes to denote:
	 * 1. Single or multiple authors.
	 * 2. Active widgets in the sidebar to change the layout and spacing.
	 * 3. When avatars are disabled in discussion settings.
	 *
	 *
	 * @param array $classes A list of existing body class values.
	 *
	 * @return array The filtered body class list.
	 */

	public function body_class( $classes ) {
		if ( ! is_multi_author() ) {
			$classes[] = 'single-author';
		}
		if ( ! is_attachment() && ! is_404() ) {
			$classes[] = 'sidebar';
		}
		if ( ! get_option( 'show_avatars' ) ) {
			$classes[] = 'no-avatars';
		}

		return $classes;
	}

	public function cat_count_span( $links ) {
		$links = str_replace( '</a> (', '</a><span class="post-count">(', $links );
		$links = str_replace( ')', ')</span>', $links );

		return $links;
	}

	public function archive_count_span( $links ) {
		$links = str_replace( '</a>&nbsp;(', '</a><span class="post-count">(', $links );
		$links = str_replace( ')', ')</span>', $links );

		return $links;
	}

	/*
	* Adjust content_width value for video post formats and attachment templates.
	*/
	public function content_width() {
		global $content_width;
		if ( is_attachment() ) {
			$content_width = 1170;
		} elseif ( has_post_format( 'audio' ) ) {
			$content_width = 600;
		}
	}

	public static function title_404() {
		if ( Clienticabuilder_Helpers::is_search() ) {
			echo esc_html__( 'No results found', 'dentalmed' );
		} else {
			if ( isset( Clienticabuilder_Core::$page_options['404_title'] ) ) {
				echo esc_html( Clienticabuilder_Core::$page_options['404_title'] );
			} else {
				echo esc_html__( 'Page not found', 'dentalmed' );
			}
		}
	}

	public static function descr_404() {
		if ( isset( Clienticabuilder_Core::$page_options['404_descr'] ) ) {
			echo esc_html( Clienticabuilder_Core::$page_options['404_descr'] );
		} else {
			echo esc_html__( 'It looks like nothing was found at this location. Maybe try a search?', 'dentalmed' );
		}
	}

	public static function icon_404() {
		if ( ! empty( Clienticabuilder_Core::$page_options['404_icon']['url'] ) ) {
			if ( Clienticabuilder_Helpers::is_search() ) {

			} else {
				echo '<img src="', esc_url( Clienticabuilder_Core::$page_options['404_icon']['url'] ), '" alt="', esc_attr__( '404 Not Found', 'dentalmed' ), '" />';
			}
		}
	}

	/**
	 * Display navigation to next/previous set of posts when applicable.
	 *
	 */
	public static function paging_nav() {
		global $wp_query;
		// Don't print empty markup if there's only one page.
		if ( $wp_query->max_num_pages < 2 ) {
			return;
		}
		echo '<nav class="navigation paging-navigation">
            <div class="nav-links clearfix">';
		if ( get_next_posts_link() ) {
			echo '<div class="nav-previous alignleft">';
			next_posts_link( '<i class="nat-arrow-left8"></i> ' . esc_html__( 'Older posts', 'dentalmed' ) );
			echo '</div>';
		}
		if ( get_previous_posts_link() ) {
			echo '<div class="nav-next alignright">';
			previous_posts_link( esc_html__( 'Newer posts', 'dentalmed' ) . ' <i class="nat-arrow-right8"></i>' );
			echo '</div>';
		}
		echo '</div><!-- .nav-links -->
        </nav><!-- .navigation -->';
	}

	/**
	 * Print the attached image with a link to the next attached image.
	 */
	public static function the_attached_image() {
		/**
		 * Filter the image attachment size to use.
		 */
		$attachment_size     = apply_filters( 'clienticabuilder_attachment_size', array( 724, 724 ) );
		$next_attachment_url = wp_get_attachment_url();
		$post                = get_post();

		/*
		 * Grab the IDs of all the image attachments in a gallery so we can get the URL
		 * of the next adjacent image in a gallery, or the first image (if we're
		 * looking at the last image in a gallery), or, in a gallery of one, just the
		 * link to that image file.
		 */
		$attachment_ids = get_posts(
			array(
				'post_parent'    => $post->post_parent,
				'fields'         => 'ids',
				'numberposts'    => - 1,
				'post_status'    => 'inherit',
				'post_type'      => 'attachment',
				'post_mime_type' => 'image',
				'order'          => 'ASC',
				'orderby'        => 'menu_order ID',
			)
		);

		// If there is more than 1 attachment in a gallery...
		if ( count( $attachment_ids ) > 1 ) {
			foreach ( $attachment_ids as $attachment_id ) {
				if ( $attachment_id == $post->ID ) {
					$next_id = current( $attachment_ids );
					break;
				}
			}

			if ( ! empty( $next_id ) ) {
				// get the URL of the next image attachment...
				$next_attachment_url = get_attachment_link( $next_id );
			} else {
				// or get the URL of the first image attachment.
				$next_attachment_url = get_attachment_link( reset( $attachment_ids ) );
			}
		}

		printf(
			'<a href="%1$s" title="%2$s" rel="attachment">%3$s</a>',
			esc_url( wp_get_attachment_url() ),
			the_title_attribute( array( 'echo' => false ) ),
			wp_get_attachment_image( $post->ID, $attachment_size )
		);
	}

	//Scroll to top button
	public static function scroll_button() {
		if ( Clienticabuilder_Core::$page_options['scroll_to_top'] ) {
			echo '<a href="#" id="scroller"><span>' . esc_html__( 'Scroll to top', 'dentalmed' ) . '</span><svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="arrow-up" class="svg-inline--fa fa-arrow-up fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M34.9 289.5l-22.2-22.2c-9.4-9.4-9.4-24.6 0-33.9L207 39c9.4-9.4 24.6-9.4 33.9 0l194.3 194.3c9.4 9.4 9.4 24.6 0 33.9L413 289.4c-9.5 9.5-25 9.3-34.3-.4L264 168.6V456c0 13.3-10.7 24-24 24h-32c-13.3 0-24-10.7-24-24V168.6L69.2 289.1c-9.3 9.8-24.8 10-34.3.4z"></path></svg></a>';
		}
	}

	// Title for Case Study page
	public static function portfolio_title() {
		if ( ! empty( Clienticabuilder_Core::$page_options['portfolio_arch_title'] ) ) {
			echo esc_html( Clienticabuilder_Core::$page_options['portfolio_arch_title'] );
		}
	}

	public static function portfolio_desc() {
		$allowed_html = array(
			'a'      => array(
				'href'  => array(),
				'title' => array()
			),
			'img'    => array(
				'src'   => array(),
				'title' => array(),
				'alt'   => array(),
				'class' => array(),
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
			'del'    => array()
		);
		if ( isset( Clienticabuilder_Core::$page_options['portfolio_arch_desc'] ) ) {
			echo '<div class="descr_arch">', wp_kses( Clienticabuilder_Core::$page_options['portfolio_arch_desc'], $allowed_html ), '</div>';
		}
	}

	// Page H1 heading
	public static function entry_header() {
		if ( Clienticabuilder_Core::$page_options['single_header'] ) {
			echo '<h1 class="archive-title">', get_the_title(), '</h1>';
		}
	}

	// Archive pagination
	public static function archive_nav() {
		echo '<div class="clearfix">', paginate_links(
			array(
				'type'               => 'list',
				'end_size'           => 3,
				'mid_size'           => 3,
				'prev_next'          => false,
				'add_args'           => false,
				'add_fragment'       => '',
				'screen_reader_text' => '',
			)
		), '</div>';
	}

	public static function boxed_class() {
		$bwc = '';
		if ( 2 === Clienticabuilder_Core::$page_options[ Clienticabuilder_Core::$page_options['page-layout'] ] ) {
			$bwc = ' boxed-wrapper';
		}

		return $bwc;
	}

	public static function sidebar_layout_class_for_main() {
		$msc = '';
		if ( Clienticabuilder_Core::$page_options['sidebar_layout'] !== 1 ) {
			$msc = ' mainsidebar';
		}

		return $msc;
	}

	public static function main_tag_classes() {
		echo ' ', self::boxed_class(), self::sidebar_layout_class_for_main(), '';
	}

	// Page meta
	public static function page_entry_meta() {
		echo '<div class="entry-title">', get_the_title(), '</div>';
		echo '<span class="updated">', get_the_modified_time( 'F jS, Y h:i a' ), '</span>';
		echo '<span class="author">', get_the_author(), '</span>';
	}

	// Page meta
	public static function is_elementor_class() {
		if ( self::is_elementor() ) {
			echo 'container-full';
		} else {
			echo 'container otherpage';
		}
	}

	public static function is_elementor() {
		global $post;
		if ( empty( $post->ID ) ) {
			return false;
		} else {
			return ! ! get_post_meta( $post->ID, '_elementor_edit_mode', true );
		}
	}
}

?>
