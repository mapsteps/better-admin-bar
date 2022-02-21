<?php
/**
 * Admin bar settings template.
 *
 * @package Better_Admin_Bar
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

$admin_bar_settings = swift_control_get_admin_bar_settings();

if ( isset( $admin_bar_settings['remove_by_roles'] ) ) {
	if ( is_numeric( $admin_bar_settings['remove_by_roles'] ) ) {
		$admin_bar_settings['remove_by_roles'] = absint( $admin_bar_settings['remove_by_roles'] );
		$admin_bar_settings['remove_by_roles'] = $admin_bar_settings['remove_by_roles'] ? array( 'all' ) : array();
	}
} else {
	$admin_bar_settings['remove_by_roles'] = array();
}

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

	</div>
</div>
