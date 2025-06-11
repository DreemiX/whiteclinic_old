<?php

/**
 * The Header template for our theme
 */

?>
    <!doctype html>
<html <?php language_attributes(); ?> class="no-js">
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="profile" href="https://gmpg.org/xfn/11">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="format-detection" content="telephone=no">

		<?php

		Clienticabuilder_Header::set_boxed_background();

		wp_head();

		?>

    </head>

<body <?php body_class(); ?>>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var animatedElement = document.querySelector('#bdt-ah-6e67497 span');
    if (animatedElement) {
        animatedElement.style.animation = 'none';
    }
});

    </script>
<?php
wp_body_open();
Clienticabuilder_Header::pageloader();
Clienticabuilder_Header::set_boxed_layout();
?>
    <!-- HEADER START -->
    <div class="headline">
		<?php
		Clienticabuilder_Header::header_layout();
		?>
    </div>
    <!-- HEADER END -->

<main class="<?php Clienticabuilder_Layout::main_tag_classes(); ?> <?php Clienticabuilder_Blog::number_of_columns(); ?>">
<?php
Clienticabuilder_Header::set_header_sidebar_layout();
?>