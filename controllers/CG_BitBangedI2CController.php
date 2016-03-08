<?php

class CG_BitBangedI2CController extends CG_Controller
{
    protected function _GenerateCCode($model=NUll, $processor_type='')
    {
        /* SCLPORT */
        if($model->scl_port == 'PORTA')
        {
            $latSCLName = 'LATA';
            $portSCLName = 'PORTA';
            $portSCLTris = 'TRISA';
        }
        else if($model->scl_port == 'PORTB')
        {
            $latSCLName = 'LATB';
            $portSCLName = 'PORTB';
            $portSCLTris = 'TRISB';
        }
        else if($model->scl_port == 'PORTC')
        {
            $latSCLName = 'LATC';
            $portSCLName = 'PORTC';
            $portSCLTris = 'TRISC';
        }
        else if($model->scl_port == 'PORTD')
        {
            $latSCLName = 'LATD';
            $portSCLName = 'PORTD';
            $portSCLTris = 'TRISD';
        }
        else if($model->scl_port == 'PORTE')
        {
            $latSCLName = 'LATE';
            $portSCLName = 'PORTE';
            $portSCLTris = 'TRISE';
        }
        else if($model->scl_port == 'PORTJ')
        {
            $latSCLName = 'LATJ';
            $portSCLName = 'PORTJ';
            $portSCLTris = 'TRISJ';
        }
        
        /* SDAPORT */
        if($model->sda_port == 'PORTA')
        {
            $latSDAName = 'LATA';
            $portSDAName = 'PORTA';
            $portSDATris = 'TRISA';
        }
        else if($model->sda_port == 'PORTB')
        {
            $latSDAName = 'LATB';
            $portSDAName = 'PORTB';
            $portSDATris = 'TRISB';
        }
        else if($model->sda_port == 'PORTC')
        {
            $latSDAName = 'LATC';
            $portSDAName = 'PORTC';
            $portSDATris = 'TRISC';
        }
        else if($model->sda_port == 'PORTD')
        {
            $latSDAName = 'LATD';
            $portSDAName = 'PORTD';
            $portSDATris = 'TRISD';
        }
        else if($model->sda_port == 'PORTE')
        {
            $latSDAName = 'LATE';
            $portSDAName = 'PORTE';
            $portSDATris = 'TRISE';
        }
        else if($model->sda_port == 'PORTJ')
        {
            $latSDAName = 'LATJ';
            $portSDAName = 'PORTJ';
            $portSDATris = 'TRISJ';
        }
        
        /* SCLBIT */
        if($model->scl_bit == 'Bit 0')
            $bitSCL = '0x0001';
        else if($model->scl_bit == 'Bit 1')
            $bitSCL = '0x0002';
        else if($model->scl_bit == 'Bit 2')
            $bitSCL = '0x0004';
        else if($model->scl_bit == 'Bit 3')
            $bitSCL = '0x0008';
        else if($model->scl_bit == 'Bit 4')
            $bitSCL = '0x0010';
        else if($model->scl_bit == 'Bit 5')
            $bitSCL = '0x0020';
        else if($model->scl_bit == 'Bit 6')
            $bitSCL = '0x0040';
        else if($model->scl_bit == 'Bit 7')
            $bitSCL = '0x0080';
        else if($model->scl_bit == 'Bit 8')
            $bitSCL = '0x0100';
        else if($model->scl_bit == 'Bit 9')
            $bitSCL = '0x0200';
        else if($model->scl_bit == 'Bit 10')
            $bitSCL = '0x0400';
        else if($model->scl_bit == 'Bit 11')
            $bitSCL = '0x0800';
        else if($model->scl_bit == 'Bit 12')
            $bitSCL = '0x1000';
        else if($model->scl_bit == 'Bit 13')
            $bitSCL = '0x2000';
        else if($model->scl_bit == 'Bit 14')
            $bitSCL = '0x4000';
        else if($model->scl_bit == 'Bit 15')
            $bitSCL = '0x8000';
        
        /* SDABIT */
        if($model->sda_bit == 'Bit 0')
            $bitSDA = '0x0001';
        else if($model->sda_bit == 'Bit 1')
            $bitSDA = '0x0002';
        else if($model->sda_bit == 'Bit 2')
            $bitSDA = '0x0004';
        else if($model->sda_bit == 'Bit 3')
            $bitSDA = '0x0008';
        else if($model->sda_bit == 'Bit 4')
            $bitSDA = '0x0010';
        else if($model->sda_bit == 'Bit 5')
            $bitSDA = '0x0020';
        else if($model->sda_bit == 'Bit 6')
            $bitSDA = '0x0040';
        else if($model->sda_bit == 'Bit 7')
            $bitSDA = '0x0080';
        else if($model->sda_bit == 'Bit 8')
            $bitSDA = '0x0100';
        else if($model->sda_bit == 'Bit 9')
            $bitSDA = '0x0200';
        else if($model->sda_bit == 'Bit 10')
            $bitSDA = '0x0400';
        else if($model->sda_bit == 'Bit 11')
            $bitSDA = '0x0800';
        else if($model->sda_bit == 'Bit 12')
            $bitSDA = '0x1000';
        else if($model->sda_bit == 'Bit 13')
            $bitSDA = '0x2000';
        else if($model->sda_bit == 'Bit 14')
            $bitSDA = '0x4000';
        else if($model->sda_bit == 'Bit 15')
            $bitSDA = '0x8000';
        
//        if(!$model->ContainsEmptyAttribute())
//        {
            $code = PHP_EOL.'/************************************************'.PHP_EOL;
            $code .= ' * To obtain a copy of PicCodeGen go to'.PHP_EOL;
            $code .= ' * http://www.kmitechnology.com/.'.PHP_EOL;
            $code .= ' *'.PHP_EOL;
            $code .= ' ************************************************/'.PHP_EOL;
            $code .= PHP_EOL;
            $code .= '#include "bbI2C.h"'.PHP_EOL;
            $code .= PHP_EOL;
            $code .= PHP_EOL;
            $code .= "#include <{$processor_type}.h>".PHP_EOL;
            $code .= PHP_EOL;
            $code .= PHP_EOL;
            $code .= '#define I2CSDA1 '.$portSDAName.' = '.$portSDAName.' | '.$bitSDA.PHP_EOL;
            $code .= '#define I2CSDA0 '.$portSDAName.' = '.$portSDAName.' & ~'.$bitSDA.PHP_EOL;
            $code .= '#define I2CSDA ('.$portSDAName.' & '.$bitSDA.')'.PHP_EOL;
            $code .= PHP_EOL;
            $code .= '#define I2CSDAIN ('.$portSDATris.' = '.$portSDATris.' | '.$bitSDA.')'.PHP_EOL;
            $code .= '#define I2CSDAOUT ('.$portSDATris.' = '.$portSDATris.' & ~'.$bitSDA.')'.PHP_EOL;
            $code .= PHP_EOL;
            $code .= '#define I2CSCL1 '.$portSCLName.' = '.$portSCLName.' | '.$bitSCL.PHP_EOL;
            $code .= '#define I2CSCL0 '.$portSCLName.' = '.$portSCLName.' & ~'.$bitSCL.PHP_EOL;
            $code .= '#define I2CSCL ('.$portSCLName.' & '.$bitSCL.')'.PHP_EOL;
            $code .= PHP_EOL;
            $code .= 'void'.PHP_EOL;
            $code .= 'i2cSDA1(void)'.PHP_EOL;
            $code .= '{'.PHP_EOL;
            $code .= '    '.$latSDAName.' = '.$latSDAName.' | '.$bitSDA.';'.PHP_EOL;
            $code .= '}'.PHP_EOL;
            $code .= PHP_EOL;
            $code .= 'void'.PHP_EOL;
            $code .= 'i2cSDA0(void)'.PHP_EOL;
            $code .= '{'.PHP_EOL;
            $code .= '    '.$portSDAName.' = '.$portSDAName.' & ~'.$bitSDA.';'.PHP_EOL;
            $code .= '}'.PHP_EOL;
            $code .= PHP_EOL;
            $code .= 'unsigned int'.PHP_EOL;
            $code .= 'i2cSDA(void)'.PHP_EOL;
            $code .= '{'.PHP_EOL;
            $code .= '    return '.$portSDAName.' & '.$bitSDA.';'.PHP_EOL;
            $code .= '}'.PHP_EOL;
            $code .= PHP_EOL;
            $code .= 'void'.PHP_EOL;
            $code .= 'i2cSDAIN(void)'.PHP_EOL;
            $code .= '{'.PHP_EOL;
            $code .= '    '.$portSDATris.' = '.$portSDATris.' | '.$bitSDA.';'.PHP_EOL;
            $code .= '}'.PHP_EOL;
            $code .= PHP_EOL;
            $code .= 'void'.PHP_EOL;
            $code .= 'i2cSDAOUT(void)'.PHP_EOL;
            $code .= '{'.PHP_EOL;
            $code .= '    '.$portSDATris.' = '.$portSDATris.' & ~'.$bitSDA.';'.PHP_EOL;
            $code .= '}'.PHP_EOL;
            $code .= PHP_EOL;
            $code .= 'void'.PHP_EOL;
            $code .= 'i2cSCL1(void)'.PHP_EOL;
            $code .= '{'.PHP_EOL;
            $code .= '    '.$latSCLName.' = '.$latSCLName.' | '.$bitSCL.';'.PHP_EOL;
            $code .= '}'.PHP_EOL;
            $code .= PHP_EOL;
            $code .= 'void'.PHP_EOL;
            $code .= 'i2cSCL0(void)'.PHP_EOL;
            $code .= '{'.PHP_EOL;
            $code .= '    '.$portSCLName.' = '.$portSCLName.' & ~'.$bitSCL.';'.PHP_EOL;
            $code .= '}'.PHP_EOL;
            $code .= PHP_EOL;
            $code .= 'unsigned int'.PHP_EOL;
            $code .= 'i2cSCL(void)'.PHP_EOL;
            $code .= '{'.PHP_EOL;
            $code .= '    return '.$portSCLName.' & '.$bitSCL.';'.PHP_EOL;
            $code .= '}'.PHP_EOL;
            $code .= PHP_EOL;
            $code .= 'unsigned char NoACK;'.PHP_EOL;
            $code .= PHP_EOL;
            $code .= '//'.PHP_EOL;
            $code .= '//! @par Function:'.PHP_EOL;
            $code .= '//! SyncClock'.PHP_EOL;
            $code .= '//!'.PHP_EOL;
            $code .= '//! @par Synopsis:'.PHP_EOL;
            $code .= '//! This routine verifies that the scl is free.  If'.PHP_EOL;
            $code .= '//! this I/O bit remains low then I can\'t toggle it to talk to'.PHP_EOL;
            $code .= '//! the I2C device attached to the bus.'.PHP_EOL;
            $code .= '//!'.PHP_EOL;
            $code .= '//! @return 1 when the clock is working and 0 if the clock isn\'t'.PHP_EOL;
            $code .= '//! working.'.PHP_EOL;
            $code .= '//!'.PHP_EOL;
            $code .= '//! @par Globals:'.PHP_EOL;
            $code .= '//! None'.PHP_EOL;
            $code .= '//'.PHP_EOL;
            $code .= 'unsigned char'.PHP_EOL;
            $code .= 'SyncClock(void) '.PHP_EOL;
            $code .= '{'.PHP_EOL;
            $code .= '   int clockbad;'.PHP_EOL;
            $code .= '   // Configure Timer 0 as a 16-bit timer '.PHP_EOL;
            $code .= PHP_EOL;
            $code .= '   clockbad = 0;'.PHP_EOL;
            $code .= '   // Try to synchronise the clock'.PHP_EOL;
            $code .= '   while ((I2CSCL == 0) && (clockbad < 16384)) '.PHP_EOL;
            $code .= '      clockbad++; '.PHP_EOL;
            $code .= PHP_EOL;
            $code .= '   if (clockbad == 16384)'.PHP_EOL;
            $code .= '   {'.PHP_EOL;
            $code .= '         return 0;  // Error - Timeout condition failed'.PHP_EOL;
            $code .= '   }'.PHP_EOL;
            $code .= '   return 1;  // OK - Clock synchronised'.PHP_EOL;
            $code .= '}'.PHP_EOL;
            $code .= PHP_EOL;
            $code .= '//'.PHP_EOL;
            $code .= '//! @par Procedure:'.PHP_EOL;
            $code .= '//! i2cinit'.PHP_EOL;
            $code .= '//!'.PHP_EOL;
            $code .= '//! @par Synopsis:'.PHP_EOL;
            $code .= '//! This routine grabs the necessary two bits for'.PHP_EOL;
            $code .= '//! I2C communication.  It then puts them into the proper state.'.PHP_EOL;
            $code .= '//!'.PHP_EOL;
            $code .= '//! @par Globals:'.PHP_EOL;
            $code .= '//! Changes TRISJ, I2CSDA1, and I2CSCL1.'.PHP_EOL;
            $code .= '//'.PHP_EOL;
            $code .= 'void'.PHP_EOL;
            $code .= 'i2cinit(void)'.PHP_EOL;
            $code .= '{'.PHP_EOL;
            $code .= '	i2cSDA1();'.PHP_EOL;
            $code .= '	i2cSCL1();'.PHP_EOL;
            $code .= '	'.$portSDATris.' &= ~'.$bitSDA.';'.PHP_EOL;
            $code .= '	'.$portSCLTris.' &= ~'.$bitSCL.';'.PHP_EOL;
            $code .= '//#define I2CSDAOUT ('.$portSDATris.' &= ~'.$bitSDA.')'.PHP_EOL;
            $code .= '//   TRISJ &= ~0x03;'.PHP_EOL;
            $code .= '}'.PHP_EOL;
            $code .= PHP_EOL;
            $code .= '//'.PHP_EOL;
            $code .= '//! @par Procedure:'.PHP_EOL;
            $code .= '//! i2crelease'.PHP_EOL;
            $code .= '//!'.PHP_EOL;
            $code .= '//! @par Synopsis:'.PHP_EOL;
            $code .= '//! This routine returns the two I2C bits back to the'.PHP_EOL;
            $code .= '//! system.  It changes them to input.'.PHP_EOL;
            $code .= '//! @par Globals:'.PHP_EOL;
            $code .= '//! Changes TRISJ'.PHP_EOL;
            $code .= '//'.PHP_EOL;
            $code .= 'void'.PHP_EOL;
            $code .= 'i2crelease(void)'.PHP_EOL;
            $code .= '{'.PHP_EOL;
            $code .= '//   TRISJ |= 0x03;'.PHP_EOL;
            $code .= '}'.PHP_EOL;
            $code .= "".PHP_EOL;
            $code .= "//".PHP_EOL;
            $code .= " //! @par Procedure:".PHP_EOL;
            $code .= "//! i2cdelay".PHP_EOL;
            $code .= "//!".PHP_EOL;
            $code .= "//! @par Synopsis:".PHP_EOL;
            $code .= "//! This routine is a simple delay routine.".PHP_EOL;
            $code .= "//!".PHP_EOL;
            $code .= "//! @par globals:".PHP_EOL;
            $code .= "//! none".PHP_EOL;
            $code .= "//".PHP_EOL;
            $code .= "void".PHP_EOL;
            $code .= "i2cdelay(void) ".PHP_EOL;
            $code .= "{".PHP_EOL;
            $code .= "	long x;".PHP_EOL;
            $code .= "".PHP_EOL;
            $code .= "	x = 0;".PHP_EOL;
            $code .= "	x++;".PHP_EOL;
            $code .= "	x++;".PHP_EOL;
            $code .= "	x++;".PHP_EOL;
            $code .= "	x++;".PHP_EOL;
            $code .= "	x++;".PHP_EOL;
            $code .= "	x++;".PHP_EOL;
            $code .= "}".PHP_EOL;
            $code .= "".PHP_EOL;
            $code .= "".PHP_EOL;
            $code .= "//".PHP_EOL;
            $code .= "//! @par Procedure:".PHP_EOL;
            $code .= "//! i2cstart".PHP_EOL;
            $code .= "//!".PHP_EOL;
            $code .= "//! @par Synopsis:".PHP_EOL;
            $code .= "//! This procedure generates a I2C start condition on the".PHP_EOL;
            $code .= "//! two I2C lines.".PHP_EOL;
            $code .= "//!".PHP_EOL;
            $code .= "//! @par Globals:".PHP_EOL;
            $code .= "//! changes I2CSDA and I2CSCL".PHP_EOL;
            $code .= "//".PHP_EOL;
            $code .= "void".PHP_EOL;
            $code .= "i2cstart(void) ".PHP_EOL;
            $code .= "{".PHP_EOL;
            $code .= "	i2cSDA1();".PHP_EOL;
            $code .= "	i2cSCL1();".PHP_EOL;
            $code .= "	if (SyncClock())".PHP_EOL;
            $code .= "      ;".PHP_EOL;
            $code .= "".PHP_EOL;
            $code .= "	i2cdelay();".PHP_EOL;
            $code .= "	i2cSDA0();".PHP_EOL;
            $code .= "	i2cdelay();".PHP_EOL;
            $code .= "	i2cSCL0();".PHP_EOL;
            $code .= "	i2cdelay();".PHP_EOL;
            $code .= "	i2cSDA1();".PHP_EOL;
            $code .= "}".PHP_EOL;
            $code .= "".PHP_EOL;
            $code .= "//".PHP_EOL;
            $code .= "//! @par Procedure:".PHP_EOL;
            $code .= "//! i2cstop".PHP_EOL;
            $code .= "//! ".PHP_EOL;
            $code .= "//! @par Synopsis:".PHP_EOL;
            $code .= "//! This routine generates a stop conditions on the I2C pins.".PHP_EOL;
            $code .= "//!".PHP_EOL;
            $code .= "//! @par Globals:".PHP_EOL;
            $code .= "//! Changes I2CSDA AND I2CSCL".PHP_EOL;
            $code .= "//".PHP_EOL;
            $code .= "void".PHP_EOL;
            $code .= "i2cstop(void) ".PHP_EOL;
            $code .= "{".PHP_EOL;
            $code .= "	i2cSDA0();".PHP_EOL;
            $code .= "	i2cdelay();".PHP_EOL;
            $code .= "	i2cSCL1();".PHP_EOL;
            $code .= "	i2cdelay();".PHP_EOL;
            $code .= "	i2cSDA1();".PHP_EOL;
            $code .= "}".PHP_EOL;
            $code .= "".PHP_EOL;
            $code .= "//".PHP_EOL;
            $code .= "//! @par Procedure:".PHP_EOL;
            $code .= "//! i2cres".PHP_EOL;
            $code .= "//!".PHP_EOL;
            $code .= "//! @par Synopsis:".PHP_EOL;
            $code .= "//! This procedure resets all I2C devices on the I2C bus.".PHP_EOL;
            $code .= "//!".PHP_EOL;
            $code .= "//! @par Globals:".PHP_EOL;
            $code .= "//! Changes I2CSCL and I2CSDA".PHP_EOL;
            $code .= "//".PHP_EOL;
            $code .= "void".PHP_EOL;
            $code .= "i2cres(void) ".PHP_EOL;
            $code .= "{".PHP_EOL;
            $code .= "	unsigned char i;".PHP_EOL;
            $code .= "".PHP_EOL;
            $code .= "	for (i=0; i<9; i++) {".PHP_EOL;
            $code .= "		i2cSCL0();".PHP_EOL;
            $code .= "		i2cSDA0();".PHP_EOL;
            $code .= "		i2cstop();".PHP_EOL;
            $code .= "	}".PHP_EOL;
            $code .= "}".PHP_EOL;
            $code .= "".PHP_EOL;
            $code .= "//".PHP_EOL;
            $code .= "//! @par Procedure:".PHP_EOL;
            $code .= "//! i2csendbyte".PHP_EOL;
            $code .= "//".PHP_EOL;
            $code .= "//! @par Synopsis:".PHP_EOL;
            $code .= "//! This procedure sends a byte to an addressed I2C device.".PHP_EOL;
            $code .= "//! This routine expects a start to have been generated along with the I2C".PHP_EOL;
            $code .= "//! device has been addressed.  It doesn't do a i2cstop.  It leaves the".PHP_EOL;
            $code .= "//! bus in a state to send another byte.".PHP_EOL;
            $code .= "//".PHP_EOL;
            $code .= "//! @param bytetosend byte to send".PHP_EOL;
            $code .= "//".PHP_EOL;
            $code .= "//! @par Globals:".PHP_EOL;
            $code .= "//! Changes I2CSDA and I2CSCL".PHP_EOL;
            $code .= "//".PHP_EOL;
            $code .= "void".PHP_EOL;
            $code .= "i2csendbyte(unsigned char bytetosend) ".PHP_EOL;
            $code .= "{".PHP_EOL;
            $code .= "	unsigned char i;".PHP_EOL;
            $code .= "".PHP_EOL;
            $code .= "	i = 8;".PHP_EOL;
            $code .= "".PHP_EOL;
            $code .= "".PHP_EOL;
            $code .= "	while (i > 0) {".PHP_EOL;
            $code .= "		i--;".PHP_EOL;
            $code .= "		if (bytetosend & (1 << i)) {".PHP_EOL;
            $code .= "			i2cSDA1();".PHP_EOL;
            $code .= "		} else {".PHP_EOL;
            $code .= "			i2cSDA0();".PHP_EOL;
            $code .= "		}".PHP_EOL;
            $code .= "		i2cdelay();".PHP_EOL;
            $code .= "		i2cSCL1();".PHP_EOL;
            $code .= "		if (SyncClock())".PHP_EOL;
            $code .= "         ;".PHP_EOL;
            $code .= "		i2cdelay();".PHP_EOL;
            $code .= "		i2cSCL0();".PHP_EOL;
            $code .= "		//i2cdelay();".PHP_EOL;
            $code .= "	}".PHP_EOL;
            $code .= "	//i2cSDA1();".PHP_EOL;
            $code .= "}".PHP_EOL;
            $code .= "".PHP_EOL;
            $code .= "//".PHP_EOL;
            $code .= "//! @par Function:".PHP_EOL;
            $code .= "//! i2cgetack".PHP_EOL;
            $code .= "//!".PHP_EOL;
            $code .= "//! @par Synopsis:".PHP_EOL;
            $code .= "//! This function waits for an ack from the I2C device.  If the".PHP_EOL;
            $code .= "//! device doesn't ack a NoACK value is returned.".PHP_EOL;
            $code .= "//!".PHP_EOL;
            $code .= "//! @par Globals:".PHP_EOL;
            $code .= "//! Changes NoAck, I2CSDAIN, I2CSCL, and I2CSDA".PHP_EOL;
            $code .= "//".PHP_EOL;
            $code .= "unsigned char".PHP_EOL;
            $code .= "i2cgetack(void) ".PHP_EOL;
            $code .= "{".PHP_EOL;
            $code .= "	NoACK = 0;".PHP_EOL;
            $code .= "	i2cSDAIN();".PHP_EOL;
            $code .= "	//i2cSDA1();".PHP_EOL;
            $code .= "   i2cdelay();".PHP_EOL;
            $code .= "	i2cSCL1();".PHP_EOL;
            $code .= "	if (SyncClock())".PHP_EOL;
            $code .= "      ;".PHP_EOL;
            $code .= "	i2cdelay();".PHP_EOL;
            $code .= "	//while (i2csda != 0) ;".PHP_EOL;
            $code .= "	NoACK = i2cSDA();".PHP_EOL;
            $code .= "	i2cSCL0();".PHP_EOL;
            $code .= "	i2cSDAOUT();".PHP_EOL;
            $code .= "   i2cdelay();".PHP_EOL;
            $code .= "   i2cdelay();".PHP_EOL;
            $code .= "	return NoACK;".PHP_EOL;
            $code .= "}".PHP_EOL;
            $code .= "".PHP_EOL;
            $code .= "//".PHP_EOL;
            $code .= "//! @par Function:".PHP_EOL;
            $code .= "//! i2cgetbyte".PHP_EOL;
            $code .= "//!".PHP_EOL;
            $code .= "//! @par Synopsis:".PHP_EOL;
            $code .= "//! This function reads a byte from the I2C device.  It returns".PHP_EOL;
            $code .= "//! this byte to the calling routine.".PHP_EOL;
            $code .= "//!".PHP_EOL;
            $code .= "//! @par Globals:".PHP_EOL;
            $code .= "//! Changes I2CSCL, I2CSDA, and I2CSDAIN".PHP_EOL;
            $code .= "//".PHP_EOL;
            $code .= "unsigned char".PHP_EOL;
            $code .= "i2cgetbyte(void) ".PHP_EOL;
            $code .= "{".PHP_EOL;
            $code .= "	unsigned char i;".PHP_EOL;
            $code .= "	unsigned char rxbyte;".PHP_EOL;
            $code .= "".PHP_EOL;
            $code .= "	rxbyte = 0;".PHP_EOL;
            $code .= "	i = 8;".PHP_EOL;
            $code .= "".PHP_EOL;
            $code .= "	i2cSDA1();".PHP_EOL;
            $code .= "	i2cSDAIN();".PHP_EOL;
            $code .= "	while (i > 0) {".PHP_EOL;
            $code .= "		I2CSCL1;".PHP_EOL;
            $code .= "		if (SyncClock())".PHP_EOL;
            $code .= "         ;".PHP_EOL;
            $code .= "		//i2cdelay();".PHP_EOL;
            $code .= "		i--;".PHP_EOL;
            $code .= "		if (i2cSDA()) {".PHP_EOL;
            $code .= "			rxbyte |= (1 << i);".PHP_EOL;
            $code .= "		}".PHP_EOL;
            $code .= "		i2cSCL0();".PHP_EOL;
            $code .= "		i2cdelay();".PHP_EOL;
            $code .= "	}".PHP_EOL;
            $code .= "	i2cSDA0();".PHP_EOL;
            $code .= "	i2cSDAOUT();".PHP_EOL;
            $code .= "".PHP_EOL;
            $code .= "	return rxbyte;".PHP_EOL;
            $code .= "}".PHP_EOL;
            $code .= "".PHP_EOL;
            $code .= "//".PHP_EOL;
            $code .= "//! @par Procedure:".PHP_EOL;
            $code .= "//! i2csendack".PHP_EOL;
            $code .= "//!".PHP_EOL;
            $code .= "//! @par Synopsis:".PHP_EOL;
            $code .= "//! This procedure sends an ACK to the I2C device.".PHP_EOL;
            $code .= "//!".PHP_EOL;
            $code .= "//! @par Globals:".PHP_EOL;
            $code .= "//! changes I2CSDA and I2CSCL".PHP_EOL;
            $code .= "//".PHP_EOL;
            $code .= "void".PHP_EOL;
            $code .= "i2csendack(void) ".PHP_EOL;
            $code .= "{".PHP_EOL;
            $code .= "	i2cSDA0();".PHP_EOL;
            $code .= "	i2cdelay();".PHP_EOL;
            $code .= "	i2cSCL1();".PHP_EOL;
            $code .= "	if (SyncClock())".PHP_EOL;
            $code .= "      ;".PHP_EOL;
            $code .= "	//i2cdelay();".PHP_EOL;
            $code .= "	i2cSCL0();".PHP_EOL;
            $code .= "	i2cdelay();".PHP_EOL;
            $code .= "	i2cSDA1();".PHP_EOL;
            $code .= "}".PHP_EOL;
            $code .= "".PHP_EOL;
            $code .= "//".PHP_EOL;
            $code .= "//! @par Procedure:".PHP_EOL;
            $code .= "//! i2csendnack".PHP_EOL;
            $code .= "//!".PHP_EOL;
            $code .= "//! @par Synopsis:".PHP_EOL;
            $code .= "//! This procedure sends a NACK to the I2C device.".PHP_EOL;
            $code .= "//!".PHP_EOL;
            $code .= "//! @par Globals:".PHP_EOL;
            $code .= "//! changes I2CSDA and I2CSCL".PHP_EOL;
            $code .= "//".PHP_EOL;
            $code .= "void".PHP_EOL;
            $code .= "i2csendnack(void) ".PHP_EOL;
            $code .= "{".PHP_EOL;
            $code .= "	i2cSDA1();".PHP_EOL;
            $code .= "	i2cdelay();".PHP_EOL;
            $code .= "	i2cSCL1();".PHP_EOL;
            $code .= "	if (SyncClock())".PHP_EOL;
            $code .= "      ;".PHP_EOL;
            $code .= "	//i2cdelay();".PHP_EOL;
            $code .= "	i2cSCL0();".PHP_EOL;
            $code .= "	i2cdelay();".PHP_EOL;
            $code .= "	i2cSDA1();".PHP_EOL;
            $code .= "}".PHP_EOL;
            $code .= "".PHP_EOL;
            $code .= "int".PHP_EOL;
            $code .= "main()".PHP_EOL;
            $code .= "{".PHP_EOL;
            $code .= "   unsigned char data[8];".PHP_EOL;
            $code .= "".PHP_EOL;
            $code .= "   AD1PCFG = 0xFFFF;".PHP_EOL;
            $code .= "   data[0] = 1;".PHP_EOL;
            $code .= "   data[1] = 2;".PHP_EOL;
            $code .= "".PHP_EOL;
            $code .= "   i2cinit();".PHP_EOL;
            $code .= "   PORTB = 0x3FF;".PHP_EOL;
            $code .= "".PHP_EOL;
            $code .= "   i2cstart();".PHP_EOL;
            $code .= "   i2csendbyte(0x90);".PHP_EOL;
            $code .= "   i2cgetack();".PHP_EOL;
            $code .= "   i2csendbyte(0xAC);".PHP_EOL;
            $code .= "   i2cgetack();".PHP_EOL;
            $code .= "".PHP_EOL;
            $code .= "   i2cstart();".PHP_EOL;
            $code .= "   i2csendbyte(0x02);".PHP_EOL;
            $code .= "   i2cgetack();".PHP_EOL;
            $code .= "   i2cstop();".PHP_EOL;
            $code .= "".PHP_EOL;
            $code .= "".PHP_EOL;
            $code .= "   i2cstart();".PHP_EOL;
            $code .= "   i2csendbyte(0x90);".PHP_EOL;
            $code .= "   i2cgetack();".PHP_EOL;
            $code .= "   i2csendbyte(0xEE);".PHP_EOL;
            $code .= "   i2cgetack();".PHP_EOL;
            $code .= "".PHP_EOL;
            $code .= "".PHP_EOL;
            $code .= "   i2cstop();".PHP_EOL;
            $code .= "".PHP_EOL;
            $code .= "   i2cstart();".PHP_EOL;
            $code .= "   i2csendbyte(0x90);".PHP_EOL;
            $code .= "   i2cgetack();".PHP_EOL;
            $code .= "   i2csendbyte(0xAA);".PHP_EOL;
            $code .= "   i2cgetack();".PHP_EOL;
            $code .= "".PHP_EOL;
            $code .= "   i2cstart();".PHP_EOL;
            $code .= "   i2csendbyte(0x91);".PHP_EOL;
            $code .= "   i2cgetack();".PHP_EOL;
            $code .= "   data[0] = i2cgetbyte();".PHP_EOL;
            $code .= "   i2csendack();".PHP_EOL;
            $code .= "   data[1] = i2cgetbyte();".PHP_EOL;
            $code .= "   i2csendnack();".PHP_EOL;
            $code .= "   i2cstop();".PHP_EOL;
            $code .= PHP_EOL;
            $code .= PHP_EOL;
            $code .= PHP_EOL;
            $code .= "   while (1)".PHP_EOL;
            $code .= "       ;".PHP_EOL;
            $code .= "}".PHP_EOL;
//      }
        
        return $code;
    }
    
    /*
     * Bit Banged I2C header file
     */
    protected function _GenerateHCode($model=NULL, $processor_type='')
    {
        $hFile = '/************************************************'.PHP_EOL;
        $hFile .= ' * bbI2C.h is automatically generated by PicCodeGen'.PHP_EOL;
        $hFile .= ' * Please do not change.'.PHP_EOL;
        $hFile .= ' *'.PHP_EOL;
        $hFile .= ' * To obtain a copy of PicCodeGen go to'.PHP_EOL;
        $hFile .= ' * http://www.kmitechnology.com/.'.PHP_EOL;
        $hFile .= ' *'.PHP_EOL;
        $hFile .= ' ************************************************/'.PHP_EOL;
        $hFile .= PHP_EOL.PHP_EOL.PHP_EOL;
        $hFile .= 'void i2cinit(void);'.PHP_EOL;
        $hFile .= 'void i2cstart(void);'.PHP_EOL;
        $hFile .= 'void i2csendbyte(unsigned char bytetosend);'.PHP_EOL;
        $hFile .= 'unsigned char i2cgetack(void);'.PHP_EOL;
        $hFile .= 'unsigned char i2cgetbyte(void);'.PHP_EOL;
        $hFile .= 'void i2csendack(void);'.PHP_EOL;
        $hFile .= 'unsigned char i2cgetack(void);'.PHP_EOL;
        $hFile .= 'void i2cstop(void);'.PHP_EOL;
        $hFile .= 'void i2csendnack(void);'.PHP_EOL;
        
        return $hFile;
    }
}