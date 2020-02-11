<?php

/**
 * Register the Book post type
 * @return void
 */
function shiver_register_post_type_cpts_books() {

	$labels = [
		"name" => __( "Books", "shiver" ),
		"singular_name" => __( "Book", "shiver" ),
		"menu_name" => __( "Books", "shiver" ),
		"all_items" => __( "Books", "shiver" ),
		"add_new" => __( "New Read", "shiver" ),
		"add_new_item" => __( "Add New Read", "shiver" ),
	];

	$args = [
		"label" => __( "Books", "shiver" ),
		"labels" => $labels,
		"description" => "Keeping track of the books I have read.",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => "books-ive-read",
		"show_in_menu" => "edit.php?post_type=reading",
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => [ "slug" => "books-ive-read", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor", "thumbnail" ],
	];

	register_post_type( "books", $args );
}

add_action( 'init', 'shiver_register_post_type_cpts_books' );
