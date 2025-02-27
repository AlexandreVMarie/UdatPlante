#include <ESP8266WiFi.h>
#include <ESP8266WebServer.h>

#define SENSOR_PIN A0  // Moisture sensor on A0

// WiFi Credentials
const char* ssid = "HUAWEI-100SR0";
const char* password = "QuadCork1620";

// Create Web Server
ESP8266WebServer server(80);

// ✅ Declare functions BEFORE setup()
void handleMoisture();
void handleWebPage();

void setup() {
    Serial.begin(115200);
    WiFi.begin(ssid, password);

    Serial.print("Connecting to WiFi");
    while (WiFi.status() != WL_CONNECTED) {
        delay(500);
        Serial.print(".");
    }
    Serial.println("\nWiFi connected!");
    Serial.print("IP Address: ");
    Serial.println(WiFi.localIP());

    pinMode(SENSOR_PIN, INPUT);

    // ✅ Define web routes AFTER function declarations
    server.on("/moisture", handleMoisture);
    server.on("/", handleWebPage);

    server.begin();
    Serial.println("HTTP server started");
}

void loop() {
    server.handleClient();
}

// Function to get moisture value
void handleMoisture() {
    int rawValue = analogRead(SENSOR_PIN);
    int moisturePercent = map(rawValue, 758, 382, 0, 100);
    moisturePercent = constrain(moisturePercent, 0, 100);

    Serial.print("Moisture Value Sent: ");
    Serial.println(moisturePercent);

    server.send(200, "text/plain", String(moisturePercent));
}

// Function to serve the web page with auto-refreshing moisture data
void handleWebPage() {
    String html = "<html><head>";
    html += "<script>";
    html += "function updateMoisture() {";
    html += "  fetch('/moisture')";
    html += "    .then(response => response.text())";
    html += "    .then(data => {";
    html += "      document.getElementById('moisture').innerText = data + '%';";
    html += "    })";
    html += "    .catch(error => console.error('Error:', error));";
    html += "}";
    html += "setInterval(updateMoisture, 2000);";  // Refresh every 2 sec
    html += "window.onload = updateMoisture;";    // Fetch immediately on page load
    html += "</script></head><body>";
    html += "<h1>Moisture Level:</h1>";
    html += "<h2 id='moisture'>Loading...</h2>";
    html += "</body></html>";

    server.send(200, "text/html", html);
}
