# Filament Clinic Management System

A production-grade clinic management platform built with Laravel and Filament v5, featuring an AI-powered medical assistant, a multi-step appointment booking flow, and a role-aware admin panel for clinic staff.

<!-- Add a hero screenshot or GIF here, e.g. ![Dashboard](docs/screenshots/dashboard.png) -->

## Overview

This project was built as a graduation thesis (Faculty of Computers and Information, Tanta University) but designed and implemented to production-quality standards rather than as a throwaway academic prototype. It covers the full lifecycle of a single-doctor clinic: patients discover services, chat with an AI assistant for guidance, book appointments through a guided wizard, and clinic staff manage everything from a Filament-powered admin dashboard.

## Key Features

### AI Medical Assistant
- Conversational chatbot powered by the Gemini API (`gemini-2.5-flash`), with structured prompts built dynamically from live clinic data (services, pricing, doctor schedule, availability).
- Help User For Booking And View Avilable Booking Times.

### Appointment Booking
- Multi-step, server-rendered booking wizard (Blade + Bootstrap 5 RTL, minimal vanilla JS — no Livewire/Vue/React dependency).
- Server-side availability logic via a dedicated `AvailableDateService`, driven by doctor schedules rather than client-side date math.
- Interactive service selection with live price totals.
- Returning-patient phone lookup with debounce and auto-fill.
- Egyptian phone number validation and Arabic-first UI.

### Admin Panel (Filament v5)
- Role-aware views and permissions for Admin, Doctor, and Receptionist roles, built on Spatie Laravel Permission.
- Centralized, type-safe clinic configuration via Spatie Laravel Settings.
- Visit, booking, and dashboard statistics tailored to each role.
- Subscription lifecycle management with scheduled commands and database notifications.

## Tech Stack

| Layer | Technology |
|---|---|
| Backend | Laravel <!-- e.g. 11.x --> |
| Admin Panel | Filament v5 |
| Frontend | Blade, Bootstrap 5 (RTL), vanilla JavaScript |
| AI | Google Gemini API (`gemini-2.5-flash`) |
| Settings | Spatie Laravel Settings |
| Permissions | Spatie Laravel Permission |
| Database | MySQL <!-- adjust if different --> |

## Architecture Highlights

- **Server-first booking logic** — date and slot availability are computed entirely in `AvailableDateService` on the backend; the client only renders what the server decides, avoiding duplicated business logic in JavaScript.
- **Dynamic AI context** — `PromptBuilderService` assembles the Gemini system prompt at request time from current services, schedules, and clinic settings, so the assistant's knowledge never drifts from the database.
- **Thin controllers, focused services** — request handling, payload construction, and external API calls are separated into single-responsibility services (`GeminiService`, `PromptBuilderService`) rather than living inline in controllers.

## Screenshots

<!-- Add screenshots here, for example:
### Patient Booking Wizard
<img width="423" height="647" alt="image" src="https://github.com/user-attachments/assets/b2f4e864-c3fb-4b6f-8045-1aa331a87c89" />

### AI Assistant
<img width="1070" height="703" alt="image" src="https://github.com/user-attachments/assets/705d3d02-8d74-4bcd-b483-0bc23ff1ba6e" />

### Admin Dashboard
<img width="1758" height="731" alt="image" src="https://github.com/user-attachments/assets/b3d286ec-15fa-46b3-970d-a253fb54bf7e" />

-->

## Getting Started

### Requirements

- PHP <!-- e.g. ^8.2 -->
- Composer
- Node.js & npm
- MySQL (or your configured database)
- A Google Gemini API key

### Installation

```bash
git clone https://github.com/Abulrahman-muhammed/filament-clinic-management.git
cd filament-clinic-management

composer install
npm install

cp .env.example .env
php artisan key:generate
```

### Configuration

Set your database credentials and Gemini API key in `.env`:

```env
DB_CONNECTION=mysql
DB_DATABASE=clinic_management
DB_USERNAME=root
DB_PASSWORD=

GEMINI_API_KEY=your_gemini_api_key_here
```

### Database & Build

```bash
php artisan migrate --seed
npm run build
```

### Run

```bash
php artisan serve
```

Visit `http://localhost:8000` for the patient-facing app, and `http://localhost:8000/admin` for the Filament admin panel.

## Roadmap

<!-- Optional: list any planned improvements, e.g. -->
- [ ] Online payment integration
- [ ] SMS/WhatsApp appointment reminders
- [ ] Multi-doctor / multi-clinic support


## Author

**Abdulrahman Mohammed**
Computer Science student, Faculty of Computers and Information, Tanta University
<!-- Add LinkedIn / portfolio link here -->
