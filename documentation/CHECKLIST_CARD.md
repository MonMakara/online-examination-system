# 🎯 DEMO CHECKLIST CARD - Print This!

**Project**: Online Examination System  
**Date**: Tomorrow (January 29, 2026)  
**Duration**: 25 minutes

---

## ✅ MORNING SETUP (30 min before demo)

### **Terminal 1: Start Services**

```
sudo /opt/lampp/lampp start
```

### **Terminal 2: Laravel Server**

```
cd /home/mark/Documents/projects/project-final\ \(Copy\)
php artisan serve --host=0.0.0.0 --port=8000
```

### **Terminal 3: Vite Assets**

```
npm run dev
```

### **Browser Health Check**

- [ ] http://localhost:8000 loads
- [ ] Admin login works
- [ ] Styles loaded (CSS colors visible)
- [ ] No console errors (F12)

### **Database Check**

```
php artisan migrate:status  (all should show [X])
php artisan tinker
User::count();  (should be > 0)
exit
```

---

## 🔐 USER CREDENTIALS

```
┌─────────────────────────────────────────┐
│ ADMIN LOGIN                             │
├─────────────────────────────────────────┤
│ Email:    admin@example.com             │
│ Password: password123                   │
│ Access:   Full system control           │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│ TEACHER LOGIN                           │
├─────────────────────────────────────────┤
│ Email:    teacher@example.com           │
│ Password: password123                   │
│ Access:   Classes, Exams, Questions     │
└─────────────────────────────────────────┘

┌─────────────────────────────────────────┐
│ STUDENT LOGIN                           │
├─────────────────────────────────────────┤
│ Email:    student@example.com           │
│ Password: password123                   │
│ Access:   Take exams, View results      │
└─────────────────────────────────────────┘
```

---

## 📊 DEMO FLOW (25 Minutes)

### **PART 1: Overview (2 min)**

- [ ] Show landing page
- [ ] Highlight features list
- [ ] Mention tech stack

### **PART 2: Authentication (3 min)**

- [ ] Show login page
- [ ] Demo login process
- [ ] Show OTP flow
- [ ] Logout

### **PART 3: ADMIN (5 min)**

- [ ] LOGIN: admin@example.com
- [ ] Dashboard → Show stats
- [ ] Teachers → Create/Edit/Delete
- [ ] Classes → Create/Show
- [ ] Students → Search and show details

### **PART 4: TEACHER (5 min)**

- [ ] LOGOUT and LOGIN: teacher@example.com
- [ ] Dashboard → Show stats
- [ ] Create NEW exam
- [ ] Add 3-4 questions
- [ ] Show grading view

### **PART 5: STUDENT (5 min)**

- [ ] LOGOUT and LOGIN: student@example.com
- [ ] Dashboard → Show enrolled classes
- [ ] View available exam
- [ ] **START EXAM** (most important!)
- [ ] Answer questions
- [ ] Submit exam
- [ ] View results

### **PART 6: Features (2 min)**

- [ ] Show responsive design
- [ ] Highlight modals/confirmations
- [ ] Show search functionality

---

## 🚨 QUICK FIXES

| Problem         | Fix                                   |
| --------------- | ------------------------------------- |
| Page won't load | `sudo lsof -ti:8000 \| xargs kill -9` |
| No styles       | Stop npm, run `npm run dev`           |
| No data         | `php artisan db:seed`                 |
| Login fails     | `php artisan tinker` then create user |
| Database error  | `sudo /opt/lampp/lampp restart`       |

---

## 💾 BACKUP EMERGENCY COMMANDS

```bash
# Kill everything and restart
sudo killall -9 php php-fpm node npm
sudo /opt/lampp/lampp stop
sudo /opt/lampp/lampp start

# Fresh database (DEMO ONLY!)
php artisan migrate:fresh --seed

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
```

---

## ⏱️ TIMELINE

```
0:00 - 2:00   System Overview
2:00 - 5:00   Authentication Flow
5:00 - 10:00  Admin Portal
10:00 - 15:00 Teacher Portal
15:00 - 20:00 Student Portal
20:00 - 25:00 Key Features + Q&A
```

---

## 📱 DEVICE PREP

- [ ] Laptop battery charged
- [ ] Phone on silent
- [ ] WiFi connected / hotspot ready
- [ ] Close unnecessary apps
- [ ] Set resolution to 1920x1080
- [ ] Zoom at 100%
- [ ] Disable screensaver
- [ ] Disable notifications

---

## 📞 HAVE THESE READY

- [ ] `TROUBLESHOOTING.md` (open on phone)
- [ ] `DEMO_PREPARATION.md` (full guide)
- [ ] Render backup URL
- [ ] This checklist (printed)
- [ ] Demo user credentials (written)

---

## 🎯 REMEMBER

✅ Everything is set up and working  
✅ You have detailed documentation  
✅ You have troubleshooting solutions  
✅ You have backup plans ready

**Stay calm. You've got this! 🚀**

---

## ✨ FINAL WORDS

- **Speak clearly** - Explain what you're doing
- **Go slowly** - Let features sink in
- **Be confident** - You built this!
- **Answer questions** - You know your project
- **Smile** - You're proud of this work!

---

**Good luck tomorrow! You're going to CRUSH IT! 💪**

_Print this page and keep it with you during the demo!_
