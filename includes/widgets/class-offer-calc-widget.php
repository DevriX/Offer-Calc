<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * Add function to widgets_init that'll load our widget.
 *
 * @package Offer Calc
 * @since 1.0.0
 */
add_action( 'widgets_init', 'offer_calc_load_widgets' );

/**
 * Register our widget.
 * 'Offer_Calc_Widget' is the widget class used below.
 * 
 * @package Offer Calc
 * @since 1.0.0
 */
function offer_calc_load_widgets() {
	
	register_widget('Offer_Calc_Widget');
}

class Offer_Calc_Widget extends WP_Widget {
	
	public $model,$shortcode;
	
	function Offer_Calc_Widget() {
		
		global $offer_calc_model, $offer_calc_shortcodes;
		
		$widget_ops		= array('classname' => 'offercalc_widget', 'description' => __('The Offer Calc Widget for calculation'));
		parent::WP_Widget(false, __('Offer Calc Widget', 'offercalc'), $widget_ops);
		$this->model		= $offer_calc_model;
		$this->shortcode	= $offer_calc_shortcodes;
	}

	public function widget( $args, $instance ) {

		extract( $args );
			
		echo $before_widget;
		echo $before_title;
		echo $after_title;
		echo $this->shortcode->offer_calc_shortcode( $instance, 'widget');
		echo $after_widget;
	}
	
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['offer_slug'] = strip_tags($new_instance['offer_slug']);
        return $instance;
    }
	
	function form( $instance ) {
		
		global $wpdb; 
		
		$offer_slug_arr = array();
		$args=array(	
			'post_type' => OFFER_CALC_POST_TYPE,
			'post_status' => 'publish',			
		);
				
		$offer_post_arr = $this->model->offer_calc_get_post();
		
		/* Set up some default widget settings. */
		$defaults = array( 'offer_slug' => 'Offer Slug');
		$instance = wp_parse_args( (array) $instance, $defaults ); 		
		
	?>
		<p>
			<label for="<?php echo $this->get_field_id( 'offer_slug' ); ?>"><?php _e('Offer form:','offercalc');?></label>
			<select  name="<?php echo $this->get_field_name( 'offer_slug' ); ?>">
				<option value=""><?php _e('--Select Offer Form--','offercalc');?></option>
				<?php					
					foreach ($offer_post_arr as $ofr_post){
						echo "<option value='$ofr_post->post_name' ".(($ofr_post->post_name == $instance['offer_slug']) ? 'selected="selected"' : '').">$ofr_post->post_title</option>";
					}
				?>
			</select>
	<?php 
	}
}
?>