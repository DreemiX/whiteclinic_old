<?php
/**
 * The class contain blog functions.
 */

class Clienticabuilder_Blog {

private static $s_temp;
private static $a_temp;

public function __construct() {
	
	add_filter( 'excerpt_more', array( $this, 'new_excerpt_more' ) );
}

public static function single_template() {
	if ( is_null( self::$s_temp ) ) {
		self::$s_temp = 'single_template_' . Clienticabuilder_Core::$page_options['single_template'];
		if ( ! method_exists( self::class, self::$s_temp ) ) {
			self::$s_temp = 'single_template_1';
		}
	}
	$md = self::$s_temp;
	self::$md();
}

public static function single_template_1() {
	echo '<div class="postbody">';
	Clienticabuilder_Header::single_post_heading();
	if ( has_post_thumbnail() && ! is_attachment() ) {
		echo '<div class="entry-thumbnail">';
		the_post_thumbnail( 'clienticabuilder_long' );
		echo '</div>';
	}
	echo '<div class="entry-content">';
	/* translators: %s: Name of current post */
	the_content(
		sprintf(
			esc_html__( 'Continue reading ', 'dentalmed' ), '<span class="meta-nav">&rarr;</span>',
			the_title( '<span class="screen-reader-text">', '</span>', false )
		)
	);

	wp_link_pages(
		array(
			'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'dentalmed' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>'
		)
	);
	self::tags();
	echo '</div>
        </div>';
	echo '<div class="postoddy">';
	self::post_author();
	self::post_nav();
	self::show_postmore();
	comments_template();
	echo '</div>';
}

public static function single_template_2() {
	global $post;
	$format = get_post_format();
	if ( false === $format ) {
		$format = 'standard';
	}
	$category = get_the_category( $post->ID );
	$author   = '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" rel="author">' . get_the_author_meta( 'display_name' ) . '</a>';
	echo '<div class="postbody">';
	echo '<div class="meta">';
	echo '<div class="date"><label>' . esc_html__( 'Date', 'dentalmed' ) . '</label><span>' . get_the_date() . '</span></div>';
	echo '<div class="author"><label>' . esc_html__( 'Author', 'dentalmed' ) . '</label><span>' . $author . '</span></div>';
	if ( ! empty( $category ) ) {
		echo '<div class="cat"><label>' . esc_html__( 'Category', 'dentalmed' ) . '</label><span>' . $category[0]->name . '</span></div>';
	}
	echo '</div>';
	if ( has_post_thumbnail() && ! is_attachment() ) {
		echo '<div class="entry-thumbnail">';
		the_post_thumbnail( 'clienticabuilder_long650' );
		echo '<div class="over">';
		if ( 'standard' == $format ) {

		} elseif ( 'video' == $format ) {
			echo '<div><img src="' . get_template_directory_uri() . '/images/play.svg" alt="' . get_the_title() . '"></div>';
		} elseif ( 'audio' == $format ) {
			echo '<div><img src="' . get_template_directory_uri() . '/images/volume.svg" alt="' . get_the_title() . '"></div>';
		} elseif ( 'gallery' == $format ) {
			echo '<div><img src="' . get_template_directory_uri() . '/images/gallery.svg" alt="' . get_the_title() . '"></div>';
		} elseif ( 'quote' == $format ) {
			echo '<div><img src="' . get_template_directory_uri() . '/images/quote.svg" alt="' . get_the_title() . '"></div>';
		} elseif ( 'link' == $format ) {
			echo '<div><img src="' . get_template_directory_uri() . '/images/link.svg" alt="' . get_the_title() . '"></div>';
		}
		echo '</div>';
		echo '</div>';
	}
	echo '<div class="entry-content">';
	/* translators: %s: Name of current post */
	the_content(
		sprintf(
			esc_html__( 'Continue reading ', 'dentalmed' ), '<span class="meta-nav">&rarr;</span>',
			the_title( '<span class="screen-reader-text">', '</span>', false )
		)
	);

	wp_link_pages(
		array(
			'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'dentalmed' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>'
		)
	);
	echo '<div class="clearfix"></div>';
	self::share();
	self::tags();
	echo '</div>
        </div>';

	echo '<div class="postoddy">';
	self::post_author();
	self::post_nav();
	self::show_postmore2();
	comments_template();
	echo '</div>';
}

public static function single_template_3() {
	echo '<div class="postbody">';
	if ( has_post_thumbnail() && ! is_attachment() ) {
		echo '<div class="entry-thumbnail">';
		the_post_thumbnail( 'clienticabuilder_long650' );
		echo '</div>';
	}

	echo '<div class="entry-header">';
	echo '<div class="entry-meta">';
	self::entry_meta();
	echo '</div>';
	self::post_header();
	echo '</div>';
	echo '<div class="entry-content">';
	/* translators: %s: Name of current post */
	the_content(
		sprintf(
			esc_html__( 'Continue reading ', 'dentalmed' ), '<span class="meta-nav">&rarr;</span>',
			the_title( '<span class="screen-reader-text">', '</span>', false )
		)
	);

	wp_link_pages(
		array(
			'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'dentalmed' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>'
		)
	);
	echo '<div class="clearfix"></div>';
	self::tags();
	self::share_2();
	echo '</div>
        </div>';
	echo '<div class="postoddy">';
	self::post_author_2();
	self::post_nav();
	self::show_postmore3();
	comments_template();
	echo '</div>';
}

public static function single_template_5() {
	echo '<div class="postbody">';
	$format = get_post_format();
	if ( false === $format ) {
		$format = 'standard';
	}

	if ( has_post_thumbnail() && ! is_attachment() ) {
		echo '<div class="entry-thumbnail">';
		echo '<span class="data">' . get_the_date( 'j' ) . '<span>' . get_the_date( 'M' ) . '</span></span>';
		echo get_the_post_thumbnail( '', 'clienticabuilder_long650' );
		echo '</div>';
	}
	echo '<div class="entry-header">';
	echo '<div class="entry-meta">';
	self::entry_meta_2();
	echo '</div>';
	self::post_header();
	echo '</div>';

	echo '<div class="entry-content">';
	/* translators: %s: Name of current post */
	the_content(
		sprintf(
			esc_html__( 'Continue reading ', 'dentalmed' ), '<span class="meta-nav">&rarr;</span>',
			the_title( '<span class="screen-reader-text">', '</span>', false )
		)
	);

	wp_link_pages(
		array(
			'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'dentalmed' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>'
		)
	);
	echo '<div class="clearfix"></div>';
	self::share_2();
	self::tags();
	echo '</div>
        </div>';
	echo '<div class="postoddy">';
	self::post_author_2();
	self::post_nav_2();
	self::show_postmore4();
	comments_template();
	echo '</div>';
}

public static function single_template_6() {
	echo '<div class="postbody">';
	$format = get_post_format();
	if ( false === $format ) {
		$format = 'standard';
	}
	echo '<div class="entry-meta">';
	self::cat_list_single();
	self::entry_meta_3();
	echo '</div>';

	if ( has_post_thumbnail() ) {
		echo '<div class="entry-thumbnail">';
		self::cat_list_single();
		echo '<span class="data">' . get_the_date( 'j' ) . '<span>' . get_the_date( 'M' ) . '</span></span>';
		echo get_the_post_thumbnail( '', 'clienticabuilder_long650' );
		echo '</div>';
	}
	echo '<div class="entry-header">';
	self::post_header();
	echo '</div>';

	echo '<div class="entry-content">';
	/* translators: %s: Name of current post */
	the_content(
		sprintf(
			esc_html__( 'Continue reading ', 'dentalmed' ), '<span class="meta-nav">&rarr;</span>',
			the_title( '<span class="screen-reader-text">', '</span>', false )
		)
	);

	wp_link_pages(
		array(
			'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'dentalmed' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>'
		)
	);
	echo '<div class="clearfix"></div>';
	self::tags();

	echo '</div>
        </div>';
	echo '<div class="postoddy">';
	self::post_author_3();
	self::post_nav_3();
	self::show_postmore5();
	comments_template();
	echo '</div>';
}

public static function single_template_7() {
	echo '<div class="postbody">';
	$format = get_post_format();
	if ( false === $format ) {
		$format = 'standard';
	}


	if ( has_post_thumbnail() ) {
		echo '<div class="entry-thumbnail">';
		self::cat_list_single();
		echo '<span class="data">' . get_the_date( 'j' ) . '<span>' . get_the_date( 'M' ) . '</span></span>';
		echo get_the_post_thumbnail( '', 'clienticabuilder_long' );
		echo '</div>';
	}

	echo '<div class="entry-meta">';
	self::cat_list_single();
	self::entry_meta_3();
	echo '</div>';

	echo '<div class="entry-header">';
	self::post_header();
	echo '</div>';

	echo '<div class="entry-content">';
	/* translators: %s: Name of current post */
	the_content(
		sprintf(
			esc_html__( 'Continue reading ', 'dentalmed' ), '<span class="meta-nav">&rarr;</span>',
			the_title( '<span class="screen-reader-text">', '</span>', false )
		)
	);

	wp_link_pages(
		array(
			'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'dentalmed' ) . '</span>',
			'after'       => '</div>',
			'link_before' => '<span>',
			'link_after'  => '</span>'
		)
	);
	echo '<div class="clearfix"></div>';

	self::tags();
	self::share();
	echo '</div>
        </div>';
	echo '<div class="postoddy">';
	self::post_author_2();
	self::post_nav_3();
	self::show_postmore6();
	comments_template();
	echo '</div>';
}

public static function archive_template() {
	if ( is_null( self::$a_temp ) ) {
		self::$a_temp = 'archive_template_' . Clienticabuilder_Core::$page_options['archive_template'];
		if ( ! method_exists( self::class, self::$a_temp ) ) {
			self::$a_temp = 'archive_template_1';
		}
	}
	$md = self::$a_temp;
	self::$md();
}

public static function archive_template_1() {
	$perm  = get_the_permalink();
	$title = get_the_title();
	if ( has_post_thumbnail() && ! is_attachment() ) {
		echo '<div class="thumb">';
		echo get_the_post_thumbnail( '', 'clienticabuilder_long650' );
		self::cat_list();
		echo '<div><a href="' . $perm . '" rel="bookmark"><img src="' . esc_url( get_template_directory_uri() ) . '/images/eye.svg" alt="' . esc_attr__( 'Full Post', 'dentalmed' ) . '"></a></div></div>';
	}

	echo '<div class="c_block">
            <header class="entry-header">
                <h3 class="entry-title"><a href="' . $perm . '" rel="bookmark">' . $title . '</a></h3>
                <div class="entry-meta">';
	self::entry_meta();
	echo '</div>
            </header><!-- .entry-header -->
            <div class="entry-content">';
	the_excerpt();
	self::read_more();

	echo '</div>
        </div>';
}

public static function archive_template_2() {
	$authorimg = '<span class="post-author">' . get_avatar( get_the_author_meta( 'user_email' ), 40 ) . '  <a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" rel="author">' . get_the_author_meta( 'display_name' ) . '</a></span>';
	$format    = get_post_format();
	if ( false === $format ) {
		$format = 'standard';
	}
	$perm  = get_the_permalink();
	$title = get_the_title();

	if ( has_post_thumbnail() && ! is_attachment() ) {
		echo '<div class="thumb">
                <a href="' . $perm . '" rel="bookmark"></a>';
		Clienticabuilder_Blog::thumb_size();
		echo '<div class="over">';
		if ( 'standard' == $format ) {

		} elseif ( 'video' == $format ) {
			echo '<div><img src="' . get_template_directory_uri() . '/images/play.svg" alt="' . $title . '"></div>';
		} elseif ( 'audio' == $format ) {
			echo '<div><img src="' . get_template_directory_uri() . '/images/volume.svg" alt="' . $title . '"></div>';
		} elseif ( 'gallery' == $format ) {
			echo '<div><img src="' . get_template_directory_uri() . '/images/gallery.svg" alt="' . $title . '"></div>';
		} elseif ( 'quote' == $format ) {
			echo '<div><img src="' . get_template_directory_uri() . '/images/quote.svg" alt="' . $title . '"></div>';
		} elseif ( 'link' == $format ) {
			echo '<div><img src="' . get_template_directory_uri() . '/images/link.svg" alt="' . $title . '"></div>';
		}
		echo '</div></div>';
	}

	echo '<div class="c_block">
            <header class="entry-header">';
	self::cat_list();
	echo '<h3 class="entry-title"><a href="' . $perm . '" rel="bookmark">' . $title . '</a></h3>';
	echo '</header><!-- .entry-header -->
            <div class="entry-content">';
	echo wpautop( get_the_excerpt() );
	echo '<div class="entry-meta">' . $authorimg . '<span class="date">' . get_the_date() . '</span></div>';
	self::read_more();
	echo '</div>
        </div>';


}

public static function archive_template_3() {
	$format = get_post_format();
	if ( false === $format ) {
		$format = 'standard';
	}
	$perm  = get_the_permalink();
	$title = get_the_title();
	if ( has_post_thumbnail() && ! is_attachment() && ( 'quote' != $format ) ) {
		echo '<div class="thumb">';
		echo get_the_post_thumbnail( '', 'clienticabuilder_long750' );
		self::cat_list();
		echo '<div class="thhov"><a href="' . $perm . '" rel="bookmark">';
		if ( 'video' == $format ) {
			echo '<span><img src="' . get_template_directory_uri() . '/images/play2.svg" alt="' . $title . '"></span>';
		} elseif ( 'audio' == $format ) {
			echo '<span><img src="' . get_template_directory_uri() . '/images/audio.svg" alt="' . $title . '"></span>';
		} elseif ( 'gallery' == $format ) {
			echo '<span><img src="' . get_template_directory_uri() . '/images/gallery2.svg" alt="' . $title . '"></span>';
		}
		echo '</a></div>';
		echo '</div>';
	}
	if ( 'quote' == $format ) {
		echo '<div class="thumb">';
		self::cat_list();
		echo '</div>';
	}

	echo '<div class="c_block">
            <header class="entry-header">
                <h3 class="entry-title"><a href="' . $perm . '" rel="bookmark">' . $title . '</a></h3>';
	echo '</header><!-- .entry-header -->
            <div class="entry-content">';
	if ( 'quote' == $format ) {
		$quote = Clienticabuilder_Blog::get_tag( get_the_content(), '<blockquote', '</blockquote>' );
		if ( ! empty( $quote ) ) {
			echo '<a href="' . $perm . '" rel="bookmark">' . $quote . '</a>';
		} else {
			echo '<a href="' . $perm . '" rel="bookmark">' . wpautop( get_the_excerpt() ) . '</a>';
		}
	} else {
		echo wpautop( get_the_excerpt() );
	}

	echo '<div class="entry-meta">';
	self::entry_meta();
	echo '</div>';
	self::read_more();

	echo '</div>';
	if ( self::is_sticky_isnt_category() ) {
		echo '<svg x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;">
<g>
	<g>
		<path d="M346.991,149.886l-21.508-21.509l-176.36,176.355c-21.903,21.903-21.903,57.543,0,79.447
			c21.903,21.904,57.543,21.904,79.447,0L465.422,147.32c16.3-16.3,25.277-37.971,25.277-61.022s-8.977-44.722-25.277-61.022
			C449.123,8.977,427.451,0,404.4,0c-23.052,0-44.723,8.977-61.022,25.277L55.401,313.254c-21.99,21.99-34.1,51.226-34.1,82.323
			c0,31.098,12.11,60.333,34.1,82.323c21.99,21.99,51.226,34.1,82.323,34.1s60.333-12.11,82.323-34.1l227.479-227.48l-21.508-21.508
			l-227.479,227.48c-16.245,16.245-37.842,25.19-60.815,25.19c-22.973,0-44.57-8.946-60.815-25.19
			c-16.245-16.245-25.19-37.842-25.19-60.815c0-22.973,8.947-44.571,25.19-60.815L364.887,46.785
			c21.788-21.787,57.24-21.787,79.028,0c21.788,21.788,21.788,57.24,0,79.028L207.063,362.67c-10.044,10.044-26.387,10.044-36.431,0
			s-10.044-26.387,0-36.431L346.991,149.886z"/>
	</g>
</g>
</svg>';
	}
	echo '</div>';
}

public static function archive_template_4() {

	$format = get_post_format();
	if ( false === $format ) {
		$format = 'standard';
	}
	$perm  = get_the_permalink();
	$title = get_the_title();
	if ( has_post_thumbnail() && ! is_attachment() && ( 'quote' != $format ) ) {
		echo '<div class="thumb">';
		echo get_the_post_thumbnail( '', 'clienticabuilder_long650' );
		echo '<div class="thhov"><a href="' . $perm . '" rel="bookmark">';
		if ( 'video' == $format ) {
			echo '<span><img src="' . get_template_directory_uri() . '/images/play3.svg" alt="' . $title . '"></span>';
		} elseif ( 'audio' == $format ) {
			echo '<span><img src="' . get_template_directory_uri() . '/images/audio2.svg" alt="' . $title . '"></span>';
		} elseif ( 'gallery' == $format ) {
			echo '<span><img src="' . get_template_directory_uri() . '/images/gallery3.svg" alt="' . $title . '"></span>';
		}
		if ( self::is_sticky_isnt_category() ) {
			echo '<span><img src="' . get_template_directory_uri() . '/images/clip2.svg" alt="' . $title . '"></span>';
		}
		echo '</a></div>';
		echo '</div>';
	}


	echo '<div class="c_block">';
	echo '<div class="entry-meta">';
	self::entry_meta();
	echo '</div>';
	echo '<header class="entry-header">
                <h3 class="entry-title"><a href="' . $perm . '" rel="bookmark">' . $title . '</a></h3>';
	echo '</header>';


	echo '<div class="entry-content">';
	if ( 'quote' == $format ) {
		$quote = Clienticabuilder_Blog::get_tag( get_the_content(), '<blockquote', '</blockquote>' );
		if ( ! empty( $quote ) ) {
			echo '<a href="' . $perm . '" rel="bookmark">' . $quote . '</a>';
		} else {
			echo '<a href="' . $perm . '" rel="bookmark">' . wpautop( get_the_excerpt() ) . '</a>';
		}
	} else {
		echo wpautop( get_the_excerpt() );
	}

	if ( Clienticabuilder_Core::$page_options['show_read_more'] ) {
		if ( ! empty( Clienticabuilder_Core::$page_options['read_more_text'] ) ) {
			$text = Clienticabuilder_Core::$page_options['read_more_text'];
		} else {
			$text = esc_html__( 'Read more', 'dentalmed' );
		}

		echo '<a href="', $perm, '" rel="bookmark" class="more">', esc_html( $text ), '<span class="screen-reader-text">', $title, '</span><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 404.258 404.258" style="enable-background:new 0 0 404.258 404.258; width:10px; height:10px" xml:space="preserve"><polygon points="138.331,0 114.331,18 252.427,202.129 114.331,386.258 138.331,404.258 289.927,202.129 "/></svg></a>';

	}

	echo '</div>';

	echo '</div>';
}

public static function archive_template_5() {

	$format = get_post_format();
	if ( false === $format ) {
		$format = 'standard';
	}

	$perm  = get_the_permalink();
	$title = get_the_title();

	echo '<div class="postc">';


	if ( has_post_thumbnail() && ! is_attachment() && ( 'quote' != $format ) ) {
		echo '<div class="thumb">';
		echo '<span class="data">' . get_the_date( 'j' ) . '<span>' . get_the_date( 'M' ) . '</span></span>';
		echo get_the_post_thumbnail( '', 'clienticabuilder_long650' );
		echo '<div class="thhov"><a href="' . $perm . '" rel="bookmark">';
		if ( self::is_sticky_isnt_category() ) {
			echo '<span></span>';
		}
		echo '<span>+</span>';
		echo '</a></div>';
		echo '</div>';
	}

	echo '<div class="c_block">';
	echo '<div class="entry-meta">';
	self::entry_meta_2();
	echo '</div>';
	echo '<header class="entry-header">
                    <h3 class="entry-title"><a href="' . $perm . '" rel="bookmark">' . $title . '</a></h3>';
	echo '</header>';

	echo '<div class="entry-content">';

	if ( 'quote' == $format ) {
		$quote = Clienticabuilder_Blog::get_tag( get_the_content(), '<blockquote', '</blockquote>' );
		if ( ! empty( $quote ) ) {
			echo '<a href="' . $perm . '" rel="bookmark">' . $quote . '</a>';
		} else {
			echo '<a href="' . $perm . '" rel="bookmark">' . wpautop( get_the_excerpt() ) . '</a>';
		}
	} elseif ( 'audio' == $format ) {
		$aud = Clienticabuilder_Blog::get_tag( get_the_content(), '<audio', '</audio>' );
		if ( ! empty( $aud ) ) {
			echo '<p>' . $aud . '</p>';
		} else {
			echo '<p>' . wpautop( get_the_excerpt() ) . '</p>';
		}
	} else {
		echo wpautop( get_the_excerpt() );
	}

	if ( Clienticabuilder_Core::$page_options['show_read_more'] ) {
		if ( ! empty( Clienticabuilder_Core::$page_options['read_more_text'] ) ) {
			echo '<a href="', $perm, '" rel="bookmark" class="more">', esc_html( Clienticabuilder_Core::$page_options['read_more_text'] ), '<span class="screen-reader-text">', $title, '</span></a>';
		} else {
			echo '<a href="', $perm, '" rel="bookmark" class="more">', esc_html__( 'Read more', 'dentalmed' ), '<span class="screen-reader-text">', $title, '</span></a>';
		}
	}

	self::share_2();
	echo '</div>';
	echo '</div>';

	echo '</div>';
}

public static function archive_template_6() {
	$perm  = get_the_permalink();
	$title = get_the_title();
	if ( self::is_sticky_isnt_category() ) {
		if ( has_post_thumbnail() && ! is_attachment() ) {
			echo '<div class="thumb">';

			echo get_the_post_thumbnail( '', 'clienticabuilder_long650' );
			echo '<a href="' . $perm . '" rel="bookmark"></a>';
			echo '</div>';
		}

		echo '<div class="c_block">';
		self::cat_list_single();
		echo '<div class="entry-meta">';
		self::entry_meta();
		echo '</div>';
		echo '<header class="entry-header">
                    <h3 class="entry-title"><a href="' . $perm . '" rel="bookmark">' . $title . '</a></h3>
                    ';
		echo '
                </header><!-- .entry-header -->
                <div class="entry-content">';
		the_excerpt();
		self::read_more();

		echo '</div>
            </div>';
	} else {
		if ( has_post_thumbnail() && ! is_attachment() ) {
			echo '<div class="thumb">';
			self::cat_list_single();
			echo get_the_post_thumbnail( '', 'clienticabuilder_long650' );
			echo '<a href="' . $perm . '" rel="bookmark"></a>';
			echo '</div>';
		}

		echo '<div class="c_block">';
		echo '<div class="entry-meta">';
		self::entry_meta();
		echo '</div>';
		echo '<header class="entry-header">
                    <h3 class="entry-title"><a href="' . $perm . '" rel="bookmark">' . $title . '</a></h3>
                    ';
		echo '
                </header><!-- .entry-header -->
                <div class="entry-content">';
		the_excerpt();
		self::read_more();

		echo '</div>
            </div>';
	}

}

public static function archive_template_7() {
	$perm  = get_the_permalink();
	$title = get_the_title();
	if ( has_post_thumbnail() ) {
		echo '<div class="thumb">';
		echo get_the_post_thumbnail( '', 'clienticabuilder_long' );
		echo '<a href="' . $perm . '" rel="bookmark"></a>';
		echo '</div>';
	}

	echo '<div class="conte">';

	echo '<header class="entry-header">
                    <h3 class="entry-title"><a href="' . $perm . '" rel="bookmark">' . $title . '</a></h3>
                    </header><!-- .entry-header -->';
	echo '<div class="entry-meta">';
	self::cat_list_single();
	self::entry_meta();
	echo '</div>';

	echo '<div class="entry-content">';
	the_excerpt();
	self::read_more();

	echo '</div>
            </div>';


}

public static function comment_template() {

	if ( empty( Clienticabuilder_Core::$page_options['comment_template'] ) ) {
		$method = 'update_comments_fields_' . Clienticabuilder_Theme_Demo::DEFAULT_COMMENT_THEMPLATE;
	} else {
		$method = 'update_comments_fields_' . Clienticabuilder_Core::$page_options['comment_template'];
	}
	if ( method_exists( 'Clienticabuilder_Blog', $method ) ) {
		$args = self::$method();
		comment_form( $args );
	} else {
		comment_form();
	}
}

// Comment form fields update: add placeholders
public static function update_comments_fields_1() {
	$commenter = wp_get_current_commenter();
	$req       = get_option( 'require_name_email' );
	$label     = $req ? '*' : ' ' . esc_html__( '(optional)', 'dentalmed' );
	$aria_req  = $req ? "aria-required='true'" : '';

	$args = array(
		'title_reply'   => esc_html__( 'Leave a reply', 'dentalmed' ),
		'comment_field' => '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Comment', 'dentalmed' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="' . esc_attr__( 'Enter comment here...', 'dentalmed' ) . '"></textarea></p>',

		'fields' => apply_filters( 'comment_form_default_fields', array(

				'author' => '<p class="comment-form-author">
                        <label for="author">' . esc_html__( "Name", 'dentalmed' ) . $label . '</label>
                        <input id="author" name="author" type="text" placeholder="' . esc_attr__( "Your Name", 'dentalmed' ) . '" value="' . esc_attr( $commenter['comment_author'] ) .
				            '" size="30" ' . $aria_req . ' />
                    </p>',
				'email'  => '<p class="comment-form-email">
                        <label for="email">' . esc_html__( "Email", 'dentalmed' ) . $label . '</label>
                        <input id="email" name="email" type="email" placeholder="' . esc_attr__( "Your E-mail", 'dentalmed' ) . '" value="' . esc_attr( $commenter['comment_author_email'] ) .
				            '" size="30" ' . $aria_req . ' />
                    </p>',
				'url'    => '<p class="comment-form-url">
                        <label for="url">' . esc_html__( "Website", 'dentalmed' ) . '</label>
                        <input id="url" name="url" type="url"  placeholder="' . esc_attr__( "Your Website", 'dentalmed' ) . '" value="' . esc_attr( $commenter['comment_author_url'] ) .
				            '" size="30" />
                        </p>'
			)
		),

	);

	return $args;
}

public static function update_comments_fields_2() {

	add_filter( 'comment_form_fields', function ( $fields ) {

		$fields = array_replace( array_flip( array( 'author', 'email', 'comment', ) ), $fields );

		return $fields;
	}, 999 );

	$commenter = wp_get_current_commenter();
	$req       = get_option( 'require_name_email' );
	$label     = $req ? '*' : ' ' . esc_attr__( '(optional)', 'dentalmed' );
	$aria_req  = $req ? "aria-required='true'" : '';

	$args = array(
		'class_form'           => 'comment-form cf2',
		'comment_notes_before' => '',
		'comment_notes_after'  => '',
		'title_reply'          => esc_html__( 'Leave a reply', 'dentalmed' ),
		'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title">',
		'title_reply_after'    => '</h3>',

		'fields'        => apply_filters( 'comment_form_default_fields', array(
				'author' =>
					'<p class="comment-form-author">
                        <label for="author">' . esc_html__( "Name", 'dentalmed' ) . $label . '</label>
                        <input id="author" name="author" type="text" placeholder="' . esc_attr__( "Your Name", 'dentalmed' ) . '" value="' . esc_attr( $commenter['comment_author'] ) .
					'" size="30" ' . $aria_req . ' />
                    </p>',
				'email'  =>
					'<p class="comment-form-email">
                        <label for="email">' . esc_html__( "Email", 'dentalmed' ) . $label . '</label>
                        <input id="email" name="email" type="email" placeholder="' . esc_attr__( "Your E-mail", 'dentalmed' ) . '" value="' . esc_attr( $commenter['comment_author_email'] ) .
					'" size="30" ' . $aria_req . ' />
                    </p>',
			)
		),
		'comment_field' => '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Comment', 'dentalmed' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="' . esc_attr__( 'Enter comment here...', 'dentalmed' ) . '"></textarea></p>',

	);

	return $args;
}


public static function update_comments_fields_3() {
	add_filter( 'comment_form_fields', function ( $fields ) {

		$fields = array_replace( array_flip( array( 'author', 'email', 'url', 'comment', ) ), $fields );

		return $fields;
	}, 999 );

	$commenter = wp_get_current_commenter();
	$req       = get_option( 'require_name_email' );
	$label     = $req ? '*' : ' ' . esc_attr__( '(optional)', 'dentalmed' );
	$aria_req  = $req ? "aria-required='true'" : '';

	$args = array(
		'class_form'           => 'comment-form cf3',
		'comment_notes_before' => '',
		'comment_notes_after'  => '',
		'title_reply'          => esc_html__( 'Leave a reply', 'dentalmed' ),
		'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title">',
		'title_reply_after'    => '</h3>',

		'fields'        => apply_filters( 'comment_form_default_fields', array(
				'author' =>
					'<p class="comment-form-author">
                        <label for="author">' . esc_html__( "Name", 'dentalmed' ) . $label . '</label>
                        <input id="author" name="author" type="text" placeholder="' . esc_attr__( "Your Name", 'dentalmed' ) . '" value="' . esc_attr( $commenter['comment_author'] ) .
					'" size="30" ' . $aria_req . ' />
                    </p>',
				'email'  =>
					'<p class="comment-form-email">
                        <label for="email">' . esc_html__( "Email", 'dentalmed' ) . $label . '</label>
                        <input id="email" name="email" type="email" placeholder="' . esc_attr__( "Your E-mail", 'dentalmed' ) . '" value="' . esc_attr( $commenter['comment_author_email'] ) .
					'" size="30" ' . $aria_req . ' />
                    </p>',
				'url'    => '<p class="comment-form-url">
                        <label for="url">' . esc_html__( "Website", 'dentalmed' ) . '</label>
                        <input id="url" name="url" type="url"  placeholder="' . esc_attr__( "Your Website", 'dentalmed' ) . '" value="' . esc_attr( $commenter['comment_author_url'] ) .
				            '" size="30" />
                        </p>'
			)
		),
		'comment_field' => '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Comment', 'dentalmed' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="' . esc_attr__( 'Enter comment here...', 'dentalmed' ) . '"></textarea></p>',

	);

	return $args;
}

public static function update_comments_fields_4() {
	add_filter( 'comment_form_fields', function ( $fields ) {

		$fields = array_replace( array_flip( array( 'author', 'email', 'url', 'comment', ) ), $fields );

		return $fields;
	}, 999 );

	$commenter = wp_get_current_commenter();
	$req       = get_option( 'require_name_email' );
	$label     = $req ? '*' : ' ' . esc_attr__( '(optional)', 'dentalmed' );
	$aria_req  = $req ? "aria-required='true'" : '';

	$args = array(
		'class_form'           => 'comment-form cf4',
		'comment_notes_before' => '',
		'comment_notes_after'  => '',
		'title_reply'          => esc_html__( 'Leave a reply', 'dentalmed' ),
		'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title">',
		'title_reply_after'    => '</h3>',

		'fields'        => apply_filters( 'comment_form_default_fields', array(
				'author' =>
					'<p class="comment-form-author">
                        <label for="author">' . esc_html__( "Name", 'dentalmed' ) . $label . '</label>
                        <input id="author" name="author" type="text" placeholder="' . esc_attr__( "Your Name", 'dentalmed' ) . '" value="' . esc_attr( $commenter['comment_author'] ) .
					'" size="30" ' . $aria_req . ' />
                    </p>',
				'email'  =>
					'<p class="comment-form-email">
                        <label for="email">' . esc_html__( "Email", 'dentalmed' ) . $label . '</label>
                        <input id="email" name="email" type="email" placeholder="' . esc_attr__( "Your E-mail", 'dentalmed' ) . '" value="' . esc_attr( $commenter['comment_author_email'] ) .
					'" size="30" ' . $aria_req . ' />
                    </p>',
				'url'    => '<p class="comment-form-url">
                        <label for="url">' . esc_html__( "Website", 'dentalmed' ) . '</label>
                        <input id="url" name="url" type="url"  placeholder="' . esc_attr__( "Your Website", 'dentalmed' ) . '" value="' . esc_attr( $commenter['comment_author_url'] ) .
				            '" size="30" />
                        </p>'
			)
		),
		'comment_field' => '<p class="comment-form-comment"><label for="comment">' . esc_html__( 'Comment', 'dentalmed' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" placeholder="' . esc_attr__( 'Enter comment here...', 'dentalmed' ) . '"></textarea></p>',

	);

	return $args;
}


public static function show_postmore() {

	if ( Clienticabuilder_Core::$page_options['is_related_posts'] ) {
		self::postmore_query();
	}
}

public static function show_postmore2() {

	if ( Clienticabuilder_Core::$page_options['is_related_posts'] ) {
		self::postmore_query2();
	}
}

public static function show_postmore3() {

	if ( Clienticabuilder_Core::$page_options['is_related_posts'] ) {
		self::postmore_query3();
	}
}

public static function show_postmore4() {

	if ( Clienticabuilder_Core::$page_options['is_related_posts'] ) {
		self::postmore_query4();
	}
}
public static function show_postmore5() {

	if ( Clienticabuilder_Core::$page_options['is_related_posts'] ) {
		self::postmore_query5();
	}
}

public static function show_postmore6() {

	if ( Clienticabuilder_Core::$page_options['is_related_posts'] ) {
		self::postmore_query6();
	}
}

public static function post_author() {

	if ( Clienticabuilder_Core::$page_options['show_post_author'] ) {
		$user_description = get_the_author_meta( 'description' );
		if ( ! empty( $user_description ) ) {
			echo '<div class="author_info vcard" itemscope="" itemtype="https://schema.org/Person">

                    <div class="author_avatar" itemprop="image">' . get_avatar( get_the_author_meta( 'user_email' ), 150 ) . '</div>
                
                    <div class="author_description">
                        <h5 class="author_title" itemprop="name">' . get_the_author() . '</h5>
                
                        <div class="author_bio" itemprop="description">
                            <p>' . $user_description . '</p>
                            <a class="author_link" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" rel="author">' . esc_html__( 'View all posts by', 'dentalmed' ) . ' <span class="author_name">' . get_the_author() . '</span></a>
                            <div class="entry-meta author_links">';
			self::author_links();
			echo '</div></div>
                    </div>
                </div>';
		}
	}
}

public static function post_author_2() {

	if ( Clienticabuilder_Core::$page_options['show_post_author'] ) {
		$user_description = get_the_author_meta( 'description' );
		if ( ! empty( $user_description ) ) {
			echo '<div class="author_info vcard" itemscope="" itemtype="https://schema.org/Person">

                    <div class="author_avatar" itemprop="image">' . get_avatar( get_the_author_meta( 'user_email' ), 150 ) . '</div>
                
                    <div class="author_description">
                        <h5 class="author_title" itemprop="name">' . get_the_author() . '</h5>
                
                        <div class="author_bio" itemprop="description">
                            <p>' . $user_description . '</p>
                            
                            <div class="entry-meta author_links">';
			self::author_links_2();
			echo '</div>
                    <a class="author_link" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" rel="author"><i><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 492 492" style="enable-background:new 0 0 492 492;" xml:space="preserve"><g><g><path d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788C492,219.198,479.172,207.418,464.344,207.418z"/></g></g></svg></i> <span>' . esc_html__( 'Read more', 'dentalmed' ) . '</span></a>';

			echo '</div>
                    </div>
                </div>';
		}
	}
}

public static function post_author_3() {

	if ( Clienticabuilder_Core::$page_options['show_post_author'] ) {
		$user_description = get_the_author_meta( 'description' );
		if ( ! empty( $user_description ) ) {
			echo '<div class="author_info vcard" itemscope="" itemtype="https://schema.org/Person">
                    <div class="author_avatar" itemprop="image">' . get_avatar( get_the_author_meta( 'user_email' ), 150 ) . '</div>
                    <div class="author_description">
                        <h5 class="author_title" itemprop="name">' . get_the_author() . '</h5>
                        <div class="author_bio" itemprop="description">
                            <p>' . $user_description . '</p>
                            <div class="entry-meta author_links">';
			self::author_links_3();
			echo '</div>
                    <a class="author_link" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" rel="author"><i></i> <span>' . esc_html__( 'Read more', 'dentalmed' ) . '</span></a>';
			echo '</div>
                    </div>
                </div>';
		}
	}
}

public static function cat_list() {
	// Categories: used between list items, there is a space after the comma.
	if ( Clienticabuilder_Core::$page_options['show_post_category'] ) {
		global $post;
		$category = get_the_category( $post->ID );
		if ( ! empty( $category ) ) {
			echo '<span class="scat"><i></i><a href="/category/' . $category[0]->slug . '/">' . $category[0]->name . ' <span>' . $category[0]->count . '</span></a></span>';
		}
	}
}
public static function cat_list_single() {
	global $post;
	if ( Clienticabuilder_Helpers::is_singular() || is_archive() || Clienticabuilder_Helpers::is_home() ) {
		// Categories: used between list items, there is a space after the comma.
		if ( Clienticabuilder_Core::$page_options['show_post_category'] ) {
			$category = get_the_category( $post->ID );
			if ( ! empty( $category ) ) {
				echo '<span class="scat"><i></i><a href="/category/' . $category[0]->slug . '/">' . $category[0]->name . ' <span>' . $category[0]->count . '</span></a></span>';
			}
		}
	}
}



public static function postmore_query() {
	global $post;
	$rmore = '';
	if ( ! empty( Clienticabuilder_Core::$page_options['read_more_related'] ) ) {
		$rmore = Clienticabuilder_Core::$page_options['read_more_related'];
	}
	$backup = $post;
	$tags   = wp_get_post_tags( $post->ID );
	if ( $tags ) {
		$tag_ids = array();
		foreach ( $tags as $individual_tag ) {
			$tag_ids[] = $individual_tag->term_id;
		}

		$args     = array(
			'tag__in'      => $tag_ids,
			'post__not_in' => array( $post->ID ),
			'showposts'    => 3, // Number of related posts that will be shown.
		);
		$my_query = new WP_Query( $args );
		if ( $my_query->have_posts() ) {
			echo '<div class="related">';
			if ( ! empty( Clienticabuilder_Core::$page_options['related_posts_title'] ) ) {
				echo '<h2>' . esc_html( Clienticabuilder_Core::$page_options['related_posts_title'] ) . '</h2>';
			}

			while ( $my_query->have_posts() ) {
				$my_query->the_post();
				$perm  = get_the_permalink();
				$title = get_the_title();
				echo '<div class="rblock ';
				if ( has_post_thumbnail() ) {
					echo ' has-post-thumbnail';
				}
				echo '">
                        <div class="thumb">';
				if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
					the_post_thumbnail( 'clienticabuilder_rectangle' );
				}
				if ( Clienticabuilder_Core::$page_options['show_post_category'] ) {
					$category = get_the_category( $post->ID );
					if ( ! empty( $category ) ) {
						echo '<span class="scat"><i></i><a href="/category/' . $category[0]->slug . '/">' . $category[0]->name . ' <span>' . $category[0]->count . '</span></a></span>';
					}
				}

				echo '<div><a href="' . $perm . '" rel="bookmark"><img src="' . esc_url( get_template_directory_uri() ) . '/images/eye.svg" alt="' . esc_attr__( 'Full Post', 'dentalmed' ) . '"></a></div>
                        </div>
                        <div class="wr"><h5><a href="', $perm, '" title="', $title, '">', $title, '</a></h5>
<div class="descr">' . get_the_excerpt() . '</div>
                        <div class="entry-meta"><span class="date"><i></i> ', get_the_date(), '</span></div>';

				echo '<a href="', $perm, '" rel="bookmark" class="rmore">', esc_html( $rmore ), '</a>';

				echo '
                        </div>
                    </div>';
			}
			echo '</div>';
		}
	}
	$post = $backup;
	wp_reset_postdata();
}

public static function postmore_query2() {
	global $post;
	$rmore     = '';
	$authorimg = '<span class="post-author">' . get_avatar( get_the_author_meta( 'user_email' ), 40 ) . '  <a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" rel="author">' . get_the_author_meta( 'display_name' ) . '</a></span>';
	if ( ! empty( Clienticabuilder_Core::$page_options['read_more_related'] ) ) {
		$rmore = Clienticabuilder_Core::$page_options['read_more_related'];
	}
	$backup = $post;
	$tags   = wp_get_post_tags( $post->ID );
	if ( $tags ) {
		$tag_ids = array();
		foreach ( $tags as $individual_tag ) {
			$tag_ids[] = $individual_tag->term_id;
		}

		$args     = array(
			'tag__in'      => $tag_ids,
			'post__not_in' => array( $post->ID ),
			'showposts'    => 2, // Number of related posts that will be shown.
		);
		$my_query = new WP_Query( $args );
		if ( $my_query->have_posts() ) {
			echo '<div class="related layout2">';
			if ( ! empty( Clienticabuilder_Core::$page_options['related_posts_title'] ) ) {
				echo '<div class="may"><span>' . esc_html__( 'May also like', 'dentalmed' ) . '</span></div>';
				echo '<h2>' . esc_html( Clienticabuilder_Core::$page_options['related_posts_title'] ) . '</h2>';
			}

			while ( $my_query->have_posts() ) {
				$my_query->the_post();
				$format = get_post_format();
				if ( false === $format ) {
					$format = 'standard';
				}
				$perm  = get_the_permalink();
				$title = get_the_title();
				echo '<div class="rblock ';
				if ( has_post_thumbnail() ) {
					echo ' has-post-thumbnail';
				}
				echo '">';
				if ( has_post_thumbnail() ) {
					echo '<div class="thumb"><a href="' . $perm . '" rel="bookmark"></a>';

					the_post_thumbnail( 'clienticabuilder_rectangle_450' );

					echo '<div class="over">';
					if ( 'standard' == $format ) {

					} elseif ( 'video' == $format ) {
						echo '<div><img src="' . get_template_directory_uri() . '/images/play.svg" alt="' . $title . '"></div>';
					} elseif ( 'audio' == $format ) {
						echo '<div><img src="' . get_template_directory_uri() . '/images/volume.svg" alt="' . $title . '"></div>';
					} elseif ( 'gallery' == $format ) {
						echo '<div><img src="' . get_template_directory_uri() . '/images/gallery.svg" alt="' . $title . '"></div>';
					} elseif ( 'quote' == $format ) {
						echo '<div><img src="' . get_template_directory_uri() . '/images/quote.svg" alt="' . $title . '"></div>';
					} elseif ( 'link' == $format ) {
						echo '<div><img src="' . get_template_directory_uri() . '/images/link.svg" alt="' . $title . '"></div>';
					}
					echo '</div>';
					echo '</div>';
				}

				echo '<div class="wr">';
				$category = get_the_category( $post->ID );
				if ( ! empty( $category ) ) {
					echo '<span class="scat"><i></i><a href="/category/' . $category[0]->slug . '/">' . $category[0]->name . ' <span>' . $category[0]->count . '</span></a></span>';
				}
				echo '<h5 class="entry-title"><a href="' . $perm . '" rel="bookmark">' . $title . '</a></h5>';
				echo '<div class="entry-content">';
				echo wpautop( get_the_excerpt() );
				echo '<div class="entry-meta">' . $authorimg . '<span class="date">' . get_the_date() . '</span></div>';
				echo '<a href="', $perm, '" rel="bookmark" class="rmore">', esc_html( $rmore ), '</a>';
				echo '</div>
                        </div>
                    </div>';
			}
			echo '</div>';
		}
	}
	$post = $backup;
	wp_reset_postdata();
}

public static function postmore_query3() {
	global $post;
	$rmore = '';
	if ( ! empty( Clienticabuilder_Core::$page_options['read_more_related'] ) ) {
		$rmore = Clienticabuilder_Core::$page_options['read_more_related'];
	}
	$backup = $post;
	$tags   = wp_get_post_tags( $post->ID );
	if ( $tags ) {
		$tag_ids = array();
		foreach ( $tags as $individual_tag ) {
			$tag_ids[] = $individual_tag->term_id;
		}

		$args     = array(
			'tag__in'      => $tag_ids,
			'post__not_in' => array( $post->ID ),
			'showposts'    => 2, // Number of related posts that will be shown.
		);
		$my_query = new WP_Query( $args );
		if ( $my_query->have_posts() ) {
			echo '<div class="related layout3">';
			if ( ! empty( Clienticabuilder_Core::$page_options['related_posts_title'] ) ) {
				echo '<h2>' . esc_html( Clienticabuilder_Core::$page_options['related_posts_title'] ) . '</h2>';
			}

			while ( $my_query->have_posts() ) {
				$my_query->the_post();
				$perm  = get_the_permalink();
				$title = get_the_title();
				echo '<div class="rblock ';
				if ( has_post_thumbnail() ) {
					echo ' has-post-thumbnail';
				}
				echo '">';
				echo '<div class="thumb">';
				if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
					the_post_thumbnail( 'clienticabuilder_rectangle' );
				}

				echo '<div><a href="' . $perm . '" rel="bookmark"></a></div>
                        </div>
                        <div class="wr">
                        <div class="entry-meta"><span class="author vcard"><i></i> <a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr__( 'View all posts by', 'dentalmed' ) . get_the_author() . '" rel="author">' . get_the_author() . '</a></span><span class="date"><i></i> ', get_the_date(), '</span><span class="comments-link"><i></i> <a href="' . get_comments_link() . '">' . get_comments_number_text( esc_attr__( 'Comments(0)', 'dentalmed' ), esc_attr__( 'Comments(1)', 'dentalmed' ), esc_attr__( 'Comments(%)', 'dentalmed' ), '' ) . '</a></span></div>
                        <h5><a href="', $perm, '" title="', $title, '">', $title, '</a></h5>
<div class="descr">' . get_the_excerpt() . '</div>
                     ';

				echo '<a href="', $perm, '" rel="bookmark" class="rmore">', esc_html( $rmore ), '</a>';

				echo '
                        </div>
                    </div>';
			}
			echo '</div>';
		}
	}
	$post = $backup;
	wp_reset_postdata();
}

public static function postmore_query4() {
	global $post;
	$backup = $post;
	$tags   = wp_get_post_tags( $post->ID );
	if ( $tags ) {
		$tag_ids = array();
		foreach ( $tags as $individual_tag ) {
			$tag_ids[] = $individual_tag->term_id;
		}

		$args     = array(
			'tag__in'      => $tag_ids,
			'post__not_in' => array( $post->ID ),
			'showposts'    => 2, // Number of related posts that will be shown.
		);
		$my_query = new WP_Query( $args );
		if ( $my_query->have_posts() ) {
			echo '<div class="related layout4">';
			if ( ! empty( Clienticabuilder_Core::$page_options['related_posts_title'] ) ) {
				echo '<h2>' . esc_html( Clienticabuilder_Core::$page_options['related_posts_title'] ) . '</h2>';
			} else {
				echo '<h2>' . esc_html__( "Related Posts", 'dentalmed' ) . '</h2>';
			}

			while ( $my_query->have_posts() ) {
				$my_query->the_post();
				$perm  = get_the_permalink();
				$title = get_the_title();
				echo '<div class="rblock ';
				if ( has_post_thumbnail() ) {
					echo ' has-post-thumbnail';
				}
				echo '">';
				if ( has_post_thumbnail() ) {
					echo '<div class="thumb">';
					echo '<span class="data">' . get_the_date( 'j' ) . '<span>' . get_the_date( 'M' ) . '</span></span>';
					echo get_the_post_thumbnail( '', 'clienticabuilder_rectangle' );
					echo '<div class="thhov"><a href="' . $perm . '" rel="bookmark">';
					echo '<span>+</span>';
					echo '</a></div>';
					echo '</div>';
				}
				echo '<div class="wr">
                        <div class="entry-meta">';
				self::entry_meta_2();
				echo '</div>
                        <h5><a href="', $perm, '" title="', $title, '">', $title, '</a></h5>
                        <div class="descr">' . get_the_excerpt() . '</div>
                     ';


				if ( ! empty( Clienticabuilder_Core::$page_options['read_more_related'] ) ) {
					echo '<a href="' . $perm . '" rel="bookmark" class="rmore">' . esc_html( Clienticabuilder_Core::$page_options['read_more_related'] ) . '</a>';
				}

				echo '
                        </div>
                    </div>';
			}
			echo '</div>';
		}
	}
	$post = $backup;
	wp_reset_postdata();
}

public static function postmore_query5() {
	global $post;
	$rmore = '';
	if ( ! empty( Clienticabuilder_Core::$page_options['read_more_related'] ) ) {
		$rmore = Clienticabuilder_Core::$page_options['read_more_related'];
	}
	$backup = $post;
	$tags   = wp_get_post_tags( $post->ID );
	if ( $tags ) {
		$tag_ids = array();
		foreach ( $tags as $individual_tag ) {
			$tag_ids[] = $individual_tag->term_id;
		}

		$args     = array(
			'tag__in'      => $tag_ids,
			'post__not_in' => array( $post->ID ),
			'showposts'    => 2, // Number of related posts that will be shown.
		);
		$my_query = new WP_Query( $args );
		if ( $my_query->have_posts() ) {
			echo '<div class="related">';
			if ( ! empty( Clienticabuilder_Core::$page_options['related_posts_title'] ) ) {
				echo '<h2>' . esc_html( Clienticabuilder_Core::$page_options['related_posts_title'] ) . '</h2>';
			}

			while ( $my_query->have_posts() ) {
				$my_query->the_post();
				$perm  = get_the_permalink();
				$title = get_the_title();
				echo '<div class="rblock ';
				if ( has_post_thumbnail() ) {
					echo ' has-post-thumbnail';
				}
				echo '">';
				if ( has_post_thumbnail( $post->ID ) ) {
					echo '<div class="thumb">';
					self::cat_list_single();
					echo '<span class="data">' . get_the_date( 'j' ) . '<span>' . get_the_date( 'M' ) . '</span></span>';
					echo get_the_post_thumbnail( '', 'clienticabuilder_rectangle_340' );
					echo '</div>';
				}

				echo '<div class="wr">
                        <div class="entry-meta">';
				self::entry_meta_3();
				echo '</div>
                        <h5><a href="', $perm, '" title="', $title, '">', $title, '</a></h5>
                        <div class="descr">' . get_the_excerpt() . '</div>
                     ';

				echo '<a href="', $perm, '" rel="bookmark" class="rmore">', esc_html( $rmore ), '</a>';

				echo '
                        </div>
                    </div>';
			}
			echo '</div>';
		}
	}
	$post = $backup;
	wp_reset_postdata();
}

public static function postmore_query6() {
	global $post;
	$rmore = '';
	if ( ! empty( Clienticabuilder_Core::$page_options['read_more_related'] ) ) {
		$rmore = Clienticabuilder_Core::$page_options['read_more_related'];
	}
	$backup = $post;
	$tags   = wp_get_post_tags( $post->ID );
	if ( $tags ) {
		$tag_ids = array();
		foreach ( $tags as $individual_tag ) {
			$tag_ids[] = $individual_tag->term_id;
		}

		$args     = array(
			'tag__in'      => $tag_ids,
			'post__not_in' => array( $post->ID ),
			'showposts'    => 2, // Number of related posts that will be shown.
		);
		$my_query = new WP_Query( $args );
		if ( $my_query->have_posts() ) {
			echo '<div class="related">';
			if ( ! empty( Clienticabuilder_Core::$page_options['related_posts_title'] ) ) {
				echo '<h2>' . esc_html( Clienticabuilder_Core::$page_options['related_posts_title'] ) . '</h2>';
			}

			while ( $my_query->have_posts() ) {
				$my_query->the_post();
				$perm  = get_the_permalink();
				$title = get_the_title();
				echo '<div class="rblock ';
				if ( has_post_thumbnail() ) {
					echo ' has-post-thumbnail';
				}
				echo '">';
				if ( has_post_thumbnail( $post->ID ) ) {
					echo '<div class="thumb">';
					echo get_the_post_thumbnail( '', 'clienticabuilder_thumb' );
					echo '</div>';
				}

				echo '<div class="conte">
                            <div class="wrap">
                            <h5><a href="', $perm, '" title="', $title, '">', $title, '</a></h5>
                        <div class="entry-meta">';
				self::cat_list_single();
				self::entry_meta();
				echo '</div>
                        <div class="descr">' . get_the_excerpt() . '</div>
                     ';
				echo '<a href="', $perm, '" rel="bookmark" class="rmore">', esc_html( $rmore ), '</a>';
				echo '</div>';
				echo '
                        </div>
                    </div>';
			}
			echo '</div>';
		}
	}
	$post = $backup;
	wp_reset_postdata();
}

// Read more link for post feed
public static function read_more() {
	if ( Clienticabuilder_Core::$page_options['show_read_more'] ) {
		global $post;
		$text = '';
		if ( ! empty( Clienticabuilder_Core::$page_options['read_more_text'] ) ) {
			$text = Clienticabuilder_Core::$page_options['read_more_text'];
		} else {
			if ( !'product' == $post->post_type ) {
				$text = esc_html__( 'Read more', 'dentalmed' );
			}
		}
		if ( '' !== $text ) {
			echo '<a href="', get_the_permalink( $post ), '" rel="bookmark" class="more">', esc_html( $text ), '<span class="screen-reader-text">', get_the_title( $post ), '</span></a>';
        }
	}
}

/**
 * Print HTML with meta information for current post: categories, comments counter, author, and date.
 *
 * Create your own entry_meta() to override in a child theme.
 *
 */
public static function entry_meta_header() {


	// Post author
	if ( isset( Clienticabuilder_Core::$page_options['show_post_author'] ) ) {
		if ( '0' != Clienticabuilder_Core::$page_options['show_post_author'] ) {
			if ( 'post' === get_post_type() ) {
				$post_id        = get_queried_object_id();
				$post_author_id = get_post_field( 'post_author', $post_id );
				$anick          = get_the_author_meta( 'nickname', $post_author_id );

				$author_url = get_author_posts_url( $post_author_id, $anick );
				echo '<span class="author vcard"><i></i> <a class="url fn n" href="' . esc_url( $author_url ) . '" title="' . esc_attr__( 'View all posts by', 'dentalmed' ) . ' ' . $anick . '" rel="author">' . $anick . '</a></span>';
			}
		}
	} else {
		if ( 'post' === get_post_type() ) {
			$post_id        = get_queried_object_id();
			$post_author_id = get_post_field( 'post_author', $post_id );
			$anick          = get_the_author_meta( 'nickname', $post_author_id );

			$author_url = get_author_posts_url( $post_author_id, $anick );
			echo '<span class="author vcard"><i></i> <a class="url fn n" href="' . esc_url( $author_url ) . '" title="' . esc_attr__( 'View all posts by', 'dentalmed' ) . ' ' . $anick . '" rel="author">' . $anick . '</a></span>';
		}
	}

	//Post data
	echo '<span class="updated"> ', get_the_modified_time( 'F jS, Y h:i a' ), '</span>';
	if ( Clienticabuilder_Core::$page_options['show_post_date'] ) {
		echo '<span class="date"><i></i> ', get_the_date(), '</span>';
	}


	// Comments counter
	if ( Clienticabuilder_Core::$page_options['show_comments_count'] ) {
		if ( comments_open( get_the_ID() ) ) {
			echo '<span class="comments-link"><i></i> ';
			comments_popup_link( esc_html__( 'Add a comment', 'dentalmed' ), esc_html__( '1 Comment', 'dentalmed' ), esc_html__( '% Comments', 'dentalmed' ) );
			echo '</span>';
		}
	}
}
// Author Data Comment
public static function entry_meta() {
	// Post author
	if ( isset( Clienticabuilder_Core::$page_options['show_post_author'] ) ) {
		if ( '0' != Clienticabuilder_Core::$page_options['show_post_author'] ) {
			if ( 'post' === get_post_type() ) {

				echo '<span class="author vcard"><i></i> <a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr__( 'View all posts by', 'dentalmed' ) . get_the_author() . '" rel="author">' . get_the_author() . '</a></span>';
			}
		}
	} else {
		echo '<span class="author vcard"><i></i> <a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr__( 'View all posts by', 'dentalmed' ) . get_the_author() . '" rel="author">' . get_the_author() . '</a></span>';
	}

	//Post data
	echo '<span class="updated"> ', get_the_modified_time( 'F jS, Y h:i a' ), '</span>';
	if ( Clienticabuilder_Core::$page_options['show_post_date'] ) {
		echo '<span class="date"><i></i> ', get_the_date(), '</span>';
	}


	// Comments counter
	if ( Clienticabuilder_Core::$page_options['show_comments_count'] ) {
		if ( comments_open( get_the_ID() ) ) {
			echo '<span class="comments-link"><i></i> ';
			comments_popup_link( esc_html__( 'Add a comment', 'dentalmed' ), esc_html__( '1 Comment', 'dentalmed' ), esc_html__( '% Comments', 'dentalmed' ) );
			echo '</span>';
		}
	}
}
// Data Author CatList Comment
public static function entry_meta_2() {
	global $post;

	//Post data
	echo '<span class="updated"> ', get_the_modified_time( 'F jS, Y h:i a' ), '</span>';
	if ( Clienticabuilder_Core::$page_options['show_post_date'] ) {
		echo '<span class="date"><i></i> ', get_the_date(), '</span>';
	}
	// Post author
	if ( isset( Clienticabuilder_Core::$page_options['show_post_author'] ) ) {
		if ( '0' != Clienticabuilder_Core::$page_options['show_post_author'] ) {
			if ( 'post' === get_post_type() ) {

				echo '<span class="author vcard"><i></i>' . get_avatar( get_the_author_meta( 'user_email' ), 150 ) . ' <a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr__( 'View all posts by', 'dentalmed' ) . get_the_author() . '" rel="author">' . get_the_author() . '</a></span>';
			}
		}
	} else {
		echo '<span class="author vcard"><i></i>' . get_avatar( get_the_author_meta( 'user_email' ), 150 ) . ' <a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr__( 'View all posts by', 'dentalmed' ) . get_the_author() . '" rel="author">' . get_the_author() . '</a></span>';
	}

	// Category
	if ( Clienticabuilder_Core::$page_options['show_post_category'] ) {
		$category = get_the_category( $post->ID );
		if ( ! empty( $category ) ) {
			echo '<span class="cat"><i></i><a href="/category/' . $category[0]->slug . '/">' . $category[0]->name . ' <span>' . $category[0]->count . '</span></a></span>';
		}
	}


	// Comments counter
	if ( Clienticabuilder_Core::$page_options['show_comments_count'] ) {
		if ( comments_open( get_the_ID() ) ) {
			echo '<span class="comments-link"><i></i> ';
			comments_popup_link( esc_html__( 'Add a comment', 'dentalmed' ), esc_html__( '1 Comment', 'dentalmed' ), esc_html__( '% Comments', 'dentalmed' ) );
			echo '</span>';
		}
	}
}
// Data Author Comment
public static function entry_meta_3() {
	//Post data
	echo '<span class="updated"> ', get_the_modified_time( 'F jS, Y h:i a' ), '</span>';
	if ( Clienticabuilder_Core::$page_options['show_post_date'] ) {
		echo '<span class="date"><i></i> ', get_the_date(), '</span>';
	}
	// Post author
	if ( isset( Clienticabuilder_Core::$page_options['show_post_author'] ) ) {
		if ( '0' != Clienticabuilder_Core::$page_options['show_post_author'] ) {
			if ( 'post' === get_post_type() ) {

				echo '<span class="author vcard"><i></i>' . get_avatar( get_the_author_meta( 'user_email' ), 150 ) . ' <a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr__( 'View all posts by', 'dentalmed' ) . get_the_author() . '" rel="author">' . get_the_author() . '</a></span>';
			}
		}
	} else {
		echo '<span class="author vcard"><i></i>' . get_avatar( get_the_author_meta( 'user_email' ), 150 ) . ' <a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" title="' . esc_attr__( 'View all posts by', 'dentalmed' ) . get_the_author() . '" rel="author">' . get_the_author() . '</a></span>';
	}

	// Comments counter
	if ( Clienticabuilder_Core::$page_options['show_comments_count'] ) {
		if ( comments_open( get_the_ID() ) ) {
			echo '<span class="comments-link"><i></i> ';
			comments_popup_link( esc_html__( 'Add a comment', 'dentalmed' ), esc_html__( '1 Comment', 'dentalmed' ), esc_html__( '% Comments', 'dentalmed' ) );
			echo '</span>';
		}
	}
}

/**
 * Display navigation to next/previous post when applicable.
 *
 */
public static function post_nav() {
	global $post;
	$prev_post = get_previous_post();
	$next_post = get_next_post();
	$author    = '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" rel="author">' . get_the_author_meta( 'display_name' ) . '</a>';
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );
	if ( ! $next && ! $previous ) {
		return;
	}


	echo '<nav class="nav-links clearfix">';
	if ( ! empty( $prev_post ) ) {
		?>

        <div class="nav-previous">
			<?php
			echo '<span><a href="' . esc_url( get_permalink( $prev_post->ID ) ) . '"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 512.005 512.005" style="enable-background:new 0 0 512.005 512.005;" xml:space="preserve"><g><g><path d="M458.672,202.653H207.088l77.781-77.781c21.803-21.803,21.803-57.28,0-79.083s-57.28-21.803-79.083,0L3.12,248.456
			c-4.16,4.16-4.16,10.923,0,15.083l202.667,202.667c10.901,10.901,25.216,16.363,39.552,16.363s28.629-5.44,39.552-16.363
			c21.803-21.803,21.803-57.28,0-79.083l-77.803-77.803h251.584c29.397,0,53.333-23.936,53.333-53.333
			S488.069,202.653,458.672,202.653z"/></g></g></svg></a></span>';
			echo '<label>' . esc_html__( 'Previous Post', 'dentalmed' ) . '</label>';
			echo '<h5><a href="' . esc_url( get_permalink( $prev_post->ID ) ) . '">' . esc_html( get_the_title( $prev_post->ID ) ) . '</a></h5>';
			echo '<div class="metadata"><span>' . $author . '</span> <span class="divider">/</span> <span class="date">' . get_the_date( '', $prev_post->ID ) . '</span></div>';
			?>
        </div>
		<?php
	}
	if ( ! empty( $next_post ) ) {
		?>
        <div class="nav-next">
			<?php
			echo '<span><a href="' . esc_url( get_permalink( $next_post->ID ) ) . '"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 512.005 512.005" style="enable-background:new 0 0 512.005 512.005;" xml:space="preserve"><g><g><path d="M458.672,202.653H207.088l77.781-77.781c21.803-21.803,21.803-57.28,0-79.083s-57.28-21.803-79.083,0L3.12,248.456
			c-4.16,4.16-4.16,10.923,0,15.083l202.667,202.667c10.901,10.901,25.216,16.363,39.552,16.363s28.629-5.44,39.552-16.363
			c21.803-21.803,21.803-57.28,0-79.083l-77.803-77.803h251.584c29.397,0,53.333-23.936,53.333-53.333
			S488.069,202.653,458.672,202.653z"/></g></g></svg></a></span>';
			echo '<label>' . esc_html__( 'Next Post', 'dentalmed' ) . '</label>';
			echo '<h5><a href="' . esc_url( get_permalink( $next_post->ID ) ) . '">' . esc_html( get_the_title( $next_post->ID ) ) . '</a></h5>';
			echo '<div class="metadata"><span>' . $author . '</span> <span class="divider">/</span> <span class="date">' . get_the_date( '', $next_post->ID ) . '</span></div>';
			?>
        </div>

		<?php
	}
	echo '</nav>';
}

public static function post_nav_3() {
	global $post;
	$prev_post = get_previous_post();
	$next_post = get_next_post();
	$author    = '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" rel="author">' . get_the_author_meta( 'display_name' ) . '</a>';
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );
	if ( ! $next && ! $previous ) {
		return;
	}


	echo '<nav class="nav-links clearfix">';
	if ( ! empty( $prev_post ) ) {
		?>

        <div class="nav-previous">
			<?php
			if ( has_post_thumbnail( $prev_post->ID ) ) {
				echo '<span><a href="' . esc_url( get_permalink( $prev_post->ID ) ) . '">' . get_the_post_thumbnail( $prev_post->ID, 'clienticabuilder_tiny' ) . '</a></span>';
			}
			echo '<a href="' . esc_url( get_permalink( $prev_post->ID ) ) . '"></a>';
			echo '<label>' . esc_html__( 'Previous Post', 'dentalmed' ) . '</label>';
			echo '<h5><a href="' . esc_url( get_permalink( $prev_post->ID ) ) . '">' . esc_html( get_the_title( $prev_post->ID ) ) . '</a></h5>';
			echo '<div class="metadata"><span>' . $author . '</span> <span class="divider">/</span> <span class="date">' . get_the_date( '', $prev_post->ID ) . '</span></div>';
			?>
        </div>
		<?php
	}
	if ( ! empty( $next_post ) ) {
		?>
        <div class="nav-next">
			<?php
			if ( has_post_thumbnail( $next_post->ID ) ) {
				echo '<span><a href="' . esc_url( get_permalink( $next_post->ID ) ) . '">' . get_the_post_thumbnail( $next_post->ID, 'clienticabuilder_tiny' ) . '</a></span>';
			}
			echo '<a href="' . esc_url( get_permalink( $next_post->ID ) ) . '"></a>';
			echo '<label>' . esc_html__( 'Next Post', 'dentalmed' ) . '</label>';
			echo '<h5><a href="' . esc_url( get_permalink( $next_post->ID ) ) . '">' . esc_html( get_the_title( $next_post->ID ) ) . '</a></h5>';
			echo '<div class="metadata"><span>' . $author . '</span> <span class="divider">/</span> <span class="date">' . get_the_date( '', $next_post->ID ) . '</span></div>';
			?>
        </div>

		<?php
	}
	echo '</nav>';
}

public static function post_nav_2() {
	global $post;
	$prev_post = get_previous_post();
	$next_post = get_next_post();
	$author    = '<a href="' . get_author_posts_url( get_the_author_meta( 'ID' ) ) . '" rel="author">' . get_the_author_meta( 'display_name' ) . '</a>';
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );
	if ( ! $next && ! $previous ) {
		return;
	}


	echo '<nav class="nav-links skin2 clearfix">';
	if ( ! empty( $prev_post ) ) {
		?>

        <div class="nav-previous">
			<?php
			echo get_the_post_thumbnail( $prev_post->ID, 'clienticabuilder_thumb' ) . '<span><a href="' . esc_url( get_permalink( $prev_post->ID ) ) . '"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 492 492" style="enable-background:new 0 0 492 492;" xml:space="preserve"><g><g><path d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788C492,219.198,479.172,207.418,464.344,207.418z"/></g></g></svg></a></span>';
			echo '<label>' . esc_html__( 'Previous Post', 'dentalmed' ) . '</label>';
			echo '<h5><a href="' . esc_url( get_permalink( $prev_post->ID ) ) . '">' . esc_html( get_the_title( $prev_post->ID ) ) . '</a></h5>';
			echo '<div class="metadata"><span>' . $author . '</span> <span class="divider">/</span> <span class="date">' . get_the_date( '', $prev_post->ID ) . '</span></div>';
			echo '<a href="' . esc_url( get_permalink( $prev_post->ID ) ) . '"></a>';
			?>
        </div>
		<?php
	}
	if ( ! empty( $next_post ) ) {
		?>
        <div class="nav-next">
			<?php
			echo get_the_post_thumbnail( $next_post->ID, 'clienticabuilder_thumb' ) . '<span><a href="' . esc_url( get_permalink( $next_post->ID ) ) . '"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 492 492" style="enable-background:new 0 0 492 492;" xml:space="preserve"><g><g><path d="M464.344,207.418l0.768,0.168H135.888l103.496-103.724c5.068-5.064,7.848-11.924,7.848-19.124c0-7.2-2.78-14.012-7.848-19.088L223.28,49.538c-5.064-5.064-11.812-7.864-19.008-7.864c-7.2,0-13.952,2.78-19.016,7.844L7.844,226.914C2.76,231.998-0.02,238.77,0,245.974c-0.02,7.244,2.76,14.02,7.844,19.096l177.412,177.412c5.064,5.06,11.812,7.844,19.016,7.844c7.196,0,13.944-2.788,19.008-7.844l16.104-16.112c5.068-5.056,7.848-11.808,7.848-19.008c0-7.196-2.78-13.592-7.848-18.652L134.72,284.406h329.992c14.828,0,27.288-12.78,27.288-27.6v-22.788C492,219.198,479.172,207.418,464.344,207.418z"/></g></g></svg></a></span>';
			echo '<label>' . esc_html__( 'Next Post', 'dentalmed' ) . '</label>';
			echo '<h5><a href="' . esc_url( get_permalink( $next_post->ID ) ) . '">' . esc_html( get_the_title( $next_post->ID ) ) . '</a></h5>';
			echo '<div class="metadata"><span>' . $author . '</span> <span class="divider">/</span> <span class="date">' . get_the_date( '', $next_post->ID ) . '</span></div>';
			echo '<a href="' . esc_url( get_permalink( $next_post->ID ) ) . '"></a>';
			?>
        </div>

		<?php
	}
	echo '</nav>';
}

/* Prev & Next Post Narrow Icons */
/**
 * Display navigation to next/previous post when applicable.
 *
 */
public function post_navicon() {
	global $post;

	// Don't print empty markup if there's nowhere to navigate.
	$iprevious = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
	$inext     = get_adjacent_post( false, '', false );

	if ( ! $inext && ! $iprevious ) {
		return;
	}
	?>
    <div class="post-navigation-icon">
		<?php next_post_link( '%link', '<span class="icon-arrow-left22"></span>', true ); ?>
		<?php previous_post_link( '%link', '<span class="icon-arrow-right22"></span>', true ); ?>
    </div>
	<?php
}

// Display of CSS class, for column option of tag, category, archive and index page
public static function number_of_columns() {

	if ( ! Clienticabuilder_Helpers::is_single() && ! is_page() ) {
		echo ' ' . ' column' . Clienticabuilder_Core::$page_options['blog-columns'];

		if ( isset( Clienticabuilder_Core::$page_options['blog-sidebar_layout'] ) ) {
			$sl_blog_sidebars = Clienticabuilder_Core::$page_options['blog-sidebar_layout'];
			if ( '3' === $sl_blog_sidebars || '4' === $sl_blog_sidebars ) {
				echo ' sb';
			} elseif ( '2' === $sl_blog_sidebars ) {
				echo ' sbs';
			}
		}

		echo ' alayout' . Clienticabuilder_Core::$page_options['archive_template'];
	}

	if ( Clienticabuilder_Helpers::is_single() ) {
		echo ' slayout' . Clienticabuilder_Core::$page_options['single_template'];
	}
}

// Select Thumb Size depends on post caategory wide
public static function thumb_size() {
	if ( ! Clienticabuilder_Helpers::is_single() ) {
		$sl_blog_sidebars = Clienticabuilder_Core::$page_options['blog-sidebar_layout'];
		if ( '2' === $sl_blog_sidebars || '1' === $sl_blog_sidebars ) {
			echo get_the_post_thumbnail( '', 'clienticabuilder_rectangle' );
		} elseif ( '3' === $sl_blog_sidebars || '4' === $sl_blog_sidebars ) {
			echo get_the_post_thumbnail( '', 'clienticabuilder_rectangle_big' );
		} else {
			echo get_the_post_thumbnail();
		}
	}
}

// Tags for blog post
public static function tags() {


	//tags
	if ( Clienticabuilder_Core::$page_options['show_post_tags'] ) {
		$tag_list = get_the_tag_list( '', ' ' );
		if ( $tag_list ) {
			echo '<span class="tags-links"><b>', esc_html__( 'Tags', 'dentalmed' ), ':</b> ', $tag_list, '</span>';
		}
	}

}
// Share buttons for blog post
public static function share() {

	//share
	if ( Clienticabuilder_Core::$page_options['show_post_share'] ) {
		$perm  = get_the_permalink();
		$title = get_the_title();
		echo '<span class="sharing">', esc_html__( 'Share it', 'dentalmed' ) . ':';
		echo ' <a href="https://www.facebook.com/sharer.php?u=' . $perm . '" target="_blank"><svg enable-background="new 0 0 24 24" height="10"  viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m15.997 3.985h2.191v-3.816c-.378-.052-1.678-.169-3.192-.169-3.159 0-5.323 1.987-5.323 5.639v3.361h-3.486v4.266h3.486v10.734h4.274v-10.733h3.345l.531-4.266h-3.877v-2.939c.001-1.233.333-2.077 2.051-2.077z"/></svg></a>';
		echo ' <a href="https://twitter.com/intent/tweet?text=' . $title . '&url=' . $perm . '" target="_blank"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" height="10" width="10" xml:space="preserve"><g><g><path d="M512,97.248c-19.04,8.352-39.328,13.888-60.48,16.576c21.76-12.992,38.368-33.408,46.176-58.016c-20.288,12.096-42.688,20.64-66.56,25.408C411.872,60.704,384.416,48,354.464,48c-58.112,0-104.896,47.168-104.896,104.992c0,8.32,0.704,16.32,2.432,23.936c-87.264-4.256-164.48-46.08-216.352-109.792c-9.056,15.712-14.368,33.696-14.368,53.056c0,36.352,18.72,68.576,46.624,87.232c-16.864-0.32-33.408-5.216-47.424-12.928c0,0.32,0,0.736,0,1.152c0,51.008,36.384,93.376,84.096,103.136c-8.544,2.336-17.856,3.456-27.52,3.456c-6.72,0-13.504-0.384-19.872-1.792c13.6,41.568,52.192,72.128,98.08,73.12c-35.712,27.936-81.056,44.768-130.144,44.768c-8.608,0-16.864-0.384-25.12-1.44C46.496,446.88,101.6,464,161.024,464c193.152,0,298.752-160,298.752-298.688c0-4.64-0.16-9.12-0.384-13.568C480.224,136.96,497.728,118.496,512,97.248z"/></g></g></svg></a>';
		echo ' <a href="https://pinterest.com/pin/create/button/?url=' . $perm . '&media=' . get_the_post_thumbnail_url() . '&description=' . $title . '" target="_blank"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" height="10" width="10" x="0px" y="0px" viewBox="0 0 511.977 511.977" style="enable-background:new 0 0 511.977 511.977;" xml:space="preserve">
<g>
	<g>
		<path d="M262.948,0C122.628,0,48.004,89.92,48.004,187.968c0,45.472,25.408,102.176,66.08,120.16
			c6.176,2.784,9.536,1.6,10.912-4.128c1.216-4.352,6.56-25.312,9.152-35.2c0.8-3.168,0.384-5.92-2.176-8.896
			c-13.504-15.616-24.224-44.064-24.224-70.752c0-68.384,54.368-134.784,146.88-134.784c80,0,135.968,51.968,135.968,126.304
			c0,84-44.448,142.112-102.208,142.112c-31.968,0-55.776-25.088-48.224-56.128c9.12-36.96,27.008-76.704,27.008-103.36
			c0-23.904-13.504-43.68-41.088-43.68c-32.544,0-58.944,32.224-58.944,75.488c0,27.488,9.728,46.048,9.728,46.048
			S144.676,371.2,138.692,395.488c-10.112,41.12,1.376,107.712,2.368,113.44c0.608,3.168,4.16,4.16,6.144,1.568
			c3.168-4.16,42.08-59.68,52.992-99.808c3.968-14.624,20.256-73.92,20.256-73.92c10.72,19.36,41.664,35.584,74.624,35.584
			c98.048,0,168.896-86.176,168.896-193.12C463.62,76.704,375.876,0,262.948,0z"/>
	</g>
</g>
</svg></a>';
		echo ' <a href="https://www.linkedin.com/shareArticle?url=' . $perm . '" target="_blank"><svg enable-background="new 0 0 24 24" height="10" width="10" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m23.994 24v-.001h.006v-8.802c0-4.306-.927-7.623-5.961-7.623-2.42 0-4.044 1.328-4.707 2.587h-.07v-2.185h-4.773v16.023h4.97v-7.934c0-2.089.396-4.109 2.983-4.109 2.549 0 2.587 2.384 2.587 4.243v7.801z"/><path d="m.396 7.977h4.976v16.023h-4.976z"/><path d="m2.882 0c-1.591 0-2.882 1.291-2.882 2.882s1.291 2.909 2.882 2.909 2.882-1.318 2.882-2.909c-.001-1.591-1.292-2.882-2.882-2.882z"/></svg></a>';
		echo '</span>';
	}
}

public static function share_2() {

	//share
	if ( Clienticabuilder_Core::$page_options['show_post_share'] ) {
		$perm  = get_the_permalink();
		$title = get_the_title();
		echo '<span class="sharing"><label><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M503.691 189.836L327.687 37.851C312.281 24.546 288 35.347 288 56.015v80.053C127.371 137.907 0 170.1 0 322.326c0 61.441 39.581 122.309 83.333 154.132 13.653 9.931 33.111-2.533 28.077-18.631C66.066 312.814 132.917 274.316 288 272.085V360c0 20.7 24.3 31.453 39.687 18.164l176.004-152c11.071-9.562 11.086-26.753 0-36.328z"></path></svg> ', esc_html__( 'Share This Post: ', 'dentalmed' ) . ': </label>';
		echo '<a href="https://www.facebook.com/sharer.php?u=' . $perm . '" target="_blank"><svg enable-background="new 0 0 24 24" height="10"  viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m15.997 3.985h2.191v-3.816c-.378-.052-1.678-.169-3.192-.169-3.159 0-5.323 1.987-5.323 5.639v3.361h-3.486v4.266h3.486v10.734h4.274v-10.733h3.345l.531-4.266h-3.877v-2.939c.001-1.233.333-2.077 2.051-2.077z"/></svg></a>';
		echo ' <a href="https://twitter.com/intent/tweet?text=' . $title . '&url=' . $perm . '" target="_blank"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" height="10" width="10" xml:space="preserve"><g><g><path d="M512,97.248c-19.04,8.352-39.328,13.888-60.48,16.576c21.76-12.992,38.368-33.408,46.176-58.016c-20.288,12.096-42.688,20.64-66.56,25.408C411.872,60.704,384.416,48,354.464,48c-58.112,0-104.896,47.168-104.896,104.992c0,8.32,0.704,16.32,2.432,23.936c-87.264-4.256-164.48-46.08-216.352-109.792c-9.056,15.712-14.368,33.696-14.368,53.056c0,36.352,18.72,68.576,46.624,87.232c-16.864-0.32-33.408-5.216-47.424-12.928c0,0.32,0,0.736,0,1.152c0,51.008,36.384,93.376,84.096,103.136c-8.544,2.336-17.856,3.456-27.52,3.456c-6.72,0-13.504-0.384-19.872-1.792c13.6,41.568,52.192,72.128,98.08,73.12c-35.712,27.936-81.056,44.768-130.144,44.768c-8.608,0-16.864-0.384-25.12-1.44C46.496,446.88,101.6,464,161.024,464c193.152,0,298.752-160,298.752-298.688c0-4.64-0.16-9.12-0.384-13.568C480.224,136.96,497.728,118.496,512,97.248z"/></g></g></svg></a>';
		echo ' <a href="https://pinterest.com/pin/create/button/?url=' . $perm . '&media=' . get_the_post_thumbnail_url() . '&description=' . $title . '" target="_blank"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" height="10" width="10" x="0px" y="0px" viewBox="0 0 511.977 511.977" style="enable-background:new 0 0 511.977 511.977;" xml:space="preserve">
<g>
	<g>
		<path d="M262.948,0C122.628,0,48.004,89.92,48.004,187.968c0,45.472,25.408,102.176,66.08,120.16
			c6.176,2.784,9.536,1.6,10.912-4.128c1.216-4.352,6.56-25.312,9.152-35.2c0.8-3.168,0.384-5.92-2.176-8.896
			c-13.504-15.616-24.224-44.064-24.224-70.752c0-68.384,54.368-134.784,146.88-134.784c80,0,135.968,51.968,135.968,126.304
			c0,84-44.448,142.112-102.208,142.112c-31.968,0-55.776-25.088-48.224-56.128c9.12-36.96,27.008-76.704,27.008-103.36
			c0-23.904-13.504-43.68-41.088-43.68c-32.544,0-58.944,32.224-58.944,75.488c0,27.488,9.728,46.048,9.728,46.048
			S144.676,371.2,138.692,395.488c-10.112,41.12,1.376,107.712,2.368,113.44c0.608,3.168,4.16,4.16,6.144,1.568
			c3.168-4.16,42.08-59.68,52.992-99.808c3.968-14.624,20.256-73.92,20.256-73.92c10.72,19.36,41.664,35.584,74.624,35.584
			c98.048,0,168.896-86.176,168.896-193.12C463.62,76.704,375.876,0,262.948,0z"/>
	</g>
</g>
</svg></a>';
		echo ' <a href="https://www.linkedin.com/shareArticle?url=' . $perm . '" target="_blank"><svg enable-background="new 0 0 24 24" height="10" width="10" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m23.994 24v-.001h.006v-8.802c0-4.306-.927-7.623-5.961-7.623-2.42 0-4.044 1.328-4.707 2.587h-.07v-2.185h-4.773v16.023h4.97v-7.934c0-2.089.396-4.109 2.983-4.109 2.549 0 2.587 2.384 2.587 4.243v7.801z"/><path d="m.396 7.977h4.976v16.023h-4.976z"/><path d="m2.882 0c-1.591 0-2.882 1.291-2.882 2.882s1.291 2.909 2.882 2.909 2.882-1.318 2.882-2.909c-.001-1.591-1.292-2.882-2.882-2.882z"/></svg></a>';
		echo '</span>';
	}

}
// Comment layout
public static function comment( $comment, $args, $depth ) {
switch ( $comment->comment_type ) {
case 'pingback':
?>
<li class="trackback comment-content">
    <p><?php esc_html_e( 'Trackback:', 'dentalmed' ); ?><?php comment_author_link(); ?><?php edit_comment_link( esc_html__( 'Edit', 'dentalmed' ), '<span class="edit-link">', '<span>' ); ?></p>
	<?php
	break;
	case 'trackback':
	?>
<li class="pingback comment-content">
    <p><?php esc_html_e( 'Pingback:', 'dentalmed' ); ?><?php comment_author_link(); ?><?php edit_comment_link( esc_html__( 'Edit', 'dentalmed' ), '<span class="edit-link">', '<span>' ); ?></p>
	<?php
	break;
	default:
		$c                  = $GLOBALS['comment'];
		$GLOBALS['comment'] = $comment;
		echo '<li ';
		comment_class();
		echo ' ', 'id="li-comment-';
		comment_ID();
		echo '">';
		echo '<div id="comment-';
		comment_ID();
		echo '">';
		echo '<div class="comment-author vcard">', get_avatar( $comment, $args['avatar_size'] ), '</div>';
		if ( '0' === $comment->comment_approved ) {
			echo '<em>', esc_html__( 'Your comment is awaiting moderation.', 'dentalmed' ), '</em><br />';
		}
		echo '<footer class="comment-meta">';
		echo '<div class="comment-metadata">';
		echo '<span class="fn"> ' . get_comment_author_link() . '</span> <span class="date"><i></i> <a href="' . get_comment_link() . '">' . get_comment_date() . ' ' . esc_html__( 'at', 'dentalmed' ) . ' ' . get_comment_time() . '</a></span> ';
		echo '<span class="reply">';
		comment_reply_link( array_merge( $args, array(
			'reply_text' => '' . esc_html__( "Reply", 'dentalmed' ),
			'depth'      => $depth,
			'max_depth'  => $args['max_depth']
		) ) );
		echo '</span>';
		edit_post_link( esc_html__( 'Edit', 'dentalmed' ), '<span class="edit-link"><i></i> ', '</span>' );

		echo '</div>
        </footer>';
		echo '<div class="comment-content">';
		comment_text();

		echo '</div>';
		echo '
    </div>';
		$GLOBALS['comment'] = $c;
		break;
	}

	}

	public static function get_tag( $text, $tag_start, $tag_end = '' ) {
		$val       = '';
		$pos_start = strpos( $text, $tag_start );
		if ( false !== $pos_start ) {
			$pos_end = $tag_end ? strpos( $text, $tag_end, $pos_start ) : false;
			if ( false === $pos_end ) {
				$tag_end = substr( $tag_start, 0, 1 ) == '<' ? '>' : ']';
				$pos_end = strpos( $text, $tag_end, $pos_start );
			}
			$val = substr( $text, $pos_start, $pos_end + strlen( $tag_end ) - $pos_start );
		}

		return $val;
	}

	public static function get_tag_attrib( $text, $tag, $attr ) {
		$val       = '';
		$pos_start = strpos( $text, substr( $tag, 0, strlen( $tag ) - 1 ) );
		if ( false !== $pos_start ) {
			$pos_end  = strpos( $text, substr( $tag, - 1, 1 ), $pos_start );
			$pos_attr = strpos( $text, ' ' . ( $attr ) . '=', $pos_start );
			if ( false !== $pos_attr && $pos_attr < $pos_end ) {
				$pos_attr  += strlen( $attr ) + 3;
				$pos_quote = strpos( $text, substr( $text, $pos_attr - 1, 1 ), $pos_attr );
				$val       = substr( $text, $pos_attr, $pos_quote - $pos_attr );
			}
		}

		return $val;
	}
	public static function get_post_iframe( $post_text = '', $src = true ) {
		global $post;
		$img = '';
		$tag = Clienticabuilder_Blog::get_embed_video_tag_name();
		if ( empty( $post_text ) ) {
			$post_text = $post->post_content;
		}
		if ( $src ) {
			if ( preg_match_all( '/<' . esc_html( $tag ) . '.+src=[\'"]([^\'"]+)[\'"][^>]*>/i', $post_text, $matches ) ) {
				$img = $matches[1][0];
			}
		} else {
			$img = Clienticabuilder_Blog::get_tag( $post_text, '<' . esc_html( $tag ), '</' . esc_html( $tag ) . '>' );
		}

		return $img;
	}

	public static function get_embed_video_tag_name() {
		return 'iframe';
	}

	public static function show_layout( $str, $before = '', $after = '' ) {
		if ( trim( $str ) != '' ) {
			printf( '%s%s%s', $before, $str, $after );
		}
	}

	// Author links
	public static function author_links() {

		if ( get_the_author_meta( 'facebook' ) ) : ?><a target="_blank" class="author-social"
                                                        href="https://facebook.com/<?php echo the_author_meta( 'facebook' ); ?>">
                Facebook</a><?php endif;
		if ( get_the_author_meta( 'twitter' ) ) : ?><a target="_blank" class="author-social"
                                                       href="https://twitter.com/<?php echo the_author_meta( 'twitter' ); ?>">
                Twitter</a><?php endif;
		if ( get_the_author_meta( 'instagram' ) ) : ?><a target="_blank" class="author-social"
                                                         href="https://instagram.com/<?php echo the_author_meta( 'instagram' ); ?>">
                Instagram</a><?php endif;
		if ( get_the_author_meta( 'pinterest' ) ) : ?><a target="_blank" class="author-social"
                                                         href="https://pinterest.com/<?php echo the_author_meta( 'pinterest' ); ?>">
                Pinterest</a><?php endif;
		if ( get_the_author_meta( 'tumblr' ) ) : ?><a target="_blank" class="author-social"
                                                      href="https://<?php echo the_author_meta( 'tumblr' ); ?>.tumblr.com/">
                Tumblr</a><?php endif;
	}
	//bold icons
	public static function author_links_2() {

		if ( get_the_author_meta( 'facebook' ) ) : ?><a target="_blank" class="author-social"
                                                        href="https://facebook.com/<?php echo the_author_meta( 'facebook' ); ?>">
                <svg enable-background="new 0 0 24 24" height="10" viewBox="0 0 24 24"
                     xmlns="http://www.w3.org/2000/svg">
                    <path d="m15.997 3.985h2.191v-3.816c-.378-.052-1.678-.169-3.192-.169-3.159 0-5.323 1.987-5.323 5.639v3.361h-3.486v4.266h3.486v10.734h4.274v-10.733h3.345l.531-4.266h-3.877v-2.939c.001-1.233.333-2.077 2.051-2.077z"/>
                </svg></a><?php endif;
		if ( get_the_author_meta( 'twitter' ) ) : ?><a target="_blank" class="author-social"
                                                       href="https://twitter.com/<?php echo the_author_meta( 'twitter' ); ?>">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"
                     style="enable-background:new 0 0 512 512;" height="15" width="15" xml:space="preserve"><g>
                        <g>
                            <path d="M512,97.248c-19.04,8.352-39.328,13.888-60.48,16.576c21.76-12.992,38.368-33.408,46.176-58.016c-20.288,12.096-42.688,20.64-66.56,25.408C411.872,60.704,384.416,48,354.464,48c-58.112,0-104.896,47.168-104.896,104.992c0,8.32,0.704,16.32,2.432,23.936c-87.264-4.256-164.48-46.08-216.352-109.792c-9.056,15.712-14.368,33.696-14.368,53.056c0,36.352,18.72,68.576,46.624,87.232c-16.864-0.32-33.408-5.216-47.424-12.928c0,0.32,0,0.736,0,1.152c0,51.008,36.384,93.376,84.096,103.136c-8.544,2.336-17.856,3.456-27.52,3.456c-6.72,0-13.504-0.384-19.872-1.792c13.6,41.568,52.192,72.128,98.08,73.12c-35.712,27.936-81.056,44.768-130.144,44.768c-8.608,0-16.864-0.384-25.12-1.44C46.496,446.88,101.6,464,161.024,464c193.152,0,298.752-160,298.752-298.688c0-4.64-0.16-9.12-0.384-13.568C480.224,136.96,497.728,118.496,512,97.248z"/>
                        </g>
                    </g></svg></a><?php endif;
		if ( get_the_author_meta( 'instagram' ) ) : ?><a target="_blank" class="author-social"
                                                         href="https://instagram.com/<?php echo the_author_meta( 'instagram' ); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512">
                    <path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"></path>
                </svg></a><?php endif;
		if ( get_the_author_meta( 'pinterest' ) ) : ?><a target="_blank" class="author-social"
                                                         href="https://pinterest.com/<?php echo the_author_meta( 'pinterest' ); ?>">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" height="14" width="14" x="0px" y="0px"
                     viewBox="0 0 511.977 511.977" style="enable-background:new 0 0 511.977 511.977;"
                     xml:space="preserve">
<g>
    <g>
        <path d="M262.948,0C122.628,0,48.004,89.92,48.004,187.968c0,45.472,25.408,102.176,66.08,120.16
			c6.176,2.784,9.536,1.6,10.912-4.128c1.216-4.352,6.56-25.312,9.152-35.2c0.8-3.168,0.384-5.92-2.176-8.896
			c-13.504-15.616-24.224-44.064-24.224-70.752c0-68.384,54.368-134.784,146.88-134.784c80,0,135.968,51.968,135.968,126.304
			c0,84-44.448,142.112-102.208,142.112c-31.968,0-55.776-25.088-48.224-56.128c9.12-36.96,27.008-76.704,27.008-103.36
			c0-23.904-13.504-43.68-41.088-43.68c-32.544,0-58.944,32.224-58.944,75.488c0,27.488,9.728,46.048,9.728,46.048
			S144.676,371.2,138.692,395.488c-10.112,41.12,1.376,107.712,2.368,113.44c0.608,3.168,4.16,4.16,6.144,1.568
			c3.168-4.16,42.08-59.68,52.992-99.808c3.968-14.624,20.256-73.92,20.256-73.92c10.72,19.36,41.664,35.584,74.624,35.584
			c98.048,0,168.896-86.176,168.896-193.12C463.62,76.704,375.876,0,262.948,0z"/>
    </g>
</g>
</svg></a><?php endif;
		if ( get_the_author_meta( 'tumblr' ) ) : ?><a target="_blank" class="author-social"
                                                      href="https://<?php echo the_author_meta( 'tumblr' ); ?>.tumblr.com/">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" height="11" width="11">
                    <path d="M309.8 480.3c-13.6 14.5-50 31.7-97.4 31.7-120.8 0-147-88.8-147-140.6v-144H17.9c-5.5 0-10-4.5-10-10v-68c0-7.2 4.5-13.6 11.3-16 62-21.8 81.5-76 84.3-117.1.8-11 6.5-16.3 16.1-16.3h70.9c5.5 0 10 4.5 10 10v115.2h83c5.5 0 10 4.4 10 9.9v81.7c0 5.5-4.5 10-10 10h-83.4V360c0 34.2 23.7 53.6 68 35.8 4.8-1.9 9-3.2 12.7-2.2 3.5.9 5.8 3.4 7.4 7.9l22 64.3c1.8 5 3.3 10.6-.4 14.5z"></path>
                </svg></a><?php endif;
	}
	// outline icons
	public static function author_links_3() {

		if ( get_the_author_meta( 'facebook' ) ) : ?><a target="_blank" class="author-social"
                                                        href="https://facebook.com/<?php echo the_author_meta( 'facebook' ); ?>">
                <svg viewBox="0 0 12 19" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                          d="M4.15188 2.00589C5.13389 1.10572 6.46578 0.600006 7.85455 0.600006H10.4727C10.9547 0.600006 11.3455 0.958178 11.3455 1.40001V4.60001C11.3455 5.04183 10.9547 5.40001 10.4727 5.40001H7.85455V7.00001H10.4727C10.7415 7.00001 10.9952 7.11351 11.1606 7.30768C11.326 7.50185 11.3846 7.75504 11.3194 7.99404L10.4467 11.194C10.3495 11.5502 10.0005 11.8 9.6 11.8H7.85455V17.4C7.85455 17.8418 7.46381 18.2 6.98182 18.2H3.49091C3.00892 18.2 2.61818 17.8418 2.61818 17.4V11.8H0.872727C0.390733 11.8 0 11.4418 0 11V7.80001C0 7.35818 0.390733 7.00001 0.872727 7.00001H2.61818V5.40001C2.61818 4.12697 3.16987 2.90607 4.15188 2.00589ZM7.85455 2.20001C6.9287 2.20001 6.04077 2.53715 5.3861 3.13726C4.73143 3.73738 4.36364 4.55131 4.36364 5.40001V7.80001C4.36364 8.24184 3.9729 8.60001 3.49091 8.60001H1.74545V10.2H3.49091C3.9729 10.2 4.36364 10.5582 4.36364 11V16.6H6.10909V11C6.10909 10.5582 6.49983 10.2 6.98182 10.2H8.9186L9.35496 8.60001H6.98182C6.49983 8.60001 6.10909 8.24184 6.10909 7.80001V5.40001C6.10909 4.97566 6.29299 4.56869 6.62032 4.26864C6.94766 3.96858 7.39162 3.80001 7.85455 3.80001H9.6V2.20001H7.85455Z"/>
                </svg>
            </a><?php endif;
		if ( get_the_author_meta( 'twitter' ) ) : ?><a target="_blank" class="author-social"
                                                       href="https://twitter.com/<?php echo the_author_meta( 'twitter' ); ?>">
                <svg viewBox="0 -45 512.00013 512" xmlns="http://www.w3.org/2000/svg">
                    <path d="m194.816406 422.710938c-85.453125 0-152.992187-14.929688-185.304687-40.953126l-.628907-.507812-.570312-.570312c-7.675781-7.679688-10.191406-17.753907-6.894531-27.636719l.300781-.820313c4-10.003906 13.800781-16.738281 24.421875-16.800781 21.859375-.378906 40.984375-2.984375 58.339844-8.042969-27.683594-12.875-46.914063-35.167968-58.355469-67.433594-3.847656-10.0625-.527344-21.21875 8.339844-27.871093 2.1875-1.644531 4.660156-2.886719 7.277344-3.71875-15.382813-17.757813-26.746094-37.964844-33.109376-59.335938l-.199218-.664062-.136719-.679688c-2.160156-10.808593 2.671875-21.921875 11.527344-26.707031 3.714843-2.132812 7.75-3.238281 11.800781-3.332031-4.367188-9.40625-7.542969-19.0625-9.425781-28.777344-5.226563-26.921875-.914063-53.910156 12.8125-80.214844l3.175781-6.351562c2.542969-5.082031 7.402344-8.652344 13.003906-9.5625 5.601563-.90625 11.34375.945312 15.355469 4.957031l5.785156 5.792969c45.703125 47.914062 86.640625 70.648437 157.417969 86.203125 3.160156-27.167969 14.90625-52.421875 33.855469-72.296875 22.550781-23.648438 52.664062-36.917969 84.792969-37.371094h.210937c23.441406 0 52.519531 13.382813 70.105469 22.820313 15.085937-4.9375 33.261718-12.582032 52.121094-20.664063 8.824218-4.140625 19.703124-2.2460938 26.640624 4.691406 6.800782 6.800781 8.6875 16.390625 5.078126 25.710938-1.371094 3.816406-2.925782 7.5625-4.65625 11.226562 2.582031 1.183594 4.945312 2.789063 6.941406 4.785157 6.035156 6.035156 8.550781 15.480468 6.40625 24.066406l-.230469.816406c-7.226563 23.289062-21.109375 42.257812-39.46875 54.164062-3.066406 163.285157-126.027344 295.078126-276.730469 295.078126zm-156.511718-57.675782c30.449218 17.226563 88.476562 27.648438 156.511718 27.648438 65.410156 0 127.136719-28.082032 173.804688-79.074219 47.050781-51.410156 72.960937-119.679687 72.960937-192.234375v-.816406c0-6.570313 3.617188-12.566406 9.4375-15.652344 11.808594-6.253906 21.371094-16.90625 27.589844-30.527344-6.414063 1.011719-12.933594-1.5625-16.929687-6.929687-4.644532-6.238281-4.695313-14.664063-.128907-20.957031 2.464844-3.398438 4.699219-6.933594 6.691407-10.589844-16.285157 6.839844-31.75 12.972656-45.175782 17.046875-4.878906 1.476562-10.316406.898437-14.773437-1.589844-23.902344-13.316406-46.164063-21.277344-59.585938-21.316406-49.527343.757812-89.796875 43.175781-89.796875 94.605469 0 5.316406-2.359375 10.300781-6.464844 13.679687-4.109374 3.375-9.453124 4.726563-14.671874 3.695313-81.609376-16.078126-129.96875-40.1875-180.257813-90.722657-7.207031 17.269531-9.175781 34.664063-5.84375 51.839844 3.378906 17.398437 12.367187 34.832031 25.996094 50.414063 5.179687 5.914062 5.867187 14.375 1.710937 21.050781-4.140625 6.652343-12.011718 9.761719-19.578125 7.734375-5.914062-1.585938-11.351562-3.667969-16.507812-6.34375 10.503906 22.816406 28.570312 43.917968 51.28125 59.480468 6.582031 4.511719 9.332031 12.921876 6.691406 20.453126-2.644531 7.542968-10.03125 12.398437-18.015625 11.804687-8.699219-.644531-16.40625-2.296875-23.5-5.082031 12.734375 25.933594 33.082031 40.203125 64.429688 45.65625 7.464843 1.300781 13.277343 7.195312 14.464843 14.667968 1.191407 7.472657-2.507812 14.878907-9.199219 18.429688-26.71875 14.164062-55.921874 21.765625-91.140624 23.628906zm0 0"/>
                </svg></a><?php endif;
		if ( get_the_author_meta( 'instagram' ) ) : ?><a target="_blank" class="author-social"
                                                         href="https://instagram.com/<?php echo the_author_meta( 'instagram' ); ?>">
                <svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                          d="M6.28711 2.875C4.53821 2.875 3.12044 4.29276 3.12044 6.04167V13.9583C3.12044 15.7072 4.53821 17.125 6.28711 17.125H14.2038C15.9527 17.125 17.3704 15.7072 17.3704 13.9583V6.04167C17.3704 4.29276 15.9527 2.875 14.2038 2.875H6.28711ZM1.53711 6.04167C1.53711 3.41831 3.66376 1.29166 6.28711 1.29166H14.2038C16.8271 1.29166 18.9538 3.41831 18.9538 6.04167V13.9583C18.9538 16.5817 16.8271 18.7083 14.2038 18.7083H6.28711C3.66376 18.7083 1.53711 16.5817 1.53711 13.9583V6.04167Z"/>
                    <path fill-rule="evenodd" clip-rule="evenodd"
                          d="M10.6281 7.61643C10.134 7.54316 9.62931 7.62756 9.18589 7.85764C8.74248 8.08772 8.38291 8.45175 8.15832 8.89797C7.93372 9.34418 7.85555 9.84986 7.93491 10.3431C8.01428 10.8363 8.24713 11.2919 8.60037 11.6451C8.9536 11.9984 9.40923 12.2312 9.90243 12.3106C10.3956 12.3899 10.9013 12.3118 11.3475 12.0872C11.7937 11.8626 12.1578 11.503 12.3879 11.0596C12.6179 10.6162 12.7023 10.1115 12.6291 9.61737C12.5543 9.11332 12.3194 8.64668 11.9591 8.28637C11.5988 7.92605 11.1322 7.69118 10.6281 7.61643ZM8.45666 6.45224C9.19568 6.06877 10.0368 5.9281 10.8604 6.05023C11.7004 6.1748 12.4782 6.56626 13.0787 7.16678C13.6792 7.7673 14.0707 8.54504 14.1953 9.38512C14.3174 10.2087 14.1767 11.0498 13.7933 11.7888C13.4098 12.5279 12.8031 13.1271 12.0594 13.5015C11.3157 13.8758 10.4729 14.0061 9.65089 13.8738C8.82888 13.7415 8.06951 13.3534 7.48078 12.7647C6.89206 12.176 6.50396 11.4166 6.37169 10.5946C6.23942 9.7726 6.36971 8.92981 6.74403 8.18612C7.11835 7.44243 7.71764 6.8357 8.45666 6.45224Z"/>
                    <path fill-rule="evenodd" clip-rule="evenodd"
                          d="M13.808 5.64583C13.808 5.20861 14.1624 4.85416 14.5997 4.85416H14.608C15.0452 4.85416 15.3997 5.20861 15.3997 5.64583C15.3997 6.08306 15.0452 6.4375 14.608 6.4375H14.5997C14.1624 6.4375 13.808 6.08306 13.808 5.64583Z"/>
                </svg>
            </a><?php endif;
		if ( get_the_author_meta( 'pinterest' ) ) : ?><a target="_blank" class="author-social"
                                                         href="https://pinterest.com/<?php echo the_author_meta( 'pinterest' ); ?>">
                <svg width="15" height="20" viewBox="0 0 15 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M6.24655 12.8272C6.07568 13.4909 5.89303 14.1142 5.75699 14.7468C5.45432 16.1534 4.90639 17.4465 4.05484 18.6068C3.89762 18.8215 3.75405 19.0465 3.59023 19.2559C3.53939 19.3214 3.44337 19.3501 3.36758 19.3972C3.32474 19.3214 3.25366 19.2489 3.24283 19.1689C3.03477 17.6348 3.02395 16.1115 3.409 14.5934C3.84207 12.8846 4.23183 11.1651 4.63007 9.44785C4.66363 9.29031 4.65535 9.12672 4.60606 8.97336C4.26996 7.95706 4.22666 6.94311 4.66726 5.94799C4.96052 5.2852 5.43266 4.79094 6.17688 4.66761C7.14517 4.50709 7.85126 5.07055 7.89834 6.04873C7.91425 6.45522 7.87144 6.86188 7.77124 7.25614C7.53588 8.19007 7.25344 9.11175 6.99689 10.04C6.66032 11.2559 7.5274 12.3202 8.78895 12.2454C9.737 12.1889 10.4064 11.6711 10.9322 10.9311C11.5841 10.0146 11.8939 8.96912 12.0516 7.87139C12.1928 6.89415 12.2474 5.91739 11.9184 4.96323C11.4947 3.73933 10.6474 2.93345 9.4235 2.53474C8.26014 2.14736 7.00317 2.14357 5.83749 2.52391C3.98376 3.11797 2.83471 4.38706 2.3654 6.27044C2.17005 7.05279 2.1258 7.84597 2.40353 8.61184C2.54474 9.00113 2.80976 9.34429 2.99617 9.71946C3.06725 9.86304 3.11997 10.0443 3.09926 10.1973C3.06019 10.4905 2.96793 10.7772 2.88743 11.0639C2.79329 11.3934 2.60076 11.4983 2.28396 11.3675C1.68367 11.123 1.18049 10.6878 0.852007 10.129C0.00893241 8.72246 -0.0894498 7.21048 0.309728 5.65708C0.567216 4.64878 1.06666 3.7624 1.74969 2.97393C2.49344 2.1125 3.39723 1.48313 4.43613 1.04394C5.22562 0.714253 6.0622 0.511225 6.91499 0.442353C8.26833 0.327966 9.59296 0.426819 10.8592 0.964862C12.6216 1.71803 13.8662 2.96922 14.5111 4.78529C14.8905 5.85384 14.8797 6.9577 14.7314 8.06297C14.5676 9.27886 14.2197 10.4378 13.5546 11.4805C12.4832 13.1562 10.9708 14.1104 8.96218 14.1349C7.91952 14.1476 6.99266 13.7866 6.33928 12.9058C6.31034 12.8774 6.27935 12.8511 6.24655 12.8272Z"/>
                </svg>
            </a><?php endif;
		if ( get_the_author_meta( 'tumblr' ) ) : ?><a target="_blank" class="author-social"
                                                      href="https://<?php echo the_author_meta( 'tumblr' ); ?>.tumblr.com/">
                <svg width="14" height="20" viewBox="0 0 14 20" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.08 15.269C13.08 15.0022 13.08 14.7361 13.08 14.4704C13.08 14.3164 13.08 13.9068 12.6677 13.7572C12.2676 13.612 12.0099 13.9057 11.8996 14.0343C11.2585 14.7647 10.4522 15.1149 9.50289 15.0761C8.90716 15.0517 8.58021 14.7015 8.58187 14.0897C8.58409 12.9917 8.58612 11.8939 8.58797 10.7963V9.46635H12.3164C12.8417 9.46635 13.0761 9.23083 13.0778 8.70382C13.0778 7.85152 13.0778 6.9909 13.0778 6.18294C13.08 5.60107 12.8539 5.37663 12.272 5.37663H8.59129V4.26831C8.59129 3.26564 8.59129 2.26316 8.59129 1.26087C8.59129 0.75381 8.35743 0.5061 7.87864 0.503329C7.04241 0.500004 6.19344 0.500004 5.35388 0.503883C4.92662 0.503883 4.69942 0.725548 4.66062 1.16888C4.65287 1.25533 4.64566 1.34122 4.63901 1.42767C4.61696 1.79119 4.57254 2.153 4.50601 2.51106C4.16354 4.15415 3.04524 5.1727 1.35838 5.37663C0.954947 5.42595 0.748799 5.66757 0.748799 6.09372C0.745474 6.92496 0.74492 7.78391 0.748799 8.72709C0.748799 9.21919 0.989305 9.45914 1.47974 9.46136C1.83939 9.46136 2.20015 9.46136 2.57421 9.46136H2.82801V9.54282C2.82801 9.9795 2.82801 10.4162 2.82524 10.8529C2.82136 11.913 2.81748 13.0097 2.83633 14.0892C2.8463 14.6533 2.87733 15.3931 3.03028 16.113C3.40268 17.8636 4.47498 18.9702 6.13359 19.3121C6.74932 19.437 7.37617 19.4989 8.00443 19.4967C8.74676 19.4922 9.48723 19.4221 10.2172 19.2872C11.182 19.1154 12.0199 18.7452 12.7109 18.1872C12.8772 18.0542 13.0933 17.8336 13.09 17.4563C13.0783 16.7297 13.0794 15.9872 13.08 15.269ZM6.78251 18.1617C5.26244 18.0115 4.40904 17.1637 4.17407 15.5732C4.10422 15.1014 4.06719 14.6254 4.06324 14.1485C4.05382 12.9293 4.05548 11.6952 4.05714 10.4987C4.05714 9.99853 4.05714 9.49849 4.05714 8.99863C4.05714 8.44004 3.84767 8.23112 3.28464 8.2289H2.00952V6.54037C3.07295 6.28047 3.92636 5.80555 4.55478 5.12504C4.64455 5.02825 4.72989 4.92702 4.8108 4.82136C4.84793 4.77259 4.88395 4.72327 4.92163 4.67284C5.4503 3.91586 5.77061 2.94164 5.87701 1.7707H7.34664V2.95106C7.34664 3.90865 7.34664 4.86662 7.34664 5.82495C7.34664 6.40072 7.57385 6.62516 8.15683 6.62571H11.8176V8.23278H8.133C7.56609 8.23278 7.34498 8.45445 7.34443 9.02135C7.34147 10.7093 7.33999 12.3982 7.33999 14.0881C7.33999 15.4552 8.18287 16.3125 9.54112 16.3258C10.3473 16.3361 11.14 16.1193 11.8287 15.7001V15.7672C11.8287 16.2659 11.8287 16.7857 11.8204 17.2955C11.7995 17.3246 11.7741 17.3501 11.745 17.3709C11.6663 17.4274 11.586 17.481 11.504 17.5316C10.9039 17.8934 10.2251 18.1045 9.52561 18.1467C8.70711 18.2077 7.7401 18.2576 6.78251 18.1617Z"/>
                </svg>
            </a><?php endif;
	}


	public function new_excerpt_more( $more ) {
		return '...';
	}

	// Page H1 heading
	private static function post_header() {
		if ( Clienticabuilder_Core::$page_options['post_header'] ) {
			echo '<h1 class="archive-title">', get_the_title(), '</h1>';
		}
	}

	// Page H1 heading
	private static function is_sticky_isnt_category() {
		return is_sticky() && ! Clienticabuilder_Helpers::is_category();
	}
	}

	?>
