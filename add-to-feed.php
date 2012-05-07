<?php
/*
Plugin Name: Add to Feed
Version:     1.2
Plugin URI:  http://ajaydsouza.com/wordpress/plugins/add-to-feed/
Description: Add to Feed is a feed enhancement plugin that allows you to easily add a copyright notice and custom text/HTML to your WordPress blog feed. The custom text can be entered before and/or after the content of your blog post.
Author:      Ajay D'Souza
Author URI:  http://ajaydsouza.com/
*/

if (!defined('ABSPATH')) die("Aren't you supposed to come here via WP-Admin?");

define('ALD_ATF_DIR', dirname(__FILE__));
define('ATF_LOCAL_NAME', 'atf');

// Pre-2.6 compatibility
if ( ! defined( 'WP_CONTENT_URL' ) )
      define( 'WP_CONTENT_URL', get_option( 'siteurl' ) . '/wp-content' );
if ( ! defined( 'WP_CONTENT_DIR' ) )
      define( 'WP_CONTENT_DIR', ABSPATH . 'wp-content' );
if ( ! defined( 'WP_PLUGIN_URL' ) )
      define( 'WP_PLUGIN_URL', WP_CONTENT_URL. '/plugins' );
if ( ! defined( 'WP_PLUGIN_DIR' ) )
      define( 'WP_PLUGIN_DIR', WP_CONTENT_DIR . '/plugins' );

// Guess the location
$atf_path = WP_PLUGIN_DIR.'/'.plugin_basename(dirname(__FILE__));
$atf_url = WP_PLUGIN_URL.'/'.plugin_basename(dirname(__FILE__));

function ald_atf_init() {
	//* Begin Localization Code */
	$atf_localizationName = ATF_LOCAL_NAME;
	$atf_comments_locale = get_locale();
	$atf_comments_mofile = ALD_ATF_DIR . "/languages/" . $atf_localizationName . "-". $atf_comments_locale.".mo";
	load_textdomain($atf_localizationName, $atf_comments_mofile);
	//* End Localization Code */
}
add_action('init', 'ald_atf_init');


/*********************************************************************
*				Main Function (Do not edit)							*
********************************************************************/
function ald_atf($content)
{
	$atf_settings = atf_read_options();
	$creditline = '<br /><span style="font-size: 0.8em">Feed enhanced by the <a href="http://ajaydsouza.com/wordpress/plugins/add-to-feed/">Add To Feed Plugin</a> by <a href="http://ajaydsouza.com/">Ajay D\'Souza</a></span>';
	
	$str_before ='';
	$str_after ='<hr style="border-top:black solid 1px" />';
	
    if(is_feed()&&$atf_settings[enable_plugin]) {
		if($atf_settings[addhtmlbefore])
		{
			$str_before .= stripslashes($atf_settings[htmlbefore]);
			$str_before .= '<br />';
		}
		
		if($atf_settings[addhtmlafter])
		{
			$str_after .= stripslashes($atf_settings[htmlafter]);
			$str_after .= '<br />';
		}
		
		if($atf_settings[addtitle])
		{
			$str_after .= '<a href="'.get_permalink().'">'.the_title('','',false).'</a> was first posted on '.get_the_time('F j, Y').' at '.get_the_time('g:i a').'.';
			$str_after .= '<br />';
		}
		
		if($atf_settings[addcopyright])
		{
			$str_after .= stripslashes($atf_settings[copyrightnotice]);
			$str_after .= '<br />';
		}
		
		if($atf_settings[addcredit])
		{
			$str_after .= $creditline;
			$str_after .= '<br />';
		}
		
        return $str_before.$content.$str_after;
    } else {
        return $content;
    }
}
add_filter('the_content', 'ald_atf',99999999);

// Default Options
function atf_default_options() {
	$copyrightnotice = '&copy;'. date("Y").' &quot;<a href="'.get_option('home').'">'.get_option('blogname').'</a>&quot;. ';
	$copyrightnotice .= __('Use of this feed is for personal non-commercial use only. If you are not reading this article in your feed reader, then the site is guilty of copyright infringement. Please contact me at ','ald_atf_plugin');
	$copyrightnotice .= get_option('admin_email');

	$atf_settings = 	Array (
						'enable_plugin' => false,		// Add HTML to Feed?
						'htmlbefore' => '',		// HTML you want added to the feed
						'htmlafter' => '',		// HTML you want added to the feed
						'copyrightnotice' => $copyrightnotice,		// Copyright Notice
						'emailaddress' => get_option('admin_email'),		// Admin Email
						'addhtmlbefore' => false,		// Add HTML to Feed?
						'addhtmlafter' => false,		// Add HTML to Feed?
						'addtitle' => true,		// Add title to the post?
						'addcopyright' => true,		// Add copyright notice?
						'addcredit' => false,		// Show credits?
						);
	return $atf_settings;
}

// Function to read options from the database
function atf_read_options() 
{
	$atf_settings_changed = false;
	
	$defaults = atf_default_options();
	
	$atf_settings = array_map('stripslashes',(array)get_option('ald_atf_settings'));
	unset($atf_settings[0]); // produced by the (array) casting when there's nothing in the DB
	
	foreach ($defaults as $k=>$v) {
		if (!isset($atf_settings[$k]))
			$atf_settings[$k] = $v;
		$atf_settings_changed = true;	
	}
	if ($atf_settings_changed == true)
		update_option('ald_atf_settings', $atf_settings);
	
	return $atf_settings;

}


// This function adds an Options page in WP Admin
if (is_admin() || strstr($_SERVER['PHP_SELF'], 'wp-admin/')) {
	require_once(ALD_ATF_DIR . "/admin.inc.php");

	// Add meta links
	function atf_plugin_actions( $links, $file ) {
		static $plugin;
		if (!$plugin) $plugin = plugin_basename(__FILE__);
	 
		// create link
		if ($file == $plugin) {
			$links[] = '<a href="' . admin_url( 'options-general.php?page=atf_options' ) . '">' . __('Settings', ATF_LOCAL_NAME ) . '</a>';
			$links[] = '<a href="http://ajaydsouza.com/support/">' . __('Support', ATF_LOCAL_NAME ) . '</a>';
			$links[] = '<a href="http://ajaydsouza.com/donate/">' . __('Donate', ATF_LOCAL_NAME ) . '</a>';
		}
		return $links;
	}
	global $wp_version;
	if ( version_compare( $wp_version, '2.8alpha', '>' ) )
		add_filter( 'plugin_row_meta', 'atf_plugin_actions', 10, 2 ); // only 2.8 and higher
	else add_filter( 'plugin_action_links', 'atf_plugin_actions', 10, 2 );

}

?>