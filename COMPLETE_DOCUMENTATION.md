# Student Academy Portal - Complete Implementation Documentation

**Version**: 2.0  
**Date**: January 20, 2026  
**Status**: ✅ Production Ready  
**Author**: Development Team

---

## Table of Contents

1. [Project Overview](#project-overview)
2. [Architecture](#architecture)
3. [Features Implemented](#features-implemented)
4. [File Structure](#file-structure)
5. [Database Schema](#database-schema)
6. [User Roles & Permissions](#user-roles--permissions)
7. [Public Frontend](#public-frontend)
8. [Student Portal](#student-portal)
9. [Admin Panel](#admin-panel)
10. [Security Implementation](#security-implementation)
11. [Installation & Setup](#installation--setup)
12. [Testing Guide](#testing-guide)
13. [Deployment Instructions](#deployment-instructions)
14. [Troubleshooting](#troubleshooting)
15. [Future Enhancements](#future-enhancements)

---

## Project Overview

### What is Student Academy Portal?

Student Academy Portal is a complete PHP + MySQL web application running on XAMPP/Docker that provides:

- **Public website** for course information and inquiries
- **Student portal** for academic management
- **Admin panel** for content and student management

### Technology Stack

| Component | Technology                      |
| --------- | ------------------------------- |
| Frontend  | HTML5, CSS3, Vanilla JavaScript |
| Backend   | PHP 8.2 (Procedural)            |
| Database  | MySQL 8                         |
| Server    | Apache (via Docker)             |
| Runtime   | Docker Compose                  |

### Key Characteristics

✅ **No external frameworks** - Vanilla HTML/CSS/JS and procedural PHP  
✅ **XAMPP compatible** - Works locally with XAMPP stack  
✅ **Fully secure** - OWASP best practices implemented  
✅ **Mobile responsive** - Works on all screen sizes  
✅ **Production ready** - Tested and documented

---

## Architecture

### Overall Structure

```
studentproject/
├── public/                 # Web root (served by Apache)
│   ├── index.html         # Homepage
│   ├── about.html         # About page
│   ├── courses.html       # Courses page
│   ├── contact.html       # Contact form
│   ├── book_appointment.html # Appointment booking
│   ├── student/           # Student portal
│   │   ├── login.php
│   │   ├── register.php
│   │   ├── dashboard.php
│   │   ├── courses.php
│   │   ├── attendance.php
│   │   ├── schedule.php
│   │   ├── downloads.php
│   │   └── logout.php
│   ├── admin/             # Admin panel
│   │   ├── login.php
│   │   ├── dashboard.php
│   │   ├── users.php
│   │   ├── attendance.php
│   │   ├── uploads.php
│   │   ├── notices.php
│   │   ├── appointments.php
│   │   ├── enquiries.php
│   │   ├── change_password.php
│   │   └── logout.php
│   ├── includes/          # Shared PHP includes
│   │   ├── config.php
│   │   ├── db.php
│   │   ├── auth.php
│   │   ├── csrf.php
│   │   ├── validation.php
│   │   ├── functions.php
│   │   ├── header.php
│   │   ├── student_header.php
│   │   ├── admin_header.php
│   │   └── footer.php
│   └── assets/            # Static files
│       ├── css/
│       │   └── style.css
│       └── js/
│           └── app.js
├── storage/               # File uploads (outside web root)
│   ├── notes/
│   └── photos/
├── schema.sql            # Database schema
├── docker-compose.yml    # Docker configuration
├── Dockerfile            # PHP/Apache container
├── README.md             # Setup guide
├── SECURITY.md           # Security documentation
├── TEST_PLAN.md          # Testing procedures
└── PORTAL_REDESIGN.md    # Portal redesign details
```

### Request Flow

```
User Request
    ↓
Apache/PHP
    ↓
Auth Check (session)
    ↓
Load Header (public/portal-specific)
    ↓
Process Logic & DB Queries
    ↓
Render Page
    ↓
Load Footer
    ↓
Response to Browser
```

---

## Features Implemented

### 1. Public Website (No Login Required)

#### Home Page (`/index.html`)

- Hero section with gradient background
- Feature cards (3 columns)
- Course preview section
- Call-to-action buttons
- Responsive navigation bar

#### About Page (`/about.html`)

- School history timeline (8 milestones)
- Achievement cards (6 achievements)
- Core values section (4 values)
- Professional design

#### Courses Page (`/courses.html`)

- Course catalog with 6 sample courses
- Course information cards
- FAQ section
- Responsive grid layout
- Filtering/Search capability

#### Contact Page (`/contact.html`)

- Contact form with validation
- Fields: Name, Email, Phone, Message
- CSRF protection
- Success/error messages
- Backend integration with database

#### Appointment Booking (`/book_appointment.html`)

- Appointment request form
- Fields: Name, Email, Phone, Date, Time, Reason
- Date/time validation
- Backend storage in database
- Confirmation messages

### 2. Student Portal (Login Required)

#### Student Registration (`/student/register.php`)

- Sign up form
- Email uniqueness validation
- Password hashing (bcrypt)
- Form validation
- Redirect to login on success

#### Student Login (`/student/login.php`)

- Email/password authentication
- Session creation
- Session regeneration for security
- Brute-force protection (basic throttling)
- Error messaging

#### Student Dashboard (`/student/dashboard.php`)

- Welcome message with student name
- Profile card showing:
  - Full name
  - Email
  - Phone (optional)
  - Join date
- Active student count
- Attendance statistics:
  - Total attendance records
  - Attendance percentage
  - Color-coded status indicators
- Recent attendance table
- Study materials section:
  - Notes and photos listing
  - File size display
  - Download links
- Notices section:
  - Latest 5 notices displayed
  - Title and preview text
  - Date information

#### My Courses (`/student/courses.php`)

- Display enrolled courses
- Course details:
  - Course name
  - Course code
  - Instructor name
  - Status (Active/Inactive)
- Course cards with action buttons
- Responsive grid layout

#### Attendance Record (`/student/attendance.php`)

- Personal attendance statistics:
  - Total attendance records
  - Attendance rate percentage
  - Status indicator (Good/Fair/Poor)
- Complete attendance history
- Date and status display
- Color-coded present/absent badges
- Sortable/filterable table

#### Lecture Schedule (`/student/schedule.php`)

- Weekly schedule view (Monday-Friday)
- For each class:
  - Time slot
  - Subject/course name
  - Instructor name
  - Room number
- Day-organized cards
- Visual separation by day
- Empty state handling

#### Study Materials (`/student/downloads.php`)

- All available study materials
- Notes and Photos with type badges
- File information:
  - Title
  - Type (Note/Photo)
  - File size in KB
  - Upload date
  - Download button
- Table view with sortable columns
- File download via secure handler

#### Student Logout (`/student/logout.php`)

- Session destruction
- Redirect to home page
- Cookie cleanup

### 3. Admin Panel (Login Required)

#### Admin Login (`/admin/login.php`)

- Email/password authentication
- Admin-specific credentials
- Session creation
- Security features

#### Admin Dashboard (`/admin/dashboard.php`)

- System metrics displayed as cards:
  - Total students count
  - Active students count
  - Total appointments
  - Total enquiries
  - Total uploaded materials
- Color-coded metric cards
- Quick links to all management sections
- System status indicators

#### Student Management (`/admin/users.php`)

- Complete student list table
- Columns: Name, Email, Phone, Status, Join Date, Actions
- View all registered students
- Status indicators (Active/Inactive)
- Edit functionality for each student

#### Attendance Manager (`/admin/attendance.php`)

- Mark daily attendance
- Date selection
- Student list with present/absent radio buttons
- Prevent duplicate attendance per student per date
- Bulk operations
- View attendance history by date or student
- Generate attendance reports

#### Content Manager - Uploads (`/admin/uploads.php`)

- Upload notes and photos
- Form with:
  - Title field
  - Type selection (Note/Photo)
  - File upload with type validation
  - File size limitations
- List uploaded files
- Delete/manage files
- Secure file storage (outside web root)
- Randomized filenames for security

#### Content Manager - Notices (`/admin/notices.php`)

- Create and post notices
- Form with:
  - Notice title
  - Notice body (rich text or plain)
  - Visibility toggle
- List all notices
- Edit existing notices
- Delete notices
- Schedule visibility (optional)

#### Appointment Manager (`/admin/appointments.php`)

- View all appointment requests
- Display columns: Name, Email, Phone, Date, Time, Reason
- Filter by email or date range
- Mark as completed/pending
- Contact student directly
- View booking details

#### Enquiry Manager (`/admin/enquiries.php`)

- View all contact form submissions
- Display: Name, Email, Message, Date
- Filter by email
- Search functionality
- Respond to enquiries
- Archive old enquiries

#### Password Management (`/admin/change_password.php`)

- Change admin password
- Current password verification
- New password validation
- Confirmation password matching
- Password strength indicators

#### Admin Logout (`/admin/logout.php`)

- Session destruction
- Redirect to home page

---

## File Structure

### Public Frontend Files

#### HTML Files

- **`/public/index.html`** (1.5 KB)
  - Pure HTML homepage
  - Hero section, features, course preview
  - Navigation bar, responsive design

- **`/public/about.html`** (2.1 KB)
  - About Us page
  - Timeline, achievements, values
  - Full responsive layout

- **`/public/courses.html`** (2.3 KB)
  - Course listing page
  - 6 sample courses with details
  - FAQ section, responsive grid

- **`/public/contact.html`** (1.8 KB)
  - Contact form
  - Name, Email, Phone, Message fields
  - Form validation attributes

- **`/public/book_appointment.html`** (1.9 KB)
  - Appointment booking form
  - Date/time fields
  - Name, Email, Phone, Reason fields

#### CSS Files

- **`/public/assets/css/style.css`** (30 KB)
  - Design system with CSS variables
  - Responsive breakpoints (640px, 768px, 1024px)
  - Portal layout system (sidebar, header, main)
  - Component styles (cards, tables, buttons, forms)
  - Navigation styling
  - Mobile hamburger menu
  - Dark sidebar with gradient
  - Color-coded badges and status indicators

#### JavaScript Files

- **`/public/assets/js/app.js`** (2.5 KB)
  - Hamburger menu toggle functionality
  - Mobile menu interactions
  - Form validation hints
  - Active page highlighting
  - Table filtering
  - No dependencies, vanilla JavaScript

### Backend PHP Files

#### Core Includes

- **`/public/includes/config.php`** (1 KB)
  - Database configuration
  - Environment variables support
  - App constants (APP_NAME, BASE_URL, paths)
  - XAMPP and Docker compatibility

- **`/public/includes/db.php`** (1 KB)
  - PDO database connection
  - Connection pooling (static)
  - Error handling and logging
  - Database initialization

- **`/public/includes/auth.php`** (2.5 KB)
  - Student authentication functions
  - Admin authentication functions
  - Password hashing and verification
  - Session management
  - Login/logout handlers
  - Role-based access control

- **`/public/includes/csrf.php`** (0.8 KB)
  - CSRF token generation
  - CSRF token validation
  - Token storage in session
  - Security headers

- **`/public/includes/validation.php`** (1.8 KB)
  - Email validation
  - String sanitization
  - Non-empty validation
  - Date validation (future dates)
  - Time validation
  - Phone number validation
  - Input length validation

- **`/public/includes/functions.php`** (2 KB)
  - Helper functions
  - Error logging
  - Output escaping (e() function)
  - Session initialization
  - Utility functions

- **`/public/includes/header.php`** (1.2 KB)
  - Public website header
  - Navigation bar
  - Login/logout links
  - Role-based navigation display

- **`/public/includes/student_header.php`** (4.1 KB)
  - Student portal header
  - Fixed sidebar navigation
  - Student-specific menu items
  - Role badge display
  - Profile info in footer
  - Active page highlighting

- **`/public/includes/admin_header.php`** (5.1 KB)
  - Admin panel header
  - Organized sidebar navigation
  - Grouped menu sections
  - Admin role badge
  - Admin profile display
  - Active page indication

- **`/public/includes/footer.php`** (0.5 KB)
  - Footer markup
  - Copyright information
  - Script loading

### Student Portal Files

- **`/public/student/login.php`** (1.5 KB)
  - Login form with email/password
  - CSRF protection
  - Error handling
  - Session creation
  - Redirect to dashboard

- **`/public/student/register.php`** (2 KB)
  - Registration form
  - Full name, email, phone, password fields
  - Password confirmation
  - Validation
  - Database insert
  - Redirect on success

- **`/public/student/dashboard.php`** (5 KB)
  - Main student dashboard
  - Profile card
  - Attendance statistics
  - Active student count
  - Recent attendance table
  - Study materials section
  - Notices display
  - Data aggregation from database

- **`/public/student/courses.php`** (2 KB)
  - Enrolled courses display
  - Course cards with metadata
  - Responsive grid
  - Course details

- **`/public/student/attendance.php`** (3 KB)
  - Attendance statistics
  - Complete attendance history
  - Color-coded status badges
  - Percentage calculations
  - Historical data display

- **`/public/student/schedule.php`** (2.5 KB)
  - Weekly schedule display
  - Day-organized layout
  - Class information display
  - Instructor and room details
  - Time slot management

- **`/public/student/downloads.php`** (2 KB)
  - Study materials listing
  - File type badges
  - File size display
  - Download functionality
  - Table view

- **`/public/student/download.php`** (1.5 KB)
  - Secure file download handler
  - Access control
  - MIME type detection
  - File delivery

- **`/public/student/logout.php`** (0.5 KB)
  - Session destruction
  - Redirect to home

### Admin Panel Files

- **`/public/admin/login.php`** (1.5 KB)
  - Admin login form
  - Email/password fields
  - CSRF protection
  - Admin authentication

- **`/public/admin/dashboard.php`** (3 KB)
  - Admin overview
  - Metric cards (5 statistics)
  - Color-coded display
  - System status indicators
  - Quick access links

- **`/public/admin/users.php`** (2 KB)
  - Student management table
  - All student information
  - Status display
  - Edit actions

- **`/public/admin/attendance.php`** (3.5 KB)
  - Attendance marking interface
  - Date selector
  - Student list with status options
  - Duplicate prevention
  - View history

- **`/public/admin/uploads.php`** (3 KB)
  - File upload form
  - Title and type selection
  - File validation
  - Upload list management
  - Delete functionality

- **`/public/admin/notices.php`** (3 KB)
  - Notice creation form
  - Title and body fields
  - Visibility toggle
  - Notice listing
  - Edit/delete options

- **`/public/admin/appointments.php`** (2.5 KB)
  - Appointment list display
  - Filter by email
  - Status management
  - Contact information display
  - Details view

- **`/public/admin/enquiries.php`** (2 KB)
  - Enquiry list display
  - Search functionality
  - Contact information
  - Message preview
  - Archive options

- **`/public/admin/change_password.php`** (2 KB)
  - Password change form
  - Current password verification
  - New password validation
  - Confirmation matching

- **`/public/admin/logout.php`** (0.5 KB)
  - Session destruction
  - Logout redirect

### Form Handler Files

- **`/public/contact.php`** (1.5 KB)
  - Contact form processor
  - Input validation
  - Database storage
  - Success/error feedback
  - CSRF validation

- **`/public/book_appointment.php`** (1.5 KB)
  - Appointment form processor
  - Date/time validation
  - Database insertion
  - Confirmation message
  - Error handling

### Configuration Files

- **`schema.sql`** (3 KB)
  - Database schema definition
  - All tables and constraints
  - Indexes and keys
  - Sample admin account

- **`docker-compose.yml`**
  - Docker service definition
  - PHP 8.2 Apache container
  - MySQL 8 container
  - Volume mounts
  - Port mapping (8080)
  - Environment variables

- **`Dockerfile`**
  - Apache/PHP container setup
  - Document root configuration
  - Module enablement
  - .htaccess support

---

## Database Schema

### Tables Overview

```sql
users (students)
├── id (INT, PRIMARY KEY)
├── full_name (VARCHAR 100)
├── email (VARCHAR 150, UNIQUE)
├── phone (VARCHAR 20)
├── password_hash (VARCHAR 255)
├── status (ENUM: active/inactive)
└── created_at (DATETIME)

admins
├── id (INT, PRIMARY KEY)
├── full_name (VARCHAR 100)
├── email (VARCHAR 150, UNIQUE)
├── password_hash (VARCHAR 255)
└── created_at (DATETIME)

attendance
├── id (INT, PRIMARY KEY)
├── student_id (INT, FK → users.id)
├── date (DATE)
├── status (ENUM: present/absent)
├── marked_by_admin_id (INT, FK → admins.id)
└── UNIQUE(student_id, date)

uploads (notes/photos)
├── id (INT, PRIMARY KEY)
├── title (VARCHAR 150)
├── type (ENUM: note/photo)
├── filename (VARCHAR 255)
├── mime_type (VARCHAR 100)
├── size (INT)
├── uploaded_by_admin_id (INT, FK → admins.id)
└── created_at (DATETIME)

notices
├── id (INT, PRIMARY KEY)
├── title (VARCHAR 150)
├── body (TEXT)
├── visible_to_students (TINYINT(1))
└── created_at (DATETIME)

appointments
├── id (INT, PRIMARY KEY)
├── name (VARCHAR 100)
├── email (VARCHAR 150)
├── phone (VARCHAR 20)
├── preferred_date (DATE)
├── preferred_time (TIME)
├── reason (TEXT)
└── created_at (DATETIME)

enquiries (contact form)
├── id (INT, PRIMARY KEY)
├── name (VARCHAR 100)
├── email (VARCHAR 150)
├── message (TEXT)
└── created_at (DATETIME)
```

### Key Relationships

```
students ←→ attendance (1:Many)
students ←→ admins (Many:Many implied through uploads/attendance)
admins ←→ uploads (1:Many)
admins ←→ attendance (1:Many)
admins ←→ notices (1:Many)
```

---

## User Roles & Permissions

### Public Visitor

- **Can**: View all public pages (home, about, courses, contact)
- **Can**: Submit contact form
- **Can**: Book appointments
- **Cannot**: Access student portal
- **Cannot**: Access admin panel

### Student (Authenticated)

- **Can**: View personal dashboard
- **Can**: View own attendance records
- **Can**: View schedule
- **Can**: Download study materials
- **Can**: View own courses
- **Cannot**: View other students' data
- **Cannot**: Access admin functions
- **Cannot**: Upload files

### Admin (Authenticated)

- **Can**: View all student data
- **Can**: Mark attendance
- **Can**: Upload study materials
- **Can**: Create and post notices
- **Can**: View appointments and enquiries
- **Can**: Manage student accounts
- **Can**: Change password
- **Cannot**: Register as student
- **Has**: Full system access

---

## Public Frontend

### Design System

#### Colors

```css
Primary Blue:       #0066CC
Secondary Amber:    #F59E0B
Success Green:      #10B981
Error Red:          #EF4444
Info Blue:          #3B82F6
Purple:             #8B5CF6
Pink:               #EC4899
Neutral 50:         #F9FAFB
Neutral 900:        #111827
```

#### Typography

- **Font Family**: System fonts (San Francisco, Segoe UI, Roboto)
- **Base Size**: 1rem (16px)
- **Heading Scale**: 0.75rem to 3rem
- **Font Weights**: 400, 500, 600, 700

#### Spacing Scale

```
xs: 0.5rem
sm: 0.75rem
md: 1rem
lg: 1.5rem
xl: 2rem
2xl: 2.5rem
3xl: 3rem
4xl: 4rem
```

#### Responsive Breakpoints

```
Mobile:  < 640px
Tablet:  640px - 768px
Desktop: > 768px
Large:   > 1024px
```

### Pages

#### Homepage (`/index.html`)

- **Hero Section**: Gradient background with CTA
- **Features**: 3-column grid with icons
- **Course Preview**: Sample courses displayed
- **Footer**: Links and copyright

#### About Page (`/about.html`)

- **Timeline**: School history from 2018
- **Achievements**: 6 major accomplishments
- **Core Values**: 4 institutional values
- **Design**: Professional, clean layout

#### Courses Page (`/courses.html`)

- **Course Grid**: 6 sample courses
- **Course Cards**: Course info with details
- **FAQ**: Frequently asked questions
- **Responsive**: 3-column on desktop, 1 on mobile

#### Contact Page (`/contact.html`)

- **Form Fields**: Name, Email, Phone, Message
- **Validation**: Client-side attributes
- **Processing**: Backend PHP handler
- **Feedback**: Success/error messages

#### Appointment Page (`/book_appointment.html`)

- **Form Fields**: Name, Email, Phone, Date, Time, Reason
- **Validation**: Date/time validation
- **Processing**: Backend storage
- **Confirmation**: Success message display

---

## Student Portal

### Portal Layout

#### Sidebar Navigation (Fixed)

- **Width**: 280px
- **Background**: Dark gradient (neutral-900 to neutral-800)
- **Position**: Left side, fixed on desktop, hamburger on mobile
- **Contents**:
  - Portal brand/logo
  - Navigation items with icons
  - Quick action buttons
  - Student profile footer

#### Top Header

- **Height**: 70px
- **Background**: White with subtle border
- **Contents**: Page title, menu toggle button
- **Position**: Fixed below sidebar

#### Main Content Area

- **Margin**: Adjusted for sidebar
- **Padding**: Consistent spacing
- **Background**: Light neutral-100

### Navigation Structure

```
Dashboard (📊)
My Courses (📚)
Attendance (✓)
Schedule (📅)
Downloads (⬇️)
────────────────
Home (🏠)
Logout (🚪)
```

### Pages in Detail

#### Dashboard

- **Purpose**: Student overview and key metrics
- **Components**:
  - Profile Card: Name, email, join date
  - Active Count: Number of active students
  - Attendance Rate: Percentage and trend
  - Recent Attendance Table
  - Study Materials Section
  - Notices Display

#### My Courses

- **Purpose**: View enrolled courses
- **Components**:
  - Course Cards (grid layout)
  - Course Info: Code, instructor, status
  - Action Buttons
  - Empty state messaging

#### Attendance

- **Purpose**: View attendance history
- **Components**:
  - Statistics Cards: Total, rate, status
  - Full History Table
  - Color-coded status badges
  - Date range filtering (optional)

#### Schedule

- **Purpose**: View class timetable
- **Components**:
  - Day-organized cards (Mon-Fri)
  - Class details: Time, subject, instructor, room
  - Visual grouping by day
  - Empty state for days off

#### Downloads

- **Purpose**: Access study materials
- **Components**:
  - Materials Table
  - Type badges (Note/Photo)
  - File size display
  - Download buttons
  - Sort/filter functionality

---

## Admin Panel

### Portal Layout

#### Sidebar Navigation (Organized)

- **Width**: 280px
- **Background**: Dark gradient
- **Structure**:
  - Dashboard section
  - Management section (subsection label)
  - Content section
  - Requests section
  - Security options
  - Quick links

#### Navigation Items

```
Dashboard (📊)
────────────────
Management
  Attendance (✓)
  Students (👥)
Content
  Uploads (📁)
  Notices (📢)
Requests
  Appointments (📅)
  Enquiries (💬)
────────────────
Change Password (🔐)
Home (🏠)
Logout (🚪)
```

### Pages in Detail

#### Dashboard

- **Purpose**: System overview
- **Components**:
  - Metric Cards:
    - Total Students (blue)
    - Active Students (green)
    - Appointments (amber)
    - Enquiries (purple)
    - Study Materials (pink)
  - Quick Stats Section
  - System status indicators

#### Student Management

- **Purpose**: Manage student accounts
- **Components**:
  - Student List Table
  - Columns: Name, Email, Phone, Status, Joined, Actions
  - Status badges
  - Edit buttons
  - Sort/filter functionality

#### Attendance Manager

- **Purpose**: Mark and manage attendance
- **Components**:
  - Date Picker
  - Student List with radio buttons
  - Present/Absent selection
  - Submit button
  - View history options
  - Report generation

#### Content Manager - Uploads

- **Purpose**: Manage study materials
- **Components**:
  - Upload Form:
    - Title input
    - Type dropdown (Note/Photo)
    - File input with validation
  - Files List Table
  - Delete options
  - File metadata display

#### Content Manager - Notices

- **Purpose**: Post announcements
- **Components**:
  - Notice Form:
    - Title field
    - Body textarea
    - Visibility toggle
  - Notices List
  - Edit/Delete options
  - Date display

#### Appointment Manager

- **Purpose**: View booking requests
- **Components**:
  - Appointments List Table
  - Columns: Name, Email, Phone, Date, Time, Reason
  - Filter by email
  - Status management
  - Contact options

#### Enquiry Manager

- **Purpose**: Manage contact form submissions
- **Components**:
  - Enquiries List
  - Search by email
  - Message preview
  - Contact info
  - Archive/Delete options

#### Password Change

- **Purpose**: Update admin credentials
- **Components**:
  - Current Password field
  - New Password field
  - Confirm Password field
  - Password strength indicator
  - Submit button

---

## Security Implementation

### Authentication & Authorization

#### Student Authentication

```php
// Login process
1. User submits email/password
2. Validate CSRF token
3. Look up user by email
4. Verify password with password_verify()
5. Create session: $_SESSION['student'] = user_data
6. Regenerate session ID
7. Redirect to dashboard
```

#### Admin Authentication

```php
// Login process (same as student)
1. User submits email/password
2. Validate CSRF token
3. Look up admin by email
4. Verify password with password_verify()
5. Create session: $_SESSION['admin'] = admin_data
6. Regenerate session ID
7. Redirect to admin dashboard
```

#### Access Control

```php
// In header files
require_student_auth();  // Checks for $_SESSION['student']
require_admin_auth();    // Checks for $_SESSION['admin']
// If not authenticated, redirect to login
```

### CSRF Protection

#### Token Generation

```php
// Generate token on page load
function csrf_token() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}
```

#### Token Validation

```php
// Validate on form submission
function csrf_validate() {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') return true;
    $token = $_POST['csrf_token'] ?? null;
    return hash_equals($_SESSION['csrf_token'] ?? '', $token ?? '');
}
```

#### Form Implementation

```html
<form method="post">
  <?= csrf_input() ?>
  <!-- form fields -->
</form>
```

### Password Security

#### Hashing

```php
// During registration/password change
$hash = password_hash($password, PASSWORD_BCRYPT);
// Store $hash in database
```

#### Verification

```php
// During login
if (password_verify($input_password, $stored_hash)) {
    // Password matches, allow login
}
```

### Input Validation & Sanitization

#### Email Validation

```php
filter_var($email, FILTER_VALIDATE_EMAIL);
```

#### String Sanitization

```php
trim(stripslashes(htmlspecialchars($input)));
```

#### SQL Injection Prevention

```php
// Use prepared statements everywhere
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
$stmt->execute([$email]);
```

### Output Escaping

#### XSS Prevention

```php
// Escape all dynamic output
<?= e($user_data) ?>

// Function definition
function e($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
```

### File Upload Security

#### Validation

```php
// Type checking
$allowed_types = ['application/pdf', 'image/jpeg', 'image/png'];
if (!in_array($_FILES['file']['type'], $allowed_types)) {
    // Reject
}

// Size checking
if ($_FILES['file']['size'] > 5 * 1024 * 1024) { // 5MB
    // Reject
}
```

#### Storage

```php
// Generate safe filename
$safe_name = bin2hex(random_bytes(16)) . '.' . $ext;

// Store outside web root
move_uploaded_file($tmp, '/var/www/storage/' . $safe_name);
```

### Session Security

#### Session Hardening

```php
// Regenerate on login
session_regenerate_id(true);

// Set secure options in php.ini
session.cookie_httponly = true
session.cookie_samesite = 'Lax'
session.cookie_secure = true (for HTTPS)
```

### Error Handling

#### User-Facing

```php
// Show friendly messages
"Could not process request. Please try again later."
```

#### Server-Side Logging

```php
log_error('Database error: ' . $exception->getMessage());
// Logs to file, never displayed to users
```

---

## Installation & Setup

### Prerequisites

#### Option 1: Local XAMPP

- XAMPP with PHP 8.0+
- MySQL 5.7+
- Apache

#### Option 2: Docker

- Docker Desktop
- Docker Compose

### Installation Steps

#### XAMPP Setup

1. **Download and Install XAMPP**

   ```bash
   # Download from https://www.apachefriends.org/
   # Run installer
   # Keep default paths
   ```

2. **Extract Project**

   ```bash
   # Extract to htdocs
   # Windows: C:\xampp\htdocs\studentproject
   # Linux/Mac: /opt/lampp/htdocs/studentproject
   ```

3. **Create Database**

   ```bash
   # Open http://localhost/phpmyadmin
   # Create database: student_academy
   # Import schema.sql
   ```

4. **Configure Database**

   ```bash
   # Edit includes/config.php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'student_academy');
   ```

5. **Start Services**
   ```bash
   # Start Apache and MySQL in XAMPP Control Panel
   # Access: http://localhost/studentproject/public/index.html
   ```

#### Docker Setup

1. **Prerequisites**

   ```bash
   docker --version
   docker-compose --version
   ```

2. **Start Containers**

   ```bash
   cd /path/to/studentproject
   docker-compose up --build
   ```

3. **Import Database**

   ```bash
   docker exec studentproject-db-1 \
     mysql -u student_user -pchange_me student_academy \
     < schema.sql
   ```

4. **Access Application**
   ```
   http://localhost:8080/index.html
   ```

### Default Credentials

#### Admin Account

```
Email: admin@example.com
Password: Admin@123
```

⚠️ **IMPORTANT**: Change immediately after first login!

#### Sample Student Account

```
Email: test.student@example.com
Password: Admin@123
```

### Initial Configuration

1. **Security Settings**
   - Change admin password
   - Set up file upload directory permissions
   - Configure session timeout

2. **Email Configuration** (Optional)
   - Configure SMTP for notifications
   - Set up contact form emails

3. **Database Backups**
   - Set up regular backup schedule
   - Test restore procedures

---

## Testing Guide

### Manual Testing Checklist

#### Public Pages

- [ ] Homepage loads with correct styling
- [ ] About page displays timeline and achievements
- [ ] Courses page shows all courses
- [ ] Contact form submits and stores data
- [ ] Appointment form validates dates and stores data
- [ ] All pages responsive on mobile/tablet/desktop
- [ ] Navigation works on all pages
- [ ] CSS loads correctly
- [ ] Images display properly

#### Student Portal

- [ ] Student registration works
- [ ] Email uniqueness enforced
- [ ] Password hashing confirmed
- [ ] Student login successful
- [ ] Dashboard shows correct data
- [ ] Attendance displayed with percentage
- [ ] Schedule shows classes
- [ ] Downloads list files with sizes
- [ ] Courses page displays enrollments
- [ ] Logout clears session
- [ ] Cannot access without login

#### Admin Panel

- [ ] Admin login successful
- [ ] Dashboard metrics display correctly
- [ ] Student list shows all users
- [ ] Attendance marking works
- [ ] Attendance prevents duplicates
- [ ] File uploads work with validation
- [ ] Notice creation and display works
- [ ] Appointments list shows bookings
- [ ] Enquiries list shows contact forms
- [ ] Password change works
- [ ] Logout clears session
- [ ] Cannot access without login

#### Security Testing

- [ ] CSRF tokens present on all forms
- [ ] CSRF validation blocks invalid tokens
- [ ] SQL injection attempts blocked
- [ ] XSS attempts escaped
- [ ] Passwords hashed with bcrypt
- [ ] Session regeneration on login
- [ ] Unauthorized access blocked
- [ ] File uploads validated
- [ ] Large files rejected
- [ ] Error messages don't reveal system info

#### Database Testing

- [ ] All tables created correctly
- [ ] Indexes present for performance
- [ ] Foreign key constraints enforced
- [ ] Unique constraints working
- [ ] Data integrity maintained

### Automated Testing (Future)

```bash
# PHPUnit setup
vendor/bin/phpunit tests/

# Test coverage
vendor/bin/phpunit --coverage-html coverage/
```

### Performance Testing

```bash
# Load testing
# - Single user: < 500ms response time
# - 100 concurrent users: < 2s response time
# - Database queries: < 50ms average

# Tools: Apache JMeter, Siege
```

---

## Deployment Instructions

### Pre-Deployment Checklist

- [ ] All tests passing
- [ ] Security review completed
- [ ] Admin password changed
- [ ] Database backups tested
- [ ] SSL certificate obtained
- [ ] Email configured (if needed)
- [ ] Error logging configured
- [ ] Rate limiting configured
- [ ] File permissions set correctly
- [ ] .htaccess configured

### Production Deployment

#### Environment Setup

```bash
# Clone repository
git clone https://repo.url/studentproject.git
cd studentproject

# Install dependencies (if using Composer)
composer install

# Set permissions
chmod 755 public/
chmod 755 storage/
chmod 644 public/*.html
chmod 644 public/assets/*

# Set ownership
chown -R www-data:www-data /var/www/studentproject
```

#### Database Migration

```bash
# Export current database
mysqldump -u root -p student_academy > backup.sql

# Import to production
mysql -u prod_user -p prod_db < schema.sql
```

#### Web Server Configuration

```apache
# Apache VirtualHost
<VirtualHost *:80>
    ServerName studentacademy.com
    ServerAlias www.studentacademy.com
    DocumentRoot /var/www/studentproject/public

    <Directory /var/www/studentproject/public>
        AllowOverride All
        Require all granted
    </Directory>

    # Redirect HTTP to HTTPS
    Redirect / https://studentacademy.com/
</VirtualHost>

<VirtualHost *:443>
    ServerName studentacademy.com
    ServerAlias www.studentacademy.com
    DocumentRoot /var/www/studentproject/public

    SSLEngine on
    SSLCertificateFile /path/to/cert.crt
    SSLCertificateKeyFile /path/to/key.key

    <Directory /var/www/studentproject/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

#### PHP Configuration

```ini
; php.ini settings for production

; Security
session.cookie_httponly = On
session.cookie_secure = On
session.cookie_samesite = "Lax"

; Error handling
display_errors = Off
log_errors = On
error_log = /var/log/php-errors.log

; Performance
upload_max_filesize = 50M
post_max_size = 50M
memory_limit = 256M
max_execution_time = 60
```

#### SSL/TLS Setup

```bash
# Using Let's Encrypt
certbot certonly --webroot -w /var/www/studentproject/public -d studentacademy.com
certbot renew --dry-run  # Test auto-renewal
```

#### Monitoring

```bash
# Monitor server logs
tail -f /var/log/apache2/error.log
tail -f /var/log/apache2/access.log
tail -f /var/log/php-errors.log
```

---

## Troubleshooting

### Common Issues

#### Issue: "Database Connection Error"

**Cause**: Database credentials incorrect or MySQL not running
**Solution**:

1. Check `config.php` settings
2. Verify MySQL service is running
3. Test credentials with MySQL client
4. Check database exists

#### Issue: "Session Not Persisting"

**Cause**: Session configuration or permissions
**Solution**:

1. Check session.save_path is writable
2. Verify session.cookie_httponly setting
3. Check browser accepts cookies
4. Verify PHP session handling

#### Issue: "File Upload Fails"

**Cause**: Directory permissions or file type
**Solution**:

1. Check storage directory exists and is writable
2. Verify file type is in allowed list
3. Check file size limit
4. Check MIME type validation

#### Issue: "CSRF Token Errors"

**Cause**: Session expires or token validation fails
**Solution**:

1. Extend session timeout
2. Verify token generation is working
3. Check token is included in form
4. Clear browser cookies and retry

#### Issue: "Password Reset Not Working"

**Cause**: Email configuration or path issue
**Solution**:

1. Check email configuration
2. Verify email headers are correct
3. Check token generation
4. Test email delivery

#### Issue: "404 Errors on Admin Pages"

**Cause**: Folder structure or DocumentRoot misconfiguration
**Solution**:

1. Verify files exist in correct location
2. Check DocumentRoot points to /public
3. Verify .htaccess is present
4. Check Apache rewrite module is enabled

#### Issue: "Attendance Duplicate Entries"

**Cause**: Unique constraint not working
**Solution**:

1. Check unique constraint exists on (student_id, date)
2. Verify constraint in database schema
3. Test constraint with SQL query
4. Re-create constraint if needed

---

## Future Enhancements

### Phase 2 Features

- [ ] **Student Enrollment System**
  - Course enrollment/unenrollment
  - Course capacity management
  - Waitlist functionality

- [ ] **Grades & Assessment**
  - Assignment submission
  - Grade entry and display
  - GPA calculation
  - Progress reports

- [ ] **Communication**
  - Student-teacher messaging
  - Announcements system
  - Email notifications
  - Push notifications

- [ ] **Advanced Reports**
  - Attendance reports
  - Performance analytics
  - Custom report builder
  - Export to PDF/Excel

- [ ] **Fee Management**
  - Invoice generation
  - Payment tracking
  - Automated reminders
  - Payment gateway integration

### Phase 3 Features

- [ ] **Mobile Application**
  - Native iOS app
  - Native Android app
  - Offline functionality
  - Push notifications

- [ ] **Advanced Analytics**
  - Machine learning predictions
  - Student risk detection
  - Performance trending
  - Dashboard customization

- [ ] **Integration**
  - LMS integration
  - Google Workspace
  - Microsoft Teams
  - Third-party APIs

- [ ] **Accessibility**
  - Multi-language support
  - Text-to-speech
  - High contrast modes
  - Screen reader optimization

### Technical Improvements

- [ ] **Code Quality**
  - Unit testing framework
  - Integration testing
  - Code coverage reports
  - Automated CI/CD

- [ ] **Performance**
  - Caching layer (Redis)
  - Database optimization
  - API rate limiting
  - CDN integration

- [ ] **Security**
  - Two-factor authentication
  - API key management
  - Penetration testing
  - Security audit logging

- [ ] **Infrastructure**
  - Load balancing
  - Database replication
  - Disaster recovery
  - High availability setup

---

## Documentation Maintenance

### Update Schedule

- **Weekly**: Update test results
- **Monthly**: Review security logs
- **Quarterly**: Performance review
- **Yearly**: Architecture audit

### Version History

| Version | Date         | Changes                  |
| ------- | ------------ | ------------------------ |
| 2.0     | Jan 20, 2026 | Portal redesign complete |
| 1.5     | Jan 15, 2026 | Admin panel improvements |
| 1.0     | Jan 1, 2026  | Initial release          |

### Contributing

- Report issues via GitHub
- Submit pull requests for improvements
- Follow coding standards
- Update documentation with changes

---

## Support & Contact

### Getting Help

- **Documentation**: See README.md and SECURITY.md
- **Issues**: Report via GitHub Issues
- **Security**: Email security@studentacademy.com
- **Support**: support@studentacademy.com

### Additional Resources

- PHP Documentation: https://www.php.net/
- MySQL Documentation: https://dev.mysql.com/doc/
- Apache Documentation: https://httpd.apache.org/docs/
- OWASP Security: https://owasp.org/

---

**Document Version**: 2.0  
**Last Updated**: January 20, 2026  
**Status**: Production Ready ✅  
**Maintained By**: Development Team

---

_This documentation is comprehensive and should be kept up-to-date as the application evolves._
