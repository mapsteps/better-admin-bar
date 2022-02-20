<?php
/**
 * Setup Better Admin Bar.
 *
 * @package Better_Admin_Bar
 */

namespace Bab;

/**
 * Setup Better Admin Bar.
 */
class Setup {
	/**
	 * Setup action & filter hooks.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'setup' ) );
	}

	/**
	 * Setup action & filters.
	 */
	public function setup() {
		// Stop if Better Admin Bar Pro is active.
		if ( defined( 'SWIFT_CONTROL_PRO_PLUGIN_URL' ) ) {
			return;
		}

		add_action( 'init', array( $this, 'setup_text_domain' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ), 999 );
		add_action( 'admin_menu', array( $this, 'add_submenu_page' ) );
		add_action( 'wp', array( $this, 'check_page' ) );
		add_action( 'wp', array( $this, 'remove_admin_bar' ) );

		// Process export-import.
		add_action( 'admin_init', array( $this, 'process_export' ) );
		add_action( 'admin_init', array( $this, 'process_import' ) );

		add_action( 'wp_ajax_swift_control_change_widgets_order', array( new Ajax\Change_Widgets_Order(), 'ajax' ) );
		add_action( 'wp_ajax_swift_control_change_widget_settings', array( new Ajax\Change_Widget_Settings(), 'ajax' ) );
		add_action( 'wp_ajax_swift_control_save_general_settings', array( new Ajax\Save_General_Settings(), 'ajax' ) );
		add_action( 'wp_ajax_swift_control_save_position', array( new Ajax\Save_Position(), 'ajax' ) );

		add_filter( 'plugin_action_links', array( $this, 'add_settings_link' ), 10, 4 );

		add_action( 'admin_notices', array( $this, 'discount_notice' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'discount_notice_script' ) );
		add_action( 'wp_ajax_sc_discount_notice_dismissal', array( $this, 'dismiss_discount_notice' ) );

	}

	/**
	 * Setup textdomain.
	 */
	public function setup_text_domain() {
		load_plugin_textdomain( 'better-admin-bar', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
	}

	/**
	 * Admin discount notice.
	 */
	public function discount_notice() {

		// Stop here if notice has been dismissed.
		if ( ! empty( get_option( 'swift_control_discontinue_message', 0 ) ) ) {
			return;
		}

		// Stop here if current user can't manage options.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		?>

		<div class="notice notice-info swift-control-discontinue-notice is-dismissible">
			<h2>Get 50% OFF Better Admin Bar PRO!</h2>

			<p>
				Thank you for checking out Better Admin Bar! 🎉<br>
				As a valued user of our plugin we would like to offer you a <strong style="color: green;">50% discount</strong> on <strong>Better Admin Bar PRO</strong> (yearly plan) for the first year.
			</p>

			<p><strong>Alternatively, check out our <a target="_blank" href="https://wp-pagebuilderframework.com/pricing/">Agency Bundle</a> which includes Better Admin Bar PRO for free.</strong></p>

			<p>Grab it while it lasts.</p>

			<p><a target="_blank" href="https://wpswiftcontrol.com/?edd_action=add_to_cart&download_id=241&edd_options%5Bprice_id%5D=1&discount=SCPRO50" style="font-weight: 700;" class="button button-primary">Get Better Admin Bar PRO and save 50%</a> <strong>Coupon limited to 100 users!</strong></p>
		</div>

		<?php

	}

	/**
	 * Script that handles discount notice dismissal.
	 */
	public function discount_notice_script() {

		// Stop here if notice has been dismissed.
		if ( ! empty( get_option( 'swift_control_discontinue_message', 0 ) ) ) {
			return;
		}

		// Stop here if current user can't manage options.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		wp_enqueue_script( 'swift-control-discount', BETTER_ADMIN_BAR_PLUGIN_URL . '/assets/js/discount-notice.js', array( 'jquery' ), BETTER_ADMIN_BAR_PLUGIN_VERSION, true );

		wp_localize_script(
			'swift-control-discount',
			'swiftControlDismissal',
			array(
				'nonces' => array(
					'dismissalNonce' => wp_create_nonce( 'Better_Admin_Bar_Dismiss_Discount_Notice' ),
				),
			)
		);

	}

	/**
	 * Dismiss discount notice.
	 */
	public function dismiss_discount_notice() {
		$nonce   = isset( $_POST['nonce'] ) ? $_POST['nonce'] : 0;
		$dismiss = isset( $_POST['dismiss'] ) ? absint( $_POST['dismiss'] ) : 0;

		if ( empty( $dismiss ) ) {
			wp_send_json_error( __( 'Invalid Request', 'better-admin-bar' ) );
		}

		if ( ! wp_verify_nonce( $nonce, 'Better_Admin_Bar_Dismiss_Discount_Notice' ) ) {
			wp_send_json_error( __( 'Invalid Token', 'better-admin-bar' ) );
		}

		update_option( 'swift_control_discontinue_message', 1 );
		wp_send_json_success( __( 'Discount notice has been dismissed', 'better-admin-bar' ) );
	}

	/**
	 * Enqueue admin styles & scripts.
	 */
	public function admin_scripts() {
		$current_screen = get_current_screen();

		if ( 'settings_page_swift-control' !== $current_screen->id ) {
			return;
		}

		// Font Awesome 5.
		$this->deregister_font_awesome();
		wp_enqueue_style( 'font-awesome', BETTER_ADMIN_BAR_PLUGIN_URL . '/assets/vendor/fontawesome-free/css/all.min.css', array(), '5.14.0' );

		// Icon picker.
		wp_enqueue_style( 'icon-picker', BETTER_ADMIN_BAR_PLUGIN_URL . '/assets/css/icon-picker.css', array(), BETTER_ADMIN_BAR_PLUGIN_VERSION );

		// Select2.
		wp_enqueue_style( 'select2', BETTER_ADMIN_BAR_PLUGIN_URL . '/assets/css/select2.min.css', array(), BETTER_ADMIN_BAR_PLUGIN_VERSION );

		// Settings page styling.
		wp_enqueue_style( 'settings-page', BETTER_ADMIN_BAR_PLUGIN_URL . '/assets/css/settings-page.css', array(), BETTER_ADMIN_BAR_PLUGIN_VERSION );
		wp_enqueue_style( 'setting-fields', BETTER_ADMIN_BAR_PLUGIN_URL . '/assets/css/setting-fields.css', array(), BETTER_ADMIN_BAR_PLUGIN_VERSION );

		// Better Admin Bar admin styling.
		wp_enqueue_style( 'swift-control-admin', BETTER_ADMIN_BAR_PLUGIN_URL . '/assets/css/swift-control-admin.css', array(), BETTER_ADMIN_BAR_PLUGIN_VERSION );

		// Color picker dependency.
		wp_enqueue_style( 'wp-color-picker' );

		// jQuery UI dependencies.
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-widget' );
		wp_enqueue_script( 'jquery-ui-mouse' );
		wp_enqueue_script( 'jquery-ui-sortable' );

		// Select2.
		wp_enqueue_script( 'select2', BETTER_ADMIN_BAR_PLUGIN_URL . '/assets/js/select2.min.js', array( 'jquery' ), BETTER_ADMIN_BAR_PLUGIN_VERSION, true );

		// Icon picker.
		wp_enqueue_script( 'icon-picker', BETTER_ADMIN_BAR_PLUGIN_URL . '/assets/js/icon-picker.js', array( 'jquery' ), BETTER_ADMIN_BAR_PLUGIN_VERSION, true );

		$icons = file_get_contents( BETTER_ADMIN_BAR_PLUGIN_DIR . '/assets/json/fontawesome5.json' );
		$icons = json_decode( $icons, true );
		$icons = $icons ? $icons : array();

		wp_localize_script(
			'icon-picker',
			'iconPickerIcons',
			$icons
		);

		// Color picker alpha.
		wp_enqueue_script( 'wp-color-picker-alpha', BETTER_ADMIN_BAR_PLUGIN_URL . '/assets/js/wp-color-picker-alpha.js', array( 'wp-color-picker', 'wp-i18n' ), '2.1.3', true );

		wp_enqueue_script( 'swift-control-widget-settings', BETTER_ADMIN_BAR_PLUGIN_URL . '/assets/js/swift-control-widget-settings.js', array( 'jquery-ui-sortable', 'icon-picker' ), BETTER_ADMIN_BAR_PLUGIN_VERSION, true );
		wp_enqueue_script( 'swift-control-general-settings', BETTER_ADMIN_BAR_PLUGIN_URL . '/assets/js/swift-control-general-settings.js', array( 'select2', 'wp-color-picker-alpha' ), BETTER_ADMIN_BAR_PLUGIN_VERSION, true );

		wp_localize_script(
			'swift-control-widget-settings',
			'Bab',
			array(
				'nonces' => array(
					'changeWidgetsOrder'   => wp_create_nonce( 'change_widgets_order' ),
					'changeWidgetSettings' => wp_create_nonce( 'change_widget_settings' ),
					'saveGeneralSettings'  => wp_create_nonce( 'save_general_settings' ),
				),
				'labels' => array(
					'edit' => __( 'Edit', 'better-admin-bar' ),
					'save' => __( 'Save', 'better-admin-bar' ),
				),
			)
		);
	}

	/**
	 * Add settings link to plugin list page.
	 *
	 * @param array  $actions     An array of plugin action links.
	 * @param string $plugin_file Path to the plugin file relative to the plugins directory.
	 * @param array  $plugin_data An array of plugin data. See `get_plugin_data()`.
	 * @param string $context     The plugin context. By default this can include 'all', 'active', 'inactive',
	 *                            'recently_activated', 'upgrade', 'mustuse', 'dropins', and 'search'.
	 *
	 * @return array The modified plugin action links.
	 */
	public function add_settings_link( $actions, $plugin_file, $plugin_data, $context ) {
		if ( BETTER_ADMIN_BAR_PLUGIN_BASENAME === $plugin_file ) {
			$settings_link = '<a href="' . esc_url( admin_url( 'options-general.php?page=better-admin-bar' ) ) . '">' . __( 'Settings', 'better-admin-bar' ) . '</a>';

			array_unshift( $actions, $settings_link );
		}

		return $actions;
	}

	/**
	 * Add submenu under "Settings" menu item.
	 */
	public function add_submenu_page() {
		add_options_page( __( 'Better Admin Bar', 'better-admin-bar' ), __( 'Better Admin Bar', 'better-admin-bar' ), 'delete_others_posts', 'better-admin-bar', array( $this, 'page_output' ) );
	}

	/**
	 * Better Admin Bar page output.
	 */
	public function page_output() {
		require __DIR__ . '/templates/admin-page.php';
	}

	/**
	 * Whether or not to show the widgets in frontend.
	 * Only display the widgets when there's any active one.
	 */
	public function check_page() {
		// Only show to logged-in admin users.
		if ( ! is_user_logged_in() || ! current_user_can( 'delete_others_posts' ) ) {
			return;
		}

		// Stop if this is a customizer preview.
		if ( is_customize_preview() ) {
			return;
		}

		// Stop if current page is in edit mode inside page builder.
		if ( swift_control_is_inside_page_builder() ) {
			return;
		}

		// Stop if swift control doesn't have active widgets.
		if ( ! swift_control_has_active_widgets() ) {
			return;
		}

		if ( ! apply_filters( 'swift_control_frontend_display', true ) ) {
			return;
		}

		add_filter( 'body_class', array( $this, 'add_body_class' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'frontend_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'inline_styles' ) );
		add_action( 'wp_footer', array( $this, 'frontend_output' ), 5 );
	}

	/**
	 * Add a class to body_class().
	 *
	 * @param array $classes The body classes.
	 * @return array $classes The modified body classes.
	 */
	public function add_body_class( $classes ) {
		$classes[] = 'has-swift-control';

		return $classes;
	}

	/**
	 * Remove admin bar on frontend for certain roles.
	 */
	public function remove_admin_bar() {
		$misc_settings = swift_control_get_misc_settings();

		if ( ! isset( $misc_settings['remove_admin_bar'] ) || empty( $misc_settings['remove_admin_bar'] ) ) {
			return;
		}

		$selected_roles = $misc_settings['remove_admin_bar'];

		// Backward compatibility: old value's format is int (0 / 1).
		if ( is_numeric( $selected_roles ) ) {
			add_filter( 'show_admin_bar', '__return_false' );
			return;
		}

		if ( in_array( 'all', $misc_settings['remove_admin_bar'], true ) ) {
			add_filter( 'show_admin_bar', '__return_false' );
			return;
		} else {
			$current_user = wp_get_current_user();

			foreach ( $current_user->roles as $role ) {
				if ( in_array( $role, $selected_roles, true ) ) {
					add_filter( 'show_admin_bar', '__return_false' );
					break;
				}
			}
		}
	}

	/**
	 * Enqueue frontend assets.
	 */
	public function frontend_scripts() {
		$misc_settings       = swift_control_get_misc_settings();
		$remove_font_awesome = isset( $misc_settings['remove_font_awesome'] ) ? absint( $misc_settings['remove_font_awesome'] ) : 0;

		/**
		 * If "Don't enqueue Font Awesome" setting is un-checked (disabled),
		 * then deregister any existing Font Awesome css, and enqueue our version.
		 */
		if ( ! $remove_font_awesome ) {
			$this->deregister_font_awesome();
			wp_enqueue_style( 'font-awesome', BETTER_ADMIN_BAR_PLUGIN_URL . '/assets/vendor/fontawesome-free/css/all.min.css', array(), '5.14.0' );
		}

		// Better Admin Bar frontend styling.
		wp_enqueue_style( 'better-admin-bar', BETTER_ADMIN_BAR_PLUGIN_URL . '/assets/css/swift-control.css', array(), BETTER_ADMIN_BAR_PLUGIN_VERSION );

		// Better Admin Bar frontend script.
		wp_enqueue_script( 'interact', BETTER_ADMIN_BAR_PLUGIN_URL . '/assets/js/interact.min.js', array( 'jquery' ), BETTER_ADMIN_BAR_PLUGIN_VERSION, true );
		wp_enqueue_script( 'better-admin-bar', BETTER_ADMIN_BAR_PLUGIN_URL . '/assets/js/swift-control.js', array( 'jquery', 'interact' ), BETTER_ADMIN_BAR_PLUGIN_VERSION, true );
	}

	/**
	 * Dequeue & deregister existing Font Awesome
	 * to prevent conflict with our enqueue.
	 */
	public function deregister_font_awesome() {

		wp_dequeue_style( 'font-awesome' );
		wp_dequeue_style( 'fontawesome' );

		wp_deregister_style( 'font-awesome' );
		wp_deregister_style( 'fontawesome' );

	}

	/**
	 * Output the widgets to frontend.
	 */
	public function frontend_output() {
		require __DIR__ . '/templates/frontend-output.php';
	}

	/**
	 * Output the inline styles to frontend.
	 */
	public function inline_styles() {

		$color_settings = swift_control_get_color_settings();
		$default_colors = swift_control_get_default_color_settings();
		$css            = '';

		if ( $color_settings['setting_button_bg_color'] !== $default_colors['setting_button_bg_color'] ) {
			$css .= '.swift-control-widgets .swift-control-widget-setting .swift-control-widget-link, .swift-control-widgets .swift-control-widget-setting .swift-control-widget-link:hover {';
			$css .= 'background-color: ' . esc_attr( $color_settings['setting_button_bg_color'] ) . ';';
			$css .= '}';

			$css .= '.swift-control-widgets .swift-control-widget-setting::after {';
			$css .= 'color: ' . esc_attr( $color_settings['setting_button_bg_color'] ) . ';';
			$css .= '}';
		}

		if ( $color_settings['setting_button_icon_color'] !== $default_colors['setting_button_icon_color'] ) {
			$css .= '.swift-control-widgets .swift-control-widget-setting a {';
			$css .= 'color: ' . esc_attr( $color_settings['setting_button_icon_color'] ) . ';';
			$css .= '}';
		}

		if ( $color_settings['widget_bg_color_hover'] !== $default_colors['widget_bg_color_hover'] ) {
			$css .= '.swift-control-widgets .swift-control-widget-link:hover {';
			$css .= 'background-color: ' . esc_attr( $color_settings['widget_bg_color_hover'] ) . ';';
			$css .= '}';
		}

		if ( $color_settings['widget_bg_color'] !== $default_colors['widget_bg_color'] ) {
			$css .= '.swift-control-widgets .swift-control-widget-link {';
			$css .= 'background-color: ' . esc_attr( $color_settings['widget_bg_color'] ) . ';';
			$css .= '}';

			$css .= '.swift-control-widgets .is-disabled .swift-control-widget-link {';
			$css .= 'background-color: ' . esc_attr( $color_settings['widget_bg_color'] ) . ';';
			$css .= '}';
		}

		if ( $color_settings['widget_icon_color'] !== $default_colors['widget_icon_color'] ) {
			$css .= '.swift-control-widgets .swift-control-widget-link {';
			$css .= 'color: ' . esc_attr( $color_settings['widget_icon_color'] ) . ';';
			$css .= '}';
		}

		wp_add_inline_style( 'better-admin-bar', $css );

	}

	/**
	 * Process widget export.
	 */
	public function process_export() {

		if ( ! isset( $_POST['swift_control_action'] ) || 'export' !== $_POST['swift_control_action'] ) {
			return;
		}

		if ( ! isset( $_POST['swift_control_export_nonce'] ) || empty( $_POST['swift_control_export_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['swift_control_export_nonce'], 'swift_control_export_widgets' ) ) {
			return;
		}

		$exporter = new Helpers\Export();

		$exporter->export();

	}

	/**
	 * Process widget import.
	 */
	public function process_import() {

		if ( ! isset( $_FILES['swift_control_import_file'] ) || empty( $_FILES['swift_control_import_file'] ) ) {
			return;
		}

		if ( ! isset( $_POST['swift_control_action'] ) || 'import' !== $_POST['swift_control_action'] ) {
			return;
		}

		if ( ! isset( $_POST['swift_control_import_nonce'] ) || empty( $_POST['swift_control_import_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['swift_control_import_nonce'], 'swift_control_import_widgets' ) ) {
			return;
		}

		$importer = new Helpers\Import();

		$importer->import();

	}

}
