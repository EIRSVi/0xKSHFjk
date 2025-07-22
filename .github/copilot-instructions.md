# Online Quiz System (OQS) - Copilot Instructions

This is a comprehensive Laravel-based Online Quiz System with role-based access control and Material Design UI.

## Project Structure
- **Backend**: Laravel 12.x with Sanctum authentication
- **Frontend**: Blade templates with Tailwind CSS v3
- **Database**: SQLite for development (easily configurable for MySQL)
- **Styling**: Material Design principles with Google Fonts

## Key Features
- Role-based authentication (Admin, Teacher, Student)
- Quiz creation and management
- Real-time quiz taking with timers
- Comprehensive analytics and reporting
- Responsive Material Design UI

## User Roles & Permissions
- **Admin**: Full system control, user management, subject/class creation
- **Teacher**: Class management, quiz creation, student grading
- **Student**: Quiz participation, progress tracking

## Database Models
- User (with roles: admin, teacher, student)
- Subject, ClassModel, Quiz, Question
- QuizAttempt, UserAnswer, ClassEnrollment

## Development Guidelines
- Follow Laravel conventions and best practices
- Use Tailwind CSS utility classes for styling
- Implement Material Design components consistently
- Ensure responsive design for all screen sizes
- Maintain clean, readable code with proper documentation

## Authentication
- Default admin: admin@oqs.com / admin123
- Default teacher: teacher@oqs.com / teacher123  
- Default student: student1@oqs.com / student123

## Available Routes
- `/login` - Authentication
- `/admin/*` - Admin dashboard and management
- `/teacher/*` - Teacher dashboard and tools
- `/student/*` - Student dashboard and quizzes
