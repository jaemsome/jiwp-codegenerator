<?php

class CG_BitBangedI2C extends CG_ActiveRecord
{
    public static $attributes = array(
        'id', 'project_id', 'scl_port', 'scl_bit', 'sda_port', 'sda_bit'
    );
    public $id;
    public $project_id;
    public $scl_port;
    public $scl_bit;
    public $sda_port;
    public $sda_bit;
    
    /*
     * Name of the database table used for storing bit banged I2C data
     */
    protected $_table_name = 'wp_kmi_bitbanged';
    
    /*
     * Create new instance of the class
     * 
     * @return CG_BitBangedI2C object
     */
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
        foreach($this::$attributes as $attribute)
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