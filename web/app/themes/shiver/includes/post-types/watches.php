<?php

/**
 * Create the Jam category if it doesn't already exist
 * @return void
 */
function shiver_create_category_watches(): void
{

	if (category_exists('watches')) {
		return;
	}

	// Create the category
	wp_insert_category([
		'cat_name' => 'Watches',
	    'category_description' => 'Things I\'ve been watching.',
	    'category_nicename' => 'watches',
	]);
}
add_action('admin_init', 'shiver_create_category_watches');