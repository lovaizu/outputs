# chee-portfolio

WordPress FSE (Full Site Editing) block theme for Chee Design portfolio site.

## Requirements

| Tool | Version |
|------|---------|
| Colima | latest |
| Docker | latest |
| docker-compose | v2 |
| WP-CLI | bundled in `cli` container |

## Quick Start

### 1. Start Colima + containers

```bash
colima start
cd wp-dev
docker compose up -d
```

### 2. Install WordPress core

```bash
docker compose run --rm cli wp core install \
  --url=http://localhost:8080 \
  --title="Chee Portfolio" \
  --admin_user=admin \
  --admin_password=admin \
  --admin_email=admin@example.com \
  --allow-root
```

### 3. Activate the theme

```bash
docker compose run --rm cli wp theme activate chee-portfolio --allow-root
```

### 4. Install and activate plugins

```bash
docker compose run --rm cli wp plugin install pods meta-field-block fluentform --activate --allow-root
```

### 5. Open in browser

- Site: http://localhost:8080
- Admin: http://localhost:8080/wp-admin (admin / admin)

## Directory Layout

```
chee-portfolio/
├── wp-dev/
│   ├── docker-compose.yml   # MariaDB + WordPress + WP-CLI
│   └── .gitignore
├── theme/                   # Mounted as wp-content/themes/chee-portfolio/
│   ├── style.css
│   ├── theme.json
│   ├── functions.php
│   ├── index.php
│   ├── parts/
│   ├── patterns/
│   ├── templates/
│   ├── assets/
│   │   ├── fonts/
│   │   ├── images/
│   │   └── js/vendor/
│   └── e2e/                 # Playwright tests
└── input/                   # Design source files (read-only)
```

## Stopping and Restarting

```bash
# Stop containers (data preserved in volumes)
docker compose down

# Stop and delete all data
docker compose down -v

# Resume from stopped
colima start
cd wp-dev && docker compose up -d
```

## Theme Development

The `theme/` directory is bind-mounted into the container at runtime. PHP and JSON changes are reflected immediately; no rebuild needed.

## TODO

- [ ] Task 2: theme.json tokens, functions.php CPT registration, Pods field groups
- [ ] Task 3: fixture data
- [ ] Task 4: template parts (header / footer)
- [ ] Task 5: page templates
- [ ] Task 6: block patterns × 8
- [ ] Task 7: styles + JS
- [ ] Task 8: code review
- [ ] Task 9: CI/CD (GHA deploy workflow + Playwright)
