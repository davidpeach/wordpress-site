<?php
/**
 * Plugin Name: Archive Gutenberg Content
 */

class ArchiveGutenbergContent
{
	private static $instance;

	private $dividerBlock = 'core/separator';

	private $done = false;

	private $topContent = '';

	private $bottomContent = '';

	public static function instance() {
		if ( ! isset( self::$instance ) && ! ( self::$instance instanceof ArchiveGutenbergContent ) ) {
			self::$instance = new ArchiveGutenbergContent();
		}
		self::$instance->init();

		return self::$instance;
	}

	public function init() {

		if ( ! is_tag()) {
			return;
		}

		if ($this->done === true) {
			return;
		}

		$object = get_queried_object();
		$term = $object->term_id;

		$args = array(
		    'post_type' => 'archive_content',
		    'meta_key'		=> 'taxonomy',
			'meta_value'	=> $term,
			'posts_per_page' => 1,
		);

		$query = new WP_Query($args);
		$posts = $query->get_posts();

		if (empty($posts)) {
			$this->content = '';
			$this->done = true;
		}

		$post = $posts[0];

		$blocks = parse_blocks($post->post_content);

		$sortedBlocks = [
			'before' => [],
			'after' => [],
		];

		$currentArea = 'before';

		foreach ($blocks as $block) {
			if ($block['blockName'] === 'core/separator') {
				$currentArea = 'after';
				continue;
			}

			$sortedBlocks[$currentArea][] = $block;
		}

		$contentBefore = '';
		foreach ($sortedBlocks['before'] as $block) {
			$contentBefore .= render_block($block);
		}

		$contentAfter = '';
		foreach ($sortedBlocks['after'] as $block) {
			$contentAfter .= render_block($block);
		}

		$this->topContent = $contentBefore;
		$this->bottomContent = $contentAfter;
		$this->done = true;
	}

	public function getTopContent()
	{
		return $this->topContent;
	}

	public function getBottomContent()
	{
		return $this->bottomContent;
	}
}

function archiveGutenbergContentInit()
{
	ArchiveGutenbergContent::instance();
}

add_action( 'init', 'archiveGutenbergContentInit', 90 );

function archiveTopContent() {
	$instance = ArchiveGutenbergContent::instance();
	return $instance->getTopContent();
}

function archiveBottomContent() {
	$instance = ArchiveGutenbergContent::instance();
	return $instance->getBottomContent();
}


// // Get the plugin running. Load on plugins_loaded action to avoid issue on multisite.
// if ( function_exists( 'is_multisite' ) && is_multisite() ) {
// 	add_action( 'plugins_loaded', 'archiveGutenbergContentInit', 90 );
// } else {
// 	archiveGutenbergContentInit();
// }