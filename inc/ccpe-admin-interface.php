<?php
/**
 * @package		Hit_Wp_Ccpe
 * @author		Michael Johnson <michael.johnson@highimpacttech.com>
 * @license		GPL-2.0+
 * @link		http://highimpacttech.com/go/wordpress
 * @copyright	2016 High Impact Technologies
 *
 * @wordpress-plugin
 * Plugin Name:			Custom category and page extensions
 * PLugin URI:			http://highimpacttech.com/go/wpccpe
 * Description:			Add any ".extension" to WordPress category and page urls.
 * Version:				0.0.1
 * Stable tag:			trunk
 * Author:				High Impact Technologies, techbymike
 * Author URI:			http://highimpacttech.com/
 * Text Domain:			hit-wp-ccpe
 * License:				GPLv2 or later
 * License URI:			http://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path:			/languages
 * GitHub Plugin URI:	https://github.com/highimpacttech/WordPress-Custom-Category-and-Page-Extensions
 * GitHib Branch:		master
*/

/**
 * Custom category and page extensions
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	 See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * Copyright 2016 High Impact Technologies, http://highimpacttech.com/
*/

function hit_add_ccpe_settings_menu() {
    if ( hit_get_menu_item( 'hit_plugin_core' ) ) {
        add_submenu_page( 'hit_plugin_core', 'Custom category and page extensions', 'Ccpe Settings', 'administrator', 'hit_wp_ccpe', 'ccpe_settings_page' );
    } else {
        add_options_page( 'Custom category and page extensions - Settings', 'Custom category and page extensions', 'administrator', 'hit-wp-ccpe', 'ccpe_settings_page' );
    }
    add_action( 'admin_init', 'register_ccpe_settings' );
}
add_action( 'admin_menu', 'hit_add_ccpe_settings_menu' );

function register_ccpe_settings() {
    flush_rewrite_rules();
	register_setting( 'ccpe-settings-group', 'ccpe_extension_to_use' );
}

function ccpe_settings_page() {
	$ccpe_extension_to_use = get_option( 'ccpe_extension_to_use' );
	if ( empty( $ccpe_extension_to_use ) ) {
		update_option( 'ccpe_extension_to_use', 'html' );
		$ccpe_extension_to_use	= get_option( 'ccpe_extension_to_use' );
	}
?>
<div class="wrap">
	<h2>Custom category and page extensions</h2>
	<form method="post" action="options.php">
		<?php settings_fields( 'ccpe-settings-group' ); ?>
		<table class="form-table">
			<tr valign="top">
				<th scope="row">Extension to use</th>
				<td>
					<input name="ccpe_extension_to_use" maxlength="10" value="<?php echo $ccpe_extension_to_use; ?>" type="text" />
				</td>
			</tr>
		</table>
		<p class="submit">
			<input type="submit" name="submit-bpu" class="button-primary" value="<?php _e( 'Save Changes', 'hit-wp-ccpe' ); ?>" />
		</p>
	</form>
</div>
<?php }
