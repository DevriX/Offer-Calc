jQuery(document).ready( function($) {	
	$("#post").submit(function(){
	  //alert("Submitted");
	});

	jQuery( ".wpd-mb-meta-multiple_textbox" ).each(function( i ) {
		
		jQuery(this).show();
		if ( i %2 != 0){
		   jQuery(this).after('<div class="offer-calc-meta-error offer-calc-error"></div>');
		}
	});	
	
	jQuery( document ).on( "click", "#publish", function() {
				
		var error = 'false';
		var error_wrap;
		
		jQuery(".wpd-mb-meta-repater-block").css('border', '1px solid #dfdfdf');
		jQuery('.offer-calc-meta-error').html('');
		jQuery('.offer-calc-price-error').html('');
		
		jQuery( ".wpd-mb-meta-multiple_textbox" ).each(function( j ) {
			
			var meta_val_to 	= jQuery(this).val();
			
			if( meta_val_to != '' && !isInteger(meta_val_to) ){
				
				jQuery(this).css('border', '1px solid red');
				
				jQuery(this).after('<div class="offer-calc-meta-error">Please Enter Numeric value</div>');
								
				error_wrap = jQuery(this).parent().closest(".wpd-mb-meta-repater-block").css('border', '1px solid red');
				
				error = 'true';
				
			} else {
				jQuery(this).css('border', 'none');
			}
			
		});
		
		jQuery( ".check_numeric_value" ).each(function( j ) {
			
			var meta_val_to 	= jQuery(this).val();
			
			if( meta_val_to != '' && !jQuery.isNumeric(meta_val_to) ){
				
				jQuery(this).css('border', '1px solid red');
				
				jQuery(this).after('<div class="offer-calc-price-error">Please Enter Numeric value</div>');
								
				error_wrap = jQuery(this).parent().closest(".wpd-mb-meta-repater-block").css('border', '1px solid red');
				
				error = 'true';
				
			} else {
				jQuery(this).css('border', 'none');
			}
			
		});
				
		if( error == 'true' ) {
			
			if ( jQuery(".wpd-mb-meta-repater-block").is(":visible") ) {
				
				jQuery('#offer_calc_meta').css('border', 'none');
					
			} else {
				jQuery('#offer_calc_meta').css('border', '1px solid red');
			}
			
			jQuery('html, body').animate({
		        scrollTop:error_wrap.offset().top
		    }, 500);
		    
			return false;
		}
	});
	
});

function isInteger( value ) {
	return /^[0-9]+$/.test(value);
}