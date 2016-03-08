/************************************************
 * uart2.h is automatically generated by theEngineerTutor.com.
 * Please do not change.
 *
 * To obtain a copy of PicCodeGen go to
 * http://www.theEngineerTutor.com/.
 *
 ************************************************/


#define BRATE2  2

void uart2ps(unsigned char *st);
int uart2GetChar(void);
int uart2IsChar(void);
void uart2put(unsigned char c);
void uart2Init(unsigned int config21, unsigned int config22, unsigned int brate2);

int config21 = UART_EN | UART_IDLE_CON | UART_IrDA_ENABLE | UART_MODE_SIMPLEX | UART_UEN_10 | UART_DIS_WAKE | UART_EN_LOOPBACK | UART_DIS_ABAUD | UART_NO_PAR_9BIT | UART_BRGH_FOUR | UART_2STOPBITS;

int config22 = UART_TX_ENABLE;