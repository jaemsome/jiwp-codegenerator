function KMI_AjaxClick(button, data, message_container, BeforeAjax_Callback_Function, BeforeSend_Callback_Function, Success_Callback_Function)
{
    button.click(function(){
        // Cancel event if already disabled
        if(button.hasClass('disabled')) return false;
        
        if(BeforeAjax_Callback_Function)
            BeforeAjax_Callback_Function(button, data);
        
        jQuery.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: data,
            beforeSend: function() {
                // Disable the button while processing
                button.addClass('disabled');
                
                if(BeforeSend_Callback_Function)
                    BeforeSend_Callback_Function(button);
            },
            success: function(response) {
                // Delete previous message
                jQuery('p.kmi-message').remove();
                
                // Check for the message container if it exists
                if(message_container) {
                    if(response.success) {
                        ajax_response = response.success;
                        // Add success message
                        message_container.prepend('<p class="success kmi-message">'+response.success+'</p>');
                    } else if(response.error) {
                        // Add error message
                        message_container.prepend('<p class="error kmi-message">'+response.error+'</p>');
                    }
                }
                // Enable the button
                button.removeClass('disabled');
                
                if(Success_Callback_Function)
                    Success_Callback_Function(response);
            },
            error: function(xhr, error) {
                // Enable the button
                button.removeClass('disabled');
            }
        });
        // Cancel the event, no need to process by the server
        return false;
    });
}

jQuery(document).ready(function($){
    $.fn.extend({
        KMI_TableList_Pagination: function() {
            $(this).each(function() {
                var currentPage = 0;
                var numPerPage = 3;
                var $table = $(this);

                $table.bind('repaginate', function() {
                    $table.find('tbody tr').hide().slice(currentPage * numPerPage, (currentPage + 1) * numPerPage).show();
                });

                $table.trigger('repaginate');

                var $old_pager = $('div.pager');

                if($old_pager.length)
                    $old_pager.remove();

                var numRows = $table.find('tbody tr').length;
                var numPages = Math.ceil(numRows / numPerPage);
                var $pager = $('<div class="pager"></div>');

                for(var page = 0; page < numPages; page++) {
                    $('<span class="page-number"></span>').text(page + 1).bind('click', {
                        newPage: page
                    }, function(event) {
                        currentPage = event.data['newPage'];
                        $table.trigger('repaginate');
                        $(this).addClass('active').siblings().removeClass('active');
                    }).appendTo($pager).addClass('clickable');
                }
                $pager.insertAfter($table).find('span.page-number:first').addClass('active');
            });
        }
    });
});