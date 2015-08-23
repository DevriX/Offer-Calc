<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Define Some needed predefined variables
 * 
 * @package Offer Calc Pro
 * @since 1.0.0
 * 
 */
if( !defined( 'OFFER_CALC_PRO_DIR' ) ) {
	define( 'OFFER_CALC_PRO_DIR', dirname( __FILE__ ) ); // plugin dir
}
if(!defined('OFFER_CALC_PRO_URL')) {
	define('OFFER_CALC_PRO_URL',plugin_dir_url( __FILE__ ) ); // plugin url
}
if( !defined( 'OFFER_CALC_PRO_ADMIN' ) ) {
	define( 'OFFER_CALC_PRO_ADMIN', OFFER_CALC_PRO_DIR . '/includes/admin' ); // plugin admin dir
}
if( !defined( 'OFFER_CALC_PRO_META_PREFIX' ) ) {
	define( 'OFFER_CALC_PRO_META_PREFIX', '_offercalc_' ); // plugin meta prefix
}
if ( ! defined( 'OFFER_CALC_PRO_POST_TYPE' ) ) {
	define( 'OFFER_CALC_PRO_POST_TYPE', 'offer-calc' );
}
if( !defined( 'OFFER_CALC_AW_AUTH_URL' ) ) {
	define( 'OFFER_CALC_AW_AUTH_URL', 'https://auth.aweber.com/1.0/oauth/authorize_app/f49b1bcf' );
}
if( !defined( 'OFFER_CALC_MC_API_URL' ) ) {
	define( 'OFFER_CALC_MC_API_URL', 'http://admin.mailchimp.com/account/api' ); // plugin mailchimp app url
}
/**
 * Plugin Activation hook
 * 
 * This hook will call when plugin will activate
 * 
 * @package Offer Calc Pro
 * @since 1.0.0
 */
function offer_calc_pro_install() {
	
	global $wpdb;
}

//plugin activation hook
register_activation_hook( __FILE__, 'offer_calc_pro_install' );

/**
 * Load Text Domain
 * 
 * This gets the plugin ready for translation.
 * 
 * @package Offer Calc Pro
 * @since 1.0.0
 */
load_plugin_textdomain( 'offercalc', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		
/**
 * Plugin Loaded
 * 
 * Hnadle plugin loaded function
 * 
 * @package Offer Calc Pro
 * @since 1.0.0
 */
function offer_calc_pro_plugin_loaded() {
	
	if ( class_exists( 'Offer_Calc_Model' ) ) {
		
		/**
		 * Plugin Deactivation hook
		 * 
		 * This hook will call when plugin will deactivate
		 * 
		 * @package Offer Calc Pro
		 * @since 1.0.0
		 */
		function offer_calc_pro_uninstall() {
			global $wpdb;
		}
		
		/**
		 * Plugin default settings
		 *  
		 * @package Offer Calc Pro
		 * @since 1.0.0
		 */
		
		function offer_calc_default_settings() {
			
			$options = array(
								'global_enable_offer'		=>	'',
								'global_email_addr'			=>	'',
								'global_redirect_url'		=>	'',
								'global_offer_value'		=>	'',
								'global_enable_vat'			=>	'',
								'global_vat_amount'			=>	'',
								'admin_email_addr'			=>	'',
								'sender_name'				=>	'',
								'admin_email_subject'		=>	'',
								'admin_email_body'			=>	'',
								'user_email_subject'		=>	'',
								'user_email_body'			=>	''
							);
							
			update_option('offer_calc_options',$options);
		}
		
		//plugin deactivation hook
		register_deactivation_hook( __FILE__, 'offer_calc_pro_uninstall' );
		
		/**
		 * declare global vars
		 * 
		 * @package Offer Calc Pro
		 * @since 1.0.0
		 */
		global $offer_calc_pro_scripts, $offer_calc_pro_admin, $offer_calc_pro_public;
		
		/**
		 * Includes Class Files
		 * 
		 * @package Offer Calc Pro
		 * @since 1.0.0
		 */
		
		// Includind misc functions file
		include_once( OFFER_CALC_PRO_DIR . '/includes/offer-calc-misc-functions.php' );
		
		// Script Class to manage all scripts and styles
		include_once( OFFER_CALC_PRO_DIR . '/includes/class-offer-calc-pro-scripts.php' );
		$offer_calc_pro_scripts = new Offer_Calc_Pro_Scripts();
		$offer_calc_pro_scripts->add_hooks();
		
		//includes admin class
		require_once( OFFER_CALC_PRO_ADMIN . '/class-offer-calc-pro-admin.php');
		$offer_calc_pro_admin = new Offer_Calc_Pro_Admin_Pages();
		$offer_calc_pro_admin->add_hooks();
		
		//includes frontend class
		require_once( OFFER_CALC_PRO_DIR . '/includes/class-offer-calc-pro-public.php');
		$offer_calc_pro_public = new Offer_Calc_Pro_Public();
		$offer_calc_pro_public->add_hooks();
	}
}
// Add action to load plugin
add_action( 'plugins_loaded', 'offer_calc_pro_plugin_loaded', 9 );