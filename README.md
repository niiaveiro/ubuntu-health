# Ubuntu Health AI (English Edition)

A PHP + MySQL starter web application for health education and healthcare navigation.

## Included
- Responsive home dashboard
- Rule-based symptom guidance (not a diagnosis)
- Health information chatbot with safe, curated answers
- Appointment request form
- Health education articles
- Contact / feedback form
- MySQL schema and sample articles
- Prepared statements, input validation, and CSRF token on forms

## Requirements
- XAMPP (Apache + PHP + MySQL/MariaDB)
- A modern web browser

## Installation
1. Extract this folder into `C:\xampp\htdocs\ubuntu-health-ai`.
2. Start **Apache** and **MySQL** in XAMPP.
3. Open `http://localhost/phpmyadmin`.
4. Import `database/ubuntu_health_ai.sql`.
5. If your MySQL username/password differs from the defaults, edit `config/database.php`.
6. Open `http://localhost/ubuntu-health-ai/`.

## Important safety note
This prototype provides general health education and basic navigation only. It does not diagnose diseases, prescribe medicine, or replace a qualified health professional. Emergency warnings are general and should not delay seeking urgent help. Do not enter highly sensitive personal information.

## Before real-world deployment
- Add secure authentication and role-based access for clinicians/admins.
- Use HTTPS, secure session settings, rate limiting, audit logging, backups, and security testing.
- Obtain appropriate consent and review Rwanda's applicable health-data and privacy requirements.
- Have qualified clinicians review all medical content and triage rules.
- Connect only to verified health facilities and appointment workflows.
- Do not advertise the rule-based chatbot as a clinically validated AI system.
