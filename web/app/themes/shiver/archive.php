<?php get_header(); ?>

<main id="site-content" role="main">
	<?php
	// On the cover page template, output the cover header
	// if ( is_page_template( array( 'template-cover.php', 'template-full-width-cover.php' ) ) ) :

		$cover_header_style = '';
		$cover_header_classes = '';

		$color_overlay_style = '';
		$color_overlay_classes = '';

		$section_inner_classes = '';

		$term = get_queried_object();
		$image_url = get_field('tag_image', $term);

		if (!$image_url) {
			$image_url  = 'https://davidpeach.co.uk/wp-content/uploads/2019/06/WoWScrnShot_102810_192417.jpg';
		}

		if ( $image_url ) {
			$cover_header_style 	= ' style="background-image: url( ' . esc_url( $image_url ) . ' );"';
			$cover_header_classes 	= ' bg-image';
		}

		// Get the color used for the color overlay
		$color_overlay_color = get_theme_mod( 'shiver_cover_template_overlay_background_color' );
		if ( $color_overlay_color ) {
			$color_overlay_style = ' style="color: ' . esc_attr( $color_overlay_color ) . ';"';
		} else {
			$color_overlay_style = '';
		}

		// Note: The text color is applied by shiver_get_customizer_css(), in functions.php

		// Get the fixed background attachment option
		if ( get_theme_mod( 'shiver_cover_template_fixed_background', true ) ) {
			$cover_header_classes .= ' bg-attachment-fixed';
		}

		// Get the opacity of the color overlay
		$color_overlay_opacity = get_theme_mod( 'shiver_cover_template_overlay_opacity' );
		$color_overlay_opacity = ( $color_overlay_opacity === false ) ? 80 : $color_overlay_opacity;
		$color_overlay_classes .= ' opacity-' . $color_overlay_opacity;

		// Get the blend mode of the color overlay (default = multiply)
		$color_overlay_opacity = get_theme_mod( 'shiver_cover_template_overlay_blend_mode', 'multiply' );
		$color_overlay_classes .= ' blend-mode-' . $color_overlay_opacity;

		// Check whether we're fading the text
		$overlay_fade_text = get_theme_mod( 'shiver_cover_template_fade_text', true );
		$section_inner_classes = $overlay_fade_text ? ' fade-block' : '';

		?>
		<div class="cover-header screen-height screen-width<?php echo esc_attr( $cover_header_classes ); ?>"<?php echo $cover_header_style; ?>>
			<div class="cover-header-inner-wrapper">
				<div class="cover-header-inner">
					<div class="cover-color-overlay color-accent<?php echo esc_attr( $color_overlay_classes ); ?>"<?php echo $color_overlay_style; ?>></div>
					<div class="section-inner<?php echo esc_attr( $section_inner_classes ); ?>">
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