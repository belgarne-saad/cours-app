# Authentication Setup Instructions

## What I've Done:

1. **Created Authentication System:**
   - Login page at `/auth/login.php`
   - Authentication checker at `/auth/check_auth.php`
   - Logout function at `/auth/logout.php`

2. **Protected Pages:**
   - Dashboard (`/admin/dashboard.php`)
   - Add Course (`/admin/add_course.php`)
   - Edit Course (`/admin/edit_course.php`)
   - Delete Course (`/admin/delete_course.php`)

3. **Changed Label:**
   - Changed "Upload" to "Dashboard" in navigation
   - Changed icon from 📤 to 📊

## FIRST TIME SETUP:

### Step 1: Run Setup Script
Navigate to: `http://localhost/xampp/cours-app/auth/setup.php`

This will:
- Create the `admins` table in your database
- Create a default admin account
- Default credentials: **admin** / **admin123**

### Step 2: Login
1. Click "Dashboard" link from home page
2. Enter username: **admin**
3. Enter password: **admin123**
4. You'll be redirected to the dashboard

### Step 3: Change Default Password (IMPORTANT!)
After first login, you should:
1. Go to the database
2. Find the `admins` table
3. Change the password from "admin123" to something secure

To set a new password safely:
```php
password_hash("your_new_password", PASSWORD_DEFAULT)
```

## How It Works:

- **Public pages** (home, search, view courses): Anyone can access
- **Admin pages** (dashboard, add course, edit course, delete course): Only logged-in admins can access
- **Login required**: Anyone trying to access admin pages without login will be redirected to login page
- **Logout**: Click the "Logout" link to logout and return to home page
- **Session-based**: Uses PHP sessions to maintain login state

## Security Notes:

- Passwords are hashed using PHP's `password_hash()` function
- Sessions expire when browser closes (can be configured in `check_auth.php`)
- All admin pages check for valid session before allowing access
- Can add more security features like rate limiting, email verification, etc.
