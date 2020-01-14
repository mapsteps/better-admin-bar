<?php
/**
 * Change widget item's setting.
 *
 * @package Swift_Control
 */

namespace SwiftControl\Ajax;

/**
 * Class to manage ajax request of changing widget item's setting.
 */
class Save_General_Settings {
	/**
	 * Available fields.
	 *
	 * @var array
	 */
	private $fields = array( 'widget_bg_color', 'widget_bg_color_hover', 'widget_icon_color', 'setting_button_bg_color', 'setting_button_icon_color', 'delete_on_uninstall', 'remove_font_awesome', 'remove_admin_bar' );

	/**
	 * Allowed empty fields.
	 *
	 * @var array
	 */
	private $empty_allowed = array( 'delete_on_uninstall', 'remove_font_awesome', 'remove_admin_bar' );

	/**
	 * Color setting fields.
	 *
	 * @var array
	 */
	private $color_setting_fields = array( 'widget_bg_color', 'widget_bg_color_hover', 'widget_icon_color', 'setting_button_bg_color', 'setting_button_icon_color' );

	/**
	 * Misc setting fields.
	 *
	 * @var array
	 */
	private $misc_setting_fields = array( 'delete_on_uninstall', 'remove_font_awesome', 'remove_admin_bar' );

	/**
	 * Sanitized data.
	 *
	 * @var array
	 */
	private $data = array();

	/**
	 * Setup the flow.
	 */
	public function ajax() {
		$this->sanitize();
		$this->validate();
		$this->save();
	}

	/**
	 * Sanitize the data.
	 */
	public function sanitize() {
		foreach ( $this->fields as $field ) {
			if ( isset( $_POST[ $field ] ) ) {
				$this->data[ $field ] = sanitize_text_field( $_POST[ $field ] );
				$this->data[ $field ] = 'new_tab' === $field ? absint( $this->data[ $field ] ) : $this->data[ $field ];
			}
		}
	}

	/**
	 * Validate the data.
	 */
	public function validate() {
		// Check if nonce is incorrect.
		if ( ! check_ajax_referer( 'save_general_settings', 'nonce', false ) ) {
			wp_send_json_error( __( 'Invalid token', 'swift-control' ) );
		}

		// Check if there is empty field.
		foreach ( $this->fields as $field ) {
			if ( ! in_array( $field, $this->empty_allowed ) ) {
				if ( ! isset( $this->data[ $field ] ) || empty( $this->data[ $field ] ) ) {
					$field_name = str_ireplace( '_', ' ', $field );
					$field_name = ucfirst( $field_name );

					wp_send_json_error( $field_name . ' ' . __( 'is empty', 'swift-control' ) );
				}
			}
		}
	}

	/**
	 * Save the data.
	 */
	public function save() {
		$color_settings = array();
		$misc_settings  = array();

		foreach ( $this->color_setting_fields as $field ) {
			$color_settings[ $field ] = $this->data[ $field ];
		}

		foreach ( $this->misc_setting_fields as $field ) {
			$misc_settings[ $field ] = $this->data[ $field ];
		}

		update_option( 'swift_control_color_settings', $color_settings );
		update_option( 'swift_control_misc_settings', $misc_settings );
		wp_send_json_success( __( 'The settings are saved' ), 'swift-control' );
	}
}
