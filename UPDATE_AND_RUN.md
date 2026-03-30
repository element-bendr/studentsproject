# Update & Run Guide

Pull the latest changes from GitHub and run the project with Docker.

---

## If you already have the project on your machine

```bash
# 1. Go into the project folder
cd studentsproject

# 2. Make sure the remote is set (only need to do this once)
git remote add origin https://github.com/element-bendr/studentsproject.git
# If you get "remote origin already exists", skip the line above

# 3. Save any local changes you've made (optional but safe)
git stash

# 4. Pull the latest changes
git pull origin main

# 5. Restore your local changes on top (skip if you didn't stash)
git stash pop

# 6. Rebuild and start the containers
docker compose down
docker compose up -d --build

# 7. Open in browser
# http://localhost:8080
```

---

## If you're setting it up fresh (first time)

```bash
# 1. Clone the repo
git clone https://github.com/element-bendr/studentsproject.git
cd studentsproject

# 2. Start the containers
docker compose up -d --build

# 3. Open in browser
# http://localhost:8080
```

---

## Useful commands

```bash
# Check containers are running
docker ps

# View logs if something is wrong
docker compose logs web
docker compose logs db

# Stop the project
docker compose down

# Stop and wipe the database (fresh start)
docker compose down -v
```

---

## Default login credentials

| Role    | Email                                 | Password               |
| ------- | ------------------------------------- | ---------------------- |
| Admin   | admin@example.com                     | _(set on first login)_ |
| Student | _(register at /student/register.php)_ | _(your password)_      |
