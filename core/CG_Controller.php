<?php

abstract class CG_Controller
{
    protected $_filename;
    protected $_controller_type;
    protected $_var_arr = array();
    
    public function __construct($controller_type='')
    {
        $this->_controller_type = $controller_type;
        
        // Assign filename value
        switch(strtolower($this->_controller_type))
        {
            case 'configuration':
                $this->_filename = 'config';
                break;
            case 'pin_assignment':
                $this->_filename = 'pinAssign';
                break;
            case 'serial_port':
                $this->_filename = 'uart';
                break;
            case 'timer1':
                $this->_filename = 'timer1';
                break;
            case 'pwm_timer':
                $this->_filename = 'timer';
                break;
            case 'pwm_oc':
                $this->_filename = 'oc';
                break;
            case 'bit_banged_i2c':
                $this->_filename = 'bbI2C';
                break;
            case 'main':
                $this->_filename = 'main';
                break;
        }
    }
        
    /*
     * Generate codes based on the values assign on the
     * CG model object
     * 
     * @return array
     */
    public function GenerateCode($processor_type='', $type='', $attribute_values_arr=array())
    {
        // Set the values for configuration
        $model_obj = $this::Instantiate($this->_controller_type, 0, $type);
        
        // Assign portb POST data
        if(!empty($attribute_values_arr['portb']))
            $this->_var_arr['POST']['portb'] = $attribute_values_arr['portb'];
        
        $attribute_values_arr = !empty($attribute_values_arr['main']) ? $attribute_values_arr['main'] : $attribute_values_arr;
        
        if(count($attribute_values_arr) > 0 && is_array($attribute_values_arr))
        {
            foreach($model_obj::$attributes as $attribute)
            {
                if(is_array($attribute))
                {
                    foreach($attribute as $attr)
                    {
                        $model_obj->$attr = $attribute_values_arr[$attr];
                    }
                    continue;
                }
                $model_obj->$attribute = $attribute_values_arr[$attribute];
            }
            
            // Set included user codes
            // Before main includes
            if(!empty($attribute_values_arr['before_main_includes']))
                $model_obj->before_main_includes = stripslashes($attribute_values_arr['before_main_includes']);
            // Inside main includes
            if(!empty($attribute_values_arr['inside_main_includes']))
                $model_obj->inside_main_includes = stripslashes($attribute_values_arr['inside_main_includes']);
        }
        
        if(property_exists($model_obj, 'type'))
            $model_obj->type = $type;
        
        $code = array();
        
        if(method_exists($this, '_GenerateCCode'))
            $code['c'] = $this->_GenerateCCode($model_obj, $processor_type);
        if(method_exists($this, '_GenerateHCode'))
            $code['h'] = $this->_GenerateHCode($model_obj, $processor_type);
        
        // Get the filename for the generated files
        $code['filename'] = $this->_filename.$type;
        // Get the included files
        $code['includes'] = $this->_var_arr['includes'];
        
        $this->_WriteFile($code, $model_obj);
        
        return $code;
    }
    
    /*
     * Gets all attributes value for CG model
     * 
     * @param project ID
     * 
     * @returns CG model
     */
    public static function Instantiate($controller_type='', $project_id=0, $type='')
    {
        // Create new CG model instance
        switch(strtolower($controller_type))
        {
            case 'project':
                $model_obj = CG_Project::model();
                break;
            case 'configuration':
                $model_obj = CG_Configuration::model();
                break;
            case 'pin_assignment':
                $model_obj = CG_PinAssignment::model();
                break;
            case 'portb':
                $model_obj = CG_PortB::model();
                break;
            case 'serial_port':
                $model_obj = CG_SerialPort::model();
                break;
            case 'timer1':
                $model_obj = CG_Timer1::model();
                break;
            case 'pwm_timer':
                $model_obj = CG_PWMTimer::model();
                break;
            case 'pwm_oc':
                $model_obj = CG_PWMOC::model();
                break;
            case 'bit_banged_i2c':
                $model_obj = CG_BitBangedI2C::model();
                break;
            case 'main':
                $model_obj = CG_Main::model();
                break;
        }
        
        if(!empty($project_id))
        {
            // Look for an existing record in the database
            // that has the same project ID attribute
            $result = $model_obj->FindByProjectID($project_id, $type);
            
            // If there's a record found
            if($result !== FALSE)
                $model_obj = $result;
            
            // Assign new project ID
            $model_obj->project_id = $project_id;
        }
        
        // Assign POST data into the object properties
        if(isset($_POST['kmi_code_generator_form']))
        {
            foreach($model_obj::$attributes as $attribute)
            {
                if($attribute !== 'id' && $attribute !== 'project_id')
                {
                    if(is_array($attribute))
                    {
                        foreach($attribute as $attr)
                        {
                            if(property_exists($model_obj, $attr))
                            {
                                $model_obj->$attr = $_POST[$controller_type.$type][$attr];
                            }
                        }
                        continue;
                    }
                    if(property_exists($model_obj, $attribute))
                        $model_obj->$attribute = $_POST[$controller_type.$type][$attribute];
                }
            }
        }
        
        // Add the current user ID
        if(property_exists($model_obj, 'user_id'))
            $model_obj->user_id = get_current_user_id();
        
        // Set type value
        if(!empty($type))
            $model_obj->type = $type;
        
        // Set included user codes
        // Before main includes
        if(!empty($_POST[$controller_type.$type]['before_main_includes']))
            $model_obj->before_main_includes = stripslashes($_POST[$controller_type.$type]['before_main_includes']);
        // Inside main includes
        if(!empty($_POST[$controller_type.$type]['inside_main_includes']))
            $model_obj->inside_main_includes = stripslashes($_POST[$controller_type.$type]['inside_main_includes']);
        
        return $model_obj;
    }
    
    protected function _WriteFile($codes=array(), $CG_model=NULL)
    {
        $path = plugin_dir_path(__FILE__);
        $path = dirname($path).'/generated-files/';
        
        $filename = $this->_filename;
        if(!empty($CG_model->type))
            $filename .= $CG_model->type;
        
        // C file
        if(!empty($codes['c']))
        {
            $file = $path.$filename.'.c';
            $cfileHandler = fopen($file, 'w') or die("can't open file.");
            fwrite($cfileHandler, $codes['c']);
            fclose($cfileHandler);
        }
        // H file
        if(!empty($codes['h']))
        {
            $file = $path.$filename.'.h';
            $hfileHandler = fopen($file, 'w') or die("can't open file.");
            fwrite($hfileHandler, $codes['h']);
            fclose($hfileHandler);
        }
    }
}