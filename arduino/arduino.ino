#include <ESP8266HTTPClient.h>
#include <ESP8266WiFi.h>

#define MOTION_PIN 5
#define BUZZER_PIN 4
#define INT_LED_PIN 13

const char networkPassword[] = "ISKON2738000796";
const char networkName[]     = "ISKONOVAC-487409";

const String serverName = "192.168.5.14";
const unsigned int port = 80;

const unsigned int DEVICE_ID = 5;
const String DEVICE_NAME = "IOT-ALARM-1";

const int buzzer    = BUZZER_PIN;
const int mDetector = MOTION_PIN;
const int LED       = INT_LED_PIN;

int motionValue = LOW;

bool motionDetected = false;
bool buzzOnMotion   = true;

WiFiClient client;

void setup() {
  pinMode(MOTION_PIN, INPUT);
  
  pinMode(BUZZER_PIN, OUTPUT);
  pinMode(INT_LED_PIN, OUTPUT);

  Serial.begin(57600);

  connectToWiFi();
}

void connectToWiFi()
{
  WiFi.begin(networkName, networkPassword);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println();

  Serial.print("Connected, IP address: ");
  Serial.println(WiFi.localIP());

  if (client.connect(serverName, 80)) {
    Serial.println("Connected to the server");
  } else {
    Serial.println("Could not connect to the server");
  }
}

void sendAlert()
{
  if (WiFi.status() == WL_CONNECTED) {
    //Serial.println("Sending alert to server...");

    HTTPClient http;

    http.begin("http://" + serverName + "/php/api/add_alert.php");
    http.addHeader("Content-Type", "application/x-www-form-urlencoded");

    int httpCode = http.POST("alarm_id=" + String(DEVICE_ID) + "&alarm_name=" + DEVICE_NAME);
    String payload = http.getString();
    
    http.end();
    
  } else {
    Serial.println("Server is not available.");
  }
}

bool GetIfUsingSound()
{
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;

    http.begin("http://" + serverName + "/php/api/alarm_ring.php");

    int httpCode = http.GET();
    String payload = http.getString();
    
    http.end();

    return payload == "1";
    
  } else {
    Serial.println("Server is not available.");

    return false;
  }
}

void loop() {
  motionValue = digitalRead(mDetector);

  if (motionValue == HIGH) {
    if (motionDetected == false) {
      motionDetected = true;
      buzzOnMotion = GetIfUsingSound();
      sendAlert();
      
      Serial.println("Motion started!");    
    }
  } else {
    if (motionDetected == true) {
      motionDetected = false;

      Serial.println("Motion ended!");
    }
  }

  if (motionDetected) {
    digitalWrite(LED, HIGH);
    if (buzzOnMotion) {
      tone(buzzer, 500);
    }
  } else {
    digitalWrite(LED, LOW);
    noTone(buzzer);
  }
}
