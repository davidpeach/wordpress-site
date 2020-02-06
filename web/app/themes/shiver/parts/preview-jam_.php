<article <?php post_class( 'preview preview-' . get_post_type() ); ?> id="post-<?php the_ID(); ?>">
	<header class="preview-header">

		<?php
		the_title( '<h2 class="preview-title heading-size-3"><a href="' . get_the_permalink() . '">', '</a></h2>' );

		if ( get_theme_mod( 'shiver_display_excerpts', false ) ) :

			$excerpt = get_the_excerpt();

			if ( $excerpt ) :
				?>

				<div class="preview-excerpt">
					<?php echo apply_filters( 'the_excerpt', $excerpt ); ?>
				</div><!-- .preview-excerpt -->

				<?php
			endif;
		endif;

		shiver_the_post_meta( $post->ID, 'archive' );
		?>

	</header><!-- .preview-header -->

</article><!-- .preview -->