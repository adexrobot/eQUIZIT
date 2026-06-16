# eQUIZMona REST API Documentation

Base URL: `{APP_URL}/api/v1`

## Authentication

Protected endpoints require a Bearer token from Laravel Sanctum.

```bash
# Generate token (run in tinker)
php artisan tinker
>>> $user = App\Models\User::where('email', 'faculty@equizmona.edu')->first();
>>> echo $user->createToken('api-token')->plainTextToken;
```

Include in requests:

```
Authorization: Bearer {your-token}
Accept: application/json
```

---

## Public Endpoints

### Health Check

```
GET /api/v1/health
```

**Response:**
```json
{
  "status": "ok",
  "app": "eQUIZMona"
}
```

### Verify Quiz Code

```
POST /api/v1/quiz/verify
Content-Type: application/json
```

**Body:**
```json
{
  "quiz_code": "QUIZ2026CS101ABCD"
}
```

**Success Response (200):**
```json
{
  "valid": true,
  "data": {
    "title": "Midterm Examination",
    "subject": "Computer Science",
    "duration": 60,
    "quiz_code": "QUIZ2026CS101ABCD"
  }
}
```

**Error Response (404):**
```json
{
  "valid": false,
  "message": "Quiz not found or unavailable."
}
```

---

## Authenticated Endpoints

### List Quizzes

```
GET /api/v1/quizzes
Authorization: Bearer {token}
```

**Response:**
```json
{
  "data": [
    {
      "id": 1,
      "title": "Midterm Exam",
      "quiz_code": "QUIZ2026CS101ABCD",
      "status": "published",
      "questions_count": 10,
      "attempts_count": 25
    }
  ]
}
```

### Get Quiz Details

```
GET /api/v1/quizzes/{id}
Authorization: Bearer {token}
```

Returns quiz with all questions.

### List Quiz Attempts

```
GET /api/v1/quizzes/{id}/attempts
Authorization: Bearer {token}
```

Returns student attempts with scores.

### Quiz Analytics

```
GET /api/v1/quizzes/{id}/analytics
Authorization: Bearer {token}
```

**Response:**
```json
{
  "data": {
    "highest": 95.5,
    "lowest": 45.0,
    "average": 78.25,
    "total_attempts": 25
  }
}
```

---

## Error Codes

| Code | Description |
|------|-------------|
| 401 | Unauthenticated |
| 403 | Forbidden (not quiz owner) |
| 404 | Resource not found |
| 422 | Validation error |
