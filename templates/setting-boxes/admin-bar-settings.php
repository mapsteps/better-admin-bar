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

	</div>
</div>
