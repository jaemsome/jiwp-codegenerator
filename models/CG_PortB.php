<?php

class CG_PortB extends CG_ActiveRecord
{
    public static $attributes = array(
        'id', 'project_id',
        'bit_0', 'bit_1', 'bit_2', 'bit_3',
        'bit_4', 'bit_5', 'bit_6', 'bit_7',
        'bit_8', 'bit_9', 'bit_10', 'bit_11',
        'bit_12', 'bit_13', 'bit_14', 'bit_15'
    );
    public $id;
    public $project_id;
    public $bit_0;
    public $bit_1;
    public $bit_2;
    public $bit_3;
    public $bit_4;
    public $bit_5;
    public $bit_6;
    public $bit_7;
    public $bit_8;
    public $bit_9;
    public $bit_10;
    public $bit_11;
    public $bit_12;
    public $bit_13;
    public $bit_14;
    public $bit_15;
    
    /*
     * Name of the database table used for storing configuration data
     */
    protected $_table_name = 'wp_kmi_portb';
    
    public static function model()
    {
        return new self;
    }
}