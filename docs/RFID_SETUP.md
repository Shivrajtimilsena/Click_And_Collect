# RFID Collection Setup

This project supports an Arduino Uno + MFRC522 RFID reader as a collection desk scanner.

## 1. Run the Laravel migration

From the project root:

```powershell
php artisan migrate
```

This adds these fields to the `order` table:

- `rfid_uid`
- `rfid_assigned_at`
- `collected_at`

## 2. Open the test page

Start the Laravel app, then open:

```text
http://127.0.0.1:8000/iot/rfid-scan
```

Use the page to:

1. Assign an RFID UID to an order.
2. Mark that order `READY` in the trader order screen.
3. Scan/test the RFID UID.
4. Confirm the order becomes `COMPLETED`.

## 3. Wire the MFRC522 reader

Use 3.3V, not 5V, for the RFID reader power pin.

| MFRC522 Pin | Arduino Uno Pin |
| --- | --- |
| SDA / SS | 10 |
| SCK | 13 |
| MOSI | 11 |
| MISO | 12 |
| RST | 9 |
| 3.3V | 3.3V |
| GND | GND |

## 4. Upload the Arduino sketch

Open this file in Arduino IDE:

```text
arduino/rfid_click_collect.ino
```

Install the `MFRC522` library:

```text
Sketch > Include Library > Manage Libraries > search "MFRC522"
```

Select your Arduino board and COM port, then upload the sketch.

## 5. Run the USB bridge

Install the Python dependencies:

```powershell
python -m pip install -r scripts\requirements.txt
```

Run the bridge, replacing `COM3` with your Arduino port:

```powershell
python scripts\rfid_bridge.py --port COM3 --url http://127.0.0.1:8000/api/iot/rfid-scan
```

When a card is scanned, the bridge sends:

```json
{
  "rfid_uid": "A1B2C3D4"
}
```

to Laravel. If the matching order is `READY`, Laravel marks it `COMPLETED`.

## 6. Useful API endpoint

The bridge posts scans here:

```text
POST /api/iot/rfid-scan
```

Example response:

```json
{
  "ok": true,
  "message": "Order #ORD-12 collected successfully."
}
```
