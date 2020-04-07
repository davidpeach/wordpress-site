<?php get_header(); ?>

<main id="site-content" role="main">

	<?php set_query_var( 'title', get_the_archive_title() ); ?>
	<?php set_query_var( 'description', get_the_archive_description() ); ?>
	<?php set_query_var( 'image_url', get_field('tag_image', get_queried_object()) ); ?>
	<?php #$content = get_archive_content(); ?>

	<!-- @TODO Add option to use full or half header in admin -->
	<?php get_template_part('parts/headers/header', 'half'); ?>

	<?php get_template_part('parts/archives/content'); ?>

	<div class="section-inner">
		<div class="entry-content post-inner">
			<?php echo archiveTopContent(); ?>
			<?php #echo isset($content[0]) ? $content[0]: ''; ?>
		</div>
	</div>

	<!-- @TODO BELOW HERE NEEDS TIDYING -->
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

	<div class="section-inner">
		<div class="entry-content post-inner">
			<?php echo archiveBottomContent(); ?>
			<?php #echo isset($content[1]) ? $content[1]: ''; ?>
		</div>
	</div>


	<?php get_template_part( 'pagination' ); ?>

</main>

<?php get_footer(); ?>