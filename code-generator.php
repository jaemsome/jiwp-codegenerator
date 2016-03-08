<?php

if(!defined('ABSPATH')) exit; // Exit if accessed directly

class KMI_CodeGenerator
{
    // Public variables
    public $general_settings = array();
    // Private variables
    private $__message = array();
    private $__code = array();
    private $__calculated_values = array();
    private $__model_type; // For multiple model types i.e. OC1, OC2, OC3
    private $__var_arr = array();
    private $__general_settings_key = 'kmi_codegenerator_general_settings';
    private $__settings_key = array('general'=>'kmi_codegenerator_general_settings', 'controller'=>'kmi_codegenerator_controller_settings');
    private $__plugin_options_key = 'kmi_codegenerator_menu_option';
    // Directories
    private $__controllers_dir = '';
    private $__plugin_settings_tabs = array();
    // Code Generator controllers
    private $__CGProjectController;
    private $__CGConfigurationController;
    private $__CGPinAssignmentController;
    private $__CGSerialPortController;
    private $__CGTimer1Controller;
    private $__CGPWMTimerController;
    private $__CGPWMOCController;
    private $__CGBitBangedI2CController;
    private $__CGMainController;
    
    public function __construct()
    {
        // Instantiate all code generator controllers
        $this->__CGProjectController = new CG_ProjectController();
        $this->__CGConfigurationController = new CG_ConfigurationController('configuration');
        $this->__CGPinAssignmentController = new CG_PinAssignmentController('pin_assignment');
        $this->__CGSerialPortController = new CG_SerialPortController('serial_port');
        $this->__CGTimer1Controller = new CG_Timer1Controller('timer1');
        $this->__CGPWMTimerController = new CG_PWMTimerController('pwm_timer');
        $this->__CGPWMOCController = new CG_PWMOCController('pwm_oc');
        $this->__CGBitBangedI2CController = new CG_BitBangedI2CController('bit_banged_i2c');
        $this->__CGMainController = new CG_MainController('main');
        // Assign controllers directory
        $this->__controllers_dir = plugin_dir_path(__FILE__).'controllers'.DIRECTORY_SEPARATOR;
        // Add all shortocodes
        $this->_Add_Shortcodes();
        // Add all Filters
        $this->_Add_Filters();
        // Add all Actions
        $this->_Add_Actions();
    }
    
    /*
     * Intercepts pre get post request to handle
     * the downloading of file
     */
    public function Download_File()
    {
        if(!empty($_GET['download']))
        {
            $path = plugin_dir_path(__FILE__).'generated-files/';
            $C_path = $path.basename($_GET['download']).'.c';
            $H_path = $path.basename($_GET['download']).'.h';

            $zipName = basename($_GET['download']).'.zip';
            $zip = new ZipArchive();
            $zip->open($zipName, 1?ZIPARCHIVE::OVERWRITE:ZIPARCHIVE::CREATE===TRUE);

            if(file_exists($path.'included-files/library.X.a'))
                $zip->addFile($path.'included-files/library.X.a', 'library.X.a');

    //        if(file_exists($path.'included-files/ssrtos.h'))
    //            $zip->addFile($path.'included-files/ssrtos.h', 'ssrtos.h');

            if(file_exists($C_path))
                $zip->addFile($C_path, basename($_GET['download']).'.c');

            if(file_exists($H_path))
                $zip->addFile($H_path, basename($_GET['download']).'.h');

            if(!empty($_GET['includes']))
            {
                $includedFiles = explode(',', $_GET['includes']);
                foreach($includedFiles as $filename)
                {
                    $filename = trim($filename);
                    $pathFilename = $path.$filename;

                    if($filename == 'ssrtos')
                    {
                        if(file_exists($path.'included-files/ssrtos.h'))
                            $zip->addFile($path.'included-files/ssrtos.h', 'ssrtos.h');
                    }

                    if(file_exists("{$pathFilename}.c"))
                        $zip->addFile("{$pathFilename}.c", "{$filename}.c");

                    if(file_exists("{$pathFilename}.h"))
                        $zip->addFile("{$pathFilename}.h", "{$filename}.h");
                }
            }
            $zip->close();

            header('Content-Description: File Transfer');
            header('Content-Type: application/zip'); // removed(octet-stream)
            header('Content-Disposition: attachment; filename='.$zipName); // removed(basename($C_path))
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: '.filesize($zipName)); // removed($C_path)
    //            ob_clean();
    //            flush();
            readfile($zipName); // removed($C_path)
            exit();
        }
    }
    
    /*
     * Setup all CSS and JS files for the code generator UI
     */
    public function Add_Front_End_Styles_And_Scripts()
    {
        if(!wp_style_is('kmi_global_style', 'registered'))
        {
            wp_register_style('kmi_global_style', plugins_url('css/kmi-global.css', __FILE__));
        }
        
        if(!wp_style_is('kmi_global_style', 'enqueued'))
        {
            wp_enqueue_style('kmi_global_style');
        }
        
        if(!wp_style_is('kmi_code_generator_style', 'registered'))
        {
            wp_register_style('kmi_code_generator_style', plugins_url('css/kmi-code-generator.css', __FILE__));
        }
        
        if(!wp_style_is('kmi_code_generator_style', 'enqueued'))
        {
            wp_enqueue_style('kmi_code_generator_style');
        }
        
        if(!wp_script_is('kmi_global_script', 'registered'))
        {
            // Register the script that contains the kmi global functions
            wp_register_script('kmi_global_script', plugins_url('js/kmi-global.js', __FILE__), false, false, true);
        }
        
        if(!wp_script_is('kmi_code_generator_script', 'registered'))
        {
            // Register the script used for the code generator form
            wp_register_script('kmi_code_generator_script', plugins_url('js/kmi-code-generator.js', __FILE__), array('jquery', 'kmi_global_script'), false, true);
        }
        
        if(!wp_script_is('kmi_code_generator_script', 'enqueued'))
        {
            // Enqueue the code generator script
            wp_enqueue_script('kmi_code_generator_script');
        }
        
        wp_localize_script('kmi_global_script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
    }
    
    public function Add_Admin_Option_Page()
    {
        if(empty($GLOBALS['admin_page_hooks']['kmi_menu_options']))
            add_menu_page('KMI Options', 'KMI Options', 'manage_options', 'kmi_menu_options', array($this, 'KMI_Options_Page'));
        
        if(empty($GLOBALS['admin_page_hooks'][$this->__plugin_options_key]))
        {
            $option_page = add_submenu_page('kmi_menu_options', 'KMI Code Generator', 'Code Generator', 'manage_options', $this->__plugin_options_key, array($this, 'CodeGenerator_Option_Page'));
            // Add css to the option page
            add_action('admin_print_styles-'.$option_page, array($this, 'Add_Option_Page_Styles'));
            // Add javascript to the option page
            add_action('admin_print_scripts-'.$option_page, array($this, 'Add_Option_Page_Scripts'));
        }
    }
    
    /*
     * KMI option page UI
     */
    public function KMI_Options_Page()
    {
        ?>
        <div class="wrap">
            <h2>Welcome to KMI Technology plugins. You can select the items under this menu to edit the desired plugin's settings.</h2>
        </div>
        <?php
    }
    
    /*
     * Code Generator option page
     */
    public function CodeGenerator_Option_Page()
    {
        ?>
        <div class="wrap">
            <?php $this->__Plugin_Options_Tabs(); ?>
            <form id="kmi_codegenerator_form" method="POST" action="" enctype="multipart/form-data">
                <?php if(!empty($this->__message['error'])): ?>
                    <p class="error kmi-message"><?php echo $this->__SetMessages($this->__message['error']); ?></p>
                <?php elseif(!empty($this->__message['success'])): ?>
                    <p class="success kmi-message"><?php echo $this->__SetMessages($this->__message['success']); ?></p>
                <?php endif; ?>
                <input type="hidden" name="kmi_codegenerator" value="true" />
                <?php
                    settings_fields($this->__settings_key['controller']);
                    
                    do_settings_sections($this->__settings_key['controller']);
                    
                    submit_button();
                ?>
            </form>
        </div>
        <?php
    }
    
    /*
     * Adding css for the option page
     */
    public function Add_Option_Page_Styles()
    {
        if(!wp_style_is('kmi_global_style', 'registered'))
        {
            wp_register_style('kmi_global_style', plugins_url('css/kmi-global.css', __FILE__));
        }
        
        if(!wp_style_is('kmi_global_style', 'enqueued'))
        {
            wp_enqueue_style('kmi_global_style');
        }
    }
    
    /*
     * Adding scripts for the option page
     */
    public function Add_Option_Page_Scripts()
    {
        if(!wp_script_is('kmi_global_script', 'registered'))
        {
            // Register the script that contains the kmi global functions
            wp_register_script('kmi_global_script', plugins_url('js/kmi-global.js', __FILE__), array('jquery'), false, true);
        }
        
        if(!wp_script_is('kmi_codegenerator_script', 'registered'))
        {
            // Register the script for the plugin
            wp_register_script('kmi_codegenerator_script', plugins_url('js/kmi-codegenerator-admin.js', __FILE__), array('kmi_global_script'), false, true);
        }
        
        if(!wp_script_is('kmi_codegenerator_script', 'enqueued'))
        {
            // Enqueue the plugin script
            wp_enqueue_script('kmi_codegenerator_script');
        }
        
        // Ajax object variables
        $ajax_obj_variables_arr = array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'option_page' => $this->__plugin_options_key,
            'ajax_loader' => get_site_url().'/wp-admin/images/loading.gif'
        );
        wp_localize_script('kmi_codegenerator_script', 'ajax_object', $ajax_obj_variables_arr);
    }
    
    public function Register_Admin_Option_Settings()
    {
        // Register generation code settings tab
        $this->__plugin_settings_tabs[$this->__settings_key['controller']] = 'Controllers';
        register_setting($this->__settings_key['controller'], $this->__settings_key['controller'], array($this, 'Sanitize_Controller_Settings'));
        
        // Add configuration controller section
        add_settings_section('kmi_codegenerator_configuration_controller', 'Configuration Controller', array($this, 'ControllerSection_Description'), $this->__settings_key['controller']);
        // Add fields to the section
        add_settings_field('kmi_codegenerator_configuration_controller_code', 'Content:', array($this, 'Display_ConfigurationController_Code_Field'), $this->__settings_key['controller'], 'kmi_codegenerator_configuration_controller');
        
        // Add pin assignment controller section
        add_settings_section('kmi_codegenerator_pinassignment_controller', 'Pin Assignment Controller', array($this, 'ControllerSection_Description'), $this->__settings_key['controller']);
        // Add fields to the section
        add_settings_field('kmi_codegenerator_pinassignment_controller_code', 'Content:', array($this, 'Display_PinAssignmentController_Code_Field'), $this->__settings_key['controller'], 'kmi_codegenerator_pinassignment_controller');
        
        // Add serial port generation code section
        add_settings_section('kmi_codegenerator_serialport_controller', 'Serial Port Controller', array($this, 'ControllerSection_Description'), $this->__settings_key['controller']);
        // Add fields to the section
        add_settings_field('kmi_codegenerator_serialport_controller_code', 'Content: ', array($this, 'Display_SerialPortController_Code_Field'), $this->__settings_key['controller'], 'kmi_codegenerator_serialport_controller');
        
        // Add timer1 generation code section
        add_settings_section('kmi_codegenerator_timer1_controller', 'Timer1 Controller', array($this, 'ControllerSection_Description'), $this->__settings_key['controller']);
        // Add fields to the section
        add_settings_field('kmi_codegenerator_timer1_controller_code', 'Content: ', array($this, 'Display_Timer1Controller_Code_Field'), $this->__settings_key['controller'], 'kmi_codegenerator_timer1_controller');
        
        // Add pwm timer generation code section
        add_settings_section('kmi_codegenerator_pwmtimer_controller', 'PWM Timer Controller', array($this, 'ControllerSection_Description'), $this->__settings_key['controller']);
        // Add fields to the section
        add_settings_field('kmi_codegenerator_pwmtimer_controller_code', 'Content: ', array($this, 'Display_PWMTimerController_Code_Field'), $this->__settings_key['controller'], 'kmi_codegenerator_pwmtimer_controller');
        
        // Add pwm oc generation code section
        add_settings_section('kmi_codegenerator_pwmoc_controller', 'PWM OC Controller', array($this, 'ControllerSection_Description'), $this->__settings_key['controller']);
        // Add fields to the section
        add_settings_field('kmi_codegenerator_pwmoc_controller_code', 'Content: ', array($this, 'Display_PWMOCController_Code_Field'), $this->__settings_key['controller'], 'kmi_codegenerator_pwmoc_controller');
        
        // Add bitbanged I2C generation code section
        add_settings_section('kmi_codegenerator_bitbangedi2c_controller', 'BitBangedI2C Controller', array($this, 'ControllerSection_Description'), $this->__settings_key['controller']);
        // Add fields to the section
        add_settings_field('kmi_codegenerator_bitbangedi2c_controller_code', 'Content: ', array($this, 'Display_BitBangedI2CController_Code_Field'), $this->__settings_key['controller'], 'kmi_codegenerator_bitbangedi2c_controller');
        
        // Add main generation code section
        add_settings_section('kmi_codegenerator_main_controller', 'Main Controller', array($this, 'ControllerSection_Description'), $this->__settings_key['controller']);
        // Add fields to the section
        add_settings_field('kmi_codegenerator_main_controller_code', 'Content: ', array($this, 'Display_MainController_Code_Field'), $this->__settings_key['controller'], 'kmi_codegenerator_main_controller');
        
        $generationcode_controllers = array(
            'configuration', 'pin_assignment', 'serial_port', 'timer1',
            'pwm_timer', 'pwm_oc', 'bit_banged_i2c', 'main'
        );
        
        // Submit form
        if(isset($_POST['submit']))
        {
            if($_GET['page'] === $this->__plugin_options_key)
            {
                // Configuration
                if(isset($_POST[$this->__settings_key['controller']]['configuration_code']))
                {
                    $content = str_replace("\r\n", PHP_EOL, stripslashes($_POST[$this->__settings_key['controller']]['configuration_code']));
                    file_put_contents($this->__controllers_dir.'CG_ConfigurationController.php', $content);
                    $this->__message['success']['configuration'] = 'Controller file has been changed.';
                }
                // Pin Assignment
                if(isset($_POST[$this->__settings_key['controller']]['pinassignment_code']))
                {
                    $content = str_replace("\r\n", PHP_EOL, stripslashes($_POST[$this->__settings_key['controller']]['pinassignment_code']));
                    file_put_contents($this->__controllers_dir.'CG_PinAssignmentController.php', $content);
                    $this->__message['success']['pin_assignment'] = 'Controller file has been changed.';
                }
                // Serial Port
                if(isset($_POST[$this->__settings_key['controller']]['serialport_code']))
                {
                    $content = str_replace("\r\n", PHP_EOL, stripslashes($_POST[$this->__settings_key['controller']]['serialport_code']));
                    file_put_contents($this->__controllers_dir.'CG_SerialPortController.php', $content);
                    $this->__message['success']['serial_port'] = 'Controller file has been changed.';
                }
                // Timer1
                if(isset($_POST[$this->__settings_key['controller']]['timer1_code']))
                {
                    $content = str_replace("\r\n", PHP_EOL, stripslashes($_POST[$this->__settings_key['controller']]['timer1_code']));
                    file_put_contents($this->__controllers_dir.'CG_Timer1Controller.php', $content);
                    $this->__message['success']['timer1'] = 'Controller file has been changed.';
                }
                // PWM Timer
                if(isset($_POST[$this->__settings_key['controller']]['pwmtimer_code']))
                {
                    $content = str_replace("\r\n", PHP_EOL, stripslashes($_POST[$this->__settings_key['controller']]['pwmtimer_code']));
                    file_put_contents($this->__controllers_dir.'CG_PWMTimerController.php', $content);
                    $this->__message['success']['PWM_timer'] = 'Controller file has been changed.';
                }
                // PWM OC
                if(isset($_POST[$this->__settings_key['controller']]['pwmoc_code']))
                {
                    $content = str_replace("\r\n", PHP_EOL, stripslashes($_POST[$this->__settings_key['controller']]['pwmoc_code']));
                    file_put_contents($this->__controllers_dir.'CG_PWMOCController.php', $content);
                    $this->__message['success']['PWM_OC'] = 'Controller file has been changed.';
                }
                // Bit Banged I2C
                if(isset($_POST[$this->__settings_key['controller']]['bitbangedi2c_code']))
                {
                    $content = str_replace("\r\n", PHP_EOL, stripslashes($_POST[$this->__settings_key['controller']]['bitbangedi2c_code']));
                    file_put_contents($this->__controllers_dir.'CG_BitBangedI2CController.php', $content);
                    $this->__message['success']['bit_banged_I2C'] = 'Controller file has been changed.';
                }
                // Main
                if(isset($_POST[$this->__settings_key['controller']]['main_code']))
                {
                    $content = str_replace("\r\n", PHP_EOL, stripslashes($_POST[$this->__settings_key['controller']]['main_code']));
                    file_put_contents($this->__controllers_dir.'CG_MainController.php', $content);
                    $this->__message['success']['main'] = 'Controller file has been changed.';
                }
            }
        }
        
        foreach($generationcode_controllers as $controller)
        {
            switch(strtolower($controller))
            {
                case 'configuration':
                    $this->__var_arr['controller'][$controller] = file($this->__controllers_dir.'CG_ConfigurationController.php');
                    break;
                case 'pin_assignment':
                    $this->__var_arr['controller'][$controller] = file($this->__controllers_dir.'CG_PinAssignmentController.php');
                    break;
                case 'serial_port':
                    $this->__var_arr['controller'][$controller] = file($this->__controllers_dir.'CG_SerialPortController.php');
                    break;
                case 'timer1':
                    $this->__var_arr['controller'][$controller] = file($this->__controllers_dir.'CG_Timer1Controller.php');
                    break;
                case 'pwm_timer':
                    $this->__var_arr['controller'][$controller] = file($this->__controllers_dir.'CG_PWMTimerController.php');
                    break;
                case 'pwm_oc':
                    $this->__var_arr['controller'][$controller] = file($this->__controllers_dir.'CG_PWMOCController.php');
                    break;
                case 'bit_banged_i2c':
                    $this->__var_arr['controller'][$controller] = file($this->__controllers_dir.'CG_BitBangedI2CController.php');
                    break;
                case 'main':
                    $this->__var_arr['controller'][$controller] = file($this->__controllers_dir.'CG_MainController.php');
                    break;
            }
        }
    }
    
    /*
     * Validating general settings field data
     */
    public function Sanitize_Controller_Settings($input)
    {
        $new_input = array();
        
        $current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : $this->__settings_key['controller'];
        
        switch($current_tab)
        {
            case 'kmi_codegenerator_controller_settings':
                if(isset($input['configuration_code']))
                    $new_input['configuration_code'] = esc_attr($input['configuration_code']);
                
                if(isset($input['pinassignment_code']))
                    $new_input['pinassignment_code'] = esc_attr($input['pinassignment_code']);
                
                if(isset($input['serialport_code']))
                    $new_input['serialport_code'] = esc_attr($input['serialport_code']);
                
                if(isset($input['timer1_code']))
                    $new_input['timer1_code'] = esc_attr($input['timer1_code']);
                
                if(isset($input['pwmtimer_code']))
                    $new_input['pwmtimer_code'] = esc_attr($input['pwmtimer_code']);
                
                if(isset($input['pwmoc_code']))
                    $new_input['pwmoc_code'] = esc_attr($input['pwmoc_code']);
                
                if(isset($input['bitbangedi2c_code']))
                    $new_input['bitbangedi2c_code'] = esc_attr($input['bitbangedi2c_code']);
                
                if(isset($input['main_code']))
                    $new_input['main_code'] = esc_attr($input['main_code']);
                
                break;
        }

        return $new_input;
    }
    
    /*
     * General settings section
     */
    public function ControllerSection_Description() { echo 'Modify the GenerateCCode and GenerateHCode methods to specify the codes to be used for generating the C and H file.'; }
    
    /*
     * Outputs code generator's configuration generation code C field
     */
    public function Display_ConfigurationController_Code_Field()
    {
        ?>
        <textarea rows="30" id="kmi_codegenerator_configurationcontroller_code" class="regular-text kmi-one-column vertical-resize" name="<?php echo $this->__settings_key['controller']; ?>[configuration_code]"><?php echo $this->__Get_GenerationCode($this->__var_arr['controller']['configuration'], 'c'); //$this->__general_settings['kmi_codegenerator_configuration_c_code']; ?></textarea>
        <?php
    }
    
    /*
     * Outputs code generator's pin assignment generation code C field
     */
    public function Display_PinAssignmentController_Code_Field()
    {
        ?>
        <textarea rows="30" id="kmi_codegenerator_pinassignmentcontroller_code" class="regular-text kmi-one-column vertical-resize" name="<?php echo $this->__settings_key['controller']; ?>[pinassignment_code]"><?php echo $this->__Get_GenerationCode($this->__var_arr['controller']['pin_assignment'], 'c'); //$this->__general_settings['kmi_codegenerator_pinassignment_c_code']; ?></textarea>
        <?php
    }
    
    /*
     * Outputs code generator's serial port generation code C field
     */
    public function Display_SerialPortController_Code_Field()
    {
        ?>
        <textarea rows="30" id="kmi_codegenerator_serialportcontroller_code" class="regular-text kmi-one-column vertical-resize" name="<?php echo $this->__settings_key['controller']; ?>[serialport_code]"><?php echo $this->__Get_GenerationCode($this->__var_arr['controller']['serial_port'], 'c'); //$this->__general_settings['kmi_codegenerator_serialport_c_code']; ?></textarea>
        <?php
    }
    
    /*
     * Outputs code generator's timer1 generation code C field
     */
    public function Display_Timer1Controller_Code_Field()
    {
        ?>
        <textarea rows="30" id="kmi_codegenerator_timer1controller_code" class="regular-text kmi-one-column vertical-resize" name="<?php echo $this->__settings_key['controller']; ?>[timer1_code]"><?php echo $this->__Get_GenerationCode($this->__var_arr['controller']['timer1'], 'c'); //$this->__general_settings['kmi_codegenerator_timer1_c_code']; ?></textarea>
        <?php
    }
    
    /*
     * Outputs code generator's pwm timer generation code C field
     */
    public function Display_PWMTimerController_Code_Field()
    {
        ?>
        <textarea rows="30" id="kmi_codegenerator_pwmtimercontroller_code" class="regular-text kmi-one-column vertical-resize" name="<?php echo $this->__settings_key['controller']; ?>[pwmtimer_code]"><?php echo $this->__Get_GenerationCode($this->__var_arr['controller']['pwm_timer'], 'c'); //$this->__general_settings['kmi_codegenerator_pwmtimer_c_code']; ?></textarea>
        <?php
    }
    
    /*
     * Outputs code generator's pwm oc generation code C field
     */
    public function Display_PWMOCController_Code_Field()
    {
        ?>
        <textarea rows="30" id="kmi_codegenerator_pwmoccontroller_code" class="regular-text kmi-one-column vertical-resize" name="<?php echo $this->__settings_key['controller']; ?>[pwmoc_code]"><?php echo $this->__Get_GenerationCode($this->__var_arr['controller']['pwm_oc'], 'c'); //$this->__general_settings['kmi_codegenerator_pwmoc_c_code']; ?></textarea>
        <?php
    }
    
    /*
     * Outputs code generator's bitbanged I2C generation code C field
     */
    public function Display_BitBangedI2CController_Code_Field()
    {
        ?>
        <textarea rows="30" id="kmi_codegenerator_bitbangedi2ccontroller_code" class="regular-text kmi-one-column vertical-resize" name="<?php echo $this->__settings_key['controller']; ?>[bitbangedi2c_code]"><?php echo $this->__Get_GenerationCode($this->__var_arr['controller']['bit_banged_i2c'], 'c'); //$this->__general_settings['kmi_codegenerator_bitbangedI2C_c_code']; ?></textarea>
        <?php
    }
    
    /*
     * Outputs code generator's main generation code C field
     */
    public function Display_MainController_Code_Field()
    {
        ?>
        <textarea rows="30" id="kmi_codegenerator_maincontroller_code" class="regular-text kmi-one-column vertical-resize" name="<?php echo $this->__settings_key['controller']; ?>[main_code]"><?php echo $this->__Get_GenerationCode($this->__var_arr['controller']['main'], 'c'); //$this->__general_settings['kmi_codegenerator_main_c_code']; ?></textarea>
        <?php
    }
    
    /*
     * Update CG controller files content through ajax call
     */
    public function Update_Controller_Files()
    {
        // Holds the response data
        $response_arr = array();
        
        // Configuration
        if(isset($_POST['configuration_controller_code']))
        {
            $decoded_code = urldecode($_POST['configuration_controller_code']);
            $content = str_replace("\r\n", PHP_EOL, stripslashes($decoded_code));
            file_put_contents($this->__controllers_dir.'CG_ConfigurationController.php', $content);
            $this->__message['success']['configuration'] = 'Controller file has been changed.';
        }
        // Pin Assignment
        if(isset($_POST['pinassignment_controller_code']))
        {
            $decoded_code = urldecode($_POST['pinassignment_controller_code']);
            $content = str_replace("\r\n", PHP_EOL, stripslashes($decoded_code));
            file_put_contents($this->__controllers_dir.'CG_PinAssignmentController.php', $content);
            $this->__message['success']['pin_assignment'] = 'Controller file has been changed.';
        }
        // Serial Port
        if(isset($_POST['serialport_controller_code']))
        {
            $decoded_code = urldecode($_POST['serialport_controller_code']);
            $content = str_replace("\r\n", PHP_EOL, stripslashes($decoded_code));
            file_put_contents($this->__controllers_dir.'CG_SerialPortController.php', $content);
            $this->__message['success']['serial_port'] = 'Controller file has been changed.';
        }
        // Timer1
        if(isset($_POST['timer1_controller_code']))
        {
            $decoded_code = urldecode($_POST['timer1_controller_code']);
            $content = str_replace("\r\n", PHP_EOL, stripslashes($decoded_code));
            file_put_contents($this->__controllers_dir.'CG_Timer1Controller.php', $content);
            $this->__message['success']['timer1'] = 'Controller file has been changed.';
        }
        // PWM Timer
        if(isset($_POST['pwmtimer_controller_code']))
        {
            $decoded_code = urldecode($_POST['pwmtimer_controller_code']);
            $content = str_replace("\r\n", PHP_EOL, stripslashes($decoded_code));
            file_put_contents($this->__controllers_dir.'CG_PWMTimerController.php', $content);
            $this->__message['success']['PWM_timer'] = 'Controller file has been changed.';
        }
        // PWM OC
        if(isset($_POST['pwmoc_controller_code']))
        {
            $decoded_code = urldecode($_POST['pwmoc_controller_code']);
            $content = str_replace("\r\n", PHP_EOL, stripslashes($decoded_code));
            file_put_contents($this->__controllers_dir.'CG_PWMOCController.php', $content);
            $this->__message['success']['PWM_OC'] = 'Controller file has been changed.';
        }
        // Bit Banged I2C
        if(isset($_POST['bitbangedi2c_controller_code']))
        {
            $decoded_code = urldecode($_POST['bitbangedi2c_controller_code']);
            $content = str_replace("\r\n", PHP_EOL, stripslashes($decoded_code));
            file_put_contents($this->__controllers_dir.'CG_BitBangedI2CController.php', $content);
            $this->__message['success']['bit_banged_I2C'] = 'Controller file has been changed.';
        }
        // Main
        if(isset($_POST['main_controller_code']))
        {
            $decoded_code = urldecode($_POST['main_controller_code']);
            $content = str_replace("\r\n", PHP_EOL, stripslashes($decoded_code));
            file_put_contents($this->__controllers_dir.'CG_MainController.php', $content);
            $this->__message['success']['main'] = 'Controller file has been changed.';
        }
        
        $response_arr['success'] = $this->__SetMessages($this->__message['success']);
        
        echo json_encode($response_arr);
        wp_die();
    }
    
    /*
     * Generate tab code through ajax call
     */
    public function GenerateCode()
    {
        // Holds the response data
        $response = array();
        
        if(is_user_logged_in())
        {
            // The name of the button
            $name = trim($_POST['name']);
            // The selected project's processor type
            $processor_type = trim($_POST['processor_type']);
            // Holds the form data
            $form_data_arr = $this->__ConvertJSSerializeToAssociativeArray($_POST['form_data']);

            // Generate configuration code
            if($name == 'generate_configuration_code' || ($name == 'generate_main_code' && !empty($form_data_arr['main']['configuration'])))
            {
                $response = $this->__code = $this->__CGConfigurationController->GenerateCode($processor_type, NULL, $form_data_arr['configuration']);
            }

            // Generate pin assignment code
            if($name == 'generate_pin_assignment_code' || ($name == 'generate_main_code' && !empty($form_data_arr['main']['pin_assignment'])))
            {
                $response = $this->__code = $this->__CGPinAssignmentController->GenerateCode($processor_type, NULL, $form_data_arr['pin_assignment']);
            }

            // Generate serial port1 code
            if($name == 'generate_serial_port1_code' || ($name == 'generate_main_code' && !empty($form_data_arr['main']['serial_port1'])))
            {
                $response = $this->__code = $this->__CGSerialPortController->GenerateCode($processor_type, '1', $form_data_arr['serial_port1']);
            }

            // Generate serial port2 code
            if($name == 'generate_serial_port2_code' || ($name == 'generate_main_code' && !empty($form_data_arr['main']['serial_port2'])))
            {
                $response = $this->__code = $this->__CGSerialPortController->GenerateCode($processor_type, '2', $form_data_arr['serial_port2']);
            }

            // Generate timer1 code
            if($name == 'generate_timer1_code' || ($name == 'generate_main_code' && !empty($form_data_arr['main']['timer1'])))
            {
                $response = $this->__code = $this->__CGTimer1Controller->GenerateCode($processor_type, NULL, $form_data_arr['timer1']);
            }

            // Generate pwm timer2 code
            if($name == 'generate_pwm_timer2_code' || ($name == 'generate_main_code' && !empty($form_data_arr['main']['PWM'])))
            {
                $response = $this->__code = $this->__CGPWMTimerController->GenerateCode($processor_type, '2', $form_data_arr['pwm_timer2']);
            }

            // Generate pwm timer3 code
            if($name == 'generate_pwm_timer3_code' || ($name == 'generate_main_code' && !empty($form_data_arr['main']['PWM'])))
            {
                $response = $this->__code = $this->__CGPWMTimerController->GenerateCode($processor_type, '3', $form_data_arr['pwm_timer3']);
            }

            // Generate pwm oc1 code
            if($name == 'generate_pwm_oc1_code' || ($name == 'generate_main_code' && !empty($form_data_arr['main']['PWM'])))
            {
                $response = $this->__code = $this->__CGPWMOCController->GenerateCode($processor_type, '1', $form_data_arr['pwm_oc1']);
            }

            // Generate pwm oc2 code
            if($name == 'generate_pwm_oc2_code' || ($name == 'generate_main_code' && !empty($form_data_arr['main']['PWM'])))
            {
                $response = $this->__code = $this->__CGPWMOCController->GenerateCode($processor_type, '2', $form_data_arr['pwm_oc2']);
            }

            // Generate pwm oc3 code
            if($name == 'generate_pwm_oc3_code' || ($name == 'generate_main_code' && !empty($form_data_arr['main']['PWM'])))
            {
                $response = $this->__code = $this->__CGPWMOCController->GenerateCode($processor_type, '3', $form_data_arr['pwm_oc3']);
            }

            // Generate pwm oc4 code
            if($name == 'generate_pwm_oc4_code' || ($name == 'generate_main_code' && !empty($form_data_arr['main']['PWM'])))
            {
                $response = $this->__code = $this->__CGPWMOCController->GenerateCode($processor_type, '4', $form_data_arr['pwm_oc4']);
            }

            // Generate pwm oc5 code
            if($name == 'generate_pwm_oc5_code' || ($name == 'generate_main_code' && !empty($form_data_arr['main']['PWM'])))
            {
                $response = $this->__code = $this->__CGPWMOCController->GenerateCode($processor_type, '5', $form_data_arr['pwm_oc5']);
            }

            // Generate bit banged i2c code
            if($name == 'generate_bit_banged_i2c_code' || ($name == 'generate_main_code' && !empty($form_data_arr['main']['bit_banged_I2C'])))
            {
                $response = $this->__code = $this->__CGBitBangedI2CController->GenerateCode($processor_type, NULL, $form_data_arr['bit_banged_i2c']);
            }

            // Generate main code
            if($name == 'generate_main_code')
            {
                $response = $this->__code = $this->__CGMainController->GenerateCode($processor_type, NULL, $form_data_arr);
                $response['includes'] = implode(',', $response['includes']);
            }
        }
        else
        {
            // variable for the KMI User Control object
            global $kmi_user_control;
            $response['error'] = 'I\'m sorry, you need to <a href="'.$kmi_user_control->general_settings['kmi_user_control_login_url'].'">login</a> first.';
        }
        
        echo json_encode($response);
        wp_die();
    }
    
    /*
     * Calculate constant baud rate through ajax call
     */
    public function CalculateBaudRate()
    {
        $response_arr = array();
        
        if(is_user_logged_in())
        {
            // Get crystal frequency value
            $frequency = sanitize_text_field($_POST['frequency']);
            
            // Get serial port type
            $serial_port = sanitize_text_field($_POST['serial_port']);
            
            // Get BRGH value
            $BRGH = sanitize_text_field($_POST['BRGH']);

            // Get desired baud rate value
            $desiredBR = sanitize_text_field($_POST['desiredBR']);
            
            if(empty($frequency))
                $response_arr['errors']['project'] = 'Please enter crystal frequency value.';
            
            if(empty($BRGH) && $BRGH != '0')
                $response_arr['errors']['serial_port'.$serial_port][] = 'Please select a BRGH.';
            
            if(empty($desiredBR))
                $response_arr['errors']['serial_port'.$serial_port][] = 'Please enter a desired baud rate.';
            
            if(empty($response_arr['errors']))
                $response_arr['constant_baud_rate'] = CG_SerialPort::CalculateBaudRate($frequency, $BRGH, $desiredBR);
            else
                $response_arr['error'] = $this->__SetMessages($response_arr['errors']);
            
            $response_arr['tab'] = $this->__code['tab'] = 'serial_port'.$serial_port;
        }
        else
        {
            // variable for the KMI User Control object
            global $kmi_user_control;
            $response_arr['error'] = 'I\'m sorry, you need to <a href="'.$kmi_user_control->general_settings['kmi_user_control_login_url'].'">login</a> first.';
        }
        
        echo json_encode($response_arr);
        wp_die();
    }
    
    /*
     * Calculate reload value through ajax call
     */
    public function CalculateReload()
    {
        $response_arr = array();
        
        if(is_user_logged_in())
        {
            // Get crystal frequency value
            $frequency = sanitize_text_field($_POST['frequency']);
            
            // Get interrupt number value
            $interrupt_number = sanitize_text_field($_POST['interrupt_number']);
            
            if(empty($frequency))
                $response_arr['errors']['project'] = 'Please enter crystal frequency value.';
            
            if(empty($interrupt_number))
                $response_arr['errors']['timer1'] = 'Please enter an interrupt number.';
            
            if(empty($response_arr['errors']))
                $response_arr['reload_value'] = CG_Timer1::CalculateReloadValue($frequency, $interrupt_number);
            else
                $response_arr['error'] = $this->__SetMessages($response_arr['errors']);
            
            $response_arr['tab'] = $this->__code['tab'] = 'timer1';
        }
        else
        {
            // variable for the KMI User Control object
            global $kmi_user_control;
            $response_arr['error'] = 'I\'m sorry, you need to <a href="'.$kmi_user_control->general_settings['kmi_user_control_login_url'].'">login</a> first.';
        }
        
        echo json_encode($response_arr);
        wp_die();
    }
    
    /*
     * Create or Update CG project through ajax call
     */
    public function SaveCGProject()
    {
        // Holds the ajax response data
        $response_arr = array();
        
        if(is_user_logged_in())
        {
            // Holds the form data
            $form_data_arr = $this->__ConvertJSSerializeToAssociativeArray($_POST['form_data']);
            // Holds the action status
            $action_create = FALSE;

            // Check if the project exists, if not set the project ID to 0
            if(!array_key_exists($form_data_arr['project']['id'], $this->__var_arr['user_projects_arr']))
            {
                $form_data_arr['project']['id'] = 0;
                $action_create = TRUE;
            }

            $result = $this->__CGProjectController->Save($form_data_arr['project']['id'], $form_data_arr);

            if(!empty($result->id))
            {
                // Add the newly created project into the list
                if($action_create)
                {

                    $response_arr['cg_project'] = $this->__var_arr['user_projects_arr'][$result->id] = $result;
                    unset($response_arr['cg_project']->user_id);
                }

                $response_arr['success'] = 'CG Project: '.$result->name.' successfully saved.';
            }
            else
            {
                $response_arr['error'] = $this->__SetMessages($result);
            }
        }
        else
        {
            // variable for the KMI User Control object
            global $kmi_user_control;
            $response_arr['error'] = 'I\'m sorry, you need to <a href="'.$kmi_user_control->general_settings['kmi_user_control_login_url'].'">login</a> first.';
        }
        
        echo json_encode($response_arr);
        wp_die();
    }
    
    /*
     * Retrieve all the data of an specific CG project through ajax call
     */
    public function ViewCGProject()
    {
        $response = array();
        
        list($action, $cg_project, $project_id) = explode('_', $_POST['project_info']);
        
        if($action == 'view' && $cg_project == 'cgproject' && !empty($project_id))
        {
            // Check if the project exists
            if(array_key_exists($project_id, $this->__var_arr['user_projects_arr']))
            {
                // Merge the current project data to the retrieved additional data
                $response['cg_project'] = $this->__var_arr['current'] = $this->__CGProjectController->GetAllProjectDataByID($project_id);
                // Remove unnecessary properties for security purposes
                foreach($response['cg_project'] as $data_obj)
                {
                    unset($data_obj->id);
                    unset($data_obj->project_id);
                }
                $response['cg_project']['project'] = $this->__var_arr['current']['project'] = $this->__var_arr['user_projects_arr'][$project_id];
                // Remove user_id property
                unset($response['cg_project']['project']->user_id);
            }
            else
                $response['error'] = 'Project doesn\'t exists.';
        }
        else
            $response['error'] = 'Invalid request, unrecognized operation.';
        
        echo json_encode($response);
        wp_die();
    }
    
    /*
     * Delete CG Project through ajax call
     */
    public function DeleteCGProject()
    {
        $response_arr = array();
        
        list($action, $cg_project, $project_id) = explode('_', $_POST['project_info']);
        
        if($action == 'delete' && $cg_project == 'cgproject' && !empty($project_id))
        {
            // Check if the project exists
            if(array_key_exists($project_id, $this->__var_arr['user_projects_arr']))
            {
                // Delete the selected CG Project
                $deleted = $this->__CGProjectController->ActionDeleteCGProject($project_id);
                
                if(!empty($deleted->id))
                {
//                    $this->__var_arr['user_projects_arr'] = array_diff($this->__var_arr['user_projects_arr'], array($project_id => $this->__var_arr['user_projects_arr'][$project_id]->id));
                      $response_arr['success'] = 'CG Project: '.$deleted->name.' was successfully deleted.';
                      $response_arr['cg_project'] = $deleted->id;
                      // Remove the project in the current lists
                      unset($this->__var_arr['user_projects_arr'][$deleted->id]);
                }
                else
                    $response_arr['error'] = $this->__SetMessages($deleted);
            }
            else
                $response_arr['error'] = 'Cannot delete project that doesn\'t exists.';
        }
        else
            $response_arr['error'] = 'Invalid request, unrecognized operation.';
        
        echo json_encode($response_arr);
        wp_die();
    }
    
    /*
     * Execute the functions needed for the
     * code generator before the html headers
     */
    public function Initialize()
    {
        // Code generator is being submit
        if(isset($_POST['kmi_code_generator_form']))
        {
            if(!is_user_logged_in())
            {
                $this->general_settings = (array) get_option('kmi_user_control_general_settings');
                $login_url = $this->general_settings['kmi_user_control_login_url'];
                wp_redirect($login_url);
                exit();
            }
            else if(isset($_POST['save_project_code']))
            {
                $project_id = trim($_GET['cg_project']);
                
                $result = $this->__CGProjectController->Save($project_id);
                
                if(!empty($result->id))
                {
                    $this->__message['success'] = 'CG Project: '.$result->name.' successfully saved.';
                }
                else
                {
                    if(count($result) > 0)
                        $this->__message['error'] = $result;
                    else
                        $this->__message['error'][] = 'Failed to save CG Project.';
                }
            }
            else
            {
                // Calculate baud rate process
                if(isset($_POST['serial_port1']['calculateBR']) || isset($_POST['serial_port2']['calculateBR']))
                {
//                    // Put the serial port POST data into a variable
//                    $serial_port_post = isset($_POST['serial_port1']['calculateBR']) ? $_POST['serial_port1'] : $_POST['serial_port2'];
                    
                    // Put the serial port POST data into a variable
                    // and get the correct serial port type
                    if(isset($_POST['serial_port1']['calculateBR']))
                    {
                        $serial_port_post = $_POST['serial_port1'];
                        $this->__model_type = '1';
                    }
                    else
                    {
                        $serial_port_post = $_POST['serial_port2'];
                        $this->__model_type = '2';
                    }

//                    // Get the correct serial port type
//                    if(array_key_exists('calculateBR', $serial_port_post))
//                        $this->__model_type = '1';
//                    elseif(array_key_exists('calculateBR2', $serial_port_post))
//                        $this->__model_type = '2';


                    // Get crystal frequency value
                    $frequency = $_POST['project']['frequency'];

                    // Get BRGH value
                    $BRGH = $serial_port_post['BRGH'];

                    // Get desired baud rate value
                    $desiredBR = $serial_port_post['desiredBR'];

                    if(empty($frequency))
                        $this->__message['error']['project'] = 'Please enter crystal frequency value.';

                    if(empty($BRGH) && $BRGH != '0')
                        $this->__message['error']['serial_port'.$this->__model_type][] = 'Please select a BRGH.';

                    if(empty($desiredBR))
                        $this->__message['error']['serial_port'.$this->__model_type][] = 'Please enter a desired baud rate.';

                    if(empty($this->__message['error']))
                        $this->__calculated_values['serial_port'.$this->__model_type.'_constantBR'] = CG_SerialPort::CalculateBaudRate($frequency, $BRGH, $desiredBR);
                    
                    $this->__code['tab'] = 'serial_port'.$this->__model_type;
                }
                
                // Calculate reload value process
                if(isset($_POST['timer1']['calculateReload']))
                {
                    // Put the serial port POST data into a variable
                    $timer1_post = $_POST['timer1'];

                    // Get crystal frequency value
                    $frequency = $_POST['project']['frequency'];

                    // Get interrupt number value
                    $interruptNumber = $timer1_post['interrupt_number'];

                    if(empty($frequency))
                        $this->__message['error']['project'] = 'Please enter crystal frequency value.';

                    if($interruptNumber == '')
                        $this->__message['error']['timer1'] = 'Please enter an interrupt number.';

                    if(empty($this->__message['error']))
                        $this->__calculated_values['timer1_reload_value'] = CG_Timer1::CalculateReloadValue($frequency, $interruptNumber);
                    
                    $this->__code['tab'] = 'timer1';
                }
                
                // Check for any generate code request
                $processor_type = trim($_POST['project']['processor_type']);
                // Configuration
                if(isset($_POST['generate_configuration_code']) || (isset($_POST['generate_main_code']) && !empty($_POST['main']['configuration'])))
                {
                    $this->__code = $this->__CGConfigurationController->GenerateCode($processor_type);
                    $this->__code['tab'] = 'configuration';
                }

                // Pin Assignment
                if(isset($_POST['generate_pin_assignment_code']) || (isset($_POST['generate_main_code']) && !empty($_POST['main']['pin_assignment'])))
                {
                    $this->__code = $this->__CGPinAssignmentController->GenerateCode($processor_type);
                    $this->__code['tab'] = 'pin_assignment';
                }

                // Serial Port 1
                if(isset($_POST['generate_serial_port1_code']) || (isset($_POST['generate_main_code']) && !empty($_POST['main']['serial_port_1'])))
                {
                    $this->__code = $this->__CGSerialPortController->GenerateCode($processor_type, '1');
                    $this->__code['tab'] = 'serial_port1';
                }

                // Serial Port 2
                if(isset($_POST['generate_serial_port2_code']) || (isset($_POST['generate_main_code']) && !empty($_POST['main']['serial_port_2'])))
                {
                    $this->__code = $this->__CGSerialPortController->GenerateCode($processor_type, '2');
                    $this->__code['tab'] = 'serial_port2';
                }

                // Timer 1
                if(isset($_POST['generate_timer1_code']) || (isset($_POST['generate_main_code']) && !empty($_POST['main']['timer_1'])))
                {
                    $this->__code = $this->__CGTimer1Controller->GenerateCode($processor_type);
                    $this->__code['tab'] = 'timer1';
                }

                // PWM Timer 2
                if(isset($_POST['generate_pwm_timer2_code']) || (isset($_POST['generate_main_code']) && !empty($_POST['main']['PWM'])))
                {
                    $this->__code = $this->__CGPWMTimerController->GenerateCode($processor_type, '2');
                    $this->__code['tab'] = 'pwm';
                }

                // PWM Timer 3
                if(isset($_POST['generate_pwm_timer3_code']) || (isset($_POST['generate_main_code']) && !empty($_POST['main']['PWM'])))
                {
                    $this->__code = $this->__CGPWMTimerController->GenerateCode($processor_type, '3');
                    $this->__code['tab'] = 'pwm';
                }

                // PWM OC 1
                if(isset($_POST['generate_pwm_oc1_code']) || (isset($_POST['generate_main_code']) && !empty($_POST['main']['PWM'])))
                {
                    $this->__code = $this->__CGPWMOCController->GenerateCode($processor_type, '1');
                    $this->__code['tab'] = 'pwm';
                }

                // PWM OC 2
                if(isset($_POST['generate_pwm_oc2_code']) || (isset($_POST['generate_main_code']) && !empty($_POST['main']['PWM'])))
                {
                    $this->__code = $this->__CGPWMOCController->GenerateCode($processor_type, '2');
                    $this->__code['tab'] = 'pwm';
                }

                // PWM OC 3
                if(isset($_POST['generate_pwm_oc3_code']) || (isset($_POST['generate_main_code']) && !empty($_POST['main']['PWM'])))
                {
                    $this->__code = $this->__CGPWMOCController->GenerateCode($processor_type, '3');
                    $this->__code['tab'] = 'pwm';
                }

                // PWM OC 4
                if(isset($_POST['generate_pwm_oc4_code']) || (isset($_POST['generate_main_code']) && !empty($_POST['main']['PWM'])))
                {
                    $this->__code = $this->__CGPWMOCController->GenerateCode($processor_type, '4');
                    $this->__code['tab'] = 'pwm';
                }

                // PWM OC 5
                if(isset($_POST['generate_pwm_oc5_code']) || (isset($_POST['generate_main_code']) && !empty($_POST['main']['PWM'])))
                {
                    $this->__code = $this->__CGPWMOCController->GenerateCode($processor_type, '5');
                    $this->__code['tab'] = 'pwm';
                }

                // Bit Banged I2C
                if(isset($_POST['generate_bit_banged_i2c_code']) || (isset($_POST['generate_main_code']) && !empty($_POST['main']['bit_banged_I2C'])))
                {
                    $this->__code = $this->__CGBitBangedI2CController->GenerateCode($processor_type);
                    $this->__code['tab'] = 'bit_banged_i2c';
                }

                // Main
                if(isset($_POST['generate_main_code']))
                {
                    $this->__code = $this->__CGMainController->GenerateCode($processor_type);
                    $this->__code['tab'] = 'main';
                }
            }
        }
        
        // Get all the current user's projects
        if(is_user_logged_in())
        {
            $this->__var_arr['user_projects_arr'] = CG_Project::model()->FindAllByUserID(get_current_user_id());
            
            // Sort user projects by ID
            ksort($this->__var_arr['user_projects_arr']);
            
            if(isset($_GET['cg_project']))
            {
                $project_id = trim($_GET['cg_project']);
                
                // Check if the project exists
                if(array_key_exists($project_id, $this->__var_arr['user_projects_arr']))
                {
                    if($_GET['action'] == 'view')
                    {
                        // Merge the current project data to the retrieved additional data
                        $this->__var_arr['current'] = $this->__CGProjectController->GetAllProjectDataByID($project_id);
                        $this->__var_arr['current']['project'] = $this->__var_arr['user_projects_arr'][$project_id];
                    }
                    else if($_GET['action'] == 'delete')
                    {
                        $delete = $this->__CGProjectController->ActionDeleteCGProject($project_id);
                        
                        if($delete === TRUE)
                        {
//                            $this->__var_arr['user_projects_arr'] = array_diff($this->__var_arr['user_projects_arr'], array($project_id => $this->__var_arr['user_projects_arr'][$project_id]->id));
                            $this->__message['success'] = $this->__var_arr['user_projects_arr'][$project_id]->name.' was successfully deleted.';
                            unset($this->__var_arr['user_projects_arr'][$project_id]);
                        }
                        else
                            $this->__message['error'] = $delete;
                    }
                }
            }
        }
    }
    
    /*
     * Callback function for displaying the code
     * generator form on the page.
     */
    public function Code_Generator_Form()
    {
        if(count($this->__message['error']) > 0)
        {
            ?>
            <p class="error">
            <?php
//                foreach($this->__message['error'] as $key => $error)
//                {
//                    $key = ucwords(str_replace('_', ' ', $key));
//                    echo '<span class="bold">'.$key.'</span>: '.$error.'<br/>';
//                }
                 echo $this->__SetMessages($this->__message['error']);
                // Remove all existing error messages
                $this->__message['error'] = array();
            ?>
            </p>
            <?php
        }
        else if(!empty($this->__message['success']))
        {
            ?><p class="success message"><?php echo $this->__message['success']; ?></p><?php
        }
        ?>
        <form name="kmi_code_generator_form" id="kmi_code_generator_form" action="" method="POST">
            <input type="hidden" name="kmi_code_generator_form" value="yes" />
            <div id="tab-container">
                <input type="button" class="btn_toggle" value="Hide projects" />
                <input type="button" style="display: none;" class="btn_toggle" value="Show projects" />
                <div id="toggle_effect">
                    <table id="kmi_cg_project_list" class="kmi-table kmi-one-column" border="1">
                        <thead>
                            <tr>
                                <th class="align-center bold bg-grey">Project Name</th>
                                <th class="align-center bold bg-grey kmi-four-columns">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($this->__var_arr['user_projects_arr']) > 0): ?>
                                <?php foreach($this->__var_arr['user_projects_arr'] as $project): ?>
                                    <tr id="cg_project_<?php echo $project->id; ?>">
                                        <td class="align-center bold"><?php echo $project->name; ?></td>
                                        <td class="align-center">
                                            <a href="?cg_project=<?php echo $project->id; ?>&action=view" class="dashicons dashicons-media-spreadsheet no-text-decoration btn-kmi-cg-view-project" id="view_cgproject_<?php echo $project->id; ?>" alt="View project" title="View project"></a>
                                            <a href="?cg_project=<?php echo $project->id; ?>&action=delete" class="dashicons dashicons-trash no-text-decoration btn-kmi-cg-delete-project" id="delete_cgproject_<?php echo $project->id; ?>" alt="Delete project" title="Delete project"></a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr id="kmi_cg_empty_row">
                                    <td class="align-center bold" colspan="2">No projects found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                    <div id="pagingControls"></div>
                </div><br/>
                <input type="button" class="btn-kmi-reset-form" value="Reset" />
                <p class="kmi-responsive">
                    <label class="inline-block kmi-four-columns bold">Project Name: </label>
                    <input type="hidden" name="project[id]" value="<?php $this->_CurrentValue('project', 'id'); ?>" />
                    <input type="text" class="kmi-four-columns" name="project[name]" value="<?php $this->_CurrentValue('project', 'name'); ?>" />
                </p>
                <p class="kmi-responsive">
                    <label class="inline-block kmi-four-columns bold">Processor Type: </label>
                    <select class="kmi-four-columns" id="project_processor_type" name="project[processor_type]">
                        <?php foreach(array('p24FJ32GA002', 'p24FJ48GA002', 'p24FJ64GA002', 'p24FJ64GA004') as $processor_type): ?>
                            <option <?php $this->_SelectedOrNot('project', 'processor_type', $processor_type, 'selected'); ?>><?php echo $processor_type; ?></option>
                        <?php endforeach; ?>
                    </select>
                </p>
                <p class="kmi-responsive">
                    <label class="inline-block kmi-four-columns bold">Crystal Frequency in MHZ: </label>
                    <input type="text" class="kmi-four-columns" name="project[frequency]" value="<?php $this->_CurrentValue('project', 'frequency'); ?>" />
                </p>
                <!-- TABS -->
                <ul class="tab-menu">
                    <li id="configuration" <?php if(empty($this->__code['tab']) || $this->__code['tab'] == 'configuration'){echo 'class="active"';} ?>>Configuration</li>
                    <li id="pin_assignment" <?php if($this->__code['tab'] == 'pin_assignment'){echo 'class="active"';} ?>>PinAssignment</li>
                    <li id="portb">PortB</li>
                    <li id="serial_port1" <?php if($this->__code['tab'] == 'serial_port1'){echo 'class="active"';} ?>>SerialPort1</li>
                    <li id="serial_port2" <?php if($this->__code['tab'] == 'serial_port2'){echo 'class="active"';} ?>>SerialPort2</li>
                    <li id="timer1" <?php if($this->__code['tab'] == 'timer1'){echo 'class="active"';} ?>>Timer1</li>
                    <li id="PWM" <?php if($this->__code['tab'] == 'pwm'){echo 'class="active"';} ?>>PWM</li>
                    <li id="bit_banged_i2c" <?php if($this->__code['tab'] == 'bit_banged_i2c'){echo 'class="active"';} ?>>Bit Banged I2C</li>
                    <li id="i2c">I2C</li>
                    <li id="main" <?php if($this->__code['tab'] == 'main'){echo 'class="active"';} ?>>Main</li>
                </ul>
                <div class="clear"></div>
                <div class="tab-top-border"></div>
                <!-- TAB CONTENT -->
                <div id="configuration-tab" class="tab-content <?php if(empty($this->__code['tab']) || $this->__code['tab'] == 'configuration'){echo 'active';} ?>">
                    <?php require_once 'views/CG_configuration.php'; ?>
                </div>
                <!-- TAB CONTENT -->
                <div id="pin_assignment-tab" class="tab-content <?php if($this->__code['tab'] == 'pin_assignment'){echo 'active';} ?>">
                    <?php require_once 'views/CG_pin-assignment.php'; ?>
                </div>
                <!-- TAB CONTENT -->
                <div id="portb-tab" class="tab-content">
                    <?php require_once 'views/CG_portb.php'; ?>
                </div>
                <!-- TAB CONTENT -->
                <div id="serial_port1-tab" class="tab-content <?php if($this->__code['tab'] == 'serial_port1'){echo 'active';} ?>">
                    <?php
                        // Set model type first
                        $this->__model_type = '1';
                        require 'views/CG_serial-port.php';
                    ?>
                </div>
                <!-- TAB CONTENT -->
                <div id="serial_port2-tab" class="tab-content <?php if($this->__code['tab'] == 'serial_port2'){echo 'active';} ?>">
                    <?php
                        // Set model type first
                        $this->__model_type = '2';
                        require 'views/CG_serial-port.php';
                    ?>
                </div>
                <!-- TAB CONTENT -->
                <div id="timer1-tab" class="tab-content <?php if($this->__code['tab'] == 'timer1'){echo 'active';} ?>">
                    <?php require_once 'views/CG_timer1.php'; ?>
                </div>
                <!-- TAB CONTENT -->
                <div id="PWM-tab" class="tab-content <?php if($this->__code['tab'] == 'pwm'){echo 'active';} ?>">
                    <?php require_once 'views/CG_pwm.php'; ?>
                </div>
                <!-- TAB CONTENT -->
                <div id="bit_banged_i2c-tab" class="tab-content <?php if($this->__code['tab'] == 'bit_banged_i2c'){echo 'active';} ?>">
                    <?php require_once 'views/CG_bit-banged-i2c.php'; ?>
                </div>
                <!-- TAB CONTENT -->
                <div id="i2c-tab" class="tab-content">
                    <h1>I2C</h1>
                </div>
                <!-- TAB CONTENT -->
                <div id="main-tab" class="tab-content <?php if($this->__code['tab'] == 'main'){echo 'active';} ?>">
                    <?php require_once 'views/CG_main.php'; ?>
                </div>
                <br/>
                <h1 id="kmi_cg_generated_file">
                    Generated File
                    <?php if(!empty($this->__code['filename'])): ?>
                        [ <a href="?download=<?php echo $this->__code['filename']; if(!empty($this->__code['includes'])){echo '&includes='.implode(',', $this->__code['includes']);} ?>">
                            Download files
                        </a> ]
                    <?php endif; ?>
                </h1>
                <p class="kmi-responsive">
                    <textarea class="vertical-resize block kmi-one-column" id="txt_kmi_cg_c_code" name="" rows="20"><?php if(!empty($this->__code['c'])){echo trim($this->__code['c']);} // Display the generated code ?></textarea>
                    <br/>
                    <textarea class="vertical-resize block kmi-one-column" id="txt_kmi_cg_h_code"  name="" rows="20"><?php if(!empty($this->__code['h'])){echo trim($this->__code['h']);} // Display the generated code ?></textarea>
                </p>
            </div>
        </form>
        <?php
    }
    
    private function __SetMessages($messages='', $source='')
    {
        $msgs = '';
        
        if(is_array($messages))
        {
            foreach($messages as $src => $msg)
            {
                $new_src = is_numeric($src) ? $source : $src;
                $msgs .= $this->__SetMessages($msg, $new_src);
            }
        }
        else
        {
            if(!empty($source))
                $msgs .= '<span class="bold">'.ucwords(str_replace('_', ' ', $source)).'</span>: ';
            $msgs .= $messages.'<br/>';
        }
        
        return $msgs;
    }
    
    /*
     * Output the header tabs in the option page
     */
    private function __Plugin_Options_Tabs()
    {
        $current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : $this->__settings_key['controller'];
        
        screen_icon();
        
        echo '<h2 class="nav-tab-wrapper">';
	foreach($this->__plugin_settings_tabs as $tab_key => $tab_caption)
        {
            $active = $current_tab == $tab_key ? 'nav-tab-active' : '';
            echo '<a class="nav-tab ' . $active . '" href="?page=' . $this->__plugin_options_key . '&tab=' . $tab_key . '">' . $tab_caption . '</a>';	
        }
	echo '</h2>';
    }
    
    /*
     * Retrieve the codes in the controllers generate code function
     */
    private function __Get_GenerationCode_StartIndex($content_arr=array(), $code_type='c')
    {
//        $content_arr = explode(PHP_EOL, $content);
        
        if(strtolower($code_type) === 'c')
            $function_name = 'function __GenerateCCode';
        else if(strtolower($code_type) === 'h')
            $function_name = 'function __GenerateHCode';
        
        foreach($content_arr as $key => $value)
        {
            if(strpos($value, $function_name) !== FALSE)
                return $key;
        }
        
        return -1;
    }
    
    /*
     * Retrieve the codes in the controllers generate code function
     */
    private function __Get_GenerationCode($content_arr=array(), $code_type='c')
    {
//        $index = $this->__Get_GenerationCode_StartIndex($content_arr, $code_type);
////        $content_arr = explode(PHP_EOL, $content);
//        $open_braces = 0;
//        $close_braces = 0;
////        $lines_count = 0;
//        $started = FALSE;
//        $codes_arr = array();
//        
//        if($index > -1)
//        {
////            $codes = $content_arr[$index];
//            while(true)
//            {
//                if(strpos($content_arr[$index], '{') !== FALSE)
//                {
//                    if(!$started)
//                        $started = TRUE;
//
//                    $open_braces++;
//                }
//                else if(strpos($content_arr[$index], '}') !== FALSE)
//                {
//                    $close_braces++;
//                }
//
//                $codes_arr[] = $content_arr[$index];
////                $codes .= $content_arr[$index].PHP_EOL;
//
//                if($started && ($open_braces === $close_braces))
//                    break;
//
//                $index++;
////                $lines_count++;
//            }
//        }
//        
////        $codes = array_slice($content_arr, $index, $lines_count);
//        
////        return $codes;
        return implode('', $content_arr);
    }
    
    /*
     * Convert javascript serialize array to php associative array
     * 
     * @param array (javascript serialize array)
     * 
     * @return array (associative array)
     */
    private function __ConvertJSSerializeToAssociativeArray($serialize_arr=array())
    {
        $associative_arr = array();
        
        // Create an associative array out of the serialize js array
        foreach($serialize_arr as $ser_arr)
        {
            // Get the array keys from 'key0[key1]' to array(key0, key1)
            $keys_arr = explode(',', str_replace(array('[', ']'), array(',', ''), $ser_arr['name']));
            
            // 2-dimensional array
            if(count($keys_arr) == 2)
                $associative_arr[$keys_arr[0]][$keys_arr[1]] = $ser_arr['value'];
            // Single array
            else
                $associative_arr[$keys_arr[0]] = $ser_arr['value'];
        }
        
        return $associative_arr;
    }
    
    /*
     * Verifies the checkbox if checked or not
     */
    private function _CheckedOrNot($tab='', $name='')
    {
        $tab = strtolower($tab);
        
        if(!empty($_POST[$tab][$name]) || ($this->__var_arr['current'][$tab]->$name == 'yes'))
            echo 'checked="true"';
    }
    
    /*
     * Verifies the dropdown list's selected item or multiple radio button's
     * selected option
     */
    private function _SelectedOrNot($tab='', $name='', $value='', $selection_type='')
    {
        $tab = strtolower($tab);
        
        if(($_POST[$tab][$name] != null && $_POST[$tab][$name] == $value) || ($this->__var_arr['current'][$tab]->$name != null && $this->__var_arr['current'][$tab]->$name == $value))
        {
            switch(strtolower($selection_type))
            {
                case 'selected':
                    echo 'selected="selected"';
                    break;
                case 'checked':
                    echo 'checked="checked"';
                    break;
            }
        }
    }
    
    /*
     * Verifies the textbox / textarea value
     */
    private function _CurrentValue($tab='', $name='')
    {
        $tab = strtolower($tab);
        
        if(!empty($_POST[$tab][$name]))
            echo trim($_POST[$tab][$name]);
        elseif(!empty($this->__calculated_values[$tab.'_'.$name]))
            echo trim($this->__calculated_values[$tab.'_'.$name]);
        elseif(!empty($this->__var_arr['current'][$tab]->$name))
            echo trim($this->__var_arr['current'][$tab]->$name);
    }
    
    /*
     * Setups all shortcode functions
     */
    private function _Add_Shortcodes()
    {
        // Code generator form UI
        add_shortcode('kmi_code_generator_form', array($this, 'Code_Generator_Form'));
    }
    
    /*
     * Setups all filter functions
     */
    private function _Add_Filters()
    {
        
    }
    
    /*
     * Setups all action functions
     */
    private function _Add_Actions()
    {
        // Add front-end initialization functions
        add_action('init', array($this, 'Initialize'));
        // Add front-end css and scripts
        add_action('wp_enqueue_scripts', array($this, 'Add_Front_End_Styles_And_Scripts'));
        // Add function that intercepts request for downloading a file 
        add_action('pre_get_posts', array($this, 'Download_File'));
        // Add option page in the admin panel
        add_action('admin_menu', array($this, 'Add_Admin_Option_Page'));
        // Register the settings to be use in the admin option pages
        add_action('admin_init', array($this, 'Register_Admin_Option_Settings'));
        // Ajax functions
        add_action('wp_ajax_kmicodegenerator_update_controller_files', array($this, 'Update_Controller_Files'));
        add_action('wp_ajax_generate_code', array($this, 'GenerateCode'));
        add_action('wp_ajax_nopriv_generate_code', array($this, 'GenerateCode'));
        add_action('wp_ajax_calculate_baudrate', array($this, 'CalculateBaudRate'));
        add_action('wp_ajax_nopriv_calculate_baudrate', array($this, 'CalculateBaudRate'));
        add_action('wp_ajax_calculate_reload', array($this, 'CalculateReload'));
        add_action('wp_ajax_nopriv_calculate_reload', array($this, 'CalculateReload'));
        add_action('wp_ajax_save_cgproject', array($this, 'SaveCGProject'));
        add_action('wp_ajax_nopriv_save_cgproject', array($this, 'SaveCGProject'));
        add_action('wp_ajax_view_cgproject', array($this, 'ViewCGProject'));
        add_action('wp_ajax_delete_cgproject', array($this, 'DeleteCGProject'));
    }
}

$kmi_code_generator = new KMI_CodeGenerator();