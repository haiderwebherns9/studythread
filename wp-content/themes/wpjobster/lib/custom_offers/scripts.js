jQuery( document ).ready(function( $ ){

	window.custom_offers_show_message = function(form_obj, msg) {
		jQuery('.custom-offers-msg-target', form_obj).addClass( msg.cssClass );
		jQuery('.custom-offers-msg-target', form_obj).fadeIn().html( msg.description );
	}

	// custom_offers_request_submit
	$( document ).on('submit', '.request_form', function( event ){
		event.preventDefault();

		$('.request_form .actions .button').addClass('loading');
		$('.offer_form .button').addClass("disabled");

		var $this = $(this);
		$.ajax({
			data: "action=custom_offers_request_submit&" + $this.serialize(),
			dataType: 'json',
			type: "POST",
			url: _custom_offers_settings.ajaxurl,
			success: function( msg ) {
				custom_offers_show_message($this, msg);
				if( msg['code'] == 'success' ){
					$('.ui.modal.add-extra-chatbox').modal('hide');
					$('.ui.modal.recent-request-modal').modal('hide');
					$('.ui.modal.single-req-custom-offer').modal('hide');
					$('.ui.modal.single-job-custom-offer').modal('hide');
				}
			}
		});

	});

	// custom_offers_offer_submit
	$( document ).on('submit', '.offer_form', function( event ){
		event.preventDefault();

		var id =  $('input[name="associate_request_id"]').val();
		$('.offer_form .button').addClass('loading');
		$('.offer_form .button').addClass("disabled");

		var $this = $(this);
		$.ajax({
			data: "action=custom_offers_offer_submit&" + $this.serialize(),
			dataType: 'json',
			type: "POST",
			url: _custom_offers_settings.ajaxurl,
			success: function( msg ) {
				custom_offers_show_message($this, msg);
				if( msg['code'] == 'success' ){
					$('.ui.modal.add-extra-chatbox').modal('hide');
					if ( id ){
						$('.ui.modal.recent-request-modal.'+id).modal('hide');
					} else {
						$('.ui.modal.recent-request-modal').modal('hide');
					}
					$('.ui.modal.single-req-custom-offer').modal('hide');
					$('.ui.modal.request-received').modal('hide');

					if ( $('input[name="page"]').length ) {
						if( $('input[name="page"]').val() === 'single_request' || _custom_offers_settings.live_notify != 'yes' ){
							location.reload();
						}
					}
				}
			}
		});
	});

});
