jQuery(document).ready( function($) {
	
	jQuery( ".wpd-mb-meta-multiple_textbox" ).keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) || 
             // Allow: home, end, left, right, down, up
            (e.keyCode >= 35 && e.keyCode <= 40)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
	
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
		jQuery('.offer-calc-price-error').html('');
		
		/*jQuery( ".wpd-mb-meta-multiple_textbox" ).each(function( j ) {
			
			var meta_val_to 	= jQuery(this).val();
			
			if( meta_val_to != '' && !isInteger(meta_val_to) ){
				
				jQuery(this).addClass("oc_error_box");
				
				jQuery(this).after('<div class="offer-calc-meta-error">Please Enter Numeric value.</div>');
								
				error_wrap = jQuery(this).parent().closest(".wpd-mb-meta-repater-block").addClass("oc_error_box");
				
				error = 'true';
				
			}
			else {
				
				jQuery(this).addClass("oc_error_none");
			}
		});*/
		
		
		jQuery('.offer-calc-meta-error').html('');
		
		jQuery( ".wpd-mb-meta-multiple_textbox:first-child" ).each(function( j ) {
			var meta_val_from = parseInt( jQuery(this).val() );
			var meta_val_to = parseInt( jQuery(this).next().val() );
			
			if ( jQuery(this).parent().closest(".wpd-mb-meta-repater-block").find(".replace_dropdown_check").attr("checked") != "checked" ) {
				if( meta_val_from != '' && meta_val_to != '' && meta_val_from > meta_val_to ) {
					jQuery(this).parent().closest("td").append('<div class="offer-calc-meta-error">From value must be less than To value.</div>');
					error_wrap = jQuery(this).parent().closest(".wpd-mb-meta-repater-block").addClass("oc_error_box");
					error = 'true';
				} else {
					jQuery(this).parent().closest(".wpd-mb-meta-repater-block").removeClass("oc_error_box");
				}
			}
			
		});
		
		jQuery( ".check_numeric_value" ).each(function( j ) {
			
			var meta_val_to 	= jQuery(this).val();
			
			if( meta_val_to != '' && !jQuery.isNumeric(meta_val_to) ){
				
				jQuery(this).addClass("oc_error_box");
				
				jQuery(this).after('<div class="offer-calc-price-error">Please Enter Numeric value.</div>');
								
				error_wrap = jQuery(this).parent().closest(".wpd-mb-meta-repater-block").addClass("oc_error_box");
				
				error = 'true';
				
			} else {
				jQuery(this).addClass("oc_error_none");
			}
			
		});
		
		// Offer Calc Email Address
		if ( jQuery( '#_offercalc_enable_email_offer' ).attr( 'checked' ) ) {
			var email_address = jQuery( '#_offercalc_email' ).val();
			if ( email_address == '' ) {
				jQuery('#_offercalc_email').after('<div class="offer-calc-price-error">Please, add an email address</div>');
				error_wrap = jQuery('#_offercalc_email').closest('td').addClass("oc_error_box");
				error = 'true';
			}
		}
			
		if( error == 'true' ) {
			
			if ( jQuery(".wpd-mb-meta-repater-block").is(":visible") ) {
				
				jQuery('#offer_calc_meta').addClass("oc_error_none");
					
			} else {
				jQuery('#offer_calc_meta').addClass("oc_error_box");
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