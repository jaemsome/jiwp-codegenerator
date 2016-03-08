<?php

class CG_ProjectController extends CG_Controller
{
    /*
     * Retrieve all record data of an specific project
     * 
     * @param project ID
     * 
     * @return array
     */
    public function GetAllProjectDataByID($project_id=0)
    {
        $data_arr = array();
        
        $data_arr['configuration'] = CG_Configuration::model()->FindByProjectID($project_id);
        $data_arr['pin_assignment'] = CG_PinAssignment::model()->FindByProjectID($project_id);
        $data_arr['portb'] = CG_PortB::model()->FindByProjectID($project_id);
        $data_arr['serial_port1'] = CG_SerialPort::model()->FindByProjectID($project_id, '1');
        $data_arr['serial_port2'] = CG_SerialPort::model()->FindByProjectID($project_id, '2');
        $data_arr['timer1'] = CG_Timer1::model()->FindByProjectID($project_id);
        $data_arr['pwm_timer2'] = CG_PWMTimer::model()->FindByProjectID($project_id, '2');
        $data_arr['pwm_timer3'] = CG_PWMTimer::model()->FindByProjectID($project_id, '3');
        $data_arr['pwm_oc1'] = CG_PWMOC::model()->FindByProjectID($project_id, '1');
        $data_arr['pwm_oc2'] = CG_PWMOC::model()->FindByProjectID($project_id, '2');
        $data_arr['pwm_oc3'] = CG_PWMOC::model()->FindByProjectID($project_id, '3');
        $data_arr['pwm_oc4'] = CG_PWMOC::model()->FindByProjectID($project_id, '4');
        $data_arr['pwm_oc5'] = CG_PWMOC::model()->FindByProjectID($project_id, '5');
        $data_arr['bit_banged_i2c'] = CG_BitBangedI2C::model()->FindByProjectID($project_id);
        $data_arr['main'] = CG_Main::model()->FindByProjectID($project_id);
        
        return $data_arr;
    }
    
    /*
     * Create or Update CG Project
     * 
     * @param (int) project ID
     * @param (array) form data
     * 
     * @return mixed (CG_Project object if success and array of string errors if fails)
     */
    public function Save($project_id=0, $form_data_arr=array())
    {
        $errors = array();
        
        $tab_arr = array(
            'project', 'configuration', 'pin_assignment', 'portb',
            'serial_port1', 'serial_port2', 'timer1', 'pwm_timer2',
            'pwm_timer3', 'pwm_oc1', 'pwm_oc2', 'pwm_oc3', 'pwm_oc4',
             'pwm_oc5', 'bit_banged_i2c', 'main'
        );
        
        $model_obj_arr = array();
        
        foreach($tab_arr as $tab)
        {
            switch($tab)
            {
                case 'project':
                    $model_obj = $this::Instantiate($tab, $project_id);
                    break;
                case 'configuration':
                    $model_obj = CG_ConfigurationController::Instantiate($tab, $model_obj_arr['project']->id);
                    break;
                case 'pin_assignment':
                    $model_obj = CG_PinAssignmentController::Instantiate($tab, $model_obj_arr['project']->id);
                    break;
                case 'portb':
                    $model_obj = CG_PortBController::Instantiate($tab, $model_obj_arr['project']->id);
                    break;
                case 'serial_port1':
                    $model_obj = CG_SerialPortController::Instantiate('serial_port', $model_obj_arr['project']->id, '1');
                    break;
                case 'serial_port2':
                    $model_obj = CG_SerialPortController::Instantiate('serial_port', $model_obj_arr['project']->id, '2');
                    break;
                case 'timer1':
                    $model_obj = CG_Timer1Controller::Instantiate($tab, $model_obj_arr['project']->id);
                    break;
                case 'pwm_timer2':
                    $model_obj = CG_PWMTimerController::Instantiate('pwm_timer', $model_obj_arr['project']->id, '2');
                    break;
                case 'pwm_timer3':
                    $model_obj = CG_PWMTimerController::Instantiate('pwm_timer', $model_obj_arr['project']->id, '3');
                    break;
                case 'pwm_oc1':
                    $model_obj = CG_PWMOCController::Instantiate('pwm_oc', $model_obj_arr['project']->id, '1');
                    break;
                case 'pwm_oc2':
                    $model_obj = CG_PWMOCController::Instantiate('pwm_oc', $model_obj_arr['project']->id, '2');
                    break;
                case 'pwm_oc3':
                    $model_obj = CG_PWMOCController::Instantiate('pwm_oc', $model_obj_arr['project']->id, '3');
                    break;
                case 'pwm_oc4':
                    $model_obj = CG_PWMOCController::Instantiate('pwm_oc', $model_obj_arr['project']->id, '4');
                    break;
                case 'pwm_oc5':
                    $model_obj = CG_PWMOCController::Instantiate('pwm_oc', $model_obj_arr['project']->id, '5');
                    break;
                case 'bit_banged_i2c':
                    $model_obj = CG_BitBangedI2CController::Instantiate($tab, $model_obj_arr['project']->id);
                    break;
                case 'main':
                    $model_obj = CG_MainController::Instantiate($tab, $model_obj_arr['project']->id);
                    break;
            }
            
            // Assign the form data values
            if(count($form_data_arr[$tab]) > 0)
            {
                foreach($model_obj::$attributes as $attribute)
                {
                    if(is_array($attribute))
                    {
                        foreach($attribute as $attr)
                        {
                            if($attr != 'id' && $attr != 'project_id' && $attr != 'user_id' && $attr != 'type')
                            {
                                $model_obj->$attr = $form_data_arr[$tab][$attr];
                            }
                        }
                        continue;
                    }
                    
                    if($attribute != 'id' && $attribute != 'project_id' && $attribute != 'user_id' && $attribute != 'type')
                    {
                        $model_obj->$attribute = $form_data_arr[$tab][$attribute];
                    }
                }
            }
            
            if(($result = $model_obj->Save()) !== TRUE)
            {
                $errors[$tab] = $result;
                // Stop the loop if saving the project fails
                if($tab == 'project')
                    break;
            }
            else
                $model_obj_arr[$tab] = $model_obj;
        }
        
        return (count($errors) <= 0) ? $model_obj_arr['project'] : $errors;
    }
    
    /*
     * Delete an specific CG Project
     * 
     * @param project ID
     * 
     * @return mixed (TRUE if success, array of string errors when fails)
     */
    public function ActionDeleteCGProject($project_id=0)
    {
        $errors = array();
        
        $tab_arr = array(
            'project', 'configuration', 'pin_assignment', 'portb',
            'serial_port1', 'serial_port2', 'timer1', 'pwm_timer2',
            'pwm_timer3', 'pwm_oc1', 'pwm_oc2', 'pwm_oc3', 'pwm_oc4',
             'pwm_oc5', 'bit_banged_i2c', 'main'
        );
        
        $model_obj_arr = array();
        
        foreach($tab_arr as $tab)
        {
            switch($tab)
            {
                case 'project':
                    $model_obj = $this::Instantiate($tab, $project_id);
                    break;
                case 'configuration':
                    $model_obj = CG_ConfigurationController::Instantiate($tab, $model_obj_arr['project']->id);
                    break;
                case 'pin_assignment':
                    $model_obj = CG_PinAssignmentController::Instantiate($tab, $model_obj_arr['project']->id);
                    break;
                case 'portb':
                    $model_obj = CG_PortBController::Instantiate($tab, $model_obj_arr['project']->id);
                    break;
                case 'serial_port1':
                    $model_obj = CG_SerialPortController::Instantiate('serial_port', $model_obj_arr['project']->id, '1');
                    break;
                case 'serial_port2':
                    $model_obj = CG_SerialPortController::Instantiate('serial_port', $model_obj_arr['project']->id, '2');
                    break;
                case 'timer1':
                    $model_obj = CG_Timer1Controller::Instantiate($tab, $model_obj_arr['project']->id);
                    break;
                case 'pwm_timer2':
                    $model_obj = CG_PWMTimerController::Instantiate('pwm_timer', $model_obj_arr['project']->id, '2');
                    break;
                case 'pwm_timer3':
                    $model_obj = CG_PWMTimerController::Instantiate('pwm_timer', $model_obj_arr['project']->id, '3');
                    break;
                case 'pwm_oc1':
                    $model_obj = CG_PWMOCController::Instantiate('pwm_oc', $model_obj_arr['project']->id, '1');
                    break;
                case 'pwm_oc2':
                    $model_obj = CG_PWMOCController::Instantiate('pwm_oc', $model_obj_arr['project']->id, '2');
                    break;
                case 'pwm_oc3':
                    $model_obj = CG_PWMOCController::Instantiate('pwm_oc', $model_obj_arr['project']->id, '3');
                    break;
                case 'pwm_oc4':
                    $model_obj = CG_PWMOCController::Instantiate('pwm_oc', $model_obj_arr['project']->id, '4');
                    break;
                case 'pwm_oc5':
                    $model_obj = CG_PWMOCController::Instantiate('pwm_oc', $model_obj_arr['project']->id, '5');
                    break;
                case 'bit_banged_i2c':
                    $model_obj = CG_BitBangedI2CController::Instantiate($tab, $model_obj_arr['project']->id);
                    break;
                case 'main':
                    $model_obj = CG_MainController::Instantiate($tab, $model_obj_arr['project']->id);
                    break;
            }
            
            if(($result = $model_obj->Delete()) !== TRUE)
            {
                $errors[$tab] = $result;
                // Stop the loop if deleting the project fails
                if($tab == 'project')
                    break;
            }
            else
                $model_obj_arr[$tab] = $model_obj;
        }
        
        return (count($errors) <= 0) ? $model_obj_arr['project'] : $errors;
    }
}