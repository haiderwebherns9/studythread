jQuery( document ).ready( function( $ ) {

	var search_jobs_url = search_autocomplete.search_jobs_url + '?term1=';
	var search_requests_url = search_autocomplete.search_requests_url + '?term1=';
	var search_users_url = search_autocomplete.search_users_url + '?user=';
	var user_profile_url = search_autocomplete.user_profile_url;
	var jobs_label = search_autocomplete.jobs_label;
	var requests_label = search_autocomplete.requests_label;
	var users_label = search_autocomplete.users_label;
	var search_users_label = '<span class="no-highlight">' + search_autocomplete.search_users_label + '</span> ';

	$('.nh-search-container').each( function() {
		search_autocomplete_init( $(this) );
	});
	$('.autocomplete-search').each( function() {
		search_autocomplete_init( $(this) );
	});
	$('.hd-advanced-search > div > .wpj-search-autocomplete').each( function() {
		search_autocomplete_init( $(this) );
	});

	// search init
	function search_autocomplete_init( $container ) {
		var search_timeout;

		var $input = $container.find('input[type="text"]');
		$input.on('keyup keydown keypress', function() {
			$container.addClass('loading');
			clearTimeout(search_timeout);
			search_timeout = setTimeout(function() {
				search_when_done_typing($container, $input);
			}, 250);
		});

		$container.append('<div class="autocomplete-list-container"></div>');
		//$container.append('<i class="autocomplete-icon notched circle loading icon" style="display: none"></i>');

		var $list = $container.find('.autocomplete-list-container');

		$('html').click(function() {
			$list.slideUp(150);
		});

		$container.click(function(event) {
			$list.slideDown(150);
			event.stopPropagation();
		});
	}



	// search ajax
	function search_when_done_typing( $container, $input ) {

		function autocomplete_item_html( item_html, item_input, item_class ) {

			if ( item_class == '' ) {
				item_class = 'autocomplete';
			}

			var cnt_open = '';
			var cnt_close = '';

			if ( item_input != '' ) {
				cnt_open = '<div class="autocomplete-text">';
				cnt_close = '</div>';
			}

			var a_href_open = '';
			var a_href_close = '';
			var item_icon = '';

			if ( item_class == 'autocomplete-search-jobs' ) {
				a_href_open = '<a href="' + search_jobs_url + item_input + '">';
				a_href_close = '</a>';
				item_icon = '<i class="search icon"></i>';
			}
			if ( item_class == 'autocomplete-search-requests' ) {
				a_href_open = '<a href="' + search_requests_url + item_input + '">';
				a_href_close = '</a>';
				item_icon = '<i class="search icon"></i>';
			}
			if ( item_class == 'autocomplete-search-users' ) {
				a_href_open = '<a href="' + search_users_url + item_input + '">';
				a_href_close = '</a>';
				item_icon = '<i class="search icon"></i>';
			}
			if ( item_class == 'autocomplete-user' ) {
				a_href_open = '<a href="' + user_profile_url + item_input + '/">';
				a_href_close = '</a>';
				item_icon = '<i class="user icon"></i>';
			}

			var item_return =
				'<div class="autocomplete-item">'
					+ a_href_open
						+ '<div class="' + item_class + '" data-input="' + item_input + '">'
							+ cnt_open
								+ item_icon + item_html
							+ cnt_close
						+ '</div>'
					+ a_href_close
				+ '</div>';

			return item_return;
		}

		var action = 'search_autocomplete_ajax';
		var input = $input.val();

		var data = {
			'action': action,
			'input': input
		};

		$.post( ajaxurl, data, function( response ) {

			var arr = JSON.parse( response );

			var items_html = '';
			if ( input != '' && input != ' ' ) {
				items_html += '<div class="autocomplete-list">';

				if(search_autocomplete.allow_job == 'yes'){
					items_html += autocomplete_item_html( jobs_label, '', 'autocomplete-title' );
					if ( arr['jobs'].length ) {
						for ( i = 0; i < arr['jobs'].length; i++ ) {
							items_html += autocomplete_item_html( arr['jobs'][i], arr['jobs'][i], 'autocomplete-search-jobs' );
						}
					} else {
						items_html += autocomplete_item_html( input, input, 'autocomplete-search-jobs' );
					}
				}

				if(search_autocomplete.allow_request == 'yes'){
					items_html += autocomplete_item_html( requests_label, '', 'autocomplete-title' );
					if ( arr['requests'].length ) {
						for ( i = 0; i < arr['requests'].length; i++ ) {
							items_html += autocomplete_item_html( arr['requests'][i], arr['requests'][i], 'autocomplete-search-requests' );
						}
					} else {
						items_html += autocomplete_item_html( input, input, 'autocomplete-search-requests' );
					}
				}

				if(search_autocomplete.allow_users == 'yes'){
					items_html += autocomplete_item_html( users_label, '', 'autocomplete-title');
					items_html += autocomplete_item_html( search_users_label + input, input, 'autocomplete-search-users');
					if ( arr['users'].length ) {
						for ( i = 0; i < arr['users'].length; i++ ) {
							if ( arr['companies'].length ) {
								items_html += autocomplete_item_html( arr['users'][i] + arr['companies'][i], arr['users'][i], 'autocomplete-user');
							} else {
								items_html += autocomplete_item_html( arr['users'][i], arr['users'][i], 'autocomplete-user');
							}
						}
					}
				}

				items_html += '</div>';
			}

			$container.find('.autocomplete-list-container').html( items_html );

			$container.find('.autocomplete-list-container .autocomplete-item').each( function() {
				$(this).find( '.autocomplete-text' ).highlight( input );
			});

			$container.removeClass('loading');
		});
	}



	// highlight jquery
	jQuery.extend({
		highlight: function (node, re, nodeName, className) {
			if (node.nodeType === 3) {
				var match = node.data.match(re);
				if (match) {
					var highlight = document.createElement(nodeName || 'span');
					highlight.className = className || 'highlight';
					var wordNode = node.splitText(match.index);
					wordNode.splitText(match[0].length);
					var wordClone = wordNode.cloneNode(true);
					highlight.appendChild(wordClone);
					wordNode.parentNode.replaceChild(highlight, wordNode);
					return 1; //skip added node in parent
				}
			} else if ((node.nodeType === 1 && node.childNodes) && // only element nodes that have children
					!/(script|style)/i.test(node.tagName) && // ignore script and style nodes
					!(node.tagName === nodeName.toUpperCase() && node.className === className)) { // skip if already highlighted
				for (var i = 0; i < node.childNodes.length; i++) {
					i += jQuery.highlight(node.childNodes[i], re, nodeName, className);
				}
			}
			return 0;
		}
	});

	jQuery.fn.unhighlight = function (options) {
		var settings = { className: 'highlight', element: 'span' };
		jQuery.extend(settings, options);

		return this.find(settings.element + "." + settings.className).each(function () {
			var parent = this.parentNode;
			parent.replaceChild(this.firstChild, this);
			parent.normalize();
		}).end();
	};

	jQuery.fn.highlight = function (words, options) {
		var settings = { className: 'highlight', element: 'span', caseSensitive: false, wordsOnly: false };
		jQuery.extend(settings, options);

		if (words.constructor === String) {
			words = [words];
		}
		words = jQuery.grep(words, function(word, i){
		return word != '';
		});
		words = jQuery.map(words, function(word, i) {
		return word.replace(/[-[\]{}()*+?.,\\^$|#\s]/g, "\\$&");
		});
		if (words.length == 0) { return this; };

		var flag = settings.caseSensitive ? "" : "i";
		var pattern = "(" + words.join("|") + ")";
		if (settings.wordsOnly) {
			pattern = "\\b" + pattern + "\\b";
		}
		var re = new RegExp(pattern, flag);

		return this.each(function () {
			jQuery.highlight(this, re, settings.element, settings.className);
		});
	};
});
