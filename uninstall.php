<?php
/**
 * Fired when the plugin is uninstalled
 *
 * @package Add_to_Feed
 */

// If uninstall not called from WordPress, then exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

delete_option( 'ald_atf_settings' );

