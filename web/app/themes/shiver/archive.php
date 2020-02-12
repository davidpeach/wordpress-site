<?php get_header(); ?>

<main id="site-content" role="main">
	<div class="cover-header screen-height screen-width<?php echo esc_attr( getCoverHeaderClasses($post->ID) ); ?>"<?php echo getCoverHeaderStyle($post->ID); ?>>
		<div class="cover-header-inner-wrapper">
			<div class="cover-header-inner">
				<div class="cover-color-overlay color-accent<?php echo esc_attr( getColorOverlayClasses() ); ?>"<?php echo getColorOverlayStyle(); ?>></div>
				<div class="section-inner<?php echo esc_attr( getSectionInnerClasses() ); ?>">
					<header class="entry-header">
						<?php the_archive_title( '<h1 class="archive-title dashicons-before dashicons-tag">', '</h1>' );
						if ( get_the_archive_description() ) : ?>

						<div class="intro-text section-inner thin max-percentage">
							<?php the_archive_description(); ?>
						</div>
					<?php endif; ?>

					<?php
					if ( function_exists('yoast_breadcrumb') ) {
					  yoast_breadcrumb( '<p id="breadcrumbs" class="breadcrumbs">','</p>' );
					}
					?>
					</header>
				</div><!-- .section-inner -->
			</div><!-- .cover-header-inner -->
		</div><!-- .cover-header-inner-wrapper -->
	</div><!-- .cover-header -->

	<div class="posts section-inner post-inner">
		<?php if ( have_posts() ) :

			$post_grid_column_classes = shiver_get_post_grid_column_classes();

			?>
			<div class="posts-grid grid load-more-target <?php echo $post_grid_column_classes; ?>">

				<?php while ( have_posts() ) : the_post(); ?>

					<div class="grid-item">

						<?php get_template_part( 'parts/preview', get_post_type() ); ?>

					</div><!-- .grid-item -->

				<?php endwhile; ?>

			</div><!-- .posts-grid -->
		<?php else: ?>
			<p>Sorry about this. But no posts could be found. Go <a href="/">Home</a></p>
		<?php endif; ?>

	</div><!-- .posts -->

	<?php get_template_part( 'pagination' ); ?>

</main><!-- #site-content -->

<?php get_footer(); ?>