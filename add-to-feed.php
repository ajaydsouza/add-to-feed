<?php
/*
Plugin Name: Add to Feed
Version:     1.1
Plugin URI:  http://ajaydsouza.com/wordpress/plugins/add-to-feed/
Description: Add to Feed is a feed enhancement plugin that allows you to easily add a copyright notice and custom text/HTML to your WordPress blog feed. The custom text can be entered before and/or after the content of your blog post. <a href="options-general.php?page=atf_options">Configure...</a>
Author:      Ajay D'Souza
Author URI:  http://ajaydsouza.com/
*/

if (!defined('ABSPATH')) die("Aren't you supposed to come here via WP-Admin?");

function ald_atf_init() {
     load_plugin_textdomain('myald_atf_plugin', PLUGINDIR.'/'.dirname(plugin_basename(__FILE__)));
}
add_action('init', 'ald_atf_init');

define('ALD_ATF_DIR', dirname(__FILE__));

/*********************************************************************
*				Main Function (Do not edit)							*
********************************************************************/
function ald_atf($content)
{
	$atf_settings = atf_read_options();
	$creditline = '<br /><span style="font-size: 0.8em">Feed enhanced by the <a href="http://ajaydsouza.com/wordpress/plugins/add-to-feed/">Add To Feed Plugin</a> by <a href="http://ajaydsouza.com/">Ajay D\'Souza</a></span>';
	
	$str_before ='';
	$str_after ='<hr style="border-top:black solid 1px" />';
	
    if(is_feed()) {
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
						htmlbefore => '',		// HTML you want added to the feed
						htmlafter => '',		// HTML you want added to the feed
						copyrightnotice => $copyrightnotice,		// Copyright Notice
						emailaddress => get_option('admin_email'),		// Admin Email
						addhtmlbefore => false,		// Add HTML to Feed?
						addhtmlafter => false,		// Add HTML to Feed?
						addtitle => true,		// Add title to the post?
						addcopyright => true,		// Add copyright notice?
						addcredit => true,		// Show credits?
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
}


?>