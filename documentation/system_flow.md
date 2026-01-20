# System Flow Documentation

This document describes the flow of the Online Examination System for different user roles.

## User Roles
1.  **Admin**: Manages the overall system, including teachers and classes.
2.  **Teacher**: Manages their specific classes, exams, and questions.
3.  **Student**: Joins classes, takes exams, and views results.

## Authentication Flow
- **Registration**: Users can register as a student. Teachers and Admins are typically created by an existing Admin.
- **Login**: Users login with email and password or Google OAuth.
- **OTP Verification**: Verifies email ownership.
- **Password Reset**: Users can request a password reset via email OTP.

## Admin Flow
1.  **Dashboard**: View system-wide statistics.
2.  **Teacher Management**:
    -   View list of teachers.
    -   Create new teacher accounts.
    -   Edit or Delete teacher accounts.
3.  **Class Management**:
    -   View all classes in the system.
    -   Create, Edit, or Delete classes directly.
4.  **Profile**: Update own profile settings.

## Teacher Flow
1.  **Dashboard**: View statistics for their classes and students.
2.  **Class Management**:
    -   View assigned classes.
    -   Access "Manage Class" view for specific class details.
3.  **Exam Management**:
    -   Create new exams for a specific class.
    -   Set exam title, duration, due date, and closing date.
    -   Edit or Delete exams.
4.  **Question Management**:
    -   Add questions to an exam.
    -   Define options (A, B, C, D) and the correct answer.
    -   Edit or Delete questions.
5.  **Grading**:
    -   View grades/results for students in their classes.

## Student Flow
1.  **Dashboard**: View enrolled classes and upcoming exams.
2.  **Join Class**:
    -   Input a unique class code provided by the teacher to join a class.
3.  **Taking Exams**:
    -   View list of available exams.
    -   Start an exam (if within the valid time window).
    -   Answer questions within the time limit.
    -   Submit the exam.
4.  **Results**:
    -   View immediate results (score) after submission.
    -   Review exam details (correct/incorrect answers) if allowed.
5.  **Profile**: Update own profile settings.
