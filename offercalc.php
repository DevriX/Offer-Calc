<?php
/*
 * Plugin Name: Offer Calc
 * Plugin URI: http://offercalc.com/
 * Description: Offer Calc is a simple plugin letting your site visitors to calculate your services at final cost.
 * Version: 1.2
 * Stable tag: 1.2
 * Author: DevriX
 * Author URI: http://devrix.com
 * License: GPL2
 
 Copyright 2015 DevriX (email : contact@devrix.com)
 
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

/**
 * Define Some needed predefined variables
 * 
 * @package Offer Calc
 * @since 0.8
 * 
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

if ( ! defined( 'OFFER_CALC_DIR' ) ) {
	define( 'OFFER_CALC_DIR', dirname( __FILE__ ) ); // plugin dir
}

if ( ! defined( 'OFFER_CALC_URL' ) ) {
	define( 'OFFER_CALC_URL', plugin_dir_url( __FILE__ ) ); // plugin url
}

if ( ! defined( 'OFFER_CALC_POST_TYPE' ) ) {
	define( 'OFFER_CALC_POST_TYPE', 'offer-calc' );
}

if( ! defined( 'OFFER_CALC_METABOX_DIR' ) ) {
	define( 'OFFER_CALC_METABOX_DIR', OFFER_CALC_DIR . '/includes/meta-boxes' ); // meta-boxes url
}

if( ! defined( 'OFFER_CALC_METABOX_URL' ) ) {
	define( 'OFFER_CALC_METABOX_URL', OFFER_CALC_URL . '/includes/meta-boxes' ); // meta-boxes url
}

if( ! defined( 'OFFER_CALC_ADMIN' ) ) {
	define( 'OFFER_CALC_ADMIN', OFFER_CALC_DIR . '/includes/admin' ); // plugin admin dir
}

if( ! defined( 'OFFER_CALC_META_PREFIX' ) ) {
	define( 'OFFER_CALC_META_PREFIX', '_offercalc_' ); // plugin meta prefix
}

if ( ! defined( 'OFFER_CALC_ADMIN_HELP_PAGE_URL' ) ) {
	define( 'OFFER_CALC_ADMIN_HELP_PAGE_URL', admin_url( 'edit.php?post_type=offer-calc&page=offer-calc-help' ) );
}

if ( ! defined( 'OFFER_CALC_PRO_SITE_URL' ) ) {
	define( 'OFFER_CALC_PRO_SITE_URL', 'http://offercalc.com?ref=offer-calc-plugin' ); // Offer Calc Pro version URL
}

/**
 * Load Text Domain
 * 
 * This gets the plugin ready for translation.
 * 
 * @package Offer Calc
 * @since 1.0.0
 * 
 */
function offer_calc_load_textdomain() {
	
	load_plugin_textdomain( 'offercalc', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
  
}

add_action( 'plugins_loaded', 'offer_calc_load_textdomain' );

/**
 * Plugin Activation hook
 * 
 * This hook will call when plugin will activate
 * 
 * @package Offer Calc
 * @since 1.0.0
 * 
 */	
		
register_activation_hook(  __FILE__, 'offer_calc_install' );

function offer_calc_install() {
	
	global $wpdb;
	
	update_option( 'ofc_pro_notice', 1 );
	$version = get_option( 'ofc_version' );
	if( ! $version ) {
		update_option( 'ofc_version', 1 );
		update_option( 'ofc_migration_done', true );
	}
	//register custom post type
	offer_calc_register_post_types();	
		
	//IMP Call of Function
	//Need to call when custom post type is being used in plugin
	flush_rewrite_rules();	
}

/**
 * Plugin Deactivation hook
 * 
 * This hook will call when plugin will deactivate
 * 
 * @package Offer Calc
 * @since 1.0.0
 * 
 */

register_deactivation_hook( __FILE__, 'offer_calc_uninstall' );

function offer_calc_uninstall() {
	
	global $wpdb;
	
}

/**
 * Includes Class Files
 * 
 * @package Offer Calc
 * @since 1.0.0
 */
global $offer_calc_model,$offer_calc_scripts,$offer_calc_admin,$offer_calc_shortcodes;

/**
 * Includes Class Files
 * 
 * @package Offer Calc
 * @since 1.0.0
 * 
 */

//includes model file
include_once( OFFER_CALC_DIR . '/includes/class-offer-calc-model.php');
$offer_calc_model = new Offer_Calc_Model;

//includes script file
include_once( OFFER_CALC_DIR . '/includes/class-offer-calc-scripts.php');
$offer_calc_scripts = new Offer_Calc_Scripts;
$offer_calc_scripts->add_hooks();

//includes post types file
include_once( OFFER_CALC_DIR . '/includes/dx-offer-calc-post-types.php');

//includes widget file
require_once ( OFFER_CALC_DIR . '/includes/widgets/class-offer-calc-widget.php');

//includes shortcode file
require_once ( OFFER_CALC_DIR . '/includes/class-offer-calc-shortcodes.php');
$offer_calc_shortcodes = new Offer_Calc_Shortcodes();
$offer_calc_shortcodes->add_hooks();

//include the main class file for metabox
require_once ( OFFER_CALC_METABOX_DIR . '/class-offer-calc-meta-box.php' );

add_action( 'init', 'offer_calc_loag_meta' );

function offer_calc_loag_meta() {
	//Metabox file to handle metaboxes
	include_once( OFFER_CALC_METABOX_DIR . '/offer-calc-meta-box.php' );
}

//includes admin pages
require_once( OFFER_CALC_ADMIN . '/class-offer-calc-admin.php');
$offer_calc_admin = new Offer_Calc_Admin_Pages();
$offer_calc_admin->add_hooks();

require_once ( OFFER_CALC_DIR . '/offer-calc-pro/offer-calc-pro.php' );
