# API Documentation for Demo

**Project**: Online Examination System  
**API Type**: RESTful with Laravel Sanctum  
**Status**: Complete and ready for demo

---

## 🔑 API Authentication

### **Base URL**

```
http://localhost:8000/api
```

### **Authentication Method**

- Laravel Sanctum Token-based authentication
- Token passed via `Authorization: Bearer {token}` header

---

## 📚 API Endpoints Overview

### **Public Endpoints (No Auth Required)**

#### **1. User Registration**

```http
POST /api/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}

Response: 201 Created
{
  "user": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "role": "student"
  },
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
}
```

#### **2. User Login**

```http
POST /api/login
Content-Type: application/json

{
  "email": "admin@example.com",
  "password": "password123"
}

Response: 200 OK
{
  "user": {
    "id": 1,
    "name": "Admin User",
    "email": "admin@example.com",
    "role": "admin"
  },
  "token": "eyJ0eXAiOiJKV1QiLCJhbGc..."
}
```

#### **3. OTP Verification**

```http
POST /api/verify-otp
Content-Type: application/json

{
  "email": "user@example.com",
  "otp": "123456"
}

Response: 200 OK
{
  "message": "Email verified successfully"
}
```

#### **4. Forgot Password**

```http
POST /api/forgot-password
Content-Type: application/json

{
  "email": "user@example.com"
}

Response: 200 OK
{
  "message": "OTP sent to your email"
}
```

#### **5. Reset Password**

```http
POST /api/reset-password
Content-Type: application/json

{
  "email": "user@example.com",
  "otp": "123456",
  "password": "newpassword123",
  "password_confirmation": "newpassword123"
}

Response: 200 OK
{
  "message": "Password reset successfully"
}
```

---

### **Protected Endpoints (Auth Required)**

#### **User Profile**

**Get Current User**

```http
GET /api/user
Authorization: Bearer {token}

Response: 200 OK
{
  "id": 1,
  "name": "Admin User",
  "email": "admin@example.com",
  "role": "admin",
  "profile_image_url": "https://..."
}
```

**Update Profile**

```http
POST /api/user/update
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Updated Name",
  "email": "newemail@example.com"
}

Response: 200 OK
{
  "message": "Profile updated successfully",
  "user": { ... }
}
```

**Logout**

```http
POST /api/logout
Authorization: Bearer {token}

Response: 200 OK
{
  "message": "Logged out successfully"
}
```

**Delete Account**

```http
DELETE /api/account
Authorization: Bearer {token}

Response: 200 OK
{
  "message": "Account deleted successfully"
}
```

---

#### **Student Endpoints**

**Student Dashboard**

```http
GET /api/student/dashboard
Authorization: Bearer {token}

Response: 200 OK
{
  "enrolled_classes_count": 3,
  "total_exams_taken": 5,
  "average_score": 85.5,
  "upcoming_exams": [...]
}
```

**Get Student Classes**

```http
GET /api/student/classes
Authorization: Bearer {token}

Response: 200 OK
{
  "data": [
    {
      "id": 1,
      "name": "Advanced Mathematics",
      "code": "MATH-2024-001",
      "teacher": "Dr. Smith",
      "student_count": 25
    }
  ]
}
```

**Get Available Exams**

```http
GET /api/student/exams
Authorization: Bearer {token}

Response: 200 OK
{
  "data": [
    {
      "id": 1,
      "title": "Final Exam",
      "class": "Mathematics",
      "duration": 60,
      "due_at": "2026-02-15",
      "status": "available"
    }
  ]
}
```

**Get Student Results**

```http
GET /api/student/results
Authorization: Bearer {token}

Response: 200 OK
{
  "data": [
    {
      "id": 1,
      "exam_title": "Midterm",
      "score": 85,
      "total_questions": 20,
      "correct_answers": 17,
      "submitted_at": "2026-01-15 10:30:00"
    }
  ]
}
```

**Review Exam Results**

```http
GET /api/student/results/{id}
Authorization: Bearer {token}

Response: 200 OK
{
  "exam_title": "Final Exam",
  "score": 90,
  "total_questions": 25,
  "correct_answers": 22,
  "answered_questions": [
    {
      "question": "What is 2+2?",
      "your_answer": "4",
      "correct_answer": "4",
      "is_correct": true
    }
  ]
}
```

**Join Class**

```http
POST /api/student/join-class
Authorization: Bearer {token}
Content-Type: application/json

{
  "class_code": "MATH-2024-001"
}

Response: 200 OK
{
  "message": "Joined class successfully",
  "class": { ... }
}
```

**Submit Exam**

```http
POST /api/student/exams/{id}/submit
Authorization: Bearer {token}
Content-Type: application/json

{
  "answers": [
    {
      "question_id": 1,
      "selected_option": "option_a"
    },
    {
      "question_id": 2,
      "selected_option": "option_b"
    }
  ]
}

Response: 200 OK
{
  "message": "Exam submitted successfully",
  "result": {
    "score": 85,
    "total_questions": 20,
    "correct_answers": 17
  }
}
```

---

#### **Teacher Endpoints**

**Teacher Dashboard**

```http
GET /api/teacher/dashboard
Authorization: Bearer {token}

Response: 200 OK
{
  "classes_count": 3,
  "students_count": 75,
  "exams_count": 12,
  "recent_submissions": [...]
}
```

**Get Teacher Classes**

```http
GET /api/teacher/classes
Authorization: Bearer {token}

Response: 200 OK
{
  "data": [
    {
      "id": 1,
      "name": "Mathematics 101",
      "code": "MATH-2024-001",
      "students_count": 25,
      "created_at": "2026-01-12"
    }
  ]
}
```

**Create Class**

```http
POST /api/teacher/classes
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Advanced Physics",
  "code": "PHYS-2024-001"
}

Response: 201 Created
{
  "message": "Class created successfully",
  "class": { ... }
}
```

**Get Exams**

```http
GET /api/teacher/exams
Authorization: Bearer {token}

Response: 200 OK
{
  "data": [
    {
      "id": 1,
      "title": "Midterm Exam",
      "class": "Mathematics 101",
      "duration": 60,
      "total_questions": 20,
      "submissions": 18
    }
  ]
}
```

**Create Exam**

```http
POST /api/teacher/exams
Authorization: Bearer {token}
Content-Type: application/json

{
  "title": "Final Exam",
  "class_id": 1,
  "duration": 120,
  "due_at": "2026-02-15",
  "closed_at": "2026-02-20"
}

Response: 201 Created
{
  "message": "Exam created successfully",
  "exam": { ... }
}
```

**Add Question**

```http
POST /api/teacher/exams/{exam_id}/questions
Authorization: Bearer {token}
Content-Type: application/json

{
  "question": "What is the capital of France?",
  "option_a": "London",
  "option_b": "Berlin",
  "option_c": "Paris",
  "option_d": "Madrid",
  "correct_option": "option_c"
}

Response: 201 Created
{
  "message": "Question added successfully",
  "question": { ... }
}
```

**Get Grades**

```http
GET /api/teacher/grades?class_id=1
Authorization: Bearer {token}

Response: 200 OK
{
  "data": [
    {
      "student_id": 1,
      "student_name": "John Doe",
      "exam_title": "Midterm",
      "score": 85,
      "submitted_at": "2026-01-15 10:30:00"
    }
  ]
}
```

---

#### **Admin Endpoints**

**Admin Dashboard**

```http
GET /api/admin/dashboard
Authorization: Bearer {token}

Response: 200 OK
{
  "total_users": 150,
  "total_teachers": 10,
  "total_students": 135,
  "total_classes": 8,
  "total_exams": 20,
  "recent_activity": [...]
}
```

**Get All Users**

```http
GET /api/admin/users
Authorization: Bearer {token}

Response: 200 OK
{
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "role": "student"
    }
  ]
}
```

**Create Teacher**

```http
POST /api/admin/teachers
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Dr. Smith",
  "email": "smith@example.com",
  "password": "password123"
}

Response: 201 Created
{
  "message": "Teacher created successfully",
  "teacher": { ... }
}
```

**Update Teacher**

```http
PUT /api/admin/teachers/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "Dr. Smith Jr",
  "email": "smith.jr@example.com"
}

Response: 200 OK
{
  "message": "Teacher updated successfully",
  "teacher": { ... }
}
```

**Delete Teacher**

```http
DELETE /api/admin/teachers/{id}
Authorization: Bearer {token}

Response: 200 OK
{
  "message": "Teacher deleted successfully"
}
```

---

## 🧪 Testing the API (Using cURL)

### **Example: Login via cURL**

```bash
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "password123"
  }'
```

### **Example: Get User Profile**

```bash
curl -X GET http://localhost:8000/api/user \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

### **Example: Create an Exam**

```bash
curl -X POST http://localhost:8000/api/teacher/exams \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Content-Type: application/json" \
  -d '{
    "title": "Quiz 1",
    "class_id": 1,
    "duration": 30,
    "due_at": "2026-02-15"
  }'
```

---

## 📊 API Response Format

### **Success Response (200)**

```json
{
    "message": "Operation successful",
    "data": {
        /* response data */
    }
}
```

### **Created Response (201)**

```json
{
    "message": "Resource created successfully",
    "data": {
        /* resource data */
    }
}
```

### **Error Response (400/401/403/404/500)**

```json
{
    "message": "Error message",
    "errors": {
        "field_name": ["Error details"]
    }
}
```

---

## 🔐 Security Features

1. **CORS Protection**: Configured in `config/cors.php`
2. **CSRF Protection**: Enabled for web routes
3. **Rate Limiting**: API throttling enabled
4. **Sanctum Tokens**: Secure token-based authentication
5. **Role Middleware**: Access control based on user roles
6. **Input Validation**: All inputs validated before processing

---

## 📱 API Client Recommendations

For testing APIs in demo:

1. **Postman** (Recommended)
    - Import collection for all endpoints
    - Save tokens automatically
    - Mock requests

2. **Insomnia**
    - Request history
    - Environment variables
    - Code generation

3. **cURL** (Command line)
    - Simple and quick
    - No GUI needed
    - Shell scripting friendly

---

## ⚠️ API Demo Tips

1. **Show login flow first** - Get token to use in other requests
2. **Test with Postman** - More professional than cURL
3. **Keep responses visible** - Screenshot or record for slides
4. **Explain error handling** - Show what happens on invalid input
5. **Don't overload** - Pick 3-4 key endpoints to demonstrate

---

## 🔗 Helpful Resources

- **Laravel Sanctum Docs**: https://laravel.com/docs/sanctum
- **RESTful API Best Practices**: https://restfulapi.net/
- **HTTP Status Codes**: https://httpwg.org/specs/rfc7231.html#status.codes
