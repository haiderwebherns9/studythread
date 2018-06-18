function isNumeric(n) {
	return !isNaN(parseFloat(n)) && isFinite(n);
}

function addNotify(type, msgs, cls){
	var html = '';

	if ( isNumeric(msgs) && msgs > 0 ) {
		if (msgs > 99) { msgs = 99; }

		html = '<div class="unread-label digits"><span class="messages_count">' + msgs + '</span></div>';

	}
	jQuery("." + cls + " .nh-link").each(function(){
		jQuery(this).html( html );
	});
}

function addMessageContent() {
	msg_username = jQuery('#pm_current_read_user_id').val();
	jQuery.ajax({
		type: "POST",
		url: base_main.ajaxurl,
		data: {
			action: 'show_new_messages',
			msg_username: msg_username
		},
		success: function(data){
			if(data != ''){
				$('.error-no-messages').remove();
			}

			var div_exist = $(data).filter('.pm-holder').attr("id");
			if($("#" + div_exist).length != 0) {
				$("#" + div_exist).fadeOut("normal", function() {
					$(this).remove();
				});
			}

			$( '.private-message-from-user' ).append( data );
		}
	});
}

function addMessageOnArchive() {
	$.extend($.expr[":"], {
		"containsIN": function(elem, i, match, array) {
			return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
		}
	});

	jQuery.ajax({
		type: "POST",
		url: base_main.ajaxurl,
		data: {
			action: 'show_new_messages_for_archive',
		},
		success: function(data){
			$('.pm-unread-message a').each(function(i, obj) {
				var op1 = $(this).attr('href').split('=');
				$("div > a:containsIN('"+op1[1]+"')").parent().remove();
			});

			if( $("#user_messages > #no_messages").length > 0 ){
				if(data != ''){
					$("#user_messages > #no_messages").remove();
				}
			}

			$("#user_messages").prepend( data );
		}
	});

}

function addMessageChatBox() {
	var oid = getUrlParameter('oid');
	jQuery.ajax({
		type: "POST",
		url: base_main.ajaxurl,
		data: {
			action: 'notifications_for_chatbox',
			oid: oid,
			jb_action: getUrlParameter('jb_action')
		},
		success: function(data){
			var buyer_rating      = $('.buyer_rating').val();
			var seller_rating     = $('.seller_rating').val();
			var rating_stars      = $('input[name=stars]:checked', '#rating').val();

			$('#all_chatbox_messages').html(data);

			$('.buyer_rating').val(buyer_rating).focus();
			$('.seller_rating').val(seller_rating).focus();

			$('input[name=stars]:checked', '#rating').val(rating_stars);
			$('input[name=stars]').filter('[value='+rating_stars+']').prop('checked', true);
		}
	});
}

function addMessageFormChatBox() {
	jQuery.ajax({
		type: "POST",
		url: base_main.ajaxurl,
		data: {
			action: 'messages_for_chatbox',
			oid: getUrlParameter('oid'),
			jb_action: getUrlParameter('jb_action')
		},
		success: function(data){
			obj = JSON.parse(data);

			sts1payment = $('#payment_status').html();
			sts2payment = obj['payment_status'];

			sts1order = $('#order_status').html();
			sts2order = obj['order_status'];

			sts1timeup = $('#times_up').html();
			sts2timeup = obj['times_up'];

			sts1order_cancellation = $('#order_cancellation').html();
			sts2order_cancellation = obj['order_cancellation'];

			if(
				( sts1payment            != '' && sts1payment            != sts2payment ) ||
				( sts1order              != '' && sts1order              != sts2order )   ||
				( sts1timeup             != '' && sts1timeup             != sts2timeup )  ||
				( sts1order_cancellation != '' && sts1order_cancellation != sts2order_cancellation )
			){
				$('#chat_box_message_form').html(obj['content']);
			}

			$('#payment_status').html(obj['payment_status']);
			$('#order_status').html(obj['order_status']);
			$('#times_up').html(obj['times_up']);
			$('#order_cancellation').html(obj['order_cancellation']);
		}
	});
}

// declare initLiveNotifications timeout handle in global scope
// in order to clear it when the function is called repeatedly
// and prevent flooding
var ilnTimeoutHandle;

function initLiveNotifications(previous){
	clearTimeout(ilnTimeoutHandle);

	previous = typeof previous !== 'undefined' ? previous : [];
	jQuery.ajax({
		type: "POST",
		url: live_notifications.light_ajax_url,
		data: {
			action: 'check_live_notifications'
		},
		success: function(data){
			addNotify("new", data.notifications, "nh-notifications");
			addNotify("new", data.messages, "nh-messages");

			if ( live_notifications.is_page_pm == 1 ) {
				addMessageOnArchive();
			}

			if( live_notifications.is_page_pm_single == 1 ){
				addMessageContent();
			}

			if( live_notifications.is_page_chatbox == 1 ){
				addMessageChatBox();
				addMessageFormChatBox();
			}

			if ( data.enabled == 'yes' ) {

				// check previous request
				var timeout = data.timeout;
				if ( previous.timeout > data.timeout ) {
					if ( previous.timeout <= data.max_timeout ) {
						timeout = previous.timeout;
					} else {
						timeout = data.max_timeout;
					}
				}

				// add delay or reset for the next one
				if ( data.notifications == previous.notifications
					&& data.messages == previous.messages ) {
					data.timeout += timeout;
				} else {
					timeout = data.timeout;
				}

				clearTimeout(ilnTimeoutHandle);
				ilnTimeoutHandle = setTimeout(
					function() {
						initLiveNotifications(data);
					},
					timeout
				);
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown){
			clearTimeout(ilnTimeoutHandle);
			ilnTimeoutHandle = setTimeout(
				function() {
					initLiveNotifications();
				},
				32000
			);
		}
	});
};

jQuery(document).ready(function(){
	initLiveNotifications();
});
