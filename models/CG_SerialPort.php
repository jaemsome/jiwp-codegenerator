<?php

class CG_SerialPort extends CG_ActiveRecord
{
    // PUBLIC PROPERTIES
    public static $attributes = array(
        'id', 'project_id', 'type',
        'BRGH', 'desiredBR', 'constantBR',
        'dataBits', 'parity', 'stopBits', 'flowControl', 'polarity',
        'loopBack', 'autoBaud', 'IREnable', 'wake', 'RTSMode'
    );
    public $id;
    public $project_id;
    public $type;
    public $BRGH;
    public $desiredBR;
    public $constantBR;
    public $dataBits;
    public $parity;
    public $stopBits;
    public $flowControl;
    public $polarity;
    public $loopBack;
    public $autoBaud;
    public $IREnable;
    public $wake;
    public $RTSMode;
    
    /*
     * Name of the database table used for storing serial port data
     */
    protected $_table_name = 'wp_kmi_serialport';
    
    private $processor_type;

    public static function model()
    {
        return new self;
    }
    
    public static function CalculateBaudRate($frequency, $BRGH, $desiredBR)
    {
        $baud_rate = 0;
        
        if($BRGH == 0)
            $baud_rate = ((($frequency / 2) * 1000000) / (16 * $desiredBR)) - 1;
        elseif($BRGH == 1)
            $baud_rate = ((($frequency / 2) * 1000000) / (4 * $desiredBR)) - 1;
        
        return (int)($baud_rate + .5);
    }
}