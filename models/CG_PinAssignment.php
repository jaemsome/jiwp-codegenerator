<?php

class CG_PinAssignment extends CG_ActiveRecord
{
    private $_error;
    
    // PUBLIC PROPERTIES
    public static $attributes = array(
        'id', 'project_id',
        'input'         => array(
            'INT1', 'INT2',
            'IC1', 'IC2', 'IC3', 'IC4', 'IC5',
            'OCFA', 'OCFB',
            'U1RX', 'U2RX',
            'U1CTS', 'U2CTS',
            'SDI1', 'SDI2',
            'SCK1IN', 'SCK2IN',
            'SS1IN', 'SS2IN',
            'T2CK', 'T3CK', 'T4CK', 'T5CK'
        ),
        'output'        => array(
            'RP0', 'RP1', 'RP2', 'RP3', 'RP4', 'RP5', 'RP6', 'RP7', 'RP8',
            'RP9', 'RP10', 'RP11', 'RP12', 'RP13', 'RP14', 'RP15', 'RP16'
        )
    );
    public $id;
    public $project_id;
    public $processor_type;
    // INPUT
    public $INT1, $INT2;
    public $IC1, $IC2, $IC3, $IC4, $IC5;
    public $OCFA, $OCFB;
    public $U1RX, $U2RX;
    public $U1CTS, $U2CTS;
    public $SDI1, $SDI2;
    public $SCK1IN, $SCK2IN;
    public $SS1IN, $SS2IN;
    public $T2CK, $T3CK, $T4CK, $T5CK;
    //OUTPUT
    public $RP0, $RP1, $RP2, $RP3, $RP4, $RP5, $RP6, $RP7, $RP8;
    public $RP9, $RP10, $RP11, $RP12, $RP13, $RP14, $RP15, $RP16;
    
    /*
     * Name of the database table used for storing configuration data
     */
    protected $_table_name = 'wp_kmi_pinassignment';
    
    /*
     * Create new instance of the class
     * 
     * @return CG_PinAssignment object
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
        foreach(CG_PinAssignment::$attributes as $key => $data)
        {
            if($key === 'input' || $key === 'output')
            {
                foreach($data as $attribute)
                {
                    if($this->$attribute == '')
                    {
                        $this->_error = 'All attributes cannot be empty';
                        return true;
                    }
                }
            }
        }
        
        return false;
    }
    
    public function InitializeAttributeInCode($attribute='', $value='')
    {
        $output_values_arr = array(
            'C1OUT', 'C2OUT', 'U1TX', 'U1RTS', 'U2TX', 'U2RTS', 'SD01', 'SCK1OUT', 'SS1OUT',
            'SD02', 'SCK2OUT', 'SS2OUT', 'NULL', 'NULL', 'NULL', 'NULL', 'NULL', 'OC1', 'OC2', 'OC3', 'OC4', 'OC5'
        );
        
        $code = '';
        
        $INPUT_INIT = $code.$this->$attribute."; // assign {$attribute} pin RP {$value}".PHP_EOL;
        
//        if((int)$value > 0)
        $OUTPUT_INIT = $code.$this->$attribute."; // assign ".$output_values_arr[$value]." to pin {$attribute}".PHP_EOL;
//        else
//            $OUTPUT_INIT = $code.$this->$attribute."; // assign ".CG_PinAssignment::$attributes['output_values'][$value-1]." to pin {$attribute}".PHP_EOL;
        
        switch($attribute)
        {
            // INPUT
            case 'INT1':
                $code .= 'RPINR0bits.INT1R = '.$INPUT_INIT;
                break;
            
            case 'INT2':
                $code .= 'RPINR1bits.INT2R = '.$INPUT_INIT;
                break;
            
            case 'IC1':
                $code .= 'RPINR7bits.IC1R = '.$INPUT_INIT;
                break;
            
            case 'IC2':
                $code .= 'RPINR7bits.IC2R = '.$INPUT_INIT;
                break;
            
            case 'IC3':
                $code .= 'RPINR8bits.IC3R = '.$INPUT_INIT;
                break;
            
            case 'IC4':
                $code .= 'RPINR8bits.IC4R = '.$INPUT_INIT;
                break;
            
            case 'IC5':
                $code .= 'RPINR9bits.IC5R = '.$INPUT_INIT;
                break;
            
            case 'OCFA':
                $code .= 'RPINR11bits.OCFAR = '.$INPUT_INIT;
                break;
            
            case 'OCFB':
                $code .= 'RPINR11bits.OCFBR = '.$INPUT_INIT;
                break;
            
            case 'U1RX':
                $code .= 'RPINR18bits.U1RXR = '.$INPUT_INIT;
                break;
            
            case 'U2RX':
                $code .= 'RPINR19bits.U2RXR = '.$INPUT_INIT;
                break;
            
            case 'U1CTS':
                $code .= 'RPINR18bits.U1CTSR = '.$INPUT_INIT;
                break;
            
            case 'U2CTS':
                $code .= 'RPINR19bits.U2CTSR = '.$INPUT_INIT;
                break;
            
            case 'SDI1':
                $code .= 'RPINR20bits.SDI1R = '.$INPUT_INIT;
                break;
            
            case 'SDI2':
                $code .= 'RPINR22bits.SDI2R = '.$INPUT_INIT;
                break;
            
            case 'SS1IN':
                $code .= 'RPINR21bits.SS1R = '.$INPUT_INIT;
                break;
            
            case 'SS2IN':
                $code .= 'RPINR23bits.SS2R = '.$INPUT_INIT;
                break;
            
            case 'SCK1IN':
                $code .= 'RPINR20bits.SCK1R = '.$INPUT_INIT;
                break;
            
            case 'SCK2IN':
                $code .= 'RPINR22bits.SCK2R = '.$INPUT_INIT;
                break;
            
            case 'T2CK':
                $code .= 'RPINR3bits.T2CKR = '.$INPUT_INIT;
                break;
            
            case 'T3CK':
                $code .= 'RPINR3bits.T3CKR = '.$INPUT_INIT;
                break;
            
            case 'T4CK':
                $code .= 'RPINR4bits.T4CKR = '.$INPUT_INIT;
                break;
            
            case 'T5CK':
                $code .= 'RPINR3bits.T5CKR = '.$INPUT_INIT;
                break;
            
            // OUTPUT
            case 'RP0':
                $code .= 'RPOR0bits.RP0R = '.$OUTPUT_INIT;
                break;
            
            case 'RP1':
                $code .= 'RPOR0bits.RP1R = '.$OUTPUT_INIT;
                break;
            
            case 'RP2':
                $code .= 'RPOR1bits.RP2R = '.$OUTPUT_INIT;
                break;
            
            case 'RP3':
                $code .= 'RPOR1bits.RP3R = '.$OUTPUT_INIT;
                break;
            
            case 'RP4':
                $code .= 'RPOR2bits.RP4R = '.$OUTPUT_INIT;
                break;
            
            case 'RP5':
                $code .= 'RPOR2bits.RP5R = '.$OUTPUT_INIT;
                break;
            
            case 'RP6':
                $code .= 'RPOR3bits.RP6R = '.$OUTPUT_INIT;
                break;
            
            case 'RP7':
                $code .= 'RPOR3bits.RP7R = '.$OUTPUT_INIT;
                break;
            
            case 'RP8':
                $code .= 'RPOR4bits.RP8R = '.$OUTPUT_INIT;
                break;
            
            case 'RP9':
                $code .= 'RPOR4bits.RP9R = '.$OUTPUT_INIT;
                break;
            
            case 'RP10':
                $code .= 'RPOR5bits.RP10R = '.$OUTPUT_INIT;
                break;
            
            case 'RP11':
                $code .= 'RPOR5bits.RP11R = '.$OUTPUT_INIT;
                break;
            
            case 'RP12':
                $code .= 'RPOR6bits.RP12R = '.$OUTPUT_INIT;
                break;
            
            case 'RP13':
                $code .= 'RPOR6bits.RP13R = '.$OUTPUT_INIT;
                break;
            
            case 'RP14':
                $code .= 'RPOR7bits.RP14R = '.$OUTPUT_INIT;
                break;
            
            case 'RP15':
                $code .= 'RPOR7bits.RP15R = '.$OUTPUT_INIT;
                break;
            
            case 'RP16':
                $code .= 'RPOR8bits.RP16R = '.$OUTPUT_INIT;
                break;
        }
        
        return $code;
    }
}