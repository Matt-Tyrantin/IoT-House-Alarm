#include <ESP8266WiFi.h>
#include <ESP8266mDNS.h>
#include <WiFiUdp.h>

#include <ArduinoOTA.h>
 
const char* ssid = "ISKONOVAC-487409";
const char* password = "ISKON2738000796";
 
int ESP_BUILTIN_LED = 13;
 
void setup() {
  Serial.begin(115200);
  Serial.println("Booting");
  
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);
  
  while (WiFi.waitForConnectResult() != WL_CONNECTED) {
    Serial.println("Connection Failed! Rebooting...");
    delay(5000);
    ESP.restart();
  }
 
  // Postavljamo port za naš Croduino( nije potrebno mijenjati, jer je zadani postavljen na 8266)
  // ArduinoOTA.setPort(8266);
 
  // Ovom naredbom mijenjamo ime našeg Croduina koje vidimo prilikom prebacivanja koda(zadano je  esp8266-[ChipID])
  // ArduinoOTA.setHostname("myesp8266");
 
  //postavljamo lozinku za upload koda( zaštita da nam drugi ne mogu prenijeti kod na pločicu(za postavljanje potrebno otkomentirati funkciju)
  // ArduinoOTA.setPassword((const char *)"123");
 
  ArduinoOTA.onStart([]() {
    Serial.println("Start");
  });

  ArduinoOTA.onEnd([]() {
    Serial.println("\nEnd");
  });

  ArduinoOTA.onProgress([](unsigned int progress, unsigned int total) {
    Serial.printf("Progress: %u%%\r", (progress / (total / 100)));
  });

  ArduinoOTA.onError([](ota_error_t error) {
    Serial.printf("Error[%u]: ", error);
    if (error == OTA_AUTH_ERROR) Serial.println("Auth Failed");
    else if (error == OTA_BEGIN_ERROR) Serial.println("Begin Failed");
    else if (error == OTA_CONNECT_ERROR) Serial.println("Connect Failed");
    else if (error == OTA_RECEIVE_ERROR) Serial.println("Receive Failed");
    else if (error == OTA_END_ERROR) Serial.println("End Failed");
  });
  
  ArduinoOTA.begin();
  
  Serial.println("Ready");
  Serial.print("IP address: ");
  Serial.println(WiFi.localIP());
  
  pinMode(ESP_BUILTIN_LED, OUTPUT);
}
 
void loop() {
  ArduinoOTA.handle(); 
  
  digitalWrite(ESP_BUILTIN_LED, LOW);
  delay(1000);
  digitalWrite(ESP_BUILTIN_LED, HIGH);
  delay(500);
}
