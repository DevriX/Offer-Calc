<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Offer Calc Model Class
 *
 * Handles generic functionailties
 *
 * @package Offer Calc
 * @since 1.0.0
 */
 class Offer_Calc_Model {
 	 
 	public $version;
 	
	function __construct()	{		
		$this->version = get_option('ofc_version');
	}
	
	/**
	 * Get Offers
	 * 
	 * Handles get all offers from database
	 * 
	 * @package Offer Calc
	 * @since 1.0.0
	 */
	function offer_calc_get_offers($args=array()) {
		
		$query_args = array( 'post_type' => OFFER_CALC_POST_TYPE, 'post_status' => 'publish');
		
		//if search is called then retrive searching data
		if(isset($args['search'])) {
			$query_args['s'] = $args['search'];
		}
		
		//if sorting is called then retrived sorted data
		if(isset($args['meta_query'])) {
			$query_args['meta_query'] = $args['meta_query'];
		}
		
		//fire query in to table for retriving data
		$result = new WP_Query($query_args);
		
		//retrived data is in object format so assign that data to array for listing
		$postslist = $this->offer_calc_object_to_array($result->posts);
		
		return $postslist;
	}
	
	/**
	 * Convert Object To Array
	 *
	 * Converting Object Type Data To Array Type
	 * 
	 * @package Offer Calc
	 * @since 1.0.0
	 */
	function offer_calc_object_to_array($result) {
	    $array = array();
	    foreach ($result as $key=>$value) {
	        if (is_object($value)) {
	            $array[$key]=$this->offer_calc_object_to_array($value);
	        } else {
	        	$array[$key]=$value;
	        }
	    }
	   
	    return $array;
	} 
		
	/**
	  * Escape Tags & Slashes
	  *
	  * Handles escapping the slashes and tags
	  *
	  * @package Offer Calc
	  * @since 1.0.0
	  */
	   
	 function offer_calc_escape_attr($data){
	  
	 	return esc_attr(stripslashes($data));
	 }
	 
	 /**
	  * Stripslashes 
 	  * 
  	  * It will strip slashes from the content
	  *
	  * @package Offer Calc
	  * @since 1.0.0
	  */
	   
	function offer_calc_escape_slashes_deep($data = array(),$flag=false){
		//return stripslashes_deep($data);
		if($flag != true) {
			$data = $this->offer_calc_nohtml_kses($data);
		}
		$data = stripslashes_deep($data);
		return $data;
	}
	 	
	/**
	 * Strip Html Tags 
	 * 
	 * It will sanitize text input (strip html tags, and escape characters)
	 * 
	 * @package Offer Calc
	 * @since 1.0.0
	 */
	function offer_calc_nohtml_kses($data = array()) {
		
		
		if ( is_array($data) ) {
			
			$data = array_map(array($this,'offer_calc_nohtml_kses'), $data);
			
		} elseif ( is_string( $data ) ) {
			
			$data = wp_filter_nohtml_kses($data);
		}
		
		return $data;
	}

	/**
	 * Get Post
	 *
	 * get post according to slug and type from the database table.
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
	 */
	public function offer_calc_get_post( $slug='' ) { 
   
   		global $wpdb;		
   		
   		if($slug != ''){
			$args['name']=$slug;
   		}
		$args['post_type']=OFFER_CALC_POST_TYPE;
		$args['post_status']='publish';
			
		$offer_post = get_posts( $args,true );		
		return $offer_post;
	}
	
	/**
	 * migration
	 *
	 * migrate custom tables data to WP tables.
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
	 */
	public function offer_calc_migrate_data() { 
   		
   		global $wpdb;		
   		
   		//check if offercalc_offers table exist in DB
   		
   		$check_table = $this->offer_calc_check_table();
   		if( $check_table ){
   			   			
	   		$select_offer_qry = 'SELECT of.id, of.name as form_name, of.slug, fl.name, fl.price FROM offercalc_offers of, offercalc_fields fl WHERE of.slug=fl.offer_slug';
	   		$post_results = $wpdb->get_results( $select_offer_qry );
			$id = 0;			
			
			if(!empty($post_results)){
				foreach ( $post_results as $post_data ){
		  				$post_exist = $this->offer_calc_get_post( $post_data->slug );
											
						if(empty($post_exist)){
			  				$post_meta_arr[$post_data->form_name][] = array(
											  								'name'     => $post_data->name, 
											  								'price'    => $post_data->price,
											  								);
							$post_slug_arr[$post_data->form_name] = $post_data->slug;		
						}
		  		}			  	
		  		if( !empty($post_meta_arr) ){
			  		foreach ( $post_meta_arr as $post_name => $post_meta ){  			
			  				$post_arr =	 array(
			  								'post_title'     => $post_name,
			  								'post_name'      => $post_slug_arr[$post_name],
			  								'post_type'      => OFFER_CALC_POST_TYPE,
			  								'post_status'    =>	'publish', 	  								
							);
													
						  	$post_id = wp_insert_post($post_arr, true );
						  	$meta_id = update_post_meta($post_id, OFFER_CALC_META_PREFIX.'service_fields', $post_meta, '');
			  		}
		  		} 
					
			}
   		}
   		//Update version		
		if( get_option( 'ofc_version') < 1 ) {
			update_option( 'ofc_version', 1 );
			update_option( 'ofc_pro_notice', 1 );	
			update_option( 'ofc_migration_done', false );				
		}		
	}
	
	/**
	 * migration
	 *  
	 * delete custom tables.
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
	 */
	public function offer_calc_delete_data(){	
		global $wpdb;	
			
		$drop_sql1 = "DROP TABLE IF EXISTS offercalc_offers;";
		$drop_result = $wpdb->query($drop_sql1);
		$drop_sql2 = "DROP TABLE IF EXISTS offercalc_fields;";
		$drop_result = $wpdb->query($drop_sql2);
		
		//Update version		
		if(get_option('ofc_migration_done') == false) {
			update_option('ofc_migration_done', true);		
		}
	}	
	
	/**
	 * 
	 * Check old tables are available?
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
	 */
	public function offer_calc_check_table(){	
		global $wpdb;	
			
		$check_tableqry = "SELECT * 
						FROM information_schema.tables
						WHERE table_schema = '".DB_NAME."' 
						    AND table_name = 'offercalc_offers' 
						LIMIT 1;";
   		
   		$table_exist = $wpdb->get_results( $check_tableqry );
   		if(!empty($table_exist)){
   			return true;   			
   		}else{
   			return false;
   		}
	}
	/**
	 * Get Offers count
	 *
	 * @package Offer Calc
 	 * @since 1.0.0
	 */
	public function offer_calc_dashboard_widget_count(  ) { 
   
   		global $wpdb;		
   		$count_qry = "SELECT COUNT(ID) FROM ".$wpdb->posts." WHERE post_type='offer-calc' and post_status='publish'";   		
   		$post_count = $wpdb->get_var( $count_qry );
   		return $post_count;
	}
	
 }
?>