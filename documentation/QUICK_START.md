# Quick Start - 30 Minutes Before Demo

**Time**: 30 minutes  
**Goal**: Ensure everything is working perfectly

---

## ⚡ 5-Minute Emergency Setup

### **Terminal 1: Start Services**

```bash
# Start LAMPP
sudo /opt/lampp/lampp start

# Wait for confirmation that all services started
# You should see: MySQL started, Apache started
```

### **Terminal 2: Laravel Server**

```bash
cd /home/mark/Documents/projects/project-final\ \(Copy\)
php artisan serve --host=0.0.0.0 --port=8000

# Should show: Laravel development server started: http://127.0.0.1:8000
```

### **Terminal 3: Vite Assets**

```bash
cd /home/mark/Documents/projects/project-final\ \(Copy\)
npm run dev

# Should show: VITE v4.0.0 ready in XXX ms
```

---

## ✅ 5-Minute Health Check

### **Check 1: Database Connection**

```bash
# In a new terminal
cd /home/mark/Documents/projects/project-final\ \(Copy\)
php artisan tinker
# Type: DB::connection()->getPdo();
# Should return: true (no error)
# Type: exit
```

### **Check 2: Application Access**

```bash
# Open browser and go to:
http://localhost:8000

# Should see: Login page with branding
# No 500 errors, no white screen
```

### **Check 3: Clear Caches**

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Should show: Application cache cleared
```

---

## 🔐 Demo User Credentials

### **Copy These for Easy Reference:**

**Admin Dashboard**

```
URL: http://localhost:8000/admin/dashboard
Email: admin@example.com
Password: password123
```

**Teacher Dashboard**

```
URL: http://localhost:8000/teacher/dashboard
Email: teacher@example.com
Password: password123
```

**Student Dashboard**

```
URL: http://localhost:8000/student/dashboard
Email: student@example.com
Password: password123
```

---

## 🎯 Critical Demo Sequence

**NEVER SKIP THIS ORDER:**

1. ✅ Login as Admin
    - Show Dashboard stats
    - Show Teacher list (Create one if empty)
    - Show Class list (Create one if empty)
2. ✅ Login as Teacher
    - Show Exam list
    - Create a NEW exam (or use existing)
    - Add 3-4 questions to the exam
3. ✅ Login as Student
    - Join a class (or show already enrolled)
    - View available exam
    - **START EXAM** (most important part)
    - Answer questions
    - Submit exam
    - View results

---

## 🆘 If Something Breaks (Solutions)

### **"Page not loading"**

```bash
# Solution 1: Restart Laravel
# Stop: Ctrl+C in Terminal 2
php artisan serve --host=0.0.0.0 --port=8000

# Solution 2: Kill and restart
sudo lsof -ti:8000 | xargs kill -9
php artisan serve --host=0.0.0.0 --port=8000
```

### **"Database error"**

```bash
# Solution: Check and restart MySQL
sudo /opt/lampp/lampp restart

# Then verify connection
php artisan tinker
DB::connection()->getPdo();
```

### **"Styles/CSS not loading"**

```bash
# Solution 1: Rebuild assets
npm run build

# Solution 2: Stop and restart Vite
# Stop: Ctrl+C in Terminal 3
npm run dev

# Solution 3: Hard refresh browser
# Chrome: Ctrl+Shift+R
# Firefox: Ctrl+Shift+R
# Safari: Cmd+Shift+R
```

### **"Login not working"**

```bash
# Solution: Ensure users exist
php artisan tinker
User::where('email', 'admin@example.com')->first();
# If null, seed data
exit
php artisan db:seed

# Or manually create in MySQL
# mysql -u root project-final-2
# INSERT INTO users (...) VALUES (...);
```

### **"File upload not working"**

```bash
# Check storage link
php artisan storage:link

# If error, remove and recreate
rm public/storage
php artisan storage:link
```

---

## 📱 Device Prep

### **Before Going Live:**

- [ ] Charge laptop battery (100%)
- [ ] Close all unnecessary applications
- [ ] Disable notifications (Win+A, then turn off notifications)
- [ ] Set display to 100% zoom (not 125% or 150%)
- [ ] Set resolution to 1920x1080 or higher
- [ ] Close all tabs except demo tabs
- [ ] Have browser console closed (F12)
- [ ] Test microphone/speakers
- [ ] Disable screensaver
- [ ] Disable Wi-Fi if using hotspot

---

## 🎬 Pre-Demo Screen Setup

### **Open These Tabs (in order):**

**Tab 1:** http://localhost:8000 (Login page)  
**Tab 2:** Admin Dashboard (pre-logged in)  
**Tab 3:** Teacher Dashboard (pre-logged in)  
**Tab 4:** Student Dashboard (pre-logged in)

---

## ⏱️ 5-Minute Mark: Verification

**STOP HERE. Answer all YES:**

- [ ] Laravel server is running (check Terminal 2)
- [ ] Vite dev server is running (check Terminal 3)
- [ ] http://localhost:8000 loads without errors
- [ ] Admin account logs in successfully
- [ ] Database has sample data (teachers, classes, exams)
- [ ] All browsers tabs open correctly
- [ ] Network is stable (WiFi signal strong)
- [ ] Screen brightness is good
- [ ] Zoom level is readable (not too small)

**If ANY is NO:** Stop and fix it before proceeding!

---

## 🎭 Final 2 Minutes: Mental Prep

### **Confidence Boosters:**

1. **You've tested everything** - System is working
2. **You have a script** - Know what to say
3. **You have backup plans** - Solutions ready
4. **It's a demo, not perfection** - Minor glitches are normal
5. **You built this** - You know it better than anyone

### **Remember:**

- Speak clearly and slowly
- Pause between demonstrations
- Watch their reactions
- Answer questions positively
- Stay confident!

---

## 🎬 Show Time Checklist

**5 minutes before demo starts:**

- [ ] All three terminals open and running
- [ ] All browser tabs ready
- [ ] Credentials written/memorized
- [ ] Screen sharing enabled (if virtual)
- [ ] Audio/video tested
- [ ] Troubleshooting cheat sheet ready
- [ ] Backup demo link ready
- [ ] Smile and take a deep breath! 😊

---

**YOU'VE GOT THIS! 🚀**
