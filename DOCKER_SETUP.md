# Docker Deployment Guide for eQUIZMona

## Prerequisites
- Docker and Docker Compose installed locally
- Render account for deployment

## Local Development

### 1. Start the application locally
```bash
docker-compose up --build
```

This will:
- Build the Laravel application with PHP 8.1
- Start MySQL database
- Install all dependencies
- Run migrations
- Start the app on http://localhost:8000

### 2. Access the application
- Application: http://localhost:8000
- MySQL: localhost:3306

### 3. Environment Variables
Configure `docker-compose.yml` environment variables:
- `DB_DATABASE`: equizmona
- `DB_USERNAME`: laravel
- `DB_PASSWORD`: password (change in production!)

## Deployment to Render

### 1. Connect your GitHub repository to Render
- Log in to Render dashboard
- Create a new Web Service
- Connect your GitHub repository

### 2. Configure Render Service
Set these environment variables in Render:
```
DB_CONNECTION=mysql
DB_HOST=your-mysql-host.render.com
DB_PORT=3306
DB_DATABASE=equizmona
DB_USERNAME=your_db_user
DB_PASSWORD=your_db_password
APP_ENV=production
APP_DEBUG=false
```

### 3. Database Setup
You can either:
- Use Render's MySQL database service (recommended)
- Use an external MySQL service (e.g., AWS RDS, DigitalOcean)

Add the MySQL service in Render and link it to your web service.

### 4. Build Command
Render will automatically use the Dockerfile. If you need a custom build:
```bash
docker build -t equizmona .
```

### 5. Deploy
Render will automatically deploy when you push to your main branch (or configured branch).

## Troubleshooting

### Migrations won't run
The Dockerfile is set to run migrations automatically. If you need to run them manually:
```bash
docker-compose exec app php artisan migrate
```

### Storage permissions issues
```bash
docker-compose exec app chmod -R 775 storage bootstrap/cache
```

### Rebuild without cache
```bash
docker-compose up --build --no-cache
```

## Production Notes
- Change `APP_DEBUG=false` in production
- Use strong database passwords
- Enable HTTPS on Render
- Set `APP_ENV=production`
- Configure proper backups for MySQL data
