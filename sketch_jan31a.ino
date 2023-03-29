// Benjamín Morozov III.A

#include <WiFi.h>
#include <dht11.h>
#include <BlynkSimpleEsp32.h>

/* Fill-in information from Blynk Device Info here */
#define BLYNK_TEMPLATE_ID "***"
#define BLYNK_TEMPLATE_NAME "***"
#define BLYNK_AUTH_TOKEN "***"

/* Comment this out to disable prints and save space */
#define BLYNK_PRINT Serial

#define DHTPIN 27
dht11 DHT11_sensor;

// Your WiFi credentials.
// Set password to "" for open networks.
char ssid[] = "***";
char pass[] = "***";

void setup()
{
  // Debug console
  Serial.begin(9600);
  pinMode(27,OUTPUT);
  Blynk.begin(BLYNK_AUTH_TOKEN, ssid, pass);
  Blynk.virtualWrite(0, WiFi.localIP().toString());
  Blynk.virtualWrite(4, WiFi.status());
}

void loop()
{
  Blynk.run();
  Blynk.virtualWrite(5, 1);
  Blynk.virtualWrite(5, 0);
  probeSerial();
}

void probeSerial() {
  delay(250);
  DHT11_sensor.read(DHTPIN);

  Serial.print("Vlhkost vzduchu (%): ");
  Serial.println((float)DHT11_sensor.humidity, 2);
  Blynk.virtualWrite(2, (float)DHT11_sensor.humidity);

  Serial.print("Teplota C: ");
  Serial.println((float)DHT11_sensor.temperature);
  Blynk.virtualWrite(1, (float)DHT11_sensor.temperature);

  Serial.print("Teplota K: ");
  Serial.println(DHT11_sensor.kelvin(), 2);

  Serial.print("Teplota F: ");
  Serial.println(DHT11_sensor.fahrenheit(), 2);

  Serial.print("Rosny bod: ");
  Serial.println(DHT11_sensor.dewPoint(), 2);
  Blynk.virtualWrite(3, (float)DHT11_sensor.dewPoint());

  Serial.println("------------------");
}
