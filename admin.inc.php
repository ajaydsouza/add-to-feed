<?php
/**
 * Generates the settings page in the Admin
 *
 * @package Add_to_Feed
 */

// If this file is called directly, then abort execution.
if ( ! defined( 'WPINC' ) ) {
	die( "Aren't you supposed to come here via WP-Admin?" );
}

/**
 * Add to Feed options.
 */
function atf_options() {

	$atf_settings = atf_read_options();

	if ( isset( $_POST['atf_save'] ) && check_admin_referer( 'atf-admin-options' ) ) {
		$atf_settings['enable_plugin'] = isset( $_POST['enable_plugin'] ) ? true : false;
		$atf_settings['disable_notice'] = isset( $_POST['disable_notice'] ) ? true : false;
		$atf_settings['htmlbefore'] = $_POST['htmlbefore'];
		$atf_settings['htmlafter'] = $_POST['htmlafter'];
		$atf_settings['copyrightnotice'] = $_POST['copyrightnotice'];
		$atf_settings['addhtmlbefore'] = isset( $_POST['addhtmlbefore'] ) ? true : false;
		$atf_settings['addhtmlafter'] = isset( $_POST['addhtmlafter'] ) ? true : false;
		$atf_settings['addcopyright'] = isset( $_POST['addcopyright'] ) ? true : false;
		$atf_settings['addtitle'] = isset( $_POST['addtitle'] ) ? true : false;
		$atf_settings['addcredit'] = isset( $_POST['addcredit'] ) ? true : false;

		update_option( 'ald_atf_settings', $atf_settings );

		$str = '<div id="message" class="updated fade"><p>'. __( 'Options saved successfully.', 'add-to-feed' ) .'</p></div>';
		echo $str;
	}

	if ( isset( $_POST['atf_default'] ) && check_admin_referer( 'atf-admin-options' ) ) {

		delete_option( 'ald_atf_settings' );
		$atf_settings = atf_default_options();
		update_option( 'ald_atf_settings', $atf_settings );

		$str = '<div id="message" class="updated fade"><p>'. __( 'Options set to Default.', 'add-to-feed' ) .'</p></div>';
		echo $str;
	}
?>
<div class="wrap">
	<h2>Add to Feed</h2>
	<div id="poststuff">
	<div id="post-body" class="metabox-holder columns-2">
	<div id="post-body-content">
	  <form method="post" id="atf_options" name="atf_options" onsubmit="return checkForm()">
	    <div id="genopdiv" class="postbox"><div class="handlediv" title="<?php _e( 'Click to toggle', 'add-to-feed' ); ?>"><br /></div>
	      <h3 class='hndle'><span><?php _e( 'General options', 'add-to-feed' ); ?></span></h3>
	      <div class="inside">
			<table class="form-table">
				<tr>
					<th scope="row"><label for="enable_plugin"><?php _e( 'Enable the plugin:', 'add-to-feed' ); ?></label></th>
					<td><input type="checkbox" name="enable_plugin" id="enable_plugin" <?php if ( $atf_settings['enable_plugin'] ) echo 'checked="checked"' ?> /></td>
				</tr>
				<tr>
					<th scope="row"><label for="disable_notice"><?php _e( 'Disable admin-wide notice:', 'add-to-feed' ); ?></label></th>
					<td>
						<input type="checkbox" name="disable_notice" id="disable_notice" <?php if ( $atf_settings['disable_notice'] ) echo 'checked="checked"' ?> />
						<p class="description"><?php _e( 'Disables the "Add to Feed plugin is disabled." notice when the above option is unchecked.', 'add-to-feed' ) ?></p>
					</td>
				</tr>
				<tr style="vertical-align: top; "><td scope="row" colspan="2">
					<label><input type="checkbox" name="addhtmlbefore" id="addhtmlbefore" <?php if ( $atf_settings['addhtmlbefore'] ) echo 'checked="checked"' ?> /> <?php _e( 'Add the following to the feed before the content. (You can use HTML):', 'add-to-feed' ); ?></label>
					<br /><textarea name="htmlbefore" id="htmlbefore" rows="15" cols="80"><?php echo stripslashes( $atf_settings['htmlbefore'] ); ?></textarea></td>
				</tr>
				<tr style="vertical-align: top; "><td scope="row" colspan="2">
					<label><input type="checkbox" name="addhtmlafter" id="addhtmlafter" <?php if ( $atf_settings['addhtmlafter'] ) echo 'checked="checked"' ?> /> <?php _e( 'Add the following to the feed after the content. (You can use HTML):', 'add-to-feed' ); ?></label>
					<br /><textarea name="htmlafter" id="htmlafter" rows="15" cols="80"><?php echo stripslashes( $atf_settings['htmlafter'] ); ?></textarea></td>
				</tr>
				<tr style="vertical-align: top; "><td scope="row" colspan="2">
					<label><input type="checkbox" name="addcopyright" id="addcopyright" <?php if ( $atf_settings['addcopyright'] ) echo 'checked="checked"' ?> /> <?php _e( 'Add the following copyright notice to the feed (You can use HTML):', 'add-to-feed' ); ?></label>
					<br /><textarea name="copyrightnotice" id="copyrightnotice" rows="15" cols="80"><?php echo stripslashes( $atf_settings['copyrightnotice'] ); ?></textarea></td>
				</tr>
				<tr>
					<th scope="row"><label for="addtitle"><?php _e( 'Add a link to the title of the post in the feed:', 'add-to-feed' ); ?></label></th>
					<td><input type="checkbox" name="addtitle" id="addtitle" <?php if ( $atf_settings['addtitle'] ) echo 'checked="checked"' ?> /></td>
				</tr>
				<tr>
					<th scope="row"><label for="addcredit"><?php _e( 'Add a link to "Add to Feed" plugin page:', 'add-to-feed' ); ?></label></th>
					<td><input type="checkbox" name="addcredit" id="addcredit" <?php if ( $atf_settings['addcredit'] ) echo 'checked="checked"' ?> /></td>
				</tr>
			</table>
	      </div>
	    </div>
		<p>
		  <input type="submit" name="atf_save" id="atf_save" value="<?php _e( 'Save Options', 'add-to-feed' ); ?>" class="button button-primary" />
		  <input type="submit" name="atf_default" id="atf_default" value="<?php _e( 'Default Options', 'add-to-feed' ); ?>" class="button button-secondary" onclick="if ( ! confirm( '<?php _e( "Do you want to set options to Default?", 'add-to-feed' ); ?>' ) ) return false;" />
		</p>
		<?php wp_nonce_field( 'atf-admin-options' ); ?>
	  </form>

	</div><!-- /post-body-content -->
	<div id="postbox-container-1" class="postbox-container">
	  <div id="side-sortables" class="meta-box-sortables ui-sortable">
		  <?php atf_admin_side(); ?>
	  </div><!-- /side-sortables -->
	</div><!-- /postbox-container-1 -->
	</div><!-- /post-body -->
	<br class="clear" />
	</div><!-- /poststuff -->
</div><!-- /wrap -->

<?php
}


/**
 * Function to generate the right sidebar of the Settings page.
 */
function atf_admin_side() {
?>
    <div id="donatediv" class="postbox"><div class="handlediv" title="<?php _e( 'Click to toggle', 'add-to-feed' ); ?>"><br /></div>
      <h3 class='hndle'><span><?php _e( 'Support the development', 'add-to-feed' ); ?></span></h3>
      <div class="inside">
		<div id="donate-form">
			<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
				<input type="hidden" name="cmd" value="_xclick">
				<input type="hidden" name="business" value="donate@ajaydsouza.com">
				<input type="hidden" name="lc" value="IN">
				<input type="hidden" name="item_name" value="<?php _e( 'Donation for Add to Feed', 'add-to-feed' ); ?>">
				<input type="hidden" name="item_number" value="atf_admin">
				<strong><?php _e( 'Enter amount in USD: ', 'add-to-feed' ); ?></strong> <input name="amount" value="10.00" size="6" type="text"><br />
				<input type="hidden" name="currency_code" value="USD">
				<input type="hidden" name="button_subtype" value="services">
				<input type="hidden" name="bn" value="PP-BuyNowBF:btn_donate_LG.gif:NonHosted">
				<input type="image" src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="<?php _e( 'Send your donation to the author of Add to Feed', 'add-to-feed' ); ?>">
				<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1">
			</form>
		</div>
      </div>
    </div>
    <div id="followdiv" class="postbox"><div class="handlediv" title="<?php _e( 'Click to toggle', 'add-to-feed' ); ?>"><br /></div>
      <h3 class='hndle'><span><?php _e( 'Follow me', 'add-to-feed' ); ?></span></h3>
      <div class="inside">
		<div id="follow-us">
			<iframe src="//www.facebook.com/plugins/likebox.php?href=http%3A%2F%2Fwww.facebook.com%2Fajaydsouzacom&amp;width=292&amp;height=62&amp;colorscheme=light&amp;show_faces=false&amp;border_color&amp;stream=false&amp;header=true&amp;appId=113175385243" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:292px; height:62px;" allowTransparency="true"></iframe>
			<div style="text-align:center"><a href="https://twitter.com/ajaydsouza" class="twitter-follow-button" data-show-count="false" data-size="large" data-dnt="true">Follow @ajaydsouza</a>
			<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></div>
		</div>
      </div>
    </div>
    <div id="qlinksdiv" class="postbox"><div class="handlediv" title="<?php _e( 'Click to toggle', 'add-to-feed' ); ?>"><br /></div>
      <h3 class='hndle'><span><?php _e( 'Quick links', 'add-to-feed' ); ?></span></h3>
      <div class="inside">
        <div id="quick-links">
			<ul>
				<li><a href="http://ajaydsouza.com/wordpress/plugins/add-to-feed/"><?php _e( 'Add to Feed plugin page', 'add-to-feed' ); ?></a></li>
				<li><a href="http://ajaydsouza.com/wordpress/plugins/"><?php _e( 'Other plugins', 'add-to-feed' ); ?></a></li>
				<li><a href="http://ajaydsouza.com/"><?php _e( "Ajay's blog", 'add-to-feed' ); ?></a></li>
				<li><a href="https://wordpress.org/plugins/add-to-feed/faq/"><?php _e( 'FAQ', 'add-to-feed' ); ?></a></li>
				<li><a href="http://wordpress.org/support/plugin/add-to-feed"><?php _e( 'Support', 'add-to-feed' ); ?></a></li>
				<li><a href="https://wordpress.org/support/view/plugin-reviews/add-to-feed"><?php _e( 'Reviews', 'add-to-feed' ); ?></a></li>
			</ul>
        </div>
      </div>
    </div>

<?php
}


/**
 * Display a message at the top of Admin pages if the plugin is disabled. Filters `admin_notices`.
 */
function atf_admin_notice() {

	global $atf_settings;

	$plugin_settings_page = admin_url( 'options-general.php?page=atf_options' );

	if ( $atf_settings['enable_plugin'] || $atf_settings['disable_notice'] ) {
		return;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

    echo '<div class="error">
       <p>' . sprintf( __( 'Add to Feed plugin is disabled. Please visit the <a href="%s">plugin settings page</a> to enable the plugin or disable this notice.', 'add-to-feed' ), $plugin_settings_page ) . '</p>
    </div>';
}
add_action( 'admin_notices', 'atf_admin_notice' );


/**
 * Add menu item in WP-Admin.
 *
 */
function atf_adminmenu() {

	$plugin_page = add_options_page( __( "Add to Feed", 'add-to-feed' ), __( "Add to Feed", 'add-to-feed' ), 'manage_options', 'atf_options', 'atf_options');
	add_action( 'admin_head-'. $plugin_page, 'atf_adminhead' );
}
add_action( 'admin_menu', 'atf_adminmenu' );


/**
 * Function scripts to Admin head.
 *
 * @access public
 * @return void
 */
function atf_adminhead() {
	global $atf_url;

	wp_enqueue_script( 'common' );
	wp_enqueue_script( 'wp-lists' );
	wp_enqueue_script( 'postbox' );
?>
	<style type="text/css">
	.postbox .handlediv:before {
		right:12px;
		font:400 20px/1 dashicons;
		speak:none;
		display:inline-block;
		top:0;
		position:relative;
		-webkit-font-smoothing:antialiased;
		-moz-osx-font-smoothing:grayscale;
		text-decoration:none!important;
		content:'\f142';
		padding:8px 10px;
	}
	.postbox.closed .handlediv:before {
		content: '\f140';
	}
	.wrap h2:before {
	    content: "\f303";
	    display: inline-block;
	    -webkit-font-smoothing: antialiased;
	    font: normal 29px/1 'dashicons';
	    vertical-align: middle;
	    margin-right: 0.3em;
	}
	</style>

	<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready( function($) {
			// close postboxes that should be closed
			$('.if-js-closed').removeClass('if-js-closed').addClass('closed');
			// postboxes setup
			postboxes.add_postbox_toggles('atf_options');
		});
		//]]>
	</script>

	<script type="text/javascript" language="JavaScript">
		//<![CDATA[
		function checkForm() {
		answer = true;
		if (siw && siw.selectingSomething)
			answer = false;
		return answer;
		}//
		//]]>
	</script>

<?php
}

?>