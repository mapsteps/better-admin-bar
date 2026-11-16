<?php
/**
 * Black Friday notice: a dismissible banner in the admin during the sale.
 *
 * @package Better_Admin_Bar
 */

namespace SwiftControl;

defined( 'ABSPATH' ) || die( "Can't access directly" );

/**
 * Black Friday notice (same look as in Ultimate Dashboard).
 */
class Bfcm_Notice {

	/**
	 * First day of the sale (site time).
	 * Set by hand each year on purpose, so an old release never shows a sale that isn't running.
	 *
	 * @var string
	 */
	const START = '2026-11-23';

	/**
	 * Last day of the sale (site time).
	 *
	 * @var string
	 */
	const END = '2026-11-30';

	/**
	 * Holds the start date of the sale that was dismissed, so the next sale shows again.
	 *
	 * @var string
	 */
	const OPTION = 'swift_control_bfcm_dismissed';

	/**
	 * Register the hooks.
	 */
	public function __construct() {

		add_action( 'admin_notices', array( $this, 'notice' ) );
		add_action( 'wp_ajax_swift_control_dismiss_bfcm_notice', array( $this, 'dismiss' ) );

	}

	/**
	 * Whether the notice should show to the current user.
	 *
	 * @return bool
	 */
	private function is_shown() {

		$today = current_time( 'Y-m-d' );

		return $today >= self::START
			&& $today <= self::END
			&& is_main_site()
			&& current_user_can( 'manage_options' )
			&& self::START !== get_option( self::OPTION );

	}

	/**
	 * Print the notice.
	 */
	public function notice() {

		if ( ! $this->is_shown() ) {
			return;
		}

		$url = 'https://betteradminbar.com/pricing/?utm_source=repository&utm_medium=bfcm_banner&utm_campaign=bab';
		?>

		<div class="notice notice-info is-dismissible swift-control-bfcm-notice" data-nonce="<?php echo esc_attr( wp_create_nonce( 'swift_control_dismiss_bfcm_notice' ) ); ?>">
			<img class="swift-control-bfcm-notice-logo" src="<?php echo esc_url( SWIFT_CONTROL_PLUGIN_URL . '/assets/img/logo.png' ); ?>" alt="">
			<div>
				<h2><?php esc_html_e( 'Black Friday: up to 20% off Better Admin Bar PRO', 'better-admin-bar' ); ?></h2>
				<p><?php esc_html_e( 'All prices are reduced, no coupon code needed. The deal ends soon.', 'better-admin-bar' ); ?></p>
				<p>
					<a href="<?php echo esc_url( $url ); ?>" target="_blank" class="button button-primary">
						<?php esc_html_e( 'Get the Deal', 'better-admin-bar' ); ?>
						<span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'better-admin-bar' ); ?></span>
					</a>
				</p>
			</div>
		</div>

		<style>
			.swift-control-bfcm-notice { display: flex; align-items: flex-start; gap: 16px; padding: 16px 40px 16px 16px; }
			.swift-control-bfcm-notice-logo { flex-shrink: 0; width: 48px; height: 48px; }
			.swift-control-bfcm-notice h2 { margin: 0 0 4px; font-size: 15px; }
			.swift-control-bfcm-notice p { margin: 0 0 10px; padding: 0; }
			.swift-control-bfcm-notice p:last-child { margin-bottom: 0; }
		</style>

		<script>
			document.addEventListener( 'click', function ( event ) {
				var notice = event.target.closest( '.swift-control-bfcm-notice' );

				if ( ! notice || ! event.target.closest( '.notice-dismiss' ) ) {
					return;
				}

				var data = new FormData();

				data.append( 'action', 'swift_control_dismiss_bfcm_notice' );
				data.append( 'nonce', notice.dataset.nonce );
				fetch( window.ajaxurl, { method: 'POST', body: data, credentials: 'same-origin' } );
			} );
		</script>

		<?php

	}

	/**
	 * Remember that the notice was dismissed (Ajax).
	 */
	public function dismiss() {

		check_ajax_referer( 'swift_control_dismiss_bfcm_notice', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( null, 403 );
		}

		update_option( self::OPTION, self::START, false );
		wp_send_json_success();

	}

}
