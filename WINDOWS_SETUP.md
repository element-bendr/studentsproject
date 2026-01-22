# 🪟 Windows Setup Guide for Student Academy Portal

**Complete step-by-step guide for Windows 10/11 users**

---

## 📋 Prerequisites Check

Before you start, your Windows machine needs:
- ✅ Windows 10 Pro/Enterprise/Education OR Windows 11 (any version)
- ✅ 4GB RAM minimum (8GB recommended)
- ✅ Git installed (or use GitHub Desktop)

---

## Step 1️⃣: Install Docker Desktop

### Download Docker Desktop

1. Go to: **https://www.docker.com/products/docker-desktop**
2. Click **"Download for Windows"**
3. Save the `.exe` file to your Downloads folder

### Install Docker Desktop

1. **Find the installer** in your Downloads folder
2. **Double-click** `Docker Desktop Installer.exe`
3. Windows may ask: **"Do you want to allow this app to make changes to your device?"**
   - Click **Yes**

4. **Follow the installation wizard:**
   - Accept the license agreement
   - **Important:** Make sure **WSL 2** is selected (recommended)
   - Click **Install**

5. **Restart your computer** when prompted
   - This is essential! Do not skip this step.

### Verify Installation

1. **Open Command Prompt or PowerShell** (search "cmd" or "PowerShell")
2. Type this command:
   ```bash
   docker --version
   ```
3. You should see output like: `Docker version 24.0.0, build 12345`

**If you see an error:**
- Make sure Docker Desktop is running (see next step)
- Try restarting your computer again

---

## Step 2️⃣: Start Docker Desktop

### Launch Docker Desktop

1. **Press Windows Key** (⊞ key)
2. **Type:** `Docker`
3. Click **Docker Desktop** to launch it

### Wait for Docker to Start

1. Look at **bottom-right corner** of your screen (system tray)
2. Look for **whale icon** 🐳
3. **Wait 30-60 seconds** for Docker to fully start
4. Whale icon should be **stable** (not animated)

### Verify Docker is Running

```bash
docker ps
```

Should show something like:
```
CONTAINER ID   IMAGE     COMMAND   CREATED   STATUS    PORTS     NAMES
```

**If you get an error**, Docker isn't running yet. Wait another 30 seconds and try again.

---

## Step 3️⃣: Clone the Repository

### Using Git Bash or Command Prompt

```bash
# Navigate to a folder where you want the project
cd Documents

# Clone the repository
git clone https://github.com/element-bendr/studentsproject.git

# Enter the project folder
cd studentsproject
```

### Or Use GitHub Desktop

1. Go to: https://github.com/element-bendr/studentsproject
2. Click **Code** button (green)
3. Click **Open with GitHub Desktop**
4. Choose where to save the project
5. Open in Command Prompt from GitHub Desktop

---

## Step 4️⃣: Create Environment File

### In Command Prompt/PowerShell

```bash
# Make sure you're in the studentsproject folder
cd studentsproject

# Copy the environment file
copy .env.example .env
```

### What This Does

Creates a file called `.env` with database configuration. You can use the default values for development.

---

## Step 5️⃣: Start the Application

### Run Docker Compose

```bash
docker-compose up --build
```

### Wait for Startup

You'll see messages like:

```
Creating studentsproject-db-1 ...
Creating studentsproject-web-1 ...
[notice] Apache/2.4.x started
db is ready for connections
```

**This takes 1-3 minutes on first run.** Be patient! ⏳

### Success Indicators

You'll see these messages:
```
✓ db is ready for connections
✓ Apache/2.4.x started
✓ Health check passed
```

---

## Step 6️⃣: Access the Application

### Open Your Browser

Once Docker is running, open your browser and go to:

**http://localhost:8080**

You should see the **Student Academy Portal** home page!

### Navigate to Different Sections

| Section | URL |
|---------|-----|
| **Home** | http://localhost:8080 |
| **Admin Login** | http://localhost:8080/admin/login.php |
| **Student Login** | http://localhost:8080/student/login.php |
| **About** | http://localhost:8080/public/about.html |
| **Courses** | http://localhost:8080/public/courses.html |

---

## 👥 Test the Application

### Login as Admin

1. Go to: http://localhost:8080/admin/login.php
2. Email: `admin@example.com`
3. Password: `Admin@123`
4. You should see the **Admin Dashboard**

### Login as Student

1. Go to: http://localhost:8080/student/login.php
2. Email: `test.student@example.com`
3. Password: `Admin@123`
4. You should see the **Student Dashboard**

### Test Contact Form

1. Go to: http://localhost:8080/public/contact.html
2. Fill in the form
3. Submit
4. Should show success message

---

## ⚙️ Common Windows Issues

### ❌ Error: Docker Desktop Not Running

**Error Message:**
```
error during connect: Get "http://%2F%2F.%2Fpipe%2FdockerDesktopLinuxEngine":
open //./pipe/dockerDesktopLinuxEngine: The system cannot find the file specified
```

**Fix:**
1. Open Docker Desktop application (search Windows key for "Docker")
2. Wait for whale icon to appear in system tray (bottom-right)
3. Wait 30-60 seconds for it to fully start
4. Try the docker command again

### ❌ Error: Docker Not Installed

**Error Message:**
```
'docker' is not recognized as an internal or external command
```

**Fix:**
1. Download Docker Desktop again from: https://www.docker.com/products/docker-desktop
2. Run the installer and restart your computer
3. Open Docker Desktop from Start Menu
4. Try again

### ❌ Error: WSL 2 or Hyper-V Not Enabled

**Error Message:**
```
Docker Desktop requires Hyper-V or WSL 2 to be enabled
```

**Fix - Enable WSL 2 (Recommended):**

1. **Open PowerShell as Administrator:**
   - Press Windows Key + X
   - Click **Windows PowerShell (Admin)**
   
2. **Run these commands:**
   ```powershell
   wsl --install
   wsl --set-default-version 2
   ```

3. **Restart your computer**

4. **Open Docker Desktop again**

**Alternative Fix - Enable Hyper-V:**

1. Press **Windows Key + R**
2. Type: `optionalfeatures.exe`
3. Press Enter
4. Check ✓ the box for **Hyper-V**
5. Click OK and restart

### ❌ Error: Port 8080 Already in Use

**Error Message:**
```
bind: address already in use
```

**Fix:**

1. **Find what's using port 8080:**
   ```bash
   netstat -ano | findstr :8080
   ```

2. **Either:**
   - **Stop the application** using that port, OR
   - **Change the Docker port**

3. **To change the Docker port:**
   - Open `docker-compose.yml` in a text editor
   - Find the line: `ports: - "8080:80"`
   - Change to: `ports: - "8081:80"`
   - Save the file
   - Try again: `docker-compose up --build`
   - Access at: http://localhost:8081

### ❌ Error: Cannot Connect to Database

**Error Message:**
```
SQLSTATE[HY000]: General error: 2002 No such file or directory
```

**Fix:**
1. Check that Docker is running (whale icon in system tray)
2. Wait 10 seconds and refresh the browser
3. Check Docker logs:
   ```bash
   docker-compose logs db
   ```
4. If database crashed, restart:
   ```bash
   docker-compose restart db
   ```

---

## 🛑 Stop the Application

### Stop Docker

**Press Ctrl + C** in the Command Prompt window where you ran `docker-compose up`

Or, in another Command Prompt:
```bash
docker-compose down
```

### Restart Later

Just run again from the project folder:
```bash
docker-compose up
```

---

## 🔄 Update & Restart

### If You Make Code Changes

1. **Edit files** in VS Code or your editor
2. **Refresh your browser** (Ctrl + F5)
3. Changes should appear instantly

### If You Change the Database

1. **Stop Docker:** Ctrl + C
2. **Restart:** `docker-compose up`

### Full Reset (Clear Database)

```bash
# Stop and remove everything
docker-compose down -v

# Start fresh
docker-compose up --build
```

---

## 🔒 Change Admin Password

⚠️ **IMPORTANT:** Change the default admin password immediately!

1. Login as admin: http://localhost:8080/admin/login.php
   - Email: `admin@example.com`
   - Password: `Admin@123`

2. Go to: http://localhost:8080/admin/change_password.php

3. Enter:
   - Current password: `Admin@123`
   - New password: (something strong!)
   - Confirm password: (same as new)

4. Click Submit

---

## 📚 Need More Help?

- **Setup Issues:** See [DOCKER_QUICKSTART.md](DOCKER_QUICKSTART.md)
- **Full Documentation:** See [COMPLETE_DOCUMENTATION.md](COMPLETE_DOCUMENTATION.md)
- **Security Info:** See [SECURITY.md](SECURITY.md)
- **Testing Guide:** See [TEST_PLAN.md](TEST_PLAN.md)

---

## ✅ Success Checklist

- [ ] Docker Desktop installed
- [ ] Docker Desktop running (whale icon visible)
- [ ] Repository cloned
- [ ] `.env` file copied
- [ ] `docker-compose up --build` running
- [ ] http://localhost:8080 loads
- [ ] Can login as admin
- [ ] Can login as student
- [ ] Admin password changed

**Congratulations! 🎉 Your Student Academy Portal is ready to use!**

---

**Last Updated:** January 22, 2026 | **For:** Windows 10/11 users
