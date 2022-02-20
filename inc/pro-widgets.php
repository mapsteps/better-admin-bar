<?php
/**
 * Locked pro widget items.
 *
 * @package Better_Admin_Bar
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return array(
	'page_builders'     => array(
		'text'       => __( 'Page Builder Support', 'better-admin-bar' ),
		'url'        => 'https://wpswiftcontrol.com/pro/',
		'new_tab'    => true,
		'icon_class' => 'fas fa-unlock-alt',
	),
	'custom_links'      => array(
		'text'       => __( 'Custom Controls', 'better-admin-bar' ),
		'url'        => 'https://wpswiftcontrol.com/pro/',
		'new_tab'    => true,
		'icon_class' => 'fas fa-unlock-alt',
	),
	'custom_post_types' => array(
		'text'       => __( 'Custom Post Type Support', 'better-admin-bar' ),
		'url'        => 'https://wpswiftcontrol.com/pro/',
		'new_tab'    => true,
		'icon_class' => 'fas fa-unlock-alt',
	),
	'woocommerce'       => array(
		'text'       => __( 'WooCommerce Support', 'better-admin-bar' ),
		'url'        => 'https://wpswiftcontrol.com/pro/',
		'new_tab'    => true,
		'icon_class' => 'fas fa-unlock-alt',
	),
	'logout'            => array(
		'text'         => __( 'Logout Widget', 'better-admin-bar' ),
		'url'          => 'https://wpswiftcontrol.com/pro/',
		'icon_class'   => 'fas fa-unlock-alt',
		'redirect_url' => '',
	),

);
