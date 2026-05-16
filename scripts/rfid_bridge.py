"""Read RFID UIDs from Arduino serial and send them to the Laravel API.

Install dependency:
    py -m pip install pyserial requests

Run example on Windows:
    py scripts\rfid_bridge.py --port COM3 --url http://127.0.0.1:8000/api/iot/rfid-scan
"""

import argparse
import time

import requests
import serial


def main() -> None:
    parser = argparse.ArgumentParser(description="Click&Collect Arduino RFID bridge")
    parser.add_argument("--port", required=True, help="Arduino serial port, for example COM3")
    parser.add_argument("--baud", type=int, default=9600, help="Serial baud rate")
    parser.add_argument(
        "--url",
        default="http://127.0.0.1:8000/api/iot/rfid-scan",
        help="Laravel RFID API URL",
    )
    args = parser.parse_args()

    print(f"Listening on {args.port} at {args.baud} baud")
    print(f"Sending scans to {args.url}")

    with serial.Serial(args.port, args.baud, timeout=1) as arduino:
        time.sleep(2)

        while True:
            raw_line = arduino.readline().decode("utf-8", errors="ignore").strip()
            uid = "".join(ch for ch in raw_line.upper() if ch in "0123456789ABCDEF")

            if len(uid) < 4:
                continue

            print(f"Scanned UID: {uid}")

            try:
                response = requests.post(
                    args.url,
                    json={"rfid_uid": uid},
                    headers={"Accept": "application/json"},
                    timeout=10,
                )
                data = response.json()
                print(f"Laravel response [{response.status_code}]: {data.get('message', data)}")
            except requests.RequestException as exc:
                print(f"Could not reach Laravel: {exc}")


if __name__ == "__main__":
    main()
