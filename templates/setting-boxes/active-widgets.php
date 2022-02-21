<?php
/**
 * Active widget template.
 *
 * @package Better_Admin_Bar
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );
?>

<div class="heatbox active-widgets-box">
	<h2>
		<?php _e( 'Quick Access Panel', 'better-admin-bar' ); ?>
		<span class="saved-status">
			<?php _e( 'Updated', 'better-admin-bar' ); ?> 🚀
		</span>
	</h2>

	<ul id="active-items" class="widget-items active-items">
		<?php
		foreach ( $active_widgets as $widget_key ) {
			swift_control_settings_output_widget_item( $widget_key );
		}
		?>
	</ul>
</div>
