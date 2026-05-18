# Click & Collect

A local food marketplace connecting customers with nearby vendors for order-ahead pickup.

Built with Laravel 11 + Oracle Database + Tailwind CSS.

---

## Prerequisites

- PHP 8.4+
- Composer 2+
- Node.js 20+
- Oracle Database (or a compatible Oracle instance)
- Oracle Instant Client (for PHP OCI8 extension)

---

## Clone & Install

```bash
git clone https://github.com/Shivrajtimilsena/Click_And_Collect.git
cd Click_And_Collect
```

### Backend

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Run migrations and seeders:

```bash
php artisan migrate
php artisan db:seed
```

### Frontend

```bash
npm install
npm run build
```

### Run

```bash
php artisan serve
```

Visit `http://localhost:8000`.

---

## Key Features

- **Multi-vendor marketplace** — vendors manage their own shops, products, and inventory
- **Collection slots** — global time-slot booking system (3 slots/day × 20 capacity)
- **Flash deals** — time-limited discounts set by vendors
- **Trader portal** — dashboard, order management, inventory, and shop settings
- **RFID scanning** — order handover at pickup via serial number scanning
- **Oracle Database** — production-grade relational storage

---

## Project Structure

```
app/
  Http/Controllers/   — Web & API controllers
  Models/             — Eloquent models (Oracle compat)
database/
  migrations/         — Schema migrations
  seeders/            — Sample data seeders
resources/
  views/              — Blade templates
routes/
  web.php             — Web routes
  api.php             — API routes
```
