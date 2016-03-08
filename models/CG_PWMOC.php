<?php

class CG_PWMOC extends CG_ActiveRecord
{
    public static $attributes = array('id', 'project_id', 'type', 'timer', 'single_continuous');
    public $id;
    public $project_id;
    public $type;
    public $timer;
    public $single_continuous;
    
    /*
     * Name of the database table used for storing pwm oc data
     */
    protected $_table_name = 'wp_kmi_pwmoc';
    
    public static function model()
    {
        return new self;
    }
    
    /*
     * Check for an empty attribute
     * 
     * @returns boolean
     */
    public function ContainsEmptyAttribute()
    {
        foreach(self::$attributes as $attribute)
        {
            if($this->$attribute == '')
            {
                $this->_error = 'All attributes cannot be empty';
                return true;
            }
        }
        
        return false;
    }
}