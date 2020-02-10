<?php

/**
 * Register the Artist post type
 * @return void
 */
function shiver_register_post_type_music_artist() {

	$labels = [
		"name" => __( "Music Artists", "shiver" ),
		"singular_name" => __( "Music Artist", "shiver" ),
		"menu_name" => __( "Music Artists", "shiver" ),
		"all_items" => __( "All Artists", "shiver" ),
		"add_new" => __( "Add new", "shiver" ),
		"add_new_item" => __( "Add new Artist", "shiver" ),
		"edit_item" => __( "Edit Artist", "shiver" ),
		"new_item" => __( "New Artist", "shiver" ),
		"view_item" => __( "View Artist", "shiver" ),
		"view_items" => __( "View Artists", "shiver" ),
		"search_items" => __( "Search Artists", "shiver" ),
		"not_found" => __( "No Artists found", "shiver" ),
		"not_found_in_trash" => __( "No Artists found in trash", "shiver" ),
		"parent" => __( "Parent Artist:", "shiver" ),
		"featured_image" => __( "Featured image for this Artist", "shiver" ),
		"set_featured_image" => __( "Set featured image for this Artist", "shiver" ),
		"remove_featured_image" => __( "Remove featured image for this Artist", "shiver" ),
		"use_featured_image" => __( "Use as featured image for this Artist", "shiver" ),
		"archives" => __( "Artist archives", "shiver" ),
		"insert_into_item" => __( "Insert into Artist", "shiver" ),
		"uploaded_to_this_item" => __( "Upload to this Artist", "shiver" ),
		"filter_items_list" => __( "Filter Artists list", "shiver" ),
		"items_list_navigation" => __( "Artists list navigation", "shiver" ),
		"items_list" => __( "Artists list", "shiver" ),
		"attributes" => __( "Artists attributes", "shiver" ),
		"name_admin_bar" => __( "Artist", "shiver" ),
		"item_published" => __( "Artist published", "shiver" ),
		"item_published_privately" => __( "Artist published privately.", "shiver" ),
		"item_reverted_to_draft" => __( "Artist reverted to draft.", "shiver" ),
		"item_scheduled" => __( "Artist scheduled", "shiver" ),
		"item_updated" => __( "Artist updated.", "shiver" ),
		"parent_item_colon" => __( "Parent Artist:", "shiver" ),
	];

	$args = [
		"label" => __( "Music Artists", "shiver" ),
		"labels" => $labels,
		"description" => "",
		"public" => false,
		"publicly_queryable" => false,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => false,
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => true,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => [ "slug" => "music_artist", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor", "thumbnail" ],
	];

	register_post_type( "music_artist", $args );
}

add_action( 'init', 'shiver_register_post_type_music_artist' );

function shiver_register_acf_group_artist_fields(): void
{
	if( function_exists('acf_add_local_field_group') ):

		acf_add_local_field_group(array(
			'key' => 'group_5e41c7a7d6299',
			'title' => 'Artist Fields',
			'fields' => array(
				array(
					'key' => 'field_5e41c941b4372',
					'label' => 'Albums',
					'name' => 'album_artist',
					'type' => 'relationship',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'post_type' => array(
						0 => 'album',
					),
					'taxonomy' => '',
					'filters' => array(
						0 => 'search',
						1 => 'post_type',
						2 => 'taxonomy',
					),
					'elements' => array(
						0 => 'featured_image',
					),
					'min' => '',
					'max' => '',
					'return_format' => 'object',
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'music_artist',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'normal',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
		));

	endif;
}

add_action( 'init', 'shiver_register_acf_group_artist_fields' );