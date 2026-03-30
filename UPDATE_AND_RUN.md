# Update & Run Guide

Pull the latest changes from GitHub and run the project with Docker.

---

## If you already have the project on your machine

```bash
# 1. Go into the project folder
cd studentsproject

# 2. Save any local changes you've made (optional but safe)
git stash

# 3. Pull the latest changes
git pull origin main

# 4. Restore your local changes on top (skip if you didn't stash)
git stash pop

# 5. Rebuild and start the containers
docker compose down
docker compose up -d --build

# 6. Open in browser
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

| Role    | Email                         | Password   |
|---------|-------------------------------|------------|
| Admin   | admin@example.com             | *(set on first login)* |
| Student | *(register at /student/register.php)* | *(your password)* |
