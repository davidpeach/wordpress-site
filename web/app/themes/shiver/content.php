

	<div class="post-inner" id="post-inner">

		<div class="entry-content">

			<?php
			the_content();
			wp_link_pages( array(
				'before'           => '<nav class="post-nav-links bg-light-background"><span class="label">' . __( 'Pages:', 'shiver' ) . '</span>',
				'after'            => '</nav>',
			) );
			if ( $post_type !== 'post' ) {
				edit_post_link();
			}
			?>

		</div><!-- .entry-content -->

		<?php

		// Single bottom post meta
		shiver_the_post_meta( $post->ID, 'single-bottom' );

		// Single post navigation
		if ( is_single() ) {
			the_post_navigation( array(
				'prev_text' => '<span class="arrow" aria-hidden="true">&larr;</span><span class="screen-reader-text">' . __( 'Previous post:', 'shiver' ) . '</span><span class="post-title">%title</span>',
				'next_text' => '<span class="arrow" aria-hidden="true">&rarr;</span><span class="screen-reader-text">' . __( 'Next post:', 'shiver' ) . '</span><span class="post-title">%title</span>',
			) );
		}

		// Output comments wrapper if it's a post, or if comments are open, or if there's a comment number – and check for password
		if ( ( $post_type == 'post' || comments_open() || get_comments_number() ) && ! post_password_required() ) : ?>

			<div class="comments-wrapper">

				<?php comments_template(); ?>

			</div><!-- .comments-wrapper -->

		<?php endif; ?>

	</div><!-- .post-inner -->