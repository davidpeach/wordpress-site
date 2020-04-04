<div class="cover-header screen-height" style="background-image: url( <?php echo esc_url( $image_url ); ?> );">
	<div class="cover-header-inner-wrapper">
		<div class="cover-header-inner">
			<div class="cover-color-overlay color-accent opacity-80 blend-mode-normal" style="color: #292b29;"></div>
			<div class="section-inner">
				<header class="entry-header">
					<h1><?php echo $title; ?></h1>
					<?php if ( ! empty($description)): ?>
					<div class="intro-text section-inner thin max-percentage">
						<p><?php echo $description; ?></p>
					</div>
					<?php endif; ?>
				</header>
			</div>
		</div>
	</div>
</div>