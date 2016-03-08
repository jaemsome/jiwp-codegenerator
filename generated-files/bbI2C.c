
/************************************************
 * To obtain a copy of PicCodeGen go to
 * http://www.kmitechnology.com/.
 *
 ************************************************/

#include "bbI2C.h"


#include <p24FJ32GA002.h>


#define I2CSDA1 PORTJ = PORTJ | 0x8000
#define I2CSDA0 PORTJ = PORTJ & ~0x8000
#define I2CSDA (PORTJ & 0x8000)

#define I2CSDAIN (TRISJ = TRISJ | 0x8000)
#define I2CSDAOUT (TRISJ = TRISJ & ~0x8000)

#define I2CSCL1 PORTA = PORTA | 0x0001
#define I2CSCL0 PORTA = PORTA & ~0x0001
#define I2CSCL (PORTA & 0x0001)

void
i2cSDA1(void)
{
    LATJ = LATJ | 0x8000;
}

void
i2cSDA0(void)
{
    PORTJ = PORTJ & ~0x8000;
}

unsigned int
i2cSDA(void)
{
    return PORTJ & 0x8000;
}

void
i2cSDAIN(void)
{
    TRISJ = TRISJ | 0x8000;
}

void
i2cSDAOUT(void)
{
    TRISJ = TRISJ & ~0x8000;
}

void
i2cSCL1(void)
{
    LATA = LATA | 0x0001;
}

void
i2cSCL0(void)
{
    PORTA = PORTA & ~0x0001;
}

unsigned int
i2cSCL(void)
{
    return PORTA & 0x0001;
}

unsigned char NoACK;

//
//! @par Function:
//! SyncClock
//!
//! @par Synopsis:
//! This routine verifies that the scl is free.  If
//! this I/O bit remains low then I can't toggle it to talk to
//! the I2C device attached to the bus.
//!
//! @return 1 when the clock is working and 0 if the clock isn't
//! working.
//!
//! @par Globals:
//! None
//
unsigned char
SyncClock(void) 
{
   int clockbad;
   // Configure Timer 0 as a 16-bit timer 

   clockbad = 0;
   // Try to synchronise the clock
   while ((I2CSCL == 0) && (clockbad < 16384)) 
      clockbad++; 

   if (clockbad == 16384)
   {
         return 0;  // Error - Timeout condition failed
   }
   return 1;  // OK - Clock synchronised
}

//
//! @par Procedure:
//! i2cinit
//!
//! @par Synopsis:
//! This routine grabs the necessary two bits for
//! I2C communication.  It then puts them into the proper state.
//!
//! @par Globals:
//! Changes TRISJ, I2CSDA1, and I2CSCL1.
//
void
i2cinit(void)
{
	i2cSDA1();
	i2cSCL1();
	TRISJ &= ~0x8000;
	TRISA &= ~0x0001;
//#define I2CSDAOUT (TRISJ &= ~0x8000)
//   TRISJ &= ~0x03;
}

//
//! @par Procedure:
//! i2crelease
//!
//! @par Synopsis:
//! This routine returns the two I2C bits back to the
//! system.  It changes them to input.
//! @par Globals:
//! Changes TRISJ
//
void
i2crelease(void)
{
//   TRISJ |= 0x03;
}

//
 //! @par Procedure:
//! i2cdelay
//!
//! @par Synopsis:
//! This routine is a simple delay routine.
//!
//! @par globals:
//! none
//
void
i2cdelay(void) 
{
	long x;

	x = 0;
	x++;
	x++;
	x++;
	x++;
	x++;
	x++;
}


//
//! @par Procedure:
//! i2cstart
//!
//! @par Synopsis:
//! This procedure generates a I2C start condition on the
//! two I2C lines.
//!
//! @par Globals:
//! changes I2CSDA and I2CSCL
//
void
i2cstart(void) 
{
	i2cSDA1();
	i2cSCL1();
	if (SyncClock())
      ;

	i2cdelay();
	i2cSDA0();
	i2cdelay();
	i2cSCL0();
	i2cdelay();
	i2cSDA1();
}

//
//! @par Procedure:
//! i2cstop
//! 
//! @par Synopsis:
//! This routine generates a stop conditions on the I2C pins.
//!
//! @par Globals:
//! Changes I2CSDA AND I2CSCL
//
void
i2cstop(void) 
{
	i2cSDA0();
	i2cdelay();
	i2cSCL1();
	i2cdelay();
	i2cSDA1();
}

//
//! @par Procedure:
//! i2cres
//!
//! @par Synopsis:
//! This procedure resets all I2C devices on the I2C bus.
//!
//! @par Globals:
//! Changes I2CSCL and I2CSDA
//
void
i2cres(void) 
{
	unsigned char i;

	for (i=0; i<9; i++) {
		i2cSCL0();
		i2cSDA0();
		i2cstop();
	}
}

//
//! @par Procedure:
//! i2csendbyte
//
//! @par Synopsis:
//! This procedure sends a byte to an addressed I2C device.
//! This routine expects a start to have been generated along with the I2C
//! device has been addressed.  It doesn't do a i2cstop.  It leaves the
//! bus in a state to send another byte.
//
//! @param bytetosend byte to send
//
//! @par Globals:
//! Changes I2CSDA and I2CSCL
//
void
i2csendbyte(unsigned char bytetosend) 
{
	unsigned char i;

	i = 8;


	while (i > 0) {
		i--;
		if (bytetosend & (1 << i)) {
			i2cSDA1();
		} else {
			i2cSDA0();
		}
		i2cdelay();
		i2cSCL1();
		if (SyncClock())
         ;
		i2cdelay();
		i2cSCL0();
		//i2cdelay();
	}
	//i2cSDA1();
}

//
//! @par Function:
//! i2cgetack
//!
//! @par Synopsis:
//! This function waits for an ack from the I2C device.  If the
//! device doesn't ack a NoACK value is returned.
//!
//! @par Globals:
//! Changes NoAck, I2CSDAIN, I2CSCL, and I2CSDA
//
unsigned char
i2cgetack(void) 
{
	NoACK = 0;
	i2cSDAIN();
	//i2cSDA1();
   i2cdelay();
	i2cSCL1();
	if (SyncClock())
      ;
	i2cdelay();
	//while (i2csda != 0) ;
	NoACK = i2cSDA();
	i2cSCL0();
	i2cSDAOUT();
   i2cdelay();
   i2cdelay();
	return NoACK;
}

//
//! @par Function:
//! i2cgetbyte
//!
//! @par Synopsis:
//! This function reads a byte from the I2C device.  It returns
//! this byte to the calling routine.
//!
//! @par Globals:
//! Changes I2CSCL, I2CSDA, and I2CSDAIN
//
unsigned char
i2cgetbyte(void) 
{
	unsigned char i;
	unsigned char rxbyte;

	rxbyte = 0;
	i = 8;

	i2cSDA1();
	i2cSDAIN();
	while (i > 0) {
		I2CSCL1;
		if (SyncClock())
         ;
		//i2cdelay();
		i--;
		if (i2cSDA()) {
			rxbyte |= (1 << i);
		}
		i2cSCL0();
		i2cdelay();
	}
	i2cSDA0();
	i2cSDAOUT();

	return rxbyte;
}

//
//! @par Procedure:
//! i2csendack
//!
//! @par Synopsis:
//! This procedure sends an ACK to the I2C device.
//!
//! @par Globals:
//! changes I2CSDA and I2CSCL
//
void
i2csendack(void) 
{
	i2cSDA0();
	i2cdelay();
	i2cSCL1();
	if (SyncClock())
      ;
	//i2cdelay();
	i2cSCL0();
	i2cdelay();
	i2cSDA1();
}

//
//! @par Procedure:
//! i2csendnack
//!
//! @par Synopsis:
//! This procedure sends a NACK to the I2C device.
//!
//! @par Globals:
//! changes I2CSDA and I2CSCL
//
void
i2csendnack(void) 
{
	i2cSDA1();
	i2cdelay();
	i2cSCL1();
	if (SyncClock())
      ;
	//i2cdelay();
	i2cSCL0();
	i2cdelay();
	i2cSDA1();
}

int
main()
{
   unsigned char data[8];

   AD1PCFG = 0xFFFF;
   data[0] = 1;
   data[1] = 2;

   i2cinit();
   PORTB = 0x3FF;

   i2cstart();
   i2csendbyte(0x90);
   i2cgetack();
   i2csendbyte(0xAC);
   i2cgetack();

   i2cstart();
   i2csendbyte(0x02);
   i2cgetack();
   i2cstop();


   i2cstart();
   i2csendbyte(0x90);
   i2cgetack();
   i2csendbyte(0xEE);
   i2cgetack();


   i2cstop();

   i2cstart();
   i2csendbyte(0x90);
   i2cgetack();
   i2csendbyte(0xAA);
   i2cgetack();

   i2cstart();
   i2csendbyte(0x91);
   i2cgetack();
   data[0] = i2cgetbyte();
   i2csendack();
   data[1] = i2cgetbyte();
   i2csendnack();
   i2cstop();



   while (1)
       ;
}
