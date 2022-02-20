<?php
/**
 * Better Admin Bar export widgets template.
 *
 * @package Better_Admin_Bar
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );
?>

<form method="post" action="<?php menu_page_url( 'better-admin-bar', true ); ?>">
	<input type="hidden" name="swift_control_action" value="export">
	<?php wp_nonce_field( 'swift_control_export_widgets', 'swift_control_export_nonce' ); ?>

	<div class="neatbox is-smooth has-bigger-heading has-medium-gap">
		<h2><?php _e( 'Export Widgets', 'better-admin-bar' ); ?></h2>

		<div class="neatbox-content has-bottom-gapless">

			<p>
				<?php _e( 'Use the export button to export to a .json file which you can then import to another Better Admin Bar installation.', 'better-admin-bar' ); ?>
			</p>

			<div class="setting-fields is-gapless">
				<div class="field">
					<label for="swift_control_export_settings" class="label checkbox-label">
						<?php _e( 'Include Settings', 'better-admin-bar' ); ?>
						<input type="checkbox" name="swift_control_export_settings" id="swift_control_export_settings" value="1" class="export-widgets-field">
						<div class="indicator"></div>
					</label>
				</div>
			</div>

		</div><!-- .neatbox-content -->

		<?php submit_button( __( 'Export File', 'better-admin-bar' ), 'primary', 'submit_export' ); ?>
	</div><!-- .neatbox -->
</form>
