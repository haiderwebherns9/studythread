jQuery(document).ready(function($){

    jQuery(".wpj-notification-unread input[type='checkbox'], .wpj-notification-read input[type='checkbox']").on('change', function() {
        jQuery(this).closest('.wpj-notification-unread, .wpj-notification-read').toggleClass('box-checked', this.checked)
    });

    jQuery("#chk-all-notify").on('click', function(){
        var checkedStatus = this.checked;
        jQuery('.chk-notify').each(function () {
            jQuery(this).prop('checked', checkedStatus);
            if(checkedStatus){
                jQuery('.mark').text(_notifications_settings.uncheck_all);
            }else{
                jQuery('.mark').text(_notifications_settings.check_all);
            }
        });
        jQuery('ul.wpj-all-notifications-style > li').each(function () {
            jQuery(this).toggleClass('box-checked', this.checked)
        });

    });

    $('#more_notifications_handler').on('click', function(event){
        event.preventDefault();

        var notifications_handler = $("#more_notifications_handler");
        var notifications_target = $("#more_notifications_target");

        var more_text = notifications_handler.data('more-text');
        var loading_text = notifications_handler.data('loading-text');

        var limit = notifications_handler.data('limit');
        var offset = notifications_handler.data('offset');


        $.ajax({
            type: "POST",
            url: _notifications_settings.ajaxurl,
            data: {
                action: 'wpjobster_ajax_notifications',
                limit: limit,
                offset: offset,
                is_ajax: true,
            },
            beforeSend : function () {
                notifications_handler.attr('disabled', true);
                notifications_handler.addClass('loading').html(loading_text);

                notifications_handler.data('offset', offset + limit);
            },
            success: function(data){

                var notifications = $.parseJSON(data);
                if (notifications.count > 0) {

                    $(notifications.content).hide().appendTo(notifications_target).slideDown(400);

                    notifications_handler.removeClass('loading').html(more_text);

                    if (notifications.count < limit) {
                        notifications_handler.slideUp(400);
                    }

                } else {
                    notifications_handler.hide();
                }

                jQuery(".wpj-notification-unread input[type='checkbox'], .wpj-notification-read input[type='checkbox']").on('change', function() {
                    jQuery(this).closest('.wpj-notification-unread, .wpj-notification-read').toggleClass('box-checked', this.checked)
                });

            },
            error: function(jqXHR, textStatus, errorThrown) {
                notifications_handler.html(jqXHR + " :: " + textStatus + " :: " + errorThrown);
            }
        });
        // return false;
    });

});
