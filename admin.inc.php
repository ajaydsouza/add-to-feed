<?php
/**********************************************************************
*					Admin Page										*
*********************************************************************/
function atf_options() {
	
	$atf_settings = atf_read_options();

	if($_POST['atf_save']){
		$atf_settings[htmlbefore] = ($_POST['htmlbefore']);
		$atf_settings[htmlafter] = ($_POST['htmlafter']);
		$atf_settings[copyrightnotice] = ($_POST['copyrightnotice']);
		$atf_settings[emailaddress] = $_POST['emailaddress'];
		$atf_settings[addhtmlbefore] = (($_POST['addhtmlbefore']) ? true : false);
		$atf_settings[addhtmlafter] = (($_POST['addhtmlafter']) ? true : false);
		$atf_settings[addcopyright] = (($_POST['addcopyright']) ? true : false);
		$atf_settings[addcredit] = (($_POST['addcredit']) ? true : false);
		
		update_option('ald_atf_settings', $atf_settings);
		
		$str = '<div id="message" class="updated fade"><p>'. __('Options saved successfully.','ald_atf_plugin') .'</p></div>';
		echo $str;
	}
	
	if ($_POST['atf_default']){
	
		delete_option('ald_atf_settings');
		$atf_settings = atf_default_options();
		update_option('ald_atf_settings', $atf_settings);
		
		$str = '<div id="message" class="updated fade"><p>'. __('Options set to Default.','ald_atf_plugin') .'</p></div>';
		echo $str;
	}
?>

<div class="wrap">
  <h2> Add to Feed </h2>
  <div style="border: #ccc 1px solid; padding: 10px">
    <fieldset class="options">
    <legend>
    <h3>
      <?php _e('Support the Development','ald_atf_plugin'); ?>
    </h3>
    </legend>
    <p>
      <?php _e('If you find my','ald_atf_plugin'); ?>
      <a href="http://ajaydsouza.com/wordpress/plugins/add-to-feed/">Add to Feed</a>
      <?php _e('useful, please do','ald_atf_plugin'); ?>
      <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_xclick&amp;business=donate@ajaydsouza.com&amp;item_name=Add%20to%20Feed%20(From%20WP-Admin)&amp;no_shipping=1&amp;return=http://ajaydsouza.com/wordpress/plugins/add-to-feed/&amp;cancel_return=http://ajaydsouza.com/wordpress/plugins/add-to-feed/&amp;cn=Note%20to%20Author&amp;tax=0&amp;currency_code=USD&amp;bn=PP-DonationsBF&amp;charset=UTF-8" title="Donate via PayPal"><?php _e('drop in your contribution','ald_atf_plugin'); ?></a>.
	  (<a href="http://ajaydsouza.com/donate/"><?php _e('Some reasons why you should.','ald_atf_plugin'); ?></a>)</p>
    </fieldset>
  </div>
  <form method="post" id="atf_options" name="atf_options" style="border: #ccc 1px solid; padding: 10px">
    <fieldset class="options">
    <legend>
    <h3>
      <?php _e('Options:','ald_atf_plugin'); ?>
    </h3>
    </legend>
    <p>
      <label>
      <input type="checkbox" name="addhtmlbefore" id="addhtmlbefore" <?php if ($atf_settings[addhtmlbefore]) echo 'checked="checked"' ?> />
      <?php _e('Add the following to the feed before the content. (You can use HTML)','ald_atf_plugin'); ?>
      </label>
    </p>
    <p>
      <label>
      <textarea name="htmlbefore" id="htmlbefore" cols="45" rows="5"><?php echo stripslashes($atf_settings[htmlbefore]); ?></textarea>
      </label>
    </p>
    <p>
      <label>
      <input type="checkbox" name="addhtmlafter" id="addhtmlafter" <?php if ($atf_settings[addhtmlafter]) echo 'checked="checked"' ?> />
      <?php _e('Add the following to the feed after the content. (You can use HTML)','ald_atf_plugin'); ?>
      </label>
    </p>
    <p>
      <label>
      <textarea name="htmlafter" id="htmlafter" cols="45" rows="5"><?php echo stripslashes($atf_settings[htmlafter]); ?></textarea>
      </label>
    </p>
    <p>
      <label>
      <input type="checkbox" name="addcopyright" id="addcopyright" <?php if ($atf_settings[addcopyright]) echo 'checked="checked"' ?> />
      <?php _e('Add Copyright to Feed','ald_atf_plugin'); ?>
      </label>
    </p>
    <p>
      <label><?php _e('Copyright Notice','ald_atf_plugin'); ?><br />
      <textarea name="copyrightnotice" id="copyrightnotice" cols="45" rows="5"><?php echo stripslashes($atf_settings[copyrightnotice]); ?></textarea>
      </label>
    </p>
    <p>
      <label>
      <input type="checkbox" name="addcredit" id="addcredit" <?php if ($atf_settings[addcredit]) echo 'checked="checked"' ?> />
      <?php _e('Add Plugin Credits to Feed? While this is not compulsory, it would be nice.','ald_atf_plugin'); ?>
      </label>
    </p>
    <p>
      <input type="submit" name="atf_save" id="atf_save" value="Save Options" style="border:#00CC00 1px solid" />
      <input name="atf_default" type="submit" id="atf_default" value="Default Options" style="border:#FF0000 1px solid" onclick="if (!confirm('<?php _e('Do you want to set options to Default? If you don\'t have a copy of the username, please hit Cancel and copy it first.','ald_atf_plugin'); ?>')) return false;" />
    </p>
    </fieldset>
  </form>
</div>
<?php

}


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
		add_options_page(__("Add to Feed"), __("Add to Feed"), 9, 'atf_options', 'atf_options');
		}
}


add_action('admin_menu', 'atf_adminmenu');

?>