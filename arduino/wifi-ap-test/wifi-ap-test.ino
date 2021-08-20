#include <ESP8266WiFi.h>
 
const char AP_Password[] = "12345678";
const char AP_Name[] = "Croduino_AP";
 
WiFiServer server(80);
  
String header = "HTTP/1.1 200 OK\r\nContent-Type: text/html\r\n\r\n";
 
String html_1 = "<!DOCTYPE html><html><head><meta name='viewport' content='width=device-width,"
" initial-scale=1.0'/><meta charset='utf-8'><style>body {font-size:140%;}"
" #main {display: table; margin: auto; padding: 0 10px 0 10px; } h2,{text-align:center; }"
" .button { padding:10px 10px 10px 10px; width:100%; background-color: #4CAF50; font-size: 120%;}</style>"
"<title>AP LED</title></head><body><div id='main'><h2>Kontrola LED diode</h2>";
 
String html_LED = "";
 
//postavljamo tipku za paljenje i gašenje diode
String html_2 = "<form id='F1' action='LEDON'><input class='button' type='submit' value='LED ON' ></form><br>";
String html_3 = "<form id='F2' action='LEDOFF'><input class='button' type='submit' value='LED OFF' ></form><br>";
 
String html_end = "</div></body></html>";
  
// string u koji spremamo zahtjev klijenta( uređaj koji se poveže na naš AP)
String request = "";

int LED_Pin = 13;
  
void setup() 
{
 pinMode(LED_Pin, OUTPUT);

 boolean conn = WiFi.softAP(AP_Name, AP_Password);

 server.begin();
}
  
void loop() 
{
 WiFiClient client = server.available();
 if (!client) { return; }
 //pročitamo prvu liniju zahtijeva klijenta koji je povezan na naš AP
 request = client.readStringUntil('\r');
  
 if ( request.indexOf("LEDON") > 0 ) { digitalWrite(LED_Pin, HIGH); }
 if ( request.indexOf("LEDOFF") > 0 ) { digitalWrite(LED_Pin, LOW); }
 
 if (digitalRead(LED_Pin) == HIGH) { html_LED = "LED dioda je upaljena<br><br>"; }
 if(digitalRead(LED_Pin) == LOW) { html_LED = "LED dioda je ugašena<br><br>"; }
  
 client.flush();

 client.print( header );
 client.print( html_1 ); 
 
 client.print( html_LED );
 client.print( html_2 );
 client.print( html_3 );

 client.print( html_end);
  
 delay(5);
 
}
