<?php
/*
Plugin Name: Multisite wp_head Code
Description: Includes custom HTML/CSS/JS code in the <head> section on every blog in the network.
Version: 1.1
Author: Zaantar
Author URI: http://zaantar.eu
License: GPL2
*/

/*
    Copyright 2011 Zaantar (email: zaantar@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/* ************************************************************************* *\
	I18N
\* ************************************************************************* */


define( 'MWC_TEXTDOMAIN', 'multisite-wphead-code' );


add_action( 'init', 'mwc_load_textdomain' );


function mwc_load_textdomain() {
	$plugin_dir = basename(dirname(__FILE__));
	load_plugin_textdomain( MURM_TEXTDOMAIN, false, $plugin_dir.'/languages' );
}


/*****************************************************************************\
		LOGGING AND NOTICES
\*****************************************************************************/


function mwc_nag( $message ) {
	echo( '<div id="message" class="updated"><p>'.$message.'</p></div>' );
}

function mwc_nagerr( $message ) {
	echo( '<div id="message" class="error"><p>'.$message.'</p></div>' );
}



/*****************************************************************************\
		OPTIONS
\*****************************************************************************/


add_action( 'network_admin_menu','mwc_network_admin_menu' );


function mwc_network_admin_menu() {
	add_submenu_page( 'settings.php', __( 'Multisite wp_head Code', MWC_TEXTDOMAIN ), __( 'Multisite wp_head Code', MWC_TEXTDOMAIN ),
		'manage_network_options', 'mwc-options', 'mwc_options_page' );
}


function mwc_options_page() {
	if( isset($_REQUEST['action']) ) {
        $action = $_REQUEST['action'];
    } else {
        $action = 'default';
    }
    
    switch( $action ) {
    case 'update-options':
    	update_site_option( 'mlj_code', $_POST['mwc_code'] );
    	mwc_update_settings( $_POST['settings'] );
    	mwc_nag( __( 'Settings saved.', MWC_TEXTDOMAIN ) );
    	mwc_options_page_default();
    	break;
	default:
		mwc_options_page_default();
		break;
	}
}
/* <?php _e( '', MWC_TEXTDOMAIN ); ?> */

function mwc_options_page_default() {
	extract( mwc_get_settings() );
	?>
	<div id="wrap">
		<h2><?php _e( 'Multisite wp_head Code', MWC_TEXTDOMAIN ); ?></h2>
		<?php
			if( !$hide_donation_button ) {
				?>
				<h3><?php _e( 'Please consider a donation', MWC_TEXTDOMAIN ); ?></h3>
				<p>
					<?php _e( 'I spend quite a lot of my precious time working on opensource WordPress plugins. If you find this one useful, please consider helping me develop it further. Even the smallest amount of money you are willing to spend will be welcome.', MWC_TEXTDOMAIN ); ?>
					<form action="https://www.paypal.com/cgi-bin/webscr" method="post"><input type="hidden" name="cmd" value="_s-xclick">
						<input type="hidden" name="hosted_button_id" value="39WB3KGYFB3NA">
						<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!" style="border:none;" >
						<img style="display:none;" alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
					</form>
				</p>
				<?php
			}
		?>
		<h3><?php _e( 'Settings', SUH_TEXTDOMAIN ); ?></h3>
		<form method="post">
            <input type="hidden" name="action" value="update-options" />
            <table class="form-table">
                <tr valign="top">
                	<th><label for="mwc_code"><?php _e( 'Code (<code>HTML</code>, <code>CSS</code>, <code>JS</code> etc):', MWC_TEXTDOMAIN ); ?></label></th>
                	<td>
                		<textarea name="mwc_code" cols="60" rows="15" style="font-family: monospace;"><?php echo stripslashes( get_site_option( 'mlj_code' ) ); ?></textarea>
                	</td>
                </tr>
                <tr valign="top">
                	<th>
                		<label><?php _e( 'Hide donation button', MWC_TEXTDOMAIN ); ?></label><br />
                	</th>
                	<td>
                		<input type="checkbox" name="settings[hide_donation_button]" 
                			<?php if( $hide_donation_button ) echo 'checked="checked"'; ?>
                		/>
                	</td>
                	<td><small><?php _e( 'If you don\'t want to be bothered again...', MWC_TEXTDOMAIN ); ?></small></td>
                </tr>
			</table>
			<p class="submit">
	            <input type="submit" class="button-primary" value="<?php _e( 'Save', MWC_TEXTDOMAIN ); ?>" />    
	        </p>
		</form>
	</div>
	<?php
}


define( 'MWC_SETTINGS', 'mwc_settings' );

function mwc_get_settings() {
	$defaults = array(
		'hide_donation_button' => false
	);
	$settings = get_site_option( MWC_SETTINGS, array() );
	return wp_parse_args( $settings, $defaults );
}


function mwc_update_settings( $settings ) {
	update_site_option( MWC_SETTINGS, $settings );
}

/*****************************************************************************\
		CODE EMBEDDING
\*****************************************************************************/

add_action( 'wp_head', 'mwc_embed_code' );

function mwc_embed_code() {
	if( is_admin() ) {
		return;
	}
	
	echo stripslashes( get_site_option( 'mlj_code' ) );

}

?>
