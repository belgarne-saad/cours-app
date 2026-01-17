# 🔐 Authentication System - Visual Architecture

## System Architecture Diagram

```
┌─────────────────────────────────────────────────────────────┐
│                   COURSES APPLICATION                       │
└─────────────────────────────────────────────────────────────┘
                           ↓
         ┌─────────────────────────────────────┐
         │  ALL PAGES include auth_gate.php    │
         │  (Global authentication check)      │
         └─────────────────────────────────────┘
                           ↓
         ┌─────────────────────────────────────┐
         │  Is user authenticated?             │
         └─────────────────────────────────────┘
              ↙                            ↖
           NO                              YES
            ↓                               ↓
    Redirect to              Check: $_SESSION['user_type']
    auth/index.php                      ↙        ↖
                                  'student'    'admin'
                                      ↓            ↓
                          Allow public/     Allow admin/
                          Allow public/     BLOCK public/
                          BLOCK admin/      ALLOW admin/
```

---

## Authentication Flow

### ➡️ First Time Visitor

```
Visit: http://localhost/xampp/cours-app/
         ↓
    auth_gate.php checks
    User logged in? NO
         ↓
    Redirect to: auth/index.php
         ↓
    ┌──────────────────────────┐
    │   Choice Page            │
    ├──────────────────────────┤
    │  👨‍🎓 Étudiant (Student)   │
    │                          │
    │  👨‍🏫 Professeur (Admin)   │
    └──────────────────────────┘
```

### 👨‍🎓 Student Path

```
Click: Étudiant
  ↓
┌─────────────────────────┐
│ 1. Have account?        │
├─────────────────────────┤
│ YES → Login             │
│ NO  → Register          │
└─────────────────────────┘
  ↓                    ↓
Login Page        Registration
  ↓                    ↓
Enter Email       Enter Name
Enter Password    Enter Email
  ↓               Enter Password
Verify Creds      Confirm Password
  ↓                    ↓
✅ Session          ✅ Session
Created           Created & DB
  ↓                    ↓
Redirect to: public/index.php
  ↓
┌──────────────────────────────┐
│  Student Home Page           │
│  ✅ View courses             │
│  ✅ Search courses           │
│  ✅ Download PDFs            │
│  ❌ Access admin dashboard   │
└──────────────────────────────┘
```

### 👨‍🏫 Admin Path

```
Click: Professeur
  ↓
┌─────────────────────────┐
│  Admin Login Page       │
├─────────────────────────┤
│  Username field         │
│  Password field         │
│  (Hardcoded check)      │
└─────────────────────────┘
  ↓
Enter: admin / admin123
  ↓
Check against hardcoded values
  ↓
Match?
  ├─ YES → ✅ Session created
  │        Redirect to: admin/dashboard.php
  │        ↓
  │        ┌──────────────────────────────┐
  │        │  Admin Dashboard             │
  │        │  ✅ Add courses              │
  │        │  ✅ Edit courses             │
  │        │  ✅ Delete courses           │
  │        │  ❌ Access student pages     │
  │        └──────────────────────────────┘
  │
  └─ NO  → ❌ Error: Invalid credentials
          Stay on login page
```

---

## Session Management

### Session Creation

```
User Successfully Authenticates
         ↓
    PHP: session_start()
         ↓
STUDENT:              ADMIN:
  ↓                    ↓
$_SESSION[            $_SESSION[
  'user_id'→1,        'user_id'→'admin',
  'user_type'→'student',  'user_type'→'admin',
  'user_name'→'John',     'user_name'→'Administrator',
  'user_email'→'john@',   'username'→'admin'
]                     ]
         ↓
PHPSESSID Cookie Set
(Browser stores cookie ID)
(Server stores session data)
```

### Session Verification (Every Page Load)

```
Page Load
   ↓
include("../config/auth_gate.php")
   ↓
Check: $_SESSION['user_id'] exists?
   ├─ YES → Check: $_SESSION['user_type']?
   │        ├─ 'student' → Allow if page is public/*
   │        ├─ 'admin'   → Allow if page is admin/*
   │        └─ ??? → REDIRECT (invalid)
   │
   └─ NO → REDIRECT to auth/index.php
```

### Session Destruction (Logout)

```
User clicks: Logout
   ↓
Go to: auth/logout.php
   ↓
session_destroy()
   ↓
$_SESSION = [] (all data cleared)
   ↓
PHPSESSID cookie becomes invalid
   ↓
Redirect to: auth/index.php
   ↓
Next page load:
auth_gate checks $_SESSION['user_id']
Not set → REDIRECT to login
```

---

## Database Architecture

### Students Table (MySQL)

```
CREATE TABLE students (
┌─────────────────────────────────────┐
│ Column          │ Type              │
├─────────────────────────────────────┤
│ id              │ INT AUTO_INCREMENT│  ← Primary Key
│ name            │ VARCHAR(100)      │  ← Student name
│ email           │ VARCHAR(100) UNQ  │  ← Unique, indexed
│ password        │ VARCHAR(255)      │  ← HASHED! Never plain text
│ created_at      │ TIMESTAMP         │  ← Auto-created
│ updated_at      │ TIMESTAMP         │  ← Auto-updated
└─────────────────────────────────────┘
);

Index on: email (for faster lookups)
```

### Admin Credentials (PHP File)

```
auth/admin_login.php (Line ~28-29)
┌──────────────────────────────────────┐
│ const ADMIN_USERNAME = 'admin';      │  ← In PHP file
│ const ADMIN_PASSWORD = 'admin123';   │  ← NOT in database
└──────────────────────────────────────┘

Why this is secure:
- SQL injection can't reveal it (not in DB)
- Database backup doesn't expose it
- File permissions protect it
```

---

## File Inclusion Hierarchy

### Every Page Gets Protected This Way:

```
public/index.php (Student page)
│
├─ include("../config/auth_gate.php")
│  ├─ Check: Logged in?
│  ├─ Check: Not on auth page?
│  ├─ If NO login → REDIRECT
│  └─ If logged in → CONTINUE
│
├─ Check: $_SESSION['user_type'] === 'admin'?
│  └─ If admin → REDIRECT to dashboard
│
├─ include("../config/db.php")
│  └─ Database connection
│
└─ Page code (now SAFE - definitely authenticated)
```

```
admin/dashboard.php (Admin page)
│
├─ include("../auth/admin_gate.php")
│  ├─ Check: Logged in?
│  ├─ Check: $_SESSION['user_type'] === 'admin'?
│  ├─ If NOT admin → REDIRECT
│  └─ If admin → CONTINUE
│
├─ include("../config/db.php")
│  └─ Database connection
│
└─ Page code (now SAFE - definitely admin)
```

---

## Access Control Matrix

```
                    PUBLIC PAGES        ADMIN PAGES        AUTH PAGES
                    (public/*)          (admin/*)          (auth/*)
┌───────────────┬──────────────────────────────────────────────────────┐
│ NO AUTH       │ REDIRECT            │ REDIRECT           │ ALLOW      │
│ (No Login)    │ → auth/index.php    │ → auth/index.php   │ Register   │
│               │                     │                    │ Login      │
├───────────────┼──────────────────────────────────────────────────────┤
│ STUDENT       │ ALLOW ✅            │ REDIRECT           │ REDIRECT   │
│ (Logged in)   │ View courses        │ → auth/index.php   │ → public/  │
│               │ Search              │                    │            │
├───────────────┼──────────────────────────────────────────────────────┤
│ ADMIN         │ REDIRECT            │ ALLOW ✅           │ REDIRECT   │
│ (Logged in)   │ → admin/dashboard   │ Manage courses     │ → admin/   │
│               │                     │                    │            │
└───────────────┴──────────────────────────────────────────────────────┘
```

---

## Security Layers

```
Layer 1: SESSION VALIDATION (auth_gate.php)
├─ Check if user has valid session
└─ Blocks: No auth users

Layer 2: USER TYPE CHECK (admin_gate.php)
├─ Check if user is 'admin' or 'student'
└─ Blocks: Wrong user type accessing page

Layer 3: PAGE CONTENT CHECK
├─ Student pages block admin access
├─ Admin pages already blocked by admin_gate
└─ Blocks: Cross-role access

Layer 4: PASSWORD HASHING
├─ Student passwords: bcrypt hashed
├─ Admin password: hardcoded, not in DB
└─ Blocks: Password theft, brute force
```

---

## Data Flow - Student Registration

```
User submits form with: name, email, password
         ↓
student_register.php receives POST
         ↓
Validate input:
├─ Fields not empty?
├─ Email format valid?
├─ Password >= 6 chars?
├─ Passwords match?
└─ Email not already in DB?
         ↓
Generate hashed password:
  password_hash("plain", PASSWORD_DEFAULT)
         ↓
Insert into students table:
  INSERT INTO students (name, email, password)
  VALUES ('John', 'john@email', '$2y$10$...')
         ↓
Create session:
  $_SESSION['user_id'] = new_id
  $_SESSION['user_type'] = 'student'
  $_SESSION['user_name'] = 'John'
  $_SESSION['user_email'] = 'john@email'
         ↓
Redirect to: public/index.php
         ↓
auth_gate checks → OK, user authenticated
         ↓
Student home page loads ✅
```

---

## Data Flow - Admin Login

```
User submits form with: username, password
         ↓
admin_login.php receives POST
         ↓
Compare against hardcoded values:
  if ('admin' === ADMIN_USERNAME &&
      'admin123' === ADMIN_PASSWORD)
         ↓
Create session:
  $_SESSION['user_id'] = 'admin'
  $_SESSION['user_type'] = 'admin'
  $_SESSION['user_name'] = 'Administrator'
  $_SESSION['username'] = 'admin'
         ↓
Redirect to: admin/dashboard.php
         ↓
admin_gate checks → OK, is admin
         ↓
Admin dashboard loads ✅
```

---

## Error Scenarios

```
Scenario 1: Unauthenticated access attempt
────────────────────────────────────────
User: http://localhost/.../public/index.php
  ↓
auth_gate.php runs
  ↓
$_SESSION['user_id'] NOT set
  ↓
REDIRECT to: auth/index.php
Result: ✅ Blocked

Scenario 2: Student accessing admin page
─────────────────────────────────────────
User (student) visits: admin/dashboard.php
  ↓
admin_gate.php runs
  ↓
$_SESSION['user_type'] === 'student'
(not 'admin')
  ↓
REDIRECT to: auth/index.php
Result: ✅ Blocked

Scenario 3: Wrong admin password
────────────────────────────────
User enters: admin / wrongpassword
  ↓
admin_login.php compares
  ↓
'wrongpassword' !== ADMIN_PASSWORD
  ↓
Set error message
  ↓
Stay on login page
Result: ✅ Access denied
```

---

## File Dependencies

```
config/auth_gate.php (CRITICAL - used everywhere)
  └─ Used by: public/*, admin/* (all pages)
  └─ Must be: First include in each page

auth/admin_gate.php (ADMIN PAGES ONLY)
  └─ Used by: admin/*
  └─ Must be: First include in admin pages

config/db.php (DATABASE)
  └─ Used by: public/*, admin/*, auth/*
  └─ Must be: After auth gates

Each file is independent:
- Can be updated without affecting others
- Can be tested separately
- Can be reused in new pages
```

---

**This architecture ensures complete security while maintaining simplicity and clarity.**
