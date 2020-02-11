<?php

/**
 * Create the Jam category if it doesn't already exist
 * @return void
 */
function shiver_create_category_reading(): void
{

	if (category_exists('reading')) {
		return;
	}

	// Create the category
	wp_insert_category([
		'cat_name' => 'Reading',
	    'category_description' => 'Books I am reading, or have read.',
	    'category_nicename' => 'reading',
	]);
}
add_action('admin_init', 'shiver_create_category_reading');

function shiver_register_acf_group_reading_fields()
{
	if( function_exists('acf_add_local_field_group') ):

		acf_add_local_field_group(array(
			'key' => 'group_5d5c2b2967c6d',
			'title' => 'Reading Fields',
			'fields' => array(
				array(
					'key' => 'field_5d5c2b323d2b3',
					'label' => 'Start Date',
					'name' => 'start_date',
					'type' => 'date_picker',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'display_format' => 'd/m/Y',
					'return_format' => 'd/m/Y',
					'first_day' => 1,
				),
				array(
					'key' => 'field_5d5c2b663d2b4',
					'label' => 'Finish Date',
					'name' => 'finish_date',
					'type' => 'date_picker',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'display_format' => 'd/m/Y',
					'return_format' => 'd/m/Y',
					'first_day' => 1,
				),
				array(
					'key' => 'field_5e4301312e759',
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
						0 => 'books',
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
						'value' => 'post',
					),
					array(
						'param' => 'post_category',
						'operator' => '==',
						'value' => 'category:reading',
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
add_action( 'init', 'shiver_register_acf_group_reading_fields' );
