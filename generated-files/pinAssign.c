
/************************************************
 * To obtain a copy of PicCodeGen go to
 * http://www.kmitechnology.com/.
 *
 ************************************************/


#include <p24FJ32GA002.h>
void
pinAssign(void)
{
     //***********************************
     // Unlock Registers
     //***********************************
     asm volatile ("MOV #OSCCON, w1\n"
         "MOV #0x46,w2\n"
         "MOV #0x57,w3\n"
         "MOV.b w2,[w1]\n"
         "MOV.b w3,[w1]\n"
         "BCLR OSCCON,#6"
     );

     RPINR0bits.INT1R = 0; // assign INT1 pin RP 0
     RPOR0bits.RP0R = 0; // assign C1OUT to pin RP0

     //***********************************
     // Lock Registers
     //***********************************
     asm volatile ("MOV #OSCCON, w1\n"
         "MOV #0x46,w2\n"
         "MOV #0x57,w3\n"
         "MOV.b w2,[w1]\n"
         "MOV.b w3,[w1]\n"
         "BCLR OSCCON,#6"
     );
}// end pinAssign
