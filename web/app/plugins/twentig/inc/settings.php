<?php
/**
 * Twentig's Dashboard Settings Page.
 *
 * @package twentig
 */

/**
 * Set up the settings.
 */
function twentig_initialize_settings() {
	add_settings_section(
		'twentig_options_section',
		'',
		'__return_false',
		'twentig-options'
	);

	register_setting(
		'twentig-options',
		'twentig-options',
		[
			'type'              => 'array',
			'sanitize_callback' => 'twentig_validate_options',
		]
	);

	add_settings_field(
		'twentig_core_block_patterns',
		__( 'Core Block Patterns', 'twentig' ),
		'twentig_core_block_patterns_render',
		'twentig-options',
		'twentig_options_section'
	);

	add_settings_field(
		'twentig_core_block_directory',
		__( 'Block Directory', 'twentig' ),
		'twentig_core_block_directory_render',
		'twentig-options',
		'twentig_options_section'
	);
}
add_action( 'admin_init', 'twentig_initialize_settings' );

/**
 * Validate options.
 *
 * @param array $options Plugin options.
 * @return array Options.
 */
function twentig_validate_options( $options ) {
	if ( $options ) {
		foreach ( $options as $key => $setting ) {
			if ( ! empty( $key ) ) {
				$options[ $key ] = absint( $options[ $key ] );
			}
		}
	}
	return $options;
}

/**
 * Displays a checkbox field for core block patterns.
 */
function twentig_core_block_patterns_render() {
	$value = twentig_is_option_enabled( 'twentig_core_block_patterns' ) ? 1 : 0;
	?>
	<label for="twentig_core_block_patterns">
		<input type="checkbox" name="twentig-options[twentig_core_block_patterns]" id="twentig_core_block_patterns" <?php checked( 1, $value ); ?> value="1">
		<?php esc_html_e( 'Enable default WordPress block patterns', 'twentig' ); ?>
	</label>
	<?php
}

/**
 * Display a checkbox field for core block directory.
 */
function twentig_core_block_directory_render() {
	$value = twentig_is_option_enabled( 'twentig_core_block_directory' ) ? 1 : 0;
	?>
	<label for="twentig_core_block_directory">
		<input type="checkbox" name="twentig-options[twentig_core_block_directory]" id="twentig_core_block_directory" <?php checked( 1, $value ); ?> value="1"> 
		<?php esc_html_e( 'Enable WordPress Block Directory', 'twentig' ); ?>
	</label>
	<?php
}

/**
 * Apply settings.
 */
function twentig_enable_block_directory() {
	if ( ! twentig_is_option_enabled( 'twentig_core_block_directory' ) ) {
		remove_action( 'enqueue_block_editor_assets', 'wp_enqueue_editor_block_directory_assets' );
	}
	if ( ! twentig_is_option_enabled( 'twentig_core_block_patterns' ) ) {
		remove_theme_support( 'core-block-patterns' );
	}
}
add_action( 'plugins_loaded', 'twentig_enable_block_directory' );

/**
 * Checks whether the Twentig option is enabled.
 *
 * @param string $name The name of the option.
 *
 * @return bool True when the option is enabled.
 */
function twentig_is_option_enabled( $name ) {
	$options = get_option(
		'twentig-options',
		array(
			'twentig_core_block_directory' => 1,
			'twentig_core_block_patterns'  => 1,
		)
	);
	return ! empty( $options[ $name ] );
}
