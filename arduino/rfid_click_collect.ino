/*
  Click&Collect RFID reader for Arduino Uno + MFRC522.

  Wiring:
  MFRC522 SDA/SS  -> Arduino pin 10
  MFRC522 SCK     -> Arduino pin 13
  MFRC522 MOSI    -> Arduino pin 11
  MFRC522 MISO    -> Arduino pin 12
  MFRC522 RST     -> Arduino pin 9
  MFRC522 3.3V    -> Arduino 3.3V
  MFRC522 GND     -> Arduino GND

  Install this Arduino IDE library:
  Sketch > Include Library > Manage Libraries > search "MFRC522" by GithubCommunity
*/

#include <SPI.h>
#include <MFRC522.h>

#define SS_PIN 10
#define RST_PIN 9

MFRC522 rfid(SS_PIN, RST_PIN);

String lastUid = "";
unsigned long lastScanTime = 0;
const unsigned long duplicateDelayMs = 3000;

void setup() {
  Serial.begin(9600);
  SPI.begin();
  rfid.PCD_Init();

  Serial.println("Click&Collect RFID reader ready");
}

void loop() {
  if (!rfid.PICC_IsNewCardPresent()) {
    return;
  }

  if (!rfid.PICC_ReadCardSerial()) {
    return;
  }

  String uid = "";
  for (byte i = 0; i < rfid.uid.size; i++) {
    if (rfid.uid.uidByte[i] < 0x10) {
      uid += "0";
    }
    uid += String(rfid.uid.uidByte[i], HEX);
  }

  uid.toUpperCase();

  unsigned long now = millis();
  if (uid != lastUid || now - lastScanTime > duplicateDelayMs) {
    Serial.println(uid);
    lastUid = uid;
    lastScanTime = now;
  }

  rfid.PICC_HaltA();
  rfid.PCD_StopCrypto1();
}
