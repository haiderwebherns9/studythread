jQuery(document).ready(function($) {
	Dropzone.autoDiscover = false;

	//sortable thumbnails
	$( "#dropzone-jobimages" ).sortable({
		connectWith: '#dropzone-jobimages',
		start: function(event, ui) {
		},
		change: function(event, ui) {
		},
		update: function(event, ui) {
			var images_order = $( "#dropzone-jobimages" ).sortable('toArray', { attribute: 'data-id' });
			$( "#images_order" ).val( images_order );
		},
		items: ".dz-preview"
	});

	$( "#dropzone-jobimages div" ).disableSelection();


	if ( $( "#dropzone-jobimages .dz-preview.dz-image-preview.dz-complete" ).length > 0 ) {
		$( "#dropzone-jobimages" ).addClass( "dz-started" );
	}

	if ( $( "#dropzone-jobimages .dz-preview.dz-image-preview.dz-complete" ).length >= $("#dropzone-jobimages").data("maxnrofpictures") ) {
		$( "#dropzone-jobimages" ).addClass( "maxnrofpictures" );
	}



	// cover
	// $( "#dropzone-jobcover .dz-default.dz-message" ).remove();
	if ( $( "#dropzone-jobcover .dz-preview" ).length >= 1 ) {
		$( "#dropzone-jobcover" ).addClass( "maxnrofpictures" );
	}

});


function delete_this( id ) {
	jQuery.ajax({
		method: 'get',
		url : ajaxurl + '?_ad_delete_pid=' + id,
		dataType : 'text',
		success: function ( text ) {
			jQuery( '#image_ss' + id ).remove();
			if ( jQuery( "#dropzone-jobimages .dz-preview.dz-image-preview.dz-complete" ).length < jQuery("#dropzone-jobimages").data("maxnrofpictures") ) {
				jQuery( "#dropzone-jobimages" ).removeClass( "maxnrofpictures" );
			}
		}
	});
}


function delete_this_cover( id, pid ) {
	jQuery.ajax({
		method: 'get',
		url : ajaxurl + '?_ad_delete_pid=' + id + '&cover_parent=' + pid,
		dataType : 'text',
		success: function ( text ) {
			jQuery( '#image_ss' + id ).hide();
			$( "#dropzone-jobcover" ).removeClass( "maxnrofpictures" );
		}
	});
}


function delete_this_cover_thumb( thumb ) {
	thumb.parent().hide();
	$( "#dropzone-jobcover" ).removeClass( "maxnrofpictures" );
}
