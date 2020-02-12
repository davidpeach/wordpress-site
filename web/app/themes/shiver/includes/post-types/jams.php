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

function shiver_register_acf_group_jam_fields()
{
	if( function_exists('acf_add_local_field_group') ):

		acf_add_local_field_group(array(
			'key' => 'group_5e388fe1e7590',
			'title' => 'Jam Fields',
			'fields' => array(
				array(
					'key' => 'field_5e388fe9456bc',
					'label' => 'Current Jam',
					'name' => 'current_jam',
					'type' => 'true_false',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'message' => '',
					'default_value' => 0,
					'ui' => 0,
					'ui_on_text' => '',
					'ui_off_text' => '',
				),
				array(
					'key' => 'field_5e41cb06a0dee',
					'label' => 'Jamable Ting',
					'name' => 'jamable_ting',
					'type' => 'relationship',
					'instructions' => 'What is my jam?',
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
						'value' => 'post',
					),
					array(
						'param' => 'post_category',
						'operator' => '==',
						'value' => 'category:jams',
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

add_action( 'init', 'shiver_register_acf_group_jam_fields' );