<?php
/**
 * The template for displaying content
 * Used for both single and index/archive/search.
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	// CAGETORY page
	if ( ! Clienticabuilder_Helpers::is_single() ) {
		Clienticabuilder_Blog::archive_template();

	} elseif ( Clienticabuilder_Helpers::is_single() ) {
		// SINGLE
		Clienticabuilder_Blog::single_template();

	} ?>
</article><!-- #post -->