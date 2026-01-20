# Database Documentation

This document outlines the database schema for the Online Examination System.

## Tables

### `users`
Stores user information for admins, teachers, and students.
- **id**: Primary Key
- **name**: User's full name
- **email**: Unique email address
- **profile_image**: Path to profile image (nullable)
- **role**: Enum ['admin', 'teacher', 'student'] (default: 'student')
- **password**: Hashed password
- **email_verified_at**: Timestamp provided by Laravel (nullable)
- **otp**: One Time Password for verification
- **otp_expires_at**: Expiry time for OTP
- **email_verified**: Boolean status of email verification
- **google_id**: ID for Google Authentication
- **remember_token**: Token for "remember me" functionality
- **created_at**, **updated_at**: Timestamps

### `class_rooms`
Stores information about classes created by teachers.
- **id**: Primary Key
- **name**: Class name
- **code**: Unique code for students to join
- **logo**: Path to class logo image (nullable)
- **teacher_id**: Foreign Key -> `users.id`
- **created_at**, **updated_at**: Timestamps

### `class_student`
Pivot table for the Many-to-Many relationship between Classes and Students.
- **class_id**: Foreign Key -> `class_rooms.id`
- **student_id**: Foreign Key -> `users.id`
- **Constraint**: Composite Primary Key (`class_id`, `student_id`)

### `exams`
Stores exam details.
- **id**: Primary Key
- **title**: Exam title
- **class_id**: Foreign Key -> `class_rooms.id`
- **teacher_id**: Foreign Key -> `users.id`
- **duration**: Duration in minutes
- **due_at**: Deadline for starting the exam (nullable)
- **closed_at**: Hard deadline for the exam (nullable)
- **created_at**, **updated_at**: Timestamps

### `questions`
Stores questions for exams.
- **id**: Primary Key
- **exam_id**: Foreign Key -> `exams.id`
- **question**: Question text
- **option_a**: Option A text
- **option_b**: Option B text
- **option_c**: Option C text
- **option_d**: Option D text
- **correct_option**: The key of the correct option (e.g., 'option_a')
- **created_at**, **updated_at**: Timestamps

### `student_answers`
Stores answers submitted by students.
- **id**: Primary Key
- **user_id**: Foreign Key -> `users.id` (Student)
- **exam_id**: Foreign Key -> `exams.id`
- **question_id**: Foreign Key -> `questions.id`
- **selected_option**: The option selected by the student
- **created_at**, **updated_at**: Timestamps

### `results`
Stores the final score of a student for an exam.
- **id**: Primary Key
- **user_id**: Foreign Key -> `users.id` (Student)
- **exam_id**: Foreign Key -> `exams.id`
- **score**: The calculated score
- **is_late**: Boolean indicating if the submission was late
- **created_at**, **updated_at**: Timestamps

## Relationships

- **User (Teacher)** has many **ClassRooms**.
- **User (Student)** belongs to many **ClassRooms** (via `class_student`).
- **ClassRoom** has many **Exams**.
- **Exam** has many **Questions**.
- **Exam** has many **Results**.
- **User (Student)** has many **Results**.
- **User (Student)** has many **StudentAnswers**.
