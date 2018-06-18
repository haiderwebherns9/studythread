var $ = jQuery.noConflict();

//---------------------------------------
// Sidebar left responsive
//---------------------------------------

jQuery(function($){
	$('#toggle').click(function() {
		if ( ! $('.left.menu').hasClass('uncover') ) {
			$('.ui.sidebar.left').sidebar('toggle');
		}
	});

});
//---------------------------------------
// Sidebar right responsive
//---------------------------------------

jQuery(function($){
	$('#togglee').click(function(){
		if ( ! $('.right.menu').hasClass('uncover') ) {
			$('.ui.sidebar.right').sidebar('toggle');
		}
	});
});

//---------------------------------------
// Semantic ui dropdown
//---------------------------------------

jQuery(function($){
	$('.ui.selection.dropdown').dropdown();
});

jQuery(function($){
	$('.ui.dropdown').dropdown();
});

//---------------------------------------
// Language button top menu
//---------------------------------------

jQuery(function($){
	$('html').click(function() {
		$('#flag-test ul').slideUp(200);
	 });
});

//---------------------------------------
// Menu hover on profile top menu
//---------------------------------------

jQuery(function($){
	$('.nh-user-info.nh-has-submenu').hover(function(e) {
		$('.menu-user-dropdown').show();
		e.stopPropagation();
	}, function(e) {
		$('.menu-user-dropdown').hide();
		e.stopPropagation();
	});
});

jQuery(function($){

	$('.menu-user-dropdown li.parent').on('click', function(e) {
		$(this).children('ul').slideToggle(200);
		$(this).siblings('li').find('ul').hide();
		e.stopPropagation();
	});

});

//---------------------------------------
// Main menu hover subcategories
//---------------------------------------

jQuery(function($){
	$('#menu-header-main-menu li').each(function() {
		// this doesn't work for more than one level
		// it also prevents the top level menu link from working as usual
		if ( $(this).find('ul').first().size() > 0 ) {
			$(this).find('ul').parent().children("a").addClass('nh-accordion-handler');
			$(this).find('ul').children("li").addClass('nh-accordion-container');
			$(this).find('ul').wrap('<div class="nh-accordion" style="display: none;"></div>');
		}
	});
});

//---------------------------------------
// Modal Sales
//---------------------------------------

jQuery(function($){
	$('.button-modal-open').click(function(e){
		e.preventDefault();
		$('.ui.modal.legend')
		.modal('setting', 'transition', 'fly down')
		.modal('show');
	});
});

//---------------------------------------
// Sales graph arrow switch up, down
//---------------------------------------

jQuery(function($){
	$('.graph-link').click(function(){
		$(this).find('i').toggleClass('down').toggleClass('up');
	});
});

//---------------------------------------
// Other page hide sidebar if ul empty
//---------------------------------------

jQuery(function($){
	if($("ul.subcat-list").has('li').length == 0) {
		$(".ui.other-page.segment").remove();
	}
});

//---------------------------------------
// Hide parent div if child empty
//---------------------------------------

jQuery(function($){
	$('.two.fields.empty-field').not(':has(div.field)').hide();
});

//---------------------------------------
// Semantic post, edit job show hide divs
//---------------------------------------

jQuery(function($){
	$('#lets_meet').change(function(){
		if(this.checked)
		$('#location-input').show();
		else
		$('#location-input').hide();
		if(this.checked)
		$('#distance-input').show();
		else
		$('#distance-input').hide();
		if(this.checked)
		$('#map-checkbox').show();
		else
		$('#map-checkbox').hide();
	});
});

jQuery(function($){
	$('#lets_meet').change(function(){
		if(this.checked)
		$('#edit-location-input').show();
		else
		$('#edit-location-input').hide();
		if(this.checked)
		$('#edit-distance-input').show();
		else
		$('#edit-distance-input').hide();
		if(this.checked)
		$('#edit-map-checkbox').show();
		else
		$('#edit-map-checkbox').hide();
	});
});

jQuery( function( $ ) {

	$( '#packages' ).change( function() {
		if ( this.checked ) {
			$( '.packages' ).show();
			$( '#job_delivery_time_field' ).hide();
			$( '#job_price_field' ).hide();

			$( '.package_name').attr( "required", true );
			$( '.package_description').attr( "required", true );
			$( '.package_price').attr( "required", true );
		} else {
			$( '.packages' ).hide();
			$( '#job_delivery_time_field' ).show();
			$( '#job_price_field' ).show();

			$( '.package_name').removeAttr( "required" );
			$( '.package_description').removeAttr( "required" );
			$( '.package_price').removeAttr( "required" );
		}
	});

	$( document ).on( 'click', '.pck-icon-rem', function( e ) {
		if( $( '.pck-icon-rem' ).length > 1 ) {
			$( this ).parent().parent().remove();
		} else {
			$('.pck-repeater .ui.checkbox').each(function(){
				$(this).checkbox('uncheck');
			});
			$('.pck-inp-custom-name').val('');
		}
	});

	$( '#add_custom_field_to_package' ).click( function() {
		var clone = $(".pck-repeater:first").clone();
		clone.find("input:text").val("");
		clone.find("input:checkbox").removeAttr('checked');
		clone.find('.pck-icon-rem').show();
		clone.appendTo(".packages tbody");
	});
});

//---------------------------------------
// Semantic ui upload file post new job
//---------------------------------------

jQuery(function($){
	$("#divUpload").on("click", function() {
		$('#hidde-new-file').click();
	});
});

//---------------------------------------
// Semantic ui tooltip popup
//---------------------------------------
jQuery(function($){
	$( ".ui.popup" ).each(function( index ) {
		if( $( this ).text() === "" ) {
			$(this).prev().removeClass('instructions-popup');
		}
	});
	$('.instructions-popup').popup({inline: true});
});

//---------------------------------------
// Scroll to top button
//---------------------------------------

jQuery(function($){

	//Check to see if the window is top if not then display button
	$(window).scroll(function(){
		if ($(this).scrollTop() > 100) {
			$('.scrollToTop').fadeIn();
		} else {
			$('.scrollToTop').fadeOut();
		}
	});

	//Click event to scroll to top
	$('.scrollToTop').click(function(){
		$('html, body').animate({scrollTop : 0},800);
		return false;
	});


});

//---------------------------------------
// My account delete/deactivate modals
//---------------------------------------

jQuery(function($){
	$(document).on( 'click', '.open-delete-job', function(){
		var my_id = $(this).attr('data-id');

		var my_title_delete = $(this).attr('data-title-delete');

		$('.job-title-modal').html(my_title_delete);

		$('.ajax_delete_job').attr('data-id', $(this).attr('data-id') );

		$('.ui.modal.delete-job')
			.modal('setting', 'transition', 'fly down')
			.modal('show');
	});

	$(document).on('click', '.ajax_delete_job', function(event){

		var my_id = $(this).attr('data-id');

		event.preventDefault();

		jQuery.ajax({
			type : 'post',
			url : base_main2.ajax_url,
			data : {
				action		: 'wpj_ajax_delete_job',
				var_post_id : my_id
			},
			beforeSend: function() {
				$('.ajax_delete_job').addClass( 'loading' );
			},
			success: function( response ) {

				if ( response == 'success' ) {

					$('.background-delete-' + my_id).css('background', '#DF3F3F');
					$('.background-delete-' + my_id).slideUp(800);
					$('.ui.modal.delete-job').modal('hide');


				} else if ( response == 'not_your_post' ) {

					$('.content.delete-job').text('This is not your post.');

				} else if ( response == 'not_logged_in' ) {

					$('.content.delete-job').text('You need to login.');

				}

			},
			error: function( response ) {
				console.log( 'oops' );
			},
			complete: function() {
				$('.ajax_delete_job').removeClass( 'loading' );
			}
		});

		return false;

	});
});


jQuery(function($){
	$(document).on( 'click', '.open-deactivate-job', function(){
		var my_id = $(this).attr('data-id');

		$('.ajax_deactivate_job').attr('data-id', $(this).attr('data-id') );

		var my_title_deactivate = $(this).attr('data-title-deactivate');

		$('.job-title-modal').html(my_title_deactivate);
		$('.ui.modal.deactivate-job')
			.modal('setting', 'transition', 'fly down')
			.modal('show');
	});

	$(document).on('click', '.ajax_deactivate_job', function(event){

		var my_id = $(this).attr('data-id');

		event.preventDefault();

		jQuery.ajax({
			type : 'post',
			url : base_main2.ajax_url,
			data : {
				action		: 'wpj_ajax_deactivate_job',
				var_post_id : my_id
			},
			beforeSend: function() {
				$('.ajax_deactivate_job').addClass( 'loading' );
			},
			success: function( response ) {

				if ( response == 'success' ) {

					$('.background-delete-' + my_id + ' .open-deactivate-job').html(base_main2.activate);
					$('.background-delete-' + my_id + ' .open-deactivate-job').addClass('open-activate-job').removeClass('open-deactivate-job');
					$('.background-delete-' + my_id + ' .my-account-job-status .oe-green').addClass('oe-yellow').removeClass('oe-green');
					$('.background-delete-' + my_id + ' .my-account-job-status .oe-yellow').html(base_main2.paused);
					$('.ui.modal.deactivate-job').modal('hide');

				} else if ( response == 'not_your_post' ) {

					$('.content.deactivate-job').text('This is not your post.');

				} else if ( response == 'not_logged_in' ) {

					$('.content.deactivate-job').text('You need to login.');

				}

			},
			error: function( response ) {
				console.log( 'oops' );
			},
			complete: function() {
				$('.ajax_deactivate_job').removeClass( 'loading' );
			}
		});

		return false;

	});
});



jQuery(function($){

	$(document).on( 'click', '.open-activate-job', function(){

		var my_id = $(this).attr('data-id');

		$('.ajax_activate_job').attr('data-id', $(this).attr('data-id') );

		var my_title_activate = $(this).attr('data-title-activate');

		$('.job-title-modal').html(my_title_activate);

		$('.ui.modal.activate-job')
			.modal('setting', 'transition', 'fly down')
			.modal('show');
	});

	$(document).on('click', '.ajax_activate_job', function(event) {

		var my_id = $(this).attr('data-id');

		event.preventDefault();

		jQuery.ajax({
			type : 'post',
			url : base_main2.ajax_url,
			data : {
				action		: 'wpj_ajax_activate_job',
				var_post_id : my_id
			},
			beforeSend: function() {
				$('.ajax_activate_job').addClass( 'loading' );
			},
			success: function( response ) {

				if ( response = 'success' ) {

					$('.background-delete-' + my_id + ' .open-activate-job').html(base_main2.deactivate);
					$('.background-delete-' + my_id + ' .open-activate-job').addClass('open-deactivate-job').removeClass('open-activate-job');
					$('.background-delete-' + my_id + ' .my-account-job-status .oe-yellow').addClass('oe-green').removeClass('oe-yellow');
					$('.background-delete-' + my_id + ' .my-account-job-status .oe-green').html(base_main2.published);
					$('.ui.modal.activate-job').modal('hide');

				} else if ( response = 'not_your_post' ) {

					$('.content.activate-job').text('This is not your post.');

				} else if ( response = 'not_logged_in' ) {

					$('.content.activate-job').text('You need to login.');

				}

			},
			error: function( response ) {
				console.log( 'oops' );
			},
			complete: function() {
				$('.ajax_activate_job').removeClass( 'loading' );
			}
		});

		return false;
	});

});

//---------------------------------------
// Request / Custom Offer Modals
//---------------------------------------

jQuery(function($){

	$(document).on( 'click', '.request-open-modal', function(){

		var my_id = $(this).attr('data-request-id');

		$('.ajax_delete_request').attr('data-request-id', $(this).attr('data-request-id') );

		var my_title_request = $(this).attr('data-title-request');

		$('.request-title-modal').html(my_title_request);

		$('.ui.modal.delete-request')
			.modal('setting', 'transition', 'fly down')
			.modal('show');

	});

	$(document).on('click', '.ajax_delete_request', function(event) {

		var my_id = $(this).attr('data-request-id');

		event.preventDefault();

		jQuery.ajax({
			type : 'post',
			url : base_main2.ajax_url,
			data : {
				action		: 'wpj_ajax_request_delete',
				var_post_id : my_id
			},
			beforeSend: function() {
				$('.ajax_delete_request').addClass( 'loading' );
			},
			success: function( response ) {

				if ( response = 'success' ) {

					$( '.background-request-' + my_id ).slideUp( 800, function() { $(this).remove(); } );
					$( '.background-request-' + my_id ).css('background-color', '#df3f3f');
					$('.ui.modal.delete-request').modal('hide');

				} else if ( response = 'not_your_post' ) {

					$('.content.delete-request').text('This is not your post.');

				} else if ( response = 'not_logged_in' ) {

					$('.content.delete-request').text('You need to login.');

				}
			},
			error: function( response ) {
				console.log( 'oops' );
			},
			complete: function() {
				$('.ajax_delete_request').removeClass( 'loading' );
			}
		});

		return false;

	});

});

jQuery(function($){

	$(document).on('click', '.open-custom-request-received-modal', function(e){
		e.preventDefault();
		$('.ui.modal.request-received')
			.modal({
				onApprove: function() { return false; },
				onDeny: function() { return false; }
			})
			.modal( 'setting', 'transition', 'fly down' )
			.modal( 'show' )
			.modal('refresh');

		jQuery.ajax({
			type: 'post',
			url : base_main2.ajax_url,
			data: {
				action: 'custom_offers_offer_form',
				user: $( '.custom-offers-offer-dialog' ).attr( 'data-user' ),
				jid: $( '.custom-offer-pm' ).attr( 'data-jid' ),
				extra: $( "#custom-offers-offer-target" ).data( "extra" ),
				oid: $( "#custom-offers-offer-target" ).data( "oid" ),
				formrid: $('#form-rand-num').val()
			},
			success: function( data ) {

				$( '.form-customer-offer-request-received' ).html( data );
				$('.ui.modal.request-received').modal('refresh');

			}
		});
		return false;
	});

});

(function ( $ ) {
$.fn.offerModalInit = function(){
	$('.open-modal-recent-request').click(function(e){

		e.preventDefault();

		var associate_request_id = $(this).attr("data-requestid");

		$('.recent-request-modal.custom-offers-container.' + associate_request_id)
			.modal({
				onApprove: function() { return false; },
				onDeny: function() { return false; }
			})
			.modal('setting', 'transition', 'fly down')
			.modal('show')
			.modal('refresh');

		jQuery.ajax({

			type: 'post',
			url: base_main2.ajax_url,
			data: {
				action: 'custom_offers_offer_form',
				associate_request_id:associate_request_id,
				user: $("#custom-offers-offer-target-" + associate_request_id).attr("data-user"),
				jid: associate_request_id
			},

			beforeSend: function() {
				$( '.semantic-new-btn' ).addClass( 'loading' );
			},

			success: function( data ) {

				$('.form-customer-offer-recent-request').html( data );
				$('.recent-request-modal.custom-offers-container.' + associate_request_id).modal('refresh');

			},

			error: function( response ) {
				console.log( 'oops' );
			},

			complete: function() {

			}

		});
		return false;
	});
}
$.fn.offerModalInit();
}( jQuery ));

jQuery(function($){
	$('.open-modal-request-error').click(function(e){

		e.preventDefault();
		var associate_request_id = $(this).attr("data-requestid");

		$('.request-error-modal.custom-offers-container.' + associate_request_id)
			.modal({
				onApprove: function() { return false; },
				onDeny: function() { return false; }
			})
			.modal('setting', 'transition', 'fly down')
			.modal('show')
			.modal('refresh');

		jQuery.ajax({
			type: 'post',
			url: base_main2.ajax_url,
			data: {
				action: 'request_error_content',
			},

			beforeSend: function() {
				$( '.semantic-new-btn' ).addClass( 'loading' );
			},

			success: function( data ) {
				$('.form-customer-offer-request-error').html( data );
				$('.request-error-modal.custom-offers-container.' + associate_request_id).modal('refresh');
			},

			error: function( response ) {
				console.log( 'oops' );
			},

			complete: function() {

			}

		});
		return false;
	});
});

jQuery(function($){
	$(document).on('click', '.open-modal-single-request-offer', function(e){
		e.preventDefault();
		$('.ui.modal.single-job-custom-offer')
			.modal({
				onApprove: function() { return false; },
				onDeny: function() { return false; }
			})
			.modal( 'setting', 'transition', 'fly down' )
			.modal( 'show' )
			.modal('refresh');

		jQuery.ajax({
			type: 'post',
			url : base_main2.ajax_url,
			data: {
				action: 'custom_offers_request_form',
				user: $( '.custom-offers-offer-dialog' ).attr( 'data-user' ),
				jid: $( '.custom-offers-offer-dialog' ).attr( 'data-jid' ),
				extra: $( "#custom-offers-offer-target" ).data( "extra" ),
				oid: $( "#custom-offers-offer-target" ).data( "oid" )
			},

			beforeSend: function() {
				$( '.ui.button.semantic-req-btn' ).addClass( 'loading' );
			},

			success: function( data ) {
				$( '.single-job-request-custom-offer' ).html( data );
				$('.ui.modal.single-job-custom-offer').modal('refresh');
			},

			error: function( response ) {
				console.log( 'oops' );
			},

			complete: function() {
				$( '.ui.button.semantic-req-btn' ).removeClass( 'loading' );
			}
		});
		return false;
	});
});

//---------------------------------------
// Accept only numbers on input number
//---------------------------------------

function validate(evt) {

	var theEvent = evt || window.event;
	var key = theEvent.keyCode || theEvent.which;
	key = String.fromCharCode( key );
	var regex = /^[0-9]+$/;
	if( !regex.test(key) ) {
		theEvent.returnValue = false;
		if(theEvent.preventDefault) theEvent.preventDefault();
	}
}

//---------------------------------------
// Private Message Ajax no page reload
//---------------------------------------

jQuery(function($){

	$("#message-pm-user").click(function(){
		$('.wrapper-pm-errors').fadeOut(600);
	});

	$('.submit-private-message').click(function(e){


		e.preventDefault();

		var otheruid = $(this).attr('data-otheruid');
		var message = $('#message-pm-user').val();
		var upload = $('input[name="hidden_files_pm_attachments"]').val();

		if ( message != "" ) {

			jQuery.ajax({
				type: 'post',
				url: base_main2.ajax_url,
				data: {
					action: 'wpj_ajax_send_message_users',
					otheruid : otheruid,
					message : message,
					upload : upload,
				},

				beforeSend: function() {

					$( '.send-my-pm' ).addClass( 'loading' );

				},

				success: function( data ) {

					$( '.private-message-from-user' ).append( data );

				},

				error: function( response ) {

					console.log( 'oops' );

				},

				complete: function() {

					$('.send-my-pm').removeClass('loading');
					$('#message-pm-user').val('');
					$('#uploadifive-file_upload_pm_attachments-queue').empty();
					$('.error-no-messages').remove();
					$('.pm-delete.action-tooltip').click(function(){
						$('.pm-delete-confirm').show();
					});

				}

			});
		} else {

			$('.wrapper-pm-errors').hide().html('<div class="ui small red message"> ' + base_main2.msg_err + ' </div>').fadeIn(600);
			return false;

		}
	});
});


jQuery(function($){
	$(document).on('click', '.report-job-new.wpj-modal-report', function(e){
		e.preventDefault();
		$('.ui.modal.report-job')
			.modal( 'setting', 'transition', 'fly down' )
			.modal( 'show' )
			.modal('refresh');

		var my_title = $('.report-job-new.wpj-modal-report').attr('data-title');


		jQuery.ajax({
			type: 'post',
			url : base_main2.ajax_url,
			data: {
				action: 'wpj_report_job_form',
				my_title: my_title
			},

			beforeSend: function() {
				$( '.login-input-wrappers' ).addClass( 'loading' );
			},

			success: function( data ) {
				$( '.content.report-form-modal' ).html( data );
				$('.ui.modal.report-job').modal('refresh');
			},

			error: function( response ) {
				console.log( 'oops' );
			},

			complete: function() {
				$( '.login-input-wrappers' ).removeClass( 'loading' );
			}
		});
		return false;

	});
});

jQuery(function($){
	$( document ).on('click', '#report-modal-semantic', function(){
		var $this = $(this);
		$.ajax({
			data: "action=report_submit&job_id="+$("#user-report-job").data("jobid")+"&content=" + $('#report_content').val(),
			dataType: 'json',
			type: "POST",
			url: _custom_offers_settings.ajaxurl,
			success: function( msg ) {
				if(msg.err=='1'){
					$(".report-job-msg-target").removeClass("white-cnt padding-cnt center red-cnt");
					$(".report-job-msg-target").removeClass("white-cnt padding-cnt center green-cnt");
					$(".report-job-msg-target").addClass("white-cnt padding-cnt center red-cnt");
					$(".report-job-msg-target").html(msg.msg);
				}else{
					$(".report-job-msg-target").removeClass("white-cnt padding-cnt center red-cnt");
					$(".report-job-msg-target").removeClass("white-cnt padding-cnt center green-cnt");
					$(".report-job-msg-target").addClass("white-cnt padding-cnt center green-cnt");
					$(".report-job-msg-target").html(msg.msg);
				}
			}
		});
	});
});

jQuery(function($){
	$( document ).on('click', '#report-close', function( e ){
		e.preventDefault();
		$('.ui.modal.report-job').modal("hide");
	});
});


jQuery(function($){
	jQuery(document).ready(function(){
		jQuery("#load-more-feedback").click(function(){
			var current = jQuery("#load-more-feedback").attr("data-rel");
			// var pid = jQuery("#pid").val();
			var uid = jQuery("#uid").val();
			var total_per_load = jQuery("#total_per_load").val();
			var action = "show_more_feedbacks_user";
			jQuery.ajax({
				type: "POST",
				url: ajaxurl,
				dataType: 'json',
				data: {current:current,uid:uid,total_per_load:total_per_load,action:action  },
				success: function (data) {
					if(data.ok=='1'){
						jQuery("#all-feedback-box").append(data.html);

					}else if(typeof data.error !='undefined'){
						alert(data.error);
					}
					if(data.current == '0'){
						jQuery("#load-more-feedback").hide('slow');
					}else{
						jQuery("#load-more-feedback").attr("data-rel",data.current);
					}
				}
			});

		});
	});
});


jQuery(function($){

	$('#job_tags, #job_tags_rejected').tagsInput({
		'defaultText':'',
		'height': 'auto',
		'width' : '100%'
	});

});

jQuery(function($){
	$('.layout_jobs').on('click', function(){
		var job_style = $(this).data("value");
		if( job_style == 'grid' ) {
			$('.wpj-load-more-target').removeClass('list-grid');
			$(this).parent().removeClass('list-grid');
		}
		if( job_style == 'list' ) {
			$('.wpj-load-more-target').addClass('list-grid');
			$(this).parent().addClass('list-grid');
		}

		if ( $.cookie('cards-layout') != 'list' ) {
			$.cookie('cards-layout', 'list', { expires: 7, path: '/' });
		} else {
			$.cookie('cards-layout', 'grid', { expires: 7, path: '/' });
		}
	});

});

jQuery(function($){

	if( $('input#lets_meet').is( ':checked' ) ) {
		$('#edit-location-input').css('display', 'block');
		$('#edit-distance-input').css('display', 'block');
		$('#edit-map-checkbox').css('display', 'block');
	}

});

jQuery(function($){
	$('.open-single-req-offer').click(function(e){
		e.preventDefault();
		$('.ui.modal.single-req-custom-offer')
		.modal({
			onApprove: function() { return false; },
			onDeny: function() { return false; }
		})
		.modal('setting', 'transition', 'fly down')
		.modal('show')
		.modal('refresh');

		var user = $(this).attr('data-user');
		var associate_request_id = $(this).attr('data-requestid');

		jQuery.ajax({
			type: 'post',
			url : base_main2.ajax_url,
			data: {
				action: 'custom_offers_offer_form',
				user: user,
				associate_request_id: associate_request_id,
				page: 'single_request'
			},
			success: function( data ) {

				$('.modal-content-single-request-offer').html(data);
				$('.ui.modal.single-req-custom-offer').modal('refresh');

			},
			error: function( response ) {
				console.log( 'oops' );
			},
			complete: function() {

			}
		});
	});
});

jQuery(function($){

	var enable_disable = base_main2.header_fixed;

	$(window).bind('scroll', function () {
		if ( enable_disable == 1 ) {

			var wpj_hwrapper = $(".wrapper-menu-top");
			var wpj_hwrapper_r = $(".top-menu-wrapper-responsive");
			$(window).scroll(function() {
				var windowpos = $(window).scrollTop();
				if (windowpos > 0) {
					wpj_hwrapper.addClass("fixed");
					wpj_hwrapper_r.addClass("fixed");
				} else {
					wpj_hwrapper.removeClass("fixed");
					wpj_hwrapper_r.removeClass("fixed");
				}
			});

		}
	});
});

jQuery(function($){
	jQuery("#max_days").change(function(){
		if(jQuery("#max_days").val()=='instant'){
			$("#instant-file-section").removeClass('hidden');
		}else{
			$("#instant-file-section").addClass('hidden');
		}
	});
});

jQuery(function($){
	// make sure that we are on the pm page, because it breaks chatbox otherwise
	if ( base_main2.is_page_pm_single == 1 ) {
		$(document).on('click', '.pm_ajax', function(e){

			e.preventDefault();

			var user_modal_id = $( '.custom-offers-offer-dialog' ).attr( 'data-user' );
			var modal_message = $('.offer_description').val();
			var modal_price = parseInt( $('input[name="price"]').val() );
			var modal_delivery = $('input[name="delivery"]').val();
			var formrandomid = $('input[name="formrandomid"]').val();

			$('.offer_description').click(function(){
				$('.wrapper-textarea-error').fadeOut(600);
			});

			$('input[name="price"]').click(function(){
				$('.wrapper-price-error').fadeOut(600);
			});

			$('input[name="delivery"]').click(function(){
				$('.wrapper-days-error').fadeOut(600);
			});

			if ( modal_message == "" ) {
				$('.wrapper-textarea-error').hide().html('<div class="ui tiny red message">' + base_main2.msg_err + '</div>').fadeIn(600);
				return false;
			}

			if ( modal_price == "" || isNaN( modal_price ) ) {
				$('.wrapper-price-error').hide().html('<div class="ui tiny red message">' + base_main2.amount_err + '</div>').fadeIn(600);
				return false;
			}

			if ( modal_price < parseInt( base_main2.price_min ) ) {
				$('.wrapper-price-error').hide().html('<div class="ui tiny red message">' + base_main2.price_min_err + '</div>').fadeIn(600);
				return false;
			}

			if ( modal_price > parseInt( base_main2.price_max ) ) {
				$('.wrapper-price-error').hide().html('<div class="ui tiny red message">' + base_main2.price_max_err + '.</div>').fadeIn(600);
				return false;
			}

			if ( modal_delivery == "" ) {
				$('.wrapper-days-error').hide().html('<div class="ui tiny red message">' + base_main2.deliv_err + '</div>').fadeIn(600);
				return false;
			}

			if ( modal_delivery < 1 ) {
				$('.wrapper-price-error').hide().html('<div class="ui tiny red message">' + base_main2.deliv_min_err + '</div>').fadeIn(600);
				return false;
			}

			if ( modal_delivery > parseInt( base_main2.deliv_max ) ) {
				$('.wrapper-price-error').hide().html('<div class="ui tiny red message">' + base_main2.deliv_max_err + '.</div>').fadeIn(600);
				return false;
			}

			jQuery.ajax({
				type: 'post',
				url: base_main2.ajax_url,
				data: {
					action: 'wpj_send_modal_message_ajax_user',
					otheruid: user_modal_id,
					modal_message: modal_message,
					modal_price: modal_price,
					modal_delivery: modal_delivery,
					formrandomid : formrandomid,
					jid: $( '.custom-offer-pm' ).attr( 'data-jid' ),
				},

				beforeSend: function() {
					$( '.content.pm-offer' ).addClass( 'loading' );
				},

				success: function( data ) {
					$( '.private-message-from-user' ).append( data );
				},

				error: function( response ) {
					console.log('oops');
				},

				complete: function() {
					$('.content.pm-offer').removeClass('loading');
					$('.ui.modal.request-received').modal('hide');
				}


			});


		});
	}

});

/* Semantic UI Accordeon initialization */
jQuery(function($){
	$('.ui.accordion').accordion();
});
