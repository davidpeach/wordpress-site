<?php
// On the cover page template, output the cover header
if ( is_page_template( array( 'template-cover.php', 'template-full-width-cover.php' ) ) ) :
	?>
	<div class="cover-header screen-height screen-width<?php echo esc_attr( getCoverHeaderClasses() ); ?>"<?php echo getCoverHeaderStyle(); ?>>
		<div class="cover-header-inner-wrapper">
			<div class="cover-header-inner">

				<div class="cover-color-overlay color-accent<?php echo esc_attr( getColorOverlayClasses() ); ?>"<?php echo getColorOverlayStyle(); ?>></div>

				<div class="section-inner<?php echo esc_attr( getSectionInnerClasses() ); ?>">
					<?php get_template_part( 'parts/page-header' ); ?>
				</div><!-- .section-inner -->

			</div><!-- .cover-header-inner -->
		</div><!-- .cover-header-inner-wrapper -->
	</div><!-- .cover-header -->

<?php

// On all other pages, output the regular page header
else :

	get_template_part( 'parts/page-header' );

	if (has_post_thumbnail() && ! post_password_required()) : ?>

		<figure class="featured-media">

			<?php

			shiver_the_post_thumbnail(get_the_ID());

			if ( $caption = get_the_post_thumbnail_caption() ) : ?>
				<figcaption class="wp-caption-text"><?php echo esc_html( $caption ); ?></figcaption>
			<?php endif; ?>

		</figure><!-- .featured-media -->

	<?php endif; ?>

<?php endif; ?>