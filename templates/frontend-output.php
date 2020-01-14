<?php
/**
 * Swift Control's frontend output template.
 *
 * @package Swift_Control
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

$active_widgets = swift_control_get_active_widgets();

// Get saved widget settings.
$saved_widget_settings = swift_control_get_saved_widget_settings();

$transition_delay = 0;
$widget_list      = '';
$extra_styles     = '';

foreach ( $active_widgets as $widget_key ) {
	// If the setting from database is empty, then take the data from default settings.
	$widget_settings = ! empty( $saved_widget_settings[ $widget_key ] ) ? $saved_widget_settings[ $widget_key ] : array();
	$parsed_settings = swift_control_parse_widget_settings( $widget_key, $widget_settings );

	$widget_class = '';
	$widget_class = swift_control_parse_widget_class( $widget_class, $widget_key );
	$icon_class   = $parsed_settings['icon_class'];
	$widget_name  = $parsed_settings['text'];
	$widget_name  = swift_control_parse_widget_name( $widget_name, $widget_key );
	$widget_url   = $parsed_settings['url'];
	$widget_url   = swift_control_parse_widget_url( $widget_url, $widget_key );
	$tab_target   = $parsed_settings['new_tab'];
	$redirect_url = $parsed_settings['redirect_url'];
	$target_attr  = $tab_target ? 'target="_blank"' : '';
	$target_attr  = 'logout' === $widget_key ? '' : $target_attr;

	ob_start();
	?>

	<li class="widget-item <?php echo esc_attr( $widget_class ); ?>" data-widget-key="<?php echo esc_attr( $widget_key ); ?>">
		<a class="widget-link" href="<?php echo esc_url( $widget_url ); ?>" <?php echo $target_attr; ?>>
			<i class="<?php echo esc_attr( $icon_class ); ?>"></i>
		</a>
		<span class="widget-title"><?php echo esc_html( $widget_name ); ?></span>
	</li>

	<?php
	$widget_list .= ob_get_clean();

	ob_start();
	?>

	[data-widget-key="<?php echo esc_attr( $widget_key ); ?>"] {
		transition-delay: 0ms;
	}

	.swift-control-widgets.is-expanded [data-widget-key="<?php echo esc_attr( $widget_key ); ?>"] {
		transition-delay: <?php echo esc_attr( $transition_delay ); ?>ms;
	}

	<?php
	$extra_styles     .= ob_get_clean();
	$transition_delay += 75;
}
?>

<style>
	<?php
	// We don't hook this extra styles to `class-setup.php` because we need the loop.
	echo $extra_styles;
	?>
</style>

<ul class="swift-control-widgets">

	<li class="widget-item widget-setting">
		<a class="widget-link" href="<?php echo esc_url( admin_url( 'options-general.php?page=swift-control' ) ); ?>" target="_blank">
			<i class="fas fa-cog"></i>
		</a>
	</li>

	<?php echo $widget_list; ?>

</ul>

<script>
	(function () {
		document.querySelector('.swift-control-widgets .widget-setting').addEventListener('click', function (e) {
			if (!this.parentNode.classList.contains('is-expanded')) {
				e.preventDefault();
				this.parentNode.classList.add('is-expanded');
			}
		});

		var disabledItems = document.querySelectorAll('.swift-control-widgets .widget-item.is-disabled a');

		if (!disabledItems.length) return;

		[].slice.call(disabledItems).forEach(function (el) {
			el.addEventListener('click', function (e) {
				e.preventDefault();
			});
		});
	})();
</script>
