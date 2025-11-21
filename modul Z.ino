#include <Arduino.h>
#include <ESP8266WiFi.h>
#include <ESP8266WiFiMulti.h>
#include <ESP8266HTTPClient.h>
#include <WiFiClientSecureBearSSL.h>

const char fingerprint[] = "64 3B FA D4 D4 67 0B F6 95 71 90 88 92 D5 BE 86 89 A2 04 3C";
ESP8266WiFiMulti WiFiMulti;

const char *ssid = "*********";  
const char *password = ""*********";";

const char *host = "";  

///wejścia
const int ledPin2 = D1; 
const int ledPin = D2;
const int buzzerPin = D3;
const int gasPin =D4;   
const int gasAPin = A0;

char ip = 0;

//test
int sensorID;
int value;

int stezenie=0;
int gasVal=0;
unsigned long interval=600000; 
unsigned long interval2=500;
unsigned long interval3 =50;
unsigned long previousMillis=0; 
unsigned long previousMillis2=0; 
unsigned long previousMillis3=0;
int gasAStan = 0;       
int ostatnigasAStan = 0;
int gasStan=0;
int ostatnigasStan=0;
int alarmState = LOW;

void setup() {
  pinMode(gasAPin, INPUT);
  pinMode(ledPin, OUTPUT);
  pinMode(ledPin2, OUTPUT);
  pinMode(buzzerPin, OUTPUT);
  pinMode(gasPin, INPUT);
  
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
  digitalWrite(ledPin, HIGH);
 
   
kalibracja();
}


void loop() {
 unsigned long currentMillis = millis();

//================== meldowanie ==============================================================
 if ((unsigned long)(currentMillis - previousMillis) >= interval) {
previousMillis = millis();
sensorID=3;
value = ip;
post();
 }
//========================== alarm ======================================================
if (gasStan == HIGH) {          
digitalWrite(ledPin2, HIGH);   
  if ((unsigned long)(currentMillis - previousMillis3) >= interval2) {
    previousMillis3 = millis();
    if (alarmState == LOW) {
      alarmState = HIGH;
    } 
    digitalWrite(buzzerPin, alarmState);
  }
 }else{
 alarmState = LOW;	 
  digitalWrite(buzzerPin, alarmState);
 digitalWrite(ledPin2, LOW);    
  }

//===========CZUJNIK CYFROWY====================================================
 gasStan = !digitalRead(gasPin);
  if (gasStan != ostatnigasStan) {
       delay(50);
     sensorID=1;
     value=gasStan;
post();
  }
  ostatnigasStan = gasStan;

 
 // ===============CZUJNIK ANALOGOWY ==========================================================
 if ((unsigned long)(currentMillis - previousMillis2) >= interval3) {
previousMillis2 = millis();
value =analogRead(A0);
if(value>1.2*stezenie && gasAStan==0){
sensorID=2;
post();  
gasAStan=1;
ostatnigasAStan=0;
interval3=5000;
}
else if(value<=1.2*stezenie && gasAStan==1)
 {
   value =0;
 sensorID=2;    
 post();
 gasAStan=0;
ostatnigasAStan=1;
interval3=5000;
  } else
  {
interval3=50;
    } 
  
}
}
// =============================== kalibracja początkowa================================
int kalibracja(){
   for (int i=0; i <= 60; i++){
      digitalWrite(ledPin2, HIGH);
      delay(500);
      digitalWrite(ledPin2, LOW);
      delay(500);
      }  
    stezenie = analogRead(gasAPin);
   return stezenie;
  }
   
//================================================= wysyłanie ==========================
void post(){
std::unique_ptr<BearSSL::WiFiClientSecure>client(new BearSSL::WiFiClientSecure);
HTTPClient https;   
  String Stringsensorvalue, module, sensor, postData;
  sensor=String(sensorID);
  Stringsensorvalue = String(value);  
  module = "Z";
  postData = "value=" + Stringsensorvalue + "&module=" + module + "&sensor=" + sensor;
client->setFingerprint(fingerprint);  
  https.begin(*client, "");  
  https.addHeader("Content-Type", "application/x-www-form-urlencoded");   
  int httpCode = https.POST(postData); 
  https.end(); 
 }
