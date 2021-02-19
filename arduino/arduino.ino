#define MOTION_PIN 5
#define BUZZER_PIN 4
#define INT_LED_PIN 13

const int buzzer    = BUZZER_PIN;
const int mDetector = MOTION_PIN;
const int LED       = INT_LED_PIN;

int motionValue = LOW;

bool motionDetected = false;
bool buzzOnMotion   = true;

void setup() {
  pinMode(MOTION_PIN, INPUT);
  
  pinMode(BUZZER_PIN, OUTPUT);
  pinMode(INT_LED_PIN, OUTPUT);
  
  Serial.begin(115200);
}

void loop() {
  motionValue = digitalRead(mDetector);

  if (motionValue == HIGH) {
    if (motionDetected == false) {
      motionDetected = true;
      
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
