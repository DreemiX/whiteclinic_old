<?php

class Composer_Widget_Wordpress_Adapter {


	public function widget( $id ) {
		$post_status = get_post_status( $id );
		if ( $post_status == 'publish' ) {
			$content_post           = get_post( $id );
			return do_shortcode( $content_post->post_content ); // This is where the actual content of the custom post is being displayed
		} else {
			return '';
		}

	}
}