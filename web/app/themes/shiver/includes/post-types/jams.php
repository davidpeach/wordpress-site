<?php

/**
 * Create the Jam category if it doesn't already exist
 * @return void
 */
function shiver_create_category_jams(): void
{

	if (category_exists('jams')) {
		return;
	}

	// Create the category
	wp_insert_category([
		'cat_name' => 'Jams',
	    'category_description' => 'Jams / Songs that I am currently jamming to.',
	    'category_nicename' => 'jams',
	]);
}
add_action('admin_init', 'shiver_create_category_jams');
