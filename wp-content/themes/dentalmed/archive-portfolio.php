<?php
/**
 * The template for displaying Archive pages
 */

get_header(); ?>

<div class="p-arch">


	<?php if ( have_posts() ) : ?>
        <header class="archive-header">
            <h1 class="archive-title">
				<?php
				Clienticabuilder_Layout::portfolio_title();
				?>
            </h1>
			<?php
			Clienticabuilder_Layout::portfolio_desc();
			?>
        </header><!-- .archive-header -->

        <div class="row">
			<?php /* The loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 plist">
                    <div class="wrapper">
                        <div class="entry-thumbnail"><?php the_post_thumbnail( 'clienticabuilder_thumb' ); ?></div>
                        <div class="over">
                            <a href="<?php the_permalink(); ?>" rel="bookmark">
                                <div class="wrap">
                                    <h3 class="entry-title"><?php the_title(); ?></h3>
                                    <div class="desc">
										<?php
										if ( has_excerpt( $id ) ) {
											the_excerpt();
										}
										?>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
			<?php endwhile; ?>


        </div>
		<?php
		Clienticabuilder_Layout::paging_nav();
		?>
	<?php else : ?>
		<?php get_template_part( 'content', 'none' ); ?>
	<?php endif; ?>


</div><!-- #primary -->

<?php get_footer(); ?>
