<?php
/**
 * Twentig utility classes for blocks.
 *
 * @package twentig
 */

/**
 * Retrieves additional CSS classes for blocks.
 */
function twentig_get_block_css_classes() {

	$classes = array(

		'core/paragraph'        => array(
			'tw-text-uppercase'       => __( 'Convert all letters to uppercase.', 'twentig' ),
			'tw-eyebrow'              => __( 'Set style to small uppercase.', 'twentig' ),
			'tw-line-height-tight'    => __( 'Set tight spacing between lines.', 'twentig' ),
			'tw-letter-spacing-tight' => __( 'Set tight spacing between letters.', 'twentig' ),
			'tw-letter-spacing-loose' => __( 'Set loose spacing between letters.', 'twentig' ),
			'tw-link-hover-underline' => __( 'Underline link only on hover.', 'twentig' ),
			'tw-link-no-underline'    => __( 'Remove underline from link.', 'twentig' ),
			'tw-rounded'              => __( 'Make the block rounded if background color is set.', 'twentig' ),
		),
		'core/heading'          => array(
			'tw-heading-border-bottom' => __( 'Add a bottom border to the heading.', 'twentig' ),
			'tw-heading-dash-bottom'   => __( 'Add a small line below the heading.', 'twentig' ),
			'tw-text-uppercase'        => __( 'Convert all letters to uppercase.', 'twentig' ),
			'tw-eyebrow'               => __( 'Set style to small uppercase.', 'twentig' ),
			'tw-letter-spacing-normal' => __( 'Set normal spacing between letters.', 'twentig' ),
			'tw-letter-spacing-loose'  => __( 'Set loose spacing between letters.', 'twentig' ),
			'tw-link-hover-underline'  => __( 'Underline link only on hover.', 'twentig' ),
			'tw-link-no-underline'     => __( 'Remove underline from link.', 'twentig' ),
			'tw-rounded'               => __( 'Make the block rounded if background color is set.', 'twentig' ),
		),
		'core/list'             => array(
			'has-small-font-size'     => __( 'Set font size to small.', 'twentig' ),
			'has-medium-font-size'    => __( 'Set font size to medium.', 'twentig' ),
			'has-large-font-size'     => __( 'Set font size to large.', 'twentig' ),
			'has-larger-font-size'    => __( 'Set font size to larger.', 'twentig' ),
			'tw-font-bold'            => __( 'Set font weight to bold.', 'twentig' ),
			'tw-font-italic'          => __( 'Set font style to italic.', 'twentig' ),
			'tw-text-uppercase'       => __( 'Convert all letters to uppercase.', 'twentig' ),
			'has-text-align-center'   => __( 'Align text to center.', 'twentig' ),
			'tw-list-spacing-medium'  => __( 'Set medium spacing between list items.', 'twentig' ),
			'tw-list-spacing-loose'   => __( 'Set loose spacing between list items.', 'twentig' ),
			'tw-link-hover-underline' => __( 'Underline link only on hover.', 'twentig' ),
			'tw-text-wide'            => __( 'Set the block width to wide.', 'twentig' ),
		),
		'core/table'            => array(
			'has-small-font-size' => __( 'Set font size to small.', 'twentig' ),
			'tw-row-valign-top'   => __( 'Vertically align the content inside each cell to the top.', 'twentig' ),
		),
		'core/group'            => array(
			'tw-height-full'          => __( 'Set the height to full.', 'twentig' ),
			'tw-pt-0'                 => __( 'Remove the top padding.', 'twentig' ),
			'tw-pb-0'                 => __( 'Remove the bottom padding', 'twentig' ),
			'tw-group-overlap-bottom' => __( 'Make the last block inside the group overlap the group below.', 'twentig' ),
			'tw-rounded'              => __( 'Make the block rounded.', 'twentig' ),
		),
		'core/cover'            => array(
			'tw-pt-0'    => __( 'Remove the top padding.', 'twentig' ),
			'tw-pb-0'    => __( 'Remove the bottom padding', 'twentig' ),
			'tw-rounded' => __( 'Make the block rounded.', 'twentig' ),
		),
		'core/columns'          => array(
			'tw-cols-rounded'   => __( 'Make the columns rounded. You must set the columns style to card.', 'twentig' ),
			'tw-justify-center' => __( 'Center the columns horizontally (useful for 3 columns that stack into 2 on medium devices).', 'twentig' ),
		),
		'core/media-text'       => array(
			'tw-content-narrow' => __( 'Constrain the content to a narrow width when the block is full width.', 'twentig' ),
			'tw-media-narrow'   => __( 'Constrain the media to a narrow width when the block is stacked.', 'twentig' ),
			'tw-height-full'    => __( 'Make the block full height. You must enable the "Crop image to fill entire column" setting.', 'twentig' ),
			'tw-rounded'        => __( 'Make the block rounded.', 'twentig' ),
			'tw-img-rounded'    => __( 'Make the image rounded.', 'twentig' ),
		),
		'core/image'            => array(
			'tw-img-bw'    => __( 'Make the image black & white.', 'twentig' ),
			'tw-img-sepia' => __( 'Make the image sepia.', 'twentig' ),
		),
		'core/gallery'          => array(
			'tw-caption-large'    => __( 'Set a larger font-size for the captions.', 'twentig' ),
			'tw-img-border'       => __( 'Add a border around the images (useful for logos and illustrations).', 'twentig' ),
			'tw-img-border-inner' => __( 'Add an inner border between the images (useful for logos).', 'twentig' ),
			'tw-img-bw'           => __( 'Make the images black & white.', 'twentig' ),
			'tw-img-sepia'        => __( 'Make the images sepia.', 'twentig' ),
			'tw-img-center'       => __( 'Align the images of the last row to center. You must enable the "Fixed width columns" setting.', 'twentig' ),
		),
		'core-embed/youtube'    => array(
			'is-style-tw-frame' => __( 'Add a frame around the block.', 'twentig' ),
		),
		'core-embed/vimeo'      => array(
			'is-style-tw-frame' => __( 'Add a frame around the block.', 'twentig' ),
		),
		'core/video'            => array(
			'is-style-tw-frame' => __( 'Add a frame around the block.', 'twentig' ),
		),
		'core-embed/soundcloud' => array(
			'is-style-tw-frame' => __( 'Add a frame around the block.', 'twentig' ),
		),
		'core/buttons'          => array(
			'tw-btn-full' => __( 'Make the buttons full width.', 'twentig' ),
		),
		'core/spacer'           => array(
			'tw-sm-hidden' => __( 'Hide block only on small devices (only visible on live page).', 'twentig' ),
			'tw-md-hidden' => __( 'Hide block only on medium devices (only visible on live page).', 'twentig' ),
			'tw-lg-hidden' => __( 'Hide block only on large devices (only visible on live page).', 'twentig' ),
		),
		'core/separator'        => array(
			'tw-sm-hidden' => __( 'Hide block only on small devices (only visible on live page).', 'twentig' ),
			'tw-md-hidden' => __( 'Hide block only on medium devices (only visible on live page).', 'twentig' ),
			'tw-lg-hidden' => __( 'Hide block only on large devices (only visible on live page).', 'twentig' ),
		),
		'core/latest-posts'     => array(
			'tw-posts-rounded'      => __( 'Make the posts rounded. You must set the style to card.', 'twentig' ),
			'has-text-align-center' => __( 'Align text to center.', 'twentig' ),
		),
		'core/search'           => array(
			'tw-justify-center' => __( 'Center the search form.', 'twentig' ),
			'tw-search-full'    => __( 'Make the search form full width.', 'twentig' ),
		),
	);

	return apply_filters( 'twentig_block_classes', $classes );
}

/**
 * Retrieves font-size presets for Heading block.
 */
function twentig_get_editor_font_sizes() {
	$sizes = array();

	if ( 'twentytwenty' === get_template() ) {

		$h1_font_size = get_theme_mod( 'twentig_h1_font_size' );
		$h1_size_px   = 84;

		if ( 'small' === $h1_font_size ) {
			$h1_size_px = 56;
		} elseif ( 'medium' === $h1_font_size ) {
			$h1_size_px = 64;
		} elseif ( 'large' === $h1_font_size ) {
			$h1_size_px = 72;
		}

		$sizes = array(
			array(
				'name' => 'h1',
				'size' => $h1_size_px,
				'slug' => 'h1',
			),
			array(
				'name' => 'h2',
				'size' => 48,
				'slug' => 'h2',
			),
			array(
				'name' => 'h3',
				'size' => 40,
				'slug' => 'h3',
			),
			array(
				'name' => 'h4',
				'size' => 32.01,
				'slug' => 'h4',
			),
			array(
				'name' => 'h5',
				'size' => 24.01,
				'slug' => 'h5',
			),
			array(
				'name' => 'h6',
				'size' => 18.01,
				'slug' => 'h6',
			),
		);
	}
	return $sizes;
}
