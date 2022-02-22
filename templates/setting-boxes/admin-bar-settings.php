<?php
/**
 * Admin bar settings template.
 *
 * @package Better_Admin_Bar
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

$admin_bar_settings = swift_control_get_admin_bar_settings();

$roles_obj = new \WP_Roles();
$roles     = $roles_obj->role_names;
?>

<div class="heatbox misc-settings-box">
	<h2>
		<?php _e( 'Admin Bar Settings', 'better-admin-bar' ); ?>
	</h2>
	<div class="setting-fields">

		<div class="field">
			<label for="remove_by_roles" class="label select2-label">
				<p>
					<?php _e( 'Remove Admin Bar from the frontend for:', 'better-admin-bar' ); ?>
				</p>
				<select name="remove_by_roles[]" id="remove_by_roles" class="general-setting-field multiselect remove-admin-bar is-fullwidth" multiple>
					<option value="all" <?php echo esc_attr( in_array( 'all', $admin_bar_settings['remove_by_roles'], true ) ? 'selected' : '' ); ?>><?php _e( 'All', 'better-admin-bar' ); ?></option>

					<?php foreach ( $roles as $role_key => $role_name ) : ?>
						<?php
						$selected_attr = '';

						if ( in_array( $role_key, $admin_bar_settings['remove_by_roles'], true ) ) {
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
			<label for="overflow_layout" class="label checkbox-label">
				<?php _e( 'Enable layout overflow instead of preprending admin bar before the layout', 'better-admin-bar' ); ?>
				<input type="checkbox" name="overflow_layout" id="overflow_layout" value="1" class="general-setting-field" <?php checked( $admin_bar_settings['overflow_layout'], 1 ); ?>>
				<div class="indicator"></div>
			</label>
		</div>

		<div class="field">
			<label for="fix_horizontal_overflow" class="label checkbox-label">
				<?php _e( 'Fix horizontal overflow when there are too many items in admin bar', 'better-admin-bar' ); ?>
				<input type="checkbox" name="fix_horizontal_overflow" id="fix_horizontal_overflow" value="1" class="general-setting-field" <?php checked( $admin_bar_settings['fix_horizontal_overflow'], 1 ); ?>>
				<div class="indicator"></div>
			</label>
		</div>

		<hr>

		<div class="field is-horizontal">
			<div class="field-label">
				<label for="hide_below_screen_width" class="label">
					<?php _e( 'Hide admin bar when screen width is smaller than', 'better-admin-bar' ); ?>
				</label>
			</div>
			<div class="field-body">
				<div class="field">
					<div class="control">
						<input type="text" name="hide_below_screen_width" id="hide_below_screen_width" class="general-setting-field" value="<?php echo esc_attr( $admin_bar_settings['hide_below_screen_width'] ); ?>">
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
