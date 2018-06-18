jQuery(document).ready( function($) {

	var added_message = upb_vars.added_message;
	var delete_message = upb_vars.delete_message;

	$(".upb_add_bookmark").click( function() {
		var post_id = $(this).attr('rel');
		var data = {
			action: 'bookmark_post',
			post_read: post_id
		};
	 	$.post(upb_vars.ajaxurl, data, function(response) {
			// alert(added_message);
			$('.upb_bookmark_control_'+post_id).toggle();
			if($('.upb-bookmarks-list').length > 0 ) {
				var bookmark_data = {
					action: 'insert_bookmark',
					post_id: post_id
				};
				$.post(upb_vars.ajaxurl, bookmark_data, function(bookmark) {
					$(bookmark).appendTo('.upb-bookmarks-list');
					$('.no-bookmarks').fadeOut();
				});
			}
	 	});
	 	return false;
	});
	$(".upb_del_bookmark").click( function() {
		// if(confirm(delete_message)) {
			var post_id = $(this).attr('rel');
			var data = {
				action: 'del_bookmark',
				del_post_id: post_id
			};
			$.post(upb_vars.ajaxurl, data, function(response) {
				$('.bookmark-'+post_id).fadeOut();
				$('.upb_bookmark_control_'+post_id).toggle();
			});
		// }
	 	return false;
	});
});
