<?php
/**
 * Display settings template.
 *
 * @package Better_Admin_Bar
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

$display_settings = swift_control_get_display_settings();
$remove_indicator = isset( $display_settings['remove_indicator'] ) ? absint( $display_settings['remove_indicator'] ) : 0;
$expanded         = isset( $display_settings['expanded'] ) ? absint( $display_settings['expanded'] ) : 0;
?>

<div class="heatbox display-settings-box">
	<h2>
		<?php _e( 'Display Settings', 'better-admin-bar' ); ?>
	</h2>
	<div class="setting-fields">

		<div class="field">
			<label for="remove_indicator" class="label checkbox-label">
				<?php _e( 'Remove indicator arrow', 'better-admin-bar' ); ?>
				<input type="checkbox" name="remove_indicator" id="remove_indicator" value="1" class="general-setting-field" <?php checked( $remove_indicator, 1 ); ?>>
				<div class="indicator"></div>
			</label>
		</div>

		<div class="field">
			<label for="expanded" class="label checkbox-label">
				<?php _e( 'Expand control panel by default', 'better-admin-bar' ); ?>
				<input type="checkbox" name="expanded" id="expanded" value="1" class="general-setting-field" <?php checked( $expanded, 1 ); ?>>
				<div class="indicator"></div>
			</label>
		</div>

	</div>
</div>
