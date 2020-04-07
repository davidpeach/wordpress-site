<?php

class PostType
{
	public static function setup()
	{
		add_action( 'init', [get_called_class(), 'cptui_register_my_cpts_archive_content']);

		if( function_exists('acf_add_local_field_group') ) {
			add_action( 'init', [get_called_class(), 'cptui_register_my_cpts_archive_content_fields']);
		}
	}

	public static function cptui_register_my_cpts_archive_content() {

		/**
		 * Post Type: Archive Content.
		 */

		$labels = [
			"name" => __( "Archive Content", "shiver" ),
			"singular_name" => __( "Archive Content", "shiver" ),
		];

		$args = [
			"label" => __( "Archive Content", "shiver" ),
			"labels" => $labels,
			"description" => "",
			"public" => true,
			"publicly_queryable" => false,
			"show_ui" => true,
			"show_in_rest" => true,
			"rest_base" => "",
			"rest_controller_class" => "WP_REST_Posts_Controller",
			"has_archive" => false,
			"show_in_menu" => true,
			"show_in_nav_menus" => false,
			"delete_with_user" => false,
			"exclude_from_search" => true,
			"capability_type" => "post",
			"map_meta_cap" => true,
			"hierarchical" => false,
			"rewrite" => [ "slug" => "archive_content", "with_front" => true ],
			"query_var" => true,
			"supports" => [ "title", "editor", "thumbnail" ],
		];

		register_post_type( "archive_content", $args );
	}

	public static function cptui_register_my_cpts_archive_content_fields() {
		acf_add_local_field_group(array(
			'key' => 'group_5e8b6078a3219',
			'title' => 'Archive Content Fields',
			'fields' => array(
				array(
					'key' => 'field_5e8b61eaef79f',
					'label' => 'Taxonomy',
					'name' => 'taxonomy',
					'type' => 'taxonomy',
					'instructions' => 'Select the Taxonomy that this content will be for.',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'taxonomy' => 'post_tag',
					'field_type' => 'select',
					'allow_null' => 1,
					'add_term' => 1,
					'save_terms' => 0,
					'load_terms' => 0,
					'return_format' => 'id',
					'multiple' => 0,
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'archive_content',
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
	}
}
