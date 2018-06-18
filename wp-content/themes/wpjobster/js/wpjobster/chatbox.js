jQuery(document).ready(function($){

	function addLoadingClass( $this, container ) {
		container = typeof container !== 'undefined' ? container : '#all_chatbox_messages';

		$this.addClass('loading');
		$(container + ' .button:not(.loading):not(.alwaysdisabled)').addClass('disabled');
	}

	function removeLoadingClass( $this, container ) {
		container = typeof container !== 'undefined' ? container : '#all_chatbox_messages';

		$(container + ' .button:not(.loading):not(.alwaysdisabled)').removeClass('disabled');
		$this.removeClass('loading');
	}


	// Chatbox Modals
	jQuery( document ).ajaxStop(function($) {
		var $ = jQuery;

		// Open request mutual cancellation modal
		$('.open-modal-request-cancellation').click(function(e){
			e.preventDefault();

			remove_modal_classes( 'request-mutual-cancellation' );

			$('.ui.modal.request-mutual-cancellation')
			.modal('setting', 'transition', 'fly down')
			.modal('show')
			.modal('refresh');

			var oid = getUrlParameter('oid');

			jQuery.ajax({
				type: 'post',
				url : base_main2.ajax_url,
				data: {
					action: 'wpj_request_mutual_cancellation_form',
					oid : oid
				},
				success: function( data ) {
					$('.modal-content-cancellation').html(data);
					$('.ui.modal.request-mutual-cancellation').modal('refresh');
				},
			});
		});

		// Request mutual cancellation action
		$('.btn-wpj-send-mutual-cancellation').click(function(e) {
			e.preventDefault();

			$(this).addClass('loading');

			jQuery.ajax({
				type: 'post',
				url : base_main2.ajax_url,
				data: $('#wpj_send_mutual_cancellation').serialize(),
				success: function( data ) {
					jQuery('.ui.modal.request-mutual-cancellation').modal('hide');
					remove_modal_classes( 'request-mutual-cancellation' );
					if ( chatbox_vars.live_notify == 'yes' ) {
						initLiveNotifications();
					} else {
						location.reload();
					}
				}
			});
		});

		// Open request modification modal
		$('.open-modal-request-modifications').click(function(e){
			e.preventDefault();

			var $this = $(this);
			addLoadingClass($this);

			remove_modal_classes( 'request-modification' );

			$('.ui.modal.request-modification')
			.modal({
				onShow: function() {
					addLoadingClass($this);
				},
				onHide: function() {
					removeLoadingClass($this);
				}
			})
			.modal('setting', 'transition', 'fly down')
			.modal('show')
			.modal('refresh');

			var oid = getUrlParameter('oid');

			jQuery.ajax({
				type: 'post',
				url : base_main2.ajax_url,
				data: {
					action: 'wpj_request_modification_form_function',
					oid: oid

				},
				success: function( data ) {

					$('.modal-content-request-modification').html(data);
					$('.ui.modal.request-modification').modal('refresh');
					if( chatbox_vars.live_notify == 'yes' ){
						initLiveNotifications();
					}

				},
				error: function( response ) {
					console.log( 'oops' );
				},
				complete: function() {

				}
			});
		});

		// Request modification action
		$('.btn-wpj-send-request-modification').click(function(e) {
			e.preventDefault();

			$(this).addClass('loading');

			jQuery.ajax({
				type: 'post',
				url : base_main2.ajax_url,
				data: $('#wpj_send_request_modification').serialize(),
				success: function( data ) {
					jQuery('.ui.modal.request-modification').modal('hide');
					remove_modal_classes( 'request-modification' );
					if ( chatbox_vars.live_notify == 'yes' ) {
						initLiveNotifications();
					} else {
						location.reload();
					}
				}
			});
		});
	});

	// Function for Buttons Action
	function transaction_action( url, data ){
		jQuery.ajax({
			type: "POST",
			url: url,
			data: data,
			success: function( msg ){
				if ( chatbox_vars.live_notify == 'yes' ) {
					initLiveNotifications();
				} else {
					location.reload();
				}
			}
		});

		return false;
	}

	// Ajax for ChatBox & Custom Offer Buttons
	var actions = [
		"mark_completed",
		"abort_mutual_cancelation",
		"mark_delivered",
		"order_cancellation",
		"accept_cancellation",
		"deny_cancellation",
		"decline_custom_extra",
		"cancel_custom_extra",
		"withdraw_custom_offer",
		"decline_custom_offer"
	];
	actions.forEach(function(action) {
		$( document ).on( 'click', 'a.'+action, function() {

			$(this).addClass("disabled");

			if( typeof oid !== 'undefined' ) {
				var data = "oid="+oid;
			}else{
				var data = '';
			}

			if( action == 'accept_cancellation' ){
				var url = chatbox_vars.blog_url + "/?jb_action=answer_mutual_cancellation&accept=yes&oid=" + oid;
			}else if( action == 'deny_cancellation' ){
				var url = chatbox_vars.blog_url + "/?jb_action=answer_mutual_cancellation&accept=no&oid=" + oid;
			}else if( action == 'decline_custom_extra' || action == 'cancel_custom_extra' ){
				var url = chatbox_vars.ajaxurl;
				data = $( $( this ).parent().children( 'form' ) ).serialize();
			}else if( action == 'decline_custom_offer' || action == 'withdraw_custom_offer' ){
				var url = chatbox_vars.ajaxurl;
				data = $( $( this ).parent().children( 'form' ) ).serialize();
				var pm_id = $( this ).attr( 'data-id' );
				$( '#pm_'+pm_id ).fadeOut( "normal", function() {
					$( this ).remove();
				});
			}else{
				var url = chatbox_vars.blog_url + "/?jb_action=" + action + "&oid=" + oid;
			}

			addLoadingClass( $(this) );

			transaction_action( url, data );

			if( chatbox_vars.live_notify == 'yes' ){
				initLiveNotifications();
			}
		});
	});

	// Ajax for Send Message System
	$('.send-message').click(function(e) {
		e.preventDefault();

		var $this = $(this);
		addLoadingClass($this, '#chat_box_message_form');
		$(this).attr("disabled", "disabled");

		$.ajax({
			type: "POST",
			url: chatbox_vars.ajaxurl,
			data: $('#chatbox_message_form').serialize(),
			success: function(msg) {
				if( msg.indexOf( "error" ) > -1 ){
					$('#chat_box_message_form').before( msg );
					$( '.error' ).delay( 3000 ).fadeOut( 'slow' );
				} else {
					$( '.chat_box_messages' ).val( '' );
					$( '#uploadifive-file_upload_chat_box_attachments-queue' ).empty();
					if ( chatbox_vars.live_notify == 'yes' ) {
						initLiveNotifications();
					} else {
						location.reload();
					}
				}

				removeLoadingClass($this, '#chat_box_message_form');
				setTimeout('$(".send-message").removeAttr("disabled")', 1500);
			}
		});
	});

	// Ajax for Modals Open
	var modals = [
		"request-mutual-cancellation",
		"request-modification",
		"add-extra-chatbox"
	];

	function remove_modal_classes( modal ){
		var i=1;
		$( '.ui.modal.smaller.' + modal ).each( function() {
			if(i !== 1){
				$( this ).remove();
			}else{
				$( '.ui.modal.' + modal ).modal( 'hide' );
			}
			i++;
		});
	}

	modals.forEach(function( modal ) {
		$( document ).on( 'click', '.' + modal + ' i.close.icon, ' + modal + ' ui.cancel', function() {
			remove_modal_classes( modal );
		});
	});

});
