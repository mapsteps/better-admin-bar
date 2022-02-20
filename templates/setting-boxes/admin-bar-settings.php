<?php
/**
 * Admin bar settings template.
 *
 * @package Better_Admin_Bar
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

$misc_settings = swift_control_get_misc_settings();

if ( isset( $misc_settings['remove_admin_bar'] ) ) {
	if ( is_numeric( $misc_settings['remove_admin_bar'] ) ) {
		$misc_settings['remove_admin_bar'] = absint( $misc_settings['remove_admin_bar'] );
		$misc_settings['remove_admin_bar'] = $misc_settings['remove_admin_bar'] ? array( 'all' ) : array();
	}
} else {
	$misc_settings['remove_admin_bar'] = array();
}

$remove_font_awesome = isset( $misc_settings['remove_font_awesome'] ) ? absint( $misc_settings['remove_font_awesome'] ) : 0;
$delete_on_uninstall = isset( $misc_settings['delete_on_uninstall'] ) ? absint( $misc_settings['delete_on_uninstall'] ) : 0;

$roles_obj = new \WP_Roles();
$roles     = $roles_obj->role_names;
?>

<div class="heatbox misc-settings-box">
	<h2>
		<?php _e( 'Admin Bar Settings', 'better-admin-bar' ); ?>
	</h2>
	<div class="setting-fields">

		<div class="field">
			<label for="remove_admin_bar" class="label select2-label">
				<p>
					<?php _e( 'Remove Admin Bar from the frontend for:', 'better-admin-bar' ); ?>
				</p>
				<select name="remove_admin_bar[]" id="remove_admin_bar" class="general-setting-field multiselect remove-admin-bar is-fullwidth" multiple>
					<option value="all" <?php echo esc_attr( in_array( 'all', $misc_settings['remove_admin_bar'], true ) ? 'selected' : '' ); ?>><?php _e( 'All', 'better-admin-bar' ); ?></option>

					<?php foreach ( $roles as $role_key => $role_name ) : ?>
						<?php
						$selected_attr = '';

						if ( in_array( $role_key, $misc_settings['remove_admin_bar'], true ) ) {
							$selected_attr = 'selected';
						}
						?>
						<option value="<?php echo esc_attr( $role_key ); ?>" <?php echo esc_attr( $selected_attr ); ?>><?php echo esc_attr( $role_name ); ?></option>
					<?php endforeach; ?>
				</select>
			</label>
		</div>

		<hr>

		<div class="field">
			<label for="remove_font_awesome" class="label checkbox-label">
				<?php _e( "Don't load FontAwesome 5 (your theme or another plugin may already include it)", 'better-admin-bar' ); ?>
				<input type="checkbox" name="remove_font_awesome" id="remove_font_awesome" value="1" class="general-setting-field" <?php checked( $remove_font_awesome, 1 ); ?>>
				<div class="indicator"></div>
			</label>
		</div>

		<div class="field">
			<label for="delete_on_uninstall" class="label checkbox-label">
				<?php _e( 'Remove data on uninstall', 'better-admin-bar' ); ?>
				<input type="checkbox" name="delete_on_uninstall" id="delete_on_uninstall" value="1" class="general-setting-field" <?php checked( $delete_on_uninstall, 1 ); ?>>
				<div class="indicator"></div>
			</label>
		</div>

	</div>
</div>
