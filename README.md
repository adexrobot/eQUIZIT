# eQUIZMona

**Electronic Quiz Management and AI-Assisted Assessment Platform**

A web-based quiz management system for colleges and universities. Faculty create and manage quizzes; students take exams using a unique quiz code without registration.

## Features

- Faculty registration and secure login (Admin & Faculty roles)
- Quiz creation with auto-generated quiz codes
- DOCX/PDF upload with automatic question extraction
- Question types: Multiple Choice, True/False, Identification, Essay
- Student exam interface with timer, auto-save, and navigation
- Automatic grading for objective questions
- AI-powered essay evaluation with feedback and writing pattern indicators
- Analytics dashboard with Chart.js
- Export results to CSV and PDF
- Activity logs, dark mode, mobile responsive design
- REST API (Sanctum authentication)

## Technology Stack

| Layer | Technology |
|-------|------------|
| Frontend | HTML5, CSS3, Bootstrap 5, JavaScript, AJAX |
| Backend | Laravel 9 (PHP 8.0+), REST API |
| Database | MySQL |
| AI | OpenAI API (configurable) |
| Charts | Chart.js |
| PDF | DomPDF |

> **Note:** Laravel 11 requires PHP 8.2+. This project uses Laravel 9 for compatibility with PHP 8.0 (XAMPP). Upgrade PHP to 8.2+ for Laravel 11.

## Requirements

- PHP 8.0+ with extensions: `openssl`, `pdo`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `zip`
- Composer
- MySQL 5.7+ / MariaDB
- Node.js (optional, for asset building)
- OpenAI API key (optional, for full AI essay grading)

## Installation

### 1. Clone and install dependencies

```bash
cd eQUIZMona
composer install
```

### 2. Environment configuration

```bash
copy .env.example .env
php artisan key:generate
```

Edit `.env`:

```env
APP_NAME=eQUIZMona
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=equizmona
DB_USERNAME=root
DB_PASSWORD=

OPENAI_API_KEY=your-openai-api-key
OPENAI_MODEL=gpt-4o-mini
```

### 3. Create database

```sql
CREATE DATABASE equizmona CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 4. Run migrations and seeders

```bash
php artisan migrate --seed
php artisan storage:link
```

### 5. Start the server

```bash
php artisan serve
```

Visit: http://localhost:8000

## Default Accounts

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@equizmona.edu | admin12345 |
| Faculty | faculty@equizmona.edu | faculty12345 |

## Student Access

1. Go to **Take Quiz** on the homepage
2. Enter the quiz code (e.g. `QUIZ2026CS101ABCD`)
3. Fill in student information
4. Take the exam and submit

## API Documentation

Base URL: `/api/v1`

### Public Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/health` | Health check |
| POST | `/quiz/verify` | Verify quiz code `{ "quiz_code": "QUIZ2026CS101" }` |

### Authenticated Endpoints (Bearer Token via Sanctum)

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/quizzes` | List faculty quizzes |
| GET | `/quizzes/{id}` | Get quiz with questions |
| GET | `/quizzes/{id}/attempts` | List student attempts |
| GET | `/quizzes/{id}/analytics` | Quiz analytics summary |

Generate API token:

```bash
php artisan tinker
>>> $user = App\Models\User::where('email', 'faculty@equizmona.edu')->first();
>>> $user->createToken('api-token')->plainTextToken;
```

## Project Structure

```
app/
├── Http/Controllers/
│   ├── Auth/          # Login, registration
│   ├── Admin/         # Admin dashboard
│   ├── Faculty/       # Quiz, questions, analytics
│   ├── Student/       # Exam taking
│   └── Api/           # REST API
├── Models/            # Eloquent models
├── Services/          # Business logic (AI, grading, parsing)
database/migrations/   # Database schema
resources/views/       # Blade templates
public/css|js/         # Frontend assets
routes/web.php         # Web routes
routes/api.php         # API routes
```

## Security

- CSRF protection on all forms
- Password hashing (bcrypt)
- Role-based middleware
- SQL injection prevention via Eloquent ORM
- File upload validation (PDF/DOCX only, 10MB max)
- XSS prevention via Blade escaping

## License

MIT License
