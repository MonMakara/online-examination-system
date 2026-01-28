# Demo Troubleshooting Guide

**Quick fixes for common issues that might arise during your demo**

---

## 🚨 Critical Issues

### **Issue 1: Laravel Server Won't Start**

**Error:**

```
Port 8000 already in use
```

**Solution:**

```bash
# Find and kill process using port 8000
sudo lsof -ti:8000 | xargs kill -9

# Or use different port
php artisan serve --host=0.0.0.0 --port=8001

# Update browser to http://localhost:8001
```

---

### **Issue 2: Database Connection Failed**

**Error:**

```
SQLSTATE[HY000] [2002] No such file or directory
```

**Solution:**

```bash
# Start LAMPP MySQL
sudo /opt/lampp/lampp start

# Or restart it
sudo /opt/lampp/lampp restart

# Verify database exists
mysql -u root -e "SHOW DATABASES LIKE 'project-final-2';"

# If database doesn't exist, create it
mysql -u root -e "CREATE DATABASE \`project-final-2\`;"

# Run migrations
php artisan migrate
```

---

### **Issue 3: Login Not Working**

**Error:**

```
Invalid credentials or user not found
```

**Solution:**

```bash
# Check if users exist
php artisan tinker
User::count();  # Should return > 0

# If no users, seed the database
exit
php artisan db:seed

# If that fails, create admin manually
php artisan tinker
User::create([
  'name' => 'Admin User',
  'email' => 'admin@example.com',
  'password' => bcrypt('password123'),
  'role' => 'admin'
]);
exit
```

---

### **Issue 4: 500 Internal Server Error**

**Solution:**

```bash
# Check error logs
tail -f storage/logs/laravel.log

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Verify .env file exists
ls -la .env

# Check file permissions
chmod 775 storage
chmod 775 bootstrap/cache

# Restart server
php artisan serve --host=0.0.0.0 --port=8000
```

---

## 🎨 UI/UX Issues

### **Issue 5: Styles Not Loading (CSS Broken)**

**Symptoms:**

- Page looks plain without colors
- Buttons don't align properly
- Responsive layout broken

**Solution:**

```bash
# Option 1: Rebuild CSS
npm run build

# Option 2: Restart Vite dev server
# Stop: Ctrl+C in Vite terminal
npm run dev

# Option 3: Hard refresh browser
# Chrome/Firefox/Edge: Ctrl+Shift+R
# Safari: Cmd+Shift+R

# Option 4: Clear browser cache
# Chrome: Ctrl+Shift+Delete
```

---

### **Issue 6: JavaScript Not Working (Alpine.js)**

**Symptoms:**

- Dropdowns don't work
- Modals don't appear
- Confirmation buttons don't respond

**Solution:**

```bash
# Restart Vite
npm run dev

# Check browser console for errors
# F12 > Console tab

# Clear browser cache and refresh
Ctrl+Shift+Delete (or Cmd+Shift+Delete on Mac)
```

---

### **Issue 7: Images Not Loading**

**Error:**

```
Failed to load resource: the server responded with a 404 (Not Found)
```

**Solution:**

```bash
# Create storage link if missing
php artisan storage:link

# If link exists, remove and recreate
rm public/storage
php artisan storage:link

# Check file permissions
chmod -R 775 storage/app

# Verify public/storage exists
ls -la public/storage
```

---

## 🔐 Authentication Issues

### **Issue 8: Session Expires Too Quickly**

**Solution:**

```bash
# Increase session lifetime in .env
SESSION_LIFETIME=1440  # 24 hours

# Clear sessions
php artisan cache:clear
```

---

### **Issue 9: CSRF Token Mismatch**

**Error:**

```
CSRF token mismatch
```

**Solution:**

```bash
# Clear session
php artisan session:clear

# Verify CSRF middleware in routes
# Routes should be in 'web' middleware group
```

---

### **Issue 10: Login Loops (Can't Stay Logged In)**

**Solution:**

```bash
# Clear all sessions
php artisan session:clear

# Verify SESSION_DRIVER in .env
SESSION_DRIVER=file

# Create session directory
mkdir -p storage/framework/sessions
chmod 775 storage/framework/sessions

# Restart Laravel
php artisan serve
```

---

## 📊 Data Issues

### **Issue 11: No Data Showing Up**

**Solution:**

```bash
# Verify migrations ran
php artisan migrate:status

# All migrations should show [X] (applied)

# If not, run migrations
php artisan migrate

# Seed demo data
php artisan db:seed --class=DatabaseSeeder

# Verify data exists
php artisan tinker
User::count();
ClassRoom::count();
Exam::count();
```

---

### **Issue 12: Incorrect Data Relationships**

**Example:**

- Exams not showing for classes
- Students not appearing in class
- Results missing

**Solution:**

```bash
# Check database schema
php artisan tinker

# Verify relationships
Teacher = User::where('role', 'admin')->first();
Teacher->classRooms;  # Should show classes

# If empty, manually create test data
ClassRoom::create([
  'name' => 'Test Class',
  'code' => 'TEST-001',
  'teacher_id' => 1
]);
exit
```

---

### **Issue 13: Exam Not Showing as "Available"**

**Reason:**

- Exam date not within current window
- Class doesn't have students

**Solution:**

```bash
# Update exam dates to be available now
php artisan tinker
exam = Exam::first();
exam->update([
  'due_at' => now(),
  'closed_at' => now()->addHours(24)
]);
exit

# Or via SQL
mysql -u root project-final-2
UPDATE exams SET due_at = NOW(), closed_at = DATE_ADD(NOW(), INTERVAL 24 HOUR);
exit
```

---

## 🌐 Network/Performance Issues

### **Issue 14: Website Loads Slowly**

**Solution:**

```bash
# Check if you're in development mode
# .env should have: APP_DEBUG=true

# If too many requests, clear caches
php artisan cache:clear

# Verify database is running locally
mysql -u root -e "SELECT 1;"

# Check active PHP processes
ps aux | grep php
```

---

### **Issue 15: Assets Not Found (404 on CSS/JS)**

**Solution:**

```bash
# Verify Vite dev server is running
# Should see "VITE vX.X.X ready"

# If not, start it
npm run dev

# Check browser console (F12)
# Look for 404 errors

# Clear browser cache
Ctrl+Shift+Delete
```

---

## 📧 Email Issues

### **Issue 16: OTP Email Not Sending**

**Solution:**

```bash
# Verify email credentials in .env
# MAIL_MAILER=smtp
# MAIL_HOST=smtp.gmail.com
# MAIL_PORT=465
# MAIL_USERNAME=your_email@gmail.com
# MAIL_PASSWORD=your_app_password

# Test email sending
php artisan tinker
Mail::raw('Test email', function($message) {
  $message->to('test@example.com');
});
exit

# If error, credentials are wrong
# For Gmail, use app password (not regular password)
# Generate at: myaccount.google.com/apppasswords
```

---

## 🎬 Display/Screen Issues

### **Issue 17: Font/Text Too Small**

**Solution:**

```bash
# Browser zoom: Ctrl++ to increase
# Or zoom to 125%/150%

# CSS/Tailwind might need adjustment
# In dev mode, check tailwind.config.js
```

---

### **Issue 18: Responsive Layout Broken**

**Solution:**

```bash
# Verify Tailwind CSS is compiled
npm run build

# If demo on mobile/tablet:
# Make sure viewport meta tag exists in layout

# Restart Vite
npm run dev
```

---

### **Issue 19: Modal/Popup Not Closing**

**Solution:**

```bash
# Refresh page
F5

# Check browser console for JavaScript errors
F12 > Console

# Restart Vite
npm run dev
```

---

## 💾 File/Permission Issues

### **Issue 20: Permission Denied Errors**

**Solution:**

```bash
# Fix Laravel directories
sudo chown -R $USER:$USER /home/mark/Documents/projects/project-final\ \(Copy\)
chmod -R 755 /home/mark/Documents/projects/project-final\ \(Copy\)

# Fix storage specifically
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Fix public directory
chmod -R 755 public
```

---

### **Issue 21: .env File Not Found**

**Solution:**

```bash
# Create .env from example
cp .env.example .env

# Generate app key
php artisan key:generate

# Verify .env has database credentials
cat .env | grep DB_

# Should show your database details
```

---

## 🔄 Last Resort Solutions

### **Nuclear Option 1: Complete Fresh Start**

**Do this ONLY if everything fails:**

```bash
# 1. Clear everything
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan migrate:fresh --seed

# 2. Rebuild assets
npm run build

# 3. Restart server
php artisan serve --host=0.0.0.0 --port=8000
```

---

### **Nuclear Option 2: Fresh Database**

**WARNING: This deletes all data!**

```bash
# Backup current data first (optional)
mysqldump -u root project-final-2 > backup.sql

# Reset database
php artisan migrate:fresh

# Seed fresh data
php artisan db:seed

# Verify
php artisan tinker
User::count();  # Should be > 0
```

---

### **Nuclear Option 3: Use Live Demo Instead**

**If local demo absolutely fails:**

```
URL: https://online-examination-system-na0j.onrender.com
Note: May have 1-2 minute cold start
Credentials: Same as local
```

---

## ✅ Quick Health Check Script

Create this file: `health-check.sh`

```bash
#!/bin/bash

echo "🔍 Demo Health Check..."
echo ""

echo "✓ Checking LAMPP..."
sudo /opt/lampp/lampp status

echo ""
echo "✓ Checking Laravel..."
php artisan --version

echo ""
echo "✓ Checking Database..."
mysql -u root -e "SHOW DATABASES LIKE 'project-final-2';"

echo ""
echo "✓ Checking Migration Status..."
php artisan migrate:status | head -5

echo ""
echo "✓ Checking User Count..."
php artisan tinker -e "echo User::count() . ' users found';"

echo ""
echo "✓ All checks complete!"
```

Run it:

```bash
chmod +x health-check.sh
./health-check.sh
```

---

## 📞 Final Fallback: Reset Everything

**If all else fails (1 minute before demo):**

```bash
# 1. Kill all processes
sudo killall -9 php
sudo killall -9 npm

# 2. Restart LAMPP
sudo /opt/lampp/lampp stop
sudo /opt/lampp/lampp start

# 3. Start fresh
cd /home/mark/Documents/projects/project-final\ \(Copy\)
php artisan serve &
npm run dev &

# 4. Open browser
# http://localhost:8000

# Done!
```

---

## 🎯 Common Demo Mistakes to Avoid

1. ❌ **Don't refresh during login** - Wait for redirect
2. ❌ **Don't hit back button** - Use navigation menu
3. ❌ **Don't open console** - Closes your view
4. ❌ **Don't type too fast** - Let forms respond
5. ❌ **Don't change data mid-demo** - Confuses demo flow
6. ❌ **Don't open multiple tabs** - Causes session issues
7. ❌ **Don't forget to save changes** - Click submit/save button

---

**Remember: Stay calm! Most issues are easily fixed. You've got backup plans! 💪**
