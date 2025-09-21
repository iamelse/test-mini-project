# Simple Finance App (Laravel 12 + Bootstrap 5 + SQLite + AJAX)

A minimal finance application built with **Laravel 12**, **Bootstrap 5**, **SQLite**, and **AJAX**.  
Currently, it only supports **Chart of Account** and **Journal** features, but they are still **buggy** and under development.

---

## Features

- **Chart of Account**
  - Create, update, delete accounts
  - AJAX-based form submission

- **Journal**
  - Add journal entries
  - AJAX-based form submission
  - **Journal lines cannot be added or modified yet**
  - Basic validation

---

## Tech Stack

- Laravel 12
- Bootstrap 5
- SQLite (default DB)
- AJAX (for Journal entry submission)

---

## Installation

1. Clone this repository:

```bash
git clone https://github.com/iamelse/test-mini-project.git
cd test-mini-project
```

2. Install dependencies:

```bash
composer install
npm install
npm run dev
```

3. Copy `.env.example` to `.env` and set database to SQLite:

```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite
```

4. Create SQLite file:

```bash
touch database/database.sqlite
```

5. Run migrations:

```bash
php artisan migrate
```

6. Serve the application:

```bash
php artisan serve
```

---

## Usage

- Access the app at `http://127.0.0.1:8000`
- Navigate to:
  - **Chart of Account**: `/chart-of-accounts`
  - **Journal**: `/journals`
- Add/edit accounts (Chart of Account)
- **Journal lines cannot be input or updated yet**

---

## Known Issues

- **Journal lines cannot be added or modified**
- Validation is basic, may allow some invalid data
- No authentication yet

---

---

## License

MIT License