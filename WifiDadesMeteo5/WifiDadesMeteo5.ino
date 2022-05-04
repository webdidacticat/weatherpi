/********************************
 * Wifi+DadesMeteo+NivellBatery *
 ********************************/

#include <ESP8266WiFi.h>
#include <Wire.h>
#include <BMx280I2C.h>
#include "ClosedCube_HDC1080.h"

#ifndef STASSID
#define STASSID "WeatherPi"
#define STAPSK  "25ZbkQpO9Lv*O8u&Vl"
#endif

#define I2C_ADDRESS 0x76
ClosedCube_HDC1080 hdc1080;
BMx280I2C bmx280(I2C_ADDRESS);

//#define durationSleep  60   // secondes
#define durationSleepWifi  10   // secondes
#define NB_TRYWIFI     1000   // nbr d'essai connexion WiFi, number of try to connect WiFi

#define ANALOGPILA 0

const char* ssid     = STASSID;
const char* password = STAPSK;

const char* host = "192.168.5.1";
const uint16_t port = 80;

int pinSonda = A0;

float temp, temp_HDC, hum_HDC, temp_BMP, pres_BMP, volt;
int durationSleep; // Es mesura segons

void setup() {

  Serial.begin(9600);
  pinMode(pinSonda, INPUT);
  
  while (!Serial);
  
  init_WIFI();
  
  Wire.begin();
    
}

void loop() {

  //HDC1080
  init_HDC1080();
  
  //BMP280
  init_BMP280();

  //BateryLevel
  batery();
  
  /*Serial.println("Valores enviados a la web");
  Serial.print("Temp = ");
  temp=(temp_HDC+temp_BMP)/2;
  Serial.println(temp);
  Serial.print("Hum = ");
  Serial.println(hum_HDC);
  Serial.print("Pres = ");
  Serial.println(pres_BMP);*/

  WIFIsend();
  
  ESP.deepSleep(durationSleep * 1000000);
  
}

void init_WIFI()
{
  WiFi.mode(WIFI_STA);
  WiFi.begin(ssid, password);
  // Start Fixed IP
  IPAddress ip(192,168,5,2);   
  IPAddress gateway(192,168,5,1);   
  IPAddress subnet(255,255,255,0);   
  WiFi.config(ip, gateway, subnet);
  Serial.println("Start Connection");
  int _try = 0;
  while (WiFi.status() != WL_CONNECTED) {
    Serial.print("..");
    delay(500);
    _try++;
    if ( _try >= NB_TRYWIFI ) {
        Serial.println("Impossible to connect WiFi network, go to deep sleep");
        ESP.deepSleep(durationSleepWifi * 1000000);
    }
  }
  Serial.println("Connected to the WiFi network");
  //Serial.print ( "IP address: " );
  //Serial.println ( WiFi.localIP() );
  //Serial.print("MAC: ");
  //Serial.println(WiFi.macAddress());
}

void WIFIsend()
{
  WiFiClient client;
  if (!client.connect(host, port)) {
    Serial.println("connection failed");
    delay(5000);
    return;
  }
  // This will send the request to the server
  client.print("GET /index.php?tb=");
  client.print(temp_BMP);
  client.print("&pb=");
  client.print(pres_BMP);
  client.print("&th=");
  client.print(temp_HDC);
  client.print("&hh=");
  client.print(hum_HDC);
  client.print("&bat=");
  client.print(volt);
  client.print("&ii=");
  client.print("A1010");//ID device
  client.println(" HTTP/1.1");
  client.println("Host: 192.168.5.1");
  client.println("Connection: close");
  client.println();
  Serial.print("GET /index.php?tb=");
  Serial.print(temp_BMP);
  Serial.println(" HTTP/1.1");
  Serial.println("Host: 192.168.5.1");
  Serial.println("Connection: close");
  Serial.println();
  //read back one line from server
  Serial.println("receiving from remote server");
  while(client.connected() || client.available())
  {
    String line = client.readStringUntil('\r');
    Serial.print(line);
  }
  Serial.println();

  Serial.println("closing connection");
  client.stop();

  client.stop();
}

void batery()
{
  // Leemos valor de la entrada analógica
  float lectura = analogRead(pinSonda);
  lectura = map(lectura, 0, 1023, 0, 420); //(valor,fromLow,fromHigh,toLow,toHigh)
  volt = lectura/100; //Lectura en voltios
  
  Serial.println(volt);

  if ( volt >= 4.0 ){
    durationSleep = 120; //2min --- 30 lectures per hora
  } else if ( volt <= 4.0 && volt >= 3.4 ) {
    durationSleep = 240; //4min --- 15 lecturas per hora
  } else if ( volt <= 3.4 && volt >= 3.0) {
    durationSleep = 360; //6min --- 10 lectures per hora
  } else {
    durationSleep = 480; //8min --- 7 lectures per hora
  }
  
}

void init_HDC1080()
{
  int n;
  hdc1080.begin(0x40);
  Serial.println("**** HDC1080 ****");
  for(n=0;n<10;n++)
  {
    temp_HDC=hdc1080.readTemperature();
    hum_HDC=hdc1080.readHumidity();
    delay(300);
  }
  Serial.print("T=");
  Serial.print(temp_HDC);
  Serial.print("C, RH=");
  Serial.print(hum_HDC);
  Serial.println("%");
  Serial.println(n);
}

void init_BMP280()
{
  int n=0;

  if (!bmx280.begin())
  {
    Serial.println("begin() failed. check your BMx280 Interface and I2C Address.");
    while (1);
  }
  bmx280.resetToDefaults();
  bmx280.writeOversamplingPressure(BMx280MI::OSRS_P_x16);
  bmx280.writeOversamplingTemperature(BMx280MI::OSRS_T_x16);
  delay(1000);
  Serial.println("**** BMP280 ****");
  do{ 
    //start a measurement
    if (!bmx280.measure())
    {
      Serial.println("could not start measurement, is a measurement already running?");
      return;
    }
    do
    {
      delay(100);
    } while (!bmx280.hasValue());
    pres_BMP=bmx280.getPressure()/100;
    //Serial.print("Pressure (64 bit): "); Serial.println(bmx280.getPressure64());
    temp_BMP=bmx280.getTemperature();
    n++;
    delay(1000);
  }while(!((pres_BMP<900)||(pres_BMP>1100))&&(n<2));
  Serial.print("Pressure: "); Serial.println(pres_BMP);
  Serial.print("Temperature: "); Serial.println(temp_BMP);
  Serial.println(n);
}
