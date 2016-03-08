<?php

class CG_PWMTimer extends CG_ActiveRecord
{
    // PUBLIC PROPERTIES
    public static $attributes = array('id', 'project_id', 'type', 'pwm_period', 'pr_value', 'timer_prescale');
    public $id;
    public $project_id;
    public $type;
    public $pwm_period;
    public $pr_value;
    public $timer_prescale;
    
    /*
     * Name of the database table used for storing pwm timer data
     */
    protected $_table_name = 'wp_kmi_pwmtimer';
    
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