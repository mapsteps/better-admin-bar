<?php
/**
 * Pro widgets template.
 *
 * @package Better_Admin_Bar
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );
?>

<div class="heatbox sidebar-heatbox is-clean pro-widgets-box">
	<h2><?php _e( 'There is more!', 'better-admin-bar' ); ?></h2>

	<ul id="pro-items" class="widget-items pro-items">

		<?php
		// Build locked widget items output.
		foreach ( $locked_widgets as $widget_key ) :
			$widget_settings = $locked_settings[ $widget_key ];
			?>
			<li class="widget-item is-locked" data-widget-key="<?php echo esc_attr( $widget_key ); ?>">
				<div class="heatbox-cols widget-default-area">
					<div class="widget-item-col drag-wrapper">
						<span class="locked-handle fas fa-unlock-alt"></span>
					</div>
					<div class="widget-item-col text-wrapper">
						<?php echo esc_html( $widget_settings['text'] ); ?>
					</div>
					<div class="widget-item-col actions-wrapper">
						<a href="<?php echo esc_url( $widget_settings['url'] ); ?>" target="_blank" class="pro-unlock"><?php _e( 'Unlock', 'better-admin-bar' ); ?></a>
					</div>
				</div>
			</li>
		<?php endforeach; ?>

	</ul>
</div>
