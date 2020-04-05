<?php get_header(); ?>

<main id="site-content">

	<?php

	if ( have_posts() ) :

		while ( have_posts() ) : the_post(); ?>
		<article <?php post_class( 'section-inner' ); ?> id="post-<?php the_ID(); ?>">

			<?php set_query_var( 'title', get_the_title() ); ?>
			<?php set_query_var( 'description', get_the_excerpt() ); ?>
			<?php set_query_var( 'image_url', get_the_post_thumbnail_url( get_the_ID(), 'shiver_fullscreen' ) ); ?>

			<!-- @TODO Add option to use full or half header in admin -->
			<?php get_template_part('parts/headers/header', 'half'); ?>

			<?php
			get_template_part( 'content', get_post_type() );

			// Display related posts
			get_template_part( 'parts/related-posts' );
		?>
		</article>
		<?php
		endwhile;

	endif;

	?>

</main><!-- #site-content -->

<?php get_footer(); ?>