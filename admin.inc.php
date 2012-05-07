<?php
/**********************************************************************
*					Admin Page										*
*********************************************************************/
function atf_options() {
	
	$atf_settings = atf_read_options();
	
	if($_POST['atf_save']){
		$atf_settings[enable_plugin] = (($_POST['enable_plugin']) ? true : false);
		$atf_settings[htmlbefore] = ($_POST['htmlbefore']);
		$atf_settings[htmlafter] = ($_POST['htmlafter']);
		$atf_settings[copyrightnotice] = ($_POST['copyrightnotice']);
		$atf_settings[emailaddress] = $_POST['emailaddress'];
		$atf_settings[addhtmlbefore] = (($_POST['addhtmlbefore']) ? true : false);
		$atf_settings[addhtmlafter] = (($_POST['addhtmlafter']) ? true : false);
		$atf_settings[addcopyright] = (($_POST['addcopyright']) ? true : false);
		$atf_settings[addtitle] = (($_POST['addtitle']) ? true : false);
		$atf_settings[addcredit] = (($_POST['addcredit']) ? true : false);
		
		update_option('ald_atf_settings', $atf_settings);
		
		$str = '<div id="message" class="updated fade"><p>'. __('Options saved successfully.',ATF_LOCAL_NAME) .'</p></div>';
		echo $str;
	}
	
	if ($_POST['atf_default']){
	
		delete_option('ald_atf_settings');
		$atf_settings = atf_default_options();
		update_option('ald_atf_settings', $atf_settings);
		
		$str = '<div id="message" class="updated fade"><p>'. __('Options set to Default.',ATF_LOCAL_NAME) .'</p></div>';
		echo $str;
	}
?>

<div class="wrap">
	<div id="page-wrap">
	<div id="inside">
		<div id="header">
		<h2>Add to All</h2>
		</div>
	  <div id="side">
		<div class="side-widget">
			<span class="title"><?php _e('Support the development',ATF_LOCAL_NAME) ?></span>
			<div id="donate-form">
				<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_xclick">
				<input type="hidden" name="business" value="donate@ajaydsouza.com">
				<input type="hidden" name="lc" value="IN">
				<input type="hidden" name="item_name" value="Donation for Add to Feed">
				<input type="hidden" name="item_number" value="atf">
				<strong><?php _e('Enter amount in USD: ',ATF_LOCAL_NAME) ?></strong> <input name="amount" value="10.00" size="6" type="text"><br />
				<input type="hidden" name="currency_code" value="USD">
				<input type="hidden" name="button_subtype" value="services">
				<input type="hidden" name="bn" value="PP-BuyNowBF:btn_donate_LG.gif:NonHosted">
				<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="<?php _e('Send your donation to the author of',ATF_LOCAL_NAME) ?> Add to All?">
				<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
				</form>
			</div>
		</div>
		<div class="side-widget">
		<span class="title"><?php _e('Quick links') ?></span>				
		<ul>
			<li><a href="http://ajaydsouza.com/wordpress/plugins/add-to-feed/"><?php _e('Add to Feed ');_e('plugin page',ATF_LOCAL_NAME) ?></a></li>
			<li><a href="http://ajaydsouza.com/wordpress/plugins/"><?php _e('Other plugins',ATF_LOCAL_NAME) ?></a></li>
			<li><a href="http://ajaydsouza.com/"><?php _e('Ajay\'s blog',ATF_LOCAL_NAME) ?></a></li>
			<li><a href="http://ajaydsouza.com/support/"><?php _e('Support',ATF_LOCAL_NAME) ?></a></li>
			<li><a href="http://twitter.com/ajaydsouza"><?php _e('Follow @ajaydsouza on Twitter',ATF_LOCAL_NAME) ?></a></li>
		</ul>
		</div>
		<div class="side-widget">
		<span class="title"><?php _e('Recent developments',ATF_LOCAL_NAME) ?></span>				
		<?php require_once(ABSPATH . WPINC . '/rss.php'); wp_widget_rss_output('http://ajaydsouza.com/archives/category/wordpress/plugins/feed/', array('items' => 5, 'show_author' => 0, 'show_date' => 1));
		?>
		</div>
		<div class="side-widget">
		<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fajaydsouzacom&amp;width=292&amp;height=62&amp;colorscheme=light&amp;show_faces=false&amp;border_color&amp;stream=false&amp;header=true&amp;appId=113175385243" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:292px; height:62px;" allowTransparency="true"></iframe>
		</div>
	  </div>

	  <div id="options-div">
	  <div class="updated"><br />If you like <strong>Add to Feed</strong>, check out the more powerful plugin <a href="http://ajaydsouza.com/wordpress/plugins/add-to-all/">Add to All</a><br />&nbsp;</div>
	  <form method="post" id="atf_options" name="atf_options" onsubmit="return checkForm()">
		<fieldset class="options">
		<table class="form-table">
			<tr style="vertical-align: top;"><th scope="row" style="background:#EEE"><label for="enable_plugin"><?php _e('Enable the plugin: ',ATF_LOCAL_NAME); ?></label></th>
			<td style="background:#EEE"><input type="checkbox" name="enable_plugin" id="enable_plugin" <?php if ($atf_settings[enable_plugin]) echo 'checked="checked"' ?> /></td>
			</tr>
			<tr style="vertical-align: top; "><td scope="row" colspan="2">
				<label><input type="checkbox" name="addhtmlbefore" id="addhtmlbefore" <?php if ($atf_settings[addhtmlbefore]) echo 'checked="checked"' ?> /> <?php _e('Add the following to the feed before the content. (You can use HTML): ',ATF_LOCAL_NAME); ?></label>
				<br /><textarea name="htmlbefore" id="htmlbefore" rows="15" cols="80"><?php echo stripslashes($atf_settings[htmlbefore]); ?></textarea></td>
			</tr>
			<tr style="vertical-align: top; "><td scope="row" colspan="2">
				<label><input type="checkbox" name="addhtmlafter" id="addhtmlafter" <?php if ($atf_settings[addhtmlafter]) echo 'checked="checked"' ?> /> <?php _e('Add the following to the feed after the content. (You can use HTML): ',ATF_LOCAL_NAME); ?></label>
				<br /><textarea name="htmlafter" id="htmlafter" rows="15" cols="80"><?php echo stripslashes($atf_settings[htmlafter]); ?></textarea></td>
			</tr>
			<tr style="vertical-align: top; "><td scope="row" colspan="2">
				<label><input type="checkbox" name="addcopyright" id="addcopyright" <?php if ($atf_settings[addcopyright]) echo 'checked="checked"' ?> /> <?php _e('Add the following copyright notice to the feed (You can use HTML): ',ATF_LOCAL_NAME); ?></label>
				<br /><textarea name="copyrightnotice" id="copyrightnotice" rows="15" cols="80"><?php echo stripslashes($atf_settings[copyrightnotice]); ?></textarea></td>
			</tr>
			<tr style="vertical-align: top;"><th scope="row"><label for="addtitle"><?php _e('Add a link to the title of the post in the feed: ',ATF_LOCAL_NAME); ?></label></th>
			<td><input type="checkbox" name="addtitle" id="addtitle" <?php if ($atf_settings[addtitle]) echo 'checked="checked"' ?> /></td>
			</tr>
			<tr style="vertical-align: top;"><th scope="row"><label for="addcredit"><?php _e('Add a link to "Add to All" plugin page: ',ATF_LOCAL_NAME); ?></label></th>
			<td><input type="checkbox" name="addcredit" id="addcredit" <?php if ($atf_settings[addcredit]) echo 'checked="checked"' ?> /></td>
			</tr>
		</table>
		<p>
		  <input type="submit" name="atf_save" id="atf_save" value="Save Options" style="border:#0C0 1px solid" />
		  <input name="atf_default" type="submit" id="atf_default" value="Default Options" style="border:#F00 1px solid" onclick="if (!confirm('<?php _e('Do you want to set options to Default?',ATF_LOCAL_NAME); ?>')) return false;" />
		</p>
		</fieldset>
	  </form>
	</div>

	  </div>
	  <div style="clear: both;"></div>
	</div>
</div>
<?php

}

function atf_admin_notice() {
	$plugin_settings_page = '<a href="' . admin_url( 'options-general.php?page=atf_options' ) . '">' . __('plugin settings page', ATF_LOCAL_NAME ) . '</a>';

	$atf_settings = atf_read_options();
	if ($atf_settings[enable_plugin]) return;
	if ( !current_user_can( 'manage_options' ) ) return;

    echo '<div class="error">
       <p>'.__('Add to Feed plugin is disabled. Please visit the ', ATF_LOCAL_NAME ).$plugin_settings_page.__(' to enable.', ATF_LOCAL_NAME ).'</p>
    </div>';
}
add_action('admin_notices', 'atf_admin_notice');

function atf_adminmenu() {
	if (function_exists('current_user_can')) {
		// In WordPress 2.x
		if (current_user_can('manage_options')) {
			$atf_is_admin = true;
		}
	} else {
		// In WordPress 1.x
		global $user_ID;
		if (user_can_edit_user($user_ID, 0)) {
			$atf_is_admin = true;
		}
	}

	if ((function_exists('add_options_page'))&&($atf_is_admin)) {
		$plugin_page = add_options_page(__("Add to Feed", ATF_LOCAL_NAME), __("Add to Feed", ATF_LOCAL_NAME), 9, 'atf_options', 'atf_options');
		add_action( 'admin_head-'. $plugin_page, 'atf_adminhead' );
	}
	
}
add_action('admin_menu', 'atf_adminmenu');

function atf_adminhead() {
	global $atf_url;

?>
<link rel="stylesheet" type="text/css" href="<?php echo $atf_url ?>/admin-styles.css" />
<script type="text/javascript" language="JavaScript">
function checkForm() {
answer = true;
if (siw && siw.selectingSomething)
	answer = false;
return answer;
}//
</script>
<?php }

?>