#include <Arduino.h>
#include <ESP8266WiFi.h>
#include <ESP8266WiFiMulti.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClientSecureBearSSL.h>

const char fingerprint[] = "64 3B FA D4 D4 67 0B F6 95 71 90 88 92 D5 BE 86 89 A2 04 3C";
ESP8266WiFiMulti WiFiMulti;


const char *ssid = "*******";  
const char *password = "*********";

unsigned long interval=600000; 
unsigned long previousMillis=0; 


const char *host = "";   

///wejścia
const int ledPin = D2; 
const int pirPin =D6;   
const int kontraktonPin =D7;
const int przycisk= D5;

//test
int sensorID, value;


int przycisklicznik = 1;  
int przysciskstan = 0;         
int ostatniprzysciskstan = 0;     


int kontraktonstan = 0;   
int ostatnikontraktonstan = 0;
int pirstan=0;
int ostatnipirstan=0;
char ip = 0;

void setup() {
  pinMode(przycisk, INPUT);
  pinMode(kontraktonPin, INPUT_PULLUP);
  pinMode(ledPin, OUTPUT);
  pinMode(pirPin, INPUT);
  delay(1000);
  WiFi.mode(WIFI_OFF);        
  delay(1000);
  WiFi.mode(WIFI_STA);
  
  WiFiMulti.addAP("", "");
  while ((WiFiMulti.run()!= WL_CONNECTED)) {
    digitalWrite(ledPin, HIGH);
    delay(200);
    digitalWrite(ledPin, LOW);
    delay(200);
  }
   
}

void loop() {
 unsigned long currentMillis = millis(); 
 if ((unsigned long)(currentMillis - previousMillis) >= interval) {   
  previousMillis = millis();
sensorID=3;
value=ip;
post();
 }


  

 przysciskstan = digitalRead(przycisk);
  if (przysciskstan != ostatniprzysciskstan) {
    if (przysciskstan == HIGH) {
      przycisklicznik++;
    } else {
    }
    delay(50);
  }
  ostatniprzysciskstan = przysciskstan;
//=====================ALARM===================================================================
  if (przycisklicznik % 2 == 0) {
    digitalWrite(ledPin, HIGH);
  } else {
    digitalWrite(ledPin, LOW);
  }

//=====================KONTRAKTON===================================================================  
int kontraktonstan = digitalRead(kontraktonPin);
  if (kontraktonstan != ostatnikontraktonstan) {
    if (kontraktonstan == HIGH) {
      if (przycisklicznik% 2!=0){
       przycisklicznik++;
       }
    } else {
    }
    delay(50);
     sensorID=4;
     value=kontraktonstan;
    post();
  }
  ostatnikontraktonstan = kontraktonstan;
//=====================PIR===================================================================  
 pirstan = digitalRead(pirPin);
  if (pirstan != ostatnipirstan) {
    if (pirstan == HIGH) {
    } else {
    }
    delay(50);
     sensorID=5;
     value=pirstan;
post();
  }
  ostatnipirstan = pirstan;

}
void post(){
std::unique_ptr<BearSSL::WiFiClientSecure>client(new BearSSL::WiFiClientSecure);
HTTPClient https;   
 String Stringsensorvalue, module, sensor, postData;
   sensor=String(sensorID);
  Stringsensorvalue = String(value);  
  module = "A";

  postData = "value=" + Stringsensorvalue + "&module=" + module + "&sensor=" + sensor;
  client->setFingerprint(fingerprint);  
  https.begin(*client, "");  
  https.addHeader("Content-Type", "application/x-www-form-urlencoded");   
  int httpCode = https.POST(postData);  
  https.end();  
  }

