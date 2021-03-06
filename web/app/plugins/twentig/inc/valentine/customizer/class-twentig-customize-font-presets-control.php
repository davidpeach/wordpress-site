<?php
/**
 * Customize Font Presets Control class.
 *
 * @package twentig
 */

if ( class_exists( 'WP_Customize_Control' ) ) {

	/**
	 * Font presets control.
	 */
	class Twentig_Customize_Font_Presets_Control extends WP_Customize_Control {
		/**
		 * Type.
		 *
		 * @var string
		 */
		public $type = 'twentig-font-presets';

		/**
		 * Render the content of the font presets control.
		 */
		public function render_content() {
			$presets   = twentig_get_font_presets();
			$imagepath = TWENTIG_ASSETS_URI . '/images/font-presets/';
			?>

			<h4 class="tw-customize-section-title"><?php esc_html_e( 'Presets', 'twentig' ); ?></h4>
			<p class="customize-control-description"><?php esc_html_e( 'Choosing a preset will override all the font settings.', 'twentig' ); ?></p>

			<div class="twentig-preset-panel">
				<h3 class="twentig-preset-panel-title">
					<button type="button" class="twentig-preset-panel-toggle" aria-expanded="false">
					<?php esc_html_e( 'View Presets', 'twentig' ); ?>
					<span class="toggle-indicator" aria-hidden="true"></span>
					</button>
				</h3>
				<div class="twentig-preset-list">
					<?php foreach ( $presets as $item ) { ?>
						<div class="twentig-preset-item" tabindex="0" role="button" aria-label="<?php echo esc_attr( $item['name'] ); ?>" data-value="<?php echo esc_attr( $item['name'] ); ?>">
							<img src="<?php echo esc_url( $imagepath . $item['image'] ); ?>" alt="<?php echo esc_html( $item['name'] ); ?>"/>
						</div>
					<?php } ?>
				</div>		
			</div>
			<?php
		}
	}
}
