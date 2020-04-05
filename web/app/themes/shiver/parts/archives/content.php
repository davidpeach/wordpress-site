<?php if ($content = get_field('content', get_queried_object())): ?>
<div class="section-inner">
	<div class="entry-content post-inner">
		<div class="archive-content__text">
			<?php echo $content; ?>
		</div>
	</div>
</div>
<?php endif; ?>