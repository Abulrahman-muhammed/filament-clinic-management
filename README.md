# AI-Powered Clinic Management System

A modern clinic management system built with **Laravel 12** and **Filament**, featuring an AI-powered medical assistant, online appointment booking, patient management, and a complete administration dashboard.

---

## Features

### 🤖 AI Medical Assistant
- Integrated Google Gemini API.
- Generates dynamic prompts from clinic services, schedules, and settings.
- Recommends suitable clinic services.
- Answers clinic-related questions.
- Displays available appointment slots.
- Guides patients through the booking process.

### 📅 Appointment Booking
- Dynamic appointment scheduling.
- Slot availability based on doctor's schedule.
- Double-booking prevention.
- Returning patient lookup by phone number.
- Multi-step booking wizard.
- Arabic-first user interface.

### 👨‍⚕️ Patient Management
- Patient profiles.
- Appointment history.
- Automatic patient lookup.
- Patient information updates.

### ⚙️ Admin Dashboard
- Built with Filament.
- Patient management.
- Appointment management.
- Doctor schedule management.
- Services management.
- Dashboard statistics.
- Clinic settings.

### 🔐 Authentication & Authorization
- Role-Based Access Control (RBAC).
- Spatie Laravel Permission.
- Admin / Doctor / Receptionist roles.

---

# Tech Stack

| Category | Technologies |
|----------|--------------|
| Backend | Laravel 12, PHP 8.2 |
| Admin Panel | Filament |
| Database | MySQL |
| AI | Google Gemini API |
| Frontend | Blade, Bootstrap 5, JavaScript |
| Permissions | Spatie Laravel Permission |
| Settings | Spatie Laravel Settings |
| APIs | RESTful APIs |
| Version Control | Git, GitHub |

---

# Architecture

The project follows a clean and maintainable architecture by separating business logic from controllers.

### Highlights

- Service Layer Architecture
- Form Requests Validation
- Custom Exceptions
- Enums
- Repository-like Business Services
- Dynamic AI Prompt Builder
- Thin Controllers
- Reusable Services

Example Services:

- BookingService
- AvailableDateService
- SlotGeneratorService
- GeminiService
- PromptBuilderService
- PatientLookupService

---

# Screenshots

## Home Page

<img width="1482" height="788" alt="image" src="https://github.com/user-attachments/assets/719663b9-c281-4dcc-a3a7-751f418c6a85" />


---

## AI Assistant

<img width="1070" height="703" alt="image" src="https://github.com/user-attachments/assets/705d3d02-8d74-4bcd-b483-0bc23ff1ba6e" />

---

## Booking Wizard

<img width="423" height="647" alt="Screenshot 2026-06-30 200807" src="https://github.com/user-attachments/assets/9d59e146-eab7-4f33-9ca2-bae09b27f13d" />


---

## Admin Dashboard

<img width="1758" height="731" alt="image" src="https://github.com/user-attachments/assets/b3d286ec-15fa-46b3-970d-a253fb54bf7e" />

---

## Installation

Clone the repository

```bash
git clone https://github.com/your-username/clinic-management.git
```

Install dependencies

```bash
composer install

npm install
```

Copy environment file

```bash
cp .env.example .env
```

Generate application key

```bash
php artisan key:generate
```

Configure your database and Gemini API key inside `.env`

```env
DB_CONNECTION=mysql
DB_DATABASE=clinic_management
DB_USERNAME=root
DB_PASSWORD=

GEMINI_API_KEY=YOUR_API_KEY
```

Run migrations

```bash
php artisan migrate --seed
```

Build frontend assets

```bash
npm run build
```

Start the application

```bash
php artisan serve
```

---

# Future Improvements

- Online Payment Integration
- WhatsApp Notifications
- Email Notifications
- Multi-doctor Support
- Multi-clinic Support
- AI-powered Automatic Booking

---

# Author

**Abdulrahman Muhammed**
