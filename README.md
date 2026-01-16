![Dashboard](doc/screenshots/screenshot_1.png)

A web application skeleton built with **Nette Framework**, **Doctrine ORM (Contributte)**, and **Vite**, featuring ready-to-use **user login/registration**, **translations** via **contributte/translation** and **contributte/datagrid**.
This project comes pre-configured with **Bootstrap 5** and **Tabler** for a responsive administration UI.


## Features

- **Stack**: Nette 3.2, PHP 8.2+, Vite.
- **Auth**: Ready-to-use user registration and login.
- **Translations**: Multi-language support via `contributte/translation`.
- **ORM**: Doctrine 2 with Contributte integration.
- **UI**: Tabler Admin Dashboard (Bootstrap 5) for the admin interface.
- **Frontend**: Vite for fast asset building and HMR.
- **Tools**: PHPStan for static analysis, Nette Tester for testing.

## Prerequisites

- **PHP** >= 8.2
- **Node.js** >= 20 (v22 recommended)
- **MySQL** or **MariaDB** database

## Installation

1. **Install via Composer**
   ```bash
   composer create-project martyd420/nette-starter my-project
   cd my-project
   ```

2. **Install Frontend dependencies**
   ```bash
   npm install
   ```

3. **Database Configuration**
   Default configuration requires a database `nsdb` (user: `nettestarter`, pass: `nettestarter123`). Use these credentials or override them in `private/config/local.neon`.


4. **Initialize Database**
   Run the following command to create the schema and load default fixtures (admin user):
   ```bash
   composer db:reset
   ```

5. **Build Assets**
   For production build:
   ```bash
   npm run build
   ```

## Development

### 1. Start Frontend Server (Vite)
This will start the Vite development server (usually on port 5173) with Hot Module Replacement (HMR).
```bash
npm run dev
```

### 2. Start Backend Server
You can use the built-in PHP server or your preferred web server (Apache/Nginx/Docker).
To use the built-in PHP server:
```bash
php -S localhost:8000 -t web
```

Access the application at: `http://localhost:8000`

## Default Credentials

The `composer db:reset` command creates a default administrator account:

- **Email:** `starter@pcdr.cz`
- **Password:** `123456`

## Available Commands

### Composer Scripts
Defined in `composer.json`:

- `composer db:reset`: **Destructive!** Drops the database schema, clears cache, creates new schema, and loads fixtures.
- `composer phpstan`: Runs static analysis on `private/app`.
- `composer tester`: Runs unit/integration tests.

### NPM Scripts
Defined in `package.json`:

- `npm run dev`: Starts the Vite development server.
- `npm run build`: Builds frontend assets for production (`web/assets/`).


![Users Management](doc/screenshots/screenshot_2.png)

![User Edit](doc/screenshots/screenshot_3.png)