<?php if ($content = get_field('content', get_queried_object())): ?>
<div class="section-inner">
	<div class="entry-content post-inner">
		<?php echo $content; ?>
	</div>
</div>
<?php endif; ?>