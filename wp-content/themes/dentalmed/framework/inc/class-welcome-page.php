<?php

class Clienticabuilder_Welcome {

	public $allowed_html;

	public function __construct() {

		// Allowed HTML tags for escaping of texts
		$this->allowed_html = array(
			'a'      => array(
				'href'   => array(),
				'title'  => array(),
				'target' => array(),
			),
			'div'    => array(
				'id'    => array(),
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
			'del'    => array(),
		);

	}

	public function render() {
		$theme = wp_get_theme();
		echo '<div class="increase">';
		echo '<div class="container">';
		echo '<br><h1>', esc_html__( 'Welcome to', 'dentalmed' ), ' ' , $theme->Name, ' ' ,esc_html__( 'Theme', 'dentalmed' ) , '</h1>
 		<p class="descr">', esc_html__( 'Fully Customizable and Easy-to-use', 'dentalmed' ), '</p>';
		echo '<div class="noinstaller">', esc_html__( 'To install the theme\'s demo install the "clientica Installer" plugin',
			'dentalmed' ), '</div>';
		echo '<p><a class="meta_btn" href="', esc_url( get_admin_url( null, 'themes.php?page=install-required-plugins' ) ), '">', esc_html__( 'Install', 'dentalmed' ), '</a></p>';
		echo '</div></div>';
	}
}
