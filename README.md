# Task Report Generator

A Laravel 10 application that generates PDF reports containing task completion statistics and visualizations for selected users.

## 📋 Features

- User authentication system
- Task completion statistics generation
- PDF report creation with charts
- Email notification system with PDF attachments
- Queue system for handling report generation
- Interactive user selection interface

## 🚀 Prerequisites

- PHP >= 8.2
- Composer
- Node.js & NPM
- MySQL/PostgreSQL
- Mailpit (for local email testing)

## ⚙️ Installation

1. Clone the repository
   ```bash
   git clone https://github.com/mirkodzudzar/report-generator.git
   cd report-generator
   ```

2. Copy the environment file
   ```bash
   cp .env.example .env
   ```

3. Install PHP dependencies
   ```bash
   composer install
   ```

4. Generate application key
   ```bash
   php artisan key:generate
   ```

5. Configure your `.env` file
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database
   DB_USERNAME=your_username
   DB_PASSWORD=your_password

   QUEUE_CONNECTION=database  # Use 'sync' for testing
   TASK_API=your_api_url     # Required for task retrieval
   ```

6. Install and compile frontend dependencies
   ```bash
   npm install && npm run dev
   ```

7. Set up the database
   ```bash
   php artisan migrate:fresh --seed
   ```

8. Start the development server
   ```bash
   php artisan serve
   ```

## 📧 Email Testing Setup

1. Install Mailpit for local email testing
2. Start Mailpit server (Linux):
   ```bash
   mailpit
   ```
3. Access Mailpit interface at `http://localhost:8025`

## 🔑 Test Credentials

- Email: `johndoe1@example.com`
- Password: `password`

## 📝 Usage Guide

1. Log in using the provided test credentials
2. Navigate to "Create task report" in the main navigation
3. Select a user from the dropdown menu
4. Click "Generate report" button
5. Check Mailpit interface for the email containing the PDF report

## 🛠️ Additional Packages

### Backend (composer.json)
- **laravel/breeze**: Authentication starter kit
- **barryvdh/laravel-dompdf**: Laravel wrapper for Dompdf - HTML to PDF Converter
- **spatie/browsershot**: Converts a webpage to an image or PDF using headless Chrome

### Frontend (package.json)
- **chart.js**: JavaScript charting library
- **puppeteer**: Node library which provides a high-level API to control Chromium or Chrome over the DevTools Protocol.

## 🚨 Important Notes

- Ensure `TASK_API` is properly configured in `.env` as it's crucial for retrieving user tasks
- When using queues (`QUEUE_CONNECTION=database`), make sure to run:
  ```bash
  php artisan queue:work
  ```
- For production deployment, configure your email settings accordingly in `.env`
