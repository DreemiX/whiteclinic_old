<?php

class CLIENTICA_Docs {

	public function __construct() {
		wp_enqueue_script( 'atiframebuilder-import-attach-js',
			CLIENTICA_INSTALLER_DIR_URL . '/pages/js/import_attach.js',
			array( 'jquery', 'jquery-ui-tooltip', 'jquery-ui-progressbar' ),
			20140421,
			true );
		wp_enqueue_script( 'atiframebuilder-welcome-js',
			CLIENTICA_INSTALLER_DIR_URL . '/pages/js/welcome.js',
			array( 'jquery' ),
			'1.0',
			true );
		wp_enqueue_script( 'bootstrap',
			CLIENTICA_INSTALLER_DIR_URL . '/pages/js/bootstrap.min.js',
			array( 'jquery' ),
			'1.0',
			true );

		wp_enqueue_style( 'atiframebuilder-welcome',
			CLIENTICA_INSTALLER_DIR_URL . '/pages/css/welcome.css',
			array(),
			'3.03' );
		wp_enqueue_style( 'jquery-ui',
			CLIENTICA_INSTALLER_DIR_URL . '/pages/css/jquery-ui.css',
			array(),
			'3.03' );

	}

	public function render() {
		$theme = wp_get_theme();
		$active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'welcomedocs';
		if ( $active_tab == 'welcomedocs' ) {
			$active_class = 'nav-tab-active';
		} else {
			$active_class = '';
		}

		echo '<div class="wrap">
<h2 class="nav-tab-wrapper">
    <a href="?page=welcome" class="nav-tab">' . esc_html__( 'Installation', 'clientica-installer' ) . '</a>
    <a href="?page=welcomedocs" class="nav-tab ' . $active_class . '">' . esc_html__( 'Documentation',
				'clientica-installer' ) . '</a>
</h2>';

		echo '<div class="increase">';

		echo '<div class="container">';

		echo '<br><h1>', esc_html__( 'Welcome to', 'clientica-installer' ), ' ' , $theme->Name, ' ' ,esc_html__( 'Theme', 'clientica-installer' ) , '</h1>
 		<p class="descr">' . esc_html__( 'Fully Customizable and Easy-to-use', 'clientica-installer' ) . '</p>
 

	<div class="tab-content"  class="tab-pane fade in active" id="firsttab">

            
            <div class="row">
                <div class="col-md-6 col-sm-12">
                	<h2>' . esc_html__( 'Settings', 'clientica-installer' ) . '</h2>
					<div class="helpful">
						<ol>
						<li><a href="admin.php?page=revslider">' . esc_html__( 'Edit Sliders', 'clientica-installer' ) . '</a></li>
						<li><a href="customize.php">' . esc_html__( 'Customizer',
				'clientica-installer' ) . '</a> ' . esc_html__( '(choose colors, design, pageloader, type of footer and header)',
				'clientica-installer' ) . '</li>
						<li><a href="nav-menus.php">' . esc_html__( 'Menu',
				'clientica-installer' ) . '</a> ' . esc_html__( '(configure your menu items)', 'clientica-installer' ) . '</li>
						<li><a href="edit.php?post_type=composer_widget">' . esc_html__( 'Edit Headers',
				'clientica-installer' ) . '</a> ' . esc_html__( '(change design of header)', 'clientica-installer' ) . '</li>
						<li><a href="edit.php?post_type=composer_widget">' . esc_html__( 'Edit Footers',
				'clientica-installer' ) . '</a> ' . esc_html__( '(if you choose it at Theme Options -> Design -> Footer)',
				'clientica-installer' ) . '</li>
						<li><a href="widgets.php">' . esc_html__( 'Widgets',
				'clientica-installer' ) . '</a> ' . esc_html__( '(choose custom footer to display)', 'clientica-installer' ) . '</li>
						<li><a href="admin.php?page=caldera-forms">' . esc_html__( 'Contact Forms in Caldera Forms Plugin',
				'clientica-installer' ) . '</a> ' . esc_html__( '(setup your email data to receive requests)',
				'clientica-installer' ) . '</li>
						</ol>
					</div>
                </div>
                
                <div class="col-md-6 col-sm-12">
                	<h2>' . esc_html__( 'Documentation', 'clientica-installer' ) . '</h2>
					<div class="helpful">
						<p>' . esc_html__( 'Online knowledge base', 'clientica-installer' ) . ' <a href="https://support.clientica.org/help-center" target="_blank">https://support.clientica.org/help-center</a> </p>
						<h3>' . esc_html__( 'Auto Installation', 'clientica-installer' ) . '</h3>
						<p><iframe width="100%" height="315" src="https://www.youtube.com/embed/DWIEGQkuoSg" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe></p>
					</div>
                </div>
                
            </div><!-- row -->
            


            
            <div class="row pt30">
            	<div class="col-md-6 col-sm-12">
            		<h2>' . esc_html__( 'Support', 'clientica-installer' ) . '</h2>
            		<div class="manager">
            			<img src="' . CLIENTICA_INSTALLER_DIR_URL . '/pages/images/manager.jpg" alt="' . esc_attr__( 'Rate the theme',
				'clientica-installer' ) . '">
            			<p>' . esc_html__( 'Send us feedback and criticism. Your feedback and experience is valuable for us! It will help to improve the theme.',
				'clientica-installer' ) . '</p>
            			<b>support@clientica.org</b>
            		</div>
            	</div>
            	<div class="col-md-6 col-sm-12">
            		
            	</div>
            </div>
            <div class="row pt30">
            	<div class="col-md-12">
            		
            	</div>
            </div>
</div><!-- Tab Content -->
</div>';


		echo '</div>';

		echo '</div>';

	}
}
