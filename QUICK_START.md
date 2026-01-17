# 🚀 Quick Start Guide - New Authentication System

## ⚡ 5-Minute Setup

### Step 1: Create Database Table (1 minute)
```
Visit: http://localhost/xampp/cours-app/auth/setup.php
```
✓ Creates the `students` table

### Step 2: Test the System (4 minutes)

**As a Student:**
1. Go to: `http://localhost/xampp/cours-app/`
2. Click "Étudiant" (Student)
3. Click "Register here"
4. Enter: Name, Email, Password
5. Auto-login → See student home page

**As Admin:**
1. Go to: `http://localhost/xampp/cours-app/`
2. Click "Professeur" (Professor)
3. Enter: `admin` / `admin123`
4. See admin dashboard

---

## 🔑 Default Credentials

### ADMIN (Hardcoded)
- **Username:** admin
- **Password:** admin123
- **Change in:** `auth/admin_login.php` (line ~28-29)

### STUDENT
- **No default account** → Must register
- All student accounts stored in database with **hashed passwords**

---

## 📋 What Changed

### ✅ NEW FILES CREATED:
```
auth/
├── index.php                 (Main choice page)
├── student_login.php         (Student login)
├── student_register.php      (Student registration)
├── admin_login.php          (Admin login - hardcoded creds)
├── admin_gate.php           (NEW: Protect admin pages)
├── logout.php               (Updated)
└── setup.php                (Updated: Create students table)

config/
└── auth_gate.php            (NEW: Global auth protection)

AUTHENTICATION_SYSTEM.md      (Complete documentation)
QUICK_START.md               (This file)
```

### ✅ UPDATED FILES:
```
public/index.php                (Added auth_gate, student info)
public/search.php               (Added auth_gate)
public/course.php               (Added auth_gate)
public/search_api.php           (Added auth_gate)

admin/dashboard.php             (Uses admin_gate now)
admin/add_course.php            (Uses admin_gate now)
admin/edit_course.php           (Uses admin_gate now)
admin/delete_course.php         (Uses admin_gate now)
```

### ❌ REMOVED:
- auth/check_auth.php (replaced by admin_gate.php)

---

## 🎯 Key Features

### 🔐 Security
- ✅ Global authentication gate on ALL pages
- ✅ Password hashing for students (bcrypt)
- ✅ Hardcoded admin credentials (not in database)
- ✅ Session-based access control
- ✅ Automatic redirects for unauthorized access

### 👥 Two User Types
- **Student (Étudiant):**
  - Can register with email/password
  - Can view and search courses
  - Can download PDFs
  - Cannot access admin pages

- **Admin (Professeur):**
  - Fixed credentials (admin/admin123)
  - Can manage courses (add/edit/delete)
  - Cannot access student pages
  - Dashboard with admin info

### 🚫 Access Control
| What | Without Auth | As Student | As Admin |
|------|------------|---------|------|
| Any page | → Login | ✅ | ✅ if allowed |
| Student pages | ❌ | ✅ | Redirect |
| Admin pages | ❌ | Redirect | ✅ |
| Logout | N/A | ✅ | ✅ |

---

## 🛠 How to Customize

### Change Admin Password
Edit `auth/admin_login.php` line ~28-29:
```php
const ADMIN_USERNAME = 'admin';      // Change this
const ADMIN_PASSWORD = 'admin123';   // Change this
```

### Add New Student Page
```php
<?php
include("../config/auth_gate.php");
include("../config/db.php");

if ($_SESSION['user_type'] === 'admin') {
    header("Location: ../admin/dashboard.php");
    exit();
}

// Your page code here
?>
```

### Add New Admin Page
```php
<?php
include("../auth/admin_gate.php");
include("../config/db.php");

// Your page code here
// admin_gate automatically protects this page!
?>
```

---

## 🐛 Troubleshooting

### "Not found" error on auth/index.php
- Make sure you visited: `http://localhost/xampp/cours-app/`
- Or: `http://localhost/xampp/cours-app/auth/index.php`

### Can't create student account
- Check if setup.php was run: `auth/setup.php`
- Check if `students` table exists in database

### Admin login doesn't work
- Credentials are: `admin` / `admin123`
- Make sure you're clicking "Professeur" (not Student)

### Keep getting logged out
- Browser closed → Session destroyed (normal)
- Sessions expire after 24 min of inactivity (can be configured)

### Student can access admin page?
- This shouldn't happen (auth_gate prevents it)
- Check if `include("../auth/admin_gate.php");` is at TOP of page
- Check if admin page has the include statement

---

## 📊 Database

### Students Table (Auto-created)
Columns:
- `id` - Auto-increment
- `name` - Student name
- `email` - Unique email
- `password` - Hashed password
- `created_at` - Registration time
- `updated_at` - Last update

### NO Admin Table
- Admin credentials are hardcoded in PHP
- Not vulnerable to database theft
- Stored in: `auth/admin_login.php`

---

## 🔄 Session Flow

```
USER VISIT
    ↓
auth_gate.php checks:
  Is user authenticated?
    ↓
  NO → Redirect to auth/index.php
  YES → Check user_type:
    ├─ student → Can access: public/* pages
    ├─ admin → Can access: admin/* pages
    └─ Invalid → Redirect to auth/index.php
    ↓
USER ACCESS GRANTED
```

---

## ✅ Test These Scenarios

1. **Open http://localhost/xampp/cours-app/**
   - Should see choice: Étudiant or Professeur

2. **Click Étudiant → Register**
   - Fill form → See student home

3. **Click Logout**
   - Should go back to choice page

4. **Try to manually visit admin/dashboard.php**
   - Should redirect to auth/index.php

5. **Login as admin (admin/admin123)**
   - Should see admin dashboard

6. **As admin, try to visit public/index.php**
   - Should redirect to admin/dashboard.php

7. **Close browser → Reopen application**
   - Should ask to login again (session destroyed)

---

## 📞 Support

For detailed information, read:
- **AUTHENTICATION_SYSTEM.md** - Complete technical documentation
- **auth_gate.php** - Global authentication logic
- **auth/admin_gate.php** - Admin access control

---

**✨ Your application is now FULLY PROTECTED! ✨**

No page can be accessed without authentication.
