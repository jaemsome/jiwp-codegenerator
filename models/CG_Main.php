<?php

class CG_Main extends CG_ActiveRecord
{
    public static $attributes = array(
        'id', 'project_id',
        'configuration', 'pin_assignment', 'port_bits', 'serial_port1', 'serial_port2', 
        'timer1', 'PWM', 'bit_banged_I2C', 'I2C', 'SSRTOS'
    );
    public $id;
    public $project_id;
    public $configuration;
    public $pin_assignment;
    public $port_bits;
    public $serial_port1;
    public $serial_port2;
    public $timer1;
    public $PWM;
    public $bit_banged_I2C;
    public $I2C;
    public $SSRTOS;
    public $before_main_includes;
    public $inside_main_includes;
    
    /*
     * Name of the database table used for storing project data
     */
    protected $_table_name = 'wp_kmi_main';
    
    public static function model()
    {
        return new self;
    }
}