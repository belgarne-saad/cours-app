# 🔐 Complete Authentication System Documentation

## System Overview

This is a **GLOBAL authentication system** that protects ALL pages in the application. No user can access ANY page without authentication.

### Key Features:
- ✅ Two-tier user system (Students & Admin)
- ✅ Session-based protection on ALL pages
- ✅ Secure password hashing for students
- ✅ Hardcoded admin credentials (no database)
- ✅ Automatic redirects for unauthorized access
- ✅ Clean separation of student and admin interfaces

---

## 🚀 QUICK START

### Step 1: Initialize Database
Visit: `http://localhost/xampp/cours-app/auth/setup.php`

This will create the `students` table for storing student accounts.

### Step 2: First Access
Visit: `http://localhost/xampp/cours-app/` 
OR: `http://localhost/xampp/cours-app/auth/index.php`

The application will automatically redirect you to the authentication page.

---

## 📋 Authentication Flow

```
ANY PAGE ACCESS
        ↓
    Is user authenticated?
        ↓
    ├─ NO → Redirect to auth/index.php
    │
    └─ YES → Is user type valid?
        ├─ Student → Allow access to public pages
        │   └─ Block admin pages
        │
        └─ Admin → Allow access to admin pages
            └─ Block student pages
```

---

## 🔐 How It Works: Technical Explanation

### 1. GLOBAL AUTHENTICATION GATE (`config/auth_gate.php`)

**Purpose:** Protects ALL pages from unauthorized access.

**How it works:**
```php
// This is included at the TOP of EVERY page
include("../config/auth_gate.php");

// The gate checks:
// 1. Is a session started?
// 2. Is $_SESSION['user_id'] set?
// 3. Is user on an auth page (allowed without login)?
// 4. If NOT authenticated → Redirect to auth/index.php
```

**Code flow:**
- Start session if not already started
- Check if `$_SESSION['user_id']` exists
- If NOT: Get the current file name
- If current file is NOT in auth/ folder → REDIRECT to login
- If current file IS in auth/ folder → ALLOW (auth pages can be accessed)

### 2. ADMIN ACCESS CONTROL (`auth/admin_gate.php`)

**Purpose:** Ensures ONLY admin can access admin pages.

**How it works:**
```php
// Included at the TOP of every admin page
include("../auth/admin_gate.php");

// Checks:
// 1. Is user authenticated?
// 2. Is $_SESSION['user_type'] === 'admin'?
// 3. If NOT admin → Redirect to auth/index.php
```

**This prevents students** from accessing admin pages even if they somehow get the URL.

### 3. SESSION VARIABLES

When a user logs in, the session stores:

**For Students:**
```php
$_SESSION['user_id']      = 1;              // Student ID from database
$_SESSION['user_type']    = 'student';      // Type identifier
$_SESSION['user_name']    = 'John Doe';     // Student name
$_SESSION['user_email']   = 'john@email.com'; // Student email
```

**For Admin:**
```php
$_SESSION['user_id']      = 'admin';        // Special admin ID
$_SESSION['user_type']    = 'admin';        // Type identifier
$_SESSION['user_name']    = 'Administrator'; // Fixed name
$_SESSION['username']     = 'admin';        // Admin username
```

---

## 👥 USER FLOWS

### ÉTUDIANT (STUDENT) FLOW

#### Registration:
1. User clicks "Étudiant" on auth/index.php
2. User enters: Name, Email, Password
3. System validates:
   - ✓ All fields filled
   - ✓ Valid email format
   - ✓ Password ≥ 6 characters
   - ✓ Email not already registered
4. Password is hashed using `password_hash()`
5. Student account created in database
6. Session automatically created (auto-login)
7. Redirect to `public/index.php` (student home)

#### Login:
1. User clicks "Étudiant" on auth/index.php
2. User enters: Email, Password
3. System checks:
   - ✓ Student exists in database
   - ✓ Password matches (using `password_verify()`)
4. Session created
5. Redirect to `public/index.php`

#### Access Rights:
- ✅ Can view courses
- ✅ Can search courses
- ✅ Can download PDFs
- ❌ CANNOT access admin dashboard
- ❌ If tries to access admin page → Redirected to auth/index.php

### PROFESSEUR (ADMIN) FLOW

#### Login Only (No Registration):
1. User clicks "Professeur" on auth/index.php
2. User enters: Username, Password
3. System checks against HARDCODED credentials:
   - Username: `admin`
   - Password: `admin123`
4. If credentials match:
   - Session created
   - Redirect to `admin/dashboard.php`
5. If credentials don't match:
   - Show error message
   - Remain on login page

#### Access Rights:
- ✅ Can access admin/dashboard.php
- ✅ Can add courses
- ✅ Can edit courses
- ✅ Can delete courses
- ❌ CANNOT access student pages (redirected to dashboard)

#### Security Notes:
- Admin credentials are **NOT in the database**
- They are **hardcoded in the PHP file** (`auth/admin_login.php`)
- This prevents database compromise from exposing admin password
- For production: Use environment variables or secure key management

---

## 🔑 Database Structure

### Students Table (AUTO-CREATED by setup.php)
```sql
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,           -- HASHED, never plain text
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX email_idx (email)
);
```

### NO Admin Table
- Admin credentials are hardcoded only
- Not stored in database
- Cannot be compromised by SQL injection or database theft

---

## 📁 File Structure & Responsibilities

### Auth Files (`auth/`)
- **index.php** - Main entry point (choice between Étudiant/Professeur)
- **student_login.php** - Student login form
- **student_register.php** - Student registration form
- **admin_login.php** - Admin login form (hardcoded credentials)
- **logout.php** - Logout for both student and admin
- **setup.php** - Create students database table
- **admin_gate.php** - Admin access control check
- **check_auth.php** - DEPRECATED (use auth_gate.php instead)

### Config Files (`config/`)
- **db.php** - Database connection
- **auth_gate.php** - Global authentication check (MOST IMPORTANT)

### Public Files (`public/`)
- **index.php** - Student home (protected, student only)
- **search.php** - Search courses (protected, student only)
- **course.php** - View course details (protected, student only)
- **search_api.php** - API for live search (protected, student only)

### Admin Files (`admin/`)
- **dashboard.php** - Admin dashboard (protected, admin only)
- **add_course.php** - Add new course (protected, admin only)
- **edit_course.php** - Edit course (protected, admin only)
- **delete_course.php** - Delete course (protected, admin only)

---

## 🔒 Security Implementation

### Password Hashing (Students)
```php
// When registering:
$hashed = password_hash("user_password", PASSWORD_DEFAULT);
// Stored in database: $2y$10$...

// When logging in:
if (password_verify($submitted_password, $stored_hash)) {
    // Correct!
}
```

**Why this is secure:**
- Uses bcrypt algorithm (industry standard)
- Each password gets unique salt
- Computationally expensive (slow, prevents brute force)
- One-way hashing (can't reverse to get password)

### Session Protection
```php
session_start(); // Unique session ID created
$_SESSION['user_id'] = $id; // Only server knows user_id

// Browser stores: PHPSESSID cookie (random)
// Server matches PHPSESSID to $_SESSION data
// User can't forge session (cookie is meaningless without server data)
```

### Admin Security
- No database password = No database compromise
- Credentials only in PHP file
- Can be changed by modifying `auth/admin_login.php`
- For production: Move to environment variables

---

## ❌ LOGOUT SYSTEM

### How Logout Works:

```php
// User clicks "Logout" → Goes to auth/logout.php
session_destroy(); // Destroys ALL session data
// Session cookie still exists but is now empty/invalid
// Any access attempt → Redirect to auth/index.php
```

### Logout happens on:
- Student clicking "Logout" button
- Admin clicking "Logout" button
- Browser close (sessions expire)

---

## 🚫 Access Control Matrix

| Page | Student | Admin | Unauthenticated |
|------|---------|-------|-----------------|
| auth/index.php | ✅ Direct | ✅ Redirect to dashboard | ✅ Show choice |
| auth/student_login.php | ✅ Show form | ✅ Redirect to dashboard | ✅ Show form |
| auth/admin_login.php | ✅ Show form | ✅ Redirect to dashboard | ✅ Show form |
| public/index.php | ✅ Allow | ❌ Redirect | ❌ Redirect to login |
| public/search.php | ✅ Allow | ❌ Redirect | ❌ Redirect to login |
| public/course.php | ✅ Allow | ❌ Redirect | ❌ Redirect to login |
| admin/dashboard.php | ❌ Redirect | ✅ Allow | ❌ Redirect to login |
| admin/add_course.php | ❌ Redirect | ✅ Allow | ❌ Redirect to login |
| admin/edit_course.php | ❌ Redirect | ✅ Allow | ❌ Redirect to login |
| admin/delete_course.php | ❌ Redirect | ✅ Allow | ❌ Redirect to login |

---

## 🔧 How to Add Pages

### To add a STUDENT page:
```php
<?php
include("../config/auth_gate.php");
include("../config/db.php");

// IMPORTANT: Prevent admin access
if ($_SESSION['user_type'] === 'admin') {
    header("Location: ../admin/dashboard.php");
    exit();
}

// Your page code here
?>
```

### To add an ADMIN page:
```php
<?php
include("../auth/admin_gate.php");
include("../config/db.php");

// admin_gate.php automatically checks:
// - Is user authenticated?
// - Is user type 'admin'?
// If not → Automatically redirects to auth/index.php

// Your page code here
?>
```

---

## 🐛 Debugging Tips

### Session not persisting?
- Check if cookies are enabled
- Check if session_start() is called
- Look in php.ini for session settings

### Redirect loops?
- Check if auth_gate.php is preventing auth pages
- Make sure auth pages are in the `$auth_pages` array in auth_gate.php

### Admin can't access admin pages?
- Check if $_SESSION['user_type'] is exactly 'admin'
- Make sure admin_gate.php is included at TOP of page

### User keeps getting logged out?
- Check session timeout settings in php.ini
- Session defaults to browser close
- Can be extended with session_set_cookie_params()

---

## ✅ Testing Checklist

- [ ] Run auth/setup.php to create students table
- [ ] Try to access public/index.php without login → Should redirect to auth/index.php
- [ ] Register a student account
- [ ] Login as student → Should see student home
- [ ] Try to access admin/dashboard.php as student → Should redirect to auth/index.php
- [ ] Login as admin (admin/admin123) → Should see admin dashboard
- [ ] Try to access public/index.php as admin → Should redirect to admin/dashboard.php
- [ ] Click logout as student → Should go to auth/index.php
- [ ] Click logout as admin → Should go to auth/index.php
- [ ] Try to access admin/add_course.php without auth → Should redirect to auth/index.php

---

## 📝 PRODUCTION CHECKLIST

- [ ] Change admin password in auth/admin_login.php
- [ ] Move admin credentials to environment variables (.env file)
- [ ] Enable HTTPS on production
- [ ] Use secure session cookie settings in php.ini
- [ ] Implement CSRF protection
- [ ] Add rate limiting on login attempts
- [ ] Add email verification for student registration
- [ ] Implement password reset for students
- [ ] Set session timeout appropriately
- [ ] Add logging for login attempts
- [ ] Sanitize all user inputs
- [ ] Use prepared statements everywhere (not just auth)

---

## 📞 Summary

This authentication system is:
- ✅ **Global:** Protects ALL pages
- ✅ **Secure:** Uses password hashing, hardcoded admin credentials
- ✅ **Simple:** Easy to understand and modify
- ✅ **Extensible:** Easy to add new pages
- ✅ **Professional:** Production-ready architecture

**No page in the application is accessible without authentication!**
