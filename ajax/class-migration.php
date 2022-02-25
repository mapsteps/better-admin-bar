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
	private $old_plugin_slug = '';

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

		// Check against old plugin slug existence.
		if ( ! isset( $_POST['old_plugin_slug'] ) || empty( $_POST['old_plugin_slug'] ) ) {
			wp_send_json_error( __( 'Old plugin slug is not specified', 'swift-control' ) );
		}

		$this->old_plugin_slug = sanitize_text_field( $_POST['old_plugin_slug'] );
	}

	/**
	 * Migrate to Better Admin Bar.
	 */
	public function migrate() {
		if ( file_exists( WP_PLUGIN_DIR . '/' . $this->old_plugin_slug ) ) {
			$deleted = delete_plugins( [ $this->old_plugin_slug ] );

			if ( $deleted && ! is_wp_error( $deleted ) ) {
				wp_send_json_success( __( 'Old Swift Control plugin has been uninstalled', 'swift-control' ) );
			} elseif ( is_wp_error( $deleted ) ) {
				wp_send_json_error( $deleted->get_error_message() );
			} elseif ( null === $deleted ) {
				wp_send_json_error( __( 'Filesystem credentials are required', 'swift-control' ) );
			} else {
				wp_send_json_error( __( 'Old plugin is not specified', 'swift-control' ) );
			}
		} else {
			wp_send_json_error( __( 'Old plugin is not found', 'swift-control' ) );
		}
	}
}
