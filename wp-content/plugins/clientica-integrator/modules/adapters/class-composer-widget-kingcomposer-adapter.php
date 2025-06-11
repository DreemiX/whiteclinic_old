<?php

class Composer_Widget_Kingcomposer_Adapter {

	public function widget( $id ) {
		$post_status = get_post_status( $id );
		if ( $post_status == 'publish' ) {
			global $wp_scripts, $wp_styles;

			if ( ! in_array( 'ultimate-script', $wp_scripts->queue ) ) {
				$wp_scripts->queue[] = 'ultimate-script';
			}
			if ( ! in_array( 'ultimate-style-min', $wp_styles->queue ) ) {
				$wp_styles->queue[] = 'ultimate-style-min';
			}
			$content_post = get_post( $id );
			if ( isset( $content_post ) && isset( $content_post->post_content_filtered ) && ! empty( $content_post->post_content_filtered ) && function_exists( 'kc_do_shortcode' ) ) {
				$content = kc_do_shortcode( $content_post->post_content_filtered );
			} elseif ( isset( $content_post ) && isset( $content_post->post_content ) && ! empty( $content_post->post_content ) ) {
				$content = $content_post->post_content;
			} else {
				$content = '';
			}

			return do_shortcode( $content ) . $this->add_shortcodes_custom_css( $id );

//		echo do_shortcode( $content ); // This is where the actual content of the custom post is being displayed
//		echo $this->add_shortcodes_custom_css( $id );
		} else {
			return '';
		}

	}

	private function add_shortcodes_custom_css( $id = null ) {
		if ( ! empty( $id ) ) {
			$out                   = '';
			$shortcodes_custom_css = get_post_meta( $id, '_wpb_shortcodes_custom_css', true );
			if ( ! empty( $shortcodes_custom_css ) ) {
				$shortcodes_custom_css = strip_tags( $shortcodes_custom_css );
				$out                   .= '<style type="text/css" data-type="vc_shortcodes-custom-css">';
				$out                   .= $shortcodes_custom_css;
				$out                   .= '</style>';
			}

			return $out;
		}
	}
}