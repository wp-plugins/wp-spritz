<?php
/*
Plugin Name: WP Spritz
Plugin Script: wpspritz.php
Plugin URI: https://wordpress.org/plugins/wp-spritz
Description: This plugin integrate Spritz to your Wordpress Website. Spritz is the best way to engage with content in the digital age. It deliver a focused reading experience and help readers get their content faster, with less effort and across any device or screen size.
Version: 1.0
Author: Romain Laurent
Author URI: http://www.rlaurent.com

=== RELEASE NOTES ===
2015-01-28 - v1.0 - first version
*/


/**
 * Admin settings
 */
add_action( 'admin_init', 'wpspritz_settings' );
function wpspritz_settings() {
	register_setting( 'wpspritz-settings-group', 'client_id' );
}

/**
 * Add Settings menu in Admin 
 */
add_action('admin_menu', 'wpspritz_settings_menu');
function wpspritz_settings_menu() {
	add_menu_page('WpSpritz Admin Settings', 'WpSpritz Settings', 'administrator', 'wpspritz-settings', 'wpspritz_settings_menu_page', 'dashicons-admin-generic');
}

function wpspritz_settings_menu_page() {
  	?>

  	<div class="wrap">

	<h2>Setup</h2>
  	<ul>
		<li>1. Sign up to the Spritz Software Developers Kit <a href="http://www.spritzinc.com/developers/">here.</a></li>
		<li>2. You will recieve an e-mail with your Application ClientId.</li>
		<li>3. Put the Application ClientId values in the field "client ID" below.</li>
		<li>4. Save changes!</li>
  	</ul>

	<h2>Setting</h2>
	 
	<form method="post" action="options.php">
	    <?php settings_fields( 'wpspritz-settings-group' ); ?>
	    <?php do_settings_sections( 'wpspritz-settings-group' ); ?>
	    <table class="form-table">
	        <tr valign="top">
	        <th scope="row">Client Id</th>
	        <td><input type="text" name="client_id" value="<?php echo esc_attr( get_option('client_id') ); ?>" /></td>
	        </tr>
	    </table>
	    
	    <?php submit_button(); ?>
	 
	</form>
	</div>

	<?php
}

/**
 * Add Spritz settings to head
 */
add_action( 'wp_enqueue_scripts', 'wpspritz_settings_head', 20);
function wpspritz_settings_head() {
  echo '
		<script type="text/javascript">
		var SpritzSettings = {
		      clientId: "' . esc_attr( get_option('client_id') ) . '",
		      redirectUri: "login_success.html",
		};
		</script>';
}

/**
 * Add all scripts and style to head
 */
add_action('wp_enqueue_scripts', 'add_all_scripts', 21);
function add_all_scripts() {
	wp_enqueue_style( 'spritzcss', plugins_url( 'wpspritz.css', __FILE__ ) );
	wp_enqueue_script( 'spritzjs', '//sdk.spritzinc.com/js/1.2/js/spritz.min.js' );
}

/**
 * Generate the (HTML) output
 */
add_filter('the_content', 'wpspritz_filter');
function wpspritz_filter($content) {
	if ( is_single() && get_option('client_id') ){
		$datarole = '
					<div class="spritz-dropdown">
						<div class="reticle-primary">
							<div data-role="spritzer" data-selector="p"></div>
						</div>
					</div>
					';
		$content = $datarole . $content;
	}

	return $content;
}



?>