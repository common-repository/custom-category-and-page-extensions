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

if ( !function_exists( 'hit_core_add_parent_menu' ) ) {
    function hit_core_add_parent_menu() {
        $hit_plugins = hit_get_hit_plugins();
        if ( !hit_get_menu_item( 'hit_plugin_core' ) && count( $hit_plugins ) > 1 ) {
           add_menu_page( 'WordPress Plugins by High Impact Technologies', 'HiT Plugins', 'administrator', 'hit_plugin_core', 'hit_plugin_core_page', 'dashicons-admin-generic' );
        }
    }
    add_action( 'admin_menu', 'hit_core_add_parent_menu' );
}

if( !function_exists('hit_get_hit_plugins') ) {
    function hit_get_hit_plugins() {
        // Check if get_plugins() function exists. This is required on the front end of the
        // site, since it is in a file that is normally only loaded in the admin.
        if ( ! function_exists( 'get_plugins' ) ) {
        	require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $result = array();
        $active_plugins = array_intersect_key( get_plugins(), array_flip( get_option('active_plugins') ) );
        foreach ( $active_plugins as $name => $plugin ) {
            if ( false !== strpos( $plugin['Author'], 'High Impact Technologies' ) ) {
                $result[] = $plugin;
            }
        }
        return $result;
    }
}

if ( !function_exists( 'hit_get_menu_item' ) ) {
    function hit_get_menu_item( $handle, $sub = false ) {
        if ( !is_admin() || ( defined('DOING_AJAX') && DOING_AJAX ) ) {
            return false;
        }
        global $menu, $submenu;
        $check_menu = $sub ? $submenu : $menu;
        if ( empty( $check_menu ) ) {
            return false;
        }
        foreach ( $check_menu as $k => $item ) {
            if ( $sub ) {
                foreach ( $item as $sm ) {
                    if ( $handle == $sm[2] ) {
                        return true;
                    }
                }
            } else {
                if ( $handle == $item[2] ) {
                    return true;
                }
            }
        }
        return false;
    }
}

if ( ! function_exists('hit_plugin_core_page') ) {
    function hit_plugin_core_page() {
        $hit_plugins = hit_get_hit_plugins();
?>
        <div class="wrap">
        	<h2><?php _e('Active Plugins by High Impact Technologies','hit-wp-ccpe'); ?>
        	    <span class="title-count theme-count"><?php echo count($hit_plugins); ?></span>
        	    <a href="<?php echo admin_url( 'plugin-install.php?tab=search&type=author&s=highimpacttech'); ?>" class="hide-if-no-js page-title-action"><?php _e('Add New','hit-wp-ccpe'); ?></a>
            </h2>
        	<?php foreach( $hit_plugins as $config => $plugin ) { ?>
        	<h3><?php echo $plugin['Name']; ?></h3>
        	<p><?php echo $plugin['Description']; ?></p>
        	<p>
            	<?php _e('Version','hit-wp-ccpe'); ?> <?php echo $plugin['Version']; ?> |
            	<?php _e('by','hit-wp-ccpe'); ?> <a href="<?php echo $plugin['AuthorURI']; ?>" target="_blank"><?php echo $plugin['Author']; ?></a> |
            	<a href="<?php echo $plugin['PluginURI']; ?>" target="_blank"><?php _e('Plugin Site', 'hit-wp-ccpe'); ?></a> |

        	    <a href="<?php echo admin_url( 'admin.php?page='.str_replace('-', '_', $plugin['TextDomain'])); ?>"><?php _e('Settings','hit-wp-ccpe'); ?></a>
            </p>
        	<?php } ?>
        </div>
<?php }
}
