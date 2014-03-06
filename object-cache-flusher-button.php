<?php
/*
 * Plugin Name: Object Cache Flusher Button
 * Plugin URI: http://github.com/georgestephanis/object-cache-flusher-button/
 * Description: This plugin adds a button to the adminbar that simply flushes the object cache.
 * Author: George Stephanis
 * Version: 1.0
 * Author URI: http://stephanis.info
 */

if ( is_admin() ) :

add_action( 'admin_bar_menu', 'nuke_spin_deactivate_reminder' );
function nuke_spin_deactivate_reminder( $wp_admin_bar ) {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$query_args = array(
		'action' => 'flush_object_cache',
	);
	$url = add_query_arg( $query_args, admin_url( 'index.php' ) );
	$wp_admin_bar->add_node( array(
		'id'     => 'object-cache-flusher-button',
		'title'  => __( 'Flush Cache' ),
		'href'   => wp_nonce_url( $url, 'object-cache-flush' ),
		'parent' => 'top-secondary',
	) );
}

add_action( 'admin_init', 'object_cache_flusher_button_do_it' );
function object_cache_flusher_button_do_it() {
	if ( empty( $_GET['action'] ) ) {
		return;
	}

	if ( 'flush_object_cache' !== $_GET['action'] ) {
		return;
	}

	if ( ! check_admin_referer( 'object-cache-flush' ) ) {
		return;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	wp_cache_flush();
}

add_action( 'admin_head', 'object_cache_flusher_button_style' );
function object_cache_flusher_button_style() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	echo '<style>#wpadminbar #wp-admin-bar-object-cache-flusher-button a{background:#a00}</style>';
}

endif;
