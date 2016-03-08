<?php

class CG_PortBController extends CG_Controller
{
    /*
     * Gets the bit value based on the selected bits
     * on the portb tab
     */
    public static function GetPortBValue($portb_data='')
    {
        // Get portb post data
        $portb_post = !empty($portb_data) ? $portb_data : $_POST['portb'];
        
        $bits = 0;
        
        if(!empty($portb_post['bit_0']))
            $bits += 1;
        if(!empty($portb_post['bit_1']))
            $bits += 2;
        if(!empty($portb_post['bit_2']))
            $bits += 4;
        if(!empty($portb_post['bit_3']))
            $bits += 8;
        if(!empty($portb_post['bit_4']))
            $bits += 16;
        if(!empty($portb_post['bit_5']))
            $bits += 32;
        if(!empty($portb_post['bit_6']))
            $bits += 64;
        if(!empty($portb_post['bit_7']))
            $bits += 128;
        if(!empty($portb_post['bit_8']))
            $bits += 256;
        if(!empty($portb_post['bit_9']))
            $bits += 512;
        if(!empty($portb_post['bit_10']))
            $bits += 1024;
        if(!empty($portb_post['bit_11']))
            $bits += 2048;
        if(!empty($portb_post['bit_12']))
            $bits += 4096;
        if(!empty($portb_post['bit_13']))
            $bits += 8192;
        if(!empty($portb_post['bit_14']))
            $bits += 16384;
        if(!empty($portb_post['bit_15']))
            $bits += 32768;

        return dechex($bits);
    }
}