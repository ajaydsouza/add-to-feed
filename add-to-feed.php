<?php
/**
 * Add to Feed lets you add a copyright notice and custom text or HTML to your WordPress feed.
 *
 * @package Add_to_Feed
 *
 * @wordpress-plugin
 * Plugin Name: Add to Feed
 * Version:     1.2.0.1
 * Plugin URI:  http://ajaydsouza.com/wordpress/plugins/add-to-feed/
 * Description: Add to Feed is a feed enhancement plugin that allows you to easily add a copyright notice and custom text/HTML to your WordPress blog feed. The custom text can be entered before and/or after the content of your blog post.
 * Author:      Ajay D'Souza
 * Author URI:  http://ajaydsouza.com/
 * Text Domain:	atf
 * License:		GPL-2.0+
 * License URI:	http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:	/languages
*/

// If this file is called directly, then abort execution.
if ( ! defined( 'WPINC' ) ) {
	die( "Aren't you supposed to come here via WP-Admin?" );
}

/**
 * Holds the filesystem directory path.
 */
define('ALD_ATF_DIR', dirname(__FILE__));

/**
 * Localisation name.
 */
define('ATF_LOCAL_NAME', 'atf');


// Set the global variables for Better Search path and URL
$atf_path = plugin_dir_path( __FILE__ );
$atf_url = plugins_url() . '/' . plugin_basename( dirname( __FILE__ ) );


/**
 * Declare $atf_settings global so that it can be accessed in every function
 */
global $atf_settings;
$atf_settings = atf_read_options();


/**
 * Function to load translation files.
 */
function atf_lang_init() {
	load_plugin_textdomain( ATF_LOCAL_NAME, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
add_action('init', 'atf_lang_init');


/**
 * Adds the custom content to the feed. Filters the_excerpt_rss and the_content_feed.
 *
 * @param string $content Post content
 * @return string Filtered post content
 */
function ald_atf( $content ) {

	global $atf_settings;

	$str_before ='';
	$str_after = '<hr style="border-top:black solid 1px" />';

    if ( is_feed() && $atf_settings['enable_plugin'] ) {
		if ( $atf_settings['addhtmlbefore'] ) {
			$str_before .= stripslashes( $atf_settings['htmlbefore'] );
			$str_before .= '<br />';
		}

		if ( $atf_settings['addhtmlafter'] ) {
			$str_after .= stripslashes( $atf_settings['htmlafter'] );
			$str_after .= '<br />';
		}

		if ( $atf_settings['addtitle'] ) {
			$str_after .= '<a href="' . get_permalink() . '">' . the_title( '', '', false ) . '</a> was first posted on ' . get_the_time( 'F j, Y' ) . ' at ' . get_the_time( 'g:i a' ) . '.';
			$str_after .= '<br />';
		}

		if ( $atf_settings['addcopyright'] ) {
			$str_after .= stripslashes( $atf_settings['copyrightnotice'] );
			$str_after .= '<br />';
		}

		if ( $atf_settings['addcredit'] ) {
			$creditline = '<br /><span style="font-size: 0.8em">';
			$creditline .= __( 'Feed enhanced by ', ATF_LOCAL_NAME );
			$creditline .= '<a href="http://ajaydsouza.com/wordpress/plugins/add-to-feed/" rel="nofollow">Add To Feed</a>';

			$str_after .= $creditline;
			$str_after .= '<br />';
		}

        return $str_before.$content.$str_after;
    } else {
        return $content;
    }
}
add_filter( 'the_excerpt_rss', 'ald_atf', 99999999 );
add_filter( 'the_content_feed', 'ald_atf', 99999999 );


/**
 * Default Options.
 *
 * @return array Default options
 */
function atf_default_options() {
	$copyrightnotice = '&copy;' . date( "Y" ) . ' &quot;<a href="' . get_option( 'home' ) . '">' . get_option( 'blogname' ) . '</a>&quot;. ';
	$copyrightnotice .= __( 'Use of this feed is for personal non-commercial use only. If you are not reading this article in your feed reader, then the site is guilty of copyright infringement. Please contact me at ', ATF_LOCAL_NAME );
	$copyrightnotice .= get_option( 'admin_email' ) . ".";

	$atf_settings = array (
		'enable_plugin' 	=> false,		// Add HTML to Feed?
		'htmlbefore' 		=> '',		// HTML you want added to the feed
		'htmlafter'			=> '',		// HTML you want added to the feed
		'copyrightnotice' 	=> $copyrightnotice,		// Copyright Notice
		'addhtmlbefore' 	=> false,		// Add HTML to Feed?
		'addhtmlafter' 		=> false,		// Add HTML to Feed?
		'addtitle' 			=> true,		// Add title to the post?
		'addcopyright' 		=> true,		// Add copyright notice?
		'addcredit' 		=> false,		// Show credits?
	);
	return apply_filters( 'atf_default_options', $atf_settings );
}


/**
 * Function to read options from the database and add any new ones.
 *
 * @return array ATF options
 */
function atf_read_options() {
	$atf_settings_changed = false;

	$defaults = atf_default_options();

	$atf_settings = array_map( 'stripslashes', (array) get_option( 'ald_atf_settings' ) );
	unset( $atf_settings[0] ); // produced by the (array) casting when there's nothing in the DB

	// If there are any new options added to the Default Options array, let's add them
	foreach ( $defaults as $k=>$v ) {
		if ( ! isset( $atf_settings[ $k ] ) ) {
			$atf_settings[ $k ] = $v;
		}
		$atf_settings_changed = true;
	}

	if ( true == $atf_settings_changed ) {
		update_option( 'ald_atf_settings', $atf_settings );
	}

	return apply_filters( 'atf_read_options', $atf_settings );
}


/**
 *  Admin option
 *
 */
if ( is_admin() || strstr( $_SERVER['PHP_SELF'], 'wp-admin/' ) ) {

	/**
	 *  Load the admin pages if we're in the Admin.
	 *
	 */
	require_once(ALD_ATF_DIR . "/admin.inc.php");

	/**
	 * Adding WordPress plugin action links.
	 *
	 * @param array $links
	 * @return array
	 */
	function atf_plugin_actions_links( $links ) {

		return array_merge(
			array(
				'settings' => '<a href="' . admin_url( 'options-general.php?page=atf_options' ) . '">' . __( 'Settings', ATF_LOCAL_NAME ) . '</a>'
			),
			$links
		);

	}
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'atf_plugin_actions_links' );

	/**
	 * Add meta links on Plugins page.
	 *
	 * @param array $links
	 * @param string $file
	 * @return array
	 */
	function atf_plugin_actions( $links, $file ) {
		static $plugin;
		if ( ! $plugin ) {
			$plugin = plugin_basename( __FILE__ );
		}

		// create link
		if ( $file == $plugin ) {
			$links[] = '<a href="http://wordpress.org/support/plugin/better-search">' . __( 'Support', ATF_LOCAL_NAME ) . '</a>';
			$links[] = '<a href="http://ajaydsouza.com/donate/">' . __( 'Donate', ATF_LOCAL_NAME ) . '</a>';
		}
		return $links;
	}

	global $wp_version;
	if ( version_compare( $wp_version, '2.8alpha', '>' ) ) {
		add_filter( 'plugin_row_meta', 'atf_plugin_actions', 10, 2 ); // only 2.8 and higher
	} else {
		add_filter( 'plugin_action_links', 'atf_plugin_actions', 10, 2 );
	}

} // End admin.inc

?>