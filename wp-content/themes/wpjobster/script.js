/* PACKAGES */
function change_color( $this ) {
	$('.sn-packages .active').removeClass('active');

	this_index = $this.index();
	col = $this.parents('tbody').siblings('thead').children('tr').children('th:nth('+this_index+')').index();
	$("td").filter(":nth-child(" + (col + 1) + ")").addClass("active");
}

function package_selection( $this ) {
	$(".packages-sidebar.selected").each(function(){
		$(this).removeClass('selected');
		$(this).find('.pck-sidebar-select-package').removeClass('right').removeClass('labeled');
		$(this).find('.pck-sidebar-select-package').children('i.checkmark.icon').hide();
	});

	$this.parents('.packages-sidebar').addClass('selected');
	$this.addClass('right').addClass('labeled');
	$this.children('i.checkmark.icon').show();
}

jQuery(document).ready(function($) {
	$('.pck-sidebar-select-package').on('click', function(e) {
		var $this = $( this );
		var regex = /[+-]?\d+(\.\d+)?/g;
		var indx = parseFloat( $(this).attr('class').match( regex ) );

		$( '.pck-order' ).eq(indx).trigger( 'click' );

		package_selection( $this );

	});
});

jQuery(document).ready(function($) {
	$('.pck-order').on('click', function(e) {
		var $this = $( this );
		current_nth = $this.index();
		nth = current_nth+1;
		pck_to_select = current_nth-1;

		package_selection( $( '.'+pck_to_select ) );

		// Check the column
		$this.children( 'div' ).children( 'input' ).prop( 'checked', true );

		// Change column color
		change_color( $this );

		// Get float value
		var regex = /[+-]?\d+(\.\d+)?/g;

		// Get and update delivery time
		deliv_time_unformatted = $this.parent().prev( 'tr' ).prev( 'tr' ).prev( 'tr' ).children( 'td:nth-child( ' + nth + ' )' ).html();
		deliv_time = parseFloat( deliv_time_unformatted.match( regex ) );
		$( '.single-job-delivery-time' ).html( deliv_time_unformatted );
		$( '.pck_deliv_val' ).val( deliv_time );

		// Get and update the price
		price = $this.parent().prev( 'tr' ).children( 'td:nth-child( ' + nth + ' )' ).attr('data-price');
		price_unformatted = $this.parent().prev( 'tr' ).children( 'td:nth-child( ' + nth + ' )' ).html();
		$( '.main_amount_box span.extra-price-inside' ).html( price_unformatted );
		$( '.total' ).data( 'price', price );
		$( '.pck_price_val' ).val( price );

	});
});
/* END PACKAGES */

jQuery(window).ready(function($){
	$( '.pck-order' ).eq(1).trigger( 'click' );

	Number.prototype.formatMoney = function(c, d, t){
	var n = this,
		c = isNaN(c = Math.abs(c)) ? 2 : c,
		d = d == undefined ? "." : d,
		t = t == undefined ? "," : t,
		s = n < 0 ? "-" : "",
		i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "",
		j = (j = i.length) > 3 ? j % 3 : 0;
		return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
	};

	function isInt(value) {
		return !isNaN(value) && (function(x) { return (x | 0) === x; })(parseFloat(value))
	}

	function get_exchange_value(amount, from, to) {
		var from = from.toUpperCase();
		var to = to.toUpperCase();
		from = from.trim();
		to = to.trim();

		var data = null;
		$.ajax({
			async: false,
			url: script_vars.ajaxurl,
			data: {
				action: 'wpj_show_exchange_values_for_js',
			},
			dataType: "json",
			success: function(response){
				data = response;
			}
		});

		if ( !data ) {
			return;
		}
		var rate_from, rate_to;
		$.each(data.rates, function(index, element) {
			if ( index == from ) {
				rate_from = element;
			}
			if ( index == to ) {
				rate_to = element;
			}
		});

		if ( !rate_from || !rate_to ) {
			return amount;
		} else if ( from == to ) {
			return amount;
		} else {
			if ( from != 'USD' ) {
				dollars = amount / rate_from;
			} else {
				dollars = amount;
			}

			if ( to != 'USD' ) {
				ret = Math.round( rate_to * dollars, 2 );
			} else {
				ret = Math.round( dollars, 2) ;
			}

			return ret;
		}
	}

	function calculateArmorPaymentsFee(amount) {
		var fee;

		if (amount > 1000000) {
			fee = 6400 + ((amount-1000000)*0.0035);
		} else if (amount > 500000) {
			fee = 3900 + ((amount-500000)*0.005);
		} else if (amount > 50000) {
			fee = 525 + ((amount-50000)*0.0075);
		} else if (amount > 5000) {
			fee = 75 + ((amount-5000)*0.01);
		} else {
			fee = Math.max(10, amount*0.015);
		}

		return fee;
	}

	var updatePurchasePrice = function(purchase_type){
		if(typeof purchase_type == 'undefined'){
			purchase_type = 'job_purchase';
		}
		$(".total").each(function(){

			var $total = $(this);
			if( purchase_type == 'job_purchase'){
				var total = Number($total.data("price"));
			}else if(purchase_type == 'feature'){
				master_total = 0;
				s_total = 0;
				$(".featured_chk[type=checkbox]").each(function(){
					ob=$("#"+this.id);
					if($(this).is(":checked")){
						s_total = Number(ob.data("price"));
						master_total = master_total+s_total;
						ob.attr( "checked", "checked" );

					}else{
						ob.removeAttr("checked");
					}
				});
				var total = master_total;
			}

			var shipping = $total.data("shipping") || 0;
			var cur = $total.data("cur");
			var symbol = $total.data("symbol") || cur;
			var position = $total.data("position") || '';
			var space = $total.data("space") || 'yes';
			var decimal = $total.data("decimal") || '.';
			var thousands = $total.data("thousands") || ',';
			var decimaldisplay = $total.data("decimaldisplay") || 'default';
			var processingfeesenable = $total.data("processingfeesenable") || 'disabled';
			var processingfeesfixed = Number($total.data("processingfeesfixed")) || 0;
			var processingfeespercent = $total.data("processingfeespercent") || 0;
			var processingfeetax = $total.data("processingfeetax") || '';
			var tax = $total.data("tax") || 0;
			var zerowithfree = $total.data("zerowithfree") || '';
			var freestr = $total.data("freestr") || 'Free';
			var amount;

			shipping = Number(shipping);
			var space_str = "";
			if (space == "yes") {space_str = " ";}
			if( purchase_type == 'job_purchase' ){
				if ( Number($("[name=myFormPurchase2] .main_value_inp").val()) ) {
					total = total * Number($("[name=myFormPurchase2] .main_value_inp").val());
				}

				$("[name=myFormPurchase2] [type=checkbox]").each(function(){
					if($(this).is(":checked")) {
						amount = Number($(this).parent().children(".uzextracheck").val());
						if( isNaN( amount ) ){ amount = Number($total.data("price")); }

						total = total + amount * Number($(this).data("price"));
					}
				});
			}
			var processingfee_total = 0;
			if (processingfeesenable != 'disabled') {
				if (processingfeesenable == 'percent') {
					if (processingfeespercent > 0) {
						processingfee_total = (total + shipping) * processingfeespercent / 100;
					} else {
						processingfee_total = 0;
					}
				}
				if (processingfeesenable == 'fixed') {
					processingfee_total = processingfeesfixed;
				}
				// display
				var formatted_processingfee = String(processingfee_total.formatMoney(2, decimal, thousands));

				if (decimaldisplay == "ifneeded") {
					if (isInt(processingfee_total)) {
						formatted_processingfee = String(processingfee_total.formatMoney(0, decimal, thousands));
					} else {
						formatted_processingfee = String(processingfee_total.formatMoney(2, decimal, thousands));
					}
				} else if (decimaldisplay == "never") {
					formatted_processingfee = String(processingfee_total.formatMoney(0, decimal, thousands));
				}
				if (position == "front") {
					$(".processingfee-amount").html(symbol + space_str + formatted_processingfee);
				} else {
					$(".processingfee-amount").html(formatted_processingfee + space_str + symbol);
				}
			}

			chargable_total = total + Number(shipping);

			// processing fees only apply when price > 0
			if (chargable_total > 0) {
				chargable_total = chargable_total + Number(processingfee_total);
			}

			if (tax != '0') {
				if (processingfeetax == 'yes') {
					tax_chargable_total = total + processingfee_total + shipping;
				} else {
					tax_chargable_total = total + shipping;
				}
				tax_amount = tax_chargable_total*tax/100;

				// display
				var formatted_tax = String(tax_amount.formatMoney(2, decimal, thousands));

				if (decimaldisplay == "ifneeded") {
					if (isInt(tax_amount)) {
						formatted_tax = String(tax_amount.formatMoney(0, decimal, thousands));
					} else {
						formatted_tax = String(tax_amount.formatMoney(2, decimal, thousands));
					}
				} else if (decimaldisplay == "never") {
					formatted_tax = String(tax_amount.formatMoney(0, decimal, thousands));
				}else {
						formatted_tax = String(tax_amount.formatMoney(2, decimal, thousands));
					}

				if (position == "front") {
					$(".tax-amount").html(symbol + space_str + formatted_tax);
				} else {
					$(".tax-amount").html(formatted_tax + space_str + symbol);
				}
			} else {
				tax_amount=0;
			}

			// tax only applies when price > 0
			if (chargable_total > 0) {
				chargable_total = chargable_total + tax_amount;
			}

			// payoneer fees
			if ( script_vars.is_payoneer && $( "#payoneer_fees" ).length > 0 ) {
				if ( script_vars.current_currency == 'USD' ) {
					if ($(".sj-payoneer")[0]){
						chargable_total = chargable_total + calculateArmorPaymentsFee( chargable_total );

						var formatted_payoneerfee = String(calculateArmorPaymentsFee( chargable_total ).formatMoney(2, decimal, thousands));

						if (decimaldisplay == "ifneeded") {
							if (isInt(chargable_total)) {
								formatted_payoneerfee = String(calculateArmorPaymentsFee( chargable_total ).formatMoney(0, decimal, thousands));
							} else {
								formatted_payoneerfee = String(calculateArmorPaymentsFee( chargable_total ).formatMoney(2, decimal, thousands));
							}
						} else if (decimaldisplay == "never") {
							formatted_payoneerfee = String(calculateArmorPaymentsFee( chargable_total ).formatMoney(0, decimal, thousands));
						}
						if (position == "front") {
							$(".sj-payoneer").html(symbol + space_str + formatted_payoneerfee);
						} else {
							$(".sj-payoneer").html(formatted_payoneerfee + space_str + symbol);
						}
					}
				}
			}

			// total price inserted here
			if (decimaldisplay == "ifneeded") {
				if (isInt(chargable_total)) {
					formatted_money = String(chargable_total.formatMoney(0, decimal, thousands));
				} else {
					formatted_money = String(chargable_total.formatMoney(2, decimal, thousands));
				}
			} else if (decimaldisplay == "never") {
				formatted_money = String(chargable_total.formatMoney(0, decimal, thousands));
			} else {
				formatted_money = String(chargable_total.formatMoney(2, decimal, thousands));
			}
			if (position == "front") {
				if(total > 0) {
					$total.html(symbol + space_str + formatted_money);
				} else {
					if( zerowithfree === 'yes' ) {
						$total.html(freestr);
					} else {
						$total.html(symbol + space_str + formatted_money);
					}
				}
			} else {
				if(chargable_total > 0) {
					$total.html(formatted_money + space_str + symbol);
				} else {
					if( zerowithfree === 'yes' ) {
						$total.html(freestr);
					} else {
						$total.html(formatted_money + space_str + symbol);
					}
				}
			}
		});
	};

	$(".uzextracheck[type=checkbox]").change(function(){
		var ob=$("[name="+$(this).attr("name")+"]");
		if($(this).is(":checked")){
			ob.attr("checked","checked");
		}else{
			ob.removeAttr("checked");
		}

		updatePurchasePrice("job_purchase");
	});

	if( typeof $( ".featured_chk[type=checkbox]" ) != 'undefined' ){
		$(".featured_chk[type=checkbox]").change(function(){
			updatePurchasePrice("feature");
			return ;
		});
	}

	$('.pck-order, .pck-sidebar-select-package').on('click', function(e) {
		updatePurchasePrice();
	});

	$('.amount_add').on('click', function(e) {
		e.preventDefault();
		var amountnb = $(this).parent().attr('data-amountnb');
		var c_amount_chk = $(".extra_nb_"+amountnb+" label .uzextracheck");
		var c_amount_cont = $(".extra_nb_"+amountnb+" .amount_section .current_amount");
		var max = parseInt(c_amount_cont.data("max"));
		if($(this).parent().parent().hasClass("main_amount_box")) {
			var max = parseInt($(".main_amount_box .current_amount").data("max"));
			var c_amount = parseInt($(this).parent().children(".main_value_inp").val());
			if(c_amount<max){
				$(".main_value_inp").val(c_amount + 1);
				if ($(".chk_extrafast").length>0 && $('.chk_extrafast').is(':checked')){
					$(".chk_extrafast").val(c_amount + 1);
					$('.hid_extrafast').val(c_amount + 1);
				}
			}
			c_amount_cont = $(".main_amount_box .current_amount");
		}
		else {
			var ob=$("[name="+$(this).parent().parent().parent().children("div").children("[type=checkbox]").attr("name")+"]");
			ob.attr("checked","checked");

			var c_amount = parseInt(c_amount_chk.val());
			if(c_amount<max){
				c_amount_chk.val(c_amount + 1);
				$('.hid_extra'+amountnb).val(c_amount + 1);
			}
		}
		if(c_amount<max){
			c_amount_cont.html(c_amount + 1);
		}
		updatePurchasePrice();
	});

	$('.amount_rmv').on('click', function(e) {
		e.preventDefault();
		var amountnb = $(this).parent().attr('data-amountnb');
		var c_amount_chk = $(".extra_nb_"+amountnb+" label .uzextracheck");
		var c_amount_cont = $(".extra_nb_"+amountnb+" .amount_section .current_amount");
		if($(this).parent().parent().hasClass("main_amount_box")) {
			var c_amount = parseInt($(this).parent().children(".main_value_inp").val());
			if(c_amount>1) {
				$(".main_value_inp").val(c_amount - 1);
				if ($(".chk_extrafast").length>0 && $('.chk_extrafast').is(':checked')){
					$(".chk_extrafast").val(c_amount - 1);
					$('.hid_extrafast').val(c_amount - 1);
				}
			}
			c_amount_cont = $(".main_amount_box .current_amount");
		} else {
			var ob=$("[name="+$(this).parent().parent().parent().children("div").children("[type=checkbox]").attr("name")+"]");
			ob.attr("checked","checked");

			var c_amount = parseInt(c_amount_chk.val());
			if(c_amount>1)
				c_amount_chk.val(c_amount - 1);
		}
		if(c_amount>1){
			c_amount_cont.html(c_amount - 1);
			$('.hid_extra'+amountnb).val(c_amount - 1);
		}
		updatePurchasePrice();
	});

	$(".chk_extrafast").change(function(){
		var c_amount = parseInt($(".main_amount_box .main_value_inp").val());
		c_amount = isNaN(c_amount) ? 1 : c_amount;
		$(".chk_extrafast").val(c_amount);
		$('.hid_extrafast').val(c_amount);
	});

	$('.current_amount').on('click keyup keypress blur change', function(e) {
		var ob=$("[name="+$(this).parent().parent().parent().children("div").children("[type=checkbox]").attr("name")+"]");
		ob.attr("checked","checked");

		var amountnb = $(this).parent().attr('data-amountnb');
		$('.hid_extra'+amountnb).val( $(this).val() );
	});

	$(".current_amount").each(function(){
		$(this).on("change", function(){

			var amountnb = $(this).parent().attr('data-amountnb');
			var c_amount_cont = $(".extra_nb_"+amountnb+" .amount_section .current_amount");
			var c_amount_chk = $(".extra_nb_"+amountnb+" label .uzextracheck");

			if($(this).parent().parent().hasClass("main_amount_box")) {
				c_amount_cont = $(".main_amount_box .current_amount");
			}

			var max = parseInt(c_amount_cont.data("max"));

			if( parseInt( $(this ).val() ) > max ){

				$( this ).css( { border: '0 solid #ff0000' } ).fadeIn( 'slow' );

				$( this ).val( max );

				$( this ).animate ( { borderWidth: 1, borderColor: '#dddddd' }, 2000 );

			}

			c_amount_chk.val( $( this ).val() );

			updatePurchasePrice();

		});
	});

	$(".amount_section").click(function(e){
		e.preventDefault();
		e.stopPropagation();
	});

	$(window).load(function () {
		$(".extrali").each(function (ind) {
			var newamnt = $(".hid_extra"+parseInt(ind+1)).val();
			if(typeof newamnt == 'undefined'){ newamnt = 1; }
			$(".chk_extra"+parseInt(ind+1)).val(newamnt);
			$(this).find('.current_amount').html(newamnt);
		});
		$(".extralibot").each(function (ind) {
			var newamnt = $(".hid_extra"+parseInt(ind+1)).val();
			if(typeof newamnt == 'undefined'){ newamnt = 1; }
			$(".chk_extra"+parseInt(ind+1)).val(newamnt);
			$(this).find('.current_amount').html(newamnt);
		});

		var newamnt = $(".hid_extrarevision").val();
		if(typeof newamnt == 'undefined'){ newamnt = 1; }
		$(".chk_extrarevision").val(newamnt);
		$(".extra_nb_revision").find('.current_amount').html(newamnt);

		$(".main_amount_box .main_amount").html($("[name=myFormPurchase2] .main_amount_box .main_value_inp").val());
		updatePurchasePrice();
	});

	$(".the_content .show_more").click(function(){
		$(this).prev().css("max-height","auto");
		$(this).hide();
		return false;
	});

	$(".feedback .show_more").click(function(){
		if($(".visible_feedbacks_box").last().next().length==1){
			$(".visible_feedbacks_box").last().next().slideDown(300);
			$(".visible_feedbacks_box").last().next().addClass('visible_feedbacks_box');
			if(! $(".visible_feedbacks_box").last().next().length==1 ){
				$(this).slideUp(300);
			}
		}
		return false;
	});

	$(".the_content .show_more").each(function(){
		var h=$(this).prev().height();
		$(this).prev().css("max-height","auto");
		if(h==$(this).prev().height()){$(this).hide();}
		$(this).prev().css("max-height",h);
	});

	$("[data-submit]").click(function(){
		var ob=$(this).parents("form");
		if($(this).data("submit")){ob=$($(this).data("submit"));}
		ob.submit();
		return false;
	});

	//expand menu
	$('html').click(function() {
		$(".categories-top > li > ul").css("display","none");
	});

	$('.categories-top > li').click(function(event){
		if ($(".categories-top > li > ul").css("display") == "block") {
			$(".categories-top > li > ul").css("display","none");
			event.stopPropagation();
		}
		else {
			$(".categories-top > li > ul").css("display","block");
			event.stopPropagation();
		}
	});

	//expand notifications
	$('.nh-notifications .nh-link1').click(function(event){
		if ($(".nh-notifications-dropdown").css("display") == "block") {
			$(".nh-notifications-dropdown").css("display","none");
			event.stopPropagation();
		}
		else {
			$(".nh-notifications-dropdown").css("display","block");
			event.stopPropagation();
		}
	});

	$(document).mouseup(function (e){
		var notifications_container = $(".nh-notifications-dropdown");
		var notifications_handler = $(".nh-notifications");

		if (!notifications_container.is(e.target) // if the target of the click isn't the container...
			&& notifications_container.has(e.target).length === 0 // ... nor a descendant of the container
			&& !notifications_handler.is(e.target)
			&& notifications_handler.has(e.target).length === 0)
		{
			notifications_container.css("display","none");
		}
	});

	//delivery days slider
	$(function() {
		var slider_default = $("#slider-range-min").data("value");
		if ('undefined' == typeof slider_default) slider_default = script_vars.max_default_days;
		$( "#slider-range-min" ).slider({
			range: "min",
			value: slider_default,
			min: 1,
			max: script_vars.max_default_days,
			slide: function( event, ui ) {
				$( "#amount" ).val( ui.value );
				$( "#amount-label" ).html( ui.value == 1 ? script_vars.day : script_vars.days );
			}
		});
		$( "#amount" ).val( $( "#slider-range-min" ).slider( "value" ) );
	});

	//sortable thumbnails
	$( "#thumbnails" ).sortable({
		connectWith: '#thumbnails',
		start: function(event, ui) {
		},
		change: function(event, ui) {
		},
		update: function(event, ui) {
			var images_order = $( "#thumbnails" ).sortable('toArray', { attribute: 'image_id' });
			$( "#images_order" ).val( images_order );
		}
	});

	$( "#thumbnails div" ).disableSelection();

	$.fn.myFunction = function(){

		jQuery(function(){
			$('.ui.dropdown').dropdown();
		});

		$('select.styledselect').each(function(){
			var title = $(this).children(":first").text();
			if( $('option:selected', this).val() != ''  ) title = $('option:selected',this).text();
			$(this).css({'z-index':10,'opacity':0,'-khtml-appearance':'none'});

			$(this).parent().children('span.styledselect').remove();

			$(this).after('<span class="styledselect">' + title + '</span>');

			$(this).change(function() {
				val = $('option:selected', this).text();
				$(this).next().text(val);
			});

			$(this).focus(function () {
				$(this).next().addClass('focus');
			}).blur(function () {
				$(this).next().removeClass('focus');
			});
		});

		$('select.styledselectblack').each(function(){
			var title = $(this).children(":first").text();
			if( $('option:selected', this).val() != ''  ) title = $('option:selected',this).text();
			$(this).css({'z-index':10,'opacity':0,'-khtml-appearance':'none'});

			$(this).parent().children('span.styledselectblack').remove();

			$(this).after('<span class="styledselectblack">' + title + '</span>');

			$(this).change(function(){
				val = $('option:selected',this).text();
				$(this).next().text(val);
			})
		});
	}
	$.fn.myFunction();

	// cool message input
	$(".cmi-listen").focus(function() {
		$(this).parents(".cool-message-input").addClass("focus");
	}).blur(function(){
		$(this).parents(".cool-message-input").removeClass("focus");
	});

	$.fn.requestFunctions = function(){
		// public request errors
		$('html').on('click', function() {
			$(".request-error-container").slideUp(400);
		});


		$('html').on('click', '.request-error', function(event){

			if ($(this).siblings(".request-error-container").css("display") == "inline-block") {
				$(this).siblings(".request-error-container").slideUp(400);
				event.stopPropagation();
			}
			else {
				$(this).siblings(".request-error-container").slideDown(400);
				event.stopPropagation();
			}

		});

		$('html').on('click', '.request-view-more-link', function(event){
			var requestid = $(this).data('requestid');
			$('#request-' + requestid + ' .request-right-view-more').slideToggle(400);
			$('#request-' + requestid + ' .request-content-view-more').slideToggle(400);
			$('#request-' + requestid + ' .request-content-title').slideToggle(400);

			if ($('#request-' + requestid + ' .request-content-view-more').css("display") != "none") {
				$('#request-' + requestid + ' .request-map').each(function(){
					var embed ="<iframe class='request-google-map' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='https://maps.google.com/maps?&amp;q="+ encodeURIComponent($(this).data('address')) +"&amp;output=embed'></iframe>";
					$(this).html(embed);
				});
			}
		});
	}

	$.fn.requestFunctions();

	if ( script_vars.is_rtl == 'true' ) {
		var is_rtl = true;
	} else {
		var is_rtl = false;
	}

	$('.wpj-carousel').owlCarousel({
		rtl: is_rtl,
		loop:false,
		margin:14,
		nav:true,
		dots:false,
		navText:['',''],
		lazyLoad: true,
		video: true,
		URLhashListener: true,
		startPosition: 'URLHash',
		responsive:{
			0:{
				items:1
			}
		}
	});

	$('.news-carousel').owlCarousel({
		rtl: is_rtl,
		loop:false,
		margin:14,
		nav:true,
		dots:false,
		navText:['<','>'],
		responsive:{
			0:{
				items:1
			},
			480:{
				items:2
			}
		}
	});

	if (Modernizr.touch){
		$('body').addClass('touch');
	} else {
		$('body').addClass('no-touch');
	}

	/*** info dialog ***/
	jQuery(function($) {
		$('.send-custom-err-link').each(function() {
			$.data(this, 'dialog',
				$(this).next('.send-custom-err-container').dialog({
					autoOpen: false,
					width: 500,
					resizable: false,
					draggable: false,
					modal: true,
					dialogClass: "send-custom-err"
				})
			);
			$('.send-custom-err-container').bind('DOMSubtreeModified',function(){

				if ($('.send-custom-err-container').height() > $(window).height()) {
					$('.send-custom-err-container').dialog( "option", "position", {
						my: "center top",
						at: "center top",
						of: window
					});
				} else {
					$('.send-custom-err-container').dialog( "option", "position", {
						my: "center center",
						at: "center center",
						of: window
					});
				}
			});
		}).click(function() {
			$.data(this, 'dialog').dialog('open');
			return false;
		});
	});

	/*** embed dialog ***/
	$.fn.embedDialogInit = function(){
		$.fn.eachEmbedDialogInit = function( uzid ){
			$('.embed' + uzid).dialog({
				autoOpen: false,
				width: 600,
				resizable: false,
				draggable: false,
				modal: true
			});

			$('.embed' + uzid).bind('DOMSubtreeModified',function(){

				if ($('.embed' + uzid).height() > $(window).height()) {
					$('.embed' + uzid).dialog( "option", "position", {
						my: "center top",
						at: "center top",
						of: window
					});
				} else {
					$('.embed' + uzid).dialog( "option", "position", {
						my: "center center",
						at: "center center",
						of: window
					});
				}
			});
		};

		$(".embed-root").each(function(){

			var uzid = $(this).data("uzid");
			$.fn.eachEmbedDialogInit( uzid );

			/* open */
			$( document ).on('click', '.link' + uzid, function( event ){
				event.preventDefault();
				$('.embed' + uzid).dialog('open');
			});


			/* close */
			$( document ).on('click', '.embed' + uzid + ' .cancel', function(){
				$(this).closest('.embed' + uzid).dialog('close');
			});

			$( document ).on('click', '.ui-widget-overlay', function( event ){
				event.preventDefault();
				$('.embed' + uzid).dialog('close');
			});

		});
	};
	$.fn.embedDialogInit();


	/* menu accordions */
	$(".nh-accordion-handler").click(function(event) {
		event.preventDefault();
		$(".nh-accordion").slideUp(150);
		$(".nh-accordion-handler").removeClass("nh-accordion-selected");

		if ($(this).parent().children(".nh-accordion").css("display") == "block") {
			$(this).parent().children(".nh-accordion").slideUp(150);
			$(this).removeClass("nh-accordion-selected");
			event.stopPropagation();
		}
		else {
			$(this).parent().children(".nh-accordion").slideDown(150);
			$(this).addClass("nh-accordion-selected");
			event.stopPropagation();
		}
	});

	$(".uz-ui-demo .uz-btn").click(function(event) {
		event.preventDefault();
		var tabid = $(this).data("id");

		$(".uz-btns-container .uz-ui-demo").removeClass("uz-btn-active");
		$(this).parent(".uz-ui-demo").addClass("uz-btn-active");

		$(".uz-tabs-container .uz-tab").removeClass("uz-tab-active");
		$(".uz-tabs-container #uz-tab" + tabid).addClass("uz-tab-active");
	});

	var biggestHeight = "0";
	$(".auto-absolute-height div").each(function(){
		if ($(this).height() > biggestHeight ) {
			biggestHeight = $(this).height()
		}
	});

	$(".auto-absolute-height").height(biggestHeight);

	// stop propagation for location inputs
	$('#location_input').keydown(function(event){
		if((event.keyCode == 13)) {
			  event.stopPropagation();
			  return false;
		}
	});

	$('#request_location_input').keydown(function(event){
		if((event.keyCode == 13)) {
			  event.stopPropagation();
			  return false;
		}
	});

	// expand chart graph
	$('.graph-link').click(function() {
		if ($(this).hasClass('open')) {
			$(this).removeClass('open');
			$('#chart-div-container').slideUp(400);
			$('#chart_div').slideUp(400);
		} else {
			$(this).addClass('open');
			$('#chart-div-container').slideDown(400);
			$('#chart_div').slideDown(400);
			setTimeout(function(){
				drawChart();
			}, 500);
		}
	});

	// disable mousewheel on a input number field when not in focus
	// (to prevent Cromium browsers change the value when scrolling)
	$('form').on('focus', 'input[type=number]', function (e) {
		$(this).on('mousewheel.disableScroll', function (e) {
			// e.preventDefault()
		})
	})
	$('form').on('blur', 'input[type=number]', function (e) {
		$(this).off('mousewheel.disableScroll')
	})

	// new universal load more function
	$('.wpj-load-more').unbind().click(function(event){
		var button = this;
		var max_num_pages = Number( $(button).attr('data-max') );
		var action = $(button).attr('data-action');
		var page = Number( $(button).attr('data-page') );
		if ( isNaN(page) ) {
				page = 2;
		}
		var initial = Number( $(button).attr('data-initial') );
		var clicks = Number( $(button).attr('data-clicks') );
		var query_type = $(button).attr('data-querytype');
		var query_status = $(button).attr('data-querystatus');
		var function_name = $(button).attr('data-functionname');

		$(button).addClass('loading');
		$(button).attr('data-page', page + 1);
		$(button).attr('data-initial', initial + 1);

		var data = {
			'action': action,
			'page': page,
			'initial': initial,
			'query_type': query_type,
			'query_status': query_status,
			'function_name': function_name,
		};

		$.post(ajaxurl, data, function(response){
			if(initial==clicks){
				$('#wpjobster-query-load-more-button').hide();
			}

			if( query_type == 'request' || query_type == 'user_profile' || query_type == 'homepage' ) {
				$(response).hide().appendTo($(button).siblings(".wpj-load-more-target")).slideDown(400);
			} else {
				$(response).hide().appendTo( $(button).siblings("div").children("div").children(".wpj-load-more-target") ).slideDown(400);
			}

			$(button).removeClass('loading');
			if ( page >= max_num_pages || response == '' ) {
				$(button).slideUp(400);
			}

			$.fn.offerModalInit();
			if (typeof jQuery.fn.echoLazyLoadRender == 'function') {
				jQuery.fn.echoLazyLoadRender();
			}
		});
	});

});

// end document ready function

//charscounter
(function($) {
	$.fn.counted = function(options) {
		var settings = {
			count: 300
		};
		options = $.extend(settings, options || {});
		return this.each(function() {
			var $this = $(this);
			var counter = $('<span class="charscounter" />').html(options.count).insertAfter($this);
			function wpjobster_count_and_insert($this) {
				var count = options.count - $this.val().length;
				if (count >= 0) {
					counter.html(count);
				} else {
					$this.val($this.val().substr(0, options.count));
					counter.html(0);
				}
			}
			$this.on("ready change paste keyup blur", function(e) {
				wpjobster_count_and_insert($this);
			});
			$this.ready(function(){
				wpjobster_count_and_insert($this);
			});
		});
	};
})(jQuery);

jQuery(function($) {
	$('.counted1200').counted({count: 1200});
	$('.counted1000').counted({count: 1000});
	$('.counted500').counted({count: 500});
	$('.counted350').counted({count: 350});
	$('.counted200').counted({count: 200});
	$('.counted80').counted({count: 80});
	$('.counted65').counted({count: 65});
	$('.counted50').counted({count: 50});
});

// maps functions
function initialize() {
	if (jQuery('#location_input').length) {
		var input = document.getElementById("location_input");
		var lat = document.getElementById("lat");
		var long = document.getElementById("long");
		var autocomplete = new google.maps.places.Autocomplete(input);
		autocomplete.setTypes(['geocode']);
		google.maps.event.addListener(autocomplete, 'place_changed', function() {
			var place = autocomplete.getPlace();
			if (place.geometry) {
				lat.value = place.geometry.location.lat();
				long.value = place.geometry.location.lng();
			}
		});
	}

	if (jQuery('#request_location_input').length) {
		var request_input = document.getElementById("request_location_input");
		var request_lat = document.getElementById("request_lat");
		var request_long = document.getElementById("request_long");
		var request_autocomplete = new google.maps.places.Autocomplete(request_input);
		request_autocomplete.setTypes(['geocode']);
		google.maps.event.addListener(request_autocomplete, 'place_changed', function() {
			var request_place = request_autocomplete.getPlace();
			if (request_place.geometry) {
				request_lat.value = request_place.geometry.location.lat();
				request_long.value = request_place.geometry.location.lng();
			}
		});
	}

	if (jQuery('#locationInp').length) {
		var user_input = document.getElementById("locationInp");
		var user_lat = document.getElementById("user_lat");
		var user_long = document.getElementById("user_long");
		var user_autocomplete = new google.maps.places.Autocomplete(user_input);
		user_autocomplete.setTypes(['geocode']);
		google.maps.event.addListener(user_autocomplete, 'place_changed', function() {
			var user_place = user_autocomplete.getPlace();
			if (user_place.geometry) {
				user_lat.value = user_place.geometry.location.lat();
				user_long.value = user_place.geometry.location.lng();
			}
		});
	}
}
if (typeof google !== 'undefined') {
	google.maps.event.addDomListener(window, 'load', initialize);
}

//on submit check
jQuery(document).ready(function() {
	jQuery("form").submit(function(e) {
		if(jQuery(this).find("#location_input").length>0 && jQuery(this).find("#location_input").val()!="" && jQuery(this).find("#lat").val()=='' && jQuery(this).find("#long").val()=='' ){
			e.preventDefault();
			jQuery(this).find("#location_input").val("");
			jQuery(this).find("#location_input").attr('placeholder',jQuery(this).find("#location_input").data("replaceplaceholder"));
		}
		if(jQuery(this).find("#location_input").length>0 && jQuery(this).find("#location_input").val()==""){
			jQuery(this).find("#lat").val("");
			jQuery(this).find("#long").val("");
		}
		if(jQuery(this).find("#location_input_radius").length>0 && jQuery(this).find("#location_input_radius").val()!="" && (isNaN(jQuery(this).find("#location_input_radius").val()) || jQuery(this).find("#location_input_radius").val()<=0)){
			e.preventDefault();
			jQuery(this).find("#location_input_radius").val("");
			jQuery(this).find("#location_input_radius").attr('placeholder',jQuery(this).find("#location_input_radius").data("replaceplaceholder"));
		}
		if(jQuery(this).find("#location_input_radius").length>0 && jQuery(this).find("#location_input_radius").val()!="" && jQuery(this).find("#location_input").val()==""){
			e.preventDefault();
			jQuery(this).find("#location_input_radius").val("");
			jQuery(this).find("#location_input_radius").attr('placeholder',jQuery(this).find("#location_input_radius").data("replaceplaceholder2"));
		}
		if(jQuery(this).find("#request_location_input").length>0 && jQuery(this).find("#request_location_input").val()!="" && jQuery(this).find("#request_lat").val()=='' && jQuery(this).find("#request_long").val()=='' ){
			e.preventDefault();
			jQuery(this).find("#request_location_input").val("");
			jQuery(this).find("#request_location_input").attr('placeholder',jQuery(this).find("#request_location_input").data("replaceplaceholder"));
		}
		if(jQuery(this).find("#request_location_input").length>0 && jQuery(this).find("#request_location_input").val()==""){
			jQuery(this).find("#request_lat").val("");
			jQuery(this).find("#request_long").val("");
		}
	});
});

// input char counter functions
function get_input_length( element, type ) {
	type = typeof type !== 'undefined' ? type : 'input';

	if ( type == 'html' ) {
		return $("<div/>").html(( element ).html()).text().length;
	} else {
		return $("<div/>").html(( element ).val()).text().length;
	}
}

function the_input_length( element, target, max_length, type ) {
	type = typeof type !== 'undefined' ? type : 'input';

	var target_cnt = target.find('em').eq(0);
	if ( target_cnt.length == 0 ) {
		var target_cnt = $('<em/>').prependTo(target);
	}

	var input_length = get_input_length( element, type );
	target_cnt.html( input_length );

	if ( input_length > max_length ) {
		target.addClass( 'char-limit-exceeded' );
	} else {
		target.removeClass( 'char-limit-exceeded' );
	}
}

function add_input_length_listener( element, target, max_length, type ) {
	type = typeof type !== 'undefined' ? type : 'input';

	if ( type == 'html' ) {
		var editor = element;
		var element = $( editor.composer.element );

		the_input_length( element, target, max_length, type );
		editor.composer.element.addEventListener("keyup", function() {
			the_input_length( element, target, max_length, type );
		});
	} else {
		the_input_length( element, target, max_length, type );
		element.keyup( function(e) {
			the_input_length( element, target, max_length, type );
		});
	}
}
