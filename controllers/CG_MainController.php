<?php

class CG_MainController extends CG_Controller
{
    protected function _GenerateCCode($model=NULL, $processor_type='')
    {
        $code = '/************************************************'.PHP_EOL;
        $code .= ' * main.c is automatically generated by PicCodeGen'.PHP_EOL;
        $code .= ' * Please do not change.'.PHP_EOL;
        $code .= ' *'.PHP_EOL;
        $code .= ' * To obtain a copy of PicCodeGen go to'.PHP_EOL;
        $code .= ' * http://www.kmitechnology.com/.'.PHP_EOL;
        $code .= ' *'.PHP_EOL;
        $code .= ' ***********************************************/'.PHP_EOL;
        $code .= PHP_EOL;
        $code .= '#define USE_AND_OR'.PHP_EOL;
        $code .= PHP_EOL;
        $code .= '#include "uart.h"'.PHP_EOL;
        if(!empty($model->pin_assignment))
        {
            $code .= '#include "pinAssign.h"'.PHP_EOL;
            $this->_var_arr['includes'][] = 'pinAssign';
        }
        if(!empty($model->serial_port1))
        {
            $code .= '#include "uart1.h"'.PHP_EOL;
            $this->_var_arr['includes'][] = 'uart1';
        }
        if(!empty($model->serial_port2))
        {
            $code .= '#include "uart2.h"'.PHP_EOL;
            $this->_var_arr['includes'][] = 'uart2';
        }
        if(!empty($model->timer1))
        {
            $code .= '#include "timer1.h"'.PHP_EOL;
            $this->_var_arr['includes'][] = 'timer1';
        }
        if(!empty($model->SSRTOS))
        {
            $code .= '#include "ssRTOS.h"'.PHP_EOL;
            $this->_var_arr['includes'][] = 'ssrtos';
        }
        $code .= PHP_EOL;
        $code .= "#include <{$processor_type}.h>".PHP_EOL;
        $code .= PHP_EOL;
        if(!empty($model->configuration))
        {
            $code .= '#include "config.c"'.PHP_EOL;
            $this->_var_arr['includes'][] = 'config';
        }
        $code .= PHP_EOL;
        if(!empty($model->before_main_includes))
        {
            $code .= '// User code here'.PHP_EOL;
            $code .= $model->before_main_includes.PHP_EOL;
        }
        $code .= PHP_EOL;
        $code .= 'int'.PHP_EOL;
        $code .= 'main(void)'.PHP_EOL;
        $code .= '{'.PHP_EOL;
        $code .= '    CLKDIV = 0;'.PHP_EOL;
        if(!empty($model->pin_assignment))
            $code .= '    pinAssign();'.PHP_EOL;
        if(!empty($model->serial_port1))
            $code .= '    uart1Init(config1, config2, BRATE);'.PHP_EOL;
        if(!empty($model->serial_port2))
            $code .= '    uart2Init(config21, config22, BRATE2);'.PHP_EOL;
        if(!empty($model->timer1))
            $code .= '    timer1Init();'.PHP_EOL;
        if(!empty($model->SSRTOS))
            $code .= '    ssRTOSInit();'.PHP_EOL;
        $code .= PHP_EOL;
        if(!empty($model->port_bits))
            $code .= '    TRISB = 0x'.strtoupper(CG_PortBController::GetPortBValue($this->_var_arr['POST']['portb'])).';';
        $code .= PHP_EOL;
        if(!empty($model->inside_main_includes))
        {
            $code .= '   // User code here'.PHP_EOL;
            $code .= '   '.$model->inside_main_includes.PHP_EOL;
        }
        $code .= "}".PHP_EOL;
        
        if(!empty($model->PWM))
            $this->_var_arr['includes'][] = 'pwm';
        
        if(!empty($model->bit_banged_I2C))
            $this->_var_arr['includes'][] = 'bbI2C';
        
        return $code;
    }
    
    protected function _GenerateHCode()
    {
        $hFile = '/************************************************'.PHP_EOL;
        $hFile .= ' * main.h is automatically generated by PicCodeGen'.PHP_EOL;
        $hFile .= ' * Please do not change.'.PHP_EOL;
        $hFile .= ' *'.PHP_EOL;
        $hFile .= ' * To obtain a copy of PicCodeGen go to'.PHP_EOL;
        $hFile .= ' * http://www.kmitechnology.com/.'.PHP_EOL;
        $hFile .= ' *'.PHP_EOL;
        $hFile .= ' ***********************************************/'.PHP_EOL;
        $hFile .= PHP_EOL.PHP_EOL.PHP_EOL;
        
        return $hFile;
    }
}