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
		"public" => false,
		"publicly_queryable" => false,
		"show_ui" => true,
		"show_in_rest" => true,
		"rest_base" => "",
		"rest_controller_class" => "WP_REST_Posts_Controller",
		"has_archive" => "books-ive-read",
		"show_in_menu" => true,
		"show_in_nav_menus" => true,
		"delete_with_user" => false,
		"exclude_from_search" => true,
		"capability_type" => "post",
		"map_meta_cap" => true,
		"hierarchical" => false,
		"rewrite" => [ "slug" => "books-ive-read", "with_front" => true ],
		"query_var" => true,
		"supports" => [ "title", "editor", "thumbnail" ],
		"menu_icon" => "dashicons-book-alt",
	];

	register_post_type( "books", $args );
}

add_action( 'init', 'shiver_register_post_type_cpts_books' );

function shiver_register_acf_group_book_fields(): void
{
	if( function_exists('acf_add_local_field_group') ):

		acf_add_local_field_group(array(
			'key' => 'group_5e430ddecd1ba',
			'title' => 'Book Fields',
			'fields' => array(
				array(
					'key' => 'field_5e430de1c700f',
					'label' => 'Readable Ting',
					'name' => 'readable_ting',
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
				array(
					'key' => 'field_5e430e52abf17',
					'label' => 'Number Of Pages',
					'name' => 'number_of_pages',
					'type' => 'number',
					'instructions' => 'How many pages does this book have?',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
					'prepend' => '',
					'append' => '',
					'min' => '',
					'max' => '',
					'step' => '',
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'books',
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
add_action( 'init', 'shiver_register_acf_group_book_fields' );
