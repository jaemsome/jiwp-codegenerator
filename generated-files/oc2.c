/*********************************************************
 * oc2.c is automatically generated by pwm.exe.
 * Please do not change.
 *
 * To obtain a copy of pwm.exe go to
 * http://www.kmitechnology.com/.
 *
 *********************************************************/


#include <p24FJ32GA002.h>


void oc2Init(void)
{

    // init PWM
    // set the initial duty cycles (master and slave)
    OC2R = OC2RS = 200;  // init at 50%

} // end oc2Init


/*
main(void)
{
    pinAssign();
    oc2Init();

    // main loop
    while (1)
       ;

} // end main
*/
