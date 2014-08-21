<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Offer Calc Meta Box Validate Class
 *
 * Handles all the functions to validate meta box data.
 *
 * @package Offer Calc
 * @since 1.0.0
 */

if ( ! class_exists( 'Offer_Calc_Meta_Box_Validate' ) ) {

class Offer_Calc_Meta_Box_Validate {

	public $model;
	
	public function __construct() {
		
		global $offer_calc_model;
		
		$this->model = $offer_calc_model;
	}
	
	public function date_str_to_time($data){
		return strtotime($data);
    }
    
    public function escape_html($data){
	
    	return $this->model->offer_calc_escape_slashes_deep($data); 
    }
	
} // End Class

} // End Check Class Exists
?>