<?php

/**
 * Register the Film post type
 * @return void
 */
function shiver_register_post_type_film() {

	$labels = [
		"name" => __( "Films", "shiver" ),
		"singular_name" => __( "Film", "shiver" ),
		"menu_name" => __( "Films", "shiver" ),
		"all_items" => __( "Films", "shiver" ),
		"add_new" => __( "Add new", "shiver" ),
		"add_new_item" => __( "Add new Film", "shiver" ),
		"edit_item" => __( "Edit Film", "shiver" ),
		"new_item" => __( "New Film", "shiver" ),
		"view_item" => __( "View Film", "shiver" ),
		"view_items" => __( "View Films", "shiver" ),
		"search_items" => __( "Search Films", "shiver" ),
		"not_found" => __( "No Films found", "shiver" ),
		"not_found_in_trash" => __( "No Films found in trash", "shiver" ),
		"parent" => __( "Parent Film:", "shiver" ),
		"featured_image" => __( "Featured image for this Film", "shiver" ),
		"set_featured_image" => __( "Set featured image for this Film", "shiver" ),
		"remove_featured_image" => __( "Remove featured image for this Film", "shiver" ),
		"use_featured_image" => __( "Use as featured image for this Film", "shiver" ),
		"archives" => __( "Film archives", "shiver" ),
		"insert_into_item" => __( "Insert into Film", "shiver" ),
		"uploaded_to_this_item" => __( "Upload to this Film", "shiver" ),
		"filter_items_list" => __( "Filter Films list", "shiver" ),
		"items_list_navigation" => __( "Films list navigation", "shiver" ),
		"items_list" => __( "Films list", "shiver" ),
		"attributes" => __( "Films attributes", "shiver" ),
		"name_admin_bar" => __( "Film", "shiver" ),
		"item_published" => __( "Film published", "shiver" ),
		"item_published_privately" => __( "Film published privately.", "shiver" ),
		"item_reverted_to_draft" => __( "Film reverted to draft.", "shiver" ),
		"item_scheduled" => __( "Film scheduled", "shiver" ),
		"item_updated" => __( "Film updated.", "shiver" ),
		"parent_item_colon" => __( "Parent Film:", "shiver" ),
	];

	$args = [
		"label" => __( "Films", "shiver" ),
		"labels" => $labels,
		"description" => "",
		"public" => true,
		"publicly_queryable" => true,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => false,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => [ "slug" => "film", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor", "thumbnail" ],
		"menu_icon" => "dashicons-video-alt2",
	];

	register_post_type( "film", $args );
}

add_action( 'init', 'shiver_register_post_type_film' );
