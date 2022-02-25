<?php
/**
 * Change widget order.
 *
 * @package Swift_Control
 */

namespace SwiftControl\Ajax;

/**
 * Class to manage ajax request of changing widget order.
 */
class Migration {
	/**
	 * Setup the flow.
	 */
	public function ajax() {
		$this->validate();
		$this->migrate();
	}

	/**
	 * Validate the data.
	 */
	public function validate() {
		// Check if nonce is incorrect.
		if ( ! check_ajax_referer( 'swift_control_migration', 'nonce', false ) ) {
			wp_send_json_error( __( 'Invalid token', 'swift-control' ) );
		}
	}

	/**
	 * Migrate to Better Admin Bar.
	 */
	public function migrate() {
		uninstall_plugin( 'swift-control/swift-control.php' );
		wp_send_json_success( __( 'Old Swift Control plugin has been uninstalled', 'swift-control' ) );
	}
}
