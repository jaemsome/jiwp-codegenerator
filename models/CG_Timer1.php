<?php

class CG_Timer1 extends CG_ActiveRecord
{
    // PUBLIC PROPERTIES
    public static $attributes = array('id', 'project_id', 'interrupt_number', 'reload_value', 't1_includes');
    public $id;
    public $project_id;
    public $interrupt_number;
    public $reload_value;
    public $t1_includes;
    
    /*
     * Name of the database table used for storing timer1 data
     */
    protected $_table_name = 'wp_kmi_timer1';
    
    public static function model()
    {
        return new self;
    }
    
    public static function CalculateReloadValue($frequency, $interrupt_number)
    {
        $frequency = $frequency / 2;
        
        $reload_value = ($frequency * 1000000) / 256;
        
        $reload_value = $reload_value / $interrupt_number;
        
        return (int)($reload_value + .5);
    }
}