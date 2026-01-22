# 🐳 Docker Quick Start - Student Academy Portal

**Get the application running in 3 commands!**

---

## ⚡ Fastest Way to Run (3 Steps)

### Prerequisites: Install Docker Desktop

Before running the application, you must install **Docker Desktop** for your OS:

#### **Windows**
1. Download: https://www.docker.com/products/docker-desktop
2. **Requirements:**
   - Windows 10 Pro/Enterprise/Education (or Windows 11)
   - 4GB RAM minimum (8GB recommended)
   - Hyper-V or WSL 2 enabled

3. **After Installation:**
   - Open Docker Desktop application
   - Wait for the whale icon to appear in system tray (bottom-right)
   - It should say "Docker is running"

4. **Verify Installation:**
   ```bash
   docker --version
   docker-compose --version
   ```

#### **macOS**
1. Download: https://www.docker.com/products/docker-desktop
2. Install and launch Docker Desktop
3. Check system tray (top-right) for whale icon

#### **Linux**
```bash
# Ubuntu/Debian
sudo apt-get update
sudo apt-get install docker.io docker-compose
sudo systemctl start docker
docker --version
```

---

### Step 1: Clone the Repository

```bash
git clone https://github.com/element-bendr/studentsproject.git
cd studentsproject
```

### Step 2: Copy Environment File

```bash
cp .env.example .env
```

The `.env` file contains all Docker configuration:

```env
MYSQL_DATABASE=student_academy
MYSQL_USER=student_user
MYSQL_PASSWORD=change_me
MYSQL_ROOT_PASSWORD=change_me_root
```

**Optional:** Edit `.env` to customize database credentials (but defaults work fine for development).

### Step 3: Start Docker

```bash
docker-compose up --build
```

Wait for the output to show:

```
web      | [notice] Apache/2.4.x started
db       | Ready for connections
```

---

## 🌐 Access the Application

Once Docker is running, open your browser:

| Component               | URL                                                |
| ----------------------- | -------------------------------------------------- |
| **Homepage**            | http://localhost:8080                              |
| **Student Login**       | http://localhost:8080/student/login.php            |
| **Admin Login**         | http://localhost:8080/admin/login.php              |
| **Contact Form**        | http://localhost:8080/public/contact.html          |
| **Appointment Booking** | http://localhost:8080/public/book_appointment.html |

---

## 👥 Default Login Credentials

### Admin Account

```
Email:    admin@example.com
Password: Admin@123
```

### Sample Student Account

```
Email:    test.student@example.com
Password: Admin@123
```

⚠️ **SECURITY:** Change these passwords immediately after first login!

- Admin: http://localhost:8080/admin/change_password.php

---

## 🛑 Stop Docker

```bash
# Stop all containers
docker-compose down

# Stop and remove volumes (clears database)
docker-compose down -v
```

---

## 🔧 Useful Docker Commands

### View Logs

```bash
# All services
docker-compose logs -f

# Only web service
docker-compose logs -f web

# Only database
docker-compose logs -f db
```

### Execute Commands in Container

```bash
# Access bash in web container
docker-compose exec web bash

# Access MySQL CLI in database container
docker-compose exec db mysql -u student_user -pchange_me student_academy
```

### Restart Services

```bash
# Restart web service only
docker-compose restart web

# Rebuild and restart everything
docker-compose up --build
```

### View Running Containers

```bash
docker-compose ps
```

---

## 📁 What Docker Creates

### Containers

- **web**: PHP 8.2 + Apache (port 8080)
- **db**: MySQL 8.0 (port 3306, only accessible from web container)

### Volumes

- **./**: Project files mounted to `/var/www/html` in container
- **./storage**: Study materials storage (persistent)
- **Database data**: Stored in Docker volume (persistent between restarts)

### Network

- **app-network**: Internal Docker network connecting web and db containers

---

## 🐛 Troubleshooting

### ⚠️ WINDOWS USERS: Docker Desktop Not Running

**Error Message:**
```
unable to get image 'studentsproject-web': error during connect: 
Get "http://%2F%2F.%2Fpipe%2FdockerDesktopLinuxEngine": 
open //./pipe/dockerDesktopLinuxEngine: The system cannot find the file specified
```

**Solution:**
1. **Open Docker Desktop Application**
   - Look for Docker icon in Start Menu
   - Or search "Docker Desktop"
   - Click to launch
   - Wait 30-60 seconds for startup

2. **Verify Docker is Running:**
   - Look at system tray (bottom-right corner)
   - Should see whale icon
   - Hover over it - should say "Docker is running"

3. **Test Docker Command:**
   ```bash
   docker --version
   ```
   - Should show version number (e.g., "Docker version 24.0.0")
   - If it doesn't work, Docker Desktop is not running

4. **Then Try Again:**
   ```bash
   docker-compose up --build
   ```

### Windows: Docker Desktop Not Installed

**Error:** `'docker' is not recognized as an internal or external command`

**Solution:**
1. Download Docker Desktop: https://www.docker.com/products/docker-desktop
2. Run the installer (.exe file)
3. Follow the installation wizard
4. **Restart your computer** when prompted
5. Open Docker Desktop from Start Menu
6. Wait for whale icon to appear in system tray
7. Try again: `docker-compose up --build`

### Windows: Hyper-V or WSL 2 Not Enabled

**Error:** `Docker Desktop requires Hyper-V or WSL 2 to be enabled`

**Solution (Enable WSL 2 - Recommended):**

1. Open PowerShell **as Administrator**
2. Run these commands:
   ```powershell
   wsl --install
   wsl --set-default-version 2
   ```
3. Restart your computer
4. Open Docker Desktop again

**Solution (Enable Hyper-V - Alternative):**

1. Press `Windows Key + R`
2. Type: `optionalfeatures.exe`
3. Check ✓ "Hyper-V"
4. Click OK and restart

### Windows: Port 8080 Already in Use

**Error:** `bind: address already in use`

**Solution:**
1. Find what's using port 8080:
   ```bash
   netstat -ano | findstr :8080
   ```
2. Either:
   - **Stop the other application**, OR
   - **Change Docker port** in `docker-compose.yml`:
     ```yaml
     ports:
       - "8081:80"  # Changed from 8080 to 8081
     ```
   Then access: http://localhost:8081

### Windows: Cannot Delete Docker Container

**Error:** `Error response from daemon: You cannot remove a running container`

**Solution:**
```bash
# Stop the container first
docker-compose down

# Then start fresh
docker-compose up --build
```

### Issue: Port 8080 Already in Use

```bash
# Change port in docker-compose.yml
# Find: ports: - "8080:80"
# Change to: ports: - "8081:80"
# Then access: http://localhost:8081
```

### Issue: Database Connection Error

```bash
# Wait 10 seconds for MySQL to fully start, then refresh browser
# Or check logs:
docker-compose logs db

# Restart database service:
docker-compose restart db
```

### Issue: Files Not Updating

```bash
# Files are live-mounted; refresh browser (Ctrl+F5 for hard refresh)
# If CSS/JS cached, clear browser cache or open in incognito mode
```

### Issue: Cannot Access Database

```bash
# Database is only accessible from inside the web container
# To access locally, use:
docker-compose exec db mysql -u student_user -pchange_me student_academy

# Or use PHP container:
docker-compose exec web php -r "
  \$pdo = new PDO('mysql:host=db;dbname=student_academy', 'student_user', 'change_me');
  \$stmt = \$pdo->query('SELECT COUNT(*) as count FROM users');
  echo 'Users: ' . \$stmt->fetch()['count'];
"
```

### Issue: Database Not Initialized

```bash
# The schema.sql should auto-run on first startup
# If not, manually import:
docker-compose exec db mysql -u student_user -pchange_me student_academy < schema.sql
```

---

## 📦 Docker Compose Services

### Web Service (PHP + Apache)

```yaml
web:
  build: . # Uses Dockerfile
  ports:
    - "8080:80" # Host:Container port mapping
  environment:
    - DB_HOST=db # Database hostname
    - MYSQL_DATABASE=student_academy
    - MYSQL_USER=student_user
    - MYSQL_PASSWORD=change_me
  depends_on:
    - db # Wait for db before starting
  volumes:
    - ./:/var/www/html:cached # Project files mounted
    - ./storage:/var/www/html/storage # Persistent uploads
```

### Database Service (MySQL)

```yaml
db:
  image: mysql:8.0
  environment:
    - MYSQL_DATABASE=student_academy
    - MYSQL_USER=student_user
    - MYSQL_PASSWORD=change_me
    - MYSQL_ROOT_PASSWORD=change_me_root
  volumes:
    - db_data:/var/lib/mysql # Persistent database
```

---

## 🔄 Development Workflow

### Making Changes

1. **Edit files** in your local editor (VS Code, etc.)
2. **Files auto-sync** to Docker container
3. **Refresh browser** to see changes
4. **For PHP changes**: Automatic (no restart needed)
5. **For database changes**: Might need container restart:
   ```bash
   docker-compose restart web
   ```

### Database Changes

Edit `schema.sql`, then:

```bash
# Restart db container
docker-compose restart db

# Reimport schema (if needed):
docker-compose exec db mysql -u student_user -pchange_me student_academy < schema.sql
```

---

## 📊 Container Architecture

```
┌─────────────────────────────────────────────┐
│          Docker Compose Network             │
│           (app-network)                     │
└─────────────────────────────────────────────┘
         │                      │
    ┌────▼────┐            ┌────▼────┐
    │   web   │            │    db   │
    │         │            │         │
    │ PHP 8.2 │◄──connect──┤ MySQL 8 │
    │ Apache  │            │         │
    │         │            │         │
    └────┬────┘            └────┬────┘
         │                      │
    ┌────▼────┐            ┌────▼────┐
    │ Port    │            │ Volumes │
    │ 8080    │            │ db_data │
    │         │            │ storage │
    └─────────┘            └─────────┘
```

---

## ✅ Verify Installation

### Quick Health Check

```bash
# All services running?
docker-compose ps

# Can access web?
curl http://localhost:8080

# Can access MySQL?
docker-compose exec db mysql -u student_user -pchange_me student_academy -e "SELECT COUNT(*) FROM users;"
```

### Database Verification

```bash
# Check tables created
docker-compose exec db mysql -u student_user -pchange_me student_academy -e "SHOW TABLES;"

# Output should show:
# +-----------------------+
# | Tables_in_student_academy |
# +-----------------------+
# | admins                |
# | appointments          |
# | attendance            |
# | enquiries             |
# | notices               |
# | uploads               |
# | users                 |
# +-----------------------+
```

---

## 🔐 Security Notes

### For Development Only

- Default credentials are for development only
- `.env` file is git-ignored (never commit passwords)
- Database is NOT exposed to external network

### For Production Deployment

- Change `MYSQL_PASSWORD` and `MYSQL_ROOT_PASSWORD` in `.env`
- Use strong passwords (20+ characters, mixed case + numbers + special)
- Set up SSL/TLS certificates
- Use environment-specific configurations
- Never commit `.env` file to git
- Set up proper backup strategy

---

## 🆘 Need Help?

### Check Logs

```bash
# Full logs
docker-compose logs

# Last 50 lines
docker-compose logs --tail 50

# Follow in real-time
docker-compose logs -f
```

### Rebuild Everything

```bash
# Clean build (removes cached layers)
docker-compose up --build --force-recreate

# Full reset (removes containers and volumes)
docker-compose down -v
docker-compose up --build
```

### Container Shell Access

```bash
# Access web server bash
docker-compose exec web bash

# Then can run PHP directly:
php -v
curl http://localhost/index.php
```

---

## 📚 Documentation

- **Setup Issues**: See [DEPLOYMENT_GUIDE.md](DEPLOYMENT_GUIDE.md)
- **Architecture**: See [COMPLETE_DOCUMENTATION.md](COMPLETE_DOCUMENTATION.md)
- **Security**: See [SECURITY.md](SECURITY.md)
- **Testing**: See [TEST_PLAN.md](TEST_PLAN.md)

---

## 🎉 Ready to Go!

Your Student Academy Portal is now running!

**Next Steps:**

1. ✅ Login as admin (admin@example.com / Admin@123)
2. ✅ Change admin password
3. ✅ Explore student portal
4. ✅ Test appointment booking
5. ✅ Review uploaded study materials

---

**Questions?** Check the [COMPLETE_DOCUMENTATION.md](COMPLETE_DOCUMENTATION.md) or [README.md](README.md) for detailed information.

**Last Updated:** January 20, 2026 | **Status:** ✅ Production Ready
