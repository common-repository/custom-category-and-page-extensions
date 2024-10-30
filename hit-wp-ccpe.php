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

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	die( 'Hi there! I\'m just a plugin and I don\'t do anything when accessed directly but die();' );
}

function ccpe_init() {
	add_filter( 'category_rewrite_rules', 'ccpe_category_rewrite', 3 );
	add_filter( 'category_link', 'ccpe_category_link', 1 );

	add_filter( 'page_rewrite_rules', 'ccpe_page_rewrite', 2 );
	add_filter( 'page_link', 'ccpe_page_link' );
	add_filter( 'post_rewrite_rules', 'ccpe_post_rewrite', 2 );
	add_filter( 'post_link', 'ccpe_post_link' );

	add_filter( 'user_trailingslashit', 'ccpe_no_page_slash', 1,2 );
}

$ccpe_extension_to_use = get_option( 'ccpe_extension_to_use' );

if ( empty( $ccpe_extension_to_use ) ) {
	update_option( 'ccpe_extension_to_use', 'html' );
	$ccpe_extension_to_use = get_option( 'ccpe_extension_to_use' );
}

function ccpe_category_rewrite( $rules ) {
	global $ccpe_extension_to_use;
	foreach ( $rules as $key => $value ) {
		$new_rules[str_replace( '/?', '.' . $ccpe_extension_to_use, $key )] = $value;
	}
	return $new_rules;
}

function ccpe_category_link( $link ) {
	global $ccpe_extension_to_use;
	return $link . '.' . $ccpe_extension_to_use;
}

function ccpe_page_rewrite( $rules ) {
	global $ccpe_extension_to_use;
	foreach ( $rules as $key => $value ) {
		$new_rules[str_replace( '/?', '.' . $ccpe_extension_to_use, $key )] = $value;
	}
	return $new_rules;
}

function ccpe_page_link( $link ) {
	// Don't add the " . extension" to the home page link
	if ( substr( $link, 0, -1 ) == get_site_url() ) {
		return $link;
	}
	global $ccpe_extension_to_use;
	return $link  .  '.' . $ccpe_extension_to_use;
}

function ccpe_post_rewrite( $rules ) {
	global $ccpe_extension_to_use;
	foreach ( $rules as $key => $value ) {
		$new_rules[str_replace( '/?', '.' . $ccpe_extension_to_use, $key )] = $value;
	}
	return $new_rules;
}

function ccpe_post_link( $link ) {
	global $ccpe_extension_to_use;
	return $link . '.' . $ccpe_extension_to_use;
}

function ccpe_no_page_slash( $string, $type ) {
	global $wp_rewrite, $ccpe_extension_to_use;
	if ( $wp_rewrite->using_permalinks() && true == $wp_rewrite->use_trailing_slashes ) {
		return untrailingslashit( $string );
	} else {
		return $string;
	}
}

ccpe_init();

// Core functions
require_once( dirname( __file__ ) . '/inc/hit-plugin-core.php' );

// Admin
require_once( dirname( __file__ ) . '/inc/ccpe-admin-interface.php' );
