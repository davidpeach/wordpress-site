<?php

/**
 * Register the Album post type
 * @return void
 */
function shiver_register_post_type_album(): void
{
	$labels = [
		"name" => __( "Albums", "shiver" ),
		"singular_name" => __( "Album", "shiver" ),
		"menu_name" => __( "Albums", "shiver" ),
		"all_items" => __( "All Albums", "shiver" ),
		"add_new" => __( "Add new", "shiver" ),
		"add_new_item" => __( "Add new Album", "shiver" ),
		"edit_item" => __( "Edit Album", "shiver" ),
		"new_item" => __( "New Album", "shiver" ),
		"view_item" => __( "View Album", "shiver" ),
		"view_items" => __( "View Albums", "shiver" ),
		"search_items" => __( "Search Albums", "shiver" ),
		"not_found" => __( "No Albums found", "shiver" ),
		"not_found_in_trash" => __( "No Albums found in trash", "shiver" ),
		"parent" => __( "Parent Album:", "shiver" ),
		"featured_image" => __( "Featured image for this Album", "shiver" ),
		"set_featured_image" => __( "Set featured image for this Album", "shiver" ),
		"remove_featured_image" => __( "Remove featured image for this Album", "shiver" ),
		"use_featured_image" => __( "Use as featured image for this Album", "shiver" ),
		"archives" => __( "Album archives", "shiver" ),
		"insert_into_item" => __( "Insert into Album", "shiver" ),
		"uploaded_to_this_item" => __( "Upload to this Album", "shiver" ),
		"filter_items_list" => __( "Filter Albums list", "shiver" ),
		"items_list_navigation" => __( "Albums list navigation", "shiver" ),
		"items_list" => __( "Albums list", "shiver" ),
		"attributes" => __( "Albums attributes", "shiver" ),
		"name_admin_bar" => __( "Album", "shiver" ),
		"item_published" => __( "Album published", "shiver" ),
		"item_published_privately" => __( "Album published privately.", "shiver" ),
		"item_reverted_to_draft" => __( "Album reverted to draft.", "shiver" ),
		"item_scheduled" => __( "Album scheduled", "shiver" ),
		"item_updated" => __( "Album updated.", "shiver" ),
		"parent_item_colon" => __( "Parent Album:", "shiver" ),
	];

	$args = [
		"label" => __( "Albums", "shiver" ),
		"labels" => $labels,
		"description" => "Music albums that I have listened to at least once",
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
		"rewrite" => [ "slug" => "album", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor", "thumbnail" ],
		"menu_icon" => "dashicons-album",
	];

	register_post_type( "album", $args );
}

add_action( 'init', 'shiver_register_post_type_album' );

function shiver_register_acf_group_album_fields(): void
{
	if( function_exists('acf_add_local_field_group') ):

		acf_add_local_field_group(array(
			'key' => 'group_5e41c714d3d35',
			'title' => 'Album Fields',
			'fields' => array(
				array(
					'key' => 'field_5e41c71f4814d',
					'label' => 'Artists',
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
						0 => 'music_artist',
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
				array(
					'key' => 'field_5e41cb84cfcd3',
					'label' => 'Jammed!',
					'name' => 'jamable_ting',
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
						0 => 'post',
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
						'value' => 'album',
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

add_action( 'init', 'shiver_register_acf_group_album_fields' );