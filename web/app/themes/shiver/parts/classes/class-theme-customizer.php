<?php

/* ---------------------------------------------------------------------------------------------
   CUSTOMIZER SETTINGS
   --------------------------------------------------------------------------------------------- */

if ( ! class_exists( 'shiver_Customize' ) ) :
	class shiver_Customize {

		public static function shiver_register( $wp_customize ) {

			/* ------------------------------------------------------------------------
			 * Site Title & Description
			 * ------------------------------------------------------------------------ */

			$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
			$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';

			$wp_customize->selective_refresh->add_partial( 'blogname', array(
				'selector'        => '.site-title a',
				'render_callback' => 'shiver_customize_partial_blogname',
			) );

			$wp_customize->selective_refresh->add_partial( 'blogdescription', array(
				'selector'        => '.site-description',
				'render_callback' => 'shiver_customize_partial_blogdescription',
			) );

			/* ------------------------------------------------------------------------
			 * Site Identity
			 * ------------------------------------------------------------------------ */

			/* 2X Header Logo ---------------- */

			$wp_customize->add_setting( 'shiver_retina_logo', array(
				'capability' 		=> 'edit_theme_options',
				'sanitize_callback' => 'shiver_sanitize_checkbox',
				'transport'			=> 'postMessage',
			) );

			$wp_customize->add_control( 'shiver_retina_logo', array(
				'type' 			=> 'checkbox',
				'section' 		=> 'title_tagline',
				'priority'		=> 10,
				'label' 		=> __( 'Retina logo', 'shiver' ),
				'description' 	=> __( 'Scales the logo to half its uploaded size, making it sharp on high-res screens.', 'shiver' ),
			) );

			/* ------------------------------------------------------------------------
			 * Color Schemes
			 * ------------------------------------------------------------------------ */

			$wp_customize->add_section( 'shiver_color_schemes', array(
				'title' 		=> __( 'Color Schemes', 'shiver' ),
				'priority' 		=> 40,
				'capability' 	=> 'edit_theme_options',
				'description' 	=> __( 'Select which color scheme to use.', 'shiver' ),
			) );

			/* Color Scheme Selector --------- */

			$color_schemes = shiver_get_color_schemes();

			if ( $color_schemes ) {

				$wp_customize->add_setting( 'shiver_color_schemes_selector', array(
					'default' 			=> 'default',
					'sanitize_callback' => 'shiver_sanitize_select',
					'transport'			=> 'refresh',
				) );

				$wp_customize->add_control( new shiver_Colour_Scheme_Control( $wp_customize, 'shiver_color_schemes_selector', array(
					'label' 		=> __( 'Color Schemes', 'shiver' ),
					'description'	=> __( 'Selecting a color scheme will update the settings on the "Colors" Customizer panel.', 'shiver' ),
					'section' 		=> 'shiver_color_schemes',
					'settings' 		=> 'shiver_color_schemes_selector',
					'transport'		=> 'postMessage',
					'choices' 		=> $color_schemes,
				) ) );

			}

			/* ------------------------------------------------------------------------
			 * Colors
			 * ------------------------------------------------------------------------ */

			$shiver_accent_color_options = self::shiver_get_color_options();

			// Loop over the color options and add them to the customizer
			foreach ( $shiver_accent_color_options as $color_option_name => $color_option ) {

				$wp_customize->add_setting( $color_option_name, array(
					'default' 			=> $color_option['default'],
					'type' 				=> 'theme_mod',
					'sanitize_callback' => 'sanitize_hex_color',
				) );

				$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $color_option_name, array(
					'label' 		=> $color_option['label'],
					'section' 		=> 'colors',
					'settings' 		=> $color_option_name,
					'priority' 		=> 10,
				) ) );

			}

			// Update background color with postMessage, so inline CSS output is updated as well
			$wp_customize->get_setting( 'background_color' )->transport = 'refresh';

			/* ------------------------------------------------------------------------
			 * Fonts
			 * ------------------------------------------------------------------------ */

			$wp_customize->add_section( 'shiver_fonts_options', array(
				'title' 		=> __( 'Fonts', 'shiver' ),
				'priority' 		=> 40,
				'capability' 	=> 'edit_theme_options',
				'description' 	=> __( 'Specify which fonts to use. shiver supports all fonts on <a href="https://fonts.google.com" target="_blank">Google Fonts</a> and all <a href="https://www.w3schools.com/cssref/css_websafe_fonts.asp" target="_blank">web safe fonts</a>.', 'shiver' ),
			) );

			/* Font Options ------------------ */

			$shiver_font_options = apply_filters( 'shiver_font_options', array(
				'shiver_body_font' => array(
					'default'	=> '',
					'label'		=> __( 'Body Font', 'shiver' ),
					'slug'		=> 'body'
				),
				'shiver_headings_font' => array(
					'default'	=> 'Merriweather',
					'label'		=> __( 'Headings Font', 'shiver' ),
					'slug'		=> 'headings'
				),
			) );

			// Loop over the font options and add them to the customizer
			foreach ( $shiver_font_options as $font_option_name => $font_option ) {
				$wp_customize->add_setting( $font_option_name, array(
					'default' 			=> $font_option['default'],
					'sanitize_callback' => 'wp_filter_nohtml_kses',
					'type'				=> 'theme_mod',
				) );

				$wp_customize->add_control( $font_option_name, array(
					'type'			=> 'text',
					'label' 		=> $font_option['label'],
					'description'	=> self::shiver_suggested_fonts_data_list( $font_option['slug'] ),
					'section' 		=> 'shiver_fonts_options',
					'input_attrs' 	=> array(
						'autocapitalize'	=> 'off',
						'autocomplete'		=> 'off',
						'autocorrect'		=> 'off',
						'class'				=> 'font-suggestions',
						'list'  			=> 'shiver-suggested-fonts-list-' . $font_option['slug'],
						'placeholder' 		=> __( 'Enter the font name', 'shiver' ),
						'spellcheck'		=> 'false',
					),
				) );
			}

			/* Separator --------------------- */

			$wp_customize->add_setting( 'shiver_fonts_separator_1', array(
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			) );

			$wp_customize->add_control( new shiver_Separator_Control( $wp_customize, 'shiver_fonts_separator_1', array(
				'section'		=> 'shiver_fonts_options',
			) ) );

			/* Headings Weight --------------- */

			$wp_customize->add_setting( 'shiver_headings_weight', array(
				'default' 			=> '700',
				'sanitize_callback' => 'shiver_sanitize_select',
			) );

			$wp_customize->add_control( 'shiver_headings_weight', array(
				'label' 		=> __( 'Headings Weight', 'shiver' ),
				'description'	=> __( 'Note: All fonts do not support all weights.', 'shiver' ),
				'section' 		=> 'shiver_fonts_options',
				'settings' 		=> 'shiver_headings_weight',
				'type' 			=> 'select',
				'choices' 		=> array(
					'100' 			=> __( 'Thin (100)', 'shiver' ),
					'200' 			=> __( 'Ultra Light (200)', 'shiver' ),
					'300' 			=> __( 'Light (300)', 'shiver' ),
					'400' 			=> __( 'Normal (400)', 'shiver' ),
					'500' 			=> __( 'Medium (500)', 'shiver' ),
					'600' 			=> __( 'Semi Bold (600)', 'shiver' ),
					'700' 			=> __( 'Bold (700)', 'shiver' ),
					'800' 			=> __( 'Extra Bold (800)', 'shiver' ),
					'900' 			=> __( 'Black (900)', 'shiver' ),
				),
			) );

			/* Headings Text Case ------------ */

			$wp_customize->add_setting( 'shiver_headings_letter_case', array(
				'default' 			=> 'normal',
				'sanitize_callback' => 'shiver_sanitize_select',
			) );

			$wp_customize->add_control( 'shiver_headings_letter_case', array(
				'label' 		=> __( 'Headings Case', 'shiver' ),
				'section' 		=> 'shiver_fonts_options',
				'settings' 		=> 'shiver_headings_letter_case',
				'type' 			=> 'select',
				'choices' 		=> array(
					'normal' 		=> __( 'Normal', 'shiver' ),
					'uppercase' 	=> __( 'Uppercase', 'shiver' ),
					'lowercase' 	=> __( 'Lowercase', 'shiver' ),
				),
			) );

			/* Headings Letter Spacing ------- */

			$wp_customize->add_setting( 'shiver_headings_letterspacing', array(
				'default' 			=> 'normal',
				'sanitize_callback' => 'shiver_sanitize_select',
			) );

			$wp_customize->add_control( 'shiver_headings_letterspacing', array(
				'label' 		=> __( 'Headings Letterspacing', 'shiver' ),
				'section' 		=> 'shiver_fonts_options',
				'settings' 		=> 'shiver_headings_letterspacing',
				'type' 			=> 'select',
				'choices' 		=> array(
					'-0_3125' 		=> __( '-50%', 'shiver' ),
					'-0_28125' 		=> __( '-45%', 'shiver' ),
					'-0_25' 		=> __( '-40%', 'shiver' ),
					'-0_21875' 		=> __( '-35%', 'shiver' ),
					'-0_1875' 		=> __( '-30%', 'shiver' ),
					'-0_15625' 		=> __( '-25%', 'shiver' ),
					'-0_125' 		=> __( '-20%', 'shiver' ),
					'-0_09375' 		=> __( '-15%', 'shiver' ),
					'-0_0625' 		=> __( '-10%', 'shiver' ),
					'-0_03125' 		=> __( '-5%', 'shiver' ),
					'normal' 		=> __( 'Normal', 'shiver' ),
					'0_03125' 		=> __( '5%', 'shiver' ),
					'0_0625' 		=> __( '10%', 'shiver' ),
					'0_09375' 		=> __( '15%', 'shiver' ),
					'0_125' 		=> __( '20%', 'shiver' ),
					'0_15625' 		=> __( '25%', 'shiver' ),
					'0_1875' 		=> __( '30%', 'shiver' ),
					'0_21875' 		=> __( '35%', 'shiver' ),
					'0_25' 			=> __( '40%', 'shiver' ),
					'0_28125' 		=> __( '45%', 'shiver' ),
					'0_3125' 		=> __( '50%', 'shiver' ),
				),
			) );

			/* Separator --------------------- */

			$wp_customize->add_setting( 'shiver_fonts_separator_2', array(
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			) );

			$wp_customize->add_control( new shiver_Separator_Control( $wp_customize, 'shiver_fonts_separator_2', array(
				'section'		=> 'shiver_fonts_options',
			) ) );

			/* Languages --------------------- */

			$wp_customize->add_setting( 'shiver_font_languages', array(
				'capability' 		=> 'edit_theme_options',
				'default'           => array( 'latin' ),
				'sanitize_callback' => 'shiver_sanitize_multiple_checkboxes',
			) );

			$wp_customize->add_control( new shiver_Customize_Control_Checkbox_Multiple( $wp_customize, 'shiver_font_languages', array(
				'section' 		=> 'shiver_fonts_options',
				'label'   		=> __( 'Languages', 'shiver' ),
				'description'	=> __( 'Note: All fonts do not support all languages. Check Google Fonts to make sure.', 'shiver' ),
				'choices' 		=> apply_filters( 'shiver_font_languages', array(
					'latin'			=> __( 'Latin', 'shiver' ),
					'latin-ext'		=> __( 'Latin Extended', 'shiver' ),
					'cyrillic'		=> __( 'Cyrillic', 'shiver' ),
					'cyrillic-ext'	=> __( 'Cyrillic Extended', 'shiver' ),
					'greek'			=> __( 'Greek', 'shiver' ),
					'greek-ext'		=> __( 'Greek Extended', 'shiver' ),
					'vietnamese'	=> __( 'Vietnamese', 'shiver' ),
				) ),
			) ) );


			/* ------------------------------------------------------------------------
			 * Fallback Image Options
			 * ------------------------------------------------------------------------ */

			$wp_customize->add_section( 'shiver_image_options', array(
				'title' 		=> __( 'Images', 'shiver' ),
				'priority' 		=> 40,
				'capability' 	=> 'edit_theme_options',
				'description' 	=> __( 'Settings for images in shiver.', 'shiver' ),
			) );

			// Activate low-resolution images setting
			$wp_customize->add_setting( 'shiver_activate_low_resolution_images', array(
				'capability' 		=> 'edit_theme_options',
				'default'			=> false,
				'sanitize_callback' => 'shiver_sanitize_checkbox'
			) );

			$wp_customize->add_control( 'shiver_activate_low_resolution_images', array(
				'type' 			=> 'checkbox',
				'section' 		=> 'shiver_image_options',
				'priority'		=> 5,
				'label' 		=> __( 'Use Low-Resolution Images', 'shiver' ),
				'description'	=> __( 'Checking this will decrease load times, but also make images look less sharp on high-resolution screens.', 'shiver' ),
			) );

			// Fallback image setting
			$wp_customize->add_setting( 'shiver_fallback_image', array(
				'capability' 		=> 'edit_theme_options',
				'sanitize_callback' => 'absint'
			) );

			$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'shiver_fallback_image', array(
				'label'			=> __( 'Fallback Image', 'shiver' ),
				'description'	=> __( 'The selected image will be used when a post is missing a featured image. A default fallback image included in the theme will be used if no image is set.', 'shiver' ),
				'priority'		=> 10,
				'mime_type'		=> 'image',
				'section' 		=> 'shiver_image_options',
			) ) );

			// Disable fallback image setting
			$wp_customize->add_setting( 'shiver_disable_fallback_image', array(
				'capability' 		=> 'edit_theme_options',
				'default'			=> false,
				'sanitize_callback' => 'shiver_sanitize_checkbox'
			) );

			$wp_customize->add_control( 'shiver_disable_fallback_image', array(
				'type' 			=> 'checkbox',
				'section' 		=> 'shiver_image_options',
				'priority'		=> 15,
				'label' 		=> __( 'Disable Fallback Image', 'shiver' )
			) );

			/* ------------------------------------------------------------------------
			 * Site Header Options
			 * ------------------------------------------------------------------------ */

			$wp_customize->add_section( 'shiver_site_header_options', array(
				'title' 		=> __( 'Site Header', 'shiver' ),
				'priority' 		=> 40,
				'capability' 	=> 'edit_theme_options',
				'description' 	=> __( 'Settings for the site header.', 'shiver' ),
			) );

			/* Sticky Header ----------------- */

			$wp_customize->add_setting( 'shiver_sticky_header', array(
				'capability' 		=> 'edit_theme_options',
				'default'			=> false,
				'sanitize_callback' => 'shiver_sanitize_checkbox',
			) );

			$wp_customize->add_control( 'shiver_sticky_header', array(
				'type' 			=> 'checkbox',
				'section' 		=> 'shiver_site_header_options',
				'priority'		=> 10,
				'label' 		=> __( 'Sticky Header', 'shiver' ),
				'description' 	=> __( 'Stick the header to the top of the window when the visitor scrolls.', 'shiver' ),
			) );

			/* Disable Header Search --------- */

			$wp_customize->add_setting( 'shiver_disable_header_search', array(
				'capability' 		=> 'edit_theme_options',
				'default'			=> false,
				'sanitize_callback' => 'shiver_sanitize_checkbox',
			) );

			$wp_customize->add_control( 'shiver_disable_header_search', array(
				'type' 			=> 'checkbox',
				'section' 		=> 'shiver_site_header_options',
				'priority'		=> 10,
				'label' 		=> __( 'Disable Search Button', 'shiver' ),
				'description' 	=> __( 'Check to disable the search button in the header.', 'shiver' ),
			) );

			/* Disable Menu Modal on Desktop - */

			$wp_customize->add_setting( 'shiver_disable_menu_modal_on_desktop', array(
				'capability' 		=> 'edit_theme_options',
				'default'			=> false,
				'sanitize_callback' => 'shiver_sanitize_checkbox',
			) );

			$wp_customize->add_control( 'shiver_disable_menu_modal_on_desktop', array(
				'type' 			=> 'checkbox',
				'section' 		=> 'shiver_site_header_options',
				'priority'		=> 10,
				'label' 		=> __( 'Disable Menu Modal on Desktop', 'shiver' ),
				'description' 	=> __( 'Check to display a regular menu on desktop screens, instead of the search and menu toggles.', 'shiver' ),
			) );

			/* ------------------------------------------------------------------------
			 * Posts
			 * ------------------------------------------------------------------------ */

			$wp_customize->add_panel( 'shiver_post_options', array(
				'priority'       => 41,
				'capability'     => 'edit_theme_options',
				'theme_supports' => '',
				'title'          => __( 'Posts', 'shiver' ),
				'description'    => '',
			) );

			$wp_customize->add_section( 'shiver_single_post_options', array(
				'title' 		=> __( 'Single Post', 'shiver' ),
				'priority' 		=> 10,
				'capability' 	=> 'edit_theme_options',
				'description' 	=> __( 'Settings for single posts.', 'shiver' ),
				'panel'			=> 'shiver_post_options',
			) );

			$wp_customize->add_section( 'shiver_post_archive_options', array(
				'title' 		=> __( 'Post Archive', 'shiver' ),
				'priority' 		=> 20,
				'capability' 	=> 'edit_theme_options',
				'description' 	=> __( 'Settings for post archives.', 'shiver' ),
				'panel'			=> 'shiver_post_options',
			) );

			/* ------------------------------------------------------------------------
			 * Posts > Single Post
			 * ------------------------------------------------------------------------ */

			/* Enable Related Posts ---------- */

			$wp_customize->add_setting( 'shiver_enable_related_posts', array(
				'capability' 		=> 'edit_theme_options',
				'default'			=> true,
				'sanitize_callback' => 'shiver_sanitize_checkbox',
			) );

			$wp_customize->add_control( 'shiver_enable_related_posts', array(
				'type' 			=> 'checkbox',
				'section' 		=> 'shiver_single_post_options',
				'label' 		=> __( 'Show Related Posts', 'shiver' ),
				'description' 	=> __( 'Check to show related posts on single posts.', 'shiver' ),
			) );

			/* Separator --------------------- */

			$wp_customize->add_setting( 'shiver_single_post_separator_1', array(
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			) );

			$wp_customize->add_control( new shiver_Separator_Control( $wp_customize, 'shiver_single_post_separator_1', array(
				'section'		=> 'shiver_single_post_options',
			) ) );

			/* Post Meta Single -------------- */

			$post_meta_choices = array(
				'author'		=> __( 'Author', 'shiver' ),
				'categories'	=> __( 'Categories', 'shiver' ),
				'comments'		=> __( 'Comments', 'shiver' ),
				'edit-link'		=> __( 'Edit link (for logged in users)', 'shiver' ),
				'post-date'		=> __( 'Post date', 'shiver' ),
				'sticky'		=> __( 'Sticky status', 'shiver' ),
				'tags'			=> __( 'Tags', 'shiver' ),
			);

			if ( post_type_exists( 'jetpack-portfolio' ) ) {
				if ( taxonomy_exists( 'jetpack-portfolio-type' ) ) {
					$post_meta_choices['jetpack-portfolio-type'] = __( 'Portfolio types', 'shiver' );
				}
				if ( taxonomy_exists( 'jetpack-portfolio-tag' ) ) {
					$post_meta_choices['jetpack-portfolio-tag'] = __( 'Project tags', 'shiver' );
				}
			}

			$post_meta_choices = apply_filters( 'shiver_post_meta_choices_in_the_customizer', $post_meta_choices );

			// Post Meta Single Top Setting
			$wp_customize->add_setting( 'shiver_post_meta_single_top', array(
				'capability' 		=> 'edit_theme_options',
				'default'           => array( 'post-date', 'categories' ),
				'sanitize_callback' => 'shiver_sanitize_multiple_checkboxes',
			) );

			$wp_customize->add_control( new shiver_Customize_Control_Checkbox_Multiple( $wp_customize, 'shiver_post_meta_single_top', array(
				'section' 		=> 'shiver_single_post_options',
				'label'   		=> __( 'Top Post Meta', 'shiver' ),
				'description'	=> __( 'Select post meta to display above the content.', 'shiver' ),
				'choices' 		=> $post_meta_choices,
			) ) );

			// Post Meta Single Bottom Setting
			$wp_customize->add_setting( 'shiver_post_meta_single_bottom', array(
				'capability' 		=> 'edit_theme_options',
				'default'           => array( 'tags' ),
				'sanitize_callback' => 'shiver_sanitize_multiple_checkboxes',
			) );

			$wp_customize->add_control( new shiver_Customize_Control_Checkbox_Multiple( $wp_customize, 'shiver_post_meta_single_bottom', array(
				'section' 		=> 'shiver_single_post_options',
				'label'   		=> __( 'Bottom Post Meta', 'shiver' ),
				'description'	=> __( 'Select post meta to display below the content.', 'shiver' ),
				'choices' 		=> $post_meta_choices,
			) ) );

			/* ------------------------------------------------------------------------
			 * Posts > Archive Posts
			 * ------------------------------------------------------------------------ */

			/* Pagination Type --------------- */

			$wp_customize->add_setting( 'shiver_pagination_type', array(
				'capability' 		=> 'edit_theme_options',
				'default'           => 'button',
				'sanitize_callback' => 'shiver_sanitize_select',
			) );

			$wp_customize->add_control( 'shiver_pagination_type', array(
				'type'			=> 'select',
				'section' 		=> 'shiver_post_archive_options',
				'label'   		=> __( 'Pagination Type', 'shiver' ),
				'description'	=> __( 'Determines how the pagination on archive pages should be displayed.', 'shiver' ),
				'choices' 		=> array(
					'button'		=> __( 'Load more on button click', 'shiver' ),
					'scroll'		=> __( 'Load more on scroll', 'shiver' ),
					'links'			=> __( 'Previous and next page links', 'shiver' ),
				),
			) );

			/* Number of Post Columns -------- */

			$wp_customize->add_setting( 'shiver_post_grid_columns', array(
				'capability' 		=> 'edit_theme_options',
				'default'           => '2',
				'sanitize_callback' => 'shiver_sanitize_select',
			) );

			$wp_customize->add_control( 'shiver_post_grid_columns', array(
				'type'			=> 'select',
				'section' 		=> 'shiver_post_archive_options',
				'label'   		=> __( 'Number of Columns', 'shiver' ),
				'description'	=> __( 'The maximum number of columns to use in the post grid.', 'shiver' ),
				'choices' 		=> array(
					'1'				=> __( 'One', 'shiver' ),
					'2'				=> __( 'Two', 'shiver' ),
					'3'				=> __( 'Three', 'shiver' ),
					'4'				=> __( 'Four', 'shiver' ),
				),
			) );

			/* Preview Image Aspect Ratio ---- */

			$wp_customize->add_setting( 'shiver_preview_image_aspect_ratio', array(
				'capability' 		=> 'edit_theme_options',
				'default'           => '16x10',
				'sanitize_callback' => 'shiver_sanitize_select',
			) );

			$wp_customize->add_control( 'shiver_preview_image_aspect_ratio', array(
				'type'			=> 'select',
				'section' 		=> 'shiver_post_archive_options',
				'label'   		=> __( 'Preview Image Aspect Ratio', 'shiver' ),
				'description'	=> __( 'Aspect ratio of featured images on archive pages.', 'shiver' ),
				'choices' 		=> array(
					'9x16'			=> __( '9:16', 'shiver' ),
					'10x16'			=> __( '10:16', 'shiver' ),
					'3x4'			=> __( '3:4', 'shiver' ),
					'1x1'			=> __( '1:1', 'shiver' ),
					'4x3'			=> __( '4:3', 'shiver' ),
					'16x10'			=> __( '16:10', 'shiver' ),
					'16x9'			=> __( '16:9', 'shiver' ),
					'original'		=> __( 'Original aspect ratio of each image', 'shiver' ),
				),
			) );

			/* Separator --------------------- */

			$wp_customize->add_setting( 'shiver_post_archive_separator_1', array(
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			) );

			$wp_customize->add_control( new shiver_Separator_Control( $wp_customize, 'shiver_post_archive_separator_1', array(
				'section'		=> 'shiver_post_archive_options',
			) ) );

			/* Show Archive Header On Home --- */

			$wp_customize->add_setting( 'shiver_show_archive_header_on_home', array(
				'capability' 		=> 'edit_theme_options',
				'default'			=> true,
				'sanitize_callback' => 'shiver_sanitize_checkbox',
			) );

			$wp_customize->add_control( 'shiver_show_archive_header_on_home', array(
				'type' 			=> 'checkbox',
				'section' 		=> 'shiver_post_archive_options',
				'label' 		=> __( 'Show Archive Header On The Blog Page', 'shiver' ),
				'description' 	=> __( 'Whether to display the archive title and description on the main posts page.', 'shiver' ),
			) );

			/* Enable Excerpts --------------- */

			$wp_customize->add_setting( 'shiver_display_excerpts', array(
				'capability' 		=> 'edit_theme_options',
				'default'			=> false,
				'sanitize_callback' => 'shiver_sanitize_checkbox',
			) );

			$wp_customize->add_control( 'shiver_display_excerpts', array(
				'type' 			=> 'checkbox',
				'section' 		=> 'shiver_post_archive_options',
				'label' 		=> __( 'Show Excerpts', 'shiver' ),
				'description' 	=> __( 'Whether to display excerpts in post previews.', 'shiver' ),
			) );

			/* Separator --------------------- */

			$wp_customize->add_setting( 'shiver_post_archive_separator_2', array(
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			) );

			$wp_customize->add_control( new shiver_Separator_Control( $wp_customize, 'shiver_post_archive_separator_2', array(
				'section'		=> 'shiver_post_archive_options',
			) ) );

			/* Post Meta Archive ------------- */

			$wp_customize->add_setting( 'shiver_post_meta_archive', array(
				'capability' 		=> 'edit_theme_options',
				'default'           => array( 'post-date' ),
				'sanitize_callback' => 'shiver_sanitize_multiple_checkboxes',
			) );

			$wp_customize->add_control( new shiver_Customize_Control_Checkbox_Multiple( $wp_customize, 'shiver_post_meta_archive', array(
				'section' 		=> 'shiver_post_archive_options',
				'label'   		=> __( 'Archive Post Meta', 'shiver' ),
				'description'	=> __( 'Select post meta to display on archive pages.', 'shiver' ),
				'choices' 		=> $post_meta_choices,
			) ) );

			/* ------------------------------------------------------------------------
			 * Template: Cover Template
			 * ------------------------------------------------------------------------ */

			$wp_customize->add_section( 'shiver_cover_template_options', array(
				'title' 		=> __( 'Cover Template', 'shiver' ),
				'capability' 	=> 'edit_theme_options',
				'description' 	=> __( 'Settings for the "Cover Template" page template.', 'shiver' ),
				'priority'       => 42,
			) );

			/* Overlay Fixed Background ------ */

			$wp_customize->add_setting( 'shiver_cover_template_fixed_background', array(
				'capability' 		=> 'edit_theme_options',
				'default'			=> true,
				'sanitize_callback' => 'shiver_sanitize_checkbox',
			) );

			$wp_customize->add_control( 'shiver_cover_template_fixed_background', array(
				'type' 			=> 'checkbox',
				'section' 		=> 'shiver_cover_template_options',
				'label' 		=> __( 'Fixed Background Image', 'shiver' ),
				'description' 	=> __( 'Creates a parallax effect when the visitor scrolls.', 'shiver' ),
			) );

			/* Overlay Fade Text ------------- */

			$wp_customize->add_setting( 'shiver_cover_template_fade_text', array(
				'capability' 		=> 'edit_theme_options',
				'default'			=> true,
				'sanitize_callback' => 'shiver_sanitize_checkbox',
			) );

			$wp_customize->add_control( 'shiver_cover_template_fade_text', array(
				'type' 			=> 'checkbox',
				'section' 		=> 'shiver_cover_template_options',
				'label' 		=> __( 'Fade Text On Scroll', 'shiver' ),
				'description' 	=> __( 'Fade out the text in the header as the visitor scrolls down the page.', 'shiver' ),
			) );

			/* Separator --------------------- */

			$wp_customize->add_setting( 'shiver_cover_template_separator_1', array(
				'sanitize_callback' => 'wp_filter_nohtml_kses',
			) );

			$wp_customize->add_control( new shiver_Separator_Control( $wp_customize, 'shiver_cover_template_separator_1', array(
				'section'		=> 'shiver_cover_template_options',
			) ) );

			/* Overlay Background Color ------ */

			$wp_customize->add_setting( 'shiver_cover_template_overlay_background_color', array(
				'default' 			=> get_theme_mod( 'shiver_accent_color', '#007C89' ),
				'type' 				=> 'theme_mod',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'shiver_cover_template_overlay_background_color', array(
				'label' 		=> __( 'Image Overlay Background Color', 'shiver' ),
				'description'	=> __( 'The color used for the featured image overlay. Defaults to the accent color.', 'shiver' ),
				'section' 		=> 'shiver_cover_template_options',
				'settings' 		=> 'shiver_cover_template_overlay_background_color',
			) ) );

			/* Overlay Text Color ------------ */

			$wp_customize->add_setting( 'shiver_cover_template_overlay_text_color', array(
				'default' 			=> '#FFFFFF',
				'type' 				=> 'theme_mod',
				'sanitize_callback' => 'sanitize_hex_color',
			) );

			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'shiver_cover_template_overlay_text_color', array(
				'label' 		=> __( 'Image Overlay Text Color', 'shiver' ),
				'description'	=> __( 'The color used for the text in the featured image overlay.', 'shiver' ),
				'section' 		=> 'shiver_cover_template_options',
				'settings' 		=> 'shiver_cover_template_overlay_text_color',
			) ) );

			/* Overlay Blend Mode ------------ */

			$wp_customize->add_setting( 'shiver_cover_template_overlay_blend_mode', array(
				'default' 			=> 'multiply',
				'sanitize_callback' => 'shiver_sanitize_select',
			) );

			$wp_customize->add_control( 'shiver_cover_template_overlay_blend_mode', array(
				'label' 		=> __( 'Image Overlay Blend Mode', 'shiver' ),
				'description'	=> __( 'How the overlay color will blend with the image. Some browsers, like Internet Explorer and Edge, only support the "Normal" mode.', 'shiver' ),
				'section' 		=> 'shiver_cover_template_options',
				'settings' 		=> 'shiver_cover_template_overlay_blend_mode',
				'type' 			=> 'select',
				'choices' 		=> array(
					'normal' 			=> __( 'Normal', 'shiver' ),
					'multiply' 			=> __( 'Multiply', 'shiver' ),
					'screen' 			=> __( 'Screen', 'shiver' ),
					'overlay' 			=> __( 'Overlay', 'shiver' ),
					'darken' 			=> __( 'Darken', 'shiver' ),
					'lighten' 			=> __( 'Lighten', 'shiver' ),
					'color-dodge' 		=> __( 'Color Dodge', 'shiver' ),
					'color-burn' 		=> __( 'Color Burn', 'shiver' ),
					'hard-light' 		=> __( 'Hard Light', 'shiver' ),
					'soft-light' 		=> __( 'Soft Light', 'shiver' ),
					'difference' 		=> __( 'Difference', 'shiver' ),
					'exclusion' 		=> __( 'Exclusion', 'shiver' ),
					'hue' 				=> __( 'Hue', 'shiver' ),
					'saturation' 		=> __( 'Saturation', 'shiver' ),
					'color' 			=> __( 'Color', 'shiver' ),
					'luminosity' 		=> __( 'Luminosity', 'shiver' ),
				),
			) );

			/* Overlay Color Opacity --------- */

			$wp_customize->add_setting( 'shiver_cover_template_overlay_opacity', array(
				'default' 			=> '80',
				'sanitize_callback' => 'shiver_sanitize_select',
			) );

			$wp_customize->add_control( 'shiver_cover_template_overlay_opacity', array(
				'label' 		=> __( 'Image Overlay Opacity', 'shiver' ),
				'description'	=> __( 'Make sure that the value is high enough that the text is readable.', 'shiver' ),
				'section' 		=> 'shiver_cover_template_options',
				'settings' 		=> 'shiver_cover_template_overlay_opacity',
				'type' 			=> 'select',
				'choices' 		=> array(
					'0' 			=> __( '0%', 'shiver' ),
					'10' 			=> __( '10%', 'shiver' ),
					'20' 			=> __( '20%', 'shiver' ),
					'30' 			=> __( '30%', 'shiver' ),
					'40' 			=> __( '40%', 'shiver' ),
					'50' 			=> __( '50%', 'shiver' ),
					'60' 			=> __( '60%', 'shiver' ),
					'70' 			=> __( '70%', 'shiver' ),
					'80' 			=> __( '80%', 'shiver' ),
					'90' 			=> __( '90%', 'shiver' ),
					'100' 			=> __( '100%', 'shiver' ),
				),
			) );


			/* Sanitation Functions ---------- */

			// Sanitize boolean for checkbox
			function shiver_sanitize_checkbox( $checked ) {
				return ( ( isset( $checked ) && true == $checked ) ? true : false );
			}

			// Sanitize booleans for multiple checkboxes
			function shiver_sanitize_multiple_checkboxes( $values ) {
				$multi_values = ! is_array( $values ) ? explode( ',', $values ) : $values;
				return ! empty( $multi_values ) ? array_map( 'sanitize_text_field', $multi_values ) : array();
			}

			// Sanitize radio
			function shiver_sanitize_radio( $input, $setting ) {
				$input = sanitize_key( $input );
				$choices = $setting->manager->get_control( $setting->id )->choices;
				return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
			}

			// Sanitize select
			function shiver_sanitize_select( $input, $setting ) {
				$input = sanitize_key( $input );
				$choices = $setting->manager->get_control( $setting->id )->choices;
				return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
			}

		}

		// Return an array of suggested fonts
		public static function shiver_suggested_fonts_data_list( $font_option ) {

			$suggested_fonts = shiver_Google_Fonts::get_suggested_fonts( $font_option );

			$list = '<datalist id="shiver-suggested-fonts-list-' . esc_attr( $font_option ) . '">';
			foreach ( $suggested_fonts as $font ) {
				$list .= '<option value="' . esc_attr( $font ) . '">';
			}
			$list .= '</datalist>';

			return $list;
		}

		// Return the sitewide color options included
		public static function shiver_get_color_options() {
			return apply_filters( 'shiver_accent_color_options', array(
				'shiver_accent_color' => array(
					'default'	=> '#007C89',
					'label'		=> __( 'Accent Color', 'shiver' ),
					'slug'		=> 'accent',
				),
				'shiver_primary_text_color' => array(
					'default'	=> '#1A1B1F',
					'label'		=> __( 'Primary Text Color', 'shiver' ),
					'slug'		=> 'primary',
				),
				'shiver_headings_text_color' => array(
					'default'	=> '#1A1B1F',
					'label'		=> __( 'Headings Text Color', 'shiver' ),
					'slug'		=> 'headings',
				),
				'shiver_buttons_background_color' => array(
					'default'	=> '#007C89',
					'label'		=> __( 'Buttons Background Color', 'shiver' ),
					'slug'		=> 'buttons-background',
				),
				'shiver_buttons_text_color' => array(
					'default'	=> '#FFFFFF',
					'label'		=> __( 'Buttons Text Color', 'shiver' ),
					'slug'		=> 'buttons-text',
				),
				'shiver_secondary_text_color' => array(
					'default'	=> '#747579',
					'label'		=> __( 'Secondary Text Color', 'shiver' ),
					'slug'		=> 'secondary',
				),
				'shiver_border_color' => array(
					'default'	=> '#E1E1E3',
					'label'		=> __( 'Border Color', 'shiver' ),
					'slug'		=> 'border',
				),
				'shiver_light_background_color' => array(
					'default'	=> '#F1F1F3',
					'label'		=> __( 'Light Background Color', 'shiver' ),
					'slug'		=> 'light-background',
				),
			) );
		}

		// Initiate the customize controls js
		public static function shiver_customize_controls() {
			wp_enqueue_script( 'shiver-customize-controls', get_template_directory_uri() . '/assets/js/customize-controls.js', array( 'jquery', 'customize-controls' ), '', true );

			// Setup AJAX
			$ajax_url = admin_url( 'admin-ajax.php' );

			// AJAX Color Schemes
			wp_localize_script( 'shiver-customize-controls', 'shiver_ajax_get_color_scheme_colors', array(
				'ajaxurl'   => esc_url( $ajax_url ),
			) );
		}

	}

	// Setup the Theme Customizer settings and controls
	add_action( 'customize_register', array( 'shiver_Customize', 'shiver_register' ) );

	// Enqueue customize controls javascript in Theme Customizer admin screen
	add_action( 'customize_controls_init', array( 'shiver_Customize', 'shiver_customize_controls' ) );

endif;


/* ---------------------------------------------------------------------------------------------
   CUSTOM CONTROLS
   --------------------------------------------------------------------------------------------- */


if ( class_exists( 'WP_Customize_Control' ) ) :

	/* Separator Control --------------------- */

	if ( ! class_exists( 'shiver_Separator_Control' ) ) :
		class shiver_Separator_Control extends WP_Customize_Control {

			public function render_content() {
				echo '<hr/>';
			}

		}
	endif;

	/* Image Radio Button Control ------------------ */
	/* Based on a solution by @maddisondesigns: https://github.com/maddisondesigns/customizer-custom-controls */

	if ( ! class_exists( 'shiver_Colour_Scheme_Control' ) ) :
		class shiver_Colour_Scheme_Control extends WP_Customize_Control {

			// Set the type
			public $type = 'shiver_image_radio_button';

			// Enqueue custom styles
			public function enqueue() {
				wp_enqueue_style( 'shiver-customizer-custom-controls-css', get_template_directory_uri() . '/assets/css/customizer.css', array(), '1.0', 'all' );
			}

			// Render the content
			public function render_content() {
				?>

				<div class="shiver-color-scheme-control">

					<?php if ( ! empty( $this->label ) ) : ?>
						<span class="customize-control-title"><?php echo wp_kses_post( $this->label ); ?></span>
					<?php endif; ?>

					<?php if ( ! empty( $this->description ) ) : ?>
						<span class="customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
					<?php endif; ?>

					<div class="radio-button-labels">

						<?php foreach ( $this->choices as $key => $value ) :

							$accent_color 		= isset( $value['colors']['shiver_accent_color'] ) ? $value['colors']['shiver_accent_color'] : '';
							$primary_color 		= isset( $value['colors']['shiver_primary_text_color'] ) ? $value['colors']['shiver_primary_text_color'] : '';
							$secondary_color 	= isset( $value['colors']['shiver_secondary_text_color'] ) ? $value['colors']['shiver_secondary_text_color'] : '';
							$background_color 	= isset( $value['colors']['background_color'] ) ? '#' . $value['colors']['background_color'] : '';

							$active = false;

							// First, check if the current option is the selected one
							if ( $this->value() === $key ) {

								// Second, make sure that the user hasn't changed any colors independently
								foreach ( $value['colors'] as $setting_name => $color_scheme_value  ) {
									$setting_value = strtoupper( get_theme_mod( $setting_name ) );

									// The colour scheme value matches the colour setting
									if ( $color_scheme_value == $setting_value ) {
										$active = true;

									// We have a mismatch between the colour scheme and the color settings, so the scheme is not active
									} else {
										$active = false;
										break;
									}
								}

								// If we're not active at this point, the chosen color scheme is no longer valid, so we can unset the color scheme setting
								if ( ! $active ) {
									set_theme_mod( 'shiver_color_schemes_selector', '' );
								}


							}

							?>
							<label class="radio-button-label">
								<input type="radio" name="<?php echo esc_attr( $this->id ); ?>" value="<?php echo esc_attr( $key ); ?>" <?php $this->link(); ?> <?php checked( $active ); ?>/>
								<div class="color-scheme-preview">
									<?php if ( $accent_color ) : ?>
										<div class="color color-accent" style="background-color: <?php echo $accent_color; ?>"></div>
									<?php endif; ?>
									<?php if ( $primary_color ) : ?>
										<div class="color color-primary" style="background-color: <?php echo $primary_color; ?>"></div>
									<?php endif; ?>
									<?php if ( $secondary_color ) : ?>
										<div class="color color-secondary" style="background-color: <?php echo $secondary_color; ?>"></div>
									<?php endif; ?>
									<?php if ( $background_color ) : ?>
										<div class="color color-background" style="background-color: <?php echo $background_color; ?>"></div>
									<?php endif; ?>
								</div><!-- .color-scheme-preview -->
								<span class="radio-button-label-text"><?php echo wp_kses_post( $value['name'] ); ?></span>
							</label>
						<?php endforeach; ?>

					</div><!-- .radio-button-labels -->

				</div><!-- .shiver-image-radio-button-control -->

			<?php
			}

		}
	endif;

endif;


/* ---------------------------------------------------------------------------------------------
   PARTIAL REFRESH FUNCTIONS
   --------------------------------------------------------------------------------------------- */

/* Render the site title for the selective refresh partial */
if ( ! function_exists( 'shiver_customize_partial_blogname' ) ) :
	function shiver_customize_partial_blogname() {
		bloginfo( 'name' );
	}
endif;

/* Render the site description for the selective refresh partial */
if ( ! function_exists( 'shiver_customize_partial_blogdescription' ) ) :
	function shiver_customize_partial_blogdescription() {
		bloginfo( 'description' );
	}
endif;


/* ---------------------------------------------------------------------------------------------
   GET COLOR SCHEMES
   Returns a filterable list with all color schemes.
   --------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'shiver_get_color_schemes' ) ) :
	function shiver_get_color_schemes() {

		return apply_filters( 'shiver_color_schemes', array(
			'default' 			=> array(
				'name'			=> _x( 'Default', 'Color scheme name', 'shiver' ),
				'colors'		=> array(
					'background_color'									=> 'FFFFFF',
					'shiver_primary_text_color'						=> '#1A1B1F',
					'shiver_headings_text_color'						=> '#1A1B1F',
					'shiver_secondary_text_color'						=> '#747579',
					'shiver_accent_color'								=> '#007C89',
					'shiver_buttons_background_color'					=> '#007C89',
					'shiver_buttons_text_color'						=> '#FFFFFF',
					'shiver_border_color'								=> '#E1E1E3',
					'shiver_light_background_color'					=> '#F1F1F3',
					'shiver_cover_template_overlay_text_color' 		=> '#FFFFFF',
					'shiver_cover_template_overlay_background_color'	=> '#007C89',
				),
			),
			'macchiato' 		=> array(
				'name'			=> _x( 'Macchiato', 'Color scheme name', 'shiver' ),
				'colors'		=> array(
					'background_color'									=> 'F6F2F0',
					'shiver_primary_text_color'						=> '#1A1A1B',
					'shiver_headings_text_color'						=> '#AE9254',
					'shiver_secondary_text_color'						=> '#747579',
					'shiver_accent_color'								=> '#AE9254',
					'shiver_buttons_background_color'					=> '#AE9254',
					'shiver_buttons_text_color'						=> '#FFFFFF',
					'shiver_border_color'								=> '#E0DDDB',
					'shiver_light_background_color'					=> '#EAE6E4',
					'shiver_cover_template_overlay_text_color' 		=> '#FFFFFF',
					'shiver_cover_template_overlay_background_color'	=> '#AE9254',
				),
			),
			'naxos' 		=> array(
				'name'			=> _x( 'Naxos', 'Color scheme name', 'shiver' ),
				'colors'		=> array(
					'background_color'									=> '0C1B31',
					'shiver_primary_text_color'						=> '#F6F2F0',
					'shiver_headings_text_color'						=> '#E9513D',
					'shiver_secondary_text_color'						=> '#808690',
					'shiver_accent_color'								=> '#E9513D',
					'shiver_buttons_background_color'					=> '#E9513D',
					'shiver_buttons_text_color'						=> '#FFFFFF',
					'shiver_border_color'								=> '#313D4F',
					'shiver_light_background_color'					=> '#1F2B40',
					'shiver_cover_template_overlay_text_color' 		=> '#F6F2F0',
					'shiver_cover_template_overlay_background_color'	=> '#0C1B31',
				),
			),
			'twisted-sisters' 	=> array(
				'name'			=> _x( 'Twisted Sisters', 'Color scheme name', 'shiver' ),
				'colors'		=> array(
					'background_color'									=> '1C1C1C',
					'shiver_primary_text_color'						=> '#FFFFFF',
					'shiver_headings_text_color'						=> '#790AFF',
					'shiver_secondary_text_color'						=> '#AAAAAA',
					'shiver_accent_color'								=> '#790AFF',
					'shiver_buttons_background_color'					=> '#790AFF',
					'shiver_buttons_text_color'						=> '#FFFFFF',
					'shiver_border_color'								=> '#3C3C3C',
					'shiver_light_background_color'					=> '#2C2C2C',
					'shiver_cover_template_overlay_text_color' 		=> '#FFFFFF',
					'shiver_cover_template_overlay_background_color'	=> '#790AFF',
				),
			),
			'taxi'				=> array(
				'name'			=> _x( 'Taxi', 'Color scheme name', 'shiver' ),
				'colors'		=> array(
					'background_color'									=> 'FFCC02',
					'shiver_primary_text_color'						=> '#000000',
					'shiver_headings_text_color'						=> '#000000',
					'shiver_secondary_text_color'						=> '#000000',
					'shiver_accent_color'								=> '#000000',
					'shiver_buttons_background_color'					=> '#000000',
					'shiver_buttons_text_color'						=> '#FFFFFF',
					'shiver_border_color'								=> '#000000',
					'shiver_light_background_color'					=> '#FFD327',
					'shiver_cover_template_overlay_text_color' 		=> '#FFFFFF',
					'shiver_cover_template_overlay_background_color'	=> '#FFCC02',
				),
			),
			'mint-condition' 	=> array(
				'name'			=> _x( 'Mint Condition', 'Color scheme name', 'shiver' ),
				'colors'		=> array(
					'background_color'									=> '292B29',
					'shiver_primary_text_color'						=> '#A6D1C9',
					'shiver_headings_text_color'						=> '#A6D1C9',
					'shiver_secondary_text_color'						=> '#A6D1C9',
					'shiver_accent_color'								=> '#A6D1C9',
					'shiver_buttons_background_color'					=> '#A6D1C9',
					'shiver_buttons_text_color'						=> '#292B29',
					'shiver_border_color'								=> '#A6D1C9',
					'shiver_light_background_color'					=> '#3D3F3D',
					'shiver_cover_template_overlay_text_color' 		=> '#A6D1C9',
					'shiver_cover_template_overlay_background_color'	=> '#292B29',
				),
			),
			'tilted-cyan' 	=> array(
				'name'			=> _x( 'Tilted Cyan', 'Color scheme name', 'shiver' ),
				'colors'		=> array(
					'background_color'									=> 'A4C5C6',
					'shiver_primary_text_color'						=> '#062421',
					'shiver_headings_text_color'						=> '#062421',
					'shiver_secondary_text_color'						=> '#547473',
					'shiver_accent_color'								=> '#CD7468',
					'shiver_buttons_background_color'					=> '#CD7468',
					'shiver_buttons_text_color'						=> '#FFFFFF',
					'shiver_border_color'								=> '#84A4A5',
					'shiver_light_background_color'					=> '#94B5B5',
					'shiver_cover_template_overlay_text_color' 		=> '#FFFFFF',
					'shiver_cover_template_overlay_background_color'	=> '#FFC3BC',
				),
			),
			'bloom' 	=> array(
				'name'			=> _x( 'Bloom', 'Color scheme name', 'shiver' ),
				'colors'		=> array(
					'background_color'									=> '05234F',
					'shiver_primary_text_color'						=> '#FFC3BC',
					'shiver_headings_text_color'						=> '#FFFFFF',
					'shiver_secondary_text_color'						=> '#FFC3BC',
					'shiver_accent_color'								=> '#FFC3BC',
					'shiver_buttons_background_color'					=> '#FFFFFF',
					'shiver_buttons_text_color'						=> '#05234F',
					'shiver_border_color'								=> '#4F6483',
					'shiver_light_background_color'					=> '#2A4369',
					'shiver_cover_template_overlay_text_color' 		=> '#FFFFFF',
					'shiver_cover_template_overlay_background_color'	=> '#05234F',
				),
			),
			'daisys' 	=> array(
				'name'			=> _x( 'Daisy’s', 'Color scheme name', 'shiver' ),
				'colors'		=> array(
					'background_color'									=> 'FFFFFF',
					'shiver_primary_text_color'						=> '#082E54',
					'shiver_headings_text_color'						=> '#FF345F',
					'shiver_secondary_text_color'						=> '#4F6483',
					'shiver_accent_color'								=> '#FF345F',
					'shiver_buttons_background_color'					=> '#082E54',
					'shiver_buttons_text_color'						=> '#FFFFFF',
					'shiver_border_color'								=> '#DAE0E6',
					'shiver_light_background_color'					=> '#FFF6F8',
					'shiver_cover_template_overlay_text_color' 		=> '#FFFFFF',
					'shiver_cover_template_overlay_background_color'	=> '#FF345F',
				),
			),
			'inverness' 	=> array(
				'name'			=> _x( 'Inverness', 'Color scheme name', 'shiver' ),
				'colors'		=> array(
					'background_color'									=> 'FCEACD',
					'shiver_primary_text_color'						=> '#025B55',
					'shiver_headings_text_color'						=> '#025B55',
					'shiver_secondary_text_color'						=> '#7EA291',
					'shiver_accent_color'								=> '#025B55',
					'shiver_buttons_background_color'					=> '#025B55',
					'shiver_buttons_text_color'						=> '#FFFFFF',
					'shiver_border_color'								=> '#D6D5BB',
					'shiver_light_background_color'					=> '#FCD6CF',
					'shiver_cover_template_overlay_text_color' 		=> '#F7C87A',
					'shiver_cover_template_overlay_background_color'	=> '#025B55',
				),
			),
		) );

	}
endif;


/*	-----------------------------------------------------------------------------------------------
	AJAX GET COLOR SCHEME COLORS
	Returns the colors of the color scheme specified. Used by customize-controls.js to set the values
	of the color pickers when a new color scheme is selected.
--------------------------------------------------------------------------------------------------- */

if ( ! function_exists( 'shiver_ajax_get_color_scheme_colors' ) ) :
	function shiver_ajax_get_color_scheme_colors() {

		$color_scheme = wp_unslash( $_POST['color_scheme'] );

		$color_schemes = shiver_get_color_schemes();
		$color_scheme_colors = isset( $color_schemes[$color_scheme]['colors'] ) ? $color_schemes[$color_scheme]['colors'] : array();

		if ( $color_scheme_colors ) {
			echo json_encode( $color_scheme_colors );
		}

		wp_die();

	}
	add_action( 'wp_ajax_nopriv_shiver_ajax_get_color_scheme_colors', 'shiver_ajax_get_color_scheme_colors' );
	add_action( 'wp_ajax_shiver_ajax_get_color_scheme_colors', 'shiver_ajax_get_color_scheme_colors' );
endif;