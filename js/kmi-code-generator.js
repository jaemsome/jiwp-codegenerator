jQuery(document).ready(function($){
    var activeTabIndex = -1;
    var tabNames = [
        'configuration', 'pin_assignment', 'portb',
        'serial_port1', 'serial_port2', 'timer1', 'PWM', 'bit_banged_i2c',
        'i2c', 'main'
    ];
    
    function runToggleEffect() {
        $('#toggle_effect').toggle('fold');
        $('.btn_toggle').toggle();
    }
    
    runToggleEffect();
    
    // Show projects toggle effects
    $('.btn_toggle').click(function() {
        runToggleEffect();
    });

    $(".tab-menu > li").click(function(e){
        for(var i=0;i<tabNames.length;i++) {
            if(e.target.id == tabNames[i]) {
                activeTabIndex = i;
            } else {
                $("#"+tabNames[i]).removeClass("active");
                $("#"+tabNames[i]+"-tab").css("display", "none");
            }
        }
        $("#"+tabNames[activeTabIndex]+"-tab").fadeIn();
        $("#"+tabNames[activeTabIndex]).addClass("active");
        return false;
    });
    
    $.fn.extend({
        KMI_GenerateCode: function() {
            var data = {action: 'generate_code', name: $(this).attr('name')};
            
            // Function to be executed before calling the jQuery ajax method
            function BeforeAjax_Callback_Function(button, data) {
                var form_data = $('form#kmi_code_generator_form').serializeArray();
                // Assign new properties into the POST data
                // Set project processor type
                data.processor_type = $('select#project_processor_type').val();
                // Set form data
                data.form_data = form_data;
                
            }
            
            function Success_Callback_Function(response)
            {
                var txt_c_code = $('#txt_kmi_cg_c_code');
                var txt_h_code = $('#txt_kmi_cg_h_code');
                
                if(response.filename)
                {
                    // Delete previous download link
                    $('span#kmi_cg_download_link').remove();
                    
                    // Create new download link
                    var download_link = '<span id="kmi_cg_download_link">[ <a href="?download='+response.filename;
                    // Append included files
                    if(response.includes)
                        download_link += '&includes='+response.includes;
                    download_link += '">Download files</a> ]</span>';
                    
                    $('h1#kmi_cg_generated_file').append(download_link);
                }
                
                if(response.c)
                    txt_c_code.val(response.c);
                else
                    txt_c_code.val('');
                
                if(response.h)
                    txt_h_code.val(response.h);
                else
                    txt_h_code.val('');
            }
            
            KMI_AjaxClick($(this), data, $('form#kmi_code_generator_form'), BeforeAjax_Callback_Function, null, Success_Callback_Function);
        },
        KMI_ViewCGProject: function() {
            var data = {action: 'view_cgproject'};
            
            // Set project info into the POST data
            $(this).click(function(){
                data.project_info = $(this).attr('id');
                return false;
            });
            
            // Function to be executed before calling the jQuery ajax method
            function BeforeAjax_Callback_Function(button, data) {
                // Assign new properties into the POST data
            }
            
            function Success_Callback_Function(response)
            {
                if(response.cg_project)
                {
                    var i;
                    
                    var cg_project = response.cg_project;
                    
//                    // Set values to the project input fields
//                    if(cg_project.project)
//                    {
                        $('input[name="project[id]"]').val(cg_project.project.id).trigger('change');
                        $('input[name="project[name]"]').val(cg_project.project.name);
                        $('select[name="project[processor_type]"]').val(cg_project.project.processor_type);
                        $('input[name="project[frequency]"]').val(cg_project.project.frequency);
//                    }
//                    
//                    // Set values to the configuration input fields
//                    if(cg_project.configuration)
//                    {
                        var checkboxes = [
                            'JTAG', 'debug', 'watchDog', 'watchWin', 'GCP', 'GWRP',
                             'WDTPrescale', 'IESO', 'IOL1WAY', 'I2C1SEL', 'OSCIOFCN'
                        ];
                        
                        var dropdownlists = ['EMPin', 'WDTPostscaler', 'POSCMD', 'FNOSC', 'FCKSM'];
                        
                        for(i = 0; i < checkboxes.length; i++)
                        {
                            var cb = $('input[name="configuration['+checkboxes[i]+']"]');
                            
                            if(cg_project.configuration[checkboxes[i]] === 'yes')
                                cb.prop('checked', true);
                            else
                                cb.prop('checked', false);
                        }
                        
                        for(i = 0; i < dropdownlists.length; i++)
                        {
                            $('select[name="configuration['+dropdownlists[i]+']"]').val(cg_project.configuration[dropdownlists[i]]);
                        }
//                    }
//                    //Set values to the pin assignment input fields
//                    if(cg_project.pin_assignment)
//                    {
                        var input_fields = [
                            'INT1', 'INT2', 'IC1', 'IC2', 'IC3', 'IC4', 'IC5',
                            'OCFA', 'OCFB', 'U1RX', 'U2RX', 'U1CTS', 'U2CTS', 'SDI1',
                            'SDI2', 'SCK1IN', 'SCK2IN', 'SS1IN', 'SS2IN', 'T2CK', 'T3CK',
                            'T4CK', 'T5CK'
                        ];
                        
                        for(i = 0; i < input_fields.length; i++)
                        {
                            $('select[name="pin_assignment['+input_fields[i]+']"]').val(cg_project.pin_assignment[input_fields[i]]);
                        }
                        
                        for(i = 0; i < 17; i++)
                        {
                            $('select[name="pin_assignment[RP'+i+']"]').val(cg_project.pin_assignment['RP'+i]);
                        }
//                    }
//                    //Set values to the portb input fields
//                    if(cg_project.portb)
//                    {
                        for(i = 0; i < 16; i++)
                        {
                            var cb = $('input[name="portb[bit_'+[i]+']"]');
                            
                            if(cg_project.portb['bit_'+[i]] === 'yes')
                                cb.prop('checked', true);
                            else
                                cb.prop('checked', false);
                        }
//                    }
                    //Set values to the serial port input fields
                    // Textbox
                    var serial_port_txt_fields = ['desiredBR', 'constantBR'];
                    // Dropdown list
                    var serial_port_ddl_fields = ['dataBits', 'parity', 'stopBits', 'flowControl'];
                    // Checkbox
                    var serial_port_cb_fields = ['polarity', 'loopBack', 'autoBaud', 'IREnable', 'wake', 'RTSMode'];
                    
                    for(i = 1; i <= 2; i++)
                    {
//                        if(cg_project['serial_port'+i])
//                        {
                            var j;
                            
                            if(cg_project['serial_port'+i].BRGH === '0' || cg_project['serial_port'+i].BRGH === '1')
                                $('input[name="serial_port'+i+'[BRGH]"][value="'+cg_project['serial_port'+i].BRGH+'"]').prop('checked', true);
                            else
                                $('input[name="serial_port'+i+'[BRGH]"]').prop('checked', false);
                            
                            for(j = 0; j < serial_port_txt_fields.length; j++)
                            {
                                $('input[name="serial_port'+i+'['+serial_port_txt_fields[j]+']"]').val(cg_project['serial_port'+i][serial_port_txt_fields[j]]);
                            }
                            
                            for(j = 0; j < serial_port_ddl_fields.length; j++)
                            {
                                $('select[name="serial_port'+i+'['+serial_port_ddl_fields[j]+']"]').val(cg_project['serial_port'+i][serial_port_ddl_fields[j]]);
                            }
                            
                            for(j = 0; j < serial_port_cb_fields.length; j++)
                            {
                                var cb = $('input[name="serial_port'+i+'['+serial_port_cb_fields[j]+']"]');
                                
                                if(cg_project['serial_port'+i][serial_port_cb_fields[j]] === 'yes')
                                    cb.prop('checked', true);
                                else
                                    cb.prop('checked', false);
                            }
//                        }
                    }
                    //Set values to the timer1 input fields
//                    if(cg_project.timer1)
//                    {
                        $('input[name="timer1[interrupt_number]"]').val(cg_project.timer1.interrupt_number);
                        $('input[name="timer1[reload_value]"]').val(cg_project.timer1.reload_value);
                        $('textarea[name="timer1[t1_includes]"]').val(cg_project.timer1.t1_includes);
//                    }
                    //Set values to the pwm timer input fields
                    for(i = 2; i <= 3; i++)
                    {
//                        if(cg_project['pwm_timer'+i])
//                        {
                            $('input[name="pwm_timer'+i+'[pwm_period]"]').val(cg_project['pwm_timer'+i].pwm_period);
                            $('input[name="pwm_timer'+i+'[pr_value]"]').val(cg_project['pwm_timer'+i].pr_value);
                            $('select[name="pwm_timer'+i+'[timer_prescale]"]').val(cg_project['pwm_timer'+i].timer_prescale);
//                        }
                    }
                    //Set values to the pwm OC input fields
                    for(i = 1; i <= 5; i++)
                    {
//                        if(cg_project['pwm_oc'+i])
//                        {
                            if(cg_project['pwm_oc'+i].timer === '2' || cg_project['pwm_oc'+i].timer === '3')
                                $('input[name="pwm_oc'+i+'[timer]"][value="'+cg_project['pwm_oc'+i].timer+'"]').prop('checked', true);
                            else
                                $('input[name="pwm_oc'+i+'[timer]"]').prop('checked', false);
                            
                            if(cg_project['pwm_oc'+i].single_continuous === '1' || cg_project['pwm_oc'+i].single_continuous === '2')
                                $('input[name="pwm_oc'+i+'[single_continuous]"][value="'+cg_project['pwm_oc'+i].single_continuous+'"]').prop('checked', true);
                            else
                                $('input[name="pwm_oc'+i+'[single_continuous]"]').prop('checked', false);
//                        }
                    }
//                    //Set values to the bit banged i2c input fields
//                    if(cg_project.bit_banged_i2c)
//                    {
                        var ddl_fields = ['scl_port', 'scl_bit', 'sda_port', 'sda_bit'];
                        
                        for(i = 0; i < ddl_fields.length; i++)
                        {
                            $('select[name="bit_banged_i2c['+ddl_fields[i]+']"]').val(cg_project.bit_banged_i2c[ddl_fields[i]]);
                        }
//                    }
//                    //Set values to the main input fields
//                    if(cg_project.main)
//                    {
                        var cb_fields = [
                            'configuration', 'pin_assignment', 'port_bits', 'serial_port1',
                            'serial_port2', 'timer1', 'PWM', 'bit_banged_I2C', 'I2C', 'SSRTOS'
                        ];
                        
                        for(i = 0; i < cb_fields.length; i++)
                        {
                            var cb = $('input[name="main['+cb_fields[i]+']"]');
                            
                            if(cg_project.main[cb_fields[i]] === 'yes')
                                cb.prop('checked', true);
                            else
                                cb.prop('checked', false);
                        }
//                    }
                }
            }
            
            KMI_AjaxClick($(this), data, $('form#kmi_code_generator_form'), BeforeAjax_Callback_Function, null, Success_Callback_Function);
        },
        KMI_DeleteCGProject: function() {
            var data = {action: 'delete_cgproject'};
            
            // Set project info into the POST data
            $(this).click(function(){
                data.project_info = $(this).attr('id');
            });
            
            function Success_Callback_Function(response) {
                // CG Project successfully deleted 
                if(response.success) {
                    //Remove the deleted CG Project item
                    $('table#kmi_cg_project_list tbody tr#cg_project_'+response.cg_project).remove();
                    
                    // If no more CG project items left, add an empty row
                    if($('table#kmi_cg_project_list tbody tr:last').index() <= 0)
                        $('table#kmi_cg_project_list tbody').append('<tr id="kmi_cg_empty_row"><td class="align-center bold" colspan="2">No projects found.</td></tr>');
                    
                    // Repaginate CG Project table list
                    $('table#kmi_cg_project_list').KMI_CG_TablePagination();
                }
            }
            
            KMI_AjaxClick($(this), data, $('form#kmi_code_generator_form'), null, null, Success_Callback_Function);
        },
        KMI_SaveCGProject: function() {
            var data = {action: 'save_cgproject'};
            
            // Function to be executed before calling the jQuery ajax method
            function BeforeAjax_Callback_Function(button, data) {
                // Assign new properties into the POST data
                var form_data = $('form#kmi_code_generator_form').serializeArray();
                // Set form data
                data.form_data = form_data;
            }
            
            function Success_Callback_Function(response)
            {
                // Reset all input fields in the code generator form
                $('form#kmi_code_generator_form').trigger('reset');
                
                // If new CG Project been created
                if(response.cg_project)
                {
                    // CG Project table
                    var cg_project_list = $('table#kmi_cg_project_list tbody');
                    // CG Project list empty row
                    var cg_project_list_empty_row = $('table#kmi_cg_project_list tbody tr#kmi_cg_empty_row');
                    // New CG Project item
                    var cg_project_item = '<tr id="cg_project_'+response.cg_project.id+'"><td class="align-center bold">'+response.cg_project.name+'</td>';
                    cg_project_item += '<td class="align-center">';
                    cg_project_item += '<a href="?cg_project='+response.cg_project.id+'&action=view" class="dashicons dashicons-media-spreadsheet no-text-decoration btn-kmi-cg-view-project-new" id="view_cgproject_'+response.cg_project.id+'" alt="View project" title="View project"></a>&nbsp;';
                    cg_project_item += '<a href="?cg_project='+response.cg_project.id+'&action=delete" class="dashicons dashicons-trash no-text-decoration btn-kmi-cg-delete-project-new" id="delete_cgproject_'+response.cg_project.id+'" alt="Delete project" title="Delete project"></a>';
                    cg_project_item += '</td></tr>';
                    
                    // If an empty row exists, remove it
                    if(cg_project_list_empty_row)
                        cg_project_list_empty_row.remove();
                    
                    // Add the new CG project item into the list
                    cg_project_list.append(cg_project_item);
                    
                    // Apply the KMI view function to the newly added CG Projects
                    $('.btn-kmi-cg-view-project-new').KMI_ViewCGProject();
                    // Apply the KMI delete function to the newly added CG Projects
                    $('.btn-kmi-cg-delete-project-new').KMI_DeleteCGProject();
                    // Repaginate CG Project table list
                    $('table#kmi_cg_project_list').KMI_CG_TablePagination();
                }
            }
            
            KMI_AjaxClick($(this), data, $('form#kmi_code_generator_form'), BeforeAjax_Callback_Function, null, Success_Callback_Function);
        },
        KMI_CG_CalculateBaudRate: function() {
            var data = {action: 'calculate_baudrate'};
            
            // Set project info into the POST data
            $(this).click(function(){
                var btn_calculateBR = $(this).attr('id');
                
                if(btn_calculateBR.indexOf('serial_port1') >= 0) {
                    data.serial_port = '1';
                }
                else if(btn_calculateBR.indexOf('serial_port2') >= 0) {
                    data.serial_port = '2';
                }
                
                // Set frequency value
                data.frequency = $('input[name="project[frequency]"]').val();
                
                if(data.serial_port)
                {
                    // Set BRGH value
                    data.BRGH = $('input[name="serial_port'+data.serial_port+'[BRGH]"]:checked').val();
                    // Set desired baud rate value
                    data.desiredBR = $('input[name="serial_port'+data.serial_port+'[desiredBR]"]').val();
                }
                
                return false;
            });
            
            // Function to be executed before calling the jQuery ajax method
            function BeforeAjax_Callback_Function(button, data) {
                // Assign new properties into the POST data
            }
            
            function Success_Callback_Function(response) {
                if(response.constant_baud_rate && response.tab)
                {
                    $('input[name="'+response.tab+'[constantBR]"]').val(response.constant_baud_rate);
                }
            }
            
            KMI_AjaxClick($(this), data, $('form#kmi_code_generator_form'), BeforeAjax_Callback_Function, null, Success_Callback_Function);
        },
        KMI_CG_CalculateReload: function() {
            var data = {action: 'calculate_reload'};
            
            // Set project info into the POST data
            $(this).click(function(){
                // Set frequency value
                data.frequency = $('input[name="project[frequency]"]').val();
                // Set interrupt number
                data.interrupt_number = $('input[name="timer1[interrupt_number]"]').val();
                
                return false;
            });
            
            // Function to be executed before calling the jQuery ajax method
            function BeforeAjax_Callback_Function(button, data) {
                // Assign new properties into the POST data
            }
            
            function Success_Callback_Function(response) {
                if(response.reload_value && response.tab)
                {
                    $('input[name="'+response.tab+'[reload_value]"]').val(response.reload_value);
                }
            }
            
            KMI_AjaxClick($(this), data, $('form#kmi_code_generator_form'), BeforeAjax_Callback_Function, null, Success_Callback_Function);
        }
    });
    
    var kmi_cg_project_id = $('input[name="project[id]"]');
    
    $('.btn-kmi-reset-form').on('click', function(){
        kmi_cg_project_id.val('').trigger('change');
        $('form#kmi_code_generator_form').trigger('reset');
    });
    
    kmi_cg_project_id.on('change', function(){
        var value = parseInt($(this).val());
        var btn_save = $('input[name="save_project_code"]');
        
        if(value > 0 && value !== 'NaN')
            btn_save.val('Update Project');
        else
            btn_save.val('Add Project');
    });
    
    $('#btn_generate_configuration_code').KMI_GenerateCode();
    $('#btn_generate_pin_assignment_code').KMI_GenerateCode();
    $('#btn_generate_serial_port1_code').KMI_GenerateCode();
    $('#btn_generate_serial_port2_code').KMI_GenerateCode();
    $('#btn_generate_timer1_code').KMI_GenerateCode();
    $('#btn_generate_pwm_timer2_code').KMI_GenerateCode();
    $('#btn_generate_pwm_timer3_code').KMI_GenerateCode();
    $('#btn_generate_pwm_oc1_code').KMI_GenerateCode();
    $('#btn_generate_pwm_oc2_code').KMI_GenerateCode();
    $('#btn_generate_pwm_oc3_code').KMI_GenerateCode();
    $('#btn_generate_pwm_oc4_code').KMI_GenerateCode();
    $('#btn_generate_pwm_oc5_code').KMI_GenerateCode();
    $('#btn_generate_bit_banged_i2c_code').KMI_GenerateCode();
    $('#btn_generate_main_code').KMI_GenerateCode();
    $('input[name="save_project_code"]').KMI_SaveCGProject();
    $('.btn-kmi-cg-view-project').KMI_ViewCGProject();
    $('.btn-kmi-cg-delete-project').KMI_DeleteCGProject();
    $('.btn-kmi-cg-calculate-baud-rate').KMI_CG_CalculateBaudRate();
    $('#timer1_calculateReload').KMI_CG_CalculateReload();
    $('table#kmi_cg_project_list').KMI_TableList_Pagination();
});