<?php
/* 
The file contain theme functions.
*/

class Clienticabuilder_Helpers {

	private $theme_options = array();

	private $layout_options = array();

	private static $is_singular = null;
	private static $is_single = null;
	private static $is_category = null;
	private static $is_home = null;
	private static $is_search = null;
	private static $is_author = null;

	public function __construct() {
		if ( $theme_options = get_theme_mods() ) {
			$this->theme_options = $theme_options;
		}
	}

	//The function set global variables for correct work of Metabox Plugin with page setting: sidebar and slider
	public function set_globals() {
		$this->set_types();
		$this->set_layout_options();
		Clienticabuilder_Core::$page_options = array_merge( Clienticabuilder_Core::$page_options, $this->theme_options, $this->layout_options );
		$this->set_sidebar_layout();
		$this->theme_options = $this->layout_options = array();
	}

	private function set_types() {
		if ( class_exists( 'RevSliderFront' ) ) {
			$this->theme_options['is_active_slider_plugins'] = true;
		} else {
			$this->theme_options['is_active_slider_plugins'] = false;
		}

		$this->set_general_page_type();

		if ( self::is_singular() ) {
			global $post;
			if ( $layout_options = json_decode( get_post_meta( $post->ID, 'layout_settings', true ), true ) ) {
				$this->layout_options = array_filter( $layout_options );
			}
			if ( 'post' === $post->post_type ) {
				$this->set_blog_page_type();
			} elseif ( $this->is_woocommerce() ) {
				$this->set_shop_page_type();
			}
		} elseif ( $this->is_woocommerce() ) {
			$this->set_shop_page_type();
		} elseif ( self::is_category() || self::is_home() || is_tag() || self::is_author() || self::is_search() ) {
			$this->set_blog_page_type();
		}
	}

	private function set_blog_page_type() {
		$this->theme_options['page_type']       = 'blog';
		$this->theme_options['page-layout']     = 'blog-layout';
		$this->theme_options['pagetype_prefix'] = 'blog-';
	}

	private function set_shop_page_type() {
		$this->theme_options['page_type']       = 'shop';
		$this->theme_options['page-layout']     = 'shop-layout';
		$this->theme_options['pagetype_prefix'] = 'shop-';
	}

	private function set_general_page_type() {
		$this->theme_options['page_type']       = '';
		$this->theme_options['page-layout']     = 'layout';
		$this->theme_options['pagetype_prefix'] = '';
	}

	private function is_woocommerce() {
		return function_exists( 'is_woocommerce' ) && is_woocommerce();
	}

	private function set_layout_options() {
		$this->set_sidebar_option();
		$this->set_design_option();
	}

	private function set_sidebar_option() {
		$ppsl = $this->theme_options ['pagetype_prefix'] . 'sidebar_layout';
		if ( ! isset( $this->theme_options['sidebar_layout'] ) ) {
			$this->theme_options['sidebar_layout'] = Clienticabuilder_Theme_Demo::DEFAULT_SETTINGS[ $ppsl ];
		}

		if ( isset( $this->layout_options[ $this->theme_options['pagetype_prefix'] . 'sidebar_layout' ] ) ) {
			$this->layout_options['sidebar_layout'] = (int) $this->layout_options[ $this->theme_options['pagetype_prefix'] . 'sidebar_layout' ];
		} else {
			if ( empty( $this->theme_options[ $ppsl ] ) ) {
				$this->layout_options['sidebar_layout'] = (int) $this->theme_options['sidebar_layout'];
			} else {
				$this->layout_options['sidebar_layout'] = (int) $this->theme_options[ $ppsl ];
			}
		}
	}

	private function set_design_option() {
		if ( ! isset( $this->theme_options[ $this->theme_options['page-layout'] ] ) ) {
			$this->layout_options[ $this->theme_options['page-layout'] ] = Clienticabuilder_Theme_Demo::DEFAULT_SETTINGS['layout'];
		}
	}

	private function set_sidebar_layout() {
		if ( Clienticabuilder_Core::$page_options['sidebar_layout'] !== 1 ) {
			$this->set_sidebar_widgets();
			if ( Clienticabuilder_Core::$page_options['sidebar_layout'] === 3 && ! is_active_sidebar( Clienticabuilder_Core::$page_options['left_sidebar_widgets'] ) ) {
				Clienticabuilder_Core::$page_options['sidebar_layout'] = 1;
			} else if ( Clienticabuilder_Core::$page_options['sidebar_layout'] === 4 && ! is_active_sidebar( Clienticabuilder_Core::$page_options['right_sidebar_widgets'] ) ) {
				Clienticabuilder_Core::$page_options['sidebar_layout'] = 1;
			} else if ( Clienticabuilder_Core::$page_options['sidebar_layout'] === 2 ) {
				if ( ! is_active_sidebar( Clienticabuilder_Core::$page_options['left_sidebar_widgets'] ) && ! is_active_sidebar( Clienticabuilder_Core::$page_options['right_sidebar_widgets'] ) ) {
					Clienticabuilder_Core::$page_options['sidebar_layout'] = 1;
				} else if ( ! is_active_sidebar( Clienticabuilder_Core::$page_options['left_sidebar_widgets'] ) ) {
					Clienticabuilder_Core::$page_options['sidebar_layout'] = 4;
				} else if ( ! is_active_sidebar( Clienticabuilder_Core::$page_options['right_sidebar_widgets'] ) ) {
					Clienticabuilder_Core::$page_options['sidebar_layout'] = 3;
				}
			}
		}
	}

	private function set_sidebar_widgets() {
		Clienticabuilder_Core::$page_options['left_sidebar_widgets']  = Clienticabuilder_Core::$page_options[ Clienticabuilder_Core::$page_options['pagetype_prefix'] . 'left_sidebar_widgets' ];
		Clienticabuilder_Core::$page_options['right_sidebar_widgets'] = Clienticabuilder_Core::$page_options[ Clienticabuilder_Core::$page_options['pagetype_prefix'] . 'right_sidebar_widgets' ];
	}

	// Slider for Shop/blog/site
	/*
	*get_customized_slider() returns aliases of availeble sliders, dependinding
	*/
	public static function get_customized_slider( $slider ) {
		if ( ! empty( $slider ) && preg_match( '/(rev_|lay_|smart_)(.+)/',
				$slider,
				$slider ) ) {
			$type   = $slider[1];
			$slider = $slider[2];
			switch ( $type ) {
				case 'rev_':
					echo do_shortcode( '[rev_slider alias="' . $slider . '"]' );
					break;
				case 'smart_':
					echo do_shortcode( '[smartslider3 slider=' . $slider . ']' );
					break;
				case 'lay_':
					echo do_shortcode( '[layerslider id="' . $slider . '"]' );
					break;
			}
		} else {
			return;
		}
	}

	public function customize_save_after( $manager ) {
		self::generate_styles_file(
			'/framework/css/colors-generator.css',
			'/framework/css/src/colors.css',
			array(
				'colors' => self::set_colors_styles(),
			)
		);
	}

	private static function set_colors_styles() {
		return array(
			'gc1'  => Clienticabuilder_Config::get_color_val( Clienticabuilder_Core::$page_options['gc1'] ),
			'bgrc' => Clienticabuilder_Config::get_color_val( Clienticabuilder_Core::$page_options['bgrc'] ),
			'ac1'  => Clienticabuilder_Config::get_color_val( Clienticabuilder_Core::$page_options['ac1'] ),
			'ac1l' => Clienticabuilder_Config::get_color_val( Clienticabuilder_Core::$page_options['ac1l'] ),
		);
	}

	/* Generate CSS by Template */
	public static function generate_styles_file( $from, $to, $opts, $min = false ) {
		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			WP_Filesystem();
		}
		if ( $wp_filesystem ) {
			$dir          = get_template_directory();
			$template_css = $dir . $from;
			$css          = $dir . $to;
			$content      = $wp_filesystem->get_contents( $template_css );
			if ( $min ) {
				$content = self::minify_css( $content );
			}
			$opts    = self::to_row( $opts );
			$content = preg_replace( $opts['keys'], $opts['values'], $content );
			$wp_filesystem->put_contents( $css, $content, FS_CHMOD_FILE );
			self::generate_main_style();
		}
	}

	private static function minify_css( $input ) {
		if(trim($input) === "") return $input;
		return preg_replace(
			array(
				// Remove comment(s)
				'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')|\/\*(?!\!)(?>.*?\*\/)|^\s*|\s*$#s',
				// Remove unused white-space(s)
				'#("(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\'|\/\*(?>.*?\*\/))|\s*+;\s*+(})\s*+|\s*+([*$~^|]?+=|[{};,>~]|\s(?![0-9\.])|!important\b)\s*+|([[(:])\s++|\s++([])])|\s++(:)\s*+(?!(?>[^{}"\']++|"(?:[^"\\\]++|\\\.)*+"|\'(?:[^\'\\\\]++|\\\.)*+\')*+{)|^\s++|\s++\z|(\s)\s+#si',
				// Replace `0(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)` with `0`
				'#(?<=[\s:])(0)(cm|em|ex|in|mm|pc|pt|px|vh|vw|%)#si',
				// Replace `:0 0 0 0` with `:0`
				'#:(0\s+0|0\s+0\s+0\s+0)(?=[;\}]|\!important)#i',
				// Replace `background-position:0` with `background-position:0 0`
				'#(background-position):0(?=[;\}])#si',
				// Replace `0.6` with `.6`, but only when preceded by `:`, `,`, `-` or a white-space
				'#(?<=[\s:,\-])0+\.(\d+)#s',
				// Minify string value
				'#(\/\*(?>.*?\*\/))|(?<!content\:)([\'"])([a-z_][a-z0-9\-_]*?)\2(?=[\s\{\}\];,])#si',
				'#(\/\*(?>.*?\*\/))|(\burl\()([\'"])([^\s]+?)\3(\))#si',
				// Minify HEX color code
				'#(?<=[\s:,\-]\#)([a-f0-6]+)\1([a-f0-6]+)\2([a-f0-6]+)\3#i',
				// Replace `(border|outline):none` with `(border|outline):0`
				'#(?<=[\{;])(border|outline):none(?=[;\}\!])#',
				// Remove empty selector(s)
				'#(\/\*(?>.*?\*\/))|(^|[\{\}])(?:[^\s\{\}]+)\{\}#s'
			),
			array(
				'$1',
				'$1$2$3$4$5$6$7',
				'$1',
				':0',
				'$1:0 0',
				'.$1',
				'$1$3',
				'$1$2$4$5',
				'$1$2$3',
				'$1:0',
				'$1$2'
			),
			$input);
	}

	private static function generate_main_style() {
		global $wp_filesystem;
		if ( empty( $wp_filesystem ) ) {
			WP_Filesystem();
		}
		if ( $wp_filesystem ) {
			$dir          = get_template_directory();
			$src = $dir . '/framework/css/src/';
			$stylecss = $dir . '/style.css';
			$css      = $wp_filesystem->get_contents( $stylecss );
			preg_match('~\/\*[\s\S]*?\*\/~', $css, $matches);
			$css = empty( $matches[0] ) ? '' : $matches[0] . PHP_EOL ;

			if ( $ccss = $wp_filesystem->get_contents( $src . 'colors.css' ) ) {
				$css .= self::minify_css( $ccss );
			}

			if ( $scss = $wp_filesystem->get_contents( $src . 'style.css' ) ) {
				$css .= self::minify_css( $scss );
			}

			if( $pcss = $wp_filesystem->get_contents( $src . 'p' . Clienticabuilder_Core::$page_options['single_template'] . '.css' ) ){
				$css .= self::minify_css( $pcss );
			}
			if( $acss = $wp_filesystem->get_contents( $src . 'a' . Clienticabuilder_Core::$page_options['archive_template'] . '.css' ) ){
				$css .= self::minify_css( $acss );
			}

			$wp_filesystem->put_contents( $stylecss, $css, FS_CHMOD_FILE );
		}
	}

	/* Color Schemes - Generate CSS */
	public static function to_row( $arr ) {
		$keys   = array();
		$values = array();
		foreach ( $arr as $key => $val ) {
			if ( is_array( $val ) ) {
				foreach ( $val as $k => $v ) {
					if ( ! is_array( $v ) ) {
						$keys[]   = '/\$' . $key . '_' . $k . '\$/';
						$values[] = $v;
					} else {
						foreach ( $v as $k1 => $v1 ) {
							$keys[]   = '/\$' . $key . '_' . $k . '_' . $k1 . '\$/';
							$values[] = $v1;
						}
					}
				}
			} else {
				$keys[]   = '/\$' . $key . '\$/';
				$values[] = $val;
			}
		}
		$result         = array();
		$result['keys'] = $keys;
		ksort( $result['keys'] );
		$result['values'] = $values;
		ksort( $result['values'] );

		return $result;
	}

	public static function correct_enqueue_styles_for_widgets( $html ) {
		if ( empty( $html ) ) {
			return $html;
		}
		preg_match_all( '~<style[\s\r\n].*?>(?P<styles>.*?)\<\/style>~', $html, $styles );
		if ( ! empty( $styles['styles'] ) ) {
			if ( is_array( $styles['styles'] ) ) {
				foreach ( $styles['styles'] as $style ) {
					wp_add_inline_style( 'clienticabuilder-ownstyles', $style );
				}
			} else {
				wp_add_inline_style( 'clienticabuilder-ownstyles', $styles['styles'] );
			}

			return preg_replace( '@<(style)[^>]*?>.*?</\\1>@si', '', $html );
		} else {
			return $html;
		}
	}

	public static function is_singular() {
		if ( is_null( self::$is_singular ) ) {
			self::$is_singular = is_singular();
		}

		return self::$is_singular;
	}

	public static function is_single() {
		if ( is_null( self::$is_single ) ) {
			self::$is_single = is_single();
		}

		return self::$is_single;
	}

	public static function is_category() {
		if ( is_null( self::$is_category ) ) {
			self::$is_category = is_category();
		}

		return self::$is_category;
	}

	public static function is_home() {
		if ( is_null( self::$is_home ) ) {
			self::$is_home = is_home();
		}

		return self::$is_home;
	}

	public static function is_search() {
		if ( is_null( self::$is_search ) ) {
			self::$is_search = is_search();
		}

		return self::$is_search;
	}

	public static function is_author() {
		if ( is_null( self::$is_author ) ) {
			self::$is_author = is_author();
		}

		return self::$is_author;
	}
}

?>
