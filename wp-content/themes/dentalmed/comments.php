<?php
/**
 * The template for displaying Comments
 *
 * The area of the page that contains comments and the comment form.
 *
 * @package WordPress
 */

/*
 * If the current post is protected by a password and the visitor has not yet
 * entered the password we will return early without loading the comments.
 */
if ( post_password_required() ) {
	return;
}
?>
<?php if ( have_comments() ) : ?>
    <div id="comments" class="comments-area">


        <h2 class="comments-title"><?php echo esc_html__( 'Discussion', 'dentalmed' ); ?></h2>

        <ol class="comment-list">
			<?php
			$args = array(
				'style'       => 'ol',
				'short_ping'  => true,
				'avatar_size' => 80,
				'format'      => 'html5',
				'callback'    => 'Clienticabuilder_Blog::comment'
			);
			wp_list_comments( $args );
			?>
        </ol><!-- .comment-list -->

		<?php
		// Are there comments to navigate through?
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
			?>
            <nav class="nav-links clearfix">
                <div class="nav-previous alignleft"><?php previous_comments_link( '<i class="nat-arrow-left8"></i> ' . esc_html__( 'Older Comments', 'dentalmed' ) ); ?></div>
                <div class="nav-next alignright"><?php next_comments_link( esc_html__( 'Newer Comments', 'dentalmed' ) . ' <i class="nat-arrow-right8"></i>' ); ?></div>
            </nav><!-- .comment-navigation -->
		<?php endif; // Check for comment navigation ?>

		<?php if ( ! comments_open() && get_comments_number() ) : ?>
            <p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'dentalmed' ); ?></p>
		<?php endif; ?>


    </div><!-- #comments -->
<?php endif; // have_comments() ?>
<?php Clienticabuilder_Blog::comment_template(); ?>