<?php
/**
 * Display settings box of the quick access panel.
 *
 * @package Better_Admin_Bar
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

$swift_control_display = swift_control_get_display_settings();
?>

<div class="heatbox display-settings-box">
	<h2><?php esc_html_e( 'Display Settings', 'better-admin-bar' ); ?></h2>
	<table class="form-table" role="presentation">
		<tr>
			<th scope="row"><?php esc_html_e( 'Quick access panel', 'better-admin-bar' ); ?></th>
			<td>
				<div class="setting-fields">
					<div class="setting-field">
						<label class="label checkbox-label">
							<?php esc_html_e( 'Disable the quick access panel', 'better-admin-bar' ); ?>
							<input type="checkbox" name="disable_swift_control" id="disable_swift_control" value="1" class="general-setting-field" <?php checked( $swift_control_display['disable_swift_control'], 1 ); ?>>
							<div class="indicator"></div>
						</label>
					</div>
					<div class="setting-field" data-show-if-field-unchecked="disable_swift_control">
						<label class="label checkbox-label">
							<?php esc_html_e( 'Remove the click indicator (arrow)', 'better-admin-bar' ); ?>
							<input type="checkbox" name="remove_indicator" id="remove_indicator" value="1" class="general-setting-field" <?php checked( $swift_control_display['remove_indicator'], 1 ); ?>>
							<div class="indicator"></div>
						</label>
					</div>
					<div class="setting-field" data-show-if-field-unchecked="disable_swift_control">
						<label class="label checkbox-label">
							<?php esc_html_e( 'Expand the panel by default', 'better-admin-bar' ); ?>
							<input type="checkbox" name="expanded" id="expanded" value="1" class="general-setting-field" <?php checked( $swift_control_display['expanded'], 1 ); ?>>
							<div class="indicator"></div>
						</label>
					</div>
				</div>
			</td>
		</tr>
	</table>
</div>
