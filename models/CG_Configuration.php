<?php

class CG_Configuration extends CG_ActiveRecord
{
    public static $attributes = array(
        'id', 'project_id',
        'JTAG', 'GCP', 'GWRP', 'debug', 'EMPin', 'watchDog', 'watchWin', 'WDTPrescale', 'WDTPostscaler',
        'IESO', 'FNOSC', 'FCKSM', 'OSCIOFCN', 'IOL1WAY', 'I2C1SEL', 'POSCMD'
    );
    public $id;
    public $project_id;
    public $JTAG;
    public $GCP;
    public $GWRP;
    public $debug;
    public $EMPin;
    public $watchDog;
    public $watchWin;
    public $WDTPrescale;
    public $WDTPostscaler;
    public $IESO;
    public $FNOSC;
    public $FCKSM;
    public $OSCIOFCN;
    public $IOL1WAY;
    public $I2C1SEL;
    public $POSCMD;
    
    /*
     * Name of the database table used for storing configuration data
     */
    protected $_table_name = 'wp_kmi_configuration';
    
    /*
     * Create new instance of the class
     * 
     * @return CG_Configuration object
     */
    public static function model()
    {
        return new self;
    }
}