jQuery(document).ready(function($){
    var $kmi_codegenerator_form = $('form#kmi_codegenerator_form');
    
    $('input[type="submit"]').on('click', function(e){
        e.preventDefault();
        
        var data = {action: 'kmicodegenerator_update_controller_files'};
        data.configuration_controller_code = encodeURIComponent($('textarea#kmi_codegenerator_configurationcontroller_code').val());
        data.pinassignment_controller_code = encodeURIComponent($('textarea#kmi_codegenerator_pinassignmentcontroller_code').val());
        data.serialport_controller_code = encodeURIComponent($('textarea#kmi_codegenerator_serialportcontroller_code').val());
        data.timer1_controller_code = encodeURIComponent($('textarea#kmi_codegenerator_timer1controller_code').val());
        data.pwmtimer_controller_code = encodeURIComponent($('textarea#kmi_codegenerator_pwmtimercontroller_code').val());
        data.pwmoc_controller_code = encodeURIComponent($('textarea#kmi_codegenerator_pwmoccontroller_code').val());
        data.bitbangedi2c_controller_code = encodeURIComponent($('textarea#kmi_codegenerator_bitbangedi2ccontroller_code').val());
        data.main_controller_code = encodeURIComponent($('textarea#kmi_codegenerator_maincontroller_code').val());
        
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            dataType: 'json',
            data: data,
            success: function(response) {
                // Delete previous message
                $('p.kmi-message').remove();
                
                if(response.error) {
                    // Add error message
                    $kmi_codegenerator_form.prepend('<p class="error kmi-message">'+response.error+'</p>');
                } else if(response.success) {
                    // Add success message
                    $kmi_codegenerator_form.prepend('<p class="success kmi-message">'+response.success+'</p>');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log('ERRORS: '+ textStatus);
            }
        });
    });
});