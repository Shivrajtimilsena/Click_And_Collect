# Local Setup Guide — Click & Collect

This guide explains how to set up the Click & Collect project locally.

---

## 1. Prerequisites

Install locally:

- PHP 8.2+
- Composer
- Node.js + npm
- Laravel Herd / PHP runtime
- Oracle Database (local)
- Oracle Instant Client / OCI8 for PHP
- Visual Studio Code (recommended)
- Oracle SQL Developer extension for VS Code (recommended for browsing schema/data)

---

## 2. Clone the repository

```bash
git clone <YOUR-REPO-URL>
cd clickandcollect
```

---

## 3. Install dependencies

```bash
composer install
npm install
```

---

## 4. Create your local `.env`

Copy `.env.example` to `.env`

Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Then edit `.env` and set your own Oracle values.

### Example Oracle section

```env
APP_NAME=ClickAndCollect
APP_ENV=local
APP_DEBUG=true
APP_URL=http://clickandcollect.test

DB_CONNECTION=oracle
DB_HOST=192.168.10.100
DB_PORT=1521
DB_SERVICE_NAME=XEPDB1
DB_DATABASE=
DB_USERNAME=CC_APP
DB_PASSWORD=App@123
DB_CHARSET=AL32UTF8

CACHE_STORE=file
SESSION_DRIVER=file
QUEUE_CONNECTION=sync
```

> Update these values to match your own local Oracle environment if different.

---

## 5. Prepare local Oracle schema

Create or use a local Oracle schema:

```text
Schema/User: CC_APP
```

This schema is where all project tables and data will live.

---

## 6. Run migrations

From the project root:

```bash
php artisan config:clear
php artisan migrate --database=oracle
```

This creates the full local database schema using the migration files in:

```text
database/migrations/
```

---

## 7. Seed sample data

```bash
php artisan db:seed --database=oracle
```

This inserts the pilot sample data required for development and testing.

### If you want a full reset later

```bash
php artisan migrate:fresh --database=oracle --seed
```

---

## 8. Verify the schema and data

You can inspect the database using:

- Oracle SQL Developer for VS Code
- SQL Developer / SQLcl / SQLTools
- APEX SQL Workshop

Useful query:

```sql
SELECT table_name
FROM user_tables
ORDER BY table_name;
```

Useful data checks:

```sql
SELECT * FROM users;
SELECT * FROM shops;
SELECT * FROM product_categories;
SELECT * FROM products;
```

Optional helper queries are available in:

```text
database/sql/click_collect_dev_queries.sql
```

---

## 9. Run the Laravel app

If using Herd, open the local site as configured.

If needed:

```bash
npm run dev
```

---

## 10. Import APEX applications

If APEX application export files are included, import them into your local APEX workspace.

Expected location:

```text
apex/applications/
```

### Important

- Import the **application export SQL files** for the Trader/Admin dashboards.
- If a workspace export is included, remember it only contains workspace metadata/users/groups and **not** the database schema objects or the applications themselves.
- If the application export was created with **Supporting Object Definitions**, some additional DB objects or seed/supporting SQL may also be included in the app export.

---

## 11. Local APEX workspace requirements

Use a local APEX workspace that is mapped to the Oracle schema:

```text
CC_APP
```

That way the imported Trader/Admin apps will use the same local tables that Laravel created.

---

## 12. Development workflow

### When schema changes are added
Run:

```bash
php artisan migrate --database=oracle
```

### When seed/sample data changes
Run:

```bash
php artisan db:seed --database=oracle
```

### When you want a full reset
Run:

```bash
php artisan migrate:fresh --database=oracle --seed
```

---

## 13. Notes for APEX migration

- Application export/import is the main way to move APEX apps between environments.
- Workspace export/import is useful for workspace-level metadata but does **not** replace application export/import or schema migration.
- If the imported app expects tables/packages/views, they must already exist in the local schema before testing the imported app.

---

## 14. Troubleshooting

### Laravel cannot connect to Oracle
- Check `.env`
- Check Oracle listener / DB status
- Confirm `CC_APP` credentials

### Migrations fail
- Verify migration file order
- Check Oracle-specific foreign key rules
- Re-run with:

```bash
php artisan migrate:fresh --database=oracle
```

### Seeders fail
- Ensure all migrations ran first
- Verify any dependent lookup/category/shop rows exist

### APEX app import works but app errors on run
- Check whether all expected schema objects exist in `CC_APP`
- Check supporting objects / plug-ins / static files from the source environment
