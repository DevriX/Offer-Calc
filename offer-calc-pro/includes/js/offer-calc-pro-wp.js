// JavaScript Document
jQuery(document).ready(function($) {
	$( '.offer-sum' ).val('0');
	$( '.offer_selector_shortcode' ).val('0');
	$( '.offer-total' ).val('0');
	
	$( ".offer_selector_shortcode" ).change(function() {
		var off_short_tot = 0;
		$( '#frm_offer_short .offer_sum' ).each(function() {
			off_short_tot = parseFloat(off_short_tot) + parseFloat($(this).val());
		});
		$( '#frm_offer_short .offer_total' ).val(off_short_tot);
	});
	
	$( ".offer_selector" ).change(function() {
		var off_wigdet_tot = 0;
		$( '#frm_offer_widget .offer_sum' ).each(function() {
			off_wigdet_tot = parseFloat(off_wigdet_tot) + parseFloat($(this).val());
		});
		$( '#frm_offer_widget .offer_total' ).val(off_wigdet_tot);
	});
	
	jQuery( ".submit-offer").click(function() {
	    
		var form_ofr = jQuery(this).parents('div.offercalc_wrapper').find('form.offer-form');
		
		if( offerValidate( form_ofr ) ){
			form_ofr.submit();
		}
		else{
			return false;
		}
	});
	
    //allow textbox to enter only 0-9 number
     $(".ofc_cnt_txt_shortcode, .ofc_cnt_txt_widget").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) || 
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });
    
    //shortcode calculation script textbox, textarea
	jQuery( ".ofc_cnt_txt_shortcode" ).blur(function() {
		var offer_count = jQuery(this).val();
		
		if( offer_count  && typeof(offer_count) !== 'undefined' ) {
			
			offer_count = jQuery(this).val();
			
		} else {
			
			offer_count = 0;
		}
		
		var offer_cls	= jQuery(this).closest('.offer_calc_price');	
		var offer_price = offer_cls.find( '.offer_calc_currency .price-span' ).text();	
		var total_input = offer_cls.find( '.offer_calc_orderlist .offer-sum' );
				
		var net_total_input = jQuery(this).parents('form.offer-form').children('div.total-offercalc-sum-div').children('span.total-offercalc-sum-span');
		
		if( offer_price.length !== undefined ) {
			offer_price = parseFloat(offer_price.replace(/[^0-9-.]/g, ''));
		}
		
		var row_sum = parseFloat(offer_price) * parseFloat(offer_count);
		total_input.val(row_sum.toFixed(2));
		
		var net_total = 0;
		jQuery(this).parents('form.offer-form').find('.offer-sum').each(function() {				
			net_total = parseFloat(net_total) + parseFloat(jQuery(this).val());			
		});

		/*  START For VAT calculation  */
		var form_cls 			= jQuery(this).parents('form.offer-form');
		var offer_enable_vat	= form_cls.find('.offer_enable_vat').val();		
		var vat_amount			= form_cls.find('.offer_vat_amount').val();
		
		if( offer_enable_vat !== null && offer_enable_vat !== undefined && vat_amount !== null && vat_amount !== undefined ) {
			
			var vat_sub_total	= net_total;
			var vat_amount		= ( net_total * vat_amount ) / 100;
									
			var form_cls = jQuery(this).parents('form.offer-form');
			form_cls.find('.offer-calc-total-text').html('');
			form_cls.find('.offer-calc-total-text').html('');
			form_cls.find('.offercalc_vat_net_sum').append('<span class="offer-calc-total-text">'+vat_sub_total.toFixed(2)+'</span>');
			form_cls.find('.offercalc_vat_sum').append('<span class="offer-calc-total-text">'+vat_amount.toFixed(2)+'</span>');
									
			form_cls.find('.offer_hidden_vat_amount').val(vat_amount);
			form_cls.find('.offer_hidden_vat_sub_total').val(vat_sub_total);
			form_cls.find('.offer_hidden_vat_enable').val(offer_enable_vat);
			
			net_total = net_total + vat_amount;
			
		} 
		/*  END For VAT calculation  */
		
		net_total_input.html(net_total.toFixed(2));
		jQuery(this).parents('form.offer-form').find('.offer_total' ).val(net_total);		
		
		//call trigger when change offer calc values
		jQuery( 'body' ).trigger( 'offer_calc_fields_change', [jQuery(this)] );
		
	});
	
	//shortcode calculation script for select,number,
	jQuery(".offer_selector_pro, .offer_selector_shortcode").change(function() {
		
		var offer_count = jQuery(this).val();
		var offer_cls	= jQuery(this).closest('.offer_calc_price');	
		var offer_price = offer_cls.find( '.offer_calc_currency .price-span' ).text();	
		var total_input = offer_cls.find( '.offer_calc_orderlist .offer-sum' );
		
		var net_total_input = jQuery(this).parents('form.offer-form').children('div.total-offercalc-sum-div').children('span.total-offercalc-sum-span');
		
		if( offer_price.length !== undefined ) {
			offer_price = parseFloat(offer_price.replace(/[^0-9-.]/g, ''));
		}
		
		var row_sum = parseFloat(offer_price) * parseFloat(offer_count);
		total_input.val(row_sum.toFixed(2));
		
		var net_total = 0;
		jQuery(this).parents('form.offer-form').find('.offer-sum').each(function() {				
			net_total = parseFloat(net_total) + parseFloat(jQuery(this).val());			
		});
		
		/*  START For VAT calculation  */
		var form_cls 			= jQuery(this).parents('form.offer-form');
		var offer_enable_vat	= form_cls.find('.offer_enable_vat').val();		
		var vat_amount			= form_cls.find('.offer_vat_amount').val();
		
		if( offer_enable_vat !== null && offer_enable_vat !== undefined && vat_amount !== null && vat_amount !== undefined ) {
			
			var vat_sub_total	= net_total;
			var vat_amount		= ( net_total * vat_amount ) / 100;

			var form_cls = jQuery(this).parents('form.offer-form');			
			form_cls.find('.offer-calc-total-text').html('');
			form_cls.find('.offercalc_vat_net_sum').append('<span class="offer-calc-total-text">'+vat_sub_total.toFixed(2)+'</span>');
			form_cls.find('.offercalc_vat_sum').append('<span class="offer-calc-total-text">'+vat_amount.toFixed(2)+'</span>');
									
			form_cls.find('.offer_hidden_vat_amount').val(vat_amount);
			form_cls.find('.offer_hidden_vat_sub_total').val(vat_sub_total);
			form_cls.find('.offer_hidden_vat_enable').val(offer_enable_vat);
			
			net_total = net_total + vat_amount;
			
		} 
		/*  END For VAT calculation  */
		
		net_total_input.html(net_total.toFixed(2));
		jQuery(this).parents('form.offer-form').find('.offer_total' ).val(net_total);
		
		//call trigger when change offer calc values
		jQuery( 'body' ).trigger( 'offer_calc_fields_change', [jQuery(this)] );
	});
	
	//shortcode calculation script radio,checkbox,multiselect
	jQuery( ".ofc_cnt_chk_shortcode, .ofc_cnt_radio_shortcode, .offer_selector_multi_shortcode" ).click( function() {
		
		if( jQuery(this).attr('type') == 'radio' ){
			//var offer_price = 1;
			var offer_cls	= jQuery(this).closest('.offer_calc_price');	
			var offer_price = offer_cls.find( '.offer_calc_currency .price-span' ).text();	
			var offer_count = jQuery(this).val();
		}
		else if( jQuery(this).attr('type') == 'checkbox' ){
			if( jQuery( this ).prop( "checked" ) == true ){				
				var offer_cls	= jQuery(this).closest('.offer_calc_price');
				var offer_price = offer_cls.find( '.offer_calc_currency .price-span' ).text();
			}
			else{
				var offer_price = 0;
			}
			var offer_count = 1;
		}
		else if( jQuery(this).hasClass('multi') ){
			//var offer_price = 1;
			var offer_count = 0;			
			var offer_cls	= jQuery(this).closest('.offer_calc_price');
			var offer_price = offer_cls.find( '.offer_calc_currency .price-span' ).text();	
			var sum_id		= offer_cls.find( '.count-td .offer_selector_multi_shortcode' );
			var multi_val	= sum_id.val();
			
			jQuery.each( multi_val, function( key, value ) {
				offer_count = parseFloat(offer_count) + parseFloat(value);
			});
		}
		
		if( offer_count == '' ){offer_count = 0;}			
		if( offer_price == '' ){ offer_price = 0;}
		
		var offer_cls		= jQuery(this).closest('.offer_calc_price');
		var total_input 	= offer_cls.find( '.offer_calc_orderlist .offer-sum' );		
		var net_total_input = jQuery(this).parents('form.offer-form').children('div.total-offercalc-sum-div').children('span.total-offercalc-sum-span');

		if( offer_price.length !== undefined ) {
			offer_price = parseFloat(offer_price.replace(/[^0-9-.]/g, ''));
		}
			
		var row_sum = parseFloat(offer_price) * parseFloat(offer_count);
		total_input.val(row_sum.toFixed(2));
		
		var net_total = 0;
		jQuery(this).parents('form.offer-form').find('.offer-sum').each(function() {				
			net_total = parseFloat(net_total) + parseFloat(jQuery(this).val());	
		});
		
		/*  START For VAT calculation  */
		var form_cls 			= jQuery(this).parents('form.offer-form');
		var offer_enable_vat	= form_cls.find('.offer_enable_vat').val();		
		var vat_amount			= form_cls.find('.offer_vat_amount').val();
		
		if( offer_enable_vat !== null && offer_enable_vat !== undefined && vat_amount !== null && vat_amount !== undefined ) {
			
			var vat_sub_total	= net_total;
			var vat_amount		= ( net_total * vat_amount ) / 100;
									
			var form_cls = jQuery(this).parents('form.offer-form');			
			form_cls.find('.offer-calc-total-text').html('');
			form_cls.find('.offercalc_vat_net_sum').append('<span class="offer-calc-total-text">'+vat_sub_total.toFixed(2)+'</span>');
			form_cls.find('.offercalc_vat_sum').append('<span class="offer-calc-total-text">'+vat_amount.toFixed(2)+'</span>');
			
			form_cls.find('.offer_hidden_vat_amount').val(vat_amount);
			form_cls.find('.offer_hidden_vat_sub_total').val(vat_sub_total);
			form_cls.find('.offer_hidden_vat_enable').val(offer_enable_vat);
			
			net_total = net_total + vat_amount;
			
		} 
		/*  END For VAT calculation  */
		
		net_total_input.html(net_total.toFixed(2));
		jQuery(this).parents('form.offer-form').find('.offer_total' ).val(net_total);
		
		//call trigger when change offer calc values
		jQuery( 'body' ).trigger( 'offer_calc_fields_change', [jQuery(this)] );
	});	
	
	jQuery( ".offer-captcha-refresh" ).click( function() {
		var rand = Math.random()*1000;
		var img = jQuery(this).parents('form.offer-form').find('.ofc-captcha-img');		
		var post_id = jQuery(this).parents('form.offer-form').find('input.offer-id-hidden').val();		
		jQuery(this).parents('form.offer-form').find('.captcha_id').val(rand);
		
		var img_src = img.attr('src');
		img_src = img_src.substring(0,img_src.lastIndexOf("?"));
		img_src = img_src +"?offer_calc_captcha=true&ofc_id="+post_id+"&rand="+rand;
		img.attr('src', img_src);
	});
	
});

function offerValidate( frm_obj ){
	
	var valid_offer = 1;
	var tot = frm_obj.find('.offer_total').val();	
	var email = frm_obj.find('.offer_email input').val();
	var sub_value = frm_obj.find('.offer_submission_value').val();
	var captcha = frm_obj.find('#offer_captcha').val();
	
	if( tot <= 0 ){
				
		frm_obj.find('.err-total').show();
		valid_offer = 0;
		
	} else {
		frm_obj.find('.err-total').hide();		
	}
	
	// Condition for minimum submission offer value
	if( tot != 0 ){
	
		if (sub_value >= 0) {
			
			if( tot <= sub_value ){	
				
				frm_obj.find('.err-submission-value').show().html( "Total should be grater than " + sub_value + "." );
				valid_offer = 0;
			} else {
				
				frm_obj.find('.err-submission-value').hide();
			}
		}
	} else {
		frm_obj.find('.err-submission-value').hide();
	}
	
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	if( email != '' && ! regex.test(email) ){	
		frm_obj.find('.err-email').show();
		frm_obj.find('.err-empty-email').hide();
		valid_offer = 0;
	}
	else if( email == '')
	{
		frm_obj.find('.err-empty-email').show();
		frm_obj.find('.err-email').hide();
		valid_offer = 0;
	}
	else {
		frm_obj.find('.err-email').hide();
		frm_obj.find('.err-empty-email').hide();
	}
	
	if(captcha == '')
	{
		frm_obj.find('.err-captcha').show();
		frm_obj.find('.err-wrong-captcha').hide();
		valid_offer = 0;
	}
	else
	{
		frm_obj.find('.err-captcha').hide();
	}
	
	return valid_offer;
}