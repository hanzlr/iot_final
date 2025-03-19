// Include Library Arduino
#include <Wire.h>
#include <LiquidCrystal_I2C.h>
#include <WiFi.h>
#include <WiFiClient.h>
#include <HTTPClient.h> 
#include <ThingSpeak.h>

int led = 16;
int ldrpin = 34;
float resistance;    
float voltage;       
int lampStatus = 0;  

LiquidCrystal_I2C lcd(0x27, 20, 4);
WiFiClient client;

char ssid[] = "BarraIbnuHasan";    
char pass[] = "barraibnuhasan12";  

// ThingSpeak
unsigned long myChannelNumber = 2731279;
const char* myWriteAPIKey = "5W135KE9DSF5K9GI";

int ledBrightness = 0;  // Brightness value (0-255)

void setup() 
{
  pinMode(led, OUTPUT);
  pinMode(ldrpin, INPUT);
  Serial.begin(9600);

  WiFi.begin(ssid, pass);
  while (WiFi.status() != WL_CONNECTED) 
  {
    delay(500);
    Serial.print(".");
  }
  Serial.println("Connected to WiFi");


  lcd.init();
  lcd.backlight();
  lcd.setCursor(7, 0);
  lcd.print("RAIHAN");
  lcd.setCursor(2, 1);
  lcd.print("RAMANDHA SAPUTRA");
  lcd.setCursor(4, 3);
  lcd.print("41422110039");
  delay(3000);
  lcd.clear();

  ThingSpeak.begin(client);
}

unsigned long lastSendTime = 0;            // Menyimpan waktu terakhir pengiriman
const unsigned long sendInterval = 20000;  // Interval pengiriman data ke database dalam milidetik (20 detik)

unsigned long lastThingSpeakTime = 0;            // Pengiriman ke ThingSpeak
const unsigned long thingSpeakInterval = 10000;  // Interval pengiriman data ke ThingSpeak (10 detik)

void loop() 
{
  unsigned long currentMillis = millis();  // Deklarasikan di awal loop

  // These constants should match the photoresistor's "gamma" and "rl10" attributes
  const float GAMMA = 0.7;
  const float RL10 = 50;

  int analogValue = analogRead(ldrpin);
  Serial.print("Analog Value : ");
  Serial.println(analogValue);

  int light = analogRead(ldrpin);
  float voltage = light / 4096. * 5;
  float resistance = 2000 * voltage / (1 - voltage / 5);
  float lux = pow(RL10 * 1e3 * pow(10, GAMMA) / resistance, (1 / GAMMA));

  Serial.print("Lux : ");
  Serial.println(lux, 2);  

  lcd.setCursor(4, 0);
  lcd.print("STATUS LAMPU");

  String textStatus;

  // Lampu Menyala 50%
  if (analogValue >= 1500 && analogValue < 3092) 
  {
    ledBrightness = 128;
    analogWrite(led, ledBrightness);
    lcd.setCursor(4, 2);
    lcd.print("Cahaya Redup");
    lcd.setCursor(1, 3);
    lcd.print("Lampu menyala: 50%");
    delay(2000);
    lcd.clear();

    lampStatus = 50;
    textStatus = "On";

  } 
  else if (analogValue >= 3092) 
  {
    ledBrightness = 255;
    analogWrite(led, ledBrightness);
    lcd.setCursor(4, 2);
    lcd.print("Cahaya Gelap");
    lcd.setCursor(1, 3);
    lcd.print("Lampu menyala:100%");
    delay(2000);
    lcd.clear();

    lampStatus = 100;
    textStatus = "On";
  } 
  else 
  {
    ledBrightness = 0;
    analogWrite(led, ledBrightness);
    lcd.setCursor(4, 2);
    lcd.print("Cahaya Terang");
    lcd.setCursor(5, 3);
    lcd.print("Lampu Mati");
    lampStatus = 0;
    textStatus = "Off";
    delay(2000);
    lcd.clear();
  }

  // Pengiriman data ke ThingSpeak
  if (currentMillis - lastThingSpeakTime >= thingSpeakInterval) 
  {
    lastThingSpeakTime = currentMillis;
    if (WiFi.status() == WL_CONNECTED) 
    {
      ThingSpeak.setField(1, analogValue);
      ThingSpeak.setField(2, lux);

      int response = ThingSpeak.writeFields(myChannelNumber, myWriteAPIKey);
      if (response == 200) {
        Serial.println("Data sent to ThingSpeak successfully.");
      } 
      else 
      {
        Serial.print("Error sending data to ThingSpeak: ");
        Serial.println(response);
      }
    } 
    else 
    {
      Serial.println("WiFi disconnected.");
    }
  }

  if (currentMillis - lastSendTime >= sendInterval) 
  {
    lastSendTime = currentMillis;

    String url = "https://192.168.0.133/iot_final/api/create.php?analog_value=" + String(analogValue) + "&lux_value=" + String(lux) + "&lamp_percentage=" + String(lampStatus);

    HTTPClient http;
    http.begin(url);
    int httpResponseCode = http.GET();

    if (httpResponseCode > 0) 
    {
      String response = http.getString();
      Serial.println(httpResponseCode);
      Serial.println(response);
    } 
    else 
    {
      Serial.print("Error on sending GET: ");
      Serial.println(httpResponseCode);
    }

    http.end();
  }
}



