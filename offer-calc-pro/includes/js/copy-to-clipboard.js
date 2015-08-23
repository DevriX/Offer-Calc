jQuery(document).ready(function() {				
	
	jQuery('a.copy').zclip({
	    path: OfferPro.flash_path,	       
	    copy: function () {	        	
	    	var id = jQuery(this).attr('id');        	
	        return jQuery('#p_'+id).text();
	    },
			afterCopy:function(){
	    	jQuery(this).text('Copied...');
	    	var copy_id = jQuery(this).attr('id');
	    	change_copy_btn_text( copy_id );   	      
		}
	});
	
	jQuery('a.copy').zclip({
	    path: OfferPro.flash_path,	       
	    copy: function () {	        	
	    	var id = jQuery(this).attr('id');        	
	        return jQuery('#p_'+id).text();
	    },
			afterCopy:function(){
	    	jQuery(this).text('Copied...');
	    	var copy_id = jQuery(this).attr('id');
	    	change_copy_btn_text( copy_id );   	      
		}
	}); 
	
	jQuery( document ).on( "change", ".replace_dropdown_check", function() {
		 if( jQuery(this).attr('checked') == 'checked' ){		 	
		 	jQuery(this).parents('tr').siblings( 'tr.cnt_type_options' ).show();
		 }
		 else{		 			 	
		 	jQuery(this).parents('tr').siblings( 'tr.cnt_type_options' ).hide();
		 	jQuery(this).parents('tr').siblings( 'tr.cnt_options' ).hide();
		 	jQuery(this).parents('tr').siblings( 'tr.cnt_type_options' ).children().each(function(){
		 		jQuery(this).children().each(function(){
		 			if( jQuery(this).hasClass('wpd-mb-meta-radio') ){
		 				jQuery(this).prop('checked', false);
		 			}		 			
		 		});
		    });		
		    jQuery(this).parents('tr').siblings( 'tr.cnt_options' ).children().each(function(){
		 		jQuery(this).children().each(function(){
		 			jQuery(this).children().each(function(){			 			
			 			if( jQuery(this).hasClass('wpd-mb-meta-custom_repeater') ){
			 				jQuery(this).val('');
			 			}	
		 			});	 			
		 		});
		    });	
		     	
		 }
	});
	
	jQuery( document ).on( "click", ".wpd-mb-meta-radio", function() {
//		   jQuery(this).parents('tr').siblings( 'tr.cnt_options' ).show();
//		   jQuery('.opt_radio, .opt_checkbox, .opt_text, .opt_textarea' ).hide();
		   
		   var opt = jQuery(this).val();
		  
		   if( opt == 1 ){
		   		jQuery(this).parents('tr').siblings( 'tr.cnt_options' ).hide();
		   		jQuery(this).parents('tr').siblings( 'tr.opt_radio' ).show();	
		   		
		   		jQuery(this).parents('tr').siblings( 'tr.opt_checkbox, tr.opt_text, tr.opt_textarea, tr.opt_select, tr.opt_multi_select, tr.opt_number' ).children().each(function(){
			 		jQuery(this).children().each(function(){			 			
			 			jQuery(this).children().each(function(){
			 				jQuery(this).val('');
			 			});		 			
			 		});
			    });				    
		   }
		   if( opt == 2 ){	
		   		jQuery(this).parents('tr').siblings( 'tr.cnt_options' ).hide();
		   		jQuery(this).parents('tr').siblings( 'tr.opt_checkbox' ).show();			   	
		   		
		   		jQuery(this).parents('tr').siblings( 'tr.opt_radio, tr.opt_text, tr.opt_textarea, tr.opt_select, tr.opt_multi_select, tr.opt_number' ).children().each(function(){
			 		jQuery(this).children().each(function(){			 			
			 			jQuery(this).children().each(function(){
			 				jQuery(this).val('');
			 			});		 			
			 		});
			    });	
		   }
		   if( opt == 3 ){	
		   		jQuery(this).parents('tr').siblings( 'tr.cnt_options' ).hide();
		   		jQuery(this).parents('tr').siblings( 'tr.opt_text' ).show();	
		   		
		   		jQuery(this).parents('tr').siblings( 'tr.opt_radio, tr.opt_checkbox, tr.opt_textarea, tr.opt_select, tr.opt_multi_select, tr.opt_number' ).children().each(function(){
			 		jQuery(this).children().each(function(){			 			
			 			jQuery(this).children().each(function(){
			 				jQuery(this).val('');
			 			});		 			
			 		});
			    });
		   }
		   if( opt == 4 ){	
		   		jQuery(this).parents('tr').siblings( 'tr.cnt_options' ).hide();
		   		jQuery(this).parents('tr').siblings( 'tr.opt_textarea' ).show();
		   		
		   		jQuery(this).parents('tr').siblings( 'tr.opt_radio, tr.opt_checkbox, tr.opt_text, tr.opt_select, tr.opt_multi_select, tr.opt_number' ).children().each(function(){
			 		jQuery(this).children().each(function(){			 			
			 			jQuery(this).children().each(function(){
			 				jQuery(this).val('');
			 			});		 			
			 		});
			    });
		   }
		   if( opt == 5 ){	
		   		jQuery(this).parents('tr').siblings( 'tr.cnt_options' ).hide();
		   		jQuery(this).parents('tr').siblings( 'tr.opt_select' ).show();
		   		
		   		jQuery(this).parents('tr').siblings( 'tr.opt_radio, tr.opt_checkbox, tr.opt_text, tr.opt_textarea, tr.opt_multi_select, tr.opt_number' ).children().each(function(){
			 		jQuery(this).children().each(function(){			 			
			 			jQuery(this).children().each(function(){
			 				jQuery(this).val('');
			 			});		 			
			 		});
			    });
		   }
		   if( opt == 6 ){	
		   		jQuery(this).parents('tr').siblings( 'tr.cnt_options' ).hide();
		   		jQuery(this).parents('tr').siblings( 'tr.opt_multi_select' ).show();
		   		
		   		jQuery(this).parents('tr').siblings( 'tr.opt_radio, tr.opt_checkbox, tr.opt_text, tr.opt_select, tr.opt_textarea, tr.opt_number' ).children().each(function(){
			 		jQuery(this).children().each(function(){			 			
			 			jQuery(this).children().each(function(){
			 				jQuery(this).val('');
			 			});		 			
			 		});
			    });
		   }
		   if( opt == 7 ){	
		   		jQuery(this).parents('tr').siblings( 'tr.cnt_options' ).hide();
		   		jQuery(this).parents('tr').siblings( 'tr.opt_number' ).show();
		   		
		   		jQuery(this).parents('tr').siblings( 'tr.opt_radio, tr.opt_checkbox, tr.opt_text, tr.opt_select, tr.opt_textarea, tr.opt_multi_select' ).children().each(function(){
			 		jQuery(this).children().each(function(){			 			
			 			jQuery(this).children().each(function(){
			 				jQuery(this).val('');
			 			});		 			
			 		});
			    });
		   }
	});
		
	jQuery( document ).on( "click", ".add-radio-reapeter, .add-multi-select-reapeter", function() {
		//var clone = $(".wpd-mb-meta-custom_repeater").clone(true).val("");
		var jQueryfirst = jQuery(this).parent().find('.count-type-opt-radio:last');
		jQueryfirst.clone().insertAfter(jQueryfirst).show();
     	jQuery(this).parent().find('.count-type-opt-radio:last .wpd-mb-meta-custom_repeater, .count-type-opt-radio:last .wpd-mb-meta-radio_price').val('');
      	return false;
		//jQuery(this).closest('.wpd-mb-meta-custom_repeater').clone(true).appendTo('.count-type-opt-radio').find('input').val('');
	});
	
	jQuery( document ).on( "click", ".remove-radio-pro", function() {
		
		var row = jQuery(this).parent().parent( '.type_opt' );
		var count =	row.find('.wpd-mb-meta-custom_repeater').length;
		
		if( count > 1 ) {
			jQuery(this).parent('.count-type-opt-radio').remove();
		} else {
			alert( 'Minimum one must be inserted' );
		}
		return false;
	});
   
	jQuery( document ).on( "click", ".add-multi-select-reapeter", function() {
		var jQueryfirst = jQuery(this).parent().find('.count-type-opt-multi-select:last');
		jQueryfirst.clone().insertAfter(jQueryfirst).show();
     	jQuery(this).parent().find('.count-type-opt-multi-select:last .wpd-mb-meta-select_repeater, .count-type-opt-multi-select:last .wpd-mb-meta-select_price').val('');
      	return false;
	});
	
	jQuery( document ).on( "click", ".remove-multi-select-pro", function() {
		
		var row = jQuery(this).parent().parent( '.type_opt' );
		var count =	row.find('.wpd-mb-meta-select_repeater').length;
		if( count > 1 ) {
			jQuery(this).parent('.count-type-opt-multi-select').remove();
		} else {
			alert( 'Minimum one must be inserted' );
		}
		return false;
	});
	
	jQuery( document ).on( "click", "", function() {
		jQuery(this).parent().remove();
	});
	
	jQuery( document ).on( "click", ".show-edit-header", function() {
		if( jQuery(this).attr('checked') == 'checked' ){		 	
			jQuery(this).parents('tr').siblings( 'tr.edit-header-opt' ).show();
		}
		else{
			jQuery(this).parents('tr').siblings( 'tr.edit-header-opt' ).hide();
			
			jQuery(this).parents('tr').siblings( 'tr.edit-header-opt' ).children().each(function(){					
		 		jQuery(this).children().each(function(){			
		 			if(jQuery(this).hasClass('regular-text')){		 				
		 				jQuery(this).val('');	
		 			} 			
		 		});
		    });
		}
		
	});
	
	if( jQuery('.show-edit-image').is(':checked') ) {
		
		jQuery('.edit_image_upload').closest('tr').show();
		//jQuery('.wpd-mb-meta-delete_image_button').closest('tr').show();
		
	} else {
		
		jQuery('.edit_image_upload').closest('tr').hide();
		//jQuery('.wpd-mb-meta-delete_image_button').closest('tr').hide();
	}
		
	jQuery( document ).on( "click", ".show-edit-image", function() {					
		
		if( jQuery(this).is(':checked') ) {
			
			//alert( jQuery('.wpd-mb-meta-upload_image_button').parents().parents('tr').html() );
			jQuery('.edit_image_upload').closest('tr').show();
			//jQuery('.wpd-mb-meta-delete_image_button').closest('tr').show();
			
		} else {
			
			jQuery('.edit_image_upload').closest('tr').hide();
			//jQuery('.wpd-mb-meta-delete_image_button').closest('tr').hide();
		}
		
	});			
	
	/*jQuery( document ).on( "click", ".replace_dropdown_check", function() {
		var inp = jQuery(this).parents('tr').find( '.wpd-mb-meta-multiple_textbox' );
		if( jQuery(this).attr('checked') == 'checked' ){	
			inp.parent().parent().hide();
			inp.val('');
		}
		else{
			inp.parent().parent().show();
		}
		
	});*/
			
	jQuery( document ).on( "click", ".offer-calc-pro-autores-type", function() {					
		
		var inp = jQuery(this).attr( 'aria-label' );
		
		jQuery('.offer-calc-pro-table').hide();
		jQuery('.offer-calc-pro-autores-type').removeClass('offer-calc-active-res');
		jQuery('.offer-calc-autores-radio').removeAttr('checked');
		
		if( typeof(inp) != 'undefined' && inp != '' && inp != null) {
			jQuery(this).addClass('offer-calc-active-res');			
			jQuery('.offer-calc-pro-'+inp+'-table').show();
			jQuery(this).find('.offer-calc-autores-radio').attr('checked', 'checked');
		}
		
	});
	
	/*jQuery( ".wpd-mb-meta-multiple_textbox" ).each(function( i ) {
	
		if ( i %2 != 0){
		   jQuery(this).after('<div class="offer-calc-meta-error offer-calc-error"></div>');
		}
	});	*/
	
	jQuery( document ).on( "click", "#add-_offercalc_service_fields", function() {
	
		if( jQuery('.show-edit-image').is(':checked') ) {
		
			jQuery('.edit_image_upload').closest('tr').show();
			//jQuery('.wpd-mb-meta-delete_image_button').closest('tr').show();
			
		} else {
			
			jQuery('.edit_image_upload').closest('tr').hide();
			//jQuery('.wpd-mb-meta-delete_image_button').closest('tr').hide();
		}
	});
	
	jQuery( document ).on( "click", "#publish", function() {

		jQuery('input').each(function(){
		   jQuery(this).val(jQuery.trim(jQuery(this).val()))
		})
			
		var error = 'false';
		var error_wrap;

		jQuery('.offer-calc-offer-price-error').html('');	
		jQuery( ".check_offer_value" ).each(function( j ) {
			
			var meta_val_to = jQuery(this).val();
			
			if( meta_val_to != '' && !jQuery.isNumeric(meta_val_to) ){
				
				jQuery(this).addClass("oc_error_box");
				
				jQuery(this).after('<div class="offer-calc-offer-price-error">Please Enter Numeric value.</div>');
								
				error_wrap = jQuery(this).addClass("oc_error_box");
				
				error = 'true';
				
			} else {
				jQuery(this).addClass("oc_error_none");
			}
			
		});
		
		//Email Validation on single edit offer page
		jQuery('.offer-calc-email-error').html('');	
		jQuery( "#_offercalc_email" ).each(function( j ) {
			
			var meta_val_to = jQuery(this).val();
			
			var myRegExp = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
			
			if (meta_val_to != '' && !myRegExp.test(meta_val_to)){
				
				jQuery(this).addClass("oc_error_box");
				
				jQuery(this).after('<div class="offer-calc-email-error">Please Enter Proper Email Address.</div>');
								
				error_wrap = jQuery(this).addClass("oc_error_box");
				
				error = 'true';
				
			} else {
				jQuery(this).addClass("oc_error_none");
			}
			
		});
		
		jQuery('.offer-calc-vat-amount-error').html('');	
		jQuery( ".check_vat_amount" ).each(function( j ) {
			
			var meta_val_to = jQuery(this).val();
			
			if( meta_val_to != '' && !jQuery.isNumeric(meta_val_to) ){
				
				jQuery(this).addClass("oc_error_box");
				
				jQuery(this).after('<div class="offer-calc-vat-amount-error">Please Enter Numeric value.</div>');
								
				error_wrap = jQuery(this).addClass("oc_error_box");
				
				error = 'true';
				
			} else if ( meta_val_to != '' && (meta_val_to > 100 || meta_val_to < 0) ) {
				
				jQuery(this).addClass("oc_error_box");
				
				jQuery(this).after('<div class="offer-calc-vat-amount-error">Please Enter Proper Number of Percentage</div>');
								
				error_wrap = jQuery(this).addClass("oc_error_box");
				
				error = 'true';
			}
			else {
				jQuery(this).addClass("oc_error_none");
			}
			
		});
		
		jQuery('.offer-calc-radio-error').html('');
		jQuery( ".wpd-mb-meta-radio_price" ).each(function( j ) {
			
			var meta_val_to 	= jQuery(this).val();
			
			if( meta_val_to != '' && !jQuery.isNumeric(meta_val_to) ){
				
				jQuery(this).addClass("oc_error_box");
				
				jQuery(this).after('<div class="offer-calc-radio-error">Please Enter Numeric value.</div>');
								
				error_wrap = jQuery(this).parent().closest(".wpd-mb-meta-repater-block").addClass("oc_error_box");
				
				error = 'true';
				
			} else {
				jQuery(this).removeClass("oc_error_box");
			}
			
		});
		
		jQuery('.offer-calc-multi-select-error').html('');
		jQuery( ".wpd-mb-meta-select_price" ).each(function( j ) {
			
			var meta_val_to 	= jQuery(this).val();
			
			if( meta_val_to != '' && !jQuery.isNumeric(meta_val_to) ){
				
				jQuery(this).addClass("oc_error_box");
				
				jQuery(this).after('<div class="offer-calc-multi-select-error">Please Enter Numeric value.</div>');
								
				error_wrap = jQuery(this).parent().closest(".wpd-mb-meta-repater-block").addClass("oc_error_box");
				
				error = 'true';
				
			} else {
				jQuery(this).addClass("oc_error_none");
			}
			
		});
		
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
	
	// call when click on save changes button on global settings
	jQuery( document ).on( "click", "#offer_calc_global_setting_save", function() {
		
		var error = 'false';
		
		jQuery('.offer-calc-offer-price-error').html('');	
		jQuery( ".check_global_offer_value" ).each(function( j ) {
			
			var meta_val_to = jQuery(this).val();
			
			if( meta_val_to != '' && !jQuery.isNumeric(meta_val_to) ){
				
				jQuery(this).addClass("oc_error_box");
				
				jQuery(this).after('<div class="offer-calc-offer-price-error">Please Enter Numeric value.</div>');
								
				error_wrap = jQuery(this).addClass("oc_error_box");
				
				error = 'true';
				
			} else {
				jQuery(this).addClass("oc_error_none");
			}
			
		});
		
		jQuery('.offer-calc-vat-amount-error').html('');	
		jQuery( ".check_global_vat_amount" ).each(function( j ) {
			
			var meta_val_to = jQuery(this).val();
			
			if( meta_val_to != '' && !jQuery.isNumeric(meta_val_to) ){
				
				jQuery(this).addClass("oc_error_box");
				
				jQuery(this).after('<div class="offer-calc-vat-amount-error">Please Enter Numeric value.</div>');
								
				error_wrap = jQuery(this).addClass("oc_error_box");
				
				error = 'true';
				
			} else if ( meta_val_to != '' && (meta_val_to > 100 || meta_val_to < 0) ) {
				
				jQuery(this).addClass("oc_error_box");
				
				jQuery(this).after('<div class="offer-calc-vat-amount-error">Please Enter Proper Number of Percentage</div>');
								
				error_wrap = jQuery(this).addClass("oc_error_box");
				
				error = 'true';
			}
			else {
				jQuery(this).addClass("oc_error_none");
			}
			
		});
		
		//Email Validation for global settings
		jQuery('.offer-calc-email-error').html('');	
		jQuery( "#offer_calc_global_email_addr" ).each(function( j ) {
			
			var meta_val_to = jQuery(this).val();
			
			var myRegExp = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
			
			if (meta_val_to != '' && !myRegExp.test(meta_val_to)){
				
				jQuery(this).addClass("oc_error_box");
				
				jQuery(this).after('<div class="offer-calc-email-error">Please Enter Proper Email Address.</div>');
								
				error_wrap = jQuery(this).addClass("oc_error_box");
				
				error = 'true';
				
			} else {
				jQuery(this).addClass("oc_error_none");
			}
			
		});
		
		//Email Validation for admin receipent email on global settings
		jQuery('.offer-calc-email-error-admin').html('');	
		jQuery( "#offer_calc_admin_email_addr" ).each(function( j ) {
			
			var meta_val_to = jQuery(this).val();
			
			var myRegExp = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i;
			
			if (meta_val_to != '' && !myRegExp.test(meta_val_to)){
				
				jQuery(this).addClass("oc_error_box");
				
				jQuery(this).after('<div class="offer-calc-email-error-admin">Please Enter Proper Email Address.</div>');
								
				error_wrap = jQuery(this).addClass("oc_error_box");
				
				error = 'true';
				
			} else {
				jQuery(this).addClass("oc_error_none");
			}
			
		});
		
		if( error == 'true' ) {
			
			if ( jQuery(".wpd-mb-meta-repater-block").is(":visible") ) {
				
				jQuery('#offer_calc_meta').addClass("oc_error_none");
					
			} else {
				jQuery('#offer_calc_meta').addClass("oc_error_box");
			}
			
			jQuery('html, body').animate({
		        scrollTop:error_wrap.offset().top
		    }, 500);
		    
		    jQuery('.wrong_setting').css('display', '');
			return false;
		}
		
	});
	
	//call on click reset options button from settings page
	jQuery( document ).on('click', '#offer_calc_reset_all_options', function() {
		var ans;
		ans = confirm('Click OK to reset all options. All settings will be lost!');
		
		if(ans){
			return true;
		} else {
			return false;
		}
	});
	
});

function change_copy_btn_text( id ){
	setTimeout(function() {	
		jQuery('#'+id).text('Copy to clipboard');
	}, 1000);   
}

function isInteger( value ) {
	return /^[0-9]+$/.test(value);
}