//---------------------------------------
// Vacation Mode
//---------------------------------------

jQuery(function($){
	$('#vacation_mode_toggle_ui').click(function(){
		$('#vacation_mode_modal_ui').modal({
			onShow: function(){
				$('#vacation_mode_end_ui').wpjcalendar();
			},
			onHide : function(){
				$('#vacation_mode_toggle_ui').checkbox('toggle');
			},
			autofocus: false,
			transition: 'fly down',
			observeChanges: true
		})
		.modal('show')
		.modal('refresh')
		;
	});


	// HELPERS
	$(document).on( 'click', '.message .close', function( event ) {
		$(this)
			.closest('.message')
			.transition('fade')
		;
	});

	function dismissableMessage( type, title, content ) {
		return '<div class="ui ' + type + ' message"><i class="close icon"></i><div class="header">' + title + '</div><p>' + content + '</p></div>';
	}

	function vacationModalRefresh() {
		jQuery.ajax({
			type : 'post',
			url : modals.ajax_url,
			data : {
				action: 'wpj_vacation_mode_content',
				_ajax_nonce: modals._ajax_nonce
			},
			beforeSend: function() {
				$('#vacation_modal_refresh').addClass( 'loading' );
			},
			success: function( response ) {
				$('#vacation_mode_modal_ui').html( response );
				$('.ui.dropdown').dropdown();
			},
			error: function( response ) {
				$('#vacation_mode_modal_ui .content').html( dismissableMessage( 'error', modals.err, modals.err_something_wrong ) );
				$('#vacation_mode_modal_ui .actions').html( '<div class="ui green labeled icon button" id="vacation_modal_refresh">' + modals.err_try_again + '<i class="refresh icon"></i></div>' );
			},
			complete: function() {
				$('#vacation_modal_refresh').removeClass( 'loading' );
			}
		});
	}


	// AJAX REFRESH
	$(document).on( 'click', '#vacation_modal_refresh', function( event ) {
		vacationModalRefresh();
		return false;
	});


	// AJAX YES
	$(document).on( 'click', '#vacation_mode_yes', function( event ) {
		event.preventDefault();
		var $button = $(this);
		var $modal_cnt = $('#vacation_mode_modal_ui');
		var $messages_cnt = $('#vacation_mode_modal_ui .content .messages');

		var away_reason = $("#wpjobster_vacation_away_reason").val();
		var end_date = $("#wpjobster_vacation_duration_end").val();
		var end_date_timestamp = $("#wpjobster_vacation_duration_end").attr('data-timestamp');

		jQuery.ajax({
			type : 'post',
			url : modals.ajax_url,
			data : {
				action: 'wpj_vacation_mode_activate',
				_ajax_nonce: modals._ajax_nonce,
				away_reason: away_reason,
				end_date: end_date,
				end_date_timestamp: end_date_timestamp
			},
			beforeSend: function() {
				$button.addClass( 'loading' );
			},
			success: function( response ) {

				if ( response == 'success' ) {
					$messages_cnt.html( dismissableMessage( 'success', modals.success, modals.success_saved ) );

					$modal_cnt
					.modal({
						transition: 'fly down',
					})
					.delay(1000)
					.queue(function() {
						$(this).modal('hide').dequeue();
						vacationModalRefresh();
					});

				} else if ( response == 'err_already_in_vacation' ) {
					$messages_cnt.html( dismissableMessage( 'error', modals.err, modals.err_already_in_vacation ) );

				} else if ( response == 'err_empty_end_date' ) {
					$messages_cnt.html( dismissableMessage( 'error', modals.err, modals.err_empty_end_date ) );

				} else if ( response == 'err_small_end_date' ) {
					$messages_cnt.html( dismissableMessage( 'error', modals.err, modals.err_small_end_date ) );

				} else {
					$messages_cnt.html( dismissableMessage( 'error', modals.err_unknown, modals.err_try_again_later ) );
				}
			},
			error: function( response ) {
				$messages_cnt.html( dismissableMessage( 'error', modals.err_unknown, modals.err_try_again_later ) );
			},
			complete: function() {
				$button.removeClass( 'loading' );
			}
		});

		return false;
	});


	// AJAX NO
	$(document).on( 'click', '#vacation_mode_no', function( event ) {
		event.preventDefault();
		var $button = $(this);
		var $modal_cnt = $('#vacation_mode_modal_ui');
		var $messages_cnt = $('#vacation_mode_modal_ui .content .messages');

		jQuery.ajax({
			type : 'post',
			url : modals.ajax_url,
			data : {
				action: 'wpj_vacation_mode_deactivate',
				_ajax_nonce: modals._ajax_nonce
			},
			beforeSend: function() {
				$button.addClass( 'loading' );
			},
			success: function( response ) {

				if ( response == 'success' ) {
					$messages_cnt.html( dismissableMessage( 'success', modals.success, modals.success_saved ) );

					$modal_cnt
					.modal({
						transition: 'fly down',
					})
					.delay(1000)
					.queue(function() {
						$(this).modal('hide').dequeue();
						vacationModalRefresh();
					});
				} else {
					$messages_cnt.html( dismissableMessage( 'error', modals.err_unknown, modals.err_try_again_later ) );
				}
			},
			error: function( response ) {
				$messages_cnt.html( dismissableMessage( 'error', modals.err_unknown, modals.err_try_again_later ) );
			},
			complete: function() {
				$button.removeClass( 'loading' );
			}
		});

		return false;
	});

});
