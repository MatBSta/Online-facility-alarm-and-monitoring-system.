# Online-facility-alarm-and-monitoring-system.
A web-based facility alarm and monitoring system designed to track, signal, and manage alerts for a building or protected area.

A complete, distributed alarm and monitoring system built on Wemos D1 mini (ESP8266) microcontrollers communicating with a web application in real time.
The system is designed to secure a small facility (holiday cottage) by providing live sensor monitoring, remote status access, and a full event history.

The project includes both the hardware layer and the server / web application, covering networking, security, responsive UI, and data archiving.

Features
Real-time monitoring using multiple sensors

PIR motion sensor

Gas sensor (LPG, propane, hydrogen – MQ-2)

Door/window magnetic contact sensor (reed switch)

Secure bidirectional communication

Encrypted HTTPS connection using BearSSL with certificate fingerprint verification

Microcontrollers send telemetry, receive commands, and maintain persistent secure communication with the server

Local alarm signaling

LED and buzzer activation on threat detection

System-level detection of power loss or Internet outage for each module

Full event archiving

All alarms, warnings, sensor changes, and status transitions stored in a relational MySQL database

Web application

Live dashboard showing current sensor states

Data visualization (JavaScript charts)

Historical event log with filtering

Administrator panel (user management, logs, security configuration)

Fully responsive RWD interface

Technologies Used
Microcontrollers / Firmware

C++, Arduino IDE

ESP8266WiFi

WifiClientSecureBearSSL

Custom firmware for each sensor node

Backend

PHP 7.x

REST-like endpoints for real-time communication

Authentication & authorization layer

Frontend

HTML5, CSS3, JavaScript

Responsive Web Design (RWD)

Database

MySQL

Relational model for logging events, device states, user accounts, authentication history

Security

The system implements multiple security layers, including:

SSL/TLS with certificate pinning (fingerprint)

CAA, DNSSEC, DDoS protection

Protection against:

brute force attacks

SQL Injection

authorization bypass

Secure cookies, HTTP security headers

Password hashing and hardened authentication mechanisms
