/*********************************************************
 * oc5.c is automatically generated by pwm.exe.
 * Please do not change.
 *
 * To obtain a copy of pwm.exe go to
 * http://www.kmitechnology.com/.
 *
 *********************************************************/


#include <p24FJ32GA002.h>


void oc5Init(void)
{

    // init PWM
    // set the initial duty cycles (master and slave)
    OC5R = OC5RS = 200;  // init at 50%

    // activate the PWM module
    OC5CON = 0x0006;
} // end oc5Init


/*
main(void)
{
    pinAssign();
    timer2Init();
    oc5Init();

    // main loop
    while (1)
       ;

} // end main
*/