# 📚 Student Academy Portal - Update Guide

## For Novice Users: How to Update Your Installation

Last Updated: January 26, 2026

---

## 🎯 Quick Summary

This guide covers **all methods** to update your Student Academy Portal to the latest version:
- **Option 1: Zip File Download** (Easiest - Recommended for beginners)
- **Option 2: Git with GitHub Desktop** (Medium difficulty)
- **Option 3: Git Command Line** (Advanced)

Choose the method that best matches your comfort level.

---

## 📋 Prerequisites

Before updating, make sure you have:
- **XAMPP** or **Docker** installed (depending on your setup)
- Your database backed up (optional but recommended)
- About 5-10 minutes of free time

---

## ⚡ OPTION 1: Update Using ZIP File (EASIEST)

### Best For:
- Complete beginners
- Quick updates
- Windows/Mac/Linux users

### Step-by-Step Instructions:

#### 1️⃣ Download the Latest ZIP File
1. Go to: https://github.com/element-bendr/studentsproject
2. Click the green **"Code"** button
3. Click **"Download ZIP"**
4. Wait for the download to complete
5. The file will be named: `studentsproject-main.zip`

#### 2️⃣ Stop Your Server

**If using XAMPP:**
- Open XAMPP Control Panel
- Click **"Stop"** for Apache
- Click **"Stop"** for MySQL
- Wait 5 seconds

**If using Docker:**
- Open Terminal/Command Prompt
- Navigate to your project folder
- Run: `docker compose down`

#### 3️⃣ Backup Your Current Code (Optional but Recommended)

1. Navigate to your project folder (e.g., `C:\xampp\htdocs\` or `/opt/lampp/htdocs/`)
2. Right-click the `studentsproject` folder
3. Select **"Copy"**
4. Create a backup folder (e.g., `studentsproject_backup_jan2026`)
5. Paste the folder there

**This creates a safety net in case something goes wrong!**

#### 4️⃣ Extract the ZIP File

1. Extract the `studentsproject-main.zip` file to your Desktop or Downloads
2. Inside, you'll find a folder named `studentsproject-main`
3. Right-click it and select **"Copy"**

#### 5️⃣ Replace Your Existing Installation

**Option A: Complete Replacement (Cleanest)**
1. Navigate to your project folder (e.g., `C:\xampp\htdocs\`)
2. Delete the old `studentsproject` folder completely
3. Paste the extracted `studentsproject-main` folder
4. Rename `studentsproject-main` to `studentsproject`

**Option B: Selective Update (Keep Custom Files)**
1. Open the extracted `studentsproject-main` folder
2. Open your existing `studentsproject` folder (in another window)
3. Copy individual files/folders from new version:
   - Copy all files from `public/` (except `index.html`)
   - Copy all files from `admin/`
   - Copy all files from `student/`
   - Copy all files from `includes/`
   - Copy all files from `assets/`
   - Copy `schema.sql`
4. Keep your existing `storage/` folder (user uploads)
5. Keep your existing `config.php` with your settings

#### 6️⃣ Start Your Server

**If using XAMPP:**
- Open XAMPP Control Panel
- Click **"Start"** for Apache
- Click **"Start"** for MySQL
- Wait until both show "Running" in green

**If using Docker:**
- Open Terminal/Command Prompt
- Navigate to your project folder
- Run: `docker compose up --build -d`

#### 7️⃣ Verify the Update

1. Open your browser
2. Go to: `http://localhost:8080/` (Docker) or `http://localhost/studentsproject/public/` (XAMPP)
3. Check that the homepage loads
4. Login as admin to verify everything works

**✅ You're done!** Your installation is updated.

---

## 🖥️ OPTION 2: Update Using GitHub Desktop (MEDIUM)

### Best For:
- Users comfortable with graphical interfaces
- Those who want easy future updates

### Prerequisites:
- Download **GitHub Desktop**: https://desktop.github.com/

### Step-by-Step Instructions:

#### 1️⃣ Install GitHub Desktop
1. Download and install GitHub Desktop
2. Open the application
3. Sign in with your GitHub account (if you have one)

#### 2️⃣ Clone the Repository

1. Open GitHub Desktop
2. Click **File** → **Clone Repository**
3. Enter the URL: `https://github.com/element-bendr/studentsproject`
4. Choose where to save (e.g., Desktop)
5. Click **"Clone"**
6. Wait for the download (2-3 minutes)

#### 3️⃣ Update to Latest Version

1. Open GitHub Desktop
2. Make sure `studentsproject` is selected (left panel)
3. Click **"Current Branch"** at the top
4. Click **"Fetch origin"** button
5. Click **"Pull origin"** to download latest changes
6. Wait for completion

#### 4️⃣ Move to Your Server

1. Navigate to the cloned folder on your Desktop
2. Copy the `studentsproject` folder
3. Navigate to your server folder (e.g., `C:\xampp\htdocs\`)
4. Delete old `studentsproject` folder
5. Paste the new `studentsproject` folder

#### 5️⃣ Restart Server & Test

Follow Step 6 and 7 from **OPTION 1** above.

---

## 💻 OPTION 3: Update Using Command Line (ADVANCED)

### Best For:
- Advanced users
- Linux/Mac users
- Those familiar with Git

### Prerequisites:
- Git installed on your computer
- Terminal/Command Prompt knowledge

### Step-by-Step Instructions:

#### Method A: Update Existing Clone

If you already have the project:

```bash
# Navigate to your project folder
cd /path/to/studentsproject

# Fetch latest changes
git fetch origin

# Update to latest version
git pull origin main

# Verify you have the latest code
git log -1 --oneline
```

#### Method B: Fresh Clone

If you don't have the project yet:

```bash
# Navigate to where you want the project
cd /opt/lampp/htdocs/  # XAMPP Linux/Mac
# or
cd C:\xampp\htdocs     # XAMPP Windows

# Clone the repository
git clone https://github.com/element-bendr/studentsproject.git

# Navigate into the project
cd studentsproject

# Verify the clone
git log -1 --oneline
```

---

## 🐳 Docker-Specific Update Steps

If you're using Docker, follow these additional steps:

```bash
# Navigate to your project folder
cd /path/to/studentsproject

# Stop existing containers
docker compose down

# Pull latest code (if using git)
git pull origin main

# Rebuild and start containers
docker compose up --build -d

# Wait 10 seconds for startup
# Then test at http://localhost:8080
```

---

## 📊 What Gets Updated?

### Files that will be updated:
✅ All PHP files in `/public/`, `/admin/`, `/student/`
✅ All include files in `/includes/`
✅ All CSS/JavaScript in `/assets/`
✅ Documentation files (README, SECURITY, etc.)
✅ Docker configuration (if applicable)
✅ Database schema (`schema.sql`)

### Files that should NOT be updated:
⚠️ `.env` file (your settings)
⚠️ `storage/` folder (user uploads)
⚠️ `/uploads/` folder (if exists)
⚠️ Database contents (not in code)

**Always backup these before updating!**

---

## 🆘 Troubleshooting

### Problem: "Fatal error: Database connection failed"
**Solution:**
- Check that MySQL is running
- Verify database credentials in `includes/config.php`
- Make sure database exists: `student_academy`

### Problem: "404 Not Found" on admin pages
**Solution:**
- Clear your browser cache (Ctrl+Shift+Delete)
- Restart Apache/Docker
- Verify all PHP files were copied correctly

### Problem: "Cannot read property 'X' of undefined" in console
**Solution:**
- Check that `/assets/js/app.js` was updated
- Clear browser cache
- Hard refresh page (Ctrl+F5)

### Problem: "Login not working after update"
**Solution:**
- Check that `/includes/auth.php` was updated
- Verify database has admin user: `admin@example.com`
- Try resetting password by updating database directly

### Problem: "Old features still showing"
**Solution:**
- Clear browser cache completely
- Restart your server
- Check that all files in `/public/` were updated

---

## ✅ Verification Checklist

After updating, verify everything works:

- [ ] Homepage loads at `http://localhost:8080/`
- [ ] Admin login works (`admin@example.com` / `Admin@123`)
- [ ] Student login works (`test.student@example.com` / `Admin@123`)
- [ ] Admin can view Students page (`/admin/users.php`)
- [ ] Admin can add new students (`/admin/add_student.php`)
- [ ] New student can login with their credentials
- [ ] Logout works and returns to home page
- [ ] Contact form works on public pages
- [ ] No error messages in browser console (F12)

---

## 🔄 Keeping Your Installation Updated

### Automatic Checks (Once a Month)
1. Go to: https://github.com/element-bendr/studentsproject
2. Check if there's a "Compare" indicator
3. If there are new changes, follow the update steps above

### Update Frequency
- **Security updates**: Do immediately
- **Feature updates**: Do within 1 week
- **Bug fixes**: Do as soon as convenient

---

## 📞 Need Help?

If something goes wrong:

1. **Check the logs:**
   - XAMPP: Look in `apache/logs/` folder
   - Docker: Run `docker compose logs`

2. **Re-read the error message** carefully - it often tells you what's wrong

3. **Try the backup:** If you backed up your old version, you can restore it

4. **Start fresh:** Delete everything and follow Option 1 step-by-step again

5. **Ask for help:**
   - Post on GitHub Issues: https://github.com/element-bendr/studentsproject/issues
   - Include error messages and your setup (XAMPP or Docker)

---

## 🎓 Quick Reference

### Common Commands

**Docker Users:**
```bash
docker compose up --build -d      # Start
docker compose down               # Stop
docker compose logs               # View logs
docker compose restart            # Restart
```

**XAMPP Users:**
- Start Apache + MySQL via Control Panel
- Place project in `htdocs/` folder
- Access via `http://localhost/projectname/public/`

**Git Users:**
```bash
git pull origin main              # Update
git log -1                        # See latest commit
git status                        # Check status
```

---

## 📝 Version History

**Current Version:** January 26, 2026

### Recent Updates:
- ✅ Fixed admin login session handling
- ✅ Added Student Management page (`/admin/users.php`)
- ✅ Added Add Student page (`/admin/add_student.php`)
- ✅ Fixed logout redirect to homepage
- ✅ Added validation functions to admin header
- ✅ Created test student account

---

## 📄 License & Attribution

This project is provided as-is. Always maintain backups of your data.

**Repository:** https://github.com/element-bendr/studentsproject  
**Contact:** element-bendr (GitHub)

---

**Good luck! 🚀 Choose the update method that works best for you.**

**Questions?** Re-read the relevant section above or check the GitHub Issues page.
